<?php
require_once __DIR__ . '/../config/database.php';

class Order {
    public $id;
    public $customer;
    public $amount;
    public $status;
    public $date;
    public $payment;

    public function __construct($id, $customer, $amount, $status, $date, $payment) {
        $this->id = $id;
        $this->customer = $customer;
        $this->amount = $amount;
        $this->status = $status;
        $this->date = $date;
        $this->payment = $payment;
    }

    // Simulación: obtener todos los pedidos
    public static function all() {
        return [
            new Order('#1234', 'Juan Pérez', 150.00, 'COMPLETADO', '2024-01-15', 'Tarjeta'),
            new Order('#1235', 'María García', 200.00, 'PENDIENTE', '2024-01-14', 'Efectivo'),
            new Order('#1236', 'Carlos López', 75.00, 'EN PROCESO', '2024-01-13', 'Transferencia'),
        ];
    }
} 