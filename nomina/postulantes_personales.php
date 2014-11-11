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
<form name='frmentrada' action='postulantes_personales.php' method='POST' onSubmit='return verificarPostReferencias(this, \"".$registro."\");'>
<input type='hidden' name='tipo' id='tipo' value='P' />
<input type='hidden' name='cargo' id='cargo' />
<table width='800' class='tblForm'>
  <tr>
    <td class='tagForm'>Nombre:</td>
	<td><input type='text' name='nombre' id='nombre' size='50' maxlength='200' />*</td>
    <td class='tagForm'>Tel&eacute;fono:</td>
	<td><input type='text' name='tel' id='tel' size='15' maxlength='15' /></td>
  </tr>	
  <tr>
    <td class='tagForm'>Empresa:</td>
	<td><input type='text' name='empresa' id='empresa' size='75' maxlength='200' />*</td>
  </tr>	
  <tr>
    <td class='tagForm'>Direcci&oacute;n:</td>
	<td colspan='3'><input type='text' name='dir' id='dir' size='100' maxlength='200' /></td>
  </tr>
</table>

<table width='800' class='tblBotones'>
	<tr>
		<td bgcolor='#CCCCCC' align='right'>
			<input name='btNuevo' type='submit' id='btNuevo' value='Agregar' />
			<input name='btBorrar' type='button' id='btBorrar' value='Eliminar' onClick='eliminarReferencia(this.form, \"postulantes_personales.php?accion=ELIMINAR&registro=".$_GET['registro']."\");' />
		</td>
	</tr>
</table>";

//	ELIMINO EL REGISTRO
if ($_GET['accion']=="ELIMINAR") {
	$sql="DELETE FROM rh_postulantes_referencias WHERE Secuencia='".$_POST['det']."' AND Postulante='".$registro."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
}
//	CONSULTO LA TABLA
$sql="SELECT Secuencia, Nombre, Telefono, Empresa, Cargo, Direccion FROM rh_postulantes_referencias WHERE Tipo='P' AND Postulante='".$registro."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
//	MUESTRO LA TABLA
echo "
<input type='hidden' name='det' id='det' />
<table width='800' class='tblLista'>";
for ($i=1; $i<=$rows; $i++) {
	$field=mysql_fetch_array($query);
	echo "
	<tr class='trListaBody' onclick='mClk(this, \"det\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field["Secuencia"]."'>
		<td>
			<table>
			  <tr class='trListaHead'>
				<td align='center' width='50'>#</td>
				<td width='550'>Nombre Jefe Anterior</td>
				<td width='180'>Telefono</td>
			  </tr>
			  <tr>
				<td align='center' rowspan='5'>".$i."</td>
				<td>".($field['Nombre'])."</td>
				<td>".$field["Telefono"]."</td>
			  </tr>
			  <tr class='trListaHead'>
				<td colspan='2'>Empresa</td>
			  </tr>
			  <tr>
				<td colspan='2'>".($field['Empresa'])."</td>
			  </tr>
			  <tr class='trListaHead'>
				<td colspan='2'>Direcci&oacute;n</td>
			  </tr>
			  <tr>
				<td colspan='2'>".($field['Direccion'])."</td>
			  </tr>
			  <tr><td colspan='3' valign='bottom'><hr width='775' size='2' color='#CDCDCD' /></td></tr>
			</table>
		</td>
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