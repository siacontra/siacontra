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
		<td class="titulo">Maestro de Tipos de Cargo | Actualizaci&oacute;n</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'tiposcargo.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
include("fphp.php");
connect();
if ($_POST['registro']=="") $_POST['registro']=$_GET['registro'];
if ($_POST['filtro']=="")  $_POST['filtro']=$_GET['filtro'];
$sql="SELECT * FROM rh_tipocargo WHERE CodTipoCargo='".$_POST['registro']."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) {
	$field=mysql_fetch_array($query);
	echo "
	<form id='frmentrada' name='frmentrada' action='tiposcargo.php' method='POST' onsubmit='return verificarTipoCargo(this, \"ACTUALIZAR\");'>
	<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />
	<div style='width:900px' class='divFormCaption'>Datos del Tipo de Cargo</div>
	<table width='900' class='tblForm'>
	  <tr>
	    <td class='tagForm'>Tipo de Cargo:</td>
	    <td><input name='codigo' type='text' id='codigo' size='3' maxlength='3' value='".$field['CodTipoCargo']."' readonly /></td>
	  </tr>
	  <tr>
	    <td class='tagForm'>Descripci&oacute;n:</td>
	    <td><input name='descripcion' type='text' id='descripcion' size='60' maxlength='30' value='".$field['TipCargo']."' />*</td>
	  </tr>	
	  <tr>
	    <td class='tagForm'>Definici&oacute;n:</td>
	    <td><textarea name='definicion' id='definicion' cols='125' rows='3'>".$field['Definicion']."</textarea></td>
	  </tr>
	  <tr>
	    <td class='tagForm'>Funci&oacute;n:</td>
	    <td><textarea name='funcion' id='funcion' cols='125' rows='3'>".$field['Funcion']."</textarea></td>
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
	<input name='bt_cancelar' type='button' id='bt_cancelar' value='Cancelar' onclick='cargarPagina(this.form, \"tiposcargo.php\");' />
	</center>
	</form>";
}
?>
</body>
</html>