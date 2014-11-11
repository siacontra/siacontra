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
$sql="SELECT Plantilla, Descripcion, Estado, UltimoUsuario, UltimaFecha FROM rh_encuesta_plantillas WHERE (Plantilla='".$registro."')";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
if ($rows!=0) $field=mysql_fetch_array($query);
if ($field['Estado']=="A") $activo="checked"; else $inactivo="checked";
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Plantilla de Preguntas de Clima Laboral | Ver Registro</td>
		<td align="right"><a class="cerrar" href="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<div style="width:700px" class="divFormCaption">Datos de la Plantilla</div>
<table width="700" class="tblForm">
  <tr>
    <td class="tagForm">Plantilla:</td>
    <td colspan="3"><input name="codigo" type="text" id="codigo" size="8" value="<?=$field['Plantilla']?>" readonly /></td>
  </tr>
  <tr>
    <td class="tagForm">Descripci&oacute;n:</td>
    <td colspan="3"><input name="descripcion" type="text" id="descripcion" size="100" maxlength="250" value="<?=$field['Descripcion']?>" readonly /></td>
  </tr>
  <tr>
    <td class="tagForm">Estado:</td>
    <td colspan="3">
		<input name="status" type="radio" value="A" <?=$activo?> /> Activo
		<input name="status" type="radio" value="I" <?=$inactivo?> /> Inactivo
	</td>
  </tr>
  <tr>
	  <td class="tagForm">&Uacute;ltima Modif.:</td>
	  <td colspan="3">
			<input name="ult_usuario" type="text" id="ult_usuario" size="30" value="<?=$field['UltimoUsuario']?>" readonly />
			<input name="ult_fecha" type="text" id="ult_fecha" size="25" value="<?=$field['UltimaFecha']?>" readonly />
		</td>
	</tr>
</table>

<br /><div class='divDivision'>Preguntas de la Plantilla</div><br />
<table width="700" class="tblLista">
  <tr class="trListaHead">
		<th width="75" scope="col">Pregunta</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="75" scope="col">Estado</th>
  </tr>
<?php
//	--------------------------------------------
if ($accion=="AGREGAR") {
	for ($i=1; $i<=$filas; $i++) {
		if ($_POST[$i]!="") {
			$sql="INSERT INTO rh_encuesta_plantillas_det (Plantilla, Pregunta) VALUES ('".$registro."', '".$_POST[$i]."')";
			$query=mysql_query($sql) or die ($sql.mysql_error());
		}
	}
}
elseif ($accion=="ELIMINAR") {
	$sql="DELETE FROM rh_encuesta_plantillas_det WHERE PLantilla='".$registro."' AND Pregunta='".$det."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
}
//	--------------------------------------------
$sql="SELECT rh_encuesta_plantillas_det.Pregunta, rh_encuesta_plantillas_det.Plantilla, rh_encuesta_preguntas.Descripcion, mastmiscelaneosdet.Descripcion AS Area, rh_encuesta_preguntas.Estado FROM rh_encuesta_plantillas_det, rh_encuesta_preguntas, mastmiscelaneosdet WHERE (rh_encuesta_plantillas_det.Plantilla='".$registro."') AND (rh_encuesta_plantillas_det.Pregunta=rh_encuesta_preguntas.Pregunta) AND (mastmiscelaneosdet.CodDetalle=rh_encuesta_preguntas.Area AND mastmiscelaneosdet.CodMaestro='AREACLIMA') ORDER BY mastmiscelaneosdet.Descripcion, rh_encuesta_plantillas_det.Pregunta";
$query=mysql_query($sql) or die ($sql.mysql_error());
$rows=mysql_num_rows($query);
//	MUESTRO LA TABLA
for ($i=0; $i<$rows; $i++) {
	$field=mysql_fetch_array($query);
	if ($area!=$field['Area']) { $area=$field['Area']; echo "<tr class='trListaBody2'><td>&nbsp;</td><td colspan='2'>".$field['Area']."</td></tr>"; }
	echo "
	<tr class='trListaBody'>
		<td align='center'>".$field['Pregunta']."</td>
		<td>".$field['Descripcion']."</td>
		<td align='center'>".$field['Estado']."</td>
	</tr>";
}
?>
</table>
<script type="text/javascript" language="javascript">
	totalPuestos(<?=$rows?>);
</script>

</form>
</body>
</html>
