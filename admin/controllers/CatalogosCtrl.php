<?php
  require_once('StandardCtrl.php');

  class CatalogosCtrl extends StandardCtrl {
    private $model;
    public $table;
    public $url;
    public $modal;

    public function __construct() {
      //parent::__construct();
      require_once('./models/CatalogosMdl.php');
      $this->model = new CatalogosMdl();
      $this->table_name = 'catalogos';
      $this->url = 'images/catalogos';
      $this->modal = 'modal-delete-catalogo';
    }

    public function execute() {
      if(isset($_GET['action'])) {
        $action = $_GET['action'];

        switch ($action) {
          case '':
          case 'list':
            $this->showCatalogos();
            break;
          case 'view':
            if(isset($_GET['id']) && !empty($_GET['id']))
              $this->showCatalogo($_GET['id']);
            else
              $this->showErrorPage();
            break;
          case 'edit':
            if(isset($_GET['id']) && !empty($_GET['id']))
              $this->editCatalogo($_GET['id']);
            else
              $this->showErrorPage();
            break;
          
          default:
            $this->showErrorPage();
            break;
        } 
      }
      else {
          $this->showCatalogos();
        }
    }

    public function showCatalogos() {
      //$catalogos = $this->model->getAll();
      $view = $this->getView("catalogos", 'list', $this->modal);
      $area = $this->getRow($view);
      $table = "";

      $catalogo = [
        'id'            =>'1',
        'codigo'        =>'123456',
        'nombre'        =>'Invierno 2016',
        'fecha'         =>'12/12/2016',
        'categoria'     =>'Dama'
      ];

      //foreach ($catalogos as $catalogo) {
        $area = $row = $this->getRow($view);
        $diccionary = array(
          '{{id}}'=>$catalogo['id'],
          '{{codigo}}'=>$catalogo['codigo'],
          '{{nombre}}'=>$catalogo['nombre'],
          '{{fecha}}'=>$catalogo['fecha'],
          '{{categoria}}'=>$catalogo['categoria'],
          '{{catalogo-view}}'=>"index.php?ctrl=catalogos&action=view&id=".$catalogo['id'],
          '{{catalogo-edit}}'=>"index.php?ctrl=catalogos&action=edit&id=".$catalogo['id'],
        );
        $row = strtr($row,$diccionary);
        $table .= $row;
      //}

      $view = str_replace($area, $table, $view);
      echo $view;
    }

    public function showCatalogo($id) {
      //$catalogo = $this->model->getOne($id);
      $catalogo = [
        'id'            =>'1',
        'codigo'        =>'123456',
        'nombre'        =>'Invierno 2016',
        'fecha'         =>'12/12/2016',
        'categoria'     =>'Dama'
      ];
      $table = "";

      if($this->isInt($id)) {
        
        $view = $this->getView("catalogo-view", 'view', $this->modal);

        $content = $view;
        $diccionary = array(
          '{{id}}'=>$catalogo['id'],
          '{{codigo}}'=>$catalogo['codigo'],
          '{{nombre}}'=>$catalogo['nombre'],
          '{{fecha}}'=>$catalogo['fecha'],
          '{{categoria}}'=>$catalogo['categoria'],
          '{{catalogo-view}}'=>"index.php?ctrl=catalogos&action=view&id=".$catalogo['id'],
          '{{catalogo-edit}}'=>"index.php?ctrl=catalogos&action=edit&id=".$catalogo['id'],
        );
        $content = strtr($view,$diccionary);
        $view = str_replace($view, $table, $content);

        echo $view;
      } else {
        $this->showErrorPage();
        
      }
    }

    public function editCatalogo($id) {
      //$catalogo = $this->model->getOne($id);
      //$image = $catalogo['image'];
      $catalogo = [
        'id'            =>'1',
        'codigo'        =>'123456',
        'nombre'        =>'Invierno 2016',
        'fecha'         =>'12/12/2016',
        'categoria'     =>'Dama'
      ];
      $table = "";

      if(empty($_POST)) {
        $diccionary = array(
          '{{id}}'=>$catalogo['id'],
          '{{codigo}}'=>$catalogo['codigo'],
          '{{nombre}}'=>$catalogo['nombre'],
          '{{fecha}}'=>$catalogo['fecha'],
          '{{categoria}}'=>$catalogo['categoria'],
          '{{catalogo-view}}'=>"index.php?ctrl=catalogos&action=view&id=".$catalogo['id'],
          '{{catalogo-edit}}'=>"index.php?ctrl=catalogos&action=edit&id=".$catalogo['id'],
        );
        $this->showForm($id,'catalogo-edit',$this->modal,$diccionary);//
        //$this->showFormEdit($id,$catalogo);
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