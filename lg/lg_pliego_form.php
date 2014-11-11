
<?php
	session_start();
	if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
	//	------------------------------------
	
	//	------------------------------------
	
	//extract($_POST);
	//extract($_GET);
	//	------------------------------------
	include("../lib/fphp.php");
	include("../lib/lg_fphp.php");
	//	------------------------------------
	//list($codorganismo, $codrequerimiento, $secuencia, $numero) = split("[.]", $registro);
	
	
	foreach($_GET as $nombreCampo => $valor)
	{
		$nombreCampo = $valor;
	}
	

	$vectorCodRequerimiento = array();
	$condicionRequerimiento = array();
	$condSecuenReque = array();
	
	for($i = 0; $i < count($registro); $i++)
	{
		$vectorCodRequerimiento[$i] = split("[.]", $registro[$i]);
		
		
	}
	
	for($i = 0; $i < count($registro); $i++)
	{
		
		$condicionRequerimiento[$i] = "'". $vectorCodRequerimiento[$i][1]."'";
		$condSecuenReque[$i] = $vectorCodRequerimiento[$i][2];
		
	}
	
	$cadenaCondicionReque = implode(',',$condicionRequerimiento);
	$cadenaCondicionSecue = implode(',',$condSecuenReque);
	
	
	//**********INCLUSION DE ARCHIVOS PARA CONSULTA*******//
	include_once ("../clases/MySQL.php");
		
	include_once("../comunes/objConexion.php");
	//**************************************************//


	$sql = "SELECT rda.CodRequerimiento, AI.CodPersonaAsistente,AI.CodPersonaAsistente2,AI.CodPersonaDirector
			FROM lg_requedetalleacta AS rda
			JOIN lg_actainicio as AI on AI.CodActaInicio=rda.CodActaInicio
			where
			rda.CodRequerimiento in (".$cadenaCondicionReque.") and Estado='PR'
			GROUP BY rda.CodRequerimiento";

	$resp = $objConexion->consultar($sql,'fila');//verifico si ya existe un acta de inicio para el requerimiento
	

	$requeActaValidar = $objConexion->getCantidadFilasConsulta();
	
    $sql1 ="select p.NomCompleto, p.Ndocumento,

			pu.DescripCargo
			
			from mastpersonas as p
			
			join mastempleado as me on p.CodPersona=me.CodPersona
			
			join rh_puestos as pu on me.CodCargo=pu.CodCargo
			
			where me.CodEmpleado='".$resp['CodPersonaAsistente']."'";    

    $resultado1 = $objConexion->consultar($sql1,'fila');			
	
	$sql2 ="select p.NomCompleto, p.Ndocumento,

			pu.DescripCargo
			
			from mastpersonas as p
			
			join mastempleado as me on p.CodPersona=me.CodPersona
			
			join rh_puestos as pu on me.CodCargo=pu.CodCargo
			
			where me.CodEmpleado='".$resp['CodPersonaAsistente2']."'";    
	
	$resultado2 = $objConexion->consultar($sql2,'fila');	

	$sql3 ="select p.NomCompleto, p.Ndocumento,

			pu.DescripCargo
			
			from mastpersonas as p
			
			join mastempleado as me on p.CodPersona=me.CodPersona
			
			join rh_puestos as pu on me.CodCargo=pu.CodCargo
			
			where me.CodEmpleado='".$resp['CodPersonaDirector']."'";    
	
	$resultado3 = $objConexion->consultar($sql3,'fila');	
	
	//	consulto los datos de la cotizacion
	$sql = "SELECT 
				c.*,
				p.NomCompleto AS NomProveedor,
				i.CodImpuesto,
				i.FactorPorcentaje
			FROM
				lg_cotizacion c
				INNER JOIN lg_requerimientosdet rd ON (c.CodOrganismo = rd.CodOrganismo AND
													   c.CodRequerimiento = rd.CodRequerimiento AND
													   c.Secuencia = rd.Secuencia)
				INNER JOIN mastpersonas p ON (c.CodProveedor = p.CodPersona)
				INNER JOIN mastproveedores mp ON (c.CodProveedor = mp.CodProveedor)
				LEFT JOIN masttiposervicioimpuesto tsi ON (mp.CodTipoServicio = tsi.CodTipoServicio)
				LEFT JOIN mastimpuestos i ON (tsi.CodImpuesto = i.CodImpuesto AND tsi.CodImpuesto = '".$_PARAMETRO["IGVCODIGO"]."')
			WHERE
				rd.CodRequerimiento in (".$cadenaCondicionReque.") AND
				c.Secuencia in (".$cadenaCondicionSecue.")
				GROUP BY p.NomCompleto";

	$resp = $objConexion->consultar($sql,'matriz');//nombre de los proveedores sin repetir
	

	
	$cantProveedores = $objConexion->getCantidadFilasConsulta();
	
	if ($cantProveedores < 1)
	{
		
		echo "<script language='javascript'>
			//alert('No se han asociado ningún proveedor a los requerimientos asociados');
			alert('Debe asociar al menos 1 proveedor');
			window.open('lg_cotizaciones_invitar.php?concepto=01-0006&limit=0&filtrar=default', target=\"main\");
		</script>";
		
	}
	
	if(isset($tipoReque) && ($tipoReque == 'commodity'))
	{
		$sql2 = "SELECT 
				c.*,
				p.NomCompleto AS NomProveedor,
				i.CodImpuesto,
				i.FactorPorcentaje, d.Descripcion
			FROM
				lg_cotizacion c
				INNER JOIN lg_requerimientosdet rd ON (c.CodOrganismo = rd.CodOrganismo AND
													   c.CodRequerimiento = rd.CodRequerimiento AND
													   c.Secuencia = rd.Secuencia)
				INNER JOIN mastpersonas p ON (c.CodProveedor = p.CodPersona)
				INNER JOIN mastproveedores mp ON (c.CodProveedor = mp.CodProveedor)
				INNER JOIN lg_commoditysub AS d 
				LEFT JOIN masttiposervicioimpuesto tsi ON (mp.CodTipoServicio = tsi.CodTipoServicio)
				LEFT JOIN mastimpuestos i ON (tsi.CodImpuesto = i.CodImpuesto AND tsi.CodImpuesto = '".$_PARAMETRO["IGVCODIGO"]."')
			WHERE
				rd.CodRequerimiento in (".$cadenaCondicionReque.") AND
				c.Secuencia in (".$cadenaCondicionSecue.")
				and (rd.CommoditySub = d.Codigo) group by d.Descripcion";
		
	} else {
	
	$sql2 = "SELECT 
				c.*,
				p.NomCompleto AS NomProveedor,
				i.CodImpuesto,
				i.FactorPorcentaje, d.Descripcion
			FROM
				lg_cotizacion c
				INNER JOIN lg_requerimientosdet rd ON (c.CodOrganismo = rd.CodOrganismo AND
													   c.CodRequerimiento = rd.CodRequerimiento AND
													   c.Secuencia = rd.Secuencia)
				INNER JOIN mastpersonas p ON (c.CodProveedor = p.CodPersona)
				INNER JOIN mastproveedores mp ON (c.CodProveedor = mp.CodProveedor)
				INNER JOIN lg_itemmast AS d ON ( d.CodItem = rd.CodItem )
				LEFT JOIN masttiposervicioimpuesto tsi ON (mp.CodTipoServicio = tsi.CodTipoServicio)
				LEFT JOIN mastimpuestos i ON (tsi.CodImpuesto = i.CodImpuesto AND tsi.CodImpuesto = '".$_PARAMETRO["IGVCODIGO"]."')
			WHERE
				rd.CodRequerimiento in (".$cadenaCondicionReque.") AND
				c.Secuencia in (".$cadenaCondicionSecue.") group by d.Descripcion";
	}
	
	$resp2 = $objConexion->consultar($sql2,'matriz');//nombre de los item sin repetir

echo $registroCodSecGenerarActa;
/*echo $cadenaCondicionReque.'<br />';
echo $cadenaCondicionSecue.'<br />';*/

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../cp/css1.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="../css/custom-theme/jquery-ui-1.8.16.custom.css" charset="utf-8" />

<link href="css1.css" rel="stylesheet" type="text/css" charset="utf-8" />


<script type="text/javascript" src="../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery.prettyPhoto.js" charset="utf-8"></script>


<script type="text/javascript" language="javascript" src="../cp/fscript.js"></script>
<script type="text/javascript" src="../cp/ckeditor/ckeditor.js"></script>
<script type="text/javascript" language="javascript" src="../cp/ckeditor/sample.js"></script>
<link href="../cp/ckeditor/sample.css" rel="stylesheet" type="text/css" />

<!-- INCLUSION DE LOS ARCHIVOS FUNCIONALIDADES CES -->
    <script  type='text/JavaScript' src='../js/vEmergente.js' charset="utf-8"></script>
    
    <script type='text/JavaScript' src='../js/AjaxRequest.js' charset="utf-8"></script>

    <script type='text/JavaScript' src='../js/xCes.js' charset="utf-8"></script>
    
    <!-- <script type='text/JavaScript' src='../js/comun.js' charset="utf-8"></script>--> 
    
    <script type='text/JavaScript' src='../js/dom.js' charset="utf-8"></script>

	<script type='text/JavaScript' src='js/funcionalidadCes.js' charset="utf-8"></script>
<!--*********************************************** -->

    <!-- INCLUSION DE LOS ARCHIVOS FUNCIONALIDADES CES -->
	<link rel="stylesheet" href="../css/vEmergente.css" type="text/css" charset="utf-8" />

    <link rel="stylesheet" href="../css/estiloCes.css" type="text/css"  charset="utf-8" />
    <!--*********************************************** -->
        
<style type="text/css">
<!--
UNKNOWN {FONT-SIZE: small}
#header {FONT-SIZE: 93%; BACKGROUND: url(../cp/imagenes/bg.gif) #dae0d2 repeat-x 50% bottom; FLOAT: left; WIDTH: 100%; LINE-HEIGHT: normal}
#header UL {PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 10px; LIST-STYLE-TYPE: none}
#header LI {
        PADDING-RIGHT: 0px; PADDING-LEFT: 9px; BACKGROUND: url(../cp/imagenes/left.gif) no-repeat left top; FLOAT: left; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 0px}
#header A {
        PADDING-RIGHT: 15px; DISPLAY: block; PADDING-LEFT: 6px; FONT-WEIGHT: bold; BACKGROUND: url(../cp/imagenes/right.gif) no-repeat right top; FLOAT: left; PADDING-BOTTOM: 4px; COLOR: #765; PADDING-TOP: 5px; TEXT-DECORATION: none}
#header A { FLOAT: none}
#header A:hover {  COLOR: #333 }
#header #current { BACKGROUND-IMAGE: url(../cp/imagenes/left_on.gif)}
#header #current A { BACKGROUND-IMAGE: url(../cp/imagenes/right_on.gif); PADDING-BOTTOM: 5px; COLOR: #333 }
-->
</style>
</head>
<body onload="consultarEstadoReq('');inicializarCodRequerimientoSecuencia('<? echo $cadena = implode(',',$registro); ?>');">
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Pliego de condiciones</td>
		<td align="right"><a class="cerrar" href="../lg/framemain.php"  onclick="cargarPagina(document.getElementById('frmentrada'),'<?=$regresar?>.php?&limit=0')";>[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?
/* list($cod_tipocuenta, $cod_documentocompleto)=SPLIT('[|]', $_POST['registro']);
 //echo $cod_tipocuenta;
 
 $s="select * from 
                   cp_documentointerno 
			  where 
			       Cod_DocumentoCompleto='$cod_documentocompleto' and 
				   Cod_TipoDocumento = '$cod_tipocuenta'";
 $q=mysql_query($s) or die ($s.mysql_error());
 if(mysql_num_rows($q)!=0){ 
   $f=mysql_fetch_array($q);
 }*/	
?>
<form name="fromActa" id="fromActa" action="" method="">
<div style="width:895px; height:15px" class="divFormCaption"></div>
<table class="tblForm" width="895px" border="0">
<tr>
</tr>
<tr>
		<td class="tagForm">*Primer Firmante:</td>
		<td class="gallery clearfix">
			<input type="hidden" id="asistenteActaInicio" value="<?=$resp['CodPersonaDirector']?>" />
			<input type="text" disabled="disabled" class="disabled" id="NomAsistenteActaInicio" style="width:220px;" value="<?=$resultado3['NomCompleto']?>" />
			
			<!--<a href="../lib/listas/listado_empleados.php?filtrar=default&cod=asistenteActaInicio&nom=NomAsistenteActaInicio&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]" style=" <?=$display_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />            </a>	 --> </td>
	</tr>

    <tr>
		<td class="tagForm">*Segundo Firmante:</td>
		<td class="gallery clearfix">
			<input type="hidden" id="asistenteActaInicio" value="<?=$resp['CodPersonaAsistente']?>" />
			<input type="text" disabled="disabled" class="disabled" id="NomAsistenteActaInicio" style="width:220px;" value="<?=$resultado1['NomCompleto']?>" />
			
			<!--<a href="../lib/listas/listado_empleados.php?filtrar=default&cod=asistenteActaInicio&nom=NomAsistenteActaInicio&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]" style=" <?=$display_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />            </a>	 --> </td>
	</tr>
   <tr>
		<td class="tagForm">*Tercer Firmante:</td>
		<td class="gallery clearfix">
			<input type="hidden" id="directorActaInicio" value="<?=$resp['CodPersonaAsistente2']?>" />
			<input type="text" disabled="disabled" class="disabled" id="NomDirectorActaInicio" style="width:220px;" value="<?=$resultado2['NomCompleto']?>" />
			</td>
	</tr>

 <tr>
		<td class="tagForm">*Fecha Limite:</td>
		<td class="gallery clearfix">
			<input type="hidden" id="flimite" value="<?=$resp[0]['FechaLimite']?>" />
			<input type="text" disabled="disabled" class="disabled" id="flim" style="width:220px;" value="<?=$resp[0]['FechaLimite']?>" />
			
            	</td>
	</tr>
<tr>
 <td colspan="4"><div class="divFormCaption" align="center"><b></b></div></td>
</tr>
<tr>
  <td colspan="4"><!--<textarea class="ckeditor" cols="90" id="editor1" name="editor1" rows="15"></textarea>-->  </td>
</tr>
</table>
<center> 

<div class="divMsj" style="width:1100px">Campos Obligatorios *</div>
    <input type="button" value="Guardar Registro" onclick="guardarPliego('<? echo $resp2[0]['NroSolicitudCotizacion'].'/'.$resp2[0]['CodRequerimiento'].'/'.$resp2[0]['Secuencia']  ?>','guardar','<? echo $resp2[0]['Numero']?>');" />
    <input type="button" value="Cancelar" onClick="cargarPagina(this.form,'lg_cotizaciones_invitar.php?concepto=01-0006&limit=0&filtrar=default');"  />
<!--    -->
</center>
</form>
</body>
</html>
