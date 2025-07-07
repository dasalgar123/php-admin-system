/**
 * Sistema de Modo Oscuro para PHP Admin Interface
 * Maneja el cambio entre tema claro y oscuro con persistencia
 */

class DarkModeManager {
    constructor() {
        this.theme = localStorage.getItem('theme') || 'light';
        this.init();
    }

    /**
     * Inicializa el sistema de modo oscuro
     */
    init() {
        // Aplicar tema guardado
        this.applyTheme(this.theme);
        
        // Crear botón de cambio de tema si no existe
        this.createThemeToggle();
        
        // Escuchar cambios en el botón
        this.addEventListeners();
        
        // Detectar preferencia del sistema
        this.detectSystemPreference();
    }

    /**
     * Aplica el tema especificado
     */
    applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        this.theme = theme;
        localStorage.setItem('theme', theme);
        
        // Actualizar icono del botón
        this.updateToggleIcon();
        
        // Disparar evento personalizado
        document.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme } }));
    }

    /**
     * Cambia entre tema claro y oscuro
     */
    toggleTheme() {
        const newTheme = this.theme === 'light' ? 'dark' : 'light';
        this.applyTheme(newTheme);
        
        // Mostrar notificación
        this.showNotification(`Modo ${newTheme === 'dark' ? 'oscuro' : 'claro'} activado`);
    }

    /**
     * Crea el botón de cambio de tema
     */
    createThemeToggle() {
        // Buscar si ya existe un botón de tema
        let themeToggle = document.querySelector('.theme-toggle');
        
        if (!themeToggle) {
            // Crear botón en el header si existe
            const headerActions = document.querySelector('.header-actions');
            if (headerActions) {
                themeToggle = document.createElement('button');
                themeToggle.className = 'theme-toggle';
                themeToggle.title = 'Cambiar tema';
                themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
                
                // Insertar antes del botón de notificaciones
                const notificationBtn = headerActions.querySelector('.btn-secondary');
                if (notificationBtn) {
                    headerActions.insertBefore(themeToggle, notificationBtn);
                } else {
                    headerActions.appendChild(themeToggle);
                }
            }
        }
    }

    /**
     * Actualiza el icono del botón según el tema actual
     */
    updateToggleIcon() {
        const themeToggle = document.querySelector('.theme-toggle');
        if (themeToggle) {
            const icon = themeToggle.querySelector('i');
            if (icon) {
                if (this.theme === 'dark') {
                    icon.className = 'fas fa-sun';
                    themeToggle.title = 'Cambiar a modo claro';
                } else {
                    icon.className = 'fas fa-moon';
                    themeToggle.title = 'Cambiar a modo oscuro';
                }
            }
        }
    }

    /**
     * Agrega event listeners
     */
    addEventListeners() {
        // Event listener para el botón de tema
        document.addEventListener('click', (e) => {
            if (e.target.closest('.theme-toggle')) {
                e.preventDefault();
                this.toggleTheme();
            }
        });

        // Event listener para atajos de teclado
        document.addEventListener('keydown', (e) => {
            // Ctrl/Cmd + Shift + T para cambiar tema
            if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'T') {
                e.preventDefault();
                this.toggleTheme();
            }
        });
    }

    /**
     * Detecta la preferencia del sistema operativo
     */
    detectSystemPreference() {
        if (localStorage.getItem('theme') === null) {
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            this.applyTheme(prefersDark ? 'dark' : 'light');
        }
    }

    /**
     * Muestra una notificación temporal
     */
    showNotification(message) {
        // Crear elemento de notificación
        const notification = document.createElement('div');
        notification.className = 'theme-notification';
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: var(--bg-card);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 12px 16px;
            box-shadow: var(--shadow-lg);
            z-index: 9999;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        `;

        document.body.appendChild(notification);

        // Animar entrada
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);

        // Remover después de 3 segundos
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }

    /**
     * Obtiene el tema actual
     */
    getCurrentTheme() {
        return this.theme;
    }

    /**
     * Establece un tema específico
     */
    setTheme(theme) {
        if (theme === 'light' || theme === 'dark') {
            this.applyTheme(theme);
        }
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    window.darkModeManager = new DarkModeManager();
});

// También inicializar si el script se carga después del DOM
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.darkModeManager = new DarkModeManager();
    });
} else {
    window.darkModeManager = new DarkModeManager();
}

// Exportar para uso global
window.DarkModeManager = DarkModeManager; 