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
</head>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Aprobar Presupuesto</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />
<?php
include "gmsector.php";
//	VALORES POR DEFECTO Y VALORES SELECCIONADOS DEL FILTRO AL CARGAR O RECARGAR LA PAGINA...............
//////////  ORGANISMO //////////
if($_POST['chkorganismo']=="1"){ 
   $obj[0]="checked"; $obj[1]="enabled"; $obj[2]=$_POST['forganismo']; 
}else{ 
   $obj[0]="checked"; $obj[1]="enabled"; $obj[2]=$_POST['forganismo']; 
}
////////  NUMERO ANTEPROYECTO  ////////
if($_POST['chknanteproyecto']=="1"){ 
  $obj[3]="checked"; $obj[4]=""; $obj[5]=$_POST['fnanteproyecto']; 
}else{ 
  $obj[3]=""; $obj[4]="disabled"; $obj[5]=""; 
}
/////////   PREPARADO POR ///////////
if($_POST['chkpreparado']=="1"){ 
  $obj[6]="checked"; $obj[7]=""; $obj[9]=$_POST['fpreparado'];
}else{ 
  $obj[6]=""; $obj[7]="disabled"; $obj[9]=""; 
}
///////////////////////////////////////////////////////////////////////////////////////////
if ($_POST['chkejercicio']=="1"){ 
   $obj[14]="checked"; $obj[15]=""; $obj[16]=$_POST['fejercicio']; 
}else{
   $obj[14]=""; $obj[15]="disabled"; $obj[16]=""; 
 }
///////////////////////////////////////////////////////////////////////////////////////////
if ($_POST['chkfingreso']=="1") { $obj[10]="checked"; $obj[11]=""; $obj[12]=$_POST['ffingresod']; $obj[13]=$_POST['ffingresoh']; }
else { $obj[10]=""; $obj[11]="disabled"; $obj[12]=""; $obj[13]=""; }

if ($_POST['chkstatus']=="1") { $obj[17]="checked"; $obj[18]=""; $obj[19]=$_POST['fstatus']; }
else { $obj[17]=""; $obj[18]="disabled"; $obj[19]="0"; }
////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////*****  APLICACION ******//////////////////////////////////////////
if(!$_POST){ 
 $sql="SELECT * 
	     FROM pv_presupuesto 
		WHERE CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' AND 
		      Estado='Preparado'
		ORDER BY CodPresupuesto";
    $qry=mysql_query($sql) or die ($sql.mysql_error());
    $rows=mysql_num_rows($qry);//echo"CantidadRows:".$rows; 
   $MAXLIMIT=30;
   include "presupuesto_aprobar2.php";?>
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
		<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form,'anteproyecto_ver2.php', 'BLANK', 'height=550, width=850, left=200, top=100, resizable=no');"/> 
		<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarOpcion(this.form, 'anteproyecto_pdf.php', 'BLANK', 'height=800, width=800, left=200, top=200, resizable=yes');" /> | 
		<input name="btAprobar" type="button" class="btLista" id="btAprobar" value="Aprobar" onclick="cargarAprobar(this.form,'anteproyecto_aprobar3.php');"/>
		<input name="btRechazar" type="button" class="btLista" id="btRechazar" value="Anular" onclick="anularAnteproyectoRevisado(this.form);" />
	</td>
  </tr>
</table>
<input type="hidden" name="registro" id="registro" />
<table width="900" class="tblLista">
  <tr class="trListaHead">
	<th width="100" scope="col"># Presupuesto</th>
	<th scope="col">Elaborado Por</th>
	<th width="135" scope="col">Ejer. Presupuestario</th>
	<th width="100" scope="col">Fecha Inicio</th>	
	<th width="100" scope="col">Fecha Fin</th>
	<th width="85" scope="col">Monto</th>
	<th width="75" scope="col">Estado</th>
  </tr>
 <?php 
   $MAXLIMIT=30;
   $sql="SELECT * 
	     FROM pv_presupuesto 
		WHERE CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' AND Estado='Preparado' 
		ORDER BY CodPresupuesto";
   $qry=mysql_query($sql) or die ($sql.mysql_error());
   $registros=mysql_num_rows($qry);
   if($registros!=0){ 
  	for($i=0; $i<$registros; $i++){
  	   $field=mysql_fetch_array($qry);
      echo "
      <tr class='trListaBody' onclick='mClk(this,\"registro\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['CodAnteproyecto']."'>
	  <td align='center'>".$field['CodAnteproyecto']."</td>
	  <td align='center'>".$field['PreparadoPor']."</td>";
	  list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaInicio']); $fInicio=$d.'-'.$m.'-'.$a;
      list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaFin']); $fFin=$d.'-'.$m.'-'.$a;
      echo"
	  <td align='center'>".$field['EjercicioPpto']."</td>
	  <td align='center'>$fInicio</td>
	  <td align='center'>$fFin</td>
	  <td align='center'>".$field['MontoPresupuestado']."</td>
	  <td align='center'>".$field['Estado']."</td>
      </tr>";
   }}
	$rows=(int)$rows;
	echo "
	<script type='text/javascript' language='javascript'>
		totalAnteproyectos($registros,\"$_INSERT\", \"$_UPDATE\", \"$_DELETE\");
		totalLotes($registros, $rows, ".$limit.");
	</script>
 </table>";	
}else{
  include "presupuesto_aprobar2.php";
  $MAXLIMIT=30;
  include "presupuesto_aprobarcontenido.php";
}
?>
</body>
</html>
