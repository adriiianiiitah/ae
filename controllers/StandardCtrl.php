<?php
  require_once('./admin/controllers/Constants.php');

  class StandardCtrl {
    
    function __construct() {

    }

    function isLogin(){
      if( isset($_SESSION['usuario']) )
        return true;
      return false;
    }

    function isAdmin(){
      if( isset($_SESSION['rol']) && $_SESSION['rol'] == 'administrador' )
        return true;
      return false;
    }

    function isUsuario(){
      if( isset($_SESSION['rol']) && $_SESSION['rol'] == 'usuario' )
        return true;
      return false;
    }

    function isGerente(){
      if( isset($_SESSION['rol']) && $_SESSION['rol'] == 'gerencial' )
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
      return preg_match(INT, $value);
    }

    function isAlphanumeric($value) {
      return preg_match(ALPHANUMERIC, $value);
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

    function isPassword($value) {
      return preg_match(PASS, $value);
    }

    function isEmail($value) {
      return preg_match(EMAIL, $value);
    }

    function equals($value1, $value2) {
      return $value1 == $value2;
    }

    function cryptPassword($password) {
      return sha1(md5($password));
    }

    function isDate($value) {
      return preg_match(DATE, $value);
    }

    function isGender($value) {
      if($this->equals($value, FEMENINO) || $this->equals($value, MASCULINO)) {
        return true;
      }
      return false;
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

    public function getUrlProducto($id) {
      return 'index.php?ctrl=productos&action=show&id='.$id;
    }

    public function getDataDescuentos($descuentos) {
      $list = array();
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
      $list = array();
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
      $list = array();
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
      $list = array();
      foreach ($subcategorias as $subcategoria) {
        $dictionary = array(
          '{{subcategoria}}'=>ucwords(strtolower($subcategoria['nombre'])),
          '{{subcategoria_url}}'=>'index.php?ctrl=productos&action=list&categoria='.strtolower($subcategoria['categoria_nombre']).'&subcategoria='.strtolower($subcategoria['nombre'])
        );
        $list[] = $dictionary;
      }
      return $list; 
    }

    public function getDataCatalogos($catalogos) {
      $list = array();
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

    public function getDataTallasByProducto($tallas) {
      $list = array();
      foreach ($tallas as $talla) {
        $dictionary = array(
          '{{talla_id}}'        =>$talla['talla_id'],
          '{{talla}}'           =>$talla['talla'],
          '{{stock}}'           =>$talla['stock'],
        );
        $list[] = $dictionary;
      }
      return $list; 
    }

    public function getDataCarrito() {
      $list = array();
      for($i = 0; $i < count($_SESSION['productos']); $i++) { 
        $dictionary = array(
          '{{id}}'              =>$_SESSION['productos'][$i]['id'],
          '{{nombre}}'          =>$_SESSION['productos'][$i]['nombre'],
          '{{cantidad}}'        =>'1',
          '{{precio}}'          =>$_SESSION['productos'][$i]['precio'],
          '{{subtotal}}'        =>$_SESSION['productos'][$i]['precio'],
          '{{image}}'           =>$this->getUrl(IMAGE_URL,$_SESSION['productos'][$i]['imagen'])
        );
        $list[] = $dictionary;
      }
      return $list; 
    }

    public function getAbsoluteUrl($param,$new_value) {
      $host= $_SERVER["HTTP_HOST"];
      $url= $_SERVER["REQUEST_URI"];
      $full_url = 'http://'.$host.$url;
    
      $start = strpos($full_url,$param);
      if($start) {
        $end = strrpos($full_url,'&',$start+1);
        if ($end) {
          $old_value = substr($full_url,$start,$end-$start);
          if($new_value != '') {
            $new_value = $param.$new_value;
          }
          
          $full_url = str_replace($old_value, $new_value, $full_url);
          return $full_url;
        } else {
          $old_value = substr($full_url,$start);
          if($new_value != '') {
            $new_value = $param.$new_value;
          }
          
          $full_url = str_replace($old_value, $new_value, $full_url);
          return $full_url;
        }
      } 
      if($new_value != '') {
        return $full_url.$param.$new_value;
      }
      return $full_url;
    }

    public function getColorNombre($color) {
      return ucwords(str_replace('-',' ',$color));
    }

    public function getDataColores($colores) {
      $list = array();
      foreach ($colores as $color) {
        $dictionary = array(
          '{{color_nombre}}'=>$this->getColorNombre($color['color_nombre']),
          '{{color_imagen}}'=>$this->getUrl(IMAGE_URL,$color['color_imagen']),
          '{{color_url}}'=>$this->getAbsoluteUrl('&color=',$color['color_nombre'])
        );
        $list[] = $dictionary;
      }
      return $list; 
    }

    public function getDataColoresByProducto($colores) {
      $list = array();
      foreach ($colores as $color) {
        $dictionary = array(
          '{{producto_id}}'=>$color['producto_id'],
          '{{color_nombre}}'=>$this->getColorNombre($color['color_nombre']),
          '{{color_imagen}}'=>$this->getUrl(IMAGE_URL,$color['color_imagen']),
          '{{color_url}}'=>$this->getUrlProducto($color['producto_id'])
        );
        $list[] = $dictionary;
      }
      return $list; 
    }

    public function getDataFiltters($filtters) {
      $list = array();

      foreach ($filtters as $key => $value) {
        $dictionary = array(
          '{{filtro}}'=>ucwords($key),
          '{{valor}}'=>$value,
          '{{filtro_url}}'=>$this->getAbsoluteUrl('&'.$key.'=','')
        );
        /*
        $dictionary = array(
          '{{filtro}}'=>ucwords($key),
          '{{valor}}'=>$value,
          '{{filtro_url}}'=>$this->getAbsoluteUrl('&'.$key.'=',$value)
        );
        */

        $list[] = $dictionary;
      }
      //echo '<pre>';
      //var_dump($list);exit();
      return $list; 
    }

    public function getDataProductos($productos) {
      $list = array();
      foreach ($productos as $producto) {
        $dictionary = array(
          '{{id}}'                  =>$producto['id'],
          '{{codigo}}'              =>$producto['codigo'],
          '{{modelo}}'              =>$producto['modelo'],
          '{{nombre}}'              =>$producto['nombre'],
          '{{categoria}}'           =>$producto['categoria_nombre'],
          '{{subcategoria}}'        =>$producto['subcategoria_nombre'],
          '{{descripcion}}'         =>$producto['descripcion'],
          '{{color}}'               =>$producto['color_imagen'],
          '{{material}}'            =>$producto['material'],
          '{{marca}}'               =>$producto['marca'],
          '{{altura}}'              =>$producto['altura'],
          '{{precio}}'              =>$producto['precio'],
          '{{image}}'               =>$this->getUrl(IMAGE_URL,$producto['imagen']),
          '{{url_producto}}'        =>'index.php?ctrl=productos&action=show&id='.$producto['id'],
          '{{url_shopping_cart}}'   =>'index.php?ctrl=shopping-cart&action=list'
        );
        $list[] = $dictionary;
      }
      return $list; 
    }
      
    public function getDataSocios($socios) {
      $list = array();
      foreach ($socios as $socio) {
        $dictionary = array(
          '{{id}}'                  =>$socio['id'],
          '{{codigo}}'              =>$socio['codigo'],
          '{{nombre}}'              =>$socio['nombre'],
          '{{image}}'               =>$this->getUrl(IMAGE_URL,$socio['image'])
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
      if($this->isLogin()) {
        $menu =  file_get_contents("views/menu.html");
        $footer = file_get_contents("views/footer.html");
      } else {
        $menu =  file_get_contents("views/menu-login.html");
        $footer = file_get_contents("views/footer-login.html");
      }
      $view =  file_get_contents("views/".$type.".html");
      $view = $header.$menu.$view.$footer;
      $view = $this->showDataMenu($view);
      return $view;
    }

    public function getForm($view) {
      $header = file_get_contents("views/header.html");
      $menu =  file_get_contents("views/menu.html");
      $modal_address =  file_get_contents("views/modal_address.html");
      $modal_phone =  file_get_contents("views/modal_phone.html");
      $view =  file_get_contents("views/".$view.".html");
      $footer = file_get_contents("views/footer.html");
      return $header.$menu.$modal_address.$modal_phone.$view.$footer;
    }

    public function showForm($view,$diccionary) {
      $view = $this->getForm($view);
      $table = "";
      $content = $view;
      $content = strtr($view,$diccionary);
      $view = str_replace($view, $table, $content);

      echo $view;
    }

    public function showErrorPage() {
      http_response_code(404);
      $view = $this->getView('404');
      echo $view;
    }

    public function moveImage($temp, $name) {
      if(file_exists($name)) {
        unlink($name);
      }
      if(move_uploaded_file($temp, $name)) {
        ;
      } else {
        echo 'NO SE PUDO';
        exit();
      }
    }

    public function moveFile($temp, $name) {
      if(file_exists($name)) {
        unlink($name);
      }
      if(move_uploaded_file($temp, $name)) {
        ;
      } else {
        echo 'NO SE PUDO MOVER EL PDF';
        exit();
      }
    }

    public function getFileExtension($file) {
      $name = explode(".",$file);
      return end($name);
    }

    public function generateNameImage($id, $single, $extension, $url) {
      return  $url.$single.$id.'.'.$extension;
    }

    public function generateNameFile($id, $single, $extension, $url) {
      return $url.$single.$id.'.'.$extension;
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

    public function uploadFile($id, $single, $file, $url) {
      $original = $file['name'];
      $extension = $this->getFileExtension($original);
      $name = $this->generateNameFile($id, $single, $extension, $url);
      $temporal = $file['tmp_name'];
      $final['original'] = $original;
      $final['name']= $name;
      $final['id'] = $id;  
      
      $this->moveFile($temporal, $name);
      return $name;
    }

  }

?>