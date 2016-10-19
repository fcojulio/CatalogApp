<?php 

	require_once('./controller/items.php');

	if(isset($_POST['orderItemToChange'])){
		changeOrderitemTotal($_POST['idItem'], ($_POST['orderItemToChange']) );
	}

	if(isset($_POST['orderItemToChangeUp'])){
		changeOrderitem($_POST['idItem'], -($_POST['orderItemToChangeUp']) );
	}

	if(isset($_POST['orderItemToChangeDown'])){
		changeOrderitem($_POST['idItem'], +($_POST['orderItemToChangeDown']) );
	}

?>

<?php

	if ( isset($_GET['itemsSection']) ){

		require_once('./controller/connection.php');
		$connection = open_connection();

		$itemsSection = $_GET['itemsSection'];

		$sql = "SELECT * FROM items where idSection = $itemsSection ORDER BY orderItem ASC";
		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		close_connection($connection);
		echo "<div ><h2>Ordenar artículos<br></h2><a href='app.php?sec=orderItems' >Volver al listado de secciones</a></div>";
		echo "<button style='width: 70%' class='btn btn-primary' type='button' onclick=saveDisplayChanges() >Guardar orden</button><br><br>";
		echo "<ul id='categoryorder' class='list-group' >";

		while ($item = mysqli_fetch_array($result)){
			echo "<li id='ID_".$item['idItem']."' class='list-group-item' style='font-size: 40px; padding-left: 20px;' >
					<img src='./images/items/".$item['image']."' height='90' width='60' alt='No file on server' title='Preview' style='margin-right: 20px;'>".$item['reference']."

					<form style='display: inline-flex;' id='order_item' class='appnitro' enctype='multipart/form-data' method='post' action='' >
						<input type='hidden' name='idItem' value= " . $item['idItem'] . " /> 
						<input style='width: 75px;' name='orderItemToChange' type='number' step='any' class='form-control' id='orderItemToChange' placeholder='' value='".$item['orderItem']."' > 
						<button class='btn btn-default' aria-label='Left Align' type='submit'>
						  <span class='glyphicon glyphicon-refresh' aria-hidden='true'></span>
						</button>
					   </form>
					   <form style='display: inline-flex;' id='order_item' class='appnitro' enctype='multipart/form-data' method='post' action='' >
						<input type='hidden' name='idItem' value= " . $item['idItem'] . " /> 
						<input type='hidden' name='orderItemToChangeUp' type='number' step='any' class='form-control' id='orderItemToChangeUp' placeholder='' value='1' > 
						<button class='btn btn-default' aria-label='Left Align' type='submit'>
						  <span class='glyphicon glyphicon-arrow-up' aria-hidden='true'></span>
						</button>
					   </form>
					   <form style='display: inline-flex;' id='order_item' class='appnitro' enctype='multipart/form-data' method='post' action='' >
						<input type='hidden' name='idItem' value= " . $item['idItem'] . " /> 
						<input type='hidden' name='orderItemToChangeDown' type='number' step='any' class='form-control' id='orderItemToChangeDown' placeholder='' value='1' > 
						<button class='btn btn-default' aria-label='Left Align' type='submit'>
						  <span class='glyphicon glyphicon-arrow-down' aria-hidden='true'></span>
						</button>
					   </form></li>";
		}

		echo "</ul>";

	} else {

		require_once('./controller/sections.php');
		$subSections = getSubSections(1);
		echo "<div ><h2>Ordenar artículos<br></h2></div>";
		while( $sec = mysqli_fetch_array ( $subSections )){
				
			echo "<div class='col-sm-3 col-md-3' style='display: inline-block; float: none; text-align: center;'>
		            <div class='thumbnail' >
		                <a href='app.php?sec=orderItems&itemsSection=".$sec['idSection']."' >
			                <h3>".$sec['name']."</h3>                
			                <img src='./images/sections/".$sec['image']."' />                  
			            </a>
		            </div>
		        </div>";
		}
	}

?>