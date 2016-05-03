<?php
  require_once('DataBase.php');

  class StandardMdl {
    public $connection;
    public $_query;

    function __construct() {
      $this->connection = DataBase::getInstance();
    }

    function getAllRoles() {
      $_query = 'SELECT id AS rol_id, nombre AS rol_nombre, descripcion 
                FROM roles';
      $roles = $this->connection->execute($_query)->getResult();
      return $roles;
    }

    function getAllProductos() {
      $_query = 'SELECT id AS producto_id, nombre AS producto_nombre 
                FROM productos';
      $productos = $this->connection->execute($_query)->getResult();
      return $productos;
    }

    function getAllCategorias() {
      $_query = 'SELECT id AS categoria_id, nombre AS categoria_nombre 
                FROM categorias';
      $categorias = $this->connection->execute($_query)->getResult();
      return $categorias;
    }

    function getAllPaises() {
      $_query = 'SELECT id AS pais_id, nombre AS pais_nombre 
                FROM paises';
      $paises = $this->connection->execute($_query)->getResult();
      return $paises;
    }

    function getAllEstados() {
      $_query = 'SELECT id AS estado_id, nombre AS estado_nombre 
                FROM estados';
      $estados = $this->connection->execute($_query)->getResult();
      return $estados;
    }

    function getAllMunicipios() {
      $_query = 'SELECT id AS municipio_id, nombre AS municipio_nombre 
                FROM municipios';
      $municipios = $this->connection->execute($_query)->getResult();
      return $municipios;
    }

  }
?>