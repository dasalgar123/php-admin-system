<?php
require_once 'config/database.php';

// Manejar acciones (editar, eliminar, agregar)
$action = isset($_GET['action']) ? $_GET['action'] : '';
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Procesar formulario de edición
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'edit') {
    try {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = (float)$_POST['precio'];
        $tipo_producto = $_POST['tipo_producto'];
        $categoria_id = (int)$_POST['categoria_id'];
        
        $sql = "UPDATE productos SET 
                nombre = ?, 
                descripcion = ?, 
                precio = ?, 
                tipo_producto = ?, 
                categoria_id = ? 
                WHERE id = ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $descripcion, $precio, $tipo_producto, $categoria_id, $product_id]);
        
        $success_message = "Producto actualizado correctamente";
    } catch(PDOException $e) {
        $error = "Error al actualizar producto: " . $e->getMessage();
    }
}

// Procesar formulario de nuevo producto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'add') {
    try {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = (float)$_POST['precio'];
        $imagen = $_POST['imagen'];
        $categoria_id = (int)$_POST['categoria_id'];
        $tallas_id = (int)$_POST['tallas_id'];
        $tipo_producto = $_POST['tipo_producto'];
        $color_id = (int)$_POST['color_id'];
        
        // Validar que tallas_id existe
        $check_talla = $pdo->prepare("SELECT id FROM tallas WHERE id = ?");
        $check_talla->execute([$tallas_id]);
        if (!$check_talla->fetch()) {
            throw new Exception("La talla ID $tallas_id no existe en la tabla tallas");
        }
        
        // Validar que categoria_id existe
        $check_cat = $pdo->prepare("SELECT id FROM categorias WHERE id = ?");
        $check_cat->execute([$categoria_id]);
        if (!$check_cat->fetch()) {
            throw new Exception("La categoría ID $categoria_id no existe en la tabla categorias");
        }
        
        $sql = "INSERT INTO productos (nombre, descripcion, precio, imagen, categoria_id, tallas_id, tipo_producto, color_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $descripcion, $precio, $imagen, $categoria_id, $tallas_id, $tipo_producto, $color_id]);
        
        $success_message = "Producto agregado correctamente";
        $action = ''; // Volver a la lista después de agregar
    } catch(Exception $e) {
        $error = "Error al agregar producto: " . $e->getMessage();
    }
}

// Obtener tallas disponibles
try {
    $stmt_tallas = $pdo->query("SELECT id, nombre FROM tallas ORDER BY nombre");
    $tallas_disponibles = $stmt_tallas->fetchAll();
} catch(PDOException $e) {
    $tallas_disponibles = [];
}

// Obtener categorías disponibles
try {
    $stmt_cat = $pdo->query("SELECT id, nombre FROM categorias ORDER BY nombre");
    $categorias_disponibles = $stmt_cat->fetchAll();
} catch(PDOException $e) {
    $categorias_disponibles = [];
}

// Obtener producto para editar
$product_to_edit = null;
if ($action === 'edit' && $product_id > 0) {
    try {
        $sql = "SELECT * FROM productos WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$product_id]);
        $product_to_edit = $stmt->fetch();
        
        if (!$product_to_edit) {
            $error = "Producto no encontrado";
        }
    } catch(PDOException $e) {
        $error = "Error al cargar producto: " . $e->getMessage();
    }
}

// Obtener productos de la base de datos
try {
    $sql = "SELECT * FROM productos ORDER BY nombre";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $products = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = "Error al cargar productos: " . $e->getMessage();
    $products = [];
}

// Función para formatear precio
function formatPrice($price) {
    return number_format($price, 0, ',', '.');
}
?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle"></i>
        <?php echo $error; ?>
    </div>
<?php endif; ?>

<?php if (isset($success_message)): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <?php echo $success_message; ?>
    </div>
<?php endif; ?>

<?php if ($action === 'edit' && $product_to_edit): ?>
    <!-- Formulario de edición -->
    <div class="card">
        <div class="card-header">
            <h3>Editar Producto</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="?page=productos&action=edit&id=<?php echo $product_id; ?>">
                <div class="form-group">
                    <label for="nombre">Nombre del Producto</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" 
                           value="<?php echo htmlspecialchars($product_to_edit['nombre']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?php echo htmlspecialchars($product_to_edit['descripcion']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="precio">Precio</label>
                    <input type="number" class="form-control" id="precio" name="precio" 
                           value="<?php echo $product_to_edit['precio']; ?>" step="0.01" required>
                </div>
                
                <div class="form-group">
                    <label for="tipo_producto">Tipo de Producto</label>
                    <select class="form-control" id="tipo_producto" name="tipo_producto" required>
                        <option value="niño" <?php echo $product_to_edit['tipo_producto'] === 'niño' ? 'selected' : ''; ?>>Niño</option>
                        <option value="niña" <?php echo $product_to_edit['tipo_producto'] === 'niña' ? 'selected' : ''; ?>>Niña</option>
                        <option value="caballero" <?php echo $product_to_edit['tipo_producto'] === 'caballero' ? 'selected' : ''; ?>>Caballero</option>
                        <option value="dama" <?php echo $product_to_edit['tipo_producto'] === 'dama' ? 'selected' : ''; ?>>Dama</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="categoria_id">Categoría</label>
                    <input type="number" class="form-control" id="categoria_id" name="categoria_id" 
                           value="<?php echo $product_to_edit['categoria_id']; ?>">
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                    <a href="?page=productos" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
<?php elseif ($action === 'add'): ?>
    <!-- Formulario de nuevo producto -->
    <div class="card">
        <div class="card-header">
            <h3>Nuevo Producto</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="?page=productos&action=add">
                <div class="form-group">
                    <label for="nombre">Nombre del Producto</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                
                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="precio">Precio</label>
                    <input type="number" class="form-control" id="precio" name="precio" step="0.01" required>
                </div>
                
                <div class="form-group">
                    <label for="imagen">Ruta de la Imagen</label>
                    <input type="text" class="form-control" id="imagen" name="imagen" placeholder="ej: vista_cliente/img/Niños/producto.jpg">
                </div>
                
                <div class="form-group">
                    <label for="categoria_id">Categoría</label>
                    <select class="form-control" id="categoria_id" name="categoria_id" required>
                        <option value="">Seleccionar categoría</option>
                        <?php foreach ($categorias_disponibles as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['nombre']); ?> (ID: <?php echo $cat['id']; ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="tallas_id">Talla</label>
                    <select class="form-control" id="tallas_id" name="tallas_id" required>
                        <option value="">Seleccionar talla</option>
                        <?php foreach ($tallas_disponibles as $talla): ?>
                            <option value="<?php echo $talla['id']; ?>"><?php echo htmlspecialchars($talla['nombre']); ?> (ID: <?php echo $talla['id']; ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="tipo_producto">Tipo de Producto</label>
                    <select class="form-control" id="tipo_producto" name="tipo_producto" required>
                        <option value="">Seleccionar tipo</option>
                        <option value="niño">Niño</option>
                        <option value="niña">Niña</option>
                        <option value="caballero">Caballero</option>
                        <option value="dama">Dama</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="color_id">Color ID (opcional)</label>
                    <input type="number" class="form-control" id="color_id" name="color_id" value="1" min="1">
                    <small class="form-text text-muted">Si no tienes colores definidos, usa 1 como valor por defecto</small>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Agregar Producto
                    </button>
                    <a href="?page=productos" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
<?php else: ?>
    <!-- Lista de productos (código existente) -->
    <div class="card">
        <div class="card-header">
            <div class="filters">
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Buscar productos...">
                </div>
                <select class="filter-select">
                    <option value="all">Todos los tipos</option>
                    <option value="niño">Niño</option>
                    <option value="niña">Niña</option>
                    <option value="caballero">Caballero</option>
                    <option value="dama">Dama</option>
                </select>
            </div>
            
            <a href="?page=productos&action=add" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Nuevo Producto
            </a>
        </div>
        
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Tipo</th>
                        <th>Categoría</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">No hay productos en la base de datos</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td>
                                    <div class="product-info">
                                        <div class="product-image">
                                            <?php if ($product['imagen']): ?>
                                                <img src="<?php echo $product['imagen']; ?>" alt="<?php echo htmlspecialchars($product['nombre']); ?>" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                            <?php else: ?>
                                                <div style="width: 40px; height: 40px; background-color: #f8fafc; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">📦</div>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <div class="product-name"><?php echo htmlspecialchars($product['nombre']); ?></div>
                                            <div class="product-id">ID: <?php echo $product['id']; ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars(substr($product['descripcion'], 0, 50)) . (strlen($product['descripcion']) > 50 ? '...' : ''); ?></td>
                                <td>$<?php echo formatPrice($product['precio']); ?></td>
                                <td>
                                    <span class="status-badge success">
                                        <?php echo ucfirst(htmlspecialchars($product['tipo_producto'] ?? 'N/A')); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($product['categoria_id'] ?? 'Sin categoría'); ?></td>
                                <td>
                                                                    <div class="action-buttons">
                                    <a href="?page=productos&action=edit&id=<?php echo $product['id']; ?>" 
                                       class="btn btn-primary btn-sm action-btn" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?page=productos&action=delete&id=<?php echo $product['id']; ?>" 
                                       class="btn btn-danger btn-sm btn-delete action-btn" title="Eliminar"
                                       onclick="return confirm('¿Estás seguro de eliminar este producto?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="table-footer">
            <div class="table-info">
                Mostrando <?php echo count($products); ?> productos de la base de datos
            </div>
        </div>
    </div>
<?php endif; ?>

 
</style> 