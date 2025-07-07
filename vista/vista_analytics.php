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
            <canvas id="ventasChart" width="400" height="200"></canvas>
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

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  var ctx = document.getElementById('ventasChart').getContext('2d');
  var ventasChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'],
      datasets: [{
        label: 'Ventas',
        data: [120, 190, 300, 500, 200, 300],
        backgroundColor: 'rgba(37, 99, 235, 0.7)'
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: true }
      }
    }
  });
});
</script>

<style>
.analytics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.analytics-card {
    background: white;
    border-radius: 12px;
    box-shadow: var(--shadow);
    padding: 1.5rem;
    transition: transform 0.2s;
}

.analytics-card:hover {
    transform: translateY(-2px);
}

.analytics-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.analytics-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.trend-indicator {
    padding: 0.5rem;
    border-radius: 6px;
    font-size: 1rem;
}

.trend-indicator.up {
    background-color: #dcfce7;
    color: var(--success-color);
}

.trend-indicator.down {
    background-color: #fee2e2;
    color: var(--danger-color);
}

.analytics-title {
    font-size: 0.875rem;
    color: var(--secondary-color);
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.analytics-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--dark-color);
    margin-bottom: 0.25rem;
}

.analytics-change {
    font-size: 0.875rem;
    font-weight: 500;
}

.analytics-change.up {
    color: var(--success-color);
}

.analytics-change.down {
    color: var(--danger-color);
}

.analytics-content-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.chart-placeholder {
    height: 300px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background-color: #f8fafc;
    border-radius: 6px;
    color: var(--secondary-color);
    text-align: center;
}

.chart-note {
    font-size: 0.875rem;
    margin-top: 0.5rem;
    opacity: 0.7;
}

@media (max-width: 1024px) {
    .analytics-content-grid {
        grid-template-columns: 1fr;
    }
}
</style> 