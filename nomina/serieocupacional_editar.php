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
		<td class="titulo">Maestro de Serie Ocupacional | Actualizaci&oacute;n</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'serieocupacional.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="serieocupacional.php" method="POST" onsubmit="return verificarSerieOcupacional(this, 'ACTUALIZAR');">
<?php
include("fphp.php");
connect();
$sql="SELECT * FROM rh_serieocupacional WHERE CodSerieOcup='".$_POST['registro']."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) {
	$field=mysql_fetch_array($query);
	echo "
	<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />
	<div style='width:700px' class='divFormCaption'>Datos del Grupo Ocupacional</div>
	<table width='700' class='tblForm'>
	  <tr>
	    <td class='tagForm'>Serie:</td>
	    <td><input name='codigo' type='text' id='codigo' size='5' maxlength='5' value='".$field['CodSerieOcup']."' readonly /></td>
	  </tr>
	  <tr>
	    <td class='tagForm'>Descripci&oacute;n:</td>
	    <td><input name='descripcion' type='text' id='descripcion' size='60' maxlength='100' value='".$field['SerieOcup']."' />*</td>
	  </tr>
		<tr>
	    <td class='tagForm'>Grupo Ocupacional:</td>
	    <td>
				<select name='grupo' id='grupo' class='selectBig'>
					<option value=''>";
					getGrupos($field['CodGrupOcup'], 0);
				echo "
				</select>*
			</td>
	  </tr>
	  <tr>
	    <td class='tagForm'>&Uacute;ltima Modif.:</td>
	    <td>
				<input name='ult_usuario' type='text' id='ult_usuario' size='30' value='".$field['UltimoUsuario']."' readonly />
				<input name='ult_fecha' type='text' id='ult_fecha' size='25' value='".$field['UltimaFecha']."' readonly />
			</td>
	  </tr>
	</table>
	<center> 
	<input type='submit' value='Guardar Registro' />
	<input name='bt_cancelar' type='button' id='bt_cancelar' value='Cancelar' onclick='cargarPagina(this.form, \"serieocupacional.php\");' />
	</center><br />";
}
?>

</form>
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>