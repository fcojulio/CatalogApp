<?php 
	require_once('./controller/categories.php');

	if ( isset($_GET['idCategoryToRem'] ) ){
		//echo "asdasd";
		remCategory($_GET['idCategoryToRem']);
	}

?>

<div > <h2>Listado de categorías <a class='btn btn-primary' a href="?sec=newCategory" >Añadir nueva categoría</a><br> </h2></div>
	
	<?php $categories = get_base_categories(); ?>
	<?php if(count($categories)>0):?>
		<ul>
			<?php foreach($categories as $cat):?>
				<li><div style="margin: 8px;" ><?php echo $cat["name"] . " " .edit_btn($cat["idCategory"])." ".del_btn($cat["idCategory"]) ?> </div></li>
				<?php
				list_tree_cat_id($cat["idCategory"]);
				?>
			<?php endforeach;?>
		</ul>
			<?php else:?>
				<p class="alert alert-danger">No hay categorias</p>
			<?php endif;?>


