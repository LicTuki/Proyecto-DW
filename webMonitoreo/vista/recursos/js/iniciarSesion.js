document.getElementById('formIniciarSesion').addEventListener('submit', (e) => {
  e.preventDefault()

  const formData = new FormData(e.target);

  fetch('/iniciar-sesion', {
    method: 'POST',
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      if (data.ok) {
        window.location.href = data.redireccion;
      } else {
        document.getElementById('msgErrorIniciarSesion').innerText = data.error;
      }
    })
    .catch(err => console.log(err));
})