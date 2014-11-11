<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
extract($_GET);
extract($_POST);
//	------------------------------
if ($opcion == "ACTUALIZAR") {
	$titulo = "Modificar Obligaci&oacute;n Aprobada";
	$btLabelSubmit = "Modificar";
}
elseif ($opcion == "ANULAR") {
	$titulo = "Anular Obligaci&oacute;n Aprobada";
	$btLabelSubmit = "Anular";
}
//	------------------------------
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
//	------------------------------
list($codorganismo, $nroorden)=SPLIT( '[|]', $registro);
$sql = "SELECT 
			op.*,
			mp.NomCompleto AS NomProveedor,
			mp.Busqueda,
			mp.DocFiscal,
			p.DiasPago,
			o.MontoAdelanto,
			o.MontoPagoParcial,
			(o.MontoObligacion - o.MontoAdelanto - MontoPagoParcial) AS MontoPendiente
		FROM 
			ap_ordenpago op
			INNER JOIN ap_obligaciones o ON (op.CodProveedor = o.CodProveedor AND
											 op.CodTipoDocumento = o.CodTipoDocumento AND
											 op.NroDocumento = o.NroDocumento)
			INNER JOIN mastpersonas mp ON (op.CodProveedor = mp.CodPersona)
			INNER JOIN ap_tipodocumento td ON (op.CodTipoDocumento = td.CodTipoDocumento)
			LEFT JOIN mastproveedores p ON (mp.CodPersona = p.CodProveedor)
		WHERE 
			op.CodOrganismo = '".$codorganismo."' AND
			op.NroOrden = '".$nroorden."'";
$query_orden = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_orden) != 0) $field_orden = mysql_fetch_array($query_orden);
//	------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="document.getElementById('frmentrada').submit();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="ap_ordenes_pago_listar.php" method="POST" onsubmit="return verificarOrdenPago(this, '<?=$opcion?>');">
<input type="hidden" name="forganismo" id="forganismo" value="<?=$forganismo?>" />
<input type="hidden" name="fcodproveedor" id="fcodproveedor" value="<?=$fcodproveedor?>" />
<input type="hidden" name="fnomproveedor" id="fnomproveedor" value="<?=$fnomproveedor?>" />
<input type="hidden" name="ftdoc" id="ftdoc" value="<?=$ftdoc?>" />
<input type="hidden" name="fsfuente" id="fsfuente" value="<?=$fsfuente?>" />
<input type="hidden" name="fndoc" id="fndoc" value="<?=$fndoc?>" />
<input type="hidden" name="fbanco" id="fbanco" value="<?=$fbanco?>" />
<input type="hidden" name="fprogramadad" id="fprogramadad" value="<?=$fprogramadad?>" />
<input type="hidden" name="fprogramadah" id="fprogramadah" value="<?=$fprogramadah?>" />
<input type="hidden" name="fctabancaria" id="fctabancaria" value="<?=$fctabancaria?>" />
<input type="hidden" name="fprocesod" id="fprocesod" value="<?=$fprocesod?>" />
<input type="hidden" name="fprocesoh" id="fprocesoh" value="<?=$fprocesoh?>" />
<input type="hidden" name="fmontosd" id="fmontosd" value="<?=$fmontosd?>" />
<input type="hidden" name="fmontosh" id="fmontosh" value="<?=$fmontosh?>" />
<input type="hidden" name="fordenar" id="fordenar" value="<?=$fordenar?>" />
<input type="hidden" name="chkflagpago" id="chkflagpago" value="<?=$chkflagpago?>" />
<input type="hidden" name="accion" id="accion" value="<?=$accion?>" />
<input type="hidden" id="nroorden" value="<?=$nroorden?>" />

<div style="width:1100px" class="divFormCaption">Informaci&oacute;n de la Obligaci&oacute;n</div>
<table width="1100" class="tblForm">
	<tr>
		<td class="tagForm" width="150">Organismo:</td>
		<td>
        	<select id="organismo" style="width:340px;" disabled="disabled">
            	<?=loadSelect("mastorganismos", "CodOrganismo", "Organismo", $field_orden['CodOrganismo'], 1)?>
            </select>
		</td>
		<td class="tagForm">Tipo de Documento:</td>
		<td>
        	<select id="tdoc" style="width:300px;" disabled="disabled">
                <?=loadSelect("ap_tipodocumento", "CodTipoDocumento", "Descripcion", $field_orden['CodTipoDocumento'], 0)?>
            </select>
        </td>
	</tr>
	<tr>
		<td class="tagForm">Proveedor:</td>
		<td>
        	<input type="text" id="codproveedor" value="<?=$field_orden['CodProveedor']?>" disabled="disabled" style="width:60px;" />
			<input type="text" id="nomproveedor" value="<?=($field_orden['NomProveedor'])?>" disabled="disabled" style="width:265px;" />
        </td>
        <td class="tagForm">Tipo de Pago:</td>
        <td>
            <select id="tpago" style="width:175px;">
                <?=loadSelect("masttipopago", "CodTipoPago", "TipoPago", $field_orden['CodTipoPago'], 0)?>
            </select>
        </td>
	</tr>
	<tr>
		<td class="tagForm">Cheque a nombre de:</td>
		<td>
        	<input type="hidden" id="codpagara" value="<?=$field_orden['CodProveedorPagar']?>" />
			<input type="text" id="nompagara" value="<?=($field_orden['NomProveedorPagar'])?>" style="width:335px;" />
        </td>
		<td class="tagForm">Cuenta Bancaria:</td>
		<td>
        	<select id="ctabancaria" style="width:175px;" <?=$disabled?>>
                <?=loadSelect("ap_ctabancaria", "NroCuenta", "NroCuenta", $field_orden['NroCuenta'], 0)?>
            </select>
        </td>
	</tr>
    <tr>
		<td class="tagForm">Fecha Documento:</td>
		<td><input type="text" id="fdocumento" value="<?=formatFechaDMA($field_orden['FechaDocumento'])?>" style="width:60px;" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm">F. Vencimiento Original:</td>
		<td><input type="text" id="fvencimientoo" value="<?=formatFechaDMA($field_orden['FechaVencimiento'])?>" style="width:60px;" disabled="disabled" /></td>
		<td class="tagForm"><strong>Total Obligaci&oacute;n:</strong></td>
		<td><input type="text" id="monto_obligacion" value="<?=number_format($field_orden['MontoTotal'], 2, ',', '.')?>" style="width:150px; text-align:right; font-size:12px; font-weight:bold;" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm">F. Vencimiento Real:</td>
		<td><input type="text" id="fvencimientor" value="<?=formatFechaDMA($field_orden['FechaVencimientoReal'])?>" style="width:60px;" /></td>
		<td class="tagForm">Adelanto (-):</td>
		<td><input type="text" id="monto_adelanto" value="<?=number_format($field_orden['MontoAdelanto'], 2, ',', '.')?>" style="width:150px; text-align:right;" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm">&nbsp;</td>
		<td><?=printFlagForm($field_orden['FlagSuspension'], 'flagsuspension', '')?> Suspender el Pago Temporalmente</td>
		<td class="tagForm">Pagos Parciales (-):</td>
		<td><input type="text" id="monto_parcial" value="<?=number_format($field_orden['MontoParcial'], 2, ',', '.')?>" style="width:150px; text-align:right;" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm">&nbsp;</td>
		<td><?=printFlagForm($field_orden['FlagPagoDiferido'], 'flagpagodiferido', '')?> Diferir el Pago</td>
		<td class="tagForm"><strong>Saldo Pendiente:</strong></td>
		<td><input type="text" id="monto_pendiente" value="<?=number_format($field_orden['MontoPendiente'], 2, ',', '.')?>" style="width:150px; text-align:right; font-size:12px; font-weight:bold;" disabled="disabled" /></td>
	</tr>
</table>
<center> 
<input type="submit" value="<?=$btLabelSubmit?>" />
<input type="button" value="Cancelar" onclick="document.getElementById('frmentrada').submit();" />
</center>
<div style="width:1100px" class="divMsj">Campos Obligatorios *</div>
</form>
</body>
</html>
