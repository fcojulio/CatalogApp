<?php 
	require_once('./controller/business.php');

	if(isset($_GET['disableuser'])){
		disableUser($_GET['disableuser']);
	}
?>

<div > <h2>Listado de comerciales <a class='btn btn-primary' a href="?sec=newBusiness" >Añadir nuevo comercial</a><br> </h2></div>

<?php
	
	echo listBusiness();

?>