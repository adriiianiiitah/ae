<?php
  require_once('StandardCtrl.php');

  class PrivacidadCtrl extends StandardCtrl {
    public $model;

    public function __construct() {
      parent::__construct();
      require_once('./models/HomeMdl.php');
      $this->model = new HomeMdl();
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
            $this->showErrorPage();
            break;
        } 
      }
      else {
        $this->showAviso();
      }
    }

    public function showAviso() {
      $view = $this->getView("aviso");
      $view = $this->showDataMenu($view);
      $this->showView($view);
    }

    public function showTerminos() {
      $view = $this->getView("terminos");
      $view = $this->showDataMenu($view);
      $this->showView($view);
    }

    public function showCambios() {
      $view = $this->getView("cambios");
      $view = $this->showDataMenu($view);
      $this->showView($view);
    }

  }

?>