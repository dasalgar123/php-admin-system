<?php
// Controlador para la gestión de devoluciones
class ControladorDevoluciones {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function obtenerDevoluciones() {
        // Consultar devoluciones desde productos_devoluciones, uniendo con productos para mostrar el nombre
        $stmt = $this->pdo->query('SELECT d.id, d.tipo, d.entidad_id, d.fecha, d.motivo, d.factura_remision, d.producto_id, p.nombre AS producto FROM productos_devoluciones d LEFT JOIN productos p ON d.producto_id = p.id ORDER BY d.fecha DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?> 