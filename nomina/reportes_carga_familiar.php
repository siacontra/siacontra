<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('02', $concepto);
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
		<td class="titulo">Reporte de Carga Familiar</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" method="post" action="pdf_carga_familiar.php" target="iCarga">
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td width="170" align="right">Organismo:</td>
		<td colspan="3">
			<input type="checkbox" name="chkorganismo" id="chkorganismo" value="1" onclick="forzarCheck('chkorganismo');" checked="checked" />
			<select name="forganismo" id="forganismo" class="selectBig">
				<?=getOrganismos($_SESSION["FILTRO_ORGANISMO_ACTUAL"], 3)?>
			</select>
		</td>
	</tr>
	<tr>
		<td width="170" class="tagForm">Empleado:</td>
		<td colspan="3">
			<input type="checkbox" name="chkempleado" id="chkempleado" value="1" onclick="enabledNomEmpleado(this.form);" />
			<input name="codempleado" type="hidden" id="codempleado" />
			<input name="nomempleado" type="text" id="nomempleado" size="75" readonly />
			<input name="bt_examinar" type="button" id="bt_examinar" value="..." onclick="cargarVentana(this.form, 'lista_empleados.php?limit=0&campo=1', 'height=500, width=800, left=200, top=200, resizable=yes');" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td width="170" class="tagForm">Sexo(titular):</td>
		<td colspan="3">
			<input type="checkbox" name="chksexo" id="chksexo" value="1" onclick="enabledSexo(this.form);" />
			<select name="fsexo" id="fsexo" disabled="disabled">
				<option value=""></option>
				<?=getSexo("", 0)?>
			</select>
		</td>
	</tr>
	<tr><td colspan="4"><hr style="border:solid 0.1px #333; width:900px;" /></td></tr>
	<tr>
		<td width="170" class="tagForm">Edad:</td>
		<td width="333">
			<input type="checkbox" name="chkedad" id="chkedad" value="1" onclick="enabledCargaEdad(this.form);" />
			>= <input type="text" name="fedadd" id="fedadd" size="5" maxlength="3" disabled="disabled" />
			<= <input type="text" name="fedadh" id="fedadh" size="5" maxlength="3" disabled="disabled" />
		</td>
		<td width="155" class="tagForm">Parentesco:</td>
		<td width="322">
			<input type="checkbox" name="chkparentesco" id="chkparentesco" value="1" onclick="enabledParentesco(this.form);" />
			<select name="fparentesco" id="fparentesco" disabled="disabled">
				<option value=""></option>
				<?=getMiscelaneos("", "PARENT", 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td width="170" class="tagForm">Sexo(familiar):</td>
		<td>
			<input type="checkbox" name="chkfsexo" id="chkfsexo" value="1" onclick="enabledFSexo(this.form);" />
			<select name="ffsexo" id="ffsexo" disabled="disabled">
				<option value=""></option>
				<?=getSexo("", 0)?>
			</select>
		</td>
		<td class="tagForm">&iquest;Seguro M&eacute;dico?:</td>
		<td>
			<input type="checkbox" name="chkseguro" id="chkseguro" value="S" />
		</td>
	</tr>
</table>
</div>
<center><input type="submit" name="btBuscar" value="Buscar"></center>
</form> 

<br /><div class="divDivision">Listado de Carga Familiar</div><br />

<center>
<iframe name="iCarga" id="iCarga" style="border:solid 1px #CDCDCD; width:1000px; height:750px;"></iframe>
</center>
</body>
</html>