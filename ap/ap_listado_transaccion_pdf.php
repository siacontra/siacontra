<?php
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("lib/fphp.php");
//---------------------------------------------------
if ($fCodOrganismo != "") $filtro.=" AND (bt.CodOrganismo = '".$fCodOrganismo."')";
if ($fCodBanco != "") {
	$filtro .= " AND (cb.CodBanco = '".$fCodBanco."')";
	if ($fNroCuenta != "") $filtro .= " AND (bt.NroCuenta = '".$fNroCuenta."')";
}
if ($fFechaTransacciond != "" || $fFechaTransaccionh != "") {
	if ($fFechaTransacciond != "") $filtro.=" AND (bt.FechaTransaccion >= '".formatFechaAMD($fFechaTransacciond)."')";
	if ($fFechaTransaccionh != "") $filtro.=" AND (bt.FechaTransaccion <= '".formatFechaAMD($fFechaTransaccionh)."')";
}
if ($fPeriodoContabled != "" || $fPeriodoContableh != "") {
	if ($fPeriodoContabled != "") $filtro.=" AND (bt.PeriodoContable >= '".($fPeriodoContabled)."')";
	if ($fPeriodoContableh != "") $filtro.=" AND (bt.PeriodoContable <= '".($fPeriodoContableh)."')";
}
if ($fCodTipoTransaccion != "") $filtro.=" AND (bt.CodTipoTransaccion = '".$fCodTipoTransaccion."')";
if ($fEstado != "") $filtro.=" AND (bt.Estado = '".$fEstado."')";
if ($fCodProveedor != "") $filtro.=" AND (bt.CodProveedor = '".$fCodProveedor."')";
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
		$this->SetXY(10, 20); $this->Cell(198, 5, utf8_decode('LISTADO DE TRANSACCIONES'), 0, 1, 'C', 0);
		$this->Ln(5);
		##
		$this->SetFont('Arial', 'B', 6);
		$this->SetWidths(array(15, 10, 5, 55, 20, 20, 12, 12, 30, 20));
		$this->SetAligns(array('C', 'C', 'C', 'L', 'R', 'C', 'C', 'C', 'L', 'C'));
		$this->Row(array('Fecha',
						 'Nro.',
						 '#',
						 utf8_decode('Tipo de Transacción'),
						 'Monto',
						 'Cuenta Bancaria',
						 'Periodo',
						 'Voucher',
						 'Doc. Ref. Banco',
						 'Cheque'));
		$this->Ln(1);
		##
		$this->SetWidths(array(15, 10, 5, 7, 48, 20, 20, 12, 12, 30, 20));
		$this->SetAligns(array('C', 'C', 'C', 'L', 'L', 'R', 'C', 'C', 'C', 'L', 'C'));
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
$sql = "SELECT
			bt.FechaTransaccion,
			bt.NroTransaccion,
			bt.Secuencia,
			bt.CodTipoTransaccion,
			bt.Monto,
			bt.NroCuenta,
			bt.PeriodoContable,
			bt.Voucher,
			bt.CodTipoDocumento,
			bt.CodigoReferenciaBanco,
			bt.NroPago,
			btt.Descripcion AS NomTipoTransaccion
		FROM
			ap_bancotransaccion bt
			INNER JOIN ap_bancotipotransaccion btt ON (btt.CodTipoTransaccion = bt.CodTipoTransaccion)
			INNER JOIN ap_ctabancaria cb ON (bt.NroCuenta = cb.NroCuenta)
		WHERE 1 $filtro
		ORDER BY FechaTransaccion, NroTransaccion, Secuencia";
$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
while ($field = mysql_fetch_array($query)) {
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 6);
	$pdf->Row(array($field['FechaTransaccion'],
					$field['NroTransaccion'],
					$field['Secuencia'],
					$field['CodTipoTransaccion'],
					utf8_decode($field['NomTipoTransaccion']),
					number_format($field['Monto'], 2, ',', '.'),
					$field['NroCuenta'],
					$field['PeriodoContable'],
					$field['Voucher'],
					$field['CodigoReferenciaBanco'],
					$field['NroPago']));
	$pdf->Ln(1);
}
//---------------------------------------------------

//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
