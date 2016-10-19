<?php
	
	function listBusiness(){

		require_once('./controller/connection.php');

		$connection = open_connection();
		$listBusiness = "";		

		$sql = "select * from `business` ORDER BY `idUser`";
		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");

		$listBusiness .= "<div class='listUsusers' >
					<table class='table table-hover table-striped table-bordered' data-pagination='true'>
						<thead>
							<tr>
								<th>Nombre comercial</th>
								<th>Usuario</th>
								<th>Email</th>
								<th>Teléfono</th>
								<th>Director comercial</th>
								<th>Activo</th>
								<th>Administrador</th>
								<th>Editar</th>
								<th>Deshabilitar</th>
							</tr>
						</thead>";

				$listBusiness .= "</tbody>";

							while( $row = mysqli_fetch_array ( $result )){

								if($row['isActive'] == 1){
									$imgCheck = 'empty40.png';
								}else{ 
									$imgCheck = 'check_1.png';
								}

								$listBusiness .= "<tr>";

									$listBusiness .= "<th>" . "<a href='app.php?sec=newBusiness&edituser=".$row['idUser']."'> " . $row['name'] . "</a>";
									$listBusiness .= "<th>" . $row['user'];
									$listBusiness .= "<th>" . $row['email'];
									$listBusiness .= "<th>" . $row['phone'];
									$listBusiness .= "<th>" . $row['parent'];

									if ($row['isActive'] == 1 ){
										$listBusiness .= "<th>SI";
									}else{
										$listBusiness .= "<th>NO";
									}

									if ($row['isAdmin'] == 1 ){
										$listBusiness .= "<th>SI";
									}else{
										$listBusiness .= "<th>NO";
									}
									
									$listBusiness .= "<th>
														<a href = ' app.php?sec=newBusiness&edituser=".$row['idUser']."'> 
														<span class='glyphicon glyphicon-pencil' 'glyphicon glyphicon-pencil'></a></span>";

									if ($row['isActive'] == 1 ){
										$listBusiness .= "<th>
															<a href = 'app.php?sec=business&disableuser=".$row['idUser']." '>
															<span class='glyphicon glyphicon-remove' 'glyphicon glyphicon-remove' ></span>";
									}else{
										$listBusiness .= "<th>";
									}									
								$listBusiness .= "</tr>";
							}

				$listBusiness .= "</tbody>";
			$listBusiness .= "</table>";
		close_connection($connection);
		echo $listBusiness;
	}

	function newBusiness($name, $user, $pass, $phone, $email, $isAdmin, $isActive, $parent, $categories){

		require_once('./controller/connection.php');
		$connection = open_connection();

		$sql = "INSERT INTO `business` (`idUSer`, `name`, `user`, `pass`, `phone`, `email`, `isAdmin`, `isActive`, `parent`, `categories`) 
				VALUES (NULL, '$name', '$user', '$pass', '$phone', '$email', '$isAdmin', '$isActive', '$parent', '$categories');";

		if($parent == 0){
			$sql = "INSERT INTO `business` (`idUSer`, `name`, `user`, `pass`, `phone`, `email`, `isAdmin`, `isActive`, `parent`, `categories`) 
				VALUES (NULL, '$name', '$user', '$pass', '$phone', '$email', '$isAdmin', '$isActive', NULL, '$categories');";
		}

		mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		echo "<div class='alert alert-info' role='alert'>Comercial añadido</div>";
		close_connection($connection);
	}

	function disableUser($idUser){

		require_once('./controller/connection.php');
		$connection = open_connection();
		$sql = "UPDATE business SET isActive = 0 WHERE `idUser` = $idUser";
		//echo $sql;
		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		echo "<div class='alert alert-info' role='alert'>Comercial deshabilitado</div>";
		close_connection($connection);
	}

	function getUser($idUser, $syn){

		if ( $syn == 1){
			require_once('./connection.php');
		}else{
			require_once('./controller/connection.php');
		}

		$sql = "SELECT * FROM `business` WHERE `idUser` = $idUser";
		$connection = open_connection();
		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		close_connection($connection);
		$result = mysqli_fetch_array ( $result );

		return $result;
	}

	function updateBusiness($idUser, $name, $user, $pass, $phone, $email, $isAdmin, $isActive, $parent, $categories){

		$sql = "UPDATE `business` 
					SET  `name` = '$name',
						 `user` = '$user',
						 `pass` = '$pass',
						 `phone` = '$phone',
						 `email` = '$email',
						 `isAdmin` = '$isAdmin',
						 `isActive` = '$isActive',
						 `parent` = '$parent', 
						 `categories` = '$categories'
					WHERE `idUser` = $idUser";

		if($parent == 0){
			$sql = "UPDATE `business` 
					SET  `name` = '$name',
						 `user` = '$user',
						 `pass` = '$pass',
						 `phone` = '$phone',
						 `email` = '$email',
						 `isAdmin` = '$isAdmin',
						 `isActive` = '$isActive',
						 `parent` = NULL, 
						 `categories` = '$categories'
					WHERE `idUser` = $idUser";
		}

		//echo $sql;
		require_once('./controller/connection.php');
		$connection = open_connection();
		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		echo "<div class='alert alert-info' role='alert'>Comercial modificado</div>";
		close_connection($connection);
	}

	function getBusinessList(){
		
		require_once('./controller/connection.php');

		$connection = open_connection();
		$sql = "SELECT * FROM `business` ";
		$businessList = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion(002)");
		close_connection($connection);

		return $businessList;
	}

	function getClientBusinessList($idClient){

		require_once('./controller/connection.php');

		$connection = open_connection();
		$sql = "SELECT business.* 
						FROM `clients` 
						RIGHT JOIN clientbusiness 
						ON clients.idClient = clientbusiness.idClient 
						RIGHT JOIN business 
						ON clientbusiness.idBusiness = business.idUser
						WHERE clients.idClient = $idClient";

		$businessList = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion(002)");
		close_connection($connection);

		return $businessList;
	}

	function getClientBusinessListToAdd($idClient){

		require_once('./controller/connection.php');

		$connection = open_connection();
		$sql = "SELECT * 
				FROM `business`
				WHERE `isActive` = 1";

		$businessList = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion(002)");
		close_connection($connection);

		return $businessList;
	}

	function addBussinesToClient($idClient, $idBusinessToClient){

		$sql = "INSERT INTO `clientbusiness` (`idCliBus`, `idClient`, `idBusiness`) VALUES (NULL, '$idClient', '$idBusinessToClient')";

		require_once('./controller/connection.php');
		$connection = open_connection();
		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		
		close_connection($connection);

	}
	
	function removeBusinessToClient($idBusiness, $idClient){
		$sql = "DELETE FROM `clientbusiness` WHERE `idClient` = '$idClient' and `idBusiness` = '$idBusiness'";
		//echo $sql;
		require_once('./controller/connection.php');
		$connection = open_connection();
		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		echo "<div class='alert alert-info' role='alert'>Comercial quitado</div>";
		close_connection($connection); 
	}

?>