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
	$pdf->Cell(190, 5, ('RESUMEN DE CONCEPTOS POR PROCESO - '.$nomina), 0, 1, 'L');
	$pdf->Cell(190, 5, ($periodo.' '.$proceso), 0, 1, 'L');
	$pdf->Ln(5);
	
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetWidths(array(75, 35, 35, 35));
	$pdf->SetAligns(array('L', 'R', 'R', 'R'));
	$pdf->Row(array('CONCEPTO', 'ADELANTO', 'FIN DE MES', 'NETO'));
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
	$y=$pdf->GetY();
	$pdf->Rect(10, $y, 190, 0.1, "DF");
	$pdf->Ln(2);
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

Cabecera($pdf, $ftiponom, $field_nomina['Nomina'], $field_proceso['Descripcion'], $periodo);

//	Cuerpo
$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);

if ($ftproceso == "FIN") {
	$sql = "(SELECT
				pc.CodConcepto,
				pc.Descripcion,
				SUM(ptnec.Monto) AS MontoFin,
				(SELECT SUM(Monto)
					FROM pr_tiponominaempleadoconcepto
						WHERE
							CodConcepto = pc.CodConcepto AND
							Tipo = 'I' AND
							CodTipoNom = '".$ftiponom."' AND 
							Periodo = '".$fperiodo."' AND 
							CodTipoProceso = 'ADE'
						GROUP BY
							CodTipoNom,
							Periodo,
							CodTipoProceso) AS MontoAde
				
			FROM
				pr_concepto pc
				INNER JOIN pr_tiponominaempleadoconcepto ptnec ON (pc.CodConcepto = ptnec.CodConcepto)
			WHERE
				pc.Tipo = 'I' AND
				ptnec.CodTipoNom = '".$ftiponom."' AND 
				ptnec.Periodo = '".$fperiodo."' AND 
				ptnec.CodTipoProceso = '".$ftproceso."'
			GROUP BY
				ptnec.CodTipoNom,
				ptnec.Periodo,
				ptnec.CodTipoProceso,
				ptnec.CodConcepto)
				
			UNION 
			
			(SELECT
				pc.CodConcepto,
				pc.Descripcion,
				'' AS MontoFin,
				SUM(ptnec.Monto) AS MontoAde
			FROM
				pr_concepto pc
				INNER JOIN pr_tiponominaempleadoconcepto ptnec ON (pc.CodConcepto = ptnec.CodConcepto)
			WHERE
				pc.Tipo = 'I' AND
				ptnec.CodTipoNom = '".$ftiponom."' AND 
				ptnec.Periodo = '".$fperiodo."' AND 
				ptnec.CodTipoProceso = 'ADE' AND
				ptnec.CodConcepto NOT IN (SELECT CodConcepto FROM pr_tiponominaempleadoconcepto WHERE CodTipoNom = '".$ftiponom."' AND Periodo = '".$fperiodo."' AND CodTipoProceso = 'FIN')
			GROUP BY
				ptnec.CodTipoNom,
				ptnec.Periodo,
				ptnec.CodTipoProceso,
				ptnec.CodConcepto)";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	while ($field = mysql_fetch_array($query)) {
		$total_mes = $field['MontoFin'] - $field['MontoAde'];
		$total_asignaciones += $total_mes;
		$total_ade_a += $field['MontoAde'];
		$total_fin_a += $field['MontoFin'];
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetWidths(array(75, 35, 35, 35));
		$pdf->SetAligns(array('L', 'R', 'R', 'R'));
		$pdf->Row(array(utf8_decode($field['Descripcion']), number_format($field['MontoAde'], 2, ',', '.'), number_format($field['MontoFin'], 2, ',', '.'), number_format($total_mes, 2, ',', '.')));
	}
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Row(array(('TOTAL ASIGNACIONES'), number_format($total_ade_a, 2, ',', '.'), number_format($total_fin_a, 2, ',', '.'), number_format($total_asignaciones, 2, ',', '.')));
	$pdf->Ln(5);
	//---------------------------------------------------
	$sql = "(SELECT
				pc.CodConcepto,
				pc.Descripcion,
				SUM(ptnec.Monto) AS MontoFin,
				(SELECT SUM(Monto)
					FROM pr_tiponominaempleadoconcepto
						WHERE
							CodConcepto = pc.CodConcepto AND
							Tipo = 'D' AND
							CodTipoNom = '".$ftiponom."' AND 
							Periodo = '".$fperiodo."' AND 
							CodTipoProceso = 'ADE'
						GROUP BY
							CodTipoNom,
							Periodo,
							CodTipoProceso) AS MontoAde
			FROM
				pr_concepto pc
				INNER JOIN pr_tiponominaempleadoconcepto ptnec ON (pc.CodConcepto = ptnec.CodConcepto)
			WHERE
				pc.Tipo = 'D' AND
				ptnec.CodTipoNom = '".$ftiponom."' AND 
				ptnec.Periodo = '".$fperiodo."' AND 
				ptnec.CodTipoProceso = '".$ftproceso."'
			GROUP BY
				ptnec.CodTipoNom,
				ptnec.Periodo,
				ptnec.CodTipoProceso,
				ptnec.CodConcepto)
				
			UNION 
			
			(SELECT
				pc.CodConcepto,
				pc.Descripcion,
				'' AS MontoFin,
				SUM(ptnec.Monto) AS MontoAde
			FROM
				pr_concepto pc
				INNER JOIN pr_tiponominaempleadoconcepto ptnec ON (pc.CodConcepto = ptnec.CodConcepto)
			WHERE
				pc.Tipo = 'D' AND
				ptnec.CodTipoNom = '".$ftiponom."' AND 
				ptnec.Periodo = '".$fperiodo."' AND 
				ptnec.CodTipoProceso = 'ADE' AND
				ptnec.CodConcepto NOT IN (SELECT CodConcepto FROM pr_tiponominaempleadoconcepto WHERE CodTipoNom = '".$ftiponom."' AND Periodo = '".$fperiodo."' AND CodTipoProceso = 'FIN')
			GROUP BY
				ptnec.CodTipoNom,
				ptnec.Periodo,
				ptnec.CodTipoProceso,
				ptnec.CodConcepto)";
	$query = mysql_query($sql) or die ($sql.mysql_error()); $total_ade=0; $total_fin=0; $total_mes=0;
	while ($field = mysql_fetch_array($query)) {
		if ($field['CodConcepto'] != "0017") $total_mes = $field['MontoFin'];
		$total_deducciones += $total_mes;
		$total_ade_f += $field['MontoAde'];
		$total_fin_f += $field['MontoFin'];
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetWidths(array(75, 35, 35, 35));
		$pdf->SetAligns(array('L', 'R', 'R', 'R'));
		$pdf->Row(array($field['Descripcion'], number_format($field['MontoAde'], 2, ',', '.'), number_format($field['MontoFin'], 2, ',', '.'), number_format($total_mes, 2, ',', '.')));
	}
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Row(array(('TOTAL DEDUCCIONES'), number_format($total_ade_f, 2, ',', '.'), number_format($total_fin_f, 2, ',', '.'), number_format($total_deducciones, 2, ',', '.')));
	$pdf->Ln(5);
	//---------------------------------------------------
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
	$y=$pdf->GetY();
	$pdf->Rect(10, $y, 190, 0.1, "DF");
	$pdf->Ln(5);
	//---------------------------------------------------
	$total_adelanto = $total_ade_a - $total_ade_f;
	$total_fin = $total_fin_a - $total_fin_f;
	$total_neto = $total_asignaciones - $total_deducciones;
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Row(array(('TOTAL NETO'), number_format($total_adelanto, 2, ',', '.'), number_format($total_fin, 2, ',', '.'), number_format($total_neto, 2, ',', '.')));
	//---------------------------------------------------
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
	$y=$pdf->GetY();
	$pdf->Rect(10, $y, 190, 0.1, "DF");
	$pdf->Ln(5);
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	//---------------------------------------------------
	$sql = "(SELECT
				pc.CodConcepto,
				pc.Descripcion,
				SUM(ptnec.Monto) AS MontoFin,
				(SELECT SUM(Monto)
					FROM pr_tiponominaempleadoconcepto
						WHERE
							CodConcepto = pc.CodConcepto AND
							Tipo = 'A' AND
							CodTipoNom = '".$ftiponom."' AND 
							Periodo = '".$fperiodo."' AND 
							CodTipoProceso = 'ADE'
						GROUP BY
							CodTipoNom,
							Periodo,
							CodTipoProceso) AS MontoAde
			FROM
				pr_concepto pc
				INNER JOIN pr_tiponominaempleadoconcepto ptnec ON (pc.CodConcepto = ptnec.CodConcepto)
			WHERE
				pc.Tipo = 'A' AND
				ptnec.CodTipoNom = '".$ftiponom."' AND 
				ptnec.Periodo = '".$fperiodo."' AND 
				ptnec.CodTipoProceso = '".$ftproceso."'
			GROUP BY
				ptnec.CodTipoNom,
				ptnec.Periodo,
				ptnec.CodTipoProceso,
				ptnec.CodConcepto)
				
			UNION 
			
			(SELECT
				pc.CodConcepto,
				pc.Descripcion,
				'' AS MontoFin,
				SUM(ptnec.Monto) AS MontoAde
			FROM
				pr_concepto pc
				INNER JOIN pr_tiponominaempleadoconcepto ptnec ON (pc.CodConcepto = ptnec.CodConcepto)
			WHERE
				pc.Tipo = 'A' AND
				ptnec.CodTipoNom = '".$ftiponom."' AND 
				ptnec.Periodo = '".$fperiodo."' AND 
				ptnec.CodTipoProceso = 'ADE' AND
				ptnec.CodConcepto NOT IN (SELECT CodConcepto FROM pr_tiponominaempleadoconcepto WHERE CodTipoNom = '".$ftiponom."' AND Periodo = '".$fperiodo."' AND CodTipoProceso = 'FIN')
			GROUP BY
				ptnec.CodTipoNom,
				ptnec.Periodo,
				ptnec.CodTipoProceso,
				ptnec.CodConcepto)";
	$query = mysql_query($sql) or die ($sql.mysql_error()); $total_ade=0; $total_fin=0; $total_mes=0;
	while ($field = mysql_fetch_array($query)) {
		$total_mes = $field['MontoFin'] - $field['MontoAde'];
		$total_patronales += $total_mes;
		$total_ade_p += $field['MontoAde'];
		$total_fin_p += $field['MontoFin'];
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetWidths(array(75, 35, 35, 35));
		$pdf->SetAligns(array('L', 'R', 'R', 'R'));
		$pdf->Row(array(utf8_decode($field['Descripcion']), number_format($field['MontoAde'], 2, ',', '.'), number_format($field['MontoFin'], 2, ',', '.'), number_format($total_mes, 2, ',', '.')));
	}
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Row(array(('TOTAL APORTES'), number_format($total_ade_p, 2, ',', '.'), number_format($total_fin_p, 2, ',', '.'), number_format($total_patronales, 2, ',', '.')));
	$pdf->Ln(5);
	//---------------------------------------------------
	
} else {
	$sql = "SELECT
				pc.CodConcepto,
				pc.Descripcion,
				SUM(ptnec.Monto) AS Monto
			FROM
				pr_concepto pc
				INNER JOIN pr_tiponominaempleadoconcepto ptnec ON (pc.CodConcepto = ptnec.CodConcepto)
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
	while ($field = mysql_fetch_array($query)) {
		$total_asignaciones += $field['Monto'];
		$pdf->SetFont('Arial', '', 8);
		$pdf->Row(array(utf8_decode($field['Descripcion']), number_format($field['Monto'], 2, ',', '.'), '', ''));
	}
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Row(array(('TOTAL ASIGNACIONES'), number_format($total_asignaciones, 2, ',', '.'), '', ''));
	$pdf->Ln(5);
	//---------------------------------------------------
	$sql = "SELECT
				pc.CodConcepto,
				pc.Descripcion,
				SUM(ptnec.Monto) AS Monto
			FROM
				pr_concepto pc
				INNER JOIN pr_tiponominaempleadoconcepto ptnec ON (pc.CodConcepto = ptnec.CodConcepto)
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
	while ($field = mysql_fetch_array($query)) {
		$total_deducciones += $field['Monto'];
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 8);
		$pdf->Row(array(utf8_decode($field['Descripcion']), '', number_format($field['Monto'], 2, ',', '.'), ''));
	}
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Row(array(('TOTAL DEDUCCIONES'), '', number_format($total_deducciones, 2, ',', '.'), ''));
	$pdf->Ln(5);
	//---------------------------------------------------
	$total_neto = $total_asignaciones - $total_deducciones;
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Row(array(('TOTAL NETO'), '', '', number_format($total_neto, 2, ',', '.')));
	//---------------------------------------------------
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
	$y=$pdf->GetY();
	$pdf->Rect(10, $y, 190, 0.1, "DF");
}
//---------------------------------------------------
list($nomelaborado, $carelaborado) = getFirmaNomina($ftiponom, $fperiodo, $ftproceso, "ProcesadoPor");
list($nomaprobado, $caraprobado) = getFirmaNomina($ftiponom, $fperiodo, $ftproceso, "AprobadoPor");
//---------------------------------------------------
$pdf->Rect(10, 250, 70, 0.1, "DF");
$pdf->Rect(120, 250, 70, 0.1, "DF");
$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(10, 252);
$pdf->Cell(110, 4, ('ELABORADO POR:'), 0, 0, 'L');
$pdf->Cell(80, 4, ('CONFORMADO POR:'), 0, 1, 'L');

$pdf->Cell(110, 4, utf8_decode($nomelaborado), 0, 0, 'L');
$pdf->Cell(80, 4, utf8_decode($nomaprobado), 0, 1, 'L');

$pdf->Cell(110, 4, utf8_decode($carelaborado), 0, 0, 'L');
$pdf->Cell(80, 4, utf8_decode($caraprobado), 0, 1, 'L');
//---------------------------------------------------
$pdf->Output();
?>  
