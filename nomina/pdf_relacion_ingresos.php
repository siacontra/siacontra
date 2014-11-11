<?php
define('FPDF_FONTPATH','font/');
require('mc_table.php');
require('fphp_nomina.php');
connect();
//---------------------------------------------------

//---------------------------------------------------
//	Imprime la cabedera del documento
function Cabecera($pdf, $head, $fperiodo) {
	$pdf->AddPage();
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(260, 4, ('CONTRALORIA DEL ESTADO MONAGAS'), 0, 1, 'L');
	$pdf->Cell(260, 4, ('DIVISION DE ADMINISTRACION Y PRESUPUESTO'), 0, 1, 'L');
	$pdf->Cell(260, 4, ('DIRECCION DE RECURSOS HUMANOS'), 0, 1, 'L');
	$pdf->Cell(260, 4, ('R.I.F. '.$head['DocFiscal']), 0, 1, 'L');
	//---------------------------------------------------
	$pdf->Cell(260, 6, 'RELACION DE INGRESOS', 0, 1, 'C');
	//---------------------------------------------------
	$pdf->Cell(160, 4, 'NOMBRES Y APELLIDOS', 0, 0, 'L');
	$pdf->Cell(100, 4, 'CEDULA DE IDENTIDAD', 0, 1, 'C');
	//---------------------------------------------------
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(160, 4, utf8_decode($head['NomCompleto']), 0, 0, 'L');
	$pdf->Cell(100, 4, number_format($head['Ndocumento'], 0, '', '.'), 0, 1, 'C');
	//---------------------------------------------------
	$pdf->SetFont('Arial', 'BU', 8);
	$pdf->Cell(260, 10, utf8_decode('REGISTRO DE SUELDOS Y REMUNERACIONES PERCIBIDAS EN EL AÑO '.$fperiodo), 0, 1, 'C');
	//---------------------------------------------------
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(50, 4, ('PERIODO'), 1, 0, 'L');
	$pdf->Cell(40, 4, ('SUELDO FIJO'), 1, 0, 'R');
	$pdf->Cell(5, 4);
	$pdf->Cell(120, 4, ('BONOS Y REMUNERACIONES'), 1, 0, 'L');
	$pdf->Cell(40, 4, ('MONTO'), 1, 1, 'R');
	//---------------------------------------------------
	$pdf->SetFont('Arial', '', 8);
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf=new PDF_MC_Table('L', 'mm', 'Letter');
$pdf->Open();
$pdf->SetAutoPageBreak(0, 2);
//---------------------------------------------------
//	obtengo los datos de la cabecera
if ($codempleado != "") $filtro_empleado = "AND me.CodPersona = '".$codempleado."'";
$sql = "SELECT
			me.CodEmpleado,
			me.CodPersona,
			mp.NomCompleto,
			mp.Ndocumento,
			(SELECT DocFiscal FROM mastorganismos WHERE CodOrganismo = '".$forganismo."') AS DocFiscal
		FROM 
			mastpersonas mp
			INNER JOIN mastempleado me ON (mp.CodPersona = me.CodPersona)
		WHERE
			me.CodTipoNom = '".$ftiponom."' $filtro_empleado
		ORDER BY length(mp.Ndocumento), mp.Ndocumento";
$query_empleado = mysql_query($sql) or die ($sql.mysql_error());
while ($field_empleado = mysql_fetch_array($query_empleado)) {
	Cabecera($pdf, $field_empleado, $fperiodo);
	//---------------------------------------------------
	$total_ingresos = 0;
	$total_bonos = 0;
	$total_retenciones = 0;
	//---------------------------------------------------
	//	obtengo los sueldos por periodo del empleado
	$sueldos = consultarSueldoNormal($fperiodo, $field_empleado['CodPersona']);
	foreach ($sueldos as $sueldo) {
		$pdf->SetFont('Arial', '', 8);
		list($anio, $mes) = SPLIT( '[/.-]', $sueldo['Periodo']);	$mes = (int) $mes;
		
		$pdf->Cell(50, 5, strtoupper(getNombreMes($mes)), 1, 0, 'L');
		$pdf->Cell(40, 5, number_format($sueldo['TotalIngresos'], 2, ',', '.'), 1, 1, 'R');
		$total_ingresos += $sueldo['TotalIngresos'];
	}
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(50, 5, 'TOTAL', 1, 0, 'C');
	$pdf->Cell(40, 5, number_format($total_ingresos, 2, ',', '.'), 1, 1, 'R');
	//---------------------------------------------------
	$pdf->SetY(54.00125);
	//---------------------------------------------------
	//	obtengo las bonficaciones del empleado
	$bonos = consultarConceptoBonificacionAnual($fperiodo, $field_empleado['CodPersona']);
	foreach ($bonos as $bono) {
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetX(105);
		$pdf->Cell(120, 5, utf8_decode($bono['Descripcion']).' '.substr($bono['Periodo'], 0, 4), 1, 0, 'L');
		$pdf->Cell(40, 5, number_format($bono['Monto'], 2, ',', '.'), 1, 1, 'R');
		$total_bonos += $bono['Monto'];
	}
	//---------------------------------------------------
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetX(105);
	$pdf->Cell(120, 5, 'TOTAL', 1, 0, 'C');
	$pdf->Cell(40, 5, number_format($total_bonos, 2, ',', '.'), 1, 1, 'R');
	//---------------------------------------------------
	$pdf->SetY($pdf->GetY()+4);
	$pdf->SetX(105); 
	$pdf->Cell(120, 5, ('RETENCIONES'), 1, 0, 'L');
	$pdf->Cell(40, 5, ('MONTO'), 1, 1, 'R');
	//---------------------------------------------------
	//	obtengo las retenciones del empleado
	$retenciones = consultarConceptoRetencionAnual($fperiodo, $field_empleado['CodPersona']);
	foreach ($retenciones as $retencion) {
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetX(105);
		$pdf->Cell(120, 5, utf8_decode($retencion['Descripcion']), 1, 0, 'L');
		$pdf->Cell(40, 5, number_format($retencion['Monto'], 2, ',', '.'), 1, 1, 'R');
		$total_retenciones += $retencion['Monto'];
	}
	//---------------------------------------------------
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->SetX(105);
	$pdf->Cell(120, 5, 'TOTAL', 1, 0, 'C');
	$pdf->Cell(40, 5, number_format($total_retenciones, 2, ',', '.'), 1, 1, 'R');
	//---------------------------------------------------
	//	imprimo los totales
	$ingresos = $total_ingresos + $total_bonos;
	$total = $ingresos - $total_retenciones;
	$pdf->SetY(168);
	$pdf->Cell(225, 5, 'TOTAL INGRESOS ............................................................................................................................................................................................................', 0, 0, 'L');
	$pdf->Cell(30, 5, number_format($ingresos, 2, ',', '.'), 0, 1, 'R');
	$pdf->Cell(225, 5, 'TOTAL RETENCIONES .....................................................................................................................................................................................................', 0, 0, 'L');
	$pdf->Cell(30, 5, number_format($total_retenciones, 2, ',', '.'), 0, 1, 'R');
	$pdf->Cell(225, 5, 'TOTAL GENERAL .............................................................................................................................................................................................................', 0, 0, 'L');
	$pdf->Cell(30, 5, number_format($total, 2, ',', '.'), 0, 1, 'R');
	//---------------------------------------------------
	//	imprimo las firmas de la hoja
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
	$pdf->Rect(10, 194, 55, 0.1, "DF");
	$pdf->Rect(75, 194, 55, 0.1, "DF");
	$pdf->Rect(140, 194, 60, 0.1, "DF");
	$pdf->Rect(210, 194, 60, 0.1, "DF");
	
	$pdf->SetXY(10, 195);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(65, 3, ('ELABORADO POR:'), 0, 0, 'L');
	$pdf->Cell(65, 3, ('REVISADO POR:'), 0, 0, 'L');
	$pdf->Cell(70, 3, ('CONFORMADO POR:'), 0, 0, 'L');
	$pdf->Cell(70, 3, ('APROBADO POR:'), 0, 1, 'L');
	
	$pdf->Cell(65, 3, ('T.S.U. OMAIRYS MONTERO'), 0, 0, 'L');
	$pdf->Cell(65, 3, ('ABOG. MARLYN ABREU'), 0, 0, 'L');
	$pdf->Cell(70, 3, ('LICDA. YOSMAR GREHAM'), 0, 0, 'L');
	$pdf->Cell(70, 3, ('LICDO. FREDDY CUDJOE'), 0, 1, 'L');
	
	$pdf->Cell(65, 3, ('ANALISTA RR.HH II'), 0, 0, 'L');
	$pdf->Cell(65, 3, ('JEFA RECURSOS HUMANOS'), 0, 0, 'L');
	$pdf->Cell(70, 3, ('JEFA (E) ADMINISTRACION Y PPTO.'), 0, 0, 'L');
	$pdf->Cell(70, 3, ('CONTRALOR (I)'), 0, 1, 'L');
}

//---------------------------------------------------
$pdf->Output();
?>  
