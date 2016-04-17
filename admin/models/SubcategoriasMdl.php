<?php
  require_once('StandardMdl.php');
  require_once('DataBase.php');

  class SubcategoriasMdl extends StandardMdl {
    public $connection;
    public $_query;

    function __construct(){
      parent::__construct();
      $this->connection = DataBase::getInstance();
    }

    function create() {

    }

    function getAll() {
      $_query = 'SELECT subcategorias.id, subcategorias.codigo, subcategorias.nombre, categorias.id AS categoria_id, categorias.nombre AS categoria_nombre, subcategorias.imagen, subcategorias.descripcion 
                FROM subcategorias 
                INNER JOIN categorias
                ON categorias.id = categoria';
      $subcategorias = $this->connection->execute($_query)->getResult();
      return $subcategorias;
    }

    function getOne($id) {
      $_query = 'SELECT subcategorias.id, subcategorias.codigo, subcategorias.nombre, categorias.id AS categoria_id, categorias.nombre AS categoria_nombre, subcategorias.imagen, subcategorias.descripcion 
                FROM subcategorias 
                INNER JOIN categorias
                ON categorias.id = categoria 
                WHERE subcategorias.id="'.$id.'"';
      $subcategoria = $this->connection->execute($_query)->getFirst();
      return $subcategoria;
    }
/*
    function delete($id) {
      $_query = 'DELETE FROM categories 
                 WHERE id="'.$id.'"';
      $category = $this->connection->execute($_query)->getResult();
      return $category;
    }
    */

    function update($subcategoria) {
      $_query = 'UPDATE subcategorias SET 
                codigo = "'.$subcategoria['codigo'].'",
                nombre = "'.$subcategoria['nombre'].'",
                categoria = "'.$subcategoria['categoria'].'",
                descripcion = "'.$subcategoria['descripcion'].'",
                imagen = "'.$subcategoria['imagen'].'" 
                WHERE id="'.$subcategoria['id'].'"';
      $subcategoria = $this->connection->execute($_query);
    }
  }
?>