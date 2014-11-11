<?php
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("lib/fphp.php");
//---------------------------------------------------
if ($fCodOrganismo != "") $filtro .=" AND (r.CodOrganismo = '".$fCodOrganismo."')";
if ($fPeriodod != "" || $fPeriodoh != "") {
	if ($fPeriodod != "") $filtro .=" AND (r.PeriodoFiscal >= '".$fPeriodod."')";
	if ($fPeriodoh != "") $filtro .=" AND (r.PeriodoFiscal <= '".$fPeriodoh."')";
}
if ($fCodProveedor != "") $filtro .=" AND (r.CodProveedor = '".$fCodProveedor."')";
//---------------------------------------------------

class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
		global $_PARAMETRO;
		global $Ahora;
		global $_POST;
		global $field;
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
		$this->SetXY(235, 5); $this->Cell(15, 5, utf8_decode('Fecha: '), 0, 0, 'L');
		$this->Cell(30, 5, formatFechaDMA(substr($Ahora, 0, 10)), 0, 1, 'L');
		$this->SetXY(235, 10); $this->Cell(15, 5, utf8_decode('Página: '), 0, 0, 'L'); 
		$this->Cell(30, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		##	titulo
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(268, 5, utf8_decode('RELACION DETALLADA DEL I.S.L.R RETENIDO'), 0, 0, 'C');
		##
		$this->SetTextColor(0, 0, 0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		##
		$this->Ln(10);
		##	imprimir titulos
		$this->SetFillColor(255, 255, 255);
		$this->SetDrawColor(0, 0, 0);
		$this->SetFont('Arial', 'B', 6);
		$this->SetWidths(array(20, 61, 12, 16, 25, 25, 16, 16, 16, 16, 16, 13, 16));
		$this->SetAligns(array('C', 'L', 'C', 'C', 'L', 'L', 'C', 'R', 'R', 'R', 'R', 'R', 'R'));
		$this->Row(array('Comprobante',
						 utf8_decode('Nombre o Razón Social'),
						 'Periodo Fiscal',
						 'Fecha Comprobante',
						 'Nro. Control',
						 'Nro. Factura',
						 'Fecha Factura',
						 'Monto Imponible',
						 'Monto Exento',
						 'Monto Impuesto',
						 'Monto Factura',
						 'Porcentaje',
						 'Monto Retenido'));
		$this->Ln(1);	
	}
	
	//	Pie de página.
	function Footer() {
	}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('L', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->SetMargins(5, 5, 5);
$pdf->SetAutoPageBreak(5, 5);
$pdf->AddPage();
//---------------------------------------------------
$i = 0;
//	consulto
$sql = "SELECT distinct
			CONCAT(SUBSTRING(PeriodoFiscal, 1, 4), SUBSTRING(PeriodoFiscal, 6, 2), NroComprobante) AS NroComprobante,
			p.NomCompleto AS NomProveedor,
			r.PeriodoFiscal,
			r.FechaComprobante,
			r.NroDocumento,
			r.NroControl,
			r.FechaFactura,
			r.MontoAfecto,
			r.MontoNoAfecto,
			r.MontoImpuesto,
			r.MontoFactura,
			i.FactorPorcentaje,
			ABS(r.MontoRetenido) AS MontoRetenido
		FROM
			ap_retenciones r
			INNER JOIN mastorganismos o ON (o.CodOrganismo = r.CodOrganismo)
			INNER JOIN mastpersonas p ON (p.CodPersona = r.CodProveedor)
			INNER JOIN mastimpuestos i ON (i.CodImpuesto = r.CodImpuesto)

JOIN ap_obligacionesimpuesto as obi ON (obi.CodProveedor = r.CodProveedor AND
											  obi.CodTipoDocumento = r.CodTipoDocumento AND
											  obi.NroDocumento = r.NroDocumento)

		WHERE
			r.TipoComprobante = 'ISLR' AND
			r.Estado = 'PA'
			$filtro
		ORDER BY FechaComprobante, NroComprobante";
$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
while ($field = mysql_fetch_array($query)) {
	$MontoRetenido += $field['MontoRetenido'];
	##	imprimo linea
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 6);
	$pdf->Row(array($field['NroComprobante'],
					utf8_decode($field['NomProveedor']),
					$field['PeriodoFiscal'],
					formatFechaDMA($field['FechaComprobante']),
					$field['NroDocumento'],
					$field['NroControl'],
					formatFechaDMA($field['FechaFactura']),
					number_format($field['MontoAfecto'], 2, ',', '.'),
					number_format($field['MontoNoAfecto'], 2, ',', '.'),
					number_format($field['MontoImpuesto'], 2, ',', '.'),
					number_format($field['MontoFactura'], 2, ',', '.'),
					number_format($field['FactorPorcentaje'], 2, ',', '.'),
					number_format($field['MontoRetenido'], 2, ',', '.')));
}
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
				'TOTAL:',
				'',
				number_format($MontoRetenido, 2, ',', '.')));
//---------------------------------------------------

//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
