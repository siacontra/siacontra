<?php
define('FPDF_FONTPATH','font/');
require('mc_table3.php');
require('fphp_nomina.php');
connect();
//---------------------------------------------------

//---------------------------------------------------
//	Imprime la cabedera del documento
function Cabecera($pdf, $ftiponom, $nomina, $proceso, $periodo, $periodo_fecha, $periodoant, $codempleado, $nomempleado) {
	$codempleado = number_format($codempleado, 0, '', '.');

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
		
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(190, 10, ($codempleado.'  '.utf8_decode($nomempleado)), 0, 1, 'L');
	
	$pdf->SetWidths(array(115, 25, 25, 25));
	$pdf->SetAligns(array('C', 'C', 'C', 'C'));
	$pdf->Row(array('CONCEPTO', $periodoant, $periodo, 'VARIACION'));
	$pdf->Ln(2);
}
function Cabecera2($pdf, $ftiponom, $nomina, $proceso, $periodo, $periodo_fecha, $periodoant, $codempleado, $nomempleado) {
	$codempleado = number_format($codempleado, 0, '', '.');
	$pdf->Ln(3);
		
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(190, 10, $codempleado.'  '.utf8_decode($nomempleado), 0, 1, 'L');
	
	$pdf->SetWidths(array(115, 25, 25, 25));
	$pdf->SetAligns(array('C', 'C', 'C', 'C'));
	$pdf->Row(array('CONCEPTO', $periodoant, $periodo, 'VARIACION'));
	$pdf->Ln(2);
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf = new PDF_MC_Table();
$pdf->Open();
$pdf->SetMargins(7, 10, 7);

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

if ($codempleado) $filtro_empleado = "AND (me.CodEmpleado = '".$codempleado."')";

//	Obtengo todos los empleados
$sql = "(SELECT
			tnec.CodPersona,
			me.CodEmpleado,
			mp.NomCompleto,
			mp.Ndocumento,
			tnec.CodConcepto,
			c.Descripcion AS NomConcepto,
			'ASIGNACION' AS TipoConcepto,
			(SELECT Monto 
				FROM pr_tiponominaempleadoconcepto
					WHERE
						CodPersona = tnec.CodPersona AND
						CodConcepto = tnec.CodConcepto AND
						CodTipoNom = '".$ftiponom."' AND
						Periodo = '".$periodoant."' AND
						CodOrganismo = '".$forganismo."' AND
						CodTipoProceso = '".$ftproceso."') AS MontoAnterior,
			(SELECT Monto 
				FROM pr_tiponominaempleadoconcepto
					WHERE
						CodPersona = tnec.CodPersona AND
						CodConcepto = tnec.CodConcepto AND
						CodTipoNom = '".$ftiponom."' AND
						Periodo = '".$fperiodo."' AND
						CodOrganismo = '".$forganismo."' AND
						CodTipoProceso = '".$ftproceso."') AS MontoActual
		FROM
			pr_tiponominaempleadoconcepto tnec
			INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto AND c.Tipo = 'I')
			INNER JOIN mastempleado me ON (tnec.CodPersona = me.CodPersona)
			INNER JOIN mastpersonas mp ON (tnec.CodPersona = mp.CodPersona)
		WHERE
			tnec.CodTipoNom = '".$ftiponom."' AND
			(tnec.Periodo = '".$fperiodo."' OR tnec.Periodo = '".$periodoant."') AND
			tnec.CodOrganismo = '".$forganismo."' AND
			tnec.CodTipoProceso = '".$ftproceso."' $filtro_empleado
		GROUP BY CodPersona, CodConcepto)
		
		UNION
		
		(SELECT
			tnec.CodPersona,
			me.CodEmpleado,
			mp.NomCompleto,
			mp.Ndocumento,
			tnec.CodConcepto,
			c.Descripcion AS NomConcepto,
			'DEDUCCION' AS TipoConcepto,
			(SELECT Monto 
				FROM pr_tiponominaempleadoconcepto
					WHERE
						CodPersona = tnec.CodPersona AND
						CodConcepto = tnec.CodConcepto AND
						CodTipoNom = '".$ftiponom."' AND
						Periodo = '".$periodoant."' AND
						CodOrganismo = '".$forganismo."' AND
						CodTipoProceso = '".$ftproceso."') AS MontoAnterior,
			(SELECT Monto 
				FROM pr_tiponominaempleadoconcepto
					WHERE
						CodPersona = tnec.CodPersona AND
						CodConcepto = tnec.CodConcepto AND
						CodTipoNom = '".$ftiponom."' AND
						Periodo = '".$fperiodo."' AND
						CodOrganismo = '".$forganismo."' AND
						CodTipoProceso = '".$ftproceso."') AS MontoActual
		FROM
			pr_tiponominaempleadoconcepto tnec
			INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto AND c.Tipo = 'D')
			INNER JOIN mastempleado me ON (tnec.CodPersona = me.CodPersona)
			INNER JOIN mastpersonas mp ON (tnec.CodPersona = mp.CodPersona)
		WHERE
			tnec.CodTipoNom = '".$ftiponom."' AND
			(tnec.Periodo = '".$fperiodo."' OR tnec.Periodo = '".$periodoant."') AND
			tnec.CodOrganismo = '".$forganismo."' AND
			tnec.CodTipoProceso = '".$ftproceso."' $filtro_empleado
		GROUP BY CodPersona, CodConcepto)
		
		ORDER BY length(Ndocumento), Ndocumento, TipoConcepto, CodConcepto";
$query = mysql_query($sql) or die ($sql.mysql_error());
$rows = mysql_num_rows($query);
for ($i=0; $i<$rows; $i++) {
	$field = mysql_fetch_array($query);
		
	if ($i == 0) $sw = $field['TipoConcepto'];
	
	if ($sw != $field['TipoConcepto'] || $grupo != $field['CodEmpleado']) {
		
		if ($sw == "ASIGNACION") {
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 6);
			$pdf->Row(array('TOTAL ASIGNACIONES', number_format($sum_anterior, 2, ',', '.'), number_format($sum_actual, 2, ',', '.'), number_format(($sum_actual - $sum_anterior), 2, ',', '.')));
			$asignacion_anterior = $sum_anterior;
			$asignacion_actual = $sum_actual;
		}
		
		elseif ($sw == "DEDUCCION") {
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 6);
			$pdf->Row(array('TOTAL DEDUCCIONES', number_format($sum_anterior, 2, ',', '.'), number_format($sum_actual, 2, ',', '.'), number_format(($sum_actual - $sum_anterior), 2, ',', '.')));
			
			$neto_anterior = $asignacion_anterior - $sum_anterior;
			$neto_actual = $asignacion_actual - $sum_actual;
			$pdf->Row(array('TOTAL NETO', number_format($neto_anterior, 2, ',', '.'), number_format($neto_actual, 2, ',', '.'), number_format(($neto_actual - $neto_anterior), 2, ',', '.')));
		}
		
		$sum_anterior = 0; $sum_actual = 0;
		
		$sw = $field['TipoConcepto'];
	}
	
	
	$linea = $pdf->GetY();
	if ($i == 0) {
		$emp = 1;
		Cabecera($pdf, $ftiponom, $field_nomina['Nomina'], utf8_decode($field_proceso['Descripcion']), $fperiodo, $periodo_fecha, $periodoant, $field['Ndocumento'], $field['NomCompleto']);
		$grupo = $field['CodEmpleado'];
	}
	elseif ($grupo != $field['CodEmpleado']) {
		$emp++;
		$grupo = $field['CodEmpleado'];
		
		if ($emp == 3) {
			$emp = 1;
			Cabecera($pdf, $ftiponom, $field_nomina['Nomina'], utf8_decode($field_proceso['Descripcion']), $fperiodo, $periodo_fecha, $periodoant, $field['Ndocumento'], utf8_decode($field['NomCompleto']));
		} else Cabecera2($pdf, $ftiponom, $field_nomina['Nomina'], utf8_decode($field_proceso['Descripcion']), $fperiodo, $periodo_fecha, $periodoant, $field['Ndocumento'], utf8_decode($field['NomCompleto']));
	}
	elseif ($linea > 275) {
		Cabecera($pdf, $ftiponom, $field_nomina['Nomina'], utf8_decode($field_proceso['Descripcion']), $fperiodo, $periodo_fecha, $periodoant, $field['Ndocumento'], utf8_decode($field['NomCompleto']));
	}
	
	
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(115, 25, 25, 25));
	$pdf->SetAligns(array('L', 'R', 'R', 'R'));
	
	$diferencia = $field['MontoActual'] - $field['MontoAnterior'];
	if ($diferencia != 0) {
		$pdf->Row(array(utf8_decode($field['NomConcepto']), number_format($field['MontoAnterior'], 2, ',', '.'), number_format($field['MontoActual'], 2, ',', '.'), number_format($diferencia, 2, ',', '.')));
	}
	$sum_anterior += $field['MontoAnterior'];
	$sum_actual += $field['MontoActual'];
}

if ($sw == "ASIGNACION") {
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Row(array('TOTAL ASIGNACIONES', number_format($sum_anterior, 2, ',', '.'), number_format($sum_actual, 2, ',', '.'), number_format(($sum_actual - $sum_anterior), 2, ',', '.')));
	$asignacion_anterior = $sum_anterior;
	$asignacion_actual = $sum_actual;
}

elseif ($sw == "DEDUCCION") {
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Row(array('TOTAL DEDUCCIONES', number_format($sum_anterior, 2, ',', '.'), number_format($sum_actual, 2, ',', '.'), number_format(($sum_actual - $sum_anterior), 2, ',', '.')));
	
	$neto_anterior = $asignacion_anterior - $sum_anterior;
	$neto_actual = $asignacion_actual - $sum_actual;
	$pdf->Row(array('TOTAL NETO', number_format($neto_anterior, 2, ',', '.'), number_format($neto_actual, 2, ',', '.'), number_format(($neto_actual - $neto_anterior), 2, ',', '.')));
}
//---------------------------------------------------
$pdf->Output();
?>  
