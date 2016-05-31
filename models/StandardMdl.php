<?php
  require_once('./admin/models/DataBase.php');

  class StandardMdl {
    public $connection;
    public $_query;

    function __construct() {
      $this->connection = DataBase::getInstance();
    }

    function getAllSubcategoriasByCathegory($categoria = '') {
      $_query = 'SELECT subcategorias.id, subcategorias.codigo, subcategorias.nombre, categorias.id AS categoria_id, categorias.nombre AS categoria_nombre, subcategorias.imagen, subcategorias.descripcion 
                FROM subcategorias 
                INNER JOIN categorias
                ON categorias.id = categoria ';
      if($categoria !='') {
        $_query.='WHERE categorias.nombre = "'.$categoria.'" '; 
      }
      $_query.='ORDER  BY subcategorias.nombre ASC';
      $subcategorias = $this->connection->execute($_query)->getResult();
      return $subcategorias;
    }

    function getAllColoresByCathegory($categoria = '') {
      $_query = 'SELECT colores.nombre as color_nombre, colores.imagen as color_imagen 
                FROM colores
                WHERE colores.id IN(
                                    SELECT color 
                                    FROM productos 
                                    WHERE categoria IN (
                                                        SELECT id 
                                                        FROM categorias';
      if($categoria !='') {
          $_query.='                                    WHERE nombre = "'.$categoria.'" '; 
      }
          $_query.=                                     '))';  


      $colores = $this->connection->execute($_query)->getResult();
      return $colores;
    }

    function getProductoTalla($producto_id,$talla_id) {
      $_query = 'SELECT id FROM productos_tallas
                WHERE producto = "'.$producto_id.'"
                AND talla = "'.$talla_id.'" 
                LIMIT 1';
      $producto_talla = $this->connection->execute($_query)->getFirst();
      return $producto_talla;
    }


    function getProductoById($id) {
      $_query = 'SELECT productos.id, productos.codigo, productos.nombre, productos.descripcion, productos.imagen,
                categorias.id AS categoria_id, categorias.nombre AS categoria_nombre, 
                subcategorias.id AS subcategoria_id, subcategorias.nombre AS subcategoria_nombre, 
                colores.nombre AS color_nombre, colores.imagen AS color_imagen,
                modelo, material, marca, altura, precio, colores.id AS color_id 
                FROM productos
                INNER JOIN categorias
                ON categorias.id = categoria
                INNER JOIN subcategorias
                ON subcategorias.id = subcategoria  
                INNER JOIN colores
                ON colores.id = color
                WHERE productos.id="'.$id.'"';
      $producto = $this->connection->execute($_query)->getFirst();
      return $producto;
    }

    function getTallaById($id) {
      $_query = 'SELECT id AS talla_id, talla  
                 FROM tallas 
                 WHERE id="'.$id.'"';
      $talla = $this->connection->execute($_query)->getFirst();
      return $talla;
    }

    
  }
?>