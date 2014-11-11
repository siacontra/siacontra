<?php
define('FPDF_FONTPATH','font/');
require('mc_table3.php');
require('fphp.php');
require('../funciones.php');
connect();
//	---------------------------------------------------

//	Creación del objeto de la clase heredada
$pdf=new PDF_MC_Table();
$pdf->Open();
$pdf->SetMargins(10, 5, 10);
//	---------------------------------------------------

//	Encabezado
$pdf->AddPage('P', 'Letter');
$pdf->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetXY(20, 10); $pdf->Cell(190, 5, 'Contraloría del estado Monagas', 0, 1, 'L');
$pdf->SetXY(20, 15); $pdf->Cell(190, 5, 'Dirección de Recursos Humanos', 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(190, 10, 'Reporte de Jubilación', 0, 1, 'C');
$pdf->Ln(5);
//	---------------------------------------------------

//	Consulto datos del trabajador, calculo de jubilacion y funcionario responsable...
$sql = "SELECT 
			rpj.*, 
			mp.NomCompleto, 
			mp.Ndocumento, 
			mp.Fnacimiento, 
			mp.Sexo, 
			md.Dependencia, 
			me.Fingreso, 
			me.SueldoActual, 
			rp.DescripCargo, 
			rp.NivelSalarial 
		FROM 
			rh_proceso_jubilacion rpj 
			INNER JOIN mastpersonas mp ON (rpj.CodPersona = mp.CodPersona)
			INNER JOIN mastempleado me ON (mp.CodPersona = me.CodPersona) 
			INNER JOIN mastdependencias md ON (me.CodDependencia = md.CodDependencia) 
			INNER JOIN rh_puestos rp ON (me.CodCargo = rp.CodCargo)
		WHERE 
			mp.CodPersona = '".$codpersona."'";
$query_datos = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_datos) != 0) $field_datos = mysql_fetch_array($query_datos);
list($a, $m, $d)=SPLIT( '[/.-]', $field_datos['Fingreso']); $fingreso=$d."/".$m."/".$a;
list($a, $m, $d)=SPLIT( '[/.-]', $field_datos['Fnacimiento']); $fnacimiento=$d."/".$m."/".$a;
//	---------------------------------------------------

//	Imprimo datos del trabajador...
$pdf->SetDrawColor(0, 0, 0); 
$pdf->SetTextColor(0, 0, 0);

$pdf->SetFillColor(235, 235, 235); 
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(190, 8, 'I. DATOS DE JUBILACION', 1, 1, 'L', 1);
$pdf->SetFillColor(255, 255, 255);

$y = $pdf->GetY();
$pdf->Rect(10, $y, 90, 10, "DF"); 
$pdf->Rect(100, $y, 25, 10, "DF"); 
$pdf->Rect(125, $y, 25, 10, "DF"); 
$pdf->Rect(150, $y, 25, 10, "DF"); 
$pdf->Rect(175, $y, 25, 10, "DF");
$pdf->SetY($y); 
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(90, 4, 'Apellidos y Nombres', 0, 0, 'L');
$pdf->Cell(25, 4, 'Nro. Doc.', 0, 0, 'C');
$pdf->Cell(25, 4, 'Fecha Nac.', 0, 0, 'C');
$pdf->Cell(25, 4, 'Sexo', 0, 0, 'C');
$pdf->Cell(25, 4, 'Edad', 0, 1, 'C');
$pdf->SetY($y+4); 
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(90, 5, utf8_decode($field_datos['NomCompleto']), 0, 0, 'L');
$pdf->Cell(25, 5, $field_datos['Ndocumento'], 0, 0, 'C');
$pdf->Cell(25, 5, $fnacimiento, 0, 0, 'C');
$pdf->Cell(25, 5, $field_datos['Sexo'], 0, 0, 'C');
$pdf->Cell(25, 5, $field_datos['Edad'], 0, 1, 'C');

$y = $pdf->GetY();
$pdf->Rect(10, $y, 155, 10, "DF"); 
$pdf->Rect(165, $y, 35, 10, "DF"); 
$pdf->SetY($y); 
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(155, 4, 'Dependencia', 0, 0, 'L');
$pdf->Cell(35, 4, 'Fecha de Ingreso', 0, 1, 'C');
$pdf->SetY($y+4); 
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(155, 5, utf8_decode($field_datos['Dependencia']), 0, 0, 'L');
$pdf->Cell(35, 5, $fingreso, 0, 1, 'C');

$y = $pdf->GetY();
$pdf->Rect(10, $y, 155, 10, "DF"); 
$pdf->Rect(165, $y, 35, 10, "DF"); 
$pdf->SetY($y); 
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(155, 4, 'Cargo Actual', 0, 0, 'L');
$pdf->Cell(35, 4, 'NivelSalarial', 0, 1, 'C');
$pdf->SetY($y+4); 
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(155, 5, utf8_decode($field_datos['DescripCargo']), 0, 0, 'L');
$pdf->Cell(35, 5, number_format($field_datos['NivelSalarial'], 2, ',', '.'), 0, 1, 'C');

$pdf->Ln(10);
//	---------------------------------------------------

//	Consulto el tiempo de servicio...
$sql = "SELECT * FROM rh_empleado_antecedentes WHERE CodPersona = '".$codpersona."' ORDER BY FIngreso DESC";
$query_tiempo = mysql_query($sql) or die ($sql.mysql_error());

//	Imprimo el tiempo de servicio...
$pdf->SetDrawColor(0, 0, 0); 
$pdf->SetTextColor(0, 0, 0);

$pdf->SetFillColor(235, 235, 235); 
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(190, 8, 'II. TIEMPO DE SERVICIO', 1, 1, 'L', 1);
$pdf->SetFillColor(255, 255, 255);

$pdf->SetFont('Arial', 'B', 8);
$pdf->SetWidths(array(105, 20, 20, 15, 15, 15));
$pdf->SetAligns(array('L', 'C', 'C', 'C', 'C', 'C'));
$pdf->Row(array('Organismo', 'Ingreso', 'Egreso', 'Años', 'Meses', 'Dias'));
$pdf->SetFont('Arial', '', 8);
while ($field_tiempo = mysql_fetch_array($query_tiempo)) { 
	list($a, $m, $d)=SPLIT( '[/.-]', $field_tiempo['FIngreso']); $fingreso=$d."/".$m."/".$a;
	list($a, $m, $d)=SPLIT( '[/.-]', $field_tiempo['FEgreso']); $fegreso=$d."/".$m."/".$a;
	
	list ($a, $m, $d) = getEdadAMD($fingreso, $fegreso);
	$anios = $a; 
	$meses = $m; 
	$dias = $d;
	
	if ($dias == 30) { $meses++; $dias = 0; }
	if ($meses == 13) { $anios++; $meses = 0; }
	
	$total_anios += (int) $anios;
	$total_meses += (int) $meses;
	$total_dias += (int) $dias;
	
	$pdf->Row(array($field_tiempo['Organismo'], $fingreso, $fegreso, $anios, $meses, $dias));
}
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetWidths(array(145, 15, 15, 15));
$pdf->SetAligns(array('R', 'C', 'C', 'C', 'C', 'C'));

$pdf->Row(array('SUB-TOTAL ANTIGUEDAD', $total_anios, $total_meses, $total_dias));

if ($total_dias >= 30) {
	$div = (int) ($total_dias / 30);
	$total_meses = $total_meses + $div;
	$total_dias = $total_dias - ($div * 30);
}
if ($total_meses >= 12) {
	$div = (int) ($total_meses / 12);
	$total_anios = $total_anios + $div;
	$total_meses = $total_meses - ($div * 12);
	
	if ($total_meses >= 8) $total_anios++;
}
$anios_servicio = $total_anios;

$pdf->Row(array('TOTAL ANTIGUEDAD', $total_anios, $total_meses, $total_dias));
$pdf->Ln(10);
//	---------------------------------------------------

//	Imprimo Caculo de Jubilacion...
$pdf->SetFillColor(235, 235, 235); 
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(190, 8, 'III. CALCULO DE JUBILACION', 1, 1, 'L', 1);
$pdf->SetFillColor(255, 255, 255);

$y = $pdf->GetY();
$pdf->Rect(10, $y, 60, 10, "DF"); 
$pdf->Rect(70, $y, 60, 10, "DF"); 
$pdf->Rect(130, $y, 70, 10, "DF"); 
$pdf->SetY($y); 
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(60, 4, 'Coeficiente', 0, 0, 'C');
$pdf->Cell(60, 4, 'Porcentaje', 0, 0, 'C');
$pdf->Cell(70, 4, 'Salario Jubilación', 0, 1, 'C');
$pdf->SetY($y+4); 
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(60, 5, $field_datos['Coeficiente'], 0, 0, 'C');
$pdf->Cell(60, 5, number_format(($field_datos['Coeficiente'] * 100), 2, ',', '.'), 0, 0, 'C');
$pdf->Cell(70, 5, number_format($field_datos['MontoJubilacion'], 2, ',', '.'), 0, 1, 'C');

$y = $pdf->GetY();
$pdf->Rect(10, $y, 190, 15, "DF"); 
$pdf->SetY($y); 
$pdf->SetFont('Arial', 'B', 8); 
$pdf->Cell(190, 4, 'Observaciones', 0, 1, 'L');
$pdf->SetY($y+4); 
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(190, 5, utf8_decode($field_datos['ObsAprobado']), 0, 'L');
$pdf->Ln(15);
//	---------------------------------------------------

//	Imprimo funcionario responsable...
$pdf->SetFillColor(235, 235, 235); 
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(190, 8, 'IV. FUNCIONARIO RESPONSABLE DE LA INFORMACION', 1, 1, 'L', 1);
$pdf->SetFillColor(255, 255, 255);

$y = $pdf->GetY();
$pdf->Rect(10, $y, 60, 15, "DF"); 
$pdf->Rect(70, $y, 60, 15, "DF"); 
$pdf->Rect(130, $y, 70, 15, "DF"); 
$pdf->SetY($y); 
$pdf->SetFont('Arial', 'B', 8); 
$pdf->Cell(60, 4, 'Procesado Por', 0, 0, 'L');
$pdf->Cell(60, 4, 'Aprobado Por', 0, 0, 'L');
$pdf->Cell(70, 4, 'Dirección de Recursos Humanos', 0, 1, 'L');
$pdf->SetY($y+4); 
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(60, 5, utf8_decode($field_datos['ProcesadoPor']), 0, 0, 'L');
$pdf->Cell(60, 5, utf8_decode($field_datos['AprobadoPor']), 0, 0, 'L');
$pdf->Cell(70, 5, '', 0, 0, 'L');
$pdf->Ln(10);
//	---------------------------------------------------

$pdf->Output();
?>  
