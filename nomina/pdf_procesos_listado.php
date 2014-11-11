<?php
define('FPDF_FONTPATH','font/');
require('mc_table3.php');
require('fphp_nomina.php');
connect();
extract($_POST);
extract($_GET);
//---------------------------------------------------

//---------------------------------------------------
//	Imprime la cabedera del documento
function Cabecera() {
	global $pdf;
	global $ftiponom;
	global $field_nomina;
	global $field_proceso;
	global $periodo;
	global $periodo_fecha;
	global $_POST;
	global $_GET;
	extract($_POST);
	extract($_GET);
	
	$pdf->AddPage();
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	
	$pdf->Cell(190, 5, ('CONTRALORIA DEL ESTADO MONAGAS'), 0, 1, 'L');
	$pdf->Cell(190, 5, ('DIRECCION DE RECURSOS HUMANOS'), 0, 1, 'L');
	$pdf->Cell(190, 5, utf8_encode('TIPO DE NOMINA '.$field_nomina['Nomina']), 0, 1, 'L');
	$pdf->Cell(190, 5, utf8_encode($field_proceso['Descripcion']), 0, 1, 'L');
	$pdf->Ln(1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(190, 5, ($periodo_fecha), 0, 1, 'C');
	$pdf->Ln(3);
	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	if ($flaggrado != "S" && $flagcargo != "S") {
		$pdf->SetWidths(array(20, 150, 25));
		$pdf->SetAligns(array('R', 'L', 'R'));
		$pdf->Row(array('CEDULA', 'NOMBRES Y APELLIDOS', 'MONTO'));
	}
	elseif ($flagcargo != "S") {
		$pdf->SetWidths(array(20, 140, 10, 25));
		$pdf->SetAligns(array('R', 'L', 'C', 'R'));
		$pdf->Row(array('CEDULA', 'NOMBRES Y APELLIDOS', 'GR.', 'MONTO'));
	}
	elseif ($flaggrado != "S") {
		$pdf->SetWidths(array(20, 60, 90, 25));
		$pdf->SetAligns(array('R', 'L', 'L', 'R'));
		$pdf->Row(array('CEDULA', 'NOMBRES Y APELLIDOS', 'CARGO', 'MONTO'));
	}
	else {
		$pdf->SetWidths(array(20, 60, 10, 80, 25));
		$pdf->SetAligns(array('R', 'L', 'C', 'L', 'R'));
		$pdf->Row(array('CEDULA', 'NOMBRES Y APELLIDOS', 'GR.', 'CARGO', 'MONTO'));
	}
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf = new PDF_MC_Table();
$pdf->Open();
$pdf->SetMargins(10, 15, 10);

//	Tipo de Nomina
$sql = "SELECT Nomina FROM tiponomina WHERE CodTipoNom = '".$ftiponom."'";
$query_nomina = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_nomina) != 0) $field_nomina = mysql_fetch_array($query_nomina);

//	Tipo de Proceso
$sql = "SELECT Descripcion FROM pr_tipoproceso WHERE CodTipoProceso = '".$ftproceso."'";
$query_proceso = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_proceso) != 0) $field_proceso = mysql_fetch_array($query_proceso);

//	Periodo
$periodo = getPeriodoLetras($fperiodo);

/*list($anio, $mes)=SPLIT( '[/.-]', $fperiodo); $m = (int) $mes;
$dias = getDiasMes($anio, $m);

if ($ftproceso == "ADE") $periodo_fecha = "DESDE: 01/$mes/$anio HASTA: 15/$mes/$anio";

else $periodo_fecha = "DESDE: 01/$mes/$anio HASTA: $dias/$mes/$anio";
*/

$periodo_fecha = $periodo;

Cabecera();

//	Cuerpo
$sql = "(SELECT 
			p.CodPersona,
			p.Ndocumento,
			p.NomCompleto,
			tne.TotalIngresos,
			e.CodCargo,
			pu.DescripCargo,
			pu.Grado
		FROM
			mastpersonas p			
			INNER JOIN pr_tiponominaempleado tne ON (p.CodPersona = tne.CodPersona)
			INNER JOIN mastempleado e ON (p.CodPersona = e.CodPersona)
			INNER JOIN rh_puestos pu ON (e.CodCargo = pu.CodCargo)
		WHERE
			e.CodCargoTemp = '' AND
			tne.CodTipoNom = '".$ftiponom."' AND
			tne.Periodo = '".$fperiodo."' AND
			tne.CodTipoProceso = '".$ftproceso."')
		UNION
		(SELECT 
			p.CodPersona,
			p.Ndocumento,
			p.NomCompleto,
			tne.TotalIngresos,
			e.CodCargoTemp AS CodCargo,
			pu.DescripCargo,
			pu.Grado
		FROM
			mastpersonas p			
			INNER JOIN pr_tiponominaempleado tne ON (p.CodPersona = tne.CodPersona)
			INNER JOIN mastempleado e ON (p.CodPersona = e.CodPersona)
			INNER JOIN rh_puestos pu ON (e.CodCargoTemp = pu.CodCargo)
		WHERE
			e.CodCargoTemp <> '' AND
			tne.CodTipoNom = '".$ftiponom."' AND
			tne.Periodo = '".$fperiodo."' AND
			tne.CodTipoProceso = '".$ftproceso."')
		ORDER BY $fordenar";
$query = mysql_query($sql) or die ($sql.mysql_error());	$i=0;
while ($field = mysql_fetch_array($query)) {	$i++;	
	//	solo si esta ordenado por grado muestro subtotales
	if ($fordenar == "Grado, length(Ndocumento), Ndocumento") {
		if ($grupo != $field['Grado']) {
			$grupo = $field['Grado'];
			if ($i > 1) {
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->Cell(170, 5, 'SUB-TOTAL', 1, 0, 'R');
				$pdf->Cell(25, 5, number_format($sum_submonto, 2, ',', '.'), 1, 1, 'R');
				$pdf->Ln(2);
				$sum_submonto = 0;
			}
		}
	}
	$sum_monto += $field['TotalIngresos'];
	$sum_submonto += $field['TotalIngresos'];
	
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	if ($flaggrado != "S" && $flagcargo != "S") {
		$pdf->Row(array(number_format($field['Ndocumento'], 0, '', '.'), 
						utf8_decode($field['NomCompleto']), 
						number_format($field['TotalIngresos'], 2, ',', '.')));
	}
	elseif ($flagcargo != "S") {
		$pdf->Row(array(number_format($field['Ndocumento'], 0, '', '.'), 
						utf8_encode($field['NomCompleto']), 
						utf8_encode($field['Grado']), 
						number_format($field['TotalIngresos'], 2, ',', '.')));
	}
	elseif ($flaggrado != "S") {
		$pdf->Row(array(number_format($field['Ndocumento'], 0, '', '.'), 
						utf8_decode($field['NomCompleto']), 
						utf8_decode($field['DescripCargo']), 
						number_format($field['TotalIngresos'], 2, ',', '.')));
	}
	else {
		$pdf->Row(array(number_format($field['Ndocumento'], 0, '', '.'), 
						utf8_decode($field['NomCompleto']), 
						utf8_decode($field['Grado']), 
						utf8_decode($field['DescripCargo']), 
						number_format($field['TotalIngresos'], 2, ',', '.')));
	}
	
	if ($pdf->GetY() > 260) Cabecera();
}
//	solo si esta ordenado por grado muestro subtotales
if ($fordenar == "Grado, length(Ndocumento), Ndocumento") {
	if ($grupo != $field['Grado']) {
		$grupo = $field['Grado'];
		if ($i > 1) {
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Cell(170, 5, 'SUB-TOTAL', 1, 0, 'R');
			$pdf->Cell(25, 5, number_format($sum_submonto, 2, ',', '.'), 1, 1, 'R');
			$pdf->Ln(2);
			$sum_submonto = 0;
		}
	}
}
//---------------------------------------------------
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(170, 5, 'TOTAL', 1, 0, 'R');
$pdf->Cell(25, 5, number_format($sum_monto, 2, ',', '.'), 1, 1, 'R');
//---------------------------------------------------
if ($pdf->GetY() > 260) Cabecera();

$y = $pdf->GetY() + 20;
//---------------------------------------------------

//---------------------------------------------------
list($nomelaborado, $carelaborado) = getFirmaNomina($ftiponom, $fperiodo, $ftproceso, "ProcesadoPor");
list($nomaprobado, $caraprobado) = getFirmaNomina($ftiponom, $fperiodo, $ftproceso, "AprobadoPor");
//---------------------------------------------------
$pdf->Rect(10, $y+5, 70, 0.1, "DF");
$pdf->Rect(120, $y+5, 70, 0.1, "DF");
$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(10, $y+10);
$pdf->Cell(110, 4, ('ELABORADO POR:'), 0, 0, 'L');
$pdf->Cell(80, 4, ('CONFORMADO POR:'), 0, 1, 'L');

$pdf->Cell(110, 4, utf8_decode($nomelaborado), 0, 0, 'L');
$pdf->Cell(80, 4, utf8_decode($nomaprobado), 0, 1, 'L');

$pdf->Cell(110, 4, utf8_decode($carelaborado), 0, 0, 'L');
$pdf->Cell(80, 4, utf8_decode($caraprobado), 0, 1, 'L');
//---------------------------------------------------
$pdf->Output();
?>  
