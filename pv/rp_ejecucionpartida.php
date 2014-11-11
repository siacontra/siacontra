<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location:index.php");
//	------------------------------------
include("fphp.php");
include("r_php.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('01', $concepto);
//	------------------------------------
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="r_fscript.js"></script>
</head>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Reporte | Ejecuci&oacute;n por Partida</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />
<?php

if(!$_POST) $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
if(!$_POST) $fPEjecucion=date("Y"); $cPEjecucion = "checked";
if(!$_POST) $fEstado='PA'; $cEstado = "checked";
$MAXLIMIT=30;

$filtro = "";
if ($forganismo != "")$corganismo = "checked";else $dorganismo = "disabled";
if ($fPartida != "")$cPartida = "checked";else $dPartida = "disabled";
if ($fNumPresupuesto != "")$cNumPresupuesto = "checked"; else $dNumPresupuesto = "disabled";
if ($fEstado != "")$cEstado = "checked"; else $dEstado = "disabled";
if ($fdesde != "" || $fhasta != "")	$cFechaDocumento = "checked"; else $dFechaDocumento = "disabled";
if ($fPEjecucion != "")$cPEjecucion = "checked";else $dPEjecucion = "disabled";
//	-------------------------------------------------------------------------------
//// ------------------------------------------------------------------------------
echo "
<form name='frmentrada' id='frmentrada' method='POST' action='rp_ejecucionpartidapdf.php'  target='reporte'>
      <input type='hidden' name='limit' id='limit' value='".$limit."'/>
      <input type='hidden' name='registros' id='registros' value='".$registros."'/>

<div class='divBorder' style='width:900px;'>
<table width='900' class='tblFiltro'>
<tr>
 <td align='right'>Organismo:</td>
 <td>
  <input type='checkbox' name='chkorganismo' id='chkorganismo' value='1' $corganismo onclick='this.checked=true' />
  <select name='forganismo' id='forganismo' class='selectBig' $dorganismo onchange='getFOptions_2(this.id, \"fnanteproyecto\", \"chknanteproyecto\");'>";
		getOrganismos($forganismo, 3, $_SESSION[ORGANISMO_ACTUAL]);
		echo "
   </select>
 </td>
 <td align='right'>Fecha:</td>
  <td align='left'>
    <input type='checkbox' name='chkFecha' id='chkFecha' value='1' $cFechaDocumento onclick='enabledFechaRpEjecucionPartida(this.form);' />
    <input type='text' name='fdesde' id='fdesde' size='15' maxlength='10' $dFechaDocumento value='$fdesde'/> - 
    <input type='text' name='fhasta' id='fhasta' size='15' maxlength='10' $dFechaDocumento value='$fhasta'/>
  </td>
</tr>

<tr>
  <td align='right'>Partida Nro.:</td>
  <td>
	<input type='checkbox' name='chkPartida' id='chkPartida' value='1' $cPartida onclick='enabledPartidaRP(this.form);' />
	<input type='text' name='fPartida' id='fPartida' size='35' maxlength=12' $dPartida value='$fPartida'/>";?> <input type='button' id='btPartida' name='btPartida' value='...' onclick="cargarVentana(this.form, 'rp_listapartidas.php?limit=0&campo=1', 'height=500, width=800, left=200, top=200, resizable=yes');" disabled/>
  <? echo"</td>
  <td align='right'>P. Ejecuci&oacute;n:</td>
  <td><input type='checkbox' id='chkPEjecucion' name='chkPEjecucion' value='1' $cPEjecucion onclick='enabledPEjecucion(this.form);'/>
      <input type='text' id='fPEjecucion' name='fPEjecucion' size='6' maxlength='4' value='$fPEjecucion' $dPEjecucion/></td>
</tr>

<tr>
   <td align='right'>Nro. Presupuesto:</td>
  <td>
	<input type='checkbox' name='chknpresupuesto' id='chknpresupuesto' value='1' $cNumPresupuesto onclick='enabledNumPresupuestoRP(this.form);' />
	<input type='text' name='fNumPresupuesto' id='fNumPresupuesto' size='6' maxlength='4' $dNumPresupuesto value='$fNumPresupuesto' />
  </td>
  <td align='right'>Estado:</td>
  <td align='left'>
    <input type='checkbox' name='chkestado' id='chkestado' value='1' $cEstado onclick='enabledEstadoRP(this.form);' />
	<select id='fEstado' name='fEstado' $dEstado>
	  <option value=''></option>";
	     getEstadoRp($fEstado, 0);
	echo"</select></td>
</tr>

<tr><td height='2'></td></tr>
</table>
</div>
<center><input type='submit' name='btBuscar' value='Buscar' onclick='filtroReporteEjecucionPartida(this.form, 0);'></center>
<br /><div class='divDivision' style='width:900px'>Resultados</div>";
//// ------------------------------------------------------------------------------
?>
<div style="width:900px" class="divFormCaption"></div>
<center>
<iframe name="reporte" id="reporte" style="border:solid 1px #CDCDCD; width:900px; height:300px;"></iframe>
</center>
</form>
</body>
</html>
