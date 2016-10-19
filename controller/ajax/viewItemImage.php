<?php

	require_once('../connection.php');

	function getItem($idItem){

		
		$connection = open_connection();

		$sql = "SELECT * FROM `items` WHERE `idItem` = $idItem";
		
		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		close_connection($connection);
		$result = mysqli_fetch_array ( $result );

		return $result;
	}

	if(isset($_POST['idItem'])){

		$item = getItem($_POST['idItem']);		
		$image = $item['image'];
		
	}

?>


	<div class="col-md-12">
		<img class='media-object' width="100%" src='./images/items/<?php echo $image ?> ' alt="<?php echo $image ?>">		
	</div>