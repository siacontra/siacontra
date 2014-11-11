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
	$maxlimit = $_SESSION["MAXLIMIT"];
	$fEstado = "A";
	$fOrdenar = "CodItem";
}
if ($fTipoEvaluacion != "") { $cTipoEvaluacion = "checked"; $filtro.=" AND (ea.TipoEvaluacion = '".$fTipoEvaluacion."')"; } else $dTipoEvaluacion = "disabled";
if ($fEstado != "") { $cEstado = "checked"; $filtro.=" AND (ea.Estado = '".$fEstado."')"; } else $dEstado = "disabled";
if ($fBuscar != "") {
	$cBuscar = "checked";
	$filtro .= " AND (ea.Competencia LIKE '%".$fBuscar."%' OR
					  ea.Descripcion LIKE '%".$fBuscar."%' OR
					  te.Descripcion LIKE '%".$fBuscar."%')";
} else $dBuscar = "disabled";
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
<!-- ui-dialog -->
<div id="cajaModal"></div>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Listado de Competencias</td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="listado_items.php?" method="post">
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="cod" id="cod" value="<?=$cod?>" />
<input type="hidden" name="nom" id="nom" value="<?=$nom?>" />
<input type="hidden" name="campo3" id="campo3" value="<?=$campo3?>" />
<input type="hidden" name="campo4" id="campo4" value="<?=$campo4?>" />
<input type="hidden" name="ventana" id="ventana" value="<?=$ventana?>" />
<input type="hidden" name="seldetalle" id="seldetalle" value="<?=$seldetalle?>" />
<div class="divBorder" style="width:100%;">
<table width="100%" class="tblFiltro">
	<tr>
		<td align="right" width="125">Tipo de Evaluaci&oacute;n:</td>
		<td>
			<input type="checkbox" <?=$cTipoEvaluacion?> onclick="chkFiltro(this.checked, 'fTipoEvaluacion');" />
            <select name="fTipoEvaluacion" id="fTipoEvaluacion" style="width:300px;" <?=$dTipoEvaluacion?>>
                <option value="">&nbsp;</option>
                <?=loadSelect("rh_tipoevaluacion", "TipoEvaluacion", "Descripcion", $fTipoEvaluacion, 0)?>
            </select>
		</td>
		<td align="right" width="125">Estado:</td>
		<td>
            <input type="checkbox" <?=$cEstado?> onclick="chkFiltro(this.checked, 'fEstado');" />
            <select name="fEstado" id="fEstado" style="width:100px;" <?=$dEstado?>>
                <option value="">&nbsp;</option>
                <?=loadSelectGeneral("ESTADO", $fEstado, 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td align="right">Buscar:</td>
		<td>
			<input type="checkbox" <?=$cBuscar?> onclick="chkFiltro(this.checked, 'fBuscar');" />
			<input type="text" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" style="width:295px;" <?=$dBuscar?> />
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<center>
<table width="100%" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
	</tr>
</table>

<div style="overflow:scroll; width:100%; height:300px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
		<th scope="col" width="40">Cod.</th>
		<th scope="col" align="left">Denominaci&oacute;n</th>
		<th scope="col" width="100">Nivel</th>
		<th scope="col" width="100">Tipo</th>
	</tr>
    </thead>
	<?php
	//	consulto todos
	$sql = "SELECT ef.Competencia
			FROM
				rh_evaluacionfactores ef
				INNER JOIN rh_evaluacionarea ea ON (ea.Area = ef.Area)
				INNER JOIN rh_tipoevaluacion te ON (te.TipoEvaluacion = ea.TipoEvaluacion)
				LEFT JOIN mastmiscelaneosdet md1 ON (md1.CodDetalle = ef.Nivel AND
													 md1.CodMaestro = 'NIVELCOMPE')
				LEFT JOIN mastmiscelaneosdet md2 ON (md2.CodDetalle = ef.TipoCompetencia AND
													 md2.CodMaestro = 'TIPOCOMPE')
			WHERE ef.Estado = 'A' $filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				ef.Competencia,
				ef.Descripcion,
				ef.Estado,
				te.TipoEvaluacion,
				te.Descripcion AS NomTipoEvaluacion,
				md1.Descripcion AS NomNivel,
				md2.Descripcion As NomTipoCompetencia
			FROM
				rh_evaluacionfactores ef
				INNER JOIN rh_evaluacionarea ea ON (ea.Area = ef.Area)
				INNER JOIN rh_tipoevaluacion te ON (te.TipoEvaluacion = ea.TipoEvaluacion)
				LEFT JOIN mastmiscelaneosdet md1 ON (md1.CodDetalle = ef.Nivel AND
													 md1.CodMaestro = 'NIVELCOMPE')
				LEFT JOIN mastmiscelaneosdet md2 ON (md2.CodDetalle = ef.TipoCompetencia AND
													 md2.CodMaestro = 'TIPOCOMPE')
			WHERE ef.Estado = 'A' $filtro
			ORDER BY TipoEvaluacion, Competencia
			LIMIT ".intval($limit).", ".intval($maxlimit);
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		if ($ventana == "competencias_plantilla_insertar") {
			?><tr class="trListaBody" onclick="listado_insertar_linea('<?=$seldetalle?>', 'accion=<?=$ventana?>&Competencia=<?=$field['Competencia']?>', '<?=$field['Competencia']?>');" id="<?=$field['Competencia']?>"><?
		}
		else {
			?><tr class="trListaBody" onclick="selListado2('<?=$field['Competencia']?>', '<?=($field["Descripcion"])?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$field['Competencia']?>"><?
		}
		?>
			<td align="center"><?=$field['Competencia']?></td>
			<td><?=htmlentities($field['Descripcion'])?></td>
			<td><?=htmlentities($field['NomNivel'])?></td>
			<td><?=htmlentities($field['NomTipoCompetencia'])?></td>
        </tr>
		<?
	}
	?>
</table>
</div>
<table width="100%">
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
	totalRegistros(parseInt(<?=$rows_lista?>));
</script>
</body>
</html>