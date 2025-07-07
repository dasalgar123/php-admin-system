<?php
// Datos simulados de pedidos
$orders = [
    [
        'id' => '#1234',
        'customer' => 'Juan Pérez',
        'products' => 3,
        'total' => 150.00,
        'status' => 'Completado',
        'date' => '2024-01-15',
        'payment' => 'Tarjeta'
    ],
    [
        'id' => '#1235',
        'customer' => 'María García',
        'products' => 2,
        'total' => 200.00,
        'status' => 'Pendiente',
        'date' => '2024-01-14',
        'payment' => 'Efectivo'
    ],
    [
        'id' => '#1236',
        'customer' => 'Carlos López',
        'products' => 1,
        'total' => 75.00,
        'status' => 'En proceso',
        'date' => '2024-01-13',
        'payment' => 'Transferencia'
    ]
];

$getStatusColor = function($status) {
    switch ($status) {
        case 'Completado': return 'success';
        case 'Pendiente': return 'warning';
        case 'En proceso': return 'primary';
        default: return 'secondary';
    }
};
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
                    <th>ID Pedido</th>
                    <th>Cliente</th>
                    <th>Productos</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Pago</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo $order['id']; ?></td>
                    <td><?php echo htmlspecialchars($order['customer']); ?></td>
                    <td><?php echo $order['products']; ?> items</td>
                    <td>$<?php echo number_format($order['total'], 2); ?></td>
                    <td>
                        <span class="status-badge <?php echo $getStatusColor($order['status']); ?>">
                            <?php echo $order['status']; ?>
                        </span>
                    </td>
                    <td><?php echo $order['date']; ?></td>
                    <td><?php echo $order['payment']; ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="?page=ordenes&action=view&id=<?php echo $order['id']; ?>" 
                               class="btn btn-secondary btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="?page=ordenes&action=edit&id=<?php echo $order['id']; ?>" 
                               class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div> 