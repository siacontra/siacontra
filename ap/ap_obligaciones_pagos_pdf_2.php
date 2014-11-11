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
		extract($_POST);
		extract($_GET);
		$this->Image($_PARAMETRO["PATHLOGO"].'contraloria.jpg', 10, 5, 10, 10);
		$this->SetFont('Arial', '', 6);
		$this->SetXY(20, 5); $this->Cell(100, 5, $_SESSION['NOMBRE_ORGANISMO_ACTUAL'], 0, 1, 'L');
		$this->SetXY(20, 10); $this->Cell(100, 5, utf8_decode('DIRECCIÓN DE ADMINISTRACIÓN Y SERVICIOS'), 0, 0, 'L');
		$this->SetFont('Arial', '', 6);
		$this->SetXY(225, 5); $this->Cell(20, 5, utf8_decode('Fecha: '), 0, 0, 'R'); 
		$this->Cell(30, 5, date("d-m-Y"), 0, 1, 'L');
		$this->SetFont('Arial', '', 6);
		$this->SetXY(225, 10); $this->Cell(20, 5, utf8_decode('Página: '), 0, 0, 'R'); 
		$this->Cell(30, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		$this->Ln(5);
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(260, 5, utf8_decode('Obligaciones Vs. Pagos'), 0, 0, 'C');
		$this->Ln(5);
		//	imprimom datos generales
		$this->SetFont('Arial', 'B', 6);
		$this->Cell(20, 5, 'Periodo: '.$fperiodo, 0, 0, 'L');
		$this->Ln(5);
		//	imprimo cuerpo
		$this->SetFillColor(255, 255, 255);
		$this->SetDrawColor(0, 0, 0);
		$this->SetFont('Arial', 'B', 6);
		$this->SetWidths(array(15, 55, 30, 18, 18, 18, 18, 15, 20, 15, 18, 15, 5));
		$this->SetAligns(array('C', 'L', 'C', 'R', 'R', 'R', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
		$this->Row(array('Fecha Documento',
						 'Proveedor',
						 'Nro. Documento',
						 'Monto Obligaciones',
						 '(-)Adelantos (-)Pago Parcial',
						 'Neto por Pagar',
						 utf8_decode('Voucher Provisión'),
						 'Fecha de Pago',
						 'Cuenta Bancaria',
						 'Nro. Pago',
						 'Voucher',
						 'Nro. Proceso',
						 '#'));
		$this->Ln(2);
	}
	//	Pie de página.
	function Footer() {}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('L', 'mm', 'Letter');
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
if ($fperiodo != "") $filtro .= " AND o.Periodo = '".$fperiodo."'";
//---------------------------------------------------
$total_obligaciones = 0;
$total_novoucher = 0;
$total_nopago = 0;
//	consulto la obligaciones
$sql = "SELECT
			o.CodProveedor,
			o.CodTipoDocumento,
			o.NroDocumento,
			o.FechaDocumento,
			o.MontoObligacion,
			o.MontoAdelanto,
			o.MontoPagoParcial,
			o.Periodo AS PeriodoVoucherProvision,
			o.Voucher AS VoucherProvision,
			p.FechaPago,
			p.NroProceso,
			p.NroPago,
			p.Periodo AS PeriodoVoucherPago,
			p.VoucherPago,
			p.MontoPago,
			p.NroCuenta,
			p.Secuencia,
			mp.NomCompleto AS NomProveedor
		FROM
			ap_obligaciones o
			INNER JOIN ap_pagos p ON (o.NroProceso = p.NroProceso AND
									  o.ProcesoSecuencia = p.Secuencia)
			INNER JOIN mastpersonas mp ON (o.CodProveedor = mp.CodPersona)
		WHERE 1 $filtro
		ORDER BY NroCuenta, CodProveedor";
$query = mysql_query($sql) or die ($sql.mysql_error());
while ($field = mysql_fetch_array($query)) {
	list($aniop, $mesp) = split("[-]", $field['PeriodoVoucherPago']);
	list($codvoucherp, $nrovoucherp) = split("[-]", $field['VoucherPago']);
	$voucherpago = "$aniop$mesp-$codvoucherp$nrovoucherp";
	list($anioo, $meso) = split("[-]", $field['PeriodoVoucherProvision']);
	list($codvouchero, $nrovouchero) = split("[-]", $field['VoucherProvision']);
	$voucherprovision = "$anioo$meso-$codvouchero$nrovouchero";
	$monto_adelanto = $field['MontoAdelanto'] + $field['MontoPagoParcial'];
	$monto_neto = $field['MontoObligacion'] - $monto_adelanto;
	$total_obligaciones++;
	if ($field['VoucherPago'] == "") $total_novoucher++;
	if ($field['NroPago'] == "") $total_nopago++;
	//	imprimo documento
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 6);
	$pdf->Row(array(formatFechaDMA($field['FechaDocumento']),
					$field['NomProveedor'],
					$field['CodTipoDocumento'].'-'.$field['NroDocumento'],
					number_format($field['MontoObligacion'], 2, ',', '.'),
					number_format($monto_adelanto, 2, ',', '.'),
					number_format($monto_neto, 2, ',', '.'),
					$voucherprovision,
					formatFechaDMA($field['FechaPago']),
					$field['NroCuenta'],
					$field['NroPago'],
					$voucherpago,
					$field['NroProceso'],
					$field['Secuencia']));
}
//	imprimo sub-total
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(132, 5, 'Total Obligaciones: '.$total_obligaciones, 0, 0, 'L');
$pdf->Cell(75, 5, 'Total Pagos No Contabilizados: '.$total_novoucher, 0, 0, 'R');
$pdf->Ln(8);
$pdf->Cell(60, 5, 'Total Obligaciones No Pagadas: '.$total_nopago, 0, 0, 'L');
//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>