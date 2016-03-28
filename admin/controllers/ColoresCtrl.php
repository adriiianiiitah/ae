<?php
  require_once('StandardCtrl.php');

  class ColoresCtrl extends StandardCtrl {
    private $model;
    public $table;
    public $url;
    public $modal;

    public function __construct() {
      //parent::__construct();
      require_once('./models/ColoresMdl.php');
      $this->model = new ColoresMdl();
      $this->table_name = 'colores';
      $this->url = 'images/colores';
      $this->modal = 'modal-delete-color';
    }

    public function execute() {
      if(isset($_GET['action'])) {
        $action = $_GET['action'];

        switch ($action) {
          case '':
          case 'list':
            $this->showColores();
            break;
          case 'view':
            if(isset($_GET['id']) && !empty($_GET['id']))
              $this->showColor($_GET['id']);
            else
              $this->showErrorPage();
            break;
          case 'edit':
            if(isset($_GET['id']) && !empty($_GET['id']))
              $this->editColor($_GET['id']);
            else
              $this->showErrorPage();
            break;
          
          default:
            $this->showErrorPage();
            break;
        } 
      }
      else {
          $this->showColores();
        }
    }

    public function showColores() {
      //$colors = $this->model->getAll();
      $view = $this->getView("colores", 'list', $this->modal);
      $area = $this->getRow($view);
      $table = "";

      $color = [
        'id'            =>'1',
        'codigo'        =>'123456',
        'nombre'        =>'rojo-blanco-multicolor'
      ];

      //foreach ($colors as $color) {
        $area = $row = $this->getRow($view);
        $diccionary = array(
          '{{id}}'=>$color['id'],
          '{{codigo}}'=>$color['codigo'],
          '{{nombre}}'=>$color['nombre'],
          '{{color-view}}'=>"index.php?ctrl=colores&action=view&id=".$color['id'],
          '{{color-edit}}'=>"index.php?ctrl=colores&action=edit&id=".$color['id'],
        );
        $row = strtr($row,$diccionary);
        $table .= $row;
      //}

      $view = str_replace($area, $table, $view);
      echo $view;
    }

    public function showColor($id) {
      //$color = $this->model->getOne($id);
      $color = [
        'id'            =>'1',
        'codigo'        =>'123456',
        'nombre'        =>'rojo-blanco-multicolor'
      ];
      $table = "";

      if($this->isInt($id)) {
        
        $view = $this->getView("color-view", 'view', $this->modal);

        $content = $view;
        $diccionary = array(
          '{{id}}'=>$color['id'],
          '{{codigo}}'=>$color['codigo'],
          '{{nombre}}'=>$color['nombre'],
          '{{color-view}}'=>"index.php?ctrl=colores&action=view&id=".$color['id'],
          '{{color-edit}}'=>"index.php?ctrl=colores&action=edit&id=".$color['id'],
        );
        $content = strtr($view,$diccionary);
        $view = str_replace($view, $table, $content);

        echo $view;
      } else {
        $this->showErrorPage();
        
      }
    }

    public function editColor($id) {
      //$color = $this->model->getOne($id);
      //$image = $color['image'];
      $color = [
        'id'            =>'1',
        'codigo'        =>'123456',
        'nombre'        =>'rojo-blanco-multicolor'
      ];
      $table = "";

      if(empty($_POST)) {
        $diccionary = array(
          '{{id}}'=>$color['id'],
          '{{codigo}}'=>$color['codigo'],
          '{{nombre}}'=>$color['nombre'],
          '{{color-view}}'=>"index.php?ctrl=colores&action=view&id=".$color['id'],
          '{{color-edit}}'=>"index.php?ctrl=colores&action=edit&id=".$color['id'],
        );
        $this->showForm($id,'color-edit',$this->modal,$diccionary);//
        //$this->showFormEdit($id,$color);
      } else {
        $codigo = $_POST['codigo'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];

        if($_FILES['image']['tmp_name'] != '')
          $image = $this->uploadImage($id, $this->table_name, $_FILES['image'],$this->url);

        //$this->model->update($id,$codigo,$nombre,$descripcion,$image);

        $this->showCatalogo($id);
      }
    }
  }
?>