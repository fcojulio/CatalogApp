<?php 

	require_once('./controller/items.php');

	$refItemToSearch = "";
	$orderBy = "ORDER BY `orderItem` ASC";
	$to = 1;

	if(isset($_POST['orderItemToChange'])){
		changeOrderitemTotal($_POST['idItem'], ($_POST['orderItemToChange']) );
	}

	if(isset($_POST['orderItemToChangeUp'])){
		changeOrderitem($_POST['idItem'], -($_POST['orderItemToChangeUp']) );
	}

	if(isset($_POST['orderItemToChangeDown'])){
		changeOrderitem($_POST['idItem'], +($_POST['orderItemToChangeDown']) );
	}

	if(isset($_GET['disableitem'])){
		disableItem($_GET['disableitem']);
	}

	if(isset($_POST['idItemToDuplicate'])){
		duplicateItem($_POST['idItemToDuplicate']);
	}

	if(isset($_POST['idItemToRemove'])){
		removeItem($_POST['idItemToRemove']);
	}
	
	if( isset( $_POST['searchItemByRef'] ) ){
		$refItemToSearch = $_POST['searchItemByRef'];
	}

	if(isset($_GET['ordBy']) ){

		$ord = $_GET['ordBy'];

		switch ($ord) {
			case 'ref':
				$orderBy = "ORDER BY `reference` ASC";
				break;
			case 'sto':
				$orderBy = "ORDER BY `stock` ASC";
				break;
			default:
				break;
		}
	}

	if(isset($_GET['to'])){
		$to = $_GET['to'];
	}

?>

<div ><h2>Listado de artículos <a class='btn btn-primary' a href="?sec=newItem" >Añadir nuevo artículo</a><br></h2></div>

<div>
	<form id='search_item' class='appnitro' enctype='multipart/form-data' method='POST' action='' >
		<label>Buscar artículo por referencia: </label>
		<input type='text' name='searchItemByRef' />				    
		<input class='btn btn-primary' type='submit' value='Buscar' />
	</form>
</div>

<?php
	echo listItems($orderBy, $refItemToSearch, $to);
	//$numberOfItems = getNumberOfItems();

	//$numPagination = $numberOfItems / 10;
?>

<!--<nav>
  <ul class="pagination pagination-lg" >

  <?php
  	/*for ($i=1; $i < $numPagination+1; $i++) { 
  		$n = $i*10;
  		echo "<li class=''><a href='app.php?sec=items&to=$n'>$i</a></li>";
  	}  */  
  ?>
  </ul>
</nav>-->