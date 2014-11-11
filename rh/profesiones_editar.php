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
		<td class="titulo">Maestro de Profesiones | Actualizaci&oacute;n</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'profesiones.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="profesiones.php" method="POST" onsubmit="return verificarProfesion(this, 'ACTUALIZAR');">
<?php
include("fphp.php");
connect();
$sql="SELECT * FROM rh_profesiones WHERE CodProfesion='".$_POST['registro']."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) {
	$field=mysql_fetch_array($query);
	if ($field['Estado']=="A") $activo="checked"; else $inactivo="checked";
}
echo "<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />";
?>

<div style="width:700px" class="divFormCaption">Datos de la Profesi&oacute;n</div>
<table width="700" class="tblForm">
  <tr>
    <td class="tagForm">Profesi&oacute;n:</td>
    <td><input name="codigo" type="text" id="codigo" size="8" maxlength="6" value="<?php echo $field['CodProfesion'] ?>" readonly /></td>
  </tr>
  <tr>
    <td class="tagForm">Descripci&oacute;n:</td>
    <td><input name="descripcion" type="text" id="descripcion" size="75" maxlength="200" value="<?php echo $field['Descripcion'] ?>" />*</td>
  </tr>
	<tr>
		<td class="tagForm">Grado de Instrucci&oacute;n:</td>
		<td>
			<select name="grado" id="grado" class="selectMed">
				<option value=""></option>
				<?php getGInstruccion($field['CodGradoInstruccion'], 0); ?>
			</select>*
		</td>
  </tr>
	<tr>
		<td class="tagForm">Area:</td>
		<td>
			<select name="area" id="area" class="selectMed">
				<option value=""></option>
				<?php getMiscelaneos($field['Area'], "AREA", 0); ?>
			</select>*
		</td>
  </tr>
  <tr>
    <td class="tagForm">Estado:</td>
    <td>
			<input name="status" type="radio" value="A" <?php echo $activo ?> /> Activo
			<input name="status" type="radio" value="I" <?php echo $inactivo ?> /> Inactivo
		</td>
  </tr>
  <tr>
    <td class="tagForm">&Uacute;ltima Modif.:</td>
    <td>
			<input name="ult_usuario" type="text" id="ult_usuario" size="30" value="<?php echo $field['UltimoUsuario'] ?>" readonly />
			<input name="ult_fecha" type="text" id="ult_fecha" size="25" value="<?php echo $field['UltimaFecha'] ?>" readonly />
		</td>
  </tr>
</table>
<center> 
<input type="submit" value="Guardar Registro" />
<input name="bt_cancelar" type="button" id="bt_cancelar" value="Cancelar" onclick="cargarPagina(this.form, 'profesiones.php');" />
</center><br />
</form>

<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>

