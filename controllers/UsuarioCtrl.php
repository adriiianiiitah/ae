<?php
  require_once('admin/controllers/StandardCtrl.php');

  class UsuarioCtrl extends StandardCtrl {
    private $model;
    public $table_name;
    public $single;
    public $url;

    public function __construct() {
      parent::__construct();
      require_once('admin/models/UsuariosMdl.php');
      $this->model = new UsuariosMdl();
      $this->table_name = 'usuarios';
      $this->single = 'usuario';
      $this->url = 'images/usuarios/';
    }

    public function execute() {
      if(isset($_GET['action'])) {
      $action = $_GET['action'];

      switch ($action) {
        case '':
        $this->showUsuario();
        break;
        case 'registro':
        $this->createUsuario();
        break;
        case 'recuperar':
        $this->showTokenForm();
        break;

        default:
        http_response_code(404);
        break;
        } 
      }
      else {
      $this->showUsuario();
      }
    }

    public function createUsuario() {
      $header = file_get_contents("views/header.html");
      $menu =  file_get_contents("views/menu.html");
      $modal_address =  file_get_contents("views/modal_address.html");
      $modal_phone =  file_get_contents("views/modal_phone.html");
      $view =  file_get_contents("views/register.html");
      $footer = file_get_contents("views/footer.html");
      echo $header.$menu.$modal_address.$modal_phone.$view.$footer;
    }

    public function showTokenForm() {
      $header = file_get_contents("views/header.html");
      $menu =  file_get_contents("views/menu.html");
      $view =  file_get_contents("views/forgot-password.html");
      $footer = file_get_contents("views/footer.html");
      echo $header.$menu.$view.$footer;
    }
  }
?>