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
<form name='frmreporta' action='cargos_experiencia.php' method='POST' onSubmit='return verificarCargoExperiencia(this, \"".$registro."\");'>
<table width='700' class='tblForm'>
	<tr>
		<td class='tagForm'>Area de Experiencia:</td>
		<td>
			<select name='area' id='area' style='width:200px;'>
				<option value=''>";
				getMiscelaneos('', 'AREAEXP', 0);
				echo "
			</select>
		</td>
		<td class='tagForm'>Cargo:</td>
		<td>
			<select name='cargo_experiencia' id='cargo_experiencia' style='width:250px;'>
				<option value=''>";
				getCargos("", "", 2);
				echo "
			</select>
		</td>
	</tr>
	<tr>
		<td class='tagForm'>Meses:</td>
		<td><input type='text' name='meses' id='meses' size='6' dir='rtl' /></td>
		<td class='tagForm'>Necesario:</td>
		<td><input type='checkbox' name='flag' id='flag' value='S' /></td>
	</tr>
</table>

<table width='700' class='tblBotones'>
	<tr>
		<td bgcolor='#CCCCCC' align='right'>
			<input name='btNuevo' type='submit' id='btNuevo' value='Agregar Exp. Previa' />
			<input name='btBorrar' type='button' id='btBorrar' value='Eliminar Exp. Previa' onClick='eliminarSubCargo(this.form, \"cargos_experiencia.php?accion=ELIMINAR&registro=".$_GET['registro']."\");' />
		</td>
	</tr>
</table>";

//	ELIMINO EL REGISTRO
if ($_GET['accion']=="ELIMINAR") {
	$sql="DELETE FROM rh_cargoexperiencia WHERE Secuencia='".$_POST['det']."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
}
//	CONSULTO LA TABLA
$sql = "SELECT
			ce.*,
			md.Descripcion AS NomAreaExperiencia,
			c.DescripCargo
		FROM
			rh_cargoexperiencia ce
			LEFT JOIN mastmiscelaneosdet md ON (ce.AreaExperiencia = md.CodDetalle AND md.CodMaestro = 'AREAEXP')
			LEFT JOIN rh_puestos c ON (ce.CargoExperiencia = c.CodCargo)
		WHERE ce.CodCargo = '".$_GET["registro"]."'";

$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
//	MUESTRO LA TABLA
echo "
<input type='hidden' name='det' id='det' />
<table width='700' class='tblLista'>
  <tr class='trListaHead'>
		<th scope='col'>Area de Experiencia</th>
		<th width='100' scope='col'>Meses</th>
		<th width='75' scope='col'>Necesario</th>
	</tr>";
for ($i=0; $i<$rows; $i++) {
	$field=mysql_fetch_array($query);
	if ($field['FlagNecesario']=="S") $flag="checked"; else $flag="";
	if ($field['NomAreaExperiencia'] != "") $descripcion = $field['NomAreaExperiencia']; else $descripcion = $field['DescripCargo'];
	echo "
	<tr class='trListaBody' onclick='mClk(this, \"det\");' id='".$field[1]."'>
		<td align='left'>".($descripcion)."</td>
		<td align='right'>".$field['Meses']."</td>
		<td align='center'><input type='checkbox' name='flagnecesario' id='flagnecesario' disabled $flag /></td>
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