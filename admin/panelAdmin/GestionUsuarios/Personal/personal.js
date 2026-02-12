const tbody = document.getElementById("tabla-personal");
const modal = document.getElementById("modal");
const form = document.getElementById("form-personal");

// CARGAR PERSONAL
function cargarPersonal() {
    fetch("obtener_personal.php")
    .then(r => r.json())
    .then(data => {
        tbody.innerHTML = "";

        data.forEach(p => {
            const tr = document.createElement("tr");

            tr.innerHTML = `
                <td>${p.id_personal}</td>
                <td>${p.nombre} ${p.apellido}</td>
                <td>${p.curp}</td>
                <td>${p.telefono}</td>
                <td>${p.area}</td>
                <td>${p.turno}</td>
                <td>${p.placa || "—"}</td>

                <td>
                    <button onclick='editar(${JSON.stringify(p)})'>✏️</button>
                    <button onclick='eliminar("${p.id_personal}")'>🗑️</button>
                </td>
            `;

            tbody.appendChild(tr);
        });
    });
}

cargarPersonal();

function abrirModal() {
    modal.style.display = "block";
    form.reset();
    document.getElementById("modo").value = "nuevo";
    document.getElementById("id_personal").readOnly = false;
}

function cerrarModal() {
    modal.style.display = "none";
}

function editar(p) {
    abrirModal();

    document.getElementById("modo").value = "editar";
    document.getElementById("id_personal").readOnly = true;

    for (let campo in p) {
        if (document.getElementById(campo)) {
            document.getElementById(campo).value = p[campo];
        }
    }
}

form.addEventListener("submit", e => {
    e.preventDefault();

    const datos = new FormData(form);
    const archivo = (document.getElementById("modo").value === "nuevo")
        ? "insertar_personal.php"
        : "actualizar_personal.php";

    fetch(archivo, {
        method: "POST",
        body: datos
    })
    .then(r => r.text())
    .then(msg => {
        alert(msg);
        cerrarModal();
        cargarPersonal();
    });
});

function eliminar(id) {
    if (!confirm("¿Eliminar este personal?")) return;

    fetch("eliminar_personal.php?id=" + id)
    .then(r => r.text())
    .then(msg => {
        alert(msg);
        cargarPersonal();
    });
}
