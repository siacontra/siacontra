<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="cp_script.js"></script>
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
		<td class="titulo">Documentos Internos | Nuevo Registro</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />
<form name="frmentrada" id="frmentrada" action="<?=$regresar?>.php?limit=0&fremitente=<?=$fremitente?>&fEstado=<?=$fEstado?>" method="post"  onsubmit="return VerificarDatos(this);" >
<div style="width:925px; height:15px" class="divFormCaption">Datos del Documento</div>
<table class="tblForm" width="925" border="0">
<tr><td height="5"></td></tr>
<tr>
  <td width="120" class="tagForm">Tipo Documento:</td>
  <td width="320">
     <? 
	  echo"<input type='hidden' id='regresar' name='regresar' value='".$regresar."'/>";
	  echo"<input type='hidden' id='fremitente' name='fremitente' value='".$fremitente."'/>";
	  echo"<input type='hidden' id='fEstado' name='fEstado' value='".$fEstado."'/>
	       <input type='hidden' id='verificador' name='verificador'/>";
	  $sql="SELECT * FROM cp_tipocorrespondencia WHERE FlagUsoInterno='1'";
	  $qry=mysql_query($sql) or die ($sql.mysql_error());
	  $row=mysql_num_rows($qry);?>
      <select name="t_documento" id="t_documento" class="selectMed">
        <option value=""></option>
        <?
      for($i; $i<$row; $i++){
	    $field=mysql_fetch_array($qry);?>
        <option value="<?=$field['Cod_TipoDocumento'];?>"><?=$field['Descripcion'];?></option>
        <? }?>
      </select>*</td>
   <td width="118" class="tagForm">Fecha Documento:</td>
 <? $fecha=date("d-m-Y");?>
 <td width="347"><input type="text" id="fecha_documento" name="fecha_documento" size="10" value="<?=$fecha;?>" style="text-align:right" readonly/>*(dd-mm-aaaa)</td>
</tr>
<tr style="border-style:double">
 <td class="tagForm">Nro. Documento:</td>
 <td><input type="text" id="n_documento" name="n_documento" size="20" readonly />*</td>
 <td class="tagForm">Plazo de Atenci&oacute;n:</td>
 <td><input type="text" id="plazo" name="plazo" size="5" maxlength="3" style="text-align:right"/> d&iacute;a(s)</td>
</tr>
<tr>
  <td class="tagForm">Asunto:</td>
  <td colspan="2"><input type="text" id="asunto" name="asunto" size="84"/>*</td><input type="hidden" name="anexos" id="anexos"/>
  <td>Anexos: Si<input type="radio" id="anexsi1" name="anexsi1"  onclick="asignar1(this.form);"/> No<input type="radio" id="anexsi2" name="anexsi2" value="" onclick="asignar2(this.form);"/></td>
</tr>
<tr>
  <td class="tagForm">Descripci&oacute;n:</td>
  <td colspan="2"><textarea name="descrip" id="descrip" rows="2" cols="80"></textarea></td>
  <td><textarea name="anexDescp" id="anexDescp" rows="2" cols="80" style="visibility:hidden"></textarea></td>
</tr>
<tr><td height="5"></td></tr>
<tr>
 <td colspan="4"><div class="cellText" align="center"><b>Remitente</b></div></td>
 <!--<td colspan="4"><div class="divBorder" style="border-color:#999999;"></div></td>-->
</tr>
<tr><td height="5"></td></tr>
 <tr>
    <td class="tagForm">Organismo:</td>
    <td colspan="3"><select id="organismo" name="organismo" class="selectBig" onchange="getOrganismoInterno(this.id, 'dep_interna');" >
      <option value=""></option>
      <? getOrganismos('', 0); ?>
    </select>*</td>
  </tr>
  <tr>
    <td class="tagForm" colspan="">Dependencia:</td>
    <td colspan="3"><select id="dep_interna" name="dep_interna" class="selectBig" disabled>
      <option value=""></option>
    </select>*</td>
  </tr>
  <tr>
    <td class="tagForm">Representante:</td>
    <td><input type="text" id="destinatario_int" name="destinatario_int" size="68" value="" readonly="readonly"/></td>
    <td class="tagForm">Cargo:</td>
    <td><input type="text" id="cargodestinatario_int" name="cargodestinatario_int" size="68" value="" readonly="readonly"/>
        <input type="hidden" id="codigo_interno" name="codigo_interno" value=""/>
        <input type="hidden" id="codigo_persona" name="codigo_persona" value=""/>
        <input type="hidden" id="codigo_cargo" name="codigo_cargo" value=""/></td>
  </tr>
<tr><td height="5"></td></tr>
<tr>
 <td colspan="4">
  <table align="center" width="400">
  <tr>
     <td width="92" class="tagForm">Ultima Modif.:</td>
     <td width="296" colspan="2"><input type="text" id="ultimousuario" size="20" readonly="readonly"/><input type="text" id="ultimafecha" size="20" readonly="readonly"/></td>
  </tr>
  </table>
 </td>
</tr>
</table> 
<center>
    <input name="guardar" type="submit" id="guardar" value="Guardar Registro"/>
    <input name="cancelar" type="button" id="cancelar" value="Cancelar" onClick="cargarPagina(this.form,'<?=$regresar?>.php?limit=0&fremitente=<?=$fremitente?>&fEstado=<?=$fEstado?>');" />
</center> 
</form><br/>
<!-- @@@@@@@@@@@@@@@@@@@@@@@@ LISTA A MOSTRAR @@@@@@@@@@@@@@@@@@@@@@@@ -->
<form name="frmdetalles" id="frmdetalles">
<input type="hidden" id="seldetalle"/>
<table align="center" cellpadding="0" cellspacing="0">
<tr>
 <td colspan="4"><div class="cellText" align="center"><b>Destinatario(s)</b></div></td>
</tr>
<tr>
  <td colspan="8">
  <table width="800" class="tblBotones">
   <tr>
 	<td align="right">
    <input type="button" class="btLista" id="btInsertarDependencia" value="Ins. Dep." onclick="Verificar()|cargarVentana(this.form,'lista_dependencias.php?limit=0&ventana=insertarDestinatarioDep&tabla=item', 'height=600, width=800, left=50, top=50, resizable=yes');" />
    <input type="button" class="btLista" id="btInsertarEmpleado" value="Ins. Emp." onclick="Verificar()|cargarVentana(this.form,'lista_empleados.php?limit=0&campo=1&ventana=insertarDestinatarioEmp&tabla=item', 'height=600, width=800, left=50, top=50, resizable=yes');" />
    <input name="btEliminar" type="button" class="btLista" id="btEliminar" value="Eliminar" onclick="quitarLineaDestinatario(document.getElementById('seldetalle').value);" />
	</td>
  </tr>
  </table>
  </td>
</tr>
<tr>
  <td valign="top" style="height:150px; width:800px;">
    <table align="center" width="800px">
    <tr>
      <td align="center"><div style="overflow:scroll; height:180px; width:800px;">
      <table width="800px" class="tblLista">
       <tr>
         <th scope="col" class="trListaHead">Dependencia</th>
        <th scope="col" class="trListaHead">Representante/Empleado</th>
        <th scope="col" class="trListaHead">Cargo</th>
        <th scope="col" class="trListaHead">C.c.</th>
        </tr>
       <tbody id="listaDetalles"></tbody>
      </table>
      </div></td>
   </tr>
  </table>
</td>
</tr>
</table>   
</form> 
</div>
<div class="divMsj" style="width:795px;">Campos Obligatorios *</div>
</body>
</html>
<!-- VALIDACIONES CAMPOS OBLIGATORIOS  -->
<SCRIPT LANGUAGE="JavaScript">
function VerificarDatos(formulario) {
	
/// VALIDACION TIPO DOCUMENTO
if (formulario.t_documento.value.length <1) {
 alert("Eliga el \"Tipo Documento\".");
 formulario.t_documento.focus();
return (false);
}
var checkOK = "0123456789";
var checkStr = formulario.t_documento.value;
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
 alert("Eliga el \"Tipo Documento\"."); 
 formulario.t_documento.focus(); 
 return (false); 
}

/// VALIDACION ASUNTO
if (formulario.asunto.value.length <1) {
 alert("Escriba el Asunto en el campo \"Asunto\".");
 formulario.asunto.focus();
return (false);
}
var checkOK ="ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚ" + "abcdefghijklmnñopqrstuvwxyzáéíóú" + ".,_/- " + "0123456789";
var checkStr = formulario.asunto.value;
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
 alert("Corrija el asunto en el campo \"Asunto\"."); 
 formulario.asunto.focus(); 
 return (false); 
}

/// VALIDACION ORGANISMO INTERNO
if (formulario.organismo.value.length <1) {
 alert("Elija el \"Organismo\".");
 formulario.organismo.focus();
return (false);
}
var checkOK ="0123456789";
var checkStr = formulario.organismo.value;
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
 alert("Elija el \"Organismo\"."); 
 formulario.organismo.focus(); 
 return (false); 
}

//// VALIDACION DESCRIPCION ANEXO
if(formulario.anexsi1.value == 'S'){ 
    
   if(formulario.anexDescp.value.length < 1){
      alert("Ingrese el Anexo del Punto de Cuenta.");	
	  formulario.anexDescp.focus(); 
      return false; 
   }
}


/// VALIDACION ORGANISMO INTERNO
if (formulario.dep_interna.value.length <1) {
 alert("Elija la \"Dependencia\".");
 formulario.dep_interna.focus();
return (false);
}
var checkOK ="0123456789";
var checkStr = formulario.dep_interna.value;
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
 alert("Elija la \"Dependencia\"."); 
 formulario.dep_interna.focus(); 
 return (false); 
}

/// VALIDAR QUE INTRODUZCA UN DESTINATARIO
if(formulario.verificador.value.length < 1){
  alert("Elija un Destinatario.");
  return (false);
}
var checkOK = "0123456789";
var checkStr = formulario.verificador.value;
var allValid = true;
for (i=0;i<checkStr.length;i++){
	ch = checkStr.charAt(i);
	for (j=0;j<checkOK.length;j++)
	 if (ch = checkOK.charAt(j))
	 break;
	 if (j == checkOK.length){
	    allValid = false;
	    break;
	 }
}
if (!allValid){
  alert("Elija un Destinatario");
  return (false);
}

return guardarDocumentoInterno(formulario);

}
</SCRIPT>
