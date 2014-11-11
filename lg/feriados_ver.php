<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
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
include("fphp.php");
connect();
//	------------------
$sql = "SELECT * FROM rh_feriados WHERE CodFeriado = '".$registro."'";
$query = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro de Feriados | Ver Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<div style="width:500px" class="divFormCaption">Datos del Dia Feriado</div>
<table width="500" class="tblForm">
	<tr>
		<td class="tagForm">C&oacute;digo:</td>
		<td><input name="codigo" type="text" id="codigo" size="6" value="<?=$field['CodFeriado']?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm">A&ntilde;o:</td>
		<td><input name="anio" type="text" id="anio" size="6" value="<?=$field['AnioFeriado']?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm">Dia Feriado:</td>
		<td><input name="feriado" type="text" id="feriado" size="6" maxlength="5" value="<?=$field['DiaFeriado']?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td><input name="descripcion" type="text" id="descripcion" size="50" maxlength="25" value="<?=utf8_decode($field['Descripcion'])?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<?php
			if ($field['FlagVariable'] == "S") $flagvariable = "checked";
			?>
			<input name="flagvariable" type="checkbox" id="flagvariable" <?=$flagvariable?> disabled="disabled" /> Variable todos los a&ntilde;os?
		</td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
			<?php
			if ($field['Estado'] == "A") $flagactivo = "checked"; else $flaginactivo = "checked";
			?>
			<input id="activo" name="status" type="radio" value="A" <?=$flagactivo?> disabled="disabled" /> Activo
			<input id="inactivo" name="status" type="radio" value="I" <?=$flaginactivo?> disabled="disabled" /> Inactivo
		</td>
	</tr>
	<tr>
	<td class="tagForm">&Uacute;ltima Modif.:</td>
	<td>
		<input name="ult_usuario" type="text" id="ult_usuario" size="30" value="<?=$field['UltimoUsuario']?>" readonly />
		<input name="ult_fecha" type="text" id="ult_fecha" size="25" value="<?=$field['UltimaFecha']?>" readonly />
	</td>
	</tr>
</table>
</body>
</html>
