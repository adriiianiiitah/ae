<?php
  require_once('StandardCtrl.php');

  class HomeCtrl extends StandardCtrl {
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
          case 'home':
            $this->showHome();
            break;
          
          default:
            $this->showErrorPage();
            break;
        } 
      }
      else {
        $this->showHome();
      }
    }

    public function showHome() {
      $view = $this->getView("home");

      $descuentos = $this->model->getAllDescuentos();
      $data = $this->getDataDescuentos($descuentos);
      $view = $this->showData($view,$data,DESCUENTO_TAG_START,DESCUENTO_TAG_END);

      $ofertas = $this->model->getAllOfertas();
      $data = $this->getDataOfertas($ofertas);
      $view = $this->showData($view,$data,OFERTA_TAG_START,OFERTA_TAG_END);


      $catalogos = $this->model->getAllCatalogosByCathegory('Dama');
      $data = $this->getDataCatalogos($catalogos);
      $view = $this->showData($view,$data,CATALOGO_DAMA_TAG_START,CATALOGO_DAMA_TAG_END);

      $catalogos = $this->model->getAllCatalogosByCathegory('Caballero');
      $data = $this->getDataCatalogos($catalogos);
      $view = $this->showData($view,$data,CATALOGO_CABALLERO_TAG_START,CATALOGO_CABALLERO_TAG_END);

      $catalogos = $this->model->getAllCatalogosByCathegory('Infantil');
      $data = $this->getDataCatalogos($catalogos);
      $view = $this->showData($view,$data,CATALOGO_INFANTIL_TAG_START,CATALOGO_INFANTIL_TAG_END);

      $catalogos = $this->model->getAllCatalogosByCathegory('Especial');
      $data = $this->getDataCatalogos($catalogos);
      $view = $this->showData($view,$data,CATALOGO_ESPECIAL_TAG_START,CATALOGO_ESPECIAL_TAG_END);

      
      $this->showView($view);
    }

  }

?>