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
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript.js"></script>
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
		<td class="titulo">Detalles del Activo</td>
		<td align="right"><a class="cerrar" href="" onclick="window.close();">[cerrar]</a></td>
	</tr>
</table>
<hr width="100%" color="#333333" />

<? 
/// FILTRO QUE PERMITE REALIZAR BUSQUEDAS ESPECIFICAS
if(!$_POST) $fOrganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"]; else $cOrganismo = "checked"; 
if(!$_POST){ $fEstado = 'PE'; $cEstado = "checked";} 
$filtro = "";

//echo $fOrganismo;echo $fDependencia;echo $fTipoActivo;

//if ($fOrganismo != "") { $filtro .= " AND (CodOrganismo = '".$fOrganismo."')"; $cOrganismo = "checked"; }else $dOrganismo = "disabled";
if ($cod_activo != "") $filtro .= " AND (Activo= '".$cod_activo."')";
if ($contabilidad!= "") $filtro .= " AND (CodContabilidad = '".$contabilidad."')";
//if ($fMoneda!= "")  $filtro .= " AND (Moneda = '".$fMoneda."')";
if ($periodo!= "")  $filtro .= " AND (Periodo = '".$periodo."')";

echo"<form name='frmentrada' id='frmentrada' action='af_listactivosagregarverdetallesinfcontable.php?limit=0&registro=$registro' method='POST'>
<table class='tblForm' width='1000' height='50'>
<tr>
   <td>
   <table>
   <tr>
       <td align='right' width='85'>Activo:</td>
       <td align='left' width='200'><input type='text' name='registro' id='registro' value='".$registro."' disabled/></td>
       <td align='right' width='90'>Contabilidad:</td>";?>
	   <td><select id="contabilidad" name="contabilidad" disabled>
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
       <? 
	   echo"
	    <td align='right' width='90'>Moneda:</td>
		<td align='left' width='200'>
           <select name='fMoneda' id='fMoneda' class='selectMed' $cMoneda>
		    <option value='L'>Local</option>
			</select></td>
	   <td align='right'>Per&iacute;odo>= </td>
       <td width='248'><input type='text' name='periodo' id='periodo' value='' maxlenght='7' size='6'/></td>	   
   </tr>
   </table>
   </td>
</tr>
</table>
<center><input type='submit' name='btBuscar' value='Buscar'/></center>
</form>";

  /// CONSULTA PARA OBTENER DATOS DE LA TABLA A MOSTRAR
  $sa= "select 
              * 
		 from 
		      af_activohistoricontable 
        where 
              CodActivo='".$_GET['registro']."'$filtro
             order by 
                      CodActivo"; //echo $sa;
  $qa= mysql_query($sa) or die ($sa.mysql_error());
  $ra= mysql_num_rows($qa);
  
?>

<form id="tabs" name="tabs">
<table class="tblForm" width="1000">
<tr>
  <td>

<!--<table width="1000" class="tblLista">
 <tr> <input type="hidden" id="registro" name="registro"/>
  <td><div id="rows"></div></td>
  <td align="right"></td>
  <td align="right">
    <input type="button" name="btAgregar" id="btAgregar" class="btLista" value="Agregar" onclick="cargarPaginaAgregar(this.form, 'af_listactivosagregar.php?regresar=af_listactivos');"/>
    <input type="button" name="btVer" id="btVer" class="btLista" value="Ver" onclick="cargarOpcion(this.form,'af_listactivosver.php','BLANK', 'height=600, width=920, left=250, top=50, resizable=no');"/>
    <input type="button" name="btModificar" id="btModificar" class="btLista" value="Modificar" onclick="cargarOpcionListActEditar(this.form, 'af_listactivosmodificar.php?regresar=af_listactivos','SELF')"/>
    <input type="button" name="btBorrar" id="btBorrar" class="btLista" value="Borrar" onclick="EliminarRegistros(this.form,'af_listactivos.php','1','APLICACIONES');"/>
    <input type="button" name="btSustento" id="btSustento" class="btLista" value="Sustento" onclick=""/>
    <input type="button" name="btDepriaciacion" id="btDepreciacion" class="btLista" value="Depreciaci&oacute;n" onclick=""/>
   <input type="button" name="btTransferir" id="btTransferir" class="btLista"  value="Transferir" onclick="cargarOpcionTransferir(this.form,'af_transferirdatosgenerales.php', 'BLANK','height=800, width=1000, left=200, top=70, resizable=yes');" />
    <input type="button" name="btUnidRecibir" id="btUnidRecibir" class="btLista"  value="UnidxRecibir" onclick="cargarOpcion(this.form, 'af_catastroeditar.php', 'SELF');"/>
  </tr>
</table>-->

<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1000px; height:400px;">
<table width="1000" class="tblLista" border="0">
  <tr class="trListaHead">
		<th width="50" scope="col">Per&iacute;odo</th>
		<th width="25" scope="col">Dep. %</th>
		<th width="60" scope="col">Monto Original</th>
		<th width="60" scope="col">[+ / -] Ajustes</th>
    	<th width="100" scope="col">Monto Inicio Mes</th>
 	    <th width="150" scope="col">Acumulada A&ntilde;o Anterior</th>
        <th width="150" scope="col">Acumulada Mes Anterior</th>
        <th width="60" scope="col">[+ / -] Ajustes</th>
        <th width="100" scope="col">Mensual</th>
        <th width="100" scope="col">Acumulada Anual</th>
        <th width="70" scope="col">Acumulada</th>
  </tr>
  <?
  
  if($ra!=0){
      
   for($i=0;$i<$ra;$i++){
     $fa= mysql_fetch_array($qa);
	 if($fa['TipoActivo']=='I') $tipoActivo= 'Individual'; else $tipoActivo = 'Principal';
	 if($fa['Estado']=='A') $estado = 'Activo';else $estado = 'Inactivo';
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
						 af_clasificacionactivo ca
					where
					     cc.CodCentroCosto = '".$fa['CentroCosto']."' and 
						 ca.CodClasificacion = '".$fa['Clasificacion']."'";
	 $q_mostrar = mysql_query($s_mostrar) or die ($s_mostrar.mysql_error());
	 $f_mostrar = mysql_fetch_array($q_mostrar);
	 /// -------------------------------------------
     $s_ubicaciones = "select * from af_ubicaciones where CodUbicacion = '".$fa['Ubicacion']."'";
	 $q_ubicaciones = mysql_query($s_ubicaciones) or die ($s_ubicaciones.mysql_error());
	 $f_ubicaciones = mysql_fetch_array($q_ubicaciones);
	 /// -------------------------------------------
     //$id= $fa['NroOrden']."|".$fa['Secuencia'];
     
    echo"<tr class='trListaBody' onclick='mClkAF(this, \"registro\");' id='".$fa['Activo']."'>
		<td align='center'>'".$fa['Periodo']."'</td>
		<td align='center'>'".$fa['Periodo']."'</td>
		<td align='center'>'".$fa['Periodo']."'</td>
		<td align='center'>'".$fa['Periodo']."'</td>
		<td align='center'>'".$fa['Periodo']."'</td>
		<td align='center'>'".$fa['Periodo']."'</td>
		<td align='center'>'".$fa['Periodo']."'</td>
		<td align='center'>'".$fa['Periodo']."'</td>
		<td align='center'>'".$fa['Periodo']."'</td>
		<td align='center'>'".$fa['Periodo']."'</td>
		<td align='center'>'".$fa['Periodo']."'</td>
		<td align='center'>'".$fa['Periodo']."'</td>
	</tr>";
    }
 }
?>
</table>
</div></td></tr>
</table>
</td></tr></table>
</form>
</body>
</html>
