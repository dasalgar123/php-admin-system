<?php
require_once 'config/database.php';
?>
<link rel="stylesheet" href="css/clientes.css">
<script src="js/clientes.js"></script>
<?php

// Procesar acciones
$action = isset($_GET['action']) ? $_GET['action'] : '';
$client_id = isset($_GET['id']) ? $_GET['id'] : '';

// Procesar formularios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                try {
                    $stmt = $pdo->prepare("INSERT INTO cliente (nombre, correo, contraseña, rol) VALUES (?, ?, ?, ?)");
                    $stmt->execute([
                        $_POST['nombre'],
                        $_POST['correo'],
                        $_POST['contraseña'],
                        'cliente'
                    ]);
                    $success_message = "Cliente agregado exitosamente";
                } catch(PDOException $e) {
                    $error_message = "Error al agregar cliente: " . $e->getMessage();
                }
                break;
                
            case 'edit':
                try {
                    $stmt = $pdo->prepare("UPDATE cliente SET nombre = ?, correo = ?, contraseña = ? WHERE id = ?");
                    $stmt->execute([
                        $_POST['nombre'],
                        $_POST['correo'],
                        $_POST['contraseña'],
                        $_POST['id']
                    ]);
                    $success_message = "Cliente actualizado exitosamente";
                } catch(PDOException $e) {
                    $error_message = "Error al actualizar cliente: " . $e->getMessage();
                }
                break;
                
            case 'delete':
                try {
                    $stmt = $pdo->prepare("DELETE FROM cliente WHERE id = ?");
                    $stmt->execute([$_POST['id']]);
                    $success_message = "Cliente eliminado exitosamente";
                } catch(PDOException $e) {
                    $error_message = "Error al eliminar cliente: " . $e->getMessage();
                }
                break;
                
            case 'change_status':
                try {
                    $stmt = $pdo->prepare("UPDATE cliente SET rol = ? WHERE id = ?");
                    $stmt->execute([$_POST['estado'], $_POST['id']]);
                    $success_message = "Estado del cliente actualizado exitosamente";
                } catch(PDOException $e) {
                    $error_message = "Error al actualizar estado: " . $e->getMessage();
                }
                break;
        }
    }
}

// Configuración de filtros
$search = isset($_GET['search']) ? $_GET['search'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';

// Obtener clientes de la base de datos
try {
    $where_conditions = [];
    $params = [];
    
    if (!empty($search)) {
        $where_conditions[] = "(nombre LIKE ? OR correo LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    
    if ($status_filter !== 'all') {
        $where_conditions[] = "rol = ?";
        $params[] = $status_filter;
    }
    
    $where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';
    
    $sql = "SELECT * FROM cliente $where_clause ORDER BY nombre";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $clients = $stmt->fetchAll();
    
} catch(PDOException $e) {
    $error = "Error al cargar clientes: " . $e->getMessage();
    $clients = [];
}

// Estadísticas
$total_clients = count($clients);
$active_clients = count(array_filter($clients, fn($c) => $c['rol'] === 'cliente'));
$inactive_clients = count(array_filter($clients, fn($c) => $c['rol'] === 'inactivo'));
$pending_clients = count(array_filter($clients, fn($c) => $c['rol'] === 'pendiente'));
$suspended_clients = count(array_filter($clients, fn($c) => $c['rol'] === 'suspendido'));

// Función para generar iniciales del cliente
$getClientInitials = function($name) {
    $words = explode(' ', $name);
    $initials = '';
    foreach ($words as $word) {
        $initials .= strtoupper(substr($word, 0, 1));
    }
    return substr($initials, 0, 2);
};

// Función para formatear fecha
$formatDate = function($date) {
    if (!$date) return 'N/A';
    return date('d/m/Y', strtotime($date));
};

// Función para obtener clase CSS del estado
$getStatusClass = function($rol) {
    switch($rol) {
        case 'cliente': return 'status-active';
        case 'inactivo': return 'status-inactive';
        case 'pendiente': return 'status-pending';
        case 'suspendido': return 'status-inactive';
        default: return 'status-inactive';
    }
};
?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle"></i>
        <?php echo $error; ?>
    </div>
<?php endif; ?>

<?php if (isset($error_message)): ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle"></i>
        <?php echo $error_message; ?>
    </div>
<?php endif; ?>

<?php if (isset($success_message)): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <?php echo $success_message; ?>
    </div>
<?php endif; ?>

<!-- Estadísticas de clientes -->
<div class="product-stats">
    <div class="stat-item">
        <div class="stat-number"><?php echo $total_clients; ?></div>
        <div class="stat-label">Total Clientes</div>
    </div>
    <div class="stat-item">
        <div class="stat-number"><?php echo $active_clients; ?></div>
        <div class="stat-label">Clientes Activos</div>
    </div>
    <div class="stat-item">
        <div class="stat-number"><?php echo $inactive_clients; ?></div>
        <div class="stat-label">Clientes Inactivos</div>
    </div>
    <div class="stat-item">
        <div class="stat-number"><?php echo $pending_clients; ?></div>
        <div class="stat-label">Pendientes</div>
    </div>
    <div class="stat-item">
        <div class="stat-number"><?php echo $suspended_clients; ?></div>
        <div class="stat-label">Suspendidos</div>
    </div>
</div>

<!-- Formulario para agregar/editar cliente -->
<?php if ($action === 'add' || $action === 'edit'): ?>
    <?php 
    $client = null;
    if ($action === 'edit' && $client_id) {
        $client = $clienteController->getClientById($client_id);
        if (!$client) {
            $error_message = "Error al cargar cliente: Cliente no encontrado";
        }
    }
    ?>
    
    <div class="card">
        <div class="card-header">
            <h3><?php echo $action === 'add' ? 'Agregar Nuevo Cliente' : 'Editar Cliente'; ?></h3>
            <a href="?page=clientes" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Volver
            </a>
        </div>
        <div class="card-body">
            <form method="POST" class="client-form">
                <input type="hidden" name="action" value="<?php echo $action; ?>">
                <?php if ($action === 'edit' && $client): ?>
                    <input type="hidden" name="id" value="<?php echo $client['id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required 
                           value="<?php echo htmlspecialchars($client['nombre'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="correo">Correo Electrónico:</label>
                    <input type="email" id="correo" name="correo" 
                           value="<?php echo htmlspecialchars($client['correo'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="contraseña">Contraseña:</label>
                    <input type="password" id="contraseña" name="contraseña" 
                           <?php echo $action === 'add' ? 'required' : ''; ?>
                           placeholder="<?php echo $action === 'edit' ? 'Dejar vacío para mantener la actual' : ''; ?>">
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        <?php echo $action === 'add' ? 'Agregar Cliente' : 'Actualizar Cliente'; ?>
                    </button>
                    <a href="?page=clientes" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

<?php else: ?>
    <!-- Lista de clientes -->
    <div class="card">
        <div class="card-header">
            <div class="filters">
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Buscar clientes..." 
                           value="<?php echo htmlspecialchars($search); ?>"
                           onchange="window.location.href='?page=clientes&search=' + this.value + '&status=<?php echo $status_filter; ?>'">
                </div>
                
                <select class="filter-select" onchange="window.location.href='?page=clientes&search=<?php echo urlencode($search); ?>&status=' + this.value">
                    <option value="all" <?php echo $status_filter === 'all' ? 'selected' : ''; ?>>Todos los estados</option>
                    <option value="cliente" <?php echo $status_filter === 'cliente' ? 'selected' : ''; ?>>Activos</option>
                    <option value="inactivo" <?php echo $status_filter === 'inactivo' ? 'selected' : ''; ?>>Inactivos</option>
                    <option value="pendiente" <?php echo $status_filter === 'pendiente' ? 'selected' : ''; ?>>Pendientes</option>
                    <option value="suspendido" <?php echo $status_filter === 'suspendido' ? 'selected' : ''; ?>>Suspendidos</option>
                </select>
            </div>
            
            <a href="?page=clientes&action=add" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Nuevo Cliente
            </a>
        </div>
        
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($clients)): ?>
                    <tr>
                        <td colspan="4" class="text-center">
                            <div style="padding: 2rem; color: var(--secondary-color);">
                                <i class="fas fa-user-tie" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>
                                No se encontraron clientes
                            </div>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($clients as $client): ?>
                        <tr>
                            <td>
                                <div class="client-info">
                                    <div class="client-avatar">
                                        <?php echo $clienteController->getClientInitials($client['nombre']); ?>
                                    </div>
                                    <div>
                                        <div class="client-name"><?php echo htmlspecialchars($client['nombre']); ?></div>
                                        <div class="client-id">ID: <?php echo $client['id']; ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="contact-info">
                                    <div class="contact-email"><?php echo htmlspecialchars($client['correo'] ?? 'Sin correo'); ?></div>
                                </div>
                            </td>
                            <td>
                                <span class="client-status <?php echo $clienteController->getStatusClass($client['rol']); ?>">
                                    <?php echo ucfirst(htmlspecialchars($client['rol'])); ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="?page=clientes&action=edit&id=<?php echo $client['id']; ?>" 
                                       class="btn btn-primary btn-sm action-btn" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="#" onclick="suspendClient(<?php echo $client['id']; ?>, '<?php echo htmlspecialchars($client['nombre']); ?>')" 
                                       class="btn btn-warning btn-sm action-btn" title="Suspender">
                                        <i class="fas fa-ban"></i>
                                    </a>
                                    <a href="#" onclick="confirmDelete(<?php echo $client['id']; ?>, '<?php echo htmlspecialchars($client['nombre']); ?>')" 
                                       class="btn btn-danger btn-sm action-btn" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="table-footer">
            <div class="table-info">
                Mostrando <?php echo $total_clients; ?> clientes
            </div>
        </div>
    </div>
<?php endif; ?>



 