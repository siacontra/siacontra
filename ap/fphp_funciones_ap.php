<?php 
include ("fphp_ap.php");
//	--------------------------
if ($accion == "verDocumentoDetalles") {
	connect();
	
	$documentos = split(";", $detalles);
	foreach ($documentos as $documento) {
		list($anio, $codorganismo, $codproveedor, $clasificacion, $referencia)=SPLIT( '[ ]', $documento);
		
		//	CONSULTO LA TABLA
		$sql = "SELECT * 
				FROM ap_documentosdetalle 
				WHERE 
					Anio = '".$anio."' AND
					CodProveedor = '".$codproveedor."' AND
					DocumentoClasificacion = '".$clasificacion."' AND
					DocumentoReferencia = '".$referencia."'
				ORDER BY Secuencia";
		$query_det = mysql_query($sql) or die ($sql.mysql_error());
		$rows_det = mysql_num_rows($query_det); $suma_rows_det += $rows_det;
		//	MUESTRO LA TABLA
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
	?>
	<input type="hidden" name="rows_detalle" id="rows_detalle" value="Registros: <?=$suma_rows_det?>" />
	<?
}

//	--------------------------
elseif ($accion == "insertarTipoPagoDisponible") {
	connect();
	?>
    <td width="50%" align="center">
    	<select name="tpago" style="width:95%">
        	<option value=""></option>
            <?=loadSelect("masttipopago", "CodTipoPago", "TipoPago", "", 0);?>
        </select>
    </td>
    <td align="right">0,00</td>
    <?
}

//	--------------------------
elseif ($accion == "insertarImpuesto") {
	connect();	
	echo "||";
	?>
    <td align="center">
    	<input type="hidden" name="nroimpuesto" value="<?=$canimpuesto?>" />
    	<select name="codimpuesto" style="width:95%" onchange="getFactorPorcentaje(this.value, <?=$canimpuesto?>);">
        	<option value=""></option>
            <?=loadSelectImpuesto("", "R");?>
        </select>
    	<input type="hidden" name="signo" id="signo_<?=$canimpuesto?>" />
    	<input type="hidden" name="imponible" id="imponible_<?=$canimpuesto?>" />
    </td>
    <td align="center"><input type="text" name="afecto" id="afecto_<?=$canimpuesto?>" value="0,00" onblur="numeroBlur(this); calcularTotalImpuesto('<?=$canimpuesto?>');" onfocus="numeroFocus(this);" style="width:95%; text-align:right;" disabled="disabled" /></td>
    <td align="right"><input type="text" name="factor" id="factor_<?=$canimpuesto?>" value="0,00" style="width:95%; text-align:right;" disabled="disabled" /></td>
    <td align="right"><span id="monto_<?=$canimpuesto?>">0,00</span></td>
    <?
}

//	--------------------------
elseif ($accion == "getFactorPorcentaje") {
	connect();
	
	$sql = "SELECT FactorPorcentaje, FlagImponible, Signo FROM mastimpuestos WHERE CodImpuesto = '".$codimpuesto."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	echo "|".$field[0]."|".$field[1]."|".$field[2];
}

//	--------------------------
elseif ($accion == "insertarDocumento") {
	connect();	
	list($codorganismo, $codproveedor, $tipodoc, $nroorden, $documento_clasificacion, $documento_referencia)=SPLIT( '[|]', $documento);
	$orden = "$tipodoc-$nroorden";
	
	if ($tipodoc == "OC") {
		$clasificacion = "O.Compra";		
		$sql = "SELECT
					oc.FechaPreparacion,
					oc.MontoAfecto,
					oc.MontoNoAfecto,
					oc.MontoBruto,
					oc.MontoIGV,
					oc.MontoTotal,
					oc.Observaciones,
					oc.cod_partida,
					oc.CodProveedor,
					d.DocumentoReferencia,
					i.FactorPorcentaje
				FROM
					lg_ordencompra oc
					LEFT JOIN ap_documentos d ON (d.CodOrganismo = oc.CodOrganismo AND
												  d.ReferenciaNroDocumento = oc.NroOrden AND
												  d.ReferenciaTipoDocumento = 'OC' AND
												  d.CodProveedor = '".$codproveedor."' AND
												  d.DocumentoClasificacion = '".$documento_clasificacion."' AND
												  d.DocumentoReferencia = '".$documento_referencia."')
					INNER JOIN mastproveedores p ON (oc.CodProveedor = p.CodProveedor)
					INNER JOIN masttiposervicioimpuesto tsi ON (p.CodTipoServicio = tsi.CodTipoServicio)
					INNER JOIN mastimpuestos i ON (tsi.CodImpuesto = i.CodImpuesto)
				WHERE 
					oc.CodOrganismo = '".$codorganismo."' AND
					oc.NroOrden = '".$nroorden."'";
	}
	elseif ($tipodoc == "OS") {
		$clasificacion = "O.Servicio";
		$sql = "SELECT 
					os.FechaPreparacion,
					os.MontoOriginal AS MontoAfecto,
					'0.00' AS MontoNoAfecto,
					os.MontoOriginal AS MontoBruto,
					os.MontoIva AS MontoIGV,
					os.TotalMontoIva AS MontoTotal,
					os.Descripcion AS Observaciones,
					os.cod_partida,
					os.CodProveedor,
					d.DocumentoReferencia,
					i.FactorPorcentaje
				FROM 
					lg_ordenservicio os
					LEFT JOIN ap_documentos d ON (os.NroOrden = d.ReferenciaNroDocumento AND d.ReferenciaTipoDocumento = 'OS')
					INNER JOIN mastproveedores p ON (os.CodProveedor = p.CodProveedor)
					INNER JOIN masttiposervicioimpuesto tsi ON (p.CodTipoServicio = tsi.CodTipoServicio)
					INNER JOIN mastimpuestos i ON (tsi.CodImpuesto = i.CodImpuesto)
				WHERE 
					os.CodOrganismo = '".$codorganismo."' AND
					os.NroOrden = '".$nroorden."'";
	}
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	
	$porcentaje_monto = ($monto * 100 / $field['MontoTotal']) / 100;
	$monto_afecto = $field['MontoAfecto'] * $porcentaje_monto;
	$monto_noafecto = $field['MontoNoAfecto'] * $porcentaje_monto;
	$monto_bruto = $monto_afecto + $monto_noafecto;
	if ($monto_afecto > 0) $monto_impuesto = $monto_bruto * $field['FactorPorcentaje'] / 100; else $monto_impuesto = 0;
	$monto_total = $monto_bruto + $monto_impuesto;
	
	echo "||";
	?>
    <!--	Documentos Pendientes	 -->
    <td align="center">
		<?=$clasificacion?>
        <input type="hidden" name="documento" value="<?=$orden?>" />
        <input type="hidden" name="doc_clasificacion" value="<?=$documento_clasificacion?>" />
        <input type="hidden" name="doc_referencia" value="<?=$documento_referencia?>" />
		<input type="hidden" name="monto" value="<?=$monto_total?>" />
		<input type="hidden" name="afecto" value="<?=$monto_afecto?>" />
		<input type="hidden" name="noafecto" value="<?=$monto_noafecto?>" />
		<input type="hidden" name="impuesto" value="<?=$monto_impuesto?>" />
		<input type="hidden" name="porcentaje_monto" value="<?=$porcentaje_monto?>" />
	</td>
    <td align="center"><?=$documento_referencia?></td>
    <td align="center"><?=formatFechaDMA($field['FechaPreparacion'])?></td>
    <td align="center"><?=$orden?></td>
    <td align="right"><?=number_format($monto_total, 2, ',', '.')?></td>
    <td align="right"><?=number_format($monto_afecto, 2, ',', '.')?></td>
    <td align="right"><?=number_format($monto_impuesto, 2, ',', '.')?></td>
    <td align="right"><?=number_format($monto_noafecto, 2, ',', '.')?></td>
    <td><?=($field['Observaciones'])?></td>
    <?
	
	echo "||";
	
	//	verifico si la orden tiene activos fijos
	$sql = "SELECT ocd.*
			FROM 
				lg_ordencompradetalle ocd
				INNER JOIN lg_commoditysub cs ON (ocd.CommoditySub = cs.Codigo)
				INNER JOIN lg_commoditymast cm ON (cs.CommodityMast = cm.CommodityMast)
			WHERE 
				cm.Clasificacion = 'BIM' AND
				ocd.CodOrganismo = '".$codorganismo."' AND
				ocd.NroOrden = '".$nroorden."'";
	$query_comm = mysql_query($sql) or die ($sql.mysql_error());
	$rows_comm = mysql_num_rows($query_comm);
	
	//	si es una orden de compra y no tiene activos fijos
	if ($tipodoc == "OC" && $rows_comm == 0) {
		$sql = "SELECT 
					doc.*,
					p.denominacion,
					pc.Descripcion AS NomCuenta,
					'OC' AS TipoOrden
				FROM 
					lg_distribucionoc doc
					INNER JOIN pv_partida p ON (doc.cod_partida = p.cod_partida)
					LEFT JOIN ac_mastplancuenta pc ON (doc.CodCuenta = pc.CodCuenta)
				WHERE
					doc.CodOrganismo = '".$codorganismo."' AND
					doc.NroOrden = '".$nroorden."' AND
					doc.cod_partida <> '".$field['cod_partida']."'
				GROUP BY cod_partida
				ORDER BY Secuencia";
		$query_dis = mysql_query($sql) or die ($sql.mysql_error());	$partida=0;	$can=0;
		while ($field_dis = mysql_fetch_array($query_dis)) {	$can++;
			$monto_partida = $field_dis['Monto'] * $porcentaje_monto;			
			?>
			<!--	Distribucion	 -->
            <tr class="trListaBody">
                <td align="center" width="85"><input type="text" name="codpartida" id="codpartida_<?=$can?>" value="<?=$field_dis['cod_partida']?>" style="width:98%; text-align:center;" readonly="readonly" /></td>
                <td><input type="text" name="nompartida" id="nompartida_<?=$can?>" value="<?=($field_dis['denominacion'])?>" style="width:98%;" readonly="readonly" /></td>
                <td align="center">
                    <input type="text" name="codcuenta" id="codcuenta_<?=$can?>" value="<?=$field_dis['CodCuenta']?>" title="<?=($field_dis['NomCuenta'])?>" style="width:98%; text-align:center;" readonly="readonly" />
                    <input type="hidden" name="nomcuenta" id="nomcuenta_<?=$can?>" value="<?=($field_dis['NomCuenta'])?>" />
                </td>
                <td align="center">
                    <input type="text" name="ccosto" id="ccosto_<?=$can?>" value="<?=$field_dis['CodCentroCosto']?>" title="<?=$field_dis['NomCentroCosto']?>" style="width:98%; text-align:center;" disabled="disabled" />
                    <input type="hidden" name="nomccosto" id="nomccosto_<?=$can?>" value="<?=$field_dis['NomCentroCosto']?>" />
                </td>
			    <td align="center"><input type="checkbox" name="flagnoafecto" style="width:96%;" disabled="disabled" /></td>
                <td align="center"><input type="text" name="monto" value="<?=number_format($monto_partida, 2, ',', '.')?>" style="width:98%; text-align:right;" onfocus="numeroFocus(this);" onblur="numeroBlur(this);" /></td>
                <td align="center">
                    <input type="hidden" name="nroorden" value="<?=$field_dis['TipoOrden']?>-<?=$field_dis['NroOrden']?>" />
                    <?=$field_dis['TipoOrden']?>-<?=$field_dis['NroOrden']?>
                </td>
                <td align="center"><input type="text" name="referencia" value="<?=$field_dis['Referencia']?>" style="width:96%;" /></td>
                <td align="center"><input type="text" name="descripcion" value="<?=($field_dis['denominacion'])?>" style="width:98%;" /></td>
                <td align="center">
                    <input type="text" name="codpersona" id="codpersona_<?=$can?>" value="<?=$field['CodProveedor']?>" title="<?=$field['NomProveedor']?>" style="width:98%; text-align:center;" readonly="readonly" />
                    <input type="hidden" name="nompersona" id="nompersona_<?=$can?>" value="<?=$field['NomProveedor']?>" />
                </td>
                <td align="center"><input type="text" name="nroactivo" id="nroactivo_<?=$can?>" value="<?=$field_dis['NroActivo']?>" style="width:98%; text-align:center;" readonly="readonly" /></td>
                <td align="center"><input type="checkbox" name="flagdiferido" style="width:96%;" <?=$flagdiferido?> /></td>
            </tr>
			<?
			$partida += $monto_partida;
		}
	}
	//	si es una orden de servicio o si tiene activos fijos
	elseif ($tipodoc == "OS" || $rows_comm != 0) {
		//	si es una orden de servicio
		if ($tipodoc == "OS")
			$sql = "SELECT 
						osd.*,
						p.denominacion,
						pc.Descripcion AS NomCuenta,
						'OS' AS TipoOrden
					FROM 
						lg_ordenserviciodetalle osd
						LEFT JOIN pv_partida p ON (osd.cod_partida = p.cod_partida)
						LEFT JOIN ac_mastplancuenta pc ON (osd.CodCuenta = pc.CodCuenta)
					WHERE 
						osd.CodOrganismo = '".$codorganismo."' AND
						osd.NroOrden = '".$nroorden."'
					ORDER BY Secuencia";
		//	si tiene activos fijos
		else
			$sql = "SELECT 
						ocd.*,
						p.denominacion,
						pc.Descripcion AS NomCuenta,
						'OC' AS TipoOrden
					FROM 
						lg_ordencompradetalle ocd
						LEFT JOIN pv_partida p ON (ocd.cod_partida = p.cod_partida)
						LEFT JOIN ac_mastplancuenta pc ON (ocd.CodCuenta = pc.CodCuenta)
					WHERE 
						ocd.CodOrganismo = '".$codorganismo."' AND
						ocd.NroOrden = '".$nroorden."'
					ORDER BY Secuencia";			
		$query_dis = mysql_query($sql) or die ($sql.mysql_error());	$partida=0;	$can=0;
		while ($field_dis = mysql_fetch_array($query_dis)) {	$can++;
			for ($i=1; $i<=$field_dis['CantidadPedida']; $i++) {
				?>
				<!--	Distribucion	 -->
                <tr class="trListaBody">
                    <td align="center" width="85"><input type="text" name="codpartida" id="codpartida_<?=$can?>" value="<?=$field_dis['cod_partida']?>" style="width:98%; text-align:center;" readonly="readonly" /></td>
                    <td><input type="text" name="nompartida" id="nompartida_<?=$can?>" value="<?=($field_dis['denominacion'])?>" style="width:98%;" readonly="readonly" /></td>
                    <td align="center">
                        <input type="text" name="codcuenta" id="codcuenta_<?=$can?>" value="<?=$field_dis['CodCuenta']?>" title="<?=($field_dis['NomCuenta'])?>" style="width:98%; text-align:center;" readonly="readonly" />
                        <input type="hidden" name="nomcuenta" id="nomcuenta_<?=$can?>" value="<?=($field_dis['NomCuenta'])?>" />
                    </td>
                    <td align="center">
                        <input type="text" name="ccosto" id="ccosto_<?=$can?>" value="<?=$field_dis['CodCentroCosto']?>" title="<?=$field_dis['NomCentroCosto']?>" style="width:98%; text-align:center;" disabled="disabled" />
                        <input type="hidden" name="nomccosto" id="nomccosto_<?=$can?>" value="<?=$field_dis['NomCentroCosto']?>" />
                    </td>
				    <td align="center"><input type="checkbox" name="flagnoafecto" style="width:96%;" disabled="disabled" /></td>
                    <td align="center"><input type="text" name="monto" value="<?=number_format($field_dis['PrecioUnit'], 2, ',', '.')?>" style="width:98%; text-align:right;" onfocus="numeroFocus(this);" onblur="numeroBlur(this);" /></td>
                    <td align="center">
                        <input type="hidden" name="nroorden" value="<?=$field_dis['TipoOrden']?>-<?=$field_dis['NroOrden']?>" />
                        <?=$field_dis['TipoOrden']?>-<?=$field_dis['NroOrden']?>
                    </td>
                    <td align="center"><input type="text" name="referencia" value="<?=$field_dis['Referencia']?>" style="width:96%;" /></td>
                    <td align="center"><input type="text" name="descripcion" value="<?=($field_dis['denominacion'])?>" style="width:98%;" /></td>
                    <td align="center">
                        <input type="text" name="codpersona" id="codpersona_<?=$can?>" value="<?=$field['CodProveedor']?>" title="<?=$field['NomProveedor']?>" style="width:98%; text-align:center;" readonly="readonly" />
                        <input type="hidden" name="nompersona" id="nompersona_<?=$can?>" value="<?=$field['NomProveedor']?>" />
                    </td>
                    <td align="center"><input type="text" name="nroactivo" id="nroactivo_<?=$can?>" value="<?=$field_dis['NroActivo']?>" style="width:98%; text-align:center;" readonly="readonly" /></td>
                    <td align="center"><input type="checkbox" name="flagdiferido" style="width:96%;" <?=$flagdiferido?> /></td>
                </tr>
				<?
				$partida += $field_dis['PrecioUnit'];
			}
		}
	}
	echo "||$monto||$monto_afecto||$monto_impuesto||$monto_noafecto||$partida";
}

//	--------------------------
elseif ($accion == "insertarObligacionCuenta") {
	connect();	
	
	$sql = "SELECT i.FactorPorcentaje
			FROM
				mastproveedores p
				INNER JOIN masttiposervicioimpuesto tsi ON (p.CodTipoServicio = tsi.CodTipoServicio)
				INNER JOIN mastimpuestos i ON (tsi.CodImpuesto = i.CodImpuesto)
			WHERE
				p.CodProveedor = '".$codproveedor."' AND
				i.CodRegimenFiscal = 'I'
			LIMIT 0, 1";
	$query_igv_proveedor = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query_igv_proveedor) == 0) { $flagnoafecto = "checked"; $dnoafecto = "disabled"; }
	
	$sql = "SELECT i.FactorPorcentaje
			FROM mastimpuestos i
			WHERE i.CodImpuesto = '".getParametro('IGVCODIGO')."'";
	$query = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	?>
    <!--	Distribucion	 -->
    <td align="center" width="85">
    	<input type="text" name="codpartida" id="codpartida_<?=$can?>" style="width:98%; text-align:center;" onchange="getDescripcionLista('accion=getPartidaVsCuenta', this, 'nompartida_<?=$can?>', 'codcuenta_<?=$can?>', 'nomcuenta_<?=$can?>');" />
    </td>
    <td><input type="text" name="nompartida" id="nompartida_<?=$can?>" style="width:98%;" readonly="readonly" /></td>
    <td align="center">
    	<input type="text" name="codcuenta" id="codcuenta_<?=$can?>" style="width:98%; text-align:center;" onchange="getDescripcionLista('accion=getDescripcionCuenta', this, 'nomcuenta_<?=$can?>');" />
    	<input type="hidden" name="nomcuenta" id="nomcuenta_<?=$can?>" />
	</td>
    <td align="center">
    	<input type="text" name="ccosto" id="ccosto_<?=$can?>" style="width:98%; text-align:center;" value="<?=$codccosto?>" onchange="getDescripcionLista('accion=getDescripcionCCosto', this, 'nomccosto_<?=$can?>');" />
    	<input type="hidden" name="nomccosto" id="nomccosto_<?=$can?>" />
	</td>
    <td align="center"><input type="checkbox" name="flagnoafecto" style="width:96%;" value="<?=$field['FactorPorcentaje']?>" onclick="setTotalDistribucionObligacion();" <?=$flagnoafecto?> <?=$dnoafecto?> /></td>
    <td align="center"><input type="text" name="monto" style="width:98%; text-align:right;" onchange="setTotalDistribucionObligacion();" onfocus="numeroFocus(this);" onblur="numeroBlur(this);" /></td>
    <td align="center">
    	<input type="text" name="nroorden" style="width:98%;" />
    </td>
    <td align="center"><input type="text" name="referencia" style="width:96%;" /></td>
    <td align="center"><input type="text" name="descripcion" style="width:98%;" /></td>
    <td align="center">
    	<input type="text" name="codpersona" id="codpersona_<?=$can?>" value="<?=$codproveedor?>" maxlength="6" style="width:98%; text-align:center;" onchange="getDescripcionLista('accion=getDescripcionPersona&flagproveedor=S&flagempleado=S&flagotros=S', this, 'nompersona_<?=$can?>');" />
    	<input type="hidden" name="nompersona" id="nompersona_<?=$can?>" />
	</td>
    <td align="center"><input type="text" name="nroactivo" id="nroactivo_<?=$can?>" style="width:98%; text-align:center;" readonly="readonly" /></td>
    <td align="center"><input type="checkbox" name="flagdiferido" style="width:96%;" /></td>
    <?
}

//	--------------------------
elseif ($accion == "getCuentaBancariaDefault") {
	connect();	
	echo getCuentaBancariaDefault($codorganismo, $codtipopago);
}

//	--------------------------
elseif ($accion == "tservicio_obligacion") {
	connect();
	
	$canimpuesto = 0;
	$monto = 0;
	$total_retenciones = 0;
	//	imprimo el listado de retenciones y otros impuestos por defecto del proveedor
	$sql = "SELECT 
				tsi.CodImpuesto,
				i.Signo,
				i.FlagImponible,
				i.FactorPorcentaje
			FROM 
				masttiposervicioimpuesto tsi
				INNER JOIN mastimpuestos i ON (tsi.CodImpuesto = i.CodImpuesto)
			WHERE 
				tsi.CodTipoServicio = '".$codtiposervicio."' AND
				i.CodRegimenfiscal = 'R'";
	$query_impuestos = mysql_query($sql) or die($sql.mysql_error());
	while($field_impuestos = mysql_fetch_array($query_impuestos)) {	$canimpuesto++;
		if ($field_impuestos['FlagImponible'] == "N") $afecto = $monto_afecto;
		elseif ($field_impuestos['FlagImponible'] == "I") $afecto = $monto_impuesto;		
		$monto = $afecto * $field_impuestos['FactorPorcentaje'] / 100;
		$total_retenciones += $monto;
	
		?>
		<tr class="trListaBody" id="imp_<?=$canimpuesto?>" onclick="mClk(this, 'selimpuesto');">
			<td align="center">
				<input type="hidden" name="nroimpuesto" value="<?=$canimpuesto?>" />
				<select name="codimpuesto" style="width:95%" onchange="getFactorPorcentaje(this.value, <?=$canimpuesto?>);">
					<option value=""></option>
					<?=loadSelectImpuesto($field_impuestos['CodImpuesto'], "R");?>
				</select>
				<input type="hidden" name="signo" id="signo_<?=$canimpuesto?>" value="<?=$field_impuestos['Signo']?>" />
				<input type="hidden" name="imponible" id="imponible_<?=$canimpuesto?>" value="<?=$field_impuestos['FlagImponible']?>" />
			</td>
			<td align="center"><input type="text" name="afecto" id="afecto_<?=$canimpuesto?>" value="<?=number_format($afecto, 2, ',', '.')?>" onblur="numeroBlur(this); calcularTotalImpuesto('<?=$canimpuesto?>');" onfocus="numeroFocus(this);" style="width:95%; text-align:right;" /></td>
			<td align="right"><input type="text" name="factor" id="factor_<?=$canimpuesto?>" value="<?=number_format($field_impuestos['FactorPorcentaje'], 2, ',', '.')?>" style="width:95%; text-align:right;" disabled="disabled" /></td>
			<td align="right"><span id="monto_<?=$canimpuesto?>"><?=number_format($monto, 2, ',', '.')?></span></td>
		</tr>
		<?
	}	
	echo "||$canimpuesto||$total_retenciones";
}

//	--------------------------
elseif ($accion == "insertarLineaTransaccionBancaria") {
	connect();
	?>
    <td align="center">
		<?=$nrodetalles?>
        <input type="hidden" name="nrosecuencia" value="<?=$nrodetalles?>">
	</td>
    <td align="center"><input type="text" name="codtransaccion" id="codtransaccion<?=$nrodetalles?>" class="cell2" style="text-align:center;" readonly></td>
    <td align="center"><input type="text" name="nomtransaccion" id="nomtransaccion<?=$nrodetalles?>" class="cell2" readonly></td>
    <td align="center"><input type="text" name="tipotransaccion" id="tipotransaccion<?=$nrodetalles?>" class="cell2" style="text-align:center;" readonly></td>
    <td align="center">
    	<select name="nrocuenta" class="cell2">
        	<option value="">&nbsp;</option>
        	<?=loadSelect("ap_ctabancaria", "NroCuenta", "NroCuenta", "", 0);?>
        </select>
	</td>
    <td align="center"><input type="text" name="monto" id="monto<?=$nrodetalles?>" class="cell" style="text-align:right;" onBlur="numeroBlur(this); this.className='cell';" onFocus="numeroFocus(this); this.className='cellFocus';" value="0,00"></td>
    <td align="center">
    	<select name="tipodocumento" class="cell2">
        	<option value="">&nbsp;</option>
        	<?=loadSelect("ap_tipodocumento", "CodTipoDocumento", "Descripcion", "", 0);?>
        </select>
	</td>
    <td align="center"><input type="text" name="referenciabanco" id="referenciabanco<?=$nrodetalles?>" maxlength="20" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';"></td>
    <td align="center"><input type="text" name="persona" id="persona<?=$nrodetalles?>" class="cell2" style="text-align:center;" readonly></td>
    <td align="center"><input type="text" name="ccosto" id="ccosto<?=$nrodetalles?>" class="cell2" style="text-align:center;"></td>
    <td align="center">
    	<input type="text" name="cod_partida" id="cod_partida<?=$nrodetalles?>" class="cell2" style="text-align:center;">
    </td>
    <?
}

//	--------------------------
elseif ($accion == "verOrdenesPrepago") {
	connect();
	//	consulto las ordenes del prepago
	list($codproveedor, $nroproceso, $codtipopago, $nrocuenta) = split("[|]", $registro);
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
				p.CodProveedor = '".$codproveedor."' AND
				p.NroProceso = '".$nroproceso."' AND
				p.CodTipoPago = '".$codtipopago."' AND
				p.NroCuenta = '".$nrocuenta."'
			ORDER BY Secuencia";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	while ($field = mysql_fetch_array($query)) {
		?>
		<tr class="trListaBody">
			<td align="center"><?=$field['Secuencia']?></td>
			<td><?=($field['NomProveedorPagar'])?></td>
			<td><?=($field['NomProveedor'])?></td>
			<td align="right"><?=number_format($field['MontoPagar'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field['MontoRetenido'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field['MontoPago'], 2, ',', '.')?></td>
		</tr>
		<?
	}
}

//	--------------------------
elseif ($accion == "insertarClasificacionGasto") {
	connect();
	?>
    <td align="center"><?=$nrodetalles?></td>
    <td align="center">
    	<select name="clasificacion" style="width:99%">
        	<option value=""></option>
            <?=loadSelect("ap_clasificaciongastos", "CodClasificacion", "Descripcion", "", 0);?>
        </select>
    </td>
    <?
}

//	--------------------------
elseif ($accion == "insertarConceptoGasto") {
	connect();
	?>
    <td align="center"><?=$nrodetalles?></td>
    <td align="center">
    	<select name="concepto" style="width:99%">
        	<option value=""></option>
            <?=loadSelect("ap_conceptogastos", "CodConceptoGasto", "Descripcion", "", 0);?>
        </select>
    </td>
    <?
}

//	--------------------------
elseif ($accion == "selListadoCajaChica") {
	connect();
	//	-----------------------------------
	$sql = "SELECT
				p.NomCompleto,
				cca.Monto
			FROM
				mastpersonas p
				INNER JOIN mastempleado e ON (p.CodPersona = e.CodPersona)
				LEFT JOIN ap_cajachicaautorizacion cca ON (e.CodEmpleado = cca.CodEmpleado)
			WHERE
				p.CodPersona = '".$codpersona."' AND
				cca.CodOrganismo = '".$codorganismo."'";
	$query = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	$monto = number_format($field['Monto'], 2, ',', '.');
	echo "$field[NomCompleto]||$monto";
}

//	--------------------------
elseif ($accion == "insertarCajaChicaDetalle") {
	connect();
	?>
    <td align="center"><?=$candetalles?></td>
    <td align="center"><input type="text" name="fdocumento" value="<?=date("d-m-Y")?>" style="width:94%; text-align:center;" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" /></td>
    <td align="center">
    	<input type="hidden" name="concepto" id="concepto_<?=$candetalles?>" />
    	<input type="text" name="nomconcepto" id="nomconcepto_<?=$candetalles?>" style="width:99%;" class="cell" disabled="disabled" />
    </td>
    <td align="center"><textarea name="descripcion" style="width:99%; height:35px;" class="cell" onBlur="this.className='cell'; this.style.height='35px'" onFocus="this.className='cellFocus'; this.style.height='60px'"></textarea></td>
    <td align="center"><input type="text" name="pagado" id="pagado_<?=$candetalles?>" value="0,00" style="width:95%; text-align:right;" class="cell" onfocus="numeroFocus(this); this.className='cellFocus';" onblur="numeroBlur(this); this.className='cell';" onchange="setMontosCajaChica(<?=$candetalles?>, 'pagado');" /></td>
    <td align="center">
    	<select name="tipoimpuesto" id="tipoimpuesto_<?=$candetalles?>" style="width:100%;" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" onchange="setTipoServicioCajaChica(<?=$candetalles?>, this.value); limpiarMontosCajaChicaDetalle(<?=$candetalles?>);">
            <?=loadSelect("ap_regimenfiscal", "CodRegimenFiscal", "Descripcion", "I", 0);?>
        </select>
    </td>
    <td align="center">
    	<select name="tiposervicio" id="tiposervicio_<?=$candetalles?>" style="width:100%;" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" onchange="limpiarMontosCajaChicaDetalle(<?=$candetalles?>);">
        <?
		$sql = "SELECT * FROM masttiposervicio WHERE CodRegimenFiscal = 'I'";
		$query = mysql_query($sql) or die($sql.mysql_error());
		while ($field = mysql_fetch_array($query)) {
			?><option value="<?=$field['CodTipoServicio']?>"><?=$field['Descripcion']?></option><?
		}
		?></select>
    </td>
    <td align="center"><input type="text" name="afecto" id="afecto_<?=$candetalles?>" value="0,00" style="width:95%; text-align:right;" class="cell" onfocus="numeroFocus(this); this.className='cellFocus';" onblur="numeroBlur(this); this.className='cell';" onchange="setMontosCajaChica(<?=$candetalles?>, 'afecto');" /></td>
    <td align="center"><input type="text" name="noafecto" id="noafecto_<?=$candetalles?>" value="0,00" style="width:95%; text-align:right;" class="cell" onfocus="numeroFocus(this); this.className='cellFocus';" onblur="numeroBlur(this); this.className='cell';" onchange="setMontosCajaChica(<?=$candetalles?>, 'noafecto');" /></td>
    <td align="center"><input type="text" name="impuesto" id="impuesto_<?=$candetalles?>" value="0,00" style="width:95%; text-align:right;" class="cell" onfocus="numeroFocus(this); this.className='cellFocus';" onblur="numeroBlur(this); this.className='cell';" disabled="disabled" /></td>
    <td align="center"><input type="text" name="retencion" id="retencion_<?=$candetalles?>" value="0,00" style="width:95%; text-align:right;" class="cell" onfocus="numeroFocus(this); this.className='cellFocus';" onblur="numeroBlur(this); this.className='cell';" disabled="disabled" /></td>
    <td align="center"><input type="text" name="rif" id="rif_<?=$candetalles?>" maxlength="20" style="width:95%;" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" /></td>
    <td align="center" width="40">
    	<select name="tipodocumento" style="width:100%;" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';">
        	<option value=""></option>
            <?=loadSelect("ap_tipodocumento", "CodTipoDocumento", "Descripcion", "", 10);?>
        </select>
	</td>
    <td align="center" width="108"><input type="text" name="nrodocumento" style="width:95%;" maxlength="20" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" /></td>
    <td align="center"><input type="text" name="nrofactura" style="width:95%;" class="cell" maxlength="20" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" /></td>
    <td align="center">
    	<input type="hidden" name="codpersona" id="codpersona_<?=$candetalles?>" />
    	<input type="text" name="nompersona" id="nompersona_<?=$candetalles?>" style="width:99%;" maxlength="100" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" />
    	<input type="hidden" name="distribucion" id="distribucion_<?=$candetalles?>" value="" />
    </td>
    <?
}

//	--------------------------
elseif ($accion == "setTipoServicioCajaChica") {
	connect();
	?><select name="tiposervicio" id="tiposervicio_<?=$candetalles?>" style="width:100%;" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" onchange="limpiarMontosCajaChicaDetalle(<?=$candetalles?>);"><?		
	$sql = "SELECT * FROM masttiposervicio WHERE CodRegimenFiscal = '".$tipoimpuesto."'";
	$query = mysql_query($sql) or die($sql.mysql_error());
	while ($field = mysql_fetch_array($query)) {
		?><option value="<?=$field['CodTipoServicio']?>"><?=$field['Descripcion']?></option><?
	}
	?></select><?
}

//	--------------------------
elseif ($accion == "setMontosCajaChica") {
	connect();
	$defiva = getParametro("DEFTSERCC");
	if ($campo == "pagado") {
		if ($tipoimpuesto != "I" && $tipoimpuesto != "R") { $afecto = 0; $noafecto = $pagado; }
	} elseif ($campo == "afecto") $pagado = 0;
	$impuesto = 0;
	$retencion = 0;
	
	//	impuestos	(OJO NO PUEDEN HABER MAS DE DOS RESULTADOS EL QUERY SI NO DA ERROR)
	$sql = "SELECT
				i.FactorPorcentaje,
				i.Signo,
				i.CodRegimenFiscal,
				i.FlagImponible
			FROM
				mastimpuestos i
				INNER JOIN masttiposervicioimpuesto tsi ON (i.CodImpuesto = tsi.CodImpuesto)
			WHERE
				tsi.CodTipoServicio = '".$tiposervicio."' AND
				i.CodRegimenFiscal = 'I'";
	$query = mysql_query($sql) or die($sql.mysql_error());
	while ($field = mysql_fetch_array($query)) {
		if (($tipoimpuesto == "I" || $tipoimpuesto == "R") && $campo == "pagado") $afecto = $pagado / (($field['FactorPorcentaje'] / 100) + 1);
		$monto_impuesto = $afecto * $field['FactorPorcentaje'] / 100;
		if ($field['Signo'] == "N") $monto_impuesto *= (-1);
		$impuesto += $monto_impuesto;
	}
	
	//	retenciones
	$sql = "SELECT
				i.FactorPorcentaje,
				i.Signo,
				i.CodRegimenFiscal,
				i.FlagImponible
			FROM
				mastimpuestos i
				INNER JOIN masttiposervicioimpuesto tsi ON (i.CodImpuesto = tsi.CodImpuesto)
			WHERE
				tsi.CodTipoServicio = '".$tiposervicio."' AND
				i.CodRegimenFiscal = 'R'";
	$query = mysql_query($sql) or die($sql.mysql_error());
	while ($field = mysql_fetch_array($query)) {
		if ($field['FlagImponible'] == "N") $monto_retencion = $afecto * $field['FactorPorcentaje'] / 100;
		else $monto_retencion = $impuesto * $field['FactorPorcentaje'] / 100;
		if ($field['Signo'] == "N") $monto_retencion *= (-1);
		$retencion += $monto_retencion;
	}
	
	if ($campo != "pagado") $pagado = $afecto + $noafecto + $impuesto + $retencion;
	$pagado = number_format($pagado, 2, ',', '.');
	$afecto = number_format($afecto, 2, ',', '.');
	$noafecto = number_format($noafecto, 2, ',', '.');
	$impuesto = number_format($impuesto, 2, ',', '.');
	$retencion = number_format($retencion, 2, ',', '.');
	echo "$pagado|$afecto|$noafecto|$impuesto|$retencion";
}

//	--------------------------
elseif ($accion == "insertarDistribucionCajaChica") {
	connect();
	?>
    <td align="center">
    	<input type="hidden" name="nro" value="<?=$candetalles?>" />
		<?=$candetalles?>
	</td>
    <td align="center">
        <input type="hidden" name="concepto" id="concepto_<?=$candetalles?>" />
        <input type="text" name="nomconcepto" id="nomconcepto_<?=$candetalles?>" style="width:99%;" class="cell" disabled="disabled" />
    </td>
    <td align="center">
    	<input type="text" name="codpartida" id="codpartida_<?=$candetalles?>" style="width:96%; text-align:center;" class="cell" disabled="disabled" />
        <input type="hidden" name="nompartida" id="nompartida_<?=$candetalles?>" />
	</td>
    <td align="center">
    	<input type="text" name="codcuenta" id="codcuenta_<?=$candetalles?>" style="width:96%; text-align:center;" class="cell" disabled="disabled" />
    	<input type="hidden" name="nomcuenta" id="nomcuenta_<?=$candetalles?>" />
	</td>
    <td align="center">
    	<input type="text" name="ccosto" id="ccosto_<?=$candetalles?>" value="<?=$codccosto?>" style="width:95%; text-align:center;" class="cell" disabled="disabled" />
    	<input type="hidden" name="nomccosto" id="nomccosto_<?=$candetalles?>" />
	</td>
    <td align="center"><input type="text" name="monto" value="0,00" style="width:95%; text-align:right;" class="cell" onfocus="numeroFocus(this); this.className='cellFocus';" onblur="numeroBlur(this); this.className='cell';" onchange="setTotalesDistribucionCajaChica();" /></td>
    <?
}

//	--------------------------
elseif ($accion == "cargarOpcionVerObligacion") {
	connect();
	list($periodo, $sistema, $secuencia) = split("[.]", $codigo);
	$sql = "SELECT *
			FROM ap_registrocompras
			WHERE
				Periodo = '".$periodo."' AND
				SistemaFuente = '".$sistema."' AND
				Secuencia = '".$secuencia."'";
	$query = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	echo "$field[CodOrganismo]|$field[CodProveedor]|$field[CodTipoDocumento]|$field[NroDocumento]";
}

elseif ($accion == "getDescripcionPersona") {
	connect();
	$filtro_flag = "";	
	if ($flagcliente == "S") { $filtro_flag .= " AND (EsCliente = 'S' "; }
	if ($flagproveedor == "S") {
		if ($filtro_flag == "") $filtro_flag .= " AND (EsProveedor = 'S' ";
		else $filtro_flag .= " OR EsProveedor = 'S' ";
	}
	if ($flagempleado == "S") {
		if ($filtro_flag == "") $filtro_flag .= " AND ((EsEmpleado = 'S' AND Estado = 'A') ";
		else $filtro_flag .= " OR (EsEmpleado = 'S' AND Estado = 'A') ";
	}
	if ($flagotros == "S") {
		if ($filtro_flag == "") $filtro_flag .= " AND (EsOtros = 'S' ";
		else $filtro_flag .= " OR EsOtros = 'S' ";
	}
	if ($filtro_flag != "") $filtro_flag = "$filtro_flag)";
	$sql = "SELECT CodPersona, NomCompleto FROM mastpersonas WHERE CodPersona = '".$codigo."' $filtro_flag";
	$query = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	echo "|$field[CodPersona]|$field[NomCompleto]";
}

elseif ($accion == "getPartidaVsCuenta") {
	connect();
	$sql = "SELECT
				p.cod_partida,
				p.denominacion AS NomPartida,
				p.CodCuenta,
				c.Descripcion AS NomCuenta
			FROM
				pv_partida p
				LEFT JOIN ac_mastplancuenta c ON (p.CodCuenta = c.CodCuenta)
			WHERE
				p.cod_partida = '".$codigo."' AND
				(p.especifica <> '00' OR p.subespecifica <> '00')";
	$query = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		echo "|$field[cod_partida]|$field[NomPartida]|$field[CodCuenta]|$field[NomCuenta]";
	}
}

elseif ($accion == "getDescripcionCuenta") {
	connect();
	$sql = "SELECT CodCuenta, Descripcion FROM ac_mastplancuenta WHERE CodCuenta = '".$codigo."'";
	$query = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	echo "|$field[CodCuenta]|$field[Descripcion]";
}

elseif ($accion == "getDescripcionCCosto") {
	connect();
	$sql = "SELECT CodCentroCosto, Descripcion FROM ac_mastcentrocosto WHERE CodCentroCosto = '".$codigo."'";
	$query = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	echo "|$field[CodCentroCosto]|$field[Descripcion]";
}
?>