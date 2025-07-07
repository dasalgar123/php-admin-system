<?php
require_once __DIR__ . '/../config/database.php';

class Product {
    public $id;
    public $name;
    public $category;
    public $price;
    public $stock;
    public $status;
    public $image;

    public function __construct($id, $name, $category, $price, $stock, $status, $image) {
        $this->id = $id;
        $this->name = $name;
        $this->category = $category;
        $this->price = $price;
        $this->stock = $stock;
        $this->status = $status;
        $this->image = $image;
    }

    // Simulación: obtener todos los productos
    public static function all() {
        return [
            new Product(1, 'Producto A', 'Electrónicos', 150.00, 25, 'Disponible', '📱'),
            new Product(2, 'Producto B', 'Ropa', 75.50, 10, 'Disponible', '👕'),
            new Product(3, 'Producto C', 'Hogar', 200.00, 0, 'Agotado', '🏠'),
        ];
    }
} 