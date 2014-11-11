<?php
define('FPDF_FONTPATH','font/');
require('mc_table.php');
require('fphp.php');
require('../nomina/funciones_globales_nomina.php');
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
/*	$filtro.=" AND (";
	for ($i=1; $i<=$DiasMes[$fmes]; $i++) {
		if ($i<10) $d="0".$i; else $d=$i;
		$dia=$fmes."-".$d;
		if ($i<$DiasMes[$fmes]) $filtro.=" me.Fingreso LIKE '%-".$dia."' OR";
		else $filtro.=" me.Fingreso LIKE '%-".$dia."'";
	}*/
	//$filtro.=")";
}
//---------------------------------------------------

//---------------------------------------------------
//	Imprime la cabedera del documento
function Cabecera($pdf) {
	$pdf->AddPage();
	$pdf->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY(20, 10); $pdf->Cell(190, 5,utf8_decode( 'Contraloria del estado Monagas'), 0, 1, 'L');
	$pdf->SetXY(20, 15); $pdf->Cell(190, 5, utf8_decode('Direccion de Recursos Humanos'), 0, 1, 'L');
	
	$pdf->SetXY(20, 15);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(190, 15, ' Calculo de Años de Servicio del  Personal ', 0, 1, 'C');	
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->SetWidths(array(20, 50, 20, 20,20,15,20,15));
	$pdf->SetAligns(array('C', 'L', 'C', 'C', 'C', 'C', 'C','C','C'));
	
	
	$pdf->Cell(1, 5); $pdf->Row(array('CODIGO', 'TRABAJADOR', 'FECHA  INGRESO', 'FECHA ANIVERSARIO','FECHA CORTE Adm. Publica','AÑOS EN CEM', 'TOTAL AÑOS Adm. Publica' , 'FALTAN (dias)'));
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
$sql="SELECT me.CodEmpleado, m.NomCompleto, me.Fingreso,m.CodPersona, me.CodTipoTrabajador, SUBSTRING(me.Fingreso, 6, 2) AS MesIngreso FROM mastpersonas m INNER JOIN mastempleado me ON (m.CodPersona=me.CodPersona) $filtro ORDER BY MesIngreso";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);


$empleados = array();  

			$pdf->Ln(5);		

			$pdf->SetFont('Arial', 'B', 8);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetWidths(array(50));
			$pdf->SetAligns(array('L'));
			$pdf->Cell(15, 5); $pdf->Row(array($NomMes[$fmes]));
			$pdf->Cell(15, 5); $pdf->Row(array('Fecha de Calculo: '.date('d-m-Y')));
			$pdf->Ln(2);

  

for ($i=1; $i<=$rows; $i++) {
	$field=mysql_fetch_array($query);
	list($a, $m, $d)=SPLIT( '[/.-]', $field['Fingreso']); $Fnacimiento=$d."/".$m."/".$a; $mes=$m;
	
		
    $_ARGS['TRABAJADOR'] =$field['CodPersona'];
    $_ARGS ['PERIODO'] = '2014-'.$m;
    $_ARGS['HASTA'] = '2014-'.$m.'-'.$d;
    $_ARGS['DESDE'] = $field['Fingreso'];
    $_ARGS['FECHA_ANIVERSARIO'] =  '2014-'.$m.'-'.$d;
    $_ARGS['FECHA_INGRESO'] = $field['Fingreso'];
    $_ARGS['FECHA_CORTE'] = '2014-'.$m.'-'.$d;
    $_ARGS['ANIOS_CONTRALORIA'] = $field['Fingreso'];
    $_ARGS['TOTAL_ANIOS'] = $field['Fingreso'];
	
	
	list($a, $m, $d)=getEdad($Fnacimiento); $edad=$a;
	
	
	/*
	//	----------------------------------
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	//	----------------------------------
	if ($mesActual!=$mes) {
		if ($i!=1) {
			$pdf->Ln(2);
			$pdf->SetFont('Arial', 'BI', 8);
			$pdf->SetWidths(array(50, 10));
			$pdf->SetAligns(array('C', 'C'));
			$pdf->Cell(20, 5); $pdf->Row(array('Número de Trabajadores', $con, ''));
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
	}*/
	//	----------------------------------
	
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(20, 50, 20, 20,20,15,15,15));
	$pdf->SetAligns(array('C', 'L', 'C', 'C', 'C', 'C', 'C','C','C'));
	
		
	//$pdf->Cell(20, 5); $pdf->Row(array($field['CodEmpleado'], utf8_decode($field['NomCompleto']), $Fnacimiento, $edad));
	list($anios, $meses, $dias) = TIEMPO_DE_SERVICIO(formatFechaDMA($_ARGS['DESDE']), formatFechaDMA(date('Y-m-d')));

    // CALCULO  DE ANIOS EN LA CONTRALORIA
		$totalAnio = 0;
		$sumaAnio = 0;//$_ARGS['TRABAJADOR'];
		$sumaMes = 0;
		$totalMese = 0;
		$sumaDias = 0;
		$totalDias = 0;
		$auxAnioMes = 0;
		$auxAnioDia = 0;
		
		$sumaMes += $meses;
		$sumaDias += $dias;
		$sumaAnio+=$anios;

		$auxAnioDia = $sumaDias/30;

		$sumaMes += $auxAnioDia;

		$auxAnioMes = $sumaMes/12;

		 $_ARGS['ANIOS_CONTRALORIA'] = round ($sumaAnio+$auxAnioMes,2);
		
	 
     ////////////////////////////////////////////////////
     // CALCULO EL TOTAL DE ANIOS EN LA ADMINISTRACION PUBLICA
	  $_ARGS['HASTA'] = date('Y-m-d');
	  $_ARGS['TOTAL_ANIOS'] = round (TOTAL_ANIOS_SERVICIO($_ARGS),2);
	 
	 
	 if  (ANIO_SERVICIO_OTRA_INSTITUCION2 ($_ARGS) > 0)
	 {
	 /////////////////////////////////////////////////////////////
	// CALCULO LA FECHA LA NUEVA FECHA DE CORTE
	 
	   //calculo la cantidad de dias desde : 
	   
	  $n = $_ARGS['TOTAL_ANIOS'];
	  $decimales = floor($n);      // 1
      $total_dias = intval((1 - ($n - $decimales)) *12 *30); // .25


      //sumo la cantidad de dias desde
        $fecha = date_create(date('Y-m-d'));
     
   //  if ($_ARGS['TOTAL_ANIOS']<=0) {$fecha = date_create( $_ARGS['FECHA_INGRESO']);   $total_dias =365;}
   
     date_add($fecha, date_interval_create_from_date_string(intval($total_dias ).' days'));
     $_ARGS['FECHA_CORTE'] = date_format($fecha, 'Y-m-d');
	
    } else {
		
		
		   $total_dias=0;
		   	 /////////////////////////////////////////////////////////////
	// CALCULO LA FECHA LA NUEVA FECHA DE CORTE
	 
	   //calculo la cantidad de dias desde : 
	   
	  $n = $_ARGS['ANIOS_CONTRALORIA'];
	  $decimales = floor($n);      // 1
      $total_dias = intval((1 - ($n - $decimales)) *12 *30); // .25


      //sumo la cantidad de dias desde
     //   $fecha = date_create(date('Y-m-d'));
     
   //  if ($_ARGS['TOTAL_ANIOS']<=0) {$fecha = date_create( $_ARGS['FECHA_INGRESO']);   $total_dias =365;}
   
    // date_add($fecha, date_interval_create_from_date_string(intval($total_dias ).' days'));
    // $_ARGS['FECHA_CORTE'] = date_format($fecha, 'Y-m-d');
	//  $_ARGS['FECHA_CORTE'] 
		
		
		}
		
		list($a, $varM, $d)=SPLIT( '[/.-]', $_ARGS['FECHA_CORTE']);
		
		if ($varM==$fmes) {
			

		
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(20, 50, 20, 20,20,15,20,15));
	$pdf->SetAligns(array('C', 'L', 'C', 'C', 'C', 'C', 'C','C','C'));	
			
	$pdf->Cell(1, 5); $pdf->Row(array($field['CodEmpleado'], 
	                            utf8_decode($field['NomCompleto']),
	                            $_ARGS['FECHA_INGRESO'],
	                            $_ARGS['FECHA_ANIVERSARIO'], 
	                            $_ARGS['FECHA_CORTE'] ,
	                            $_ARGS['ANIOS_CONTRALORIA'] ,
	                            $_ARGS['TOTAL_ANIOS'],intval($total_dias)));
	                            $con++;
	                            }
	
	
	
	
	if ($i==$rows) {
		$pdf->Ln(2);
		$pdf->SetFont('Arial', 'BI', 8);
		$pdf->SetWidths(array(50, 10));
		$pdf->SetAligns(array('C', 'C'));
		$pdf->Cell(10, 5); $pdf->Row(array('Número de Trabajadores', $con, ''));			
	}
}
//---------------------------------------------------

$pdf->Output();
?>  
