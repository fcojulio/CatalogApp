<?php
  
  session_start();

  if(isset($_SESSION["sessionNumber"])){
    //echo $_SESSION["sessionNumber"] ." : " . $_SESSION["sessionIdUser"];
  }else{
    header ("Location: ./index.php");
  }  

  require_once('./controller/shoppingcart.php');
  $countCart = countCart();

  function showMenu(){

    if( isset($_GET['sec']) ){
                              
      $sec = $_GET['sec'];

      switch ($sec) {
        case 'items':
          require_once('view/items.php');
          break;
        case 'newItem':
          require_once('view/newItem.php');
          break;
        case 'orderItems':
          require_once('view/orderItems.php');
          break;
        case 'batch':
          require_once('view/batch.php');
          break;
        case 'clients':
          require_once('view/clients.php');
          break;
        case 'newClient':
          require_once('view/newClient.php');
          break;
        case 'addBusinessClient':
          require_once('view/addBusinessClient.php');
          break;
        case 'orders':
          require_once('view/orders.php');
          break;
        case 'selClient':
          require_once('view/selClient.php');
          break;
        case 'newOrder':
          require_once('view/newOrder.php');
          break;
        case 'viewOrder':
          require_once('view/viewOrder.php');
          break;
        case 'shoppingcart':
          require_once('view/shoppingcart.php');
          break;
        case 'catalog':
          require_once('view/catalog.php');
          break;
        case 'viewItem':
          require_once('view/viewItem.php');
          break;
        case 'business':
          require_once('view/business.php');
          break;
        case 'newBusiness':
          require_once('view/newBusiness.php');
          break;
        case 'categories':
          require_once('view/categories.php');
          break;
        case 'newCategory':
          require_once('view/newCategory.php');
          break;
        case 'sections':
          require_once('view/sections.php');
          break;
        case 'newSection':
          require_once('view/newSection.php');
          break;
        case 'stock':
          require_once('view/stock.php');
          break;
        case 'backup':
          require_once('view/backup.php');
          break;
        case 'sync':
          require_once('view/sync.php');
          break;
        case 'recovery':
          require_once('view/recovery.php');
          break;
        default:
          # code...
          break;
      }
    }
  }

?>

<!DOCTYPE html>
<html lang="es">
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Panel App Catalog</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/dashboard.css" rel="stylesheet">
    
    <script type="text/javascript" src="js/jquery-1.12.3.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui.js"></script>

    <script type='text/javascript'>

      $(document).ready(function(){

          $("ul#categoryorder").sortable({ 
                 opacity: 0.6, 
                 cursor: 'move'  
                 });
          
       });
     </script>

    <script type="text/javascript">
      $(document).ready(function(){

        $("ul#categoryorder").sortable({ 
               opacity: 0.6, 
               cursor: 'move',  
               update: function(){
                      $('#categorysavemessage').html('Changes not saved');
                      $('#categorysavemessage').css("color","red");
                      }
               });

      });
    </script>

    <script type="text/javascript">
        function saveDisplayChanges(){
          var order = $("ul#categoryorder").sortable("serialize");

          $('#categorysavemessage').html('Saving changes..');

          $.post("./controller/ajax/update_displayorder.php",order,function(theResponse){
            $("#categorysavemessage").html(theResponse);
            $('#categorysavemessage').css("color","green");
          });

          alert("Orden guardado...");
        }
    </script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top" style='background-color: #5f5d5e' >

      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          </button>
        <a class="navbar-brand" href="./app.php" ><b>Catalog</b></a>
      </div>
      
      <div class="navbar-collapse collapse">
        <ul class="nav navbar-nav botonera" style="font-size: 20px;">
          
        <?php
          if ( isset($_SESSION['catalogurl']) ){
            $urlCatalog = $_SESSION['catalogurl'];
            $itemContext = $_SESSION['itemContext'];
          }
        ?>


          <li <?php if( isset($_GET['sec']) and ($_GET['sec'] == 'catalog') ) {echo ' class="active" '; } ?> ><a href="<?php echo $urlCatalog . "#" . $itemContext; ?>" >
                <span class="glyphicon glyphicon-book"></span>
                <b>Catálogo</b></a>
          </li>
          
          <li <?php if( isset($_GET['sec']) and ($_GET['sec'] == 'orders') ) {echo ' class="active" '; } ?> ><a href="?sec=orders">
                <span class="glyphicon glyphicon-align-left"></span>
                <b>Pedidos</b></a></li>
          <li <?php if( isset($_GET['sec']) and ($_GET['sec'] == 'clients') ) {echo ' class="active" '; } ?> ><a href="?sec=clients">
                <span class="glyphicon glyphicon-briefcase"></span>
                <b>Clientes</b></a></li>
          <li <?php if( isset($_GET['sec']) and ($_GET['sec'] == 'shoppingcart') ) {echo ' class="active" '; } ?> ><a onclick=viewCart(<?php echo $_SESSION["sessionIdUser"]; ?>) >
                <span class="glyphicon glyphicon-shopping-cart"></span>
                <b>Carrito</b></a>
          </li>

          <?php if( $_SESSION["isAdmin"] == TRUE ) { ?> 
          <li role="presentation" class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-fire"></span>
              <b>Administrador</b><span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li <?php if( isset($_GET['sec']) and ($_GET['sec'] == 'business') ) {echo ' class="active" '; } ?> ><a href="?sec=business">Comerciales</a></li>
              <li <?php if( isset($_GET['sec']) and ($_GET['sec'] == 'categories') ) {echo ' class="active" '; } ?> ><a href="?sec=categories">Categorías</a></li>
              <li <?php if( isset($_GET['sec']) and ($_GET['sec'] == 'sections') ) {echo ' class="active" '; } ?> ><a href="?sec=sections">Secciones</a></li>
              <li <?php if( isset($_GET['sec']) and ($_GET['sec'] == 'items') ) {echo ' class="active" '; } ?> ><a href="?sec=items&to=10">Artículos</a></li>
              <li <?php if( isset($_GET['sec']) and ($_GET['sec'] == 'orderItems') ) {echo ' class="active" '; } ?> ><a href="?sec=orderItems">Ordenar Artículos</a></li>
              <li <?php if( isset($_GET['sec']) and ($_GET['sec'] == 'stock') ) {echo ' class="active" '; } ?> ><a href="?sec=stock">Stock</a></li>
              <li <?php if( isset($_GET['sec']) and ($_GET['sec'] == 'backup') ) {echo ' class="active" '; } ?> ><a href="?sec=backup">Backup</a></li>  
            </ul>
          </li>
          <?php } ?>
         
          <?php if ( $_SERVER["HTTP_HOST"] == "localhost" or $_SERVER["HTTP_HOST"] == "localhost:8080" ) { ?>
            <li role="presentation" class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-wrench"></span>
              <b>Utiles</b><span class="caret"></span></a>

              <ul class="dropdown-menu">
                <li <?php if( isset($_GET['sec']) and ($_GET['sec'] == 'sync') ) {echo ' class="active" '; } ?> ><a href="?sec=sync" >Sync</a></li>         
                <li <?php if( isset($_GET['sec']) and ($_GET['sec'] == 'recovery') ) {echo ' class="active" '; } ?> ><a href="?sec=recovery" >Recuperar Pedido</a></li>         
                <li <?php if( isset($_GET['sec']) and ($_GET['sec'] == 'update') ) {echo ' class="active" '; } ?> ><a id="dupdate" >Update</a></li>
                <li> <a href="#" ><b>Ver. rel 1.05</b></a></li>
              </ul>
            </li>
          <?php } ?>

          <li><a href="#"><span class="glyphicon glyphicon-user"></span><?php echo $_SESSION["userAccess"]; ?> </a></li>

          <li>
            <a href="./controller/logout.php"><span class="glyphicon glyphicon-off"></span> Cerrar</a>
           </li>
          
        </ul>
      </div><!--/.navbar-collapse -->

      <?php

        if( isset($_GET['sec']) ){
          if ( $_GET['sec'] == 'catalog' ){

            if( isset($_GET['cat']) ){

              require_once('./controller/sections.php');
              $cat = $_GET['cat'];
              
              echo "<div style='background-color: #5f5d5e; padding-top: 8px; max-height: 46px; padding-left: 10px;' >";
                echo printDirectory($cat);
              echo "</div>";
            }
          }
        }

      ?>

    </nav>

    <div class="container-fluid">
      <div class="row">    
        <div class='col-sm-12 col-sm-offset-0 col-md-12 col-md-offset-0 main' <?php if( isset($_GET['cat']) ) { echo "style='padding-top: 50px; padding-bottom: 0px;'"; } ?> >      
          <?php showMenu(); ?>            

        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/bootstrap.min.js"></script>    

    <script type='text/javascript'>

      $(function(){
       $('#dupdate').click(function(){

       	if (confirm('¿Seguro de actualizar la app? Este proceso necesita al menos 10 minutos') != true ){
          return;
        }
        
         $(this).text('Actualización en curso...');
         alert("La Actualización ha comenzado, por favor espere, el tiempo de Actualización dependerá de la conexión...")

         setTimeout(function(){

              window.location.replace('update_app.php');

         },2000);

         return false;

       })
      });

      function viewCart(idUser) {
        var xhttp = new XMLHttpRequest();         

        xhttp.onreadystatechange = function() {  
          //alert("xhttp.readyState: " + xhttp.readyState + " xhttp.status: " + xhttp.status );
          if (xhttp.readyState == 4 && xhttp.status == 200) {           
            cart = xhttp.responseText;   
            document.getElementById('textModalItemCart').innerHTML = cart;
          }
        };

        xhttp.open("POST", "./controller/ajax/viewCart.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("idUser="+idUser);         

        $('#alertViewCart').modal('show');

      }
      
      function viewItem(idItem) {
        var xhttp = new XMLHttpRequest();

        xhttp.onreadystatechange = function() {  
          if (xhttp.readyState == 4 && xhttp.status == 200) {
            infoItem = xhttp.responseText;   
            document.getElementById('textModalItemView').innerHTML = infoItem;
          }
        };
        xhttp.open("POST", "./controller/ajax/viewItem.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("idItem="+idItem);         

        $('#alertViewItem').modal('show');

      }

      function remItemCart(idItem, idCaption) {
        var xhttp = new XMLHttpRequest();

        xhttp.onreadystatechange = function() {  
          if (xhttp.readyState == 4 && xhttp.status == 200) {
            //infoItem = xhttp.responseText;   
            //document.getElementById('textModalItemView').innerHTML = infoItem;
          }
        };
        xhttp.open("POST", "./controller/ajax/remItemCart.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("idItemToRem="+idItem);         

        document.getElementById("caption"+idCaption).style.backgroundColor = "rgb(255, 255, 255)";
        document.getElementById("caption"+idCaption).style.color = "rgb(0, 0, 0)";
        //$('#alertViewCart').modal('hide'); 
        setTimeout(function(){ viewCart(<?php echo $_SESSION["sessionIdUser"]; ?>) }, 100); 
      }

      function updateItemCart(idCart) {

        quantity = document.getElementById('quantityToChange'+idCart).value;

        //alert("q: " + quantity + " c: " + idCart);

        var xhttp = new XMLHttpRequest();

        xhttp.onreadystatechange = function() {  
          if (xhttp.readyState == 4 && xhttp.status == 200) {
            //infoItem = xhttp.responseText;   
            //document.getElementById('textModalItemView').innerHTML = infoItem;
          }
        };
        xhttp.open("POST", "./controller/ajax/updateItemCart.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("idItemToUpdate="+idCart+"&quantityToChange="+quantity);       

        //$('#alertViewCart').modal('hide'); 
        setTimeout(function(){ viewCart(<?php echo $_SESSION["sessionIdUser"]; ?>) }, 100);

      }

      function viewItemImage(idItem) {
        var xhttp = new XMLHttpRequest();

        xhttp.onreadystatechange = function() {  
          if (xhttp.readyState == 4 && xhttp.status == 200) {
            infoItem = xhttp.responseText;   
            document.getElementById('textModalViewItem').innerHTML = infoItem;
          }
        };
        xhttp.open("POST", "./controller/ajax/viewItemImage.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("idItem="+idItem);         

        $('#alertViewItemImage').modal('show');

      }

      function addItem(idItem, idUser, image, reference) {
        
        quantity = document.getElementById('quantityAddItem'+idItem).value;

        var xhttp = new XMLHttpRequest();

        xhttp.onreadystatechange = function() {  
          if (xhttp.readyState == 4 && xhttp.status == 200) {
            //alert("xhttp.readyState: " + xhttp.readyState + " xhttp.status: " + xhttp.status + " idItem: " + idItem + " quantity: " + quantity + " idUser: " + idUser);         
          }
        };
        lolz = document.getElementById('lolz');
        xhttp.open("POST", "./controller/ajax/addItem.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("idItem="+idItem+"&quantity="+quantity+"&idUser="+idUser);

        document.getElementById('textModalItemAdd').innerHTML="<img style='max-width: 54px;' src='./images/items/147-FAB0B003W9_1532016.jpg' />Artículo: " + reference + " cantidad: " + quantity;
        document.getElementById("caption"+idItem).style.backgroundColor = "#5f5d5e";

        $('#alertAddItem').modal('show');
        setTimeout(function(){ $('#alertAddItem').modal('hide') }, 500); 
        
      }

      function delShoppigCart(){
        var result = confirm("¿Seguro que quieres borrar el carrito?");
        
        if (result) {

             var xhttp = new XMLHttpRequest();

              xhttp.onreadystatechange = function() {  
                if (xhttp.readyState == 4 && xhttp.status == 200) {
                  //infoItem = xhttp.responseText;   
                  //document.getElementById('textModalItemView').innerHTML = infoItem;
                }
              };
              xhttp.open("POST", "./controller/ajax/delShoppingCart.php", true);
              xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
              xhttp.send("idUser="+<?php echo $_SESSION["sessionIdUser"]; ?>);         

               //$('#alertViewCart').modal('hide'); 
               setTimeout(function(){ viewCart(<?php echo $_SESSION["sessionIdUser"]; ?>) }, 100); 
        }

      }
  
    </script>

    <div id='alertAddItem' class='modal fade bs-example-modal-sm' role='dialog'>
      <div class='modal-dialog modal-sm'>
        <div class='modal-content'>
          <div class='modal-header'>
            <button type='button' class='close' data-dismiss='modal' style="font-size: 50px;" >&times;</button>
            <h4 class='modal-title'>Artículo añadido</h4>
          </div>
          <div class='modal-body'>
            <p id='textModalItemAdd' ></p>
          </div>
          <div class='modal-footer'>
            <button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>
          </div>
        </div>
      </div>
    </div>

    <div id='alertViewItem' class='modal fade modal-fullscreen' role='dialog'>
      <div class='modal-dialog modal-lg' style="width: 90%;">
        <div class='modal-content'>
          <div class='modal-header'>
            <button type='button' class='close' data-dismiss='modal' style="font-size: 50px;" >&times;</button>
            <h4 class='modal-title'>Info Artículo</h4>
          </div>
          <div id='textModalItemView' class='modal-body'>
          </div>
          <div class='modal-footer'>
            <button style="height: 50px; width: 160px;" type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>
          </div>
        </div>
      </div>
    </div>

    <div id='alertViewCart' class='modal fade modal-fullscreen' role='dialog'>
      <div class='modal-dialog modal-lg' style="width: 90%;" >
        <div class='modal-content'>
          <div class='modal-header'>
            <button type='button' class='close' data-dismiss='modal' style="font-size: 50px;" >&times;</button>
            <h4 class='modal-title'>Artículos en el carrito</h4>
          </div>
          <div id='textModalItemCart' class='modal-body'>
          </div>
          <div class='modal-footer'>
            <a href="#" onclick="delShoppigCart();" class="btn btn-danger" style="float: left; height: 50px; width: 160px;" >Borrar carrito</a>
            <a href="?sec=shoppingcart" class="btn btn-primary" style="height: 50px; width: 160px;" >Tramitar carrito</a>
            <button type='button' class='btn btn-default' data-dismiss='modal' style="height: 50px; width: 160px;" >Cerrar</button>
          </div>
        </div>
      </div>
    </div>
    
    <div id='alertViewItemImage' class='modal fade bs-example-modal-sm' role='dialog'>
      <div class='modal-dialog modal-lg'>
        <div class='modal-content'>
          <div class='modal-header'>
            <button type='button' class='close' data-dismiss='modal' style="font-size: 50px;" >&times;</button>
            <h4 class='modal-title'>Artículo ampliado</h4>
          </div>
          <div id='textModalViewItem' class='modal-body'>
          </div>
          <div class='modal-footer'>
            <button type='button' class='btn btn-default' data-dismiss='modal' style="height: 50px; width: 160px;" >Cerrar</button>
          </div>
        </div>
      </div>
    </div>

    <?php 

      if(isset($_GET['cat'])){

      	require_once('./controller/categories.php');
      	$idSection = $_GET['cat'];

      	$printFeatures = "";
      	echo "<form id='search_item' class='appnitro' enctype='multipart/form-data' method='POST' action='' >
      			<div id='viewFeaturesToSearch' class='modal bs-example-modal-lg' role='dialog'>
                  <div class='modal-dialog modal-lg'>
                    <div class='modal-content'>
                      <div class='modal-header'>
                        <button type='button' class='close' data-dismiss='modal'>&times;</button>
                        <h4 class='modal-title'>Seleccionar carcterísticas a buscar:</h4>
                      </div>
                      <div class='modal-body'>
                      <div id='accordion' class='panel-group'>";
                       $categories = get_base_categories(); 
                       $count = 0;
                       foreach($categories as $cat):   
                        echo "<div class='panel panel-default'>";   
                          echo "<div class='panel-heading'>";         
                       	    echo "<li style='font-size: 18px;' >
                                    <input style='margin-right: 10px; transform: scale(2);' name=categoriesSelected[] type='checkbox' value=".$cat['idCategory']." />";
                        if ( hasSubCaegories($cat['idCategory']) != 0){
                          echo "<a style='font-weight: bold; color: rgb(183, 51, 51);' data-toggle='collapse' data-parent='#accordion' href='#collapse".$count."'>".$cat['name']."</a>
                                </li>";
                        }else{
                          echo $cat['name']. "</li>";
                        }
                           
                          echo "</div>";

                          if ( hasSubCaegories($cat['idCategory']) != 0){
                              echo "<div id='collapse".$count."' class='panel-collapse collapse'>
                                    <div class='panel-body'>";
                                      $subprintFeatures = "";
                                      $subprintFeatures .= list_tree_cat_id_art_search($cat['idCategory'], $subprintFeatures);
                                      
                                      echo $subprintFeatures;
                              echo "</div>";
                            echo "</div>";
                          }           
                          
                        echo "</div>";
                        $count++;

                       endforeach;
		
        echo "</div>";

        echo "</div>
                      <div class='modal-footer'>
                      	<input type='hidden' name='sectionToSearch' value='$idSection' />
                      	<input class='btn btn-primary' type='submit' value='Buscar' />
                        <button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>
                      </div>
                    </div>
                  </div>
                </div>
            </form>"; 
      }
    ?>  

  </body>
</html>