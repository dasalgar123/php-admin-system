<?php
require_once __DIR__ . '/../config/database.php';

// Procesar acciones CRUD
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Agregar o editar usuario
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $rol = trim($_POST['rol'] ?? 'usuario');
    $password = $_POST['password'] ?? '';
    
    if ($nombre && $correo && $rol) {
        if ($id > 0) {
            // Editar usuario
            if ($password) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $sql = "UPDATE usuario SET nombre=?, correo=?, contraseña=?, rol=? WHERE id=?";
                $params = [$nombre, $correo, $hash, $rol, $id];
            } else {
                $sql = "UPDATE usuario SET nombre=?, correo=?, rol=? WHERE id=?";
                $params = [$nombre, $correo, $rol, $id];
            }
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $mensaje = 'Usuario actualizado correctamente.';
        } else {
            // Agregar usuario
            if ($password) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO usuario (nombre, correo, contraseña, rol) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nombre, $correo, $hash, $rol]);
                $mensaje = 'Usuario agregado correctamente.';
            } else {
                $mensaje = 'La contraseña es obligatoria para nuevos usuarios.';
            }
        }
    } else {
        $mensaje = 'Todos los campos son obligatorios.';
    }
}

// Eliminar usuario
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    if ($id > 0) {
        $stmt = $pdo->prepare("DELETE FROM usuario WHERE id=?");
        $stmt->execute([$id]);
        $mensaje = 'Usuario eliminado correctamente.';
    }
}

// Obtener usuarios
$stmt = $pdo->query("SELECT id, nombre, correo, rol FROM usuario ORDER BY id ASC");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Si se va a editar
$usuario_editar = null;
if (isset($_GET['editar'])) {
    $id = intval($_GET['editar']);
    $stmt = $pdo->prepare("SELECT id, nombre, correo, rol FROM usuario WHERE id=?");
    $stmt->execute([$id]);
    $usuario_editar = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/dark-mode.css">
    <link rel="stylesheet" href="../css/users.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="main-content">
        <header class="content-header">
            <h1><i class="fas fa-users"></i> Usuarios</h1>
        </header>
        <div class="content">
            <?php if ($mensaje): ?>
                <div class="alert alert-info" style="margin:20px;">
                    <i class="fas fa-info-circle"></i> <?php echo htmlspecialchars($mensaje); ?>
                </div>
            <?php endif; ?>
            <div class="card">
                <h2><?php echo $usuario_editar ? 'Editar Usuario' : 'Agregar Usuario'; ?></h2>
                <form method="POST" class="user-form">
                    <?php if ($usuario_editar): ?>
                        <input type="hidden" name="id" value="<?php echo $usuario_editar['id']; ?>">
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" name="nombre" id="nombre" required value="<?php echo htmlspecialchars($usuario_editar['nombre'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="correo">Correo:</label>
                        <input type="email" name="correo" id="correo" required value="<?php echo htmlspecialchars($usuario_editar['correo'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="rol">Rol:</label>
                        <select name="rol" id="rol" required>
                            <option value="administrador" <?php if (($usuario_editar['rol'] ?? '') === 'administrador') echo 'selected'; ?>>Administrador</option>
                            <option value="usuario" <?php if (($usuario_editar['rol'] ?? '') === 'usuario') echo 'selected'; ?>>Usuario</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña<?php if ($usuario_editar): ?> (dejar en blanco para no cambiar)<?php endif; ?>:</label>
                        <input type="password" name="password" id="password" <?php if (!$usuario_editar): ?>required<?php endif; ?> autocomplete="new-password">
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> <?php echo $usuario_editar ? 'Actualizar' : 'Agregar'; ?>
                        </button>
                        <?php if ($usuario_editar): ?>
                        <a href="?page=users" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            <div class="card">
                <h2>Lista de Usuarios</h2>
                <table class="users-table">
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
                        <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($usuario['id']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['correo']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['rol']); ?></td>
                            <td>
                                <a href="?page=users&editar=<?php echo $usuario['id']; ?>" class="btn btn-sm btn-warning" title="Editar">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <a href="?page=users&eliminar=<?php echo $usuario['id']; ?>" class="btn btn-sm btn-danger" title="Eliminar" onclick="return confirm('¿Seguro que deseas eliminar este usuario?');">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <a href="?page=users&suspender=<?php echo $usuario['id']; ?>" class="btn btn-sm btn-secondary" title="Suspender" onclick="return confirm('¿Seguro que deseas suspender este usuario?');">
                                    <i class="fas fa-user-slash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($usuarios)): ?>
                        <tr><td colspan="5" style="text-align:center;">No hay usuarios registrados.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html> 