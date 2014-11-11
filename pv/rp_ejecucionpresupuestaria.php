<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
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
<script type="text/javascript" language="javascript" src="pv_fscript.js"></script>
</head>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Ejecuci&oacute;n Presupuestaria</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />
<?php
include "gmsector.php";
echo"<input type='hidden' name='regresar' id='regresar' value='anteproyecto_listar'/>";
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
if((!$_POST)or($volver=='0')) $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];

$MAXLIMIT=30; 
$filtro = "";
if ($forganismo != "") { $filtro .= " AND (Organismo = '".$forganismo."')"; $corganismo = "checked"; } else $dorganismo = "disabled";
if ($fPeriodo != "") {
	list($a,$m)= SPLIT('[-]',$_POST['fPeriodo']); $ejercicioPpto = $a; 
	$filtro .="AND (EjercicioPpto = '$ejercicioPpto' and Estado='AP')"; $cPeriodo = "checked";}else $dPeriodo = "disabled";
if ($fPresupuesto != ""){$filtro .= "AND (CodPresupuesto = '".$fPresupuesto."')"; $cPresupuesto = "checked";}else $dPresupuesto = "disabled";

//if ($fnajuste != "") { $filtro .= " AND (aj.CodAjuste = '".$fnajuste."')"; $cnajuste = "checked"; } else $dnajuste = "disabled";
/*if ($fdesde != "" || $fhasta != "") {
	if ($fdesde != "") $filtro .= " AND (FechaAjuste >= '".$fdesde."')";
	if ($fhasta != "") $filtro .= " AND (FechaAjuste <= '".$fhasta."')"; 
	$cajuste = "checked"; 
} else $dajuste = "disabled";*/
//	-------------------------------------------------------------------------------
$MAXLIMIT=30;
//---------------------------------------------------------------------------------
echo "
<form name='frmentrada' action='rp_ejecucionpresupuestaria.php?limite=0' method='POST'>";
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
  <td width='125' align='right'>Per&iacute;odo:</td>
  <td>
	<input type='checkbox' name='chkPeriodo' id='chkPeriodo' value='1' $cPeriodo onclick='enabledPeriodoReporteEjecucion(this.form);' />
	<input type='text' name='fPeriodo' id='fPeriodo' size='6' maxlength='7' $dPeriodo value='$fPeriodo' />
  </td>
  <td width='200'></td>
 </tr>
 
 <tr>
   <td width='125' align='right'>Nro. Presupuesto:</td>
  <td><input type='checkbox' name='chkPresupuesto' id='chkPresupuesto' value='1' $cPresupuesto onclick='enabledPresupuestoReporteEjecucion(this.form);' /><input type='text' id='fPresupuesto' name='fPresupuesto' size='6' maxlenght='4' $dPresupuesto value='$fPresupuesto'/></td>
  
  <td width='125' align='right'></td>
  <td></td>
</tr>
<tr>
<td width='125' align='right'></td>
<td>
	
</td>
<td width='125' align='right' rowspan='2'></td>
<td>
</td>
</tr>
</table>
</div>
<center><input type='submit' name='btBuscar' value='Buscar'></center>
<br /><div class='divDivision'>Ejecuci&oacute;n Presupuestaria</div>
<form/>"; 
/// -------------------------------------------------------------------------------------------------------

$sql = "select * from pv_presupuesto where Organismo='".$_SESSION['ORGANISMO_ACTUAL']."' $filtro";
$qry = mysql_query($sql) or die ($sql.mysql_error());
$row = mysql_num_rows($qry);
$field = mysql_fetch_array($qry);
if($row!=0)$display = 'block';else $display='none';
?>
<div class='divBorder' style='width:1030px; display:<?=$display;?>'>
<table id="Principal" width="1030" align="center" class='tblFiltro'>
<tr>
 <td>
   <table width="1000">
    <tr><?
          $sconsulta = "select
							  pvs.descripcion as DescpSector,
							  pvs.cod_sector as codsector,
							  pvp.descp_programa as DescpPrograma,
							  pvp.cod_programa as codprograma,
							  pvsu.descp_subprog as DescpSubprograma,
							  pvsu.cod_subprog as codsubprog,
							  pvy.descp_proyecto as DescpProyecto,
							  pvy.cod_proyecto as codproyecto,
							  pva.descp_actividad as DescpActividad,
							  pva.cod_actividad as codactividad,
							  pvun.UnidadEjecutora as UnidadEjecutora
						  from
							  pv_presupuesto pv
							  inner join pv_sector pvs on (pvs.cod_sector = pv.Sector)
							  inner join pv_programa1 pvp on (pvp.id_programa = pv.Programa)
							  inner join pv_subprog1 pvsu on (pvsu.id_sub = pv.SubPrograma)
							  inner join pv_proyecto1 pvy on (pvy.id_proyecto = pv.Proyecto)
							  inner join pv_actividad1 pva on (pva.id_actividad = pv.Actividad)
							  inner join pv_unidadejecutora pvun on (pvun.id_unidadejecutora = pv.UnidadEjecutora)
						 where
							  pv.CodPresupuesto = '".$field['CodPresupuesto']."' and 
							  pv.Organismo = '".$field['Organismo']."' and 
							  pv.EjercicioPpto  = '".$field['EjercicioPpto']."'";
				$qconsulta = mysql_query($sconsulta) or die ($sconsulta.mysql_error());
				$fconsulta = mysql_fetch_array($qconsulta);
	    ?>
       <td width="118" class="tagForm">Sector:</td><td width="32" align="center"><?=$fconsulta['codsector'];?></td><td width="420"><?=$fconsulta['DescpSector'];?></td><td width="191"></td><td width="53"></td><td width="58"></td>
    </tr>
    <tr>
      <td class="tagForm">Programa:</td><td align="center"><?=$fconsulta['codprograma'];?></td><td><?=$fconsulta['DescpPrograma'];?></td>
    </tr>
    <tr>
      <td class="tagForm">Subprograma:</td><td align="center"><?=$fconsulta['codsubprog'];?></td><td><?=$fconsulta['DescpSubprograma'];?></td>
    </tr>
    <tr>
      <td class="tagForm">Proyecto:</td><td align="center"><?=$fconsulta['codproyecto'];?></td><td><?=$fconsulta['DescpProyecto'];?></td>
    </tr>
    <tr>
      <td class="tagForm">Actividad:</td><td align="center"><?=$fconsulta['codactividad'];?></td><td><?=$fconsulta['DescpActividad'];?></td>
    </tr>
    <tr>
      <td class="tagForm">Unidad Ejecutora:</td><td></td><td><?=$fconsulta['UnidadEjecutora'];?></td>
    </tr>
   </table>
 </td>
</tr>
<!--<tr>
  <td>
   <div class='divBorder' style='width:1015px;'>
   <table width="1015" class="tblLista" border="0">
  <tr class="trListaHeadRpEjecucion">
		<th width="10">PAR</th><th width="10">GE</th><th width="10">ESP</th><th width="10">SE</th>
        <th width="300">DENOMINACION</th>
        <th width="20">DISPON. PRESUP.</th>
        <th width="50">DISPON. FINANC.</th>
        <th width="50">REINTEGRO.</th>
        <th width="50">INCREMENTO</th>
        <th width="50">DISMINUCION</th>
        <th width="50">PPTO. AJUSTADO</th>
        <th width="50">COMPROMISOS</th>
        <th width="50">CAUSADO</th>
        <th width="50">PAGADO</th>
  </tr>
   </table>
   </div>
  </td>
</tr>-->
<tr>
<td>
  <table align="center"><tr><td align="center"><div style="overflow:scroll; width:1020px; height:300px;">
   <table width="1250" class="tblLista" border="0">
   <tr class="trListaHeadRpEjecucion">
		<th width="10">PAR</th><th width="10">GE</th><th width="10">ESP</th><th width="10">SE</th>
        <th width="300">DENOMINACION</th>
        <th width="20">DISPON. PRESUP.</th>
        <th width="50">DISPON. FINANC.</th>
        <th width="50">REINTEGRO.</th>
        <th width="50">INCREMENTO</th>
        <th width="50">DISMINUCION</th>
        <th width="50">PPTO. AJUSTADO</th>
        <th width="50">COMPROMISOS</th>
        <th width="50">CAUSADO</th>
        <th width="50">PAGADO</th>
  </tr>
<? 
$total=0; $year=date("Y");
$sql="SELECT * 
        FROM pv_presupuesto 
       WHERE CodPresupuesto='".$field['CodPresupuesto']."' AND
	         Organismo='".$_SESSION['ORGANISMO_ACTUAL']."'";// Consulta el año del ejercicio presupuestario
$qry=mysql_query($sql) or die ($sql.mysql_error());
if(mysql_num_rows($qry)!=0){
  $field=mysql_fetch_array($qry);
  $sqlDet="SELECT * 
             FROM pv_presupuestodet 
            WHERE CodPresupuesto='".$field['CodPresupuesto']."' 
	     ORDER BY cod_partida";
  $query=mysql_query($sqlDet) or die ($sqlDet.mysql_error());
  $rows=mysql_num_rows($query);
  for($i=0; $i<$rows; $i++){
   $fielDet=mysql_fetch_array($query);
   ///  ORDENA PARTIDAS ////  ORDENA PARTIDAS POR TIPO=DETALLE "D"
   //// **** Obtengo Partidas Tipo "T" 301-00-00-00	**** ////  
   if(($fielDet['partida']!=00) and (($cont1==0) or ($pCapturada!=$fielDet['partida']))){
    $sqlP="SELECT * FROM pv_partida 
				   WHERE partida1='".$fielDet['partida']."' AND
						 cod_tipocuenta='".$fielDet['tipocuenta']."' AND
						 tipo='T' AND 
						 generica='00'";
    $qryP=mysql_query($sqlP) or die ($sqlP.mysql_error());
    if(mysql_num_rows($qryP)!=0){
	 $fieldP=mysql_fetch_array($qryP);
	 $montoP=0; $cont1=0;
	 $sqldet="SELECT * FROM pv_presupuestodet 
					  WHERE partida='".$fieldP['partida1']."' AND
							tipocuenta='".$fieldP['cod_tipocuenta']."' AND
							CodPresupuesto='".$fielDet['CodPresupuesto']."'";
	 $qrydet=mysql_query($sqldet);
	 $rwdet=mysql_num_rows($qrydet);
	 for($a=0; $a<$rwdet; $a++){
	  $fdet=mysql_fetch_array($qrydet);
	  $cont1 = $cont1 + 1;
	  $montoP = $montoP + $fdet['MontoAprobado'];
	 }
	 $montoAprobado=number_format($montoP,2,',','.');
	 $cont1= $cont1 + 1;
	 $codigo_partida = $fieldP[cod_partida];
	 $pCapturada = $fieldP[partida1];
	 /// -------------- MODULO CONSULTAS ------------- ///
	 $scon01 ="select 
	                  aj.CodAjuste,
					  aj.TipoAjuste
	              from
				      pv_ajustepresupuestario aj
					  inner join pv_ajustepresupuestariodet ajdet on (ajdet.CodPresupuesto = aj.CodPresupuesto)
				 where 
				      (aj.CodPresupuesto = '$fPresupuesto' or  aj.CodPresupuesto = '".$field['CodPresupuesto']."') and 
					  (aj.Periodo = '$fPeriodo')"; 
	 $qcon01 = mysql_query($scon01) or die ($scon01.mysql_error());
	 /// --------------------------------------------- ///
	 echo "<tr class='trListaBody6RpEjecucion'>
	  <td align='center' width='10'>".$fieldP['cod_tipocuenta']."".$fieldP['partida1']."</td>
	  <td align='center' width='10'>".$fieldP['generica']."</td>
	  <td align='center' width='10'>".$fieldP['especifica']."</td>
	  <td align='center' width='10'>".$fieldP['subespecifica']."</td>
	  <td width='300'>".$fieldP['denominacion']."</td>
	   <td align='center' width='50'><input type='text' class='inputP' size='12' maxlength='12' id='MontoAprobadoPar' style='text-align:right' value='$montoAprobado' readonly/></td>
	  <td align='center' width='50'><input type='text' class='inputP' size='12' maxlength='12' id='MontoAprobadoPar' style='text-align:right' value='$montoAprobado' readonly/></td>
	  <td width='50'><input type='text' class='inputP' size='12' maxlength='12' id='MontoAprobadoPar' style='text-align:right' value='' readonly/></td>
	  <td width='50'><input type='text' class='inputP' size='12' maxlength='12' id='MontoAprobadoPar' style='text-align:right' value='' readonly/></td>
	  <td width='50'><input type='text' class='inputP' size='12' maxlength='12' id='MontoAprobadoPar' style='text-align:right' value='' readonly/></td>
	  <td align='center' width='50'><input type='text' class='inputP' size='12' maxlength='12' id='MontoAprobadoPar' style='text-align:right' value='$montoAprobado' readonly/></td>
	  <td align='center' width='50'><input type='text' class='inputP' size='12' maxlength='12' readonly/></td>
	   <td align='center' width='50'><input type='text' class='inputP' size='12' maxlength='12' readonly/></td>
	  <td align='right' width='50'><b><input class='inputP' type='text' size='12' maxlength='12' id='".$codigo_partida."' style='text-align:right' name='".$fielDet['CodPresupuesto']."' value='' readonly/></td></b>         
	     </tr>";
	    }
	  }
  //////////////////////////////////////////////////////////////////////////////////////
  //// **** Obtengo Partidas Tipo "T" 301-01-00-00	**** ////
  if(($fielDet['generica']!=00) and (($cont2==0) or ($gCapturada!=$fielDet['generica']) or ($pCapturada2!=$fielDet['partida']))){
	$sqlP="SELECT * FROM pv_partida 
				   WHERE partida1='".$fielDet['partida']."' AND 
						 cod_tipocuenta='".$fielDet['tipocuenta']."' AND 
						 tipo='T' AND 
						 generica='".$fielDet['generica']."' AND 
						 especifica='00'";
	 $qryP=mysql_query($sqlP) or die ($sqlP.mysql_error());
	 if(mysql_num_rows($qryP)){
	   $fieldP=mysql_fetch_array($qryP);
	   $cont2=0; $montoG=0;
	   $sqldet="SELECT * FROM pv_presupuestodet 
						WHERE partida='".$fieldP['partida1']."' AND 
							  generica='".$fieldP['generica']."' AND 
							  tipocuenta='".$fieldP['cod_tipocuenta']."' AND
							  CodPresupuesto='".$fielDet['CodPresupuesto']."'";
      $qrydet=mysql_query($sqldet);
      $rwdet=mysql_num_rows($qrydet);
      for($b=0; $b<$rwdet; $b++){
	   $fdet=mysql_fetch_array($qrydet);
	   $cont2= $cont2 + 1;
	   $montoG= $montoG + $fdet['MontoAprobado'];
      } 
	  $montoGen=number_format($montoG,2,',','.');
      $codigo_generica = $fieldP[cod_partida];
      $pCapturada2 = $fieldP[partida1];
	  $gCapturada = $fieldP[generica];
	   echo "<tr class='trListaBody5RpEjecucion'>
		 <td align='center' width='10'>".$fieldP['cod_tipocuenta']."".$fieldP['partida1']."</td>
		 <td align='center' width='10'>".$fieldP['generica']."</td>
		 <td align='center' width='10'>".$fieldP['especifica']."</td>
		 <td align='center' width='10'>".$fieldP['subespecifica']."</td>
		 <td width='300'>".$fieldP['denominacion']."</td>
		 <td align='center' width='50'><b><input type='text' class='inputG' size='12' style='text-align:right' id='MontoAprobadoGen' value='$montoGen' readonly/></td>
		 <td align='center' width='50'><b><input type='text' class='inputG' size='12' style='text-align:right' id='MontoAprobadoGen' value='$montoGen' readonly/></td>
		 <td width='50'><input type='text' class='inputG' size='12' style='text-align:right' id='MontoReintegro' name='MontoReintegro' value='' readonly/></td>
		 <td width='50'><input type='text' class='inputG' size='12' style='text-align:right' id='MontoIncremento' name='MontoIncremento' value='' readonly/></td>
		 <td width='50'><input type='text' class='inputG' size='12' style='text-align:right' id='MontoDisminucion' name='MontoDisminucion' value='' readonly/></td>
		 <td align='center' width='50'><b><input type='text' class='inputG' size='12' maxlength='12' style='text-align:right' id='MontoAprobadoGen' value='$montoGen' readonly/></td>
		 <td align='center' width='50'><b><input type='text' class='inputG' size='12' maxlength='12' readonly/></td>
		 <td align='center' width='50'><b><input type='text' class='inputG' size='12' maxlength='12' readonly/></td>
		 <td align='right' width='50'><b><input type='text' class='inputG' size='12' maxlength='12' style='text-align:right' id='".$codigo_generica."' name='".$fielDet['CodPresupuesto']."' value='' readonly/></td></b>         
	   </tr>";
	  }
   }
	//////////////////////////////////////////////////////////////////////////////////////
    //// **** Obtengo Partidas Tipo "D" 301-01-01-01	**** ////
   if($fielDet['partida']!=00){
    //$cont=1;
	$s="SELECT cod_tipocuenta,
	           subespecifica,
			   partida1,
			   generica,
			   especifica,
			   cod_partida,
			   denominacion 
		   FROM 
		        pv_partida 
		   WHERE 
		        cod_partida='".$fielDet['cod_partida']."'";
	$q=mysql_query($s) or die ($s.mysql_error());
	$f=mysql_fetch_array($q);
    $monto = $fielDet['MontoAprobado'];
	$total = $monto + $total;
	$totalT=number_format($total,2,',','.');
	$montoD=number_format($monto,2,',','.');
    $codigo_detalle = $fielDet['cod_partida'];
    echo "<tr class='trListaBodyRpEjecucion' onclick='mClk(this,\"registro2\");'>
	<td align='center' width='10'>".$f['cod_tipocuenta']."".$f['partida1']."</td>
	<td align='center' width='10'>".$f['generica']."</td>
	<td align='center' width='10'>".$f['especifica']."</td>
	<td align='center' width='10'>".$f['subespecifica']."</td>
	<td width='300'>".$f['denominacion']."</td>
	<td align='right' width='50'><input type='text' size='12' id='MontoDetalle' style='text-align:right' value='$montoD' readonly/></td>
	<td align='right' width='50'><input type='text' size='12' id='MontoDetalle' style='text-align:right' value='$montoD' readonly/></td>
	 <td width='50'><input type='text' size='12' id='MontoReintegro' name='MontoReintegro' style='text-align:right' value='' readonly/></td>
	 <td width='50'><input type='text' size='12' id='MontoIncremento' name='MontoIncremento' style='text-align:right' value='' readonly/></td>
	 <td width='50'><input type='text' size='12' id='MontoDisminucion' name='MontoDisminucion' style='text-align:right' value='' readonly/></td>
	<td align='right' width='50'><input type='text' size='12' id='PptoAjustado' name='PptoAjustado' style='text-align:right' value='$montoD' readonly/></td>
	<td align='right' width='50'><input type='text' size='12' maxlength='12' readonly/></td>
	<td align='right' width='50'><input type='text' size='12' maxlength='12' readonly/></td>
	<td align='right' width='50'><input type='text' size='11' class='montoA' style='text-align:right' id='$codigo_partida|$codigo_generica' name='".$fielDet['CodPresupuesto']."' value='' onchange='sumarPartida(this.value, this.id);' onfocus='obtener(this.value);' readonly/></td>         
   </tr>";
	   }
}}?>
  </table></div></td></tr></table>
</td>
</tr>
</table>
</div>