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
	$pdf->SetXY(20, 10); $pdf->Cell(190, 5,( 'Contraloría del Estado Delta Amacuro'), 0, 1, 'L');
	$pdf->SetXY(20, 15); $pdf->Cell(190, 5, ('Dirección de Recursos Humanos'), 0, 1, 'L');	
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(190, 10, 'Lista de Postulantes', 0, 1, 'C');	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(30, 5, 'CODIGO', 1, 0, 'C', 1);
	$pdf->Cell(30, 5, 'EXPEDIENTE', 1, 0, 'C', 1);
	$pdf->Cell(50, 5, 'NOMBRE COMPLETO', 1, 0, 'C', 1);
	$pdf->Cell(40, 5, 'FECHA DE REGISTRO', 1, 0, 'C', 1);
	$pdf->Cell(20, 5, 'SEXO', 1, 0, 'C', 1);
	$pdf->Cell(20, 5, 'ESTADO', 1, 1, 'C', 1);
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf=new PDF_MC_Table();
$pdf->Open();
Cabecera($pdf);
//	Cuerpo
$query=mysql_query("SELECT * FROM rh_postulantes") or die ($sql.mysql_error());
while ($field=mysql_fetch_array($query)) {
	list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaRegistro']); $fregistro=$d."-".$m."-".$a;
	if ($field['Estado']=="P") $status="POSTULANTE";
	elseif ($field['Estado']=="A") $status="ACEPTADO";
	elseif ($field['Estado']=="C") $status="CONTRATADO";
	//	------------------------------------------------
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(30, 30, 50, 40, 20, 20));
	$pdf->SetAligns(array('C', 'C', 'L', 'C', 'C', 'C'));
	$pdf->Row(array($field['Postulante'], $field['Expediente'], $field['Apellido1']." ".$field['Apellido2'].", ".$field['Nombres'], $fregistro, $field['sexo'], $status));
}
//---------------------------------------------------

$pdf->Output();
?>  
