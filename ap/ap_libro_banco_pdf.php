<?php
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("lib/fphp.php");
//---------------------------------------------------
if ($fCodBanco != "") {
	$filtro .= " AND (cb.CodBanco = '".$fCodBanco."')";
	if ($fNroCuenta != "") $filtro .= " AND (bt.NroCuenta = '".$fNroCuenta."')";
}
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
		$this->SetXY(10, 20); $this->Cell(190, 5, utf8_decode('REPORTE DE BANCOS'), 0, 1, 'C', 0);
		##
		$this->SetTextColor(0, 0, 0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		##
		$this->Ln(5);
		$this->SetFont('Arial', 'B', 6);
		$this->SetWidths(array(15, 15, 17, 75, 15, 20, 20, 20));
		$this->SetAligns(array('C', 'C', 'C', 'L', 'C', 'R', 'R', 'R'));
		$this->Row(array('Fecha',
						 'Voucher',
						 'Comprobante',
						 'Concepto',
						 utf8_decode('Transacción'),
						 'Debe',
						 'Haber',
						 'Saldo'));
		$this->Ln(1);
		
	}
	
	//	Pie de página.
	function Footer() {
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(0, 0, 0);
		$this->Rect(10, 270, 197, 0.1, 'DF');
	}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->SetMargins(10, 5, 10);
$pdf->SetAutoPageBreak(5, 10);
$pdf->AddPage();
//---------------------------------------------------
//	consulto

/*
AND 

			bt.FechaTransaccion >= '".formatFechaAMD($fFechaTransacciond)."' AND
			bt.FechaTransaccion <= '".formatFechaAMD($fFechaTransaccionh)."'
*/

 /*echo */ $sql = "SELECT
			cb.CodBanco,
			cb.NroCuenta,
			b.Banco,
			(SELECT SUM(bt2.Monto)
			 FROM ap_bancotransaccion bt2
			 WHERE
			 	bt2.FechaTransaccion < '".formatFechaAMD($fFechaTransacciond)."' AND
				bt2.CodOrganismo = bt.CodOrganismo and bt2.NroCuenta=cb.NroCuenta) AS SaldoCuenta
		FROM
			ap_ctabancaria cb
			INNER JOIN mastbancos b ON (b.CodBanco = cb.CodBanco)
			INNER JOIN ap_bancotransaccion bt ON (bt.NroCuenta = cb.NroCuenta)
		WHERE
			bt.CodOrganismo = '".$fCodOrganismo."'  AND 
			bt.FechaTransaccion >= '".formatFechaAMD($fFechaTransacciond)."' AND
			bt.FechaTransaccion <= '".formatFechaAMD($fFechaTransaccionh)."'
			$filtro
		GROUP BY NroCuenta
		ORDER BY Banco, CodBanco, NroCuenta";
//echo $sql;
$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
while ($field = mysql_fetch_array($query)) {	++$i;
	##	imprimo cuenta bancaria
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(35, 5, utf8_decode('CUENTA. '.$field['NroCuenta']), 0, 0, 'L', 0);
	$pdf->Cell(147, 5, utf8_decode($field['Banco']), 0, 0, 'L', 0);
	$pdf->Cell(20, 5, number_format($field['SaldoCuenta'], 2, ',', '.'), 0, 1, 'R', 0);
	$Saldo = $field['SaldoCuenta'];
	
	##	muestro las transaciones
	$sql = "SELECT
				bt.NroTransaccion,
				bt.Secuencia,
				bt.TipoTransaccion,
				bt.Comentarios,
				bt.FechaTransaccion,
				bt.Voucher,
				bt.NroPago,
				bt.NroTransaccion,
				bt.Monto,
				p.NomCompleto AS NomProveedor
			FROM
				ap_bancotransaccion bt
				INNER JOIN ap_ctabancaria cb ON (bt.NroCuenta = cb.NroCuenta)
				LEFT JOIN mastpersonas p ON (p.CodPersona = bt.CodProveedor)
			WHERE
				bt.NroCuenta = '".$field['NroCuenta']."'  AND 
				bt.FechaTransaccion >= '".formatFechaAMD($fFechaTransacciond)."' AND
				bt.FechaTransaccion <= '".formatFechaAMD($fFechaTransaccionh)."' AND 
				bt.CodOrganismo = '".$fCodOrganismo."'
			ORDER BY FechaTransaccion";
	$query_transacciones = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field_transacciones = mysql_fetch_array($query_transacciones)) {
		if ($field_transacciones['NroPago'] != "") $Concepto = $field_transacciones['NomProveedor']; else $Concepto = $field_transacciones['Comentarios'];
		if ($field_transacciones['Monto'] > 0) {
			$Debe = $field_transacciones['Monto'];
			$Haber = 0;
		} else {
			$Debe = 0;
			$Haber = $field_transacciones['Monto'];
		}
		$TotalDebe += $Debe;
		$TotalHaber += $Haber;
		$Saldo += $field_transacciones['Monto'];
		##
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetDrawColor(255, 255, 255);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetFont('Arial', '', 6);
		$pdf->Row(array(formatFechaDMA($field_transacciones['FechaTransaccion']),
						$field_transacciones['Voucher'],
						$field_transacciones['NroPago'],
						utf8_decode(strtoupper(substr($Concepto, 0, 55))),
						$field_transacciones['NroTransaccion'],
						number_format($Debe, 2, ',', '.'),
						number_format($Haber, 2, ',', '.'),
						number_format($Saldo, 2, ',', '.')));
	}
	##
	$pdf->Ln(1);
	##
	$pdf->SetDrawColor(0, 0, 0);
	$pdf->SetFillColor(0, 0, 0);
	$y = $pdf->GetY();
	$pdf->Rect(149, $y, 18, 0.1, 'DF');
	$pdf->Rect(169, $y, 18, 0.1, 'DF');
	$pdf->Rect(189, $y, 18, 0.1, 'DF');
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(137, 5, 'TOTAL. ', 0, 0, 'R', 0);
	$pdf->Cell(20, 5, number_format($TotalDebe, 2, ',', '.'), 0, 0, 'R', 0);
	$pdf->Cell(20, 5, number_format($TotalHaber, 2, ',', '.'), 0, 0, 'R', 0);
	$pdf->Cell(20, 5, number_format($Saldo, 2, ',', '.'), 0, 0, 'R', 0);
	$pdf->Ln(8);
	$TotalDebe = 0;
	$TotalHaber = 0;
}
//---------------------------------------------------

//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
