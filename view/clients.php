<?php 
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

	if(isset($_GET['disableclient'])){
		disableClient($_GET['disableclient']);
	}	

?>

<div > <h2>Listado de clientes
			<?php if( $_SESSION["isAdmin"] == TRUE ) { ?>
				 <a class='btn btn-primary' a href="?sec=newClient" >Añadir nuevo cliente</a>
			<?php } ?>
		<br></h2></div>

<div>
	<form id='search_item' class='appnitro' enctype='multipart/form-data' method='GET' action='' >
		<label>Buscar cliente(nombre, dni, nif, empresa, dirección, postal): </label>		
		<input type='hidden' name='sec' value='clients' />	
		<input type='text' name='searchClient' />			    
		<input class='btn btn-primary' type='submit' value='Buscar' />
	</form>
</div>

<?php
	listClients($searchClient, $to);
?>