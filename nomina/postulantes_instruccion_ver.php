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
$sql="SELECT rh_postulantes_instruccion.Secuencia, rh_gradoinstruccion.Descripcion, rh_postulantes_instruccion.CodProfesion, rh_centrosestudios.Descripcion, rh_postulantes_instruccion.FechaDesde, rh_postulantes_instruccion.FechaHasta FROM rh_gradoinstruccion, rh_centrosestudios, rh_postulantes_instruccion WHERE (rh_gradoinstruccion.CodGradoInstruccion=rh_postulantes_instruccion.CodGradoInstruccion) AND (rh_centrosestudios.CodCentroEstudio=rh_postulantes_instruccion.CodCentroEstudio) AND (rh_postulantes_instruccion.Postulante='".$_GET['registro']."') ORDER BY rh_postulantes_instruccion.FechaDesde";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
//	MUESTRO LA TABLA
echo "
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
	<tr class='trListaBody' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field[0]."'>
		<td align='center'>".$field[0]."</td>
	  	<td>".($field[1])."</td>
		<td>".($field1[0])."</td>
		<td>".($field[3])."</td>
		<td align='center'>".$fdesde."</td>
		<td align='center'>".$fhasta."</td>
	</tr>";
}
echo "</table>";
?>
</body>
</html>