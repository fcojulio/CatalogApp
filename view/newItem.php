<?php
	
	require_once('./controller/items.php');
	require_once('./controller/categories.php');
	require_once('./controller/sections.php');

	$idItem = 0;
	$price = 0;
	$pricebase = 0;
	$discount = 0;
	$casethickness = 0;
	$casediameter = 0;
	$reference = "";
	$stock ="";
	$image = "";
	$isActive = -1;
	$isActiveNoStock = 0;
	$features = "";
	$idSection = 0;
	$categoriesSelected = array(); 

	if(isset($_POST['idItem']) ){

		$idItem = $_POST['idItem'];
		$price = $_POST['price'];
		$pricebase = $_POST['pricebase'];
		$discount = $_POST['discount'];
		$casethickness = $_POST['casethickness'];
		$casediameter = $_POST['casediameter'];
		$reference = str_replace(' ', '',$_POST['reference']);
		$image = $_FILES["image"]["name"];
		$stock = $_POST['stock'];
		$features = $_POST['features'];
		$idSection = $_POST['idSection'];
		$name_file = $reference;

		if ( isset($_POST['isActive']) ){
			$isActive = $_POST['isActive'];
		}

		if ( isset($_POST['isActiveNoStock']) ){
			$isActiveNoStock = $_POST['isActiveNoStock'];
		}

		if( isset($_POST['categoriesSelected']) && is_array($_POST['categoriesSelected'])) {
			$categoriesSelected = $_POST['categoriesSelected'];
			$categoriesSelected = serialize($categoriesSelected);
		}

		if ( isset( $_FILES["image"] ) && !empty( $_FILES["image"]["name"] ) ) {
			
			$target_dir = "./images/items/";
			$uploadOk = 1;
			$imageFileType = pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION);
			$imageFileType = strtolower($imageFileType);
			$extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
			$name_file = $reference . "_" . date("jnY") . "." . $extension;
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

		if($idItem == 0){
			newItem($reference, $name_file, $stock, $isActive, $categoriesSelected, $price, $pricebase, $features, $isActiveNoStock, $discount, $casethickness, $casediameter, $idSection);	
			//header("Location: app.php?sec=items&to=10");
			die();
		}

		if($idItem > 0 ){
			updateItem($idItem, $reference, $name_file, $stock, $isActive, $categoriesSelected, $price, $pricebase, $features, $isActiveNoStock, $discount, $casethickness, $casediameter, $idSection);
			//header("Location: app.php?sec=items&to=10");
			die();
		}
	}

	if(isset($_GET['edititem'])){

		$item = getItem($_GET['edititem']);
		$idItem = $item['idItem'];
		$price = $item['price'];
		$pricebase = $item['pricebase'];
		$reference = $item['reference'];
		$stock = $item['stock'];
		$image = $item['image'];
		$isActive = $item['isActive'];
		$isActiveNoStock = $item['isActiveNoStock'];
		$features = $item['features'];
		$casethickness = $item['casethickness'];
		$casediameter = $item['casediameter'];
		$discount = $item['discount'];
		$categoriesSelected = $item['categories'];
		$idSection = $item['idSection'];

		if(isset($categoriesSelected)){
			$categoriesSelected = unserialize($categoriesSelected);
		}
	}

?>

<div>
	<?php if( isset( $_GET['edititem'] ) ) { echo "<h2>Editar "; } else { echo "<h2>Añadir nuevo "; } ?>artículo<a class='btn btn-warming' href='app.php?sec=items&to=10' >Volver al listado</a><br></h2>
			
	<?php 
		
		if ( isset($_POST['confirmDelItem']) ){
			echo "<form id='remove_item' class='appnitro' enctype='multipart/form-data' method='post' action='app.php?sec=items&to=10' >
					<input type='hidden' name='idItemToRemove' value= $idItem />				    
					<input class='btn btn-danger'  type='submit' name='submit' value='Confirmar eliminar artículo' /> <a class='btn btn-primary' a href='' >Cancelar</a>
				</form>
				";

		} else {

			echo "<form id='remove_item' class='appnitro' enctype='multipart/form-data' method='post' action='' >
				<input type='hidden' name='confirmDelItem' value= $idItem />				    
				<input class='btn btn-danger'  type='submit' name='submit' value='Eliminar artículo' />
			</form>";
		}

	?>
			
</div>

<div>
	<form id="form_report" class="appnitro" enctype="multipart/form-data" method="post" action="">
	<div class="col-md-4">
		<img class='media-object' width="100%" src='./images/items/<?php echo $image ?> ' alt="<?php echo $image ?>">

	</div>

	<div class="col-md-4">
		
		<fieldset class="form-group">
		    <label for="reference">Referencia</label>
		    <input name="reference" type="text" class="form-control" id="reference" placeholder="" required autofocus value="<?php echo $reference ?>"> 
		</fieldset>	
		
		<fieldset class="form-group">
		    <label for="pricebase">Precio Base(€)</label>
		    <input name="pricebase" type="number" step="any" class="form-control" id="pricebase" placeholder="" value="<?php echo $pricebase ?>">
		</fieldset>
		
		<fieldset class="form-group">
		    <label for="price">P.V.P.(€)</label>
		    <input name="price" type="number" step="any" class="form-control" id="price" placeholder="" value="<?php echo $price ?>">
		</fieldset>

		<fieldset class="form-group">
		    <label for="discount">Descuento(%)</label>
		    <input name="discount" type="number" step="any" class="form-control" id="discount" placeholder="" value="<?php echo $discount ?>">
		</fieldset>

		<fieldset class="form-group">
			    <label for="image">Imagen</label>
		    	<input type="file" name="image" accept="image/*" class="form-control" id="image" placeholder="" value="<?php echo $image ?>">
		</fieldset>		

		<fieldset class="form-group">
		    <label for="casethickness">Grosor caja(mm)</label>
		    <input name="casethickness" type="number" step="any" class="form-control" id="casethickness" placeholder="" value="<?php echo $casethickness ?>">
		</fieldset>

		<fieldset class="form-group">
		    <label for="casediameter">Diámetro caja(mm)</label>
		    <input name="casediameter" type="number" class="form-control" step="any" id="casediameter" placeholder="" value="<?php echo $casediameter ?>">
		</fieldset>			
			
		<fieldset class="form-group">
		    <label for="stock">Stock</label>
		    <input name="stock" type="number" class="form-control" id="stock" placeholder="" value="<?php echo $stock ?>">
		</fieldset>

		<fieldset class="form-group">
			<label for="stock">Mostrar</label><br>
		    <input name="isActive" type="checkbox" value="1" 
		    		<?php
		    		if( $isActive == 1 or $isActive == -1) {
		    			echo "checked='checked'" ;
		    		} 
		    		
		    		?>
		    > Activo
		</fieldset>

		<fieldset class="form-group">
			<label for="stock">Mostrar sin stock</label><br>
		    <input name="isActiveNoStock" type="checkbox" value="1" <?php if( $isActiveNoStock == 1) {echo "checked='checked'" ;} ?> > Activo
		</fieldset>

		<fieldset class="form-group">
			<label for="others">Otras características</label><br>
		    <textarea name="features" rows="8" cols="35"><?php echo $features ?></textarea>
		</fieldset>

	</div>

	<div class="col-md-4">
		<h5><b>Sección</b></h5>
			<select name="idSection">
				<?php

					$secList = getSectionList();

					while($section = mysqli_fetch_array ( $secList )){
			  			$checked = "";
			  			if($section['idSection'] == $idSection ) $checked = "selected";
			  			echo "<option value=".$section['idSection']." ".$checked." > - ".$section['name']."</option>";	
			  		}	  
			  	?>
		</select> 

		<div class="checkbox">
			<h5><b>Categorías del artículo</b></h5>
				
			  	<?php $categories = get_base_categories(); ?>
				<?php if(count($categories)>0):?>
					<ul>
						<?php foreach($categories as $cat):
							$checked = "";
			  				if (isset($categoriesSelected) && is_array($categoriesSelected) && in_array( $cat['idCategory'], $categoriesSelected ) == TRUE ){
			  					$checked  = "checked='checked'";
		  					}
		  				?>
							<li><input name=categoriesSelected[] type='checkbox' <?php echo $checked ?> value=<?php echo $cat['idCategory'] ?> /><?php echo $cat['name'] ?></label></li>
							<?php
								list_tree_cat_id_art($cat["idCategory"], $categoriesSelected);
							?>
						<?php endforeach;?>
					</ul>
						<?php else:?>
							<p class="alert alert-danger">No hay categorias</p>
						<?php endif;?>
		</div>

		<div class="fieldForm">
			<input type="hidden" name="idItem" value="<?php echo $idItem ?>" />				    
			<input class="btn btn-primary"  type="submit" name="submit" value="Guardar artículo" />
			</form>			
		</div>

		
	</div>
	
</div>