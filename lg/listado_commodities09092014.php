<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_lg.php");
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
		<td class="titulo">Lista de Commodities</td>
		<td align="right"><a class="cerrar" href="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?
$MAXLIMIT=30;
//	------------------------------- 
if ($fbuscar != "") $filtro .= " AND (cs.Codigo LIKE '%".$fbuscar."%' OR cs.Descripcion LIKE '%".$fbuscar."%' OR cm.Clasificacion LIKE '%".$fbuscar."%' OR cm.Descripcion LIKE '%".$fbuscar."%') "; 
//	-------------------------------

//	-------------------------------
if ($ventana == "insertarCommodityRequerimiento" && $clasificacion != "") $filtro .= " AND cm.Clasificacion = '".$clasificacion."'";
//	-------------------------------

//	-------------------------------
if ($ventana == "insertarCommodityOrdenServicio" && $clasificacion != "") $filtro .= " AND cm.Clasificacion = '".$clasificacion."'";
//	-------------------------------

//	-------------------------------
if ($ventana == "insertarCommodityTransaccionEspecial" && $flagactivo == "true") $filtro .= " AND cm.Clasificacion = 'BIM'";
else if ($ventana == "insertarCommodityTransaccionEspecial") {
	$filtro .= " AND cm.Clasificacion <> 'BIM'";
	if ($clasificacion != "" && $cantdetalles != "0") {
		$filtro .= " AND cm.Clasificacion = '".$clasificacion."'";
	}
}
//	-------------------------------

//	CONSULTO LA TABLA PARA OBTENER EL TOTAL DE REGISTROS
$sql = "SELECT 
			cm.Clasificacion,
			cm.Descripcion AS NomCommodity,
			cs.*
		FROM 
			lg_commoditysub cs
			INNER JOIN lg_commoditymast cm ON (cs.CommodityMast = cm.CommodityMast)
		WHERE 1 $filtro
		ORDER BY cs.CommodityMast, cs.CommoditySub";
$query = mysql_query($sql) or die ($sql.mysql_error());
$registros = mysql_num_rows($query);
?>

<form name="frmentrada" id="frmentrada" action="listado_commodities.php?filtrar=" method="get">
<input type="hidden" name="limit" id="limit" value="<?=$limit?>" />
<input type="hidden" name="cod" id="cod" value="<?=$cod?>" />
<input type="hidden" name="nom" id="nom" value="<?=$nom?>" />
<input type="hidden" name="ventana" id="ventana" value="<?=$ventana?>" />
<input type="hidden" name="tabla" id="tabla" value="<?=$tabla?>" />
<input type="hidden" name="clasificacion" id="clasificacion" value="<?=$clasificacion?>" />
<input type="hidden" name="flagactivo" id="flagactivo" value="<?=$flagactivo?>" />
<input type="hidden" name="cantdetalles" id="cantdetalles" value="<?=$cantdetalles?>" />
<input type="hidden" name="codccosto" id="codccosto" value="<?=$codccosto?>" />
<input type="hidden" name="descripcion" id="descripcion" value="<?=$descripcion?>" />
<input type="hidden" name="fentrega" id="fentrega" value="<?=$fentrega?>" />

<table width="100%" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">Filtro: <input type="text" name="fbuscar" id="fbuscar" value="<?=$fbuscar?>" size="45" /></td>
		<td width="350">
			<table align="center">
				<tr>
					<td>
						<input name="btPrimero" type="button" id="btPrimero" value="&lt;&lt;" onclick="setLotes(this.form, 'P', <?=$registros?>, <?=$limit?>, '');" />
						<input name="btAtras" type="button" id="btAtras" value="&lt;" onclick="setLotes(this.form, 'A', <?=$registros?>, <?=$limit?>, '');" />
					</td>
					<td>Del</td><td><div id="desde"></div></td>
					<td>Al</td><td><div id="hasta"></div></td>
					<td>
						<input name="btSiguiente" type="button" id="btSiguiente" value="&gt;" onclick="setLotes(this.form, 'S', <?=$registros?>, <?=$limit?>, '');" />
						<input name="btUltimo" type="button" id="btUltimo" value="&gt;&gt;" onclick="setLotes(this.form, 'U', <?=$registros?>, <?=$limit?>, '');" />
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<input type="hidden" name="registro" id="registro" />

<table width="100%" class="tblLista">
	<tr class="trListaHead">
		<th width="100" scope="col">Commodity</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="75" scope="col">Estado</th>
	</tr>
	<?php
	//	CONSULTO LA TABLA
	$sql = "SELECT 
				cm.Clasificacion,
				cm.Descripcion AS NomCommodity,
				cs.*
			FROM 
				lg_commoditysub cs
				INNER JOIN lg_commoditymast cm ON (cs.CommodityMast = cm.CommodityMast)
			WHERE 1 $filtro
			ORDER BY cs.CommodityMast, cs.CommoditySub 
			LIMIT $limit, $MAXLIMIT";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {
		$status = printValores("ESTADO", $field['Estado']);
		
		if ($grupo != $field['CommodityMast']) {
			$grupo = $field['CommodityMast'];
			?><tr class="trListaBody2"><td colspan="3"><?=($field['NomCommodity'])?></td></tr><?
		}
		
		if ($ventana == "insertarCommodityOrden") {
			?>
			<tr class="trListaBody" onclick="mClk(this, 'registro'); insertarItemOrden(this.id, '<?=$ventana?>');" id="<?=$field['Codigo']?>">
                <td align="center"><?=$field['Codigo']?></td>
                <td><?=($field['Descripcion'])?></td>
                <td align='center'><?=$status?></td>
			</tr>
			<?
		} elseif ($ventana == "insertarCommodityOrdenServicio") {
			?>
			<tr class="trListaBody" onclick="mClk(this, 'registro'); insertarItemOrdenServicio(this.id, '<?=$ventana?>', '<?=$codccosto?>', '<?=$descripcion?>', '<?=$fentrega?>');" id="<?=$field['Codigo']?>">
                <td align="center"><?=$field['Codigo']?></td>
                <td><?=($field['Descripcion'])?></td>
                <td align='center'><?=$status?></td>
			</tr>
			<?
		} elseif ($ventana == "insertarCommodityRequerimiento") {
			?>
			<tr class="trListaBody" onclick="mClk(this, 'registro'); insertarItemRequerimiento(this.id, '<?=$ventana?>');" id="<?=$field['Codigo']?>">
                <td align="center"><?=$field['Codigo']?></td>
                <td><?=($field['Descripcion'])?></td>
                <td align='center'><?=$status?></td>
			</tr>
			<?
		} elseif ($ventana == "insertarCommodityTransaccionEspecial") {
			?>
			<tr class="trListaBody" onclick="mClk(this, 'registro'); insertarCommodityTransaccionEspecial(this.id, '<?=$ventana?>', '<?=$field['Clasificacion']?>');" id="<?=$field['Codigo']?>">
                <td align="center"><?=$field['Codigo']?></td>
                <td><?=($field['Descripcion'])?></td>
                <td align='center'><?=$status?></td>
			</tr>
			<?
		} else {
			?>
			<tr class="trListaBody" onclick="mClk(this, 'registro'); selListado('<?=($field["Descripcion"])?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$field['Codigo']?>">
                <td align="center"><?=$field['Codigo']?></td>
                <td><?=($field['Descripcion'])?></td>
                <td align='center'><?=$status?></td>
			</tr>
			<?
		}
	}
	?>
</table>

</form>

<script type="text/javascript" language="javascript">
	totalLista(<?=$registros?>);
	totalLotes(<?=$registros?>, <?=$rows?>, <?=$limit?>)
</script>
</body>
</html>