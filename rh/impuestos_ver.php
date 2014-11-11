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
//----------------------
$sql = "SELECT * FROM mastimpuestos WHERE CodImpuesto = '".$registro."'";
$query = mysql_query($sql) or de ($sql.mysql_error());
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Impuestos | Ver Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<div style="width:600px" class="divFormCaption">Datos del Impuesto</div>
<table width="600" class="tblForm">
	<tr>
		<td class="tagForm" width="125">C&oacute;digo:</td>
		<td><input name="codigo" type="text" id="codigo" size="4" value="<?=$field['CodImpuesto']?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td><input name="descripcion" type="text" id="descripcion" style="width:90%;" maxlength="25" value="<?=($field['Descripcion'])?>" disabled="disabled" />*</td>
	</tr>
	<tr>
		<td class="tagForm">R&eacute;gimen Fiscal:</td>
		<td>
			<select name="regimen" id="regimen" style="width:200px;" disabled="disabled">
				<?=loadSelect("ap_regimenfiscal", "CodRegimenFiscal", "Descripcion", $field['CodRegimenFiscal'], 1)?>
			</select>*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Signo:</td>
		<td>
			<? if ($field['Signo'] == "P") $flagpositivo = "checked"; else $flagnegativo = "checked"; ?>
			<input id="positivo" name="signo" type="radio" value="P" disabled="disabled" <?=$flagpositivo?> /> Positivo 
			<input id="negativo" name="signo" type="radio" value="N" disabled="disabled" <?=$flagnegativo?> /> Negativo
		</td>
	</tr>
	<tr>
		<td class="tagForm">Provisionar en:</td>
		<td>
			<select name="provisionar" id="provisionar" style="width:200px;" disabled="disabled">
				<?=cargarSelect("PROVISION", $field['FlagProvision'], 1)?>
			</select>*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Monto Imponible:</td>
		<td>
			<select name="imponible" id="imponible" style="width:200px;" disabled="disabled">
				<?=cargarSelect("MONTO-IMPONIBLE", $field['FlagImponible'], 1)?>
			</select>*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Cuenta Contable:</td>
		<td>
			<input type="text" name="codcuenta" id="codcuenta" size="15" disabled="disabled" value="<?=$field['CodCuenta']?>" />
			<input type="hidden" name="nomcuenta" id="nomcuenta" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Porcentaje:</td>
		<td><input name="porcentaje" type="text" id="porcentaje" size="15" maxlength="2" value="<?=$field['FactorPorcentaje']?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
			<? if ($field['Estado'] == "A") $flagactivo = "checked"; else $flaginactivo = "checked"; ?>
			<input id="activo" name="status" type="radio" value="A" <?=$flagactivo?> disabled="disabled" /> Activo &nbsp;&nbsp;
			<input id="inactivo" name="status" type="radio" value="I" <?=$flaginactivo?> disabled="disabled" /> Inactivo
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
</form>

</body>
</html>
