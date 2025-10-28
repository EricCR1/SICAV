document.addEventListener('DOMContentLoaded', function() {
    const formAdmin = document.getElementById('form-admin');
    const alertContainer = document.getElementById('alert-container');

    formAdmin.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const usuario = document.getElementById('U-admin').value.trim();
        const password = document.getElementById('123').value.trim();
        
        if (validarCredencialesAdmin(usuario, password)) {
            mostrarAlerta('✅ Login exitoso. Redirigiendo al panel de administración...', 'success');
            
            // Guardar sesión en localStorage
            localStorage.setItem('usuarioLogueado', 'admin');
            localStorage.setItem('rolUsuario', 'administrador');
            localStorage.setItem('timestampLogin', new Date().getTime());
            
            // Redirigir después de 1 segundo
            setTimeout(() => {
                window.location.href = 'admin.html';
            }, 1000);
        } else {
            mostrarAlerta('❌ Usuario o contraseña incorrectos para Administrador', 'error');
        }
    });

    function validarCredencialesAdmin(usuario, password) {
        // Credenciales de prueba para administrador
        const credencialesAdmin = {
            'admin': 'admin123',
            'administrador': 'sicav2024',
            'root': 'root123'
        };
        
        return credencialesAdmin[usuario] === password;
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