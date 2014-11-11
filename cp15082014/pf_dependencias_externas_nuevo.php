<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	----------------------------------
include("fphp_pf.php");
connect();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_pf.js"></script>
<script type="text/javascript" language="javascript" src="../js/funciones.js"></script>
<script type="text/javascript" language="javascript" src="fscript.js"></script>
</head>

<body onload="document.getElementById('descripcion').focus();">
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro de Dependencias Externas | Nuevo Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="document.getElementById('frmentrada').submit();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="pf_dependencias_externas.php" method="POST" onsubmit="return verificarDependenciaExterna(this, 'INSERTAR');">
<div style="width:700px" class="divFormCaption">Datos de la Dependencia</div>
<table width="700" class="tblForm">
  <tr>
    <td class="tagForm" width="150">Dependencia:</td>
    <td><input type="text" id="codigo" size="6" maxlength="4" disabled="disabled" /></td>
  </tr>
  <tr>
    <td class="tagForm">Descripci&oacute;n:</td>
    <td><input type="text" id="descripcion" size="75" maxlength="100" />*</td>
  </tr>
  <tr>
    <td class="tagForm">Organismo:</td>
    <td>
    	<select id="codorganismo" style="width:300px;">
    		<option value="">:::. Seleccione .:::</option>
            <?=loadSelect("pf_organismosexternos", "CodOrganismo", "Organismo", "", 0);?>
        </select>
	</td>
  </tr>
  <tr>
    <td class="tagForm">Representante:</td>
    <td><input type="text" id="representante" size="75" maxlength="100" /></td>
  </tr>
  <tr>
    <td class="tagForm">Cargo:</td>
    <td><input type="text" id="cargo" size="75" maxlength="100" value="<?=htmlentities($field['Cargo'])?>" /></td>
  </tr>
  <tr>
    <td class="tagForm">Telefono:</td>
    <td>
    	<input type="text" id="tel1" size="15" maxlength="15" /> - 
    	<input type="text" id="tel2" size="15" maxlength="15" />
	</td>
  </tr>
  <tr>
    <td class="tagForm">Estado:</td>
    <td>
			<input name="status" id="activo" type="radio" value="A" checked /> Activo
			<input name="status" id="inactivo" type="radio" value="I" /> Inactivo
	  </td>
  </tr>
  <tr>
    <td class="tagForm">&Uacute;ltima Modif.:</td>
    <td colspan="2">
		<input name="ult_usuario" type="text" id="ult_usuario" size="30" disabled="disabled" />
		<input name="ult_fecha" type="text" id="ult_fecha" size="25" disabled="disabled" />	  
	</td>
  </tr>
</table>
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onclick="this.form.submit();" />
</center><br />
</form>
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>

</body>
</html>
