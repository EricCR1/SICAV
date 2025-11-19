const tbody = document.getElementById("tabla-proveedores");
const modal = document.getElementById("modal");
const form = document.getElementById("form-proveedor");

function cargarProveedores() {
    fetch("obtener_proveedores.php")
    .then(r => r.json())
    .then(data => {
        tbody.innerHTML = "";
        data.forEach(p => {
            const tr = document.createElement("tr");

            tr.innerHTML = `
                <td>${p.id_proveedor}</td>
                <td>${p.nombre} ${p.apellido}</td>
                <td>${p.empresa}</td>
                <td>${p.telefono}</td>
                <td>${p.tipo_servicio}</td>
                <td>${p.placa || "—"}</td>
                <td>
                    <button onclick='editar(${JSON.stringify(p)})'>✏️</button>
                    <button onclick='eliminar("${p.id_proveedor}")'>🗑️</button>
                </td>
            `;

            tbody.appendChild(tr);
        });
    });
}

cargarProveedores();

function abrirModal() {
    modal.style.display = "block";
    form.reset();
    document.getElementById("modo").value = "nuevo";
    document.getElementById("id_proveedor").readOnly = false;
}

function cerrarModal() {
    modal.style.display = "none";
}

function editar(p) {
    abrirModal();
    document.getElementById("modo").value = "editar";
    document.getElementById("id_proveedor").readOnly = true;

    for (let campo in p) {
        if (document.getElementById(campo)) {
            document.getElementById(campo).value = p[campo];
        }
    }
}

form.addEventListener("submit", e => {
    e.preventDefault();

    const datos = new FormData(form);
    const modo = document.getElementById("modo").value;

    const archivo = modo === "nuevo"
        ? "insertar_proveedor.php"
        : "actualizar_proveedor.php";

    fetch(archivo, {
        method: "POST",
        body: datos
    })
    .then(r => r.text())
    .then(msg => {
        alert(msg);
        cerrarModal();
        cargarProveedores();
    });
});

function eliminar(id) {
    if (!confirm("¿Eliminar proveedor?")) return;

    fetch("eliminar_proveedor.php?id=" + id)
    .then(r => r.text())
    .then(msg => {
        alert(msg);
        cargarProveedores();
    });
}
