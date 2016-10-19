<?php

	function importStock(){		

		
	    $handle = fopen("./stock/stock.csv", "r");
	    

		if ($handle) {

			$count = 0;
			require_once('./controller/connection.php');
			$connection = open_connection();

		    while (($line = fgets($handle)) !== false) {				    	
		    	$pieces = explode(";", $line);
		        $sql = "UPDATE `items` SET `stock` = $pieces[1] WHERE `reference` = \"".$pieces[0]."\";";

		        //echo $sql;
		        
		        mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion, importación no completada.");
		        
		        $count++;
		    }

		    close_connection($connection);
		    fclose($handle);
		    echo "Se han importado " . $count . " registros.";

		} else {
		    // error opening the file.
		} 

	}

?>