<?php
	require_once('StandardCtrl.php');

	class IndexCtrl extends StandardCtrl {
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
					case 'index':
						$this->showIndex();
						break;
					
					default:
						$this->showErrorPage();
						break;
				} 
			}
			else {
					$this->showIndex();
				}
		}

		public function showIndex() {
			$navigation = file_get_contents("views/navigation.html");
			$view =  file_get_contents("views/index.html");
			$footer = file_get_contents("views/footer.html");
			echo $navigation.$view.$footer;
		}
	}
?>