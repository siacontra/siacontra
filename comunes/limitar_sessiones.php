<?php

include_once ("../clases/MySQL.php");
include_once("../comunes/objConexion.php");

if ($objConexion->compareIP()==0) {
$_SESSION = array();

session_destroy();
$message = "SU SESION ESTA ACTIVA EN OTRO EQUIPO";
echo "<script type='text/javascript'>alert('$message');</script>";

}	

?>
