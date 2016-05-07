<?php
  require_once('StandardCtrl.php');

  class ColoresCtrl extends StandardCtrl {
    private $model;
    public $table_name;
    public $single;
    public $url;
    public $modal;

    public function __construct() {
      parent::__construct();
      require_once('./models/ColoresMdl.php');
      $this->model = new ColoresMdl();
      $this->table_name = 'colores';
      $this->single = 'color';
      $this->url = 'images/colores/';
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
          case 'create':
              $this->createColor();
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

    public function getDictionary($color) {
      return array(
        '{{id}}'          =>$color['id'],
        '{{codigo}}'      =>$color['codigo'],
        '{{nombre}}'      =>$color['nombre'],
        '{{image}}'       =>$color['imagen'],
        '{{color-view}}'  =>"index.php?ctrl=colores&action=view&id=".$color['id'],
        '{{color-edit}}'  =>"index.php?ctrl=colores&action=edit&id=".$color['id'],
      );
    }

    public function showColores() {
      $colors = $this->model->getAll();
      $view = $this->getView("colores", 'list', $this->modal);
      $area = $this->getRow($view);
      $table = "";

      foreach ($colors as $color) {
        $area = $row = $this->getRow($view);
        $diccionary = $this->getDictionary($color);
        
        $row = strtr($row,$diccionary);
        $table .= $row;
      }

      $view = str_replace($area, $table, $view);
      echo $view;
    }

    public function showColor($id) {
      if($this->isInt($id)) {
        $color = $this->model->getOne($id);

        if($color) {
          $table = "";
          $view = $this->getView("color-view", 'view', $this->modal);

          $content = $view;
          $diccionary = $this->getDictionary($color);
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

    public function createColor() {
      $color = [
        'id'            =>'',
        'codigo'        =>'',
        'nombre'        =>'',
        'imagen'        =>''
      ];
      $table = "";

      if(empty($_POST)) {
        $diccionary = $this->getDictionary($color);
        $view = $this->getViewForm($id,'color-edit',$this->modal,$diccionary);

        echo $view;
      } else {
        $errors = [];
        $color = [
          'id' => $id
        ];
        if($this->isCode($_POST['codigo'])) {
          $color['codigo'] = $_POST['codigo'];
        } else {
          $errors['codigo'] = 'El código es incorrecto. Debe contener letras, dígitos y guiones.';
        }

        if($this->isColor($_POST['nombre'])) {
          $color['nombre'] = $_POST['nombre'];
        } else {
          $errors['nombre'] = 'El nombre es incorrecto. Debe contener letras, dígitos y guiones.';
        }

        if($_FILES['image']['tmp_name'] != '') {
          $color['imagen'] = $this->uploadImage($id, $this->single, $_FILES['image'],$this->url);
        } else {
          $color['imagen'] = $imagen;
        }

        if(empty($errors)) {
          $this->model->update($color);
          header ("Location: index.php?ctrl=colores&action=view&id=".$id);
        } else {
          $this->editColor($id);
        }

 
          $id = $this->model->create($color);

        //$this->model->update($color);

        $this->showColor(1);
      }
    }

    public function editColor($id) {
      if($this->isInt($id)) {
        $color = $this->model->getOne($id);
        $imagen = $color['imagen'];

        if($color) {
          if(empty($_POST)) {
            $table = "";
            $diccionary = $this->getDictionary($color);
            $view = $this->getViewForm($id,'color-edit',$this->modal,$diccionary);

            echo $view;
          } else {
            $errors = [];
            $color = [
              'id' => $id
            ];
            if($this->isCode($_POST['codigo'])) {
              $color['codigo'] = $_POST['codigo'];
            } else {
              $errors['codigo'] = 'El código es incorrecto. Debe contener letras, dígitos y guiones.';
            }

            if($this->isColor($_POST['nombre'])) {
              $color['nombre'] = $_POST['nombre'];
            } else {
              $errors['nombre'] = 'El nombre es incorrecto. Debe contener letras, dígitos y guiones.';
            }

            if($_FILES['image']['tmp_name'] != '') {
              $color['imagen'] = $this->uploadImage($id, $this->single, $_FILES['image'],$this->url);
            } else {
              $color['imagen'] = $imagen;
            }

            if(empty($errors)){
              $this->model->update($color);
              header ("Location: index.php?ctrl=colores&action=view&id=".$id);
            } else {
              $this->editColor($id);
            }
          }
        }
      }
    }
    
  }
?>