<?php
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("lib/fphp.php");
//---------------------------------------------------
if ($fCodOrganismo != "") $filtro.=" AND (op.CodOrganismo = '".$fCodOrganismo."')";
if ($fCodProveedor != "") $filtro.=" AND (op.CodProveedor = '".$fCodProveedor."')";
if ($fCodSistemaFuente != "") $filtro.=" AND (op.CodSistemaFuente = '".$fCodSistemaFuente."')";
if ($fCodTipoDocumento != "") $filtro.=" AND (op.CodTipoDocumento = '".$fCodTipoDocumento."')";
if ($fNroDocumento != "") $filtro.=" AND (op.NroDocumento LIKE '%".$fNroDocumento."%')";
if ($fEstado != "") $filtro.=" AND (op.Estado = '".$fEstado."')";
if ($fFechaOrdenPagod != "" || $fFechaOrdenPagoh != "") {
	if ($fFechaOrdenPagod != "") $filtro.=" AND (op.FechaOrdenPago >= '".formatFechaAMD($fFechaOrdenPagod)."')";
	if ($fFechaOrdenPagoh != "") $filtro.=" AND (op.FechaOrdenPago <= '".formatFechaAMD($fFechaOrdenPagoh)."')";
}
if ($FlagPagoDiferido == "S") { $filtro.=" AND (op.FlagPagoDiferido = 'S')"; }
if ($fCodBanco != "") {
	$filtro.= " AND (cb.CodBanco = '".$fCodBanco."')";
	if ($fNroCuenta != "") $filtro.= " AND (op.NroCuenta = '".$fNroCuenta."')";
}
if ($fMontoTotald != "" || $fMontoTotalh != "") {
	if ($fMontoTotald != "") $filtro.=" AND (op.MontoTotal >= ".setNumero($fMontoTotald).")";
	if ($fMontoTotalh != "") $filtro.=" AND (op.MontoTotal <= ".setNumero($fMontoTotalh).")"; 
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
		$this->SetFont('Arial', '', 10);
		$this->SetXY(20, 5); $this->Cell(100, 5, $NomOrganismo, 0, 1, 'L');
		$this->SetXY(20, 10); $this->Cell(100, 5, $NomDependencia, 0, 0, 'L');	
		$this->SetFont('Arial', '', 10);
		$this->SetXY(165, 5); $this->Cell(20, 5, utf8_decode('Fecha: '), 0, 0, 'L');
		$this->Cell(30, 5, formatFechaDMA(substr($Ahora, 0, 10)), 0, 1, 'L');
		$this->SetXY(165, 10); $this->Cell(20, 5, utf8_decode('Página: '), 0, 0, 'L'); 
		$this->Cell(30, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		$this->SetFont('Arial', 'B', 12);		
		$this->SetXY(10, 20); $this->Cell(195, 5, utf8_decode('ORDENES DE PAGO POR PERIODO'), 0, 1, 'C', 0);
		##
		$this->Ln(5);
		$this->SetFont('Arial', 'B', 8);
		$this->SetWidths(array(20, 20, 20, 110, 30));
		$this->SetAligns(array('C', 'C', 'C', 'L', 'R'));
		$this->Row(array('Nro. Orden',
						 'Fecha',
						 'Nro. Cheque',
						 'Beneficiario',
						 'Monto Bs.'));
		$this->Ln(1);
		if ($_sub == 2) {
			$this->SetWidths(array(5, 20, 145, 30));
			$this->SetAligns(array('C', 'C', 'L', 'R'));
		}
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
			op.Anio,
			op.CodOrganismo,
			op.NroOrden,
			op.FechaOrdenPago,
			op.NroPago,
			op.NomProveedorPagar,
			op.MontoTotal
		FROM ap_ordenpago op
		WHERE 1 $filtro
		ORDER BY $fordenar";
$query_orden = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
while ($field_orden = mysql_fetch_array($query_orden)) {
	##	imprimo orden
	$_sub = 1;
	if ($verDistribucion == "S") $pdf->SetFont('Arial', 'B', 8); else $pdf->SetFont('Arial', '', 8);
	$pdf->SetWidths(array(20, 20, 20, 110, 30));
	$pdf->SetAligns(array('C', 'C', 'C', 'L', 'R'));
	$pdf->Row(array($field_orden['NroOrden'],
					formatFechaDMA($field_orden['FechaOrdenPago']),
					$field_orden['NroPago'],
					utf8_decode($field_orden['NomProveedorPagar']),
					number_format($field_orden['MontoTotal'], 2, ',', '.')));
	$MontoTotal += $field_orden['MontoTotal'];
	##	muestro distribucion
	if ($verDistribucion == "S") {
		$sql = "SELECT
					opd.cod_partida,
					opd.Monto,
					p.denominacion
				FROM
					ap_ordenpagodistribucion opd
					INNER JOIN pv_partida p ON (p.cod_partida = opd.cod_partida)
				WHERE
					opd.Anio = '".$field_orden['Anio']."' AND
					opd.CodOrganismo = '".$field_orden['CodOrganismo']."' AND
					opd.NroOrden = '".$field_orden['NroOrden']."'";
		$query_distribucion = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		while ($field_distribucion = mysql_fetch_array($query_distribucion)) {
			##	imprimo distribucion
			$_sub = 2;
			$pdf->SetFont('Arial', '', 8);
			$pdf->SetWidths(array(5, 20, 145, 30));
			$pdf->SetAligns(array('C', 'C', 'L', 'R'));
			$pdf->Row(array('',
							$field_distribucion['cod_partida'],
							utf8_decode($field_distribucion['denominacion']),
							number_format($field_distribucion['Monto'], 2, ',', '.')));
		}
		$pdf->Ln(2);
	}
	$pdf->Ln(2);
}
$pdf->Ln(1);
//	imprimo total
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(0, 0, 0);
$y = $pdf->GetY();
$pdf->Rect(10, $y, 200, 0.1, "FD");
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(170, 5, 'Total: ', 0, 0, 'R');
$pdf->Cell(30, 5, number_format($MontoTotal, 2, ',', '.'), 0, 0, 'R');
//---------------------------------------------------

//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
