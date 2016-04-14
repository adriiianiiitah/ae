<?php
  require_once('StandardMdl.php');
  require_once('DataBase.php');

  class CatalogosMdl extends StandardMdl {
    public $connection;
    public $_query;

    function __construct(){
      parent::__construct();
      $this->connection = DataBase::getInstance();
    }

    function create() {

    }

    function getAll() {
      $_query = 'SELECT * FROM catalogos';
      $catalogos = $this->connection->execute($_query)->getResult();
      return $catalogos;
    }

    function getOne($id) {
      $_query = 'SELECT * FROM catalogos 
                 WHERE id="'.$id.'"';
      $catalogo = $this->connection->execute($_query)->getFirst();
      return $catalogo;
    }

    function delete($id) {
      $_query = 'DELETE FROM catalogos 
                 WHERE id="'.$id.'"';
      $category = $this->connection->execute($_query)->getResult();
      return $category;
    }

    function update($catalogo) {
      $_query = 'UPDATE catalogos SET 
                codigo = "'.$catalogo['codigo'].'",
                nombre = "'.$catalogo['nombre'].'",
                fecha = "'.$catalogo['fecha'].'",
                imagen = "'.$catalogo['imagen'].'",
                categoria = "'.$catalogo['categoria'].'" 
                WHERE id="'.$catalogo['id'].'"';
      $category = $this->connection->execute($_query);
      //return $category;
    }
  }
?>