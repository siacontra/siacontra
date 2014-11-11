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
		<td class="titulo">Maestro de Centros de Estudio | Actualizaci&oacute;n</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'centroestudio.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="centroestudio.php" method="POST" onsubmit="return verificarCEstudio(this, 'ACTUALIZAR');">
	
<?php
include("fphp.php");
connect();
$sql="SELECT * FROM rh_centrosestudios WHERE CodCentroEstudio='".$_POST['registro']."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) {
	$field=mysql_fetch_array($query);
	if ($field['FlagEstudio'] == "S") $cestudio = "checked";
	if ($field['FlagCurso'] == "S") $ccurso= "checked";
	echo "
	<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />
	<div style='width:700px' class='divFormCaption'>Datos del Centro de Estudio</div>
	<table width='700' class='tblForm'>
	  <tr>
	    <td class='tagForm'>Pais:</td>
	    <td><input name='codigo' type='text' id='codigo' size='6' maxlength='3' value='".$field['CodCentroEstudio']."' readonly /></td>
	  </tr>
	  <tr>
	    <td class='tagForm'>Descripci&oacute;n:</td>
	    <td><input name='descripcion' type='text' id='descripcion' size='75' maxlength='70' value='".$field['Descripcion']."' />*</td>
	  </tr>
	  <tr>
	    <td class='tagForm'>Ubicaci&oacute;n:</td>
	    <td><input name='ubicacion' type='text' id='ubicacion' size='75' maxlength='70' value='".$field['Ubicacion']."' /></td>
	  </tr>
	  <tr>
		<td class='tagForm'>Tipo de Centro:</td>
		<td>
			<input type='checkbox' name='tipo' id='estudio' value='S' $cestudio /> Centro de Estudios &nbsp; &nbsp; &nbsp;
			<input type='checkbox' name='tipo' id='curso' value='S' $ccurso /> Cursos
		</td>
	  </tr>
	  <tr>
	    <td class='tagForm'>&Uacute;ltima Modif.:</td>
	    <td>
				<input name='ult_usuario' type='text' id='ult_usuario' size='30' value='".$field['UltimoUsuario']."' disabled />
				<input name='ult_fecha' type='text' id='ult_fecha' size='25' value='".$field['UltimaFecha']."' disabled />
			</td>
	  </tr>
	</table>
	<center> 
	<input type='submit' value='Guardar Registro' />
	<input name='bt_cancelar' type='button' id='bt_cancelar' value='Cancelar' onclick='cargarPagina(this.form, \"centroestudio.php\");' />
	</center><br />";
}
?>

</form>

<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
