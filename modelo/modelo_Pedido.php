<?php
require_once __DIR__ . '/../config/database.php';

class Pedido {
    public $id;
    public $customer;
    public $amount;
    public $status;
    public $date;
    public $payment;

    public function __construct($id, $cliente, $monto, $estado, $fecha, $pago) {
        $this->id = $id;
        $this->cliente = $cliente;
        $this->monto = $monto;
        $this->estado = $estado;
        $this->fecha = $fecha;
        $this->pago = $pago;
    }

    // Simulación: obtener todos los pedidos
    public static function todos() {
        return [
            new Pedido('#1234', 'Juan Pérez', 150.00, 'COMPLETADO', '2024-01-15', 'Tarjeta'),
            new Pedido('#1235', 'María García', 200.00, 'PENDIENTE', '2024-01-14', 'Efectivo'),
            new Pedido('#1236', 'Carlos López', 75.00, 'EN PROCESO', '2024-01-13', 'Transferencia'),
        ];
    }
} 