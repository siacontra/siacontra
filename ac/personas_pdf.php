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
	$pdf->Cell(190, 10, 'Lista de Personas', 0, 1, 'C');	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(20, 5, 'PERSONA', 1, 0, 'C', 1);
	$pdf->Cell(85, 5, 'NOMBRE DE BUSQUEDA', 1, 0, 'C', 1);
	$pdf->Cell(10, 5, 'CLI', 1, 0, 'C', 1);
	$pdf->Cell(10, 5, 'PRO', 1, 0, 'C', 1);
	$pdf->Cell(10, 5, 'EMP', 1, 0, 'C', 1);
	$pdf->Cell(10, 5, 'OTR', 1, 0, 'C', 1);
	$pdf->Cell(20, 5, 'DOCUMENTO', 1, 0, 'C', 1);
	$pdf->Cell(25, 5, 'DOC. FISCAL', 1, 1, 'C', 1);
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf=new PDF_MC_Table();
$pdf->Open();
Cabecera($pdf);
//	Cuerpo
$query=mysql_query("SELECT CodPersona, Busqueda, EsCliente, EsProveedor, EsEmpleado, EsOtros, Ndocumento, DocFiscal FROM mastpersonas") or die ($sql.mysql_error());
while ($field=mysql_fetch_array($query)) {
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(20, 85, 10, 10, 10, 10, 20, 25));
	$pdf->SetAligns(array('C', 'L', 'C', 'C', 'C', 'C', 'L', 'L'));
	if ($field[2]=="S") $cli="*"; else $cli="";
	if ($field[3]=="S") $pro="*"; else $pro="";
	if ($field[4]=="S") $emp="*"; else $emp="";
	if ($field[5]=="S") $otr="*"; else $otr="";
	$pdf->Row(array($field[0], $field[1], $cli, $pro, $emp, $otr, $field[6], $field[7]));
	$y=$pdf->GetY(); if ($y==270) Cabecera($pdf);
}
//---------------------------------------------------

$pdf->Output();
?>  
