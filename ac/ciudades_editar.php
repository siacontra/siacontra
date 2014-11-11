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
		<td class="titulo">Maestro de Ciudades | Actualizaci&oacute;n</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'ciudades.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="ciudades.php" method="POST" onsubmit="return verificarCiudad(this, 'ACTUALIZAR');">
<?php
include("fphp.php");
connect();
$sql="SELECT mastciudades.*, mastmunicipios.CodEstado, mastestados.CodPais FROM mastciudades, mastmunicipios, mastestados WHERE mastciudades.CodCiudad='".$_POST['registro']."' AND (mastciudades.CodMunicipio=mastmunicipios.CodMunicipio AND mastmunicipios.CodEstado=mastestados.CodEstado)";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) {
	$field=mysql_fetch_array($query);
	echo "
	<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />
	<div style='width:700px' class='divFormCaption'>Datos de la Ciudad</div>
	<table width='700' class='tblForm'>
	  <tr>
	    <td class='tagForm'>Ciudad:</td>
	    <td><input name='codigo' type='text' id='codigo' size='6' maxlength='4' value='".$field['CodCiudad']."' readonly /></td>
	  </tr>
	  <tr>
	    <td class='tagForm'>Descripci&oacute;n:</td>
	    <td><input name='descripcion' type='text' id='descripcion' size='50' maxlength='100' value='".htmlentities($field['Ciudad'])."' />*</td>
	  </tr>
		<tr>
	    <td class='tagForm'>Pais:</td>
	    <td>
				<select name='pais' id='pais' class='selectMed' onchange='getOptions_3(this.id, \"estado\", \"municipio\")'>
					<option value=''>";
					getPaises($field['CodPais'], 0);
				echo "
				</select>*
			</td>
	  </tr>
		<tr>
	    <td class='tagForm'>Estado:</td>
	    <td>
				<select name='estado' id='estado' class='selectMed' onchange='getOptions_2(this.id, \"municipio\")'>
					<option value=''>";
					getEstados($field['CodEstado'], $field['CodPais'], 0);
				echo "
				</select>*
			</td>
	  </tr>
		<tr>
	    <td class='tagForm'>Municipio:</td>
	    <td>
				<select name='municipio' id='municipio' class='selectMed'>
					<option value=''>";
					getMunicipios($field['CodMunicipio'], $field['CodEstado'], 0);
				echo "
				</select>*
			</td>
	  </tr>
	  <tr>
	    <td class='tagForm'>Cod. Postal:</td>
	    <td><input name='postal' type='text' id='postal' size='10' maxlength='10' value='".$field['CodPostal']."' /></td>
	  </tr>
	  <tr class='tr4'>
	    <td class='tagForm'>&Uacute;ltima Modif.:</td>
	    <td>
				<input name='ult_usuario' type='text' id='ult_usuario' size='30' value='".$field['UltimoUsuario']."' readonly />
				<input name='ult_fecha' type='text' id='ult_fecha' size='25' value='".$field['UltimaFecha']."' readonly />
			</td>
	  </tr>
	</table>
	<center> 
	<input type='submit' value='Guardar Registro' />
	<input name='bt_cancelar' type='button' id='bt_cancelar' value='Cancelar' onclick='cargarPagina(this.form, \"ciudades.php\");' />
	</center><br />";
}
?>

</form>
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>