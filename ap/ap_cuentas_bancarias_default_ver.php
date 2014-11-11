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
list($organismo, $nrocuenta, $tpago)=SPLIT('[ ]', $registro);
//	------------------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Asignaci&oacute;n de Cuentas Bancarias por Defecto | Ver Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<div style="width:600px" class="divFormCaption">Datos de la Cuenta</div>
<table width="600" class="tblForm">
	<tr>
		<td class="tagForm" width="125">Organismo:</td>
		<td>
        	<select id="organismo" style="width:300px;" disabled="disabled">
            	<?=getOrganismos($organismo, 1);?>
            </select>*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Tipo de Pago:</td>
		<td>
        	<select id="tpago" style="width:300px;" disabled="disabled">
            	<?=loadSelect("masttipopago", "CodTipoPago", "TipoPago", $tpago, 1);?>
            </select>*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Cuenta Bancaria:</td>
		<td>
        	<select id="nrocuenta" style="width:300px;" disabled="disabled">
            	<?=loadSelect("ap_ctabancaria", "NroCuenta", "NroCuenta", $nrocuenta, 1);?>
            </select>*
		</td>
	</tr>
</table>
</body>
</html>
