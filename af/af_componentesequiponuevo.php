<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//--------
include ("fphp.php");
connect();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
<script type="text/javascript" language="javascript" src="af_fscript.js"></script>
</head>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
 <tr>
  <td class="titulo">Maestro Componentes de un Equipo | Nuevo Registro</td>
 <td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'),'<?=$regresar?>.php?limit=0')">[Cerrar]</a></td>
 </tr>
</table>
<hr width="100%" color="#333333" />
<? 
 echo"<input type='hidden' name='regresar' id='regresar' value='".$regresar."' />";
?>

<form name="frmentrada" id="frmentrada" action="af_componentesequipo.php" method="POST" onsubmit="return  guardarNuevoComponenteEquipo(this);">
<? 
   echo "<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."'/>"; 
   //echo "<input type='text' name='selector' id='selector' />";
?>

<div style="width:780px" class="divFormCaption">Datos</div>
<table id="t_1" width="780" class="tblForm">
<tr>
 <td class="tagForm" colspan="2">Tipo de Equipo:</td>
 <td width="393"><input type="text" name="cod_tipocomponente"  id="cod_tipocomponente" size="8" maxlength="4"/>*</td>
</tr>
<tr>
 <td class="tagForm" colspan="2">Descripci&oacute;n Local:</td>
 <td><input type="text" name="descp_local"  id="descp_local" size="40" maxlength="30"/>*</td>
</tr>
<tr>
 <td></td>
</tr>
<tr>
 <td class="tagForm" colspan="2">Estado:</td>
 <td><input type="hidden" id="radioEstado" name="radioEstado" value="A"/> 
     <input type="radio" name="radio1" checked="checked" onclick="estadosPosee(this.form);"/>Activo<input type="radio" name="radio2" onclick="estadosPosee(this.form);"/>Inactivo</td>
</tr>
<tr><td height="5"></td></tr>
<tr>
   <td width="78"></td>
   <td width="137" class='tagForm'>&Uacute;ltima Modif.:</td>
   <td width="393">
	  <input type="text" name="ult_usuario"  id="ult_usuario" size="30"  readonly />
	  <input type="text" name="ult_fecha"  id="ult_fecha" size="20"  readonly />
   </td>
   <td width="152"></td>
</tr>
</table>
  <center>
    <input name="guardar" type="submit" id="guardar" value="Guardar Registro"/>
    <input name="cancelar" type="button" id="cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'af_componentesequipo.php');" />
  </center><br />
</form>
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>

