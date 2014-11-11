<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<?php
define('FPDF_FONTPATH','font/');
require('mc_table2.php');
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
	
	//	AQUI SE COLOCA LA FECHA DE IMPRESION Y LA PAGINA.......
	
	$pdf->SetFont('Arial', 'BU', 12);
	$pdf->Cell(190, 15, 'Resultado de Encuesta', 0, 1, 'C');
	
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf=new PDF_MC_Table();
$pdf->Open();
Cabecera($pdf);
//	Segunda cabecera
$query=mysql_query("SELECT PeriodoContable, Titulo, Fecha, Observaciones, Muestra FROM rh_encuestas WHERE Secuencia='".$registro."'") or die (mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) {
	$field=mysql_fetch_array($query);
	list($a, $m, $d)=SPLIT( '[/.-]', $field['Fecha']); $fecha=$d."-".$m."-".$a;
	
	$pdf->SetFont('Arial', '', 10);
	$pdf->Cell(15, 8, 'Titulo:', 0, 0, 'L');
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(175, 8, $field['Titulo'], 0, 1, 'L');
	
	$pdf->SetFont('Arial', '', 10);
	$pdf->Cell(20, 8, 'Período: ', 0, 0, 'L');
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(30, 8, $field['PeriodoContable'], 0, 0, 'L');
	
	$pdf->SetFont('Arial', '', 10);
	$pdf->Cell(15, 8, 'Fecha:', 0, 0, 'L');
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(30, 8, $fecha, 0, 0, 'L');
	
	$pdf->SetFont('Arial', '', 10);
	$pdf->Cell(20, 8, 'Muestras:', 0, 0, 'L');
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(30, 8, $field['Muestra'], 0, 1, 'L');
	
	$pdf->SetFont('Arial', '', 10);
	$pdf->Cell(15, 5, 'Observaciones:', 0, 1, 'L');
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->MultiCell(175, 5, $field['Observaciones'], 0, 'L');
	$pdf->Ln(10);
	
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(140, 5, 'PREGUNTA', 1, 0, 'L', 1);
	$pdf->Cell(50, 5, 'RESULTADO', 1, 1, 'R', 1);
}
//	Cuerpo
$query=mysql_query("SELECT rh_encuesta_detalle.Pregunta, rh_encuesta_preguntas.Descripcion, mastmiscelaneosdet.Descripcion AS Area, AVG(rh_encuesta_sujeto.Valor) AS Resultado FROM rh_encuesta_detalle, rh_encuesta_preguntas, mastmiscelaneosdet, rh_encuesta_sujeto WHERE (rh_encuesta_detalle.Secuencia='".$registro."') AND (rh_encuesta_detalle.Pregunta=rh_encuesta_preguntas.Pregunta) AND (rh_encuesta_preguntas.Area=mastmiscelaneosdet.CodDetalle AND mastmiscelaneosdet.CodMaestro='AREACLIMA') AND (rh_encuesta_detalle.Secuencia=rh_encuesta_sujeto.Secuencia AND rh_encuesta_sujeto.Pregunta=rh_encuesta_preguntas.Pregunta) GROUP BY rh_encuesta_detalle.Pregunta ORDER BY mastmiscelaneosdet.Descripcion, rh_encuesta_detalle.Pregunta") or die (mysql_error());
while ($field=mysql_fetch_array($query)) {
	$resultado=number_format($field['Resultado'], 2, ',', '.');
	if ($area!=$field['Area']) {
		$area=$field['Area'];
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 12);		
		$pdf->Cell(190, 10, $field['Area'], 0, 1, 'L', 1);
	}
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 10);
	$pdf->SetWidths(array(140, 50));
	$pdf->SetAligns(array('L', 'R'));
	$pdf->Row(array($field['Descripcion'], $resultado));
	$y=$pdf->GetY(); if ($y==270) Cabecera($pdf);
}
//---------------------------------------------------
$pdf->Output();
?>  
