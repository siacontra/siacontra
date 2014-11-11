<?php
list($NroProceso, $Secuencia, $CodTipoPago) = split("[.]", $registro);
//---------------------------------------------------
//	verifico si muestro tab de retenciones
//	consulto iva
$sql = "SELECT
			r.*,
			op.Concepto AS Comentarios,
			og.Organismo,
			og.DocFiscal AS RifOrganismo,
			og.Direccion AS DirOrganismo,			
			mp.NomCompleto AS NomProveedor,
			mp.DocFiscal AS RifProveedor,
			o.NroControl
		FROM
			ap_pagos p
			INNER JOIN ap_ordenpago op ON (p.CodOrganismo = op.CodOrganismo AND p.NroOrden = op.NroOrden)
			INNER JOIN ap_obligaciones o ON (op.CodProveedor = o.CodProveedor AND
											 op.CodTipoDocumento = o.CodTipoDocumento AND
											 op.NroDocumento = o.NroDocumento)
			INNER JOIN ap_retenciones r ON (op.CodOrganismo = r.CodOrganismo AND op.NroOrden = r.NroOrden)
			INNER JOIN mastorganismos og ON (r.CodOrganismo = og.CodOrganismo)
			INNER JOIN mastpersonas mp ON (r.CodProveedor = mp.CodPersona)
			INNER JOIN mastimpuestos i ON (r.CodImpuesto = i.CodImpuesto)
		WHERE
			p.NroProceso = '".$NroProceso."' AND
			p.Secuencia = '".$Secuencia."' AND
			r.Estado = 'PA' AND
			i.TipoComprobante = 'IVA'";
$query_iva = mysql_query($sql) or die ($sql.mysql_error());
//---------------------------------------------------
//	consulto islr
$sql = "SELECT
			r.*,
			op.Concepto AS Comentarios,
			og.Organismo,
			og.DocFiscal AS RifOrganismo,
			og.Direccion AS DirOrganismo,
			mp.NomCompleto AS NomProveedor,
			mp.DocFiscal AS RifProveedor,
			o.NroControl
		FROM
			ap_pagos p
			INNER JOIN ap_ordenpago op ON (p.CodOrganismo = op.CodOrganismo AND p.NroOrden = op.NroOrden)
			INNER JOIN ap_obligaciones o ON (op.CodProveedor = o.CodProveedor AND
											 op.CodTipoDocumento = o.CodTipoDocumento AND
											 op.NroDocumento = o.NroDocumento)
			INNER JOIN ap_retenciones r ON (op.CodOrganismo = r.CodOrganismo AND op.NroOrden = r.NroOrden)
			INNER JOIN mastorganismos og ON (r.CodOrganismo = og.CodOrganismo)
			INNER JOIN mastpersonas mp ON (r.CodProveedor = mp.CodPersona)
			INNER JOIN mastimpuestos i ON (r.CodImpuesto = i.CodImpuesto)
		WHERE
			p.NroProceso = '".$NroProceso."' AND
			p.Secuencia = '".$Secuencia."' AND
			r.Estado = 'PA' AND
			i.TipoComprobante = 'ISLR'";
$query_islr = mysql_query($sql) or die ($sql.mysql_error());
//---------------------------------------------------
//	consulto 1X1000
$sql = "SELECT
			r.*,
			op.Concepto AS Comentarios,
			og.Organismo,
			og.DocFiscal AS RifOrganismo,
			og.Direccion AS DirOrganismo,			
			mp.NomCompleto AS NomProveedor,
			mp.DocFiscal AS RifProveedor,
			o.NroControl,
			op.NroOrden,
			p.Periodo
		FROM
			ap_pagos p
			INNER JOIN ap_ordenpago op ON (p.CodOrganismo = op.CodOrganismo AND p.NroOrden = op.NroOrden)
			INNER JOIN ap_obligaciones o ON (op.CodProveedor = o.CodProveedor AND
											 op.CodTipoDocumento = o.CodTipoDocumento AND
											 op.NroDocumento = o.NroDocumento)
			INNER JOIN ap_retenciones r ON (op.CodOrganismo = r.CodOrganismo AND op.NroOrden = r.NroOrden)
			INNER JOIN mastorganismos og ON (r.CodOrganismo = og.CodOrganismo)
			INNER JOIN mastpersonas mp ON (r.CodProveedor = mp.CodPersona)
			INNER JOIN mastimpuestos i ON (r.CodImpuesto = i.CodImpuesto)
		WHERE
			p.NroProceso = '".$NroProceso."' AND
			p.Secuencia = '".$Secuencia."' AND
			r.Estado = 'PA' AND
			i.TipoComprobante = '1X1000'";
$query_mil = mysql_query($sql) or die ($sql.mysql_error());
//---------------------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Reportes Generados del Pago</td>
		<td align="right"><a class="cerrar" href="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" method="post" target="iReporte">
<input type="hidden" name="registro" id="registro" value="<?=$registro?>" />
<table width="1000" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" onclick="currentTab('tab', this);">
			<?php
            if ($CodTipoPago == "02") $tab1 = "ap_orden_pago_transferir_sustento_cheques_pdf";
            else $tab1 = "ap_orden_pago_transferir_sustento_nocheques_pdf";
            ?>
            <a href="#" onclick="cargarPagina(document.getElementById('frmentrada'), '<?=$tab1?>.php');">Sustento</a>
            </li>
            <?php
			if ($CodTipoPago == "02") {
				?>
                <li id="li2" onclick="currentTab('tab', this);">
                    <a href="#" onclick="cargarPagina(document.getElementById('frmentrada'), 'ap_orden_pago_transferir_cheque_pdf.php');">Cheque</a>
                </li>
                <?
			}
			if (mysql_num_rows($query_iva) != 0 || mysql_num_rows($query_islr) != 0 || mysql_num_rows($query_mil) != 0) {
				?>
                <li id="li3" onclick="currentTab('tab', this);">
                    <a href="#" onclick="cargarPagina(document.getElementById('frmentrada'), 'ap_orden_pago_transferir_retenciones_pdf.php');">Retenciones</a>
                </li>
                <?
			}
			?>
            </ul>
            </div>
        </td>
    </tr>
</table>
</form>

<center>
<iframe name="iReporte" id="iReporte" style="border-left:solid 1px #CDCDCD; border-right:solid 1px #CDCDCD; border-bottom:solid 1px #CDCDCD; border-top:0; width:1000px; height:600px;" src="<?=$tab1?>.php?registro=<?=$registro?>"></iframe>
</center>