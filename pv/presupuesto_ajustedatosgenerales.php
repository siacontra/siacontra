<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include ("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('01', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="fscriptpresupuesto.js"></script>
</head>
<body>
<style type="text/css">
<!--
UNKNOWN {FONT-SIZE: small}
#header {FONT-SIZE: 93%; BACKGROUND: url(imagenes/bg.gif) #dae0d2 repeat-x 50% bottom; FLOAT: left; WIDTH: 100%; LINE-HEIGHT: normal}
#header UL {PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 10px; LIST-STYLE-TYPE: none}
#header LI {PADDING-RIGHT: 0px; PADDING-LEFT: 9px; BACKGROUND: url(imagenes/left.gif) no-repeat left top; FLOAT: left; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 0px}
#header A {PADDING-RIGHT: 15px; DISPLAY: block; PADDING-LEFT: 6px; FONT-WEIGHT: bold; BACKGROUND: url(imagenes/right.gif) no-repeat right top; FLOAT: left; PADDING-BOTTOM: 4px; COLOR: #765; PADDING-TOP: 5px; TEXT-DECORATION: none}
#header A {FLOAT: none}
#header A:hover {COLOR: #333}
#header #current {BACKGROUND-IMAGE: url(imagenes/left_on.gif)}
#header #current A {BACKGROUND-IMAGE: url(imagenes/right_on.gif); PADDING-BOTTOM: 5px; COLOR: #333}
-->
</style>
<table width="100%" cellspacing="0" cellpadding="0">
<tr>
 <td class="titulo">Nuevo | Ajuste</td>
 <td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'),'<?=$regresar?>.php?limit=0')";>[Cerrar]</a></td>
</tr>
</table><hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="presupuesto_ajustedetalle.php?accion=GuardarAjuste" method="post" onsubmit="return verificarDatosAjuste(this,'Guardar')" >
<? echo"<input type='hidden' name='regresar' id='regresar' value='".$regresar."' />";
 //echo"Regresar= ".$regresar;?>
<table width="850" align="center">
<tr><td>
<div id="header">
<ul>
<!-- CSS Tabs PESTAÑAS OPCIONES DE PRESUPUESTO -->
<li><a onclick="document.getElementById('tab1').style.display='block';" href="#">Datos Generales</a></li>
<li><a onclick="javascript:alert('&iexcl;DEBE GUARDAR PRIMERO!');" href="#">Detalle de Ajuste</a></li>
</ul>
</div>
  </td>
</tr>
</table>
<div id="tab1" style="display:block;">
<div style="width:850px" class="divFormCaption">Informaci&oacute;n de Presupuesto</div>
<table width="850" class="tblForm">
<tr>
	<td width="48"></td>
	<td width="90" class="tagForm">Organismo:</td>
	<td width="300">
		<select name="organismo" id="organismo" class="selectBig">
		<?php 
		// segundo bloque php //* Conectamos a los datos *//
		include "conexion_.php";
		$sql="SELECT CodOrganismo,Organismo FROM mastorganismos WHERE 1";
		$rs=mysql_query($sql);
		while($reg=mysql_fetch_assoc($rs)){
		$codOrganismo=$reg['CodOrganismo'];// Codigo del orgnismo
		$organismo=$reg['Organismo'];// Descripcion del Organismo
		   echo "<option value=$codOrganismo>$organismo</option>";
		}
		?></select></td>
</tr>
<tr><td height="4"></td></tr>
</table>
<table width="850" class="tblForm" border="0">
<tr><td height="2"></td></tr>
<tr>
    <td width="190"></td>
	<td width="75" align="right">Nro. Gaceta:</td>
	<td width="70"><input name="gaceta" id="gaceta" type="text" size="8"/>*</td>
	<td width="64" align="right">F. Gaceta:</td>
	<td width="150"><input name="fgaceta" id="fgaceta" type="text" size="8"/>*<i>(dd-mm-aaaa)</i></td>
	<td colspan="2" width="200"></td>
</tr>
<tr>
 <td width="190"></td>
 <td width="75" align="right">Nro. Decreto:</td>
 <td width="70"><input name="decreto" id="decreto" type="text" size="8"/>*</td>
 <td width="64" align="right">F. Decreto:</td>
 <td width="150"><input name="fdecreto" id="fdecreto" type="text" size="8"/>*<i>(dd-mm-aaaa)</i></td>
</tr>
<tr><td height="2"></td></tr>
</table>
<table width="850" class="tblForm">
<tr><td height="2"></td></tr>
<tr>
 <td width="150"></td>
 <td class="tagForm">Nro. Presupuesto:</td>
 <td><input id="npresupuesto" name="npresupuesto" type="text" size="8" readonly/>
     <input name="bt_examinar" id="bt_examinar" type="button" value="..." onclick="cargarVentana(this.form, 'presupuesto_seleccionar.php?limit=0&campo=1', 'height=500, width=800, left=200, top=200, resizable=yes');"/>*
 </td>
 <td class="tagForm">Estado:</td>
 <td><input type="text" id="status" name="status" size="12" value="Preparaci&oacute;n"  readonly/></td>
 <td width="250"></td>
</tr>
<tr>
 <td width="33"></td>
 <td class="tagForm">F. Ajuste:</td><? $fcreacion=date("d-m-Y"); $fperiodo=date("Y-m");?>
 <td><input type="text" id="fAjuste" name="fAjuste" size="8" maxlength="8" value="<?=$fcreacion?>" readonly/></td>
 <td class="tagForm">Tipo Ajuste:</td>
  <td><select id="tAjuste" name="tAjuste">
      <option value=""></option>
	  <option value="IN">Incremento</option>
	  <option value="DI">Disminuci&oacute;n</option>
     </select>*</td>
</tr>
<tr><td height="2"></td></tr>
</table>
<div style="width:850px" class="divFormCaption">Duraci&oacute;n del Ajuste</div>
<table class="tblForm" width="850">
<tr><td height="2"></td></tr>
<tr>
  <td width="50"></td>
  <td width="70" align="right">Per&iacute;odo:</td>
  <td width="180"><input id="fperiodo" name="fperiodo" type="text" size="8" maxlength="8" value="<?=$fperiodo?>"/>*<i>(aaaa-mm)</i></td>
</tr>
<tr><td height="2"></td></tr>
</table>
<div style="width:850px" class="divFormCaption">Descripci&oacute;n de Motivo</div>
<table class="tblForm" width="850">
<tr>
  <td width="195"></td>
  <td width="50">Descripci&oacute;n:</td>
</tr>
<tr>
  <td colspan="1"></td>
  <td width="580"><textarea name="descripcion" id="descripcion" rows="5" cols="80"></textarea>*</td>
</tr>
<tr><td height="2"></td></tr>
</table>
<table width="850" class="tblForm">
<tr><td width="45"></td>
   <td width="245" class="tagForm">Preparado por:</td>
   <? 
    $sql3=mysql_query("SELECT * FROM usuarios WHERE Usuario='".$_SESSION['USUARIO_ACTUAL']."'");
	if(mysql_num_rows($sql3)!=0){
	 $field3=mysql_fetch_array($sql3);
	 $sql4=mysql_query("SELECT * FROM mastpersonas WHERE CodPersona='".$field3['CodPersona']."'");
	 if(mysql_num_rows($sql4)!=0){
	   $field4=mysql_fetch_array($sql4);
	 }
	}
  ?>
   <td width="520"><input name="prepor" id="prepor" type="text" size="60" value="<?=$field4['NomCompleto']?>" readonly/>
                   <input name="fpreparacion" id="fpreparacion" type="hidden" value="<?=$fcreacion;?>"/>
   </td>
</tr>
<tr><td></td>
   <td class="tagForm">Aprobado por:</td>
   <td><input name="codempleado" type="hidden" id="codempleado" value="" />
	       <input name="nomempleado" id="nomempleado" type="text" size="60" readonly/>
		   <input name="bt_examinar" id="bt_examinar" type="button" value="..." onclick="cargarVentana(this.form, 'lista_empleados.php?limit=0&campo=1', 'height=500, width=800, left=200, top=200, resizable=yes');" />*</td>
</tr>
<tr><td></td>
   <td class="tagForm">&Uacute;ltima Modif.:</td>
   <td colspan="1"><? $fCompleta=date("d-m-Y H:m:s");  ?>
	<input name="ult_usuario" type="text" id="ult_usuario" size="30" readonly />
	<input name="ult_fecha" type="text" id="ult_fecha" size="22" readonly /></td>
</tr>
<tr><td height="5"></td></tr>
</table>
</div>

<div id="tab2" style="display:none;">
<div style="width:850px" class="divFormCaption">Informaci&oacute;n de Presupuesto</div>


</div>


<center>
<input name="btguardar" type="submit" id="btguardar" value="Guardar Registro"/>
<input name="btcancelar" type="button" id="btcancelar" value="Cancelar" onclick="cargarPagina(this.form, '<?=$regresar?>.php?limit=0');"/>
</center>

</form>
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
