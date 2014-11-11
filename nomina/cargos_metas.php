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
$registro=$_GET['registro'];
echo "
<form name='frmreporta' action='cargos_mestas.php' method='POST' onSubmit='return verificarCargoMeta(this, \"".$registro."\");'>
<table width='700' class='tblForm'>
  <tr>
    <td class='tagForm'>Descripci&oacute;n:</td>
		<td><input type='text' name='descripcion' id='descripcion' size='75' maxlength='50' /></td>
	</tr>
	<tr>
	  <td class='tagForm'>Factor Participaci&oacute;n:</td>
		<td><input type='text' name='factor' id='factor' size='15' maxlength='8' /></td>
	</tr>
</table>

<table width='700' class='tblBotones'>
	<tr>
		<td align='right'>
			<input name='btNuevo' type='submit' id='btNuevo' value='Agregar' />
			<input name='btBorrar' type='button' id='btBorrar' value='Eliminar' onClick='eliminarSubCargo(this.form, \"cargos_metas.php?accion=ELIMINAR&registro=".$_GET['registro']."\");' />
		</td>
	</tr>
</table>";

//	ELIMINO EL REGISTRO
if ($_GET['accion']=="ELIMINAR") {
	$sql="DELETE FROM rh_cargometas WHERE Secuencia='".$_POST['det']."' AND CodCargo='".$_GET['registro']."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
}
//	CONSULTO LA TABLA
$sql="SELECT Secuencia, Descripcion, FactorParticipacion FROM rh_cargometas WHERE (CodCargo='".$_GET['registro']."')";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
//	MUESTRO LA TABLA
echo "
<input type='hidden' name='det' id='det' />
<table width='700' class='tblLista'>
  <tr class='trListaHead'>
		<th width='25' scope='col'>#</th>
		<th scope='col'>Descripci&oacute;n</th>
		<th width='150' scope='col'>Factor Participaci&oacute;n</th>
	</tr>";
for ($i=0; $i<$rows; $i++) {
	$field=mysql_fetch_array($query);
	echo "
	<tr class='trListaBody' onclick='mClk(this, \"det\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field[0]."'>
		<td align='right'>".$field[0]."</td>
		<td align='left'>".$field[1]."</td>
		<td align='right'>".$field[2]."</td>
	</tr>";
}
echo "
</table>
<script type='text/javascript' language='javascript'>
	totalPuestos($rows);
</script>
</form>";

?>
</body>
</html>