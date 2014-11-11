<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript.js"></script>
</head>

<body onload="document.getElementById('grado').focus();">
<?php
include("fphp.php");
connect();

$sql = "SELECT * FROM rh_nivelsalarial WHERE CodNivel = '".$registro."'";
$query = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Grado Salarial | Actualizar Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'grado_salarial.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="grado_salarial.php" method="POST" onsubmit="return verificarGradoSalarial(this, 'ACTUALIZAR');">
<input type="hidden" name="filtro" id="filtro" value="<?=$filtro?>" />
<input type="hidden" name="codigo" id="codigo" value="<?=$field['CodNivel']?>" />
<div style="width:500px" class="divFormCaption">Datos del Grado Salarial</div>
<table width="500" class="tblForm">
	<tr>
        <td class="tagForm">Categor&iacute;a:</td>
        <td>
            <select name="categoria" id="categoria" class="selectMed">
                <?=getMiscelaneos($field['CategoriaCargo'], "CATCARGO", 1)?>
            </select>*
        </td>
    </tr>
	<tr>
		<td class="tagForm">Grado:</td>
		<td><input name="grado" type="text" id="grado" size="4" maxlength="3" disabled="disabled" value="<?=$field['Grado']?>" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td><input name="descripcion" type="text" id="descripcion" size="75" maxlength="45" value="<?=($field['Descripcion'])?>" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Sueldo Minimo:</td>
		<td><input name="sueldo_minimo" type="text" id="sueldo_minimo" size="25" value="<?=number_format($field['SueldoMinimo'], 2, ',', '')?>" onkeyup="getSueldoPromedio(document.getElementById('sueldo_minimo').value, document.getElementById('sueldo_maximo').value);" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Sueldo Maximo:</td>
		<td><input name="sueldo_maximo" type="text" id="sueldo_maximo" size="25" value="<?=number_format($field['SueldoMaximo'], 2, ',', '')?>" onkeyup="getSueldoPromedio(document.getElementById('sueldo_minimo').value, document.getElementById('sueldo_maximo').value);" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Sueldo Promedio:</td>
		<td><input name="sueldo_promedio" type="text" id="sueldo_promedio" size="25" value="<?=number_format($field['SueldoPromedio'], 2, ',', '')?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
			<?
			if ($field['Estado'] == "A") $flagactivo = "checked"; else $flaginactivo = "checked";
			?>
			<input id="activo" name="status" type="radio" value="A" <?=$flagactivo?> /> Activo
			<input id="inactivo" name="status" type="radio" value="I" <?=$flaginactivo?> /> Inactivo
		</td>
	</tr>
	<tr>
	<td class="tagForm">&Uacute;ltima Modif.:</td>
	<td>
		<input name="ult_usuario" type="text" id="ult_usuario" size="30" value="<?=$field['UltimoUsuario']?>" disabled="disabled" />
		<input name="ult_fecha" type="text" id="ult_fecha" size="25" value="<?=$field['UltimaFecha']?>" disabled="disabled" />
	</td>
	</tr>
</table>
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'grado_salarial.php');" />
</center><br />
</form>

<div style="width:500px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
