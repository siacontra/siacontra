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
		<td class="titulo">Maestro de Formatos de Contrato | Actualizaci&oacute;n </td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'formatoscontrato.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="formatoscontrato.php" method="POST" onsubmit="return verificarFormContrato(this, 'ACTUALIZAR');">

<?php
include("fphp.php");
connect();
$sql="SELECT * FROM rh_formatocontrato WHERE CodFormato='".$_POST['registro']."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) {
	$field=mysql_fetch_array($query);
	echo "
	<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />
	<div style='width:700px' class='divFormCaption'>Datos del Formato</div>
	<table width='700' class='tblForm'>
	  <tr>
	    <td class='tagForm'>Formato:</td>
	    <td><input name='codigo' type='text' id='codigo' size='2' maxlength='2' value='".$field['CodFormato']."' readonly /></td>
	  </tr>
	  <tr>
	    <td class='tagForm'>Documento:</td>
	    <td><input name='descripcion' type='text' id='descripcion' size='30' maxlength='30' value='".$field['Documento']."' />*</td>
	  </tr>
		<tr>
	    <td class='tagForm'>Tipo de Contrato:</td>
	    <td>
				<select name='tipocontrato' id='tipocontrato' class='selectMed'>
					<option value=''>";
					getTContratos($field['TipoContrato'], 0);
				echo "
				</select>*
			</td>
	  </tr>
		<tr>
	    <td class='tagForm'>Ruta de Plantilla:</td>
	    <td><input name='ruta' type='text' id='ruta' size='75' value='".$field['RutaPlant']."' />*</td>
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
	<input name='bt_cancelar' type='button' id='bt_cancelar' value='Cancelar' onclick='cargarPagina(this.form, \"formatoscontrato.php\");' />
	</center><br />";
}
?>

</form>

<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
