<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("../lib/fphp.php");
include("lib/fphp.php");


list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('', $concepto);



//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" /> -->
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
<link type="text/css" rel="stylesheet" href="../css/custom-theme/jquery-ui-1.8.16.custom.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="../css/estilo.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" src="../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/fscript.js" charset="utf-8"></script>
<script type="text/javascript" src="js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="js/fscript.js" charset="utf-8"></script>
<script type="text/javascript" src="js/form.js" charset="utf-8"></script>

<!-- INCLUSION DE LOS ARCHIVOS FUNCIONALIDADES CES -->
    <script  type='text/JavaScript' src='../js/vEmergente.js' charset="utf-8"></script>
    
    <script type='text/JavaScript' src='../js/AjaxRequest.js' charset="utf-8"></script>

    <script type='text/JavaScript' src='../js/xCes.js' charset="utf-8"></script>
    
    <!-- <script type='text/JavaScript' src='../js/comun.js' charset="utf-8"></script>--> 
    
    <script type='text/JavaScript' src='../js/dom.js' charset="utf-8"></script>

	<script type='text/JavaScript' src='js/funcionalidadCes.js' charset="utf-8"></script>
	
    <!--*********************************************** -->
	
	 <!-- INCLUSION DE LOS ARCHIVOS FUNCIONALIDADES CES -->
	<link rel="stylesheet" href="../css/vEmergente.css" type="text/css" charset="utf-8" />

    <link rel="stylesheet" href="../css/estiloCes.css" type="text/css"  charset="utf-8" />
</head>

<body>
<!-- ui-dialog -->
<div id="cajaModal"></div>
<!-- pretty -->
<span class="gallery clearfix"></span>
<!-- progress-bar -->
<div class="div-progressbar"><div class="progressbar"></div></div>

<!-- INICIO -->
<?php if (file_exists("$anz.php")) include("$anz.php"); ?>
<!-- FIN -->

<script type="text/javascript" language="javascript">
$(document).ready(function() {
	<?
	if (isset($rows_total)) {
		?> totalRegistros(parseInt(<?=$rows_total?>), "<?=$_ADMIN?>", "<?=$_INSERT?>", "<?=$_UPDATE?>", "<?=$_DELETE?>"); <?
	}
	if (isset($focus)) {
		?> $("#<?=$focus?>").focus(); <?
	}
	?>
	$(".div-progressbar").css("display", "none");
});

crearVentanaAmpliarFoto();
</script>
</body>
</html>
