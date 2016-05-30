<?php
  require_once('StandardCtrl.php');

  class IndexCtrl extends StandardCtrl {
    private $model;

    public function __construct() {
      parent::__construct();
      require_once('./models/UsuariosMdl.php');
      $this->model = new UsuariosMdl();
    }

    public function execute() {
      if(isset($_GET['action'])) {
        $action = $_GET['action'];

        switch ($action) {
          case '':
          case 'index':
            $this->showIndex();
            break;
          
          default:
            $this->showErrorPage();
            break;
        } 
      }
      else {
          $this->showIndex();
        }
    }

    public function showIndex() { 
      if($this->isLogin()) {
        $view = $this->getView("index", '');
        echo $view;
      } else {
        if(empty($_POST)) {
          $view = $this->getViewLogin();

          echo $view;
        } else {
          $errors = array();

          if($this->isEmail($_POST['correo'])) {
            $credenciales['correo'] = $_POST['correo'];
          } else {
            $errors['correo'] = 'El correo es incorrecto. Debe formato es usuario@mail.com.';
          }

          if($this->isPassword($_POST['contrasena'])) {
            $credenciales['contrasena'] = $this->cryptPassword($_POST['contrasena']);
          } else {
            $credenciales['contrasena'] = '';
            $errors['contrasena'] = 'La contraseña es incorrecta. Debe contener mayúsculas, minúsculas, dígitos y caracteres de puntuación.';
          }


          if(empty($errors)) {
            $usuario = $this->model->getFirstUsuarioByCredenciales($credenciales['correo'],$credenciales['contrasena']);
            if($usuario) {
        
              $_SESSION['usuario']          = $usuario;
              $_SESSION['id']               = $usuario['id'];
              $_SESSION['nombre']           = $usuario['nombre'];
              $_SESSION['apellidos']        = $usuario['apellidos'];
              $_SESSION['correo']           = $usuario['correo'];
              $_SESSION['fecha_nacimiento'] = $usuario['fecha_nacimiento'];
              $_SESSION['genero']           = $usuario['genero'];
              $_SESSION['contrasena']       = $usuario['contrasena'];
              $_SESSION['imagen']           = $usuario['imagen'];
              $_SESSION['rol_id']           = $usuario['rol_id'];
              $_SESSION['rol']              =  $usuario['rol_nombre'];
              unset($errors);

              header ("Location: index.php");
            } else {
              $errors['credenciales'] = 'Las credenciales no coinciden';
              var_dump($errors);
              echo '<br><pre>';
              var_dump($credenciales);
              exit();
            }
          }
        }
      }

      //echo $view;
    }
  }
?>