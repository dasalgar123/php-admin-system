<?php
// Test del parsing de WhatsApp sin dependencias de BD
class WhatsappOrder {
    public $fecha;
    public $hora;
    public $productos;
    public $total;
    public $nombre;
    public $telefono;
    public $correo;
    public $direccion;
    public $fecha_entrega;

    public function __construct($data) {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    // Procesar un mensaje de WhatsApp (parsing mejorado)
    public static function fromMessage($mensaje) {
        $order_data = [];
        
        // Normalizar saltos de línea y quitar espacios extra
        $mensaje = str_replace(["\r\n", "\r"], "\n", $mensaje);
        $mensaje = preg_replace('/ +/', ' ', $mensaje);
        
        // Extraer fecha y hora (más flexible)
        if (preg_match('/fecha\s*:\s*(.*?)(?:\n|$)/i', $mensaje, $matches)) {
            $order_data['fecha'] = trim($matches[1]);
        }
        if (preg_match('/hora\s*:\s*(.*?)(?:\n|$)/i', $mensaje, $matches)) {
            $order_data['hora'] = trim($matches[1]);
        }

        // Extraer productos (más flexible)
        $productos = [];
        
        // Buscar sección de productos con diferentes formatos
        $productos_patterns = [
            '/productos:(.*?)(?:total|subtotal|$)/si',
            '/productos\s*:(.*?)(?:total|subtotal|$)/si'
        ];
        
        $productos_texto = '';
        foreach ($productos_patterns as $pattern) {
            if (preg_match($pattern, $mensaje, $matches)) {
                $productos_texto = $matches[1];
                break;
            }
        }
        
        if (!empty($productos_texto)) {
            // Dividir por líneas y procesar cada producto
            $lineas = explode("\n", trim($productos_texto));
            $producto_actual = null;
            
            foreach ($lineas as $linea) {
                $linea = trim($linea);
                if (empty($linea)) continue;
                
                // Si la línea no empieza con espacios o caracteres especiales, es un nuevo producto
                if (!preg_match('/^\s*[-*•]/', $linea) && !preg_match('/^\s*(detalle|color|talla|cantidad|precio)/i', $linea) && !preg_match('/^\s*\d+$/', $linea)) {
                    // Guardar producto anterior si existe
                    if ($producto_actual && !empty($producto_actual['nombre'])) {
                        $productos[] = $producto_actual;
                    }
                    
                    // Nuevo producto
                    $producto_actual = [
                        'nombre' => $linea,
                        'color' => '',
                        'talla' => '',
                        'cantidad' => '',
                        'precio_unitario' => '',
                        'precio_total' => ''
                    ];
                } else {
                    // Es un detalle del producto actual
                    if ($producto_actual) {
                        // Extraer color y talla
                        if (preg_match('/detalle\s*:\s*(.*?)(?:\s*\/\s*(\d+))?/i', $linea, $matches)) {
                            $producto_actual['color'] = trim($matches[1]);
                            if (isset($matches[2])) {
                                $producto_actual['talla'] = trim($matches[2]);
                            }
                        }
                        
                        // Extraer cantidad
                        if (preg_match('/cantidad\s*:\s*(\d+)/i', $linea, $matches)) {
                            $producto_actual['cantidad'] = trim($matches[1]);
                        }
                        
                        // Extraer precio
                        if (preg_match('/precio\s*:\s*\$?([\d\.,]+)/i', $linea, $matches)) {
                            $producto_actual['precio_unitario'] = trim($matches[1]);
                        }
                        
                        // Si la línea es solo un número, puede ser la cantidad
                        if (preg_match('/^\s*(\d+)\s*$/', $linea, $matches)) {
                            $producto_actual['cantidad'] = trim($matches[1]);
                        }
                    }
                }
            }
            
            // Agregar el último producto
            if ($producto_actual && !empty($producto_actual['nombre'])) {
                $productos[] = $producto_actual;
            }
        }
        
        // Extraer total/subtotal
        if (preg_match('/(?:total|subtotal)\s*:\s*\$?([\d\.,]+)/i', $mensaje, $matches)) {
            $order_data['total'] = trim($matches[1]);
        }

        // Extraer datos del cliente (más flexible)
        $cliente_seccion = $mensaje;
        if (preg_match('/datos del cliente:(.*?)(?:productos|$)/si', $mensaje, $matches)) {
            $cliente_seccion = $matches[1];
        }
        
        // Extraer nombre
        if (preg_match('/nombre\s*:\s*(.*?)(?:\n|$)/i', $cliente_seccion, $matches)) {
            $order_data['nombre'] = trim($matches[1]);
        }
        
        // Extraer teléfono (más flexible)
        if (preg_match('/tel[eé]fono\s*:\s*(.*?)(?:\n|$)/i', $cliente_seccion, $matches)) {
            $order_data['telefono'] = trim($matches[1]);
        }
        
        // Extraer correo
        if (preg_match('/correo\s*:\s*(.*?)(?:\n|$)/i', $cliente_seccion, $matches)) {
            $order_data['correo'] = trim($matches[1]);
        }
        
        // Extraer dirección
        if (preg_match('/direcci[oó]n\s*:\s*(.*?)(?:\n|$)/i', $cliente_seccion, $matches)) {
            $order_data['direccion'] = trim($matches[1]);
        }
        
        // Extraer fecha de entrega
        if (preg_match('/fecha de entrega\s*:\s*(.*?)(?:\n|$)/i', $mensaje, $matches)) {
            $order_data['fecha_entrega'] = trim($matches[1]);
        }
        
        $order_data['productos'] = $productos;
        
        return new WhatsappOrder($order_data);
    }
}

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
echo "Mensaje de prueba:\n";
echo $mensaje . "\n\n";

// Procesar el mensaje
$order = WhatsappOrder::fromMessage($mensaje);

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
    echo "✅ EL SISTEMA FUNCIONA CORRECTAMENTE\n";
} else {
    echo "❌ CAMPOS FALTANTES: " . implode(', ', $missing_fields) . "\n";
    echo "❌ NECESITA MEJORAS EN EL PARSING\n";
}

echo "\n=== FIN DEL TEST ===\n";
?> 