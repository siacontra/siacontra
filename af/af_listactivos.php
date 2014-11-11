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
<script type="text/javascript" language="javascript" src="af_fscript01.js"></script>
<script type="text/javascript" src="../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/fscript.js" charset="utf-8"></script>
</head>
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
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Lista de Activos</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table>
<hr width="100%" color="#333333" />

<? 
/// FILTRO QUE PERMITE REALIZAR BUSQUEDAS ESPECIFICAS
if(!$_POST) $fOrganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"]; else $cOrganismo = "checked"; 
if(!$_POST){ $fEstado = 'AP'; $cEstado = "checked";} 
$filtro = "";

if ($fOrganismo != "") { $filtro .= " AND (CodOrganismo = '".$fOrganismo."')"; $cOrganismo = "checked"; }else $dOrganismo = "disabled";
if ($fBuscarPor != "") { $filtro .= " AND ($fBuscarPor = '".$BuscarValor."')"; $cBuscarPor = "checked"; } else $dBuscarPor = "disabled";
if ($fCategoria != "") { $filtro .= " AND (Categoria = '".$fCategoria."')"; $cCategoria = "checked"; } else $dCategoria = "disabled"; 
if ($centro_costos != "") { $filtro .= " AND (CentroCosto = '".$centro_costos."')"; $cCosto = "checked"; } else $dCosto = "disabled";

if ($fDependencia != "") { $filtro .= " AND (CodDependencia = '".$fDependencia."')"; $cDependencia = "checked"; } else $dDependencia = "disabled";
if ($fTipoActivo != "") { $filtro .= " AND (TipoActivo = '".$fTipoActivo."')"; $cTipoActivo = "checked"; } else $dTipoActivo = "disabled";
if ($fSituacionActivo != "") { $filtro .= " AND (SituacionActivo = '".$fSituacionActivo."')"; $cSituacionActivo = "checked"; } else $dSituacionActivo = "disabled";
if ($fT_Seguro!= "") { $filtro .= " AND (TipoSeguro = '".$fT_Seguro."')"; $cT_Seguro = "checked"; } else $dT_Seguro = "disabled";

if ($fColor!= "") { $filtro .= " AND (Color = '".$fColor."')"; $cColor = "checked"; } else $dColor = "disabled";
if ($fubicacion!= "") { $filtro .= " AND (Ubicacion = '".$fubicacion."')"; $cUbicacion = "checked"; } else $dUbicacion = "disabled";
//if ($fCodCatClasf != ""){ $filtro .="AND (Clasificacion = '".$fCodCatClasf."')"; $cCatClasf = "checked"; } else $dCatClasf = "disabled";
if ($fClasificacion != ""){ $filtro .="AND (Clasificacion = '".$fClasificacion."')"; $cCatClasf = "checked"; } else $dCatClasf = "disabled";
//if ($fClasificacion != "")$filtro.="AND (Clasificacion = '".$fClasificacion."')";
if($fEstado!=""){$filtro.="AND (Estado ='".$fEstado."')"; $cEstado = "checked";} else $dEstado = "disabled";
if($fCodclasficacionPub20!=""){$filtro.=" AND (ClasificacionPublic20 = '".$fCodclasficacionPub20."')"; $cClasficacionPub20 = "checked";} else $dClasficacionPub20 = "disabled";

echo"<form name='frmentrada' id='frmentrada' action='af_listactivos.php?limit=0' method='POST'>
<input type='hidden' name='limit' value='".$limit."'>
<table class='tblForm' width='1200' height='50'>
<tr>
   <td>
   <table>
   <tr>
       <td align='right' width='85'>Organismo:</td>
       <td align='left' width='200'>
	       <input type='checkbox' id='checkOrganismo' name='checkOrganismo' value='1' $cOrganismo onclick='this.checked=true;'/>
           <select name='fOrganismo' id='fOrganismo' class='selectMed' $dOrganismo>";
           getOrganismos($_SESSION['ORGANISMO_ACTUAL'],3);
           echo"
           </select>
       </td>
       <td align='right' width='90'>Buscar Por:</td>";
	   $tvalor[0]="Nro de Activo"; $vvalor[0]="Activo";
	   $tvalor[1]="Codigo Barras"; $vvalor[1]="CodigoBarras";
	   $tvalor[2]="Categoria"; $vvalor[2]="Categoria";
	   $tvalor[3]="Tipo Activo"; $vvalor[3]="TipoActivo";
	   $tvalor[4]="Codigo Interno"; $vvalor[4]="CodigoInterno";
	   $total=5;
       echo"<td align='left' width='200'>
           <select name='fBuscarPor' id='fBuscarPor' class='selectMed' $cBuscarPor>
		    <option value=''></option>";
		    for ($i=0; $i<$total; $i++) {
				if ($fBuscarPor==$vvalor[$i]) echo "<option value='".$vvalor[$i]."' selected>".htmlentities($tvalor[$i])."</option>";
				else echo "<option value='".$vvalor[$i]."'>".htmlentities($tvalor[$i])."</option>";
			}
        echo"  </select></td>
	   <td align='right'>Categor&iacute;a:</td>
       <td width='248'><input type='checkbox' name='checkCategoria' id='checkCategoria' value='1' $cCategoria onclick='enabledCategoria(this.form);'/>
	       <select id='fCategoria' name='fCategoria' class='selectMed' $dCategoria/>
		   <option value=''></option>";
		   getCategoria($fCategoria, 0);
	   echo"</select></td>	   
	   <td align='right'>C.Costo:</td>
	   <td class='gallery clearf'><input type='checkbox' name='chekcCosto' id='checkCosto' value='1' $cCosto onclick='enabledCosto(this.form);'/>
		   <input type='hidden' id='centro_costos' value='$centro_costos' name='centro_costos'/>
		   <input type='text' id='centro_costos2' value='$centro_costos2' name='centro_costos2' $dCosto/>";?><input type='hidden' id='btcosto' name='btcosto' value='...' onclick="cargarVentanaLista(this.form,'af_listacentroscostos.php?limit=0&campo=9','height=500,width=800,left=200,top=100,resizable=yes');"  <?=$dCosto?> /> <a id="c_costo" href="af_listacentroscostos.php?filtrar=default&limit=0&campo=9&iframe=true&width=70%&height=100%" rel="prettyPhoto[iframe1]" style="visibility:hidden">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;"/>
            </a>
	 <?  echo" </select></td>
   </tr>
   
   <tr>
       <td align='right'>Dependencia:</td>
       <td align='left'>
	       <input type='checkbox' id='checkDependencia' name='checkDependencia' value='1' $cDependencia onclick='enabledDependencia(this.form);'/>
           <select name='fDependencia' id='fDependencia' class='selectMed' $dDependencia>
		      <option value=''></option>";
              getDependencias($fDependencia, $fOrganismo,  2);
           echo"
           </select>
       </td>
       <td align='right'>Buscar Valor >=</td>
       <td align='left'><input type='text' id='BuscarValor' name='BuscarValor' value='$BuscarValor' size='15' /></td>
	   <td align='right'>Cat/Clasf.:</td>
       <td class='gallery clearfix'><input type='checkbox' id='chkCatClasf' name='chkCatClasf' value='1' $cCatClasf onclick='enabledCatClasf(this.form);'/>
	       <input type='text' id='fCatClasf' name='fCatClasf' value='$fCatClasf' $dCatClasf size='10' readonly/> <input type='hidden' name='fCodCatClasf' id='fCodCatClasf' value='$fCodCatClasf'/>";
		     ///getCategoria($fCatClasf, 1);
	   echo"<input type='hidden' id='fClasificacion' name='fClasificacion' value='$fClasificacion'/><input type='text' id='DescpClasificacion' name='DescpClasificacion' value='$DescpClasificacion' $dCatClasf size='20' readonly/>";?><input type='hidden' id='btClasif' name='btClasif' value='...' onclick="cargarVentanaLista(this.form, 'af_listaclasificacionactivo.php?limit=0&campo=17&ventana=insertarClasificacionActivo','height=50%, width=80%, left=200, top=100, resizable=yes');" <?=$dCatClasf?>/>
       <a id="clasificacion" href="af_listaclasificacionactivo.php?filtrar=default&limit=0&campo=17&ventana=insertarClasificacionActivo&iframe=true&width=70%&height=100%" rel="prettyPhoto[iframe2]" style="visibility:hidden">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;"/>
            </a>
	 <? echo"</td>	
	   <td align='right'>Ubicaci&oacute;n:</td>
	   <td class='gallery clearfix'><input type='checkbox' name='checkUbicacion' id='checkUbicacion' value='1' $cUbicacion onclick='enabledUbicacionListaActivos(this.form);'/> <input type='hidden' name='fubicacion' id='fubicacion' value='$fubicacion'/><input type='text' name='fubicacion2' id='fubicacion2' value='$fubicacion2' $dUbicacion/>";?><input type="hidden" name="btUbicacion" id="btUbicacion" value="..." onclick="cargarVentanaLista(this.form, 'af_listaubicacionesactivo.php?limit=0&campo=27','height=500, width=800, left=200, top=100, resizable=yes');" <?=$dUbicacion?>/> <a id="ubicacion" href="af_listaubicacionesactivo.php?filtrar=default&limit=0&campo=27&iframe=true&width=70%&height=100%" rel="prettyPhoto[iframe3]" style="visibility:hidden">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;"/>
            </a>
	   <? echo"</td>
   </tr>
   
   <tr>
       <td align='right'>Tipo de Activo:</td>
       <td align='left'>
	       <input type='checkbox' id='checkTipoActivo' name='checkTipoActivo' value='1' $cTipoActivo onclick='enabledTipoActivo(this.form);'/>
	       <select name='fTipoActivo' id='fTipoActivo' class='selectMed' $dTipoActivo>
		   <option value=''></option>";
             getTipoActivo($fTipoActivo, 0);
           echo"
           </select>
       </td>
	   <td align='right'>T. Seguro:</td>
	   <td><input type='checkbox' name='checkT_Seguro' id='checkT_Seguro' value='1' $cT_Seguro onclick='enabledT_Seguro(this.form);'/>
	       <select id='fT_Seguro' name='fT_Seguro' class='selectMed' $dT_Seguro/>
		   <option value=''></option>";
		     getT_Seguro($fT_Seguro, 0);
	   echo"</select></td>	  
	   <td align='right'>Clasf.Pub20:</td>
	   <td colspan='2' class='gallery clearfix'><input type='checkbox' name='chkclasificacionpub20' id='chkclasificacionpub20' value='1' $cClasficacionPub20 onclick='enabledClasificacionPub20(this.form);'/> <input type='text' id='fClasificacionPub20' name='fClasificacionPub20' value='$fClasificacionPub20' size='38' $dClasficacionPub20><input type='hidden' id='fCodclasficacionPub20' name='fCodclasficacionPub20' value='$fCodclasficacionPub20'/>";?><input type='hidden' id='btClasifPub20' name='btClasifPub20' value='...' onclick="cargarVentanaLista(this.form,'af_listadoclasificacion20.php?limit=0&campo=2&ventana=insertarClasificacionPub20','height=500, width=800, left=200, top=100, resizable=yes');" <?=$dClasficacionPub20?>/> <a id="clasificacion20" href="af_listadoclasificacion20.php?filtrar=default&limit=0&campo=2&ventana=insertarClasificacionPub20&iframe=true&width=70%&height=100%" rel="prettyPhoto[iframe4]" style="visibility:hidden">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;"/>
            </a><? echo" </td>
	   <td></td>	 
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
	<td align='right'>Color:</td>
	<td><input type='checkbox' id='checkColor' name='checkColor' value='1' $cColor onclick='enabledColor(this.form);'/>
	    <select id='fColor' name='fColor' class='selectMed' $dColor>
		<option value=''></option>";
		getColor($fColor, 0);
  echo"</select></td>
  <td align='right'>Estado:</td>
	   <td><input type='checkbox' name='chkEstado' id='chkEstado' value='1' $cEstado onclick='enabledEstadoActivos(this.form);'/> <select id='fEstado' name='fEstado' $dEstado>
	   <option value=''></option>";
	   getEstadoListActivo2($fEstado,0);
	   echo"</select></td>
  </tr>
  
  <tr>
    <td align='right'></td>
    <td align='left'><input type='hidden' name='numLineas' id='numLineas' value='".$_POST['lineas']."'/>
    </td>
  </tr>
   
   </table>
   </td>
</tr>
</table>
<center><input type='submit' name='btBuscar' value='Buscar'/></center>
</form>";


//$valor = '123456';
//$v = substr($valor,0,4); echo '-///////////'.$v;
  /// CONSULTA PARA OBTENER DATOS DE LA TABLA A MOSTRAR
  $sa= "select 
              * 
		  from 
		      af_activo 
         where 
              CodOrganismo<>'' $filtro
        
         order by Activo desc"; //echo $sa;
  $qa= mysql_query($sa) or die ($sa.mysql_error());
  $ra= mysql_num_rows($qa);
  
  //echo "<input type='text' name='linea' id='linea' value='$ra'/>";
?>

<form id="tabs" name="tabs">

<? echo"<input type='hidden'id='registro' name='registro'/>"; ?>
<table width="1200" class="tblLista">
 <tr> 
  <td><div id="rows"># de Lineas:<?=$ra;?></div></td>
  <td align="right"></td>
  <td align="right">
    <input type="button" name="btAgregar" id="btAgregar" class="btLista" value="Agregar" onclick="cargarPaginaAgregar(this.form, 'af_listactivosagregar.php?regresar=af_listactivos&fOrganismo=<?=$fOrganismo;?>&fBuscarPor=<?=$fBuscarPor;?>&fCategoria=<?=$fCategoria;?>&centro_costos=<?=$centro_costos;?>&fDependencia=<?=$fDependencia;?>&fTipoActivo=<?=$fTipoActivo;?>&fSituacionActivo=<?=$fSituacionActivo;?>&fT_Seguro=<?=$fT_Seguro;?>&fColor=<?=$fColor;?>&fubicacion=<?=$fubicacion;?>&fClasificacion=<?=$fClasificacion;?>&fCatClasf=<?=$fCatClasf;?>&DescpClasificacion=<?=$DescpClasificacion;?>&fEstado=<?=$fEstado;?>&fCodclasficacionPub20=<?=$fCodclasficacionPub20;?>&BuscarValor=<?=$BuscarValor;?>&fClasificacionPub20=<?=$fClasificacionPub20;?>&centro_costos2=<?=$centro_costos2;?>&fubicacion2=<?=$fubicacion2;?>');"/>
    <input type="button" name="btVer" id="btVer" class="btLista" value="Ver" onclick="cargarOpcion(this.form, 'af_listactivosver.php?', 'BLANK', 'height=600, width=920, left=250, top=50, resizable=no');"/>
    <input type="button" name="btModificar" id="btModificar" class="btLista" value="Modificar" onclick="cargarPaginaAgregar(this.form, 'af_listactivosmodificar.php?regresar=af_listactivos&fOrganismo=<?=$fOrganismo;?>&fBuscarPor=<?=$fBuscarPor;?>&fCategoria=<?=$fCategoria;?>&centro_costos=<?=$centro_costos;?>&fDependencia=<?=$fDependencia;?>&fTipoActivo=<?=$fTipoActivo;?>&fSituacionActivo=<?=$fSituacionActivo;?>&fT_Seguro=<?=$fT_Seguro;?>&fColor=<?=$fColor;?>&fubicacion=<?=$fubicacion;?>&fClasificacion=<?=$fClasificacion;?>&fCatClasf=<?=$fCatClasf;?>&DescpClasificacion=<?=$DescpClasificacion;?>&fEstado=<?=$fEstado;?>&fCodclasficacionPub20=<?=$fCodclasficacionPub20;?>&BuscarValor=<?=$BuscarValor;?>&fClasificacionPub20=<?=$fClasificacionPub20;?>&centro_costos2=<?=$centro_costos2;?>&fubicacion2=<?=$fubicacion2;?>','SELF')"/>
    <input type="button" name="btBorrar" id="btBorrar" class="btLista" value="Borrar" onclick="EliminarRegistros(this.form,'af_listactivos.php','1','APLICACIONES');"/>
    <input type="button" name="btSustento" id="btSustento" class="btLista" value="Sustento"/>
    <input type="button" name="btDepriaciacion" id="btDepreciacion" class="btLista" value="Depreciaci&oacute;n"/>
  </td>
  </tr>
</table>
<center>
<div style="overflow:scroll; width:1200px; height:300px;">
<table width="1700" class="tblLista">
<thead>
  <tr class="trListaHead">
		<th width="50">Activo</th>
        <th width="60">C&oacute;digo Interno</th>
		<th width="70">C&oacute;digo Barras</th>
		<th width="150">Descripci&oacute;n Local</th>
		<th width="50">Tipo de Activo</th>
    	<th width="50">Situaci&oacute;n</th>
 	    <th width="50">Categor&iacute;a</th>
        <th width="50">Clasificaci&oacute;n Pub. 20</th>
        <th width="50">Centro Costos</th>
        <th width="50">C.C Destino</th>
        <th width="50">Ubicaci&oacute;n</th>
        <th width="40">N&uacute;mero Serie</th>
        <th width="50">Estado</th>
  </tr>
  </thead>
  <?
  
  if($ra!=0){
      
   for($i=0;$i<$ra;$i++){
     $fa= mysql_fetch_array($qa);
	 if($fa['TipoActivo']=='I') $tipoActivo= 'Individual'; else $tipoActivo = 'Principal';
	 if($fa['Estado']=='AP')$estado = 'Activado';else $estado = 'Pendiente de Activar';
	 /// -------------------------------------------
	 $s_sitActivo = "select * from af_situacionactivo where  CodSituActivo= '".$fa['SituacionActivo']."'";
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
						 af_clasificacionactivo20 ca
					where
					     cc.CodCentroCosto = '".$fa['CentroCosto']."' and 
						 ca.CodClasificacion = '".$fa['ClasificacionPublic20']."'";
	 $q_mostrar = mysql_query($s_mostrar) or die ($s_mostrar.mysql_error());
	 $f_mostrar = mysql_fetch_array($q_mostrar);
	 /// -------------------------------------------
     $s_ubicaciones = "select * from af_ubicaciones where CodUbicacion = '".$fa['Ubicacion']."'";
	 $q_ubicaciones = mysql_query($s_ubicaciones) or die ($s_ubicaciones.mysql_error());
	 $f_ubicaciones = mysql_fetch_array($q_ubicaciones);
	 /// -------------------------------------------
     //$id= $fa['NroOrden']."|".$fa['Secuencia'];
     
    echo"<tr class='trListaBody' onclick='mClk(this, \"registro\");' id='".$fa['Activo']."'>
		<td align='center'>".$fa['Activo']."</td>
		<td align='center'>".$fa['CodigoInterno']."</td>
		<td align='center'>".$fa['CodigoBarras']."</td>
        <td>".$fa['Descripcion']."</td>
        <td align='left'>$tipoActivo</td>
        <td align='left'>".htmlentities($f_sitActivo['Descripcion'])."</td>
		<td align='left'>".htmlentities($f_catDeprec['DescripcionLocal'])."</td>
		<td align='left'>".$f_mostrar['DescripClasificacionActivo']."</td>
		<td align='left'>".$f_mostrar['DescripCentroCosto']."</td>
        <td align='right'>".$fa['CodClasificacion']."</td>
		<td>".$f_ubicaciones['Descripcion']."</td>
        <td align='center'>".$fa['NumeroSerie']."</td>
        <td align='center'>$estado</td>
	</tr>";
    }
 }
  ?>
</table>
</div>
</center>
</form>
</body>
</html>
