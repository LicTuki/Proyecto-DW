<?php
class BaseDeDatos
{
  private function conectarDB() {}

  public function validarUsuario($nombreUsuario, $claveUsuario) {}

  // Opciones de lo servidores
  public function agregarServidor($nombreServidor, $ipServidor) {}

  public function editarServidor($idServidor, $nombreServidor, $ipServidor) {}

  public function eliminarServidor($idServidor) {}

  // Opciones de los estados de los servidores 
  public function servidorActivo($ipServidor, $tokenServidor) {}

  public function servidorApagar($ipServidor, $tokenServidor) {}
}
