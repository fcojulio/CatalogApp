<?php 
	require_once('./controller/shoppingcart.php');

	if(isset($_GET['ItemCart'])){
		remItemCart($_GET['ItemCart']);
	}

	if(isset($_POST['quantityToChange']) and isset($_POST['idCart']) ){
		changeQuantity($_POST['idCart'], $_POST['quantityToChange']);
	}

	$urlCatalog = $_SESSION['catalogurl'];
	$itemContext = $_SESSION['itemContext'];
?>


<?php
	if (countCart() > 0){
	?>

<div >
	<h2>Carrito</h2>	
	<a href='<?php echo $urlCatalog . "#" . $itemContext; ?>' class='btn btn-primary' role='button' style="height: 50px; width: 160px;" >Volver al catálogo</a>
	<a href='app.php?sec=selClient' class='btn btn-primary' role='button' style="height: 50px; width: 160px;" >Tramitar pedido</a>
</div>

<?php
		echo listCart();
	}else{
		echo "<div class='alert alert-info' role='alert'>El carrito está vacío</div>";
	}
?>