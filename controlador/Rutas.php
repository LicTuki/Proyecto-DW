<?php

class Rutas
{

  public function iniciarSesion()
  {
    require_once __DIR__ . '/../vista/iniciarSesion.php';
  }

  public function panelAdministracion()
  {
    require_once __DIR__ . '/../vista/panelAdministracion.php';
  }

  public function error404()
  {
    require_once __DIR__ . '/../vista/error404.php';
  }
}
