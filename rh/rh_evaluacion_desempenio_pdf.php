<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
extract($_POST);
extract($_GET);
//	------------------------------------
include("../lib/fpdf.php");
include("../lib/fphp.php");
include("lib/fphp.php");
//	------------------------------------

//---------------------------------------------------
class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
	}
	//	Pie de página.
	function Footer() {
		$this->SetDrawColor(0, 0, 0); $this->SetFillColor(255, 255, 255); $this->SetTextColor(0, 0, 0);
		$this->SetFont('Arial', '', 7);
		$this->SetY(-10); 
		$this->Cell(195, 5, utf8_decode('Página '.$this->PageNo().' de {nb}'), 0, 0, 'C');
	}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(1, 10);
$pdf->AddPage();
$letra = 8;
//---------------------------------------------------
if ($fordenar != "") { $orderby = "ORDER BY $fordenar"; }
if ($fedoreg != "") { $filtro.=" AND (ee.Estado = '".$fedoreg."')"; }
if ($fbuscar != "") {
	$filtro.=" AND (e1.CodEmpleado LIKE '%".$fbuscar."%' OR
					p.NomCompleto LIKE '%".$fbuscar."%' OR
					pu.DescripCargo LIKE '%".$fbuscar."%' OR
					ee.Periodo LIKE '%".$fbuscar."%' OR
					ee.Estado LIKE '%".$fbuscar."%')";
}
if ($forganismo != "") { $filtro.=" AND (e1.CodOrganismo = '".$forganismo."')"; }
if ($fdependencia != "") { $filtro.=" AND (e1.CodDependencia = '".$fdependencia."')"; }
if ($fpersona != "") { $filtro.=" AND (ee.CodPersona = '".$fpersona."')"; }
if ($fevaluador != "") { $filtro.=" AND (ee.Evaluador = '".$fevaluador."')"; }
if ($fperiodo != "") { $filtro.=" AND (ee.Periodo = '".$fperiodo."')"; }
//	consulto datos del los involucrados
$sql = "SELECT
			ee.Estado,
			ep.FechaInicio,
			ep.FechaFin,
			ep.CodOrganismo,
			ep.Periodo,
			ep.Secuencia,
			ee.CodPersona,
			ee.Evaluador,
			ee.ComentarioPersona,
			ee.ComentarioEvaluador,
			ee.TotalDesempenio,
			ee.TotalMetas,
			
			p1.Apellido1 AS Apellido1Evaluado,
			p1.Apellido2 AS Apellido2Evaluado,
			p1.Nombres AS NombresEvaluado,
			p1.Ndocumento AS NdocumentoEvaluado,
			p1.Foto,
			e1.Fingreso AS FingresoEvaluado,
			pu1.DescripCargo AS DescripCargoEvaluado,
			pu2.DescripCargo AS DescripCargoTempEvaluado,
			d1.Dependencia AS DependenciaEvaluado,
			
			p2.Apellido1 AS Apellido1Evaluador,
			p2.Apellido2 AS Apellido2Evaluador,
			p2.Nombres AS NombresEvaluador,
			p2.Ndocumento AS NdocumentoEvaluador,
			e2.Fingreso AS FingresoEvaluador,
			pu3.DescripCargo AS DescripCargoEvaluador,
			pu4.DescripCargo AS DescripCargoTempEvaluador,
			d2.Dependencia AS DependenciaEvaluador,
			
			p3.Apellido1 AS Apellido1Mediato,
			p3.Apellido2 AS Apellido2Mediato,
			p3.Nombres AS NombresMediato,
			p3.Ndocumento AS NdocumentoMediato,
			e3.Fingreso AS FingresoMediato,
			pu5.DescripCargo AS DescripCargoMediato,
			pu6.DescripCargo AS DescripCargoTempMediato,
			d3.Dependencia AS DependenciaMediato
		FROM
			rh_evaluacionempleado ee
			INNER JOIN rh_evaluacionperiodo ep ON (ee.CodOrganismo = ep.CodOrganismo AND
												   ee.Periodo = ep.Periodo AND
												   ee.Secuencia = ep.Secuencia)
			INNER JOIN mastpersonas p1 ON (ee.CodPersona = p1.CodPersona)
			INNER JOIN mastempleado e1 ON (p1.CodPersona = e1.CodPersona)
			INNER JOIN mastdependencias d1 ON (e1.CodDependencia = d1.CodDependencia)
			LEFT JOIN rh_puestos pu1 ON (e1.CodCargo = pu1.CodCargo)			
			LEFT JOIN rh_puestos pu2 ON (e1.CodCargoTemp = pu2.CodCargo)
			
			INNER JOIN mastpersonas p2 ON (ee.Evaluador = p2.CodPersona)
			INNER JOIN mastempleado e2 ON (p2.CodPersona = e2.CodPersona)
			INNER JOIN mastdependencias d2 ON (e2.CodDependencia = d2.CodDependencia)
			LEFT JOIN rh_puestos pu3 ON (e2.CodCargo = pu3.CodCargo)
			LEFT JOIN rh_puestos pu4 ON (e2.CodCargoTemp = pu4.CodCargo)
			
			INNER JOIN mastpersonas p3 ON (ee.Supervisor = p3.CodPersona)
			INNER JOIN mastempleado e3 ON (p3.CodPersona = e3.CodPersona)
			INNER JOIN mastdependencias d3 ON (e3.CodDependencia = d3.CodDependencia)
			LEFT JOIN rh_puestos pu5 ON (e3.CodCargo = pu5.CodCargo)
			LEFT JOIN rh_puestos pu6 ON (e3.CodCargoTemp = pu6.CodCargo)
		WHERE 1 $filtro
		$orderby";
$query_empleado = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
while ($field_empleado = mysql_fetch_array($query_empleado)) {	$k++;
	//	GENERAL
	//	imprimo periodo evaluado
	if ($k > 1) $pdf->AddPage();
	//	imprimo el membreter
	$pdf->Image($_PARAMETRO["PATHLOGO"].'contraloria.jpg', 10, 5, 15, 15);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetXY(30, 5); $pdf->Cell(100, 5, utf8_decode('REPÚBLICA BOLIVARIANA DE VENEZUELA'), 0, 1, 'L');
	$pdf->SetXY(30, 10); $pdf->Cell(100, 5, $_SESSION['NOMBRE_ORGANISMO_ACTUAL'], 0, 1, 'L');
	$pdf->SetXY(30, 15); $pdf->Cell(100, 5, utf8_decode('DIVISIÓN DE RECURSOS HUMANOS'), 0, 0, 'L');
	$pdf->Ln(15);
	$pdf->SetFont('Arial', 'BU', 12);
	$pdf->Cell(195, 5, utf8_decode('INSTRUMENTO DE EVALUACIÓN DEL DESEMPEÑO PARA FUNCIONARIOS'), 0, 1, 'C', 0);
	$pdf->Cell(195, 5, utf8_decode('(EMPLEADOS)'), 0, 1, 'C', 0);
	//---------------------------------------------------
	$pdf->SetY(50);
	$pdf->SetFont('Arial', 'B', $letra);
	$pdf->Cell(140, 5, utf8_decode('PERIODO EVALUADO'), 0, 1, 'C', 0);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255);
	$y = $pdf->GetY();
	$pdf->Rect(10, $y, 67, 10, "DF"); $pdf->Rect(77, $y, 68, 10, "DF");
	$pdf->SetFont('Arial', '', $letra);
	$pdf->SetX(12); $pdf->Cell(60, 4, utf8_decode('DESDE:'), 0, 0, 'C', 0);
	$pdf->SetX(79); $pdf->Cell(60, 4, utf8_decode('HASTA:'), 0, 0, 'C', 0);
	if (file_exists($_PARAMETRO["PATHFOTO"].''.$field_empleado['Foto'].'.jpg')) $pdf->Image($_PARAMETRO["PATHFOTO"].''.$field_empleado['Foto'].'.jpg', 185, 45, 20, 25);
	$pdf->Ln();
	$pdf->SetFont('Arial', 'B', $letra);
	$pdf->SetX(12); $pdf->Cell(60, 5, formatFechaDMA($field_empleado['FechaInicio']), 0, 0, 'C', 0);
	$pdf->SetX(79); $pdf->Cell(60, 5, formatFechaDMA($field_empleado['FechaFin']), 0, 0, 'C', 0);
	//	imprimo datos de identificacion
	$pdf->SetY(70);
	$pdf->SetFont('Arial', 'B', $letra);
	$pdf->Cell(195, 5, utf8_decode('DATOS DE IDENTIFICACIÓN'), 0, 1, 'C', 0);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255);
	$pdf->SetFont('Arial', 'B', $letra);
	$pdf->SetX(10); $pdf->Cell(97, 5, utf8_decode('1.- DATOS DEL EVALUADO'), 1, 0, 'L', 0);
	$pdf->SetX(107); $pdf->Cell(98, 5, utf8_decode('2.- DATOS DEL EVALUADOR'), 1, 0, 'L', 0);
	##
	$pdf->Ln();	
	$y = $pdf->GetY();
	$pdf->Rect(10, $y, 97, 10, "DF"); $pdf->Rect(107, $y, 98, 10, "DF");
	$pdf->SetFont('Arial', '', $letra-1);
	$pdf->SetX(10); $pdf->Cell(90, 3, utf8_decode('Apellidos y nombres:'), 0, 0, 'L', 0);
	$pdf->SetX(107); $pdf->Cell(90, 3, utf8_decode('Apellidos y nombres:'), 0, 0, 'L', 0);
	$pdf->Ln();
	$pdf->SetFont('Arial', 'B', $letra);
	$pdf->SetX(10); $pdf->Cell(90, 5, utf8_decode($field_empleado['Apellido1Evaluado'].' '.$field_empleado['Apellido2Evaluado'].', '.$field_empleado['NombresEvaluado']), 0, 0, 'L', 0);
	$pdf->SetX(107); $pdf->Cell(90, 5, utf8_decode($field_empleado['Apellido1Evaluador'].' '.$field_empleado['Apellido2Evaluador'].', '.$field_empleado['NombresEvaluador']), 0, 0, 'L', 0);
	##
	$pdf->Ln();
	$y = $pdf->GetY();
	$pdf->Rect(10, $y, 97, 10, "DF"); $pdf->Rect(107, $y, 98, 10, "DF");
	$pdf->SetFont('Arial', '', $letra-1);
	$pdf->SetX(10); $pdf->Cell(90, 3, utf8_decode('Cédula de identidad:'), 0, 0, 'L', 0);
	$pdf->SetX(107); $pdf->Cell(90, 3, utf8_decode('Cédula de identidad:'), 0, 0, 'L', 0);
	$pdf->Ln();
	$pdf->SetFont('Arial', 'B', $letra);
	$pdf->SetX(10); $pdf->Cell(90, 5, number_format($field_empleado['NdocumentoEvaluado'], 0, '', '.'), 0, 0, 'L', 0);
	$pdf->SetX(107); $pdf->Cell(90, 5, number_format($field_empleado['NdocumentoEvaluador'], 0, '', '.'), 0, 0, 'L', 0);
	##
	if ($field_empleado['DescripCargoTempEvaluado'] != "") $CargoEvaluado = $field_empleado['DescripCargoTempEvaluado'];
	else $CargoEvaluado = $field_empleado['DescripCargoEvaluado'];
	if ($field_empleado['DescripCargoTempEvaluador'] != "") $CargoEvaluador = $field_empleado['DescripCargoTempEvaluador'];
	else $CargoEvaluador = $field_empleado['DescripCargoEvaluador'];
	$pdf->Ln();
	$y = $pdf->GetY();
	$pdf->Rect(10, $y, 97, 13, "DF"); $pdf->Rect(107, $y, 98, 13, "DF");
	$pdf->SetFont('Arial', '', $letra-1);
	$pdf->SetX(10); $pdf->Cell(90, 3, utf8_decode('Denominación del cargo:'), 0, 0, 'L', 0);
	$pdf->SetX(107); $pdf->Cell(90, 3, utf8_decode('Denominación del cargo:'), 0, 0, 'L', 0);
	$pdf->Ln();
	$pdf->SetFont('Arial', 'B', $letra);
	$y = $pdf->GetY();
	$pdf->SetXY(10, $y); $pdf->MultiCell(90, 4, utf8_decode($CargoEvaluado), 0, 'L', 0);
	$pdf->SetXY(107, $y); $pdf->MultiCell(90, 4, utf8_decode($CargoEvaluador), 0, 'L', 0);
	##
	$pdf->Ln();
	$y = $pdf->GetY();
	$pdf->Rect(10, $y, 97, 10, "DF"); $pdf->Rect(107, $y, 98, 10, "DF");
	$pdf->SetFont('Arial', '', $letra-1);
	$pdf->SetX(10); $pdf->Cell(90, 3, utf8_decode('Fecha de ingreso:'), 0, 0, 'L', 0);
	$pdf->SetX(107); $pdf->Cell(90, 3, utf8_decode('Fecha de ingreso:'), 0, 0, 'L', 0);
	$pdf->Ln();
	$pdf->SetFont('Arial', 'B', $letra);
	$pdf->SetX(10); $pdf->Cell(90, 5, formatFechaDMA($field_empleado['FingresoEvaluado']), 0, 0, 'L', 0);
	$pdf->SetX(107); $pdf->Cell(90, 5, formatFechaDMA($field_empleado['FingresoEvaluador']), 0, 0, 'L', 0);
	##
	$pdf->Ln();
	$y = $pdf->GetY();
	$pdf->Rect(10, $y, 97, 13, "DF"); $pdf->Rect(107, $y, 98, 13, "DF");
	$pdf->SetFont('Arial', '', $letra-1);
	$pdf->SetX(10); $pdf->Cell(90, 3, utf8_decode('Dependencia:'), 0, 0, 'L', 0);
	$pdf->SetX(107); $pdf->Cell(90, 3, utf8_decode('Dependencia:'), 0, 0, 'L', 0);
	$pdf->Ln();
	$pdf->SetFont('Arial', 'B', $letra);
	$y = $pdf->GetY();
	$pdf->SetXY(10, $y); $pdf->MultiCell(90, 4, utf8_decode($field_empleado['DependenciaEvaluado']), 0, 'L', 0);
	$pdf->SetXY(107, $y); $pdf->MultiCell(90, 4, utf8_decode($field_empleado['DependenciaEvaluador']), 0, 'L', 0);
	//	imprimo rango de evaluacion de los odi
	$pdf->SetY(135);
	$pdf->SetFont('Arial', 'B', $letra);
	$pdf->Cell(195, 5, utf8_decode('RANGOS DE EVALUACIÓN'), 0, 1, 'C', 0);
	$pdf->SetFont('Arial', 'BI', $letra);
	$pdf->Cell(195, 5, utf8_decode('OBJETIVOS DE DESEMPEÑO INDIVIDUAL (ODI)'), 0, 1, 'C', 0);	
	$pdf->SetFont('Arial', 'B', $letra);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255);
	$pdf->SetWidths(array(20, 60, 115));
	$pdf->SetAligns(array('C', 'C', 'C'));
	$pdf->Row(array('RANGOS',
					utf8_decode('DESCRIPCIÓN'),
					'CONDUCTAS ASOCIADAS'));
	$pdf->SetFont('Arial', '', $letra);
	$pdf->SetAligns(array('C', 'L', 'L'));
	$sql = "SELECT
				Descripcion,
				Explicacion,
				Valor
			FROM rh_evaluacionperiodovalor
			WHERE
				CodOrganismo = '".$field_empleado['CodOrganismo']."' AND
				Periodo = '".$field_empleado['Periodo']."' AND
				Secuencia = '".$field_empleado['Secuencia']."'
			ORDER BY Rango";
	$query_rangos = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field_rangos = mysql_fetch_array($query_rangos)) {
		$pdf->Row(array($field_rangos['Valor'],
						utf8_decode($field_rangos['Descripcion']),
						utf8_decode($field_rangos['Explicacion'])));
	}
	//	imprimo rango de evaluacion competencias
	$pdf->SetY(185);
	$pdf->SetFont('Arial', 'BI', $letra);
	$pdf->Cell(195, 5, utf8_decode('COMPETENCIAS'), 0, 1, 'C', 0);
	$pdf->SetFont('Arial', 'B', $letra);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255);
	$pdf->SetWidths(array(20, 60, 115));
	$pdf->SetAligns(array('C', 'C', 'C'));
	$pdf->Row(array('RANGOS',
					utf8_decode('DESCRIPCIÓN'),
					'CONDUCTAS ASOCIADAS'));
	$pdf->SetFont('Arial', '', $letra);
	$pdf->SetAligns(array('C', 'L', 'L'));
	$sql = "SELECT
				Grado,
				Descripcion
			FROM rh_gradoscompetencia
			ORDER BY Grado";
	$query_rangos2 = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field_rangos2 = mysql_fetch_array($query_rangos2)) {
		$pdf->Row(array($field_rangos2['Grado'],
						utf8_decode($field_rangos2['Descripcion']),
						''));
	}
	
	//	ODI
	$pdf->AddPage();
	$pdf->SetY(10);
	$pdf->SetFont('Arial', 'B', $letra+1);
	$pdf->Cell(195, 5, utf8_decode('I PARTE'), 0, 1, 'C', 0);
	##
	$pdf->SetFont('Arial', 'BI', $letra);
	$pdf->Cell(195, 5, utf8_decode('ESTABLECIMIENTO Y EVALUACIÓN DE LOS OBJETIVOS DE DESEMPEÑO INDIVIDUAL (ODI)'), 0, 1, 'C', 0);
	$pdf->Ln(5);
	##
	$pdf->SetFont('Arial', 'B', $letra);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255);
	$pdf->SetWidths(array(105, 20, 50, 20));
	$pdf->SetAligns(array('C', 'C', 'C', 'C'));
	$pdf->Row(array(utf8_decode('OBJETIVOS DE DESEMPEÑO INDIVIDUAL (ODI)'),
					'PESO',
					'RANGOS',
					'TOTAL PESO X RANGO'));
	$pdf->SetFont('Arial', '', $letra);
	$pdf->SetWidths(array(105, 20, 10, 10, 10, 10, 10, 20));
	$pdf->SetAligns(array('J', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
	$pdf->Row(array('',
					'',
					'1',
					'2',
					'3',
					'4',
					'5',
					''));
	##
	$sql = "SELECT
				Descripcion,
				Calificacion,
				Peso
			FROM rh_empleado_metas
			WHERE
				CodOrganismo = '".$field_empleado['CodOrganismo']."' AND
				Periodo = '".$field_empleado['Periodo']."' AND
				Secuencia = '".$field_empleado['Secuencia']."' AND
				CodPersona = '".$field_empleado['CodPersona']."' AND
				Evaluador = '".$field_empleado['Evaluador']."'
			ORDER BY SecuenciaDesempenio";
	$query_odi = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$sum_odi=0;
	while ($field_odi = mysql_fetch_array($query_odi)) {
		$total_odi = $field_odi['Peso'] * $field_odi['Calificacion'];
		$sum_odi += $total_odi;
		if ($field_odi['Calificacion'] == 1) { $cal1 = "X"; $cal2 = ""; $cal3 = ""; $cal4 = ""; $cal5 = ""; }
		elseif ($field_odi['Calificacion'] == 2) { $cal1 = ""; $cal2 = "X"; $cal3 = ""; $cal4 = ""; $cal5 = ""; }
		elseif ($field_odi['Calificacion'] == 3) { $cal1 = ""; $cal2 = ""; $cal3 = "X"; $cal4 = ""; $cal5 = ""; }
		elseif ($field_odi['Calificacion'] == 4) { $cal1 = ""; $cal2 = ""; $cal3 = ""; $cal4 = "X"; $cal5 = ""; }
		elseif ($field_odi['Calificacion'] == 5) { $cal1 = ""; $cal2 = ""; $cal3 = ""; $cal4 = ""; $cal5 = "X"; }
		$pdf->Row(array(utf8_decode($field_odi['Descripcion']),
						$field_odi['Peso'],
						$cal1,
						$cal2,
						$cal3,
						$cal4,
						$cal5,
						$total_odi));
	}
	
	//	COMPETENCIAS
	$pdf->AddPage();
	$pdf->SetY(10);
	$pdf->SetFont('Arial', 'B', $letra+1);
	$pdf->Cell(195, 5, utf8_decode('II PARTE'), 0, 1, 'C', 0);
	##
	$pdf->SetFont('Arial', 'BI', $letra);
	$pdf->Cell(195, 5, utf8_decode('COMPETENCIAS'), 0, 1, 'C', 0);
	$pdf->SetFont('Arial', '', $letra);
	##	
	$sql = "SELECT
				ef.Descripcion,
				ef.Explicacion,
				ee.Calificacion,
				ee.Peso,
				ee.Competencia,
				fv.Grado,
				fv.Explicacion AS Indicador,
				fv.Explicacion2 AS Conducta
			FROM
				rh_empleado_evaluacion ee
				INNER JOIN rh_evaluacionfactores ef ON (ee.Competencia = ef.Competencia)
				INNER JOIN rh_factorvalor fv ON (ee.Competencia = fv.Competencia)
			WHERE
				ee.CodOrganismo = '".$field_empleado['CodOrganismo']."' AND
				ee.Periodo = '".$field_empleado['Periodo']."' AND
				ee.Secuencia = '".$field_empleado['Secuencia']."' AND
				ee.CodPersona = '".$field_empleado['CodPersona']."' AND
				ee.Evaluador = '".$field_empleado['Evaluador']."'
			ORDER BY Competencia";
	$query_competencia = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$nrocompetencia = 0;	$nro++;	$sum_competencia = 0;
	while ($field_competencia = mysql_fetch_array($query_competencia)) {
		$nrocompetencia++;
		$competencia = utf8_decode(strtoupper($field_competencia['Descripcion']).': '.$field_competencia['Explicacion']);
		if ($grupo_competencia != $field_competencia['Competencia']) {
			$grupo_competencia = $field_competencia['Competencia'];
			if ($nrocompetencia > 1) {
				//	puntajes
				$pdf->SetFont('Arial', 'B', $letra);
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255);
				$pdf->SetWidths(array(20, 20, 40));
				$pdf->SetAligns(array('C', 'C', 'C'));
				$pdf->Row(array('PESO',
								'RANGOS',
								'TOTAL PESO X RANGO'));
				$pdf->SetFont('Arial', '', $letra);
				$total_competencia = $Peso * $Calificacion;
				$pdf->Row(array($Peso,
								$Calificacion,
								$total_competencia));
				$sum_competencia += $total_competencia;
				if ($nro % 3 == 0) $pdf->AddPage();
				$nro++;
			}
			$pdf->Ln(5);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255);
			$pdf->SetWidths(array(195));
			$pdf->SetAligns(array('J'));
			$pdf->Row(array(utf8_decode(' ('.$nro.') '.$competencia)));
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255);
			$pdf->SetWidths(array(65, 130));
			$pdf->SetAligns(array('L', 'L'));
			$pdf->Row(array('INDICADOR',
							'CONDUCTAS ASOCIADAS'));
		}
		$pdf->Row(array(utf8_decode($field_competencia['Indicador']),
						utf8_decode($field_competencia['Conducta'])));
		$Peso = $field_competencia['Peso'];
		$Calificacion = $field_competencia['Calificacion'];
	}
	//	puntajes
	$pdf->SetFont('Arial', 'B', $letra);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255);
	$pdf->SetWidths(array(20, 20, 40));
	$pdf->SetAligns(array('C', 'C', 'C'));
	$pdf->Row(array('PESO',
					'RANGOS',
					'TOTAL PESO X RANGO'));
	$pdf->SetFont('Arial', '', $letra);
	$total_competencia = $Peso * $Calificacion;
	$pdf->Row(array($Peso,
					$Calificacion,
					$total_competencia));
	$sum_competencia += $total_competencia;
	
	//	REVISIONES
	$pdf->AddPage();
	$pdf->SetY(10);
	$pdf->SetFont('Arial', 'BI', $letra);
	$pdf->Cell(195, 5, utf8_decode('REVISIONES'), 0, 1, 'C', 0);	
	##	
	$sql = "SELECT
				er.*,
				em.Descripcion AS Meta
			FROM 
				rh_empleado_revision er
				INNER JOIN rh_empleado_metas em ON (em.CodOrganismo = er.CodOrganismo AND 
													em.Periodo = er.Periodo AND
													em.CodPersona = er.CodPersona AND
													em.Secuencia = er.Secuencia AND
													em.SecuenciaDesempenio = er.SecuenciaDesempenio)
			WHERE
				er.CodOrganismo = '".$field_empleado['CodOrganismo']."' AND
				er.Periodo = '".$field_empleado['Periodo']."' AND
				er.Secuencia = '".$field_empleado['Secuencia']."' AND
				er.CodPersona = '".$field_empleado['CodPersona']."' AND
				er.Evaluador = '".$field_empleado['Evaluador']."'
			ORDER BY SecuenciaDesempenio";
	$query_revision = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$nrorevision = 0;	$nro++;
	while ($field_revision = mysql_fetch_array($query_revision)) {
		$nrorevision++;
		##
		if ($grupo != $field_revision['SecuenciaDesempenio']) {
			$grupo = $field_revision['SecuenciaDesempenio'];
			$pdf->SetFont('Arial', '', $letra);
			$pdf->Ln(10);
			$pdf->MultiCell(195, 5, utf8_decode($field_revision['Meta']), 0, 'J', 0);
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255);
			$pdf->SetFont('Arial', 'B', $letra);
			$pdf->SetWidths(array(20, 25, 155));
			$pdf->SetAligns(array('C', 'C', 'J'));
			$pdf->Row(array('FECHA',
							'PORCENTAJE',
							'OBSERVACION'));
			$pdf->SetFont('Arial', '', $letra);
		}
		$pdf->Row(array(formatFechaDMA($field_revision['Fecha1']),
						($field_revision['Porcentaje1']),
						utf8_decode($field_revision['Observacion1'])));
		$pdf->Row(array(formatFechaDMA($field_revision['Fecha2']),
						($field_revision['Porcentaje2']),
						utf8_decode($field_revision['Observacion2'])));
	}
	
	//	CALIFICACION FINAL
	$pdf->AddPage();
	$pdf->SetY(10);
	$pdf->SetFont('Arial', 'B', $letra+1);
	$pdf->Cell(195, 5, utf8_decode('III PARTE'), 0, 1, 'C', 0);
	##	
	$pdf->SetFont('Arial', 'BI', $letra);
	$pdf->Cell(195, 5, utf8_decode('CALIFICACIÓN FINAL'), 0, 1, 'C', 0);
	$pdf->SetFont('Arial', '', $letra);
	##	puntaje
	$puntaje_final = $field_empleado['TotalMetas'] + $field_empleado['TotalDesempenio'];
	$pdf->Ln(5);
	$pdf->SetFont('Arial', '', $letra);
	$pdf->Cell(50, 7, utf8_decode('TOTAL PRIMERA PARTE: '), 0, 0, 'L', 0);
	$pdf->Cell(20, 7, $field_empleado['TotalDesempenio'], 0, 1, 'R', 0);
	$y = $pdf->GetY(); $pdf->Rect(55, $y-1, 25, 0.1, "DF");
	$pdf->Cell(50, 7, utf8_decode('TOTAL PRIMERA PARTE: '), 0, 0, 'L', 0);
	$pdf->Cell(20, 7, $field_empleado['TotalMetas'], 0, 1, 'R', 0);
	$y = $pdf->GetY(); $pdf->Rect(55, $y-1, 25, 0.1, "DF");
	$pdf->Cell(50, 7, utf8_decode('PUNTAJE FINAL (I + II): '), 0, 0, 'L', 0);
	$pdf->Cell(20, 7, $puntaje_final, 0, 1, 'R', 0);
	$y = $pdf->GetY(); $pdf->Rect(55, $y-1, 25, 0.1, "DF");
	##	rangos
	$pdf->Ln(10);
	$pdf->SetFont('Arial', 'B', $letra);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255);
	$pdf->SetWidths(array(25, 60, 110));
	$pdf->SetAligns(array('C', 'C', 'C'));
	$pdf->Row(array('ESCALA CUANTITATIVA',
					utf8_decode('RANGO DE ACTUACIÓN'),
					utf8_decode('DEFINICIÓN DE LOS RANGOS DE ACTUACIÓN')));
	$pdf->SetFont('Arial', '', $letra);
	$pdf->SetAligns(array('C', 'L', 'L'));
	$sql = "SELECT
				Descripcion,
				Definicion,
				PuntajeMin,
				PuntajeMax
			FROM rh_gradoscalificacion
			ORDER BY Grado";
	$query_rangos3 = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field_rangos3 = mysql_fetch_array($query_rangos3)) {
		$pdf->Row(array($field_rangos3['PuntajeMin'].' - '.$field_rangos3['PuntajeMax'],
						utf8_decode($field_rangos3['Descripcion']),
						utf8_decode($field_rangos3['Definicion'])));
		if ($puntaje_final >= $field_rangos3['PuntajeMin'] && $puntaje_final <= $field_rangos3['PuntajeMax']) {
			$rango_actuacion = $field_rangos3['Descripcion'];
		}
	}
	##
	$pdf->SetY(135);
	$pdf->SetX(150);
	$pdf->Cell(45, 5, utf8_decode('Rango de actuación'), 0, 1, 'C', 0);
	$y = $pdf->GetY() + 8;
	$pdf->Rect(150, $y, 45, 0.1, "DF");
	$y += 8;
	$pdf->Rect(150, $y, 45, 0.1, "DF");
	$pdf->SetXY(100, 150);
	$pdf->Cell(100, 5, utf8_decode($rango_actuacion), 0, 1, 'R', 0);
	##
	$pdf->SetY(170);
	$pdf->SetFont('Arial', 'BI', $letra);
	$pdf->Cell(195, 5, utf8_decode('OPINIÓN DEL EVALUADO'), 0, 1, 'L', 0);
	$pdf->SetFont('Arial', '', $letra);
	$pdf->MultiCell(195, 5, utf8_decode($field_empleado['ComentarioPersona']), 0, 'J', 0);
	##
	$pdf->SetY(215);
	$pdf->SetFont('Arial', 'BI', $letra);
	$pdf->Cell(195, 5, utf8_decode('OBSERVACIONES DEL SUPERVISOR INMEDIATO'), 0, 1, 'L', 0);
	$pdf->SetFont('Arial', '', $letra);
	$pdf->MultiCell(195, 5, utf8_decode($field_empleado['ComentarioEvaluador']), 0, 'J', 0);
	
	//	INCIDENTES CRITICOS
	$pdf->AddPage();
	$pdf->SetY(10);
	$pdf->SetFont('Arial', 'B', $letra+1);
	$pdf->Cell(195, 5, utf8_decode('INCIDENTES CRITICOS'), 0, 1, 'C', 0);
	##
	$pdf->Ln(5);
	$pdf->SetFont('Arial', 'B', $letra);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255);
	$pdf->SetWidths(array(25, 20, 30, 40, 80));
	$pdf->SetAligns(array('L', 'C', 'L', 'L', 'L'));
	$pdf->Row(array('Tipo',
					'Fecha',
					'Documento',
					utf8_decode('Clasificación'),
					'Observaciones'));
	$pdf->SetFont('Arial', '', $letra);
	##
	$sql = "(SELECT
				mf.Secuencia, 
				mf.Documento, 
				mf.FechaDoc, 
				mf.Observacion, 
				md.Descripcion AS Clasificacion, 
				'Mérito' AS Tipo 
			 FROM 
				rh_meritosfaltas mf 
				LEFT JOIN mastmiscelaneosdet md ON (mf.Clasificacion = md.CodDetalle AND md.CodMaestro = 'MERITO') 
			 WHERE
				mf.CodPersona = '".$field_empleado['CodPersona']."' AND 
				mf.Tipo = 'M' AND
				mf.FechaDoc >= '".$field_empleado['FechaInicio']."' AND
				mf.FechaDoc <= '".$field_empleado['FechaFin']."')
			
			UNION 
			
			(SELECT 
				mf.Secuencia, 
				mf.Documento, 
				mf.FechaDoc, 
				mf.Observacion, 
				md.Descripcion AS Clasificacion, 
				'Demérito' AS Tipo 
			 FROM
				rh_meritosfaltas mf 
				LEFT JOIN mastmiscelaneosdet md ON (mf.Clasificacion = md.CodDetalle AND md.CodMaestro = 'DEMERITO') 
			 WHERE
				mf.CodPersona = '".$field_empleado['CodPersona']."' AND 
				mf.Tipo = 'D' AND
				mf.FechaDoc >= '".$field_empleado['FechaInicio']."' AND
				mf.FechaDoc <= '".$field_empleado['FechaFin']."')
			ORDER BY Secuencia";
	$query_incidentes = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field_incidentes = mysql_fetch_array($query_incidentes)) {
		$pdf->Row(array(utf8_decode($field_incidentes['Tipo']),
						formatFechaDMA($field_incidentes['FechaDoc']),
						$field_incidentes['Documento'],
						utf8_decode($field_incidentes['Clasificacion']),
						utf8_decode($field_incidentes['Observacion'])));
	}
	
	//	FORTALEZAS Y DEBILIDADES
	$pdf->Ln(10);
	$pdf->SetFont('Arial', 'B', $letra+1);
	$pdf->Cell(195, 5, utf8_decode('FORTALEZAS Y DEBILIDADES'), 0, 1, 'C', 0);
	##	fortalezas
	$pdf->Ln(5);
	$pdf->SetFont('Arial', 'BI', $letra);
	$pdf->Cell(195, 5, utf8_decode('Fortalezas:'), 0, 1, 'L', 0);	
	$pdf->Ln(2);
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255);
	$pdf->SetWidths(array(190));
	$pdf->SetAligns(array('J'));
	$pdf->SetFont('Arial', '', $letra);
	##
	$sql = "SELECT * 
			FROM rh_empleado_desempenio 
			WHERE
				Tipo = 'F' AND
				CodOrganismo = '".$field_empleado['CodOrganismo']."' AND
				Periodo = '".$fperiodo."' AND
				CodPersona = '".$field_empleado['CodPersona']."' AND
				Secuencia = '".$field_empleado['Secuencia']."'";
	$query_fortalezas = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field_fortalezas = mysql_fetch_array($query_fortalezas)) {
		$pdf->SetX(15);
		$pdf->Row(array(utf8_decode('- '.$field_fortalezas['Descripcion'])));
		$pdf->Ln(2);
	}
	##	debilidades
	$pdf->Ln(5);
	$pdf->SetFont('Arial', 'BI', $letra);
	$pdf->Cell(195, 5, utf8_decode('Debilidades:'), 0, 1, 'L', 0);	
	$pdf->Ln(2);
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255);
	$pdf->SetWidths(array(190));
	$pdf->SetAligns(array('J'));
	$pdf->SetFont('Arial', '', $letra);
	##
	$sql = "SELECT * 
			FROM rh_empleado_desempenio 
			WHERE
				Tipo = 'D' AND
				CodOrganismo = '".$field_empleado['CodOrganismo']."' AND
				Periodo = '".$fperiodo."' AND
				CodPersona = '".$field_empleado['CodPersona']."' AND
				Secuencia = '".$field_empleado['Secuencia']."'";
	$query_fortalezas = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field_fortalezas = mysql_fetch_array($query_fortalezas)) {
		$pdf->SetX(15);
		$pdf->Row(array(utf8_decode('- '.$field_fortalezas['Descripcion'])));
		$pdf->Ln(2);
	}
	
	//	NECESIDADES DE CAPACITACION
	$pdf->Ln(10);
	$pdf->SetFont('Arial', 'B', $letra+1);
	$pdf->Cell(195, 5, utf8_decode('NECESIDADES DE CAPACITACION'), 0, 1, 'C', 0);
	##
	$pdf->Ln(5);
	$pdf->SetFont('Arial', 'B', $letra);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255);
	$pdf->SetWidths(array(60, 15, 60, 60));
	$pdf->SetAligns(array('L', 'C', 'L', 'L'));
	$pdf->Row(array(utf8_decode('Necesidad de Capacitación'),
					'Prioridad',
					'Curso',
					'Objetivo de Mejora'));
	$pdf->SetFont('Arial', '', $letra);
	##
	$sql = "SELECT
				en.*,
				c.Descripcion AS NomCurso
			FROM
				rh_empleado_necesidad en
				INNER JOIN rh_cursos c ON (en.CodCurso = c.CodCurso)
			WHERE
				en.CodOrganismo = '".$field_empleado['CodOrganismo']."' AND
				en.Periodo = '".$fperiodo."' AND
				en.CodPersona = '".$field_empleado['CodPersona']."' AND
				en.Secuencia = '".$field_empleado['Secuencia']."'";
	$query_necesidad = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field_necesidad = mysql_fetch_array($query_necesidad)) {
		$pdf->Row(array(utf8_decode($field_necesidad['Necesidad']),
						printValoresGeneral("PRIORIDAD", $field_necesidad['Prioridad'], 0),
						utf8_decode($field_necesidad['NomCurso']),
						utf8_decode($field_necesidad['Objetivo'])));
	}
	
	##
	$evaluado = $field_empleado['NombresEvaluado'].' '.$field_empleado['Apellido1Evaluado'].' '.$field_empleado['Apellido2Evaluado'];
	$evaluador = $field_empleado['NombresEvaluador'].' '.$field_empleado['Apellido1Evaluador'].' '.$field_empleado['Apellido2Evaluador'];
	$evaluador_mediato = $field_empleado['NombresMediato'].' '.$field_empleado['Apellido1Mediato'].' '.$field_empleado['Apellido2Mediato'];
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
	$y = $pdf->GetY() + 50;
	$pdf->Rect(10, $y, 45, 0.1, "DF");
	$pdf->SetXY(7, $y);
	$pdf->Cell(50, 5, utf8_decode('Funcionario Evaluado'), 0, 1, 'C', 0);
	$pdf->SetXY(7, $y+5);
	$pdf->Cell(50, 5, utf8_decode($evaluado), 0, 1, 'C', 0);
	$pdf->SetXY(7, $y+10);
	$pdf->Cell(50, 5, 'Fecha:                         ', 0, 1, 'C', 0);
	
	$pdf->Rect(85, $y, 45, 0.1, "DF");
	$pdf->SetXY(82, $y);
	$pdf->Cell(50, 5, utf8_decode('Jefe Inmediato'), 0, 1, 'C', 0);
	$pdf->SetXY(82, $y+5);
	$pdf->Cell(50, 5, utf8_decode($evaluador), 0, 1, 'C', 0);
	$pdf->SetXY(82, $y+10);
	$pdf->Cell(50, 5, 'Fecha:                         ', 0, 1, 'C', 0);
	
	$pdf->Rect(155, $y, 45, 0.1, "DF");
	$pdf->SetXY(152, $y);
	$pdf->Cell(50, 5, utf8_decode('Jefe Mediato'), 0, 1, 'C', 0);
	$pdf->SetXY(152, $y+5);
	$pdf->Cell(50, 5, utf8_decode($evaluador_mediato), 0, 1, 'C', 0);
	$pdf->SetXY(152, $y+10);
	$pdf->Cell(50, 5, 'Fecha:                         ', 0, 1, 'C', 0);
}
//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
