<?php
// Conexión a la base de datos usando PDO (esto asegura que siempre tengamos acceso a los datos)
require_once __DIR__ . '/../config/database.php';

// Consultar garantías desde productos_garantia, uniendo con productos para mostrar el nombre
$stmt = $pdo->query('SELECT g.id, g.producto_id, p.nombre AS producto, g.cantidad, g.fecha, g.motivo, g.beneficiario_tipo, g.beneficiario_id, g.factura_remision, g.observaciones FROM productos_garantia g LEFT JOIN productos p ON g.producto_id = p.id ORDER BY g.fecha DESC');
$garantias = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Ahora $garantias es un arreglo con todas las filas de la consulta
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Garantías</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/dark-mode.css">
    <link rel="stylesheet" href="../css/vista_garantias.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="main-content">
        <header class="content-header">
            <h1><i class="fas fa-shield-alt"></i> Garantías</h1>
        </header>
        <div class="content">
            <div class="card">
                <h2>Listado de Garantías</h2>
                <!--
                    Mostramos la tabla de garantías. Cada fila representa una garantía.
                    Mostramos: ID, producto, producto_id, cantidad, fecha, motivo, beneficiario_tipo, beneficiario_id, factura_remision, observaciones.
                    Usamos htmlspecialchars para evitar problemas de seguridad (XSS).
                -->
                <div class="garantias-container">
                    <table class="garantias-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Producto</th>
                                <th>ID Producto</th>
                                <th>Cantidad</th>
                                <th>Fecha</th>
                                <th>Motivo</th>
                                <th>Tipo Beneficiario</th>
                                <th>ID Beneficiario</th>
                                <th>Factura/Remisión</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($garantias as $garantia): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($garantia['id']); ?></td>
                                <td><?php echo htmlspecialchars($garantia['producto']); ?></td>
                                <td><?php echo htmlspecialchars($garantia['producto_id']); ?></td>
                                <td><?php echo htmlspecialchars($garantia['cantidad']); ?></td>
                                <td><?php echo htmlspecialchars($garantia['fecha']); ?></td>
                                <td><?php echo htmlspecialchars($garantia['motivo']); ?></td>
                                <td><?php echo htmlspecialchars($garantia['beneficiario_tipo']); ?></td>
                                <td><?php echo htmlspecialchars($garantia['beneficiario_id']); ?></td>
                                <td><?php echo htmlspecialchars($garantia['factura_remision']); ?></td>
                                <td><?php echo htmlspecialchars($garantia['observaciones']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($garantias)): ?>
                            <tr><td colspan="10" style="text-align:center;">No hay garantías registradas.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 