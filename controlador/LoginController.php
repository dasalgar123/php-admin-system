<?php
session_start();
require_once '../config/database.php';
require_once '../modelo/modelo_Autenticacion.php';

class LoginController {
    private $auth;
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->auth = new Autenticacion($pdo);
    }
    
    /**
     * Maneja el proceso de login
     */
    public function handleLogin() {
        // Si ya está logueado, redirigir al panel principal
        if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']) {
            header('Location: ../index.php');
            exit;
        }
        
        $error = '';
        $username = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
            $correo = trim($_POST['username'] ?? '');
            $contraseña = trim($_POST['password'] ?? '');
            $username = $correo; // Para mantener el valor en el formulario en caso de error
            
            if ($this->auth->login($correo, $contraseña)) {
                header('Location: ../index.php');
                exit;
            } else {
                $error = 'Usuario o contraseña incorrectos';
            }
        }
        
        return [
            'error' => $error,
            'username' => $username
        ];
    }
    
    /**
     * Verifica si el usuario está autenticado
     */
    public function isLoggedIn() {
        return $this->auth->isLoggedIn();
    }
}

// Instanciar el controlador y manejar la solicitud
$loginController = new LoginController($pdo);
$data = $loginController->handleLogin();

// Incluir la vista
include '../vista/vista_login.php';
?> 