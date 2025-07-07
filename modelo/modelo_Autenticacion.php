<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Clase para manejar la autenticación de usuarios
 */
class Autenticacion {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Autenticar usuario con correo y contraseña
     */
    public function login($correo, $contraseña) {
        try {
            // Debug: ver qué se está buscando
            error_log("Intentando login con: correo=$correo, contraseña=$contraseña");
            
            $stmt = $this->pdo->prepare("SELECT * FROM usuario WHERE correo = ? AND contraseña = ?");
            $stmt->execute([$correo, $contraseña]);
            $user = $stmt->fetch();
            
            if ($user) {
                error_log("Usuario encontrado: " . $user['nombre']);
                // Iniciar sesión del usuario
                session_start();
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $user['nombre'];
                $_SESSION['admin_email'] = $user['correo'];
                $_SESSION['admin_role'] = $user['rol'];
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['login_success'] = true; // Marcar login exitoso
                return true;
            } else {
                error_log("Usuario no encontrado");
                return false;
            }
        } catch(PDOException $e) {
            error_log("Error en autenticación: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Verificar si el usuario está autenticado
     */
    public function isLoggedIn() {
        return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    }
    
    /**
     * Cerrar sesión del usuario
     */
    public function logout() {
        session_start();
        session_destroy();
        return true;
    }
    
    /**
     * Obtener información del usuario actual
     */
    public function getCurrentUser() {
        if ($this->isLoggedIn()) {
            return [
                'id' => $_SESSION['admin_id'] ?? '',
                'nombre' => $_SESSION['admin_username'] ?? '',
                'correo' => $_SESSION['admin_email'] ?? '',
                'rol' => $_SESSION['admin_role'] ?? ''
            ];
        }
        return null;
    }
}
?> 