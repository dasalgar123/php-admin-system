<?php
// Conexión a la base de datos usando PDO
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controlador/ControladorVentas.php';

// Obtener datos usando el controlador
$controladorVentas = new ControladorVentas($pdo);
$datos = $controladorVentas->obtenerVentas();
$ventas = $datos['ventas'];
$clientes_exists = $datos['clientes_exists'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ventas</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/vista_ventas.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="main-content">
           <div class="content">
            <div class="card">
                <h2>Listado de Ventas</h2>
                <!--
                    Mostramos la tabla de ventas. Cada fila representa una venta.
                    Mostramos: ID, cliente_id (y nombre si existe), fecha, factura.
                    Usamos htmlspecialchars para evitar problemas de seguridad (XSS).
                -->
                <div class="ventas-container">
                    <table class="ventas-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente ID</th>
                                <?php if ($clientes_exists): ?><th>Cliente</th><?php endif; ?>
                                <th>Fecha</th>
                                <th>Factura</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ventas as $venta): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($venta['id']); ?></td>
                                <td><?php echo htmlspecialchars($venta['cliente_id']); ?></td>
                                <?php if ($clientes_exists): ?><td><?php echo htmlspecialchars($venta['cliente']); ?></td><?php endif; ?>
                                <td><?php echo htmlspecialchars($venta['fecha']); ?></td>
                                <td><?php echo htmlspecialchars($venta['factura']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($ventas)): ?>
                            <tr><td colspan="<?php echo $clientes_exists ? 5 : 4; ?>" style="text-align:center;">No hay ventas registradas.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 