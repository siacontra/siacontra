<?php
include("fphp.php");
include("lg_fphp.php");
extract($_POST);
extract($_GET);

//	----------------
//	requerimientos
//	----------------
if ($accion == "requerimientos_insertar_item") {
	if ($flagdirigido == "A") $dirigidoa = "Almacén"; else $dirigidoa = "Compras";
	if ($tipo == "item") {
		$readonly = "readonly";
		$sql = "SELECT *, CtaGasto AS CodCuenta, PartidaPresupuestal AS cod_partida FROM lg_itemmast WHERE CodItem = '".$codigo."'";
		$codigo_return = "$codigo.$nrodetalle";
	} else {
		$sql = "SELECT
					cs.*,
					cm.Clasificacion,
					cm.Descripcion AS NomCommodity
				FROM
					lg_commoditysub cs
					INNER JOIN lg_commoditymast cm ON (cs.CommodityMast = cm.CommodityMast)
				WHERE cs.Codigo = '".$codigo."'";
		$codigo_return = "$codigo.$nrodetalle";
	}
	
	$query = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($tipo == "item" ) $descripcion = $field['Descripcion'];
		else $descripcion = strtoupper($field['NomCommodity']."-".$field['Descripcion'])
		?>
    	<td align="center"><?=$nrodetalle?></td>
		<td align="center"><input type="text" name="codigo" class="cell2" style="text-align:center;" value="<?=$codigo?>" readonly /></td>
		<td align="center">
        	<textarea name="descripcion" style="width:99%; height:30px;" class="cell" onBlur="this.className='cell'; this.style.height='30px';" onFocus="this.className='cellFocus'; this.style.height='60px';" <?=$readonly?>><?=($descripcion)?></textarea>
        </td>
		<td align="center"><input type="text" name="unidad" value="<?=$field['CodUnidad']?>" class="cell2" style="text-align:center;" readonly />		
        <td align="center">
            <input type="text" name="codccosto" id="codccosto_<?=$codigo_return?>" class="cell2" style="text-align:center;" value="<?=$ccosto?>" readonly />
            <input type="hidden" name="nomccosto" id="nomccosto_<?=$codigo_return?>" />
        </td>
		<td align="center"><input type="checkbox" name="flagexon" /></td>
		<td align="center"><input type="text" name="cantidad" value="0,00" class="cell" style="text-align:right; font-weight:bold;" onBlur="numeroBlur(this); this.className='cell';" onFocus="numeroFocus(this); this.className='cellFocus';" /></td>
		<td align="center">
            <input type="hidden" name="flagdirigido" value="<?=$flagdirigido?>" />
			<?=$dirigidoa?>
        </td>
		<td align="center"><?=printValores("ESTADO-REQUERIMIENTO", "PR")?></td>
		<td>
        	<input type="text" name="docreferencia" class="cell" onblur="this.className='cell';" onfocus="this.className='cellFocus';" />
            <input type="hidden" name="codcuenta" value="<?=$field['CodCuenta']?>" />
            <input type="hidden" name="codpartida" value="<?=$field['cod_partida']?>" />
        </td>
		<td align="right">0,00</td>
		<td align="right">0,00</td>
		<td align="center"></td>
		<?
		echo "|$codigo_return";
	}
}
elseif ($accion == "setDirigidoA") {
	$sql = "SELECT ReqAlmacenCompra, TipoRequerimiento, CodAlmacen, FlagCajaChica
			FROM lg_clasificacion
			WHERE Clasificacion = '".$clasificacion."'";
	$query = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		echo ".".$field['ReqAlmacenCompra'].".".$field['TipoRequerimiento'].".".$field['FlagCajaChica'];
	}
	
}
elseif ($accion == "requerimientos_validar_modificar") {
	list($codorganismo, $codrequerimiento, $secuencia) = split("[.]", $codigo);
	$sql = "SELECT Estado
			FROM lg_requerimientos
			WHERE CodRequerimiento = '".$codrequerimiento."'";
	$query = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] == "CE") echo "¡ERROR: No se puede modificar un registro en estado 'Cerrado'!";
		elseif ($field['Estado'] == "CO") echo "¡ERROR: No se puede modificar un registro en estado 'Completado'!";
		elseif ($field['Estado'] == "RE") echo "¡ERROR: No se puede modificar un registro en estado 'Rechazado'!";
		elseif ($field['Estado'] == "AP") echo "¡ERROR: No se puede modificar un registro en estado 'Aprobado'!";
		elseif ($field['Estado'] == "CN") echo "¡ERROR: No se puede modificar un registro en estado 'Conformado'!";
		elseif ($field['Estado'] == "RV") echo "¡ERROR: No se puede modificar un registro en estado 'Revisado'!";
		elseif ($field['Estado'] == "AN") echo "¡ERROR: No se puede modificar un registro en estado 'Anulado'!";
	} else echo "¡ERROR: No se encontró el registro!";
	
}
elseif ($accion == "requerimientos_validar_anular") {
	list($codorganismo, $codrequerimiento, $secuencia) = split("[.]", $codigo);
	$sql = "SELECT Estado
			FROM lg_requerimientos
			WHERE CodRequerimiento = '".$codrequerimiento."'";
	$query = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] == "AN") echo "¡ERROR: No se puede anular un registro en estado 'Rechazado'!";
		elseif ($field['Estado'] == "CE") echo "¡ERROR: No se puede anular un registro en estado 'Cerrado'!";
		elseif ($field['Estado'] == "CO") echo "¡ERROR: No se puede anular un registro en estado 'Completado'!";
		elseif ($field['Estado'] == "AN") echo "¡ERROR: El registro ya se encuentra en estado 'Anulado'!";
	} else echo "¡ERROR: No se encontró el registro!";
	
}
elseif ($accion == "requerimientos_validar_cerrar") {
	list($codorganismo, $codrequerimiento, $secuencia) = split("[.]", $codigo);
	$sql = "SELECT Estado
			FROM lg_requerimientos
			WHERE CodRequerimiento = '".$codrequerimiento."'";
	$query = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] == "CE") echo "¡ERROR: No se puede cerrar un registro en estado 'Cerrado'!";
		elseif ($field['Estado'] == "AN") echo "¡ERROR: No se puede cerrar un registro en estado 'Anulado'!";
	} else echo "¡ERROR: No se encontró el registro!";
	
}

//	----------------
//	cotizaciones
//	----------------
elseif ($accion == "cotizaciones_invitar_proveedor_insertar") {
	$sql = "SELECT
				p.NomCompleto,
				pr.CodFormaPago
			FROM
				mastpersonas p
				LEFT JOIN mastproveedores pr ON (p.CodPersona = pr.CodProveedor)
			WHERE p.CodPersona = '".$codigo."'";
	$query = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		?>
    	<td align="center"><?=$nrodetalle?></td>
		<td>
        	<input type="hidden" name="codproveedor" value="<?=$codigo?>" />
        	<input type="hidden" name="nomproveedor" value="<?=($field['NomCompleto'])?>" />
            <?=($field['NomCompleto'])?>
        </td>
		<td align="center">
        	<select name="codformapago" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';">
            	<?=loadSelect("mastformapago", "CodFormaPago", "Descripcion", $field['CodFormaPago'], 0)?>
            </select>
        </td>
		<?
		echo "|$codigo";
	}
}
elseif ($accion == "cotizaciones_invitar_proveedor_ver_invitaciones") {
	list($organismo, $requerimiento, $secuencia) = split("[.]", $requerimiento);
	$sql = "SELECT
				c.*,
				rd.CodItem,
				rd.CommoditySub,
				rd.Descripcion,
				rd.CantidadPedida
			FROM
				lg_cotizacion c
				INNER JOIN lg_requerimientosdet rd ON (c.CodOrganismo = rd.CodOrganismo AND 
													   c.CodRequerimiento = rd.CodRequerimiento AND 
													   c.Secuencia = rd.Secuencia)
			WHERE
				c.CodRequerimiento = '".$requerimiento."' AND
				c.Secuencia = '".$secuencia."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	while ($field = mysql_fetch_array($query)) {
		$i++;
		?>
		<tr class="trListaBody">
			<td align="center"><?=$i?></td>
			<td><?=($field['NomProveedor'])?></td>
			<td align="center"><?=$field['CantidadPedida']?></td>
			<td align="center"><?=$field['CotizacionNumero']?></td>
			<td><?=$field['Condiciones']?></td>
			<td align="center"><?=formatFechaDMA($field['FechaInvitacion'])?></td>
		</tr>
		<?
	}
}
elseif ($accion == "cotizaciones_invitar_cotizar_insertar") {
	$sql = "SELECT				
				p.NomCompleto AS NomProveedor,
				mp.CodProveedor,
				mp.CodFormaPago,
				i.CodImpuesto,
				i.FactorPorcentaje
			FROM
				mastpersonas p
				INNER JOIN mastproveedores mp ON (p.CodPersona = mp.CodProveedor)
				LEFT JOIN masttiposervicioimpuesto tsi ON (mp.CodTipoServicio = tsi.CodTipoServicio)
				LEFT JOIN mastimpuestos i ON (tsi.CodImpuesto = i.CodImpuesto AND tsi.CodImpuesto = '".$_PARAMETRO["IGVCODIGO"]."')
			WHERE p.CodPersona = '".$codigo."'";
	$query_detalle = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query_detalle) != 0) {
		$field_detalle = mysql_fetch_array($query_detalle);
		if ($field_detalle['CodImpuesto'] == "") { $flagexon = "checked"; $dexon = "disabled"; }
		$flimite = getFechaFin(date("d-m-Y"), $_PARAMETRO['DIASLIMCOT']);
		?>
        <td align="center"><?=$nrodetalle?></td>
        
        <td align="center">
            <input type="hidden" name="codproveedor" value="<?=$field_detalle['CodProveedor']?>" />
            <?=$field_detalle['CodProveedor']?>
        </td>
        
        <td>
            <?=($field_detalle['NomProveedor'])?>
            <input type="hidden" name="nomproveedor" value="<?=($field_detalle['NomProveedor'])?>" />
        </td>
        
        <td align="center"><input type="radio" name="flagasig" id="flagasig_<?=$field_detalle['CodProveedor']?>" <?=$flagasig?> /></td>
        
        <td align="center">
            <input type="text" name="cant" value="<?=number_format($cantidad, 2, ',', '.')?>" style="width:97%; text-align:right;" class="cell" onblur="numeroBlur(this); this.className='cell';" onfocus="numeroFocus(this); this.className='cellFocus';" onchange="setMontosProveedorItem();" />
        </td>
        
        <td align="center">
            <input type="text" name="pu" value="0,00" style="width:97%; text-align:right;" class="cell" onblur="numeroBlur(this); this.className='cell';" onfocus="numeroFocus(this); this.className='cellFocus';" onchange="setMontosProveedorItem();" />
        </td>
        
        <td align="center">
            <input type="checkbox" name="flagexon" id="flagexon_<?=$field_detalle['CodProveedor']?>" value="<?=$field_detalle['FactorPorcentaje']?>" <?=$flagexon?> <?=$dexon?> onchange="setMontosProveedorItem();" />
        </td>
        
        <td align="center">
            <input type="text" name="pu_igv" value="0,00" style="width:98%; text-align:right;" class="cell" readonly="readonly" />
        </td>
        
        <td align="center">
            <input type="text" name="descp" value="0,00" style="width:97%; text-align:right;" class="cell" onblur="numeroBlur(this); this.className='cell';" onfocus="numeroFocus(this); this.className='cellFocus';" onchange="setMontosProveedorItem();" />
        </td>
        
        <td align="center">
            <input type="text" name="descf" value="0,00" style="width:97%; text-align:right;" class="cell" onblur="numeroBlur(this); this.className='cell';" onfocus="numeroFocus(this); this.className='cellFocus';" onchange="setMontosProveedorItem();" />
        </td>
        
        <td align="center">
            <input type="text" name="pu_total" value="0,00" style="width:98%; text-align:right;" class="cell" readonly="readonly" />
        </td>
        
        <td align="center">
            <input type="text" name="total" value="0,00" style="width:98%; text-align:right;" class="cell" readonly="readonly" />
        </td>
        
        <td align="center">
            <input type="text" name="comparar" value="0,00" style="width:98%; text-align:right;" class="cell" readonly="readonly" />
        </td>
        
        <td align="center">
            <input type="checkbox" name="flagmejor" id="flagmejor_<?=$field_detalle['CodProveedor']?>" onclick="this.checked=!this.checked" <?=$flagmejor?> />
        </td>
        
        <td align="center">
            <select name="formapago" style="width:99%;" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';">
                <?=loadSelect("mastformapago", "CodFormaPago", "Descripcion", $field_detalle['CodFormaPago'], 0)?>
            </select>
        </td>
        
        <td align="center">
            <input type="text" name="finvitacion" value="<?=date("d-m-Y")?>" style="width:99%; text-align:center;" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" onkeyup="setFechaDMA(this);" />
        </td>
        
        <td align="center">
            <input type="text" name="flimite" value="<?=$flimite?>" style="width:99%; text-align:center;" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" onkeyup="setFechaDMA(this);" />
        </td>
        
        <td align="center">
            <textarea name="condiciones" style="width:99%; height:15px;" class="cell" onBlur="this.className='cell'; this.style.height='15px';" onFocus="this.className='cellFocus'; this.style.height='50px';"></textarea>
        </td>
        
        <td align="center">
            <textarea name="observaciones" style="width:99%; height:15px;" class="cell" onBlur="this.className='cell'; this.style.height='15px';" onFocus="this.className='cellFocus'; this.style.height='50px';"></textarea>
        </td>
        
        <td align="center">
            <input type="text" name="dias" value="<?=$_PARAMETRO['DIASLIMCOT']?>" style="width:99%;" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" />
        </td>
        
        <td align="center">
            <input type="text" name="validez" value="0" style="width:99%;" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" />
        </td>
        
        <td align="center">
            <input type="text" name="nrocotizacion" style="width:99%;" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" />
        </td>
       
        <td align="center"><?=date("d-m-Y")?></td>
       
        <td align="center">&nbsp;</td>
		<?
		echo "|$codigo";
	}
}
elseif ($accion == "cotizaciones_invitaciones_ver_detalles") {
	$sql = "SELECT
				rd.CodItem,
				rd.CommoditySub,
				rd.Descripcion,
				rd.CodUnidad,
				rd.Secuencia,
				rd.CodCentroCosto,				
				c.Cantidad,
				c.PrecioUnit,
				c.PrecioUnitIva,
				c.PrecioCantidad,
				c.Total,
				c.FlagExonerado,
				r.CodInterno,
				r.FechaRequerida,
				r.Comentarios
			FROM
				lg_cotizacion c
				INNER JOIN lg_requerimientosdet rd ON (c.CodRequerimiento = rd.CodRequerimiento AND c.Secuencia = rd.Secuencia)
				INNER JOIN lg_requerimientos r ON (rd.CodRequerimiento = r.CodRequerimiento)
			WHERE c.NroCotizacionProv = '".$nrocotizacionprov."'";
	$query = mysql_query($sql) or die($sql.mysql_error());
	while ($field = mysql_fetch_array($query)) {
		if ($field['CodItem'] != "") $codigo = $field['CodItem']; else $codigo = $field['CommoditySub'];
		if (strlen($field['Comentarios']) > 200) $comentarios = substr($field['Comentarios'], 0, 200)."...";
		else $comentarios = $field['Comentarios'];
		?>
        <tr class="trListaBody">
            <td align="center"><?=$codigo?></td>
            <td><?=($field['Descripcion'])?></td>
            <td align="center"><?=$field['CodUnidad']?></td>
            <td align="right"><?=number_format($field['Cantidad'], 2, ',', '.')?></td>
            <td align="right"><?=number_format($field['PrecioUnit'], 2, ',', '.')?></td>
            <td align="right"><?=number_format($field['PrecioUnitIva'], 2, ',', '.')?></td>
            <td align="right"><?=number_format($field['PrecioCantidad'], 2, ',', '.')?></td>
            <td align="right"><?=number_format($field['Total'], 2, ',', '.')?></td>
            <td align="center"><?=printFlag($field['FlagExonerado'])?></td>
            <td align="center"><?=$field['CodInterno']?></td>
            <td align="center"><?=$field['Secuencia']?></td>
            <td align="center"><?=formatFechaDMA($field['FechaRequerida'])?></td>
            <td align="center"><?=$field['CodCentroCosto']?></td>
			<td title="<?=$field['Comentarios']?>"><?=($comentarios)?></td>
		</tr>
		<?
	}
}

//	----------------
//	compras
//	----------------
elseif ($accion == "compras_insertar_item") {
	if ($tipo == "item") {
		$readonly = "readonly";
		$sql = "SELECT
					*,
					CtaGasto AS CodCuenta,
					PartidaPresupuestal AS cod_partida,
					(SELECT Descripcion FROM ac_mastcentrocosto WHERE CodCentroCosto = '".$_PARAMETRO['CCOSTOCOMPRA']."') AS NomCentroCosto
				FROM lg_itemmast
				WHERE CodItem = '".$codigo."'";
		$disabled_descripcion = "disabled";
		$codigo_return = $codigo;
	} else {
		$sql = "SELECT
					cs.*,
					cm.Clasificacion,
					cm.Descripcion AS NomCommodity,
					(SELECT Descripcion FROM ac_mastcentrocosto WHERE CodCentroCosto = '".$_PARAMETRO['CCOSTOCOMPRA']."') AS NomCentroCosto
				FROM
					lg_commoditysub cs
					INNER JOIN lg_commoditymast cm ON (cs.CommodityMast = cm.CommodityMast)
				WHERE Codigo = '".$codigo."'";
		$codigo_return = "$codigo.$nrodetalle";
	}
	if ($impuesto == 0) {
		$flagexon = "checked";
		$dexon = "disabled";
	}
	$query_det = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query_det) != 0) {
		$field_det = mysql_fetch_array($query_det);
		if ($tipo == "item") $descripcion = $field_det['Descripcion'];
		else $descripcion = strtoupper($field_det['NomCommodity']."-".$field_det['Descripcion']);
		?>
        <td align="center"><?=$nrodetalle?></td>
        <td align="center"><input type="text" name="codigo" class="cell2" style="text-align:center;" value="<?=$codigo?>" readonly />
        <td align="center">
            <input type="text" name="descripcion" value="<?=($descripcion)?>" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" <?=$disabled_descripcion?> />
        </td>
        <td align="center">
            <input type="text" name="codunidad" value="<?=$field_det['CodUnidad']?>" class="cell2" style="text-align:center;" readonly />
        </td>
        <td align="center">
            <input type="text" name="cantidad" class="cell" style="text-align:right;" value="0,00" onBlur="numeroBlur(this); this.className='cell';" onFocus="numeroFocus(this); this.className='cellFocus';" onchange="setMontosCompras();" />
        </td>            
        <td align="center">
            <input type="text" name="pu" value="0,00" style="width:97%; text-align:right;" class="cell" onblur="numeroBlur(this); this.className='cell';" onfocus="numeroFocus(this); this.className='cellFocus';" onchange="setMontosCompras();" />
        </td>            
        <td align="center">
            <input type="text" name="descp" value="0,00" style="width:97%; text-align:right;" class="cell" onblur="numeroBlur(this); this.className='cell';" onfocus="numeroFocus(this); this.className='cellFocus';" onchange="setMontosCompras();" />
        </td>                
        <td align="center">
            <input type="text" name="descf" value="0,00" style="width:97%; text-align:right;" class="cell" onblur="numeroBlur(this); this.className='cell';" onfocus="numeroFocus(this); this.className='cellFocus';" onchange="setMontosCompras();" />
        </td>            
        <td align="center">
            <input type="checkbox" name="flagexon" value="<?=$impuesto?>" onchange="setMontosCompras();" <?=$flagexon?> <?=$dexon?> />
        </td>            
        <td align="center">
            <input type="text" name="pu_total" value="0,00" style="width:98%; text-align:right;" class="cell" readonly="readonly" />
        </td>
        <td align="center">
            <input type="text" name="total" value="0,00" style="width:98%; text-align:right;" class="cell" readonly="readonly" />
        </td>            
        <td align="center">
            <input type="text" name="fentrega" value="<?=getFechaFin(date("d-m-Y"), $_PARAMETRO['DIAENTOC'])?>" style="width:99%; text-align:center;" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" onkeyup="setFechaDMA(this);" />
        </td>                
        <td align="right">0,00</td>
        <td align="center">
            <input type="text" name="codccosto" id="codccosto_<?=$codigo_return?>" class="cell2" style="text-align:center;" value="<?=$_PARAMETRO['CCOSTOCOMPRA']?>" readonly />
            <input type="hidden" name="nomccosto" id="nomccosto_<?=$codigo_return?>" value="<?=($field_det['NomCentroCosto'])?>" />
        </td>                
        <td align="center"><?=printValores("ESTADO-COMPRA", "PR")?></td>
        <td align="center">
            <input type="hidden" name="codpartida" value="<?=$field_det['cod_partida']?>" />
            <?=$field_det['cod_partida']?>
        </td>
        <td align="center">
            <input type="hidden" name="codcuenta" value="<?=$field_det['CodCuenta']?>" />
            <?=$field_det['CodCuenta']?>
        </td>            
        <td align="center">
            <textarea name="observaciones" style="width:99%; height:15px;" class="cell" onBlur="this.className='cell'; this.style.height='15px';" onFocus="this.className='cellFocus'; this.style.height='50px';"><?=($comentarios)?></textarea>
		<?
		echo "|$codigo_return|$field_det[Clasificacion]";
	}
}
elseif ($accion == "compras_validar_modificar") {
	list($anio, $organismo, $nroorden) = split("[.]", $codigo);
	$sql = "SELECT Estado
			FROM lg_ordencompra
			WHERE
				Anio = '".$anio."' AND
				CodOrganismo = '".$organismo."' AND
				NroOrden = '".$nroorden."'";
	$query = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] == "CE") echo "¡ERROR: No se puede modificar un registro en estado 'Cerrado'!";
		elseif ($field['Estado'] == "CO") echo "¡ERROR: No se puede modificar un registro en estado 'Completado'!";
		elseif ($field['Estado'] == "RE") echo "¡ERROR: No se puede modificar un registro en estado 'Rechazado'!";
		elseif ($field['Estado'] == "AP") echo "¡ERROR: No se puede modificar un registro en estado 'Aprobado'!";
		elseif ($field['Estado'] == "CN") echo "¡ERROR: No se puede modificar un registro en estado 'Conformado'!";
		elseif ($field['Estado'] == "RV") echo "¡ERROR: No se puede modificar un registro en estado 'Revisado'!";
		elseif ($field['Estado'] == "AN") echo "¡ERROR: No se puede modificar un registro en estado 'Anulado'!";
	} else echo "¡ERROR: No se encontró el registro!";
	
}
elseif ($accion == "compras_validar_anular") {
	list($anio, $organismo, $nroorden) = split("[.]", $codigo);
	$sql = "SELECT Estado
			FROM lg_ordencompra
			WHERE
				Anio = '".$anio."' AND
				CodOrganismo = '".$organismo."' AND
				NroOrden = '".$nroorden."'";
	$query = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] == "RE") echo "¡ERROR: No se puede anular un registro en estado 'Rechazado'!";
		elseif ($field['Estado'] == "CE") echo "¡ERROR: No se puede anular un registro en estado 'Cerrado'!";
		elseif ($field['Estado'] == "CO") echo "¡ERROR: No se puede anular un registro en estado 'Completado'!";
		elseif ($field['Estado'] == "AN") echo "¡ERROR: El registro ya se encuentra en estado 'Anulado'!";
	} else echo "¡ERROR: No se encontró el registro!";
	
}
elseif ($accion == "compras_validar_cerrar") {
	list($anio, $organismo, $nroorden) = split("[.]", $codigo);
	$sql = "SELECT Estado
			FROM lg_ordencompra
			WHERE
				Anio = '".$anio."' AND
				CodOrganismo = '".$organismo."' AND
				NroOrden = '".$nroorden."'";
	$query = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] == "RE") echo "¡ERROR: No se puede cerrar un registro en estado 'Rechazado'!";
		elseif ($field['Estado'] == "AN") echo "¡ERROR: No se puede cerrar un registro en estado 'Anulado'!";
		elseif ($field['Estado'] == "CE") echo "¡ERROR: El registro ya se encuentra en estado 'Cerrado'!";
	} else echo "¡ERROR: No se encontró el registro!";
	
}

//	----------------
//	servicios
//	----------------
elseif ($accion == "servicios_insertar_item") {
	if ($impuesto == 0) {
		$flagexon = "checked";
		$dexon = "disabled";
	}
	$codigo_return = "$codigo.$nrodetalle";
	
	if ($ccosto == "") {
		$ccosto = $_PARAMETRO['CCOSTOCOMPRA'];
		$sql = "SELECT Abreviatura FROM ac_mastcentrocosto WHERE CodCentroCosto = '".$ccosto."'";
		$query_cc = mysql_query($sql) or die($sql.mysql_error());
		if (mysql_num_rows($query_cc) != 0) $field_cc = mysql_fetch_array($query_cc);
		$nomccosto = $field_cc['Abreviatura'];
	}	
	
	$sql = "SELECT
				cs.*,
				cm.Clasificacion,
				cm.Descripcion AS NomCommodity
			FROM
				lg_commoditysub cs
				INNER JOIN lg_commoditymast cm ON (cs.CommodityMast = cm.CommodityMast)
			WHERE cs.Codigo = '".$codigo."'";
	$query_det = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query_det) != 0) {
		$field_det = mysql_fetch_array($query_det);
		$descripcion = strtoupper($field_det['NomCommodity']."-".$field_det['Descripcion']);
		?>
        <td align="center"><?=$nrodetalle?></td>
        <td align="center"><input type="text" name="codigo" class="cell2" style="text-align:center;" value="<?=$codigo?>" readonly />
        <td align="center">
            <input type="text" name="descripcion" value="<?=($descripcion)?>" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" />
        </td>
        <td align="center">
            <input type="text" name="cantidad" class="cell" style="text-align:right;" onBlur="numeroBlur(this); this.className='cell';" onFocus="numeroFocus(this); this.className='cellFocus';" onchange="setMontosServicios();" value="0,00" />
        </td>
        <td align="center">
            <input type="text" name="pu" style="width:97%; text-align:right;" class="cell" onblur="numeroBlur(this); this.className='cell';" onfocus="numeroFocus(this); this.className='cellFocus';" onchange="setMontosServicios();" value="0,00" />
        </td>
        <td align="center">
            <input type="checkbox" name="flagexon" value="<?=$impuesto?>" onchange="setMontosServicios();" <?=$flagexon?> <?=$dexon?> />
        </td>
        <td align="center">
            <input type="text" name="total" style="width:98%; text-align:right;" class="cell" readonly="readonly" value="0,00" />
        </td>
        <td align="center">
            <input type="text" name="fesperada" value="<?=$fentrega?>" style="width:99%; text-align:center;" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" onkeyup="setFechaDMA(this);" />
        </td>
        <td align="center">
            <input type="text" name="freal" value="<?=$fentrega?>" style="width:99%; text-align:center;" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" onkeyup="setFechaDMA(this);" />
        </td>
        <td align="right">0,00</td>
        <td align="center">
            <input type="hidden" name="codccosto" id="codccosto_<?=$codigo_return?>" value="<?=$ccosto?>" readonly />
            <input type="text" name="nomccosto" id="nomccosto_<?=$codigo_return?>" value="<?=$nomccosto?>" class="cell2" style="text-align:center;" readonly="readonly" />
        </td>
        <td align="center">
            <input type="hidden" name="codactivo" id="codactivo_<?=$codigo_return?>" readonly />
            <input type="text" name="nomactivo" id="nomactivo_<?=$codigo_return?>" class="cell2" readonly="readonly" />
        </td>
        <td align="center">
            <input type="checkbox" name="flagterminado" />
        </td>
        <td align="center">
            <input type="hidden" name="codpartida" value="<?=$field_det['cod_partida']?>" />
            <?=$field_det['cod_partida']?>
        </td>
        <td align="center">
            <input type="hidden" name="codcuenta" value="<?=$field_det['CodCuenta']?>" />
            <?=$field_det['CodCuenta']?>
        </td>            
        <td align="center">
            <textarea name="observaciones" style="width:99%; height:15px;" class="cell" onBlur="this.className='cell'; this.style.height='15px';" onFocus="this.className='cellFocus'; this.style.height='50px';"><?=$comentarios?></textarea>
		</td>
		<?
		echo "|$codigo_return";
	}
}
elseif ($accion == "servicios_validar_modificar") {
	list($anio, $organismo, $nroorden) = split("[.]", $codigo);
	$sql = "SELECT Estado
			FROM lg_ordenservicio
			WHERE
				Anio = '".$anio."' AND
				CodOrganismo = '".$organismo."' AND
				NroOrden = '".$nroorden."'";
	$query = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] == "CE") echo "¡ERROR: No se puede modificar un registro en estado 'Cerrado'!";
		elseif ($field['Estado'] == "CO") echo "¡ERROR: No se puede modificar un registro en estado 'Completado'!";
		elseif ($field['Estado'] == "RE") echo "¡ERROR: No se puede modificar un registro en estado 'Rechazado'!";
		elseif ($field['Estado'] == "AP") echo "¡ERROR: No se puede modificar un registro en estado 'Aprobado'!";
		elseif ($field['Estado'] == "CN") echo "¡ERROR: No se puede modificar un registro en estado 'Conformado'!";
		elseif ($field['Estado'] == "RV") echo "¡ERROR: No se puede modificar un registro en estado 'Revisado'!";
		elseif ($field['Estado'] == "AN") echo "¡ERROR: No se puede modificar un registro en estado 'Anulado'!";
	} else echo "¡ERROR: No se encontró el registro!";
	
}
elseif ($accion == "servicios_validar_anular") {
	list($anio, $organismo, $nroorden) = split("[.]", $codigo);
	$sql = "SELECT Estado
			FROM lg_ordenservicio
			WHERE
				Anio = '".$anio."' AND
				CodOrganismo = '".$organismo."' AND
				NroOrden = '".$nroorden."'";
	$query = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] == "RE") echo "¡ERROR: No se puede anular un registro en estado 'Rechazado'!";
		elseif ($field['Estado'] == "CE") echo "¡ERROR: No se puede anular un registro en estado 'Cerrado'!";
		elseif ($field['Estado'] == "CO") echo "¡ERROR: No se puede anular un registro en estado 'Completado'!";
		elseif ($field['Estado'] == "AN") echo "¡ERROR: El registro ya se encuentra en estado 'Anulado'!";
	} else echo "¡ERROR: No se encontró el registro!";
	
}
elseif ($accion == "servicios_validar_cerrar") {
	list($anio, $organismo, $nroorden) = split("[.]", $codigo);
	$sql = "SELECT Estado
			FROM lg_ordenservicio
			WHERE
				Anio = '".$anio."' AND
				CodOrganismo = '".$organismo."' AND
				NroOrden = '".$nroorden."'";
	$query = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] == "RE") echo "¡ERROR: No se puede cerrar un registro en estado 'Rechazado'!";
		elseif ($field['Estado'] == "AN") echo "¡ERROR: No se puede cerrar un registro en estado 'Anulado'!";
		elseif ($field['Estado'] == "CE") echo "¡ERROR: El registro ya se encuentra en estado 'Cerrado'!";
	} else echo "¡ERROR: No se encontró el registro!";
	
}

//	----------------
//	almacen
//	----------------
elseif ($accion == "transacciones_especiales_detalle_ingreso_oc") {
	list($anio, $codorganismo, $nroorden) = split("[.]", $orden);
	$sql = "SELECT ocd.*
			FROM lg_ordencompradetalle ocd
			WHERE 
				ocd.Anio = '".$anio."' AND
				ocd.CodOrganismo = '".$codorganismo."' AND
				ocd.NroOrden = '".$nroorden."' AND
				ocd.Estado = 'PE'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {
		$pendiente = $field['CantidadPedida'] - $field['CantidadRecibida'];
		?>
		<tr class="trListaBody" onclick="mClkMulti(this);" id="row_ingreso<?=$field['Secuencia']?>">
			<td align="center"><?=$field['Secuencia']?></td>
			<td align="center">
            	<input type="checkbox" name="detalle_ingreso" id="ingreso<?=$field['Secuencia']?>" value="<?=$field['Secuencia']?>" style="display:none;" />
				<?=$field['CommoditySub']?>
			</td>
			<td><?=($field['Descripcion'])?></td>
			<td align="center"><?=$field['CodUnidad']?></td>
			<td align="right"><?=number_format($field['StockActual'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field['CantidadPedida'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field['CantidadRecibida'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($pendiente, 2, ',', '.')?></td>
			<td align="right"><?=number_format($field['PrecioUnit'], 2, ',', '.')?></td>
			<td align="center"><?=$field['CodCentroCosto']?></td>
		</tr>
		<?
	}
}
elseif ($accion == "transacciones_especiales_detalle_despacho_oc") {
	list($anio, $codorganismo, $nroorden) = split("[.]", $orden);
	$sql = "SELECT
				ocd.Secuencia,
				ocd.CommoditySub,
				ocd.Descripcion,
				ocd.CodUnidad,
				rd.CantidadPedida,
				rd.CantidadRecibida,
				(rd.CantidadPedida - rd.CantidadRecibida) AS CantidadPendiente,
				rd.CodCentroCosto,				
				rd.CodRequerimiento,
				rd.Docreferencia
			FROM
				lg_ordencompradetalle ocd
				INNER JOIN lg_requerimientosdet rd ON (ocd.Anio = rd.Anio AND
													   ocd.CodOrganismo = rd.CodOrganismo AND
													   ocd.NroOrden = rd.NroOrden AND
													   ocd.Secuencia = rd.OrdenSecuencia)
			WHERE 
				rd.Anio = '".$anio."' AND
				rd.CodOrganismo = '".$codorganismo."' AND
				rd.NroOrden = '".$nroorden."' AND
				ocd.Estado <> 'PE'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {
		?>
		<tr class="trListaBody" onclick="mClkMulti(this);" id="row_despacho<?=$field['Secuencia']?>">
			<td align="center"><?=$field['CodRequerimiento']?></td>
			<td align="center"><?=$field['Secuencia']?></td>
			<td align="center">
            	<input type="checkbox" name="detalle_despacho" id="despacho<?=$field['Secuencia']?>" value="<?=$field['Secuencia']?>" style="display:none;" />
				<?=$field['CommoditySub']?>
			</td>
			<td><?=($field['Descripcion'])?></td>
			<td align="center"><?=$field['CodUnidad']?></td>
			<td align="right"><?=number_format($field['CantidadPedida'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field['CantidadRecibida'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field['CantidadPendiente'], 2, ',', '.')?></td>
			<td align="center"><?=$field['CodCentroCosto']?></td>
		</tr>
		<?
	}
}
elseif ($accion == "transacciones_especiales_validar") {
	list($anio, $codorganismo, $nroorden) = split("[.]", $orden);
	//	verifico si existe un periodo abierto para generar transacciones
	$sql = "SELECT *
			FROM lg_periodocontrol
			WHERE
				CodOrganismo = '".$codorganismo."' AND
				Periodo = '".date("Y-m")."' AND
				FlagTransaccion = 'S' AND
				Estado = 'A'";
	$query_periodos = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query_periodos) == 0) die("¡ERROR: No se puede generar ninguna transacción porque no se ha abierto ningún periodo!");
	//	-----------------------------------------------------------------------
	//	verifico que los detalles pertenezcan al mismo centro de costo
	/*
	$detalle = split(";", $codigo);	$z = 0;
	foreach ($detalle as $secuencia) {	$z++;
		$sql = "SELECT 
					rd.CodCentroCosto,
					cc.Descripcion AS NomCentroCosto
				FROM
					lg_ordencompradetalle rd
					INNER JOIN ac_mastcentrocosto cc ON (rd.CodCentroCosto = cc.CodCentroCosto)
				WHERE 
					rd.Anio = '".$anio."' AND 
					rd.CodOrganismo = '".$codorganismo."' AND 
					rd.NroOrden = '".$nroorden."' AND 
					rd.Secuencia = '".$secuencia."'";
		$query_ocd = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_ocd) != 0) { 
			$field_ocd = mysql_fetch_array($query_ocd);		
			if ($z > 1 && $ccosto != $field_ocd['CodCentroCosto']) die("¡ERROR: No puede generar una transacción con items de diferentes centros de costos!");
			$ccosto = $field_ocd['CodCentroCosto'];
			$nomccosto = $field_ocd['NomCentroCosto'];
		}
	}
	*/
	//	-----------------------------------------------------------------------
}
?>