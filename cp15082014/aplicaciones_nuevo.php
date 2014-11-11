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

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro de Aplicaciones | Nuevo Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'aplicaciones.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="aplicaciones.php" method="POST" onsubmit="return verificarAplicacion(this, 'GUARDAR');">
<?php echo "<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />"; ?>

<div style="width:700px" class="divFormCaption">Datos de la Aplicaci&oacute;n</div>
<table width="700" class="tblForm">
  <tr>
    <td class="tagForm">Aplicaci&oacute;n:</td>
    <td><input name="codigo" type="text" id="codigo" size="20" maxlength="10" />*</td>
  </tr>
  <tr>
    <td class="tagForm">Descripci&oacute;n:</td>
    <td><input name="descripcion" type="text" id="descripcion" size="60" maxlength="30" />*</td>
  </tr>
  <tr>
    <td class="tagForm">Per&iacute;odo Contable:</td>
    <td><input name="periodo" type="text" id="periodo" size="15" maxlength="7" /><i>(aaaa-mm)</i></td>
  </tr>
  <tr>
    <td class="tagForm">Prefijo Voucher:</td>
    <td><input name="voucher" type="text" id="voucher" size="20" maxlength="10" /></td>
  </tr>
  <tr>
    <td class="tagForm">Estado:</td>
    <td>
			<input name="status" type="radio" value="A" checked /> Activo
			<input name="status" type="radio" value="I" /> Inactivo
		</td>
  </tr>
  <tr>
	  <td class="tagForm">&Uacute;ltima Modif.:</td>
	  <td>
			<input name="ult_usuario" type="text" id="ult_usuario" size="30" readonly />
			<input name="ult_fecha" type="text" id="ult_fecha" size="25" readonly />
		</td>
	</tr>
</table>
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'aplicaciones.php');" />
</center><br />
</form>

<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
