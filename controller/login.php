<?php

    if(isset($_POST['inputUser']) AND isset($_POST['inputPassword'])){

        $userAccess = $_POST['inputUser'];
        $passAccess = $_POST['inputPassword'];

        require_once('./controller/connection.php');
        require_once('./controller/isAdmin.php');

        $connection = open_connection();

        $sql = "SELECT `idUser`, `pass`, `name`, `isActive`, `categories` FROM `business` WHERE `user` = '$userAccess' and `isActive`= 1";
        //echo $sql;

        $result = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
        $row = $result->fetch_assoc();
        $passBD = $row['pass'];
        $idUserBD = $row['idUser'];
        $name = $row['name'];
        $sections = $row['categories'];

        close_connection($connection);

        //echo $passAccess . " : " . $passBD;
        if ( ($passAccess == $passBD) ){
            $_SESSION["sessionNumber"] = "cambiarIDSession";
            $_SESSION["sessionIdUser"] = $idUserBD;
            $_SESSION["sessionName"] = $name;
            $_SESSION["userAccess"] = $userAccess;
            $_SESSION["sessionSections"] = unserialize($sections);
            $_SESSION["isAdmin"] = isAdmin();
            $_SESSION['catalogurl'] = "?sec=catalog";
            $_SESSION['itemContext'] = "";
            
            header ("Location: ./app.php");
            //echo "location";
        }else{
            echo "<div class='alert alert-danger' role='alert'>Acceso denegado</div>";

            if ( ($_SERVER["HTTP_HOST"] == "localhost") or ($_SERVER["HTTP_HOST"] == "localhost:8080") ){
                echo "<div class='alert alert-danger' role='alert'>Si has sincronizado hace poco y estas seguro de no haber introducido mal tus credenciales prueba a forzar SYNC haciendo click en el enlace de abajo</div>";
                echo "<div class='alert alert-danger' role='alert'><a class='btn btn-lg btn-primary btn-block' href='./syn/install_2.php' target='_blank' >FORZAR SYNC</a></div>";
            }
        }
  }
  
?>