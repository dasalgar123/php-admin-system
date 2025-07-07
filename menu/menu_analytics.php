<?php
// Datos simulados para analíticas
$analytics_data = [
    [
        'title' => 'Ventas Totales',
        'value' => '$45,231',
        'change' => '+20.1%',
        'trend' => 'up',
        'icon' => 'fas fa-dollar-sign',
        'color' => '#10b981'
    ],
    [
        'title' => 'Usuarios Activos',
        'value' => '2,350',
        'change' => '+180.1%',
        'trend' => 'up',
        'icon' => 'fas fa-users',
        'color' => '#3b82f6'
    ],
    [
        'title' => 'Tasa de Conversión',
        'value' => '12.5%',
        'change' => '+19%',
        'trend' => 'up',
        'icon' => 'fas fa-chart-line',
        'color' => '#f59e0b'
    ],
    [
        'title' => 'Pedidos Promedio',
        'value' => '$89.34',
        'change' => '-4.3%',
        'trend' => 'down',
        'icon' => 'fas fa-shopping-cart',
        'color' => '#ef4444'
    ]
];
?>

<link rel="stylesheet" href="css/analytics.css">

<div class="analytics-grid">
    <?php foreach ($analytics_data as $item): ?>
    <div class="analytics-card">
        <div class="analytics-header">
            <div class="analytics-icon" style="background-color: <?php echo $item['color']; ?>">
                <i class="<?php echo $item['icon']; ?>"></i>
            </div>
            <div class="trend-indicator <?php echo $item['trend']; ?>">
                <i class="fas fa-<?php echo $item['trend'] === 'up' ? 'trending-up' : 'trending-down'; ?>"></i>
            </div>
        </div>
        <div class="analytics-content">
            <h3 class="analytics-title"><?php echo $item['title']; ?></h3>
            <p class="analytics-value"><?php echo $item['value']; ?></p>
            <p class="analytics-change <?php echo $item['trend']; ?>">
                <?php echo $item['change']; ?> desde el mes pasado
            </p>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="analytics-content-grid">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Ventas Mensuales</h2>
        </div>
        <div class="chart-placeholder">
            <p>Gráfico de ventas mensuales</p>
            <p class="chart-note">Integrar con Chart.js para visualización</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Productos Más Vendidos</h2>
        </div>
        <div class="chart-placeholder">
            <p>Gráfico de productos populares</p>
            <p class="chart-note">Integrar con Chart.js para visualización</p>
        </div>
    </div>
</div> 