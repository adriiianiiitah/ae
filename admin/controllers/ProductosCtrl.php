<?php
  require_once('StandardCtrl.php');

  class ProductosCtrl extends StandardCtrl {
    private $model;
    public $table_name;
    public $url;
    public $modal;

    public function __construct() {
      parent::__construct();
      require_once('./models/ProductosMdl.php');
      $this->model = new ProductosMdl();
      $this->table_name = 'productos';
      $this->url = 'images/productos';
      $this->modal = 'modal-delete-producto';
    }

    public function execute() {
      if(isset($_GET['action'])) {
        $action = $_GET['action'];

        switch ($action) {
          case '':
          case 'list':
            $this->showProductos();
            break;
          case 'view':
            if(isset($_GET['id']) && !empty($_GET['id']) && is_int((int)$_GET['id']))
              $this->showProducto($_GET['id']);
            else
              $this->showErrorPage();
            break;
          case 'edit':
            if(isset($_GET['id']) && !empty($_GET['id']) && is_int((int)$_GET['id']))
              $this->editProducto($_GET['id']);
            else
             // $this->showErrorPage();
               echo 's';
            break;
          
          default:
            $this->showProductos();
            break;
        } 
      }
      else {
          $this->showProductos();
        }
    }

    public function showProductos() {
      //$productos = $this->model->getAll();
      $view = $this->getView("productos", 'list', $this->modal);
      $area = $this->getRow($view);
      $table = "";

      //foreach ($productos as $producto) {
        $producto = [
          'id'=>'1',
          'codigo'=>'123456',
          'modelo'=>'MOD-1234',
          'nombre'=>'Zapatilla Pump',
          'categoria'=>'dama',
          'subcategoria'=>'zapatilla',
          'descripcion'=>'',
          'color'=>'negro-multicolor',
          'material'=>'piel',
          'marca'=>'AE',
          'altura'=>'10',
          'talla'=>'25.0',
          'precio'=>'$419.00',
          'stock'=>'$419.00',
        ];
        $area = $row = $this->getRow($view);
        $diccionary = array(
          '{{id}}'=>$producto['id'],
          '{{codigo}}'=>$producto['codigo'],
          '{{modelo}}'=>$producto['modelo'],
          '{{nombre}}'=>$producto['nombre'],
          '{{categoria}}'=>$producto['categoria'],
          '{{subcategoria}}'=>$producto['subcategoria'],
          '{{descripcion}}'=>$producto['descripcion'],
          '{{color}}'=>$producto['color'],
          '{{material}}'=>$producto['material'],
          '{{marca}}'=>$producto['marca'],
          '{{altura}}'=>$producto['altura'],
          '{{talla}}'=>$producto['talla'],
          '{{precio}}'=>$producto['precio'],
          '{{stock}}'=>$producto['stock'],
          '{{producto-view}}'=>"index.php?ctrl=productos&action=view&id=1".$producto['id'],
          '{{producto-edit}}'=>"index.php?ctrl=productos&action=edit&id=1".$producto['id'],
        );
        $row = strtr($row,$diccionary);
        $table .= $row;
      //} 

      $view = str_replace($area, $table, $view);
      echo $view;
    }

    public function showProducto($id) {
      //$producto = $this->model->getOne($id);
      $producto = [
          'id'=>'1',
          'codigo'=>'123456',
          'modelo'=>'MOD-1234',
          'nombre'=>'Zapatilla Pump',
          'categoria'=>'dama',
          'subcategoria'=>'zapatilla',
          'descripcion'=>'',
          'color'=>'negro-multicolor',
          'material'=>'piel',
          'marca'=>'AE',
          'altura'=>'10',
          'talla'=>'25.0',
          'precio'=>'$419.00',
          'stock'=>'$419.00',
        ];
        $table = "";

      if($this->isInt($id)) {
        
        $view = $this->getView("producto-view", 'view', $this->modal);

        $content = $view;
        $diccionary = array(
          '{{id}}'=>$producto['id'],
          '{{codigo}}'=>$producto['codigo'],
          '{{modelo}}'=>$producto['modelo'],
          '{{nombre}}'=>$producto['nombre'],
          '{{categoria}}'=>$producto['categoria'],
          '{{subcategoria}}'=>$producto['subcategoria'],
          '{{descripcion}}'=>$producto['descripcion'],
          '{{color}}'=>$producto['color'],
          '{{material}}'=>$producto['material'],
          '{{marca}}'=>$producto['marca'],
          '{{altura}}'=>$producto['altura'],
          '{{talla}}'=>$producto['talla'],
          '{{precio}}'=>$producto['precio'],
          '{{stock}}'=>$producto['stock'],
          '{{producto-view}}'=>"index.php?ctrl=productos&action=view&id=".$producto['id'],
          '{{producto-edit}}'=>"index.php?ctrl=productos&action=edit&id=".$producto['id'],
        );
        $content = strtr($view,$diccionary);
        $view = str_replace($view, $table, $content);

        echo $view;
      } else {
        $this->showErrorPage();
        
      }
    }

    public function editProducto($id) {
      //$producto = $this->model->getOne($id);
      //$image = $producto['image'];
      $producto = [
        'id'=>'1',
        'codigo'=>'123456',
        'modelo'=>'MOD-1234',
        'nombre'=>'Zapatilla Pump',
        'categoria'=>'dama',
        'subcategoria'=>'zapatilla',
        'descripcion'=>'',
        'color'=>'negro-multicolor',
        'material'=>'piel',
        'marca'=>'AE',
        'altura'=>'10',
        'talla'=>'25.0',
        'precio'=>'419',
        'stock'=>'120',
      ];

      if(empty($_POST)) {
        $diccionary = array(
          '{{id}}'=>$producto['id'],
          '{{codigo}}'=>$producto['codigo'],
          '{{modelo}}'=>$producto['modelo'],
          '{{nombre}}'=>$producto['nombre'],
          '{{categoria}}'=>$producto['categoria'],
          '{{subcategoria}}'=>$producto['subcategoria'],
          '{{descripcion}}'=>$producto['descripcion'],
          '{{color}}'=>$producto['color'],
          '{{material}}'=>$producto['material'],
          '{{marca}}'=>$producto['marca'],
          '{{altura}}'=>$producto['altura'],
          '{{talla}}'=>$producto['talla'],
          '{{precio}}'=>$producto['precio'],
          '{{stock}}'=>$producto['stock'],
          '{{producto-view}}'=>"index.php?ctrl=productos&action=view&id=".$producto['id'],
          '{{producto-edit}}'=>"index.php?ctrl=productos&action=edit&id=".$producto['id'],
        );
        $this->showForm($id,'producto-edit',$this->modal,$diccionary);//$id,$view,$modal,$diccionary
      } else {
        $codigo         = $_POST['codigo'];
        $modelo         = $_POST['modelo'];
        $nombre         = $_POST['nombre'];
        $categoria      = $_POST['categoria'];
        $subcategoria   = $_POST['subcategoria'];
        $descripcion    = $_POST['descripcion'];
        $color          = $_POST['color'];
        $material       = $_POST['material'];
        $marca          = $_POST['marca'];
        $altura         = $_POST['altura'];
        $talla          = $_POST['talla'];
        $precio         = $_POST['precio'];
        $stock          = $_POST['stock'];

        if($_FILES['image']['tmp_name'] != '')
          $image = $this->uploadImage($id, $this->table_name, $_FILES['image'],$this->url);

       // $this->model->update($id,$code,$name,$description,$image);

        $this->showproducto($id);
      }
    }

  }
?>
