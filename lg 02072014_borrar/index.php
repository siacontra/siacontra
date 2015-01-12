<?php

session_start();

if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
include_once("../comunes/limitar_sessiones.php");

?>

<html>

<head>

<link href="../imagenes/icono.ico" type="image/x-icon" rel="shortcut icon" />





<title>M&oacute;dulo de Log&iacute;stica | <?=$_SESSION['NOMBRE_USUARIO_ACTUAL']?></title>

<script language='JavaScript' type='text/JavaScript' src='fscript.js'></script>

 

</head>

    
<frameset id='frmSet' frameborder='no' border='0' rows='75px, *'>

<frame noresize scrolling='no'  src='frametop.php'>

<frame src='framebottom.php'>

</frameset>

<noframes></noframes>

<body ></body>

</html>
