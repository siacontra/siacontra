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
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript2.js"></script>
<script type="text/javascript" src="../js/funciones.js" charset="utf-8"></script>
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
		<td class="titulo">Transferir Activos desde Log&iacute;stica</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table>
<hr width="100%" color="#333333" />

<? 
/// FILTRO QUE PERMITE REALIZAR BUSQUEDAS ESPECIFICAS
if(!$_POST) $forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];

$filtro = "";

if ($forganismo != "") { $filtro .= " AND (CodOrganismo = '".$forganismo."')";  }
if ($fOrdenCompra != "") { $filtro .= " AND (NroOrden = '".$fOrdenCompra."')"; $cOrdenCompra = "checked"; } else $dOrdenCompra = "disabled"; // listo

echo"<form name='frmentrada' id='frmentrada' action='af_transferiractivos.php?limit=0' method='POST'>
<table class='tblForm' width='1016' height='50'>
<tr>
   <td>
   <table>
   <tr>
       <td width='5></td>
       <td align='right'>Organismo:</td>
       <td align='left'>
           <select name='fOrganismo' id='fOrganismo' class='selectBig' $cOrganismo>";
           getOrganismos($_SESSION['ORGANISMO_ACTUAL'],3);
           echo"
           </select>
       </td>
       <td width='40'></td>
       <td align='right'>Orden de Compra:</td>
       <td align='left'>
           <input type='checkbox' id='chkOrdenCompra' name='chkOrdenCompra' value='1' $cOrdenCompra onclick='enabledOrdenCompra(this.form);'/>
           <input type='text' id='fOrdenCompra' name='fOrdenCompra' value='$fOrdenCompra' $dOrdenCompra/>
       </td>
       <td width='300'></td>
   </tr>
   </table>
   </td>
</tr>
</table>
<center><input type='submit' name='btBuscar' value='Buscar'/></center>
</form>";
     
?>

<form id="tabs" name="tabs">
<table class="tblForm" width="1000">
<tr>
  <td>

<table width="1008" align="center">
<tr>
  <td>
	<div id="header">
	<ul>
	<!-- CSS Tabs PESTA�AS OPCIONES -->
	<li><a onClick="document.getElementById('tab1').style.display='block'; document.getElementById('tab2').style.display='none';" href="#">Ingresos x Activar</a></li>
	<li><a onClick="document.getElementById('tab1').style.display='none'; document.getElementById('tab2').style.display='block';" href="#">Activos Transferidos</a></li> 
	</ul>
	</div>
  </td>
</tr>
</table>

<!-- PRIMERA PESTA�A -->
<div id="tab1" style="display: block;">

<table width="1005" class="tblLista">
 <tr>
  <td><div id="rows"></div></td>
  <td align="right">Transferir a:</td>
  <td align="left">
    <input type="button" name="btActivoMayor" id="btActivoMayor" class="btLista"  value="Activo Mayor" onclick="cargarOpcionTransferir(this.form,'af_transferirdatosgenerales.php?Nat=1', 'BLANK','height=650, width=950, left=200, top=70, resizable=yes');" disabled/>
  <input type="button" name="btActivoMenor" id="btActivoMenor" class="btLista"  value="Activo Menor" onclick="cargarOpcionTransferir(this.form,'af_transferiractivosmenores.php?Nat=2', 'BLANK','height=650, width=950, left=200, top=70, resizable=yes');" disabled/></td>
    
    
   <!-- <input type="button" name="btUnidRecibir" id="btUnidRecibir" class="btLista"  value="UnidxRecibir" onclick="cargarOpcion(this.form, 'af_catastroeditar.php', 'SELF');"/>-->
  </tr>
</table>

<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1005px; height:350px;">
<table width="200%" class="tblLista" border="0">
<thead>
  <tr class="trListaHead">
		<th width="150" scope="col">Orden de Compra</th>
		<th scope="300">Secuencia</th>
		<th width="44" scope="col">#</th>
		<th width="40" scope="col">F.Recepci&oacute;n</th>
		<th width="250" scope="col">Descripci&oacute;n</th>
    	<th width="130" scope="col">Codigo Barras</th>
 	    <th width="150" scope="col">N&uacute;mero de Serie</th>
        <th width="124" scope="col">Modelo</th>
        <th width="124" scope="col">C. de Costos</th>
        <th width="124" scope="col">Clasificaci&oacute;n</th>
        <th width="124" scope="col">Monto Local</th>
        <th width="100" scope="col">Factura #</th>
        <th width="80" scope="col">F. Factura</th>
        <th width="124" scope="col">Proveedor</th>
        <th width="80" scope="col">Facturado</th>
        <th width="80" scope="col">Nro.Placa</th>
        <th width="150" scope="col">Marca</th>
        <th width="80" scope="col">Color</th>
  </tr>
 </thead>
  <?
   $sql = "select * from mastparametros where ParametroClave='ACTTRANSLOG'";
   $qry = mysql_query($sql)  or die ($sql.mysql_error());
   $field = mysql_fetch_array($qry);
   
  /// CONSULTA PARA OBTENER DATOS DE LA TABLA A MOSTRAR
  $sa= "select 
               * 
		 from 
		      lg_activofijo 
        where 
              FlagFacturado='".$field['ValorParam']."' and 
			  CodOrganismo='".$_SESSION['ORGANISMO_ACTUAL']."' and 
              Estado='PE' $filtro
     order by 
              NroOrden, Secuencia, NroSecuencia"; //echo $sa;
  $qa= mysql_query($sa) or die ($sa.mysql_error());
  $ra= mysql_num_rows($qa);
  
  if($ra!=0){
      
   for($i=0;$i<$ra;$i++){
     $fa= mysql_fetch_array($qa);
     $id= $fa['NroOrden']."|".$fa['Secuencia']."|".$fa['NroSecuencia']."|".$fa['CodOrganismo'];
	 $clasificacion = $fa['Clasificacion'];
	 /// --------------------------------------------------------------------------
	 if($fa['FlagFacturado']=='S') $facturado = 'checked';
	 /// --------------------------------------------------------------------------
	 /// --------------------------------------------------------------------------
	 $s_marcas = "select * from lg_marcas where CodMarca='".$fa['CodMarca']."'";
     $q_marcas = mysql_query($s_marcas) or die ($s_marcas.mysql_error()); 
	 $f_marcas = mysql_fetch_array($q_marcas); 
	 /// --------------------------------------------------------------------------
	 $montoLocal = number_format($fa['Monto'],2,',','.'); 
	 /// --------------------------------------------------------------------------
	 list($a,$m,$d)=SPLIT('[-]',$fa['ObligacionFechaDocumento']); 
	 $ObligacionFechaDocumento = $d.'-'.$m.'-'.$a;
	 /// --------------------------------------------------------------------------
	 $s_color = "select * from mastmiscelaneosdet where CodMaestro='COLOR' and CodDetalle='".$fa['Color']."'";
	 $q_color = mysql_query($s_color) or die ($s_color.mysql_error());
	 $f_color = mysql_fetch_array($q_color);
	 /// --------------------------------------------------------------------------
	 $s_proveedor = "select * from mastpersonas where CodPersona = '".$fa['CodProveedor']."'";
	 $q_proveedor = mysql_query($s_proveedor) or die ($s_proveedor.mysql_error());
	 $f_proveedor = mysql_fetch_array($q_proveedor);
     /// --------------------------------------------------------------------------
	 
    echo"<tr class='trListaBody' onclick='mClk(this,\"registro\")| activarBotones(tabs,\"$clasificacion\");' id='$id'>
		<td align='right'>".$fa['NroOrden']."</td>
		<td align='right'>".$fa['Secuencia']."</td>
        <td align='right'>".$fa['NroSecuencia']."</td>
        <td align='right'>".$fa['FechaIngreso']."</td>
        <td align='left'>".$fa['Descripcion']."</td>
        <td align='right'>".$fa['CodBarra']."</td>
        <td align='right'>".$fa['NroSerie']."</td>
		<td align='right'>".$fa['Modelo']."</td>
		<td align='right'>".$fa['CodCentroCosto']."</td>
        <td align='right'>".$fa['CodClasificacion']."</td>
        <td align='right'>$montoLocal</td>
	   	<td align='center'>".$fa['ObligacionNroDocumento']."</td>
		<td align='center'>$ObligacionFechaDocumento</td>
		<td>".htmlentities($f_proveedor['NomCompleto'])."</td>
		<td align='center'><input type='checkbox' $facturado disabled /></td>
		<td align='center'>".$fa['NroPlaca']."</td>
		<td align='left'>".$f_marcas['Descripcion']."</td>
		<td align='left'>".$f_color['Descripcion']."</td>
	</tr>";
    }
 }
  ?>
</table>
</div></td></tr>
</table>
</div>
<!-- SEGUNDA PESTA�A -->
<div id="tab2" style="display: none;">
<div style="width:1000px; height:15px" class="divFormCaption">Activos Transferidos</div>

<input type="hidden" name="registro" id="registro" />
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1005px; height:350px;">
<table width="200%" class="tblLista" border="0">
<thead>
  <tr class="trListaHead">
        <th width="10">Activo</th>
        <th width="250">Descripci&oacute;n</th>
        <th width="10">Cod. Barras</th>
        <th width="10">Cod. Interno</th> 
        <th width="10">N&uacute;mero Serie</th>
        <th width="10">Modelo</th>
        <th width="10">N&uacute;mero Serie Motor</th> 
        <th width="10">N&uacute;mero Placa</th>     
        <th width="10">Tipo Activo</th>
		<th width="10">Naturaleza</th>
        <th width="10">MontoLocal</th>
        <th width="10">Factura #</th>
        <th width="25">Fecha Factura</th>
        <th width="25">Proveedor</th>
  </tr>
  </thead>
  <?
    $s_activo = "select * from af_activo order by Activo";
	$q_activo = mysql_query($s_activo) or die ($s_activo.mysql_error());
	$r_activo = mysql_num_rows($q_activo);
	
	for($a=0; $a<$r_activo; $a++){
	   $f_activo = mysql_fetch_array($q_activo);	
	   if($f_activo['TipoActivo']=='I') $tipoActivo = 'Individual'; elseif($f_activo['TipoActivo']=='P') $tipoActivo='Principal';
	   if($f_activo['Naturaleza']=='AN') $naturaleza = 'Activo Normal'; elseif($f_activo['Naturaleza']=='AM') $naturaleza = 'Activo Menor';
	   list($ffano, $ffmes, $ffdia) = split('[-]',$f_activo['FacturaFecha']);$fechaFactura = $ffdia.'-'.$ffmes.'-'.$ffano;
	   $montoLocal = number_format($f_activo['MontoLocal'],2,',','.');
	   echo" <tr class='trListaBody'>
	     <td align='center'>".$f_activo['Activo']."</td>
		 <td>".$f_activo['Descripcion']."</td>
		 <td align='right'>".$f_activo['CodigoBarras']."</td>
		 <td align='center'>".$f_activo['CodigoInterno']."</td>
		 <td align='right'>".$f_activo['NumeroSerie']."</td>
		 <td align='right'>".$f_activo['Modelo']."</td>
		 <td align='right'>".$f_activo['NumeroSerieMotor']."</td>
		 <td align='right'>".$f_activo['NumeroPlaca']."</td>
		 <td align='center'>$tipoActivo</td>
		 <td align='center'>$naturaleza</td>
		 <td align='right'>$montoLocal</td>
		 <td align='right'>".$f_activo['FacturaNumeroDocumento']."</td>
		 <td align='center'>$fechaFactura</td>
		 <td></td>
		 
	   </tr>";
	}
  ?>
</table>
</div>
</td></tr></table>
</div>
</td></tr></table>
</form>
</body>
</html>
