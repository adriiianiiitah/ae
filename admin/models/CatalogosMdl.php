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

    function getAll() {
      $_query = 'SELECT catalogos.id, catalogos.codigo, catalogos.nombre, fecha, categorias.id AS categoria_id, categorias.nombre AS categoria_nombre, pdf, catalogos.imagen 
                FROM catalogos
                INNER JOIN categorias
                ON categorias.id = categoria';
      $catalogos = $this->connection->execute($_query)->getResult();
      return $catalogos;
    }

    function getOne($id) {
      $_query = 'SELECT catalogos.id, catalogos.codigo, catalogos.nombre, fecha, categorias.id AS categoria_id, categorias.nombre AS categoria_nombre, pdf, catalogos.imagen 
                FROM catalogos
                INNER JOIN categorias
                ON categorias.id = categoria 
                WHERE catalogos.id="'.$id.'"';
      $catalogo = $this->connection->execute($_query)->getFirst();
      return $catalogo;
    }

    function delete($id) {
      $_query = 'DELETE FROM catalogos 
                 WHERE id="'.$id.'"';
      $catalogo = $this->connection->execute($_query)->getResult();
    }

    function insert($catalogo) {
      $_query = 'INSERT INTO catalogos (codigo,nombre,fecha,categoria,imagen,pdf) 
                 VALUES (
                   "'.$catalogo['codigo'].'",
                   "'.$catalogo['nombre'].'",
                   "'.$catalogo['fecha'].'",
                   "'.$catalogo['categoria'].'",
                   "'.$catalogo['imagen'].'",
                   "'.$catalogo['pdf'].'"
                   )';
      $this->connection->execute($_query);
      return $this->connection->returnId();
    }

    function update($catalogo) {
      $_query = 'UPDATE catalogos SET 
                codigo = "'.$catalogo['codigo'].'",
                nombre = "'.$catalogo['nombre'].'",
                fecha = "'.$catalogo['fecha'].'",
                imagen = "'.$catalogo['imagen'].'",
                categoria = "'.$catalogo['categoria'].'",
                pdf ="'.$catalogo['pdf'].'"
                WHERE id="'.$catalogo['id'].'"';
      $catalogo = $this->connection->execute($_query);
      //return $catalogo;
    }

    function updateImage($id, $image) {
      $_query = 'UPDATE catalogos SET 
                imagen = "'.$image.'" 
                WHERE id="'.$id.'"';
      $catalogo = $this->connection->execute($_query);
    }

    function updateFile($id, $pdf) {
      $_query = 'UPDATE catalogos SET 
                pdf = "'.$pdf.'" 
                WHERE id="'.$id.'"';
      $catalogo = $this->connection->execute($_query);
    }
  }
?>