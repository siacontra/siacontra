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
	$pdf->SetXY(20, 10); $pdf->Cell(190, 5,utf8_decode( 'Contraloría del estado Monagas'), 0, 1, 'L');
	$pdf->SetXY(20, 15); $pdf->Cell(190, 5, utf8_decode('División de Correspondencia y Mensajería'), 0, 1, 'L');	
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(190, 10,utf8_decode('Maestro Particular'), 0, 1, 'C');	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(20, 5, 'NRO. CEDULA', 1, 0, 'C', 1);
	$pdf->Cell(60, 5, 'NOMBRE Y APELLIDO', 1, 0, 'C', 1);
	$pdf->Cell(60, 5, 'DIRECCION', 1, 0, 'C', 1);
	$pdf->Cell(20, 5, 'TELEFONO', 1, 0, 'C', 1);
	$pdf->Cell(30, 5, 'CARGO', 1, 1, 'C', 1);
	
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf=new PDF_MC_Table();
$pdf->Open();
Cabecera($pdf);
//	Cuerpo
/*$sql="SELECT  
             Cedula, 
			 Nombre,
			 Direccion,
			 Telefono, 
			 Cargo
	    FROM 
		     cp_particular
		WHERE 
		     CodParticular = '".$registro."'
	ORDER BY 
	        CodParticular";*/

$sql="SELECT  
             Cedula, 
			 Nombre,
			 Direccion,
			 Telefono, 
			 Cargo
	    FROM 
		     cp_particular
	ORDER BY 
	        CodParticular";			
$query=mysql_query($sql) or die ($sql.mysql_error());
while ($field=mysql_fetch_array($query)) {
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(20, 60, 60, 20, 30));
	$pdf->SetAligns(array('C', 'L', 'L', 'C', 'L'));
	$pdf->Row(array($field[0], $field[1], $field[2], $field[3], $field[4]));
	$y=$pdf->GetY(); if ($y==270) Cabecera($pdf);
}
//---------------------------------------------------
$pdf->Output();
?>  
