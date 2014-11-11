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
<script type="text/javascript" src="../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript_02.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript01.js"></script>
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
<!--////////////////////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@///////////////////////////-->
<table width="100%" cellspacing="0" cellpadding="0">
 <tr>
  <td class="titulo">Reporte | Movimiento de Activos</td>
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

//if($fClasificacion != ""){ $filtro .="AND (Clasificacion='".$fClasificacion."')"; $cCatClasf = "checked"; } else $dCatClasf = "disabled";
//if($fCodclasficacionPub20!=""){$filtro.=" AND (ClasificacionPublic20='".$fCodclasficacionPub20."')"; $cClasficacionPub20 = "checked";} else $dClasficacionPub20 = "disabled";
//if($fEstado!=""){$filtro.="AND (Estado ='".$fEstado."')"; $cEstado = "checked";} else $dEstado = "disabled";
if($ubicacion!= "") { $filtro .= " AND (Ubicacion = '".$ubicacion."')"; $cUbicacion = "checked"; } else $dUbicacion = "disabled";
if($fSituacionActivo != "") { $filtro .= " AND (SituacionActivo = '".$fSituacionActivo."')"; $cSituacionActivo = "checked"; } else $dSituacionActivo = "disabled";
//if($fBienes!=""){$filtro.=" AND (ClasificacionPublic20 LIKE '".$fBienes."'%)"; $cBienes="checked";}else $dBienes="disabled";

if($centro_costos != ""){ $filtro .= " AND (CentroCosto = '".$centro_costos."')"; $cCosto = "checked"; } else $dCosto = "disabled";
if($fNaturaleza!=""){$filtro.=" AND (Naturaleza = '".$fNaturaleza."'%)"; $cNaturaleza="checked";}else $dNaturaleza="disabled";
if($fActivo!=""){$filtro.=" AND (Activo = '".$fActivo."'%)"; $cActivo="checked";}else $dActivo="disabled";
if(($fFechaAprobacionDesde!="") and ($fFechaAprobacionHasta!="")) $cFechaAprobacion="checked";else $dFechaAprobacion="disabled";
if(($fFechaPreparacionDesde!="") and ($fFechaPreparacionHasta!="")) $cFechaPreparacion="checked";else $dFechaPreparacion="disabled";

if($fub_actual!="") $cubicacionActual="checked";else{ $dubicacionActual="disabled"; $dubicacionY_O="disabled";}
if($fub_anterior!="") $cubicacionAnterior="checked";else{ $dubicacionAnterior="disabled"; $dubicacionY_O="disabled";}


?>
<? echo"
<form name='frmentrada' id='frmentrada' action='af_rptabmovimientoactivospdf.php' method='POST' target='iReporte'>
<input type='hidden' name='limit' id='limit' value='".$limit."'>
<input type='hidden' name='registros' id='registros' value='".$registros."'/>
<input type='hidden' name='usuarioActual' id='usuarioActual' value='".$_SESSION['USUARIO_ACTUAL']."'/>
<input type='hidden' id='asignado' name='asignado'/>
<input type='hidden' id='filtro' name='filtro'/>

<div class='divBorder' style='width:900px;'>
<table width='900' class='tblFiltro'>
<tr>
  <td class='tagForm'>Organismo:</td>
<td>
<input type='checkbox' name='chkorganismo' id='chkorganismo' value='1' $corganismo onclick='this.checked=true' />
<select name='forganismo' id='forganismo' class='selectBig' $dorganismo onchange='getFOptions_2(this.id, \"fanteproyecto\", \"chknanteproyecto\");'>";
	getOrganismos($obj[2], 3, $_SESSION[ORGANISMO_ACTUAL]);
	echo "
</select>
</td>
  <td align='right'>Activo:</td>
  <td class='gallery clearfix'><input type='checkbox' id='chkActivo' name='chkActivo' value='1' $cActivo onclick='enabledRPActivo(this.form);'/>
	<input type='text' id='fActivo' name='fActivo' value='$fActivo' $dActivo size='10' readonly/> 
	<input type='text' name='fDescpActivo' id='fDescpActivo' value='$fCodCatClasf' size='55' $dActivo readonly/>";?> 
    <a href="af_selectoractivos.php?filtrar=default&limit=0&campo=3&iframe=true&width=80%&height=100%" rel="prettyPhoto[iframe1]" id="activo" style="visibility:hidden">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;"/>
            </a>
	 <? echo"</td>
</tr>

<tr>
  <td class='tagForm'>Dependencia:</td>
  <td><input type='checkbox' id='checkDependencia' name='checkDependencia' value='1' $cDependencia onclick='enabledDependencia(this.form);'/>
      <select id='fDependencia' name='fDependencia' $dDependencia class='selectBig'>
	     <option value=''></option>";
		   getDependencias($fDependencia, $forganismo, 2);
		 echo"</td>
  <td align='right'>Fecha Aprobaci&oacute;n:</td>
  <td>
      <input type='checkbox' name='chkFAprobacion' id='chkFAprobacion' value='1' $cFechaAprobacion onclick='enabledRPFechaAprobacion(this.form);'/>
	  <input type='text' id='fFechaAprobacionDesde' name='fFechaAprobacionDesde' value='$fFechaAprobacionDesde' size='20' $dFechaAprobacion> hasta:
	  <input type='text' id='fFechaAprobacionHasta' name='fFechaAprobacionHasta' value='$fFechaAprobacionHasta' size='20' $dFechaAprobacion/></td>	 
</tr>

<tr>
  <td class='tagForm'>Centro Costo:</td>
  <td class='gallery clearfix'><input type='checkbox' name='chekcCosto' id='checkCosto' value='1' $cCosto onclick='enabledCosto(this.form);'/>
	  <input type='hidden' id='centro_costos' value='$centro_costos' name='centro_costos'/>
	  <input type='text' id='centro_costos2' value='$centro_costos2' name='centro_costos2' $dCosto size='62' readonly/>";?><input type='hidden' id='btcosto' name='btcosto' value='...' onclick="cargarVentanaLista(this.form,'af_listacentroscostos.php?limit=0&campo=9','height=500,width=800,left=200,top=100,resizable=yes');"  <?=$dCosto?> /> <a href="af_listacentroscostos.php?filtrar=default&limit=0&campo=9&iframe=true&width=80%&height=100%" rel="prettyPhoto[iframe1]" id="c_costo" style="visibility:hidden;">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;"/>
            </a>
	 <?  echo"</td>
  <td align='right'>Fecha Preparaci&oacute;n:</td>
  <td>
      <input type='checkbox' name='chkFPreparacion' id='chkFPreparacion' value='1' $cFechaPreparacion onclick='enabledRPFechaPreparacion(this.form);'/>
	  <input type='text' id='fFechaPreparacionDesde' name='fFechaPreparacionDesde' value='$fFechaPreparacionDesde' size='20' $dFechaPreparacion/> hasta:
	  <input type='text' id='fFechaPreparacionHasta' name='fFechaPreparacionHasta' value='$fFechaPreparacionHasta' size='20' $dFechaPreparacion/></td>
</tr>

<tr>
 <td align='right'>Naturaleza:</td>
 <td><input type='checkbox' id='chkNaturaleza' name='chkNaturaleza' value='1' $cNaturaleza onclick='enabledRPNaturaleza(this.form);'/>
     <select id='fNaturaleza' name='fNaturaleza' class='selectMed' $dNaturaleza>
	   <option></option>";
	   getNaturaleza($fNaturaleza,0);
	 echo " </select></td>	
</tr>

<tr>
 <td align='center' colspan='4' class='gallery clearfix'>Para la ubicaci&oacuten ==> Actual <input type='checkbox' id='chkubicacionActual' name='chkubicacionActual' onclick='enabledRPUbicacionActual(this.form);' value='1' $cubicacionActual/> <input type='hidden' id='fub_actual' name='fub_actual'/><input type='text' id='fub_actual_descp' name='fub_actual_descp' size='25' $dubicacionActual onclick='valor_asignado(this.id);'/>
                        <select id='y_o' name='y_o' $dubicacionY_O>
						 <option></option>
						  <option value='and'>Y</option>
						  <option value='or'>O</option>
						</select>
						<input type='checkbox' id='chkubicacionAnterior' name='chkubicacionAnterior' onclick='enabledRPUbicacionAnterior(this.form);' value='1' $cubicacionAnterior/><input type='hidden' id='fub_anterior' name='fub_anterior'/><input type='text' id='fub_anterior_descp' name='fub_anterior_descp' size='25' $dubicacionAnterior onclick='valor_asignado(this.id);'/>";?> 
                        <a href="pagina.php?iframe=true"  rel="prettyPhoto[iframe3]" id="a_lista" style="display:none"></a>
                        <a href="javascript:" onclick="rpmovlista();">
                            <img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;"/>
                        </a>
            </td>
<? echo" </tr>

</table>
</div>
<center><input type='submit' name='btBuscar' value='Buscar'></center>
<br /><div style='width:900;' class='divDivision'>Movimiento de Activos</div>
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

<table width="908" align="center">
<tr>
  <td>
	<div id="header">
	<ul>
	<!-- CSS Tabs PESTA�AS OPCIONES -->
	<li><a onClick="af_rptabmovimiento_activos_movimientos('frmentrada','movimiento');" href="#">Movimientos</a></li>
	<li><a onClick="af_rptabmovimiento_activos_movimientos('frmentrada','otroformato');" href="#">Otro Formato</a></li> 
	</ul>
	</div>
  </td>
</tr>
</table>

<center>
<iframe name="iReporte" id="iReporte" style="border:solid 1px #CDCDCD; width:900px; height:400px;"></iframe>
</center>

<!--<input type="hidden" name="registro" id="registro" />
<div id="tab1" style="display: block;">
<div style="width:900px" class="divFormCaption">Movimientos</div>
<center>
<iframe name="af_rptabmovimientos" id="af_rptabmovimientos" style="border:solid 1px #CDCDCD; width:900px; height:400px; visibility:false; display:false;" ></iframe>
</center>
</div>
<div id="tab2" style="display: none;">
<div style="width:900px" class="divFormCaption">Otro Formato</div>
<center>
<iframe name="af_rptabotroformato" id="af_rptabotroformato" style="border:solid 1px #CDCDCD; width:900px; height:400px; visibility:false; display:false;" ></iframe>
</center>
</div>-->
<form/>
</body>
</html>
