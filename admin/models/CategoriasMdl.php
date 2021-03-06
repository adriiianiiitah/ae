<?php
  require_once('StandardMdl.php');
  require_once('DataBase.php');

  class CategoriasMdl extends StandardMdl {
    public $connection;
    public $_query;

    function __construct(){
      parent::__construct();
      $this->connection = DataBase::getInstance();
    }

    function getAll() {
      $_query = 'SELECT * FROM categorias';
      $categorias = $this->connection->execute($_query)->getResult();
      return $categorias;
    }

    function getOne($id) {
      $_query = 'SELECT * FROM categorias 
                 WHERE id="'.$id.'"';
      $categoria = $this->connection->execute($_query)->getFirst();
      return $categoria;
    }

    function delete($id) {
      $_query = 'DELETE FROM categorias 
                 WHERE id="'.$id.'"';
      $categoria = $this->connection->execute($_query)->getResult();
    }
  
    function insert($categoria) {
      $_query = 'INSERT INTO categorias (codigo,nombre,descripcion,imagen) 
                 VALUES (
                   "'.$categoria['codigo'].'",
                   "'.$categoria['nombre'].'",
                   "'.$categoria['descripcion'].'",
                   "'.$categoria['imagen'].'"
                   )';
      $this->connection->execute($_query);
      return $this->connection->returnId();
    }

    function update($categoria) {
      $_query = 'UPDATE categorias SET 
                codigo = "'.$categoria['codigo'].'",
                nombre = "'.$categoria['nombre'].'",
                descripcion = "'.$categoria['descripcion'].'",
                imagen = "'.$categoria['imagen'].'" 
                WHERE id="'.$categoria['id'].'"';
      $categoria = $this->connection->execute($_query);
    }

    function updateImage($id, $image) {
      $_query = 'UPDATE categorias SET 
                imagen = "'.$image.'" 
                WHERE id="'.$id.'"';
      $categoria = $this->connection->execute($_query);
    }
  }
?>