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
<script type="text/javascript" language="javascript">
 function Mensaje(){
  alert('NO PUEDE MODIFICAR ESTE CAMPO ¡BOTON INHABILITADO!');
 }
</script>

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
</head>
<body>
<?
include "gpresupuesto.php";
?>
<table width="100%" cellspacing="0" cellpadding="0">
<tr>
 <td class="titulo">Reformulaci&oacute;n | Nuevo Registro</td>
 <td align="right"><a class="cerrar"; href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'),'<?=$regresar?>.php?limit=0')">[Cerrar]</a></td>
</tr>
</table><hr width="100%" color="#333333" />
<form id="frmentrada" name="frmentrada" action="reformulacion_detalle.php?accion=GuardarReformulacion" method="post" onsubmit="return verificarDatos(this,'Guardar')" >
<? 
$SQL="SELECT 
            CodPresupuesto,Organismo
	    FROM 
		    pv_presupuesto 
	   WHERE 
	        EjercicioPpto = '".date("Y")."' AND
			Estado='AP'"; 
$QRY=mysql_query($SQL) or die ($SQL.mysql_error());
$FIELD=mysql_fetch_array($QRY);


echo"<input type='hidden' id='regresar' name='regresar' value='".$regresar."'/>"; ?>
<table width="850" align="center">
<tr><td>
<div id="header">
<ul>
<!-- CSS Tabs PESTAÑAS OPCIONES DE PRESUPUESTO -->
 <li><a onclick="document.getElementById('tab1').style.display='block';" href="#">Datos Generales</a></li>
 <li><a onclick="javascript:alert('&iexcl;DEBE GUARDAR PRIMERO!');" href="#">Detalle de Reformulaci&oacute;n</a></li>
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
		<select name="Org" id="Org" class="selectBig">
		<?php 
		$sql="select 
		  			CodOrganismo,
					Organismo 
			   from 
			   		mastorganismos";
		$qry = mysql_query($sql) or die ($sql.mysql_error());
		$row = mysql_num_rows($qry);
		
		if($row!=0)
		  for($i=0; $i<$row; $i++){
		     $fie = mysql_fetch_array($qry);
		     if($_SESSION['ORGANISMO_ACTUAL']==$fie['CodOrganismo'])
			   echo"<option value='".$fie['CodOrganismo']."' selected>".$fie['Organismo']."</option>";
		  }
		?></select></td>
</tr>
<tr><td height="4"></td></tr>
</table>
<table width="850" class="tblForm" border="0">
<tr><td height="2"></td></tr>
<tr>
 <td width="129"></td>
 <td width="138" align="right">Nro. Resoluci&oacute;n:</td>
 <td width="140"><input name="resolucion" id="resolucion" type="text" size="18" style="text-align:right"/>*</td>
 <td width="85" align="right">F. Resoluci&oacute;n:</td>
 <td width="174"><input name="fresolucion" id="fresolucion" type="text" size="8" style="text-align:right"/>*<i>(dd-mm-aaaa)</i></td>
</tr>
<tr>
    <td width="129"></td>
	<td width="138" align="right">Nro. Gaceta:</td>
	<td width="140"><input name="gaceta" id="gaceta" type="text" size="18" style="text-align:right"/>*</td>
	<td width="85" align="right">F. Gaceta:</td>
	<td width="174"><input name="fgaceta" id="fgaceta" type="text" size="8" style="text-align:right"/>*<i>(dd-mm-aaaa)</i></td>
	<td colspan="2" width="156"></td>
</tr>
<tr><td height="2"></td></tr>
</table>
<table width="850" class="tblForm">
<tr><td height="2"></td></tr>
<tr>
 <td width="95"></td>
 <td width="171" class="tagForm">Nro. Presupuesto:</td>
 <td width="81">
   <input type="text" id="num_presupuesto" name="num_presupuesto" size="8" style="text-align:right" value="<?=$FIELD['CodPresupuesto']?>" readonly/>
 </td>
 <td width="141" class="tagForm">Estado:</td>
 <td width="118"><input type="text" id="status" name="status" size="10" value="Preparado"  readonly/></td>
 <td width="216"></td>
</tr>
<tr>
 <td width="95"></td>
 <td class="tagForm">F. Reformulaci&oacute;n:</td><? $fcreacion=date("d-m-Y"); $fperiodo=date("Y-m");?>
 <td><input type="text" id="fReformulacion" name="fReformulacion" size="8" maxlength="8" value="<?=$fcreacion?>" readonly/></td>
 <td class="tagForm"></td>
 <td></td>
 <!--<td class="tagForm">Tipo Ajuste:</td>
  <td><select id="tAjuste" name="tAjuste">
      <option value=""></option>
	  <option value="IN">Incremento</option>
	  <option value="DI">Disminuci&oacute;n</option>
     </select>*</td>-->
</tr>
<tr><td height="2"></td></tr>
</table>
<div style="width:850px" class="divFormCaption">Duraci&oacute;n de Reformulaci&oacute;n</div>
<table class="tblForm" width="850">
<tr><td height="2"></td></tr>
<tr>
  <td width="50"></td>
  <td width="70" align="right">Per&iacute;odo:</td>
  <td width="180"><input id="fperiodo" name="fperiodo" type="text" size="8" maxlength="8" style="text-align:right" value="<?=$fperiodo?>" readonly/>*<i>(aaaa-mm)</i></td>
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
    $sql3="select 
	             u.CodPersona,
				 m.NomCompleto 
		     from  
			     usuarios u 
				 inner join mastpersonas m on (m.CodPersona = u.CodPersona)
			where			
		         u.Usuario='".$_SESSION['USUARIO_ACTUAL']."'";
	$qry3=mysql_query($sql3) or die ($sql3.mysql_error());
	 
	if(mysql_num_rows($qry3)!=0)$field3=mysql_fetch_array($qry3);
  ?>
   <td width="520"><input name="prepor" id="prepor" type="hidden" value="<?=$field3['CodPersona']?>"/>
   				   <input name="preparado_por" id="preparado_por" type="text" size="60" value="<?=$field3['NomCompleto']?>" readonly/>
                   <input name="fpreparacion" id="fpreparacion" type="hidden" value="<?=$fcreacion;?>"/>
   </td>
</tr>
<tr><td></td>
   <td class="tagForm">Aprobado por:</td>
   <td><input name="codempleado" type="hidden" id="codempleado" value="" />
	       <input name="nomempleado" id="nomempleado" type="text" size="60" readonly/>
		   <!--<input name="bt_examinar" id="bt_examinar" type="button" value="..." onclick="cargarVentana(this.form, 'lista_empleados.php?limit=0&campo=1', 'height=500, width=800, left=200, top=200, resizable=yes');" />*--></td>
</tr>
<tr><td></td>
   <td class="tagForm">&Uacute;ltima Modif.:</td>
   <td colspan="1"><? $fCompleta=date("d-m-Y H:m:s");  ?>
	<input name="ult_usuario" type="text" id="ult_usuario" size="30" readonly />
	<input name="ult_fecha" type="text" id="ult_fecha" size="22" readonly /></td>
</tr>
<tr><td height="5"></td></tr>
</table>
<center>
<input name="btguardar" type="submit" id="btguardar" value="Guardar Registro"/>
<input name="btcancelar" type="button" id="btcancelar" value="Cancelar" onclick="cargarPagina(this.form, '<?=$regresar?>.php?limit=0');"/>
</center>

</div>
<!-- //// ************** AJUSTE DETALLE ******** ///// -->
</form>
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
<!-- VALIDACIONES CAMPOS OBLIGATORIOS  -->
<script language="javascript">

function verificarDatos(formulario) {
////   VALIDACION NUMERO DE OFICIO
if (formulario.oficio.value.length <1) {
 alert("Introduzca el Número de Oficio.");
 formulario.oficio.focus();
return (false);
}
var checkOK ="0123456789" + "-";
var checkStr = formulario.oficio.value;
var allValid = true; 
for (i = 0; i < checkStr.length; i++) {
  ch = checkStr.charAt(i); 
  for (j = 0; j < checkOK.length; j++)
	  if (ch == checkOK.charAt(j))
	  break;
	  if (j == checkOK.length) { 
		 allValid = false; 
	  break; 
	  }
}
if (!allValid) { 
 alert("Escriba sólo números y carácteres permitidos en el campo \"Nro. Oficio\"."); 
 formulario.oficio.focus(); 
 return (false); 
} 

////   VALIDACION FECHA DE OFICIO
if (formulario.foficio.value.length <10) {
 alert("Introduzca la Fecha del Oficio.");
 formulario.foficio.focus();
return (false);
}
var checkOK ="0123456789" + "-";
var checkStr = formulario.foficio.value;
var allValid = true; 
for (i = 0; i < checkStr.length; i++) {
  ch = checkStr.charAt(i); 
  for (j = 0; j < checkOK.length; j++)
	  if (ch == checkOK.charAt(j))
	  break;
	  if (j == checkOK.length) { 
		 allValid = false; 
	  break; 
	  }
}
if (!allValid) { 
 alert("Escriba sólo números y carácteres permitidos en el campo \"F. Oficio\"."); 
 formulario.foficio.focus(); 
 return (false); 
} 

////   VALIDACION FECHA DE PERIODO
if (formulario.fperiodo.value.length <7) {
 alert("Introduzca el Período.");
 formulario.fperiodo.focus();
return (false);
}
var checkOK ="0123456789" + "-";
var checkStr = formulario.fperiodo.value;
var allValid = true; 
for (i = 0; i < checkStr.length; i++) {
  ch = checkStr.charAt(i); 
  for (j = 0; j < checkOK.length; j++)
	  if (ch == checkOK.charAt(j))
	  break;
	  if (j == checkOK.length) { 
		 allValid = false; 
	  break; 
	  }
}
if (!allValid) { 
 alert("Escriba sólo números y carácteres permitidos en el campo \"Período\"."); 
 formulario.fperiodo.focus(); 
 return (false); 
} 

//VALIDACION DEL CAMPO DESCRIPCION
if (formulario.descripcion.value.length <1) {
 alert("Introduzca la Descripción.");
 formulario.descripcion.focus();
return (false);
}
var checkOK ="0123456789" + "ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚ" + "abcdefghijklmnñopqrstuvwxyzáéíóú" + " /-*.;";
var checkStr = formulario.descripcion.value;
var allValid = true; 
for (i = 0; i < checkStr.length; i++) {
  ch = checkStr.charAt(i); 
  for (j = 0; j < checkOK.length; j++)
	  if (ch == checkOK.charAt(j))
	  break;
	  if (j == checkOK.length) { 
		 allValid = false; 
	  break; 
	  }
}
if (!allValid) { 
 alert("Introduzca la Descripción."); 
 formulario.descripcion.focus(); 
 return (false); 
} 

//VALIDACION APROBADO POR
if (formulario.nomempleado.value.length <2) {
 alert("Elija por quien sera aprobado haciendo click en el botón");
 formulario.nomempleado.focus();
return (false);
}
var checkOK = "ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚ" + "abcdefghijklmnñopqrstuvwxyzáéíóú" + " .,_/";
var checkStr = formulario.nomempleado.value;
var allValid = true; 
for (i = 0; i < checkStr.length; i++) {
  ch = checkStr.charAt(i); 
  for (j = 0; j < checkOK.length; j++)
	  if (ch == checkOK.charAt(j))
	  break;
	  if (j == checkOK.length) { 
		 allValid = false; 
	  break; 
	  }
}
if (!allValid) { 
 alert("Elija por quien sera aprobado haciendo click en el Botón"); 
 formulario.nomempleado.focus(); 
 return (false); 
} 
return (true); 
} 
</SCRIPT>