<?php

	function viewOrderToSend($client, $business){

		require_once('./controller/connection.php');
		require_once('./controller/business.php');
		require_once('./controller/clients.php');

		$connection = open_connection();
		$listOrder = "";		

		$idUser = $_SESSION["sessionIdUser"];

		$sql = "select sum(quantity) AS quantityTotal from `shoppingcart` left join `items` ON shoppingcart.idItem = items.idItem WHERE idUser = $idUser ORDER BY `idCart` ";
		$quantityTotal = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		$quantityTotal = mysqli_fetch_array ( $quantityTotal );
		$quantityTotal = $quantityTotal['quantityTotal'];

		$sql = "select count(*) AS count from `shoppingcart` left join `items` ON shoppingcart.idItem = items.idItem WHERE idUser = $idUser ORDER BY `idCart` ";
		$count = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		$count = mysqli_fetch_array ( $count );
		$count = $count['count'];

		$sql = "select * from `shoppingcart` left join `items` ON shoppingcart.idItem = items.idItem  WHERE idUser = $idUser ORDER BY `idCart`";
		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");

		$client = getClient($client,0);
		$business = getUser($business,0);

		$listOrder .= "<h3>- Orden de compra</h3>
					<table class='table table-hover table-striped table-bordered' data-pagination='true'>
						<thead>
							
							<tr>
								<th>Fecha de pedido:  </th>
								<th>". date('d-m-Y h:i:s') ."</th>
							</tr>
							<tr>
								<th>Estado del pedido:  </th>
								<th>Pendiente</th>
							</tr>

						</thead>
					<table>";

		$listOrder .= "<h3>- Información del cliente</h3>
					<table class='table table-hover table-striped table-bordered' data-pagination='true'>
						<thead>
							<tr>
								<th>Nombre: </th>
								<th>" . $client['name'] . "</th>
							</tr>
							<tr>
								<th>Empresa: </th>
								<th>" . $client['company'] . "</th>
							</tr>
							<tr>
								<th>NIF/CIF: </th>
								<th>" . $client['nif'] . "</th>
							</tr>
							<tr>
								<th>Dirección: </th>
								<th>" . $client['address'] . "</th>
							</tr>
							<tr>
								<th>Ciudad: </th>
								<th>" . $client['city'] . "</th>
							</tr>
							<tr>
								<th>Provincia: </th>
								<th>" . $client['province'] . "</th>
							</tr>
							<tr>
								<th>C.P.: </th>
								<th>" . $client['postalcode'] . "</th>
							</tr>
							<tr>
								<th>Teléfono: </th>
								<th>" . $client['phone'] . "</th>
							</tr>
							<tr>
								<th>E-mail: </th>
								<th>" . $client['email'] . "</th>
							</tr>
						</thead>
					<table>";

		$listOrder .= "<div class='listUsusers' >
					<h3>- Productos del pedido</h3>
					<table class='table table-hover table-striped table-bordered' data-pagination='true'>
						<thead>
							<tr>
								<th></th>
								<th>Referencia</th>
								<th>Precio base</th>
								<th>Descuento</th>
								<th>Precio Final</th>
								<th>Cantidad</th>
								<th>Precio Total</th>
							</tr>
						</thead>";

				$listOrder .= "</tbody>";
				$priceTotal = 0;
				$counter = 0;
				$finalOrder = array($count);

							while( $row = mysqli_fetch_array ( $result )){

								$listOrder .= "<tr>";

									$listOrder .= "<th>" . "<img width='64px' src='./images/items/" . $row['image'] . "'>";
									$listOrder .= "<th>" . $row['reference'];
									$listOrder .= "<th>" . $row['pricebase'] . " €";
									$listOrder .= "<th>" . $row['discount'] . " %";
									$finalPrice = $row['pricebase'] - ($row['pricebase'] * ($row['discount']/100));
									
									$listOrder .= "<th>" . $finalPrice . " €";
									$listOrder .= "<th>" . $row['quantity'];
									$finalTotalPrice = $finalPrice * $row['quantity'] ;

									$listOrder .= "<th>" . $finalTotalPrice . " €";
									$priceTotal += $finalTotalPrice; 
						
								$listOrder .= "</tr>";

								$finalOrder[$counter] = array(
														'reference' => $row['reference'],
														'pricebase' => $row['pricebase'],
														'price' => $row['price'],
														'discount' => $row['discount'],
														'finalPrice' => $finalPrice,
														'quantity' => $row['quantity'],
														'finalTotalPrice' => $finalTotalPrice,
														 );
								$counter++;

								
							}

							$listOrder .= "<th>";
							$listOrder .= "<th>";
							$listOrder .= "<th>";
							$listOrder .= "<th>";
							$listOrder .= "<th><b>Total:</b>";
							$listOrder .= "<th>" . $quantityTotal;
							$listOrder .= "<th>" . $priceTotal . " €";							

							$finalTotal = array(												
												'quantityTotal' => $quantityTotal,
												'priceTotal' => $priceTotal,);

							//var_dump($finalTotal);
							//var_dump($finalOrder);

				$listOrder .= "</tbody>";
			$listOrder .= "</table>";

		close_connection($connection);	

		$_SESSION["finalTotal"] = $finalTotal;
		$_SESSION["finalOrder"] = $finalOrder;

		return $listOrder;
	}

	function getOrder($idOrder, $syn){

		if ( $syn == 1){
			require_once('./connection.php');
			echo " asdasd";
		}else{
			require_once('./controller/connection.php');
		}
		
		$connection = open_connection();

		$sql = "SELECT * FROM `orders` WHERE `idOrder` = $idOrder";
		
		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		close_connection($connection);
		$result = mysqli_fetch_array ( $result );

		return $result;
	}

	function sendOrder($idOrder, $syn){

		$order = 0;

		if($syn == 1){
			require_once('./business.php');
			require_once('./clients.php');
			require_once('./items.php');
			require_once('./dompdf-master/dompdf_config.inc.php');
			require_once('./phpMAILER/class.phpmailer.php');
			$order = getOrder($idOrder,1);
		} else{
			require_once('./controller/business.php');
			require_once('./controller/clients.php');
			require_once('./controller/items.php');
			require_once('./controller/dompdf-master/dompdf_config.inc.php');
			require_once('./controller/phpMAILER/class.phpmailer.php');
			$order = getOrder($idOrder);
		}		

		

		$date = $order['date'];
		$comments = $order['comments'];
		$items = unserialize($order['items']);
		$total = unserialize($order['total']);

		$client;
		$business;

		if ($syn == 1){
			$client = getClient($order['idClient'],1);
			$business = getUser($order['idUser'],1);
		}else{
			$client = getClient($order['idClient'],0);
			$business = getUser($order['idUser'],0);
		}
		

		$sendOrder = "";
		$sendOrder.= "<html>";
        $sendOrder.= "<head>";
        $sendOrder.= "<meta http-equiv='content-type' content='text/html; charset=UTF-8' /> "; 
        $sendOrder.= "</head>";
        $sendOrder.= "<body><div ><div style='width: 920px;' >";
		
		$sendOrder .= "<h3>- Orden de compra</h3>
					<table class='table table-hover table-striped table-bordered' data-pagination='true'>
						<thead>
							<tr>
								<th>Pedido:  </th>
								<th>" . $order['idOrderUser'] . "</th>
							</tr>
							<tr>
								<th>Fecha de pedido:  </th>
								<th>". date('d-m-Y h:i:s') ."</th>
							</tr>
							<tr>
								<th>Estado del pedido:  </th>
								<th>Pendiente</th>
							</tr>
							<tr>
								<th>Comercial:  </th>
								<th>" . $business['name'] . "</th>
							</tr>
						</thead>";

		$sendOrder .= "
						<thead>
							<tr>
								<th>Nombre cliente: </th>
								<th>" . $client['name'] . "</th>
							</tr>
							<tr>
								<th>Empresa: </th>
								<th>" . $client['company'] . "</th>
							</tr>
							<tr>
								<th>NIF/CIF: </th>
								<th>" . $client['nif'] . "</th>
							</tr>
							<tr>
								<th>Direcci&oacute;n: </th>
								<th>" . $client['address'] . "</th>
							</tr>
							<tr>
								<th>Ciudad: </th>
								<th>" . $client['city'] . "</th>
							</tr>
							<tr>
								<th>Provincia: </th>
								<th>" . $client['province'] . "</th>
							</tr>
							<tr>
								<th>C.P.: </th>
								<th>" . $client['postalcode'] . "</th>
							</tr>
							<tr>
								<th>Tel&eacute;fono: </th>
								<th>" . $client['phone'] . "</th>
							</tr>
							<tr>
								<th>E-mail: </th>
								<th>" . $client['email'] . "</th>
							</tr>
						</thead>
					<table>
					<br>Observaciones: ".$comments;

		$sendOrder .= "
				<h3>- Pedido: ".$idOrder." ".$date."</h3>

				<table class='table table-hover table-striped table-bordered' data-pagination='true'>
					
					<thead>
						<tr>
							<th></th>
							<th>Referencia</th>
							<th>Precio base</th>
							<th>Descuento</th>
							<th>Precio Final</th>
							<th>Cantidad</th>
							<th>Precio Total</th>
						</tr>
					</thead>
					<tbody>";


					$counter = 0;
					$nItems  = count($items);
					$img;

					while( $counter < $nItems ) {

						if ( $syn == 1){
							$img = getImageItemByRef($items[$counter]['reference'], 1);
						}else{
							$img = getImageItemByRef($items[$counter]['reference']);
						}
						
						$img = $img['image'];

						//
						$sendOrder .= "<tr>
										<th><img src='http://platisur-clientes.es/app/images/items/".$img."' style='width: 120px;' />
										<th> ".$items[$counter]['reference']."
										<th> ".$items[$counter]['pricebase']." &euro;
										<th> ".$items[$counter]['discount']." %
										<th> ".$items[$counter]['pricebase']." &euro;
										<th> ".$items[$counter]['quantity']." 
										<th> ".$items[$counter]['quantity'] * $items[$counter]['pricebase']." &euro;
									 </tr>";
						$totall = $totall + $items[$counter]['quantity'] * $items[$counter]['pricebase'];
						$counter++;
					}

					$sendOrder .= "<tr>
										<th>
										<th>
										<th>
										<th>
										<th>
										<th>" . $total['quantityTotal'] . "
										<th>" . $totall . " &euro;
									 </tr>
						

					</tbody>
				</table>"
				;

		$sendOrder.= "</div></div></body>";
        $sendOrder.= "</html>";
		//remove shopping cart	

        if ( ($_SERVER["HTTP_HOST"] == "localhost") or ($_SERVER["HTTP_HOST"] == "localhost:8080") ){
			
		}else{
			$dompdf = new DOMPDF();
	        $dompdf->load_html($sendOrder);
	        //$dompdf->set_paper("letter", "portrait" );
	        $dompdf->render();
	        $output = $dompdf->output();
	        if( $syn == 1 ) {
	        	$file_to_save = 'pdfs_syn/pedido_'.date('d-m-Y_hi').'.pdf';
	        } else {
	        	$file_to_save = 'pdfs/pedido_'.date('d-m-Y_hi').'.pdf';
	        }
	        
	        file_put_contents($file_to_save, $output); 

	        $mail = new phpmailer();
			$mail->Timeout=15;
			$mail->SetFrom("info@platisur-clientes.es", "Platisur Pedidos App");
	        $mail->AddReplyTo("info@platisur-clientes.es", "Platisur Pedidos App");
	        $mail->Subject ="Nuevo pedido num.";
	        $mail->AddAddress($client['email']);
	        $mail->AddAddress($business['email']);
	        $mail->AddAddress("pacoruiz@platisur.es");
	        $mail->AddAddress("info@platisur.es");
	        $mail->AddAddress("proyectos@malagamicro.com");
	        $mail->AddAddress("platisur.2016@gmail.com");

	        $mail->AddAttachment($file_to_save); 
	        $mail->Body=$sendOrder; 
	        $mail->IsHTML(true);			
	        $mail->Send();
		}

		return $sendOrder;

	}

	function addNewOrder($idUser, $idClient, $items, $total, $comments){

		require_once('./controller/connection.php');
		$connection = open_connection();
		$sql = "";

		$sql = "INSERT INTO `orders` (`idOrder`, `idUser`, `idClient`, `items`, `total`, `date`, `comments`) 
				VALUES (NULL, '$idUser', '$idClient', '$items', '$total', now(), '$comments' );";

		mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion, newOrder");

		$last_id = mysqli_insert_id($connection);
		$sql  = "UPDATE `orders` SET `idOrderUser`=concat(`idOrder`,`idUser`)";
		mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion, newOrder");
		//echo $sql;

		$idOrderUser = $last_id.$idUser;
		$insert = "INSERT INTO `orders` (`idOrder`, `idOrderUser`,`idUser`, `idClient`, `items`, `total`, `date`, `comments`) 
				VALUES (NULL, $idOrderUser,'$idUser', '$idClient', '$items', '$total', now(), '$comments' );";;

		$orders_file = "./syn/orders_file.syn";
		//echo $sql;
		$myfile = fopen($orders_file, "a") or die("Unable to open file!");
		fwrite($myfile, $insert . "\n");
		fclose($myfile);

		if ( ($_SERVER["HTTP_HOST"] == "localhost") or ($_SERVER["HTTP_HOST"] == "localhost:8080") ){
			
		}else{
			echo sendOrder($last_id);
		}	

		echo "<div class='alert alert-info' role='alert'>Pedido añadido</div>";

		$sql = "DELETE FROM `shoppingcart` WHERE idUser = $idUser";
		mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");		

		close_connection($connection);
	}

	function listOrders(){

		require_once('./controller/connection.php');
		require_once('./controller/business.php');
		require_once('./controller/clients.php');

		$connection = open_connection();
		$listOrders = "";		
		$sql = "";

		if( $_SESSION["isAdmin"] == TRUE ) {
			$sql = "select * from `orders` ORDER BY `date` DESC";
		}else{
			$sql = "select * from `orders` where `idUser` =  " .  $_SESSION["sessionIdUser"] . " ORDER BY `date` DESC ;";
		}
		
		//echo $sql;
		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");

		$listOrders .= "<div class='listOrders' >
					<table class='table table-hover table-striped table-bordered' data-pagination='true'>
						<thead>
							<tr>
								<th>Pedido</th>
								<th>Comercial</th>
								<th>Cliente</th>
								<th>Empresa</th>
								<th>Fecha</th>";
				
				$listOrders .= "</tr>
						</thead>
					</tbody>";

							while( $row = mysqli_fetch_array ( $result )){

								$client = getClient($row['idClient'],0);
								$business = getUser($row['idUser'],0);

								$listOrders .= "<tr>";
									$listOrders .= "<th><a style='font-size: 25px;' href='app.php?sec=viewOrder&order=".$row['idOrder']."' >" . $row['idOrderUser'] . "</a>";
									$listOrders .= "<th>" . $business['name'];
									$listOrders .= "<th>" . $client['name'];	
									$listOrders .= "<th>" . $client['company'];
									$listOrders .= "<th>" . $row['date'];

								$listOrders .= "</tr>";
							}

				$listOrders .= "</tbody>";
			$listOrders .= "</table>";
		close_connection($connection);
		echo $listOrders;
	}


?>