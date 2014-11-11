<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="../js/funciones.js"></script>
<script type="text/javascript" language="javascript" src="../js/fscript.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Listado de Centro de Costos</td>
		<td align="right"><a class="cerrar" href="#" onclick="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="listado_centro_costos.php" method="POST">
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="cod" id="cod" value="<?=$cod?>" />
<input type="hidden" name="nom" id="nom" value="<?=$nom?>" />
<input type="hidden" name="dependencia" id="dependencia" value="<?=$dependencia?>" />
<table width="800" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">Filtro: <input type="text" name="filtro" id="filtro" value="<?=$filtro?>" size="30" /></td>
	</tr>
</table>

<table align="center"><tr><td align="center"><div style="overflow:scroll; width:800px; height:700px;">
<table width="100%" class="tblLista">
	<tr class="trListaHead">
		<th width="75" scope="col">Centro</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="175" scope="col">Grupo</th>
		<th width="175" scope="col">Sub-Grupo</th>
	</tr>
	<?php
	$filtro = trim($filtro);
	if ($filtro != "") $where = "AND (mcc.CodCentroCosto LIKE '%".$filtro."%' OR 
									  mcc.Descripcion LIKE '%".$filtro."%' OR 
									  sgcc.Descripcion LIKE '%".$filtro."%' OR 
									  gcc.Descripcion LIKE '%".$filtro."%')";
	if ($dependencia != "") $where .= " AND (mcc.CodDependencia = '".$dependencia."')";
	//	CONSULTO LA TABLA
	$sql = "SELECT
				mcc.*,
				sgcc.Descripcion AS NomSubGrupo,
				gcc.Descripcion AS NomGrupo
			FROM
				ac_mastcentrocosto mcc
				INNER JOIN ac_subgrupocentrocosto sgcc ON (mcc.CodSubGrupoCentroCosto = sgcc.CodSubGrupoCentroCosto AND 
														   mcc.CodGrupoCentroCosto = sgcc.CodGrupoCentroCosto)
				INNER JOIN ac_grupocentrocosto gcc ON (mcc.CodGrupoCentroCosto = gcc.CodGrupoCentroCosto)
			WHERE mcc.Estado = 'A' $where";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {
		?>
		<tr class="trListaBody" onclick="selListado('<?=$field['CodCentroCosto']?>', '<?=($field["Abreviatura"])?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$field['CodCentroCosto']?>">
            <td align="center"><?=$field['CodCentroCosto']?></td>
            <td><?=($field['Descripcion'])?></td>
            <td><?=($field['NomGrupo'])?></td>
            <td><?=($field['NomSubGrupo'])?></td>
        </tr>
		<?
	}
	?>
</table>
</div></td></tr></table>
</form>
<script type="text/javascript" language="javascript">
	totalRegistros(parseInt(<?=$rows?>));
</script>
</body>
</html>