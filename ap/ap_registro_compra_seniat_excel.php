<?php
extract($_POST);
extract($_GET);
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=$nombre_archivo.xls");
header("Pragma: no-cache");
header("Expires: 0");
//---------------------------------------------------
include("../lib/fphp.php");
include("lib/fphp.php");
include("../lib/css_excel.php");
//---------------------------------------------------
if ($fCodOrganismo != "") $filtro .=" AND (r.CodOrganismo = '".$fCodOrganismo."')";
if ($fPeriodod != "" || $fPeriodoh != "") {
	if ($fPeriodod != "") $filtro .=" AND (r.PeriodoFiscal >= '".$fPeriodod."')";
	if ($fPeriodoh != "") $filtro .=" AND (r.PeriodoFiscal <= '".$fPeriodoh."')";
}
if ($fCodProveedor != "") $filtro .=" AND (r.CodProveedor = '".$fCodProveedor."')";
if ($fFechaComprobanted != "" || $fFechaComprobanteh != "") {
	if ($fFechaComprobanted != "") $filtro .=" AND (r.FechaComprobante >= '".formatFechaAMD($fFechaComprobanted)."')";
	if ($fFechaComprobanteh != "") $filtro .=" AND (r.FechaComprobante <= '".formatFechaAMD($fFechaComprobanteh)."')";
}
//---------------------------------------------------
?>
<!--	IMPRIMO TITULOS		-->
<table>
	<tr>
    	<th class="thead" width="115">Comprobante</th>
    	<th class="thead" width="350">Nombre o Raz&oacute;n Social</th>
    	<th class="thead" width="60">Periodo</th>
    	<th class="thead" width="75">Fecha</th>
    	<th class="thead" width="115">Nro. Control</th>
    	<th class="thead" width="115">Nro. Factura</th>
    	<th class="thead" width="75">Monto Imponible</th>
    	<th class="thead" width="75">Monto Impuesto</th>
    	<th class="thead" width="75">Monto Factura</th>
    	<th class="thead" width="50">% IVA</th>
    	<th class="thead" width="75">Monto Retenido</th>
    </tr>

<!--	IMPRIMO CUERPO		-->
<?php
$sql = "SELECT
			CONCAT(SUBSTRING(PeriodoFiscal, 1, 4), SUBSTRING(PeriodoFiscal, 6, 2), NroComprobante) AS NroComprobante,
			p.NomCompleto AS NomProveedor,
			r.PeriodoFiscal,
			r.FechaComprobante,
			r.NroDocumento,
			r.NroControl,
			r.FechaFactura,
			r.MontoAfecto,
			r.MontoImpuesto,
			r.MontoFactura,
			i.FactorPorcentaje AS PorcentajeIVA,
			ABS(r.MontoRetenido) AS MontoRetenido
		FROM
			ap_retenciones r

			INNER JOIN mastorganismos o ON (o.CodOrganismo = r.CodOrganismo)

			INNER JOIN mastpersonas p ON (p.CodPersona = r.CodProveedor)

			JOIN ap_obligaciones ob ON (ob.CodProveedor = r.CodProveedor AND

											  ob.CodTipoDocumento = r.CodTipoDocumento AND

											  ob.NroDocumento = r.NroDocumento)

			

			JOIN ap_obligacionesimpuesto as obi ON (obi.CodProveedor = r.CodProveedor AND

											  obi.CodTipoDocumento = r.CodTipoDocumento AND

											  obi.NroDocumento = r.NroDocumento )



			JOIN masttiposervicio ts ON (ts.CodTipoServicio = ob.CodTipoServicio)

			JOIN masttiposervicioimpuesto tsi ON (tsi.CodTipoServicio = ts.CodTipoServicio and tsi.CodTipoServicio=ob.CodTipoServicio AND tsi.CodImpuesto = obi.CodImpuesto)

			JOIN mastimpuestos i 

		WHERE

            i.CodImpuesto=r.CodImpuesto AND

			r.TipoComprobante = 'IVA' AND

			r.Estado = 'PA'  

			$filtro


		ORDER BY FechaComprobante, NroComprobante";
/*
ap_retenciones r
			INNER JOIN mastorganismos o ON (o.CodOrganismo = r.CodOrganismo)
			INNER JOIN mastpersonas p ON (p.CodPersona = r.CodProveedor)
			LEFT JOIN ap_obligaciones ob ON (ob.CodProveedor = r.CodProveedor AND
											  ob.CodTipoDocumento = r.CodTipoDocumento AND
											  ob.NroDocumento = r.NroDocumento)
			LEFT JOIN masttiposervicio ts ON (ts.CodTipoServicio = ob.CodTipoServicio)
			LEFT JOIN masttiposervicioimpuesto tsi ON (tsi.CodTipoServicio = ts.CodTipoServicio)
			LEFT JOIN mastimpuestos i ON (i.CodImpuesto = tsi.CodImpuesto AND i.CodRegimenFiscal = 'I')
		WHERE
			r.TipoComprobante = 'IVA' AND
			r.Estado = 'PA'
			$filtro
		ORDER BY FechaComprobante";*/
$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
while ($field = mysql_fetch_array($query)) {
	$MontoRetenido += $field['MontoRetenido'];
	?>
    <tr>
        <td class="tbody" align="center">=CONCATENAR(<?=$field['NroComprobante']?>)</th>
        <td class="tbody" align="left"><?=$field['NomProveedor']?></th>
        <td class="tbody" align="center"><?=$field['PeriodoFiscal']?></th>
        <td class="tbody" align="center"><?=$field['FechaComprobante']?></th>
        <td class="tbody" align="left"><?=$field['NroDocumento']?></th>
        <td class="tbody" align="left"><?=$field['NroControl']?></th>
        <td class="tbody" align="right">=DECIMAL(<?=number_format($field['MontoAfecto'], 2, ',', '')?>; 2)</th>
        <td class="tbody" align="right">=DECIMAL(<?=number_format($field['MontoImpuesto'], 2, ',', '')?>; 2)</th>
        <td class="tbody" align="right">=DECIMAL(<?=number_format($field['MontoFactura'], 2, ',', '')?>; 2)</th>
        <td class="tbody" align="right">=DECIMAL(<?=number_format($field['PorcentajeIVA'], 2, ',', '')?>; 2)</th>
        <td class="tbody" align="right">=DECIMAL(<?=number_format($field['MontoRetenido'], 2, ',', '')?>; 2)</th>
    </tr>
    <?
}
?>
<tr>
    <td class="tbody" align="right"></th>
    <td class="tbody" align="right"></th>
    <td class="tbody" align="right"></th>
    <td class="tbody" align="right"></th>
    <td class="tbody" align="right"></th>
    <td class="tbody" align="right"></th>
    <td class="tbody" align="right"></th>
    <td class="tbody" align="right"></th>
    <td class="tbody" align="right"></th>
    <td class="tbody" align="right">TOTAL:</th>
    <td class="tbody" align="right">=DECIMAL(<?=number_format($MontoRetenido, 2, ',', '')?>; 2)</th>
</tr>
</table>
