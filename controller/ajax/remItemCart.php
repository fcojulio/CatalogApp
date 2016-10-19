<?php

	function remItemCart($idItemCart){
		require_once('../connection.php');
		$connection = open_connection();

		$sql = "DELETE FROM `shoppingcart` WHERE `idCart` = $idItemCart";
		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		//echo "<div class='alert alert-info' role='alert'>Artículo eliminado del carrito</div>";
		close_connection($connection);
	}

	$idItemToRem = $_POST['idItemToRem'];
	remItemCart($idItemToRem);
?>
