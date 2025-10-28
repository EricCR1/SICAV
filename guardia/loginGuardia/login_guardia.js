document.addEventListener('DOMContentLoaded', function() {
    const formGuardia = document.getElementById('form-guardia');
    const alertContainer = document.getElementById('alert-container');

    formGuardia.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const usuario = document.getElementById('U-guardia').value.trim();
        const password = document.getElementById('123').value.trim();
        
        if (validarCredencialesGuardia(usuario, password)) {
            mostrarAlerta('✅ Login exitoso. Redirigiendo al panel de guardia...', 'success');
            
            // Guardar sesión en localStorage
            localStorage.setItem('usuarioLogueado', usuario);
            localStorage.setItem('rolUsuario', 'guardia');
            localStorage.setItem('timestampLogin', new Date().getTime());
            
            // Redirigir después de 1 segundo
            setTimeout(() => {
                window.location.href = 'guardia.html';
            }, 1000);
        } else {
            mostrarAlerta('❌ Usuario o contraseña incorrectos para Guardia', 'error');
        }
    });

    function validarCredencialesGuardia(usuario, password) {
        // Credenciales de prueba para guardia
        const credencialesGuardia = {
            'guardia': 'guardia123',
            'vigilante': 'sicav2024',
            'seguridad': 'seguro123'
        };
        
        return credencialesGuardia[usuario] === password;
    }

    function mostrarAlerta(mensaje, tipo) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${tipo}`;
        alertDiv.textContent = mensaje;
        
        alertContainer.appendChild(alertDiv);
        
        // Ocultar después de 5 segundos
        setTimeout(() => {
            alertDiv.style.opacity = '0';
            alertDiv.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.parentNode.removeChild(alertDiv);
                }
            }, 300);
        }, 5000);
    }

    // Validación en tiempo real
    const inputs = document.querySelectorAll('input[type="text"], input[type="password"]');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            limpiarError(this);
        });
        
        input.addEventListener('blur', function() {
            validarCampo(this);
        });
    });

    function validarCampo(campo) {
        const valor = campo.value.trim();
        
        if (campo.hasAttribute('required') && valor === '') {
            mostrarErrorCampo(campo, 'Este campo es obligatorio');
            return false;
        }
        
        return true;
    }

    function mostrarErrorCampo(campo, mensaje) {
        limpiarError(campo);
        campo.style.borderColor = '#ef4444';
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = mensaje;
        
        campo.parentNode.appendChild(errorDiv);
    }

    function limpiarError(campo) {
        campo.style.borderColor = '';
        const errorExistente = campo.parentNode.querySelector('.error-message');
        if (errorExistente) {
            errorExistente.remove();
        }
    }
});