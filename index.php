<?php
  
  error_reporting(E_ALL); ini_set('display_errors', 'On');
  session_start();

  if(isset($_SESSION["sessionNumber"])){
    header ("Location: ./app.php");
    //echo $_SESSION["sessionNumber"] ." : " . $_SESSION["sessionIdUser"];
  }

  require_once('./controller/login.php');
  
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Signin App Platisur</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">

      <form class="form-signin" action="./index.php" method="post" >
        <h2 class="form-signin-heading">App Platisur</h2>
        <label for="inputUser" class="sr-only">Usuario</label>
        <input type="user" id="inputUser" name="inputUser"  class="form-control" placeholder="usuario" required autofocus>
        <label for="inputPassword" class="sr-only">Clave</label>
        <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="clave" required>

        <button class="btn btn-lg btn-primary btn-block" type="submit">Iniciar sesión</button>
      </form>

    </div> <!-- /container -->

  </body>
</html>
