<?php
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("lib/fphp.php");
//---------------------------------------------------
if ($fCodOrganismo != "") $filtro .= " AND (vs.CodOrganismo = '".$fCodOrganismo."')";
if ($fCodDependencia != "") $filtro .= " AND (vs.CodDependencia = '".$fCodDependencia."')";
if ($fCodPersona != "") $filtro .= " AND (vs.CodPersona = '".$fCodPersona."')";
if ($fEstado != "") $filtro .= " AND (vs.Estado = '".$fEstado."')";
if ($fBuscar != "") $filtro .= " AND (e.CodEmpleado LIKE '%".$fEstado."%' OR
									  p.NomCompleto LIKE '%".$fEstado."%' OR
									  CONCAT(vs.Anio, '-', vs.CodSolicitud) LIKE '%".$fEstado."%' OR
									  vs.Tipo LIKE '%".$fEstado."%')";
if ($fFechaD != "" || $fFechaH != "") {
	if ($fFechaD != "") $filtro .= " AND ('".formatFechaAMD($fFechaD)."' >= vs.FechaSalida AND '".formatFechaAMD($fFechaD)."' <= vs.FechaTermino)";
	if ($fFechaH != "") $filtro .= " AND ('".formatFechaAMD($fFechaH)."' >= vs.FechaSalida AND '".formatFechaAMD($fFechaH)."' <= vs.FechaTermino)";
}
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
		$this->SetXY(165, 5); $this->Cell(10, 5, utf8_decode('Fecha: '), 0, 0, 'L');
		$this->Cell(30, 5, formatFechaDMA(substr($Ahora, 0, 10)), 0, 1, 'L');
		$this->SetXY(165, 10); $this->Cell(10, 5, utf8_decode('Página: '), 0, 0, 'L');
		$this->Cell(30, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		$this->SetFont('Arial', 'B', 10);
		$this->SetXY(10, 20); $this->Cell(190, 5, utf8_decode('Listado de Solicitud de Vacaciones'), 0, 1, 'C', 0);
		$this->Ln(5);
		##
		$this->SetDrawColor(0, 0, 0);
		$this->SetWidths(array(25, 15, 25, 25, 25, 15, 25));
		$this->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C'));
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(10, 5);
		$this->Row(array('Solicitud',
						 'Tipo',
						 'Fecha',
						 'Fecha Salida',
						 'Fecha Termino',
						 'Dias',
						 'Incorporacion'));
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
			vs.CodPersona,
			vs.Anio,
			vs.CodSolicitud,
			vs.Tipo,
			vs.Fecha,
			vs.FechaSalida,
			vs.FechaTermino,
			vs.NroDias,
			vs.FechaIncorporacion,
			e.CodEmpleado,
			p.NomCompleto
		FROM
			rh_vacacionsolicitud vs
			INNER JOIN mastempleado e ON (e.CodPersona = vs.CodPersona)
			INNER JOIN mastpersonas p ON (p.CodPersona = e.CodPersona)
		WHERE 1 $filtro
		ORDER BY CodPersona, Anio, CodSolicitud";	//die($sql);
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
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(10, 5);
	$pdf->Row(array($field['Anio'].'-'.$field['CodSolicitud'],
					$field['Tipo'],
					formatFechaDMA($field['Fecha']),
					formatFechaDMA($field['FechaSalida']),
					formatFechaDMA($field['FechaTermino']),
					$field['NroDias'],
					formatFechaDMA($field['FechaIncorporacion'])));
	##
	if ($FlagDetalle == "S") {
		$sql = "SELECT
					vsd.*,
					vp.Anio As AnioPeriodo
				FROM
					rh_vacacionsolicituddetalle vsd
					INNER JOIN rh_vacacionperiodo vp ON (vp.CodPersona = vsd.CodPersona AND
														 vp.NroPeriodo = vsd.NroPeriodo)
				WHERE
					vsd.Anio = '".$field['Anio']."' AND
					vsd.CodSolicitud = '".$field['CodSolicitud']."' AND
					vp.CodPersona = '".$field['CodPersona']."'
				ORDER BY Secuencia";
		$query_detalle = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		while ($field_detalle = mysql_fetch_array($query_detalle)) {
			$Periodo = "$field_detalle[AnioPeriodo] - ".($field_detalle['AnioPeriodo'] + 1);
			$pdf->SetFont('Arial', 'BI', 8);
			$pdf->Cell(25, 5);
			$pdf->Cell(50, 5, $Periodo, 0, 0, 'C');
			$pdf->Cell(25, 5, formatFechaDMA($field_detalle['FechaInicio']), 0, 0, 'C');
			$pdf->Cell(25, 5, formatFechaDMA($field_detalle['FechaFin']), 0, 0, 'C');
			$pdf->Cell(15, 5, $field_detalle['NroDias'], 0, 0, 'C');
			$pdf->Cell(25, 5, formatFechaDMA($field_detalle['FechaIncorporacion']), 0, 0, 'C');
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
