<?php
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("lib/fphp.php");
//---------------------------------------------------
if ($fCodOrganismo != "") $filtro.=" AND (pa.CodOrganismo = '".$fCodOrganismo."')";
if ($fFechaEntregadod != "" || $fFechaEntregadoh != "") {
	if ($fFechaEntregadod != "") $filtro.=" AND (pa.FechaEntregado >= '".formatFechaAMD($fFechaEntregadod)."')";
	if ($fFechaEntregadoh != "") $filtro.=" AND (pa.FechaEntregado <= '".formatFechaAMD($fFechaEntregadoh)."')";
}
if ($fCodBanco != "") {
	$filtro.= " AND (cb.CodBanco = '".$fCodBanco."')";
	if ($fNroCuenta != "") $filtro.= " AND (pa.NroCuenta = '".$fNroCuenta."')";
}
if ($fFechaCobranzad != "" || $fFechaCobranzah != "") {
	if ($fFechaCobranzad != "") $filtro.=" AND (pa.FechaCobranza >= '".formatFechaAMD($fFechaCobranzad)."')";
	if ($fFechaCobranzah != "") $filtro.=" AND (pa.FechaCobranza <= '".formatFechaAMD($fFechaCobranzah)."')";
}
if ($fFechaEmisionDiferidod != "" || $fFechaEmisionDiferidoh != "") {
	if ($fFechaEmisionDiferidod != "") $filtro.=" AND (pa.FechaEmisionDiferido >= '".formatFechaAMD($fFechaEmisionDiferidod)."')";
	if ($fFechaEmisionDiferidoh != "") $filtro.=" AND (pa.FechaEmisionDiferido <= '".formatFechaAMD($fFechaEmisionDiferidoh)."')";
}
if ($fFechaPagod != "" || $fFechaPagoh != "") {
	if ($fFechaPagod != "") $filtro.=" AND (pa.FechaPago >= '".formatFechaAMD($fFechaPagod)."')";
	if ($fFechaPagoh != "") $filtro.=" AND (pa.FechaPago <= '".formatFechaAMD($fFechaPagoh)."')";
}
if ($fMontoPagod != "" || $fMontoPagoh != "") {
	if ($fMontoPagod != "") $filtro.=" AND (pa.MontoPago >= ".setNumero($fMontoPagod).")";
	if ($fMontoPagoh != "") $filtro.=" AND (pa.MontoPago <= ".setNumero($fMontoPagoh).")"; 
}
if ($fPeriodo != "") $filtro.=" AND (pa.Periodo = '".$fPeriodo."')";
if ($fCodTipoPago != "") $filtro.=" AND (pa.CodTipoPago = '".$fCodTipoPago."')";
if ($fEstadoEntrega != "") $filtro.=" AND (pa.EstadoEntrega = '".$fEstadoEntrega."')";
if ($fEstado != "") $filtro.=" AND (pa.Estado = '".$fEstado."')";
if ($fFlagCobrado != "") $filtro.=" AND (pa.FlagCobrado = '".$fFlagCobrado."')";
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
		$this->SetXY(165, 5); $this->Cell(20, 5, utf8_decode('Fecha: '), 0, 0, 'L');
		$this->Cell(30, 5, formatFechaDMA(substr($Ahora, 0, 10)), 0, 1, 'L');
		$this->SetXY(165, 10); $this->Cell(20, 5, utf8_decode('Página: '), 0, 0, 'L'); 
		$this->Cell(30, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		$this->SetFont('Arial', 'B', 10);
		$this->SetXY(10, 20); $this->Cell(198, 5, utf8_decode('LIBRO DE CHEQUES'), 0, 1, 'C', 0);
		##
		$this->SetTextColor(0, 0, 0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		##
		$this->Ln(5);
		$this->SetFont('Arial', 'B', 6);
		$this->SetWidths(array(15, 15, 88, 20, 15, 15, 15, 15));
		$this->SetAligns(array('C', 'C', 'L', 'R', 'C', 'C', 'C', 'C'));
		$this->Row(array('Nro. Cheque',
						 'Fecha',
						 'Pagar A',
						 'Monto',
						 'Voucher Pago',
						 'Estado Documento',
						 'Estado Entrega',
						 'Fecha Entrega'));
		$this->Ln(1);
		
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
$pdf->SetAutoPageBreak(5, 5);
$pdf->AddPage();
//---------------------------------------------------
//	consulto
$sql = "SELECT
			pa.CodOrganismo,
			pa.NroPago,
			pa.FechaPago,
			pa.NomProveedorPagar,
			pa.MontoPago,
			pa.Periodo,
			pa.VoucherPago,
			pa.VoucherAnulacion,
			pa.PeriodoAnulacion,
			pa.Estado,
			pa.EstadoEntrega,
			pa.FechaEntregado,
			pa.FechaCobranza,
			pa.FechaEmisionDiferido,
			pa.NroProceso,
			pa.FlagPagoDiferido,
			pa.FlagCobrado,
			pa.NroCuenta,
			cb.CodBanco,
			cb.CodCuenta,
			b.Banco
		FROM
			ap_pagos pa
			INNER JOIN ap_ctabancaria cb ON (cb.Nrocuenta = pa.NroCuenta)
			INNER JOIN mastbancos b ON (b.CodBanco = cb.CodBanco)
		WHERE 1 $filtro
		ORDER BY CodOrganismo, Banco, CodBanco, NroCuenta, $fordenar";
$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
while ($field = mysql_fetch_array($query)) {	++$i;
	$TotalCheques++;
	$TotalCuenta += $field['MontoPago'];
	$g = "$field[CodBanco].$field[NroCuenta]";
	##
	if ($grupo != $g) {
		$grupo = $g;
		if ($i > 1) {
			$pdf->SetDrawColor(0, 0, 0);
			$pdf->SetFillColor(0, 0, 0);
			$y = $pdf->GetY();
			$pdf->Rect(128, $y, 20, 0.1, 'DF');
			$pdf->Rect(128, $y+0.5, 20, 0.1, 'DF');
			$pdf->SetFont('Arial', 'B', 7);
			$pdf->Cell(30, 5, utf8_decode('Total Cheques.       '.$TotalCheques), 0, 0, 'L', 0);
			$pdf->Cell(88, 5, 'Total Cta. Bancaria. ', 0, 0, 'R', 0);
			$pdf->Cell(20, 5, number_format($TotalCuenta, 2, ',', '.'), 0, 0, 'R', 0);
			$pdf->Ln(8);
			$TotalCheques = 0;
		}
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetDrawColor(255, 255, 255);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(50, 5, utf8_decode('Cta. Bancaria. '.$field['NroCuenta']), 0, 0, 'L', 0);
		$pdf->Cell(80, 5, utf8_decode($field['Banco']), 0, 1, 'L', 0);
	}
	##
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 6);
	$pdf->Row(array($field['NroPago'],
					formatFechaDMA($field['FechaPago']),
					utf8_decode($field['NomProveedorPagar']),
					number_format($field['MontoPago'], 2, ',', '.'),
					$field['VoucherPago'],
					printValores("ESTADO-PAGO", $field['Estado']),
					printValores("ESTADO-CHEQUE", $field['EstadoEntrega']),
					formatFechaDMA($field['FechaEntregado'])));
}
##	
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(0, 0, 0);
$y = $pdf->GetY();
$pdf->Rect(128, $y, 20, 0.1, 'DF');
$pdf->Rect(128, $y+0.5, 20, 0.1, 'DF');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(30, 5, utf8_decode('Total Cheques.       '.$TotalCheques), 0, 0, 'L', 0);
$pdf->Cell(88, 5, 'Total Cta. Bancaria. ', 0, 0, 'R', 0);
$pdf->Cell(20, 5, number_format($TotalCuenta, 2, ',', '.'), 0, 0, 'R', 0);
//---------------------------------------------------

//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
