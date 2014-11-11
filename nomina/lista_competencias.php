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
		<td class="titulo">Lista de Competencias</td>
		<td align="right"><a class="cerrar"; href="javascript:window.close();">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?php
include("fphp.php");
connect();
$MAXLIMIT=30;
$filtro=trim($filtro); 
if ($filtro!="") $filtrado="WHERE (rt.Descripcion LIKE '%".$filtro."%' OR r.Descripcion LIKE '%".$filtro."%' OR md1.Descripcion LIKE '%".$filtro."%' OR md2.Descripcion LIKE '%".$filtro."%') AND r.FlagPlantilla='S'"; else $filtrado="WHERE (r.FlagPlantilla='S')";
//	CONSULTO LA TABLA PARA SABER EL TOTAL DE REGISTROS SOLAMENTE............
$sql="SELECT r.*, ea.TipoEvaluacion AS Evaluacion, rt.Descripcion AS DescripcionEvaluacion, md1.Descripcion AS NivelCompetencia, md2.Descripcion AS TipoCompetencia FROM rh_evaluacionfactores r INNER JOIN rh_evaluacionarea ea ON (r.Area=ea.Area) INNER JOIN rh_tipoevaluacion rt ON (ea.TipoEvaluacion=rt.TipoEvaluacion) INNER JOIN mastmiscelaneosdet md1 ON (md1.CodDetalle=r.Nivel AND md1.CodMaestro='NIVELCOMPE') INNER JOIN mastmiscelaneosdet md2 ON (md2.CodDetalle=r.TipoCompetencia AND md2.CodMaestro='TIPOCOMPE')";
$query=mysql_query($sql) or die ($sql.mysql_error());
$registros=mysql_num_rows($query);
?>

<form name="frmlista" id="frmlista" action="lista_competencias.php?filtro=<?=$filtro?>">
<table width="700" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td><input type="text" name="filtro" id="filtro" size="50" value="<?=$filtro?>" /></td>
		<td width="250">
			<table align="center">
				<tr>
					<td>
						<input name="btPrimero" type="button" id="btPrimero" value="&lt;&lt;" onclick="setLotes(this.form, 'P', <?=$registros?>, <?=$limit?>);" />
						<input name="btAtras" type="button" id="btAtras" value="&lt;" onclick="setLotes(this.form, 'A', <?=$registros?>, <?=$limit?>);" />
					</td>
					<td>Del</td><td><div id="desde"></div></td>
					<td>Al</td><td><div id="hasta"></div></td>
					<td>
						<input name="btSiguiente" type="button" id="btSiguiente" value="&gt;" onclick="setLotes(this.form, 'S', <?=$registros?>, <?=$limit?>);" />
						<input name="btUltimo" type="button" id="btUltimo" value="&gt;&gt;" onclick="setLotes(this.form, 'U', <?=$registros?>, <?=$limit?>);" />
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="accion" id="accion" value="<?=$accion?>" />
<table width="700" class="tblLista">
	<tr class="trListaHead">
		<th width="75" scope="col">Comp.</th>		
		<th scope="col">Descripci&oacute;n</th>
		<th width="150" scope="col">Tipo de Evaluaci&oacute;n</th>
		<th width="100" scope="col">Nivel</th>
		<th width="100" scope="col">Tipo</th>
		<th width="75" scope="col">Estado</th>
	</tr>
	<?php 
	if ($registros!=0) {
		//	CONSULTO LA TABLA
		$sql="SELECT r.*, ea.TipoEvaluacion AS Evaluacion, rt.Descripcion AS DescripcionEvaluacion, md1.Descripcion AS NivelCompetencia, md2.Descripcion AS TipoCompetencia FROM rh_evaluacionfactores r INNER JOIN rh_evaluacionarea ea ON (r.Area=ea.Area) INNER JOIN rh_tipoevaluacion rt ON (ea.TipoEvaluacion=rt.TipoEvaluacion) INNER JOIN mastmiscelaneosdet md1 ON (md1.CodDetalle=r.Nivel AND md1.CodMaestro='NIVELCOMPE') INNER JOIN mastmiscelaneosdet md2 ON (md2.CodDetalle=r.TipoCompetencia AND md2.CodMaestro='TIPOCOMPE') $filtrado ORDER BY r.Competencia";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		//	MUESTRO LA TABLA
		for ($i=0; $i<$rows; $i++) {
			$field=mysql_fetch_array($query);
			echo "
			<tr class='trListaBody' onclick='mClk(this, \"registro\"); selCompetencia(\"".$field['Descripcion']."\");' id='".$field['Competencia']."'>
				<td align='center'>".$field['Competencia']."</td>
				<td>".($field['Descripcion'])."</td>
				<td>".($field['DescripcionEvaluacion'])."</td>
				<td>".($field['NivelCompetencia'])."</td>
				<td>".($field['TipoCompetencia'])."</td>
				<td align='center'>".$status."</td>
			</tr>";
		}
	}
	$rows=(int)$rows;
	?>
	
</table>
</form>
<script type="text/javascript" language="javascript">
	totalLista(<?=$registros?>);
	totalLotes(<?=$registros?>, <?=$rows?>, <?=$limit?>);
</script>
</body>
</html>