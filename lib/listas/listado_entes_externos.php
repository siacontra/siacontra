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
	$fordenar = "CodOrganismo, CodDependencia";
	$fedoreg = "A";
	$maxlimit = $_SESSION["MAXLIMIT"];
}
if ($fordenar != "") { $cordenar = "checked"; $orderby = "ORDER BY CodOrganismo, $fordenar"; } else $dordenar = "disabled";
if ($fbuscar != "") {
	$cbuscar = "checked";
	$filtro1.=" AND (oe.CodOrganismo LIKE '%".$fbuscar."%' OR
					oe.Organismo LIKE '%".$fbuscar."%')";
	$filtro2.=" AND (oe.CodOrganismo LIKE '%".$fbuscar."%' OR
					 oe.Organismo LIKE '%".$fbuscar."%' OR
					 de.CodDependencia LIKE '%".$fbuscar."%' OR
					 de.Dependencia LIKE '%".$fbuscar."%')";
} else $dbuscar = "disabled";
if ($CodDependencia != "") $filtro .= " AND (oe.CodDependencia = '".$CodDependencia."')";
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
<?php
if ($ventana == "filtrar") {
?>
<script type="text/javascript" charset="utf-8">
var CodDependencia = parent.$("#CodDependencia").val();
location.href = "listado_entes_externos.php?cod=<?=$cod?>&nom=<?=$nom?>&cod2=<?=$cod2?>&nom2=<?=$nom2?>&filtrar=<?=$filtrar?>&CodDependencia="+CodDependencia;
</script>
<?
}
?>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Listado de Entes Externos</td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="listado_entes_externos.php?" method="post">
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="cod" id="cod" value="<?=$cod?>" />
<input type="hidden" name="nom" id="nom" value="<?=$nom?>" />
<input type="hidden" name="cod2" id="cod2" value="<?=$cod2?>" />
<input type="hidden" name="nom2" id="nom2" value="<?=$nom2?>" />
<div class="divBorder" style="width:800px;">
<table width="800" class="tblFiltro">
	<tr>
		<td align="right">Buscar:</td>
        <td>
            <input type="checkbox" <?=$cbuscar?> onclick="chkFiltro(this.checked, 'fbuscar');" />
            <input type="text" name="fbuscar" id="fbuscar" style="width:200px;" value="<?=$fbuscar?>" <?=$dbuscar?> />
		</td>
		<td align="right">Ordenar Por:</td>
		<td>
            <input type="checkbox" <?=$cordenar?> onclick="this.checked=!this.checked;" />
            <select name="fordenar" id="fordenar" style="width:100px;" <?=$dordenar?>>
                <?=loadSelectGeneral("ORDENAR-DEPENDENCIA-EXTERNA", $fordenar, 0)?>
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
		<th scope="col" width="50">C&oacute;digo</th>
		<th scope="col">Descripci&oacute;n</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "(SELECT
				oe.CodOrganismo,
				oe.Organismo,
				'' AS CodDependencia,
				'' AS Dependencia,
				oe.Estado,
				'Organismo' AS Tipo
			 FROM pf_organismosexternos oe
			 WHERE oe.Estado = 'A' AND oe.FlagSujetoControl = 'S' $filtro1 $filtro)
			UNION
			(SELECT
				oe.CodOrganismo,
				oe.Organismo,
				de.CodDependencia,
				de.Dependencia,
				de.Estado,
				'Dependencia' AS Tipo
			 FROM
			 	pf_dependenciasexternas de
				INNER JOIN pf_organismosexternos oe ON (de.CodOrganismo = oe.CodOrganismo)
			 WHERE oe.Estado = 'A' AND de.Estado = 'A' AND oe.FlagSujetoControl = 'S' $filtro2 $filtro)";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "(SELECT
				oe.CodOrganismo,
				oe.Organismo,
				'' AS CodDependencia,
				'' AS Dependencia,
				oe.Estado,
				'Organismo' AS Tipo
			 FROM pf_organismosexternos oe
			 WHERE oe.Estado = 'A' AND oe.FlagSujetoControl = 'S' $filtro1 $filtro)
			UNION
			(SELECT
				oe.CodOrganismo,
				oe.Organismo,
				de.CodDependencia,
				de.Dependencia,
				de.Estado,
				'Dependencia' AS Tipo
			 FROM
			 	pf_dependenciasexternas de
				INNER JOIN pf_organismosexternos oe ON (de.CodOrganismo = oe.CodOrganismo)
			 WHERE oe.Estado = 'A' AND de.Estado = 'A' AND oe.FlagSujetoControl = 'S' $filtro2 $filtro)
			$orderby
			LIMIT ".intval($limit).", $maxlimit";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		if ($grupo != $field['CodOrganismo']) {
			$grupo = $field['CodOrganismo'];
			?>
			<tr class="trListaBody" onclick="selListado2('<?=$field['CodOrganismo']?>', '<?=($field["Organismo"])?>', '<?=$cod?>', '<?=$nom?>', '', '<?=$cod2?>', '', '<?=$nom2?>');" id="<?=$field['CodOrganismo']?><?=$field['CodDependencia']?>">
				<td align="center"><strong><?=$field['CodOrganismo']?></strong></td>
				<td><strong><?=$field['Organismo']?></strong></td>
			</tr>
			<?
		}
		
		if ($field['Tipo'] == "Dependencia") {
			?>
            <tr class="trListaBody" onclick="selListado2('<?=$field['CodOrganismo']?>', '<?=($field["Organismo"])?>', '<?=$cod?>', '<?=$nom?>', '<?=$field['CodDependencia']?>', '<?=$cod2?>', '<?=($field["Dependencia"])?>', '<?=$nom2?>');" id="<?=$field['CodOrganismo']?><?=$field['CodDependencia']?>">
                <td align="center"><?=$field['CodDependencia']?></td>
                <td><?=($field['Dependencia'])?></td>
            </tr>
			<?
		}
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
