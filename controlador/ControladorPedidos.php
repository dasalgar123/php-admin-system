<?php
// Controlador para la gestión de pedidos
class ControladorPedidos {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function obtenerPedidos() {
        // Consulta para obtener todos los pedidos
        $query = "SELECT * FROM pedidos ORDER BY fecha DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
}
?> 