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
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Maestro de Tipos de Contrato | Ver Registro</td>
		<td align="right"><a class="cerrar" href="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
include("fphp.php");
connect();
$sql="SELECT * FROM rh_tipocontrato WHERE TipoContrato='".$_GET['registro']."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) {
	$field=mysql_fetch_array($query);
	echo "
	<div style='width:700px' class='divFormCaption'>Datos del Tipo de Contrato</div>
	<table width='700' class='tblForm'>
	  <tr>
	    <td class='tagForm'>Tipo:</td>
	    <td><input name='codigo' type='text' id='codigo' size='2' maxlength='2' value='".$field['TipoContrato']."' readonly /></td>
	  </tr>
	  <tr>
	    <td class='tagForm'>Descripci&oacute;n:</td>
	    <td><input name='descripcion' type='text' id='descripcion' size='60' maxlength='30' value='".$field['Descripcion']."' readonly /></td>
	  </tr>
	  <tr>
	    <td class='tagForm'>En N&oacute;mina?:</td>
	    <td>";
				if ($field['FlagNomina']=="S") echo "<input name='nomina' type='radio' value='S' checked disabled /> Si"; 
				else echo "<input name='nomina' type='radio' value='S' disabled /> Si";
				if ($field['FlagNomina']=="N") echo "<input name='nomina' type='radio' value='N' checked disabled /> No"; 
				else echo "<input name='nomina' type='radio' value='N' disabled /> No";
			echo "
			</td>
	  </tr>
	  <tr>
	    <td class='tagForm'>Tiene Vencimiento?:</td>
	    <td>";
				if ($field['FlagVencimiento']=="S") echo "<input name='vence' type='radio' value='S' checked disabled /> Si"; 
				else echo "<input name='vence' type='radio' value='S' disabled /> Si";
				if ($field['FlagVencimiento']=="N") echo "<input name='vence' type='radio' value='N' checked disabled /> No"; 
				else echo "<input name='vence' type='radio' value='N' disabled /> No";
			echo "
			</td>
	  </tr>
	  <tr>
	    <td class='tagForm'>Estado:</td>
	    <td>";
				if ($field['Estado']=="A") echo "<input name='status' type='radio' value='A' checked disabled /> Activo"; 
				else echo "<input name='status' type='radio' value='A' disabled /> Activo";
				if ($field['Estado']=="I") echo "<input name='status' type='radio' value='I' checked disabled /> Inactivo"; 
				else echo "<input name='status' type='radio' value='I' disabled /> Inactivo";
			echo "
			</td>
	  </tr>
	  <tr>
		  <td class='tagForm'>&Uacute;ltima Modif.:</td>
		  <td>
				<input name='ult_usuario' type='text' id='ult_usuario' size='30' value='".$field['UltimoUsuario']."' readonly />
				<input name='ult_fecha' type='text' id='ult_fecha' size='25' value='".$field['UltimaFecha']."' readonly />
			</td>
		</tr>
	</table>";
}
?>
</body>
</html>
