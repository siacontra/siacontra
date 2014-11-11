<?php
if ($accion == "insertarItemRequerimiento") {
	//connect();
	
	if ($ventana == "insertarItemRequerimiento") $ddesc = "disabled"; else $ddesc = "";
	
	$detalle = split(";", $detalles);
	foreach ($detalle as $registro) {
		if ($codigo == $registro) die("¡No se puede insertar dos veces el mismo $tabla en una misma orden!");
	}
	
	//	si nosencontraron errores inserta en la tabla los datos del proveedor
	echo "||";
	if ($tabla == "item") {
		$sql = "SELECT * FROM lg_itemmast WHERE CodItem = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	} else {
		$sql = "SELECT * FROM lg_commoditysub WHERE Codigo = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	}
	?>
		<td align="center"><?=$nrodetalles?><input type="checkbox" name="chkdetalles" id="chk_<?=$codigo?>" title="<?=$codigo?>" style="display:none;" /></td>
		<td align="center"><?=$codigo?></td>
		<td><input type="text" name="txtdescripcion" value="<?=htmlentities($field['Descripcion'])?>" style="width:98%" <?=$ddesc?> /></td>
		<td align="center"><input type="hidden" name="txtcodunidad" value="<?=$field['CodUnidad']?>" /><?=$field['CodUnidad']?></td>
		<td>
        	<input type="hidden" name="txtnomccostos" id="txtnomccostos_<?=$codigo?>" />
			<input type="text" name="txtccostos" id="txtccostos_<?=$codigo?>" value="<?=$ccosto?>" style="width:98%; text-align:center;" disabled="disabled" />
        </td>
        <td align="center"><input type="checkbox" name="chkexon" id="chkexon_<?=$codigo?>" style="width:98%" /></td>
        <td><input type="text" name="txtcantidad" id="txtcantidad_<?=$codigo?>" value="0,00" style="width:98%; text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
        <td align="center"><?=$dirigidoa?></td>
        <td align="center"><?=htmlentities("En Preparación")?></td>
	
	<?
}

 ?>