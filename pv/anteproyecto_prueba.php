<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include ("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('01', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
</head>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Listar Anteproyecto</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />
<?php
include "gmsector.php";
//	VALORES POR DEFECTO Y VALORES SELECCIONADOS DEL FILTRO AL CARGAR O RECARGAR LA PAGINA...............
//////////  ORGANISMO //////////
if($_POST['chkorganismo']=="1"){ 
   $obj[0]="checked"; $obj[1]="enabled"; $obj[2]=$_POST['forganismo']; 
}else{ 
   $obj[0]="checked"; $obj[1]="enabled"; $obj[2]=$_POST['forganismo']; 
}
////////  NUMERO ANTEPROYECTO  ////////
if($_POST['chknanteproyecto']=="1"){ 
  $obj[3]="checked"; $obj[4]=""; $obj[5]=$_POST['fnanteproyecto']; 
}else{ 
  $obj[3]=""; $obj[4]="disabled"; $obj[5]=""; 
}
/////////   PREPARADO POR ///////////
if($_POST['chkpreparado']=="1"){ 
  $obj[6]="checked"; $obj[7]=""; $obj[9]=$_POST['fpreparado'];
}else{ 
  $obj[6]=""; $obj[7]="disabled"; $obj[9]=""; 
}
///////   FECHA DE INGRESO /////////
if($_POST['chkejercicio']=="1"){
  $obj[10]="checked"; $obj[11]=""; $obj[12]=$_POST['fejercicio'];
}else{ 
  $obj[10]=""; $obj[11]="disabled"; $obj[12]=""; 
}
//////  ESTADO DEL ANTEPROYECTO  //////
if($_POST['chkstatus']=="1"){
  $obj[17]="checked"; $obj[18]=""; $obj[19]=$_POST['fstatus']; 
}else{ 
  $obj[17]=""; $obj[18]="disabled"; $obj[19]="0"; 
}
if ($_POST['chkempleado']=="1") { $obj[14]="checked"; $obj[15]=""; $obj[16]=$_POST['fempleado']; }
else { $obj[14]=""; $obj[15]="disabled"; $obj[16]=""; }
/////////////////////////////////////////////////////////////////////////////////////
if(!$_POST){
include "anteproyecto_listar2.php";
include "anteproyecto_pruebapart1.php";
}
?>
