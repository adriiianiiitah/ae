<?php     header('Content-Type: text/html; charset=utf-8');
  session_start();
  if(isset($_GET) && !empty($_GET['ctrl'])) {
    $controller = $_GET['ctrl'];

    switch ($controller) {
      case '':
      case 'index':
        require_once('controllers/IndexCtrl.php');
        $controller = new IndexCtrl();
      break;
      case 'categorias':
        require_once('controllers/CategoriasCtrl.php');
        $controller = new CategoriasCtrl();
      break;
      case 'subcategorias':
        require_once('controllers/SubcategoriasCtrl.php');
        $controller = new SubcategoriasCtrl();
      break;
      case 'productos':
        require_once('controllers/ProductosCtrl.php');
        $controller = new ProductosCtrl();
      break;
      case 'catalogos':
        require_once('controllers/CatalogosCtrl.php');
        $controller = new CatalogosCtrl();
      break;
	    case 'usuarios':
        require_once('controllers/UsuariosCtrl.php');
        $controller = new UsuariosCtrl();
      break;
      case 'ofertas':
        require_once('controllers/OfertasCtrl.php');
        $controller = new OfertasCtrl();
      break;
      case 'descuentos':
        require_once('controllers/DescuentosCtrl.php');
        $controller = new DescuentosCtrl();
      break;
      case 'colores':
        require_once('controllers/ColoresCtrl.php');
        $controller = new ColoresCtrl();
      break;
      
      default:
        $this->showErrorPage();
    }
  } else {
    require_once('controllers/IndexCtrl.php');
    $controller = new IndexCtrl();
  }
  $controller->execute();
?>
