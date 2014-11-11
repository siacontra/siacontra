<?php
define('FPDF_FONTPATH','font/');
require('mc_table3.php');
require('fphp_nomina.php');
connect();
//---------------------------------------------------

//---------------------------------------------------
//	Imprime la cabedera del documento
function Cabecera($pdf, $ftiponom, $nomina, $proceso, $periodo, $periodo_fecha) {
	$pdf->AddPage();
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	
	$pdf->Cell(190, 5, ('CONTRALORIA DEL ESTADO MONAGAS'), 0, 1, 'L');
	$pdf->Cell(190, 5, ('DIRECCION DE RECURSOS HUMANOS'), 0, 1, 'L');
	$pdf->Cell(190, 5, ('REPORTE DE RETENCIONES'), 0, 1, 'L');
	$pdf->Cell(190, 5, ('CORRESPONDIENTE A LA LEY DE IMPUESTO SOBRE LA RENTA'), 0, 1, 'L');
	$pdf->Cell(190, 5, ('TIPO DE NOMINA '.$nomina), 0, 1, 'L');
	$pdf->Cell(190, 5, ($proceso), 0, 1, 'L');
	$pdf->Ln(1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(190, 5, ($periodo_fecha), 0, 1, 'C');
	$pdf->Ln(3);
	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetWidths(array(20, 20, 85, 25, 15, 25));
	$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C'));
	$pdf->Row(array('R.I.F.', 'CEDULA', 'APELLIDOS Y NOMBRES', 'SALARIO', '%', 'RETENCIONES'));
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
list($fecha_desde, $fecha_hasta) = getPeriodoProceso($ftproceso, $fperiodo, $ftiponom);
$periodo_fecha = "DESDE: ".formatFechaDMA($fecha_desde)." HASTA: ".formatFechaDMA($fecha_hasta);


Cabecera($pdf, $ftiponom, $field_nomina['Nomina'], $field_proceso['Descripcion'], $periodo, $periodo_fecha);

//	Cuerpo
$sql = "SELECT 
			mp.DocFiscal, 
			mp.Ndocumento, 
			mp.NomCompleto AS Busqueda,
			ptne.TotalNeto,
			ptne.TotalIngresos,
			(SELECT SUM(TotalIngresos) 
				FROM pr_tiponominaempleado 
					WHERE CodPersona = mp.CodPersona AND CodTipoNom = '".$ftiponom."' AND Periodo = '".$fperiodo."') AS TotalIngresosMes,
			ptnec.Cantidad,
			ptnec.Monto
		FROM
			mastpersonas mp
			INNER JOIN pr_tiponominaempleado ptne ON (mp.CodPersona = ptne.CodPersona)
			INNER JOIN pr_tiponominaempleadoconcepto ptnec ON (ptne.CodPersona = ptnec.CodPersona AND ptne.CodTipoNom = ptnec.CodTipoNom AND ptne.Periodo = ptnec.Periodo AND ptne.CodTipoproceso = ptnec.CodTipoProceso AND ptnec.CodConcepto = '0051')
		WHERE
			ptne.CodTipoNom = '".$ftiponom."' AND
			ptne.Periodo = '".$fperiodo."' AND
			ptne.CodTipoProceso = '".$ftproceso."'
		ORDER BY length(mp.Ndocumento), mp.Ndocumento";
$query = mysql_query($sql) or die ($sql.mysql_error());
while ($field = mysql_fetch_array($query)) {
	$sum_total += $field['TotalIngresos'];
	$sum_retenciones += $field['Monto'];
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetWidths(array(20, 20, 85, 25, 15, 25));
	$pdf->SetAligns(array('C', 'R', 'L', 'R', 'R', 'R'));
	$pdf->Row(array($field['DocFiscal'], $field['Ndocumento'], $field['Busqueda'], number_format($field['TotalIngresos'], 2, ',', '.'), number_format($field['Cantidad'], 2, ',', '.'), number_format($field['Monto'], 2, ',', '.')));
	if ($pdf->GetY() > 260) Cabecera($pdf, $ftiponom, $field_nomina['Nomina'], $field_proceso['Descripcion'], $periodo, $periodo_fecha);
}
//---------------------------------------------------
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetWidths(array(125, 25, 15, 25));
$pdf->Row(array('TOTAL', number_format($sum_total, 2, ',', '.'), '', number_format($sum_retenciones, 2, ',', '.'), ''));
//---------------------------------------------------
list($nomelaborado, $carelaborado) = getFirmaNomina($ftiponom, $fperiodo, $ftproceso, "ProcesadoPor");
list($nomaprobado, $caraprobado) = getFirmaNomina($ftiponom, $fperiodo, $ftproceso, "AprobadoPor");
//---------------------------------------------------
$pdf->Ln(25);
$y = $pdf->GetY();
$pdf->Rect(10, $y, 70, 0.1, "DF");
$pdf->Rect(120, $y, 70, 0.1, "DF");
$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(10, $y);
$pdf->Cell(110, 4, ('ELABORADO POR:'), 0, 0, 'L');
$pdf->Cell(80, 4, ('CONFORMADO POR:'), 0, 1, 'L');
$pdf->Cell(110, 4, utf8_decode($nomelaborado), 0, 0, 'L');
$pdf->Cell(80, 4, utf8_decode($nomaprobado), 0, 1, 'L');
$pdf->Cell(110, 4, utf8_decode($carelaborado), 0, 0, 'L');
$pdf->Cell(80, 4, utf8_decode($caraprobado), 0, 1, 'L');
//---------------------------------------------------
$pdf->Output();
?>  
