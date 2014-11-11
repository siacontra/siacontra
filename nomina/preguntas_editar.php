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
$sql="SELECT Pregunta, Descripcion, Area, ValorMinimo, ValorMaximo, Estado, UltimoUsuario, UltimaFecha FROM rh_encuesta_preguntas WHERE (Pregunta='".$registro."')";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) $field=mysql_fetch_array($query);
if ($field['Estado']=="A") $activo="checked"; else $inactivo="checked";
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Preguntas de Clima Laboral | Actualizaci&oacute;n</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'preguntas.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="preguntas.php" method="POST" onsubmit="return verificarPregunta(this, 'ACTUALIZAR');">
<?php echo "<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />"; ?>

<div style="width:700px" class="divFormCaption">Datos de la Pregunta</div>
<table width="700" class="tblForm">
  <tr>
    <td class="tagForm">Pregunta:</td>
    <td colspan="3"><input name="codigo" type="text" id="codigo" size="8" value="<?=$field['Pregunta']?>" readonly /></td>
  </tr>
  <tr>
    <td class="tagForm">Descripci&oacute;n:</td>
    <td colspan="3"><input name="descripcion" type="text" id="descripcion" size="100" maxlength="250" value="<?=$field['Descripcion']?>" />*</td>
  </tr>
  <tr>
    <td class="tagForm">Area:</td>
    <td colspan="3">
		<select name="area" id="area">
			<option value=""></option>
			<?php getMiscelaneos($field['Area'], "AREACLIMA", 0); ?>
		</select>*
	</td>
  </tr>
  <tr>
    <td class="tagForm">Valor M&iacute;nimo:</td>
    <td width="100"><input name="minimo" type="text" id="minimo" size="10" maxlength="4" value="<?=$field['ValorMinimo']?>" />*</td>
    <td class="tagForm">Valor M&aacute;ximo:</td>
    <td><input name="maximo" type="text" id="maximo" size="10" maxlength="4" value="<?=$field['ValorMaximo']?>" />*</td>
  </tr>
  <tr>
    <td class="tagForm">Estado:</td>
    <td colspan="3">
		<input name="status" type="radio" value="A" <?=$activo?> /> Activo
		<input name="status" type="radio" value="I" <?=$inactivo?> /> Inactivo
	</td>
  </tr>
  <tr>
	  <td class="tagForm">&Uacute;ltima Modif.:</td>
	  <td colspan="3">
			<input name="ult_usuario" type="text" id="ult_usuario" size="30" value="<?=$field['UltimoUsuario']?>" readonly />
			<input name="ult_fecha" type="text" id="ult_fecha" size="25" value="<?=$field['UltimaFecha']?>" readonly />
		</td>
	</tr>
</table>
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onClick="cargarPagina(this.form, 'preguntas.php');" />
</center><br />
</form>

<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
