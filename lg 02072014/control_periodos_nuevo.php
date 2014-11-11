<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_lg_2.js"></script>
<body>
<?php
include("fphp_lg.php");
connect();
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Control de Periodos | Nuevo Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'control_periodos.php?limit=0');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="control_periodos.php?limit=0" method="POST" onsubmit="return verificarControlPeriodo(this, 'INSERTAR');">
<input type="hidden" name="forganismo" id="forganismo" value="<?=$forganismo?>" />
<input type="hidden" name="fperiodo" id="fperiodo" value="<?=$fperiodo?>" />

<div style="width:700px;" class="divFormCaption">Datos Generales</div>
<table width="700px" class="tblForm">
	<tr>
		<td class="tagForm" width="150">Organismo:</td>
		<td>
        	<select id="organismo" style="width:300px;">
				<?=getOrganismos($forganismo, 3);?>
			</select>
        </td>
	</tr>
	<tr>
		<td class="tagForm">Periodo:</td>
		<td><input type="text" id="periodo" size="15" maxlength="7" /></td>
	</tr>
	<tr>
		<td class="tagForm">&nbsp;</td>
		<td>
			<input type="checkbox" id="flagtransaccion" /> Disponible para Transacci&oacute;n
		</td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
			<input id="activo" name="status" type="radio" value="A" checked /> Activo
			<input id="inactivo" name="status" type="radio" value="I" /> Inactivo
		</td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td>
			<input name="ult_usuario" type="text" id="ult_usuario" size="30" disabled="disabled" />
			<input name="ult_fecha" type="text" id="ult_fecha" size="25" disabled="disabled" />
		</td>
	</tr>
</table>

<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'control_periodos.php?limit=0');" />
</center><br />
</form>

<div style="width:700px%" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
