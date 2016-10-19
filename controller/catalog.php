<?php 

	function gridCatalog($idSection, $searchCatByRef, $listCatToSearch){

		require_once('./controller/connection.php');
		require_once('./controller/categories.php');
		require_once('./controller/sections.php');
		require_once('./controller/shoppingcart.php');

		$connection = open_connection();
		$gridCatalog = "";		

		$subSectionsNums = array();
		$subSectionsNums = getAllSubSections($idSection, $subSectionsNums);

		$whereSections = "";
		$whereCategories = "";

		foreach ($subSectionsNums as $sec) {
			$whereSections .= " or idSection = " . $sec;
		}

		$sql = "SELECT * FROM `items` 
					WHERE `isActive` = 1 
					and (`isActiveNoStock` = 1 or `stock` > 0) 
					and (idSection = $idSection $whereSections )
					ORDER BY `idSection`, `orderItem` ASC";

		if ( is_string($searchCatByRef)){
			
			$searchCatByRef = strtoupper($searchCatByRef);

			$sql = "SELECT * FROM `items` 
					WHERE `reference` LIKE '%$searchCatByRef%' 
					AND `isActive` = 1 
					AND (`isActiveNoStock` = 1 or `stock` > 0) 
					AND ( idSection = $idSection $whereSections ) 
					ORDER BY `idSection`, `orderItem` ASC";
		}

		if ( $listCatToSearch != 0){
			$whereSubCategories = "";
			foreach ($listCatToSearch as $cat ) {

				if ( hasSubCaegories($cat) ){

					$subCats = getSubCaegories($cat);
					$whereSubCategories = "";
					foreach ($subCats as $subcat ) {
						$whereSubCategories .= " OR categories LIKE '%\"" . $subcat['idCategory'] . "\"%'";
					}

				}

				$whereCategories .= " AND ( categories LIKE '%\"" . $cat . "\"%' $whereSubCategories )";
			}

			$sql = "SELECT * FROM `items` 
					WHERE `isActive` = 1 
					AND (`isActiveNoStock` = 1 or `stock` > 0) 
					AND ( (idSection = $idSection $whereSections) $whereCategories )
					ORDER BY `idSection`, `orderItem` ASC"; 	
		}
		
		//echo $sql . "</br>";	
		
		$result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		$resultClone = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
		$rowClone = mysqli_fetch_array ( $resultClone );
		$rowClone = mysqli_fetch_array ( $resultClone );
		$rowClone = mysqli_fetch_array ( $resultClone );

		$referenceInCart = getReferencesInCart();

		close_connection($connection);
		//$count = 0;
		$numSecSeparator = 0;

		if( $_GET['sec'] ) { $sec = $_GET['sec']; }
		if( $_GET['cat'] ) { $cat = $_GET['cat']; }
		if( $_GET['all'] ) { $all = $_GET['all']; }

		while( $row = mysqli_fetch_array ( $result )){
			//$count++;

			if($numSecSeparator != $row['idSection']){
				$numSecSeparator = $row['idSection'];									

				$gridCatalog .= "<div class='col-sm-3 col-md-3' style='display: inline-block; float: none; top: -50px; '>";
				$gridCatalog .= "<div class='thumbnail' style='text-align: center; background-color:#5f5d5e;' >";
				
				$gridCatalog .= "<img style='width: 240px;' src='./images/sections/" . getImageSection($numSecSeparator) . "'/>";
				$gridCatalog .= "<h4 style='color: white;' >" . getNameSection($numSecSeparator) . "</h4>";
				$gridCatalog .= "</div></div>";
			}

			$catImages = getCategoryListImage();
			$catsItem = unserialize($row['categories']);							

			$color = "";
			if ( in_array($row['reference'], $referenceInCart) ){
				$color = "background-color: #5f5d5e; color:white;";
			}

			$gridCatalog .= "<div class='col-sm-3 col-md-3' style='display: inline-block; float: none;'  >";
			$gridCatalog.= "<div class='thumbnail' style='padding: 0px;margin-bottom: 0px;' >";

			$gridCatalog .= "<div style='border-bottom: 4px solid rgb(49, 189, 154);' onclick=viewItem(".$row['idItem'].") >";

				if ( $row['discount'] > 0 ){
					$gridCatalog .=   "<span class='glyphicon glyphicon-bookmark' style='color: green; font-size: 20px; ' ></span>";
				}

				if ( $row['stock'] <= 0 ){
					$gridCatalog .=   "<span class='glyphicon glyphicon-ban-circle' style='color: red; font-size: 20px; ' ></span>";
				}

				while( $catImage = mysqli_fetch_array ( $catImages )){
					if(!is_array($catsItem) ) break;
					if ( in_array($catImage['idCategory'],  $catsItem) ){
						$gridCatalog .=  "<img width='30px' style='vertical-align: bottom; margin: 3px;' src='./images/categories/" . $catImage['image'] ." ' />";
					}
				}

			$gridCatalog .= "</div>";							

			$contextSearch = "";
			if ( isset($_GET['searchCatByRef']) ) { 
				$searchCatByRef = $_GET['searchCatByRef'];
				$contextSearch .= "&searchCatByRef=".$searchCatByRef;
			}

			if ( isset($_GET['sectionToSearch']) ) { 
				$sectionToSearch = $_GET['sectionToSearch'];
				$contextSearch .= "&sectionToSearch=".$sectionToSearch;
			}

			$gridCatalog.= "
				    	<img style='height: 360px;' src='./images/items/".$row['image']."' alt=".$row['idItem']." >

			      <div id='caption".$row['idItem']."' class='caption' style='text-align: center; padding: 0px; ".$color.";' >
			      	<a style='color: rgb(49, 189, 154); font-size:25px' onclick=viewItem(".$row['idItem'].") >
			        	<h3 style='margin: 0px;''>" . $row['reference'] . "</h3>
			        </a>
			        <p style='margin: 0px;' >P.V.P.: " . $row['price'] . " €</p>";

			if ($row['discount'] > 0 ) {
				//$gridCatalog .= "<p style='margin: 0px;' >Oferta: " . $row['price'] * ($row['discount']/100) . " €</p>";
			}else {
				//$gridCatalog .= "<p style='margin: 0px;'> - </p>";
			}

			$rowClone = mysqli_fetch_array ( $resultClone );
			//$gridCatalog .= $rowClone['idItem'] ;
			$itemImage = $row['image'];
			$gridCatalog .= "<p style='margin: 0px;'>
								<!-- <form id='add_item' class='appnitro' enctype='multipart/form-data' method='post' action='#".$rowClone['idItem']."' >
									<input type='hidden' name='idItemToAdd' value= " . $row['idItem'] . " />		
									<input type='hidden' name='sec' value='$sec' />
	                                <input type='hidden' name='cat' value='$cat' />
	                                <input type='hidden' name='all' value='$all' />-->	    
									<input min='1' style='width: 20%; height:50px;' name='quantityAddItem".$row['idItem']."' type='number' id='quantityAddItem".$row['idItem']."' value='1' >
									<button style='background-color: rgb(49, 189, 154); color: white; border-color:white; height:50px; width: 70%' class='btn btn-primary' type='button' onclick=addItem(".$row['idItem'].",".$_SESSION["sessionIdUser"].",'','".$row['reference']."') >Añadir</button>
									<!--<input style='width: 70%' class='btn btn-primary'  type='submit' name='submit' value='Añadir' />
								</form>-->								
							</p>
			        <p style='margin: 0px;' >Ref.: XF-0" . $row['stock'] . "YT-1</p>
			      </div>
			    </div>
			   </div>";
		}
		//echo $count;				
		echo $gridCatalog;
	}

	function printCatalog($idSection, $searchCatByRef, $listCatToSearch){

		require_once('./controller/connection.php');
		require_once('./controller/categories.php');
		require_once('./controller/sections.php');

		$printCatalog = "";

		
		echo $printCatalog;	

		echo "<div class='row' style='white-space: nowrap;'>";

        if(isset($_GET['all']) ){
        	echo gridCatalog($_GET['all'], $searchCatByRef, $listCatToSearch);
        }else{

        	$subSections = getSubSections($idSection);
        	
        	echo "<div class='col-sm-3 col-md-3' style='display: inline-block; float: none; text-align: center;'>
	                <div class='thumbnail' >               
		                <a href='app.php?sec=catalog&cat=".$idSection."&all=".$idSection."' >
		                	<h3>TODO</h3>
		                  <img src='./images/sections/".getImageSection($idSection)."' />                  
		                </a>
	                </div>
	              </div>";	 

			while( $sec = mysqli_fetch_array ( $subSections )){
				
				echo "<div class='col-sm-3 col-md-3' style='display: inline-block; float: none; text-align: center;'>
		                <div class='thumbnail' >
			                <a href='app.php?sec=catalog&cat=".$sec['idSection']."' >
			                  <h3>".$sec['name']."</h3>                
			                  <img src='./images/sections/".$sec['image']."' />                  
			                </a>
		                </div>
		            </div>";
			}

			       
        }	

        echo "</div>";
	}

?>