<?php
define('FPDF_FONTPATH','font/');
require('mc_table3.php');
require('fphp_nomina.php');
connect();
//---------------------------------------------------

$total='{nb}' ;
$total;
//---------------------------------------------------
//	Imprime la cabedera del documento
function Cabecera($pdf, $ftiponom, $nomina, $proceso, $periodo, $periodo_fecha, $nom_concepto) {
	global $flagsueldo;
	
	$pdf->AddPage();
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	
	$pdf->Cell(190, 5, ('CONTRALORIA DEL ESTADO MONAGAS'), 0, 1, 'L');
	$pdf->Cell(190, 5, ('DIRECCION DE RECURSOS HUMANOS'), 0, 1, 'L');
	$pdf->Cell(190, 5, ('REPORTE DE '.$nom_concepto), 0, 1, 'L');
	$pdf->Cell(190, 5, ('TIPO DE NOMINA '.$nomina), 0, 1, 'L');
	
	$pdf->Cell(190, 5, ($proceso), 0, 1, 'L');
	$pdf->Cell(190, 5, ('Pag '.$pdf->PageNo()."/".'{nb}'), 0, 1, 'L');
	$pdf->Ln(1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(190, 5, ($periodo_fecha), 0, 1, 'C');
	$pdf->Ln(3);
	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	if ($flagsueldo == "S") {
		$pdf->SetWidths(array(20, 110, 30, 30));
		$pdf->SetAligns(array('C', 'C', 'C', 'C'));
		$pdf->Row(array('CEDULA', 'APELLIDOS Y NOMBRES', 'SALARIO', 'MONTO'));
	} else {
		$pdf->SetWidths(array(20, 140, 30));
		$pdf->SetAligns(array('C', 'C', 'C'));
		$pdf->Row(array('CEDULA', 'APELLIDOS Y NOMBRES', 'MONTO'));
	}
}

//	Imprime la cabedera del documento
function Cabecera2($pdf, $ftiponom, $nomina, $proceso, $periodo, $periodo_fecha, $nom_concepto) {
	global $flagsueldo;
	
	$pdf->AddPage();
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	
	$pdf->Cell(190, 5, ('CONTRALORIA DEL ESTADO MONAGAS'), 0, 1, 'L');
	$pdf->Cell(190, 5, ('DIRECCION DE RECURSOS HUMANOS'), 0, 1, 'L');
	$pdf->Cell(190, 5, ('REPORTE DE '.$nom_concepto), 0, 1, 'L');
	$pdf->Cell(190, 5, ('TIPO DE NOMINA '.$nomina), 0, 1, 'L');
	$pdf->Cell(190, 5, ($proceso), 0, 1, 'L');
	$pdf->Cell(190, 5, ('Pag: '.$pdf->PageNo()."/".'{nb}'), 0, 1, 'L');
	$pdf->Ln(1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(190, 5, ($periodo_fecha), 0, 1, 'C');
	$pdf->Ln(3);
	
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf = new PDF_MC_Table();
$pdf->Open();
$pdf->AliasNbPages();

//echo "".$pdf->PageNo()." ".'{nb}';

$pdf->SetMargins(10, 15, 10);


//	Tipo de Nomina
$sql = "SELECT Nomina FROM tiponomina WHERE CodTipoNom = '".$ftiponom."'";
$query_nomina = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_nomina) != 0) $field_nomina = mysql_fetch_array($query_nomina);

//	Tipo de Proceso
$sql = "SELECT Descripcion FROM pr_tipoproceso WHERE CodTipoProceso = '".$ftproceso."'";
$query_proceso = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_proceso) != 0) $field_proceso = mysql_fetch_array($query_proceso);

//	Concepto
$sql = "SELECT Descripcion FROM pr_concepto WHERE CodConcepto = '".$codconcepto."'";
$query_concepto = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_concepto) != 0) $field_concepto = mysql_fetch_array($query_concepto);

//	Periodo
list($fecha_desde, $fecha_hasta) = getPeriodoProceso($ftproceso, $fperiodo, $ftiponom);
$periodo_fecha = "DESDE: ".formatFechaDMA($fecha_desde)." HASTA: ".formatFechaDMA($fecha_hasta);


Cabecera($pdf, $ftiponom, $field_nomina['Nomina'], $field_proceso['Descripcion'], $periodo, $periodo_fecha, $field_concepto['Descripcion']);

//	Cuerpo
$sql = "SELECT 
			mp.CodPersona,
			mp.Ndocumento,
			mp.NomCompleto AS Busqueda,
			ptne.TotalIngresos,
			(SELECT SUM(TotalIngresos) 
				FROM pr_tiponominaempleado 
					WHERE CodPersona = mp.CodPersona AND CodTipoNom = '".$ftiponom."' AND Periodo = '".$fperiodo."') AS TotalIngresosMes,
			ptnec.Monto,
			ptnec.Cantidad
		FROM
			mastpersonas mp
			INNER JOIN pr_tiponominaempleado ptne ON (mp.CodPersona = ptne.CodPersona)
			INNER JOIN pr_tiponominaempleadoconcepto ptnec ON (ptne.CodPersona = ptnec.CodPersona AND ptne.CodTipoNom = ptnec.CodTipoNom AND ptne.Periodo = ptnec.Periodo AND ptne.CodTipoproceso = ptnec.CodTipoProceso AND ptnec.CodConcepto = '".$codconcepto."')
		WHERE
			ptne.CodTipoNom = '".$ftiponom."' AND
			ptne.Periodo = '".$fperiodo."' AND
			ptne.CodTipoProceso = '".$ftproceso."'
		ORDER BY length(mp.Ndocumento), mp.Ndocumento";
		
$contador=0;

$query = mysql_query($sql) or die ($sql.mysql_error());
while ($field = mysql_fetch_array($query)) {
	$contador=$contador +1;
	$sum_ingresos += $field['TotalIngresos'];
	$sum_retenciones += $field['Monto'];
	$sum_aportes += $field['Aporte'];
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	if ($flagsueldo == "S") {
		$pdf->SetWidths(array(20, 110, 30, 30));
		$pdf->SetAligns(array('R', 'L', 'R', 'R'));
		$pdf->Row(array(number_format($field['Ndocumento'], 0, '', '.'), utf8_decode($field['Busqueda']), number_format($field['TotalIngresos'], 2, ',', '.'), number_format($field['Monto'], 2, ',', '.')));
	} else {
		$pdf->SetWidths(array(20, 140, 30));
		$pdf->SetAligns(array('R', 'L', 'R'));
		$pdf->Row(array(number_format($field['Ndocumento'], 0, '', '.'), utf8_decode($field['Busqueda']), number_format($field['Monto'], 2, ',', '.')));
	}
	if ($pdf->GetY() > 210) Cabecera($pdf, $ftiponom, $field_nomina['Nomina'], $field_proceso['Descripcion'], $periodo, $periodo_fecha, $field_concepto['Descripcion']);
}
//---------------------------------------------------
$pdf->SetFont('Arial', 'B', 8);
if ($flagsueldo == "S") {
	$pdf->Row(array('', 'TOTAL', number_format($sum_ingresos, 2, ',', '.'), number_format($sum_retenciones, 2, ',', '.')));
} else {
	$pdf->Row(array('', 'TOTAL', number_format($sum_retenciones, 2, ',', '.')));
}
//---------------------------------------------------

if ($pdf->GetY() > 210)
{
	 Cabecera($pdf, $ftiponom, $field_nomina['Nomina'], $field_proceso['Descripcion'], $periodo, $periodo_fecha, $field_concepto['Descripcion']);
}
$y = $pdf->GetY() + 5;

//---------------------------------------------------

//---------------------------------------------------
/*
list($nomelaborado, $carelaborado) = getFirmaNomina($ftiponom, $fperiodo, $ftproceso, "ProcesadoPor");
list($nomaprobado, $caraprobado) = getFirmaNomina($ftiponom, $fperiodo, $ftproceso, "AprobadoPor");*/

//---------------------------------------------------

if ($pdf->GetY() > 210)
{
	 Cabecera2($pdf, $ftiponom, $field_nomina['Nomina'], $field_proceso['Descripcion'], $periodo, $periodo_fecha, $field_concepto['Descripcion']);
}
$y = $pdf->GetY() + 5;


$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
$pdf->SetWidths(array(10,100, 100));
$pdf->SetAligns(array('','L', 'L'));
$pdf->Row(array('','ELABORADO POR:','REVISADO POR:'));$pdf->Ln(4);
$pdf->Row(array('','__________________','__________________'));
$pdf->Row(array('','Tatiana Jimenez','Nhysette Reyes'));
$pdf->Row(array('','ANALISTA DE RECURSOS HUMANOS I','JEFE (A) DE DIVISIÓN DE ADMINISTRACIÓN DE PERSONAL'));

$pdf->Ln(5);
$pdf->Row(array('','CONFORMADO POR: ','AUTORIZADO POR:'));$pdf->Ln(4);
$pdf->Row(array('','__________________','__________________'));
$pdf->Row(array('','Karla Azocar','Cesar Granado'));
$pdf->Row(array('','DIRECTORA DE RECURSOS HUMANOS','DIRECTOR GENERAL '));
//$pdf->Row(array('','Karla Azocar','Freddy Cudjoe'));
//$pdf->Row(array('','DIRECTORA DE RECURSOS HUMANOS','CONTRALOR DEL ESTADO MONAGAS (P)'));
/*$pdf->Rect(10, $y+5, 70, 0.1, "DF");
$pdf->Rect(120, $y+5, 70, 0.1, "DF");
$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(10, $y+10);
$pdf->Cell(110, 4, ('ELABORADO POR:'), 0, 0, 'L');
$pdf->Cell(80, 4, ('CONFORMADO POR:'), 0, 1, 'L');

$pdf->Cell(110, 4, utf8_decode($nomelaborado), 0, 0, 'L');
$pdf->Cell(80, 4, utf8_decode($nomaprobado), 0, 1, 'L');

$pdf->Cell(110, 4, utf8_decode($carelaborado), 0, 0, 'L');
$pdf->Cell(80, 4, utf8_decode($caraprobado), 0, 1, 'L');

*/
//$pdf->Cell(110, 4, 'TOTAL:'.$contador, 0, 0, 'L');
//---------------------------------------------------
$pdf->Output();
?>  
