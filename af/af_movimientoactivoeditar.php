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
<!--<link href="css1.css" rel="stylesheet" type="text/css" />-->
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript.js"></script>
<script type="text/javascript" src="../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/fscript.js" charset="utf-8"></script>
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
list($organismo,$num_movimiento)=SPLIT('[|]',$_POST['registro']);
//// CONSULTA PRINCIPAL
//echo $num_orden,$num_movimiento;
$sql = "select * from af_movimientos where Organismo = '$organismo' and MovimientoNumero = '$num_movimiento'"; //echo $sql;
$qry = mysql_query($sql) or die ($sql.mysql_error());
$row = mysql_num_rows($qry);
if($row!=0)$field = mysql_fetch_array($qry);
?>

<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Movimientos de Activos | Editar</td>
		<td align="right"><a class="cerrar" onclick="window.close();">[cerrar]</a></td>
	</tr>
</table>
<hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="af_movimientoactivonuevo.php" method="POST" onsubmit="return guardarEditarMovimientoActivo(this)">
<table class='tblForm' width='1000' height='50'>
<? echo "
     <input type='hidden' name='numeroMovimientoGenerado' id='numeroMovimientoGenerado'/> 
	 <input type='hidden' name='fOrganismo' id='fOrganismo' value='".$fOrganismo."'/> 
	 <input type='hidden' name='fmovimiento' id='fmovimiento' value='".$fmovimiento."'/>
	 <input type='hidden' name='fEstado' id='fEstado' value='".$fEstado."'/>
	 <input type='hidden' name='valorguardar' id='valorguardar' /> 
";?>
<tr>
   <td>
   <table>
   <tr>
       <td width='5'></td>
       <td align='right'>Organismo:</td>
       <td align='left'>
           <select name='fOrganismo' id='fOrganismo' class='selectBig' $dOrganismo disabled>
           <? getOrganismos($_SESSION['ORGANISMO_ACTUAL'],3);?>
           </select>
       </td>
	   <td width='5'></td>
       <td align='right'>Movimiento #</td>
	   <td><input type='text' id='fmovimiento' name='fmovimiento' value='<?=$num_movimiento;?>' style="text-align:right" disabled/></td>
	   <td width='5'>Comentario:</td>
       <td><input type='text' id='comentario' name='comentario' size='50' value="<?=$field['Comentario'];?>"/></td>
	   <!--<td align='right'>Estado:</td>
       <td><input type="text" id="estado" name="estado" value="En Preparaci&oacute;n" size="20" disabled/></td>-->	   
   </tr>
   <tr>
     <td width='5'></td>
     <td align="right">Preparado Por:</td>
     <? /// Consulta Usuario Actual
	$s_con = "select
				   NomCompleto
			   from 
				   mastpersonas
			  where
				   CodPersona = '".$field['PreparadoPor']."'";
	$q_con = mysql_query($s_con) or die ($s_con.mysql_error());
	$f_con = mysql_fetch_array($q_con);
	?>
	 <td><input type="hidden" id="preparado_por" name="preparado_por" value="<?=$f_con['NomCompleto'];?>" /><input type='text' id='pre_por' name='pre_por' value="<?=$f_con['NomCompleto'];?>" size='51' disabled/>
	 <? 
	    list($a,$m,$d)= SPLIT('[-]',$field['FechaPreparacion']);
	    if($d!=00)$fechaPreparacion = $d.'-'.$m.'-'.$a;
     ?>
	     <input type='text' id='fecha_prepa' name='fecha_prepa' value="<?=$fechaPreparacion;?>" size='8' disabled/></td>
	 <td width='5'></td>
	 <td>Tipo Movimiento:</td>
	 <td><? if($field['InternoExternoFlag']=I){ 
	          $checked1 = 'checked';  $checked2 = ''; $flag = 'I';
			}else{
			  $checked1 = '';  $checked2 = 'checked'; $flag = 'E';	
			}?> 
         <input type='radio' id='radio1' name='radio1' <?=$checked1;?> onclick="estadosPosee02(this.form)|selectMotMovimiento(this.form);"/>Interno
         <input type='radio' id='radio2' name='radio2' <?=$checked2;?> onclick="estadosPosee02(this.form)|selectMotMovimiento(this.form);"/>Externo
         <input type="hidden" name="radioEstado" id="radioEstado" value="<?=$flag;?>"/>
       </td>
    <td></td>
   </tr>
   <tr>
     <td width='5'></td>
     <td align="right">Aprobado Por:</td>
     <? $s_aprobado= "select
				             NomCompleto
			            from 
				             mastpersonas
			           where
				             CodPersona = '".$field['AprobadoPor']."'";
		$q_aprobado = mysql_query($s_aprobado) or die ($s_aprobado.mysql_error());
		$f_aprobado = mysql_fetch_array($q_aprobado); ?>
	 <td> <? list($a,$m,$d)= SPLIT('[-]',$field['FechaAprobacion']); if($d!=00)$fechaAprobacion = $d.'-'.$m.'-'.$a;?>
         <input type='text' id='apro_por' name='apro_por' value="<?=$f_aprobado['NomCompleto'];?>" size="51" disabled/>
	     <input type='text' id='fecha_apro' name='fecha_apro' value='<?=$fechaAprobacion;?>' size='8' disabled/></td>
	 <td width='5'></td>
	 <td align='right'>Motivo Traslado:</td>
	 <td align='left' colspan='2'>
     <? 
	  if($field['InternoExternoFlag']='I') $vstyle = 'style="display:block;"';
	 ?>
        <select id="motivoTrasladoInterno" name="motivoTrasladoInterno"  <?=$vstyle;?> class="selectMed">
          <option value=""></option>
          <?
            $s_movint = "select * from mastmiscelaneosdet where CodMaestro='MMOVINTER'";
            $q_movint = mysql_query($s_movint) or die ($s_movint.mysql_error());
            $r_movint = mysql_num_rows($q_movint);
            
            for($i=0; $i<$r_movint; $i++){
                $f_movint = mysql_fetch_array($q_movint);
                if($f_movint['CodDetalle']==$field['MotivoTraslado'])
				  echo"<option value='".$f_movint['CodDetalle']."' selected>".$f_movint['Descripcion']."</option>";
				else
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
                if($f_movext['CodDetalle']==$field['MotivoTraslado'])
				  echo"<option value='".$f_movext['CodDetalle']."' selected>".$f_movext['Descripcion']."</option>";
				else
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
      <input type="button" id="btInsertar" name="Insertar" value="Insertar" disabled/><input type="button" id="btEliminar" name="btEliminar" value="Eliminar" disabled/></td>
 <td>Seleccionar <input type="button" id="btactivo" name="btactivo" value="Activo" onclick="cargarVentanaLista(this.form, 'af_selectoractivos.php?limit=0&campo=1','height=500, width=870, left=200, top=100, resizable=yes');" /><input type="button" id="centro_costos" name="centro_costos" value="Centro de Costos" onclick="cargarVentanaLista(this.form, 'af_listacentroscostos.php?limit=0&campo=10','height=500, width=870, left=200, top=100, resizable=yes');" /><input type="button" id="btubicacion" name="btubicacion" value="Ubicaci&oacute;n" onclick="cargarVentanaLista(this.form, 'af_listaubicacionesactivo?limit=0&campo=11','height=500, width=870, left=200, top=100, resizable=yes');" /><input type="button" id="btDependencia" name="btDependencia" value="Dependencia" onclick="cargarVentanaLista(this.form, 'af_listadependencias.php?limit=0&campo=14','height=500, width=870, left=200, top=100, resizable=yes');" /> <input type="button" id="btEmpleadoUsuario" name="btEmpleadoUsuario" value="Empl. Usuario" onclick="cargarVentanaLista(this.form, 'af_listaempleados.php?limit=0&campo=12', 'height=500, width=870, left=200, top=100, resizable=yes');" /><input type="button" id="btEmpleadoResponsable" name="btEmpleadoResponsable" value="Empl. Respons." onclick="cargarVentanaLista(this.form, 'af_listaempleados.php?limit=0&campo=13', 'width=870, height=500, top=100, left=200, resizable=yes');" /></td>
 </tr>
</table>
</td>
</tr>
<tr>
  <td align='center'>Ultima Modif.:<input type='text' id='ultimo_usuario' name='ultimo_usuario' value="<?=$field['UltimoUsuario'];?>" size='25' readonly/><input type='text' id='ultima_fecha' value="<?=$field['UltimaFechaModif'];?>" readonly/></td>
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
<?
  $s_consulta = "select 
                       ac.*,
					   afm.CentroCosto as cc_Actual,
					   afm.CentroCostoAnterior as cc_Anterior,
					   ccosto1.Descripcion as Descpcc_Actual,
					   ccosto2.Descripcion as Descpcc_Anterior,
					   afm.Ubicacion as ub_Actual,
					   afm.UbicacionAnterior as ub_Anterior,
					   afu1.Descripcion as Descpubi_Actual,
					   afu2.Descripcion as Descpubi_Anterior,
					   afm.Dependencia as Dep_Actual,
					   afm.DependenciaAnterior as Dep_Anterior,
					   mdep1.Dependencia as Descpdep_Actual,
					   mdep2.Dependencia as Descpdep_Anterior,
					   afm.EmpleadoUsuario as emp_Actual,
					   afm.EmpleadoUsuarioAnterior as emp_Anterior,
					   mper1.NomCompleto as nom_empActual,
					   mper2.NomCompleto as nom_empAnterior,
					   afm.EmpleadoResponsable as empresp_Actual,
					   afm.EmpleadoResponsableAnterior as empresp_Anterior,
					   mper3.NomCompleto as nom_resActual,
					   mper4.NomCompleto as nom_resAnterior,
					   afm.OrganismoActual as orgActual,
					   afm.OrganismoAnterior as orgAnterior,
					   morg1.Organismo as nomorgActual,
					   morg2.Organismo as nomorgAnterior
				   from
				       af_movimientosdetalle afm
					   inner join af_activo ac on (ac.Activo = afm.Activo)
					   inner join ac_mastcentrocosto ccosto1 on (afm.CentroCosto = ccosto1.CodCentroCosto)
					   inner join ac_mastcentrocosto ccosto2 on (afm.CentroCostoAnterior = ccosto2.CodCentroCosto)
					   inner join af_ubicaciones afu1 on (afu1.CodUbicacion = afm.Ubicacion)
					   inner join af_ubicaciones afu2 on (afu2.CodUbicacion = afm.UbicacionAnterior)
					   inner join mastdependencias mdep1 on (mdep1.CodDependencia = afm.Dependencia)
					   inner join mastdependencias mdep2 on (mdep2.CodDependencia = afm.DependenciaAnterior) 
					   inner join mastpersonas mper1 on (mper1.CodPersona = afm.EmpleadoUsuario)
					   inner join mastpersonas mper2 on (mper2.CodPersona = afm.EmpleadoUsuarioAnterior)
					   inner join mastpersonas mper3 on (mper3.CodPersona = afm.EmpleadoResponsable)
					   inner join mastpersonas mper4 on (mper4.CodPersona = afm.EmpleadoResponsableAnterior)
					   inner join mastorganismos morg1 on (morg1.CodOrganismo = afm.OrganismoActual) 
					   inner join mastorganismos morg2 on (morg2.CodOrganismo = afm.OrganismoAnterior) 
				  where
				       afm.MovimientoNumero = '".$field['MovimientoNumero']."' ";
  $q_consulta = mysql_query($s_consulta) or die ($s_consulta.mysql_error());
  $f_consulta = mysql_fetch_array($q_consulta);
?>
<td colspan="4"><input type="text" name="nro_movimientos" id="nro_movimientos" style="border-color:#666; text-align:right" size="18" value="1" readonly/><input type="text" id="activo" name="activo" style="border-color:#666; text-align:right" size="60" value='<?=$f_consulta['Activo'];?>' readonly/><input type="text" id="descripcion" name="descripcion" style="border-color:#666" size="88" value="<?=$f_consulta['Descripcion'];?>" readonly/> Cod.Bar. <input type="text" id="cod_bar" name="cod_bar" size="37" style="border-color:#666; text-align:right" disabled value="<?=$f_consulta['CodigoBarras'];?>"/></td>
</tr>
<tr>
 <td width="98">Centro Costos</td><td colspan="3"><input type="text" id="c_costos" name="c_costos" size="6" value="<?=$f_consulta['cc_Anterior'];?>" disabled/><input type="text" id="c_costos2" name="c_costos2" size="89" value="<?=$f_consulta['Descpcc_Anterior'];?>" disabled/><input type="text" id="c_costosActual" name="c_costosActual" size="6" value="<?=$f_consulta['cc_Actual'];?>" disabled/><input type="text" id="c_costosActual2" name="c_costosActual2" size="89" value="<?=$f_consulta['Descpcc_Actual'];?>" disabled/></td>
</tr>
<tr>
 <td>Ubicaci&oacute;n</td><td colspan="3"><input type="text" id="ubicacion" name="ubicacion" size="6" value="<?=$f_consulta['ub_Anterior'];?>" disabled/><input type="text" id="ubicacion2" name="ubicacion2" size="89" value="<?=$f_consulta['Descpubi_Anterior'];?>" disabled/><input type="text" id="ubicacion_Actual" name="ubicacion_Actual" size="6" value="<?=$f_consulta['ub_Actual'];?>" disabled/><input type="text" id="ubicacion_Actual2" name="ubicacion_Actual2" size="89" value="<?=$f_consulta['Descpubi_Actual'];?>" disabled/></td>
</tr>
<tr>
 <td>Dependencia</td><td colspan="3"><input type="text" id="dependencia" name="dependencia" size="6" value="<?=$f_consulta['Dep_Anterior'];?>" disabled/><input type="text" id="dependencia2" name="dependencia2" size="89" value="<?=htmlentities($f_consulta['Descpdep_Anterior']);?>" disabled/><input type="text" id="dependenciaActual" name="dependenciaActual" value="<?=$f_consulta['Dep_Actual'];?>" size="6" disabled/><input type="text" id="dependenciaActual2" name="dependenciaActual2" size="89" readonly value="<?=htmlentities($f_consulta['Descpdep_Actual']);?>" disabled/></td>
</tr>
<tr>
 <td>Empl. Usuario</td><td colspan="3"><input type="text" id="e_usuario" name="e_usuario" size="6" value="<?=$f_consulta['emp_Anterior'];?>" disabled/><input type="text" id="e_usuario2" name="e_usuario2" size="89" value="<?=htmlentities($f_consulta['nom_empAnterior']);?>" disabled/><input type="text" id="e_usuarioActual" name="e_usuarioActual" size="6" value="<?=$f_consulta['emp_Actual'];?>" disabled/><input type="text" id="e_usuarioActual2" name="e_usuarioActual2" size="89" value="<?=htmlentities($f_consulta['nom_empActual']);?>" disabled/></td>
</tr>
<tr>
 <td>Empl. Respons.</td><td colspan="3"><input type="text" id="e_responsable" name="e_responsable" size="6" value="<?=$f_consulta['empresp_Anterior'];?>" disabled/><input type="text" id="e_responsable2" name="e_responsable2" size="89" value="<?=htmlentities($f_consulta['nom_resActual']);?>" disabled/><input type="text" id="e_responsableActual" name="e_responsableActual" value="<?=$f_consulta['empresp_Actual'];?>" size="6" disabled/><input type="text" id="e_responsableActual2" name="e_responsableActual2" size="89" value="<?=htmlentities($f_consulta['nom_resAnterior']);?>" disabled/></td>
</tr>
<tr>
 <td>Organismo</td><td colspan="3"><input type="text" id="organismo" name="organismo" size="6" value="<?=$f_consulta['orgAnterior'];?>" disabled/><input type="text" id="organismo2" name="organismo2" size="89" value="<?=$f_consulta['nomorgAnterior'];?>" disabled/><select id="organismoActual" name="organismoActual" onchange="cargarOrganismoMovimiento(this.form);">
                                 <option value=""></option>
                                 <?
                                 $s_organismo = "select * from mastorganismos";
								 $q_organismo = mysql_query($s_organismo) or die ($s_organismo.mysql_error());
								 $r_organismo =  mysql_num_rows($q_organismo);
								 if($r_organismo!=0){
								   for($i=0;$i<$r_organismo;$i++){
									   $f_organismo = mysql_fetch_array($q_organismo);
								     if($f_organismo['CodOrganismo']==$f_consulta['orgActual'])
									   echo"<option value='".$f_organismo['CodOrganismo']."' selected>".$f_organismo['CodOrganismo']."</option>";
									 else 
									   echo"<option value='".$f_organismo['CodOrganismo']."'>".$f_organismo['CodOrganismo']."</option>";  
								   }
								 }
								 ?>
                                 </select><input type="text" id="organismoActual2" name="organismoActual2" size="89" value="<?=htmlentities($f_consulta['nomorgActual']);?>" disabled/></td>
</tr>
</table>
</div>
<center>
 <input type="submit" id="guardar" name="guardar" value="Guardar Registro" />
 <input type="button" id="cancelar" name="cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'af_movimientoactivos.php');" />
</center>
</form>
<div style="width:850px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>