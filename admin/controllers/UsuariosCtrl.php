<?php
  require_once('StandardCtrl.php');

  class UsuariosCtrl extends StandardCtrl {
    private $model;
    public $table_name;
    public $url;
    public $modal;

    public function __construct() {
      parent::__construct();
      require_once('./models/UsuariosMdl.php');
      $this->model = new UsuariosMdl();
      $this->table_name = 'usuarios';
      $this->url = 'images/usuarios';
      $this->modal = 'modal-delete-usuario';
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
            if(isset($_GET['id']) && !empty($_GET['id']) && is_int((int)$_GET['id']))
              $this->showUsuario($_GET['id']);
            else
              $this->showErrorPage();
            break;
          case 'edit':
            if(isset($_GET['id']) && !empty($_GET['id']) && is_int((int)$_GET['id']))
              $this->editUsuario($_GET['id']);
            else
             // $this->showErrorPage();
               echo 's';
            break;
          
          default:
            $this->showUsuarios();
            break;
        } 
      }
      else {
          $this->showUsuarios();
        }
    }
/*
    public function getRow($view) {
      $start = strrpos($view,'<!--row-->');
      $end = strrpos($view,'<!--.row-->') +11;
      $row = substr($view,$start,$end-$start);
      return $row;
    }
*/

    public function showUsuarios() {
      //$usuarios = $this->model->getAll();
      $view = $this->getView("usuarios", 'list', $this->modal);
      $area = $this->getRow($view);
      $table = "";

      //foreach ($usuarios as $usuario) {
        $usuario = [
          'id'=>'1',
          'nombre'=>'Ana',
          'apellido'=>'Díaz',
          'correo'=>'ana@mail.com',
          'rol'=>'usuario',
          'telefono'=>'361511145',
          'genero'=>'Femenino',
          'fecha_nacimiento'=>'15/04/2000'
        ];
        $area = $row = $this->getRow($view);
        $diccionary = array(
          '{{id}}'=>$usuario['id'],
          '{{nombre}}'=>$usuario['nombre'],
          '{{apellido}}'=>$usuario['apellido'],
          '{{correo}}'=>$usuario['correo'],
          '{{rol}}'=>$usuario['rol'],
          '{{telefono}}'=>$usuario['telefono'],
          '{{genero}}'=>$usuario['genero'],
          '{{fecha_nacimiento}}'=>$usuario['fecha_nacimiento'],
          '{{usuario-view}}'=>"index.php?ctrl=usuarios&action=view&id=1".$usuario['id'],
          '{{usuario-edit}}'=>"index.php?ctrl=usuarios&action=edit&id=1".$usuario['id'],
        );
        $row = strtr($row,$diccionary);
        $table .= $row;
      //}success 

      $view = str_replace($area, $table, $view);
      echo $view;
    }

    public function showUsuario($id) {
      //$usuario = $this->model->getOne($id);
      $usuario = [
        'id'=>'1',
        'nombre'=>'Ana',
        'apellido'=>'Díaz',
        'correo'=>'ana@mail.com',
        'rol'=>'usuario',
        'telefono'=>'361511145',
        'genero'=>'Femenino',
        'fecha_nacimiento'=>'15/04/2000'
      ];

      if($this->isInt($id)) {
        
        $view = $this->getView("usuario-view", 'view', $this->modal);

        $content = $view;
        $diccionary = array(
          '{{id}}'=>$usuario['id'],
          '{{nombre}}'=>$usuario['nombre'],
          '{{apellido}}'=>$usuario['apellido'],
          '{{correo}}'=>$usuario['correo'],
          '{{rol}}'=>$usuario['rol'],
          '{{telefono}}'=>$usuario['telefono'],
          '{{genero}}'=>$usuario['genero'],
          '{{fecha_nacimiento}}'=>$usuario['fecha_nacimiento'],
          '{{usuario-view}}'=>"index.php?ctrl=usuarios&action=view&id=1".$usuario['id'],
          '{{usuario-edit}}'=>"index.php?ctrl=usuarios&action=edit&id=1".$usuario['id'],
        );
        $content = strtr($view,$diccionary);
        $view = str_replace($view, $table, $content);

        echo $view;
      } else {
        $this->showErrorPage();
        
      }
    }

    public function editUsuario($id) {
      //$category = $this->model->getOne($id);
      //$image = $category['image'];
      $usuario = [
        'id'=>'1',
        'nombre'=>'Ana',
        'apellido'=>'Díaz',
        'correo'=>'ana@mail.com',
        'rol'=>'usuario',
        'telefono'=>'361511145',
        'genero'=>'Femenino',
        'fecha_nacimiento'=>'15/04/2000'
      ];

      if(empty($_POST)) {
        $diccionary = array(
          '{{id}}'=>$usuario['id'],
          '{{nombre}}'=>$usuario['nombre'],
          '{{apellido}}'=>$usuario['apellido'],
          '{{correo}}'=>$usuario['correo'],
          '{{rol}}'=>$usuario['rol'],
          '{{telefono}}'=>$usuario['telefono'],
          '{{genero}}'=>$usuario['genero'],
          '{{fecha_nacimiento}}'=>$usuario['fecha_nacimiento'],
          '{{usuario-view}}'=>"index.php?ctrl=usuarios&action=view&id=1".$usuario['id'],
          '{{usuario-edit}}'=>"index.php?ctrl=usuarios&action=edit&id=1".$usuario['id'],
        );
        $this->showForm($id,'usuario-edit',$this->modal,$diccionary);
      } else {
        $code = $_POST['code'];
        $name = $_POST['name'];
        $description = $_POST['description'];

        if($_FILES['image']['tmp_name'] != '')
          $image = $this->uploadImage($id, $this->table_name, $_FILES['image'],$this->url);

        $this->model->update($id,$code,$name,$description,$image);

        $this->showUsuario($id);
      }
    }
  }
?>
