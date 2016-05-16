<?php
  require_once('./admin/models/DataBase.php');

  class StandardMdl {
    public $connection;
    public $_query;

    function __construct() {
      $this->connection = DataBase::getInstance();
    }

    function getAllSubcategoriasByCathegory($categoria) {
      $_query = 'SELECT subcategorias.id, subcategorias.codigo, subcategorias.nombre, categorias.id AS categoria_id, categorias.nombre AS categoria_nombre, subcategorias.imagen, subcategorias.descripcion 
                FROM subcategorias 
                INNER JOIN categorias
                ON categorias.id = categoria
                WHERE categorias.nombre = "'.$categoria.'" 
                ORDER  BY subcategorias.nombre ASC';
      $subcategorias = $this->connection->execute($_query)->getResult();
      return $subcategorias;
    }

    function getAllColoresByCathegory($categoria) {
      $_query = 'SELECT colores.nombre as color_nombre, colores.imagen as color_imagen 
                FROM colores
                WHERE colores.id IN(
                                    SELECT color 
                                    FROM productos 
                                    WHERE categoria = (
                                                        SELECT id 
                                                        FROM categorias 
                                                        WHERE nombre = "'.$categoria.'"))'; 
      $colores = $this->connection->execute($_query)->getResult();
      return $colores;
    }

    
  }
?>