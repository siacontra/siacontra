<?php
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("lib/fphp.php");
//---------------------------------------------------
if ($fCodOrganismo != "") $filtro .= " AND (e.CodOrganismo = '".$fCodOrganismo."')";
if ($fCodDependencia != "") $filtro .= " AND (e.CodDependencia = '".$fCodDependencia."')";
if ($fCodPersona != "") $filtro .= " AND (vp.CodPersona = '".$fCodPersona."')";
if ($fBuscar != "") $filtro .= " AND (e.CodEmpleado LIKE '%".$fBuscar."%' OR
									  p.NomCompleto LIKE '%".$fBuscar."%')";
if ($fEstado != "") $filtro .= " AND (e.Estado = '".$fEstado."')";
//---------------------------------------------------

class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
		global $_PARAMETRO;
		global $Ahora;
		global $field;
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
		$this->SetXY(10, 20); $this->Cell(190, 5, utf8_decode('Resumen General de Vacaciones'), 0, 1, 'C', 0);
		$this->Ln(5);
		##
		$this->SetDrawColor(0, 0, 0);
		$this->SetWidths(array(30, 10, 15, 25, 25, 25, 25, 25));
		$this->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(10, 5);
		$this->Row(array('Periodo',
						 'Mes Prog.',
						 'Derecho',
						 'Pendiente Ant.',
						 'Dias Solicitud',
						 utf8_decode('Interrupción'),
						 'Total Utilizado',
						 'Pendientes'));
		$this->Ln(1);
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
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFillColor(255, 255, 255);
//	consulto
$sql = "SELECT
			vp.CodPersona,
			vp.NroPeriodo,
			vp.Mes,
			vp.CodTipoNom,
			vp.Anio,
			vp.Derecho,
			vp.PendientePeriodo,
			vp.DiasGozados,
			vp.DiasInterrumpidos,
			(vp.DiasGozados - vp.DiasInterrumpidos) AS TotalUtilizados,
			vp.Pendientes,
			e.CodEmpleado,
			p.NomCompleto
		FROM
			rh_vacacionperiodo vp
			INNER JOIN mastempleado e ON (e.CodPersona = vp.CodPersona)
			INNER JOIN mastpersonas p ON (p.CodPersona = e.CodPersona)
		WHERE 1 $filtro
		ORDER BY CodEmpleado, Anio";
$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
while ($field = mysql_fetch_array($query)) {	++$i;
	if ($Grupo != $field['CodEmpleado']) {
		$Grupo = $field['CodEmpleado'];
		$pdf->SetDrawColor(255, 255, 255);
		$pdf->SetFont('Arial', 'BU', 8);
		$pdf->Ln(5);
		$pdf->Cell(12, 5, $field['CodEmpleado'], 0, 0, 'L', 0);
		$pdf->Cell(170, 5, utf8_decode($field['NomCompleto']), 0, 0, 'L', 0);
		$pdf->Ln(5);
	}
	##
	$Periodo = "$field[Anio] - ".($field['Anio'] + 1);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(10, 5);
	$pdf->Row(array($Periodo,
					$field['Mes'],
					number_format($field['Derecho'], 0, ',', '.'),
					number_format($field['PendientePeriodo'], 0, ',', '.'),
					number_format($field['DiasGozados'], 0, ',', '.'),
					number_format($field['DiasInterrumpidos'], 0, ',', '.'),
					number_format($field['TotalUtilizados'], 0, ',', '.'),
					number_format($field['Pendientes'], 0, ',', '.')));
	##
	if ($FlagDetalle == "S") {
		$sql = "SELECT
					FechaInicio,
					FechaFin,
					TipoUtilizacion,
					DiasUtiles
				FROM rh_vacacionutilizacion
				WHERE
					CodPersona = '".$field['CodPersona']."' AND
					NroPeriodo = '".$field['NroPeriodo']."' AND
					CodTipoNom = '".$field['CodTipoNom']."'
				ORDER BY Secuencia";
		$query_detalle = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		while ($field_detalle = mysql_fetch_array($query_detalle)) {
			$pdf->SetFont('Arial', 'BI', 8);
			$pdf->Cell(35, 5);
			$pdf->Cell(20, 5, printValores("TIPO-VACACIONES", $field_detalle['TipoUtilizacion']), 0, 0, 'L');
			$pdf->Cell(20, 5, number_format($field_detalle['DiasUtiles'], 0, ',', '.').' dias', 0, 0, 'C');
			$pdf->Cell(50, 5, 'Del   '.formatFechaDMA($field_detalle['FechaInicio']).'   Al   '.formatFechaDMA($field_detalle['FechaFin']), 0, 0, 'C');
			$pdf->Ln(5);
		}
		$pdf->Ln(3);
	}
}
//---------------------------------------------------

//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>