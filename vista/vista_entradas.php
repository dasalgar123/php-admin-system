<?php
// Conexión a la base de datos usando PDO (esto asegura que siempre tengamos acceso a los datos)
require_once __DIR__ . '/../config/database.php';

// Consultar entradas desde productos_entradas, uniendo con productos para mostrar el nombre
$stmt = $pdo->query('SELECT e.id, e.producto_id, p.nombre AS producto, e.cantidad, e.fecha, e.motivo, e.beneficiario_tipo, e.beneficiario_id, e.factura_remision, e.beneficiario FROM productos_entradas e LEFT JOIN productos p ON e.producto_id = p.id ORDER BY e.fecha DESC');
$entradas = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Ahora $entradas es un arreglo con todas las filas de la consulta
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Entradas</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/dark-mode.css">
    <link rel="stylesheet" href="../css/vista_entradas.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="main-content">
        
        <div class="content">
            <div class="card">
                <h2>Listado de Entradas</h2>
                <!--
                    Mostramos la tabla de entradas. Cada fila representa una entrada de producto.
                    Mostramos: ID, producto, cantidad, fecha, motivo, beneficiario_tipo, beneficiario_id, factura_remision, beneficiario y nombre del producto.
                    Usamos htmlspecialchars para evitar problemas de seguridad (XSS).
                -->
                <div class="entradas-container">
                    <table class="entradas-table">
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
                                <th>Beneficiario</th>
                                <th>Factura/Remisión</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($entradas as $entrada): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($entrada['id']); ?></td>
                                <td><?php echo htmlspecialchars($entrada['producto']); ?></td>
                                <td><?php echo htmlspecialchars($entrada['producto_id']); ?></td>
                                <td><?php echo htmlspecialchars($entrada['cantidad']); ?></td>
                                <td><?php echo htmlspecialchars($entrada['fecha']); ?></td>
                                <td><?php echo htmlspecialchars($entrada['motivo']); ?></td>
                                <td><?php echo htmlspecialchars($entrada['beneficiario_tipo']); ?></td>
                                <td><?php echo htmlspecialchars($entrada['beneficiario_id']); ?></td>
                                <td><?php echo htmlspecialchars($entrada['beneficiario']); ?></td>
                                <td><?php echo htmlspecialchars($entrada['factura_remision']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($entradas)): ?>
                            <tr><td colspan="10" style="text-align:center;">No hay entradas registradas.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 