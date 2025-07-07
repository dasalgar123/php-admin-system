<?php
require_once __DIR__ . '/../modelo/Product.php';
require_once __DIR__ . '/../config/database.php';

class ProductsController {
    private $pdo;
    
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    
    // Obtener todos los productos
    public function getAllProducts() {
        return Product::all();
    }
    
    // Actualizar inventario después de entrada
    public function actualizarInventarioEntrada($producto_id, $bodega_id, $cantidad) {
        try {
            $sql = "INSERT INTO inventario_bodega (producto_id, bodega_id, stock_actual) 
                    VALUES (?, ?, ?) 
                    ON DUPLICATE KEY UPDATE stock_actual = stock_actual + ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$producto_id, $bodega_id, $cantidad, $cantidad]);
            return true;
        } catch (Exception $e) {
            error_log("Error actualizando inventario entrada: " . $e->getMessage());
            return false;
        }
    }
    
    // Actualizar inventario después de salida
    public function actualizarInventarioSalida($producto_id, $bodega_id, $cantidad) {
        try {
            $sql = "INSERT INTO inventario_bodega (producto_id, bodega_id, stock_actual) 
                    VALUES (?, ?, 0) 
                    ON DUPLICATE KEY UPDATE stock_actual = GREATEST(stock_actual - ?, 0)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$producto_id, $bodega_id, $cantidad]);
            return true;
        } catch (Exception $e) {
            error_log("Error actualizando inventario salida: " . $e->getMessage());
            return false;
        }
    }
    
    // Actualizar inventario después de devolución
    public function actualizarInventarioDevolucion($producto_id, $bodega_id, $cantidad) {
        try {
            $sql = "INSERT INTO inventario_bodega (producto_id, bodega_id, stock_actual) 
                    VALUES (?, ?, ?) 
                    ON DUPLICATE KEY UPDATE stock_actual = stock_actual + ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$producto_id, $bodega_id, $cantidad, $cantidad]);
            return true;
        } catch (Exception $e) {
            error_log("Error actualizando inventario devolución: " . $e->getMessage());
            return false;
        }
    }
    
    // Actualizar inventario después de garantía
    public function actualizarInventarioGarantia($producto_id, $bodega_id, $cantidad) {
        try {
            $sql = "INSERT INTO inventario_bodega (producto_id, bodega_id, stock_actual) 
                    VALUES (?, ?, ?) 
                    ON DUPLICATE KEY UPDATE stock_actual = stock_actual + ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$producto_id, $bodega_id, $cantidad, $cantidad]);
            return true;
        } catch (Exception $e) {
            error_log("Error actualizando inventario garantía: " . $e->getMessage());
            return false;
        }
    }
    
    // Actualizar inventario después de traslado
    public function actualizarInventarioTraslado($producto_id, $bodega_origen_id, $bodega_destino_id, $cantidad) {
        try {
            $this->pdo->beginTransaction();
            
            // Reducir stock en bodega origen
            $sql_origen = "INSERT INTO inventario_bodega (producto_id, bodega_id, stock_actual) 
                          VALUES (?, ?, 0) 
                          ON DUPLICATE KEY UPDATE stock_actual = GREATEST(stock_actual - ?, 0)";
            $stmt = $this->pdo->prepare($sql_origen);
            $stmt->execute([$producto_id, $bodega_origen_id, $cantidad]);
            
            // Aumentar stock en bodega destino
            $sql_destino = "INSERT INTO inventario_bodega (producto_id, bodega_id, stock_actual) 
                           VALUES (?, ?, ?) 
                           ON DUPLICATE KEY UPDATE stock_actual = stock_actual + ?";
            $stmt = $this->pdo->prepare($sql_destino);
            $stmt->execute([$producto_id, $bodega_destino_id, $cantidad, $cantidad]);
            
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Error actualizando inventario traslado: " . $e->getMessage());
            return false;
        }
    }
    
    // Recalcular inventario completo
    public function recalcularInventarioCompleto() {
        try {
            $this->pdo->beginTransaction();
            
            // Limpiar tabla inventario_bodega
            $this->pdo->exec("TRUNCATE TABLE inventario_bodega");
            
            // Recalcular desde entradas
            $sql_entradas = "INSERT INTO inventario_bodega (producto_id, bodega_id, stock_actual)
                            SELECT 
                                producto_id,
                                COALESCE(bodega_id, 1) as bodega_id,
                                SUM(cantidad) as stock_actual
                            FROM productos_entradas
                            GROUP BY producto_id, COALESCE(bodega_id, 1)
                            ON DUPLICATE KEY UPDATE stock_actual = VALUES(stock_actual)";
            $this->pdo->exec($sql_entradas);
            
            // Restar salidas
            $sql_salidas = "UPDATE inventario_bodega ib
                           JOIN (
                               SELECT 
                                   producto_id,
                                   COALESCE(bodega_id, 1) as bodega_id,
                                   SUM(cantidad) as total_salidas
                               FROM productos_salidas
                               GROUP BY producto_id, COALESCE(bodega_id, 1)
                           ) s ON ib.producto_id = s.producto_id AND ib.bodega_id = s.bodega_id
                           SET ib.stock_actual = GREATEST(ib.stock_actual - s.total_salidas, 0)";
            $this->pdo->exec($sql_salidas);
            
            // Sumar devoluciones
            $sql_devoluciones = "UPDATE inventario_bodega ib
                                JOIN (
                                    SELECT 
                                        producto_id,
                                        SUM(cantidad) as total_devoluciones
                                    FROM productos_devoluciones
                                    GROUP BY producto_id
                                ) d ON ib.producto_id = d.producto_id
                                SET ib.stock_actual = ib.stock_actual + d.total_devoluciones";
            $this->pdo->exec($sql_devoluciones);
            
            // Sumar garantías
            $sql_garantias = "UPDATE inventario_bodega ib
                             JOIN (
                                 SELECT 
                                     producto_id,
                                     SUM(cantidad) as total_garantias
                                 FROM productos_garantia
                                 GROUP BY producto_id
                             ) g ON ib.producto_id = g.producto_id
                             SET ib.stock_actual = ib.stock_actual + g.total_garantias";
            $this->pdo->exec($sql_garantias);
            
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Error recalculando inventario: " . $e->getMessage());
            return false;
        }
    }
}

// Instanciar controlador
$controller = new ProductsController();

// Obtener todos los productos
$products = $controller->getAllProducts();

// Filtros
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category_filter = isset($_GET['category']) ? $_GET['category'] : 'all';

// Filtrar productos
$filtered_products = array_filter($products, function($product) use ($search, $category_filter) {
    $matches_search = empty($search) || 
                     stripos($product->name, $search) !== false;
    $matches_category = $category_filter === 'all' || $product->category === $category_filter;
    return $matches_search && $matches_category;
});

// Pasar datos a la vista
require __DIR__ . '/../vista/vista_products.php'; 