<?php
  require_once('StandardCtrl.php');

  class UsuariosCtrl extends StandardCtrl {
    private $model;
    public $table_name;
    public $single;
    public $url;
    public $modal;
    public $modals;
    public $image;

    public function __construct() {
      parent::__construct();
      require_once('./models/UsuariosMdl.php');
      $this->model = new UsuariosMdl();
      $this->table_name = 'usuarios';
      $this->single = 'usuario';
      $this->url = 'images/usuarios/';
      $this->image = 'images/usuarios/usuario.png';
      $this->modal = 'modal-delete-usuario';
      $this->modals = array(
        'modal_address',
        'modal_phone'
      );
    }

    public function execute() {
      if(isset($_GET['action'])) {
        $action = $_GET['action'];

        switch ($action) {
          case '':
          case 'list':
            $this->showUsuarios();
            break;
          case 'view':
            if(isset($_GET['id']) && !empty($_GET['id']))
              $this->showUsuario($_GET['id']);
            else
              $this->showErrorPage();
            break;
          case 'edit':
            if(isset($_GET['id']) && !empty($_GET['id']))
              $this->editUsuario($_GET['id']);
            else
              $this->showErrorPage();
            break;
          case 'create':
              $this->createUsuario();
            break;
          case 'roles':
              $roles = $this->model->getAllRoles();
              $data = $this->loadDataRoles($roles);
              break;
          default:
            $this->showErrorPage();
            break;
        } 
      }
      else {
          $this->showUsuarios();
        }
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
        '{{id}}'                  =>$usuario['id'],
        '{{nombre}}'              =>$usuario['nombre'],
        '{{apellidos}}'           =>$usuario['apellidos'],
        '{{correo}}'              =>$usuario['correo'],
        '{{rol_id}}'              =>$usuario['rol_id'],
        '{{rol_nombre}}'          =>$usuario['rol_nombre'],
        '{{rol}}'                 =>$usuario['rol_nombre'],
        '{{genero}}'              =>$usuario['genero'],
        '{{contrasena}}'          =>'',
        '{{contrasena_required}}' =>$usuario['contrasena_required'],
        '{{fecha_nacimiento}}'    =>$usuario['fecha_nacimiento'],
        '{{image}}'               =>$usuario['imagen'],
        '{{femenino}}'            =>$usuario['femenino'],
        '{{masculino}}'           =>$usuario['masculino'],
        '{{usuario-view}}'        =>"index.php?ctrl=usuarios&action=view&id=".$usuario['id'],
        '{{usuario-edit}}'        =>"index.php?ctrl=usuarios&action=edit&id=".$usuario['id'],
      );
    }

    public function showUsuarios() {
      $usuarios = $this->model->getAll();
      $view = $this->getView("usuarios", 'list', $this->modal);
      $area = $this->getRow($view);
      $table = "";

      foreach ($usuarios as $usuario) {
        $area = $row = $this->getRow($view);
        $diccionary = $this->getDictionary($usuario);
        $row = strtr($row,$diccionary);
        $table .= $row;
      } 

      $view = str_replace($area, $table, $view);
      echo $view;
    }

    public function showUsuario($id) {
      if($this->isInt($id)) {
        $usuario = $this->model->getOne($id);

        if($usuario) {
          $table = "";
          $view = $this->getView("usuario-view", 'view', $this->modal);

          $content = $view;
          $diccionary = $this->getDictionary($usuario);
          $content = strtr($view,$diccionary);
          $view = str_replace($view, $table, $content);

          $domicilios = $this->model->getDomicilios($id);
          $telefonos = $this->model->getTelefonos($id);
          $data = $this->getDataDomicilios($domicilios);
          $view = $this->showData($view,$data,DOMICILIO_TAG_START,DOMICILIO_TAG_END);
          $data = $this->getDataTelefonos($telefonos);
          $view = $this->showData($view,$data,TELEFONO_TAG_START,TELEFONO_TAG_END);
 
          echo $view;
        } else {
          $this->showErrorPage();
        }
      } else {
        $this->showErrorPage();
        
      }
    }

    public function createUsuario() {
      $usuario = array(
        'id'                  =>'',
        'nombre'              =>'',
        'apellidos'           =>'',
        'correo'              =>'',
        'rol_id'              =>'',
        'rol_nombre'          =>'',
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
        $view = $this->getViewForm($id,'usuario-edit',$this->modal,$diccionary,$this->modals);


        //$roles = $this->model->getAllRoles();
        //$data = $this->getDataRoles($roles);
        //$view = $this->showData($view,$data,ROL_TAG_START,ROL_TAG_END);
        $domicilios = $this->model->getDomicilios($id);
        $telefonos = $this->model->getTelefonos($id);
        $data = $this->getDataDomicilios($domicilios);
        $view = $this->showData($view,$data,DOMICILIO_TAG_START,DOMICILIO_TAG_END);
        $data = $this->getDataTelefonos($telefonos);
        $view = $this->showData($view,$data,TELEFONO_TAG_START,TELEFONO_TAG_END);

        $paises = $this->model->getAllPaises();
        $data = $this->getDataPaises($paises);
        $view = $this->showData($view,$data,PAIS_TAG_START,PAIS_TAG_END);

        $estados = $this->model->getAllEstados();
        $data = $this->getDataEstados($estados);
        $view = $this->showData($view,$data,ESTADO_TAG_START,ESTADO_TAG_END);

        $municipios = $this->model->getAllMunicipios();
        $data = $this->getDataMunicipios($municipios);
        $view = $this->showData($view,$data,MUNICIPIO_TAG_START,MUNICIPIO_TAG_END);

        echo $view;
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

        if($this->isInt($_POST['rol'])) {
          $usuario['rol'] = $_POST['rol'];
        } else {
          $errors['rol'] = 'El rol es incorrecto. Algo salió mal.';
        }

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
          
          header ("Location: index.php?ctrl=usuarios&action=view&id=".$id);
        } else {
          //$this->createUsuario();
          var_dump($errors);
          exit();
        }
      }
    }

    public function editUsuario($id) {
      if($this->isInt($id)) {
        $usuario = $this->model->getOne($id);
        $imagen = $usuario['imagen'];
        $contrasena = $usuario['contrasena'];

        if ($usuario) {
          if(empty($_POST)) {
            $table = "";
            $usuario['contrasena_required'] = 'editar';
            $diccionary = $this->getDictionary($usuario);
            $view = $this->getViewForm($id,'usuario-edit',$this->modal,$diccionary,$this->modals);

            //$roles = $this->model->getAllRoles();
            //$data = $this->getDataRoles($roles);
            //$view = $this->showData($view,$data,ROL_TAG_START,ROL_TAG_END);
            $domicilios = $this->model->getDomicilios($id);
            $telefonos = $this->model->getTelefonos($id);
            $data = $this->getDataDomicilios($domicilios);
            $view = $this->showData($view,$data,DOMICILIO_TAG_START,DOMICILIO_TAG_END);
            $data = $this->getDataTelefonos($telefonos);
            $view = $this->showData($view,$data,TELEFONO_TAG_START,TELEFONO_TAG_END);

            $paises = $this->model->getAllPaises();
            $data = $this->getDataPaises($paises);
            $view = $this->showData($view,$data,PAIS_TAG_START,PAIS_TAG_END);

            $estados = $this->model->getAllEstados();
            $data = $this->getDataEstados($estados);
            $view = $this->showData($view,$data,ESTADO_TAG_START,ESTADO_TAG_END);

            $municipios = $this->model->getAllMunicipios();
            $data = $this->getDataMunicipios($municipios);
            $view = $this->showData($view,$data,MUNICIPIO_TAG_START,MUNICIPIO_TAG_END);

            echo $view;
          } else {
            $errors = array();
            $usuario = array(
              'id' => $id
            );

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

            if(!($usuario['contrasena'] == '') && !($usuario['repetir_contrasena']=='')) {
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
            } else {
              $usuario['contrasena'] = $usuario['repetir_contrasena'] = $contrasena;
            }

            if($this->isEmail($_POST['correo'])) {
              $usuario['correo'] = $_POST['correo'];
            } else {
              $errors['correo'] = 'El correo es incorrecto. Debe formato es usuario@mail.com.';
            }

            if($this->isInt($_POST['rol'])) {
              $usuario['rol'] = $_POST['rol'];
            } else {
              $errors['rol'] = 'El rol es incorrecto. Algo salió mal.';
            }

            if($this->isDate($_POST['fecha_nacimiento'])) {
              $usuario['fecha_nacimiento'] = $_POST['fecha_nacimiento'];
            } else {
              $errors['fecha_nacimiento'] = 'La fecha es incorrecta. El formato es dd/mm/aaaa.';
            }

            if($this->isGender($_POST['genero'])) {
              $usuario['genero'] = $_POST['genero'];
            } else {
              $errors['genero'] = 'El género es incorrecto. Algo salió mal.';
            }

            if($_FILES['image']['tmp_name'] != '') {
              $usuario['imagen']  = $this->uploadImage($id, $this->single, $_FILES['image'],$this->url);
            } else {
              $usuario['imagen'] = $imagen;
            }

            if(empty($errors)) {
              $this->model->update($usuario);
              header ("Location: index.php?ctrl=usuarios&action=view&id=".$id);
            } else {
              $this->editUsuario($id);
            }
          }
        }
      }
    }


  }
?>

















