<?php
	
	if ( isset($_GET['idClient'] ) and isset($_POST['idBusinessToClient'] ) ){
		require_once('./controller/clients.php');
		require_once('./controller/business.php');

		addBussinesToClient($_GET['idClient'], $_POST['idBusinessToClient']);
		//header("Location: app.php?sec=newClient&editclient=".$_GET['idClient']);
		echo "<div class='alert alert-info' role='alert'>Comercial asignado</div>";
		//die();

	}

	if ( isset($_GET['idClient'] ) ) {

		require_once('./controller/clients.php');
		require_once('./controller/business.php');

		echo "<h5><b>Seleccionar comercial</b></h5><a class='btn btn-warming' href='app.php?sec=newClient&editclient=".$_GET['idClient']."' >Volver</a><br>";

		$busList = getClientBusinessListToAdd($_GET['idClient']);

		echo "<table class='table table-hover table-striped table-bordered' data-pagination='true' >";

			while($business = mysqli_fetch_array ( $busList )){
				echo "<tr>";
				echo "<th><form id='order_client' class='appnitro' enctype='multipart/form-data' method='post' action='' >
						<input type='hidden' name='idBusinessToClient' value= ".$business['idUser']." />
						<input class='btn btn-primary'  type='submit' name='submit' value='Asignar' />
					</form>
					<th> ".$business['name'];
				echo "</tr>";
			}
		echo "</table>";
	}

?>