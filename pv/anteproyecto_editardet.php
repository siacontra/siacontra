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
  <td class="titulo">Anteproyecto | Actualizar</td>
  <td align="right"><a class="cerrar"; href="../presupuesto/framemain.php">[Cerrar]</a></td>
</tr>
</table><hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="framemain.php?accion=GuardarMontoEditado" method="POST" onsubmit="return presupuestoDetalle(this, CargarPartida);">
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
<div id="tab2" style="display:block;">
<div style="width:800px; height:15px" class="divFormCaption">Detalle de Presupuesto</div>
<!--////////////////// **************** MOSTRAR LA TABLA DE PARTIDAS  ************ //////////////////// -->
<input type="hidden" name="registro2" id="registro2" />
<table width="800" class="tblBotones">
<tr>
  <td align="right">
  <input name="btNuevo" type="button" id="btNuevo" value="Agregar" onclick="cargarVentana(this.form,'lista_partidas.php?pagina=anteproyecto_editardet.php?accion=AGREGAR&limit=0&registro=<?=$registro?>&target=main','height=500, width=800, left=200, top=200, resizable=yes');"/>
  <input name="btBorrar" type="button" id="btBorrar" value="Eliminar" onClick="eliminarPartidaAnte(this.form,'anteproyecto_editardet.php?accion=ELIMINAR&registro=<?=$registro?>');" />
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
if ($accion=="AGREGAR"){
  $sqlSecuencia=mysql_query("SELECT MAX(Secuencia) FROM pv_antepresupuestodet");
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
			  $sqlAnt="SELECT MAX(CodAnteproyecto), MAX(Secuencia) FROM pv_anteproyecto";
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
		   }
		}
	  }
   }
}
elseif ($accion=="ELIMINAR") {
	$sql="DELETE FROM pv_antepresupuestodet WHERE IdAnteProyectoDet='".$registro2."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
}
//------------------------------------------------------------------------------------------------------------
///////////////////************  MOSTRAR PARTIDAS ASOCIADAS AL ANTEPROYECTO *************/////////////////////
//------------------------------------------------------------------------------------------------------------
$sql="SELECT MAX(UltimaFecha) FROM pv_antepresupuesto";// consulta adquiere datos ultima modificacion
$qry=mysql_query($sql) or die ($sql.mysql_error());
$field=mysql_fetch_array($qry);
$sqlConsulta="SELECT * FROM pv_antepresupuesto WHERE UltimaFecha='".$field['0']."'";// consulta de tabla para obtener datos segun condicion //
$qryConsulta=mysql_query($sqlConsulta) or die ($sqlConsulta.mysql_error());
if(mysql_num_rows($qry)!=0){
 $fieldConsulta=mysql_fetch_array($qryConsulta);
 $sqlDet="SELECT cod_partida,Estado,IdAnteProyectoDet,Secuencia,MontoAsignado,tipo FROM pv_antepresupuestodet WHERE CodAnteproyecto='".$fieldConsulta['CodAnteproyecto']."' ORDER BY cod_partida";
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
	/// ********  ORDENO PARTIDAS *********   ////////
	$valor=$fieldPartida[cod_tipocuenta];
	if($cod_partida!=$fieldPartida['cod_partida']){
	////  ORDENA PARTIDAS POR TIPOCUENTA -- PARTIDA=0  -- GENERICA=0
	if(($fielDet['tipocuenta']!=$valor) and ($fieldPartida['partida1']!=0) and ($fieldPartida['generica']==0) and ($fieldPartida['especifica']==0)){
		$codigo_partida = $fielDet['cod_partida'];
	 echo "<tr class='trListaBody6'>
	    <td align='center'>".$fielDet['cod_partida']."</td>
	    <td>".$fieldPartida['denominacion']."</td>
	    <td align='center'>".$fielDet['Estado']."</td>
	    <td align='center'>".$fieldPartida['tipo']."</td>";
	    echo"<td align='right'><input type='text' size='15' maxlength='12' id='".$codigo_partida."' name='".$fielDet['IdAnteProyectoDet']."' value='".$fielDet['MontoAsignado']."' readonly />Bs.F</td>         
	    </tr>";$valor=$fieldPartida[cod_tipocuenta]; $tcuenta=$fielDet[tipocuenta]; $partida=$fielDet[partida]; $generica=$fielDet[generica];
		
		  ////  ORDENA PARTIDAS POR TIPOCUENTA -- PARTIDA!=0  -- GENERICA!=0 -- ESPECIFICA!=0
	}else{ 
      if(($fielDet['tipocuenta']!=$valor) and ($fieldPartida['partida1']!=0) and ($fieldPartida['generica']!=0) and ($fieldPartida['especifica']==0)){
		$codigo_generica = $fielDet['cod_partida'];
		echo "<tr class='trListaBody5'>
		    <td align='center'>".$fielDet['cod_partida']."</td>
		    <td>".$fieldPartida['denominacion']."</td>
		    <td align='center'>".$fielDet['Estado']."</td>
		    <td align='center'>".$fieldPartida['tipo']."</td>
		    <td align='right'><input type='text' size='15' maxlength='12' id='".$codigo_generica."' name='".$fielDet['IdAnteProyectoDet']."'  value='".$fielDet['MontoAsignado']."' readonly/>Bs.F</td>         
	      </tr>";$valor=$fieldPartida[cod_tipocuenta]; $tcuenta=$fielDet[tipocuenta]; $partida=$fielDet[partida]; $generica=$fielDet[generica];
	 }else{
		  ////  ORDENA PARTIDAS POR TIPO=DETALLE "D"
		$monto=$fielDet['MontoAsignado'];
        $total=$monto + $total;
		$valor=$fielDet[tipocuenta];
		$codigo_detalle = $fielDet['cod_partida'];
		echo "<tr class='trListaBody' onclick='mClk(this,\"registro2\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$fielDet['IdAnteProyectoDet']."'>
		     <td align='center'>".$fielDet['cod_partida']."</td>
		     <td>".$fieldPartida['denominacion']."</td>
		     <td align='center'>".$fielDet['Estado']."</td>
	         <td align='center'>".$fieldPartida['tipo']."</td>
	         <td align='right'><input type='text' size='15' maxlength='12' id='$codigo_partida|$codigo_generica' name='".$fielDet['IdAnteProyectoDet']."' value='".$fielDet['MontoAsignado']."' onchange='sumarPartidaEditada(this.value, this.id);'  onfocus='obtener(this.value);'/>Bs.F</td>         
	       </tr>";$valor=$fielDet[tipocuenta];
}}}}}}}
echo"<tr><td colspan='3'></td>
         <td align='right'><b>Total:</b></td>
		 <td align='center' class='trListaBody'><input type='text' id='total' name='total' size='15' value='$total'/>Bs.F</td></tr>";
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
<input type="hidden" id="montovoy" name="montovoy"/>
<script type="text/javascript" language="javascript">
	totalPuestos(<?=$rows?>);
</script>
</div>
<!--////////////////// ***** ****** ****** **** **************** PESTAÑA Nº 1  ************  ******** ********** ********* //////////////////// -->
<!--////////////////// ***** ****** ****** **** ****************               ************  ******** ********** ********* //////////////////// -->
<div name="tab1" id="tab1" style="display:none;">
<?php
echo "
<table align='center' cellpadding='0' cellspacing='0'>
<tr>
  <td valign='middle'>
   <iframe align='left' name='frmtab1' id='frmtab1' class='frameTab' style='height:498px; width:808px; border:none;' src='anteproyecto_editargen2.php?registro=".$_GET['registro']."'></iframe>
  </td>
 </tr>
</table>";
?>
</div>
<center>
<input name="btguardar" type="submit" id="btguardar" value="Guardar Registro"/>
<input name="btcancelar" type="button" id="btcancelar" value="Cancelar" onclick="cargarPagina(this.form,'framemain.php');"/>
<input type="hidden" name="filas" id="filas" value="<?=$rows?>" />
</center></div>
</form>
</body>
</html>

<table><tr><td valign="middle"><iframe style="border:none" align="left"</td></tr>
