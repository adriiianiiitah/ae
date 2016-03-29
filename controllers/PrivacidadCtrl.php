<?php
  require_once('StandardCtrl.php');

  class PrivacidadCtrl extends StandardCtrl {
    private $model;

    public function __construct() {
      parent::__construct();
    }

    public function execute() {
      if(isset($_GET['action'])) {
        $action = $_GET['action'];

        switch ($action) {
          case '':
          case 'aviso_privacidad':
            $this->showAviso();
            break;
          case 'terminos_y_condiciones':
            $this->showTerminos();
            break;
          case 'cambios_y_devoluciones':
            $this->showCambios();
            break;
          default:
            http_response_code(404);
            break;
        } 
      }
      else {
          $this->showAviso();
        }
    }

    public function showAviso() {
      $header = file_get_contents("views/header.html");
      $menu =  file_get_contents("views/menu.html");
      $view =  file_get_contents("views/aviso.html");
      $footer = file_get_contents("views/footer.html");
      echo $header.$menu.$view.$footer;
    }

    public function showTerminos() {
      $header = file_get_contents("views/header.html");
      $menu =  file_get_contents("views/menu.html");
      $view =  file_get_contents("views/terminos.html");
      $footer = file_get_contents("views/footer.html");
      echo $header.$menu.$view.$footer;
    }

    public function showCambios() {
      $header = file_get_contents("views/header.html");
      $menu =  file_get_contents("views/menu.html");
      $view =  file_get_contents("views/cambios.html");
      $footer = file_get_contents("views/footer.html");
      echo $header.$menu.$view.$footer;
    }

  }

?>