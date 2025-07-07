<?php
require_once __DIR__ . '/../config/database.php';

// Procesar acciones CRUD
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $nombre = trim($_POST['nombre'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    
    if ($nombre && $telefono) {
        if ($id > 0) {
            $sql = "UPDATE proveedor SET nombre=?, telefono=?, correo=?, direccion=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $telefono, $correo, $direccion, $id]);
            $mensaje = 'Proveedor actualizado correctamente.';
        } else {
            $sql = "INSERT INTO proveedor (nombre, telefono, correo, direccion, fecha_creacion) VALUES (?, ?, ?, ?, NOW())";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $telefono, $correo, $direccion]);
            $mensaje = 'Proveedor agregado correctamente.';
        }
    } else {
        $mensaje = 'Nombre y teléfono son obligatorios.';
    }
}

if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    if ($id > 0) {
        $stmt = $pdo->prepare("DELETE FROM proveedor WHERE id=?");
        $stmt->execute([$id]);
        $mensaje = 'Proveedor eliminado correctamente.';
    }
}

$stmt = $pdo->query("SELECT id, nombre, telefono, correo, direccion, fecha_creacion FROM proveedor ORDER BY id ASC");
$proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

$proveedor_editar = null;
if (isset($_GET['editar'])) {
    $id = intval($_GET['editar']);
    $stmt = $pdo->prepare("SELECT id, nombre, telefono, correo, direccion FROM proveedor WHERE id=?");
    $stmt->execute([$id]);
    $proveedor_editar = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Proveedores</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/dark-mode.css">
    <link rel="stylesheet" href="../css/clientes.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="main-content">
        <header class="content-header">
            <h1><i class="fas fa-truck"></i> Proveedores</h1>
        </header>
        <div class="content">
            <?php if ($mensaje): ?>
                <div class="alert alert-info" style="margin:20px;">
                    <i class="fas fa-info-circle"></i> <?php echo htmlspecialchars($mensaje); ?>
                </div>
            <?php endif; ?>
            <div class="card">
                <h2><?php echo $proveedor_editar ? 'Editar Proveedor' : 'Agregar Proveedor'; ?></h2>
                <form method="POST" class="user-form">
                    <?php if ($proveedor_editar): ?>
                        <input type="hidden" name="id" value="<?php echo $proveedor_editar['id']; ?>">
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" name="nombre" id="nombre" required value="<?php echo htmlspecialchars($proveedor_editar['nombre'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono:</label>
                        <input type="text" name="telefono" id="telefono" required value="<?php echo htmlspecialchars($proveedor_editar['telefono'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="correo">Correo:</label>
                        <input type="email" name="correo" id="correo" value="<?php echo htmlspecialchars($proveedor_editar['correo'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="direccion">Dirección:</label>
                        <textarea name="direccion" id="direccion" rows="3"><?php echo htmlspecialchars($proveedor_editar['direccion'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> <?php echo $proveedor_editar ? 'Actualizar' : 'Agregar'; ?>
                        </button>
                        <?php if ($proveedor_editar): ?>
                        <a href="?page=proveedores" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            <div class="card">
                <h2>Lista de Proveedores</h2>
                <table class="clientes-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Correo</th>
                            <th>Dirección</th>
                            <th>Fecha Creación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($proveedores as $proveedor): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($proveedor['id']); ?></td>
                            <td><?php echo htmlspecialchars($proveedor['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($proveedor['telefono']); ?></td>
                            <td><?php echo htmlspecialchars($proveedor['correo']); ?></td>
                            <td><?php echo htmlspecialchars($proveedor['direccion']); ?></td>
                            <td><?php echo $proveedor['fecha_creacion'] ? date('d/m/Y H:i', strtotime($proveedor['fecha_creacion'])) : ''; ?></td>
                            <td>
                                <a href="?page=proveedores&editar=<?php echo $proveedor['id']; ?>" class="btn btn-sm btn-warning" title="Editar"><i class="fas fa-pen"></i></a>
                                <a href="?page=proveedores&eliminar=<?php echo $proveedor['id']; ?>" class="btn btn-sm btn-danger" title="Eliminar" onclick="return confirm('¿Seguro que deseas eliminar este proveedor?');"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($proveedores)): ?>
                        <tr><td colspan="7" style="text-align:center;">No hay proveedores registrados.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html> 