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

if ($accion == "selCompetenciaEvaluacionDesempenio") $filtrado = "WHERE 1";
else $filtrado = "WHERE (r.FlagPlantilla = 'S')";

if ($filtro != "") $filtrado = "AND (rt.Descripcion LIKE '%".$filtro."%' OR r.Descripcion LIKE '%".$filtro."%' OR md1.Descripcion LIKE '%".$filtro."%' OR md2.Descripcion LIKE '%".$filtro."%')";
?>

<form name="frmlista" id="frmlista" action="lista_competencias.php?filtro=<?=$filtro?>">
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="accion" id="accion" value="<?=$accion?>" />

<table align="center" width="700">
	<tr><td align="right"><input type="button" value="Agregar" onclick="selCompetenciaEvaluacionDesempenio();" /></td></tr>
</table>

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
	//	CONSULTO LA TABLA
	$sql = "SELECT
				r.*,
				ea.TipoEvaluacion AS Evaluacion,
				rt.Descripcion AS DescripcionEvaluacion,
				md1.Descripcion AS NivelCompetencia,
				md2.Descripcion AS TipoCompetencia
			FROM
				rh_evaluacionfactores r
				LEFT JOIN rh_evaluacionarea ea ON (r.Area = ea.Area)
				LEFT JOIN rh_tipoevaluacion rt ON (ea.TipoEvaluacion = rt.TipoEvaluacion)
				LEFT JOIN mastmiscelaneosdet md1 ON (md1.CodDetalle = r.Nivel AND md1.CodMaestro = 'NIVELCOMPE')
				LEFT JOIN mastmiscelaneosdet md2 ON (md2.CodDetalle = r.TipoCompetencia AND md2.CodMaestro = 'TIPOCOMPE')
			$filtrado
			ORDER BY r.Competencia";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	//	MUESTRO LA TABLA
	for ($i=0; $i<$rows; $i++) {
		$field=mysql_fetch_array($query);
		if ($field['Estado']=="A") $status="Activo";
		elseif ($field['Estado']=="I") $status="Inactivo";
		
		if ($accion == "selCompetenciaEvaluacionDesempenio") {
			?>
			<tr class="trListaBody" onclick="mClkMulti(this);" id="row_<?=$field['Competencia']?>">
				<td align="center">
                	<input type="checkbox" name="competencia" id="<?=$field['Competencia']?>" value="<?=$field['Competencia']?>" style="display:none;" />
					<?=$field['Competencia']?>
				</td>
				<td><?=($field['Descripcion'])?></td>
				<td><?=($field['DescripcionEvaluacion'])?></td>
				<td><?=($field['NivelCompetencia'])?></td>
				<td><?=($field['TipoCompetencia'])?></td>
				<td align='center'><?=$status?></td>
			</tr>
			<?
		}
		else {
			?>
			<tr class="trListaBody" onclick="mClk(this, 'registro'); selCompetencia('<?=$field['Descripcion']?>');" id="<?=$field['Competencia']?>">
				<td align="center"><?=$field['Competencia']?></td>
				<td><?=($field['Descripcion'])?></td>
				<td><?=($field['DescripcionEvaluacion'])?></td>
				<td><?=($field['NivelCompetencia'])?></td>
				<td><?=($field['TipoCompetencia'])?></td>
				<td align='center'><?=$status?></td>
			</tr>
			<?
		}
	}
	?>
	
</table>
</form>
</body>
</html>