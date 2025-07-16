// Funcionalidad para la página de usuarios

function mostrarFormularioUsuario() {
    const formulario = document.getElementById('formularioUsuario');
    formulario.style.display = 'block';
    
    // Cambiar el título del formulario
    const titulo = formulario.querySelector('h2');
    titulo.textContent = 'Agregar Usuario';
    
    // Limpiar el formulario
    const form = formulario.querySelector('form');
    form.reset();
    
    // Remover el campo hidden de ID si existe
    const idInput = form.querySelector('input[name="id"]');
    if (idInput) {
        idInput.remove();
    }
    
    // Hacer scroll al formulario
    formulario.scrollIntoView({ behavior: 'smooth' });
} 