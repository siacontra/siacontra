<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location:index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="../fscript02.js"></script>
<script type="text/javascript" language="javascript" src="../fscript.js"></script>
<style type="text/css">
<!--
UNKNOWN {
        FONT-SIZE: small
}
#header {
        FONT-SIZE: 93%; BACKGROUND: url(../imagenes/bg.gif) #dae0d2 repeat-x 50% bottom; FLOAT: left; WIDTH: 100%; LINE-HEIGHT: normal
}
#header UL {
        PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 10px; LIST-STYLE-TYPE: none
}
#header LI {
        PADDING-RIGHT: 0px; PADDING-LEFT: 9px; BACKGROUND: url(../imagenes/left.gif) no-repeat left top; FLOAT: left; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 0px
}
#header A {
        PADDING-RIGHT: 15px; DISPLAY: block; PADDING-LEFT: 6px; FONT-WEIGHT: bold; BACKGROUND: url(../imagenes/right.gif) no-repeat right top; FLOAT: left; PADDING-BOTTOM: 4px; COLOR: #765; PADDING-TOP: 5px; TEXT-DECORATION: none
}
#header A {
        FLOAT: none
}
#header A:hover {
        COLOR: #333
}
#header #current {
        BACKGROUND-IMAGE: url(../imagenes/left_on.gif)}
#header #current A {
        BACKGROUND-IMAGE: url(../imagenes/right_on.gif); PADDING-BOTTOM: 5px; COLOR: #333
}
-->
</style>
</head>
<body>
<? 
include "fphp.php";
connect();
$sql="SELECT * FROM pv_partida WHERE cod_partida='".$_POST['registro']."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
 if($rows!=0) $field=mysql_fetch_array($query);
 if($field['Estado']=="A") $activo="checked"; else $inactivo="checked";
?>
<table width="100%" height="19" cellpadding="0" cellspacing="0">
<tr>
  <td class="titulo">Anteproyecto | Ajustar</td>
  <td align="right"><a class="cerrar"; href="../framemain.php">[Cerrar]</a></td>
</tr>
</table><hr width="100%" color="#333333" />
<form id="frmentrada" name="frmentrada" action="anteproyecto_ajustar.php?accion=GuardarAjuste" method="POST" onsubmit="return presupuestoDetalle(this, CargarPartida);">
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
<!--////////////////// ***** ****** ****** **** **************** PESTAÑA Nº 2  ************ ******* ******** ********** ********* //////////////////// -->
<!--////////////////// ***** ****** ****** **** **************** ************  ************ ******* ******** ********** ********* //////////////////// -->
<div id="tab2" style="display:none;">
<div style="width:800px; height:15px" class="divFormCaption">Detalle de Presupuesto</div>
<table width="800" class="tblBotones">
<tr>
  <td align="right">
  <!--<input name="btNuevo" type="button" id="btNuevo" value="Agregar" onclick="cargarVentana(this.form,'lista_partidas.php?pagina=anteproyecto_ajustar.php?accion=AGREGAR&limit=0&registro=<?=$registro?>&target=main','height=500, width=800, left=200, top=200, resizable=yes');"/>
  <input name="btBorrar" type="button" id="btBorrar" value="Eliminar" onClick="eliminarPartida(this.form,anteproyecto_ajustar.php?accion=ELIMINAR&registro=<?=$registro?>');" />-->
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
//------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------
$fecha=date("Y-m-d H:m:s");
if($accion=="AGREGAR"){
 $sqlSecuencia=mysql_query("SELECT MAX(Secuencia) FROM pu_antepresupuestodet");/// CONSULTO PARA OBTENER EL CODIGO DE SECUENCIA INTRODUCIDO ANTERIORMENTE
 $fieldSec=mysql_fetch_array($sqlSecuencia);
 for($i=1; $i<=$filas; $i++){
  if($_POST[$i]!=""){
	$sqlpartida=mysql_query("SELECT * FROM pv_partida WHERE cod_partida='".$_POST[$i]."'");
	if(mysql_num_rows($sqlpartida)!=0){
	 $field=mysql_fetch_array($sqlpartida);
	 $sqlAntep="SELECT * FROM pu_antepresupuestodet WHERE cod_partida='".$_POST[$i]."' AND Secuencia='".$fieldSec['Secuencia']."'";
	 $qryAntep=mysql_query($sqlAntep) or die ($sqlAntep.mysql_error());
	 if(mysql_num_rows($qryAntep)!=0){
	   echo"<script>";
	   echo"alert('Los datos ya han sido ingresado anteriormente')";
	   echo"</script>";
	 }else{
	   $sqlAnt="SELECT MAX(CodAnteproyecto), MAX(Secuencia) FROM pu_anteproyecto";
	   $qryAnt=mysql_query($sqlAnt) or die ($sqlAnt.mysql_error());
	   $fieldAnt=mysql_fetch_array($qryAnt);
	   $sql="INSERT INTO pu_antepresupuestodet (Organismo,
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
												        '".$fieldAnt['0']."',
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
														'".$fieldAnt['1']."')";
			  $query=mysql_query($sql) or die ($sql.mysql_error());
}}}}}
elseif($accion=="ELIMINAR"){
	$sql="DELETE FROM pu_antepresupuestodet WHERE PLantilla='".$registro."' AND Pregunta='".$det."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
}
//------------------------------------------------------------------------------------------------------------
///////////////////************  MOSTRAR PARTIDAS ASOCIADAS AL ANTEPROYECTO *************/////////////////////
//------------------------------------------------------------------------------------------------------------
$total=0;
$monto=0;
$sql="SELECT CodAnteproyecto FROM pu_antepresupuesto WHERE CodAnteproyecto='".$_POST['registro']."' ";
$qry=mysql_query($sql) or die ($sql.mysql_error());
if(mysql_num_rows($qry)!=0){
  $field=mysql_fetch_array($qry);
  $sqlDet="SELECT cod_partida,Estado,IdAnteProyectoDet,Secuencia,MontoAsignado FROM pu_antepresupuestodet WHERE CodAnteProyecto='".$field['CodAnteproyecto']."' ORDER BY cod_partida";
  $query=mysql_query($sqlDet) or die ($sqlDet.mysql_error());
  $rows=mysql_num_rows($query);
  for($i=0; $i<$rows; $i++){
     $fielDet=mysql_fetch_array($query);
     if($codpartida!=$fielDet['cod_partida']){
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
		   echo "<tr class='trListaBody6' onmouseover='mOvr(this);' onmouseout='mOut(this);'>
			     <td align='center'>".$fielDet['cod_partida']."</td>
		         <td>".$fieldPartida['denominacion']."</td>
		         <td align='center'>".$fielDet['Estado']."</td>
		         <td align='center'>".$fieldPartida['tipo']."</td>
		         <td align='right'><input type='text' size='15' maxlength='12' name='montoAsignado' value='".$fielDet['MontoAsignado']."'/>Bs.F</td> 
				 <td><input type='checkbox' name='campos[$id]' /></td>        
	             </tr>";$valor=$fieldPartida[cod_tipocuenta]; $tcuenta=$fielDet[tipocuenta]; $partida=$fielDet[partida]; $generica=$fielDet[generica];
		  ////  ORDENA PARTIDAS POR TIPOCUENTA -- PARTIDA!=0  -- GENERICA!=0 -- ESPECIFICA!=0
		  }else{ 
		   if(($fielDet['tipocuenta']!=$valor) and ($fieldPartida['partida1']!=0) and ($fieldPartida['generica']!=0) and ($fieldPartida['especifica']==0)){
		    echo "<tr class='trListaBody5' onclick='mClk(this,\"registro\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$fieldDet['IdAnteProyectoDet']."'>
			     <td align='center'>".$fielDet['cod_partida']."</td>
		         <td>".$fieldPartida['denominacion']."</td>
		         <td align='center'>".$fielDet['Estado']."</td>
		         <td align='center'>".$fieldPartida['tipo']."</td>
		         <td align='right'><input type='text' size='15' maxlength='12' name='montoAsignado' value='".$fielDet['MontoAsignado']."'/>Bs.F</td>
				 <td witch='10'><input type='checkbox' name='campos[$id]'/></td>         
	             </tr>";$valor=$fieldPartida[cod_tipocuenta]; $tcuenta=$fielDet[tipocuenta]; $partida=$fielDet[partida]; $generica=$fielDet[generica];
		   }else{
			    ////  ORDENA PARTIDAS POR TIPO=DETALLE "D"
			    $monto=$fielDet['MontoAsignado'];
                $total=$monto + $total;
				$valor=$fielDet[tipocuenta];
		        echo "<tr class='trListaBody' onclick='mClk(this,\"registro\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$fieldDet['IdAnteProyectoDet']."'>
			          <td align='center'>".$fielDet['cod_partida']."</td>
		              <td>".$fieldPartida['denominacion']."</td>
		              <td align='center'>".$fielDet['Estado']."</td>
		              <td align='center'>".$fieldPartida['tipo']."</td>
		              <td align='right'><input type='text' size='15' maxlength='12' name='montoAsignado' value='".$fielDet['MontoAsignado']."'/>Bs.F</td>
					  <td><input type='checkbox' name='campos[$id]' /></td>         
	                  </tr>";$valor=$fielDet[tipocuenta];
}}}}}}}
echo"<tr><td colspan='3'></td><td align='right'><b>Total:</b></td><td align='center' class='trListaBody'>"; echo"".$total; echo" Bs.F</td></tr>";
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
<!--////////////////// ***** ****** ****** **** **************** PESTAÑA Nº 1  ************  ******** ********** ********* //////////////////// -->
<!--////////////////// ***** ****** ****** **** ****************               ************  ******** ********** ********* //////////////////// -->
<div id="tab1" style="display:block;">
<body>
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
$sql="SELECT * FROM pu_antepresupuesto WHERE CodAnteproyecto='".$_POST['registro']."'";
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
			 Estado:<input name="estado" type="text" id="estado" size="11" value="<?=$field[Estado]?>" readonly/></td>
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
	    <td><input name="vacantes" type="text" id="vacantes" size="6" maxlength="3" value="<?=$dif2?>" readonly/> d&iacute;as.</td>
		<td colspan="1"></td>
	</tr>
	<tr><td></td></tr>
	</table>
	<!---  TABLA 2 ------>
	<div style="width:800px" class="divFormCaption">Duraci&oacute;n de Presupuesto</div>
    <table width="800" class="tblForm">
	<tr><td></td></tr>
	<tr><td></td>
	  <td class="tagForm">Monto Autorizado:</td>
	  <td ><input name="montoautori" type="text" id="montoautori" size="20" maxlength="15" value="<?=$field[MontoPresupuestado]?>" readonly/> Bs.F</td>
	</tr>
	<tr><td></td>
	  <td class="tagForm">Monto Restante:</td><? $montoRestante= $field[MontoPresupuestado] - $total;?>
	  <td><input name="montorestante" id="montorestante" type="text" size="20" maxlength="15" value="<?=$montoRestante ?>" readonly/> Bs.F</td>
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
<!--////////////////// ***** ****** ****** **** **************** PESTAÑA Nº 1  ************  ******** ********** ********* //////////////////// -->
<!--////////////////// ***** ****** ****** **** ****************  HAS AQUI     ************  ******** ********** ********* //////////////////// -->
<center>
<input name="btguardar" type="submit" id="btguardar" value="Guardar Ajuste"/>
<input name="btcancelar" type="button" id="btcancelar" value="Cancelar" onclick="cargarPagina(this.form,'listar_anteproyecto.php');"/>
<input type="hidden" name="filas" id="filas" value="<?=$rows?>" />
</center>
</div>
</form>
</body>
</html>

<table><tr><td valign="middle"><iframe style="border:none" align="left"</td></tr>
