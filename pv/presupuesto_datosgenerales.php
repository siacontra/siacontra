<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location:index.php");
//	------------------------------------
include("fphp.php");
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
<script type="text/javascript" language="javascript" src="fscript03.js"></script>
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
		       document.getElementById("dias").value= Dias;
		 }
</script>
<style type="text/css">
<!--
UNKNOWN { FONT-SIZE: small}
#header {FONT-SIZE: 93%; BACKGROUND: url(imagenes/bg.gif) #dae0d2 repeat-x 50% bottom; FLOAT: left; WIDTH: 100%; LINE-HEIGHT: normal}
#header UL {PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 10px; LIST-STYLE-TYPE: none}
#header LI {PADDING-RIGHT: 0px; PADDING-LEFT: 9px; BACKGROUND: url(imagenes/left.gif) no-repeat left top; FLOAT: left; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 0px}
#header A {PADDING-RIGHT: 15px; DISPLAY: block; PADDING-LEFT: 6px; FONT-WEIGHT: bold; BACKGROUND: url(imagenes/right.gif) no-repeat right top; FLOAT: left; PADDING-BOTTOM: 4px; COLOR: #765; PADDING-TOP: 5px; TEXT-DECORATION: none}
#header A { FLOAT: none}
#header A:hover {  COLOR: #333}
#header #current { BACKGROUND-IMAGE: url(imagenes/left_on.gif)}
#header #current A { BACKGROUND-IMAGE: url(imagenes/right_on.gif); PADDING-BOTTOM: 5px; COLOR: #333}
-->
</style>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
<tr>
 <td class="titulo">Presupuesto</td>
 <td align="right"><a class="cerrar"; href="" onclick="window.close()">[Cerrar]</a></td>
</tr>
</table><hr width="100%" color="#333333" />
<?php
$annio_actual=date("Y");
$mes_actual=date("m");
$dia_actual=date("d");
$hora_actual=date("H");
$min_actual=date("i");
$periodo=$annio_actual."-".$mes_actual;
$fecha=$dia_actual."-".$mes_actual."-".$annio_actual;
$fecha2=$dia_actual."-".$mes_actual."-".$annio_actual;

if ($hora_actual<12) $meridiano="AM";
else {
	$meridiano="PM";
	$hora_actual=(int) $hora_actual;
	$hora_actual-=12;
	if ($hora_actual==0) $hora_actual=12;
	if ($hora_actual<10) $hora_actual="0$hora_actual";
}
$hora=$hora_actual.":".$min_actual;
?>
<form id="frmentrada" name="frmentrada" >
<?php
$limit=(int) $limit;
echo "
<input type='hidden' name='codantepres' id='codantepres' value='".$codantepres."'/>
<input type='hidden' name='filtro' id='filtro' value='".$filtro."' />
<input type='hidden' name='regresar' id='regresar' value='".$regresar."' />
<input type='hidden' name='forganismo' id='forganismo' value='".$forganismo."' />
<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />";
?>

<?php
include "gmsector.php";
$sql="SELECT * FROM pv_sector,pv_programa1,pv_subprog1,pv_actividad1,pv_proyecto1 WHERE 1";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0){
 $field=mysql_fetch_array($query);
 list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaDesde']); $fdesde=$d.'/'.$m.'/'.$a;
 list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaHasta']); $fhasta=$d.'/'.$m.'/'.$a;
 $limit=(int) $limit;
}
//-----------------
list($cod_presupuesto, $cod_organismo, $ejercicioPpto)=SPLIT('[-]', $_GET['registro']);
//echo "CodPresupuesto=".$cod_presupuesto;

//-----------------
?>
<table width="1000" align="center">
<tr><td>
<div id="header">
<ul>
<!-- CSS Tabs PESTAÑAS OPCIONES DE PRESUPUESTO -->
<li>
<a onClick="document.getElementById('tab1').style.display='block'; 
            document.getElementById('tab2').style.display='none';
			document.getElementById('tab3').style.display='none';
			document.getElementById('tab4').style.display='none';" href="#">Datos Generales</a></li>
<li>
<a onClick="document.getElementById('tab1').style.display='none'; 
            document.getElementById('tab2').style.display='block';
			document.getElementById('tab3').style.display='none';
			document.getElementById('tab4').style.display='none';" href="#">Detalle de Presupuesto</a></li>
<li>
<a onClick="document.getElementById('tab1').style.display='none'; 
            document.getElementById('tab2').style.display='none';
			document.getElementById('tab3').style.display='block';
			document.getElementById('tab4').style.display='none';" href="#">Ajustes Positivos</a></li> 
<li>
<a onClick="document.getElementById('tab1').style.display='none'; 
            document.getElementById('tab2').style.display='none';
			document.getElementById('tab3').style.display='none';
			document.getElementById('tab4').style.display='block';" href="#">Ajustes Negativos</a></li> 
</ul>
</div>
  </td>
</tr>
</table>
<!--////////////////////////// **********DATOS GENERALES DEL PRESUPUESTO*************  ///////////////////////--> 
<!---/////////////////////////////////////////////////////////////////////////////////////////////////////////-->  
<div id="tab1" style="display:block;">
<div style="width:850px" class="divFormCaption">Informaci&oacute;n de Presupuesto</div>
<? //// *** CONSULTA TABLA PRESUPUESTO PARA MOSTRAR DATOS
$sqlP="SELECT * FROM pv_presupuesto WHERE CodPresupuesto='$cod_presupuesto'"; //echo $sqlP;
$qryP=mysql_query($sqlP) or die ($sqlP.mysql_error());
$fieldP=mysql_fetch_array($qryP); 
?>
<table width="850" class="tblForm">
<tr>
 <td width="48"></td>
 <td width="90" class="tagForm">Organismo:</td>
 <? 
   $sql="SELECT * FROM mastorganismos";
   $rs=mysql_query($sql) or die ($sql.mysql_error());
   $field=mysql_fetch_array($rs);
 ?>
 <td width="300"><input type="text" size="59" name="organismo" id="organismo" value="<?=$field['Organismo'];?>" readonly/></td>
	<!--<td width="342">
		<select name="organismo" id="organismo" class="selectBig">
		<?php 
		// segundo bloque php //* Conectamos a los datos *//
		include "conexion.php";
		$sql="SELECT CodOrganismo,Organismo FROM mastorganismos WHERE 1";
		$rs=mysql_query($sql);
		while($reg=mysql_fetch_assoc($rs)){
		$codOrganismo=$reg['CodOrganismo'];// Codigo del orgnismo
		$organismo=$reg['Organismo'];// Descripcion del Organismo
		   echo "<option value=$codOrganismo>$organismo</option>";
		}
		?></select>-->		</td>
</tr>
<tr><td height="4"></td></tr>
</table>
<table width="850" class="tblForm">
<tr><td height="2"></td></tr>
<tr>
    <? if($fieldP['FechaGaceta']!='0000-00-00'){list($a, $m, $d)=SPLIT( '[/.-]', $fieldP['FechaGaceta']); $fgaceta=$d.'-'.$m.'-'.$a;}
       if($fieldP['FechaDecreto']!='0000-00-00'){list($a, $m, $d)=SPLIT( '[/.-]', $fieldP['FechaDecreto']); $fdecreto=$d.'-'.$m.'-'.$a;}
    ?>
    <td width="190"></td>
	<td width="75" align="right">Nro. Gaceta:</td>
	<td width="70"><input name="gaceta" id="gaceta" type="text" size="8" value="<?=$fieldP['NumeroGaceta']?>" readonly/></td>
	<td width="65" align="right">F. Gaceta:</td>
	<td width="140"><input name="fgaceta" id="fgaceta" type="text" size="8" value="<?=$fgaceta?>" readonly/></td>
	<td colspan="2" width="200"></td>
</tr>
<tr>
    <td width="190"></td>
	<td width="75" align="right">Nro. Decreto:</td>
	<td width="70"><input name="decreto" id="decreto" type="text" size="8" value="<?=$fieldP['NumeroDecreto']?>" readonly/></td>
	<td width="65" align="right">F. Decreto:</td>
	<td width="140"><input name="fdecreto" id="fdecreto" type="text" size="8" value="<?=$fdecreto?>" readonly/></td>
	<td colspan="2" width="200"></td>
</tr>
</table>
 <table width="850" class="tblForm">
	<tr>
	<td width="85"></td>
	    <td width="166" class="tagForm">Nro. Presupuesto:<input name="npresupuesto" id="npresupuesto" size="3" value="<?=$fieldP['CodPresupuesto']?>" readonly/></td>	
		<td width="110" >Ejercicio P.:<input name="ejercicioPpto" type="text" id="ejercicioPpto" size="3" maxlength="4" value="<?=$fieldP['EjercicioPpto']?>" readonly/></td>
		<td width="442">
		     <? 
		        list($a, $m, $d)=SPLIT( '[/.-]', $fieldP['FechaPresupuesto']); $fpresupuesto=$d.'-'.$m.'-'.$a;
		        list($a, $m, $d)=SPLIT( '[/.-]', $fieldP['FechaInicio']); $fInicio=$d.'-'.$m.'-'.$a;
		        list($a, $m, $d)=SPLIT( '[/.-]', $fieldP['FechaFin']); $fFin=$d.'-'.$m.'-'.$a;
		        if($fieldP['Estado']==AP){$estado=Aprobado;}
             ?>
			F.Creaci&oacute;n:
			<input name="fcreacion" type="text" id="fcreacion" size="8" value="<?=$fpresupuesto?>" readonly /> 
	    Estado:<input name="estado" type="text" id="estado" size="11" value="<?=$estado?>" readonly/>		</td>
	</tr>
	</table>
	<!---////////////////////////////////////////////////////////////////////////////////////////////////-->
	<!---/////////////////// ********   CARGAR SELECT SECTOR PRESUPUESTO  *******   /////////////////////-->
	<table width="850" class="tblForm">
	<tr>
	  <td width="60"></td>
	  <td width="181" class="tagForm">Sector:</td><? $sql="SELECT * FROM pv_sector WHERE cod_sector='".$fieldP['Sector']."'";
	                                                 $qry=mysql_query($sql) or die ($sql.mysql_error());
													 if(mysql_num_rows($qry)!=0){$fieldSector=mysql_fetch_array($qry);}
												  ?>
	  <td width="520"><input name="sector" id="sector" value="<?=$fieldSector['descripcion']?>" size="70" readonly/></td>
	</tr>
	<tr>
	  <td width="60"></td>
	  <td class="tagForm">Programa:</td><? $sql="SELECT * FROM pv_programa1 WHERE id_programa='".$fieldP['Programa']."'";
	                                                 $qry=mysql_query($sql) or die ($sql.mysql_error());
													 if(mysql_num_rows($qry)!=0){$fieldPrograma=mysql_fetch_array($qry);}
												  ?>
	  <td><input name="programa" id="programa" value="<?=$fieldPrograma[descp_programa]?>" size="70" readonly/></td>
	</tr>
	<tr>
	  <td width="60"></td>
	  <td class="tagForm">Actividad:</td><? $sql="SELECT * FROM pv_subprog1 WHERE id_sub='".$fieldP['SubPrograma']."'";
	                                                 $qry=mysql_query($sql) or die ($sql.mysql_error());
													 if(mysql_num_rows($qry)!=0){$fieldSubprog=mysql_fetch_array($qry);}
												  ?>
	  <td><input name="subprograma" id="subprograma" value="<?=$fieldSubprog[descp_subprog]?>" size="70" readonly/></td>
	</tr>
	<tr><td width="60"></td>
	  <td class="tagForm">Unidad Ejecutora:</td>
	  <td><input type="text" name="unidadejecutora" id="unidadejecutora" size="70" value="<?=$fieldP['UnidadEjecutora']?>" readonly /></td>
	</tr>
	<tr>
	  <td width="60"></td>
	  <td class="tagForm">Proyecto:</td><? $sql="SELECT * FROM pv_proyecto1 WHERE id_proyecto='".$fieldP['Proyecto']."'";
	                                                 $qry=mysql_query($sql) or die ($sql.mysql_error());
													 if(mysql_num_rows($qry)!=0){$fieldProyecto=mysql_fetch_array($qry);}
												  ?>
	  <td><input name="proyecto" id="proyecto" value="<?=$fieldProyecto[descp_proyecto]?>" size="70" readonly/></td>
	</tr>
	<tr>
	  <td width="60"></td>
	  <td class="tagForm">Sub-Programa:</td><? $sql="SELECT * FROM pv_actividad1 WHERE id_actividad='".$fieldP['Actividad']."'";
	                                                 $qry=mysql_query($sql) or die ($sql.mysql_error());
													 if(mysql_num_rows($qry)!=0){$fieldActividad=mysql_fetch_array($qry);}
												  ?>
	  <td><input name="actividad" id="actividad" value="<?=$fieldActividad['descp_actividad']?>" size="70" readonly/></td>
	</tr>
	</table>
	
	
	<!---////////////////////////////////////////////////////////////////////////////////////////////////-->
	<!---/////////////////// ********   CARGAR SELECT SECTOR PRESUPUESTO  *******   /////////////////////-->
	<!---<table width="800" class="tblForm">
	<tr>
	  <td width="83"></td>
	  <td width="181" class="tagForm">Sector:</td>
	  <td width="520"><select name="sector" id="sector" class="selectMed" onchange="getOptions_5(this.id, 'programa', 'subprograma', 'proyecto', 'actividad');">
        <option value=""></option>
        <?php getSector('', 0); ?>
      </select>*		</td>
	</tr>
	<tr>
	  <td width="83"></td>
	  <td class="tagForm">Programa:</td>
	  <td><select name="programa" id="programa" class="selectMed" disabled>
        <option value=""></option>
      </select>*</td>
	</tr>
	<tr>
	  <td width="83"></td>
	  <td class="tagForm">Actividad:</td>
	  <td>
			<select name="subprograma" id="subprograma" class="selectMed" disabled>
				<option value=""></option>
			</select>*	  </td>
	</tr>
	<tr>
	  <td width="83"></td>
	  <td class="tagForm">Proyecto:</td>
	  <td>
			<select name="proyecto" id="proyecto" class="selectMed" disabled>
				<option value=""></option>
			</select>*	  </td>
	</tr>
	<tr>
	  <td width="83"></td>
	  <td class="tagForm">Sub-Programa:</td>
	  <td>
			<select name="actividad" id="actividad" class="selectMed" disabled>
				<option value=""></option>
			</select>*	  </td>
	</tr>
	<tr><td></td></tr>
	</table>-->
	<div style="width:850px" class="divFormCaption">Duraci&oacute;n de Presupuesto</div>
	<table width="850" class="tblForm"> 
	<tr><td></td></tr>
	<tr><td width="40"></td>
		<td class="tagForm">F. Inicio:</td>
	    <td colspan="2"><input  name="finicio" type="text" id="finicio" size="10" maxlength="10" value="<?=$fInicio?>" readonly/>*(dd-mm-aaaa)</td>
	</tr>	
	<tr><td></td>
		<td class="tagForm">F. Termino:</td>
	    <td colspan="2"><input name="ftermino" type="text" id="ftermino" size="10" maxlength="10" value="<?=$fFin?>" readonly/>*(dd-mm-aaaa)</td>
	</tr>	
	<tr><td></td>
		<td class="tagForm">Duraci&oacute;n:</td><? $date1=strtotime($fieldP['FechaInicio']);
													$date2=strtotime($fieldP['FechaFin']);
													$s = ($date1)-($date2);
													$d = intval($s/86400);
													$s -= $d*86400;
													$h = intval($s/3600);
													$s -= $h*3600;
													$m = intval($s/60);
													$s -= $m*60;
													
													$dif= (($d*24)+$h).hrs." ".$m."min";
													$dif2= abs($d.$space); $dif2= $dif2 + 1; ?>
	    <td><input name="dias" type="text" style="text-align:right" id="dias" size="6" maxlength="3" value="<?=$dif2?>" readonly/> d&iacute;as.</td>
		<td colspan="1"></td>
	</tr>
	<tr><td></td></tr>
	</table>
	<!--<div style="width:800px" class="divFormCaption">Duraci&oacute;n de Presupuesto</div>
	<table width="800" class="tblForm"> 
	<tr><td></td></tr>
	<tr><td></td>
		<td class="tagForm">F. Inicio:</td>
	    <td colspan="2"><input name="fdesde" type="text" id="fdesde" size="10" value="<?=$fecha?>" maxlength="10"/>*<i>(dd-mm-aaaa)</i></td>
	</tr>	
	<tr><td></td>
		<td class="tagForm">F. Termino:</td>
	    <td colspan="2"><input name="fhasta" type="text" id="fhasta" size="10" value="<?=$fecha2?>" maxlength="10"/>*<i>(dd-mm-aaaa)</i></td>
	</tr>	
	<tr><td></td>
		<td class="tagForm">Duraci&oacute;n:</td>
		<td colspan="2"><input type="text" name="dias" id="dias" size="6" maxlength="3" onclick="comparaFecha()" readonly/> d&iacute;as.</td>
	</tr>
	<tr><td></td></tr>
	</table>-->
	<!---  TABLA 2 ------>
	<div style="width:850px" class="divFormCaption">Monto de Presupuesto</div>
	<table width="850" class="tblForm">
	<tr>
	  <td>
	    <table width="642" height="5">
		 <tr>
		  <td colspan="2" width="200"></td>
		  <td width="102" align="center">PRESUPUESTADO</td>
          <td colspan="3"></td>
		  <td width="115" align="center">AJUSTADO</td>
		  <td colspan="3"></td>
		  <td width="115" align="center">COMPROMETIDO</td>
		  <td colspan="3"></td>
          <td width="115" align="center">CAUSADO</td>
		  <td colspan="3"></td>
		  <td width="59" align="center">PAGADO</td>
		  <td colspan="3"></td>
		  <td width="62" align="center">DISPONIBLE</td>
		 </tr>
         <?
          /**** OPERACION  *****/
		  $s_mostrar= "select * from pv_presupuestodet where Organismo= '$cod_organismo' and CodPresupuesto='$cod_presupuesto'";
		  $q_mostrar= mysql_query($s_mostrar) or die ($s_mostrar.mysql_error());
		  $r_mostrar = mysql_num_rows($q_mostrar);
		  
		  for($i=0;$i<$r_mostrar;$i++){
		     $f_mostrar = mysql_fetch_array($q_mostrar);
			 $montoCompromiso = $montoCompromiso + $f_mostrar['MontoCompromiso'];
			 $montoAjustado = $montoAjustado + $f_mostrar['MontoAjustado'];
			 $montoCausado = $montoCausado + $f_mostrar['MontoCausado'];
			 $montoPagado = $montoPagado + $f_mostrar['MontoPagado'];
		  }
		 ?>
		 <tr>
		  <td colspan="2" width="200" align="right">Totales:</td><? $m_presupuestado=$fieldP['MontoAprobado']; $m_presupuestado=number_format($m_presupuestado,2,',','.')?>
		  <td align="center"><input type="text" align="right" class="inputG" style="text-align:right" size="14" name="presupuestado2" id="presupuestado2" value="<?=$m_presupuestado?>"  readonly /></td>
		  <td colspan="3"></td>
		  <td align="center">
            <input type="text" class="inputG" size="14" style="text-align:right" id="ajustado2" value="<?=number_format($montoAjustado,2,',','.');?>" readonly/>
          </td>
          <td colspan="3"></td>
		  <td align="center">
            <input type="text" class="inputG" size="14" style="text-align:right" id="comprometido2" value="<?=number_format($montoCompromiso,2,',','.');?>" readonly/>
          </td>
		  <td colspan="3"></td>
           <td align="center"><input type="text" class="inputG" size="14" style="text-align:right" id="causado2" value="<?=number_format($montoCausado,2,',','.');?>" readonly/></td>
		  <td colspan="3"></td>
		  <td align="center"><input type="text" class="inputG" size="14" style="text-align:right" name="pagado2" value="<?=number_format($montoPagado,2,',','.');?>" readonly/></td>
		  <td colspan="3"></td><? $montoDisponible = $montoAjustado - $montoCompromiso;?>
		  <td align="center"><input type="text" class="inputG" size="14" style="text-align:right" name="disponible" value="<?=number_format($montoDisponible,2,',','.');?>" readonly /></td>
		 </tr>
		</table>
	  </td>
	 </tr>
	</table>
	
    <table width="850" class="tblForm">
	<tr><td width="19"></td>
	</tr>
	<!--<tr>
	  <td></td>
	  <td class="tagForm">Monto Total Presupuesto:</td>
	  <td ><input name="totalAnteproyecto" type="text" id="totalAnteproyecto" size="20" maxlength="15" readonly/>Bs.F</td>
	</tr>
	<tr>
	  <td></td>
	  <td class="tagForm">Monto Autorizado:</td>
	  <td><input name="montoautorizado" id="montoautorizado" type="text" size="20" maxlength="15" readonly/>Bs.F</td>
	</tr>
	<tr>
	  <td></td>
	  <td class="tagForm">Diferencia:</td>
	  <td><input name="diferencia" id="diferencia" type="text" size="20" maxlength="15"  readonly/>Bs.F</td>
	</tr>-->
	
	<tr><td></td>
	   <td width="220" class="tagForm">Preparado por:</td>
	   <td width="520"><input name="prepor" id="prepor" type="text" size="60" value="<?=$fieldP['PreparadoPor']?>" readonly/></td>
	<tr>
	<tr><td></td>
	   <td class="tagForm">Aprobado por:</td>
	   <td><input name="nomempleado" id="nomempleado" type="text" size="60" value="<?=$fieldP['AprobadoPor']?>" readonly/></td>
	</tr>
	<tr><td></td>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td colspan="1">
			<input name="ult_usuario" type="text" id="ult_usuario" size="30" value="<?=$fieldP['UltimoUsuario']?>" readonly />
			<input name="ult_fecha" type="text" id="ult_fecha" size="23" value="<?=$fieldP['UltimaFechaModif']?>" readonly />		</td>
	</tr><tr><td height="5"></td></tr>
</table></div>
<!-- //////////////////////////////////////////////////////////////////////////////////////////// -->
<!-- ///////////////////////////////// **** TAB2 **** /////////////////////////////////////////// -->
<div id="tab2" style="display:none;">
<div style="width:1000px; height:15px" class="divFormCaption">Detalle de Presupuesto</div>
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1000px; height:500px;">
<table width="100%" class="tblLista" border="0">
  <tr class="trListaHead">
		<th width="78" scope="col"># Partida</th>
		<th scope="300">Denominaci&oacute;n</th>
		<th width="44" scope="col">T.Presupuestado</th>
        <th width="44" scope="col">T.Ajustado</th> 
		<th width="24" scope="col">T.Comprometido</th>
        <th width="44" scope="col">T.Causado</th>
		<th width="44" scope="col">T.Pagado</th>
		<th width="44" scope="col">T.Disponible</th>
  </tr>
<?php
//------------------------------------------------------------------------------------------------------------
///////////////////************ MOSTRAR PARTIDAS ASOCIADAS AL ANTEPROYECTO *************/////////////////////
//////////////////************* CARGA LOS DATOS DE LA TABLA "pv_antepresupuestodet" ****///////////////////// 
//------------------------------------------------------------------------------------------------------------
$total=0; $year=date("Y");

 
	
$sql="SELECT * 
        FROM pv_presupuesto 
       WHERE CodPresupuesto='$cod_presupuesto' AND
	         Organismo='$cod_organismo' and 
			 EjercicioPpto='$ejercicioPpto'";// Consulta el año del ejercicio presupuestario
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
	  $montoA1 = 0;
	  $montoAj1 = 0; // Monto Ajustado
	  $montoC1 = 0; // Monto Compromiso
	  $montoCa1 = 0; // Monto Causado
	  $montoP1 = 0; // Monto Pagado
	  
	  
	  
	 $sqldet="SELECT * FROM pv_presupuestodet 
					  WHERE partida='".$fieldP['partida1']."' AND
							tipocuenta='".$fieldP['cod_tipocuenta']."' AND
							CodPresupuesto='".$fielDet['CodPresupuesto']."'";
	 $qrydet=mysql_query($sqldet);
	 $rwdet=mysql_num_rows($qrydet);
	 for($a=0; $a<$rwdet; $a++){
	  $fdet=mysql_fetch_array($qrydet);
	  $cont1 = $cont1 + 1;
	  $montoA1 = $montoA1 + $fdet['MontoAprobado']; // Monto Aprobado
	  $montoAj1 = $montoAj1 + $fdet['MontoAjustado']; // Monto Ajustado
	  $montoC1 = $montoC1 + $fdet['MontoCompromiso']; // Monto Compromiso
	  $montoCa1 = $montoCa1 + $fdet['MontoCausado']; // Monto Causado
	  $montoP1 = $montoP1 + $fdet['MontoPagado']; // Monto Pagado
	  $montoD1 = $montoAj1 - $montoC1; // Monto Disponible 
	 }
	 $montoAprobado1 = number_format($montoA1,2,',','.'); 
	 $montoAjustado1 = number_format($montoAj1,2,',','.');
	 $montoCompromiso1 = number_format($montoC1,2,',','.');
	 $montoCausado1 = number_format($montoCa1,2,',','.');
	 $montoPagado1 = number_format($montoP1,2,',','.');
	 $montoDisponible1 = number_format($montoD1,2,',','.');
	 
	 $cont1= $cont1 + 1;
	 $codigo_partida = $fieldP[cod_partida];
	 $pCapturada = $fieldP[partida1];
	 echo "<tr class='trListaBody6'>
	  <td align='center'>".$fieldP['cod_partida']."</td>
	  <td>".$fieldP['denominacion']."</td>
	  <td align='center'>
	     <input type='text' class='inputP' size='12' maxlength='12' style='text-align:right' value='$montoAprobado1' readonly/></td>
	  <td align='center'>
	     <input type='text' class='inputP' size='12' maxlength='12' style='text-align:right' value='$montoAjustado1' readonly/></td>
	  <td align='center'>
	     <input type='text' class='inputP' size='12' maxlength='12' style='text-align:right' value='$montoCompromiso1' readonly/></td>
	   <td align='center'>
	     <input type='text' class='inputP' size='12' maxlength='12' style='text-align:right' value='$montoCausado1' readonly/></td>
	   <td align='center'>
	     <input type='text' class='inputP' size='12' maxlength='12' style='text-align:right' value='$montoPagado1' readonly/></td>
	  <td align='right'>
	     <b><input class='inputP' type='text' size='12' maxlength='12' id='".$codigo_partida."' style='text-align:right' name='".$fielDet['CodPresupuesto']."' value='$montoDisponible1' readonly/></td></b>         
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
	   $montoA2 = 0;
	   $montoAj2 = 0; // Monto Ajustado
	   $montoC2 = 0; // Monto Compromiso
	   $montoCa2 = 0; // Monto Causado
	   $montoP2 = 0; // Monto Pagado
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
	   $montoA2 = $montoA2 + $fdet['MontoAprobado']; // Monto Aprobado
	   $montoAj2 = $montoAj2 + $fdet['MontoAjustado']; // Monto Ajustado
	   $montoC2 = $montoC2 + $fdet['MontoCompromiso']; // Monto Compromiso
	   $montoCa2 = $montoCa2 + $fdet['MontoCausado']; // Monto Causado
	   $montoP2 = $montoP2 + $fdet['MontoPagado']; // Monto Pagado
	   $montoD2 = $montoAj2 - $montoC2; // Monto Disponible 
	 }
	   $montoAprobado2 = number_format($montoA2,2,',','.'); 
	   $montoAjustado2 = number_format($montoAj2,2,',','.');
	   $montoCompromiso2 = number_format($montoC2,2,',','.');
	   $montoCausado2 = number_format($montoCa2,2,',','.');
	   $montoPagado2 = number_format($montoP2,2,',','.');
	   $montoDisponible2 = number_format($montoD2,2,',','.');
	  
	 // echo $montoDisponible2."<br>";
	  
      $codigo_generica = $fieldP['cod_partida'];
      $pCapturada2 = $fieldP['partida1'];
	  $gCapturada = $fieldP['generica'];
	   echo "<tr class='trListaBody5'>
		 <td align='center'>".$fieldP['cod_partida']."</td>
		 <td>".$fieldP['denominacion']."</td>
		 <td align='center'><b>
		    <input type='text' class='inputG' size='12' maxlength='12' style='text-align:right' value='$montoAprobado2' readonly/></td>
		 <td align='center'><b>
		    <input type='text' class='inputG' size='12' maxlength='12' style='text-align:right' value='$montoAjustado2' readonly/></td>
		 <td align='center'><b>
		    <input type='text' class='inputG' size='12' maxlength='12' style='text-align:right' value='$montoCompromiso2' readonly/></td>
		 <td align='center'><b>
		    <input type='text' class='inputG' size='12' maxlength='12' style='text-align:right' value='$montoCausado2' readonly/></td>
		 <td align='center'><b>
		    <input type='text' class='inputG' size='12' maxlength='12' style='text-align:right' value='$montoPagado2' readonly/></td>
		 <td align='right'><b>
		    <input type='text' class='inputG' size='12' maxlength='12' style='text-align:right' id='".$codigo_generica."' name='".$fielDet['CodPresupuesto']."' value='$montoDisponible2' readonly/></td></b>         
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
    $monto = $fielDet['MontoAprobado'];
	$montoAjustado3 = number_format($fielDet['MontoAjustado'],2,',','.'); // Monto Ajustado
	$montoCompromiso3 = number_format($fielDet['MontoCompromiso'],2,',','.'); // Monto Compromiso
	$montoCausado3 = number_format($fielDet['MontoCausado'],2,',','.'); // Monto Causado
	$montoPagado3 = number_format($fielDet['MontoPagado'],2,',','.'); // Monto Pagado
	$montoDisponible3 = number_format(($fielDet['MontoAjustado'] - $fielDet['MontoCompromiso']),2,',','.'); // Monto Disponible
	
	//echo $montoDisponible3;
	
	$total = $monto + $total;
	$totalDisponibleUltimo=0.00;
	$totalAjustado = number_format($totalAjustado,2,'.','') + number_format($fielDet['MontoAjustado'],2,'.',''); // Monto Ajustado
	$totalCompromiso = number_format( $totalCompromiso,2,'.','') + number_format($fielDet['MontoCompromiso'],2,'.',''); // Monto Compromiso
	$totalCausado = number_format($totalCausado,2,'.','') + number_format($fielDet['MontoCausado'],2,'.',''); // Monto Causado
	$totalPagado = number_format($totalPagado,2,'.','') + number_format($fielDet['MontoPagado'],2,'.',''); // Monto Pagado
	$totalDisponibleUltimo = number_format($totalDisponibleAA,2,'.','') + number_format($totalAjustado,2,'.','') - number_format($totalCompromiso,2,'.',''); // Monto Disponible
	
	//$totalPruebaAjustado = 0.00;
	$totalPruebaAjustado =  number_format($totalPruebaAjustado,2,'.','') +  number_format($totalAjustado,2,'.','') - number_format($totalCompromiso,2,'.','');
	//echo number_format($totalDisponibleAA,2,'.','').' + '.number_format($totalAjustado,2,'.','').' - '.number_format($totalCompromiso,2,'.','').'<br>';
	
	//echo $fielDet['MontoAjustado'].' '.$totalAjustado.' '.$totalCompromiso.' '.$totalDisponible.' '.$totalPruebaAjustado.'<br>';
	//echo  number_format($totalPruebaAjustado,2,',','.');
	

	
	
	
	$totalT=number_format($total,2,',','.');
	$montoD=number_format($monto,2,',','.');
	$tAjustado = number_format($totalAjustado,2,',','.');
	$tCompromiso = number_format($totalCompromiso,2,',','.');
	$tCausado = number_format($totalCausado,2,',','.');
	$tPagado = number_format($totalPagado,2,',','.');
	$totalDisponible = number_format($totalDisponibleUltimo,2,',','.');
	
    $codigo_detalle = $fielDet['cod_partida'];
    echo "<tr class='trListaBody' onclick='mClk(this,\"registro2\");'>
		<td align='center'>".$fielDet['cod_partida']."</td>
		<td>".$f['denominacion']."</td>
		<td align='right'><input type='text' size='12' maxlength='12' style='text-align:right' value='$montoD' readonly/></td>
		<td align='right'><input type='text' size='12' maxlength='12' style='text-align:right' value='$montoAjustado3' readonly/></td>
		<td align='right'><input type='text' size='12' maxlength='12' style='text-align:right' value='$montoCompromiso3' readonly/></td>
		<td align='right'><input type='text' size='12' maxlength='12' style='text-align:right' value='$montoCausado3' readonly/></td>
		<td align='right'><input type='text' size='12' maxlength='12' style='text-align:right' value='$montoPagado3' readonly/></td>   
		<td align='right'><input type='text' size='12' maxlength='12' style='text-align:right' id='$codigo_partida|$codigo_generica' name='".$fielDet['CodPresupuesto']."' value='$montoDisponible3' onchange='sumarPartida(this.value, this.id);' onfocus='obtener(this.value);' readonly/></td>         
	   </tr>";
	   }
}}echo"<tr height='15'></tr><tr><td colspan='1'></td>
	   <td align='right'><b>Total:</b></td>
	   <td align='center'><input type='text' style='text-align:right' id='totalAnt' name='totalAnt' size='13' value='$totalT' readonly/></td>
	   <td align='center'><input type='text' style='text-align:right' size='13' value='$tAjustado'  readonly/></td>
	   <td align='center'><input type='text' style='text-align:right' size='13' value='$tCompromiso' readonly/></td>
	   <td align='center'><input type='text' style='text-align:right' size='13' value='$tCausado' readonly/></td>
	   <td align='center'><input type='text' style='text-align:right' size='13' value='$tPagado' readonly/></td>
	   <td align='center' class='trListaBody'><input type='hidden' id='total' name='total' size='15' value='$total'/>";
	   echo"<input type='text' style='text-align:right' id='totalAnt2' name='totalAnt2' size='13' value='$totalDisponible' readonly/>
			<input type='hidden' class='inputT' id='totalAnt' name='totalAnt' size='13' value='$totalT' readonly/></td>
   </tr>";
//	------------------------------------------------------------------------------------------------------------
//	------------------------------------------------------------------------------------------------------------
$rows=(int)$rows;
echo "
	<script type='text/javascript' language='javascript'>
		totalLista($registros);
		totalLotes($registros, $rows, ".$limit.");
	</script>";
	
	
	//echo $totalDisponible.' '.$totalAjustado.' '.$totalCompromiso;
?>
</table>
</div></td></tr></table>
<!--<center>
<input name="btguardar" type="submit" id="btguardar" value="Guardar Registro"/>
<input name="btcancelar" type="button" id="btcancelar" value="Cancelar" onclick="cargarPagina(this.form, 'framemain.php');"/>
</center>--></div>

<!--////////////////////////// **************TAB AJUSTES POSITIVOS ******************  ///////////////////////--> 
<!---/////////////////////////////////////////////////////////////////////////////////////////////////////////-->  
<div id="tab3" style="display:none">
<div style="width:850px; height:15px" class="divFormCaption">Ajuste Positivo</div>
<table align="center">
  <tr>
     <td align="center"><div style="overflow:scroll; width:800px; height:500px;">
      <table width="100%" class="tblLista" border="0">
       <tr class="trListaHead">
        <th width="15" scope="col">Organismo</th>
		<th width="25" scope="col"># Presupuesto</th>
		<th width="50" scope="col"># Ajuste</th>
        <th width="65" scope="col"># Partida</th>
		<th width="45" scope="col">F. Ajuste</th>
		<th width="150" scope="col">Descripci&oacute;n</th>
        <th width="45" scope="col">T. Ajuste</th>
		<th width="45" scope="col">Estado</th>
        <th width="70" scope="col">Total Ajuste</th>
       </tr>
  <?
   $SPOS="SELECT 
               * 
		   FROM 
		       pv_ajustepresupuestario 
           WHERE 
		       CodPresupuesto='$cod_presupuesto' AND
               TipoAjuste='IN' AND
			   Organismo='$cod_organismo'"; 
   $QPOS=mysql_query($SPOS) or die ($SPOS.mysql_error());
   $RPOS=mysql_num_rows($QPOS);  
   if($RPOS!=0){
     for($i=0; $i<$RPOS; $i++){
	   $FPOS=mysql_fetch_array($QPOS);
	   $SQL="SELECT 
	              *
	          FROM 
			       pv_ajustepresupuestariodet 
			 WHERE 
			       Organismo = '".$FPOS['Organismo']."' and 
				   CodPresupuesto = '".$FPOS['CodPresupuesto']."' and 
				   CodAjuste = '".$FPOS['CodAjuste']."'"; 
	   $QRY=mysql_query($SQL) or die ($SQL.mysql_error()); 
	   $rowsql = mysql_num_rows($QRY);
	   if($rowsql!=0){
		 for($c=0; $c<$rowsql; $c++){
		   $FIELD=mysql_fetch_array($QRY);
		   $monto=number_format($FIELD['MontoAjuste'],2,',','.');
		   list($a,$m,$d)=SPLIT('[/.-]',$FPOS['FechaAjuste']); $f_ajuste1=$d.'-'.$m.'-'.$a;
		   echo"
			<tr class='trListaBody' onclick='mClk(this,\"registro2\");'>
			 <td align='center'>".$FIELD['Organismo']."</td>
			 <td align='center'>".$FIELD['CodPresupuesto']."</td>
			 <td align='center'>".$FIELD['CodAjuste']."</td>
			 <td align='center'>".$FIELD['cod_partida']."</td>
			 <td align='center'>$f_ajuste1</td>
			 <td align='center'>".$FPOS['Descripcion']."</td>
			 <td align='center'>".$FPOS['TipoAjuste']."</td>
			 <td align='center'>".$FIELD['Estado']."</td>
			 <td align='center'>$monto</td>
			</tr>";
		 }
	   }
	 }
   }
   ?>
 </table>
 </div></td></tr></table>
</div>
<!--////////////////////////// **************TAB AJUSTES NEGATIVOS ******************  ///////////////////////--> 
<!---/////////////////////////////////////////////////////////////////////////////////////////////////////////-->  
<div id="tab4" style="display:none">
<div style="width:850px; height:15px" class="divFormCaption">Ajuste Negativo</div>
<table align="center">
  <tr>
    <td align="center"><div style="overflow:scroll; width:800px; height:500px;">
      <table width="100%" class="tblLista" border="0">
        <tr class="trListaHead">
         <th width="15" scope="col">Organismo</th>
		 <th width="25" scope="col"># Presupuesto</th>
		 <th width="50" scope="col"># Ajuste</th>
         <th width="65" scope="col"># Partida</th>
		 <th width="45" scope="col">F. Ajuste</th>
		 <th width="150" scope="col">Descripci&oacute;n</th>
         <th width="45" scope="col">T. Ajuste</th>
		 <th width="45" scope="col">Estado</th>
         <th width="70" scope="col">Total Ajuste</th>
        </tr>
  <?
   $SPOS="SELECT 
                 p.CodPresupuesto,
				 p.Organismo,
				 p.CodAjuste,
				 p.FechaAjuste,
				 p.Descripcion,
				 p.TipoAjuste,
				 pj.MontoAjuste,
				 pj.cod_partida,
				 pj.Estado				 
		     FROM 
			      pv_ajustepresupuestario p
				  inner join pv_ajustepresupuestariodet pj on ((pj.CodPresupuesto = p.CodPresupuesto) and (pj.CodAjuste = p.CodAjuste)) 
            WHERE 
			      p.CodPresupuesto='$cod_presupuesto' AND
                  p.Organismo = '$cod_organismo' and 
				  TipoAjuste='DI'"; 
   $QPOS=mysql_query($SPOS) or die ($SPOS.mysql_error()); 
   $RPOS=mysql_num_rows($QPOS); 
   if($RPOS!=0){
     for($i=0; $i<$RPOS; $i++){
	   $FPOS=mysql_fetch_array($QPOS);
	   if($FPOS['Estado']=='AP'){ $estado = 'Aprobado';}else $estado = 'Preparado';
	   $monto2=number_format($FPOS['MontoAjuste'],2,',','.');
	   list($a,$m,$d)=SPLIT('[/.-]',$FPOS['FechaAjuste']); $f_ajuste=$d.'-'.$m.'-'.$a;
	   echo"
	    <tr class='trListaBody' onclick='mClk(this,\"registro2\");'>
		 <td align='center'>".$FPOS['Organismo']."</td>
		 <td align='center'>".$FPOS['CodPresupuesto']."</td>
		 <td align='center'>".$FPOS['CodAjuste']."</td>
		 <td align='center'>".$FPOS['cod_partida']."</td>
		 <td align='center'>$f_ajuste</td>
		 <td align='center'>".$FPOS['Descripcion']."</td>
		 <td align='center'>".$FPOS['TipoAjuste']."</td>
		 <td align='center'>$estado</td>
		 <td align='center'>$monto2</td>
		</tr>";
	 }
   }
   ?>
</table>
 </div></td></tr></table>
</div>
</form>
</body>
</html>
<!-- VALIDACIONES CAMPOS OBLIGATORIOS  -->
<SCRIPT LANGUAGE="JavaScript">
function verificarDatosgenerales(formulario) {
	       //VALIDACION AÑO DEL PRESUPUESTO
		   if (formulario.ejercicioPpto.value.length <1) {
	  		 alert("Escriba los datos correctos en el campo \"Ejercicio P.\".");
	   		 formulario.ejercicioPpto.focus();
	      return (false);
	      }
          var checkOK ="0123456789";
	      var checkStr = formulario.ejercicioPpto.value;
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
	         formulario.ejercicioPpto.focus(); 
	         return (false); 
	       } 
		   //VALIDACION SECTOR
		   if (formulario.sector.value.length <2) {
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
		   if (formulario.programa.value.length <1) {
	  		 alert("Seleccione el Programa a utilizar.");
	   		 formulario.programa.focus();
	      return (false);
	      }
          var checkOK ="0123456789";
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
	         alert("Seleccione el Programa a utilizar 2."); 
	         formulario.programa.focus(); 
	         return (false); 
	       } 
		   //VALIDACION SUB-PROGRAMA
		   if (formulario.subprograma.value.length <1) {
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
		   //VALIDACION PROYECTO
		   if (formulario.proyecto.value.length <1) {
	  		 alert("Seleccione el Proyecto a utilizar.");
	   		 formulario.proyecto.focus();
	      return (false);
	      }
          var checkOK ="0123456789";
	      var checkStr = formulario.proyecto.value;
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
	         alert("Seleccione el Proyecto a utilizar."); 
	         formulario.proyecto.focus(); 
	         return (false); 
	       }
		   //VALIDACION ACTIVIDAD
		   if (formulario.actividad.value.length <1) {
	  		 alert("Seleccione el Actividad a utilizar.");
	   		 formulario.actividad.focus();
	      return (false);
	      }
          var checkOK ="0123456789";
	      var checkStr = formulario.actividad.value;
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
	         alert("Seleccione el Actividad a utilizar."); 
	         formulario.actividad.focus(); 
	         return (false); 
	       } 
		   //VALIDACION FECHA INICIO
		  /* if (formulario.finicio.value.length <10) {
	  		 alert("Escriba los datos correctos en el campo \"F. Inicio\".");
	   		 formulario.finicio.focus();
	      return (false);
	      }
          var checkOK ="0123456789" + "-";
	      var checkStr = formulario.finicio.value;
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
	         formulario.finicio.focus(); 
	         return (false); 
	       } 
		   //VALIDACION FECHA TERMINO
		   if (formulario.ftermino.value.length <10) {
	  		 alert("Escriba los datos correctos en el campo \"F. Termino\".");
	   		 formulario.ftermino.focus();
	      return (false);
	      }
          var checkOK ="0123456789" + "-";
	      var checkStr = formulario.ftermino.value;
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
	         formulario.ftermino.focus(); 
	         return (false); 
	       } */
		   /*//VALIDACION MONTO AUTORIZADO
		   if (formulario.montoautori.value.length <1) {
	  		 alert("Escriba los datos correctos en el campo \"Monto Autorizado\".");
	   		 formulario.montoautori.focus();
	      return (false);
	      }
          var checkOK ="0123456789" + ",";
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
	       } */
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
</SCRIPT>