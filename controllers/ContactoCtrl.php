<?php
  require_once('StandardCtrl.php');

  class ContactoCtrl extends StandardCtrl {
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
            $this->showContacto();
            break;
          
          default:
            $this->showErrorPage();
            break;
        } 
      }
      else {
        $this->showContacto();
      }
    }

    public function showContacto() {
      $view = $this->getView("contacto");
      $this->showView($view);
    }

  }

?>