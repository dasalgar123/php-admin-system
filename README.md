# PHP Admin Interface

Una interfaz de administración moderna y funcional construida con PHP puro siguiendo el patrón MVC para la gestión de sistemas empresariales.

## Características

- 🔐 **Sistema de Autenticación**: Login seguro con sesiones PHP
- 🎨 **Diseño Moderno**: Interfaz limpia y profesional con CSS3
- 📱 **Responsive**: Optimizado para dispositivos móviles y tablets
- 🧭 **Navegación Intuitiva**: Sidebar con navegación por páginas
- 📊 **Dashboard Interactivo**: Estadísticas y métricas en tiempo real
- 👥 **Gestión de Usuarios**: CRUD completo con búsqueda y filtros
- 📦 **Gestión de Productos**: Catálogo con control de stock
- 🛒 **Gestión de Pedidos**: Seguimiento de pedidos y estados
- 📈 **Analíticas**: Métricas de rendimiento y estadísticas
- ⚙️ **Configuración**: Panel de configuración personalizable
- 🔍 **Búsqueda Avanzada**: Filtros en todas las secciones
- 🏗️ **Arquitectura MVC**: Separación clara de responsabilidades

## Tecnologías Utilizadas

- **PHP 7.4+**: Backend y lógica de negocio
- **HTML5**: Estructura semántica
- **CSS3**: Estilos modernos con variables CSS
- **JavaScript**: Interactividad y validaciones
- **Font Awesome**: Iconografía moderna
- **Sesiones PHP**: Autenticación y seguridad
- **Patrón MVC**: Arquitectura de software

## Instalación

1. **Requisitos del Servidor:**
   - PHP 7.4 o superior
   - Servidor web (Apache, Nginx, o servidor integrado de PHP)
   - Soporte para sesiones PHP

2. **Configuración:**
   ```bash
   # Copiar archivos al directorio web
   cp -r php-admin/ /var/www/html/
   
   # O usar servidor integrado de PHP
   cd php-admin
   php -S localhost:8000
   ```

3. **Acceso:**
   - Abrir navegador en: `http://localhost:8000`
   - Credenciales: `admin` / `admin123`

> **Nota importante:**
> Para evitar errores de rutas en los `require_once` y `include` de PHP, este proyecto utiliza rutas absolutas con `__DIR__`. Ejemplo:
> ```php
> require_once __DIR__ . '/../config/database.php';
> ```
> Así te aseguras de que los archivos siempre se incluyan correctamente, sin importar desde dónde se ejecute el script.

## Estructura del Proyecto (Arquitectura MVC)

```
php-admin/
├── index.php                          # Punto de entrada principal
├── config/
│   └── database.php                   # Configuración de base de datos
├── controlador/                       # 🎯 Controladores (Lógica de negocio)
│   ├── LoginController.php            # Controlador de autenticación
│   ├── DashboardController.php        # Controlador del dashboard
│   ├── LogoutController.php           # Controlador de cierre de sesión
│   ├── UsersController.php            # Controlador de usuarios
│   ├── ProductsController.php         # Controlador de productos
│   ├── OrdersController.php           # Controlador de pedidos
│   ├── AnalyticsController.php        # Controlador de analíticas
│   ├── SettingsController.php         # Controlador de configuración
│   ├── ClienteController.php          # Controlador de clientes
│   └── WhatsappOrdersController.php   # Controlador de pedidos WhatsApp
├── modelo/                           # 🗃️ Modelos (Acceso a datos)
│   ├── modelo_Autenticacion.php      # Modelo de autenticación
│   ├── modelo_User.php               # Modelo de usuarios
│   ├── modelo_Product.php            # Modelo de productos
│   ├── modelo_Order.php              # Modelo de pedidos
│   ├── modelo_Analytics.php          # Modelo de analíticas
│   ├── modelo_Settings.php           # Modelo de configuración
│   ├── modelo_Cliente.php            # Modelo de clientes
│   └── modelo_WhatsappOrder.php      # Modelo de pedidos WhatsApp
├── vista/                            # 📱 Vistas (Presentación)
│   ├── vista_login.php               # Vista de login
│   ├── vista_dashboard.php           # Vista del dashboard
│   ├── vista_usuarios.php            # Vista de usuarios
│   ├── vista_products.php            # Vista de productos
│   ├── vista_orders.php              # Vista de pedidos
│   ├── vista_analytics.php           # Vista de analíticas
│   ├── vista_settings.php            # Vista de configuración
│   ├── vista_whatsapp-orders.php     # Vista de pedidos WhatsApp
│   └── vista_main-dashboard.php      # Vista del dashboard principal
├── menu/                             # 🧭 Componentes de menú
│   ├── menu_dashboard-content.php    # Contenido del dashboard
│   ├── menu_usuarios.php             # Menú de usuarios
│   ├── menu_productos.php            # Menú de productos
│   ├── menu_ordenes.php              # Menú de pedidos
│   ├── menu_analytics.php            # Menú de analíticas
│   ├── menu_settings.php             # Menú de configuración
│   ├── menu_clientes.php             # Menú de clientes
│   ├── menu_entradas.php             # Menú de entradas
│   ├── menu_salidas.php              # Menú de salidas
│   └── menu_inventario.php           # Menú de inventario
├── css/                              # 🎨 Estilos CSS
│   ├── style.css                     # Estilos principales
│   ├── dark-mode.css                 # Variables CSS para modo oscuro
│   ├── dashboard.css                 # Estilos del dashboard
│   ├── users.css                     # Estilos de usuarios
│   ├── products.css                  # Estilos de productos
│   ├── analytics.css                 # Estilos de analíticas
│   ├── clientes.css                  # Estilos de clientes
│   └── main-dashboard.css            # Estilos del dashboard principal
├── js/                               # ⚡ JavaScript
│   ├── script.js                     # Script principal
│   ├── dark-mode.js                  # Sistema de modo oscuro
│   ├── clientes.js                   # Script de clientes
│   └── usuarios.js                   # Script de usuarios
├── diagrama_actividad_login_dashboard.md      # Diagrama de actividades (Markdown)
├── diagrama_actividad_login_dashboard.html    # Diagrama de actividades (HTML)
├── diagrama_actividades_uml.html              # Diagrama UML profesional
├── diagrama_actividad_login_dashboard_bmp.html # Generador de diagrama BMP
├── demo-dark-mode.html               # Demostración del modo oscuro
└── README.md                         # Documentación
```

## Arquitectura MVC

### 🎯 **Controladores** (`controlador/`)
- Manejan la lógica de negocio
- Procesan las solicitudes HTTP
- Coordinan entre modelos y vistas
- Gestionan la autenticación y autorización

### 🗃️ **Modelos** (`modelo/`)
- Acceden y manipulan los datos
- Contienen la lógica de negocio específica
- Manejan las consultas a la base de datos
- Validan los datos

### 📱 **Vistas** (`vista/`)
- Presentan la información al usuario
- Contienen solo HTML y lógica de presentación
- Reciben datos de los controladores
- No contienen lógica de negocio

## Páginas Disponibles

### 🔐 Login (`controlador/LoginController.php`)
- Formulario de autenticación seguro
- Validación de credenciales
- Manejo de errores
- Recordar sesión

### 🏠 Dashboard (`controlador/DashboardController.php`)
- Estadísticas generales del sistema
- Métricas de usuarios, productos y ventas
- Pedidos recientes
- Acciones rápidas

### 👥 Usuarios (`controlador/UsersController.php`)
- Lista de usuarios con búsqueda
- Filtros por rol (Admin, Editor, Usuario)
- Gestión de estados (Activo/Inactivo)
- Acciones: Ver, Editar, Eliminar

### 📦 Productos (`controlador/ProductsController.php`)
- Catálogo de productos
- Control de stock con alertas
- Filtros por categoría
- Gestión de precios y disponibilidad

### 🛒 Pedidos (`controlador/OrdersController.php`)
- Seguimiento de pedidos
- Estados: Completado, Pendiente, En proceso
- Información de clientes y pagos
- Historial de transacciones

### 📈 Analíticas (`controlador/AnalyticsController.php`)
- Métricas de rendimiento
- Gráficos de ventas (preparado para Chart.js)
- Estadísticas de usuarios
- Tendencias del negocio

### ⚙️ Configuración (`controlador/SettingsController.php`)
- Notificaciones del sistema
- Configuración regional (idioma, zona horaria)
- Apariencia (modo oscuro)
- Configuración de seguridad

### 👥 Clientes (`controlador/ClienteController.php`)
- Gestión de clientes
- Información de contacto
- Historial de compras
- Segmentación de clientes

## Flujo de Navegación

```
1. Usuario accede a index.php
2. index.php verifica autenticación
3. Si no autenticado → LoginController.php
4. Si autenticado → DashboardController.php
5. DashboardController.php incluye vista_dashboard.php
6. Usuario navega entre secciones
7. Logout → LogoutController.php
```

## Personalización

### Colores
Los colores se pueden personalizar editando las variables CSS en `assets/css/style.css`:

```css
:root {
  --primary-color: #2563eb;
  --secondary-color: #64748b;
  --success-color: #10b981;
  --warning-color: #f59e0b;
  --danger-color: #ef4444;
  --dark-color: #1e293b;
  --light-color: #f8fafc;
  --border-color: #e2e8f0;
  --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}
```

### Agregar Nuevas Páginas
1. Crear controlador en `controlador/`
2. Crear modelo en `modelo/`
3. Crear vista en `vista/`
4. Agregar enlace en `vista_dashboard.php` (sidebar)
5. Agregar case en el switch de páginas del controlador

### Integración con Base de Datos
El proyecto está preparado para integrarse con MySQL/PostgreSQL:

```php
// Ejemplo de conexión a base de datos
$pdo = new PDO("mysql:host=localhost;dbname=admin_db", "user", "password");

// Obtener usuarios
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
```

## Seguridad

### Implementado
- ✅ Autenticación con sesiones PHP
- ✅ Validación de formularios
- ✅ Escape de datos HTML
- ✅ Protección contra XSS básica

### Recomendaciones para Producción
- 🔒 Usar HTTPS
- 🔒 Implementar hash de contraseñas (password_hash)
- 🔒 Configurar base de datos segura
- 🔒 Implementar CSRF tokens
- 🔒 Validación del lado servidor
- 🔒 Logs de auditoría

## Características Responsive

- **Desktop**: Sidebar fijo con navegación completa
- **Tablet**: Sidebar colapsible
- **Mobile**: Sidebar oculto, navegación optimizada

## 🌙 Modo Oscuro

El sistema incluye un modo oscuro completamente funcional con las siguientes características:

### ✨ Características
- **Cambio instantáneo** entre tema claro y oscuro
- **Persistencia automática** del tema elegido
- **Detección de preferencia** del sistema operativo
- **Atajos de teclado** (Ctrl/Cmd + Shift + T)
- **Transiciones suaves** entre temas
- **Notificaciones visuales** al cambiar tema

### 🎛️ Cómo usar
1. **Botón de tema**: Haz clic en el botón de luna/sol en el header
2. **Atajo de teclado**: Usa `Ctrl + Shift + T` (Windows/Linux) o `Cmd + Shift + T` (Mac)
3. **Detección automática**: El tema se ajusta según tu configuración del sistema

### 🎨 Personalización
El modo oscuro usa variables CSS que se pueden personalizar en `css/dark-mode.css`:

```css
[data-theme="dark"] {
  --bg-primary: #0f172a;      /* Fondo principal */
  --text-primary: #f1f5f9;    /* Texto principal */
  --border-color: #334155;    /* Bordes */
  /* ... más variables */
}
```

### 📱 Demo
Abre `demo-dark-mode.html` para ver una demostración completa del modo oscuro.

## Scripts Disponibles

```bash
# Servidor de desarrollo
php -S localhost:8000

# Verificar sintaxis PHP
php -l index.php

# Verificar errores
php -d display_errors=1 index.php
```

## Diagramas del Sistema

El proyecto incluye varios diagramas para documentar el flujo:

- **`diagrama_actividad_login_dashboard.md`**: Diagrama básico en Markdown
- **`diagrama_actividad_login_dashboard.html`**: Diagrama interactivo con Mermaid
- **`diagrama_actividades_uml.html`**: Diagrama UML profesional
- **`diagrama_actividad_login_dashboard_bmp.html`**: Generador de diagrama en formato BMP

## Próximas Mejoras

- [ ] Integración con base de datos MySQL
- [ ] Sistema de roles y permisos avanzado
- [ ] API REST para integración
- [ ] Gráficos con Chart.js
- [ ] Exportación de datos (PDF, Excel)
- [ ] Notificaciones en tiempo real
- [x] Modo oscuro funcional
- [ ] Internacionalización (i18n)
- [ ] Tests unitarios
- [ ] Optimización de rendimiento

## Contribución

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## Soporte

Para soporte técnico o preguntas:
- Revisar la documentación
- Verificar logs de errores
- Contactar al equipo de desarrollo

---

**Nota**: Esta es una versión de demostración con arquitectura MVC implementada. Para uso en producción, implementar todas las medidas de seguridad recomendadas. 