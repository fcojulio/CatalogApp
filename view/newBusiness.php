<?php
	
	require_once('./controller/business.php');
	require_once('./controller/categories.php');
	require_once('./controller/sections.php');

	$idUser = 0;
	$name = "";
	$user ="";
	$pass = "";
	$phone = 0;
	$email = "";
	$isAdmin = 0;
	$isActive = 0;
	$sectionsSelected;
	$parent = 0;

	if(isset($_POST['idUser']) ){

		$idUser = $_POST['idUser'];
		$name = $_POST['name'];
		$user = $_POST['user'];
		$pass = $_POST['pass'];
		$phone = $_POST['phone'];
		$email = $_POST['email'];
		$parent = $_POST['parent'];

		if ( isset($_POST['isAdmin']) ){
			$isAdmin = $_POST['isAdmin'];
		}

		if ( isset($_POST['isActive']) ){
			$isActive = $_POST['isActive'];
		}

		if( isset($_POST['sectionsSelected']) && is_array($_POST['sectionsSelected'])) {
			$sectionsSelected = $_POST['sectionsSelected'];
			$sectionsSelected = serialize($sectionsSelected);
		}


		if($idUser == 0){
			newBusiness($name, $user, $pass, $phone, $email, $isAdmin, $isActive, $parent, $sectionsSelected);	
		}

		if($idUser > 0 ){
			updateBusiness($idUser, $name, $user, $pass, $phone, $email, $isAdmin, $isActive, $parent, $sectionsSelected);
		}
	}

	if(isset($_GET['edituser'])){

		$business = getUser($_GET['edituser'],0);
		$idUser = $business['idUser'];
		$name = $business['name'];
		$user = $business['user'];
		$pass = $business['pass'];
		$email = $business['email'];
		$phone = $business['phone'];
		$isAdmin = $business['isAdmin'];
		$isActive = $business['isActive'];
		$parent = $business['parent'];
		$sectionsSelected = $business['categories'];
	}

?>

<div>
	<?php if( isset( $_GET['edituser'] ) ) { echo "<h2>Editar "; } else { echo "<h2>Añadir nuevo "; } ?>

	comercial<a class='btn btn-warming' href='app.php?sec=business' >Volver al listado</a><br></h2>
</div>

<div>
	<form id="form_report" class="appnitro" enctype="multipart/form-data" method="post" action="">
		<fieldset class="form-group">
		    <label for="businessName">Nombre comercial</label>
		    <input name="name" type="text" class="form-control" id="businessName" placeholder="" required autofocus value="<?php echo $name ?>"> 
		</fieldset>						
		<fieldset class="form-group">
		    <label for="user">Usuario</label>
		    <input name="user" type="text" class="form-control" id="user" placeholder="" required value="<?php echo $user ?>">
		</fieldset>
		<fieldset class='form-group'>
			<label for='pass'>Password</label>
			<input name='pass' type='pass' class='form-control' id='pass' placeholder='' required value='<?php echo $pass ?>' >
		</fieldset>	
		<fieldset class="form-group">
		    <label for="phone">Teléfono</label>
		    <input name="phone" class="form-control" id="phone" placeholder="" value="<?php echo $phone ?>">
		  </fieldset>
		<fieldset class="form-group">
    		<label for="email">Email address</label>
    		<input name="email" type="email" class="form-control" id="email" placeholder="" value="<?php echo $email ?>" />
		</fieldset>
		
		<div class="checkbox">
			<h5><b>Comercial padre</b></h5>
			<select name="parent">
				<option value="0" selected >- - -</option>
				<?php

					$busList = getBusinessList();

					while($business = mysqli_fetch_array ( $busList )){
			  			$checked = "";
			  			if($business['idUser'] == $parent ) $checked = "selected";
			  			//echo "papa :" . $parent . " == " . $business['idSection'];
			  			echo "<option value=".$business['idUser']." ".$checked." >".$business['name']."</option>";	
			  		}

			  	?>
			</select> 
		</div>	

		<div class="checkbox">
			<h5><b>Categorías del comercial</b></h5>
				<?php

					$secList = getSectionList();
					if(isset($sectionsSelected)){
						$sectionsSelected = unserialize($sectionsSelected);
					}

					while($section = mysqli_fetch_array ( $secList )){
						$checked = "";
		  				if (isset($sectionsSelected) && is_array($sectionsSelected) && in_array( $section['idSection'], $sectionsSelected ) == TRUE ){
		  					$checked = "checked='checked'";
		  				}

			  			echo "<label> <input name=sectionsSelected[] type='checkbox' value=".$section['idSection']." " . $checked ." >  " . $section['name'] . "  </label></br>";	
			  		}

			  	?>
		</div>

		<div class="checkbox">
			<h5><b>Nivel de Acceso</b></h5>
		    <label>
		      <input name="isAdmin" type="checkbox" value="1" <?php if( $isAdmin == 1) {echo "checked='checked'" ;} ?> > Administrador 
		    </label></br>
		    <label>
		    	<input name="isActive" type="checkbox" value="1" <?php if( $isActive == 1) {echo "checked='checked'" ;} ?> > Activo
			</label>
		  </div>

		<div class="fieldForm">
			<input type="hidden" name="idUser" value="<?php echo $idUser ?>" />				    
			<input class="btn btn-primary"  type="submit" name="submit" value="Guardar usuario" />
		</div>
	</form>
</div>