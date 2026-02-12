let registrosRFID = []; 
const cuerpo = document.getElementById('cuerpo-tabla-usuarios');
let intervaloAuto = null;  // Intervalo dinámico

// --- Función que carga los registros desde PHP ---
async function cargarRegistrosRFID() {
    try {
        const res = await fetch('consultar_estacionamiento.php');
        const datos = await res.json();

        registrosRFID = datos;
        renderTablaRFID(datos);

    } catch (err) {
        console.error("Error cargando registros:", err);
        cuerpo.innerHTML = `<tr><td colspan="7">Error al obtener datos</td></tr>`;
    }
}

// --- Renderizar tabla ---
function renderTablaRFID(lista) {
    cuerpo.innerHTML = '';

    if (!Array.isArray(lista) || lista.length === 0) {
        cuerpo.innerHTML = `<tr><td colspan="7">No hay registros</td></tr>`;
        return;
    }

    lista.forEach(r => {
        const fila = document.createElement('tr');
        fila.innerHTML = `
          <td>${r.nombre || ''} ${r.apellido || ''}</td>
          <td>${r.perfil || ''}</td>
          <td>${r.telefono || ''}</td>
          <td>${r.correo || ''}</td>
          <td>${r.placa || ''}</td>
          <td>${r.fecha_hora || ''}</td>
          <td>${r.accion == '1' ? 'Salida' : 'Entrada'}</td>
        `;
        cuerpo.appendChild(fila);
    });
}

// --- FUNCIÓN PARA BUSCAR ---
document.getElementById("btn-buscar").addEventListener("click", () => {

    clearInterval(intervaloAuto);

    const texto = document.getElementById("buscar-rfid").value.toLowerCase();
    const estado = document.getElementById("filtro-estado-usuario").value; 
    const tipo = document.getElementById("filtro-tipo-usuario").value;

    const filtrados = registrosRFID.filter(r => {
        
        // --- Filtro por texto (nombre, apellido, RFID) ---
        const nombre = (r.nombre || "").toLowerCase();
        const apellido = (r.apellido || "").toLowerCase();
        const nombreCompleto = `${nombre} ${apellido}`;
        const rfid = (r.rfid || "").toString();

        const coincideTexto =
            nombre.includes(texto) ||
            apellido.includes(texto) ||
            nombreCompleto.includes(texto) ||
            rfid.includes(texto);

        // --- Filtro por estado (0 entrada / 1 salida) ---
        const coincideEstado = 
            estado === "" || r.accion == estado;

        // --- Filtro por tipo de usuario ---
        const coincideTipo = 
            tipo === "" || (r.perfil && r.perfil.toLowerCase() === tipo.toLowerCase());

        return coincideTexto && coincideEstado && coincideTipo;
    });

    renderTablaRFID(filtrados);

    // 🔁 Reanudar refresco automático después de 12 segundos
    intervaloAuto = setTimeout(() => {
        intervaloAuto = setInterval(cargarRegistrosRFID, 1000);
    }, 12000);
});


// --- CARGA INICIAL ---
cargarRegistrosRFID();
intervaloAuto = setInterval(cargarRegistrosRFID, 1000);
