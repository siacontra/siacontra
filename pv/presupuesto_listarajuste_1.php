<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location:index.php");
//	------------------------------------
include("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('01', $concepto);
//	------------------------------------
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
</head>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Listar Ajustes</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />
<?php
include "gmsector.php";
//	VALORES POR DEFECTO Y VALORES SELECCIONADOS DEL FILTRO AL CARGAR O RECARGAR LA PAGINA...............
if($_POST['chkorganismo']=="1"){ /// *** ORGANISMO
   $obj[0]="checked"; $obj[1]="enabled"; $obj[2]=$_POST['forganismo']; 
}else{ 
   $obj[0]="checked"; $obj[1]="enabled"; $obj[2]=$_POST['forganismo']; 
}
if($_POST['chknpresupuesto']=="1"){ /// *** NUMERO DE PRESUPUESTO
  $obj[3]="checked"; $obj[4]=""; $obj[5]=$_POST['fnpresupuesto']; 
}else{ 
  $obj[3]=""; $obj[4]="disabled"; $obj[5]=""; 
}
if($_POST['chknajuste']=="1"){ /// *** NUMERO DE AJUSTE
  $obj[6]="checked"; $obj[7]=""; $obj[9]=$_POST['fnajuste'];
}else{ 
  $obj[6]=""; $obj[7]="disabled"; $obj[9]=""; 
}
if($_POST['chkejercicio']=="1"){ /// *** EJERCICIO PRESUPUESTARIO
  $obj[10]="checked"; $obj[11]="enabled"; $obj[12]=$_POST['fejercicio'];
}else{ 
  $obj[10]="cheked"; $obj[11]="disabled"; $obj[12]=$_POST['fejercicio'];; 
}
if($_POST['chkstatus']=="1"){ /// *** ESTADO DE AJUSTE
  $obj[17]="checked"; $obj[18]=""; $obj[19]=$_POST['fstatus']; 
}else{ 
  $obj[17]=""; $obj[18]="disabled"; $obj[19]="0"; 
}
if($_POST['chkfajuste']=="1"){ /// *** FECHA DE AJUSTE
  $obj[14]="checked"; $obj[15]=""; $obj[16]=$_POST['fajuste']; 
}else{ 
$obj[14]=""; $obj[15]="disabled"; $obj[16]=""; 
}
if($_POST['chktajuste']=="1"){ /// *** TIPO DE AJUSTE
  $obj[20]="checked"; $obj[21]=""; $obj[22]=$_POST['ftajuste']; 
}else{ 
$obj[20]=""; $obj[21]="disabled"; $obj[22]=""; 
}
/////////////////////////////////////////////////////////////////////////////////////
//////////*************** MUESTRO LOS FILTROS ***********////////////////////////////
if(!$_POST){ 
$fecha=date("Y");
$sql="SELECT * FROM pv_ajustepresupuestario,pv_presupuesto 
		      WHERE pv_ajustepresupuestario.Organismo='".$_SESSION['ORGANISMO_ACTUAL']."' AND 
		            pv_presupuesto.EjercicioPpto='$fecha'
		   ORDER BY CodPresupuesto";
    $qry=mysql_query($sql) or die ($sql.mysql_error());
    $rows=mysql_num_rows($qry);//echo"CantidadRows:".$rows; 
   $MAXLIMIT=30;
   include "presupuesto_listarajuste2.php";?>
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
		<!--<input name="btNuevo" type="button" class="btLista" id="btNuevo" value="Nuevo" onclick="cargarPagina(this.form, 'presupuesto_datosgenerales.php');" />
		<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" onclick="cargarOpcion(this.form, 'anteproyecto_listareditar.php?accion=EDITAR', 'SELF');"/>-->
		<input name="btMostrar" type="button" class="btLista" id="btMostrar" value="Nuevo" onclick="cargarPagina(this.form, 'presupuesto_ajustedatosgenerales.php');"/>
		<input name="btVer" type="button" class="btLista" id="btVer" value="Ver" onclick="cargarOpcion(this.form, 'presupuesto_ajustever.php', 'BLANK', 'height=500, width=950, left=200, top=200, resizable=no');" /> 
		<input name="btPDF" type="button" class="btLista" id="btPDF" value="PDF" onclick="cargarOpcion(this.form, 'presupuesto_pdf.php', 'BLANK', 'height=800, width=800, left=200, top=200, resizable=yes');" /> |  
		<!--<input name="btImprimir" type="button" class="btLista" id="btImprimir" value="Imprimir" onclick="abrirAnteproyecto(this.form);" />-->
		<input name="btAnular" type="button" class="btLista" id="btAnular" value="Anular" onclick="anularAnteproyecto(this.form);" />
		<!--<input name="btAjustar" type="button" class="btLista" id="btAjustar" value="Ajustar" onclick="cargarOpcion(this.form,'anteproyecto_ajustar.php?accion=EDITAR','SELF');"/>-->
	</td>
  </tr>
</table>
<input type="hidden" name="registro" id="registro" />
<!--<div style="width:850px; height:15px" class="divFormCaption">Lista de Ajustes</div>-->
<table width="900" class="tblLista">
  <tr class="trListaHead">
   <th width="50" scope="col"># Presupuesto</th>
   <th width="50" scope="col"># Ajuste</th>
   <th width="50" scope="col">T. Ajuste</th>
   <th width="50" scope="col">F. Ajuste</th>
   <th width="50" scope="col">Estado</th>
   <th width="50" scope="col">Total</th>
  </tr>
<? 
   $MAXLIMIT=30;
   $sql="SELECT * 
	     FROM pv_presupuesto 
		WHERE Organismo='".$_SESSION['ORGANISMO_ACTUAL']."' AND  
		      EjercicioPpto='$fecha' 
		ORDER BY CodPresupuesto";
   $qry=mysql_query($sql) or die ($sql.mysql_error());
   $registros=mysql_num_rows($qry);
   if($registros!=0){ 
  	for($i=0; $i<$registros; $i++){
  	   $field=mysql_fetch_array($qry);
      echo "
      <tr class='trListaBody' onclick='mClk(this,\"registro\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['CodPresupuesto']."'>
	  <td align='center'>".$field['CodPresupuesto']."</td>";
	  list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaInicio']); $fInicio=$d.'-'.$m.'-'.$a;
      list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaFin']); $fFin=$d.'-'.$m.'-'.$a;
      echo"
	  <td align='center'>".$field['CodAjuste']."</td>
	  <td align='center'>$tajuste</td>
	  <td align='center'>$fAjuste</td>
	  <td align='center'>".$field['Estado']."</td>
	  <td align='center'>".$field['Total']."</td>
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
  include "presupuesto_listarajuste2.php";
  $MAXLIMIT=30;
  include "presupuesto_ajustecontenido.php";
}
?>
</body>
</html>
