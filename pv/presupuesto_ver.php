<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location:index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript02.js"></script>
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<style type="text/css">
<!--
UNKNOWN {
        FONT-SIZE: small
}
#header {
        FONT-SIZE: 93%; BACKGROUND: url(imagenes/bg.gif) #dae0d2 repeat-x 50% bottom; FLOAT: left; WIDTH: 100%; LINE-HEIGHT: normal
}
#header UL {
        PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 10px; LIST-STYLE-TYPE: none
}
#header LI {
        PADDING-RIGHT: 0px; PADDING-LEFT: 9px; BACKGROUND: url(imagenes/left.gif) no-repeat left top; FLOAT: left; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 0px
}
#header A {
        PADDING-RIGHT: 15px; DISPLAY: block; PADDING-LEFT: 6px; FONT-WEIGHT: bold; BACKGROUND: url(imagenes/right.gif) no-repeat right top; FLOAT: left; PADDING-BOTTOM: 4px; COLOR: #765; PADDING-TOP: 5px; TEXT-DECORATION: none
}
#header A {
        FLOAT: none
}
#header A:hover{
        COLOR: #333
}
#header #current {
        BACKGROUND-IMAGE: url(imagenes/left_on.gif)
}
#header #current A {
        BACKGROUND-IMAGE: url(imagenes/right_on.gif); PADDING-BOTTOM: 5px; COLOR: #333
}
-->
</style>
</head>
<body>
<? 
   include "fphp.php";
   connect();
 	$sql="SELECT * FROM pv_partida WHERE (cod_partida='".$_POST['registro']."')";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	if($rows!=0) $field=mysql_fetch_array($query);
	if($field['Estado']=="A") $activo="checked"; else $inactivo="checked";
?>
<table width="100%" height="19" cellpadding="0" cellspacing="0">
<tr>
  <td class="titulo">Anteproyecto | Ver</td>
  <td align="right"><a class="cerrar"; href="../presupuesto/framemain.php">[Cerrar]</a></td>
</tr>
</table><hr width="100%" color="#333333" />
<form id="frmentrada" name="frmentrada" action="presupuesto_detalle.php?accion=GuardarMontoPartidas" method="POST" onsubmit="return presupuestoDetalle(this, CargarPartida);">
<? include "gmsector.php"; ?>
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
<!--////////////////// ***** ****** ****** **** **************** PESTAA N 2  ************ ******* ******** ********** ********* //////////////////// -->
<div id="tab2" style="display:none;">
<div style="width:800px; height:15px" class="divFormCaption">Detalle de Presupuesto</div>
<!--////////////////// **************** MOSTRAR LA TABLA DE PARTIDAS  ************ //////////////////// -->
<table width="800" class="tblLista" border="0">
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
///////////////////************  MOSTRAR PARTIDAS ASOCIADAS AL ANTEPROYECTO *************/////////////////////
//------------------------------------------------------------------------------------------------------------
$total=0;$monto=0;
$sql="SELECT * FROM pv_presupuesto WHERE CodPresupuesto='".$_GET['registro']."' ";
$qry=mysql_query($sql) or die ($sql.mysql_error());
if(mysql_num_rows($qry)!=0){
  $field=mysql_fetch_array($qry);
  $sqlDet="SELECT * FROM pv_antepresupuestodet 
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
			   $cont1=0;$montoP=0;
			   $sqldet="SELECT * FROM pv_antepresupuestodet 
			                    WHERE partida='".$fieldP['partida1']."' AND
			                          CodPresupuesto='".$fielDet['CodPresupuesto']."'";
			   $qrydet=mysql_query($sqldet) or die ($sqldet.mysql_error());
			   $rwdet=mysql_num_rows($qrydet);
			   for($a=0; $a<$rwdet; $a++){
			     $fdet=mysql_fetch_array($qrydet);
			     $cont1= $cont1 + 1;
				 $montoP = $montoP + $fdet['MontoPresupuestado'];
			   }
			   $montoP=number_format($montoP,2,',','.');
		       $codigo_partida = $fieldP[cod_partida];
		       $pCapturada = $fieldP[partida1];
		       echo "<tr class='trListaBody6'>
			     <td align='center'>".$fieldP['cod_partida']."</td>
		         <td>".$fieldP['denominacion']."</td>
		         <td align='center'>".$fieldP['Estado']."</td>
		         <td align='center'>".$fieldP['tipo']."</td>
		         <td align='right'><input type='text' size='12' maxlength='12' class='inputP' id='partida' name='partida' value='$montoP' readonly/>Bs.F</td>         
	           </tr>";
		    }
		 }
		 //////////////////////////////////////////////////////////////////
		 //// ******** Obtengo Partidas Tipo "T" 301-01-00-00 ******** ////
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
			 $cont2=0;$montoG=0;
			 $sqldet="SELECT * FROM pv_antepresupuestodet 
			                  WHERE partida='".$fieldP['partida1']."' AND
							        generica='".$fieldP['generica']."' AND
									CodPresupuesto='".$fielDet['CodPresupuesto']."' AND
									tipocuenta='".$fieldP['cod_tipocuenta']."'";
			 $qrydet=mysql_query($sqldet) or die ($sqldet.mysql_error());
			 $rwdet=mysql_num_rows($qrydet);
			 for($b=0; $b<$rwdet; $b++){
			   $fdet=mysql_fetch_array($qrydet);
			   $cont2= $cont2 + 1;
			   $montoG = $montoG + $fdet['MontoPresupuestado'];
			 }
			 $montoG=number_format($montoG,2,',','.');
			 $codigo_generica = $fieldP[cod_partida];
			 $gCapturada = $fieldP[generica];
			 $pCapturada2 = $fieldP[partida1];
			 echo "<tr class='trListaBody5'>
			     <td align='center'>".$fieldP['cod_partida']."</td>
		         <td>".$fieldP['denominacion']."</td>
		         <td align='center'>".$fieldP['Estado']."</td>
		         <td align='center'>".$fieldP['tipo']."</td>
		         <td align='right'><input type='text' size='12' maxlength='12' class='inputG' id='generica' name='generica' value='$montoG' readonly/>Bs.F</td>         
	             </tr>";
		   }
	     }
		 //////////////////////////////////////////////////////////////////////////////////////
		 //// **** Obtengo Partidas Tipo "D" 301-01-01-01	**** ////
		 if($fielDet['partida']!=00){
		  $s="SELECT cod_partida,denominacion,tipo FROM pv_partida WHERE cod_partida='".$fielDet['cod_partida']."'";
	      $q=mysql_query($s) or die ($s.mysql_error());
	      $f=mysql_fetch_array($q);
		  $contTotal = $cont;
		  $monto = $fielDet['MontoPresupuestado'];
		  $total = $monto + $total;
		  $montoTotal = number_format($monto,2,',','.');
		  $totalSumado = number_format($total,2,',','.');
		  $codigo_detalle = $fielDet['cod_partida'];
		  echo "<tr class='trListaBody'>
				<td align='center'>".$fielDet['cod_partida']."</td>
				<td>".$f['denominacion']."</td>
				<td align='center'>".$fielDet['Estado']."</td>
				<td align='center'>".$f['tipo']."</td>
		        <td align='right'><input type='text' size='12' class='montoA' maxlength='12' id='$codigo_partida|$codigo_generica' name='".$fielDet['IdAnteProyectoDet']."' value='$montoTotal' onchange='sumarPartidaVer3(this.value, this.id)'; onfocus='obtener(this.value);' readonly/>Bs.F</td>         
	     </tr>";
	 }
}}echo"<tr><td colspan='3'></td>
               <td align='right'><b>Total:</b></td>
			   <td align='center' class='trListaBody'><b><input type='hidden' id='total' name='total' size='15' value='$total'/>";
			                                           
			                                     echo"<input type='text' class='inputT' id='totalAnt' name='totalAnt' size='13' value='$totalSumado' readonly/>Bs.F</b></td>
		   </tr>";
//	------------------------------------------------------------------------------------------------------------
//	------------------------------------------------------------------------------------------------------------
$rows=(int)$rows;
echo "
	<script type='text/javascript' language='javascript'>
		totalLista($registros);
		totalLotes($registros, $rows, ".$limit.");
	</script>";
?>
</table>
<script type="text/javascript" language="javascript">
	totalPuestos(<?=$rows?>);
</script>
</div>
<!--////////////////// ***** ****** ****** **** **************** PESTAÑA N 1  ************  ******** ********** ********* //////////////////// -->
<!--////////////////// ***** ****** ****** **** ****************              ************  ******** ********** ********* //////////////////// -->
<div name="tab1" id="tab1" style="display:block;">
<?php
$limit=(int) $limit;
echo "
<input type='hidden' name='codantepres' id='codantepres' value='".$codantepres."'/>
<input type='hidden' name='filtro' id='filtro' value='".$filtro."' />
<input type='hidden' name='regresar' id='regresar' value='".$regresar."' />
<input type='hidden' name='forganismo' id='forganismo' value='".$forganismo."' />
<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />";
////////////////////////////////////////////////////////////////////////////////////////////
include "gmsector.php";
//echo"<br>Post:".$_POST["registro"];
$sql="SELECT * FROM pv_presupuesto WHERE CodPresupuesto='".$_GET['registro']."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if($rows!=0){
 $field=mysql_fetch_array($query);
 list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaAnteproyecto']); $fAntp=$d.'-'.$m.'-'.$a;
  list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaInicio']); $fInicio=$d.'-'.$m.'-'.$a;
 list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaFin']); $fFin=$d.'-'.$m.'-'.$a;
 $limit=(int) $limit;
}
?>
<!--////////////////////////// **********  DATOS GENERALES DEL PRESUPUESTO  *************  ///////////////////////-->   
<div style="width:800px" class="divFormCaption">Informaci&oacute;n de Anteproyecto</div>
<table width="800" class="tblForm">
	<tr>
		<td width="87"></td>
		<td width="151" class="tagForm">Organismo:</td>
		<? $sql2=mysql_query("SELECT * FROM mastorganismos WHERE CodOrganismo='".$field['Organismo']."'");
		   if(mysql_num_rows($sql2)!=0){$field2=mysql_fetch_array($sql2);}?>
		<td width="342"><input name="organismo" id="organismo" value="<?=$field2['Organismo']?>" maxlength="80" size="60" readonly/></td>
		<td colspan="2"></td>
	</tr>	
	<tr>
	<td></td>		
		<td class="tagForm">A&ntilde;o P.:</td>
		<td><? $ano = date(Y); // devuelve el ao $fcreacion= date("d-m-Y");//Fecha de Creacin ?>
			<input name="anop" type="text" id="anop" size="2" value="<?=$field[EjercicioPpto]?>" readonly /> 
			F.Creaci&oacute;n:<input name="fcreacion" type="text" id="fcreacion" size="9" value="<?=$fAntp?>" readonly/> 
			<? if($field[Estado]==PE){$estado=Preparado;}if($field[Estado]==AP){$estado=Aprobado;}if($field[Estado]==AN){$estado=Anulado;}
			   if($field[Estado]==GE){$estado=Generado;if($field[Estado]==RE){$estado=Revisado;}}?>
			 Estado:<input name="estado" type="text" id="estado" size="11" value="<?=$estado?>" readonly/></td>
	   <td></td>
	</tr>
	</table>
	<!---////////////////////////////////////////////////////////////////////////////////////////////////-->
	<!---/////////////////// ********   CARGAR SELECT SECTOR PRESUPUESTO  *******   /////////////////////-->
	<table width="800" class="tblForm">
	<tr>
	  <td width="83"></td>
	  <td width="181" class="tagForm">Sector:</td><? $sql="SELECT * FROM pv_sector WHERE cod_sector='".$field['Sector']."'";
	                                                 $qry=mysql_query($sql) or die ($sql.mysql_error());
													 if(mysql_num_rows($qry)!=0){$fieldSector=mysql_fetch_array($qry);}
												  ?>
	  <td width="520"><input name="sector" id="sector" value="<?=$fieldSector['descripcion']?>" size="50" readonly/></td>
	</tr>
	<tr>
	  <td width="83"></td>
	  <td class="tagForm">Programa:</td><? $sql="SELECT * FROM pv_programa1 WHERE id_programa='".$field['Programa']."'";
	                                                 $qry=mysql_query($sql) or die ($sql.mysql_error());
													 if(mysql_num_rows($qry)!=0){$fieldPrograma=mysql_fetch_array($qry);}
												  ?>
	  <td><input name="programa" id="programa" value="<?=$fieldPrograma[descp_programa]?>" size="50" readonly/></td>
	</tr>
	<tr>
	  <td width="83"></td>
	  <td class="tagForm">Sub-Programa:</td><? $sql="SELECT * FROM pv_subprog1 WHERE id_sub='".$field['SubPrograma']."'";
	                                                 $qry=mysql_query($sql) or die ($sql.mysql_error());
													 if(mysql_num_rows($qry)!=0){$fieldSubprog=mysql_fetch_array($qry);}
												  ?>
	  <td><input name="subprograma" id="subprograma" value="<?=$fieldSubprog[descp_subprog]?>" size="50" readonly/></td>
	</tr>
	<tr>
	  <td width="83"></td>
	  <td class="tagForm">Proyecto:</td><? $sql="SELECT * FROM pv_proyecto1 WHERE id_proyecto='".$field['Proyecto']."'";
	                                                 $qry=mysql_query($sql) or die ($sql.mysql_error());
													 if(mysql_num_rows($qry)!=0){$fieldProyecto=mysql_fetch_array($qry);}
												  ?>
	  <td><input name="proyecto" id="proyecto" value="<?=$fieldProyecto[descp_proyecto]?>" size="50" readonly/></td>
	</tr>
	<tr>
	  <td width="83"></td>
	  <td class="tagForm">Actividad:</td><? $sql="SELECT * FROM pv_actividad1 WHERE id_actividad='".$field['Actividad']."'";
	                                                 $qry=mysql_query($sql) or die ($sql.mysql_error());
													 if(mysql_num_rows($qry)!=0){$fieldActividad=mysql_fetch_array($qry);}
												  ?>
	  <td><input name="actividad" id="actividad" value="<?=$fieldActividad[descp_actividad]?>" size="50" readonly/></td>
	</tr>
	<tr><td></td></tr>
	</table>
	<div style="width:800px" class="divFormCaption">Duraci&oacute;n de Presupuesto</div>
	<table width="800" class="tblForm"> 
	<tr><td></td></tr>
	<tr><td></td>
		<td class="tagForm">F. Inicio:</td>
	    <td colspan="2"><input  name="finicio" type="text" id="finicio" size="10" maxlength="10" value="<?=$fInicio?>" readonly/>*(dd-mm-yyyy)</td>
	</tr>	
	<tr><td></td>
		<td class="tagForm">F. Termino:</td>
	    <td colspan="2"><input name="ftermino" type="text" id="ftermino" size="10" maxlength="10" value="<?=$fFin?>" readonly/>*(dd-mm-yyyy)</td>
	</tr>	
	<tr><td></td>
		<td class="tagForm">Duraci&oacute;n:</td><? $date1=strtotime($field['FechaInicio']);
													$date2=strtotime($field['FechaFin']);
													$s = ($date1)-($date2);
													$d = intval($s/86400);
													$s -= $d*86400;
													$h = intval($s/3600);
													$s -= $h*3600;
													$m = intval($s/60);
													$s -= $m*60;
													
													$dif= (($d*24)+$h).hrs." ".$m."min";
													$dif2= abs($d.$space);?>
	    <td><input name="dias" type="text" id="dias" size="6" maxlength="3" value="<?=$dif2?>" readonly/> d&iacute;as.</td>
		<td colspan="1"></td>
	</tr>
	<tr><td></td></tr>
	</table>
	<!---  TABLA 2 ------>
	<div style="width:800px" class="divFormCaption">Monto de Presupuesto</div>
    <table width="800" class="tblForm">
	<tr><td width="15"></td>
	</tr>
	<tr><td></td>
	  <td width="248" class="tagForm">Monto Total Anteproyecto:</td><? $totalAnt=number_format($total,2,',','.')?>
	  <td width="521" ><input name="totalAnteproyecto" type="text" id="totalAnteproyecto" size="20" maxlength="15" value="<?=$totalAnt?>" readonly/>Bs.F</td>
	</tr>
	<tr><td></td>
	  <td class="tagForm">Monto Autorizado:</td><? $sql="SELECT MontoAprobado FROM pv_presupuesto WHERE CodPresupuesto='".$_GET['registro']."' "; 
	                                             $qry=mysql_query($sql) or die ($sql.mysql_error());
												 if(mysql_num_rows($qry)!=0){
												   $fieldAp=mysql_fetch_array($qry);
												 }
												 ?>
	  <td ><input name="montoautori" type="text" id="montoautori" size="20" maxlength="15" value="<?=$fieldAp['MontoAprobado']?>"  readonly/>Bs.F</td>
	</tr>
	<tr><td></td>
	  <td class="tagForm">Diferencia:</td>
	  <td><input name="diferencia" id="diferencia" type="text" size="20" maxlength="15" readonly/>Bs.F</td>
	</tr>
	<tr><td></td></tr>
	<tr><td></td>
	   <td class="tagForm">Preparado por:</td>
	   <td><input name="prepor" id="prepor" type="text" size="60" value="<?=$field[PreparadoPor]?>" readonly/></td>
	<tr>
	<tr><td></td>
	   <td class="tagForm">Aprobado por:</td>
	   <td><input name="apropor" id="apropor" type="text" size="60" value="<?=$field[AprobadoPor]?>" readonly/></td>
	</tr>
	<tr><td></td>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td colspan="1">
			<input name="ult_usuario" type="text" id="ult_usuario" size="30" value="<?=$field['UltimoUsuario']?>" readonly />
			<input name="ult_fecha" type="text" id="ult_fecha" size="22" value="<?=$field['UltimaFecha']?>" readonly />		</td>
	</tr>
</table>
</div>
<center>
<input type="hidden" name="filas" id="filas" value="<?=$rows?>" />
</center></div>
</form>
</body>
</html>

<table><tr><td valign="middle"><iframe style="border:none" align="left"</td></tr>
