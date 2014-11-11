<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_pv.js"></script>
</head>

<body onload="document.getElementById('par').focus();">
<?php
include("fphp_pv.php");
connect();
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Clasificador Presupuestario | Nuevo Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'pv_clasificador_presupuestario.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="pv_clasificador_presupuestario.php" method="POST" onsubmit="return verificarClasificadorPresupuestario(this, 'GUARDAR');">
<input type="hidden" name="filtro" id="filtro" value="<?=$filtro?>" />
<input type="hidden" name="codigo" id="codigo" />
<div style="width:900px" class="divFormCaption">Datos de la Partida</div>
<table width="900" class="tblForm">
	<tr>
		<td class="tagForm">Tipo de Cuenta:</td>
		<td>
        	<select name="cuenta" id="cuenta">
            	<?=loadSelect("pv_tipocuenta", "cod_tipocuenta", "descp_tipocuenta", "", 10)?>
            </select>*
        </td>
	</tr>
	<tr>
		<td class="tagForm">Partida:</td>
		<td><input name="par" type="text" id="par" size="2" maxlength="2" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Generica:</td>
		<td><input name="gen" type="text" id="gen" size="2" maxlength="2" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Especifica:</td>
		<td><input name="esp" type="text" id="esp" size="2" maxlength="2" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Sub-Especifica:</td>
		<td><input name="subesp" type="text" id="subesp" size="2" maxlength="2" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td><input name="descripcion" type="text" id="descripcion" size="125" maxlength="300" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Tipo:</td>
		<td>
			<input id="titulo" name="tipo" type="radio" value="T" checked="checked" /> Titulo
			<input id="detalle" name="tipo" type="radio" value="D" /> Detalle
		</td>
	</tr>
	<tr>
		<td class="tagForm">Cuenta Contable:</td>
		<td>
			<input type="text" name="codcuenta" id="codcuenta" size="15" disabled="disabled" value="<?=$field['CodCuenta']?>" />
			<input type="text" name="nomcuenta" id="nomcuenta" size="75" value="<?=$field['NomCuenta']?>" disabled="disabled" />
			<input type="button" value="..." onclick="cargarVentana(this.form, 'listado_cuentas_contables.php?cod=codcuenta&nom=nomcuenta', 'height=500, width=900, left=50, top=50, resizable=yes');" />*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
			<input id="activo" name="status" type="radio" value="A" checked="checked" /> Activo
			<input id="inactivo" name="status" type="radio" value="I" /> Inactivo
		</td>
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
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'pv_clasificador_presupuestario.php');" />
</center><br />
</form>

<div style="width:900px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
