<?php
// Controlador para la gestión de clientes
class ControladorClientes {
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
            $correo = trim($_POST['correo'] ?? '');
            $contraseña = trim($_POST['contraseña'] ?? '');
            $rol = trim($_POST['rol'] ?? 'cliente');
            
            if ($nombre && $correo) {
                if ($id > 0) {
                    // Actualizar cliente existente
                    if ($contraseña) {
                        $sql = "UPDATE cliente SET nombre=?, correo=?, contraseña=?, rol=? WHERE id=?";
                        $stmt = $this->pdo->prepare($sql);
                        $stmt->execute([$nombre, $correo, $contraseña, $rol, $id]);
                    } else {
                        $sql = "UPDATE cliente SET nombre=?, correo=?, rol=? WHERE id=?";
                        $stmt = $this->pdo->prepare($sql);
                        $stmt->execute([$nombre, $correo, $rol, $id]);
                    }
                    $mensaje = 'Cliente actualizado correctamente.';
                } else {
                    // Insertar nuevo cliente
                    if (!$contraseña) {
                        $contraseña = 'cliente123'; // Contraseña por defecto
                    }
                    $sql = "INSERT INTO cliente (nombre, correo, contraseña, rol) VALUES (?, ?, ?, ?)";
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute([$nombre, $correo, $contraseña, $rol]);
                    $mensaje = 'Cliente agregado correctamente.';
                }
            } else {
                $mensaje = 'Nombre y correo son obligatorios.';
            }
        }
        
        // Procesar eliminación
        if (isset($_GET['eliminar'])) {
            $id = intval($_GET['eliminar']);
            if ($id > 0) {
                $stmt = $this->pdo->prepare("DELETE FROM cliente WHERE id=?");
                $stmt->execute([$id]);
                $mensaje = 'Cliente eliminado correctamente.';
            }
        }
        
        return $mensaje;
    }
    
    public function obtenerClientes() {
        $stmt = $this->pdo->query("SELECT id, nombre, correo, rol FROM cliente ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function obtenerClienteParaEditar() {
        if (isset($_GET['editar'])) {
            $id = intval($_GET['editar']);
            $stmt = $this->pdo->prepare("SELECT id, nombre, correo, rol FROM cliente WHERE id=?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return null;
    }
}
?> 