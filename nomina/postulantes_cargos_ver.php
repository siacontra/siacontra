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
$sql="SELECT rh_postulantes_cargos.Secuencia, mastorganismos.Organismo, rh_puestos.DescripCargo, rh_postulantes_cargos.Comentario FROM rh_postulantes_cargos, mastorganismos, rh_puestos WHERE rh_postulantes_cargos.CodOrganismo=mastorganismos.CodOrganismo AND rh_postulantes_cargos.CodCargo=rh_puestos.CodCargo AND rh_postulantes_cargos.Postulante='".$registro."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
//	MUESTRO LA TABLA
echo "
<input type='hidden' name='det' id='det' />
<table width='800' class='tblLista'>
	<tr class='trListaHead'>
		<th width='275' scope='col'>Organismo</th>
		<th width='200' scope='col'>Cargo</th>
		<th scope='col'>Comentario</th>
	</tr>";
for ($i=0; $i<$rows; $i++) {
	$field=mysql_fetch_array($query);
	//-------------------------------
	echo "
	<tr class='trListaBody' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['Secuencia']."'>
		<td>".($field['Organismo'])."</td>
		<td>".($field['DescripCargo'])."</td>
		<td>".($field['Comentario'])."</td>
	</tr>";
}
echo "
</table>";

?>
</body>
</html>