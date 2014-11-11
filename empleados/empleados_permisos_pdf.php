<?php
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("lib/fphp.php");
//---------------------------------------------------
//	datos del empleado
$sql = "SELECT
			p.CodPersona,
			p.NomCompleto,
			e.CodEmpleado
		FROM
			mastpersonas p
			INNER JOIN mastempleado e ON (e.CodPersona = p.CodPersona)
		WHERE p.CodPersona = '".$CodPersona."'";
$query_empleado = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query_empleado)) $field_empleado = mysql_fetch_array($query_empleado);
//---------------------------------------------------
list($FechaInicio, $FechaFin) = split("[|]", $fSemana);
$filtro = "";
if ($fTipoPermiso != "") $filtro.=" AND (p.TipoPermiso = '".$fTipoPermiso."')";
if ($fTipoFalta != "") $filtro.=" AND (p.TipoFalta = '".$fTipoFalta."')";
if ($fEstado != "") $filtro.=" AND (p.Estado = '".$fEstado."')";
if ($fFechaDesde != "" || $fFingresoH != "") {
	if ($fFechaDesde != "") $filtro.=" AND (p.FechaDesde >= '".formatFechaAMD($fFechaDesde)."')";
	if ($fFechaHasta != "") $filtro.=" AND (p.FechaHasta <= '".formatFechaAMD($fFechaHasta)."')";
}
if ($fdBuscar != "") {
	$filtro .= " AND (p.CodPermiso LIKE '%".$fdBuscar."%' OR
					  md1.Descripcion LIKE '%".$fdBuscar."%' OR
					  md2.Descripcion LIKE '%".$fdBuscar."%')";
}
//---------------------------------------------------

class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
		global $_PARAMETRO;
		global $Ahora;
		global $field_empleado;
		global $_GET;
		extract($_GET);
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
		$this->SetXY(10, 20); $this->Cell(195, 5, utf8_decode('PERMISOS DEL EMPLEADO'), 0, 1, 'C', 0);
		$this->Ln(2);
		##	imprimo empleado
		$this->SetDrawColor(255, 255, 255);
		$this->SetFillColor(255, 255, 255);
		$this->SetFont('Arial', 'BU', 8);
		$this->Ln(5);
		$this->Cell(12, 5, $field_empleado['CodEmpleado'], 0, 0, 'L', 0);
		$this->Cell(170, 5, utf8_decode($field_empleado['NomCompleto']), 0, 0, 'L', 0);
		$this->Ln(5);
		##
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(240, 240, 240);
		$this->SetWidths(array(20, 65, 40, 31, 31, 18));
		$this->SetAligns(array('C', 'L', 'C', 'C', 'C', 'C'));
		$this->SetFont('Arial', 'B', 8);
		$this->Row(array('Permiso',
						 'Motivo de Ausencia',
						 'Tipo de Evento',
						 'Desde',
						 'Hasta',
						 'Estado'));
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
//	consulto
$sql = "SELECT
				p.CodPermiso,
				p.FechaDesde,
				p.FechaHasta,
				p.HoraDesde,
				p.HoraHasta,
				p.Estado,
				CONCAT(p.FechaDesde, ' ', p.HoraDesde) AS Desde,
				CONCAT(p.FechaHasta, ' ', p.HoraHasta) AS Hasta,
				md1.Descripcion AS MotivoAusencia,
				md2.Descripcion AS TipoEvento
            FROM
				rh_permisos p
				LEFT JOIN mastmiscelaneosdet md1 ON (md1.CodDetalle = p.TipoPermiso AND
													 md1.CodMaestro = 'PERMISOS' AND
													 md1.CodAplicacion = 'RH')
				LEFT JOIN mastmiscelaneosdet md2 ON (md2.CodDetalle = p.TipoFalta AND
													 md2.CodMaestro = 'TIPOFALTAS' AND
													 md2.CodAplicacion = 'RH')
            WHERE CodPersona = '".$CodPersona."' $filtro
            ORDER BY $fdOrderBy";
$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
while ($field = mysql_fetch_array($query)) {
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetWidths(array(20, 65, 40, 17, 14, 17, 14, 18));
	$pdf->SetAligns(array('C', 'L', 'C', 'C', 'C', 'C', 'C', 'C'));
	$pdf->SetFont('Arial', '', 8);
	$pdf->Row(array($field['CodPermiso'],
					utf8_decode($field['MotivoAusencia']),
					utf8_decode($field['TipoEvento']),
					formatFechaDMA($field['FechaDesde']),
					formatHora12($field['HoraDesde']),
					formatFechaDMA($field['FechaHasta']),
					formatHora12($field['HoraHasta']),
					printValoresGeneral("ESTADO-PERMISOS", $field['Estado'])));
	$pdf->Ln(1);
}
//---------------------------------------------------

//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>