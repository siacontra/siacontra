<?php

session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
include_once("../comunes/limitar_sessiones.php");


?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="../imagenes/icono.ico" type="image/x-icon" rel="shortcut icon" />
<title>M&oacute;dulo de Eventos | <?=$_SESSION['NOMBRE_USUARIO_ACTUAL']?></title>
<script language='JavaScript' type='text/JavaScript' src='../rh/fscript.js'></script>


<frameset id='frmSet' frameborder='no' border='0' rows='75px, *'>
	<frame  src='frametop.php' noresize scrolling='no'>
	<frame src='framebottom.php'>

	<!--frame  src='procesos.php' noresize scrolling='yes'-->	
</frameset>
		
</head>
</body>

</html>
