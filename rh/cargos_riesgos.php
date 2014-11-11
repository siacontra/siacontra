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
<form name='frmreporta' action='cargos_riesgos.php' method='POST' onSubmit='return verificarCargoRiesgo(this, \"".$registro."\");'>
<table width='700' class='tblForm'>
  <tr>
    <td class='tagForm'>Tipo de Riesgo:</td>
	  <td>
			<select name='triesgo' id='triesgo'>
				<option value=''></option>";
					getMiscelaneos('', 'TRIESGO', 0);
					echo "
			</select>
		</td>
	</tr>
	<tr>
	  <td class='tagForm'>Riesgo:</td>
		<td><textarea name='riesgo' id='riesgo' cols='100' rows='1'></textarea></td>
	</tr>
</table>

<table width='700' class='tblBotones'>
	<tr>
		<td align='right'>
			<input name='btNuevo' type='submit' id='btNuevo' value='Agregar Riesgo' />
			<input name='btBorrar' type='button' id='btBorrar' value='Eliminar Riesgo' onClick='eliminarSubCargo(this.form, \"cargos_riesgos.php?accion=ELIMINAR&registro=".$_GET['registro']."\");' />
		</td>
	</tr>
</table>";

//	ELIMINO EL REGISTRO
if ($_GET['accion']=="ELIMINAR") {
	$sql="DELETE FROM rh_cargoriesgo WHERE Secuencia='".$_POST['det']."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
}
//	CONSULTO LA TABLA
$sql="SELECT rh_cargoriesgo.Secuencia, mastmiscelaneosdet.Descripcion, rh_cargoriesgo.Riesgo FROM rh_cargoriesgo, mastmiscelaneosdet WHERE (rh_cargoriesgo.CodCargo='".$_GET['registro']."' AND mastmiscelaneosdet.CodMaestro='TRIESGO' AND rh_cargoriesgo.TipoRiesgo=mastmiscelaneosdet.CodDetalle)";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
//	MUESTRO LA TABLA
echo "
<input type='hidden' name='det' id='det' />
<table width='700' class='tblLista'>
  <tr class='trListaHead'>
		<th width='200' scope='col'>Tipo de Riesgo</th>
		<th scope='col'>Riesgo</th>
	</tr>";
for ($i=0; $i<$rows; $i++) {
	$field=mysql_fetch_array($query);
	echo "
	<tr class='trListaBody' onclick='mClk(this, \"det\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field[0]."'>
		<td align='left'>".$field[1]."</td>
		<td align='left'>".$field[2]."</td>
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