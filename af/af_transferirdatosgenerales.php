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
<link href="css2.css" rel="stylesheet" type="text/css" />
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../css/custom-theme/jquery-ui-1.8.16.custom.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript2.js"></script>
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
<div id="cajaModal"></div>
<!-- pretty -->
<span class="gallery clearfix"></span>
<?
list($num_orden,$secuencia,$nrosecuencia,$codorganismo)=SPLIT('[|]',$_GET['registro']);
//// CONSULTA PRINCIPAL
$sa = "select 
             * 
		 from 
		     lg_activofijo 
		where 
		     NroOrden = '$num_orden' and 
			 Secuencia = '$secuencia' and 
			 NroSecuencia = '$nrosecuencia' and 
			 CodOrganismo = '$codorganismo'";
$qa = mysql_query($sa) or die ($sa.mysql_error());
$ra = mysql_num_rows($qa); //echo $ra;
$fa=mysql_fetch_array($qa);
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Transferir Activos</td>
		<td align="right"><a class="cerrar" href="" onclick="window.close();">[cerrar]</a></td>
	</tr>
</table>
<hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="af_transferirdatosgenerales.php" method="POST" onsubmit="return guardarActivosTransferidos(this);">
<table width="908" align="center">
<tr>
  <td>
	<div id="header">
	<ul>
	<!-- CSS Tabs PESTA�AS OPCIONES -->
	<li><a onClick="document.getElementById('tab1').style.display='block'; 
    document.getElementById('tab2').style.display='none';  
    document.getElementById('tab3').style.display='none'; 
    document.getElementById('tab4').style.display='none';
    <? if($_PARAMETRO['VOUINGP20']=='S'){?>document.getElementById('tab5').style.display='none';<? }?>" href="#">Informaci&oacute;n General</a></li>
	<li><a onClick="document.getElementById('tab1').style.display='none'; 
    document.getElementById('tab2').style.display='block'; 
    document.getElementById('tab3').style.display='none'; 
    document.getElementById('tab4').style.display='none';
    <? if($_PARAMETRO['VOUINGP20']=='S'){?>document.getElementById('tab5').style.display='none';<? }?>" href="#">Informaci&oacute;n Adicional</a></li> 
    <li><a onclick="document.getElementById('tab1').style.display='none'; 
    document.getElementById('tab2').style.display='none'; 
    document.getElementById('tab3').style.display='block'; 
    document.getElementById('tab4').style.display='none';
    <? if($_PARAMETRO['VOUINGP20']=='S'){?>document.getElementById('tab5').style.display='none';<? }?>" href="#">Informaci&oacute;n Contable</a></li>
    <li><a onclick="document.getElementById('tab1').style.display='none'; 
    document.getElementById('tab2').style.display='none'; 
    document.getElementById('tab3').style.display='none'; 
    document.getElementById('tab4').style.display='block';
    <? if($_PARAMETRO['VOUINGP20']=='S'){?>document.getElementById('tab5').style.display='none';<? }?>" href="#">Car. T&eacute;cnicas y Documentaci&oacute;n</a></li>
    <? if($_PARAMETRO['VOUINGP20']=='S'){?><li><a  onclick="document.getElementById('tab1').style.display='none'; 
    document.getElementById('tab2').style.display='none'; 
    document.getElementById('tab3').style.display='none'; 
    document.getElementById('tab4').style.display='none';
    document.getElementById('tab5').style.display='block';" href="#">Voucher Ingreso</a></li><? }?>
	</ul>
	</div>
  </td>
</tr>
</table>
<?
  echo"
      <input type='hidden' id='num_orden' name='num_orden' value='$num_orden'/>
	  <input type='hidden' id='secuencia' name='secuencia' value='$secuencia'/>
	  <input type='hidden' id='nrosecuencia' name='nrosecuencia' value='$nrosecuencia'/>
	  <input type='hidden' id='codorganismo' name='codorganismo' value='$codorganismo'/>
      <input type='hidden' id='activo' name='activo' value='' />
   ";
 ?>
<!-- ****************************************************** COMIENZO TAB1 ************************************************ -->
<div id="tab1" style="display: block;">
<div style="width:900px; height=15px;" class="divFormCaption">Informaci&oacute;n General</div>
<table class="tblForm" width="900">
<tr>
   <td class="tagForm">Activo #:</td>
   <td><input type="text" id="nro_activo" name="nro_activo" size="30" disabled="true"/></td>
   <td class="tagForm">Estado:</td>
   <td><input type="text" name="estado_pendiente" id="estado_pendiente" size="30" value="Pendiente de Activar" disabled="true"/></td>
   <td></td>
</tr>
<tr>
  <td class="tagForm">Descripci&oacute;n:</td>
  <td colspan="3"><textarea id="descripcion" name="descripcion" cols="160" rows="4"><?=$fa['Descripcion']; ?></textarea></td>
  <td></td>
</tr>
</table>

<table class="tblForm" width="900">
<tr>
   <td class="tagForm">Situaci&oacute;n Activo:</td>
   <td>
        <select id="situacion_activo" class="selectMed">
        <? $s_activo = "select * from af_situacionactivo";
           $q_activo = mysql_query($s_activo) or die ($s_activo.mysql_error());
           $r_activo = mysql_num_rows($q_activo);
          
           if($r_activo!='0'){
		    for($i=0;$i<$r_activo;$i++){
               $f_activo = mysql_fetch_array($q_activo);
			   if($f_activo['CodSituActivo']=='OP') echo"<option value='".$f_activo['CodSituActivo']."' selected>".$f_activo['Descripcion']."</option>";
			   else echo"<option value='".$f_activo['CodSituActivo']."'>".$f_activo['Descripcion']."</option>";
            }
		   }
        ?>         
       </select>
   </td> 
   <td class="tagForm" width="132">Organismo:</td>
   <td>
       <select id="organismo" name="organismo" class="selectBig" disabled>
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
       </select>
   </td>
</tr>
<tr>
   <td class="tagForm">Concep. de Movimiento:</td>
   <td>
       <select id="conceptoMovimiento" class="selectMed">
        
        <? $s_cm = "select * from af_tipomovimientos";
           $q_cm = mysql_query($s_cm) or die ($s_cm.mysql_error());
           $r_cm = mysql_num_rows($q_cm);
          
           if($r_cm!='0'){
		    for($i=0;$i<$r_cm;$i++){
               $f_cm = mysql_fetch_array($q_cm);
			   if(($f_cm['CodTipoMovimiento']=='03')and($f_cm['TipoMovimiento']=='IN')){
                echo"<option value='".$f_cm['CodTipoMovimiento']."' selected>".htmlentities($f_cm['DescpMovimiento'])."</option>";
			   }else{
			     echo"<option value='".$f_cm['CodTipoMovimiento']."'>".htmlentities($f_cm['DescpMovimiento'])."</option>";
			   }	
            }
		   }
        ?>         
       </select></td> 
  <td class="tagForm">Dependencia</td>
   <td>
      <select id="dependencia" name="dependencia" class="selectBig" >
         <option value=""></option>
         <?
          $s_dep = "select * from mastdependencias where CodOrganismo = '".$_SESSION['ORGANISMO_ACTUAL']."'";
          $q_dep = mysql_query($s_dep) or die ($s_dep.mysql_error()); //echo $s_dep;
          $r_dep = mysql_num_rows($q_dep);
		  if($r_dep!='0'){
			for($i=0;$i<$r_dep;$i++){
			   $f_dep = mysql_fetch_array($q_dep);
			   echo"<option value='".$f_dep['CodDependencia']."'>".$f_dep['Dependencia']."</option>"; 
			}
		  }
		 ?>
       </select>
    </td> 
</tr>
<tr>
   <td class="tagForm">Tipo de Activo:</td>
   <td>
      <select id="tipo_activo" class="selectMed">
        <?
         $s_tactivo = "select * from mastmiscelaneosdet where CodMaestro = 'TIPOACTIVO'";
		 $q_tactivo = mysql_query($s_tactivo) or die ($s_tactivo.mysql_error());
		 $r_tactivo = mysql_num_rows($q_tactivo);
		 
		 if($r_tactivo != 0){
	       for($i=0; $i<$r_tactivo; $i++){
			 $f_tactivo = mysql_fetch_array($q_tactivo);  
		     echo"<option value='".$f_tactivo['CodDetalle']."'>".$f_tactivo['Descripcion']."</option>";
		   }
		 }
		?></select>
     </td>
   <td class="tagForm">Categor&iacute;a:</td>
   <td width="301">
    <select id="select_categoria" style=" width:65px" onchange="cargarCampoCategoria(this.id)">
                   <option value=""></option>
                   <?
                   $s_categoria = "select * from af_categoriadeprec";
				   $q_categoria = mysql_query($s_categoria) or die ($s_categoria.mysql_error());
				   $r_categoria = mysql_num_rows($q_categoria);
				   
				   if($r_categoria!=0){
					   for($i=0;$i<$r_categoria;$i++){
						  $f_categoria = mysql_fetch_array($q_categoria);
						   echo"<option value='".$f_categoria['CodCategoria']."'>".$f_categoria['CodCategoria'].'  '.' -  '.$f_categoria['DescripcionLocal']."</option>";
					   }
					}
				   ?>
                   </select>
     <input type="text" id="categoria" name="categoria" size="50" value="" disabled/></td>
   <td width="43"></td>
</tr>
<tr>
   <td class="tagForm">Naturaleza:</td><? 
           /*$s_parametro = "select * from mastparametros where ParametroClave = 'CATDEPRDEFECT'";
           $q_parametro = mysql_query($s_parametro) or die ($s_parametro.mysql_error());
           $f_parametro = mysql_fetch_array($q_parametro);
		   if($f_parametro['ValorParam']=='AN'){
			  $parametro = 'Activo Normal';   
			}*/
			//echo"<input type='text' name='naturaleza' id='naturaleza' value='".$_PARAMETRO['CATDEPRDEFECT']."'/>";
			$Nat = $_GET['Nat'];
			if($Nat==1){ 
			   $nat = 'Activo Normal';
			   echo"<input type='hidden' name='naturaleza' id='naturaleza' value='".$_PARAMETRO['CATDEPRDEFECT']."'/>";
			}else{
				if($Nat==2) $nat = 'Activo Menor';
				echo"<input type='hidden' name='naturaleza' id='naturaleza' value='AM'/>";
			}
		  ?>
   <td><input type="text" id="naturalezaDescp" name="naturalezaDescp" size="30" value="<?=$nat;?>" disabled/></td>
   <td class="tagForm">Clasificaci&oacute;n20:</td><?
                   $s_c20 = "select * from af_clasificacionactivo20";
				   $q_c20 = mysql_query($s_c20) or die ($s_c20.mysql_error());
				   $r_c20 = mysql_num_rows($q_c20);
				   
				   /*if($r_c20!=0){
					   for($i=0;$i<$r_c20;$i++){
						  $f_c20 = mysql_fetch_array($q_c20);
						   echo"<option value='".$f_c20['CodClasificacion']."'>".htmlentities($f_c20['Descripcion'])."</option>";
					   }
					}*/
				   ?>
   <td><input type="hidden" id="clasificacion20" name="clasificacion20" disabled="true" value=""/><input type="text" id="clasificacion20Descp" name="clasificacion20Descp" size="67" value="" disabled="true"/></td>
   <td class="gallery clearfix"><input type="hidden" name="btClasificacion20" id="btClasificacio20" value="..." onclick="cargarVentanaLista(this.form, 'af_listadoclasificacion20.php?limit=0&campo=1&ventana=insertarClasificacionPub20','height=500, width=800, left=200, top=100, resizable=yes');"/> <a href="af_listadoclasificacion20.php?filtrar=default&limit=0&campo=1&ventana=insertarClasificacionPub20&iframe=true&width=100%&height=100%" rel="prettyPhoto[iframe1]">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;"/>
            </a></td>
</tr>
<tr>
   <td class="tagForm">Origen Activo:</td>
   <td><input type="text" id="origen_activo" name="AT" size="30" value="Autom&aacute;tico" disabled="true"/></td>
   <td class="tagForm">Clasificaci&oacute;n:</td>
   <td><?
        $s_clactivo = "select * from af_clasificacionactivo where CodClasificacion= '".$fa['CodClasificacion']."'";
		$q_clactivo = mysql_query($s_clactivo) or die ($s_clactivo.mysql_error());
		$f_clactivo = mysql_fetch_array($q_clactivo);
	   ?><input type="text" id="clasificacion" name="clasificacion" size="8" value="<?=$f_clactivo['CodClasificacion'];?>" style="text-align:right" disabled/>
       <input type="text" id="clasificacion2" name="clasificacion2" size="51" value="<?=$f_clactivo['Descripcion'];?>" disabled/></td>
   <td class="gallery clearfix"><input type="hidden" name="btClasificacion" id="btClasificacion" value="..." onclick="cargarVentanaLista(this.form, 'af_listaclasificacionactivo.php?limit=0&campo=1&ventana=cargarClasificacionActivo','height=500, width=800, left=200, top=100, resizable=yes');"/> <a href="af_listaclasificacionactivo.php?filtrar=default&limit=0&campo=1&ventana=cargarClasificacionActivo&iframe=true&width=100%&height=100%" rel="prettyPhoto[iframe1]">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;"/>
            </a></td>
</tr>
<tr>
   <td class="tagForm" width="147">Estado Conserv.:</td>
   <td>
        <select id="estado_conserv" name="estado_conserv" class="selectMed">
        <option></option>
        <?
         //// CONSULTA PARA CARGAR EL SELECT  DE TIPO MEJORA
         $s_miscelaneos = "select * from mastmiscelaneosdet where CodMaestro = 'ESTCONSERV'";
         $q_miscelaneos = mysql_query($s_miscelaneos) or die ($s_miscelaneos.mysql_error());
         $r_miscelaneos = mysql_num_rows($q_miscelaneos);
         if($r_miscelaneos!=0){
            for($i=0;$i<$r_miscelaneos;$i++){
              $f_miscelaneos = mysql_fetch_array($q_miscelaneos);
			  if($f_miscelaneos['CodDetalle']=='B')
                 echo"<option value='".$f_miscelaneos['CodDetalle']."' selected>".$f_miscelaneos['Descripcion']."</option>";
			  else 
			     echo"<option value='".$f_miscelaneos['CodDetalle']."'>".$f_miscelaneos['Descripcion']."</option>";
            }
          }
        ?>
        </select>
    </td>
   <td class="tagForm">Ubicaci&oacute;n:</td>
   <td><?
      $s_con = "select 
	                  ub.Descripcion as DescpUbicacion,
					  ub.CodUbicacion as CodUbicacion
				  from 
				      af_ubicaciones ub
				 where 
				      ub.CodUbicacion = '".$fa['CodUbicacion']."'";
	  $q_con = mysql_query($s_con) or die ($s_con.mysql_error());
	  $f_con = mysql_fetch_array($q_con); 
   
   ?><input type="text" name="ubicacion" id="ubicacion" size="8" value="<?=$f_con['CodUbicacion'];?>" style="text-align:right" disabled/>
       <input type="text" name="ubicacion2" id="ubicacion2" size="51" value="<?=htmlentities($f_con['DescpUbicacion']);?>" disabled/></td>
   <td class="gallery clearfix"><input type="hidden" name="btUbicacion" id="btUbicacion" value="..." onclick="cargarVentanaLista(this.form, 'af_listaubicacionesactivo.php?limit=0&campo=2','height=500, width=800, left=200, top=100, resizable=yes');"/> <a href="af_listaubicacionesactivo.php?filtrar=default&limit=0&campo=2&iframe=true&width=100%&height=100%" rel="prettyPhoto[iframe1]">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;"/>
            </a></td>
</tr>
<tr>
   <td class="tagForm">C&oacute;digo Barras:</td>
   <td><input type="text" id="codigo_barras" name="codigo_barras" size="30" value="<?=$fa['CodBarra'];?>" maxlength="15" style="text-align:right"/></td>
   <td class="tagForm">Tipo Mejora:</td>
   <td>
      <select id="tipo_mejora" style="width: 72px;">
       <?
         //// CONSULTA PARA CARGAR EL SELECT  DE TIPO MEJORA
         $s_miscelaneos = "select * from mastmiscelaneosdet where CodMaestro = 'TIPOMEJORA'";
         $q_miscelaneos = mysql_query($s_miscelaneos) or die ($s_miscelaneos.mysql_error());
         $r_miscelaneos = mysql_num_rows($q_miscelaneos);
         if($r_miscelaneos!=0){
            for($i=0;$i<$r_miscelaneos;$i++){
              $f_miscelaneos = mysql_fetch_array($q_miscelaneos);
              echo"<option value='".$f_miscelaneos['CodDetalle']."'>".$f_miscelaneos['Descripcion']."</option>";
            }
          }
        ?>
       </select> 
       Act. Principal:<input type="hidden" name="activo_principal" id="activo_principal" value=""/><input type="text" name="activo_principal2" id="activo_principal2" size="29" value="" disabled/></td>
   <td class="gallery clearfix"><input type="hidden" name="btActivop" id="btActivop" value="..." onclick="cargarVentanaLista(this.form, 'af_listaactivosfijos.php?limit=0&campo=16','height=500, width=950, left=200,top=100,resizable=yes');"/> <a href="af_listaactivosfijos.php?filtrar=default&limit=0&campo=16&iframe=true&width=113%&height=100%" rel="prettyPhoto[iframe1]">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;"/>
            </a></td>
</tr>
<tr>
   <td class="tagForm">C&oacute;digo Interno:</td>
   <td><input type="text" id="codigo_interno" name="codigo_interno" size="30" value="" style="text-align:right" maxlength="10" /></td>
   <td class="tagForm" width="132">Empleado Usuario:</td>
   <td><input type="hidden" name="cod_usuario" id="cod_usuario" value="" />
       <input type="text" id="nomb_usuario" name="nomb_usuario" size="67" value="" disabled="true"/></td>
   <td class="gallery clearfix">
       <input type="hidden" name="btEmpleado" id="btEmpleado" value="..." onclick="cargarVentanaLista(this.form,'af_listaempleados.php?limit=0&campo=7','height=500, width=800,left=200,top=100,resizable=yes');"/> <a href="af_listaempleados.php?filtrar=default&limit=0&campo=7&iframe=true&width=100%&height=100%" rel="prettyPhoto[iframe1]">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;"/>
            </a></td>
</tr>
<tr>
   <td class="tagForm">T. Seguro:</td>
   <td width="253"><input type="text" id="t_seguro" name="t_seguro" size="10" value="" />
                   <select id="select_TSeguro" class="selectSma" onchange="valorTSeguro(this.id);">
                   <option value=""></option>
                   <?
                    $s_tseguro = "select * from af_tiposeguro";
					$q_tseguro = mysql_query($s_tseguro) or die ($s_tseguro.mysql_error());
					
					while($f_tseguro = mysql_fetch_array($q_tseguro)){
					  echo"<option value='".$f_tseguro['CodTipoSeguro']."'>".$f_tseguro['Descripcion']."</option>";
					}
				   ?>
                   </select></td>
   <td class="tagForm" width="132">Empl. Responsable:</td>
   <td>
   <input type="hidden" id="cod_empresponsable" name="cod_empresponsable" value="" disabled="true"/>
   <input type="text" id="empleado_responsable" name="empleado_responsable" size="67" value="" disabled="true"/></td>
   <td class="gallery clearfix"><input type="hidden" name="btEmpleadoResp" id="btEmpleadoResp" value="..." onclick="cargarVentanaLista(this.form,'af_listaempleados.php?limit=0&campo=8','height=500,width=800,left=200,top=100,resizable=yes');"/> <a href="af_listaempleados.php?filtrar=default&limit=0&campo=8&iframe=true&width=100%&height=100%" rel="prettyPhoto[iframe1]">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;"/>
            </a></td>
</tr>
<tr>
   <td class="tagForm">T. Veh&iacute;culo:</td>
   <td><input type="text" id="t_vehiculo" name="t_vehiculo" size="10" value="" />
       <select id="select_TVehiculo" class="selectSma" onchange="valorTVehiculo(this.id);">
       <option value=""></option>
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
   <td colspan="2"><input type="checkbox" name="disp_mantenimiento" id="disp_mantenimiento" value="N" onclick="asignarValorDispMantenimiento(this.form);"/><input type="hidden" name="disp_mantenimientoflag" id="disp_mantenimientoflag" value=""/> Disponible Para Mantenimiento</td>
   <td class="tagForm">Centro Costos:</td>
   <?
    $s_centcosto = "select * from ac_mastcentrocosto where CodCentroCosto='".$fa['CodCentroCosto']."'";
	$q_centcosto = mysql_query($s_centcosto) or die ($s_centcosto.mysql_error());
	$f_centcosto = mysql_fetch_array($q_centcosto);
	
   ?>
   <td><input type="text" id="centro_costos" name="centro_costos" size="8" value="<?=$f_centcosto['CodCentroCosto'];?>" disabled="true" style="text-align:right"/>
       <input type="text" id="centro_costos2" name="centro_costos2" size="51" value="<?=$f_centcosto['Descripcion'];?>" disabled="true"/></td>
   <td class="gallery clearfix"><input type="hidden" name="btCentroCostos" id="btCentroCostos" value="..." onclick="cargarVentanaLista(this.form,'af_listacentroscostos.php?limit=0&campo=9','height=500,width=800,left=200,top=100,resizable=yes');"/> <a href="af_listacentroscostos.php?filtrar=default&limit=0&campo=9&iframe=true&width=100%&height=100%" rel="prettyPhoto[iframe1]">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;"/>
            </a></td>
</tr>
<tr>
   <td colspan="2"><input type="checkbox" name="disp_operaciones" id="disp_operaciones" value="N" onclick="asignarValorDispMantenimiento(this.form);"/>Disponible Para Operaciones</td>
</tr>
<tr>
   <td class="tagForm">Preparado por:</td>
   <td colspan="2">
   <?
     $s_prepor = "select 
	                    m.CodPersona,
						m.NomCompleto
					from
						usuarios u
						inner join mastpersonas m on (u.CodPersona = m.CodPersona)
				   where
				        u.Usuario = '".$_SESSION['USUARIO_ACTUAL']."'";
	 $q_prepor = mysql_query($s_prepor) or die ($s_prepor.mysql_error());
	 $f_prepor = mysql_fetch_array($q_prepor);
   ?>
   <input type="hidden" id="cod_prepor" name="cod_prepor" value="<?=$f_prepor['0'];?>"/>
   <input type="text" id="preparadoPor" name="preparadoPor" size="50" value="<?=$f_prepor['1'];?>" disabled/>
   <input type="text" name="fecha_preparado" id="fecha_preparado" size="8" disabled value="<?=date("d-m-Y");?>"/>
   </td>
</tr>
<tr>
   <td class="tagForm">Aprobado por:</td>
   <td colspan="2"><input type="text" id="aprobadoPor" name="aprobadoPor" size="50" disabled="true"/>
                   <input type="text" name="fecha_aprobado" id="fecha_aprobado" size="8" disabled="true"/></td>
</tr>
<tr><td align="center" colspan="5">Ultima Modif.:<input type="text" name="ultimo_usuario" value="" size="25" /><input type="text" name="ultima_fecha" value="" size="20"/></td></tr>
</table>
</div>
<!-- ****************************************************** COMIENZO TAB2 ************************************************ -->
<div id="tab2" style="display: none;">
<div style="width:900px; height=15px;" class="divFormCaption">Informaci&oacute;n Adicional</div>
<?
$scon2 = "select 
                ma.Descripcion as DescpMarcas
			from
			    lg_marcas ma
		   where
		        ma.CodMarca = '".$fa['CodMarca']."'";
$qcon2= mysql_query($scon2) or die ($scon2.mysql_error());
$fcon2= mysql_fetch_array($qcon2);
?>
<table class="tblForm" width="900">
<tr>
  <td align="left" colspan="2"><u>Informaci&oacute;n del Activo</u></td>
</tr>
<tr>
  <td class="tagForm">Fabricante(Marca):</td>
  <td><select id="fabricante" name="fabricante" class="selectMed" />
      <option></option>
		   <?
            $scon2 = "select * from lg_marcas";
            $qcon2= mysql_query($scon2) or die ($scon2.mysql_error()); $rcon2 = mysql_num_rows($qcon2); 
            for($i=0;$i<$rcon2;$i++){
               $fcon2= mysql_fetch_array($qcon2);
               if($fcon2['CodMarca']==$fa['Marca']) echo"<option value='".$fcon2['CodMarca']."' selected>".htmlentities($fcon2['Descripcion'])."</option>";
			   else echo"<option value='".$fcon2['CodMarca']."'>".htmlentities($fcon2['Descripcion'])."</option>";
			}
            ?>
            </select></td>
  <td class="tagForm">Material:</td>
  <td><input type="text" id="material" name="material" size="25"/></td>
  <td class="tagForm">Fecha Ingreso:</td>
  <? list($a,$m,$d)=SPLIT('[-]',$fa['FechaIngreso']); $fechaIngreso = $d.'-'.$m.'-'.$a;
     $periodoRegistro = $a.'-'.$m; 
	 
	 if($m<12){
		$m2 = $m + 1; $a2 = $a;
	 }else{ 
	    $m2 = 01; $a2 = $a + 1;
	 }
	 if($m2<='09'){
		$inicioDepreciacion = $a2.'-'.'0'.$m2; 
		$inicioAjuste = $a2.'-'.'0'.$m2;
	 }else{
		$inicioDepreciacion = $a2.'-'.$m2;
	    $inicioAjuste = $a2.'-'.$m2; 
	 }?>
  <td><input type="text" id="fecha_ingreso" name="fecha_ingreso" size="8" value="<?=$fechaIngreso;?>" onchange="crearPeriodo(this.form, this.id);" disabled/></td>
</tr>
<tr>
  <td class="tagForm">Modelo:</td>
  <td><input type="text" name="modelo" id="modelo" size="40" value="<?=$fa['Modelo'];?>" disabled/></td>
  <td class="tagForm">Dimensiones:</td>
  <td><input type="text" id="dimensiones" name="dimensiones" size="25"/></td>
  <td class="tagForm">Per&iacute;odo Registro:</td><? list($A, $M, $D) = SPLIT('[-]', $fa['FechaIngreso']); $periodo = $A.'-'.$M;?>
  <td><input type="text" id="periodo_registro" name="periodo_registro" size="8" disabled value="<?=$periodoRegistro;?>" style="text-align:center"/></td>
</tr>
<tr>
  <td class="tagForm">N&uacute;mero de Serie:</td>
  <td><input type="text" name="nro_serie" id="nro_serie" size="40" value="<?=$fa['NroSerie'];?>" disabled/></td>
  <td class="tagForm">Nro. Parte:</td>
  <td><input type="text" id="nro_parte" name="nro_parte" size="25"/></td>
  <td class="tagForm">Inicio Depreciaci&oacute;n:</td>
  <td><input type="text" id="ini_depreciacion" name="ini_depreciacion" size="8" style="text-align:center"  value="<?=$inicioDepreciacion;?>" disabled/></td>
</tr>
<tr>
  <td class="tagForm">Nro. Serie Motor:</td>
  <td><input type="text" name="nro_seriemotor" id="nro_seriemotor" size="40"/></td>
  <td class="tagForm">Color:</td>
  <td><select id="color" class="selectMed">
      <option value=""></option>
      <?
       $s_color = "select * from mastmiscelaneosdet where CodMaestro='COLOR'";
	   $q_color = mysql_query($s_color) or die ($s_color.mysql_error());
	   
	   while($f_color = mysql_fetch_array($q_color)){
		  if ($f_color['CodDetalle']==$fa['Color']) echo"<option value='".$f_color['CodDetalle']."' selected>".$f_color['Descripcion']."</option>";
		  else echo"<option value='".$f_color['CodDetalle']."'>".$f_color['Descripcion']."</option>";
	   }
	  ?>
  
      </select>
  </td>
  <td class="tagForm">Inicio Ajust.x Inflac.:</td>
  <td><input type="text" name="ini_ajuste" id="ini_ajuste" size="8" style="text-align:center" value="<?=$inicioAjuste;?>" disabled/></td>
</tr>
<tr>
  <td class="tagForm">N&uacute;mero Placa:</td>
  <td><input type="text" name="nro_placa" id="nro_placa" size="40" value="<?=$fa['NroPlaca'];?>"/></td>
  <td class="tagForm">Pa&iacute;s de Fabricaci&oacute;n:</td>
  <td><select id="pais_fabricacion" name="pais_fabricacion" class="selectMed">
      <option></option>
       <?
        $s_pais = "select * from mastpaises";
		$q_pais = mysql_query($s_pais) or die ($s_pais.mysql_error());
		$r_pais = mysql_num_rows($q_pais);
		for($i=0; $i<$r_pais;$i++){
		   $f_pais = mysql_fetch_array($q_pais);
		   echo"<option value='".$f_pais['CodPais']."'>".$f_pais['Pais']."</option>";
		}
	   ?>
      </select>
  </td>
  <td class="tagForm"><u>Informaci&oacute;n de la Baja</u></td>
</tr>
<tr>
  <td class="tagForm">Marca Motor:</td>
  <td><input type="text" name="marca_motor" id="marca_motor" size="40"/></td>
  <td class="tagForm">A&ntilde;o de Fabricaci&oacute;n:</td>
  <td><input type="text" id="ano_fabricacion" name="ano_fabricacion" size="8" style="text-align: right;" maxlength="4"/></td>
  <td class="tagForm">Per&iacute;odo de Baja:</td>
  <td><input type="text" id="periodo_baja" name="periodo_baja" size="8" disabled/></td>
</tr>
<tr>
  <td class="tagForm">N&uacute;mero Asientos:</td>
  <td><input type="text" name="nro_asientos" id="nro_asientos" size="5" value="" maxlength="3"/></td>
  <td colspan="2"></td>
  <td class="tagForm">Voucher de Baja:</td>
  <td><input type="text" id="voucher_baja" name="voucher_baja" size="8" disabled/></td>
</tr>
<tr>
  <td class="tagForm"><u>Informaci&oacute;n Adicional</u></td>
  <td></td>
  <td colspan="2" align="left"><u>Informaci&oacute;n para Inmuebles</u></td>
  <td colspan="2" align="left"><input type="checkbox" name="depre_especifica" id="depre_especifica"/> Depreciaci&oacute;n Espec&iacute;fica</td>
</tr>
<tr>
  <td class="tagForm">Poliza de Seguro:</td>
  <td><select id="poliza_seguro" class="selectSma">
      <option value=""></option>
      <?
       $s_polseguro = "select * from af_polizaseguro";
	   $q_polseguro = mysql_query($s_polseguro) or die ($s_polseguro.mysql_error());
	   
	   while($f_polseguro = mysql_fetch_array($q_polseguro)){
	     echo"<option value='".$f_polseguro['CodPolizaSeguro']."'>".$f_polseguro['DescripcionLocal']."</option>";
	   }
	  ?>
      </select></td>
  <td class="tagForm">C&oacute;digo Catastro:</td>
  <td><select id="cod_catastro" name="cod_catastro" class="selectSma" onchange="valorTerreno(this.form, this.id);">
      <OPTION value=""></OPTION>
      <?
       $s_catastro = "select * from af_catastro";
	   $q_catastro = mysql_query($s_catastro) or die ($s_catastro.mysql_error());
	   
	   while($f_catastro = mysql_fetch_array($q_catastro)){
	       echo"<option value='".$f_catastro['CodCatastro']."'>".$f_catastro['Descripcion']."</option>";
	   }
	  ?>
      </select>
  </td>
  <td colspan="2" align="left"></td>
</tr>
<tr>
  <td class="tagForm">N&uacute;mero Unidades:</td>
  <td><input type="text" id="nro_unidades" name="nro_unidades" size="5"/></td>
  <td class="tagForm">&Aacute;rea Terreno(m2)</td>
  <td><input type="text" name="area_terreno" id="area_terreno" size="10" style="text-align:right"/><input type="text" name="area_terreno2" id="area_terreno2" size="20" style="text-align:right" readonly/></td>
</tr>
<tr>
  <td class="tagForm">Unidad de Medida:</td>
  <td><select id="unidad_medida" name="unidad_medida" class="selectMed">
      <option></option>
      <?
       $sunidad = "select * from mastunidades";
	   $qunidad = mysql_query($sunidad) or die ($sunidad.mysql_error()); 
	   $runidad = mysql_num_rows($qunidad);
       for($i=0; $i<$runidad;$i++){
	     $funidad = mysql_fetch_array($qunidad);
	     if ($funidad['CodUnidad']==$valorX)echo"<option value='".$funidad['CodUnidad']."' selected>".$funidad['Descripcion']."</option>";
		 else echo"<option value='".$funidad['CodUnidad']."'>".$funidad['Descripcion']."</option>";
	   }
	  ?>
      </select></td>
  <td colspan="2"><? if($_PARAMETRO['VOUINGP20']=='S'){?>
  					<input type="checkbox" name="gen_voucher" id="gen_voucher" value="S" checked="checked" onclick='this.checked=true;'/>
                  <? }else{?>
                    <input type="checkbox" name="gen_voucher" id="gen_voucher" />
                  <? }?>Generar Voucher de Ingreso del Activo</td>
</tr>
<tr>
 <td height="5"></td>
</tr>
<tr>
  <td align="left" colspan="2"><u>Informaci&oacute;n Documentaria</u></td>
  <td class="tagForm"></td>
  <td class="tagForm"><u>Informaci&oacute;n Monetaria</u></td>
  <td class="tagForm"></td>
  <td></td>
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
  <td colspan="2" class="gallery clearfix"><input type="text" id="proveedor" name="proveedor" size="6" value="<?=$f_proveedor['codProveedor'];?>" disabled/><input type="text" id="nomb_proveedor" name="nomb_proveedor" value="<?=$f_proveedor['NombProveedor'];?>" size="48" disabled="true"/>
                  <input type="hidden" id="btProveedor" name="btProveedor" value="..." onclick="cargarVentanaLista(this.form, 'af_listaproveedor.php?limit=0&campo=1','height=500, width=800, left=200,top=100,resizable=yes');"/> <a href="af_listaproveedor.php?filtrar=default&limit=0&campo=1&cierre=1&iframe=true&width=100%&height=100%" rel="prettyPhoto[iframe8]">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;"/>
            </a></td>
<td class="tagForm">Monto Local:</td><? $monto_local = number_format($fa['Monto'],2,',','.');?>
<td><input type="text" id="monto_local" name="monto_local" value="<?=$monto_local;?>" style="text-align:right" disabled/>Bs.F</td>
</tr>
<tr>
  <td class="tagForm">Factura:</td>
  <td colspan="2"><select id="factura" name="factura" style="width:130px;">
                  <option value=""></option>
                  <?
                  $s_facnumerodoc = "select * from ap_tipodocumento";
				  $q_facnumerodoc = mysql_query($s_facnumerodoc) or die ($s_facnumerodoc.mysql_error());
				  
				  while($f_facnumerodoc = mysql_fetch_array($q_facnumerodoc)){
					if($f_facnumerodoc['CodTipoDocumento']==$fa['ObligacionTipoDocumento']){  
				    echo"<option value='".$f_facnumerodoc['CodTipoDocumento']."' selected>".$f_facnumerodoc['Descripcion']."</option>";
					}else{
				    echo"<option value='".$f_facnumerodoc['CodTipoDocumento']."'>".$f_facnumerodoc['Descripcion']."</option>";
					}
				  }
				  ?>
                  </select> <input type="text" id="num_factura" name="num_factura" size="20" value="<?=$fa['ObligacionNroDocumento'];?>" style="text-align:right" />
				  <? list($a,$b,$c)= SPLIT('[-]',$fa['ObligacionFechaDocumento']); $obligacionfdoc = $c.'-'.$b.'-'.$a; ?><input type="text" id="fecha_factura" name="fecha_factura" size="8" value="<?=$obligacionfdoc;?>" /></td>
<td class="tagForm">Voucher Ingreso:</td>
<?
  $s_obligaciones = "select 
                            * 
					  from 
					        ap_obligaciones 
					  where 
					        CodProveedor='".$fa['CodProveedor']."' and 
							CodTipoDocumento = '".$fa['ObligacionTipoDocumento']."'";
  $q_obligaciones = mysql_query($s_obligaciones) or die ($s_obligaciones.mysql_error());
  $f_obligaciones = mysql_fetch_array($q_obligaciones);
?>
<td><input type="text" name="voucher_ingreso" id="voucher_ingreso" value="<?=$f_obligaciones['Voucher'];?>"/></td>
<td></td>
</tr>
<tr>
   <td class="tagForm">Orden Compra:</td><? list($a,$m,$d)= SPLIT('[-]',$fa['NumeroOrdenFecha']); $fechaOrdenCompra = $d.'-'.$m.'-'.$a;?>
   <td><input type="text" id="orden_compra" name="orden_compra" size="25" value="<?=$fa['NroOrden']?>" style="text-align:right" />
       <input type="text" id="fecha_ordencompra" name="fecha_ordencompra" size="8" value="<?=$fechaOrdenCompra;?>" /></td>
 <td></td>
 <td class="tagForm">Valor Mercado:</td>
 <td><input type="text" name="valor_mercado" id="valor_mercado" value="" style="text-align:right"/></td>
</tr>
<tr>
   <td class="tagForm">Gu&iacute;a Remisi&oacute;n #:</td><? list($a,$m,$d)= SPLIT('[-]',$fa['NumeroGuiaFecha']); $fechaGuiaRemision = $d.'-'.$m.'-'.$a;?>
   <td><input type="text" id="nro_guiaremision" name="nro_guiaremision" size="25" value="<?=$fa['NumeroGuia'];?>" style="text-align:right" />
       <input type="text" id="fecha_guiaremision" name="fecha_guiaremision" size="8" value="<?=$fechaGuiaRemision;?>" /></td>
 <td></td>
 <td class="tagForm">Monto Referencial:</td>
 <td><input type="text" name="monto_referencial" id="monto_referencial" value="" style="text-align:right"/></td>
</tr>
<tr>
   <td class="tagForm">Docum. Almacen:</td>
   <td><input type="text" id="nro_documalmacen" name="nro_documalmacen" size="25" maxlength="15" />
       <input type="text" id="fecha_documalmacen" name="fecha_documalmacen" size="8" maxlength="10" /></td>
</tr>
<tr>
   <td colspan="2" align="left"><u>Informaci&oacute;n Inventario F&iacute;sico</u></td>
</tr>
<tr>
   <td class="tagForm">Fecha Inventario:</td>
   <td><input type="text" name="fecha_inventario" id="fecha_inventario" size="8" value="<?=$fechaIngreso;?>"/></td>
   <td class="tagForm">Observaciones</td>
   <td colspan="3"><textarea name="observacion" id="observacion" rows="2" cols="80"></textarea><input type="hidden" id="clas_public20" name="clas_public20" value="ojo"/></td>
</tr>
</table>
</div>
<!-- ****************************************************** COMIENZO TAB3 ************************************************ -->
<div id="tab3" style="display: none;">
<div style="width:900px; height=15px;" class="divFormCaption">Informaci&oacute;n Contable</div>
<table id="principal" name="principal" width="900" align="center" class="tblForm">
<table id="tabla01" name="table01" width="900" class="tblForm">
<tr>
 <td><input type="button" id="btCatDeprec" name="btCatDeprec" value="Ver Deprec. por Categor&iacute;a" onclick="cargarOpcionVerCategoria2(this.form,'af_listactivosvercategoria.php','BLANK', 'height=600, width=920, left=250, top=50, resizable=no');"/></td>
 <td align="left"><b>Contabilidad</b> <select id="contabilidad" name="contabilidad">
     <option value=""></option>
     <?
      $s_balance = "select * from ac_contabilidades";
	  $q_balance = mysql_query($s_balance) or die ($s_balance.mysql_error());
	  while($f_balance=mysql_fetch_array($q_balance)){
		if($f_balance['CodContabilidad']=='T') 
		 echo"<option value='".$f_balance['CodContabilidad']."' selected>".$f_balance['Descripcion']."</option>";
		else 
		echo"<option value='".$f_balance['CodContabilidad']."' selected>".$f_balance['Descripcion']."</option>";
	  }
	 ?>
     
     </select></td>
 <td align="left"></td>
 <td>Per&iacute;odo <input type="text" id="Periodo" name="Periodo" size="6" value="<?=date("Y-m");?>" readonly/></td>
 <td></td>
 <td width="100"></td>
</tr>
<tr>
 <td><input type="button" id="btDetalles" name="btDetalles" value="Ver Detalles" onclick="cargarOpcionVerDetalles2(this.form,'af_listactivosagregarverdetallesinfcontable.php','BLANK','height=600, width=1100, left=200, top=50, resizable=no');"/></td>
 <td align="left"><b><u>Hist&oacute;rico</u></b></td>
 <td align="center"><b><u>Local</u></b></td>
 <td align="center"><b><u>Hist.Ajustado</u></b></td>
 <td align="center"><b><u>Loc.Ajustado</u></b></td>
</tr>
<tr>
 <td></td>
 <td align="left">Inicio de A&ntilde;o</td>
 <td align="center"><input type="text" id="h_inicioAnoLocal" name="h_inicioAnoLocal" value="" style="text-align:right" onchange="cambioFormatoMonto();"/></td>
 <td align="center"><input type="text" id="h_inicioAnoHistAjust" name="h_inicioAnoHistAjust" value="" style="text-align:right"/></td>
 <td align="center"><input type="text" id="h_inicioAnoLocalAjust" name="h_inicioAnoLocalAjust" value="" style="text-align:right"/></td>
</tr>
<tr>
 <td></td>
 <td align="left">Acum. Mes Anterior</td>
 <td align="center"><input type="text" id="h_acumMesAntLocal" name="h_acumMesAntLocal" value="" style="text-align:right"/></td>
 <td align="center"><input type="text" id="h_acumMesAntHistAjust" name="h_acumMesAntHistAjust" value="" style="text-align:right"/></td>
 <td align="center"><input type="text" id="h_acumMesAntLocalAjust" name="h_acumMesAntLocalAjust" value="" style="text-align:right"/></td>
</tr>
<tr>
 <td></td>
 <td align="left">+ Ajustes Inflaci&oacute;n Mes</td>
 <td align="center"><input type="text" id="h_ajustInfMesLocal" name="h_ajustInfMesLocal" value="" style="text-align:right"/></td>
 <td align="center"><input type="text" id="h_ajustInfMesHistAjust" name="h_ajustInfMesHistAjust" value="" style="text-align:right"/></td>
 <td align="center"><input type="text" id="h_ajustInfMesLocalAjust" name="h_ajustInfMesLocalAjust" value="" style="text-align:right"/></td>
</tr>
<tr>
 <td></td>
 <td align="left">+ Adiciones/-Retiros</td>
 <td align="center"><input type="text" id="h_adicRetLocal" name="h_adicRetLocal" value="" style="text-align:right"/></td>
 <td align="center"><input type="text" id="h_adicRetHistAjust" name="h_adicRetHistAjust" value="" style="text-align:right"/></td>
 <td align="center"><input type="text" id="h_adicRetLocalAjust" name="h_adicRetLocalAjust" value="" style="text-align:right"/></td>
</tr>
<tr>
 <td></td>
 <td align="left"></td>
 <td align="center">---------------------</td>
 <td align="center">---------------------</td>
 <td align="center">---------------------</td>
</tr>
<tr>
 <td></td>
 <td align="left">Monto Inicio Mes</td>
 <td align="center"><input type="text" id="h_montoIniMesLocal" name="h_montoIniMesLocal" value="" style="text-align:right" disabled/></td>
 <td align="center"><input type="text" id="h_montoIniMesHistAjust" name="h_montoIniMesHistAjust" value="" style="text-align:right" disabled/></td>
 <td align="center"><input type="text" id="h_montoIniMesLocalAjust" name="h_montoIniMesLocalAjust" value="" style="text-align:right" disabled/></td>
</tr>
<tr>
 <td></td>
 <td align="left"><b><u>Depreciaci&oacute;n</u></b></td>
 <td align="center"></td>
 <td align="center"></td>
 <td align="center"></td>
</tr>
<tr>
 <td></td>
 <td align="left">Inicio de A&ntilde;o</td>
 <td align="center"><input type="text" id="d_iniAnoLocal" name="d_iniAnoLocal" value="" style="text-align:right"/></td>
 <td align="center"><input type="text" id="d_iniAnoHistAjust" name="d_iniAnoHistAjust" value="" style="text-align:right"/></td>
 <td align="center"><input type="text" id="d_iniAnoLocalAjust" name="d_iniAnoLocalAjust" value="" style="text-align:right"/></td>
</tr>
<tr>
 <td></td>
 <td align="left">Acum. Mes Anterior</td>
 <td align="center"><input type="text" id="d_acumMesAntLocal" name="d_acumMesAntLocal" value="" style="text-align:right"/></td>
 <td align="center"><input type="text" id="d_acumMesAntHistAjust" name="d_acumMesAntHistAjust" value="" style="text-align:right"/></td>
 <td align="center"><input type="text" id="d_acumMesAntLocalAjust" name="d_acumMesAntLocalAjust" value="" style="text-align:right"/></td>
</tr>
<tr>
 <td></td>
 <td align="left">+ Ajust. Inflaci&oacute;n Mes</td>
 <td align="center"><input type="text" id="d_ajustInfMesLocal" name="d_ajustInfMesLocal" value="" style="text-align:right"/></td>
 <td align="center"><input type="text" id="d_ajustInfMesHistAjust" name="d_ajustInfMesHistAjust" value="" style="text-align:right"/></td>
 <td align="center"><input type="text" id="d_ajustInfMesLocalAjust" name="d_ajustInfMesLocalAjust" value="" style="text-align:right"/></td>
</tr>
<tr>
 <td></td>
 <td align="left">[+/-] Ajustes</td>
 <td align="center"><input type="text" id="d_ajusteLocal" name="d_ajusteLocal" value="" style="text-align:right"/></td>
 <td align="center"><input type="text" id="d_ajusteHistAjust" name="d_ajusteHistAjust" value="" style="text-align:right"/></td>
 <td align="center"><input type="text" id="d_ajusteLocalAjust" name="d_ajusteLocalAjust" value="" style="text-align:right"/></td>
</tr>
<tr>
 <td></td>
 <td align="left">Depreciaci&oacute;n Mes</td>
 <td align="center"><input type="text" id="d_depreMesLocal" name="d_depreMesLocal" value="" style="text-align:right"/></td>
 <td align="center"><input type="text" id="d_depreMesHistAjust" name="d_depreMesHistAjust" value="" style="text-align:right"/></td>
 <td align="center"><input type="text" id="d_depreMesLocalAjust" name="d_depreMesLocalAjust" value="" style="text-align:right"/></td>
</tr>
<tr>
 <td></td>
 <td align="left"></td>
 <td align="center">---------------------</td>
 <td align="center">---------------------</td>
 <td align="center">---------------------</td>
</tr>
<tr>
 <td></td>
 <td align="left">Deprec. Acumulada</td>
 <td align="center"><input type="text" id="d_depreAcumLocal" name="d_depreAcumLocal" value="" style="text-align:right" disabled/></td>
 <td align="center"><input type="text" id="d_depreAcumHistAjust" name="d_depreAcumHistAjust" value="" style="text-align:right" disabled/></td>
 <td align="center"><input type="text" id="d_depreAcumLocalAjustado" name="d_depreAcumLocalAjustado" value="" style="text-align:right" disabled/></td>
</tr>
<tr><td height="5"></td></tr>
<tr>
 <td></td>
 <td align="left"><b><u>Informaci&oacute;n Adicional</u></b></td>
 <td align="center"></td>
 <td align="center"></td>
 <td align="center"></td>
</tr>
<tr>
 <td></td>
 <td align="left">Monto Neto</td>
 <td align="center"><input type="text" id="ia_montoNetoLocal" name="ia_montoNetoLocal" value="" style="text-align:right"/></td>
 <td align="center"><input type="text" id="ia_montoNetoHistAjust" name="ia_montoNetoHistAjust" value="" style="text-align:right"/></td>
 <td align="center"><input type="text" id="ia_montoNetoLocalAjust" name="ia_montoNetoLocalAjust" value="" style="text-align:right"/></td>
</tr>
<tr>
 <td></td>
 <td align="left">Depreciaci&oacute;n Anual</td>
 <td align="center"><input type="text" id="ia_depreAnualLocal" name="ia_depreAnualLocal" value="" style="text-align:right"/></td>
 <td align="center"><input type="text" id="ia_depreAnualHistAjust" name="ia_depreAnualHistAjust" value="" style="text-align:right"/></td>
 <td align="center"><input type="text" id="ia_depreAnualLocalAjust" name="ia_depreAnualLocalAjust" value="" style="text-align:right"/></td>
</tr>
<tr>
 <td></td>
 <td align="left">Adiciones/Retiros A&ntilde;o</td>
 <td align="center"><input type="text" id="ia_adiRetAnoLocal" name="ia_adiRetAnoLocal" value="" style="text-align:right"/></td>
 <td align="center"><input type="text" id="ia_adiRetAnoHistAjust" name="ia_adiRetAnoHistAjust" value="" style="text-align:right"/></td>
 <td align="center"><input type="text" id="ia_adiRetAnoLocalAjust" name="ia_adiRetAnoLocalAjust" value="" style="text-align:right"/></td>
</tr>
<tr>
 <td></td>
 <td align="left">Inflaci&oacute;n Anual Hist&oacute;rico</td>
 <td align="center"><input type="text" id="ia_infAnualHistLocal" name="ia_infAnualHistLocal" value="" style="text-align:right"/></td>
 <td align="center"><input type="text" id="ia_infAnualHistAjust" name="ia_infAnualHistAjust" value="" style="text-align:right"/></td>
 <td align="center"><input type="text" id="ia_infAnualHistLocalAjust" name="ia_infAnualHistLocalAjust" value="" style="text-align:right"/></td>
</tr>
<tr>
 <td></td>
 <td align="left">Inflaci&oacute;n Anual Depreciaci&oacute;n</td>
 <td align="center"><input type="text" id="ia_infAnualDepreLocal" name="ia_infAnualDepreLocal" value="" style="text-align:right"/></td>
 <td align="center"><input type="text" id="ia_infAnualDepreHistAjust" name="ia_infAnualDepreHistAjust" value="" style="text-align:center" disabled/></td>
 <td align="center"><input type="text" id="ia_infAnualDepreLocalAjust" name="ia_infAnualDepreLocalAjust" value="" style="text-align:right"/></td>
</tr>
<tr><td height="15"></td></tr>
</table>
</table>
</div>
<!-- ****************************************************** COMIENZO TAB4 ************************************************ -->
<div id="tab4" style="display:none;">
<div style="width:900px;height:15px;" class="divFormCaption">Caracter&iacute;sticas T&eacute;cnicas del Activo</div>
<!--<form name="frmdetalles" id="frmdetalles">-->
<input type="hidden" id="seldetalle" /><input type="hidden" id="seldetalle2" />
<input type="hidden" id="candetalle" /><input type="hidden" id="candetalle2" />

<table width="900" class="tblBotones">
 <tr>
    <td>Caracter&iacute;cas T&eacute;cnicas del Activo</td>
	<td align="right">
    	<input type="button" class="btLista" id="btInsertar" value="Insertar" onclick="insertarLineaCaracTecnicasActivo();" />
        <input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="quitarLinea(document.getElementById('seldetalle').value);" />
	</td>
 </tr>
</table>

<table align="center" cellpadding="0" cellspacing="0">
<tr>
   <td valign="top" style="height:100px; width:150px;">
   <table align="center" width="900"><tr><td align="center">
     <div style="overflow:scroll; height:150px; width:900px;">
     <table width="900" class="tblLista">
     <thead>
     <tr class="trListaHead">
        <th scope="col" width="10">#</th>
        <th scope="col" width="200">Caracter&iacute;stica T&eacute;cnica</th>
        <th scope="col" width="80">Cantidad</th>
        <th scope="col" width="200">Comentario</th>
        <th scope="col" width="200">Observaciones</th>
     </tr>
     </thead>
     <tbody id="listaDetalles">
     </tbody>
   </table>
   </div>
  </td>
</tr>
</table>
</td></tr></table>
<? /// ------------------------------------------------------- /// ?>
<table width="900" class="tblBotones">
 <tr>
	<td>Partes del Activo(Solo para Activos Compuestos)</td>
    <td align="right">
    	<input type="button" class="btLista" id="btInsertar" value="Insertar" onclick="insertarLineaCaracTecnicasActivo2();" />
        <input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="quitarLineaCompuestoActivos(document.getElementById('seldetalle2').value);" />
	</td>
 </tr>
</table>

<table align="center" cellpadding="0" cellspacing="0">
<tr>
   <td valign="top" style="height:100px; width:150px;">
   <table align="center" width="900"><tr><td align="center">
     <div style="overflow:scroll; height:150px; width:900px;">
     <table width="900" class="tblLista">
     <thead>
     <tr class="trListaHead">
        <th scope="col" width="10">#</th>
        <th scope="col" width="200">Tipo de Equipo</th>
        <th scope="col" width="200">Descripci&oacute;n</th>
        <th scope="col" width="200">Marca</th>
        <th scope="col" width="100">N&uacute;mero Serie</th>
        <th scope="col" width="200">Fecha Asignaci&oacute;n</th>
     </tr>
     </thead>
     <tbody id="listaDetalles2">
     </tbody>
   </table>
   </div>
  </td>
</tr>
</table>
</td></tr></table>
<!--</form>-->
</div>
<!-- ****************************************************** COMIENZO TAB5 ************************************************ -->
<div id="tab5" style="display: none;">
<div style="width:900px; height=15px;" class="divFormCaption">Voucher Ingreso</div>
<!--<form id="frmdetalles3" name="frmdetalles3">-->
<input type="hidden" id="seldetalle3" />
<input type="hidden" id="candetalle3" />
<table width="900" class="tblForm">
<? echo"<input type='hidden' id='inform' name='inform' value=''/>
        <input type='hidden' id='monto' name='monto' value='$monto_local'/>";?>
<tr>
  <td class="tagForm">Tipo Transacci&oacute;n:</td>
  <td colspan="3"><select id="tipobaja" name="tipobaja" class="selectSma" onchange="CargarInformacion(this.form, this.id, 'insertarDatos_1')">
       <option value=""></option>
       <?
         $st = "select * from af_tipotransaccion"; //echo $st;
		 $qt = mysql_query($st) or die ($st.mysql_error());
		 $rt = mysql_num_rows($qt);
		 if($rt!=0) 
		 for($i=0; $i<$rt; $i++){
			 $ft= mysql_fetch_array($qt);
			 echo" <option value='".$ft['TipoTransaccion']."' >".$ft['Descripcion']."</option>";		 
		 } 
	   
	   ?>
      </select></td>
</tr>
</table>
<table width="900" class="tblLista">
<tr>
  <td width="50" class="trListaColor"><font color="#FFFFFF">#</font></td>
  <td width="150" class="trListaColor"><font color="#FFFFFF">Cuenta</font></td>
  <td width="450" class="trListaColor"><font color="#FFFFFF">Descripci&oacute;n</font></td>
  <td width="60" class="trListaColor"><font color="#FFFFFF">Monto</font></td>
  <td width="50" class="trListaColor"><font color="#FFFFFF">Signo</font></td>
</tr>
<tr><td colspan="5">
 <div id="resultados" name="resultados" style="width:880px">
 </div>
</td></tr>
</table>
<!--</form>-->
</div>

<center>
 <input type="submit" id="guardar" name="guardar" value="Guardar Registro"/>
 <input type="button" id="cancelar" name="cancelar" value="Cancelar" onclick="window.close();"/>
</center>
</form>

<div style="width:850px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>