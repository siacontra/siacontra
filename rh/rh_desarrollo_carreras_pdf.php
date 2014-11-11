<?php
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("lib/fphp.php");
//---------------------------------------------------
list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
$FechaActual = "$DiaActual-$MesActual-$AnioActual";
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
		$NomDependencia = getValorCampo("mastdependencias", "CodDependencia", "Dependencia", $_PARAMETRO["DEPRHPR"]);
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
	}
	
	//	Pie de página.
	function Footer() {
		$this->SetTextColor(50, 50, 50);
		$this->SetFont('Arial', 'BI', 7);
		$this->SetXY(10, 260); $this->Cell(195, 5, utf8_decode('Hacia la Consolidación y Fortalecimiento del Sistema Nacional de Control Fiscal'), 0, 1, 'C', 0);
		$this->SetXY(10, 264); $this->Cell(195, 5, utf8_decode('Calle Centurion - Quinta Paola N° 36 / Telefono (0287) 7211344 - Fax (0287) 7211655'), 0, 1, 'C', 0);
		$this->SetXY(10, 268); $this->Cell(195, 5, utf8_decode('Tucupita, Estado Delta Amacuro.'), 0, 1, 'C', 0);
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
$pdf->SetDrawColor(255, 255, 255);

//	datos del empleado
$pdf->SetFillColor(230, 230, 230);
$pdf->SetWidths(array(145, 25, 25)); $pdf->SetAligns(array('L', 'C', 'C'));
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array('NOMBRES Y APELLIDOS', 'CEDULA', 'INGRESO'));
$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('Arial', '', 8);
$pdf->Row(array(utf8_decode($field['NomPersona']), number_format($field['Ndocumento'], 0, '', '.'), formatFechaDMA($field['Fingreso'])));
##
$pdf->SetFillColor(230, 230, 230);
$pdf->SetWidths(array(170, 25)); $pdf->SetAligns(array('L', 'C'));
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array('CARGO', 'GRADO'));
$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('Arial', '', 8);
$pdf->Row(array(utf8_decode($field['DescripCargo']), $field['Grado']));

//	experiencia laboral en la institucion
$pdf->SetFillColor(230, 230, 230);
$pdf->SetWidths(array(195)); $pdf->SetAligns(array('L'));
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array('EXPERIENCIA LABORAL EN LA INSTITUCION'));
$pdf->SetFillColor(255, 255, 255);
##	
$sql = "SELECT
   			enh.Secuencia,
   			enh.Fecha,
   			enh.Cargo,
   			enh.TipoAccion,
   			en.CodCargo,
   			en.FechaHasta
		FROM
			rh_empleadonivelacionhistorial enh
			INNER JOIN rh_empleadonivelacion en ON (en.CodPersona = enh.CodPersona AND en.Secuencia = enh.Secuencia)
		WHERE enh.CodPersona = '".$field['CodPersona']."'
		ORDER BY Secuencia";
$query_experiencia = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$nro_experiencia=0;
while($field_experiencia = mysql_fetch_array($query_experiencia)) {
	$nro_nivel++;
	$_DESDE = formatFechaDMA($field_experiencia['Fecha']);
	if ($field_experiencia['FechaHasta'] == '0000-00-00') $_HASTA = $FechaActual; else $_HASTA = formatFechaDMA($field_experiencia['FechaHasta']);
	list($A, $M, $D) = getTiempo($_DESDE, $_HASTA);
	$Experiencia = $field_experiencia['Cargo'].' ('.$A.' años - '.$M.' meses - '.$D.' dias)';
	##
	$pdf->Ln(1);
	$pdf->Cell(1, 2);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetWidths(array(8, 5, 180)); $pdf->SetAligns(array('C', 'C', 'L'));
	$pdf->Row(array(' ', '-', utf8_decode($Experiencia)));
}

//	nivel academico
$pdf->SetFillColor(230, 230, 230);
$pdf->SetWidths(array(195)); $pdf->SetAligns(array('L'));
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array('NIVEL ACADEMICO'));
$pdf->SetFillColor(255, 255, 255);
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
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetWidths(array(8, 5, 180)); $pdf->SetAligns(array('C', 'C', 'L'));
	$pdf->Row(array(' ', '-', utf8_decode($Profesion)));
}
if ($nro_nivel == 0) {
	$pdf->Ln(1);
	$pdf->SetFont('Arial', 'I', 8);
	$pdf->Cell(195, 8, utf8_decode('(SIN NIVEL ACADEMICO)'), 0, 1, 'C', 0);
	$pdf->Ln(1);
}

//	cursos realizados en el area
$pdf->SetFillColor(230, 230, 230);
$pdf->SetWidths(array(195)); $pdf->SetAligns(array('L'));
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array('CURSOS REALIZADOS EN EL AREA'));
$pdf->SetFillColor(255, 255, 255);
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
	$nro_cursosa++;
	$pdf->Ln(1);
	$pdf->Cell(1, 2);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetWidths(array(8, 5, 180)); $pdf->SetAligns(array('C', 'C', 'L'));
	$pdf->Row(array(' ', '-', utf8_decode($field_cursosa['NomCurso'])));
}
if ($nro_cursosa == 0) {
	$pdf->Ln(1);
	$pdf->SetFont('Arial', 'I', 8);
	$pdf->Cell(195, 8, utf8_decode('(NO HA REALIZADO CURSOS EN EL AREA)'), 0, 1, 'C', 0);
	$pdf->Ln(1);
}

//	cursos realizados en formacion general
$pdf->SetFillColor(230, 230, 230);
$pdf->SetWidths(array(195)); $pdf->SetAligns(array('L'));
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array('CURSOS REALIZADOS EN FORMACION GENERAL'));
$pdf->SetFillColor(255, 255, 255);
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
	$nro_cursosfg++;
	$pdf->Ln(1);
	$pdf->Cell(1, 2);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetWidths(array(8, 5, 180)); $pdf->SetAligns(array('C', 'C', 'L'));
	$pdf->Row(array(' ', '-', utf8_decode($field_cursosfg['NomCurso'])));
}
if ($nro_cursosfg == 0) {
	$pdf->Ln(1);
	$pdf->SetFont('Arial', 'I', 8);
	$pdf->Cell(195, 8, utf8_decode('(NO HA REALIZADO CURSOS EN FORMACION GENERAL)'), 0, 1, 'C', 0);
	$pdf->Ln(1);
}

//	competencias conductuales adquiridas
$pdf->SetFillColor(230, 230, 230);
$pdf->SetWidths(array(195)); $pdf->SetAligns(array('L'));
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array('COMPETENCIAS CONDUCTUALES ADQUIRIDAS'));
$pdf->SetFillColor(255, 255, 255);
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
	$nro_competenciasca++;
	$pdf->Ln(1);
	$pdf->SetWidths(array(8, 5, 180)); $pdf->SetAligns(array('C', 'C', 'L'));
	$pdf->SetFont('Arial', 'BI', 8);
	$pdf->Cell(1, 2); $pdf->Row(array(' ', '-', utf8_decode($field_competenciasca['Descripcion'])));
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(1, 2); $pdf->Row(array(' ', ' ', utf8_decode('  '.$field_competenciasca['Explicacion'])));
	$pdf->Cell(1, 2); $pdf->Row(array(' ', ' ', utf8_decode('  '.$field_competenciasca['Explicacion2'])));
}
if ($nro_competenciasca == 0) {
	$pdf->Ln(1);
	$pdf->SetFont('Arial', 'I', 8);
	$pdf->Cell(195, 8, utf8_decode('(NO SE HAN EVALUADO)'), 0, 1, 'C', 0);
	$pdf->Ln(1);
}

//	competencias conductuales por adquirir
$pdf->SetFillColor(230, 230, 230);
$pdf->SetWidths(array(195)); $pdf->SetAligns(array('L'));
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array('COMPETENCIAS CONDUCTUALES O GENERICAS POR ADQUIRIR'));
$pdf->SetFillColor(255, 255, 255);
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
	$nro_competenciascgpa++;
	$pdf->Ln(1);
	$pdf->SetWidths(array(8, 5, 180)); $pdf->SetAligns(array('C', 'C', 'L'));
	$pdf->SetFont('Arial', 'BI', 8);
	$pdf->Cell(1, 2); $pdf->Row(array(' ', '-', utf8_decode($field_competenciascgpa['Descripcion'])));
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(1, 2); $pdf->Row(array(' ', ' ', utf8_decode('  '.$field_competenciascgpa['Explicacion'])));
	$pdf->Cell(1, 2); $pdf->Row(array(' ', ' ', utf8_decode('  '.$field_competenciascgpa['Explicacion2'])));
}
if ($nro_competenciascgpa == 0) {
	$pdf->Ln(1);
	$pdf->SetFont('Arial', 'I', 8);
	$pdf->Cell(195, 8, utf8_decode('(NO SE HAN EVALUADO)'), 0, 1, 'C', 0);
	$pdf->Ln(1);
}

//	habllidades y destrezas tecnicas adquiridas
$pdf->SetFillColor(230, 230, 230);
$pdf->SetWidths(array(195)); $pdf->SetAligns(array('L'));
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array('HABILIDADES Y DESTREZAS TECNICAS ADQUIRIDAS'));
$pdf->SetFillColor(255, 255, 255);
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
	$nro_fortalezasa++;
	$pdf->Ln(1);
	$pdf->Cell(1, 2);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetWidths(array(8, 5, 180)); $pdf->SetAligns(array('C', 'C', 'L'));
	$pdf->Row(array(' ', '-', utf8_decode($field_fortalezasa['Descripcion'])));
}
if ($nro_fortalezasa == 0) {
	$pdf->Ln(1);
	$pdf->SetFont('Arial', 'I', 8);
	$pdf->Cell(195, 8, utf8_decode('(NO SE HAN EVALUADO)'), 0, 1, 'C', 0);
	$pdf->Ln(1);
}

//	habllidades y destrezas tecnicas por adquirir
$pdf->SetFillColor(230, 230, 230);
$pdf->SetWidths(array(195)); $pdf->SetAligns(array('L'));
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array('HABILIDADES Y DESTREZAS TECNICAS POR ADQUIRIR'));
$pdf->SetFillColor(255, 255, 255);
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
	$nro_fortalezaspa++;
	$pdf->Ln(1);
	$pdf->Cell(1, 2);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetWidths(array(8, 5, 180)); $pdf->SetAligns(array('C', 'C', 'L'));
	$pdf->Row(array(' ', '-', utf8_decode($field_fortalezaspa['Descripcion'])));
}
if ($nro_fortalezaspa == 0) {
	$pdf->Ln(1);
	$pdf->SetFont('Arial', 'I', 8);
	$pdf->Cell(195, 8, utf8_decode('(NO SE HAN EVALUADO)'), 0, 1, 'C', 0);
	$pdf->Ln(1);
}

//	capacitacion requeridas para desarrollar competencias conductuales
$pdf->SetFillColor(230, 230, 230);
$pdf->SetWidths(array(195)); $pdf->SetAligns(array('L'));
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array('CAPACITACION REQUERIDAS PARA DESARROLLAR COMPETENCIAS CONDUCTUALES'));
$pdf->SetFillColor(255, 255, 255);
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
	$nro_capacitacioncc++;
	$pdf->Ln(1);
	$pdf->SetWidths(array(8, 5, 180)); $pdf->SetAligns(array('C', 'C', 'L'));
	$pdf->SetFont('Arial', 'BI', 8);
	$pdf->Cell(1, 2); $pdf->Row(array(' ', '-', utf8_decode($field_capacitacioncc['Necesidad'])));
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(1, 2); $pdf->Row(array(' ', ' ', utf8_decode('  '.$field_capacitacioncc['Objetivo'])));
	$pdf->Cell(1, 2); $pdf->Row(array(' ', ' ', utf8_decode('  '.$field_capacitacioncc['NomCurso'])));
	$pdf->Cell(1, 2); $pdf->Row(array(' ', ' ', utf8_decode('  '.strtoupper(printValoresGeneral("PRIORIDAD", $field_capacitacioncc['Prioridad'])))));
}
if ($nro_capacitacioncc == 0) {
	$pdf->Ln(1);
	$pdf->SetFont('Arial', 'I', 8);
	$pdf->Cell(195, 8, utf8_decode('(NO SE HAN EVALUADO)'), 0, 1, 'C', 0);
	$pdf->Ln(1);
}

//	
$pdf->SetFillColor(230, 230, 230);
$pdf->SetWidths(array(195)); $pdf->SetAligns(array('L'));
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array('CAPACITACION PARA DESARROLLAR COMPETENCIAS TECNICAS REQUERIDAS PARA EJECUTAR SUS FUNCIONES DE ACUERDO AL MANUAL DESCRIPTIVO DE CARGOS'));
$pdf->SetFillColor(255, 255, 255);
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
	$nro_captecnica++;
	$pdf->Ln(1);
	$pdf->Cell(1, 2);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetWidths(array(8, 5, 180)); $pdf->SetAligns(array('C', 'C', 'L'));
	$pdf->Row(array(' ', '-', utf8_decode($field_captecnica['Descripcion'])));
}
if ($nro_captecnica == 0) {
	$pdf->Ln(1);
	$pdf->SetFont('Arial', 'I', 8);
	$pdf->Cell(195, 8, utf8_decode('(NO SE HAN INGRESADOS)'), 0, 1, 'C', 0);
	$pdf->Ln(1);
}

//	
$pdf->SetFillColor(230, 230, 230);
$pdf->SetWidths(array(195)); $pdf->SetAligns(array('L'));
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array('HABILIDADES, DESTREZAS Y CAPACIDAD PARA REALIZAR ACTIVIDADES EXTRAORDINARIAS (NO CONTEMPLADAS EN EL MANUAL DESCRIPTIVO DE CARGOS)'));
$pdf->SetFillColor(255, 255, 255);
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
	$nro_habilidad++;
	$pdf->Ln(1);
	$pdf->SetWidths(array(8, 5, 180)); $pdf->SetAligns(array('C', 'C', 'L'));
	if ($Grupo != $field_habilidad['Tipo']) {
		$Grupo = $field_habilidad['Tipo'];
		$pdf->SetFont('Arial', 'BI', 8);
		$pdf->Cell(1, 2); $pdf->Row(array(' ', ' ', utf8_decode('  '.printValores("TIPO-HABILIDAD", $field_habilidad['Tipo']))));
	}
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(1, 2); $pdf->Row(array(' ', '-', utf8_decode('  '.$field_habilidad['Descripcion'])));
}
if ($nro_habilidad == 0) {
	$pdf->Ln(1);
	$pdf->SetFont('Arial', 'I', 8);
	$pdf->Cell(195, 8, utf8_decode('(NO SE HAN INGRESADOS)'), 0, 1, 'C', 0);
	$pdf->Ln(1);
}

//	
$pdf->SetFillColor(230, 230, 230);
$pdf->SetWidths(array(195)); $pdf->SetAligns(array('L'));
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array('EVALUACION CUALITATIVA PARA SER ASCENDIDO AL CARGO INMEDIATAMENTE SUPERIOR'));
$pdf->SetFillColor(255, 255, 255);
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
	$nro_evaluacion++;
	$pdf->Ln(1);
	$pdf->Cell(1, 2);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetWidths(array(8, 5, 180)); $pdf->SetAligns(array('C', 'C', 'L'));
	$pdf->Row(array(' ', '-', utf8_decode($field_evaluacion['Descripcion'])));
}
if ($nro_evaluacion == 0) {
	$pdf->Ln(1);
	$pdf->SetFont('Arial', 'I', 8);
	$pdf->Cell(195, 8, utf8_decode('(NO SE HAN INGRESADOS)'), 0, 1, 'C', 0);
	$pdf->Ln(1);
}

//	
$pdf->SetFillColor(230, 230, 230);
$pdf->SetWidths(array(195)); $pdf->SetAligns(array('L'));
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array('METAS QUE DEBE SUPERAR PARA LOGRAR ASCENDER'));
$pdf->SetFillColor(255, 255, 255);
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
	$nro_metas++;
	$pdf->Ln(1);
	$pdf->Cell(1, 2);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetWidths(array(8, 5, 180)); $pdf->SetAligns(array('C', 'C', 'L'));
	$pdf->Row(array(' ', '-', utf8_decode($field_metas['Descripcion'])));
}
if ($nro_metas == 0) {
	$pdf->Ln(1);
	$pdf->SetFont('Arial', 'I', 8);
	$pdf->Cell(195, 8, utf8_decode('(NO SE HAN INGRESADOS)'), 0, 1, 'C', 0);
	$pdf->Ln(1);
}
##	firmas
list($_JEFE['Nombre'], $_JEFE['Cargo'], $_JEFE['Nivel']) = getFirmaxDependencia($field["CodDependencia"]);
list($_CONFORME['Nombre'], $_CONFORME['Cargo'], $_CONFORME['Nivel']) = getFirmaxDependencia($_PARAMETRO["DEPRHPR"]);
##
$pdf->Ln(20);
$y = $pdf->GetY();
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(0, 0, 0);
$pdf->Rect(23, 240, 60, 0.1, 'DF');
$pdf->Rect(138, 240, 60, 0.1, 'DF');
$pdf->GetY($y+1);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetXY(10, 240); $pdf->Cell(85, 4, utf8_decode(strtoupper($_JEFE['Nivel'].' '.$_JEFE['Nombre'])), 0, 1, 'C', 0);
$pdf->SetXY(125, 240); $pdf->Cell(85, 4, utf8_decode(strtoupper($_CONFORME['Nivel'].' '.$_CONFORME['Nombre'])), 0, 1, 'C', 0);

$pdf->SetXY(10, 244); $pdf->Cell(85, 4, utf8_decode(strtoupper($_JEFE['Cargo'])), 0, 1, 'C', 0);
$pdf->SetXY(125, 244); $pdf->Cell(85, 4, utf8_decode(strtoupper($_CONFORME['Cargo'])), 0, 1, 'C', 0);

//---------------------------------------------------

//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
