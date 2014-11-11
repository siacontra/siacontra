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
		$this->Cell(260, 5, utf8_decode('Pagos Vs. Obligaciones'), 0, 0, 'C');
		$this->Ln(5);
		//	imprimom datos generales
		$this->SetFont('Arial', 'B', 6);
		$this->Cell(20, 5, 'Periodo: '.$fperiodo, 0, 0, 'L');
		$this->Ln(5);
		//	imprimo cuerpo
		$this->SetFillColor(255, 255, 255);
		$this->SetDrawColor(0, 0, 0);
		$this->SetFont('Arial', 'B', 6);
		$this->SetWidths(array(65, 15, 15, 20, 20, 30, 15, 20, 20, 20, 20));
		$this->SetAligns(array('L', 'C', 'C', 'C', 'R', 'C', 'C', 'R', 'R', 'R', 'C'));
		$this->Row(array('Pagar A',
						 'Fecha de Pago',
						 'Nro. Proceso',
						 'Voucher',
						 'Monto Pagado',
						 'Nro. Documento',
						 'Fecha Documento',
						 'Monto Obligaciones',
						 'Monto Adelantos',
						 'Pagado',
						 utf8_decode('Voucher Provisión')));
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
if ($forganismo != "") $filtro .= " AND p.CodOrganismo = '".$forganismo."'";
if ($fproveedor != "") $filtro .= " AND p.CodProveedor = '".$fproveedor."'";
if ($fperiodo != "") $filtro .= " AND p.Periodo = '".$fperiodo."'";
//---------------------------------------------------
$total_pagos = 0;
$total_novoucher = 0;
//	consulto la obligaciones
$sql = "SELECT
			o.CodProveedor,
			p.NomProveedorPagar,
			p.FechaPago,
			p.NroProceso,
			p.Periodo AS PeriodoVoucherPago,
			p.VoucherPago,
			p.MontoPago,
			p.NroCuenta,
			o.CodTipoDocumento,
			o.NroDocumento,
			o.FechaDocumento,
			o.MontoObligacion,
			o.MontoAdelanto,
			o.MontoPagoParcial,
			o.Periodo AS PeriodoVoucherProvision,
			o.Voucher AS VoucherProvision,
			cb.Descripcion AS NomCuenta
		FROM
			ap_pagos p
			INNER JOIN ap_obligaciones o ON (p.NroProceso = o.NroProceso AND
											 p.Secuencia = o.ProcesoSecuencia)
			INNER JOIN ap_ctabancaria cb ON (p.NroCuenta = cb.NroCuenta)
		WHERE 1 $filtro
		ORDER BY NroCuenta, CodProveedor";
$query = mysql_query($sql) or die ($sql.mysql_error());
while ($field = mysql_fetch_array($query)) {
	$total_pagos++;
	list($aniop, $mesp) = split("[-]", $field['PeriodoVoucherPago']);
	list($codvoucherp, $nrovoucherp) = split("[-]", $field['VoucherPago']);
	$voucherpago = "$aniop$mesp-$codvoucherp$nrovoucherp";
	list($anioo, $meso) = split("[-]", $field['PeriodoVoucherProvision']);
	list($codvouchero, $nrovouchero) = split("[-]", $field['VoucherProvision']);
	$voucherprovision = "$anioo$meso-$codvouchero$nrovouchero";
	$monto_adelanto = $field['MontoAdelanto'] + $field['MontoPagoParcial'];
	$monto_neto = $field['MontoObligacion'] - $monto_adelanto;
	//	---------------
	//	si cambia de proveedor
	if ($grupo != $field['NroCuenta']) {
		$grupo = $field['NroCuenta'];		
		//	imprimo sub-total		
		if ($total_pagos > 1) {
			$pdf->SetFont('Arial', 'B', 6);
			$pdf->Cell(60, 5, 'Total Pagos: '.$sub_total_pagos, 0, 0, 'L');
			$pdf->Cell(75, 5, 'Total Obligaciones No Contabilizadas: '.$sub_total_novoucher, 0, 0, 'R');
			$pdf->Ln(5);
		}
		//	imprimo proveedor
		$pdf->Ln(2);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetDrawColor(255, 255, 255);
		$pdf->SetFont('Arial', 'B', 6);
		$pdf->SetWidths(array(25, 210));
		$pdf->SetAligns(array('L', 'L'));
		$pdf->Row(array($field['NroCuenta'],
						$field['NomCuenta']));
		$sub_total_pagos = 0;
		$sub_total_novoucher = 0;
	}
	$sub_total_pagos++;
	if ($field['VoucherProvision'] != "") $sub_total_novoucher++;
	//	imprimo documento
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(65, 15, 15, 20, 20, 30, 15, 20, 20, 20, 20));
	$pdf->SetAligns(array('L', 'C', 'C', 'C', 'R', 'C', 'C', 'R', 'R', 'R', 'C'));
	$pdf->Row(array($field['NomProveedorPagar'],
					formatFechaDMA($field['FechaPago']),
					$field['NroProceso'],
					$voucherpago,
					number_format($field['MontoPago'], 2, ',', '.'),
					$field['CodTipoDocumento'].'-'.$field['NroDocumento'],
					formatFechaDMA($field['FechaDocumento']),
					number_format($field['MontoObligacion'], 2, ',', '.'),
					number_format($monto_adelanto, 2, ',', '.'),
					number_format($monto_neto, 2, ',', '.'),
					$voucherprovision));
}
//	imprimo sub-total
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(60, 5, 'Total Pagos: '.$sub_total_pagos, 0, 0, 'L');
$pdf->Cell(75, 5, 'Total Obligaciones No Contabilizadas: '.$sub_total_novoucher, 0, 0, 'R');
//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>