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
}
if ($fCommodityMast != "") { $cCommodityMast = "checked"; $filtro.=" AND (cs.CommodityMast = '".$fCommodityMast."')"; } else $dCommodityMast = "disabled";
if ($fClasificacion != "") { $cClasificacion = "checked"; $filtro.=" AND (c.Clasificacion = '".$fClasificacion."')"; } else $dClasificacion = "disabled";
if ($fBuscar != "") { 
	$cBuscar = "checked"; 
	$filtro.=" AND (c.Descripcion LIKE '%".$fBuscar."%' OR
					cs.Codigo LIKE '%".$fBuscar."%' OR
					cs.Descripcion LIKE '%".$fBuscar."%' OR
					cs.cod_partida LIKE '%".$fBuscar."%' OR
					cs.CodCuenta LIKE '%".$fBuscar."%' OR
					cs.CodClasificacion LIKE '%".$fBuscar."%')";
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
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Listado de Commodities</td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="listado_commodities.php?" method="post">
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="cod" id="cod" value="<?=$cod?>" />
<input type="hidden" name="nom" id="nom" value="<?=$nom?>" />
<input type="hidden" name="campo3" id="campo3" value="<?=$campo3?>" />
<input type="hidden" name="campo4" id="campo4" value="<?=$campo4?>" />
<input type="hidden" name="ventana" id="ventana" value="<?=$ventana?>" />
<input type="hidden" name="seldetalle" id="seldetalle" value="<?=$seldetalle?>" />
<input type="hidden" name="PorClasificacion" id="PorClasificacion" value="<?=$PorClasificacion?>" />
</script>
<div class="divBorder" style="width:900px;">
<table width="900" class="tblFiltro">
	<tr>
		<td align="right">Commodity:</td>
		<td>
			<input type="checkbox" <?=$cCommodityMast?> onclick="chkFiltro(this.checked, 'fCommodityMast')" />
			<select name="fCommodityMast" id="fCommodityMast" style="width:305px;" <?=$dCommodityMast?>>
				<option value="">&nbsp;</option>
				<?=loadSelect("lg_commoditymast", "CommodityMast", "Descripcion", $fCommodityMast, 0)?>
			</select>
		</td>
		<td align="right">Clasificaci&oacute;n:</td>
		<td>
        	<?
			if ($PorClasificacion == "S") {
				?>
                <input type="checkbox" <?=$cClasificacion?> onclick="this.checked=!this.checked" />
                <select name="fClasificacion" id="fClasificacion" style="width:150px;" <?=$dClasificacion?>>
                    <?=loadSelect("lg_commodityclasificacion", "Clasificacion", "Descripcion", $fClasificacion, 0)?>
                </select>
                <?
			} else {
				?>
                <input type="checkbox" <?=$cClasificacion?> onclick="chkFiltro(this.checked, 'fClasificacion')" />
                <select name="fClasificacion" id="fClasificacion" style="width:150px;" <?=$dClasificacion?>>
                    <option value="">&nbsp;</option>
                    <?=loadSelect("lg_commodityclasificacion", "Clasificacion", "Descripcion", $fClasificacion, 0)?>
                </select>
                <?
			}
			?>
		</td>
	</tr>
    <tr>
		<td align="right">Buscar:</td>
		<td>
			<input type="checkbox" <?=$cBuscar?> onclick="chkFiltro(this.checked, 'fBuscar');" />
			<input type="text" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" style="width:300px;" <?=$dBuscar?> />
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<center>
<table width="900" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
	</tr>
</table>

<div style="overflow:scroll; width:900px; height:300px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
		<th scope="col" width="50">C&oacute;digo</th>
		<th scope="col">Descripci&oacute;n</th>
		<th scope="col" width="75">Partida</th>
		<th scope="col" width="75">Cuenta</th>
		<th scope="col" width="75">Clasificaci&oacute;n Activo</th>
		<th scope="col" width="50">Unidad</th>
	</tr>
    </thead>
	<?php
	//	consulto todos
	$sql = "SELECT
				cs.*,
				c.Descripcion AS NomCommodity
			FROM
				lg_commoditysub cs
				INNER JOIN lg_commoditymast c ON (cs.CommodityMast = c.CommodityMast)
			WHERE cs.Estado = 'A' and FlagPresupuesto='S' $filtro
			ORDER BY cs.CommodityMast, cs.Codigo";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				cs.*,
				c.Descripcion AS NomCommodity
			FROM
				lg_commoditysub cs
				INNER JOIN lg_commoditymast c ON (cs.CommodityMast = c.CommodityMast)
			WHERE cs.Estado = 'A' and FlagPresupuesto='S' $filtro
			ORDER BY c.Descripcion, cs.Codigo
			LIMIT ".intval($limit).", ".intval($maxlimit);
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		if ($grupo != $field['CommodityMast']) {
			$grupo = $field['CommodityMast'];
			?>
            <tr class="trListaBody2">
            	<td colspan="2"><?=$field['NomCommodity']?></td>
            </tr>
            <?
		}
		
		if ($ventana == "requerimiento_detalles_insertar") {
			?><tr class="trListaBody" onclick="requerimiento_detalles_insertar('<?=$field["Codigo"]?>', 'commodity');" id="<?=$field['Codigo']?>"><?
		}
		elseif ($ventana == "orden_compra_detalles_insertar") {
			?><tr class="trListaBody" onclick="orden_compra_detalles_insertar('<?=$field["Codigo"]?>', 'commodity');" id="<?=$field['Codigo']?>"><?
		}
		elseif ($ventana == "orden_servicio_detalles_insertar") {
			?><tr class="trListaBody" onclick="orden_servicio_detalles_insertar('<?=$field["Codigo"]?>');" id="<?=$field['Codigo']?>"><?
		}
		elseif ($ventana == "commodity_detalles_insertar") {
			?><tr class="trListaBody" onclick="commodity_detalles_insertar('<?=$field["Codigo"]?>');" id="<?=$field['Codigo']?>"><?
		}
		else {
			?><tr class="trListaBody" onclick="selListado2('<?=$field['Codigo']?>', '<?=($field["Descripcion"])?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$field['Codigo']?>"><?
		}
		?>
			<td align="center"><?=$field['Codigo']?></td>
			<td><?=($field['Descripcion'])?></td>
			<td align="center"><?=$field['cod_partida']?></td>
			<td align="center"><?=$field['CodCuenta']?></td>
			<td align="center"><?=$field['CodClasificacion']?></td>
			<td align="center"><?=$field['CodUnidad']?></td>
        </tr>
		<?
	}
	?>
</table>
</div>
<table width="900">
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
