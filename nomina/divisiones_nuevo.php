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
		<td class="titulo">Maestro de Divisiones | Nuevo Registro</td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" action="divisiones.php" method="POST" onsubmit="return verificarDivision(this, 'GUARDAR');">
<?php
include("fphp.php");
connect();
echo "<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />";
?>

<div style="width:700px" class="divFormCaption">Datos de la Divisi&oacute;n</div>
<table width="700" class="tblForm">
  <tr>
    <td class="tagForm">Divisi&oacute;n:</td>
    <td><input name="codigo" type="text" id="codigo" size="6" maxlength="4" readonly /></td>
  </tr>
  <tr>
    <td class="tagForm">Descripci&oacute;n:</td>
    <td><input name="descripcion" type="text" id="descripcion" size="75" maxlength="100" />*</td>
  </tr>
	<tr>
    <td class="tagForm">Organismo:</td>
    <td>
			<select name="organismo" id="organismo" class="selectBig" onchange="getOptions_2(this.id, 'dependencia')">
				<option value=""></option>
				<?php getOrganismos(0, 0); ?>
			</select>*
		</td>
  </tr>
	<tr>
    <td class="tagForm">Dependencia:</td>
    <td>
			<select name="dependencia" id="dependencia" class="selectBig" disabled>
				<option value=""></option>
			</select>*
		</td>
  </tr>
  <tr>
    <td class="tagForm">Extenci&oacute;n:</td>
    <td>
			<input name="ext" type="text" id="ext" size="6" maxlength="4" />
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
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onclick="cargarPagina(this.form, 'divisiones.php');" />
</center><br />
</form>
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
