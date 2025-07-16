<?php
// Controlador para la gestión de productos
class ControladorProductos {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function obtenerProductos() {
        // Consultar productos reales
        $stmt = $this->pdo->query('SELECT id, nombre, descripcion, precio, imagen, categoria_id, tallas_id, tipo_producto, color_id FROM productos ORDER BY id DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?> 