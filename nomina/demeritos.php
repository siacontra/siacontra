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
<form name='frmentrada' action='demeritos.php' method='POST' onSubmit='return verificarDemeritos(this, \"".$registro."\");'>
<input type='hidden' name='tipo' id='tipo' value='D' />
<table width='700' class='tblForm'>
  <tr>
    <td class='tagForm'>Documento:</td>
	<td colspan='3'><input type='text' name='doc' id='doc' size='50' maxlength='50' />*</td>
  </tr>
  <tr>
    <td class='tagForm'>Fecha Doc.:</td>
	<td colspan='3'><input name='fdoc' type='text' id='fdoc' size='15' maxlength='10' />*<em>(dd-mm-yyyy)</em></td>
  </tr>	
  <tr>
  	<td class='tagForm'>Dem&eacute;rito:</td>
    <td>
		<select name='merito' id='merito' style='selectMed' onchange='enabledSuspension();'>
			<option value=''>";
			getMiscelaneos('', 'DEMERITO', 0);
			echo "
		</select>*
	</td>
	<td class='tagForm'>Per&iacute;odo Suspensi&oacute;n:</td>
	<td>
		<input name='fdesde' type='text' id='fdesde' size='15' maxlength='10' disabled /> - 
		<input name='fhasta' type='text' id='fhasta' size='15' maxlength='10' disabled /><em>(dd-mm-yyyy)</em>
	</td>
  </tr>
  <tr>
    <td class='tagForm'>Persona Relacionada:</td>
    <td colspan='3'>
		<input name='codpersona' type='hidden' id='codpersona' value='000001' />
		<input name='persona' type='text' id='persona' size='65' readonly />*
		<input name='bt_examinar' type='button' id='bt_examinar' value='...' onclick='window.open(\"lista_personas.php?limit=0\", \"wLista\", \"toolbar=no, menubar=no, location=no, scrollbars=yes, height=500, width=800, left=200, top=200, resizable=yes\");' />
	</td>
  </tr>
  <tr>
    <td class='tagForm'>Observaciones:</td>
	<td colspan='3'><textarea name='obs' id='obs' cols='75' rows='3'></textarea></td>
  </tr>
</table>

<table width='700' class='tblBotones'>
	<tr>
		<td bgcolor='#CCCCCC' align='right'>
			<input name='btNuevo' type='submit' id='btNuevo' value='Agregar Mérito' />
			<input name='btBorrar' type='button' id='btBorrar' value='Eliminar Mérito' onClick='eliminarMerito(this.form, \"demeritos.php?accion=ELIMINAR&registro=".$_GET['registro']."\");' />
		</td>
	</tr>
</table>";

//	ELIMINO EL REGISTRO
if ($_GET['accion']=="ELIMINAR") {
	$sql="DELETE FROM rh_meritosfaltas WHERE Secuencia='".$_POST['det']."' AND CodPersona='".$registro."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
}
//	CONSULTO LA TABLA
$sql="SELECT rh_meritosfaltas.Secuencia, rh_meritosfaltas.Documento, rh_meritosfaltas.FechaDoc, rh_meritosfaltas.FechaIni, rh_meritosfaltas.FechaFin, rh_meritosfaltas.Observacion, (SELECT mastpersonas.Busqueda FROM mastpersonas WHERE mastpersonas.CodPersona=rh_meritosfaltas.Responsable) AS Responsable, (SELECT mastmiscelaneosdet.Descripcion FROM mastmiscelaneosdet WHERE mastmiscelaneosdet.CodDetalle=rh_meritosfaltas.Clasificacion AND mastmiscelaneosdet.CodMaestro='DEMERITO') AS Clasificacion FROM rh_meritosfaltas WHERE rh_meritosfaltas.Tipo='D' AND rh_meritosfaltas.CodPersona='".$registro."' ORDER BY rh_meritosfaltas.Secuencia";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
//	MUESTRO LA TABLA
echo "
<input type='hidden' name='det' id='det' />
<table width='700' class='tblLista'>";
for ($i=1; $i<=$rows; $i++) {
	$field=mysql_fetch_array($query);
	echo "
	<tr class='trListaBody' onclick='mClk(this, \"det\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field["Secuencia"]."'>
		<td>
			<table>
			  <tr class='trListaHead'>
				<td align='center' width='50'>#</td>
				<td width='335'>Documento</td>
				<td align='center' width='100'>Fecha</td>
				<td align='center' width='200'>Periodo</td>
			  </tr>
			  <tr>
				<td align='center' rowspan='5'>".$i."</td>
				<td>".$field["Documento"]."</td>
				<td align='center'>".$field["FechaDoc"]."</td>
				<td align='center'>
					".$field["FechaIni"]." A ".$field["FechaFin"]."
				</td>
			  </tr>
			  <tr class='trListaHead'>
				<td colspan='2'>Responsable</td>
				<td>Clasificaci&oacute;n</td>
			  </tr>
			  <tr>
				<td colspan='2'>".$field["Responsable"]."</td>
				<td>".$field["Clasificacion"]."</td>
			  </tr>
			  <tr class='trListaHead'>
				<td colspan='3'>Observaciones</td>
			  </tr>
			  <tr>
				<td colspan='3'>".$field["Observacion"]."</td>
			  </tr>
			  <tr><td colspan='4' valign='bottom'><hr width='675' size='2' color='#CDCDCD' /></td></tr>
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