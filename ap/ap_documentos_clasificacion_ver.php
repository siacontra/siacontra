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

<body>
<?php
include("fphp_ap.php");
connect();
//	-------------------------------------------
$sql = "SELECT
			dc.*,
			c.Descripcion AS NomCuenta
		FROM 
			ap_documentosclasificacion dc
			LEFT JOIN ac_mastplancuenta c ON (dc.CodCuenta = c.CodCuenta)
		WHERE 
			dc.DocumentoClasificacion = '".$registro."'";
$query = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
//	-------------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Clasificaci&oacute;n de Documentos | Ver Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<div style="width:700px" class="divFormCaption">Datos del Registro</div>
<table width="700" class="tblForm">
	<tr>
		<td class="tagForm" width="125">C&oacute;digo:</td>
		<td><input type="text" id="codigo" size="6" maxlength="3" value="<?=$field['DocumentoClasificacion']?>" disabled="disabled" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td><input type="text" id="descripcion" maxlength="50" style="width:400px;" value="<?=($field['Descripcion'])?>" disabled="disabled" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Cta. Contable:</td>
		<td>
			<input type="text" name="codcuenta" id="codcuenta" size="15" value="<?=$field['CodCuenta']?>" disabled="disabled" />
			<input type="hidden" name="nomcuenta" id="nomcuenta" value="<?=($field['NomCuenta'])?>" />
			<input type="button" value="..." disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
        	<? if ($field['FlagItem'] == "S") $flagitem = "checked"; ?>
            <input type="checkbox" id="flagitem" value="S" <?=$flagitem?> disabled="disabled" /> Item
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
        	<? if ($field['FlagCliente'] == "S") $flagcliente = "checked"; ?>
            <input type="checkbox" id="flagcliente" value="S" <?=$flagcliente?> disabled="disabled" /> Cliente
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
        	<? if ($field['FlagCompromiso'] == "S") $flagcompromiso = "checked"; ?>
            <input type="checkbox" id="flagcompromiso" value="S" <?=$flagcompromiso?> disabled="disabled" /> Compromiso
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
        	<? if ($field['FlagTransaccion'] == "S") $flagtransaccion = "checked"; ?>
            <input type="checkbox" id="flagtransaccion" value="S" <?=$flagtransaccion?> disabled="disabled" /> Transacci&oacute;n del Sistema
		</td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
        	<? if ($field['Estado'] == "A") $flagactivo = "checked"; else $flaginactivo = "checked"; ?>
			<input id="activo" name="estado" type="radio" value="A" <?=$flagactivo?> disabled="disabled" /> Activo &nbsp;&nbsp;
			<input id="inactivo" name="estado" type="radio" value="I" <?=$flaginactivo?> disabled="disabled" /> Inactivo
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
</body>
</html>
