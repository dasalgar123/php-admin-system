<?php
require_once __DIR__ . '/../config/database.php';

class User {
    public $id;
    public $name;
    public $email;
    public $role;
    public $status;
    public $last_login;
    public $avatar;

    public function __construct($id, $name, $email, $role, $status, $last_login, $avatar) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->role = $role;
        $this->status = $status;
        $this->last_login = $last_login;
        $this->avatar = $avatar;
    }

    // Simulación: obtener todos los usuarios
    public static function all() {
        return [
            new User(1, 'Juan Pérez', 'juan.perez@email.com', 'Admin', 'Activo', '2024-01-15 10:30', 'JP'),
            new User(2, 'María García', 'maria.garcia@email.com', 'Usuario', 'Activo', '2024-01-14 15:45', 'MG'),
            new User(3, 'Carlos López', 'carlos.lopez@email.com', 'Editor', 'Inactivo', '2024-01-10 09:15', 'CL'),
            new User(4, 'Ana Rodríguez', 'ana.rodriguez@email.com', 'Usuario', 'Activo', '2024-01-15 14:20', 'AR'),
            new User(5, 'Luis Martínez', 'luis.martinez@email.com', 'Editor', 'Activo', '2024-01-13 11:30', 'LM'),
        ];
    }
} 