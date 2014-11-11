<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_2.js"></script>
</head>

<body onload="document.getElementById('periodo').focus();">
<?php
include("fphp.php");
connect();
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Sueldos Minimos | Nuevo Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'sueldos_minimos.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="sueldos_minimos.php" method="POST" onsubmit="return verificarSueldoMinimo(this, 'GUARDAR');">
<input type="hidden" name="secuencia" id="secuencia" />
<div style="width:400px" class="divFormCaption">Datos del Sueldo Minimo</div>
<table width="400" class="tblForm">
	<tr>
		<td class="tagForm">Periodo:</td>
		<td><input name="periodo" type="text" id="periodo" size="15" maxlength="7" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Monto:</td>
		<td><input name="monto" type="text" id="monto" size="15" />*</td>
	</tr>
	<tr>
        <td class="tagForm">&Uacute;ltima Modif.:</td>
        <td>
            <input name="ult_usuario" type="text" id="ult_usuario" size="30"  disabled="disabled" />
            <input name="ult_fecha" type="text" id="ult_fecha" size="25"  disabled="disabled" />
        </td>
	</tr>
</table>
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'sueldos_minimos.php');" />
</center><br />
</form>

<div style="width:400px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
