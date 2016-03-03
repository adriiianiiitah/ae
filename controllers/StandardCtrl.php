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