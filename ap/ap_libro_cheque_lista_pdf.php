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
		$this->SetXY(225, 5); $this->Cell(20, 5, utf8_decode('Fecha: '), 0, 0, 'L');
		$this->Cell(30, 5, formatFechaDMA(substr($Ahora, 0, 10)), 0, 1, 'L');
		$this->SetXY(225, 10); $this->Cell(20, 5, utf8_decode('Página: '), 0, 0, 'L'); 
		$this->Cell(30, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		$this->SetFont('Arial', 'B', 10);
		$this->SetXY(10, 20); $this->Cell(260, 5, utf8_decode('LIBRO DE CHEQUES'), 0, 1, 'C', 0);
		##
		$this->SetTextColor(0, 0, 0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		##
		$this->Ln(5);
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(80, 5, utf8_decode('Cta. Bancaria. '.$field['NroCuenta'].' '.$field['Banco']), 0, 1, 'L', 0);
		$this->Cell(80, 5, utf8_decode('Cta. Contable. '.$field['CodCuenta']), 0, 1, 'L', 0);
		$this->Ln(2);
		##
		$this->SetFont('Arial', 'B', 6);
		$this->SetWidths(array(15, 15, 75, 20, 15, 15, 15, 15, 15, 15, 15, 15, 8, 8));
		$this->SetAligns(array('C', 'C', 'L', 'R', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
		$this->Row(array('Nro. Cheque',
						 'Fecha',
						 'Pagar A',
						 'Monto',
						 'Voucher Pago',
						 utf8_decode('Voucher Anulación'),
						 'Estado Documento',
						 'Estado Entrega',
						 'Fecha Entrega',
						 'Fecha Cobranza',
						 'Fecha Dif. Original',
						 'Pre-Pago',
						 'Dif.',
						 'Cob.'));
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
while ($field = mysql_fetch_array($query)) {
	$g = "$field[CodBanco].$field[NroCuenta]";
	##
	if ($grupo != $g) {
		$grupo = $g;
		$pdf->AddPage();
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
					$field['VoucherAnulacion'],
					printValores("ESTADO-PAGO", $field['Estado']),
					printValores("ESTADO-CHEQUE", $field['EstadoEntrega']),
					formatFechaDMA($field['FechaEntregado']),
					formatFechaDMA($field['FechaCobranza']),
					formatFechaDMA($field['FechaEmisionDiferido']),
					$field['NroProceso'],
					printValoresGeneral("FLAG", $field['FlagPagoDiferido']),
					printValoresGeneral("FLAG", $field['FlagCobrado'])));
}
//---------------------------------------------------

//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
