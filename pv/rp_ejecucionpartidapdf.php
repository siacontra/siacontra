<?php
define('FPDF_FONTPATH','font/');
require('fpdf.php');
require('fphp.php');
connect();
extract ($_POST);
extract ($_GET);
/// ----------------------------------------------------
//------------------------------------------------------
$filtro1=strtr($filtro1, "*", "'");
$filtro2=strtr($filtro2, "*", "'");
$filtro3=strtr($filtro3, "*", "'");
$filtro4=strtr($filtro4, "*", "'");
global $Partida;
global $fd; // fecha desde
global $fh; // fecha hasta


//$filtro=strtr($filtro, ";", "%");
//---------------------------------------------------
//---------------------------------------------------
class PDF extends FPDF
{
//Page header
function Header(){
    
	$this->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$this->SetFont('Arial', 'B', 8);
	$this->SetXY(20, 10); $this->Cell(200, 5,utf8_decode( 'Contraloría del estado Monagas'), 0, 0, 'L');
	                      $this->Cell(10,5,'Fecha:',0,0,'');$this->Cell(10,5,date('d/m/Y'),0,1,'');
	$this->SetXY(20, 15); $this->Cell(200, 5, utf8_decode('Dirección de Administración y Presupuesto'), 0, 0, 'L');
	                       $this->Cell(10,5,utf8_decode('Página:'),0,1,'');
	$this->SetXY(20, 20); $this->Cell(204, 5, '', 0, 0, 'L');
	                       $this->Cell(7,5,utf8_decode('Año:'),0,0,'L');$this->Cell(5,5,date('Y'),0,1,'L');
						   
	$this->SetFont('Arial', 'B', 10);
	$this->Cell(250, 10, utf8_decode('Reporte Ejecución por Partidas'), 0, 1, 'C');
	//// 
    
	//// 
$this->SetFont('Arial', 'B', 10);
	$this->Rect(14, 35, 260, 0.1);
	$this->Rect(14, 37, 260, 0.1);
	$this->Cell(5, 15);
	$this->Cell(40, 10, utf8_decode('Número'), 0, 0, 'L');	
	$this->Cell(60, 10, utf8_decode('Fecha'), 0, 0, 'L');
	$this->Cell(100, 10, utf8_decode('Detalle'), 0, 0, 'L');
	$this->Cell(60, 10, utf8_decode('Monto Bs.'), 0, 1, 'C');
	$this->Rect(14, 43, 260, 0.1);
	$this->Rect(14, 45, 260, 0.1);		
}

//Page footer
function Footer(){
    //Position at 1.5 cm from bottom
    $this->SetXY(200,13);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Page number
    $this->Cell(0,10,' '.$this->PageNo().'/{nb}',0,0,'C');
}
}

//Instanciation of inherited class
$pdf=new PDF('L','mm','Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

////	
$sql = "select * from pv_presupuesto where Organismo<>'' $filtro1";
$query=mysql_query($sql) or die ($sql.mysql_error()); //echo $sql ;
$rows = mysql_num_rows($query);
$field = mysql_fetch_array($query);
/// ******************************************************************************************************
/// CONSULTA PARA MOSTRAR  DATOS DE CABECERA
$scon01 = "select
                  pvs.descripcion as DescpSector,
				  pvs.cod_sector as CodigoSector,
				  pvp.cod_programa as CodigoPrograma,
				  pvp.descp_programa as DescpPrograma,
				  pvsub.cod_subprog as CodigoSubPrograma,
				  pvsub.descp_subprog as DescpSubprog,
				  pvpro.cod_proyecto as CodigoProyecto,
				  pvpro.descp_proyecto as DescpProyecto,
				  pvact.cod_actividad as CodigoActividad,
				  pvact.descp_actividad as DescpActividad
			 from 
			      pv_presupuesto pv 
				  inner join pv_sector pvs on (pvs.cod_sector = pv.Sector) 
				  inner join pv_programa1 pvp on (pvp.id_programa = pv.Programa) 
				  inner join pv_subprog1 pvsub on (pvsub.id_sub = pv.SubPrograma) 
				  inner join pv_proyecto1 pvpro on (pvpro.id_proyecto = pv.Proyecto) 
				  inner join pv_actividad1 pvact on (pvact.id_actividad = pv.Actividad)
			 where 
			      CodPresupuesto='".$field['CodPresupuesto']."' and 
				  EjercicioPpto='".$field['EjercicioPpto']."' and 
				  Organismo= '".$field['Organismo']."'";
$qcon01 = mysql_query($scon01) or die ($scon01.mysql_error());
$fcon01 = mysql_fetch_array($qcon01);
/// ******************************************************************************************************
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetXY(10, 42);$pdf->Cell(5,14);
$pdf->Cell(10, 15, $fcon01['CodigoSector'],0,0,'L');$pdf->Cell(5, 15, $fcon01['DescpSector'],0,1,'L');
$pdf->SetXY(10, 46);$pdf->Cell(5,14);
$pdf->Cell(10, 15, $fcon01['CodigoPrograma'],0,0,'L');$pdf->Cell(5, 15, $fcon01['DescpPrograma'],0,1,'L');
$pdf->SetXY(10, 50);$pdf->Cell(5,14);
$pdf->Cell(10, 15, $fcon01['CodigoSubPrograma'],0,0,'L');$pdf->Cell(5, 15, $fcon01['DescpSubprog'],0,1,'L');
$pdf->SetXY(10, 54);$pdf->Cell(5,14);
$pdf->Cell(10, 15, $fcon01['CodigoProyecto'],0,0,'L');$pdf->Cell(5, 15, $fcon01['DescpProyecto'],0,1,'L');
$pdf->SetXY(10, 58);$pdf->Cell(5,14);
$pdf->Cell(10, 15, $fcon01['CodigoActividad'],0,0,'L');$pdf->Cell(5, 15, $fcon01['DescpActividad'],0,1,'L');
$pdf->Rect(14, 69, 260, 0.1);
$pdf->Rect(14, 71, 260, 0.1);
////
/// ******************************************************************************************************
if($Partida!=0){
  //// ****** CONSULTO PARA OBTENER LAS PARTIDAS QUE SE ENCUENTRAN EN EL PERIODO SELECCIONADO
  $montoPartidaActualF = 0;
  list($fdano, $fdmes, $fddia) = SPLIT('[-]', $fd); 
  if($fdano!='')$Pdesde = $fdano.'-'.$fdmes; else $Pdesde = '9999'.'-'.'99';
  list($fhano, $fhmes, $fhdia) = SPLIT('[-]', $fh); 
  if($fhano!='')$Phasta = $fhano.'-'.$fhmes; else $Phasta = '9999'.'-'.'99';
  /// --------------------------------------------------------------------------------------------
  list($part, $gen, $esp, $sesp) = split('[.]', $Partida);
  $Partida2 = $part.'.'.'99'.'.'.'99'.'.'.'99';
  /// --------------------------------------------------------------------------------------------
   
  
  $s_ordenpdist = "select 
                         *
		   			from 
		        		 ap_ordenpagodistribucion apor
					where 
						 apor.CodOrganismo='$CodOrganismo' and 
						 apor.Anio='".$field['EjercicioPpto']."' and 
						 apor.cod_partida >= '$Partida' and 
						 apor.cod_partida <= '$Partida2' and
						 apor.Estado='PA' and 
						 apor.Periodo>='$Pdesde' and 
						 apor.Periodo<='$Phasta'  $filtro4
				order by cod_partida"; //echo $s_ordenpdist;
  $q_ordenpdist = mysql_query($s_ordenpdist) or die ($s_ordenpdist.mysql_error());
  $r_ordenpdist = mysql_num_rows($q_ordenpdist);  //echo $r_ordenpdist;
  
  if($r_ordenpdist!=0){
	for($i=0;$i<$r_ordenpdist;$i++){
	  $f_ordenpdist = mysql_fetch_array($q_ordenpdist);
	  if($f_ordenpdist['cod_partida']!=$partidaCapturada){
	    $partidaCapturada = $f_ordenpdist['cod_partida']; //echo $partidaCapturada;
		/// *** Consulta para obtener la partida de forma 401.00.00.00
		$s_partida="select 
						  cod_partida,
						  denominacion,
						  partida1,
						  generica,
						  cod_tipocuenta 
		  			from 
		      			  pv_partida 
		 			where 
		      			  cod_partida = '".$f_ordenpdist['cod_partida']."'"; //echo $s_partida; 
	   $q_partida = mysql_query($s_partida) or die ($s_partida.mysql_error());
	   $f_partida = mysql_fetch_array($q_partida);
	   
	   
	   
	   /// *** Consulta para obtener partid y generica
	   $partidaobt = $f_partida['cod_tipocuenta'].''.$f_partida['partida1'].'.'.'00'.'.'.'00'.'.'.'00'; //echo $partidaobt;
	   $partidaobt2 = $f_partida['cod_tipocuenta'].''.$f_partida['partida1'].'.'.'99'.'.'.'99'.'.'.'99'; //echo $partidaobt;
	   
	   $genericaobt = $f_partida['cod_tipocuenta'].''.$f_partida['partida1'].'.'.$f_partida['generica'].'.'.'00'.'.'.'00'; //echo $genericaobt;
	   $genericaobt2 = $f_partida['cod_tipocuenta'].''.$f_partida['partida1'].'.'.$f_partida['generica'].'.'.'99'.'.'.'99'; //echo $genericaobt;
	   
	   $s_pg = "select
	                  pv.denominacion,
					  pv1.denominacion
				 from
				      pv_partida pv,
					  pv_partida pv1
				 where 
				      pv.cod_partida = '$partidaobt' and 
					  pv1.cod_partida = '$genericaobt'";
	   $q_pg = mysql_query($s_pg) or die ($s_pg.mysql_error());
	   $f_pg = mysql_fetch_array($q_pg);  //echo $s_pg ;
	   
	   if(($genericaobt!=$genericaobtCapturada)and($partidaobt!=$partidaobtCapturada)){
		  $genericaobtCapturada = $genericaobt; $partidaobtCapturada = $partidaobt;
		  
	   /// -----------------------------------------------------------------------------------------------
	   ///                    PARA MOSTRAR MONTO POR PARTIDAS CASO 401.00.00.00
	   /// -----------------------------------------------------------------------------------------------
	       $sql_montop = "select 
						        sum(apordist.Monto)						    
					        from 
								ap_ordenpagodistribucion apordist
								left join ap_pagos apor on ((apor.NroOrden = apordist.NroOrden)and
															 (apor.Anio = apordist.Anio)and
															 (apor.CodOrganismo = apordist.CodOrganismo)and apor.Estado<>'AN')
								left join ap_bancotransaccion bt on ((bt.CodigoReferenciaBanco = apordist.NroDocumento)and
															 (bt.CodProveedor = apordist.CodProveedor)and
															 (bt.CodTipoDocumento = apordist.CodTipoDocumento)and bt.Estado<>'AN')							 
								inner join mastpersonas mtper on (mtper.CodPersona = apordist.CodProveedor)
						   where 
								apordist.cod_partida>='$partidaobt' and apordist.cod_partida<='$partidaobt2' and 
								apordist.Anio = '".$f_ordenpdist['Anio']."' and 
								apordist.Estado='PA' and 
								apordist.Periodo>='$Pdesde' and 
								apordist.Periodo<='$Phasta' and 
								apordist.CodOrganismo='$CodOrganismo'
						order by FechaPago, FechaTransaccion"; //echo $s_obtener;
	      $qry_montop = mysql_query($sql_montop) or die ($sql_montop.mysql_error());
	      $row_montop = mysql_num_rows($qry_montop); //echo $r_obtener;
		  if($row_montop!=0) $f_montop = mysql_fetch_array($qry_montop); 
	      $MONTO_GENERAL = number_format($f_montop['0'],2,',','.');
		  
		  
		   $pdf->SetFont('Arial', 'B', 11); /// **** Mostrando partida tipo 401.00.00.00
		   //$pdf->SetXY(10, 70);$pdf->Cell(5,14);
		   $pdf->Cell(30, 10, '*'.$partidaobt.'*', 0, 0,'C');
		   $pdf->Cell(200, 10, '*'.$f_pg['0'].'*',0,0,'L');
		   $pdf->Cell(50, 10, '*'.$MONTO_GENERAL.'*',0,1,'L');
	   /// -----------------------------------------------------------------------------------------------
	   /// -----------------------------------------------------------------------------------------------
	   
	   }	  
	   
	   if($genericaobt!=$genericaobtCapturada2){
		    $genericaobtCapturada2 = $genericaobt;
	   /// -----------------------------------------------------------------------------------------------
	   ///                    PARA MOSTRAR MONTO POR PARTIDAS CASO 401.00.00.00
	   /// -----------------------------------------------------------------------------------------------
	       $sql_montog = "select 
						        sum(apordist.Monto)						    
					        from 
								ap_ordenpagodistribucion apordist
								left join ap_pagos apor on ((apor.NroOrden = apordist.NroOrden)and
															 (apor.Anio = apordist.Anio)and
															 (apor.CodOrganismo = apordist.CodOrganismo)and apor.Estado<>'AN')
								left join ap_bancotransaccion bt on ((bt.CodigoReferenciaBanco = apordist.NroDocumento)and
															 (bt.CodProveedor = apordist.CodProveedor)and
															 (bt.CodTipoDocumento = apordist.CodTipoDocumento)and bt.Estado<>'AN')							 
								inner join mastpersonas mtper on (mtper.CodPersona = apordist.CodProveedor)
						   where 
								apordist.cod_partida>='$genericaobt' and apordist.cod_partida<='$genericaobt2' and 
								apordist.Anio = '".$f_ordenpdist['Anio']."' and 
								apordist.Estado='PA' and 
								apordist.Periodo>='$Pdesde' and 
								apordist.Periodo<='$Phasta' and 
								apordist.CodOrganismo='$CodOrganismo'
						order by FechaPago, FechaTransaccion"; //echo $s_obtener;
	      $qry_montog = mysql_query($sql_montog) or die ($sql_montog.mysql_error());
	      $row_montog = mysql_num_rows($qry_montog); //echo $r_obtener;
		  if($row_montog!=0) $f_montog = mysql_fetch_array($qry_montog); 
	      $MONTO_SECUNDARIO = number_format($f_montog['0'],2,',','.');
	   /// -----------------------------------------------------------------------------------------------
	   /// -----------------------------------------------------------------------------------------------        
	   $pdf->SetFont('Arial', 'B', 9);
	   $pdf->Cell(5,10);
	   $pdf->Cell(30,05,$genericaobt,0,0,'L');
	   $pdf->Cell(200,05,substr($f_pg['1'],0, 80),0,0,'L');
	   $pdf->Cell(50, 05,'*'.$MONTO_SECUNDARIO.'*',0,1,'L');
	    
	   $pdf->Cell(2,10);
	   $pdf->Cell(80,02,'---------------------------------------------------------------------------------------------',0,0,'L');
	   $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,0,'L');
	   $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,1,'L');
	   $pdf->Cell(2,10);
	   $pdf->Cell(80,02,'---------------------------------------------------------------------------------------------',0,0,'L');
	   $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,0,'L');
	   $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,1,'L');
	   
	   
	   }
	   
	     
	    
	   $s_ordenpdist2 = "select 
                         	  apord.NroOrden,
							  apord.Anio,
							  apord.cod_partida,
							  pvp.partida1,
							  pvp.cod_tipocuenta
						from 
							 ap_ordenpagodistribucion apord
							 inner join pv_partida pvp on (pvp.cod_partida = apord.cod_partida)
						where 
							 apord.CodOrganismo='$CodOrganismo' and 
							 apord.Anio='".$field['EjercicioPpto']."' and 
							 apord.cod_partida = '".$f_ordenpdist['cod_partida']."' and 
							 apord.Estado='PA' and 
							 apord.Periodo>='$Pdesde' and 
							 apord.Periodo<='$Phasta'
					order by apord.cod_partida"; //echo $s_ordenpdist2;
  		$q_ordenpdist2 = mysql_query($s_ordenpdist2) or die ($s_ordenpdist2.mysql_error());
  		$r_ordenpdist2 = mysql_num_rows($q_ordenpdist2); //echo $r_ordenpdist2; 
	   	
		for($a=0;$a<$r_ordenpdist2;$a++){
		  $montoPartidaActual = 0;	
		  $f_ordenpdist2 = mysql_fetch_array($q_ordenpdist2);
		  if($f_ordenpdist2['cod_partida']!=$partidaCapturada2){ //echo h.'--'.$f_ordenpdist2['cod_partida'];
		   $partidaCapturada2 = $f_ordenpdist2['cod_partida'];
		   $s_obtener = "select 
	                       apordist.NroOrden,
						   apor.FechaPago,
						   bt.FechaTransaccion,
						   mtper.NomCompleto,
						   apordist.Monto,
						   apordist.cod_partida						    
					   from 
					        ap_ordenpagodistribucion apordist
							left join ap_pagos apor on ((apor.NroOrden = apordist.NroOrden)and
							                             (apor.Anio = apordist.Anio)and
														 (apor.CodOrganismo = apordist.CodOrganismo)and apor.Estado<>'AN')
							left join ap_bancotransaccion bt on ((bt.CodigoReferenciaBanco = apordist.NroDocumento)and
							                             (bt.CodProveedor = apordist.CodProveedor)and
														 (bt.CodTipoDocumento = apordist.CodTipoDocumento)and bt.Estado<>'AN')							 
							inner join mastpersonas mtper on (mtper.CodPersona = apordist.CodProveedor)
					   where 
					        apordist.cod_partida = '".$f_ordenpdist2['cod_partida']."' and 
							apordist.Anio = '".$f_ordenpdist2['Anio']."' and 
							apordist.Estado='PA' and 
							apordist.Periodo>='$Pdesde' and 
							apordist.Periodo<='$Phasta' and 
							apordist.CodOrganismo='$CodOrganismo'
					order by FechaPago, FechaTransaccion"; //echo $s_obtener;
	      $q_obtener = mysql_query($s_obtener) or die ($s_obtener.mysql_error());
	      $r_obtener = mysql_num_rows($q_obtener); //echo $r_obtener; 
		  
		  if($r_obtener!=0){
			  $montoT = 0; 
			 for($b=0; $b<$r_obtener; $b++){
				$f_obtener = mysql_fetch_array($q_obtener);
				if($f_obtener['FechaPago']!='')$fechaMostrar = $f_obtener['FechaPago']; else $fechaMostrar= $f_obtener['FechaTransaccion']; 
				if(($fechaMostrar>=$fd)and($fechaMostrar<=$fh)){
				  $cont = $cont + 1;
				  if($f_obtener['Monto']>0){
			        $montoOrdenPago = number_format($f_obtener['Monto'],2,',','.'); 
				    $montoOrdenPago2 = cambioFormato($montoOrdenPago);
				    $montoT =  $montoT + $montoOrdenPago2; 
				    $montoPartidaActual = $montoT; ///echo $montoT.'--'.$montoPartidaActual.'*/*'; //// Calcula el Monto de la partida consultada 401.00.00.00
				    list($FobtAnio, $FobtbMes, $FobtDia) = SPLIT('[-]', $fechaMostrar);
				    $FobtFecha = $FobtDia.'-'.$FobtbMes.'-'.$FobtAnio;
				    if($f_ordenpdist['cod_partida']!=$capturada){
					  $capturada = $f_obtener['cod_partida'];	
					  $pdf->Cell(5,10);
					  $pdf->Cell(30,05,$f_ordenpdist2['cod_partida'],0,0,'L');$pdf->Cell(200,05,$f_partida['denominacion'],0,0,'L');
					  $pdf->Cell(20, 05,'',0,1,'L'); 
					  $pdf->Cell(2,10);
					  $pdf->Cell(80,02,'---------------------------------------------------------------------------------------------',0,0,'L');
					  $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,0,'L');
					  $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,1,'L');
					}
				
					$pdf->SetFont('Arial', 'B', 8);
					$pdf->Cell(5, 5);
					$pdf->Cell(30, 5,$f_obtener['NroOrden'],0,0,'L');
					$pdf->Cell(20, 5,$FobtFecha,0,0,'L');
					$pdf->Cell(160,5,$f_obtener['NomCompleto'],0,0,'L'); 
					$pdf->Cell(30,05,$montoOrdenPago,0,1,'R');
				  }
			  }
			  
			 } 
			 //echo $cont;
			 $montoPartidaActualF = number_format($montoPartidaActual,2,',','.'); //// Calcula el Monto de la partida consultada 401.00.00.00
			 $montoFinal = $montoFinal + $montoT; //// Calcula el Monto de la partida consultada 401.01.00.00
			 $montoT = number_format($montoT,2,',','.');
			 $pdf->Cell(225,05,'Total = ',0,0,'R'); $pdf->Cell(30,05,$montoT,0,1,'L');
			 /*if(($f_ordenpdist2['partida1']!=$partida1Capt)and($f_ordenpdist2['cod_tipocuenta']!=$cod_tipocuentaCapt)){
				 $pdf->Cell(200,10,'Total Partida = ',0,0,'R'); $pdf->Cell(30,10,$montoPartidaActualF,0,1,'L');
			 }
			 $contadorImprimir=1;
			 $partida1Capt=$f_ordenpdist2['partida1']; //
			 $cod_tipocuentaCapt = $f_ordenpdist2['cod_tipocuenta']; //*/
			 
			 
		  }else{
			  $s_banTrans = "select 
			                        *
								from
								    ap_bancotransaccion 
								where
								    CodPartida='".$f_ordenpdist2['cod_partida']."' and 
									FlagPresupuesto = 'S'";
			 $q_banTrans  = mysql_query($s_banTrans) or die ($s_banTrans.mysql_error());
			 $r_banTrans = mysql_num_rows($q_banTrans);
			 
			 if($r_banTrans!=0){
				 $montoT = 0; $montoPartidaActual=0;
			   for($c=0;$c<$r_banTrans; $c++){
                  $f_banTrans = mysql_fetch_array($q_banTrans);
				  if(($f_banTrans['FechaTransaccion']>=$fd)and($f_banTrans['FechaTransaccion']<=$fh)){
				     $montoBanTrans = $f_banTrans['Monto'] * (-1); //echo $montoBanTrans;
				     $montoOrdenPago = number_format($montoBanTrans,2,',','.'); 
				     //$montoOrdenPago2 = cambioFormato($montoOrdenPago);echo $montoOrdenPago2;
				     $montoT =  $montoT + $montoBanTrans;
					 //$montoPartidaActual = $montoPartidaActual + $montoT; //// Calcula el Monto de la partida consultada 401.00.00.00
				     list($tbAnio, $tbMes, $tbDia) = SPLIT('[-]', $f_banTrans['FechaTransaccion']);
				     $fechaTransaccion = $tbDia.'-'.$tbMes.'-'.$tbAnio;
					 if($f_banTrans['CodPartida']!=$capturada){
					  $capturada = $f_banTrans['CodPartida'];	
					  $pdf->Cell(5,10);
					  $pdf->Cell(30,05,$f_banTrans['CodPartida'],0,0,'L');$pdf->Cell(200,05,$f_partida['denominacion'],0,0,'L');
					  $pdf->Cell(20, 05,'',0,1,'L'); 
					  $pdf->Cell(2,10);
					  $pdf->Cell(80,02,'---------------------------------------------------------------------------------------------',0,0,'L');
					  $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,0,'L');
					  $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,1,'L');
					 }
					
					$pdf->SetFont('Arial', 'B', 8);
					$pdf->Cell(5, 5);
					$pdf->Cell(30, 5,$f_banTrans['CodigoReferenciaBanco'],0,0,'L');
					$pdf->Cell(20, 5,$fechaTransaccion,0,0,'L');
					$pdf->Cell(160,5,$f_obtener['NomCompleto'],0,0,'L'); 
					$pdf->Cell(30,05,$montoOrdenPago,0,1,'R');
				 } }
				 //echo $cont;
				 $montoPartidaActual = $montoPartidaActual + $montoT; //// Calcula el Monto de la partida consultada 401.00.00.00
			     $montoFinal = $montoFinal + $montoT; //// Calcula el Monto de la partida consultada 401.01.00.00
				 $montoT = number_format($montoT,2,',','.');
				 $pdf->Cell(225,05,'Total = ',0,0,'R'); $pdf->Cell(30,05,$montoT,0,1,'L');   	   
			 }	 
			 }
		  }
	}	
   }
  }   
 }
    $montoFinal = number_format($montoFinal,2,',','.');
	$pdf->Cell(2,10);
		   $pdf->Cell(80,02,'---------------------------------------------------------------------------------------------',0,0,'L');
		   $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,0,'L');
		   $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,1,'L');
		   $pdf->Cell(2,10);
		   $pdf->Cell(80,02,'---------------------------------------------------------------------------------------------',0,0,'L');
		   $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,0,'L');
		   $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,1,'L');
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(120,10); $pdf->Cell(80,6,'',0,0,'');$pdf->Cell(24,6,'Total: ',0,0,'R');
	$pdf->Cell(10,6,$montoFinal,0,1,'');
  
  
    //// ***** 
}else{
   //// ****** CONSULTO PARA OBTENER LAS PARTIDAS QUE SE ENCUENTRAN EN EL PERIODO SELECCIONADO
  $montoPartidaActualF = 0;
  list($fdano, $fdmes, $fddia) = SPLIT('[-]', $fd); 
  if($fdano!='')$Pdesde = $fdano.'-'.$fdmes;else $Pdesde = '9999'.'-'.'99';
  list($fhano, $fhmes, $fhdia) = SPLIT('[-]', $fh); 
  if($fhano!='')$Phasta = $fhano.'-'.$fhmes;else $Phasta = '9999'.'-'.'99'; 
  
  $s_ordenpdist = "select 
                         *
		   			from 
		        		 ap_ordenpagodistribucion
					where 
						 CodOrganismo='$CodOrganismo' and 
						 Anio='".$field['EjercicioPpto']."' and 
						 cod_partida <>'' and 
						 Estado='PA' and 
						 Periodo>='$Pdesde' and 
						 Periodo<='$Phasta' $filtro4
				order by cod_partida"; //echo $s_ordenpdist;
  $q_ordenpdist = mysql_query($s_ordenpdist) or die ($s_ordenpdist.mysql_error());
  $r_ordenpdist = mysql_num_rows($q_ordenpdist);  //echo $r_ordenpdist;
  
  if($r_ordenpdist!=0){
	for($i=0;$i<$r_ordenpdist;$i++){
	  $f_ordenpdist = mysql_fetch_array($q_ordenpdist);
	  if($f_ordenpdist['cod_partida']!=$partidaCapturada){
	    $partidaCapturada = $f_ordenpdist['cod_partida']; //echo $partidaCapturada;
		/// *** Consulta para obtener la partida de forma 401.00.00.00
		$s_partida="select 
						  cod_partida,
						  denominacion,
						  partida1,
						  generica,
						  cod_tipocuenta 
		  			from 
		      			  pv_partida 
		 			where 
		      			  cod_partida = '".$f_ordenpdist['cod_partida']."'"; //echo $s_partida; 
	   $q_partida = mysql_query($s_partida) or die ($s_partida.mysql_error());
	   $f_partida = mysql_fetch_array($q_partida);
	   
	   
	   
	   /// *** Consulta para obtener partid y generica
	   $partidaobt = $f_partida['cod_tipocuenta'].''.$f_partida['partida1'].'.'.'00'.'.'.'00'.'.'.'00'; //echo $partidaobt;
	   $partidaobt2 = $f_partida['cod_tipocuenta'].''.$f_partida['partida1'].'.'.'99'.'.'.'99'.'.'.'99'; //echo $partidaobt;
	   
	   $genericaobt = $f_partida['cod_tipocuenta'].''.$f_partida['partida1'].'.'.$f_partida['generica'].'.'.'00'.'.'.'00'; //echo $genericaobt;
	   $genericaobt2 = $f_partida['cod_tipocuenta'].''.$f_partida['partida1'].'.'.$f_partida['generica'].'.'.'99'.'.'.'99'; //echo $genericaobt;
	   
	   $s_pg = "select
	                  pv.denominacion,
					  pv1.denominacion
				 from
				      pv_partida pv,
					  pv_partida pv1
				 where 
				      pv.cod_partida = '$partidaobt' and 
					  pv1.cod_partida = '$genericaobt'";
	   $q_pg = mysql_query($s_pg) or die ($s_pg.mysql_error());
	   $f_pg = mysql_fetch_array($q_pg);  //echo $s_pg ;
	   
	   if(($genericaobt!=$genericaobtCapturada)and($partidaobt!=$partidaobtCapturada)){
		  $genericaobtCapturada = $genericaobt; $partidaobtCapturada = $partidaobt;
		  
	   /// -----------------------------------------------------------------------------------------------
	   ///                    PARA MOSTRAR MONTO POR PARTIDAS CASO 401.00.00.00
	   /// -----------------------------------------------------------------------------------------------
	       $sql_montop = "select 
						        apordist.Monto						    
					        from 
								ap_ordenpagodistribucion apordist
								left join ap_pagos apor on ((apor.NroOrden = apordist.NroOrden)and
															 (apor.Anio = apordist.Anio)and
															 (apor.CodOrganismo = apordist.CodOrganismo)and apor.Estado<>'AN')
								left join ap_bancotransaccion bt on ((bt.CodigoReferenciaBanco = apordist.NroDocumento)and
															 (bt.CodProveedor = apordist.CodProveedor)and
															 (bt.CodTipoDocumento = apordist.CodTipoDocumento)and bt.Estado<>'AN')							 
								inner join mastpersonas mtper on (mtper.CodPersona = apordist.CodProveedor)
						   where 
								apordist.cod_partida>='$partidaobt' and apordist.cod_partida<='$partidaobt2' and 
								apordist.Anio = '".$f_ordenpdist['Anio']."' and 
								apordist.Estado='PA' and 
								apordist.Periodo>='$Pdesde' and 
								apordist.Periodo<='$Phasta' and 
								apordist.CodOrganismo='$CodOrganismo'
						order by FechaPago, FechaTransaccion"; //echo $sql_montop;
	      $qry_montop = mysql_query($sql_montop) or die ($sql_montop.mysql_error());
	      $row_montop = mysql_num_rows($qry_montop); //echo $r_obtener;
		  $MONTO_GEN= 0 ; $m_general= 0;
		  if($row_montop!=0) 
		    for($igen=0; $igen<$row_montop; $igen++){
		       $f_montop = mysql_fetch_array($qry_montop); //echo $f_montop['cod_partida'].'='.$f_montop['Monto'].'/*/';
			   if($f_montop['Monto']<0){ 
				  //$m_general = -1 * $f_montop['Monto'];
				  //$m_general = $f_montop['Monto'];
				  //$MONTO_GEN = $m_general + $MONTO_GEN;
				}else{  //echo $fconMonto['cod_partida'].'='.$fconMonto['Monto'].'/-/';
				  $MONTO_GEN = $f_montop['Monto'] + $MONTO_GEN;
				}
				//$MONTO_GEN = $f_montop['0'] + $MONTO_GEN;
			} 
	        $MONTO_GENERAL = number_format($MONTO_GEN,2,',','.');
		  
		  
		   $pdf->SetFont('Arial', 'B', 11); /// **** Mostrando partida tipo 401.00.00.00
		   //$pdf->SetXY(10, 70);$pdf->Cell(5,14);
		   $pdf->Cell(30, 10, '*'.$partidaobt.'*', 0, 0,'C');
		   $pdf->Cell(200, 10, '*'.$f_pg['0'].'*',0,0,'L');
		   $pdf->Cell(50, 10, '*'.$MONTO_GENERAL.'*//',0,1,'L');
	   /// -----------------------------------------------------------------------------------------------
	   /// -----------------------------------------------------------------------------------------------
	   
	   }	  
	   
	   if($genericaobt!=$genericaobtCapturada2){
		    $genericaobtCapturada2 = $genericaobt;
	   /// -----------------------------------------------------------------------------------------------
	   ///                   PARA MOSTRAR MONTO POR PARTIDAS CASO 401.01.00.00
	   /// -----------------------------------------------------------------------------------------------
	      /* $sql_montog = "select 
						        sum(apordist.Monto)						    
					        from 
								ap_ordenpagodistribucion apordist
								left join ap_pagos apor on ((apor.NroOrden = apordist.NroOrden)and
															 (apor.Anio = apordist.Anio)and
															 (apor.CodOrganismo = apordist.CodOrganismo)and apor.Estado<>'AN')
								left join ap_bancotransaccion bt on ((bt.CodigoReferenciaBanco = apordist.NroDocumento)and
															 (bt.CodProveedor = apordist.CodProveedor)and
															 (bt.CodTipoDocumento = apordist.CodTipoDocumento)and bt.Estado<>'AN')							 
								inner join mastpersonas mtper on (mtper.CodPersona = apordist.CodProveedor)
						   where 
								apordist.cod_partida>='$genericaobt' and apordist.cod_partida<='$genericaobt2' and 
								apordist.Anio = '".$f_ordenpdist['Anio']."' and 
								apordist.Estado='PA' and 
								apordist.Periodo>='$Pdesde' and 
								apordist.Periodo<='$Phasta' and 
								apordist.CodOrganismo='$CodOrganismo'
						order by FechaPago, FechaTransaccion"; //echo $s_obtener;
	      $qry_montog = mysql_query($sql_montog) or die ($sql_montog.mysql_error());
	      $row_montog = mysql_num_rows($qry_montog); //echo $r_obtener;
		  if($row_montog!=0) $f_montog = mysql_fetch_array($qry_montog); 
	      $MONTO_SECUNDARIO = number_format($f_montog['0'],2,',','.');*/
		  
		    $sconMonto = "select 
						        apordist.cod_partida,
								apordist.Monto						    
					        from 
								ap_ordenpagodistribucion apordist
								left join ap_pagos apor on ((apor.NroOrden = apordist.NroOrden)and
															 (apor.Anio = apordist.Anio)and
															 (apor.CodOrganismo = apordist.CodOrganismo)and apor.Estado<>'AN')
								left join ap_bancotransaccion bt on ((bt.CodigoReferenciaBanco = apordist.NroDocumento)and
															 (bt.CodProveedor = apordist.CodProveedor)and
															 (bt.CodTipoDocumento = apordist.CodTipoDocumento)and bt.Estado<>'AN')							 
								inner join mastpersonas mtper on (mtper.CodPersona = apordist.CodProveedor)
						   where 
								apordist.cod_partida>='$genericaobt' and apordist.cod_partida<='$genericaobt2' and 
								apordist.Anio = '".$f_ordenpdist['Anio']."' and 
								apordist.Estado='PA' and 
								apordist.Periodo>='$Pdesde' and 
								apordist.Periodo<='$Phasta' and 
								apordist.CodOrganismo='$CodOrganismo'
						order by FechaPago, FechaTransaccion"; //echo $sconMonto;
	       $qconMonto = mysql_query($sconMonto) or die ($sconMonto.mysql_error());
	       $rconMonto = mysql_num_rows($qconMonto); //echo $rconMonto;
		   $MONTO_SECUN = 0 ; $m_secundario = 0;
		  if($rconMonto!=0){
		     for($icon=0; $icon<$rconMonto; $icon++){
			    $fconMonto = mysql_fetch_array($qconMonto);//echo $fconMonto['cod_partida'].'='.$fconMonto['Monto'].'/*/';
				if($fconMonto['Monto']<0){ 
				  //echo $fconMonto['cod_partida'].'='.$fconMonto['Monto'].'/*/';
				  //$m_secundario = -1 * $fconMonto['Monto'];
				  //$m_secundario = $fconMonto['Monto'];
				  //$MONTO_SECUN = $m_secundario + $MONTO_SECUN;
				}else{  //echo $fconMonto['cod_partida'].'='.$fconMonto['Monto'].'/-/';
				  $MONTO_SECUN = $fconMonto['Monto'] + $MONTO_SECUN;
				}
			 }
		  } 
		  
		  //$f_montog = mysql_fetch_array($qry_montog); 
	      $MONTO_SECUNDARIO = number_format($MONTO_SECUN,2,',','.');
	   /// -----------------------------------------------------------------------------------------------
	   /// -----------------------------------------------------------------------------------------------        
	   $pdf->SetFont('Arial', 'B', 9);
	   $pdf->Cell(5,10);
	   $pdf->Cell(30,05,$genericaobt,0,0,'L');
	   $pdf->Cell(200,05,substr($f_pg['1'],0, 80),0,0,'L');
	   $pdf->Cell(50, 05,'*'.$MONTO_SECUNDARIO.'*',0,1,'L');
	    
	   $pdf->Cell(2,10);
	   $pdf->Cell(80,02,'---------------------------------------------------------------------------------------------',0,0,'L');
	   $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,0,'L');
	   $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,1,'L');
	   $pdf->Cell(2,10);
	   $pdf->Cell(80,02,'---------------------------------------------------------------------------------------------',0,0,'L');
	   $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,0,'L');
	   $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,1,'L');
	   
	   
	   }
	    
	   $s_ordenpdist2 = "select 
                         	  apord.NroOrden,
							  apord.Anio,
							  apord.cod_partida,
							  pvp.partida1,
							  pvp.cod_tipocuenta
						from 
							 ap_ordenpagodistribucion apord
							 inner join pv_partida pvp on (pvp.cod_partida = apord.cod_partida)
						where 
							 apord.CodOrganismo='$CodOrganismo' and 
							 apord.Anio='".$field['EjercicioPpto']."' and 
							 apord.cod_partida = '".$f_ordenpdist['cod_partida']."' and 
							 apord.Estado='PA' and 
							 apord.Periodo>='$Pdesde' and 
							 apord.Periodo<='$Phasta'
					order by apord.cod_partida"; //echo $s_ordenpdist2;
  		$q_ordenpdist2 = mysql_query($s_ordenpdist2) or die ($s_ordenpdist2.mysql_error());
  		$r_ordenpdist2 = mysql_num_rows($q_ordenpdist2); //echo $r_ordenpdist2; 
	   	
		for($a=0;$a<$r_ordenpdist2;$a++){
		  $montoPartidaActual = 0;	
		  $f_ordenpdist2 = mysql_fetch_array($q_ordenpdist2);
		  if($f_ordenpdist2['cod_partida']!=$partidaCapturada2){ //echo h.'--'.$f_ordenpdist2['cod_partida'];
		   $partidaCapturada2 = $f_ordenpdist2['cod_partida'];
		   $s_obtener = "select 
	                       apordist.NroOrden,
						   apor.FechaPago,
						   bt.FechaTransaccion,
						   mtper.NomCompleto,
						   apordist.Monto,
						   apordist.cod_partida						    
					   from 
					        ap_ordenpagodistribucion apordist
							left join ap_pagos apor on ((apor.NroOrden = apordist.NroOrden)and
							                             (apor.Anio = apordist.Anio)and
														 (apor.CodOrganismo = apordist.CodOrganismo)and apor.Estado<>'AN')
							left join ap_bancotransaccion bt on ((bt.CodigoReferenciaBanco = apordist.NroDocumento)and
							                             (bt.CodProveedor = apordist.CodProveedor)and
														 (bt.CodTipoDocumento = apordist.CodTipoDocumento)and 
														 (bt.CodPartida = apordist.cod_partida)and
                                						 (substring(bt.FechaTransaccion,1,4) = apordist.Anio)and
														 (bt.CodOrganismo = apordist.CodOrganismo)and
														 (bt.Secuencia = apordist.Linea)and
														  bt.Estado<>'AN') 						 
							inner join mastpersonas mtper on (mtper.CodPersona = apordist.CodProveedor)
					   where 
					        apordist.cod_partida = '".$f_ordenpdist2['cod_partida']."' and 
							apordist.Anio = '".$f_ordenpdist2['Anio']."' and 
							apordist.Estado='PA' and 
							apordist.Periodo>='$Pdesde' and 
							apordist.Periodo<='$Phasta' and 
							apordist.CodOrganismo='$CodOrganismo'
					order by FechaPago, FechaTransaccion"; //if($f_ordenpdist2['cod_partida']=='401.04.46.00')echo $s_obtener;
	      $q_obtener = mysql_query($s_obtener) or die ($s_obtener.mysql_error());
	      $r_obtener = mysql_num_rows($q_obtener); //echo $r_obtener; 
		  
		  if($r_obtener!=0){
			  $montoT = 0; $montoTotalPartida=0; $montoT1 = 0;
			 for($b=0; $b<$r_obtener; $b++){$montoPart=0;
				$f_obtener = mysql_fetch_array($q_obtener);
				if($f_obtener['FechaPago']!='')$fechaMostrar = $f_obtener['FechaPago']; else $fechaMostrar= $f_obtener['FechaTransaccion']; 
				if(($fechaMostrar>=$fd)and($fechaMostrar<=$fh)){
				  $cont = $cont + 1; //$montoPart=0;
				  if($f_obtener['Monto']!=0){
					  if($f_obtener['Monto']<0){
						  //$montoPart=-1*$f_obtener['Monto'];
						  //$montoPart=$f_obtener['Monto'];
						  $montoOrdenPago = number_format($f_obtener['Monto'],2,',','.');
					  }else{ 
					      $montoPart=$f_obtener['Monto'];
					      $montoOrdenPago = number_format($f_obtener['Monto'],2,',','.');
					  }
					   
			        //$montoOrdenPago = number_format($f_obtener['Monto'],2,',','.'); 
				    //$montoOrdenPago2 = cambioFormato($montoOrdenPago);
					
				    //$montoT =  $montoT + $f_obtener['Monto'];
					$montoT =  $montoT + $montoPart;  
					$montoT1 =  $montoT1 + $montoPart;
				    $montoPartidaActual = $montoT; ///echo $montoT.'--'.$montoPartidaActual.'*/*'; //// Calcula el Monto de la partida consultada 401.00.00.00
				    list($FobtAnio, $FobtbMes, $FobtDia) = SPLIT('[-]', $fechaMostrar);
				    $FobtFecha = $FobtDia.'-'.$FobtbMes.'-'.$FobtAnio;
				    if($f_ordenpdist['cod_partida']!=$capturada){
					  $capturada = $f_obtener['cod_partida'];	
					  $pdf->Cell(5,10);
					  $pdf->Cell(30,05,$f_ordenpdist2['cod_partida'],0,0,'L');$pdf->Cell(200,05,$f_partida['denominacion'],0,0,'L');
					  $pdf->Cell(20, 05,'',0,1,'L'); 
					  $pdf->Cell(2,10);
					  $pdf->Cell(80,02,'---------------------------------------------------------------------------------------------',0,0,'L');
					  $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,0,'L');
					  $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,1,'L');
					}
					if($montoOrdenPago<0){ //echo $b.'-'.$capturada.'***';
						 $pdf->SetFont('Arial', 'B', 8);
						 $pdf->Cell(5, 5);
						 $pdf->Cell(30, 5,$f_obtener['NroOrden'],0,0,'L');
						 $pdf->Cell(20, 5,$FobtFecha,0,0,'L');
						 $pdf->Cell(160,5,$f_obtener['NomCompleto'],0,0,'L'); 
						 $pdf->Cell(30, 5,$montoOrdenPago,0,0,'R');
						 $pdf->Cell(5, 5,' R',0,1,'R');
					
					}else{
						$pdf->SetFont('Arial', 'B', 8);
						$pdf->Cell(5, 5);
						$pdf->Cell(30, 5,$f_obtener['NroOrden'],0,0,'L');
						$pdf->Cell(20, 5,$FobtFecha,0,0,'L');
						$pdf->Cell(160,5,$f_obtener['NomCompleto'],0,0,'L'); 
						$pdf->Cell(30, 5,$montoOrdenPago,0,1,'R');
					}
				  }
			  }
			  
			 } 
			 //echo $cont;
			 $montoPartidaActualF = number_format($montoPartidaActual,2,',','.'); //// Calcula el Monto de la partida consultada 401.00.00.00
			 $montoFinal = $montoFinal + $montoT; //// Calcula el Monto de la partida consultada 401.01.00.00
			 $montoTotalPartida = number_format($montoT1,2,',','.');
			 $pdf->Cell(225,05,'Total = ',0,0,'R'); $pdf->Cell(30,05,$montoTotalPartida,0,1,'L');
				 
		  }else{
			  $s_banTrans = "select 
			                        *
								from
								    ap_bancotransaccion 
								where
								    CodPartida='".$f_ordenpdist2['cod_partida']."' and 
									FlagPresupuesto = 'S'";
			 $q_banTrans  = mysql_query($s_banTrans) or die ($s_banTrans.mysql_error());
			 $r_banTrans = mysql_num_rows($q_banTrans);
			 
			 if($r_banTrans!=0){
				 $montoT = 0; $montoPartidaActual=0;
			   for($c=0;$c<$r_banTrans; $c++){
                  $f_banTrans = mysql_fetch_array($q_banTrans);
				  if(($f_banTrans['FechaTransaccion']>=$fd)and($f_banTrans['FechaTransaccion']<=$fh)){
				     $montoBanTrans = $f_banTrans['Monto'] * (-1); //echo $montoBanTrans;
				     $montoOrdenPago = number_format($montoBanTrans,2,',','.'); 
				     //$montoOrdenPago2 = cambioFormato($montoOrdenPago);echo $montoOrdenPago2;
				     $montoT =  $montoT + $montoBanTrans;
					 //$montoPartidaActual = $montoPartidaActual + $montoT; //// Calcula el Monto de la partida consultada 401.00.00.00
				     list($tbAnio, $tbMes, $tbDia) = SPLIT('[-]', $f_banTrans['FechaTransaccion']);
				     $fechaTransaccion = $tbDia.'-'.$tbMes.'-'.$tbAnio;
					 if($f_banTrans['CodPartida']!=$capturada){
					  $capturada = $f_banTrans['CodPartida'];	
					  $pdf->Cell(5,10);
					  $pdf->Cell(30,05,$f_banTrans['CodPartida'],0,0,'L');$pdf->Cell(200,05,$f_partida['denominacion'],0,0,'L');
					  $pdf->Cell(20, 05,'',0,1,'L'); 
					  $pdf->Cell(2,10);
					  $pdf->Cell(80,02,'---------------------------------------------------------------------------------------------',0,0,'L');
					  $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,0,'L');
					  $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,1,'L');
					 }
					
					$pdf->SetFont('Arial', 'B', 8);
					$pdf->Cell(5, 5);
					$pdf->Cell(30, 5,$f_banTrans['CodigoReferenciaBanco'],0,0,'L');
					$pdf->Cell(20, 5,$fechaTransaccion,0,0,'L');
					$pdf->Cell(160,5,$f_obtener['NomCompleto'],0,0,'L'); 
					$pdf->Cell(30,05,$montoOrdenPago,0,1,'R');
				 } }
				 //echo $cont;
				 $montoPartidaActual = $montoPartidaActual + $montoT; //// Calcula el Monto de la partida consultada 401.00.00.00
			     $montoFinal = $montoFinal + $montoT; //// Calcula el Monto de la partida consultada 401.01.00.00
				 $montoT = number_format($montoT,2,',','.');
				 $pdf->Cell(225,05,'Total = ',0,0,'R'); $pdf->Cell(30,05,$montoT,0,1,'L');   	   
			 }	 
			 }
		  }
	}	
   }
  }   
 }
    $montoFinal = number_format($montoFinal,2,',','.');
	$pdf->Cell(2,10);
		   $pdf->Cell(80,02,'---------------------------------------------------------------------------------------------',0,0,'L');
		   $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,0,'L');
		   $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,1,'L');
		   $pdf->Cell(2,10);
		   $pdf->Cell(80,02,'---------------------------------------------------------------------------------------------',0,0,'L');
		   $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,0,'L');
		   $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,1,'L');
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(120,10); $pdf->Cell(80,6,'',0,0,'');$pdf->Cell(24,6,'Total: ',0,0,'R');
	$pdf->Cell(10,6,$montoFinal,0,1,'');
}
//---------------------------------------------------
$pdf->Output();
?> 
