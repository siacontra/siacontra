<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_sia.js"></script>
</head>

<body onload="document.getElementById('codigo').focus();">
<?php
include("fphp_sia.php");
connect();
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Impuestos | Nuevo Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'impuestos.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="impuestos.php" method="POST" onsubmit="return verificarImpuesto(this, 'GUARDAR');">
<input type="hidden" name="filtro" id="filtro" value="<?=$filtro?>" />
<div style="width:600px" class="divFormCaption">Datos del Impuesto</div>
<table width="600" class="tblForm">
	<tr>
		<td class="tagForm" width="125">C&oacute;digo:</td>
		<td><input name="codigo" type="text" id="codigo" size="4" maxlength="3" /></td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td><input name="descripcion" type="text" id="descripcion" style="width:90%;" maxlength="25" />*</td>
	</tr>
	<tr>
		<td class="tagForm">R&eacute;gimen Fiscal:</td>
		<td>
			<select name="regimen" id="regimen" style="width:200px;">
				<option value=""></option>
				<?=loadSelect("ap_regimenfiscal", "CodRegimenFiscal", "Descripcion", "", 0)?>
			</select>*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Signo:</td>
		<td>
			<input id="positivo" name="signo" type="radio" value="+" checked /> Positivo 
			<input id="negativo" name="signo" type="radio" value="-" /> Negativo
		</td>
	</tr>
	<tr>
		<td class="tagForm">Provisionar en:</td>
		<td>
			<select name="provisionar" id="provisionar" style="width:200px;">
				<option value=""></option>
				<?=cargarSelect("PROVISION", "", 0)?>
			</select>*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Monto Imponible:</td>
		<td>
			<select name="imponible" id="imponible" style="width:200px;">
				<option value=""></option>
				<?=cargarSelect("MONTO-IMPONIBLE", "", 0)?>
			</select>*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Cuenta Contable:</td>
		<td>
			<input type="text" name="codcuenta" id="codcuenta" size="15" disabled="disabled" />
			<input type="hidden" name="nomcuenta" id="nomcuenta" />
			<input type="button" value="..." onclick="cargarVentana(this.form, 'listado_cuentas_contables.php?cod=codcuenta&nom=nomcuenta', 'height=500, width=900, left=50, top=50, resizable=yes');" />*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Porcentaje:</td>
		<td><input name="porcentaje" type="text" id="porcentaje" size="15" maxlength="5" /></td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
			<input id="activo" name="status" type="radio" value="A" checked /> Activo &nbsp;&nbsp;
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
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'impuestos.php');" />
</center>
<div style="width:600px" class="divMsj">Campos Obligatorios *</div>
</form>

</body>
</html>
