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
		<td class="titulo">Maestro de Grado de Instrucci&oacute;n | Actualizaci&oacute;n</td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
include("fphp.php");
connect();
if ($_POST['registro']=="") $_POST['registro']=$_GET['registro'];
if ($_POST['filtro']=="")  $_POST['filtro']=$_GET['filtro'];
$sql="SELECT * FROM rh_gradoinstruccion WHERE (CodGradoInstruccion='".$_POST['registro']."')";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) {
	$field=mysql_fetch_array($query);
	echo "
	<form name='frmentrada' action='ginstruccion.php' method='POST' onsubmit='return verificarGInstruccion(this, \"ACTUALIZAR\");'>
	<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />
	<div style='width:700px' class='divFormCaption'>Datos del Grado de Instrucci&oacute;n</div>
	<table width='700' class='tblForm'>
	  <tr>
	    <td class='tagForm'>Grado:</td>
	    <td><input name='codigo' type='text' id='codigo' size='6' maxlength='3' value='".$field['CodGradoInstruccion']."' readonly /></td>
	  </tr>
	  <tr>
	    <td class='tagForm'>Descripci&oacute;n:</td>
	    <td><input name='descripcion' type='text' id='descripcion' size='75' maxlength='255' value='".$field['Descripcion']."' /></td>
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
	<input name='bt_cancelar' type='button' id='bt_cancelar' value='Cancelar' onclick='cargarPagina(this.form, \"ginstruccion.php\");' />
	</center>
	</form>
	
	<br><div class='divDivision'>Niveles del Grado de Instrucci&oacute;n</div><br>
	
	<form name='frmriesgos' action='tiposcargo_editar.php' method='POST' onsubmit='return verificarNInstruccion(this, \"INSERTAR\");'>
	<input type='hidden' name='helemento' id='helemento' />
	<table width='700' class='tblForm'>
	  <tr>
	    <td class='tagForm'>Nivel:</td>
	    <td><input name='elemento' type='text' id='elemento' size='6' maxlength='3' />*</td>
	  </tr>
	  <tr>
	    <td class='tagForm'>Detalle:</td>
	    <td><input name='detalle' type='text' id='detalle' size='75' maxlength='255' />*</td>
	  </tr>
	  <tr>
	    <td class='tagForm'>&Uacute;ltima Modif.:</td>
	    <td>
				<input name='ult_usuario' type='text' id='ult_usuario' size='30' readonly />
				<input name='ult_fecha' type='text' id='ult_fecha' size='25' readonly />
			</td>
	  </tr>
	</table>
	<center>
	<input type='submit' value='Guardar Elemento' />
	<input type='button' value='Resetear' onclick='resetNInstruccion(this.form);' />
	</center>
	</form>
	
	<form name='frmtabla'>
	<input type='hidden' name='det' id='det' />
	<table width='700' class='tblBotones'>
	 <tr>
		<td align='right'>
			<input name='btEditar' type='button' class='btLista' id='btEditar' value='Editar' onclick='editarNInstruccion(this.form, \"ginstruccion_editar.php\", \"\", \"\");' />
			<input name='btBorrar' type='button' class='btLista' id='btBorrar' value='Borrar' onclick='eliminarNInstruccion(this.form, \"ginstruccion_editar.php?accion=ELIMINAR\", \"1\", \"GINSTRUCCION\");' />
		</td>
	 </tr>
	</table>";
	
	//	ELIMINO EL REGISTRO
	if ($_GET['accion']=="ELIMINAR") {
		$sql="DELETE FROM rh_nivelgradoinstruccion WHERE (CodGradoInstruccion='".$_POST['registro']."' AND Nivel='".$_GET['elemento']."')";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	}
	//	CONSULTO LA TABLA
	$sql="SELECT * FROM rh_nivelgradoinstruccion WHERE (CodGradoInstruccion='".$_POST['registro']."') ORDER BY Nivel";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	//	MUESTRO LA TABLA
	echo "	
	<table width='700' class='tblLista'>
	  <tr class='trListaHead'>
	    <th width='75' scope='col'>Nivel</th>
	    <th width='500' scope='col'>Detalle</th>
	  </tr>";
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		echo "
		<tr class='trListaBody' onclick='mClk(this, \"det\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['Nivel']."'>
			<td align='center'>".$field['Nivel']."</td>
			<td>".$field['Descripcion']."</td>
		</tr>";
	}
	echo "
	</table>
	</form>";
	echo "
	<script type='text/javascript' language='javascript'>
		totalElementos($rows);
	</script>";
}
?>

</body>
</html>