<?php
  require_once('StandardCtrl.php');

  class CatalogosCtrl extends StandardCtrl {
    private $model;
    public $table_name;
    public $url;
    public $single;
    public $modal;
    public $image;
    public $url_pdf;

    public function __construct() {
      parent::__construct();
      require_once('./models/CatalogosMdl.php');
      $this->model = new CatalogosMdl();
      $this->table_name = 'catalogos';
      $this->single = 'catalogo';
      $this->url = 'images/catalogos/';
      $this->image = 'images/catalogos/catalogo.png';
      $this->modal = 'modal-delete-catalogo';
      $this->url_pdf = 'pdf/';
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
            $this->createCatalogo();
          break;
          case 'delete':
            if(isset($_POST['catalogo_id']) && !empty($_POST['catalogo_id'])) {
              $this->deleteCatalogo($_POST['catalogo_id']);
            }
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

    public function getDictionary($catalogo) {
      if(!isset($catalogo['pdf_required']))$catalogo['pdf_required'] = '';
      return  array(
        '{{id}}'              =>$catalogo['id'],
        '{{codigo}}'          =>$catalogo['codigo'],
        '{{nombre}}'          =>$catalogo['nombre'],
        '{{fecha}}'           =>$catalogo['fecha'],
        '{{categoria_id}}'    =>$catalogo['categoria_id'],
        '{{categoria_nombre}}'=>$catalogo['categoria_nombre'],
        '{{categoria}}'       =>$catalogo['categoria_nombre'],
        '{{image}}'           =>$catalogo['imagen'],
        '{{pdf}}'             =>$catalogo['pdf'],
        '{{pdf_required}}'    =>$catalogo['pdf_required'],
        '{{catalogo-view}}'   =>"index.php?ctrl=catalogos&action=view&id=".$catalogo['id'],
        '{{catalogo-edit}}'   =>"index.php?ctrl=catalogos&action=edit&id=".$catalogo['id'],
      );
    }

    public function showCatalogos() {
      $catalogos = $this->model->getAll();
      $view = $this->getView("catalogos", 'list', $this->modal);
      $area = $this->getRow($view);
      $table = "";

      foreach ($catalogos as $catalogo) {
        $area = $row = $this->getRow($view);
        $diccionary = $this->getDictionary($catalogo);
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
          $diccionary = $this->getDictionary($catalogo);
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

    public function createCatalogo() {
      $catalogo = array(
        'id'                =>'',
        'codigo'            =>'',
        'nombre'            =>'',
        'fecha'             =>'',
        'categoria_id'      =>'{{categoria_id}}',
        'categoria_nombre'  =>'{{categoria_nombre}}',
        'pdf'               =>'',
        'pdf_required'      =>'data-required',
        'imagen'            =>$this->image
      );
      $id ='';

      if(empty($_POST)) {
        $table = "";
        $diccionary = $this->getDictionary($catalogo);
        $view = $this->getViewForm($id,'catalogo-edit',$this->modal,$diccionary);

        $categorias = $this->model->getAllCategorias();
        $data = $this->getDataCategorias($categorias);
        $view = $this->showData($view,$data,CATEGORIA_TAG_START,CATEGORIA_TAG_END);
        echo $view;
      } else {
        $errors = array();

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
          $errors['fecha'] = 'La fecha es incorrecta. El formato es aaaa/mm/dd.';
        }

        if($this->isInt($_POST['categoria'])) {
          $catalogo['categoria'] = $_POST['categoria'];
        } else {
          $errors['categoria'] = 'La categoría es incorrecta. El formato es aaaa/mm/dd.';
        }

        if(empty($errors)){
          $id = $this->model->insert($catalogo);
          if($_FILES['image']['tmp_name'] != '') {
            $catalogo['imagen'] = $this->uploadImage($id, $this->single, $_FILES['image'],$this->url);
            $this->model->updateImage($id, $catalogo['imagen']);
          }

          if($_FILES['pdf']['tmp_name'] != '') {
            $catalogo['pdf'] = $this->uploadFile($id, $this->single, $_FILES['pdf'],$this->url_pdf);
            $this->model->updateFile($id, $catalogo['pdf']);
          }

          header ("Location: index.php?ctrl=catalogos&action=view&id=".$id);
        } else {
          $this->showErrorPage();
        }
      }
    }

    public function editCatalogo($id) {
      if($this->isInt($id)) {
        $catalogo = $this->model->getOne($id);
        $imagen = $catalogo['imagen'];
        $pdf = $catalogo['imagen'];

        if ($catalogo) { 
          if(empty($_POST)) {
            $table = "";
            $diccionary = $this->getDictionary($catalogo);
            $view = $this->getViewForm($id,'catalogo-edit',$this->modal,$diccionary);

            echo $view;
          } else {
            $errors = array();

            $catalogo = array(
              'id' => $id
            );

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
              $errors['fecha'] = 'La fecha es incorrecta. El formato es aaaa/mm/dd.';
            }

            if($this->isInt($_POST['categoria'])) {
              $catalogo['categoria'] = $_POST['categoria'];
            } else {
              $errors['categoria'] = 'La categoría es incorrecta. El formato es aaaa/mm/dd.';
            }

            if($_FILES['image']['tmp_name'] != '') {
              $catalogo['imagen'] = $this->uploadImage($id, $this->single, $_FILES['image'],$this->url);
            } else {
              $catalogo['imagen'] = $imagen;
            }

            if($_FILES['pdf']['tmp_name'] != '') {
              $catalogo['pdf'] = $this->uploadFile($id, $this->single, $_FILES['pdf'],$this->url_pdf);
            } else {
              $catalogo['pdf'] = $pdf;
            }

            if(empty($errors)){ 
              $this->model->update($catalogo);
              header ("Location: index.php?ctrl=catalogos&action=view&id=".$id);
            } else {
              $this->showErrorPage();
            }
          }
        } else {
          $this->showErrorPage();
        }
      } else {
          $this->showErrorPage();
        }
    }

    public function deleteCatalogo($id) {
      if($this->isInt($id)) {
        $this->model->delete($id);
      }
      header ("Location: index.php?ctrl=catalogos");
    }
  }
?>