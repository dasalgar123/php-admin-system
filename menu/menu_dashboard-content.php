<?php
?>
<link rel="stylesheet" href="css/dashboard.css">
<?php
// Datos simulados para el panel principal
$stats = [
    'users' => 1234,
    'products' => 567,
    'orders' => 89,
    'revenue' => 12345
];

$recent_orders = [
    ['id' => '#1234', 'customer' => 'Juan Pérez', 'amount' => 150, 'status' => 'Completado'],
    ['id' => '#1235', 'customer' => 'María García', 'amount' => 200, 'status' => 'Pendiente'],
    ['id' => '#1236', 'customer' => 'Carlos López', 'amount' => 75, 'status' => 'En proceso']
];

// Función para obtener el color del estado
$getStatusColor = function($status) {
    switch ($status) {
        case 'Completado': return 'success';
        case 'Pendiente': return 'warning';
        case 'En proceso': return 'primary';
        default: return 'secondary';
    }
};
?>

<!-- Panel de estadísticas -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon users">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-content">
            <h3>Total Usuarios</h3>
            <div class="stat-value"><?php echo number_format($stats['users']); ?></div>
            <div class="stat-change">+12% desde el mes pasado</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon products">
            <i class="fas fa-box"></i>
        </div>
        <div class="stat-content">
            <h3>Productos</h3>
            <div class="stat-value"><?php echo number_format($stats['products']); ?></div>
            <div class="stat-change">+8% desde el mes pasado</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon orders">
            <i class="fas fa-shopping-cart"></i>
        </div>
        <div class="stat-content">
            <h3>Pedidos</h3>
            <div class="stat-value"><?php echo number_format($stats['orders']); ?></div>
            <div class="stat-change">-3% desde el mes pasado</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon revenue">
            <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="stat-content">
            <h3>Ingresos</h3>
            <div class="stat-value">$<?php echo number_format($stats['revenue']); ?></div>
            <div class="stat-change">+15% desde el mes pasado</div>
        </div>
    </div>
</div>

<!-- Contenido del panel principal -->
<div class="dashboard-content">
    <!-- Lista de pedidos recientes -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Pedidos Recientes</h2>
            <a href="?page=ordenes" class="btn btn-primary">
                <i class="fas fa-eye"></i>
                Ver todos
            </a>
        </div>
        
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Monto</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_orders as $order): ?>
                    <tr>
                        <td><?php echo $order['id']; ?></td>
                        <td><?php echo $order['customer']; ?></td>
                        <td>$<?php echo number_format($order['amount']); ?></td>
                        <td>
                            <span class="status-badge <?php echo $getStatusColor($order['status']); ?>">
                                <?php echo $order['status']; ?>
                            </span>
                        </td>
                        <td>
                            <a href="?page=ordenes&action=view&id=<?php echo $order['id']; ?>" 
                               class="btn btn-secondary btn-sm" data-tooltip="Ver detalles">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Panel de acciones rápidas -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Acciones Rápidas</h2>
        </div>
        
        <div class="quick-actions">
            <div class="action-grid">
                <a href="?page=usuarios&action=add" class="action-card">
                    <i class="fas fa-user-plus"></i>
                    <h3>Agregar Usuario</h3>
                    <p>Crear nuevo usuario en el sistema</p>
                </a>
                
                <a href="?page=productos&action=add" class="action-card">
                    <i class="fas fa-plus"></i>
                    <h3>Nuevo Producto</h3>
                    <p>Agregar producto al catálogo</p>
                </a>
                
                <a href="?page=analytics" class="action-card">
                    <i class="fas fa-chart-bar"></i>
                    <h3>Ver Reportes</h3>
                    <p>Analizar métricas del sistema</p>
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

 