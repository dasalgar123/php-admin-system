<?php
// Conexión a la base de datos usando PDO
require_once __DIR__ . '/../config/database.php';

// Consultar productos reales
$stmt = $pdo->query('SELECT id, nombre, descripcion, precio, imagen, categoria_id, tallas_id, tipo_producto, color_id FROM productos ORDER BY id DESC');
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="css/products.css">

<div class="card">
    <div class="card-header">
        <div class="filters">
            <div class="search-box">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" placeholder="Buscar productos...">
            </div>
            <!-- Aquí podrías agregar filtros por categoría, tipo, etc. -->
        </div>
        <a href="?page=products&action=add" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Nuevo Producto
        </a>
    </div>
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Categoría ID</th>
                    <th>Tallas ID</th>
                    <th>Tipo</th>
                    <th>Color ID</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['id']); ?></td>
                    <td>
                        <?php if (!empty($product['imagen'])): ?>
                            <img src="<?php echo htmlspecialchars($product['imagen']); ?>" alt="Imagen" style="width:40px;height:40px;object-fit:cover;border-radius:6px;">
                        <?php else: ?>
                            <span style="color:#ccc;">Sin imagen</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($product['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($product['descripcion']); ?></td>
                    <td>$<?php echo number_format($product['precio'], 2); ?></td>
                    <td><?php echo htmlspecialchars($product['categoria_id']); ?></td>
                    <td><?php echo htmlspecialchars($product['tallas_id']); ?></td>
                    <td><?php echo htmlspecialchars($product['tipo_producto']); ?></td>
                    <td><?php echo htmlspecialchars($product['color_id']); ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="?page=products&action=view&id=<?php echo $product['id']; ?>" class="btn btn-secondary btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="?page=products&action=edit&id=<?php echo $product['id']; ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="?page=products&action=delete&id=<?php echo $product['id']; ?>" class="btn btn-danger btn-sm btn-delete">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($products)): ?>
                <tr><td colspan="10" style="text-align:center;">No hay productos registrados.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div> 