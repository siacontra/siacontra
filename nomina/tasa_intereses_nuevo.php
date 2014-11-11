<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_nomina.js"></script>
</head>

<body>
<?php
include("fphp_nomina.php");
connect();
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Tasa de Interes | Nuevo Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'tasa_intereses.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="tasa_intereses.php" method="POST" onsubmit="return verificarTasaInteres(this, 'GUARDAR');">
<input type="hidden" name="filtro" id="filtro" value="<?=$filtro?>" />
<div style="width:500px" class="divFormCaption">Datos del Periodo</div>
<table width="500" class="tblForm">
	<tr>
		<td class="tagForm">Periodo:</td>
		<td><input name="periodo" type="text" id="periodo" size="10" maxlength="7" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Porcentaje:</td>
		<td><input name="porcentaje" type="text" id="porcentaje" size="25" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
			<input id="activo" name="status" type="radio" value="A" checked /> Activo
			<input id="inactivo" name="status" type="radio" value="I" /> Inactivo
		</td>
	</tr>
	<tr>
	<td class="tagForm">&Uacute;ltima Modif.:</td>
	<td>
		<input name="ult_usuario" type="text" id="ult_usuario" size="30" readonly />
		<input name="ult_fecha" type="text" id="ult_fecha" size="25" readonly />
	</td>
	</tr>
</table>
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'tasa_intereses.php');" />
</center><br />
</form>

<div style="width:500px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
