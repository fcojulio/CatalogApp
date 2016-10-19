<?php 

	function listItems($orderBy, $refItemToSearch, $to){

		require_once('./controller/connection.php');

		$connection = open_connection();
		$listItems = "";
		
		$from = $to - 10;

		if ( $from < 10 ){
			$from = 0;
		}		

		if( $refItemToSearch == "" ){
			$sql = "select * from `items` " . $orderBy. " ";
		}else{
			$upSearch = strtoupper($refItemToSearch);
			$downSearch = strtolower($refItemToSearch);

			$sql = "SELECT * FROM `items` WHERE `reference` LIKE \"%$upSearch%\" or `reference` LIKE \"%$downSearch%\" " . $orderBy . " ";
		}
		
		//echo $sql;
		
		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion 001");
		close_connection($connection);

		$listItems .= "<div class='table' >
					<table class='table table-hover table-striped table-bordered' data-pagination='true'>
						<thead>
							<tr>
								<th></th>
								<th><a href='app.php?sec=items&ordBy=ref' >Referencia</a></th>
								<th>Precio costo</a></th>
								<th>Precio P.V.P</a></th>
								<th><a href='app.php?sec=items&ordBy=sto' >Stock</th>
								<th>Orden</th>
								<th>Activo</a></th>
								<th>Activo Sin Stock</th>
								<th>Editar</th>
								<th>Deshabilitar</th>
								<th>
							</tr>
						</thead>";

				$listItems .= "</tbody>";

							while( $row = mysqli_fetch_array ( $result )){

								$stockColor = "";
								
								if ( $row['stock'] <= 0 ){
									$stockColor = "style = 'background-color: rgb(242, 174, 174); ' ";
								}

								$listItems .= "<tr  ". $stockColor ." >";

									$listItems .= "<th>" . "<a href='app.php?sec=newItem&edititem=".$row['idItem']."'><img width='48px' class='media-object' src='./images/items/".$row['image']."' alt=".$row['idItem']."></a>";
									$listItems .= "<th>" . "<a href='app.php?sec=newItem&edititem=".$row['idItem']."'>" . $row['reference'] . "</a>";
									$listItems .= "<th>" . $row['pricebase'] . " €";
									$listItems .= "<th>" . $row['price'] . " €";
									$listItems .= "<th>" . $row['stock'];
									$listItems .= "<th><form style='display: inline-flex;' id='order_item' class='appnitro' enctype='multipart/form-data' method='post' action='' >
														<input type='hidden' name='idItem' value= " . $row['idItem'] . " /> 
														<input style='width: 75px;' name='orderItemToChange' type='number' step='any' class='form-control' id='orderItemToChange' placeholder='' value='".$row['orderItem']."' > 
														<button class='btn btn-default' aria-label='Left Align' type='submit'>
														  <span class='glyphicon glyphicon-refresh' aria-hidden='true'></span>
														</button>
													   </form>
													   <form style='display: inline-flex;' id='order_item' class='appnitro' enctype='multipart/form-data' method='post' action='' >
														<input type='hidden' name='idItem' value= " . $row['idItem'] . " /> 
														<input type='hidden' name='orderItemToChangeUp' type='number' step='any' class='form-control' id='orderItemToChangeUp' placeholder='' value='1' > 
														<button class='btn btn-default' aria-label='Left Align' type='submit'>
														  <span class='glyphicon glyphicon-arrow-up' aria-hidden='true'></span>
														</button>
													   </form>
													   <form style='display: inline-flex;' id='order_item' class='appnitro' enctype='multipart/form-data' method='post' action='' >
														<input type='hidden' name='idItem' value= " . $row['idItem'] . " /> 
														<input type='hidden' name='orderItemToChangeDown' type='number' step='any' class='form-control' id='orderItemToChangeDown' placeholder='' value='1' > 
														<button class='btn btn-default' aria-label='Left Align' type='submit'>
														  <span class='glyphicon glyphicon-arrow-down' aria-hidden='true'></span>
														</button>
													   </form>";

									if ($row['isActive'] == 1 ){
										$listItems .= "<th>SI";
									}else{
										$listItems .= "<th>NO";
									}

									if ($row['isActiveNoStock'] == 1 ){
										$listItems .= "<th>SI";
									}else{
										$listItems .= "<th>NO";
									}
									
									$listItems .= "<th>
														<a href = ' app.php?sec=newItem&edititem=".$row['idItem']."'> 
														<span class='glyphicon glyphicon-pencil' 'glyphicon glyphicon-pencil'></a></span>";

									if ($row['isActive'] == 1 ){
										$listItems .= "<th>
															<a href = ' app.php?sec=items&to=10&disableitem=".$row['idItem']." '>
															<span class='glyphicon glyphicon-remove' 'glyphicon glyphicon-remove' ></span>";
									}else{
										$listItems .= "<th>";
									}	

									$listItems .= "<th>
													<form id='duplicate_item' class='appnitro' enctype='multipart/form-data' method='post' action='' >
													<input type='hidden' name='idItemToDuplicate' value= " . $row['idItem'] . " />				    
													<input class='btn btn-primary'  type='submit' name='submit' value='Duplicar' />
													</form>";

								$listItems .= "</tr>";
							}

				$listItems .= "</tbody>";
			$listItems .= "</table>";
		
		echo $listItems;
	}

	function disableItem($idItem){

		require_once('./controller/connection.php');
		$connection = open_connection();

		$sql = "UPDATE items SET isActive = 0 WHERE `idItem` = $idItem";

		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion 002");
		echo "<div class='alert alert-info' role='alert'>Artículo deshabilitado</div>";
		close_connection($connection);
	}

	function changeOrderitem($idItem, $orderItem){

		require_once('./controller/connection.php');
		$connection = open_connection();

		$sql = "UPDATE items SET `orderItem` = `orderItem` + $orderItem WHERE `idItem` = $idItem";

		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion 003");
		echo "<div class='alert alert-info' role='alert'>Artículo reordenado</div>";
		close_connection($connection);
	}

	function changeOrderitemTotal($idItem, $orderItem){

		require_once('./controller/connection.php');
		$connection = open_connection();

		$sql = "UPDATE items SET `orderItem` = $orderItem WHERE `idItem` = $idItem";

		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion 004");
		echo "<div class='alert alert-info' role='alert'>Artículo reordenado</div>";
		close_connection($connection);
	}

	function removeItem($idItem){

		require_once('./controller/connection.php');
		$connection = open_connection();

		$sql = "DELETE FROM items WHERE `idItem` = $idItem";

		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion 005");
		echo "<div class='alert alert-info' role='alert'>Artículo eliminado</div>";
		close_connection($connection);
	}

	function getItem($idItem){

		require_once('./controller/connection.php');
		$connection = open_connection();

		$sql = "SELECT * FROM `items` WHERE `idItem` = $idItem";
		
		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion 006");
		close_connection($connection);
		$result = mysqli_fetch_array ( $result );

		return $result;
	}

	function newItem($reference, $image, $stock, $isActive, $categoriesSelected, $price, $pricebase, $features, $isActiveNoStock, $discount, $casethickness, $casediameter, $idSection){

		require_once('./controller/connection.php');
		$connection = open_connection();

		$sql = "INSERT INTO `items` (`idItem`, `reference`, `image`, `stock`, `isActive`, `categories`, `price`, `pricebase`, `features`, `isActiveNoStock`, `discount`, `casethickness`, `casediameter`, `idSection`) 
				VALUES (NULL, '$reference', '$image', '$stock', '$isActive', '$categoriesSelected', '$price', '$pricebase', '$features', '$isActiveNoStock', '$discount', '$casethickness', '$casediameter', '$idSection');";

		//echo $sql;

		mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion 007");
		echo "<div class='alert alert-info' role='alert'>Artículo añadido <a href='app.php?sec=items'>Volver al listado</a></div>";
		close_connection($connection);
	}

	function updateItem($idItem, $reference, $image, $stock, $isActive, $categoriesSelected, $price, $pricebase, $features, $isActiveNoStock, $discount, $casethickness, $casediameter, $idSection){

		$sql = "UPDATE `items` 
					SET  `reference` = '$reference',
						 `image` = '$image',
						 `stock` = '$stock',
						 `isActive` = '$isActive',
						 `price` = '$price',
						 `discount` = '$discount',
						 `casethickness` = '$casethickness',
						 `casediameter` = '$casediameter',
						 `pricebase` = '$pricebase',
						 `features` = '$features',
						 `isActiveNoStock` = '$isActiveNoStock',
						 `categories` = '$categoriesSelected',
						 `idSection` = '$idSection'
					WHERE `idItem` = $idItem";

		if($image == 0){
			$sql = "UPDATE `items` 
					SET  `reference` = '$reference',
						 `stock` = '$stock',
						 `isActive` = '$isActive',
						 `price` = '$price',
						 `discount` = '$discount',
						 `casethickness` = '$casethickness',
						 `casediameter` = '$casediameter',
						 `pricebase` = '$pricebase',
						 `features` = '$features',
						 `isActiveNoStock` = '$isActiveNoStock',
						 `categories` = '$categoriesSelected',
						 `idSection` = '$idSection'
					WHERE `idItem` = $idItem";
		}
		//echo $sql;

		require_once('./controller/connection.php');
		$connection = open_connection();
		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion 008");
		echo "<div class='alert alert-info' role='alert'>Artículo modificado <a href='app.php?sec=items'>Volver al listado</a></div>";
		close_connection($connection);
	}

	function duplicateItem($idItem){

		require_once('./controller/connection.php');

		$connection = open_connection();
		$itemToDuplicate = getItem($idItem);
		$newReference  =$itemToDuplicate['reference'] . " - copia";

		$sql = "INSERT INTO `items` (`idItem`, `idSection`, `reference`, `image`, `stock`, `isActive`, `categories`, `price`, `pricebase`, `features`, `isActiveNoStock`, `discount`, `casethickness`, `casediameter`) 
				VALUES (NULL, '$itemToDuplicate[idSection]', 
								'$newReference',								
								'$itemToDuplicate[image]', 
								'$itemToDuplicate[stock]', 
								'$itemToDuplicate[isActive]', 
								'$itemToDuplicate[categories]', 
								'$itemToDuplicate[price]', 
								'$itemToDuplicate[pricebase]', 
								'$itemToDuplicate[features]', 
								'$itemToDuplicate[isActiveNoStock]', 
								'$itemToDuplicate[discount]', 
								'$itemToDuplicate[casethickness]', 
								'$itemToDuplicate[casediameter]'
						);";

		//echo $sql;

		mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion 009");
		echo "<div class='alert alert-info' role='alert'>Artículo duplicado</div>";
		close_connection($connection);
	}

	function getNumberOfItemsCatalog(){

		require_once('./controller/connection.php');
		$connection = open_connection();

		$sql = "SELECT COUNT(idItem) AS total FROM items WHERE (`stock` > 0 and `isActive` = 1) or `isActiveNoStock` = 1 LIMIT 1";	
		$result = mysqli_query($connection, $sql);
		$value = mysqli_fetch_object($result);
		$result = $value->total;
		close_connection($connection);

		return $result;
	}

	function getNumberOfItems(){

		require_once('./controller/connection.php');
		$connection = open_connection();

		$sql = "SELECT COUNT(idItem) AS total FROM items LIMIT 1";	
		$result = mysqli_query($connection, $sql);
		$value = mysqli_fetch_object($result);
		$result = $value->total;
		close_connection($connection);

		return $result;
	}

	function getIdNextItem($idItem, $idSection){
		require_once('./controller/connection.php');
		$connection = open_connection();

		$sql = "SELECT `idItem` FROM `items` ORDER BY `idItem` DESC LIMIT 1";
		$result = mysqli_query($connection, $sql);
		$value = mysqli_fetch_object($result);
		$result = $value->idItem;

		if ( $result != $idItem ){

			$where = "";
			if ( isset( $_GET['searchCatByRef']) ) {
				$searchCatByRef = $_GET['searchCatByRef'];
				$where = " AND `reference` LIKE '%$searchCatByRef%'";
			}

			$sql = "SELECT idItem FROM items WHERE idItem > $idItem AND idSection = $idSection AND `isActive` = 1 AND (`isActiveNoStock` = 1 or `stock` > 0) $where ORDER BY idItem LIMIT 1";
			//echo $sql;
			$result = mysqli_query($connection, $sql);
			$value = mysqli_fetch_object($result);
			$result = $value->idItem;
		}		

		close_connection($connection);
		//echo $result . " : " . $idItem . "\n";
		return $result;
	}

	function getImageItemByRef($reference, $syn){

		if ( $syn == 1){
			require_once('./connection.php');
		}else{
			require_once('./controller/connection.php');
		}
		
		$connection = open_connection();

		$sql = "SELECT * FROM `items` WHERE `reference` = \"$reference\"";
		//echo $sql;
		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion 010");
		close_connection($connection);
		$result = mysqli_fetch_array ( $result );

		return $result;
	}
?>