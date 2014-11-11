<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include ("fphp.php");
include ("controlActivoFijo.php");
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
<script type="text/javascript" language="javascript" src="af_fscript2.js"></script>
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

list($organismo, $activo)=SPLIT('[|]',$_GET['registro']);
//// CONSULTA PRINCIPAL
$sa = "select 
             * 
		 from 
		     af_activo 
	    where 
		     CodOrganismo = '$organismo' and 
			 Activo= '$activo'";
$qa = mysql_query($sa) or die ($sa.mysql_error()); //echo $sa;
$ra = mysql_num_rows($qa); 

if($ra!='0')$fa=mysql_fetch_array($qa);
?>

<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Aprobar | Activo Mayor</td>
		<td align="right"><a class="cerrar" href="" onclick="window.close();">[cerrar]</a></td>
	</tr>
</table>
<hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="af_listactivosagregar.php"  onsubmit="return AprobarActivo(this);">
<? echo"<input type='hidden' id='registro' name='registro' value='".$registro."'/>";?>
<table width="908" align="center">
<tr>
  <td>
	<div id="header">
	<ul>
	<!-- CSS Tabs PESTA�AS OPCIONES -->
	<li><a onClick="document.getElementById('tab1').style.display='block'; document.getElementById('tab2').style.display='none';" href="#">Informaci&oacute;n General</a></li>
	<li><a onClick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='block';" href="#">Informaci&oacute;n Adicional</a></li> 
	</ul>
	</div>
  </td>
</tr>
</table>
<? echo" <input type='hidden' id='regresar' name='regresar' value='".$_GET['regresar']."' />";?>
<!-- ****************************************************** COMIENZO TAB1 ************************************************ -->
<div id="tab1" style="display: block;">
<div style="width:900px; height=15px;" class="divFormCaption">Informaci&oacute;n General</div>
<table class="tblForm" width="900">
<tr>
   <td class="tagForm">Activo #:</td>
   <td><input type="hidden" name="clas_public20" id="clas_public20" /><input type="text" id="nro_activo" name="nro_activo" size="30" value="<?=$fa['Activo'];?>" disabled style="text-align:right"/></td>
   <td class="tagForm">Estado Pendiente:</td>
   <? 
     if($fa['Estado']=='PE') $estado = 'Pendiente de Activar'; else $estado = 'Aprobado'
   
   ?>
   <td><input type="text" name="estado_pendiente" id="estado_pendiente" size="30" value="<?=$estado;?>" disabled/></td>
   <td></td>
</tr>
<tr>
  <td class="tagForm">Descripci&oacute;n:</td>
  <td colspan="3"><textarea id="descripcion" name="descripcion" cols="160" rows="4" disabled><?=$fa['Descripcion']; ?></textarea><input type="button" id="btCargar" name="btCargar" value="..." onclick="cargarVentanaLista(this.form, 'af_listactivoslogistica.php?limit=0&campo=2','height=500, width=820, left=200, top=100, resizable=yes');" disabled/></td>
  <td></td>
</tr>
</table>

<table class="tblForm" width="900">
<tr>
   <td class="tagForm">Situaci&oacute;n Activo:</td>
   <td>
       <select id="situacion_activo" class="selectMed" disabled>
        <? $s_activo = "select * from af_situacionactivo where CodSituActivo = '".$fa['SituacionActivo']."'";
           $q_activo = mysql_query($s_activo) or die ($s_activo.mysql_error());
           $r_activo = mysql_num_rows($q_activo);
          
           if($r_activo!='0'){
		    for($i=0;$i<$r_activo;$i++){
               $f_activo = mysql_fetch_array($q_activo);
                echo"<option value='".$f_activo['CodSituActivo']."' selected>".$f_activo['Descripcion']."</option>";
            }
		   }
        ?>         
       </select></td> 
   <td class="tagForm" width="100">Organismo:</td>
   <td><select id="organismo" name="organismo" class="selectBig" disabled>
       <?
        $s_org = "select * from mastorganismos where CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."'";
        $q_org = mysql_query($s_org) or die ($s_org.mysql_error());
        $r_org = mysql_num_rows($q_org);
        if($r_org!='0'){
            for($i=0;$i<$r_org;$i++){
              $f_org = mysql_fetch_array($q_org);
              echo"<option value='".$f_org['CodOrganismo']."'>".$f_org['Organismo']."</option>";
            }
			
        }
        
       ?>
       </select></td>
</tr>
<tr>
   <td class="tagForm">Tipo de Activo:</td>
   <td><select id="tipo_activo" class="selectMed" disabled>
        <?
         $s_tactivo = "select * from mastmiscelaneosdet where CodMaestro = 'TIPOACTIVO'";
		 $q_tactivo = mysql_query($s_tactivo) or die ($s_tactivo.mysql_error());
		 $r_tactivo = mysql_num_rows($q_tactivo);
		 
		 if($r_tactivo != 0){
	       for($i=0; $i<$r_tactivo; $i++){
			 $f_tactivo = mysql_fetch_array($q_tactivo); 
			 if($f_tactivo['CodDetalle']== $fa['TipoActivo']){ 
		      echo"<option value='".$f_tactivo['CodDetalle']."' selected>".$f_tactivo['Descripcion']."</option>";
			 }else{
			  echo"<option value='".$f_tactivo['CodDetalle']."'>".$f_tactivo['Descripcion']."</option>";
			 }
		   }
		 }
		?>
      </select>
      </td>
   <td class="tagForm">Dependencia</td>
   <td><select id="dependencia" name="dependencia" class="selectBig" disabled>
         <option value=""></option>
         <?
          $s_dep = "select 
		                  * 
					  from 
					      mastdependencias 
					  where 
					      CodOrganismo = '".$fa['CodOrganismo']."'";
          $q_dep = mysql_query($s_dep) or die ($s_dep.mysql_error()); //echo $s_dep;
          $r_dep = mysql_num_rows($q_dep);
  
		  if($r_dep!='0'){
			for($i=0;$i<$r_dep;$i++){
			   $f_dep = mysql_fetch_array($q_dep);
			   if($f_dep['CodDependencia']==$fa['CodDependencia'])
			      echo"<option value='".$f_dep['CodDependencia']."' selected>".$f_dep['Dependencia']."</option>"; 
			   else echo"<option value='".$f_dep['CodDependencia']."'>".$f_dep['Dependencia']."</option>"; 
			}
		  }
		 ?>
       </select></td> 
</tr>
<tr>
   <td class="tagForm">Naturaleza:</td>
          <? /*
           $s_parametro = "select * from mastparametros where ParametroClave = 'CATDEPRDEFECT'";
           $q_parametro = mysql_query($s_parametro) or die ($s_parametro.mysql_error());
           $f_parametro = mysql_fetch_array($q_parametro);
		   if($f_parametro['ValorParam']=='AN'){
			  $parametro = 'Activo Normal';   
			}*/
		  ?>
          <? if($fa['Naturaleza']='BMA') $natu = 'Bien Mayor'; ?>
   <td><input type="text" id="naturaleza" name="naturaleza" size="30" value="<?=$natu;?>" disabled/></td>
   <td class="tagForm">Categor&iacute;a:</td>
   <td width="300"><?
                   $s_categoria = "select * from af_categoriadeprec where CodCategoria = '".$fa['Categoria']."'";
				   $q_categoria = mysql_query($s_categoria) or die ($s_categoria.mysql_error());
				   $r_categoria = mysql_num_rows($q_categoria);
				   
				   if($r_categoria!=0){
					   for($i=0;$i<$r_categoria;$i++){
						  $f_categoria = mysql_fetch_array($q_categoria);
						  $CodCategoria = $f_categoria['CodCategoria'];
						  $DescripcionLocal = $f_categoria['DescripcionLocal'];
					   }
					}
				   ?>
                   <select id="select_categoria" class="selectSma" onchange="cargarCampoCategoria(this.id)" disabled>
                     <option value="<?=$CodCategoria;?>"><?=$CodCategoria;?></option>
                     </select>
                   <input type="text" id="categoria" name="categoria" size="34" value="<?=$DescripcionLocal;?>" disabled/></td>
   <td></td>
</tr>
<tr>
   <td class="tagForm">Origen Activo:</td>
   <td><input type="text" id="origen_activo" name="origen_activo" size="30" value="Manual" disabled/></td>
   <td class="tagForm">Clasificaci&oacute;n:</td>
       <?
        $s_clactivo = "select * from af_clasificacionactivo where CodClasificacion= '".$fa['Clasificacion']."'";
		$q_clactivo = mysql_query($s_clactivo) or die ($s_clactivo.mysql_error());
		$f_clactivo = mysql_fetch_array($q_clactivo);
	   ?>
   <td><input type="text" id="clasificacion" name="clasificacion" size="19" value="<?=$f_clactivo['CodClasificacion'];?>" disabled/>
       <input type="text" id="clasificacion2" name="clasificacion2" size="40" value="<?=$f_clactivo['Descripcion'];?>" disabled/></td>
   <td><input type="button" name="btClasificacion" id="btClasificacion" value="..." onclick="cargarVentanaLista(this.form, 'af_listaclasificacionactivo.php?limit=0&campo=1','height=500, width=800, left=200, top=100, resizable=yes');" disabled/></td>
</tr>
<tr>
   <td class="tagForm" width="100">Estado Conserv.:</td>
   <td><select id="estado_conserv" name="estado_conserv" class="selectMed" disabled>
        <?
         //// CONSULTA PARA CARGAR EL SELECT  DE TIPO MEJORA
         $s_estconv = "select * from mastmiscelaneosdet where CodMaestro = 'ESTCONSERV'";
         $q_estconv = mysql_query($s_estconv) or die ($s_estconv.mysql_error());
         $r_estconv = mysql_num_rows($q_estconv);
         if($r_estconv!=0){
            for($i=0;$i<$r_estconv;$i++){
              $f_estconv = mysql_fetch_array($q_estconv);
			  if($fa['CodDetalle']==$fa['EstadoConserv']){
               echo"<option value='".$f_estconv['CodDetalle']."' selected>".$f_estconv['Descripcion']."</option>";
			  }else{
			    echo"<option value='".$f_estconv['CodDetalle']."'>".$f_estconv['Descripcion']."</option>";
			  }
            }
          }
        ?>
        </select></td>
   <td class="tagForm">Ubicaci&oacute;n:</td>
   <?
    $s_ubicacion = "select * from af_ubicaciones where CodUbicacion = '".$fa['Ubicacion']."'";
	$q_ubicacion = mysql_query($s_ubicacion) or die ($s_ubicacion.mysql_error());
	$f_ubicacion = mysql_fetch_array($q_ubicacion);
   
   ?>
   <td><input type="text" name="ubicacion" id="ubicacion" size="19" value="<?=$f_ubicacion['CodUbicacion'];?>" disabled/>
       <input type="text" name="ubicacion2" id="ubicacion2" size="40" value="<?=$f_ubicacion['Descripcion'];?>" disabled/></td>
   <td><input type="button" name="btUbicacion" id="btUbicacion" value="..." onclick="cargarVentanaLista(this.form, 'af_listaubicacionesactivo.php?limit=0&campo=2','height=500, width=800, left=200, top=100, resizable=yes');" disabled/></td>
</tr>
<tr>
   <td class="tagForm">C&oacute;digo Barras:</td>
   <td><input type="text" id="codigo_barras" name="codigo_barras" size="30" style="text-align:right" value="<?=$fa['CodigoBarras'];?>" disabled/></td>
   <td class="tagForm">Tipo Mejora:</td>
   <td><select id="tipo_mejora" style="width: 72px;" disabled>
       <?
         //// CONSULTA PARA CARGAR EL SELECT  DE TIPO MEJORA
         $s_miscelaneos = "select * from mastmiscelaneosdet where CodMaestro = 'TIPOMEJORA' and CodAplicacion='AF'";
         $q_miscelaneos = mysql_query($s_miscelaneos) or die ($s_miscelaneos.mysql_error());
         $r_miscelaneos = mysql_num_rows($q_miscelaneos);
         if($r_miscelaneos!=0){
            for($i=0;$i<$r_miscelaneos;$i++){
              $f_miscelaneos = mysql_fetch_array($q_miscelaneos);
			  if($f_miscelaneos['CodDetalle']==$fa['TipoMejora'])
                 echo"<option value='".$f_miscelaneos['CodDetalle']."' selected>".$f_miscelaneos['Descripcion']."</option>";
			  else
			     echo"<option value='".$f_miscelaneos['CodDetalle']."'>".$f_miscelaneos['Descripcion']."</option>";
            }
          }
        ?>
       </select> 
       <?
         $s_actconsolidado = "select * from af_activo where Activo = '".$fa['ActivoConsolidado']."'";
		 $q_actconsolidado = mysql_query($s_actconsolidado) or die ($s_actconsolidado.mysql_error());
		 $f_actconsolidado = mysql_fetch_array($q_actconsolidado);
	   ?>
       Act. Principal:<input type="hidden" name="activo_principal" id="activo_principal"  value="<?=$f_actconsolidado['Activo'];?>"/>
       <input type="text" name="activo_principal2" id="activo_principal2" size="29" value="<?=$f_actconsolidado['Descripcion'];?>"/></td>
   <td><input type="button" name="btActivop" id="btActivop" value="..." onclick="cargarVentanaLista(this.form, 'af_listaactivosfijos.php?limit=0&campo=16','height=500, width=800, left=200,top=100,resizable=yes');" disabled/></td>
</tr>
<tr>
   <td class="tagForm">C&oacute;digo Interno:</td>
   <td><input type="text" id="codigo_interno" name="codigo_interno" size="30" style="text-align:right" value="<?=$fa['CodigoInterno'];?>" disabled/></td>
   <td class="tagForm" width="150">Empleado Usuario:</td>
   <?
    $s_empusuario = "select *  from mastpersonas where CodPersona = '".$fa['EmpleadoUsuario']."'";
	$q_empusuario = mysql_query($s_empusuario) or die ($s_empusuario.mysql_error());
	$f_empusuario = mysql_fetch_array($q_empusuario);
   ?>
   <td><input type="hidden" name="cod_usuario" id="cod_usuario" value="" /><input type="text" id="nomb_usuario" name="nomb_usuario" size="68" value="<?=$f_empusuario['NomCompleto'];?>" disabled/></td>
   <td><input type="button" name="btEmpleado" id="btEmpleado" value="..." onclick="cargarVentanaLista(this.form,'af_listaempleados.php?limit=0&campo=7','height=500, width=800,left=200,top=100,resizable=yes');" disabled/></td>
</tr>
<tr>
   <td class="tagForm">T. Seguro:</td>
   <td width="245"><?
                    $s_tseguro = "select * from af_tiposeguro where CodTipoSeguro='".$fa['TipoSeguro']."'";
					$q_tseguro = mysql_query($s_tseguro) or die ($s_tseguro.mysql_error());
					$f_tseguro = mysql_fetch_array($q_tseguro);
				   ?>
                   <input type="text" id="t_seguro" name="t_seguro" size="10" value="<?=$f_tseguro['CodTipoSeguro'];?>" disabled/> <select id="select_TSeguro" class="selectSma" onchange="valorTSeguro(this.id);" disabled>
   <option value="<?=$f_tseguro['CodTipoSeguro'];?>"><?=$f_tseguro['Descripcion'];?></option>
                   
   </select></td>
   <td class="tagForm" width="150">Empl. Responsable:</td>
   <?
    $s_empresp = "select *  from mastpersonas where CodPersona = '".$fa['EmpleadoResponsable']."'";
	$q_empresp= mysql_query($s_empresp) or die ($s_empresp.mysql_error());
	$f_empresp = mysql_fetch_array($q_empresp);
   ?>
   <td><input type="hidden" id="cod_empresponsable" name="cod_empresponsable" value="" disabled="true"/><input type="text" id="empleado_responsable" name="empleado_responsable" size="68" value="<?=$f_empresp['NomCompleto'];?>" disabled/></td>
   <td><input type="button" name="btEmpleadoResp" id="btEmpleadoResp" value="..." onclick="cargarVentanaLista(this.form,'af_listaempleados.php?limit=0&campo=8','height=500,width=800,left=200,top=100,resizable=yes');" disabled/></td>
</tr>
<tr>
   <td class="tagForm">T. Veh&iacute;culo:</td>
                   <?
                    $s_tvehiculo = "select * from af_tipovehiculo where CodTipoVehiculo='".$fa['TipoVehiculo']."'";
					$q_tvehiculo = mysql_query($s_tvehiculo) or die ($s_tvehiculo.mysql_error());
					$f_tvehiculo = mysql_fetch_array($q_tvehiculo);
				   ?>
   <td><input type="text" id="t_vehiculo" name="t_vehiculo" size="10" value="<?=$f_tvehiculo['CodTipoVehiculo'];?>" disabled/> <select id="select_TVehiculo" class="selectSma" onchange="valorTVehiculo(this.id);" disabled>
   <option value="<?=$f_tvehiculo['CodTipoVehiculo'];?>"><?=$f_tvehiculo['Descripcion'];?></option>
       <?
        $s_tvehiculo = "select * from af_tipovehiculo";
		$q_tvehiculo = mysql_query($s_tvehiculo) or die ($s_tvehiculo.mysql_error());
		
		while($f_tvehiculo=mysql_fetch_array($q_tvehiculo)){
		 echo"<option value='".$f_tvehiculo['CodTipoVehiculo']."'>".$f_tvehiculo['Descripcion']."</option>";
		}
	   ?>
   </select></td>
   <td class="tagForm"><u>Informaci&oacute;n Contable</u></td>
</tr>
<tr>
   <td colspan="2"><input type="checkbox" name="disp_mantenimientoflag" id="disp_mantenimientoflag" value="S"/> Disponible Para Mantenimiento</td>
   <td class="tagForm">Centro Costos:</td>
   <?
    $s_cencosto = "select * from ac_mastcentrocosto where CodCentroCosto = '".$fa['CentroCosto']."'";
	$q_cencosto = mysql_query($s_cencosto) or die ($s_cencosto.mysql_error());
	$f_cancosto = mysql_fetch_array($q_cencosto);
   ?>
   <td><input type="text" id="centro_costos" name="centro_costos" size="19" value="<?=htmlentities($f_cancosto['CodCentroCosto']);?>" disabled/>
       <input type="text" id="centro_costos2" name="centro_costos2" size="40" value="<?=htmlentities($f_cancosto['Descripcion']);?>" disabled/></td>
   <td><input type="button" name="btCentroCostos" id="btCentroCostos" value="..." onclick="cargarVentanaLista(this.form,'af_listacentroscostos.php?limit=0&campo=9','height=500,width=800,left=200,top=100,resizable=yes');" disabled/></td>
</tr>
<tr>
   <td colspan="2"><input type="checkbox" name="disp_operacionesflag" id="disp_operacionesflag" value="S"/> Disponible Para Operaciones</td>
</tr>
<tr>
   <td class="tagForm">Preparado por:</td>
   <td colspan="2"><? $s_usuario = "select 
                                           NomCompleto
									  from 
									       mastpersonas
									 where 
									       CodPersona = '".$fa['PreparadoPor']."'";
                      $q_usuario = mysql_query($s_usuario) or die ($s_usuario.mysql_error());
					  $f_usuario = mysql_fetch_array($q_usuario);
					?>
   <input type="text" id="preparadoPor" name="preparadoPor" size="50" disabled="true" value="<?=$f_usuario['NomCompleto'];?>"/>
   <input type="text" name="fecha_preparado" id="fecha_preparado" size="8" disabled="true" value="<?=date("d-m-Y");?>"/></td>
</tr>
<tr>
   <td class="tagForm">Aprobado por:</td>
   <?
     $s_aprobpor = "select 
	                       mp.NomCompleto 
					  from 
					       usuarios u 
						   inner join mastpersonas mp on (mp.CodPersona = u.CodPersona) 
					 where 
					       Usuario = '".$_SESSION['USUARIO_ACTUAL']."'";
	 $q_aprobpor = mysql_query($s_aprobpor) or die ($s_aprobpor.mysql_error());
	 $f_aprobpor = mysql_fetch_array($q_aprobpor);
	 
   ?>
   <td colspan="2"><input type="text" id="aprobadoPor" name="aprobadoPor" size="50" value="<?=$f_aprobpor['NomCompleto'];?>" disabled/>
                   <input type="text" name="fecha_aprobado" id="fecha_aprobado" size="8" value="<?=date("d-m-Y");?>" disabled/></td>
</tr>
<tr><td align="center" colspan="5">Ultima Modif.:<input type="text" name="ultimo_usuario" value="<?=$fa['UltimoUsuario'];?>" size="25" disabled/><input type="text" name="ultima_fecha" value="<?=$fa['UltimaFechaModif'];?>" size="20" disabled/></td></tr>
</table>
</div>
<!-- ****************************************************** COMIENZO TAB2 ************************************************ -->
<div id="tab2" style="display: none;">
<div style="width:900px; height=15px;" class="divFormCaption">Informaci&oacute;n Adicional</div>
<table class="tblForm" width="900">
<tr>
  <td width="133" class="tagForm"><u>Informaci&oacute;n del Activo</u></td>
</tr>
<tr><?
$scon2 = "select 
                ma.Descripcion as DescpMarcas
			from
			    lg_marcas ma
		   where
		        ma.CodMarca = '".$fa['Marca']."'";
$qcon2= mysql_query($scon2) or die ($scon2.mysql_error());
$fcon2= mysql_fetch_array($qcon2);
?>
  <td class="tagForm">Fabricante(Marca):</td>
  <td width="199"><input type="hidden" name="fabricante" id="fabricante" size="40" value="<?=$fa['CodMarca'];?>" disabled/><input type="text" name="fabricante2" id="fabricante2" size="40" value="<?=$fcon2['DescpMarcas'];?>" disabled/></td>
  <td width="118" class="tagForm">Material:</td>
  <td width="190"><input type="text" id="material" name="material" size="25" value="<?=$fa['Material'];?>" disabled/></td>
  <td width="141" class="tagForm">Fecha Ingreso:</td><? 
                                                        list($a, $m, $d) = SPLIT('[-]', $fa['FechaIngreso']); 
                                                        $fecha_ingreso = $d.'-'.$m.'-'.$a;
													 ?>
  <td width="91"><input type="text" id="fecha_ingreso" name="fecha_ingreso" size="8" value="<?=$fecha_ingreso;?>" onchange="crearPeriodo(this.form, this.id);" disabled/></td>
</tr>
<tr>
  <td class="tagForm">Modelo:</td>
  <td><input type="text" name="modelo" id="modelo" size="40" value="<?=$fa['Modelo'];?>" disabled/></td>
  <td class="tagForm">Dimensiones:</td>
  <td><input type="text" id="dimensiones" name="dimensiones" size="25" value="<?=$fa['Dimensiones'];?>" disabled/></td>
  <td class="tagForm">Per&iacute;odo Registro:</td>
  <td><input type="text" id="periodo_registro" name="periodo_registro" size="8" disabled value="<?=$fa['PeriodoIngreso'];?>" style="text-align:center;"/></td>
</tr>
<tr>
  <td class="tagForm">N&uacute;mero de Serie:</td>
  <td><input type="text" name="nro_serie" id="nro_serie" size="40" value="<?=$fa['NumeroSerie'];?>" disabled/></td>
  <td class="tagForm">Nro. Parte:</td>
  <td><input type="text" id="nro_parte" name="nro_parte" size="25" value="<?=$fa['NumerodeParte'];?>" disabled/></td>
  <td class="tagForm">Inicio Depreciaci&oacute;n:</td>
  <td><input type="text" id="ini_depreciacion" name="ini_depreciacion" size="8" style="text-align:center" value="<?=$fa['PeriodoInicioDepreciacion'];?>" disabled/></td>
</tr>
<tr>
  <td class="tagForm">Nro. Serie Motor:</td>
  <td><input type="text" name="nro_seriemotor" id="nro_seriemotor" size="40" value="<?=$fa['NumeroSerieMotor'];?>" disabled/></td>
  <td class="tagForm">Color:</td>
  <td><select id="color" class="selectMed" disabled>
      <option value=""></option>
       <?
       $s_color = "select * from mastmiscelaneosdet where CodMaestro='COLOR'";
	   $q_color = mysql_query($s_color) or die ($s_color.mysql_error());
	   
	   while($f_color = mysql_fetch_array($q_color)){
		  if ($f_color['CodDetalle']==$fa['Color']) echo"<option value='".$f_color['CodDetalle']."' selected>".$f_color['Descripcion']."</option>";
		  else echo"<option value='".$f_color['CodDetalle']."'>".$f_color['Descripcion']."</option>";
	   }
	  ?>
      </select></td>
  <td class="tagForm">Inicio Ajust.x Inflac.:</td>
  <td><input type="text" name="ini_ajuste" id="ini_ajuste" size="8" style="text-align:center" value="<?=$fa['PeriodoInicioRevaluacion'];?>" disabled/></td>
</tr>
<tr>
  <td class="tagForm">N&uacute;mero Placa:</td>
  <td><input type="text" name="nro_placa" id="nro_placa" size="40" value="<?=$fa['NumeroPlaca'];?>" disabled /></td>
  <td class="tagForm">Pa&iacute;s de Fabricaci&oacute;n:</td>
  <td><select id="pais_fabricacion" class="selectMed" disabled>
      <?
	     $spaises = "select * from mastpaises";
		 $qpaises = mysql_query($spaises) or die ($spaises.mysql_error());
		 $rpaises = mysql_num_rows($qpaises);
		 
		 for($i=0;$i<$rpaises;$i++){
		   $fpaises = mysql_fetch_array($qpaises);
		   echo"<option value='".$fpaises['CodPais']."'>".$fpaises['Pais']."</option>";
		 }
		 
	   ?>
      </select></td>
  <td class="tagForm"><u>Informaci&oacute;n de la Baja</u></td>
</tr>
<tr>
  <td class="tagForm">Marca Motor:</td>
<?
$scon2 = "select 
                ma.Descripcion as DescpMarcas
			from
			    lg_marcas ma
		   where
		        ma.CodMarca = '".$fa['MarcaMotor']."'";
$qcon2= mysql_query($scon2) or die ($scon2.mysql_error());
$fcon2= mysql_fetch_array($qcon2);
?>
  <td><input type="text" name="marca_motor" id="marca_motor" size="40" value="<?=$fcon2['DescpMarcas'];?>" disabled/></td>
  <td class="tagForm">A&ntilde;o de Fabricaci&oacute;n:</td>
  <td><input type="text" id="ano_fabricacion" name="ano_fabricacion"  size="8" style="text-align: right;" value="<?=$fa['FabricacionAno'];?>" disabled/></td>
  <td class="tagForm">Per&iacute;odo de Baja:</td>
  <td><input type="text" id="periodo_baja" name="periodo_baja" size="8" value="<?=$fa['PeriodoBaja'];?>" style="text-align:center" disabled/></td>
</tr>
<tr>
  <td class="tagForm">N&uacute;mero Asientos:</td>
  <td><input type="text" name="nro_asientos" id="nro_asientos" size="5" value="<?=$fa['NumeroAsiento'];?>" style="text-align:right" disabled/></td>
  <td colspan="2"></td>
  <td class="tagForm">Voucher de Baja:</td>
  <td><input type="text" id="voucher_baja" name="voucher_baja" size="8" value="<?=$fa['VoucherBaja'];?>" style="text-align:right" disabled/></td>
</tr>
<tr>
  <td class="tagForm"><u>Informaci&oacute;n Adicional</u></td>
  <td></td>
  <td colspan="2" align="left"><u>Informaci&oacute;n para Inmuebles</u></td>
  <td colspan="2" align="left"><? if($fa['DepreEspecificaFlag']=='S'){?><input type="checkbox" name="depre_especifica" id="depre_especifica" checked disabled/><? }else{?><input type="checkbox" name="depre_especifica" id="depre_especifica" checked disabled/><? }?> Depreciaci&oacute;n Espec&iacute;fica</td>
</tr>
<tr>
  <td class="tagForm">Poliza de Seguro:</td>
  <td><select id="poliza_seguro" class="selectSma" disabled>
      <?
       $s_polseguro = "select * from af_polizaseguro";
	   $q_polseguro = mysql_query($s_polseguro) or die ($s_polseguro.mysql_error());
	   $r_polseguro = mysql_num_rows($q_polseguro);
	   
	   for($i=0; $i<$r_polseguro; $i++){
		 $f_polseguro = mysql_fetch_array($q_polseguro);
		 if($f_polseguro['CodPolizaSeguro']==$fa['PolizaSeguro']) 
		    echo"<option value='".$f_polseguro['CodPolizaSeguro']."' selected>".$f_polseguro['DescripcionLocal']."</option>";
		 else
		    echo"<option value='".$f_polseguro['CodPolizaSeguro']."'>".$f_polseguro['DescripcionLocal']."</option>";
	   }
	  ?>
      </select></td>
  <td class="tagForm">C&oacute;digo Catastro:</td>
  <td><select id="cod_catastro" name="cod_catastro" class="selectSma" onchange="valorTerreno(this.form, this.id);" disabled>
  <?
       $s_catastro = "select * from af_catastro";
	   $q_catastro = mysql_query($s_catastro) or die ($s_catastro.mysql_error());
	   $r_catastro = mysql_num_rows($q_catastro);
	   
	   for($i=0; $i<$r_catastro; $i++){
	      $f_catastro = mysql_fetch_array($q_catastro);
		  if($f_catastro['CodCatastro']==$fa['CodigoCatastro'])
		     echo"<option value='".$f_catastro['CodCatastro']."' selected>".$f_catastro['Descripcion']."</option>";
		  else
		     echo"<option value='".$f_catastro['CodCatastro']."'>".$f_catastro['Descripcion']."</option>";
	   }
	  ?>
  </select></td>
  <td colspan="2" align="left"></td>
</tr>
<tr>
  <td class="tagForm">N&uacute;mero Unidades:</td>
  <td><input type="text" id="nro_unidades" name="nro_unidades" size="5" value="<?=$fa['NumeroUnidades'];?>" style="text-align:right" disabled/></td>
  <td class="tagForm">&Aacute;rea Terreno(m2)</td><? $montoCatastro = number_format($fa['MontoCatastro'],2,',','.');?>
  <td><input type="text" name="area_terreno" id="area_terreno" size="10" style="text-align:right" value="<?=$fa['AreaFisicaCatastro'];?>" disabled/><input type="text" name="area_terreno2" id="area_terreno2" size="20" style="text-align:right" value="<?=$montoCatastro;?>" disabled/></td>
</tr>
<tr>
  <td class="tagForm">Unidad de Medida:</td>
  <td><select id="unidad_medida" name="unidad_medida" class="selectMed" disabled>
      <?
       $sunidad = "select * from mastunidades";
	   $qunidad = mysql_query($sunidad) or die ($sunidad.mysql_error()); $runidad = mysql_num_rows($qunidad);
       for($i=0; $i<$runidad;$i++){
	    $funidad = mysql_fetch_array($qunidad);
	    if($funidad['CodUnidad']==$fa['UnidadMedida'])
		  echo"<option value='".$funidad['CodUnidad']."' selected>".$funidad['Descripcion']."</option>";
		  else echo"<option value='".$funidad['CodUnidad']."' selected>".$funidad['Descripcion']."</option>";
	   }
	  ?>
      </select></td>
  <td colspan="2"><? if($fa['GenerarVoucherIngresoFlag']=='S'){?><input type="checkbox" name="gen_voucher" id="gen_voucher" checked="checked" disabled/><? }else{?><input type="checkbox" name="gen_voucher" id="gen_voucher" disabled/><? }?>Generar Voucher de Ingreso del Activo</td>
</tr>
<tr>
 <td height="5"></td>
</tr>
<tr>
  <td align="left" colspan="2"><u>Informaci&oacute;n Documentaria</u></td>
  <td class="tagForm"></td>
  <td class="tagForm"><u>Informaci&oacute;n Monetaria</u></td>
</tr>
<tr>
  <td class="tagForm">Proveedor:</td>
     <?
	 $s_proveedor = "select 
							p.CodProveedor as codProveedor,
							mp.NomCompleto as NombProveedor
					   from 
							mastproveedores p
							inner join mastpersonas mp on (p.CodProveedor = mp.CodPersona)
					   where
							p.CodProveedor = '".$fa['CodProveedor']."'";
	 $q_proveedor = mysql_query($s_proveedor) or die ($s_proveedor.mysql_error());
	 $f_proveedor = mysql_fetch_array($q_proveedor);
	 ?>
  <td colspan="2"><input type="text" id="proveedor" name="proveedor" size="8" value="<?=$f_proveedor['codProveedor'];?>" disabled/><input type="text" id="nomb_proveedor" name="nomb_proveedor" size="48" value="<?=$f_proveedor['NombProveedor'];?>" readonly/>
                  <input type="button" id="btProveedor" name="btProveedor" value="..." onclick="cargarVentanaLista(this.form, 'af_listaproveedor.php?limit=0&campo=1','height=500, width=800, left=200,top=100,resizable=yes');" disabled/></td>
 <td class="tagForm">Monto Local:</td><?
                                      $monto_local = number_format($fa['MontoLocal'],2,',','.');
									 ?>
<td><input type="text" id="monto_local" name="monto_local" value="<?=$monto_local;?>" style="text-align:right" disabled/>Bs.F</td>
</tr>
<tr>
  <td class="tagForm">Factura:</td>
  <td colspan="2"><select id="factura" name="factura" style="width:70px;" disabled>
                  <?
                  $s_facnumerodoc = "select * from ap_tipodocumento";
				  $q_facnumerodoc = mysql_query($s_facnumerodoc) or die ($s_facnumerodoc.mysql_error());
				  $r_facnumerodoc = mysql_num_rows($q_facnumerodoc); 
				  
				  for($i=0; $i<$r_facnumerodoc; $i++){
				    $f_facnumerodoc = mysql_fetch_array($q_facnumerodoc);
					if($f_facnumerodoc['CodTipoDocumento']==$fa['FacturaTipoDocumento'])
					   echo"<option value='".$f_facnumerodoc['CodTipoDocumento']."' selected>".$f_facnumerodoc['Descripcion']."-$r_facnumerodoc</option>";
				    else
					   echo"<option value='".$f_facnumerodoc['CodTipoDocumento']."'>".$f_facnumerodoc['Descripcion']."</option>";
				  }
				  ?>
                  </select> <? list($a, $m, $d) = SPLIT('[-]',$fa['FacturaFecha']); $fechaFactura = $d.'-'.$m.'-'.$a;?>
                  <input type="text" id="num_factura" name="num_factura" size="30" value="<?=$fa['FacturaNumeroDocumento'];?>" disabled/>
                  <input type="text" id="fecha_factura" name="fecha_factura" size="8" value="<?=$fechaFactura;?>" disabled/></td>
                  <td class="tagForm">Voucher Ingreso:</td>
<td><input type="text" name="voucher_ingreso" id="voucher_ingreso" value="<?=$fa['VoucherIngreso'];?>" disabled/></td>
</tr>
<tr>
   <td class="tagForm">Orden Compra:</td><? list($a,$m,$d)= SPLIT('[-]',$fa['NumeroOrdenFecha']); $fechaOrdenCompra = $d.'-'.$m.'-'.$a;?>
   <td><input type="text" id="orden_compra" name="orden_compra" size="25" value="<?=$fa['NumeroOrden']?>"/><input type="text" id="fecha_ordencompra" name="fecha_ordencompra" size="8" value="<?=$fechaOrdenCompra;?>"/></td>
  <td></td>
   <td class="tagForm">Valor Mercado:</td><? $montoMercado = number_format($fa['MontoMercado'],2,',','.');?>
 <td><input type="text" name="valor_mercado" id="valor_mercado" style="text-align:right" value="<?=$montoMercado;?>" disabled/></td>
</tr>
<tr>
   <td class="tagForm">Gu&iacute;a Remisi&oacute;n #:</td><? list($a,$m,$d)= SPLIT('[-]',$fa['NumeroGuiaFecha']); $fechaGuiaRemision = $d.'-'.$m.'-'.$a;?>
   <td><input type="text" id="nro_guiaremision" name="nro_guiaremision" size="25" value="<?=$fa['NumeroGuia'];?>" disabled/><input type="text" id="fecha_guiaremision" name="fecha_guiaremision" size="8" value="<?=$fechaGuiaRemision;?>" disabled/></td>
   <td></td>
   <td class="tagForm">Monto Referencial:</td><? $montoReferencia = number_format($fa['MontoReferencia'],2,',','.');?>
 <td><input type="text" name="monto_referencial" id="monto_referencial" value="<?=$montoReferencia;?>" style="text-align:right" disabled/></td>
</tr>
<tr>
   <td class="tagForm">Docum. Almacen:</td><? list($a,$m,$d)=SPLIT('[-]',$fa['DocAlmacenFecha']);$fechaAlmacen = $d.'-'.$m.'-'.$a;?>
   <td><input type="text" id="nro_documalmacen" name="nro_documalmacen" size="25" value="<?=$fa['NumeroDocAlmacen'];?>" disabled/><input type="text" id="fecha_documalmacen" name="fecha_documalmacen" size="8" value="<?=$fechaAlmacen;?>" disabled/></td>
</tr>
<tr>
   <td colspan="2" align="left"><u>Informaci&oacute;n Inventario F&iacute;sico</u></td>
</tr>
<tr>
   <td class="tagForm">Fecha Inventario:</td>
   <td><input type="text" name="fecha_inventario" id="fecha_inventario" size="8" value="<?=date('d-m-Y');?>"/></td>
   <td class="tagForm">Observaciones</td>
   <td colspan="3"><textarea name="observacion" id="observacion" rows="2" cols="80"></textarea></td>
</tr>
</table>
</div>
<center><input type="submit" name="btAprobar" id="btAprobar" value="Aprobar"/><input type="button" name="btCancelar" id="btCancelar" value="Cancelar" onclick="window.close();"/></center>
</form>