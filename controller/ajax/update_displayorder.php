<?php 
	
	if ( isset($_POST['ID'])){

		$newOrder = $_POST['ID'];
		$counter = 1;		

		require_once('../connection.php');
		$connection = open_connection();

	 	foreach ($newOrder as $recordIDValue) {

	  		$sql = "UPDATE items SET orderItem = " . $counter . " WHERE idItem = " . (int)$recordIDValue;  			  		
			$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");			
	  		$counter ++;
	 	}
	 	
	 	close_connection($connection);
		echo 'Orden guardado';
	}

?>