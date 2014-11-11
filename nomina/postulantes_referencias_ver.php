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
$sql="SELECT Secuencia, Nombre, Telefono, Empresa, Cargo, Direccion FROM rh_postulantes_referencias WHERE Tipo='L' AND Postulante='".$registro."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
//	MUESTRO LA TABLA
echo "
<table width='800' class='tblLista'>";
for ($i=1; $i<=$rows; $i++) {
	$field=mysql_fetch_array($query);
	echo "
	<tr class='trListaBody' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field["Secuencia"]."'>
		<td>
			<table>
			  <tr class='trListaHead'>
				<td align='center' width='50'>#</td>
				<td width='450'>Nombre Jefe Anterior</td>
				<td width='180'>Telefono</td>
			  </tr>
			  <tr>
				<td align='center' rowspan='5'>".$i."</td>
				<td>".($field['Nombre'])."</td>
				<td>".$field["Telefono"]."</td>
			  </tr>
			  <tr class='trListaHead'>
				<td>Empresa</td>
				<td>Cargo</td>
			  </tr>
			  <tr>
				<td>".($field['Empresa'])."</td>
				<td>".($field['Cargo'])."</td>
			  </tr>
			  <tr class='trListaHead'>
				<td colspan='2'>Direcci&oacute;n</td>
			  </tr>
			  <tr>
				<td colspan='2'>".($field['Direccion'])."</td>
			  </tr>
			  <tr><td colspan='3' valign='bottom'><hr width='675' size='2' color='#CDCDCD' /></td></tr>
			</table>
		</td>
	</tr>";
}
echo "</table>";

?>
</body>
</html>