<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location:index.php");
//	------------------------------------
include ("fphp.php");
include ("fphp02.php");
extract($_POST);
extract($_GET);
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('01', $concepto);
//	------------------------------------
$sql="SELECT * FROM pv_antepresupuesto 
              WHERE CodAnteproyecto='".$_POST['registro']."'";
$qry=mysql_query($sql) or die ($sql.mysql_error());
if(mysql_num_rows($qry)!=0){
 $field=mysql_fetch_array($qry);
 if(($field[Estado]==AP)or($field[Estado]==GE)){
   echo"<script>
        alert('NO PUEDE SER EDITADO');
        history.back(-1);
      </script>";
 }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript02.js"></script>
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="fscript03.js"></script>
<script type="text/javascript" language="javascript" src="fscript04.js"></script>
<script type="text/javascript" language="javascript" src="fscriptpresupuesto.js"></script>
<script type="text/javascript">
function Alarma2(){
  alert("ANTES DE REALIZAR LA ACCION DEBE GUARDAR LOS CAMBIOS REALIZADOS...!");
  return true;
}
</script>
<script type="text/javascript" language="javascript">
		 function comparaFecha(){
		  
		   var str1 = document.getElementById("fdesde").value;
           var str2 = document.getElementById("fhasta").value;
		   
		   var dt1  = parseInt(str1.substring(0,2),10);
		   var mon1 = parseInt(str1.substring(3,5),10);
		   var yr1  = parseInt(str1.substring(6,10),10);
		   var dt2  = parseInt(str2.substring(0,2),10);
		   var mon2 = parseInt(str2.substring(3,5),10);
		   var yr2  = parseInt(str2.substring(6,10),10);
		   var date1 = new Date(yr1, mon1, dt1);
		   var date2 = new Date(yr2, mon2, dt2); 
		   
		   var diferencia = date1 - date2;
           var Dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));
		       Dias = -1 * Dias;
		       document.getElementById("dias").value= Dias + 1;
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
echo"<input type='hidden' id='ejercicioPpto' name='ejercicioPpto' value='".$_POST['ejercicioPpto']."'/>
	 <input type='hidden' id='ejercicioPpto2' name='ejercicioPpto2' value='".$_POST['ejercicioPpto2']."'/>
     <input type='hidden' id='cod_anteproyecto' name='cod_anteproyecto' value='".$_POST['registro']."'/>";
?>
<table width="100%" height="19" cellpadding="0" cellspacing="0">
<tr>
  <td class="titulo">Anteproyecto | Actualizar</td>
  <td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'),'<?=$regresar?>.php?limit=0')">[Cerrar]</a></td>
</tr>
</table><hr width="100%" color="#333333" />
<form id="frmentrada" name="frmentrada" action="anteproyecto_listareditar.php?limit=0&accion=GuardarMontoEditado" method="POST" onsubmit="return verificarEditar(this, 'Guardar');">
<? include "gmsector.php";
   echo"<input type='hidden' id='registro' name='registro' value='".$_POST['registro']."'/>";
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
<!---------------------------------------- PESTAÑA Nº 2 ------------------------------------------------->
<div id="tab2" style="display:block;">
<div style="width:800px; height:15px" class="divFormCaption">Detalle de Presupuesto</div>
<!--////////////////// **************** MOSTRAR LA TABLA DE PARTIDAS  ************ //////////////////// -->
<input type="hidden" name="registro2" id="registro2" />
<input type="hidden" id="permitir" name="permitir" />
<input type="hidden" id="horaCreada" name="horaCreada" size="40" />
<input type="hidden" id="horaModif" name="horaModif" size="40" />
<table width="800" class="tblBotones">
<tr>
 <td align="right">
  <input type="button" name="btNuevo"  id="btNuevo" value="Agregar" onmouseup="Alarma(frmentrada)" onclick="cargarVentanaPart(this.form,'lista_partidas.php?pagina=anteproyecto_listareditar.php?accion=AGREGAR&limit=0&registro=<?=$registro?>&regresar=<?=$regresar?>&target=main','height=500, width=800, left=200, top=200, resizable=yes');"/>
<input name="btBorrar" type="button" id="btBorrar" value="Eliminar" onmouseup="Alarma2()" onClick="eliminarPartidaAnte(this.form,'anteproyecto_listareditar.php?accion=ELIMINAR&registro=<?=$registro?>');"/>
  </td>
</tr>
</table>
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:800px; height:500px;">
<table width="100%" class="tblLista" border="0">
  <tr class="trListaHead">
		<th width="88" scope="col">Partida</th>
		<th scope="300">Denominaci&oacute;n</th>
		<th width="44" scope="col">Estado</th>
		<th width="24" scope="col">Tipo</th>
		<th width="124" scope="col">MontoUtilizar</th>
  </tr>
<?php
//------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------
$fecha=date("Y-m-d H:m:s");
$year=date("Y");
//echo $accion;
if($accion=="AGREGAR"){
  for($i=1; $i<=$filas; $i++){
    if($_POST[$i]!=""){
      $SPARTIDA="SELECT * 
                   FROM pv_partida 
  	              WHERE cod_partida='".$_POST[$i]."'"; //echo $SPARTIDA;
      $QPARTIDA=mysql_query($SPARTIDA) or die ($SPARTIDA.mysql_error()); 
      if(mysql_num_rows($QPARTIDA)!=0){
	    $FPARTIDA=mysql_fetch_array($QPARTIDA);
	    $SPRE2="SELECT * 
                  FROM pv_antepresupuestodet 
		  	     WHERE cod_partida='".$_POST[$i]."' AND
				       CodAnteproyecto='".$_POST['registro']."' AND
					   Organismo='".$_SESSION['ORGANISMO_ACTUAL']."'";
	    $QPRE2=mysql_query($SPRE2) or die ($SPRE2.mysql_error());
	    if(mysql_num_rows($QPRE2)!=0){
	         echo"<script>";
	         echo"alert('!UNA(S) DE LA(S) PARTIDA(S) HA SIDO INGRESADA ANTERIORMENTE¡')";
	         echo"</script>";
	    }else{
		  if($FPARTIDA['tipo']!='T'){
		// ** CONSULTA PARA OBTENER EL MAX DE SECUENCIAS EXISTENTES
		 $SPDET="SELECT MAX(Secuencia) FROM pv_antepresupuestodet  
		                              WHERE CodAnteproyecto='".$_POST['registro']."' AND 
									        Organismo='".$_SESSION['ORGANISMO_ACTUAL']."'";
		 $QPDET=mysql_query($SPDET) or die ($SPDET.mysql_error());
		 $FPDET=mysql_fetch_array($QPDET);
		 $secuencia=(int) ($FPDET[0]+1); //echo"Secuencia1=".$FPDET[0];
		 $secuencia=(string) str_repeat("0", 4-strlen($secuencia)).$secuencia; 
		 $SQL="INSERT INTO pv_antepresupuestodet (Organismo,
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
											'".$_POST['registro']."',
											'".$_POST[$i]."',
											'".$FPARTIDA['partida1']."',
											'".$FPARTIDA['generica']."',
											'".$FPARTIDA['especifica']."',
											'".$FPARTIDA['subespecifica']."',
											'".$FPARTIDA['cod_tipocuenta']."',
											'PR',
											'".$_SESSION['USUARIO_ACTUAL']."',
											'$fecha',
											'".$FPARTIDA['tipo']."',
											'$secuencia')"; //echo $SQL;
		$QUERY=mysql_query($SQL) or die ($SQL.mysql_error());}
}}}}
}else{
 if ($accion=="ELIMINAR") {
	$sql="DELETE FROM pv_antepresupuestodet WHERE Secuencia='".$registro2."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
}}
//------------------------------------------------------------------------------------------------------------
///////////////////************ MOSTRAR PARTIDAS ASOCIADAS AL ANTEPROYECTO *************/////////////////////
//////////////////************* CARGA LOS DATOS DE LA TABLA "pv_antepresupuestodet" ****/////////////////////
//------------------------------------------------------------------------------------------------------------
$total=0; $monto=0;
$sql="SELECT * FROM pv_antepresupuesto WHERE CodAnteproyecto='".$_POST['registro']."' OR CodAnteproyecto='".$_GET['registro']."'"; 
$qry=mysql_query($sql) or die ($sql.mysql_error());
if(mysql_num_rows($qry)!=0){
 $field=mysql_fetch_array($qry);
 $sqlDet="SELECT * FROM pv_antepresupuestodet WHERE CodAnteproyecto='".$field['CodAnteproyecto']."' ORDER BY cod_partida";
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
	    <td align='right'><input type='text' size='12' class='inputP' style='text-align:right' maxlength='12' id='".$codigo_partida."' name='".$fielDet['IdAnteProyectoDet']."' value='$montoPar' readonly />Bs.F</td>
	    </tr>";
     }
	}
	//////////////////////////////////////////////////////////////
	//// **** Obtengo Partidas Tipo "T" 301-01-00-00	**** ////
	if(($fielDet['partida']!=00) and (($cont2==0) or ($gCapturada!=$fielDet['generica']) or ($pCapturada2!=$fielDet['partida']))){
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
		$cont2= $cont2 + 1;
		$codigo_generica = $fieldP[cod_partida];
		$gCapturada = $fieldP[generica];
		$pCapturada2 = $fieldP[partida1];
		echo "<tr class='trListaBody5'>
		    <td align='center'>".$fieldP['cod_partida']."</td>
		    <td>".$fieldP['denominacion']."</td>
		    <td align='center'>".$fieldP['Estado']."</td>
		    <td align='center'>".$fieldP['tipo']."</td>
		    <td align='right'><input type='text' size='12' class='inputG' style='text-align:right' maxlength='12' id='".$codigo_generica."' name='".$fielDet['IdAnteProyectoDet']."'  value='$montoGen' readonly/>Bs.F</td>         
	      </tr>";
	  }
	 }


	//////////////////////////////////////////////////////////////
	//// **** Obtengo Partidas Tipo "T" 301-01-01-00	**** ////
	if(($fielDet['partida']!=00) and (($cont2==0) or ($gCapturada3!=$fielDet['especifica']) or ($pCapturada!=$fielDet['partida']))){
	  $sqlP="SELECT * FROM pv_partida 
	                 WHERE partida1='".$fielDet['partida']."' AND 
					       cod_tipocuenta='".$fielDet['tipocuenta']."' AND 
						   tipo='T' AND 
						   generica='".$fielDet['generica']."' AND 
						   especifica='".$fielDet['especifica']."' " ;
	  $qryP=mysql_query($sqlP) or die ($sqlP.mysql_error());
	  if(mysql_num_rows($qryP)){
	   $fieldP=mysql_fetch_array($qryP);
	   $cont2=0; $montoG=0;
	   $sqldet="SELECT * FROM pv_antepresupuestodet 
	                    WHERE partida='".$fieldP['partida1']."' AND 
						      generica='".$fieldP['generica']."' AND 
							  especifica='".$fielDet['especifica']."' AND
							  							  
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
		$cont2= $cont2 + 1;
		$codigo_generica = $fieldP[cod_partida];
		$gCapturada3 = $fielDet[especifica];
		$gCapturada = $fieldP[generica];
		$pCapturada2 = $fieldP[partida1];
		echo "<tr class='trListaBody5'>
		    <td align='center'>".$fieldP['cod_partida']."</td>
		    <td>".$fieldP['denominacion']."</td>
		    <td align='center'>".$fieldP['Estado']."</td>
		    <td align='center'>".$fieldP['tipo']."</td>
		    <td align='right'><input type='text' size='12' class='inputG' style='text-align:right' maxlength='12' id='".$codigo_generica."' name='".$fielDet['IdAnteProyectoDet']."'  value='$montoGen' readonly/>Bs.F</td>         
	      </tr>";
	  }
	 }

	 /////////////////////////////////////////////////////////////
	 //// **** Obtengo Partidas Tipo "D" 301-01-01-01	**** ////
	 if($fielDet['partida']!=00){
	    $s="SELECT cod_partida,denominacion FROM pv_partida WHERE cod_partida='".$fielDet['cod_partida']."'";
		$q=mysql_query($s) or die ($s.mysql_error());
		$f=mysql_fetch_array($q);
		$contTotal = $cont;
	    $monto = $fielDet['MontoPresupuestado'];
	    $total = $monto + $total;
		$totalT=number_format($total,2,',','.');
		$montoD=number_format($monto,2,',','.');
	    $codigo_detalle = $fielDet['cod_partida'];
		echo "<tr class='trListaBody' onclick='mClk(this,\"registro2\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$fielDet['Secuencia']."'>
		     <td align='center'>".$fielDet['cod_partida']."</td>
		     <td>".$f['denominacion']."</td>
		     <td align='center'>".$fielDet['Estado']."</td>
	         <td align='center'>".$fielDet['tipo']."</td>
	         <td align='right'><input type='text' size='11' style='text-align:right' maxlength='12' class='montoA' id='$codigo_partida|$codigo_generica' name='".$fielDet['Secuencia']."' value='$montoD' onchange='sumarPartida(this.value, this.id);'  onfocus='obtener(this.value);' onBlur='numeroBlur(this);'/>Bs.F</td>         
	       </tr>";
     }
}}echo"<tr><td colspan='3'></td>
         <td align='right'><b>Total:</b></td>
		 <td align='center' class='trListaBody'><b>
		     <input type='hidden' class='inputT' id='total' name='total' size='13' value='$total'/>";
		     echo"<input type='text' style='text-align:right' id='totalAnt' name='totalAnt' size='13' value='$totalT' readonly/>
			      <input type='hidden' class='inputT' id='totalT' name='totalAnt' size='13' value='$totalT' readonly/>Bs.F</b></td>
		</tr>";
//	------------------------------------------------------------------------------------------------------------
//	------------------------------------------------------------------------------------------------------------
$rows=(int)$rows;
echo "
	<script type='text/javascript' language='javascript'>
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
<!--////////////////// **************** PESTAÑA Nº 1  ************//////////////////// -->
<!--////////////////// ************      **********               //////////////////// -->
<div name="tab1" id="tab1" style="display:none;">
<?php
$limit=(int) $limit;
echo "
<input type='hidden' name='cod_anteproyecto' id='cod_anteproyecto' value='".$_POST['cod_anteproyecto']."'
<input type='hidden' name='ejercicioPpto' id='ejercicioPpto' value='".$_POST['ejercicioPpto']."'/>
<input type='hidden' name='ejercicioPpt2' id='ejercicioPpto2' value='".$_POST['ejercicioPpto2']."'/>
<input type='hidden' name='codantepres' id='codantepres' value='".$codantepres."'/>
<input type='hidden' name='filtro' id='filtro' value='".$filtro."' />
<input type='hidden' name='regresar' id='regresar' value='".$regresar."' />
<input type='hidden' name='forganismo' id='forganismo' value='".$forganismo."' />";
////////// ******* ******* CONSULTO TABLA PARA VERIFICAR DATOS EXISTENTES ******** *******//////////
$sqlAnt="SELECT * FROM pv_antepresupuesto 
                 WHERE (CodAnteproyecto='".$_POST['registro']."' OR CodAnteproyecto='".$_GET['registro']."') AND 
				       Organismo='".$_SESSION['ORGANISMO_ACTUAL']."'";
$qryAnt=mysql_query($sqlAnt) or die ($sqlAnt.mysql_error());
if(mysql_num_rows($qryAnt)!=0){
  $fieldAnt=mysql_fetch_array($qryAnt);
   list($a, $m, $d)=SPLIT( '[/.-]', $fieldAnt['FechaAnteproyecto']); $fAnteproyecto=$d.'-'.$m.'-'.$a;
   list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaDesde']); $fdesde=$d.'/'.$m.'/'.$a;
   list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaHasta']); $fhasta=$d.'/'.$m.'/'.$a;
   list($a, $m, $d)=SPLIT( '[/.-]', $fieldAnt['FechaInicio']); $fInicio=$d.'-'.$m.'-'.$a;
   list($a, $m, $d)=SPLIT( '[/.-]', $fieldAnt['FechaFin']); $fFin=$d.'-'.$m.'-'.$a;
}
echo"<input type='hidden' name='fieldPrograma' id='fieldPrograma' value='".$fieldAnt['Programa']."'";
echo"<input type='hidden' name='CodAnteproyecto' id='CodAnteproyecto' value='".$fieldAnt['CodAnteproyecto']."'";
echo"<input type='hidden' name='UnidadEjecutora' id='UnidadEjecutora' value='".$fieldAnt['UnidadEjecutora']."'";
$sql="SELECT * FROM pv_sector,pv_programa1,pv_subprog1,pv_actividad1,pv_proyecto1 WHERE 1";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0){
 $field=mysql_fetch_array($query);

 $limit=(int) $limit;
}
?>
<!--////////////////////////// **********DATOS GENERALES DEL PRESUPUESTO*************  ///////////////////////--> 
<!--////////////////////////// **********  ///// ///// ///// ///// //// *************  ///////////////////////-->  
<div style="width:800px" class="divFormCaption">Informaci&oacute;n de Presupuesto</div>
<table width="800" class="tblForm">
<tr>
<td width="123"></td>
<td width="151" class="tagForm">Organismo:</td>
<td width="342">
	<select name="organismo" id="organismo" class="selectBig">
	<?php 
		// segundo bloque php //* Conectamos a los datos *//
		include "conexion_.php";
		$sql="SELECT CodOrganismo,Organismo FROM mastorganismos WHERE 1";
		$rs=mysql_query($sql);
		while($reg=mysql_fetch_assoc($rs)){
		$codOrganismo=$reg['CodOrganismo'];// Codigo del orgnismo
		$organismo=$reg['Organismo'];// Descripcion del Organismo
		   echo "<option value=$codOrganismo>$organismo</option>";
		}
	?></select></td>
	<td colspan="2"></td>
</tr>
</table>
<table width="800" class="tblForm">
<tr><td height="2"></td></tr>
<tr>
 <td width="199"></td>
 <td width="75" align="right">Nro. Gaceta:</td>
 <td width="70"><input name="gaceta" id="gaceta" type="text" size="8" readonly/>*</td>
 <td width="65" align="right">F. Gaceta:</td>
 <td width="150"><input name="fgaceta" id="fgaceta" type="text" size="8" maxlength="10" onchange="validaFormatoFecha()" readonly/>*<i>(dd-mm-aaaa)</i></td>
 <td colspan="2" width="200"></td>
</tr>
<tr>
 <td width="190"></td>
 <td width="75" align="right">Nro. Decreto:</td>
 <td width="70"><input name="decreto" id="decreto" type="text" size="8" readonly/>*</td>
 <td width="65" align="right">F. Decreto:</td>
 <td width="150"><input name="fdecreto" id="fdecreto" type="text" size="8" maxlength="10" onchange="validaFormatoFecha()" readonly/>*<i>(dd-mm-aaaa)</i></td>
</tr>
<tr><td height="2"></td></tr>
</table>
<table width="800" class="tblForm">
<tr>
<td width="163"></td>		
	<td class="tagForm">A&ntilde;o:</td>
	<td><? $ano = date(Y); // devuelve el año
		   $fcreacion= date("d-m-Y");// Fecha de Creación 
		$variable = explode("_", $fieldAnt[EjercicioPpto]);
		?>
		<input title="A&ntilde;o de Presupuesto"  pattern="[0-9]{4}" placeholder="Introduzca solo numeros" name="ejercicioPpto" type="tel" style="text-align:right" id="ejercicioPpto" value="<?=$variable[0]?>" size="20" maxlength="4" readonly />*
    Partida:<input title="Partida"  pattern="[0-9]{3}" placeholder="Introduzca solo numeros" name="ejercicioPpto2" type="tel" style="text-align:right" id="ejercicioPpto2" value="<?=$variable[1]?>" size="20" maxlength="3" readonly  />* 
		F.Creaci&oacute;n:<input name="fcreacion" type="text" id="fcreacion" size="8" value="<?=$fAnteproyecto?>" readonly />
		 <? if($fieldAnt[Estado]==PE){$estado=Preparado;}if($fieldAnt[Estado]==AN){$estado=Anulado;} ?> 
		 Estado:<input name="estado" type="text" id="estado" size="11" value="<?=$estado?>" readonly/></td>
</tr>
</table>
<!---////////////////////////////////////////////////////////////////////////////////////////////////-->
<!---/////////////////// ********   CARGAR SELECT SECTOR PRESUPUESTO  *******   /////////////////////-->
<table width="800" class="tblForm">
<tr>
  <td width="100"></td>
  <td width="181" class="tagForm">Sector:</td>
  <td width="520"><select name="sector" id="sector" class="selectBig" onchange="getOptionsEd_5(this.id, 'programa', 'subprograma', 'proyecto', 'actividad');">
	<option value=""></option>
	<?php getSector2('', $fieldAnt[Sector], 0); ?>
  </select>*</td>
</tr>
<tr>
  <td width="83"></td>
  <td class="tagForm">Programa:</td>
  <td><select name="programa" id="programa" class="selectBig" onchange="getOptionsEd_4(this.id, 'subprograma', 'proyecto','actividad')">
		<option value="">
		<?php	getPrograma2($fieldAnt[Programa], $fieldAnt[Sector], 0); ?>
	  </select>*</td>
</tr>
<tr>
  <td width="83"></td>
  <td class="tagForm">Actividad:</td>
  <td><select name="subprograma" id="subprograma" class="selectBig" onchange="getOptionsEd_3(this.id,'proyecto','actividad')">
			<option value=""></option>
			<?php getSubprograma2($fieldAnt[SubPrograma], $fieldAnt[Programa], 0); ?>
	</select>*</td>
</tr>
<tr>
   <td width="83"></td>
   <td class="tagForm">Unidad Ejecutora:</td>
   <td><select name="unidadejecutora" id="unidadejecutora" class="selectBig">

   		<option><?echo $fieldAnt[UnidadEjecutora]?></option>
	       <?
		    $SUNIDAD="SELECT id_unidadejecutora,Unidadejecutora FROM pv_unidadejecutora";
			$QUNIDAD=mysql_query($SUNIDAD) or die ($SUNIDAD.mysql_error());
			while($RUNIDAD=mysql_fetch_assoc($QUNIDAD)){
			  $id= $RUNIDAD[id_unidadejecutora];
			  $u_ejecutora= $RUNIDAD[Unidadejecutora];
			  if($fieldAnt[UnidadEjecutora]==$u_ejecutora){
			     echo"<option value='$u_ejecutora'>$u_ejecutora</option>";
			  }else{
			    echo"<option value='$u_ejecutora'>$u_ejecutora</option>";
			  }
			}
		   ?>
	      </select>*</td>
</tr>
<tr>
  <td width="83"></td>
  <td class="tagForm">Proyecto:</td>
  <td>
		<select name="proyecto" id="proyecto" class="selectBig" onchange="getOptionsEd_2(this.id,'actividad')">
			<option value=""></option>
			<?php getProyecto2($fieldAnt[Proyecto], $fieldAnt[SubPrograma], 0); ?>
		</select>	  </td>
</tr>
<tr>
  <td width="83"></td>
  <td class="tagForm">Sub-Programa:</td>
  <td>
		<select name="actividad" id="actividad" class="selectBig">
			<option value=""></option>
			<?php getActividad2($fieldAnt[Actividad], $fieldAnt[Proyecto], 0); ?>
		</select>	  </td>
</tr>
</table>
<div style="width:800px" class="divFormCaption">Duraci&oacute;n de Presupuesto</div>
<table width="800" class="tblForm"> 
<tr><td></td></tr>
<tr><td width="50"></td>
	<td class="tagForm">F. Inicio:</td>
	<td colspan="2"><input name="fdesde" type="text" id="fdesde" size="10" value="<?=$fInicio?>" maxlength="10"/>*<i>(dd-mm-aaaa)</i></td>
</tr>	
<tr><td></td>
	<td class="tagForm">F. Termino:</td>
	<td colspan="2"><input name="fhasta" type="text" id="fhasta" size="10" value="<?=$fFin?>" maxlength="10"/>*<i>(dd-mm-aaaa)</i></td>
</tr>	
<tr><td></td>
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
												$dif2= abs($d.$space); $dif2 = $dif2 + 1;
											 ?>
	<td><input type="text" name="dias"  id="dias" size="6" maxlength="3" style="text-align:right" onclick="comparaFecha()" value="<?=$dif2?>" readonly/> d&iacute;as.</td>
	<td colspan="1"></td>
</tr>
<tr><td></td></tr>
</table>
<!---  TABLA 2 ------>
<div style="width:800px" class="divFormCaption">Monto de Presupuesto</div>
<table width="800" class="tblForm">
<tr><td width="30"></td></tr>
<tr>
  <td></td>
  <td width="248" class="tagForm">Monto Total Anteproyecto:</td><? $totalAnt=number_format($total,2,',','.')?>
  <td width="521"><input name="totalAnteproyecto" type="text" id="totalAnteproyecto" size="20" maxlength="15" style="text-align:right" value="<?=$totalAnt?>" readonly/>Bs.F</td>
</tr>
<tr><td></td>
  <td class="tagForm">Monto Autorizado:</td><? $sql="SELECT MontoPresupuestado FROM pv_antepresupuesto WHERE CodAnteproyecto='".$fieldAnt['CodAnteproyecto']."'"; 
											 $qry=mysql_query($sql) or die ($sql.mysql_error());
											 if(mysql_num_rows($qry)!=0){
											   $fieldAp=mysql_fetch_array($qry);
											 }
											 ?>
  <td><input name="montoautori" id="montoautori" type="text" size="20" maxlength="15" style="text-align:right"  readonly/>Bs.F</td>
</tr>
<tr><td></td>
  <td class="tagForm">Diferencia:</td>
  <td><input name="diferencia" id="diferencia" type="text" size="20" maxlength="15" style="text-align:right"  readonly/>Bs.F</td>
</tr>
<tr><td></td></tr>
<tr><td></td>
   <td class="tagForm">Preparado por:</td><? $sql3=mysql_query("SELECT * FROM usuarios WHERE Usuario='".$_SESSION['USUARIO_ACTUAL']."'");
											 if(mysql_num_rows($sql3)!=0){
											   $field3=mysql_fetch_array($sql3);
											   $sql4=mysql_query("SELECT * FROM mastpersonas WHERE CodPersona='".$field3['CodPersona']."'");
											   if(mysql_num_rows($sql4)!=0){
												 $field4=mysql_fetch_array($sql4);
											   }
											 }
										  ?>
   <td><input name="prepor" id="prepor" type="text" size="60" value="<?=$fieldAnt[PreparadoPor]?>" readonly/></td>
<tr>
<tr><td></td>
   <td class="tagForm">Aprobado por:</td>
   <td><input name="codempleado" type="hidden" id="codempleado" value="" />
	   <input name="nomempleado" id="nomempleado" type="text" size="60"  value="<?=$fieldAnt[AprobadoPor] ?>" readonly/>
	   <input name="bt_examinar" id="bt_examinar" type="button" value="..." onclick="cargarVentana(this.form, 'lista_empleados.php?limit=0&campo=1', 'height=500, width=800, left=200, top=200, resizable=yes');" /> </td>
</tr>
<tr><td></td>
	<? 
	   echo"<td class='tagForm'>&Uacute;ltima Modif.:</td>
		   <td>";
		    if($fieldAnt[UltimaFechaModif]!='0000-00-00 00:00:00'){
			  $UFM=$fieldAnt[UltimaFechaModif];
			  $USUA=$fieldAnt[UltimoUsuario];
			}
			echo"
			 <input name='ult_usuario' type='text' id='ult_usuario' size='30' value='$USUA' readonly />
			 <input name='ult_fecha' type='text' id='ult_fecha' size='22' value='$UFM' readonly />
		   </td>";
   ?>
</tr>
</table>

</div><!--////////////////// *****  FIN PESTAÑA 1 ********* ///////////////////// -->
<center>
<input name="btguardar" type="submit" id="btguardar" value="Guardar Registro"/>
<input name="btcancelar" type="button" id="btcancelar" value="Cancelar" onclick="cargarPagina(this.form,'<?=$regresar?>.php?limit=0');"/>
<input type="hidden" name="filas" id="filas" value="<?=$rows?>" />
</center></div>
</form>
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
<!-- VALIDACIONES CAMPOS OBLIGATORIOS  -->
<SCRIPT LANGUAGE="JavaScript">
function Alarma(formulario){
    var cont = 0;
    var H_creada = document.getElementById('horaCreada').value; //alert('H_creada Alarma'+H_creada);
	var H_modif = document.getElementById('horaModif').value;  //alert('H_modif Alarma'+H_modif);
    var permitir = new Number(document.getElementById('permitir').value); //alert('Permitir= '+permitir);
	
   for (var i=0; formulario.elements.length; i++) {//SE RECORRE TODO LOS CAMPOS LOS MISMOS DEBEN CONTENER UNA CANTIDAD ASIGNADA
	if (formulario.elements[i].className == "montoA"){
	  //alert(formulario.elements[i].value);
	  if((formulario.elements[i].value!='0,00')&&(H_creada!=H_modif)&&(cont==0)){
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

function verificarEditar(formulario) {
    var contador = 0;
  for (var i=0; formulario.elements.length; i++) {//SE RECORRE TODO LOS CAMPOS LOS MISMOS DEBEN CONTENER UNA CANTIDAD ASIGNADA
	if (formulario.elements[i].className == "montoA"){
	  //alert(formulario.elements[i].value);
	  if((formulario.elements[i].value =='') || (formulario.elements[i].value =='0,00')){
	    alert('NO PUEDE DEJAR CAMPO(S) VACIO(s)...!');
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
  
          var checkOK ="0123456789";
	      var checkStr = formulario.anop.value;
	      var allValid = true; 
	      for (i = 0; i < checkStr.length; i++) {
	          ch = checkStr.charAt(i); 
	          for (j = 0; j < checkOK.length; j++)
	              if (ch == checkOK.charAt(j))
	              break;
	              if (j == checkOK.length) { 
	                 allValid = false; 
	              break; 
	              }
	      }
	      if (!allValid) { 
	         alert("Escriba sólo números en el campo \"Ejercicio P.\"."); 
	         formulario.anop.focus(); 
	         return (false); 
	       } 
		   //VALIDACION SECTOR
		   if (formulario.sector.value.length <2) {
	  		 alert("Debe completar el llenado del formulario.");
			 alert("Seleccione el Sector a utilizar.");
	   		 formulario.sector.focus();
	      return (false);
	      }
          var checkOK ="0123456789";
	      var checkStr = formulario.sector.value;
	      var allValid = true; 
	      for (i = 0; i < checkStr.length; i++) {
	          ch = checkStr.charAt(i); 
	          for (j = 0; j < checkOK.length; j++)
	              if (ch == checkOK.charAt(j))
	              break;
	              if (j == checkOK.length) { 
	                 allValid = false; 
	              break; 
	              }
	      }
	      if (!allValid) { 
	         alert("Seleccione el Sector a utilizar."); 
	         formulario.sector.focus(); 
	         return (false); 
	       } 
		   //VALIDACION PROGRAMA
		  if (formulario.programa.value.length <2) {
	  		 alert("Debe completar el llenado del formulario.");
			 alert("Seleccione el Sector a utilizar.");
	   		 formulario.programa.focus();
	      return (false);
	      }
          var checkOK ="0123456789" + " ";
	      var checkStr = formulario.programa.value;
	      var allValid = true; 
	      for (i = 0; i < checkStr.length; i++) {
	          ch = checkStr.charAt(i); 
	          for (j = 0; j < checkOK.length; j++)
	              if (ch == checkOK.charAt(j))
	              break;
	              if (j == checkOK.length) { 
	                 allValid = false; 
	              break; 
	              }
	      }
	      if (!allValid) { 
	         alert("Seleccione el Sector a utilizar."); 
	         formulario.programa.focus(); 
	         return (false); 
	       } 
		   //VALIDACION SUB-PROGRAMA
		   if (formulario.subprograma.value.length <1) {
		     alert("Debe completar el llenado del formulario.");
	  		 alert("Seleccione el Sub-Programa a utilizar.");
	   		 formulario.subprograma.focus();
	      return (false);
	      }
          var checkOK ="0123456789";
	      var checkStr = formulario.subprograma.value;
	      var allValid = true; 
	      for (i = 0; i < checkStr.length; i++) {
	          ch = checkStr.charAt(i); 
	          for (j = 0; j < checkOK.length; j++)
	              if (ch == checkOK.charAt(j))
	              break;
	              if (j == checkOK.length) { 
	                 allValid = false; 
	              break; 
	              }
	      }
	      if (!allValid) { 
	         alert("Seleccione el Sub-Programa a utilizar."); 
	         formulario.subprograma.focus(); 
	         return (false); 
	       } 

		   //VALIDACION UNIDAD EJECUTORA
		  /* if (formulario.unidadejecutora.value.length <1) {
	  		 alert("Seleccione la Unidad Ejecutora a utilizar.");
	   		 formulario.unidadejecutora.focus();
	      return (false);
	      }
          var checkOK ="0123456789";
	      var checkStr = formulario.unidadejecutora.value;
	      var allValid = true; 
	      for (i = 0; i < checkStr.length; i++) {
	          ch = checkStr.charAt(i); 
	          for (j = 0; j < checkOK.length; j++)
	              if (ch == checkOK.charAt(j))
	              break;
	              if (j == checkOK.length) { 
	                 allValid = false; 
	              break; 
	              }
	      }
	      if (!allValid) { 
	         alert("Seleccione la Unidad Ejecutora a utilizar."); 
	         formulario.unidadejecutora.focus(); 
	         return (false); 
	       }*/ 
		   //VALIDACION FECHA INICIO
		   if (formulario.fdesde.value.length <10) {
	  		 alert("Escriba los datos correctos en el campo \"F. Inicio\".");
	   		 formulario.fdesde.focus();
	      return (false);
	      }
          var checkOK ="0123456789" + "-";
	      var checkStr = formulario.fdesde.value;
	      var allValid = true; 
	      for (i = 0; i < checkStr.length; i++){
	          ch = checkStr.charAt(i); 
	          for (j = 0; j < checkOK.length; j++)
	              if (ch == checkOK.charAt(j))
	              break;
	              if (j == checkOK.length) { 
	                 allValid = false; 
	              break; 
	              }
	      }
	      if (!allValid) { 
	         alert("Escriba sólo números en el campo \"F. Inicio\"."); 
	         formulario.fdesde..focus(); 
	         return (false); 
	       } 
		   //VALIDACION FECHA INICIO
		  if (formulario.fhasta.value.length <10) {
	  		 alert("Escriba los datos correctos en el campo \"F. Termino\".");
	   		 formulario.fhasta.focus();
	      return (false);
	      }
          var checkOK ="0123456789" + "-";
	      var checkStr = formulario.fhasta.value;
	      var allValid = true; 
	      for (i = 0; i < checkStr.length; i++){
	          ch = checkStr.charAt(i); 
	          for (j = 0; j < checkOK.length; j++)
	              if (ch == checkOK.charAt(j))
	              break;
	              if (j == checkOK.length) { 
	                 allValid = false; 
	              break; 
	              }
	      }
	      if (!allValid) { 
	         alert("Escriba sólo números en el campo \"F. Termino\"."); 
	         formulario.fhasta.focus(); 
	         return (false); 
	       } 
		   //VALIDACION MONTO AUTORIZADO
		   if (formulario.montoautori.value.length <1) {
	  		 alert("Escriba los datos correctos en el campo \"Monto Autorizado\".");
	   		 formulario.montoautori.focus();
	      return (false);
	      }
          var checkOK ="0123456789" + ",.";
	      var checkStr = formulario.montoautori.value;
	      var allValid = true; 
	      for (i = 0; i < checkStr.length; i++){
	          ch = checkStr.charAt(i); 
	          for (j = 0; j < checkOK.length; j++)
	              if (ch == checkOK.charAt(j))
	              break;
	              if (j == checkOK.length) { 
	                 allValid = false; 
	              break; 
	              }
	      }
	      if (!allValid) { 
	         alert("Escriba sólo números en el campo \"Monto Autorizado\"."); 
	         formulario.montoautori.focus(); 
	         return (false); 
	       } 
		   //VALIDACION APROBADO POR
		   if (formulario.nomempleado.value.length <2) {
	  		 alert("Elija por quien sera aprobado haciendo click en el botón");
	   		 formulario.nomempleado.focus();
	      return (false);
	      }
          var checkOK = "ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚ" + "abcdefghijklmnñopqrstuvwxyzáéíóú" + " .,_/";
	      var checkStr = formulario.nomempleado.value;
	      var allValid = true; 
	      for (i = 0; i < checkStr.length; i++) {
	          ch = checkStr.charAt(i); 
	          for (j = 0; j < checkOK.length; j++)
	              if (ch == checkOK.charAt(j))
	              break;
	              if (j == checkOK.length) { 
	                 allValid = false; 
	              break; 
	              }
	      }
	      if (!allValid) { 
	         alert("Elija por quien sera aprobado haciendo click en el botón"); 
	         formulario.nomempleado.focus(); 
	         return (false); 
	       } 
	return (true); 
	} 
</SCRIPT>>