<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_nomina.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('02', $concepto);
//	------------------------------------
$ftiponom = $_SESSION["NOMINA_ACTUAL"];
$forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
$dSeleccion1 = "disabled"; 
$dSeleccion2 = "disabled"; 
$dfsittra = "disabled";
	//	---------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_nomina.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Reporte de Retenciones Embargo Prima por Hijos</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" method="post" action="pdf_embargo_hijos.php" target="iReporte">
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
    <tr>
        <td align="right">Organismo:</td>
        <td>
        	<input type="checkbox" name="chkorganismo" id="chkorganismo" value="1" onclick="forzarCheck('chkorganismo');" checked="checked" />
			<select name="forganismo" id="forganismo" class="selectBig">
				<?=getOrganismos($forganismo, 3)?>
			</select>
        </td>
        <td align="right">N&oacute;mina:</td>
        <td>
        	<input type="checkbox" name="chktiponom" id="chktiponom" value="1" onclick="forzarCheck('chktiponom');" checked="checked" />
			<select name="ftiponom" id="ftiponom" class="selectBig" onchange="getFOptions_Periodo(this.id, 'fperiodo', 'chkperiodo', document.getElementById('forganismo').value, '6'); getFOptions_Proceso('ftiponom', 'ftproceso', 'chktproceso', this.value, document.getElementById('forganismo').value, '6');">
				<?=getTNomina($ftiponom, 0)?>
			</select>
        </td>
    </tr>
    <tr>
        <td align="right">Per&iacute;odo:</td>
        <td>
        	<input type="checkbox" name="chkperiodo" id="chkperiodo" value="1" onclick="forzarCheck('chkperiodo');" checked="checked" />
			<select name="fperiodo" id="fperiodo" style="width:100px;" onchange="getFOptions_Proceso(this.id, 'ftproceso', 'chktproceso', document.getElementById('ftiponom').value, document.getElementById('forganismo').value, '6');">
				<option value=""></option>
				<?=getPeriodos($fperiodo, $ftiponom, $forganismo, 6);?>
			</select>
		</td>
        <td align="right">Proceso:</td>
        <td>
        	<input type="checkbox" name="chktproceso" id="chktproceso" value="1" onclick="forzarCheck('chktproceso');" checked="checked" />
			<select name="ftproceso" id="ftproceso" class="selectBig">
				<option value=""></option>
                <?=getTipoProcesoNomina($ftproceso, $fperiodo, $ftiponom, $forganismo, 6)?>
			</select>
		</td>
    </tr>
	<tr><td colspan="4"><hr width="90%" size="2" /></td></tr>
	<tr>
		<td align="right"></td>
		<td colspan="3">
        	<input type="checkbox" name="chkempleado" id="chkempleado" value="1" onclick="enabledInputBrowse(this.checked, 'codempleado', 'nomempleado', 'bt_examinar');" />
			<input type="text" name="codempleado" id="codempleado" size="6" readonly />
			<input type="text" name="nomempleado" id="nomempleado" size="60" readonly />
			<input name="bt_examinar" type="button" id="bt_examinar" value="..." onclick="cargarVentana(this.form, 'lista_empleados.php?limit=0&campo=1', 'height=500, width=900, left=200, top=200, resizable=yes');" disabled="disabled" />
		</td>
	</tr>
</table>
</div>
<center><input type="submit" name="btBuscar" value="Buscar"></center>
</form> 

<br /><div class="divDivision">Reporte de Retenciones Embargo Prima por Hijos</div><br />

<center>
<iframe name="iReporte" id="iReporte" style="border:solid 1px #CDCDCD; width:1000px; height:750px;"></iframe>
</center>
</body>
</html>