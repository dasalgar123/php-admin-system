/**
 * Funcionalidades JavaScript para la vista de Inventario
 * Incluye exportación a Excel y PDF, filtros dinámicos y más
 */

// Variables globales
let inventarioData = [];
let filteredData = [];

// Inicialización cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    initializeInventario();
});

/**
 * Inicializa todas las funcionalidades del inventario
 */
function initializeInventario() {
    // Cargar datos de la tabla
    loadInventarioData();
    
    // Inicializar filtros
    initializeFilters();
    
    // Inicializar búsqueda en tiempo real
    initializeSearch();
    
    // Inicializar eventos de exportación
    initializeExportButtons();
}

/**
 * Carga los datos de la tabla de inventario
 */
function loadInventarioData() {
    const table = document.getElementById('inventarioTable');
    if (!table) return;
    
    const rows = table.querySelectorAll('tbody tr');
    inventarioData = [];
    
    rows.forEach(row => {
        if (row.classList.contains('no-data')) return;
        
        const rowData = {
            id: row.querySelector('.product-id')?.textContent?.trim() || '',
            producto: row.querySelector('.product-name strong')?.textContent?.trim() || '',
            descripcion: row.querySelector('.product-name small')?.textContent?.trim() || '',
            bodega: row.querySelector('.bodega-name')?.textContent?.trim() || '',
            precio: row.querySelector('.price')?.textContent?.trim() || '',
            entradas: row.querySelector('.entradas')?.textContent?.trim() || '0',
            salidas: row.querySelector('.salidas')?.textContent?.trim() || '0',
            devoluciones: row.querySelector('.devoluciones')?.textContent?.trim() || '0',
            garantias: row.querySelector('.garantias')?.textContent?.trim() || '0',
            traslados: row.querySelector('.traslados')?.textContent?.trim() || '0',
            stock: row.querySelector('.stock-actual .stock-number')?.textContent?.trim() || '0',
            estado: row.querySelector('.status-badge')?.textContent?.trim() || '',
            element: row
        };
        
        inventarioData.push(rowData);
    });
    
    filteredData = [...inventarioData];
}

/**
 * Inicializa los filtros dinámicos
 */
function initializeFilters() {
    const bodegaSelect = document.getElementById('bodega');
    const stockSelect = document.getElementById('stock');
    
    if (bodegaSelect) {
        bodegaSelect.addEventListener('change', applyFilters);
    }
    
    if (stockSelect) {
        stockSelect.addEventListener('change', applyFilters);
    }
}

/**
 * Inicializa la búsqueda en tiempo real
 */
function initializeSearch() {
    const searchInput = document.getElementById('producto');
    if (!searchInput) return;
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        filterBySearch(searchTerm);
    });
}

/**
 * Filtra por término de búsqueda
 */
function filterBySearch(searchTerm) {
    const rows = document.querySelectorAll('#inventarioTable tbody tr');
    
    rows.forEach(row => {
        if (row.classList.contains('no-data')) return;
        
        const productName = row.querySelector('.product-name strong')?.textContent?.toLowerCase() || '';
        const productDesc = row.querySelector('.product-name small')?.textContent?.toLowerCase() || '';
        
        if (productName.includes(searchTerm) || productDesc.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
    
    updateSummary();
}

/**
 * Aplica todos los filtros
 */
function applyFilters() {
    const bodegaFilter = document.getElementById('bodega')?.value || '';
    const stockFilter = document.getElementById('stock')?.value || '';
    
    const rows = document.querySelectorAll('#inventarioTable tbody tr');
    
    rows.forEach(row => {
        if (row.classList.contains('no-data')) return;
        
        let showRow = true;
        
        // Filtro por bodega
        if (bodegaFilter) {
            const bodega = row.querySelector('.bodega-name')?.textContent?.trim() || '';
            if (bodega !== bodegaFilter) {
                showRow = false;
            }
        }
        
        // Filtro por stock
        if (stockFilter && showRow) {
            const stock = parseInt(row.querySelector('.stock-actual .stock-number')?.textContent || '0');
            
            switch(stockFilter) {
                case 'disponible':
                    showRow = stock > 10;
                    break;
                case 'bajo':
                    showRow = stock <= 10 && stock > 0;
                    break;
                case 'agotado':
                    showRow = stock <= 0;
                    break;
            }
        }
        
        row.style.display = showRow ? '' : 'none';
    });
    
    updateSummary();
}

/**
 * Actualiza el resumen de estadísticas
 */
function updateSummary() {
    const visibleRows = document.querySelectorAll('#inventarioTable tbody tr:not([style*="display: none"]):not(.no-data)');
    
    let total = visibleRows.length;
    let disponible = 0;
    let bajo = 0;
    let agotado = 0;
    
    visibleRows.forEach(row => {
        const stock = parseInt(row.querySelector('.stock-actual .stock-number')?.textContent || '0');
        
        if (stock > 10) {
            disponible++;
        } else if (stock > 0) {
            bajo++;
        } else {
            agotado++;
        }
    });
    
    // Actualizar contadores en el DOM
    const summaryCards = document.querySelectorAll('.summary-card p');
    if (summaryCards.length >= 4) {
        summaryCards[0].textContent = total;
        summaryCards[1].textContent = disponible;
        summaryCards[2].textContent = bajo;
        summaryCards[3].textContent = agotado;
    }
}

/**
 * Inicializa los botones de exportación
 */
function initializeExportButtons() {
    // Los botones ya están configurados en el HTML con onclick
    // Aquí podríamos agregar funcionalidades adicionales
}

/**
 * Exporta la tabla a Excel con formato mejorado
 */
function exportToExcel() {
    try {
        // Verificar si XLSX está disponible
        if (typeof XLSX === 'undefined') {
            showNotification('Librería Excel no disponible. Cargando...', 'info');
            loadExcelLibrary();
            return;
        }
        
        // Crear un libro de trabajo
        const wb = XLSX.utils.book_new();
        
        // Obtener datos de la tabla visible
        const data = getVisibleTableData();
        
        // Crear hoja de trabajo
        const ws = XLSX.utils.json_to_sheet(data);
        
        // Configurar anchos de columna
        const colWidths = [
            { wch: 8 },   // ID
            { wch: 30 },  // Producto
            { wch: 20 },  // Bodega
            { wch: 12 },  // Precio
            { wch: 10 },  // Entradas
            { wch: 10 },  // Salidas
            { wch: 12 },  // Devoluciones
            { wch: 10 },  // Garantías
            { wch: 10 },  // Traslados
            { wch: 12 },  // Stock
            { wch: 15 }   // Estado
        ];
        ws['!cols'] = colWidths;
        
        // Agregar hoja al libro
        XLSX.utils.book_append_sheet(wb, ws, "Inventario");
        
        // Generar archivo y descargar
        const fileName = `inventario_${new Date().toISOString().split('T')[0]}.xlsx`;
        XLSX.writeFile(wb, fileName);
        
        showNotification('Excel exportado correctamente', 'success');
    } catch (error) {
        console.error('Error al exportar Excel:', error);
        showNotification('Error al exportar Excel', 'error');
        
        // Fallback: exportación básica
        exportToExcelBasic();
    }
}

/**
 * Exportación básica a Excel (fallback)
 */
function exportToExcelBasic() {
    const table = document.getElementById('inventarioTable');
    if (!table) return;
    
    const html = table.outerHTML;
    const url = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);
    const downloadLink = document.createElement("a");
    document.body.appendChild(downloadLink);
    downloadLink.href = url;
    downloadLink.download = `inventario_${new Date().toISOString().split('T')[0]}.xls`;
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

/**
 * Exporta la tabla a PDF con formato profesional
 */
function exportToPDF() {
    try {
        // Verificar si jsPDF está disponible
        if (typeof jsPDF === 'undefined') {
            showNotification('Librería PDF no disponible. Cargando...', 'info');
            loadPDFLibrary();
            return;
        }
        
        // Crear nuevo documento PDF
        const doc = new jsPDF();
        
        // Configuración del documento
        const pageWidth = doc.internal.pageSize.width;
        const margin = 20;
        
        // Título principal
        doc.setFontSize(20);
        doc.setFont('helvetica', 'bold');
        doc.text('Reporte de Inventario', pageWidth / 2, 30, { align: 'center' });
        
        // Fecha del reporte
        doc.setFontSize(12);
        doc.setFont('helvetica', 'normal');
        doc.text(`Fecha: ${new Date().toLocaleDateString('es-ES')}`, margin, 45);
        
        // Estadísticas del inventario
        const stats = getInventoryStats();
        doc.setFontSize(10);
        doc.text(`Total Productos: ${stats.total}`, margin, 55);
        doc.text(`Disponible: ${stats.disponible}`, margin + 50, 55);
        doc.text(`Stock Bajo: ${stats.bajo}`, margin + 100, 55);
        doc.text(`Agotado: ${stats.agotado}`, margin + 150, 55);
        
        // Obtener datos de la tabla visible
        const data = getVisibleTableData();
        
        if (data.length > 0) {
            // Preparar datos para la tabla
            const headers = Object.keys(data[0]);
            const tableData = data.map(row => Object.values(row));
            
            // Crear tabla en el PDF
            doc.autoTable({
                head: [headers],
                body: tableData,
                startY: 70,
                margin: { top: 70 },
                styles: {
                    fontSize: 8,
                    cellPadding: 3
                },
                headStyles: {
                    fillColor: [102, 126, 234],
                    textColor: 255,
                    fontStyle: 'bold'
                },
                alternateRowStyles: {
                    fillColor: [245, 245, 245]
                },
                columnStyles: {
                    0: { cellWidth: 15 }, // ID
                    1: { cellWidth: 40 }, // Producto
                    2: { cellWidth: 25 }, // Bodega
                    3: { cellWidth: 20 }, // Precio
                    4: { cellWidth: 15 }, // Entradas
                    5: { cellWidth: 15 }, // Salidas
                    6: { cellWidth: 20 }, // Devoluciones
                    7: { cellWidth: 15 }, // Garantías
                    8: { cellWidth: 15 }, // Traslados
                    9: { cellWidth: 15 }, // Stock
                    10: { cellWidth: 20 } // Estado
                }
            });
        } else {
            // Si no hay datos, mostrar mensaje
            doc.setFontSize(14);
            doc.text('No hay datos de inventario para mostrar', pageWidth / 2, 80, { align: 'center' });
        }
        
        // Agregar pie de página con numeración
        const pageCount = doc.internal.getNumberOfPages();
        for (let i = 1; i <= pageCount; i++) {
            doc.setPage(i);
            doc.setFontSize(10);
            doc.text(`Página ${i} de ${pageCount}`, pageWidth / 2, doc.internal.pageSize.height - 10, { align: 'center' });
        }
        
        // Generar nombre del archivo con fecha
        const fileName = `inventario_${new Date().toISOString().split('T')[0]}.pdf`;
        
        // Descargar el PDF
        doc.save(fileName);
        
        showNotification('PDF exportado correctamente', 'success');
        
    } catch (error) {
        console.error('Error al exportar PDF:', error);
        showNotification('Error al exportar PDF: ' + error.message, 'error');
        
        // Fallback: intentar exportación básica
        exportToPDFBasic();
    }
}

/**
 * Exportación básica a PDF (fallback)
 */
function exportToPDFBasic() {
    try {
        const table = document.getElementById('inventarioTable');
        if (!table) {
            showNotification('No se encontró la tabla de inventario', 'error');
            return;
        }
        
        // Crear ventana de impresión
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Inventario - PDF</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; font-weight: bold; }
                    .header { text-align: center; margin-bottom: 20px; }
                    @media print {
                        body { margin: 0; }
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>Reporte de Inventario</h1>
                    <p>Fecha: ${new Date().toLocaleDateString('es-ES')}</p>
                </div>
                ${table.outerHTML}
            </body>
            </html>
        `);
        
        printWindow.document.close();
        
        // Esperar a que se cargue y luego imprimir
        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 500);
        
                showNotification('PDF básico generado (impresión)', 'info');
        
    } catch (error) {
        console.error('Error en exportación básica PDF:', error);
        showNotification('No se pudo generar el PDF', 'error');
    }
}

/**
 * Carga la librería PDF si no está disponible
 */
function loadPDFLibrary() {
    // Cargar jsPDF
    const script = document.createElement('script');
    script.src = 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js';
    script.onload = function() {
        // Cargar autoTable plugin
        const autoTableScript = document.createElement('script');
        autoTableScript.src = 'https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/autotable.min.js';
        autoTableScript.onload = function() {
            showNotification('Librería PDF cargada. Intenta exportar nuevamente.', 'success');
        };
        document.head.appendChild(autoTableScript);
    };
    document.head.appendChild(script);
}

/**
 * Carga la librería Excel si no está disponible
 */
function loadExcelLibrary() {
    const script = document.createElement('script');
    script.src = 'https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js';
    script.onload = function() {
        showNotification('Librería Excel cargada. Intenta exportar nuevamente.', 'success');
    };
    document.head.appendChild(script);
}

/**
 * Obtiene los datos de la tabla visible
 */
function getVisibleTableData() {
    const visibleRows = document.querySelectorAll('#inventarioTable tbody tr:not([style*="display: none"]):not(.no-data)');
    const data = [];
    
    visibleRows.forEach(row => {
        const rowData = {
            'ID': row.querySelector('.product-id')?.textContent?.trim() || '',
            'Producto': row.querySelector('.product-name strong')?.textContent?.trim() || '',
            'Bodega': row.querySelector('.bodega-name')?.textContent?.trim() || '',
            'Precio': row.querySelector('.price')?.textContent?.trim() || '',
            'Entradas': row.querySelector('.entradas')?.textContent?.trim() || '0',
            'Salidas': row.querySelector('.salidas')?.textContent?.trim() || '0',
            'Devoluciones': row.querySelector('.devoluciones')?.textContent?.trim() || '0',
            'Garantías': row.querySelector('.garantias')?.textContent?.trim() || '0',
            'Traslados': row.querySelector('.traslados')?.textContent?.trim() || '0',
            'Stock': row.querySelector('.stock-actual .stock-number')?.textContent?.trim() || '0',
            'Estado': row.querySelector('.status-badge')?.textContent?.trim() || ''
        };
        data.push(rowData);
    });
    
    return data;
}

/**
 * Obtiene estadísticas del inventario
 */
function getInventoryStats() {
    const visibleRows = document.querySelectorAll('#inventarioTable tbody tr:not([style*="display: none"]):not(.no-data)');
    
    let total = visibleRows.length;
    let disponible = 0;
    let bajo = 0;
    let agotado = 0;
    
    visibleRows.forEach(row => {
        const stock = parseInt(row.querySelector('.stock-actual .stock-number')?.textContent || '0');
        
        if (stock > 10) {
            disponible++;
        } else if (stock > 0) {
            bajo++;
        } else {
            agotado++;
        }
    });
    
    return { total, disponible, bajo, agotado };
}

/**
 * Muestra notificaciones al usuario
 */
function showNotification(message, type = 'info') {
    // Crear elemento de notificación
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
        <span>${message}</span>
    `;
    
    // Agregar estilos
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        z-index: 10000;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transform: translateX(100%);
        transition: transform 0.3s ease;
        max-width: 400px;
        ${type === 'success' ? 'background: #28a745;' : 
          type === 'error' ? 'background: #dc3545;' : 
          'background: #17a2b8;'}
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
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

/**
 * Función para imprimir el inventario
 */
function printInventario() {
    const printWindow = window.open('', '_blank');
    const table = document.getElementById('inventarioTable');
    
    if (!table) return;
    
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Inventario - Impresión</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; font-weight: bold; }
                .header { text-align: center; margin-bottom: 20px; }
                .stats { margin-bottom: 20px; }
                @media print {
                    .no-print { display: none; }
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>Reporte de Inventario</h1>
                <p>Fecha: ${new Date().toLocaleDateString('es-ES')}</p>
            </div>
            <div class="stats">
                <strong>Estadísticas:</strong> ${getInventoryStats().total} productos total
            </div>
            ${table.outerHTML}
        </body>
        </html>
    `);
    
    printWindow.document.close();
    printWindow.print();
}

/**
 * Función para exportar a CSV
 */
function exportToCSV() {
    const data = getVisibleTableData();
    if (data.length === 0) return;
    
    const headers = Object.keys(data[0]);
    const csvContent = [
        headers.join(','),
        ...data.map(row => headers.map(header => `"${row[header]}"`).join(','))
    ].join('\n');
    
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    
    link.setAttribute('href', url);
    link.setAttribute('download', `inventario_${new Date().toISOString().split('T')[0]}.csv`);
    link.style.visibility = 'hidden';
    
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    showNotification('CSV exportado correctamente', 'success');
}

// Cargar librerías necesarias
function loadLibraries() {
    // Cargar XLSX si no está disponible
    if (typeof XLSX === 'undefined') {
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js';
        document.head.appendChild(script);
    }
}

// Cargar librerías al inicializar
loadLibraries(); 