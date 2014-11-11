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
	$pdf->SetXY(20, 10); $pdf->Cell(190, 5,utf8_decode( 'Contraloría del Estado Monagas'), 0, 1, 'L');
	$pdf->SetXY(20, 15); $pdf->Cell(190, 5, utf8_decode('Dirección de Recursos Humanos'), 0, 1, 'L');	
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(190, 10, 'Maestro de Ciudades', 0, 1, 'C');
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf=new PDF_MC_Table();
$pdf->Open();
Cabecera($pdf);
//	Cuerpo
$query=mysql_query("SELECT mastciudades.CodCiudad, mastciudades.Ciudad, mastciudades.CodPostal, mastmunicipios.Municipio, mastestados.Estado, mastpaises.Pais FROM mastciudades, mastmunicipios, mastestados, mastpaises WHERE mastciudades.CodMunicipio=mastmunicipios.CodMunicipio AND mastmunicipios.CodEstado=mastestados.CodEstado AND mastestados.CodPais=mastpaises.CodPais ORDER BY mastpaises.CodPais, mastestados.CodEstado, mastmunicipios.CodMunicipio, mastciudades.CodPostal") or die ($sql.mysql_error());
while ($field=mysql_fetch_array($query)) {
	if ($edo!=$field[4]) {
		$edo=$field[4];
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(190, 1, '', 0, 1, 'L');
		$pdf->Cell(190, 5, $field[4], 0, 1, 'L', 1);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(25, 5, 'CIUDAD', 1, 0, 'C', 1);
		$pdf->Cell(70, 5, 'DESCRIPCION', 1, 0, 'C', 1);
		$pdf->Cell(25, 5, 'COD. POSTAL', 1, 0, 'C', 1);
		$pdf->Cell(70, 5, 'MUNICIPIO', 1, 1, 'C', 1);
	}
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(25, 70, 25, 70));
	$pdf->SetAligns(array('C', 'L', 'C', 'L'));
	$pdf->Row(array($field[0], $field[1], $field[2], $field[3]));
	$y=$pdf->GetY(); if ($y==270) Cabecera($pdf);
}
//---------------------------------------------------

$pdf->Output();
?>  
