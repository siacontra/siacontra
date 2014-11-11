<?php
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("lib/fphp.php");
//---------------------------------------------------
if ($fCodOrganismo != "") $filtro .= " AND (e.CodOrganismo = '".$fCodOrganismo."')";
if ($fCodDependencia != "") $filtro .= " AND (e.CodDependencia = '".$fCodDependencia."')";
if ($fCodPersona != "") $filtro .= " AND (rj.CodPersona = '".$fCodPersona."')";
if ($fBuscar != "") $filtro .= " AND (e.CodEmpleado LIKE '%".$fBuscar."%' OR
									  p1.NomCompleto LIKE '%".$fBuscar."%' OR
									  p2.NomCompleto LIKE '%".$fBuscar."%' OR
									  rj.Expediente LIKE '%".$fBuscar."%' OR
									  rj.Juzgado LIKE '%".$fBuscar."%' OR
									  rj.CodRetencion LIKE '%".$fBuscar."%' OR
									  tp.Descripcion LIKE '%".$fBuscar."%')";
if ($fFechaD != "" || $fFechaH != "") {
	if ($fFechaD != "") $filtro .= " AND ('".formatFechaAMD($fFechaD)."' >= rj.FechaResolucion AND '".formatFechaAMD($fFechaD)."' <= rj.FechaResolucion)";
	if ($fFechaH != "") $filtro .= " AND ('".formatFechaAMD($fFechaH)."' >= rj.FechaResolucion AND '".formatFechaAMD($fFechaH)."' <= rj.FechaResolucion)";
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
		$this->SetXY(180, 5); $this->Cell(10, 5, utf8_decode('Fecha: '), 0, 0, 'L');
		$this->Cell(30, 5, formatFechaDMA(substr($Ahora, 0, 10)), 0, 1, 'L');
		$this->SetXY(180, 10); $this->Cell(10, 5, utf8_decode('Página: '), 0, 0, 'L');
		$this->Cell(30, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		$this->SetFont('Arial', 'B', 10);
		$this->SetXY(10, 20); $this->Cell(190, 5, utf8_decode('Retenciones Judiciales Detallado'), 0, 1, 'C', 0);
		$this->Ln(5);
		##
		$this->SetDrawColor(0, 0, 0);
		$this->SetWidths(array(13, 10, 60, 60, 35, 25));
		$this->SetAligns(array('C', 'C', 'L', 'L', 'C', 'C'));
		$this->SetFont('Arial', 'B', 6);
		$this->Row(array(utf8_decode('Retención'),
						 'Cod.',
						 'Empleado',
						 'Demandante',
						 'Expediente',
						 'Tipo Pago'));
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
			rj.CodRetencion,
			rj.CodOrganismo,
			rj.Expediente,
			rj.Juzgado,
			e.CodEmpleado,
			p1.NomCompleto,
			p2.NomCompleto AS Demandante,
			tp.TipoPago
		FROM
			rh_retencionjudicial rj
			INNER JOIN mastempleado e ON (e.CodPersona = rj.CodPersona AND
										  e.Estado = 'A')
			INNER JOIN mastpersonas p1 ON (p1.CodPersona = rj.CodPersona)
			INNER JOIN mastpersonas p2 ON (p2.CodPersona = rj.Demandante)
			INNER JOIN masttipopago tp ON (tp.CodTipoPago = rj.CodTipoPago)
		WHERE 1 $filtro
		ORDER BY CodEmpleado";
$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
while ($field = mysql_fetch_array($query)) {	++$i;
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Row(array($field['CodRetencion'],
					$field['CodEmpleado'],
					utf8_decode($field['NomCompleto']),
					utf8_decode($field['Demandante']),
					$field['Expediente'],
					utf8_decode($field['TipoPago'])));
	##	detalles
	$sql = "SELECT
				rjc.TipoDescuento,
				rjc.CodConcepto,
				rjc.Descuento,
				c.Descripcion AS NomConcepto
			FROM
				rh_retencionjudicialconceptos rjc
				INNER JOIN pr_concepto c ON (c.CodConcepto = rjc.CodConcepto)
			WHERE
				rjc.CodRetencion = '".$field['CodRetencion']."' AND
				rjc.CodOrganismo = '".$field['CodOrganismo']."'
			ORDER BY NomConcepto";
	$query_detalle = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field_detalle = mysql_fetch_array($query_detalle)) {
		if ($field_detalle['TipoDescuento'] == "P") $Monto = number_format($field_detalle['Descuento'], 2, ',', '.')." %";
		else $Monto = number_format($field_detalle['Descuento'], 2, ',', '.')." Bs.";
		$pdf->SetFont('Arial', 'I', 6);
		$pdf->Cell(10, 5);
		$pdf->Cell(10, 5, $field_detalle['CodConcepto'], 0, 0, 'C');
		$pdf->Cell(70, 5, utf8_decode($field_detalle['NomConcepto']), 0, 0, 'L');
		$pdf->Cell(20, 5, $Monto, 0, 0, 'R');
		$pdf->Ln(5);
	}
	$pdf->Ln(3);
}
//---------------------------------------------------

//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>