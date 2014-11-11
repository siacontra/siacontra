<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include ("../fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('01', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="../fscript.js"></script>
<script language ="Javascript">
function marcar(){
marca_1();
marca_2();
marca_3();
}
function marca_1(){
if(document.frmentrada.chck1.checked){
document.frmentrada.preparado.disabled=false
document.frmentrada.preparado.style.backgroundColor='#FFFFFF'
document.frmentrada.preparado.value=a1
document.frmentrada.preparado.focus()
}
else{
document.frmentrada.preparado.disabled=true
document.frmentrada.preparado.style.backgroundColor='#FFFFFF'
a1=document.frmentrada.preparado.value
document.frmentrada.preparado.value=""
}
}
function marca_2(){
if(document.frmentrada.chck2.checked){
document.frmentrada.organismo.disabled=false
document.frmentrada.organismo.style.backgroundColor='#FFFFFF'
document.frmentrada.organismo.value=a2
document.frmentrada.organismo.focus()
}
else{
document.frmentrada.organismo.disabled=true
document.frmentrada.organismo.style.backgroundColor='#FFFFFF'
a2=document.frmentrada.organismo.value
document.frmentrada.organismo.value=""
}
}
function marca_3(){
if(document.form.chck3.checked){
document.form.b3.disabled=false
document.form.b3.style.backgroundColor='#FFFFFF'
document.form.b3.value=a3
document.form.b3.focus()
}
else{
document.form.b3.disabled=true
document.form.b3.style.backgroundColor='#D6D3CE'
a3=document.form.b3.value
document.form.b3.value=""
}
}
</script>
</head>

<body onload="marcar()">
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Listar Anteproyecto</td>
		<td align="right"><a class="cerrar" href="../framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
if ($filtrar=="DEFAULT") {
	$annio_actual=date("Y");
	$mes_actual=date("m"); $m=(int) $mes_actual;
	$dia_actual=date("d");	
	$fecha_desde="$annio_actual-$mes_actual-01";
	$fecha_hasta="$annio_actual-$mes_actual-$dia_actual";	
	$filtro="(rh_permisos.FechaIngreso>=*$fecha_desde* AND rh_permisos.FechaIngreso<=*$fecha_hasta*) AND (mastempleado.CodOrganismo=*".$_SESSION["FILTRO_ORGANISMO_ACTUAL"]."* AND mastempleado.CodDependencia=*".$_SESSION["FILTRO_DEPENDENCIA_ACTUAL"]."*)";
	$_POST['chkfingreso']="1";
	$_POST['ffingresod']="01-$mes_actual-$annio_actual";
	$_POST['ffingresoh']="$dia_actual-$mes_actual-$annio_actual";
	$_POST['chkorganismo']="1"; 
	$_POST['chkpreparado']="1"; 
	$_POST['forganismo']=$_SESSION["FILTRO_ORGANISMO_ACTUAL"]; 
	$_POST['fdependencia']=$_SESSION["FILTRO_DEPENDENCIA_ACTUAL"];
}
//	VALORES POR DEFECTO Y VALORES SELECCIONADOS DEL FILTRO AL CARGAR O RECARGAR LA PAGINA...............
//////////  ORGANISMO //////////
if($_POST['chkorganismo']=="1"){ 
   $obj[0]="checked"; $obj[1]=""; $obj[2]=$_POST['forganismo']; 
}else{ 
   $obj[0]=""; $obj[1]="disabled"; $obj[2]=""; 
}
////////  NUMERO ANTEPROYECTO  ////////
if($_POST['chknanteproyecto']=="1"){ 
  $obj[3]="checked"; $obj[4]=""; $obj[5]=$_POST['fnanteproyecto']; 
}else{ 
  $obj[3]=""; $obj[4]="disabled"; $obj[5]=""; 
}
/////////   PREPARADO POR ///////////
if($_POST['chkpreparado']=="1"){ 
  $obj[6]=""; $obj[7]=""; $obj[9]=$_POST['fpreparado'];
}else{ 
  $obj[6]="checked"; $obj[7]="disabled"; $obj[9]=""; 
}
///////   FECHA DE INGRESO /////////
if($_POST['chkfingreso']=="1"){
  $obj[10]="checked"; $obj[11]=""; $obj[12]=$_POST['ffingresod']; $obj[13]=$_POST['ffingresoh'];
}else{ 
  $obj[10]=""; $obj[11]="disabled"; $obj[12]=""; $obj[13]=""; 
}
//////  ESTADO DEL ANTEPROYECTO  //////
if($_POST['chkstatus']=="1"){
  $obj[17]="checked"; $obj[18]=""; $obj[19]=$_POST['fstatus']; 
}else{ 
  $obj[17]=""; $obj[18]="disabled"; $obj[19]="0"; 
}

if ($_POST['chkempleado']=="1") { $obj[14]="checked"; $obj[15]=""; $obj[16]=$_POST['fempleado']; }
else { $obj[14]=""; $obj[15]="disabled"; $obj[16]=""; }
/////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////
if (!$_POST){ 
$MAXLIMIT=30;
echo "
<form name='frmentrada' action='anteproyecto_listar.php' method='POST'>
<input type='hidden' name='limit' id='limit' value='".$limit."'>
<input type='hidden' name='filtro' id='filtro' value='".$filtro."'>
<input type='hidden' name='regresar' id='regresar' value='permisos' />
<div class='divBorder' style='width:900px;'>
<table width='900' class='tblFiltro'>
 <tr>
  <td width='125' align='right'>Organismo:</td>"; ?>
  <td> <input type="checkbox" name="chck2" value="ON" onclick="marca_2()"> 
       <select id="organismo" name="organismo">
       <option value=" "></option>
	 <?php 
    // segundo bloque php //* Conectamos a los datos *//
	include "conexion.php";
	$sql="SELECT * FROM pv_antepresupuesto WHERE 1 ORDER BY CodAnteproyecto";
	$qry=mysql_query($sql) or die ($sql.mysql_error());
	if(mysql_num_rows($qry)!=0){
	  $field=mysql_fetch_array($qry);
	  $sql1="SELECT * FROM mastorganismos WHERE CodOrganismo='".$field['Organismo']."'";
	  $rs=mysql_query($sql1);
	  while($reg=mysql_fetch_assoc($rs)){
	    $cs=$reg['CodOrganismo'];// Codigo de Sector
	    $cp=$reg['Organismo'];// Codigo Programa
	    if($cp!=$PP){
	      $PP=$cp;
	      echo "<option value=$cp>$cp</option>";
	     }
	  }
	 }
    ?>
	</select> 
  
   </td>
  <? echo "
  <td width='125' align='right'>Nro. Anteproyecto:</td>
  <td>
	<input type='checkbox' name='chknanteproyecto' id='chknanteproyecto' value='1' $obj[3] onclick='enabledNanteproyecto(this.form);' />
	<input type='text' name='fnanteproyecto' size='15' maxlength='10' $obj[4] value='$obj[5]' />
  </td>
 </tr>
 <tr>
  <td width='125' align='right'>Preparado Por:</td>"; ?>
  <td> <input type="checkbox" name="chck1" value="ON" onclick="marca_1()"> 
       <select id="preparado" name="preparado">
       <option value=" "></option>
	 <?php 
    // segundo bloque php //* Conectamos a los datos *//
	include "conexion.php";
	$sql="SELECT * FROM pv_antepresupuesto WHERE 1 ORDER BY CodAnteproyecto";
	$rs=mysql_query($sql);
	while($reg=mysql_fetch_assoc($rs)){
	$cs=$reg['CodAnteproyecto'];// Codigo de Sector
	$cp=$reg['PreparadoPor'];// Codigo Programa
	if($cp!=$PP){
	   $PP=$cp;
	   echo "<option value=$cp>$cp</option>";
	}
	}
    ?>
	</select> 
  

  <? echo "
  <td width='125' align='right'>Fecha de Ejercicio:</td>
		<td>
			<input type='checkbox' name='chkfcreado' id='chkfcreado' value='1' $obj[10] onclick='enabledFIngreso(this.form);' />
			<input type='text' name='fcreado' id='fcreado' size='15' maxlength='10' $obj[11] value='$obj[12]' />
		</td>
 </tr>
 <tr>
		<td width='125' align='right'></td>
		<td>
			
		</td>
		<td width='125' align='right' rowspan='2'>Estado:</td>
		<td>
			<input type='checkbox' name='chkstatus' value='1' $obj[17] onclick='enabledStatus(this.form);' />
			<select name='fstatus' id='fstatus' class='selectMed' $obj[18]>
				<option value=''></option>";
				getStatusAnteproyecto($obj[19], 0);
				echo "
			</select>
		</td>
	</tr>
</table>
</div>
<center><input type='submit' name='btBuscar' value='Buscar'></center>
<br /><div class='divDivision'>Listado de Anteproyectos</div><br />";
}else{
echo "
<form name='frmentrada' action='anteproyecto_listar.php' method='POST'>
<input type='hidden' name='limit' id='limit' value='".$limit."'>
<input type='hidden' name='filtro' id='filtro' value='".$filtro."'>
<input type='hidden' name='regresar' id='regresar' value='permisos' />
<div class='divBorder' style='width:900px;'>
<table width='900' class='tblFiltro'>
 <tr>
  <td width='125' align='right'>Organismo:</td>"; ?>
  <td> <input type="checkbox" name="chck2" value="ON" onclick="marca_2()"> 
       <select id="organismo" name="organismo">
       <option value=" "></option>
	 <?php 
    // segundo bloque php //* Conectamos a los datos *//
	include "conexion.php";
	$sql="SELECT * FROM pv_antepresupuesto WHERE 1 ORDER BY CodAnteproyecto";
	$qry=mysql_query($sql) or die ($sql.mysql_error());
	if(mysql_num_rows($qry)!=0){
	  $field=mysql_fetch_array($qry);
	  $sql1="SELECT * FROM mastorganismos WHERE CodOrganismo='".$field['Organismo']."'";
	  $rs=mysql_query($sql1);
	  while($reg=mysql_fetch_assoc($rs)){
	    $cs=$reg['CodOrganismo'];// Codigo de Sector
	    $cp=$reg['Organismo'];// Codigo Programa
	    if($cp!=$PP){
	      $PP=$cp;
	      echo "<option value=$cp>$cp</option>";
	     }
	  }
	 }
    ?>
	</select> 
  
   </td>
  <? echo "
    <td width='125' align='right'>Nro. Anteproyecto:</td>
  <td>
	<input type='checkbox' name='chknanteproyecto' id='chknanteproyecto' value='1' $obj[3] onclick='enabledNanteproyecto(this.form);' />
	<input type='text' name='fnanteproyecto' size='15' maxlength='10' $obj[4] value='$obj[5]' />
  </td>
 </tr>
 <tr>
  <td width='125' align='right'>Preparado Por:</td>"; ?>
  <td> <input type="checkbox" name="chck1" value="ON" onclick="marca_1()"> 
       <select id="preparado" name="preparado">
       <option value=" "></option>
	 <?php 
    // segundo bloque php //* Conectamos a los datos *//
	include "conexion.php";
	$sql="SELECT * FROM pv_antepresupuesto WHERE 1 ORDER BY CodAnteproyecto";
	$rs=mysql_query($sql);
	while($reg=mysql_fetch_assoc($rs)){
	$cs=$reg['CodAnteproyecto'];// Codigo de Sector
	$cp=$reg['PreparadoPor'];// Codigo Programa
	if($cp!=$PP){
	   $PP=$cp;
	   echo "<option value=$cp>$cp</option>";
	}
	}
    ?>
	</select> 
  

  <?php
  
  echo "</td>  </td>
  <td width='125' align='right'>Fecha de Ejercicio:</td>
		<td>
			<input type='checkbox' name='chkfcreado' id='chkfcreado' value='1' $obj[10] onclick='enabledFIngreso(this.form);' />
			<input type='text' name='fcreado' id='fcreado' size='15' maxlength='10' $obj[11] value='$obj[12]' />
		</td>
 </tr>
 <tr>
		<td width='125' align='right'></td>
		<td>
			
		</td>
		<td width='125' align='right' rowspan='2'>Estado:</td>
		<td>
			<input type='checkbox' name='chkstatus' value='1' $obj[17] onclick='enabledStatus(this.form);' />
			<select name='fstatus' id='fstatus' class='selectMed' $obj[18]>
				<option value=''></option>";
				getStatusAnteproyecto($obj[19], 0);
				echo "
			</select>
		</td>
	</tr>
</table>
</div>
<center><input type='submit' name='btBuscar' value='Buscar'></center>
<br /><div class='divDivision'>Listado de Anteproyectos</div><br />";
  
  $sql="SELECT CodAnteProyecto,PreparadoPor,EjercicioPpto,FechaInicio,FechaFin,MontoPresupuestado,Estado FROM pv_antepresupuesto";
  $query=mysql_query($sql) or die ($sql.mysql_error());
  $registros=mysql_num_rows($query);
  echo"CantidadRegistros:".$registros;
 }
?>

<table width="900" class="tblBotones">
  <tr>
	<td><div id="rows"></div></td>
	<td align="center">
		<?php 
		echo "
		<table align='center'>
			<tr>
				<td>
					<input name='btPrimero' type='button' id='btPrimero' value='&lt;&lt;' disabled onclick='setLotes(this.form, \"P\", $registros, ".$limit.");' />
					<input name='btAtras' type='button' id='btAtras' value='&lt;' disabled onclick='setLotes(this.form, \"A\", $registros, ".$limit.");' />
				</td>
				<td>Del</td><td><div id='desde'></div></td>
				<td>Al</td><td><div id='hasta'></div></td>
				<td>
					<input name='btSiguiente' type='button' id='btSiguiente' value='&gt;' disabled onclick='setLotes(this.form, \"S\", $registros, ".$limit.");' />
					<input name='btUltimo' type='button' id='btUltimo' value='&gt;&gt;' disabled onclick='setLotes(this.form, \"U\", $registros, ".$limit.");' />
				</td>
			</tr>
		</table>";
		?> 
		
	</td>
    <td align="right">
		<input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'anteproyecto_datosgenerales.php');" />
		<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'anteproyecto_editargen.php?accion=EDITAR', 'SELF');" />
		<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'anteproyecto_ver.php', 'BLANK', 'height=550, width=850, left=200, top=100, resizable=no');" /> 
		<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarVentana(this.form, 'anteproyecto_pdf.php', 'height=800, width=800, left=200, top=200, resizable=yes');" /> |  
		<input name="btImprimir" type="button" class="btLista" id="btImprimir" value="Imprimir" onclick="abrirPermiso(this.form);" />
		<input name="btAnular" type="button" class="btLista" id="btAnular" value="Anular" onclick="anularAnteproyecto(this.form);" />
	</td>
  </tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table width="900" class="tblLista">
  <tr class="trListaHead">
	<th width="100" scope="col"># Anteproyecto</th>
	<th scope="col">Elaborado Por</th>
	<th width="135" scope="col">Ejer. Presupuestario</th>
	<th width="100" scope="col">Fecha Inicio</th>	
	<th width="100" scope="col">Fecha Fin</th>
	<th width="85" scope="col">Monto</th>
	<th width="75" scope="col">Estado</th>
  </tr>
	<?php
	if($registros!=0){
		//	CONSULTO LA TABLA
  $sql="SELECT CodAnteproyecto,PreparadoPor,EjercicioPpto,FechaInicio,FechaFin,MontoPresupuestado,Estado FROM pv_antepresupuesto 
                       WHERE Organismo='".$_POST['forganismo']."' ORDER BY Codanteproyecto";
  $qry=mysql_query($sql) or die ($sql.mysql_error());
  $rows=mysql_num_rows($qry);
  //	MUESTRO LA TABLA
  for($i=0; $i<$rows; $i++) {
	$field=mysql_fetch_array($qry);		
	echo "
    <tr class='trListaBody' onclick='mClk(this,\"registro\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['CodAnteproyecto']."'>
	  <td align='center'>".$field['CodAnteproyecto']."</td>
	  <td align='center'>".$field['PreparadoPor']."</td>
	  <td align='center'>".$field['EjercicioPpto']."</td>
	  <td align='center'>".$field['FechaInicio']."</td>
	  <td align='center'>".$field['FechaFin']."</td>
	  <td align='center'>".$field['MontoPresupuestado']."</td>
	  <td align='center'>".$field['Estado']."</td>
 </tr>";
	}
}
	$rows=(int)$rows;
	echo "
	<script type='text/javascript' language='javascript'>
		totalPermisos($registros, \"$_ADMIN\", \"$_INSERT\", \"$_UPDATE\", \"$_DELETE\");
		totalLotes($registros, $rows, ".$limit.");
	</script>";
	?>
</table>
</form>
</body>
</html>
