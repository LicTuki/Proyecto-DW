document.getElementById('btnCerrarSesion').addEventListener('click', () => {
  window.location.href = '/cerrar-sesion';
})

document.getElementById('abrirModalNuevoServidor').addEventListener('click', () => {
  document.getElementById('modalNuevoServidor').style.display = 'flex';
})

document.getElementById('btnCerrarModalNuevoServidor').addEventListener('click', () => {
  document.getElementById('modalNuevoServidor').style.display = 'none';
  document.getElementById('formNuevoServidor').reset();
  document.getElementById('msgErrorAgregarServidor').innerHTML = '&nbsp';
})

document.getElementById('inputIP').addEventListener('input', () => {
  inputIP.value = inputIP.value.replace(/[^0-9.]/g, '');
})

document.getElementById('formNuevoServidor').addEventListener('submit', (e) => {
  e.preventDefault()

  const formData = new FormData(e.target);
  fetch('/agregar-servidor', {
    method: 'POST',
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      if (data.ok) {
        obtenerServidores();
        document.getElementById('formNuevoServidor').reset();
        document.getElementById('msgErrorAgregarServidor').innerHTML = '&nbsp';
      } else {
        document.getElementById('msgErrorAgregarServidor').innerText = data.error;
      }
    })
    .catch(err => console.log(err));
})

document.addEventListener('DOMContentLoaded', () => {
  obtenerServidores();
  setInterval(obtenerServidores, 2500);
});

function obtenerServidores() {
  fetch('/obtener-servidores')
    .then(res => res.json())
    .then(data => {
      if (data.ok) {
        renderizarServidores(data.servidores);
      } else {
        document.querySelector('main').innerHTML =
          `<p>${data.error}</p>`;
      }
    })
    .catch(err => console.error(err));
}

function renderizarServidores(servidores) {
  const contenedor = document.getElementById('contenedorServidores');
  contenedor.innerHTML = '';

  servidores.forEach(servidor => {
    const div = document.createElement('div');
    div.classList.add('itemServidor');

    div.innerHTML = `
      <h3>${servidor.Hostname}</h3>
      <hr>
      <p>🛜 IP: ${servidor.IP}</p>
      <p>⚡Estado: <span class="estado ${servidor.Estado}">${servidor.Estado}</span></p>
      <p>🎫 Token</p>
      <div class="contenedorToken">
        <p>${servidor.Token}</p>
      </div>
      <button class="btnEliminarServidor" data-id="${servidor.ID}">Eliminar servidor</button>
    `;
    contenedor.appendChild(div);
  });
}

document.getElementById('contenedorServidores').addEventListener('click', (e) => {
  if (e.target.classList.contains('btnEliminarServidor')) {
    const idServidor = e.target.dataset.id;
    document.getElementById('idServidorBorrar').value = idServidor;
    document.getElementById('modalBorrarServidor').style.display = 'flex';
  }
});

document.getElementById('btnCerrarModalBorrarServidor').addEventListener('click', () => {
  document.getElementById('modalBorrarServidor').style.display = 'none';
  document.getElementById('idServidorBorrar').value = '';
  document.getElementById('msgErrorEliminarServidor').innerHTML = '&nbsp';
})

document.getElementById('formBorrarServidor').addEventListener('submit', (e) => {
  e.preventDefault()

  const formData = new FormData(e.target);
  fetch('/eliminar-servidor', {
    method: 'POST',
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      if (data.ok) {
        obtenerServidores();
        document.getElementById('modalBorrarServidor').style.display = 'none';
      } else {
        document.getElementById('msgErrorEliminarServidor').innerText = data.error;
      }
    })
    .catch(err => console.log(err));
})

document.getElementById('abrirModalAyuda').addEventListener('click', () => {
  document.getElementById('modalAyuda').style.display = 'flex';
})

document.getElementById('btnCerrarModalAyuda').addEventListener('click', () => {
  document.getElementById('modalAyuda').style.display = 'none';
})