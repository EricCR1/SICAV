/* docentes.js  */
const tbody = document.getElementById("tbody-docentes");
const modal = document.getElementById("modal");
const form = document.getElementById("form-docente");
const listaVeh = document.getElementById("lista-vehiculos");
const btnAddVeh = document.getElementById("btn-add-veh");
const btnClearVeh = document.getElementById("btn-clear-veh");
const modoInput = document.getElementById("modo");

let docentes = []; // cache

// ---------- Util: crear bloque vehículo ----------
function crearBloqueVeh(data = {}) {
  const wrapper = document.createElement("div");
  wrapper.className = "veh";

  wrapper.innerHTML = `
    <div class="row">
      <label class="mini">RFID<input name="rfid" value="${data.rfid || ''}"></label>
      <label class="mini">Tipo<input name="tipo_vehiculo" value="${data.tipo_vehiculo || ''}"></label>
      <label class="mini">Marca<input name="marca" value="${data.marca || ''}"></label>
    </div>
    <div class="row">
      <label class="mini">Modelo<input name="modelo" value="${data.modelo || ''}"></label>
      <label class="mini">Año<input name="año" value="${data.año || ''}"></label>
      <label class="mini">Color<input name="color" value="${data.color || ''}"></label>
    </div>
    <div class="row">
      <label class="full">Placa<input name="placa" value="${data.placa || ''}"></label>
    </div>
    <div class="veh-actions">
      <button type="button" class="btn" onclick="this.closest('.veh').remove()">Eliminar vehículo</button>
    </div>
  `;
  return wrapper;
}

// ---------- Añadir vehículo ----------
btnAddVeh.addEventListener("click", () => {
  listaVeh.appendChild(crearBloqueVeh());
});

// limpiar vehículos
btnClearVeh.addEventListener("click", () => {
  listaVeh.innerHTML = "";
});

// ---------- Cargar listado ----------
async function cargarDocentes() {
  tbody.innerHTML = `<tr><td colspan="8" class="empty">Cargando...</td></tr>`;
  try {
    const res = await fetch("obtener_docentes.php");
    const data = await res.json();
    docentes = data;
    renderTabla(data);
  } catch (e){
    console.error(e);
    tbody.innerHTML = `<tr><td colspan="8" class="empty">Error al cargar</td></tr>`;
  }
}

function renderTabla(list){
  if (!Array.isArray(list) || list.length === 0) {
    tbody.innerHTML = `<tr><td colspan="8" class="empty">No hay registros</td></tr>`;
    return;
  }
  tbody.innerHTML = "";
  list.forEach(d => {
    const tr = document.createElement("tr");
    const vehCount = Array.isArray(d.vehiculos) ? d.vehiculos.length : 0;
    const placas = (d.vehiculos || []).map(v => v.placa || v.rfid || "—").join(" / ");
    tr.innerHTML = `
      <td>${d.id_docente}</td>
      <td>${d.nombre} ${d.apellido}</td>
      <td>${d.rfc || '—'}</td>
      <td>${d.correo || '—'}</td>
      <td>${d.telefono || '—'}</td>
      <td>${d.especialidad || '—'}</td>
      <td title="${placas}">${vehCount} vehículo(s)</td>
      <td>
        <button class="btn" onclick='abrirEditar(${JSON.stringify(d)})'>✏️</button>
        <button class="btn" onclick='eliminarDocente("${d.id_docente}")'>🗑️</button>
      </td>
    `;
    tbody.appendChild(tr);
  });
}

// ---------- Abrir modal nuevo ----------
function abrirModalNuevo(){
  modoInput.value = "nuevo";
  document.getElementById("modal-title").innerText = "Nuevo Docente";
  form.reset();
  listaVeh.innerHTML = "";
  document.getElementById("id_docente").readOnly = false;
  modal.setAttribute("aria-hidden","false");
}

// ---------- Abrir modal editar (rellena datos) ----------
function abrirEditar(objAsJson){
  // objeto puede venir como string si se pasa en atributo onclick
  const obj = (typeof objAsJson === "string") ? JSON.parse(objAsJson) : objAsJson;
  modoInput.value = "editar";
  document.getElementById("modal-title").innerText = "Editar Docente";
  document.getElementById("id_docente").value = obj.id_docente || "";
  document.getElementById("id_docente").readOnly = true;
  document.getElementById("nombre").value = obj.nombre || "";
  document.getElementById("apellido").value = obj.apellido || "";
  document.getElementById("rfc").value = obj.rfc || "";
  document.getElementById("f_nacimiento").value = obj.f_nacimiento || "";
  document.getElementById("correo").value = obj.correo || "";
  document.getElementById("telefono").value = obj.telefono || "";
  document.getElementById("especialidad").value = obj.especialidad || "";
  document.getElementById("estado").value = obj.estado || "activo";

  // vehiculos
  listaVeh.innerHTML = "";
  if (Array.isArray(obj.vehiculos) && obj.vehiculos.length > 0) {
    obj.vehiculos.forEach(v => listaVeh.appendChild(crearBloqueVeh(v)));
  }
  modal.setAttribute("aria-hidden","false");
}

// ---------- Cerrar modal ----------
function cerrarModal(){
  modal.setAttribute("aria-hidden","true");
}

// ---------- Recolectar vehículos desde DOM ----------
function recolectarVehiculos(){
  const bloques = Array.from(listaVeh.querySelectorAll(".veh"));
  const arr = bloques.map(b => {
    const rfid = b.querySelector("input[name='rfid']").value.trim();
    return {
      rfid,
      tipo_vehiculo: b.querySelector("input[name='tipo_vehiculo']").value.trim(),
      marca: b.querySelector("input[name='marca']").value.trim(),
      modelo: b.querySelector("input[name='modelo']").value.trim(),
      año: b.querySelector("input[name='año']").value.trim(),
      color: b.querySelector("input[name='color']").value.trim(),
      placa: b.querySelector("input[name='placa']").value.trim()
    };
  }).filter(v => v.rfid || v.placa); // ignorar bloques vacíos
  return arr;
}

// ---------- Submit (insertar / actualizar) ----------
form.addEventListener("submit", async (e) => {
  e.preventDefault();
  const modo = modoInput.value || "nuevo";

  const payload = {
    id_docente: document.getElementById("id_docente").value.trim(),
    nombre: document.getElementById("nombre").value.trim(),
    apellido: document.getElementById("apellido").value.trim(),
    rfc: document.getElementById("rfc").value.trim(),
    f_nacimiento: document.getElementById("f_nacimiento").value || "",
    correo: document.getElementById("correo").value.trim(),
    telefono: document.getElementById("telefono").value.trim(),
    especialidad: document.getElementById("especialidad").value.trim(),
    estado: document.getElementById("estado").value || "activo",
    vehiculos: recolectarVehiculos()
  };

  const url = (modo === "nuevo") ? "insertar_docente.php" : "actualizar_docente.php";
  const formData = new FormData();
  for (const k in payload) {
    if (k === "vehiculos") formData.append("vehiculos", JSON.stringify(payload.vehiculos));
    else formData.append(k, payload[k]);
  }

  try {
    const res = await fetch(url, { method: "POST", body: formData });
    const txt = await res.text();
    alert(txt);
    cerrarModal();
    cargarDocentes();
  } catch (err) {
    console.error(err);
    alert("Error en la petición");
  }
});

// ---------- Eliminar ----------
async function eliminarDocente(id) {
  if (!confirm("¿Eliminar docente y sus vehículos?")) return;
  try {
    const res = await fetch("eliminar_docente.php?id=" + encodeURIComponent(id));
    const txt = await res.text();
    alert(txt);
    cargarDocentes();
  } catch (err) {
    console.error(err);
    alert("Error al eliminar");
  }
}

// init
document.addEventListener("DOMContentLoaded", () => {
  cargarDocentes();
});
