<?php
// Controlador para la gestión de ventas
class ControladorVentas {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function obtenerVentas() {
        // Verificar si existe la tabla clientes
        $clientes_exists = false;
        try {
            $this->pdo->query('SELECT 1 FROM clientes LIMIT 1');
            $clientes_exists = true;
        } catch (Exception $e) {}
        
        // Consultar ventas desde productos_ventas, uniendo con clientes si existe la tabla
        if ($clientes_exists) {
            $stmt = $this->pdo->query('SELECT v.id, v.cliente_id, c.nombre AS cliente, v.fecha, v.factura FROM productos_ventas v LEFT JOIN clientes c ON v.cliente_id = c.id ORDER BY v.fecha DESC');
        } else {
            $stmt = $this->pdo->query('SELECT v.id, v.cliente_id, v.fecha, v.factura FROM productos_ventas v ORDER BY v.fecha DESC');
        }
        
        return [
            'ventas' => $stmt->fetchAll(PDO::FETCH_ASSOC),
            'clientes_exists' => $clientes_exists
        ];
    }
}
?> 