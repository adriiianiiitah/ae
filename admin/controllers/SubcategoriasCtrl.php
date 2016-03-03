<?php
	require_once('StandardCtrl.php');

	class SubcategoriasCtrl extends StandardCtrl {
		private $model;
		public $table_name;
		public $url;
		public $modal;

		public function __construct() {
			parent::__construct();
			//require_once('./models/subcategoriasMdl.php');
			//$this->model = new subcategoriasMdl();
			$this->table_name = 'subcategorias';
			$this->url = 'images/subcategorias';
			$this->modal = 'modal-delete-subcategoria';
		}

		public function execute() {
			if(isset($_GET['action'])) {
				$action = $_GET['action'];

				switch ($action) {
					case '':
					case 'list':
						$this->showSubcategorias();
						break;
					case 'view':
						if(isset($_GET['id']) && !empty($_GET['id']))
							$this->showSubcategoria($_GET['id']);
						else
							$this->showErrorPage();
						break;
					case 'edit':
						if(isset($_GET['id']) && !empty($_GET['id']))
							$this->editSubcategoria($_GET['id']);
						else
							$this->showErrorPage();
						break;
					
					default:
						$this->showErrorPage();
						break;
				} 
			}
			else {
					$this->showSubcategorias();
				}
		}

		public function showSubcategorias() {
			//$subcategorias = $this->model->getAll();
			$view = $this->getView("subcategorias", 'list', $this->modal);
			$area = $this->getRow($view);
			$table = "";

			$subcategoria = [
        'id'            =>'1',
        'codigo'        =>'123456',
        'nombre'        =>'Zapatilla',
        'categoria'     =>'Dama',
        'descripcion'   =>'Lorem ipsum'
      ];

			//foreach ($subcategorias as $subcategoria) {
				$area = $row = $this->getRow($view);
				$diccionary = array(
					'{{id}}'=>$subcategoria['id'],
					'{{codigo}}'=>$subcategoria['codigo'],
					'{{nombre}}'=>$subcategoria['nombre'],
          '{{categoria}}'=>$subcategoria['categoria'],
					'{{descripcion}}'=>$subcategoria['descripcion'],
					'{{subcategoria-view}}'=>"index.php?ctrl=subcategorias&action=view&id=".$subcategoria['id'],
					'{{subcategoria-edit}}'=>"index.php?ctrl=subcategorias&action=edit&id=".$subcategoria['id'],
				);
				$row = strtr($row,$diccionary);
				$table .= $row;
			//}

			$view = str_replace($area, $table, $view);
			echo $view;
		}

		public function showsubcategoria($id) {
			//$subcategoria = $this->model->getOne($id);

      $subcategoria = [
        'id'            =>'1',
        'codigo'        =>'123456',
        'nombre'        =>'Zapatilla',
        'categoria'     =>'Dama',
        'descripcion'   =>'Lorem ipsum'
      ];
      $table = "";

			if($this->isInt($id)) {
				
				$view = $this->getView("subcategoria-view", 'view', $this->modal);

				$content = $view;
				$diccionary = array(
          '{{id}}'=>$subcategoria['id'],
          '{{codigo}}'=>$subcategoria['codigo'],
          '{{nombre}}'=>$subcategoria['nombre'],
          '{{categoria}}'=>$subcategoria['categoria'],
          '{{descripcion}}'=>$subcategoria['descripcion'],
          '{{subcategoria-view}}'=>"index.php?ctrl=subcategorias&action=view&id=".$subcategoria['id'],
          '{{subcategoria-edit}}'=>"index.php?ctrl=subcategorias&action=edit&id=".$subcategoria['id'],
        );
				$content = strtr($view,$diccionary);
				$view = str_replace($view, $table, $content);

				echo $view;
			} else {
				$this->showErrorPage();
				
			}
		}

		public function editsubcategoria($id) {
			//$subcategoria = $this->model->getOne($id);
			//$image = $subcategoria['image'];
      $subcategoria = [
        'id'            =>'1',
        'codigo'        =>'123456',
        'nombre'        =>'Zapatilla',
        'categoria'     =>'Dama',
        'descripcion'   =>'Lorem ipsum'
      ];

			if(empty($_POST)) {
        $diccionary = array(
          '{{id}}'=>$subcategoria['id'],
          '{{codigo}}'=>$subcategoria['codigo'],
          '{{nombre}}'=>$subcategoria['nombre'],
          '{{categoria}}'=>$subcategoria['categoria'],
          '{{descripcion}}'=>$subcategoria['descripcion'],
          '{{subcategoria-view}}'=>"index.php?ctrl=subcategorias&action=view&id=".$subcategoria['id'],
          '{{subcategoria-edit}}'=>"index.php?ctrl=subcategorias&action=edit&id=".$subcategoria['id'],
        );
				$this->showForm($id,'subcategoria-edit',$this->modal,$diccionary);
			} else {
				$code = $_POST['code'];
				$name = $_POST['name'];
        $categoria = $_POST['categoria'];
				$description = $_POST['description'];

				if($_FILES['image']['tmp_name'] != '')
					$image = $this->uploadImage($id, $this->table_name, $_FILES['image'],$this->url);

				//$this->model->update($id,$code,$name,$description,$image);

				$this->showsubcategoria($id);
			}
		}

		public function showFormEdit($id,$subcategoria) {

			if($this->isInt($id) && $this->isResponse($subcategoria)) {
				$view = $this->getView("subcategoria-edit", 'edit', $this->modal);

				$content = $view;
				$diccionary = array(
					'{{id}}'=>$subcategoria['id'],
					'{{code}}'=>$subcategoria['code'],
					'{{name}}'=>$subcategoria['name'],
					'{{description}}'=>$subcategoria['description'],
					'{{image}}'=>$subcategoria['image'],
					'{{subcategoria-view}}'=>"index.php?ctrl=subcategorias&action=view&id=".$subcategoria['id'],
					'{{subcategoria-edit}}'=>"index.php?ctrl=subcategorias&action=edit&id=".$subcategoria['id'],
					'{{action}}'=>"index.php?ctrl=subcategorias&action=edit&id=".$subcategoria['id']
				);
				$content = strtr($view,$diccionary);
				$view = str_replace($view, $table, $content);

				echo $view;
			} else {
				$this->showErrorPage();
				
			}
		}
	}
?>