<?php 
	require_once('./controller/sections.php');

	if ( isset($_GET['idSectionToRem'] ) ){
		//echo "asdasd";
		remSection($_GET['idSectionToRem']);
	}

?>

<div > <h2>Listado de secciones <a class='btn btn-primary' a href="?sec=newSection" >Añadir nueva sección</a><br> </h2></div>
	
	<?php $sections = get_base_sections(); ?>
	<?php if(count($sections)>0):?>
		<ul>
			<?php foreach($sections as $sec):?>
				<li><div style="margin: 8px;" ><?php echo $sec["name"] . " " .edit_sec_btn($sec["idSection"])." ".del_sec_btn($sec["idSection"]) ?> </div></li>
				<?php
				list_tree_sec_id($sec["idSection"]);
				?>
			<?php endforeach;?>
		</ul>
			<?php else:?>
				<p class="alert alert-danger">No hay secciones</p>
			<?php endif;?>


