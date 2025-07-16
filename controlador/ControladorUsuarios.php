<?php
require_once __DIR__ . '/../config/database.php';

class UsersController {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Procesar acciones CRUD
    public function procesarAcciones() {
        $mensaje = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Agregar o editar usuario
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            $nombre = trim($_POST['nombre'] ?? '');
            $correo = trim($_POST['correo'] ?? '');
            $rol = trim($_POST['rol'] ?? 'usuario');
            $password = $_POST['password'] ?? '';
            
            if ($nombre && $correo && $rol) {
                if ($id > 0) {
                    // Editar usuario
                    if ($password) {
                        $hash = password_hash($password, PASSWORD_DEFAULT);
                        $sql = "UPDATE usuario SET nombre=?, correo=?, contraseña=?, rol=? WHERE id=?";
                        $params = [$nombre, $correo, $hash, $rol, $id];
                    } else {
                        $sql = "UPDATE usuario SET nombre=?, correo=?, rol=? WHERE id=?";
                        $params = [$nombre, $correo, $rol, $id];
                    }
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute($params);
                    $mensaje = 'Usuario actualizado correctamente.';
                } else {
                    // Agregar usuario
                    if ($password) {
                        $hash = password_hash($password, PASSWORD_DEFAULT);
                        $sql = "INSERT INTO usuario (nombre, correo, contraseña, rol) VALUES (?, ?, ?, ?)";
                        $stmt = $this->pdo->prepare($sql);
                        $stmt->execute([$nombre, $correo, $hash, $rol]);
                        $mensaje = 'Usuario agregado correctamente.';
                    } else {
                        $mensaje = 'La contraseña es obligatoria para nuevos usuarios.';
                    }
                }
            } else {
                $mensaje = 'Todos los campos son obligatorios.';
            }
        }
        
        // Eliminar usuario
        if (isset($_GET['eliminar'])) {
            $id = intval($_GET['eliminar']);
            if ($id > 0) {
                $stmt = $this->pdo->prepare("DELETE FROM usuario WHERE id=?");
                $stmt->execute([$id]);
                $mensaje = 'Usuario eliminado correctamente.';
            }
        }
        
        return $mensaje;
    }
    
    // Obtener todos los usuarios
    public function obtenerUsuarios() {
        $stmt = $this->pdo->query("SELECT id, nombre, correo, rol FROM usuario ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Obtener usuario para editar
    public function obtenerUsuarioParaEditar($id) {
        $stmt = $this->pdo->prepare("SELECT id, nombre, correo, rol FROM usuario WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Verificar si se está editando
    public function estaEditando() {
        return isset($_GET['editar']);
    }
    
    // Obtener ID del usuario a editar
    public function obtenerIdEditar() {
        return isset($_GET['editar']) ? intval($_GET['editar']) : 0;
    }
}

// Inicializar el controlador
$usersController = new UsersController($pdo);
$mensaje = $usersController->procesarAcciones();
$usuarios = $usersController->obtenerUsuarios();

// Si se va a editar
$usuario_editar = null;
if ($usersController->estaEditando()) {
    $id = $usersController->obtenerIdEditar();
    $usuario_editar = $usersController->obtenerUsuarioParaEditar($id);
}
?> 