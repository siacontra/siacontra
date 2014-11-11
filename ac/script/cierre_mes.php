<?
$dbhost="localhost";  // host del MySQL (generalmente localhost)
 $dbusuario="root"; // ingresar el nombre de usuario para acceder a la base
 $dbpassword="root"; // password de acceso para el usuario
 $db="siaceda";        // Seleccionamos la base con la cual trabajar
 $conexion = mysql_connect($dbhost, $dbusuario, $dbpassword);
mysql_select_db($db, $conexion);
////////////////////////////////////////////////////
?>
<table>
<tr>
  <td width='100' align='center'>COD_PARTIDA</td>
  <td width='150' align='center'>DISP PRESUP</td>
  <td width='150' align='center'>DISP FINANC</td>
  <td width='150' align='center'>INCREMENTO</td>
  <td width='150' align='center'>DISMINUCION</td>
  <td width='150' align='center'>PRE AJUSTADO</td>
  <td width='150' align='center'>COMPROMETIDO</td>
  <td width='150' align='center'>CAUSADO</td>
  <td width='150' align='center'>PAGADO</td>
  <td width='150' align='center'>MONTO DISP PRES</td>
  <td width='150' align='center'>MONTO DISP FINA</td>
  <td></td>
  <td></td>
</tr>
<tr>
<?
$mes = 01;
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
		        CodPresupuesto='0002' and 
				Organismo = '0001'
		  ORDER BY cod_partida"; //echo $sqlDet;
$qryDet=mysql_query($sqlDet) or die ($sqlDet.mysql_error());
$rows=mysql_num_rows($qryDet); //echo $rows;
for($i=0; $i<$rows ; $i++){
 $fieldet=mysql_fetch_array($qryDet);
 //// --------------------------------------------------------------------------------
 //// **** **** **** **** Capturando Partida Tipo "T" 301-00-00-00
 //// --------------------------------------------------------------------------------
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
  $rwPar=mysql_num_rows($qryPar);
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
	/// ************************************************************
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
						   aj.Periodo = '2012-01' and 
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
	///                     MONTO COMPROMISO
	/// ************************************************************
	  list($ano, $mes) = SPLIT('[-]', $Periodo);
	  $s_compromiso1 = "select
	                         Monto 
						from
						     lg_distribucioncompromisos 
						where 
						      Estado <> 'AN' and 
							  Periodo = '2012-01' and 
							  cod_partida = '".$fdet['cod_partida']."'";
	  $q_compromiso1 = mysql_query($s_compromiso1) or die ($s_compromiso1.mysql_error());
	  $r_compromiso1 = mysql_num_rows($q_compromiso1);
	  
	  for($c=0;$c<$r_compromiso1; $c++){
		$f_compromiso1 = mysql_fetch_array($q_compromiso1);
	    $montoCompromiso1 = $montoCompromiso1 + $f_compromiso1['Monto'];
	  }
	  
	  $montoCompromisoTotal1 = number_format($montoCompromiso1,2,',','.');
	/// ************************************************************
	///                      MONTO CAUSADO
	/// ************************************************************
	 $s_causado1 = "select 
	                     *
					 from
					     ap_distribucionobligacion
					 where 
					     Estado<>'AN' and 
						 Periodo = '2012-01' and 
						 cod_partida = '".$fdet['cod_partida']."'";
	$q_causado1 = mysql_query($s_causado1) or die ($s_causado1.mysql_error());
	$r_causado1 = mysql_num_rows($q_causado1);
	
	for($c=0; $c<$r_causado1; $c++){
	  $f_causado1 = mysql_fetch_array($q_causado1);
	  $montoCausado1 =  $montoCausado1 + $f_causado1['Monto'];
	}  
	  $montoCausadoTotal1  = number_format($montoCausado1,2,',','.');
	/// ************************************************************
	///                      MONTO PAGADO
	/// ************************************************************
	 $s_pagado1 = "select
	                       *
					  from 
					       ap_ordenpagodistribucion 
					 where
					       Estado = 'PA' and 
						   Periodo = '2012-01' and 
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
	/*$pdf->SetFillColor(202, 202, 202); 
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetWidths(array(18, 80,20,20,20,20,20,20,20,20,20,20,20,20));
	$pdf->SetAligns(array('C','L','R','R','R','R','R','R','R','R','R','R','R','R'));
	$pdf->Row(array($codigo_partida,$fpar['denominacion'],$montoPar,$montoPar,'0,00',$montoIncrementoTotal1,$montoDisminucionTotal1,$montoPptoAjustado1,$montoCompromisoTotal1,$montoCausadoTotal1,$montoPagadoTotal1,$montoDisponPresupuestaria1,$montoDisponFinanciera1,$montoVariacion1));*/
	echo"<tr>
	  <td>$codigo_partida</td>
	  <td align='right'>$montoPar</td>
	  <td align='right'>$montoPar</td>
	  <td align='right'>$montoIncrementoTotal1</td>
	  <td align='right'>$montoDisminucionTotal1</td>
	  <td align='right'>$montoPptoAjustado1</td>
	  <td align='right'>$montoCompromisoTotal1</td>
	  <td align='right'>$montoCausadoTotal1</td>
	  <td align='right'>$montoPagadoTotal1</td>
	  <td align='right'>$montoDisponPresupuestaria1</td>
	  <td align='right'>$montoDisponFinanciera1</td>
	  <td>**************</td>
	</tr>";
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
					 especifica='00'"; //echo $sql2;
	$qry2=mysql_query($sql2) or die ($sql2.mysql_error());
	$rows2=mysql_num_rows($qry2); //echo $rows2;
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
						   aj.Periodo = '2012-01' and 
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
	   ///                    MONTO COMPROMETIDO
	   /// ************************************************************
	   list($ano, $mes) = SPLIT('[-]', $Periodo);
	  $s_compromiso2 = "select
	                         Monto 
						from
						     lg_distribucioncompromisos 
						where 
						      Estado <> 'AN' and 
							  Periodo = '2012-01' and 
							  cod_partida = '".$fdet2['cod_partida']."'";
	  $q_compromiso2 = mysql_query($s_compromiso2) or die ($s_compromiso2.mysql_error());
	  $r_compromiso2 = mysql_num_rows($q_compromiso2);
	  
	  for($c=0;$c<$r_compromiso2; $c++){
		$f_compromiso2 = mysql_fetch_array($q_compromiso2);
	    $montoCompromiso2 = $montoCompromiso2 + $f_compromiso2['Monto'];
	  }
	  
	  $montoCompromisoTotal2 = number_format($montoCompromiso2,2,',','.');
	   /// ************************************************************
	   ///                    MONTO CAUSADO
	   /// ************************************************************
	   $s_causado2 = "select 
							 *
						 from
							 ap_distribucionobligacion
						 where 
							 Estado<>'AN' and 
							 Periodo = '2012-01' and 
							 cod_partida = '".$fdet2['cod_partida']."'";
		$q_causado2 = mysql_query($s_causado2) or die ($s_causado2.mysql_error());
		$r_causado2 = mysql_num_rows($q_causado2);
		
		for($c=0; $c<$r_causado2; $c++){
		  $f_causado2 = mysql_fetch_array($q_causado2);
		  $montoCausado2 =  $montoCausado2 + $f_causado2['Monto'];
		}  
		  $montoCausadoTotal2  = number_format($montoCausado2,2,',','.');
	   /// ************************************************************
	   ///                      MONTO PAGADO
	   /// ************************************************************
	   $s_pagado2 = "select
	                       *
					  from 
					       ap_ordenpagodistribucion 
					 where
					       Estado = 'PA' and 
						   Periodo = '2012-01' and 
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
	  /*$pdf->SetFillColor(202, 202, 202);
	  $pdf->SetFont('Arial', 'B', 7);
	  $pdf->SetWidths(array(18, 80,20,20,20,20,20,20,20,20,20,20,20,20));
	  $pdf->SetAligns(array('C','L','R','R','R','R','R','R','R','R','R','R','R','R'));
	  $pdf->Row(array($codigo_partida,$fpar2['denominacion'],$montoGen,$montoGen,'0,00',$montoIncrementoTotal2,$montoDisminucionTotal2,$montoPptoAjustado2,$montoCompromisoTotal2,$montoCausadoTotal2,$montoPagadoTotal2,$montoDisponPresupuestaria2, $montoDisponFinanciera2,$montoVariacion2));*/
	  echo"<tr>
	  <td>$codigo_partida</td>
	  <td align='right'>$montoGen</td>
	  <td align='right'>$montoGen</td>
	  <td align='right'>$montoIncrementoTotal2</td>
	  <td align='right'>$montoDisminucionTotal2</td>
	  <td align='right'>$montoPptoAjustado2</td>
	  <td align='right'>$montoCompromisoTotal2</td>
	  <td align='right'>$montoCausadoTotal2</td>
	  <td align='right'>$montoPagadoTotal2</td>
	  <td align='right'>$montoDisponPresupuestaria2</td>
	  <td align='right'>$montoDisponFinanciera2</td>
	  <td>***</td>
	</tr>";
   }
  }
 //// **** **** **** Capturando Partida Tipo "D" 301-01-01-00 ó 301-01-01-01
 $montoIncremento3 = 0; $montoDisminucion3 = 0; $montoIncrementoTotal3 = 0; $montoDisminucionTotal3 = 0; 
 $montoCompromisoTotal = 0; $montoCompromiso3 = 0;
 $montoCausado3 = 0; $montoCausadoTotal3 = 0;
 $montoPagado3 = 0; $montoPagadoTotal3 = 0;
 if($fieldet['partida']!=00){
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
						   aj.Periodo = '2012-01' and 
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
							  Periodo = '2012-01' and 
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
							 Periodo = '2012-01' and 
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
						   Periodo = '2012-01' and 
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
	   $montoVariacionTotal = $montoVariacionTotal + $montoVariacion3;
	  /// ************ 
	   $montoPptoAjustado3 = number_format($montoPptoAjustado3,2,',','.');
	   $montoDisponPresupuestaria3 = number_format($montoDisponPresupuestaria3,2,',','.');
	   $montoDisponFinanciera3 = number_format($montoDisponFinanciera3,2,',','.');
	   $montoVariacion3 = number_format($montoVariacion3,2,',','.');
	 ///
	 /*$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	 $pdf->SetFont('Arial', '', 6.5);
	 $pdf->SetWidths(array(18, 80,20,20,20,20,20,20,20,20,20,20,20,20));
	 $pdf->SetAligns(array('C','L','R','R','R','R','R','R','R','R','R','R','R','R'));
	 $pdf->Row(array($fieldet['cod_partida'],$field['denominacion'],$monto,$monto,'0,00',$montoIncrementoTotal3,$montoDisminucionTotal3,$montoPptoAjustado3,$montoCompromisoTotal3,$montoCausadoTotal3,$montoPagadoTotal3,$montoDisponPresupuestaria3,$montoDisponFinanciera3,$montoVariacion3));*/
	echo"<tr>
	  <td>".$fieldet['cod_partida']."</td>
	  <td align='right'>$monto</td>
	  <td align='right'>$monto</td>
	  <td align='right'>$montoIncrementoTotal3</td>
	  <td align='right'>$montoDisminucionTotal3</td>
	  <td align='right'>$montoPptoAjustado3</td>
	  <td align='right'>$montoCompromisoTotal3</td>
	  <td align='right'>$montoCausadoTotal3</td>
	  <td align='right'>$montoPagadoTotal3</td>
	  <td align='right'>$montoDisponPresupuestaria3</td>
	  <td align='right'>$montoDisponFinanciera3</td>
	  <td></td>
	</tr>"; 
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
	
	/*$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(202, 202, 202); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8.5);
	 $pdf->SetWidths(array(18, 80,20,20,20,20,20,20,20,20,20,20,20,20));
	$pdf->SetAligns(array('C','R','R','R','R','R','R','R','R','R','R','R','R','R'));
	$pdf->Row(array('' ,'Total:',$montoTotal,$montoTotal,'0,00',$montoIncTotal,$montoDismTotal,$montoPptoAjusTotal,$montoCompTotal,$montoCausTotal,$montoPagaTotal,$montoDisponPresupuestTotal,$montoDisponFinancieraTotal,$montoVariacionTotal));*/
	/////
}
  echo"<tr>
	  <td>TOTAL = </td>
	  <td>$montoTotal</td>
	  <td>$montoTotal</td>
	  <td>$montoIncTotal</td>
      <td>$montoDismTotal</td>
	  <td>$montoPptoAjusTotal</td>
	  <td>$montoCompTotal</td>
	  <td>$montoCausTotal</td>
	  <td>$montoPagaTotal</td>
	  <td>$montoDisponPresupuestTotal</td>
	  <td>$montoDisponFinancieraTotal</td>
	  <td>---</td>
	</tr>";
?>
</tr>
</table>