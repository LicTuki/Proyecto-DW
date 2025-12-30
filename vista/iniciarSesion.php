<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar sesión</title>
  <link rel="stylesheet" href="/recursos/css/iniciarSesion.css">
</head>

<body id="ventanaIniciarSesion">
  <div id="panelIzquierdo">
    <img src="/recursos/imagenes/candadoMorado.jpg" alt="Candado morado cibernético.">
  </div>
  <div id="panelDerecho">
    <form id="formIniciarSesion">
      <header>
        <h1>Iniciar sesión</h1>
        <hr>
        <p>Ingresa tus credenciales para acceder al panel de monitoreo de servidores.</p>
      </header>
      <div id="inputs">
        <input type="text" name="inputNombre" id="inputNombre" placeholder="Nombre de usuario" autocomplete="off" required>
        <input type="password" name="inputClave" id="inputClave" placeholder="Contraseña" autocomplete="off" required>
      </div>
      <footer>
        <button type="submit">Acceder</button>
        <p id="msgErrorIniciarSesion">&nbsp</p>
      </footer>
    </form>
  </div>
  <script src="/recursos/js/iniciarSesion.js"></script>
</body>

</html>