<?php
	
	include_once("./orders.php");

	$idOrder = $_GET['idOrder'];
	sendOrder($idOrder, 1);

?>