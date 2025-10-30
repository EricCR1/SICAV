

// --- Cambiar Sección ---
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

    // Cargar datos según sección
    if (seccion === 'visitantes') {
        cargarVisitantes();
    } else if (seccion === 'usuarios-rfid') {
        cargarUsuariosRFID();
    }
}

// --- Funciones del Modal ---
function abrirModalUsuario() {
    document.getElementById('modal-usuario').style.display = 'block';
    document.getElementById('modal-titulo').textContent = 'Agregar Usuario RFID';
    limpiarFormularioUsuario();
}

function cerrarModal() {
    document.getElementById('modal-usuario').style.display = 'none';
    limpiarFormularioUsuario();
}

// Cerrar modal al hacer clic fuera
window.onclick = function (event) {
    const modal = document.getElementById('modal-usuario');
    if (event.target === modal) {
        cerrarModal();
    }
};

// --- Funciones de carga (placeholder) ---
function cargarVisitantes() {
    console.log('Cargando visitantes...');
}

function cargarUsuariosRFID() {
    console.log('Cargando usuarios RFID...');
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

// ================================
// Control dinámico de formularios
// ================================
function cambiarCamposUsuario() {
    const tipoUsuario = document.getElementById('tipo-usuario').value;

    // 🔹 Ocultar y desactivar todos los bloques
    document.querySelectorAll('.campos-especificos').forEach(campo => {
        campo.style.display = 'none';
        campo.querySelectorAll('input, select').forEach(input => {
            input.disabled = true;
            input.removeAttribute('required');
            input.value = ''; // limpia los datos si se cambió de tipo
        });
    });

    // 🔹 Mostrar solo el bloque del tipo seleccionado
    if (tipoUsuario) {
        const camposEspecificos = document.getElementById(`campos-${tipoUsuario}`);
        if (camposEspecificos) {
            camposEspecificos.style.display = 'block';
            camposEspecificos.querySelectorAll('input, select').forEach(input => {
                input.disabled = false;
                input.setAttribute('required', ''); // activa required solo en visibles
            });
        }
    }

    // 🔹 Activar los campos de vehículo (siempre visibles)
    const datosVehiculo = document.getElementById('datos-vehiculo');
    if (datosVehiculo) {
        datosVehiculo.style.display = 'block';
        datosVehiculo.querySelectorAll('input, select').forEach(input => {
            input.disabled = false;
            input.setAttribute('required', '');
        });
    }

    // 🔹 Scroll al inicio del modal
    document.querySelector('.modal-body').scrollTop = 0;
}


function limpiarFormularioUsuario() {
    // 🔹 Resetea todo el formulario
    const form = document.getElementById('form-usuario');
    form.reset();

    // 🔹 Oculta y desactiva todos los bloques específicos
    document.querySelectorAll('.campos-especificos').forEach(campo => {
        campo.style.display = 'none';
        campo.querySelectorAll('input, select').forEach(input => {
            input.disabled = true;
            input.removeAttribute('required');
            input.value = '';
        });
    });

    // 🔹 Muestra datos del vehículo
    const datosVehiculo = document.getElementById('datos-vehiculo');
    if (datosVehiculo) {
        datosVehiculo.style.display = 'block';
        datosVehiculo.querySelectorAll('input, select').forEach(input => {
            input.disabled = false;
            input.setAttribute('required', '');
        });
    }
}


// --- Limpiar Formulario ---
function limpiarFormularioUsuario() {
    document.getElementById('form-usuario').reset();
    document.querySelectorAll('.campos-especificos').forEach(campo => {
        campo.style.display = 'none';
    });
    document.getElementById('datos-vehiculo').style.display = 'block';
}

// --- Inicialización ---
document.addEventListener('DOMContentLoaded', function () {
    // Mostrar sección de visitantes por defecto
    mostrarSeccion('visitantes');

    // Configurar el cierre del modal
    document.querySelector('.close').addEventListener('click', cerrarModal);

    // Configurar el cambio de tipo de usuario
    document.getElementById('tipo-usuario').addEventListener('change', cambiarCamposUsuario);
});
