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
/*
    function create() {

    }
*/
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
/*
    function delete($id) {
      $_query = 'DELETE FROM categories 
                 WHERE id="'.$id.'"';
      $category = $this->connection->execute($_query)->getResult();
      return $category;
    }
  */

    function update($categoria) {
      $_query = 'UPDATE categories SET 
                codigo = "'.$categoria['codigo'].'",
                nombre = "'.$categoria['nombre'].'",
                descripcion = "'.$categoria['descripcion'].'",
                imagen = "'.$categoria['imagen'].'" 
                WHERE id="'.$id.'"';
      $categoria = $this->connection->execute($_query);
    }
  }
?>