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
	$pdf->SetXY(20, 10); $pdf->Cell(190, 5,utf8_decode( 'Contralor�a del Estado Sucre'), 0, 1, 'L');
	$pdf->SetXY(20, 15); $pdf->Cell(190, 5, utf8_decode('Divisi�n de Recursos Humanos'), 0, 1, 'L');	
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(190, 10, 'Maestro de Municipios', 0, 1, 'C');
}
//---------------------------------------------------

//---------------------------------------------------
//	Creaci�n del objeto de la clase heredada
$pdf=new PDF_MC_Table();
$pdf->Open();
Cabecera($pdf);
//	Cuerpo
$query=mysql_query("SELECT mastmunicipios.CodMunicipio, mastmunicipios.Municipio, mastestados.Estado, mastpaises.Pais, mastmunicipios.Estado AS Status FROM mastmunicipios, mastestados, mastpaises WHERE mastmunicipios.CodEstado=mastestados.CodEstado AND mastestados.CodPais=mastpaises.CodPais ORDER BY mastpaises.CodPais, mastestados.CodEstado, mastmunicipios.CodMunicipio") or die ($sql.mysql_error());
while ($field=mysql_fetch_array($query)) {
	if ($edo!=$field[2]) {
		$edo=$field[2];
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(190, 1, '', 0, 1, 'L');
		$pdf->Cell(35, 5);
		$pdf->Cell(100, 5, $field[2], 0, 1, 'L', 1);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(35, 5);
		$pdf->Cell(25, 5, 'MUNICIPIO', 1, 0, 'C', 1);
		$pdf->Cell(75, 5, 'DESCRIPCION', 1, 0, 'C', 1);
		$pdf->Cell(20, 5, 'ESTADO', 1, 1, 'C', 1);
	}
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(25, 75, 20));
	$pdf->SetAligns(array('C', 'L', 'C'));
	$pdf->Cell(35, 5); $pdf->Row(array($field[0], $field[1], $field['Status']));
	$y=$pdf->GetY(); if ($y==270) Cabecera($pdf);
}
//---------------------------------------------------

$pdf->Output();
?>  
