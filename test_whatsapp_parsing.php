<?php
require_once __DIR__ . '/modelo/modelo_WhatsappOrder.php';

// Mensaje de prueba basado en la captura de pantalla
$test_message = "PEDIDO GARDEM

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

echo "=== TESTING WHATSAPP ORDER PARSING ===\n\n";
echo "Mensaje de prueba:\n";
echo $test_message . "\n\n";

// Procesar el mensaje
$order = WhatsappOrder::fromMessage($test_message);

echo "=== RESULTADOS ===\n";
echo "Nombre: " . ($order->nombre ?? 'NO ENCONTRADO') . "\n";
echo "Teléfono: " . ($order->telefono ?? 'NO ENCONTRADO') . "\n";
echo "Correo: " . ($order->correo ?? 'NO ENCONTRADO') . "\n";
echo "Dirección: " . ($order->direccion ?? 'NO ENCONTRADO') . "\n";
echo "Total: " . ($order->total ?? 'NO ENCONTRADO') . "\n";
echo "Productos encontrados: " . count($order->productos) . "\n\n";

if (!empty($order->productos)) {
    echo "=== PRODUCTOS ===\n";
    foreach ($order->productos as $index => $producto) {
        echo "Producto " . ($index + 1) . ":\n";
        echo "  Nombre: " . $producto['nombre'] . "\n";
        echo "  Color: " . $producto['color'] . "\n";
        echo "  Talla: " . $producto['talla'] . "\n";
        echo "  Cantidad: " . $producto['cantidad'] . "\n";
        echo "  Precio Unit: " . $producto['precio_unitario'] . "\n";
        echo "  Precio Total: " . $producto['precio_total'] . "\n\n";
    }
}

// Verificar si todos los campos requeridos están presentes
$required_fields = ['nombre', 'telefono', 'productos', 'total'];
$missing_fields = [];

foreach ($required_fields as $field) {
    if (empty($order->$field)) {
        $missing_fields[] = $field;
    }
}

if (empty($missing_fields)) {
    echo "✅ TODOS LOS CAMPOS REQUERIDOS ENCONTRADOS\n";
} else {
    echo "❌ CAMPOS FALTANTES: " . implode(', ', $missing_fields) . "\n";
}

echo "\n=== FIN DEL TEST ===\n";
?> 