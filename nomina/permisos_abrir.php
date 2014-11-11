<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<?php
define('FPDF_FONTPATH','font/');
require('mc_table2.php');
require('fphp.php');
connect();
$fecha_impresion=date("d-m-Y");

//---------------------------------------------------
//	Imprime la cabedera del documento
function Cabecera($pdf) {
	$pdf->AddPage();
	//	------------
	$pdf->Rect(10, 10, 40, 35);
	$pdf->Image('../imagenes/logos/logo_reporte_permisos.jpg', 15, 12, 30, 30);
	
	$pdf->Rect(50, 10, 115, 25);
	$pdf->SetXY(50, 15); $pdf->SetFont('Arial', 'B', 14); $pdf->Cell(115, 14, 'DIRECCION DE RECURSOS HUMANOS', 0, 1, 'C'); 
	
	$pdf->Rect(50, 35, 115, 10);
	$pdf->SetXY(50, 36); $pdf->SetFont('Arial', 'B', 12); $pdf->Cell(115, 5, 'FORMATO ', 0, 1, 'C'); 
	$pdf->SetXY(50, 40); $pdf->SetFont('Arial', 'B', 12); $pdf->Cell(115, 5, 'SOLICITUD DE PERMISO O LICENCIA', 0, 1, 'C'); 
	
	$pdf->Rect(165, 10, 40, 8);
	$pdf->SetXY(165, 11); $pdf->SetFont('Arial', 'B', 9); $pdf->MultiCell(40, 3, 'CODIGO: FOR-DRRHH-011', 0, 'C'); 
	
	$pdf->Rect(165, 18, 40, 6);
	$pdf->SetXY(166, 18); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(40, 5, 'REVISION:', 0, 1, 'C');

	$pdf->Rect(165, 24, 20, 6);
	$pdf->SetXY(166, 24); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(18, 5, ('Nº'), 0, 1, 'C');
	
	$pdf->Rect(185, 24, 20, 6);
	$pdf->SetXY(186, 24); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(18, 5, 'FECHA', 0, 1, 'C');
	
	$pdf->Rect(165, 30, 20, 5);
	$pdf->SetXY(166, 30); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(18, 5, '1', 0, 1, 'C');
	
	$pdf->Rect(185, 30, 20, 5);
	$pdf->SetXY(186, 30); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(18, 5, '01-2009', 0, 1, 'C');
	
	$pdf->Rect(165, 35, 40, 10);
	$pdf->SetXY(166, 36); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(38, 5, 'PAGINA', 0, 1, 'C');
	$pdf->SetXY(166, 40); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(38, 5, '1 DE 1', 0, 1, 'C');
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf=new PDF_MC_Table('P','mm','Letter');
$pdf->Open();
$pdf->SetLeftMargin(10);
Cabecera($pdf);

//	Cuerpo
$sql = "SELECT 
			mastpersonas.NomCompleto, 
			mastpersonas.Ndocumento, 
			rh_puestos.DescripCargo, 
			mastempleado.CodEmpleado, 
			rh_permisos.FechaIngreso, 
			rh_permisos.PeriodoContable, 
			rh_permisos.FechaDesde, 
			rh_permisos.FechaHasta, 
			rh_permisos.HoraDesde, 
			rh_permisos.HoraHasta, 
			rh_permisos.TotalHoras, 
			rh_permisos.TotalMinutos, 
			rh_permisos.TotalFecha, 
			rh_permisos.TotalTiempo, 
			rh_permisos.ObsMotivo, 
			rh_permisos.FechaAprobado, 
			rh_permisos.ObsAprobado, 
			rh_permisos.Estado, 
			(SELECT Dependencia FROM mastdependencias WHERE CodDependencia=mastempleado.CodDependencia) AS Dependencia, 
			(SELECT Division FROM mastdivisiones WHERE CodDivision=mastempleado.CodDivision) AS Division, 
			(SELECT Descripcion FROM mastmiscelaneosdet WHERE CodDetalle=rh_permisos.TipoFalta AND CodMaestro='TIPOFALTAS') AS TipoFalta, 
			(SELECT NomCompleto FROM mastpersonas WHERE CodPersona=rh_permisos.Aprobador) AS NomAprobador, 
			(SELECT DescripCargo FROM rh_puestos WHERE CodCargo=(SELECT CodCargo FROM mastempleado WHERE CodPersona=rh_permisos.Aprobador)) AS CargoAprobador, 
			rh_permisos.TotalHoras, 
			rh_permisos.TotalDias, 
			rh_permisos.FlagJustificativo 
		FROM 
			rh_permisos, 
			mastpersonas, 
			mastempleado, 
			rh_puestos 
		WHERE 
			rh_permisos.CodPersona=mastpersonas.CodPersona AND 
			rh_permisos.CodPersona=mastempleado.CodPersona AND 
			mastempleado.CodCargo=rh_puestos.CodCargo AND 
			rh_permisos.CodPermiso='".$registro."'";
$query = mysql_query($sql) or die ($sql.mysql_error());
$rows = mysql_num_rows($query);
if ($rows!=0) $field=mysql_fetch_array($query);
list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaIngreso']); $fingreso=$d."/".$m."/".$a;
list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaAprobado']); $faprobado=$d."/".$m."/".$a;
list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaDesde']); $fdesde=$d."/".$m."/".$a;
list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaHasta']); $fhasta=$d."/".$m."/".$a;
list($h, $m, $s)=SPLIT('[:]', $field['HoraDesde']); if ($h>12) { $h=$h-12; $merdesde="PM"; if ($h<10) $h="0$h"; } else { $merdesde="AM"; } $hdesde=$h.":".$m;
list($h, $m, $s)=SPLIT('[:]', $field['HoraHasta']); if ($h>12) { $h=$h-12; $merhasta="PM"; if ($h<10) $h="0$h"; } else { $merhasta="AM"; } $hhasta=$h.":".$m;
$desde=$fdesde." ".$hdesde;
$hasta=$fhasta." ".$hhasta;

$h1=5; $h2=8; $h3=12; $h4=17;

$y=47;
$pdf->Rect(10, $y, 195, $h1);
$pdf->SetXY(11, $y); $pdf->SetFont('Arial', 'B', 10); $pdf->Cell(195, 5, 'DATOS DEL FUNCIONARIO O TRABAJADOR', 0, 1, 'C');
$y=52;
$pdf->Rect(10, $y, 40, $h1); $pdf->Rect(50, $y, 155, $h1);
$pdf->SetXY(11, $y); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(38, 5, 'CEDULA:', 0, 1, 'C');
$pdf->SetXY(51, $y); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(153, 5, 'APELLIDOS Y NOMBRES:', 0, 1, 'L');
$pdf->Rect(10, $y+5, 40, $h1); $pdf->Rect(50, $y+5, 155, $h1);
$pdf->SetXY(11, $y+5); $pdf->SetFont('Arial', '', 9); $pdf->Cell(38, 5, $field['Ndocumento'], 0, 1, 'C');
$pdf->SetXY(51, $y+5); $pdf->SetFont('Arial', '', 9); $pdf->Cell(153, 5, ($field['NomCompleto']), 0, 1, 'L');
$y=62;
$pdf->Rect(10, $y, 155, $h1); $pdf->Rect(165, $y, 40, $h1);
$pdf->SetXY(11, $y); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(153, 5, 'DENOMINACION DEL CARGO:', 0, 1, 'L');
$pdf->SetXY(166, $y); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(38, 5, 'CODIGO:', 0, 1, 'C');
$pdf->Rect(10, $y+5, 155, $h2); $pdf->Rect(165, $y+5, 40, $h2);
$pdf->SetXY(11, $y+5); $pdf->SetFont('Arial', '', 9); $pdf->MultiCell(153, 5, ($field['DescripCargo']), 0, 'L');
$pdf->SetXY(166, $y+5); $pdf->SetFont('Arial', '', 9); $pdf->Cell(38, 5, ($field['CodEmpleado']), 0, 1, 'C');
$y=75;
$pdf->Rect(10, $y, 195, $h1); $pdf->Rect(110, $y, 95, $h1);
$pdf->SetXY(11, $y); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(98, 5, 'DEPENDENCIA:', 0, 1, 'L');
$pdf->Rect(10, $y+5, 195, $h3);
$pdf->SetXY(11, $y+6); $pdf->SetFont('Arial', '', 9); $pdf->MultiCell(193, 4, ($field['Dependencia']), 0, 'L');
$y=92;
$pdf->Rect(10, $y, 100, $h1); $pdf->Rect(110, $y, 95, $h1);
$pdf->SetXY(11, $y); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(100, 5, 'FECHA:', 0, 1, 'C');
$pdf->SetXY(111, $y); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(95, 5, 'FIRMA:', 0, 1, 'C');
$pdf->Rect(10, $y+5, 100, $h2); $pdf->Rect(110, $y+5, 95, $h2);
$pdf->SetXY(11, $y+7); $pdf->SetFont('Arial', '', 9); $pdf->MultiCell(100, 4, $fingreso, 0, 'C');
$pdf->SetXY(111, $y+5); $pdf->SetFont('Arial', '', 9); $pdf->MultiCell(95, 5, '', 0, 'L');

//--
$y=105;
$pdf->Rect(10, $y, 195, $h1);
$pdf->SetXY(11, $y); $pdf->SetFont('Arial', 'B', 10); $pdf->Cell(195, 5, 'DATOS DEL PERMISO', 0, 1, 'C');
$y=110;
$pdf->Rect(10, $y, 50, $h1); $pdf->Rect(60, $y, 145, $h1);
$pdf->SetXY(11, $y); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(50, 5, 'TIPO:', 0, 1, 'C');
$pdf->SetXY(61, $y); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(140, 5, 'MOTIVO DEL PERMISO:', 0, 1, 'C');
$pdf->Rect(10, $y+5, 50, $h4); $pdf->Rect(60, $y+5, 145, $h4);
$pdf->SetXY(11, $y+11); $pdf->SetFont('Arial', '', 9); $pdf->MultiCell(50, 4, ($field['TipoFalta']), 0, 'C');
$pdf->SetXY(61, $y+6); $pdf->SetFont('Arial', '', 9); $pdf->MultiCell(143, 4, ($field['ObsMotivo']), 0, 'L');

//--
$y=132;
$pdf->Rect(10, $y, 195, $h1);
$pdf->SetXY(11, $y); $pdf->SetFont('Arial', 'B', 10); $pdf->Cell(195, 5, 'DURACION DEL PERMISO', 0, 1, 'C');
$pdf->Rect(10, $y+5, 60, $h1); $pdf->Rect(70, $y+5, 85, $h1); $pdf->Rect(155, $y+5, 50, $h1);
$pdf->Rect(10, $y+10, 20, $h1); $pdf->Rect(30, $y+10, 20, $h1); $pdf->Rect(50, $y+10, 20, $h1);
$pdf->Rect(70, $y+10, 30, $h1); $pdf->Rect(100, $y+10, 30, $h1); $pdf->Rect(130, $y+10, 25, $h1);
$pdf->Rect(155, $y+10, 25, $h1); $pdf->Rect(180, $y+10, 25, $h1);

$pdf->SetXY(11, $y+5); $pdf->SetFont('Arial', 'B', 10); $pdf->Cell(58, 5, 'HORAS', 0, 1, 'C');
$pdf->SetXY(71, $y+5); $pdf->SetFont('Arial', 'B', 10); $pdf->Cell(83, 5, 'DIAS', 0, 1, 'C');
$pdf->SetXY(156, $y+5); $pdf->SetFont('Arial', 'B', 10); $pdf->Cell(48, 5, 'TIEMPO TOTAL', 0, 1, 'C');

$pdf->SetXY(11, $y+10); $pdf->SetFont('Arial', 'B', 10); $pdf->Cell(18, 5, 'DESDE', 0, 1, 'C');
$pdf->SetXY(31, $y+10); $pdf->SetFont('Arial', 'B', 10); $pdf->Cell(18, 5, 'HASTA', 0, 1, 'C');
$pdf->SetXY(51, $y+10); $pdf->SetFont('Arial', 'B', 10); $pdf->Cell(18, 5, 'TOTAL', 0, 1, 'C');

$pdf->SetXY(71, $y+10); $pdf->SetFont('Arial', 'B', 10); $pdf->Cell(28, 5, 'DESDE', 0, 1, 'C');
$pdf->SetXY(101, $y+10); $pdf->SetFont('Arial', 'B', 10); $pdf->Cell(28, 5, 'HASTA', 0, 1, 'C');
$pdf->SetXY(131, $y+10); $pdf->SetFont('Arial', 'B', 10); $pdf->Cell(23, 5, 'TOTAL', 0, 1, 'C');

$pdf->SetXY(157, $y+10); $pdf->SetFont('Arial', 'B', 10); $pdf->Cell(23, 5, 'DIAS', 0, 1, 'C');
$pdf->SetXY(183, $y+10); $pdf->SetFont('Arial', 'B', 10); $pdf->Cell(23, 5, 'HORAS', 0, 1, 'C');

$pdf->Rect(10, $y+15, 20, $h1); $pdf->Rect(30, $y+15, 20, $h1); $pdf->Rect(50, $y+15, 20, $h1);
$pdf->Rect(70, $y+15, 30, $h1); $pdf->Rect(100, $y+15, 30, $h1); $pdf->Rect(130, $y+15, 25, $h1);
$pdf->Rect(155, $y+15, 25, $h1); $pdf->Rect(180, $y+15, 25, $h1);

$pdf->SetXY(11, $y+15); $pdf->SetFont('Arial', '', 9); $pdf->Cell(18, 5, $hdesde.' '.$merdesde , 0, 1, 'C');
$pdf->SetXY(31, $y+15); $pdf->SetFont('Arial', '', 9); $pdf->Cell(18, 5, $hhasta.' '.$merhasta, 0, 1, 'C');
$pdf->SetXY(51, $y+15); $pdf->SetFont('Arial', '', 9); $pdf->Cell(18, 5, $field['TotalTiempo'], 0, 1, 'C');

$pdf->SetXY(71, $y+15); $pdf->SetFont('Arial', '', 9); $pdf->Cell(28, 5, $fdesde, 0, 1, 'C');
$pdf->SetXY(101, $y+15); $pdf->SetFont('Arial', '', 9); $pdf->Cell(28, 5, $fhasta, 0, 1, 'C');
$pdf->SetXY(131, $y+15); $pdf->SetFont('Arial', '', 9); $pdf->Cell(23, 5, $field['TotalFecha'], 0, 1, 'C');

$pdf->SetXY(157, $y+15); $pdf->SetFont('Arial', '', 9); $pdf->Cell(23, 5, $field['TotalDias'], 0, 1, 'C');
$pdf->SetXY(183, $y+15); $pdf->SetFont('Arial', '', 9); $pdf->Cell(23, 5, $field['TotalHoras'].':'.$field['TotalMinutos'], 0, 1, 'C');

//--
$y=152;
$pdf->Rect(10, $y, 195, $h1);
$pdf->SetXY(11, $y); $pdf->SetFont('Arial', 'B', 10); $pdf->Cell(193, 5, 'AUTORIZADO POR', 0, 1, 'C');
$y=157;
$pdf->Rect(10, $y, 100, $h1); $pdf->Rect(110, $y, 95, $h1);
$pdf->SetXY(11, $y); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(98, 5, 'APELLIDOS Y NOMBRES:', 0, 1, 'L');
$pdf->SetXY(111, $y); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(93, 5, 'CARGO:', 0, 1, 'L');
$pdf->Rect(10, $y+5, 100, $h3); $pdf->Rect(110, $y+5, 95, $h3);
$pdf->SetXY(11, $y+7); $pdf->SetFont('Arial', '', 9); $pdf->MultiCell(98, 4, ($field['NomAprobador']), 0, 'L');
$pdf->SetXY(111, $y+7); $pdf->SetFont('Arial', '', 9); $pdf->MultiCell(93, 5, ($field['CargoAprobador']), 0, 'L');
$y=174;
$pdf->Rect(10, $y, 100, $h1); $pdf->Rect(110, $y, 95, $h1);
$pdf->SetXY(11, $y); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(98, 5, 'FIRMA Y SELLO:', 0, 1, 'C');
$pdf->SetXY(111, $y); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(93, 5, 'FECHA:', 0, 1, 'C');
$pdf->Rect(10, $y+5, 100, $h2); $pdf->Rect(110, $y+5, 95, $h2);
$pdf->SetXY(11, $y+7); $pdf->SetFont('Arial', '', 9); $pdf->MultiCell(98, 4, '', 0, 'L');
$pdf->SetXY(111, $y+7); $pdf->SetFont('Arial', '', 9); $pdf->MultiCell(93, 5, $faprobado, 0, 'C');

//--
$y=187;
$pdf->Rect(10, $y, 195, $h1);
$pdf->SetXY(11, $y); $pdf->SetFont('Arial', 'B', 10); $pdf->Cell(193, 5, 'OBSERVACIONES', 0, 1, 'C');
$pdf->Rect(10, $y+5, 195, $h3);
$pdf->SetXY(11, $y+5); $pdf->SetFont('Arial', 'B', 10); $pdf->MultiCell(193, 4, ($field['ObsAprobado']), 0, 'L');

//--
$y=204;
$pdf->Rect(10, $y, 195, $h1);
$pdf->SetXY(11, $y); $pdf->SetFont('Arial', 'B', 10); $pdf->Cell(193, 5, 'VERIFICADO POR', 0, 1, 'C');
$y=209;
$pdf->Rect(10, $y, 100, $h1); $pdf->Rect(110, $y, 95, $h1);
$pdf->SetXY(11, $y); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(98, 5, 'APELLIDOS Y NOMBRES:', 0, 1, 'L');
$pdf->SetXY(111, $y); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(93, 5, 'CARGO:', 0, 1, 'L');
$pdf->Rect(10, $y+5, 100, $h3); $pdf->Rect(110, $y+5, 95, $h3);
$pdf->SetXY(11, $y+7); $pdf->SetFont('Arial', '', 9); $pdf->MultiCell(98, 4, 'ABREU MARIN MARLIN DEL VALLE', 0, 'L');
$pdf->SetXY(111, $y+7); $pdf->SetFont('Arial', '', 9); $pdf->MultiCell(93, 5, 'JEFA DIVISION DE RECUROS HUMANOS', 0, 'L');
$y=226;
$pdf->Rect(10, $y, 100, $h1); $pdf->Rect(110, $y, 95, $h1);
$pdf->SetXY(11, $y); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(98, 5, 'FIRMA Y SELLO:', 0, 1, 'C');
$pdf->SetXY(111, $y); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(93, 5, 'FECHA:', 0, 1, 'C');
$pdf->Rect(10, $y+5, 100, $h2); $pdf->Rect(110, $y+5, 95, $h2);
$pdf->SetXY(11, $y+5); $pdf->SetFont('Arial', '', 9); $pdf->MultiCell(98, 4, '', 0, 'L');
$pdf->SetXY(111, $y+8); $pdf->SetFont('Arial', '', 9); $pdf->MultiCell(93, 5, $faprobado, 0, 'C');

//--
if ($field['FlagJustificativo'] == "S") $justificativo = "JUSTIFICADO";  else $justificativo = "INJUSTIFICADO";

$y=239;
$pdf->Rect(10, $y, 195, $h1);
$pdf->SetXY(11, $y); $pdf->SetFont('Arial', 'B', 10); $pdf->Cell(195, 5, 'VERIFICACION POSTERIOR', 0, 1, 'C');
$y=244;
$pdf->Rect(10, $y, 145, $h1); $pdf->Rect(155, $y, 50, $h1);
$pdf->SetXY(11, $y); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(143, 5, 'OBSERVACIONES:', 0, 1, 'C');
$pdf->SetXY(155, $y); $pdf->SetFont('Arial', 'B', 9); $pdf->Cell(48, 5, 'ESTADO:', 0, 1, 'C');
$pdf->Rect(10, $y+5, 145, $h3); $pdf->Rect(155, $y+5, 50, $h3);
$pdf->SetXY(11, $y); $pdf->SetFont('Arial', '', 9); $pdf->MultiCell(143, 4, '', 0, 'C');
$pdf->SetXY(155, $y+9); $pdf->SetFont('Arial', '', 9); $pdf->MultiCell(48, 4, $justificativo, 0, 'L');
//---------------------------------------------------
if ($field['Estado'] != "A") {
	$pdf->SetXY(20, 170); 
	$pdf->SetFont('Arial', 'B', 50);
	$pdf->SetTextColor(240, 240, 240); 	
	$pdf->Cell(175, 8, 'NO APROBADO', 0, 1, 'C');
}

$pdf->Output();
?>