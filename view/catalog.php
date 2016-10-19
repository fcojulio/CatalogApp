<?php 
	require_once('./controller/catalog.php');
  require_once('./controller/sections.php');
	require_once('./controller/shoppingcart.php');
  require_once('./controller/items.php');
 
  $pageURL = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  $_SESSION["catalogurl"] = $pageURL;

	if(isset($_POST['idItemToAdd']) and isset($_POST['quantityAddItem']) ){
		addItemCart($_POST['idItemToAdd'], $_POST['quantityAddItem']); 
    $_SESSION['itemContext'] = $_POST['idItemToAdd'];  
	}

	if( isset($_GET['cat']) ){
    
    $cat = $_GET['cat'];              
    $searchCatByRef = 0;
    $listCatToSearch = 0;

    if( isset($_GET['searchCatByRef']) ){
      //echo $_GET['searchCatByRef'];
      $searchCatByRef = $_GET['searchCatByRef'];
    }

    if( isset($_POST['categoriesSelected']) ){
      $listCatToSearch = $_POST['categoriesSelected'];
      //var_dump($listCatToSearch);
    }
    
    echo printCatalog($cat, $searchCatByRef, $listCatToSearch);          

  }else{

    	echo "<div ><h2>Catálogo</h2></div>";
      
      $sections = getSectionListParent(); 
      if(count($sections)>0):

        foreach($sections as $sec):

        echo "<div class='col-sm-3 col-md-3' style='display: inline-block; float: none; text-align: center;'>
                <div class='thumbnail' >
                <a href='app.php?sec=catalog&cat=".$sec['idSection']."' >                  
                  <h3>".$sec['name']."</h3>
                  <img style='height: 360px;' src='./images/sections/".$sec['image']."' />
                </a>
                </div>
              </div>";
          
        endforeach;

      else:
        echo "<p class='alert alert-danger'>No hay secciones</p>";
      endif;
    }

?>