<?php
require_once __DIR__ . '/../config/database.php';

class Analytics {
    public static function getStats() {
        return [
            'users' => 1234,
            'products' => 567,
            'orders' => 89,
            'revenue' => 12345
        ];
    }

    public static function getRecentOrders() {
        return [
            ['id' => '#1234', 'customer' => 'Juan Pérez', 'amount' => 150, 'status' => 'Completado'],
            ['id' => '#1235', 'customer' => 'María García', 'amount' => 200, 'status' => 'Pendiente'],
            ['id' => '#1236', 'customer' => 'Carlos López', 'amount' => 75, 'status' => 'En proceso']
        ];
    }
} 