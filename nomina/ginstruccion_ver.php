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
		<td class="titulo">Maestro de Grado de Instrucci&oacute;n | Ver Registro</td>
		<td align="right"><a class="cerrar" href="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
include("fphp.php");
connect();
if ($_POST['registro']=="") $_POST['registro']=$_GET['registro'];
if ($_POST['filtro']=="")  $_POST['filtro']=$_GET['filtro'];
$sql="SELECT * FROM rh_gradoinstruccion WHERE (CodGradoInstruccion='".$_GET['registro']."')";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) {
	$field=mysql_fetch_array($query);
	echo "
	<div style='width:700px' class='divFormCaption'>Datos del Grado de Instrucci&oacute;n</div>
	<table width='700' class='tblForm'>
	  <tr>
	    <td class='tagForm'>Grado:</td>
	    <td><input name='codigo' type='text' id='codigo' size='6' maxlength='3' value='".$field['CodGradoInstruccion']."' readonly /></td>
	  </tr>
	  <tr>
	    <td class='tagForm'>Descripci&oacute;n:</td>
	    <td><input name='descripcion' type='text' id='descripcion' size='75' maxlength='255' value='".$field['Descripcion']."' readonly /></td>
	  </tr>
		<tr>
	    <td class='tagForm'>Estado:</td>
	    <td>";
				if ($field['Estado']=="A") echo "<input name='status' type='radio' value='A' checked disabled /> Activo"; 
				else echo "<input name='status' type='radio' value='A' disabled /> Activo";
				if ($field['Estado']=="I") echo "<input name='status' type='radio' value='I' checked disabled /> Inactivo"; 
				else echo "<input name='status' type='radio' value='I' disabled /> Inactivo";
			echo "
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
	
	<br><div class='divDivision'>Niveles del Grado de Instrucci&oacute;n</div><br>";
	
	//	CONSULTO LA TABLA
	$sql="SELECT * FROM rh_nivelgradoinstruccion WHERE (CodGradoInstruccion='".$_GET['registro']."') ORDER BY Nivel";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	//	MUESTRO LA TABLA
	echo "	
	<table width='700' class='tblLista'>
	  <tr class='trListaHead'>
	    <th width='75' scope='col'>Nivel</th>
	    <th width='500' scope='col'>Detalle</th>
	  </tr>";
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		echo "
		<tr class='trListaBody'>
			<td align='center'>".$field['Nivel']."</td>
			<td>".$field['Descripcion']."</td>
		</tr>";
	}
	echo "</table>";
}
?>

</body>
</html>