<?php
define('FPDF_FONTPATH','font/');
require('mc_table3.php');
require('fphp.php');
connect();
//---------------------------------------------------
$filtro=strtr($filtro, "*", "'");
$filtro=strtr($filtro, ";", "%");
if ($ordenar == "") $orderby = "ORDER BY me.CodEmpleado";
elseif($ordenar == "mp.Ndocumento") $orderby = "ORDER BY LENGTH(mp.Ndocumento), mp.Ndocumento";
else $orderby = "ORDER BY $ordenar";
//---------------------------------------------------

$sql = "SELECT b.descripcioninsthcm, count(a.codBeneficio) as numeroCasos, sum(a.montoTotal) as total

FROM
   rh_beneficio as a
        JOIN rh_institucionhcm as b on
               a.idInstHcm = b.idInstHcm

group by a.idInstHcm";
//---------------------------------------------------
//	Imprime la cabedera del documento
function Cabecera($pdf) {
	$pdf->AddPage();
	$pdf->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY(20, 10); $pdf->Cell(190, 5,utf8_decode( 'Contraloría del Estado Monagas'), 0, 1, 'L');
	$pdf->SetXY(20, 15); $pdf->Cell(190, 5, utf8_decode('Dirección de Recursos Humanos'), 0, 1, 'L');
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(190, 10, utf8_decode('Consumo por Institución'), 0, 1, 'C');	
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->SetWidths(array(15, 135, 40));
	$pdf->SetAligns(array('C', 'L', 'R'));
	$pdf->Row(array(utf8_decode('Nro Caso'), utf8_decode('Descripción'),  'Monto'));
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
	$y=$pdf->GetY();
	$pdf->Rect(5, $y, 200, 0.1, "DF");
	$pdf->Ln(2);
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
	
	if($field['tipoSolicitud']=='R')
		$solicituD = 'REEMBOLSO';
	if($field['tipoSolicitud']=='E')
		$solicituD = 'EMISIÓN';
		
	  if($field['estatus']=='PE'){$est='Preparado';}
	  if($field['estatus']=='RV'){$est='Revisado';}
	  if($field['estatus']=='AP'){$est='Aprobado';}
	  if($field['estatus']=='GE'){$est='Generado';}
	  if($field['estatus']=='AN'){$est='Anulado';}
	
		
	//if ($field['estatus']=="A") $status="Activo"; else $status="Inactivo";
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(15, 135, 40));
	$pdf->SetAligns(array('C', 'L', 'R'));
	
	$pdf->Row(array($field['numeroCasos'], utf8_decode($field['descripcioninsthcm']),  number_format($field['total'],2,',','.')));
}
//---------------------------------------------------

$pdf->Output();
?>  
