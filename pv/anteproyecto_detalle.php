<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location:index.php");
//	------------------------------------
include ("fphp.php");
connect();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript02.js"></script>
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="fscript03.js"></script>
<script type="text/javascript" language="javascript" src="fscriptpresupuesto.js"></script>
<script type="text/javascript">
function Alarma2(){
  alert("ANTES DE REALIZAR LA ACCION DEBE GUARDAR LOS CAMBIOS REALIZADOS...!");
  return true;
}
</script>
<style type="text/css">
<!--
UNKNOWN {FONT-SIZE: small}
#header {FONT-SIZE: 93%; BACKGROUND: url(imagenes/bg.gif) #dae0d2 repeat-x 50% bottom; FLOAT: left; WIDTH: 100%; LINE-HEIGHT: normal}
#header UL {PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 10px; LIST-STYLE-TYPE: none}
#header LI {
        PADDING-RIGHT: 0px; PADDING-LEFT: 9px; BACKGROUND: url(imagenes/left.gif) no-repeat left top; FLOAT: left; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 0px}
#header A {
        PADDING-RIGHT: 15px; DISPLAY: block; PADDING-LEFT: 6px; FONT-WEIGHT: bold; BACKGROUND: url(imagenes/right.gif) no-repeat right top; FLOAT: left; PADDING-BOTTOM: 4px; COLOR: #765; PADDING-TOP: 5px; TEXT-DECORATION: none}
#header A { FLOAT: none}
#header A:hover {  COLOR: #333 }
#header #current { BACKGROUND-IMAGE: url(imagenes/left_on.gif)}
#header #current A { BACKGROUND-IMAGE: url(imagenes/right_on.gif); PADDING-BOTTOM: 5px; COLOR: #333 }
-->
</style>
</head>
<body>
<? 
$sql="SELECT * FROM pv_partida WHERE cod_partida='".$_POST['registro']."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if($rows!=0) $field=mysql_fetch_array($query);
if($field['Estado']=="A") $activo="checked"; else $inactivo="checked";
echo"<input type='hidden' id='ejercicioPpto' name='ejercicioPpto' value='".$_POST['ejercicioPpto']."'/>";
?>
<table width="100%" height="19" cellpadding="0" cellspacing="0">
<tr>
  <td class="titulo">Anteproyecto | Nuevo</td>
  <td align="right"><a class="cerrar"; href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'),'<?=$regresar?>.php')">[Cerrar]</a></td>
</tr>
</table><hr width="100%" color="#333333" />
<form id="frmentrada" name="frmentrada" action="anteproyecto_detalle.php?limit=0&accion=GuardarMontoPartidas" method="POST" onsubmit="return verificarDet(this,'Guardar')">
<? include "gmsector.php"; 
 echo"<input type='hidden' name='regresar' id='regresar' value='".$_POST['$regresar']."'/>";
?>
<table width="800" align="center">
<tr>
  <td>
	<div id="header">
	<ul>
	<!-- CSS Tabs PESTAÑAS OPCIONES DE PRESUPUESTO -->
	<li><a onClick="document.getElementById('tab1').style.display='block'; document.getElementById('tab2').style.display='none';" href="#">Datos Generales</a></li>
	<li><a onClick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='block';" href="#">Detalle de Presupuesto</a></li> 
	</ul>
	</div>
  </td>
</tr>
</table>
<!--////////////////// ***** ****** ****** **** **************** ************  ************ ******* ******** ********** ********* //////////////////// -->
<div id="tab2" style="display:block">
<div style="width:800px; height:15px" class="divFormCaption">Detalle de Presupuesto</div>
<!--////////////////// **************** MOSTRAR LA TABLA DE PARTIDAS  ************ //////////////////// -->
<input type="hidden" name="registro2" id="registro2" />
<input type="hidden" id="permitir" name="permitir" />
<input type="hidden" id="horaCreada" name="horaCreada" size="40" />
<input type="hidden" id="horaModif" name="horaModif" size="40" />
<table width="800" class="tblBotones">
<tr><td align="right">
  <input name="btNuevo" type="button" id="btNuevo" value="Agregar" onmouseup="Alarma(frmentrada)" onclick="cargarVentanaPart(this.form,'lista_partidas.php?pagina=anteproyecto_detalle.php?accion=AGREGAR&limit=0&ejercicioPpto=<?=$ejercicioPpto?>&regresar=<?=$regresar?>&target=main','height=500, width=800, left=200, top=200, resizable=yes');"/>
  <input name="btBorrar" type="button" id="btBorrar" value="Eliminar" onmouseup="Alarma2()" onClick="eliminarPartidaAnte(this.form,'anteproyecto_detalle.php?accion=ELIMINAR&registro=<?=$registro?>');"/>
  </td>
</tr>
</table>
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:800px; height:500px;">
<table width="100%" class="tblLista" border="0">
  <tr class="trListaHead">
		<th width="80" scope="col"># Partida</th>
		<th scope="300">Denominaci&oacute;n</th>
		<th width="44" scope="col">Estado</th>
		<th width="24" scope="col">Tipo</th>
		<th width="124" scope="col">MontoUtilizar</th>
  </tr>
<?php
//------------------------------------------------------------------------------------------------------------
///////////////////************ INSERTAR PARTIDAS ASOCIADAS AL ANTEPROYECTO *************/////////////////////
//////////////////************* CARGA LOS DATOS DE LA TABLA "pv_antepresupuestodet" ****///////////////////// 
//------------------------------------------------------------------------------------------------------------
$fecha=date("Y-m-d H:m:s");
if($accion=="AGREGAR"){ 
 $sqlPpto="SELECT * FROM pv_antepresupuesto WHERE EjercicioPpto='".$_POST['ejercicioPpto']."'";// Consulta el año del ejercicio presupuestario
 $qryPpto=mysql_query($sqlPpto) or die ($sqlPpto.mysql_error()); 
 $rPpto=mysql_num_rows($qryPpto);
 if($rPpto!=0){
  $fieldPpto=mysql_fetch_array($qryPpto); //echo $filas;
 for($i=0; $i<=$filas; $i++){
  if($_POST[$i]!=""){
   $sqlpartida=mysql_query("SELECT * FROM pv_partida WHERE cod_partida='".$_POST[$i]."'");/// CONSULTO PARA COMPARAR COD_PARTIDA
   if(mysql_num_rows($sqlpartida)!=0){
	$fieldP=mysql_fetch_array($sqlpartida);
	$sqlAntep="SELECT * FROM pv_antepresupuestodet 
	                   WHERE cod_partida='".$_POST[$i]."' AND 
					         CodAnteproyecto='".$fieldPpto['CodAnteproyecto']."'";
	$qryAntep=mysql_query($sqlAntep) or die ($sqlAntep.mysql_error());
	if(mysql_num_rows($qryAntep)!=0){
		echo"<script>";
		echo"alert('Los datos ya han sido ingresado anteriormente')";
		echo"</script>";
	}else{
		if($fieldP['tipo'] != 'T'){
	      $sqlAnt="SELECT MAX(Secuencia) FROM pv_antepresupuestodet WHERE CodAnteproyecto='".$fieldPpto['CodAnteproyecto']."'";
		  $qryAnt=mysql_query($sqlAnt) or die ($sqlAnt.mysql_error());
		  $fieldAnt=mysql_fetch_array($qryAnt);
		  $secuencia=(int) ($fieldAnt[0]+1); //echo"Secuencia=".$fieldAnt[0];
          $secuencia=(string) str_repeat("0", 4-strlen($secuencia)).$secuencia;
		  $sql="INSERT INTO pv_antepresupuestodet (Organismo,
			                                           CodAnteproyecto,
			                                           cod_partida,
													   partida,
													   generica,
													   especifica,
													   subespecifica,
													   tipocuenta,
													   Estado,
													   UltimoUsuario,
													   UltimaFechaModif,
													   tipo,
													   Secuencia) 
											    VALUES ('".$_SESSION['ORGANISMO_ACTUAL']."',
												        '".$fieldPpto['CodAnteproyecto']."',
												        '".$_POST[$i]."',
														'".$fieldP['partida1']."',
														'".$fieldP['generica']."',
														'".$fieldP['especifica']."',
														'".$fieldP['subespecifica']."',
														'".$fieldP['cod_tipocuenta']."',
														'PR',
														'".$_SESSION['USUARIO_ACTUAL']."',
														'$fecha',
														'".$fieldP['tipo']."',
														'$secuencia')";
			  $query=mysql_query($sql) or die ($sql.mysql_error());
}}}}}}}
else{
 if ($accion=="ELIMINAR") {
	$sql="DELETE FROM pv_antepresupuestodet WHERE Secuencia='".$registro2."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
}}
//------------------------------------------------------------------------------------------------------------
///////////////////************ MOSTRAR PARTIDAS ASOCIADAS AL ANTEPROYECTO *************/////////////////////
//////////////////************* CARGA LOS DATOS DE LA TABLA "pv_antepresupuestodet" ****///////////////////// 
//------------------------------------------------------------------------------------------------------------
$total=0;
$sql="SELECT * FROM pv_antepresupuesto WHERE EjercicioPpto='".$_POST['ejercicioPpto']."'";// Consulta el año del ejercicio presupuestario
$qry=mysql_query($sql) or die ($sql.mysql_error());
if(mysql_num_rows($qry)!=0){
  $field=mysql_fetch_array($qry);
  $sqlDet="SELECT * FROM pv_antepresupuestodet 
                   WHERE CodAnteproyecto='".$field['CodAnteproyecto']."' 
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
	 $sqldet="SELECT * FROM pv_antepresupuestodet 
					  WHERE partida='".$fieldP['partida1']."' AND
							tipocuenta='".$fieldP['cod_tipocuenta']."' AND
							CodAnteproyecto='".$fielDet['CodAnteproyecto']."'";
	 $qrydet=mysql_query($sqldet);
	 $rwdet=mysql_num_rows($qrydet);
	 for($a=0; $a<$rwdet; $a++){
	  $fdet=mysql_fetch_array($qrydet);
	  $cont1 = $cont1 + 1;
	  $montoP = $montoP + $fdet['MontoPresupuestado'];
	 }
	 $montoPar=number_format($montoP,2,',','.');
	 $cont1= $cont1 + 1;
	 $codigo_partida = $fieldP[cod_partida];
	 $pCapturada = $fieldP[partida1];
	 echo "<tr class='trListaBody6'>
	  <td align='center'>".$fieldP['cod_partida']."</td>
	  <td>".$fieldP['denominacion']."</td>
	  <td align='center'>".$fieldP['Estado']."</td>
	  <td align='center'>".$fieldP['tipo']."</td>
	  <td align='right'><b><input class='inputP' style='text-align:right' type='text' size='12' maxlength='12' id='".$codigo_partida."' name='".$fielDet['Secuencia']."' value='$montoPar' readonly/>Bs.F</td></b>         
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
	   $sqldet="SELECT * FROM pv_antepresupuestodet 
						WHERE partida='".$fieldP['partida1']."' AND 
							  generica='".$fieldP['generica']."' AND 
							  tipocuenta='".$fieldP['cod_tipocuenta']."' AND
							  CodAnteproyecto='".$fielDet['CodAnteproyecto']."'";
      $qrydet=mysql_query($sqldet);
      $rwdet=mysql_num_rows($qrydet);
      for($b=0; $b<$rwdet; $b++){
	   $fdet=mysql_fetch_array($qrydet);
	   $cont2= $cont2 + 1;
	   $montoG= $montoG + $fdet['MontoPresupuestado'];
      } 
	  $montoGen=number_format($montoG,2,',','.');
      $codigo_generica = $fieldP[cod_partida];
      $pCapturada2 = $fieldP[partida1];
	  $gCapturada = $fieldP[generica];
	   echo "<tr class='trListaBody5'>
		 <td align='center'>".$fieldP['cod_partida']."</td>
		 <td>".$fieldP['denominacion']."</td>
		 <td align='center'><b>".$fieldP['Estado']."</td>
		 <td align='center'><b>".$fieldP['tipo']."</td>
		 <td align='right'><b><input type='text' style='text-align:right' class='inputG' size='12' maxlength='12' id='".$codigo_generica."' name='".$fielDet['Secuencia']."' value='$montoGen' readonly/>Bs.F</td></b>         
	   </tr>";
	  }
   }
	   //////////////////////////////////////////////////////////////////////////////////////
	   //// **** Obtengo Partidas Tipo "D" 301-01-01-01	**** ////
   if($fielDet['partida']!=00){
    //$cont=1;
	$s="SELECT cod_partida,denominacion FROM pv_partida WHERE cod_partida='".$fielDet['cod_partida']."'";
	$q=mysql_query($s) or die ($s.mysql_error());
	$f=mysql_fetch_array($q);
    $monto = $fielDet['MontoPresupuestado'];
	if($monto!='0,00'){
	  $count= $count + 1;
	  echo"<input type='hidden' id='n' name='n' value='$count'/>";
	}
	$total = $monto + $total;
	$totalT=number_format($total,2,',','.');
	$montoD=number_format($monto,2,',','.');
    $codigo_detalle = $fielDet['cod_partida'];
    echo "<tr class='trListaBody' onclick='mClk(this,\"registro2\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$fielDet['Secuencia']."'>
		<td align='center'>".$fielDet['cod_partida']."</td>
		<td>".$f['denominacion']."</td>
		<td align='center'>".$fielDet['Estado']."</td>
		<td align='center'>".$fielDet['tipo']."</td>
		<td align='right'>
		<input type='text' style='text-align:right' size='11' class='montoA' maxlength='12' id='$codigo_partida|$codigo_generica' name='".$fielDet['Secuencia']."' value='$montoD' onchange='sumarPartida(this.value, this.id);' onfocus='obtener(this.value);' onBlur='numeroBlur(this);'/>Bs.F</td>         
	   </tr>";
	   }
}}echo"<tr><td colspan='3'></td>
               <td align='right'><b>Total:</b></td>
			   <td align='center' class='trListaBody'><b><input type='hidden' id='total' name='total' size='15' value='$total'/>";
			   echo"<input type='text' style='text-align:right' id='totalAnt' name='totalAnt' size='13' value='$totalT' readonly/>
			        <input type='hidden' class='inputT' id='totalAnt' name='totalAnt' size='13' value='$totalT' readonly/> Bs.F</b></td>
		   </tr>";
//	------------------------------------------------------------------------------------------------------------
//	------------------------------------------------------------------------------------------------------------
$rows=(int)$rows;
echo "
	<script type='text/javascript' language='javascript'>
		totalLista($registros);
		totalLotes(".intval($registros).", ".intval($rows).", ".intval($limit).");
	</script>";
?>
</table>
</div></td></tr></table>
<input type="hidden" id="montovoy" name="montovoy"/>
<script type="text/javascript" language="javascript">
	totalPuestos(<?=$rows?>);
</script>
</div>
<!--//////////////////*****************************************************//////////////////// -->
<div name="tab1" id="tab1" style="display:none;">
<?php
$limit=(int) $limit;
echo "
<input type='hidden' name='ejercicioPpto' id='ejercicioPpto' value='".$_POST['ejercicioPpto']."'/>
<input type='hidden' name='codantepres' id='codantepres' value='".$codantepres."'/>
<input type='hidden' name='filtro' id='filtro' value='".$filtro."' />
<input type='hidden' name='regresar' id='regresar' value='".$regresar."' />
<input type='hidden' name='forganismo' id='forganismo' value='".$forganismo."' />";

$sql="SELECT CodAnteproyecto FROM pv_antepresupuesto WHERE EjercicioPpto='2014' ";
$query=mysql_query($sql) or die ($sql.mysql_error());
if(mysql_num_rows($query)!=0){
  $field=mysql_fetch_array($query);
  $sqlAnt="SELECT * FROM pv_antepresupuesto WHERE CodAnteproyecto='".$field['0']."'";
  $qry=mysql_query($sqlAnt) or die ($sqlAnt.mysql_error());
 if(mysql_num_rows($qry)!=0){
   $fieldAnt=mysql_fetch_array($qry);
   list($a, $m, $d)=SPLIT( '[/.-]', $fieldAnt['FechaInicio']); $fInicio=$d.'-'.$m.'-'.$a;
   list($a, $m, $d)=SPLIT( '[/.-]', $fieldAnt['FechaFin']); $fFin=$d.'-'.$m.'-'.$a;
   list($a, $m, $d)=SPLIT( '[/.-]', $fieldAnt['FechaAnteproyecto']); $fAnt=$d.'-'.$m.'-'.$a;
   $limit=(int) $limit; 
}}
echo"<input type='hidden' name='CodAnteproyecto' id='CodAnteproyecto' value='".$fieldAnt['CodAnteproyecto']."'/>";
if($fieldAnt[Estado]==PE){$estado=Preparado;}
?>
<div style="width:800px" class="divFormCaption">Informaci&oacute;n de Presupuesto</div>
<table width="800" class="tblForm">

<tr> 
  <td width="123"></td>
  <td width="151" class="tagForm">Organismo:</td>
    <? $sql2=mysql_query("SELECT * FROM mastorganismos WHERE CodOrganismo='".$fieldAnt['Organismo']."'");
       if(mysql_num_rows($sql2)!=0){$field2=mysql_fetch_array($sql2);}?>
  <td width="342"><input name="organismo" id="organismo" value="<?=$field2['Organismo']?>" maxlength="100" size="65" readonly/></td>
  <td colspan="2"></td>
</tr>	
</table>
<table width="800" class="tblForm" border="0">
<tr><td height="2"></td></tr>
<tr>
    <td width="190"></td>
	<td width="75" align="right">Nro. Gaceta:</td>
	<? 
	  if($fieldAnt['FechaGaceta']!='0000-00-00'){list($a,$m,$d)=SPLIT('[/.-]',$fieldAnt['FechaGaceta']);$fgaceta=$d.'-'.$m.'-'.$a;}
	  if($fieldAnt['FechaDecreto']!='0000-00-00'){list($a,$m,$d)=SPLIT('[/.-]',$fieldAnt['FechaDecreto']);$fdecreto=$d.'-'.$m.'-'.$a;}
	?>
	<td width="70"><input name="gaceta" id="gaceta" type="text" size="8" value="<?=$fieldAnt['NumeroGaceta']?>" readonly/>*</td>
	<td width="65" align="right">F. Gaceta:</td>
	<td width="150"><input name="fgaceta" id="fgaceta" type="text" size="8" value="<?=$fgaceta?>" readonly/>*<i>(dd-mm-aaaa)</i></td>
	<td colspan="2" width="200"></td>
</tr>
<tr>
 <td width="190"></td>
 <td width="75" align="right">Nro. Decreto:</td>
 <td width="70"><input name="decreto" id="decreto" type="text" size="8" value="<?=$fieldAnt['NumeroDecreto']?>"  readonly/>*</td>
 <td width="65" align="right">F. Decreto:</td>
 <td width="150"><input name="fdecreto" id="fdecreto" type="text" size="8" value="<?=$fdecreto?>" readonly/>*<i>(dd-mm-aaaa)</i></td>
</tr>
<tr><td height="2"></td></tr>
</table>
<table width="800" class="tblForm">
<tr>
  <td width="163"></td>		
  <td class="tagForm">Ejercicio P.:</td>
  <td><? $ano = date(Y); // devuelve el año
	   $fcreacion= date("d-m-Y");//Fecha de Creación ?>
	<input name="anop" type="text" id="anop" size="3" value="<?=$fieldAnt[EjercicioPpto]?>" readonly /> 
	F.Creaci&oacute;n:<input name="fcreacion" type="text" id="fcreacion" size="8" value="<?=$fAnt?>" readonly/> 
	 Estado:<input name="estado" type="text" id="estado" size="13" value="<?=$estado?>" readonly/></td>
 <td></td>
</tr>
</table>

<table width="800" class="tblForm">
<tr>
  <td width="83"></td>
  <td width="181" class="tagForm">Sector:</td><? $sql="SELECT * FROM pv_sector,pv_programa1 WHERE pv_sector.cod_sector=pv_programa1.cod_sector";
												 $qry=mysql_query($sql) or die ($sql.mysql_error());
												 if(($field[cod_sector]==$fieldAnt[Sector]) and ($field[cod_sector]==$fieldAnt[Programa])){
												    
												 }
												 if(mysql_num_rows($qry)!=0){$fieldSector=mysql_fetch_array($qry);}
											  ?>
  <td width="520"><input name="sector" id="sector" value="<?=$fieldSector['descripcion']?>" size="50" readonly/></td>
</tr>
<tr>
  <td width="83"></td>
  <td class="tagForm">Programa:</td><? $sql="SELECT * FROM pv_programa1 WHERE id_programa='".$fieldAnt['Programa']."'";
												 $qry=mysql_query($sql) or die ($sql.mysql_error());
												 if(mysql_num_rows($qry)!=0){$fieldPrograma=mysql_fetch_array($qry);}
											  ?>
  <td><input name="programa" id="programa" value="<?=$fieldPrograma[descp_programa]?>" size="50" readonly/></td>
</tr>
<tr>
  <td width="83"></td>
  <td class="tagForm">Sub-Programa:</td><? $sql="SELECT * FROM pv_subprog1 WHERE id_sub='".$fieldAnt['SubPrograma']."'";
												 $qry=mysql_query($sql) or die ($sql.mysql_error());
												 if(mysql_num_rows($qry)!=0){$fieldSubprog=mysql_fetch_array($qry);}
											  ?>
  <td><input name="subprograma" id="subprograma" value="<?=$fieldSubprog[descp_subprog]?>" size="50" readonly/></td>
</tr>
<tr>
  <td width="83"></td>
  <td class="tagForm">Proyecto:</td><? $sql="SELECT * FROM pv_proyecto1 WHERE id_proyecto='".$fieldAnt['Proyecto']."'";
												 $qry=mysql_query($sql) or die ($sql.mysql_error());
												 if(mysql_num_rows($qry)!=0){$fieldProyecto=mysql_fetch_array($qry);}
											  ?>
  <td><input name="proyecto" id="proyecto" value="<?=$fieldProyecto[descp_proyecto]?>" size="50" readonly/></td>
</tr>
<tr>
  <td width="83"></td>
  <td class="tagForm">Actividad:</td><? $sql="SELECT * FROM pv_actividad1 WHERE id_actividad='".$fieldAnt['Actividad']."'";
												 $qry=mysql_query($sql) or die ($sql.mysql_error());
												 if(mysql_num_rows($qry)!=0){$fieldActividad=mysql_fetch_array($qry);}
											  ?>
  <td><input name="actividad" id="actividad" value="<?=$fieldActividad[descp_actividad]?>" size="50" readonly/></td>
</tr>
<tr>
 <td width="83"></td>
 <td class="tagForm">Unidad Ejecutora:</td>
 <td><input name="unidadejecutora" value="<?=$fieldAnt['UnidadEjecutora']?>"  size="50" readonly/></td>
</tr>

<tr><td></td></tr>
</table>
<div style="width:800px" class="divFormCaption">Duraci&oacute;n de Presupuesto</div>
<table width="800" class="tblForm"> 
<tr><td></td></tr>
<tr><td width="40"></td>
	<td class="tagForm">F. Inicio:</td>
	<td colspan="2"><input  name="finicio" type="text" id="finicio" size="10" maxlength="10" value="<?=$fInicio?>" readonly/>*(dd-mm-yyyy)</td>
</tr>	
<tr><td width="40"></td>
	<td class="tagForm">F. Termino:</td>
	<td colspan="2"><input name="ftermino" type="text" id="ftermino" size="10" maxlength="10" value="<?=$fFin?>" readonly/>*(dd-mm-yyyy)</td>
</tr>	
<tr><td width="40"></td>
	<td class="tagForm">Duraci&oacute;n:</td><? $date1=strtotime($fieldAnt['FechaInicio']);
												$date2=strtotime($fieldAnt['FechaFin']);
												$s = ($date1)-($date2);
												$d = intval($s/86400);
												$s -= $d*86400;
												$h = intval($s/3600);
												$s -= $h*3600;
												$m = intval($s/60);
												$s -= $m*60;
												
												$dif= (($d*24)+$h).hrs." ".$m."min";
												$dif2= abs($d.$space); $dif2=$dif2+1; ?>
	<td><input name="dias" type="text" style="text-align:right" id="dias" size="6" maxlength="3" value="<?=$dif2?>" readonly/> d&iacute;as.</td>
	<td colspan="1"></td>
</tr>
<tr><td></td></tr>
</table>
<div style="width:800px" class="divFormCaption">Duraci&oacute;n de Presupuesto</div>
<table width="800" class="tblForm">
<tr><td></td></tr>
<tr><td></td>
  <td width="246" class="tagForm">Monto Total Anteproyecto:</td><? $totalAnt=number_format($total,2,',','.')?>
  <td width="521"><input name="totalAnteproyecto" type="text" style="text-align:right" id="totalAnteproyecto" size="20" maxlength="15" value="<?=$totalAnt?>" readonly/>Bs.F</td>
</tr>
<tr><td></td>
  <td class="tagForm">Monto Autorizado:</td><? $sql="SELECT MontoPresupuestado FROM pv_antepresupuesto WHERE CodAnteproyecto='".$_GET['registro']."' "; 
											 $qry=mysql_query($sql) or die ($sql.mysql_error());
											 if(mysql_num_rows($qry)!=0){
											   $fieldAp=mysql_fetch_array($qry);
											 }
											 ?>
  <td><input name="montoautori" id="montoautori" type="text" size="20" maxlength="15" valor="<?=$fieldAp['MontoAprobado']?>" readonly/>Bs.F</td>
</tr>
<tr><td></td>
  <td class="tagForm">Diferencia:</td>
  <td><input name="diferencia" id="diferencia" type="text" size="20" maxlength="15" readonly/>Bs.F</td>
</tr>
<tr><td height="5"></td></tr>
<tr><td></td>
   <td class="tagForm">Preparado por:</td>
   <td><input name="prepor" id="prepor" type="text" size="60" value="<?=$fieldAnt['PreparadoPor']?>" readonly/></td>
<tr>
<tr><td></td>
   <td class="tagForm">Aprobado por:</td>
   <td><input name="nomempleado" id="nomempleado" type="text" size="60" value="<?=$fieldAnt['AprobadoPor']?>" readonly/></td>
</tr>
<tr><td></td>
	<td class="tagForm">&Uacute;ltima Modif.:</td>
	<td colspan="1"> 
	     <?
	       if($fieldAnt['UltimaFechaModif']!='0000-00-00 00:00:00'){
		      $usuario = $fieldAnt['UltimoUsuario']; $UltimaFecha=$fieldAnt['UltimaFechaModif']; 
		   }
	     ?>
		<input name="ult_usuario" type="text" id="ult_usuario" size="30" value="<?=$usuario?>" readonly />
		<input name="ult_fecha" type="text" id="ult_fecha" size="22" value="<?=$UltimaFecha?>" readonly />		</td>
</tr>
</table>
</div>
<center>
<input name="btguardar" type="submit" id="btguardar" value="Guardar Registro"/>
<input name="btcancelar" type="button" id="btcancelar" value="Cancelar" onclick="cargarPagina(this.form,'<?=$regresar?>');"/>
<input type="hidden" name="filas" id="filas" value="<?=$rows?>"/>
</center></div>
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</form>
</body>
</html>
<!-- VALIDACIONES CAMPOS OBLIGATORIOS  -->
<script language="javascript">
function Alarma(formulario){
    var cont = 0;
    var H_creada = document.getElementById('horaCreada').value; //alert('H_creada Alarma'+H_creada);
	var H_modif = document.getElementById('horaModif').value;  //alert('H_modif Alarma'+H_modif);
    var permitir = new Number(document.getElementById('permitir').value); //alert('Permitir= '+permitir);
	
    //var n = new Number(document.getElementById('n').value); alert('N= '+n);
   for (var i=0; formulario.elements.length; i++) {//SE RECORRE TODO LOS CAMPOS LOS MISMOS DEBEN CONTENER UNA CANTIDAD ASIGNADA
	if (formulario.elements[i].className == "montoA"){
	  //alert(formulario.elements[i].value);
	  if((formulario.elements[i].value!='0,00')&&(H_creada!=H_modif)&&(cont==0)){ 
	     //alert('Paso 1');
	     document.getElementById('permitir').value = 1; //alert('Permitir Alarma1='+document.getElementById('permitir').value);
	     alert('DEBE GUARDAR LOS REGISTROS ANTES DE INGRESAR OTRAS PARTIDAS...!');
		 cont = cont + 1;
		 formulario.elements[i].focus();
	  }else{
	     if((formulario.elements[i].value=='0,00')&&(H_creada==H_modif)){ 
	       document.getElementById('permitir').value = 0; //alert('Permitir Alarma2='+document.getElementById('permitir').value);
		 }
	  }
 	}
  }
}

function verificarDet(formulario) {
  var contador = 0;
  for (var i=0; formulario.elements.length; i++) {//SE RECORRE TODO LOS CAMPOS LOS MISMOS DEBEN CONTENER UNA CANTIDAD ASIGNADA
	if ((formulario.elements[i].className == "montoA")&&(contador==0)){
	  //alert(formulario.elements[i].value);
	  if((formulario.elements[i].value =='') || (formulario.elements[i].value =='0,00')){
	    alert('NO PUEDE DEJAR CAMPO(S) VACIO(S)...!');
		contador = contador + 1;
		formulario.elements[i].focus();
	  }
	}
  }
  if(contador == 0){ 
	       document.getElementById('permitir').value = 0; //alert('Permitir Verificar Det= '+document.getElementById('permitir').value);
		   document.getElementById('horaModif').value = 0;
		   document.getElementById('horaCreada').value = 0;
  }
} 
</SCRIPT>
<table><tr><td valign="middle"><iframe style="border:none;" align="left"></td></tr></table>
