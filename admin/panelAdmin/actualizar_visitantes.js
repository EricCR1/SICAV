let registrosVisitantes = [];
const cuerpoVisitantes = document.getElementById('cuerpo-tabla-visitantes');
let intervaloVisitantes = null;

// --- Cargar registros desde PHP ---
async function cargarVisitantes() {
    try {
        const res = await fetch('consultar_visitantes.php');
        const datos = await res.json();

        registrosVisitantes = datos;
        renderTablaVisitantes(datos);

    } catch (err) {
        console.error("Error cargando visitantes:", err);
        cuerpoVisitantes.innerHTML = `<tr><td colspan="5">Error al obtener datos</td></tr>`;
    }
}

// --- Dibujar tabla ---
function renderTablaVisitantes(lista) {
    cuerpoVisitantes.innerHTML = "";

    if (!Array.isArray(lista) || lista.length === 0) {
        cuerpoVisitantes.innerHTML = `<tr><td colspan="5">No hay registros</td></tr>`;
        return;
    }

    lista.forEach(r => {
        const fila = document.createElement("tr");
        fila.innerHTML = `
            <td>${r.numero_asignado || ''}</td>
            <td>${r.nombre || ''}</td>
            <td>${r.motivo || ''}</td>
            <td>${r.fecha_hora || ''}</td>
            <td>${r.accion == "1" ? "Salida" : "Entrada"}</td>
        `;
        cuerpoVisitantes.appendChild(fila);
    });
}

// --- BOTÓN BUSCAR ---
document.getElementById("btn-buscar-visitante").addEventListener("click", () => {

    clearInterval(intervaloVisitantes);

    const texto = document.getElementById("buscar-visitante").value.toLowerCase();
    const estado = document.getElementById("filtro-estado-visitante").value;
    const fecha = document.getElementById("filtro-fecha").value;

    const filtrados = registrosVisitantes.filter(r => {

        // --- Filtro por texto (nombre / número asignado) ---
        const nombre = (r.nombre || "").toLowerCase();
        const numero = (r.numero_asignado || "").toString();
        const coincideTexto =
            texto === "" ||
            nombre.includes(texto) ||
            numero.includes(texto);

        // --- Filtro por estado ---
        const coincideEstado =
            estado === "" || r.accion == estado;

        // --- Filtro por FECHA ---
        const fechaRegistro = r.fecha_hora ? r.fecha_hora.substring(0, 10) : "";
        const coincideFecha =
            fecha === "" || fechaRegistro === fecha;

        return coincideTexto && coincideEstado && coincideFecha;
    });

    renderTablaVisitantes(filtrados);

    // Reanudar auto-refresh después de 12s
    intervaloVisitantes = setTimeout(() => {
        intervaloVisitantes = setInterval(cargarVisitantes, 5000);
    }, 12000);
});

// --- Carga inicial ---
cargarVisitantes();
intervaloVisitantes = setInterval(cargarVisitantes, 5000);
