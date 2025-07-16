<?php
require_once __DIR__ . '/../config/database.php';

class Usuario {
    public $id;
    public $name;
    public $email;
    public $role;
    public $status;
    public $last_login;
    public $avatar;

    public function __construct($id, $nombre, $correo, $rol, $estado, $ultimo_acceso, $avatar) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->correo = $correo;
        $this->rol = $rol;
        $this->estado = $estado;
        $this->ultimo_acceso = $ultimo_acceso;
        $this->avatar = $avatar;
    }

    // Simulación: obtener todos los usuarios
    public static function todos() {
        return [
            new Usuario(1, 'Juan Pérez', 'juan.perez@email.com', 'Admin', 'Activo', '2024-01-15 10:30', 'JP'),
            new Usuario(2, 'María García', 'maria.garcia@email.com', 'Usuario', 'Activo', '2024-01-14 15:45', 'MG'),
            new Usuario(3, 'Carlos López', 'carlos.lopez@email.com', 'Editor', 'Inactivo', '2024-01-10 09:15', 'CL'),
            new Usuario(4, 'Ana Rodríguez', 'ana.rodriguez@email.com', 'Usuario', 'Activo', '2024-01-15 14:20', 'AR'),
            new Usuario(5, 'Luis Martínez', 'luis.martinez@email.com', 'Editor', 'Activo', '2024-01-13 11:30', 'LM'),
        ];
    }
} 