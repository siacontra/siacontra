<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_lg_2.js"></script>
</head>

<body onload="document.getElementById('codigo').focus();">
<?php
include("fphp_lg.php");
connect();
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Tipo de Transacciones | Nuevo Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'tipos_transacciones_commodities.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="tipos_transacciones_commodities.php" method="POST" onsubmit="return verificarTiposTransaccionesCommodities(this, 'GUARDAR');">
<input type="hidden" name="filtro" id="filtro" value="<?=$filtro?>" />
<div style="width:600px;" class="divFormCaption">Datos del Tipo de Transacci&oacute;n</div>
<table width="600px" class="tblForm">
	<tr>
		<td class="tagForm">Tipo de Transacci&oacute;n:</td>
		<td><input name="codigo" type="text" id="codigo" size="5" maxlength="3" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td><input name="descripcion" type="text" id="descripcion" size="50" maxlength="50" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Tipo de Movimiento:</td>
		<td>
			<input id="ingreso" name="tipo" type="radio" value="I" checked onclick="setDocumentoGenerado('NI');" /> Ingreso
			<input id="egreso" name="tipo" type="radio" onclick="setDocumentoGenerado('NS');" value="E" /> Egreso
			<input id="transferencia" name="tipo" type="radio" value="T" onclick="setDocumentoGenerado('NT');" /> Transferencia
		</td>
	</tr>
	<tr>
		<td class="tagForm">Doc. a generar:</td>
		<td>
			<input name="docgenerado" type="hidden" id="docgenerado" value="NI" />
			<input name="docgeneradodesc" type="text" id="docgeneradodesc" size="50" value="Nota de Ingreso" disabled="disabled" />*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Doc. Transacci&oacute;n:</td>
		<td>
			<select name="doctransaccion" id="doctransaccion" style="width:175px;">
				<option value=""></option>
				<?=loadSelect("lg_tipodocumento", "CodDocumento", "Descripcion", "", 0)?>
			</select>
		</td>
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
			<input name="ult_usuario" type="text" id="ult_usuario" size="30" disabled="disabled" />
			<input name="ult_fecha" type="text" id="ult_fecha" size="25" disabled="disabled" />
		</td>
	</tr>
</table>
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'tipos_transacciones_commodities.php');" />
</center><br />
</form>

<div style="width:600px%" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
