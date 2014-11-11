<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_sia.php");
connect();
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_lg.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Lista de Centros de Costos</td>
		<td align="right"><a class="cerrar" href="#" onclick="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="listado_centro_costos.php" method="POST">
<input type="hidden" name="cod" id="cod" value="<?=$cod?>" />
<input type="hidden" name="nom" id="nom" value="<?=$nom?>" />
<input type="hidden" name="ventana" id="ventana" value="<?=$ventana?>" />
<input type="hidden" name="detalle" id="detalle" value="<?=$detalle?>" />
<input type="hidden" name="seldetalle" id="seldetalle" value="<?=$seldetalle?>" />
<table width="800" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">Filtro: <input type="text" name="filtro" id="filtro" value="<?=$filtro?>" size="30" /></td>
	</tr>
</table>

<input type="hidden" name="registro" id="registro" />
<table width="800" class="tblLista">
	<tr class="trListaHead">
		<th width="75" scope="col">Centro</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="175" scope="col">Grupo</th>
		<th width="175" scope="col">Sub-Grupo</th>
		<th width="75" scope="col">Estado</th>
	</tr>
	<?php
	$filtro = trim($filtro); 
	if ($filtro != "") $filtro = "AND (mcc.CodCentroCosto LIKE '%".$filtro."%' OR mcc.Descripcion LIKE '%".$filtro."%' OR sgcc.Descripcion LIKE '%".$filtro."%' OR gcc.Descripcion LIKE '%".$filtro."%')";
	else $filtro = "";
	if ($dependencia != "" || $origen == "empleados") $filtro = "AND (mcc.CodDependencia = '".$dependencia."')";
	//	CONSULTO LA TABLA
	$sql = "SELECT
				mcc.*,
				sgcc.Descripcion AS NomSubGrupo,
				gcc.Descripcion AS NomGrupo
			FROM
				ac_mastcentrocosto mcc
				INNER JOIN ac_subgrupocentrocosto sgcc ON (mcc.CodSubGrupoCentroCosto = sgcc.CodSubGrupoCentroCosto AND mcc.CodGrupoCentroCosto = sgcc.CodGrupoCentroCosto)
				INNER JOIN ac_grupocentrocosto gcc ON (mcc.CodGrupoCentroCosto = gcc.CodGrupoCentroCosto)
			WHERE 1 $filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	for ($i=0; $i<$rows; $i++) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] == "A") $status = "Activo"; else $status = "Inactivo";
		
		if ($ventana == "lista") {
			?>
			<tr class="trListaBody" onclick="mClk(this, 'registro'); selListadoDetalle('<?=htmlentities($field["Descripcion"])?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$field['CodCentroCosto']?>">
				<td align="center"><?=$field['CodCentroCosto']?></td>
				<td><?=htmlentities($field['Descripcion'])?></td>
				<td><?=htmlentities($field['NomGrupo'])?></td>
				<td><?=htmlentities($field['NomSubGrupo'])?></td>
				<td align="center"><?=htmlentities($status)?></td>
			</tr>
			<?
		} elseif ($ventana == "listaFormularioDetalle") {
			?>
			<tr class="trListaBody" onclick="mClk(this, 'registro'); selListadoFormularioDetalle('<?=htmlentities($field["Descripcion"])?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$field['CodCentroCosto']?>">
				<td align="center"><?=$field['CodCentroCosto']?></td>
				<td><?=htmlentities($field['Descripcion'])?></td>
				<td><?=htmlentities($field['NomGrupo'])?></td>
				<td><?=htmlentities($field['NomSubGrupo'])?></td>
				<td align="center"><?=htmlentities($status)?></td>
			</tr>
			<?
		} elseif ($ventana == "selTransaccionCCosto") {
			?>
			<tr class="trListaBody" onclick="mClk(this, 'registro'); selTransaccionCCosto('<?=htmlentities($field["Descripcion"])?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$field['CodCentroCosto']?>">
				<td align="center"><?=$field['CodCentroCosto']?></td>
				<td><?=htmlentities($field['Descripcion'])?></td>
				<td><?=htmlentities($field['NomGrupo'])?></td>
				<td><?=htmlentities($field['NomSubGrupo'])?></td>
				<td align="center"><?=htmlentities($status)?></td>
			</tr>
			<?
		} elseif ($ventana == "listadoCentroCosto") {
			?>
			<tr class="trListaBody" onclick="mClk(this, 'registro'); listadoCentroCosto('<?=$field["CodCentroCosto"]?>', '<?=$seldetalle?>');" id="<?=$field['CodCentroCosto']?>">
				<td align="center"><?=$field['CodCentroCosto']?></td>
				<td><?=htmlentities($field['Descripcion'])?></td>
				<td><?=htmlentities($field['NomGrupo'])?></td>
				<td><?=htmlentities($field['NomSubGrupo'])?></td>
				<td align="center"><?=htmlentities($status)?></td>
			</tr>
			<?
		} else {
			?>
			<tr class="trListaBody" onclick="mClk(this, 'registro'); selListado('<?=htmlentities($field["Descripcion"])?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$field['CodCentroCosto']?>">
				<td align="center"><?=$field['CodCentroCosto']?></td>
				<td><?=htmlentities($field['Descripcion'])?></td>
				<td><?=htmlentities($field['NomGrupo'])?></td>
				<td><?=htmlentities($field['NomSubGrupo'])?></td>
				<td align="center"><?=htmlentities($status)?></td>
			</tr>
			<?
		}
	}
	?>
	<script type="text/javascript" language="javascript">
		totalLista(<?=$rows?>);
	</script>
</table>
</form>
</body>
</html>