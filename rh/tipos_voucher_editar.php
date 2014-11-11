<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_sia.js"></script>
</head>

<body onload="document.getElementById('descripcion').focus();">
<?php
include("fphp_sia.php");
connect();
//----------------------
$sql = "SELECT * FROM ac_voucher WHERE CodVoucher = '".$registro."'";
$query = mysql_query($sql) or de ($sql.mysql_error());
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Tipos de Voucher | Modificar Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'tipos_voucher.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="tipos_voucher.php" method="POST" onsubmit="return verificarTipoVoucher(this, 'ACTUALIZAR');">
<input type="hidden" name="filtro" id="filtro" value="<?=$filtro?>" />
<div style="width:500px" class="divFormCaption">Datos del Tipo de Voucher</div>
<table width="500" class="tblForm">
	<tr>
		<td class="tagForm" width="125">C&oacute;digo:</td>
		<td><input name="codigo" type="text" id="codigo" size="4" value="<?=$field['CodVoucher']?>" disabled="disabled" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td><input name="descripcion" type="text" id="descripcion" style="width:90%;" maxlength="50" value="<?=($field['Descripcion'])?>" />*</td>
	</tr>
	<tr>
		<td class="tagForm">&nbsp;</td>
		<td>
			<? if ($field['FlagManual'] == "S") $flag = "checked"; ?>
			<input type="checkbox" name="flagmanual" id="flagmanual" <?=$flag?> /> Solo para Voucher Manual
		</td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
			<? if ($field['Estado'] == "A") $activo = "checked"; else $inactivo="checked"; ?>
			<input id="activo" name="status" type="radio" value="A" <?=$activo?> /> Activo &nbsp;&nbsp;
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
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'tipos_voucher.php');" />
</center>
<div style="width:500px" class="divMsj">Campos Obligatorios *</div>
</form>

</body>
</html>
