<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_lg.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('07', $concepto);
//	------------------------------------
$fdesde = "01-".date("m-Y");
$fhasta = date("d-m-Y");
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_lg.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Consumo por Dependencia</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" method="post" action="reporte_consumo_dependencia_pdf.php" target="iReporte">
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
    <tr>
		<td width="125" align="right">Organismo:</td>
		<td>
			<input type="checkbox" name="chkorganismo" id="chkorganismo" value="1" onclick="this.checked=!this.checked" checked="checked" />
			<select name="forganismo" id="forganismo" class="selectBig" onchange="getFOptions_2(this.id, 'fdependencia', 'chkdependencia');">
				<?=getOrganismos($_SESSION["ORGANISMO_ACTUAL"], 3);?>
			</select>
		</td>
		<td width="125" align="right">Periodo:</td>
		<td>
			<input type="checkbox" name="chkfecha" value="1" onclick="chkFiltro_2(this.checked, 'fdesde', 'fhasta')" checked="checked" />
			<input type="text" name="fdesde" id="fdesde" size="15" maxlength="10" value="<?=$fdesde?>" /> - 
			<input type="text" name="fhasta" id="fhasta" size="15" maxlength="10" value="<?=$fhasta?>" />
		</td>
		
	</tr>
	<tr>
		<td align="right">Dependencia:</td>
		<td>
			<input type="checkbox" name="chkdependencia" id="chkdependencia" value="1" onclick="enabledDependencia(this.form);" />
			<select name="fdependencia" id="fdependencia" class="selectBig" disabled="disabled">
				<option value=''></option>
				<?=getDependencias("", $_SESSION["ORGANISMO_ACTUAL"], 3);?>
			</select>
		</td>
		<td align="right">Centro de Costo:</td>
		<td>
			<input type="checkbox" name="chkccosto" id="chkccosto" value="1" onclick="enabledCCosto(this.form);" />
			<input type="text" name="nomccosto" id="nomccosto" size="55" readonly="readonly" />
			<input type="hidden" name="fccosto" id="fccosto" />
			<input type="button" value="..." id="btCCosto" onclick="cargarVentana(this.form, 'listado_centro_costos.php?cod=fccosto&nom=nomccosto', 'height=600, width=1100, left=50, top=50, resizable=yes');" disabled="disabled" />
		</td>
	</tr>
</table>
</div>
<center><input type="submit" name="btBuscar" value="Buscar"></center>
</form> 

<br /><div class="divDivision">Consumo por Dependencia</div><br />

<center>
<iframe name="iReporte" id="iReporte" style="border:solid 1px #CDCDCD; width:1000px; height:750px;"></iframe>
</center>
</body>
</html>