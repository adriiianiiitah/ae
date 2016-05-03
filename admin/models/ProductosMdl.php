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

    function create() {

    }

    function getAll() {
      $_query = 'SELECT productos.id, productos.codigo, modelo, productos.nombre, categorias.nombre AS categoria_nombre, subcategorias.nombre AS subcategoria_nombre, productos.descripcion, material, marca, altura, precio, productos.imagen, colores.imagen AS color_imagen
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
      $_query = 'SELECT productos.id, productos.codigo, modelo, productos.nombre, categorias.nombre AS categoria_nombre, subcategorias.nombre AS subcategoria_nombre, productos.descripcion, material, marca, altura, precio, productos.imagen, colores.imagen AS color_imagen
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
      $_query = 'SELECT talla, stock FROM productos_tallas  
                 WHERE producto ="'.$id.'"';
      $tallas = $this->connection->execute($_query)->getResult();
      return $tallas;
    }

    function delete($id) {
      $_query = 'DELETE FROM productos 
                 WHERE id="'.$id.'"';
      $producto = $this->connection->execute($_query)->getResult();
      return $producto;
    }

    function update($id) {
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
                stock = "'.$producto['stock'].'",
                imagen = "'.$producto['imagen'].'",
                WHERE id="'.$producto['id'].'"';
      $producto = $this->connection->execute($_query);
    }
    /*
    function agregar($producto, $descripcion, $precio, $categoria, $marca, $imagen) {
      $query = "INSERT INTO productos (producto,categoria,marca,descripcion,precio,imagen) VALUES('".$categoria."','".$producto."','".$marca."','".$descripcion."','".$precio."','".$imagen."')";
      $resultado = $this->connection->ejecutar($query);
    }
    function listar() {
      $consulta = 'SELECT * FROM productos';
      $productos = $this->connection->ejecutar($consulta)->obtenerResultado();
      return $productos;
    }
    function consutar($id) {
      $consulta = 'SELECT * FROM productos WHERE codigo="'.$id.'"';
      $productos = $this->connection->ejecutar($consulta)->obtenerResultado();
      return $productos[0];
    }*/
  }
?>