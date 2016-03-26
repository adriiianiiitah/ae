<?php
  require_once('StandardCtrl.php');

  class OfertasCtrl extends StandardCtrl {
    private $model;
    public $table;
    public $url;
    public $modal;

    public function __construct() {
      //parent::__construct();
      require_once('./models/OfertasMdl.php');
      $this->model = new OfertasMdl();
      $this->table_name = 'ofertas';
      $this->url = 'images/ofertas';
      $this->modal = 'modal-delete-oferta';
    }

    public function execute() {
      if(isset($_GET['action'])) {
        $action = $_GET['action'];

        switch ($action) {
          case '':
          case 'list': 
            $this->showOfertas();
            break;
          case 'view':
            if(isset($_GET['id']) && !empty($_GET['id']))
              $this->showOferta($_GET['id']);
            else
              $this->showErrorPage();
            break;
          case 'edit':
            if(isset($_GET['id']) && !empty($_GET['id']))
              $this->editOferta($_GET['id']);
            else
              $this->showErrorPage();
            break;
          
          default:
            $this->showErrorPage();
            break;
        } 
      }
      else {
          $this->showOfertas();
        }
    }

    public function showOfertas() {
      //$Ofertas = $this->model->getAll();
      $view = $this->getView("ofertas", 'list', $this->modal);
      $area = $this->getRow($view);
      $table = "";

      $oferta = [
        'id'            =>'1',
        'codigo'        =>'123456',
        'cantidad'      =>'3',
        'producto'      =>'6549885',
        'precio'        =>'123.50',
        'de'            =>'10/02/2015',
        'hasta'         =>'10/03/2015'
      ];

      //foreach ($Ofertas as $oferta) {
        $area = $row = $this->getRow($view);
        $diccionary = array(
          '{{id}}'=>$oferta['id'],
          '{{codigo}}'=>$oferta['codigo'],
          '{{cantidad}}'=>$oferta['cantidad'],
          '{{producto}}'=>$oferta['producto'],
          '{{precio}}'=>$oferta['precio'],
          '{{de}}'=>$oferta['de'],
          '{{hasta}}'=>$oferta['hasta'],
          '{{oferta-view}}'=>"index.php?ctrl=ofertas&action=view&id=".$oferta['id'],
          '{{oferta-edit}}'=>"index.php?ctrl=ofertas&action=edit&id=".$oferta['id'],
        );
        $row = strtr($row,$diccionary);
        $table .= $row;
      //}

      $view = str_replace($area, $table, $view);
      echo $view;
    }

    public function showoferta($id) {
      //$oferta = $this->model->getOne($id);
      $oferta = [
        'id'            =>'1',
        'codigo'        =>'123456',
        'cantidad'      =>'3',
        'producto'      =>'6549885',
        'precio'        =>'123.50',
        'de'            =>'10/02/2015',
        'hasta'         =>'10/03/2015'
      ];
      $table = "";

      if($this->isInt($id)) {
        $view = $this->getView("oferta-view", 'view', $this->modal);

        $content = $view;
        $diccionary = array(
          '{{id}}'=>$oferta['id'],
          '{{codigo}}'=>$oferta['codigo'],
          '{{cantidad}}'=>$oferta['cantidad'],
          '{{producto}}'=>$oferta['producto'],
          '{{precio}}'=>$oferta['precio'],
          '{{de}}'=>$oferta['de'],
          '{{hasta}}'=>$oferta['hasta'],
          '{{oferta-view}}'=>"index.php?ctrl=ofertas&action=view&id=".$oferta['id'],
          '{{oferta-edit}}'=>"index.php?ctrl=ofertas&action=edit&id=".$oferta['id'],
        );
        $content = strtr($view,$diccionary);
        $view = str_replace($view, $table, $content);

        echo $view;
      } else {
        $this->showErrorPage();
        
      }
    }

    public function editoferta($id) {
      //$oferta = $this->model->getOne($id);
      //$image = $oferta['image'];
      $oferta = [
        'id'            =>'1',
        'codigo'        =>'123456',
        'cantidad'      =>'3',
        'producto'      =>'6549885',
        'precio'        =>'123.50',
        'de'            =>'10/02/2015',
        'hasta'         =>'10/03/2015'
      ];
      $table = "";

      if(empty($_POST)) {
        $diccionary = array(
          '{{id}}'=>$oferta['id'],
          '{{codigo}}'=>$oferta['codigo'],
          '{{cantidad}}'=>$oferta['cantidad'],
          '{{producto}}'=>$oferta['producto'],
          '{{precio}}'=>$oferta['precio'],
          '{{de}}'=>$oferta['de'],
          '{{hasta}}'=>$oferta['hasta'],
          '{{oferta-view}}'=>"index.php?ctrl=ofertas&action=view&id=".$oferta['id'],
          '{{oferta-edit}}'=>"index.php?ctrl=ofertas&action=edit&id=".$oferta['id'],
        );
        $this->showForm($id,'oferta-edit',$this->modal,$diccionary);//
        //$this->showFormEdit($id,$oferta);
      } else {
        $codigo = $_POST['codigo'];
        $cantidad = $_POST['cantidad'];
        $producto = $_POST['producto'];
        $precio = $_POST['precio'];
        $de = $_POST['de'];
        $hasta = $_POST['hasta'];
        
        $descripcion = $_POST['descripcion'];

        if($_FILES['image']['tmp_name'] != '')
          $image = $this->uploadImage($id, $this->table_name, $_FILES['image'],$this->url);

        //$this->model->update($id,$codigo,$nombre,$descripcion,$image);

        $this->showoferta($id);
      }
    }
  }
?>