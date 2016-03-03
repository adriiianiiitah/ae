<?php
  require_once('StandardCtrl.php');

  class CategoriasCtrl extends StandardCtrl {
    private $model;
    public $table;
    public $url;
    public $modal;

    public function __construct() {
      //parent::__construct();
      require_once('./models/CategoriasMdl.php');
      $this->model = new CategoriasMdl();
      $this->table_name = 'categorias';
      $this->url = 'images/categorias';
      $this->modal = 'modal-delete-categoria';
    }

    public function execute() {
      if(isset($_GET['action'])) {
        $action = $_GET['action'];

        switch ($action) {
          case '':
          case 'list':
            $this->showCategorias();
            break;
          case 'view':
            if(isset($_GET['id']) && !empty($_GET['id']))
              $this->showCategoria($_GET['id']);
            else
              $this->showErrorPage();
            break;
          case 'edit':
            if(isset($_GET['id']) && !empty($_GET['id']))
              $this->editCategoria($_GET['id']);
            else
              $this->showErrorPage();
            break;
          
          default:
            $this->showErrorPage();
            break;
        } 
      }
      else {
          $this->showCategorias();
        }
    }

    public function showCategorias() {
      //$categorias = $this->model->getAll();
      $view = $this->getView("categorias", 'list', $this->modal);
      $area = $this->getRow($view);
      $table = "";

      $categoria = [
        'id'            =>'1',
        'codigo'        =>'123456',
        'nombre'        =>'Dama',
        'descripcion'   =>'Lorem ipsum'
      ];

      //foreach ($categorias as $categoria) {
        $area = $row = $this->getRow($view);
        $diccionary = array(
          '{{id}}'=>$categoria['id'],
          '{{codigo}}'=>$categoria['codigo'],
          '{{nombre}}'=>$categoria['nombre'],
          '{{descripcion}}'=>$categoria['descripcion'],
          '{{categoria-view}}'=>"index.php?ctrl=categorias&action=view&id=".$categoria['id'],
          '{{categoria-edit}}'=>"index.php?ctrl=categorias&action=edit&id=".$categoria['id'],
        );
        $row = strtr($row,$diccionary);
        $table .= $row;
      //}

      $view = str_replace($area, $table, $view);
      echo $view;
    }

    public function showCategoria($id) {
      //$categoria = $this->model->getOne($id);
      $categoria = [
        'id'            =>'1',
        'codigo'        =>'123456',
        'nombre'        =>'Dama',
        'descripcion'   =>'Lorem ipsum'
      ];
      $table = "";

      if($this->isInt($id)) {
        
        $view = $this->getView("categoria-view", 'view', $this->modal);

        $content = $view;
        $diccionary = array(
          '{{id}}'=>$categoria['id'],
          '{{codigo}}'=>$categoria['codigo'],
          '{{nombre}}'=>$categoria['nombre'],
          '{{descripcion}}'=>$categoria['descripcion'],
          '{{image}}'=>$categoria['image'],
          '{{categoria-view}}'=>"index.php?ctrl=categorias&action=view&id=".$categoria['id'],
          '{{categoria-edit}}'=>"index.php?ctrl=categorias&action=edit&id=".$categoria['id'],
        );
        $content = strtr($view,$diccionary);
        $view = str_replace($view, $table, $content);

        echo $view;
      } else {
        $this->showErrorPage();
        
      }
    }

    public function editCategoria($id) {
      //$categoria = $this->model->getOne($id);
      //$image = $categoria['image'];
      $categoria = [
        'id'            =>'1',
        'codigo'        =>'123456',
        'nombre'        =>'Dama',
        'descripcion'   =>'Lorem ipsum'
      ];
      $table = "";

      if(empty($_POST)) {
        $diccionary = array(
          '{{id}}'=>$categoria['id'],
          '{{codigo}}'=>$categoria['codigo'],
          '{{nombre}}'=>$categoria['nombre'],
          '{{descripcion}}'=>$categoria['descripcion'],
          '{{image}}'=>$categoria['image'],
          '{{categoria-view}}'=>"index.php?ctrl=categorias&action=view&id=".$categoria['id'],
          '{{categoria-edit}}'=>"index.php?ctrl=categorias&action=edit&id=".$categoria['id'],
        );
        $this->showForm($id,'categoria-edit',$this->modal,$diccionary);//
        //$this->showFormEdit($id,$categoria);
      } else {
        $codigo = $_POST['codigo'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];

        if($_FILES['image']['tmp_name'] != '')
          $image = $this->uploadImage($id, $this->table_name, $_FILES['image'],$this->url);

        //$this->model->update($id,$codigo,$nombre,$descripcion,$image);

        $this->showCategoria($id);
      }
    }
  }
?>