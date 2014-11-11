<?php
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("lib/fphp.php");
//---------------------------------------------------
$imprimir_titulos = true;
if ($fCodOrganismo != "") $filtro .=" AND (rc.CodOrganismo = '".$fCodOrganismo."')";
if ($fPeriodod != "" || $fPeriodoh != "") {
	if ($fPeriodod != "") $filtro .=" AND (rc.Periodo >= '".$fPeriodod."')";
	if ($fPeriodoh != "") $filtro .=" AND (rc.Periodo <= '".$fPeriodoh."')";
}
if ($fCodProveedor != "") $filtro .=" AND (rc.CodProveedor = '".$fCodProveedor."')";
//---------------------------------------------------

class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
		global $_PARAMETRO;
		global $Ahora;
		global $_POST;
		global $field;
		global $nombre_periodo;
		global $imprimir_titulos;
		extract($_POST);
		##	membrete (logo)
		$Logo = getValorCampo("mastorganismos", "CodOrganismo", "Logo", $fCodOrganismo);
		$NomOrganismo = getValorCampo("mastorganismos", "CodOrganismo", "Organismo", $fCodOrganismo);
		$NomDependencia = getValorCampo("mastdependencias", "CodDependencia", "Dependencia", $_PARAMETRO["DEPLOGCXP"]);
		##	membrete (titulo)
		$this->SetFillColor(255, 255, 255);
		$this->SetDrawColor(0, 0, 0);
		$this->Image($_PARAMETRO["PATHLOGO"].$Logo, 10, 5, 10, 10);		
		$this->SetFont('Arial', '', 8);
		$this->SetXY(20, 5); $this->Cell(100, 5, $NomOrganismo, 0, 1, 'L');
		$this->SetXY(20, 10); $this->Cell(100, 5, $NomDependencia, 0, 0, 'L');	
		##	fecha, pagina
		$this->SetFont('Arial', '', 8);
		$this->SetXY(315, 5); $this->Cell(15, 5, utf8_decode('Fecha: '), 0, 0, 'L');
		$this->Cell(30, 5, formatFechaDMA(substr($Ahora, 0, 10)), 0, 1, 'L');
		$this->SetXY(315, 10); $this->Cell(15, 5, utf8_decode('Página: '), 0, 0, 'L'); 
		$this->Cell(30, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		##	titulo
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(347, 5, utf8_decode('LIBRO DE COMPRAS '.strtoupper($nombre_periodo)), 0, 0, 'C');
		##
		$this->SetTextColor(0, 0, 0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		##
		$this->Ln(5);
		##	imprimir titulos
		if ($imprimir_titulos) {
			$this->SetFillColor(255, 255, 255);
			$this->SetDrawColor(0, 0, 0);
			$this->SetFont('Arial', 'B', 5);
			$this->Cell(277, 5, '', 0, 0, 'C');
			$this->Cell(45, 5, 'IMPORTACIONES E IMPORTACIONES', 1, 1, 'C');
			$this->SetFont('Arial', 'B', 5);
			$this->SetWidths(array(8, 14, 18, 55, 10, 14, 14, 14, 24, 24, 14, 14, 10, 14, 15, 15, 15, 15, 15, 15, 15, 15));
			$this->SetAligns(array('R', 'C', 'L', 'L', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));
			$this->Row(array('# Op.',
							 'F.Factura',
							 'RIF',
							 utf8_decode('Nombre o Razón Social'),
							 'Tipo Prov.',
							 'Nro. Comprobante',
							 'Nro. Planilla Imp.',
							 'Nro. Expediente',
							 'Nro. Factura',
							 'Nro. Control',
							 utf8_decode('Nro. Nota Crédito'),
							 utf8_decode('Nro. Nota Débito'),
							 'Tipo. Trans.',
							 'Nro. Factura Afectada',
							 'Total Compras + IVA',
							 utf8_decode('Compras sin derecho a Crédito IVA'),
							 'Base Imponible',
							 '% Alicuota',
							 'Impuesto IVA',
							 'IVA Retenido al Vendedor',
							 'IVA Retenido a Terceros',
							 utf8_decode('Anticipo de IVA (Importación)')));
		}
		$this->Ln(1);	
	}
	
	//	Pie de página.
	function Footer() {
	}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('L', 'mm', array(215.9, 375.6));
$pdf->AliasNbPages();
$pdf->SetMargins(5, 5, 5);
$pdf->SetAutoPageBreak(5, 5);
//	$pdf->AddPage();
//---------------------------------------------------
$i = 0;
//	consulto
$sql = "(SELECT
			rc.*,
			p.Nacionalidad,
			td.CodFiscal AS TipoTransaccion,
			i.FactorPorcentaje,
			i.FlagGeneral
		 FROM
			ap_registrocompras rc
			INNER JOIN mastproveedores p ON (rc.CodProveedor = p.CodProveedor)
			INNER JOIN ap_tipodocumento td ON (rc.CodTipoDocumento = td.CodTipoDocumento)
			INNER JOIN ap_obligaciones o ON (rc.CodProveedor = o.CodProveedor AND
											 rc.CodTipoDocumento = o.CodTipoDocumento AND
											 rc.NroDocumento = o.NroDocumento)
			INNER JOIN masttiposervicioimpuesto tsi ON (o.CodTipoServicio = tsi.CodTipoServicio)
			INNER JOIN mastimpuestos i ON (tsi.CodImpuesto = i.CodImpuesto AND i.CodRegimenFiscal = 'I')
		 WHERE
		 	rc.EstadoDocumento = 'IN' AND
			rc.FlagCajaChica <> 'C'
			$filtro)
		UNION
		(SELECT
			rc.*,
			p.Nacionalidad,
			td.CodFiscal AS TipoTransaccion,
			i.FactorPorcentaje,
			i.FlagGeneral
		 FROM
			ap_registrocompras rc
			INNER JOIN mastproveedores p ON (rc.CodProveedor = p.CodProveedor)
			INNER JOIN ap_tipodocumento td ON (rc.CodTipoDocumento = td.CodTipoDocumento)
			INNER JOIN ap_cajachicadetalle ccd ON (rc.CodProveedor = ccd.CodProveedor AND
												   rc.CodTipoDocumento = ccd.CodTipoDocumento AND
												   rc.NroDocumento = ccd.NroDocumento)
			INNER JOIN masttiposervicioimpuesto tsi ON (ccd.CodTipoServicio = tsi.CodTipoServicio)
			INNER JOIN mastimpuestos i ON (tsi.CodImpuesto = i.CodImpuesto AND i.CodRegimenFiscal = 'I')
		 WHERE
		 	rc.EstadoDocumento = 'IN' AND
			rc.FlagCajaChica = 'C'
			$filtro)
		ORDER BY Periodo, SistemaFuente, Secuencia";
$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query) == 0) $pdf->AddPage();
while ($field = mysql_fetch_array($query)) {
	##	cambio de periodo
	if ($grupo != $field['Periodo']) {
		$grupo = $field['Periodo'];
		##	imprimo suma del periodo
		if ($i > 0) {
			$pdf->SetDrawColor(0, 0, 0);
			$pdf->SetFillColor(0, 0, 0);
			$pdf->Rect(5, $pdf->GetY(), 367, 0.1, 'DF');
			$pdf->Ln(1);
			$pdf->SetDrawColor(255, 255, 255);
			$pdf->SetFillColor(255, 255, 255);
			$pdf->SetFont('Arial', 'B', 6);
			$pdf->Row(array('',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							number_format($total_obligacion, 2, ',', '.'),
							number_format($total_noafecto, 2, ',', '.'),
							number_format($total_afecto, 2, ',', '.'),
							'',
							number_format($total_igv, 2, ',', '.'),
							'',
							'',
							''));
		}
		##	imprimo periodo
		list($anio, $mes) = split("[/.-]", $grupo);
		$nombre_periodo = getNombreMes($grupo)." $anio";
		$pdf->AddPage();
		##	inicializo sumadores
		$total_obligacion = 0;
		$total_noafecto = 0;
		$total_afecto = 0;
		$total_igv = 0;
		$i = 0;
	}
	##	imprimo linea
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 6);
	$pdf->Row(array(++$i,
					formatFechaDMA($field['FechaDocumento']),
					$field['RifProveedor'],
					utf8_decode($field['NomProveedor']),
					substr(printValoresGeneral("NACIONALIDAD", $field['Nacionalidad']), 0, 3),
					'',
					'',
					'',
					$field['NroDocumento'],
					$field['NroDocumentoInterno'],
					'',
					'',
					$field['TipoTransaccion'],
					'',
					number_format($field['FiscalObligacion'], 2, ',', '.'),
					number_format($field['FiscalNoAfecto'], 2, ',', '.'),
					number_format($field['ImponibleGravado'], 2, ',', '.'),
					number_format($field['FactorPorcentaje'], 2, ',', '.'),
					number_format($field['IGVGravado'], 2, ',', '.'),
					'',
					'',
					''));
	##	contadores/acumuladores
	$total_obligacion += $field['FiscalObligacion'];
	$total_noafecto += $field['FiscalNoAfecto'];
	$total_afecto += $field['ImponibleGravado'];
	$total_igv += $field['IGVGravado'];
	if ($field['Nacionalidad'] == "E") {
		if ($field['FlagGeneral'] == "G") {
			$i_total_alicuota_general += $field['ImponibleGravado'];
			$i_total_alicuota_general_igv += $field['IGVGravado'];
		}
		elseif ($field['FlagGeneral'] == "R") {
			$i_total_alicuota_reducido += $field['ImponibleGravado'];
			$i_total_alicuota_reducido_igv += $field['IGVGravado'];
		}
		elseif ($field['FlagGeneral'] == "A") {
			$i_total_alicuota_adicional += $field['ImponibleGravado'];
			$i_total_alicuota_adicional_igv += $field['IGVGravado'];
		}
	} else {
		if ($field['FlagGeneral'] == "G") {
			$n_total_alicuota_general += $field['ImponibleGravado'];
			$n_total_alicuota_general_igv += $field['IGVGravado'];
		}
		elseif ($field['FlagGeneral'] == "R") {
			$n_total_alicuota_reducido += $field['ImponibleGravado'];
			$n_total_alicuota_reducido_igv += $field['IGVGravado'];
		}
		elseif ($field['FlagGeneral'] == "A") {
			$n_total_alicuota_adicional += $field['ImponibleGravado'];
			$n_total_alicuota_adicional_igv += $field['IGVGravado'];
		}
	}
	$total_general_imponible += $field['ImponibleGravado'];
	$total_general_igv += $field['IGVGravado'];
}
##	imprimo suma del periodo
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(0, 0, 0);
$pdf->Rect(5, $pdf->GetY(), 367, 0.1, 'DF');
$pdf->Ln(1);
$pdf->SetDrawColor(255, 255, 255);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Row(array('',
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				number_format($total_obligacion, 2, ',', '.'),
				number_format($total_noafecto, 2, ',', '.'),
				number_format($total_afecto, 2, ',', '.'),
				'',
				number_format($total_igv, 2, ',', '.'),
				'',
				'',
				''));
##	imprimo totales del periodo
$y = $pdf->GetY();
if ($y > 160) {
	$imprimir_titulos = false;
	$pdf->AddPage();
} else $pdf->SetY(160);
//	-
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetX(105);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(35, 5, utf8_decode('BASE IMPONIBLE'), 1, 0, 'R');
$pdf->Cell(35, 5, utf8_decode('CRÉDITO FISCAL'), 1, 0, 'R');
$pdf->Cell(35, 5, utf8_decode('IVA RETENIDO'), 1, 0, 'R');
$pdf->Ln();
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(100, 5, utf8_decode('Total Compras Exentas y/o sin derecho a Crédito Fiscal'), 1, 0, 'L');
$pdf->Cell(35, 5, number_format($total_noafecto, 2, ',', '.'), 1, 0, 'R');
$pdf->Cell(35, 5, number_format($field_resumen[''], 2, ',', '.'), 1, 0, 'R');
$pdf->Cell(35, 5, number_format($field_resumen[''], 2, ',', '.'), 1, 0, 'R');
$pdf->Ln();
$pdf->Cell(100, 5, utf8_decode('Total Compras Importación Afectadas solo Alicuota General'), 1, 0, 'L');
$pdf->Cell(35, 5, number_format($i_total_alicuota_general, 2, ',', '.'), 1, 0, 'R');
$pdf->Cell(35, 5, number_format($i_total_alicuota_general_igv, 2, ',', '.'), 1, 0, 'R');
$pdf->Cell(35, 5, number_format($field_resumen[''], 2, ',', '.'), 1, 0, 'R');
$pdf->Ln();
$pdf->Cell(100, 5, utf8_decode('Total Compras Importación Afectadas solo Alicuota General - Adicional'), 1, 0, 'L');
$pdf->Cell(35, 5, number_format($i_total_alicuota_adicional, 2, ',', '.'), 1, 0, 'R');
$pdf->Cell(35, 5, number_format($i_total_alicuota_adicional_igv, 2, ',', '.'), 1, 0, 'R');
$pdf->Cell(35, 5, number_format($field_resumen[''], 2, ',', '.'), 1, 0, 'R');
$pdf->Ln();
$pdf->Cell(100, 5, utf8_decode('Total Compras Importación Afectadas solo Alicuota Reducida'), 1, 0, 'L');
$pdf->Cell(35, 5, number_format($i_total_alicuota_reducido, 2, ',', '.'), 1, 0, 'R');
$pdf->Cell(35, 5, number_format($i_total_alicuota_reducido_igv, 2, ',', '.'), 1, 0, 'R');
$pdf->Cell(35, 5, number_format($field_resumen[''], 2, ',', '.'), 1, 0, 'R');
$pdf->Ln();
$pdf->Cell(100, 5, utf8_decode('Total Compras Internas Afectadas solo Alicuota General'), 1, 0, 'L');
$pdf->Cell(35, 5, number_format($n_total_alicuota_general, 2, ',', '.'), 1, 0, 'R');
$pdf->Cell(35, 5, number_format($n_total_alicuota_general_igv, 2, ',', '.'), 1, 0, 'R');
$pdf->Cell(35, 5, number_format($field_resumen[''], 2, ',', '.'), 1, 0, 'R');
$pdf->Ln();
$pdf->Cell(100, 5, utf8_decode('Total Compras Internas Afectadas solo Alicuota General - Adicional'), 1, 0, 'L');
$pdf->Cell(35, 5, number_format($n_total_alicuota_adicional, 2, ',', '.'), 1, 0, 'R');
$pdf->Cell(35, 5, number_format($n_total_alicuota_adicional_igv, 2, ',', '.'), 1, 0, 'R');
$pdf->Cell(35, 5, number_format($field_resumen[''], 2, ',', '.'), 1, 0, 'R');
$pdf->Ln();
$pdf->Cell(100, 5, utf8_decode('Total Compras Internas Afectadas solo Alicuota Reducida'), 1, 0, 'L');
$pdf->Cell(35, 5, number_format($n_total_alicuota_reducido, 2, ',', '.'), 1, 0, 'R');
$pdf->Cell(35, 5, number_format($n_total_alicuota_reducido_igv, 2, ',', '.'), 1, 0, 'R');
$pdf->Cell(35, 5, number_format($field_resumen[''], 2, ',', '.'), 1, 0, 'R');
$pdf->Ln();
$pdf->SetX(105);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(35, 5, number_format($total_general_imponible, 2, ',', '.'), 1, 0, 'R');
$pdf->Cell(35, 5, number_format($total_general_igv, 2, ',', '.'), 1, 0, 'R');
$pdf->Cell(35, 5, number_format($field_resumen[''], 2, ',', '.'), 1, 0, 'R');
//---------------------------------------------------

//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
