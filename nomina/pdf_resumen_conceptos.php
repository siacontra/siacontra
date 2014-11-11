<?php
define('FPDF_FONTPATH','font/');
require('mc_table3.php');
require('fphp_nomina.php');
connect();
//---------------------------------------------------

//---------------------------------------------------
//	Imprime la cabedera del documento
function Cabecera($pdf, $ftiponom, $nomina, $proceso, $periodo) {
	$pdf->AddPage();
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	
	$pdf->Cell(190, 5, ('CONTRALORIA DEL ESTADO MONAGAS'), 0, 1, 'L');
	$pdf->Cell(190, 5, ('DIRECCION DE RECURSOS HUMANOS'), 0, 1, 'L');
	$pdf->Cell(190, 5, ('RESUMEN DE CONCEPTOS - '.$nomina), 0, 1, 'L');
	$pdf->Cell(190, 5, ($periodo.' '.$proceso), 0, 1, 'L');
	$pdf->Cell(190, 5, 'Pagina: '.$pdf->PageNo().'/{nb}', 0, 1, 'R');
	$pdf->Ln(5);
	
	$pdf->SetWidths(array(70, 40, 40, 40));
	$pdf->SetAligns(array('L', 'R', 'R', 'R'));
	$pdf->Row(array('NOMBRE DEL CONCEPTO', 'ASIGNACIONES', 'DEDUCCIONES', ''));
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
	$y=$pdf->GetY();
	$pdf->Rect(10, $y, 190, 0.1, "DF");
	$pdf->Ln(2);
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf = new PDF_MC_Table('P', 'mm', 'Letter');
$pdf->Open();
$pdf->SetMargins(10, 15, 10);
$pdf->AliasNbPages();
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

Cabecera($pdf, $ftiponom, $field_nomina['Nomina'], utf8_decode($field_proceso['Descripcion']), $periodo);

//	Cuerpo
$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
$sql = "SELECT
			pc.CodConcepto,
			pc.Descripcion,
			SUM(ptnec.Monto) AS Monto
		FROM
			pr_concepto pc
			INNER JOIN pr_tiponominaempleadoconcepto ptnec ON (pc.CodConcepto = ptnec.CodConcepto)
			JOIN mastpersonas mt ON mt.CodPersona = ptnec.CodPersona
		WHERE
			pc.Tipo = 'I' AND
			ptnec.CodTipoNom = '".$ftiponom."' AND 
			ptnec.Periodo = '".$fperiodo."' AND 
			ptnec.CodTipoProceso = '".$ftproceso."'
		GROUP BY
			ptnec.CodTipoNom,
			ptnec.Periodo,
			ptnec.CodTipoProceso,
			ptnec.CodConcepto";
			
$query = mysql_query($sql) or die ($sql.mysql_error());

$total_asignaciones = 0;

while ($field = mysql_fetch_array($query)) {
	$total_asignaciones += $field['Monto'];
	$pdf->SetFont('Arial', '', 8);
	$pdf->Row(array(utf8_decode($field['Descripcion']), number_format($field['Monto'], 2, ',', '.'), '', ''));
	$pdf->Ln(2);
}
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array(('TOTAL ASIGNACIONES'), number_format($total_asignaciones, 2, ',', '.'), '', ''));
$pdf->Ln(5);
//---------------------------------------------------
$sql = "SELECT pc.CodConcepto, pc.Descripcion, sum( ptnec.Monto ) AS Monto
FROM pr_concepto pc
INNER JOIN pr_tiponominaempleadoconcepto ptnec ON ( pc.CodConcepto = ptnec.CodConcepto )
JOIN mastpersonas mt ON mt.CodPersona = ptnec.CodPersona
		WHERE
			pc.Tipo = 'D' AND
			ptnec.CodTipoNom = '".$ftiponom."' AND 
			ptnec.Periodo = '".$fperiodo."' AND 
			ptnec.CodTipoProceso = '".$ftproceso."'
		GROUP BY
			ptnec.CodTipoNom,
			ptnec.Periodo,
			ptnec.CodTipoProceso,
			ptnec.CodConcepto";

$query = mysql_query($sql) or die ($sql.mysql_error());

$total_deducciones = 0;
$row=mysql_num_rows($query); $i=0;
while ($field = mysql_fetch_array($query)) {
	$total_deducciones += $field['Monto'];
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Row(array(utf8_decode($field['Descripcion']), '', number_format($field['Monto'], 2, ',', '.'), ''));
	$pdf->Ln(2);
	
	if($i==$row-2 and $pdf->GetY() > 205) 
	{

    Cabecera($pdf, $ftiponom, $field_nomina['Nomina'], $field_proceso['Descripcion'], $periodo);
    }
	else if ($pdf->GetY() > 250) 
	{

    Cabecera($pdf, $ftiponom, $field_nomina['Nomina'], $field_proceso['Descripcion'], $periodo);
    }
    $i=$i+1;
}
$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array(('TOTAL DEDUCCIONES'), '', number_format($total_deducciones, 2, ',', '.'), ''));
$pdf->Ln(5);
//---------------------------------------------------
$total_neto = $total_asignaciones - $total_deducciones;
$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array(('TOTAL NETO'), '', '', number_format($total_neto, 2, ',', '.')));
//---------------------------------------------------
$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); 
$y=$pdf->GetY();
$pdf->Rect(10, $y, 190, 0.1, "DF");
//---------------------------------------------------
list($nomelaborado, $carelaborado) = getFirmaNomina($ftiponom, $fperiodo, $ftproceso, "ProcesadoPor");
list($nomaprobado, $caraprobado) = getFirmaNomina($ftiponom, $fperiodo, $ftproceso, "AprobadoPor");
//---------------------------------------------------
//$pdf->Rect(10, 223, 70, 0.1, "DF");
//$pdf->Rect(120, 223, 70, 0.1, "DF");
/*$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(10, 265);
$pdf->Cell(110, 4, ('ELABORADO POR:'), 0, 0, 'L');
$pdf->Cell(80, 4, ('CONFORMADO POR:'), 0, 1, 'L');

$pdf->Cell(110, 4, utf8_decode($nomelaborado), 0, 0, 'L');
$pdf->Cell(80, 4, utf8_decode($nomaprobado), 0, 1, 'L');
$pdf->SetXY(10, 272);
$pdf->Cell(110, 4, utf8_decode($carelaborado), 0, 0, 'L');
$pdf->Cell(80, 4, utf8_decode($caraprobado), 0, 1, 'L');*/
//---------------------------------------------------...

//$pdf->SetXY(10, 150);
//	$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 7.5);
$pdf->Ln(4);
$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
$pdf->SetWidths(array(10,100, 100));
$pdf->SetAligns(array('','L', 'L'));
$pdf->Row(array('','ELABORADO POR:','REVISADO POR:'));$pdf->Ln(4);
$pdf->Row(array('','__________________','__________________'));
$pdf->Row(array('','Tatiana Jimenez','Nhysette Reyes'));
$pdf->Row(array('','ANALISTA DE RECURSOS HUMANOS I','JEFE DE DIVISIÓN ADMINISTRACIÓN DE PERSONAL'));

$pdf->Ln(5);
$pdf->Row(array('','CONFORMADO POR: ','AUTORIZADO POR:'));$pdf->Ln(4);
$pdf->Row(array('','__________________','__________________'));
$pdf->Row(array('','Karla Azocar','Cesar Granado'));
$pdf->Row(array('','DIRECTORA DE RECURSOS HUMANOS','DIRECTOR GENERAL '));
//$pdf->Row(array('','Karla Azocar','Freddy Cudjoe'));
//$pdf->Row(array('','DIRECTORA DE RECURSOS HUMANOS','CONTRALOR DEL ESTADO MONAGAS (P)'));

$pdf->Output();
?>  
