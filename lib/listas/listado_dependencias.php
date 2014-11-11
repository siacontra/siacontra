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
	$fordenar = "d.Estructura";
	$fedoreg = "A";
	$maxlimit = $_SESSION["MAXLIMIT"];
	$forganismo = $_SESSION["ORGANISMO_ACTUAL"];
}
if ($forganismo != "") { $corganismo = "checked"; $filtro.=" AND (d.CodOrganismo = '".$forganismo."')"; } else $dorganismo = "disabled";
if ($fordenar != "") { $cordenar = "checked"; $orderby = "ORDER BY d.CodOrganismo, $fordenar"; } else $dordenar = "disabled";
if ($fedoreg != "") { $cedoreg = "checked"; $filtro.=" AND (d.Estado = '".$fedoreg."')"; } else $dedoreg = "disabled";
if ($fbuscar != "") {
	$cbuscar = "checked";
	$filtro.=" AND (d.CodDependencia LIKE '%".$fbuscar."%' OR
					d.Dependencia LIKE '%".$fbuscar."%' OR
					d.Organismo LIKE '%".$fbuscar."%')";
} else $dbuscar = "disabled";
if ($FlagControlFiscal != "") $filtro.=" AND (d.FlagControlFiscal = '".$FlagControlFiscal."')";
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
		<td class="titulo">Listado de Dependencias</td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="listado_dependencias.php?" method="post">
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="cod" id="cod" value="<?=$cod?>" />
<input type="hidden" name="nom" id="nom" value="<?=$nom?>" />
<input type="hidden" name="campo3" id="campo3" value="<?=$campo3?>" />
<div class="divBorder" style="width:800px;">
<table width="800" class="tblFiltro">
	<tr>
		<td align="right" width="125">Organismo:</td>
        <td>
            <input type="checkbox" <?=$corganismo?> onclick="chkFiltro(this.checked, 'forganismo');" />
            <select name="forganismo" id="forganismo" style="width:300px;" <?=$dorganismo?>>
            	<option value="">&nbsp;</option>
                <?=loadSelect("mastorganismos", "CodOrganismo", "Organismo", $forganismo, 0)?>
            </select>
		</td>
		<td align="right">Ordenar Por:</td>
		<td>
            <input type="checkbox" <?=$cordenar?> onclick="this.checked=!this.checked;" />
            <select name="fordenar" id="fordenar" style="width:100px;" <?=$dordenar?>>
                <?=loadSelectGeneral("ORDENAR-DEPENDENCIA", $fordenar, 0)?>
            </select>
		</td>
	</tr>
    <tr>
		<td align="right">Buscar:</td>
        <td>
            <input type="checkbox" <?=$cbuscar?> onclick="chkFiltro(this.checked, 'fbuscar');" />
            <input type="text" name="fbuscar" id="fbuscar" style="width:200px;" value="<?=$fbuscar?>" <?=$dbuscar?> />
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
		<th scope="col" width="60">Dependencia</th>
		<th scope="col">Descripci&oacute;n</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				d.*,
				o.Organismo
			FROM
				mastdependencias d
				INNER JOIN mastorganismos o ON (d.CodOrganismo = o.CodOrganismo)
			WHERE 1 $filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				d.*,
				o.Organismo
			FROM
				mastdependencias d
				INNER JOIN mastorganismos o ON (d.CodOrganismo = o.CodOrganismo)
			WHERE 1 $filtro
			$orderby
			LIMIT ".intval($limit).", $maxlimit";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		if ($grupo != $field['CodOrganismo']) {
			$grupo = $field['CodOrganismo'];
			?>
            <tr class="trListaBody2">
                <td colspan="2"><?=$field['Organismo']?></td>
            </tr>
            <?
		}
		
		if ($ventana == "dependencias") {
			?>
			<tr class="trListaBody" onclick="selListado2('<?=$field['CodDependencia']?>', '<?=($field["Dependencia"])?>', '<?=$cod?>', '<?=$nom?>', '<?=($field["Estructura"])?>', '<?=$campo3?>');" id="<?=$field['CodDependencia']?>">
            <?
		}
		else {
			?>
			<tr class="trListaBody" onclick="selListado2('<?=$field['CodDependencia']?>', '<?=($field["Dependencia"])?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$field['CodDependencia']?>">
            <?
		}
		?>
			<td align="right"><?=$field['Estructura']?></td>
			<td><?=($field['Dependencia'])?></td>
		</tr>
		<?
	}
	?>
    </tbody>
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
	totalRegistros(parseInt(<?=$rows_total?>));
</script>
</body>
</html>