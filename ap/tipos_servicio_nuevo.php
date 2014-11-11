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
		<td class="titulo">Tipos de Servicio | Nuevo Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'tipos_servicio.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="tipos_servicio.php" method="POST" onsubmit="return verificarTipoServicio(this, 'GUARDAR');">
<input type="hidden" name="filtro" id="filtro" value="<?=$filtro?>" />
<div style="width:500px" class="divFormCaption">Datos del Tipo de Servicio</div>
<table width="500" class="tblForm">
	<tr>
		<td class="tagForm" width="125">C&oacute;digo:</td>
		<td><input name="codigo" type="text" id="codigo" size="8" maxlength="5" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td><input name="descripcion" type="text" id="descripcion" style="width:90%;" maxlength="25" />*</td>
	</tr>
	<tr>
		<td class="tagForm">R&eacute;gimen Fiscal:</td>
		<td colspan="3">
			<select name="regimen" id="regimen" style="width:200px;">
				<option value=""></option>
				<?=loadSelect("ap_regimenfiscal", "CodRegimenFiscal", "Descripcion", "", 0)?>
			</select>*
		</td>
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
		<input name="ult_usuario" type="text" id="ult_usuario" size="30" readonly />
		<input name="ult_fecha" type="text" id="ult_fecha" size="25" readonly />
	</td>
	</tr>
</table>
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'tipos_servicio.php');" />
</center>
</form>
<div style="width:500px" class="divMsj">Campos Obligatorios *</div>

<br /><div class="divDivision">Impuestos aplicables a este servicio</div><br />

<form name="frmelementos">
<table width="500" class="tblForm">
	<tr>
		<td width="150" align="right">Impuesto: </td>
		<td>
			<select name="impuesto" id="impuesto" style="width:80%;" disabled="disabled">
				<option value=""></option>
				<?=loadSelect("mastimpuestos", "CodImpuesto", "Descripcion", "", 0)?>
			</select>
		</td>
	</tr>
</table>

<table width="500" class="tblBotones">
 <tr>
	<td align="right">
		<input name="btInsertar" type="submit" class="btLista" id="btInsertar" value="Insertar" disabled /> | 
		<input name="btBorrar" type="button" class="btLista" id="btBorrar" value="Borrar" disabled />
	</td>
 </tr>
</table>

<table width="500" class="tblLista">
	<tr class="trListaHead">
		<th width="150" scope="col">C&oacute;digo</th>
		<th scope="col">Descripci&oacute;n</th>
	</tr>
</table>
</form>

</body>
</html>
