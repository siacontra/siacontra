<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
//include('../pv/rp_ejecucion.php');


include ("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('01', $concepto);


//	------------------------------------
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="fscriptpresupuesto.js"></script>
</head>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Reporte Disponibilidad</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />
<?php
include "gmsector.php";

///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
if((!$_POST)or($volver=='0')) $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];


//$fpreparado = $_SESSION["CODPERSONA_ACTUAL"];

$MAXLIMIT=30; 
$filtro = "";


if ($forganismo != "") { $filtro .= " AND (a.`CodOrganismo` = '".$forganismo."')"; $corganismo = "checked"; } else $dorganismo = "disabled";
//if ($fejercicio != "") { $filtro .= " AND (EjercicioPpto = '".$fejercicio."')"; $cejercicio = "checked"; } else $dejercicio = "disabled";
//if ($fnanteproyecto != "") { $filtro .= " AND (CodAnteproyecto = '".$fnanteproyecto."')"; $cnpoyecto = "checked"; } else $dnproyecto = "disabled";
if ($fstatus != "") { $filtro .= " AND (A.`Estado` = '".$fstatus."')"; $cstatus = "checked"; } else $dstatus = "disabled";
if ($faprobado != "") { $filtro .= " AND (B.`NomCompleto` = '".$faprobado."')"; $cpreparado = "checked"; } else $dpreparado = "disabled";

//echo $fstatus;

//if ($fnajuste != "") { $filtro .= " AND (aj.CodAjuste = '".$fnajuste."')"; $cnajuste = "checked"; } else $dnajuste = "disabled";
/*if ($fdesde != "" || $fhasta != "") {
	if ($fdesde != "") $filtro .= " AND (FechaAjuste >= '".$fdesde."')";
	if ($fhasta != "") $filtro .= " AND (FechaAjuste <= '".$fhasta."')"; 
	$cajuste = "checked"; 
} else $dajuste = "disabled";*/
if ($ftajuste != "") { $filtro .= " AND (TipoAjuste = '".$ftajuste."')"; $ctajuste = "checked"; } else $dtajuste = "disabled";
//	-------------------------------------------------------------------------------
$MAXLIMIT=30;
//---------------------------------------------------------------------------------
echo "
<form name='frmentrada' action='rp_creditoadicional.php?limite=0' method='POST'>";
echo" <input type='hidden' name='limit' id='limit' value='".$limit."'/>
      <input type='hidden' name='registros' id='registros' value='".$registros."'/>

<div class='divBorder' style='width:900px;'>
<table width='900' class='tblFiltro'>
 <tr>
  <td width='125' align='right'>Organismo:</td>
  <td>
	<input type='checkbox' name='chkorganismo' id='chkorganismo' value='1'$corganismo onclick='this.checked=true' />
	<select name='forganismo' id='forganismo' class='selectBig' $dorganismo onchange='getFOptions_2(this.id, \"fnanteproyecto\", \"chknanteproyecto\");'>";
		getOrganismos($obj[2], 3, $_SESSION[ORGANISMO_ACTUAL]);
		echo "
	</select>
  </td>
   
 </tr> 
</table>
</div>
<center><input type='submit' name='btBuscar' value='Buscar' disabled='disabled'></center>
<br /><div class='divDivision'>Listado de Partida</div><br />
<form/>";
/// -------------------------------------------------------------------------------------------------------
$ano=date("Y");

$sql="SELECT A.`CodPresupuesto`,A.`Organismo`,A.`CodAjuste`,A.`FechaAjuste`,A.`Periodo`,
		A.`TipoAjuste`, A.`NumGaceta`,A.`FechaGaceta`,
		A.`NumResolucion`,A.`FechaResolucion`,A.`Descripcion` ,
		A.`TotalAjuste`, A.`PreparadoPor`, A.`FechaPreparacion`, A.`AprobadoPor`, A.`FechaAprobacion` ,A.`Estado`, A.`UltimaFechaModif`, A.`UltimoUsuario`, A.`MotivoAjuste` , B.`NomCompleto`
		FROM `pv_ajustepresupuestario` AS A 
		JOIN `mastpersonas` AS B ON A.`PreparadoPor` = B.`CodPersona`";


 //echo $sql;

 /*$sql="SELECT * FROM `pv_credito_adicional` WHERE `CodOrganismo`= '".$_SESSION['ORGANISMO_ACTUAL']."'  $filtro
		    ORDER BY `co_credito_adicional`";*/
			 //echo $sql;
$qry=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($qry);
$registros=$rows; //echo"Registros=".$registros;
?>
<table width="900" class="tblBotones">
<tr>
<td><div id="rows"></div></td>
<td align="center">


</td>
<td align="right"> 
   
<!-- <input name="btAnular" type="button" class="btLista" id="btAnular" value="Imprimir" onclick="cargarOpcion(this.form, 'rp_disponibilidadPdf.php','SELF');" /></td> -->
</tr><!-- onclick="aprobarCreditoAdicional(this.form);"-->
</table>
<input type="hidden" name="registro" id="registro" />
<table width="900" class="tblLista" >

  <tr class="trListaHead">
	<th width="18" scope="col">N°</th>
	<th width="115" scope="col">Partida</th>
	<th width="483" scope="col">DescripciónDescripción</th>
	<th width="30" scope="col">Tipo</th>
	<th width="108" scope="col">Asignado</th>
	<th width="118" scope="col" >Disponible</th>
  </tr>
  
  
<?
//------------------------------------------------------------------------------------------------------------
///////////////////************ INSERTAR PARTIDAS ASOCIADAS AL ANTEPROYECTO *************/////////////////////
//////////////////************* CARGA LOS DATOS DE LA TABLA "pv_antepresupuestodet" ****///////////////////// 
//------------------------------------------------------------------------------------------------------------
if($accion=="AGREGAR"){ 
 $SADET="SELECT * FROM pv_ajustepresupuestario 
                 WHERE CodPresupuesto='".$F['CodPresupuesto']."' AND
				       CodAjuste='".$F['CodAjuste']."'";// CONSULTA EL CODIGO DE PRESUPUESTO
 $QADET=mysql_query($SADET) or die ($SADET.mysql_error()); 
 $RADET=mysql_num_rows($QADET);
 if($RADET!=0){
  $FADET=mysql_fetch_array($QADET);
 for($i=1; $i<=$filas; $i++){
  if($_POST[$i]!=""){
   $SPART=mysql_query("SELECT * FROM pv_partida WHERE cod_partida='".$_POST[$i]."'");/// CONSULTO PARA COMPARAR COD_PARTIDA
   if(mysql_num_rows($SPART)!=0){
	$FPART=mysql_fetch_array($SPART);
	$SADET2="SELECT * FROM pv_ajustepresupuestariodet
	                  WHERE cod_partida='".$_POST[$i]."' AND 
					         CodPresupuesto='".$FADET['CodPresupuesto']."'";
	$QADET2=mysql_query($SADET2) or die ($SADET2.mysql_error());
	if(mysql_num_rows($QADET2)!=0){
		echo"<script>";
		echo"alert('LOS DATOS HAN SIDO INGRESADOS ANTERIORMENTE')";
		echo"</script>";
	}else{
	 if($FPART['tipo'] != 'T'){
	  $sqlAnt="SELECT MAX(CodAjuste) FROM pv_ajustepresupuestariodet WHERE CodPresupuesto='".$FADET['CodPresupuesto']."'";
	  $qryAnt=mysql_query($sqlAnt) or die ($sqlAnt.mysql_error());
	  $fieldAnt=mysql_fetch_array($qryAnt);
	  $sql="INSERT INTO pv_ajustepresupuestariodet (Organismo,
												   CodPresupuesto,
												   CodAjuste,
												   CodPartida)
											VALUES ('".$_SESSION['ORGANISMO_ACTUAL']."',
													'".$fieldPpto['CodPresupuesto']."',
													'".$_POST[$i]."',
													'".$fieldP['CodPartida']."'";
		  $query=mysql_query($sql) or die ($sql.mysql_error());
}}}}}}}
else{
 if ($accion=="ELIMINAR") {
	$sql="DELETE FROM pv_ajustepresupuestariodet WHERE CodPresupuesto='".$registro2."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
}}
//------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------
$sql="SELECT *
		FROM pv_presupuestodet
		WHERE CodPresupuesto = '0001'
		AND Organismo = '0001'
		AND (
		cod_partida LIKE '%401.%'
		OR cod_partida LIKE '%407.%')";

/*$sql="SELECT * FROM pv_presupuestodet 
              WHERE CodPresupuesto='".$_POST['npresupuesto']."' AND
			        Organismo='".$_POST['organismo']."'";*/
$qry=mysql_query($sql) or die (($sql).mysql_error());
$row=mysql_num_rows($qry);
if($row!=0){
 for($i=0; $i<$row; $i++){
	 
	 $actaulizado = 0.00;
	 $disponible = 0.00;
	 //$disponible = $montoP-$disponible;
	 
    $field=mysql_fetch_array($qry);
   ///  ORDENA PARTIDAS ////  ORDENA PARTIDAS POR TIPO=DETALLE "D"
   //// **** Obtengo Partidas Tipo "T" 301-00-00-00	**** ////  
   if(($field['partida']!=00) and (($cont1==0) or ($pCapturada!=$field['partida']))){
    $count= $count + 1;
    $sqlP="SELECT * FROM pv_partida 
				   WHERE partida1='".$field['partida']."' AND
						 cod_tipocuenta='".$field['tipocuenta']."' AND
						 tipo='T' AND 
						 generica='00'";
    $qryP=mysql_query($sqlP) or die ($sqlP.mysql_error());
    if(mysql_num_rows($qryP)!=0){
	 $fieldP=mysql_fetch_array($qryP);
	 $montoP=0; $cont1=0;
	$sqldet="SELECT * FROM pv_presupuestodet 
					  WHERE partida='".$fieldP['partida1']."' AND
							tipocuenta='".$fieldP['cod_tipocuenta']."' AND
							CodPresupuesto='".$field['CodPresupuesto']."'";
	 $qrydet=mysql_query($sqldet);
	 $rwd=mysql_num_rows($qrydet);
	 
	 
	 
	 
	 $sqlCredito="SELECT SUM( `mm_monto` ) AS CREDITO_ADICIONAL
					FROM `pv_item_credito_adicional`
					WHERE LEFT( `cod_partida` , 3 )
					IN (
					
					SELECT CONCAT( B.tipocuenta, B.partida )
					FROM pv_partida AS A, pv_presupuestodet AS B
					WHERE B.partida = '".$fieldP['partida1']."'
					AND B.tipocuenta = '".$fieldP['cod_tipocuenta']."'
					AND A.cod_partida = B.cod_partida
					AND B.CodPresupuesto = '".$field['CodPresupuesto']."'
					)";
					
	$respCredito=mysql_query($sqlCredito);
	$datoCredito=mysql_fetch_array($respCredito);
	
	
	 
	 
	 for($a=0; $a<$rwd; $a++){
	  $fdet=mysql_fetch_array($qrydet);
	  $cont1 = $cont1 + 1;
	  $montoP = $montoP + $fdet['MontoAprobado'];
	  $compromiso = $compromiso+$fdet['MontoCompromiso'];	   
	 //$actualizado=($asig+$inc+$cre)-($reb+$deec);
	 //$disponible=$this->actualizado-$com-$pcom;	
	 //echo $fdet['MontoCompromiso'].'+'.$fdet['MontoCausado'].'+'.$fdet['MontoPagado'];			 	 
	 }
	 
	 $incremento = 0.00;// TRASPASO PARTIDA RECEPTORA
	 $descremento = 0.00;// TRASPASO PARTIDA CEDENTE
	 $rebajaCredito = 0.00; //DESCREMENTO DE CREDITO
	 $disponible =0.00;
	 $actaulizado=0.00;
	 
	 $actaulizado = ($montoP+$incremento+$datoCredito['CREDITO_ADICIONAL'])-($rebajaCredito+$descremento); //MONTO ACTUALIZADO = ASIGNADO + INCREMENTO + CREDITO ADICIONAL - (REBAJA DE CREDITO + DESCREMENTO)  
	 $disponible = $actaulizado-$compromiso; 
	 //$disponible = $montoP-$disponible;
	 $disponible0 = number_format($disponible,2,',','.');
	 
	 
	 
	 $montoPar=number_format($montoP,2,',','.');
	 $cont1= $cont1 + 1;
	 $codigo_partida = $fieldP[cod_partida];
	 $pCapturada = $fieldP[partida1];
	 echo "<tr class='trListaBody' onclick='mClk(this,\"registro\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='$codigo_partida'>
	  <td align='center'>".$field['CodPresupuesto']."</td>
	  <td align='center'>".$fieldP['cod_partida']."</td>
	  <td>".$fieldP['denominacion']."</td>	  
	  <td align='center'>".$fdet['tipo']."</td>
	  <td align='right'>$montoPar Bs.F</td>
	   <td align='right'>$disponible0 Bs.F</td>
	   
	 </tr>";
	    }
	  } //<input type='text' size='11' maxlength='12' style='text-align:right' class='inputP' id='montoPartida' name='montoPartida' value='$montoPar' readonly/>
	  //<input type='text' size='11' maxlength='12' style='text-align:right' class='inputP' id='montoPartida' name='montoPartida' value='$disponible' readonly/>
  //// **** Obtengo Partidas Tipo "T" 301-01-00-00	**** ////
  if(($field['generica']!=00) and (($cont2==0) or ($gCapturada!=$field['generica']) or ($pCapturada2!=$field['partida']))){
	  
	  
	  $actaulizado =0.00; 
	 $disponible = 0.00; 
	  
	$sqlP="SELECT * FROM pv_partida 
				   WHERE partida1='".$field['partida']."' AND 
						 cod_tipocuenta='".$field['tipocuenta']."' AND 
						 tipo='T' AND 
						 generica='".$field['generica']."' AND 
						 especifica='00'";
	 $qryP=mysql_query($sqlP) or die ($sqlP.mysql_error());
	 $rw=mysql_num_rows($qryP); 
	 if($rw!=0){
	   $fieldP=mysql_fetch_array($qryP);
	   //$montoASI = $field['MontoAprobado'];
	   $cont2=0; $montoG=0;
	   $sqldet="SELECT * FROM pv_presupuestodet 
						WHERE partida='".$fieldP['partida1']."' AND 
							  generica='".$fieldP['generica']."' AND 
							  tipocuenta='".$fieldP['cod_tipocuenta']."' AND
							  CodPresupuesto='".$field['CodPresupuesto']."'";
      $qrydet=mysql_query($sqldet);
      $rwdet=mysql_num_rows($qrydet);
	  
	  
	   $sqlCredito="SELECT SUM( `mm_monto` ) AS CREDITO_ADICIONAL
					FROM `pv_item_credito_adicional`
					WHERE LEFT( `cod_partida` , 3 )
					IN (
					
					SELECT CONCAT( B.tipocuenta, B.partida )
					FROM pv_partida AS A, pv_presupuestodet AS B
					WHERE B.partida = '".$fieldP['partida1']."'
					AND B.generica = '".$fieldP['generica']."'
					AND B.tipocuenta = '".$fieldP['cod_tipocuenta']."'
					AND A.cod_partida = B.cod_partida
					AND B.CodPresupuesto = '".$field['CodPresupuesto']."'
					)";
					
	$respCredito=mysql_query($sqlCredito);
	$datoCredito=mysql_fetch_array($respCredito);
	  
	  $montoG=0.00;
	  $compromiso = 0.00;
      for($b=0; $b<$rwdet; $b++){
	   $fdet=mysql_fetch_array($qrydet);
	   $cont2= $cont2 + 1;
	   $montoG= $montoG + $fdet['MontoAprobado'];
	   $compromiso = $compromiso+$fdet['MontoCompromiso'];	   
      } 
	  
	  
	 $incremento    = 0.00; // TRASPASO PARTIDA RECEPTORA
	 $descremento   = 0.00; // TRASPASO PARTIDA CEDENTE
	 $rebajaCredito = 0.00; //DESCREMENTO DE CREDITO
	 $actaulizado	= 0.00;
	 $disponible 	= 0.00;
	 
	 $actaulizado = ($montoG+$incremento+$datoCredito['CREDITO_ADICIONAL'])-($rebajaCredito+$descremento); //MONTO ACTUALIZADO = ASIGNADO + INCREMENTO + CREDITO ADICIONAL - (REBAJA DE CREDITO + DESCREMENTO)  
	 $disponible = $actaulizado-$compromiso; 
	 
	 //echo $actaulizado.'-'.$fdet['MontoCompromiso'].'<br />'; 
	 //$disponible = $montoP-$disponible;
	 $disponible1 = number_format($disponible,2,',','.');
	  
	  $montoGen=number_format($montoG,2,',','.');
      $codigo_generica = $fieldP[cod_partida];
      $pCapturada2 = $fieldP[partida1];
	  $gCapturada = $fieldP[generica];
	   echo "<tr class='trListaBody' onclick='mClk(this,\"registro\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='$codigo_generica'>
		 <td align='center'>".$field['CodPresupuesto']."</td>
	     <td align='center'>".$fieldP['cod_partida']."</td>
		 <td>".$fieldP['denominacion']."</td>
		  <td align='center'>".$fdet['tipo']."</td>
		 <td align='right'>$montoGen Bs.F</td>
		 <td align='right'>$disponible1 Bs.F</td>
		 
	   </tr>";
	  }
   } //<input type='text' size='11' maxlenght='12' style='text-align:right' class='inputG' id='montoGenerica' name='montoGenerica' value='$montoGen' readonly/>
    //<input type='text' size='11' maxlength='12' style='text-align:right' class='inputG' id='montoPartida' name='montoPartida' value='$disponible' readonly/>
	   //////////////////////////////////////////////////////////////////////////////////////
	   //// **** Obtengo Partidas Tipo "D" 301-01-01-01	**** ////
   if($field['generica']!=00 && $field['subespecifica']>=00){
    //$cont=1;
		if($field['subespecifica']!=00){$codEspecifica="subespecifica='".$field['subespecifica']."' AND ";}
		else{$codEspecifica='';}
	 $actaulizado2 =0.00; 
	 $disponible2 = 0.00; 
	 $monto=0.00; 
	 $total=0.00;
	 $s="SELECT * FROM pv_partida WHERE cod_partida='".$field['cod_partida']."'";
	$q=mysql_query($s) or die ($s.mysql_error());
	$f=mysql_fetch_array($q);
	$codigo_codpartida=$field['cod_partida'];
    //$monto = $field['MontoAprobado'];  
	
    $codigo_detalle = $field['cod_partida'];
	
	$sqldet="SELECT * FROM pv_presupuestodet 
						WHERE partida='".$f['partida1']."' AND 
							  generica='".$f['generica']."' AND 
							  especifica='".$f['especifica']."' AND 
							  tipocuenta='".$f['cod_tipocuenta']."' AND
							  $codEspecifica
							  CodPresupuesto='".$field['CodPresupuesto']."'";
      $qrydet=mysql_query($sqldet);
      $rwdet=mysql_num_rows($qrydet);
	  
	  
	  
	$sqlCredito2="SELECT SUM( `mm_monto` ) AS CREDITO_ADICIONAL
					FROM `pv_item_credito_adicional`
					WHERE `cod_partida`='".$field['cod_partida']."'";
					
	$respCredito=mysql_query($sqlCredito2);
	$datoCredito=mysql_fetch_array($respCredito);
	
	 $monto=0.00;
	 if($field['subespecifica']==00)
	 {
	 	 for($b=0; $b<$rwdet; $b++){
	   		$fdet=mysql_fetch_array($qrydet);
	   		$cont2= $cont2 + 1;
	   		$monto= $monto + $fdet['MontoAprobado'];
      	} 
	 }
	else
	{$fdet=mysql_fetch_array($qrydet);
	   		
	   		$monto= $fdet['MontoAprobado'];
	}
	
	$total=$total+$monto;
	//$totalT=number_format($total,2,',','.');
	$montoD=number_format($monto,2,',','.');
	 
	 $incremento    = 0.00; // TRASPASO PARTIDA RECEPTORA
	 $descremento   = 0.00; // TRASPASO PARTIDA CEDENTE
	 $rebajaCredito = 0.00; //DESCREMENTO DE CREDITO
	 $actaulizado	= 0.00;
	 $disponible2	= 0.00;
	 //echo $rebajaCredito;
	 $actaulizado2 = ($monto+$incremento+$datoCredito['CREDITO_ADICIONAL'])-($rebajaCredito+$descremento); //MONTO ACTUALIZADO = ASIGNADO + INCREMENTO + CREDITO ADICIONAL - (REBAJA DE CREDITO + DESCREMENTO)  
	 $disponible2 = $actaulizado2-$fdet['MontoCompromiso']; 
	 
	 $disponible2 = number_format($disponible2,2,',','.');
	// PRUEBA
	
	// FIN PRUEBA
    echo "<tr class='trListaBody' onclick='mClk(this,\"registro\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='$codigo_codpartida'>
		<td align='center'>".$field['CodPresupuesto']."</td>
		<td align='center'>".$field['cod_partida']."</td>
		<td>".$f['denominacion']."</td>
		 <td align='center'>".$fieldP['tipo']."</td>
		<td align='right'>$montoD Bs.F</td>
		<td align='right'>$disponible2 Bs.F</td>
	   </tr>"; 
	   }
}}

//" <input type='text' size='11' style='text-align:right' maxlenght='12' id='".$codigo_codpartida."' name='montoCodPartida' value='$montoD' onfocus='obtenerMontoD(this.value);' readonly/>";   

// "<input type='text' size='11' maxlength='12' style='text-align:right'  id='montoPartida' name='montoPartida' value='$disponible2' readonly/>"
?>
<input type="hidden" id="montovoy" name="montovoy"/>
<input type="hidden" id="montova"  name="montova"/>


</table>

</body>
</html>

