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
	$pdf->SetXY(20, 15); $pdf->Cell(190, 5, utf8_decode('División de Recursos Humanos'), 0, 1, 'L');	
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(190, 10, 'Maestro de Misceláneos', 0, 1, 'C');	
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf=new PDF_MC_Table();
$pdf->Open();
Cabecera($pdf);
//	Cuerpo
$query=mysql_query("SELECT mastmiscelaneoscab.CodAplicacion, mastaplicaciones.Descripcion, mastmiscelaneoscab.CodMaestro, mastmiscelaneoscab.Descripcion FROM mastmiscelaneoscab, mastaplicaciones WHERE (mastmiscelaneoscab.CodAplicacion=mastaplicaciones.CodAplicacion) ORDER BY mastaplicaciones.CodAplicacion, mastmiscelaneoscab.CodMaestro") or die ($sql.mysql_error());
while ($field=mysql_fetch_array($query)) {
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);	$pdf->SetFillColor(250, 250, 250);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(30, 5, 'Aplicación:', 0, 0, 'R', 1);
	$pdf->Cell(160, 5, $field[1], 0, 1, 'L', 1);
	$pdf->Cell(30, 5, 'Maestro:', 0, 0, 'R', 1);
	$pdf->Cell(160, 5, $field[2], 0, 1, 'L', 1);
	$pdf->Cell(30, 5, 'Descripción:', 0, 0, 'R', 1);
	$pdf->Cell(160, 5, $field[3], 0, 1, 'L', 1);
	$pdf->Ln(2);	
	//	DETALLES
	$query1=mysql_query("SELECT CodDetalle, Descripcion FROM mastmiscelaneosdet WHERE CodMaestro='".$field[2]."' AND CodAplicacion='".$field[0]."'") or die ($sql.mysql_error());
	$rows1=mysql_num_rows($query1);
	if ($rows1!=0) {
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetTextColor(255, 255, 255);	$pdf->SetFillColor(200, 200, 200);
		$pdf->SetFont('Arial', 'B', 6);
		$pdf->Cell(30, 5, 'NIVEL', 1, 0, 'C', 1);
		$pdf->Cell(160, 5, 'DETALLE', 1, 1, 'C', 1);
		while ($field1=mysql_fetch_array($query1)) {
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 6);
			$pdf->SetWidths(array(30, 160));
			$pdf->SetAligns(array('C', 'L'));
			$pdf->Row(array($field1[0], $field1[1]));
			$y=$pdf->GetY(); if ($y==270) Cabecera($pdf);
		}
		$pdf->Ln(10);
	}
}
//---------------------------------------------------

$pdf->Output();
?>
