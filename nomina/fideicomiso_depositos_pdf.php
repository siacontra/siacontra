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
	$pdf->Cell(190, 5, ('TIPO DE NOMINA '.$nomina), 0, 1, 'L');
	$pdf->Cell(190, 5, ($periodo), 0, 1, 'L');
	$pdf->Ln(3);
	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetWidths(array(20, 90, 45, 25, 10));
	$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C'));
	$pdf->Row(array(('Cédula'), 'Nombre Completo', 'Nro. Cuenta', 'Monto', 'Dias'));
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
list($anio, $mes)=SPLIT( '[/.-]', $fperiodo); $m = (int) $mes;
$dias = getDiasMes($anio, $m);
$periodo_fecha = "DESDE: 01/$mes/$anio HASTA: $dias/$mes/$anio";


Cabecera($pdf, $ftiponom, $field_nomina['Nomina'], $field_proceso['Descripcion'], $periodo, $periodo_fecha);

//	Cuerpo
$sql = "SELECT
			me.CodEmpleado,
			mp.NomCompleto,
			bp.Ncuenta,
			mp.Ndocumento,
			tnec.Monto,
			tnec.Cantidad
		FROM
			mastpersonas mp
			INNER JOIN mastempleado me ON (mp.CodPersona = me.CodPersona)
			INNER JOIN pr_tiponominaempleadoconcepto tnec ON (mp.CodPersona = tnec.CodPersona)
			LEFT JOIN bancopersona bp ON (mp.CodPersona = bp.CodPersona AND bp.Aportes = 'FI')
		WHERE
			tnec.CodOrganismo = '".$forganismo."' AND
			tnec.Periodo = '".$fperiodo."' AND
			tnec.CodTipoNom = '".$ftiponom."' AND
			tnec.CodTipoProceso = 'FIN' AND
			tnec.CodConcepto = '0045'
			$filtro_status
		ORDER BY length(mp.Ndocumento), mp.Ndocumento";
$query = mysql_query($sql) or die ($sql.mysql_error());
while ($field = mysql_fetch_array($query)) {
	$sum_total += $field['Monto'];
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetWidths(array(20, 90, 45, 25, 10));
	$pdf->SetAligns(array('R', 'L', 'L', 'R', 'R'));
	$pdf->Row(array(number_format($field['Ndocumento'], 0, '', '.'), $field['NomCompleto'], $field['Ncuenta'], number_format($field['Monto'], 2, ',', '.'), number_format($field['Cantidad'], 2, ',', '.')));
	if ($pdf->GetY() > 260) Cabecera($pdf, $ftiponom, $field_nomina['Nomina'], $field_proceso['Descripcion'], $periodo, $periodo_fecha);
}
//---------------------------------------------------
$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array('', '', '', number_format($sum_total, 2, ',', '.'), ''));
//---------------------------------------------------
$pdf->Rect(10, 223, 70, 0.1, "DF");
$pdf->Rect(120, 223, 70, 0.1, "DF");
$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(10, 225);
$pdf->Cell(110, 4, ('ELABORADO POR:'), 0, 0, 'L');
$pdf->Cell(80, 4, ('CONFORMADO POR:'), 0, 1, 'L');

$pdf->Cell(110, 4, ('T.S.U. OMAIRYS MONTERO'), 0, 0, 'L');
$pdf->Cell(80, 4, ('ABOG. MARLYN ABREU'), 0, 1, 'L');

$pdf->Cell(110, 4, ('ANALISTA DE RR.HH. II'), 0, 0, 'L');
$pdf->Cell(80, 4, ('JEFE DE DIVISION RR.HH'), 0, 1, 'L');
//---------------------------------------------------
$pdf->Output();
?>  
