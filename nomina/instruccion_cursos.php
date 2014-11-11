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
//	EDITO EL REGISTRO
if ($_GET['accion']=="EDITAR") {
	$sql="SELECT rh_empleado_cursos.*, rh_cursos.Descripcion As Curso, rh_centrosestudios.Descripcion As Centro FROM rh_empleado_cursos, rh_cursos, rh_centrosestudios WHERE (rh_empleado_cursos.Secuencia='".$_GET['secuencia']."' AND rh_empleado_cursos.CodPersona='".$_GET['registro']."') AND (rh_empleado_cursos.CodCurso=rh_cursos.CodCurso) AND (rh_empleado_cursos.CodCentroEstudio=rh_centrosestudios.CodCentroEstudio)";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	if ($rows!=0) {
		$field=mysql_fetch_array($query);
		list($a, $m, $d)=SPLIT('[/.-]', $field['FechaDesde']); $fdesde=$d."-".$m."-".$a;
		if ($fdesde=="00-00-0000" || $fdesde=="00/00/0000") $fdesde="";
		list($a, $m, $d)=SPLIT('[/.-]', $field['FechaHasta']); $fhasta=$d."-".$m."-".$a;
		if ($fhasta=="00-00-0000" || $fhasta=="00/00/0000") $fhasta="";
		if ($field['FlagPago'] == "S") $flagpago = "checked"; 
		if ($field['FlagInstitucional'] == "S") $flaginstitucional = "checked"; 
	}
}
//	ELIMINO EL REGISTRO
if ($_GET['accion']=="ELIMINAR") {
	$sql="DELETE FROM rh_empleado_cursos WHERE Secuencia='".$_GET['secuencia']."' AND CodPersona='".$_GET['registro']."' ";
	$query=mysql_query($sql) or die ($sql.mysql_error());
}
if ($_POST['registro']=="") $_POST['registro']=$_GET['registro'];
echo "
<form id='frmentrada' name='frmentrada' action='instruccion_cursos.php' method='POST' onsubmit='return verificarInstruccionCurso(this, \"GUARDAR\");'>
<input type='hidden' name='registro' id='registro' value='".$_POST['registro']."' />
<table width='750' class='tblForm'>
	<tr>
		<td class='tagForm'>Secuencia:</td>
		<td colspan='3'>
			<input type='text' name='secuencia' id='secuencia' value='".$field['Secuencia']."' size='6' readonly />
			&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
			&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
			Auspiciado por el organismo? &nbsp;<input type='checkbox' name='flag' id='flag' value='S' $flaginstitucional /> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
			Pago N&oacute;mina? &nbsp;<input type='checkbox' name='pago' id='pago' value='S' $flagpago />
		</td>
	</tr>
	<tr>
		<td class='tagForm'>Curso:</td>
		<td>
			<input name='codcurso' type='hidden' id='codcurso' value='".$field['CodCurso']."' readonly />
			<input name='nomcurso' type='text' id='nomcurso' size='65' value='".$field['Curso']."' readonly />
			<input name='bt_examinar' type='button' id='bt_examinar' value='...' onclick='window.open(\"lista_cursos.php?limit=0&registro=".$_POST['registro']."\", \"wLista\", \"toolbar=no, menubar=no, location=no, scrollbars=yes, height=500, width=800, left=200, top=200, resizable=yes\");' />
		</td>
		<td class='tagForm'>Tipo de Curso:</td>
		<td>
			<select name='tcurso' id='tcurso'>
			<option value=''></option>";
				getMiscelaneos($field['TipoCurso'], 'TIPOCURSO', 0);
				echo "
			</select>*
		</td>
	</tr>
	<tr>
	  <td class='tagForm'>Centro de Estudio:</td>
	  <td colspan='3'>
			<input name='codcentro' type='text' id='codcentro' size='5' maxlength='3' value='".$field['CodCentroEstudio']."' readonly />
			<input name='nomcentro' type='text' id='nomcentro' size='75' value='".$field['Centro']."' readonly />
			<input name='bt_examinar' type='button' id='bt_examinar' value='...' onclick='window.open(\"lista_centros_estudios.php?limit=0&flag_estudio=N&flag_curso=S\", \"wLista\", \"toolbar=no, menubar=no, location=no, scrollbars=yes, height=500, width=800, left=200, top=200, resizable=yes\");' />*
		</td>
	</tr>
	<tr>
		<td class='tagForm'>Duraci&oacute;n:</td>
	    <td>
			<input name='fdesde' type='text' id='fdesde' size='15' maxlength='10' value='$fdesde' /> - 
			<input name='fhasta' type='text' id='fhasta' size='15' maxlength='10' value='$fhasta' />
		</td>
		<td class='tagForm'>Periodo Culminaci&oacute;n:</td>
	    <td><input name='fculminacion' type='text' id='fculminacion' size='15' maxlength='10' value='".$field['FechaCulminacion']."' />*<i>(yyyy-mm)</i></td>
	</tr>
	<tr>
		<td class='tagForm'>Horas:</td>
		<td><input type='text' name='horas' id='horas' size='8' maxlength='4' value='".$field['TotalHoras']."' /></td>
	  <td class='tagForm'>A&ntilde;os:</td>
		<td><input type='text' name='anios' id='anios' size='4' maxlength='2' value='".$field['AniosVigencia']."' /></td>
	</tr>
  <tr>
    <td class='tagForm'>Observaciones:</td>
    <td colspan='3'><textarea name='observaciones' id='observaciones' rows='2' cols='100'>".$field['Observaciones']."</textarea></td>
  </tr>
</table>
<center>
<input type='submit' value='Guardar Registro' />
<input type='button' value='Resetear' onclick='resetEstudio(this.form);' />
</center>
	<br /><div style='width:750px' class='divMsj'>Campos Obligatorios *</div><br />
</form>

<br /><div class='divDivision'>Lista de Cursos Realizados</div><br />

<form name='frmtabla'>
<table width='750' class='tblBotones'>
 <tr>
	<td align='right'>
		<input name='btEditar' type='button' class='btLista' id='btEditar' value='Editar' onclick='optICurso(this.form, \"EDITAR\");' />
		<input name='btBorrar' type='button' class='btLista' id='btBorrar' value='Borrar' onclick='optICurso(this.form, \"ELIMINAR\");' />
	</td>
 </tr>
</table>";

//	CONSULTO LA TABLA
$sql="SELECT rh_empleado_cursos.*, rh_cursos.Descripcion As Curso, rh_centrosestudios.Descripcion As Centro FROM rh_empleado_cursos, rh_cursos, rh_centrosestudios WHERE (rh_empleado_cursos.CodPersona='".$_GET['registro']."') AND (rh_empleado_cursos.CodCurso=rh_cursos.CodCurso) AND (rh_empleado_cursos.CodCentroEstudio=rh_centrosestudios.CodCentroEstudio) ORDER BY rh_empleado_cursos.Secuencia";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
//	MUESTRO LA TABLA
echo "<input type='hidden' name='sec' id='sec' />	";
echo "<table align='center' width='752'>";
for ($i=1; $i<=$rows; $i++) {
	$field=mysql_fetch_array($query);
	list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaDesde']); $fdesde=$d."-".$m."-".$a; if ($fdesde=="00-00-0000") $fdesde="";
	list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaHasta']); $fhasta=$d."-".$m."-".$a; if ($fhasta=="00-00-0000") $fhasta="";
	if ($field['FlagInstitucional']=="S") $flag="checked"; else $flag="";
	if ($field['FlagPago']=="S") $flagpago="checked"; else $flagpago="";
	//
	echo "
	<tr class='trListaBody' onclick='mClk(this, \"sec\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['Secuencia']."'>
		<td>
			<table style='width:750px; border-color:#000000;'>
				<tr>
					<td width='125' class='trListaHead'>Nro.:</td>
					<td>".$field['Secuencia']."</td>
					<td colspan='8' align='right'>
						Auspiciado por el organismo? &nbsp;<input type='checkbox' $flag disabled />
						&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
						Pago N&oacute;mina? &nbsp;<input type='checkbox' $flagpago disabled />
					</td>
				</tr>
				<tr>
				  <td class='trListaHead'>Curso:</td>
			    <td colspan='9'>".$field['Curso']."</td>
				</tr>
				<tr>
				  <td class='trListaHead'>Centro de Estudio:</td>
				  <td colspan='9'>".$field['Centro']."</td>
				</tr>
				<tr>
					<td width='125' class='trListaHead'>Periodo Culminacion:</td> <td width='100'>".$field['FechaCulminacion']."</td>
					<td class='trListaHead'>Desde:</td> <td width='100'>$fdesde</td>
					<td width='50' class='trListaHead'>Hasta:</td> <td width='100'>$fhasta</td>
					<td width='50' class='trListaHead'>Horas:</td> <td width='100'>".$field['TotalHoras']."</td>
					<td width='50' class='trListaHead'>A&ntilde;os:</td>	<td>".$field['AniosVigencia']."</td>
				</tr>
			  <tr>
			    <td class='trListaHead'>Observaciones:</td>
			    <td colspan='9'>".$field['Observaciones']."</td>
			  </tr>
			</table>
		</td>
	</tr>";
}
echo "
</table>
</form>";
echo "
<script type='text/javascript' language='javascript'>
	totalElementos($rows);
</script>";
?>

</body>
</html>