<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Disponibilidad Presupuestaria</td>
		<td align="right"><a class="cerrar" href="#" onclick="window.close();">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<table width="1000" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" class="current" onclick="currentTab('tab', this);">
            	<a href="#" onclick="mostrarTab('tab', '1', 2)">General</a>
            </li>
            <li id="li2" onclick="currentTab('tab', this);">
            	<a href="#" onclick="mostrarTab('tab', '2', 2);">Detallada</a>
            </li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<center>
<div id="tab1" style="display:block;">
<div style="overflow:scroll; width:995px; height:400px;">
<table width="100%" class="tblLista">
	<thead>
	<tr class="trListaHead">
        <th scope="col" width="90">Partida</th>
        <th scope="col">Descripci&oacute;n</th>
        <th scope="col" width="50">Nro. Items</th>
        <th scope="col" width="100">Monto</th>
        <th scope="col" width="100">Disponible</th>
        <th scope="col" width="100">Diferencia</th>
    </tr>
    </thead>
    
    <tbody>        
    <?php
	//	obtengo detalles
	$MontoAfecto = 0;
	$_cod_partida_igv = $_PARAMETRO["IVADEFAULT"];
	$detalle = split(";char:tr;", $detalles);
	foreach ($detalle as $linea) {
		list($_CodItem, $_CommoditySub, $_CantidadPedida, $_PrecioUnit, $_FlagExonerado, $_cod_partida) = split(";char:td;", $linea);
		$PrecioCantidad = $_PrecioUnit * $_CantidadPedida;
		$_PARTIDA[$_cod_partida] = $_cod_partida;
		$_PARTIDA_MONTO[$_cod_partida] += $PrecioCantidad;
		$_PARTIDA_ITEMS[$_cod_partida]++;
		if ($_FlagExonerado == "N") $MontoAfecto += $PrecioCantidad;
	}
	$_PARTIDA[$_cod_partida_igv] = $_cod_partida_igv;
	$_PARTIDA_MONTO[$_cod_partida_igv] = $MontoAfecto * $FactorImpuesto / 100;
	
	//	imprimo partidas
	$detalle = split(";char:tr;", $detalles_partida);
	foreach ($detalle as $linea) {
		list($_cod_partida, $_CodCuenta, $_Monto, $_MontoDisponible, $_MontoPendiente) = split(";char:td;", $linea);
		$_Descripcion = getValorCampo("pv_partida", "cod_partida", "denominacion", $_cod_partida);
		##	valido
		if ($_MontoDisponible < $_Monto) $style = "style='font-weight:bold; background-color:#F8637D;'";
		elseif ($_MontoDisponible < ($_Monto + $_MontoPendiente) && $Estado == "PR") $style = "style='font-weight:bold; background-color:#FFC;'";
		else $style = "style='font-weight:bold; background-color:#D0FDD2;'";
		##
		$_Diferencia = $_MontoDisponible - $_Monto;
		$_PARTIDA_DISPONIBLE[$_cod_partida] = $_MontoDisponible;
		?>
        <tr class="trListaBody" <?=$style?>>
            <td align="center">
                <?=$_cod_partida?>
            </td>
            <td>
                <?=$_Descripcion?>
            </td>
            <td align="right">
                <?=number_format($_PARTIDA_ITEMS[$_cod_partida], 2, ',', '.')?>
            </td>
            <td align="right">
                <?=number_format($_Monto, 2, ',', '.')?>
            </td>
            <td align="right">
                <?=number_format($_MontoDisponible, 2, ',', '.')?>
            </td>
            <td align="right">
                <?=number_format($_Diferencia, 2, ',', '.')?>
            </td>
        </tr>
        <?
	}
	?>
    </tbody>
</table>
</div>
</div>

<div id="tab2" style="display:none;">
<div style="overflow:scroll; width:995px; height:400px;">
<table width="100%" class="tblLista">
	<thead>
	<tr class="trListaHead">
        <th scope="col" width="75">Item / Commodity</th>
        <th scope="col">Descripci&oacute;n</th>
        <th scope="col" width="75">Partida</th>
        <th scope="col" width="60">Cant.</th>
        <th scope="col" width="100">Precio Unit.</th>
        <th scope="col" width="100">Total</th>
        <th scope="col" width="100">Disponible</th>
    </tr>
    </thead>
    
    <tbody>        
    <?php
	//	obtengo detalles
	$MontoAfecto = 0;
	$_cod_partida_igv = $_PARAMETRO["IVADEFAULT"];
	$detalle = split(";char:tr;", $detalles);
	foreach ($detalle as $linea) {		
		list($_CodItem, $_CommoditySub, $_CantidadPedida, $_PrecioUnit, $_FlagExonerado, $_cod_partida) = split(";char:td;", $linea);
		$PrecioCantidad = $_PrecioUnit * $_CantidadPedida;
		if ($_CodItem != "") $Descripcion = getValorCampo("lg_itemmast", "CodItem", "Descripcion", $_CodItem);
		else $Descripcion = getValorCampo("lg_commoditysub", "Codigo", "Descripcion", $_CommoditySub);
		$diferencia = $_PARTIDA_DISPONIBLE[$_cod_partida] - $PrecioCantidad;
		if ($diferencia < 0) $style = "style='font-weight:bold; background-color:#F8637D;'";
		else $style = "style='font-weight:bold; background-color:#D0FDD2;'";
		?>
		<tr class="trListaBody" <?=$style?>>
			<td align="center">
				<?=$_CodItem?><?=$_CommoditySub?>
			</td>
			<td>
				<?=$Descripcion?>
			</td>
			<td align="center">
				<?=$_cod_partida?>
			</td>
			<td align="center">
				<?=$_CantidadPedida?>
			</td>
			<td align="right">
				<?=number_format($_PrecioUnit, 2, ',', '.')?>
			</td>
			<td align="right">
				<?=number_format($PrecioCantidad, 2, ',', '.')?>
			</td>
			<td align="right">
				<?=number_format($_PARTIDA_DISPONIBLE[$_cod_partida], 2, ',', '.')?>
			</td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
</div>
</center>

<table width="995" align="center">
	<tr>
    	<td width="35"><div style="background-color:#F8637D; width:25px; height:20px;"></div></td>
        <td>Sin disponibilidad presupuestaria</td>
    	<td width="35"><div style="background-color:#D0FDD2; width:25px; height:20px;"></div></td>
        <td>Disponibilidad presupuestaria</td>
    	<td width="35"><div style="background-color:#FFC; width:25px; height:20px;"></div></td>
        <td>Disponibilidad presupuestaria (Tiene ordenes pendientes)</td>
    </tr>
</table>