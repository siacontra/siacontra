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
<form name='frmreporta' action='cargos_sub.php' method='POST' onSubmit='return verificarCargoSub(this, \"".$registro."\");'>
<table width='700' class='tblForm'>
  <tr>
    <td class='tagForm'>Cargo:</td>
	  <td>
			<select name='cargos' id='cargos' class='selectBig'>
				<option value=''></option>";
					getCargos('', '', 2);
					echo "
			</select>
		</td>
	  <td class='tagForm'>Cantidad:</td>
		<td><input type='text' name='cantidad' id='cantidad' size='8' maxlength='4' /></td>
	</tr>
</table>

<table width='700' class='tblBotones'>
	<tr>
		<td align='right'>
			<input name='btNuevo' type='submit' id='btNuevo' value='Agregar Cargo Sub.' />
			<input name='btBorrar' type='button' id='btBorrar' value='Eliminar Cargo Sub.' onClick='eliminarSubCargo(this.form, \"cargos_sub.php?accion=ELIMINAR&registro=".$_GET['registro']."\");' />
		</td>
	</tr>
</table>";

//	ELIMINO EL REGISTRO
if ($_GET['accion']=="ELIMINAR") {
	$sql="DELETE FROM rh_cargosub WHERE CargoSubordinado='".$_POST['det']."' AND CodCargo='".$_GET['registro']."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
}
//	CONSULTO LA TABLA
$sql="SELECT rh_cargosub.CodCargo, rh_cargosub.CargoSubordinado, rh_puestos.DescripCargo, rh_cargosub.Cantidad FROM rh_cargosub, rh_puestos WHERE (rh_cargosub.CodCargo='".$_GET['registro']."' AND rh_cargosub.CargoSubordinado=rh_puestos.CodCargo)";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
//	MUESTRO LA TABLA
echo "
<input type='hidden' name='det' id='det' />
<table width='700' class='tblLista'>
  <tr class='trListaHead'>
		<th width='550' scope='col'>Cargos Subordinados</th>
		<th scope='col'>Cantidad</th>
	</tr>";
for ($i=0; $i<$rows; $i++) {
	$field=mysql_fetch_array($query);
	echo "
	<tr class='trListaBody' onclick='mClk(this, \"det\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field[1]."'>
		<td align='left'>".$field[2]."</td>
		<td align='center'>".$field[3]."</td>
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