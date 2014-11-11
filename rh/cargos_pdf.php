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
	$pdf->Cell(190, 10, 'Maestro de Cargos', 0, 1, 'C');	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(25, 5, 'TIPO', 1, 0, 'C', 1);
	$pdf->Cell(15, 5, 'NIVEL', 1, 0, 'C', 1);
	$pdf->Cell(30, 5, 'SERIE OCUPACIONAL', 1, 0, 'C', 1);
	$pdf->Cell(15, 5, 'CODIGO', 1, 0, 'C', 1);
	$pdf->Cell(25, 5, 'CLASIFICACION', 1, 0, 'C', 1);
	$pdf->Cell(65, 5, 'DESCRIPCION', 1, 0, 'C', 1);		
	$pdf->Cell(15, 5, 'ESTADO', 1, 1, 'C', 1);
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf=new PDF_MC_Table();
$pdf->Open();
Cabecera($pdf);
//	Cuerpo
$query=mysql_query("SELECT rh_puestos.CodCargo, rh_puestos.DescripCargo, rh_puestos.Estado, rh_tipocargo.TipCargo, rh_nivelclasecargo.NivelClase, rh_serieocupacional.SerieOcup, rh_puestos.CodGrupOcup, rh_puestos.CodSerieOcup, rh_puestos.CodTipoCargo, rh_puestos.CodNivelClase, rh_puestos.CodDesc FROM rh_puestos, rh_tipocargo, rh_nivelclasecargo, rh_serieocupacional WHERE (rh_puestos.CodTipoCargo=rh_tipocargo.CodTipoCargo AND rh_puestos.CodNivelClase=rh_nivelclasecargo.CodNivelClase AND rh_puestos.CodTipoCargo=rh_nivelclasecargo.CodTipoCargo AND rh_puestos.CodSerieOcup=rh_serieocupacional.CodSerieOcup) ORDER BY rh_puestos.CodGrupOcup, rh_puestos.CodSerieOcup, rh_puestos.CodTipoCargo, rh_puestos.CodNivelClase") or die ($sql.mysql_error());
while ($field=mysql_fetch_array($query)) {
	$clasificacion=$field['CodDesc'];
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(25, 15, 30, 15, 25, 65, 15));
	$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'L', 'C'));
	$pdf->Row(array($field[3], $field[4], $field[5], $field[0], $clasificacion, $field[1], $field[2]));
	$y=$pdf->GetY(); if ($y==270) Cabecera($pdf);
}
//---------------------------------------------------

$pdf->Output();
?>