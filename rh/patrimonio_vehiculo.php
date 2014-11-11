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
<form name='frmentrada' id='frmentrada' action='patrimonio_vehiculo.php' method='POST' onSubmit='return verificarPatrimonioVehiculo(this, \"".$registro."\");'>
<table width='950' class='tblForm'>
  <tr>
      <td>Marca:</td>
	  <td>Modelo:</td>
	  <td>A&ntilde;o:</td>
	  <td>Color:</td>
	  <td>Placa:</td>
	  <td>Valor:</td>
	  <td>Prendado:</td>
	</tr>
	<tr>
	  <td><input type='text' name='marca' id='marca' size='60' maxlength='50' /></td>
	  <td><input type='text' name='modelo' id='modelo' size='60' maxlength='50' /></td>
	  <td><input type='text' name='anio' id='anio' size='8' maxlength='4' /></td>
	  <td><input type='text' name='color' id='color' size='15' maxlength='20' /></td>
	  <td><input type='text' name='placa' id='placa' size='10' maxlength='15' /></td>
	  <td><input type='text' name='valor' id='valor' /></td>
	  <td align='center'><input type='checkbox' name='fprendado' id='fprendado' value='S' /></td>
	</tr>
</table>

<table width='950' class='tblBotones'>
	<tr>
		<td align='right'>
			<input name='btNuevo' type='submit' id='btNuevo' value='Agregar' />
			<input name='btBorrar' type='button' id='btBorrar' value='Eliminar' onClick='eliminarPatrimonio(this.form, \"patrimonio_vehiculo.php?accion=ELIMINAR&registro=".$registro."\", \"".$registro."\");' />
		</td>
	</tr>
</table>";

//	ELIMINO EL REGISTRO
if ($_GET['accion']=="ELIMINAR") {
	$sql="DELETE FROM rh_patrimonio_vehiculo WHERE Secuencia='".$det."' AND CodPersona='".$registro."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
}
//	CONSULTO LA TABLA
$sql="SELECT Secuencia, Marca, Modelo, Anio, Color, Placa, Valor, FlagPrendado FROM rh_patrimonio_vehiculo WHERE CodPersona='".$registro."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
//	MUESTRO LA TABLA
echo "
<input type='hidden' name='det' id='det' />
<table width='950' class='tblLista'>
  <tr class='trListaHead'>
		<th width='265' scope='col'>Marca</th>
		<th width='275' scope='col'>Modelo</th>
		<th width='60' scope='col'>A&ntilde;o</th>
		<th width='90' scope='col'>Color</th>
		<th width='60' scope='col'>Placa</th>
		<th width='110' scope='col'>Valor</th>
		<th scope='col'>Prendado</th>
	</tr>";
for ($i=0; $i<$rows; $i++) {
	$field=mysql_fetch_array($query);
	$valor=number_format($field['Valor'], 2, ',', '.');
	if ($field['FlagPrendado']=="S") $checked="checked"; else $checked="";
	//----------
	echo "
	<tr class='trListaBody' onclick='mClk(this, \"det\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['Secuencia']."'>
		<td>".$field['Marca']."</td>
		<td>".$field['Modelo']."</td>
		<td align='center'>".$field['Anio']."</td>
		<td align='center'>".$field['Color']."</td>
		<td align='center'>".$field['Placa']."</td>
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