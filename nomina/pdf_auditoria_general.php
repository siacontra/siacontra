<?php
define('FPDF_FONTPATH','font/');
require('mc_table3.php');
require('fphp_nomina.php');
connect();
//---------------------------------------------------

//---------------------------------------------------
//	Imprime la cabedera del documento
function Cabecera($pdf, $ftiponom, $nomina, $proceso, $periodo, $periodo_fecha, $periodoant) {
	$pdf->AddPage();
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 6);
	
	$pdf->Cell(190, 5, ('CONTRALORIA DEL ESTADO MONAGAS'), 0, 1, 'L');
	$pdf->Cell(190, 5, ('DIRECCION DE RECURSOS HUMANOS'), 0, 1, 'L');
	$pdf->Cell(190, 5, ('REPORTE DE AUDITORIA'), 0, 1, 'L');
	$pdf->Cell(190, 5, ('TIPO DE NOMINA '.$nomina), 0, 1, 'L');
	$pdf->Cell(190, 5, ($proceso), 0, 1, 'L');
	$pdf->Ln(1);
	$pdf->SetFont('Arial', '', 6);
	$pdf->Cell(190, 5, ($periodo_fecha), 0, 1, 'C');
	$pdf->Ln(3);
	
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetWidths(array(15, 75, 30, 30, 40));
	$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C'));
	$pdf->Row(array('CEDULA', 'APELLIDOS Y NOMBRES', 'ASIGNACIONES', 'DEDUCCIONES', 'VARIACION'));
	$pdf->SetWidths(array(15, 75, 15, 15, 15, 15, 20, 20));
	$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
	$pdf->Row(array('', '', $periodoant, $periodo, $periodoant, $periodo, 'ASIGNACION', 'DEDUCCION'));
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

//
list($anioant, $mesant)=SPLIT( '[-./]', $fperiodo);
$mesant = (int) $mesant; $mesant--;
if ($mesant == 0) { $anioant--; $mesant = 12; }
if ($mesant < 10) $mesant = "0$mesant";
$periodoant = "$anioant-$mesant";

Cabecera($pdf, $ftiponom, $field_nomina['Nomina'], utf8_decode($field_proceso['Descripcion']), $fperiodo, $periodo_fecha, $periodoant);

//	Cuerpo
$sql = "SELECT
			mp.CodPersona,
			mp.Ndocumento, 
			mp.NomCompleto AS Busqueda,
			ptne.TotalIngresos AS TotalIngresosActual,
			ptne.TotalEgresos AS TotalEgresosActual,
			(SELECT TotalIngresos
				FROM pr_tiponominaempleado
					WHERE 
						CodPersona = mp.CodPersona AND
						CodTipoNom = '".$ftiponom."' AND 
						Periodo = '".$periodoant."' AND 
						CodTipoProceso = '".$ftproceso."') AS TotalIngresosAnterior,
			(SELECT TotalEgresos
				FROM pr_tiponominaempleado
					WHERE 
						CodPersona = mp.CodPersona AND
						CodTipoNom = '".$ftiponom."' AND 
						Periodo = '".$periodoant."' AND 
						CodTipoProceso = '".$ftproceso."') AS TotalEgresosAnterior
		FROM
			mastpersonas mp
			INNER JOIN pr_tiponominaempleado ptne ON (mp.CodPersona = ptne.CodPersona)
		WHERE
			ptne.CodTipoNom = '".$ftiponom."' AND
			ptne.Periodo = '".$fperiodo."' AND
			ptne.CodTipoProceso = '".$ftproceso."'
		ORDER BY length(mp.Ndocumento), mp.Ndocumento";
$query = mysql_query($sql) or die ($sql.mysql_error());
while ($field = mysql_fetch_array($query)) {
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetWidths(array(15, 75, 15, 15, 15, 15, 20, 20));
	$pdf->SetAligns(array('R', 'L', 'R', 'R', 'R', 'R', 'R', 'R'));
	
	$VariacionIngresos = $field['TotalIngresosActual'] - $field['TotalIngresosAnterior'];
	$VariacionEgresos = $field['TotalEgresosActual'] - $field['TotalEgresosAnterior'];
	$pdf->Row(array($field['Ndocumento'], utf8_decode($field['Busqueda']), number_format($field['TotalIngresosAnterior'], 2, ',', '.'), number_format($field['TotalIngresosActual'], 2, ',', '.'), number_format($field['TotalEgresosAnterior'], 2, ',', '.'), number_format($field['TotalEgresosActual'], 2, ',', '.'), number_format($VariacionIngresos, 2, ',', '.'), number_format($VariacionEgresos, 2, ',', '.')));
	if ($pdf->GetY() > 260) Cabecera($pdf, $ftiponom, $field_nomina['Nomina'], utf8_decode($field_proceso['Descripcion']), $fperiodo, $periodo_fecha, $periodoant);
}
//---------------------------------------------------
$pdf->Output();
?>  
