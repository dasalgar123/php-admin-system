// JavaScript para la página de clientes

// Función para confirmar eliminación de cliente
function confirmDelete(clientId, clientName) {
    if (confirm(`¿Estás seguro de que quieres eliminar al cliente "${clientName}"?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="${clientId}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

// Función para suspender cliente
function suspendClient(clientId, clientName) {
    if (confirm(`¿Estás seguro de que quieres suspender al cliente "${clientName}"?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="action" value="change_status">
            <input type="hidden" name="id" value="${clientId}">
            <input type="hidden" name="estado" value="suspendido">
        `;
        document.body.appendChild(form);
        form.submit();
    }
} 