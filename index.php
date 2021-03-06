<?php 
  session_start();
  //$_SESSION['productos'] = array();
  //$_SESSION['tallas'] = array();
  //$_SESSION['producto_talla'] = array();
  if(isset($_GET) && !empty($_GET['ctrl'])) {
    $controller = $_GET['ctrl'];

    switch ($controller) {
      case '':
      case 'home':
        require_once('controllers/HomeCtrl.php');
        $controller = new HomeCtrl();
      break;
      case 'productos':
        require_once('controllers/ProductosCtrl.php');
        $controller = new ProductosCtrl();
      break;
      case 'descuentos':
        require_once('controllers/DescuentosCtrl.php');
        $controller = new DescuentosCtrl();
      break;
      case 'ofertas':
        require_once('controllers/OfertasCtrl.php');
        $controller = new OfertasCtrl();
      break;
      case 'contacto':
        require_once('controllers/ContactoCtrl.php');
        $controller = new ContactoCtrl();
      break;
      case 'usuario':
        require_once('controllers/UsuarioCtrl.php');
        $controller = new UsuarioCtrl();
      break;
      case 'shopping-cart':
        require_once('controllers/ShoppingCartCtrl.php');
        $controller = new ShoppingCartCtrl();
      break;
      case 'privacidad':
        require_once('controllers/PrivacidadCtrl.php');
        $controller = new PrivacidadCtrl();
      break;
      
        default:
        require_once('controllers/HomeCtrl.php');
        $controller = new HomeCtrl();
    }
  } else {
    require_once('controllers/HomeCtrl.php');
    $controller = new HomeCtrl();
  }
  $controller->execute();
?>