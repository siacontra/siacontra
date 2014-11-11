
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
<body onload="consultarEstadoReq('');buscarControlPerceptivo();">
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Reportes de Control Perceptivo</td>
		<td align="right"><a class="cerrar" style="cursor:pointer;" onclick="location.href='lg_control_perceptivo_ordencompra.php'";>[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />



<div style="width:895px; height:15px" class="divFormCaption">Controles Perceptivos Realizados</div>
<table class="tblForm" width="895px" border="0">
<tr><td height="5"></td></tr>
<tr> <!-- ///////////  PRIMER ////////// -->
  <td><form id="fromActa"></form></td>
</tr>
   <tr>
		<td colspan="2" class="tagForm"><table width="846" border="0" align="center" cellpadding="0" cellspacing="1">
                      <tr>
                        <td width="169" height="28"  align="right"><label>B&uacute;squeda por Código:</label></td>
                        <td width="260">
                          <input name="codigoActaBusqueda" id="codigoActaBusqueda" type="text" size="40" maxlength="60" class="campoTexto" onkeyup="" />
                        </td>
                        <td width="83" align="center" valign="middle">
                          <input type="button" name="Submit3" value="Buscar" onclick="buscarControlPerceptivo();" />
                      </td>
                        <td width="74" align="center" valign="middle">
                          <input type="button" name="Submit4" value="Limpiar" onclick="xGetElementById('codigoActaBusqueda').value='';" />
                       </td>
                        <td width="81" align="center" valign="middle"><input type="button" name="Submit5" value="Modificar" onclick="cargarDatosModificarControlPerceptivo();" /></td>
                        <td width="114" align="center" valign="middle"><input type="button" name="input" value="Generar Reporte" onclick="generarControlPerceptivo();" /></td>
                        <td width="57">&nbsp;</td>
          </tr>
                    </table>
	      <input type="hidden" id="fila" name="hiddenField" value="0" />
		  <input type="hidden" id="estiloFila" name="hiddenField2" value="0" />
		  <input type="hidden" id="codigoActa" name="hiddenField3" value="0"/>
					<input type="hidden" id="cantResultados" name="hiddenField3" value="0"/>
					<br />
					<div class="capaResultadoBusqueda">
					<table width="100%" border="0" class="tblLista" align="center" cellpadding="0" cellspacing="2">
						<thead>
						  <tr class="trListaHead" width="100%">
							<td width="22%"><div align="center">CÓDIGO</div></td>
							<td width="27%"><div align="center">N° ORDEN DE COMPRA</div></td>
							<td width="27%"><div align="center">PROVEEDOR</div></td>
							<td width="24%"><div align="center">FECHA REALIZACI&Oacute;N</div></td>
						  </tr>
					  	</thead>
                      <tbody id="resultadoBusquedaActa">
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