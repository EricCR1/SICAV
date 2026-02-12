// administrativos.js
const tbody = document.getElementById('tbody-administrativos');
const modal = document.getElementById('modal');
const form = document.getElementById('form-admin');
const vehiculosList = document.getElementById('vehiculos-list');
const btnAddVehicle = document.getElementById('btn-add-vehicle');

let admins = []; // cache

// Helper: create a vehicle row DOM
function createVehicleRow(vehicle = {}) {
  const wrapper = document.createElement('div');
  wrapper.className = 'veh-row';

  wrapper.innerHTML = `
    <input name="rfid[]" placeholder="RFID" value="${vehicle.rfid || ''}">
    <input name="tipo_vehiculo[]" placeholder="Tipo" value="${vehicle.tipo_vehiculo || ''}">
    <input name="marca[]" placeholder="Marca" value="${vehicle.marca || ''}">
    <input name="modelo[]" placeholder="Modelo" value="${vehicle.modelo || ''}">
    <input name="año[]" placeholder="Año" value="${vehicle.año || ''}">
    <input name="color[]" placeholder="Color" value="${vehicle.color || ''}">
    <input name="placa[]" placeholder="Placa" value="${vehicle.placa || ''}">
    <button type="button" class="veh-remove">✖</button>
  `;

  // remove button
  wrapper.querySelector('.veh-remove').addEventListener('click', () => wrapper.remove());
  return wrapper;
}

// add vehicle row
btnAddVehicle.addEventListener('click', () => {
  vehiculosList.appendChild(createVehicleRow());
});

// render table
async function cargarAdministrativos() {
  try {
    const res = await fetch('obtener_administrativos.php');
    const data = await res.json();
    admins = data;
    tbody.innerHTML = '';

    if (!Array.isArray(data) || data.length === 0) {
      tbody.innerHTML = '<tr><td colspan="8" class="empty">No hay administrativos</td></tr>';
      return;
    }

    data.forEach(a => {
      const tr = document.createElement('tr');
      const vehCount = Array.isArray(a.vehiculos) ? a.vehiculos.length : 0;
      const vehText = vehCount > 0 ? a.vehiculos.map(v => v.placa || v.rfid || '—').join(', ') : '—';

      tr.innerHTML = `
        <td>${a.id_administrativo}</td>
        <td>${(a.nombre||'') + ' ' + (a.apellido||'')}</td>
        <td>${a.correo || '—'}</td>
        <td>${a.telefono || '—'}</td>
        <td>${a.area || '—'}</td>
        <td>${a.estado || '—'}</td>
        <td>${vehText}</td>
        <td>
          <button class="btn btn-small" data-id="${a.id_administrativo}" data-action="edit">✏️</button>
          <button class="btn btn-small" data-id="${a.id_administrativo}" data-action="del">🗑️</button>
        </td>
      `;
      tbody.appendChild(tr);
    });

    // delegate actions
    tbody.querySelectorAll('button[data-action="edit"]').forEach(b => b.addEventListener('click', e => {
      const id = e.currentTarget.dataset.id;
      abrirModal('editar', admins.find(x => x.id_administrativo == id));
    }));
    tbody.querySelectorAll('button[data-action="del"]').forEach(b => b.addEventListener('click', async e => {
      const id = e.currentTarget.dataset.id;
      if (!confirm('¿Eliminar este administrativo?')) return;
      const res = await fetch('eliminar_administrativo.php?id=' + encodeURIComponent(id));
      const txt = await res.text();
      alert(txt);
      cargarAdministrativos();
    }));

  } catch (err) {
    console.error(err);
    tbody.innerHTML = '<tr><td colspan="8" class="empty">Error cargando datos</td></tr>';
  }
}

// open modal (modo: 'nuevo' or 'editar')
function abrirModal(modo = 'nuevo', admin = null) {
  form.reset();
  vehiculosList.innerHTML = '';
  document.getElementById('modo').value = modo;

  if (modo === 'nuevo') {
    document.getElementById('modal-title').innerText = 'Nuevo Administrativo';
    document.getElementById('id_administrativo').readOnly = false;
    // add one empty vehicle row by default
    vehiculosList.appendChild(createVehicleRow());
  } else if (admin) {
    document.getElementById('modal-title').innerText = 'Editar Administrativo';
    document.getElementById('id_administrativo').value = admin.id_administrativo;
    document.getElementById('id_administrativo').readOnly = true;
    document.getElementById('nombre').value = admin.nombre || '';
    document.getElementById('apellido').value = admin.apellido || '';
    document.getElementById('rfc').value = admin.rfc || '';
    document.getElementById('f_nacimiento').value = admin.f_nacimiento ? admin.f_nacimiento.substring(0,10) : '';
    document.getElementById('correo').value = admin.correo || '';
    document.getElementById('telefono').value = admin.telefono || '';
    document.getElementById('area').value = admin.area || '';
    document.getElementById('estado').value = admin.estado || 'activo';

    // vehicles
    (admin.vehiculos || []).forEach(v => vehiculosList.appendChild(createVehicleRow(v)));
  }

  modal.style.display = 'flex';
  modal.setAttribute('aria-hidden', 'false');
}

// close modal
document.getElementById('btn-cancel').addEventListener('click', () => {
  modal.style.display = 'none';
  modal.setAttribute('aria-hidden', 'true');
});

// on new button
document.getElementById('btn-nuevo').addEventListener('click', () => abrirModal('nuevo'));

// submit form
form.addEventListener('submit', async (e) => {
  e.preventDefault();
  const modo = document.getElementById('modo').value;
  const fd = new FormData(form);

  // collect vehicle rows into JSON and add to FormData as 'vehicles'
  const rows = Array.from(vehiculosList.querySelectorAll('.veh-row'));
  const vehicles = rows.map(row => {
    const fields = row.querySelectorAll('input');
    return {
      rfid: fields[0].value.trim(),
      tipo_vehiculo: fields[1].value.trim(),
      marca: fields[2].value.trim(),
      modelo: fields[3].value.trim(),
      año: fields[4].value.trim(),
      color: fields[5].value.trim(),
      placa: fields[6].value.trim()
    };
  }).filter(v => v.rfid || v.placa || v.marca || v.modelo); // skip empty rows

  fd.append('vehicles', JSON.stringify(vehicles));

  const endpoint = (modo === 'nuevo') ? 'insertar_administrativo.php' : 'actualizar_administrativo.php';
  try {
    const res = await fetch(endpoint, { method: 'POST', body: fd });
    const txt = await res.text();
    alert(txt);
    modal.style.display = 'none';
    cargarAdministrativos();
  } catch (err) {
    console.error(err);
    alert('Error guardando administrativo');
  }
});

// initial load
cargarAdministrativos();
