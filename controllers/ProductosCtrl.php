<?php
  require_once('StandardCtrl.php');

  class ProductosCtrl extends StandardCtrl {
    private $model;
    public $url;

    public function __construct() {
      parent::__construct();
      require_once('./models/ProductosMdl.php');
      $this->model = new ProductosMdl();
      $this->url = 'admin/images/productos/';
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
        if(isset($_GET['id']) && !empty($_GET['id'])) {
          $this->showProducto($_GET['id']);
        } else {
          $this->showErrorPage();
        }
        break;

        default:
          $this->showErrorPage();
        break;
        } 
      }  else {
        $this->showProductos();
      }
    }

    public function getDictionary($producto) {
      return  array(
        '{{id}}'                  =>$producto['id'],
        '{{codigo}}'              =>$producto['codigo'],
        '{{modelo}}'              =>$producto['modelo'],
        '{{nombre}}'              =>$producto['nombre'],
        '{{categoria}}'           =>$producto['categoria_nombre'],
        '{{subcategoria}}'        =>$producto['subcategoria_nombre'],
        '{{descripcion}}'         =>$producto['descripcion'],
        '{{color}}'               =>$producto['color_imagen'],
        '{{material}}'            =>$producto['material'],
        '{{marca}}'               =>$producto['marca'],
        '{{altura}}'              =>$producto['altura'],
        //'talla'               =>$producto['talla'],
        '{{precio}}'              =>$producto['precio'],
        //'{{stock}}'               =>$producto['stock'],
        '{{image}}'           =>$this->getUrl($this->url,$producto['imagen']),
        '{{producto-view}}'   =>"index.php?ctrl=productos&action=view&id=".$producto['id'],
        '{{producto-edit}}'   =>"index.php?ctrl=productos&action=edit&id=".$producto['id'],
      );
    }

    public function showProducto($id) {
      if($this->isInt($id)) {
        $producto = $this->model->getOne($id);

        if ($producto) {
          $table = "";
          $view = $this->getView("producto");

          $content = $view;
          $diccionary = $this->getDictionary($producto);
          //$content = strtr($view,$diccionary);
          //$view = str_replace($view, $table, $content);
          $this->showView($view);
        } else {
          $this->showErrorPage();
        }
      } else {
        $this->showErrorPage();
      }
    }

    public function showProductos() {
      

      $header = file_get_contents("views/header.html");
      $menu =  file_get_contents("views/menu.html");
      $view =  file_get_contents("views/productos.html");
      $footer = file_get_contents("views/footer.html"); 
      echo $header.$menu.$view.$footer;
    }

    

  }
?>