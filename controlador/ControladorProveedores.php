<?php
// Controlador para la gestión de proveedores
class ControladorProveedores {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function procesarAcciones() {
        $mensaje = '';
        
        // Procesar acciones CRUD
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            $nombre = trim($_POST['nombre'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            $correo = trim($_POST['correo'] ?? '');
            $direccion = trim($_POST['direccion'] ?? '');
            
            if ($nombre && $telefono) {
                if ($id > 0) {
                    $sql = "UPDATE proveedor SET nombre=?, telefono=?, correo=?, direccion=? WHERE id=?";
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute([$nombre, $telefono, $correo, $direccion, $id]);
                    $mensaje = 'Proveedor actualizado correctamente.';
                } else {
                    $sql = "INSERT INTO proveedor (nombre, telefono, correo, direccion, fecha_creacion) VALUES (?, ?, ?, ?, NOW())";
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute([$nombre, $telefono, $correo, $direccion]);
                    $mensaje = 'Proveedor agregado correctamente.';
                }
            } else {
                $mensaje = 'Nombre y teléfono son obligatorios.';
            }
        }
        
        // Procesar eliminación
        if (isset($_GET['eliminar'])) {
            $id = intval($_GET['eliminar']);
            if ($id > 0) {
                $stmt = $this->pdo->prepare("DELETE FROM proveedor WHERE id=?");
                $stmt->execute([$id]);
                $mensaje = 'Proveedor eliminado correctamente.';
            }
        }
        
        return $mensaje;
    }
    
    public function obtenerProveedores() {
        $stmt = $this->pdo->query("SELECT id, nombre, telefono, correo, direccion, fecha_creacion FROM proveedor ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function obtenerProveedorParaEditar() {
        if (isset($_GET['editar'])) {
            $id = intval($_GET['editar']);
            $stmt = $this->pdo->prepare("SELECT id, nombre, telefono, correo, direccion FROM proveedor WHERE id=?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return null;
    }
}
?> 