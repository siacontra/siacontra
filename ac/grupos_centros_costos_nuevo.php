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

<body>
<?php
include("fphp_sia.php");
connect();
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Grupos de Centros de Costos | Nuevo Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'grupos_centros_costos.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="grupos_centros_costos.php" method="POST" onsubmit="return verificarGrupoCentroCosto(this, 'GUARDAR');">
<input type="hidden" name="filtro" id="filtro" value="<?=$filtro?>" />
<div style="width:550px" class="divFormCaption">Datos del Grupo de Centro de Costo</div>
<table width="550" class="tblForm">
	<tr>
		<td class="tagForm" width="125">C&oacute;digo:</td>
		<td><input name="codigo" type="text" id="codigo" size="8" maxlength="4" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td><input name="descripcion" type="text" id="descripcion" style="width:90%;" maxlength="50" />*</td>
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
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'grupos_centros_costos.php');" />
</center>
</form>
<div style="width:550px" class="divMsj">Campos Obligatorios *</div>

<br /><div class="divDivision">Sub-Grupos de Centros de Costos</div><br />

<form name="frmelementos">
<table width="550" class="tblForm">
	<tr>
		<td class="tagForm" width="75">Sub-Grupo:</td>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td class="tagForm" width="75">Estado:</td>
	</tr>
	<tr>
		<td><input name="codsubgrupo" type="text" id="codsubgrupo" style="width:99%;" maxlength="4" disabled="disabled" /></td>
		<td><input name="nomsubgrupo" type="text" id="nomsubgrupo" style="width:99%;" maxlength="50" disabled="disabled" /></td>
		<td>
			<select name="edosubgrupo" id="edosubgrupo" style="width:99%;" disabled="disabled">
				<option value=""></option>
				<?=cargarSelect("ESTADO", "", 0);?>
			</select>
		</td>
	</tr>
</table>

<table width="550" class="tblBotones">
 <tr>
	<td align="right">
		<input name="btInsertar" type="submit" class="btLista" id="btInsertar" value="Insertar" disabled />
		<input name="btLimpiar" type="button" class="btLista" id="btLimpiar" value="Limpiar" disabled /> | 
		<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" disabled />
		<input name="btBorrar" type="button" class="btLista" id="btBorrar" value="Borrar" disabled />
	</td>
 </tr>
</table>

<table width="550" class="tblLista">
	<tr class="trListaHead">
		<th width="75" scope="col">Sub-Grupo</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="75" scope="col">Estado</th>
	</tr>
</table>
</form>

</body>
</html>
