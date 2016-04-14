<?php
  require_once('StandardCtrl.php');

  class CatalogosCtrl extends StandardCtrl {
    private $model;
    public $table;
    public $url;
    public $single;
    public $modal;

    public function __construct() {
      parent::__construct();
      require_once('./models/CatalogosMdl.php');
      $this->model = new CatalogosMdl();
      $this->table_name = 'catalogos';
      $this->single = 'catalogo';
      $this->url = 'images/catalogos/';
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
          case 'create':
            //$this->createCatalogo();
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
      $catalogos = $this->model->getAll();
      $view = $this->getView("catalogos", 'list', $this->modal);
      $area = $this->getRow($view);
      $table = "";

      foreach ($catalogos as $catalogo) {
        $area = $row = $this->getRow($view);
        $diccionary = array(
          '{{id}}'=>$catalogo['id'],
          '{{codigo}}'=>$catalogo['codigo'],
          '{{nombre}}'=>$catalogo['nombre'],
          '{{fecha}}'=>$catalogo['fecha'],
          '{{categoria}}'=>$catalogo['categoria'],
          '{{image}}'=>$catalogo['imagen'],
          //'{{pdf}}'=>$catalogo['pdf'],
          '{{catalogo-view}}'=>"index.php?ctrl=catalogos&action=view&id=".$catalogo['id'],
          '{{catalogo-edit}}'=>"index.php?ctrl=catalogos&action=edit&id=".$catalogo['id'],
        );
        $row = strtr($row,$diccionary);
        $table .= $row;
      }

      $view = str_replace($area, $table, $view);
      echo $view;
    }

    public function showCatalogo($id) {
      if($this->isInt($id)) {
        $catalogo = $this->model->getOne($id);

        if ($catalogo) {
          $table = "";
          $view = $this->getView("catalogo-view", 'view', $this->modal);

          $content = $view;
          $diccionary = [
            '{{id}}'=>$catalogo['id'],
            '{{codigo}}'=>$catalogo['codigo'],
            '{{nombre}}'=>$catalogo['nombre'],
            '{{fecha}}'=>$catalogo['fecha'],
            '{{categoria}}'=>$catalogo['categoria'],
            '{{image}}'=>$catalogo['imagen'],
            //'{{pdf}}'=>$catalogo['pdf'],
            '{{catalogo-view}}'=>"index.php?ctrl=catalogos&action=view&id=".$catalogo['id'],
            '{{catalogo-edit}}'=>"index.php?ctrl=catalogos&action=edit&id=".$catalogo['id'],
          ];
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

    public function editCatalogo($id) {
      if($this->isInt($id)) {
        $catalogo = $this->model->getOne($id);
        $imagen = $catalogo['imagen'];

        if ($catalogo) { 
          if(empty($_POST)) {
            $table = "";
            $diccionary = [
              '{{id}}'=>$catalogo['id'],
              '{{codigo}}'=>$catalogo['codigo'],
              '{{nombre}}'=>$catalogo['nombre'],
              '{{fecha}}'=>$catalogo['fecha'],
              '{{categoria}}'=>$catalogo['categoria'],
              '{{image}}'=>$catalogo['imagen'],
              //'{{pdf}}'=>$catalogo['pdf'],
              '{{catalogo-view}}'=>"index.php?ctrl=catalogos&action=view&id=".$catalogo['id'],
              '{{catalogo-edit}}'=>"index.php?ctrl=catalogos&action=edit&id=".$catalogo['id'],
            ];
            $this->showForm($id,'catalogo-edit',$this->modal,$diccionary);//
          } else {
            $errors = [];

            $catalogo = [
              'id' => $id
            ];

            if($this->isCode($_POST['codigo'])) {
              $catalogo['codigo'] = $_POST['codigo'];
            } else {
              $errors['codigo'] = 'El código es incorrecto. Debe contener letras, dígitos y guiones.';
            }

            if($this->isAlphanumeric($_POST['nombre'])) {
              $catalogo['nombre'] = $_POST['nombre'];
            } else {
              $errors['nombre'] = 'El nombre es incorrecto. Debe contener letras, dígitos y espacios.';
            }

            if($this->isDate($_POST['fecha'])) {
              $catalogo['fecha'] = $_POST['fecha'];
            } else {
              $errors['fecha'] = 'La fecha es incorrecta. El formato es dd/mm/aaaa.';
            }

            if($this->isInt($_POST['categoria'])) {
              $catalogo['categoria'] = $_POST['categoria'];
            } else {
              $errors['categoria'] = 'La categoría es incorrecta. El formato es dd/mm/aaaa.';
            }

            if($_FILES['image']['tmp_name'] != '') {
              $catalogo['imagen'] = $this->uploadImage($id, $this->single, $_FILES['image'],$this->url);
            } else {
              $catalogo['imagen'] = $imagen;
            }

            if(empty($errors)){ 
              $this->model->update($catalogo);
              header ("Location: index.php?ctrl=catalogos&action=view&id=".$id);
              //$this->showCatalogo($id);
            } else {
              $this->editCatalogo($id);
            }
          }
        }



      }
    }
  }
?>