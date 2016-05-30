<?php
  require_once('StandardCtrl.php');

  class OfertasCtrl extends StandardCtrl {
    private $model;
    public $table_name;
    public $single;
    public $url;
    public $modal;
    public $image;

    public function __construct() {
      parent::__construct();
      require_once('./models/OfertasMdl.php');
      $this->model = new OfertasMdl();
      $this->table_name = 'ofertas';
      $this->single = 'oferta';
      $this->url = 'images/ofertas/';
      $this->image = 'images/ofertas/oferta.png';
      $this->modal = 'modal-delete-oferta';
    }

    public function execute() {
      if(isset($_GET['action'])) {
        $action = $_GET['action'];

        switch ($action) {
          case '':
          case 'list': 
            $this->showOfertas();
            break;
          case 'view':
            if(isset($_GET['id']) && !empty($_GET['id']))
              $this->showOferta($_GET['id']);
            else
              $this->showErrorPage();
            break;
          case 'edit':
            if(isset($_GET['id']) && !empty($_GET['id']))
              $this->editOferta($_GET['id']);
            else
              $this->showErrorPage();
            break;
          case 'create':
              $this->createOferta();
            break;
          default:
            $this->showErrorPage();
            break;
        } 
      }
      else {
        $this->showOfertas();
      }
    }

    public function getDictionary($oferta) {
      return array (
        '{{id}}'              =>$oferta['id'],
        '{{codigo}}'          =>$oferta['codigo'],
        '{{cantidad}}'        =>$oferta['cantidad'],
        '{{producto_id}}'     =>$oferta['producto_id'],
        '{{producto_nombre}}' =>$oferta['producto_nombre'],
        '{{producto}}'        =>$oferta['producto_nombre'],
        '{{fecha_inicio}}'    =>$oferta['fecha_inicio'],
        '{{fecha_fin}}'       =>$oferta['fecha_fin'],
        '{{precio}}'          =>$oferta['precio'],
        '{{image}}'           =>$oferta['imagen'],
        '{{oferta-view}}'     =>"index.php?ctrl=ofertas&action=view&id=".$oferta['id'],
        '{{oferta-edit}}'     =>"index.php?ctrl=ofertas&action=edit&id=".$oferta['id'],
      );
    }

    public function showOfertas() {
      $ofertas = $this->model->getAll();
      $view = $this->getView("ofertas", 'list', $this->modal);
      $area = $this->getRow($view);
      $table = "";

      foreach ($ofertas as $oferta) {
        $area = $row = $this->getRow($view);
        $diccionary = $this->getDictionary($oferta);
        $row = strtr($row,$diccionary);
        $table .= $row;
      }

      $view = str_replace($area, $table, $view);
      echo $view;
    }

    public function showOferta($id) {
      if($this->isInt($id)) {
        $oferta = $this->model->getOne($id);

        if($oferta) {
          $table = "";
          $view = $this->getView("oferta-view", 'view', $this->modal);

          $content = $view;
          $diccionary = $this->getDictionary($oferta);
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

    public function createOferta() {
      $oferta = array(
        'id'              =>'',
        'codigo'          =>'',
        'cantidad'        =>'',
        'producto_id'     =>'{{producto_id}}',
        'producto_nombre' =>'{{producto_nombre}}',
        'precio'          =>'',
        'fecha_inicio'    =>'',
        'fecha_fin'       =>'',
        'imagen'          =>$this->image
      );
      $id ='';

      if(empty($_POST)) {
        $table = "";
        $diccionary = $this->getDictionary($oferta);
        $view = $this->getViewForm($id,'oferta-edit',$this->modal,$diccionary);

        $productos = $this->model->getAllProductos();
        $data = $this->getDataProductos($productos);
        $view = $this->showData($view,$data,PRODUCTO_TAG_START,PRODUCTO_TAG_END);

        echo $view;
      } else { 
        $errors = array();

        if($this->isCode($_POST['codigo'])) {
          $oferta['codigo'] = $_POST['codigo'];
        } else {
          $errors['codigo'] = 'El código es incorrecto. Debe contener letras, dígitos y guiones.';
        }
        if($this->isInt($_POST['cantidad'])) {
          $oferta['cantidad'] = $_POST['cantidad'];
        } else {
          $errors['cantidad'] = 'El código es incorrecto. Debe contener letras, dígitos y guiones.';
        }
        if($this->isInt($_POST['producto'])) {
          $oferta['producto'] = $_POST['producto'];
        } else {
          $errors['producto'] = 'El código es incorrecto. Debe contener letras, dígitos y guiones.';
        }
        if($this->isDate($_POST['fecha_inicio'])) {
          $oferta['fecha_inicio'] = $_POST['fecha_inicio'];
        } else {
          $errors['fecha_inicio'] = 'La fecha es incorrecta. El formato es dd/mm/aaaa.';
        }
        if($this->isDate($_POST['fecha_fin'])) {
          $oferta['fecha_fin'] = $_POST['fecha_fin'];
        } else {
          $errors['fecha_fin'] = 'La fecha es incorrecta. El formato es dd/mm/aaaa.';
        }
        if($this->isNumber($_POST['precio']) || $this->isInt($_POST['precio'])) {
          $oferta['precio'] = $_POST['precio'];
        } else {
          $errors['precio'] = 'La fecha es incorrecta. El formato es aaaa/mm/dd.';
        }

        if(empty($errors)) {
          $id = $this->model->insert($oferta);
          if($_FILES['image']['tmp_name'] != '') {
            $oferta['imagen']  = $this->uploadImage($id, $this->single, $_FILES['image'],$this->url);
            $this->model->updateImage($id, $oferta['imagen']);
          }
          header ("Location: index.php?ctrl=ofertas&action=view&id=".$id);
        } else {
          $this->showErrorPage();
        }
      }
    }

    public function editOferta($id) {
      if($this->isInt($id)) {
        $oferta = $this->model->getOne($id);
        $imagen = $oferta['imagen'];

        if ($oferta) {
          if(empty($_POST)) {
            $table = "";
            $diccionary = $this->getDictionary($oferta);
            $view = $this->getViewForm($id,'oferta-edit',$this->modal,$diccionary);

            echo $view;
          } else { 
            $errors = array();
            $oferta = array(
              'id' => $id
            );

            if($this->isCode($_POST['codigo'])) {
              $oferta['codigo'] = $_POST['codigo'];
            } else {
              $errors['codigo'] = 'El código es incorrecto. Debe contener letras, dígitos y guiones.';
            }
            if($this->isInt($_POST['cantidad'])) {
              $oferta['cantidad'] = $_POST['cantidad'];
            } else {
              $errors['cantidad'] = 'El código es incorrecto. Debe contener letras, dígitos y guiones.';
            }
            if($this->isInt($_POST['producto'])) {
              $oferta['producto'] = $_POST['producto'];
            } else {
              $errors['producto'] = 'El código es incorrecto. Debe contener letras, dígitos y guiones.';
            }
            if($this->isDate($_POST['fecha_inicio'])) {
              $oferta['fecha_inicio'] = $_POST['fecha_inicio'];
            } else {
              $errors['fecha_inicio'] = 'La fecha es incorrecta. El formato es dd/mm/aaaa.';
            }
            if($this->isDate($_POST['fecha_fin'])) {
              $oferta['fecha_fin'] = $_POST['fecha_fin'];
            } else {
              $errors['fecha_fin'] = 'La fecha es incorrecta. El formato es dd/mm/aaaa.';
            }
            if($this->isNumber($_POST['precio']) || $this->isInt($_POST['precio'])) {
              $oferta['precio'] = $_POST['precio'];
            } else {
              $errors['precio'] = 'La fecha es incorrecta. El formato es dd/mm/aaaa.';
            }
            if($_FILES['image']['tmp_name'] != '') {
              $oferta['imagen']  = $this->uploadImage($id, $this->single, $_FILES['image'],$this->url);
            } else {
              $oferta['imagen'] = $imagen;
            }

            if(empty($errors)) {
              $this->model->update($oferta);
              header ("Location: index.php?ctrl=ofertas&action=view&id=".$id);
            } else {
              $this->showErrorPage();
            }
          }
        }

      }
    }
  }
?>