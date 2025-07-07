<?php
require_once __DIR__ . '/../config/database.php';

// Procesar acciones CRUD
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $contraseña = trim($_POST['contraseña'] ?? '');
    $rol = trim($_POST['rol'] ?? 'cliente');
    
    if ($nombre && $correo) {
        if ($id > 0) {
            // Actualizar cliente existente
            if ($contraseña) {
                $sql = "UPDATE cliente SET nombre=?, correo=?, contraseña=?, rol=? WHERE id=?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nombre, $correo, $contraseña, $rol, $id]);
            } else {
                $sql = "UPDATE cliente SET nombre=?, correo=?, rol=? WHERE id=?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nombre, $correo, $rol, $id]);
            }
            $mensaje = 'Cliente actualizado correctamente.';
        } else {
            // Insertar nuevo cliente
            if (!$contraseña) {
                $contraseña = 'cliente123'; // Contraseña por defecto
            }
            $sql = "INSERT INTO cliente (nombre, correo, contraseña, rol) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $correo, $contraseña, $rol]);
            $mensaje = 'Cliente agregado correctamente.';
        }
    } else {
        $mensaje = 'Nombre y correo son obligatorios.';
    }
}

if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    if ($id > 0) {
        $stmt = $pdo->prepare("DELETE FROM cliente WHERE id=?");
        $stmt->execute([$id]);
        $mensaje = 'Cliente eliminado correctamente.';
    }
}

$stmt = $pdo->query("SELECT id, nombre, correo, rol FROM cliente ORDER BY id ASC");
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$cliente_editar = null;
if (isset($_GET['editar'])) {
    $id = intval($_GET['editar']);
    $stmt = $pdo->prepare("SELECT id, nombre, correo, rol FROM cliente WHERE id=?");
    $stmt->execute([$id]);
    $cliente_editar = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clientes</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/dark-mode.css">
    <link rel="stylesheet" href="../css/clientes.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="main-content">
        <header class="content-header">
            <h1><i class="fas fa-user-friends"></i> Clientes</h1>
        </header>
        <div class="content">
            <?php if ($mensaje): ?>
                <div class="alert alert-info" style="margin:20px;">
                    <i class="fas fa-info-circle"></i> <?php echo htmlspecialchars($mensaje); ?>
                </div>
            <?php endif; ?>
            <div class="card">
                <h2><?php echo $cliente_editar ? 'Editar Cliente' : 'Agregar Cliente'; ?></h2>
                <form method="POST" class="user-form">
                    <?php if ($cliente_editar): ?>
                        <input type="hidden" name="id" value="<?php echo $cliente_editar['id']; ?>">
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" name="nombre" id="nombre" required value="<?php echo htmlspecialchars($cliente_editar['nombre'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="correo">Correo:</label>
                        <input type="email" name="correo" id="correo" required value="<?php echo htmlspecialchars($cliente_editar['correo'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="contraseña">Contraseña: <?php echo $cliente_editar ? '(dejar vacío para no cambiar)' : ''; ?></label>
                        <input type="password" name="contraseña" id="contraseña" <?php echo $cliente_editar ? '' : 'required'; ?>>
                    </div>
                    <div class="form-group">
                        <label for="rol">Rol:</label>
                        <select name="rol" id="rol" required>
                            <option value="cliente" <?php echo ($cliente_editar['rol'] ?? '') === 'cliente' ? 'selected' : ''; ?>>Cliente</option>
                            <option value="admin" <?php echo ($cliente_editar['rol'] ?? '') === 'admin' ? 'selected' : ''; ?>>Administrador</option>
                        </select>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> <?php echo $cliente_editar ? 'Actualizar' : 'Agregar'; ?>
                        </button>
                        <?php if ($cliente_editar): ?>
                        <a href="?page=clientes" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            <div class="card">
                <h2>Lista de Clientes</h2>
                <table class="clientes-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientes as $cliente): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cliente['id']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['correo']); ?></td>
                            <td>
                                <span class="badge badge-<?php echo $cliente['rol'] === 'admin' ? 'danger' : 'info'; ?>">
                                    <?php echo htmlspecialchars($cliente['rol']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="?page=clientes&editar=<?php echo $cliente['id']; ?>" class="btn btn-sm btn-warning" title="Editar"><i class="fas fa-pen"></i></a>
                                <a href="?page=clientes&eliminar=<?php echo $cliente['id']; ?>" class="btn btn-sm btn-danger" title="Eliminar" onclick="return confirm('¿Seguro que deseas eliminar este cliente?');"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($clientes)): ?>
                        <tr><td colspan="5" style="text-align:center;">No hay clientes registrados.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html> 