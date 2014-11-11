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
if ($fBuscar != "") { 
	$cBuscar = "checked"; 
	$filtro.=" AND (CodItem LIKE '%".$fBuscar."%' OR
					CodInterno LIKE '%".$fBuscar."%' OR
					Descripcion LIKE '%".utf8_decode($fBuscar)."%' OR
					CodLinea LIKE '%".$fBuscar."%' OR
					CodFamilia LIKE '%".$fBuscar."%' OR
					CodSubFamilia LIKE '%".$fBuscar."%')";
} else $dBuscar = "disabled";
if ($fCodProcedencia != "") { $cCodProcedencia = "checked"; $filtro.=" AND (CodProcedencia = '".$fCodProcedencia."')"; } else $dCodProcedencia = "disabled";
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
		<td class="titulo">Listado de Items</td>
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
<div class="divBorder" style="width:900px;">
<table width="900" class="tblFiltro">
    <tr>
		<td align="right">Buscar:</td>
		<td>
			<input type="checkbox" <?=$cBuscar?> onclick="chkFiltro(this.checked, 'fBuscar');" />
			<input type="text" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" style="width:300px;" <?=$dBuscar?> />
		</td>
		<td align="right">Procedencia:</td>
		<td>
			<input type="checkbox" <?=$cCodProcedencia?> onclick="chkFiltro(this.checked, 'fCodProcedencia')" />
			<select name="fCodProcedencia" id="fCodProcedencia" style="width:150px;" <?=$dCodProcedencia?>>
				<option value="">&nbsp;</option>
				<?=loadSelect("lg_procedencias", "CodProcedencia", "Descripcion", $fCodProcedencia, 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">Ordenar:</td>
		<td>
			<input type="checkbox" <?=$cOrdenar?> onclick="this.checked=!this.checked;" />
			<select name="fOrdenar" id="fOrdenar" style="width:100px;" <?=$dOrdenar?>>
				<option value="">&nbsp;</option>
				<?=loadSelectGeneral("ORDENAR-ITEMS", $fOrdenar, 0)?>
			</select>
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
		<th width="90" scope="col">Item</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="30" scope="col">Und.</th>
		<th width="80" scope="col">Linea</th>
		<th width="80" scope="col">Familia</th>
		<th width="80" scope="col">Sub-Familia</th>
		<th width="65" scope="col">Cod. Interno</th>
	</tr>
    </thead>
	<?php
	//	consulto todos
	$sql = "SELECT *
			FROM lg_itemmast
			WHERE Estado = 'A' $filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT *
			FROM lg_itemmast
			WHERE Estado = 'A' $filtro
			ORDER BY $fOrdenar
			LIMIT ".intval($limit).", ".intval($maxlimit);
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		if ($ventana == "requerimiento_detalles_insertar") {
			?><tr class="trListaBody" onclick="requerimiento_detalles_insertar('<?=$field["CodItem"]?>', 'item');" id="<?=$field['CodItem']?>"><?
		}
		elseif ($ventana == "orden_compra_detalles_insertar") {
			?><tr class="trListaBody" onclick="orden_compra_detalles_insertar('<?=$field["CodItem"]?>', 'item');" id="<?=$field['CodItem']?>"><?
		}
		elseif ($ventana == "almacen_detalles_insertar") {
			?><tr class="trListaBody" onclick="almacen_detalles_insertar('<?=$field["CodItem"]?>');" id="<?=$field['CodItem']?>"><?
		}
		else {
			?><tr class="trListaBody" onclick="selListado2('<?=$field['CodItem']?>', '<?=($field["Descripcion"])?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$field['CodItem']?>"><?
		}
		?>
			<td align="center"><?=$field['CodItem']?></td>
			<td><?=($field['Descripcion'])?></td>
			<td align="center"><?=$field['CodUnidad']?></td>
			<td align="center"><?=$field['CodLinea']?></td>
			<td align="center"><?=$field['CodFamilia']?></td>
			<td align="center"><?=$field['CodSubFamilia']?></td>
			<td align="center"><?=$field['CodInterno']?></td>
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