<?php
  require_once('StandardMdl.php');
  require_once('DataBase.php');

  class DescuentosMdl extends StandardMdl {
    public $connection;
    public $_query;

    function __construct(){
      parent::__construct();
      $this->connection = DataBase::getInstance();
    }

    function getAll() {
      $_query = 'SELECT descuentos.id, descuentos.codigo, cantidad, productos.id AS producto_id, productos.nombre AS producto_nombre, descuento, fecha_inicio, fecha_fin, descuentos.precio, descuentos.imagen 
                FROM descuentos
                INNER JOIN productos
                ON productos.id = producto';
      $descuentos = $this->connection->execute($_query)->getResult();
      return $descuentos;
    }

    function getOne($id) {
      $_query = 'SELECT descuentos.id, descuentos.codigo, cantidad, productos.id AS producto_id, productos.nombre AS producto_nombre, descuento, fecha_inicio, fecha_fin, descuentos.precio, descuentos.imagen 
                FROM descuentos
                INNER JOIN productos
                ON productos.id = producto
                WHERE descuentos.id="'.$id.'"';
      $descuento = $this->connection->execute($_query)->getFirst();
      return $descuento;
    }

    function getAllProductos() {
      $_query = 'SELECT id AS producto_id, nombre AS producto_nombre
                FROM productos';
      $productos = $this->connection->execute($_query)->getResult();
      return $productos;
    }


    function update($descuento) {
      $_query = 'UPDATE descuentos SET 
                codigo = "'.$descuento['codigo'].'",
                cantidad = "'.$descuento['cantidad'].'",
                producto = "'.$descuento['producto'].'",
                descuento = "'.$descuento['descuento'].'",
                fecha_inicio = "'.$descuento['fecha_inicio'].'",
                fecha_fin = "'.$descuento['fecha_fin'].'",
                precio = "'.$descuento['precio'].'",
                imagen = "'.$descuento['imagen'].'" 
                WHERE id="'.$descuento['id'].'"';
      $descuento = $this->connection->execute($_query);
    //return $descuento;
    }
  }
?>