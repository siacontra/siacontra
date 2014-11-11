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
		<td class="titulo">Lista de Cursos</td>
		<td align="right"><a class="cerrar"; href="javascript:window.close();">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
include("fphp.php");
connect();
$MAXLIMIT=30;
//	CONSULTO LA TABLA PARA SABER EL TOTAL DE REGISTROS SOLAMENTE............
if ($_GET['filtro']!="") $sql="SELECT CodCurso, Descripcion FROM rh_cursos WHERE (CodCurso LIKE '%".$_GET['filtro']."%' OR Descripcion LIKE '%".$_GET['filtro']."%')";
else $sql="SELECT CodCurso, Descripcion FROM rh_cursos";
$query=mysql_query($sql) or die ($sql.mysql_error());
$registros=mysql_num_rows($query);
?>
<?php echo "<form name='frmlista' id='frmlista' method='POST' action='lista_cursos.php?limit=0'>"; ?>
<input type="hidden" name="cod" id="cod" value="<?=$cod?>" />
<input type="hidden" name="nom" id="nom" value="<?=$nom?>" />
<input type="hidden" name="ventana" id="ventana" value="<?=$ventana?>" />
<input type="hidden" name="detalle" id="detalle" value="<?=$detalle?>" />
<table width="700" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td><input type="text" name="filtro" id="filtro" size="45" value="<?=trim($filtro)?>" /></td>
		<td width="250">
			<?php 
			echo "
			<table align='center'>
				<tr>
					<td>
						<input name='btPrimero' type='button' id='btPrimero' value='&lt;&lt;' onclick='setLotes(this.form, \"P\", $registros, ".$_GET['limit'].", \"".$_GET['ordenar']."\");' />
						<input name='btAtras' type='button' id='btAtras' value='&lt;' onclick='setLotes(this.form, \"A\", $registros, ".$_GET['limit'].", \"".$_GET['ordenar']."\");' />
					</td>
					<td>Del</td><td><div id='desde'></div></td>
					<td>Al</td><td><div id='hasta'></div></td>
					<td>
						<input name='btSiguiente' type='button' id='btSiguiente' value='&gt;' onclick='setLotes(this.form, \"S\", $registros, ".$_GET['limit'].", \"".$_GET['ordenar']."\");' />
						<input name='btUltimo' type='button' id='btUltimo' value='&gt;&gt;' onclick='setLotes(this.form, \"U\", $registros, ".$_GET['limit'].", \"".$_GET['ordenar']."\");' />
					</td>
				</tr>
			</table>";
			?>
		</td>
	</tr>
</table>
<input type="hidden" name="registro" id="registro" />
<table width="700" class="tblLista">
	<tr class="trListaHead">
		<th width="100" scope="col">C&oacute;digo</th>
		<th scope="col">Descripci&oacute;n</th>
	</tr>
	<?php 
	if ($registros!=0) {
		//	CONSULTO LA TABLA
		$filtro = trim($filtro);
		if ($filtro != "") $where = "WHERE (rh_cursos.CodCurso LIKE '%$filtro%' OR rh_cursos.Descripcion LIKE '%$filtro%' OR mastmiscelaneosdet.Descripcion LIKE '%$filtro%')";
		$sql = "SELECT 
					rh_cursos.*, 
					mastmiscelaneosdet.Descripcion AS Area
				FROM 
					rh_cursos 
					INNER JOIN mastmiscelaneosdet ON (rh_cursos.AreaCurso = mastmiscelaneosdet.CodDetalle AND mastmiscelaneosdet.CodMaestro = 'AREACURSO') 
					$where 
				ORDER BY rh_cursos.AreaCurso, rh_cursos.CodCurso";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		//	MUESTRO LA TABLA
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			if ($area != $field['AreaCurso']) echo "<tr class='trListaBody2'><td>&nbsp;</td><td>".$field['Area']."</td></tr>";
			
			if ($ventana == "listaEvaluacionNecesidad") {
				echo "
				<tr class='trListaBody' onclick='mClk(this, \"registro\"); selListadoDetalle(\"".$field['Descripcion']."\", \"".$cod."\", \"".$nom."\");' id='".$field[0]."'>
					<td align='center'>".$field['CodCurso']."</td>
					<td align='left'>".$field['Descripcion']."</td>
				</tr>";
			} else {
				echo "
				<tr class='trListaBody' onclick='mClk(this, \"registro\"); selCurso(\"".$field['Descripcion']."\");' id='".$field[0]."'>
					<td align='center'>".$field['CodCurso']."</td>
					<td align='left'>".$field['Descripcion']."</td>
				</tr>";
			}
			$area = $field['AreaCurso'];
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
</form>
</body>
</html>