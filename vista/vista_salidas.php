<?php
// Conexión a la base de datos usando PDO (esto asegura que siempre tengamos acceso a los datos)
require_once __DIR__ . '/../config/database.php';

// Consultar salidas desde productos_salidas, uniendo con productos para mostrar el nombre
$stmt = $pdo->query('SELECT s.id, s.producto_id, p.nombre AS producto, s.venta_id, s.destinatario_tipo, s.destinatario_id, s.cantidad, s.fecha, s.motivo, s.cliente_id, s.factura_remision FROM productos_salidas s LEFT JOIN productos p ON s.producto_id = p.id ORDER BY s.fecha DESC');
$salidas = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Ahora $salidas es un arreglo con todas las filas de la consulta
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Salidas</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/dark-mode.css">
    <link rel="stylesheet" href="../css/vista_salidas.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="main-content">
        <header class="content-header">
            <h1><i class="fas fa-arrow-up"></i> Salidas</h1>
        </header>
        <div class="content">
            <div class="card">
                <h2>Listado de Salidas</h2>
                <!--
                    Mostramos la tabla de salidas. Cada fila representa una salida de producto.
                    Mostramos: ID, producto, producto_id, venta_id, destinatario_tipo, destinatario_id, cantidad, fecha, motivo, cliente_id, factura_remision.
                    Usamos htmlspecialchars para evitar problemas de seguridad (XSS).
                -->
                <div class="salidas-container">
                    <table class="salidas-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>ID Producto</th>
                            <th>Venta ID</th>
                            <th>Destinatario Tipo</th>
                            <th>Destinatario ID</th>
                            <th>Cantidad</th>
                            <th>Fecha</th>
                            <th>Motivo</th>
                            <th>Cliente ID</th>
                            <th>Factura/Remisión</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($salidas as $salida): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($salida['id']); ?></td>
                            <td><?php echo htmlspecialchars($salida['producto']); ?></td>
                            <td><?php echo htmlspecialchars($salida['producto_id']); ?></td>
                            <td><?php echo htmlspecialchars($salida['venta_id']); ?></td>
                            <td><?php echo htmlspecialchars($salida['destinatario_tipo']); ?></td>
                            <td><?php echo htmlspecialchars($salida['destinatario_id']); ?></td>
                            <td><?php echo htmlspecialchars($salida['cantidad']); ?></td>
                            <td><?php echo htmlspecialchars($salida['fecha']); ?></td>
                            <td><?php echo htmlspecialchars($salida['motivo']); ?></td>
                            <td><?php echo htmlspecialchars($salida['cliente_id']); ?></td>
                            <td><?php echo htmlspecialchars($salida['factura_remision']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($salidas)): ?>
                        <tr><td colspan="11" style="text-align:center;">No hay salidas registradas.</td></tr>
                        <?php endif; ?>
                    </tbody>
                                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 