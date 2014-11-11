<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../../index.php");
//	------------------------------------
extract($_POST);
extract($_GET);
//	------------------------------------
include("../fphp.php");
//	------------------------------------
if ($filtrar == "default") {
	$fOrdenar = "cc.CodCentroCosto";
	$maxlimit = $_SESSION["MAXLIMIT"];
}
if ($fBuscar != "") {
	$cbuscar = "checked";
	$filtro.=" AND (cc.CodCentroCosto LIKE '%".$fBuscar."%' OR
				    cc.Descripcion LIKE '%".$fBuscar."%' OR
				    sgcc.Descripcion LIKE '%".$fBuscar."%' OR
				    gcc.Descripcion LIKE '%".$fBuscar."%')";
} else $dBuscar = "disabled";
if ($CodDependencia != "") $filtro .= " AND (cc.CodDependencia = '".$CodDependencia."')";
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
<link type="text/css" rel="stylesheet" href="../../css/custom-theme/jquery-ui-1.8.16.custom.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="../../css/estilo.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="../../css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" src="../../js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="../../js/fscript.js" charset="utf-8"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Listado de Centro de Costos</td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="listado_centro_costos.php?" method="post">
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="cod" id="cod" value="<?=$cod?>" />
<input type="hidden" name="nom" id="nom" value="<?=$nom?>" />
<input type="hidden" name="campo3" id="campo3" value="<?=$campo3?>" />
<input type="hidden" name="campo4" id="campo4" value="<?=$campo4?>" />
<input type="hidden" name="ventana" id="ventana" value="<?=$ventana?>" />
<input type="hidden" name="seldetalle" id="seldetalle" value="<?=$seldetalle?>" />
<input type="hidden" name="CodDependencia" id="CodDependencia" value="<?=$CodDependencia?>" />
<div class="divBorder" style="width:800px;">
<table width="800" class="tblFiltro">
	<tr>
		<td align="right" width="125">Buscar:</td>
        <td>
            <input type="checkbox" <?=$cbuscar?> onclick="chkFiltro(this.checked, 'fbuscar');" />
            <input type="text" name="fbuscar" id="fbuscar" style="width:200px;" value="<?=$fBuscar?>" <?=$dBuscar?> />
		</td>
		<td align="right" width="125">Ordenar Por:</td>
		<td>
            <input type="checkbox" <?=$cOrdenar?> onclick="this.checked=!this.checked;" />
            <select name="fordenar" id="fordenar" style="width:100px;" <?=$dordenar?>>
                <?=loadSelectGeneral("ORDENAR-CCOSTO", $fOrdenar, 0)?>
            </select>
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<center>
<table width="800" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
	</tr>
</table>

<div style="overflow:scroll; width:800px; height:300px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
		<th width="50" scope="col">Centro</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="175" scope="col">Grupo</th>
		<th width="90" scope="col">Sub-Grupo</th>
	</tr>
	</thead>
	<?php
	if ($filtroDependencia != "S") {
		//	consulto todos
		$sql = "SELECT
					cc.*,
					sgcc.Descripcion AS NomSubGrupo,
					gcc.Descripcion AS NomGrupo
				FROM
					ac_mastcentrocosto cc
					INNER JOIN ac_subgrupocentrocosto sgcc ON (cc.CodSubGrupoCentroCosto = sgcc.CodSubGrupoCentroCosto AND 
															   cc.CodGrupoCentroCosto = sgcc.CodGrupoCentroCosto)
					INNER JOIN ac_grupocentrocosto gcc ON (cc.CodGrupoCentroCosto = gcc.CodGrupoCentroCosto)
				WHERE cc.Estado = 'A' $filtro";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		$rows_total = mysql_num_rows($query);
		
		//	consulto lista
		$sql = "SELECT
					cc.*,
					sgcc.Descripcion AS NomSubGrupo,
					gcc.Descripcion AS NomGrupo
				FROM
					ac_mastcentrocosto cc
					INNER JOIN ac_subgrupocentrocosto sgcc ON (cc.CodSubGrupoCentroCosto = sgcc.CodSubGrupoCentroCosto AND 
															   cc.CodGrupoCentroCosto = sgcc.CodGrupoCentroCosto)
					INNER JOIN ac_grupocentrocosto gcc ON (cc.CodGrupoCentroCosto = gcc.CodGrupoCentroCosto)
				WHERE cc.Estado = 'A' $filtro
				$orderby
				LIMIT ".intval($limit).", $maxlimit";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		$rows_lista = mysql_num_rows($query);
		
		//	MUESTRO LA TABLA
		while ($field = mysql_fetch_array($query)) {
			if ($ventana == "selListadoLista") {
				?><tr class="trListaBody" onclick="selListadoLista('<?=$seldetalle?>', '<?=$field["CodCentroCosto"]?>', '<?=$field["CodCentroCosto"]?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$field['CodCentroCosto']?>"><?
			}
			else {
				?><tr class="trListaBody" onclick="selListado2('<?=$field['CodCentroCosto']?>', '<?=($field["CodCentroCosto"])?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$field['CodCentroCosto']?>"><?
			}
			?>
				<td align="center"><?=$field['CodCentroCosto']?></td>
				<td><?=($field['Descripcion'])?></td>
				<td><?=($field['NomGrupo'])?></td>
				<td><?=($field['NomSubGrupo'])?></td>
			</tr>
			<?
		}
	}
	?>
</table>
</div>
<table width="800">
	<tr>
    	<td>
        	Mostrar: 
            <select name="maxlimit" style="width:50px;" onchange="this.form.submit();">
                <?=loadSelectGeneral("MAXLIMIT", $maxlimit, 0)?>
            </select>
        </td>
        <td align="right">
        	<?=paginacion(intval($rows_total), intval($rows_lista), intval($maxlimit), intval($limit));?>
        </td>
    </tr>
</table>
</center>
</form>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
	<?php
	if ($filtroDependencia == "S") {
		?>
		var CodDependencia = parent.$("#CodDependencia").val();
		$("#CodDependencia").val(CodDependencia);
		$("#frmentrada").submit();
		<?
	}
	?>
	totalRegistros(parseInt(<?=intval($rows_total)?>));
});	
</script>
</body>
</html>