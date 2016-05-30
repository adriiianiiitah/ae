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

    function updateImage($id, $image) {
      $_query = 'UPDATE descuentos SET 
                imagen = "'.$image.'" 
                WHERE id="'.$id.'"';
      $color = $this->connection->execute($_query);
    }

    function insert($descuento) {
      $_query = 'INSERT INTO descuentos (codigo,cantidad,producto,descuento,fecha_inicio,fecha_fin,precio,imagen) 
                 VALUES (
                   "'.$descuento['codigo'].'",
                   "'.$descuento['cantidad'].'",
                   "'.$descuento['producto'].'",
                   "'.$descuento['descuento'].'",
                   "'.$descuento['fecha_inicio'].'",
                   "'.$descuento['fecha_fin'].'",
                   "'.$descuento['precio'].'",
                   "'.$descuento['imagen'].'"
                   )';
      $this->connection->execute($_query);
      return $this->connection->returnId();
    }

    function delete($id) {
      $_query = 'DELETE FROM descuentos
                WHERE id ="'.$id.'"';
      $descuento = $this->connection->execute($_query)->getResult();
    }
  }
?>