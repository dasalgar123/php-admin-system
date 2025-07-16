// Funciones para la gestión de proveedores
function mostrarFormularioUsuario() {
    document.getElementById('modalUsuario').style.display = 'block';
}

function cerrarModalUsuario() {
    document.getElementById('modalUsuario').style.display = 'none';
}

// Cerrar modal al hacer clic fuera de él
window.onclick = function(event) {
    var modal = document.getElementById('modalUsuario');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
} 