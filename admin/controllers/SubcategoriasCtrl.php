<?php
  require_once('StandardCtrl.php');

  class SubcategoriasCtrl extends StandardCtrl {
    private $model;
    public $table_name;
    public $single;
    public $url;
    public $modal;

    public function __construct() {
      parent::__construct();
      require_once('./models/SubcategoriasMdl.php');
      $this->model = new SubcategoriasMdl();
      $this->table_name = 'subcategorias';
      $this->single = 'subcategoria';
      $this->url = 'images/subcategorias/';
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
          
          default:
            $this->showErrorPage();
            break;
        } 
      } else {
          $this->showSubcategorias();
      }
    }

    public function showSubcategorias() {
      $subcategorias = $this->model->getAll();
      $view = $this->getView("subcategorias", 'list', $this->modal);
      $area = $this->getRow($view);
      $table = "";

      foreach ($subcategorias as $subcategoria) {
        $area = $row = $this->getRow($view);
        $diccionary = [
          '{{id}}'=>$subcategoria['id'],
          '{{codigo}}'=>$subcategoria['codigo'],
          '{{nombre}}'=>$subcategoria['nombre'],
          '{{categoria_nombre}}'=>$subcategoria['categoria_nombre'],
          '{{descripcion}}'=>$subcategoria['descripcion'],
          '{{image}}'=>$subcategoria['imagen'],
          '{{subcategoria-view}}'=>"index.php?ctrl=subcategorias&action=view&id=".$subcategoria['id'],
          '{{subcategoria-edit}}'=>"index.php?ctrl=subcategorias&action=edit&id=".$subcategoria['id'],
        ];
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
          $diccionary = [
            '{{id}}'=>$subcategoria['id'],
            '{{codigo}}'=>$subcategoria['codigo'],
            '{{nombre}}'=>$subcategoria['nombre'],
            '{{categoria_nombre}}'=>$subcategoria['categoria_nombre'],
            '{{descripcion}}'=>$subcategoria['descripcion'],
            '{{image}}'=>$subcategoria['imagen'],
            '{{subcategoria-view}}'=>"index.php?ctrl=subcategorias&action=view&id=".$subcategoria['id'],
            '{{subcategoria-edit}}'=>"index.php?ctrl=subcategorias&action=edit&id=".$subcategoria['id'],
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

    public function editSubcategoria($id) {
      if($this->isInt($id)) {
        $subcategoria = $this->model->getOne($id);
        $imagen = $subcategoria['imagen'];

        if ($subcategoria) {
          if(empty($_POST)) {
            $table = "";

            $diccionary = [
              '{{id}}'=>$subcategoria['id'],
              '{{codigo}}'=>$subcategoria['codigo'],
              '{{nombre}}'=>$subcategoria['nombre'],
              '{{categoria_nombre}}'=>$subcategoria['categoria_nombre'],
              '{{descripcion}}'=>$subcategoria['descripcion'],
              '{{image}}'=>$subcategoria['imagen'],
              '{{subcategoria-view}}'=>"index.php?ctrl=subcategorias&action=view&id=".$subcategoria['id'],
              '{{subcategoria-edit}}'=>"index.php?ctrl=subcategorias&action=edit&id=".$subcategoria['id'],
            ];
            $this->showForm($id,'subcategoria-edit',$this->modal,$diccionary);
          } else {
            $errors = [];
            $subcategoria = [
              'id' => $id
            ];
            
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

            if($this->isDescription($_POST['descripcion'])) {
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
              $this->editSubcategoria($id);
            }
          }
        }
      }
    }
  }
?>