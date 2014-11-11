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
	$pdf->Cell(190, 10, 'Maestro de Divisiones', 0, 1, 'C');
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(30, 5, 'DIVISION', 1, 0, 'C', 1);
	$pdf->Cell(160, 5, 'DESCRIPCION', 1, 1, 'C', 1);
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf=new PDF_MC_Table();
$pdf->Open();
Cabecera($pdf);
//	Cuerpo
$query=mysql_query("SELECT mastdivisiones.CodDivision, mastdivisiones.Division, mastdependencias.Dependencia, mastorganismos.Organismo FROM mastdivisiones, mastdependencias, mastorganismos WHERE mastdivisiones.CodDependencia=mastdependencias.CodDependencia AND mastdependencias.CodOrganismo=mastorganismos.CodOrganismo AND mastorganismos.CodOrganismo<>'' ORDER BY mastorganismos.CodOrganismo, mastdependencias.CodDependencia, mastdivisiones.CodDivision") or die ($sql.mysql_error());
while ($field=mysql_fetch_array($query)) {
	if ($org!=$field[3]) {
		$org=$field[3];
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 10);
		$pdf->Cell(190, 5, $field[3], 1, 1, 'L', 1);
	}
	if ($dep!=$field[2]) {
		$dep=$field[2];
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(245, 245, 245); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(190, 5, $field[2], 1, 1, 'L', 1);
	}
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(30, 160));
	$pdf->SetAligns(array('C', 'L'));
	$pdf->Row(array($field[0], $field[1]));
	$y=$pdf->GetY(); if ($y==270) Cabecera($pdf);
}
//---------------------------------------------------

$pdf->Output();
?>
