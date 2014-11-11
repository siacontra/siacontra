<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
extract($_GET);
extract($_POST);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_ap.js"></script>
<style type="text/css">
<!--
UNKNOWN {
        FONT-SIZE: small
}
#header {
        FONT-SIZE: 93%; BACKGROUND: url(bg.gif) #dae0d2 repeat-x 50% bottom; FLOAT: left; WIDTH: 100%; LINE-HEIGHT: normal
}
#header UL {
        PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 10px; LIST-STYLE-TYPE: none
}
#header LI {
        PADDING-RIGHT: 0px; PADDING-LEFT: 9px; BACKGROUND: url(left.gif) no-repeat left top; FLOAT: left; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 0px
}
#header A {
        PADDING-RIGHT: 15px; DISPLAY: block; PADDING-LEFT: 6px; FONT-WEIGHT: bold; BACKGROUND: url(right.gif) no-repeat right top; FLOAT: left; PADDING-BOTTOM: 4px; COLOR: #765; PADDING-TOP: 5px; TEXT-DECORATION: none
}
#header A {
        FLOAT: none
}
#header A:hover {
        COLOR: #333
}
#header #current {
        BACKGROUND-IMAGE: url(left_on.gif)
}
#header #current A {
        BACKGROUND-IMAGE: url(right_on.gif); PADDING-BOTTOM: 5px; COLOR: #333
}
-->
</style>
</head>

<body>
<?php
include("fphp_ap.php");
connect();
//	------------------------------
list($codproveedor, $nroproceso, $codtipopago, $nrocuenta, $Secuencia) = split("[|]", $registro);
$sql = "SELECT
			p.NroProceso,
			p.CodTipoPago,
			p.NroCuenta,
			p.FechaPago,
			SUM(p.MontoPago) AS MontoPago,
			tp.TipoPago,
			cb.CodCuenta,
			cb.Descripcion AS NomCuentaBanco,
			pc.Descripcion AS NomCuentaContable,
			cbb.SaldoActual
		FROM
			ap_pagos p
			INNER JOIN masttipopago tp ON (p.CodTipoPago = tp.CodTipoPago)
			INNER JOIN ap_ctabancaria cb ON (p.NroCuenta = cb.NroCuenta)
			INNER JOIN ap_ctabancariabalance cbb ON (cb.NroCuenta = cbb.NroCuenta)
			INNER JOIN ac_mastplancuenta pc ON (cb.CodCuenta = pc.CodCuenta)
		WHERE
			p.CodProveedor = '".$codproveedor."' AND
			p.NroProceso = '".$nroproceso."' AND
			p.CodTipoPago = '".$codtipopago."' AND
			p.NroCuenta = '".$nrocuenta."'
		GROUP BY NroProceso";
$query = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
//	------------------------------
if ($field['MontoPago'] > $field['SaldoActual']) {
	$btSubmit = "display:none;";
} else {
	$divMsj = "display:none;";
}
//	------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Impresi&oacute;n/Transferencia de Pagos</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="document.getElementById('frmentrada').submit();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="ap_pagos_imprimir_transferir.php" method="POST" onsubmit="return verificarImprimirTransferir(this);">
<input type="hidden" name="forganismo" id="forganismo" value="<?=$forganismo?>" />
<input type="hidden" name="fperiodo" id="fperiodo" value="<?=$fperiodo?>" />
<input type="hidden" name="codproveedor" id="codproveedor" value="<?=$codproveedor?>" />
<input type="hidden" name="Secuencia" id="Secuencia" value="<?=$Secuencia?>" />

<center><div style="background-color: #FFFFB0; color: #BB0000; width: 900px; height: 25px; border: 1px solid #BB0000; text-align: center; vertical-align:middle; font-size:14px; font-weight:bold; <?=$divMsj?>">
	No hay disponibilidad financiera en Banco para Imprimir el Pre-Pago
</div></center>
<div style="width:900px" class="divFormCaption">Informaci&oacute;n del Pre-Pago</div>
<table width="900" class="tblForm">
	<tr>
		<td class="tagForm" width="150">Fecha de Pago:</td>
		<td><input type="text" id="fpago" style="width:75px;" value="<?=formatFechaDMA($field['FechaPago'])?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm">Nro. Pre-Pago:</td>
		<td><input type="text" id="nroproceso" style="width:75px;" value="<?=$nroproceso?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm">Tipo de Pago:</td>
		<td>
        	<input type="hidden" id="codtipopago" value="<?=$codtipopago?>" />
        	<input type="text" style="width:150px;" value="<?=($field['TipoPago'])?>" disabled="disabled" /> 
            Monto a Pagar: 
            <input type="text" id="total" style="width:100px; font-weight:bold; text-align:right;" value="<?=number_format($field['MontoPago'], 2, ',', '.')?>" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Cta. Bancaria:</td>
		<td>
        	<input type="text" id="nrocuenta" style="width:150px;" value="<?=$nrocuenta?>" disabled="disabled" />
        	<input type="text" style="width:350px;" value="<?=($field['NomCuentaBanco'])?>" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Cta. Contable:</td>
		<td>
        	<input type="text" id="codcuenta" style="width:150px;" value="<?=$field['CodCuenta']?>" disabled="disabled" />
        	<input type="text" style="width:350px;" value="<?=($field['NomCuentaContable'])?>" disabled="disabled" />
		</td>
	</tr>
</table>
<center> 
<input type="submit" value="Confirmar el Proceso" style=" <?=$btSubmit?>" />
<input type="button" value="Cancelar el Proceso" onclick="document.getElementById('frmentrada').submit();" />
</center>
</form><br />

<div style="width:900px" class="divFormCaption">Documentos a Pagar del Pre-Pago</div>
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:900px; height:150px;">
<table width="100%" class="tblLista">
	<tr class="trListaHead">
		<th scope="col" width="25">#</th>
		<th scope="col">Pagar A</th>
		<th scope="col" width="125">Doc. Fiscal</th>
		<th scope="col" width="150">Nro. Documento</th>
		<th scope="col" width="75">Fecha</th>
		<th scope="col" width="75"># Registro</th>
		<th scope="col" width="125">Neto a Pagar</th>
	</tr>
    
    <tbody id="trDetalle">
    <?php
	$sql = "SELECT
				p.Secuencia,
				p.NomProveedorPagar,
				p.MontoPago,
				p.FechaPago,
				mp.DocFiscal,
				op.CodTipoDocumento,
				op.NroDocumento,
				op.NroRegistro
			FROM
				ap_pagos p
				INNER JOIN ap_ordenpago op ON (p.CodOrganismo = op.CodOrganismo AND
											   p.NroOrden = op.NroOrden)
				INNER JOIN mastpersonas mp ON (p.CodProveedor = mp.CodPersona)
			WHERE
				p.CodProveedor = '".$codproveedor."' AND
				p.NroProceso = '".$nroproceso."' AND
				p.CodTipoPago = '".$codtipopago."' AND
				p.NroCuenta = '".$nrocuenta."'
			ORDER BY Secuencia";
	$query_detalles = mysql_query($sql) or die ($sql.mysql_error());
	while ($field_detalles = mysql_fetch_array($query_detalles)) {
		?>
		<tr class="trListaBody">
			<td align="center"><?=$field_detalles['Secuencia']?></td>
			<td><?=($field_detalles['NomProveedorPagar'])?></td>
			<td><?=$field_detalles['DocFiscal']?></td>
			<td align="center"><?=$field_detalles['CodTipoDocumento']?>-<?=$field_detalles['NroDocumento']?></td>
			<td align="center"><?=formatFechaDMA($field_detalles['FechaPago'])?></td>
			<td align="center"><?=$field_detalles['NroRegistro']?></td>
			<td align="right"><strong><?=number_format($field_detalles['MontoPago'], 2, ',', '.')?></strong></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div></td></tr></table>

</body>
</html>
