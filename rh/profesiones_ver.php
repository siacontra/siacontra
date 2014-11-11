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
		<td class="titulo">Maestro de Profesiones | Ver Registro</td>
		<td align="right"><a class="cerrar" href="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
include("fphp.php");
connect();
$sql="SELECT * FROM rh_profesiones WHERE CodProfesion='".$_GET['registro']."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) {
	$field=mysql_fetch_array($query);
	if ($field['Estado']=="A") $activo="checked"; else $inactivo="checked";
}
?>

<div style="width:700px" class="divFormCaption">Datos de la Profesi&oacute;n</div>
<table width="700" class="tblForm">
  <tr>
    <td class="tagForm">Profesi&oacute;n:</td>
    <td><input name="codigo" type="text" id="codigo" size="8" maxlength="6" value="<?php echo $field['CodProfesion'] ?>" readonly /></td>
  </tr>
  <tr>
    <td class="tagForm">Descripci&oacute;n:</td>
    <td><input name="descripcion" type="text" id="descripcion" size="75" maxlength="200" readonly value="<?php echo $field['Descripcion'] ?>" /></td>
  </tr>
	<tr>
		<td class="tagForm">Grado de Instrucci&oacute;n:</td>
		<td>
			<select name="grado" id="grado" class="selectMed">
				<?php getGInstruccion($field['CodGradoInstruccion'], 1); ?>
			</select>
		</td>
  </tr>
	<tr>
		<td class="tagForm">Area:</td>
		<td>
			<select name="area" id="area" class="selectMed">
				<?php getMiscelaneos($field['Area'], "AREA", 1); ?>
			</select>
		</td>
  </tr>
  <tr>
    <td class="tagForm">Estado:</td>
    <td>
			<input name="status" type="radio" value="A" <?php echo $activo ?> disabled /> Activo
			<input name="status" type="radio" value="I" <?php echo $inactivo ?> disabled /> Inactivo
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
</body>
</html>

