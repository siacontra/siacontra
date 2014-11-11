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

//	CONSULTO LA TABLA
$sql="SELECT rh_postulantes_cursos.*, rh_cursos.Descripcion As Curso, rh_centrosestudios.Descripcion As Centro FROM rh_postulantes_cursos, rh_cursos, rh_centrosestudios WHERE (rh_postulantes_cursos.Postulante='".$_GET['registro']."') AND (rh_postulantes_cursos.CodCurso=rh_cursos.CodCurso) AND (rh_postulantes_cursos.CodCentroEstudio=rh_centrosestudios.CodCentroEstudio) ORDER BY rh_postulantes_cursos.Secuencia";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
//	MUESTRO LA TABLA
echo "<table align='center' width='752'>";
for ($i=1; $i<=$rows; $i++) {
	$field=mysql_fetch_array($query);
	list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaDesde']); $fdesde=$d."-".$m."-".$a; if ($fdesde=="00-00-0000") $fdesde="";
	list($a, $m, $d)=SPLIT( '[/.-]', $field['FechaHasta']); $fhasta=$d."-".$m."-".$a; if ($fhasta=="00-00-0000") $fhasta="";
	if ($field['FlagInstitucional']=="S") $flag="checked"; else $flag="";
	//
	echo "
	<tr class='trListaBody' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['Secuencia']."'>
		<td>
			<table style='width:750px; border-color:#000000;'>
				<tr>
					<td width='125' class='trListaHead'>Nro.:</td>
					<td>".$field['Secuencia']."</td>
					<td colspan='6' align='right'>Auspiciado por el organismo? &nbsp;<input type='checkbox' name='flag' id='flag' value='S' $flag disabled /></td>
				</tr>
				<tr>
				  <td class='trListaHead'>Curso:</td>
			    <td colspan='7'>".($field['Curso'])."</td>
				</tr>
				<tr>
				  <td class='trListaHead'>Centro de Estudio:</td>
				  <td colspan='7'>".($field['Centro'])."</td>
				</tr>
				<tr>
					<td class='trListaHead'>Desde:</td> <td width='100'>$fdesde</td>
					<td width='50' class='trListaHead'>Hasta:</td> <td width='100'>$fhasta</td>
					<td width='50' class='trListaHead'>Horas:</td> <td width='100'>".$field['TotalHoras']."</td>
				  <td width='50' class='trListaHead'>A&ntilde;os:</td>	<td>".$field['AniosVigencia']."</td>
				</tr>
			  <tr>
			    <td class='trListaHead'>Observaciones:</td>
			    <td colspan='7'>".($field['Observaciones'])."</td>
			  </tr>
			</table>
		</td>
	</tr>";
}
echo "
</table>";
?>

</body>
</html>