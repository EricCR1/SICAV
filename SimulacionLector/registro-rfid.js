// ===== FUNCIONES GLOBALES =====

// Cambia el mensaje dentro de cada panel
function actualizarEstado(id, tipo, mensaje) {
    const contenedor = document.getElementById(id);

    contenedor.classList.remove("info", "success", "error");
    contenedor.classList.add(tipo);

    contenedor.querySelector(".estado-mensaje").textContent = mensaje;
}


// ===== REGISTRO DE ENTRADA =====

function registrarEntrada() {
    const rfid = document.getElementById("rfid-entrada").value.trim();

    if (rfid === "") {
        actualizarEstado("estado-entrada", "error", "⚠ Debes ingresar un RFID.");
        return;
    }

    fetch("entrada_rfid.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "rfid=" + encodeURIComponent(rfid)
    })
    .then(res => res.text())
    .then(msg => {
        if (msg.startsWith("✅")) {
            actualizarEstado("estado-entrada", "success", msg);
            document.getElementById("rfid-entrada").value = "";
        } else {
            actualizarEstado("estado-entrada", "error", msg);
        }
    })
    .catch(() => {
        actualizarEstado("estado-entrada", "error", "❌ Error al conectar con el servidor.");
    });
}



// ===== REGISTRO DE SALIDA =====

function registrarSalida() {
    const rfid = document.getElementById("rfid-salida").value.trim();

    if (rfid === "") {
        actualizarEstado("estado-salida", "error", "⚠ Debes ingresar un RFID.");
        return;
    }

    fetch("salida_rfid.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "rfid=" + encodeURIComponent(rfid)
    })
    .then(res => res.text())
    .then(msg => {
        if (msg.startsWith("✅")) {
            actualizarEstado("estado-salida", "success", msg);
            document.getElementById("rfid-salida").value = "";
        } else {
            actualizarEstado("estado-salida", "error", msg);
        }
    })
    .catch(() => {
        actualizarEstado("estado-salida", "error", "❌ Error al conectar con el servidor.");
    });
}
