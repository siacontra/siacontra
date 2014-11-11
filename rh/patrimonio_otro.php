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
$registro=$registro;
echo "
<form name='frmentrada' id='frmentrada' action='patrimonio_otros.php' method='POST' onSubmit='return verificarPatrimonioOtro(this, \"".$registro."\");'>
<table width='950' class='tblForm'>
  <tr>
	  <td>Descripci&oacute;n:</td>
	  <td>Valor Compra:</td>
	  <td>Valor:</td>
	  <td>En Garant&iacute;a:</td>
	</tr>
	<tr>
	  <td><input type='text' name='descripcion' id='descripcion' size='100' maxlength='100' /></td>
	  <td><input type='text' name='valorc' id='valorc' size='45' /></td>
	  <td><input type='text' name='valor' id='valor' size='45' /></td>
	  <td align='center'><input type='checkbox' name='fgarantia' id='fgarantia' value='S' /></td>
	</tr>
</table>

<table width='950' class='tblBotones'>
	<tr>
		<td align='right'>
			<input name='btNuevo' type='submit' id='btNuevo' value='Agregar' />
			<input name='btBorrar' type='button' id='btBorrar' value='Eliminar' onClick='eliminarPatrimonio(this.form, \"patrimonio_otro.php?accion=ELIMINAR&registro=".$registro."\", \"".$registro."\");' />
		</td>
	</tr>
</table>";

//	ELIMINO EL REGISTRO
if ($_GET['accion']=="ELIMINAR") {
	$sql="DELETE FROM rh_patrimonio_otro WHERE Secuencia='".$det."' AND CodPersona='".$registro."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
}
//	CONSULTO LA TABLA
$sql="SELECT Secuencia, Descripcion, ValorCompra, Valor, FlagGarantia FROM rh_patrimonio_otro WHERE CodPersona='".$registro."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
//	MUESTRO LA TABLA
echo "
<input type='hidden' name='det' id='det' />
<table width='950' class='tblLista'>
  <tr class='trListaHead'>
		<th width='430' scope='col'>Descripci&oacute;n</th>
		<th width='215' scope='col'>Valor Compra</th>
		<th width='215' scope='col'>Valor</th>
		<th scope='col'>En Garant&iacute;a</th>
	</tr>";
for ($i=0; $i<$rows; $i++) {
	$field=mysql_fetch_array($query);
	$valor=number_format($field['Valor'], 2, ',', '.');
	if ($field['FlagGarantia']=="S") $checked="checked"; else $checked="";
	//----------
	echo "
	<tr class='trListaBody' onclick='mClk(this, \"det\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['Secuencia']."'>
		<td>".$field['Descripcion']."</td>
		<td align='right'>".$valorc."</td>
		<td align='right'>".$valor."</td>
		<td align='center'><input type='checkbox' disabled $checked /></td>
	</tr>";
}
echo "
</table>
<script type='text/javascript' language='javascript'>
	totalPuestos($rows);
</script>
</form>";

if ($_GET['accion']=="ELIMINAR") {
	echo "
	<script type='text/javascript' language='javascript'>
		actualizarPatrimonio('', '', '".$registro."');
	</script>";
}


?>
</body>
</html>