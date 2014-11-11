<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_sia.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('03', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_sia.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Lista de Grupos de Centros de Costos</td>
		<td align="right"><a class="cerrar" href="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="lista_grupos_centros_costos.php" method="POST">
<input type="hidden" name="registro" id="registro" />
<table width="650" class="tblLista">
	<tr class="trListaHead">
		<th width="75" scope="col">C&oacute;digo</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="75" scope="col">Estado</th>
	</tr>
	<?php
	$filtro = trim($filtro); 
	if ($filtro != "") $filtro = "WHERE (agcc.CodGrupoCentroCosto LIKE '%".$filtro."%' OR agcc.Descripcion LIKE '%".$filtro."%' OR asgcc.CodSubGrupoCentroCosto LIKE '%".$filtro."%' OR asgcc.Descripcion LIKE '%".$filtro."%')"; 
	else $filtro = "";
	//	CONSULTO LA TABLA
	$sql = "SELECT
				agcc.CodGrupoCentroCosto,
				agcc.Descripcion AS NomGrupoCentroCosto,
				agcc.Estado AS EdoGrupo,				
				asgcc.CodSubGrupoCentroCosto,
				asgcc.Descripcion AS NomSubGrupoCentroCosto,
				asgcc.Estado AS EdoSubGrupo
			FROM
				ac_subgrupocentrocosto asgcc
				INNER JOIN ac_grupocentrocosto agcc ON (agcc.CodGrupoCentroCosto = asgcc.CodGrupoCentroCosto)
			$filtro
			ORDER BY
				CodGrupoCentroCosto, CodSubGrupoCentroCosto";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	for ($i=0; $i<$rows; $i++) {
		$field = mysql_fetch_array($query);
		if ($field['EdoGrupo'] == "A") $edogrupo = "Activo"; else $edogrupo = "Inactivo";
		if ($field['EdoSubGrupo'] == "A") $edosubgrupo = "Activo"; else $edosubgrupo = "Inactivo";
		
		if ($agrupa != $field['CodGrupoCentroCosto']) {
			$agrupa = $field['CodGrupoCentroCosto'];
			?>
			<tr class="trListaBody2">
				<td align="center"><?=$field['CodGrupoCentroCosto']?></td>
				<td><?=($field['NomGrupoCentroCosto'])?></td>
				<td align="center"><?=($edogrupo)?></td>
			</tr>
			<?
		}
		
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro'); selGrupoCentroCosto('<?=$field['CodGrupoCentroCosto']?>', '<?=$field['NomGrupoCentroCosto']?>', '<?=$field['NomSubGrupoCentroCosto']?>');" id="<?=$field['CodSubGrupoCentroCosto']?>">
			<td align="center"><?=$field['CodSubGrupoCentroCosto']?></td>
			<td><?=($field['NomSubGrupoCentroCosto'])?></td>
			<td align="center"><?=($edosubgrupo)?></td>
		</tr>
		<?
	}
	?>
</table>
</form>
</body>
</html>