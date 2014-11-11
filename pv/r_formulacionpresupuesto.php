<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location:index.php");
//	------------------------------------
include("fphp.php");
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
		<td class="titulo">Reporte | Formulaci&oacute;n Presupuesto</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />
<?php

if(!$_POST) $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
$MAXLIMIT=30;

$filtro = "";
if ($forganismo != "") $corganismo = "checked"; else $dorganismo = "disabled";
if ($fnproyecto != "") $cnproyecto = "checked";  else $dnproyecto = "disabled";
if ($fPeriodoEjec !="") $cPeriodoEjec = "checked"; else $dPeriodoEjec = "disabled"; 
//	-------------------------------------------------------------------------------
//// ------------------------------------------------------------------------------
echo "
<form name='frmentrada' action='r_formulacionpresupuestopdf.php' method='POST' target='reporte'>";
echo" <input type='hidden' name='limit' id='limit' value='".$limit."'/>
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
 <td align='right'>Per&iacute;odo Ejecuci&oacute;n:</td>
 <td>
  <input type='checkbox' name='chkPeriodoEjec' id='chkPeriodoEjec' value='1' $cPeriodoEjec onclick='enabledPeriodoEjecucion(this.form);' />
  <input type='text' name='fPeriodoEjec' id='fPeriodoEjec' value='$fPeriodoEjec' size='4' maxlength='4' $dPeriodoEjec />
  </td>
</tr>
<tr>
  <td align='right'>Nro. Presupuesto:</td>
  <td>
	<input type='checkbox' name='chknproyecto' id='chknproyecto' value='1' $cnproyecto onclick='enabledNumeroProyecto(this.form);' />
	<input type='text' name='fnproyecto' id='fnproyecto' size='6' maxlength='4' $dnproyecto value='$fnproyecto' />
  </td>
</tr>
<tr><td height='2'></td></tr>
</table>
</div>
<center><input type='submit' name='btBuscar' value='Buscar'  onclick='filtroPresupuesto(this.form,0)'></center>
<br/><br/>";?>
<div style="width:1000px" class="divFormCaption"></div>
<center>
<iframe name="reporte" id="reporte" style="border:solid 1px #CDCDCD; width:1000px; height:350px;">
</iframe>
</center>
</form>
</body>
</html>
