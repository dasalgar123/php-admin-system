// JavaScript para la página de usuarios

// Función para alternar dropdown de usuario
function toggleDropdownUsuario(userId) {
    // Cerrar todos los otros dropdowns
    const allDropdowns = document.querySelectorAll('.dropdown-menu');
    allDropdowns.forEach(dropdown => {
        if (dropdown.id !== `dropdown-usuario-${userId}`) {
            dropdown.classList.remove('show');
        }
    });
    // Toggle del dropdown actual
    const dropdown = document.getElementById(`dropdown-usuario-${userId}`);
    dropdown.classList.toggle('show');
}

// Función para suspender usuario
function suspendUsuario(userId, userName) {
    if (confirm(`¿Estás seguro de que quieres suspender al usuario "${userName}"?`)) {
        // Aquí puedes implementar la lógica de suspensión
        alert('Función de suspensión en desarrollo');
    }
}

// Función para confirmar eliminación de usuario
function confirmDeleteUsuario(userId, userName) {
    if (confirm(`¿Estás seguro de que quieres eliminar al usuario "${userName}"?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="${userId}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

// Función para suspender usuario (versión simplificada)
function suspendUser(userId) {
    if (confirm('¿Estás seguro de que quieres suspender este usuario?')) {
        // Aquí puedes implementar la lógica de suspensión
        alert('Función de suspensión en desarrollo');
    }
}

// Cerrar dropdowns cuando se hace clic fuera de ellos
document.addEventListener('click', function(event) {
    if (!event.target.closest('.dropdown')) {
        const dropdowns = document.querySelectorAll('.dropdown-menu');
        dropdowns.forEach(dropdown => {
            dropdown.classList.remove('show');
        });
    }
});

// Soporte para reconocimiento de voz
document.addEventListener('DOMContentLoaded', function() {
    const micBtn = document.getElementById('micBtnUsuarios');
    const micIcon = document.getElementById('micIconUsuarios');
    const searchInput = document.getElementById('searchInputUsuarios');
    let recognition;

    if (micBtn && micIcon && searchInput) {
        if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            recognition = new SpeechRecognition();
            recognition.lang = 'es-ES';
            recognition.continuous = false;
            recognition.interimResults = false;

            micBtn.addEventListener('click', function(e) {
                e.preventDefault();
                recognition.start();
                micIcon.classList.add('fa-microphone-slash');
                micIcon.classList.remove('fa-microphone');
            });

            recognition.onresult = function(event) {
                const transcript = event.results[0][0].transcript;
                searchInput.value = transcript;
                // Ejecutar búsqueda automáticamente
                const currentUrl = new URL(window.location);
                currentUrl.searchParams.set('search', transcript);
                window.location.href = currentUrl.toString();
            };

            recognition.onend = function() {
                micIcon.classList.remove('fa-microphone-slash');
                micIcon.classList.add('fa-microphone');
            };
        } else {
            micBtn.style.display = 'none'; // Ocultar si no hay soporte
        }
    }
}); 