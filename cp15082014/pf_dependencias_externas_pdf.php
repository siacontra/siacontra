<?php
define('FPDF_FONTPATH','font/');
require('mc_table.php');
require('fphp.php');
connect();

//---------------------------------------------------
//	Imprime la cabedera del documento
function Cabecera($pdf){
	$pdf->AddPage();
	$pdf->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY(20, 10); $pdf->Cell(190, 5,utf8_decode( 'Contraloría del Estado Delta Amacuro'), 0, 1, 'L');
	$pdf->SetXY(20, 15); $pdf->Cell(190, 5, utf8_decode('División de Correspondencia y Mensajería'), 0, 1, 'L');	
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(190, 10,utf8_decode('Maestro Dependencias Externas'), 0, 1, 'C');	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(20, 5, 'COD.ORGANISMO', 1, 0, 'C', 1);
	$pdf->Cell(20, 5, 'COD.DEPENDENCIA', 1, 0, 'C', 1);
	$pdf->Cell(75, 5, 'DEPENDENCIA', 1, 0, 'C', 1);
	$pdf->Cell(60, 5, 'REPRESENTANTE', 1, 0, 'C', 1);
	$pdf->Cell(15, 5, 'ESTADO', 1, 1, 'C', 1);
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf=new PDF_MC_Table();
$pdf->Open();
Cabecera($pdf);
//	Cuerpo
$sql="SELECT *
	    FROM pf_dependenciasexternas 
	ORDER BY CodOrganismo, CodDependencia";
$query=mysql_query($sql) or die ($sql.mysql_error());
while ($field=mysql_fetch_array($query)) {
    if($field[2]=='1') $f2='Si'; else $f2='';
	if($field[3]=='1') $f3='Si'; else $f3='';
	if($field[4]=='1') $f4='Si'; else $f4='';
	if($field['Estado']=='A'){$estado='Activo';}else{$estado='Inactivo';}
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(20, 20, 75, 60, 15));
	$pdf->SetAligns(array('C', 'C', 'L', 'L', 'C'));
	$pdf->Row(array($field['CodOrganismo'],$field['CodDependencia'], $field['Dependencia'], $field['Representante'], $estado));
	$y=$pdf->GetY(); if ($y==270) Cabecera($pdf);
}
//---------------------------------------------------

$pdf->Output();
?>  
