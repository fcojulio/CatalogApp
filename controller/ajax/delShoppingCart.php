<?php
	
	function delShoppingCart($idUser){
		require_once('../connection.php');
		$connection = open_connection();
		$sql = "DELETE FROM `shoppingcart` WHERE idUser = $idUser";
		//echo $sql;
		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		//echo "<div class='alert alert-info' role='alert'>". $quantity ." articulos con ref: ". $idItemCart ." añadidos al carrito</div>";
		close_connection($connection);				
	}

	delShoppingCart($_POST["idUser"]);
	//touch("test.txt");
?>