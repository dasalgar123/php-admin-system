<?php
session_start();
require_once 'modelo/modelo_Autenticacion.php';
require_once 'config/database.php';

$auth = new Autenticacion($pdo);

// Si el usuario no está autenticado, redirigir al login
if (!$auth->isLoggedIn()) {
    header('Location: controlador/LoginController.php');
    exit;
}

// Si está autenticado, redirigir al panel de administración
header('Location: controlador/ControladorMenuPrincipalPrincipal.php');
exit;

// Procesar formulario de inicio de sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];
    
    if ($auth->login($correo, $contraseña)) {
        header('Location: index.php');
        exit;
    } else {
        $_SESSION['flash_error'] = 'Credenciales incorrectas. Verifica tu correo y contraseña.';
        header('Location: index.php');
        exit;
    }
}

// Procesar cierre de sesión
if (isset($_GET['logout'])) {
    $auth->logout();
    header('Location: index.php');
    exit;
}

// Si el usuario no está autenticado, mostrar página de login
if (!$auth->isLoggedIn()) {
    $error = null;
    if (isset($_SESSION['flash_error'])) {
        $error = $_SESSION['flash_error'];
        unset($_SESSION['flash_error']);
    }
    include 'controlador/LoginController.php';
    exit;
}

// Si el usuario está autenticado, mostrar panel de administración
include 'controlador/ControladorMenuPrincipalPrincipal.php';
?> 