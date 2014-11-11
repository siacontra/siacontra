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
$sql="SELECT rh_cargoinformat.Informatica, rh_cargoinformat.Nivel, mastmiscelaneosdet.Descripcion FROM rh_cargoinformat, mastmiscelaneosdet WHERE (rh_cargoinformat.CodCargo='".$_GET['registro']."') AND (mastmiscelaneosdet.CodMaestro='INFORMAT' AND rh_cargoinformat.Informatica=mastmiscelaneosdet.CodDetalle)";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
//	MUESTRO LA TABLA
echo "
<table width='700' class='tblLista'>
  <tr class='trListaHead'>
		<th scope='col'>Curso</th>
		<th width='250' scope='col'>Nivel</th>
	</tr>";
for ($i=0; $i<$rows; $i++) {
	$field=mysql_fetch_array($query);
	//----------
	$sql1="SELECT Descripcion FROM mastmiscelaneosdet WHERE CodMaestro='NIVELIDIOM' AND CodDetalle='".$field[1]."'";
	$query1=mysql_query($sql1) or die ($sql1.mysql_error());
	$rows1=mysql_num_rows($query1);
	if ($rows1!=0) { $field1=mysql_fetch_array($query1); $nivel=$field1[0]; }
	//
	echo "
	<tr class='trListaBody' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field[0]."'>
		<td>".$field[2]."</td>
		<td>".$nivel."</td>
	</tr>";
}
echo "</table>";
?>
</body>
</html>