<?php

session_start();

if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");

?>

<?php

define('FPDF_FONTPATH','font/');

require('mc_table.php');

require('fphp.php');

include("../clases/MySQL.php");



include("../comunes/objConexion.php");

ob_end_clean();




//---------------------------------------------------

//	Imprime la cabedera del documento

function Cabecera($pdf) {

	$pdf->AddPage();

	$pdf->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	

	$pdf->SetFont('Arial', 'B', 8);

	$pdf->SetXY(20, 10); $pdf->Cell(190, 5,utf8_decode( 'Contraloría del estado Monagas'), 0, 1, 'L');

	$pdf->SetXY(20, 15); $pdf->Cell(190, 5, utf8_decode('Dirección de Administración y Presupuesto'), 0, 1, 'L');	

	$pdf->SetFont('Arial', 'B', 10);

	$pdf->Cell(190, 10, 'CLASIFICADOR PRESUPUESTARIO', 0, 1, 'C');	

	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);

	$pdf->SetFont('Arial', 'B', 6);

	$pdf->Cell(30, 5, utf8_decode('CÓDIGO'), 1, 0, 'C', 1);

	$pdf->Cell(150, 5, utf8_decode('DESCRIPCIÓN'), 1, 0, 'C', 1);	

}



//---------------------------------------------------







//---------------------------------------------------

//	Creación del objeto de la clase heredada

$pdf=new PDF_MC_Table();

$pdf->Open();

Cabecera($pdf);

//	Cuerpo





$query="SELECT * FROM `pv_partida`";



		$objConexion->ejecutarQuery($query);

$resp = $objConexion->getMatrizCompleta();

	$pdf->Row(array('', ''));// para que no se salga de la tabla

for($i=0; $i<count($resp);$i++) {

	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);

	$pdf->SetFont('Arial', '', 6);

	$pdf->SetWidths(array(30, 150));

	$pdf->SetAligns(array('C', 'L'));

	$pdf->Row(array($resp[$i]['cod_partida'], $resp[$i]['denominacion']));

	/*$y=$pdf->GetY();

	if ($y>>270) Cabecera($pdf);*/

}

//---------------------------------------------------



$pdf->Output();



?> 

