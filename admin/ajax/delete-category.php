<?php 
	require_once('../models/StandardMdl.php');
	require_once('../models/DataBase.php');

	if(isset($_POST['id'])) {
		$connection = DataBase::getInstance();
		$_query = 'DELETE FROM categories 
					   		WHERE id="'.$_POST['id'].'"';
			$category = $connection->execute($_query);
			return $category;
	}
?>