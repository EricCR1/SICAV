const cuerpo = document.getElementById('cuerpo-tabla-usuarios');

async function cargarEstacionamiento() {
  try {
    const res = await fetch('consultar_estacionamiento.php');
    const datos = await res.json();
    cuerpo.innerHTML = '';

    if (!Array.isArray(datos) || datos.length === 0) {
      cuerpo.innerHTML = `<tr><td colspan="7">No haaay registros</td></tr>`;
      return;
    }

    datos.forEach(r => {
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

  } catch (err) {
    console.error('Error al cargar registros:', err);
    cuerpo.innerHTML = `<tr><td colspan="7">Error al obtener datos</td></tr>`;
  }
}

// Carga inicial
cargarEstacionamiento();

// Actualiza automáticamente cada 5 segundos
setInterval(cargarEstacionamiento, 5000);
