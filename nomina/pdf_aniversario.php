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
		if ($i<$DiasMes[$fmes]) $filtro.=" me.Fingreso LIKE '%-".$dia."' OR";
		else $filtro.=" me.Fingreso LIKE '%-".$dia."'";
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
	$pdf->SetXY(20, 10); $pdf->Cell(190, 5, utf8_decode('Contraloria del Estado Monagas'), 0, 1, 'L');
	$pdf->SetXY(20, 15); $pdf->Cell(190, 5, utf8_decode('Direccion de Recursos Humanos'), 0, 1, 'L');
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(190, 10, 'Personal de Aniversario', 0, 1, 'C');	
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetWidths(array(20, 90, 20, 15));
	$pdf->SetAligns(array('C', 'L', 'C', 'C', 'C'));
	$pdf->Cell(20, 5); $pdf->Row(array('CODIGO', 'TRABAJADOR', 'FECHA INGRESO', 'AÑOS'));
	$y=$pdf->GetY();
	$pdf->SetDrawColor(0, 0, 0); $pdf->Rect(27, $y, 155, 0.2);
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf=new PDF_MC_Table();
$pdf->Open();
Cabecera($pdf);
//	Cuerpo
$sql="SELECT me.CodEmpleado, m.NomCompleto, me.Fingreso, me.CodTipoTrabajador, SUBSTRING(me.Fingreso, 6, 2) AS MesIngreso FROM mastpersonas m INNER JOIN mastempleado me ON (m.CodPersona=me.CodPersona) $filtro ORDER BY MesIngreso";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
for ($i=1; $i<=$rows; $i++) {
	$field=mysql_fetch_array($query);
	list($a, $m, $d)=SPLIT( '[/.-]', $field['Fingreso']); $Fnacimiento=$d."/".$m."/".$a; $mes=$m;
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
			$pdf->Cell(20, 5); $pdf->Row(array(utf8_decode('Numero de Trabajadores'), $con, ''));
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
	$pdf->SetWidths(array(20, 90, 20, 15));
	$pdf->SetAligns(array('C', 'L', 'C', 'C'));
	$pdf->Cell(20, 5); $pdf->Row(array($field['CodEmpleado'], utf8_decode($field['NomCompleto']), $Fnacimiento, $edad));
	$con++;
	if ($i==$rows) {
		$pdf->Ln(2);
		$pdf->SetFont('Arial', 'BI', 8);
		$pdf->SetWidths(array(50, 10));
		$pdf->SetAligns(array('C', 'C'));
		$pdf->Cell(20, 5); $pdf->Row(array(utf8_decode('Numero de Trabajadores'), $con, ''));			
	}
}
//---------------------------------------------------

$pdf->Output();
?>  
