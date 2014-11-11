<?php
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("lib/fphp.php");
//---------------------------------------------------
if ($fCodOrganismo != "") $filtro .=" AND (pa.CodOrganismo = '".$fCodOrganismo."')";
if ($fFechaPagod != "" || $fFechaPagoh != "") {
	if ($fFechaPagod != "") $filtro .=" AND (pa.FechaPago >= '".formatFechaAMD($fFechaPagod)."')";
	if ($fFechaPagoh != "") $filtro .=" AND (pa.FechaPago <= '".formatFechaAMD($fFechaPagoh)."')";
}
if ($fCodBanco != "") {
	$filtro.= " AND (cb.CodBanco = '".$fCodBanco."')";
	if ($fNroCuenta != "") $filtro .= " AND (pa.NroCuenta = '".$fNroCuenta."')";
}
if ($fCodTipoPago != "") $filtro.= " AND (pa.CodTipoPago = '".$fCodTipoPago."')";
if ($FlagAnulado != "S") $filtro .= "AND (pa.Estado <> 'AN')";
//---------------------------------------------------

class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
		global $_PARAMETRO;
		global $Ahora;
		global $_POST;
		global $field;
		extract($_POST);
		##
		$Logo = getValorCampo("mastorganismos", "CodOrganismo", "Logo", $fCodOrganismo);
		$NomOrganismo = getValorCampo("mastorganismos", "CodOrganismo", "Organismo", $fCodOrganismo);
		$NomDependencia = getValorCampo("mastdependencias", "CodDependencia", "Dependencia", $_PARAMETRO["DEPLOGCXP"]);
		##
		$this->SetFillColor(255, 255, 255);
		$this->SetDrawColor(0, 0, 0);
		$this->Image($_PARAMETRO["PATHLOGO"].$Logo, 10, 5, 10, 10);		
		$this->SetFont('Arial', '', 8);
		$this->SetXY(20, 5); $this->Cell(100, 5, $NomOrganismo, 0, 1, 'L');
		$this->SetXY(20, 10); $this->Cell(100, 5, $NomDependencia, 0, 0, 'L');	
		$this->SetFont('Arial', '', 8);
		$this->SetXY(225, 5); $this->Cell(20, 5, utf8_decode('Fecha: '), 0, 0, 'L');
		$this->Cell(30, 5, formatFechaDMA(substr($Ahora, 0, 10)), 0, 1, 'L');
		$this->SetXY(225, 10); $this->Cell(20, 5, utf8_decode('Página: '), 0, 0, 'L'); 
		$this->Cell(30, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		$this->SetFont('Arial', 'B', 10);
		$this->SetXY(10, 20); $this->Cell(260, 5, utf8_decode('CHEQUES EN CARTERA'), 0, 1, 'C', 0);
		$this->SetFont('Arial', '', 6);
		$this->SetXY(10, 25); $this->Cell(260, 5, utf8_decode('Fecha de Pago del '.formatFechaDMA($fFechaPagod).' al '.formatFechaDMA($fFechaPagoh)), 0, 1, 'C', 0);
		##
		$this->SetTextColor(0, 0, 0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		##
		$this->Ln(5);
		$this->SetFont('Arial', 'B', 6);
		$this->SetWidths(array(15, 15, 8, 60, 20, 15, 15, 15, 100));
		$this->SetAligns(array('C', 'C', 'C', 'L', 'R', 'C', 'C', 'C', 'L'));
		$this->Row(array('Nro. Cheque',
						 utf8_decode('Fecha Emisión'),
						 'Dif. Dias',
						 'Pagar A',
						 'Monto',
						 'Voucher Pago',
						 utf8_decode('Voucher Anulación'),
						 'Estado Documento',
						 'Glosa'));
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
$pdf->SetMargins(10, 5, 10);
$pdf->SetAutoPageBreak(5, 5);
$pdf->AddPage();
//---------------------------------------------------
//	consulto
$sql = "SELECT
			pa.CodOrganismo,
			pa.NroPago,
			pa.FechaPago,
			pa.CodProveedor,
			pa.NomProveedorPagar,
			pa.MontoPago,
			pa.Periodo,
			pa.VoucherPago,
			pa.VoucherAnulacion,
			pa.PeriodoAnulacion,
			pa.Estado,
			pa.EstadoEntrega,
			pa.FechaEntregado,
			pa.NroProceso,
			pa.Secuencia,
			pa.FlagCobrado,
			pa.NroCuenta,
			op.Concepto,
			cb.CodBanco,
			cb.CodCuenta,
			b.Banco,
			DATEDIFF(NOW(), pa.FechaPago) AS DifDias
		FROM
			ap_pagos pa
			INNER JOIN ap_ordenpago op ON (op.Anio = pa.Anio AND
										   op.CodOrganismo = pa.CodOrganismo AND
										   op.NroOrden = pa.NroOrden)
			INNER JOIN ap_ctabancaria cb ON (cb.Nrocuenta = pa.NroCuenta)
			INNER JOIN mastbancos b ON (b.CodBanco = cb.CodBanco)
		WHERE
			(pa.Estado <> 'GE') AND
			(pa.EstadoEntrega = 'C' OR (pa.EstadoEntrega <> 'C' AND pa.FechaEntregado > '".formatFechaAMD($fFechaPagoh)."')) 
			$filtro
		ORDER BY CodOrganismo, Banco, CodBanco, NroCuenta, NomProveedorPagar, CodProveedor";
$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
while ($field = mysql_fetch_array($query)) {	++$i;
	$g = "$field[CodBanco].$field[NroCuenta]";
	##
	if ($grupo != $g) {
		$grupo = $g;
		if ($i > 1) {
			$pdf->SetDrawColor(0, 0, 0);
			$pdf->SetFillColor(0, 0, 0);
			$y = $pdf->GetY();
			$pdf->Rect(108, $y, 20, 0.1, 'DF');
			$pdf->Rect(108, $y+0.5, 20, 0.1, 'DF');
			$pdf->SetFont('Arial', 'B', 6);
			$pdf->Cell(30, 5, utf8_decode('Total Cheques.       '.$TotalCheques), 0, 0, 'L', 0);
			$pdf->Cell(68, 5, 'Total Cta. Bancaria. ', 0, 0, 'R', 0);
			$pdf->Cell(20, 5, number_format($TotalCuenta, 2, ',', '.'), 0, 0, 'R', 0);
			$pdf->Ln(8);
			$TotalCheques = 0;
			$TotalCuenta = 0;
		}
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetDrawColor(255, 255, 255);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(50, 5, utf8_decode('Cta. Bancaria. '.$field['NroCuenta']), 0, 0, 'L', 0);
		$pdf->Cell(80, 5, utf8_decode($field['Banco']), 0, 1, 'L', 0);
	}
	$TotalCheques++;
	$TotalCuenta += $field['MontoPago'];
	##
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 6);
	$pdf->Row(array($field['NroPago'],
					formatFechaDMA($field['FechaPago']),
					$field['DifDias'],
					utf8_decode($field['NomProveedorPagar']),
					number_format($field['MontoPago'], 2, ',', '.'),
					$field['VoucherPago'],
					$field['VoucherAnulacion'],
					printValores("ESTADO-PAGO", $field['Estado']),
					substr(utf8_decode(strtoupper($field['Concepto'])), 0, 70)));
	##	muestro las obligaciones
	if ($FlagObligaciones == "S") {
		$sql = "SELECT
					o.CodTipoDocumento,
					o.NroDocumento,
					o.FechaDocumento,
					o.MontoObligacion
				FROM ap_obligaciones o
				WHERE
					NroProceso = '".$field['NroProceso']."' AND
					ProcesoSecuencia = '".$field['Secuencia']."'";
		$query_obligacion = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		while ($field_obligacion = mysql_fetch_array($query_obligacion)) {
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetDrawColor(255, 255, 255);
			$pdf->SetFillColor(255, 255, 255);
			$pdf->SetFont('Arial', '', 6);
			$pdf->Cell(10, 3);
			$pdf->Cell(20, 3, 'Obligaciones. ', 0, 0, 'L', 0);
			$pdf->Cell(30, 3, $field_obligacion['CodTipoDocumento'].'-'.$field_obligacion['NroDocumento'], 0, 0, 'L', 0);
			$pdf->Cell(15, 3, formatFechaDMA($field_obligacion['FechaDocumento']), 0, 0, 'C', 0);
			$pdf->Cell(20, 3, number_format($field_obligacion['FechaDocumento'], 2, ',', '.'), 0, 1, 'R', 0);
		}
		##
		$pdf->Ln(1);
	}
}
##	
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(0, 0, 0);
$y = $pdf->GetY();
$pdf->Rect(108, $y, 20, 0.1, 'DF');
$pdf->Rect(108, $y+0.5, 20, 0.1, 'DF');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(30, 5, utf8_decode('Total Cheques.       '.$TotalCheques), 0, 0, 'L', 0);
$pdf->Cell(68, 5, 'Total Cta. Bancaria. ', 0, 0, 'R', 0);
$pdf->Cell(20, 5, number_format($TotalCuenta, 2, ',', '.'), 0, 0, 'R', 0);
//---------------------------------------------------

//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
