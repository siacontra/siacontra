<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
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
		<td class="titulo">Documentos de Salida | Devoluci&oacute;n</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<div id="tab1" style="display:block;">
<form name="frmentrada" id="frmentrada" action="cpe_salidadevolucion.php?limit=0&accion=guardarDevolucion" method="post" >
<div style="width:895px; height:15px" class="divFormCaption">Datos del Documento</div>
<table class="tblForm" width="895px" border="0">
<tr><td height="5"></td></tr>
<tr>
  <td width="125" class="tagForm">Tipo Documento:</td>
  <td width="321">
     <? 
	  echo"<input type='hidden' id='regresar' name='regresar' value='".$regresar."'/>";
	  $sql="SELECT * FROM cp_tipocorrespondencia WHERE FlagProcedenciaExterna='1' and FlagUsoExterno='1'";
	  $qry=mysql_query($sql) or die ($sql.mysql_error());
	  $row=mysql_num_rows($qry);?>
      <input name="t_documento" id="t_documento" class="selectMed" value="<?=?>" readonly="readonly"/>   </td>
   <td width="104" class="tagForm">Fecha Env&iacute;o:</td>
 <? $fecha=date("d-m-Y");?>
 <td width="325"><input type="text" id="fecha_documento" name="fecha_documento" size="10" value="<?=$fecha;?>" style="text-align:right" readonly="readonly"/>*(dd-mm-aaaa)</td>
</tr>
<tr style="border-style:double">
 <td class="tagForm">Nro. Documento:</td>
 <td><input type="text" id="n_documento" name="n_documento" size="20" value="<?=?>" readonly/>*</td>
 <!--<td class="tagForm">Fecha Recibido:</td>
 <td><input type="text" id="fecha_recibido" name="fecha_recibido" size="10" maxlength="10" value="<?=$fecha;?>"/>*(dd-mm-aaaa)</td>
</tr>-->
<tr><td height="5"></td></tr>
<tr>
 <td class="tagForm">Responsable:</td>
 <td colspan="2"><input name="codempleado" type="hidden" id="codempleado" value="" />
	 <input name="nomempleado" id="nomempleado" type="text" size="68" readonly/>
	 <input name="bt_examinar" id="bt_examinar" type="button" value="..."/>* </td>
</tr>
<tr>
 <td class="tagForm">Cargo:</td>
 <td><input type="text" id="cargoremit" name="cargoremit" value="" size="68" readonly/></td>
</tr>
<tr><td height="5"></td></tr>
<tr>
 <td colspan="4"><div class="cellText" align="center"><b>Devoluci&oacute;n</b></div></td>
 <!--<td colspan="4"><div class="divBorder" style="border-color:#999999;"></div></td>-->
</tr>
<tr>
  <td class="tagForm">Motivo Devoluci&oacute;n:</td>
</tr>
<tr>
  <td colspan="1"></td>
  <td colspan="3"><textarea name="descrip" id="descrip" rows="2" cols="80"></textarea></td>
</tr>
<tr><td height="5"></td></tr>

<tr><td height="5"></td></tr>
<!--<tr>
 <td class="tagForm"></td>
 <td><input type="checkbox" id="chkorganismo" name="chkorganismo"/>Organismo 
     <input type="checkbox" id="chkdepedencia" name="chkdepedencia"/>Dependencia
 </td>
</tr>
<tr>
 <td class="tagForm">Remitente:</td>
 <td><input name="codempleado" type="hidden" id="codempleado" value="" />
	 <input name="nomempleado" id="nomempleado" type="text" size="52" readonly/>
	 <input name="bt_examinar" id="bt_examinar" type="button" value="..." onclick="cargarVentana(this.form, 'lista_empleados.php?limit=0&campo=1', 'height=500, width=800, left=200, top=200, resizable=yes');" />*
 </td>
 <td class="tagForm">Cargo:</td>
 <td><input type="text" id="cargoremitente" name="cargoremitente" value="" size="40" readonly/></td>
</tr>-->
<!--<tr>
 <td colspan="4">
  <table align="center" width="400">
  <tr>
     <td width="92" class="tagForm">Ultima Modif.:</td>
     <td width="296" colspan="2"><input type="text" id="ultimousuario" size="20" readonly="readonly"/><input type="text" id="ultimafecha" size="20" readonly="readonly"/></td>
  </tr>
  </table>
 </td>
</tr>-->
</table> 
<center>
    <input name="guardar" type="submit" id="guardar" value="Guardar Registro"/>
    <input name="cancelar" type="button" id="cancelar" value="Cancelar" onClick="cargarPagina(this.form,'cpe_salidadevolucion.php?limit=0');" />
</center> 
</form><br/>
</div>
<div class="divMsj" style="width:795px;">Campos Obligatorios *</div>
</body>
</html>