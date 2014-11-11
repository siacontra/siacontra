<?php

session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<?php

define('FPDF_FONTPATH','font/');
require('mc_table.php');
require('fphp_funciones_nomina.php');
connect();

//---------------------------------------------------
//	Imprime la cabedera del documento
function Cabecera($pdf) {
	$pdf->AddPage();
	$pdf->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY(20, 10); $pdf->Cell(190, 5, ('Contraloría del Estado Delta Amacuro'), 0, 1, 'L');
	$pdf->SetXY(20, 15); $pdf->Cell(190, 5, ('Dirección de Recursos Humanos'), 0, 1, 'L');	
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(190, 10, 'Tipos de Proceso', 0, 1, 'C');
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(25, 5); 
	$pdf->Cell(15, 5, 'Tipo', 1, 0, 'C', 1);
	$pdf->Cell(80, 5, ('Descripción'), 1, 0, 'C', 1);
	$pdf->Cell(20, 5, 'Adelanto', 1, 0, 'C', 1);
	$pdf->Cell(20, 5, 'Estado', 1, 1, 'C', 1);
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
ob_end_clean();
$pdf=new PDF_MC_Table();
$pdf->Open();
Cabecera($pdf);
//	Cuerpo
$query=mysql_query("SELECT * FROM pr_tipoproceso") or die ($sql.mysql_error());
while ($field=mysql_fetch_array($query)) {
	if ($field['FlagAdelanto']=="S") $flag="X"; else $flag="";
	if ($field['Estado']=="A") $status="Activo"; else $status="Inactivo";
	//	----------------------------------------------
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(15, 80, 20, 20));
	$pdf->SetAligns(array('C', 'L', 'C', 'C'));
	$pdf->Cell(25, 5); $pdf->Row(array($field['CodTipoProceso'], utf8_decode($field['Descripcion']), $flag, $status));
}
//---------------------------------------------------

$pdf->Output();
?>  
