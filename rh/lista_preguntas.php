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
$MAXLIMIT=30;
//	CONSULTO LA TABLA PARA OBTENER EL NUMERO DE REGISTROS TOTAL
if ($filtro!="") 
	$sql="SELECT rh_encuesta_preguntas.Pregunta, rh_encuesta_preguntas.Descripcion, mastmiscelaneosdet.Descripcion AS Area, rh_encuesta_preguntas.Estado FROM rh_encuesta_preguntas, mastmiscelaneosdet WHERE (mastmiscelaneosdet.CodDetalle=rh_encuesta_preguntas.Area AND mastmiscelaneosdet.CodMaestro='AREACLIMA') AND (rh_encuesta_preguntas.Pregunta LIKE '%$filtro%' OR rh_encuesta_preguntas.Descripcion LIKE '%$filtro%' OR rh_encuesta_preguntas.Estado LIKE '%$filtro%' OR mastmiscelaneosdet.Descripcion LIKE '%$filtro%') ORDER BY Area";
else 
	$sql="SELECT rh_encuesta_preguntas.Pregunta, rh_encuesta_preguntas.Descripcion, mastmiscelaneosdet.Descripcion AS Area, rh_encuesta_preguntas.Estado FROM rh_encuesta_preguntas, mastmiscelaneosdet WHERE (mastmiscelaneosdet.CodDetalle=rh_encuesta_preguntas.Area AND mastmiscelaneosdet.CodMaestro='AREACLIMA') ORDER BY Area";
$query=mysql_query($sql) or die ($sql.mysql_error());
$registros=mysql_num_rows($query);
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Listado de Preguntas</td>
		<td align="right"><a class="cerrar" href="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="lista_preguntas.php?limit=0" method="POST">
<input type="hidden" name="registro" id="registro" value="<?=$registro?>" />
<table width="600" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td width="250">
			<?php 
			echo "
			<table align='center'>
				<tr>
					<td>
						<input name='btPrimero' type='button' id='btPrimero' value='&lt;&lt;' onclick='setLotes(this.form, \"P\", $registros, ".$limit.");' />
						<input name='btAtras' type='button' id='btAtras' value='&lt;' onclick='setLotes(this.form, \"A\", $registros, ".$limit.");' />
					</td>
					<td>Del</td><td><div id='desde'></div></td>
					<td>Al</td><td><div id='hasta'></div></td>
					<td>
						<input name='btSiguiente' type='button' id='btSiguiente' value='&gt;' onclick='setLotes(this.form, \"S\", $registros, ".$limit.");' />
						<input name='btUltimo' type='button' id='btUltimo' value='&gt;&gt;' onclick='setLotes(this.form, \"U\", $registros, ".$limit.");' />
					</td>
				</tr>
			</table>";
			?>
		</td>
		<td align="center">
			<input name="filtro" type="text" id="filtro" size="30" value="<?=$filtro?>" /><input type="submit" value="Buscar" />
		</td>
	</tr>
</table>

<table width="600" class="tblLista">
  <tr class="trListaHead">
		<th width="25" scope="col">&nbsp;</th>
		<th width="75" scope="col">Pregunta</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="75" scope="col">Estado</th>
  </tr>
	<?php 
	if ($registros!=0) {
		//	CONSULTO LA TABLA
		if ($filtro!="") 
			$sql="SELECT rh_encuesta_preguntas.Pregunta, rh_encuesta_preguntas.Descripcion, mastmiscelaneosdet.Descripcion AS Area, rh_encuesta_preguntas.Estado FROM rh_encuesta_preguntas, mastmiscelaneosdet WHERE (mastmiscelaneosdet.CodDetalle=rh_encuesta_preguntas.Area AND mastmiscelaneosdet.CodMaestro='AREACLIMA') AND (rh_encuesta_preguntas.Pregunta LIKE '%$filtro%' OR rh_encuesta_preguntas.Descripcion LIKE '%$filtro%' OR rh_encuesta_preguntas.Estado LIKE '%$filtro%' OR mastmiscelaneosdet.Descripcion LIKE '%$filtro%') ORDER BY mastmiscelaneosdet.Descripcion, rh_encuesta_preguntas.Pregunta LIMIT $limit, $MAXLIMIT";
		else 
			$sql="SELECT rh_encuesta_preguntas.Pregunta, rh_encuesta_preguntas.Descripcion, mastmiscelaneosdet.Descripcion AS Area, rh_encuesta_preguntas.Estado FROM rh_encuesta_preguntas, mastmiscelaneosdet WHERE (mastmiscelaneosdet.CodDetalle=rh_encuesta_preguntas.Area AND mastmiscelaneosdet.CodMaestro='AREACLIMA') ORDER BY mastmiscelaneosdet.Descripcion, rh_encuesta_preguntas.Pregunta LIMIT $limit, $MAXLIMIT";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		//	MUESTRO LA TABLA
		for ($i=1; $i<=$rows; $i++) {
			$field=mysql_fetch_array($query);
			if ($area!=$field['Area']) { $area=$field['Area']; echo "<tr class='trListaBody2'><td colspan='2'></td><td colspan='2'>".$field['Area']."</td></tr>"; }
			echo "
			<tr class='trListaBody' onmouseover='mOvr(this);' onmouseout='mOut(this);'>
				<td align='center'><input type='checkbox' name='$i' value='".$field['Pregunta']."' /></td>
				<td align='center'>".$field['Pregunta']."</td>
				<td>".$field['Descripcion']."</td>
				<td align='center'>".$field['Estado']."</td>
			</tr>";
		}
	}
	$rows=(int)$rows;
	echo "
	<script type='text/javascript' language='javascript'>
		totalLista($registros);
		totalLotes($registros, $rows, ".$limit.");
	</script>";
	?>
</table>
<center><input type="button" name="btAceptar" id="btAceptar" value="Agregar Preguntas" onclick="cargarPreguntas('<?=$pagina?>', '<?=$target?>');" /></center>
<input type="hidden" name="filas" id="filas" value="<?=$rows?>" />
</form>
</body>
</html>