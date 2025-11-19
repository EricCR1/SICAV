const tbody = document.getElementById("tabla-guardias");
const modal = document.getElementById("modal");
const form = document.getElementById("form-guardia");

// =======================
// Cargar guardias
// =======================
function cargarGuardias() {
    fetch("obtener_guardias.php")
    .then(r => r.json())
    .then(data => {
        tbody.innerHTML = "";

        data.forEach(g => {
            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td>${g.id_guardia}</td>
                <td>${g.nombre} ${g.apellido}</td>
                <td>${g.curp}</td>
                <td>${g.telefono}</td>
                <td>${g.turno}</td>
                <td>${g.placa ?? "—"}</td>
                <td>
                    <button onclick='editar(${JSON.stringify(g)})'>✏️</button>
                    <button onclick='eliminar("${g.id_guardia}")'>🗑️</button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    });
}

cargarGuardias();

// =======================
// Abrir modal
// =======================
function abrirModal() {
    modal.style.display = "block";
    form.reset();
    document.getElementById("modo").value = "nuevo";
    document.getElementById("id_guardia").readOnly = false;
}

// =======================
function cerrarModal() {
    modal.style.display = "none";
}

// =======================
// Editar
// =======================
function editar(g) {
    abrirModal();

    document.getElementById("modo").value = "editar";
    document.getElementById("id_guardia").readOnly = true;

    for (let campo in g) {
        if (document.getElementById(campo)) {
            document.getElementById(campo).value = g[campo];
        }
    }
}

// =======================
// Guardar
// =======================
form.addEventListener("submit", e => {
    e.preventDefault();

    const datos = new FormData(form);
    const archivo =
        (document.getElementById("modo").value === "nuevo")
        ? "insertar_guardia.php"
        : "actualizar_guardia.php";

    fetch(archivo, { method: "POST", body: datos })
    .then(r => r.text())
    .then(msg => {
        alert(msg);
        cerrarModal();
        cargarGuardias();
    });
});

// =======================
// Eliminar
// =======================
function eliminar(id) {
    if (!confirm("¿Eliminar este guardia?")) return;

    fetch("eliminar_guardia.php?id=" + id)
    .then(r => r.text())
    .then(msg => {
        alert(msg);
        cargarGuardias();
    });
}
