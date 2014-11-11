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
	/*$filtro.=" AND (";
	for ($i=1; $i<=$DiasMes[$fmes]; $i++) {
		if ($i<10) $d="0".$i; else $d=$i;
		$dia=$fmes."-".$d;
		if ($i<$DiasMes[$fmes]) $filtro.=" me.Fingreso LIKE '%-".$dia."' OR";
		else $filtro.=" me.Fingreso LIKE '%-".$dia."'";
	}
	$filtro.=")";**/
}
//---------------------------------------------------

//---------------------------------------------------
//	Imprime la cabedera del documento
function Cabecera($pdf) {
	$pdf->AddPage();
	$pdf->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->SetXY(20, 10); $pdf->Cell(190, 5,utf8_decode( 'Contraloria del estado Monagas'), 0, 1, 'L');
	$pdf->SetXY(20, 15); $pdf->Cell(190, 5, utf8_decode('Direccion de Recursos Humanos'), 0, 1, 'L');
	
	
	
	$pdf->SetFont('Arial', 'B', 8);
	
	
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetWidths(array(190));
	$pdf->SetAligns(array('L'));
	$pdf->Cell(15, 5); $pdf->Row(array($NomMes[$fmes]));
	$pdf->Cell(15, 5); $pdf->Row(array('Fecha de Calculo: '.date('d-m-Y')));

	$pdf->SetXY(20, 15);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(230, 15, ' Calculo de Tiempo de Servicio del  Personal ', 0, 1, 'C');	
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetWidths(array(20, 70, 20, 20,20,30,30,30,30));
	$pdf->SetAligns(array('C', 'L', 'C', 'C', 'C', 'C', 'C','C','C'));
	
		      		

	$pdf->Cell(1, 5); $pdf->Row(array(
	'CODIGO', 
	'TRABAJADOR', 
	'Fecha de  Ingreso en CEM', 
	'Primer Aniversario en CEM' ,
	'Fecha Corte Adm. Publica',
	'Tiempo en CEM (Años Meses Dias)',
	'Años Anteriores en la Adm. Publica (Años Meses Dias)' ,
	'Total Tiempo en la Admin . Publica (Años Meses Dias)' 
	//, 'FALTAN (dias)'
	)
	);
	$y=$pdf->GetY();
	$pdf->SetDrawColor(0, 0, 0); $pdf->Rect(27, $y, 230, 0.2);
}
//---------------------------------------------------

//---------------------------------------------------
//	Creación del objeto de la clase heredada
$pdf=new PDF_MC_Table('L');
$pdf->Open();
Cabecera($pdf);
//	Cuerpo
$sql="SELECT me.CodEmpleado, m.NomCompleto, me.Fingreso,m.CodPersona, me.CodTipoTrabajador, SUBSTRING(me.Fingreso, 6, 2) AS MesIngreso FROM mastpersonas m INNER JOIN mastempleado me ON (m.CodPersona=me.CodPersona) $filtro ORDER BY MesIngreso";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);


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

	$pdf->SetFont('Arial', '', 8);
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
$pdf->SetWidths(array(20, 70, 20, 20,20,30,30,30,30));
	$pdf->SetAligns(array('C', 'L', 'C', 'C', 'C', 'C', 'C','C','C'));
	
	
		
	 list($AnioAnt, $MesesAnt, $DiasAnt) = AMD_GOBIERNO($_ARGS);
    
    if ($AnioAnt>=1) {
				//  echo "<br><br>  TRABAJADOR: ".$_ARGS['TRABAJADOR']." ".$AnioAnt."-".$MesesAnt."-".$DiasAnt;
		
		list($AniosCem, $MesesCem, $DiasCem) = TIEMPO_DE_SERVICIO(formatFechaDMA($_ARGS['DESDE']), formatFechaDMA(date('Y-m-d')));		
				
		  $fe_ingreso =  $fecha = date_create(date( $field['Fingreso']));
		  date_add($fe_ingreso , date_interval_create_from_date_string('1 year'));
		  $_ARGS['FECHA_PRIMER_ANIO'] = date_format($fe_ingreso , 'Y-m-d');	


        $fe_corte=    date_create(date($_ARGS['FECHA_PRIMER_ANIO']));
      //$fe_corte2=    date_create(date($_ARGS['FECHA_PRIMER_ANIO']));
      
 
         
      //--------------------------------------
		$_ARGS['FECHA_CORTE'] = date_format($fe_corte , 'Y-m-d');	
		
		
		$_ARGS['ANIOS_AMD'] = $AnioAnt." , " .$MesesAnt." , " .$DiasAnt; 
    } else {
		
		list($AniosCem, $MesesCem, $DiasCem) = TIEMPO_DE_SERVICIO(formatFechaDMA($_ARGS['DESDE']), formatFechaDMA(date('Y-m-d'))); 
		
		 
		$fe_ingreso =   date_create(date( $field['Fingreso']));
		date_add($fe_ingreso , date_interval_create_from_date_string('1 year'));
		$_ARGS['FECHA_PRIMER_ANIO'] = date_format($fe_ingreso , 'Y-m-d');	
		
		$fe_corte=    date_create(date($field['Fingreso']));
		date_add($fe_corte , date_interval_create_from_date_string($AniosCem.' year '.$MesesAnt.' month '.$DiasAnt.' days'));
		$_ARGS['FECHA_CORTE'] = date_format($fe_corte , 'Y-m-d');	
	
	      $_ARGS['ANIOS_AMD'] = "0,0,0 ";
	      
	  //    	list($AniosRest, $MesesRes, $DiasRes) = TIEMPO_DE_SERVICIO( formatFechaDMA($_ARGS['FECHA_CORTE']),formatFechaDMA(date('Y-m-d')));
      //   $_ARGS['FALTAN'] =$AniosRest.",".$MesesRes.",".$DiasRes;  
	}
	
	list($AniosCem, $MesesCem, $DiasCem) = TIEMPO_DE_SERVICIO(formatFechaDMA($_ARGS['DESDE']), formatFechaDMA(date('Y-m-d'))); 
	
	//////////////////////////////////////////////////////////
	//
	$TotalAnios = $AniosCem +$AnioAnt;
	$TotalMeses = $MesesCem +$MesesAnt;
	$TotalDias = $DiasCem +$DiasAnt;
	
	$TotalMeses = $TotalMeses + intval($TotalDias/30);
	$TotalDias = $TotalDias%30;
	 
	$TotalAnios = $TotalAnios + intval($TotalMeses/12);
	$TotalMeses = $TotalMeses%12;
	
	//////////////////////////////////////////////////////////////////////////////
	$aux_fechacorte = new $fe_corte;
	$fe_corte2 = new $fe_corte;
//	  echo "<br> FECHA PRIMER: ". date_format($aux_fechacorte , 'Y-m-d');	
	   $anio_total = $AnioAnt+1;
	   $anio_aux = 0;
      $dias=1;
      // calculo de fecha de corte 
      for ($dias=1;$dias<=360;$dias++)
      {
		  $aux_fechacorte = new $fe_corte;
		 // $aux_fechacorte =  date_create(date($_ARGS['FECHA_PRIMER_ANIO']));
		  
		 // if ($anio_aux!=$anio_total) 
	       //  {
			
			//}

			date_add($aux_fechacorte , date_interval_create_from_date_string($dias.' days'));
			$str_fecorte =date_format($aux_fechacorte , 'Y-m-d');
		    
	        
            list($anio_aux, $mes_aux, $dias_aux) = TIEMPO_DE_SERVICIO(formatFechaDMA(date('Y-m-d')) ,  
                                                                      formatFechaDMA(date('Y-m-d', strtotime($str_fecorte)))
                                                                       );
                                                   
                                                   
			$TotalAnios_aux = $AnioAnt+1 + $anio_aux;
			$TotalMeses_aux = $mes_aux +$MesesAnt;
			$TotalDias_aux =  $dias_aux +$DiasAnt;

			$TotalMeses_aux = $TotalMeses_aux + intval($TotalDias_aux/30);
			$TotalDias_aux = $TotalDias_aux%30;

			$TotalAnios_aux = $TotalAnios_aux + intval($TotalMeses_aux/12);
			$TotalMeses_aux = $TotalMeses_aux%12;                                      
      
       if ($anio_total!=$TotalAnios_aux) 
	         {
			
		/*	echo "<br><br> Cambio de Prima  Dias:".$dias;
			echo "<br>".	 $field['NomCompleto'];
			echo "<br> ANIO AUX:".$anio_aux;
			echo "<br> ANIO TOTAL:".$anio_total;
			echo "<br> TotalDias_aux: ".$TotalDias_aux;
			echo "<br> TotalMeses_aux:".$TotalMeses_aux;
			echo "<br> TotalAnios_aux:".$TotalAnios_aux;
			echo "<br> DMA: ". formatFechaDMA(date('Y-m-d'));	
			echo "<br> DMA: ".formatFechaDMA(date('Y-m-d', strtotime($str_fecorte)));	
			echo "<br> FECHA DE CORTE: ".$_ARGS['FECHA_CORTE'] =formatFechaDMA(date('Y-m-d', strtotime($str_fecorte)));	*/
			break;
	     }
	  
	   /*    
	       if ($anio_aux!=$anio_total) 
	         {
				echo "<br> anio_aux!=anio_total ";
				echo "<br> ANIA AUX:".$anio_aux;
				echo "<br> ANIO TOTAL:".$anio_total;
			}
			 */
			 
			  
         }
     //   echo "<br> ************";
	
	//////////////////////////////////////////////////////////////////////////
		
	//	$fe_corte=    date_create(date($_ARGS['FECHA_PRIMER_ANIO']));
	//	date_add($fe_corte , date_interval_create_from_date_string($AnioAnt.' year '));
	//	$_ARGS['FECHA_CORTE'] = date_format($fe_corte , 'Y-m-d');	
	
           
	/////////////////////////////////////////////////////////
	 
		$_ARGS['TOTAL_ANIOS'] = $TotalAnios." , ".$TotalMeses." , ".$TotalDias;
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetWidths(array(20, 70, 20, 20,20,30,30,30,30));


   $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetAligns(array('C', 'L', 'C', 'C', 'C', 'C', 'C','C','C'));
	
	list($a, $varM, $d)=SPLIT( '[/.-]', $_ARGS['FECHA_CORTE']); 
	list($a, $varM2, $d)=SPLIT( '[/.-]', $_ARGS['FECHA_INGRESO']); 
	//$_ARGS['FECHA_CORTE'] = date('Y')."-".$varM."-".$d ;
	
	//echo "<br> :".$varM." - ".$fmes;
	if ($varM==$fmes || $varM2==$fmes ) {		
	$pdf->Cell(1, 6); $pdf->Row(array($field['CodEmpleado'], 
	                            utf8_decode($field['NomCompleto']),
	                            $_ARGS['FECHA_INGRESO'],
	                            $_ARGS['FECHA_PRIMER_ANIO'], 
	                            $_ARGS['FECHA_CORTE'] ,
	                            $_ARGS['ANIOS_CONTRALORIA'] = $AniosCem." , ".$MesesCem." , ".$DiasCem ,
	                            $_ARGS['ANIOS_AMD'],
	                            $_ARGS['TOTAL_ANIOS']//,
	                         //  $_ARGS['FALTAN']
	                            ));
	                            $con++;
	}
	                            
	
	
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	
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
