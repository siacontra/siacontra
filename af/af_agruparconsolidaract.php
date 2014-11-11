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
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" src="../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript2.js"></script>
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
		<td class="titulo">Agrupar/Consolidar</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table>
<hr width="100%" color="#333333" />

<? 
/// FILTRO QUE PERMITE REALIZAR BUSQUEDAS ESPECIFICAS
if(!$_POST) $fOrganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"]; else $cOrganismo = "checked"; 
//if(!$_POST){ $fEstado = 'PE'; $cEstado = "checked";} 
if(!$_POST){ $fDependencia = $_SESSION["FILTRO_DEPENDENCIA_ACTUAL"]; $cDependencia = "checked";}
 //else{ $fDependencia = $_POST['fDependencia']; $cDependencia = "checked"; }
$filtro = "";

//echo $fOrganismo;echo $fDependencia;echo $fTipoActivo;

if($fOrganismo!=""){$filtro .=" AND (CodOrganismo ='".$fOrganismo."')"; $cOrganismo = "checked";}else $dOrganismo = "disabled";
if($fDependencia!=""){$filtro .=" AND (CodDependencia ='".$fDependencia."')"; $cDependencia = "checked";}else $dDependencia = "disabled";
//if($fNroActivo!=""){$filtro.=" AND (Activo ='".$fNroActivo."')"; $cNroActivo= "checked";}else $dNroActivo = "disabled";
if($fNroActivo!="") {$filtro.=" and (Activo<>'".$fNroActivo."')";$cNroActivo= "checked";}else $dNroActivo = "disabled";
//if ($fClasificacion != ""){$filtro.="AND (Clasificacion = '".$fClasificacion."')"; $cClasf = "checked";}else $dClasf = "disabled";

if ($fBuscarPor!=""){$filtro .=" AND ($fBuscarPor ='".$BuscarValor."')"; $cBuscarPor = "checked"; } else $dBuscarPor = "disabled";

if ($fSituacionActivo!=""){$filtro .=" AND (SituacionActivo ='".$fSituacionActivo."')";$cSituacionActivo ="checked";}else $dSituacionActivo = "disabled";
if ($ubicacion!= "") { $filtro .= " AND (Ubicacion = '".$ubicacion."')"; $cUbicacion = "checked"; } else $dUbicacion = "disabled";
if ($fClasf20 !=""){ $filtro.=" AND (ClasificacionPublic20 = '".$fClasf20."')";$cClasf20 = "checked";} else $dClasf20 = "disabled";
if ($fEstado != "") {$filtro.="AND (EstadoRegistro ='".$fEstado."')"; $cEstado = "checked";} else $dEstado = "disabled";
if($fmostrar != ""){ if($fmostrar=='SR')$filtro.=" and (ActivoConsolidado='')"; 
					 elseif ($fmostrar=='AR')$filtro.=" and (ActivoConsolidado='".$fNroActivo."')"; 
					 elseif ($fmostrar=='TA')$filtro.=" and (Activo<>'')";}


echo"<form name='frmentrada' id='frmentrada' action='af_agruparconsolidaract.php?limit=0' method='POST'>
<table class='tblForm' width='900' height='50'>
<input type='hidden' id='Naturaleza'  name='Naturaleza' />
<tr>
   <td>
   <table>
   <tr>
       <td align='right' width='120'>Organismo:</td>
       <td align='left' width='370'>
	       <input type='checkbox' id='checkOrganismo' name='checkOrganismo' value='1' $cOrganismo onclick='this.checked=true;'/>
           <select name='fOrganismo' id='fOrganismo' class='selectBig' $dOrganismo>";
           getOrganismos($_SESSION['ORGANISMO_ACTUAL'],3);
           echo"
           </select>
       </td>
       <td align='right'>Clasificaci&oacute;n:</td>
       <td><input type='text' id='fClasificacion' name='fClasificacion' value='$fClasificacion' size='5' readonly/><input type='text' id='DescpClasificacion' name='DescpClasificacion' value='$DescpClasificacion' size='45' readonly/></td>	
	   <td align='right'></td>
	   <td></td>
   </tr>
   
   <tr>
       <td align='right'>Dependencia:</td>
       <td align='left'>
	       <input type='checkbox' id='checkDependencia' name='checkDependencia' value='1' $cDependencia onclick='enabledDependencia(this.form);'/>
           <select name='fDependencia' id='fDependencia' value='$fDependencia' class='selectBig' $dDependencia>
		    <option></option>";
              //getDependencias($fDependencia, $fOrganismo,  2);
			  getDependenciaSeguridad($fDependencia, $fOrganismo, 3);
           echo"
           </select>
       </td>
	   <td align='right'>Centro de Costo:</td>
       <td><input type='text' id='centro_costos' name='centro_costos' value='$centro_costos' size='5' readonly/><input type='text' id='centro_costos2' name='centro_costos2' size='45' value='$centro_costos2' readonly/>";?>
   <input type="hidden" id="btCentroCosto" name="btCentroCosto" value="..." onclick="cargarVentanaLista(this.form,'af_listacentroscostos.php?limit=0&campo=9','height=500,width=820,left=200,top=100,resizable=yes');" /><? echo" </td>	
   </tr>
   
  <tr>
   <td align='right'># de Activo:</td>
   <td align='left'><input type='checkbox' id='chkNroActivo' name='chkNroActivo' $cNroActivo onclick='enabledNroActivoAgrupCons(this.form);'/><input type='text' id='fNroActivo' name='fNroActivo' value='$fNroActivo' $dNroActivo/>";?>
   <input type="button" id="btNroActivo" name="btNroActivo" value="..." onclick="cargarVentanaLista(this.form,'af_listaactivosfijos.php?limit=0&campo=20','height=500,width=950,left=200,top=100,resizable=yes');" <?=$dNroActivo;?> /><? echo" Cod. Int:<input type='text' id='codInterno' name='codInterno' value='$codInterno' size='18' style='text-align:right;' readonly/></td>
   <td align='right'>Ubicaci&oacute;n:</td>
   <td><input type='text' name='fUbicacion' id='fUbicacion' size='5' value='$fUbicacion' readonly/><input type='text' name='DescpUbicacion' id='DescpUbicacion' value='$DescpUbicacion' size='45' readonly/></td>
  </tr>
   
  <tr>
   <td align='right'>Descripci&oacute;n:</td>
   <td align='left'><input type='text' id='descripcion' name='descripcion' value='$descripcion' size='70' readonly/></td>
   <td align='right'>Situaci&oacute;n:</td>
   <td align='left'><input type='text' id='DescpSituActivo' name='DescpSituActivo' value='$DescpSituActivo' size='20' readonly/>
   </td>
 </tr>
  
  <tr>
   <td align='right'>Mostrar:</td>
   <td align='left'><select id='fmostrar' name='fmostrar' value='$fmostrar' class='selectMed' />";
                    getMostrar($fmostrar,0);
					echo"</select></td>
    </td>
	<td align='right'</td>
	<td></td>
  </tr>
    
  <tr>
       <td align='right'></td>
       <td align='left'></td>
	   <td align='right'></td>
	   <td></td>
   </tr>
   
   <tr>
     <td align='right'></td>
	   <td></td>
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
					  Naturaleza = 'AN' $filtro
             order by 
                      Activo"; //echo $sa;
  $qa= mysql_query($sa) or die ($sa.mysql_error());
  $ra= mysql_num_rows($qa);
  
?>

<table class="tblForm" width="900">
<tr>
  <td>
  <form id="tabs" name="tabs">
  <table width="400" class="tblLista">
 <tr> <input type="hidden" id="registro" name="registro"/>
      <input type="hidden" id="codigoMostrar" name="codigoMostrar"/>
      <input type="hidden" id="clickAF"/>
  <td><div id="rows"></div></td>
  <td align="right"></td>
  <td align="right">
    <input type="button" name="btVer" id="btVer" class="btLista" value="Ver" onclick="cargarOpcionTipo(this.form,'BLANK', 'height=600, width=920, left=250, top=50, resizable=no');"/>
    <!--<input type="button" name="btModificar" id="btModificar" class="btLista" value="Modificar" onclick="cargarOpcionListActEditar(this.form, 'af_activosmenoreseditar.php?regresar=af_activosmenores','SELF')"/>
    <input type="button" name="btMovimiento" id="btMovimiento" class="btLista" value="Movimientos" />-->
   </td>
  </tr> 
</table>
  </form>
  <table align="center"><tr><td align="center"><div style="overflow:scroll; width:400px; height:200px;">
<table width="150%" class="tblLista" border="0">
<thead>
  <tr class="trListaHead">
        <th width="10" scope="col">R</th>
		<th width="60" scope="col">Activo</th>
		<th width="200" scope="col">Decripci&oacute;n Local</th>
    	<th width="100" scope="col">Situaci&oacute;n</th>
        <th width="200" scope="col">clasificaci&oacute;n</th>
        <th width="100" scope="col">Ubicaci&oacute;n</th>
        <th width="140" scope="col">Medidas</th>
        <th width="100" scope="col">C&oacute;digo Barras</th>
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
	 /// consulta para verificar si el activo es consolidado principal
	 $s_con = "select * from af_activo where Activo='".$fa['Activo']."' and ActivoConsolidado=''";
	 $q_con = mysql_query($s_con) or die ($s_con.mysql_error());
	 $r_con = mysql_num_rows($q_con);
	 if($r_con!=0){
		 $f_con = mysql_fetch_array($q_con);
		 
		 $s_con2 = "select * from af_activo where ActivoConsolidado='".$f_con['Activo']."'";
	 	 $q_con2 = mysql_query($s_con2) or die ($s_con2.mysql_error());
	 	 $r_con2 = mysql_num_rows($q_con2);
		if($r_con2!=0) $valorConsolidado= 1;
		else $valorConsolidado= 0;   
	 }else{
		/// cuando activo "x" tiene consolidado un activo
	 	$s_con3 = "select * from af_activo where Activo='".$fa['Activo']."' and ActivoConsolidado<>''";
		$q_con3 = mysql_query($s_con3) or die ($s_con3.mysql_error());
	 	$r_con3 = mysql_num_rows($q_con3);
	    if($r_con3!=0){
		  $f_con3 = mysql_fetch_array($q_con3);
	        /// tomo activo consolidado y consulto si posee un activo consolidado
			$s_con2 = "select * from af_activo where Activo='".$f_con3['ActivoConsolidado']."' and ActivoConsolidado=''";
			$q_con2 = mysql_query($s_con2) or die ($s_con2.mysql_error());
			$r_con2 = mysql_num_rows($q_con2);
		    
		    if($r_con2!=0) $valorConsolidado = 2;
			else $valorConsolidado = 3;
			/// 
			     /*$f_con2 = mysql_fetch_array($q_con2);
		     
			     $s_con4 = "select * from af_activo where ActivoConsolidado='".$f_con2['Activo']."'";
	 		     $q_con4 = mysql_query($s_con4) or die ($s_con4.mysql_error());
	 	         $r_con4 = mysql_num_rows($q_con4);*/ 
		}
	 } 
	 
	 /// -------------------------------------------
	 switch ($valorConsolidado) {
		case 0: $imagen = "";
			break;
		case 1: $imagen = "<img src='imagenes/consolidado_p01.jpg' width='20' height='20'/>";
			break;
	    case 2: $imagen = "<img src='imagenes/consolidado_p03.jpg' width='20' height='20'/>";
			break;
		case 3: $imagen = "<img src='imagenes/consolidado_p04.jpg' width='20' height='20'/>";
			break;
	}
	 
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
	 $id=$fa['Activo'].'|'.$fa['Naturaleza'];
	  ?>
    <tr class='trListaBody' onclick='mClkAC(this,"registro")|clickAC();$("#sel_detalle").val("")'  id='<?=$id;?>'>
	  <? echo"  <td>$imagen</td>
		<td align='center'>".$fa['Activo']."</td>
		<td align='left'>".$fa['Descripcion']."</td>
		<td align='center'>".$f_sitActivo['Descripcion']."</td>
		<td align='center'>".$f_mostrar['DescripClasificacionActivo']."</td>
		<td align='center'>".$f_ubicaciones['Descripcion']."</td>
		<td align='center'>".$fa['Dimensiones']."</td>
		<td align='center'>".$fa['CodigoBarras']."</td>";?>
	</tr>
    <? }
 }
  ?>
</table>
</div></td></tr>
  </table>
   
   
  </td>
  
  <td>
  <table>
  <tr>
    <td><input type="button" id="btAgregar" name="btAgregar" value="==>" onclick="insertarLineaAgroCons(this.form);"/></td>
  </tr>
  <tr>
    <td><input type="button" id="btQuitar" name="btQuitar" value="<==" onclick="quitarLinea(this, 'detalle');"/></td>
  </tr>
 </table>
  </td>
  
  <td>
  <form name="frmdetalles" id="frmdetalles">
<input type="hidden" id="sel_detalle" />
<input type="hidden" id="can_detalle" />
<table width="400" class="tblLista">
 <tr> <!--<input type="hidden" id="registro" name="registro"/>-->
  <td><div id="rows"></div></td>
  <td align="left">Activos Relacionados:</td>
  <td align="right">
   <input type="button" name="btgrabar" id="btgrabar" class="btLista" value="Grabar" onclick="grabarAgrupacionConsolidacion(this.form);"/></td>
  </tr>
  </table>
<table align="center" cellpadding="0" cellspacing="0"><tr><td valign="top" style="height:100px; width:150px;">
<table align="center" width="400px"><tr><td align="center"><div style="overflow:scroll; height:200px; width:410px;">
<table width="390px" class="tblLista">
    <tr class="trListaHead">
        <!--<th scope="col" width="200"></th>
        <th scope="col">Depreciaci&oacute;n(%)</th>-->
    </tr>
                
    <tbody id="lista_detalle">
    <?
     if($fNroActivo!=''){
		 $sql = "select 
					   a.CodigoInterno,
					   a.Activo,
					   a.Descripcion,
					   b.Descripcion as DescpUbicacion
				  from 
					   af_activo a 
					   inner join af_ubicaciones b on (b.CodUbicacion=a.Ubicacion)
				 where 
					   a.ActivoConsolidado='".$fNroActivo."'"; //echo $sql;
		 $qry = mysql_query($sql) or die ($sql.mysql_error());
		 $field = mysql_fetch_array($qry);
    ?>
    <tr class="trListaBody" onclick="mClk(this, 'sel_detalle');$('#registro').val('');" id="det_<?=$field['Activo'];?>">
	 <td><img src="imagenes/asignar2.jpg" style="width:20px;height:20px;visibility:visible"/>Interno: <input type="text" id="c_Interno" name="c_Interno" size="23" value="<?=$field['CodigoInterno'];?>" disabled/> 
     # Activo: <input type="text" id="numero_activo" name="numero_activo" size="23" value="<?=$field['Activo'];?>" disabled/> 
     Descripci&oacute;n: <input type="text" id="DescripcionActivo" name="DescripcionActivo" size="67" value="<?=$field['Descripcion'];?>" disabled/>
       Ubicaci&oacute;n: <input type="text" id="ubicacionActivo" name="ubicacionActivo" size="69" value="<?=$field['DescpUbicacion'];?>" disabled/> 
       Sitacui&oacute;n de Alquiler: <input type="text" id="situacionAlquiler" name="situacionAlquiler" size="54" value="" disabled/></td>
     </tr>
     <!--<td># Activo: <input type="text" id="numero_activo" name="numero_activo"/></td>-->
	<? //echo "|".$field['Activo'];
	 }
	?>
    
    </tbody>
</table>
</div></td></tr></table>
</td></tr></table>
<!-- </form> -->
<!-- ******************************************************************************************  -->

</form>
</td>
</tr>
</table>

<!-- ******************************************************************************************  -->

</body>
</html>
