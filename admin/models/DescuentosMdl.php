<?php
	require_once('StandardMdl.php');
	require_once('DataBase.php');

	class DescuentosMdl extends StandardMdl {
		public $connection;
		public $_query;

		function __construct(){
			//parent::__construct();
			//$this->connection = DataBase::getInstance();
		}
	}
?>