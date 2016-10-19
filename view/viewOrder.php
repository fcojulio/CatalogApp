<?php
	
	require_once('./controller/orders.php');
	require_once('./controller/items.php');
	require_once('./controller/clients.php');
	require_once('./controller/business.php');

	$idOrder = 0;
	$items = 0;
	$total = 0;
	$date = "";
	
	if(isset($_GET['order'])){

		$order = getOrder($_GET['order'],0);

		$date = $order['date'];
		$comments = $order['comments'];
		$items = unserialize($order['items']);
		$total = unserialize($order['total']);
	}

	$client = getClient($order['idClient'],0);
	$business = getUser($order['idUser'],0);

?>

<div>
	<div class="col-md-10">
		<div>
			<h3>Pedido: <?php echo $_GET['order'] ?> - <?php echo $date ?></h3>
			<h3>Cliente: <?php echo $client['name'] ?> </h3>
			<h3>Comercial: <?php echo $business['name'] ?> </h3>
			<a class='btn btn-warming' href='app.php?sec=orders' >Volver al listado</a>
			<br>
		</div>

		<table class='table table-hover table-striped table-bordered' data-pagination='true'>
			
			<thead>
				<tr>
					<th></th>
					<th>Referencia</th>
					<th>Precio costo unitario</th>
					<th>Cantidad</th>
					<th>Descuento</th>
					<th>Precio Total</th>
				</tr>
			</thead>

			<tbody>

				<?php 

					$counter = 0;
					$nItems  = count($items);
					$img;
					$totall = 0;
					while( $counter < $nItems ) {



						$img = getImageItemByRef($items[$counter]['reference'],0);
						$img = $img['image'];

						echo "<tr>
								<th><img src='http://platisur-clientes.es/app/images/items/".$img."' style='width: 120px;' />
								<th>" . $items[$counter]['reference'] . "
								<th>" . $items[$counter]['pricebase'] . " €
								<th>" . $items[$counter]['quantity'] . "
								<th>" . $items[$counter]['discount'] . " %
								<th>" . $items[$counter]['pricebase'] * $items[$counter]['quantity'] . " €								
							 </tr>";
						$totall = $totall + $items[$counter]['pricebase'] * $items[$counter]['quantity'];
						$counter++;
					}

					echo "<tr>
								<th>
								<th>
								<th>
								<th>" . $total['quantityTotal'] . "
								<th>
								<th>" . $totall . " €
							 </tr>";
				
				?>

			</tbody>
		</table>

		Observaciones: <br>
		<?php echo $comments; ?>

	</div>
</div>