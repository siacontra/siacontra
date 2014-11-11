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
<!--<link href="css1.css" rel="stylesheet" type="text/css" />-->
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript_02.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript01.js"></script>
<script type="text/javascript" src="../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/fscript.js" charset="utf-8"></script>
</head>
<body>
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<table width="100%" cellspacing="0" cellpadding="0">
 <tr>
  <td class="titulo">Reporte | Inventario Activos Costo</td>
  <td align="right">
   <a class="cerrar" href="framemain.php">[cerrar]</a>
  </td>
 </tr>
</table>
<hr width="100%" color="#333333" />
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<?php 
if(!$_POST) $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
$MAXLIMIT=30;
$filtro = "";
if ($forganismo!=""){$filtro.= " AND (CodOrganismo = '".$forganismo."')"; $corganismo = "checked"; } else $dorganismo = "disabled";
if($fDependencia!=""){$filtro.="AND (CodDependencia='".$fDependencia."')"; $cDependencia="checked";}else $dDependencia="disabled";
if($centro_costos != ""){ $filtro .= " AND (CentroCosto = '".$centro_costos."')"; $cCosto = "checked"; } else $dCosto = "disabled";
if($fClasificacion != ""){ $filtro .="AND (Clasificacion = '".$fClasificacion."')"; $cCatClasf = "checked"; } else $dCatClasf = "disabled";
if($fCodclasficacionPub20!=""){$filtro.=" AND (ClasificacionPublic20 = '".$fCodclasficacionPub20."')"; $cClasficacionPub20 = "checked";} else $dClasficacionPub20 = "disabled";
if($fEstado!=""){$filtro.="AND (Estado ='".$fEstado."')"; $cEstado = "checked";} else $dEstado = "disabled";
if($fubicacion!= "") { $filtro .= " AND (Ubicacion = '".$fubicacion."')"; $cUbicacion = "checked"; } else $dUbicacion = "disabled";
if($fSituacionActivo != "") { $filtro .= " AND (SituacionActivo = '".$fSituacionActivo."')"; $cSituacionActivo = "checked"; } else $dSituacionActivo = "disabled";
if($fBienes!=""){$filtro.=" AND (ClasificacionPublic20 LIKE '".$fBienes."'%)"; $cBienes="checked";}else $dBienes="disabled";

?>
<? echo"
<form name='frmentrada' action='af_rpinventarioactivoscosto.php?limit=0' method='POST'>
<input type='hidden' name='limit' id='limit' value='".$limit."'>
<input type='hidden' name='registros' id='registros' value='".$registros."'/>
<input type='hidden' name='usuarioActual' id='usuarioActual' value='".$_SESSION['USUARIO_ACTUAL']."'/>

<div class='divBorder' style='width:1100px;'>
<table width='1100' class='tblFiltro'>
<tr>
<td class='tagForm'>Organismo:</td>
<td>
<input type='checkbox' name='chkorganismo' id='chkorganismo' value='1' $corganismo onclick='this.checked=true' />
<select name='forganismo' id='forganismo' class='selectBig' $dorganismo onchange='getFOptions_2(this.id, \"fanteproyecto\", \"chknanteproyecto\");'>";
	getOrganismos($obj[2], 3, $_SESSION[ORGANISMO_ACTUAL]);
	echo "
</select>
</td>
<td align='right'>Cat/Clasf.:</td>
<td class='gallery clearfix'><input type='checkbox' id='chkCatClasf' name='chkCatClasf' value='1' $cCatClasf onclick='enabledCatClasf(this.form);'/>
	<input type='text' id='fCatClasf' name='fCatClasf' value='$fCatClasf' $dCatClasf style='width:85px;' readonly/> 
	<input type='hidden' name='fCodCatClasf' id='fCodCatClasf' value='$fCodCatClasf'/>
	<input type='hidden' id='fClasificacion' name='fClasificacion' value='$fClasificacion'/>
	<input type='text' id='DescpClasificacion' name='DescpClasificacion' value='$DescpClasificacion' $dCatClasf size='16' readonly/>";?><input type='hidden' id='btClasif' name='btClasif' value='...' onclick="cargarVentanaLista(this.form, 'af_listaclasificacionactivo.php?limit=0&campo=17&ventana=insertarClasificacionActivo','height=500, width=800, left=200, top=100, resizable=yes');" <?=$dCatClasf?>/> 
    <a id="clasificacion" href="af_listaclasificacionactivo.php?filtrar=default&limit=0&campo=17&ventana=insertarClasificacionActivo&iframe=true&width=70%&height=100%" rel="prettyPhoto[iframe2]" style="visibility:hidden">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;"/>
            </a>
	 <? echo"</td>
<td align='right'>Ubicaci&oacute;n:</td>
	   <td class='gallery clearfix'><input type='checkbox' name='checkUbicacion' id='checkUbicacion' value='1' $cUbicacion onclick='enabledUbicacionListaActivos(this.form);'/> <input type='hidden' name='fubicacion' id='fubicacion' value='$fubicacion'/><input type='text' name='fubicacion2' id='fubicacion2' value='$fubicacion2' $dUbicacion size='40'/>";?><input type="hidden" name="btUbicacion" id="btUbicacion" value="..." onclick="cargarVentanaLista(this.form, 'af_listaubicacionesactivo.php?limit=0&campo=2','height=500, width=800, left=200, top=100, resizable=yes');" <?=$dUbicacion?>/> 
       <a id="ubicacion" href="af_listaubicacionesactivo.php?filtrar=default&limit=0&campo=27&iframe=true&width=70%&height=100%" rel="prettyPhoto[iframe3]" style="visibility:hidden">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;"/>
            </a>
	   <? echo" </select></td>
</tr>

<tr>
  <td class='tagForm'>Dependencia:</td>
  <td><input type='checkbox' id='checkDependencia' name='checkDependencia' value='1' $cDependencia onclick='enabledDependencia(this.form);'/>
      <select id='fDependencia' name='fDependencia' $dDependencia class='selectBig'>
	     <option value=''></option>";
		   getDependencias($fDependencia, $forganismo, 2);
		 echo"</td>
  <td align='right'>Clasf.Pub20:</td>
  <td class='gallery clearfix'>
      <input type='checkbox' name='chkclasificacionpub20' id='chkclasificacionpub20' value='1' $cClasficacionPub20 onclick='enabledClasificacionPub20(this.form);'/>
	  <input type='text' id='fClasificacionPub20' name='fClasificacionPub20' value='$fClasificacionPub20' size='41' $dClasficacionPub20><input type='hidden' id='fCodclasficacionPub20' name='fCodclasficacionPub20' value='$fCodclasficacionPub20'/>";?><input type='hidden' id='btClasifPub20' name='btClasifPub20' value='...' onclick="cargarVentanaLista(this.form,'af_listaclasificacionPub20.php?limit=0&campo=2&ventana=insertarClasificacionPub20','height=500, width=800, left=200, top=100, resizable=yes');" <?=$dClasficacionPub20?> /> 
	  <a id="clasificacion20" href="af_listadoclasificacion20.php?filtrar=default&limit=0&campo=2&ventana=insertarClasificacionPub20&iframe=true&width=70%&height=100%" rel="prettyPhoto[iframe4]" style="visibility:hidden">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;"/>
            </a><? echo" </td>	
  <td align='right'>Situaci&oacute;n:</td>
  <td><input type='checkbox' id='checkSituacionActivo' name='checkSituacionActivo' value='1' $cSituacionActivo onclick='enabledSituacionActivo(this.form);'/><select name='fSituacionActivo' id='fSituacionActivo' class='selectMed' $dSituacionActivo>
	   <option value=''></option>";
		 getSituacionActivo($fSituacionActivo, 0);
	   echo"
	   </select>
    </td>	 
</tr>

<tr>
  <td align='right'>Centro Costo:</td>
	   <td class='gallery clearfix'><input type='checkbox' name='chekcCosto' id='checkCosto' value='1' $cCosto onclick='enabledCosto(this.form);'/>
		   <input type='hidden' id='centro_costos' value='$centro_costos' name='centro_costos'/>
		   <input type='text' id='centro_costos2' value='$centro_costos2' name='centro_costos2' $dCosto size='67'/>";?><input type='hidden' id='btcosto' name='btcosto' value='...' onclick="cargarVentanaLista(this.form,'af_listacentroscostos.php?limit=0&campo=9','height=500,width=800,left=200,top=100,resizable=yes');"  <?=$dCosto?> /> <a id="c_costo" style="visibility:hidden" href="af_listacentroscostos.php?filtrar=default&limit=0&campo=9&iframe=true&width=70%&height=100%" rel="prettyPhoto[iframe5]">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;"/>
            </a>
	 <?  echo" </select></td>
  <td align='right'>Estado:</td> 
  <td><input type='checkbox' name='chkEstado' id='chkEstado' value='1' $cEstado onclick='enabledEstadoActivos(this.form);'/>
      <select id='fEstado' name='fEstado' $dEstado>
	   <option value=''></option>";
	     getEstadoListActivo2($fEstado,0);
	   echo"</select></td>
  <td align='right'>Bienes:</td>
  <td><input type='checkbox' id='chkBienes' name='chkBienes' value='1' $cBienes onclick='enabledBienes(this.form);'/>
      <select id='fBienes' name='fBienes' class='selectMed' $dBienes>
	   <option></option>";
	      getBienes($fBienes, 0);
	  echo " </select></td>	   
</tr>
</table>
</div>
<center><input type='submit' name='btBuscar' value='Buscar' onclick='cargarInventarioActivosLista(this.form);'></center>
<br /><div style='width:900;' class='divDivision'>Inventario Activos Costo</div>
<form/><br />";
?>
<table width="900" class="tblBotones">
<tr>
<td><div id="rows"></div></td>
<td width="250">
<?php 
//echo"<input type='hidden' name='regresar' id='regresar' value='cpi_docinternoslista'/>";
?>
		</td>
		<td align="right">
<!--<input type="button" id="btEjecutar" name="btejecutar"  value="Ejecutar Cierre" onclick="ProcesoEjecutarCierre(this.form);"/>-->
		</td>
	</tr>
</table>
<input type="hidden" name="registro" id="registro" />
<div style="width:900px" class="divFormCaption"></div>
<center>
<iframe name="af_rpinventarioactivoscostopdf" id="af_rpinventarioactivoscostopdf" style="border:solid 1px #CDCDCD; width:900px; height:400px; visibility:false; display:false;" ></iframe>
</center>
<form/>
</body>
</html>
