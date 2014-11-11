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
#header A:hover {
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
<form id="frmentrada" name="frmentrada" action="anteproyecto_editar.php?accion=GuardarDatosAnte" method="POST" onsubmit="return verificarDatosgenerales(this,'Guardar')">
<?php
$limit=(int) $limit;
echo "
<input type='hidden' name='codantepres' id='codantepres' value='".$codantepres."'/>
<input type='hidden' name='filtro' id='filtro' value='".$filtro."' />
<input type='hidden' name='regresar' id='regresar' value='".$regresar."' />
<input type='hidden' name='forganismo' id='forganismo' value='".$forganismo."' />
<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />
<input type='text' name='hola' id='hola' value='".$_POST['registro']."' />";
//////////////////*******************************************************************////////////////////////
//  *************  CONSULTA PARA VERIFICAR SI EL ANTEPROYECTO POSEE PARTIDAS AOCIADAS ******************** //
//////////////////*******************************************************************////////////////////////
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
  <td class="titulo">Anteproyecto | Actualizar</td>
  <td align="right"><a class="cerrar"; href="../presupuesto/framemain.php">[Cerrar]</a></td>
</tr>
</table><hr width="100%" color="#333333" />

<!--<form id="frmentrada" name="frmentrada" action="anteproyecto_editar.php?accion=" method="POST" onsubmit="return presupuestoDetalle(this, CargarPartida);">-->
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
<!--////////////////// ***** ****** ****** **** **************** PESTAÑA Nº 2  ************ ******* ******** ********** ********* //////////////////// -->
<!--////////////////// **************** *************************          ************************* **************************** //////////////////// -->
<div id="tab2" style="display:none;">
<div style="width:800px; height:15px" class="divFormCaption">Detalle de Presupuesto</div>
<!--////////////////// **************** MOSTRAR LA TABLA DE PARTIDAS  ************ //////////////////// -->
<table width="800" class="tblBotones">
<tr>
  <td align="right">
  <input name="btNuevo" type="button" id="btNuevo" value="Agregar" onclick="cargarVentana(this.form,'lista_partidas.php?pagina=anteproyecto_editar.php?accion=AGREGAR&limit=0&registro=<?=$registro?>&target=main','height=500, width=800, left=200, top=200, resizable=yes');"/>
  <input name="btBorrar" type="button" id="btBorrar" value="Eliminar" onClick="eliminarPartida(this.form,'anteproyecto_editar.php?accion=ELIMINAR&registro=<?=$registro?>');" />
  </td>
</tr>
</table>
<table width="800" class="tblLista" border="0">
  <tr class="trListaHead">
		<th width="97" scope="col">Partida</th>
		<th scope="300">Denominaci&oacute;n</th>
		<th width="47" scope="col">Estado</th>
		<th width="37" scope="col">Tipo</th>
		<th width="114" scope="col">MontoUtilizar</th>
  </tr>
<?php
//	------------------------------------------------------------------------------------------------------------
 ///////////////////************ AGREGAR Y ELIMINAR PARTIDAS ASOCIADAS AL ANTEPROYECTO *************/////////////////////
 //////////////////************* CARGA LOS DATOS EN LA TABLA "pv_antepresupuestodet" ****///////////////////// 
//	------------------------------------------------------------------------------------------------------------
$fecha=date("Y-m-d H:m:s");
if($accion=="AGREGAR"){
  $sqlSecuencia=mysql_query("SELECT Secuencia FROM pv_antepresupuestodet WHERE CodAnteproyecto='".$_POST['registro']."'");
  $fieldSec=mysql_fetch_array($sqlSecuencia);
  for($i=1; $i<=$filas; $i++){
	if($_POST[$i]!=""){
	  $sqlpartida=mysql_query("SELECT * FROM pv_partida WHERE cod_partida='".$_POST[$i]."'");
	  if(mysql_num_rows($sqlpartida)!=0){
		$field=mysql_fetch_array($sqlpartida);
		$sqlAntep="SELECT * FROM pv_antepresupuestodet WHERE cod_partida='".$_POST[$i]."' AND Secuencia='".$fieldSec['Secuencia']."'";
		$qryAntep=mysql_query($sqlAntep) or die ($sqlAntep.mysql_error());
		if(mysql_num_rows($qryAntep)!=0){
		  echo"<script>";
		  echo"alert('Los datos ya han sido ingresado anteriormente')";
		  echo"</script>";
		}else{
		  $sqlAnt="SELECT CodAnteproyecto,Secuencia FROM pv_anteproyecto WHERE CodAnteproyecto='".$_POST['registro']."', Secuencia='".$fieldSec['Secuencia']."'";
		  $qryAnt=mysql_query($sqlAnt) or die ($sqlAnt.mysql_error());
		  $fieldAnt=mysql_fetch_array($qryAnt);
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
												        '".$fieldAnt['CodAnteproyecto']."',
												        '".$_POST[$i]."',
														'".$field['partida1']."',
														'".$field['generica']."',
														'".$field['especifica']."',
														'".$field['subespecifica']."',
														'".$field['cod_tipocuenta']."',
														'".$field['Estado']."',
														'".$_SESSION['USUARIO_ACTUAL']."',
														'$fecha',
														'".$field['tipo']."',
														'".$fieldAnt['Secuencia']."')";
	    $query=mysql_query($sql) or die ($sql.mysql_error());
}}}}}
else{
  if($accion=="ELIMINAR") {
	$sql="DELETE FROM pv_antepresupuestodet WHERE CodAnteproyecto='".$reg."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
  }
}
//	------------------------------------------------------------------------------------------------------------
 ///////////////////************ MOSTRAR PARTIDAS ASOCIADAS AL ANTEPROYECTO *************/////////////////////
 //////////////////************* CARGA LOS DATOS DE LA TABLA "pv_antepresupuestodet" ****///////////////////// 
//	------------------------------------------------------------------------------------------------------------
$sqlDet="SELECT cod_partida,Estado,IdAnteProyectoDet,Secuencia,MontoAsignado FROM pv_antepresupuestodet WHERE CodAnteproyecto='".$_POST['registro']."' ORDER BY cod_partida";
$query=mysql_query($sqlDet) or die ($sqlDet.mysql_error());
$rows=mysql_num_rows($query);
for($i=0; $i<$rows; $i++){
  $fielDet=mysql_fetch_array($query);
  $codpartida=$fielDet['cod_partida'];
  $sqlPartida="SELECT cod_tipocuenta,cod_partida,partida1,generica,especifica,Estado,tipo,denominacion FROM pv_partida WHERE cod_partida='$codpartida'";
  $qryPartida=mysql_query($sqlPartida) or die ($sqlPartida.mysql_error());
  if(mysql_num_rows($qryPartida)!=0){
    $fieldPartida=mysql_fetch_array($qryPartida);
	/// ********  ORDENO PARTIDAS 
    $valor=$fieldPartida[cod_tipocuenta];
	if($cod_partida!=$fieldPartida['cod_partida']){
	  ////  ORDENA PARTIDAS POR TIPOCUENTA -- PARTIDA=0  -- GENERICA=0
	  if(($fielDet['tipocuenta']!=$valor) and ($fieldPartida['partida1']!=0) and ($fieldPartida['generica']==0) and ($fieldPartida['especifica']==0)){
		 echo "<tr class='trListaBody6' onclick='mClk(this,\"reg\");' onmouseover='mOvr(this);' 
				          onmouseout='mOut(this);' id='".$fielDet['IdAnteProyectoDet']."'>
			     <td align='center'>".$fielDet['cod_partida']."</td>
		         <td>".$fieldPartida['denominacion']."</td>
		         <td align='center'>".$fielDet['Estado']."</td>
		         <td align='center'>".$fieldPartida['tipo']."</td>
		         <td align='right'><input type='text' size='15' maxlength='12' name='montoAsignado' readonly/>Bs.F</td>         
	             </tr>";$valor=$fieldPartida[cod_tipocuenta]; $tcuenta=$fielDet[tipocuenta]; $partida=$fielDet[partida]; $generica=$fielDet[generica];
	  ////  ORDENA PARTIDAS POR TIPOCUENTA -- PARTIDA!=0  -- GENERICA!=0 -- ESPECIFICA!=0
	  }else{ 
		 if(($fielDet['tipocuenta']!=$valor) and ($fieldPartida['partida1']!=0) and ($fieldPartida['generica']!=0) and ($fieldPartida['especifica']==0)){
		   echo "<tr class='trListaBody5' onclick='mClk(this,\"reg\");' onmouseover='mOvr(this);' 
				          onmouseout='mOut(this);' id='".$fielDet['IdAnteProyectoDet']."'>
			     <td align='center'>".$fielDet['cod_partida']."</td>
		         <td>".$fieldPartida['denominacion']."</td>
		         <td align='center'>".$fielDet['Estado']."</td>
		         <td align='center'>".$fieldPartida['tipo']."</td>
		         <td align='right'><input type='text' size='15' maxlength='12' name='montoAsignado' readonly/>Bs.F</td>         
	             </tr>";$valor=$fieldPartida[cod_tipocuenta]; $tcuenta=$fielDet[tipocuenta]; $partida=$fielDet[partida]; $generica=$fielDet[generica];
		 }else{
		    ////  ORDENA PARTIDAS "NO UTILIZADO"
		    if(($tcuenta==$fieldPartida['cod_tipocuenta']) and ($partida==$fielDet['tipocuenta']) and ($generica==$fielDet['tipocuenta'])){
			  if(($fieldPartida['cod_tipocuenta']!=$valor) and ($fieldPartida['partida1']!=0) and ($field['generica']!=0)){
	               echo "<tr class='trListaBody' onclick='mClk(this,\"reg\");' onmouseover='mOvr(this);' 
				          onmouseout='mOut(this);' id='".$fielDet['IdAnteProyectoDet']."'>
		            <td align='center'>".$fielDet['cod_partida']."</td>
		            <td>".$fieldPartida['denominacion']."</td>
		            <td align='center'>".$fielDet['Estado']."</td>
		            <td align='center'>".$fieldPartida['tipo']."</td>
		            <td align='right'><input type='text' size='15' maxlength='12' name='montoAsignado'  readonly/>Bs.F</td>         
	                </tr>";$valor=$fielDet[tipocuenta];}
			 }else{
			    ////  ORDENA PARTIDAS POR TIPO=DETALLE "D"
			    $monto=$fielDet['MontoAsignado'];
                $total=$monto + $total;
				$valor=$fielDet[tipocuenta];
		        echo "<tr class='trListaBody' onclick='mClk(this,\"reg\");' onmouseover='mOvr(this);' 
				          onmouseout='mOut(this);' id='".$fielDet['IdAnteProyectoDet']."'>
			          <td align='center'>".$fielDet['cod_partida']."</td>
		              <td>".$fieldPartida['denominacion']."</td>
		              <td align='center'>".$fielDet['Estado']."</td>
		              <td align='center'>".$fieldPartida['tipo']."</td>
		              <td align='right'><input type='text' size='15' maxlength='12' name='montoAsignado' readonly/>Bs.F</td>         
	                  </tr>";$valor=$fielDet[tipocuenta];
}}}}}}
//	------------------------------------------------------------------------------------------------------------
//	------------------------------------------------------------------------------------------------------------
//	------------------------------------------------------------------------------------------------------------
?>
</table>
<script type="text/javascript" language="javascript">
	totalPuestos(<?=$rows?>);
</script>
</div>
<!--////////////////// **** **************** PESTAÑA Nº 1  ************ ******* ********  ********* //////////////////// -->
<!--////////////////// **** ****************               ************ ******* ********  ********* //////////////////// -->
<div name="tab1" id="tab1" style="display:block;">
<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location:index.php");
//	------------------------------------
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('01', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="css1.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="fscript03.js"></script>
<body>
<? 
echo "<input type='text' name='hola' id='hola' value='".$_POST['registro']."' />";
if($accion=="EDITAR"){
  $sql="SELECT CodAnteproyecto,Estado FROM pv_antepresupuesto WHERE CodAnteproyecto='".$_POST['registro']."'";
  $query=mysql_query($sql) or die ($sql.mysql_error());
  if(mysql_num_rows($query)!=0){
	$field=mysql_fetch_array($query);
	if(($field[1]=="Aprobado") or ($field[1]=="Anulado")){ ?>
	   <script type="text/javascript">
        alert("Según el estado del Anteproyecto no es posible ser editado");
        window.location.href="listar_anteproyecto.php";
       </script>
	    <? }
	 }
   }
?>
<?php
$annio_actual=date("Y");
$mes_actual=date("m");
$dia_actual=date("d");
$hora_actual=date("H");
$min_actual=date("i");
$periodo=$annio_actual."-".$mes_actual;
$fecha=$dia_actual."-".$mes_actual."-".$annio_actual;
if ($hora_actual<12) $meridiano="AM";
else{
	$meridiano="PM";
	$hora_actual=(int) $hora_actual;
	$hora_actual-=12;
	if ($hora_actual==0) $hora_actual=12;
	if ($hora_actual<10) $hora_actual="0$hora_actual";
}
$hora=$hora_actual.":".$min_actual;
?>
<form id="frmentrada" name="frmentrada" action="presupuesto_detalle.php?accion=GuardarDatosPres" method="POST" onsubmit="return verificarDatosgenerales(this,'Guardar')">
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
?>
<!--////////////////////////// **********DATOS GENERALES DEL PRESUPUESTO*************  ///////////////////////-->   
<div id="tab1" style="display:block;">
<div style="width:790px" class="divFormCaption">Informaci&oacute;n de Antepresupuesto</div>
<table width="790" class="tblForm">
	<tr>
		<td width="87"></td>
		<td width="151" class="tagForm">Organismo:</td>
		<td width="342">
			<select name="organismo" id="organismo" class="selectBig">
			<?php 
			// segundo bloque php //* Conectamos a los datos *//
			include "conexion_.php";
			$sql="SELECT CodOrganismo,Organismo FROM mastorganismos WHERE 1";
			$rs=mysql_query($sql);
			while($reg=mysql_fetch_assoc($rs)){
			$codOrganismo=$reg['CodOrganismo'];// Codigo de Sector
			$organismo=$reg['Organismo'];// Codigo Programa
			$p=0;
			if (($cod_sector==$cs)){
			   echo "<option value=$cs>$organismo</option>";
			}
			}
			?></select></td>
		<td colspan="2"></td>
	</tr>
	<tr>
	<td></td>		
		<td class="tagForm">A&ntilde;o P.:</td>
		<td><? $sql=mysql_query("SELECT * FROM pv_antepresupuesto WHERE CodAnteproyecto='".$_POST['registro']."'");
		       if(mysql_num_rows($sql)!=0){
			      $field=mysql_fetch_array($sql);
			   } 
			   list($a, $m, $d)=SPLIT('[/.-]', $field['FechaAnteproyecto']); $fecha="$d-$m-$a";?>
			<!--<input title="A&ntilde;o de Presupuesto" name="anop" type="text" id="anop" size="3" value="<?=$field['EjercicioPpto']?>" readonly/>*-->
			<input title="A&ntilde;o de Presupuesto" name="anop" type="text" id="anop" size="3" maxlength="4" value="<?=$field['EjercicioPpto']?>"/>
			F.Creaci&oacute;n:<input name="fcreacion" type="text" id="fcreacion" size="8" value="<?=$fecha?>" readonly /> 
			 Estado:<input name="estado" type="text" id="estado" size="11" value="<?=$field['Estado']?>" readonly/>		</td>
	</tr>
	</table>
	<!---////////////////////////////////////////////////////////////////////////////////////////////////-->
	<!---/////////////////// ********   CARGAR SELECT SECTOR PRESUPUESTO  *******   /////////////////////-->
	<table width="790" class="tblForm">
	<tr>
	  <td width="83"></td>
	  <td width="181" class="tagForm">Sector:</td>
	  <td width="520"><select name="sector" id="sector" class="selectMed" onchange="getOptions_5(this.id, 'programa', 'subprograma', 'proyecto', 'actividad');">
        <option value=""></option>
        <?php getSector('', 0); ?>
      </select>*</td>
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
	  <td class="tagForm">Sub-Programa:</td>
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
	  <td class="tagForm">Actividad:</td>
	  <td>
			<select name="actividad" id="actividad" class="selectMed" disabled>
				<option value=""></option>
			</select>*	  </td>
	</tr>
	<tr><td></td></tr>
	</table>
	<div style="width:790px" class="divFormCaption">Duraci&oacute;n de Presupuesto</div>
	<table width="790" class="tblForm"> 
	<tr><td></td></tr>
	<tr><td></td><?php
	
				    /*$date1=strtotime($field['FechaInicio']);
					$date2=strtotime($field['FechaFin']);
					//$date1="2010-04-27";
					//$date2="2010-05-27";
					
					$s = ($date1)-($date2);
					$d = intval($s/86400);
					$s -= $d*86400;
					$h = intval($s/3600);
					$s -= $h*3600;
					$m = intval($s/60);
					$s -= $m*60;
					
					$dif= (($d*24)+$h).hrs." ".$m."min";
					$dif2= abs($d.$space);*/
					
					
				
					
					list($a, $m, $d)=SPLIT('[/.-]', $field['FechaInicio']); $fechad="$d-$m-$a";
					list($a, $m, $d)=SPLIT('[/.-]', $field['FechaFin']); $fechah="$d-$m-$a";
					
					$dif2 = getFechaDias($fechad, $fechah)
			      ?>
		<td class="tagForm">F. Inicio:</td>
	    <td colspan="2"><input name="fdesde" type="text" id="finicio" size="10" value="<?=$fechad?>" maxlength="10" />*<i>(dd-mm-aaaa)</i></td>
	</tr>	
	<tr><td></td>
		<td class="tagForm">F. Termino:</td>
	    <td colspan="2"><input name="fhasta" type="text" id="ftermino" size="10" value="<?=$fechah?>" maxlength="10" />*<i>(dd-mm-aaaa)</i></td>
	</tr>	
	<tr><td></td>
		<td class="tagForm">Duraci&oacute;n:</td>
	    <td><input name="dias" type="text" id="dias" size="6" maxlength="3" value="<?=$dif2?>" readonly/> d&iacute;as.</td>
		<td colspan="1"></td>
	</tr>
	<tr><td></td></tr>
	</table>
	<!---  TABLA 2 ------>
	<div style="width:790px" class="divFormCaption">Duraci&oacute;n de Presupuesto</div>
    <table width="790" class="tblForm">
	<tr><td></td></tr>
	<tr><td></td>
	  <td class="tagForm">Monto Autorizado:</td>
	  <td ><input name="montoautori" type="text" id="montoautori" size="20" maxlength="15" value="<?=$field['MontoPresupuestado']?>"/>*<em>(99999999,99)</em></td>
	</tr>
	<tr><td></td>
	  <td class="tagForm">Monto Restante:</td>
	  <td><input name="montorestante" id="montorestante" type="text" size="20" maxlength="15" readonly/></td>
	</tr>
	<tr><td></td></tr>
	<tr><td></td>
	   <td class="tagForm">Preparado por:</td>
	   <td><input name="prepor" id="prepor" type="text" size="60" value="<?=$field['PreparadoPor']?>" readonly/></td>
	<tr>
	<tr><td></td>
	   <td class="tagForm">Aprobado por:</td>
	   <td><input name="codempleado" type="hidden" id="codempleado" value="" />
	       <input name="nomempleado" id="nomempleado" type="text" size="60" value="<?=$field['AprobadoPor']?>" readonly/>
		   <input name="bt_examinar" id="bt_examinar" type="button" value="..." onclick="cargarVentana(this.form, 'lista_aprobador.php?limit=0&campo=1', 'height=500, width=800, left=200, top=200, resizable=yes');" /> </td>
	</tr>
	<tr><td></td>
		<? $ahora=date("Y-m-d H:m:s");
           echo"<td class='tagForm'>&Uacute;ltima Modif.:</td>
	           <td>
	             <input name='ult_usuario' type='text' id='ult_usuario' size='30' value='".$_SESSION['USUARIO_ACTUAL']."' readonly />
	             <input name='ult_fecha' type='text' id='ult_fecha' size='25' value='$ahora' readonly />
	           </td>";
	   ?>
  </tr>
</table>
<center>
<!--<input name="btguardar" type="submit" id="btguardar" value="Guardar Registro"/>
<input name="btcancelar" type="button" id="btcancelar" value="Cancelar" onclick="cargarPagina(this.form, 'listar_anteproyecto.php');"/>-->
</center></div>
<div id="tab2" style="display:none;">
</form>
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
<!-- VALIDACIONES CAMPOS OBLIGATORIOS  -->
<SCRIPT LANGUAGE="JavaScript">
function verificarDatosgenerales(formulario) {
	
	       //VALIDACION AÑO DEL PRESUPUESTO
		   if (formulario.anop.value.length <1) {
	  		 alert("Escriba los datos correctos en el campo \"Año P.\".");
	   		 formulario.anop.focus();
	      return (false);
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
	         alert("Escriba sólo números en el campo \"Año P.\"."); 
	         formulario.anop.focus(); 
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
		   if (formulario.programa.value.length <2) {
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
	         alert("Seleccione el Programa a utilizar."); 
	         formulario.programa.focus(); 
	         return (false); 
	       } 
		   //VALIDACION SUB-PROGRAMA
		   if (formulario.subprograma.value.length <2) {
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
		   if (formulario.proyecto.value.length <2) {
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
		   if (formulario.actividad.value.length <2) {
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
		   if (formulario.finicio.value.length <10) {
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
		   //VALIDACION FECHA INICIO
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
	       } 
		   //VALIDACION MONTO AUTORIZADO
		   if (formulario.montoautori.value.length <1) {
	  		 alert("Escriba los datos correctos en el campo \"Monto Autorizado\".");
	   		 formulario.montoautori.focus();
	      return (false);
	      }
          var checkOK ="0123456789" + ".,";
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
</SCRIPT>
</div>
<center>
<input name="btguardar" type="submit" id="btguardar" value="Guardar Registro" onclick="cargarPagina(this.form, 'listar_anteproyecto.php');"/>
<input name="btcancelar" type="button" id="btcancelar" value="Cancelar" onclick="cargarPagina(this.form, 'listar_anteproyecto.php');"/>
</center></div>
</form>
</body>
</html>

<table><tr><td valign="middle"><iframe style="border:none" align="left"</td></tr>
>