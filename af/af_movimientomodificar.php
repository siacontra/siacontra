<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include ("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('03', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript.js"></script>
<style type="text/css">
<!--
UNKNOWN {FONT-SIZE: small}
#header {FONT-SIZE: 93%; BACKGROUND: url(imagenes/bg.gif) #dae0d2 repeat-x 50% bottom; FLOAT: left; WIDTH: 100%; LINE-HEIGHT: normal}
#header UL {PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 10px; LIST-STYLE-TYPE: none}
#header LI {
        PADDING-RIGHT: 0px; PADDING-LEFT: 9px; BACKGROUND: url(imagenes/left.gif) no-repeat left top; FLOAT: left; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 0px}
#header A {
        PADDING-RIGHT: 15px; DISPLAY: block; PADDING-LEFT: 6px; FONT-WEIGHT: bold; BACKGROUND: url(imagenes/right.gif) no-repeat right top; FLOAT: left; PADDING-BOTTOM: 4px; COLOR: #765; PADDING-TOP: 5px; TEXT-DECORATION: none}
#header A { FLOAT: none}
#header A:hover {  COLOR: #333 }
#header #current { BACKGROUND-IMAGE: url(imagenes/left_on.gif)}
#header #current A { BACKGROUND-IMAGE: url(imagenes/right_on.gif); PADDING-BOTTOM: 5px; COLOR: #333 }
-->
</style>
</head>
<body>
<?
list($organismo,$num_movimiento)=SPLIT('[|]',$_GET['registro']);
//// CONSULTA PRINCIPAL
///echo $num_orden,$num_movimiento;
$sql = "select * from af_movimientos where Organismo = '$organismo' and MovimientoNumero = '$num_movimiento'";
$qry = mysql_query($sql) or die ($sql.mysql_error());
$row = mysql_num_rows($qry);
$field = mysql_fetch_array($qry);






/*$sa = "select * from lg_activofijo where NroOrden = '$num_orden' and Secuencia='$secuencia'";
$qa = mysql_query($sa) or die ($sa.mysql_error());
$ra = mysql_num_rows($qa); //echo $ra;*/

if($ra!='0'){$fa=mysql_fetch_array($qa);}
?>

<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Movimientos de Activos | Modificar</td>
		<td align="right"><a class="cerrar" onclick="window.close();">[cerrar]</a></td>
	</tr>
</table>
<hr width="100%" color="#333333" />
<? 

/// Consulta Usuario Actual
$s_con = "select
               mp.NomCompleto,
			   mp.CodPersona
		   from 
			   usuarios u
			   inner join mastpersonas mp on (u.CodPersona=mp.CodPersona)
		  where
		       Usuario = '".$_SESSION['USUARIO_ACTUAL']."'";
$q_con = mysql_query($s_con) or die ($s_con.mysql_error());
$f_con = mysql_fetch_array($q_con);
?>

<form id="frmentrada" name="frmentrada" action="af_movimientoactivonuevo.php" method="POST" onsubmit="return guardarNuevoMovimientoActivo(this)">
<table class='tblForm' width='1000' height='50'>
<tr>
   <td>
   <table>
   <tr>
       <td width='5'></td>
       <td align='right'>Organismo:</td>
       <td align='left'><select name='fOrganismo' id='fOrganismo' class='selectBig' $dOrganismo>
                          <? getOrganismos($_SESSION['ORGANISMO_ACTUAL'],3);?>
                        </select>
       </td>
	   <td width='5'></td>
       <td align='right'>Movimiento #</td>
	   <td><input type='text' id='fmovimiento' name='fmovimiento' value='' disabled/></td>
	   <td width='5'>Comentario:</td>
       <td><input type='text' id='comentario' name='comentario' size='50'/></td>
	   <!--<td align='right'>Estado:</td>
       <td><input type="text" id="estado" name="estado" value="En Preparaci&oacute;n" size="20" disabled/></td>-->	   
   </tr>
   <tr>
     <td width='5'></td>
     <td align="right">Preparado Por:</td>
	 <td><input type="hidden" id="preparado_por" name="preparado_por" value="<?=$f_con['1'];?>" /><input type='text' id='pre_por' name='pre_por' value="<?=$f_con[0];?>" size='40' readonly/>
	     <input type='text' id='fecha_prepa' name='fecha_prepa' value="<?=date("d-m-Y");?>" size='8' readonly/></td>
	 <td width='5'></td>
	 <td>Tipo Movimiento:</td>
	 <td><input type="hidden" name="radioEstado" id="radioEstado" value="I"/><input type='radio' id='radio1' name='radio1' onclick="estadosPosee02(this.form)|selectMotMovimiento(this.form);" checked/>Interno
	     <input type='radio' id='radio2' name='radio2' onclick="estadosPosee02(this.form)|selectMotMovimiento(this.form);"/>Externo</td>
    <td></td>
    <!--<td align="right">Motivo Traslado:</td>
    <td><input type="hidden" id="m_traslado" name="m_traslado" value=""/><input type="text" id="motivo_traslado" name="btMotivo" size="20"/><input type="button" id="btMotivo" name="cargar" value="..."/></td>-->
   </tr>
    
   <tr>
     <td width='5'></td>
     <td align="right">Aprobado Por:</td>
	 <td><input type='text' id='apro_por' name='apro_por' value="" size="40"/>
	     <input type='text' id='fecha_apro' name='fecha_apro' value='' size='8' readonly/></td>
	 <td width='5'></td>
	 <td align='right'>Motivo Traslado:</td>
	 <td align='left' colspan='2'><select id="motivoTrasladoInterno" name="motivoTrasladoInterno" class="selectMed" style="display:block;">
                                  <option value=""></option>
                                  <?
                                    $s_movint = "select * from mastmiscelaneosdet where CodMaestro='MMOVINTER'";
									$q_movint = mysql_query($s_movint) or die ($s_movint.mysql_error());
									$r_movint = mysql_num_rows($q_movint);
									
									for($i=0; $i<$r_movint; $i++){
										$f_movint = mysql_fetch_array($q_movint);
										echo"<option value='".$f_movint['CodDetalle']."'>".$f_movint['Descripcion']."</option>";
									}
									
								  ?>
                                  </select>
                                  <select id="motivoTrasladoExterno" name="motivoTrasladoExterno" class="selectMed" style="display:none;">
                                  <option value=""></option>
                                  <?
                                    $s_movext = "select * from mastmiscelaneosdet where CodMaestro='MMOVEXTER'";
									$q_movext = mysql_query($s_movext) or die ($s_movext.mysql_error());
									$r_movext = mysql_num_rows($q_movext);
									
									for($i=0;$i<$r_movext; $i++){
										$f_movext = mysql_fetch_array($q_movext);
										echo"<option value='".$f_movext['CodDetalle']."'>".$f_movext['Descripcion']."</option>";
									}
								  ?>
                                  </select></td>
   </tr>	
	
   </table>
   </td>
</tr>
<tr><td height='5'></td></tr>
<tr>
 <td>
 <table>
 <tr>
   <td>
      <input type="button" id="btInsertar" name="Insertar" value="Insertar"/><input type="button" id="btEliminar" name="btEliminar" value="Eliminar"/></td>
 <td>Seleccionar <input type="button" id="btactivo" name="btactivo" value="Activo" onclick="cargarVentanaLista(this.form, 'af_selectoractivos.php?limit=0&campo=1','height=500, width=870, left=200, top=100, resizable=yes');"/><input type="button" id="centro_costos" name="centro_costos" value="Centro de Costos" onclick="cargarVentanaLista(this.form, 'af_listacentroscostos.php?limit=0&campo=10','height=500, width=870, left=200, top=100, resizable=yes');"/><input type="button" id="btubicacion" name="btubicacion" value="Ubicaci&oacute;n" onclick="cargarVentanaLista(this.form, 'af_listaubicacionesactivo?limit=0&campo=11','height=500, width=870, left=200, top=100, resizable=yes');"/><input type="button" id="btDependencia" name="btDependencia" value="Dependencia" onclick="cargarVentanaLista(this.form, 'af_listadependencias.php?limit=0&campo=14','height=500, width=870, left=200, top=100, resizable=yes');"/> <input type="button" id="btEmpleadoUsuario" name="btEmpleadoUsuario" value="Empl. Usuario" onclick="cargarVentanaLista(this.form, 'af_listaempleados.php?limit=0&campo=12', 'height=500, width=870, left=200, top=100, resizable=yes');"/><input type="button" id="btEmpleadoResponsable" name="btEmpleadoResponsable" value="Empl. Respons." onclick="cargarVentanaLista(this.form, 'af_listaempleados.php?limit=0&campo=13', 'width=870, height=500, top=100, left=200, resizable=yes');"/></td>
 </tr>
</table>
</td>
</tr>
<tr>
  <td align='center'>Ultima Modif.:<input type='text' id='ultimo_usuario' name='ultimo_usuario' size='40'/><input type='text' id=''/></td>
</tr>
</table>


<table width="1005" align="center">
<tr>
  <td>
	<div id="header">
	<ul>
    <li><a onClick="document.getElementById('tab1').style.display='block';;" href="#">Movimientos</a></li>
	<!-- CSS Tabs PESTA�AS OPCIONES -->
    <!--
	<li><a onClick="document.getElementById('tab1').style.display='block'; document.getElementById('tab2').style.display='none';" href="#">Movimientos</a></li>
	<li><a onClick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='block';" href="#">Errores de Importaci&oacute;n</a></li> 
    -->
	</ul>
	</div>
  </td>
</tr>
</table>
<!-- ****************************************************** COMIENZO TAB1 ************************************************ -->
<div id="tab1" style="display: BLOCK;">
<div style="width:1000px; height=15px;" class="divFormCaption"></div>
<table class="tblForm" width="1000">
<tr>
 <td colspan="4" align="center">
 <table width="980" border="1" bgcolor="#CCCCCC" align="center">
 <tr>
   <td width="91" align="center">#</td><td width="256" align="center">Activo</td><td width="617" align="center">Descripci&oacute;n</td>
 </tr>
 <tr>
    <td colspan="4">
  <table width="980" border="1" bordercolor="#666666" bordercolordark="#000099">
  <tr>
    <td colspan="2" width="490" align="center">Dato Anterior</td><td width="490" colspan="2" align="center">Dato Actual</td>
  </tr>
  </table>
</td>
</tr>
 </table>
 </td>
</tr>
<tr>
<td colspan="4"><input type="text" name="nro_movimientos" id="nro_movimientos" style="border-color:#666; text-align:right" size="18" value="1"/><input type="text" id="activo" name="activo" style="border-color:#666; text-align:right" size="60" value=''/><input type="text" id="descripcion" name="descripcion" style="border-color:#666" size="88"/> Cod.Bar. <input type="text" id="cod_bar" name="cod_bar" size="37" style="border-color:#666; text-align:right" disabled/></td>
</tr>
<tr>
 <td width="98">Centro Costos</td><td colspan="3"><input type="text" id="c_costos" name="c_costos" size="35" disabled/><input type="text" id="c_costos2" name="c_costos2" size="60" disabled/><input type="text" id="c_costosActual" name="c_costosActual" size="35"/><input type="text" id="c_costosActual2" name="c_costosActual2" size="60"/></td>
</tr>
<tr>
 <td>Ubicaci&oacute;n</td><td colspan="3"><input type="text" id="ubicacion" name="ubicacion" size="35" disabled/><input type="text" id="ubicacion2" name="ubicacion2" size="60" disabled/><input type="text" id="ubicacion_Actual" name="ubicacion_Actual" size="35"/><input type="text" id="ubicacion_Actual2" name="ubicacion_Actual2" size="60"/></td>
</tr>
<tr>
 <td>Dependencia</td><td colspan="3"><input type="text" id="dependencia" name="dependencia" size="35" disabled/><input type="text" id="dependencia2" name="dependencia2" size="60" disabled/><input type="text" id="dependenciaActual" name="dependenciaActual" value="" size="35"/><input type="text" id="dependenciaActual2" name="dependenciaActual2" size="60"/></td>
</tr>
<tr>
 <td>Empl. Usuario</td><td colspan="3"><input type="text" id="e_usuario" name="e_usuario" size="35" disabled/><input type="text" id="e_usuario2" name="e_usuario2" size="60" disabled/><input type="text" id="e_usuarioActual" name="e_usuarioActual" size="35"/><input type="text" id="e_usuarioActual2" name="e_usuarioActual2" size="60"/></td>
</tr>
<tr>
 <td>Empl. Respons.</td><td colspan="3"><input type="text" id="e_responsable" name="e_responsable" size="35" disabled/><input type="text" id="e_responsable2" name="e_responsable2" size="60" disabled/><input type="text" id="e_responsableActual" name="e_responsableActual" size="35"/><input type="text" id="e_responsableActual2" name="e_responsableActual2" size="60"/></td>
</tr>
<tr>
 <td>Organismo</td><td colspan="3"><input type="text" id="organismo" name="organismo" size="35" disabled/><input type="text" id="organismo2" name="organismo2" size="60" disabled/><select id="organismoActual" name="organismoActual" class="selectMed">
                                 <option value=""></option>
                                 <?
                                 $s_organismo = "select * from mastorganismos";
								 $q_organismo = mysql_query($s_organismo) or die ($s_organismo.mysql_error());
								 
								 while($f_organismo = mysql_fetch_array($q_organismo)){
								   echo"<option value='".$f_organismo['CodOrganismo']."'>".$f_organismo['CodOrganismo']."</option>";
								 }
								 ?>
                                 </select><input type="text" id="organismoActual2" name="organismoActual2" size="60"/></td>
</tr>
</table>
</div>
<!-- ****************************************************** COMIENZO TAB2 ************************************************ -->
<center>
 <input type="submit" id="guardar" name="guardar" value="Guardar Registro"/>
 <input type="button" id="cancelar" name="cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'af_movimientoactivos.php');" />
</center>
</form>
<div style="width:850px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>