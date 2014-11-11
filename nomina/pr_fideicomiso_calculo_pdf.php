<?php
require('../lib/fpdf.php');
include("../lib/fphp.php");
include("lib/fphp.php");
//---------------------------------------------------
if ($PeriodoDesde != "") $filtro .= " AND SUBSTRING(Periodo, 1, 4) >= '".$PeriodoDesde."'";
if ($PeriodoHasta != "") $filtro .= " AND SUBSTRING(Periodo, 1, 4) <= '".$PeriodoHasta."'";
//---------------------------------------------------
//	consulto los datos
$sql = "SELECT
			p.CodPersona,
			p.NomCompleto,
			p.Ndocumento,
			e.CodOrganismo,
			e.Fingreso
		FROM
			mastpersonas p
			INNER JOIN mastempleado e ON (e.CodPersona = p.CodPersona)
		WHERE p.CodPersona = '".$CodPersona."'";
$query_empleado = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query_empleado) != 0) $field_empleado = mysql_fetch_array($query_empleado);
//---------------------------------------------------

class PDF extends FPDF {
	//	Cabecera de página.
	function Header() {
		global $_PARAMETRO;
		global $Ahora;
		global $_POST;
		global $field_empleado;
		global $field;
		global $filtro;
		extract($_POST);
		##
		$Logo = getValorCampo("mastorganismos", "CodOrganismo", "Logo", $field_empleado['CodOrganismo']);
		$NomOrganismo = getValorCampo("mastorganismos", "CodOrganismo", "Organismo", $field_empleado['CodOrganismo']);
		$NomDependencia = getValorCampo("mastdependencias", "CodDependencia", "Dependencia", $_PARAMETRO["DEPRHPR"]);
		##
		$this->SetFillColor(255, 255, 255);
		$this->SetDrawColor(0, 0, 0);
		$this->Image($_PARAMETRO["PATHLOGO"].$Logo, 10, 5, 10, 10);		
		$this->SetFont('Arial', '', 8);
		$this->SetXY(20, 5); $this->Cell(100, 5, $NomOrganismo, 0, 1, 'L');
		$this->SetXY(20, 10); $this->Cell(100, 5, $NomDependencia, 0, 0, 'L');	
		$this->SetFont('Arial', '', 8);
		$this->SetXY(240, 5); $this->Cell(20, 5, utf8_decode('Fecha: '), 0, 0, 'L');
		$this->Cell(30, 5, formatFechaDMA(substr($Ahora, 0, 10)), 0, 1, 'L');
		$this->SetXY(240, 10); $this->Cell(20, 5, utf8_decode('Página: '), 0, 0, 'L'); 
		$this->Cell(30, 5, $this->PageNo().' de {nb}', 0, 1, 'L');
		$this->SetFont('Arial', 'B', 10);
		$this->SetXY(10, 20); $this->Cell(280, 5, utf8_decode('CALCULO DE FIDEICOMISOS'), 0, 1, 'C', 0);
		##
		$this->SetTextColor(0, 0, 0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(255, 255, 255);
		##
		$this->Ln(5);
		$this->SetFont('Arial', '', 9);
		$this->Cell(28, 5, 'Nombre y Apelllido:', 0, 0, 'L', 0);
		$this->SetFont('Arial', 'B', 9);
		$this->Cell(80, 5, utf8_decode($field_empleado['NomCompleto']), 0, 0, 'L', 0);
		$this->SetFont('Arial', '', 9);
		$this->Cell(18, 5, utf8_decode('Cédula:'), 0, 0, 'L', 0);
		$this->SetFont('Arial', 'B', 9);
		$this->Cell(80, 5, $field_empleado['Ndocumento'], 0, 0, 'L', 0);
		$this->Ln(5);
		$this->SetFont('Arial', '', 9);
		$this->Cell(28, 5, 'Fecha de Ingreso:', 0, 0, 'L', 0);
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(80, 5, formatFechaDMA($field_empleado['Fingreso']), 0, 0, 'L', 0);
		$this->SetFont('Arial', '', 9);
		$this->Cell(18, 5, utf8_decode('Antiguedad: '), 0, 0, 'L', 0);
		$this->SetFont('Arial', 'B', 9);
		$this->Cell(80, 5, utf8_decode('Años: '.$Anios.'          Meses: '.$Meses.'          Dias: '.$Dias), 0, 0, 'L', 0);
		$this->Ln(10);
		##
		$this->SetFont('Arial', 'B', 8);
		$this->SetWidths(array(14, 20, 21, 12, 12, 16, 16, 8, 20, 20, 20, 11, 10, 20, 20, 20, 20));
		$this->SetAligns(array('C', 'R', 'R', 'R', 'R', 'R', 'R', 'C', 'R', 'R', 'R', 'R', 'C', 'R', 'R', 'R', 'R'));
		$this->Row(array('Periodo',
						 'Sueldo Mensual',
						 'Bonos',
						 'Alic. Vac.',
						 'Alic. Fin.',
						 'Remun. Diaria',
						 'Sueldo + Alic.',
						 'Dias',
						 'Prest. Antig. Mensual',
						 'Prest. Compl.',
						 'Prest. Acumulada',
						 'Tasa (%)',
						 'Dias Mes',
						 utf8_decode('Interés Mensual'),
						 utf8_decode('Interés Acumulado'),
						 utf8_decode('Anticipo Prestación'),
						 utf8_decode('Anticipo Interés')));
		$this->Ln(1);
	}
	
	//	Pie de página.
	function Footer() {
		$this->SetTextColor(0, 0, 0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetFillColor(0, 0, 0);
		
		$this->SetFont('Arial', 'B', 8);
		$this->SetXY(10, 184); $this->MultiCell(65, 4, 'PREPARADO POR', 0, 'C');
		$this->SetXY(110, 184); $this->MultiCell(65, 4, 'REVISADO POR', 0, 'C');
		$this->SetXY(210, 184); $this->MultiCell(65, 4, 'CONFORMADO POR', 0, 'C');
		$this->SetXY(10, 190); $this->MultiCell(65, 4, 'LICDA. ANDREINA ZAPATA', 0, 'C');
		$this->SetXY(110, 190); $this->MultiCell(65, 4, 'LICDA. CARMEN ALFONZO', 0, 'C');
		$this->SetXY(210, 190); $this->MultiCell(65, 4, 'ABOG. MARLYN ABREU', 0, 'C');
		$this->SetXY(10, 196); $this->MultiCell(65, 4, 'ANALISTA DE RECURSOS HUMANOS I', 0, 'C');
		$this->SetXY(110, 196); $this->MultiCell(65, 4, 'ANALISTA DE RECURSOS HUMANOS II', 0, 'C');
		$this->SetXY(210, 196); $this->MultiCell(65, 4, 'DIRECTORA DE RECURSOS HUMANOS', 0, 'C');
	}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada.
$pdf = new PDF('L', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->SetMargins(10, 5, 10);
$pdf->SetAutoPageBreak(5, 5);
//---------------------------------------------------
//	consulto
$sql = "SELECT *
		FROM pr_fideicomisocalculo
		WHERE CodPersona = '".$CodPersona."' $filtro";
$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$i=0;
if (mysql_num_rows($query) == 0) $pdf->AddPage();
while ($field = mysql_fetch_array($query)) {
	if ($i==0) {
		$pdf->AddPage();
		
		//	imprimo anterior
		list($PrestAcumulada, $InteresAcumulado, $Dias) = prestacionAcumulada($field['CodPersona'], $field['Periodo']);
		##
		$pdf->SetDrawColor(255, 255, 255);
		$pdf->SetFillColor(200, 200, 200);
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Row(array('',
						'',
						'',
						'',
						'',
						'',
						'',
						$Dias,
						'',
						'',
						number_format($PrestAcumulada, 2, ',', '.'),
						'',
						'',
						'',
						number_format($InteresAcumulado, 2, ',', '.'),
						'',
						''));
		$pdf->Ln(1);
	}
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetDrawColor(255, 255, 255);
	if (++$i % 2 == 0) $pdf->SetFillColor(245, 245, 245); else $pdf->SetFillColor(255, 255, 255);
	$pdf->SetFont('Arial', '', 9);
	$pdf->Row(array($field['Periodo'],
					number_format($field['SueldoMensual'], 2, ',', '.'),
					number_format($field['Bonificaciones'], 2, ',', '.'),
					number_format($field['AliVac'], 2, ',', '.'),
					number_format($field['AliFin'], 2, ',', '.'),
					number_format($field['SueldoDiario'], 2, ',', '.'),
					number_format($field['SueldoDiarioAli'], 2, ',', '.'),
					$field['Dias'],
					number_format($field['PrestAntiguedad'], 2, ',', '.'),
					number_format($field['PrestComplemento'], 2, ',', '.'),
					number_format($field['PrestAcumulada'], 2, ',', '.'),
					number_format($field['Tasa'], 2, ',', '.'),
					$field['DiasMes'],
					number_format($field['InteresMensual'], 2, ',', '.'),
					number_format($field['InteresAcumulado'], 2, ',', '.'),
					number_format($field['Anticipo'], 2, ',', '.'),
					number_format($field['Anticipo'], 2, ',', '.')));
	$pdf->Ln(2);
	$Dias = $Dias + $field['Dias'];
	$PrestAcumulada = $field['PrestAcumulada'];
	$InteresAcumulado = $field['InteresAcumulado'];
}
##
$pdf->SetDrawColor(255, 255, 255);
$pdf->SetFillColor(200, 200, 200);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Row(array('',
				'',
				'',
				'',
				'',
				'',
				'',
				$Dias,
				'',
				'',
				number_format($PrestAcumulada, 2, ',', '.'),
				'',
				'',
				'',
				number_format($InteresAcumulado, 2, ',', '.'),
				'',
				''));
//---------------------------------------------------

//---------------------------------------------------
//	Muestro el contenido del pdf.
$pdf->Output();
?>  
