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

  }
?>