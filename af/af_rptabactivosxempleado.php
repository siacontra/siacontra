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
<!--<link href="css1.css" rel="stylesheet" type="text/css" />-->
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" src="../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript_02.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript01.js"></script>
<script type="text/javascript" src="../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/fscript.js" charset="utf-8"></script>
<style type="text/css">
<!--
UNKNOWN {FONT-SIZE: small}
#header {FONT-SIZE: 93%; BACKGROUND: url(imagenes/bg.gif) #dae0d2 repeat-x 50% bottom; FLOAT: left; WIDTH: 100%; LINE-HEIGHT: normal}
#header UL {PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 10px; LIST-STYLE-TYPE: none}
#header LI {
        PADDING-RIGHT: 0px; PADDING-LEFT: 9px; BACKGROUND: url(imagenes/left.gif) no-repeat left top; FLOAT: left; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 0px}
#header A {
        PADDING-RIGHT: 15px; DISPLAY: block; PADDING-LEFT: 6px; FONT-WEIGHT: bold; BACKGROUND: url(imagenes/right.gif) no-repeat right top; FLOAT: left; PADDING-BOTTOM: 4px; COLOR: #765; PADDING-TOP: 5px; TEXT-DECORATION: none}
#header A { FLOAT: none}
#header A:hover {  COLOR: #333 }
#header #current { BACKGROUND-IMAGE: url(imagenes/left_on.gif)}
#header #current A { BACKGROUND-IMAGE: url(imagenes/right_on.gif); PADDING-BOTTOM: 5px; COLOR: #333 }
-->
</style>
</head>
<body>
<div id="cajaModal"></div>
<!-- pretty -->
<span class="gallery clearfix"></span>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<table width="100%" cellspacing="0" cellpadding="0">
 <tr>
  <td class="titulo">Reporte | Listado de Activos Asignados a Usuarios</td>
  <td align="right">
   <a class="cerrar" href="framemain.php">[cerrar]</a>
  </td>
 </tr>
</table>
<hr width="100%" color="#333333" />
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<?php 
if(!$_POST) $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
$MAXLIMIT=30;
$filtro = "";
if($forganismo!="")$corganismo = "checked";else $dorganismo = "disabled";
if($fDependencia!="")$cDependencia="checked";else $dDependencia="disabled";
if($fNaturaleza!="")$cNaturaleza="checked";else $dNaturaleza="disabled";
if($festado!="") $cEstado="checked"; else $dEstado="disabled";
if(($fperiodo_desde!="")and($fperiodo_hasta!="")) $cPeriodo="checked"; else $dPeriodo="disabled";
if($cod_empresponsable!="")$cResponsable="checked"; else $dResponsable ="disabled";


?>
<? echo"
<form name='frmentrada' id='frmentrada' action='af_rptabctivosxempleado.php' method='POST' target='iReporte'>
<input type='hidden' name='limit' id='limit' value='".$limit."'>
<input type='hidden' name='registros' id='registros' value='".$registros."'/>
<input type='hidden' name='usuarioActual' id='usuarioActual' value='".$_SESSION['USUARIO_ACTUAL']."'/>
<input type='hidden' id='asignado' name='asignado'/>
<input type='hidden' id='filtro' name='filtro'/>

<div class='divBorder' style='width:900px;'>
<table width='900' class='tblFiltro'>

<tr>
  <td class='tagForm'>Organismo:</td>
<td>
<input type='checkbox' name='chkorganismo' id='chkorganismo' value='1' $corganismo onclick='this.checked=true' />
<select name='forganismo' id='forganismo' class='selectBig' $dorganismo onchange='getFOptions_2(this.id, \"fanteproyecto\", \"chknanteproyecto\");'>";
	getOrganismos($obj[2], 3, $_SESSION[ORGANISMO_ACTUAL]);
	echo "
</select>
</td>
  <td align='right'>Naturaleza:</td>
 <td><input type='checkbox' id='chkNaturaleza' name='chkNaturaleza' value='1' $cNaturaleza onclick='enabledRPNaturaleza(this.form);'/>
     <select id='fNaturaleza' name='fNaturaleza' class='selectMed' $dNaturaleza>
	   <option></option>";
	   getNaturaleza($fNaturaleza,0);
	 echo " </select></td>	
</tr>

<tr>
  <td class='tagForm'>Dependencia:</td>
  <td><input type='checkbox' id='checkDependencia' name='checkDependencia' value='1' $cDependencia onclick='enabledDependencia(this.form);'/>
      <select id='fDependencia' name='fDependencia' $dDependencia class='selectBig'>
	     <option value=''></option>";
		   getDependencias($fDependencia, $forganismo, 2);
		 echo"</td>
  <td align='right'>Estado:</td>
 <td><input type='checkbox' id='checkEstado'  name='checkEstado' value='1' $cEstado onclick='enabledEstado(this.form);'/>
     <select id='fEstado' name='fEstado' class='selectMed' $dEstado>
	 <option></option>";
	 getEstado($fEstado, 2);
	 echo" </td>	 
</tr>

<tr>
 <td class='tagForm'>Responsable:</td>
  <td class='gallery clearfix'><input type='checkbox' name='chkResponsable' id='chkResponsable' value='1' $cResponsable onclick='enabledResponsable(this.form);'/>
	  <input type='hidden' id='cod_empresponsable' value='$cod_empresponsable' name='cod_empresponsable'/>
	  <input type='text' id='empleado_responsable' value='$empleado_responsable' name='empleado_responsable' $dResponsable size='62' readonly/>";?> <a id="responsable" href="af_listaempleados.php?filtrar=default&limit=0&campo=8&iframe=true&width=70%&height=100%" rel="prettyPhoto[iframe6]" style="visibility:hidden">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;"/>
            </a>
	 <?  echo"</td>
  <td align='right'>Periodo:</td>
  <td><input type='checkbox' name='chkPeriodo' id='chkPeriodo' value='1' $cPeriodo onclick='enabledPeriodos(this.form);'/> 
      <input type='text' name='fperiodo_desde' id='fperiodo_desde' value='$fperiodo_desde' $dPeriodo size='15'/> hasta
	  <input type='text' name='fperiodo_hasta' id='fperiodo_hasta' value='$fperiodo_hasta' $dPeriodo size='15'/></td>
</tr>
</table>
</div>
<center><input type='submit' name='btBuscar' value='Buscar' onclick='cargarActivosAsignadosPersona(this.form)'></center>
<br /><div style='width:900;' class='divDivision'>Lista de Activos Asignados por Persona</div>
<form/><br />";
?>
<table width="900" class="tblBotones">
<tr>
<td><div id="rows"></div></td>
<td width="250">
<?php 
//echo"<input type='hidden' name='regresar' id='regresar' value='cpi_docinternoslista'/>";
?>
		</td>
		<td align="right">
<!--<input type="button" id="btEjecutar" name="btejecutar"  value="Ejecutar Cierre" onclick="ProcesoEjecutarCierre(this.form);"/>-->
		</td>
	</tr>
</table>
<input type="hidden" name="registro" id="registro" />
<div style="width:900px" class="divFormCaption"></div>
<center>
<iframe name="af_rptabactivosxempleadopdf" id="af_rptabactivosxempleadopdf" style="border:solid 1px #CDCDCD; width:900px; height:400px; visibility:false; display:false;" ></iframe>
</center>

<form/>
</body>
</html>
