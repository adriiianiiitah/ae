<?php
  require_once('StandardCtrl.php');

  class SubcategoriasCtrl extends StandardCtrl {
    private $model;
    public $table_name;
    public $single;
    public $url;
    public $modal;
    public $image;

    public function __construct() {
      parent::__construct();
      require_once('./models/SubcategoriasMdl.php');
      $this->model = new SubcategoriasMdl();
      $this->table_name = 'subcategorias';
      $this->single = 'subcategoria';
      $this->url = 'images/subcategorias/';
      $this->image = 'images/subcategorias/subcategoria.png';
      $this->modal = 'modal-delete-subcategoria';
    }

    public function execute() {
      if(isset($_GET['action'])) {
        $action = $_GET['action'];

        switch ($action) {
          case '':
          case 'list':
            $this->showSubcategorias();
            break;
          case 'view':
            if(isset($_GET['id']) && !empty($_GET['id']))
              $this->showSubcategoria($_GET['id']);
            else
              $this->showErrorPage();
            break;
          case 'edit':
            if(isset($_GET['id']) && !empty($_GET['id']))
              $this->editSubcategoria($_GET['id']);
            else
              $this->showErrorPage();
            break;
          case 'create':
              $this->createSubcategoria();
            break;
          case 'delete':
            if(isset($_POST['subcategoria_id']) && !empty($_POST['subcategoria_id'])) {
              $this->deleteSubcategoria($_POST['subcategoria_id']);
            }
            break;
          case 'subcategorias':
            $subcategorias = $this->model->getAllSubcategoriasByCategoria($_GET['categoria_id']);
            $data = $this->loadDataSubcategorias($subcategorias);
          break;
          default:
            $this->showErrorPage();
            break;
        } 
      } else {
        $this->showSubcategorias();
      }
    }

    public function getDictionary($subcategoria) {
      return array (
        '{{id}}'                =>$subcategoria['id'],
        '{{codigo}}'            =>$subcategoria['codigo'],
        '{{nombre}}'            =>$subcategoria['nombre'],
        '{{categoria_id}}'      =>$subcategoria['categoria_id'],
        '{{categoria_nombre}}'  =>$subcategoria['categoria_nombre'],
        '{{categoria}}'         =>$subcategoria['categoria_nombre'],
        '{{descripcion}}'       =>$subcategoria['descripcion'],
        '{{image}}'             =>$subcategoria['imagen'],
        '{{subcategoria-view}}' =>"index.php?ctrl=subcategorias&action=view&id=".$subcategoria['id'],
        '{{subcategoria-edit}}' =>"index.php?ctrl=subcategorias&action=edit&id=".$subcategoria['id']
      );
    }

    public function showSubcategorias() {
      $subcategorias = $this->model->getAll();
      $view = $this->getView("subcategorias", 'list', $this->modal);
      $area = $this->getRow($view);
      $table = "";

      foreach ($subcategorias as $subcategoria) {
        $area = $row = $this->getRow($view);
        $diccionary = $this->getDictionary($subcategoria);
        $row = strtr($row,$diccionary);
        $table .= $row;
      }

      $view = str_replace($area, $table, $view);
      echo $view;
    }

    public function showSubcategoria($id) {
      if($this->isInt($id)) {
        $subcategoria = $this->model->getOne($id);

        if ($subcategoria) {
          $table = "";
          $view = $this->getView("subcategoria-view", 'view', $this->modal);

          $content = $view;
          $diccionary = $this->getDictionary($subcategoria);

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

    public function createSubcategoria() {
      $subcategoria = array (
        'id'                =>'',
        'codigo'            =>'',
        'nombre'            =>'',
        'categoria_id'      =>'{{categoria_id}}',
        'categoria_nombre'  =>'{{categoria_nombre}}',
        'descripcion'       =>'',
        'imagen'            =>$this->image
      );
      $id ='';

      if(empty($_POST)) {
        $table = "";
        $diccionary = $this->getDictionary($subcategoria);
        $view = $this->getViewForm($id,'subcategoria-edit',$this->modal,$diccionary);

        $categorias = $this->model->getAllCategorias();
        $data = $this->getDataCategorias($categorias);
        $view = $this->showData($view,$data,CATEGORIA_TAG_START,CATEGORIA_TAG_END);
        echo $view;
      } else {
        $errors = array();
        
        if($this->isCode($_POST['codigo'])) {
          $subcategoria['codigo'] = $_POST['codigo'];
        } else {
          $errors['codigo'] = 'El código es incorrecto. Debe contener letras, dígitos y guiones.';
        }
        if($this->isAlphanumeric($_POST['nombre'])) {
          $subcategoria['nombre'] = $_POST['nombre'];
        } else {
          $errors['nombre'] = 'El nombre es incorrecto. Debe contener letras, dígitos y guiones.';
        }

        if($this->isInt($_POST['categoria'])) {
          $subcategoria['categoria'] = $_POST['categoria'];
        } else {
          $errors['categoria'] = 'El código es incorrecto. Debe contener letras, dígitos y guiones.';
        }

        if($this->isDescription($_POST['descripcion']) || empty($_POST['descripcion'])) {
          $subcategoria['descripcion'] = $_POST['descripcion'];
        } else {
          $errors['descripcion'] = 'El código es incorrecto. Debe contener letras, dígitos y guiones.';
        }

        if(empty($errors)){
          $id = $this->model->insert($subcategoria);
          if($_FILES['image']['tmp_name'] != '') {
            $subcategoria['imagen'] = $this->uploadImage($id, $this->single, $_FILES['image'],$this->url);
            $this->model->updateImage($id, $subcategoria['imagen']);
          }

          header ("Location: index.php?ctrl=subcategorias&action=view&id=".$id);
        } else {
          $this->showErrorPage();
        }
      }
    }

    public function editSubcategoria($id) {
      if($this->isInt($id)) {
        $subcategoria = $this->model->getOne($id);
        $imagen = $subcategoria['imagen'];

        if ($subcategoria) {
          if(empty($_POST)) {
            $table = "";
            $diccionary = $this->getDictionary($subcategoria);
            $view = $this->getViewForm($id,'subcategoria-edit',$this->modal,$diccionary);

            echo $view;
          } else {
            $errors = array();
            $subcategoria = array(
              'id' => $id
            );
            
            if($this->isCode($_POST['codigo'])) {
              $subcategoria['codigo'] = $_POST['codigo'];
            } else {
              $errors['codigo'] = 'El código es incorrecto. Debe contener letras, dígitos y guiones.';
            }
            if($this->isAlphanumeric($_POST['nombre'])) {
              $subcategoria['nombre'] = $_POST['nombre'];
            } else {
              $errors['nombre'] = 'El nombre es incorrecto. Debe contener letras, dígitos y guiones.';
            }

            if($this->isInt($_POST['categoria'])) {
              $subcategoria['categoria'] = $_POST['categoria'];
            } else {
              $errors['categoria'] = 'El código es incorrecto. Debe contener letras, dígitos y guiones.';
            }

            if($this->isDescription($_POST['descripcion']) || empty($_POST['descripcion'])) {
              $subcategoria['descripcion'] = $_POST['descripcion'];
            } else {
              $errors['descripcion'] = 'El código es incorrecto. Debe contener letras, dígitos y guiones.';
            }

            if($_FILES['image']['tmp_name'] != '') {
              $subcategoria['imagen'] = $this->uploadImage($id, $this->single, $_FILES['image'],$this->url);
            } else {
              $subcategoria['imagen'] = $imagen;
            }
  
            if(empty($errors)){
              $this->model->update($subcategoria);
              header ("Location: index.php?ctrl=subcategorias&action=view&id=".$id);
            } else {
              $this->showErrorPage();
            }
          }
        }
      }
    }

    public function deleteSubcategoria($id) {
      if($this->isInt($id)) {
        $this->model->delete($id);
      }
      header ("Location: index.php?ctrl=subcategorias");
    }
  }
?>