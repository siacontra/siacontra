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
//	CONSULTO LA TABLA
$sql="SELECT rh_postulantes_idioma.CodIdioma, mastidioma.DescripcionLocal, rh_postulantes_idioma.NivelLectura, rh_postulantes_idioma.NivelOral, rh_postulantes_idioma.NivelEscritura, rh_postulantes_idioma.NivelGeneral FROM rh_postulantes_idioma, mastidioma WHERE (rh_postulantes_idioma.Postulante='".$_GET['registro']."' AND rh_postulantes_idioma.CodIdioma=mastidioma.CodIdioma)";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
//	MUESTRO LA TABLA
echo "
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
	<tr class='trListaBody' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field[0]."'>
		<td align='center'>".$field[1]."</td>
		<td align='center'>".$lectura."</td>
		<td align='center'>".$oral."</td>
		<td align='center'>".$escritura."</td>
		<td align='center'>".$general."</td>
	</tr>";
}
echo "
</table>";


?>
</body>
</html>