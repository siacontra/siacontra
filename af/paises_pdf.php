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
	$pdf->SetXY(20, 10); $pdf->Cell(190, 5,utf8_decode( 'Contralor�a del Estado Sucre'), 0, 1, 'L');
	$pdf->SetXY(20, 15); $pdf->Cell(190, 5, utf8_decode('Divisi�n de Recursos Humanos'), 0, 1, 'L');	
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(190, 10, 'Maestro de Paises', 0, 1, 'C');	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(35, 5);
	$pdf->Cell(20, 5, 'PAIS', 1, 0, 'C', 1);
	$pdf->Cell(80, 5, 'DESCRIPCION', 1, 0, 'C', 1);
	$pdf->Cell(20, 5, 'ESTADO', 1, 1, 'C', 1);
}
//---------------------------------------------------

//---------------------------------------------------
//	Creaci�n del objeto de la clase heredada
$pdf=new PDF_MC_Table();
$pdf->Open();
Cabecera($pdf);
//	Cuerpo
$query=mysql_query("SELECT CodPais, Pais, Estado FROM mastpaises ORDER BY CodPais") or die ($sql.mysql_error());
while ($field=mysql_fetch_array($query)) {
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(20, 80, 20));
	$pdf->SetAligns(array('C', 'L', 'C'));
	$pdf->Cell(35, 5); $pdf->Row(array($field[0], $field[1], $field[2]));
	$y=$pdf->GetY(); if ($y==270) Cabecera($pdf);
}
//---------------------------------------------------

$pdf->Output();
?>  
