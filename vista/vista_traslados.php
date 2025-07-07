<?php
// Conexión a la base de datos usando PDO (esto asegura que siempre tengamos acceso a los datos)
require_once __DIR__ . '/../config/database.php';

// Consultar traslados desde productos_traslados, uniendo con bodegas si existen
$bodegas_exists = false;
try {
    $pdo->query('SELECT 1 FROM bodegas LIMIT 1');
    $bodegas_exists = true;
} catch (Exception $e) {}

if ($bodegas_exists) {
    $stmt = $pdo->query('SELECT t.id, t.bodega_origen_id, bo.nombre AS bodega_origen, t.bodega_destino_id, bd.nombre AS bodega_destino, t.fecha, t.motivo FROM productos_traslados t LEFT JOIN bodegas bo ON t.bodega_origen_id = bo.id LEFT JOIN bodegas bd ON t.bodega_destino_id = bd.id ORDER BY t.fecha DESC');
} else {
    $stmt = $pdo->query('SELECT t.id, t.bodega_origen_id, t.bodega_destino_id, t.fecha, t.motivo FROM productos_traslados t ORDER BY t.fecha DESC');
}
$traslados = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Ahora $traslados es un arreglo con todas las filas de la consulta
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Traslados</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/dark-mode.css">
    <link rel="stylesheet" href="../css/vista_traslados.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="main-content">
        <header class="content-header">
            <h1><i class="fas fa-exchange-alt"></i> Traslados</h1>
        </header>
        <div class="content">
            <div class="card">
                <h2>Listado de Traslados</h2>
                <!--
                    Mostramos la tabla de traslados. Cada fila representa un traslado.
                    Mostramos: ID, bodega_origen_id (y nombre si existe), bodega_destino_id (y nombre si existe), fecha, motivo.
                    Usamos htmlspecialchars para evitar problemas de seguridad (XSS).
                -->
                <div class="traslados-container">
                    <table class="traslados-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Bodega Origen ID</th>
                                <?php if ($bodegas_exists): ?><th>Bodega Origen</th><?php endif; ?>
                                <th>Bodega Destino ID</th>
                                <?php if ($bodegas_exists): ?><th>Bodega Destino</th><?php endif; ?>
                                <th>Fecha</th>
                                <th>Motivo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($traslados as $traslado): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($traslado['id']); ?></td>
                                <td><?php echo htmlspecialchars($traslado['bodega_origen_id']); ?></td>
                                <?php if ($bodegas_exists): ?><td><?php echo htmlspecialchars($traslado['bodega_origen']); ?></td><?php endif; ?>
                                <td><?php echo htmlspecialchars($traslado['bodega_destino_id']); ?></td>
                                <?php if ($bodegas_exists): ?><td><?php echo htmlspecialchars($traslado['bodega_destino']); ?></td><?php endif; ?>
                                <td><?php echo htmlspecialchars($traslado['fecha']); ?></td>
                                <td><?php echo htmlspecialchars($traslado['motivo']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($traslados)): ?>
                            <tr><td colspan="<?php echo $bodegas_exists ? 7 : 5; ?>" style="text-align:center;">No hay traslados registrados.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 