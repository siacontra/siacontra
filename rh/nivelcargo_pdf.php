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
	$pdf->SetXY(20, 10); $pdf->Cell(190, 5,utf8_decode( 'Contraloría del Estado Delta Amacuro'), 0, 1, 'L');
	$pdf->SetXY(20, 15); $pdf->Cell(190, 5, utf8_decode('Dirección de Recursos Humanos'), 0, 1, 'L');	
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(190, 10, 'Maestro de Niveles de Cargo', 0, 1, 'C');
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(30, 5, 'NIVEL', 1, 0, 'C', 1);
	$pdf->Cell(160, 5, 'DESCRIPCION', 1, 1, 'C', 1);
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf=new PDF_MC_Table();
$pdf->Open();
Cabecera($pdf);
//	Cuerpo
$query=mysql_query("SELECT rh_nivelclasecargo.CodNivelClase, rh_nivelclasecargo.NivelClase, rh_tipocargo.TipCargo FROM rh_nivelclasecargo, rh_tipocargo WHERE rh_nivelclasecargo.CodTipoCargo=rh_tipocargo.CodTipoCargo ORDER BY rh_tipocargo.CodTipoCargo, rh_nivelclasecargo.CodNivelClase") or die ($sql.mysql_error());
while ($field=mysql_fetch_array($query)) {
	if ($niv!=$field[2]) {
		$niv=$field[2];
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(190, 1, '', 0, 1, 'L');
		$pdf->Cell(190, 5, $field[2], 0, 1, 'L', 1);
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
