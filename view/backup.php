<?php

	require_once('./syn/bk_tables.php');
	require_once('./syn/bk_structure.php');

	if ( isset($_GET['bk']) ){

		if ( $_GET['bk'] == 101 ){
			Export_Database();
			
		}

		if ( $_GET['bk'] == 102 ){
			Export_Database_Stuctrure();
			
		}
		
	}

?>

<h2>Opciones de copia de seguridad</h2>

<p><a href="app.php?sec=backup&bk=101" > Forzar backup de la informatción en la base de datos para sync </a></p>
<p><a href="app.php?sec=backup&bk=102" > Forzar backup de la estructura de la base de datos para sync </a></p>