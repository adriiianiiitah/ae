<?php
  require_once('Constants.php');

  class StandardCtrl {
    
    function __construct() {

    }

    function cleanString($string) {
      if (!is_empty($string)) {
        //Strip whitespace from either side of a string 
        $value = trim($string);
        //Remove HTML Tags
        $value = filter_var($value, FILTER_SANITIZE_STRING);
        //Scape quotes
        $value = filter_var($value, FILTER_SANITIZE_MAGIC_QUOTES);
      }
      return $value;
    }

    function isInt($value) {
      //return is_int((int)$value);
      return preg_match(INT, $value);
    }

    function isArray($value) {
      return is_array($value);
    }

    function isAssoc($array) {
      foreach(array_keys($array) as $key) {
        if(!is_int($key)) return true;
      }
      return false;
    }

    function isNumber($value) {
      return preg_match(NUMBER, $value);
    }

    function isPassword($value) {
      return preg_match(PASS, $value);
    }

    function isEmail($value) {
      return preg_match(EMAIL, $value);
    }

    function isCode($value) {
      return preg_match(CODE, $value);
    }

    function isColor($value) {
      return preg_match(COLOR, $value);
    }

    function isName($value) {
      return preg_match(NAME, $value);
    }

    function isLastName($value) {
      return preg_match(LAST_NAME, $value);
    }

    function isAlphanumeric($value) {
      return preg_match(ALPHANUMERIC, $value);
    }

    function isDescription($value) {
      return preg_match(DESCRIPTION, $value);
    }

    function isPhone($value) {
      return preg_match(PHONE, $value);
    }

    function isDateTime($value) {
      return preg_match(DATE_TIME, $value);
    }

    function isDate($value) {
      return preg_match(DATE, $value);
    }

    function equals($value1, $value2) {
      return $value1 == $value2;
    }

    function isGender($value) {
      if($this->equals($value, FEMENINO) || $this->equals($value, MASCULINO)) {
        return true;
      }
      return false;
    }

    function isLogin(){
      if( isset($_SESSION['user']) )
        return true;
      return false;
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

    function cryptPassword($password) {
      return sha1(md5($password));
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

    function isResponse($result) {/*
      if(is_object($result)){
        return true;
      }*/
      if(is_array($result)){
        return true;
      }/*
      if(is_string($result)){
        return true;
      }
      if ($result !== false) {
        return true;
      }*/
      return false;
    }

    public function showErrorPage() {
      http_response_code(404);
      $navigation = file_get_contents("views/navigation.html");
      $view =  file_get_contents("views/404.html");
      $footer = file_get_contents("views/footer.html");
      echo $navigation.$view.$footer;
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

    public function getDataDomicilios($domicilios) {
      foreach ($domicilios as $domicilio) {
        $domicilio['primario'] = $domicilio['primario'] ? 'PRINCIPAL' : 'SECUNDARIO';
        $dictionary = array(
          '{{pais}}'=>$domicilio['pais_nombre'],
          '{{estado}}'=>$domicilio['estado_nombre'],
          '{{municipio}}'=>$domicilio['municipio_nombre'],
          '{{colonia}}'=>$domicilio['colonia'],
          '{{calle}}'=>$domicilio['calle'],
          '{{exterior}}'=>$domicilio['exterior'],
          '{{interior}}'=>$domicilio['interior'],
          '{{codigo_postal}}'=>$domicilio['codigo_postal'],
          '{{primario}}'=>$domicilio['primario']
        );
        $list[] = $dictionary;
      }
      return $list;
    }

    public function getDataTelefonos($telefonos) {
      foreach ($telefonos as $telefono) {
        $dictionary = array(
          '{{lada}}'=>$telefono['lada'],
          '{{telefono}}'=>$telefono['telefono'],
          '{{tipo}}'=>strtoupper($telefono['tipo'])
        );
        $list[] = $dictionary;
      }
      return $list;
    }

    public function getDataRoles($roles) {
      foreach ($roles as $rol) {
        $dictionary = array(
          '{{rol_id}}'=>$rol['rol_id'],
          '{{rol_nombre}}'=>$rol['rol_nombre']
        );
        $list[] = $dictionary;
      }
      return $list; 
    }

    public function getDataProductos($productos) {
      foreach ($productos as $producto) {
        $dictionary = array (
          '{{producto_id}}'=>$producto['producto_id'],
          '{{producto_nombre}}'=>$producto['producto_nombre']
        );
        $list[] = $dictionary;
      }
      return $list;
    }

    public function getView($view, $type ='', $modal ='', $modals = []) {
      switch ($type) {
        case 'view':
        case 'list':
          $navigation = file_get_contents("views/navigation.html");
          $modal =  file_get_contents("views/".$modal.".html");

          $view =  file_get_contents("views/".$view.".html");
          $footer = file_get_contents("views/footer.html");
          return $navigation.$modal.$view.$footer;
          break;
        default:
          $navigation = file_get_contents("views/navigation.html");
          $modals_ = '';
          foreach ($modals as $modal) {
            $modals_ .= file_get_contents("../views/".$modal.".html");
          }
          $view =  file_get_contents("views/".$view.".html");
          $footer = file_get_contents("views/footer.html");
          return $navigation.$modals_.$view.$footer;
          break;
      }
    }

    public function moveImage($temp, $name) {
      if(file_exists($name)) {
        unlink($name);
      }
      move_uploaded_file($temp, $name);
    }

    public function getFileExtension($file) {
      $name = explode(".",$file);
      return end($name);
    }

    public function generateNameImage($id, $single, $extension, $url) {
      return  $url.$single.$id.'.'.$extension;
    }

    public function uploadImage($id, $single, $image, $url) {
      $original = $image['name'];
      $extension = $this->getFileExtension($original);
      $name = $this->generateNameImage($id, $single, $extension, $url);
      $temporal = $image['tmp_name'];
      $final['original'] = $original;
      $final['name']= $name;
      $final['id'] = $id;  
      
      $this->moveImage($temporal, $name);
      return $name;
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

    public function showForm($id,$view,$modal,$diccionary,$modals = []) {
      $view = $this->getView($view, 'edit', $modal, $modals);
      $table = "";
      $content = $view;
      $content = strtr($view,$diccionary);
      $view = str_replace($view, $table, $content);

      echo $view;
    }

    public function getViewForm($id,$view,$modal,$diccionary,$modals = []) {
      $view = $this->getView($view, 'edit', $modal, $modals);
      $table = "";
      $content = $view;
      $content = strtr($view,$diccionary);
      $view = str_replace($view, $table, $content);

      return $view;
    }

    function footer() {
      /*
      if($this->isLogin()) {
        $footer = file_get_contents("views/footer.html");
        $usuario = $_SESSION['usuario'];
        $footer = str_replace("{usuario}", $usuario, $footer);
      } else {
        $footer = file_get_contents("views/pie.html");
      }*/
      $footer = file_get_contents("views/footer.html");
      return $footer;
    }

  }

?>
