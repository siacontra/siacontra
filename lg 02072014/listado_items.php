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
		<td class="titulo">Lista de Items</td>
		<td align="right"><a class="cerrar" href="javascript:window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<?
$MAXLIMIT=30;
//	-------------------------------
if ($filtrar == "DEFAULT") {
	$fedoreg = "A";
}
//	-------------------------------
if ($fedoreg != "") $cedoreg = "checked"; else $dedoreg = "disabled"; 
if ($fprocedencia != "") $cprocedencia = "checked"; else $dprocedencia = "disabled"; 
if ($fbuscar != "") $cbuscar = "checked"; else $dbuscar = "disabled"; 
//	-------------------------------

//	-------------------------------

//	CONSULTO LA TABLA PARA OBTENER EL TOTAL DE REGISTROS
$sql = "SELECT * FROM lg_itemmast";
$query = mysql_query($sql) or die ($sql.mysql_error());
$registros = mysql_num_rows($query);
?>

<form name="frmentrada" id="frmentrada" action="listado_items.php?filtrar=" method="get">
<input type="hidden" name="limit" id="limit" value="<?=$limit?>" />
<input type="hidden" name="cod" id="cod" value="<?=$cod?>" />
<input type="hidden" name="nom" id="nom" value="<?=$nom?>" />
<input type="hidden" name="ventana" id="ventana" value="<?=$ventana?>" />
<input type="hidden" name="tabla" id="tabla" value="<?=$tabla?>" />
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td width="125" align="right">Estado:</td>
		<td>
			<input type="checkbox" name="chkedoreg" value="1" <?=$cedoreg?> onclick="chkFiltro(this.checked, 'fedoreg');" />
			<select name="fedoreg" id="fedoreg" style="width:200px;" <?=$dedoreg?>>
				<option value=""></option>
				<?=loadSelectValores("ESTADO", $fedoreg, 0)?>
			</select>
		</td>
		<td width="125" align="right" rowspan="2">Buscar:</td>
		<td>
			<input type="checkbox" name="chkbuscar" value="1" <?=$cbuscar?> onclick="enabledBuscar(this.form);" />
			<select name="sltbuscar" id="sltbuscar" style="width:200px;" <?=$dbuscar?>>
				<option value=""></option>
				<?=loadSelectValores("BUSCAR-ITEMS", $sltbuscar, 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">Procedencia:</td>
		<td>
			<input type="checkbox" name="chkprocedencia" value="1" <?=$cprocedencia?> onclick="chkFiltro(this.checked, 'fprocedencia');" />
			<select name="fprocedencia" id="fprocedencia" style="width:200px;" <?=$dprocedencia?>>
				<option value=""></option>
				<?=loadSelect("lg_procedencias", "CodProcedencia", "Descripcion", $fprocedencia, 0)?>
			</select>
		</td>
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
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<input type="hidden" name="registro" id="registro" />

<table width="1000" class="tblLista">
	<tr class="trListaHead">
		<th width="100" scope="col">Item</th>
		<th scope="col">Descripci&oacute;n</th>
		<th width="50" scope="col">Und.</th>
		<th width="90" scope="col">Linea</th>
		<th width="90" scope="col">Familia</th>
		<th width="90" scope="col">Sub-Familia</th>
		<th width="75" scope="col">Cod. Interno</th>
		<th width="60" scope="col">Estado</th>
	</tr>
	<?php
	//	CONSULTO LA TABLA
	$sql = "SELECT * FROM lg_itemmast LIMIT $limit, $MAXLIMIT";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {
		$status = printValores("ESTADO", $field['Estado']);
		
		if ($ventana == "insertarItemOrden") {
			?>
			<tr class="trListaBody" onclick="mClk(this, 'registro'); insertarItemOrden(this.id, '<?=$ventana?>');" id="<?=$field['CodItem']?>">
				<td align="center"><?=$field['CodItem']?></td>
				<td><?=($field['Descripcion'])?></td>
				<td align="center"><?=$field['CodUnidad']?></td>
				<td align="center"><?=$field['CodLinea']?></td>
				<td align="center"><?=$field['CodFamilia']?></td>
				<td align="center"><?=$field['CodSubFamilia']?></td>
				<td align="center"><?=$field['CodInterno']?></td>
				<td align="center"><?=$status?></td>
			</tr>
			<?
		}
		elseif ($ventana == "insertarItemRequerimiento") {
			?>
			<tr class="trListaBody" onclick="mClk(this, 'registro'); insertarItemRequerimiento(this.id, '<?=$ventana?>');" id="<?=$field['CodItem']?>">
				<td align="center"><?=$field['CodItem']?></td>
				<td><?=($field['Descripcion'])?></td>
				<td align="center"><?=$field['CodUnidad']?></td>
				<td align="center"><?=$field['CodLinea']?></td>
				<td align="center"><?=$field['CodFamilia']?></td>
				<td align="center"><?=$field['CodSubFamilia']?></td>
				<td align="center"><?=$field['CodInterno']?></td>
				<td align="center"><?=$status?></td>
			</tr>
			<?
		}
		elseif ($ventana == "insertarItemTransaccion") {
			?>
			<tr class="trListaBody" onclick="mClk(this, 'registro'); insertarItemTransaccion(this.id, '<?=$ventana?>');" id="<?=$field['CodItem']?>">
				<td align="center"><?=$field['CodItem']?></td>
				<td><?=($field['Descripcion'])?></td>
				<td align="center"><?=$field['CodUnidad']?></td>
				<td align="center"><?=$field['CodLinea']?></td>
				<td align="center"><?=$field['CodFamilia']?></td>
				<td align="center"><?=$field['CodSubFamilia']?></td>
				<td align="center"><?=$field['CodInterno']?></td>
				<td align="center"><?=$status?></td>
			</tr>
			<?
		}
		elseif ($ventana == "insertarItemAlmacenRecepcion") {
			?>
			<tr class="trListaBody" onclick="mClk(this, 'registro'); insertarItemAlmacenRecepcion(this.id, '<?=$ventana?>');" id="<?=$field['CodItem']?>">
				<td align="center"><?=$field['CodItem']?></td>
				<td><?=($field['Descripcion'])?></td>
				<td align="center"><?=$field['CodUnidad']?></td>
				<td align="center"><?=$field['CodLinea']?></td>
				<td align="center"><?=$field['CodFamilia']?></td>
				<td align="center"><?=$field['CodSubFamilia']?></td>
				<td align="center"><?=$field['CodInterno']?></td>
				<td align="center"><?=$status?></td>
			</tr>
			<?
		} 
		elseif ($ventana == "item_x_almacen") {
			?>
			<tr class="trListaBody" onclick="mClk(this, 'registro'); selListadoItemAlmacen(this.id, '<?=($field['Descripcion'])?>', '<?=$field['CodUnidad']?>');" id="<?=$field['CodItem']?>">
				<td align="center"><?=$field['CodItem']?></td>
				<td><?=($field['Descripcion'])?></td>
				<td align="center"><?=$field['CodUnidad']?></td>
				<td align="center"><?=$field['CodLinea']?></td>
				<td align="center"><?=$field['CodFamilia']?></td>
				<td align="center"><?=$field['CodSubFamilia']?></td>
				<td align="center"><?=$field['CodInterno']?></td>
				<td align="center"><?=$status?></td>
			</tr>
			<?
		} else {
			?>
			<tr class="trListaBody" onclick="mClk(this, 'registro'); selListado('<?=($field["Descripcion"])?>', '<?=$cod?>', '<?=$nom?>');" id="<?=$field['CodItem']?>">
				<td align="center"><?=$field['CodItem']?></td>
				<td><?=($field['Descripcion'])?></td>
				<td align="center"><?=$field['CodUnidad']?></td>
				<td align="center"><?=$field['CodLinea']?></td>
				<td align="center"><?=$field['CodFamilia']?></td>
				<td align="center"><?=$field['CodSubFamilia']?></td>
				<td align="center"><?=$field['CodInterno']?></td>
				<td align="center"><?=$status?></td>
			</tr>
			<?
		}
	}
	?>
</table>

</form>

<script type="text/javascript" language="javascript">
	totalLista(<?=$rows?>);
	totalLotes(<?=$registros?>, <?=$rows?>, <?=$limit?>);
</script>
</body>
</html>