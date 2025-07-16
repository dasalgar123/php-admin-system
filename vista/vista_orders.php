<?php
// Conexión a la base de datos
require_once '../config/database.php';
require_once '../controlador/ControladorPedidos.php';

// Obtener datos usando el controlador
$controladorPedidos = new ControladorPedidos($pdo);
$pedidos = $controladorPedidos->obtenerPedidos();
?>

<div class="card">
    <div class="card-header">
        <div class="filters">
            <div class="search-box">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" placeholder="Buscar pedidos...">
            </div>
            <select class="filter-select">
                <option value="all">Todos los estados</option>
                <option value="Completado">Completado</option>
                <option value="Pendiente">Pendiente</option>
                <option value="En proceso">En proceso</option>
            </select>
        </div>
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
                            <div class="action-buttons">
                                <a href="?page=orders&action=view&id=<?php echo $pedido['id']; ?>" 
                                   class="btn btn-secondary btn-sm" data-tooltip="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="?page=orders&action=edit&id=<?php echo $pedido['id']; ?>" 
                                   class="btn btn-primary btn-sm" data-tooltip="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
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