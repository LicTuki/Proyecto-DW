<?php

class BaseDeDatos
{
  private $ip = 'localhost';
  private $baseDeDatos = 'MonitoreoDB';
  private $usuario = 'UsuarioMonitoreo';
  private $clave = 'ClaveSeguraUsuario123';
  private $conexion = null;

  private function obtenerConexion(): PDO
  {
    if ($this->conexion === null) {
      try {
        $this->conexion = new PDO('mysql:host=' . $this->ip . ';dbname=' . $this->baseDeDatos, $this->usuario, $this->clave);
        $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $e) {
        throw $e;
      }
    }
    return $this->conexion;
  }

  // Iniciar sesión
  public function validarUsuario($inputNombre, $inputClave)
  {
    $SQL = 'SELECT ID, Nombre, Clave FROM Administradores WHERE BINARY Nombre = :Nombre LIMIT 1';
    $sentencia = $this->obtenerConexion()->prepare($SQL);
    $sentencia->execute([':Nombre' => $inputNombre]);

    if (($usuario = $sentencia->fetch(PDO::FETCH_ASSOC)) &&
      (hash_equals($usuario['Clave'], hash('sha256', $inputClave)))
    ) {
      return $usuario;
    }
    return false;
  }

  // Funciones CRUD de servidores
  public function obtenerServidores()
  {
    $SQL = 'UPDATE Servidores SET Estado = "Indeterminado" WHERE Estado = "Encendido" AND Fecha_ultimo_saludo < (NOW() - INTERVAL 5 SECOND)';
    $this->obtenerConexion()->exec($SQL);

    $SQL = 'SELECT * from Servidores';
    $sentencia = $this->obtenerConexion()->prepare($SQL);
    $sentencia->execute();

    if ($servidores = $sentencia->fetchAll(PDO::FETCH_ASSOC)) {
      return ['ok' => true, 'servidores' => $servidores];
    }
    return ['ok' => false, 'error' => 'No se encuentran servidores registrados.'];
  }

  public function agregarServidor($inputHostname, $inputIP, $idAdministrador)
  {
    $SQL = 'SELECT ID FROM Servidores WHERE IP = :IP LIMIT 1';
    $sentencia = $this->obtenerConexion()->prepare($SQL);
    $sentencia->execute([':IP' => $inputIP]);

    if ($sentencia->fetch(PDO::FETCH_ASSOC)) {
      return ['ok' => false, 'error' => 'Ya se encuentra registrada la dirección IP ' . $inputIP . '.'];
    }

    $token = bin2hex(random_bytes(64));
    $SQL = 'INSERT INTO Servidores (Hostname, IP, Token, ID_administrador) VALUES (:Hostname, :IP, :Token, :ID_administrador)';
    $sentencia = $this->obtenerConexion()->prepare($SQL);
    $sentencia->execute([':Hostname' => $inputHostname, ':IP' => $inputIP, ':Token' => $token, ':ID_administrador' => $idAdministrador]);
    return ['ok' => true];
  }

  public function eliminarServidor($idServidorBorrar)
  {
    $SQL = 'DELETE FROM Servidores WHERE ID = :ID';
    $sentencia = $this->obtenerConexion()->prepare($SQL);
    $sentencia->execute([':ID' => $idServidorBorrar]);
    if ($sentencia->rowCount() === 0) {
      return ['ok' => false, 'error' => 'Error inesperado al borrar un servidor.'];
    } else {
      return ['ok' => true];
    }
  }

  // Funciones clientes servidores
  public function servidorActivo($tokenServidor, $ipServidor)
  {
    $SQL = 'UPDATE Servidores SET Estado = "Encendido", Fecha_ultimo_saludo = NOW() WHERE Token = :Token AND IP = :IP';
    $sentencia = $this->obtenerConexion()->prepare($SQL);
    $sentencia->execute([':Token' => $tokenServidor, ':IP' => $ipServidor]);
    return ['ok' => true];
  }

  public function servidorApagado($tokenServidor, $ipServidor)
  {
    $SQL = 'UPDATE Servidores SET Estado = "Apagado", Fecha_ultimo_saludo = NOW() WHERE Token = :Token AND IP = :IP';
    $sentencia = $this->obtenerConexion()->prepare($SQL);
    $sentencia->execute([':Token' => $tokenServidor, ':IP' => $ipServidor]);
    return ['ok' => true];
  }
}
