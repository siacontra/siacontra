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
	$pdf->SetXY(20, 10); $pdf->Cell(190, 5, ( 'Contraloría del Estado Monagas'), 0, 1, 'L');
	$pdf->SetXY(20, 15); $pdf->Cell(190, 5, ('Dirección de Recursos Humanos'), 0, 1, 'L');	
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(190, 10, 'Maestro de Conceptos', 0, 1, 'C');	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(20, 5);
	$pdf->Cell(20, 5, utf8_decode('Código'), 1, 0, 'C', 1);
	$pdf->Cell(80, 5, utf8_decode('Descripción'), 1, 0, 'C', 1);
	$pdf->Cell(30, 5, 'Tipo', 1, 0, 'C', 1);
	$pdf->Cell(20, 5, 'Estado', 1, 1, 'C', 1);
}
//---------------------------------------------------

ob_end_clean();
//---------------------------------------------------
//	Creación del objeto de la clase heredada

$pdf=new PDF_MC_Table();
$pdf->Open();
Cabecera($pdf);
//	Cuerpo
$query=mysql_query("SELECT * FROM pr_concepto") or die ($sql.mysql_error());
while ($field=mysql_fetch_array($query)) {
	if ($field['Tipo']=="I") $tipo="Ingresos"; 
	elseif ($field['Tipo']=="D") $tipo="Descuentos";
	elseif ($field['Tipo']=="A") $tipo="Aportes";
	elseif ($field['Tipo']=="P") $tipo="Provisiones";
	if ($field['Estado']=="A") $status="Activo"; else $status="Inactivo";
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(20, 80, 30, 20));
	$pdf->SetAligns(array('C', 'L', 'C', 'C'));
	$pdf->Cell(20, 5);$pdf->Row(array($field['CodConcepto'],utf8_decode($field['Descripcion']), $tipo, $status));
}
//---------------------------------------------------

$pdf->Output();
?>  
