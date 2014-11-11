<?php
define('FPDF_FONTPATH','font/');
require('mc_table3.php');
require('fphp.php');
connect();


$sql = "SELECT B.codAyudaE, B.decripcionAyudaE, SUM( A.montoTotal ) as totalMonto
	FROM rh_ayudamedicaespecifica AS B
	JOIN rh_beneficio AS A ON A.codAyudaE = B.codAyudaE
	GROUP BY B.codAyudaE";
//---------------------------------------------------
//	Imprime la cabedera del documento
function Cabecera($pdf) {
	$pdf->AddPage('p','Letter');
	$pdf->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY(20, 10); $pdf->Cell(190, 5,utf8_decode( 'Contraloría del Estado Monagas'), 0, 1, 'L');
	$pdf->SetXY(20, 15); $pdf->Cell(190, 5, utf8_decode('Dirección de Recursos Humanos'), 0, 1, 'L');
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(190, 10, 'Consumo de las Partidas Medicas', 0, 1, 'C');	
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->SetWidths(array(100, 30));
	$pdf->SetAligns(array('L', 'R'));
	$pdf->SetX(40);$pdf->Row(array('Nombre', 'Monto'));
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
	$y=$pdf->GetY();
	$pdf->Rect(40, $y, 130, 0.1, "DF");
	$pdf->SetX(40);$pdf->Ln(2);
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf=new PDF_MC_Table();
$pdf->Open();
$pdf->SetMargins(5, 5, 5);
Cabecera($pdf);
//	Cuerpo
//$sql="SELECT me.CodEmpleado, me.Estado, mp.NomCompleto, mp.Ndocumento, rp.DescripCargo, md.Dependencia FROM mastempleado me INNER JOIN mastpersonas mp ON (me.CodPersona=mp.CodPersona) INNER JOIN rh_puestos rp ON (me.CodCargo=rp.CodCargo) INNER JOIN mastdependencias md ON (me.CodDependencia=md.CodDependencia) WHERE me.CodEmpleado<>'' $filtro $orderby";
$query=mysql_query($sql) or die ($sql.mysql_error());
while ($field=mysql_fetch_array($query)) {
	
	
	
		
	//if ($field['estatus']=="A") $status="Activo"; else $status="Inactivo";
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(100, 30));
	$pdf->SetAligns(array('L', 'R'));
	$pdf->SetX(40);$pdf->Row(array(utf8_decode($field['decripcionAyudaE']),number_format($field['totalMonto'],2,',','.')));
}
//---------------------------------------------------

$pdf->Output();
?>  
