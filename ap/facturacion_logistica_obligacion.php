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
//	-------------------------------------------
$dias_entrega = getParametro("DIAENTOC");
$fvencimiento = getFechaFinContinuo(date("d-m-Y"), $dias_entrega);
//	-------------------------------------------
$monto_afecto = 0;
$monto_noafecto = 0;
$monto_impuesto = 0;
$monto_total = 0;
$documentos = split(";", $detalles);
foreach ($documentos as $documento) {	
	list($anio, $codorganismo, $codproveedor, $codclasificacion, $codreferencia)=SPLIT( '[ ]', $documento);
	$sql = "SELECT
				d.MontoAfecto,
				d.MontoNoAfecto,
				d.MontoImpuestos,
				d.MontoTotal,
				d.Comentarios,
				d.ReferenciaTipoDocumento,
				d.ReferenciaNroDocumento,
				d.DocumentoReferencia,
				d.TransaccionNroDocumento,
				dd.CodCentroCosto
			FROM
				ap_documentos d
				INNER JOIN ap_documentosdetalle dd ON (d.Anio = dd.Anio AND
													   d.CodProveedor = dd.CodProveedor AND
													   d.DocumentoClasificacion = dd.DocumentoClasificacion AND
													   d.DocumentoReferencia = dd.DocumentoReferencia)
			WHERE
				d.Anio = '".$anio."' AND
				d.CodOrganismo = '".$codorganismo."' AND
				d.CodProveedor = '".$codproveedor."' AND
				d.DocumentoClasificacion = '".$codclasificacion."' AND
				d.DocumentoReferencia = '".$codreferencia."'
			GROUP BY d.Anio, d.CodProveedor, d.DocumentoClasificacion, d.DocumentoReferencia";
	$query_documento = mysql_query($sql) or die ($sql.mysql_error());
	while($field_documento = mysql_fetch_array($query_documento)) {
		$monto_afecto += $field_documento['MontoAfecto'];
		$monto_noafecto += $field_documento['MontoNoAfecto'];
		if ($glosa == "") {
			$nrodoc = $field_documento['DocumentoReferencia'];
			$codccosto = $field_documento['CodCentroCosto'];
			$glosa = $field_documento['Comentarios']." / ".$field_documento['ReferenciaTipoDocumento']."-".$field_documento['ReferenciaNroDocumento'];
			$comentarios = $field_documento['Comentarios'];
			$nrofactura = $field_documento['TransaccionNroDocumento'];
		}
	}
}
//	-------------------------------------------
$sql = "SELECT
			p.CodFormaPago,
			p.CodTipoPago,
			p.CodTipoDocumento,
			mp.NomCompleto AS NomProveedor,
			p.CodTipoServicio,
			p.CodTipoDocumento,
			i.FactorPorcentaje,
			i.Descripcion AS NomImpuesto
		FROM
			mastproveedores p
			INNER JOIN mastpersonas mp ON (p.CodProveedor = mp.CodPersona)
			INNER JOIN masttiposervicioimpuesto tsi ON (p.CodTipoServicio = tsi.CodTipoServicio)
			INNER JOIN mastimpuestos i ON (tsi.CodImpuesto = i.CodImpuesto)
		WHERE p.CodProveedor = '".$proveedor."'";
$query_proveedor = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_proveedor) != 0) $field_proveedor = mysql_fetch_array($query_proveedor);
//	-------------------------------------------
$sql = "SELECT Descripcion, Abreviatura FROM ac_mastcentrocosto WHERE CodCentroCosto = '".$codccosto."'";
$query_cc = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_cc) != 0) $field_cc = mysql_fetch_array($query_cc);
//	-------------------------------------------
$monto_impuesto = $monto_afecto * $field_proveedor['FactorPorcentaje'] / 100;
$monto_total = $monto_afecto + $monto_noafecto + $monto_impuesto;
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Obligaci&oacute;n</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="facturacion_logistica.php" method="POST" onsubmit="return prepararFactura(this);" target="main">
<input type="hidden" id="detalles" value="<?=$detalles?>" />
<input type="hidden" id="nrofactura" value="<?=$nrofactura?>" />
<div style="width:700px" class="divFormCaption">Datos del Registro</div>
<table width="700" class="tblForm">
	<tr>
		<td class="tagForm" width="125">Proveedor:</td>
		<td>
        	<input type="hidden" id="codorganismo" value="<?=$organismo?>" />
        	<input type="hidden" id="codproveedor" value="<?=$proveedor?>" />
        	<input type="hidden" id="clasificacion" value="<?=$clasificacion?>" />
        	<input type="hidden" id="tpago" value="<?=$field_proveedor['CodTipoPago']?>" />
        	<input type="text" value="<?=($field_proveedor['NomProveedor'])?>" style="width:250px;" disabled="disabled" />
		</td>
		<td class="tagForm">Tipo de Servicio:</td>
		<td>
        	<input type="hidden" id="porcentaje" value="<?=$field_proveedor['FactorPorcentaje']?>" />
        	<input type="hidden" id="tservicio" value="<?=$field_proveedor['CodTipoServicio']?>" />
        	<input type="text" style="width:100px;" value="<?=$field_proveedor['NomImpuesto']?>" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Tipo de Documento:</td>
		<td colspan="3">
        	<select id="tdoc" style="width:250px;">
                <?=loadSelect("ap_tipodocumento", "CodTipoDocumento", "Descripcion", $field_proveedor['CodTipoDocumento'], 0)?>
            </select>*
        </td>
	</tr>
	<tr>
		<td class="tagForm">Nro. de Documento:</td>
		<td><input type="text" id="nrodoc" maxlength="20" style="width:150px;" />*</td>
		<td class="tagForm">Centro de Costos:</td>
		<td>
        	<input type="hidden" name="codccosto" id="codccosto" value="<?=$codccosto?>" />
        	<input type="text" name="nomccosto" id="nomccosto" value="<?=$field_cc['Abreviatura']?>" size="10" disabled="disabled" />
			<input type="button" value="..." id="btCCosto" onclick="window.open('listado_centro_costos.php?cod=codccosto&nom=nomccosto', 'wLista', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=800, width=800, left=200, top=200, resizable=yes');" />*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Fecha Emisi&oacute;n:</td>
		<td><input type="text" id="femision" value="<?=date("d-m-Y")?>" maxlength="10" style="width:75px;" />*</td>
		<td class="tagForm">Monto Afecto:</td>
		<td><input type="text" id="monto_afecto" style="width:100px; text-align:right;" value="<?=number_format($monto_afecto, 2, ',', '.')?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm">Fecha Recepci&oacute;n:</td>
		<td><input type="text" id="frecepcion" value="<?=date("d-m-Y")?>" maxlength="10" style="width:75px;" />*</td>
		<td class="tagForm">Monto No Afecto:</td>
		<td><input type="text" id="monto_noafecto" style="width:100px; text-align:right;" value="<?=number_format($monto_noafecto, 2, ',', '.')?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm">Fecha Vencimiento:</td>
		<td><input type="text" id="fvencimiento" value="<?=$fvencimiento?>" maxlength="10" style="width:75px;" />*</td>
		<td class="tagForm">(+/-) Impuestos:</td>
		<td><input type="text" id="monto_impuesto" style="width:100px; text-align:right;" value="<?=number_format($monto_impuesto, 2, ',', '.')?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm">Fecha Prog. Pago:</td>
		<td><input type="text" id="fpago" value="<?=date("d-m-Y")?>" maxlength="10" style="width:75px;" />*</td>
		<td class="tagForm">Total Obligaci&oacute;n:</td>
		<td><input type="text" id="monto_total" style="width:100px; text-align:right; font-size:12px; font-weight:bold;" value="<?=number_format($monto_total, 2, ',', '.')?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm">Nro. Registro:</td>
		<td colspan="3"><input type="text" id="nroregistro" style="width:75px;" disabled="disabled"  />*</td>
	</tr>
	<tr>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td colspan="3"><input type="checkbox" id="flagdiferir" value="S" /> Diferir el Pago</td>
	</tr>
	<tr>
		<td class="tagForm">Glosa del Voucher:</td>
		<td colspan="3">
        	<textarea id="glosavoucher" style="width:95%; height:75px;"><?=($glosa)?></textarea>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Commentarios Adicional:</td>
		<td colspan="3">
        	<textarea id="comentarios" style="width:95%; height:75px;"><?=($comentarios)?></textarea>
		</td>
	</tr>
</table>
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onClick="javascript:window.close();" />
</center>
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</form>

</body>
</html>
