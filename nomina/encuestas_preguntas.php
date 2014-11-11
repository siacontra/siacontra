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

echo "
<form name='frmentrada' action='encuestas_preguntas.php' method='POST'>
<table width='700' class='tblBotones'>
	<tr>
		<td align='right'>
			<input name='btAgregarPlantilla' type='button' id='btAgregarPlantilla' value='Agregar Plantilla' onclick='cargarVentana(this.form, \"lista_plantillas.php?limit=0&registro=".$registro."\", \"height=500, width=800, left=200, top=200, resizable=yes\");' />
			<input name='btAgregarPregunta' type='button' id='btAgregarPregunta' value='Agregar Pregunta' onclick='cargarVentana(this.form, \"lista_preguntas.php?pagina=encuestas_preguntas.php?accion=AGREGAR-PREGUNTAS&registro=$registro&limit=0&target=iPreguntas\", \"height=500, width=800, left=200, top=200, resizable=yes\");' />
			<input name='btEliminarPregunta' type='button' id='btEliminarPregunta' value='Eliminar Pregunta' onClick='eliminarSubCargo(this.form, \"encuestas_preguntas.php?accion=ELIMINAR&registro=".$registro."\");' />
		</td>
	</tr>
</table>";

//	ELIMINO EL REGISTRO
if ($_GET['accion']=="ELIMINAR") {
	$sql="DELETE FROM rh_encuesta_detalle WHERE Secuencia='".$registro."' AND Pregunta='".$det."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
}
//	AGREGO PREGUNTAS
if ($_GET['accion']=="AGREGAR-PREGUNTAS") {
	for ($i=1; $i<=$filas; $i++) {
		if ($_POST[$i]!="") {
			$sql="INSERT INTO rh_encuesta_detalle (Secuencia, Pregunta) VALUES ('".$registro."', '".$_POST[$i]."')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
}
//	AGREGO PREGUNTAS
if ($_GET['accion']=="AGREGAR-PLANTILLA") {
	$sql="SELECT Pregunta FROM rh_encuesta_plantillas_det WHERE Plantilla='".$secuencia."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		$sql="INSERT INTO rh_encuesta_detalle (Secuencia, Pregunta) VALUES ('$registro', '".$field['Pregunta']."')";
		$query_insert=mysql_query($sql) or die ($sql.mysql_error());
	}
}
//	CONSULTO LA TABLA
$sql="SELECT rh_encuesta_detalle.Secuencia, rh_encuesta_detalle.Pregunta, rh_encuesta_preguntas.Descripcion, mastmiscelaneosdet.Descripcion AS Area FROM rh_encuesta_detalle, rh_encuesta_preguntas, mastmiscelaneosdet WHERE (rh_encuesta_detalle.Secuencia='".$registro."') AND (rh_encuesta_detalle.Pregunta=rh_encuesta_preguntas.Pregunta) AND (mastmiscelaneosdet.CodDetalle=rh_encuesta_preguntas.Area AND mastmiscelaneosdet.CodMaestro='AREACLIMA') ORDER BY mastmiscelaneosdet.Descripcion, rh_encuesta_detalle.Pregunta";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
//	MUESTRO LA TABLA
echo "
<input type='hidden' name='det' id='det' />
<table width='700' class='tblLista'>
  <tr class='trListaHead'>
		<th scope='col'>Descripci&oacute;n</th>
	</tr>";
for ($i=0; $i<$rows; $i++) {
	$field=mysql_fetch_array($query);
	if ($area!=$field['Area']) { $area=$field['Area']; echo "<tr class='trListaBody2'><td>".$field['Area']."</td></tr>"; }
	echo "
	<tr class='trListaBody' onclick='mClk(this, \"det\");' onmouseover='mOvr(this);' onmouseout='mOut(this);' id='".$field['Pregunta']."'>
		<td>".$field['Descripcion']."</td>
	</tr>";
}
echo "
</table>
<script type='text/javascript' language='javascript'>
	totalEncuestaPreguntas($rows);
</script>
</form>";


?>
</body>
</html>