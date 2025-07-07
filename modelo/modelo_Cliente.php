<?php
require_once __DIR__ . '/../config/database.php';

class Cliente {
    private $id;
    private $nombre;
    private $correo;
    private $contraseña;
    private $rol;
    private $fecha_creacion;
    
    public function __construct($data = []) {
        $this->id = $data['id'] ?? null;
        $this->nombre = $data['nombre'] ?? '';
        $this->correo = $data['correo'] ?? '';
        $this->contraseña = $data['contraseña'] ?? '';
        $this->rol = $data['rol'] ?? 'cliente';
        $this->fecha_creacion = $data['fecha_creacion'] ?? null;
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getCorreo() { return $this->correo; }
    public function getContraseña() { return $this->contraseña; }
    public function getRol() { return $this->rol; }
    public function getFechaCreacion() { return $this->fecha_creacion; }
    
    // Setters
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setCorreo($correo) { $this->correo = $correo; }
    public function setContraseña($contraseña) { $this->contraseña = $contraseña; }
    public function setRol($rol) { $this->rol = $rol; }
    
    // Validación
    public function validate() {
        $errors = [];
        
        if (empty($this->nombre)) {
            $errors[] = 'El nombre es requerido';
        }
        
        if (!empty($this->correo) && !filter_var($this->correo, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'El correo electrónico no es válido';
        }
        
        if (empty($this->contraseña) && !$this->id) {
            $errors[] = 'La contraseña es requerida para nuevos clientes';
        }
        
        return $errors;
    }
    
    // Convertir a array
    public function toArray() {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'correo' => $this->correo,
            'contraseña' => $this->contraseña,
            'rol' => $this->rol,
            'fecha_creacion' => $this->fecha_creacion
        ];
    }
    
    // Generar iniciales
    public function getInitials() {
        $words = explode(' ', $this->nombre);
        $initials = '';
        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        return substr($initials, 0, 2);
    }
    
    // Obtener clase CSS del estado
    public function getStatusClass() {
        switch($this->rol) {
            case 'cliente': return 'status-active';
            case 'inactivo': return 'status-inactive';
            case 'pendiente': return 'status-pending';
            case 'suspendido': return 'status-inactive';
            default: return 'status-inactive';
        }
    }
    
    // Formatear fecha
    public function getFormattedDate() {
        if (!$this->fecha_creacion) return 'N/A';
        return date('d/m/Y', strtotime($this->fecha_creacion));
    }
}
?> 