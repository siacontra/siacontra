<?php
include("../../lib/fphp.php");
include("fphp.php");
//	--------------------------

//	--------------------------

//	inserto linea en la lista de distribucion en la obligacion
if ($accion == "obligacion_distribucion_insertar") {
	if (!afectaTipoServicio($CodTipoServicio)) { $cFlagNoAfectoIGV = "checked"; $dFlagNoAfectoIGV = "disabled"; }
	if ($FlagPresupuesto == "checked") $disabled_presupuesto = ""; else $disabled_presupuesto = "disabled";
	?>
    <th><?=$nrodetalle?></th>
    <td align="center">
        <input type="text" name="cod_partida" id="cod_partida_<?=$nrodetalle?>" style="width:20%;" maxlength="12" class="cell cod_partida" onChange="getDescripcionLista2('accion=getDescripcionPartidaCuenta', this, $('#NomPartida_<?=$nrodetalle?>'), $('#CodCuenta_<?=$nrodetalle?>'), $('#NomCuenta_<?=$nrodetalle?>'));" <?=$disabled_presupuesto?> />
        <input type="text" name="NomPartida" id="NomPartida_<?=$nrodetalle?>" style="width:75%;" class="cell2" readonly="readonly" />
    </td>
    <td align="center">
        <input type="text" name="CodCuenta" id="CodCuenta_<?=$nrodetalle?>" maxlength="13" style="width:20%;" class="cell" onChange="getDescripcionLista2('accion=getDescripcionCuenta', this, $('#NomCuenta_<?=$nrodetalle?>'));" />
        <input type="text" name="NomCuenta" id="NomCuenta_<?=$nrodetalle?>" style="width:75%;" class="cell2" readonly="readonly" />
    </td>
    <td align="center">
        <input type="text" name="CodCentroCosto" id="CodCentroCosto_<?=$nrodetalle?>" value="<?=$CodCentroCosto?>" style="text-align:center;" class="cell" onChange="getDescripcionLista2('accion=getDescripcionCCosto', this, $('#NomCentroCosto_<?=$nrodetalle?>'));" />
        <input type="hidden" name="NomCentroCosto" id="NomCentroCosto_<?=$nrodetalle?>" />
    </td>
    <td align="center">
        <input type="checkbox" name="FlagNoAfectoIGV" class="FlagNoAfectoIGV" <?=$cFlagNoAfectoIGV?> <?=$dFlagNoAfectoIGV?> onchange="actualizarMontosObligacion();" />
    </td>
    <td align="center">
        <input type="text" name="Monto" value="0,00" style="text-align:right;" class="cell" onfocus="numeroFocus(this);" onblur="numeroBlur(this);" onchange="actualizarMontosObligacion();" />
    </td>
    <td align="center">
        <input type="text" name="TipoOrden" maxlength="2" style="width:10%;" class="cell" />
        <input type="text" name="NroOrden" maxlength="100" style="width:82%;" class="cell" />
    </td>
    <td align="center">
        <input type="text" name="Referencia" class="cell" maxlength="25" />
    </td>
    <td align="center">
        <input type="text" name="Descripcion" class="cell" maxlength="255" />
    </td>
    <td align="center">
        <input type="text" name="CodPersona" id="CodPersona_<?=$nrodetalle?>" maxlength="6" style="text-align:center;" class="cell" onChange="getDescripcionLista2('accion=getDescripcionPersona', this, $('#NomPersona_<?=$nrodetalle?>'));" />
        <input type="hidden" name="NomPersona" id="NomPersona_<?=$nrodetalle?>" />
    </td>
    <td align="center">
        <input type="text" name="NroActivo" id="NroActivo_<?=$nrodetalle?>" maxlength="15" style="text-align:center;" class="cell2" readonly="readonly" />
    </td>
    <td align="center">
        <input type="checkbox" name="FlagDiferido" />
    </td>
	<?
}
//	--------------------------

//	consulto si se puede modificar una obligacion
elseif ($accion == "obligacion_modificar") {
	list($CodOrganismo, $CodProveedor, $CodTipoDocumento, $NroDocumento) = split("[.]", $codigo);
	$sql = "SELECT Estado
			FROM ap_obligaciones
			WHERE
				CodOrganismo = '".$CodOrganismo."' AND
				CodProveedor = '".$CodProveedor."' AND
				CodTipoDocumento = '".$CodTipoDocumento."' AND
				NroDocumento = '".$NroDocumento."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] != "PR") die("No se puede modificar esta obligación");
	} else die("No se encuentra el registro");
}
//	--------------------------

//	consulto si se puede anular una obligacion
elseif ($accion == "obligacion_anular") {
	list($CodOrganismo, $CodProveedor, $CodTipoDocumento, $NroDocumento) = split("[.]", $codigo);
	$sql = "SELECT Estado
			FROM ap_obligaciones
			WHERE
				CodOrganismo = '".$CodOrganismo."' AND
				CodProveedor = '".$CodProveedor."' AND
				CodTipoDocumento = '".$CodTipoDocumento."' AND
				NroDocumento = '".$NroDocumento."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] == "AN") die("La obligación ya se encuentra <strong>Anulada</strong>");
		elseif ($field['Estado'] == "AP") die("No se puede anular una obligación <strong>Aprobada</strong><br />Debe anular primero la <strong>Orden de Pago</strong> generada");
		elseif ($field['Estado'] == "PA") die("No se puede anular una obligación <strong>Pagada</strong><br />Debe anular primero el <strong>Pago</strong> y despues la <strong>Orden de Pago</strong> generada");
	} else die("No se encuentra el registro");
}
//	--------------------------

//	muestro los detalles de los documentos seleccionados en la facturacion de logisticas
elseif ($accion == "mostrarDocumentosObligacion") {
	//	documentos
	if ($detalles_documento != "") {
		$linea_documento = split(";", $detalles_documento);	$_Linea=0;
		foreach ($linea_documento as $registro) {	$_Linea++;
			list($_Anio, $_DocumentoReferencia) = split("[.]", $registro);
			if ($grupo != $_DocumentoReferencia) {
				$grupo = $_DocumentoReferencia;
				?><tr class="trListaBody2"><td colspan="7">Documento: <?=$_DocumentoReferencia?></td></tr><?
			}
			//	consulto
			$sql = "SELECT * 
					FROM ap_documentosdetalle 
					WHERE 
						Anio = '".$_Anio."' AND
						CodProveedor = '".$CodProveedor."' AND
						DocumentoClasificacion = '".$DocumentoClasificacion."' AND
						DocumentoReferencia = '".$_DocumentoReferencia."'
					ORDER BY Secuencia";
			$query_det = mysql_query($sql) or die ($sql.mysql_error());
			$rows_det = mysql_num_rows($query_det); $suma_rows_det += $rows_det;
			while ($field_det = mysql_fetch_array($query_det)) { $i++;
				if ($field_det['CodItem'] != "") $coddetalle = $field_det['CodItem']; else $coddetalle = $field_det['CommoditySub'];
				$total = $field_det['Cantidad'] * $field_det['PrecioUnit'];
				?>
				<tr class="trListaBody">
					<td align="center"><?=$i?></td>
					<td align="center"><?=$coddetalle?></td>
					<td><?=($field_det['Descripcion'])?></td>
					<td align="center"><?=$field_det['CodCentroCosto']?></td>
					<td align="right"><?=number_format($field_det['Cantidad'], 2, ',', '.')?></td>
					<td align="right"><?=number_format($field_det['PrecioUnit'], 2, ',', '.')?></td>
					<td align="right"><?=number_format($total, 2, ',', '.')?></td>
				</tr>
				<?
			}
		}
	}
}
//	--------------------------

//	consulto si se puede modificar una orden
elseif ($accion == "orden_pago_modificar") {
	list($Anio, $CodOrganismo, $NroOrden) = split("[.]", $codigo);
	$sql = "SELECT Estado
			FROM ap_ordenpago
			WHERE
				Anio = '".$Anio."' AND
				CodOrganismo = '".$CodOrganismo."' AND
				NroOrden = '".$NroOrden."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] == "AN") die("No se puede modificar esta orden");
	} else die("No se encuentra el registro");
}
//	--------------------------

//	consulto si se puede anular una orden
elseif ($accion == "orden_pago_anular") {
	list($Anio, $CodOrganismo, $NroOrden) = split("[.]", $codigo);
	$sql = "SELECT Estado
			FROM ap_ordenpago
			WHERE
				Anio = '".$Anio."' AND
				CodOrganismo = '".$CodOrganismo."' AND
				NroOrden = '".$NroOrden."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] == "AN") die("La orden ya se encuentra <strong>Anulada</strong>");
		elseif ($field['Estado'] == "PA") die("No se puede anular una orden <strong>Pagada</strong><br />Debe anular primero el <strong>Pago</strong>");
	} else die("No se encuentra el registro");
}
//	--------------------------

//	consulto si se puede modificar un pago
elseif ($accion == "pago_modificar") {
	list($NroProceso, $Secuencia, $CodTipoPago) = split("[.]", $codigo);
	$sql = "SELECT Estado
			FROM ap_pagos
			WHERE
				NroProceso = '".$NroProceso."' AND
				Secuencia = '".$Secuencia."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] == "AN") die("No se puede modificar un pago <strong>Anulado</strong>");
	} else die("No se encuentra el registro");
}
//	--------------------------

//	consulto si se puede anular un pago
elseif ($accion == "pago_anular") {
	list($NroProceso, $Secuencia, $CodTipoPago) = split("[.]", $codigo);
	$sql = "SELECT Estado
			FROM ap_pagos
			WHERE
				NroProceso = '".$NroProceso."' AND
				Secuencia = '".$Secuencia."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] == "AN") die("El pago ya se encuentra <strong>Anulado</strong>");
	} else die("No se encuentra el registro");
}
//	--------------------------

//	
elseif ($accion == "documentos_prepago") {
	list($NroProceso, $Secuencia) = split("[.]", $registro);
	$sql = "SELECT
				p.Secuencia,
				p.NomProveedorPagar,
				p.MontoPago,
				p.MontoRetenido,
				(p.MontoPago + MontoRetenido) AS MontoPagar,
				mp.NomCompleto AS NomProveedor
			FROM
				ap_pagos p
				INNER JOIN mastpersonas mp ON (p.CodProveedor = mp.CodPersona)
			WHERE
				p.NroProceso = '".$NroProceso."' AND
				p.Secuencia = '".$Secuencia."'
			ORDER BY Secuencia";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field = mysql_fetch_array($query)) {
		?>
		<tr class="trListaBody">
			<th align="center"><?=$field['Secuencia']?></th>
			<td><?=($field['NomProveedorPagar'])?></td>
			<td><?=($field['NomProveedor'])?></td>
			<td align="right"><?=number_format($field['MontoPagar'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field['MontoRetenido'], 2, ',', '.')?></td>
			<td align="right"><strong><?=number_format($field['MontoPago'], 2, ',', '.')?></strong></td>
		</tr>
		<?
	}
}
//	--------------------------

//	
elseif ($accion == "mostrarTabDistribucionObligacion") {
	$Anio = substr(formatFechaAMD($FechaRegistro), 0, 4);
	//	obtengo detalles
	$_TOTAL = 0;
	$detalle = split(";char:tr;", $detalles);
	foreach ($detalle as $linea) {
		list($_cod_partida, $_CodCuenta, $_Monto) = split(";char:td;", $linea);
		if ($_codpartida != "" || $_CodCuenta != "") {
			$_CUENTA[$_CodCuenta] = $_CodCuenta;
			$_PARTIDA[$_cod_partida] = $_cod_partida;
			$_CUENTA_MONTO[$_CodCuenta] += $_Monto;
			$_PARTIDA_MONTO[$_cod_partida] += $_Monto;
		}
	}
	if ($MontoImpuesto > 0) {
		$_cod_partida = $_PARAMETRO["IVADEFAULT"];
		$_CodCuenta = $_PARAMETRO["IVACTADEF"];
		$_CUENTA[$_CodCuenta] = $_CodCuenta;
		$_PARTIDA[$_cod_partida] = $_cod_partida;
		$_CUENTA_MONTO[$_CodCuenta] += $MontoImpuesto;
		$_PARTIDA_MONTO[$_cod_partida] += $MontoImpuesto;
	}
	//	imprimo cuentas
	foreach ($_CUENTA as $CodCuenta) {
		$Descripcion = getValorCampo("ac_mastplancuenta", "CodCuenta", "Descripcion", $CodCuenta);
		?>
        <tr class="trListaBody">
			<td align="center">
				<?=$CodCuenta?>
            </td>
			<td>
				<?=$Descripcion?>
            </td>
			<td align="right">
				<?=number_format($_CUENTA_MONTO[$CodCuenta], 2, ',', '.')?>
            </td>
		</tr>
        <?
	}
	echo "|";
	if ($FlagPresupuesto == "S" || $FlagCompromiso == "S") {
		//	imprimo partidas
		foreach ($_PARTIDA as $cod_partida) {
			$Descripcion = getValorCampo("pv_partida", "cod_partida", "denominacion", $cod_partida);
			list($MontoAjustado, $MontoCompromiso, $MontoPendiente) = disponibilidadPartida($Anio, $CodOrganismo, $cod_partida);
			$MontoDisponible = $MontoAjustado - $MontoCompromiso;
			if ($Estado == "PR" && $NroOrden != "") $MontoPendiente -= $_PARTIDA_MONTO[$cod_partida];
			//	valido
			if ($MontoDisponible < $_PARTIDA_MONTO[$cod_partida] && $FlagCompromiso == "S") $style = "style='font-weight:bold; background-color:#F8637D;'";
			elseif($MontoDisponible < ($_PARTIDA_MONTO[$cod_partida] + $MontoPendiente) && $FlagCompromiso == "S") $style = "style='font-weight:bold; background-color:#FFC;'";
			else $style = "style='font-weight:bold; background-color:#D0FDD2;'";
			?>
			<tr class="trListaBody" <?=$style?>>
				<td align="center">
					<input type="hidden" name="cod_partida" value="<?=$cod_partida?>" />
					<input type="hidden" name="Monto" value="<?=$_PARTIDA_MONTO[$cod_partida]?>" />
					<input type="hidden" name="MontoDisponible" value="<?=$MontoDisponible?>" />
					<input type="hidden" name="MontoPendiente" value="<?=$MontoPendiente?>" />
					<?=$cod_partida?>
				</td>
				<td>
					<?=$Descripcion?>
				</td>
				<td align="right">
					<?=number_format($_PARTIDA_MONTO[$cod_partida], 2, ',', '.')?>
				</td>
			</tr>
			<?
		}
	}
}
//	--------------------------
?>