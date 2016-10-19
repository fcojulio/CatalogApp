<?php 
	
	function open_connection(){

		$serverdatabase = "localhost";
		$userdatabase = "root";
		$passworddatabase = "password";
		$namedatabase = "catalog";

		$connection = mysqli_connect($serverdatabase,$userdatabase,$passworddatabase,$namedatabase);
		
		if (mysqli_connect_errno()){
  			echo "Failed to connect to MySQL: " . mysqli_connect_error();
  		}
 		
 		return $connection;
	}

	function close_connection($connection){
		mysqli_close($connection);
	}

?>