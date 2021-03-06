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
$sql = "SELECT * FROM ap_tipodocumento WHERE CodTipoDocumento = '".$registro."'";
$query = mysql_query($sql) or de ($sql.mysql_error());
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Tipos de Documentos Ctas. por Pagar | Ver Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<div style="width:700px" class="divFormCaption">Datos del Tipo de Documento</div>
<table width="700" class="tblForm">
	<tr>
		<td class="tagForm" width="125">C&oacute;digo:</td>
		<td colspan="3"><input name="codigo" type="text" id="codigo" size="4" maxlength="2" value="<?=$field['CodTipoDocumento']?>" disabled="disabled" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td colspan="3"><input name="descripcion" type="text" id="descripcion" size="43" maxlength="25" value="<?=htmlentities($field['Descripcion'])?>" disabled="disabled" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Clasificaci&oacute;n:</td>
		<td colspan="3">
			<select name="clasificacion" id="clasificacion" style="width:200px;" disabled="disabled">
				<?=cargarSelect("CLASIFICACION-CXP", $field['Clasificacion'], 1)?>
			</select>*
		</td>
	</tr>
	<tr>
		<td class="tagForm">R&eacute;gimen Fiscal:</td>
		<td colspan="3">
			<select name="regimen" id="regimen" style="width:200px;" disabled="disabled">
				<?=loadSelect("ap_regimenfiscal", "CodRegimenFiscal", "Descripcion", $field['CodRegimenFiscal'], 1)?>
			</select>*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Tipo de Voucher:</td>
		<td colspan="3">
			<select name="voucher" id="voucher" style="width:200px;" disabled="disabled">
				<?=loadSelect("ac_voucher", "CodVoucher", "Descripcion", $field['CodVoucher'], 1)?>
			</select>*
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<? if ($field['FlagProvision'] == "S") $flagprovision = "checked"; else $btProvision = "disabled"; ?>
			<input type="checkbox" name="flagprovision" id="flagprovision" value="S" <?=$flagprovision?> onclick="enabledCtaContable(this.checked, 'btProvision', 'codcuentaprov');" disabled="disabled" /> Generar Voucher de Provisi&oacute;n
		</td>
		<td class="tagForm">Cta. Provisión:</td>
		<td>
			<input type="text" name="codcuentaprov" id="codcuentaprov" size="15" value="<?=$field['CodCuentaProv']?>" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<? if ($field['FlagAdelanto'] == "S") $flagadelanto = "checked"; else $btAdelanto = "disabled"; ?>
			<input type="checkbox" name="flagadelanto" id="flagadelanto" value="S" <?=$flagadelanto?> onclick="enabledCtaContable(this.checked, 'btAdelanto', 'codcuentaade');" disabled="disabled" /> Se le considera Adelanto
		</td>
		<td class="tagForm">Cta. Adelanto:</td>
		<td>
			<input type="text" name="codcuentaade" id="codcuentaade" size="15" value="<?=$field['CodCuentaAde']?>" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
        	<? if ($field['FlagFiscal'] == "S") $flagfiscal = "checked"; else $dcodfiscal = "disabled"; ?>
        	<input type="checkbox" name="flagfiscal" id="flagfiscal" value="S" <?=$flagfiscal?> disabled="disabled" /> Fiscal
        </td>
		<td class="tagForm">C&oacute;digo Fiscal:</td>
		<td>
			<input type="text" name="codfiscal" id="codfiscal" size="5" maxlength="2" value="<?=$field['CodFiscal']?>" disabled="disabled" />*
		</td>
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

</body>
</html>
