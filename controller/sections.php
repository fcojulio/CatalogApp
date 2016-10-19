<?php

	function printDirectory($idSection){

		//require_once('./controller/connection.php');
		require_once('./controller/categories.php');
		               
                $nameSec = getNameSection($idSection);
                $paretSec = $idSection;   
                $printCatalog = "";
                $listSec = array();

                $listSec[] = $idSection;

                while ( ($paretSec = getParentSection($paretSec)) != 0) {
                  $listSec[] = $paretSec;
                }

                $listSec =  array_reverse($listSec);
                $printCatalog .= "<div style='margin-bottom: 10px; display: inline-flex;' >
                          			<h3 style='margin-top: 0px; margin-bottom: 0px; margin-right: 20px;' >";

                foreach ($listSec as $sec) {
                  $printCatalog .= "<a style='color: rgb(49, 189, 154);' href='app.php?sec=catalog&cat=".$sec."' >" . getNameSection($sec) . "</a> / ";
                }

                $printCatalog .= "</h3>";
                $contextSearch = "";
                if( isset($_GET['sec']) ) { 
                	$sec = $_GET['sec']; 
                	$contextSearch .= "<input type='hidden' name='sec' value='$sec' />";                            
                }
                if( isset($_GET['cat']) ) { 
                	$cat = $_GET['cat'];
                	$contextSearch .= "<input type='hidden' name='cat' value='$cat' />";
                }
               	if( isset($_GET['all']) ) { 
               		$all = $_GET['all'];
               		$contextSearch .= "<input type='hidden' name='all' value='$all' />"; 
               	}

                $printCatalog .= "<div class='dropdown' style='margin-right: 20px'>
                              <button style='background-color: rgb(49, 189, 154); color: white;' class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>
                                Buscar referencia 
                                <span class='caret'></span>
                              </button>
                              <ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>
                                <form id='search_item' class='appnitro' enctype='multipart/form-data' method='GET' action='' >

                                $contextSearch

                                <input style='width: 190px; height: 50px; margin: 5px;' type='text' name='searchCatByRef' /></li>
                                <input type='hidden' name='sectionToSearch' value='$idSection' />                               

                                <input style='background-color: rgb(49, 189, 154); color: white;' class='btn btn-primary' style='width: 190px; height: 50px; margin: 5px;' type='submit' value='Buscar' /></li> 
                                </form>                 
                              </ul>        
                            </div>";

                $printCatalog .= "<a style='background-color: rgb(49, 189, 154); color: white; border-color:white;' data-toggle='modal' data-target='#viewFeaturesToSearch' class='btn btn-primary' >Buscar características</a>";
                $printCatalog .= "</div>";    

        return $printCatalog;
	}
	
	function newSection($name, $parent){

		require_once('./controller/connection.php');
		$connection = open_connection();
		$sql = "";

		$sql = " INSERT INTO `sections` (`idSection`, `name`, `parent`) 
				VALUES (NULL, '$name', '$parent'); ";

		if($parent == 0){
			$sql = " INSERT INTO `sections` (`idSection`, `name`, `parent`) 
				VALUES (NULL, '$name', NULL); ";
		}

		mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		echo "<div class='alert alert-info' role='alert'>Sección añadido</div>";
		close_connection($connection);
	}

	function updateSection($idSection, $name, $parent, $image){

		$sql = "UPDATE `sections` 
					SET  `name` = '$name',
						 `parent` = '$parent',
						 `image` = '$image'
					WHERE `idSection` = $idSection";
		
		if( !is_string($image) ){
			$sql = "UPDATE `sections` 
					SET  `name` = '$name',
						 `parent` = $parent
					WHERE `idSection` = $idSection";
		}

		if($parent == 0 ){
			$sql = "UPDATE `sections` 
					SET  `name` = '$name',
						 `parent` = NULL,
						 `image` = '$image'
					WHERE `idSection` = $idSection";
		}


		if( ($parent == 0) && !( is_string($image) ) ){
			$sql = "UPDATE `sections` 
					SET  `name` = '$name',
						 `parent` = NULL
					WHERE `idSection` = $idSection";
		}

		echo $image . " " . $sql;
		require_once('./controller/connection.php');
		$connection = open_connection();
		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		echo "<div class='alert alert-info' role='alert'>Sección modificada</div>";
		close_connection($connection);
	}

	function getSection($idSection){

		$sql = "SELECT * FROM `sections` WHERE `idSection` = $idSection";
		//echo $sql;
		require_once('./controller/connection.php');
		$connection = open_connection();
		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		close_connection($connection);
		$result = mysqli_fetch_array ( $result );

		return $result;
	}

	function getSectionList(){
		
		require_once('./controller/connection.php');

		$connection = open_connection();
		$sql = "SELECT * FROM `sections` ";
		$businessList = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion(002-1)");
		close_connection($connection);

		return $businessList;
	}

	function getSectionListImage(){
		
		require_once('./controller/connection.php');

		$connection = open_connection();
		$sql = "SELECT * FROM `sections` where image is not NULL";
		$sectionList = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion(002-2)");
		close_connection($connection);

		return $sectionList;
	}

	function getSectionListParent(){
		
		require_once('./controller/connection.php');

		$connection = open_connection();
		$sql = "SELECT * FROM `sections` where `parent` is NULL";
		$sectionList = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion(002-3)");
		close_connection($connection);

		return $sectionList;
	}

	function get_base_sections(){
		
		require_once('./controller/connection.php');

		$connection = open_connection();
		$sql = "select * from sections where parent is NULL";
		$sectionList = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion(002-4)");

		if($sectionList->num_rows>0){
			while ($r=$sectionList->fetch_array()) {
				$data[]=$r;
			}
		}
		return $data;
	}

	function get_sec_by_id($id){

		require_once('./controller/connection.php');
		$data= array();
		$connection = open_connection();
		$sql = "select * from sections where idSection = $id";
		$query = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion(002-5)");

		if($query->num_rows>0){
			while ($r=$query->fetch_array()) {
				$data=$r;
			}
		}
		return $data;
	}

	function edit_sec_btn($idSection){
		return "<a href='app.php?sec=newSection&editsection=$idSection' ><span style='height: 28px;' class='btn btn-primary' >Editar</span></a>";
	}

	function del_sec_btn($idSection){
		return "<a href='app.php?sec=sections&idSectionToRem=$idSection' ><span style='height: 28px;' class='btn btn-danger' >Eliminar</span></a>";
	}

	function remSection($idSection){
		require_once('./controller/connection.php');
		$connection = open_connection();

		$sql = "DELETE FROM `sections` WHERE `idSection` = $idSection";
		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		echo "<div class='alert alert-info' role='alert'>Sección eliminada</div>";
		close_connection($connection);
	}

	function get_secs_by_sec_id($id){

		require_once('./controller/connection.php');
		$data= array();
		$connection = open_connection();
		$sql = "select * from sections where parent = $id";
		$query = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion(002-6)");

		if($query->num_rows>0){
			while ($r=$query->fetch_array()) {
				$data[]=$r;
			}
		}

		return $data;
	}

	function list_tree_sec_id($id){
		$subs = get_secs_by_sec_id($id);
		if(count($subs)>0){
			echo "<ul>";
			foreach($subs as $s){
				echo "<li style='margin: 8px;'> $s[name] ".edit_sec_btn($s["idSection"])." ".del_sec_btn($s["idSection"])."</li>";
				list_tree_sec_id($s["idSection"]);
			}
			echo "</ul>";
		}
	}

	function list_tree_sec_id_art($id, $listSec){
		$subs = get_secs_by_sec_id($id);
		if(count($subs)>0){
			echo "<ul>";
			foreach($subs as $s){
				$checked = "";
				if (isset($listSec) && is_array($listSec) && in_array( $s['idSection'], $listSec ) == TRUE ){
					$checked  = "checked='checked'";
			  	}

				echo "<li style='margin: 8px;' ><input name=sectionsSelected[] type='checkbox' value= " . $s['idSection'] . " " .  $checked . ">" . $s['name'] . " </label></li>";
				list_tree_sec_id_art($s["idSection"], $listSec);
			}
			echo "</ul>";
		}
	}

	function list_tree_sec_id_art_view($id, $listSec){
		$subs = get_secs_by_sec_id($id);
		if(count($subs)>0){
			echo "<ul>";
			foreach($subs as $s){

				if (isset($listSec) && is_array($listSec) && in_array( $s['idSection'], $listSec ) == TRUE ){
					echo "<li style='margin: 8px;' >". $s['name'] . "</li>";
			  	}

				
				list_tree_sec_id_art_view($s["idSection"], $listSec);
			}
			echo "</ul>";
		}
	}

	function select_tree_sec_id($id,$level){
		$subs = get_secs_by_sec_id($id);
		if(count($subs)>0){
			foreach($subs as $s){
				echo "<option value=\"$s[idSection]\" > ".str_repeat("-", $level)."$s[name] </option>";
				select_tree_sec_id($s["idSection"],$level+1);
			}
		}
	}
	function selected_tree_sec_id($id,$level,$curr_id,$selected_id){
		//echo $selected_id;
		$subs = get_secs_by_sec_id($id);
		if(count($subs)>0){
			foreach($subs as $s){
				if($s["idSection"]!=$curr_id){
					$selected = "";
					if($s["idSection"]==$selected_id){ $selected= "selected"; }
					echo "<option value=\"$s[idSection]\" $selected>".str_repeat("-", $level)."$s[name] </option>";
					selected_tree_sec_id($s["idSection"],$level+1,$curr_id,$selected_id);
				}
			}
		}
	}

	function getParentSection($idSection){
		require_once('./controller/connection.php');
		$connection = open_connection();

		$sql = "SELECT parent FROM sections WHERE idSection = $idSection LIMIT 1";	
		//echo $sql . "<br>";
		$result = mysqli_query($connection, $sql);
		$value = mysqli_fetch_object($result);
		$result = $value->parent;
		close_connection($connection);

		return $result;		
	}

	function getNameSection($idSection){
		require_once('./controller/connection.php');
		$connection = open_connection();

		$sql = "SELECT name FROM sections WHERE idSection = $idSection LIMIT 1";	
		$result = mysqli_query($connection, $sql);
		$value = mysqli_fetch_object($result);
		$result = $value->name;
		close_connection($connection);

		return $result;
	}

	function getImageSection($idSection){
		require_once('./controller/connection.php');
		$connection = open_connection();

		$sql = "SELECT image FROM sections WHERE idSection = $idSection LIMIT 1";	
		$result = mysqli_query($connection, $sql);
		$value = mysqli_fetch_object($result);
		$result = $value->image;
		close_connection($connection);

		return $result;
	}

	function getSubSections($idSection){
		require_once('./controller/connection.php');
		$connection = open_connection();

		$sql = "SELECT * FROM sections WHERE parent = $idSection";	
		$result = mysqli_query($connection, $sql);
		close_connection($connection);

		return $result;
	}

	function getAllSubSections($idSection, $listAllSubSections){
		require_once('./controller/connection.php');
		$connection = open_connection();

		$sql = "SELECT * FROM sections WHERE parent = $idSection";	
		$result = mysqli_query($connection, $sql);
		close_connection($connection);
		
		while( $sec = mysqli_fetch_array ( $result )){
			$listAllSubSections[] = $sec['idSection'];
			$listAllSubSections = getAllSubSections($sec['idSection'], $listAllSubSections);
		}

		return $listAllSubSections;
	}
?>