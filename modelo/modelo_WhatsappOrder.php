<?php
require_once __DIR__ . '/../config/database.php';

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

    // Simulación: procesar un mensaje de WhatsApp (parsing)
    public static function fromMessage($mensaje) {
        $order_data = [];
        // Normalizar saltos de línea y quitar espacios extra
        $mensaje = str_replace(["\r\n", "\r"], "\n", $mensaje);
        $mensaje = preg_replace('/ +/', ' ', $mensaje);
        // Permitir mayúsculas/minúsculas y tildes
        $mensaje = mb_strtolower($mensaje, 'UTF-8');

        // Extraer fecha y hora
        if (preg_match('/fecha\s*:\s*(.*)/i', $mensaje, $matches)) {
            $order_data['fecha'] = trim($matches[1]);
        }
        if (preg_match('/hora\s*:\s*(.*)/i', $mensaje, $matches)) {
            $order_data['hora'] = trim($matches[1]);
        }

        // Extraer productos (más flexible)
        $productos = [];
        if (preg_match('/productos:(.*?)total:/si', $mensaje, $matches)) {
            $productos_texto = $matches[1];
            // Permitir productos separados por líneas vacías o asteriscos
            $bloques = preg_split('/\n\s*\*/', "*" . $productos_texto);
            foreach ($bloques as $bloque) {
                if (trim($bloque) === '') continue;
                // Extraer nombre
                preg_match('/^([^\n]+)\n?/', $bloque, $nombre_match);
                $nombre = isset($nombre_match[1]) ? trim($nombre_match[1]) : '';
                // Color y talla
                preg_match('/color\s*:\s*([^|\n]+)[|,]?\s*talla\s*:\s*([^\n]+)/i', $bloque, $ct_match);
                $color = isset($ct_match[1]) ? trim($ct_match[1]) : '';
                $talla = isset($ct_match[2]) ? trim($ct_match[2]) : '';
                // Cantidad, precio unitario, total (acepta | , o salto de línea)
                preg_match('/cantidad\s*:\s*(\d+)[^\d\n]*([\$\d\.,]+)[^\d\n]*([\$\d\.,]+)/i', $bloque, $cant_match);
                $cantidad = isset($cant_match[1]) ? trim($cant_match[1]) : '';
                $precio_unitario = isset($cant_match[2]) ? trim($cant_match[2]) : '';
                $precio_total = isset($cant_match[3]) ? trim($cant_match[3]) : '';
                if ($nombre) {
                    $productos[] = [
                        'nombre' => $nombre,
                        'color' => $color,
                        'talla' => $talla,
                        'cantidad' => $cantidad,
                        'precio_unitario' => $precio_unitario,
                        'precio_total' => $precio_total
                    ];
                }
            }
        }
        if (preg_match('/total\s*:\s*\$?([\d\.,]+)/i', $mensaje, $matches)) {
            $order_data['total'] = trim($matches[1]);
        }

        // Permitir "DATOS DEL CLIENTE:" como título opcional
        $cliente_seccion = $mensaje;
        if (preg_match('/datos del cliente:(.*)/si', $mensaje, $matches)) {
            $cliente_seccion = $matches[1];
        }
        // Extraer datos del cliente (acepta asterisco, espacios, mayúsculas/minúsculas)
        if (preg_match('/\*?\s*nombre\s*:\s*(.*)/i', $cliente_seccion, $matches)) {
            $order_data['nombre'] = trim($matches[1]);
        }
        if (preg_match('/\*?\s*tel[eé]fono\s*:\s*(.*)/i', $cliente_seccion, $matches)) {
            $order_data['telefono'] = trim($matches[1]);
        }
        if (preg_match('/\*?\s*correo\s*:\s*(.*)/i', $cliente_seccion, $matches)) {
            $order_data['correo'] = trim($matches[1]);
        }
        if (preg_match('/\*?\s*direcci[oó]n\s*:\s*(.*)/i', $cliente_seccion, $matches)) {
            $order_data['direccion'] = trim($matches[1]);
        }
        if (preg_match('/fecha de entrega\s*:\s*(.*)/i', $mensaje, $matches)) {
            $order_data['fecha_entrega'] = trim($matches[1]);
        }
        $order_data['productos'] = $productos;
        // Log temporal para depuración
        file_put_contents(__DIR__ . '/../debug_whatsapp.txt', print_r($order_data, true) . "\nMENSAJE:\n" . $mensaje);
        return new WhatsappOrder($order_data);
    }
} 