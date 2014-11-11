<?php
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("lib/fphp.php");
//---------------------------------------------------
//	consulto resumen
$sql = "SELECT *
		FROM rh_bonoalimentacion
		WHERE
			CodOrganismo = '".$fCodOrganismo."' AND
			CodTipoNom = '".$fCodTipoNom."' AND
			CodBonoAlim = '".$fPeriodo."'";
$query_resumen = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query_resumen) != 0) $field_resumen = mysql_fetch_array($query_resumen);
//---------------------------------------------------
$filtro = "";
$tituloNomina=""; 
if ($fCodOrganismo != "") $filtro .= " AND (bad.CodOrganismo = '".$fCodOrganismo."')";
if ($fCodDependencia != "") $filtro .= " AND (e.CodDependencia = '".$fCodDependencia."')";
if ($fCodCentroCosto != "") $filtro .= " AND (e.CodCentroCosto = '".$fCodCentroCosto."')";
if ($fCodTipoNom != "") {
	$filtro .= " AND (ba.CodTipoNom = '".$fCodTipoNom."')"; 
//	consulto tipo de nomina ( Se puede optimizar)
$sql_nomina = "SELECT
tiponomina.CodTipoNom,
tiponomina.Nomina,
tiponomina.TituloBoleta
FROM
tiponomina

WHERE 
CodTipoNom = '".$fCodTipoNom."'";
$query_tiponomina = mysql_query($sql_nomina) or die(getErrorSql(mysql_errno(), mysql_error(), $sql_nomina));


if (mysql_num_rows($query_tiponomina) != 0) $field_tiponomina = mysql_fetch_array($query_tiponomina);
$tituloNomina =  " ".$field_tiponomina['TituloBoleta'];
	
}
if ($fCodPerfil != "") $filtro .= " AND (e.CodPerfil = '".$fCodPerfil."')";
if ($fEdoReg != "") $filtro .= " AND (p.Estado = '".$fEdoReg."')";
if ($fSitTra != "") $filtro .= " AND (e.Estado = '".$fSitTra."')";
if ($fBuscar != "") $filtro .= " AND (e.CodEmpleado LIKE '%".$fBuscar."%' OR
									  p.NomCompleto LIKE '%".$fBuscar."%' OR
									  p.Ndocumento LIKE '%".$fBuscar."%')";
if ($fCodCargo != "") {
	$inner_cargo = "INNER JOIN rh_cargoreporta cr ON (cr.CodCargo = e.CodCargo AND
													  cr.CargoReporta = '".$fCodCargo."')";
}
//---------------------------------------------------

class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
		global $tituloNomina;
		global $_PARAMETRO;
		global $Ahora;
		global $field_resumen;
		global $FechaInicio;
		global $FechaFin;
		global $_POST;
		extract($_POST);
		##
		$Logo = getValorCampo("mastorganismos", "CodOrganismo", "Logo", $fCodOrganismo);
		$NomOrganismo = getValorCampo("mastorganismos", "CodOrganismo", "Organismo", $fCodOrganismo);
		$NomDependencia = getValorCampo("mastdependencias", "CodDependencia", "Dependencia", $_PARAMETRO["DEPRHPR"]);
		##
		$this->SetFillColor(255, 255, 255);
		$this->SetDrawColor(0, 0, 0);
		$this->Image($_PARAMETRO["PATHLOGO"].$Logo, 10, 5, 10, 10);
		$this->SetFont('Arial', '', 8);
		$this->SetXY(20, 5); $this->Cell(100, 5, $NomOrganismo, 0, 1, 'L');
		$this->SetXY(20, 10); $this->Cell(100, 5, $NomDependencia, 0, 0, 'L');
		$this->SetFont('Arial', '', 8);
		$this->SetXY(180, 5); $this->Cell(10, 5, utf8_decode('Fecha: '), 0, 0, 'L');
		$this->Cell(30, 5, formatFechaDMA(substr($Ahora, 0, 10)), 0, 1, 'L');
		$this->SetXY(180, 10); $this->Cell(10, 5, utf8_decode('Página: '), 0, 0, 'L');
		$this->Cell(30, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		$this->SetFont('Arial', 'B', 10);
		$this->SetXY(10, 20); $this->Cell(190, 5, utf8_decode('Resumen General: '.$tituloNomina), 0, 1, 'C', 0);
		$this->SetFont('Arial', 'BI', 8);
		$this->SetXY(10, 24); $this->Cell(190, 5, utf8_decode('Periodo del '.formatFechaDMA($field_resumen['FechaInicio']).' al '.formatFechaDMA($field_resumen['FechaFin'])), 0, 1, 'C', 0);
		$this->Ln(5);
		##
	}
	
	//	Pie de página.
	function Footer() {}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->SetMargins(5, 5, 5);
$pdf->SetAutoPageBreak(5, 5);
$pdf->AddPage();
//---------------------------------------------------
//	consulto
$sql = "SELECT
			bad.*,
			(bad.DiasPago - bad.DiasDescuento) AS DiasTrabajados,
			p.NomCompleto,
			e.CodEmpleado
		FROM
			rh_bonoalimentaciondet bad
			INNER JOIN rh_bonoalimentacion ba ON (ba.Anio = bad.Anio AND
												  ba.CodOrganismo = bad.CodOrganismo AND
												  ba.CodBonoAlim = bad.CodBonoAlim)
			INNER JOIN mastpersonas p ON (p.CodPersona = bad.CodPersona)
			INNER JOIN mastempleado e ON (e.CodPersona = p.CodPersona)
			$inner_cargo
		WHERE
			bad.Anio = '".$field_resumen['Anio']."' AND
			bad.CodOrganismo = '".$field_resumen['CodOrganismo']."' AND
			bad.CodBonoAlim = '".$field_resumen['CodBonoAlim']."' $filtro
		GROUP BY CodPersona
		ORDER BY CodEmpleado";
$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
while ($field = mysql_fetch_array($query)) {
	//	imprimo empleado
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetFont('Arial', 'BU', 8);
	$pdf->Ln(5);
	$pdf->Cell(12, 5, $field['CodEmpleado'], 0, 0, 'L', 0);
	$pdf->Cell(170, 5, utf8_decode($field['NomCompleto']), 0, 0, 'L', 0);
	$pdf->Ln(5);
	
	//	imprimo detalle
	##	labels
	$w = 206 / $field['DiasPeriodo'];
	$f = formatFechaDMA($field_resumen['FechaInicio']);
	for($i=1;$i<=$field['DiasPeriodo'];$i++) {
		list($d, $m, $a) = split("[-./]", $f);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetDrawColor(0, 0, 0);
		$pdf->SetFillColor(200, 200, 200);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell($w, 5, $d, 1, 0, 'C', 1);
		$f = obtenerFechaFin($f, 2);
	}
	$pdf->Ln(5);
	##	valores
	$pdf->SetFillColor(255, 255, 255);
	for($i=1;$i<=$field['DiasPeriodo'];$i++) {
		$id = "Dia".$i;
		if ($field[$id] == "X") $pdf->SetTextColor(0, 153, 0);
		elseif ($field[$id] == "D") $pdf->SetTextColor(128, 0, 0);
		else $pdf->SetTextColor(0, 0, 0);
		$pdf->Cell($w, 5, $field[$id], 1, 0, 'C', 1);
	}
	$pdf->Ln(7);
	
	//	imprimo resumen
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetDrawColor(0, 0, 0);
	$pdf->SetFillColor(240, 240, 240);
	$pdf->SetWidths(array(25, 25, 25, 25, 25, 25, 28, 28));
	$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Row(array('TOTAL',
					'DIAS HABILES',
					'DIAS FERIADOS',
					'DIAS INACTIVOS',
					'DIAS TRABAJADOS',
					'DIAS DESCUENTO',
					'VALOR A DESCONTAR',
					'VALOR A PAGAR'));
	$pdf->SetFillColor(255, 255, 255);
	
	$pdf->Row(array($field['DiasPeriodo'],
					$field['DiasPeriodo']-($field['DiasFeriados']+$field['DiasInactivos']),//$field['DiasPago'],
					$field['DiasFeriados'],
					$field['DiasInactivos'],
					$field['DiasPeriodo']-($field['DiasFeriados']+$field['DiasInactivos']+$field['DiasDescuento']),//$field['DiasTrabajados'],
					$field['DiasDescuento'],
					number_format($field_resumen['ValorDia']*$field['DiasDescuento'], 2, ',', '.'),
					number_format($field_resumen['ValorDia']*($field['DiasTrabajados']-$field['DiasInactivos']), 2, ',', '.')));
}
//---------------------------------------------------

//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>
