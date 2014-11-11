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
<form name='frmentrada' id='frmentrada' action='patrimonio_cuenta.php' method='POST' onSubmit='return verificarPatrimonioCuenta(this, \"".$registro."\");'>
<table width='950' class='tblForm'>
  <tr>
	  <td>Tipo:</td>
	  <td>Instituci&oacute;n:</td>
	  <td>Cuenta:</td>
	  <td>Valor:</td>
	  <td>En Garant&iacute;a:</td>
	</tr>
	<tr>
	  <td>
	  	<select name='tipo' id='tipo' class='selectSma'>
			<option value=''></option>";
			getMiscelaneos("", "TIPOCTA", 0);
		echo "
		</select>
	  </td>
	  <td><input type='text' name='institucion' id='institucion' size='75' maxlength='100' /></td>
	  <td><input type='text' name='cta' id='cta' size='50' maxlength='30' /></td>
	  <td><input type='text' name='valor' id='valor' /></td>
	  <td align='center'><input type='checkbox' name='fgarantia' id='fgarantia' value='S' /></td>
	</tr>
</table>

<table width='950' class='tblBotones'>
	<tr>
		<td align='right'>
			<input name='btNuevo' type='submit' id='btNuevo' value='Agregar' />
			<input name='btBorrar' type='button' id='btBorrar' value='Eliminar' onClick='eliminarPatrimonio(this.form, \"patrimonio_cuenta.php?accion=ELIMINAR&registro=".$registro."\", \"".$registro."\");' />
		</td>
	</tr>
</table>";

//	ELIMINO EL REGISTRO
if ($_GET['accion']=="ELIMINAR") {
	$sql="DELETE FROM rh_patrimonio_cuenta WHERE Secuencia='".$det."' AND CodPersona='".$registro."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
}
//	CONSULTO LA TABLA
$sql="SELECT rh_patrimonio_cuenta.Secuencia, rh_patrimonio_cuenta.TipoCuenta, rh_patrimonio_cuenta.Institucion, rh_patrimonio_cuenta.NroCuenta, rh_patrimonio_cuenta.Valor, rh_patrimonio_cuenta.FlagGarantia, (SELECT Descripcion FROM mastmiscelaneosdet WHERE CodMaestro='TIPOCTA' AND CodDetalle=rh_patrimonio_cuenta.TipoCuenta) AS TipoCuenta FROM rh_patrimonio_cuenta WHERE CodPersona='".$registro."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
//	MUESTRO LA TABLA
echo "
<input type='hidden' name='det' id='det' />
<table width='950' class='tblLista'>
  <tr class='trListaHead'>
		<th width='130' scope='col'>Tipo</th>
		<th width='350' scope='col'>Instituci&oacute;n</th>
		<th width='250' scope='col'>Cuenta</th>
		<th width='110' scope='col'>Valor</th>
		<th scope='col'>En Garant&iacute;a</th>
	</tr>";
for ($i=0; $i<$rows; $i++) {
	$field=mysql_fetch_array($query);
	$valor=number_format($field['Valor'], 2, ',', '.');
	if ($field['FlagGarantia']=="S") $checked="checked"; else $checked="";
	//----------
	echo "
	<tr class='trListaBody' onclick='mClk(this, \"det\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['Secuencia']."'>
		<td>".$field['TipoCuenta']."</td>
		<td>".$field['Institucion']."</td>
		<td align='center'>".$field['NroCuenta']."</td>
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