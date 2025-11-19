const tbody = document.getElementById("tabla-alumnos");
const modal = document.getElementById("modal");
const form = document.getElementById("form-alumno");
const vehContainer = document.getElementById("vehiculos-container");

function cargarAlumnos() {
    fetch("obtener_alumnos.php")
        .then(r => r.json())
        .then(data => {
            tbody.innerHTML = "";

            data.forEach(a => {
                const tr = document.createElement("tr");

                const placas = a.vehiculos.length > 0 
                    ? a.vehiculos.map(v => v.placa).join(", ")
                    : "—";

                tr.innerHTML = `
                    <td>${a.no_control}</td>
                    <td>${a.nombre} ${a.apellido}</td>
                    <td>${a.correo}</td>
                    <td>${a.telefono}</td>
                    <td>${placas}</td>
                    <td>
                        <button onclick='editar(${JSON.stringify(a)})'>✏️</button>
                        <button onclick='eliminar("${a.no_control}")'>🗑️</button>
                    </td>
                `;

                tbody.appendChild(tr);
            });
        });
}

cargarAlumnos();

function agregarVehiculo(v = null) {
    const div = document.createElement("div");
    div.classList.add("vehiculo-box");

    div.innerHTML = `
        <label>RFID</label>
        <input type="text" name="rfid[]" value="${v?.rfid ?? ""}">
        <label>Tipo</label>
        <input type="text" name="tipo_vehiculo[]" value="${v?.tipo_vehiculo ?? ""}">
        <label>Marca</label>
        <input type="text" name="marca[]" value="${v?.marca ?? ""}">
        <label>Modelo</label>
        <input type="text" name="modelo[]" value="${v?.modelo ?? ""}">
        <label>Año</label>
        <input type="text" name="año[]" value="${v?.año ?? ""}">
        <label>Color</label>
        <input type="text" name="color[]" value="${v?.color ?? ""}">
        <label>Placa</label>
        <input type="text" name="placa[]" value="${v?.placa ?? ""}">
        <button type="button" class="btn cancelar" onclick="this.parentNode.remove()">Eliminar</button>
        <hr>
    `;

    vehContainer.appendChild(div);
}

function abrirModal() {
    modal.style.display = "block";
    form.reset();
    vehContainer.innerHTML = "";
    document.getElementById("modo").value = "nuevo";
}

function cerrarModal() {
    modal.style.display = "none";
}

function editar(a) {
    abrirModal();
    document.getElementById("modo").value = "editar";

    for (let campo in a) {
        if (document.getElementById(campo)) {
            document.getElementById(campo).value = a[campo];
        }
    }

    vehContainer.innerHTML = "";
    a.vehiculos.forEach(v => agregarVehiculo(v));
}

form.addEventListener("submit", e => {
    e.preventDefault();

    let archivo = (document.getElementById("modo").value === "nuevo")
        ? "insertar_alumno.php"
        : "actualizar_alumno.php";

    fetch(archivo, {
        method: "POST",
        body: new FormData(form)
    })
        .then(r => r.text())
        .then(msg => {
            alert(msg);
            cerrarModal();
            cargarAlumnos();
        });
});

function eliminar(id) {
    if (!confirm("¿Eliminar este alumno?")) return;

    fetch("eliminar_alumno.php?id=" + id)
        .then(r => r.text())
        .then(msg => {
            alert(msg);
            cargarAlumnos();
        });
}
