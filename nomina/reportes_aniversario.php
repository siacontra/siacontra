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
<?php
$mes=date("m"); $mes=(int) $mes;
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Reporte de Aniversarios</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" method="post" action="pdf_aniversario.php" target="iAniversario">
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td width="125" align="right">Organismo:</td>
		<td colspan="3">
			<input type="checkbox" name="chkorganismo" id="chkorganismo" value="1" size="200px;" onclick="forzarCheck('chkorganismo');" checked="checked" />
			<select name="forganismo" id="forganismo" class="selectBig">
				<?=getOrganismos($_SESSION["FILTRO_ORGANISMO_ACTUAL"], 3)?>
			</select>
		</td>
	</tr>
	<tr>
		<td width="125" align="right">Planilla:</td>
		<td colspan="3">
			<input type="checkbox" name="chkttrabajador" id="chkttrabajador" value="1" size="200px;" onclick="enabledTTrabajador(this.form);" />
			<select name="fttrabajador" id="fttrabajador" class="selectBig" disabled="disabled">
				<option value=""></option>
				<?=getTTrabajador("", 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td width="125" align="right">Mes:</td>
		<td colspan="3">
			<input type="checkbox" name="chkmes" id="chkmes" value="1" size="200px;" onclick="enabledMes(this.form);" checked="checked" />
			<select name="fmes" id="fmes" class="selectBig">
				<option value=""></option>
				<?=getMeses($mes, 0)?>
			</select>
		</td>
	</tr>
</table>
</div>
<center><input type="submit" name="btBuscar" value="Buscar"></center>
</form> 

<br /><div class="divDivision">Listado de Aniversarios</div><br />

<center>
<iframe name="iAniversario" id="iAniversario" style="border:solid 1px #CDCDCD; width:1000px; height:750px;"></iframe>
</center>
</body>
</html>