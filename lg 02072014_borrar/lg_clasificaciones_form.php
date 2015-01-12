<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
extract($_POST);
extract($_GET);
//	------------------------------------
include("../lib/fphp.php");
include("../lib/lg_fphp.php");
//	------------------------------------
$cancelar = "document.getElementById('frmentrada').submit();";
$return = "lg_clasificaciones_lista";
$display_submit = "";
$label_submit = "";
$disabled_editar = "";
$disabled_ver = "";
$accion = "";
if ($opcion == "nuevo") {
	$titulo = "Nueva Clasificaci&oacute;n";
	$label_submit = "Guardar";
	$accion = "nuevo";
	$requisiciones = "checked";
	$activo = "checked";
	$almacen = "checked";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	$disabled_editar = "disabled";
	
	if ($opcion == "modificar") {
		$titulo = "Modificar Clasificaci&oacute;n";
		$label_submit = "Modificar";
		$accion = "modificar";
	}
	
	elseif ($opcion == "ver") {
		$titulo = "Ver Clasificaci&oacute;n";
		$display_submit = "display:none;";
		$cancelar = "window.close();";
		$disabled_ver = "disabled";
	}
	
	//	consulto
	$sql = "SELECT * FROM lg_clasificacion WHERE Clasificacion = '".$registro."'";
	$query = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query)) $field = mysql_fetch_array($query);
	
	if ($field['ReqOrdenCompra'] == "R") $requisiciones = "checked"; else $orden = "checked";
	if ($field['FlagRevision'] == "S") $flagrevision = "checked";
	if ($field['FlagRecepcionAlmacen'] == "S") $flagrecepcion = "checked";
	if ($field['FlagCajaChica'] == "S") $flagcajachica = "checked";
	if ($field['FlagTransaccion'] == "S") $flagtransaccion = "checked";
	if ($field['ReqAlmacenCompra'] == "A") $almacen = "checked"; else $compra = "checked";
	if ($field['Estado'] == "A") $activo = "checked"; else $inactivo = "checked";
}
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="../js/funciones.js"></script>
<script type="text/javascript" language="javascript" src="../js/lg_funciones.js"></script>
<script type="text/javascript" language="javascript" src="../js/lg_fscript.js"></script>
</head>

<body onload="document.getElementById('codigo').focus();">
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
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="<?=$cancelar?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="<?=$return?>.php" method="POST" onsubmit="return clasificaciones(this, '<?=$accion?>');">
<input type="hidden" name="buscar" value="<?=$buscar?>" />
<div style="width:600px;" class="divFormCaption">Datos del Registro</div>
<table width="600" class="tblForm">
	<tr>
		<td class="tagForm" width="150">Clasificacion:</td>
		<td><input type="text" id="codigo" maxlength="3" style="width:75px;" value="<?=$field['Clasificacion']?>" <?=$disabled_editar?> />*</td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td><input type="text" id="descripcion" maxlength="50" style="width:250px;" value="<?=($field['Descripcion'])?>" <?=$disabled_ver?> />*</td>
	</tr>
    <tr>
		<td class="tagForm">Disponible para:</td>
		<td>
			<input type="radio" name="disponible" id="requisiciones" value="R" <?=$requisiciones?> <?=$disabled_ver?> /> Requisiciones
			<input type="radio" name="disponible" id="orden" value="O" <?=$orden?> <?=$disabled_ver?> /> Orden de Compra
		</td>
	</tr>
    <tr>
		<td class="tagForm">Almac&eacute;n por defecto:</td>
		<td>
			<select id="codalmacen" style="width:175px;" <?=$disabled_ver?>>
				<option value=""></option>
				<?=loadSelect("lg_almacenmast", "CodAlmacen", "Descripcion", $field['CodAlmacen'], 0)?>
			</select>*
		</td>
	</tr>
    <tr>
		<td class="tagForm">Tipo de Requerimiento:</td>
		<td>
			<select id="requerimiento" style="width:175px;" <?=$disabled_ver?>>
				<option value=""></option>
				<?=getMiscelaneos($field['TipoRequerimiento'], "TIPOREQ", 0)?>
			</select>*
		</td>
	</tr>
    <tr>
		<td class="tagForm">&nbsp;</td>
		<td><input type="checkbox" id="flagrevision" value="S" <?=$flagrevision?> <?=$disabled_ver?> /> La Requisición requiere Revisi&oacute;n</td>
	</tr>
    <tr>
		<td class="tagForm">&nbsp;</td>
		<td><input type="checkbox" id="flagrecepcion" value="S" <?=$flagrecepcion?> <?=$disabled_ver?> /> Recepcionar en Almac&eacute;n</td>
	</tr>
    <tr>
		<td class="tagForm">&nbsp;</td>
		<td><input type="checkbox" id="flagcajachica" value="S" <?=$flagcajachica?> <?=$disabled_ver?> /> Disponible para Caja Chica</td>
	</tr>
    <tr>
		<td class="tagForm">&nbsp;</td>
		<td><input type="checkbox" id="flagtransaccion" value="S" <?=$flagtransaccion?> <?=$disabled_ver?> /> Transacción del Sistema</td>
	</tr>
    <tr>
		<td class="tagForm">Almacen/Compra:</td>
		<td>
			<input type="radio" name="almacen_compra" id="almacen" value="A" <?=$almacen?> <?=$disabled_ver?> /> Almac&eacute;n
			<input type="radio" name="almacen_compra" id="compra" value="C" <?=$compra?> <?=$disabled_ver?> /> Compra
		</td>
	</tr>
    <tr>
		<td class="tagForm">Estado:</td>
		<td>
			<input type="radio" name="status" id="activo" value="A" <?=$activo?> <?=$disabled_ver?> /> Activo
			<input type="radio" name="status" id="inactivo" value="I" <?=$inactivo?> <?=$disabled_ver?> /> Inactivo
		</td>
	</tr>
    <tr>
    	<td class="tagForm">&Uacute;ltima Modif.:</td>
        <td colspan="3">
            <input type="text" size="30" value="<?=$field_mast['UltimoUsuario']?>" disabled="disabled" />
            <input type="text" size="25" value="<?=$field_mast['UltimaFecha']?>" disabled="disabled" />
        </td>
    </tr>
</table>
<center>
<input type="submit" value="<?=$label_submit?>" style="width:80px; <?=$display_submit?>">
<input type="button" value="Cancelar" style="width:80px;" onclick="<?=$cancelar?>" />
</center>
</form>

<div style="width:600px; <?=$display_submit?>" class="divMsj">(*) Campos Obligatorios</div>
</body>
</html>