<?php

require_once __DIR__ . '/../controlador/Rutas.php';
$Rutas = new Rutas();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');

switch ($uri) {
  case '':
  case '/':
    header('Location: /iniciar-sesion');
    exit;
    break;
  case '/iniciar-sesion':
    $Rutas->iniciarSesion();
    break;
  case '/panel-monitoreo':
    $Rutas->panelMonitoreo();
    break;
  case '/cerrar-sesion':
    $Rutas->cerrarSesion();
    break;
  case '/obtener-servidores':
    $Rutas->obtenerServidores();
    break;
  case '/agregar-servidor':
    $Rutas->agregarServidor();
    break;
  case '/eliminar-servidor':
    $Rutas->eliminarServidor();
    break;
  case '/servidor-activo':
    $Rutas->servidorActivo();
    break;
  case '/servidor-apagado':
    $Rutas->servidorApagado();
    break;
  default:
    http_response_code(404);
    $Rutas->error404();
    break;
}
