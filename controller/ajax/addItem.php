<?php
	
	function addItemCart($idItemCart, $quantity, $idUser){
		require_once('../connection.php');
		$connection = open_connection();
		$sql = " INSERT INTO `shoppingcart` (`idCart`, `idUser`, `idItem`, `quantity`) VALUES (NULL, $idUser, $idItemCart, $quantity)";
		//echo $sql;
		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		//echo "<div class='alert alert-info' role='alert'>". $quantity ." articulos con ref: ". $idItemCart ." añadidos al carrito</div>";
		close_connection($connection);				
	}

	$idItemCart = $_POST['idItem'];
	$quantity = $_POST['quantity'];
	$idUser = $_POST['idUser'];

	addItemCart($idItemCart, $quantity, $idUser);
	//touch("test.txt");
?>