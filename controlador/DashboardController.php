<?php
session_start();
require_once '../modelo/modelo_Autenticacion.php';
require_once '../config/database.php';

class DashboardController {
    private $auth;
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->auth = new Autenticacion($pdo);
    }
    
    /**
     * Maneja el proceso del dashboard
     */
    public function handleDashboard() {
        // Manejar logout
        if (isset($_GET['logout'])) {
            session_destroy();
            header('Location: LoginController.php');
            exit;
        }

        // Verificar autenticación
        if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
            header('Location: LoginController.php');
            exit;
        }

        // Obtener página actual
        $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

        // Datos simulados para estadísticas
        $stats = [
            'users' => 1234,
            'products' => 567,
            'orders' => 89,
            'revenue' => 12345
        ];

        // Pedidos recientes simulados
        $recent_orders = [
            ['id' => '#1234', 'customer' => 'Juan Pérez', 'amount' => 150, 'status' => 'Completado'],
            ['id' => '#1235', 'customer' => 'María García', 'amount' => 200, 'status' => 'Pendiente'],
            ['id' => '#1236', 'customer' => 'Carlos López', 'amount' => 75, 'status' => 'En proceso']
        ];

        $currentUser = $this->auth->getCurrentUser();
        
        return [
            'page' => $page,
            'stats' => $stats,
            'recent_orders' => $recent_orders,
            'currentUser' => $currentUser
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
$dashboardController = new DashboardController($pdo);
$data = $dashboardController->handleDashboard();

// Incluir la vista
include '../vista/vista_dashboard.php';
?> 