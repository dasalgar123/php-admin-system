<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/barra-lateral.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="admin-container">
        <!-- Barra Lateral -->
        <aside class="barra-lateral">
            <div class="barra-lateral-header">
                <i class="fas fa-cogs"></i>
                <h2>Admin Panel</h2>
            </div>
            
            <nav class="barra-lateral-nav">
                <ul>
                    <li class="<?php echo $data['page'] === 'inicio' ? 'active' : ''; ?>">
                        <a href="?page=inicio">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Menú Principal</span>
                        </a>
                    </li>
                    <li class="<?php echo $data['page'] === 'users' ? 'active' : ''; ?>">
                        <a href="?page=users">
                            <i class="fas fa-users"></i>
                            <span>Usuarios</span>
                        </a>
                    </li>
                    <li class="<?php echo $data['page'] === 'clientes' ? 'active' : ''; ?>">
                        <a href="?page=clientes">
                            <i class="fas fa-user-friends"></i>
                            <span>Clientes</span>
                        </a>
                    </li>
                    <li class="<?php echo $data['page'] === 'proveedores' ? 'active' : ''; ?>">
                        <a href="?page=proveedores">
                            <i class="fas fa-truck"></i>
                            <span>Proveedores</span>
                        </a>
                    </li>
                    <li class="<?php echo $data['page'] === 'products' ? 'active' : ''; ?>">
                        <a href="?page=products">
                            <i class="fas fa-box"></i>
                            <span>Productos</span>
                        </a>
                    </li>
                    <li class="<?php echo $data['page'] === 'orders' ? 'active' : ''; ?>">
                        <a href="?page=orders">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Pedidos</span>
                        </a>
                    </li>
                    <li class="<?php echo $data['page'] === 'inventario' ? 'active' : ''; ?>">
                        <a href="?page=inventario">
                            <i class="fas fa-warehouse"></i>
                            <span>Inventario</span>
                        </a>
                    </li>
                    <li class="<?php echo $data['page'] === 'entradas' ? 'active' : ''; ?>">
                        <a href="?page=entradas">
                            <i class="fas fa-arrow-down"></i>
                            <span>Entradas</span>
                        </a>
                    </li>
                    <li class="<?php echo $data['page'] === 'salidas' ? 'active' : ''; ?>">
                        <a href="?page=salidas">
                            <i class="fas fa-arrow-up"></i>
                            <span>Salidas</span>
                        </a>
                    </li>
                    <li class="<?php echo $data['page'] === 'ventas' ? 'active' : ''; ?>">
                        <a href="?page=ventas">
                            <i class="fas fa-cash-register"></i>
                            <span>Ventas</span>
                        </a>
                    </li>
                    <li class="<?php echo $data['page'] === 'devoluciones' ? 'active' : ''; ?>">
                        <a href="?page=devoluciones">
                            <i class="fas fa-undo"></i>
                            <span>Devoluciones</span>
                        </a>
                    </li>
                    <li class="<?php echo $data['page'] === 'garantias' ? 'active' : ''; ?>">
                        <a href="?page=garantias">
                            <i class="fas fa-shield-alt"></i>
                            <span>Garantías</span>
                        </a>
                    </li>
                    <li class="<?php echo $data['page'] === 'traslados' ? 'active' : ''; ?>">
                        <a href="?page=traslados">
                            <i class="fas fa-exchange-alt"></i>
                            <span>Traslados</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <hr style="margin: 8px 0; border: none; border-top: 1px solid #ddd;">
            <div class="barra-lateral-footer">
                <div class="user-info">
                    <i class="fas fa-user-circle"></i>
                    <span><?php echo $data['currentUser'] ? htmlspecialchars($data['currentUser']['nombre']) : ''; ?></span>
                </div>
                <a href="../controlador/LogoutController.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    Cerrar Sesión
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="content-header">
                <h1>
                    <?php
                    switch($data['page']) {
                        case 'inicio': echo 'Menú Principal'; break;
                        case 'users': echo 'Usuarios'; break;
                        case 'clientes': echo 'Gestión de Clientes'; break;
                        case 'proveedores': echo 'Gestión de Proveedores'; break;
                        case 'products': echo 'Gestión de Productos'; break;
                        case 'orders': echo 'Gestión de Pedidos'; break;
                

                        case 'settings': echo 'Configuración'; break;
                        case 'inventario': echo 'Inventario'; break;
                        case 'ventas': echo 'Gestión de Ventas'; break;
                        case 'devoluciones': echo 'Gestión de Devoluciones'; break;
                        case 'garantias': echo 'Gestión de Garantías'; break;
                        case 'traslados': echo 'Gestión de Traslados'; break;
                        default: echo 'Menú Principal';
                    }
                    ?>
                </h1>

            </header>

            <!-- Page Content -->
            <div class="content">
                <?php
                switch($data['page']) {
                    case 'inicio':
                        include '../vista/vista_menu_principal_contenido.php';
                        break;
                    case 'users':
                        include '../vista/vista_usuarios.php';
                        break;
                    case 'clientes':
                        include '../vista/vista_clientes.php';
                        break;
                    case 'proveedores':
                        include '../vista/vista_proveedores.php';
                        break;
                    case 'products':
                        include '../vista/vista_products.php';
                        break;
                    case 'orders':
                        include '../vista/vista_orders.php';
                        break;
                    
                    
                    
                    case 'entradas':
                        include '../vista/vista_entradas.php';
                        break;
                    case 'salidas':
                        include '../vista/vista_salidas.php';
                        break;
                    case 'inventario':
                        include '../vista/vista_inventario.php';
                        break;
                    case 'ventas':
                        include '../vista/vista_ventas.php';
                        break;
                    case 'devoluciones':
                        include '../vista/vista_devoluciones.php';
                        break;
                    case 'garantias':
                        include '../vista/vista_garantias.php';
                        break;
                    case 'traslados':
                        include '../vista/vista_traslados.php';
                        break;

                    case 'settings':
                        include '../vista/vista_settings.php';
                        break;
                    default:
                        include '../menu/menu_menu_principal.php';
                }
                ?>
            </div>
        </main>
    </div>

    <script src="../js/script.js"></script>

</body>
</html> 