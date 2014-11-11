<?php

	session_start();

	//**********INCLUSION DE ARCHIVOS PARA CONSULTA*******//
	include_once("../clases/MySQL.php");
	include_once("../comunes/objConexion.php");
	//**************************************************//
	
	function rellenarConCero($cadena, $cantidadRelleno)
	{
		$cantidadCadena = strlen($cadena);
		
		for($i = 0; $i < ($cantidadRelleno-$cantidadCadena); $i++)
		{
				$cadena = '0'.$cadena;
			
		}			
		
		return $cadena;
	}
	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../cp/css1.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="../css/custom-theme/jquery-ui-1.8.16.custom.css" charset="utf-8" />

<link href="../lg/css1.css" rel="stylesheet" type="text/css" charset="utf-8" />


<script type="text/javascript" src="../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery.prettyPhoto.js" charset="utf-8"></script>


<script type="text/javascript" language="javascript" src="../cp/fscript.js"></script>
<script type="text/javascript" src="../cp/ckeditor/ckeditor.js"></script>
<script type="text/javascript" language="javascript" src="../cp/ckeditor/sample.js"></script>

<link href="../cp/ckeditor/sample.css" rel="stylesheet" type="text/css" />

<!-- INCLUSION DE LOS ARCHIVOS FUNCIONALIDADES CES -->

	<script  type='text/JavaScript' src='../js/funciones.js' charset="utf-8"></script>
	
    <script  type='text/JavaScript' src='../js/vEmergente.js' charset="utf-8"></script>
    
    <script type='text/JavaScript' src='../js/AjaxRequest.js' charset="utf-8"></script>

    <script type='text/JavaScript' src='../js/xCes.js' charset="utf-8"></script>
    
    <!-- <script type='text/JavaScript' src='../js/comun.js' charset="utf-8"></script>--> 
    
    <script type='text/JavaScript' src='../js/dom.js' charset="utf-8"></script>

	<script type='text/JavaScript' src='../rh/js/funcionalidadCes.js' charset="utf-8"></script>

 
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
<body >
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro Nueva Ayuda Útiles </td>
		<td align="right"><a class="cerrar"  onclick="location.href='beneficio_utiles.php'";>[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />



<div style="width:808px; height:15px;" class="divFormCaption">Datos Ayuda &Uacute;tiles </div>
<table width="808" height="133" border="0" class="tblForm">

   <tr>
		<td width="1057" height="119" colspan="2" valign="top" class="tagForm"><table width="798" border="0" align="center" cellpadding="0" cellspacing="1">
                      <!--<tr>
                        <td width="173" height="52"  align="right"><div align="right">N&deg; Acta Inicio:</div></td>
                        <td width="173" align="center" valign="middle"><div align="left">
                          <input name="textfield42" type="text" disabled="disabled" value="<? echo $numeroVisualActa; ?>" size="20" />
                        </div></td>
                        <td width="110" align="center" valign="middle"><div align="right">N&deg; Evaluaci&oacute;n: </div></td>
                        <td width="226"><div align="left">
                          <input name="textfield43" type="text" disabled="disabled" value="<? echo $numeroVisualEvaluacion; ?>" size="20" />
                        </div></td>
                        <td width="137"><div align="right">N&deg; Recomendaci&oacute;n: </div></td>
                        <td width="211"><div align="left">
                          <input name="textfield44" type="text" disabled="disabled" value="<? echo $numeroVisualRecomendacion; ?>" size="20" />
                        </div></td>
                      </tr>-->
                      <tr>
                        <td width="214" height="28"  align="right"><div align="right">N&deg; Ayuda:</div></td>
                        <td colspan="2" align="left" valign="middle"><label>
                          <input name="numBeneficio" type="text" id="numBeneficio" onkeyup="soloNumero(this.id)" size="5" maxlength="2" />
                        </label></td>
                        <td width="120">&nbsp;</td>
                        <td width="53">&nbsp;</td>
                        <td width="58">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="28"  align="right"><div align="right">Descripci&oacute;n:</div></td>
                        <td width="300" align="left" valign="middle"><label>
                          <input name="descripcionUtiles" type="text" id="descripcionUtiles" onkeyup="validarCadenaCampo(this.id,2,'.,-/:;')" size="50" maxlength="99" />
                        </label></td>
                        <td width="46" align="center" valign="middle">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="28"  align="right"><div align="right">Periodo:</div></td>
                        <td colspan="2" align="left" valign="middle"><label>
							  <input name="periodoUtiles" type="text" id="periodoUtiles" size="10" maxlength="7" onkeyup="validarCadenaCampo(this.id,4,'-')" />
                        </label></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="28"  align="right"><div align="right">Monto:</div></td>
                        <td colspan="2" align="left" valign="middle"><label>
                          <input name="montoUtiles" type="text" id="montoUtiles" onkeypress="return(formatoMoneda(this,'.',',',event));" value="0,00" size="10" maxlength="8" />
                        </label></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                     
                    </table>
	   
					<div class="capaResultadoBusqueda">
	
		  </div>
	 </td>
  </tr>

<tr>
  <td height="2" colspan="4"><!--<textarea class="ckeditor" cols="90" id="editor1" name="editor1" rows="15"></textarea>-->  </td>
</tr>
</table>
<center>
	<input type="button" onclick="guardarMaestroUtiles();" value="Guardar Registro" />
	<input type="button" onclick="location.href='beneficio_utiles.php'" value="Cancelar" />
</center>
</body>
</html>