<?php
// Test simple del parsing de WhatsApp
require_once 'modelo/modelo_WhatsappOrder.php';

// Mensaje de prueba
$mensaje = "PEDIDO GARDEM

DATOS DEL CLIENTE
Nombre: daniel
Teléfono: 3216798086
Correo: daniel_asalinas@soy.sena.edu.co
Dirección: mz 32 # 16.18 aniversario 1
Fecha: 16/07/2025 00:13

PRODUCTOS
Boxer Unisex Básico
Detalle: Amarillo / 12
Cantidad: -
12
+ | Precio: $5.000

Subtotal: $60.000";

echo "=== PRUEBA DE PARSING WHATSAPP ===\n\n";

// Procesar el mensaje
$order = WhatsappOrder::fromMessage($mensaje);

echo "Resultados:\n";
echo "Nombre: " . ($order->nombre ?? 'NO ENCONTRADO') . "\n";
echo "Teléfono: " . ($order->telefono ?? 'NO ENCONTRADO') . "\n";
echo "Correo: " . ($order->correo ?? 'NO ENCONTRADO') . "\n";
echo "Dirección: " . ($order->direccion ?? 'NO ENCONTRADO') . "\n";
echo "Total: " . ($order->total ?? 'NO ENCONTRADO') . "\n";
echo "Productos: " . count($order->productos) . "\n\n";

if (!empty($order->productos)) {
    foreach ($order->productos as $i => $prod) {
        echo "Producto " . ($i+1) . ": " . $prod['nombre'] . "\n";
        echo "  Cantidad: " . $prod['cantidad'] . "\n";
        echo "  Precio: " . $prod['precio_unitario'] . "\n";
    }
}

echo "\n=== FIN DE PRUEBA ===\n";
?> 