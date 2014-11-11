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

<body>
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
		<td class="titulo">Tipo de Transacciones Bancarias | Ver Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<div style="width:700px" class="divFormCaption">Datos del Registro</div>
<table width="700" class="tblForm">
	<tr>
		<td class="tagForm" width="125">C&oacute;digo:</td>
		<td><input type="text" id="codigo" maxlength="3" value="<?=$field['CodTipoTransaccion']?>" style="width:50px;" disabled="disabled" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td><input type="text" id="descripcion" maxlength="100" value="<?=($field['Descripcion'])?>" style="width:297px;" disabled="disabled" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Tipo de Transacci&oacute;n:</td>
		<td>
        	<select id="ttransaccion" style="width:123px;" disabled="disabled">
            	<?=loadSelectValores("TIPO-TRANSACCION", $field['TipoTransaccion'], 1);?>
            </select>*
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
        	<? if ($field['FlagVoucher'] == "S") $flagvoucher = "checked"; ?>
        	<input type="checkbox" id="flagvoucher" value="S" <?=$flagvoucher?> disabled="disabled" /> Generar Voucher
		</td>
	</tr>
    <tr>
        <td class="tagForm">Cta. Contable:</td>
        <td>
            <input type="text" name="codcuenta" id="codcuenta" size="15" value="<?=$field['CodCuenta']?>" disabled="disabled" />
            <input type="hidden" name="nomcuenta" id="nomcuenta" />
            <input type="button" value="..." disabled="disabled" />
        </td>
    </tr>
	<tr>
		<td class="tagForm">Tipo de Voucher:</td>
		<td>
        	<select id="voucher" style="width:300px;" disabled="disabled">
            	<?=loadSelect("ac_voucher", "CodVoucher", "Descripcion", $field['CodVoucher'], 1);?>
            </select>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
        	<? if ($field['FlagTransaccion'] == "S") $flagtransaccion = "checked";?>
        	<input type="checkbox" id="flagtransaccion" value="S" <?=$flagtransaccion?> disabled="disabled" /> Transaccion del Sistema
		</td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
        	<? if ($field['Estado'] == "A") $flagactivo = "checked"; else $flaginactivo = "checked"; ?>
			<input id="activo" name="estado" type="radio" value="A" <?=$flagactivo?> disabled="disabled" /> Activo &nbsp;&nbsp;
			<input id="inactivo" name="estado" type="radio" value="I" <?=$flaginactivo?> disabled="disabled" /> Inactivo
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

</body>
</html>
