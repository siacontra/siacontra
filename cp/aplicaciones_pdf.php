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
	$pdf->SetXY(20, 10); $pdf->Cell(190, 5,utf8_decode( 'Contralor�a del Estado Monagas'), 0, 1, 'L');
	$pdf->SetXY(20, 15); $pdf->Cell(190, 5, utf8_decode('Direcci�n de Recursos Humanos'), 0, 1, 'L');	
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(190, 10, 'Maestro de Aplicaciones', 0, 1, 'C');	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(30, 5, 'APLICACION', 1, 0, 'C', 1);
	$pdf->Cell(80, 5, 'DESCRIPCION', 1, 0, 'C', 1);
	$pdf->Cell(30, 5, 'PERIODO CONT.', 1, 0, 'C', 1);
	$pdf->Cell(30, 5, 'PREF. VOUCHER', 1, 0, 'C', 1);
	$pdf->Cell(20, 5, 'ESTADO', 1, 1, 'C', 1);
}
//---------------------------------------------------

//---------------------------------------------------
//	Creaci�n del objeto de la clase heredada
$pdf=new PDF_MC_Table();
$pdf->Open();
Cabecera($pdf);
//	Cuerpo
$query=mysql_query("SELECT * FROM mastaplicaciones") or die ($sql.mysql_error());
while ($field=mysql_fetch_array($query)) {
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(30, 80, 30, 30, 20));
	$pdf->SetAligns(array('C', 'L', 'C', 'C', 'C'));
	$pdf->Row(array($field[0], $field[1], $field[2], $field[3], $field[4]));
}
//---------------------------------------------------

$pdf->Output();
?>  
