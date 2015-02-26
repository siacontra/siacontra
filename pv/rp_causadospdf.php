<?php
define('FPDF_FONTPATH','font/');
require('fpdf.php');
require('fphp.php');
connect(); 
extract ($_POST);
extract ($_GET);
//echo $_SESSION["MYSQL_BD"];
/// ----------------------------------------------------
//---------------------------------------------------
$filtro=strtr($filtro, "*", "'");
$filtro2=strtr($filtro2, "*", "'");
$filtro3=strtr($filtro3, "*", "'");
//$Periodo = $Periodo;
//$filtro=strtr($filtro, ";", "%");
global $fechaDesde,$fechaHasta;
//---------------------------------------------------
//---------------------------------------------------
//echo $Periodo;
class PDF extends FPDF
{
//Page header
function Header(){
    
	//global $Periodo;
	global $fp_hasta,$fp_desde;
	//echo $Periodo.'/'.$fp_hasta.'****';
	$this->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$this->SetFont('Arial', 'B', 8);
	$this->SetXY(20, 10); $this->Cell(150, 5,utf8_decode( 'Contraloría del estado Monagas'), 0, 0, 'L');
	                      $this->Cell(10,5,'Fecha:',0,0,'');$this->Cell(10,5,date('d/m/Y'),0,1,'');
	$this->SetXY(20, 15); $this->Cell(150, 5, utf8_decode('Dirección de Administración y Presupuesto'), 0, 0, 'L');
	                       $this->Cell(10,5,utf8_decode('Página:'),0,1,'');
	$this->SetXY(19, 20); $this->Cell(204, 5, '', 0, 0, 'L');
	                       $this->Cell(7,5,utf8_decode('Año:'),0,0,'L');$this->Cell(6,5,date('Y'),0,1,'L');
	
  /// CONDICION PARA CUANDO LOS PERIODOS SON IGUALES 	
  /*if($Periodo!=""){					   
	list($fano, $fmes) = SPLIT('[-]',$Periodo);
    switch ($fmes) {
		case 01: $mes = Enero; break;  
		case 02: $mes = Febrero;break; 
		case 03: $mes = Marzo;break;   
		case 04: $mes = Abril;break;   
		case 05: $mes = Mayo;break;    
		case 06: $mes = Junio;break;
		case 07: $mes = Julio; break;
		case 08: $mes = Agosto; break;
		case 09: $mes = Septiembre; break;
		case 10: $mes = Octubre; break;
		case 11: $mes = Noviembre; break;
		case 12: $mes = Diciembre; break;
    }
  }	*/	
    if(($fp_desde!=0)and($fp_hasta!=0)){				   
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(50, 10, '', 0, 0, 'C');
		$this->Cell(55, 10, utf8_decode('Reporte Causados'), 0, 0, 'C');
		$this->Cell(20, 10, 'Desde '.$fp_desde, 0, 0, 'R'); 
		$this->Cell(25, 10, 'Hasta '.$fp_hasta, 0, 1, 'R');
	}else{
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(50, 10, '', 0, 0, 'C');
		$this->Cell(70, 10, utf8_decode('Reporte Causados a la Fecha'), 0, 1, 'C');
	}
	
	
	$sql="SELECT Sector,
	             Programa,
				 SubPrograma,
				 Proyecto,
				 Actividad,
				 Organismo,
				 CodPresupuesto,
				 UnidadEjecutora 
	        FROM 
			     pv_presupuesto 
		   WHERE       
		         Organismo<>'' $filtro";
	$qry=mysql_query($sql) or die ($sql.mysql_error());
	$field=mysql_fetch_array($qry);
	// Sector
	$sqlSector="SELECT descripcion,cod_sector FROM pv_sector WHERE cod_sector='".$field[0]."'";
	$qrySector=mysql_query($sqlSector) or die ($sqlSector.mysql_error());
	$fieldSector=mysql_fetch_array($qrySector);
	// Programa
	$sqlPrograma="SELECT descp_programa,cod_programa FROM pv_programa1 WHERE id_programa='".$field[1]."'";
	$qryPrograma=mysql_query($sqlPrograma) or die ($sqlPrograma.mysql_error());
	$fieldPrograma=mysql_fetch_array($qryPrograma);
	// SubPrograma
	$sqlSubprog="SELECT descp_subprog,cod_subprog FROM pv_subprog1 WHERE id_sub='".$field[2]."'";
	$qrySubprog=mysql_query($sqlSubprog) or die ($sqlSubprog.mysql_error());
	$fieldSubprog=mysql_fetch_array($qrySubprog);
	// Proyecto
	$sqlProyecto="SELECT descp_proyecto,cod_proyecto FROM pv_proyecto1 WHERE id_proyecto='".$field[3]."'";
	$qryProyecto=mysql_query($sqlProyecto) or die ($sqlProyecto.mysql_error());
	$fieldProyecto=mysql_fetch_array($qryProyecto);
	// Actividad
	$sqlActividad="SELECT descp_actividad,cod_actividad FROM pv_actividad1 WHERE id_actividad='".$field[4]."'";
	$qryActividad=mysql_query($sqlActividad) or die ($sqlActividad.mysql_error());
	$fieldActividad=mysql_fetch_array($qryActividad);
	// Organismo o Unidad Ejecutora
	$sqlOrg="SELECT Organismo FROM mastorganismos WHERE CodOrganismo='".$field[5]."'";
	$qryOrg=mysql_query($sqlOrg) or die ($sqlOrg.mysql_error());
	$fieldOrg=mysql_fetch_array($qryOrg);
	
	
	$this->SetFont('Arial', '', 7);
	/*$this->Cell(27, 3, 'SECTOR:', 0, 0, 'L');$this->Cell(3, 3, $fieldSector[1], 0, 0, 'L');$this->Cell(30, 3, $fieldSector['descripcion'], 0, 1, 'L');
	$this->Cell(27, 3, 'PROGRAMA:', 0, 0, 'L');$this->Cell(3, 3, $fieldPrograma[1], 0, 0, 'L');$this->Cell(30, 3, $fieldPrograma[0], 0, 1, 'L');
	$this->Cell(27, 3, 'Actividad:', 0, 0, 'L');$this->Cell(3, 3, $fieldSubprog[1], 0, 0, 'L');$this->Cell(30, 3, $fieldSubprog[0], 0, 1, 'L');
	$this->Cell(27, 3, 'PROYECTO:', 0, 0, 'L');$this->Cell(3, 3, $fieldProyecto[1], 0, 0, 'L');$this->Cell(30, 3, $fieldProyecto[0], 0, 1, 'L');
	$this->Cell(27, 3, 'Sub-Programa:', 0, 0, 'L');$this->Cell(3, 3, $fieldActividad[1], 0, 0, 'L');$this->Cell(30, 3, $fieldActividad[0], 0, 1, 'L');
	$this->Cell(27, 3, 'UNIDAD EJECUTORA:', 0, 0, 'L');$this->Cell(30, 3, $fieldOrg[0], 0, 1, 'L');*/
	$this->SetDrawColor(0, 0, 0); $this->SetFillColor(200, 200, 200); $this->SetTextColor(0, 0, 0);
	$this->SetFont('Arial', 'B', 7);
	$this->Cell(25, 3, 'NRO. DOCUMENTO', 1, 0, 'C', 1);
	$this->Cell(25, 3, 'TIPO DOCUMENTO', 1, 0, 'C', 1);
	$this->Cell(90, 3, 'PROVEEDOR', 1, 0, 'C', 1);
	$this->Cell(27, 3, 'FECHA DOCUMENTO', 1, 0, 'C', 1);
	$this->Cell(28, 3, 'MONTO COMPROMISO', 1, 1, 'C', 1); $this->Ln();
}
//Page footer
function Footer(){
    //Position at 1.5 cm from bottom
    $this->SetXY(165,13);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Page number
    $this->Cell(0,10,' '.$this->PageNo().'/{nb}',0,0,'C');
}
}
//Instanciation of inherited class
$pdf=new PDF('P','mm','Legal');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

/// ---------------------------------------------------------------------------------------
///   Consulta tabla lg_distribucion compromisos para obtener las partidas involucradas
///   segun periodos a consultar
/// ---------------------------------------------------------------------------------------
$s_con01 = "select 
				   distinct(cod_partida) as cod_partida,
				   CodOrganismo,
				   Anio
			  from  
			       ap_distribucionobligacion 
			 where 
			       Estado='CA' and CodOrganismo<>'' $filtro2 
			 order by 
			       cod_partida";
$q_con01 = mysql_query($s_con01) or die ($s_con01.mysql_error());
$r_con01 = mysql_num_rows($q_con01);

if($r_con01!=0){  
  for($i=0;$i<$r_con01;$i++){
    $f_con01 = mysql_fetch_array($q_con01);
	/// ---------------------------------------------------------------------------------------
	/// ------------------- Consultar Presupuesto
	$sqlCon = "select 
					 CodPresupuesto,
					 Organismo 
				from 
					 pv_presupuesto 
				where
					Organismo= '".$f_con01['CodOrganismo']."' and 
					EjercicioPpto = '".$f_con01['Anio']."'"; //echo $sqlCon;
    $qryCon = mysql_query($sqlCon) or die ($sqlCon.mysql_error());
    $rowCon = mysql_num_rows($qryCon);
    $fieldCon = mysql_fetch_array($qryCon);
	/// ---------------------------------------------------------------------------------------
	$sqlDet="SELECT 
	               cod_partida,
                   partida,
				   generica,
				   especifica,
			       subespecifica,
				   tipocuenta,
				   CodPresupuesto,
				   Organismo 
		   FROM 
		        pv_presupuestodet 
		  WHERE  
				Organismo= '".$fieldCon['Organismo']."' and 
				CodPresupuesto = '".$fieldCon['CodPresupuesto']."' and 
				cod_partida = '".$f_con01['cod_partida']."'
		  ORDER BY 
		        cod_partida";  //echo $sqlDet;
    $qryDet=mysql_query($sqlDet) or die ($sqlDet.mysql_error());
    $rows=mysql_num_rows($qryDet);
	
	if($rows!=0){
	  for($j=0;$j<$rows;$j++){
	     $fieldet=mysql_fetch_array($qryDet);
		 
		 //// **********************************************************************
 		 ////                Capturando Partida Tipo "T" 301-00-00-00 
 		 //// **********************************************************************
 		 if(($fieldet['partida']!=00)and(($cont1==0)or($pCapturada!=$fieldet['partida']))){
  		  
		  $sqlPar= "select 
		     			  cod_partida, 
						  partida1, 
						  denominacion, 
						  cod_tipocuenta 
			          from 
					      pv_partida 
					 where 
					      partida1='".$fieldet['partida']."' AND 
			      		  cod_tipocuenta='".$fieldet['tipocuenta']."' AND 
				          tipo='T' AND 
				          generica='00'";
  		  $qryPar=mysql_query($sqlPar) or die ($sqlPar.mysql_error());
  		  $rwPar=mysql_num_rows($qryPar);
          
		  if($rwPar!=0) $fpar=mysql_fetch_array($qryPar);
		  $partida = $fieldet['partida'] + 1;
		  $codPartida = $fieldet['tipocuenta'].'0'.$partida.'.'.'00'.'.'.'00'.'.'.'00'; //echo $codPartida;
		  $s_distcomp01 = "select 
		                          sum(Monto) as monto
							  from
							      lg_distribucioncompromisos 
							  where 
								  CodOrganismo = '".$fieldet['Organismo']."' and 
								  Estado = 'CO' and 
								  cod_partida >= '".$fieldet['cod_partida']."' and 
								  cod_partida <'$codPartida' $filtro2 "; //echo $s_distcomp01; 
          $q_distcomp01 = mysql_query($s_distcomp01) or die ($s_distcomp01.mysql_error());
		  $r_distcomp01 = mysql_num_rows($q_distcomp01);
		  if($r_distcomp01!=0)$f_distcomp01 = mysql_fetch_array($q_distcomp01);

         
	      $codigo_partida = $fpar['cod_partida'];
	      $pCapturada = $fpar['partida1'];
          $cont1 = $cont1 + 1;
	      $monto01 = number_format($f_distcomp01['monto'],2,',','.');
	      $pdf->SetFillColor(202, 202, 202); 
	      $pdf->SetFont('Arial', 'B', 8);
	      $pdf->SetWidths(array(25,115,35,35));
	      $pdf->SetAligns(array('C','L','R','R'));
	      $pdf->Row(array($codigo_partida,$fpar['denominacion'], $monto01));
		}
		
		 //// ********************************************************************** 
 		 ////                  Capturando Partida Tipo "T" 301-01-00-00
 		 //// **********************************************************************
         if(($fieldet['partida']!=00) and (($cont2==0) or ($gCapturada!=$fieldet['generica']) or ($pCapturada2!=$fieldet['partida']))){
            $sql2= "select 
			 			  cod_partida,
						  partida1,
						  denominacion,
						  cod_tipocuenta,
						  generica,
						  tipo 
			         from 
					      pv_partida 
			        where 
					      partida1='".$fieldet['partida']."' and 
				          cod_tipocuenta='".$fieldet['tipocuenta']."' and 
				          tipo='T' and 
					      generica='".$fieldet['generica']."' AND 
					      especifica='00'";
	        $qry2=mysql_query($sql2) or die ($sql2.mysql_error());
	        $rows2=mysql_num_rows($qry2);
	        
			if($rows2!=0) $fpar2=mysql_fetch_array($qry2);
			$generica = $fieldet['generica'] + 1; 
            if($generica<10) $generica= '0'.$generica;
		    $codPartida2 = $fieldet['tipocuenta'].''.$fieldet['partida'].'.'.$generica.'.'.'00'.'.'.'00'; //echo $codPartida2;
			 $s_distcomp02 = "select 
		                          sum(Monto) as monto
							  from
							      lg_distribucioncompromisos 
							  where 
								  CodOrganismo = '".$fieldet['Organismo']."' and 
								  Estado = 'CO' and 
								  cod_partida >= '".$fieldet['cod_partida']."'  and 
								  cod_partida < '$codPartida2' $filtro2 "; //echo $s_distcomp02; 
            $q_distcomp02 = mysql_query($s_distcomp02) or die ($s_distcomp02.mysql_error());
		    $r_distcomp02 = mysql_num_rows($q_distcomp02);
		    
			if($r_distcomp02!=0)$f_distcomp02 = mysql_fetch_array($q_distcomp02);
			
	       $cont2 = $cont2 + 1;
		   $codigo_partida = $fpar2['cod_partida'];
	       $gCapturada = $fpar2['generica'];
	       $pCapturada2 = $fpar2['partida1'];
	  	   $monto02 = number_format($f_distcomp02['monto'],2,',','.'); 
	       $pdf->SetFillColor(202, 202, 202);
	       $pdf->SetFont('Arial', 'B', 7);
	       $pdf->SetWidths(array(25,115,35,35));
	       $pdf->SetAligns(array('C','L','R'));
	       $pdf->Row(array($codigo_partida,$fpar2['denominacion'],$monto02));
		 }	
		 
		 //// ********************************************************************** 
 		 ////                  Capturando Partida Tipo "T" 301-01-01-00
 		 //// **********************************************************************
		 if($fieldet['partida']!=00){
	 		$sql="select 
			            denominacion 
					FROM 
					    pv_partida 
				  WHERE 
				        cod_partida='".$fieldet['cod_partida']."'";
	 		$qry=mysql_query($sql) or die ($sql.mysql_error());
	 		$row=mysql_num_rows($qry);
			if($row!=0)$field=mysql_fetch_array($qry);
			
			$pdf->SetFillColor(255, 255, 255);
	        $pdf->SetFont('Arial', 'B', 7);
	        $pdf->SetWidths(array(25,115,35,35));
	        $pdf->SetAligns(array('C','L','R'));
	        $pdf->Row(array($fieldet['cod_partida'],$field['denominacion']));
			
$s_distcomp03 = "(SELECT
						do.cod_partida,
						p.denominacion AS nompartida,
						do.Monto,
						do.CodTipoDocumento,
						do.NroDocumento,
						o.FechaAprobado AS Fecha,
						ps.NomCompleto AS NomProveedor
					FROM
						saicom.ap_distribucionobligacion do
						INNER JOIN saicom.pv_partida p ON (p.cod_partida = do.cod_partida)
						INNER JOIN saicom.ap_obligaciones o ON (o.CodProveedor = do.CodProveedor AND
														 o.CodTipoDocumento = do.CodTipoDocumento AND
														 o.NroDocumento = do.NroDocumento)
						INNER JOIN saicom.mastpersonas ps ON (ps.CodPersona = do.CodProveedor)
					WHERE
						do.Origen = 'OB' and do.cod_partida='".$fieldet['cod_partida']."' and o.FechaAprobado>='$fechaDesde' and o.FechaAprobado<='$fechaHasta')
					
					UNION 
					
					(SELECT
						do.cod_partida,
						p.denominacion AS nompartida,
						do.Monto,
						do.CodTipoDocumento,
						do.NroDocumento,
						bt.FechaTransaccion AS Fecha,
						ps.NomCompleto AS NomProveedor
					FROM
						saicom.ap_distribucionobligacion do
						INNER JOIN saicom.pv_partida p ON (p.cod_partida = do.cod_partida)
						INNER JOIN saicom.ap_bancotransaccion bt ON (bt.CodOrganismo = do.CodOrganismo AND
															  bt.CodTipoDocumento = do.CodTipoDocumento AND
															  bt.CodigoReferenciaBanco = do.NroDocumento AND
															  bt.CodProveedor = do.CodProveedor)
						INNER JOIN saicom.mastpersonas ps ON (ps.CodPersona = do.CodProveedor)
					WHERE
						do.Origen = 'TB' and do.cod_partida='".$fieldet['cod_partida']."' and bt.FechaTransaccion>='$fechaDesde' and bt.FechaTransaccion<='$fechaHasta')
					
						
					ORDER BY cod_partida, Fecha"; //echo $s_distcomp03; 
 $q_distcomp03 = mysql_query($s_distcomp03) or die ($s_distcomp03.mysql_error());
 $r_distcomp03 = mysql_num_rows($q_distcomp03);
		     if($r_distcomp03!=0){
			   for($a=0;$a<$r_distcomp03;$a++){	 
				 $f_distcomp03 = mysql_fetch_array($q_distcomp03);
			
	             $montoT=$montoT + $f_distcomp03['Monto'];
	        
			     $monto03=number_format($f_distcomp03['Monto'],2,',','.');
	             list($ano, $mes, $dia)=split('[-]',$f_distcomp03['Fecha']);
		         $FechaDocumento = $dia.'-'.$mes.'-'.$ano;
		   
			     $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			     $pdf->SetFont('Arial', '', 6.5);
			     $pdf->SetWidths(array(25,50,100,50));
			     $pdf->SetAligns(array('C','C','C','R'));
			     $pdf->Cell(25, 5, $f_distcomp03['NroDocumento'], 0, 0, 'C');
			     $pdf->Cell(25, 5, $f_distcomp03['CodTipoDocumento'], 0, 0, 'C');
			     $pdf->Cell(90, 5, $f_distcomp03['NomProveedor'], 0, 0, 'L');
			     $pdf->Cell(27, 5, $FechaDocumento, 0, 0, 'C');
				 $pdf->Cell(28, 5, $monto03, 0, 1, 'R');
			   }
			 }
		 }
     }
   }
  }     
  		$montoTotal=number_format($montoT,2,',','.');
        $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(202, 202, 202); $pdf->SetTextColor(0, 0, 0);
	    $pdf->SetFont('Arial', 'B', 8.5);
	    //$pdf->SetWidths(array(25,100,35,35));
	    //$pdf->SetAligns(array('C','R','R','R'));
	    //$pdf->Row(array('' ,' ','Total:',$montoTotal));
		$pdf->Cell(175, 10, 'Total = ', 0, 0, 'R');
        $pdf->Cell(28, 10, $montoTotal, 0, 0, 'L');		
}
//---------------------------------------------------*/
/*$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(202, 202, 202); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(100,10,'',0,1,'L');
	$pdf->Cell(100,10,'ELABORADO POR:',0,0,'L');$pdf->Cell(120,10,'REVISADO POR:',0,0,'L');$pdf->Cell(100,10,'CONFORMADO POR:',0,1,'L');
	$pdf->Cell(100,5,'',0,0,'L');$pdf->Cell(120,5,'',0,0,'L');$pdf->Cell(100,5,'',0,1,'L');
	$pdf->Cell(100,5,'T.S.U. MARIANA SALAZAR',0,0,'L');$pdf->Cell(120,5,'LCDA. YOSMAR GREHAM',0,0,'L');$pdf->Cell(100,5,'LCDA. ROSIS REQUENA',0,1,'L');
	$pdf->Cell(100,2,'ASISTENTE DE PRESUPUESTI I',0,0,'L');$pdf->Cell(120,2,'JEFE(A) DIV. ADMINISTRACION Y PRESUPUESTO',0,0,'L');$pdf->Cell(100,2,'DIRECTORA GENERAL',0,1,'L');*/
$pdf->Output();
?>  
