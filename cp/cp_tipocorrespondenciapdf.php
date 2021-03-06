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
	$pdf->SetXY(20, 10); $pdf->Cell(190, 5,utf8_decode( 'Contraloría del Estado Monagas'), 0, 1, 'L');
	$pdf->SetXY(20, 15); $pdf->Cell(190, 5, utf8_decode('División de Correspondencia y Mensajería'), 0, 1, 'L');	
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(190, 10,utf8_decode('Maestro Tipo Correspondencia'), 0, 1, 'C');	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(25, 5);
	$pdf->Cell(20, 5, 'COD. DOCUMENTO', 1, 0, 'C', 1);
	$pdf->Cell(30, 5, 'DESCRIPCION', 1, 0, 'C', 1);
	$pdf->Cell(25, 5, 'USO INTERNO', 1, 0, 'C', 1);
	$pdf->Cell(25, 5, 'USO EXTERNO', 1, 0, 'C', 1);
	$pdf->Cell(25, 5, 'PROC. EXTERNA ', 1, 0, 'C', 1);
	$pdf->Cell(20, 5, 'ESTADO ', 1, 1, 'C', 1);
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf=new PDF_MC_Table();
$pdf->Open();
Cabecera($pdf);
//	Cuerpo
$sql="SELECT Cod_TipoDocumento, 
             Descripcion, 
			 FlagUsoInterno, 
			 FlagUsoExterno, 
			 FlagProcedenciaExterna,
			 Estado,
			 UltimaFechaModif 
	    FROM cp_tipocorrespondencia 
	ORDER BY Cod_TipoDocumento";
$query=mysql_query($sql) or die ($sql.mysql_error());
while ($field=mysql_fetch_array($query)) {
    if($field[2]=='1') $f2='Si'; else $f2='';
	if($field[3]=='1') $f3='Si'; else $f3='';
	if($field[4]=='1') $f4='Si'; else $f4='';
	if($field[5]=='A'){$estado='Activo';}else{$estado='Inactivo';}
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(20, 30, 25, 25, 25, 20));
	$pdf->SetAligns(array('C', 'L', 'C', 'C', 'C', 'C'));
	$pdf->Cell(25, 5); $pdf->Row(array($field[0], $field[1], $f2, $f3, $f4, $estado));
	$y=$pdf->GetY(); if ($y==270) Cabecera($pdf);
}
//---------------------------------------------------

$pdf->Output();
?>  
