<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_ap.js"></script>
</head>

<body onload="document.getElementById('descripcion').focus();">
<?php
include("fphp_ap.php");
connect();
//	------------------------------------------------
$sql = "SELECT * FROM ap_bancotipotransaccion WHERE CodTipoTransaccion = '".$registro."'";
$query = mysql_query($sql) or die($sql.mysql_error());
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
//	------------------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Tipo de Transacciones Bancarias | Modificar Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'ap_tipo_transaccion_bancaria.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="ap_tipo_transaccion_bancaria.php" method="POST" onsubmit="return verificarTipoTransaccionBancaria(this, 'ACTUALIZAR');">
<input type="hidden" name="filtro" id="filtro" value="<?=$filtro?>" />
<div style="width:700px" class="divFormCaption">Datos del Registro</div>
<table width="700" class="tblForm">
	<tr>
		<td class="tagForm" width="125">C&oacute;digo:</td>
		<td><input type="text" id="codigo" maxlength="3" value="<?=$field['CodTipoTransaccion']?>" style="width:50px;" disabled="disabled" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td><input type="text" id="descripcion" maxlength="100" value="<?=($field['Descripcion'])?>" style="width:297px;" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Tipo de Transacci&oacute;n:</td>
		<td>
        	<select id="ttransaccion" style="width:123px;">
            	<?=loadSelectValores("TIPO-TRANSACCION", $field['TipoTransaccion'], 0);?>
            </select>*
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
        	<? if ($field['FlagVoucher'] == "S") $flagvoucher = "checked"; ?>
        	<input type="checkbox" id="flagvoucher" value="S" <?=$flagvoucher?> /> Generar Voucher
		</td>
	</tr>
    <tr>
        <td class="tagForm">Cta. Contable:</td>
        <td>
            <input type="text" name="codcuenta" id="codcuenta" size="15" value="<?=$field['CodCuenta']?>" disabled="disabled" />
            <input type="hidden" name="nomcuenta" id="nomcuenta" />
            <input type="button" value="..." onclick="cargarVentana(this.form, 'listado_cuentas_contables.php?cod=codcuenta&nom=nomcuenta', 'height=500, width=900, left=50, top=50, resizable=yes');" />
        </td>
    </tr>
	<tr>
		<td class="tagForm">Tipo de Voucher:</td>
		<td>
        	<select id="voucher" style="width:300px;">
            	<option value=""></option>
            	<?=loadSelect("ac_voucher", "CodVoucher", "Descripcion", $field['CodVoucher'], 0);?>
            </select>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
        	<? if ($field['FlagTransaccion'] == "S") $flagtransaccion = "checked";?>
        	<input type="checkbox" id="flagtransaccion" value="S" <?=$flagtransaccion?> /> Transaccion del Sistema
		</td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
        	<? if ($field['Estado'] == "A") $flagactivo = "checked"; else $flaginactivo = "checked"; ?>
			<input id="activo" name="estado" type="radio" value="A" <?=$flagactivo?> /> Activo &nbsp;&nbsp;
			<input id="inactivo" name="estado" type="radio" value="I" <?=$flaginactivo?> /> Inactivo
		</td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td>
			<input name="ult_usuario" type="text" id="ult_usuario" size="30" value="<?=$field_mast['UltimoUsuario']?>" disabled="disabled" />
			<input name="ult_fecha" type="text" id="ult_fecha" size="25" value="<?=$field_mast['UltimoFecha']?>" disabled="disabled" />
		</td>
	</tr>
</table>
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'ap_tipo_transaccion_bancaria.php');" />
</center>
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</form>

</body>
</html>
