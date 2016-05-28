<?php
  require_once('StandardCtrl.php');

  class ProductosCtrl extends StandardCtrl {
    private $model;
    public $table_name;
    public $single;
    public $url;
    public $modal;
    public $image;

    public function __construct() {
      parent::__construct();
      require_once('./models/ProductosMdl.php');
      $this->model = new ProductosMdl();
      $this->table_name = 'productos';
      $this->single = 'producto';
      $this->url = 'images/productos/';
      $this->image = 'images/productos/producto.png';
      $this->modal = 'modal-delete-producto';
    }

    public function execute() {
      if(isset($_GET['action'])) {
        $action = $_GET['action'];

        switch ($action) {
          case '':
          case 'list':
            $this->showProductos();
            break;
          case 'view':
            if(isset($_GET['id']) && !empty($_GET['id']))
              $this->showProducto($_GET['id']);
            else
              $this->showErrorPage();
            break;
          case 'edit':
            if(isset($_GET['id']) && !empty($_GET['id']))
              $this->editProducto($_GET['id']);
            else
             $this->showErrorPage();
            break;
          case 'create':
              $this->createProducto();
            break;
          case 'productos':
            $productos = $this->model->getAllProductos();
            $data = $this->loadDataProductos($productos);
            break;
          default:
            $this->showErrorPage();
            break;
        } 
      }
      else {
        $this->showProductos();
      }
    }

    public function getDictionary($producto) {
      return  array(
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
        //'talla'               =>$producto['talla'],
        '{{precio}}'              =>$producto['precio'],
        //'{{stock}}'               =>$producto['stock'],
        '{{image}}'           =>$producto['imagen'],
        '{{producto-view}}'   =>"index.php?ctrl=productos&action=view&id=".$producto['id'],
        '{{producto-edit}}'   =>"index.php?ctrl=productos&action=edit&id=".$producto['id'],
      );
    }

    public function showProductos() {
      $productos = $this->model->getAll();
      $view = $this->getView("productos", 'list', $this->modal);
      $area = $this->getRow($view);
      $table = "";

      foreach ($productos as $producto) {
        $area = $row = $this->getRow($view);
        $diccionary = $this->getDictionary($producto);
        $row = strtr($row,$diccionary);
        $table .= $row;
      } 

      $view = str_replace($area, $table, $view);
      echo $view;
    }

    public function showProducto($id) {
      if($this->isInt($id)) {
        $producto = $this->model->getOne($id);

        if ($producto) {
          $table = "";
          $view = $this->getView("producto-view", 'view', $this->modal);

          $content = $view;
          $diccionary = $this->getDictionary($producto);
          $content = strtr($view,$diccionary);
          $view = str_replace($view, $table, $content);

          $tallas = $this->model->getTallasById($id);
          $data = $this->getDataTallas($tallas);
          $view = $this->showData($view,$data,TALLA_TAG_START,TALLA_TAG_END);

          echo $view;
        } else {
          $this->showErrorPage();
        }
      } else {
        $this->showErrorPage();
      }
    }

    public function createProducto() {
      $producto = array(
        'id'                  =>'',
        'codigo'              =>'',
        'modelo'              =>'',
        'nombre'              =>'',
        'categoria_nombre'    =>'',
        'subcategoria_nombre' =>'',
        'descripcion'         =>'',
        'color_imagen'        =>'',
        'material'            =>'',
        'marca'               =>'',
        'altura'              =>'',
        'precio'              =>'',
        'imagen'              =>$this->image
      );
      $id ='';

      if(empty($_POST)) {
        $table = "";
        $diccionary = $this->getDictionary($producto);
        $view = $this->getViewForm($id,'producto-edit',$this->modal,$diccionary);

        $tallas = $this->model->getTallasById($id);
        $data = $this->getDataTallas($tallas);
        $view = $this->showData($view,$data,TALLA_TAG_START,TALLA_TAG_END);

        $categorias = $this->model->getAllCategorias();
        $data = $this->getDataCategorias($categorias);
        $view = $this->showData($view,$data,CATEGORIA_TAG_START,CATEGORIA_TAG_END);

        $subcategorias = $this->model->getAllSubcategorias();
        $data = $this->getDataSubcategorias($subcategorias);
        $view = $this->showData($view,$data,SUBCATEGORIA_TAG_START,SUBCATEGORIA_TAG_END);

        $colores = $this->model->getAllColores();
        $data = $this->getDataColores($colores);
        $view = $this->showData($view,$data,COLOR_TAG_START,COLOR_TAG_END);

        echo $view;
      } else {
        $errors = array();

        if($this->isCode($_POST['codigo'])) {
          $producto['codigo'] = $_POST['codigo'];
        } else {
          $errors['codigo'] = 'El código es incorrecto. Debe contener letras, dígitos y guiones.';
        }

        if($this->isCode($_POST['modelo'])) {
          $producto['modelo'] = $_POST['modelo'];
        } else {
          $errors['modelo'] = 'El modelo es incorrecto. Debe contener letras, dígitos y guiones.';
        }

        if($this->isAlphanumeric($_POST['nombre'])) {
          $producto['nombre'] = $_POST['nombre'];
        } else {
          $errors['nombre'] = 'El nombre es incorrecto. Debe contener letras, dígitos y espacios.';
        }

        if($this->isInt($_POST['categoria'])) {
          $producto['categoria'] = $_POST['categoria'];
        } else {
          $errors['categoria'] = 'La categoría es incorrecta.';
        }

        if($this->isInt($_POST['subcategoria'])) {
          $producto['subcategoria'] = $_POST['subcategoria'];
        } else {
          $errors['subcategoria'] = 'La subcategoria es incorrecta.';
        }

        if($this->isDescription($_POST['descripcion'])) {
          $producto['descripcion'] = $_POST['descripcion'];
        } else {
          $producto['descripcion'] = '';
        }

        if($this->isAlphanumeric($_POST['material'])) {
          $producto['material'] = $_POST['material'];
        } else {
          $producto['material'] = '';
        }

        if($this->isAlphanumeric($_POST['marca'])) {
          $producto['marca'] = $_POST['marca'];
        } else {
          $errors['marca'] = 'La marca es incorrecta.';
        }

        if($this->isNumber($_POST['altura']) || $this->isInt($_POST['altura'])) {
          $producto['altura'] = $_POST['altura'];
        } else {
          $producto['altura'] = '';
        }

        if($this->isInt($_POST['color'])) {
          $producto['color'] = $_POST['color'];
        } else {
          $errors['color'] = 'La color es incorrecta.';
        }

        if($this->isNumber($_POST['precio']) || $this->isInt($_POST['precio'])) {
          $producto['precio'] = $_POST['precio'];
        } else {
          $errors['precio'] = 'El precio es incorrecto.';
        }

        if(empty($errors)) {
          $id = $this->model->insert($producto);
          if($_FILES['image']['tmp_name'] != '') {
            $producto['imagen']  = $this->uploadImage($id, $this->single, $_FILES['image'],$this->url);
            $this->model->updateImage($id, $producto['imagen']);
          }

          header ("Location: index.php?ctrl=productos&action=view&id=".$id);
        } else {
          $this->editProducto($id);
          $this->print_exit($errors);
        }
    //$talla          = $_POST['talla'];
    //$stock          = $_POST['stock'];
      }
    }

    public function editProducto($id) {
      if($this->isInt($id)) {
        $producto = $this->model->getOne($id);
        $imagen = $producto['imagen'];

        if ($producto) {
          if(empty($_POST)) {
            $table = "";
            $diccionary = $this->getDictionary($producto);
            $view = $this->getViewForm($id,'producto-edit',$this->modal,$diccionary);

            $tallas = $this->model->getTallasById($id);
            $data = $this->getDataTallas($tallas);
            $view = $this->showData($view,$data,TALLA_TAG_START,TALLA_TAG_END);

            $categorias = $this->model->getAllCategorias();
            $data = $this->getDataCategorias($categorias);
            $view = $this->showData($view,$data,CATEGORIA_TAG_START,CATEGORIA_TAG_END);

            $subcategorias = $this->model->getAllSubcategorias();
            $data = $this->getDataSubcategorias($subcategorias);
            $view = $this->showData($view,$data,SUBCATEGORIA_TAG_START,SUBCATEGORIA_TAG_END);

            $colores = $this->model->getAllColores();
            $data = $this->getDataColores($colores);
            $view = $this->showData($view,$data,COLOR_TAG_START,COLOR_TAG_END);


            echo $view;
          } else {
            $errors = array();
            $producto = array(
              'id' => $id
            );

            if($this->isCode($_POST['codigo'])) {
              $producto['codigo'] = $_POST['codigo'];
            } else {
              $errors['codigo'] = 'El código es incorrecto. Debe contener letras, dígitos y guiones.';
            }

            if($this->isCode($_POST['modelo'])) {
              $producto['modelo'] = $_POST['modelo'];
            } else {
              $errors['modelo'] = 'El modelo es incorrecto. Debe contener letras, dígitos y guiones.';
            }

            if($this->isAlphanumeric($_POST['nombre'])) {
              $producto['nombre'] = $_POST['nombre'];
            } else {
              $errors['nombre'] = 'El nombre es incorrecto. Debe contener letras, dígitos y espacios.';
            }

            if($this->isInt($_POST['categoria'])) {
              $producto['categoria'] = $_POST['categoria'];
            } else {
              $errors['categoria'] = 'La categoría es incorrecta.';
            }

            if($this->isInt($_POST['subcategoria'])) {
              $producto['subcategoria'] = $_POST['subcategoria'];
            } else {
              $errors['subcategoria'] = 'La subcategoria es incorrecta.';
            }

            if($this->isDescription($_POST['descripcion'])) {
              $producto['descripcion'] = $_POST['descripcion'];
            } else {
              $producto['descripcion'] = '';
            }

            if($this->isAlphanumeric($_POST['material'])) {
              $producto['material'] = $_POST['material'];
            } else {
              $producto['material'] = '';
            }

            if($this->isAlphanumeric($_POST['marca'])) {
              $producto['marca'] = $_POST['marca'];
            } else {
              $errors['marca'] = 'La marca es incorrecta.';
            }

            if($this->isNumber($_POST['altura']) || $this->isInt($_POST['altura'])) {
              $producto['altura'] = $_POST['altura'];
            } else {
              $producto['altura'] = '';
            }

            if($this->isInt($_POST['color'])) {
              $producto['color'] = $_POST['color'];
            } else {
              $errors['color'] = 'El color es incorrecto.';
            }

            if($this->isNumber($_POST['precio']) || $this->isInt($_POST['precio'])) {
              $producto['precio'] = $_POST['precio'];
            } else {
              $errors['precio'] = 'El precio es incorrecto.';
            }

            if($_FILES['image']['tmp_name'] != '') {
              $producto['imagen']  = $this->uploadImage($id, $this->single, $_FILES['image'],$this->url);
            } else {
              $producto['imagen'] = $imagen;
            }

            if(empty($errors)) {
              $this->model->update($producto);
              header ("Location: index.php?ctrl=productos&action=view&id=".$id);
            } else {
              $this->editProducto($id);
              $this->print_exit($errors);
            }
        //$talla          = $_POST['talla'];
        //$stock          = $_POST['stock'];
          }
        }
      }
    }
  }
?>
