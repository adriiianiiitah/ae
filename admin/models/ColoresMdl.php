<?php
  require_once('StandardMdl.php');
  require_once('DataBase.php');

  class ColoresMdl extends StandardMdl {
    public $connection;
    public $_query;

    function __construct(){
      parent::__construct();
      $this->connection = DataBase::getInstance();
    }
 
    function getAll() {
      $_query = 'SELECT * FROM colores';
      $colores = $this->connection->execute($_query)->getResult();
      return $colores;
    }

    function getOne($id) {
      $_query = 'SELECT * FROM colores 
                WHERE id="'.$id.'"';

      $color = $this->connection->execute($_query)->getFirst();
      return $color;
    }
/*
    function delete($id) {
    $_query = 'DELETE FROM colores 
              WHERE id="'.$id.'"';

    $color = $this->connection->execute($_query)->getResult();
    return $color;
    }*/

    function insert($color) {
      $_query = 'INSERT INTO colores (codigo,nombre,imagen) 
                 VALUES (
                   "'.$color['codigo'].'",
                   "'.$color['nombre'].'",
                   "'.$color['imagen'].'"
                   )';
      $this->connection->execute($_query);
      return $this->connection->returnId();
    }

    function update($color) {
      $_query = 'UPDATE colores SET 
                codigo = "'.$color['codigo'].'",
                nombre = "'.$color['nombre'].'",
                imagen = "'.$color['imagen'].'" 
                WHERE id="'.$color['id'].'"';
      $color = $this->connection->execute($_query);
    }

    function updateImage($id, $image) {
      $_query = 'UPDATE colores SET 
                imagen = "'.$image.'" 
                WHERE id="'.$id.'"';
      $color = $this->connection->execute($_query);
    }
  }
?>