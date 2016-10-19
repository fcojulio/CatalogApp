<?php
	
	function changeQuantity($idCart, $quantityToChange){
		require_once('./controller/connection.php');
		$connection = open_connection();

		$sql = "UPDATE `shoppingcart` SET `quantity` = '$quantityToChange' WHERE `shoppingcart`.`idCart` = $idCart";
		//echo $sql;
		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		echo "<div class='alert alert-info' role='alert'>Artículo modificado</div>";
		close_connection($connection);
	}

	function remItemCart($idItemCart){
		require_once('./controller/connection.php');
		$connection = open_connection();

		$sql = "DELETE FROM `shoppingcart` WHERE `idCart` = $idItemCart";
		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		echo "<div class='alert alert-info' role='alert'>Artículo eliminado del carrito</div>";
		close_connection($connection);
	}

	function addItemCart($idItemCart, $quantity){
		require_once('./controller/connection.php');
		$connection = open_connection();
		$idUser = $_SESSION["sessionIdUser"];
		$sql = " INSERT INTO `shoppingcart` (`idCart`, `idUser`, `idItem`, `quantity`) VALUES (NULL, $idUser, $idItemCart, $quantity)";
		//echo $sql;
		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		//echo "<div class='alert alert-info' role='alert'>". $quantity ." articulos con ref: ". $idItemCart ." añadidos al carrito</div>";
		close_connection($connection);				
	}

	function countCart(){
		require_once('./controller/connection.php');
		$connection = open_connection();
		$idUser = $_SESSION["sessionIdUser"];
		$sql = "SELECT COUNT(*) as count FROM `shoppingcart` WHERE `idUser` = $idUser";
		//echo $sql;
		$count = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		close_connection($connection);

		$count = mysqli_fetch_array ( $count );
		$count = $count['count'];

		return $count;	
	}

	function listCart(){

		require_once('./controller/connection.php');

		$connection = open_connection();
		$listCart = "";		

		$idUser = $_SESSION["sessionIdUser"];

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
									
									$listCart .= "<th><form style='display: inline-flex;' id='order_client' class='appnitro' enctype='multipart/form-data' method='post' action='' >
													<input type='hidden' name='idCart' value= " . $row['idCart'] . " /> 
													<input style='width: 50px;' name='quantityToChange' type='number' step='any' class='form-control' id='quantityToChange' placeholder='' value='".$row['quantity']."' > 
													<button class='btn btn-default' aria-label='Left Align' type='submit'>
													  <span class='glyphicon glyphicon-refresh' aria-hidden='true'></span>
													</button>
												</form>";

									//$listCart .= "<th>" . $row['price'] . " €";
									$listCart .= "<th>" . $row['discount'] . " %";
									$finalPrice = $row['pricebase'] - ($row['pricebase'] * ($row['discount']/100));
									
									$listCart .= "<th>" . $finalPrice . " €";
									
									$finalTotalPrice = $finalPrice * $row['quantity'] ;

									$listCart .= "<th>" . $finalTotalPrice . " €";
									$priceTotal += $finalTotalPrice; 

									$listCart .= "<th>" . "<a href='app.php?sec=shoppingcart&ItemCart=".$row['idCart']." ' >
															<span class='glyphicon glyphicon-remove' 'glyphicon glyphicon-remove' ></span></a>";
						
								$listCart .= "</tr>";							
							}

							$listCart .= "<th>";
							$listCart .= "<th><b>Total:</b>";
							$listCart .= "<th>";
							$listCart .= "<th>". $quantityTotal;
							$listCart .= "<th>";
							$listCart .= "<th>" ;
							$listCart .= "<th>" . $priceTotal . " €";							
							$listCart .= "<th>";

				$listCart .= "</tbody>";
			$listCart .= "</table>";
			

		close_connection($connection);
		return $listCart;
	}

	function listMiniCart(){

		require_once('./controller/connection.php');
		
		$connection = open_connection();
		$listCart = "";		

		$idUser = $_SESSION["sessionIdUser"];

		$sql = "select sum(quantity) AS quantityTotal from `shoppingcart` left join `items` ON shoppingcart.idItem = items.idItem WHERE idUser = $idUser ORDER BY `idCart` ";
		$quantityTotal = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		$quantityTotal = mysqli_fetch_array ( $quantityTotal );
		$quantityTotal = $quantityTotal['quantityTotal'] . " " ;

		$sql = "select TRUNCATE(sum(quantity*price), 2) AS priceTotal from `shoppingcart` left join `items` ON shoppingcart.idItem = items.idItem WHERE idUser = $idUser ORDER BY `idCart` ";
		$priceTotal = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		$priceTotal = mysqli_fetch_array ( $priceTotal );
		$priceTotal = $priceTotal['priceTotal'];

		$sql = "select * from `shoppingcart` left join `items` ON shoppingcart.idItem = items.idItem  WHERE idUser = $idUser ORDER BY `idCart`";
		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		
		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$url = strtok($url, '&'); 

		$listCart .= "<div class='listUsusers' >
					<table class='table table-hover table-striped table-bordered' data-pagination='true'>
						<thead>
							<tr>
								<th></th>
								<th>Referencia</th>
								<th>Cantidad</th>
								<th>Precio artículo</th>
								<th>Precio total</th>
							</tr>
						</thead>";

				$listCart .= "</tbody>";

							while( $row = mysqli_fetch_array ( $result )){

								$listCart .= "<tr>";

									$listCart .= "<th>" . "<img width='64px' src='./images/items/" . $row['image'] . "'>";
									$listCart .= "<th>" . $row['reference'];
									$listCart .= "<th>" . $row['quantity'];
									$listCart .= "<th>" . $row['price'];
									$listCart .= "<th>" . $row['price'] * $row['quantity'];
								$listCart .= "</tr>";
							}

							$listCart .= "<th>";
							$listCart .= "<th><b>Total:</b>";
							$listCart .= "<th>" . $quantityTotal;
							$listCart .= "<th>";
							$listCart .= "<th>" . $priceTotal . " €";
							

				$listCart .= "</tbody>";
			$listCart .= "</table>";
		close_connection($connection);
		return $listCart;
	}

	function getReferencesInCart(){		

		require_once('./controller/connection.php');
		$connection = open_connection();
		$idUser = $_SESSION["sessionIdUser"];

		$sql = "select `reference` from `shoppingcart` left join `items` ON shoppingcart.idItem = items.idItem  WHERE idUser = $idUser ORDER BY items.idItem";

		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		close_connection($connection);

		$referencesInCart = array();
		
		while( $row = mysqli_fetch_array ( $result )){
			$referencesInCart[] = $row['reference'];
		}

		return $referencesInCart;
	}

	function getLastItem(){

			require_once('./controller/connection.php');
			$connection = open_connection();

			$idBusiness = $_SESSION["sessionIdUser"];
			$sql = "SELECT idItem FROM shoppingcart WHERE idUser = $idBusiness ORDER BY `idCart` ASC LIMIT 1 ";

			//echo $result . " : " . $idItem . "\n";
			$result = mysqli_query($connection, $sql);
			close_connection($connection);
			$value = mysqli_fetch_object($result);
			$result = $value->idItem;

			return $result;
		}		
		
?>