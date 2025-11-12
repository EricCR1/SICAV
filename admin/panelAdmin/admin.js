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
    
    // 🔹 Configurar validaciones según el tipo de usuario
    if (tipoUsuario === 'alumno') {
        setTimeout(configurarValidacionesAlumno, 100);
    } else if (tipoUsuario === 'docente') {
        setTimeout(configurarValidacionesDocente, 100);
    } else if (tipoUsuario === 'administrativo') {
        setTimeout(configurarValidacionesAdministrativo, 100);
    } else if (tipoUsuario === 'guardia') {
        setTimeout(configurarValidacionesGuardia, 100);
    } else if (tipoUsuario === 'personal') {
        setTimeout(configurarValidacionesPersonal, 100);
    } else if (tipoUsuario === 'proveedor') {
        setTimeout(configurarValidacionesProveedor, 100);
    }
}

function limpiarFormularioUsuario() {
    // 🔹 Resetea todo el formulario
    const form = document.getElementById('form-usuario');
    form.reset();

    // 🔹 Oculta y desactivar todos los bloques específicos
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

// ================================
// Validaciones en tiempo real para ALUMNO
// ================================
function configurarValidacionesAlumno() {
    const noControlInput = document.querySelector('#campos-alumno input[name="no_control"]');
    const curpInput = document.querySelector('#campos-alumno input[name="curp"]');
    const telefonoInput = document.querySelector('#campos-alumno input[name="telefono"]');
    const emailInput = document.querySelector('#campos-alumno input[name="correo"]');

    // Validar número de control (solo números, máximo 8)
    if (noControlInput) {
        noControlInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 8) {
                this.value = this.value.slice(0, 8);
            }
            validarCampo(this, /^[0-9]{8}$/.test(this.value));
        });
    }

    // Validar CURP (solo mayúsculas y números)
    if (curpInput) {
        curpInput.addEventListener('input', function(e) {
            this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            validarCampo(this, /^[A-Z]{4}[0-9]{6}[A-Z]{6}[0-9A-Z]{2}$/.test(this.value));
        });
    }

    // Validar teléfono (solo números, máximo 10)
    if (telefonoInput) {
        telefonoInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 10) {
                this.value = this.value.slice(0, 10);
            }
            validarCampo(this, /^[0-9]{10}$/.test(this.value));
        });
    }

    // Validar email básico
    if (emailInput) {
        emailInput.addEventListener('blur', function(e) {
            const emailValido = this.value && this.value.includes('@') && 
                              this.value.includes('.') && 
                              this.value.length > 5;
            validarCampo(this, emailValido);
        });
    }
}

// ================================
// Validaciones en tiempo real para DOCENTE
// ================================
function configurarValidacionesDocente() {
    const rfcInput = document.querySelector('#campos-docente input[name="rfc"]');
    const telefonoInput = document.querySelector('#campos-docente input[name="telefono"]');
    const emailInput = document.querySelector('#campos-docente input[name="correo"]');
    const especialidadInput = document.querySelector('#campos-docente input[name="especialidad"]');

    // Validar RFC (formato: 3-4 letras + 6 números + 3 caracteres)
    if (rfcInput) {
        rfcInput.addEventListener('input', function(e) {
            this.value = this.value.toUpperCase().replace(/[^A-ZÑ&0-9]/g, '');
            if (this.value.length > 13) {
                this.value = this.value.slice(0, 13);
            }
            
            // Validar formato RFC
            const rfcValido = /^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/.test(this.value);
            validarCampo(this, rfcValido);
        });
    }

    // Validar teléfono (solo números, máximo 10)
    if (telefonoInput) {
        telefonoInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 10) {
                this.value = this.value.slice(0, 10);
            }
            validarCampo(this, /^[0-9]{10}$/.test(this.value));
        });
    }

    // Validar email básico
    if (emailInput) {
        emailInput.addEventListener('blur', function(e) {
            const emailValido = this.value && this.value.includes('@') && 
                              this.value.includes('.') && 
                              this.value.length > 5;
            validarCampo(this, emailValido);
        });
    }

    // Validar especialidad (solo letras, espacios y algunos caracteres especiales)
    if (especialidadInput) {
        especialidadInput.addEventListener('input', function(e) {
            // Permitir letras, espacios, acentos y algunos caracteres especiales comunes
            this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\-.,]/g, '');
            const especialidadValida = this.value.length >= 2;
            validarCampo(this, especialidadValida);
        });
    }
}

// ================================
// Validaciones en tiempo real para ADMINISTRATIVO
// ================================
function configurarValidacionesAdministrativo() {
    const rfcInput = document.querySelector('#campos-administrativo input[name="rfc"]');
    const telefonoInput = document.querySelector('#campos-administrativo input[name="telefono"]');
    const emailInput = document.querySelector('#campos-administrativo input[name="correo"]');

    // Validar RFC (formato: 3-4 letras + 6 números + 3 caracteres)
    if (rfcInput) {
        rfcInput.addEventListener('input', function(e) {
            this.value = this.value.toUpperCase().replace(/[^A-ZÑ&0-9]/g, '');
            if (this.value.length > 13) {
                this.value = this.value.slice(0, 13);
            }
            
            // Validar formato RFC
            const rfcValido = /^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/.test(this.value);
            validarCampo(this, rfcValido);
        });
    }

    // Validar teléfono (solo números, máximo 10)
    if (telefonoInput) {
        telefonoInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 10) {
                this.value = this.value.slice(0, 10);
            }
            validarCampo(this, /^[0-9]{10}$/.test(this.value));
        });
    }

    // Validar email básico
    if (emailInput) {
        emailInput.addEventListener('blur', function(e) {
            const emailValido = this.value && this.value.includes('@') && 
                              this.value.includes('.') && 
                              this.value.length > 5;
            validarCampo(this, emailValido);
        });
    }
}

// ================================
// Validaciones en tiempo real para GUARDIA
// ================================
function configurarValidacionesGuardia() {
    const curpInput = document.querySelector('#campos-guardia input[name="curp"]');
    const telefonoInput = document.querySelector('#campos-guardia input[name="telefono"]');

    // Validar CURP (solo mayúsculas y números)
    if (curpInput) {
        curpInput.addEventListener('input', function(e) {
            this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            validarCampo(this, /^[A-Z]{4}[0-9]{6}[A-Z]{6}[0-9A-Z]{2}$/.test(this.value));
        });
    }

    // Validar teléfono (solo números, máximo 10)
    if (telefonoInput) {
        telefonoInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 10) {
                this.value = this.value.slice(0, 10);
            }
            validarCampo(this, /^[0-9]{10}$/.test(this.value));
        });
    }
}

// ================================
// Validaciones en tiempo real para PERSONAL
// ================================
function configurarValidacionesPersonal() {
    const curpInput = document.querySelector('#campos-personal input[name="curp"]');
    const telefonoInput = document.querySelector('#campos-personal input[name="telefono"]');

    // Validar CURP (solo mayúsculas y números)
    if (curpInput) {
        curpInput.addEventListener('input', function(e) {
            this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            validarCampo(this, /^[A-Z]{4}[0-9]{6}[A-Z]{6}[0-9A-Z]{2}$/.test(this.value));
        });
    }

    // Validar teléfono (solo números, máximo 10)
    if (telefonoInput) {
        telefonoInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 10) {
                this.value = this.value.slice(0, 10);
            }
            validarCampo(this, /^[0-9]{10}$/.test(this.value));
        });
    }
}

// ================================
// Validaciones en tiempo real para PROVEEDOR
// ================================
function configurarValidacionesProveedor() {
    const rfcInput = document.querySelector('#campos-proveedor input[name="rfc"]');
    const telefonoInput = document.querySelector('#campos-proveedor input[name="telefono"]');
    const emailInput = document.querySelector('#campos-proveedor input[name="correo"]');
    const razonSocialSelect = document.querySelector('#campos-proveedor select[name="razon_social"]');

    // Validar RFC (formato: 3-4 letras + 6 números + 3 caracteres)
    if (rfcInput) {
        rfcInput.addEventListener('input', function(e) {
            this.value = this.value.toUpperCase().replace(/[^A-ZÑ&0-9]/g, '');
            if (this.value.length > 13) {
                this.value = this.value.slice(0, 13);
            }
            
            // Validar formato RFC
            const rfcValido = /^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/.test(this.value);
            validarCampo(this, rfcValido);
        });
    }

    // Validar teléfono (solo números, máximo 10)
    if (telefonoInput) {
        telefonoInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 10) {
                this.value = this.value.slice(0, 10);
            }
            validarCampo(this, /^[0-9]{10}$/.test(this.value));
        });
    }

    // Validar email básico
    if (emailInput) {
        emailInput.addEventListener('blur', function(e) {
            const emailValido = this.value && this.value.includes('@') && 
                              this.value.includes('.') && 
                              this.value.length > 5;
            validarCampo(this, emailValido);
        });
    }

    // Manejar razón social "otra"
    if (razonSocialSelect) {
        razonSocialSelect.addEventListener('change', function() {
            const otraRazonContainer = document.getElementById('otra-razon-social-container');
            if (this.value === 'otra') {
                otraRazonContainer.style.display = 'block';
                // Hacer requerido el campo de otra razón social
                const otraRazonInput = document.querySelector('input[name="otra_razon_social"]');
                if (otraRazonInput) {
                    otraRazonInput.required = true;
                }
            } else {
                otraRazonContainer.style.display = 'none';
                // Quitar requerido del campo de otra razón social
                const otraRazonInput = document.querySelector('input[name="otra_razon_social"]');
                if (otraRazonInput) {
                    otraRazonInput.required = false;
                    otraRazonInput.value = '';
                }
            }
        });
    }
}

// ================================
// Validación del campo AÑO del vehículo
// ================================
function configurarValidacionAnio() {
    const anioInput = document.querySelector('input[name="año"]');
    if (anioInput) {
        anioInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 4) {
                this.value = this.value.slice(0, 4);
            }
            const anioValido = /^[0-9]{4}$/.test(this.value) && 
                             parseInt(this.value) >= 1990 && 
                             parseInt(this.value) <= 2024;
            validarCampo(this, anioValido);
        });
    }
}

function validarCampo(campo, esValido) {
    if (esValido) {
        campo.classList.remove('input-error');
        campo.classList.add('input-success');
    } else {
        campo.classList.add('input-error');
        campo.classList.remove('input-success');
    }
}

// ================================
// Manejo de campos "otro" en marcas y colores
// ================================
function configurarCamposOtro() {
    const marcaSelect = document.querySelector('select[name="marca"]');
    const colorSelect = document.querySelector('select[name="color"]');
    
    if (marcaSelect) {
        marcaSelect.addEventListener('change', function() {
            const otroMarcaInput = document.getElementById('otro-marca-input');
            
            if (this.value === 'otro') {
                // Crear campo de texto para otra marca si no existe
                if (!otroMarcaInput) {
                    const inputGroup = this.parentElement;
                    const nuevoInput = document.createElement('input');
                    nuevoInput.type = 'text';
                    nuevoInput.name = 'otro_marca';
                    nuevoInput.id = 'otro-marca-input';
                    nuevoInput.placeholder = 'Especificar otra marca';
                    nuevoInput.required = true;
                    nuevoInput.style.marginTop = '5px';
                    
                    inputGroup.appendChild(nuevoInput);
                } else {
                    otroMarcaInput.style.display = 'block';
                }
            } else {
                // Ocultar campo de otra marca si existe
                if (otroMarcaInput) {
                    otroMarcaInput.style.display = 'none';
                    otroMarcaInput.value = '';
                }
            }
        });
    }
    
    if (colorSelect) {
        colorSelect.addEventListener('change', function() {
            const otroColorInput = document.getElementById('otro-color-input');
            
            if (this.value === 'otro') {
                // Crear campo de texto para otro color si no existe
                if (!otroColorInput) {
                    const inputGroup = this.parentElement;
                    const nuevoInput = document.createElement('input');
                    nuevoInput.type = 'text';
                    nuevoInput.name = 'otro_color';
                    nuevoInput.id = 'otro-color-input';
                    nuevoInput.placeholder = 'Especificar otro color';
                    nuevoInput.required = true;
                    nuevoInput.style.marginTop = '5px';
                    
                    inputGroup.appendChild(nuevoInput);
                } else {
                    otroColorInput.style.display = 'block';
                }
            } else {
                // Ocultar campo de otro color si existe
                if (otroColorInput) {
                    otroColorInput.style.display = 'none';
                    otroColorInput.value = '';
                }
            }
        });
    }
}

// ================================
// Validación del formulario completo
// ================================
function validarFormularioAlumno() {
    const camposAlumno = document.getElementById('campos-alumno');
    if (!camposAlumno || camposAlumno.style.display === 'none') {
        return true;
    }

    const noControl = document.querySelector('#campos-alumno input[name="no_control"]');
    const curp = document.querySelector('#campos-alumno input[name="curp"]');
    const telefono = document.querySelector('#campos-alumno input[name="telefono"]');
    const email = document.querySelector('#campos-alumno input[name="correo"]');

    let esValido = true;

    if (noControl && !/^[0-9]{8}$/.test(noControl.value)) {
        validarCampo(noControl, false);
        esValido = false;
    }

    if (curp && !/^[A-Z]{4}[0-9]{6}[A-Z]{6}[0-9A-Z]{2}$/.test(curp.value)) {
        validarCampo(curp, false);
        esValido = false;
    }

    if (telefono && !/^[0-9]{10}$/.test(telefono.value)) {
        validarCampo(telefono, false);
        esValido = false;
    }

    if (email && !/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(email.value)) {
        validarCampo(email, false);
        esValido = false;
    }

    return esValido;
}

function validarFormularioDocente() {
    const camposDocente = document.getElementById('campos-docente');
    if (!camposDocente || camposDocente.style.display === 'none') {
        return true;
    }

    const rfc = document.querySelector('#campos-docente input[name="rfc"]');
    const telefono = document.querySelector('#campos-docente input[name="telefono"]');
    const email = document.querySelector('#campos-docente input[name="correo"]');
    const especialidad = document.querySelector('#campos-docente input[name="especialidad"]');

    let esValido = true;

    if (rfc && !/^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/.test(rfc.value)) {
        validarCampo(rfc, false);
        esValido = false;
    }

    if (telefono && !/^[0-9]{10}$/.test(telefono.value)) {
        validarCampo(telefono, false);
        esValido = false;
    }

    if (email && !/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(email.value)) {
        validarCampo(email, false);
        esValido = false;
    }

    if (especialidad && especialidad.value.trim().length < 2) {
        validarCampo(especialidad, false);
        esValido = false;
    }

    return esValido;
}

function validarFormularioAdministrativo() {
    const camposAdministrativo = document.getElementById('campos-administrativo');
    if (!camposAdministrativo || camposAdministrativo.style.display === 'none') {
        return true;
    }

    const rfc = document.querySelector('#campos-administrativo input[name="rfc"]');
    const telefono = document.querySelector('#campos-administrativo input[name="telefono"]');
    const email = document.querySelector('#campos-administrativo input[name="correo"]');

    let esValido = true;

    if (rfc && !/^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/.test(rfc.value)) {
        validarCampo(rfc, false);
        esValido = false;
    }

    if (telefono && !/^[0-9]{10}$/.test(telefono.value)) {
        validarCampo(telefono, false);
        esValido = false;
    }

    if (email && !/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(email.value)) {
        validarCampo(email, false);
        esValido = false;
    }

    return esValido;
}

function validarFormularioGuardia() {
    const camposGuardia = document.getElementById('campos-guardia');
    if (!camposGuardia || camposGuardia.style.display === 'none') {
        return true;
    }

    const curp = document.querySelector('#campos-guardia input[name="curp"]');
    const telefono = document.querySelector('#campos-guardia input[name="telefono"]');

    let esValido = true;

    if (curp && !/^[A-Z]{4}[0-9]{6}[A-Z]{6}[0-9A-Z]{2}$/.test(curp.value)) {
        validarCampo(curp, false);
        esValido = false;
    }

    if (telefono && !/^[0-9]{10}$/.test(telefono.value)) {
        validarCampo(telefono, false);
        esValido = false;
    }

    return esValido;
}

function validarFormularioPersonal() {
    const camposPersonal = document.getElementById('campos-personal');
    if (!camposPersonal || camposPersonal.style.display === 'none') {
        return true;
    }

    const curp = document.querySelector('#campos-personal input[name="curp"]');
    const telefono = document.querySelector('#campos-personal input[name="telefono"]');

    let esValido = true;

    if (curp && !/^[A-Z]{4}[0-9]{6}[A-Z]{6}[0-9A-Z]{2}$/.test(curp.value)) {
        validarCampo(curp, false);
        esValido = false;
    }

    if (telefono && !/^[0-9]{10}$/.test(telefono.value)) {
        validarCampo(telefono, false);
        esValido = false;
    }

    return esValido;
}

function validarFormularioProveedor() {
    const camposProveedor = document.getElementById('campos-proveedor');
    if (!camposProveedor || camposProveedor.style.display === 'none') {
        return true;
    }

    const rfc = document.querySelector('#campos-proveedor input[name="rfc"]');
    const telefono = document.querySelector('#campos-proveedor input[name="telefono"]');
    const email = document.querySelector('#campos-proveedor input[name="correo"]');
    const razonSocial = document.querySelector('#campos-proveedor select[name="razon_social"]');
    const otraRazon = document.querySelector('#campos-proveedor input[name="otra_razon_social"]');

    let esValido = true;

    if (rfc && !/^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/.test(rfc.value)) {
        validarCampo(rfc, false);
        esValido = false;
    }

    if (telefono && !/^[0-9]{10}$/.test(telefono.value)) {
        validarCampo(telefono, false);
        esValido = false;
    }

    if (email && !/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(email.value)) {
        validarCampo(email, false);
        esValido = false;
    }

    if (razonSocial && razonSocial.value === 'otra' && (!otraRazon || !otraRazon.value.trim())) {
        if (otraRazon) {
            validarCampo(otraRazon, false);
        }
        esValido = false;
    }

    return esValido;
}

// ================================
// Manejo del envío del formulario
// ================================
document.getElementById('form-usuario').addEventListener('submit', function(e) {
    const tipoUsuario = document.getElementById('tipo-usuario').value;
    let formularioValido = true;
    
    if (tipoUsuario === 'alumno') {
        formularioValido = validarFormularioAlumno();
    } else if (tipoUsuario === 'docente') {
        formularioValido = validarFormularioDocente();
    } else if (tipoUsuario === 'administrativo') {
        formularioValido = validarFormularioAdministrativo();
    } else if (tipoUsuario === 'guardia') {
        formularioValido = validarFormularioGuardia();
    } else if (tipoUsuario === 'personal') {
        formularioValido = validarFormularioPersonal();
    } else if (tipoUsuario === 'proveedor') {
        formularioValido = validarFormularioProveedor();
    }
    
    if (!formularioValido) {
        e.preventDefault();
        alert('Por favor, corrige los errores en el formulario antes de enviar.');
        return;
    }
    
    // Si todo está bien, el formulario se envía normalmente
    console.log('Formulario enviado correctamente');
});

// ================================
// Inicialización
// ================================
document.addEventListener('DOMContentLoaded', function() {
    // Mostrar sección de visitantes por defecto
    mostrarSeccion('visitantes');

    // Configurar el cierre del modal
    document.querySelector('.close').addEventListener('click', cerrarModal);

    // Configurar el cambio de tipo de usuario
    document.getElementById('tipo-usuario').addEventListener('change', function() {
        cambiarCamposUsuario();
    });

    // Configurar validación de placas (siempre en mayúsculas)
    const placaInput = document.querySelector('input[name="placa"]');
    if (placaInput) {
        placaInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    }

    // Configurar validación del año del vehículo
    configurarValidacionAnio();

    // Configurar campos "otro" para marcas y colores
    configurarCamposOtro();
});