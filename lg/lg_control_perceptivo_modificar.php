
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
	
	$sql = "select A.NroOrden, C.NomCompleto as NomProveedor, B.FechaPrometida, A.CodProveedor, B.CodItem,B.Descripcion, B.CantidadPedida,B.Secuencia,
				E.CodPersonaConforme1,E.CodPersonaConforme2,E.CodPersonaConforme3,E.CodPersonaConforme4,E.CodPersonaConforme5, E.Estado
				from lg_ordencompra as A 
				join lg_ordencompradetalle as B on A.NroOrden=B.NroOrden and A.Anio=B.Anio and A.CodOrganismo=B.CodOrganismo
				JOIN mastpersonas C ON (A.CodProveedor = C.CodPersona)
				JOIN mastproveedores D ON (D.CodProveedor = A.CodProveedor)
				join lg_controlperceptivo as E on E.NroOrden=A.NroOrden
				where E.CodControlPerceptivo=".$CodControlPerceptivo;

	$resp = $objConexion->consultar($sql,'matriz');

	$sql2 = "SELECT DISTINCT A.CodItem, A.Descripcion, A.CantidadPedida, A.Secuencia, D.Recibido, D.ObservacionItem, D.CantidadRecibida
			FROM lg_ordencompradetalle AS A
			JOIN lg_ordencompra AS B ON B.NroOrden = A.NroOrden
			JOIN lg_controlperceptivo AS C ON C.NroOrden = B.NroOrden
			JOIN lg_controlperceptivodetalle AS D ON D.CodControlPerceptivo = C.CodControlPerceptivo
			AND D.Secuencia = A.Secuencia
			AND A.CodItem = D.CodItem
			WHERE C.CodControlPerceptivo =".$CodControlPerceptivo." ORDER BY A.Secuencia";

	/*$sql2 = "SELECT DISTINCT A.CodItem, A.Descripcion, A.CantidadPedida, A.Secuencia, D.Recibido, D.ObservacionItem, D.CantidadRecibida
			FROM lg_ordencompradetalle AS A
			JOIN lg_ordencompra AS B ON B.NroOrden = A.NroOrden
			JOIN lg_controlperceptivo AS C ON C.NroOrden = B.NroOrden
			JOIN lg_controlperceptivodetalle AS D ON D.CodControlPerceptivo = C.CodControlPerceptivo
			AND D.Secuencia = A.Secuencia
			AND A.CodItem = D.CodItem
			WHERE C.CodControlPerceptivo =".$CodControlPerceptivo;*/
			
	$resp2 = $objConexion->consultar($sql2,'matriz');
						
	
		$sql1 = "SELECT p.Nombres, p.Apellido1, p.Apellido2, p.Ndocumento, pu.DescripCargo, p.CodPersona
				FROM mastpersonas AS p
				JOIN mastempleado AS me ON p.CodPersona = me.CodPersona
				JOIN rh_puestos AS pu ON me.CodCargo = pu.CodCargo
				WHERE me.CodEmpleado = '".$resp[0]['CodPersonaConforme1']."'";		
	
	 	$resultado1 = $objConexion->consultar($sql1,'fila');
	 	
	 	$sql2 = "SELECT p.Nombres, p.Apellido1, p.Apellido2, p.Ndocumento, pu.DescripCargo, p.CodPersona
				FROM mastpersonas AS p
				JOIN mastempleado AS me ON p.CodPersona = me.CodPersona
				JOIN rh_puestos AS pu ON me.CodCargo = pu.CodCargo
				WHERE me.CodEmpleado = '".$resp[0]['CodPersonaConforme2']."'";		
	
	 	$resultado2 = $objConexion->consultar($sql2,'fila');
	 	
	 		$sql3 = "SELECT p.Nombres, p.Apellido1, p.Apellido2, p.Ndocumento, pu.DescripCargo, p.CodPersona
				FROM mastpersonas AS p
				JOIN mastempleado AS me ON p.CodPersona = me.CodPersona
				JOIN rh_puestos AS pu ON me.CodCargo = pu.CodCargo
				WHERE me.CodEmpleado = '".$resp[0]['CodPersonaConforme3']."'";		
	
	 	$resultado3 = $objConexion->consultar($sql3,'fila');
	 	
	 	$sql4 = "SELECT p.Nombres, p.Apellido1, p.Apellido2, p.Ndocumento, pu.DescripCargo, p.CodPersona
				FROM mastpersonas AS p
				JOIN mastempleado AS me ON p.CodPersona = me.CodPersona
				JOIN rh_puestos AS pu ON me.CodCargo = pu.CodCargo
				WHERE me.CodEmpleado = '".$resp[0]['CodPersonaConforme4']."'";		
	
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
			
		$persona5 = $resultado5['Nombres']." ".$resultado5['Apellido1']." ".$resultado5['Apellido2'];
	
echo '<script type="text/javascript" charset="utf-8">
		var codControlPerceptivo = \''.$CodControlPerceptivo.'\';
		var nroOrden;
		var cantidadItem = '.count($resp2).';
	</script>';
?>

<?php
	  
	if($resp[0]['Estado'] == 1)
	{
		
		$valorBandera = "";
		$deshabilitarGuardar='';
		
	} else {
		
		$valorBandera = "checked=\"checked\"";
		$deshabilitarGuardar= 'disabled="disabled"';
		echo '<script language="javascript">
				alert(\'Este control perceptivo fue cerrado, no se puede modificar\');
			 </script>';
					
	}

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
		<td class="titulo">Modificar Control Perceptivo</td>
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
<div style="width:915px; height:15px" class="divFormCaption">Funcionarios para el Control Perceptivo de la Orden <? echo $nroOrden; ?> </div>
<table class="tblForm" width="895px" border="0">
<tr><td width="368"></td></tr>
<tr>
		<td colspan="2" class="tagForm gallery clearfix"><div style="display:none;">*Asistente Administradtivo:
			<input type="hidden" id="asistenteActaInicioAux" value="<?=$field_requerimiento['asistenteActaInicio']?>" />
			<input type="text" disabled="disabled" class="disabled" id="NomAsistenteActaInicioAux" style="width:220px;" value="<?=$field_requerimiento['NomAsistenteActaInicio']?>" />
			<a href="../lib/listas/listado_empleados.php?filtrar=default&cod=asistenteActaInicioAux&nom=NomAsistenteActaInicioAux&iframe=true&width=950&height=525" rel="prettyPhoto[iframe0]" style=" <?=$display_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />            </a>	</div> </td>
	</tr>
	<tr>
		<td class="tagForm">*Area de Bienes:</td>
		<td width="515" class="gallery clearfix">
			<input type="hidden" id="persona1" value="<?=$resultado1['CodPersona']?>" />
			<input type="text" disabled="disabled" class="disabled" id="NomPersona1" style="width:220px;" value="<?=$persona1?>" />
			<a href="../lib/listas/listado_empleados.php?filtrar=default&cod=persona1&nom=NomPersona1&iframe=true&width=950&height=525" rel="prettyPhoto[iframe2]" style=" <?=$display_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
	  </td>
	</tr>
    <tr>
		<td class="tagForm">*Unidad Contratante:</td>
		<td class="gallery clearfix">
			<input type="hidden" id="persona2" value="<?=$resultado2['CodPersona']?>" />
			<input type="text" disabled="disabled" class="disabled" id="NomPersona2" style="width:220px;" value="<?=$persona2?>" />
			<a href="../lib/listas/listado_empleados.php?filtrar=default&cod=persona2&nom=NomPersona2&iframe=true&width=950&height=525" rel="prettyPhoto[iframe3]" style=" <?=$display_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
	  </td>
	</tr>
    <tr>
		<td class="tagForm">*Unidad Usuaria:</td>
		<td class="gallery clearfix">
			<input type="hidden" id="persona3" value="<?=$resultado3['CodPersona']?>" />
			<input type="text" disabled="disabled" class="disabled" id="NomPersona3" style="width:220px;" value="<?=$persona3?>" />
			<a href="../lib/listas/listado_empleados.php?filtrar=default&cod=persona3&nom=NomPersona3&iframe=true&width=950&height=525" rel="prettyPhoto[iframe4]" style=" <?=$display_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
	  </td>
	</tr>
    <tr>
		<td class="tagForm">*Control Previo:</td>
		<td class="gallery clearfix">
			<input type="hidden" id="persona4" value="<?=$resultado4['CodPersona']?>" />
			<input type="text" disabled="disabled" class="disabled" id="NomPersona4" style="width:220px;" value="<?=$persona4?>" />
			<a href="../lib/listas/listado_empleados.php?filtrar=default&cod=persona4&nom=NomPersona4&iframe=true&width=950&height=525" rel="prettyPhoto[iframe5]" style=" <?=$display_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
	  </td>
	</tr>
    <tr>
		<td class="tagForm"></td>
		<td class="gallery clearfix">
			<input type="hidden" id="persona5" value="<?=$resultado5['CodPersona']?>" />
			<input type="hidden" disabled="disabled" class="disabled" id="NomPersona5" style="width:220px;" value="<?=$persona5?>" />
			<!--<a href="../lib/listas/listado_empleados.php?filtrar=default&cod=persona5&nom=NomPersona5&iframe=true&width=950&height=525" rel="prettyPhoto[iframe6]" style=" <?=$display_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />-->
            </a>
	  </td>
	</tr>
    <tr>
      <td class="tagForm">Cerrar Control Perceptivo:</td>
      
      <td class="gallery clearfix"><input type="checkbox" id="banderaCerrar" <?=$valorBandera?> <?=$deshabilitarGuardar?> /></td>
    </tr>
    <tr>
      <td colspan="2" class="tagForm">
      	<table width="906" height="63" border="0" cellspacing="1">
          <tr style="background-color: #000066; height:15px; color: #EFEFEF; font-size:10px;">
            <td width="714"><div align="left">Cantidad Pedida</div></td>
            <td width="714"><div align="left">&Iacute;tem</div></td>
            <td width="143"><div align="center">Recibido</div></td>
            <td width="143"><div align="center">Cantidad Recibida</div></td>
            <td width="143"><div align="center">Observaci&oacute;n</div></td>
          </tr>
         
           
            	<?php 
					
					//$resp2
					for($i = 0; $i < count($resp2); $i++)
					{
						/*$sql2 = "SELECT distinct A.CodItem, A.Descripcion, A.CantidadPedida, A.Secuencia,  D.Recibido, D.ObservacionItem, D.CantidadRecibida, G.*
						FROM lg_ordencompradetalle AS A
						JOIN lg_ordencompra AS B ON B.NroOrden = A.NroOrden
						JOIN lg_controlperceptivo AS C ON C.NroOrden = B.NroOrden
						JOIN lg_controlperceptivodetalle AS G ON G.CodControlPerceptivo = E.CodControlPerceptivo and G.Secuencia=B.Secuencia and B.CodItem=G.CodItem
						WHERE C.CodControlPerceptivo = ".$CodControlPerceptivo. " and G.CodItem='".$resp[$i]['CodItem']."' and G.Secuencia=".$resp[$i]['Secuencia'];
			
						$resp2 = $objConexion->consultar($sql2,'fila');
	echo $sql2;*/
						 echo ' <tr class="trListaBody"><td>'.str_replace(".00","",$resp2[$i]['CantidadPedida']).'</td>
						 <td>'.$resp2[$i]['Descripcion'].'</td>';
							
						if ($resp2[$i]['Recibido'] == '1')
						{
							
	            			echo '<td><div align="center"><input type="checkbox" id="recibido'.$i.'" checked="checked" '.$deshabilitarGuardar.' /></div></td> ';
							
						} else {
						
	            			echo '<td><div align="center"><input type="checkbox" id="recibido'.$i.'" '.$deshabilitarGuardar.' /></div></td> ';
													
						}
						
						echo '<td><div align="center"><input type="input" size="6" value="'.$resp2[$i]['CantidadRecibida'].'" id="cantidadRecibido'.$i.'" '.$deshabilitarGuardar.' /></div></td> ';
						echo '<td><div align="center"><input type="input" size="25" value="'.$resp2[$i]['ObservacionItem'].'" id="observacionRecibido'.$i.'" '.$deshabilitarGuardar.' /></div></td> </tr>';
					}
            	?>
         
        </table>
      </td>
    </tr>
   
    
<tr>
 <td colspan="4"><div class="divFormCaption" align="center"><b></b></div></td>
</tr>
<tr>
  <td colspan="4"><!--<textarea class="ckeditor" cols="90" id="editor1" name="editor1" rows="15"></textarea>-->
  </td>
</tr>
</table>
<center>

<div class="divMsj" style="width:1100px">Campos Obligatorios *</div>
    <input type="button" value="Guardar Registro" onclick="guardarGenerarControlPerceptivo('modificar');" <?=$deshabilitarGuardar?> />
    <input type="button" value="Cancelar" onClick="cargarPagina(this.form,'lg_controlperceptivo_busqueda.php?concepto=01-0006&limit=0&filtrar=default');"  />
<!--    -->
</center>
</form>
</body>
</html>
