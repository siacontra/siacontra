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

<body onload="document.getElementById('codigo').focus();">
<?php
include("fphp_ap.php");
connect();
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Tipo de Transacciones Bancarias | Nuevo Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'ap_tipo_transaccion_bancaria.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="ap_tipo_transaccion_bancaria.php" method="POST" onsubmit="return verificarTipoTransaccionBancaria(this, 'INSERTAR');">
<input type="hidden" name="filtro" id="filtro" value="<?=$filtro?>" />
<div style="width:700px" class="divFormCaption">Datos del Registro</div>
<table width="700" class="tblForm">
	<tr>
		<td class="tagForm" width="125">C&oacute;digo:</td>
		<td><input type="text" id="codigo" maxlength="3" style="width:50px;" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td><input type="text" id="descripcion" maxlength="100" style="width:297px;" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Tipo de Transacci&oacute;n:</td>
		<td>
        	<select id="ttransaccion" style="width:123px;">
            	<?=loadSelectValores("TIPO-TRANSACCION", "I", 0);?>
            </select>*
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="checkbox" id="flagvoucher" value="S" /> Generar Voucher</td>
	</tr>
    <tr>
        <td class="tagForm">Cta. Contable:</td>
        <td>
            <input type="text" name="codcuenta" id="codcuenta" size="15" disabled="disabled" />
            <input type="hidden" name="nomcuenta" id="nomcuenta" />
            <input type="button" value="..." onclick="cargarVentana(this.form, 'listado_cuentas_contables.php?cod=codcuenta&nom=nomcuenta', 'height=500, width=900, left=50, top=50, resizable=yes');" />
        </td>
    </tr>
	<tr>
		<td class="tagForm">Tipo de Voucher:</td>
		<td>
        	<select id="voucher" style="width:300px;">
            	<option value=""></option>
            	<?=loadSelect("ac_voucher", "CodVoucher", "Descripcion", "", 0);?>
            </select>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="checkbox" id="flagtransaccion" value="S" /> Transaccion del Sistema</td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
			<input id="activo" name="estado" type="radio" value="A" checked="checked" /> Activo &nbsp;&nbsp;
			<input id="inactivo" name="estado" type="radio" value="I" /> Inactivo
		</td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td>
			<input name="ult_usuario" type="text" id="ult_usuario" size="30" disabled="disabled" />
			<input name="ult_fecha" type="text" id="ult_fecha" size="25" disabled="disabled" />
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
