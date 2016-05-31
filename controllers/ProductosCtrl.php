<?php
  require_once('StandardCtrl.php');

  class ProductosCtrl extends StandardCtrl {
    public $model;
    public $url;

    public function __construct() {
      parent::__construct();
      require_once('./models/ProductosMdl.php');
      $this->model = new ProductosMdl();
      $this->url = 'admin';
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
        '{{color}}'               =>$this->getColorNombre($producto['color_nombre']),
        //'{{color}}'               =>$producto['color_imagen'],
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
          $content = strtr($view,$diccionary);
          $view = str_replace($view, $table, $content);

          $colores = $this->model->getColoresByCodigo($id,$producto['codigo']);
          $data = $this->getDataColoresByProducto($colores);
          $view = $this->showData($view,$data,COLOR_TAG_START,COLOR_TAG_END);

          $tallas = $this->model->getTallasById($id);
          $data = $this->getDataTallasByProducto($tallas);
          $view = $this->showData($view,$data,TALLA_TAG_START,TALLA_TAG_END);


          $this->showView($view);
        } else {
          $this->showErrorPage();
        }
      } else {
        $this->showErrorPage();
      }
    }

    public function showProductos() {
      $view = $this->getView("productos");
      $filtros = array();

      if(isset($_GET['categoria']) && !empty($_GET['categoria']) && $this->isAlphanumeric($_GET['categoria'])) {
        $filtros['categoria'] = $_GET['categoria'];

        $subcategorias = $this->model->getAllSubcategoriasByCathegory($_GET['categoria']);
        $data = $this->getDataSubcategorias($subcategorias);
        $view = $this->showData($view,$data,SUBCATEGORIA_TAG_START,SUBCATEGORIA_TAG_END);

        $colores = $this->model->getAllColoresByCathegory($_GET['categoria']);
        $data = $this->getDataColores($colores);
        $view = $this->showData($view,$data,COLOR_TAG_START,COLOR_TAG_END);
      } else {
        $subcategorias = $this->model->getAllSubcategoriasByCathegory();
        $data = $this->getDataSubcategorias($subcategorias);
        $view = $this->showData($view,$data,SUBCATEGORIA_TAG_START,SUBCATEGORIA_TAG_END);

        $colores = $this->model->getAllColoresByCathegory();
        $data = $this->getDataColores($colores);
        $view = $this->showData($view,$data,COLOR_TAG_START,COLOR_TAG_END);
      }

      if(isset($_GET['subcategoria']) && !empty($_GET['subcategoria']) && $this->isAlphanumeric($_GET['subcategoria'])) {
        $filtros['subcategoria'] = $_GET['subcategoria'];
      }

      if(isset($_GET['color']) && !empty($_GET['color']) && $this->isColor($_GET['color'])) {
        $filtros['color'] = $_GET['color'];
      }

      if(isset($_GET['precio_min']) && !empty($_GET['precio_min']) && $this->isInt($_GET['precio_min'])) {
        $filtros['precio_min'] = $_GET['precio_min'];
      }

      if(isset($_GET['precio_max']) && !empty($_GET['precio_max']) && $this->isInt($_GET['precio_max'])) {
        $filtros['precio_max'] = $_GET['precio_max'];
      }

      $data = $this->getDataFiltters($filtros);
      $view = $this->showData($view,$data,FILTRO_TAG_START,FILTRO_TAG_END);

      $productos = $this->model->getAllProductosByFiltters($filtros);
      $data = $this->getDataProductos($productos);
      $view = $this->showData($view,$data,PRODUCTO_TAG_START,PRODUCTO_TAG_END);

      $this->showView($view);
    }
  }
?>