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
		$$nombreCampo = $valor;
	}
	

	
	//**********INCLUSION DE ARCHIVOS PARA CONSULTA*******//
	include_once ("../clases/MySQL.php");
		
	include_once("../comunes/objConexion.php");
	//**************************************************//
	
	
//-------------------------------------------------------------------------------------------------------------------------------------//

	$sql1 = "SELECT A.CodActaInicio, A.CodPersonaAsistente, A.CodPersonaDirector, A.AnioActa, A.FechaCreacion as FechaCreacionActa, A.NroVisualActaInicio, A.Estado as EstadoActa, A.UltimoUsuario, A.UltimaFechaModif,
			B.CodEvaluacion, B.CodActaInicio, B.ObjetoEvaluacion, B.CriterioCualitativo, B.CriterioCuantitativo, B.Conclusion, B.Recomendacion, B.CodPersonaAsistente, B.CodPersonaDirector, B.AnioEvaluacion, B.NroVisualEvaluacion, B.FechaCreacion as FechaCreacionEvaluacion, B.Estado, B.UltimoUsuario, B.UltimaFechaModif,
			C.CodInformeRecomendacion, C.Conclusiones, C.Recomendacion, C.Asistente as AsistenteRecomendacion, C.Director, C.UltimoUsuario, C.UltimaFechaModif, C.ObjetoConsulta, C.CodEvaluacion, C.Asunto, C.AnioRecomendacion, C.NroVisualRecomendacion, C.FechaCreacion as FechaCreacionREcomendacion, C.Estado, C.RevisadoPor, C.FechaRevisado, C.AprobadoPor, C.FechaAprobado
			FROM lg_actainicio AS A
			JOIN lg_evaluacion AS B ON A.CodActaInicio = B.CodActaInicio
			JOIN lg_informerecomendacion AS C ON C.CodEvaluacion = B.CodEvaluacion
			WHERE C.CodInformeRecomendacion =".$codRecomendacion;
				
	$resultado1 = $objConexion->consultar($sql1,'fila');//datos acta de inicio, evaluacion y recomendacion
	
	$sqlEva = "SELECT A.CodEvaluacion
				FROM lg_informerecomendacion as A
				where A.CodInformeRecomendacion=".$codRecomendacion;

	$resultadoEva = $objConexion->consultar($sqlEva,'fila');//codigo de la evaluacion

			
	$sql4 ="select distinct A.CodRequerimiento, A.Secuencia
			from lg_requedetalleacta as A
			 join lg_actainicio as B on A.CodActaInicio=B.CodActaInicio
			join lg_evaluacion as C on C.CodActaInicio=A.CodActaInicio
			join lg_informerecomendacion as E on E.CodEvaluacion=C.CodEvaluacion
			
			 where E.CodInformeRecomendacion=".$codRecomendacion."
			
			
			and A.CodRequerimiento not in 
			(select F.CodRequerimiento 
			from  lg_adjudicaciondetalle as F where F.CodRequerimiento=A.CodRequerimiento and F.Secuencia=A.Secuencia)";

		$resultado4 = $objConexion->consultar($sql4,'matriz');//requerimientos y secuencias asociados al acat y la evaluacion
		
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
		
		
		$sql2 = "SELECT 
				c.*,rd.CodRequerimiento, c.Secuencia,
				p.NomCompleto AS NomProveedor, d.Descripcion
				FROM
					lg_cotizacion c
					INNER JOIN lg_requerimientosdet rd ON (c.CodOrganismo = rd.CodOrganismo AND
														   c.CodRequerimiento = rd.CodRequerimiento AND
														   c.Secuencia = rd.Secuencia)
					INNER JOIN mastpersonas p ON (c.CodProveedor = p.CodPersona)
					INNER JOIN mastproveedores mp ON (c.CodProveedor = mp.CodProveedor)
					INNER JOIN lg_itemmast AS d ON ( d.CodItem = rd.CodItem )
				
				WHERE
					rd.CodRequerimiento in (".$cadenaCondicionReque.") AND
					c.Secuencia in (".$cadenaCondicionSecue.") group by d.Descripcion";
//echo $sql2;

	$respReque = $objConexion->consultar($sql2,'matriz');//nombre de los item sin repetir
	
	if (count($respReque) <= 0)
	{	
		$sql2 ="SELECT 
				c.*,rd.CodRequerimiento, c.Secuencia,
				p.NomCompleto AS NomProveedor, d.Descripcion
				FROM
					lg_cotizacion c
					INNER JOIN lg_requerimientosdet rd ON (c.CodOrganismo = rd.CodOrganismo AND
														   c.CodRequerimiento = rd.CodRequerimiento AND
														   c.Secuencia = rd.Secuencia)
					INNER JOIN mastpersonas p ON (c.CodProveedor = p.CodPersona)
					INNER JOIN mastproveedores mp ON (c.CodProveedor = mp.CodProveedor)
					INNER JOIN lg_commoditysub AS d 
				
				WHERE
					rd.CodRequerimiento in (".$cadenaCondicionReque.") AND
					c.Secuencia in (".$cadenaCondicionSecue.") and (rd.CommoditySub = d.Codigo) group by d.Descripcion";	
					
		
				
		$respReque = $objConexion->consultar($sql2,'matriz');//nombre de los item sin repetir
				
	}
	
		$sql6 = "SELECT C.*
				FROM lg_proveedorrecomendado as C
				join lg_informerecomendacion as A on A.CodInformeRecomendacion=C.CodInformeRecomendacion
				join lg_evaluacion as B on A.CodEvaluacion=B.CodEvaluacion
				where B.CodEvaluacion=".$resultadoEva['CodEvaluacion']." and C.CodProveedorRecomendado not in (select CodProveedor from lg_informeadjudicacion where CodInformeRecomendacion=A.CodInformeRecomendacion)
				 order by C.SecuenciaRecomendacion";
		
		$resp6 = $objConexion->consultar($sql6,'matriz');//datos de los proveedores recomendados en el informe
	
		for($i= 0; $i < count($resp6); $i++)
		{
			
			$condicionProveeRecomen[$i] = $resp6[$i]['CodProveedorRecomendado'];

		}
		
		$cadenaCondicionProveeRecomen = implode(',',$condicionProveeRecomen);
//		echo $cadenaCondicionProveeRecomen;
	$sql = "SELECT
				p.NomCompleto AS NomProveedor, mp.CodProveedor

			FROM
				
			mastpersonas p
			INNER JOIN mastproveedores mp ON (p.CodPersona = mp.CodProveedor)
							
							
						WHERE
			mp.Codproveedor in (".$cadenaCondicionProveeRecomen.")
				GROUP BY p.NomCompleto";
				
//rd.CodRequerimiento in (".$cadenaCondicionReque.") AND
//				c.Secuencia in (".$cadenaCondicionSecue.")

	$resp = $objConexion->consultar($sql,'matriz');//nombre y codigo de los proveedores recomendados
	
	$sql2 = "SELECT A.*
				FROM lg_informerecomendacion as A
				join lg_evaluacion as B on A.CodEvaluacion=B.CodEvaluacion
				where B.CodEvaluacion=".$resultadoEva['CodEvaluacion'];
	
	$resp2 = $objConexion->consultar($sql2,'fila');//datos informe recomendacion si esta
	
	
	$sql2 = "SELECT p.Nombres, p.Apellido1, p.Apellido2, p.Ndocumento, pu.DescripCargo
			FROM mastpersonas AS p
			JOIN mastempleado AS me ON p.CodPersona = me.CodPersona
			JOIN rh_puestos AS pu ON me.CodCargo = pu.CodCargo
			WHERE p.CodPersona = '".$resultado1['AsistenteRecomendacion']."'";

	$resultado2 = $objConexion->consultar($sql2,'fila');//asistente
	
	$sql7 = "SELECT p.Nombres, p.Apellido1, p.Apellido2, p.Ndocumento, pu.DescripCargo
			FROM mastpersonas AS p
			JOIN mastempleado AS me ON p.CodPersona = me.CodPersona
			JOIN rh_puestos AS pu ON me.CodCargo = pu.CodCargo
			WHERE p.CodPersona = '".$resultado1['RevisadoPor']."'";

	$resultado7 = $objConexion->consultar($sql7,'fila');//persona que reviso recomendacion
	
	$sql8 = "SELECT p.Nombres, p.Apellido1, p.Apellido2, p.Ndocumento, pu.DescripCargo
			FROM mastpersonas AS p
			JOIN mastempleado AS me ON p.CodPersona = me.CodPersona
			JOIN rh_puestos AS pu ON me.CodCargo = pu.CodCargo
			WHERE p.CodPersona = '".$resultado1['AprobadoPor']."'";

	$resultado8 = $objConexion->consultar($sql8,'fila');//persona que aprobo la recomendacion
	
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../cp/css1.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="../css/custom-theme/jquery-ui-1.8.16.custom.css" charset="utf-8" />

<link href="css1.css" rel="stylesheet" type="text/css" charset="utf-8" />
<?php 

	echo '<script langiage="javascript">
			var cantidadItem = '.count($respReque).';
	</script>';


	if(count($resp6) <= 0)
	{
		echo '<script langiage="javascript">
			alert(\'Ya no quedan proveedores a quién realizar adjudicación para esta recomendación\');
			location.href= \'lg_recomendacion_busqueda.php?concepto=01-0006&limit=0&filtrar=default\';
		</script>';
	}
?>
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
		<td class="titulo">Informe Adjudicación </td>
		<td align="right"><a class="cerrar" href="../lg/framemain.php"  onclick="cargarPagina(document.getElementById('frmentrada'),'<?=$regresar?>.php?&limit=0')";>[Cerrar]</a></td>
	</tr>
</table>
<hr width="100%" color="#333333" />
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
<div style="width:900px; height:15px" class="divFormCaption">Datos Informe Adjudicación </div>
<table class="tblForm" width="855" border="0">
<!--<tr>
	  <td height="3" colspan="2" class="tagForm"><div style="display:none;">*Funcionario 1:</div>	    <div style="display:none;">
			<input type="hidden" id="persona1" value="<?=$resultado1['CodPersona']?>" />
			<input type="text" disabled="disabled" class="disabled" id="NomPersona1" style="width:220px;" value="<?=$persona1?>" />
			<a href="../lib/listas/listado_empleados.php?filtrar=default&cod=persona1&nom=NomPersona1&iframe=true&width=950&height=525" rel="prettyPhoto[iframe2]" style=" <?=$display_ver?>">
   	  <img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />            </a></div></td>
	</tr>
    <tr>-->
      <!--<td valign="top" class="tagForm">*ASUNTO:</td>
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
    </tr>-->
    
        
		

	
			<tr>
			  <td width="177" valign="top" class="tagForm">Adjudicar a: </td>
			  <td width="666" class="gallery clearfix"><label>
    
		<?php
			echo '<select name="select" id="adjudicacionProveedor'.$j.'">
					<option value="-1">Seleccione...</option>';
			
			for($i = 0; $i < count($resp); $i++)
			{		

				  echo '<option value="'.$resp[$i]['CodProveedor'].'">'.$resp[$i]['NomProveedor'].'</option>';
			}
				echo '</select>';
		?>	
			  </label></td>
			</tr>

	
	
 <!--   <tr>
		<td class="tagForm">*ASISTENTE ADMINISTRACIÓN:</td>
		<td width="515" class="gallery clearfix">
			<input type="hidden" id="persona4" value="<?=$resp2['Asistente']?>" />
			<input type="text" disabled="disabled" class="disabled" id="NomPersona4" style="width:220px;" value="<? echo $resultado1['Nombres']." ".$resultado1['Apellido1']." ".$resultado1['Apellido2'];?>" />
			<a href="../lib/listas/listado_empleados.php?filtrar=default&cod=persona4&nom=NomPersona4&iframe=true&width=950&height=525" rel="prettyPhoto[iframe5]" style=" <?=$display_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />            </a>	  </td>
	</tr>
    <tr>
		<td class="tagForm">*DIRECTOR ADMINISTRACIÓN:</td>
		<td class="gallery clearfix">
			<input type="hidden" id="persona5" value="<?=$resp2['Director']?>" />
			<input type="text" disabled="disabled" class="disabled" id="NomPersona5" style="width:220px;" value="<? echo $resultado2['Nombres']." ".$resultado2['Apellido1']." ".$resultado2['Apellido2'];?>" />
			<a href="../lib/listas/listado_empleados.php?filtrar=default&cod=persona5&nom=NomPersona5&iframe=true&width=950&height=525" rel="prettyPhoto[iframe6]" style=" <?=$display_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />            </a>	  </td>
	</tr>-->
   
    <tr>
      <td colspan="2" class="tagForm"><div align="left" class="grillaTable">Requerimientos a adjudicar: </div></td>
    </tr>
    <tr>
      <td colspan="2" class="tagForm">
	  	<table width="906" height="25" border="0" cellspacing="1">
          <tr style="background-color: #000066; height:15px; color: #EFEFEF; font-size:10px;">
            <td width="194"><div align="left">Cantidad Pedida</div></td>
            <td width="601"><div align="center">&Iacute;tem</div></td>
            <td width="101"><div align="center">Adjudicar</div></td>
          </tr>
         
           
            	<?php 
					
					$codRequeSecueAdjudicacion = array();
					//$resp2
					for($i = 0; $i < count($respReque); $i++)
					{
						 echo ' <tr class="trListaBody" align="center"><td>'.str_replace(".00","",$respReque[$i]['Cantidad']).'</td>
						 <td>'.$respReque[$i]['Descripcion'].'</td>';
							
						/*if ($resp2[$i]['Recibido'] == '1')
						{
							
	            			echo '<td><div align="center"><input type="checkbox" id="adjudicar'.$i.'" checked="checked" '.$deshabilitarGuardar.' /></div></td> ';
							
						} else {*/
						
	            			echo '<td><div align="center"><input type="checkbox" id="adjudicar'.$i.'"/></div></td> ';
													
//						}
						
						echo '</tr>';
						$codRequeSecueAdjudicacion[$i]= $respReque[$i]['CodRequerimiento'].'-'.$respReque[$i]['Secuencia'];
						//.= '&adjudicacionRequeSecue['.$i.']='.$respRque[$i]['CodRequerimiento'].'-'.$respRque[$i]['Secuencia'];
					}
					
					
            	?>
        </table>	  </td>
    </tr>
    <tr>
      <td colspan="2" class="tagForm"></td>
    </tr>
    <tr>
      <td colspan="2" class="tagForm"><table width="752" border="0" align="center">
        <tr>
          <td width="291" height="28"  align="right"><div align="right">Recomendaci&oacute;n Realizada Por: </div></td>
          <td width="493" colspan="5" align="center" valign="middle"><div align="left">
              <label>
              <input name="textfield" type="text" disabled="disabled" value="<? echo $resultado2['Nombres'].' '.$resultado2['Apellido1'].' '.$resultado2['Apellido2']; ?>" size="50" />
              </label>
            </div>
              <div align="left"></div>
            <div align="left"></div>
            <div align="left"></div>
            <div align="left"></div></td>
        </tr>
        <tr>
          <td height="28"  align="right"><div align="right">Recomendaci&oacute;n Revisada Por: </div></td>
          <td colspan="5" align="center" valign="middle"><div align="left">
              <input name="textfield2" type="text" size="50" value="<? echo $resultado7['Nombres'].' '.$resultado7['Apellido1'].' '.$resultado7['Apellido2']; ?>" disabled="disabled">
            </div>
              <div align="left"></div>
            <div align="left"></div>
            <div align="left"></div>
            <div align="left"></div></td>
        </tr>
        <tr>
          <td height="28"  align="right"><div align="right">Recomendaci&oacute;n Aprobada Por:</div></td>
          <td colspan="5" align="center" valign="middle"><div align="left">
              <input name="textfield3" type="text" size="50" value="<? echo $resultado8['Nombres'].' '.$resultado8['Apellido1'].' '.$resultado8['Apellido2']; ?>" disabled="disabled" />
          </div></td>
        </tr>
      </table></td>
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
    <input type="button" value="Guardar Registro" onclick="guardarInformeAdjudicacion();" />
    <input type="button" value="Cancelar" onClick="cargarPagina(this.form,'lg_recomendacion_busqueda.php?concepto=01-0006&limit=0&filtrar=default');"  />
<!--    -->
</center>
</form>

<?php 

	echo '<script langiage="javascript">
			var cadenaCodRequeSecueAdjudicacion = "'.implode(',',$codRequeSecueAdjudicacion).'";
			var codRecomendacion = '.$codRecomendacion.';
	</script>';


/*	echo '<script type="text/javascript" charset="utf-8">
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
		
		echo 'proveedor = \''.$provee.'\'';
		echo '</script>';*/

?>
</body>
</html>