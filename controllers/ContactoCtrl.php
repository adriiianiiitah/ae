<?php
	require_once('StandardCtrl.php');

	class ContactoCtrl extends StandardCtrl {
		private $model;

		public function __construct() {
			parent::__construct();
			//require_once('./models/HomeMdl.php');
			//$this->model = new HomeMdl();
		}

		public function execute() {
			if(isset($_GET['action'])) {
				$action = $_GET['action'];

				switch ($action) {
					case '':
						$this->showContacto();
						break;
					
					default:
						http_response_code(404);
						break;
				} 
			}
			else {
					$this->showContacto();
				}
		}

		public function showContacto() {
			$header = file_get_contents("views/header.html");
			$menu =  file_get_contents("views/menu.html");
			$view =  file_get_contents("views/contacto.html");
			$footer = file_get_contents("views/footer.html");
			echo $header.$menu.$view.$footer;
		}

	}

?>