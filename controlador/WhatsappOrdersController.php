<?php
require_once __DIR__ . '/../modelo/WhatsappOrder.php';

$processed_order = null;
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['mensaje'])) {
    $mensaje = $_POST['mensaje'];
    $order = WhatsappOrder::fromMessage($mensaje);
    if (!empty($order->nombre) && !empty($order->telefono) && !empty($order->productos)) {
        $processed_order = $order;
    } else {
        $error_message = 'No se pudieron extraer todos los datos necesarios del mensaje. Verifica el formato.';
    }
}

require __DIR__ . '/../vista/vista_whatsapp-orders.php'; 