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
	$pdf->SetXY(20, 10); $pdf->Cell(270, 5,( 'Contraloría del Estado Delta Amacuro'), 0, 1, 'L');
	$pdf->SetXY(20, 15); $pdf->Cell(270, 5, ('Dirección de Recursos Humanos'), 0, 1, 'L');	
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(280, 10, 'Permisos del Empleado', 0, 1, 'C');	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(200, 200, 200); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(20, 5, 'EMPLEADO', 1, 0, 'C', 1);
	$pdf->Cell(50, 5, 'NOMBRE', 1, 0, 'C', 1);
	$pdf->Cell(40, 5, 'TIPO DE PERMISO', 1, 0, 'C', 1);
	$pdf->Cell(40, 5, 'TIPO DE FALTA', 1, 0, 'C', 1);
	$pdf->Cell(25, 5, 'FECHA DEL PERMISO', 1, 0, 'C', 1);
	$pdf->Cell(25, 5, 'ESTADO', 1, 0, 'C', 1);
	$pdf->Cell(80, 5, 'OBSERVACIONES', 1, 1, 'C', 1);
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf=new PDF_MC_Table('L');
$pdf->Open();
Cabecera($pdf);
//	Cuerpo
$query=mysql_query("SELECT mastempleado.CodEmpleado, (SELECT mastpersonas.NomCompleto FROM mastpersonas WHERE mastpersonas.CodPersona=rh_permisos.CodPersona) AS NombrePersona, (SELECT mastmiscelaneosdet.Descripcion FROM mastmiscelaneosdet WHERE mastmiscelaneosdet.CodMaestro='PERMISOS' AND mastmiscelaneosdet.CodDetalle=rh_permisos.TipoPermiso) AS TipoPermiso, (SELECT mastmiscelaneosdet.Descripcion FROM mastmiscelaneosdet WHERE mastmiscelaneosdet.CodMaestro='TIPOFALTAS' AND mastmiscelaneosdet.CodDetalle=rh_permisos.TipoFalta) AS TipoFalta, rh_permisos.FechaDesde, rh_permisos.FechaHasta, rh_permisos.HoraDesde, rh_permisos.HoraHasta, rh_permisos.Estado, rh_permisos.ObsMotivo FROM rh_permisos, mastempleado WHERE rh_permisos.CodPersona=mastempleado.CodPersona") or die ($sql.mysql_error());
while ($field=mysql_fetch_array($query)) {
	list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaDesde']); $fdesde=$d."-".$m."-".$a;
	list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaHasta']); $fhasta=$d."-".$m."-".$a;
	list($h, $m, $s)=SPLIT('[:]', $field['HoraDesde']); $hdesde=$h.":".$m;
	list($h, $m, $s)=SPLIT('[:]', $field['HoraHasta']); $hhasta=$h.":".$m;
	$desde=$fdesde." ".$hdesde;
	$hasta=$fhasta." ".$hhasta;
	if ($field["Estado"]=="P") $status="PENDIENTE";
	if ($field["Estado"]=="A") $status="APROBADO";
	if ($field["Estado"]=="N") $status="ANULADO";	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(20, 50, 40, 40, 25, 25, 80));
	$pdf->SetAligns(array('C', 'L', 'L', 'L', 'C', 'C', 'L'));
	$pdf->Row(array($field["CodEmpleado"], $field["NombrePersona"], $field["TipoPermiso"], $field["TipoFalta"], $desde.' '.$hasta, $status, $field["ObsMotivo"]));
	$y=$pdf->GetY(); if ($y==270) Cabecera($pdf);
}
//---------------------------------------------------

$pdf->Output();
?>