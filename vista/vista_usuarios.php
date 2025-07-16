<?php
// Incluir el controlador que maneja toda la lógica PHP
require_once __DIR__ . '/../controlador/ControladorUsuarios.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestion Usuarios</title>
    <!-- CSS removido -->
</head>
<body>
    <div class="usuario-main-content">
        <div class="usuario-header">
            <h1><i class="fas fa-users"></i> Gestión de Usuarios</h1>
            <button type="button" class="btn btn-primary" onclick="mostrarFormularioUsuario()">
                <i class="fas fa-user-plus"></i> Agregar Usuario
            </button>
        </div>
        
        <div class="usuario-content">
            <?php if ($mensaje): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> <?php echo htmlspecialchars($mensaje); ?>
                </div>
            <?php endif; ?>
            
            <div class="usuario-card" id="formularioUsuario" style="display: <?php echo $usuario_editar ? 'block' : 'none'; ?>;">
                <h2><?php echo $usuario_editar ? 'Editar Usuario' : 'Agregar Usuario'; ?></h2>
                <form method="POST" class="usuario-form">
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
            
            <div class="usuario-card">
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
                        <tr><td colspan="5">No hay usuarios registrados.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- CSS removido -->

    <script src="../js/usuarios.js"></script>
</body>
</html> 