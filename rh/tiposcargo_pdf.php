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
	$pdf->Image('../imagenes/logos/contraloria.jpg', 13, 10, 20, 20);	
	$pdf->SetFont('Arial', 'B', 5);
	//$nombre_organismo=utf8_decode( 'Contralor�a del Estado Delta Amacuro');
	//$pdf->SetXY(20, 10); $pdf->Cell(190, 5,$nombre_organismo, 0, 1, 'L');
	$texto1=utf8_decode('DIRECCION DE RECURSOS HUMANOS');
	$pdf->SetXY(10, 27); $pdf->Cell(190, 5, $texto1, 0, 1, 'L');	
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(190, 10, 'Maestro de Tipos de Cargo', 0, 1, 'C');	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(20, 5, 'CODIGO', 1, 0, 'C', 1);
	$pdf->Cell(170, 5, 'DESCRIPCION', 1, 1, 'C', 1);
}
//---------------------------------------------------

//---------------------------------------------------
//	Creaci�n del objeto de la clase heredada
$pdf=new PDF_MC_Table();
$pdf->Open();
Cabecera($pdf);
//	Cuerpo
$query=mysql_query("SELECT * FROM rh_tipocargo ORDER BY CodTipoCargo") or die ($sql.mysql_error());
while ($field=mysql_fetch_array($query)) {
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(20, 170));
	$pdf->SetAligns(array('C', 'L'));
	$pdf->Row(array($field[0], $field[1]));
	$y=$pdf->GetY(); if ($y==270) Cabecera($pdf);
}
//---------------------------------------------------

$pdf->Output();
?>
