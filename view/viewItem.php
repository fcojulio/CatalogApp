<?php
	
	require_once('./controller/items.php');
	require_once('./controller/categories.php');

	$idItem = 0;
	$price = 0;
	$pricebase = 0;
	$reference = "";
	$stock ="";
	$image = "";
	$isActive = 0;
	$isActiveNoStock = 0;
	$features = "";
	$categoriesSelected = array(); 


	if(isset($_GET['item'])){

		$item = getItem($_GET['item']);
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

		$_SESSION['itemContext'] = getIdNextItem($idItem, $idSection);
		//$_SESSION['catalogurl'] = "?sec=catalog&cat=".$idSection."&all=".$idSection;	
		$urlCatalog = $_SESSION['catalogurl'] ."#".$_SESSION['itemContext'];

		if(isset($categoriesSelected)){
			$categoriesSelected = unserialize($categoriesSelected);
		}
	}

?>

<div>
	
	<div class="col-md-4">
		<img class='media-object' width="100%" src='./images/items/<?php echo $image ?> ' alt="<?php echo $image ?>">		
	</div>

	<div class="col-md-4">
		<div class="well well-sm">
			<h4>Referencia: <?php echo $reference ?></h4>	
		</div>	
		<div class="well well-sm">
			<h4>Precio Base: <?php echo $pricebase ?> €</h5>
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
		<a class='btn btn-primary' role='button' href="<?php echo $urlCatalog; ?>" >Volver al listado</a><br></h2>
		<div class="checkbox">
			<h5><b>Categorías del artículo</b></h5>
				
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
</div>