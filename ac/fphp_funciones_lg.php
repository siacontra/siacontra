<?php
include ("fphp_lg.php");
//	--------------------------
if ($accion == "setDirigidoA") {
	connect();
	$sql = "SELECT ReqAlmacenCompra, TipoRequerimiento FROM lg_clasificacion WHERE Clasificacion = '".$clasificacion."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) { $field = mysql_fetch_array($query); echo "|".$field['ReqAlmacenCompra']."|".$field['TipoRequerimiento']; }
	else echo $sql;
}

//	--------------------------
elseif ($accion == "mostrarRequerimientoDetalles") {
	connect();
	//	CONSULTO LA TABLA
	$sql = "SELECT
				lrd.*,
				o.Organismo,
				lr.Clasificacion,
				i.CodInterno,
				iai.StockActual,
				(lrd.CantidadPedida - lrd.CantidadRecibida) AS CantidadPendiente,
				(SELECT SUM(CantidadPedida - CantidadRecibida) 
				 FROM lg_ordencompradetalle 
				 WHERE CodItem = lrd.CodItem AND Estado = 'PE'
				 GROUP BY CodItem) AS EnTransito
			FROM
				lg_requerimientosdet lrd
				INNER JOIN mastorganismos o ON (lrd.CodOrganismo = o.CodOrganismo)
				INNER JOIN lg_requerimientos lr ON (lrd.CodRequerimiento = lr.CodRequerimiento)
				INNER JOIN lg_itemmast i ON (lrd.CodItem = i.CodItem)
				LEFT JOIN lg_itemalmaceninv iai ON (lr.CodAlmacen = iai.CodAlmacen AND lrd.CodItem = iai.CodItem)
			WHERE
				lrd.CodRequerimiento = '".$codrequerimiento."' AND
				lrd.CodOrganismo = '".$codorganismo."' AND
				lrd.Estado = 'PE' AND
				lrd.FlagCompraAlmacen <> 'C'
			ORDER BY CodRequerimiento, CodItem, CommoditySub";
	$query_det = mysql_query($sql) or die ($sql.mysql_error());
	$rows_det = mysql_num_rows($query_det);
	//	MUESTRO LA TABLA
	while ($field_det = mysql_fetch_array($query_det)) {
		$status = printValores("ESTADO-REQUERIMIENTO-DET", $field_det['Estado']);
		$cantidad_pendiente = $field_det['CantidadPedida'] - $field_det['CantidadRecibida'];
		if ($field_det['FlagCompraAlmacen'] <> "A") $flag = "<img src='../imagenes/flag.png' />"; else $flag = "";
		?>
		<tr class="trListaBody" onclick="mClkMulti(this);" id="row_<?=$field_det['Secuencia']?>">
			<td align="center">
                <input type="checkbox" name="detalle" id="<?=$field_det['Secuencia']?>" value="<?=$field_det['Secuencia']?>" style="display:none;" />
                <?=$field_det['CodItem']?>
			</td>
			<td align="center"><?=$field_det['CodInterno']?></td>
			<td><?=htmlentities($field_det['Descripcion'])?></td>
			<td align="center"><?=$field_det['CodUnidad']?></td>
			<td align="right"><?=number_format($field_det['CantidadPedida'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field_det['CantidadPendiente'], 2, ',', '.')?></td>
			<td align="right">
            	<input type="hidden" name="stock" id="stock_<?=$field_det['Secuencia']?>" value="<?=$field_det['StockActual']?>" />
				<?=number_format($field_det['StockActual'], 2, ',', '.')?>
			</td>
			<td align="center"><?=$field_det['CodCentroCosto']?></td>
			<td align="right"><?=number_format($field_det['EnTransito'], 2, ',', '.')?></td>
			<td align="center"><?=$flag?></td>
		</tr>
		<?
	}
}

//	--------------------------
elseif ($accion == "insertarProveedorCotizacion") {
	connect();
	
	$proveedor = split(";", $proveedores);
	foreach ($proveedor as $registro) {
		if ($codproveedor == $registro) die("¡No se puede insertar dos veces el mismo proveedor en una misma invitación!");
	}
	
	//	si nosencontraron errores inserta en la tabla los datos del proveedor
	echo "||";
	?>
    <td align="center"><?=$nroproveedores?><input type="checkbox" name="chkproveedores" id="chk_<?=$codproveedor?>" title="<?=$codproveedor?>" style="display:none;" /></td>
    <td><?=$nomproveedor?><input type="hidden" name="txtnombre" value="<?=$nomproveedor?>" /></td>
    <td align="center">
        <select name="sltformapago" style="width:99%;">
            <?=loadSelect("mastformapago", "CodFormaPago", "Descripcion", $codformapago, 0)?>
        </select>
    </td>
	<?
}
	
//	--------------------------
elseif ($accion == "guardarInvitacionProveedores") {
	connect();
	$numero = getCodigo("lg_cotizacion", "Numero", 10); $numero = (int) $numero;
	
	$invitacion = split(";", $proveedores);
	foreach ($invitacion as $proveedor) {
		list($codproveedor, $nomproveedor, $codformapago)=SPLIT( '[|]', $proveedor);
		
		$nroinvitaciones = count($invitacion);
		
		$cotizacion_numero = getCodigo("lg_cotizacion", "CotizacionNumero", 8);
		
		$lineas = split(";", $detalles);
		foreach ($lineas as $detalle) {
			list($codorganismo, $codrequerimiento, $secuencia)=SPLIT( '[|]', $detalle);
			
			//	consulto para verificar que el requerimiento ya no se le haya ingresado este proveedor
			$sql = "SELECT
						*
					FROM
						lg_cotizacion
					WHERE
						CodOrganismo = '".$codorganismo."' AND
						CodRequerimiento = '".$codrequerimiento."' AND
						Secuencia = '".$secuencia."' AND
						CodProveedor = '".$codproveedor."'";
			$query_valida = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_valida) != 0) {
				$sql = "DELETE FROM lg_cotizacion WHERE Numero = '".$numero."'";
				die("¡No se puede insertar dos veces el mismo proveedor para un mismo item!");
			}
			
			//	Obtengo la cantidad pedida para insertar en la invitacion
			$sql = "SELECT
						CantidadPedida,
						FlagExonerado
					FROM
						lg_requerimientosdet
					WHERE
						CodOrganismo = '".$codorganismo."' AND
						CodRequerimiento = '".$codrequerimiento."' AND
						Secuencia = '".$secuencia."'";
			$query_cantidad = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_cantidad) != 0) $field_cantidad = mysql_fetch_array($query_cantidad);
			
			//	inserto en lg_cotizacion
			$sql = "INSERT INTO lg_cotizacion (CodOrganismo,
											   CodRequerimiento,
											   Secuencia,
											   CotizacionNumero,
											   Numero,
											   FechaInvitacion,
											   FechaDocumento,
											   CodProveedor,
											   NomProveedor,
											   CodFormaPago,
											   FechaLimite,
											   FechaEntrega,
											   Condiciones,
											   Observaciones,
											   Cantidad,
											   Estado,
											   NroCotizacionProv,
											   FlagAsignado,
											   FlagExonerado,
											   UltimoUsuario,
											   UltimaFecha)
									VALUES ('".$codorganismo."',
											'".$codrequerimiento."',
											'".$secuencia."',
											'".$cotizacion_numero."',
											'".$numero."',
											'".date("Y-m-d")."',
											'".date("Y-m-d")."',
											'".$codproveedor."',
											'".utf8_decode($nomproveedor)."',
											'".$codformapago."',
											'".formatFechaDMA($flimite)."',
											'".formatFechaDMA($flimite)."',
											'".utf8_decode($condiciones)."',
											'".utf8_decode($observaciones)."',
											'".$field_cantidad['CantidadPedida']."',
											'A',
											'".$cotizacion_numero."',
											'N',
											'".$field_cantidad['FlagExonerado']."',
											'".$_SESSION['USUARIO_ACTUAL']."',
											'".date("Y-m-d H:i:s")."')";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			
			//	actualizo numero de invitaciones en el requerimiento detalle
			$sql = "UPDATE lg_requerimientosdet 
					SET CotizacionRegistros = (CotizacionRegistros + 1)
					WHERE 
						CodOrganismo = '".$codorganismo."' AND 
						CodRequerimiento = '".$codrequerimiento."' AND 
						Secuencia = '".$secuencia."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	echo "|$numero";
}
	
//	--------------------------
elseif ($accion == "eliminarInvitacionProveedores") {
	connect();
	$sql = "SELECT FlagAsignado FROM lg_cotizacion WHERE CotizacionNumero = '".$cotizacion_numero."' AND FlagAsignado = 'S'";
	$query_flag = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query_flag) != 0) die("¡No se pueden eliminar invitaciones que tienen cotizaciones asignadas!");
	else {
		$sql = "SELECT 
					CodOrganismo,
					CodRequerimiento
				FROM 
					lg_cotizacion
				WHERE
					CotizacionNumero = '".$cotizacion_numero."'
				GROUP BY CotizacionNumero";
		$query_cotizacion = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_cotizacion) != 0) $field_cotizacion = mysql_fetch_array($query_cotizacion);
		
		$sql = "UPDATE lg_requerimientosdet 
				SET CotizacionRegistros = (CotizacionRegistros - 1)
				WHERE 
					CodOrganismo = '".$field_cotizacion['CodOrganismo']."' AND 
					CodRequerimiento = '".$codrequerimiento."'";
					
		$sql = "DELETE FROM lg_cotizacion WHERE CotizacionNumero = '".$cotizacion_numero."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
	}
}

//	--------------------------
elseif ($accion == "verInvitacionesRequerimiento") {
	connect();
	?>
    <div style="width:800px" class="divFormCaption">Requerimientos a Invitar</div>
	<table align="center"><tr><td align="center"><div style="overflow:scroll; width:800px; height:150px;">
	<table width="100%" class="tblLista">
        <tr class="trListaHead">
            <th scope="col" width="50">#</th>
            <th scope="col">Raz&oacute;n Social</th>
            <th scope="col" width="75">Cantidad</th>
            <th scope="col" width="75">Nro. Invitaci&oacute;n</th>
            <th scope="col" width="200">Condiciones de la Invitaci&oacute;n</th>
            <th scope="col" width="100">Fecha Invitaci&oacute;n</th>
        </tr>
        <?
		list($codorganismo, $codrequerimiento, $secuencia)=SPLIT( '[|]', $datos);
		
		if ($formulario == "frmstock") $campo = "CodItem"; else $campo = "CommoditySub";
		
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
					c.CodOrganismo = '".$codorganismo."' AND
					c.CodRequerimiento = '".$codrequerimiento."' AND
					c.Secuencia = '".$secuencia."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		while ($field = mysql_fetch_array($query)) {
			$i++;
			?>
            <tr class="trListaBody">
                <td align="center"><?=$i?></td>
                <td><?=htmlentities($field['NomProveedor'])?></td>
                <td align="center"><?=$field['CantidadPedida']?></td>
                <td align="center"><?=$field['CotizacionNumero']?></td>
                <td><?=$field['Condiciones']?></td>
                <td align="center"><?=formatFechaDMA($field['FechaInvitacion'])?></td>
            </tr>
            <?
		}
		?>
        
    </table>
    </div></td></tr></table>
	<?
}

//	--------------------------
elseif ($accion == "insertarProveedorCotizacionProceso") {
	connect();
	
	//	-----------------------------------------------------------------------
	$_DIAS_LIMITE_COTIZACION = getParametro("DIASCOTIZAR");
	$_FECHA_LIMITE = getFechaFinContinuo(date("d-m-Y"), $_DIAS_LIMITE_COTIZACION);
	//	-----------------------------------------------------------------------
	$sql = "SELECT
				i.FactorPorcentaje
			FROM
				mastproveedores p
				INNER JOIN masttiposervicioimpuesto tsi ON (p.CodTipoServicio = tsi.CodTipoServicio)
				INNER JOIN mastimpuestos i ON (tsi.CodImpuesto = i.CodImpuesto)";
	$query_proveedores = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query_proveedores) != 0) $field_proveedores = mysql_fetch_array($query_proveedores);
	//	-----------------------------------------------------------------------
	
	$proveedor = split(";", $proveedores);
	foreach ($proveedor as $registro) {
		if ($codproveedor == $registro) die("¡No se puede insertar dos veces el mismo proveedor en una misma cotización!");
	}
	
	echo "||";	
	?>
        <td align="center"><?=$nroproveedores?><input type="checkbox" name="chkproveedores" title="<?=$codproveedor?>" style="display:none;" /></td>
        <td align="center"><?=$codproveedor?></td>
        <td>
        	<?=$nomproveedor?>
        	<input type="hidden" name="txtnombre" value="<?=$nomproveedor?>" />
		</td>
        <td align="center"><input type="radio" name="chkasig" id="chkasig_<?=$codproveedor?>" /></td>
        <td align="center"><input type="text" name="cantidad" id="cantidad_<?=$codproveedor?>" value="<?=number_format($cantidad, 2, ',', '.')?>" style="width:97%;" dir="rtl" onchange="setTotalesCotizacion('<?=$codproveedor?>');" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
        <td align="center"><input type="text" name="pu" id="pu_<?=$codproveedor?>" value="0,00" style="width:98%;" dir="rtl" onchange="setTotalesCotizacion('<?=$codproveedor?>');" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
        <td align="center"><input type="text" name="pui" id="pui_<?=$codproveedor?>" value="0,00" style="width:98%;" dir="rtl" readonly="readonly" /></td>
        <td align="center"><input type="checkbox" name="chkexon" id="chkexon_<?=$codproveedor?>" value="<?=number_format($field_proveedores['FactorPorcentaje'], 2, ',', '.')?>" onclick="setTotalesCotizacion('<?=$codproveedor?>');" /></td>
        <td align="center"><input type="text" name="desc" id="desc_<?=$codproveedor?>" value="0,00" style="width:98%;" dir="rtl" onchange="setTotalesCotizacion('<?=$codproveedor?>');" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
        <td align="center"><input type="text" name="descf" id="descf_<?=$codproveedor?>" value="0,00" style="width:98%;" dir="rtl" onchange="setTotalesCotizacion('<?=$codproveedor?>');" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
        <td align="right"><span id="spufinal_<?=$codproveedor?>">0,00</span></td>
        <td align="right"><span id="smontofinal_<?=$codproveedor?>">0,00</span></td>
        <td align="right"><span id="monto_comparar_<?=$codproveedor?>">0,00</span></td>
        <td align="center"><input type="checkbox" name="chkmejor" id="chkmejor_<?=$codproveedor?>" onclick="forzarUnCheck(this.id);" /></td>
        <td align="center">
            <select name="sltformapago" style="width:99%;">
                <?=loadSelect("mastformapago", "CodFormaPago", "Descripcion", $codformapago, 0)?>
            </select>
        </td>
        <td align="center"><input type="text" name="finvitacion" value="<?=date("d-m-Y")?>" style="width:99%;" /></td>
        <td align="center"><input type="text" name="flimite" value="<?=$_FECHA_LIMITE?>" style="width:99%;" /></td>
        <td align="center"><input type="text" name="condiciones" style="width:99%;" /></td>
        <td align="center"><input type="text" name="observaciones" id="obs_<?=$codproveedor?>" style="width:99%;" /></td>
        <td align="center"><input type="text" name="dias" value="0" style="width:99%;" /></td>
        <td align="center"><input type="text" name="validez" value="0" style="width:99%;" /></td>
        <td align="center"><input type="text" name="nro_cotizacion" style="width:99%;" /></td>
        <td></td>
        <td></td>
    <?
}
	
//	--------------------------
elseif ($accion == "guardarCotizacionProveedoresPorItem") {
	connect();
	$numero = getCodigo("lg_cotizacion", "Numero", 10); $numero = (int) $numero;
	
	$invitacion = split(";", $proveedores);	$contador_proveedor=0;
	foreach ($invitacion as $proveedor) {	$contador_proveedor++;
		list($codproveedor, $nomproveedor, $asig, $cant, $pu, $pui, $exon, $impuesto, $desc, $descf, $mejor, $formapago, $finvitacion, $flimite, $cond, $obs_cot, $dias, $validez, $cotizacion) = SPLIT( '[|]', $proveedor);
		
		$cotizacion_numero = getCodigo("lg_cotizacion", "CotizacionNumero", 8);
		
		if ($asig == "true") $flagasig = "S"; else $flagasig = "N";
		if ($exon == "true") $flagexon = "S"; else $flagexon = "N";
		
		if ($asig == "true" && $mejor == "false") $observaciones = $obs; else $observaciones = $obs_cot;
				
		if ($desc != 0) $precio_unitario = $pu - ($pu * $desc / 100);
		else $precio_unitario = $pu - $descf;
		
		if ($flagexon == "S")
			$precio_unitario_iva = $precio_unitario;
		else
			$precio_unitario_iva = $precio_unitario + ($precio_unitario * $impuesto / 100);
			
		$precio_cantidad = $precio_unitario * $cant;
		$total = $precio_unitario_iva * $cant;
		
		$lineas = split(";", $detalles);
		foreach ($lineas as $detalle) {
			list($codorganismo, $codrequerimiento, $secuencia)=SPLIT( '[|]', $detalle);
			
			//	consulto para saber si actualizo o inserto
			$sql = "SELECT CotizacionSecuencia 
					FROM lg_cotizacion 
					WHERE 
						CodOrganismo = '".$codorganismo."' AND 
						CodRequerimiento = '".$codrequerimiento."' AND 
						Secuencia = '".$secuencia."' AND
						CodProveedor = '".$codproveedor."'";
			$query_consulto = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_consulto) != 0) {
				//	actualizo la cotizacion
				$sql = "UPDATE lg_cotizacion SET FechaInvitacion = '".formatFechaAMD($finvitacion)."',
												 FechaDocumento = '".formatFechaAMD($finvitacion)."',
												 CodFormaPago = '".$formapago."',
												 PrecioUnit = '".$precio_unitario."',
												 PrecioUnitInicio = '".$pu."',
												 PrecioUnitIva = '".$precio_unitario_iva."',
												 PrecioUnitInicioIva = '".$pui."',
												 PrecioCantidad = '".$precio_cantidad."',
												 Total = '".$total."',
												 ValidezOferta = '".$validez."',
												 DiasEntrega = '".$dias."',
												 NroCotizacionProv = '".$cotizacion."',
												 FlagAsignado = '".$flagasig."',
												 FlagExonerado = '".$flagexon."',
												 Cantidad = '".$cant."',
												 DescuentoFijo = '".$descf."',
												 DescuentoPorcentaje = '".$desc."',
												 FechaLimite = '".formatFechaAMD($flimite)."',
												 Condiciones = '".utf8_decode($cond)."',
												 Observaciones = '".utf8_decode($observaciones)."',
												 UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
												 UltimaFecha = '".date("Y-m-d H:i:s")."'
											WHERE
												 CodOrganismo = '".$codorganismo."' AND
												 CodRequerimiento = '".$codrequerimiento."' AND
												 Secuencia = '".$secuencia."' AND
												 CodProveedor = '".$codproveedor."'";
				$query_update = mysql_query($sql) or die ($sql.mysql_error());
			} else {
				//	inserto la cotizacion
				$sql = "INSERT INTO lg_cotizacion (CodOrganismo,
												   CodRequerimiento,
												   Secuencia,
												   CotizacionNumero,
												   Numero,
												   FechaInvitacion,
												   FechaDocumento,
												   CodProveedor,
												   NomProveedor,
												   CodFormaPago,
												   PrecioUnit,
												   PrecioUnitIva,
												   PrecioCantidad,
												   Total,
												   PrecioUnitInicio,
												   PrecioUnitInicioIva,
												   ValidezOferta,
												   DiasEntrega,
												   NroCotizacionProv,
												   FlagAsignado,
												   FlagExonerado,
												   Cantidad,
												   DescuentoFijo,
												   DescuentoPorcentaje,
												   FechaLimite,
												   Condiciones,
												   Observaciones,
												   Estado,
												   UltimoUsuario,
												   UltimaFecha)
											VALUES ('".$codorganismo."',
													'".$codrequerimiento."',
													'".$secuencia."',
													'".$cotizacion_numero."',
													'".$numero."',
													'".formatFechaAMD($finvitacion)."',
													'".formatFechaAMD($finvitacion)."',
													'".$codproveedor."',
													'".utf8_decode($nomproveedor)."',
													'".$formapago."',
													'".$precio_unitario."',
													'".$precio_unitario_iva."',
													'".$precio_cantidad."',
													'".$total."',
													'".$pu."',
													'".$pui."',
													'".$validez."',
													'".$dias."',
													'".$cotizacion."',
													'".$flagasig."',
													'".$flagexon."',
													'".$cant."',
													'".$descf."',
													'".$desc."',
													'".formatFechaAMD($flimite)."',
													'".utf8_decode($cond)."',
													'".utf8_decode($observaciones)."',
													'A',
													'".$_SESSION['USUARIO_ACTUAL']."',
													'".date("Y-m-d H:i:s")."')";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			}
						
			//	si ha sido asignada la cotizacion entoces actulizo el detalle del requerimiento
			if ($asig == "true") {
				//	obtengo la cotizacionsecuencia
				$sql = "SELECT CotizacionSecuencia 
					FROM lg_cotizacion 
					WHERE 
						CodOrganismo = '".$codorganismo."' AND 
						CodRequerimiento = '".$codrequerimiento."' AND 
						Secuencia = '".$secuencia."' AND
						CodProveedor = '".$codproveedor."'";
				$query_secuencia = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_secuencia) != 0) $field_secuencia = mysql_fetch_array($query_secuencia);
				
				//	actualizo el detalle del requerimiento
				$sql = "UPDATE lg_requerimientosdet
						SET
							CotizacionSecuencia = '".$field_secuencia['CotizacionSecuencia']."',
							CotizacionCantidad = '".$cant."',
							CotizacionPrecioUnit = '".$pu."',
							CotizacionPrecioUnitIva = '".$pui."',
							CotizacionPrecioUnit = '".$pu."',
							CotizacionProveedor = '".$codproveedor."',
							CotizacionFormaPago = '".$formapago."',
							CotizacionFechaAsignacion = '".date("Y-m-d")."',
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = '".date("Y-m-d H:i:s")."'
						WHERE
							CodOrganismo = '".$codorganismo."' AND
							CodRequerimiento = '".$codrequerimiento."' AND
							Secuencia = '".$secuencia."'";
				$query_update = mysql_query($sql) or die ($sql.mysql_error());
			}
			
			
			$quedaron .= " AND (CodOrganismo = '".$codorganismo."'
						   AND CodRequerimiento = '".$codrequerimiento."'
						   AND Secuencia = '".$secuencia."'
						   AND CodProveedor <> '".$codproveedor."') ";
						   
			//	actualizo numero de invitaciones en el requerimiento detalle
			$sql = "UPDATE lg_requerimientosdet 
					SET CotizacionRegistros = '$contador_proveedor'
					WHERE 
						CodOrganismo = '".$codorganismo."' AND 
						CodRequerimiento = '".$codrequerimiento."' AND 
						Secuencia = '".$secuencia."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	//	elimino los que quitaron de la lista
	$sql = "DELETE FROM lg_cotizacion
			WHERE 1 $quedaron";
	$query_delete = mysql_query($sql) or die ($sql.mysql_error());
}

//	--------------------------
elseif ($accion == "verProveedorDetallesRequerimiento") {
	connect();
	
	if ($formulario == "frmstock") $campo = "CodItem"; else $campo = "CommoditySub";
	if ($flagasignado != "") $filtro_flag = " AND (c.FlagAsignado = '".$flagasignado."')";
	$sql = "SELECT
				lrd.CodOrganismo,
				lrd.CodRequerimiento,
				lrd.Secuencia,
				lrd.CodItem,
				lrd.CommoditySub,
				lrd.Descripcion,
				lrd.CodUnidad,
				lrd.CodCentroCosto,
				lr.FechaRequerida,
				c.CotizacionSecuencia,
				o.Organismo,
				d.Dependencia,
				lr.Clasificacion,
				lr.FechaRequerida,
				i.CodLinea,
				i.CodFamilia,
				lr.Prioridad,
				c.Cantidad,
				c.PrecioUnit,
				c.PrecioUnitIva,
				c.PrecioCantidad,
				c.Total,
				c.FlagExonerado,
				c.Observaciones
			FROM
				lg_requerimientosdet lrd
				LEFT JOIN lg_itemmast i ON (lrd.CodItem = i.CodItem)
				LEFT JOIN lg_commoditysub cs ON (lrd.CommoditySub = cs.Codigo)
				INNER JOIN mastorganismos o ON (lrd.CodOrganismo = o.CodOrganismo)
				INNER JOIN lg_requerimientos lr ON (lrd.CodRequerimiento = lr.CodRequerimiento)
				INNER JOIN mastdependencias d ON (lr.CodDependencia = d.CodDependencia)
				INNER JOIN lg_cotizacion c ON (lrd.CodOrganismo = c.CodOrganismo AND lrd.CodRequerimiento = c.CodRequerimiento AND lrd.Secuencia = c.Secuencia)
			WHERE
				c.CotizacionNumero = '".$selinvitacion."' $filtro_flag
			ORDER BY CodRequerimiento, CodItem, CommoditySub";
	$query_req = mysql_query($sql) or die ($sql.mysql_error());
	$rows_req = mysql_num_rows($query_req);
	//	MUESTRO LA TABLA
	while ($field_req = mysql_fetch_array($query_req)) {
		$status = printValores("ESTADO-REQUERIMIENTO-DET", $field_req['Estado']);
		$flagexon = printFlag($field_req['FlagExonerado']);
		if ($field_req['CodItem'] != "") $codigo = $field_req['CodItem']; else $codigo = $field_req['CommoditySub'];
		$id = $field_req['CodOrganismo']."|".$field_req['CodRequerimiento']."|".$field_req['Secuencia'];
		?>
		<tr class="trListaBody" onclick="mClk(this, 'codlinea');" id="row_<?=$id?>">
			<td align="center"><?=$codigo?></td>
			<td><?=htmlentities($field_req['Descripcion'])?></td>
			<td align="center"><?=$field_req['CodUnidad']?></td>
			<td align="right"><?=number_format($field_req['Cantidad'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field_req['PrecioUnit'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field_req['PrecioUnitIva'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field_req['PrecioCantidad'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field_req['Total'], 2, ',', '.')?></td>
			<td align="center"><?=$flagexon?></td>
			<td align="center"><input type="checkbox" name="detalle" id="<?=$id?>" value="<?=$id?>" style="display:none" checked="checked" /><?=$field_req['CodRequerimiento']?></td>
			<td align="center"><?=$field_req['Secuencia']?></td>
			<td align="center"><?=formatFechaDMA($field_req['FechaRequerida'])?></td>
			<td align="center"><?=$field_req['CodCentroCosto']?></td>
			<td><?=htmlentities($field_req['Observaciones'])?></td>
		</tr>
		<?
	}
}
	
//	--------------------------
elseif ($accion == "guardarCotizacionProveedoresPorInvitacion") {
	connect();
	
	$linea = split(";", $detalles);
	foreach ($linea as $item) {
		list($cotizacion_secuencia, $cantidad, $pusi, $chkasig, $chkasiginicial, $chkexon, $puci, $desc, $descf) = SPLIT( '[|]', $item);
		
		$sql = "SELECT CodProveedor FROM lg_cotizacion WHERE CotizacionSecuencia = '".$cotizacion_secuencia."'";
		$query_proveedor = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_proveedor) != 0) $field_proveedor = mysql_fetch_array($query_proveedor);
		$impuesto = getIGVCODIGO($field_proveedor['CodProveedor']);
				
		if ($chkasig == "true") $flagasig = "S"; else $flagasig = "N";
		if ($chkexon == "true") $flagexon = "S"; else $flagexon = "N";
			
		if ($desc != 0) $precio_unitario = $pusi - ($pusi * $desc / 100);
		else $precio_unitario = $pusi - $descf;		
		
		if ($flagexon == "S") {
			$precio_unitario_iva = $precio_unitario;
			$puci = $precio_unitario;
		} else {
			$precio_unitario_iva = $precio_unitario + ($precio_unitario * $impuesto / 100);
			$puci = $pusi + ($pusi * $impuesto / 100);
		}
			
		$precio_cantidad = $precio_unitario * $cantidad;
		$total = $precio_unitario_iva * $cantidad;
		
		//	consulto los datos del detalle
		$sql = "SELECT
					lrd.CodOrganismo,
					lrd.CodRequerimiento,
					lrd.Secuencia
				FROM
					lg_requerimientosdet lrd
					INNER JOIN lg_cotizacion c ON (lrd.CodOrganismo = c.CodOrganismo AND lrd.CodRequerimiento = c.CodRequerimiento AND lrd.Secuencia = c.Secuencia)
				WHERE
					c.CotizacionSecuencia = '".$cotizacion_secuencia."'";
		$query_detalle = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_detalle) != 0) $field_detalle = mysql_fetch_array($query_detalle); 
		else die ("¡Error al consultar el detalle del requerimiento!");
		
		//	actualizo la cotizacion
		$sql = "UPDATE lg_cotizacion
				SET 
					FechaRecepcion = '".formatFechaAMD($frecepcion)."',
					FechaApertura = '".formatFechaAMD($fapertura)."',
					NroCotizacionProv = '".$cotizacion."',
					FechaDocumento = '".formatFechaAMD($fcotizacion)."',
					CodFormaPago = '".$forma_pago."',
					Cantidad = '".$cantidad."',
					DescuentoPorcentaje = '".$desc."',
					DescuentoFijo = '".$descf."',
					ValidezOferta = '".$validez."',
					DiasEntrega = '".$plazo."',
					PrecioUnit = '".$precio_unitario."',
					PrecioUnitInicio = '".$pusi."',
					PrecioUnitIva = '".$precio_unitario_iva."',
					PrecioUnitInicioIva = '".$puci."',
					PrecioCantidad = '".$precio_cantidad."',
					Total = '".$total."',
					FlagAsignado = '".$flagasig."',
					FlagExonerado = '".$flagexon."',
					UltimaFecha = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE
					CotizacionSecuencia = '".$cotizacion_secuencia."'";
		$query_cotizacion = mysql_query($sql) or die ($sql.mysql_error());
		
		if ($chkasig == "true") {
			$sql = "UPDATE lg_requerimientosdet
					SET
						CotizacionSecuencia = '".$cotizacion_secuencia."',
						CotizacionCantidad = '".$cantidad."',
						CotizacionPrecioUnit = '".$pusi."',
						CotizacionPrecioUnitIva = '".$puci."',
						CotizacionProveedor = '".$codproveedor."',
						CotizacionFechaAsignacion = '".formatFechaAMD($fcotizacion)."',
						CotizacionFormaPago = '".$forma_pago."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = '".date("Y-m-d H:i:s")."'
					WHERE
						CodOrganismo = '".$field_detalle['CodOrganismo']."' AND
						CodRequerimiento = '".$field_detalle['CodRequerimiento']."' AND
						Secuencia = '".$field_detalle['Secuencia']."'";
			$query_requerimiento = mysql_query($sql) or die ($sql.mysql_error());
		}		
		elseif ($chkasig == "false" && $chkasiginicial == "true") {
			$sql = "UPDATE lg_requerimientosdet
					SET
						CotizacionSecuencia = '',
						CotizacionCantidad = '',
						CotizacionPrecioUnit = '',
						CotizacionPrecioUnitIva = '',
						CotizacionProveedor = '',
						CotizacionFechaAsignacion = '',
						CotizacionFormaPago = '',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = '".date("Y-m-d H:i:s")."'
					WHERE
						CodOrganismo = '".$field_detalle['CodOrganismo']."' AND
						CodRequerimiento = '".$field_detalle['CodRequerimiento']."' AND
						Secuencia = '".$field_detalle['Secuencia']."'";
			$query_requerimiento = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
}
	
//	--------------------------
elseif ($accion == "generarOrdenPendiente") {
	connect();
	
	$sql = "SELECT 
				p.CodTipoPago,
				fp.DiasVence
			FROM 
				mastproveedores p
				LEFT JOIN mastformapago fp ON (p.CodFormaPago = fp.CodFormaPago)
			WHERE 
				p.CodProveedor = '".$codproveedor."'";
	$query_proveedor = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query_proveedor) != 0) $field_proveedor = mysql_fetch_array($query_proveedor);
	
	$monto_bruto = $monto_total - $monto_impuestos;
	
	if ($monto_impuestos != 0) {
		$IVADEFAULT = PARAMETROS('IVADEFAULT');
		$IVACTADEF = PARAMETROS('IVACTADEF');
	} else {
		$IVADEFAULT['IVADEFAULT'] = "";
		$IVACTADEF['IVACTADEF'] = "";
	}
	
	if ($tipo_orden == "SER") {	
		//	inserto en orden de servicio
		$nroorden = getCodigo_2("lg_ordenservicio", "NroOrden", "CodOrganismo", $organismo, 10);
		$sql = "INSERT INTO lg_ordenservicio (CodOrganismo,
												NroOrden,
												CodDependencia,
												Estado,
												CodProveedor,
												NomProveedor,
												PlazoEntrega,
												FechaEntrega,
												CodTipoPago,
												DiasPago,
												CodFormaPago,
												CodTipoServicio,
												Descripcion,
												Observaciones,
												MontoOriginal,
												MontoIva,
												TotalMontoIva,
												CodCentroCosto,
												PreparadaPor,
												FechaPreparacion,
												FechaValidoDesde,
												FechaValidoHasta,
												FechaDocumento,
												cod_partida,
												CodCuenta,
												UltimoUsuario,
												UltimaFecha)
										VALUES ('".$organismo."',
												'".$nroorden."',
												'".$dependencia."',
												'PR',
												'".$codproveedor."',
												'".utf8_decode($nomproveedor)."',
												'".$dias_entrega."',
												'".formatFechaAMD($fentrega)."',
												'".$field_proveedor['CodTipoPago']."',
												'".$field_proveedor['DiasVence']."',
												'".$formapago."',
												'".$codservicio."',
												'".utf8_decode($descripcion)."',
												'".utf8_decode($observaciones)."',
												'".$monto_bruto."',
												'".$monto_impuestos."',
												'".$monto_total."',
												'".$ccosto."',
												'".$_SESSION['CODPERSONA_ACTUAL']."',
												'".date("Y-m-d H:i:s")."',
												'".date("Y-m-d")."',
												'".formatFechaAMD($fentrega)."',
												'".date("Y-m-d")."',
												'".$IVADEFAULT['IVADEFAULT']."',
												'".$IVACTADEF['IVACTADEF']."',
												'".$_SESSION['USUARIO_ACTUAL']."',
												'".date("Y-m-d H:i:s")."')";
		$query_mast = mysql_query($sql) or die ($sql.mysql_error());
	} else {
		//	inserto en orden de compra
		$nroorden = getCodigo_2("lg_ordencompra", "NroOrden", "CodOrganismo", $organismo, 10);
		$sql = "INSERT INTO lg_ordencompra (CodOrganismo,
											NroOrden,
											CodDependencia,
											Estado,
											CodProveedor,
											NomProveedor,
											FechaPrometida,
											Clasificacion,
											CodFormaPago,
											CodTipoServicio,
											CodAlmacen,
											CodAlmacenIngreso,
											NomContacto,
											FaxContacto,
											Entregaren,
											DirEntrega,
											InsEntrega,
											Observaciones,
											ObsDetallada,
											MontoAfecto,
											MontoNoAfecto,
											MontoBruto,
											MontoIGV,
											MontoTotal,
											MontoPendiente,
											MontoOtros,
											PreparadaPor,
											FechaPreparacion,
											PlazoEntrega,
											cod_partida,
											CodCuenta,
											UltimoUsuario,
											UltimaFecha)
									VALUES ('".$organismo."',
											'".$nroorden."',
											'".$dependencia."',
											'PR',
											'".$codproveedor."',
											'".utf8_decode($nomproveedor)."',
											'".formatFechaAMD($fentrega)."',
											'".$clasificacion."',
											'".$formapago."',
											'".$codservicio."',
											'".$almacen_entrega."',
											'".$almacen_ingreso."',
											'".utf8_decode($nomcontacto)."',
											'".$faxcontacto."',
											'".utf8_decode($entregaren)."',
											'".utf8_decode($direccion)."',
											'".utf8_decode($instruccion)."',
											'".utf8_decode($observaciones)."',
											'".utf8_decode($detallada)."',
											'".$monto_afecto."',
											'".$monto_noafecto."',
											'".$monto_bruto."',
											'".$monto_impuestos."',
											'".$monto_total."',
											'".$monto_pendiente."',
											'".$monto_otros."',
											'".$_SESSION['CODPERSONA_ACTUAL']."',
											'".date("Y-m-d H:i:s")."',
											'".$dias_entrega."',
											'".$IVADEFAULT['IVADEFAULT']."',
											'".$IVACTADEF['IVACTADEF']."',
											'".$_SESSION['USUARIO_ACTUAL']."',
											'".date("Y-m-d H:i:s")."')";
		$query_mast = mysql_query($sql) or die ($sql.mysql_error());
	}
		
	//	inserto las lineas de detalle
	$sql = "SELECT
				c.*,
				lrd.CodItem,
				lrd.CommoditySub,
				lrd.Descripcion,
				lrd.CodCentroCosto,
				lrd.CodUnidad
			FROM
				lg_cotizacion c
				INNER JOIN lg_requerimientosdet lrd ON (c.CodOrganismo = lrd.CodOrganismo AND
														c.CodRequerimiento = lrd.CodRequerimiento AND
														c.Secuencia = lrd.Secuencia)
			WHERE 
				c.FlagAsignado = 'S' AND
				c.CotizacionNumero = '".$cotizacion_numero."'";
	$query_detalle = mysql_query($sql) or die ($sql.mysql_error());
	while ($field_detalle = mysql_fetch_array($query_detalle)) {
		if ($tipo_orden == "SER")
			$secuencia = getCodigo_3("lg_ordenserviciodetalle", "Secuencia", "CodOrganismo", "NroOrden", $organismo, $nroorden, 4);
		else
			$secuencia = getCodigo_3("lg_ordencompradetalle", "Secuencia", "CodOrganismo", "NroOrden", $organismo, $nroorden, 4);
		$secuencia = (int) $secuencia;
		
		if ($field_detalle['CodItem'] != "") {
			$sql = "SELECT
						CtaGasto AS CodCuenta,
						PartidaPresupuestal AS cod_partida
					FROM
						lg_itemmast
					WHERE
						CodItem = '".$field_detalle['CodItem']."'";
			$query_distribucion = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_distribucion) != 0) $field_distribucion = mysql_fetch_array($query_distribucion);
		} else {
			$sql = "SELECT
						CodCuenta,
						cod_partida
					FROM
						lg_commoditysub
					WHERE
						Codigo = '".$field_detalle['CommoditySub']."'";
			$query_distribucion = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_distribucion) != 0) $field_distribucion = mysql_fetch_array($query_distribucion);
		}
		
		if ($tipo_orden == "SER") {
			$sql = "INSERT INTO lg_ordenserviciodetalle (CodOrganismo,
															NroOrden,
															Secuencia,
															CommoditySub,
															Descripcion,
															CantidadPedida,
															PrecioUnit,
															Total,
															CodCentroCosto,
															FlagTerminado,
															FechaEsperadaTermino,
															cod_partida,
															CodCuenta,
															UltimoUsuario,
															UltimaFecha)
													VALUES ('".$organismo."',
															'".$nroorden."',
															'".$secuencia."',
															'".$field_detalle['CommoditySub']."',
															'".$field_detalle['Descripcion']."',
															'".$field_detalle['Cantidad']."',
															'".$field_detalle['PrecioUnit']."',
															'".$field_detalle['Total']."',
															'".$field_detalle['CodCentroCosto']."',
															'N',
															'".formatFechaAMD($fentrega)."',
															'".$field_distribucion['cod_partida']."',
															'".$field_distribucion['CodCuenta']."',
															'".$_SESSION['USUARIO_ACTUAL']."',
															'".date("Y-m-d H:i:s")."')";
			$query_orden_detalle = mysql_query($sql) or die ($sql.mysql_error());
		} else {
			$sql = "INSERT INTO lg_ordencompradetalle (CodOrganismo,
														NroOrden,
														Secuencia,
														CodItem,
														CommoditySub,
														Descripcion,
														CodUnidad,
														CantidadPedida,
														PrecioUnit,
														PrecioCantidad,
														Total,
														DescuentoPorcentaje,
														DescuentoFijo,
														FlagExonerado,
														CodCentroCosto,
														Estado,
														CodCuenta,
														cod_partida,
														UltimoUsuario,
														UltimaFecha)
												VALUES ('".$organismo."',
														'".$nroorden."',
														'".$secuencia."',
														'".$field_detalle['CodItem']."',
														'".$field_detalle['CommoditySub']."',
														'".$field_detalle['Descripcion']."',
														'".$field_detalle['CodUnidad']."',
														'".$field_detalle['Cantidad']."',
														'".$field_detalle['PrecioUnitInicio']."',
														'".$field_detalle['PrecioCantidad']."',
														'".$field_detalle['Total']."',
														'".$field_detalle['DescuentoPorcentaje']."',
														'".$field_detalle['DescuentoFijo']."',
														'".$field_detalle['FlagExonerado']."',
														'".$field_detalle['CodCentroCosto']."',
														'PR',
														'".$field_distribucion['CodCuenta']."',
														'".$field_distribucion['cod_partida']."',
														'".$_SESSION['USUARIO_ACTUAL']."',
														'".date("Y-m-d H:i:s")."')";
			$query_orden_detalle = mysql_query($sql) or die ($sql.mysql_error());
		}
		
		//	actualizo el detalle del requerimiento
		$sql = "UPDATE lg_requerimientosdet
				SET	
					NroOrden = '".$nroorden."',
					CantidadOrdenCompra = '".$field_detalle['Cantidad']."',
					Estado = 'CO'
				WHERE
					CodOrganismo = '".$field_detalle['CodOrganismo']."' AND
					CodRequerimiento = '".$field_detalle['CodRequerimiento']."' AND
					Secuencia = '".$field_detalle['Secuencia']."'";
		$query_requerimiento_detalle = mysql_query($sql) or die ($sql.mysql_error());
	}
	if ($tipo_orden == "SER") echo "|Nueva Orden de Servicio $nroorden ha sido creada";
	else echo "|Nueva Orden de Compra $nroorden ha sido creada";
}
	
//	--------------------------
elseif ($accion == "setClasificacionTipoReq") {
	connect();
	
	$sql = "SELECT TipoRequerimiento FROM lg_clasificacion WHERE Clasificacion = '".$clasificacion."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	
	echo "|".$field['TipoRequerimiento'];
}
	
//	--------------------------
elseif ($accion == "insertarItemOrden") {
	connect();
	$_PARAMETRO = PARAMETROS("CCOSTOCOMPRA");
	
	//	valido items y consulto items o commodities
	$codigo_mostrar = $codigo;
	if ($tabla == "item") {
		$idrow = "$codigo";
		$detalle = split(";", $detalles);
		foreach ($detalle as $linea) {
			list($registro, $cantidad, $preciounit, $codpartida, $codcuenta) = split("[|]", $linea);
			if ($codigo == $registro) die("¡No se puede insertar dos veces el mismo $tabla en una misma orden!");
		}		
		$sql = "SELECT
					i.Descripcion,
					i.CodUnidad,
					i.CtaGasto AS CodCuenta,
					i.PartidaPresupuestal AS cod_partida
				FROM lg_itemmast i
				WHERE i.CodItem = '".$codigo."'";
	} else {
		$codigo_commodity = "$codigo.$nrodetalles";
		$idrow = "$codigo_commodity";		
		$sql = "SELECT
					cs.Descripcion,
					cs.CodUnidad,
					cs.CodCuenta,
					cs.cod_partida
				FROM lg_commoditysub cs
				WHERE cs.Codigo = '".$codigo."'";
	}
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	$estado = "En Preparación";
		
	//	imprimo el registro seleccionado de la lista
	echo "||";
	?>
    <td align="center"><?=$nrodetalles?><input type="checkbox" name="chkdetalles" id="chk_<?=$idrow?>" title="<?=$idrow?>" style="display:none;" /></td>
    <td align="center"><?=$codigo_mostrar?></td>
    <td><input type="text" name="txtdescripcion" value="<?=htmlentities($field['Descripcion'])?>" style="width:98%" /></td>
    <td align="center"><input type="hidden" name="txtcodunidad" value="<?=$field['CodUnidad']?>" /><?=$field['CodUnidad']?></td>
    <td>
        <input type="text" name="txtcantidad" id="txtcantidad_<?=$idrow?>" value="0,00" style="width:98%" dir="rtl" onchange="setTotalesOrden('<?=$idrow?>');" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" />
    </td>
    <td>
        <input type="text" name="txtpu" id="txtpu_<?=$idrow?>" value="0,00" style="width:98%" dir="rtl" onchange="setTotalesOrden('<?=$idrow?>');" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
    <td>
        <input type="text" name="txtdescp" id="txtdescp_<?=$idrow?>" value="0,00" style="width:98%" dir="rtl" onchange="setTotalesOrden('<?=$idrow?>');" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" />
    </td>
    <td>
        <input type="text" name="txtdescf" id="txtdescf_<?=$idrow?>" value="0,00" style="width:98%" dir="rtl" onchange="setTotalesOrden('<?=$idrow?>');" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" />
    </td>		
    <td>
        <input type="checkbox" name="chkexon" id="chkexon_<?=$idrow?>" style="width:98%" onclick="setTotalesOrden('<?=$idrow?>');" />
    </td>
    <td align="right"><span id="spufinal_<?=$idrow?>">0,00</span></td>
    <td align="right"><span id="smontofinal_<?=$idrow?>">0,00</span></td>
    <td><input type="text" name="txtfentrega" style="width:98%; text-align:center;" value="<?=$fentrega?>" /></td>
    <td align="right">0,00</td>
    <td>
        <input type="hidden" name="txtnomccostos" id="txtnomccostos_<?=$idrow?>" value="<?=$_PARAMETRO['CCOSTOCOMPRA']?>" />
        <input type="text" name="txtccostos" id="txtccostos_<?=$idrow?>" value="<?=$_PARAMETRO['CCOSTOCOMPRA']?>" style="width:98%; text-align:center;" disabled="disabled" />
    </td>
    <td align="center"><?=htmlentities($estado)?></td>
    <td align="center">
    	<input type="hidden" name="codpartida" value="<?=$field['cod_partida']?>" />
		<?=$field['cod_partida']?>
	</td>
    <td align="center">
    	<input type="hidden" name="codcuenta" value="<?=$field['CodCuenta']?>" />
		<?=$field['CodCuenta']?>
	</td>
    <td><input type="text" name="txtobs" style="width:98%" value="<?=$observaciones?>" /></td>
	<?
	echo "||$idrow||";
	
	//	imprimo la distribucion presupuestaria
	if ($tabla == "item") {
		$filtro_sel = "i.CodItem = '".$codigo."'";
		if ($detalles != "") {
			$detalle = split(";", $detalles);
			foreach ($detalle as $linea) {
				list($registro, $descripcion, $cantidad, $preciounit, $codpartida) = split("[|]", $linea);
				$filtro_det .= " OR i.CodItem = '".$registro."'";
				$partida[$codpartida] += ($cantidad * $preciounit);
			}
		}
        //	consulto las partidas de los items
		$sql = "(SELECT 
					do.cod_partida,
					pc.denominacion,
					pvp.EjercicioPpto,
					(pvpd.MontoAjustado - pvpd.MontoCompromiso + do.Monto) AS MontoDisponible
				 FROM
					lg_distribucionoc do
					INNER JOIN pv_partida pc ON (do.cod_partida = pc.cod_partida)
					LEFT JOIN pv_presupuestodet pvpd ON (pc.cod_partida = pvpd.cod_partida AND pvpd.Organismo = do.CodOrganismo)
					LEFT JOIN pv_presupuesto pvp ON (pvp.Organismo = pvpd.Organismo AND
													 pvp.CodPresupuesto = pvpd.CodPresupuesto AND
													 pvp.EjercicioPpto = SUBSTRING(do.Periodo, 1, 4))
				 WHERE
					do.CodOrganismo = '".$organismo."' AND
					do.NroOrden = '".$nroorden."' AND
					pc.cod_partida <> '".getParametro('IVADEFAULT')."')
					
				UNION
				
				(SELECT
					p.cod_partida,
					p.denominacion,
					pvp.EjercicioPpto,
					(pvpd.MontoAjustado - pvpd.MontoCompromiso) AS MontoDisponible
				 FROM
					lg_itemmast i
					INNER JOIN pv_partida p ON (i.PartidaPresupuestal = p.cod_partida)
					LEFT JOIN pv_presupuestodet pvpd ON (p.cod_partida = pvpd.cod_partida AND pvpd.Organismo = '".$organismo."')
					LEFT JOIN pv_presupuesto pvp ON (pvp.Organismo = pvpd.Organismo AND
													 pvp.CodPresupuesto = pvpd.CodPresupuesto AND
													 pvp.EjercicioPpto = '".date("Y")."')
				 WHERE 
				 	1 AND ($filtro_sel $filtro_det) AND
					p.cod_partida NOT IN (SELECT 
												do1.cod_partida
										  FROM
												lg_distribucionoc do1
												INNER JOIN pv_partida pc1 ON (do1.cod_partida = pc1.cod_partida)
												LEFT JOIN pv_presupuestodet pvpd1 ON (pc1.cod_partida = pvpd1.cod_partida AND pvpd1.Organismo = do1.CodOrganismo)
												LEFT JOIN pv_presupuesto pvp1 ON (pvp1.Organismo = pvpd1.Organismo AND
																				  pvp1.CodPresupuesto = pvpd1.CodPresupuesto AND
																				  pvp1.EjercicioPpto = SUBSTRING(do1.Periodo, 1, 4))
										  WHERE
												do1.CodOrganismo = '".$organismo."' AND
												do1.NroOrden = '".$nroorden."' AND
												pc1.cod_partida <> '".getParametro('IVADEFAULT')."')					
				 GROUP BY cod_partida)
				
				ORDER BY cod_partida";
    } else {
		$filtro_sel = "cs.Codigo = '".$codigo."'";
		if ($detalles != "") {
			$detalle = split(";", $detalles);
			foreach ($detalle as $linea) {	$i++;
				list($registro, $descripcion, $cantidad, $preciounit, $codpartida) = split("[|]", $linea);
				list($commodity, $sec) = split("[.]", $registro);
				$filtro_det .= " OR cs.Codigo = '".$commodity."'";
				$partida[$codpartida] += ($cantidad * $preciounit);
			}
		}
        //	consulto las partidas de los commodities
		$sql = "(SELECT 
					do.cod_partida,
					pc.denominacion,
					pvp.EjercicioPpto,
					(pvpd.MontoAjustado - pvpd.MontoCompromiso + do.Monto) AS MontoDisponible
				 FROM
					lg_distribucionoc do
					INNER JOIN pv_partida pc ON (do.cod_partida = pc.cod_partida)
					LEFT JOIN pv_presupuestodet pvpd ON (pc.cod_partida = pvpd.cod_partida AND pvpd.Organismo = do.CodOrganismo)
					LEFT JOIN pv_presupuesto pvp ON (pvp.Organismo = pvpd.Organismo AND
													 pvp.CodPresupuesto = pvpd.CodPresupuesto AND
													 pvp.EjercicioPpto = SUBSTRING(do.Periodo, 1, 4))
				 WHERE
					do.CodOrganismo = '".$organismo."' AND
					do.NroOrden = '".$nroorden."' AND
					pc.cod_partida <> '".getParametro('IVADEFAULT')."')
					
				UNION
				
				(SELECT
					p.cod_partida,
					p.denominacion,
					pvp.EjercicioPpto,
					(pvpd.MontoAjustado - pvpd.MontoCompromiso) AS MontoDisponible
				 FROM
					lg_commoditysub cs
					INNER JOIN pv_partida p ON (cs.cod_partida = p.cod_partida)
					LEFT JOIN pv_presupuestodet pvpd ON (p.cod_partida = pvpd.cod_partida AND pvpd.Organismo = '".$organismo."')
					LEFT JOIN pv_presupuesto pvp ON (pvp.Organismo = pvpd.Organismo AND
													 pvp.CodPresupuesto = pvpd.CodPresupuesto AND
													 pvp.EjercicioPpto = '".date("Y")."')
				 WHERE 
				 	1 AND ($filtro_sel $filtro_det) AND
					p.cod_partida NOT IN (SELECT 
												do1.cod_partida
										  FROM
												lg_distribucionoc do1
												INNER JOIN pv_partida pc1 ON (do1.cod_partida = pc1.cod_partida)
												LEFT JOIN pv_presupuestodet pvpd1 ON (pc1.cod_partida = pvpd1.cod_partida AND pvpd1.Organismo = do1.CodOrganismo)
												LEFT JOIN pv_presupuesto pvp1 ON (pvp1.Organismo = pvpd1.Organismo AND
																				  pvp1.CodPresupuesto = pvpd1.CodPresupuesto AND
																				  pvp1.EjercicioPpto = SUBSTRING(do1.Periodo, 1, 4))
										  WHERE
												do1.CodOrganismo = '".$organismo."' AND
												do1.NroOrden = '".$nroorden."' AND
												pc1.cod_partida <> '".getParametro('IVADEFAULT')."')					
				 GROUP BY cod_partida)
				
				ORDER BY cod_partida";
	}
	$query_general = mysql_query($sql) or die ($sql.mysql_error());
	while($field_general = mysql_fetch_array($query_general)) {
		$codpartida = $field_general['cod_partida'];
		$resta = $field_general['MontoDisponible'] - $partida[$codpartida];
		if ($resta < 0) $style = "style='font-weight:bold; background-color:#F8637D;'";
		else $style = "style='font-weight:bold; background-color:#D0FDD2;'";		
		$anio = $field_general['EjercicioPpto'];
		if ($partida[$codpartida] > 0) {
			?>
			<tr class="trListaBody" <?=$style?>>
				<td align="center"><?=$field_general['cod_partida']?></td>
				<td><?=htmlentities($field_general['denominacion'])?></td>
				<td align="right"><?=number_format($partida[$codpartida], 2, ',', '.')?></td>
			</tr>
			<?php
		}
	}
	if ($total_impuesto > 0) {
		//	si ya tiene distribuido algun monto en el igv lo obtengo
		$sql = "SELECT do.Monto
				FROM lg_distribucionoc do
				WHERE
					do.CodOrganismo = '".$organismo."' AND
					do.NroOrden = '".$nroorden."' AND
					do.cod_partida = '".getParametro('IVADEFAULT')."'";
		$query_igv = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_igv) != 0) $field_igv = mysql_fetch_array($query_general);
		$montoigv = (float) $field_igv['Monto'];
		
		//	obtengo la disponibilidad de la partida del igv		
		$sql = "SELECT
					p.cod_partida,
					p.denominacion,
					(pvpd.MontoAjustado - pvpd.MontoCompromiso + $montoigv) AS MontoDisponible
				FROM
					pv_partida p
					LEFT JOIN pv_presupuestodet pvpd ON (p.cod_partida = pvpd.cod_partida)
					LEFT JOIN pv_presupuesto pvp ON (pvp.CodPresupuesto = pvpd.CodPresupuesto AND
													 pvp.EjercicioPpto = '$anio')
				WHERE p.cod_partida = '".getParametro('IVADEFAULT')."'";
		$query_general = mysql_query($sql) or die ($sql.mysql_error());
		while($field_general = mysql_fetch_array($query_general)) {
			$resta = $field_general['MontoDisponible'] - $total_impuesto;			
			if ($resta <= 0) $style = "style='font-weight:bold; background-color:#F8637D;'";
			else $style = "style='font-weight:bold; background-color:#D0FDD2;'";
			?>
			<tr class="trListaBody" <?=$style?>>
				<td align="center"><?=$field_general['cod_partida']?></td>
				<td><?=htmlentities($field_general['denominacion'])?></td>
				<td align="right"><?=number_format($total_impuesto, 2, ',', '.')?></td>
			</tr>
			<?php
		}
	}
		
	echo "||";	
	//	imprimo la distribucion contagble
	if ($tabla == "item") {
		$filtro = "i.CodItem = '".$codigo."'";
		if ($detalles != "") {
			$detalle = split(";", $detalles);
			foreach ($detalle as $linea) {
				list($registro, $descripcion, $cantidad, $preciounit, $codpartida, $codcuenta) = split("[|]", $linea);
				$filtro .= " OR i.CodItem = '".$registro."'";
				$cuenta[$codcuenta] += ($cantidad * $preciounit);
			}
		}
        //	consulto las cuentas de los items
        $sql = "SELECT
                    pc.CodCuenta,
                    pc.Descripcion
                FROM
                    lg_itemmast i
                    INNER JOIN ac_mastplancuenta pc ON (i.CtaGasto = pc.CodCuenta)
                WHERE 1 AND ($filtro)
                GROUP BY CodCuenta
                ORDER BY CodCuenta";
    } else {
		$filtro = "cs.Codigo = '".$codigo."'";
		if ($detalles != "") {
			$detalle = split(";", $detalles);
			foreach ($detalle as $linea) {	$i++;
				list($registro, $descripcion, $cantidad, $preciounit, $codpartida, $codcuenta) = split("[|]", $linea);
				list($commodity, $sec) = split("[.]", $linea);
				$filtro .= " OR cs.Codigo = '".$commodity."'";
				$cuenta[$codcuenta] += ($cantidad * $preciounit);
			}
		}
        //	consulto las cuentas de los commodities
        $sql = "SELECT
                    pc.CodCuenta,
                    pc.Descripcion
                FROM
                    lg_commoditysub cs
                    INNER JOIN ac_mastplancuenta pc ON (cs.CodCuenta = pc.CodCuenta)
                WHERE 1 AND ($filtro)
                GROUP BY CodCuenta
                ORDER BY CodCuenta";
	}
	$query_general = mysql_query($sql) or die ($sql.mysql_error());
	while($field_general = mysql_fetch_array($query_general)) {
		$codcuenta = $field_general['CodCuenta'];
		?>
        <tr class="trListaBody">
        	<td align="center"><?=$field_general['CodCuenta']?></td>
            <td><?=htmlentities($field_general['Descripcion'])?></td>
        	<td align="right"><?=number_format($cuenta[$codcuenta], 2, ',', '.')?></td>
		</tr>
        <?php
	}	
	if ($total_impuesto > 0) {
		$sql = "SELECT
					pc.CodCuenta,
					pc.Descripcion
				FROM ac_mastplancuenta pc
				WHERE pc.CodCuenta = '".getParametro('IVACTADEF')."'";
		$query_general = mysql_query($sql) or die ($sql.mysql_error());
		while($field_general = mysql_fetch_array($query_general)) {
		?>
        <tr class="trListaBody">
        	<td align="center"><?=$field_general['CodCuenta']?></td>
            <td><?=htmlentities($field_general['Descripcion'])?></td>
        	<td align="right"><?=number_format($total_impuesto, 2, ',', '.')?></td>
		</tr>
        <?php
	}
	}
}
	
//	--------------------------
elseif ($accion == "imprimirDistribucionOrden") {
	connect();
	//	imprimo la distribucion presupuestaria
	if ($tabla == "item") {
		$filtro_sel = "i.CodItem = '".$codigo."'";
		if ($detalles != "") {
			$detalle = split(";", $detalles);
			foreach ($detalle as $linea) {
				list($registro, $descripcion, $cantidad, $preciounit, $codpartida) = split("[|]", $linea);
				$filtro_det .= " OR i.CodItem = '".$registro."'";
				$partida[$codpartida] += ($cantidad * $preciounit);
			}
		}
        //	consulto las partidas de los items
		$sql = "(SELECT 
					do.cod_partida,
					pc.denominacion,
					pvp.EjercicioPpto,
					(pvpd.MontoAjustado - pvpd.MontoCompromiso + do.Monto) AS MontoDisponible
				 FROM
					lg_distribucionoc do
					INNER JOIN pv_partida pc ON (do.cod_partida = pc.cod_partida)
					LEFT JOIN pv_presupuestodet pvpd ON (pc.cod_partida = pvpd.cod_partida AND pvpd.Organismo = do.CodOrganismo)
					LEFT JOIN pv_presupuesto pvp ON (pvp.Organismo = pvpd.Organismo AND
													 pvp.CodPresupuesto = pvpd.CodPresupuesto AND
													 pvp.EjercicioPpto = SUBSTRING(do.Periodo, 1, 4))
				 WHERE
					do.CodOrganismo = '".$organismo."' AND
					do.NroOrden = '".$nroorden."' AND
					pc.cod_partida <> '".getParametro('IVADEFAULT')."')
					
				UNION
				
				(SELECT
					p.cod_partida,
					p.denominacion,
					pvp.EjercicioPpto,
					(pvpd.MontoAjustado - pvpd.MontoCompromiso) AS MontoDisponible
				 FROM
					lg_itemmast i
					INNER JOIN pv_partida p ON (i.PartidaPresupuestal = p.cod_partida)
					LEFT JOIN pv_presupuestodet pvpd ON (p.cod_partida = pvpd.cod_partida AND pvpd.Organismo = '".$organismo."')
					LEFT JOIN pv_presupuesto pvp ON (pvp.Organismo = pvpd.Organismo AND
													 pvp.CodPresupuesto = pvpd.CodPresupuesto AND
													 pvp.EjercicioPpto = '".date("Y")."')
				 WHERE 
				 	1 AND ($filtro_sel $filtro_det) AND
					p.cod_partida NOT IN (SELECT 
												do1.cod_partida
										  FROM
												lg_distribucionoc do1
												INNER JOIN pv_partida pc1 ON (do1.cod_partida = pc1.cod_partida)
												LEFT JOIN pv_presupuestodet pvpd1 ON (pc1.cod_partida = pvpd1.cod_partida AND pvpd1.Organismo = do1.CodOrganismo)
												LEFT JOIN pv_presupuesto pvp1 ON (pvp1.Organismo = pvpd1.Organismo AND
																				  pvp1.CodPresupuesto = pvpd1.CodPresupuesto AND
																				  pvp1.EjercicioPpto = SUBSTRING(do1.Periodo, 1, 4))
										  WHERE
												do1.CodOrganismo = '".$organismo."' AND
												do1.NroOrden = '".$nroorden."' AND
												pc1.cod_partida <> '".getParametro('IVADEFAULT')."')					
				 GROUP BY cod_partida)
				
				ORDER BY cod_partida";
    } else {
		$filtro_sel = "cs.Codigo = '".$codigo."'";
		if ($detalles != "") {
			$detalle = split(";", $detalles);
			foreach ($detalle as $linea) {	$i++;
				list($registro, $descripcion, $cantidad, $preciounit, $codpartida) = split("[|]", $linea);
				list($commodity, $sec) = split("[.]", $registro);
				$filtro_det .= " OR cs.Codigo = '".$commodity."'";
				$partida[$codpartida] += ($cantidad * $preciounit);
			}
		}
        //	consulto las partidas de los commodities
		$sql = "(SELECT 
					do.cod_partida,
					pc.denominacion,
					pvp.EjercicioPpto,
					(pvpd.MontoAjustado - pvpd.MontoCompromiso + do.Monto) AS MontoDisponible
				 FROM
					lg_distribucionoc do
					INNER JOIN pv_partida pc ON (do.cod_partida = pc.cod_partida)
					LEFT JOIN pv_presupuestodet pvpd ON (pc.cod_partida = pvpd.cod_partida AND pvpd.Organismo = do.CodOrganismo)
					LEFT JOIN pv_presupuesto pvp ON (pvp.Organismo = pvpd.Organismo AND
													 pvp.CodPresupuesto = pvpd.CodPresupuesto AND
													 pvp.EjercicioPpto = SUBSTRING(do.Periodo, 1, 4))
				 WHERE
					do.CodOrganismo = '".$organismo."' AND
					do.NroOrden = '".$nroorden."' AND
					pc.cod_partida <> '".getParametro('IVADEFAULT')."')
					
				UNION
				
				(SELECT
					p.cod_partida,
					p.denominacion,
					pvp.EjercicioPpto,
					(pvpd.MontoAjustado - pvpd.MontoCompromiso) AS MontoDisponible
				 FROM
					lg_commoditysub cs
					INNER JOIN pv_partida p ON (cs.cod_partida = p.cod_partida)
					LEFT JOIN pv_presupuestodet pvpd ON (p.cod_partida = pvpd.cod_partida AND pvpd.Organismo = '".$organismo."')
					LEFT JOIN pv_presupuesto pvp ON (pvp.Organismo = pvpd.Organismo AND
													 pvp.CodPresupuesto = pvpd.CodPresupuesto AND
													 pvp.EjercicioPpto = '".date("Y")."')
				 WHERE 
				 	1 AND ($filtro_sel $filtro_det) AND
					p.cod_partida NOT IN (SELECT 
												do1.cod_partida
										  FROM
												lg_distribucionoc do1
												INNER JOIN pv_partida pc1 ON (do1.cod_partida = pc1.cod_partida)
												LEFT JOIN pv_presupuestodet pvpd1 ON (pc1.cod_partida = pvpd1.cod_partida AND pvpd1.Organismo = do1.CodOrganismo)
												LEFT JOIN pv_presupuesto pvp1 ON (pvp1.Organismo = pvpd1.Organismo AND
																				  pvp1.CodPresupuesto = pvpd1.CodPresupuesto AND
																				  pvp1.EjercicioPpto = SUBSTRING(do1.Periodo, 1, 4))
										  WHERE
												do1.CodOrganismo = '".$organismo."' AND
												do1.NroOrden = '".$nroorden."' AND
												pc1.cod_partida <> '".getParametro('IVADEFAULT')."')					
				 GROUP BY cod_partida)
				
				ORDER BY cod_partida";
	}
	$query_general = mysql_query($sql) or die ($sql.mysql_error());
	while($field_general = mysql_fetch_array($query_general)) {
		$codpartida = $field_general['cod_partida'];
		$resta = $field_general['MontoDisponible'] - $partida[$codpartida];		
		if ($resta < 0) $style = "style='font-weight:bold; background-color:#F8637D;'";
		else $style = "style='font-weight:bold; background-color:#D0FDD2;'";		
		$anio = $field_general['EjercicioPpto'];
		if ($partida[$codpartida] > 0) {
			?>
			<tr class="trListaBody" <?=$style?>>
				<td align="center"><?=$field_general['cod_partida']?></td>
				<td><?=htmlentities($field_general['denominacion'])?></td>
				<td align="right"><?=number_format($partida[$codpartida], 2, ',', '.')?></td>
			</tr>
			<?php
		}
	}
	if ($total_impuesto > 0) {
		//	si ya tiene distribuido algun monto en el igv lo obtengo
		$sql = "SELECT do.Monto
				FROM lg_distribucionoc do
				WHERE
					do.CodOrganismo = '".$organismo."' AND
					do.NroOrden = '".$nroorden."' AND
					do.cod_partida = '".getParametro('IVADEFAULT')."'";
		$query_igv = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_igv) != 0) $field_igv = mysql_fetch_array($query_general);
		$montoigv = (float) $field_igv['Monto'];
		
		//	obtengo la disponibilidad de la partida del igv		
		$sql = "SELECT
					p.cod_partida,
					p.denominacion,
					(pvpd.MontoAjustado - pvpd.MontoCompromiso + $montoigv) AS MontoDisponible
				FROM
					pv_partida p
					LEFT JOIN pv_presupuestodet pvpd ON (p.cod_partida = pvpd.cod_partida)
					LEFT JOIN pv_presupuesto pvp ON (pvp.CodPresupuesto = pvpd.CodPresupuesto AND
													 pvp.EjercicioPpto = '$anio')
				WHERE p.cod_partida = '".getParametro('IVADEFAULT')."'";
		$query_general = mysql_query($sql) or die ($sql.mysql_error());
		while($field_general = mysql_fetch_array($query_general)) {
			$resta = $field_general['MontoDisponible'] - $total_impuesto;			
			if ($resta <= 0) $style = "style='font-weight:bold; background-color:#F8637D;'";
			else $style = "style='font-weight:bold; background-color:#D0FDD2;'";
			?>
			<tr class="trListaBody" <?=$style?>>
				<td align="center"><?=$field_general['cod_partida']?></td>
				<td><?=htmlentities($field_general['denominacion'])?></td>
				<td align="right"><?=number_format($total_impuesto, 2, ',', '.')?></td>
			</tr>
			<?php
		}
	}
	
	echo "||";	
	//	imprimo la distribucion contagble
	if ($tabla == "item") {
		$filtro = "i.CodItem = ''";
        $detalle = split(";", $detalles);
        foreach ($detalle as $linea) {
            list($registro, $descripcion, $cantidad, $preciounit, $codpartida, $codcuenta) = split("[|]", $linea);
            $filtro .= " OR i.CodItem = '".$registro."'";
			$cuenta[$codcuenta] += ($cantidad * $preciounit);
        }
        //	consulto las cuentas de los items
        $sql = "SELECT
                    pc.CodCuenta,
                    pc.Descripcion
                FROM
                    lg_itemmast i
                    INNER JOIN ac_mastplancuenta pc ON (i.CtaGasto = pc.CodCuenta)
                WHERE 1 AND ($filtro)
                GROUP BY CodCuenta
                ORDER BY CodCuenta";
    } else {
		$filtro = "cs.Codigo = ''";
        $detalle = split(";", $detalles);
        foreach ($detalle as $linea) {	$i++;
            list($registro, $descripcion, $cantidad, $preciounit, $codpartida, $codcuenta) = split("[|]", $linea);
            list($commodity, $codsecuencia) = split("[.]", $linea);
            $filtro .= " OR cs.Codigo = '".$commodity."'";
			$cuenta[$codcuenta] += ($cantidad * $preciounit);
        }
        //	consulto las cuentas de los commodities
        $sql = "SELECT
                    pc.CodCuenta,
                    pc.Descripcion
                FROM
                    lg_commoditysub cs
                    INNER JOIN ac_mastplancuenta pc ON (cs.CodCuenta = pc.CodCuenta)
                WHERE 1 AND ($filtro)
                GROUP BY CodCuenta
                ORDER BY CodCuenta";
	}
	$query_general = mysql_query($sql) or die ($sql.mysql_error());
	while($field_general = mysql_fetch_array($query_general)) {
		$codcuenta = $field_general['CodCuenta'];
		?>
        <tr class="trListaBody">
        	<td align="center"><?=$field_general['CodCuenta']?></td>
            <td><?=htmlentities($field_general['Descripcion'])?></td>
        	<td align="right"><?=number_format($cuenta[$codcuenta], 2, ',', '.')?></td>
		</tr>
        <?php
	}
	if ($total_impuesto > 0) {
		$sql = "SELECT
					pc.CodCuenta,
					pc.Descripcion
				FROM ac_mastplancuenta pc
				WHERE pc.CodCuenta = '".getParametro('IVACTADEF')."'";
		$query_general = mysql_query($sql) or die ($sql.mysql_error());
		while($field_general = mysql_fetch_array($query_general)) {
			?>
			<tr class="trListaBody">
				<td align="center"><?=$field_general['CodCuenta']?></td>
				<td><?=htmlentities($field_general['Descripcion'])?></td>
				<td align="right"><?=number_format($total_impuesto, 2, ',', '.')?></td>
			</tr>
			<?php
		}
	}
}
	
//	--------------------------
elseif ($accion == "insertarItemOrdenServicio") {
	connect();	
	$codigo_commodity = "$codigo.$nrodetalles";
	$idrow = "$codigo_commodity";
	
	//	inserta en la tabla
	echo "||";
	$sql = "SELECT * FROM lg_commoditysub WHERE Codigo = '".$codigo."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	?>
    <td align="center"><?=$nrodetalles?><input type="checkbox" name="chkdetalles" id="chk_<?=$idrow?>" title="<?=$idrow?>" style="display:none;" /></td>
    <td align="center"><?=$codigo?></td>
    <td><input type="text" name="txtdescripcion" value="<?=htmlentities($field['Descripcion'])?>" style="width:98%" /></td>
    <td>
        <input type="text" name="txtcantidad" id="txtcantidad_<?=$idrow?>" value="0,00" style="width:98%" dir="rtl" onchange="setTotalesOrdenServicio('<?=$idrow?>');" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" />
    </td>
    <td>
        <input type="text" name="txtpu" id="txtpu_<?=$idrow?>" value="0,00" style="width:98%" dir="rtl" onchange="setTotalesOrdenServicio('<?=$idrow?>');" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
    <td align="right"><span id="smontofinal_<?=$idrow?>">0,00</span></td>
    <td><input type="text" name="txtfesperada" style="width:98%; text-align:center;" value="<?=$fentrega?>" maxlength="10" /></td>
    <td><input type="text" name="txtfreal" style="width:98%; text-align:center;" disabled="disabled" /></td>
    <td align="right"><span id="scantidadr_<?=$idrow?>">0,00</span></td>
    <td>
        <input type="hidden" name="txtnomccostos" id="txtnomccostos_<?=$idrow?>" value="<?=$codccosto?>" />
        <input type="text" name="txtccostos" id="txtccostos_<?=$idrow?>" style="width:98%; text-align:center;" disabled="disabled" value="<?=$codccosto?>" />
    </td>
    <td><input type="text" name="txtactivo" style="width:98%" /></td>		
    <td>
        <input type="checkbox" name="chkterminado" id="chkterminado_<?=$idrow?>" style="width:98%" value="S" />
    </td>
    <td align="center">
        <input type="hidden" name="codpartida" value="<?=$field['cod_partida']?>" />
        <?=$field['cod_partida']?>
    </td>
    <td align="center">
        <input type="hidden" name="codcuenta" value="<?=$field['CodCuenta']?>" />
        <?=$field['CodCuenta']?>
    </td>
    <td><input type="text" name="txtobs" value="<?=htmlentities($descripcion)?>" style="width:98%" /></td>	
	<?
	echo "||$idrow||";
	
	//	imprimo la distribucion presupuestaria
	$filtro_sel = "cs.Codigo = '".$codigo."'";
	if ($detalles != "") {
		$detalle = split(";", $detalles);
		foreach ($detalle as $linea) {	$i++;
			list($registro, $descripcion, $cantidad, $preciounit, $codpartida) = split("[|]", $linea);
			list($commodity, $sec) = split("[.]", $registro);
			$filtro_det .= " OR cs.Codigo = '".$commodity."'";
			$partida[$codpartida] += ($cantidad * $preciounit);
		}
	}
	//	consulto las partidas de los commodities
	$sql = "(SELECT 
				do.cod_partida,
				pc.denominacion,
				pvp.EjercicioPpto,
				(pvpd.MontoAjustado - pvpd.MontoCompromiso + do.Monto) AS MontoDisponible
			 FROM
				lg_distribucionos do
				INNER JOIN pv_partida pc ON (do.cod_partida = pc.cod_partida)
				LEFT JOIN pv_presupuestodet pvpd ON (pc.cod_partida = pvpd.cod_partida AND pvpd.Organismo = do.CodOrganismo)
				LEFT JOIN pv_presupuesto pvp ON (pvp.Organismo = pvpd.Organismo AND
												 pvp.CodPresupuesto = pvpd.CodPresupuesto AND
												 pvp.EjercicioPpto = SUBSTRING(do.Periodo, 1, 4))
			 WHERE
				do.CodOrganismo = '".$organismo."' AND
				do.NroOrden = '".$nroorden."' AND
				pc.cod_partida <> '".getParametro('IVADEFAULT')."')
				
			UNION
			
			(SELECT
				p.cod_partida,
				p.denominacion,
				pvp.EjercicioPpto,
				(pvpd.MontoAjustado - pvpd.MontoCompromiso) AS MontoDisponible
			 FROM
				lg_commoditysub cs
				INNER JOIN pv_partida p ON (cs.cod_partida = p.cod_partida)
				LEFT JOIN pv_presupuestodet pvpd ON (p.cod_partida = pvpd.cod_partida AND pvpd.Organismo = '".$organismo."')
				LEFT JOIN pv_presupuesto pvp ON (pvp.Organismo = pvpd.Organismo AND
												 pvp.CodPresupuesto = pvpd.CodPresupuesto AND
												 pvp.EjercicioPpto = '".date("Y")."')
			 WHERE 
				1 AND ($filtro_sel $filtro_det) AND
				p.cod_partida NOT IN (SELECT 
											do1.cod_partida
									  FROM
											lg_distribucionos do1
											INNER JOIN pv_partida pc1 ON (do1.cod_partida = pc1.cod_partida)
											LEFT JOIN pv_presupuestodet pvpd1 ON (pc1.cod_partida = pvpd1.cod_partida AND pvpd1.Organismo = do1.CodOrganismo)
											LEFT JOIN pv_presupuesto pvp1 ON (pvp1.Organismo = pvpd1.Organismo AND
																			  pvp1.CodPresupuesto = pvpd1.CodPresupuesto AND
																			  pvp1.EjercicioPpto = SUBSTRING(do1.Periodo, 1, 4))
									  WHERE
											do1.CodOrganismo = '".$organismo."' AND
											do1.NroOrden = '".$nroorden."' AND
											pc1.cod_partida <> '".getParametro('IVADEFAULT')."')					
			 GROUP BY cod_partida)
			
			ORDER BY cod_partida";
	$query_general = mysql_query($sql) or die ($sql.mysql_error());
	while($field_general = mysql_fetch_array($query_general)) {
		$codpartida = $field_general['cod_partida'];
		$resta = $field_general['MontoDisponible'] - $partida[$codpartida];		
		if ($resta < 0) $style = "style='font-weight:bold; background-color:#F8637D;'";
		else $style = "style='font-weight:bold; background-color:#D0FDD2;'";		
		$anio = $field_general['EjercicioPpto'];
		if ($partida[$codpartida] > 0) {
			?>
			<tr class="trListaBody" <?=$style?>>
				<td align="center"><?=$field_general['cod_partida']?></td>
				<td><?=htmlentities($field_general['denominacion'])?></td>
				<td align="right"><?=number_format($partida[$codpartida], 2, ',', '.')?></td>
			</tr>
			<?php
		}
	}
	if ($total_impuesto > 0) {
		//	si ya tiene distribuido algun monto en el igv lo obtengo
		$sql = "SELECT do.Monto
				FROM lg_distribucionos do
				WHERE
					do.CodOrganismo = '".$organismo."' AND
					do.NroOrden = '".$nroorden."' AND
					do.cod_partida = '".getParametro('IVADEFAULT')."'";
		$query_igv = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_igv) != 0) $field_igv = mysql_fetch_array($query_general);
		$montoigv = (float) $field_igv['Monto'];
		
		//	obtengo la disponibilidad de la partida del igv		
		$sql = "SELECT
					p.cod_partida,
					p.denominacion,
					(pvpd.MontoAjustado - pvpd.MontoCompromiso + $montoigv) AS MontoDisponible
				FROM
					pv_partida p
					LEFT JOIN pv_presupuestodet pvpd ON (p.cod_partida = pvpd.cod_partida)
					LEFT JOIN pv_presupuesto pvp ON (pvp.CodPresupuesto = pvpd.CodPresupuesto AND
													 pvp.EjercicioPpto = '$anio')
				WHERE p.cod_partida = '".getParametro('IVADEFAULT')."'";
		$query_general = mysql_query($sql) or die ($sql.mysql_error());
		while($field_general = mysql_fetch_array($query_general)) {
			$resta = $field_general['MontoDisponible'] - $total_impuesto;			
			if ($resta <= 0) $style = "style='font-weight:bold; background-color:#F8637D;'";
			else $style = "style='font-weight:bold; background-color:#D0FDD2;'";
			?>
			<tr class="trListaBody" <?=$style?>>
				<td align="center"><?=$field_general['cod_partida']?></td>
				<td><?=htmlentities($field_general['denominacion'])?></td>
				<td align="right"><?=number_format($total_impuesto, 2, ',', '.')?></td>
			</tr>
			<?php
		}
	}
		
	echo "||";
	//	imprimo la distribucion contable
	$filtro = "cs.Codigo = '".$codigo."'";
	if ($detalles != "") {
		$detalle = split(";", $detalles);
		foreach ($detalle as $linea) {	$i++;
			list($registro, $descripcion, $cantidad, $preciounit, $codpartida, $codcuenta) = split("[|]", $linea);
			list($commodity, $sec) = split("[.]", $registro);
			$filtro .= " OR cs.Codigo = '".$commodity."'";
			$cuenta[$codcuenta] += ($cantidad * $preciounit);
		}
	}
	//	consulto las cuentas de los commodities
	$sql = "SELECT
				pc.CodCuenta,
				pc.Descripcion
			FROM
				lg_commoditysub cs
				INNER JOIN ac_mastplancuenta pc ON (cs.CodCuenta = pc.CodCuenta)
			WHERE 1 AND ($filtro)
			GROUP BY CodCuenta
			ORDER BY CodCuenta";
	$query_general = mysql_query($sql) or die ($sql.mysql_error());
	while($field_general = mysql_fetch_array($query_general)) {
		$codcuenta = $field_general['CodCuenta'];
		?>
        <tr class="trListaBody">
        	<td align="center"><?=$field_general['CodCuenta']?></td>
            <td><?=htmlentities($field_general['Descripcion'])?></td>
        	<td align="right"><?=number_format($cuenta[$codcuenta], 2, ',', '.')?></td>
		</tr>
        <?php
	}	
	if ($total_impuesto > 0) {
		$sql = "SELECT
					pc.CodCuenta,
					pc.Descripcion
				FROM ac_mastplancuenta pc
				WHERE pc.CodCuenta = '".getParametro('IVACTADEF')."'";
		$query_general = mysql_query($sql) or die ($sql.mysql_error());
		while($field_general = mysql_fetch_array($query_general)) {
			?>
			<tr class="trListaBody">
				<td align="center"><?=$field_general['CodCuenta']?></td>
				<td><?=htmlentities($field_general['Descripcion'])?></td>
				<td align="right"><?=number_format($total_impuesto, 2, ',', '.')?></td>
			</tr>
			<?php
		}
	}
}
	
//	--------------------------
elseif ($accion == "imprimirDistribucionOrdenServicio") {
	connect();	
	//	imprimo la distribucion presupuestaria
	$filtro_sel = "cs.Codigo = '".$codigo."'";
	if ($detalles != "") {
		$detalle = split(";", $detalles);
		foreach ($detalle as $linea) {	$i++;
			list($registro, $descripcion, $cantidad, $preciounit, $codpartida) = split("[|]", $linea);
			list($commodity, $sec) = split("[.]", $registro);
			$filtro_det .= " OR cs.Codigo = '".$commodity."'";
			$partida[$codpartida] += ($cantidad * $preciounit);
		}
	}
	//	consulto las partidas de los commodities
	$sql = "(SELECT 
				do.cod_partida,
				pc.denominacion,
				pvp.EjercicioPpto,
				(pvpd.MontoAjustado - pvpd.MontoCompromiso + do.Monto) AS MontoDisponible
			 FROM
				lg_distribucionos do
				INNER JOIN pv_partida pc ON (do.cod_partida = pc.cod_partida)
				LEFT JOIN pv_presupuestodet pvpd ON (pc.cod_partida = pvpd.cod_partida AND pvpd.Organismo = do.CodOrganismo)
				LEFT JOIN pv_presupuesto pvp ON (pvp.Organismo = pvpd.Organismo AND
												 pvp.CodPresupuesto = pvpd.CodPresupuesto AND
												 pvp.EjercicioPpto = SUBSTRING(do.Periodo, 1, 4))
			 WHERE
				do.CodOrganismo = '".$organismo."' AND
				do.NroOrden = '".$nroorden."' AND
				pc.cod_partida <> '".getParametro('IVADEFAULT')."')
				
			UNION
			
			(SELECT
				p.cod_partida,
				p.denominacion,
				pvp.EjercicioPpto,
				(pvpd.MontoAjustado - pvpd.MontoCompromiso) AS MontoDisponible
			 FROM
				lg_commoditysub cs
				INNER JOIN pv_partida p ON (cs.cod_partida = p.cod_partida)
				LEFT JOIN pv_presupuestodet pvpd ON (p.cod_partida = pvpd.cod_partida AND pvpd.Organismo = '".$organismo."')
				LEFT JOIN pv_presupuesto pvp ON (pvp.Organismo = pvpd.Organismo AND
												 pvp.CodPresupuesto = pvpd.CodPresupuesto AND
												 pvp.EjercicioPpto = '".date("Y")."')
			 WHERE 
				1 AND ($filtro_sel $filtro_det) AND
				p.cod_partida NOT IN (SELECT 
											do1.cod_partida
									  FROM
											lg_distribucionos do1
											INNER JOIN pv_partida pc1 ON (do1.cod_partida = pc1.cod_partida)
											LEFT JOIN pv_presupuestodet pvpd1 ON (pc1.cod_partida = pvpd1.cod_partida AND pvpd1.Organismo = do1.CodOrganismo)
											LEFT JOIN pv_presupuesto pvp1 ON (pvp1.Organismo = pvpd1.Organismo AND
																			  pvp1.CodPresupuesto = pvpd1.CodPresupuesto AND
																			  pvp1.EjercicioPpto = SUBSTRING(do1.Periodo, 1, 4))
									  WHERE
											do1.CodOrganismo = '".$organismo."' AND
											do1.NroOrden = '".$nroorden."' AND
											pc1.cod_partida <> '".getParametro('IVADEFAULT')."')					
			 GROUP BY cod_partida)
			
			ORDER BY cod_partida";
	$query_general = mysql_query($sql) or die ($sql.mysql_error());
	while($field_general = mysql_fetch_array($query_general)) {
		$codpartida = $field_general['cod_partida'];
		$resta = $field_general['MontoDisponible'] - $partida[$codpartida];		
		if ($resta < 0) $style = "style='font-weight:bold; background-color:#F8637D;'";
		else $style = "style='font-weight:bold; background-color:#D0FDD2;'";		
		$anio = $field_general['EjercicioPpto'];
		if ($partida[$codpartida] > 0) {
			?>
			<tr class="trListaBody" <?=$style?>>
				<td align="center"><?=$field_general['cod_partida']?></td>
				<td><?=htmlentities($field_general['denominacion'])?></td>
				<td align="right"><?=number_format($partida[$codpartida], 2, ',', '.')?></td>
			</tr>
			<?php
		}
	}
	if ($total_impuesto > 0) {
		//	si ya tiene distribuido algun monto en el igv lo obtengo
		$sql = "SELECT do.Monto
				FROM lg_distribucionos do
				WHERE
					do.CodOrganismo = '".$organismo."' AND
					do.NroOrden = '".$nroorden."' AND
					do.cod_partida = '".getParametro('IVADEFAULT')."'";
		$query_igv = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_igv) != 0) $field_igv = mysql_fetch_array($query_general);
		$montoigv = (float) $field_igv['Monto'];
		
		//	obtengo la disponibilidad de la partida del igv		
		$sql = "SELECT
					p.cod_partida,
					p.denominacion,
					(pvpd.MontoAjustado - pvpd.MontoCompromiso + $montoigv) AS MontoDisponible
				FROM
					pv_partida p
					LEFT JOIN pv_presupuestodet pvpd ON (p.cod_partida = pvpd.cod_partida)
					LEFT JOIN pv_presupuesto pvp ON (pvp.CodPresupuesto = pvpd.CodPresupuesto AND
													 pvp.EjercicioPpto = '$anio')
				WHERE p.cod_partida = '".getParametro('IVADEFAULT')."'";
		$query_general = mysql_query($sql) or die ($sql.mysql_error());
		while($field_general = mysql_fetch_array($query_general)) {
			$resta = $field_general['MontoDisponible'] - $total_impuesto;			
			if ($resta <= 0) $style = "style='font-weight:bold; background-color:#F8637D;'";
			else $style = "style='font-weight:bold; background-color:#D0FDD2;'";
			?>
			<tr class="trListaBody" <?=$style?>>
				<td align="center"><?=$field_general['cod_partida']?></td>
				<td><?=htmlentities($field_general['denominacion'])?></td>
				<td align="right"><?=number_format($total_impuesto, 2, ',', '.')?></td>
			</tr>
			<?php
		}
	}
	
	echo "||";	
	//	imprimo la distribucion contable
	$filtro = "cs.Codigo = ''";
	$detalle = split(";", $detalles);
	foreach ($detalle as $linea) {	$i++;
		list($registro, $descripcion, $cantidad, $preciounit, $codpartida, $codcuenta) = split("[|]", $linea);
		list($commodity, $codsecuencia) = split("[.]", $linea);
		$filtro .= " OR cs.Codigo = '".$commodity."'";
		$cuenta[$codcuenta] += ($cantidad * $preciounit);
	}
	//	consulto las cuentas de los commodities
	$sql = "SELECT
				pc.CodCuenta,
				pc.Descripcion
			FROM
				lg_commoditysub cs
				INNER JOIN ac_mastplancuenta pc ON (cs.CodCuenta = pc.CodCuenta)
			WHERE 1 AND ($filtro)
			GROUP BY CodCuenta
			ORDER BY CodCuenta";
	$query_general = mysql_query($sql) or die ($sql.mysql_error());
	while($field_general = mysql_fetch_array($query_general)) {
		$codcuenta = $field_general['CodCuenta'];
		?>
        <tr class="trListaBody">
        	<td align="center"><?=$field_general['CodCuenta']?></td>
            <td><?=htmlentities($field_general['Descripcion'])?></td>
        	<td align="right"><?=number_format($cuenta[$codcuenta], 2, ',', '.')?></td>
		</tr>
        <?php
	}
	if ($total_impuesto > 0) {
		$sql = "SELECT
					pc.CodCuenta,
					pc.Descripcion
				FROM ac_mastplancuenta pc
				WHERE pc.CodCuenta = '".getParametro('IVACTADEF')."'";
		$query_general = mysql_query($sql) or die ($sql.mysql_error());
		while($field_general = mysql_fetch_array($query_general)) {
			?>
			<tr class="trListaBody">
				<td align="center"><?=$field_general['CodCuenta']?></td>
				<td><?=htmlentities($field_general['Descripcion'])?></td>
				<td align="right"><?=number_format($total_impuesto, 2, ',', '.')?></td>
			</tr>
			<?php
		}
	}
}
	
//	--------------------------
elseif ($accion == "insertarItemRequerimiento") {
	connect();
	
	if ($ventana == "insertarItemRequerimiento") $ddesc = "disabled"; else $ddesc = "";
	
	$codigo_mostrar = $codigo;
	if ($tabla == "item") {
		$detalle = split(";", $detalles);
		foreach ($detalle as $registro) {
			if ($codigo == $registro) die("¡No se puede insertar dos veces el mismo $tabla en una misma orden!");
		}
		
		$sql = "SELECT * FROM lg_itemmast WHERE CodItem = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	} else {
		$codigo_commodity = "$codigo.$nrodetalles";
		$detalle = split(";", $detalles);
		foreach ($detalle as $registro) {
			if ($codigo_commodity == $registro) die("¡No se puede insertar dos veces el mismo $tabla en una misma orden!");
		}
		
		$sql = "SELECT * FROM lg_commoditysub WHERE Codigo = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
		
		$codigo = $codigo_commodity;
	}
		
	//	si no se encontraron errores inserta en la tabla los datos del proveedor
	echo "||";
	?>
    <td align="center"><?=$nrodetalles?><input type="checkbox" name="chkdetalles" id="chk_<?=$codigo?>" title="<?=$codigo?>" style="display:none;" /></td>
    <td align="center"><?=$codigo_mostrar?></td>
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
    <td><input type="text" name="txtdocreferencia" style="width:98%; text-align:center;" disabled /></td>
    <td align="right">0,00</td>
    <td align="right">0,00</td>
    <td align="center"></td>
	<?
	echo "||$codigo";
}
	
//	--------------------------
elseif ($accion == "insertarItemTransaccion") {
	connect();
	
	if ($flagmanual == "true") $flagmanual = ""; else $flagmanual = "disabled";
	
	$detalle = split(";", $detalles);
	foreach ($detalle as $registro) {
		if ($codigo == $registro) die("¡No se puede insertar dos veces el mismo item!");
	}
	
	//	si nosencontraron errores inserta en la tabla los datos del proveedor
	$sql = "SELECT 
				i.CodItem,
				i.Descripcion,
				i.CodUnidad,
				iai.StockActual
			FROM 
				lg_itemmast i
				LEFT JOIN lg_itemalmaceninv iai ON (i.CodItem = iai.CodItem AND iai.CodAlmacen = '".$almacen."')
			WHERE 
				i.CodItem = '".$codigo."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	
	echo "||";
	?>
    <td align="center"><?=$nrodetalles?><input type="checkbox" name="chkdetalles" id="chk_<?=$field['CodItem']?>" title="<?=$field['CodItem']?>" style="display:none;" /></td>
    <td align="center"><?=$field['CodItem']?></td>
    <td><?=htmlentities($field['Descripcion'])?></td>
    <td align="center"><input type="hidden" name="txtcodunidad" value="<?=$field['CodUnidad']?>" /><?=$field['CodUnidad']?></td>
    <td><input type="text" name="txtstock" id="txtstock_<?=$field['CodItem']?>" value="<?=number_format($field['StockActual'], 2, ',', '.')?>" style="width:98%; text-align:right;" disabled="disabled" /></td>
    <td>
        <input type="text" name="txtcantidad" id="txtcantidad_<?=$field['CodItem']?>" value="0,00" style="width:98%; text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" onchange="setTotalTransaccion('<?=$field['CodItem']?>');" />
    </td>
    <td>
        <input type="text" name="txtpreciounit" id="txtpreciounit_<?=$field['CodItem']?>" value="0,00" style="width:98%; text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" onchange="setTotalTransaccion('<?=$field['CodItem']?>');" <?=$flagmanual?> />
    </td>
    <td align="right"><span id="total_<?=$field['CodItem']?>">0,00</span></td>
    <td><input type="text" name="txtdoc" id="txtdoc_<?=$field['CodItem']?>" style="width:98%; text-align:center;" disabled="disabled" /></td>
	<?
}

//	--------------------------
elseif ($accion == "verDetallesOrdenCompra") {
	connect();
	
	$sql = "SELECT 
				ocd.*,
				iai.StockActual
			FROM 
				lg_ordencompradetalle ocd
				INNER JOIN lg_ordencompra oc ON (ocd.CodOrganismo = oc.CodOrganismo AND ocd.NroOrden = oc.NroOrden)
				LEFT JOIN lg_itemalmaceninv iai ON (ocd.CodItem = iai.CodItem AND oc.CodAlmacen = iai.CodAlmacen)
			WHERE 
				ocd.CodOrganismo = '".$codorganismo."' AND 
				ocd.NroOrden = '".$nroorden."' AND 
				ocd.Estado = 'PE'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {
		$pendiente = $field['CantidadPedida'] - $field['CantidadRecibida'];
		?>
		<tr class="trListaBody" onclick="mClkMulti(this);" id="row_<?=$field['Secuencia']?>">
			<td align="center"><?=$field['Secuencia']?></td>
			<td align="center">
            	<input type="checkbox" name="detalle" id="<?=$field['Secuencia']?>" value="<?=$field['Secuencia']?>" style="display:none;" />
				<?=$field['CodItem']?>
			</td>
			<td><?=htmlentities($field['Descripcion'])?></td>
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

//	--------------------------
elseif ($accion == "verDetallesOrdenCompraCommodity") {
	connect();
	
	$sql = "SELECT ocd.*
			FROM lg_ordencompradetalle ocd
			WHERE 
				ocd.CodOrganismo = '".$codorganismo."' AND 
				ocd.NroOrden = '".$nroorden."' AND 
				ocd.Estado = 'PE'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {
		$pendiente = $field['CantidadPedida'] - $field['CantidadRecibida'];
		?>
		<tr class="trListaBody" onclick="mClkMulti(this);" id="row_<?=$field['Secuencia']?>">
			<td align="center"><?=$field['Secuencia']?></td>
			<td align="center">
            	<input type="checkbox" name="detalle" id="<?=$field['Secuencia']?>" value="<?=$field['Secuencia']?>" style="display:none;" />
				<?=$field['CommoditySub']?>
			</td>
			<td><?=htmlentities($field['Descripcion'])?></td>
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

//	--------------------------
elseif ($accion == "insertarItemAlmacenRecepcion") {
	connect();
	
	$detalle = split(";", $detalles);
	foreach ($detalle as $registro) {
		if ($codigo == $registro) die("¡No se puede insertar dos veces el mismo item!");
	}
	
	//	si nosencontraron errores inserta en la tabla los datos
	echo "||";
	$sql = "SELECT * FROM lg_itemmast WHERE CodItem = '".$codigo."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	
	?>
		<td align="center"><?=$nrodetalles?><input type="checkbox" name="chkdetalles" id="chk_<?=$field['CodItem']?>" title="<?=$field['CodItem']?>" style="display:none;" /></td>
		<td align="center"><?=$field['CodItem']?></td>
		<td><?=htmlentities($field['Descripcion'])?></td>
		<td align="center"><input type="hidden" name="txtcodunidad" value="<?=$field['CodUnidad']?>" /><?=$field['CodUnidad']?></td>
        <td><input type="text" name="txtstock" id="txtstock<?=$field['CodItem']?>" value="0,00" style="width:98%; text-align:right;" disabled="disabled" /></td>
        <td><input type="text" name="txtcantidad" id="txtcantidad_<?=$field['CodItem']?>" value="0,00" style="width:98%; text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
        <td><input type="text" name="txtdoc" id="txtdoc<?=$field['CodItem']?>" disabled="disabled" /></td>
	
	<?
}
	
//	--------------------------
elseif ($accion == "insertarCommodityTransaccionEspecial") {
	connect();
	
	if ($flagmanual == "true") $flagmanual = ""; else $flagmanual = "disabled";
	
	$detalle = split(";", $detalles);
	foreach ($detalle as $registro) {
		if ($codigo == $registro) die("¡No se puede insertar dos veces el mismo item!");
	}
	
	//	si nosencontraron errores inserta en la tabla los datos del proveedor
	$sql = "SELECT * FROM lg_commoditysub WHERE Codigo = '".$codigo."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	
	echo "||";
	?>
    <td align="center"><?=$nrodetalles?><input type="checkbox" name="chkdetalles" id="chk_<?=$field['Codigo']?>" title="<?=$field['Codigo']?>" style="display:none;" /></td>
    <td align="center"><?=$field['Codigo']?></td>
    <td><?=htmlentities($field['Descripcion'])?></td>
    <td align="center"><input type="hidden" name="txtcodunidad" value="<?=$field['CodUnidad']?>" /><?=$field['CodUnidad']?></td>
    <td><input type="text" name="txtstock" id="txtstock_<?=$field['Codigo']?>" value="<?=number_format($field['StockActual'], 2, ',', '.')?>" style="width:98%; text-align:right;" disabled="disabled" /></td>
    <td>
        <input type="text" name="txtcantidad" id="txtcantidad_<?=$field['Codigo']?>" value="0,00" style="width:98%; text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" onchange="setTotalTransaccion('<?=$field['Codigo']?>'); setLineasActivos();" />
    </td>
    <td>
        <input type="text" name="txtpreciounit" id="txtpreciounit_<?=$field['Codigo']?>" value="0,00" style="width:98%; text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" onchange="setTotalTransaccion('<?=$field['Codigo']?>');" <?=$flagmanual?> />
    </td>
    <td align="right"><span id="total_<?=$field['Codigo']?>">0,00</span></td>
    <td><input type="text" name="txtdoc" id="txtdoc_<?=$field['Codigo']?>" style="width:98%; text-align:center;" disabled="disabled" /></td>
	<?
}
	
//	--------------------------
elseif ($accion == "setLineasActivos") {
	connect();
	
	$nrodetalles = 1;
	$detalle = split(";", $detalles);
	foreach ($detalle as $registro) {
		list($coditem, $cantidad, $preciounit, $reforden) = SPLIT( '[|]', $registro);
		
		$sql = "SELECT Descripcion, CodClasificacion
				FROM lg_commoditysub
				WHERE Codigo = '".$coditem."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
		
		$idactivogroup = "grupo_".$coditem;
		?>
        <tr class="trListaBody2" id="<?=$idactivogroup?>">
            <td align="center"><?=$nrodetalles?></td>
            <td colspan="13"><?=htmlentities($field['Descripcion'])?></td>
		</tr>
		<?
		
		for ($j=1; $j<=$cantidad; $j++) {
			$idactivo = $j."_".$coditem;
			?>
        	<tr class="trListaBody" onclick="mClk(this, 'seldetalleactivo', 'chk_<?=$idactivo?>');" id="<?=$idactivo?>">
            	<td align="center">
					<?=$j?>
                    <input type="checkbox" name="chkdetallesactivo" id="chk_<?=$idactivo?>" title="<?=$idactivo?>" style="display:none;" <?=$cactivo?> checked="checked" />
                    <input type="hidden" name="reforden" value="<?=$reforden?>" />
                    <input type="hidden" name="descripcion" value="<?=$field['Descripcion']?>" />
                    <input type="hidden" name="clasificacion" value="<?=$field['CodClasificacion']?>" />
                    <input type="hidden" name="preciounit" value="<?=$preciounit?>" />
				</td>
                <td align="center"><input type="text" name="nroserie" style="width:98%; text-align:center;" <?=$disabled?> id="nroserie_<?=$idactivo?>" maxlength="10" /></td>
                <td align="center"><input type="text" name="fingreso" value="<?=date("d-m-Y");?>" style="width:98%; text-align:center;" <?=$disabled?> id="fingreso_<?=$idactivo?>" maxlength="10" /></td>
                <td align="center"><input type="text" name="modelo" style="width:98%;" <?=$disabled?> id="modelo_<?=$idactivo?>" maxlength="50" /></td>
                <td align="center"><input type="text" name="codbarra" style="width:98%;" <?=$disabled?> id="codbarra_<?=$idactivo?>" maxlength="25" /></td>
                <td align="center">
                	<input type="hidden" name="txtnomubicacion" id="txtnomubicacion_<?=$idactivo?>" <?=$disabled?> />
                	<input type="text" name="txtubicacion" id="txtubicacion_<?=$idactivo?>" style="width:98%; text-align:center" disabled="disabled" value="<?=$codubicacion?>" />
                </td>
                <td align="center">
                	<input type="hidden" name="txtnomccostos" id="txtnomccostos_<?=$idactivo?>" />
                	<input type="text" name="txtccostos" id="txtccostos_<?=$idactivo?>" style="width:98%; text-align:center" disabled="disabled" value="<?=$ccosto?>" />
                </td>
                <td align="center"><input type="text" name="nroplaca" style="width:98%;" <?=$disabled?> id="nroplaca_<?=$idactivo?>" maxlength="15" /></td>
                <td align="center">
                	<select name="marca" style="width:98%;" <?=$disabled?> id="marca_<?=$idactivo?>">
                    	<option value=""></option>
                    	<?=loadSelect("lg_marcas", "CodMarca", "Descripcion", "", 0);?>
                    </select>
                </td>
                <td align="center">
                	<select name="color" style="width:98%;" <?=$disabled?> id="color_<?=$idactivo?>">
                    	<option value=""></option>
                    	<?=getMiscelaneos("", "COLOR", 0);?>
                    </select>
                </td>
                <td align="center"><input type="checkbox" name="flagfactura" id="flagfactura_<?=$idactivo?>" /></td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">Pendiente</td>
            </tr>
            <?
		}
	}
}

//	--------------------------
elseif ($accion == "valSeleccionInvitacion") {
	connect();
	
	$detalle = split(";", $detalles);	$i=0;
	foreach ($detalle as $registro) {
		list($codorganismo, $codrequerimiento, $secuencia) = SPLIT( '[|]', $registro);
		
		$sql = "SELECT Numero
				FROM lg_cotizacion
				WHERE
					CodOrganismo = '".$codorganismo."' AND
					CodRequerimiento = '".$codrequerimiento."' AND
					Secuencia = '".$secuencia."'
				GROUP BY Numero";
		$query = mysql_query($sql) or die ($sql.mysql_error());	//echo "$sql\n";
		if (mysql_num_rows($query) == 1) {
			$field = mysql_fetch_array($query);
			$numero[$i] = $field[0];
			$j = $i-1;
			if ($j >= 0 && $numero[$i] != $numero[$j]) die("¡ERROR: Debe seleccionar requerimientos de la misma invitación!");
			$i++;
		} 
		elseif (mysql_num_rows($query) == 0) die("¡ERROR: No puede seleccionar requerimientos que no tengan invitaciones!");
		else die("¡ERROR: Debe seleccionar requerimientos de la misma invitación!");
	}
}
?>