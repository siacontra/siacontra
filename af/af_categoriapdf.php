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
	//$pdf->SetXY(20, 10); $pdf->Cell(190, 5,utf8_decode( 'República Bolivariana de Venezuela'), 0, 1, 'L');
	$pdf->SetXY(20, 15); $pdf->Cell(190, 5, utf8_decode('Contraloría del Estado Monagas'), 0, 1, 'L');	
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(190, 10, utf8_decode('Maestro Categoría de Depreciación'), 0, 1, 'C');	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->Cell(5, 5);
	$pdf->Cell(20, 5, 'CATEGORIA', 1, 0, 'C', 1);
	$pdf->Cell(80, 5, 'DESCRIPCION', 1, 0, 'C', 1);
	$pdf->Cell(28, 5, 'CUENTA ACTIVO', 1, 0, 'C', 1);
	$pdf->Cell(31, 5, 'CUENTA DEPRECIACION', 1, 0, 'C', 1);
	$pdf->Cell(20, 5, 'ESTADO', 1, 1, 'C', 1);
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf=new PDF_MC_Table();
$pdf->Open();
Cabecera($pdf);
//	Cuerpo
$sql="SELECT * FROM af_categoriadeprec ORDER BY CodCategoria";
$query=mysql_query($sql) or die ($sql.mysql_error());
while ($field=mysql_fetch_array($query)) {
    if($field['Estado']=='A'){$estado='Activo';}else{$estado='Inactivo';}
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetWidths(array(20, 80, 28, 31, 20));
	$pdf->SetAligns(array('C', 'L', 'C', 'C', 'C'));
	$pdf->Cell(5, 5); $pdf->Row(array($field['CodCategoria'], utf8_decode($field['DescripcionLocal']), $field['CuentaHistorica'], $field['CuentaDepreciacion'], $estado));
	$y=$pdf->GetY(); if ($y==270) Cabecera($pdf);
}
//---------------------------------------------------

$pdf->Output();
?>  
