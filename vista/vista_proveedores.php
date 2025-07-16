<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controlador/ControladorProveedores.php';

// Obtener datos usando el controlador
$controladorProveedores = new ControladorProveedores($pdo);
$mensaje = $controladorProveedores->procesarAcciones();
$proveedores = $controladorProveedores->obtenerProveedores();
$proveedor_editar = $controladorProveedores->obtenerProveedorParaEditar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Proveedores</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/clientes.css">
    <link rel="stylesheet" href="../css/proveedores.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="main-content">
        <header class="content-header">
            <h1><i class="fas fa-truck"></i> Proveedores</h1>
            <div class="header-actions">
                <button type="button" class="btn btn-primary" onclick="mostrarFormularioUsuario()">
                    <i class="fas fa-user-plus"></i> Agregar Usuario
                </button>
            </div>
        </header>
        <div class="content">
            <?php if ($mensaje): ?>
                <div class="alert alert-info" style="margin:20px;">
                    <i class="fas fa-info-circle"></i> <?php echo htmlspecialchars($mensaje); ?>
                </div>
            <?php endif; ?>
            
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

    <!-- Modal para Agregar Usuario -->
    <div id="modalUsuario" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-user-plus"></i> Agregar Nuevo Usuario</h2>
                <span class="close" onclick="cerrarModalUsuario()">&times;</span>
            </div>
            <div class="modal-body">
                <form id="formUsuario" method="POST" action="?page=users&action=add">
                    <div class="form-group">
                        <label for="nombre_usuario">Nombre:</label>
                        <input type="text" name="nombre" id="nombre_usuario" required>
                    </div>
                    <div class="form-group">
                        <label for="email_usuario">Email:</label>
                        <input type="email" name="email" id="email_usuario" required>
                    </div>
                    <div class="form-group">
                        <label for="password_usuario">Contraseña:</label>
                        <input type="password" name="password" id="password_usuario" required>
                    </div>
                    <div class="form-group">
                        <label for="rol_usuario">Rol:</label>
                        <select name="rol" id="rol_usuario" required>
                            <option value="">Seleccionar rol</option>
                            <option value="admin">Administrador</option>
                            <option value="usuario">Usuario</option>
                            <option value="vendedor">Vendedor</option>
                        </select>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Usuario
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="cerrarModalUsuario()">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../js/proveedores.js"></script>
</body>
</html> 