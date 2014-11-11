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
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Asignaci&oacute;n de Cuentas Bancarias por Defecto | Nuevo Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'ap_cuentas_bancarias_default.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="ap_cuentas_bancarias_default.php" method="POST" onsubmit="return verificarCuentaBancariaDefault(this, 'INSERTAR');">
<input type="hidden" name="filtro" id="filtro" value="<?=$filtro?>" />
<input type="hidden" name="registro" id="registro" />
<div style="width:600px" class="divFormCaption">Datos de la Cuenta</div>
<table width="600" class="tblForm">
	<tr>
		<td class="tagForm" width="125">Organismo:</td>
		<td>
        	<select id="organismo" style="width:300px;">
            	<?=getOrganismos("", 0);?>
            </select>*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Tipo de Pago:</td>
		<td>
        	<select id="tpago" style="width:300px;">
            	<option value=""></option>
            	<?=loadSelect("masttipopago", "CodTipoPago", "TipoPago", "", 0);?>
            </select>*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Cuenta Bancaria:</td>
		<td>
        	<select id="nrocuenta" style="width:300px;">
            	<option value=""></option>
            	<?=loadSelect("ap_ctabancaria", "NroCuenta", "NroCuenta", "", 0);?>
            </select>*
		</td>
	</tr>
</table>
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'ap_cuentas_bancarias_default.php');" />
</center>
<div style="width:600px" class="divMsj">Campos Obligatorios *</div>
</form>
</body>
</html>
