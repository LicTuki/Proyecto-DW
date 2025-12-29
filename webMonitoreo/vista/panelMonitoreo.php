<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de monitoreo</title>
  <link rel="stylesheet" href="/recursos/css/panelMonitoreo.css">
</head>

<body id="ventanaPanelMonitoreo">
  <nav>
    <strong><?php echo htmlspecialchars($_SESSION['Usuario']['Nombre']) ?></strong>
    <div>
      <button id="abrirModalAyuda">Ayuda</button>
      <button id="abrirModalNuevoServidor">Agregar servidor</button>
      <button id="btnCerrarSesion">Cerrar sesión</button>
    </div>
  </nav>
  <main id="contenedorServidores"></main>
  <div id="modalNuevoServidor">
    <form id="formNuevoServidor">
      <header>
        <h2>Agregar servidor</h2>
        <button type="button" title="Cerrar" id="btnCerrarModalNuevoServidor">❌</button>
        <hr>
      </header>
      <div id="inputs">
        <input type="text" name="inputHostname" id="inputHostname" placeholder="Hostname" autocomplete="off" required>
        <input type="text" name="inputIP" id="inputIP" placeholder="Dirección IP" autocomplete="off" required>
      </div>
      <footer>
        <button type="submit">Agregar</button>
        <p id="msgErrorAgregarServidor">&nbsp</p>
      </footer>
    </form>
  </div>
  <div id="modalBorrarServidor">
    <form id="formBorrarServidor">
      <input type="hidden" name="idServidorBorrar" id="idServidorBorrar">
      <header>
        <h3>Eliminar servidor</h3>
        <button type="button" id="btnCerrarModalBorrarServidor" title="Cerrar">❌</button>
        <hr>
        <p>¿Está seguro de eliminar este servidor?</p>
      </header>
      <footer>
        <button type="submit">Sí</button>
        <p id="msgErrorEliminarServidor">&nbsp</p>
      </footer>
    </form>
  </div>
  <div id="modalAyuda">
    <div id="contenedorInformaciónAyuda">
      <header>
        <h1>¿Como monitoreo un servidor?</h1>
        <button id="btnCerrarModalAyuda" title="cerrar">❌</button>
        <hr>
      </header>
      <div>
        <div>
          <p>
            Selecciona el botón “Agregar servidor”, donde deberás ingresar el hostname y la dirección IP del servidor a monitorear. Posterior a ello, se generará un token, el cual debe ser utilizado para identificar el servidor.
            Es importante que, con permisos de administrador, en el servidor a monitorear se realicen los siguientes scripts, llenando los campos de la variable token y la IP del servidor central, y asignando los permisos de ejecución correspondientes.
          </p> <br>
          <p class="tituloArchivo">/usr/local/bin/servidorActivo.sh</p>
          <div class="codigo">
            #!/bin/bash <br><br>

            TOKEN_CLIENTE="" <br>
            IP_SERVIDOR_CENTRAL="" <br>
            WEBHOOK="https://$IP_SERVIDOR_CENTRAL/servidor-activo" <br><br>

            while true; do <br>
            curl -k -s -X POST "$WEBHOOK" \ <br>
            -H "Content-Type: application/json" \ <br>
            -d "{\"tokenServidor\":\"$TOKEN_CLIENTE\"}" <br><br>

            sleep 1 <br>
            done
          </div> <br>
          <p class="tituloArchivo">/usr/local/bin/servidorApagado.sh</p>
          <div class="codigo">
            #!/bin/bash <br><br>

            systemctl stop servidorActivo.service <br>
            sleep 2 <br><br>

            TOKEN_CLIENTE="" <br>
            IP_SERVIDOR_CENTRAL="" <br>
            WEBHOOK="https://$IP_SERVIDOR_CENTRAL/servidor-apagado" <br><br>

            curl -k -s -X POST "$WEBHOOK" \ <br>
            -H "Content-Type: application/json" \ <br>
            -d "{\"tokenServidor\":\"$TOKEN_CLIENTE\"}" <br><br>

            sleep 1 <br>
            poweroff
          </div><br>
          <p>
            Posterior a la creación y asignación de permisos de los scripts, se debe crear el archivo para habilitar el keep-alive como servicio. De igual manera, este debe ser editado y configurado con permisos de administrador.
          </p> <br>
          <p class="tituloArchivo">/etc/systemd/system/servidorActivo.service</p>
          <div class="codigo">
            [Unit] <br>
            Description=Servicio para mandar señales de servidor encendido <br>
            After=network.target <br> <br>

            [Service] <br>
            Type=simple <br>
            User=root <br>
            ExecStart=/usr/local/bin/servidorActivo.sh <br>
            Restart=always <br>
            RestartSec=3 <br>
            StandardOutput=journal <br>
            StandardError=journal <br><br>

            [Install] <br>
            WantedBy=multi-user.target <br>
          </div> <br>
          <p>Una vez creado el script como servicio, es necesario habilitarlo para que funcione correctamente.</p>
          <div class="codigo">
            sudo systemctl daemon-reload <br>
            sudo systemctl enable servidorActivo.service <br>
            sudo systemctl start servidorActivo.service <br>
          </div> <br>
          <p>Para apagar el equipo de manera correcta y que se envíe el aviso al panel de monitoreo, se debe ejecutar el script de apagado del equipo.</p>
          <div class="codigo">
            sudo /usr/local/bin/./servidorApagado.sh
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="/recursos/js/panelMonitoreo.js"></script>
</body>

</html>