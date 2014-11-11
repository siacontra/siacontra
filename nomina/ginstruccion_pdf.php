<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<?php
define('FPDF_FONTPATH','font/');
require('mc_table.php');
require('fphp.php');
connect();

//---------------------------------------------------
//	Imprime la cabedera del documento
function Cabecera($pdf) {
	$pdf->AddPage();
	$pdf->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY(20, 10); $pdf->Cell(190, 5,( 'Contraloría del Estado Monagas'), 0, 1, 'L');
	$pdf->SetXY(20, 15); $pdf->Cell(190, 5, ('Dirección de Recursos Humanos'), 0, 1, 'L');	
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(190, 10, 'Maestro de Grado de Instrucción', 0, 1, 'C');	
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf=new PDF_MC_Table();
$pdf->Open();
Cabecera($pdf);
//	Cuerpo
$query=mysql_query("SELECT * FROM rh_gradoinstruccion ORDER BY CodGradoInstruccion") or die ($sql.mysql_error());
while ($field=mysql_fetch_array($query)) {
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);	$pdf->SetFillColor(250, 250, 250);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(30, 5, 'Grado:', 0, 0, 'R', 1);
	$pdf->Cell(160, 5, $field['CodGradoInstruccion'], 0, 1, 'L', 1);
	$pdf->Cell(30, 5, 'Descripción:', 0, 0, 'R', 1);
	$pdf->Cell(160, 5, $field['Descripcion'], 0, 1, 'L', 1);
	$pdf->Cell(30, 5, 'Estado:', 0, 0, 'R', 1);
	$pdf->Cell(160, 5, $field['Estado'], 0, 1, 'L', 1);
	$pdf->Ln(2);	
	//	DETALLES
	$query1=mysql_query("SELECT * FROM rh_nivelgradoinstruccion WHERE (CodGradoInstruccion='".$field['CodGradoInstruccion']."') ORDER BY Nivel") or die ($sql.mysql_error());
	$rows1=mysql_num_rows($query1);
	if ($rows1!=0) {
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetTextColor(255, 255, 255);	$pdf->SetFillColor(200, 200, 200);
		$pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(30, 5, 'NIVEL', 1, 0, 'C', 1);
		$pdf->Cell(160, 5, 'DETALLE', 1, 1, 'C', 1);
		while ($field1=mysql_fetch_array($query1)) {
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 6);
			$pdf->SetWidths(array(30, 160));
			$pdf->SetAligns(array('C', 'L'));
			$pdf->Row(array($field1['Nivel'], $field1['Descripcion']));
			$y=$pdf->GetY(); if ($y==270) Cabecera($pdf);
		}
	}
}
//---------------------------------------------------

$pdf->Output();
?>
