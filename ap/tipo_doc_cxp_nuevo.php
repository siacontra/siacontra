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
		<td class="titulo">Tipos de Documentos Ctas. por Pagar | Nuevo Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'tipo_doc_cxp.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="tipo_doc_cxp.php" method="POST" onsubmit="return verificarTipoDocumentoCXP(this, 'GUARDAR');">
<input type="hidden" name="filtro" id="filtro" value="<?=$filtro?>" />
<div style="width:700px" class="divFormCaption">Datos del Tipo de Documento</div>
<table width="700" class="tblForm">
	<tr>
		<td class="tagForm" width="125">C&oacute;digo:</td>
		<td colspan="3"><input name="codigo" type="text" id="codigo" size="4" maxlength="2" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td colspan="3"><input name="descripcion" type="text" id="descripcion" size="43" maxlength="25" />*</td>
	</tr>
	<tr>
		<td class="tagForm">Clasificaci&oacute;n:</td>
		<td colspan="3">
			<select name="clasificacion" id="clasificacion" style="width:200px;">
				<option value=""></option>
				<?=cargarSelect("CLASIFICACION-CXP", "", 0)?>
			</select>*
		</td>
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
		<td class="tagForm">Tipo de Voucher:</td>
		<td colspan="3">
			<select name="voucher" id="voucher" style="width:200px;">
				<option value=""></option>
				<?=loadSelect("ac_voucher", "CodVoucher", "Descripcion", "", 0)?>
			</select>*
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="checkbox" name="flagprovision" id="flagprovision" value="S" checked="checked" onclick="enabledCtaContable(this.checked, 'btProvision', 'codcuentaprov');" /> Generar Voucher de Provisi&oacute;n</td>
		<td class="tagForm">Cta. Provisión:</td>
		<td>
			<input type="text" name="codcuentaprov" id="codcuentaprov" size="15" disabled="disabled" />
			<input type="hidden" name="nomcuentaprov" id="nomcuentaprov" />
			<input type="button" value="..." id="btProvision" onclick="cargarVentana(this.form, 'listado_cuentas_contables.php?cod=codcuentaprov&nom=nomcuentaprov', 'height=500, width=900, left=50, top=50, resizable=yes');" />*
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="checkbox" name="flagadelanto" id="flagadelanto" value="S" onclick="enabledCtaContable(this.checked, 'btAdelanto', 'codcuentaade');" /> Se le considera Adelanto</td>
		<td class="tagForm">Cta. Adelanto:</td>
		<td>
			<input type="text" name="codcuentaade" id="codcuentaade" size="15" disabled="disabled" />
			<input type="hidden" name="nomcuentaade" id="nomcuentaade" />
			<input type="button" value="..." id="btAdelanto" onclick="cargarVentana(this.form, 'listado_cuentas_contables.php?cod=codcuentaade&nom=nomcuentaade', 'height=500, width=900, left=50, top=50, resizable=yes');" disabled="disabled" />*
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="checkbox" name="flagfiscal" id="flagfiscal" value="S" onclick="enabledCtaContable(this.checked, 'codfiscal', 'codfiscal');" /> Fiscal</td>
		<td class="tagForm">C&oacute;digo Fiscal:</td>
		<td>
			<input type="text" name="codfiscal" id="codfiscal" size="5" maxlength="2" disabled="disabled" />*
		</td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td colspan="3">
			<input id="activo" name="status" type="radio" value="A" checked /> Activo &nbsp;&nbsp;
			<input id="inactivo" name="status" type="radio" value="I" /> Inactivo
		</td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td colspan="3">
			<input name="ult_usuario" type="text" id="ult_usuario" size="30" disabled="disabled" />
			<input name="ult_fecha" type="text" id="ult_fecha" size="25" disabled="disabled" />
		</td>
	</tr>
</table>
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'tipo_doc_cxp.php');" />
</center>
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</form>

</body>
</html>
