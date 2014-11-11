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
		<td class="titulo">Maestro Tipo Correspondencia | Nuevo Registro</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table>
<hr width="100%" color="#333333" />
<form id="frmentrada" name="frmentrada" action="cp_tipocorrespondencia.php?limit=0&accion=guardarTipoCorrespondencia" method="post">
<div class="divFormCaption" style="width:700px;">Datos</div>
<table class="tblForm" width="700">
<tr> 
 <td width="194" class="tagForm"> C&oacute;d. Documento:</td>
 <td width="494"><input type="text" id="cod_tipodocumento" name="cod_tipodocumento" size="8" maxlength="4" readonly="readonly"/></td>
</tr>
<tr>
 <td class="tagForm">Descripci&oacute;n:</td>
 <td><input type="text" id="descripCorta" name="descripCorta" size="8" maxlength="2"/>*</td>
</tr>
<tr>
 <td class="tagForm">Descripci&oacute;n Completa:</td>
 <td><input type="text" id="descripcion" name="descripcion" size="55"/>*</td>
</tr>
<tr>
 <td class="tagForm">Uso Interno</td>
 <td><input type="checkbox" name="uso_interno" id="uso_interno" value="1"/>
     Uso Externo
     <input type="checkbox" name="uso_externo" id="uso_externo" value="1"/>
     Procedencia Externa
     <input type="checkbox" name="proc_externa" id="proc_externa" value="1"/> *</td>
</tr>
<tr><td height="5"></td></tr>
<tr>
 <td class="tagForm">Estado:</td>
 <td>Activo<input type="radio" name="estado" id="estado" value="A" checked/> 
   Inactivo<input type="radio" name="estado" id="estado" value="I"/> *</td>
</tr>
<tr>
 <td class="tagForm">&Uacute;ltima Modif.:</td>
 <td><input type="text" id="usuario" size="20" readonly/>
     <input type="text" id="fecha" size="20" readonly/></td>
</tr>
<tr><td height="2"></td></tr>
</table>
<center>
 <input type="submit" id="guardar" name="guardar" value="Guardar Registro"/>
 <input type="button" id="cancelar" name="cancelar" value="Cancelar" onclick="cargarPagina(this.form,'cp_tipocorrespondencia.php');" />
</center>
</form>
<div class="divMsj" style="width:700px;">Campos Obligatorios *</div>
</body>
</html>