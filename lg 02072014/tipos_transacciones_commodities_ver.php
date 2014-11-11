<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_lg_2.js"></script>
</head>

<body onload="document.getElementById('descripcion').focus();">
<?php
include("fphp_lg.php");
connect();
//	---------------------
$sql = "SELECT * FROM lg_operacioncommodity WHERE CodOperacion = '".$registro."'";
$query = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Tipo de Transacciones | Ver Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<div style="width:600px;" class="divFormCaption">Datos del Tipo de Transacci&oacute;n</div>
<table width="600px" class="tblForm">
	<tr>
		<td class="tagForm">Tipo de Transacci&oacute;n:</td>
		<td><input name="codigo" type="text" id="codigo" size="5" maxlength="3" value="<?=$field['CodTransaccion']?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td><input name="descripcion" type="text" id="descripcion" size="50" maxlength="50" value="<?=($field['Descripcion'])?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm">Tipo de Movimiento:</td>
		<td>
			<? 
			if ($field['TipoMovimiento'] == "I") { $flagingreso = "checked"; $docgenerado = "NI"; $docgeneradodesc = "Nota de Ingreso"; }
			elseif ($field['TipoMovimiento'] == "E") { $flagegreso = "checked"; $docgenerado = "NS"; $docgeneradodesc = "Nota de Salida"; }
			elseif ($field['TipoMovimiento'] == "T") { $flagtransaccion = "checked"; $docgenerado = "NT"; $docgeneradodesc = "Nota de Transferencia"; }
			?>
			<input id="ingreso" name="tipo" type="radio" value="I" checked onclick="setDocumentoGenerado('NI');" <?=$flagingreso?> disabled="disabled" /> Ingreso
			<input id="egreso" name="tipo" type="radio" onclick="setDocumentoGenerado('NS');" value="E" <?=$flagegreso?> disabled="disabled" /> Egreso
			<input id="transferencia" name="tipo" type="radio" value="T" onclick="setDocumentoGenerado('NT');" <?=$flagtransaccion?> disabled="disabled" /> Transferencia
		</td>
	</tr>
	<tr>
		<td class="tagForm">Doc. a generar:</td>
		<td>
			<input name="docgenerado" type="hidden" id="docgenerado" value="<?=$docgenerado?>" />
			<input name="docgeneradodesc" type="text" id="docgeneradodesc" size="50" value="<?=($docgeneradodesc)?>" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Doc. Transacci&oacute;n:</td>
		<td>
			<select name="doctransaccion" id="doctransaccion" style="width:175px;">
				<?=loadSelect("lg_tipodocumento", "CodDocumento", "Descripcion", $field['TipoDocTransaccion'], 1)?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
			<? if ($field['Estado'] == "A") $flagactivo = "checked"; else $flaginactivo = "checked"; ?>
			<input id="activo" name="status" type="radio" value="A" <?=$flagactivo?> disabled="disabled" /> Activo
			<input id="inactivo" name="status" type="radio" value="I" <?=$flaginactivo?> disabled="disabled" /> Inactivo
		</td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td>
			<input name="ult_usuario" type="text" id="ult_usuario" size="30" value="<?=$field['UltimoUsuario']?>" disabled="disabled" />
			<input name="ult_fecha" type="text" id="ult_fecha" size="25" value="<?=$field['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>
</table>
</body>
</html>
