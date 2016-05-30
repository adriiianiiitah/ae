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
            if(isset($_GET['id']) && !empty($_GET['id'])) {
              $this->showUsuario($_GET['id']);
            }
            else
              $this->showErrorPage();
            break;
          case 'edit':
            if(isset($_GET['id']) && !empty($_GET['id'])) {
              $this->editUsuario($_GET['id']);
            }
            else
              $this->showErrorPage();
            break;
          case 'create':
              $this->createUsuario();
            break;
          case 'delete':
              if(isset($_POST['usuario_id']) && !empty($_POST['usuario_id'])) {
                $this->deleteUsuario($_POST['usuario_id']);
              }
            break;
          case 'roles':
              $roles = $this->model->getAllRoles();
              $data = $this->loadDataRoles($roles);
              break;
          case 'municipios':
              $municipios = $this->model->getAllMunicipiosByEstado($_GET['estado_id']);
              $data = $this->loadDataMunicipios($municipios);
              break;
          case 'new-domicilio':
              if(isset($_GET['user_id']) && !empty($_GET['user_id'])) {
                $this->saveDomicilio($_GET['user_id']);
              }
            break;
          case 'delete-domicilio':
              if(isset($_POST['domicilio_id']) && !empty($_POST['domicilio_id'])) {
                $this->deleteDomicilio($_POST['domicilio_id']);
              }
            break;
          case 'new-telefono':
              if(isset($_GET['user_id']) && !empty($_GET['user_id'])) {
                $this->saveTelefono($_GET['user_id']);
              }
            break;
          case 'delete-telefono':
              if(isset($_POST['telefono_id']) && !empty($_POST['telefono_id'])) {
                $this->deleteTelefono($_POST['telefono_id']);
              }
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
        $view = $this->getViewForm($id,'usuario-create',$this->modal,$diccionary,$this->modals);


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

        $municipios = $this->model->getAllMunicipiosByEstado('1');
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
          $this->showErrorPage();
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

            if(!($_POST['contrasena'] == '') && !($_POST['repetir_contrasena']=='')) {
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
              $errors['fecha_nacimiento'] = 'La fecha es incorrecta. El formato es aaaa/mm/dd.';
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
              $this->showErrorPage();
            }
          }
        } else {
          $this->showErrorPage();
        }
      } else {
          $this->showErrorPage();
        }
    }

    public function deleteUsuario($id) {
      if($this->isInt($id)) {
        $this->model->delete($id);
      }
      header ("Location: index.php?ctrl=usuarios");
    }

    public function saveDomicilio($usuario_id) {
      $domicilio = array(
        'pais'              =>'',
        'estado'            =>'',
        'municipio'         =>'',
        'colonia'           =>'',
        'calle'             =>'',
        'exterior'          =>'',
        'interior'          =>'',
        'codigo_postal'     =>'',
        'principal'         =>'',

      );

      if (!empty($_POST)) {
        $errors = array();

        if($this->isInt($usuario_id)) {
          $domicilio['usuario'] = $usuario_id;
        } else {
          $errors['usuario'] = 'El usuario_id es incorrecto.';
        }

        if($this->isInt($_POST['pais'])) {
          $domicilio['pais'] = $_POST['pais'];
        } else {
          $errors['pais'] = 'El país es incorrecto.';
        }

        if($this->isInt($_POST['estado'])) {
          $domicilio['estado'] = $_POST['estado'];
        } else {
          $errors['estado'] = 'El estado es incorrecto.';
        }

        if($this->isInt($_POST['municipio'])) {
          $domicilio['municipio'] = $_POST['municipio'];
        } else {
          $errors['municipio'] = 'El municipio es incorrecto.';
        }

        if($this->isAlphanumeric($_POST['colonia'])) {
          $domicilio['colonia'] = $_POST['colonia'];
        } else {
          $errors['colonia'] = 'La colonia es incorrecta.';
        }

        if($this->isAlphanumeric($_POST['calle'])) {
          $domicilio['calle'] = $_POST['calle'];
        } else {
          $errors['calle'] = 'La calle es incorrecta.';
        }

        if($this->isAlphanumeric($_POST['exterior']) || empty($_POST['exterior'])) {
          $domicilio['exterior'] = $_POST['exterior'];
        } else {
          $errors['exterior'] = 'El número exterior es incorrecto.';
        }

        if($this->isAlphanumeric($_POST['interior']) || empty($_POST['interior'])) {
          $domicilio['interior'] = $_POST['interior'];
        } else {
          $errors['interior'] = 'El número interior es incorrecto.';
        }

        if($this->isAlphanumeric($_POST['codigo_postal']) || empty($_POST['codigo_postal'])) {
          $domicilio['codigo_postal'] = $_POST['codigo_postal'];
        } else {
          $errors['codigo_postal'] = 'El código postal es incorrecto.';
        }

        if($this->isInt($_POST['principal'])) {
          $domicilio['primario'] = $_POST['principal'];
        } else {
          $errors['principal'] = 'El principal es incorrecto.';
        }


        if(empty($errors)){
          $this->model->insertDomicilio($domicilio);
          header ("Location: index.php?ctrl=usuarios&action=view&id=".$usuario_id);
        } else {
          $this->showErrorPage();
        }
      }
    }

    public function saveTelefono($usuario_id) {
      $telefono = array(
        'lada'         =>'',
        'telefono'     =>''
      );

      if (!empty($_POST)) {
        $errors = array();

        if($this->isInt($usuario_id)) {
          $telefono['usuario'] = $usuario_id;
        } else {
          $errors['usuario'] = 'El usuario_id es incorrecto.';
        }

        if($this->isPhone($_POST['lada'])) {
          $telefono['lada'] = $_POST['lada'];
        } else {
          $errors['lada'] = 'La lada es incorrecta.';
        }

        if($this->isPhone($_POST['telefono'])) {
          $telefono['telefono'] = $_POST['telefono'];
        } else {
          $errors['telefono'] = 'El telefono es incorrecto.';
        }

        if(empty($errors)){
          $this->model->insertTelefono($telefono);
          header ("Location: index.php?ctrl=usuarios&action=view&id=".$usuario_id);
        } else {
          $this->showErrorPage();
        }
      }
    }

    public function deleteDomicilio($id) {
      if($this->isInt($id)) {
        $this->model->deleteDomicilio($id);
      }
    }

    public function deleteTelefono($id) {
      if($this->isInt($id)) {
        $this->model->deleteTelefono($id);
      }
    }



  }
?>

