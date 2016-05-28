<?php
  require_once('StandardMdl.php');
  require_once('DataBase.php');

  class ProductosMdl extends StandardMdl {
    public $connection;
    public $_query;

    function __construct(){
      parent::__construct();
      $this->connection = DataBase::getInstance();
    }

    function getAll() {
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
                ON colores.id = color';
      $productos = $this->connection->execute($_query)->getResult();
      return $productos;
    }

    function getOne($id) {
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

    function getColoresById($id) {
    
    }

    function getTallasById($id) {
      $_query = 'SELECT tallas.talla, stock 
                 FROM tallas 
                 INNER JOIN productos_tallas
                 ON tallas.id = productos_tallas.talla 
                 WHERE producto="'.$id.'"';
      $tallas = $this->connection->execute($_query)->getResult();
      return $tallas;
    }

    function delete($id) {
      $_query = 'DELETE FROM productos 
                 WHERE id="'.$id.'"';
      $producto = $this->connection->execute($_query)->getResult();
      return $producto;
    }

    function insert($producto) {
      $_query = 'INSERT INTO productos (codigo,modelo,nombre,categoria,subcategoria,descripcion,material,marca,altura,precio,color,imagen) 
                 VALUES (
                   "'.$producto['codigo'].'",
                   "'.$producto['modelo'].'",
                   "'.$producto['nombre'].'",
                   "'.$producto['categoria'].'",
                   "'.$producto['subcategoria'].'",
                   "'.$producto['descripcion'].'",
                   "'.$producto['material'].'",
                   "'.$producto['marca'].'",
                   "'.$producto['altura'].'",
                   "'.$producto['precio'].'",
                   "'.$producto['color'].'",
                   "'.$producto['imagen'].'"
                   )';
      $this->connection->execute($_query);
      return $this->connection->returnId();
    }

    function update($producto) {
      $_query = 'UPDATE productos SET 
                codigo = "'.$producto['codigo'].'",
                modelo = "'.$producto['modelo'].'",
                nombre = "'.$producto['nombre'].'",
                categoria = "'.$producto['categoria'].'",
                subcategoria = "'.$producto['subcategoria'].'",
                descripcion = "'.$producto['descripcion'].'",
                material = "'.$producto['material'].'",
                marca = "'.$producto['marca'].'",
                altura = "'.$producto['altura'].'",
                precio = "'.$producto['precio'].'",
                color = "'.$producto['color'].'",
                imagen = "'.$producto['imagen'].'"
                WHERE id="'.$producto['id'].'"';
      $producto = $this->connection->execute($_query);
    }

    function updateImage($id, $image) {
      $_query = 'UPDATE productos SET 
                imagen = "'.$image.'" 
                WHERE id="'.$id.'"';
      $producto = $this->connection->execute($_query);
    }
  }
?>