<?php

	function updateItemCart($idItemCart, $quantityToChange){

		require_once('../connection.php');

		$connection = open_connection();

		$sql = "UPDATE `shoppingcart` SET `quantity` = '$quantityToChange' WHERE `shoppingcart`.`idCart` = $idItemCart";
		//echo $sql;
		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		//echo "<div class='alert alert-info' role='alert'>Artículo modificado</div>";
		close_connection($connection);
	}

	$idItemToUpdate = $_POST['idItemToUpdate'];
	$quantityToChange = $_POST['quantityToChange'];

	updateItemCart($idItemToUpdate, $quantityToChange);
?>
