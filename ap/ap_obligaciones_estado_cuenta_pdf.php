<?php
extract($_POST);
extract($_GET);
//---------------------------------------------------
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("../lib/ap_fphp.php");
connect();
//---------------------------------------------------
//	proveedor
$sql = "SELECT
			p.NomCompleto AS NomProveedor,
			p.Ndocumento AS RifProveedor,
			p.Direccion AS DirProveedor,
			p.Telefono1 As TelProveedor,
			p.Fax AS FaxProveedor
		FROM mastpersonas p
		WHERE p.CodPersona = '".$fproveedor."'";
$query_proveedor = mysql_query($sql) or die($sql.mysql_error());
if (mysql_num_rows($query_proveedor) != 0) $field_proveedor = mysql_fetch_array($query_proveedor);
//---------------------------------------------------

//---------------------------------------------------
class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
		global $_PARAMETRO;
		global $_POST;
		global $_GET;
		global $field_proveedor;
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
		if ($fdesde != "" && $fhasta != "") $title = "del ".$fdesde." al ".$fhasta; else $title = " al ".$fhasta;
		$this->Cell(198, 5, utf8_decode('Estado de Cuenta '.$title), 0, 0, 'C');
		$this->Ln(10);
		//	imprimo datos generales
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		$y = $this->GetY();	$x = 10;		
		$this->RoundedRect($x, $y, 135, 30, 2.5, 'DF');
		$this->SetXY($x, $y);
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(125, 5, utf8_decode('Nombre / Razón Social del Proveedor'), 0, 0, 'L');
		$this->Ln(5);
		$this->SetFont('Arial', '', 12);
		$this->Cell(125, 5, utf8_decode($field_proveedor['NomProveedor']), 0, 0, 'L');
		$this->Ln(6);
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(125, 5, utf8_decode('Dirección'), 0, 0, 'L');
		$this->Ln(4);
		$this->SetFont('Arial', '', 8);
		$this->MultiCell(125, 5, utf8_decode($field_proveedor['DirProveedor']), 0, 'L');
		$this->SetXY($x, $y+25);
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(15, 5, utf8_decode('Teléfono:'), 0, 0, 'L');
		$this->SetFont('Arial', '', 8);
		$this->Cell(35, 5, $field_proveedor['TelProveedor'], 0, 0, 'L');
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(8, 5, utf8_decode('Fax:'), 0, 0, 'L');
		$this->SetFont('Arial', '', 8);
		$this->Cell(37, 5, $field_proveedor['FaxProveedor'], 0, 0, 'L');
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(8, 5, utf8_decode('Rif:'), 0, 0, 'L');
		$this->SetFont('Arial', '', 8);
		$this->Cell(22, 5, $field_proveedor['RifProveedor'], 0, 0, 'L');
		##
		$x = 147;
		$this->RoundedRect($x, $y, 60, 30, 2.5, 'DF');
		$this->SetXY($x, $y);
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(60, 8, utf8_decode('DEPOSITOS'), 0, 0, 'L');
		$this->Ln(11); $this->SetX($x);
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(18, 5, utf8_decode('Nro. Cuenta:'), 0, 0, 'L');
		$this->SetFont('Arial', '', 8);
		$this->Cell(42, 5, $field_proveedor['NroCuenta'], 0, 0, 'L');
		$this->Ln(7); $this->SetX($x);
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(18, 5, utf8_decode('Tipo:'), 0, 0, 'L');
		$this->SetFont('Arial', '', 8);
		$this->Cell(42, 5, $field_proveedor['NroCuenta'], 0, 0, 'L');
		$this->Ln(7); $this->SetX($x);
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(18, 5, utf8_decode('Banco:'), 0, 0, 'L');
		$this->SetFont('Arial', '', 8);
		$this->Cell(42, 5, $field_proveedor['NroCuenta'], 0, 0, 'L');
		##
		$this->Ln(10);
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(198, 5, utf8_decode('Documentos Pagados'), 0, 0, 'L');
		$this->Ln(6);
		##
		$this->SetFillColor(255, 255, 255);
		$this->SetDrawColor(255, 255, 255);
		$this->SetFont('Arial', 'B', 7);
		$this->SetWidths(array(32, 20, 20, 20, 20, 20, 20, 35, 10));
		$this->SetAligns(array('L', 'C', 'C', 'C', 'C', 'C', 'C', 'R', 'C'));
		$this->Row(array(utf8_decode('Obligación'),
						 'Nro. Registro',
						 'F. Documento',
						 'F. Registro',
						 utf8_decode('F. Recepción'),
						 'F. Prog. Pago',
						 'F. Pago',
						 'Monto',
						 'Est.'));
		##
		$this->SetFillColor(0, 0, 0);
		$this->SetDrawColor(0, 0, 0);
		$this->Rect(10, $this->GetY(), 197, 0.1, "FD");
		$this->Ln(5);
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
$pdf->AddPage();
//---------------------------------------------------
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(255, 255, 255);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('Arial', '', 6);
//---------------------------------------------------
$filtro = "";
if ($forganismo != "") $filtro .= " AND o.CodOrganismo = '".$forganismo."'";
if ($fproveedor != "") $filtro .= " AND o.CodProveedor = '".$fproveedor."'";
if ($ftdoc != "") $filtro .= " AND o.CodTipoDocumento = '".$ftdoc."'";
if ($fhasta != "") $filtro .= " AND o.FechaDocumento <= '".formatFechaAMD($fhasta)."'";
//---------------------------------------------------
$total_registros = 0;
//	consulto la obligaciones
$sql = "SELECT
			o.CodTipoDocumento,
			o.NroDocumento,
			o.NroRegistro,
			o.FechaDocumento,
			o.FechaRegistro,
			o.FechaRecepcion,
			o.FechaProgramada,
			o.FechaPago,
			o.MontoObligacion,
			o.Estado
		FROM ap_obligaciones o
		WHERE 1 $filtro
		ORDER BY CodTipoDocumento, NroDocumento";
$query = mysql_query($sql) or die ($sql.mysql_error());
while ($field = mysql_fetch_array($query)) {
	$total_registros++;
	$total_documentos += $field['MontoObligacion'];
	
	if ($field['Estado'] == "PA") {
		$total_pagados += $field['MontoObligacion'];
		//	imprimo documento
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetDrawColor(255, 255, 255);
		$pdf->SetFont('Arial', '', 8);	
		$pdf->Row(array($field['CodTipoDocumento'].'-'.$field['NroDocumento'],
						$field['NroRegistro'],
						formatFechaDMA($field['FechaDocumento']),
						formatFechaDMA($field['FechaRegistro']),
						formatFechaDMA($field['FechaRecepcion']),
						formatFechaDMA($field['FechaProgramada']),
						formatFechaDMA($field['Fechapago']),
						number_format($field['MontoObligacion'], 2, ',', '.')));
		$pdf->Ln(2);
	}
}
$pdf->Ln(5);
//	imprimo total
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(0, 0, 0);
$y = $pdf->GetY();
$pdf->Rect(162, $y, 35, 0.1, "FD");
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(145, 5, 'Total Pagados: ', 0, 0, 'R');
$pdf->Cell(42, 5, number_format($total_pagados, 2, ',', '.'), 0, 0, 'R');
$pdf->Ln(10);
$y = $pdf->GetY();
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(145, 5, 'Total Documentos del Proveedor: ', 0, 0, 'R');
$pdf->Cell(7, 5);
$pdf->Cell(35, 5, number_format($total_documentos, 2, ',', '.'), 1, 0, 'R');
//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>