<?php
  require_once('StandardMdl.php');
  require_once('DataBase.php');

  class OfertasMdl extends StandardMdl {
    public $connection;
    public $_query;

    function __construct(){
      parent::__construct();
      $this->connection = DataBase::getInstance();
    }

    function getAll() {
      $_query = 'SELECT ofertas.id, ofertas.codigo, cantidad, productos.id AS producto_id, productos.nombre AS producto_nombre, fecha_inicio, fecha_fin, ofertas.precio, ofertas.imagen 
                FROM ofertas
                INNER JOIN productos
                ON productos.id = producto';
      $ofertas = $this->connection->execute($_query)->getResult();
      return $ofertas;
    }

    function getOne($id) {
      $_query = 'SELECT ofertas.id, ofertas.codigo, cantidad, productos.id AS producto_id, productos.nombre AS producto_nombre, fecha_inicio, fecha_fin, ofertas.precio, ofertas.imagen 
                FROM ofertas
                INNER JOIN productos
                ON productos.id = producto
                WHERE ofertas.id="'.$id.'"';
      $oferta = $this->connection->execute($_query)->getFirst();
      return $oferta;
    }

    function getAllProductos() {
      $_query = 'SELECT id AS producto_id, nombre AS producto_nombre
                FROM productos';
      $productos = $this->connection->execute($_query)->getResult();
      return $productos;
    }

    function update($oferta) {
      $_query = 'UPDATE ofertas SET 
                codigo = "'.$oferta['codigo'].'",
                cantidad = "'.$oferta['cantidad'].'",
                producto = "'.$oferta['producto'].'",
                fecha_inicio = "'.$oferta['fecha_inicio'].'",
                fecha_fin = "'.$oferta['fecha_fin'].'",
                precio = "'.$oferta['precio'].'",
                imagen = "'.$oferta['imagen'].'" 
                WHERE id="'.$oferta['id'].'"';
      $oferta = $this->connection->execute($_query);
    //return $oferta;
    }




  }
?>