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
		<td class="titulo">Maestro de Miscel&aacute;neos | Nuevo Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'miscelaneos.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="miscelaneos.php" method="POST" onsubmit="return verificarMiscelaneo(this, 'GUARDAR');">
<?php
include("fphp.php");
connect();
echo "<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />";
?>

<div style="width:700px" class="divFormCaption">Datos del Miscel&aacute;neos</div>
<table width="700" class="tblForm">
  <tr>
    <td class="tagForm">Aplicaci&oacute;n:</td>
    <td>
			<select name="aplicacion" id="aplicacion" class="selectMed">
				<option value="" selected></option>
				<?php getAplicaciones("", 0); ?>
			</select>*
		</td>
  </tr>
  <tr>
    <td class="tagForm">Maestro:</td>
    <td><input name="codigo" type="text" id="codigo" size="20" maxlength="10" />*</td>
  </tr>
  <tr>
    <td class="tagForm">Descripci&oacute;n:</td>
    <td><input name="descripcion" type="text" id="descripcion" size="75" maxlength="60" />*</td>
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
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onclick="cargarPagina(this.form, 'miscelaneos.php');" />
</center>
</form>

<br><div class="divDivision">Elementos del Miscel&aacute;neo</div><br>

<form name="frmelementos">
<table width="700" class="tblForm">
	<tr>
    <td class="tagForm">Elemento:</td>
    <td><input name="elemento" type="text" id="elemento" size="2" maxlength="2" readonly />*</td>
  </tr>
  <tr>
    <td class="tagForm">Detalle:</td>
    <td><input name="detalle" type="text" id="detalle" size="60" maxlength="60" readonly />*</td>
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
<input type="submit" value="Guardar Elemento" disabled />
<input type="reset" value="Resetear" disabled />
</center>
</form>

<form name="frmtabla">
<table width="700" class="tblBotones">
 <tr>
	<td align="right">
		<input name="btEditar" type="button" class="btLista" id="btEditar" value="Editar" disabled />
		<input name="btBorrar" type="button" class="btLista" id="btBorrar" value="Borrar" disabled />
	</td>
 </tr>
</table>

<table width="700" class="tblLista">
  <tr class="trListaHead">
    <th width="75" scope="col">Elemento</th>
    <th width="625" scope="col">Detalle</th>
  </tr>
</table>
</form>
</body>
</html>
