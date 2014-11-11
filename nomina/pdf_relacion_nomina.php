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
	$pdf->Cell(190, 5, ('RELACION DE NOMINA - '.$nomina), 0, 1, 'L');
	$pdf->Cell(190, 5, ($periodo.' '.utf8_decode($proceso)), 0, 1, 'L');
	$pdf->Ln(5);
	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 6);
		$pdf->SetWidths(array( 8,   80, 20,  20,  18,  18,  40,  40,  10,  10, 10));
	$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
	$pdf->Row(array('N°', 'APELLIDOS Y NOMBRES', 'NRO. CEDULA', 'RIF', 'FECHA DE NACIMIENTO', 'FECHA INGRESO', 'CARGO', 'UBICACION ADMINISTRATIVA', 'PROFESION', 'CONDICION0' , 'Monto Bs.',));
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf = new PDF_MC_Table("L");
$pdf->Open();
$pdf->SetMargins(10, 15, 10);

$h = 1;

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
  $sql = "SELECT 
			mp.Ndocumento, 
			mp.NomCompleto AS Busqueda,
			ptne.TotalNeto,
			rbp.CodBeneficiario,
			rbp.NroDocumento,
			rbp.NombreCompleto,
			tiponomina.Nomina,
			mp.Fnacimiento,
			mastempleado.Fingreso
			FROM
			mastpersonas AS mp
			INNER JOIN pr_tiponominaempleado AS ptne ON (mp.CodPersona = ptne.CodPersona)
			LEFT JOIN rh_beneficiariopension AS rbp ON (mp.CodPersona = rbp.CodPersona)
			INNER JOIN mastempleado ON mp.CodPersona = mastempleado.CodPersona
			INNER JOIN tiponomina ON mastempleado.CodTipoNom = tiponomina.CodTipoNom
		WHERE
			ptne.CodTipoNom = '".$ftiponom."' AND
			ptne.Periodo = '".$fperiodo."' AND
			ptne.CodTipoProceso = '".$ftproceso."'
		ORDER BY length(mp.Ndocumento), mp.Ndocumento";
//echo $sql;

$query = mysql_query($sql) or die ($sql.mysql_error());
while ($field = mysql_fetch_array($query)) {
	if ($field['CodBeneficiario'] != "") {
		$ndocumento = $field['NroDocumento'];
		$nombre = $field['Busqueda']." (".$field['NombreCompleto'].")";
	} else {
		$ndocumento = $field['Ndocumento'];
		$nombre = $field['Busqueda'];
	}
	$sum_total += $field['TotalNeto'];
	$pdf->SetFont('Arial', '', 8.5);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetWidths(array( 8,   80, 20,  20,  18,  18,  40,  40,  10,  10, 10));
	$pdf->SetAligns(array('C', 'L', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
	
	$pdf->Row(array(
	''.$h.'', 
	
	utf8_decode($nombre),
	
	number_format($ndocumento, 0, '', '.'), 
	
    number_format($ndocumento, 0, '', '.'), 
	$field['Fnacimiento'],
	$field['Fingreso'],
	number_format($field['TotalNeto'], 2, ',', '.')
	
	));
	 
	 
	if ($pdf->GetY() > 260) Cabecera($pdf, $ftiponom, $field_nomina['Nomina'], $field_proceso['Descripcion'], $periodo);
	$h++;
}

$pdf->SetFont('Arial', 'B', 8);
$pdf->Row(array('','', 'TOTAL', number_format($sum_total, 2, ',', '.'), '', ''));
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
//$pdf->Cell(80, 4, utf8_decode($nomaprobado), 0, 1, 'L');
$pdf->Cell(80, 4, utf8_decode('Cesar Granado'), 0, 1, 'L');
$pdf->Cell(110, 4, ($carelaborado), 0, 0, 'L');
//$pdf->Cell(80, 4, ($caraprobado), 0, 1, 'L');
$pdf->Cell(80, 4, utf8_decode('Director General'), 0, 1, 'L');
//---------------------------------------------------
$pdf->Output();
?>  
