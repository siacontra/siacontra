<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp_lg.php");
connect();
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('01', $concepto);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="fscript_lg_2.js"></script>
<style type="text/css">
<!--
UNKNOWN {
        FONT-SIZE: small
}
#header {
        FONT-SIZE: 93%; BACKGROUND: url(bg.gif) #dae0d2 repeat-x 50% bottom; FLOAT: left; WIDTH: 100%; LINE-HEIGHT: normal
}
#header UL {
        PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 10px; LIST-STYLE-TYPE: none
}
#header LI {
        PADDING-RIGHT: 0px; PADDING-LEFT: 9px; BACKGROUND: url(left.gif) no-repeat left top; FLOAT: left; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 0px
}
#header A {
        PADDING-RIGHT: 15px; DISPLAY: block; PADDING-LEFT: 6px; FONT-WEIGHT: bold; BACKGROUND: url(right.gif) no-repeat right top; FLOAT: left; PADDING-BOTTOM: 4px; COLOR: #765; PADDING-TOP: 5px; TEXT-DECORATION: none
}
#header A {
        FLOAT: none
}
#header A:hover {
        COLOR: #333
}
#header #current {
        BACKGROUND-IMAGE: url(left_on.gif)
}
#header #current A {
        BACKGROUND-IMAGE: url(right_on.gif); PADDING-BOTTOM: 5px; COLOR: #333
}
-->
</style>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Despacho de Almacen</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?
$MAXLIMIT=30;
//	-------------------------------
if ($filtrar == "DEFAULT") {
	$forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$fdependencia = $_SESSION["FILTRO_DEPENDENCIA_ACTUAL"];
}
//	-------------------------------
if ($forganismo != "") { $corganismo = "checked"; $filtro.=" AND (r.CodOrganismo = '".$forganismo."')"; } else $dorganismo = "disabled";
if ($fentrega != "") { $cfentrega = "checked"; $filtro.=" AND (r.FechaPrometida <= '".formatFechaAMD($fentrega)."')"; } else $dfentrega = "disabled";
if ($falmacen != "") { $calmacen = "checked"; $filtro.=" AND (r.FechaRequerida = '".$falmacen."')"; } else $dalmacen = "disabled";
if ($fbuscar != "") {
	$cbuscar = "checked";
	if ($sltbuscar == "") $filtro.=" AND (r.CodRequerimiento LIKE '%".$fbuscar."%' OR r.Comentarios LIKE '%".utf8_decode($fbuscar)."%')";
	else $filtro.=" AND $sltbuscar LIKE '%".$fbuscar."%'";
} else { $dbuscar = "disabled"; $sltbuscar=""; }
?>

<form name="frmfiltro" id="frmfiltro" action="despachos_almacen.php?filtrar=" method="get">
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td width="125" align="right">Organismo:</td>
		<td>
			<input type="checkbox" name="chkorganismo" id="chkorganismo" value="1" <?=$corganismo?> onclick="forzarCheck(this.id)" />
			<select name="forganismo" id="forganismo" class="selectBig" <?=$dorganismo?>>
				<?=getOrganismos($forganismo, 3);?>
			</select>
		</td>
		<td align="right" rowspan="2" valign="top">Buscar:</td>
		<td>
			<input type="checkbox" name="chkbuscar" value="1" <?=$cbuscar?> onclick="enabledBuscar(this.form);" />
			<select name="sltbuscar" id="sltbuscar" style="width:200px;" <?=$dbuscar?>>
				<option value=""></option>
				<?=loadSelectValores("BUSCAR-ORDEN-ALMACEN-DESPACHO", $sltbuscar, 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td width="125" align="right">F. Requerida (<=):</td>
		<td>
			<input type="checkbox" name="chkfentrega" id="chkfentrega" value="1" <?=$cfentrega?> onclick="chkFiltro(this.checked, 'fentrega');" />
			<input type="text" name="fentrega" id="fentrega" size="15" maxlength="10" value="<?=$fentrega?>" <?=$dfentrega?> />
		</td>
		<td><input type="text" name="fbuscar" size="50" value="<?=$fbuscar?>" <?=$dbuscar?> /></td>
	</tr>
	<tr>
		<td width="125" align="right">Almac&eacute;n:</td>
		<td colspan="3">
			<input type="checkbox" name="chkalmacen" id="chkalmacen" value="1" <?=$calmacen?> onclick="chkFiltro(this.checked, 'falmacen');" />
			<select name="falmacen" id="falmacen" style="width:200px;" <?=$dalmacen?>>
				<option value=""></option>
				<?=loadSelect("lg_almacenmast", "CodAlmacen", "Descripcion", $falmacen, 0)?>
			</select>
		</td>
	</tr>
</table>
</div>

<center><input type="submit" name="btBuscar" value="Buscar"></center>
</form>

<br />

<form name="frmordenes" id="frmordenes">
<input type="hidden" id="selrequerimiento" />

<div name="tab1" id="tab1" style="display:block;">
<table width="1000" class="tblBotones">
	<tr>
		<td><div id="rows_compra"></div></td>
		<td align="right">
        	<input type="button" value="Dirigir a Compras para Reposición" onclick="dirigirCompraReposicion();" /> | 
        	<input name="btVer" type="button" id="btVer" value="Ver Requerimiento" onclick="cargarOpcion_2(this.form, 'lg_requerimientos_form.php?ver=almacen&opcion=ver', 'BLANK', 'height=700, width=950, left=100, top=0, resizable=no', 'selrequerimiento');" />
		</td>
	</tr>
</table>

<div style="width:1000px" class="divFormCaption">Despachos Pendientes</div>
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1000px; height:200px;">
<table width="100%" class="tblLista">
	<tr class="trListaHead">
		<th scope="col" width="125"># Requerimiento</th>
		<th scope="col" width="75">C.Costos</th>
		<th scope="col" width="100">Fecha Requerida</th>
		<th scope="col">Comentarios</th>
		<th scope="col" width="150">Almacen</th>
	</tr>
	<?php
	//	CONSULTO LA TABLA
	$sql = "SELECT
				r.*,
				cc.Descripcion AS NomCentroCosto,
				a.Descripcion AS NomAlmacen
			FROM
				lg_requerimientos r
				INNER JOIN ac_mastcentrocosto cc ON (r.CodCentroCosto = cc.CodCentroCosto)
				INNER JOIN lg_almacenmast a ON (r.CodAlmacen = a.CodAlmacen)
				INNER JOIN lg_requerimientosdet rd ON (r.CodOrganismo = rd.CodOrganismo AND r.CodRequerimiento = rd.CodRequerimiento)
			WHERE
				(r.Estado = 'AP' AND r.TipoClasificacion = 'A') AND
				(rd.Estado = 'PE' AND rd.FlagCompraAlmacen <> 'C') $filtro
			GROUP BY CodOrganismo, CodRequerimiento
			ORDER BY CodRequerimiento, CodItem, CommoditySub";
	$query_mast = mysql_query($sql) or die ($sql.mysql_error());
	$rows_mast = mysql_num_rows($query_mast);
	//	MUESTRO LA TABLA
	while ($field_mast = mysql_fetch_array($query_mast)) {
		$clasificacion = printValores("ORDEN-CLASIFICACION", $field_mast['Clasificacion']);
		if (strlen($field_mast['Comentarios']) > 200) $comentarios = substr($field_mast['Comentarios'], 0, 200)."...";
		else $comentarios = $field_mast['Comentarios'];
		?>
		<tr class="trListaBody" onclick="mClk(this, 'selrequerimiento'); mostrarRequerimientoDetalles();" id="<?=$field_mast['CodRequerimiento']?>">
            <td align="center"><?=$field_mast['CodInterno']?></td>
            <td><?=$field_mast['CodCentroCosto']?></td>
            <td align="center"><?=formatFechaDMA($field_mast['FechaRequerida'])?></td>
            <td title="<?=$field_mast['Comentarios']?>"><?=($comentarios)?></td>
            <td><?=($field_mast['NomAlmacen'])?></td>
		</tr>
		<?
	}
	?>
</table>
</div></td></tr></table>
</div>




<div name="tab2" id="tab2" style="display:none;">
</div>


</form>

<div name="tabDetalles" id="tabDetalles" style="display:block;">
<form name="frmlineas" id="frmlineas">
<table width="1000" class="tblBotones">
	<tr>
		<td><div id="rows_stock"></div></td>
		<td align="right">
        	<input type="button" id="btCerrar" class="btLista" value="Cerrar" onclick="cerrarLineaRequerimiento(this.form)" />
			<input type="button" id="btCompras" value="Pasar a Compras" onclick="pasarCompras(this.form);" />
			<input type="button" id="btDespachar" class="btLista" value="Despachar" onclick="despachoAlmacen(this.form);" />
		</td>
	</tr>
</table>

<div style="width:1000px" class="divFormCaption">Detalles</div>
<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1000px; height:200px;">
<table width="100%" class="tblLista">
	<tr class="trListaHead">
        <th scope="col" width="100">Item</th>
        <th scope="col" width="75">Cod. Interno</th>
        <th scope="col">Descripci&oacute;n</th>
        <th scope="col" width="50">Uni.</th>
        <th scope="col" width="75">Cant. Pedida</th>
        <th scope="col" width="75">Cant. Pendiente</th>
        <th scope="col" width="75">Stock Actual</th>
        <th scope="col" width="100">C. Costos</th>
        <th scope="col" width="75">En Transito</th>
        <th scope="col" width="20">C.</th>
	</tr>
    
    <tbody id="trDetalle">
    	
    </tbody>
</table>
</div></td></tr></table>
</form>
</div>
</body>
</html>