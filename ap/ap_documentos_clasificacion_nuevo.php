<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_ap.js"></script>
</head>

<body onload="document.getElementById('codigo').focus();">
<?php
include("fphp_ap.php");
connect();
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Clasificaci&oacute;n de Documentos | Nuevo Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'ap_documentos_clasificacion.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="ap_documentos_clasificacion.php" method="POST" onsubmit="return verificarDocumentoClasificacion(this, 'GUARDAR');">
<input type="hidden" name="filtro" id="filtro" value="<?=$filtro?>" />
<div style="width:700px" class="divFormCaption">Datos del Registro</div>
<table width="700" class="tblForm">
	<tr>
		<td class="tagForm" width="125">C&oacute;digo:</td>
		<td><input type="text" id="codigo" size="6" maxlength="3" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td><input type="text" id="descripcion" maxlength="50" style="width:400px;" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Cta. Contable:</td>
		<td>
			<input type="text" name="codcuenta" id="codcuenta" size="15" disabled="disabled" />
			<input type="hidden" name="nomcuenta" id="nomcuenta" />
			<input type="button" value="..." onclick="cargarVentana(this.form, 'listado_cuentas_contables.php?cod=codcuenta&nom=nomcuenta', 'height=500, width=900, left=50, top=50, resizable=yes');" />
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="checkbox" id="flagitem" value="S" /> Item</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="checkbox" id="flagcliente" value="S" /> Cliente</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="checkbox" id="flagcompromiso" value="S" /> Compromiso</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="checkbox" id="flagtransaccion" value="S" checked="checked" /> Transacci&oacute;n del Sistema</td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
			<input id="activo" name="estado" type="radio" value="A" checked /> Activo &nbsp;&nbsp;
			<input id="inactivo" name="estado" type="radio" value="I" /> Inactivo
		</td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td>
			<input name="ult_usuario" type="text" id="ult_usuario" size="30" disabled="disabled" />
			<input name="ult_fecha" type="text" id="ult_fecha" size="25" disabled="disabled" />
		</td>
	</tr>
</table>
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'ap_documentos_clasificacion.php');" />
</center>
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</form>

</body>
</html>
