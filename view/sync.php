<?php	

	if ( isset($_GET['syn']) ){

		if ( $_GET['syn'] == 201 ){
			require_once('./syn/sync_bd.php');
			Sync_master();			
		}		
	}

?>

<h2>Opciones de sincronizacion</h2>

<h2><a href="app.php?sec=sync&syn=201" class='btn btn-primary' role='button' style="height: 50px; width: 350px;" > Sincronizarse con el servidor maestro </a></h2>
