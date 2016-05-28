<?php
  require_once('StandardCtrl.php');

  class CategoriasCtrl extends StandardCtrl {
    private $model;
    public $table_name;
    public $single;
    public $url;
    public $modal;
    public $image;

    public function __construct() {
      parent::__construct();
      require_once('./models/CategoriasMdl.php');
      $this->model = new CategoriasMdl();
      $this->table_name = 'categorias';
      $this->single = 'categoria';
      $this->url = 'images/categorias/';
      $this->image = 'images/categorias/categoria.png';
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
          case 'create':
              $this->createCategoria();
            break;
          case 'categorias':
            $categorias = $this->model->getAllCategorias();
            $data = $this->loadDataCategorias($categorias);
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

    public function getDictionary($categoria) {
      return  array(
        '{{id}}'              =>$categoria['id'],
        '{{codigo}}'          =>$categoria['codigo'],
        '{{nombre}}'          =>$categoria['nombre'],
        '{{descripcion}}'     =>$categoria['descripcion'],
        '{{image}}'           =>$categoria['imagen'],
        '{{categoria-view}}'  =>"index.php?ctrl=categorias&action=view&id=".$categoria['id'],
        '{{categoria-edit}}'  =>"index.php?ctrl=categorias&action=edit&id=".$categoria['id']
      );
    }

    public function showCategorias() {
      $categorias = $this->model->getAll();
      $view = $this->getView("categorias", 'list', $this->modal);
      $area = $this->getRow($view);
      $table = "";

      foreach ($categorias as $categoria) {
        $area = $row = $this->getRow($view);
        $diccionary = $this->getDictionary($categoria);
        $row = strtr($row,$diccionary);
        $table .= $row;
      }

      $view = str_replace($area, $table, $view);
      echo $view;
    }

    public function showCategoria($id) {
      if($this->isInt($id)) {
        $categoria = $this->model->getOne($id);
        
        if($categoria) {
          $table = "";
          $view = $this->getView("categoria-view", 'view', $this->modal);

          $content = $view;
          $diccionary = $this->getDictionary($categoria);
          $content = strtr($view,$diccionary);
          $view = str_replace($view, $table, $content);
          echo $view;
        } else {
          $this->showErrorPage();
        }
      } else {
        $this->showErrorPage();
        
      }
    }

    public function createCategoria() {
      $categoria = array(
        'id'            =>'',
        'codigo'        =>'',
        'nombre'        =>'',
        'descripcion'   =>'',
        'imagen'        =>$this->image
      );
      $id ='';

      if(empty($_POST)) {
        $table = "";
        $diccionary = $this->getDictionary($categoria);
        $view = $this->getViewForm($id,'categoria-edit',$this->modal,$diccionary);
        echo $view;
      } else {
        $errors = array();

        if($this->isCode($_POST['codigo'])) {
          $categoria['codigo'] = $_POST['codigo'];
        } else {
          $errors['codigo'] = 'El código es incorrecto. Debe contener letras, dígitos y guiones.';
        }
        if($this->isAlphanumeric($_POST['nombre'])) {
          $categoria['nombre'] = $_POST['nombre'];
        } else {
          $errors['nombre'] = 'El nombre es incorrecto. Debe contener letras, dígitos y guiones.';
        }

        if($this->isDescription($_POST['descripcion'])) {
          $categoria['descripcion'] = $_POST['descripcion'];
        } else {
          $errors['descripcion'] = 'El código es incorrecto. Debe contener letras, dígitos y guiones.';
        }

        if(empty($errors)){
          $id = $this->model->insert($categoria);
          if($_FILES['image']['tmp_name'] != '') {
            $categoria['imagen'] = $this->uploadImage($id, $this->single, $_FILES['image'],$this->url);
            $this->model->updateImage($id, $categoria['imagen']);
          }
          header ("Location: index.php?ctrl=categorias&action=view&id=".$id);
        } else {
          $this->createCategoria();
        }
      }
    }

    public function editCategoria($id) {
      if($this->isInt($id)) {
        $categoria = $this->model->getOne($id);
        $imagen = $categoria['imagen'];

        if ($categoria) {
          if(empty($_POST)) {
            $table = "";
            $diccionary = $this->getDictionary($categoria);
            $view = $this->getViewForm($id,'categoria-edit',$this->modal,$diccionary);
            echo $view;
          } else {
            $errors = array();
            $categoria = array(
              'id' => $id
            );

            if($this->isCode($_POST['codigo'])) {
              $categoria['codigo'] = $_POST['codigo'];
            } else {
              $errors['codigo'] = 'El código es incorrecto. Debe contener letras, dígitos y guiones.';
            }
            if($this->isAlphanumeric($_POST['nombre'])) {
              $categoria['nombre'] = $_POST['nombre'];
            } else {
              $errors['nombre'] = 'El nombre es incorrecto. Debe contener letras, dígitos y guiones.';
            }

            if($this->isDescription($_POST['descripcion'])) {
              $categoria['descripcion'] = $_POST['descripcion'];
            } else {
              $errors['descripcion'] = 'El código es incorrecto. Debe contener letras, dígitos y guiones.';
            }
            if($_FILES['image']['tmp_name'] != '') {
              $categoria['imagen'] = $this->uploadImage($id, $this->single, $_FILES['image'],$this->url);
            } else {
              $categoria['imagen'] = $imagen;
            }

            if(empty($errors)){
              $this->model->update($categoria);
              header ("Location: index.php?ctrl=categorias&action=view&id=".$id);
            } else {
              $this->editCategoria($id);
            }
          }
        }

      }
    }
  }
?>