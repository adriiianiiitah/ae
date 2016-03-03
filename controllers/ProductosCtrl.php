<?php
  require_once('StandardCtrl.php');

  class ProductosCtrl extends StandardCtrl {
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
        case 'list':
          $this->showProductos();
        break;
        case 'show':
        if(isset($_GET['id']) && is_int((int)$_GET['id'])) {
          $id = $_GET['id'];
          $this->showProducto($id);
        } else {
          http_response_code(404);
        }
        break;

        default:
            http_response_code(404);
        break;
        } 
      }  else {
        $this->showProductos();
      }
    }

    public function showProductos() {
      $header = file_get_contents("views/header.html");
      $menu =  file_get_contents("views/menu.html");
      $view =  file_get_contents("views/productos.html");
      $footer = file_get_contents("views/footer.html");
      echo $header.$menu.$view.$footer;
    }

    public function showProducto($id) {
      $header = file_get_contents("views/header.html");
      $menu =  file_get_contents("views/menu.html");
      $view =  file_get_contents("views/producto.html");
      $footer = file_get_contents("views/footer.html");
      echo $header.$menu.$view.$footer;
    }

  }
?>