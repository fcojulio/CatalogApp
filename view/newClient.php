<?php
	
	require_once('./controller/clients.php');
	require_once('./controller/business.php');

	$idClient = 0;
	$name = "";
	$surname ="";	
	$company = "";
	$address = "";
	$postalcode = 0;
	$city = "";
	$province = "";
	$phone = 0;
	$nif = 0;
	$email = "";
	$isActive = 0;

	if(isset($_POST['idClient']) ){

		$idClient = $_POST['idClient'];
		$name = $_POST['name'];
		$surname = $_POST['surname'];
		$company = $_POST['company'];
		$address = $_POST['address'];
		$postalcode = $_POST['postalcode'];
		$city = $_POST['city'];
		$province = $_POST['province'];
		$phone = $_POST['phone'];
		$nif = $_POST['nif'];
		$email = $_POST['email'];

		if ( isset($_POST['isActive']) ){
			$isActive = $_POST['isActive'];
		}

		if($idClient == 0){
			newClient($name, $surname, $company, $address, $postalcode, $city, $province, $phone, $email, $isActive, $nif);	
		}

		if($idClient > 0 ){
			updateClient($idClient, $name, $surname, $company, $address, $postalcode, $city, $province, $phone, $email, $isActive, $nif);
		}
	}

	if(isset($_GET['editclient'])){

		$client = getClient($_GET['editclient'],0);
		$idClient = $client['idClient'];
		$name = $client['name'];
		$surname = $client['surname'];
		$company = $client['company'];
		$address = $client['address'];
		$postalcode = $client['postalcode'];
		$city = $client['city'];
		$province = $client['province'];
		$email = $client['email'];
		$phone = $client['phone'];
		$nif = $client['nif'];
		$isActive = $client['isActive'];
	}

	if(isset($_POST['idBusinessToRem']) and isset($_GET['editclient']) ){
		removeBusinessToClient($_POST['idBusinessToRem'], $_GET['editclient']);
	}
?>

<div>
	<?php if( isset( $_GET['editclient'] ) ) { echo "<h2>Editar "; } else { echo "<h2>Añadir nuevo "; } ?>

	cliente<a class='btn btn-warming' href='app.php?sec=clients' >Volver al listado</a><br></h2>
</div>

<div>
	<form id="form_report" class="appnitro" enctype="multipart/form-data" method="post" action="">
		<fieldset class="form-group">
		    <label for="businessName">Nombre cliente</label>
		    <input name="name" type="text" class="form-control" id="businessName" placeholder="" required autofocus value="<?php echo $name ?>"> 
		</fieldset>						
		<fieldset class="form-group">
		    <label for="surname">Apellidos</label>
		    <input name="surname" type="text" class="form-control" id="surname" placeholder="" required value="<?php echo $surname ?>">
		</fieldset>
		<fieldset class="form-group">
		    <label for="company">Empresa</label>
		    <input name="company" type="text" class="form-control" id="company" placeholder="" required value="<?php echo $company ?>">
		</fieldset>
		<fieldset class="form-group">
		    <label for="address">Dirección</label>
		    <input name="address" type="text" class="form-control" id="address" placeholder="" required value="<?php echo $address ?>">
		</fieldset>
		<fieldset class="form-group">
		    <label for="nif">NIF/DNI</label>
		    <input name="nif" type="text" class="form-control" id="nif" placeholder="" required value="<?php echo $nif ?>">
		</fieldset>
		<fieldset class="form-group">
		    <label for="postalcode">Código Postal</label>
		    <input name="postalcode" type="text" class="form-control" id="postalcode" placeholder="" required value="<?php echo $postalcode ?>">
		</fieldset>
		<fieldset class="form-group">
		    <label for="city">Ciudad</label>
		    <input name="city" type="text" class="form-control" id="city" placeholder="" required value="<?php echo $city ?>">
		</fieldset>
		<fieldset class="form-group">
		    <label for="province">Provincia</label>
		    <input name="province" type="text" class="form-control" id="province" placeholder="" required value="<?php echo $province ?>">
		</fieldset>
		<fieldset class="form-group">
		    <label for="phone">Teléfono</label>
		    <input name="phone" type="number" class="form-control" id="phone" placeholder="" value="<?php echo $phone ?>">
		  </fieldset>
		<fieldset class="form-group">
    		<label for="email">Email address</label>
    		<input name="email" type="email" class="form-control" id="email" placeholder="" value="<?php echo $email ?>" />
		</fieldset>
		<div class="checkbox">
			<h5><b>Cliente visible</b></h5>
		    <label>
		    	<input name="isActive" type="checkbox" value="1" <?php if( $isActive == 1) {echo "checked='checked'" ;} ?> > Activo
			</label>
		  </div>

		<div class="fieldForm">
			<input type="hidden" name="idClient" value="<?php echo $idClient ?>" />				    
			<input class="btn btn-primary"  type="submit" name="submit" value="Guardar cliente" />
		</div>
	</form>

	<h5><b>Comerciales asignados </b><a href="?sec=addBusinessClient&idClient=<?php echo $idClient ?>" >Añadir comercial</a></h5>
		<table class='table table-hover table-striped table-bordered' data-pagination='true' >
			<?php
				$busList = getClientBusinessList($idClient);
				while($business = mysqli_fetch_array ( $busList )){

					echo "<tr>";
					echo "<th><form id='order_client' class='appnitro' enctype='multipart/form-data' method='post' action='' >
							<input type='hidden' name='idBusinessToRem' value= ".$business['idUser']." />
							<input class='btn btn-primary'  type='submit' name='submit' value='Quitar' />
						</form>
						<th> ".$business['name'];
					echo "</tr>";
				}
			?>
	</table>

</div>