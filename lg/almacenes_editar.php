<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_lg.js"></script>
</head>

<body onload="document.getElementById('codigo').focus();">
<?php
include("fphp_lg.php");
connect();
//	---------------------
$sql = "SELECT 
			a.*,
			mp.NomCompleto AS NomPersona 
		FROM 
			lg_almacenmast a
			LEFT JOIN mastpersonas mp ON (a.CodPersona = a.CodPersona)
		WHERE 
			a.CodAlmacen = '".$registro."'";
$query = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Almacenes | Actualizar Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'almacenes.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="almacenes.php" method="POST" onsubmit="return verificarAlmacenes(this, 'ACTUALIZAR');">
<input type="hidden" name="filtro" id="filtro" value="<?=$filtro?>" />
<div style="width:90%" class="divFormCaption">Datos del Almac&eacute;n</div>
<table width="90%" class="tblForm">
	<tr>
		<td class="tagForm">Almac&eacute;n:</td>
		<td><input name="codigo" type="text" id="codigo" size="10" maxlength="6" value="<?=$field['CodAlmacen']?>" disabled="disabled" />*</td>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td><input name="descripcion" type="text" id="descripcion" size="50" maxlength="50" value="<?=($field['Descripcion'])?>" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Tipo de Almac&eacute;n:</td>
		<td>
			<select name="tipo" id="tipo" style="width:175px;" onchange="enabledAlmacenPrincipal(this.value);">
				<option value=""></option>
				<?=loadSelectValores("TIPOALMACEN", $field['TipoAlmacen'], 0)?>
			</select>*
		</td>
		<td class="tagForm">Almac&eacute;n Principal:</td>
		<td>
			<select name="principal" id="principal" style="width:175px;" disabled="disabled">
				<option value=""></option>
				<?=loadSelect("lg_almacenmast", "CodAlmacen", "Descripcion", $field['AlmacenTransito'], 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Organismo:</td>
		<td>
			<select name="organismo" id="organismo" style="width:300px;">
				<?=loadSelect("mastorganismos", "CodOrganismo", "Organismo", $field['CodOrganismo'], 0)?>
			</select>
		</td>
		<td class="tagForm">Dependencia:</td>
		<td>
			<select name="dependencia" id="dependencia" style="width:300px;">
				<?=loadSelectDependiente("mastdependencias", "CodDependencia", "Dependencia", "CodOrganismo", $field['CodDependencia'], $field['CodOrganismo'], 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Empleado:</td>
		<td>
			<input name="codpersona" type="hidden" id="codpersona" value="<?=$field['CodPersona']?>" />
			<input name="persona" type="text" id="persona" size="65" value="<?=($field['NomPersona'])?>" disabled="disabled" />
			<input name="bt_examinar" type="button" id="bt_examinar" value="..." onclick="window.open('lista_personas.php?limit=0', 'wLista', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=500, width=800, left=200, top=200, resizable=yes');" />*
		</td>
		<td class="tagForm">Cuenta Inventario:</td>
		<td><input name="cuenta" type="text" id="cuenta" size="50" maxlength="50" value="<?=$field['CuentaInventario']?>" /></td>
	</tr>
	<tr>
		<td class="tagForm">Direcci&oacute;n</td>
		<td colspan="2"><textarea name="direccion" id="direccion" style="height:50px; width:90%;"><?=($field['Direccion'])?></textarea></td>
		<td width="350">
			<?
			if ($field['FlagVenta'] == "S") $flagventa = "checked";
			if ($field['FlagProduccion'] == "S") $flagproduccion = "checked";
			if ($field['FlagCommodity'] == "S") $flagcommodities = "checked";
			?>
			<input type="checkbox" name="flags" id="flagventa" value="S" <?=$flagventa?> /> Almac&eacute;n de Venta &nbsp; &nbsp; &nbsp; <br />
			<input type="checkbox" name="flags" id="flagproduccion" value="S" <?=$flagproduccion?> /> Almac&eacute;n de Producci&oacute;n &nbsp; &nbsp; &nbsp; <br />
			<input type="checkbox" name="flags" id="flagcommodities" value="S" <?=$flagcommodities?> /> Almac&eacute;n para Commodities
		</td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td colspan="3">
			<?
			if ($field['Estado'] == "A") $flagactivo = "checked";
			else $flaginactivo = "checked";
			?>
			<input id="activo" name="status" type="radio" value="A" <?=$flagactivo?> /> Activo
			<input id="inactivo" name="status" type="radio" value="I" <?=$flaginactivo?> /> Inactivo
		</td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td colspan="3">
			<input name="ult_usuario" type="text" id="ult_usuario" size="30" <?=$field['UltimoUsuario']?> disabled="disabled" />
			<input name="ult_fecha" type="text" id="ult_fecha" size="25" <?=$field['UltimaFecha']?> disabled="disabled" />
		</td>
	</tr>
</table>
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'almacenes.php');" />
</center><br />
</form>

<div style="width:90%" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
