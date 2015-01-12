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
	$pdf->Cell(190, 10, 'Inventario Actual', 0, 1, 'C');	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(15, 5, 'ITEM', 1, 0, 'C', 1);
	$pdf->Cell(90, 5, 'DESCRIPCION', 1, 0, 'C', 1);
	$pdf->Cell(10, 5, 'UND.', 1, 0, 'C', 1);
	$pdf->Cell(15, 5, 'COD. INTERNO.', 1, 0, 'C', 1);
	$pdf->Cell(25, 5, 'STOCK ACTUAL.', 1, 0, 'C', 1);
	$pdf->Cell(10, 5, 'LINEA.', 1, 0, 'C', 1);
	$pdf->Cell(10, 5, 'FAMILIA.', 1, 0, 'C', 1);
	$pdf->Cell(15, 5, 'SUB-FAMILIA.', 1, 1, 'C', 1);





//Item 	Descripción 	Und. 	Cod. Interno 	Stock Actual 	Linea 	Familia 	Sub-Familia
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf=new PDF_MC_Table();
$pdf->Open();
Cabecera($pdf);
//Cuerpo

$filtro=$_REQUEST['filtro'];
$sql = "SELECT
				i.Descripcion AS NomItem,
				i.CodInterno,
				i.CodTipoItem,
				i.CodUnidad,
				i.CodLinea,
				i.CodFamilia,
				i.CodSubFamilia,
				a.Descripcion AS NomAlmacen,
				ti.Descripcion AS NomTipoItem,
				ia.CodItem,
				ia.CodAlmacen,
				iav.StockActual
			FROM
				lg_itemmast i
				LEFT JOIN lg_itemalmacen ia ON (i.CodItem = ia.CodItem)
				LEFT JOIN lg_tipoitem ti ON (i.CodTipoItem = ti.CodTipoItem)
				LEFT JOIN lg_almacenmast a ON (ia.CodAlmacen = a.CodAlmacen)
				LEFT JOIN lg_itemalmaceninv iav ON (ia.CodAlmacen = iav.CodAlmacen AND ia.CodItem = iav.CodItem)
			WHERE ia.CodItem <>''
			ORDER BY ia.CodItem ASC";



$query=mysql_query($sql) or die ($sql.mysql_error());
while ($field=mysql_fetch_array($query)) {
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	//$pdf->SetXY(20, 15);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(15, 90, 10, 15, 25, 10, 10, 15));
	$pdf->SetAligns(array('C', 'L', 'C','C', 'R', 'C','C', 'C'));
	$pdf->Row(array($field['CodItem'],utf8_decode($field['NomItem']),$field['CodUnidad'],$field['CodInterno'],$field['StockActual'],$field['CodLinea'],$field['CodFamilia'],$field['CodSubFamilia']));
	$y=$pdf->GetY(); if ($y==270) Cabecera($pdf);
}
//---------------------------------------------------

$pdf->Output();
?>
