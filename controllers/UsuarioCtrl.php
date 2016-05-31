<?php
  require_once('StandardCtrl.php');

  class UsuarioCtrl extends StandardCtrl {
    private $model;
    public $table_name;
    public $single;
    public $url;
    public $image;

    public function __construct() {
      parent::__construct();
      require_once('admin/models/UsuariosMdl.php');
      $this->model = new UsuariosMdl();
      $this->table_name = 'usuarios';
      $this->single = 'usuario';
      $this->url = 'admin/images/usuarios/';
      $this->image = 'admin/images/usuarios/usuario.png';
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
        case 'logout';
        $this->logOut();
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

    public function logOut() {
      $this->closeSession();
      header ("Location: index.php");
    }

    public function getDictionary($usuario) {
      $usuario['femenino'] = (strcmp($usuario['genero'],FEMENINO) == 0) ? CHECKED: NO_CHECKED;
      $usuario['masculino'] = (strcmp($usuario['genero'],MASCULINO) == 0) ? CHECKED: NO_CHECKED;
      if(isset($usuario['contrasena_required']) && ($usuario['contrasena_required'] === 'create')) {
        $usuario['contrasena_required'] = 'data-required';
      } else {
        $usuario['contrasena_required'] = '';
      }
      return array(
        //'{{id}}'                  =>$usuario['id'],
        '{{nombre}}'              =>$usuario['nombre'],
        '{{apellidos}}'           =>$usuario['apellidos'],
        '{{correo}}'              =>$usuario['correo'],
        '{{rol_id}}'              =>$usuario['rol_id'],
        '{{genero}}'              =>$usuario['genero'],
        '{{contrasena}}'          =>'',
        '{{contrasena_required}}' =>$usuario['contrasena_required'],
        '{{fecha_nacimiento}}'    =>$usuario['fecha_nacimiento'],
        '{{image}}'               =>$usuario['imagen'],
        '{{femenino}}'            =>$usuario['femenino'],
        '{{masculino}}'           =>$usuario['masculino']
      );
    }

    public function createUsuario() {
      $usuario = array(
        //'id'                  =>'',
        'nombre'              =>'',
        'apellidos'           =>'',
        'correo'              =>'',
        'rol_id'              =>'3',
        'rol'                 =>'',
        'genero'              =>'',
        'contrasena'          =>'',
        'contrasena_required' =>'create',
        'fecha_nacimiento'    =>'',
        'femenino'            =>'',
        'masculino'           =>'',
        'imagen'              =>$this->image
      );
      $id ='';

      if(empty($_POST)) {
        $table = "";
        $diccionary = $this->getDictionary($usuario);
        $this->showForm('register',$diccionary);
      } else {
        $errors = array();

        if($this->isName($_POST['nombre'])) {
          $usuario['nombre'] = $_POST['nombre'];
        } else {
          $errors['nombre'] = 'El nombre es incorrecto. Debe contener solo letras y espacios.';
        }

        if($this->isLastName($_POST['apellidos'])) {
          $usuario['apellidos'] = $_POST['apellidos'];
        } else {
          $errors['apellidos'] = 'El apellido es incorrecto. Debe contener solo letras y espacios.';
        }

        if($this->isPassword($_POST['contrasena'])) {
          $usuario['contrasena'] = $_POST['contrasena'];
        } else {
          $usuario['contrasena'] = '';
          $errors['contrasena'] = 'La contraseña es incorrecta. Debe contener mayúsculas, minúsculas, dígitos y caracteres de puntuación.';
        }

        if($this->isPassword($_POST['repetir_contrasena'])) {
          $usuario['repetir_contrasena'] = $_POST['repetir_contrasena'];
          if ($this->equals($usuario['contrasena'],$usuario['repetir_contrasena'])) {
            $usuario['contrasena'] = $this->cryptPassword($_POST['contrasena']);
          } else {
            $errors['contrasena'] = 'La contraseñas deben coincidir.';
          }
        } else {
          $errors['contrasena'] = 'La contraseñas deben coincidir.';
        }

        if($this->isEmail($_POST['correo'])) {
          $usuario['correo'] = $_POST['correo'];
        } else {
          $errors['correo'] = 'El correo es incorrecto. Debe formato es usuario@mail.com.';
        }

        $usuario['rol'] = '1';
        
        if($this->isDate($_POST['fecha_nacimiento'])) {
          $usuario['fecha_nacimiento'] = $_POST['fecha_nacimiento'];
        } else {
          $errors['fecha_nacimiento'] = 'La fecha es incorrecta. El formato es aaaa/mm/dd.';
        }

        if($this->isGender($_POST['genero'])) {
          $usuario['genero'] = $_POST['genero'];
        } else {
          $errors['genero'] = 'El género es incorrecto. Algo salió mal.';
        }

        if(empty($errors)) {
          $id = $this->model->insert($usuario);
          if($_FILES['image']['tmp_name'] != '') {
            $usuario['imagen']  = $this->uploadImage($id, $this->single, $_FILES['image'],$this->url);
            $this->model->updateImage($id, $usuario['imagen']);
          }
          if($id) {
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
          }
          
          header ("Location: index.php");
        } else {
          $this->showErrorPage();
        }
      }
    }

    /*

    public function createUsuario() {
      $header = file_get_contents("views/header.html");
      $menu =  file_get_contents("views/menu.html");
      $modal_address =  file_get_contents("views/modal_address.html");
      $modal_phone =  file_get_contents("views/modal_phone.html");
      $view =  file_get_contents("views/register.html");
      $footer = file_get_contents("views/footer.html");
      echo $header.$menu.$modal_address.$modal_phone.$view.$footer;
    }
    */

    public function showTokenForm() {
      $header = file_get_contents("views/header.html");
      $menu =  file_get_contents("views/menu.html");
      $view =  file_get_contents("views/forgot-password.html");
      $footer = file_get_contents("views/footer.html");
      echo $header.$menu.$view.$footer;
    }
  }
?>