<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	--------------------------
extract($_POST);
extract($_GET);
//	--------------------------
include("../lib/fphp.php");
include("../lib/rh_fphp.php");
//	--------------------------
if ($accion == "INSERTAR") {
	$titulo = "Nuevo Registro";
	$label_submit = "Guardar Registro";
	$style_submit = "";
	$onclick = "document.getElementById('frmentrada').submit();";
}
elseif ($accion == "ACTUALIZAR" || $accion == "VER") {
	if ($accion == "ACTUALIZAR") {
		$titulo = "Actualizar Registro";
		$label_submit = "Actualizar Registro";
		$style_submit = "";
		$d_codigo = "disabled";
		$onclick = "document.getElementById('frmentrada').submit();";
	}
	elseif ($accion == "VER") {
		$titulo = "Ver Registro";
		$label_submit = "";
		$style_submit = "display:none;";
		$d_codigo = "disabled";
		$d_ver = "disabled";
		$onclick = "window.close();";
	}
	//	consulto los datos del registro
	$sql = "SELECT * FROM rh_gradoscalificacion WHERE Grado = '".$registro."' ORDER BY Grado";
	$query = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="../js/funciones.js"></script>
<script type="text/javascript" language="javascript" src="../js/rh_fscript.js"></script>
</head>

<body onload="document.getElementById('minimo').focus();">
<div id="bloqueo" class="divBloqueo"></div>
<div id="cargando" class="divCargando">
<table>
	<tr>
    	<td valign="middle" style="height:50px;">
			<img src="../imagenes/iconos/cargando.gif" /><br />Procesando...
        </td>
    </tr>
</table>
</div>

<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Grados de Calificaci&oacute;n General | <?=$titulo?></td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="<?=$onclick?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="rh_grados_calificacion_general.php" method="POST" onsubmit="return grados_calificacion_general_form(this, '<?=$accion?>');">
<input type="hidden" name="filtro" id="filtro" value="<?=$filtro?>" />
<div style="width:700px" class="divFormCaption">Datos del Registro</div>
<table width="700" class="tblForm">
	<tr>
		<td class="tagForm" width="125">Grado:</td>
		<td><input type="text" id="codigo" style="width:50px;" value="<?=$field['Grado']?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm">Puntaje Min.:</td>
		<td><input type="text" id="minimo" style="width:50px; text-align:right;" value="<?=$field['PuntajeMin']?>" <?=$d_ver?> />*</td>
	</tr>
	<tr>
		<td class="tagForm">Puntaje Max.:</td>
		<td><input type="text" id="maximo" style="width:50px; text-align:right;" value="<?=$field['PuntajeMax']?>" <?=$d_ver?> />*</td>
	</tr>
	<tr>
		<td class="tagForm">Rango:</td>
		<td><input type="text" id="descripcion" style="width:90%;" value="<?=($field['Descripcion'])?>" <?=$d_ver?> />*</td>
	</tr>
	<tr>
		<td class="tagForm">Definici&oacute;n:</td>
		<td><textarea id="definicion" style="width:90%; height:50px;" <?=$d_ver?>><?=($field['Definicion'])?></textarea></td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
        	<?php if ($field['Estado'] == "A" || $accion == "INSERTAR") $flagactivo = "checked"; else $flaginactivo = "checked"; ?>
			<input id="activo" name="estado" type="radio" value="A" <?=$flagactivo?> <?=$d_ver?> /> Activo &nbsp;&nbsp;
			<input id="inactivo" name="estado" type="radio" value="I" <?=$flaginactivo?> <?=$d_ver?> /> Inactivo
		</td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td>
			<input name="ult_usuario" type="text" id="ult_usuario" value="<?=$field['UltimoUsuario']?>" size="30" disabled="disabled" />
			<input name="ult_fecha" type="text" id="ult_fecha" size="25" value="<?=$field['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>
</table>
<center> 
<input type="submit" value="<?=$label_submit?>" style=" <?=$style_submit?>" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onClick="<?=$onclick?>" />
</center>
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</form>
</body>
</html>
