<?php
    $host_db = "localhost"; // Host de la BD
    $usuario_db = $_SESSION["MYSQL_USER"]; // Usuario de la BD
    $clave_db = $_SESSION["MYSQL_CLAVE"]; // Contrase�a de la BD
    $nombre_db = "automotor"; // Nombre de la BD
    
    //conectamos y seleccionamos db
    mysql_connect($host_db, $usuario_db, $clave_db);
    mysql_select_db($nombre_db);
?> 
