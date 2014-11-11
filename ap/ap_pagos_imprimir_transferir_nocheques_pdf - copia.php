<?php
extract($_POST);
extract($_GET);
//---------------------------------------------------
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("../lib/ap_fphp.php");
connect();
//---------------------------------------------------
//	consulto la informacion general
$sql = "SELECT
			p.NomProveedorPagar,
			p.MontoPago,
			p.NroProceso,
			p.Secuencia,
			p.NroCuenta,
			p.NroPago,
			p.FechaPago,
			b.Banco
		FROM
			ap_pagos p
			INNER JOIN ap_ctabancaria cb ON (p.NroCuenta = cb.NroCuenta)
			INNER JOIN mastbancos b ON (cb.CodBanco = b.CodBanco)
		WHERE p.NroProceso = '".$nroproceso."'";
$query = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
//---------------------------------------------------
list($anio, $mes) = split("[-]", $field['Periodo']);
list($cod, $nro) = split("[-]", $field['VoucherPago']);
$comprobante = "$anio$mes-$cod$nro";
//---------------------------------------------------

//---------------------------------------------------
class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
		global $_PARAMETRO;
		global $field;
		
		list($int, $dec) = split("[.]", $field['MontoPago']);
		$int_letras = strtoupper(convertir_a_letras($int, "entero"));
		$monto_letras = "$int_letras BOLIVARES CON $dec/100";
		
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(60, 5, $field['Banco'], 0, 0, 'L');
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(16, 5, 'Nro. Pago', 0, 0, 'L');
		$this->SetFont('Arial', '', 8);
		$this->Cell(100, 5, $field['NroPago'], 0, 0, 'L');
		$this->Ln(5);
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(27, 5, 'Pagar a la orden de ', 0, 0, 'L');
		$this->SetFont('Arial', '', 8);
		$this->Cell(160, 5, $field['NomProveedorPagar'], 0, 0, 'L');
		$this->Ln(5);
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(17, 5, 'La suma de ', 0, 0, 'L');
		$this->SetFont('Arial', '', 8);
		$this->Cell(160, 5, $monto_letras, 0, 0, 'L');
		$this->Ln(5);
		$this->Ln(5);
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(15, 5, 'Proceso ', 0, 0, 'L');
		$this->Cell(25, 5, $field['NroProceso'], 0, 0, 'R');
		$this->Cell(5, 5, $field['Secuencia'], 0, 0, 'L');
		$this->Ln(5);
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(15, 5, 'Cta. Cte. ', 0, 0, 'L');
		$this->Cell(25, 5, $field['NroCuenta'], 0, 0, 'R');
		$this->Ln(10);
	}
	//	Pie de página.
	function Footer() {
		
	}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->SetMargins(10, 5, 10);
$pdf->SetAutoPageBreak(5, 1);
$pdf->AddPage();
//---------------------------------------------------
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(255, 255, 255);

//	obligaciones asociadas
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(195, 5, 'Obligaciones Asociadas:', 0, 0, 'L');
##
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(255, 255, 255);
$y = $pdf->GetY();
$pdf->Rect(10, $y-35, 165, 30, "D");
$pdf->Ln(5);
$y = $pdf->GetY();
$pdf->Rect(10, $y, 195, 0.1, "FD");

//	consulto e imprimo obligaciones
$sql = "SELECT	
			o.NroDocumento,
			o.FechaDocumento,
			o.MontoObligacion,
			o.MontoAdelanto,
			o.MontoImpuestoOtros,
			o.MontoImpuesto,
			o.MontoPagoParcial,
			o.Comentarios,
			td.Descripcion AS NomTipoDocumento,
			p1.NomCompleto AS NomIngresadoPor,
			p2.NomCompleto AS NomRevisadoPor,
			p3.NomCompleto AS NomAprobadoPor
		FROM
			ap_obligaciones o
			INNER JOIN ap_tipodocumento td ON (o.CodTipoDocumento = td.CodTipoDocumento)
			INNER JOIN mastpersonas p1 ON (o.IngresadoPor = p1.CodPersona)
			INNER JOIN mastpersonas p2 ON (o.RevisadoPor = p2.CodPersona)
			INNER JOIN mastpersonas p3 ON (o.AprobadoPor = p3.CodPersona)
		WHERE
			o.NroProceso = '".$nroproceso."' AND
			o.ProcesoSecuencia = '".$field['Secuencia']."'";
$query_obligacion = mysql_query($sql) or die ($sql.mysql_error());
while($field_obligacion = mysql_fetch_array($query_obligacion)) {
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 8);
	##
	$pdf->Cell(20, 5, 'Documento: ', 0, 0, 'L');
	$pdf->Cell(35, 5, $field_obligacion['NomTipoDocumento'], 0, 0, 'L');
	$pdf->Cell(20, 5, 'Ingresado Por: ', 0, 0, 'L');
	$pdf->Cell(55, 5, $field_obligacion['NomIngresadoPor'], 0, 0, 'L');
	$pdf->Cell(15, 5, utf8_decode('Total Obligación: '), 0, 0, 'L');
	$pdf->Cell(35, 5, number_format($field_obligacion['MontoObligacion'], 2, ',', '.'), 0, 0, 'R');
	$pdf->Ln(5);
	$pdf->Cell(20, 5, utf8_decode('Número: '), 0, 0, 'L');
	$pdf->Cell(35, 5, $field_obligacion['NroDocumento'], 0, 0, 'L');
	$pdf->Cell(20, 5, 'Revisado Por: ', 0, 0, 'L');
	$pdf->Cell(55, 5, $field_obligacion['NomRevisadoPor'], 0, 0, 'L');
	$pdf->Cell(15, 5, utf8_decode('(-) Retenc. Renta: '), 0, 0, 'L');
	$pdf->Cell(35, 5, number_format($field_obligacion['MontoImpuestoOtros'], 2, ',', '.'), 0, 0, 'R');
	$pdf->Ln(5);
	$pdf->Cell(20, 5, utf8_decode('Fecha: '), 0, 0, 'L');
	$pdf->Cell(35, 5, $field_obligacion['FechaDocumento'], 0, 0, 'L');
	$pdf->Cell(20, 5, 'Aprobado Por: ', 0, 0, 'L');
	$pdf->Cell(55, 5, $field_obligacion['NomAprobadoPor'], 0, 0, 'L');
	$pdf->Cell(15, 5, utf8_decode('(-) Adelantos: '), 0, 0, 'L');
	$pdf->Cell(35, 5, number_format($field_obligacion['MontoAdelanto'], 2, ',', '.'), 0, 0, 'R');
	$pdf->Ln(5);
	$y = $pdf->GetY();
	$pdf->Cell(20, 5, utf8_decode('Descripción: '), 0, 0, 'L');
	$pdf->SetXY(30, $y);
	$pdf->MultiCell(110, 5, utf8_decode($field_obligacion['Comentarios']), 1, 'L', 1);
	$pdf->SetXY(140, $y);
	$pdf->Cell(15, 5, utf8_decode('(-) Retenc. IGV: '), 0, 0, 'L');
	$pdf->Cell(35, 5, number_format($field_obligacion['MontoImpuesto'], 2, ',', '.'), 0, 0, 'R');
	$pdf->Ln(5);
	$pdf->SetX(140);
	$pdf->Cell(15, 5, utf8_decode('(-) Pago Parcial: '), 0, 0, 'L');
	$pdf->Cell(35, 5, number_format($field_obligacion['MontoPagoParcial'], 2, ',', '.'), 0, 0, 'R');
	$pdf->Ln(5);
	$pdf->SetX(140);
	$pdf->Cell(15, 5, utf8_decode('Monto a Pagar: '), 0, 0, 'L');
	$pdf->Cell(35, 5, number_format($field_obligacion['MontoObligacion'], 2, ',', '.'), 0, 0, 'R');
	$pdf->Ln(5);
	##
	$pdf->SetDrawColor(0, 0, 0);
	$pdf->SetFillColor(0, 0, 0);
	$y = $pdf->GetY();
	$pdf->Rect(10, $y, 195, 0.1, "FD");
}
//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>
