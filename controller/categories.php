<?php
	
	function newCategory($name, $parent){

		require_once('./controller/connection.php');
		$connection = open_connection();
		$sql = "";

		$sql = " INSERT INTO `categories` (`idCategory`, `name`, `parent`) 
				VALUES (NULL, '$name', '$parent'); ";

		if($parent == 0){
			$sql = " INSERT INTO `categories` (`idCategory`, `name`, `parent`) 
				VALUES (NULL, '$name', NULL); ";
		}

		mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		echo "<div class='alert alert-info' role='alert'>Categoría añadido</div>";
		close_connection($connection);
	}

	function updateCategory($idCategory, $name, $parent, $image){

		$sql = "UPDATE `categories` 
					SET  `name` = '$name',
						 `parent` = '$parent',
						 `image` = '$image'
					WHERE `idCategory` = $idCategory";
		
		if( !( is_string($image) ) ){
			//echo "2";
			$sql = "UPDATE `categories` 
					SET  `name` = '$name',
						 `parent` = $parent
					WHERE `idCategory` = $idCategory";
		}

		if($parent == 0 ){
			//echo "3";
			$sql = "UPDATE `categories` 
					SET  `name` = '$name',
						 `parent` = NULL,
						 `image` = '$image'
					WHERE `idCategory` = $idCategory";
		}


		if( ($parent == 0) && !( is_string($image) ) ){
			//echo "4";
			$sql = "UPDATE `categories` 
					SET  `name` = '$name',
						 `parent` = NULL
					WHERE `idCategory` = $idCategory";
		}

		//echo $image . " " . $sql;
		require_once('./controller/connection.php');
		$connection = open_connection();
		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		echo "<div class='alert alert-info' role='alert'>Categoría modificada</div>";
		close_connection($connection);
	}

	function hasSubCaegories($idCategory){

		require_once('./controller/connection.php');
		$connection = open_connection();

		$sql = "SELECT count(*) as count FROM categories WHERE parent = $idCategory LIMIT 1";	
		$result = mysqli_query($connection, $sql);
		$value = mysqli_fetch_object($result);
		$result = $value->count;
		close_connection($connection);

		return $result;
	}

	function getSubCaegories($idCategory){

		require_once('./controller/connection.php');
		$connection = open_connection();

		$sql = "SELECT `idCategory` FROM `categories` WHERE `parent` = $idCategory";	
		//echo $sql;
		$result = mysqli_query($connection, $sql);
		close_connection($connection);

		return $result;
	}


	function getCategory($idCategory){

		$sql = "SELECT * FROM `categories` WHERE `idCategory` = $idCategory";
		//echo $sql;
		require_once('./controller/connection.php');
		$connection = open_connection();
		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		close_connection($connection);
		$result = mysqli_fetch_array ( $result );

		return $result;
	}

	function getCategoryList(){
		
		require_once('./controller/connection.php');

		$connection = open_connection();
		$sql = "SELECT * FROM `categories` ";
		$categoryList = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion(002)");
		close_connection($connection);

		return $categoryList;
	}

	function getCategoryListImage(){
		
		require_once('./controller/connection.php');

		$connection = open_connection();
		$sql = "SELECT * FROM `categories` where image is not NULL";
		$categoryList = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion(002)");
		close_connection($connection);

		return $categoryList;
	}

	function getCategoryListParent(){
		
		require_once('./controller/connection.php');

		$connection = open_connection();
		$sql = "SELECT * FROM `categories` where `parent` is NULL";
		$categoryList = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion(002)");
		close_connection($connection);

		return $categoryList;
	}

	function get_base_categories(){
		
		require_once('./controller/connection.php');

		$connection = open_connection();
		$sql = "select * from categories where parent is NULL";
		$categoryList = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion(002)");

		if($categoryList->num_rows>0){
			while ($r=$categoryList->fetch_array()) {
				$data[]=$r;
			}
		}
		return $data;
	}

	function get_cat_by_id($id){

		require_once('./controller/connection.php');
		$data= array();
		$connection = open_connection();
		$sql = "select * from categories where idCategory = $id";
		$query = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion(002)");

		if($query->num_rows>0){
			while ($r=$query->fetch_array()) {
				$data=$r;
			}
		}
		return $data;
	}

	function edit_btn($idCategory){
		return "<a href='app.php?sec=newCategory&editcategory=$idCategory' ><span style='height: 28px;' class='btn btn-primary' >Editar</span></a>";
	}

	function del_btn($idCategory){
		return "<a href='app.php?sec=categories&idCategoryToRem=$idCategory' ><span style='height: 28px;' class='btn btn-danger' >Eliminar</span></a>";
	}

	function remCategory($idCategory){
		require_once('./controller/connection.php');
		$connection = open_connection();

		$sql = "DELETE FROM `categories` WHERE `idCategory` = $idCategory";
		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		echo "<div class='alert alert-info' role='alert'>Categoría eliminada</div>";
		close_connection($connection);
	}

	function get_cats_by_cat_id($id){

		require_once('./controller/connection.php');
		$data= array();
		$connection = open_connection();
		$sql = "select * from categories where parent = $id";
		$query = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion(002)");

		if($query->num_rows>0){
			while ($r=$query->fetch_array()) {
				$data[]=$r;
			}
		}

		return $data;
	}

	function list_tree_cat_id($id){
		$subs = get_cats_by_cat_id($id);
		if(count($subs)>0){
			echo "<ul>";
			foreach($subs as $s){
				echo "<li style='margin: 8px;'> $s[name] ".edit_btn($s["idCategory"])." ".del_btn($s["idCategory"])."</li>";
				list_tree_cat_id($s["idCategory"]);
			}
			echo "</ul>";
		}
	}

	function list_tree_cat_id_art($id, $listCat){
		$subs = get_cats_by_cat_id($id);
		if(count($subs)>0){
			echo "<ul>";
			foreach($subs as $s){
				$checked = "";
				if (isset($listCat) && is_array($listCat) && in_array( $s['idCategory'], $listCat ) == TRUE ){
					$checked  = "checked='checked'";
			  	}

				echo "<li style='margin: 8px;' ><input name=categoriesSelected[] type='checkbox' value= " . $s['idCategory'] . " " .  $checked . ">" . $s['name'] . " </label></li>";
				list_tree_cat_id_art($s["idCategory"], $listCat);
			}
			echo "</ul>";
		}
	}

	function list_tree_cat_id_art_search($id, $text){
		$subs = get_cats_by_cat_id($id);
		if(count($subs)>0){
			$text .= "<ul >";
			foreach($subs as $s){				
				$text .=  "<li style='font-size: 18px;' ><input style='margin-right: 10px; transform: scale(2);' name=categoriesSelected[] type='checkbox' value= " . $s['idCategory'] . " >" . $s['name'] . "</li>";
				list_tree_cat_id_art_search($s["idCategory"], $text);
			}
			$text .=  "</ul>";

			return $text;
		}
	}

	function list_tree_cat_id_art_view($id, $listCat){
		$subs = get_cats_by_cat_id($id);
		if(count($subs)>0){
			echo "<ul>";
			foreach($subs as $s){

				if (isset($listCat) && is_array($listCat) && in_array( $s['idCategory'], $listCat ) == TRUE ){
					echo "<li style='margin: 8px;' >". $s['name'] . "</li>";
			  	}

				
				list_tree_cat_id_art_view($s["idCategory"], $listCat);
			}
			echo "</ul>";
		}
	}

	function select_tree_cat_id($id,$level){
		$subs = get_cats_by_cat_id($id);
		if(count($subs)>0){
			foreach($subs as $s){
				echo "<option value=\"$s[idCategory]\" > ".str_repeat("-", $level)."$s[name] </option>";
				select_tree_cat_id($s["idCategory"],$level+1);
			}
		}
	}
	function selected_tree_cat_id($id,$level,$curr_id,$selected_id){
		//echo $selected_id;
		$subs = get_cats_by_cat_id($id);
		if(count($subs)>0){
			foreach($subs as $s){
				if($s["idCategory"]!=$curr_id){
					$selected = "";
					if($s["idCategory"]==$selected_id){ $selected= "selected"; }
					echo "<option value=\"$s[idCategory]\" $selected>".str_repeat("-", $level)."$s[name] </option>";
					selected_tree_cat_id($s["idCategory"],$level+1,$curr_id,$selected_id);
				}
			}
		}
	}

?>