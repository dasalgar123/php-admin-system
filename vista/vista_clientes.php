<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controlador/ControladorClientes.php';

// Obtener datos usando el controlador
$controladorClientes = new ControladorClientes($pdo);
$mensaje = $controladorClientes->procesarAcciones();
$clientes = $controladorClientes->obtenerClientes();
$cliente_editar = $controladorClientes->obtenerClienteParaEditar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clientes</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/clientes.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="main-content">
        
        <div class="content">
            <?php if ($mensaje): ?>
                <div class="alerta alerta-exito">
                    <i class="fas fa-info-circle"></i> <?php echo htmlspecialchars($mensaje); ?>
                </div>
            <?php endif; ?>
            <div class="tarjeta">
                <h2><?php echo $cliente_editar ? 'Editar Cliente' : 'Agregar Cliente'; ?></h2>
                <form method="POST" class="formulario-cliente">
                    <?php if ($cliente_editar): ?>
                        <input type="hidden" name="id" value="<?php echo $cliente_editar['id']; ?>">
                    <?php endif; ?>
                    <div class="grupo-formulario">
                        <label for="nombre">Nombre:</label>
                        <input type="text" name="nombre" id="nombre" required value="<?php echo htmlspecialchars($cliente_editar['nombre'] ?? ''); ?>" class="control-formulario">
                    </div>
                    <div class="grupo-formulario">
                        <label for="correo">Correo:</label>
                        <input type="email" name="correo" id="correo" required value="<?php echo htmlspecialchars($cliente_editar['correo'] ?? ''); ?>" class="control-formulario">
                    </div>
                    <div class="grupo-formulario">
                        <label for="contraseña">Contraseña: <?php echo $cliente_editar ? '(dejar vacío para no cambiar)' : ''; ?></label>
                        <input type="password" name="contraseña" id="contraseña" <?php echo $cliente_editar ? '' : 'required'; ?> class="control-formulario">
                    </div>
                    <div class="grupo-formulario">
                        <label for="rol">Rol:</label>
                        <select name="rol" id="rol" required class="control-formulario">
                            <option value="cliente" <?php echo ($cliente_editar['rol'] ?? '') === 'cliente' ? 'selected' : ''; ?>>Cliente</option>
                            <option value="admin" <?php echo ($cliente_editar['rol'] ?? '') === 'admin' ? 'selected' : ''; ?>>Administrador</option>
                        </select>
                    </div>
                    <div class="acciones-formulario">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> <?php echo $cliente_editar ? 'Actualizar' : 'Agregar'; ?>
                        </button>
                        <?php if ($cliente_editar): ?>
                        <a href="?page=clientes" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            <div class="tarjeta">
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
                        <tr><td colspan="5" class="texto-centro">No hay clientes registrados.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html> 