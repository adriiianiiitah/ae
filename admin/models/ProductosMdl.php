<?php
	require_once('StandardMdl.php');
	require_once('DataBase.php');
	class ProductosMdl extends StandardMdl {
		public $connection;
		function __construct(){
			//parent::__construct();
			//$this->connection = DataBase::getInstance();
		}

		function create() {

		}

		function getAll() {

		}

		function getOne($id) {

		}

		function delete($id) {

		}

		function update($id) {

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