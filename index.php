<?php 
  session_start();
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
        http_response_code(404);
    }
  } else {
    require_once('controllers/HomeCtrl.php');
    $controller = new HomeCtrl();
  }
  $controller->execute();
?>