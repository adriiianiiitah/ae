<?php
  require_once('StandardCtrl.php');

  class DescuentosCtrl extends StandardCtrl {
    private $model;
    public $table_name;
    public $single;
    public $url;
    public $modal;
    public $image;

    public function __construct() {
      parent::__construct();
      require_once('./models/DescuentosMdl.php');
      $this->model = new DescuentosMdl();
      $this->table_name = 'descuentos';
      $this->single = 'descuento';
      $this->url = 'images/descuentos/';
      $this->image = 'images/descuentos/descuento.png';
      $this->modal = 'modal-delete-descuento';
    }

    public function execute() {
      if(isset($_GET['action'])) {
        $action = $_GET['action'];

        switch ($action) {
          case 'show':
            if(isset($_GET['id']) && !empty($_GET['id']))
              $this->showDescuento($_GET['id']);
            else
              $this->showErrorPage();
            break;
          default:
            $this->showErrorPage();
            break;
        } 
      }
      else {
        $this->showErrorPage();
      }
    }

    public function getDictionary($descuento) {
      return  array(
        '{{id}}'              =>$descuento['id'],
        '{{codigo}}'          =>$descuento['codigo'],
        '{{cantidad}}'        =>$descuento['cantidad'],
        //'{{producto_id}}'     =>$descuento['producto_id'],
        '{{producto}}'        =>$descuento['producto_nombre'],
        '{{descuento}}'       =>$descuento['descuento'],
        '{{precio}}'          =>$descuento['precio'],
        '{{fecha_inicio}}'    =>$descuento['fecha_inicio'],
        '{{fecha_fin}}'       =>$descuento['fecha_fin'],
        '{{image}}'           =>$descuento['imagen'],
        '{{descuento-view}}'  =>"index.php?ctrl=descuentos&action=view&id=".$descuento['id'],
        '{{descuento-edit}}'  =>"index.php?ctrl=descuentos&action=edit&id=".$descuento['id'],
      );
    }

    public function showDescuento($id) {
      if($this->isInt($id)) {
        $descuento = $this->model->getOne($id);

        if ($descuento) {
          $table = "";
          $view = $this->getView("descuento");

          $content = $view;
          $diccionary = $this->getDictionary($descuento);
          $content = strtr($view,$diccionary);
          $view = str_replace($view, $table, $content);
          $this->showView($view);
        } else {
          $this->showErrorPage();
        }
      } else {
        $this->showErrorPage();
      }
    }

  }
?>