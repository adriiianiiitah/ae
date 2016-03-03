<?php
  /**
  * 
  */
  class StandardCtrl {
    
    function __construct() {

    }

    function isInt($value) {
      return is_int((int)$value);
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

    public function getView($view, $type ='', $modal ='') {
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
          $view =  file_get_contents("views/".$view.".html");
          $footer = file_get_contents("views/footer.html");
          return $navigation.$view.$footer;
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

    public function generateNameImage($id, $table_name, $extension, $url) {
      return  $url.$table_name.$id.$extension;
    }

    public function uploadImage($id, $table_name, $image, $url) {
      $original = $image['name'];
      $extension = $this->getFileExtension($original);
      $name = $this->generateNameImage($id, $table_name, $extension, $url);
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

    public function showForm($id,$view,$modal,$diccionary) {
          $view = $this->getView($view, 'edit', $modal);
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
