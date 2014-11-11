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
$registro=$registro;
echo "
<form name='frmentrada' id='frmentrada' action='patrimonio_inmueble.php' method='POST' onSubmit='return verificarPatrimonioInmueble(this, \"".$registro."\");'>
<table width='950' class='tblForm'>
  <tr>
      <td>Descripci&oacute;n:</td>
	  <td>Ubicaci&oacute;n:</td>
	  <td>Uso:</td>
	  <td>Valor:</td>
	  <td>Hipotecado:</td>
	</tr>
	<tr>
	  <td><input type='text' name='descripcion' id='descripcion' size='45' maxlength='255' /></td>
	  <td><input type='text' name='ubicacion' id='ubicacion' size='75' maxlength='255' /></td>
	  <td><input type='text' name='uso' id='uso' size='45' maxlength='255' /></td>
	  <td><input type='text' name='valor' id='valor' maxlength='255' /></td>
	  <td align='center'><input type='checkbox' name='fhipoteca' id='fhipoteca' value='S' /></td>
	</tr>
</table>

<table width='950' class='tblBotones'>
	<tr>
		<td align='right'>
			<input name='btNuevo' type='submit' id='btNuevo' value='Agregar' />
			<input name='btBorrar' type='button' id='btBorrar' value='Eliminar' onClick='eliminarPatrimonio(this.form, \"patrimonio_inmueble.php?accion=ELIMINAR&registro=".$registro."\", \"".$registro."\");' />
		</td>
	</tr>
</table>";

//	ELIMINO EL REGISTRO
if ($_GET['accion']=="ELIMINAR") {
	$sql="DELETE FROM rh_patrimonio_inmueble WHERE Secuencia='".$det."' AND CodPersona='".$registro."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
}
//	CONSULTO LA TABLA
$sql="SELECT Secuencia, Descripcion, Ubicacion, Uso, Valor, FlagHipotecado FROM rh_patrimonio_inmueble WHERE CodPersona='".$registro."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
//	MUESTRO LA TABLA
echo "
<input type='hidden' name='det' id='det' />
<table width='950' class='tblLista'>
  <tr class='trListaHead'>
		<th width='210' scope='col'>Descripci&oacute;n</th>
		<th width='330' scope='col'>Ubicaci&oacute;n</th>
		<th width='210' scope='col'>Uso</th>
		<th width='110' scope='col'>Valor</th>
		<th scope='col'>Hipotecado</th>
	</tr>";
for ($i=0; $i<$rows; $i++) {
	$field=mysql_fetch_array($query);
	$valor=number_format($field['Valor'], 2, ',', '.');
	if ($field['FlagHipotecado']=="S") $checked="checked"; else $checked="";
	//----------
	echo "
	<tr class='trListaBody' onclick='mClk(this, \"det\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['Secuencia']."'>
		<td>".$field['Descripcion']."</td>
		<td>".$field['Ubicacion']."</td>
		<td>".$field['Uso']."</td>
		<td align='right'>".$valor."</td>
		<td align='center'><input type='checkbox' disabled $checked /></td>
	</tr>";
}
echo "
</table>
<script type='text/javascript' language='javascript'>
	totalPuestos($rows);
</script>
</form>";

if ($_GET['accion']=="ELIMINAR") {
	echo "
	<script type='text/javascript' language='javascript'>
		actualizarPatrimonio('', '', '".$registro."');
	</script>";
}
?>
</body>
</html>