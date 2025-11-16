const cuerpo = document.getElementById("tabla-visitantes-activos");

async function cargarVisitantesActivos() {
    try {
        const res = await fetch("tablaVisitantes.php");
        const datos = await res.json();

        cuerpo.innerHTML = "";

        if (!Array.isArray(datos) || datos.length === 0) {
            cuerpo.innerHTML = `
                <tr>
                    <td colspan="5" class="empty-state">No hay visitantes activos</td>
                </tr>
            `;
            return;
        }

        datos.forEach(v => {
            const fila = document.createElement("tr");

            fila.innerHTML = `
                <td>${v.nombre}</td>
                <td>${v.numero}</td>
                <td>${v.motivo}</td>
                <td>${v.fecha}</td>
                <td>
                    <label class="switch-container">
                        <input type="checkbox" class="toggle-salida" data-id="${v.id_visitante}" checked>
                        <span class="slider"></span>
                    </label>
                </td>
            `;

            cuerpo.appendChild(fila);
        });

        activarEventosToggle();

    } catch (e) {
        cuerpo.innerHTML = `<tr><td colspan="5">Error al cargar</td></tr>`;
        console.error(e);
    }
}

function activarEventosToggle() {
    document.querySelectorAll(".toggle-salida").forEach(chk => {
        chk.addEventListener("change", async function () {

            if (!this.checked) {
                const id = this.dataset.id;

                // Cambiar color del switch
                this.nextElementSibling.classList.add("off");

                // Registrar salida
                const res = await fetch("salida_visitantes.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `id_visitante=${id}`
                });

                const data = await res.json();

                if (data.status === "ok") {
                    // Recargar tabla
                    setTimeout(cargarVisitantesActivos, 400);
                } else {
                    alert("Error al registrar la salida");
                }
            }
        });
    });
}

// Cargar auto cada 4s
cargarVisitantesActivos();
setInterval(cargarVisitantesActivos, 4000);
