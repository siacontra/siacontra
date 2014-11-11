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
$sql="SELECT mastpersonas.CodPersona, mastpersonas.NomCompleto, mastpersonas.EstadoCivil, mastempleado.CodEmpleado, (SELECT SUM(Valor) FROM rh_patrimonio_inmueble WHERE CodPersona='".$_GET['registro']."') AS Inmueble, (SELECT SUM(Valor) FROM rh_patrimonio_inversion WHERE CodPersona='".$_GET['registro']."') AS Inversion, (SELECT SUM(Valor) FROM rh_patrimonio_vehiculo WHERE CodPersona='".$_GET['registro']."') AS Vehiculo, (SELECT SUM(Valor) FROM rh_patrimonio_cuenta WHERE CodPersona='".$_GET['registro']."') AS Cuenta, (SELECT SUM(Valor) FROM rh_patrimonio_otro WHERE CodPersona='".$_GET['registro']."') AS Otro FROM mastpersonas, mastempleado WHERE mastpersonas.CodPersona='".$_GET['registro']."' AND mastpersonas.CodPersona=mastempleado.CodPersona";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) {
	$field=mysql_fetch_array($query);
	$inmueble=number_format($field['Inmueble'], 2, ',', '.');
	$inversion=number_format($field['Inversion'], 2, ',', '.');
	$vehiculo=number_format($field['Vehiculo'], 2, ',', '.');
	$cuenta=number_format($field['Cuenta'], 2, ',', '.');
	$otro=number_format($field['Otro'], 2, ',', '.');
	$total=$field['Inmueble']+$field['Inversion']+$field['Vehiculo']+$field['Cuenta']+$field['Otro'];
	$total=number_format($total, 2, ',', '.');
	echo "
	<form name='frmentrada' id='frmentrada' action='patrimoniio_totales.php' method='POST'>
	<input name='persona' type='hidden' id='persona' value='".$field['CodPersona']."' />
	<div class='divBorder' style='width:950px;'>
	<table width='950' class='tblFiltro'>
	  <tr>
	    <td align='right'>Empleado:</td>
	    <td><input name='empleado' type='text' id='empleado' size='10' value='".$field['CodEmpleado']."' readonly /></td>
	  </tr>
	  <tr>
	    <td align='right'>Nombre Completo:</td>
	    <td><input name='nompersona' type='text' id='nompersona' size='75' value='".$field['NomCompleto']."' readonly /></td>
	  </tr>
	  <tr>
	    <td align='right'>Estado Civil:</td>
	    <td>
			<select name='edocivil' id='edocivil'>";
				getMiscelaneos($field['EstadoCivil'], "EDOCIVIL", 1);
			echo "
			</select>
		</td>
	  </tr>
	</table>
	</div><br />
	
	<div class='divBorder' style='width:950px;'>
	<table width='950' class='tblFiltro'>
		<tr>
			<td>Inmueble</td>
			<td>Inversi&oacute;n</td>
			<td>Veh&iacute;culo</td>
			<td>Cuentas</td>
			<td>Otros</td>
			<td>Total</td>
		</tr>
		<tr>
			<td><input type='text' name='inmueble' id='inmueble' size='15' dir='rtl' value='".$inmueble."' readonly /></td>
			<td><input type='text' name='inversion' id='inversion' size='15' dir='rtl' value='".$inversion."' readonly /></td>
			<td><input type='text' name='vehiculo' id='vehiculo' size='15' dir='rtl' value='".$vehiculo."' readonly /></td>
			<td><input type='text' name='cuenta' id='cuenta' size='15' dir='rtl' value='".$cuenta."' readonly /></td>
			<td><input type='text' name='otro' id='otro' size='15' dir='rtl' value='".$otro."' readonly /></td>
			<td><input type='text' name='total' id='total' size='15' dir='rtl' style='font-weight:bold; font-size=18px;' value='".$total."' readonly /></td>
		</tr>
	</table>
	</div>
	</form><br />";	
}
?>
</body>
</html>
