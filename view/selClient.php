<?php
	
	if ( isset($_POST['idClientToOrder'] ) and isset($_POST['sendOrder'] ) ){

		echo "<div ><h2>Guardado y enviado el pedido...</h2></div>";
		echo "<div ><h3><a href='app.php?sec=orders'>Ver listado de pedidos</a></h3></div>";

		require_once('./controller/orders.php');

		addNewOrder( $_SESSION["sessionIdUser"], $_POST['idClientToOrder'], serialize($_SESSION["finalOrder"]), serialize($_SESSION["finalTotal"]), $_POST['comments'] );

		$_SESSION["finalTotal"] = NULL;
		$_SESSION["finalOrder"] = null;

		//header("Location: app.php?sec=orders");
		die();
		
	} else if ( isset($_POST['idClientToOrder'] ) ) {

		require_once('./controller/orders.php');
		require_once('./controller/clients.php');
		require_once('./controller/business.php');

		$order = "<div class='order' style='max-width: 70%;' >";
		$order .= "<div><h2>Revisar pedido</h2></div>";

		$order .= viewOrderToSend($_POST['idClientToOrder'], $_SESSION["sessionIdUser"]);

		$order .= "<form id='order_client' class='appnitro' enctype='multipart/form-data' method='post' action='' >
					<input type='hidden' name='idClientToOrder' value= " . $_POST['idClientToOrder'] . " />
					<input class='form-control'type='hidden' name='sendOrder' value='1' />
					<label class='form-control' >Observaciones</label>		
					<textarea class='form-control' name='comments' rows='8' cols='50'></textarea>
					<input class='btn btn-primary' class='form-control' type='submit' name='submit' value='Guardar y enviar pedido' />
				</form>";

		$order .= "</div>";

		echo $order;

	}else {

		require_once('./controller/clients.php');

		$searchClient = 0;
	
		if(isset($_GET['to'])){
			$to = $_GET['to'];
		}else{
			$to = 1;
		}	

		if( isset( $_GET['searchClient'] ) ){
			$searchClient = $_GET['searchClient'];
		}

		echo "<div ><h2>Seleccionar cliente para el pedido</h2></div>

		<div>
			<form id='seacrh_sel_client' class='appnitro' enctype='multipart/form-data' method='GET' action='' >
				<label>Buscar cliente(nombre, dni, nif, empresa, dirección, postal): </label>		
				<input type='hidden' name='sec' value='selClient' />	
				<input type='text' name='searchClient' />			    
				<input class='btn btn-primary' type='submit' value='Buscar' />
			</form>
		</div>";
		
		echo listSelClients($searchClient, $to);
		
	}

?>

