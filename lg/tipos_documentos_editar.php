<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_lg.js"></script>
</head>

<body onload="document.getElementById('descripcion').focus();">
<?php
include("fphp_lg.php");
connect();
//	------------------------
$sql = "SELECT * FROM lg_tipodocumento WHERE CodDocumento = '".$registro."'";
$query = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Tipo de Documentos | Actualizar Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'tipos_documentos.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="tipos_documentos.php" method="POST" onsubmit="return verificarTiposDocumentos(this, 'ACTUALIZAR');">
<input type="hidden" name="filtro" id="filtro" value="<?=$filtro?>" />
<div style="width:600px;" class="divFormCaption">Datos del Tipo de Documento</div>
<table width="600px" class="tblForm">
	<tr>
		<td class="tagForm">Tipo de Documento:</td>
		<td><input name="codigo" type="text" id="codigo" size="5" maxlength="2" value="<?=$field['CodDocumento']?>" disabled="disabled" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td><input name="descripcion" type="text" id="descripcion" size="50" maxlength="50" value="<?=($field['Descripcion'])?>" />*</td>
	</tr>
	<tr>
		<td class="tagForm">&nbsp</td>
		<td>
			<? if ($field['FlagDocFiscal'] == "S") $flagdocfiscal = "checked"; ?>
			<input type="checkbox" name="flagdocfiscal" id="flagdocfiscal" value="S" <?=$flagdocfiscal?> /> Documento Fiscal
		</td>
	</tr>
	<tr>
		<td class="tagForm">&nbsp</td>
		<td>
			<? if ($field['FlagTransaccion'] == "S") $flagtransaccion = "checked"; ?>
			<input type="checkbox" name="flagtransaccion" id="flagtransaccion" value="S" <?=$flagtransaccion?> /> Transacción del Sistema
		</td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
			<? if ($field['Estado'] == "A") $activo = "checked"; else $inactivo = "checked"; ?>
			<input id="activo" name="status" type="radio" value="A" <?=$activo?> /> Activo
			<input id="inactivo" name="status" type="radio" value="I" <?=$inactivo?> /> Inactivo
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
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'tipos_documentos.php');" />
</center><br />
</form>

<div style="width:600px%" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
