<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_lg.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('06', $concepto);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_lg_2.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Inventario Actual</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?
$MAXLIMIT=30;
//	-------------------------------
if ($filtrar == "DEFAULT") {
	$forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$fordenar = "i.CodItem";
}
//	-------------------------------
if ($forganismo != "") { $corganismo = "checked"; $filtro .= " AND (a.CodOrganismo = '".$forganismo."')";  } else $dorganismo = "disabled";
if ($falmacen != "") { $calmacen = "checked"; $filtro .= " AND (a.CodAlmacen = '".$falmacen."')";  } else $dalmacen = "disabled";
if ($ftipo != "") { $ctipo = "checked"; $filtro .= " AND (i.CodTipoItem = '".$ftipo."')";  } else $dtipo = "disabled";
if ($fcodlinea != "") { $ccodlinea = "checked"; $filtro .= " AND (i.CodLinea = '".$fcodlinea."' AND i.CodFamilia = '".$fcodfamilia."' AND i.CodSubFamilia = '".$fcodsubfamilia."')"; } else $dcodlinea = "disabled";
if ($fbuscar != "") {
	$cbuscar = "checked"; 
	if ($sltbuscar != "") {
		$filtro .= " AND ($sltbuscar LIKE '%".utf8_decode($fbuscar)."%')";
	} else {
		$filtro .= " AND (i.CodItem LIKE '%".utf8_decode($fbuscar)."%' AND i.Descripcion LIKE '%".utf8_decode($fbuscar)."%' AND i.CodUnidad LIKE '%".utf8_decode($fbuscar)."%' AND i.CodLinea LIKE '%".utf8_decode($fbuscar)."%' AND i.CodFamilia LIKE '%".utf8_decode($fbuscar)."%' AND i.CodSubFamilia LIKE '%".utf8_decode($fbuscar)."%' AND i.CodInterno LIKE '%".utf8_decode($fbuscar)."%' AND ti.Descripcion LIKE '%".utf8_decode($fbuscar)."%')";
	}
} else $dbuscar = "disabled";

//	-------------------------------

//	-------------------------------

//	CONSULTO LA TABLA PARA OBTENER EL TOTAL DE REGISTROS
$sql = "SELECT
			i.Descripcion AS NomItem,
			i.CodInterno,
			i.CodTipoItem,
			i.CodUnidad,
			i.CodLinea,
			i.CodFamilia,
			i.CodSubFamilia,
			a.Descripcion AS NomAlmacen,
			ti.Descripcion AS NomTipoItem,
			ia.CodItem,
			ia.CodAlmacen,
			iav.StockActual
		FROM
			lg_itemmast i
			LEFT JOIN lg_itemalmacen ia ON (i.CodItem = ia.CodItem)
			LEFT JOIN lg_tipoitem ti ON (i.CodTipoItem = ti.CodTipoItem)
			LEFT JOIN lg_almacenmast a ON (ia.CodAlmacen = a.CodAlmacen)
			LEFT JOIN lg_itemalmaceninv iav ON (ia.CodAlmacen = iav.CodAlmacen AND ia.CodItem = iav.CodItem)
		WHERE 1 $filtro";
$query = mysql_query($sql) or die ($sql.mysql_error());
$registros = mysql_num_rows($query);
?>

<form name="frmentrada" id="frmentrada" action="inventario_actual.php?filtrar=" method="get">
<input type="hidden" name="limit" id="limit" value="<?=$limit?>" />
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td width="125" align="right">Organismo:</td>
		<td>
			<input type="checkbox" name="chkorganismo" id="chkorganismo" value="1" <?=$corganismo?> onclick="forzarCheck(this.id);" />
			<select name="forganismo" id="forganismo" class="selectBig" <?=$dorganismo?> onchange="getFOptions_2(this.id, 'falmacen', 'chkalmacen');">
				<?=getOrganismos($forganismo, 3);?>
			</select>
		</td>
        <td width="125" align="right">Linea:</td>
        <td>
			<input type="checkbox" name="chklinea" id="chklinea" value="1" <?=$ccodlinea?> onclick="chkFiltroLinea(this.checked);" />
            <input name="fcodlinea" type="text" id="fcodlinea" size="15" value="<?=$fcodlinea?>" readonly="readonly" />
            <input name="fnomlinea" type="hidden" id="fnomlinea" />
            <input type="button" id="btLinea" value="..." onclick="window.open('lista_subfamilias.php?limit=0&campo1=flinea&campo2=ffamilia&campo3=fsubfamilia', 'wLista', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=500, width=900, left=200, top=200, resizable=yes');" <?=$dcodlinea?> />
        </td>
	</tr>
	<tr>
		<td align="right">Almacen:</td>
		<td>
			<input type="checkbox" name="chkalmacen" id="chkalmacen" value="1" <?=$calmacen?> onclick="chkFiltro(this.checked, 'falmacen');" />
			<select name="falmacen" id="falmacen" style="width:200px;" <?=$dalmacen?>>
				<option value="">&nbsp;</option>
				<?=loadSelectDependiente("lg_almacenmast", "CodAlmacen", "Descripcion", "CodOrganismo", $falmacen, $forganismo, 0)?>
			</select>
		</td>
        <td align="right">Familia:</td>
        <td>
			<input type="checkbox" style="visibility:hidden;" />
            <input name="fcodfamilia" type="text" id="fcodfamilia" value="<?=$fcodfamilia?>" size="15" readonly="readonly" />
            <input name="fnomfamilia" type="hidden" id="fnomfamilia" />
        </td>
	</tr>
    <tr>
		<td align="right">Tipo:</td>
		<td>
			<input type="checkbox" name="chktipo" value="1" <?=$ctipo?> onclick="chkFiltro(this.checked, 'ftipo');" />
			<select name="ftipo" id="ftipo" style="width:200px;" <?=$dtipo?>>
				<option value="">&nbsp;</option>
				<?=loadSelect("lg_tipoitem", "CodTipoItem", "Descripcion", $ftipo, 0)?>
			</select>
		</td>
        <td align="right">Sub-Familia:</td>
        <td>
			<input type="checkbox" style="visibility:hidden;" />
            <input name="fcodsubfamilia" type="text" id="fcodsubfamilia" value="<?=$fcodsubfamilia?>" size="15" readonly="readonly" />
            <input name="fnomsubfamilia" type="hidden" id="fnomsubfamilia" />
        </td>
	</tr>
	<tr>
		<td width="125" align="right">Ordenar Por:</td>
		<td>
			<input type="checkbox" id="chkordenar" name="chkordenar" value="1" checked="checked" onclick="forzarCheck(this.id);" />
			<select name="fordenar" id="fordenar" style="width:200px;">
				<?=loadSelectValores("ORDENAR-INVENTARIO", $fordenar, 0)?>
			</select>
		</td>
		<td width="125" align="right" rowspan="2">Buscar:</td>
		<td>
			<input type="checkbox" name="chkbuscar" value="1" <?=$cbuscar?> onclick="enabledBuscar(this.form);" />
			<select name="sltbuscar" id="sltbuscar" style="width:200px;" <?=$dbuscar?>>
				<option value="">&nbsp;</option>
				<?=loadSelectValores("BUSCAR-ITEM-ALMACEN", $sltbuscar, 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">&nbsp;</td>
		<td>&nbsp;</td>
		<td><input type="text" name="fbuscar" size="50" value="<?=$fbuscar?>" <?=$dbuscar?> /></td>
	</tr>
</table>
</div>

<center><input type="submit" name="btBuscar" value="Buscar"></center>
<br /><div class="divDivision">Lista de Items</div><br />

<table width="1000" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
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
<input name="btImprimir" type="button" id="btImprimir" value="Imprimir" onclick="cargarVentana(this.form, 'lg_inventario_pdf.php?filtro=<?$filtro?>', 'height=800, width=800, left=200, top=200, resizable=yes');" />
					</td>
				</tr>
			</table>
		</td>
		<td align="right">&nbsp;</td>
	</tr>
</table>

<input type="hidden" name="registro" id="registro" />

<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1000px; height:400px;">
<table width="100%" class="tblLista">
	<tr class="trListaHead">
		<th width="100" scope="col">Item</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="50" scope="col">Und.</th>
		<th width="75" scope="col">Cod. Interno</th>
		<th width="75" scope="col">Stock Actual</th>
		<th width="90" scope="col">Linea</th>
		<th width="90" scope="col">Familia</th>
		<th width="90" scope="col">Sub-Familia</th>
	</tr>
	<?php
	//	CONSULTO LA TABLA
	$sql = "SELECT
				i.Descripcion AS NomItem,
				i.CodInterno,
				i.CodTipoItem,
				i.CodUnidad,
				i.CodLinea,
				i.CodFamilia,
				i.CodSubFamilia,
				a.Descripcion AS NomAlmacen,
				ti.Descripcion AS NomTipoItem,
				ia.CodItem,
				ia.CodAlmacen,
				iav.StockActual
			FROM
				lg_itemmast i
				LEFT JOIN lg_itemalmacen ia ON (i.CodItem = ia.CodItem)
				LEFT JOIN lg_tipoitem ti ON (i.CodTipoItem = ti.CodTipoItem)
				LEFT JOIN lg_almacenmast a ON (ia.CodAlmacen = a.CodAlmacen)
				LEFT JOIN lg_itemalmaceninv iav ON (ia.CodAlmacen = iav.CodAlmacen AND ia.CodItem = iav.CodItem)
			WHERE 1 $filtro
			ORDER BY $fordenar
			LIMIT $limit, $MAXLIMIT";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {
		$status = printValores("ESTADO", $field['Estado']);
		$id = $field['CodAlmacen']."-".$field['CodItem'];
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<td align="center"><?=$field['CodItem']?></td>
			<td><?=($field['NomItem'])?></td>
			<td align="center"><?=$field['CodUnidad']?></td>
			<td align="center"><?=$field['CodInterno']?></td>
			<td align="right"><?=number_format($field['StockActual'], 2, ',', '.')?></td>
			<td align="center"><?=$field['CodLinea']?></td>
			<td align="center"><?=$field['CodFamilia']?></td>
			<td align="center"><?=$field['CodSubFamilia']?></td>
		</tr>
		<?
	}
	?>
</table>
</div></td></tr></table>

</form>

<script type="text/javascript" language="javascript">
	numRegistros(<?=$registros?>);
	totalLotes(<?=$registros?>, <?=$rows?>, <?=$limit?>);
</script>
</body>
</html>
