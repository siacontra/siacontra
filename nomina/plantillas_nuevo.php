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
<?php
include("fphp.php");
connect();
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Plantilla de Preguntas de Clima Laboral | Nuevo Registro</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'plantillas.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="preguntas.php" method="POST" onsubmit="return verificarPlantilla(this, 'GUARDAR');">
<?php echo "<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />"; ?>

<div style="width:700px" class="divFormCaption">Datos de la Plantilla</div>
<table width="700" class="tblForm">
  <tr>
    <td class="tagForm">Plantilla:</td>
    <td colspan="3"><input name="codigo" type="text" id="codigo" size="8" disabled /></td>
  </tr>
  <tr>
    <td class="tagForm">Descripci&oacute;n:</td>
    <td colspan="3"><input name="descripcion" type="text" id="descripcion" size="100" maxlength="250" />*</td>
  </tr>
  <tr>
    <td class="tagForm">Estado:</td>
    <td colspan="3">
		<input name="status" type="radio" value="A" checked /> Activo
		<input name="status" type="radio" value="I" /> Inactivo
	</td>
  </tr>
  <tr>
	  <td class="tagForm">&Uacute;ltima Modif.:</td>
	  <td colspan="3">
			<input name="ult_usuario" type="text" id="ult_usuario" size="30" readonly />
			<input name="ult_fecha" type="text" id="ult_fecha" size="25" readonly />
		</td>
	</tr>
</table>
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'plantillas.php');" />
</center><br />

<div style="width:700px" class="divMsj">Campos Obligatorios *</div>


<br /><div class='divDivision'>Preguntas de la Plantilla</div><br />
<input type="hidden" name="elemento" id="elemento" />
<table width="700" class="tblBotones">
	<tr>
		<td align="right">
			<input name="btNuevo" type="submit" id="btNuevo" value="Agregar" disabled="disabled" />
			<input name="btBorrar" type="button" id="btBorrar" value="Eliminar" disabled="disabled" />
		</td>
	</tr>
</table>
<table width="700" class="tblLista">
  <tr class="trListaHead">
		<th width="75" scope="col">Pregunta</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="75" scope="col">Estado</th>
  </tr>
</table>

</form>
</body>
</html>
