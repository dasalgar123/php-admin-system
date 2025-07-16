<?php
// Conexión a la base de datos usando PDO
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controlador/ControladorTraslados.php';

// Obtener datos usando el controlador
$controladorTraslados = new ControladorTraslados($pdo);
$datos = $controladorTraslados->obtenerTraslados();
$traslados = $datos['traslados'];
$bodegas_exists = $datos['bodegas_exists'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Traslados</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/vista_traslados.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="main-content">
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