<?php
session_start();

if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location:index.php");
//	------------------------------------
include("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('01', $concepto);
//	------------------------------------


$coBeneficio =$_REQUEST['registro'];
$sql="SELECT *
				FROM rh_beneficiarioutiles where codbeneficiarioutiles = $coBeneficio";
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



?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="../js/AjaxRequest.js"></script>
<script type="text/javascript" language="javascript" src="../js/xCes.js"></script>
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="jsBeneficiosUtiles.js"></script>


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
 <td class="titulo">Beneficio | Editar </td>
 <td align="right"><a class="cerrar"  href="framemain.php";>[Cerrar]</a></td>
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

/*include "gmsector.php";
$sql="SELECT * FROM pv_sector,pv_programa1,pv_subprog1,pv_actividad1,pv_proyecto1 WHERE 1";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0){
 $field=mysql_fetch_array($query);
 list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaDesde']); $fdesde=$d.'/'.$m.'/'.$a;
 list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaHasta']); $fhasta=$d.'/'.$m.'/'.$a;
 $limit=(int) $limit;
}*/

$anho = date('Y');
$sqlUtiles="SELECT nroBeneficioUtiles from rh_beneficiarioutiles where codbeneficiarioutiles = $coBeneficio";
$resul=mysql_query($sqlUtiles);
$reg=mysql_fetch_array($resul);
$nro = $reg['nroBeneficioUtiles'];

?>

<!--////////////////////////// **********DATOS GENERALES DEL PRESUPUESTO*************  ///////////////////////-->   
<div id="tab1" style="display:block;">
<div style="width:800px" class="divFormCaption">Informaci&oacute;n de Beneficio de Utiles </div>
<table width="800" class="tblForm">
<tr>
 <td width="129" align="right">Organismo:</td>
 <td width="634">
   <select name="organismo" id="organismo">
    <?php 
	// segundo bloque php //* Conectamos a los datos *//
	include "conexion.php";
	$sql="SELECT CodOrganismo, Organismo
	        FROM mastorganismos 
	       WHERE CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' ";
	$rs=mysql_query($sql);
	while($reg=mysql_fetch_assoc($rs)){
	$codOrganismo=$reg['CodOrganismo'];// Codigo del orgnismo
	$organismo=$reg['Organismo'];// Descripcion del Organismo
	   echo "<option value=$codOrganismo>$organismo</option>";
	}
	?></select></td>
 <td width="21" colspan="2"><input type="hidden" name="codBeneficio" id="codBeneficio" value="<?=$coBeneficio;?>" /></td>
</tr>
</table>
<table width="800" class="tblForm">
<tr><td width="123" height="2"></td></tr>
<tr>
 <td colspan="2" align="right">Nro. Orden:</td>
 <td width="168"><input name="txt_nro_orden" type="text" id="txt_nro_orden" value="<?= $nro ?>" size="8" readonly/>*</td>
 <td width="91" align="right">&nbsp;</td>
 <td width="129">&nbsp;</td>
 <td width="264" colspan="2" rowspan="6" valign="top"><!--
 <table width="100%" border="0" cellpadding="0" cellspacing="0" class="tblLista">
   
   <tr>
     <td colspan="3" align="center" class="trListaHead">Detalle</td>
     </tr>
   <tr>
     <td width="8%">&nbsp;</td>
     <td width="66%">Disponible</td>
     <td width="26%" align="right" id="td_disponible" style="font-weight:bold">0,00</td>
   </tr>
   <tr>
     <td>&nbsp;</td>
     <td>Consumido</td>
     <td id="td_consumido" align="right" style="font-weight:bold">       0,00</td>
   </tr>
   <tr>
     <td>&nbsp;</td>
     <td>Disponible x hijo.</td>
     <td id="td_disponible_serv" align="right" style="font-weight:bold">0,00</td>
   </tr>
   <tr>
     <td>&nbsp;</td>
     <td>Consumideo x hijo.</td>
     <td id="td_consumido_serv" align="right" style="font-weight:bold">0,00</td>
   </tr>
 </table>-->
 </td>
</tr>


<tr>
  <td colspan="2" align="right">Periodo:</td>
  <td colspan="3">
  
  <?php
  	$sql="select montoasignado,periodoutiles from rh_utilesayuda";
	$rs=mysql_query($sql);
			  
  ?>
  <select name="fperiodo" id="fperiodo" style="width:100px;" onchange="asignarMontoPeriodo()" > 

				<option value="0">..</option>
				<?php					
					while($reg=mysql_fetch_assoc($rs))
					{
					
						echo '<option value="'.$reg['periodoutiles'].'#'.$reg['montoasignado'].'">'.$reg['periodoutiles'].'</option>';
					}				
				?>

	</select>
	
  </td>
</tr>

<tr>
  <td colspan="2" align="right">Proveedor:</td>
  <td colspan="3">
  
  <?php
  	$sql_proveedor="SELECT *
		FROM `mastpersonas` AS a, mastproveedores AS b
		WHERE a.TipoPersona = 'J'
		AND a.CodPersona = b.CodProveedor";
	$rs_pr=mysql_query($sql_proveedor);
			  
  ?>
  <select name="sel_proveedor" id="sel_proveedor" style="width:200px;" onchange="" > 

				<option value="0">..</option>
				<?php					
					while($reg=mysql_fetch_assoc($rs_pr))
					{
					
						echo '<option value="'.$reg['CodPersona'].'">'.$reg['NomCompleto'].'</option>';
					}				
				?>

	</select>
	
  </td>
</tr>

<tr>
  <td colspan="2" align="right">Funcionario:</td>
  <td colspan="3"> 
  		<form id="frmentrada" name="frmentrada" action="#">
            <input name="codempleado" type="hidden" id="codempleado" value="" />
           <input name="nomempleado" id="nomempleado" type="text" size="50" readonly/>
           <!--<input name="bt_examinar" id="bt_examinar" type="button" value="..." onclick="cargarVentana(this.form, 'lista_empleados.php?limit=0&campo=5', 'height=500, width=800, left=200, top=200, resizable=yes');" />*                                        -->
      </form></td>
  </tr>
<tr>
 <td colspan="2" align="right">Hijo : </td>
 <td colspan="3"><select name="sel_familia_funcionario" id="sel_familia_funcionario"> 
 </select> *</td>
 </tr>

<tr>
 <td colspan="2" align="right">Monto :</td>
 <td colspan="2" align="left">
  
<input name="txt_monto" type="text" id="txt_monto" size="12" maxlength="10" style="text-align:right" value="0,00" onkeypress="return(formatoMoneda(this,'.',',',event));"  />   
* 
<input name="bt_agregar" type="button" id="bt_agregar" value="Agregar" onclick="agregarItem()"/>
</td>
 <td align="left">&nbsp;</td>
 </tr>

<tr>
  <td colspan="2"></td>
  <td colspan="2">&nbsp;</td>
  <td>&nbsp;</td>
  </tr>
</table>


<div style="width:800px" class="divFormCaption">Hijos Agregados</div>
<table width="800" class="tblForm" style="text-align:center">

<tr class="trListaHead">	
	<td width="20" align="left">Nº</td>	
	<td width="20" align="left">Cod</td>	
	<td width="400" align="left">Nombre y Apellido</td>
	<td width="160" align="right">Monto</td>	
	<td width="20">&nbsp;</td>
</tr>
<tr valign="top">	
	<td colspan="5" height="70" id="tablaItem">No existe movimiento</td>	
</tr>

</table>

<table width="800" class="tblForm">
<tr>
 <!--<td width="127" align="right">Ejercicio P.:</td>		
 <td width="661"><? //$ano = date('Y'); // devuelve el año
      // $fcreacion= date("d-m-Y");//Fecha de Creación ?>
   <input title="A&ntilde;o de Presupuesto" name="ejercicioPpto" type="text" readonly="yes" value="<?PHP //echo $ano;?>" style="text-align:right" id="ejercicioPpto" size="3" maxlength="4" />* 
   F.Creaci&oacute;n:<input name="fcreacion" type="text" id="fcreacion" size="8" value="<?PHP //echo $fcreacion;?>" readonly /> 
   Estado:<input name="estado" type="text" id="estado" size="11" value="Preparado" readonly/>	<input name="co_estado" type="hidden" id="co_estado" size="11" value="PE" readonly/>	</td>
</tr>-->


<tr>
  <td align="right">&nbsp;</td>
  <td>&nbsp;</td>
</tr>
</table>


<!---////////////////////////////////////////////////////////////////////////////////////////////////-->
<!---/////////////////// ********     *******   /////////////////////-->
<!---  TABLA 2 ------>
<div style="width:800px" class="divFormCaption">Control</div>
<table width="800" class="tblForm">
<tr><td></td></tr>

<tr><!--<td>&nbsp;</td></tr>
<tr>
  <td>&nbsp;</td>
  <td class="tagForm">Preparado por:</td><? $sql3=mysql_query("SELECT * FROM usuarios WHERE Usuario='".$_SESSION['USUARIO_ACTUAL']."'");
											 if(mysql_num_rows($sql3)!=0){
											   $field3=mysql_fetch_array($sql3);
											   $sql4=mysql_query("SELECT * FROM mastpersonas WHERE CodPersona='".$field3['CodPersona']."'");
											   if(mysql_num_rows($sql4)!=0){
												 $field4=mysql_fetch_array($sql4);
											   }
											 }
										  ?>
   <td><input name="prepor" id="prepor" type="text" size="60" value="<?=$field4['NomCompleto']?>" readonly/>
    	<input name="codprepor" type="hidden" id="codprepor" value="<?=$_SESSION['USUARIO_ACTUAL'];?>" /></td>
<tr>
<tr><td></td>
   <td class="tagForm">Aprobado por:</td>
   		
   <td>
      	<input name="nomempleadoA" id="nomempleadoA" type="text" size="60" value="" readonly/>
    	<input name="codempleadoA" type="hidden" id="codempleadoA" value=""/>
  </td>-->
     
</tr>
<tr><td></td>
	<?
	   echo"<td class='tagForm'>&Uacute;ltima Modif.:</td>
		   <td coslpan='1'>
			 <input name='ult_usuario' type='text' id='ult_usuario' size='30' readonly value= '' />
			 <input name='ult_fecha' type='text' id='ult_fecha' size='22' readonly value= '' />
		   </td>";
   ?>
</tr>
</table>
<center>
<input name="btguardar" type="submit" id="btguardar" value="Editar Registro" onclick="validarCampoBeneficio('EDITAR')"/>
<input name="btcancelar" type="button" id="btcancelar" value="Cancelar" onclick="javascritp:location.href='framemain.php';"/>
</center></div>

<div style="width:700px" class="divMsj">Campos Obligatorios *</div>

<?php 
echo "<script>
       cargarDatosBeneficio($coBeneficio,'');
      </script>"
?>
</body>
</html>
