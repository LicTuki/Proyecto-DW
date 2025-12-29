<?php

class Utilerias
{
  // Responder en formato JSON
  public function responderJSON($datos)
  {
    header('Content-Type: application/json');
    echo json_encode($datos);
    exit;
  }

  // Validar inputs provenientes del frontend
  public function validarInputsIniciarSesion($inputNombre, $inputClave)
  {
    if ($inputNombre === '' || $inputClave === '') {
      $this->responderJSON(['ok' => false, 'error' => 'Todos los campos son obligatorios.']);
    }
  }

  public function validarInputsAgregarServidor($inputHostname, $inputIP)
  {
    if ($inputHostname === '' || $inputIP === '') {
      $this->responderJSON(['ok' => false, 'error' => 'Todos los campos son obligatorios.']);
    }

    if (!filter_var($inputIP, FILTER_VALIDATE_IP)) {
      $this->responderJSON(['ok' => false, 'error' => 'La dirección IP es invalida.']);
    }
  }

  // Manejo de sesiones
  public function crearSesion($idUsuario, $nombreUsuario)
  {
    $_SESSION['Usuario'] = [
      'ID' => $idUsuario,
      'Nombre' => $nombreUsuario,
      'Inicio_sesion' => time(),
      'Expiracion' => time() + (60 * 30)
    ];
  }

  public function verificarSesion()
  {
    if (!isset($_SESSION['Usuario'])) {
      header('Location: /iniciar-sesion');
      exit;
    }

    if (time() > $_SESSION['Usuario']['Expiracion']) {
      $this->cerrarSesion();
    }
  }

  public function cerrarSesion()
  {
    session_unset();
    session_destroy();
    header('Location: /iniciar-sesion');
    exit;
  }
}
