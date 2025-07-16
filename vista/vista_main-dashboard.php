<?php
// Conexión a la base de datos
require_once '../config/database.php';

// Consulta para obtener todos los pedidos
$query = "SELECT * FROM pedidos ORDER BY fecha DESC";
$stmt = $pdo->prepare($query);
$stmt->execute();

$pedidos = $stmt->fetchAll();
?>



<!-- Contenido del Dashboard -->
<div class="dashboard-content">
    <!-- Tabla de Pedidos -->
                <div class="tarjeta">
                <div class="encabezado-tarjeta">
                    <h2 class="titulo-tarjeta">Tabla de Pedidos</h2>
            <a href="?page=orders" class="btn btn-primary">
                <i class="fas fa-eye"></i>
                Ver todos
            </a>
        </div>
        
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre Cliente</th>
                        <th>Teléfono</th>
                        <th>Productos</th>
                        <th>Total</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pedidos)): ?>
                        <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($pedido['id']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['nombre_cliente']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['telefono']); ?></td>
                            <td>
                                <div class="product-info">
                                    <?php 
                                    $productos = htmlspecialchars($pedido['productos']);
                                    if (strlen($productos) > 50) {
                                        echo substr($productos, 0, 50) . '...';
                                    } else {
                                        echo $productos;
                                    }
                                    ?>
                                </div>
                            </td>
                            <td>$<?php echo number_format($pedido['total'], 2); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($pedido['fecha'])); ?></td>
                            <td>
                                <a href="?page=orders&action=view&id=<?php echo $pedido['id']; ?>" 
                                   class="btn btn-secondary btn-sm" data-tooltip="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="?page=orders&action=edit&id=<?php echo $pedido['id']; ?>" 
                                   class="btn btn-primary btn-sm" data-tooltip="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No hay pedidos registrados</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Acciones Rápidas -->
                <div class="tarjeta">
                <div class="encabezado-tarjeta">
                    <h2 class="titulo-tarjeta">Acciones Rápidas</h2>
        </div>
        
        <div class="quick-actions">
            <div class="action-grid">
                <a href="?page=users&action=add" class="action-card">
                    <i class="fas fa-user-plus"></i>
                    <h3>Agregar Usuario</h3>
                    <p>Crear nuevo usuario en el sistema</p>
                </a>
                
                <a href="?page=products&action=add" class="action-card">
                    <i class="fas fa-plus"></i>
                    <h3>Nuevo Producto</h3>
                    <p>Agregar producto al catálogo</p>
                </a>
                

                
                <a href="?page=settings" class="action-card">
                    <i class="fas fa-cog"></i>
                    <h3>Configuración</h3>
                    <p>Personalizar el sistema</p>
                </a>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="../css/main-dashboard.css"> 