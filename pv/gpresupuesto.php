<?php /// SOLO PARA AJUSTE DE PRESUPUESTO
 $year=date("Y");
 $ahora=date("Y-m-d H:i:s");
 $fAjuste=date("Y-m-d"); //$fAjuste = date("Y-m-d",strtotime($fAjuste);
 $fechaActual=date("Y-m-d");// ** UTILIZADO PARA FECHA DE AJUSTE Y FECHA DE PREPARACION
 extract($_POST);
 extract($_GET);
//// ----------------------------------------------------------------------------------------------- ////
//// 		                           GUARDAR NUEVO AJUSTE
//// ----------------------------------------------------------------------------------------------- ////
if(($_GET['accion']=="GuardarAjuste")or($_POST['accion']=="GuardarAjuste")){
	//echo "Paso";
 $sql="SELECT MAX(CodAjuste) 
         FROM 
		      pv_ajustepresupuestario 
	    WHERE 
		      CodPresupuesto='".$_POST['num_presupuesto']."' AND
			  Organismo = '".$_POST['Org']."'";
 $qry=mysql_query($sql) or die ($sql.mysql_error());
 $row=mysql_num_rows($qry);
 
 if($row!=0) $field=mysql_fetch_array($qry);
 
 $CodAjuste=(int)($field[0]+1);
 $CodAjuste= (string) str_repeat("0",4-strlen($CodAjuste)).$CodAjuste;
 
 if($_POST['fgaceta']!=''){$fgaceta=$_POST['fgaceta']; $fgaceta=date("Y-m-d",strtotime($fgaceta));}else{$fgaceta='';}
 if($_POST['fresolucion']!=''){$fresolucion=$_POST['fresolucion']; $fresolucion=date("Y-m-d",strtotime($fresolucion));}else{$fresolucion='';}
 
 
 $total_ajuste = cambioFormat($_POST['total_ajuste']);
 $fAjuste=date("Y-m-d"); 
 ////// _________________ Insertando datos generales de ajuste creado en pv_ajustepresupuestario
 $s_insert="INSERT INTO pv_ajustepresupuestario(Organismo,
                                           CodPresupuesto,
										   CodAjuste,
										   FechaAjuste,
										   TipoAjuste,
										   NumGaceta,
										   FechaGaceta,
										   NumResolucion,
										   FechaResolucion,
										   Descripcion,
										   TotalAjuste,
										   PreparadoPor,
										   FechaPreparacion,
										   AprobadoPor,
										   Estado,
										   Periodo,
										   UltimaFechaModif,
										   UltimoUsuario,
										   MotivoAjuste) 
             VALUE ('".$_POST['Org']."',
			        '".$_POST['num_presupuesto']."',
			        '$CodAjuste',
					'$fAjuste',
					'".$_POST['tAjuste']."',
					'".$_POST['gaceta']."',
					'$fgaceta',
					'".$_POST['nresolucion']."',
					'$fresolucion',
					'".$_POST['descripcion']."',
					'$total_ajuste',
					'".$_POST['cod_preparado']."',
					'".date("Y-m-d")."',
					'".$_POST['nomempleado']."',
					'PR',
					'".$_POST['fperiodo']."',
					'".date("Y-m-d H:i:s")."',
					'".$_SESSION['USUARIO_ACTUAL']."',
					'".$_POST['motivoAjuste']."')"; //echo $s_insert;
 $q_insert=mysql_query($s_insert) or die ($s_insert.mysql_error());	

////// _________________ Insertando datos detalle de ajuste creado en pv_ajustepresupuestariodet
$cont=0;
list($d, $m, $a)= split('[-]',$_POST['fAjuste']);
$fechaAjuste = $a.'-'.$m.'-'.$d;
$SQLDET="SELECT 
                MAX(CodAjuste) 
           FROM 
		        pv_ajustepresupuestario 
          WHERE 
		        FechaAjuste = '$fechaAjuste' and 
				CodPresupuesto = '".$_POST['num_presupuesto']."' and 
				Organismo = '".$_POST['Org']."'"; //echo $SQLDET;
$QRYDET= mysql_query($SQLDET) or die ($SQLDET.mysql_error());
$ROWS=mysql_num_rows($QRYDET); /// echo $SQLDET; echo $ROWS;
if($ROWS!=0){
  $FIELD=mysql_fetch_array($QRYDET);
  $SPDET="SELECT * 
            FROM pv_presupuestodet 
	       WHERE Organismo='".$_POST['Org']."' AND 
				 CodPresupuesto='".$_POST['num_presupuesto']."'";
  $QPDET=mysql_query($SPDET) or die ($SPDET.mysql_error());
  $RPDET=mysql_num_rows($QPDET);
  $TotalMonto=0;
  for($i; $i<$RPDET; $i++){ //echo"--RPDET = $RPDET $i";
    $FPDET=mysql_fetch_array($QPDET);
	$montoDisponible = $FPDET['MontoAjustado'] - $FPDET['MontoCompromiso'];
    $id=$FPDET['Secuencia'];
    $monto=$_POST[$id]; //echo"---Monto = $monto";
	  if(($monto!='')and($monto!='0,00')){
	   $SAJDET="SELECT * FROM pv_ajustepresupuestariodet 
	                    WHERE Organismo='".$_POST['Org']."' AND
						      CodPresupuesto='".$_POST['num_presupuesto']."' AND
							  CodAjuste='".$FIELD['0']."' AND 
							  cod_partida='".$FPDET['cod_partida']."'"; //echo $SAJDET;
	   $QAJDET=mysql_query($SAJDET) or die ($SAJDET.mysql_error());
	   if(mysql_num_rows($QAJDET)=='0'){
	     $monto=cambioFormat($monto);
	     $montoA= $montoA + $monto;
		 $SAJDET2="INSERT INTO pv_ajustepresupuestariodet (Organismo,
		  												   CodPresupuesto,
														   CodAjuste,
														   cod_partida,
														   Estado,
														   MontoDisponible,
														   MontoAjuste,
														   UltimoUsuario,
														   UltimaFechaModif)
												   VALUES ('".$_POST['Org']."',
												           '".$_POST['num_presupuesto']."',
														   '".$CodAjuste."',
														   '".$FPDET['cod_partida']."',
														   'PR',
														   '$montoDisponible',
														   '$monto',
														   '".$_SESSION['USUARIO_ACTUAL']."',
														   '".date("Y-m-d H:i:s")."')"; //echo $SAJDET2;
		 $QAJDET2=mysql_query($SAJDET2) or die ($SAJDET2.mysql_error());
}}}
    
//// **** GUARDAR TOTAL AJUSTE EN PV_AJUSTEPRESUPUESTARIO
		
		 $SAJU="UPDATE 
		               pv_ReintegroPresupuestario 
		           SET 
				       TotalAjuste='".$TotalMonto."' 
		         WHERE 
				       Organismo='".$_POST['Org']."' AND
					   CodPresupuesto='".$_POST['num_presupuesto']."' AND
					   CodReintegro='".$FIELD['0']."'";
		 $QAJU=mysql_query($SAJU) or die ($SAJU.mysql_error());
           
           }
}
//// ----------------------------------------------------------------------------------------------- ////
//// ----------------------------------------------------------------------------------------------- ////
//// 		                           GUARDAR NUEVO AJUSTE
//// ----------------------------------------------------------------------------------------------- ////
if(($_GET['accion']=="GuardarReintegro")or($_POST['accion']=="GuardarReintegro")){
    
    
	//echo "Paso";
 $sql="SELECT MAX(CodReintegro) 
         FROM 
		      pv_ReintegroPresupuestario 
	    WHERE 
		      CodPresupuesto='".$_POST['num_presupuesto']."' AND
			  Organismo = '".$_POST['Org']."'";
 $qry=mysql_query($sql) or die ($sql.mysql_error());
 $row=mysql_num_rows($qry);
 
 if($row!=0) $field=mysql_fetch_array($qry);
 
 $CodAjuste=(int)($field[0]+1);
 $CodAjuste= (string) str_repeat("0",4-strlen($CodAjuste)).$CodAjuste;
 
 if($_POST['fgaceta']!=''){$fgaceta=$_POST['fgaceta']; $fgaceta=date("Y-m-d",strtotime($fgaceta));}else{$fgaceta='';}
 if($_POST['fresolucion']!=''){$fresolucion=$_POST['fresolucion']; $fresolucion=date("Y-m-d",strtotime($fresolucion));}else{$fresolucion='';}
 
 
 $total_ajuste = cambioFormat($_POST['total_ajuste']);
 $fAjuste=date("Y-m-d"); 
 ////// _________________ Insertando datos generales de ajuste creado en pv_ReintegroPresupuestario
 $s_insert="INSERT INTO pv_ReintegroPresupuestario(Organismo,
                                           CodPresupuesto,
										   CodReintegro,
										   FechaAjuste,
										   NumGaceta,
										   FechaGaceta,
										   NumResolucion,
										   FechaResolucion,
										   Descripcion,
										   TotalAjuste,
										   PreparadoPor,
										   FechaPreparacion,
										   AprobadoPor,
										   Estado,
										   Periodo,
										   UltimaFechaModif,
										   UltimoUsuario) 
             VALUE ('".$_POST['Org']."',
			        '".$_POST['num_presupuesto']."',
			        '$CodAjuste',
					'$fAjuste',
					'".$_POST['gaceta']."',
					'$fgaceta',
					'".$_POST['nresolucion']."',
					'$fresolucion',
					'".$_POST['descripcion']."',
					'$total_ajuste',
					'".$_POST['cod_preparado']."',
					'".date("Y-m-d")."',
					'".$_POST['nomempleado']."',
					'PR',
					'".$_POST['fperiodo']."',
					'".date("Y-m-d H:i:s")."',
					'".$_SESSION['USUARIO_ACTUAL']."')"; //echo $s_insert;
 $q_insert=mysql_query($s_insert) or die ($s_insert.mysql_error());	

////// _________________ Insertando datos detalle de ajuste creado en pv_ReintegroPresupuestariodet
$cont=0;
list($d, $m, $a)= split('[-]',$_POST['fAjuste']);
$fechaAjuste = $a.'-'.$m.'-'.$d;
$SQLDET="SELECT 
                MAX(CodReintegro) 
           FROM 
		        pv_ReintegroPresupuestario 
          WHERE 
		        FechaAjuste = '$fechaAjuste' and 
				CodPresupuesto = '".$_POST['num_presupuesto']."' and 
				Organismo = '".$_POST['Org']."'"; //echo $SQLDET;
$QRYDET= mysql_query($SQLDET) or die ($SQLDET.mysql_error());
$ROWS=mysql_num_rows($QRYDET); /// echo $SQLDET; echo $ROWS;
if($ROWS!=0){
  $FIELD=mysql_fetch_array($QRYDET);
  $SPDET="SELECT * 
            FROM pv_presupuestodet 
	       WHERE Organismo='".$_POST['Org']."' AND 
				 CodPresupuesto='".$_POST['num_presupuesto']."'";
  $QPDET=mysql_query($SPDET) or die ($SPDET.mysql_error());
  $RPDET=mysql_num_rows($QPDET);
  $TotalMonto=0;
  for($i; $i<$RPDET; $i++){ //echo"--RPDET = $RPDET $i";
    $FPDET=mysql_fetch_array($QPDET);
	$montoDisponible = $FPDET['MontoAjustado'] - $FPDET['MontoCompromiso'];
    $id=$FPDET['Secuencia'];
    $monto=$_POST[$id]; //echo"---Monto = $monto";
	  if(($monto!='')and($monto!='0,00')){
	   $SAJDET="SELECT * FROM pv_ReintegroPresupuestariodet 
	                    WHERE Organismo='".$_POST['Org']."' AND
						      CodPresupuesto='".$_POST['num_presupuesto']."' AND
							  CodReintegro='".$FIELD['0']."' AND 
							  cod_partida='".$FPDET['cod_partida']."'"; //echo $SAJDET;
	   $QAJDET=mysql_query($SAJDET) or die ($SAJDET.mysql_error());
	   if(mysql_num_rows($QAJDET)=='0'){
	     $monto=cambioFormat($monto);
	     $montoA= $montoA + $monto;
		 $SAJDET2="INSERT INTO pv_ReintegroPresupuestariodet (Organismo,
		  												   CodPresupuesto,
														   CodReintegro,
														   cod_partida,
														   Estado,
														   MontoDisponible,
														   MontoReintegro,
														   UltimoUsuario,
														   UltimaFechaModif)
												   VALUES ('".$_POST['Org']."',
												           '".$_POST['num_presupuesto']."',
														   '".$CodAjuste."',
														   '".$FPDET['cod_partida']."',
														   'PR',
														   '$montoDisponible',
														   '$monto',
														   '".$_SESSION['USUARIO_ACTUAL']."',
														   '".date("Y-m-d H:i:s")."')"; //echo $SAJDET2;
                 $TotalMonto=$TotalMonto+$monto;
		 $QAJDET2=mysql_query($SAJDET2) or die ($SAJDET2.mysql_error());
                 
}}}
//// **** GUARDAR TOTAL AJUSTE EN PV_AJUSTEPRESUPUESTARIO
		
		 $SAJU="UPDATE 
		               pv_ReintegroPresupuestario 
		           SET 
				       TotalAjuste='".$TotalMonto."' 
		         WHERE 
				       Organismo='".$_POST['Org']."' AND
					   CodPresupuesto='".$_POST['num_presupuesto']."' AND
					   CodReintegro='".$FIELD['0']."'";
		 $QAJU=mysql_query($SAJU) or die ($SAJU.mysql_error());
           
           }
}
//// ----------------------------------------------------------------------------------------------- ////
//// ----------------------------------------------------------------------------------------------- ////
///////// *********  GUARDA AJUSTEDETALLE EN TABLA PV_AJUSTEPRESUPUESTARIODET  ***** ////////////
//// ----------------------------------------------------------------------------------------------- ////
if($accion=="GuardarAjusteDetalle"){ 
$cont=0;
  $SQL="SELECT * FROM pv_ajustepresupuestario 
                WHERE CodAjuste='".$_POST['codajuste']."' AND 
				      CodPresupuesto='".$_POST['npresupuesto']."' AND
					  Organismo='".$_POST['organismo']."'";
  $QRY= mysql_query($SQL) or die ($SQL.mysql_error());
  $ROWS=mysql_num_rows($QRY);
  if($ROWS!=0){
    $FIELD=mysql_fetch_array($QRY);
	$SPDET="SELECT * FROM pv_presupuestodet 
	                WHERE Organismo='".$FIELD['Organismo']."' AND 
					      CodPresupuesto='".$FIELD['CodPresupuesto']."'";
	$QPDET=mysql_query($SPDET) or die ($SPDET.mysql_error());
	$RPDET=mysql_num_rows($QPDET);
	for($i; $i<$RPDET; $i++){ //echo"RPDET = $RPDET $i";
	  $FPDET=mysql_fetch_array($QPDET);
	  $id=$FPDET['Secuencia'];
	  $monto=$_POST[$id]; //echo"Monto = $monto";
	  if(($monto!='')and($monto!='0.00')){
	   $SAJDET="SELECT * FROM pv_ajustepresupuestariodet 
	                    WHERE Organismo='".$FPDET['Organismo']."' AND
						      CodPresupuesto='".$FPDET['CodPresupuesto']."' AND
							  CodAjuste='".$FIELD['CodAjuste']."' AND 
							  cod_partida='".$FPDET['cod_partida']."'";
	   $QAJDET=mysql_query($SAJDET) or die ($SAJDET.mysql_error());
	   if(mysql_num_rows($QAJDET)=='0'){
	     $monto=cambioFormat($monto);
	     $montoA=$montoA + $monto;
		 $SAJDET2="INSERT INTO pv_ajustepresupuestariodet (Organismo,
		  												   CodPresupuesto,
														   CodAjuste,
														   cod_partida,
														   Estado,
														   MontoDMA,
														   MontoAjuste,
														   UltimoUsuario,
														   UltimaFechaModif)
												   VALUES ('".$_POST['organismo']."',
												           '".$_POST['npresupuesto']."',
														   '".$_POST['codajuste']."',
														   '".$FPDET['cod_partida']."',
														   'PR',
														   '00',
														   '$monto',
												           '".$_SESSION['USUARIO_ACTUAL']."',
														   '$ahora')";
		 $QAJDET2=mysql_query($SAJDET2) or die ($SAJDET2.mysql_error());
		 
		 //// **** GUARDAR TOTAL AJUSTE EN PV_AJUSTEPRESUPUESTARIO
		 $total_ajuste=$_POST[total_ajuste];
		 $total_ajuste=cambioFormat($total_ajuste);
		 $SAJU="UPDATE 
		               pv_ajustepresupuestario 
		           SET 
				       TotalAjuste='$total_ajuste' 
		         WHERE 
				       Organismo='".$_POST['organismo']."' AND
					   CodPresupuesto='".$_POST['npresupuesto']."' AND
					   CodAjuste='".$_POST['codajuste']."'";
		 $QAJU=mysql_query($SAJU) or die ($SAJU.mysql_error());
	  }
	 }
	}
   }
}
//// ---------------------------------------------------------------
//// ______  ***********  APROBAR AJUSTE  *********** _________ //// 
//// ---------------------------------------------------------------
if($accion==AprobarAjuste){
  ///*** Actualizo campo Estado = AP
$SQL="UPDATE 
		   pv_ajustepresupuestario aj, 
		   pv_ajustepresupuestariodet ajdet
	  SET  
		   aj.Estado = 'AP',
		   ajdet.Estado = 'AP'
	WHERE  
		   aj.Organismo = ajdet.Organismo AND
		   aj.CodPresupuesto = '".$_POST['npresupuesto']."' AND
		   aj.CodAjuste = '".$_POST['codajuste']."'";
$QRY=mysql_query($SQL) or die ($SQL.mysql_error());

  ///*** Consulta para obtener los montos correspondientes a los ajustes en la tabla pv_ajustepresupuestariodet
  $SAJDET="SELECT 
                 aj.TipoAjuste,
				 ajdet.MontoAjuste
			FROM 
				 pv_ajustepresupuestario aj,
				 pv_ajustepresupuestariodet ajdet 
		   WHERE 
				 aj.Organismo = ajdet.Organismo AND
				 aj.CodPresupuesto = '".$_POST['npresupuesto']."'
				 aj.CodAjuste = '".$_POST['codajuste']."'";
  $QAJDET=mysql_query($SAJDET) or die ($SAJDET.mysql_error());
  $RAJDET=mysql_num_rows($QAJDET);
  if($RAJDET!=0){
	for($i; $i<$RAJDET; $i++){
	    $FAJDET=mysql_fetch_array($QAJDET);
		
	}
  }
  
 //// **** ACTUALIZO MONTOS EN PV_PRESUPUESTODET
 if($FIELD['TipoAjuste']==IN){
   $monto_calculado = $FPDET['MontoCalculado'] + $monto; /// *** Monto que sera agregado al campo MontoCalculado de pv_presupuestodet
 }else{
   $monto_calculado = $FPDET['MontoCalculado'] - $monto; /// *** Monto que sera agregado al campo MontoCalculado de pv_presupuestodet
 }
 $SPRESUDET="UPDATE 
					pv_presupuestodet 
				SET 
					MontoCalculado='$monto_calculado'
			  WHERE 
					Organismo = '".$_POST['organismo']."' AND
					CodPresupuesto = '".$_POST['npresupuesto']."' AND
					cod_partida = '".$FPDET['cod_partida']."'";
 $QPRESUDET=mysql_query($SPRESUDET) or die ($SPRESUDET.mysql_error());
		
		//// **** ACTUALIZO MONTOS EN PV_PRESUPUESTO
		if($cont==0){
		     $SPRESU="SELECT * FROM 
		                           pv_presupuesto 
		                      WHERE 
						           Organismo = '".$_POST['organismo']."' AND
				                   CodPresupuesto = '".$_POST['npresupuesto']."'";
		     $QPRESU=mysql_query($SPRESU) or die ($SPRESU.mysql_error());
		     $FPRESU=mysql_fetch_array($QPRESU);
			 $monto_presu = $FPRESU['MontoCalculado'];
		     //echo"Monto Calculado= ".$monto_presu;
			 $cont = $cont + 1;
		}
		
		$monto_presu = $monto_presu + $monto; // Variable que suma el total del ajuste
		//echo"Monto_Presu= ".$monto_presu;
		$PRESU="UPDATE 
		               pv_presupuesto 
		           SET 
				       MontoAprobado = '$m_calc'
				 WHERE 
				       Organismo = '".$_POST['organismo']."' AND
					   CodPresupuesto = '".$_POST['npresupuesto']."'";
}
?>
<?php
/// --------------------------------------------------------------------------------------
function cambioFormat($num){
	$num = str_replace(".","",$num);
	$num = str_replace(",",".",$num);
	return ($num);
}
/// --------------------------------------------------------------------------------------
/// --------------------------------------------------------------------------------------	
////               **** GUARDAR APROBAR AJUSTE
/// --------------------------------------------------------------------------------------	
if($accion=="GuardarAprobarAjuste"){ 

	list($codAjuste, $tipoAjuste, $cod_presupuesto) = split('[|]',$_POST['registro']);

        
$FC=date("Y-m-d");
$FCC=date("Y-m-d H:i:s");
$sql = "select 
              CodPersona 
	      from 
		      usuarios 
		  where  
		      usuario='".$_SESSION['USUARIO_ACTUAL']."'";	
$qry = mysql_query($sql) or die ($sql.mysql_error());
$field = mysql_fetch_array($qry);

//// **** ACTUALIZO ESTADO DE PV_AJUSTEPRESUPUESTARIO
$SAJ="UPDATE pv_ajustepresupuestario SET Estado = 'AP', 
                                         FechaAprobacion = '$FC', 
										 UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
										 UltimaFechaModif= '$FCC',
										 AprobadoPor = '".$field['CodPersona']."'
								  WHERE  CodPresupuesto= '".$_POST['npresupuesto']."' AND
								         CodAjuste = '$codAjuste' and 
										 Organismo = '".$_POST['codorganismo']."'"; 	
$QAJ=mysql_query($SAJ) or die ($SAJ.mysql_error());

$saj2 = "update pv_ajustepresupuestariodet set Estado = 'AP',
											   UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
										       UltimaFechaModif= '$FCC' 
										WHERE  CodPresupuesto= '".$_POST['npresupuesto']."' AND
								         	   CodAjuste = '$codAjuste' and 
										 	   Organismo = '".$_POST['codorganismo']."'"; //echo $saj22;";
$qaj2 = mysql_query($saj2) or die ($saj2.mysql_error());	


/// ***************************--------------------------------
///     ACTUALIZO PARTDAS AFECTADAS POR AJUSTES
/// ***************************--------------------------------
$s_obtener = "select 
                    MontoAjuste,
					cod_partida,
					CodPresupuesto,
					Organismo 
		       from 
			        pv_ajustepresupuestariodet 
			  where 
			        CodPresupuesto =  '".$_POST['npresupuesto']."' and 
					Organismo = '".$_POST['codorganismo']."' and 
					CodAjuste = '$codAjuste'";	
$q_obtener = mysql_query($s_obtener) or die ($s_obtener.mysql_error());
$r_obtener = mysql_num_rows($q_obtener);

if($r_obtener!=0){
  for($a=0;$a<$r_obtener; $a++){
	  $f_obtener = mysql_fetch_array($q_obtener);
	  
	    $s_consult = "select
		                     pvdet.MontoAjustado as MontoAjustadoDet,
							 pv.MontoAjustado as MontoAjustado
							 
						from 
                             pv_presupuestodet pvdet
							 inner join pv_presupuesto pv on ((pv.CodPresupuesto = pvdet.CodPresupuesto) and (pv.Organismo = pvdet.Organismo))
						where
						     pvdet.Organismo = '".$f_obtener['Organismo']."' and 
							 pvdet.CodPresupuesto = '".$f_obtener['CodPresupuesto']."' and 
							 pvdet.cod_partida =  '".$f_obtener['cod_partida']."'";	
		$q_consult = mysql_query($s_consult) or die ($s_consult.mysql_error());
		$f_consult = mysql_fetch_array($q_consult);
	  
        if($tipoAjuste=='IN'){
		   /// CALCULLANDO MONTO AJUSTAR
		   $montoAjustActualizar = $f_obtener['MontoAjuste'] + $f_consult['MontoAjustadoDet'];
		}else{
		   $montoAjustActualizar = $f_consult['MontoAjustadoDet'] - $f_obtener['MontoAjuste'];
		}
		
		   $s_ajuste = "update 
                             pv_presupuestodet 
			            set 
				             MontoAjustado = '$montoAjustActualizar'
						where
						     Organismo = '".$f_obtener['Organismo']."' and 
							 CodPresupuesto = '".$f_obtener['CodPresupuesto']."' and 
							 cod_partida =  '".$f_obtener['cod_partida']."'";	
		$q_ajuste = mysql_query($s_ajuste) or die ($s_ajuste.mysql_error());
  }
}
}
/// --------------------------------------------------------------------------------------
/// --------------------------------------------------------------------------------------	
////               **** GUARDAR APROBAR Reintegro
/// --------------------------------------------------------------------------------------	
if($accion=="GuardarAprobarReintegro"){ 

	list($codAjuste, $FechaAjuste, $CodOrganismo, $CodPresupuesto) = split('[|]',$_POST['registro']);

$FC=date("Y-m-d");
$FCC=date("Y-m-d H:i:s");
$sql = "select 
              CodPersona 
	      from 
		      usuarios 
		  where  
		      usuario='".$_SESSION['USUARIO_ACTUAL']."'";	
$qry = mysql_query($sql) or die ($sql.mysql_error());
$field = mysql_fetch_array($qry);

//// **** ACTUALIZO ESTADO DE PV_AJUSTEPRESUPUESTARIO
$SAJ="UPDATE pv_ReintegroPresupuestario SET Estado = 'AP', 
                                         FechaAprobacion = '$FC', 
										 UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
										 UltimaFechaModif= '$FCC',
										 AprobadoPor = '".$field['CodPersona']."'
								  WHERE  CodPresupuesto= '".$_POST['npresupuesto']."' AND
								         CodReintegro = '$codAjuste' and 
										 Organismo = '".$_POST['codorganismo']."'"; 	
$QAJ=mysql_query($SAJ) or die ($SAJ.mysql_error());

$saj2 = "update pv_ReintegroPresupuestariodet set Estado = 'AP',
											   UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
										       UltimaFechaModif= '$FCC' 
										WHERE  CodPresupuesto= '".$_POST['npresupuesto']."' AND
								         	   CodReintegro = '$codAjuste' and 
										 	   Organismo = '".$_POST['codorganismo']."'"; //echo $saj22;";
$qaj2 = mysql_query($saj2) or die ($saj2.mysql_error());	


/// ***************************--------------------------------
///     ACTUALIZO PARTDAS AFECTADAS POR AJUSTES
/// ***************************--------------------------------
$s_obtener = "select 
                    MontoReintegro,
					cod_partida,
					CodPresupuesto,
					Organismo 
		       from 
			        pv_ReintegroPresupuestariodet 
			  where 
			        CodPresupuesto =  '".$_POST['npresupuesto']."' and 
					Organismo = '".$_POST['codorganismo']."' and 
					CodReintegro = '$codAjuste'";	
$q_obtener = mysql_query($s_obtener) or die ($s_obtener.mysql_error());
$r_obtener = mysql_num_rows($q_obtener);

if($r_obtener!=0){
  for($a=0;$a<$r_obtener; $a++){
	  $f_obtener = mysql_fetch_array($q_obtener);
	  
	    $s_consult = "select
		                     pvdet.MontoReintegrado as MontoReintegradoDet,
							 pv.MontoAjustado as MontoAjustado
							 
						from 
                             pv_presupuestodet pvdet
							 inner join pv_presupuesto pv on ((pv.CodPresupuesto = pvdet.CodPresupuesto) and (pv.Organismo = pvdet.Organismo))
						where
						     pvdet.Organismo = '".$f_obtener['Organismo']."' and 
							 pvdet.CodPresupuesto = '".$f_obtener['CodPresupuesto']."' and 
							 pvdet.cod_partida =  '".$f_obtener['cod_partida']."'";	
		$q_consult = mysql_query($s_consult) or die ($s_consult.mysql_error());
		$f_consult = mysql_fetch_array($q_consult);
	  
       
		   /// CALCULLANDO MONTO AJUSTAR
		   $montoAjustActualizar = $f_obtener['MontoReintegro'] + $f_consult['MontoReintegradoDet'];
		
		
		   $s_ajuste = "update 
                             pv_presupuestodet 
			            set 
				             MontoReintegrado = '$montoAjustActualizar'
						where
						     Organismo = '".$f_obtener['Organismo']."' and 
							 CodPresupuesto = '".$f_obtener['CodPresupuesto']."' and 
							 cod_partida =  '".$f_obtener['cod_partida']."'";	
		$q_ajuste = mysql_query($s_ajuste) or die ($s_ajuste.mysql_error());
  }
}
}
/// --------------------------------------------------------------------------------------
                  //// **** GUARDAR EDITAR AJUSTE
/// --------------------------------------------------------------------------------------				  
if($accion=='EditarAjuste'){
/// ------------------------------------------------------
$fecha=date("Y-m-d H:m:s");
/// ------------------------------------------------------
if($_POST['fresolucion']!=''){
	$fresolucion=$_POST['fresolucion']; 
	$fresolucion=date("Y-m-d",strtotime($fresolucion));
}else $fresolucion='';
/// ------------------------------------------------------
if($_POST['fgaceta']!=''){
	$fgaceta=$_POST['fgaceta']; 
	$fgaceta=date("Y-m-d",strtotime($fgaceta));
}else $fgaceta='';
/// ------------------------------------------------------
if($_POST['fPeriodo']!=""){ 
    
    list($m, $a)=SPLIT( '[/.-]', $_POST['fPeriodo']);
    $fPeriodo=$a.'-'.$m;
    $periodo = $fPeriodo;
    
}
list($CodAjuste,$fechaAjuste,$organismo,$CodPresupuesto)= SPLIT('[|]',$_POST['registro']);
/// ------------------------------------------------------


/// ------- ACTUALIZANDO PV_AJUSTEPRESUPUESTARIODET  

$montoAj = $_POST['partida_preDMA'];
$CodPartida = $_POST['CodPartida'];
$MontoTotal=0;
for($i=0; $i<count($montoAj);$i++){
    
    $monto = cambioFormato($montoAj[$i]);
    $s_ajdet = "update 
                      pv_ajustepresupuestariodet
                               set
                                  MontoAjuste = '".$monto."',
                                      UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
                                      UltimaFechaModif = now()
                             where 
                                  Organismo='".$_POST['org']."' and
                              CodAjuste='$CodAjuste' and
                              CodPresupuesto='".$_POST['npresupuesto']."' and
                              cod_partida='".$CodPartida[$i]."'";
    
    $q_ajdet = mysql_query($s_ajdet) or die ($s_ajdet.mysql_error()) ;
    $MontoTotal= $MontoTotal + $monto;
}


$sql="update 
             pv_ajustepresupuestario 
	    set 
	         NumResolucion='".$_POST['resolucion']."',
		     FechaResolucion='$fresolucion',
		     NumGaceta='".$_POST['gaceta']."',
		     FechaGaceta='$fgaceta',
		     Periodo='$periodo',
		     Descripcion='".$_POST['descripcion']."',
		     TipoAjuste='".$_POST['tAjuste']."',
                     TotalAjuste='".$MontoTotal."' 
	  where 
	         Organismo='".$_POST['org']."' and
	         CodAjuste='$CodAjuste' and
		     CodPresupuesto='".$_POST['npresupuesto']."'";
$qry=mysql_query($sql) or die ($sql.mysql_error()); //echo $sql;
}
/// --------------------------------------------------------------------------------------
                  //// **** GUARDAR EDITAR Reintegro
/// --------------------------------------------------------------------------------------				  
if($accion=='EditarReintegro'){
/// ------------------------------------------------------
$fecha=date("Y-m-d H:m:s");
/// ------------------------------------------------------
if($_POST['fresolucion']!=''){
	$fresolucion=$_POST['fresolucion']; 
	$fresolucion=date("Y-m-d",strtotime($fresolucion));
}else $fresolucion='';
/// ------------------------------------------------------
if($_POST['fgaceta']!=''){
	$fgaceta=$_POST['fgaceta']; 
	$fgaceta=date("Y-m-d",strtotime($fgaceta));
}else $fgaceta='';
/// ------------------------------------------------------
if($_POST['fPeriodo']!=""){ 
    
    list($m, $a)=SPLIT( '[/.-]', $_POST['fPeriodo']);
    $fPeriodo=$a.'-'.$m;
    $periodo = $fPeriodo;
    
}

list($CodAjuste,$fechaAjuste,$organismo,$CodPresupuesto)= SPLIT('[|]',$_POST['registro']);
/// ------------------------------------------------------

/// ------- ACTUALIZANDO PV_AJUSTEPRESUPUESTARIODET  
//echo"pasa=".$_POST['MontoAjuste'];
//echo"otra=".$_POST['partida_preDMA'];
$montoAj = $_POST['partida_preDMA'];
$CodPartida = $_POST['CodPartida'];
$MontoTotal=0;
for($i=0; $i<count($montoAj); $i++){
    $Monto = cambioFormato($montoAj[$i]);
    $s_ajdet = "update 
                      pv_ReintegroPresupuestariodet
                               set
                                      MontoReintegro = '".$Monto."',
                                      UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
                                      UltimaFechaModif = now()
                             where 
                                  Organismo='".$_POST['org']."' and
                              CodReintegro='$CodAjuste' and
                              CodPresupuesto='".$_POST['npresupuesto']."' and
                              cod_partida='".$CodPartida[$i]."'";
    $q_ajdet = mysql_query($s_ajdet) or die ($s_ajdet.mysql_error()) ;
    $MontoTotal= $MontoTotal + $Monto;
}

$sql="update 
             pv_ReintegroPresupuestario 
	    set 
                     NumResolucion='".$_POST['resolucion']."',
		     FechaResolucion='$fresolucion',
		     NumGaceta='".$_POST['gaceta']."',
		     FechaGaceta='$fgaceta',
		     Periodo='$periodo',
		     Descripcion='".$_POST['descripcion']."',
                     TotalAjuste='".$MontoTotal."' 
	  where 
	         Organismo='".$_POST['org']."' and
	         CodReintegro='$CodAjuste' and
		     CodPresupuesto='".$_POST['npresupuesto']."'";
$qry=mysql_query($sql) or die ($sql.mysql_error()); //echo $sql;


}
/// --------------------------------------------------------------------------------------
                      //// **** GUARDAR REFORMULACION **** ////
/// --------------------------------------------------------------------------------------				 
if($accion=='GuardarReformulacion'){
  $sql="SELECT
   				 MAX(CodRef) 
          FROM 
		  		 pv_reformulacionppto 
		 WHERE 
		 		Organismo='".$_POST['Org']."' AND 
		       	CodPresupuesto='".$_POST['num_presupuesto']."'";
  $qry=mysql_query($sql) or die ($sql.mysql_error());
  $field=mysql_fetch_array($qry);
  

  $c_ref=(int) ($field[0]+1); //echo"COD REF=".$FREF[0];
  $c_ref=(string) str_repeat("0", 4-strlen($c_ref)).$c_ref;
  
  $f_gaceta = date("Y-m-d", strtotime($_POST['fgaceta']));
  $fresolucion=date("Y-m-d",strtotime($_POST['fresolucion']));
  
  if($_POST['fReformulacion']!=''){$fref=$_POST['fReformulacion']; $fref=date("Y-m-d",strtotime($fref));}else{$fref='';}
  if($_POST['fperiodo']!=''){$fperiodo=$_POST['fperiodo']; $fperiodo=date("Y-m-d",strtotime($fperiodo));}else{$fperiodo='';}
  $insert="INSERT INTO pv_reformulacionppto(Organismo,
  											CodPresupuesto,
											CodRef,
											NumResolucion,
											FechaResolucion,
											Descripcion,
											PreparadoPor,
											FechaPreparacion,
											FechaRef,
											PeriodoRef,
											UltimoUsuario,
											Estado,
											UltimaFechaModif,
											NumGaceta,
											FechaGaceta) 
									VALUES('".$_SESSION['ORGANISMO_ACTUAL']."',
										   '".$_POST['num_presupuesto']."',
										   '$c_ref',
										   '".$_POST['resolucion']."',
										   '$fresolucion',
										   '".$_POST['descripcion']."',
										   '".$_POST['prepor']."',
										   '$fechaActual',
										   '$fref',
										   '$fperiodo',
										   '".$_SESSION['USUARIO_ACTUAL']."',
										   'PR',
										   '".date("Y-m-d H:i:s")."',
										   '".$_POST['gaceta']."',
										   '$f_gaceta')"; 
	$qinsert=mysql_query($insert) or die ($insert.mysql_error());
}
/// --------------------------------------------------------------------------------------
///              //// ***** GUARDAR REFORMULACION DETALLE **** ////
/// --------------------------------------------------------------------------------------
if($accion=='GuardarReformulacionDetalle'){
	
  if($_POST['fresolucion']!=''){list($dia, $mes,  $ano) = split('[-]',$_POST['fresolucion']); $fecha_resolucion = $ano.'-'.$mes.'-'.$dia;}
  if($_POST['fgaceta']!=''){list($dia2, $mes2,  $ano2) = split('[-]',$_POST['fgaceta']); $fecha_gaceta = $ano2.'-'.$mes2.'-'.$dia2;}
  
  $supdate = "update pv_reformulacionppto set NumResolucion='".$_POST['resolucion']."',
											  FechaResolucion='$fecha_resolucion',
											  NumGaceta='".$_POST['gaceta']."',
											  FechaGaceta='$fecha_gaceta',
											  Descripcion='".$_POST['descripcion']."' 
										where 
										      Organismo='".$_POST['Org']."' and 
										      CodPresupuesto='".$_POST['num_presupuesto']."' and 
											  CodRef='".$_POST['CodRef']."'";
  $qupdate = mysql_query($supdate) or die ($supdate.mysql_error());
											  
  
    $sql= "update 
                pv_reformulacionpptodet 
		    set 
			    Estado='PE' 
		  where 
		        Organismo='".$_POST['Org']."' and 
				CodPresupuesto='".$_POST['num_presupuesto']."' and 
				CodRef='".$_POST['CodRef']."'";
    $qry= mysql_query($sql) or die ($sql.mysql_error());
}
/// --------------------------------------------------------------------------------------
                 //// **** GUARDAR DATOS DE REFORMULACION EDITADOS **** ////
/// --------------------------------------------------------------------------------------		
if($accion=='GuardarReformulacionEdit'){
if($_POST['fresolucion']!="")$fecha_resolucion=date("Y-m-d",strtotime($_POST['fresolucion']));
if($_POST['fgaceta']!="")$fecha_gaceta=date("Y-m-d",strtotime($_POST['fgaceta']));
list($Organismo, $CodPresupuesto, $CodRef) = split('[-]',$_POST['registro']);

$sql="UPDATE pv_reformulacionppto SET NumResolucion='".$_POST['resolucion']."',
                                       FechaResolucion='$fecha_resolucion',
									   NumGaceta='".$_POST['gaceta']."',
									   FechaGaceta='$fecha_gaceta',
									   PeriodoRef='".$_POST['fperiodo']."',
									   Descripcion='".$_POST['descripcion']."',
									   AprobadoPor='".$_POST['nomempleado']."',
									   UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
									   UltimaFechaModif='".date("Y-m-d H:i:s")."'
								 WHERE 
								       CodPresupuesto='$CodPresupuesto' AND
									   Organismo='$Organismo' AND
									   CodRef='$CodRef'";
$qry=mysql_query($sql) or die ($sql.mysql_error());
//// *************************************************************************************
$sup = "update pv_reformulacionpptodet set Estado='PE' 
									 where 
									      CodPresupuesto='$CodPresupuesto' AND
									      Organismo='$Organismo' AND
									      CodRef='$CodRef'"; 
$qup = mysql_query($sup) or die ($sup.mysql_error());
}
/// --------------------------------------------------------------------------------------
                 //// **** GUARDAR DATOS DE REFORMULACION APROBAR **** ////
/// --------------------------------------------------------------------------------------
if($accion=='GuardarReformulacionAprobar'){
 
  $sap = "select CodPersona from usuarios where Usuario='".$_SESSION['USUARIO_ACTUAL']."'";
  $qap = mysql_query($sap) or die ($sap.mysql_error());
  $rap = mysql_num_rows($qap);
  if($rap!=0)$fap=mysql_fetch_array($qap);
  
  
  list($cod_organismo, $cod_presupuesto, $cod_ref) = split('[-]', $_POST['registro']);	
		$SQL="UPDATE pv_reformulacionppto 
				 SET Estado='AP',
				     AprobadoPor='".$fap['CodPersona']."',
					 FechaAprobacion='".date("Y-m-d")."',
					 UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
					 UltimaFechaModif='".date("Y-m-d H:i:s")."' 
			   WHERE CodPresupuesto='$cod_presupuesto' AND 
					 Organismo='$cod_organismo'  AND 
					 CodRef='$cod_ref'"; //echo $SQL;
		$QRY=mysql_query($SQL) or die ($SQL.mysql_error());

/// --------------------------------------
    $SUPDATE="UPDATE 
	 				pv_reformulacionpptodet 
                 SET 
				    Estado='AP',
		        	UltimoUsuario='".$_SESSION['USUARIO_ACTUAL']."',
			    	UltimaFechaModif='".date("Y-m-d H:i:s")."'  
	          WHERE 
			  		CodPresupuesto='$cod_presupuesto' AND 
	            	Organismo='$cod_organismo'  AND 
			    	CodRef='$cod_ref'";
   $QUPDATE=mysql_query($SUPDATE) or die ($SUPDATE.mysql_error());
/// -----------------------------------
/// CARGANDO DATOS A PV_PRESUPUESTO DET
$sql="SELECT * FROM 
                    pv_reformulacionpptodet 
			  WHERE 
			        CodPresupuesto='$cod_presupuesto' AND 
	                Organismo='$cod_organismo'  AND 
			        CodRef='$cod_ref'";
$qry=mysql_query($sql) or die ($sql.mysql_error());
$row=mysql_num_rows($qry);
if($row!=0){
  for($i=0; $i<$row; $i++){
    $field=mysql_fetch_array($qry);
	
	/// CONSULTA PARA OBTENER DATOS DE PV_PARTIDA
	$spart="SELECT * FROM pv_partida WHERE cod_partida='".$field['cod_partida']."'";
	$qpart=mysql_query($spart) or die ($spart.mysql_error());
	$fpart=mysql_fetch_array($qpart);
	
	/// CONSULTA PARA OBTENER VALOR DE SECUENCIA
	$spre="SELECT MAX(Secuencia) 
	             FROM pv_presupuestodet 
				WHERE CodPresupuesto='$cod_presupuesto' AND 
				      Organismo='$cod_organismo'";
	$qpre=mysql_query($spre) or die ($spre.mysql_error());
	$fpre=mysql_fetch_array($qpre);
	$secuencia=(int) ($fpre[0]+1);
    $secuencia=(string) str_repeat("0", 4-strlen($secuencia)).$secuencia;
	
	/// INSERTANDO LAS NUEVAS PARTIDAS
	$sinsert="INSERT INTO pv_presupuestodet (Organismo,
											 CodPresupuesto,
											 Secuencia,
											 cod_partida,
											 FlagsReformulacion,
											 partida,
											 generica,
											 especifica,
											 subespecifica,
											 tipocuenta,
											 tipo,
											 Estado,
											 UltimoUsuario,
											 UltimaFechaModif)
									 VALUES ('$cod_organismo',
									         '$cod_presupuesto',
											 '$secuencia',
											 '".$field['cod_partida']."',
											 'S',
											 '".$fpart['partida1']."',
											 '".$fpart['generica']."',
											 '".$fpart['especifica']."',
											 '".$fpart['subespecifica']."',
											 '".$fpart['cod_tipocuenta']."',
											 '".$fpart['tipo']."',
											 'AP',
											 '".$_SESSION['USUARIO_ACTUAL']."',
											 '".date("Y-m-d H:i:s")."')";
	$qinsert=mysql_query($sinsert) or die ($sinsert.mysql_error());
	
  }
}
}
?> 


