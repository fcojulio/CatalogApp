<?php

	function getNumberOfClientsSql($sql){

		require_once('./controller/connection.php');
		$connection = open_connection();

		$sql = "SELECT COUNT(clients.idClient) AS total ".$sql." LIMIT 1";	
		//echo $sql;
		$result = mysqli_query($connection, $sql);
		$value = mysqli_fetch_object($result);
		$result = $value->total;
		close_connection($connection);

		return $result;
	}

	function listClients($searchClient, $to){

		require_once('./controller/connection.php');

		$connection = open_connection();
		$listClients = "";		
		$sql = "";
		$formSql = "";
		$show = 0;
		$from = $to - 20;

		if ( $from < 20 ){
			$from = 0;
		}	

		if ( is_string($searchClient) ){
			$upSearch = strtoupper($searchClient);
			$downSearch = strtolower($searchClient);
			$searchClient = "and (name LIKE \"%$upSearch%\" or name LIKE \"%$downSearch%\" 
							or nif LIKE \"%$upSearch%\" or nif LIKE \"%$downSearch%\"
							or company LIKE \"%$upSearch%\" or company LIKE \"%$downSearch%\"
							or postalcode LIKE \"%$upSearch%\" or postalcode LIKE \"%$downSearch%\"
							or address LIKE \"%$upSearch%\" or address LIKE \"%$downSearch%\"
							or city LIKE \"%$upSearch%\" or city LIKE \"%$downSearch%\"
							or province LIKE \"%$upSearch%\" or province LIKE \"%$downSearch%\"
							or phone LIKE \"%$upSearch%\" or phone LIKE \"%$downSearch%\"
							)";
			$show = 1;
		}else{
			$searchClient = "";
		}

		if( $_SESSION["isAdmin"] == TRUE ) {
			$sql = "select * from `clients` Where 1 $searchClient";
			$formSql = "from `clients` Where 1 $searchClient ";
			$show = 1;

		}else{
			$sql = "SELECT * FROM `clients` inner join `clientbusiness` on clients.idClient = clientbusiness.idClient WHERE clientbusiness.idBusiness =" . $_SESSION["sessionIdUser"] . " " . $searchClient . " ";
			$formSql = "FROM `clients` inner join `clientbusiness` on clients.idClient = clientbusiness.idClient WHERE clientbusiness.idBusiness =" . $_SESSION["sessionIdUser"] . " " . $searchClient;
		}
		
		//echo $sql;

		if ($show == 1){

			$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");

			$listClients .= "<div class='listClients' >
						<table class='table table-hover table-striped table-bordered' data-pagination='true'>
							<thead>
								<tr>
									<th>Nombre cliente</th>
									<th>Apellidos</th>
									<th>NIF/DNI</th>
									<th>Empresa</th>
									<th>Dirección</th>
									<th>Código postal</th>
									<th>Ciudad</th>
									<th>Provincia</th>
									<th>Teléfono</th>
									<th>Email</th>";
								
					if( $_SESSION["isAdmin"] == TRUE ) {
					 $listClients .= "<th>Editar</th>
					 				 <th>Deshabilitar</th>";
								}

					
					$listClients .= "</tr>
							</thead>
						</tbody>";

								while( $row = mysqli_fetch_array ( $result )){

									$listClients .= "<tr>";
									if( $_SESSION["isAdmin"] == TRUE ) {
										$listClients .= "<th>" . "<a href='app.php?sec=newClient&editclient=".$row['idClient']."'> " . $row['name'] . "</a>";
									}else{
										$listClients .= "<th>" . $row['name'];
									}	
										$listClients .= "<th>" . $row['surname'];
										$listClients .= "<th>" . $row['nif'];
										$listClients .= "<th>" . $row['company'];
										$listClients .= "<th>" . $row['address'];
										$listClients .= "<th>" . $row['postalcode'];
										$listClients .= "<th>" . $row['city'];
										$listClients .= "<th>" . $row['province'];
										$listClients .= "<th>" . $row['phone'];
										$listClients .= "<th>" . $row['email'];

										if( $_SESSION["isAdmin"] == TRUE ) {

											$listClients .= "<th>
																<a href = ' app.php?sec=newClient&editclient=".$row['idClient']."'> 
																<span class='glyphicon glyphicon-pencil' 'glyphicon glyphicon-pencil'></a></span>";

											if ($row['isActive'] == 1 ){
												$listClients .= "<th>
																	<a href = ' app.php?sec=clients&disableclient=".$row['idClient']." '>
																	<span class='glyphicon glyphicon-remove' 'glyphicon glyphicon-remove' ></span>";
											}else{
												$listClients .= "<th>";
											}
										}

									$listClients .= "</tr>";
								}

					$listClients .= "</tbody>";
				$listClients .= "</table>";
		}

		close_connection($connection);		

		//$numberOfClients = getNumberOfClientsSql($formSql);
		//$numPagination = $numberOfClients / 20;

		/*$listClients .= "<nav>
		  					<ul class='pagination pagination-lg' >";

						  	for ($i=1; $i < $numPagination+1; $i++) { 
						  		$n = $i*20;
						  		$listClients .= "<li class=''><a href='app.php?sec=clients&to=$n'>$i</a></li>";
						  	}    

		$listClients .= 	"</ul>
						</nav>";*/

		echo $listClients;
	}

	function disableClient($idClient){

		require_once('./controller/connection.php');
		$connection = open_connection();
		$sql = "UPDATE clients SET isActive = 0 WHERE `idClient` = $idClient";
		//echo $sql;
		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		echo "<div class='alert alert-info' role='alert'>Cliente deshabilitado</div>";
		close_connection($connection);
	}

	function newClient($name, $surname, $company, $address, $postalcode, $city, $province, $phone, $email, $isActive, $nif){

		require_once('./controller/connection.php');
		$connection = open_connection();
		$sql = "";

		$sql = "INSERT INTO `clients` (`idClient`, `name`, `surname`, `company`, `address`, `postalcode`, `city`, `province`, `phone`, `email`, `isActive`, `nif`) 
				VALUES (NULL, '$name', '$surname', '$company', '$address', '$postalcode', '$city', '$province', '$phone', '$email', '$isActive', '$nif');";

		//echo $sql;

		mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion, newClient");
		echo "<div class='alert alert-info' role='alert'>Cliente añadido</div>";
		close_connection($connection);
	}

	function getClient($idClient,$syn){

		if ( $syn == 1){
			require_once('./connection.php');
		}else{
			require_once('./controller/connection.php');
		}


		$sql = "SELECT * FROM `clients` WHERE `idClient` = $idClient";
		
		$connection = open_connection();
		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion, getClient");
		close_connection($connection);
		$result = mysqli_fetch_array ( $result );

		return $result;
	}

	function updateClient($idClient, $name, $surname, $company, $address, $postalcode, $city, $province, $phone, $email, $isActive, $nif){

		$sql = "UPDATE `clients` 
					SET  `name` = '$name',
						 `surname` = '$surname',
						 `company` = '$company',
						 `address` = '$address',
						 `postalcode` = '$postalcode',
						 `city` = '$city',
						 `province` = '$province',
						 `phone` = '$phone',
						 `email` = '$email',
						 `isActive` = '$isActive',
						 `nif` = '$nif'
					WHERE `idClient` = $idClient";

		//echo $sql;
		require_once('./controller/connection.php');
		$connection = open_connection();
		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion, newClient");
		echo "<div class='alert alert-info' role='alert'>Comercial modificado</div>";
		close_connection($connection);

	}

	function getNumberOfClients(){

		require_once('./controller/connection.php');
		$connection = open_connection();

		$sql = "SELECT COUNT(idClient) AS total FROM clients LIMIT 1";	
		$result = mysqli_query($connection, $sql);
		$value = mysqli_fetch_object($result);
		$result = $value->total;
		close_connection($connection);

		return $result;
	}

	function listSelClients($searchClient, $to){

		require_once('./controller/connection.php');

		$connection = open_connection();
		$listClients = "";		
		$sql = "";

		$from = $to - 20;

		if ( $from < 20 ){
			$from = 0;
		}	

		if ( is_string($searchClient) ){
			$upSearch = strtoupper($searchClient);
			$downSearch = strtolower($searchClient);
			$searchClient = "and (name LIKE \"%$upSearch%\" or name LIKE \"%$downSearch%\" 
							or nif LIKE \"%$upSearch%\" or nif LIKE \"%$downSearch%\"
							or company LIKE \"%$upSearch%\" or company LIKE \"%$downSearch%\"
							or postalcode LIKE \"%$upSearch%\" or postalcode LIKE \"%$downSearch%\"
							or address LIKE \"%$upSearch%\" or address LIKE \"%$downSearch%\"
							or city LIKE \"%$upSearch%\" or city LIKE \"%$downSearch%\"
							or province LIKE \"%$upSearch%\" or province LIKE \"%$downSearch%\"
							or phone LIKE \"%$upSearch%\" or phone LIKE \"%$downSearch%\"
							)";
		}else{
			$searchClient = "";
		}

		if( $_SESSION["isAdmin"] == TRUE ) {
			$sql = "select * from `clients` Where 1 $searchClient LIMIT $from,20";;
		}else{
			$sql = "SELECT * FROM `clients` inner join `clientbusiness` on clients.idClient = clientbusiness.idClient WHERE clientbusiness.idBusiness =" . $_SESSION["sessionIdUser"] . " " . $searchClient . " LIMIT $from,20";;
		}
				
		//echo $sql;
		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");

		$listClients .= "<div class='listClients' >
					<table class='table table-hover table-striped table-bordered' data-pagination='true'>
						<thead>
							<tr>
								<th>
								<th>Nombre cliente</th>
								<th>Apellidos</th>
								<th>Empresa</th>
								<th>Dirección</th>
								<th>Código postal</th>
								<th>Ciudad</th>
								<th>Provincia</th>
								<th>Teléfono</th>
								<th>Email</th>";
				
				$listClients .= "</tr>
						</thead>
					</tbody>";

							while( $row = mysqli_fetch_array ( $result )){

								$listClients .= "<tr>";
									$listClients .= "<th><form id='order_sel_client' class='appnitro' enctype='multipart/form-data' method='post' action='' >
														<input type='hidden' name='idClientToOrder' value= " . $row['idClient'] . " />				    
														<input class='btn btn-primary'  type='submit' name='submit' value='Seleccionar' />
													</form>";
									$listClients .= "<th>" . $row['name'];	
									$listClients .= "<th>" . $row['surname'];
									$listClients .= "<th>" . $row['company'];
									$listClients .= "<th>" . $row['address'];
									$listClients .= "<th>" . $row['postalcode'];
									$listClients .= "<th>" . $row['city'];
									$listClients .= "<th>" . $row['province'];
									$listClients .= "<th>" . $row['phone'];
									$listClients .= "<th>" . $row['email'];

								$listClients .= "</tr>";
							}

				$listClients .= "</tbody>";
			$listClients .= "</table>";
		close_connection($connection);
		echo $listClients;
	}

	
?>