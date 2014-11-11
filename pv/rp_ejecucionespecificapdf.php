<?php
define('FPDF_FONTPATH','font/');
require('fpdf.php');
require('fphp.php');
connect(); //echo $_SESSION["MYSQL_BD"];
/// -------------------------------------------------
//---------------------------------------------------
$filtro1=strtr($filtro1, "*", "'");
$filtro2=strtr($filtro2, "*", "'");
$filtro3=strtr($filtro3, "*", "'");
$partida = $Partida;
$fdesde = $fd;
$fhasta = $fh;
$cont_partidas= '';
$con_veces = 0;
//$filtro=strtr($filtro, ";", "%");
//---------------------------------------------------
//---------------------------------------------------
class PDF extends FPDF{
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
	$this->Cell(250, 10, utf8_decode('Reporte Ejecución por Específica'), 0, 1, 'C');
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
//---------------------------------------------------
//Instanciation of inherited class
$pdf=new PDF('L','mm','Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

//---------------------------------------------------	
$sql = "select * from pv_presupuesto where Organismo<>'' $filtro1";
$query=mysql_query($sql) or die ($sql.mysql_error()); //echo $sql ;
$rows = mysql_num_rows($query);
$field = mysql_fetch_array($query);

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
//// -----------------------------------------
$pdf->SetFont('Arial', 'B', 10);
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
//// -----------------------------------------
////	Cuerpo
/*$sql = "select * from pv_presupuesto where Organismo<>'' $filtro1";
$query=mysql_query($sql) or die ($sql.mysql_error()); //echo $sql ;
$rows = mysql_num_rows($query);
$field = mysql_fetch_array($query);*/

/*$scon02="select cod_partida,denominacion,partida1,cod_tipocuenta from pv_partida where cod_partida = '$Partida'";
$qcon02=mysql_query($scon02) or die ($scon02.mysql_error());
$fcon02=mysql_fetch_array($qcon02);*/

//// --------------------------------------------------------------------------------------
list($fdano, $fdmes, $fddia) = SPLIT('[-]', $fd); 
  if($fdano!='')$Pdesde = $fdano.'-'.$fdmes; else $Pdesde = '9999'.'-'.'99';
  list($fhano, $fhmes, $fhdia) = SPLIT('[-]', $fh); 
  if($fhano!='')$Phasta = $fhano.'-'.$fhmes; else $Phasta = '9999'.'-'.'99';
//// --------------------------------------------------------------------------------------
$scon03 = "select 
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
					apordist.CodOrganismo='$CodOrganismo' and 
					apordist.Anio = '$fPEjecucion' and 
					apordist.Estado='PA' and 
					apordist.Periodo>='$Pdesde' and 
					apordist.Periodo<='$Phasta' $filtro3 $filtro2
			order by apordist.cod_partida, apor.FechaPago, bt.FechaTransaccion"; //echo $scon03;

//// --------------------------------------------------------------------------------------
//// --------------------------------------------------------------------------------------
//// --------------------------------------------------------------------------------------
//// --------------------------------------------------------------------------------------
//// --------------------------------------------------------------------------------------
//// --------------------------------------------------------------------------------------

/*$scon03="select
      apordist.Anio,
      apordist.NroOrden,
      apordist.Monto,
      apordist.cod_partida,
      part.partida1,
      part.cod_tipocuenta,
      part.generica,
      part.denominacion,
	  apor.NroOrden,
	  apor.CodOrganismo,
	  apor.FechaOrdenPago,
	  mtp.NomCompleto
from
      ap_ordenpago apor
      inner join ap_ordenpagodistribucion apordist on (apordist.NroOrden = apor.NroOrden)
      inner join pv_partida part on (part.cod_partida = apordist.cod_partida)
	  inner join mastpersonas mtp on (mtp.CodPersona = apordist.CodProveedor)
where
      apordist.CodOrganismo='$CodOrganismo' and
      apordist.Anio='".$field['EjercicioPpto']."' and
      apordist.Estado = 'PA' $filtro3  $filtro2 
order by 
      apordist.cod_partida";*/

/*$scon03="select 
                apor.NroOrden,
				apor.CodOrganismo,
				apor.Anio 
		   from 
		        ap_ordenpago apor
		  where 
		        apor.CodOrganismo='$CodOrganismo' and 
				apor.Anio='".$field['EjercicioPpto']."' and 
				apor.Estado='PA' $filtro2";*/
				
$qcon03=mysql_query($scon03) or die ($scon03.mysql_error());
$rcon03=mysql_num_rows($qcon03);//echo $rcon03; 
for($i=0;$i<$rcon03;$i++){
   $fcon03=mysql_fetch_array($qcon03);
   $spart01 = "select
                      *
				 from
				      pv_partida
			    where
			          cod_partida = '".$fcon03['cod_partida']."'";
   $qpart01 = mysql_query($spart01) or die ($spart01.mysql_error());
   $rpart01 = mysql_num_rows($qpart01); 
   if($rpart01!=0){
	  $fpart01 = mysql_fetch_array($qpart01); 
	  /// ------------------------------
	  list($A,$M,$D) = SPLIT('[-]', $fcon03['FechaPago']);  
	   $fechaOrdenPago = $D.'-'.$M.'-'.$A;
	   
	   /// Calculando monto total
	   $monto_x_partida = $monto_x_partida + $fcon03['Monto'];
	   $montoFinal = number_format($monto_x_partida,2,',','.');
	   
	   /// Calculando Monto por linea de distribucion
	   $monto_x_linea = number_format($fcon03['Monto'],2,',','.');
	   
	   
	   	   
	   /// ------------------------------
	   $con_veces = $con_veces + 1;
	   
	   if($fpart01['cod_partida']!= $codpartidaCapt){  
		 $codpartidaCapt = $fpart01['cod_partida'];
		 $cont_partidas = 1 + $cont_partidas; //$pdf->Cell(2,10,$con_veces);
		 /// ---------------------------
		 if(($cont_partidas > 1)or($rcon03==$con_veces)){
		   $monto_x_partidas = 0;
	       $pdf->Cell(2,10);
	       $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,0,'L');
		   $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,0,'L');
		   $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,1,'L');
		   $pdf->SetFont('Arial', 'B', 10);$pdf->Cell(120,6); $pdf->Cell(80,6,'',0,0,'');$pdf->Cell(24,6,'Total Patida: ',0,0,'');
						$pdf->Cell(13,6,$montoTotalPartidas,0,1,'L');
		   $pdf->Cell(2,10);
		   $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,0,'L');
		   $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,0,'L');
		   $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,1,'L');
	     }
		 /// ---------------------------
		 /// Calculando total por partidas 
	         $monto_x_partidas =  $monto_x_partidas + $fcon03['Monto'];
	         $montoTotalPartidas = number_format($monto_x_partidas,2,',','.');
		 /// ---------------------------
		 
		 $cont_partidas = 1; 
		 /// ---------------------------
	     $pdf->SetFont('Arial', 'B', 10);  /// Muestro la partida especifica para agrupar
	     $pdf->Cell(5,10);
	     $pdf->Cell(30, 05,$fpart01['cod_partida'],0,0,'L');$pdf->Cell(50, 05, $fpart01['denominacion'],0,1,'L');
		 /// ---------------------------
		 $pdf->SetFont('Arial', '', 10);  /// Imprime las partidas
         $pdf->Cell(5,10);
         $pdf->Cell(40, 05,$fcon03['NroOrden'],0,0,'L');$pdf->Cell(40, 05,$fechaOrdenPago,0,0,'L');
	     $pdf->Cell(150, 05,$fcon03['NomCompleto'],0,0,'L');$pdf->Cell(30,05,$monto_x_linea,0,1,'L');
	   }else{
		 		//$pdf->Cell(2,10,$con_veces); 
		 /// ---------------------------
		 /// Calculando total por partidas 
	         $monto_x_partidas =  $monto_x_partidas + $fcon03['Monto'];
	         $montoTotalPartidas = number_format($monto_x_partidas,2,',','.');
		 /// ---------------------------  
         $pdf->SetFont('Arial', 'B', 10);
         $pdf->Cell(5,10);
         $pdf->Cell(40, 05,$fcon03['NroOrden'],0,0,'L');$pdf->Cell(40, 05,$fechaOrdenPago,0,0,'L');
	     $pdf->Cell(150, 05,$fcon03['NomCompleto'],0,0,'L');$pdf->Cell(30,05,$monto_x_linea,0,1,'L');
		 /// ----------------------------
		 if($rcon03==$con_veces){
		   $monto_x_partidas = 0;
	       $pdf->Cell(2,10);
	       $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,0,'L');
		   $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,0,'L');
		   $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,1,'L');
		   $pdf->SetFont('Arial', 'B', 10);$pdf->Cell(120,6); $pdf->Cell(80,6,'',0,0,'');$pdf->Cell(24,6,'Total Patida: ',0,0,'');
						$pdf->Cell(13,6,$montoTotalPartidas,0,1,'L');
		   $pdf->Cell(2,10);
		   $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,0,'L');
		   $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,0,'L');
		   $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,1,'L');
	     }
	   }
	   /// --------------------------
		 if($rcon03==$con_veces){ //$pdf->Cell(25,8,$con_veces);     
		   /// Calculando total por partidas 
	         //$monto_x_partidas =  $monto_x_partidas + $fcon03['Monto'];
	         //$montoTotalPartidas = number_format($monto_x_partidas,2,',','.');
		  /// ---------------------------  
		   $pdf->Cell(2,10);
	       $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,0,'L');
		   $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,0,'L');
		   $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,1,'L');
		   $pdf->SetFont('Arial', 'B', 10);$pdf->Cell(120,6); $pdf->Cell(80,6,'',0,0,'');$pdf->Cell(24,6,'Total Patida: ',0,0,'');
						$pdf->Cell(13,6,$montoTotalPartidas,0,1,'L');
		   $pdf->Cell(2,10);
		   $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,0,'L');
		   $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,0,'L');
		   $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,1,'L');
		   $pdf->Cell(2,10);
           $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,0,'L');
           $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,0,'L');
           $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,1,'L');
		   $pdf->SetFont('Arial', 'B', 12);$pdf->Cell(120,6); $pdf->Cell(80,6,'',0,0,'');$pdf->Cell(24,6,'Total: ',0,0,'');
	                $pdf->Cell(13,6,$montoFinal,0,1,'L');
           $pdf->Cell(2,10);
           $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,0,'L');
           $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,0,'L');
           $pdf->Cell(75,02,'---------------------------------------------------------------------------------------------',0,1,'L');
	     }
	     
      }
   }
//---------------------------------------------------
$pdf->Output();
?> 
