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
<script type="text/javascript" src="../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/fscript.js" charset="utf-8"></script>
<!--<style type="text/css">

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
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Lista de Activos Menores</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table>
<hr width="100%" color="#333333" />

<? 
/// FILTRO QUE PERMITE REALIZAR BUSQUEDAS ESPECIFICAS
if(!$_POST) $fOrganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"]; else $cOrganismo = "checked"; 
if(!$_POST){ $fEstado = 'PE'; $cEstado = "checked";} 
//if(!$_POST) $fDependencia = $_SESSION["FILTRO_DEPENDENCIA_ACTUAL"]; else{ $fDependencia = $_POST['fDependencia']; $cDependencia = "checked"; }
$filtro = "";

if ($fOrganismo!=""){$filtro .=" AND (CodOrganismo ='".$fOrganismo."')"; $cOrganismo = "checked"; }else $dOrganismo = "disabled";
if ($fBuscarPor!=""){$filtro .=" AND ($fBuscarPor >='".$BuscarValor."')"; $cBuscarPor = "checked"; } else $dBuscarPor = "disabled";
if ($fDependencia!=""){$filtro .=" AND (CodDependencia ='".$fDependencia."')"; $cDependencia = "checked"; } else $dDependencia = "disabled";
if ($fSituacionActivo!=""){$filtro .=" AND (SituacionActivo ='".$fSituacionActivo."')";$cSituacionActivo ="checked";}else $dSituacionActivo = "disabled";
if ($fubicacion!= "") { $filtro .= " AND (Ubicacion = '".$fubicacion."')"; $cUbicacion = "checked"; } else $dUbicacion = "disabled";
if ($fClasf20 !=""){ $filtro.=" AND (ClasificacionPublic20 = '".$fClasf20."')";$cClasf20 = "checked";} else $dClasf20 = "disabled";
if ($fClasificacion != ""){$filtro.="AND (Clasificacion = '".$fClasificacion."')"; $cClasf = "checked";}else $dClasf = "disabled";
if ($fEstado != "") {$filtro.="AND (Estado ='".$fEstado."')"; $cEstado = "checked";} else $dEstado = "disabled";

echo"<form name='frmentrada' id='frmentrada' action='af_activosmenores.php?limit=0' method='POST'>
<table class='tblForm' width='1000' height='50'>
<tr>
   <td>
   <table>
   <tr>
       <td align='right' width='120'>Organismo:</td>
       <td align='left' width='200'>
	       <input type='checkbox' id='checkOrganismo' name='checkOrganismo' value='1' $cOrganismo onclick='this.checked=true;'/>
           <select name='fOrganismo' id='fOrganismo' class='selectMed' $dOrganismo>";
           getOrganismos($_SESSION['ORGANISMO_ACTUAL'],3);
           echo"
           </select>
       </td>
       <td align='right' width='120'>Ordenar Por:</td>";
	   $tvalor[0]="Nro de Activo"; $vvalor[0]="Activo";
	   $tvalor[1]="C&oacute;digo Barras"; $vvalor[1]="CodigoBarras";
	   $tvalor[2]="N&uacute;mero Serie"; $vvalor[2]="NumeroSerie";
	   $total=3;
       echo"<td align='left' width='200'>
           <select name='fBuscarPor' id='fBuscarPor' class='selectMed' $cBuscarPor>
		    <option value=''></option>";
		    for ($i=0; $i<$total; $i++) {
				if ($fBuscarPor==$vvalor[$i]) echo "<option value='".$vvalor[$i]."' selected>".utf8_decode($tvalor[$i])."</option>";
				else echo "<option value='".$vvalor[$i]."'>".utf8_decode($tvalor[$i])."</option>";
			}
        echo"  </select></td>
	   <td align='right'>Ubicaci&oacute;n:</td>
	   <td class='gallery clearfix'><input type='checkbox' name='checkUbicacion' id='checkUbicacion' value='1' $cUbicacion onclick='enabledUbicacionActivosMenores(this.form);'/> <input type='hidden' name='fubicacion' id='fubicacion' value='$fubicacion'/><input type='text' name='fubicacion2' id='fubicacion2' value='$fubicacion2' size='30' $dUbicacion readonly/>";?><input type="hidden" name="btUbicacion" id="btUbicacion" value="..." onclick="cargarVentanaLista(this.form, 'af_listaubicacionesactivo.php?limit=0&campo=26','height=500, width=800, left=200, top=100, resizable=yes');" <?=$dUbicacion?>/> <a id="ubicacionactivo" href="af_listaubicacionesactivo.php?filtrar=default&limit=0&campo=26&iframe=true&width=100%&height=100%" rel="prettyPhoto[iframe1]" style="visibility:hidden">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;"/>
            </a>
	   <? echo" </select></td>
   </tr>
   
   <tr>
       <td align='right'>Dependencia:</td>
       <td align='left'>
	       <input type='checkbox' id='checkDependencia' name='checkDependencia' value='1' $cDependencia onclick='enabledDependencia(this.form);'/>
           <select name='fDependencia' id='fDependencia' class='selectMed' $dDependencia>
		    <option></option>";
              //getDependencias($fDependencia, $fOrganismo,  2);
			  getDependenciaSeguridad($fDependencia, $fOrganismo, 3);
           echo"
           </select>
       </td>
       <td align='right'>Buscar Valor >=</td>
       <td align='left'><input type='text' id='BuscarValor' name='BuscarValor' value='$BuscarValor' size='15' /></td>
	   <td align='right'>Clasificaci&oacute;n:</td>
       <td class='gallery clearfix'><input type='checkbox' id='chkClasificacion' name='chkClasificacion' value='1' $cClasf onclick='enabledClasf(this.form);'/>
	       <input type='hidden' id='fClasificacion' name='fClasificacion' value='$fClasificacion'/><input type='text' id='DescpClasificacion' name='DescpClasificacion' value='$DescpClasificacion' size='30' $dClasf/>";?><input type='hidden' id='btClasif' name='btClasif' value='...' onclick="cargarVentanaLista(this.form, 'af_listaclasificacionactivo.php?limit=0&campo=24&ventana=insertarClasificacionActivo','height=500, width=800, left=200, top=100, resizable=yes');" <?=$dClasf?>/> <a id="clasf" href="af_listaclasificacionactivo.php?filtrar=default&limit=0&campo=24&ventana=insertarClasificacionActivo&iframe=true&width=100%&height=100%" rel="prettyPhoto[iframe1]" style="visibility:hidden">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;"/>
            </a>
	 <? echo"</td>	
   </tr>
   
   <tr>
       <td align='right'>Situaci&oacute;n:</td>
       <td align='left'>
	   <input type='checkbox' id='checkSituacionActivo' name='checkSituacionActivo' value='1' $cSituacionActivo onclick='enabledSituacionActivo(this.form);'/>
	   <select name='fSituacionActivo' id='fSituacionActivo' class='selectMed' $dSituacionActivo>
	   <option value=''></option>";
		 getSituacionActivo($fSituacionActivo, 0);
	   echo"
	   </select>
    </td>
	   <td align='right'>Clasificaci&oacute;n 20:</td>
	   <td class='gallery clearfix'><input type='hidden' name='fClasf20' id='fClasf20' value='$fClasf20'/><input type='checkbox' id='chkClasf20' name='chkClasf20' value='1' $cClasf20 onclick='enabledClasf20(this.form);'/><input type='text' name='DescpClasf20' id='DescpClasf20' value='$DescpClasf20' size='30' $dClasf20/>";?><input type='hidden' id='btclasf20' name='btclasf20' value='...' onclick="cargarVentanaLista(this.form,'af_listadoclasificacion20.php?limit=0&campo=18','height=500, width=800, left=200, top=100, resizable=yes')" <?=$dClasf20; ?> /> <a id="clasificacion20" href="af_listadoclasificacion20.php?filtrar=default&limit=0&campo=18&iframe=true&width=100%&height=100%" rel="prettyPhoto[iframe1]" style="visibility:hidden">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;"/>
            </a><? echo"</td>
   </tr>
   
   <tr>
     <td align='right'>Estado:</td>
	   <td><input type='checkbox' name='chkEstado' id='chkEstado' value='1' $cEstado onclick='enabledEstadoActivos(this.form);'/> 
	       <select id='fEstado' name='fEstado' $dEstado>
		   <option></option>";
		   getEstado($fEstado, 2);
		   echo"</select></td>
	<td align='right'></td>
  </tr>
   </table>
   </td>
</tr>
</table>
<center><input type='submit' name='btBuscar' value='Buscar'/></center>
</form>";

  /// CONSULTA PARA OBTENER DATOS DE LA TABLA A MOSTRAR
  $sa= "select * from 
                      af_activo 
                where 
                      CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' and 
					  Naturaleza = 'AM' $filtro
             order by 
                      Activo"; //echo $sa;
  $qa= mysql_query($sa) or die ($sa.mysql_error());
  $ra= mysql_num_rows($qa);
  
?>

<form id="tabs" name="tabs">
<table class="tblForm" width="1000">
<tr>
  <td>

<table width="1000" class="tblLista">
 <tr> <input type="hidden" id="registro" name="registro"/>
  <td><div id="rows"></div></td>
  <td align="right"></td>
  <td align="right">
    <input type="button" name="btAgregar" id="btAgregar" class="btLista" value="Agregar" onclick="cargarPaginaAgregar(this.form, 'af_activosmenoresagregar.php?regresar=af_activosmenores&fEstado=<?=$fEstado;?>&fOrganismo=<?=$fOrganismo;?>&fBuscarPor=<?=$fBuscarPor;?>&fDependencia=<?=$fDependencia;?>&fSituacionActivo=<?=$fSituacionActivo;?>&fClasf20=<?=$fClasf20;?>&DescpClasf20=<?=$DescpClasf20;?>&fClasificacion=<?=$fClasificacion;?>&fubicacion=<?=$fubicacion;?>&BuscarValor=<?=$BuscarValor;?>&fubicacion2=<?=$fubicacion2;?>&DescpClasificacion=<?=$DescpClasificacion;?>');"/>
    <input type="button" name="btVer" id="btVer" class="btLista" value="Ver" onclick="cargarOpcion(this.form,'af_activosmenoresver.php?','BLANK', 'height=600, width=920, left=250, top=50, resizable=no');"/>
    <input type="button" name="btModificar" id="btModificar" class="btLista" value="Modificar" onclick="cargarOpcionListActEditar(this.form, 'af_activosmenoreseditar.php?regresar=af_activosmenores&fEstado=<?=$fEstado;?>&fOrganismo=<?=$fOrganismo;?>&fBuscarPor=<?=$fBuscarPor;?>&fDependencia=<?=$fDependencia;?>&fSituacionActivo=<?=$fSituacionActivo;?>&fClasf20=<?=$fClasf20;?>&DescpClasf20=<?=$DescpClasf20;?>&fClasificacion=<?=$fClasificacion;?>&fubicacion=<?=$fubicacion;?>&BuscarValor=<?=$BuscarValor;?>&fubicacion2=<?=$fubicacion2;?>&DescpClasificacion=<?=$DescpClasificacion;?>','SELF')"/>
    <input type="button" name="btMovimiento" id="btMovimiento" class="btLista" value="Movimientos" />
  </tr>
</table>

<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1000px; height:300px;">
<table width="1500" class="tblLista">
<thead>
  <tr class="trListaHead">
		<th width="40" align="center">Activo</th>
        <th width="80" align="center">C&oacute;digo Interno</th>
		<th width="250" align="center">Descripci&oacute;n Local</th>
    	<th width="80" align="center">Situaci&oacute;n</th>
        <th width="250" align="center">Clasificaci&oacute;n</th>
        <th width="250" align="center">Ubicaci&oacute;n</th>
        <th width="40" align="center">Medidas</th>
        <th width="80" align="center">C&oacute;digo Barras</th>
  </tr>
  </thead>
  <?
  
  if($ra!=0){
      
   for($i=0;$i<$ra;$i++){
     $fa= mysql_fetch_array($qa);
	 if($fa['TipoActivo']=='I') $tipoActivo= 'Individual'; else $tipoActivo = 'Principal';
	 if($fa['Estado']=='A') $estado = 'Activo';else $estado = 'Inactivo';
	 /// -------------------------------------------
	 $s_sitActivo = "select Descripcion from af_situacionactivo where  CodSituActivo= '".$fa['SituacionActivo']."'";
	 $q_sitActivo = mysql_query($s_sitActivo) or die ($s_sitActivo.mysql_error()) ;
	 $f_sitActivo = mysql_fetch_array($q_sitActivo);
	 /// -------------------------------------------
	 $s_catDeprec = "select * from af_categoriadeprec where CodCategoria = '".$fa['Categoria']."'";
	 $q_catDeprec = mysql_query($s_catDeprec) or die ($s_catDeprec.mysql_error());
	 $f_catDeprec = mysql_fetch_array($q_catDeprec);
	 /// -------------------------------------------
	 $s_mostrar = "select 
	                     cc.Descripcion as DescripCentroCosto,
						 ca.Descripcion as DescripClasificacionActivo
					 from
					     ac_mastcentrocosto cc,
						 af_clasificacionactivo ca
					where
					     cc.CodCentroCosto = '".$fa['CentroCosto']."' and 
						 ca.CodClasificacion = '".$fa['Clasificacion']."'";
	 $q_mostrar = mysql_query($s_mostrar) or die ($s_mostrar.mysql_error());
	 $f_mostrar = mysql_fetch_array($q_mostrar);
	 /// -------------------------------------------
     $s_ubicaciones = "select Descripcion from af_ubicaciones where CodUbicacion = '".$fa['Ubicacion']."'";
	 $q_ubicaciones = mysql_query($s_ubicaciones) or die ($s_ubicaciones.mysql_error());
	 $f_ubicaciones = mysql_fetch_array($q_ubicaciones);
	 /// -------------------------------------------
    echo"<tr class='trListaBody' onclick='mClk(this, \"registro\");' id='".$fa['Activo']."'>
		<td align='center'>".$fa['Activo']."</td>
		<td align='center'>".$fa['CodigoInterno']."</td>
		<td align='left'>".$fa['Descripcion']."</td>
		<td align='center'>".$f_sitActivo['Descripcion']."</td>
		<td align='center'>".$f_mostrar['DescripClasificacionActivo']."</td>
		<td align='center'>".($f_ubicaciones['Descripcion'])."</td>
		<td align='center'>".$fa['Dimensiones']."</td>
		<td align='center'>".$fa['CodigoBarras']."</td>
	</tr>";
    }
 }
  ?>
</table></div>
</td></tr>
</table>
</td></tr></table>
</form>
</body>
</html>
