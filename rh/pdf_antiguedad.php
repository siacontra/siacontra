<?php
define('FPDF_FONTPATH','font/');
require('mc_table.php');
require('fphp.php');
connect();
//---------------------------------------------------
$NomMes['01']="Enero"; $NomMes['02']="Febrero"; $NomMes['03']="Marzo"; $NomMes['04']="Abril"; $NomMes['05']="Mayo"; $NomMes['06']="Junio";
$NomMes['07']="Julio"; $NomMes['08']="Agosto"; $NomMes['09']="Septiembre"; $NomMes['10']="Octubre"; $NomMes['11']="Noviembre"; $NomMes['12']="Diciembre";
$DiasMes['01']=31; $DiasMes['02']=29; $DiasMes['03']=31; $DiasMes['04']=30; $DiasMes['05']=31; $DiasMes['06']=30;
$DiasMes['07']=31; $DiasMes['08']=31; $DiasMes['09']=30; $DiasMes['10']=31; $DiasMes['11']=30; $DiasMes['12']=31;
//---------------------------------------------------
$filtro="WHERE me.CodOrganismo='".$forganismo."'";
if ($chkttrabajador=="1") $filtro.=" AND me.CodTipoTrabajador='".$fttrabajador."'";
if ($chkmes=="1") {
	$filtro.=" AND (";
	for ($i=1; $i<=$DiasMes[$fmes]; $i++) {
		if ($i<10) $d="0".$i; else $d=$i;
		$dia=$fmes."-".$d;
		if ($i<$DiasMes[$fmes]) $filtro.=" m.Fnacimiento LIKE '%-".$dia."' OR";
		else $filtro.=" m.Fnacimiento LIKE '%-".$dia."'";
	}
	$filtro.=")";
}
//---------------------------------------------------

//---------------------------------------------------
//	Imprime la cabedera del documento
function Cabecera($pdf) {
	$pdf->AddPage();
	$pdf->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY(20, 10); $pdf->Cell(190, 5, utf8_decode('Contraloría del estado Monagas'), 0, 1, 'L');
	$pdf->SetXY(20, 15); $pdf->Cell(190, 5, utf8_decode('Dirección de Recursos Humanos'), 0, 1, 'L');
	$pdf->SetFont('Arial', 'B', 10);
	//$pdf->Cell(190, 10, utf8_decode('Personal de Cumpleaños'), 0, 1, 'C');	
	$pdf->Ln(10);
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetWidths(array(20, 20, 90, 20, 15));
	$pdf->SetAligns(array('C', 'R', 'L', 'C', 'C', 'C'));
	$pdf->Cell(20, 5); $pdf->Row(array('CODIGO', 'CEDULA', 'TRABAJADOR', 'FECHA NACIMIENTO', 'EDAD'));
	$y=$pdf->GetY();
	$pdf->SetDrawColor(0, 0, 0); $pdf->Rect(27, $y, 175, 0.2);
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf=new PDF_MC_Table();
$pdf->Open();
Cabecera($pdf);
//	Cuerpo
$sql="SELECT me.CodEmpleado, m.NomCompleto, m.Ndocumento, m.Fnacimiento, me.CodTipoTrabajador, SUBSTRING(m.Fnacimiento, 6, 2) AS MesNac FROM mastpersonas m INNER JOIN mastempleado me ON (m.CodPersona=me.CodPersona) $filtro ORDER BY MesNac";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
for ($i=1; $i<=$rows; $i++) {
	$field=mysql_fetch_array($query);
	list($a, $m, $d)=SPLIT( '[/.-]', $field['Fnacimiento']); $Fnacimiento=$d."/".$m."/".$a; $mes=$m;
	list($a, $m, $d)=getEdad($Fnacimiento); $edad=$a;
	//	----------------------------------
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	//	----------------------------------
	if ($mesActual!=$mes) {
		if ($i!=1) {
			$pdf->Ln(2);
			$pdf->SetFont('Arial', 'BI', 8);
			$pdf->SetWidths(array(50, 10));
			$pdf->SetAligns(array('C', 'C'));
			$pdf->Cell(20, 5); $pdf->Row(array(utf8_decode('Número de Trabajadores'), $con, ''));
			$con=0;
		}
		$pdf->Ln(5);		
		$mesActual=$mes;
		$nombre=$NomMes[$mesActual];
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->SetWidths(array(50));
		$pdf->SetAligns(array('L'));
		$pdf->Cell(15, 5); $pdf->Row(array($nombre));
		$pdf->Ln(2);
	}
	//	----------------------------------
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetWidths(array(20, 20, 90, 20, 15));
	$pdf->SetAligns(array('C', 'R', 'L', 'C', 'C'));
	$pdf->Cell(20, 5); $pdf->Row(array($field['CodEmpleado'], $field['Ndocumento'], utf8_decode($field['NomCompleto']), $Fnacimiento, $edad));
	$con++;
	if ($i==$rows) {
		$pdf->Ln(2);
		$pdf->SetFont('Arial', 'BI', 8);
		$pdf->SetWidths(array(50, 10));
		$pdf->SetAligns(array('C', 'C'));
		$pdf->Cell(20, 5); $pdf->Row(array(utf8_decode('Número de Trabajadores'), $con, ''));			
	}
}
//---------------------------------------------------

$pdf->Output();
?>  
