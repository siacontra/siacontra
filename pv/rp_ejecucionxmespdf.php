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
//---------------------------------------------------
//---------------------------------------------------
//echo $Periodo;
class PDF extends FPDF{
//Page header
function Header(){
    
	global $Periodo;
	global $fhasta;
	global $EjercicioPpto;
	$this->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$this->SetFont('Arial', 'B', 8);
	

	$this->SetXY(20, 10); $this->Cell(120, 5,utf8_decode( 'Contraloría del estado Monagas'), 0, 0, 'L');
	                      $this->Cell(10,5,'Fecha :',0,0,'');$this->Cell(10,5,date('d/m/Y'),0,1,'');
	                      
	$this->SetXY(20, 15); $this->Cell(120, 5, utf8_decode('Dirección de Administración y Presupuesto'), 0, 0, 'L');
	                       $this->Cell(10,5,utf8_decode('Página:'),0,1,'');
	$this->SetXY(20, 20); $this->Cell(120, 5, '', 0, 0, 'L');
	                      $this->Cell(10,5,utf8_decode('Año:'),0,0,'');$this->Cell(6,5,date('Y'),0,1,'L');
	                       
	  list( $fmes, $fano) = split('[-]', $fhasta);                      
						   
	  list($fano, $fmes) = SPLIT('[-]',$Periodo);
	//echo $fano, $fmes;
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
	//echo $fmes;					   
	$this->SetFont('Arial', 'B', 10);
	//$this->Cell(105, 10, '', 0, 0, 'C'); 
	$this->Cell(120, 10, utf8_decode('Ejecución Presupuestaria : '.$mes.' '.$fano.''), 0, 0, 'R');
	
    $this->Cell(10, 10, $EjercicioPpto, 0, 0, 'C'); $this->Cell(10, 10,'', 0, 1, 'C');
	///// PRUEBA ***********
	$this->SetFont('Arial', 'B', 8);
	
	$sql =  "SELECT Sector,Programa,SubPrograma,Proyecto,Actividad,Organismo,CodPresupuesto,UnidadEjecutora 
	                    FROM pv_presupuesto 
					   WHERE Organismo<>'' $filtro";
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
	$this->Cell(27, 3, 'SECTOR:', 0, 0, 'L');$this->Cell(3, 3, $fieldSector[1], 0, 0, 'L');$this->Cell(30, 3, $fieldSector['descripcion'], 0, 1, 'L');
	$this->Cell(27, 3, 'PROGRAMA:', 0, 0, 'L');$this->Cell(3, 3, $fieldPrograma[1], 0, 0, 'L');$this->Cell(30, 3, $fieldPrograma[0], 0, 1, 'L');
	$this->Cell(27, 3, 'SUBPROGRAMA:', 0, 0, 'L');$this->Cell(3, 3, $fieldSubprog[1], 0, 0, 'L');$this->Cell(30, 3, $fieldSubprog[0], 0, 1, 'L');
	$this->Cell(27, 3, 'PROYECTO:', 0, 0, 'L');$this->Cell(3, 3, $fieldProyecto[1], 0, 0, 'L');$this->Cell(30, 3, $fieldProyecto[0], 0, 1, 'L');
	$this->Cell(27, 3, 'ACTIVIDAD:', 0, 0, 'L');$this->Cell(3, 3, $fieldActividad[1], 0, 0, 'L');$this->Cell(30, 3, $fieldActividad[0], 0, 1, 'L');
	$this->Cell(27, 3, 'UNIDAD EJECUTORA:', 0, 0, 'L');$this->Cell(30, 3, $fieldOrg[0], 0, 1, 'L');
	$this->SetDrawColor(0, 0, 0); $this->SetFillColor(200, 200, 200); $this->SetTextColor(0, 0, 0);
	$this->SetFont('Arial', 'B', 6);
	$this->Cell(18, 3, 'PAR GE ESP SE', 1, 0, 'C', 1);
	$this->Cell(75, 3, 'DENOMINACION', 1, 0, 'C', 1);
	$this->Cell(25, 3, 'T. COMPROMETIDO', 1, 0, 'C', 1);
	$this->Cell(25, 3, 'T. CAUSADO', 1, 0, 'C', 1);
	$this->Cell(25, 3, 'T. PAGADO', 1, 0, 'C', 1); $this->Ln();
	$this->SetFillColor(255, 255, 255);
	///// ******************	
}
//Page footer
function Footer(){
    //Position at 1.5 cm from bottom
    $this->SetXY(125,13);
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

 list( $mes, $anio) = split('[-]', $fhasta);
 
 $Periodo= $anio."-".$mes;
 
$sqlCon = "select 
                 CodPresupuesto,
				 Organismo 
		    from 
			     pv_presupuesto 
		    where
				Organismo<>'' $filtro"; //echo $sqlCon;
$qryCon = mysql_query($sqlCon) or die ($sqlCon.mysql_error());
$rowCon = mysql_num_rows($qryCon); //echo $rowCon;
$fieldCon = mysql_fetch_array($qryCon);

$sqlDet = "SELECT cod_partida,
                MontoAprobado,
				MontoCompromiso,
				MontoCausado,
				MontoPagado,
                partida,
				generica,
				especifica,
			    subespecifica,
				tipocuenta,
				CodPresupuesto,
				MontoAjustado 
		   FROM 
		        pv_presupuestodet 
		  WHERE 
		        CodPresupuesto='".$fieldCon['CodPresupuesto']."' and 
				Organismo = '".$fieldCon['Organismo']."'
		  ORDER BY cod_partida"; //echo $sqlDet;
$qryDet = mysql_query($sqlDet) or die ($sqlDet.mysql_error());
$rows = mysql_num_rows($qryDet);


for($i=0; $i<$rows ; $i++){
 $fieldet=mysql_fetch_array($qryDet);
 
 
//// --------------------------------------------------------------------------------------------------
//// **** **** **** **** Capturando Partida Tipo "T" 301-00-00-00
//// --------------------------------------------------------------------------------------------------
    $montoAprobado = 0;
	$montoAjustado = 0;
	$montoCompromiso = 0;
	$montoCausado = 0;
	$montoPagado = 0;
	$montoDisponible = 0;
	
 if(($fieldet['partida']!=00) and (($cont1==0) or ($pCapturada!=$fieldet['partida']))){
	 
	  $montoCompromiso1=0; $montoCausado1 =0; $montoPagado1 =0;
	  
  $sqlPar="SELECT cod_partida,partida1,denominacion,cod_tipocuenta 
			 FROM pv_partida 
			WHERE partida1='".$fieldet['partida']."' AND 
			      cod_tipocuenta='".$fieldet['tipocuenta']."' AND 
				  tipo='T' AND 
				  generica='00'";
  $qryPar=mysql_query($sqlPar) or die ($sqlPar.mysql_error());
  $rwPar=mysql_num_rows($qryPar);//$pdf->Cell(5, 3.5, $rwPar);
  if($rwPar!=0){
	  
		/// ************************************************************
		///                        MONTO COMPROMISO
		/// ************************************************************
		
		list($partida, $generica, $espesifica, $subespesifica ) = split('[.]', $fieldet['cod_partida']);
		
	      $s_compromiso1 = "select
								 Monto 
							from
								 lg_distribucioncompromisos 
							where 
								  Estado <> 'AN' and 
								  Periodo = '$Periodo' and 
								  cod_partida LIKE '%".$partida."%'  and 
								  CodOrganismo = '".$fieldCon['Organismo']."'";
								  
								  
		  $q_compromiso1 = mysql_query($s_compromiso1) or die ($s_compromiso1.mysql_error());
		  $r_compromiso1 = mysql_num_rows($q_compromiso1);
		 
		  for($c=0;$c<$r_compromiso1; $c++){
			$f_compromiso1 = mysql_fetch_array($q_compromiso1);
			$montoCompromiso1 = $montoCompromiso1 + $f_compromiso1['Monto'];
		  }
		  
		  $montoCompromisoTotal1 = number_format($montoCompromiso1,2,',','.');  
        //**************************************************************
		/// ************************************************************
		///                        MONTO CAUSADO
		/// ************************************************************
		 $s_causado1 = "select 
							 *
						 from
							 ap_distribucionobligacion
						 where 
							 Estado<>'AN' and 
							 Periodo = '$Periodo' and 
							 cod_partida LIKE '%".$partida."%'  and 
						     CodOrganismo = '".$fieldCon['Organismo']."'";
		$q_causado1 = mysql_query($s_causado1) or die ($s_causado1.mysql_error());
		$r_causado1 = mysql_num_rows($q_causado1);
		
		for($c=0; $c<$r_causado1; $c++){
		  $f_causado1 = mysql_fetch_array($q_causado1);
		  $montoCausado1 =  $montoCausado1 + $f_causado1['Monto'];
		}  
		  $montoCausadoTotal1  = number_format($montoCausado1,2,',','.');
	/// ************************************************************        
 	/// ************************************************************
	///                        MONTO PAGADO
	/// ************************************************************
	 $s_pagado1 = "select
	                       *
					  from 
					       ap_ordenpagodistribucion 
					 where
					       Estado = 'PA' and 
						    Periodo = '$Periodo' and 
							 cod_partida LIKE '%".$partida."%'  and 
						     CodOrganismo = '".$fieldCon['Organismo']."'";
	  $q_pagado1 = mysql_query($s_pagado1) or die ($s_pagado1.mysql_error());
	  $r_pagado1 = mysql_num_rows($q_pagado1);
	  
	  for($c=0; $c<$r_pagado1; $c++){
	    $f_pagado1 = mysql_fetch_array($q_pagado1);
		$montoPagado1 = $montoPagado1 + $f_pagado1['Monto'];
	  
	  }
	   $montoPagadoTotal1 = number_format($montoPagado1,2,',','.');
	 /// ************************************************************
	        	  
   $fpar=mysql_fetch_array($qryPar);
   $montoP=0; $cont1=0; $montoConsulta01=0;
   $sqldet="SELECT MontoAprobado, cod_partida, MontoAjustado,MontoPagado,MontoCausado,MontoCompromiso 
		      FROM pv_presupuestodet 
		     WHERE partida='".$fpar['partida1']."' AND 
			       tipocuenta='".$fpar['cod_tipocuenta']."' AND 
				   CodPresupuesto='".$fieldet['CodPresupuesto']."'";
   $qrydet=mysql_query($sqldet) or die ($sqldet.mysql_error());
   $rwdet=mysql_num_rows($qrydet);
   
   

   for($a=0; $a<$rwdet; $a++){
    $fdet=mysql_fetch_array($qrydet);
	$cont1 = $cont1 + 1;

	//$montoCompromiso = $montoCompromiso + $montoCompromisoTotal1;
	//$montoCausado = $montoCausado + $montoCausadoTotal1;
	//$montoPagado = $montoPagado + $montoPagadoTotal1;
	//$montoDisponible = ($montoAjustado - $montoCompromiso);
   }
   
   
	 
	 $montoCompromiso = number_format($montoCompromiso,2,',','.');
	 $montoCausado = number_format($montoCausado,2,',','.');
	 $montoPagado = number_format($montoPagado,2,',','.');
	 $montoDisponible = number_format($montoDisponible,2,',','.');

	$codigo_partida = $fpar['cod_partida'];
	$pCapturada = $fpar['partida1'];
	
	///**** mostrando los resultados para partida 
	$pdf->SetFillColor(202, 202, 202); 
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetWidths(array(18, 75,25,25,25));
	$pdf->SetAligns(array('C','L','R','R','R'));
	$pdf->Row(array($codigo_partida,$fpar['denominacion'],$montoCompromisoTotal1,$montoCausadoTotal1,$montoPagadoTotal1));

	
  }
 }
//// --------------------------------------------------------------------------------------------------
//// **** **** **** Capturando Partida Tipo "T" 301-01-00-00
//// --------------------------------------------------------------------------------------------------

     $montoCompromisoTotal1=0;$montoCausadoTotal1=0;$montoPagadoTotal1=0;
     $montoCompromisoTotal2=0;$montoCausadoTotal2=0;$montoPagadoTotal2=0;
    $montoAprobado1 = 0;
	$montoAjustado1 = 0;
	$montoCompromiso1 = 0;
	$montoCausado1 = 0;
	$montoPagado1 = 0;
	$montoDisponible1 = 0;
//**********************************************************************
if(($fieldet['partida']!=00) and (($cont2==0) or ($gCapturada!=$fieldet['generica']) or ($pCapturada2!=$fieldet['partida']))){
	
	  $montoCompromiso2=0; $montoCausado2 =0; $montoPagado2 =0;
    $sql2="SELECT cod_partida,partida1,denominacion,cod_tipocuenta,generica,tipo 
			    FROM pv_partida 
			   WHERE partida1='".$fieldet['partida']."' AND
				     cod_tipocuenta='".$fieldet['tipocuenta']."' AND
				     tipo='T' AND 
					 generica='".$fieldet['generica']."' AND 
					 especifica='00'";
	$qry2=mysql_query($sql2) or die ($sql2.mysql_error());
	$rows2=mysql_num_rows($qry2);//$pdf->Cell(5,3.5,$rwPar2);
	if($rows2!=0){
	  $fpar2=mysql_fetch_array($qry2);
	  $cont2=0;
	  $sqldet2="SELECT MontoAprobado, 
	                   cod_partida, 
					   MontoAjustado, 
					   MontoCompromiso, 
					   MontoCausado,
					   MontoPagado 
			      FROM pv_presupuestodet 
			     WHERE partida='".$fpar2['partida1']."' AND 
				       generica='".$fpar2['generica']."' AND 
					   tipocuenta='".$fpar2['cod_tipocuenta']."' AND 
				       CodPresupuesto='".$fieldet['CodPresupuesto']."'";
	  $qrydet2=mysql_query($sqldet2) or die ($sqldet2.mysql_error());
	  $rwdet2=mysql_num_rows($qrydet2);
	  

	   /// ************************************************************************* 
	   ///                         MONTO COMPROMETIDO
	   /// ************************************************************************* 
	   
	   	//	list($partida, $generica, $espesifica, $subespesifica ) = split('[.]', $fieldet['cod_partida']);

        $cod_part = $fpar2['cod_tipocuenta'].$fpar2['partida1'].".".$fpar2['generica'];
	    $s_compromiso2 = "select
	                         Monto 
						from
						     lg_distribucioncompromisos 
						where 
						     Estado<>'AN' and 
							 Periodo = '$Periodo' and 
							 cod_partida LIKE '%".$cod_part."%'  and 
						     CodOrganismo = '".$fieldCon['Organismo']."'";
						     
	  $q_compromiso2 = mysql_query($s_compromiso2) or die ($s_compromiso2.mysql_error());
	  $r_compromiso2 = mysql_num_rows($q_compromiso2);
	  
	  for($c=0;$c<$r_compromiso2; $c++){
		$f_compromiso2 = mysql_fetch_array($q_compromiso2);
	    $montoCompromiso2 = $montoCompromiso2 + $f_compromiso2['Monto'];
	  }
	  
	  $montoCompromisoTotal2 = number_format($montoCompromiso2,2,',','.');
	   /// ************************************************************************* 
	   /// ************************************************************************* 
	   ///                            MONTO CAUSADO
	   /// ************************************************************************* 
	   $s_causado2 = "select 
							 *
						 from
							 ap_distribucionobligacion
						 where 
						     Estado<>'AN' and 
							 Periodo = '$Periodo' and 
							 cod_partida LIKE '%".$cod_part."%'  and 
						     CodOrganismo = '".$fieldCon['Organismo']."'";
		$q_causado2 = mysql_query($s_causado2) or die ($s_causado2.mysql_error());
		$r_causado2 = mysql_num_rows($q_causado2);
		
		for($c=0; $c<$r_causado2; $c++){
		  $f_causado2 = mysql_fetch_array($q_causado2);
		  $montoCausado2 =  $montoCausado2 + $f_causado2['Monto'];
		}  
		  $montoCausadoTotal2  = number_format($montoCausado2,2,',','.');
	   /// ************************************************************************* 	   
	   /// ************************************************************************* 
	   ///                            MONTO PAGADO
	   /// ************************************************************************* 
	   $s_pagado2 = "select
	                       *
					  from 
					       ap_ordenpagodistribucion 
					 where
						     Estado<>'AN' and 
							 Periodo = '$Periodo' and 
							 cod_partida LIKE '%".$cod_part."%'  and 
						     CodOrganismo = '".$fieldCon['Organismo']."'";
	   $q_pagado2 = mysql_query($s_pagado2) or die ($s_pagado2.mysql_error());
	   $r_pagado2 = mysql_num_rows($q_pagado2);
	  
	   for($c=0; $c<$r_pagado2; $c++){
	     $f_pagado2 = mysql_fetch_array($q_pagado2);
		 $montoPagado2 = $montoPagado2 + $f_pagado2['Monto'];
	   }
	     $montoPagadoTotal2 = number_format($montoPagado2,2,',','.');
	   /// ************************************************************
	  
	  
	  
	  for($b=0; $b<$rwdet2; $b++){
	    $fdet2 = mysql_fetch_array($qrydet2);	
		$cont2 =  $cont2 + 1;
		/*   
	    $montoAprobado1 = $montoAprobado1 + $fdet2['MontoAprobado'];
		$montoAjustado1 = $montoAjustado1 + $fdet2['MontoAjustado'];
		$montoCompromiso1 = $montoCompromiso1 + $fdet2['MontoCompromiso'];
		$montoCausado1 = $montoCausado1 + $fdet2['MontoCausado'];
		$montoPagado1 = $montoPagado1 + $fdet2['MontoPagado'];
		$montoDisponible1 = ($montoAjustado1 - $montoCompromiso1);*/
	   
	  }

			$montoCompromiso1 = number_format($montoCompromiso1,2,',','.');
			$montoCausado1 = number_format($montoCausado1,2,',','.');
			$montoPagado1 = number_format($montoPagado1,2,',','.');
			$montoDisponible1 = number_format($montoDisponible1,2,',','.');


			$montoGen=number_format($montoG,2,',','.');
			$codigo_partida = $fpar2['cod_partida'];
			$gCapturada = $fpar2['generica'];
			$pCapturada2 = $fpar2['partida1'];
			/// Monto Incrementado
			$montoInc2 = number_format($montoConsulta02,2,',','.');
			///**** mostrando los resultados para partida 
			$pdf->SetFillColor(230, 230, 230);
			$pdf->SetFont('Arial', 'B', 7);
			$pdf->SetWidths(array(18, 75,25,25,25));
			$pdf->SetAligns(array('C','L','R','R','R'));
			$pdf->Row(array($codigo_partida,$fpar2['denominacion'],$montoCompromisoTotal2 ,$montoCausadoTotal2,$montoPagadoTotal2));
	  
				$montoCompromiso2 =0;
				$montoCausadoTotal2  = 0;
				$montoCausado2=0;
   }
   
   
  }
//**********************************************************************
//// --------------------------------------------------------------------------------------------------
////             **** **** **** Capturando Partida Tipo "D" 301-01-01-00 ó 301-01-01-01
//// --------------------------------------------------------------------------------------------------
 if($fieldet['partida']!=00){
	 
	 //$montoDisponible2 = $fieldet['MontoAjustado'] - $fieldet['MontoCompromiso'];
	 	
	 	 $montoCompromisoTotal3 = 0; $montoCompromiso3=0;
	 	 $montoCausadoTotal3  = 0;   $montoCausado3=0;
	 	 $montoPagadoTotal3 = 0;     $montoPagado3=0;
	 	 $montoCompTotal=0;
	 	 $montoCausTotal=0;
	 	 $montoPagaTotal=0;
	 	  

	// $montoCompromiso2 = number_format($fieldet['MontoCompromiso'] ,2,',','.');
	// $montoCausado2 = number_format($fieldet['MontoCausado'] ,2,',','.');
	 //$montoPagado2 = number_format($fieldet['MontoPagado'] ,2,',','.');
	// $montoDisponible2 = number_format(($fieldet['MontoAjustado'] - $fieldet['MontoCompromiso']),2,',','.');
	 
	 /// *************************************************************************
	 ///                            MONTO COMPROMETIDO
	 /// ************************************************************************* 
	   
        $s_compromiso3 = "select
	                         Monto 
						from
						     lg_distribucioncompromisos 
						where 
						      Estado <> 'AN' and 
							  Periodo = '$Periodo' and 
							  cod_partida = '".$fieldet['cod_partida']."'";
	  $q_compromiso3 = mysql_query($s_compromiso3) or die ($s_compromiso3.mysql_error());
	  $r_compromiso3 = mysql_num_rows($q_compromiso3);
	  
	  for($c=0;$c<$r_compromiso3; $c++){
		$f_compromiso3 = mysql_fetch_array($q_compromiso3);
	    $montoCompromiso3 = $montoCompromiso3 + $f_compromiso3['Monto'];
	  }
	  $montoCompromisoTotal3 = number_format($montoCompromiso3,2,',','.');
	  
	  $montoCompTotal = $montoCompTotal + $montoCompromiso3;
	 /// ************************************************************************* 
	 /// ************************************************************************* 
	 ///                              MONTO CAUSADO
	 /// ************************************************************************* 
	   $s_causado3 = "select 
							 *
						 from
							 ap_distribucionobligacion
						 where 
						      Estado <> 'AN' and 
							  Periodo = '$Periodo' and 
							  cod_partida = '".$fieldet['cod_partida']."'";
		$q_causado3 = mysql_query($s_causado3) or die ($s_causado3.mysql_error());
		$r_causado3 = mysql_num_rows($q_causado3);
		
		for($c=0; $c<$r_causado3; $c++){
		  $f_causado3 = mysql_fetch_array($q_causado3);
		  $montoCausado3 =  $montoCausado3 + $f_causado3['Monto'];
		}  
		  $montoCausadoTotal3  = number_format($montoCausado3,2,',','.');
		  $montoCausTotal = $montoCausTotal + $montoCausado3;
	 /// *************************************************************************
	 ///                               MONTO PAGADO
	 /// ************************************************************************* 
	    $s_pagado3 = "select
	                       *
					  from 
					       ap_ordenpagodistribucion 
					 where
					       Estado = 'PA' and 
							  Periodo = '$Periodo' and 
							  cod_partida = '".$fieldet['cod_partida']."'";
	   $q_pagado3 = mysql_query($s_pagado3) or die ($s_pagado3.mysql_error());
	   $r_pagado3 = mysql_num_rows($q_pagado3);
	  
	   for($c=0; $c<$r_pagado3; $c++){
	     $f_pagado3 = mysql_fetch_array($q_pagado3);
		 $montoPagado3 = $montoPagado3 + $f_pagado3['Monto'];
	   }
	     $montoPagadoTotal3 = number_format($montoPagado3,2,',','.');
		 $montoPagaTotal = $montoPagaTotal + $montoPagado3;
	 /// *************************************************************************	 
	 

	 $totalMontoCompromiso = $totalMontoCompromiso +  $montoCompTotal;
	 $totalMontoCausado = $totalMontoCausado + $montoCausTotal;
	 $totalMontoPagado = $totalMontoPagado + $montoPagaTotal;

	 
     //$pdf->Cell(5,3.5,$fieldet['partida']);
	 $sql="SELECT denominacion FROM pv_partida WHERE cod_partida='".$fieldet['cod_partida']."'";
	 $qry=mysql_query($sql) or die ($sql.mysql_error());
	 $field=mysql_fetch_array($qry);
	 $monto=$fieldet['MontoAprobado'];
	 $montoT=$montoT + $monto;
	 $monto=number_format($monto,2,',','.');
	 $montoTotal=number_format($montoT,2,',','.');
	 $montoDet=number_format($fieldet['MontoAprobado']);
	 
	 
	 
		//if ($montoCompromisoTotal3 != $montoCausadoTotal3 ||   $montoCompromisoTotal3 != $montoPagadoTotal3  ||  $montoCausadoTotal3  != $montoPagadoTotal3 ) 
		// {
		//	  $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 100); $pdf->SetTextColor(0, 0, 0);
		// $pdf->SetFont('Arial', '', 6.5);
		//	 $pdf->SetWidths(array(18, 75,25,25,25,25,25,25,25));
		//	 $pdf->SetAligns(array('C','L','R','R','R','R','R','R','R'));
		//	  $pdf->Row(array($fieldet['cod_partida'],$field['denominacion'],$montoCompromisoTotal3,$montoCausadoTotal3, $montoPagadoTotal3,));
		//	  }  else  {
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 6.5);
			$pdf->SetWidths(array(18, 75,25,25,25,25,25,25,25));
			$pdf->SetAligns(array('C','L','R','R','R','R','R','R','R'));
		    
		    $pdf->Row(array($fieldet['cod_partida'],$field['denominacion'],$montoCompromisoTotal3,$montoCausadoTotal3, $montoPagadoTotal3,));
		    
		//    }
 }
  
 
  


//**********************************************************************
}// for any


	///// *** Mostrar *** /////

	$totalMontoCompromiso = number_format($totalMontoCompromiso,2,',','.');
	$totalMontoCausado = number_format($totalMontoCausado,2,',','.');
	$totalMontoPagado = number_format($totalMontoPagado,2,',','.');

	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(202, 202, 202); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8.5);
		$pdf->SetWidths(array(18, 75,25,25,25));
	$pdf->SetAligns(array('C','L','R','R','R'));
	
	$pdf->Row(array('' ,'Total:',$totalMontoCompromiso,$totalMontoCausado,$totalMontoPagado));
	/////

			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetDrawColor(0, 0, 0);
			$pdf->SetFillColor(255, 255, 255);
			$pdf->SetXY(10, 237);
			$pdf->Rect(10, 237, 65, 35, "D"); 
			$pdf->Rect(75, 237, 65, 35, "D");
			$pdf->Rect(140, 237, 65, 35, "D");
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Cell(65, 5, 'ELABORADO POR:', 1, 0, 'L');
			$pdf->Cell(65, 5, 'APROBADO POR:', 1, 0, 'L');
			$pdf->Cell(65, 5, 'REVISADO POR:', 1, 1, 'L');
			##
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->SetXY(10, 242); $pdf->MultiCell(65, 4, 	utf8_decode ('Lcda: Sorielma Salmerón'), 0, 'L');
			$pdf->SetXY(75, 242); $pdf->MultiCell(65, 4, 	'Lcda: Roxaida Estrada', 0, 'L');
			//$pdf->SetXY(140, 242); $pdf->MultiCell(65, 4, 'Lcdo. Cesar Granado', 0, 'L');
				$pdf->SetXY(140, 242); $pdf->MultiCell(65, 4, 'Lcdo. Cesar Granado', 0, 'L');		
			$pdf->SetXY(10, 246); $pdf->MultiCell(65, 4, 	utf8_decode ('JEFE  DIVISIÓN  DE PRESUPUESTO Y CONTABILIDAD'), 0, 'C');
			$pdf->SetXY(75, 246); $pdf->MultiCell(65, 4, 	utf8_decode ('DIRECTORA DE ADMINISTRACIÓN Y PRESUPUESTO (E)'), 0, 'C');
			$pdf->SetXY(140, 246); $pdf->MultiCell(65, 4, 'DIRECTOR GENERAL', 0, 'C');
			//$pdf->SetXY(140, 246); $pdf->MultiCell(65, 4, 'DIRECTORA GENERAL (E)', 0, 'C');
			##
			$pdf->SetXY(10, 263);
			$pdf->SetFont('Arial', 'B', 7);
			$pdf->Cell(65, 4, 'FECHA: ', 0, 0, 'L');
			$pdf->Cell(65, 4, 'FECHA: ', 0, 0, 'L');
			$pdf->Cell(65, 4, 'FECHA: ', 0, 0, 'L');
			##
			$pdf->SetXY(10, 268);
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->Cell(65, 4, 'FIRMA Y SELLO', 1, 0, 'C');
			$pdf->Cell(65, 4, 'FIRMA Y SELLO', 1, 0, 'C');
			$pdf->Cell(65, 4, 'FIRMA Y SELLO', 1, 1, 'C');
/*
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(100,10,'',0,1,'L');
	//	$pdf->Cell(70,10,'ELABORADO POR:',0,0,'L');$pdf->Cell(70,10,'REVISADO POR:',0,0,'L');$pdf->Cell(70,10,'CONFORMADO POR:',0,1,'L');$pdf->Cell(70,10,'APROBADOPOR POR:',0,1,'L');
	//$pdf->Cell(100,5,'',0,0,'L');$pdf->Cell(120,5,'',0,0,'L');$pdf->Cell(100,5,'',0,1,'L');
	//$pdf->Cell(50,5,utf8_decode ('Lcda: Sorielma Salmerón'),1,0,'L');$pdf->Cell(50,5,'Lcda: Milagros Rivas Mata',1,0,'L');$pdf->Cell(50,5,'Lcdo. Cesar Granado',1,1,'L');$pdf->Cell(50,5,'Lcdo. Freddy Cudjoe',1,1,'L'); 
	$pdf->SetWidths(array(90, 90,90));

	$pdf->SetAligns(array('L','L','L'));
	$pdf->Row(array('ELABORADO POR:','APROBADO POR:','REVISADO POR:'),0);
	$pdf->Ln(5);
	$pdf->Row(array(utf8_decode ('Lcda: Sorielma Salmerón'),'Lcda: Milagros Rivas Mata','Lcdo. Cesar Granado'));
	$pdf->Row(array(utf8_decode ('JEFE  DIVISIÓN  DE PRESUPUESTO Y CONTABILIDAD'),utf8_decode ('DIRECTORA DE ADMINISTRACIÓN Y PRESUPUESTO'),'DIRECTOR GENERAL'));
*/

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
