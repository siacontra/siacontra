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
<!--<link href="css1.css" rel="stylesheet" type="text/css" />-->
<link href="css2.css" rel="stylesheet" type="text/css" />
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../css/custom-theme/jquery-ui-1.8.16.custom.css" charset="utf-8" />
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
<div id="cajaModal"></div>
<!-- pretty -->
<span class="gallery clearfix"></span>
<?

list($organismo, $activo )=SPLIT('[|]',$_GET['registro']);
//// CONSULTA PRINCIPAL
$sa = "select * from af_activo where CodOrganismo = '$organismo' and Activo='$activo'";
$qa = mysql_query($sa) or die ($sa.mysql_error()); //echo $sa;
$ra = mysql_num_rows($qa); 

if($ra!='0')$fa=mysql_fetch_array($qa);
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Aprobar | Activo Menor</td>
		<td align="right"><a class="cerrar" href=";" onclick="window.close();">[cerrar]</a></td>
	</tr>
</table>
<hr width="100%" color="#333333" />
<form id="frmentrada" name="frmentrada" action="af_activosmenoresagregar.php?limit=0"  onsubmit="return AprobarActivo(this);">
<? echo"<input type='hidden' id='registro' name='registro' value='".$registro."'/>";?>
<table width="908" align="center">
<tr>
  <td>
	<div id="header">
	<ul>
	<!-- CSS Tabs PESTA�AS OPCIONES -->
	<li><a onClick="document.getElementById('tab1').style.display='block'; 
    document.getElementById('tab2').style.display='none';  
    document.getElementById('tab3').style.display='none';" href="#">Informaci&oacute;n General</a></li>
	<li><a onClick="document.getElementById('tab1').style.display='none'; 
    document.getElementById('tab2').style.display='block'; 
    document.getElementById('tab3').style.display='none';" href="#">Informaci&oacute;n Adicional</a></li> 
    <li><a onclick="document.getElementById('tab1').style.display='none'; 
    document.getElementById('tab2').style.display='none'; 
    document.getElementById('tab3').style.display='block';" href="#">Componentes de un Archivo</a></i>
	</ul>
	</div>
  </td>
</tr>
</table>
<? echo" <input type='hidden' id='regresar' name='regresar' value='".$_GET['regresar']."' />
         <input type='hidden' id='activo' name='activo' value='' />";?>
<!-- ****************************************************** COMIENZO TAB1 ************************************************ -->
<div id="tab1" style="display: block;">
<div style="width:900px; height=15px;" class="divFormCaption">Informaci&oacute;n General</div>
<table class="tblForm" width="900">
<tr>
   <td class="tagForm">Activo #:</td>
   <td><input type="text" id="nro_activo" name="nro_activo" size="30" value="<?=$fa['Activo'];?>" disabled style="text-align:right"/></td>
   <td></td>
</tr>
<tr>
  <td class="tagForm">Descrip. Local:</td>
  <td colspan="3"><textarea id="descripcionLocal" name="descripcionLocal" cols="160" rows="4" readonly><?=$fa['Descripcion']; ?></textarea><!--<input type="button" id="btCargar" name="btCargar" value="..." onclick="cargarVentanaLista(this.form, 'af_listactivoslogistica.php?limit=0&campo=2','height=500, width=820, left=200, top=100, resizable=yes');"/>--></td>
  <td></td>
</tr>
<tr>
  <td class="tagForm">Descrip. Corta:</td>
  <td><input type="text" name="descripcionCorta" id="descripcionCorta" size="164" value="<?=$fa['DescpCorta'];?>" disabled/></td>
</tr>
</table>

<table class="tblForm" width="900">
<tr>
   <td class="tagForm">Situaci&oacute;n Activo:</td>
   <td>
       <select id="situacion_activo" class="selectMed" disabled>
        
        <? $s_activo = "select * from af_situacionactivo";
           $q_activo = mysql_query($s_activo) or die ($s_activo.mysql_error());
           $r_activo = mysql_num_rows($q_activo);
          
           if($r_activo!='0'){
		    for($i=0;$i<$r_activo;$i++){
               $f_activo = mysql_fetch_array($q_activo);
			   if($f_activo['CodSituActivo']==$fa['SituacionActivo'])
			      echo"<option value='".$f_activo['CodSituActivo']."' selected>".$f_activo['Descripcion']."</option>";
			   else
			     echo"<option value='".$f_activo['CodSituActivo']."'>".$f_activo['Descripcion']."</option>";	
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
 <td class="tagForm">Concep. de Movimiento:</td>
   <td>
       <select id="conceptoMovimiento" name="conceptoMovimiento" class="selectMed" disabled>
        <? $s_cm = "select * from af_tipomovimientos";
           $q_cm = mysql_query($s_cm) or die ($s_cm.mysql_error());
           $r_cm = mysql_num_rows($q_cm);
          
           if($r_cm!='0'){
		    for($i=0;$i<$r_cm;$i++){
               $f_cm = mysql_fetch_array($q_cm);
			   if($f_cm['CodTipoMovimiento']==$fa['CodTipoMovimiento'])
			     echo"<option value='".$f_cm['CodTipoMovimiento']."' selected>".htmlentities($f_cm['DescpMovimiento'])."</option>";
			   else
			     echo"<option value='".$f_cm['CodTipoMovimiento']."'>".htmlentities($f_cm['DescpMovimiento'])."</option>";
            }
		   }
        ?>         
       </select></td>  
       <td class="tagForm">Dependencia</td>
   <td><select id="dependencia" name="dependencia" class="selectBig" disabled>
         <?
          $s_dep = "select * from mastdependencias where CodOrganismo = '$organismo' and CodDependencia='".$fa['CodDependencia']."'";
          $q_dep = mysql_query($s_dep) or die ($s_dep.mysql_error()); //echo $s_dep;
           $r_dep = mysql_num_rows($q_dep);
  
		  if($r_dep!='0'){
			for($i=0;$i<$r_dep;$i++){
			   $f_dep = mysql_fetch_array($q_dep);
			   echo"<option value='".$f_dep['CodDependencia']."'>".$f_dep['Dependencia']."</option>"; 
			}
		  }
		 ?>
       </select></td> 
</tr>
<tr>
   <td class="tagForm">C&oacute;digo Interno:</td>
   <td><input type="text" id="codigo_interno" name="codigo_interno" size="30" style="text-align:right"  maxlength="10" value="<?=$fa['CodigoInterno'];?>" disabled/></td>
   
</tr>
<tr>
   <td class="tagForm">Naturaleza:</td><? 
           if($fa['Naturaleza']=='AM') $parametro = 'Activo Menor'; else $parametro = 'Activo Normal';
		  ?>
   <td><input type="text" id="naturaleza" name="naturaleza" size="30" value="<?=$parametro;?>" disabled/></td>
   <td class="tagForm"></td>
   <td width="300"></td>
   <td></td>
</tr>
<tr>
 <td></td>
 <td></td>
 <td class="tagForm">Categor&iacute;a:</td>
 <td>
    <select id="select_categoria" style="width:72px" onchange="cargarCampoCategoria(this.id)" disabled>
                   <option value=""></option>
                   <?
                   $s_categoria = "select * from af_categoriadeprec";
				   $q_categoria = mysql_query($s_categoria) or die ($s_categoria.mysql_error());
				   $r_categoria = mysql_num_rows($q_categoria);
				   if($r_categoria!=0){
					   for($i=0;$i<$r_categoria;$i++){
						  $f_categoria = mysql_fetch_array($q_categoria);
						  if($f_categoria['CodCategoria']==$fa['Categoria']){
						     $descpLocal = $f_categoria['DescripcionLocal'];
						     echo"<option value='".$f_categoria['CodCategoria']."' selected>".$f_categoria['CodCategoria']."</option>";
						  }else echo"<option value='".$f_categoria['CodCategoria']."'>".$f_categoria['CodCategoria']."</option>";
					   }
					}
				   ?>
                   </select>
     <input type="text" id="categoria" name="categoria" size="50" value="<?=$descpLocal;?>" disabled/></td>
</tr>
<tr>
 <td></td>
 <td></td>
 <td class="tagForm">Clasificaci&oacute;n20:</td><?
                   $s_c20 = "select CodClasificacion,Descripcion from af_clasificacionactivo20 where CodClasificacion='".$fa['ClasificacionPublic20']."'";
				   $q_c20 = mysql_query($s_c20) or die ($s_c20.mysql_error());
				   $r_c20 = mysql_num_rows($q_c20);
				   if($r_c20)$f_c20 = mysql_fetch_array($q_c20);
				   ?>
   <td><input type="hidden" id="clasificacion20" name="clasificacion20" disabled="true" value="<?=$f_c20['CodClasificacion'];?>"/><input type="text" id="clasificacion20Descp" name="clasificacion20Descp" size="67" value="<?=$f_c20['Descripcion'];?>" disabled="true"/></td>
   <td class="gallery clearfix"><input type="hidden" name="btClasificacion20" id="btClasificacio20" value="..." onclick="cargarVentanaLista(this.form, 'af_listadoclasificacion20.php?limit=0&campo=1&ventana=insertarClasificacionPub20','height=500, width=800, left=200, top=100, resizable=yes');" disabled/></td>
</tr>
<tr>
   <td class="tagForm"></td>
   <td></td>
   <td class="tagForm">Clasificaci&oacute;n:</td>
<?
$s_clactivo = "select * from af_clasificacionactivo where CodClasificacion='".$fa['Clasificacion']."'";
$q_clactivo = mysql_query($s_clactivo) or die ($s_clactivo.mysql_error());
$f_clactivo = mysql_fetch_array($q_clactivo);
?>
   <td><input type="text" id="clasificacion" name="clasificacion" size="8" value="<?=$f_clactivo['CodClasificacion'];?>" disabled/>
       <input type="text" id="clasificacion2" name="clasificacion2" size="51" value="<?=$f_clactivo['Descripcion'];?>" disabled/></td>
   <td><input type="hidden" name="btClasificacion" id="btClasificacion" value="..." onclick="cargarVentanaLista(this.form, 'af_listaclasificacionactivo.php?limit=0&campo=1','height=500, width=800, left=200, top=100, resizable=yes');" disabled/></td>
</tr>
<tr>
   <td class="tagForm">
   <? if($fa['FlagParaOperaciones']=='S'){?>
     <input type="checkbox" name="disp_operaciones" id="disp_operaciones" value="N" checked="checked" onclick="this.checked=true;"/>
   <? }else{ ?>
     <input type="checkbox" name="disp_operaciones" id="disp_operaciones" value="N" disabled/>
    <? }?></td>
   <td> Disponible Para Operaciones</td>
   <td class="tagForm">Ubicaci&oacute;n:</td>
<?
 $subic = "select * from af_ubicaciones where CodUbicacion = '".$fa['Ubicacion']."'";
 $qubic = mysql_query($subic) or die ($subic.mysql_error());
 $rubic = mysql_num_rows($qubic);
 if($rubic!=0) $fubic = mysql_fetch_array($qubic);
?>
   <td><input type="text" name="ubicacion" id="ubicacion" size="8" value="<?=$fubic['CodUbicacion'];?>" disabled/>
       <input type="text" name="ubicacion2" id="ubicacion2" size="51" value="<?=htmlentities($fubic['Descripcion']);?>" disabled/></td>
   <td><input type="hidden" name="btUbicacion" id="btUbicacion" value="..." onclick="cargarVentanaLista(this.form, 'af_listaubicacionesactivo.php?limit=0&campo=2','height=500, width=800, left=200, top=100, resizable=yes');" disabled/></td>
</tr>
<tr>
   <td class="tagForm"></td>
   <td></td>
   <?
     $sac = "select * from af_activo where Activo='".$fa['ActivoConsolidado']."'";
	 $qac = mysql_query($sac) or die ($sac.mysql_error());
	 $rac = mysql_num_rows($qac);
	 if($rac!=0)$fac=mysql_fetch_array($qac);
   ?>
   <td class="tagForm">Activo Consolidado</td>
   <td><input type="hidden" name="activo_consolidado" id="activo_consolidado" value="<?=$fac['Activo'];?>" disabled/><input type="text" name="activo_consolidado2" id="activo_consolidado2" size="67" value="<?=$fac['Descripcion'];?>" disabled/></td>
   <td><input type="hidden" name="btActivop" id="btActivop" value="..." onclick="cargarVentanaLista(this.form, 'af_listaactivosfijos.php?limit=0&campo=19','height=500, width=800, left=200,top=100,resizable=yes');" disabled/></td>
</tr>
<tr>
   <td class="tagForm"></td>
   <td>&nbsp;</td>
   <td class="tagForm" width="150"><u>Responsables del Activo</u></td>
   <td></td>
   <td></td>
</tr>
<tr>
<?
 $scc = "select CodCentroCosto,Descripcion from ac_mastcentrocosto where CodCentroCosto='".$fa['CentroCosto']."'";
 $qcc = mysql_query($scc) or die ($scc.mysql_error());
 $rcc = mysql_num_rows($qcc);
 if($rcc!=0) $fcc = mysql_fetch_array($qcc);

?>
   <td class="tagForm">Estado:</td>
   <td width="245">
   <? if($fa['Estado']=='PE'){ ?>
    <input type="radio" id="radio1" name="radio1" checked/>Pendiente <input type="radio" name="radio2" id="radio2" disabled/>Activado
   <? }else{?>
    <input type="radio" id="radio1" name="radio1" disabled/>Pendiente <input type="radio" name="radio2" id="radio2" checked/>Activado
    <? }?></td>
   <td class="tagForm" width="150">Centro Costos:</td>
   <td><input type="text" id="centro_costos" name="centro_costos" size="8" value="<?=$fcc['CodCentroCosto'];?>" disabled/>
       <input type="text" id="centro_costos2" name="centro_costos2" size="51" value="<?=htmlentities($fcc['Descripcion']);?>" disabled/></td>
   <td><input type="hidden" name="btCentroCostos" id="btCentroCostos" value="..." onclick="cargarVentanaLista(this.form,'af_listacentroscostos.php?limit=0&campo=9','height=500,width=800,left=200,top=100,resizable=yes');" disabled/></td>
</tr>
<tr>
<?
 $sur = "select
				b.CodPersona as CodPersonaEmpResp,
				b.NomCompleto as NombreEmpResp,
				c.CodPersona as CodPersonaEmpUsuario,
				c.NomCompleto as NombreEmpUsuario
		   from 
		        af_activo a
		        inner join mastpersonas b on (b.CodPersona = a.EmpleadoResponsable) 
				inner join mastpersonas c on (c.CodPersona = a.EmpleadoUsuario) 
		  where 
		        a.Activo='".$fa['Activo']."'  and 
				a.CodOrganismo = '".$fa['CodOrganismo']."'";
 $qur = mysql_query($sur) or die ($sur.mysql_error());
 $rur = mysql_num_rows($qur);
 if($rur!=0)$fsur=mysql_fetch_array($qur);
?>
   <td class="tagForm"></td>
   <td></td>
   <td class="tagForm">Empleado Responsable:</td>
   <td><input type="hidden" name="cod_responsable" id="cod_responsable" value="<?=$fsur['CodPersonaEmpResp'];?>" /><input type="text" id="nomb_responsable" name="nomb_responsable" size="67" value="<?=$fsur['NombreEmpResp'];?>" disabled/></td>
   <td><input type="hidden" name="btEmpleado" id="btEmpleado" value="..." onclick="cargarVentanaLista(this.form,'af_listaempleados.php?limit=0&campo=7','height=500, width=800,left=200,top=100,resizable=yes');" disabled/></td>
</tr>
<tr>
 <td class="tagForm"></td>
 <td></td>
 <td class="tagForm" width="134">Empleado Usuario:</td>
   <td><input type="hidden" name="cod_usuario" id="cod_usuario" value="<?=$fsur['CodPersonaEmpUsuario'];?>" /><input type="text" id="nomb_usuario" name="nomb_usuario" size="67" value="<?=$fsur['NombreEmpUsuario'];?>" disabled/></td>
   <td><input type="hidden" name="btEmpleado" id="btEmpleado" value="..." onclick="cargarVentanaLista(this.form,'af_listaempleados.php?limit=0&campo=7','height=500, width=800,left=200,top=100,resizable=yes');" disabled/></td>
</tr>
<tr>
   <td colspan="2"></td>
   <td class="tagForm"></td>
   <td></td>
   <td></td>
</tr>
<? 
$s_usuario = "select 
				   mp.NomCompleto,
				   mp.CodPersona 
			  from 
				   usuarios u 
				   inner join mastpersonas mp on (mp.CodPersona = u.CodPersona)
			 where 
				   u.Usuario = '".$_SESSION['USUARIO_ACTUAL']."'";
$q_usuario = mysql_query($s_usuario) or die ($s_usuario.mysql_error());
$f_usuario = mysql_fetch_array($q_usuario);
echo"<input='hidden' id='cod_prepor' nanme='cod_prepor' value='".$f_usuario['CodPersona']."' />";					 
?>
<tr><td align="center" colspan="5">Ultima Modif.:<input type="text" name="ultimo_usuario" value="<?=$fa['UltimoUsuario'];?>" size="25" disabled/><input type="text" name="ultima_fecha" value="<?=$fa['UltimaFechaModif'];?>" size="20" disabled/></td></tr>
</table>
</div>
<!-- ****************************************************** COMIENZO TAB2 ************************************************ -->
<div id="tab2" style="display: none;">
<div style="width:900px; height=15px;" class="divFormCaption">Informaci&oacute;n Adicional</div>
<table class="tblForm" width="900">
<tr>
  <td width="137" class="tagForm"><u>Informaci&oacute;n del Activo</u></td>
  <td></td>
  <td width="33"></td>
  <td class="tagForm"><u>Informaci&oacute;n Documentaria</u></td>
</tr>
<tr>
<?
/*$scon2 = "select Descripcion as DescpMarcas from lg_marcas  where CodMarca = '".$fa['CodMarca']."'";
$qcon2= mysql_query($scon2) or die ($scon2.mysql_error());
$fcon2= mysql_fetch_array($qcon2);*/
?>
  <td class="tagForm">Fabricante(Marca):</td>
  <td width="183"><input type="text" name="fabricante" id="fabricante" size="40" value="<?=$fa['Marca'];?>" disabled/><input type="hidden" name="fabricante2" id="fabricante2" size="40" value="<?=$fcon2['DescpMarcas'];?>" disabled/></td>
  <td></td>
  <td width="162" class="tagForm">Proveedor:</td>
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
 <td width="361" colspan="2"><input type="text" id="proveedor" name="proveedor" size="8" value="<?=$f_proveedor['codProveedor'];?>" disabled/><input type="text" id="nomb_proveedor" name="nomb_proveedor" size="48" value="<?=$f_proveedor['NombProveedor'];?>" disabled/>
                  <input type="button" id="btProveedor" name="btProveedor" value="..." onclick="cargarVentanaLista(this.form, 'af_listaproveedor.php?limit=0&campo=1','height=500, width=800, left=200,top=100,resizable=yes');" disabled/></td>
</tr>
<tr>
  <td class="tagForm">Modelo:</td>
  <td><input type="text" name="modelo" id="modelo" size="40" value="<?=$fa['Modelo'];?>" disabled/></td><td></td>
  <td class="tagForm">Factura:</td>
  <td colspan="2"><select id="factura" name="factura" style="width:100px;" disabled>
<?
$sfact = "select * from ap_tipodocumento";
$qfact = mysql_query($sfact) or die ($sfact.mysql_error());
$rfact = mysql_num_rows($qfact);
if($rfact!=0){
for($i=0; $i<$rfact; $i++){
  $ffact = mysql_fetch_array($qfact);
  if($ffact['CodTipoDocumento']==$fa['FacturaTipoDocumento'])
   echo"<option value='".$ffact['CodTipoDocumento']."' selected>".$ffact['Descripcion']."</option>";
  else 
   echo"<option value='".$ffact['CodTipoDocumento']."'>".$ffact['Descripcion']."</option>";
}
}

list($fano, $fmes, $fdia) = split('[-]',$fa['FacturaFecha']);
$fechaFactura = $fdia.'-'.$fmes.'-'.$fano;
?>
</select><input type="text" id="num_factura" name="num_factura" size="30" value="<?=$fa['FacturaNumeroDocumento'];?>" disabled/><input type="text" id="fecha_factura" name="fecha_factura" size="8" maxlength="10" value="<?=$fechaFactura?>" disabled/></td>
</tr>
<tr>
  <td class="tagForm">N&uacute;mero de Serie:</td>
  <td><input type="text" name="nro_serie" id="nro_serie" size="40" value="<?=$fa['NumeroSerie'];?>" disabled/></td><td></td>
  <td class="tagForm">Orden Compra:</td>
<? 
   list($a,$m,$d)= SPLIT('[-]',$fa['NumeroOrdenFecha']);$fechaOrdenCompra = $d.'-'.$m.'-'.$a;
?>
   <td><input type="text" id="orden_compra" name="orden_compra" size="25" value="<?=$fa['NumeroOrden'];?>" maxlength="15" disabled/><input type="text" id="fecha_ordencompra" name="fecha_ordencompra" size="8" maxlength="10" value="<?=$fechaOrdenCompra;?>" disabled/></td>
</tr>
<tr>
  <td class="tagForm">Color:</td>
  <td><select id="color" class="selectMed" disabled>
      <option value=""></option>
       <?
       $s_color = "select * from mastmiscelaneosdet where CodMaestro='COLOR'";
	   $q_color = mysql_query($s_color) or die ($s_color.mysql_error());
	   
	   while($f_color = mysql_fetch_array($q_color)){
		  if ($f_color['CodDetalle']==$fa['Color']) 
		    echo"<option value='".$f_color['CodDetalle']."' selected>".$f_color['Descripcion']."</option>";
		  else 
		    echo"<option value='".$f_color['CodDetalle']."'>".$f_color['Descripcion']."</option>";
	   }
	  ?>
      </select></td><td></td>
  <td class="tagForm">Gu&iacute;a Remisi&oacute;n #:</td>
  <td><input type="text" id="nro_guiaremision" name="nro_guiaremision" size="25" value="<?=$fa['NumeroGuia'];?>" maxlength="15" disabled/><input type="text" id="fecha_guiaremision" name="fecha_guiaremision" size="8" maxlength="10" value="00-00-0000" disabled/></td>
</tr>
<tr>
  <td class="tagForm">C&oacute;digo de Barras:</td>
  <td><input type="text" name="codigo_barras" id="codigo_barras" size="40" value="<?=$fa['CodigoBarras'];?>" disabled/></td><td></td>
  <td class="tagForm"></td>
  <td></td>
</tr>
<tr>
  <td class="tagForm">Medida:</td>
  <td><input type="text" name="medida" id="medida" size="40" value="<?=$fa['Dimensiones'];?>" disabled/></td><td></td>
  <td class="tagForm"><u>Informaci&oacute;n Monetaria</u></td>
  <td></td>
</tr>
<tr>
  <td class="tagForm">Pa&iacute;s de Fabricaci&oacute;n:</td>
  <td><select id="pais_fabricacion" class="selectMed" disabled>
<?
 $spaises = "select * from mastpaises";
 $qpaises = mysql_query($spaises) or die ($spaises.mysql_error());
 $rpaises = mysql_num_rows($qpaises);
 
 for($i=0;$i<$rpaises;$i++){
   $fpaises = mysql_fetch_array($qpaises);
   if($fpaises['CodPais']==$fa['FabricacionPais'])echo"<option value='".$fpaises['CodPais']."' selected>".$fpaises['Pais']."</option>";
 }

 $monto_local = number_format($fa['MontoLocal'],2,',','.');
?>
  </select></td>
  <td></td>
  <td class="tagForm">Monto Local:</td>
  <td><input type="text" id="monto_local" name="monto_local" style="text-align:right" value="<?=$monto_local;?>" disabled/>Bs.F</td>
</tr>
<tr>
  <td class="tagForm">A&ntilde;o de Fabricaci&oacute;n:</td>
  <td><input type="text" id="ano_fabricacion" name="ano_fabricacion"  size="8" style="text-align: right;" value="<?=$fa['FabricacionAno'];?>" disabled/></td>
</tr>
<tr>
 <td class="tagForm"></td>
 <td></td>
</tr>
<tr>
 <td class="tagForm">Fecha de Ingreso:</td>
 <?
  list($fia, $fim, $fid) = split('[-]',$fa['FechaIngreso']); $fecha_ingreso = $fid.'-'.$fim.'-'.$fia;
 ?>
 <td><input type="text" name="fecha_ingreso" id="fecha_ingreso" size="8" value="<?=$fecha_ingreso;?>" disabled/></td>
</tr>
<tr>
 <td class="tagForm">Periodo Registro:</td>
 <td><input type="text" name="periodo_registro" id="periodo_registro" size="8" value="<?=$fa['PeriodoIngreso'];?>" disabled/></td>
</tr>
</table>
</div>
<!-- ****************************************************** COMIENZO TAB3 ************************************************ -->
<div id="tab3" style="display: none;">
<div style="width:900px; height=15px;" class="divFormCaption">Componentes de un Archivo</div>
<table id="principal" name="principal" width="900" align="center" class="tblForm">
</table>
</div>
<center><input type="submit" name="btGuardar" id="btGuardar" value="Aprobar"/><input type="button" name="btCancelar" id="btCancelar" value="Cancelar" onclick="window.close();"/></center>
</form>
