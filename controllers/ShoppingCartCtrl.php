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
          case 'agregar-carrito':
            $this->agregarProducto();
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
      $data = $this->getDataCarrito();
      $view = $this->showData($view,$data,PRODUCTO_TAG_START,PRODUCTO_TAG_END);
      echo $view;
    }

    public function agregarProducto() {
      if(isset($_POST['producto_id']) && !empty($_POST['producto_id']) && isset($_POST['talla_id']) && !empty($_POST['talla_id'])) {
        if($this->isInt($_POST['producto_id']) && $this->isInt($_POST['talla_id'])) {
          $producto_talla = $this->model->getProductoTalla($_POST['producto_id'],$_POST['talla_id']);
          if($producto_talla) {
            $producto = $this->model->getProductoById($_POST['producto_id']);
            array_push($_SESSION['productos'],$producto);
            $talla = $this->model->getTallaById($_POST['talla_id']);
            array_push($_SESSION['tallas'],$talla);
            array_push($_SESSION['producto_talla'],$producto_talla);

            header ("Location: index.php?ctrl=shopping-cart&action=list");
          }
        } else {
          header ("Location: index.php?ctrl=shopping-cart&action=list");
          //$this->showErrorPage();
        }
        header ("Location: index.php?ctrl=shopping-cart&action=list");
        //$this->showErrorPage();
      }
    }

  }

?>