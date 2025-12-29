<?php

session_start([
  'cookie_lifetime' => 0,
  'cookie_httponly' => true,
  'cookie_secure'   => true,
  'cookie_samesite' => 'Lax'
]);

include_once __DIR__ . '/Utilerias.php';
include_once __DIR__ . '/../modelo/BaseDeDatos.php';

class Rutas
{
  private Utilerias $utilerias;
  private BaseDeDatos $baseDeDatos;

  public function __construct()
  {
    $this->utilerias = new Utilerias();
    $this->baseDeDatos = new BaseDeDatos();
  }

  // Renderizado de paginas
  public function iniciarSesion()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $inputNombre = trim($_POST['inputNombre'] ?? '');
      $inputClave = trim($_POST['inputClave'] ?? '');
      $this->utilerias->validarInputsIniciarSesion($inputNombre, $inputClave);
      $respuestaDB = $this->baseDeDatos->validarUsuario($inputNombre, $inputClave);
      if ($respuestaDB) {
        $this->utilerias->crearSesion($respuestaDB['ID'], $respuestaDB['Nombre']);
        $this->utilerias->responderJSON(['ok' => true, 'redireccion' => '/panel-monitoreo']);
      } else {
        $this->utilerias->responderJSON(['ok' => false, 'error' => 'Nombre de usuario o contraseña incorrectos.']);
      }
    }
    require_once __DIR__ . '/../vista/iniciarSesion.php';
  }

  public function panelMonitoreo()
  {
    $this->utilerias->verificarSesion();
    require_once __DIR__ . '/../vista/panelMonitoreo.php';
  }

  public function error404()
  {
    require_once __DIR__ . '/../vista/error404.php';
  }

  // CRUD Servidores
  public function obtenerServidores()
  {
    $this->utilerias->verificarSesion();
    $respuestaDB = $this->baseDeDatos->obtenerServidores();
    $this->utilerias->responderJSON($respuestaDB);
  }

  public function agregarServidor()
  {
    $this->utilerias->verificarSesion();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $inputHostname = trim($_POST['inputHostname'] ?? '');
      $inputIP = trim($_POST['inputIP'] ?? '');
      $this->utilerias->validarInputsAgregarServidor($inputHostname, $inputIP);
      $respuestaDB = $this->baseDeDatos->agregarServidor($inputHostname, $inputIP, $_SESSION['Usuario']['ID']);
      $this->utilerias->responderJSON($respuestaDB);
    }
    $this->utilerias->responderJSON(['ok' => false, 'error' => 'este es un error simulado']);
  }

  public function eliminarServidor()
  {
    $this->utilerias->verificarSesion();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $idServidorBorrar = trim($_POST['idServidorBorrar'] ?? '');
      $respuestaDB = $this->baseDeDatos->eliminarServidor($idServidorBorrar);
      $this->utilerias->responderJSON($respuestaDB);
    }
  }

  // Clientes servidores
  public function servidorActivo()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $datos = json_decode(file_get_contents('php://input'), true);
      $tokenServidor = trim($datos['tokenServidor'] ?? '');
      $ipCliente = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
      $respuestaDB = $this->baseDeDatos->servidorActivo($tokenServidor, $ipCliente);
      $this->utilerias->responderJSON($respuestaDB);
    }
  }

  public function servidorApagado()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $datos = json_decode(file_get_contents('php://input'), true);
      $tokenServidor = trim($datos['tokenServidor'] ?? '');
      $ipCliente = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
      $respuestaDB = $this->baseDeDatos->servidorApagado($tokenServidor, $ipCliente);
      $this->utilerias->responderJSON($respuestaDB);
    }
  }

  // Cerrar sesión
  public function cerrarSesion()
  {
    $this->utilerias->verificarSesion();
    $this->utilerias->cerrarSesion();
  }
}
