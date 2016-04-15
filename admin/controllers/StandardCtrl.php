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

    public function getRow($view) {
      $start = strrpos($view,'<!--row-->');
      $end = strrpos($view,'<!--.row-->') +11;
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
