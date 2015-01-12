
<?php
	session_start();
	if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");


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

    <script type='text/JavaScript' src='js/dom.js' charset="utf-8"></script>
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
<body onload="consultarEstadoReq('');buscarRecomendacionRevisada();">
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Aprobar Recomendaci&oacute;n </td>
		<td align="right"><a class="cerrar" href="../lg/framemain.php"  onclick="cargarPagina(document.getElementById('frmentrada'),'<?=$regresar?>.php?&limit=0')";>[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />



<div style="width:895px; height:15px" class="divFormCaption">Recomendaciones Revisadas Realizadas </div>
<table class="tblForm" width="895px" border="0">
<tr><td height="5"></td></tr>
<tr> <!-- ///////////  PRIMER ////////// -->
  <td><form id="fromActa"></form></td>
</tr>
   <tr>
		<td colspan="2" class="tagForm"><table width="894" border="0" align="center" cellpadding="0" cellspacing="1">
                      <tr>
                        <td width="144" height="28"  align="right"><label>B&uacute;squeda por Código:</label></td>
                        <td width="240">
                          <input name="" id="codigoRecomendacionBusqueda" type="text" size="40" maxlength="60" class="campoTexto" onkeyup="" />
                        </td>
                        <td width="70" align="center" valign="middle">
                          <input type="button" name="Submit3" value="Buscar" onclick="buscarRecomendacionRevisada();" />
                      </td>
                        <td width="58" align="center" valign="middle">
                          <input type="button" name="Submit4" value="Limpiar" onclick="xGetElementById('codigoRecomendacionBusqueda').value='';" />
                       </td>
                        <td width="68" align="center" valign="middle"><input type="button" name="Submit5" value="Aprobar" onclick="aprobarRecomendacion();" /></td>
                        <td width="155"><!--<input type="button" name="input" value="Generar Acta" onclick="generarActaInicio();" />-->
                          <input type="button" name="Submit" value="Informe Recomendaci&oacute;n" onclick="imprimirInformeRecomendacion();" />
                        </td>
                        <td width="151"><input type="button" name="button" id="button" value="Acta de Inicio" onclick="imprimirInformeActaInicio();" /></td>
          </tr>
                    </table>
	      <input type="hidden" id="fila" name="hiddenField" value="0" />
		  <input type="hidden" id="estiloFila" name="hiddenField2" value="0" />
		  <input type="hidden" id="codigoRecomendacion" name="hiddenField3" value="0"/>
					<input type="hidden" id="cantResultados" name="hiddenField3" value="0"/>
					<br />
					<div class="capaResultadoBusqueda">
					<table width="100%" border="0" class="tblLista" align="center" cellpadding="0" cellspacing="2">
						<thead>
						  <tr class="trListaHead" width="100%">
							<td width="20%"><div align="center">CÓDIGO</div></td>
							<td width="80%"><div align="center">PROVEEDORES</div></td>
						  </tr>
					  	</thead>
                      <tbody id="resultadoBusqueda">
					  </tbody>
                    </table>
		  </div>
					<br/>
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

<!--<div class="divMsj" style="width:1100px">Campos Obligatorios *</div>-->
    <!--<input type="button" value="Guardar Registro" onclick="guardarGenerarActaInicio('<? echo $registroCodSecGenerarActa ?>');" />
    <input type="button" value="Cancelar" onClick="cargarPagina(this.form,'lg_cotizaciones_invitar.php?concepto=01-0006&limit=0&filtrar=default');"  />-->
<!--    -->
</center>

</body>
</html>