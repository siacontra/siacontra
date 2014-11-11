<?php
extract($_POST);
extract($_GET);
//---------------------------------------------------
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("../lib/ap_fphp.php");
connect();
//---------------------------------------------------

//---------------------------------------------------
class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
		global $_PARAMETRO;
		global $_POST;
		global $_GET;
		global $field;
		extract($_POST);
		extract($_GET);
		//	imprimo cabecera
		$this->Image($_PARAMETRO["PATHLOGO"].'contraloria.jpg', 10, 5, 10, 10);
		$this->SetFont('Arial', '', 6);
		$this->SetXY(20, 5); $this->Cell(100, 5, $_SESSION['NOMBRE_ORGANISMO_ACTUAL'], 0, 1, 'L');
		$this->SetXY(20, 10); $this->Cell(100, 5, utf8_decode('DIRECCIÓN DE ADMINISTRACIÓN'), 0, 0, 'L');
		$this->SetFont('Arial', '', 6);
		$this->SetXY(225, 5); $this->Cell(20, 5, utf8_decode('Fecha: '), 0, 0, 'R');
		$this->Cell(30, 5, date("d-m-Y"), 0, 1, 'L');
		$this->SetFont('Arial', '', 6);
		$this->SetXY(225, 10); $this->Cell(20, 5, utf8_decode('Página: '), 0, 0, 'R');
		$this->Cell(30, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		$this->Ln(5);
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(198, 5, utf8_decode('Documentos Emitidos'), 0, 1, 'C');
		$this->SetFont('Arial', '', 8);
		$this->Cell(198, 5, utf8_decode('Fecha de Emisión del '.$ffechad.' al '.$ffechah), 0, 0, 'C');
		$this->Ln(10);
		$this->SetFont('Arial', '', 8);
		$this->Cell(25, 5, 'Cta. Bancaria', 0, 0, 'L');
		$this->Cell(75, 5, $field['NroCuenta'].' '.utf8_decode($field['Descripcion']), 0, 1, 'L');
		$this->Cell(25, 5, 'Cta. Contable', 0, 0, 'L');
		$this->Cell(75, 5, $field['CodCuenta'], 0, 0, 'L');
		$this->Ln(5);
		//	imprimo cuerpo
		$this->SetFillColor(255, 255, 255);
		$this->SetDrawColor(0, 0, 0);
		$this->SetFont('Arial', 'B', 7);
		$this->SetWidths(array(20, 20, 78, 20, 20, 20, 20));
		$this->SetAligns(array('C', 'C', 'L', 'R', 'C', 'C', 'C'));
		$this->Row(array('Nro. Cheque',
						 utf8_decode('Fecha de Emisión'),
						 'Pagar A',
						 'Monto',
						 'Voucher Pago',
						 utf8_decode('Voucher Anulación'),
						 'Estado'));
		$this->Ln(1);
	}
	//	Pie de página.
	function Footer() {}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->SetMargins(10, 5, 10);
$pdf->SetAutoPageBreak(5, 1);
//---------------------------------------------------
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(255, 255, 255);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('Arial', '', 6);
//---------------------------------------------------
$filtro = "";
if ($forganismo != "") $filtro .= " AND p.CodOrganismo = '".$forganismo."'";
if ($ftipo_pago != "") $filtro .= " AND p.CodTipoPago = '".$ftipo_pago."'";
if ($fffechad != "" || $fffechah != "") {
	if ($fffechad != "") $filtro .= " AND p.FechaPago >= '".$fffechad."'";
	if ($fffechah != "") $filtro .= " AND p.FechaPago <= '".$fffechah."'";
}
if ($fctabancaria != "") $filtro .= " AND p.NroCuenta = '".$fctabancaria."'";
//---------------------------------------------------
$total_registros = 0;
//	consulto la obligaciones
$sql = "SELECT
			p.NroPago,
			p.FechaPago,
			p.NomProveedorPagar,
			p.MontoPago,
			p.VoucherPago,
			p.VoucherAnulacion,
			p.Estado,
			cb.NroCuenta,
			cb.Descripcion,
			cb.CodCuenta
		FROM
			ap_pagos p
			INNER JOIN ap_ctabancaria cb ON (p.NroCuenta = cb.NroCuenta)
		WHERE 1 $filtro
		ORDER BY NroCuenta";
$query = mysql_query($sql) or die ($sql.mysql_error());
while ($field = mysql_fetch_array($query)) {
	$total_registros++;
	
	if ($grupo != $field['NroCuenta']) {
		$grupo = $field['NroCuenta'];
		if ($total_registros > 1) {
			$pdf->Ln(5);
			//	imprimo total
			$pdf->SetDrawColor(0, 0, 0);
			$pdf->SetFillColor(0, 0, 0);
			$y = $pdf->GetY();
			$pdf->Rect(128, $y-1, 20, 0.1, "FD");
			$pdf->Rect(128, $y, 20, 0.1, "FD");
			$pdf->SetFont('Arial', 'B', 7);
			$pdf->Cell(50, 5, 'Total Nro. Cheques: '.$total_nro_cheques, 0, 0, 'L');
			$pdf->Cell(68, 5, 'Total Nro. Cheques: ', 0, 0, 'R');
			$pdf->Cell(20, 5, number_format($total_ctabancaria, 2, ',', '.'), 0, 0, 'R');
		}
		$pdf->AddPage();
		$total_nro_cheques = 0;
		$total_ctabancaria = 0;
	}
	$total_nro_cheques++;
	$total_ctabancaria += $field['MontoPago'];
	//	imprimo documento
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 7);
	$pdf->Row(array($field['NroPago'],
					formatFechaDMA($field['FechaPago']),
					utf8_decode($field['NomProveedorPagar']),
					number_format($field['MontoPago'], 2, ',', '.'),
					$field['VoucherPago'],
					$field['VoucherAnulacion'],
					printValores2("ESTADO-PAGO", $field['Estado'])));
	$pdf->Ln(2);
}
$pdf->Ln(5);
//	imprimo total
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(0, 0, 0);
$y = $pdf->GetY();
$pdf->Rect(128, $y-1, 20, 0.1, "FD");
$pdf->Rect(128, $y, 20, 0.1, "FD");
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(50, 5, 'Total Nro. Cheques: '.$total_nro_cheques, 0, 0, 'L');
$pdf->Cell(68, 5, 'Total Cta. Bancaria: ', 0, 0, 'R');
$pdf->Cell(20, 5, number_format($total_ctabancaria, 2, ',', '.'), 0, 0, 'R');
//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>