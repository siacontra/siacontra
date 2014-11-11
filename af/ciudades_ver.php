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
		<td class="titulo">Maestro de Ciudades | Ver Registro</td>
		<td align="right"><a class="cerrar" href="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
include("fphp.php");
connect();
$sql="SELECT mastciudades.*, mastmunicipios.CodEstado, mastestados.CodPais FROM mastciudades, mastmunicipios, mastestados WHERE mastciudades.CodCiudad='".$_GET['registro']."' AND (mastciudades.CodMunicipio=mastmunicipios.CodMunicipio AND mastmunicipios.CodEstado=mastestados.CodEstado)";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) {
	$field=mysql_fetch_array($query);
	echo "
	<div style='width:700px' class='divFormCaption'>Datos de la Ciudad</div>
	<table width='700' class='tblForm'>
	  <tr>
	    <td class='tagForm'>Ciudad:</td>
	    <td><input name='codigo' type='text' id='codigo' size='6' maxlength='4' value='".$field['CodCiudad']."' readonly /></td>
	  </tr>
	  <tr>
	    <td class='tagForm'>Descripci&oacute;n:</td>
	    <td><input name='descripcion' type='text' id='descripcion' size='50' maxlength='100' value='".htmlentities($field['Ciudad'])."' readonly /></td>
	  </tr>
		<tr>
	    <td class='tagForm'>Pais:</td>
	    <td>
				<select name='pais' id='pais' class='selectMed'>";
					getPaises($field['CodPais'], 1);
				echo "
				</select>
			</td>
	  </tr>
		<tr>
	    <td class='tagForm'>Estado:</td>
	    <td>
				<select name='estado' id='estado' class='selectMed'>";
					getEstados($field['CodEstado'], $field['CodPais'], 1);
				echo "
				</select>
			</td>
	  </tr>
		<tr>
	    <td class='tagForm'>Municipio:</td>
	    <td>
				<select name='municipio' id='municipio' class='selectMed'>";
					getMunicipios($field['CodMunicipio'], $field['CodEstado'], 1);
				echo "
				</select>
			</td>
	  </tr>
	  <tr>
	    <td class='tagForm'>Cod. Postal:</td>
	    <td><input name='postal' type='text' id='postal' size='10' maxlength='10' value='".$field['CodPostal']."' readonly /></td>
	  </tr>
	  <tr>
	    <td class='tagForm'>&Uacute;ltima Modif.:</td>
	    <td>
				<input name='ult_usuario' type='text' id='ult_usuario' size='30' value='".$field['UltimoUsuario']."' readonly />
				<input name='ult_fecha' type='text' id='ult_fecha' size='25' value='".$field['UltimaFecha']."' readonly />
			</td>
	  </tr>
	</table>";
}
?>
</body>
</html>