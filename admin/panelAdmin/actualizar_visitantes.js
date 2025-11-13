const cuerpo = document.getElementById('cuerpo-tabla-visitantes');

async function cargarVisitantes() {
  try {
    const res = await fetch('consultar_visitantes.php');
    const datos = await res.json();
    cuerpo.innerHTML = '';

    if (!Array.isArray(datos) || datos.length === 0) {
      cuerpo.innerHTML = `<tr><td colspan="5" class="empty-state">No hay registros</td></tr>`;
      return;
    }

    datos.forEach(r => {
      const fila = document.createElement('tr');
      fila.innerHTML = `
        <td>${r.numero_asignado || ''}</td>
        <td>${r.nombre || ''}</td>
        <td>${r.motivo || ''}</td>
        <td>${r.fecha_hora || ''}</td>
        <td>${r.accion == '1' ? 'Salida' : 'Entrada'}</td>
      `;
      cuerpo.appendChild(fila);
    });

  } catch (err) {
    console.error('Error al cargar visitantes:', err);
    cuerpo.innerHTML = `<tr><td colspan="5" class="empty-state">Error al obtener datos</td></tr>`;
  }
}

// Carga inicial
cargarVisitantes();

// Actualiza automáticamente cada 1 segundos
setInterval(cargarVisitantes, 1000);
