<?php
require_once 'config/database.php';

class ClienteController {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Procesar acciones
    public function processAction($action, $data) {
        switch ($action) {
            case 'add':
                return $this->addClient($data);
            case 'edit':
                return $this->editClient($data);
            case 'delete':
                return $this->deleteClient($data);
            case 'change_status':
                return $this->changeStatus($data);
            default:
                return ['error' => 'Acción no válida'];
        }
    }
    
    // Agregar cliente
    public function addClient($data) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO cliente (nombre, correo, contraseña, rol) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $data['nombre'],
                $data['correo'],
                $data['contraseña'],
                'cliente'
            ]);
            return ['success' => 'Cliente agregado exitosamente'];
        } catch(PDOException $e) {
            return ['error' => 'Error al agregar cliente: ' . $e->getMessage()];
        }
    }
    
    // Editar cliente
    public function editClient($data) {
        try {
            $stmt = $this->pdo->prepare("UPDATE cliente SET nombre = ?, correo = ?, contraseña = ? WHERE id = ?");
            $stmt->execute([
                $data['nombre'],
                $data['correo'],
                $data['contraseña'],
                $data['id']
            ]);
            return ['success' => 'Cliente actualizado exitosamente'];
        } catch(PDOException $e) {
            return ['error' => 'Error al actualizar cliente: ' . $e->getMessage()];
        }
    }
    
    // Eliminar cliente
    public function deleteClient($data) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM cliente WHERE id = ?");
            $stmt->execute([$data['id']]);
            return ['success' => 'Cliente eliminado exitosamente'];
        } catch(PDOException $e) {
            return ['error' => 'Error al eliminar cliente: ' . $e->getMessage()];
        }
    }
    
    // Cambiar estado del cliente
    public function changeStatus($data) {
        try {
            $stmt = $this->pdo->prepare("UPDATE cliente SET rol = ? WHERE id = ?");
            $stmt->execute([$data['estado'], $data['id']]);
            return ['success' => 'Estado del cliente actualizado exitosamente'];
        } catch(PDOException $e) {
            return ['error' => 'Error al actualizar estado: ' . $e->getMessage()];
        }
    }
    
    // Obtener cliente por ID
    public function getClientById($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM cliente WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch(PDOException $e) {
            return null;
        }
    }
    
    // Obtener todos los clientes con filtros
    public function getClients($search = '', $status_filter = 'all') {
        try {
            $where_conditions = [];
            $params = [];
            
            if (!empty($search)) {
                $where_conditions[] = "(nombre LIKE ? OR correo LIKE ?)";
                $params[] = "%$search%";
                $params[] = "%$search%";
            }
            
            if ($status_filter !== 'all') {
                $where_conditions[] = "rol = ?";
                $params[] = $status_filter;
            }
            
            $where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';
            
            $sql = "SELECT * FROM cliente $where_clause ORDER BY nombre";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
            
        } catch(PDOException $e) {
            return [];
        }
    }
    
    // Obtener estadísticas de clientes
    public function getClientStats($clients) {
        return [
            'total' => count($clients),
            'active' => count(array_filter($clients, fn($c) => $c['rol'] === 'cliente')),
            'inactive' => count(array_filter($clients, fn($c) => $c['rol'] === 'inactivo')),
            'pending' => count(array_filter($clients, fn($c) => $c['rol'] === 'pendiente')),
            'suspended' => count(array_filter($clients, fn($c) => $c['rol'] === 'suspendido'))
        ];
    }
    
    // Función para generar iniciales del cliente
    public function getClientInitials($name) {
        $words = explode(' ', $name);
        $initials = '';
        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        return substr($initials, 0, 2);
    }
    
    // Función para formatear fecha
    public function formatDate($date) {
        if (!$date) return 'N/A';
        return date('d/m/Y', strtotime($date));
    }
    
    // Función para obtener clase CSS del estado
    public function getStatusClass($rol) {
        switch($rol) {
            case 'cliente': return 'status-active';
            case 'inactivo': return 'status-inactive';
            case 'pendiente': return 'status-pending';
            case 'suspendido': return 'status-inactive';
            default: return 'status-inactive';
        }
    }
}
?> 