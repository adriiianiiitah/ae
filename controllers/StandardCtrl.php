<?php
  require_once('./admin/controllers/Constants.php');

  class StandardCtrl {
    
    function __construct() {

    }

    function isAdmin(){
      if( isset($_SESSION['role']) && $_SESSION['role'] == 'admin' )
        return true;
      return false;
    }

    function isUser(){
      if( isset($_SESSION['role']) && $_SESSION['role'] == 'user' )
        return true;
      return false;
    }

    function isCostumer(){
      if( isset($_SESSION['role']) && $_SESSION['role'] == 'costumer' )
        return true;
      return false;
    }

    function closeSession(){
      session_unset();
      session_destroy();    
      setcookie(session_name(), '', time()-3600);
    }

    function openSesion($user_id){
      //ir a la base buscarlo validarlo
      //if ( no lo encontro )
        //return false;
      $_SESSION['user_id'] = $user_id;
      $_SESSION['role'] = $role;
      $_SESSION['user'] = $user;
      return true;
    }

    function isInt($value) {
      //return is_int((int)$value);
      return preg_match(INT, $value);
    }

    public function getRow($view, $start_tag = '', $end_tag = '' ) {
      if($start_tag == '') {
        $start = strrpos($view,ROW_TAG_START);
        $end = strrpos($view,ROW_TAG_END) +(strlen(ROW_TAG_START)+1);
      } else {
        $start = strrpos($view,$start_tag);
        $end = strrpos($view,$end_tag) +(strlen($start_tag)+1);
      }
      
      $row = substr($view,$start,$end-$start);
      return $row;
    }

    public function showData($view, $data,$start_tag = '', $end_tag = '') {
      $area = $row = $this->getRow($view,$start_tag,$end_tag);
      $table = "";

      foreach ($data as $diccionary) { 
        //$area = $row = $this->getRow($view,$start_tag,$end_tag);
        $line = strtr($row,$diccionary);
        $table .= $line;
      }
      $view = str_replace($area, $table, $view);
      return $view;
    }

    public function showDataMenu($view) {
      $subcategorias = $this->model->getAllSubcategoriasByCathegory('Dama');
      $data = $this->getDataSubcategorias($subcategorias);
      $view = $this->showData($view,$data,SUBCATEGORIA_DAMA_TAG_START,SUBCATEGORIA_DAMA_TAG_END);

      $subcategorias = $this->model->getAllSubcategoriasByCathegory('Caballero');
      $data = $this->getDataSubcategorias($subcategorias);
      $view = $this->showData($view,$data,SUBCATEGORIA_CABALLERO_TAG_START,SUBCATEGORIA_CABALLERO_TAG_END);

      $subcategorias = $this->model->getAllSubcategoriasByCathegory('Infantil');
      $data = $this->getDataSubcategorias($subcategorias);
      $view = $this->showData($view,$data,SUBCATEGORIA_INFANTIL_TAG_START,SUBCATEGORIA_INFANTIL_TAG_END);

      return $view;
    }

    public function getUrl($url,$image) {
      return $url.'/'.$image;
    }

    public function getDataDescuentos($descuentos) {
      $list = [];
      foreach ($descuentos as $descuento) {
        $dictionary = array(
          '{{descuento_imagen}}'=>$this->getUrl(IMAGE_URL,$descuento['imagen']),
          '{{descuento_url}}'=>'index.php?ctrl=descuentos&action=show&id='.$descuento['id']
        );
        $list[] = $dictionary;
      }
      return $list; 
    }

    public function getDataOfertas($ofertas) {
      $list = [];
      foreach ($ofertas as $oferta) {
        $dictionary = array(
          '{{oferta_imagen}}'=>$this->getUrl(IMAGE_URL,$oferta['imagen']),
          '{{oferta_url}}'=>'index.php?ctrl=ofertas&action=show&id='.$oferta['id']
        );
        $list[] = $dictionary;
      }
      return $list; 
    }

    public function getDataCategorias($categorias) {
      $list = [];
      foreach ($categorias as $categoria) {
        $active = $categoria['id'] == 1 ? 'active': '';
        $dictionary = array(
          '{{categoria}}'=>$categoria['nombre'],
          '{{class}}'=>$active
        );
        $list[] = $dictionary;
      }
      return $list; 
    }

    public function getDataSubcategorias($subcategorias) {
      $list = [];
      foreach ($subcategorias as $subcategoria) {
        $dictionary = array(
          '{{subcategoria}}'=>ucwords(strtolower($subcategoria['nombre'])),
          '{{subcategoria_url}}'=>'index.php?ctrl=productos&action=list&category='.strtolower($subcategoria['nombre'])
        );
        $list[] = $dictionary;
      }
      return $list; 
    }

    public function getDataCatalogos($catalogos) {
      $list = [];
      foreach ($catalogos as $catalogo) {
        $dictionary = array(
          '{{id}}'              =>$catalogo['id'],
          '{{catalogo}}'        =>$catalogo['nombre'],
          '{{fecha}}'           =>$catalogo['fecha'],
          '{{categoria}}'       =>$catalogo['categoria_nombre'],
          '{{image}}'           =>$this->getUrl(IMAGE_URL,$catalogo['imagen']),
          '{{pdf}}'             =>$this->getUrl(PDF_URL,$catalogo['pdf'])
        );
        $list[] = $dictionary;
      }
      return $list; 
    }

    public function showView($view) {
      echo $view;
    }

    public function getView($type) {
      $header = file_get_contents("views/header.html");
      $menu =  file_get_contents("views/menu.html");
      $view =  file_get_contents("views/".$type.".html");
      $footer = file_get_contents("views/footer.html");
      return $header.$menu.$view.$footer;
      /*
      switch ($type) {
        case 'home':
          $header = file_get_contents("views/header.html");
          $menu =  file_get_contents("views/menu.html");
          $view =  file_get_contents("views/home.html");
          $footer = file_get_contents("views/footer.html");
          return $header.$menu.$view.$footer;
          break;
        case 'contacto':
          $header = file_get_contents("views/header.html");
          $menu =  file_get_contents("views/menu.html");
          $view =  file_get_contents("views/contacto.html");
          $footer = file_get_contents("views/footer.html");
          return $header.$menu.$view.$footer;
        case 'descuento':
          $header = file_get_contents("views/header.html");
          $menu =  file_get_contents("views/menu.html");
          $view =  file_get_contents("views/descuento.html");
          $footer = file_get_contents("views/footer.html");
          return $header.$menu.$view.$footer;
          break;
        case 'descuento':
          $header = file_get_contents("views/header.html");
          $menu =  file_get_contents("views/menu.html");
          $view =  file_get_contents("views/descuento.html");
          $footer = file_get_contents("views/footer.html");
          return $header.$menu.$view.$footer;
          break;
        case 'producto':
          $header = file_get_contents("views/header.html");
          $menu =  file_get_contents("views/menu.html");
          $view =  file_get_contents("views/producto.html");
          $footer = file_get_contents("views/footer.html");
          return $header.$menu.$view.$footer;
        default:
          break;
      }*/
    }

    public function showErrorPage() {
      http_response_code(404);
      $header = file_get_contents("views/header.html");
      $menu =  file_get_contents("views/menu.html");
      $view =  file_get_contents("views/404.html");
      $footer = file_get_contents("views/footer.html");
      echo $header.$menu.$view.$footer;
    }

  }

?>