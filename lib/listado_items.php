<?php
session_start();
if (!isset($_SESSION['USUARIO_ACTUAL']) || !isset($_SESSION['ORGANISMO_ACTUAL'])) header("Location: ../index.php");
//	------------------------------------
include("fphp.php");
connect();
//	------------------------------------
$filtro = "";
if ($fcodlinea != "") { $ccodlinea = "checked"; $filtro .= " AND i.CodLinea = '".$fcodlinea."'"; } else $dcodlinea = "disabled";
if ($fcodfamilia != "") { $ccodfamilia = "checked"; $filtro .= " AND i.CodFamilia = '".$fcodfamilia."'"; } else $dcodfamilia = "disabled";
if ($fcodsubfamilia != "") { $ccodsubfamilia = "checked"; $filtro .= " AND i.CodSubFamilia = '".$fcodsubfamilia."'"; } else $dcodsubfamilia = "disabled";
if ($ftipoitem != "") { $ctipoitem = "checked"; $filtro .= " AND i.CodTipoItem = '".$ftipoitem."'"; } else $dtipoitem = "disabled";
if ($fmarca != "") { $cmarca = "checked"; $filtro .= " AND i.CodMarca = '".$fmarca."'"; } else $dmarca = "disabled";
if ($fprocedencia != "") { $cprocedencia = "checked"; $filtro .= " AND i.CodProcedencia = '".$fprocedencia."'"; } else $dprocedencia = "disabled";
if ($sltbuscar != "") { 
	$cbuscar = "checked"; 
	if ($sltbuscar == "") $filtro.=" AND (i.Codigo LIKE '%".$fbuscar."%' OR 
										  i.Descripcion LIKE '%".($fbuscar)."%' OR 
										  i.CodLinea LIKE '%".($fbuscar)."%' OR 
										  i.CodFamilia LIKE '%".($fbuscar)."%' OR 
										  i.CodSubFamilia LIKE '%".($fbuscar)."%')";
	else $filtro.=" AND $sltbuscar LIKE '%".$fbuscar."%'";
} else $dbuscar = "disabled"; 
//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="../js/funciones.js"></script>
<script type="text/javascript" language="javascript" src="../js/fscript.js"></script>
<script>
function chkFiltroLinea(boo) {
	document.getElementById("btLinea").disabled = !boo;
	document.getElementById("fcodlinea").value = "";
	document.getElementById("fnomlinea").value = "";
	document.getElementById("fcodfamilia").value = "";
	document.getElementById("fnomfamilia").value = "";
	document.getElementById("fcodsubfamilia").value = "";
	document.getElementById("fnomsubfamilia").value = "";
}
</script>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Listado de Items</td>
		<td align="right"><a class="cerrar" href="#" onclick="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="listado_items.php" method="POST">
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="cod" id="cod" value="<?=$cod?>" />
<input type="hidden" name="nom" id="nom" value="<?=$nom?>" />
<input type="hidden" name="opcion" id="opcion" value="<?=$opcion?>" />
<input type="hidden" name="accion" id="accion" value="<?=$accion?>" />
<input type="hidden" name="detalle" id="detalle" value="<?=$detalle?>" />
<input type="hidden" name="php" id="php" value="<?=$php?>" />
<input type="hidden" name="tipo" id="tipo" value="<?=$tipo?>" />

<div class="divBorder" style="width:800px;">
<table width="800" class="tblFiltro">
	<tr>
		<td width="125" align="right">Buscar:</td>
		<td>
			<input type="checkbox" <?=$cbuscar?> onclick="chkFiltro_2(this.checked, 'sltbuscar', 'fbuscar')" />
			<select name="sltbuscar" id="sltbuscar" style="width:200px;" <?=$dbuscar?>>
				<option value=""></option>
				<?=loadSelectGeneral("BUSCAR-ITEMS", $sltbuscar, 0)?>
			</select>
		</td>
        <td width="125" align="right">Linea:</td>
        <td>
			<input type="checkbox" name="chklinea" id="chklinea" value="1" <?=$ccodlinea?> onclick="chkFiltroLinea(this.checked);" />
            <input name="fcodlinea" type="text" id="fcodlinea" size="15" value="<?=$fcodlinea?>" readonly="readonly" />
            <input name="fnomlinea" type="hidden" id="fnomlinea" />
            <input type="button" id="btLinea" value="..." onclick="window.open('../lg/lista_subfamilias.php?limit=0&campo1=flinea&campo2=ffamilia&campo3=fsubfamilia', 'wLista', 'toolbar=no, menubar=no, location=no, scrollbars=yes, height=500, width=900, left=200, top=200, resizable=yes');" <?=$dcodlinea?> />
        </td>
	</tr>
	<tr>
		<td align="right">&nbsp;</td>
		<td>
			<input type="checkbox" style="visibility:hidden;" />
        	<input type="text" name="fbuscar" id="fbuscar" size="50" value="<?=$fbuscar?>" <?=$dbuscar?> />
		</td>
        <td align="right">Familia:</td>
        <td>
			<input type="checkbox" style="visibility:hidden;" />
            <input name="fcodfamilia" type="text" id="fcodfamilia" value="<?=$fcodfamilia?>" size="15" readonly="readonly" />
            <input name="fnomfamilia" type="hidden" id="fnomfamilia" />
        </td>
	</tr>
	<tr>
		<td align="right">Tipo Item:</td>
		<td>
			<input type="checkbox" name="chktipoitem" value="1" <?=$ctipoitem?> onclick="chkFiltro(this.checked, 'ftipoitem');" />
			<select name="ftipoitem" id="ftipoitem" style="width:200px;" <?=$dtipoitem?>>
				<option value=""></option>
				<?=loadSelect("lg_tipoitem", "CodTipoItem", "Descripcion", $ftipoitem, 0)?>
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
		<td align="right">Marca:</td>
		<td>
			<input type="checkbox" name="chkmarca" value="1" <?=$cmarca?> onclick="chkFiltro(this.checked, 'fmarca');" />
			<select name="fmarca" id="fmarca" style="width:200px;" <?=$dmarca?>>
				<option value=""></option>
				<?=loadSelect("lg_marcas", "CodMarca", "Descripcion", $fmarca, 0)?>
			</select>
		</td>
		<td align="right">Procedencia:</td>
		<td>
			<input type="checkbox" name="chkprocedencia" value="1" <?=$cprocedencia?> onclick="chkFiltro(this.checked, 'fprocedencia');" />
			<select name="fprocedencia" id="fprocedencia" style="width:200px;" <?=$dprocedencia?>>
				<option value=""></option>
				<?=loadSelect("lg_procedencias", "CodProcedencia", "Descripcion", $fprocedencia, 0)?>
			</select>
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<table width="800" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
	</tr>
</table>

<table align="center"><tr><td align="center"><div style="overflow:scroll; width:800px; height:600px;">
<table width="100%" class="tblLista">
	<tr class="trListaHead">
		<th width="100" scope="col">Item</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="50" scope="col">Und.</th>
		<th width="90" scope="col">Linea</th>
		<th width="90" scope="col">Familia</th>
		<th width="90" scope="col">Sub-Familia</th>
		<th width="75" scope="col">Cod. Interno</th>
	</tr>
	<?php
	//	CONSULTO LA TABLA
	$sql = "SELECT * FROM lg_itemmast i WHERE Estado = 'A' $filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {
		if ($opcion == "insertarLineaListado") {
			?>
            <tr class="trListaBody" onclick="<?=$opcion?>('<?=$accion?>', '<?=$detalle?>', '<?=$php?>', '<?=$field['CodItem']?>');" id="<?=$field['CodItem']?>">
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
		elseif ($opcion == "insertarLineaListadoItem") {
			?>
            <tr class="trListaBody" onclick="<?=$opcion?>('<?=$accion?>', '<?=$detalle?>', '<?=$php?>', '<?=$field['CodItem']?>', '<?=$tipo?>');" id="<?=$field['CodItem']?>">
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
		elseif ($opcion == "insertarLineaListadoItemOC") {
			?>
            <tr class="trListaBody" onclick="<?=$opcion?>('<?=$accion?>', '<?=$detalle?>', '<?=$php?>', '<?=$field['CodItem']?>', '<?=$tipo?>');" id="<?=$field['CodItem']?>">
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
		elseif ($opcion == "stock_actual_item") {
			?>
            <tr class="trListaBody" onclick="<?=$opcion?>('<?=$field['CodItem']?>');" id="<?=$field['CodItem']?>">
                <td align="center"><?=$field['CodItem']?></td>
                <td><?=($field['Descripcion'])?></td>
                <td align="center"><?=$field['CodUnidad']?></td>
                <td align="center"><?=$field['CodLinea']?></td>
                <td align="center"><?=$field['CodFamilia']?></td>
                <td align="center"><?=$field['CodSubFamilia']?></td>
                <td align="center"><?=$field['CodInterno']?></td>
            </tr>
			<?
		} else {
			?>
            <tr class="trListaBody" onclick="selListado('<?=$field['CodItem']?>', '<?=($field["Descripcion"])?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$field['CodItem']?>">
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