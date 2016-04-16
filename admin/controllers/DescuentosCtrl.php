<?php
  require_once('StandardCtrl.php');

  class DescuentosCtrl extends StandardCtrl {
    private $model;
    public $table;
    public $single;
    public $url;
    public $modal;

    public function __construct() {
      parent::__construct();
      require_once('./models/DescuentosMdl.php');
      $this->model = new DescuentosMdl();
      $this->table_name = 'descuentos';
      $this->single = 'descuento';
      $this->url = 'images/descuentos/';
      $this->modal = 'modal-delete-descuento';
    }

    public function execute() {
      if(isset($_GET['action'])) {
        $action = $_GET['action'];

        switch ($action) {
          case '':
          case 'list': 
            $this->showDescuentos();
            break;
          case 'view':
            if(isset($_GET['id']) && !empty($_GET['id']))
              $this->showDescuento($_GET['id']);
            else
              $this->showErrorPage();
            break;
          case 'edit':
            if(isset($_GET['id']) && !empty($_GET['id']))
              $this->editDescuento($_GET['id']);
            else
              $this->showErrorPage();
            break;
          
          default:
            $this->showErrorPage();
            break;
        } 
      }
      else {
          $this->showDescuentos();
        }
    }

    public function showDescuentos() {
      $descuentos = $this->model->getAll();
      $view = $this->getView("descuentos", 'list', $this->modal);
      $area = $this->getRow($view);
      $table = "";

      foreach ($descuentos as $descuento) {
        $area = $row = $this->getRow($view);
        $diccionary = [
          '{{id}}'=>$descuento['id'],
          '{{codigo}}'=>$descuento['codigo'],
          '{{cantidad}}'=>$descuento['cantidad'],
          '{{producto_id}}'=>$descuento['producto_id'],
          '{{producto_nombre}}'=>$descuento['producto_nombre'],
          '{{descuento}}'=>$descuento['descuento'],
          '{{precio}}'=>$descuento['precio'],
          '{{fecha_inicio}}'=>$descuento['fecha_inicio'],
          '{{fecha_fin}}'=>$descuento['fecha_fin'],
          '{{image}}'=>$descuento['imagen'],
          '{{descuento-view}}'=>"index.php?ctrl=descuentos&action=view&id=".$descuento['id'],
          '{{descuento-edit}}'=>"index.php?ctrl=descuentos&action=edit&id=".$descuento['id'],
        ];
        $row = strtr($row,$diccionary);
        $table .= $row;
      }

      $view = str_replace($area, $table, $view);
      echo $view;
    }

    public function showDescuento($id) {
      if($this->isInt($id)) {
        $descuento = $this->model->getOne($id);

        if ($descuento) {
          $table = "";
          $view = $this->getView("descuento-view", 'view', $this->modal);
          $content = $view;

          $diccionary = [
            '{{id}}'=>$descuento['id'],
            '{{codigo}}'=>$descuento['codigo'],
            '{{cantidad}}'=>$descuento['cantidad'],
            '{{producto_id}}'=>$descuento['producto_id'],
            '{{producto_nombre}}'=>$descuento['producto_nombre'],
            '{{descuento}}'=>$descuento['descuento'],
            '{{precio}}'=>$descuento['precio'],
            '{{fecha_inicio}}'=>$descuento['fecha_inicio'],
            '{{fecha_fin}}'=>$descuento['fecha_fin'],
            '{{image}}'=>$descuento['imagen'],
            '{{descuento-view}}'=>"index.php?ctrl=descuentos&action=view&id=".$descuento['id'],
            '{{descuento-edit}}'=>"index.php?ctrl=descuentos&action=edit&id=".$descuento['id'],
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

    public function editDescuento($id) {
      if($this->isInt($id)) {
        $descuento = $this->model->getOne($id);
        $imagen = $descuento['imagen'];

        if ($descuento) {
          if(empty($_POST)) {
            $table = "";
            $diccionary = [
              '{{id}}'=>$descuento['id'],
              '{{codigo}}'=>$descuento['codigo'],
              '{{cantidad}}'=>$descuento['cantidad'],
              '{{producto_id}}'=>$descuento['producto_id'],
              '{{producto_nombre}}'=>$descuento['producto_nombre'],
              '{{descuento}}'=>$descuento['descuento'],
              '{{precio}}'=>$descuento['precio'],
              '{{fecha_inicio}}'=>$descuento['fecha_inicio'],
              '{{fecha_fin}}'=>$descuento['fecha_fin'],
              '{{image}}'=>$descuento['imagen'],
              '{{descuento-view}}'=>"index.php?ctrl=descuentos&action=view&id=".$descuento['id'],
              '{{descuento-edit}}'=>"index.php?ctrl=descuentos&action=edit&id=".$descuento['id'],
            ];
            $this->showForm($id,'descuento-edit',$this->modal,$diccionary);
          } else {
            $errors = [];
            $descuento = [
              'id' => $id
            ];

            if($this->isCode($_POST['codigo'])) {
              $descuento['codigo'] = $_POST['codigo'];
            } else {
              $errors['codigo'] = 'El código es incorrecto. Debe contener letras, dígitos y guiones.';
            }

            if($this->isInt($_POST['cantidad'])) {
              $descuento['cantidad'] = $_POST['cantidad'];
            } else {
              $errors['cantidad'] = 'El código es incorrecto. Debe contener letras, dígitos y guiones.';
            }

            if($this->isInt($_POST['producto'])) {
              $descuento['producto'] = $_POST['producto'];
            } else {
              $errors['producto'] = 'El código es incorrecto. Debe contener letras, dígitos y guiones.';
            }

            if($this->isInt($_POST['descuento'])) {
              $descuento['descuento'] = $_POST['descuento'];
            } else {
              $errors['descuento'] = 'El código es incorrecto. Debe contener letras, dígitos y guiones.';
            }

            if($this->isDate($_POST['fecha_inicio'])) {
              $descuento['fecha_inicio'] = $_POST['fecha_inicio'];
            } else {
              $errors['fecha_inicio'] = 'La fecha es incorrecta. El formato es dd/mm/aaaa.';
            }

            if($this->isDate($_POST['fecha_fin'])) {
              $descuento['fecha_fin'] = $_POST['fecha_fin'];
            } else {
              $errors['fecha_fin'] = 'La fecha es incorrecta. El formato es dd/mm/aaaa.';
            }

            if($this->isNumber($_POST['precio'])) {
              $descuento['precio'] = $_POST['precio'];
            } else {
              $errors['precio'] = 'La fecha es incorrecta. El formato es dd/mm/aaaa.';
            }

            if($_FILES['image']['tmp_name'] != '') {
              $descuento['imagen']  = $this->uploadImage($id, $this->single, $_FILES['image'],$this->url);
            } else {
              $descuento['imagen'] = $imagen;
            }

            if(empty($errors)) {
              $this->model->update($descuento);
              header ("Location: index.php?ctrl=descuentos&action=view&id=".$id);
            } else {
              $this->editDescuento($id);
            }
          }
        }


        
      }
    }
  }
?>