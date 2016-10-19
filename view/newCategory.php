<?php

// Notificar todos los errores de PHP (ver el registro de cambios)
error_reporting(E_ALL);
ini_set('display_errors', '1');
 
?>

<?php
	
	require_once('./controller/categories.php');
	$idCategory = 0;
	$name = "";
	$parent =0;
	$image = "";

	if(isset($_POST['idCategory']) ){

		$idCategory = $_POST['idCategory'];
		$name = $_POST['name'];
		$parent = $_POST['parent'];
		$image = $_FILES["image"]["name"];

		if ( isset( $_FILES["image"] ) && !empty( $_FILES["image"]["name"] ) ) {
			
			$target_dir = "./images/categories/";
			$uploadOk = 1;
			$imageFileType = pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION);
			$extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
			$name_file = $name . "_" . date("jnY") . "." . $extension;
			$name_file = strtr($name_file,'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ', 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
			$target_file = $target_dir  . basename($name_file);
			// Check if image file is a actual image or fake image

		    $check = getimagesize($_FILES["image"]["tmp_name"]);
			if($check !== false) {
			    //echo "File is an image - " . $check["mime"] . ".";
			    $uploadOk = 1;
			} else {
			    echo "File is not an image.";
			    $uploadOk = 0;
			}
			// Allow certain file formats
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" ) {
			    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			    $uploadOk = 0;
			}
			
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
			    echo "Sorry, your file was not uploaded.";
			// if everything is ok, try to upload file
			} else {
			    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
			        //echo "The file ". basename( $_FILES["image"]["name"]). " has been uploaded.";
			    } else {
			        echo "Sorry, there was an error uploading your file.";
			    }
			}
		} else {
			$name_file = 0;
			//echo "del name"; 
		}

		

		if($idCategory == 0){
			newCategory($name, $parent, $name_file);
		}

		if($idCategory > 0 ){
			updateCategory($idCategory, $name, $parent, $name_file);
		}
		
	}

	if(isset($_GET['editcategory'])){

		$categories = getCategory($_GET['editcategory']);
		$idCategory = $categories['idCategory'];
		$name = $categories['name'];
		$parent = $categories['parent'];
		$image = $categories['image'];

	}
?>

<div>
	<?php if( isset( $_GET['editcategory'] ) ) { echo "<h2>Editar "; } else { echo "<h2>Añadir nueva "; } ?>

	categoría<a class='btn btn-warming' href='app.php?sec=categories' >Volver al listado</a><br></h2>
</div>

<div>
	<form id="form_report" class="appnitro" enctype="multipart/form-data" method="post" action="">

		<div class="col-md-4">

			<fieldset class="form-group">			
			    <label for="categoriesName">Nombre de la categoría</label>
			    <input name="name" type="text" class="form-control" id="categorysName" placeholder="" required autofocus value="<?php echo $name ?>"> 
			</fieldset>						
		
			<div class="checkbox">
			<h5><b>Categoría padre</b></h5>
				<select name="parent">
					<option value="0" selected >- - -</option>
					<?php

						$catList = getCategoryList();

						while($category = mysqli_fetch_array ( $catList )){
				  			$checked = "";
				  			if($category['idCategory'] == $parent ) $checked = "selected";
				  			//echo "papa :" . $parent . " == " . $category['idCategory'];
				  			echo "<option value=".$category['idCategory']." ".$checked." >".$category['name']."</option>";	
				  		}

				  	?>
				</select> 
			</div>	

			<div class="fieldForm">
				<input type="hidden" name="idCategory" value="<?php echo $idCategory ?>" />				    
				<input class="btn btn-primary"  type="submit" name="submit" value="Guardar categoría" />
			</div>

		</div>

			<div class="col-md-4">
				<img class='media-object' width="75px" src='./images/categories/<?php echo $image ?> ' alt="<?php echo $image ?>">
				<fieldset class="form-group">
			   		<label for="image">Imagen</label>
		    		<input type="file" name="image" accept="image/*" class="form-control" id="image" placeholder="" value="<?php echo $image ?>">
				</fieldset>				
			
		</div>
	</form>
</div>