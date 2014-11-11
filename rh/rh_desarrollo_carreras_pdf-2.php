<?php
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("lib/fphp.php");
//---------------------------------------------------
list($CodOrganismo, $Secuencia, $Codigo) = split("[.]", $registro);
$sql = "SELECT
			ac.CodOrganismo,
			ac.Secuencia,
			ac.Codigo,
			ac.CodPersona,
			ac.CodCargo,
			ac.CodDependencia,
			ac.Periodo,
			ac.Estado,
			ac.IniciadoPor,
			ac.FechaIniciado,
			ac.TerminadoPor,
			ac.FechaTerminado,
			p1.NomCompleto AS NomPersona,
			p1.Ndocumento,
			e1.CodEmpleado,
			e1.Fingreso,
			pt1.Grado,
			pt1.DescripCargo,
			md1.Descripcion AS CategoriaCargo,
			p2.NomCompleto AS NomIniciadoPor,
			p3.NomCompleto AS NomTerminadoPor
		FROM
			rh_asociacioncarreras ac
			INNER JOIN mastpersonas p1 ON (p1.CodPersona = ac.CodPersona)
			INNER JOIN mastempleado e1 ON (e1.CodPersona = p1.CodPersona)
			INNER JOIN rh_puestos pt1 ON (pt1.CodCargo = e1.CodCargo)
			LEFT JOIN mastmiscelaneosdet md1 ON (md1.CodDetalle = pt1.CategoriaCargo AND
												  md1.CodMaestro = 'CATCARGO')
			LEFT JOIN mastpersonas p2 ON (p2.CodPersona = ac.IniciadoPor)
			LEFT JOIN mastpersonas p3 ON (p3.CodPersona = ac.TerminadoPor)
		WHERE
			ac.CodOrganismo = '".$CodOrganismo."' AND
			ac.Secuencia = '".$Secuencia."' AND
			ac.Codigo = '".$Codigo."'";
$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query)) $field = mysql_fetch_array($query);
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
		$Logo = getValorCampo("mastorganismos", "CodOrganismo", "Logo", $field['CodOrganismo']);
		$NomOrganismo = getValorCampo("mastorganismos", "CodOrganismo", "Organismo", $field['CodOrganismo']);
		$NomDependencia = getValorCampo("mastdependencias", "CodDependencia", "Dependencia", $_PARAMETRO["DEPLOGCXP"]);
		##
		$this->SetFillColor(255, 255, 255);
		$this->SetDrawColor(0, 0, 0);
		$this->Image($_PARAMETRO["PATHLOGO"].$Logo, 10, 5, 10, 10);		
		$this->SetFont('Arial', '', 8);
		$this->SetXY(20, 5); $this->Cell(100, 5, $NomOrganismo, 0, 1, 'L');
		$this->SetXY(20, 10); $this->Cell(100, 5, $NomDependencia, 0, 0, 'L');	
		$this->SetFont('Arial', '', 8);
		$this->SetXY(175, 5); $this->Cell(20, 5, utf8_decode('Fecha: '), 0, 0, 'L');
		$this->Cell(30, 5, formatFechaDMA(substr($Ahora, 0, 10)), 0, 1, 'L');
		$this->SetXY(175, 10); $this->Cell(20, 5, utf8_decode('Página: '), 0, 0, 'L'); 
		$this->Cell(30, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		$this->SetFont('Arial', 'B', 10);
		$this->SetXY(10, 20); $this->Cell(195, 5, utf8_decode('DESARROLLO DE CARRERAS Y SUCESION'), 0, 1, 'C', 0);
		$this->SetFont('Arial', '', 8);
		$this->SetXY(10, 25); $this->Cell(195, 5, 'Periodo '.$field['Periodo'], 0, 1, 'C', 0);
		$this->Ln(5);
		##
		$this->SetDrawColor(0, 0, 0);
		$this->Rect(10, 35, 195, 225, 'D');
	}
	
	//	Pie de página.
	function Footer() {
		$this->SetFont('Arial', 'BI', 6);
		$this->SetXY(10, 230); $this->Cell(195, 5, 'Hacia la Consolidación y Fortalecimiento del Sistema Nacional de Control Fiscal', 0, 1, 'C', 0);
		$this->SetXY(10, 234); $this->Cell(195, 5, 'Calle Centurion - Quinta Paola N° 36 / Telefono (0287) 7211344 - Fax (0287) 7211655', 0, 1, 'C', 0);
		$this->SetXY(10, 238); $this->Cell(195, 5, 'Tucupita, estado Delta Amacuro.', 0, 1, 'C', 0);
	}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->SetMargins(10, 5, 10);
$pdf->SetAutoPageBreak(5, 30);
$pdf->AddPage();
//---------------------------------------------------
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFillColor(255, 255, 255);

//	datos del empleado
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetWidths(array(145, 25, 25)); $pdf->SetAligns(array('L', 'C', 'C'));
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array('NOMBRES Y APELLIDOS', 'CEDULA', 'INGRESO'));
$pdf->SetFont('Arial', '', 8);
$pdf->Row(array(utf8_decode($field['NomPersona']), number_format($field['Ndocumento'], 0, '', '.'), formatFechaDMA($field['Fingreso'])));
##
$pdf->SetWidths(array(170, 25)); $pdf->SetAligns(array('L', 'C'));
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array('CARGO', 'GRADO'));
$pdf->SetFont('Arial', '', 8);
$pdf->Row(array(utf8_decode($field['DescripCargo']), $field['Grado']));

//	nivel academico
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetWidths(array(195)); $pdf->SetAligns(array('L'));
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array('NIVEL ACADEMICO'));
##	
$sql = "SELECT
			ei.Secuencia,
			ei.FechaGraduacion,
			ei.CodGradoInstruccion,
			ei.Area,
			ei.CodProfesion,
			ei.Nivel,
			ei.CodCentroEstudio,
			gi.Descripcion AS NomGradoInstruccion,
			md.Descripcion AS NomArea,
			p.Descripcion AS NomProfesion
		FROM
			rh_empleado_instruccion ei
			INNER JOIN rh_gradoinstruccion gi ON (ei.CodGradoInstruccion = gi.CodGradoInstruccion)
			LEFT JOIN mastmiscelaneosdet md ON (ei.Area = md.CodDetalle AND CodMaestro = 'AREA')
			LEFT JOIN rh_profesiones p ON (ei.CodProfesion = p.CodProfesion)
		WHERE ei.CodPersona = '".$field['CodPersona']."'
		ORDER BY FechaGraduacion DESC, Secuencia";
$query_nivel = mysql_query($sql) or die($sql.mysql_error());
while ($field_nivel = mysql_fetch_array($query_nivel)) {
	$nro_nivel++;
	if ($field_nivel['CodProfesion'] != "") $Profesion = $field_nivel['NomProfesion'];
	else $Profesion = $field_nivel['NomGradoInstruccion']." EN ".$field_nivel['NomArea'];
	##
	$pdf->Ln(1);
	$pdf->Cell(1, 2);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetWidths(array(8, 5, 180)); $pdf->SetAligns(array('C', 'C', 'L'));
	$pdf->Row(array(' ', '-', utf8_decode($Profesion)));
}
if ($nro_nivel == 0) {
	
}

//	cursos realizados en el area
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetWidths(array(195)); $pdf->SetAligns(array('L'));
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array('CURSOS REALIZADOS EN EL AREA'));
##	
$sql = "SELECT
			ec.*,
			c.Descripcion AS NomCurso
		FROM
			rh_empleado_cursos ec
			INNER JOIN rh_cursos c ON (ec.CodCurso = c.CodCurso)
		WHERE
			ec.CodPersona = '".$field['CodPersona']."' AND
			ec.FlagArea = 'S'
		ORDER BY FechaCulminacion DESC, Secuencia";
$query_cursosa = mysql_query($sql) or die($sql.mysql_error());
while ($field_cursosa = mysql_fetch_array($query_cursosa)) {
	$pdf->Ln(1);
	$pdf->Cell(1, 2);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetWidths(array(8, 5, 180)); $pdf->SetAligns(array('C', 'C', 'L'));
	$pdf->Row(array(' ', '-', utf8_decode($field_cursosa['NomCurso'])));
}

//	cursos realizados en formacion general
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetWidths(array(195)); $pdf->SetAligns(array('L'));
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array('CURSOS REALIZADOS EN FORMACION GENERAL'));
##	
$sql = "SELECT
			ec.*,
			c.Descripcion AS NomCurso
		FROM
			rh_empleado_cursos ec
			INNER JOIN rh_cursos c ON (ec.CodCurso = c.CodCurso)
		WHERE
			ec.CodPersona = '".$field['CodPersona']."' AND
			ec.FlagArea = 'N'
		ORDER BY FechaCulminacion DESC, Secuencia";
$query_cursosfg = mysql_query($sql) or die($sql.mysql_error());
while ($field_cursosfg = mysql_fetch_array($query_cursosfg)) {
	$pdf->Ln(1);
	$pdf->Cell(1, 2);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetWidths(array(8, 5, 180)); $pdf->SetAligns(array('C', 'C', 'L'));
	$pdf->Row(array(' ', '-', utf8_decode($field_cursosfg['NomCurso'])));
}

//	competencias conductuales adquiridas
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetWidths(array(195)); $pdf->SetAligns(array('L'));
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array('COMPETENCIAS CONDUCTUALES ADQUIRIDAS'));
##	
$sql = "SELECT
			ef.Competencia,
			ef.Descripcion,
			ef.ValorRequerido,
			ef.ValorMinimo,
			ef.Estado,
			ee.Calificacion,
			fv.Explicacion,
			fv.Explicacion2
		FROM
			rh_evaluacionfactores ef
			INNER JOIN rh_empleado_evaluacion ee ON (ef.Competencia = ee.Competencia)
			INNER JOIN rh_evaluacionempleado eve ON (ee.CodOrganismo = eve.CodOrganismo AND
													 ee.Periodo = eve.Periodo AND
													 ee.Secuencia = eve.Secuencia AND
													 ee.CodPersona = eve.CodPersona AND
													 ee.Evaluador = eve.Evaluador)
			LEFT JOIN rh_factorvalor fv ON (ee.Competencia = fv.Competencia AND
											ee.Calificacion = fv.Grado)
		WHERE
			ef.Estado = 'A' AND
			ee.CodPersona = '".$field['CodPersona']."' AND
			ee.Calificacion >= ef.ValorMinimo AND
			eve.Estado = 'EV'
		ORDER BY Competencia";
$query_competenciasca = mysql_query($sql) or die($sql.mysql_error());
while ($field_competenciasca = mysql_fetch_array($query_competenciasca)) {
	$pdf->Ln(1);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetWidths(array(8, 5, 180)); $pdf->SetAligns(array('C', 'C', 'L'));
	$pdf->SetFont('Arial', 'BI', 8);
	$pdf->Cell(1, 2); $pdf->Row(array(' ', '-', utf8_decode($field_competenciasca['Descripcion'])));
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(1, 2); $pdf->Row(array(' ', ' ', utf8_decode('  '.$field_competenciasca['Explicacion'])));
	$pdf->Cell(1, 2); $pdf->Row(array(' ', ' ', utf8_decode('  '.$field_competenciasca['Explicacion2'])));
}

//	competencias conductuales por adquirir
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetWidths(array(195)); $pdf->SetAligns(array('L'));
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array('COMPETENCIAS CONDUCTUALES O GENERICAS POR ADQUIRIR'));
##	
$sql = "SELECT
			ef.Competencia,
			ef.Descripcion,
			ef.ValorRequerido,
			ef.ValorMinimo,
			ef.Estado,
			ee.Calificacion,
			fv.Explicacion,
			fv.Explicacion2
		FROM
			rh_evaluacionfactores ef
			INNER JOIN rh_empleado_evaluacion ee ON (ef.Competencia = ee.Competencia)
			INNER JOIN rh_evaluacionempleado eve ON (ee.CodOrganismo = eve.CodOrganismo AND
													 ee.Periodo = eve.Periodo AND
													 ee.Secuencia = eve.Secuencia AND
													 ee.CodPersona = eve.CodPersona AND
													 ee.Evaluador = eve.Evaluador)
			LEFT JOIN rh_factorvalor fv ON (ee.Competencia = fv.Competencia AND
											ee.Calificacion = fv.Grado)
		WHERE
			ef.Estado = 'A' AND
			ee.CodPersona = '".$field['CodPersona']."' AND
			ee.Calificacion < ef.ValorMinimo AND
			eve.Estado = 'EV'
		ORDER BY Competencia";
$query_competenciascgpa = mysql_query($sql) or die($sql.mysql_error());
while ($field_competenciascgpa = mysql_fetch_array($query_competenciascgpa)) {
	$pdf->Ln(1);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetWidths(array(8, 5, 180)); $pdf->SetAligns(array('C', 'C', 'L'));
	$pdf->SetFont('Arial', 'BI', 8);
	$pdf->Cell(1, 2); $pdf->Row(array(' ', '-', utf8_decode($field_competenciascgpa['Descripcion'])));
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(1, 2); $pdf->Row(array(' ', ' ', utf8_decode('  '.$field_competenciascgpa['Explicacion'])));
	$pdf->Cell(1, 2); $pdf->Row(array(' ', ' ', utf8_decode('  '.$field_competenciascgpa['Explicacion2'])));
}

//	habllidades y destrezas tecnicas adquiridas
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetWidths(array(195)); $pdf->SetAligns(array('L'));
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array('HABILIDADES Y DESTREZAS TECNICAS ADQUIRIDAS'));
##	
$sql = "SELECT
			ed.Descripcion
		FROM
			rh_empleado_desempenio ed
			INNER JOIN rh_evaluacionempleado ee ON (ed.CodOrganismo = ee.CodOrganismo AND
													ed.Periodo = ee.Periodo AND
													ed.Secuencia = ee.Secuencia AND
													ed.CodPersona = ee.CodPersona AND
													ed.Evaluador = ee.Evaluador)
		WHERE
			ed.CodPersona = '".$field['CodPersona']."' AND
			ed.Tipo = 'F' AND
			ee.Estado = 'EV'
		ORDER BY ed.SecuenciaDesempenio";
$query_fortalezasa = mysql_query($sql) or die($sql.mysql_error());
while ($field_fortalezasa = mysql_fetch_array($query_fortalezasa)) {
	$pdf->Ln(1);
	$pdf->Cell(1, 2);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetWidths(array(8, 5, 180)); $pdf->SetAligns(array('C', 'C', 'L'));
	$pdf->Row(array(' ', '-', utf8_decode($field_fortalezasa['Descripcion'])));
}

//	habllidades y destrezas tecnicas por adquirir
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetWidths(array(195)); $pdf->SetAligns(array('L'));
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array('HABILIDADES Y DESTREZAS TECNICAS POR ADQUIRIR'));
##	
$sql = "SELECT
			ed.Descripcion
		FROM
			rh_empleado_desempenio ed
			INNER JOIN rh_evaluacionempleado ee ON (ed.CodOrganismo = ee.CodOrganismo AND
													ed.Periodo = ee.Periodo AND
													ed.Secuencia = ee.Secuencia AND
													ed.CodPersona = ee.CodPersona AND
													ed.Evaluador = ee.Evaluador)
		WHERE
			ed.CodPersona = '".$field['CodPersona']."' AND
			ed.Tipo = 'D' AND
			ee.Estado = 'EV'
		ORDER BY ed.SecuenciaDesempenio";
$query_fortalezaspa = mysql_query($sql) or die($sql.mysql_error());
while ($field_fortalezaspa = mysql_fetch_array($query_fortalezaspa)) {
	$pdf->Ln(1);
	$pdf->Cell(1, 2);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetWidths(array(8, 5, 180)); $pdf->SetAligns(array('C', 'C', 'L'));
	$pdf->Row(array(' ', '-', utf8_decode($field_fortalezaspa['Descripcion'])));
}

//	capacitacion requeridas para desarrollar competencias conductuales
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetWidths(array(195)); $pdf->SetAligns(array('L'));
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array('CAPACITACION REQUERIDAS PARA DESARROLLAR COMPETENCIAS CONDUCTUALES'));
##	
$sql = "SELECT
			en.Necesidad,
			en.Objetivo,
			en.Prioridad,
			c.Descripcion AS NomCurso
		FROM
			rh_empleado_necesidad en
			INNER JOIN rh_cursos c ON (en.CodCurso = c.CodCurso)
			INNER JOIN rh_evaluacionempleado ee ON (en.CodOrganismo = ee.CodOrganismo AND
													en.Periodo = ee.Periodo AND
													en.Secuencia = ee.Secuencia AND
													en.CodPersona = ee.CodPersona AND
													en.Evaluador = ee.Evaluador)
		WHERE
			en.CodPersona = '".$field['CodPersona']."' AND
			ee.Estado = 'EV'
		ORDER BY en.SecuenciaDesempenio";
$query_capacitacioncc = mysql_query($sql) or die($sql.mysql_error());
while ($field_capacitacioncc = mysql_fetch_array($query_capacitacioncc)) {
	$pdf->Ln(1);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetWidths(array(8, 5, 180)); $pdf->SetAligns(array('C', 'C', 'L'));
	$pdf->SetFont('Arial', 'BI', 8);
	$pdf->Cell(1, 2); $pdf->Row(array(' ', '-', utf8_decode($field_capacitacioncc['Necesidad'])));
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(1, 2); $pdf->Row(array(' ', ' ', utf8_decode('  '.$field_capacitacioncc['Objetivo'])));
	$pdf->Cell(1, 2); $pdf->Row(array(' ', ' ', utf8_decode('  '.$field_capacitacioncc['NomCurso'])));
	$pdf->Cell(1, 2); $pdf->Row(array(' ', ' ', utf8_decode('  '.strtoupper(printValoresGeneral("PRIORIDAD", $field_capacitacioncc['Prioridad'])))));
}

//	
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetWidths(array(195)); $pdf->SetAligns(array('L'));
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array('CAPACITACION PARA DESARROLLAR COMPETENCIAS TECNICAS REQUERIDAS PARA EJECUTAR SUS FUNCIONES DE ACUERDO AL MANUAL DESCRIPTIVO DE CARGOS'));
##	
$sql = "SELECT *
		FROM rh_asociacioncarrerascaptecnica
		WHERE
			CodOrganismo = '".$field['CodOrganismo']."' AND
			Secuencia = '".$field['Secuencia']."' AND
			Codigo = '".$field['Codigo']."'
		ORDER BY Linea";
$query_captecnica = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$nro_captecnica=0;
while($field_captecnica = mysql_fetch_array($query_captecnica)) {
	$pdf->Ln(1);
	$pdf->Cell(1, 2);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetWidths(array(8, 5, 180)); $pdf->SetAligns(array('C', 'C', 'L'));
	$pdf->Row(array(' ', '-', utf8_decode($field_captecnica['Descripcion'])));
}

//	
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetWidths(array(195)); $pdf->SetAligns(array('L'));
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array('HABILIDADES, DESTREZAS Y CAPACIDAD PARA REALIZAR ACTIVIDADES EXTRAORDINARIAS (NO CONTEMPLADAS EN EL MANUAL DESCRIPTIVO DE CARGOS)'));
##	
$sql = "SELECT *
		FROM rh_asociacioncarrerashabilidad
		WHERE
			CodOrganismo = '".$field['CodOrganismo']."' AND
			Secuencia = '".$field['Secuencia']."' AND
			Codigo = '".$field['Codigo']."'
		ORDER BY Tipo, Linea";
$query_habilidad = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$nro_habilidad=0;
while($field_habilidad = mysql_fetch_array($query_habilidad)) {
	$pdf->Ln(1);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetWidths(array(8, 5, 180)); $pdf->SetAligns(array('C', 'C', 'L'));
	if ($Grupo != $field_habilidad['Tipo']) {
		$Grupo = $field_habilidad['Tipo'];
		$pdf->SetFont('Arial', 'BI', 8);
		$pdf->Cell(1, 2); $pdf->Row(array(' ', ' ', utf8_decode('  '.printValores("TIPO-HABILIDAD", $field_habilidad['Tipo']))));
	}
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(1, 2); $pdf->Row(array(' ', '-', utf8_decode('  '.$field_habilidad['Descripcion'])));
}

//	
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetWidths(array(195)); $pdf->SetAligns(array('L'));
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array('EVALUACION CUALITATIVA PARA SER ASCENDIDO AL CARGO INMEDIATAMENTE SUPERIOR'));
##	
$sql = "SELECT *
		FROM rh_asociacioncarrerasevaluacion
		WHERE
			CodOrganismo = '".$field['CodOrganismo']."' AND
			Secuencia = '".$field['Secuencia']."' AND
			Codigo = '".$field['Codigo']."'
		ORDER BY Linea";
$query_evaluacion = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$nro_evaluacion=0;
while($field_evaluacion = mysql_fetch_array($query_evaluacion)) {
	$pdf->Ln(1);
	$pdf->Cell(1, 2);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetWidths(array(8, 5, 180)); $pdf->SetAligns(array('C', 'C', 'L'));
	$pdf->Row(array(' ', '-', utf8_decode($field_evaluacion['Descripcion'])));
}

//	
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetWidths(array(195)); $pdf->SetAligns(array('L'));
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array('METAS QUE DEBE SUPERAR PARA LOGRAR ASCENDER'));
##	
$sql = "SELECT *
		FROM rh_asociacioncarrerasmetas
		WHERE
			CodOrganismo = '".$field['CodOrganismo']."' AND
			Secuencia = '".$field['Secuencia']."' AND
			Codigo = '".$field['Codigo']."'
		ORDER BY Linea";
$query_metas = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$nro_metas=0;
while($field_metas = mysql_fetch_array($query_metas)) {
	$pdf->Ln(1);
	$pdf->Cell(1, 2);
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetWidths(array(8, 5, 180)); $pdf->SetAligns(array('C', 'C', 'L'));
	$pdf->Row(array(' ', '-', utf8_decode($field_metas['Descripcion'])));
}
//---------------------------------------------------

//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
