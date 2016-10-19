<?php

	require_once('../connection.php');

	function get_base_categories(){
		
		$connection = open_connection();
		$sql = "select * from categories where parent is NULL";
		$categoryList = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion(002)");

		if($categoryList->num_rows>0){
			while ($r=$categoryList->fetch_array()) {
				$data[]=$r;
			}
		}
		return $data;
	}

	function get_cats_by_cat_id($id){

		$data= array();
		$connection = open_connection();
		$sql = "select * from categories where parent = $id";
		$query = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion(002)");

		if($query->num_rows>0){
			while ($r=$query->fetch_array()) {
				$data[]=$r;
			}
		}

		return $data;
	}

	function getItem($idItem){

		
		$connection = open_connection();

		$sql = "SELECT * FROM `items` WHERE `idItem` = $idItem";
		
		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		close_connection($connection);
		$result = mysqli_fetch_array ( $result );

		return $result;
	}

	function list_tree_cat_id_art_view($id, $listCat){
		$subs = get_cats_by_cat_id($id);
		if(count($subs)>0){
			echo "<ul>";
			foreach($subs as $s){

				if (isset($listCat) && is_array($listCat) && in_array( $s['idCategory'], $listCat ) == TRUE ){
					echo "<li style='margin: 8px;' >". $s['name'] . "</li>";
			  	}

				
				list_tree_cat_id_art_view($s["idCategory"], $listCat);
			}
			echo "</ul>";
		}
	}

	if(isset($_POST['idItem'])){

		$item = getItem($_POST['idItem']);
		$idItem = $item['idItem'];
		$idSection = $item['idSection'];
		$price = $item['price'];
		$pricebase = $item['pricebase'];
		$reference = $item['reference'];
		$stock = $item['stock'];
		$image = $item['image'];
		$isActive = $item['isActive'];
		$isActiveNoStock = $item['isActiveNoStock'];
		$features = $item['features'];
		$categoriesSelected = $item['categories'];
		$discount = $item['discount'];
		$casethickness = $item['casethickness'];
		$casediameter = $item['casediameter'];

		if(isset($categoriesSelected)){
			$categoriesSelected = unserialize($categoriesSelected);
		}
	}

?>


	<div class="col-md-4">
		<img  onclick=viewItemImage(<?php echo $_POST['idItem'] ?>) class='media-object' width="100%" src='./images/items/<?php echo $image ?> ' alt="<?php echo $image ?>">		
	</div>

	<div class="col-md-4">
		<div class="well well-sm">
			<h4>Referencia: <?php echo $reference ?></h4>	
		</div>	
		
		<div class="well well-sm">
			<h4>P.V.P.: <?php echo $price ?> €</h4>	
		</div>
		<div class="well well-sm">
			<h4>Descuento: <?php echo $discount ?> %</h4>	
		</div>
		<div class="well well-sm">
			<h4>Grosor caja: <?php echo $casethickness ?> mm</h4>	
		</div>
		<div class="well well-sm">
			<h4>Diámetro caja: <?php echo $casediameter ?> mm</h4>	
		</div>
		<div class="well well-sm">		
			<h4>Stock: <?php echo $stock ?></h4>
		</div>
		<div class="well well-sm">		    
			<h4>Otras características: <br>
			<?php echo $features ?></h4>
		</div>
	</div>

	<div class="col-md-4">
		<div class="checkbox">
			<div class="well well-sm">Características del artículo</div>
				
			  	<?php $categories = get_base_categories(); ?>
				<?php if(count($categories)>0):?>
					<ul>
						<?php foreach($categories as $cat):
							$checked = "";
			  				if (isset($categoriesSelected) && is_array($categoriesSelected) && in_array( $cat['idCategory'], $categoriesSelected ) == TRUE ){
			  					echo "<li>".$cat['name']."</li>";
		  					}
		  				?>
							
							<?php
								list_tree_cat_id_art_view($cat["idCategory"], $categoriesSelected);
							?>
						<?php endforeach;?>
					</ul>
						<?php else:?>
							<p class="alert alert-danger">No hay categorias</p>
						<?php endif;?>
		</div>

	</div>
