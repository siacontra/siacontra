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
$sql="SELECT rh_cargofunciones.CodFuncion, mastmiscelaneosdet.Descripcion, rh_cargofunciones.Descripcion, rh_cargofunciones.Estado FROM rh_cargofunciones, mastmiscelaneosdet WHERE (rh_cargofunciones.CodCargo='".$_GET['registro']."') AND (mastmiscelaneosdet.CodMaestro='FUNCION') AND (rh_cargofunciones.Funcion=mastmiscelaneosdet.CodDetalle)";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
//	MUESTRO LA TABLA
echo "
<table width='700' class='tblLista'>
  <tr class='trListaHead'>
		<th width='50' scope='col'>#</th>
		<th width='175' scope='col'>Funci&oacute;n</th>
		<th width='400' scope='col'>Comentarios</th>
		<th width='75' scope='col'>Estado</th>
	</tr>";
for ($i=0; $i<$rows; $i++) {
	$field=mysql_fetch_array($query);
	echo "
	<tr class='trListaBody' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field[0]."'>
		<td align='right'>".$field[0]."</td>
		<td align='left'>".$field[1]."</td>
		<td align='left'>".$field[2]."</td>
		<td align='center'>".$field[3]."</td>
	</tr>";
}
echo "</table>";
?>
</body>
</html>