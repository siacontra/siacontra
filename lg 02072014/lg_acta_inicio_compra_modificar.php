
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
	
	/************VERIFICAO EL ESTADO DE LA ADJUDICACION*********************/
	$sqlEstado = "SELECT A.Estado
				FROM lg_informeadjudicacion as A
				join lg_informerecomendacion as B on A.CodInformeRecomendacion=B.CodInformeRecomendacion
				join lg_evaluacion as C on C.CodEvaluacion=B.CodEvaluacion
				join lg_actainicio as D on D.CodActaInicio=C.CodActaInicio
				where A.Estado='AD' and C.CodActaInicio=".$variableBusqueda;

	$resultadoEstadoAdju = $objConexion->consultar($sqlEstado,'fila');//
	
	if($resultadoEstadoAdju['Estado'] == 'AD')
	{
	
		echo '<script language="javascript">
				alert(\'La orden de compra para esta acta de inicio ya fue generada\nNo se puede modificar el acta de inicio\');
				location.href="lg_vergeneraractainicio.php";
			</script>';
	
	}
	/******************************************************/
	
	$sql3 = "select CodPersonaAsistente, CodPersonaDirector
					from lg_actainicio where CodActaInicio=".$variableBusqueda;
				
	$resultado3 = $objConexion->consultar($sql3,'fila');	

	$codAsistenteActaInicio = $resultado3['CodPersonaAsistente'];
	$codDirectorActaInicio = $resultado3['CodPersonaDirector'];

	$sql1 ="select p.Nombres, p.Apellido1, p.Apellido2, p.Ndocumento,

			pu. DescripCargo
			
			from mastpersonas as p
			
			join mastempleado as me on p.CodPersona=me.CodPersona
			
			join rh_puestos as pu on me.CodCargo=pu.CodCargo
			
			where p.CodPersona='".$codAsistenteActaInicio."'";    

    $resultado1 = $objConexion->consultar($sql1,'fila');			

	$sql2 ="select p.Nombres, p.Apellido1, p.Apellido2, p.Ndocumento,

			pu. DescripCargo
			
			from mastpersonas as p
			
			join mastempleado as me on p.CodPersona=me.CodPersona
			
			join rh_puestos as pu on me.CodCargo=pu.CodCargo
			
			where p.CodPersona='".$codDirectorActaInicio."'";    
			
	$resultado2 = $objConexion->consultar($sql2,'fila');	


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
<body onload="consultarEstadoReq('');">
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Modificar Acta de Inicio de Adquisición </td>
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
<div style="width:895px; height:15px" class="divFormCaption">Datos del Inicio de la Adquisición</div>
<table class="tblForm" width="895px" border="0">
<tr><td height="5"></td></tr>
<tr> <!-- ///////////  PRIMER ////////// -->
  <td><input type="hidden" id="codActaInicio" value="<? echo $variableBusqueda; ?>" /> </td>
</tr>
<tr>
		<td colspan="2" class="tagForm gallery clearfix"><div style="display:none;">*Asistente Administradtivo:
			<input type="hidden" id="asistenteActaInicioAux" value="<?=$field_requerimiento['asistenteActaInicio']?>" />
			<input type="text" disabled="disabled" class="disabled" id="NomAsistenteActaInicioAux" style="width:220px;" value="<?=$field_requerimiento['NomAsistenteActaInicio']?>" />
			<a href="../lib/listas/listado_empleados.php?filtrar=default&cod=asistenteActaInicioAux&nom=NomAsistenteActaInicioAux&iframe=true&width=950&height=525" rel="prettyPhoto[iframe0]" style=" <?=$display_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />            </a>	</div> </td>
	</tr>
    <tr>
		<td class="tagForm">*Asistente Administradtivo:</td>
		<td class="gallery clearfix">
			<!--<input type="hidden" id="asistenteActaInicio" value="<? echo $codAsistenteActaInicio; ?>" />
			<input type="text" disabled="disabled" class="disabled" id="NomAsistenteActaInicio" style="width:220px;" value="<? echo $resultado1['Nombres']." ".$resultado1['Apellido1']." ".$resultado1['Apellido2'];?>" />-->
			
			<input type="hidden" id="asistenteActaInicio" value="<?=$_SESSION["CODPERSONA_ACTUAL"]?>" />
			<input type="text" disabled="disabled" class="disabled" id="NomAsistenteActaInicio" style="width:220px;" value="<?=$_SESSION["NOMBRE_USUARIO_ACTUAL"]?>" />
			<!--<a href="../lib/listas/listado_empleados.php?filtrar=default&cod=asistenteActaInicio&nom=NomAsistenteActaInicio&iframe=true&width=950&height=525" rel="prettyPhoto[iframe5]" style=" <?=$display_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />            </a>-->	  </td>
	</tr>
   <tr>
		<td class="tagForm">*Director Administraci&oacute;n:</td>
		<td class="gallery clearfix">
			<input type="hidden" id="directorActaInicio" value="<? echo $codDirectorActaInicio; ?>" />
			<input type="text" disabled="disabled" class="disabled" id="NomDirectorActaInicio" style="width:220px;" value="<? echo $resultado2['Nombres']." ".$resultado2['Apellido1']." ".$resultado2['Apellido2']; ?>" />
			<a href="../lib/listas/listado_empleados.php?filtrar=default&cod=directorActaInicio&nom=NomDirectorActaInicio&iframe=true&width=950&height=525" rel="prettyPhoto[iframe6]" style=" <?=$display_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />            </a>	  </td>
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
    <input type="button" value="Guardar" onclick="guardarGenerarActaInicio('<? echo $registroCodSecGenerarActa ?>','modificar');" />
    <input type="button" value="Cancelar" onClick="cargarPagina(this.form,'lg_vergeneraractainicio.php');"  />
<!--    -->
</center>
</form>
</body>
</html>
