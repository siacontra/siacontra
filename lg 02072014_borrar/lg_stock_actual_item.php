<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
extract($_POST);
extract($_GET);
//	------------------------------------
include("../lib/fphp.php");
include("../lib/lg_fphp.php");
list ($_SHOW, $_ADMIN, $_INSERT, $_UPDATE, $_DELETE) = opcionesPermisos('06', $concepto);
//	------------------------------------
if ($filtrar == "default") {
	$forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
}
if ($forganismo != "") { $corganismo = "checked"; $filtro.=" AND (os.CodOrganismo = '".$forganismo."')"; } else $dorganismo = "disabled";
//	------------------------------------
$sql = "SELECT
			i.CodItem,
			i.CodInterno,
			i.Descripcion,
			i.CodUnidad,
			i.CodTipoItem,
			i.CodLinea,
			i.CodFamilia,
			i.CodSubFamilia,
			ti.Descripcion AS NomTipoItem,
			sf.Descripcion AS SubFamilia,
			f.Descripcion AS Familia,
			l.Descripcion AS Linea
		FROM
			lg_itemmast i
			INNER JOIN lg_tipoitem ti ON (i.CodTipoItem = ti.CodTipoItem)
			INNER JOIN lg_clasesubfamilia sf ON (i.CodLinea = sf.CodLinea AND 
												 i.CodFamilia = sf.CodFamilia AND
												 i.CodSubFamilia = sf.CodSubFamilia)
			INNER JOIN lg_clasefamilia f ON (sf.CodFamilia = f.CodFamilia)
			INNER JOIN lg_claselinea l ON (f.CodLinea = l.CodLinea)
		WHERE i.CodItem = '".$item."'";
$query_item = mysql_query($sql) or die($sql.mysql_error());
if (mysql_num_rows($query_item) != 0) $field_item = mysql_fetch_array($query_item);
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="../js/funciones.js"></script>
<script type="text/javascript" language="javascript" src="../js/lg_funciones.js"></script>
<script type="text/javascript" language="javascript" src="../js/lg_fscript.js"></script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Stock Actual de un Item</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="lg_stock_actual_item.php?" method="post">
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td align="right" width="125">Organismo:</td>
		<td colspan="5">
			<input type="checkbox" <?=$corganismo?> onclick="chkFiltro(this.checked, 'forganismo');" />
			<select name="forganismo" id="forganismo" <?=$dorganismo?> style="width:300px;">
				<option value=""></option>
				<?=getOrganismos($forganismo, 3);?>
			</select>
		</td>
	</tr>
    <tr>
    	<td colspan="6" align="center">
        	<hr width="90%" />
        </td>
    </tr>
    <tr>
		<td align="right">Item:</td>
		<td>
        	<input type="text" name="item" id="item" style="width:100px;" maxlength="10" value="<?=$field_item['CodItem']?>" />
			<input type="button" value="..." onclick="cargarVentana(this.form, '../lib/listado_items.php?cod=fitem&nom=fnomitem&opcion=stock_actual_item', 'height=800, width=900, left=50, top=50, resizable=yes');" />
		</td>
		<td align="right" width="125">Cod. Interno:</td>
		<td>
			<input type="text" name="codinterno" id="codinterno" style="width:100%;" value="<?=$field_item['CodInterno']?>"readonly />
		</td>
		<td align="right" width="125">Linea:</td>
		<td><input type="text" name="linea" id="linea" style="width:225px;" value="<?=($field_item['Linea'])?>"readonly /></td>
    </tr>
    <tr>
		<td align="right">Descripci&oacute;n:</td>
		<td colspan="3"><input type="text" name="descripcion" id="descripcion" style="width:100%;" value="<?=($field_item['Descripcion'])?>"readonly /></td>
		<td align="right">Familia:</td>
		<td><input type="text" name="familia" id="familia" style="width:225px;" value="<?=($field_item['Familia'])?>"readonly /></td>
    </tr>
    <tr>
		<td align="right">Unidad:</td>
		<td><input type="text" name="codunidad" id="codunidad" style="width:100px;" maxlength="10" value="<?=$field_item['CodUnidad']?>"readonly /></td>
		<td align="right">Tipo Item:</td>
		<td><input type="text" name="tipoitem" id="tipoitem" style="width:100%;" value="<?=($field_item['NomTipoItem'])?>"readonly /></td>
		<td align="right">Sub-Familia:</td>
		<td><input type="text" name="subfamilia" id="subfamilia" style="width:225px;" value="<?=($field_item['SubFamilia'])?>"readonly /></td>
    </tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<table align="center"><tr><td align="center"><div style="overflow:scroll; width:1000px; height:400px;">
<table width="100%" class="tblLista">
	<thead>
	<tr class="trListaHead">
		<th scope="col" width="125">Almacen</th>
		<th scope="col">Descripci&oacute;n</th>
		<th scope="col" width="100">Stock Actual</th>
		<th scope="col" width="100">Stock Equivalente</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	$sql = "SELECT
				iav.StockActual,
				a.CodAlmacen,
				a.Descripcion AS NomAlmacen
			FROM
				lg_itemalmaceninv iav
				INNER JOIN lg_almacenmast a ON (iav.CodAlmacen = a.CodAlmacen)
			WHERE iav.CodItem = '".$item."'
			ORDER BY iav.CodAlmacen";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {
		?>
		<tr class="trListaBody">
			<td align="center"><?=($field['CodAlmacen'])?></td>
			<td><?=($field['NomAlmacen'])?></td>
			<td align="right"><strong><?=number_format($field['StockActual'], 2, ',', '.')?></strong></td>
			<td align="right"><?=number_format($field['StockEquivalente'], 2, ',', '.')?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div></td></tr></table>
</form>
</body>
</html>