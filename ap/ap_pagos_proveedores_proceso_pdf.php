<?php
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("lib/fphp.php");
//---------------------------------------------------
if ($fCodOrganismo != "") $filtro.=" AND (pa.CodOrganismo = '".$fCodOrganismo."')";
if ($fCodProveedor != "") $filtro.=" AND (pa.CodProveedor = '".$fCodProveedor."')";
if ($fCodBanco != "") {
	//$filtro.= " AND (cb.CodBanco = '".$fCodBanco."')";
	if ($fNroCuenta != "") $filtro.= " AND (pa.NroCuenta = '".$fNroCuenta."')";
}
//---------------------------------------------------

class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
		global $_PARAMETRO;
		global $Ahora;
		global $_sub;
		global $_POST;
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
		$this->SetXY(10, 20); $this->Cell(195, 5, utf8_decode('PAGOS A PROVEEDORES X PROCESO'), 0, 1, 'C', 0);
		##
		$this->Ln(5);
		$this->SetFont('Arial', 'B', 6);
		$this->SetWidths(array(5, 30, 30, 20, 20, 95));
		$this->SetAligns(array('C', 'L', 'L', 'R', 'C', 'L'));
		$this->Row(array('#',
						 'Proveedor',
						 'Nro. Documento',
						 'Monto',
						 'Fecha Doc.',
						 utf8_decode('Descricpión')));
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
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(255, 255, 255);
$pdf->SetFillColor(255, 255, 255);
//---------------------------------------------------
//	consulto
$sql = "SELECT
			pa.NroProceso,
			pa.Secuencia,
			pa.NroCuenta,
			pa.NomProveedorPagar,
			pa.CodProveedor,
			pa.MontoPago,
			o.FechaDocumento,
			o.CodTipoDocumento,
			o.NroDocumento,
			o.Comentarios,
			p.DocFiscal,
			tp.TipoPago
		FROM
			ap_pagos pa
			INNER JOIN ap_obligaciones o ON (o.NroProceso = pa.NroProceso)
			INNER JOIN mastpersonas p ON (p.CodPersona = pa.CodProveedor)
			INNER JOIN masttipopago tp ON (tp.CodTipoPago = pa.CodTipoPago)
		WHERE pa.NroProceso = '".$fNroProceso."' $filtro
		ORDER BY NroCuenta, NroProceso, Secuencia";
$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
while ($field = mysql_fetch_array($query)) {
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(50, 5, 'Nro. Proceso '.$field['NroProceso'], 1, 1, 'L');
	$pdf->Cell(50, 5, 'Cta. Bancaria '.$field['NroCuenta'], 1, 1, 'L');
	##	imprimo proveedor
	if ($grupo != $field['CodProveedor']) {
		$grupo = $field['CodProveedor'];
		$_sub = 1;
		$i = 0;
		$pdf->SetFont('Arial', 'B', 6);
		$pdf->SetWidths(array(65, 20, 20, 65));
		$pdf->SetAligns(array('L', 'R', 'L', 'L'));
		$pdf->Row(array($field['NomProveedorPagar'],
						number_format($field['MontoPago'], 2, ',', '.'),
						$field['TipoPago'],
						'Documento. '.$field['DocFiscal']));
	}
	##	imprimo documento
	$_sub = 2;
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(5, 30, 30, 20, 20, 90));
	$pdf->SetAligns(array('C', 'L', 'L', 'R', 'C', 'L'));
	$pdf->Row(array(++$i,
					$field['DocFiscal'],
					$field['CodTipoDocumento'].'-'.$field['NroDocumento'],
					number_format($field['MontoPago'], 2, ',', '.'),
					formatFechaDMA($field['FechaDocumento']),
					$field['Comentarios']));
	$pdf->Ln(2);
	##
	$Total += $field['MontoPago'];
}
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(65, 5, 'Total', 0, 0, 'R');
$pdf->Cell(20, 5, number_format($Total, 2, ',', '.'), 1, 1, 'R');

//---------------------------------------------------

//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
