// Funcionalidad básica del panel de administración
function mostrarSeccion(seccion) {
    // Ocultar todas las secciones
    document.querySelectorAll('.seccion').forEach(sec => {
        sec.classList.remove('activa');
    });
    
    // Quitar activo de todos los botones
    document.querySelectorAll('.nav-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Mostrar sección seleccionada
    document.getElementById(`seccion-${seccion}`).classList.add('activa');
    
    // Activar botón correspondiente
    document.querySelector(`.nav-btn[onclick="mostrarSeccion('${seccion}')"]`).classList.add('active');
    
    // Cargar datos de la sección
    if (seccion === 'visitantes') {
        cargarVisitantes();
    } else if (seccion === 'usuarios-rfid') {
        cargarUsuariosRFID();
    }
}

// Modal functions
function abrirModalUsuario() {
    document.getElementById('modal-usuario').style.display = 'block';
    document.getElementById('modal-titulo').textContent = 'Agregar Usuario RFID';
    document.getElementById('form-usuario').reset();
}

function cerrarModal() {
    document.getElementById('modal-usuario').style.display = 'none';
}

// Cerrar modal al hacer clic fuera
window.onclick = function(event) {
    const modal = document.getElementById('modal-usuario');
    if (event.target === modal) {
        cerrarModal();
    }
}

// Funciones de carga (placeholder)
function cargarVisitantes() {
    console.log('Cargando visitantes...');
    // Aquí irá la carga real de datos
}

function cargarUsuariosRFID() {
    console.log('Cargando usuarios RFID...');
    // Aquí irá la carga real de datos
}

function exportarVisitantesExcel() {
    console.log('Exportando visitantes a Excel...');
}

function generarReporteVisitantes() {
    console.log('Generando reporte de visitantes...');
}

function exportarUsuariosExcel() {
    console.log('Exportando usuarios a Excel...');
}

// Modificar la función para manejar el scroll
function cambiarCamposUsuario() {
    const tipoUsuario = document.getElementById('tipo-usuario').value;
    
    // Ocultar todos los campos específicos
    document.querySelectorAll('.campos-especificos').forEach(campo => {
        campo.style.display = 'none';
    });
    
    // Mostrar los campos correspondientes al tipo de usuario seleccionado
    if (tipoUsuario) {
        const camposEspecificos = document.getElementById(`campos-${tipoUsuario}`);
        if (camposEspecificos) {
            camposEspecificos.style.display = 'block';
        }
    }
    
    // Siempre mostrar datos del vehículo
    document.getElementById('datos-vehiculo').style.display = 'block';
    
    // Hacer scroll al inicio del modal body cuando cambia el tipo
    document.querySelector('.modal-body').scrollTop = 0;
}

// Función para limpiar el formulario cuando se cierra el modal
function limpiarFormularioUsuario() {
    document.getElementById('form-usuario').reset();
    document.querySelectorAll('.campos-especificos').forEach(campo => {
        campo.style.display = 'none';
    });
    document.getElementById('datos-vehiculo').style.display = 'block';
}

// Modificar la función abrirModalUsuario
function abrirModalUsuario() {
    document.getElementById('modal-usuario').style.display = 'block';
    document.getElementById('modal-titulo').textContent = 'Agregar Usuario RFID';
    limpiarFormularioUsuario();
}

// Modificar la función cerrarModal
function cerrarModal() {
    document.getElementById('modal-usuario').style.display = 'none';
    limpiarFormularioUsuario();
}

// Inicialización - agregar esto al DOMContentLoaded existente
document.addEventListener('DOMContentLoaded', function() {
    // Configurar el evento change para el select
    document.getElementById('tipo-usuario').addEventListener('change', cambiarCamposUsuario);
});




// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    // Mostrar sección de visitantes por defecto
    mostrarSeccion('visitantes');
    
    // Configurar cierre del modal
    document.querySelector('.close').addEventListener('click', cerrarModal);
    
    // Prevenir envío del formulario por ahora
    document.getElementById('form-usuario').addEventListener('submit', function(e) {

    });
});