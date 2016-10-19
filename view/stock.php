<?php

	require_once('./controller/stock.php');

	// En versiones de PHP anteriores a la 4.1.0, debería utilizarse $HTTP_POST_FILES en lugar
	// de $_FILES.

	if( isset($_FILES['userfile']) ){

		$dir_subida = './stock/';
		$fichero_subido = $dir_subida . "stock.csv";

		$tipo_archivo = $_FILES['userfile']['type']; 
		$tamano_archivo = $_FILES['userfile']['size'];
		//compruebo si las características del archivo son las que deseo
		if ( !(strpos($tipo_archivo, "csv")) && ($tamano_archivo < 100000) ) { 

			if (move_uploaded_file($_FILES['userfile']['tmp_name'], $fichero_subido)) {
			    echo "El fichero es válido y se subió con éxito, se procede a importar el stock...\n";
			    importStock();
			} else {
			    echo "¡Posible ataque de subida de ficheros!\n";
			}
		}
	}
?>


<h2>Opciones de stock</h2>

<form enctype="multipart/form-data" action="" method="POST">
    <label>Importar Stock</label>
    <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
    Subir fichero: <input name="userfile" type="file" />
    <input type="submit" value="Subir fichero" />
</form>