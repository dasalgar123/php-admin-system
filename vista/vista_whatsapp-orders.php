<?php
// Procesar mensaje de WhatsApp
$processed_order = null;
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['mensaje'])) {
    $mensaje = $_POST['mensaje'];
    
    // Extraer datos usando expresiones regulares
    $order_data = [];
    
    // Extraer fecha y hora
    if (preg_match('/Fecha: (.*)/', $mensaje, $matches)) {
        $order_data['fecha'] = trim($matches[1]);
    }
    if (preg_match('/Hora: (.*)/', $mensaje, $matches)) {
        $order_data['hora'] = trim($matches[1]);
    }
    
    // Extraer productos
    $productos = [];
    if (preg_match('/PRODUCTOS:(.*?)TOTAL:/s', $mensaje, $matches)) {
        $productos_texto = $matches[1];
        // Buscar líneas que contengan productos
        preg_match_all('/\* (.*?)\n.*?Color: (.*?) \| Talla: (.*?)\n.*?Cantidad: (.*?) \| \$(.*?) \| \$(.*?)/', $productos_texto, $productos_matches, PREG_SET_ORDER);
        
        foreach ($productos_matches as $producto) {
            $productos[] = [
                'nombre' => trim($producto[1]),
                'color' => trim($producto[2]),
                'talla' => trim($producto[3]),
                'cantidad' => trim($producto[4]),
                'precio_unitario' => trim($producto[5]),
                'precio_total' => trim($producto[6])
            ];
        }
    }
    
    // Extraer total
    if (preg_match('/TOTAL: \$(.*?)/', $mensaje, $matches)) {
        $order_data['total'] = trim($matches[1]);
    }
    
    // Extraer datos del cliente
    if (preg_match('/Nombre: (.*?)/', $mensaje, $matches)) {
        $order_data['nombre'] = trim($matches[1]);
    }
    if (preg_match('/Teléfono: (.*?)/', $mensaje, $matches)) {
        $order_data['telefono'] = trim($matches[1]);
    }
    if (preg_match('/Correo: (.*?)/', $mensaje, $matches)) {
        $order_data['correo'] = trim($matches[1]);
    }
    if (preg_match('/Dirección: (.*?)/', $mensaje, $matches)) {
        $order_data['direccion'] = trim($matches[1]);
    }
    if (preg_match('/Fecha de entrega: (.*?)/', $mensaje, $matches)) {
        $order_data['fecha_entrega'] = trim($matches[1]);
    }
    
    // Verificar si se extrajeron datos suficientes
    if (!empty($order_data['nombre']) && !empty($order_data['telefono']) && !empty($productos)) {
        $order_data['productos'] = $productos;
        $processed_order = $order_data;
        
        // Aquí puedes guardar en base de datos
        // saveOrderToDatabase($order_data);
        
    } else {
        $error_message = 'No se pudieron extraer todos los datos necesarios del mensaje. Verifica el formato.';
    }
}

// Función para guardar en base de datos (ejemplo)
function saveOrderToDatabase($order_data) {
    // Aquí iría el código para guardar en MySQL/PostgreSQL
    // Ejemplo:
    /*
    $pdo = new PDO("mysql:host=localhost;dbname=admin_db", "user", "password");
    
    // Insertar cliente
    $stmt = $pdo->prepare("INSERT INTO clientes (nombre, telefono, correo, direccion) VALUES (?, ?, ?, ?)");
    $stmt->execute([$order_data['nombre'], $order_data['telefono'], $order_data['correo'], $order_data['direccion']]);
    $cliente_id = $pdo->lastInsertId();
    
    // Insertar pedido
    $stmt = $pdo->prepare("INSERT INTO pedidos (cliente_id, total, fecha_entrega, estado) VALUES (?, ?, ?, 'Pendiente')");
    $stmt->execute([$cliente_id, $order_data['total'], $order_data['fecha_entrega']]);
    $pedido_id = $pdo->lastInsertId();
    
    // Insertar productos del pedido
    foreach ($order_data['productos'] as $producto) {
        $stmt = $pdo->prepare("INSERT INTO pedido_productos (pedido_id, nombre, color, talla, cantidad, precio_unitario, precio_total) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$pedido_id, $producto['nombre'], $producto['color'], $producto['talla'], $producto['cantidad'], $producto['precio_unitario'], $producto['precio_total']]);
    }
    */
}
?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">
            <i class="fab fa-whatsapp"></i>
            Procesar Pedido desde WhatsApp
        </h2>
    </div>
    
    <?php if ($error_message): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" class="whatsapp-form">
        <div class="form-group">
            <label for="mensaje">
                <i class="fab fa-whatsapp"></i>
                Mensaje de WhatsApp
            </label>
            <textarea 
                id="mensaje" 
                name="mensaje" 
                rows="15" 
                class="form-control" 
                placeholder="Pega aquí el mensaje completo de WhatsApp..."
                required
            ><?php echo isset($_POST['mensaje']) ? htmlspecialchars($_POST['mensaje']) : ''; ?></textarea>
            <small class="form-text">
                <i class="fas fa-info-circle"></i>
                Copia y pega el mensaje completo de WhatsApp para procesar automáticamente el pedido.
            </small>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-cogs"></i>
                Procesar Pedido
            </button>
            <button type="button" class="btn btn-secondary" onclick="clearForm()">
                <i class="fas fa-eraser"></i>
                Limpiar
            </button>
        </div>
    </form>
</div>

<?php if ($processed_order): ?>
<div class="card">
    <div class="card-header">
        <h2 class="card-title">
            <i class="fas fa-check-circle text-success"></i>
            Pedido Procesado Correctamente
        </h2>
    </div>
    
    <div class="order-summary">
        <div class="order-header">
            <h3>Resumen del Pedido</h3>
            <span class="order-status pending">Pendiente</span>
        </div>
        
        <div class="order-details">
            <div class="detail-section">
                <h4><i class="fas fa-user"></i> Datos del Cliente</h4>
                <div class="detail-grid">
                    <div class="detail-item">
                        <strong>Nombre:</strong> <?php echo htmlspecialchars($processed_order['nombre']); ?>
                    </div>
                    <div class="detail-item">
                        <strong>Teléfono:</strong> 
                        <a href="https://wa.me/<?php echo $processed_order['telefono']; ?>" target="_blank" class="btn btn-success btn-sm">
                            <i class="fab fa-whatsapp"></i>
                            <?php echo htmlspecialchars($processed_order['telefono']); ?>
                        </a>
                    </div>
                    <div class="detail-item">
                        <strong>Correo:</strong> <?php echo htmlspecialchars($processed_order['correo']); ?>
                    </div>
                    <div class="detail-item">
                        <strong>Dirección:</strong> <?php echo htmlspecialchars($processed_order['direccion']); ?>
                    </div>
                    <div class="detail-item">
                        <strong>Fecha de Entrega:</strong> <?php echo htmlspecialchars($processed_order['fecha_entrega']); ?>
                    </div>
                </div>
            </div>
            
            <div class="detail-section">
                <h4><i class="fas fa-box"></i> Productos</h4>
                <div class="products-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Color</th>
                                <th>Talla</th>
                                <th>Cantidad</th>
                                <th>Precio Unit.</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($processed_order['productos'] as $producto): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($producto['color']); ?></td>
                                <td><?php echo htmlspecialchars($producto['talla']); ?></td>
                                <td><?php echo htmlspecialchars($producto['cantidad']); ?></td>
                                <td>$<?php echo number_format($producto['precio_unitario']); ?></td>
                                <td>$<?php echo number_format($producto['precio_total']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="order-total">
                <h4>Total del Pedido: $<?php echo number_format($processed_order['total']); ?></h4>
            </div>
        </div>
        
        <div class="order-actions">
            <button class="btn btn-success" onclick="saveOrder()">
                <i class="fas fa-save"></i>
                Guardar Pedido
            </button>
            <button class="btn btn-primary" onclick="contactClient()">
                <i class="fab fa-whatsapp"></i>
                Contactar Cliente
            </button>
            <button class="btn btn-secondary" onclick="printOrder()">
                <i class="fas fa-print"></i>
                Imprimir Pedido
            </button>
        </div>
    </div>
</div>
<?php endif; ?>

<style>
.whatsapp-form {
    padding: 1rem 0;
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    font-size: 0.875rem;
    font-family: 'Courier New', monospace;
    resize: vertical;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-text {
    color: var(--secondary-color);
    font-size: 0.875rem;
    margin-top: 0.5rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 1rem;
}

.order-summary {
    padding: 1rem 0;
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.order-status {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
}

.order-status.pending {
    background-color: #fef3c7;
    color: var(--warning-color);
}

.detail-section {
    margin-bottom: 2rem;
}

.detail-section h4 {
    color: var(--dark-color);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.detail-item {
    padding: 0.75rem;
    background-color: #f8fafc;
    border-radius: 6px;
}

.products-table {
    overflow-x: auto;
}

.order-total {
    text-align: right;
    padding: 1rem;
    background-color: var(--primary-color);
    color: white;
    border-radius: 8px;
    margin: 1rem 0;
}

.order-total h4 {
    margin: 0;
    color: white;
}

.order-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 2rem;
    padding-top: 1rem;
    border-top: 1px solid var(--border-color);
}

@media (max-width: 768px) {
    .form-actions,
    .order-actions {
        flex-direction: column;
    }
    
    .detail-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function clearForm() {
    document.getElementById('mensaje').value = '';
}

function saveOrder() {
    // Aquí puedes implementar la lógica para guardar en base de datos
    showNotification('Pedido guardado correctamente', 'success');
}

function contactClient() {
    // Abrir WhatsApp con el número del cliente
    const telefono = '<?php echo isset($processed_order['telefono']) ? $processed_order['telefono'] : ''; ?>';
    if (telefono) {
        window.open(`https://wa.me/${telefono}`, '_blank');
    }
}

function printOrder() {
    window.print();
}

function showNotification(message, type) {
    // Implementar notificación
    alert(message);
}
</script> 