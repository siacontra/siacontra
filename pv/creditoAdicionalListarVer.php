<?php
session_start();

if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location:index.php");
//	------------------------------------


include("fphp.php");
include("../clases/MySQL.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('01', $concepto);




$coCreditoAdicional =$_REQUEST['registro'];
$sql="SELECT *
			   FROM `pv_credito_adicional`
			   WHERE `co_credito_adicional` = $coCreditoAdicional";
$qry=mysql_query($sql) or die ($sql.mysql_error());
$field=mysql_fetch_array($qry);
if(mysql_num_rows($qry)!=0){
 if(($field[tx_estatus]=='Aprobado')or($field[tx_estatus]=='Generado')){
   echo"<script>
        alert('NO PUEDE SER EDITADO');
        history.back(-1);
      </script>";
 }
}





/*






$sqlGeneral = "SELECT *
			   FROM `pv_credito_adicional`
			   WHERE `co_credito_adicional` = $coCreditoAdicional";
					
$queryG=mysql_query($sqlGeneral) or die ($sqlGeneral.mysql_error());
//$rows=mysql_num_rows($query);					
$resultadoCompleto=mysql_fetch_array($queryG);

*/



//	------------------------------------


$year_actual = date("Y"); //echo $year_actual;
//$year_actual = "2012";
///// *****************************************************
$SQL2="SELECT 
            CodPresupuesto,Organismo
	    FROM 
		    pv_presupuesto 
	   WHERE 
	        EjercicioPpto = '$year_actual' AND
			Estado='AP'";
$ejec=mysql_query($SQL2) or die ($SQL2.mysql_error());
$proy=mysql_fetch_array($ejec); //echo $FIELD[CodPresupuesto];
///// ******************************************************

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>


<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />




<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="../js/AjaxRequest.js"></script>
<script type="text/javascript" language="javascript" src="../js/xCes.js"></script>
<script type="text/javascript" language="javascript" src="fscript03.js"></script>
<script type="text/javascript" language="javascript" src="fscriptpresupuesto.js"></script>
<script type="text/javascript" language="javascript" src="js/jsCreditoAdicional.js"></script>

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





/*-------------------------------------------------------------------------------------------------*/
</script>

<?php
	echo '<script type="text/javascript">';
	echo 'cargarDatosCreditoAdicional('.$coCreditoAdicional.',"VER");';
	echo '</script>';
?>

<style type="text/css">
<!--
UNKNOWN {FONT-SIZE: small}
#header {FONT-SIZE: 93%; BACKGROUND: url(imagenes/bg.gif) #dae0d2 repeat-x 50% bottom; FLOAT: left; WIDTH: 100%; LINE-HEIGHT: normal}
#header UL {PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 10px; LIST-STYLE-TYPE: none}
#header LI {
        PADDING-RIGHT: 0px; PADDING-LEFT: 9px; BACKGROUND: url(imagenes/left.gif) no-repeat left top; FLOAT: left; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 0px}
#header A {PADDING-RIGHT: 15px; DISPLAY: block; PADDING-LEFT: 6px; FONT-WEIGHT: bold; BACKGROUND: url(imagenes/right.gif) no-repeat right top; FLOAT: left; PADDING-BOTTOM: 4px; COLOR: #765; PADDING-TOP: 5px; TEXT-DECORATION: none}
#header A {FLOAT: none}
#header A:hover {COLOR: #333}
#header #current {BACKGROUND-IMAGE: url(imagenes/left_on.gif)}
#header #current A {BACKGROUND-IMAGE: url(imagenes/right_on.gif); PADDING-BOTTOM: 5px; COLOR: #333}
-->
</style>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
<tr>
 <td class="titulo">Credito Adicional | Ver</td>
 <td align="right"><a class="cerrar"  href="#"; onclick="javascript:window.close()">[Cerrar]</a></td>
</tr>
</table><hr width="100%" color="#333333" />
<?
function validaFormatoFecha($fecha){
  $A=$fecha.date("Y");
  $D=$fecha.date("d");
  $M=$fecha.date("m");
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

<?php
$limit=(int) $limit;
echo "
<input type='hidden' name='codantepres' id='codantepres' value='".$codantepres."'/>
<input type='hidden' name='filtro' id='filtro' value='".$filtro."' />
<input type='hidden' name='regresar' id='regresar' value='".$regresar."' />
<input type='hidden' name='forganismo' id='forganismo' value='".$forganismo."' />
<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />";
//echo"Regresar= ".$regresar;

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
<div style="width:800px" class="divFormCaption">Informaci&oacute;n del Crédito Adicional</div>
<table width="800" class="tblForm">
<tr>
 <td width="123"></td>
 <td width="151" class="tagForm">Organismo:</td>
 <td width="342">
	<select name="organismo" id="organismo" disabled="disabled">
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
	?></select></td>
 <td colspan="2"></td>
</tr>
</table>
<table width="800" class="tblForm">
<tr><td height="2"></td></tr>
<tr>
 <td width="164"></td>
 <td width="112" align="right">Nro. Oficio:</td>
 <td width="80"><input name="gaceta" id="gaceta" type="text" size="8" readonly="readonly"/>*</td>
 <td width="88" align="right">F. Oficio:</td>
 <td width="150"><input name="fgaceta" id="fgaceta" type="text" size="8" maxlength="10" onchange="validaFormatoFecha()" readonly="readonly"/>*<i>(dd-mm-aaaa)</i></td>
 <td colspan="2" width="178"></td>
</tr>
<tr>
 <td width="164"></td>
 <td width="112" align="right">Nro. Decreto:</td>
 <td width="80"><input name="decreto" id="decreto" type="text" size="8" readonly/>*</td>
 <td width="88" align="right">F. Decreto:</td>
 <td width="150"><input name="fdecreto" id="fdecreto" type="text" size="8" maxlength="10" onchange="validaFormatoFecha()" readonly="readonly"/>*<i>(dd-mm-aaaa)</i></td>
 <td colspan="2" width="178"></td>
</tr>

<tr>
  <td></td>
  <td align="right">Monto:</td>
  <td colspan="2"><input type="text" name="txt_monto" id="txt_monto"  readonly="readonly" style="text-align:right"/></td>
  <td>&nbsp;</td>
  <td colspan="2"></td>
</tr>
<tr>
 <td width="164"></td>
 <td width="112" align="left">Motivo :</td>
 <td colspan="3"></td>
 <td colspan="2" width="178"></td>
</tr>

<tr>
 <td width="164"></td>
 <td align="left" colspan="4"><textarea name="txt_motivo" id="txt_motivo" cols="50" rows="5" style="width:100%; resize: none;" readonly="readonly" onKeyDown="contador('txt_motivo',1000)" ></textarea></td>
 <td align="left"><input type="hidden" name="hid_codigo" id="hid_codigo" value="<?php echo $coCreditoAdicional; ?>" /></td>
 </tr>

<tr>
 <td width="164"></td>
 <td width="112" align="right" ></td>
 <td width="80">&nbsp;</td>
 <td width="88" align="right"></td>
 <td width="150">&nbsp;</td>
 <td colspan="2" width="178"></td>
</tr>


</table>
<table width="800" class="tblForm">
<tr>
 <td width="163" align="right"></td>		
 <td class="tagForm">Ejercicio P.:</td>
 <td><? $ano = date('Y'); // devuelve el año
       $fcreacion= date("d-m-Y");//Fecha de Creación ?>
             
	<input readonly="true" title="A&ntilde;o de Presupuesto" name="ejercicioPpto" type="text" value="<?PHP echo $ano;?>" style="text-align:right" id="ejercicioPpto" size="8" maxlength="8" />*
	Nro Presupuesto: <input readonly="true" title="Nro Presupuesto" name="txt_nro_presupuesto" type="text" value="<?=$proy['CodPresupuesto']?>" style="text-align:right"  id="txt_nro_presupuesto" size="8" maxlength="8" />* 
	F.Creaci&oacute;n:<input name="fcreacion" type="text" id="fcreacion" size="8" value="<?PHP echo $fcreacion;?>" readonly /> 
	 Estado:<input name="estado" type="text" id="estado" size="11" value="Preparado" readonly/>	<input name="co_estado" type="hidden" id="co_estado" size="11" value="PE" readonly/>	</td>
</tr>
</table>
<!---////////////////////////////////////////////////////////////////////////////////////////////////-->
<!---/////////////////// ********   CARGAR SELECT SECTOR PRESUPUESTO  *******   /////////////////////-->
 <table width="800" class="tblForm" border="0px">
	<tr>
	  <td width="275" align="left">&nbsp;</td>   
	  <!--      
	  <td width="45" align="left">Sec</td>
	  <td width="45" align="left">Pro</td>
      <td width="45" align="left">Spro</td>
	  <td width="45" align="left">Proy</td>
	  <td width="45" align="left">Act</td>
	  -->
      <td width="45" align="left">Par</td>
      <td width="45" align="left">Ge</td>
	  <td width="45" align="left">Esp</td>
	  <td width="45" align="left">Sesp</td>
      <td width="45" align="left">Ord</td>
      <td width="75" align="left">&nbsp;</td>
	  <td width="75" align="right">Monto</td>
	  <td width="150" align="left">&nbsp;</td>              
	</tr>
    
    <tr>
	  <td width="275">&nbsp;</td>
	  <!--
	  <td width="45"><input name="txt_sector" id="txt_sector" type="text"  onkeyup="buscarSector();validaSoloNumeros(id)"  size="3" maxlength="2"  /></td>
	  <td width="45"><input name="txt_programa" id="txt_programa" type="text" onkeyup="buscarPrograma();validaSoloNumeros(id)"  size="3" maxlength="2" /></td>
      <td width="45"><input name="txt_sub_programa" id="txt_sub_programa" type="text" onkeyup="buscarSubPrograma();validaSoloNumeros(id)"  size="3" maxlength="2" /></td>
	  <td width="45"><input name="txt_proyecto" id="txt_proyecto" type="text" onkeyup="buscarProyecto();validaSoloNumeros(id)"  size="3" maxlength="2" /></td>
	  <td width="45"><input name="txt_actividad" id="txt_actividad" type="text" onkeyup="buscarActividad();validaSoloNumeros(id)"  size="3" maxlength="3" /></td>
	 -->
      <td width="45"><input name="txt_partida" id="txt_partida" type="text" readonly="" onkeyup="campoSiguiente(event,'txt_partida','txt_general','txt_actividad');validaSoloNumeros(id)"  size="3" maxlength="3" /></td>
      <td width="45"><input name="txt_general" id="txt_general" type="text" readonly="" onkeyup="campoSiguiente(event,'txt_general','txt_especifico','txt_partida');validaSoloNumeros(id)" size="3" maxlength="2" /></td>
	  <td width="45"><input name="txt_especifico" id="txt_especifico" type="text" readonly="" onkeyup="campoSiguiente(event,'txt_especifico','txt_sub_especifico','txt_general');validaSoloNumeros(id)" size="3" maxlength="2" /></td>
	  <td width="45"><input name="txt_sub_especifico" id="txt_sub_especifico" readonly="" type="text" onkeyup="campoSiguiente(event,'txt_sub_especifico','txt_ordinal','txt_especifico');validaSoloNumeros(id)" size="3" maxlength="2" /></td>
      <td width="45"><input name="txt_ordinal" id="txt_ordinal" type="text" readonly="" onkeyup="campoSiguiente(event,'txt_ordinal','txt_monto_item','txt_sub_especifico');validaSoloNumeros(id)" size="3" maxlength="3" /></td>
      <td width="75">&nbsp;</td>
	  <td width="75"><input name="txt_monto_item" readonly="" id="txt_monto_item" type="text" value="0,00" onChange="" size="auto" maxlength="*" style="text-align:right" onkeypress="return(formatoMoneda(this,'.',',',event));" 	onkeyup="agregarItemEnter(event)" /></td>
	  <td width="150">&nbsp;</td>                       
	</tr>
    
    <tr>
	  <td width="50" align="right"><h2>Descripci&oacute;n:</h2></td>         
	  <td  align="left" colspan="13" >
	  	<h3>
	  		<div id="td_descripcion_partida" ></div>
	  	</h3>
	  </td>
         
	</tr>
     <tr>
	  <td width="50" align="left">&nbsp;</td>         
	  <td  align="left" colspan="13">&nbsp;</td>
         
	</tr>
    
     <tr>
	      
	  <td width="45" align="left" colspan="14">
      		<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="tblLista">
					<tr class="divFormCaption"  style="background:#999;">
							<td width="5%" align="center">N&deg;</td>
							<td width="25%" align="left">C&oacute;digo Presupuestario </td>
							<td width="50%" align="left">Decripci&oacute;n</td>
							<td width="15%" align='right'>Monto</td>
							<td width="5%" align="center">&nbsp;</td>
					</tr>
					<tr>
							<td colspan="5" align="center">
                            	<div id="tablaItem" style="width:100%; height:150px; overflow:scroll; overflow-x:hidden "> <br /><label>No existe movimientos agregado</label></div>
                            </td>
					</tr>
					<tr>
							<td colspan="3" align="right"><label style="font-weight:bold; font-size:14px">Total (Bs.):</label></td>
							<td align="right" ><label id="totalMonto"  style="font-weight:bold; font-size:14px">0,00</label></td>
							<td align="right" >&nbsp;</td>
					</tr>
			</table>
      </td>	 
	              
	</tr>
	
	</table>
<!---  TABLA 2 ------>
<div style="width:800px" class="divFormCaption">Monto de Presupuesto</div>
<form id="frmentrada" name="frmentrada" action="#">
<table width="800" class="tblForm">
<tr><td></td></tr>

<tr><td>&nbsp;</td></tr>
<tr>
  <td>&nbsp;</td>
  <td class="tagForm">Preparado por:</td>
   <td><input name="prepor" id="prepor" type="text" size="30" value="" readonly/>
	 <input name='fecha_preperada' type='text' id='fecha_preperada' size='22' readonly />  
    	<input name="codprepor" type="hidden" id="codprepor" value="" /></td>
</tr>


<tr>
	<td></td>
   <td class="tagForm">Revisada por:</td>
   <td> 	      
          
           <input name="revisado" id="revisado" type="text" size="30" readonly/>
 	   <input name='fecha_revisada' type='text' id='fecha_revisada' size='22' readonly />                      
  </td>
     
</tr>

<tr>
	<td></td>
   <td class="tagForm">Confirmada por:</td>
   <td> 	      
          
           <input name="conformado" id="conformado" type="text" size="30" readonly/>
	    <input name='fecha_conformada' type='text' id='fecha_conformada' size='22' readonly />             
  </td>
     
</tr>
<tr>
	<td></td>
   <td class="tagForm">Aprobada por:</td>
   <td> 	      
           <input name="codempleado" type="hidden" id="codempleado" value="" />
           <input name="nomempleado" id="nomempleado" type="text" size="30" readonly/>    
	   <input name='fecha_aprobada' type='text' id='fecha_aprobada' size='22' readonly />                
  </td>
     
</tr>
<tr><td></td>
	<? $ahora=date("Y-m-d H:m:s");
	   echo"<td class='tagForm'>&Uacute;ltima Modif.:</td>
		   <td coslpan='1'>
			 <input name='ult_usuario' type='text' id='ult_usuario' size='30' readonly />
			 <input name='ult_fecha' type='text' id='ult_fecha' size='22' readonly />
		   </td>";
   ?>
</tr>
</table>
 </form>
<center>

<input name="btcancelar" type="button" id="btcancelar" value="Cancelar" onclick="javascritp:window.close()"/>
</center></div>

</body>
</html>
</SCRIPT>>
