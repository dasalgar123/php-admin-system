<?php
require_once __DIR__ . '/../modelo/Order.php';

// Obtener todos los pedidos
$orders = Order::all();

// Filtros
$search = isset($_GET['search']) ? $_GET['search'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';

// Filtrar pedidos
$filtered_orders = array_filter($orders, function($order) use ($search, $status_filter) {
    $matches_search = empty($search) || 
                     stripos($order->customer, $search) !== false;
    $matches_status = $status_filter === 'all' || $order->status === $status_filter;
    return $matches_search && $matches_status;
});

// Pasar datos a la vista
require __DIR__ . '/../vista/vista_orders.php'; 