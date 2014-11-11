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
$sql="SELECT rh_encuesta_detalle.Secuencia, rh_encuesta_detalle.Pregunta, rh_encuesta_preguntas.Descripcion, mastmiscelaneosdet.Descripcion AS Area FROM rh_encuesta_detalle, rh_encuesta_preguntas, mastmiscelaneosdet WHERE (rh_encuesta_detalle.Secuencia='".$registro."') AND (rh_encuesta_detalle.Pregunta=rh_encuesta_preguntas.Pregunta) AND (mastmiscelaneosdet.CodDetalle=rh_encuesta_preguntas.Area AND mastmiscelaneosdet.CodMaestro='AREACLIMA') ORDER BY mastmiscelaneosdet.Descripcion, rh_encuesta_detalle.Pregunta";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
//	MUESTRO LA TABLA
echo "
<table width='700' class='tblLista'>
  <tr class='trListaHead'>
		<th scope='col'>Descripci&oacute;n</th>
	</tr>";
for ($i=0; $i<$rows; $i++) {
	$field=mysql_fetch_array($query);
	if ($area!=$field['Area']) { $area=$field['Area']; echo "<tr class='trListaBody2'><td>".$field['Area']."</td></tr>"; }
	echo "
	<tr class='trListaBody' id='".$field['Pregunta']."'>
		<td>".$field['Descripcion']."</td>
	</tr>";
}
echo "
</table>";

?>
</body>
</html>