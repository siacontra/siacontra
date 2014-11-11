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
	$sql="SELECT rh_postulantes_instruccion.*, rh_centrosestudios.Descripcion FROM rh_postulantes_instruccion, rh_centrosestudios WHERE (rh_postulantes_instruccion.Secuencia='".$_GET['secuencia']."' AND rh_postulantes_instruccion.Postulante='".$_GET['registro']."') AND (rh_postulantes_instruccion.CodCentroEstudio=rh_centrosestudios.CodCentroEstudio)";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	if ($rows!=0) {
		$field=mysql_fetch_array($query);
		list($a, $m, $d)=SPLIT('[/.-]', $field['FechaDesde']); $fdesde=$d."-".$m."-".$a;
		if ($fdesde=="00-00-0000" || $fdesde=="00/00/0000") $fdesde="";
		list($a, $m, $d)=SPLIT('[/.-]', $field['FechaHasta']); $fhasta=$d."-".$m."-".$a;
		if ($fhasta=="00-00-0000" || $fhasta=="00/00/0000") $fhasta="";
	}
}
//	ELIMINO EL REGISTRO
if ($_GET['accion']=="ELIMINAR") {
	$sql="DELETE FROM rh_postulantes_instruccion WHERE Secuencia='".$_GET['secuencia']."' AND Postulante='".$_GET['registro']."' ";
	$query=mysql_query($sql) or die ($sql.mysql_error());
}
if ($_POST['registro']=="") $_POST['registro']=$_GET['registro'];
echo "	
<form id='frmentrada' name='frmentrada' action='postulantes_instruccion.php' method='POST' onsubmit='return verificarPostulanteInstruccion(this, \"GUARDAR\");'>
<input type='hidden' name='registro' id='registro' value='".$_POST['registro']."' />
<table width='750' class='tblForm'>
	<tr>
		<td class='tagForm'>Secuencia:</td>
		<td><input type='text' name='secuencia' id='secuencia' value='".$field['Secuencia']."' size='6' readonly /></td>
	</tr>
	<tr>
    <td class='tagForm'>Grado de Instrucci&oacute;n:</td>
	  <td>
			<select name='grado' id='grado' class='selectMed' onchange='getProfesiones(this.form); getOptions_2(this.id, \"nivel\");'>
				<option value=''></option>";
					getGInstruccion($field['CodGradoInstruccion'], 0);
					echo "
			</select>*
		</td>
    <td class='tagForm'>Nivel de Instrucci&oacute;n:</td>
	  <td>";
			if ($_GET['accion']!="EDITAR") {
				echo "
				<select name='nivel' id='nivel' class='selectMed' disabled>
					<option value=''></option>
				</select>";
			} else {
				echo "
				<select name='nivel' id='nivel' class='selectMed'>
					<option value=''></option>";
						getNInstruccion($field['Nivel'], $field['CodGradoInstruccion'], 0);
						echo "
				</select>";
			}
			echo "
		</td>
	</tr>
	<tr>
	  <td class='tagForm'>Area:</td>
	  <td>
			<select name='area' id='area' class='selectMed' onchange='getProfesiones(this.form);'>
				<option value=''></option>";
					getMiscelaneos($field['Area'], 'AREA', 0);
					echo "
			</select>
		</td>
	  <td class='tagForm'>Profesi&oacute;n:</td>
	  <td>";
			if ($_GET['accion']!="EDITAR") {
				echo "
				<select name='profesion' id='profesion' class='selectMed' disabled>
					<option value=''></option>
				</select>";
			} else {
				echo "
				<select name='profesion' id='profesion' class='selectMed'>
					<option value=''></option>";
						getProfesiones($field['CodProfesion'], $field['CodGradoInstruccion'], $field['Area'], 0);
						echo "
				</select>";
			}
			echo "
		</td>
	</tr>
	<tr>
	  <td class='tagForm'>Centro de Estudio:</td>
	  <td colspan='3'>
			<input name='codcentro' type='text' id='codcentro' size='5' maxlength='3' value='".$field['CodCentroEstudio']."' />
			<input name='nomcentro' type='text' id='nomcentro' size='75' value='".$field['Descripcion']."' readonly />
			<input name='bt_examinar' type='button' id='bt_examinar' value='...' onclick='cargarVentana(this.form, \"lista_centros_estudios.php?limit=0&flag_estudio=S&flag_curso=S\", \"height=500, width=800, left=200, top=200, resizable=yes\");' />*
		</td>
	</tr>
	<tr>
		<td class='tagForm'>Desde:</td>
	  <td><input name='fdesde' type='text' id='fdesde' size='15' maxlength='10' value='$fdesde' />*<i>(dd-mm-yyyy)</i></td>
		<td class='tagForm'>Hasta:</td>
	  <td><input name='fhasta' type='text' id='fhasta' size='15' maxlength='10' value='$fhasta' />*<i>(dd-mm-yyyy)</i></td>
	</tr>
	<tr>
	  <td class='tagForm'>Col. Profesional:</td>
	  <td>
			<select name='colegiatura' id='colegiatura' class='selectMed' onchange='setColegiatura(this.form);'>
				<option value=''></option>";
					getMiscelaneos($field['Colegiatura'], 'COLEGIO', 0);
					echo "
			</select>
		</td>
    <td class='tagForm'>Nro. Colegiatura:</td>
    <td><input name='ncolegiatura' type='text' id='ncolegiatura' size='15' maxlength='9' value='".$field['NroColegiatura']."' /></td>
	</tr>
  <tr>
    <td class='tagForm'>Observaciones:</td>
    <td colspan='3'><textarea name='observaciones' id='observaciones' rows='2' cols='100'>".($field['Observaciones'])."</textarea></td>
  </tr>
</table>
<center>
<input type='submit' value='Guardar Registro' />
<input type='button' value='Resetear' onclick='resetEstudio(this.form);' />
</center>
</form>

<form name='frmtabla'>
<table width='750' class='tblBotones'>
 <tr>
	<td align='right'>
		<input name='btEditar' type='button' class='btLista' id='btEditar' value='Editar' onclick='optPostulante(this.form, \"EDITAR\");' />
		<input name='btBorrar' type='button' class='btLista' id='btBorrar' value='Borrar' onclick='optPostulante(this.form, \"ELIMINAR\");' />
	</td>
 </tr>
</table>";

//	CONSULTO LA TABLA
$sql="SELECT rh_postulantes_instruccion.Secuencia, rh_gradoinstruccion.Descripcion, rh_postulantes_instruccion.CodProfesion, rh_centrosestudios.Descripcion, rh_postulantes_instruccion.FechaDesde, rh_postulantes_instruccion.FechaHasta FROM rh_gradoinstruccion, rh_centrosestudios, rh_postulantes_instruccion WHERE (rh_gradoinstruccion.CodGradoInstruccion=rh_postulantes_instruccion.CodGradoInstruccion) AND (rh_centrosestudios.CodCentroEstudio=rh_postulantes_instruccion.CodCentroEstudio) AND (rh_postulantes_instruccion.Postulante='".$_GET['registro']."') ORDER BY rh_postulantes_instruccion.FechaDesde";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
//	MUESTRO LA TABLA
echo "
<input type='hidden' name='sec' id='sec' />	
<table class='tblLista'>
  <tr class='trListaHead'>
    <th width='50' scope='col'>#</th>
    <th width='100' scope='col'>Grado de Instrucci&oacute;n</th>
    <th width='150' scope='col'>Profesi&oacute;n</th>
    <th width='250' scope='col'>Centro de Estudio</th>
    <th width='86' scope='col'>Desde</th>
    <th width='86' scope='col'>Hasta</th>
  </tr>";
for ($i=1; $i<=$rows; $i++) {
	$field=mysql_fetch_array($query);
	list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaDesde']); $fdesde=$d."-".$m."-".$a; if ($fdesde=="00-00-0000") $fdesde="";
	list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaHasta']); $fhasta=$d."-".$m."-".$a; if ($fhasta=="00-00-0000") $fhasta="";
	//
	$sql="SELECT Descripcion FROM rh_profesiones WHERE CodProfesion='$field[2]'";
	$query1=mysql_query($sql) or die ($sql.mysql_error());
	$rows1=mysql_num_rows($query1);
	if ($rows1!=0) $field1=mysql_fetch_array($query1);
	//
	echo "
	<tr class='trListaBody' onclick='mClk(this, \"sec\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field[0]."'>
		<td align='center'>".$field[0]."</td>
	  	<td>".($field[1])."</td>
		<td>".($field1[0])."</td>
		<td>".($field[3])."</td>
		<td align='center'>".$fdesde."</td>
		<td align='center'>".$fhasta."</td>
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