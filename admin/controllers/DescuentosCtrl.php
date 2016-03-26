<?php
  require_once('StandardCtrl.php');

  class DescuentosCtrl extends StandardCtrl {
    private $model;
    public $table;
    public $url;
    public $modal;

    public function __construct() {
      //parent::__construct();
      require_once('./models/DescuentosMdl.php');
      $this->model = new DescuentosMdl();
      $this->table_name = 'descuentos';
      $this->url = 'images/descuentos';
      $this->modal = 'modal-delete-descuento';
    }

    public function execute() {
      if(isset($_GET['action'])) {
        $action = $_GET['action'];

        switch ($action) {
          case '':
          case 'list': 
            $this->showDescuentos();
            break;
          case 'view':
            if(isset($_GET['id']) && !empty($_GET['id']))
              $this->showDescuento($_GET['id']);
            else
              $this->showErrorPage();
            break;
          case 'edit':
            if(isset($_GET['id']) && !empty($_GET['id']))
              $this->editDescuento($_GET['id']);
            else
              $this->showErrorPage();
            break;
          
          default:
            $this->showErrorPage();
            break;
        } 
      }
      else {
          $this->showDescuentos();
        }
    }

    public function showDescuentos() {
      //$descuentos = $this->model->getAll();
      $view = $this->getView("descuentos", 'list', $this->modal);
      $area = $this->getRow($view);
      $table = "";

      $descuento = [
        'id'            =>'1',
        'codigo'        =>'123456',
        'producto'      =>'6549885',
        'descuento'     =>'25%',
        'cantidad'      =>'3',
        'precio'        =>'123.50',
        'desde'         =>'10/02/2015',
        'hasta'         =>'10/03/2015'
      ];

      //foreach ($descuentos as $descuento) {
        $area = $row = $this->getRow($view);
        $diccionary = array(
          '{{id}}'=>$descuento['id'],
          '{{codigo}}'=>$descuento['codigo'],
          '{{producto}}'=>$descuento['producto'],
          '{{descuento}}'=>$descuento['descuento'],
          '{{cantidad}}'=>$descuento['cantidad'],
          '{{precio}}'=>$descuento['precio'],
          '{{desde}}'=>$descuento['desde'],
          '{{hasta}}'=>$descuento['hasta'],
          '{{descuento-view}}'=>"index.php?ctrl=descuentos&action=view&id=".$descuento['id'],
          '{{descuento-edit}}'=>"index.php?ctrl=descuentos&action=edit&id=".$descuento['id'],
        );
        $row = strtr($row,$diccionary);
        $table .= $row;
      //}

      $view = str_replace($area, $table, $view);
      echo $view;
    }

    public function showDescuento($id) {
      //$descuento = $this->model->getOne($id);
      $descuento = [
        'id'            =>'1',
        'codigo'        =>'123456',
        'producto'      =>'6549885',
        'descuento'      =>'25%',
        'cantidad'      =>'3',
        'precio'        =>'123.50',
        'desde'         =>'10/02/2015',
        'hasta'         =>'10/03/2015'
      ];
      $table = "";

      if($this->isInt($id)) {
        $view = $this->getView("descuento-view", 'view', $this->modal);

        $content = $view;
        $diccionary = array(
          '{{id}}'=>$descuento['id'],
          '{{codigo}}'=>$descuento['codigo'],
          '{{producto}}'=>$descuento['producto'],
          '{{descuento}}'=>$descuento['descuento'],
          '{{cantidad}}'=>$descuento['cantidad'],
          '{{precio}}'=>$descuento['precio'],
          '{{desde}}'=>$descuento['desde'],
          '{{hasta}}'=>$descuento['hasta'],
          '{{descuento-view}}'=>"index.php?ctrl=descuentos&action=view&id=".$descuento['id'],
          '{{descuento-edit}}'=>"index.php?ctrl=descuentos&action=edit&id=".$descuento['id'],
        );
        $content = strtr($view,$diccionary);
        $view = str_replace($view, $table, $content);

        echo $view;
      } else {
        $this->showErrorPage();
        
      }
    }

    public function editDescuento($id) {
      //$descuento = $this->model->getOne($id);
      //$image = $descuento['image'];
      $descuento = [
        'id'            =>'1',
        'codigo'        =>'123456',
        'producto'      =>'6549885',
        'descuento'      =>'25%',
        'cantidad'      =>'3',
        'precio'        =>'123.50',
        'desde'         =>'10/02/2015',
        'hasta'         =>'10/03/2015'
      ];
      $table = "";

      if(empty($_POST)) {
        $diccionary = array(
          '{{id}}'=>$descuento['id'],
          '{{codigo}}'=>$descuento['codigo'],
          '{{producto}}'=>$descuento['producto'],
          '{{descuento}}'=>$descuento['descuento'],
          '{{cantidad}}'=>$descuento['cantidad'],
          '{{precio}}'=>$descuento['precio'],
          '{{desde}}'=>$descuento['desde'],
          '{{hasta}}'=>$descuento['hasta'],
          '{{descuento-view}}'=>"index.php?ctrl=descuentos&action=view&id=".$descuento['id'],
          '{{descuento-edit}}'=>"index.php?ctrl=descuentos&action=edit&id=".$descuento['id'],
        );
        $this->showForm($id,'descuento-edit',$this->modal,$diccionary);
      } else {
        $codigo = $_POST['codigo'];
        $cantidad = $_POST['cantidad'];
        $producto = $_POST['producto'];
        $precio = $_POST['precio'];
        $de = $_POST['de'];
        $hasta = $_POST['hasta'];
        
        $descripcion = $_POST['descripcion'];

        if($_FILES['image']['tmp_name'] != '')
          $image = $this->uploadImage($id, $this->table_name, $_FILES['image'],$this->url);

        //$this->model->update($id,$codigo,$nombre,$descripcion,$image);

        $this->showDescuento($id);
      }
    }
  }
?>