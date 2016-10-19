<?php

    function isAdmin() {
        
        require_once('./controller/connection.php');
        $connection = open_connection();

        $sql = "SELECT isAdmin FROM `business` WHERE `idUser` = " . $_SESSION["sessionIdUser"] . " ";

        $isAdmin = mysqli_query($connection, $sql) or die("No se ha ejecutado la operacion");
        $isAdmin = mysqli_fetch_array ( $isAdmin );
        $isAdmin = $isAdmin['isAdmin'];

        close_connection($connection);

        if ($isAdmin == 0){
            return FALSE;
        } else {
            return TRUE;
        }

    }


?>