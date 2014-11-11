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
<form name='frmreporta' action='cargos_formacion.php' method='POST' onSubmit='return verificarCargoFormacion(this, \"".$registro."\");'>
<table width='700' class='tblForm'>
  <tr>
    <td class='tagForm'>Grado de Instrucci&oacute;n:</td>
	  <td>
			<select name='grado' id='grado' class='selectMed' onchange='getProfesiones(this.form);'>
				<option value=''></option>";
					getGInstruccion('', 0);
					echo "
			</select>*
		</td>
	  <td class='tagForm'>Area:</td>
	  <td>
			<select name='area' id='area' class='selectMed' onchange='getProfesiones(this.form);'>
				<option value=''></option>";
					getMiscelaneos('', 'AREA', 0);
					echo "
			</select>
		</td>
	</tr>
  <tr>
	  <td class='tagForm'>Profesi&oacute;n:</td>
	  <td colspan='3'>
			<select name='profesion' id='profesion' class='selectMed' disabled>
			</select>
		</td>
	</tr>
</table>

<table width='700' class='tblBotones'>
	<tr>
		<td align='right'>
			<input name='btNuevo' type='submit' id='btNuevo' value='Agregar Formaci&oacute;n' />
			<input name='btBorrar' type='button' id='btBorrar' value='Eliminar Formaci&oacute;n' onClick='eliminarSubCargo(this.form, \"cargos_formacion.php?accion=ELIMINAR&registro=".$_GET['registro']."\");' />
		</td>
	</tr>
</table>";

//	ELIMINO EL REGISTRO
if ($_GET['accion']=="ELIMINAR") {
	$sql="DELETE FROM rh_cargoformacion WHERE Secuencia='".$_POST['det']."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
}
//	CONSULTO LA TABLA
$sql="SELECT rh_cargoformacion.Secuencia, rh_gradoinstruccion.Descripcion, mastmiscelaneosdet.Descripcion, rh_profesiones.Descripcion FROM rh_cargoformacion INNER JOIN rh_gradoinstruccion ON (rh_cargoformacion.CodGradoInstruccion=rh_gradoinstruccion.CodGradoInstruccion) LEFT OUTER JOIN mastmiscelaneosdet ON (rh_cargoformacion.Area=mastmiscelaneosdet.CodDetalle AND mastmiscelaneosdet.CodMaestro='AREA') LEFT OUTER JOIN rh_profesiones ON (rh_cargoformacion.CodProfesion=rh_profesiones.CodProfesion) WHERE (rh_cargoformacion.CodCargo='".$_GET['registro']."') ORDER BY rh_cargoformacion.Secuencia";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
//	MUESTRO LA TABLA
echo "
<input type='hidden' name='det' id='det' />
<table width='700' class='tblLista'>
  <tr class='trListaHead'>
		<th width='150' scope='col'>Grado de Instrucci&oacute;n</th>
		<th scope='col'>Area</th>
		<th scope='col'>Profesi&oacute;n</th>
	</tr>";
for ($i=0; $i<$rows; $i++) {
	$field=mysql_fetch_array($query);
	echo "
	<tr class='trListaBody' onclick='mClk(this, \"det\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field[0]."'>
		<td>".$field[1]."</td>
		<td>".$field[2]."</td>
		<td>".$field[3]."</td>
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