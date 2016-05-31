<?php
  require_once('StandardCtrl.php');

  class ShoppingCartCtrl extends StandardCtrl {
    public $model;

    public function __construct() {
      parent::__construct();
      require_once('./models/HomeMdl.php');
      $this->model = new HomeMdl();
    }

    public function execute() {
      if(isset($_GET['action'])) {
        $action = $_GET['action'];

        switch ($action) {
          case '':
          case 'list':
            $this->showShoppingCar();
            break;
          
          default:
            $this->showErrorPage();
            break;
        } 
      }
      else {
          $this->showShoppingCar();
        }
    }

    public function showShoppingCar() {
      $view = $this->getView("shoppingcart");
      echo $view;
    }

  }

?>