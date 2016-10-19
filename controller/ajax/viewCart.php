<?php 

	function countCart($idUser){
		require_once('../connection.php');
		$connection = open_connection();
		$sql = "SELECT COUNT(*) as count FROM `shoppingcart` WHERE `idUser` = $idUser";
		//echo $sql;
		$count = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		close_connection($connection);

		$count = mysqli_fetch_array ( $count );
		$count = $count['count'];

		return $count;	
	}

	function listMiniCart($idUser){

		require_once('../connection.php');

		$connection = open_connection();
		$listCart = "";		

		$sql = "select sum(quantity) AS quantityTotal from `shoppingcart` left join `items` ON shoppingcart.idItem = items.idItem WHERE idUser = $idUser ORDER BY `idCart` ";
		$quantityTotal = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		$quantityTotal = mysqli_fetch_array ( $quantityTotal );
		$quantityTotal = $quantityTotal['quantityTotal'] . " " ;

		//$sql = "select TRUNCATE(sum(quantity*price), 2) AS priceTotal from `shoppingcart` left join `items` ON shoppingcart.idItem = items.idItem WHERE idUser = $idUser ORDER BY `idCart` ";
		//$priceTotal = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		//$priceTotal = mysqli_fetch_array ( $priceTotal );
		//$priceTotal = $priceTotal['priceTotal'];

		$sql = "select * from `shoppingcart` left join `items` ON shoppingcart.idItem = items.idItem  WHERE idUser = $idUser ORDER BY `idCart`";
		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");

		

		$listCart .= "<div class='listUsusers' >
					<table class='table table-hover table-striped table-bordered' data-pagination='true'>
						<thead>
							<tr>
								<th></th>
								<th>Referencia</th>
								<th>Precio costo unitario</th>
								<th>Cantidad</th>
								<th>Descuento</th>

								<th>Precio Final Unidad</th>
								
								<th>Precio total</th>
								<th>Quitar</th>
							</tr>
						</thead>";

				$listCart .= "</tbody>";
				$priceTotal = 0;

							while( $row = mysqli_fetch_array ( $result )){

								$listCart .= "<tr>";

									$listCart .= "<th>" . "<img width='64px' src='./images/items/" . $row['image'] . "'>";
									$listCart .= "<th>" . $row['reference'];
									$listCart .= "<th>" . $row['pricebase'] . " €";
									$listCart .= "<th><span style='font-size: 25px;' onclick=updateItemCart(".$row['idCart'].") class='glyphicon glyphicon-refresh' aria-hidden='true'></span>
													<input style='width: 50px;' name='quantityToChange".$row['idCart']."' type='number' step='any' class='form-control' id='quantityToChange".$row['idCart']."' placeholder='' value='".$row['quantity']."' />";

									//$listCart .= "<th>" . $row['price'] . " €";
									$listCart .= "<th>" . $row['discount'] . " %";
									$finalPrice = $row['pricebase'] - ($row['pricebase'] * ($row['discount']/100));
									
									$listCart .= "<th>" . $finalPrice . " €";
									
									$finalTotalPrice = $finalPrice * $row['quantity'] ;

									$listCart .= "<th>" . $finalTotalPrice . " €";
									$priceTotal += $finalTotalPrice; 

									$listCart .= "<th>" . "<a onclick=remItemCart(".$row['idCart'].",".$row['idItem']."); >
															<span  style='font-size: 25px;'' class='glyphicon glyphicon-remove' 'glyphicon glyphicon-remove' ></span></a>";
						
								$listCart .= "</tr>";							
							}

							$listCart .= "<th>";
							$listCart .= "<th><b>Total:</b>";
							$listCart .= "<th>";
							$listCart .= "<th>". $quantityTotal;
							$listCart .= "<th>";
							$listCart .= "<th>";
							$listCart .= "<th>" . $priceTotal . " €";
							$listCart .= "<th>" ;							
							//$listCart .= "<th>";

				$listCart .= "</tbody>";
			$listCart .= "</table>";
			

		close_connection($connection);
		return $listCart;
	}

	$idUser = $_POST['idUser'];

	if ( countCart($idUser) > 0 ){
		echo listMiniCart($idUser);
	} else {
		echo "<div class='alert alert-info' role='alert'>El carrito está vacío</div>";
	}
	
?>