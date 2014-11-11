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
	$fordenar = "i.CodImpuesto";
	$maxlimit = $_SESSION["MAXLIMIT"];
}
if ($fordenar != "") { $cordenar = "checked"; $orderby = "ORDER BY $fordenar"; } else $dordenar = "disabled";
if ($fbuscar != "") {
	$cbuscar = "checked";
	$filtro .= " AND (i.CodImpuesto LIKE '%".$fbuscar."%' OR
				    i.Descripcion LIKE '%".$fbuscar."%')";
} else $dbuscar = "disabled";
//if ($CodRegimenFiscal != "") $filtro .= "AND i.CodRegimenFiscal = '".$CodRegimenFiscal."'";
if ($CodRegimenFiscal != "") $filtro .= "AND (i.CodRegimenFiscal = '".$CodRegimenFiscal."' || i.CodRegimenFiscal = 'N'  )";
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
		<td class="titulo">Listado de Impuestos</td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="listado_impuestos.php?" method="post">
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="cod" id="cod" value="<?=$cod?>" />
<input type="hidden" name="nom" id="nom" value="<?=$nom?>" />
<input type="hidden" name="CodRegimenFiscal" id="CodRegimenFiscal" value="<?=$CodRegimenFiscal?>" />
<input type="hidden" name="ventana" id="ventana" value="<?=$ventana?>" />
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td align="right" width="125">Buscar:</td>
        <td>
            <input type="checkbox" <?=$cbuscar?> onclick="chkFiltro(this.checked, 'fbuscar');" />
            <input type="text" name="fbuscar" id="fbuscar" style="width:200px;" value="<?=$fbuscar?>" <?=$dbuscar?> />
		</td>
		<td align="right" width="125">Ordenar Por:</td>
		<td>
            <input type="checkbox" <?=$cordenar?> onclick="this.checked=!this.checked;" />
            <select name="fordenar" id="fordenar" style="width:100px;" <?=$dordenar?>>
                <?=loadSelectGeneral("ORDENAR-IMPUESTO", $fordenar, 0)?>
            </select>
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<center>
<table width="1000" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
	</tr>
</table>

<div style="overflow:scroll; width:1000px; height:225px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
		<th width="50" scope="col">C&oacute;digo</th>
		<th scope="col" align="left">Descripci&oacute;n</th>
		<th width="150" scope="col">R&eacute;gimen Fiscal</th>
		<th width="75" scope="col">Porcentaje</th>
		<th width="150" scope="col">Provisionar En</th>
		<th width="150" scope="col">Monto Imponible</th>
		<th width="50" scope="col">Signo</th>
	</tr>
	</thead>
	<?php
	//	consulto todos
	$sql = "SELECT
				i.*,
				rf.Descripcion AS NomRegimenFiscal
			FROM
				mastimpuestos i
				INNER JOIN ap_regimenfiscal rf ON (i.CodRegimenFiscal = rf.CodRegimenFiscal)
			WHERE i.Estado = 'A' $filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				i.*,
				rf.Descripcion AS NomRegimenFiscal
			FROM
				mastimpuestos i
				INNER JOIN ap_regimenfiscal rf ON (i.CodRegimenFiscal = rf.CodRegimenFiscal)
			WHERE i.Estado = 'A' $filtro
			$orderby
			LIMIT ".intval($limit).", ".intval($maxlimit);
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {
		if ($ventana == "obligacion_impuestos_insertar") {
			?><tr class="trListaBody" onclick="obligacion_impuestos_insertar('<?=$field['CodImpuesto']?>');" id="<?=$field['CodImpuesto']?>"><? 
		} else {
			?><tr class="trListaBody" onclick="selListado2('<?=$field['CodImpuesto']?>', '<?=($field["Descripcion"])?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$field['CodImpuesto']?>"><?
		}
        ?>
            <td align="center"><?=$field['CodImpuesto']?></td>
            <td><?=($field['Descripcion'])?></td>
            <td><?=($field['NomRegimenFiscal'])?></td>
            <td align="right"><?=($field['FactorPorcentaje'])?></td>
            <td><?=printValoresGeneral("PROVISIONAR", $field['FlagProvision'])?></td>
            <td><?=printValoresGeneral("IMPONIBLE", $field['FlagImponible'])?></td>
            <td align="center"><?=printValoresGeneral("SIGNO", $field['Signo'])?></td>
        </tr>
		<?
	}
	?>
</table>
</div>
<table width="1000">
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
	totalRegistros(parseInt(<?=$rows_total?>));
</script>
</body>
</html>
