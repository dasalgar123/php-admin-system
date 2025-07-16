<?php
// Controlador para la gestión de traslados
class ControladorTraslados {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function obtenerTraslados() {
        // Verificar si existe la tabla bodegas
        $bodegas_exists = false;
        try {
            $this->pdo->query('SELECT 1 FROM bodegas LIMIT 1');
            $bodegas_exists = true;
        } catch (Exception $e) {}
        
        // Consultar traslados desde productos_traslados, uniendo con bodegas si existen
        if ($bodegas_exists) {
            $stmt = $this->pdo->query('SELECT t.id, t.bodega_origen_id, bo.nombre AS bodega_origen, t.bodega_destino_id, bd.nombre AS bodega_destino, t.fecha, t.motivo FROM productos_traslados t LEFT JOIN bodegas bo ON t.bodega_origen_id = bo.id LEFT JOIN bodegas bd ON t.bodega_destino_id = bd.id ORDER BY t.fecha DESC');
        } else {
            $stmt = $this->pdo->query('SELECT t.id, t.bodega_origen_id, t.bodega_destino_id, t.fecha, t.motivo FROM productos_traslados t ORDER BY t.fecha DESC');
        }
        
        return [
            'traslados' => $stmt->fetchAll(PDO::FETCH_ASSOC),
            'bodegas_exists' => $bodegas_exists
        ];
    }
}
?> 