<?php
  require_once('StandardMdl.php');
  require_once('DataBase.php');

  class UsuariosMdl extends StandardMdl {
    
    function __construct(){
      parent::__construct();
    }

    function create() {
    }

    function getAll() {
      $_query = 'SELECT usuarios.id, usuarios.nombre, apellidos, correo, fecha_nacimiento, genero, imagen, roles.id AS rol_id, roles.nombre AS rol_nombre 
                FROM usuarios
                INNER JOIN roles
                ON roles.id = rol';
      $usuarios = $this->connection->execute($_query)->getResult();
      return $usuarios;
    }

    function getOne($id) {
      $_query = 'SELECT usuarios.id, usuarios.nombre, apellidos, correo, fecha_nacimiento, genero, contrasena, imagen, roles.id AS rol_id, roles.nombre AS rol_nombre 
                FROM usuarios 
                INNER JOIN roles
                ON roles.id = rol 
                WHERE usuarios.id="'.$id.'"';
      $usuario = $this->connection->execute($_query)->getFirst();
      return $usuario;
    }

    function getDomicilios($id) {
      $_query = 'SELECT paises.nombre AS pais_nombre, estados.nombre AS estado_nombre, municipios.nombre AS municipio_nombre, colonia, calle, exterior, interior, codigo_postal, primario 
                FROM domicilios
                INNER JOIN paises 
                ON paises.id = pais 
                INNER JOIN estados 
                ON estados.id = estado 
                INNER JOIN municipios 
                ON municipios.id = municipio
                WHERE usuario="'.$id.'"';
      $domicilios = $this->connection->execute($_query)->getResult();
      return $domicilios;
    }

    function getTelefonos($id) {
      $_query = 'SELECT * FROM telefonos 
                WHERE usuario="'.$id.'"';
      $telefonos = $this->connection->execute($_query)->getResult();
      return $telefonos;
    }
/*
    function delete($id) {
      $_query = 'DELETE FROM usuarios 
                 WHERE id="'.$id.'"';
      $usuario = $this->connection->execute($_query)->getResult();
      return $usuario;
    }

function update($color) {
      $_query = 'UPDATE colores SET 
                codigo = "'.$color['codigo'].'",
                nombre = "'.$color['nombre'].'",
                imagen = "'.$color['imagen'].'" 
                WHERE id="'.$color['id'].'"';
      $color = $this->connection->execute($_query);
    //return $color;
    }


*/
    function update($usuario) {
      $_query = 'UPDATE usuarios SET 
                nombre = "'.$usuario['nombre'].'",
                apellidos = "'.$usuario['apellidos'].'",
                correo = "'.$usuario['correo'].'",
                contrasena = "'.$usuario['contrasena'].'",
                fecha_nacimiento = "'.$usuario['fecha_nacimiento'].'",
                genero = "'.$usuario['genero'].'",
                rol = "'.$usuario['rol'].'",
                imagen = "'.$usuario['imagen'].'" 
                WHERE id="'.$usuario['id'].'"';
      $usuario = $this->connection->execute($_query);
    }
  }
?>
