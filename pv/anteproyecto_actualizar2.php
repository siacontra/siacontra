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
<?php
$annio_actual=date("Y");
$mes_actual=date("m");
$dia_actual=date("d");
$hora_actual=date("H");
$min_actual=date("i");
$periodo=$annio_actual."-".$mes_actual;
$fecha=$dia_actual."-".$mes_actual."-".$annio_actual;
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
<form id="frmentrada" name="frmentrada" action="presupuesto_detalle.php?accion=GuardarDatosPres" method="POST" onsubmit="return verificarDatosgenerales(this,'Guardar')">
<?php
$limit=(int) $limit;
echo "
<input type='hidden' name='codantepres' id='codantepres' value='".$codantepres."'/>
<input type='hidden' name='filtro' id='filtro' value='".$filtro."' />
<input type='hidden' name='regresar' id='regresar' value='".$regresar."' />
<input type='hidden' name='forganismo' id='forganismo' value='".$forganismo."' />
<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />
<input type='text' name='hola' id='hola' value='".$_POST['registro']."' />";

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
  <td class="titulo">Anteproyecto | Nuevo</td>
  <td align="right"><a class="cerrar"; href="../presupuesto/framemain.php">[Cerrar]</a></td>
</tr>
</table><hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="presupuesto_detalle.php" method="POST" onsubmit="return presupuestoDetalle(this, CargarPartida);">
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
<div id="tab2" style="display:none;">
<div style="width:800px; height:15px" class="divFormCaption">Detalle de Presupuesto</div>
<!--////////////////// **************** MOSTRAR LA TABLA DE PARTIDAS  ************ //////////////////// -->
<table width="800" class="tblBotones">
<tr>
  <td align="right">
  <input name="btNuevo" type="button" id="btNuevo" value="Agregar" onclick="cargarVentana(this.form,'lista_partidas.php?pagina=presupuesto_detalle.php?accion=AGREGAR&limit=0&registro=<?=$registro?>&target=main','height=500, width=800, left=200, top=200, resizable=yes');"/>
  <input name="btBorrar" type="button" id="btBorrar" value="Eliminar" onClick="eliminarPartida(this.form,'presupuesto_detalle.php?accion=ELIMINAR&registro=<?=$registro?>');" />
  </td>
</tr>
</table>
<table width="800" class="tblLista" border="0">
  <tr class="trListaHead">
		<th width="100" scope="col">Partida</th>
		<th scope="col">Denominaci&oacute;n</th>
		<th width="75" scope="col">Estado</th>
		<th width="60" scope="col">Tipo</th>
		<th width="60" scope="col">MontoUtilizar</th>
  </tr>
<?php
//	------------------------------------------------------------------------------------------------------------
//	------------------------------------------------------------------------------------------------------------
$fecha=date("Y-m-d H:m:s");
if ($accion=="AGREGAR"){
  $sqlSecuencia=mysql_query("SELECT MAX(Secuencia) FROM pv_antepresupuestodet");
  $fieldSec=mysql_fetch_array($sqlSecuencia);
	for ($i=1; $i<=$filas; $i++) {
		if ($_POST[$i]!="") {
		    $sqlpartida=mysql_query("SELECT * FROM pv_partida WHERE cod_partida='".$_POST[$i]."'");
			if(mysql_num_rows($sqlpartida)!=0){
			  $field=mysql_fetch_array($sqlpartida);
			  $sqlAntep="SELECT * FROM pv_antepresupuestodet WHERE cod_partida='".$_POST[$i]."' AND Secuencia!='".$fieldSec['Secuencia']."'";
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
	$sql="DELETE FROM pv_antepresupuestodet WHERE PLantilla='".$registro."' AND Pregunta='".$det."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
}
//	------------------------------------------------------------------------------------------------------------
//	------------------------------------------------------------------------------------------------------------
//	------------------------------------------------------------------------------------------------------------
$sql="SELECT cod_partida,Estado,IdAnteProyectoDet FROM pv_antepresupuestodet WHERE 1 order by cod_partida";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
for($i=0; $i<$rows; $i++){
  $field=mysql_fetch_array($query);
  if($codpartida!=$field['cod_partida']){
    $codpartida=$field['cod_partida'];
	$sqlPartida="SELECT cod_partida,Estado,tipo,denominacion FROM pv_partida WHERE cod_partida='$codpartida' ORDER BY cod_partida,tipo='T'";
    $qryPartida=mysql_query($sqlPartida) or die ($sqlPartida.mysql_error());
    if(mysql_num_rows($qryPartida)!=0){
      $fieldPartida=mysql_fetch_array($qryPartida);
	echo"<tr class='trListaBody2'><td></td><td colspan='2'>".$fieldPartida['cod_partida']."</td></tr>"; 
    }
  }
        echo"<tr class='trListaBody' onclick='mClk(this, \"registro\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['id_partida']."'>
		<td align='center'>".$field['cod_partida']."</td>
		<td>".$fieldPartida['denominacion']."</td>
		<td align='center'>".$field['Estado']."</td>
		<td align='center'>".$fieldPartida['tipo']."</td>
		<td><input id='montoAsignado' name='montoAsignado'/>Bs.F</td>
	</tr>";
}
//	------------------------------------------------------------------------------------------------------------
//	------------------------------------------------------------------------------------------------------------
//	------------------------------------------------------------------------------------------------------------
?>
</table>
<script type="text/javascript" language="javascript">
	totalPuestos(<?=$rows?>);
</script>
</div>
<!--////////////////// ***** ****** ****** **** **************** PESTAÑA Nº 1  ************ ******* ******** ********** ********* //////////////////// -->
<!--////////////////// ***** ****** ****** **** ****************               ************ ******* ******** ********** ********* //////////////////// -->
<div name="tab1" id="tab1" style="display:block;">
<?php
echo "
<table align='center' cellpadding='0' cellspacing='0'>
	<tr>
    <td valign='middle'>
			<iframe align='left' name='frmtab1' id='frmtab1' class='frameTab' style='height:498px; width:808px; border:none;' src='anteproyecto_actualizar3.php?registro=".$_GET['registro']."'></iframe>
		</td>
  </tr>
</table>";
?>
</div>
<center>
<input name="btguardar" type="submit" id="btguardar" value="Guardar Registro"/>
<input name="btcancelar" type="button" id="btcancelar" value="Cancelar" onclick="cargarPagina(this.form, 'listar_anteproyecto.php');"/>
</center></div>
</form>
</body>
</html>

<table><tr><td valign="middle"><iframe style="border:none" align="left"</td></tr>
