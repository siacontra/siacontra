
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
	
	//**********INCLUSION DE ARCHIVOS PARA CONSULTA*******//
	include_once ("../clases/MySQL.php");
		
	include_once("../comunes/objConexion.php");
	//**************************************************//
	
	foreach($_GET as $nombreCampo => $valor)
	{
		$$nombreCampo = $valor;
	}
	
	/************VERIFICAO EL ESTADO DE LA ADJUDICACION*********************/
	$sqlEstado = "SELECT A.Estado
				FROM lg_informeadjudicacion as A
				join lg_informerecomendacion as B on A.CodInformeRecomendacion=B.CodInformeRecomendacion
				join lg_evaluacion as C on C.CodEvaluacion=B.CodEvaluacion
				where A.Estado='AD' and C.CodEvaluacion=".$codEvaluacion;

	$resultadoEstadoAdju = $objConexion->consultar($sqlEstado,'fila');//
	
	if($resultadoEstadoAdju['Estado'] == 'AD')
	{
	
		echo '<script language="javascript">
				alert(\'La orden de compra para esta recomendación ya fue generada\nNo se puede modificar la recomendación\');
				location.href="lg_evaluacion_busqueda.php";
			</script>';
	
	}
	/******************************************************/

	/************VERIFICAO SI SE DECLARO DESIERTO*********************/
	$sqlDesierto = "SELECT A . *
					FROM lg_declarar_desierto AS A
					JOIN lg_informerecomendacion AS B ON A.CodInformeRecomendacion = B.CodInformeRecomendacion
					JOIN lg_evaluacion AS C ON C.CodEvaluacion = B.CodEvaluacion
					WHERE C.CodEvaluacion =".$codEvaluacion;

	$resultadoDesierto = $objConexion->consultar($sqlDesierto,'fila');//
	
	if(count($resultadoDesierto) >= 1)
	{
	
		echo '<script language="javascript">
				alert(\'Este procedimiento fue declarado desierto\');
				location.href="lg_evaluacion_busqueda.php";
			</script>';
	
	}
	/******************************************************/
	
	
	/*$sql = "select A.NroOrden, C.NomCompleto as NomProveedor, B.FechaPrometida, A.CodProveedor, B.CodItem,B.Descripcion, B.CantidadPedida,B.Secuencia,
				E.CodPersonaConforme1,E.CodPersonaConforme2,E.CodPersonaConforme3,E.CodPersonaConforme4,E.CodPersonaConforme5
				from lg_ordencompra as A 
				join lg_ordencompradetalle as B on A.NroOrden=B.NroOrden and A.Anio=B.Anio and A.CodOrganismo=B.CodOrganismo
				JOIN mastpersonas C ON (A.CodProveedor = C.CodPersona)
				JOIN mastproveedores D ON (D.CodProveedor = A.CodProveedor)
				join lg_controlperceptivo as E on E.NroOrden=A.NroOrden
				where E.CodControlPerceptivo=".$CodControlPerceptivo;

	$resp = $objConexion->consultar($sql,'matriz');

	$sql2 = "SELECT A.CodItem, A.Descripcion, A.CantidadPedida, A.Secuencia,  D.Recibido
				FROM lg_ordencompradetalle AS A
				JOIN lg_ordencompra AS B ON B.NroOrden = A.NroOrden
				JOIN lg_controlperceptivo AS C ON C.NroOrden = B.NroOrden
				JOIN lg_controlperceptivodetalle AS D ON D.CodControlPerceptivo = C.CodControlPerceptivo
				WHERE C.CodControlPerceptivo =".$CodControlPerceptivo;
	
	$resp2 = $objConexion->consultar($sql2,'matriz');
	
		$sql1 = "SELECT p.Nombres, p.Apellido1, p.Apellido2, p.Ndocumento, pu.DescripCargo, p.CodPersona
				FROM mastpersonas AS p
				JOIN mastempleado AS me ON p.CodPersona = me.CodPersona
				JOIN rh_puestos AS pu ON me.CodCargo = pu.CodCargo
				WHERE p.CodPersona = '".$resp[0]['CodPersonaConforme1']."'";		
	
	 	$resultado1 = $objConexion->consultar($sql1,'fila');
	 	
	 	$sql2 = "SELECT p.Nombres, p.Apellido1, p.Apellido2, p.Ndocumento, pu.DescripCargo, p.CodPersona
				FROM mastpersonas AS p
				JOIN mastempleado AS me ON p.CodPersona = me.CodPersona
				JOIN rh_puestos AS pu ON me.CodCargo = pu.CodCargo
				WHERE p.CodPersona = '".$resp[0]['CodPersonaConforme2']."'";		
	
	 	$resultado2 = $objConexion->consultar($sql2,'fila');
	 	
	 		$sql3 = "SELECT p.Nombres, p.Apellido1, p.Apellido2, p.Ndocumento, pu.DescripCargo, p.CodPersona
				FROM mastpersonas AS p
				JOIN mastempleado AS me ON p.CodPersona = me.CodPersona
				JOIN rh_puestos AS pu ON me.CodCargo = pu.CodCargo
				WHERE p.CodPersona = '".$resp[0]['CodPersonaConforme3']."'";		
	
	 	$resultado3 = $objConexion->consultar($sql3,'fila');
	 	
	 	$sql4 = "SELECT p.Nombres, p.Apellido1, p.Apellido2, p.Ndocumento, pu.DescripCargo, p.CodPersona
				FROM mastpersonas AS p
				JOIN mastempleado AS me ON p.CodPersona = me.CodPersona
				JOIN rh_puestos AS pu ON me.CodCargo = pu.CodCargo
				WHERE p.CodPersona = '".$resp[0]['CodPersonaConforme4']."'";		
	
	 	$resultado4 = $objConexion->consultar($sql4,'fila');
	 	
	 	$sql5 = "SELECT p.Nombres, p.Apellido1, p.Apellido2, p.Ndocumento, pu.DescripCargo, p.CodPersona
				FROM mastpersonas AS p
				JOIN mastempleado AS me ON p.CodPersona = me.CodPersona
				JOIN rh_puestos AS pu ON me.CodCargo = pu.CodCargo
				WHERE p.CodPersona = '".$resp[0]['CodPersonaConforme5']."'";		
	
	 	$resultado5 = $objConexion->consultar($sql5,'fila');

		$persona1 = $resultado1['Nombres']." ".$resultado1['Apellido1']." ".$resultado1['Apellido2'];
			
		$persona2 = $resultado2['Nombres']." ".$resultado2['Apellido1']." ".$resultado2['Apellido2'];
			
		$persona3 = $resultado3['Nombres']." ".$resultado3['Apellido1']." ".$resultado3['Apellido2'];
			
		$persona4 = $resultado4['Nombres']." ".$resultado4['Apellido1']." ".$resultado4['Apellido2'];
			
		$persona5 = $resultado5['Nombres']." ".$resultado5['Apellido1']." ".$resultado5['Apellido2'];*/
//-------------------------------------------------------------------------------------------------------------------------------------//

$sql4 = "SELECT A.CodRequerimiento, A.Secuencia, C.CodPersonaAsistente, C.CodPersonaAsistente2, C.CodPersonaDirector
					from lg_requedetalleacta as A
					join lg_actainicio as B on A.CodActaInicio=B.CodActaInicio
					join lg_evaluacion as C on C.CodActaInicio=B.CodActaInicio
					where C.CodEvaluacion=".$codEvaluacion;
				
		$resultado4 = $objConexion->consultar($sql4,'matriz');
		
		$condicionRequerimiento = array();
		$condSecuenReque = array();
		
		for($i= 0; $i < count($resultado4); $i++)
		{
			
			$condicionRequerimiento[$i] = $resultado4[$i]['CodRequerimiento'];
			$condSecuenReque[$i] = $resultado4[$i]['Secuencia'];
		}
		
		$cadenaCondicionReque = implode(',',$condicionRequerimiento);
		$cadenaCondicionSecue = implode(',',$condSecuenReque);
		//$CodActaInicio = $codActa;
		

						
	$sql = "SELECT 
				c.*,
				p.NomCompleto AS NomProveedor

			FROM
				lg_cotizacion c
				INNER JOIN lg_requerimientosdet rd ON (c.CodOrganismo = rd.CodOrganismo AND
													   c.CodRequerimiento = rd.CodRequerimiento AND
													   c.Secuencia = rd.Secuencia)
				INNER JOIN mastpersonas p ON (c.CodProveedor = p.CodPersona)
				INNER JOIN mastproveedores mp ON (c.CodProveedor = mp.CodProveedor)
				
				
			WHERE
				rd.CodRequerimiento in (".$cadenaCondicionReque.") AND
				c.Secuencia in (".$cadenaCondicionSecue.")
				GROUP BY p.NomCompleto";
				

	$resp = $objConexion->consultar($sql,'matriz');//nombre de los proveedores sin repetir		
	
	$sql2 = "SELECT  A.*
				FROM lg_informerecomendacion as A
				join lg_evaluacion as B on A.CodEvaluacion=B.CodEvaluacion
				where B.CodEvaluacion=".$codEvaluacion;
	
	$resp2 = $objConexion->consultar($sql2,'fila');//datos informe recomendacion si esta
	
	$sql6 = "SELECT C.*
				FROM lg_proveedorrecomendado as C
				join lg_informerecomendacion as A on A.CodInformeRecomendacion=C.CodInformeRecomendacion
				join lg_evaluacion as B on A.CodEvaluacion=B.CodEvaluacion
				where B.CodEvaluacion=".$codEvaluacion." order by C.SecuenciaRecomendacion";
	
	$resp6 = $objConexion->consultar($sql6,'matriz');//datos de los proveedores recomendados en el informe
	
	
	$sql3 = "SELECT p.Nombres, p.Apellido1, p.Apellido2, p.Ndocumento, pu.DescripCargo,me.CodEmpleado
			FROM mastpersonas AS p
			JOIN mastempleado AS me ON p.CodPersona = me.CodPersona
			JOIN rh_puestos AS pu ON me.CodCargo = pu.CodCargo
			WHERE me.CodEmpleado = '".$resultado4[0]['CodPersonaAsistente']."'";		

 	$resultado1 = $objConexion->consultar($sql3,'fila');//asistente
 	
 	$sql4 = "SELECT p.Nombres, p.Apellido1, p.Apellido2, p.Ndocumento, pu.DescripCargo,me.CodEmpleado
			FROM mastpersonas AS p
			JOIN mastempleado AS me ON p.CodPersona = me.CodPersona
			JOIN rh_puestos AS pu ON me.CodCargo = pu.CodCargo
			WHERE me.CodEmpleado = '".$resultado4[0]['CodPersonaAsistente2']."'";		

 	$resultado2 = $objConexion->consultar($sql4,'fila');//director
        
        
        $sql20 = "SELECT p.Nombres, p.Apellido1, p.Apellido2, p.Ndocumento, pu.DescripCargo,me.CodEmpleado
			FROM mastpersonas AS p
			JOIN mastempleado AS me ON p.CodPersona = me.CodPersona
			JOIN rh_puestos AS pu ON me.CodCargo = pu.CodCargo
			WHERE me.CodEmpleado = '".$resultado4[0]['CodPersonaDirector']."'";		

 	$resultado20 = $objConexion->consultar($sql20,'fila');//director
	
	if(count($resp2)>0)
	{
		$codRecomendacion = $resp2['CodInformeRecomendacion'];
	
	} else {
		
		$codRecomendacion = 0;
	
	}	
/*	
echo '<script type="text/javascript" charset="utf-8">
		var codInformeRecomendacion= \''.$codRecomendacion .'\';
		var codEvaluacion = \''.$codEvaluacion.'\';
		var nroOrden;
		var cantidadItem = '.count($resp2).';
		var proveedor = \'\';
		restablecerLista(\'recomendacionProveedor\',\''.$resp2['CodProveedorRecomendado'].'\');';

		for($i = 0; $i < count($resp); $i++)
		{
			echo 'proveedor += \'&proveedor['.$i.']='.$resp[$i]['NomProveedor'].'\';';
		}
		
		echo '</script>';*/
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
		<td class="titulo">Informe Recomendación </td>
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
	echo '<script language="javascript">var cantidadProveedor = '.count($resp).'; </script>';
?>
<form name="fromActa" id="fromActa" action="" method="">
<div style="width:895px; height:15px" class="divFormCaption">Datos Informe Recomendación </div>
<table class="tblForm" width="895px" border="0">
<tr><td width="368"></td></tr>
<tr>
	  <td height="3" colspan="2" class="tagForm"><div style="display:none;">*Funcionario 1:</div>	    <div style="display:none;">
			<input type="hidden" id="persona1" value="<?=$resultado1['CodPersona']?>" />
			<input type="text" disabled="disabled" class="disabled" id="NomPersona1" style="width:220px;" value="<?=$persona1?>" />
			<a href="../lib/listas/listado_empleados.php?filtrar=default&cod=persona1&nom=NomPersona1&iframe=true&width=950&height=525" rel="prettyPhoto[iframe2]" style=" <?=$display_ver?>">
   	  <img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />            </a></div></td>
	</tr>
    <tr>
      <td valign="top" class="tagForm">*ASUNTO:</td>
      <td class="gallery clearfix"><label>
        <textarea name="textfield" cols="80" rows="8" id="asunto"><? echo $resp2['Asunto'];?></textarea>
      </label></td>
    </tr>
    <tr>
      <td valign="top" class="tagForm">*OBJETO DE LA CONSULTA DE PRECIOS: </td>
      <td class="gallery clearfix"><textarea name="textarea3" cols="80" rows="8" id="objeto"><? echo $resp2['ObjetoConsulta'];?></textarea></td>
    </tr>
    <tr>
      <td valign="top" class="tagForm">*CONCLUSIONES:</td>
      <td class="gallery clearfix"><textarea name="textarea" cols="80" rows="8" id="conclusiones"><? echo $resp2['Conclusiones'];?></textarea></td>
    </tr>
    <tr>
      <td valign="top" class="tagForm">*RECOMENDACIONES:</td>
      <td class="gallery clearfix"><textarea name="textarea2" cols="80" rows="8" id="recomendaciones"><? echo $resp2['Recomendacion'];?></textarea></td>
    </tr>
    <tr>
      <td valign="top" class="tagForm">*ARTICULADO:</td>
      <td ><input name="text"  name="numeral"  id="numeral" value="<? echo $resp2['Numeral'];?>"/></td>
    </tr>
        <?php
		
		$a = array('*');
		
		
			  
		for($j = 0; $j < count($resp); $j++)
		{
			echo '<tr>
			  <td valign="top" class="tagForm">'.$a[$j].'Proveedor Recomendado (Opci&oacute;n '.($j+1).'): </td>
			  <td class="gallery clearfix"><label>';
    
		
			echo '<select name="select" id="recomendacionProveedor'.$j.'">
					<option value="-1">Seleccione...</option>
					<option value="0">Ninguno</option>';
					
			for($i = 0; $i < count($resp); $i++)
			{		

				  echo '<option value="'.$resp[$i]['CodProveedor'].'">'.$resp[$i]['NomProveedor'].'</option>';
			}
				echo '</select>';
		
			  echo '</label></td>
			</tr>';
		}
	?>
	<tr>
		<td class="tagForm">*Tipo Adjudicaci&oacute;n:</td>
		<td width="515" class="gallery clearfix">
		<select name="" id="tipoAdjudicacion">
		<option value="-1"></option>
		<option value="DE">Desierta</option>
		<option value="TT">Adjudicaci&oacute;n Total</option>
		<option value="PC">Adjudicaci&oacute;n Parcial</option>
		</select>
		</td>
	</tr>
    <tr>
		<td class="tagForm">*PRIMER FIRMANTE:</td>
		<td width="515" class="gallery clearfix">
			<input type="hidden" id="persona4" value="<?=$resultado1["CodEmpleado"]?>" />
			<input type="text" disabled="disabled" class="disabled" id="NomPersona4" style="width:220px;" value="<?php echo $resultado1['Nombres']." ".$resultado1['Apellido1']." ".$resultado1['Apellido2'];?>" />
			<!--<a href="../lib/listas/listado_empleados.php?filtrar=default&cod=persona4&nom=NomPersona4&iframe=true&width=950&height=525" rel="prettyPhoto[iframe5]" style=" <?=$display_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />            </a>-->	  </td>
	</tr>
    <tr>
		<td class="tagForm">*SEGUNDO FIRMANTE:</td>
		<td class="gallery clearfix">
			<input type="hidden" id="persona5" value="<?=$resultado2["CodEmpleado"]?>" />
			<input type="text" disabled="disabled" class="disabled" id="NomPersona5" style="width:220px;" value="<?php echo $resultado2['Nombres']." ".$resultado2['Apellido1']." ".$resultado2['Apellido2'];?>" />
			<!--<a href="../lib/listas/listado_empleados.php?filtrar=default&cod=persona5&nom=NomPersona5&iframe=true&width=950&height=525" rel="prettyPhoto[iframe6]" style=" <?=$display_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />            </a>	-->  </td>
	</tr>
        <tr>
		<td class="tagForm">TERCER FIRMANTE:</td>
		<td class="gallery clearfix">
			<input type="hidden" id="persona6" value="<?=$resultado20["CodEmpleado"]?>" />
			<input type="text" disabled="disabled" class="disabled" id="NomPersona5" style="width:220px;" value="<?php echo $resultado20['Nombres']." ".$resultado20['Apellido1']." ".$resultado20['Apellido2'];?>" />
			<!--<a href="../lib/listas/listado_empleados.php?filtrar=default&cod=persona5&nom=NomPersona5&iframe=true&width=950&height=525" rel="prettyPhoto[iframe6]" style=" <?=$display_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />            </a>	-->  </td>
	</tr>
    <tr>
      <td colspan="2" class="tagForm">      </td>
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
    <input type="button" value="Guardar Registro" onclick="guardarInformeRecomendacion();" />
    <input type="button" value="Cancelar" onClick="cargarPagina(this.form,'lg_evaluacion_busqueda.php?concepto=01-0006&limit=0&filtrar=default');"  />
<!--    -->
</center>
</form>

<?php 
	echo '<script type="text/javascript" charset="utf-8">
		var codInformeRecomendacion= \''.$codRecomendacion .'\';
		var codEvaluacion = \''.$codEvaluacion.'\';
		var nroOrden;
		var cantidadItem = '.count($resp2).';
		var proveedor = \'\';
		
		//restablecerLista(\'recomendacionProveedor\',\''.$resp2['CodProveedorRecomendado'].'\');
		';

		$provee = '';

		for($i = 0; $i < count($resp); $i++)
		{
			$provee .= '&proveedor['.$i.']='.$resp[$i]['NomProveedor'];
			
			echo 'restablecerLista(\'recomendacionProveedor'.$i.'\',\''.$resp6[$i]['CodProveedorRecomendado'].'\');';
		}
		
		echo 'restablecerLista(\'tipoAdjudicacion\',\''.$resp2['TipoAdjudicacion'].'\');';
		
		echo 'proveedor = \''.$provee.'\'';
		echo '</script>';

?>
</body>
</html>
