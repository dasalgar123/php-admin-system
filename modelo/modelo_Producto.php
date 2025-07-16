<?php
require_once __DIR__ . '/../config/database.php';

class Producto {
    public $id;
    public $name;
    public $category;
    public $price;
    public $stock;
    public $status;
    public $image;

    public function __construct($id, $nombre, $categoria, $precio, $stock, $estado, $imagen) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->categoria = $categoria;
        $this->precio = $precio;
        $this->stock = $stock;
        $this->estado = $estado;
        $this->imagen = $imagen;
    }

    // Simulación: obtener todos los productos
    public static function todos() {
        return [
            new Producto(1, 'Producto A', 'Electrónicos', 150.00, 25, 'Disponible', '📱'),
            new Producto(2, 'Producto B', 'Ropa', 75.50, 10, 'Disponible', '👕'),
            new Producto(3, 'Producto C', 'Hogar', 200.00, 0, 'Agotado', '🏠'),
        ];
    }
} 