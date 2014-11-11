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
		<td class="titulo">Maestro de Miscel&aacute;neos | Actualizaci&oacute;n</td>
		<td align="right"><a class="cerrar" href="javascript:" onclick="cargarPagina(document.getElementById('frmentrada'), 'miscelaneos.php');">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
include("fphp.php");
connect();
if ($_POST['registro']=="") $_POST['registro']=$_GET['registro'];
if ($_POST['filtro']=="")  $_POST['filtro']=$_GET['filtro'];
list($maestro, $aplicacion)=SPLIT('[-]', $_POST['registro']);
$sql="SELECT mastmiscelaneoscab.*, mastaplicaciones.Descripcion FROM mastmiscelaneoscab, mastaplicaciones WHERE mastmiscelaneoscab.CodMaestro='".$maestro."' AND mastmiscelaneoscab.CodAplicacion='".$aplicacion."' AND mastmiscelaneoscab.CodAplicacion=mastaplicaciones.CodAplicacion";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) {
	$field=mysql_fetch_array($query);
	echo "
	<form id='frmentrada' name='frmentrada' action='miscelaneos.php' method='POST' onsubmit='return verificarMiscelaneo(this, \"ACTUALIZAR\");'>
	<input type='hidden' name='filtro' id='filtro' value='".$_POST['filtro']."' />
	<div style='width:700px' class='divFormCaption'>Datos del Miscel&aacute;neo</div>
	<table width='700' class='tblForm'>
	  <tr>
	    <td class='tagForm'>Aplicaci&oacute;n:</td>
	    <td>
				<select name='aplicacion' id='aplicacion' class='select1'>
					<option value='".$field[1]."'>".$field[5]."
				</select>
			</td>
	  </tr>
	  <tr>
	    <td class='tagForm'>Maestro:</td>
	    <td><input name='codigo' type='text' id='codigo' size='20' maxlength='10' value='".$field[0]."' readonly /></td>
	  </tr>
	  <tr>
	    <td class='tagForm'>Descripci&oacute;n:</td>
	    <td><input name='descripcion' type='text' id='descripcion' size='75' maxlength='60' value='".$field[2]."' /></td>
	  </tr>
	  <tr>
	    <td class='tagForm'>&Uacute;ltima Modif.:</td>
	    <td>
				<input name='ult_usuario' type='text' id='ult_usuario' size='30' value='".$field[3]."' readonly />
				<input name='ult_fecha' type='text' id='ult_fecha' size='25' value='".$field[4]."' readonly />
			</td>
	  </tr>
	</table>
	<center> 
	<input type='submit' value='Guardar Registro' />
	<input name='bt_cancelar' type='button' id='bt_cancelar' value='Cancelar' onclick='cargarPagina(this.form, \"miscelaneos.php\");' />
	</center>
	</form>
	
	<br /><div class='divDivision'>Elementos del Miscel&aacute;neo</div><br />
	
	<form name='frmelementos' action='miscelaneos_editar.php' method='POST' onsubmit='return verificarElemento(this, \"INSERTAR\");'>
	<input name='helemento' type='hidden' id='helemento' value='' />
	<table width='700' class='tblForm'>
	  <tr>
	    <td class='tagForm'>Elemento:</td>
	    <td><input name='elemento' type='text' id='elemento' size='2' maxlength='2' />*</td>
	  </tr>
	  <tr>
	    <td class='tagForm'>Detalle:</td>
	    <td><input name='detalle' type='text' id='detalle' size='60' maxlength='60' />*</td>
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
	<input type='button' value='Resetear' onclick='resetMiscelaneos(this.form);' />
	</center>
	</form>
	
	<form name='frmtabla'>
	<input type='hidden' name='det' id='det' />
	<table width='700' class='tblBotones'>
	 <tr>
		<td align='right'>
			<input name='btEditar' type='button' class='btLista' id='btEditar' value='Editar' onclick='editarElemento(this.form, \"miscelaneos_editar.php\", \"\", \"\");' />
			<input name='btBorrar' type='button' class='btLista' id='btBorrar' value='Borrar' onclick='eliminarElemento(this.form, \"miscelaneos_editar.php?accion=ELIMINAR\", \"1\", \"MISCELANEOS\");' />
		</td>
	 </tr>
	</table>";
	
	//	ELIMINO EL REGISTRO
	if ($_GET['accion']=="ELIMINAR") {
		$sql="DELETE FROM mastmiscelaneosdet WHERE CodMaestro='".$maestro."' AND CodAplicacion='".$aplicacion."' AND CodDetalle='".$_GET['elemento']."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
	}
	//	CONSULTO LA TABLA
	$sql="SELECT * FROM mastmiscelaneosdet WHERE CodMaestro='".$maestro."' AND CodAplicacion='".$aplicacion."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	//	MUESTRO LA TABLA
	echo "
	<table width='700' class='tblLista'>
	  <tr class='trListaHead'>
	    <th width='75' scope='col'>Elemento</th>
	    <th width='500' scope='col'>Detalle</th>
	  </tr>";
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		echo "
		<tr class='trListaBody' onclick='mClk(this, \"det\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['CodDetalle']."'>
			<td align='center'>".$field['CodDetalle']."</td>
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