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
		<td class="titulo">Maestro de Tipos de Contrato | Actualizaci&oacute;n</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'tiposcontrato.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form id="frmentrada" name="frmentrada" action="tiposcontrato.php" method="POST" onsubmit="return verificarTipoContrato(this, 'ACTUALIZAR');">

<?php
include("fphp.php");
connect();
$sql="SELECT * FROM rh_tipocontrato WHERE TipoContrato='".$_POST['registro']."'";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) {
	$field=mysql_fetch_array($query);
	echo "
	<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />
	<div style='width:700px' class='divFormCaption'>Datos del Tipo de Contrato</div>
	<table width='700' class='tblForm'>
	  <tr>
	    <td class='tagForm'>Tipo:</td>
	    <td><input name='codigo' type='text' id='codigo' size='2' maxlength='2' value='".$field['TipoContrato']."' readonly />*</td>
	  </tr>
	  <tr>
	    <td class='tagForm'>Descripci&oacute;n:</td>
	    <td><input name='descripcion' type='text' id='descripcion' size='60' maxlength='30' value='".$field['Descripcion']."' />*</td>
	  </tr>
	  <tr>
	    <td class='tagForm'>En N&oacute;mina?:</td>
	    <td>";
				if ($field['FlagNomina']=="S") echo "<input name='nomina' type='radio' value='S' checked /> Si"; 
				else echo "<input name='nomina' type='radio' value='S' /> Si";
				if ($field['FlagNomina']=="N") echo "<input name='nomina' type='radio' value='N' checked /> No"; 
				else echo "<input name='nomina' type='radio' value='N' /> No";
			echo "
			</td>
	  </tr>
	  <tr>
	    <td class='tagForm'>Tiene Vencimiento?:</td>
	    <td>";
				if ($field['FlagVencimiento']=="S") echo "<input name='vence' type='radio' value='S' checked /> Si"; 
				else echo "<input name='vence' type='radio' value='S' /> Si";
				if ($field['FlagVencimiento']=="N") echo "<input name='vence' type='radio' value='N' checked /> No"; 
				else echo "<input name='vence' type='radio' value='N' /> No";
			echo "
			</td>
	  </tr>
	  <tr>
	    <td class='tagForm'>Estado:</td>
	    <td>";
				if ($field['Estado']=="A") echo "<input name='status' type='radio' value='A' checked /> Activo"; 
				else echo "<input name='status' type='radio' value='A' /> Activo";
				if ($field['Estado']=="I") echo "<input name='status' type='radio' value='I' checked /> Inactivo"; 
				else echo "<input name='status' type='radio' value='I' /> Inactivo";
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
	</table>
	<center> 
	<input type='submit' value='Guardar Registro' />
	<input name='bt_cancelar' type='button' id='bt_cancelar' value='Cancelar' onclick='cargarPagina(this.form, \"tiposcontrato.php\");' />
	</center><br />";
}
?>
</form>

<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
</body>
</html>
