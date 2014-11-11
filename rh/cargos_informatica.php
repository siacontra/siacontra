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
<form name='frmreporta' action='cargos_informatica.php' method='POST' onSubmit='return verificarCargoInformat(this, \"".$registro."\");'>
<table width='700' class='tblForm'>
  <tr>
    <td class='tagForm'>Curso:</td>
	  <td>
			<select name='curso' id='curso' class='selectMed'>
				<option value=''>";
					getMiscelaneos('', 'INFORMAT', 0);
					echo "
			</select>
		</td>
	  <td class='tagForm'>Nivel:</td>
	  <td>
			<select name='nivel' id='nivel' class='selectMed'>
				<option value=''>";
					getMiscelaneos('', 'NIVEL', 0);
					echo "
			</select>
		</td>
	</tr>
</table>

<table width='700' class='tblBotones'>
	<tr>
		<td align='right'>
			<input name='btNuevo' type='submit' id='btNuevo' value='Agregar Curso' />
			<input name='btBorrar' type='button' id='btBorrar' value='Eliminar Curso' onClick='eliminarSubCargo(this.form, \"cargos_informatica.php?accion=ELIMINAR&registro=".$_GET['registro']."\");' />
		</td>
	</tr>
</table>";

//	ELIMINO EL REGISTRO
if ($_GET['accion']=="ELIMINAR") {
	$sql="DELETE FROM rh_cargoinformat WHERE CodCargo='".$_GET['registro']."' AND Informatica='".$_POST['det']."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
}
//	CONSULTO LA TABLA
$sql="SELECT rh_cargoinformat.Informatica, rh_cargoinformat.Nivel, mastmiscelaneosdet.Descripcion FROM rh_cargoinformat, mastmiscelaneosdet WHERE (rh_cargoinformat.CodCargo='".$_GET['registro']."') AND (mastmiscelaneosdet.CodMaestro='INFORMAT' AND rh_cargoinformat.Informatica=mastmiscelaneosdet.CodDetalle)";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
//	MUESTRO LA TABLA
echo "
<input type='hidden' name='det' id='det' />
<table width='700' class='tblLista'>
  <tr class='trListaHead'>
		<th scope='col'>Curso</th>
		<th width='250' scope='col'>Nivel</th>
	</tr>";
for ($i=0; $i<$rows; $i++) {
	$field=mysql_fetch_array($query);
	//----------
	$sql1="SELECT Descripcion FROM mastmiscelaneosdet WHERE CodMaestro='NIVEL' AND CodDetalle='".$field[1]."'";
	$query1=mysql_query($sql1) or die ($sql1.mysql_error());
	$rows1=mysql_num_rows($query1);
	if ($rows1!=0) { $field1=mysql_fetch_array($query1); $nivel=$field1[0]; }
	//
	echo "
	<tr class='trListaBody' onclick='mClk(this, \"det\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field[0]."'>
		<td>".$field[2]."</td>
		<td>".$nivel."</td>
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