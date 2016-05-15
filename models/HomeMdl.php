<?php
  require_once('StandardMdl.php');
  require_once('./admin/models/DataBase.php');

  class HomeMdl extends StandardMdl {
    public $connection;
    public $_query;

    function __construct(){
      parent::__construct();
      $this->connection = DataBase::getInstance();
    }

    function getAllDescuentos() {
      $_query = 'SELECT descuentos.id, descuentos.codigo, cantidad, productos.id AS producto_id, productos.nombre AS producto_nombre, descuento, fecha_inicio, fecha_fin, descuentos.precio, descuentos.imagen 
                FROM descuentos
                INNER JOIN productos
                ON productos.id = producto';
      $descuentos = $this->connection->execute($_query)->getResult();
      return $descuentos;
    }

    function getOneDescuento($id) {
      $_query = 'SELECT descuentos.id, descuentos.codigo, cantidad, productos.id AS producto_id, productos.nombre AS producto_nombre, descuento, fecha_inicio, fecha_fin, descuentos.precio, descuentos.imagen 
                FROM descuentos
                INNER JOIN productos
                ON productos.id = producto
                WHERE descuentos.id="'.$id.'"';
      $descuento = $this->connection->execute($_query)->getFirst();
      return $descuento;
    }

    function getAllOfertas() {
      $_query = 'SELECT ofertas.id, ofertas.codigo, cantidad, productos.id AS producto_id, productos.nombre AS producto_nombre, fecha_inicio, fecha_fin, ofertas.precio, ofertas.imagen 
                FROM ofertas
                INNER JOIN productos
                ON productos.id = producto';
      $ofertas = $this->connection->execute($_query)->getResult();
      return $ofertas;
    }

    function getAllCategorias() {
      $_query = 'SELECT * FROM categorias';
      $categorias = $this->connection->execute($_query)->getResult();
      return $categorias;
    }

    function getAllCatalogosByCathegory($categoria) {
      $_query = 'SELECT catalogos.id, catalogos.codigo, catalogos.nombre, fecha, categorias.id AS categoria_id, categorias.nombre AS categoria_nombre, pdf, catalogos.imagen 
                FROM catalogos
                INNER JOIN categorias
                ON categorias.id = categoria
                WHERE categorias.nombre = "'.$categoria.'"';
      $catalogos = $this->connection->execute($_query)->getResult();
      return $catalogos;
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




  }
?>