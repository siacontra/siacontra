<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
extract($_POST);
extract($_GET);
//	------------------------------------
include("fphp.php");
connect();
//	------------------------------------
if ($fbuscar ) {
	$filtro .= " AND (cs.Codigo LIKE '%".$fbuscar."%' OR
					  cs.Descripcion LIKE '%".($fbuscar)."%' OR
					  cm.CommodityMast LIKE '%".($fbuscar)."%' OR
					  cm.Descripcion LIKE '%".($fbuscar)."%')";
}
if ($clasificacion != "") {
	$filtro_clasificacion = " AND cm.Clasificacion = '".$clasificacion."'";
}
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
		<td class="titulo">Listado de Commodities</td>
		<td align="right"><a class="cerrar" href="#" onclick="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="listado_commodities.php" method="POST">
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="cod" id="cod" value="<?=$cod?>" />
<input type="hidden" name="nom" id="nom" value="<?=$nom?>" />
<input type="hidden" name="opcion" id="opcion" value="<?=$opcion?>" />
<input type="hidden" name="accion" id="accion" value="<?=$accion?>" />
<input type="hidden" name="detalle" id="detalle" value="<?=$detalle?>" />
<input type="hidden" name="php" id="php" value="<?=$php?>" />
<input type="hidden" name="tipo" id="tipo" value="<?=$tipo?>" />
<input type="hidden" name="clasificacion" id="clasificacion" value="<?=$clasificacion?>" />

<table width="800" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">Buscar: <input type="text" name="fbuscar" id="fbuscar" size="40" value="<?=$fbuscar?>" /></td>
	</tr>
</table>

<table align="center"><tr><td align="center"><div style="overflow:scroll; width:800px; height:650px;">
<table width="100%" class="tblLista">
	<tr class="trListaHead">
		<th width="100" scope="col">Commodity</th>
		<th scope="col">Descripci&oacute;n</th>
	</tr>
	<?php
	//	CONSULTO LA TABLA
	$sql = "SELECT
				cm.Clasificacion,
				cm.Descripcion AS NomCommodity,
				cs.*
			FROM
				lg_commoditysub cs
				INNER JOIN lg_commoditymast cm ON (cs.CommodityMast = cm.CommodityMast $filtro_clasificacion)
			WHERE 1 $filtro
			ORDER BY cs.CommodityMast, cs.CommoditySub";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {
		if ($grupo != $field['CommodityMast']) {
			$grupo = $field['CommodityMast'];
			?><tr class="trListaBody2"><td colspan="2"><?=($field['NomCommodity'])?></td></tr><?
		}
		
		if ($opcion == "insertarLineaListadoItem") {
			?>
            <tr class="trListaBody" onclick="<?=$opcion?>('<?=$accion?>', '<?=$detalle?>', '<?=$php?>', '<?=$field['Codigo']?>', '<?=$tipo?>');" id="<?=$field['Codigo']?>">
                <td align="center"><?=$field['Codigo']?></td>
                <td><?=($field['Descripcion'])?></td>
            </tr>
			<?
		} 
		elseif ($opcion == "insertarLineaListadoItemOC") {
			?>
            <tr class="trListaBody" onclick="<?=$opcion?>('<?=$accion?>', '<?=$detalle?>', '<?=$php?>', '<?=$field['Codigo']?>', '<?=$tipo?>');" id="<?=$field['Codigo']?>">
                <td align="center"><?=$field['Codigo']?></td>
                <td><?=($field['Descripcion'])?></td>
            </tr>
			<?
		}  
		elseif ($opcion == "insertarLineaListadoItemOS") {
			?>
            <tr class="trListaBody" onclick="<?=$opcion?>('<?=$accion?>', '<?=$detalle?>', '<?=$php?>', '<?=$field['Codigo']?>');" id="<?=$field['CodItem']?>">
                <td align="center"><?=$field['Codigo']?></td>
                <td><?=($field['Descripcion'])?></td>
            </tr>
			<?
		} else {
			?>
            <tr class="trListaBody" onclick="selListado('<?=$field['Codigo']?>', '<?=($field["NomCommodity"])?>-<?=($field["Descripcion"])?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$field['Codigo']?>">
                <td align="center"><?=$field['Codigo']?></td>
                <td><?=($field['Descripcion'])?></td>
            </tr>
			<?
		}
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