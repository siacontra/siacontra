<?php
session_start();
include("fphp.php");
connect();
include "ControlCorrespondencia.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>

<!-- INCLUSION DE LOS ARCHIVOS FUNCIONALIDADES CES -->

    <script language='JavaScript' type='text/JavaScript' src='../js/vEmergente.js'></script>
    
    <script language='JavaScript' type='text/JavaScript' src='../js/AjaxRequest.js'></script>

    <script language='JavaScript' type='text/JavaScript' src='../js/xCes.js'></script>
    
    <script language='JavaScript' type='text/JavaScript' src='../js/comun.js'></script>

	<script language='JavaScript' type='text/JavaScript' src='js/funcionalidadCes.js'></script>

    <!--*********************************************** -->
</head>

	<!-- INCLUSION DE LOS ARCHIVOS FUNCIONALIDADES CES -->
	<link rel="stylesheet" href="../css/vEmergente.css" type="text/css" />

    <link rel="stylesheet" href="../css/estiloCes.css" type="text/css"  />

    <!--*********************************************** -->
    
<body style="margin-top:0px; margin-left:0px;" onload="crearVentanaAvisoDocumentoInterno();crearVentanaAvisoDocumentoExterno();">
<div style="position:absolute; top:20%; left:35%;">
<img src="../imagenes/fondo_main.jpg" width="60%" height="60%" />
</div>
<input type="hidden" name="regresar" id="regresar" value="framemain"/>
</body>
</html>