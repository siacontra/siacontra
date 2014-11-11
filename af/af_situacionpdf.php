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
	$pdf->SetXY(20, 10); $pdf->Cell(190, 5,utf8_decode( 'República Bolivariana de Venezuela'), 0, 1, 'L');
	$pdf->SetXY(20, 15); $pdf->Cell(190, 5, utf8_decode('Contraloría del Estado Sucre'), 0, 1, 'L');	
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(190, 10,utf8_decode('Maestro Situación del Activo'), 0, 1, 'C');	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(25, 5);
	$pdf->Cell(20, 5, 'SITUACION', 1, 0, 'C', 1);
	$pdf->Cell(50, 5, 'DESCRIPCION', 1, 0, 'C', 1);
	$pdf->Cell(20, 5, 'DEPRECIACION', 1, 0, 'C', 1);
	$pdf->Cell(25, 5, 'AJUSTE x INFLACION', 1, 0, 'C', 1);
	$pdf->Cell(20, 5, 'ESTADO', 1, 1, 'C', 1);
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf=new PDF_MC_Table();
$pdf->Open();
Cabecera($pdf);
//	Cuerpo
$sql="SELECT CodSituActivo, 
             Descripcion, 
			 DepreciacionFlag, 
			 RevaluacionFlag, 
			 Estado 
	    FROM af_situacionactivo 
	ORDER BY CodSituActivo";
$query=mysql_query($sql) or die ($sql.mysql_error());
while ($field=mysql_fetch_array($query)) {
    if($field[3]=='S'){$f3='Si';}
    if($field[2]=='S')$f4 = 'Si';
    if($field[4]=='A'){$estado='Activo';}else{$estado='Inactivo';}
	//if($field[2]=='S'){$depFlags=$pdf->Image('imagenes/aceptar.jpg', 113 ,38, 4 , 4,'JPG', 'http://www.desarrolloweb.com');}else{$estado='Inactivo';}
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(20, 50, 20,25,20));
	$pdf->SetAligns(array('C', 'L', 'C','C','C'));
	$pdf->Cell(25, 5); $pdf->Row(array($field[0], $field[1], $f4, $f3, $estado));
	$y=$pdf->GetY(); if ($y==270) Cabecera($pdf);
}
//---------------------------------------------------

$pdf->Output();
?>  
