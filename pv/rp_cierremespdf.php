<?php
define('FPDF_FONTPATH','font/');
require('fpdf.php');
require('fphp.php');
connect(); 
extract ($_POST);
extract ($_GET); 
global $Periodo;
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
class PDF extends FPDF
{
//Page header
function Header(){
    
	global $Periodo;
	$this->Image('../imagenes/logos/contraloria.jpg', 10, 10, 10, 10);	
	$this->SetFont('Arial', 'B', 8);
	$this->SetXY(20, 10); $this->Cell(200, 5,utf8_decode( 'Contraloría del estado Monagas'), 0, 0, 'L');
	                      $this->Cell(10,5,'Fecha:',0,0,'');$this->Cell(10,5,date('d/m/Y'),0,1,'');
	$this->SetXY(20, 15); $this->Cell(200, 5, utf8_decode('Dirección de Administración y Presupuesto'), 0, 0, 'L');
	                       $this->Cell(10,5,utf8_decode('Página:'),0,1,'');
	$this->SetXY(19, 20); $this->Cell(204, 5, '', 0, 0, 'L');
	                       $this->Cell(7,5,utf8_decode('Año:'),0,0,'L');$this->Cell(6,5,date('Y'),0,1,'L');
						   
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
	//echo $fmes;					   
	$this->SetFont('Arial', 'B', 10);
	$this->Cell(105, 10, '', 0, 0, 'C');
	$this->Cell(47, 10, utf8_decode('Ejecución Presupuestaria'), 0, 0, 'C');
    $this->Cell(13, 10, $mes, 0, 0, 'C'); $this->Cell(13, 10, $fano, 0, 1, 'C');
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
	$this->Cell(80, 3, 'DENOMINACION', 1, 0, 'C', 1);
	$this->Cell(20, 3, 'DISP. PRESUP.', 1, 0, 'C', 1);
	$this->Cell(20, 3, 'DISP. FINANC.', 1, 0, 'C', 1);
	$this->Cell(20, 3, 'REINTEGRO', 1, 0, 'C', 1);
	$this->Cell(20, 3, 'INCREMENTO', 1, 0, 'C', 1);
	$this->Cell(20, 3, 'DISMINUCION', 1, 0, 'C', 1);
	$this->Cell(20, 3, 'PRESUP. AJUST', 1, 0, 'C', 1);
	$this->Cell(20, 3, 'COMPROMETIDO', 1, 0, 'C', 1);
	$this->Cell(20, 3, 'CAUSADO', 1, 0, 'C', 1);
	$this->Cell(20, 3, 'PAGADO', 1, 0, 'C', 1);
	$this->Cell(20, 3, 'DISP. PRESUP.', 1, 0, 'C', 1);
	$this->Cell(20, 3, 'DISP. FINAN.', 1, 0, 'C', 1);
	$this->Cell(20, 3, 'VARIACION', 1, 1, 'C', 1);
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
$pdf=new PDF('L','mm','Legal');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

//echo $Periodo.'-';
list($ano, $mes) = split('[-]', $Periodo);

if($mes=='01'){
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

$sqlDet="SELECT cod_partida,MontoAprobado,
                partida,generica,especifica,
			    subespecifica,tipocuenta,CodPresupuesto 
		   FROM 
		        pv_presupuestodet 
		  WHERE 
		        CodPresupuesto='".$fieldCon['CodPresupuesto']."' and 
				Organismo = '".$fieldCon['Organismo']."'
		  ORDER BY cod_partida"; //echo $sqlDet;
$qryDet=mysql_query($sqlDet) or die ($sqlDet.mysql_error());
$rows=mysql_num_rows($qryDet);
for($i=0; $i<$rows ; $i++){
 $fieldet=mysql_fetch_array($qryDet);
 //// **** **** **** **** Capturando Partida Tipo "T" 301-00-00-00
 $montoIncremento1 = 0; $montoDisminucion1 = 0; $montoIncrementoTotal1 = 0; $montoDisminucionTotal1 = 0;
 $montoCompromiso1 = 0; $montoCompromisoTotal1 = 0; $montoPptoAjustado1 = 0;
 $montoCausado1 = 0; $montoCausadoTotal1 = 0;
 $montoPagado1 = 0; $montoPagadoTotal1 = 0;
 if(($fieldet['partida']!=00) and (($cont1==0) or ($pCapturada!=$fieldet['partida']))){
  $sqlPar="SELECT cod_partida,partida1,denominacion,cod_tipocuenta 
			 FROM pv_partida 
			WHERE partida1='".$fieldet['partida']."' AND 
			      cod_tipocuenta='".$fieldet['tipocuenta']."' AND 
				  tipo='T' AND 
				  generica='00'";
  $qryPar=mysql_query($sqlPar) or die ($sqlPar.mysql_error());
  $rwPar=mysql_num_rows($qryPar);//$pdf->Cell(5, 3.5, $rwPar);
  if($rwPar!=0){
   $fpar=mysql_fetch_array($qryPar);
   $montoP=0; $cont1=0; $montoConsulta01=0;
   $sqldet="SELECT MontoAprobado, cod_partida 
		      FROM pv_presupuestodet 
		     WHERE partida='".$fpar['partida1']."' AND 
			       tipocuenta='".$fpar['cod_tipocuenta']."' AND 
				   CodPresupuesto='".$fieldet['CodPresupuesto']."'";
   $qrydet=mysql_query($sqldet) or die ($sqldet.mysql_error());
   $rwdet=mysql_num_rows($qrydet);
   for($a=0; $a<$rwdet; $a++){/*$pdf->Cell(5, 3.5,$rwdet);$pdf->Cell(5, 3.5,$a);*/
    $fdet=mysql_fetch_array($qrydet);
    $cont1 = $cont1 + 1;
    $montoP = $montoP + $fdet['MontoAprobado'];
	/// - Consulta de partida incrementada o con ajuste positivo
	/// ************************************************************
	 $s_consulta01 = "select 
						  aj.CodAjuste,
						  aj.TipoAjuste,
						  aj.Periodo,
						  ajdet.cod_partida,
						  ajdet.MontoAjuste
					  from 				   
							pv_ajustepresupuestario aj
                            inner join pv_ajustepresupuestariodet ajdet on ((aj.CodPresupuesto=ajdet.CodPresupuesto)and(aj.CodAjuste=ajdet.CodAjuste))
					where 
					       aj.CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
						   aj.Periodo = '$Periodo' and 
						   aj.Estado = 'AP' and
						   ajdet.cod_partida='".$fdet['cod_partida']."'";	//echo $s_consulta03;				   
						   
	  $q_consulta01 = mysql_query($s_consulta01) or die ($s_consulta01.mysql_error());
	  $r_consulta01 = mysql_num_rows($q_consulta01);
	
      for($c=0; $c<$r_consulta01; $c++){	
	     $f_consulta01 = mysql_fetch_array($q_consulta01); 
	     if($f_consulta01['TipoAjuste']=='IN'){
	       $montoIncremento1 = $f_consulta01['MontoAjuste'] + $montoIncremento1;
	     }else{ 
	       $montoDisminucion1 = $f_consulta01['MontoAjuste'] + $montoDisminucion1;
		 }
	  }
	  $montoIncrementoTotal1 = number_format($montoIncremento1,2,',','.');
	  $montoDisminucionTotal1 = number_format($montoDisminucion1,2,',','.');
	/// MONTO COMPROMISO
	  list($ano, $mes) = SPLIT('[-]', $Periodo);
	  $s_compromiso1 = "select
	                         Monto 
						from
						     lg_distribucioncompromisos 
						where 
						      Estado <> 'AN' and 
							  Periodo = '$Periodo' and 
							  cod_partida = '".$fdet['cod_partida']."'";
	  $q_compromiso1 = mysql_query($s_compromiso1) or die ($s_compromiso1.mysql_error());
	  $r_compromiso1 = mysql_num_rows($q_compromiso1);
	  
	  for($c=0;$c<$r_compromiso1; $c++){
		$f_compromiso1 = mysql_fetch_array($q_compromiso1);
	    $montoCompromiso1 = $montoCompromiso1 + $f_compromiso1['Monto'];
	  }
	  
	  $montoCompromisoTotal1 = number_format($montoCompromiso1,2,',','.');
	/// ************************************************************
	/// MONTO CAUSADO
	 $s_causado1 = "select 
	                     *
					 from
					     ap_distribucionobligacion
					 where 
					     Estado<>'AN' and 
						 Periodo = '$Periodo' and 
						 cod_partida = '".$fdet['cod_partida']."'";
	$q_causado1 = mysql_query($s_causado1) or die ($s_causado1.mysql_error());
	$r_causado1 = mysql_num_rows($q_causado1);
	
	for($c=0; $c<$r_causado1; $c++){
	  $f_causado1 = mysql_fetch_array($q_causado1);
	  $montoCausado1 =  $montoCausado1 + $f_causado1['Monto'];
	}  
	  $montoCausadoTotal1  = number_format($montoCausado1,2,',','.');
	/// ************************************************************
	/// MONTO PAGADO
	 $s_pagado1 = "select
	                       *
					  from 
					       ap_ordenpagodistribucion 
					 where
					       Estado = 'PA' and 
						   Periodo = '$Periodo' and 
						   cod_partida = '".$fdet['cod_partida']."'";
	  $q_pagado1 = mysql_query($s_pagado1) or die ($s_pagado1.mysql_error());
	  $r_pagado1 = mysql_num_rows($q_pagado1);
	  
	  for($c=0; $c<$r_pagado1; $c++){
	    $f_pagado1 = mysql_fetch_array($q_pagado1);
		$montoPagado1 = $montoPagado1 + $f_pagado1['Monto'];
	  }
	    $montoPagadoTotal1 = number_format($montoPagado1,2,',','.');
	 /// ************************************************************
   }
    /// ************ Calculo Monto Ajustado / Disponibilidad Presupuestaria / Disponibilidad Financiera
	$montoPptoAjustado1 = $montoP + $montoReintegro + $montoIncremento1 - $montoDisminucion1;
	$montoDisponPresupuestaria1 = $montoPptoAjustado1 - $montoCompromiso1;
	$montoDisponFinanciera1 = (($montoP - $montoP) + $montoPptoAjustado1) - $montoPagado1;
	$montoVariacion1 = $montoDisponFinanciera1 - $montoDisponPresupuestaria1;
	
	if($montoVariacion1<0)$montoVariacion1 = $montoVariacion1 * -1;
	
	$montoPptoAjustado1 = number_format($montoPptoAjustado1,2,',','.');
	$montoDisponPresupuestaria1 = number_format($montoDisponPresupuestaria1,2,',','.');
	$montoDisponFinanciera1 = number_format($montoDisponFinanciera1,2,',','.');
	$montoVariacion1 = number_format($montoVariacion1,2,',','.');
	/// ************ 
	
    $montoPar=number_format($montoP,2,',','.');
	$codigo_partida = $fpar['cod_partida'];
	$pCapturada = $fpar['partida1'];
	/// Monto Incrementado
	$montoInc = number_format($montoConsulta01,2,',','.');
	///**** mostrando los resultados para partida 
	$pdf->SetFillColor(202, 202, 202); 
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetWidths(array(18, 80,20,20,20,20,20,20,20,20,20,20,20,20));
	$pdf->SetAligns(array('C','L','R','R','R','R','R','R','R','R','R','R','R','R'));
	$pdf->Row(array($codigo_partida,$fpar['denominacion'],$montoPar,$montoPar,'0,00',$montoIncrementoTotal1,$montoDisminucionTotal1,$montoPptoAjustado1,$montoCompromisoTotal1,$montoCausadoTotal1,$montoPagadoTotal1,$montoDisponPresupuestaria1,$montoDisponFinanciera1,$montoVariacion1));
  }
 }
 //// **** **** **** Capturando Partida Tipo "T" 301-01-00-00
 $montoIncremento2 = 0; $montoDisminucion2 = 0; $montoIncrementoTotal2 = 0; $montoDisminucionTotal2 = 0;
 $montoCompromiso2 = 0; $montoCompromisoTotal2 = 0;
 $montoCausado2 = 0; $montoCausadoTotal2 = 0;
 $montoPagado2 = 0; $montoPagadoTotal2 = 0;	
 if(($fieldet['partida']!=00) and (($cont2==0) or ($gCapturada!=$fieldet['generica']) or ($pCapturada2!=$fieldet['partida']))){
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
	  $montoG=0; $cont2=0; $montoConsulta02=0;
	  $sqldet2="SELECT MontoAprobado, cod_partida 
			      FROM pv_presupuestodet 
			     WHERE partida='".$fpar2['partida1']."' AND 
				       generica='".$fpar2['generica']."' AND 
					   tipocuenta='".$fpar2['cod_tipocuenta']."' AND 
				       CodPresupuesto='".$fieldet['CodPresupuesto']."'";
	  $qrydet2=mysql_query($sqldet2) or die ($sqldet2.mysql_error());
	  $rwdet2=mysql_num_rows($qrydet2);
	  for($b=0; $b<$rwdet2; $b++){
	   $fdet2=mysql_fetch_array($qrydet2);
	   $cont2 = $cont2 + 1;
	   $montoG = $montoG + $fdet2['MontoAprobado'];
	   
	   /// ************************************************************
	   $s_consulta02 = "select 
						  aj.CodAjuste,
						  aj.TipoAjuste,
						  aj.Periodo,
						  ajdet.cod_partida,
						  ajdet.MontoAjuste
					  from 				   
							pv_ajustepresupuestario aj
                            inner join pv_ajustepresupuestariodet ajdet on ((aj.CodPresupuesto=ajdet.CodPresupuesto)and(aj.CodAjuste=ajdet.CodAjuste))
					where 
					       aj.CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
						   aj.Periodo = '$Periodo' and 
						   aj.Estado = 'AP' and
						   ajdet.cod_partida='".$fdet2['cod_partida']."'";	//echo $s_consulta03;				   
						   
	  $q_consulta02 = mysql_query($s_consulta02) or die ($s_consulta02.mysql_error());
	  $r_consulta02 = mysql_num_rows($q_consulta02);
	
      for($c=0; $c<$r_consulta02; $c++){	
	     $f_consulta02 = mysql_fetch_array($q_consulta02); 
	     if($f_consulta02['TipoAjuste']=='IN'){
	       $montoIncremento2 = $f_consulta02['MontoAjuste'] + $montoIncremento2;
	     }else{ 
	       $montoDisminucion2 = $f_consulta02['MontoAjuste'] + $montoDisminucion2;
		 }
	  }
	  $montoIncrementoTotal2 = number_format($montoIncremento2,2,',','.');
	  $montoDisminucionTotal2 = number_format($montoDisminucion2,2,',','.');	
	   /// ************************************************************
	   /// MONTO COMPROMETIDO
	   list($ano, $mes) = SPLIT('[-]', $Periodo);
	  $s_compromiso2 = "select
	                         Monto 
						from
						     lg_distribucioncompromisos 
						where 
						      Estado <> 'AN' and 
							  Periodo = '$Periodo' and 
							  cod_partida = '".$fdet2['cod_partida']."'";
	  $q_compromiso2 = mysql_query($s_compromiso2) or die ($s_compromiso2.mysql_error());
	  $r_compromiso2 = mysql_num_rows($q_compromiso2);
	  
	  for($c=0;$c<$r_compromiso2; $c++){
		$f_compromiso2 = mysql_fetch_array($q_compromiso2);
	    $montoCompromiso2 = $montoCompromiso2 + $f_compromiso2['Monto'];
	  }
	  
	  $montoCompromisoTotal2 = number_format($montoCompromiso2,2,',','.');
	   /// ************************************************************
	   /// MONTO CAUSADO
	   $s_causado2 = "select 
							 *
						 from
							 ap_distribucionobligacion
						 where 
							 Estado<>'AN' and 
							 Periodo = '$Periodo' and 
							 cod_partida = '".$fdet2['cod_partida']."'";
		$q_causado2 = mysql_query($s_causado2) or die ($s_causado2.mysql_error());
		$r_causado2 = mysql_num_rows($q_causado2);
		
		for($c=0; $c<$r_causado2; $c++){
		  $f_causado2 = mysql_fetch_array($q_causado2);
		  $montoCausado2 =  $montoCausado2 + $f_causado2['Monto'];
		}  
		  $montoCausadoTotal2  = number_format($montoCausado2,2,',','.');
	   /// ************************************************************
	   /// MONTO PAGADO
	   $s_pagado2 = "select
	                       *
					  from 
					       ap_ordenpagodistribucion 
					 where
					       Estado = 'PA' and 
						   Periodo = '$Periodo' and 
						   cod_partida = '".$fdet2['cod_partida']."'";
	   $q_pagado2 = mysql_query($s_pagado2) or die ($s_pagado2.mysql_error());
	   $r_pagado2 = mysql_num_rows($q_pagado2);
	  
	   for($c=0; $c<$r_pagado2; $c++){
	     $f_pagado2 = mysql_fetch_array($q_pagado2);
		 $montoPagado2 = $montoPagado2 + $f_pagado2['Monto'];
	   }
	     $montoPagadoTotal2 = number_format($montoPagado2,2,',','.');
	   /// ************************************************************
	  $montoConsulta02 = $montoConsulta02 + $f_consulta02['MontoAjuste'];
	  }
	  /// ************ Calculo Monto Ajustado / Disponibilidad Presupuestaria
	   $montoPptoAjustado2 = $montoG + $montoReintegro + $montoIncremento2 - $montoDisminucion2;
	   $montoDisponPresupuestaria2 = $montoPptoAjustado2 - $montoCompromiso2;
	   $montoDisponFinanciera2 = (($montoG - $montoG) + $montoPptoAjustado2) - $montoPagado2;
	   $montoVariacion2 = $montoDisponFinanciera2 - $montoDisponPresupuestaria2;
	   
	   if($montoVariacion2<0)$montoVariacion2 = $montoVariacion2 * -1;
	   
	   $montoPptoAjustado2 = number_format($montoPptoAjustado2,2,',','.');
	   $montoDisponPresupuestaria2 = number_format($montoDisponPresupuestaria2,2,',','.');
	   $montoDisponFinanciera2 = number_format($montoDisponFinanciera2,2,',','.');
	   $montoVariacion2 = number_format($montoVariacion2,2,',','.');
	  /// ************ 
	    
	  $montoGen=number_format($montoG,2,',','.');
	  $codigo_partida = $fpar2['cod_partida'];
	  $gCapturada = $fpar2['generica'];
	  $pCapturada2 = $fpar2['partida1'];
	  /// Monto Incrementado
	  $montoInc2 = number_format($montoConsulta02,2,',','.');
	  ///**** mostrando los resultados para partida 
	  $pdf->SetFillColor(202, 202, 202);
	  $pdf->SetFont('Arial', 'B', 7);
	  $pdf->SetWidths(array(18, 80,20,20,20,20,20,20,20,20,20,20,20,20));
	  $pdf->SetAligns(array('C','L','R','R','R','R','R','R','R','R','R','R','R','R'));
	  $pdf->Row(array($codigo_partida,$fpar2['denominacion'],$montoGen,$montoGen,'0,00',$montoIncrementoTotal2,$montoDisminucionTotal2,$montoPptoAjustado2,$montoCompromisoTotal2,$montoCausadoTotal2,$montoPagadoTotal2,$montoDisponPresupuestaria2, $montoDisponFinanciera2,$montoVariacion2));
   }
  }
 //// **** **** **** Capturando Partida Tipo "D" 301-01-01-00 ó 301-01-01-01
 $montoIncremento3 = 0; $montoDisminucion3 = 0; $montoIncrementoTotal3 = 0; $montoDisminucionTotal3 = 0; 
 $montoCompromisoTotal = 0; $montoCompromiso3 = 0; $montoVariacion3 ='';
 $montoCausado3 = 0; $montoCausadoTotal3 = 0;
 $montoPagado3 = 0; $montoPagadoTotal3 = 0;
 if($fieldet['partida']!=00){
     //$pdf->Cell(5,3.5,$fieldet['partida']);
	 $sql="SELECT denominacion FROM pv_partida WHERE cod_partida='".$fieldet['cod_partida']."'";
	 $qry=mysql_query($sql) or die ($sql.mysql_error());
	 $field=mysql_fetch_array($qry);
	 $monto=$fieldet['MontoAprobado'];
	 $montoT=$montoT + $monto;
	 $monto=number_format($monto,2,',','.');
	 $montoTotal=number_format($montoT,2,',','.');
	 $montoDet=number_format($fieldet['MontoAprobado']);
	 
	 /// Monto Incrementado y Disminuido
	 /// *************************************************************************  
	   $s_consulta03 = "select 
						  aj.CodAjuste,
						  aj.TipoAjuste,
						  aj.Periodo,
						  ajdet.cod_partida,
						  ajdet.MontoAjuste
					  from 				   
							pv_ajustepresupuestario aj
                            inner join pv_ajustepresupuestariodet ajdet on ((aj.CodPresupuesto=ajdet.CodPresupuesto)and(aj.CodAjuste=ajdet.CodAjuste))
					where 
					       aj.CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
						   aj.Periodo = '$Periodo' and 
						   aj.Estado = 'AP' and
						   ajdet.cod_partida='".$fieldet['cod_partida']."'";		
	  $q_consulta03 = mysql_query($s_consulta03) or die ($s_consulta03.mysql_error());
	  $r_consulta03 = mysql_num_rows($q_consulta03); //echo $r_consulta03;
	
      for($c=0; $c<$r_consulta03; $c++){	
	     $f_consulta03 = mysql_fetch_array($q_consulta03); 
	     if($f_consulta03['TipoAjuste']=='IN'){
	       $montoIncremento3 = $f_consulta03['MontoAjuste'] + $montoIncremento3;
	     }else{ 
	       $montoDisminucion3 = $f_consulta03['MontoAjuste'] + $montoDisminucion3;
		 }
	  }
	  
	  $montoIncrementoTotal3 = number_format($montoIncremento3,2,',','.');
	  $montoDisminucionTotal3 = number_format($montoDisminucion3,2,',','.');	
	  	 
	  $montoInc3 = $montoInc3 + $montoIncremento3;
	  $montoIncTotal = number_format($montoInc3,2,',','.');
	  $montoDism3 = $montoDism3 + $montoDisminucion3;
	  $montoDismTotal = number_format($montoDism3,2,',','.');
	 /// *************************************************************************
	 /// MONTO COMPROMETIDO
	       list($ano, $mes) = SPLIT('[-]', $Periodo);
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
	 /// MONTO CAUSADO
	   $s_causado3 = "select 
							 *
						 from
							 ap_distribucionobligacion
						 where 
							 Estado<>'AN' and 
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
	 /// MONTO PAGADO
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
	 /// ************ Calculo Monto Ajustado / Disponibilidad Presupuestaria
	   $montoPptoAjustado3 = $fieldet['MontoAprobado'] + $montoReintegro + $montoIncremento3 - $montoDisminucion3;
	   $montoPptoAjusTotal = $montoPptoAjusTotal + $montoPptoAjustado3;
	   
	   $montoDisponPresupuestaria3 = $montoPptoAjustado3 - $montoCompromiso3;
	   $montoDisponPresupuestTotal = $montoDisponPresupuestTotal + $montoDisponPresupuestaria3;
	   
	   $montoDisponFinanciera3 = (($fieldet['MontoAprobado'] - $fieldet['MontoAprobado']) + $montoPptoAjustado3) - $montoPagado3;
	   $montoDisponFinancieraTotal = $montoDisponFinancieraTotal + $montoDisponFinanciera3;
	   
	   $montoVariacion3 = $montoDisponFinanciera3 - $montoDisponPresupuestaria3;
	   
	   if($montoVariacion3<0){$montoVariacion3=$montoVariacion3*(-1);}
	   $montoVariacionTotal = $montoVariacionTotal + $montoVariacion3;
	  
	  /// ************ 
	   $montoPptoAjustado3 = number_format($montoPptoAjustado3,2,',','.');
	   $montoDisponPresupuestaria3 = number_format($montoDisponPresupuestaria3,2,',','.');
	   $montoDisponFinanciera3 = number_format($montoDisponFinanciera3,2,',','.');
	   $montoVariacion3 = number_format($montoVariacion3,2,',','.');
	 ///
	 $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	 $pdf->SetFont('Arial', '', 6.5);
	 $pdf->SetWidths(array(18, 80,20,20,20,20,20,20,20,20,20,20,20,20));
	 $pdf->SetAligns(array('C','L','R','R','R','R','R','R','R','R','R','R','R','R'));
	 $pdf->Row(array($fieldet['cod_partida'],$field['denominacion'],$monto,$monto,'0,00',$montoIncrementoTotal3,$montoDisminucionTotal3,$montoPptoAjustado3,$montoCompromisoTotal3,$montoCausadoTotal3,$montoPagadoTotal3,$montoDisponPresupuestaria3,$montoDisponFinanciera3,$montoVariacion3));
 }
}
	///// *** Mostrar *** /////
	$montoT=number_format($montoT,2,',','.');
	$montoCompTotal = number_format($montoCompTotal,2,',','.');
	$montoCausTotal = number_format($montoCausTotal,2,',','.');
	$montoPagaTotal = number_format($montoPagaTotal,2,',','.');
	$montoPptoAjusTotal = number_format($montoPptoAjusTotal,2,',','.');
	$montoDisponPresupuestTotal = number_format($montoDisponPresupuestTotal,2,',','.');
	$montoDisponFinancieraTotal = number_format($montoDisponFinancieraTotal,2,',','.');
	$montoVariacionTotal = number_format($montoVariacionTotal,2,',','.');
	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(202, 202, 202); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8.5);
	 $pdf->SetWidths(array(18, 80,20,20,20,20,20,20,20,20,20,20,20,20));
	$pdf->SetAligns(array('C','R','R','R','R','R','R','R','R','R','R','R','R','R'));
	$pdf->Row(array('' ,'Total:',$montoTotal,$montoTotal,'0,00',$montoIncTotal,$montoDismTotal,$montoPptoAjusTotal,$montoCompTotal,$montoCausTotal,$montoPagaTotal,$montoDisponPresupuestTotal,$montoDisponFinancieraTotal,$montoVariacionTotal));
	/////
}else{
//// --------------------------------------------------------------------------------- */
//// --------------------------------------------------------------------------------- */
 $montoTotalDP = 0; $montoCompTotal=0; $montoTotalDF = 0;
 $fmes =(int) ($mes - 1);
 $fmes=(string) str_repeat("0",2-strlen($fmes)).$fmes;
 $fperiodo = $ano.'-'.$fmes; //echo $fperiodo;

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

$sqlDet="SELECT cod_partida,
                MontoAprobado,
                partida,
				generica,
				especifica,
			    subespecifica,
				tipocuenta,
				CodPresupuesto 
		   FROM 
		        pv_presupuestodet 
		  WHERE 
		        CodPresupuesto='".$fieldCon['CodPresupuesto']."' and 
				Organismo = '".$fieldCon['Organismo']."'
		  ORDER BY cod_partida"; //echo $sqlDet;
$qryDet=mysql_query($sqlDet) or die ($sqlDet.mysql_error());
$rows=mysql_num_rows($qryDet);
for($i=0; $i<$rows ; $i++){
 $fieldet=mysql_fetch_array($qryDet);
 //// ----------------------------------------------------------------------------------------------- */
 //// **** **** **** **** Capturando Partida Tipo "T" 301-00-00-00
 //// ----------------------------------------------------------------------------------------------- */
 $montoIncremento1 = 0; $montoDisminucion1 = 0; $montoIncrementoTotal1 = 0; $montoDisminucionTotal1 = 0;
 $montoCompromiso1 = 0; $montoCompromisoTotal1 = 0; $montoPptoAjustado1 = 0;  $montoCausado1 = 0; $montoCausadoTotal1 = 0;
 $montoPagado1 = 0; $montoPagadoTotal1 = 0;  $montoDisPresu = 0; $montoDisFinan = 0;
 if(($fieldet['partida']!=00) and (($cont1==0) or ($pCapturada!=$fieldet['partida']))){
  $sqlPar="SELECT cod_partida,partida1,denominacion,cod_tipocuenta 
			 FROM pv_partida 
			WHERE partida1='".$fieldet['partida']."' AND 
			      cod_tipocuenta='".$fieldet['tipocuenta']."' AND 
				  tipo='T' AND 
				  generica='00'";
  $qryPar=mysql_query($sqlPar) or die ($sqlPar.mysql_error());
  $rwPar=mysql_num_rows($qryPar);//$pdf->Cell(5, 3.5, $rwPar);
  
  if($rwPar!=0){
   $fpar=mysql_fetch_array($qryPar);
   $montoP=0; $cont1=0; $montoConsulta01=0;
   $sqldet="SELECT MontoAprobado, cod_partida, Organismo 
		      FROM pv_presupuestodet 
		     WHERE partida='".$fpar['partida1']."' AND 
			       tipocuenta='".$fpar['cod_tipocuenta']."' AND 
				   CodPresupuesto='".$fieldet['CodPresupuesto']."'"; //echo $sqldet;
   $qrydet=mysql_query($sqldet) or die ($sqldet.mysql_error());
   $rwdet=mysql_num_rows($qrydet);
   $montoReintegro = 0;
   for($a=0; $a<$rwdet; $a++){
	   
    $fdet=mysql_fetch_array($qrydet);
    $cont1 = $cont1 + 1;
	//// -------------------------------------------------------------------------
	$s_ejecucion01="SELECT 
   				         CodPartida,
				 		 DispPresupuestaria,
				 		 DispFinanciera 
		      		FROM 
			     		pv_ejecucionpresupuestaria
		     		WHERE 
			     		Periodo='$fperiodo' and 
				 		CodPresupuesto='".$fieldet['CodPresupuesto']."' and 
						CodPartida = '".$fdet['cod_partida']."'";
   $q_ejecucion01=mysql_query($s_ejecucion01) or die ($s_ejecucion01.mysql_error());
   $r_ejecucion01=mysql_num_rows($q_ejecucion01);
   if($r_ejecucion01!=0) $f_ejecucion01 = mysql_fetch_array($q_ejecucion01);
   //	EDGAR
   else {
	   $f_ejecucion01['DispPresupuestaria'] = 0;
		$f_ejecucion01['DispFinanciera'] = 0;
	}
	//	FIN
   //// -------------------------------------------------------------------------
	
    $montoDisPresu = $montoDisPresu + $f_ejecucion01['DispPresupuestaria'];
	$montoDisFinan = $montoDisFinan + $f_ejecucion01['DispFinanciera'];
	/// - Consulta de partida incrementada o con ajuste positivo
	/// ************************************************************
	 $s_consulta01 = "select 
						  aj.CodAjuste,
						  aj.TipoAjuste,
						  aj.Periodo,
						  ajdet.cod_partida,
						  ajdet.MontoAjuste
					  from 				   
							pv_ajustepresupuestario aj
                            inner join pv_ajustepresupuestariodet ajdet on ((aj.CodPresupuesto=ajdet.CodPresupuesto)and(aj.CodAjuste=ajdet.CodAjuste))
					where 
					       aj.CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
						   aj.Periodo = '$Periodo' and 
						   aj.Estado = 'AP' and 
						   ajdet.cod_partida='".$fdet['cod_partida']."'";	//echo $s_consulta03;				   
						   
	  $q_consulta01 = mysql_query($s_consulta01) or die ($s_consulta01.mysql_error());
	  $r_consulta01 = mysql_num_rows($q_consulta01);
	
      for($c=0; $c<$r_consulta01; $c++){	
	     $f_consulta01 = mysql_fetch_array($q_consulta01); 
	     if($f_consulta01['TipoAjuste']=='IN'){
	       $montoIncremento1 = $f_consulta01['MontoAjuste'] + $montoIncremento1;
	     }else{ 
	       $montoDisminucion1 = $f_consulta01['MontoAjuste'] + $montoDisminucion1;
		 }
	  }
	  $montoIncrementoTotal1 = number_format($montoIncremento1,2,',','.');
	  $montoDisminucionTotal1 = number_format($montoDisminucion1,2,',','.');
	/// ************************************************************
	///                        MONTO COMPROMISO
	/// ************************************************************
	  list($ano, $mes) = SPLIT('[-]', $Periodo);
	  $s_compromiso1 = "select
	                         Monto 
						from
						     lg_distribucioncompromisos 
						where 
						      Estado='CO' and 
							  Periodo = '$Periodo' and 
							  cod_partida = '".$fdet['cod_partida']."' and 
							  CodOrganismo = '".$fdet['Organismo']."'";
	  $q_compromiso1 = mysql_query($s_compromiso1) or die ($s_compromiso1.mysql_error());
	  $r_compromiso1 = mysql_num_rows($q_compromiso1);
	  
	  for($c=0;$c<$r_compromiso1; $c++){
		$f_compromiso1 = mysql_fetch_array($q_compromiso1);
		if($f_compromiso1['Monto']>0)$montoCompromiso1 = $montoCompromiso1 + $f_compromiso1['Monto'];
	  }
	  $montoCompromisoTotal1 = number_format($montoCompromiso1,2,',','.');
	/// ************************************************************
	///                        MONTO CAUSADO
	/// ************************************************************
	 $s_causado1 = "select 
	                     *
					 from
					     ap_distribucionobligacion
					 where 
					     Estado='CA' and 
						 Periodo = '$Periodo' and 
						 cod_partida = '".$fdet['cod_partida']."' and 
						 CodOrganismo = '".$fdet['Organismo']."'"; //echo $s_causado1;
	$q_causado1 = mysql_query($s_causado1) or die ($s_causado1.mysql_error());
	$r_causado1 = mysql_num_rows($q_causado1);
	
	for($c=0; $c<$r_causado1; $c++){
	  $f_causado1 = mysql_fetch_array($q_causado1);
	  if($f_causado1['Monto']>0)$montoCausado1 =  $montoCausado1 + $f_causado1['Monto'];
	}  
	  $montoCausadoTotal1  = number_format($montoCausado1,2,',','.');
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
						   cod_partida = '".$fdet['cod_partida']."' and 
						   CodOrganismo = '".$fdet['Organismo']."'";// echo $s_pagado1;
	  $q_pagado1 = mysql_query($s_pagado1) or die ($s_pagado1.mysql_error());
	  $r_pagado1 = mysql_num_rows($q_pagado1); //echo $r_pagado1;
	  
	  for($c=0; $c<$r_pagado1; $c++){
		  $f_pagado1 = mysql_fetch_array($q_pagado1);
	    if($f_pagado1['Monto']>0)$montoPagado1 = $montoPagado1 + $f_pagado1['Monto'];
	    else{  
		     if((($f_pagado1['cod_partida']!=$cp)and($f_pagado1['Periodo']!=$pr))or($paso==0)){ 
		    $r_01 =  -1*($f_pagado1['Monto']);
			$cp = $f_pagado1['cod_partida'];
			$pr = $f_pagado1['Periodo'];
			$paso = 1;
			$montoReintegro = $montoReintegro + $r_01;
		  }
	    }
		
	  }
	    $montoPagadoTotal1 = number_format($montoPagado1,2,',','.');
	 /// ************************************************************
   }
   
    /// ************ Calculo Monto Ajustado / Disponibilidad Presupuestaria / Disponibilidad Financiera
	$montoPptoAjustado1 = $montoDisPresu + $montoReintegro + $montoIncremento1 - $montoDisminucion1;
	$montoDisponPresupuestaria1 = $montoPptoAjustado1 - $montoCompromiso1;
	$montoDisponFinanciera1 = (($montoDisFinan - $montoDisPresu) + $montoPptoAjustado1) - $montoPagado1;
	$montoVariacion1 = $montoDisponFinanciera1 - $montoDisponPresupuestaria1;
	
	$montoPptoAjustado1 = number_format($montoPptoAjustado1,2,',','.');
	$montoDisponPresupuestaria1 = number_format($montoDisponPresupuestaria1,2,',','.');
	$montoDisponFinanciera1 = number_format($montoDisponFinanciera1,2,',','.');
	$montoVariacion1 = number_format($montoVariacion1,2,',','.');
	/// ************ 
	
    $montoDP=number_format($montoDisPresu,2,',','.');
	$montoDF=number_format($montoDisFinan,2,',','.');
	
	$montoReintegro1 = number_format($montoReintegro,2,',','.');
	
	$codigo_partida = $fpar['cod_partida'];
	$pCapturada = $fpar['partida1'];
	/// Monto Incrementado
	$montoInc = number_format($montoConsulta01,2,',','.');
	///**** mostrando los resultados para partida 
	$pdf->SetFillColor(202, 202, 202); 
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetWidths(array(18, 80,20,20,20,20,20,20,20,20,20,20,20,20));
	$pdf->SetAligns(array('C','L','R','R','R','R','R','R','R','R','R','R','R','R'));
	$pdf->Row(array($codigo_partida,$fpar['denominacion'],$montoDP,$montoDF,$montoReintegro1,$montoIncrementoTotal1,$montoDisminucionTotal1,$montoPptoAjustado1,$montoCompromisoTotal1,$montoCausadoTotal1,$montoPagadoTotal1,$montoDisponPresupuestaria1,$montoDisponFinanciera1,$montoVariacion1));
  }
 }
 //// ----------------------------------------------------------------------------------------------- */
 //// **** **** **** Capturando Partida Tipo "T" 301-01-00-00
 //// ----------------------------------------------------------------------------------------------- */
 $montoIncremento2 = 0; $montoDisminucion2 = 0; $montoIncrementoTotal2 = 0; $montoDisminucionTotal2 = 0;
 $montoCompromiso2 = 0; $montoCompromisoTotal2 = 0;
 $montoCausado2 = 0; $montoCausadoTotal2 = 0;
 $montoPagado2 = 0; $montoPagadoTotal2 = 0;	
 $montoDispPresu2 = 0;
 $montoDispFinan2 = 0;
 if(($fieldet['partida']!=00) and (($cont2==0) or ($gCapturada!=$fieldet['generica']) or ($pCapturada2!=$fieldet['partida']))){
    $sql2="SELECT cod_partida,partida1,denominacion,cod_tipocuenta,generica,tipo 
			    FROM pv_partida 
			   WHERE partida1='".$fieldet['partida']."' AND
				     cod_tipocuenta='".$fieldet['tipocuenta']."' AND
				     tipo='T' AND 
					 generica='".$fieldet['generica']."' AND 
					 especifica='00'";
	$qry2=mysql_query($sql2) or die ($sql2.mysql_error());
	$rows2=mysql_num_rows($qry2);//$pdf->Cell(5,3.5,$rwPar2);
	$montoReintegro2 = 0;
	if($rows2!=0){
	  $fpar2=mysql_fetch_array($qry2);
	  $montoG=0; $cont2=0; $montoConsulta02=0;
	   $sqldet2="SELECT MontoAprobado, cod_partida, CodPresupuesto, Organismo 
			      FROM pv_presupuestodet 
			     WHERE partida='".$fpar2['partida1']."' AND 
				       generica='".$fpar2['generica']."' AND 
					   tipocuenta='".$fpar2['cod_tipocuenta']."' AND 
				       CodPresupuesto='".$fieldet['CodPresupuesto']."'";
	  $qrydet2=mysql_query($sqldet2) or die ($sqldet2.mysql_error());
	  $rwdet2=mysql_num_rows($qrydet2);
	  for($b=0; $b<$rwdet2; $b++){
	   $fdet2=mysql_fetch_array($qrydet2);
	   $cont2 = $cont2 + 1;
	   //// ------------------------------------------------------------------
	   //// Consulta de secomparacion de partidas tomando en consideracion el periodo 
	   $s_ejecucion="SELECT
				           DispPresupuestaria,
				           DispFinanciera 
		               FROM 
			               pv_ejecucionpresupuestaria
		              WHERE 
			               Periodo='$fperiodo' and 
				           CodPresupuesto = '".$fdet2['CodPresupuesto']."' and 
						   CodPartida = '".$fdet2['cod_partida']."' and 
						   CodOrganismo = '".$fdet2['Organismo']."'"; //echo $s_ejecucion;
	  $q_ejecucion=mysql_query($s_ejecucion) or die ($s_ejecucion.mysql_error());
	  $r_ejecucion=mysql_num_rows($q_ejecucion);
	  if($r_ejecucion!=0) $f_ejecucion=mysql_fetch_array($q_ejecucion);
	  //	EDGAR
	  else {
	   $f_ejecucion['DispPresupuestaria'] = 0;
	   $f_ejecucion['DispFinanciera'] = 0;
	  }
	  //	FIN
	  //// ------------------------------------------------------------------
	   
	   $montoDispPresu2 = $montoDispPresu2 + $f_ejecucion['DispPresupuestaria'];
	   $montoDispFinan2 = $montoDispFinan2 + $f_ejecucion['DispFinanciera'];
	   
	   /// *************************************************************************
	   $s_consulta02 = "select 
						  aj.CodAjuste,
						  aj.TipoAjuste,
						  aj.Periodo,
						  ajdet.cod_partida,
						  ajdet.MontoAjuste
					  from 				   
							pv_ajustepresupuestario aj
                            inner join pv_ajustepresupuestariodet ajdet on ((aj.CodPresupuesto=ajdet.CodPresupuesto)and(aj.CodAjuste=ajdet.CodAjuste))
					where 
					       aj.CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
						   aj.Periodo = '$Periodo' and 
						   ajdet.cod_partida='".$fdet2['cod_partida']."'";	//echo $s_consulta03;				   
						   
	  $q_consulta02 = mysql_query($s_consulta02) or die ($s_consulta02.mysql_error());
	  $r_consulta02 = mysql_num_rows($q_consulta02);
	
      for($c=0; $c<$r_consulta02; $c++){	
	     $f_consulta02 = mysql_fetch_array($q_consulta02); 
	     if($f_consulta02['TipoAjuste']=='IN'){
	       $montoIncremento2 = $f_consulta02['MontoAjuste'] + $montoIncremento2;
	     }else{ 
	       $montoDisminucion2 = $f_consulta02['MontoAjuste'] + $montoDisminucion2;
		 }
	  }
	  $montoIncrementoTotal2 = number_format($montoIncremento2,2,',','.');
	  $montoDisminucionTotal2 = number_format($montoDisminucion2,2,',','.');	
	   /// ************************************************************************* 
	   ///                         MONTO COMPROMETIDO
	   /// ************************************************************************* 
	   list($ano, $mes) = SPLIT('[-]', $Periodo);
	  $s_compromiso2 = "select
	                         Monto 
						from
						     lg_distribucioncompromisos 
						where 
						      Estado = 'CO' and 
							  Periodo = '$Periodo' and 
							  cod_partida = '".$fdet2['cod_partida']."' and 
						      CodOrganismo = '".$fdet2['Organismo']."'";
	  $q_compromiso2 = mysql_query($s_compromiso2) or die ($s_compromiso2.mysql_error());
	  $r_compromiso2 = mysql_num_rows($q_compromiso2);
	  
	  for($c=0;$c<$r_compromiso2; $c++){
		$f_compromiso2 = mysql_fetch_array($q_compromiso2);
		if($f_compromiso2['Monto']>0)$montoCompromiso2 = $montoCompromiso2 + $f_compromiso2['Monto'];
	  }
	  
	  $montoCompromisoTotal2 = number_format($montoCompromiso2,2,',','.');
	   /// ************************************************************************* 
	   ///                            MONTO CAUSADO
	   /// ************************************************************************* 
	   $s_causado2 = "select 
							 *
						 from
							 ap_distribucionobligacion
						 where 
							 Estado='CA' and 
							 Periodo = '$Periodo' and 
							 cod_partida = '".$fdet2['cod_partida']."'";
		$q_causado2 = mysql_query($s_causado2) or die ($s_causado2.mysql_error());
		$r_causado2 = mysql_num_rows($q_causado2);
		
		for($c=0; $c<$r_causado2; $c++){
		  $f_causado2 = mysql_fetch_array($q_causado2);
		  if($f_causado2['Monto']>0)$montoCausado2 =  $montoCausado2 + $f_causado2['Monto'];
		}  
		  $montoCausadoTotal2  = number_format($montoCausado2,2,',','.');
	   /// ************************************************************************* 
	   ///                            MONTO PAGADO
	   /// ************************************************************************* 
	   $s_pagado2 = "select
	                       *
					  from 
					       ap_ordenpagodistribucion 
					 where
					       Estado = 'PA' and 
						   Periodo = '$Periodo' and 
						   cod_partida = '".$fdet2['cod_partida']."'"; //echo $s_pagado2; 
	   $q_pagado2 = mysql_query($s_pagado2) or die ($s_pagado2.mysql_error());
	   $r_pagado2 = mysql_num_rows($q_pagado2);
	   
	   for($c=0; $c<$r_pagado2; $c++){
	     $f_pagado2 = mysql_fetch_array($q_pagado2);
		 if($f_pagado2['Monto']>0)$montoPagado2 = $montoPagado2 + $f_pagado2['Monto'];
		 else{  
		   $r_02 =  -1*($f_pagado2['Monto']);
		   $montoReintegro2 = $montoReintegro2 +  $r_02;
		  }
	   }
	     $montoPagadoTotal2 = number_format($montoPagado2,2,',','.');
	   /// *************************************************************************
	  $montoConsulta02 = $montoConsulta02 + $f_consulta02['MontoAjuste'];
	  }
	  /// ************ Calculo Monto Ajustado / Disponibilidad Presupuestaria
	   $montoPptoAjustado2 = $montoDispPresu2 + $montoReintegro2 + $montoIncremento2 - $montoDisminucion2;
	   $montoDisponPresupuestaria2 = $montoPptoAjustado2 - $montoCompromiso2;
	   $montoDisponFinanciera2 = (($montoDispFinan2 - $montoDispPresu2) + $montoPptoAjustado2) - $montoPagado2;
	   $montoVariacion2 = $montoDisponFinanciera2 - $montoDisponPresupuestaria2;
	   
	   $montoPptoAjustado2 = number_format($montoPptoAjustado2,2,',','.');
	   $montoDisponPresupuestaria2 = number_format($montoDisponPresupuestaria2,2,',','.');
	   $montoDisponFinanciera2 = number_format($montoDisponFinanciera2,2,',','.');
	   $montoVariacion2 = number_format($montoVariacion2,2,',','.');
	  /// ************ 
	   
	   
	   $montoDP2=number_format($montoDispPresu2,2,',','.');
	   $montoDF2=number_format($montoDispFinan2,2,',','.');
	   
	   $montoReintegroT2 = number_format($montoReintegro2,2,',','.');
	  
	  $montoGen=number_format($montoG,2,',','.');
	  $codigo_partida = $fpar2['cod_partida'];
	  $gCapturada = $fpar2['generica'];
	  $pCapturada2 = $fpar2['partida1'];
	  /// Monto Incrementado
	  $montoInc2 = number_format($montoConsulta02,2,',','.');
	  ///**** mostrando los resultados para partida 
	  $pdf->SetFillColor(202, 202, 202);
	  $pdf->SetFont('Arial', 'B', 7);
	  $pdf->SetWidths(array(18, 80,20,20,20,20,20,20,20,20,20,20,20,20));
	  $pdf->SetAligns(array('C','L','R','R','R','R','R','R','R','R','R','R','R','R'));
	  $pdf->Row(array($codigo_partida,$fpar2['denominacion'],$montoDP2,$montoDF2,$montoReintegroT2,$montoIncrementoTotal2,$montoDisminucionTotal2,$montoPptoAjustado2,$montoCompromisoTotal2,$montoCausadoTotal2,$montoPagadoTotal2,$montoDisponPresupuestaria2, $montoDisponFinanciera2,$montoVariacion2));
   }
  }
 //// ----------------------------------------------------------------------------------------------- */
 //// **** **** **** Capturando Partida Tipo "D" 301-01-01-00 ó 301-01-01-01
 //// ----------------------------------------------------------------------------------------------- */
 $montoIncremento3 = 0; $montoDisminucion3 = 0; $montoIncrementoTotal3 = 0; $montoDisminucionTotal3 = 0; 
 $montoCompromisoTotal = 0; $montoCompromiso3 = 0;
 $montoCausado3 = 0; $montoCausadoTotal3 = 0;
 $montoPagado3 = 0; $montoPagadoTotal3 = 0;
 $montoDispPresu3 = 0; 
 $montoDispFinan3 = 0;
 
 if($fieldet['partida']!=00){
     //$pdf->Cell(5,3.5,$fieldet['partida']);
     
     
	 $sql="SELECT denominacion FROM pv_partida WHERE cod_partida='".$fieldet['cod_partida']."'";
	 $qry=mysql_query($sql) or die ($sql.mysql_error());
	 $field=mysql_fetch_array($qry);
	 
	 /// *************************************************************************
	 /// 
	 $s_ejecucion03="SELECT
				           DispPresupuestaria,
				           DispFinanciera 
		               FROM 
			               pv_ejecucionpresupuestaria
		              WHERE 
			               Periodo='$fperiodo' and 
				           CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
						   CodPartida = '".$fieldet['cod_partida']."'"; //echo $s_ejecucion;
	  $q_ejecucion03=mysql_query($s_ejecucion03) or die ($s_ejecucion03.mysql_error());
	  $r_ejecucion03=mysql_num_rows($q_ejecucion03);
	  if($r_ejecucion03!=0) $f_ejecucion03=mysql_fetch_array($q_ejecucion03);
	  //	linea agregada (EDGAR)	--	INICIO
	  else {
		 $f_ejecucion03['DispPresupuestaria'] = 0;
	   	$f_ejecucion03['DispFinanciera'] = 0;
		 }
	  //	--	FIN
	  //// ------------------------------------------------------------------
	   
	   
	   
	   $montoDispPresu3 = $montoDispPresu3 + $f_ejecucion03['DispPresupuestaria'];
	   $montoDispFinan3 = $montoDispFinan3 + $f_ejecucion03['DispFinanciera'];
	   
	   $montoDP3=number_format($montoDispPresu3,2,',','.');
	   $montoDF3=number_format($montoDispFinan3,2,',','.');

	   
	   $montoTotalDP = $montoTotalDP + $montoDispPresu3;
	   $montoTotalDF = $montoTotalDF + $montoDispFinan3;
	   	 
	 /// *************************************************************************
	 ///                      Monto Incrementado y Disminuido
	 /// ************************************************************************* 
	    $s_consulta03 = "select 
						  aj.CodAjuste,
						  aj.TipoAjuste,
						  aj.Periodo,
						  ajdet.cod_partida,
						  ajdet.MontoAjuste
					  from 				   
							pv_ajustepresupuestario aj
                            inner join pv_ajustepresupuestariodet ajdet on ((aj.CodPresupuesto=ajdet.CodPresupuesto)and(aj.CodAjuste=ajdet.CodAjuste))
					where 
					       aj.CodPresupuesto = '".$fieldet['CodPresupuesto']."' and 
						   aj.Periodo = '$Periodo' and 
						   aj.Estado = 'AP' and 
						   ajdet.cod_partida='".$fieldet['cod_partida']."'";		
	  $q_consulta03 = mysql_query($s_consulta03) or die ($s_consulta03.mysql_error());
	  $r_consulta03 = mysql_num_rows($q_consulta03); //echo $r_consulta03;
	
      for($c=0; $c<$r_consulta03; $c++){	
	     $f_consulta03 = mysql_fetch_array($q_consulta03); 
	     if($f_consulta03['TipoAjuste']=='IN'){
	       $montoIncremento3 = $f_consulta03['MontoAjuste'] + $montoIncremento3;
	     }else{ 
	       $montoDisminucion3 = $f_consulta03['MontoAjuste'] + $montoDisminucion3;
		 }
	  }
	  
	  $montoIncrementoTotal3 = number_format($montoIncremento3,2,',','.');
	  $montoDisminucionTotal3 = number_format($montoDisminucion3,2,',','.');	
	  	 
	  $montoInc3 = $montoInc3 + $montoIncremento3;
	  $montoIncTotal = number_format($montoInc3,2,',','.');
	  $montoDism3 = $montoDism3 + $montoDisminucion3;
	  $montoDismTotal = number_format($montoDism3,2,',','.');
	 /// *************************************************************************
	 ///                            MONTO COMPROMETIDO
	 /// ************************************************************************* 
	   list($ano, $mes) = SPLIT('[-]', $Periodo);
	  $s_compromiso3 = "select
	                         Monto 
						from
						     lg_distribucioncompromisos 
						where 
						      Estado='CO' and 
							  Periodo='$Periodo' and 
							  cod_partida = '".$fieldet['cod_partida']."'"; //echo $s_compromiso3;
	  $q_compromiso3 = mysql_query($s_compromiso3) or die ($s_compromiso3.mysql_error());
	  $r_compromiso3 = mysql_num_rows($q_compromiso3);
	  
	  for($c=0;$c<$r_compromiso3; $c++){
		$f_compromiso3 = mysql_fetch_array($q_compromiso3);
		if($f_compromiso3['Monto']>0)$montoCompromiso3 = $montoCompromiso3 + $f_compromiso3['Monto']; 
	  }
	  
	  $montoCompromisoTotal3 = number_format($montoCompromiso3,2,',','.');
	  $montoCompTotal = $montoCompTotal + $montoCompromiso3;
	 /// ************************************************************************* 
	 ///                              MONTO CAUSADO
	 /// ************************************************************************* 
	   $s_causado3 = "select 
							 *
						 from
							 ap_distribucionobligacion
						 where 
							 Estado='CA' and 
							 Periodo = '$Periodo' and 
							 cod_partida = '".$fieldet['cod_partida']."'";
		$q_causado3 = mysql_query($s_causado3) or die ($s_causado3.mysql_error());
		$r_causado3 = mysql_num_rows($q_causado3);
		
		for($c=0; $c<$r_causado3; $c++){
		  $f_causado3 = mysql_fetch_array($q_causado3);
		  if($f_causado3['Monto']>0)$montoCausado3 =  $montoCausado3 + $f_causado3['Monto'];
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
					       Estado='PA' and 
						   Periodo = '$Periodo' and 
						   cod_partida = '".$fieldet['cod_partida']."'"; //echo $s_pagado3;
	   $q_pagado3 = mysql_query($s_pagado3) or die ($s_pagado3.mysql_error());
	   $r_pagado3 = mysql_num_rows($q_pagado3); 
	   $reintegro3 =0;
	   for($c=0; $c<$r_pagado3; $c++){
	     $f_pagado3 = mysql_fetch_array($q_pagado3);
		 if($f_pagado3['Monto']>0)$montoPagado3 = $montoPagado3 + $f_pagado3['Monto'];
	     else{
		   $reintegro3 = -1*($f_pagado3['Monto']);
		   $reintegroT = $reintegroT + $reintegro3;
		 }
	   }
	   //echo 'contPagado3='.$contPagado3.'*'.'contPagadoT='.$contPagadoT.'///';
	     $montoPagadoTotal3 = number_format($montoPagado3,2,',','.');
		 $montoPagaTotal = $montoPagaTotal + $montoPagado3;
	 /// *************************************************************************
	 ///   ************ Calculo Monto Ajustado / Disponibilidad Presupuestaria
	   $montoPptoAjustado3 = ($f_ejecucion03['DispPresupuestaria'] + $reintegro3 + $montoIncremento3) - $montoDisminucion3;
	   $montoPptoAjusTotal = $montoPptoAjusTotal + $montoPptoAjustado3;
	
	/// ************************************************************************* 
	///                  Disponibilidad Presupuestaria
	   $montoDisponPresupuestaria3 = $montoPptoAjustado3 - $montoCompromiso3;
	   $montoDisponPresupuestTotal = $montoDisponPresupuestTotal + $montoDisponPresupuestaria3;
    /// ************************************************************************* 
	///                  Disponibilidad Financiera
	   $montoDisponFinanciera3 = (($f_ejecucion03['DispFinanciera'] - $f_ejecucion03['DispPresupuestaria']) + $montoPptoAjustado3) - $montoPagado3;
	   $montoDisponFinancieraTotal = $montoDisponFinancieraTotal + $montoDisponFinanciera3;
	/// ************************************************************************* 
	///                      Monto Variación  
	   
	   $montoVariacion3=$montoDisponFinanciera3 - $montoDisponPresupuestaria3;
	   if($montoVariacion3<0){$montoVariacion3=$montoVariacion3*(-1);} 
	   $montoVariacionTotal = $montoVariacionTotal + $montoVariacion3;
	/// ************************************************************************* 
	
	   $montoPptoAjustado3 = number_format($montoPptoAjustado3,2,',','.');
	   $montoDisponPresupuestaria3 = number_format($montoDisponPresupuestaria3,2,',','.');
	   $montoDisponFinanciera3 = number_format($montoDisponFinanciera3,2,',','.');
	   $montoVariacion3 = number_format($montoVariacion3,2,',','.');
	   $reintegro_3 = number_format($reintegro3,2,',','.');
	 ///
	 $pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	 $pdf->SetFont('Arial', '', 6.5);
	 $pdf->SetWidths(array(18, 80,20,20,20,20,20,20,20,20,20,20,20,20));
	 $pdf->SetAligns(array('C','L','R','R','R','R','R','R','R','R','R','R','R','R'));
	 $pdf->Row(array($fieldet['cod_partida'],$field['denominacion'],$montoDP3,$montoDF3,$reintegro_3,$montoIncrementoTotal3,$montoDisminucionTotal3,$montoPptoAjustado3,$montoCompromisoTotal3,$montoCausadoTotal3,$montoPagadoTotal3,$montoDisponPresupuestaria3,$montoDisponFinanciera3,$montoVariacion3));
 }
}
	///// *** Mostrar *** /////
	//$montoT=number_format($montoT,2,',','.');
	$reintegroTotal = number_format($reintegroT,2,',','.'); /// Reintegro Total
    $montoTDP=number_format($montoTotalDP,2,',','.');       /// Monto Disponible Presupuestario
    $montoTDF=number_format($montoTotalDF,2,',','.');       /// Monto Disponible Financiero
	$montoCompTotal = number_format($montoCompTotal,2,',','.'); /// Total Comprometido
	$montoCausTotal = number_format($montoCausTotal,2,',','.'); /// Total Causado
	$montoPagaTotal = number_format($montoPagaTotal,2,',','.'); /// Total Pagado
	$montoPptoAjusTotal = number_format($montoPptoAjusTotal,2,',','.'); /// 
	$montoDisponPresupuestTotal = number_format($montoDisponPresupuestTotal,2,',','.');
	$montoDisponFinancieraTotal = number_format($montoDisponFinancieraTotal,2,',','.');
	$montoVariacionTotal = number_format($montoVariacionTotal,2,',','.');
	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(202, 202, 202); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8.5);
	 $pdf->SetWidths(array(18, 80,20,20,20,20,20,20,20,20,20,20,20,20));
	$pdf->SetAligns(array('C','R','R','R','R','R','R','R','R','R','R','R','R','R'));
	$pdf->Row(array('' ,'Total:',$montoTDP,$montoTDF,$reintegroTotal,$montoIncTotal,$montoDismTotal,$montoPptoAjusTotal,$montoCompTotal,$montoCausTotal,$montoPagaTotal,$montoDisponPresupuestTotal,$montoDisponFinancieraTotal,$montoVariacionTotal));
	///// 
//// --------------------------------------------------------------------------------- */
}
//---------------------------------------------------*/
/*
$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(202, 202, 202); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(100,10,'',0,1,'L');
	$pdf->Cell(100,10,'ELABORADO POR:',0,0,'L');$pdf->Cell(120,10,'REVISADO POR:',0,0,'L');$pdf->Cell(100,10,'CONFORMADO POR:',0,1,'L');
	$pdf->Cell(100,5,'',0,0,'L');$pdf->Cell(120,5,'',0,0,'L');$pdf->Cell(100,5,'',0,1,'L');
	$pdf->Cell(100,5,'T.S.U. MARIANA SALAZAR',0,0,'L');$pdf->Cell(120,5,'LCDA. AMARILIS GONZALEZ',0,0,'L');$pdf->Cell(100,5,'LCDA. ROSIS REQUENA',0,1,'L');
	$pdf->Cell(100,2,'ASISTENTE DE PRESUPUESTO I',0,0,'L');$pdf->Cell(120,2,'DIRECTOR(A) DE LA DIRECCION DE ADMINISTRACION Y SERVICIOS (E)',0,0,'L');$pdf->Cell(100,2,'DIRECTORA GENERAL',0,1,'L');
*/
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
	 $pdf->Row(array(utf8_decode ('Lcda: Sorielma Salmerón'),'Lcda: Roxaida Estrada','Lcdo. Cesar Granado'));
	 $pdf->Row(array(utf8_decode ('JEFE  DIVISIÓN  DE PRESUPUESTO Y CONTABILIDAD'),utf8_decode ('DIRECTORA DE ADMINISTRACIÓN Y PRESUPUESTO (E)'),'DIRECTOR GENERAL'));
	

$pdf->Output();
?>  
