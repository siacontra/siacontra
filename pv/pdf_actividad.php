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
	$pdf->SetXY(20, 10); $pdf->Cell(190,5,utf8_decode( 'Contraloria del Estado Monagas'), 0, 1, 'L');
	$pdf->SetXY(20, 15); $pdf->Cell(190,5,utf8_decode('Dirección de Administración y Servicios'), 0, 1, 'L');	
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(190, 10, 'Maestro de Actividad', 0, 1, 'C');	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(15, 5);
	$pdf->Cell(20, 5, 'PROYECTO', 1, 0, 'C', 1);
	$pdf->Cell(20, 5, 'ACTIVIDAD', 1, 0, 'C', 1);
	$pdf->Cell(120, 5, 'DESCRIPCION', 1, 1, 'C', 1);

}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf=new PDF_MC_Table();
$pdf->Open();
Cabecera($pdf);
//	Cuerpo
$query=mysql_query("SELECT cod_proyecto, cod_actividad, descp_actividad FROM pv_actividad1 ORDER BY id_proyecto, id_actividad" ) or die ($sql.mysql_error());
while ($field=mysql_fetch_array($query)) {
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(20, 20, 120));
	$pdf->Cell(15, 5);
	$pdf->SetAligns(array('C','C','L'));
	$pdf->Row(array($field[0], $field[1], $field[2]));
}
//---------------------------------------------------

$pdf->Output();
?>  
