<?php
// Conexión a la base de datos usando PDO (esto asegura que siempre tengamos acceso a los datos)
require_once __DIR__ . '/../config/database.php';

// Consultar devoluciones desde productos_devoluciones, uniendo con productos para mostrar el nombre
$stmt = $pdo->query('SELECT d.id, d.tipo, d.entidad_id, d.fecha, d.motivo, d.factura_remision, d.producto_id, p.nombre AS producto FROM productos_devoluciones d LEFT JOIN productos p ON d.producto_id = p.id ORDER BY d.fecha DESC');
$devoluciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Ahora $devoluciones es un arreglo con todas las filas de la consulta
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Devoluciones</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/dark-mode.css">
    <link rel="stylesheet" href="../css/vista_devoluciones.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="main-content">
           <div class="content">
            <div class="card">
                <h2>Listado de Devoluciones</h2>
                <!--
                    Mostramos la tabla de devoluciones. Cada fila representa una devolución de producto.
                    Mostramos: ID, tipo, entidad_id, fecha, motivo, factura/remisión, producto y producto_id.
                    Usamos htmlspecialchars para evitar problemas de seguridad (XSS).
                -->
                <div class="devoluciones-container">
                    <table class="devoluciones-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tipo</th>
                                <th>Entidad ID</th>
                                <th>Fecha</th>
                                <th>Motivo</th>
                                <th>Factura/Remisión</th>
                                <th>Producto</th>
                                <th>ID Producto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($devoluciones as $devolucion): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($devolucion['id']); ?></td>
                                <td><?php echo htmlspecialchars($devolucion['tipo']); ?></td>
                                <td><?php echo htmlspecialchars($devolucion['entidad_id']); ?></td>
                                <td><?php echo htmlspecialchars($devolucion['fecha']); ?></td>
                                <td><?php echo htmlspecialchars($devolucion['motivo']); ?></td>
                                <td><?php echo htmlspecialchars($devolucion['factura_remision']); ?></td>
                                <td><?php echo htmlspecialchars($devolucion['producto']); ?></td>
                                <td><?php echo htmlspecialchars($devolucion['producto_id']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($devoluciones)): ?>
                            <tr><td colspan="8" style="text-align:center;">No hay devoluciones registradas.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 