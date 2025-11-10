// Función para registrar entrada
async function registrarEntrada() {
    const rfid = document.getElementById('rfid-entrada').value.trim();
    const estadoElemento = document.getElementById('estado-entrada');

    if (!rfid) {
        mostrarEstado(estadoElemento, 'error', '❌ Por favor ingresa un número de RFID válido');
        return;
    }

    try {
        mostrarEstado(estadoElemento, 'info', '⏳ Verificando RFID en la base de datos...');

        // Consultar la base de datos para verificar el RFID
        const respuesta = await fetch('consultar_rfid.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `rfid=${encodeURIComponent(rfid)}&tipo=entrada`
        });

        const resultado = await respuesta.json();

        if (!resultado.existe) {
            mostrarEstado(estadoElemento, 'error', `❌ RFID ${rfid} no está registrado en el sistema`);
            return;
        }

        if (resultado.estado === 'dentro') {
            mostrarEstado(estadoElemento, 'advertencia', `⚠️ ${resultado.nombre} ya se encuentra dentro de la institución`);
            return;
        }

        // Registrar la entrada en la base de datos
        const registroEntrada = await fetch('registrar_movimiento.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `rfid=${encodeURIComponent(rfid)}&tipo_movimiento=entrada`
        });

        const resultadoEntrada = await registroEntrada.json();

        if (resultadoEntrada.success) {
            mostrarEstado(estadoElemento, 'exito', `✅ Entrada registrada: ${resultado.nombre} - ${resultado.vehiculo}`);
            document.getElementById('rfid-entrada').value = '';
            playBeepSound();
        } else {
            mostrarEstado(estadoElemento, 'error', '❌ Error al registrar entrada en la base de datos');
        }

    } catch (error) {
        console.error('Error:', error);
        mostrarEstado(estadoElemento, 'error', '❌ Error de conexión con la base de datos');
    }
}

// Función para registrar salida
async function registrarSalida() {
    const rfid = document.getElementById('rfid-salida').value.trim();
    const estadoElemento = document.getElementById('estado-salida');

    if (!rfid) {
        mostrarEstado(estadoElemento, 'error', '❌ Por favor ingresa un número de RFID válido');
        return;
    }

    try {
        mostrarEstado(estadoElemento, 'info', '⏳ Verificando RFID en la base de datos...');

        // Consultar la base de datos para verificar el RFID
        const respuesta = await fetch('consultar_rfid.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `rfid=${encodeURIComponent(rfid)}&tipo=salida`
        });

        const resultado = await respuesta.json();

        if (!resultado.existe) {
            mostrarEstado(estadoElemento, 'error', `❌ RFID ${rfid} no está registrado en el sistema`);
            return;
        }

        if (resultado.estado === 'fuera') {
            mostrarEstado(estadoElemento, 'advertencia', `⚠️ ${resultado.nombre} no se encuentra dentro de la institución`);
            return;
        }

        // Registrar la salida en la base de datos
        const registroSalida = await fetch('registrar_movimiento.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `rfid=${encodeURIComponent(rfid)}&tipo_movimiento=salida`
        });

        const resultadoSalida = await registroSalida.json();

        if (resultadoSalida.success) {
            mostrarEstado(estadoElemento, 'exito', `✅ Salida registrada: ${resultado.nombre} - ${resultado.vehiculo}`);
            document.getElementById('rfid-salida').value = '';
            playBeepSound();
        } else {
            mostrarEstado(estadoElemento, 'error', '❌ Error al registrar salida en la base de datos');
        }

    } catch (error) {
        console.error('Error:', error);
        mostrarEstado(estadoElemento, 'error', '❌ Error de conexión con la base de datos');
    }
}

// Función para mostrar estado
function mostrarEstado(elemento, tipo, mensaje) {
    const iconos = {
        info: 'fas fa-info-circle',
        exito: 'fas fa-check-circle',
        error: 'fas fa-exclamation-circle',
        advertencia: 'fas fa-exclamation-triangle'
    };

    elemento.className = `estado-registro ${tipo}`;
    elemento.innerHTML = `
        <div class="estado-icon">
            <i class="${iconos[tipo]}"></i>
        </div>
        <div class="estado-mensaje">${mensaje}</div>
    `;

    // Limpiar mensaje después de 5 segundos (excepto info)
    if (tipo !== 'info') {
        setTimeout(() => {
            elemento.className = 'estado-registro info';
            elemento.innerHTML = `
                <div class="estado-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div class="estado-mensaje">
                    Listo para registrar ${elemento.id === 'estado-entrada' ? 'entrada' : 'salida'}
                </div>
            `;
        }, 5000);
    }
}

// Función para simular sonido de beep
function playBeepSound() {
    try {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        oscillator.frequency.value = 800;
        oscillator.type = 'sine';
        
        gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.2);
        
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.2);
    } catch (e) {
        console.log('Audio no soportado');
    }
}

// Configurar eventos al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    // Configurar eventos de entrada automática al presionar Enter
    document.getElementById('rfid-entrada').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            registrarEntrada();
        }
    });

    document.getElementById('rfid-salida').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            registrarSalida();
        }
    });

    // Limpiar campos al hacer clic
    document.getElementById('rfid-entrada').addEventListener('click', function() {
        this.select();
    });

    document.getElementById('rfid-salida').addEventListener('click', function() {
        this.select();
    });
});