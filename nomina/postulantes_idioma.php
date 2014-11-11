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
<form name='frmreporta' action='postulantes_idioma.php' method='POST' onSubmit='return verificarPostulanteIdioma(this, \"".$registro."\");'>
<table width='500' class='tblForm'>
  <tr>
    <td class='tagForm'>Idioma:</td>
	  <td class='tagForm'>Lectura:</td>
	  <td class='tagForm'>Oral:</td>
	  <td class='tagForm'>Escritura:</td>
	  <td class='tagForm'>General:</td>
	</tr>
	<tr>
	  <td align='right'>
			<select name='idioma' id='idioma'>
				<option value=''>";
					getIdiomas('', 0);
					echo "
			</select>
		</td>
	  <td align='right'>
			<select name='lectura' id='lectura'>
				<option value=''>";
					getMiscelaneos('', 'NIVEL', 0);
					echo "
			</select>
		</td>
	  <td align='right'>
			<select name='oral' id='oral'>
				<option value=''>";
					getMiscelaneos('', 'NIVEL', 0);
					echo "
			</select>
		</td>
	  <td align='right'>
			<select name='escritura' id='escritura'>
				<option value=''>";
					getMiscelaneos('', 'NIVEL', 0);
					echo "
			</select>
		</td>
	  <td align='right'>
			<select name='general' id='general'>
				<option value=''>";
					getMiscelaneos('', 'NIVEL', 0);
					echo "
			</select>
		</td>
	</tr>
</table>

<table width='500' class='tblBotones'>
	<tr>
		<td align='right'>
			<input name='btNuevo' type='submit' id='btNuevo' value='Agregar Idioma' />
			<input name='btBorrar' type='button' id='btBorrar' value='Eliminar Idioma' onClick='eliminarSubCargo(this.form, \"postulantes_idioma.php?accion=ELIMINAR&registro=".$_GET['registro']."\");' />
		</td>
	</tr>
</table>";

//	ELIMINO EL REGISTRO
if ($_GET['accion']=="ELIMINAR") {
	$sql="DELETE FROM rh_postulantes_idioma WHERE CodIdioma='".$_POST['det']."' AND Postulante='".$_GET['registro']."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
}
//	CONSULTO LA TABLA
$sql="SELECT rh_postulantes_idioma.CodIdioma, mastidioma.DescripcionLocal, rh_postulantes_idioma.NivelLectura, rh_postulantes_idioma.NivelOral, rh_postulantes_idioma.NivelEscritura, rh_postulantes_idioma.NivelGeneral FROM rh_postulantes_idioma, mastidioma WHERE (rh_postulantes_idioma.Postulante='".$_GET['registro']."' AND rh_postulantes_idioma.CodIdioma=mastidioma.CodIdioma)";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
//	MUESTRO LA TABLA
echo "
<input type='hidden' name='det' id='det' />
<table width='500' class='tblLista'>
  <tr class='trListaHead'>
		<th width='100' scope='col'>Idioma</th>
		<th width='100' scope='col'>Lectura</th>
		<th width='100' scope='col'>Oral</th>
		<th width='100' scope='col'>Escritura</th>
		<th width='100' scope='col'>General</th>
	</tr>";
for ($i=0; $i<$rows; $i++) {
	$field=mysql_fetch_array($query);
	//----------
	$sql1="SELECT Descripcion FROM mastmiscelaneosdet WHERE CodMaestro='NIVEL' AND CodDetalle='".$field[2]."'";
	$query1=mysql_query($sql1) or die ($sql1.mysql_error());
	$rows1=mysql_num_rows($query1);
	if ($rows1!=0) { $field1=mysql_fetch_array($query1); $lectura=$field1[0]; }
	//
	$sql1="SELECT Descripcion FROM mastmiscelaneosdet WHERE CodMaestro='NIVEL' AND CodDetalle='".$field[3]."'";
	$query1=mysql_query($sql1) or die ($sql1.mysql_error());
	$rows1=mysql_num_rows($query1);
	if ($rows1!=0) { $field1=mysql_fetch_array($query1); $oral=$field1[0]; }
	//
	$sql1="SELECT Descripcion FROM mastmiscelaneosdet WHERE CodMaestro='NIVEL' AND CodDetalle='".$field[4]."'";
	$query1=mysql_query($sql1) or die ($sql1.mysql_error());
	$rows1=mysql_num_rows($query1);
	if ($rows1!=0) { $field1=mysql_fetch_array($query1); $escritura=$field1[0]; }
	//
	$sql1="SELECT Descripcion FROM mastmiscelaneosdet WHERE CodMaestro='NIVEL' AND CodDetalle='".$field[5]."'";
	$query1=mysql_query($sql1) or die ($sql1.mysql_error());
	$rows1=mysql_num_rows($query1);
	if ($rows1!=0) { $field1=mysql_fetch_array($query1); $general=$field1[0]; }
	
	//----------
	echo "
	<tr class='trListaBody' onclick='mClk(this, \"det\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field[0]."'>
		<td align='center'>".$field[1]."</td>
		<td align='center'>".$lectura."</td>
		<td align='center'>".$oral."</td>
		<td align='center'>".$escritura."</td>
		<td align='center'>".$general."</td>
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