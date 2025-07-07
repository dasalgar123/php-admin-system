<?php
require_once 'config/database.php';
?>
<link rel="stylesheet" href="css/usuarios.css">
<script src="js/usuarios.js"></script>
<?php

// Procesar acciones
$action = isset($_GET['action']) ? $_GET['action'] : '';
$user_id = isset($_GET['id']) ? $_GET['id'] : '';

// Procesar formularios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                try {
                    $stmt = $pdo->prepare("INSERT INTO usuario (nombre, correo, contraseña, rol) VALUES (?, ?, ?, ?)");
                    $stmt->execute([
                        $_POST['nombre'],
                        $_POST['correo'],
                        $_POST['contraseña'],
                        $_POST['rol']
                    ]);
                    $success_message = "Usuario agregado exitosamente";
                } catch(PDOException $e) {
                    $error_message = "Error al agregar usuario: " . $e->getMessage();
                }
                break;
                
            case 'edit':
                try {
                    $stmt = $pdo->prepare("UPDATE usuario SET nombre = ?, correo = ?, contraseña = ?, rol = ? WHERE id = ?");
                    $stmt->execute([
                        $_POST['nombre'],
                        $_POST['correo'],
                        $_POST['contraseña'],
                        $_POST['rol'],
                        $_POST['id']
                    ]);
                    $success_message = "Usuario actualizado exitosamente";
                } catch(PDOException $e) {
                    $error_message = "Error al actualizar usuario: " . $e->getMessage();
                }
                break;
                
            case 'delete':
                try {
                    $stmt = $pdo->prepare("DELETE FROM usuario WHERE id = ?");
                    $stmt->execute([$_POST['id']]);
                    $success_message = "Usuario eliminado exitosamente";
                } catch(PDOException $e) {
                    $error_message = "Error al eliminar usuario: " . $e->getMessage();
                }
                break;
        }
    }
}

// Configuración de filtros
$search = isset($_GET['search']) ? $_GET['search'] : '';
$role_filter = isset($_GET['role']) ? $_GET['role'] : 'all';

// Obtener usuarios de la base de datos
try {
    $where_conditions = [];
    $params = [];
    
    if (!empty($search)) {
        $where_conditions[] = "(nombre LIKE ? OR correo LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    
    if ($role_filter !== 'all') {
        $where_conditions[] = "rol = ?";
        $params[] = $role_filter;
    }
    
    $where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';
    
    $sql = "SELECT * FROM usuario $where_clause ORDER BY nombre";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $users = $stmt->fetchAll();
    
} catch(PDOException $e) {
    $error = "Error al cargar usuarios: " . $e->getMessage();
    $users = [];
}

// Estadísticas
$total_users = count($users);
$admin_users = count(array_filter($users, fn($u) => $u['rol'] === 'administrador'));
$regular_users = count(array_filter($users, fn($u) => $u['rol'] !== 'administrador'));

// Función para generar iniciales del usuario
$getUserInitials = function($name) {
    $words = explode(' ', $name);
    $initials = '';
    foreach ($words as $word) {
        $initials .= strtoupper(substr($word, 0, 1));
    }
    return substr($initials, 0, 2);
};

// Función para enmascarar contraseña
$maskPassword = function($password) {
    return str_repeat('•', min(strlen($password), 8));
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

<!-- Estadísticas de usuarios -->
<div class="product-stats">
    <div class="stat-item">
        <div class="stat-number"><?php echo $total_users; ?></div>
        <div class="stat-label">Total Usuarios</div>
    </div>
    <div class="stat-item">
        <div class="stat-number"><?php echo $admin_users; ?></div>
        <div class="stat-label">Administradores</div>
    </div>
    <div class="stat-item">
        <div class="stat-number"><?php echo $regular_users; ?></div>
        <div class="stat-label">Usuarios Regulares</div>
    </div>
    <div class="stat-item">
        <div class="stat-number">4</div>
        <div class="stat-label">Roles Disponibles</div>
    </div>
</div>

<!-- Botón Agregar Usuario arriba de la tabla -->
<a href="?page=usuarios&action=add" class="btn btn-primary" style="margin-bottom: 1rem;">
    <i class="fas fa-plus"></i> Agregar Usuario
</a>

<!-- Formulario para agregar/editar usuario -->
<?php if ($action === 'add' || $action === 'edit'): ?>
    <?php 
    $user = null;
    if ($action === 'edit' && $user_id) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM usuario WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();
        } catch(PDOException $e) {
            $error_message = "Error al cargar usuario: " . $e->getMessage();
        }
    }
    ?>
    
    <div class="card">
        <div class="card-header">
            <h3><?php echo $action === 'add' ? 'Agregar Nuevo Usuario' : 'Editar Usuario'; ?></h3>
            <a href="?page=usuarios" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Volver
            </a>
        </div>
        <div class="card-body">
            <form method="POST" class="user-form">
                <input type="hidden" name="action" value="<?php echo $action; ?>">
                <?php if ($action === 'edit'): ?>
                    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required 
                           value="<?php echo htmlspecialchars($user['nombre'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="correo">Correo Electrónico:</label>
                    <input type="email" id="correo" name="correo" required 
                           value="<?php echo htmlspecialchars($user['correo'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="contraseña">Contraseña:</label>
                    <input type="password" id="contraseña" name="contraseña" 
                           <?php echo $action === 'add' ? 'required' : ''; ?>
                           placeholder="<?php echo $action === 'edit' ? 'Dejar vacío para mantener la actual' : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="rol">Rol:</label>
                    <select id="rol" name="rol" required>
                        <option value="">Seleccionar rol</option>
                        <option value="administrador" <?php echo ($user['rol'] ?? '') === 'administrador' ? 'selected' : ''; ?>>Administrador</option>
                        <option value="usuario" <?php echo ($user['rol'] ?? '') === 'usuario' ? 'selected' : ''; ?>>Usuario</option>
                        <option value="moderador" <?php echo ($user['rol'] ?? '') === 'moderador' ? 'selected' : ''; ?>>Moderador</option>
                        <option value="editor" <?php echo ($user['rol'] ?? '') === 'editor' ? 'selected' : ''; ?>>Editor</option>
                    </select>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        <?php echo $action === 'add' ? 'Agregar Usuario' : 'Actualizar Usuario'; ?>
                    </button>
                    <a href="?page=usuarios" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

<?php else: ?>
    <!-- Lista de usuarios -->
    <div class="card">
        <div class="card-header">
            <div class="filters">
                <div class="search-box" style="position: relative;">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" id="searchInputUsuarios" placeholder="Buscar usuarios..." 
                           value="<?php echo htmlspecialchars($search); ?>"
                           onchange="window.location.href='?page=usuarios&search=' + this.value + '&role=<?php echo $role_filter; ?>'">
                    <button type="button" id="micBtnUsuarios" style="position: absolute; right: 0.5rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer;">
                        <i class="fas fa-microphone" id="micIconUsuarios"></i>
                    </button>
                </div>
                
                <select class="filter-select" onchange="window.location.href='?page=usuarios&search=<?php echo urlencode($search); ?>&role=' + this.value">
                    <option value="all" <?php echo $role_filter === 'all' ? 'selected' : ''; ?>>Todos los roles</option>
                    <option value="administrador" <?php echo $role_filter === 'administrador' ? 'selected' : ''; ?>>Administrador</option>
                    <option value="usuario" <?php echo $role_filter === 'usuario' ? 'selected' : ''; ?>>Usuario</option>
                    <option value="moderador" <?php echo $role_filter === 'moderador' ? 'selected' : ''; ?>>Moderador</option>
                    <option value="editor" <?php echo $role_filter === 'editor' ? 'selected' : ''; ?>>Editor</option>
                </select>
            </div>
            
            <a href="?page=usuarios&action=add" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Nuevo Usuario
            </a>
        </div>
        
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="4" class="text-center">
                            <div style="padding: 2rem; color: var(--secondary-color);">
                                <i class="fas fa-users" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>
                                No se encontraron usuarios
                            </div>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">
                                        <?php echo $getUserInitials($user['nombre']); ?>
                                    </div>
                                    <div>
                                        <div class="user-name"><?php echo htmlspecialchars($user['nombre']); ?></div>
                                        <div class="user-id">ID: <?php echo $user['id']; ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="email-info">
                                    <div><?php echo htmlspecialchars($user['correo']); ?></div>
                                    <div class="password-mask"><?php echo $maskPassword($user['contraseña']); ?></div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo $user['rol'] === 'administrador' ? 'primary' : ($user['rol'] === 'moderador' ? 'warning' : 'secondary'); ?>">
                                    <?php echo ucfirst(htmlspecialchars($user['rol'])); ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="?page=usuarios&action=edit&id=<?php echo $user['id']; ?>" 
                                       class="btn btn-info btn-sm" data-tooltip="Modificar">
                                        <i class="fas fa-user-edit"></i>
                                    </a>
                                    <button onclick="suspendUser(<?php echo $user['id']; ?>)" 
                                            class="btn btn-warning btn-sm" data-tooltip="Suspender">
                                        <i class="fas fa-ban"></i>
                                    </button>
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
                Mostrando <?php echo $total_users; ?> usuarios
            </div>
        </div>
    </div>
<?php endif; ?>



 