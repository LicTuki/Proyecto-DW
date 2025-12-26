<?php

require_once __DIR__ . '/../controlador/Rutas.php';
$Rutas = new Rutas();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');

switch ($uri) {
  case '':
  case '/':
    header('Location: /iniciar-sesion');
    exit();
    break;
  case '/iniciar-sesion':
    $Rutas->iniciarSesion();
    break;
  case '/panel-administracion':
    $Rutas->panelAdministracion();
    break;
  default:
    http_response_code(404);
    $Rutas->error404();
    break;
}
