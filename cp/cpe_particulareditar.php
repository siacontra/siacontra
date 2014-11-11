<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include ("fphp.php");
connect();
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
</head>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro Particular | Editar Registro</td>
		<td align="right"><a class="cerrar" href="<?=$regresar?>.php">[cerrar]</a></td>
	</tr>
</table>
<?
$s="select * from cp_particular where CodParticular = '".$registro."'";
$q=mysql_query($s) or die ($s.mysql_error());
$f=mysql_fetch_array($q);
?>
<hr width="100%" color="#333333" />
<form id="frmentrada" name="frmentrada" action="cpe_particular.php?limit=0&accion=guardarParticularEditar" method="post">
<div class="divFormCaption" style="width:700px;">Datos</div>
<table class="tblForm" width="700">
<tr> 
 <td width="194" class="tagForm"> Cod. Particular:</td>
 <td width="494"><input type="text" id="cod_particular" name="cod_particular" size="8" maxlength="4" value="<?=$f['CodParticular']?>" readonly="readonly"/></td>
</tr>
<tr><td height="10"></td></tr>
<tr>
 <td class="tagForm">N° Cedula:</td>
 <td><input type="text" id="cedula" name="cedula" size="15" maxlength="12" value="<?=$f['Cedula']?>"/>*</td>
</tr>
<tr>
 <td class="tagForm">Nombre y Apellido:</td>
 <td><input type="text" id="nombre" name="nombre" size="40" value="<?=$f['Nombre']?>"/>*</td>
</tr>
<!--<tr>
 <td class="tagForm">apellido(s):</td>
 <td><input type="text" id="apellido" name="apellido" size="40" value="<?=$f['Apellido']?>"/>*</td>
</tr>-->
<tr>
 <td class="tagForm">Direcci&oacute;n:</td>
 <td><input type="text" id="direccion" name="direccion" size="40" value="<?=$f['Direccion']?>"/></td>
</tr>
<tr>
 <td class="tagForm">Nro. Tel&eacute;fono:</td>
 <td><input type="text" id="telefono" name="telefono" size="20" value="<?=$f['Telefono']?>"/></td>
</tr>
<tr>
 <td class="tagForm">Cargo:</td>
 <td><input type="text" id="cargo" name="cargo" size="40" value="<?=$f['Cargo']?>"/></td>
</tr>

<tr><td height="10"></td></tr>

<tr>
 <td class="tagForm">&Uacute;ltima Modif.:</td>
 <td><input type="text" id="usuario" size="20" value="<?=$f['UltimoUsuario']?>" readonly/>
     <input type="text" id="fecha" size="20" value="<?=$f['UltimaFechaModif']?>"readonly/></td>
</tr>
<tr><td height="2"></td></tr>
</table>
<center>
 <input type="submit" id="guardar" name="guardar" value="Guardar Registro"/>
 <input type="button" id="cancelar" name="cancelar" value="Cancelar" onclick="cargarPagina(this.form,'<?=$regresar?>.php?limit=0');" />
</center>
</form>
<div class="divMsj" style="width:700px;">Campos Obligatorios *</div>
</body></html>