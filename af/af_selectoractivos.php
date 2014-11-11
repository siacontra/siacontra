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
<link type="text/css" rel="stylesheet" href="../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript.js"></script>
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
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Listar Movimientos</td>
		<?
        if($_GET['cierre']!=1){?>
        <td align="right"><a class="cerrar"; href="javascript:parent.$.prettyPhoto.close();">[Cerrar]</a></td>
        <? }else{?>
		<td align="right"><a class="cerrar"; href="javascript:window.close();">[Cerrar]</a></td>
        <? }?>
	</tr>
</table>
<hr width="100%" color="#333333" />

<? 
/// FILTRO QUE PERMITE REALIZAR BUSQUEDAS ESPECIFICAS
if(!$_POST) $fOrganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
//if(!$_POST) {$fEstado = "AP"; $cEstado = "checked";} 
$filtro = "";

if ($fOrganismo != "") { $filtro .= " AND (CodOrganismo = '".$fOrganismo."')"; $cOrganismo = "checked"; }else $dOrganismo = "disabled";
if ($fPersona !=""){$filtro .="AND (EmpleadoResponsable = '".$fPersona."')"; $cPersona = "checked";}else $dPersona = "disabled";
if ($fCentroCosto !=""){$filtro .="AND (CentroCosto = '".$fCentroCosto."')"; $cCentroCosto = "checked";}else $dCentroCosto = "disabled";
if ($fUbicacion !=""){$filtro .="AND (Ubicacion = '".$fUbicacion."')"; $cUbicacion = "checked";}else $dUbicacion = "disabled";
if ($fNaturaleza !=""){$filtro .="AND (Naturaleza = '".$fNaturaleza."')"; $cNaturaleza = "checked";}else $dNaturaleza = "disabled";
if ($fConsolidado !=""){$filtro.= "AND (Activo = '".$fConsolidado."')"; $cConsolidado="checked";}else $dConsolidado="disabled";

if ($fmovimiento != "") $filtro .= " AND (MovimientoNumero = '".$fmovimiento."')";
//if ($fEstado !=""){ $filtro .= "AND (Estado = '".$fEstado."')"; $cEstado = "checked"; }else $dEstado ="disabled"; 

echo"<form name='frmentrada' id='frmentrada' action='af_selectoractivos.php?limit=0&campo=$campo&cierre=$cierre' method='POST'>
<table class='tblForm' width='850' height='50'>
<input type='hidden' name='campo' id='campo' value='".$campo."'/>
<input type='hidden' name='cierre' id='cierre' value='".$cierre."'/>

<tr>
<td>
<table>
<tr>
   <td align='right'>Organismo:</td>
   <td align='left'>
	   <select name='fOrganismo' id='fOrganismo' class='selectBig'>";
	   getOrganismos($_SESSION['ORGANISMO_ACTUAL'],3);
	   echo"
	   </select>
   </td>
   <td align='right'>Persona:</td>
   <td><input type='checkbox' id='checkPersona' name='checkPersona' value='1' $cPersona onclick='enabledPersonaSelecActivo(this.form);'/>
	   <input type='hidden' id='fPersona' name='fPersona' value='".$fPersona."'/> 
	   <input type='text' name='NombPersona' id='NombPersona' size='50' value='".$NombPersona."' $dPersona/>";?>
       <input type="button" id="btpersona" name="btpersona" value="..." onclick="cargarVentana(this.form,'af_listaempleados.php?limit=0&campo=23','height=500,width=850,left=200,top=100,resizable=yes');" <?=$dPersona;?> /><? echo" </td>
</tr>

<tr>
 <td align='right'>Ordenar Por:</td>
 <td><select id='' name='' class='selectMed'>
      <option value='Activo'>Activo</option>
	  <option value='CodigoInterno'>C&oacute;digo Interno</option>
	  <option value='Descripcion'>Descripci&oacute;n</option>
	  <option value='CodigoBarras'>C&oacute;digo Barras</option>
	 </select></td>
	 
 <td align='right'>Centro Costo:</td>
 <td><input type='checkbox' name='checkCentroCosto' id='checkCentroCosto' value='1' $cCentroCosto onclick='enabledCentroCostosSelectorActivos(this.form);'/>
     <input type='hidden' id='fCentroCosto' name='fCentroCosto' value='".$fCentroCosto."' />
	 <input type='text' id='fCentroCosto2' name='fCentroCosto2' size='50' value='".$fCentroCosto2."' $dCentroCosto/>";?>
	 <input type="button" id="btCentroCosto" name="btCentroCosto" value="..." onclick="cargarVentana(this.form,'af_listacentroscostos.php?limit=0&campo=22','height=500,width=850,left=200,top=100,resizable=yes');" <?=$dCentroCosto;?> /><? echo"</td>
</tr>

<tr>   
   <td align='right'>Buscar >=</td>
   <td><input type='text' id='buscar' name='buscar'/></td>
   <td align='right'>Ubicaci&oacute;n:</td>
   <td><input type='checkbox' id='checkUbicacion' name='checkUbicacion' value='1' $cUbicacion onclick='enabledUbicacionSelectorActivo(this.form);'/>
	   <input type='hidden' id='fUbicacion' name='fUbicacion' value='".$fUbicacion."'/> 
	   <input type='text' name='DescpUbicacion' id='DescpUbicacion' size='50' value='".$DescpUbicacion."' $dUbicacion/> 
	   ";?><input type="button" id="btUbicacion" name="btUbicacion" value="..." onclick="cargarVentana(this.form,'af_listaubicacionesactivo.php?limit=0&campo=21','height=500,width=850,left=200,top=100,resizable=yes');" <?=$dUbicacion;?>/><? echo"</td>
</tr>

<tr>   
   <td align='right'>Naturaleza:</td>
   <td><input type='checkbox' id='checkNaturaleza' name='checkNaturaleza' value='1' $cNaturaleza onclick='enabledNaturalezaSelectorActivo(this.form);'/>
       <select id='fNaturaleza' name='fNaturaleza' class='selectMed' $dNaturaleza> 
	   <option value=''></option>";
	     getNaturaleza($fNaturaleza,0);
	   echo"</select></td>
   <td align='right'>Activo Consolidado:</td>
   <td><input type='checkbox' id='checkConsolidado' name='checkConsolidado' value='1' $cConsolidado onclick='enabledConsolidadoSelectorActivo(this.form);'/>
	   <input type='hidden' name='fConsolidado' id='fConsolidado'  value='$fConsolidado'/>
       <input type='text' name='fNomConsolidado' id='fNomConsolidado' size='50' value='$fNomConsolidado' $dConsolidado/>";?>
       <input type="button" name="btConsolidado" id="btConsolidado" value="..." onclick="cargarVentana(this.form,'af_listaactivosfijos.php?limit=0&campo=25','height=600, width=950, left=200,top=100,resizable=yes');" <?=$dConsolidado;?>/><? echo"</td>
</tr>

   </table>
   </td>
</tr>
</table>
<center><input type='submit' name='btBuscar' value='Buscar'/></center>
</form>";

  /// CONSULTA PARA OBTENER DATOS DE LA TABLA A MOSTRAR
 $sa= "select * from af_activo 
                where 
                      CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' and 
                      Estado='AP' $filtro
             order by 
                      Activo"; //echo $sa;
  $qa= mysql_query($sa) or die ($sa.mysql_error());
  $ra= mysql_num_rows($qa);
  
  
?>
<form id="tabs" name="tabs" method="post" action="af_selectoractivos.php?limit=0&amp;campo=<?=$campo?>">
<table class="tblForm" width="850">
<? echo"<input type='hidden' name='registro' id='registro'/>";?>
<tr><td><div class="rows"><b>Registros  <?=$ra;?></b></div></td></tr>
<tr>
  <td>
    <table align="center"><tr><td align="center"><div style="overflow:scroll; width:837px; height:300px;">
    <table class="tblLista" width="850" align="center">
    <thead>
    <tr class="trListaHead">
        <th width="120">Activo</th>
        <th width="120">C&oacute;digo Interno</th>
        <th width="200"> Descripci&oacute;n</th>
        <th width="120">C&oacute;digo Barra</th>
    </tr>
    </thead>
    <?
  
  if($ra!=0){
      
   for($i=0;$i<$ra;$i++){
     $fa= mysql_fetch_array($qa);
	 // consultas
	 $s_consultas = "select
	                        cc.CodCentroCosto,
							cc.Descripcion as DescripCentroCosto,
							ub.CodUbicacion,
							ub.Descripcion as DescripUbicacion,
							dp.CodDependencia,
							dp.Dependencia,
							mp.CodPersona,
							mp.NomCompleto,
							mo.CodOrganismo,
							mo.Organismo,
							mp2.CodPersona,
							mp2.NomCompleto,
							cat.CodCategoria,
							cat.DescripcionLocal
					   from
					        ac_mastcentrocosto cc,
							af_ubicaciones ub,
							mastpersonas mp,
							mastdependencias dp,
							mastorganismos mo,
							mastpersonas mp2,
							af_categoriadeprec cat
					  where 
					        cc.CodCentroCosto = '".$fa['CentroCosto']."' and 
							ub.CodUbicacion = '".$fa['Ubicacion']."' and 
							dp.CodDependencia = '".$fa['CodDependencia']."' and
							mp.CodPersona = '".$fa['EmpleadoUsuario']."' and
							mo.CodOrganismo = '".$fa['CodOrganismo']."' and 
							mp2.CodPersona = '".$fa['EmpleadoResponsable']."' and 
							cat.CodCategoria = '".$fa['Categoria']."'"; //echo $s_consultas;
     $q_consultas = mysql_query($s_consultas) or die ($s_consultas.mysql_error());
	 $f_consultas = mysql_fetch_array($q_consultas);
	 
	 /// ----- *********
	 $MontoLocal = number_format($fa['MontoLocal'],2,',','.');
	 $s_consultas02 = "select CodPersona,NomCompleto from mastpersonas where CodPersona = '".$fa['EmpleadoResponsable']."'";
	 $q_consultas02 = mysql_query($s_consultas02) or die ($s_consultas02.mysql_error());
	 $f_consultas02 = mysql_fetch_array($q_consultas02);
	 
if($_GET['campo']==1){
    echo"<tr class='trListaBody' onclick='mClk(this, \"registro\"); selActivo(\"".$field['Busqueda']."\", ".$_GET['campo'].", \"".$fa['Descripcion']."\", \"".$fa['CodigoBarras']."\" , \"".$fa['CentroCosto']."\", \"".$f_consultas['DescripCentroCosto']."\", \"".$f_consultas['CodUbicacion']."\", \"".$f_consultas['DescripUbicacion']."\", \"".$f_consultas['4']."\", \"".$f_consultas['5']."\", \"".$f_consultas['6']."\", \"".$f_consultas['7']."\", \"".$f_consultas['10']."\", \"".$f_consultas['11']."\", \"".$f_consultas['8']."\", \"".$f_consultas['9']."\" );' id='".$fa['Activo']."'>";
}elseif($_GET['campo']==2){
    echo"<tr class='trListaBody' onclick='mClk(this, \"registro\"); selActivo(\"".$field['Busqueda']."\", ".$_GET['campo'].", \"".$fa['Descripcion']."\", \"".$fa['CodigoBarras']."\" , \"".$fa['CentroCosto']."\",\"".$f_consultas['CodDependencia']."\", \"".$f_consultas['Dependencia']."\", \"".$f_consultas['CodOrganismo']."\", \"".$f_consultas['Organismo']."\",\"".$fa['FacturaNumeroDocumento']."\",\"".$f_consultas['CodCentroCosto']."\",\"".$f_consultas['DescripCentroCosto']."\",\"".$f_consultas['CodPersona']."\",\"".$f_consultas['NomCompleto']."\",\"".$f_consultas['CodUbicacion']."\",\"".$f_consultas['DescripUbicacion']."\",\"".$f_consultas['CodCategoria']."\",\"".htmlentities($f_consultas['DescripcionLocal'])."\",\"".$MontoLocal."\",\"".$fa['CodigoInterno']."\");' id='".$fa['Activo']."'>";	  
}elseif($_GET['campo']==3){
    echo"<tr class='trListaBody' onclick='mClk(this, \"registro\"); selActivo(\"".$field['Busqueda']."\", ".$_GET['campo'].", \"".$fa['Descripcion']."\");' id='".$fa['Activo']."'>";	
}
echo"
		<td align='center'>".$fa['Activo']."</td>
		<td align='center'>".$fa['CodigoInterno']."</td>
        <td align='left'>".$fa['Descripcion']."</td>
		<td align='centers'>".$fa['CodigoBarras']."</td>
	</tr>";
   }
 }
  ?>
    </table>
    </div></td></tr></table>
 </td>
</tr>
</table>
</form>
</body>
</html>