<?php
if ($opcion == "nuevo") {
	$accion = "nuevo";
	$titulo = "Nueva Transacci&oacute;n";
	$label_submit = "Guardar";
	//	valores default
	$field_transaccion['Estado'] = "PR";
	$field_transaccion['CodOrganismo'] = $_SESSION["ORGANISMO_ACTUAL"];
	$field_transaccion['CodDependencia'] = $_SESSION["DEPENDENCIA_ACTUAL"];
	$field_transaccion['CodCentroCosto'] = $_SESSION["CCOSTO_ACTUAL"];
	$field_transaccion['IngresadoPor'] = $_SESSION["CODPERSONA_ACTUAL"];
	$field_transaccion['NomIngresadoPor'] = $_SESSION["NOMBRE_USUARIO_ACTUAL"];
	$field_transaccion['Anio'] = substr($Ahora, 0, 4);
	$field_transaccion['FechaPreparacion'] = substr($Ahora, 0, 10);
	//	default
	$disabled_anular = "disabled";
	$disabled_item = "disabled";
}
elseif ($opcion == "modificar" || $opcion == "ejecutar" || $opcion == "reversa" || $opcion == "ver") {
	list($CodOrganismo, $CodDocumento, $NroDocumento) = split("[.]", $registro);
	//	consulto datos generales
	$sql = "SELECT
				t.*,
				tt.TipoMovimiento,
				tt.Descripcion AS NomTransaccion,
				p1.NomCompleto AS NomIngresadoPor,
				p2.NomCompleto AS NomRecibidoPor
			FROM
				lg_commoditytransaccion t
				INNER JOIN lg_operacioncommodity tt ON (t.CodTransaccion = tt.CodOperacion)
				LEFT JOIN mastpersonas p1 ON (t.IngresadoPor = p1.CodPersona)
				LEFT JOIN mastpersonas p2 ON (t.RecibidoPor = p2.CodPersona)
			WHERE
				t.CodOrganismo = '".$CodOrganismo."' AND
				t.CodDocumento = '".$CodDocumento."' AND
				t.NroDocumento = '".$NroDocumento."'";
	$query_transaccion = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_transaccion)) $field_transaccion = mysql_fetch_array($query_transaccion);
	
	if ($opcion == "modificar") {
		$titulo = "Modificar Transacci&oacute;n";
		$accion = "modificar";
		$disabled_modificar = "disabled";
		$display_modificar = "display:none;";
		$label_submit = "Modificar";
		$disabled_anular = "disabled";
		$display_anular = "display:none;";
	}
	
	elseif ($opcion == "ver") {
		$titulo = "Ver Transacci&oacute;n";
		$disabled_ver = "disabled";
		$display_ver = "display:none;";
		$display_submit = "display:none;";
		$disabled_modificar = "disabled";
		$display_modificar = "display:none;";
		$disabled_anular = "disabled";
		$display_anular = "display:none;";
		$disabled_item = "disabled";
	}
	
	elseif ($opcion == "ejecutar") {
		$titulo = "Ejecutar Transacci&oacute;n";
		$accion = "ejecutar";
		$label_submit = "Ejecutar";
		$disabled_ver = "disabled";
		$display_ver = "display:none;";
		$disabled_modificar = "disabled";
		$display_modificar = "display:none;";
		$disabled_anular = "disabled";
		$display_anular = "display:none;";
		$disabled_item = "disabled";
	}
	
	elseif ($opcion == "reversa") {
		if ($field_transaccion['CodTransaccion'] == $_PARAMETRO["TRANSDESPA"]) $CodTransaccion = $_PARAMETRO["TRANSANULREQ"];
		elseif ($field_transaccion['CodTransaccion'] == $_PARAMETRO["TRANSRECEP"]) $CodTransaccion = $_PARAMETRO["TRANSANULREC"];
		elseif ($field_transaccion['TipoMovimiento'] == "I") $CodTransaccion = $_PARAMETRO["TRANSANULIN"];
		elseif ($field_transaccion['TipoMovimiento'] == "E") $CodTransaccion = $_PARAMETRO["TRANSANULEG"];
		$sql = "SELECT
					Descripcion,
					TipoMovimiento,
					TipoDocGenerado,
					TipoDocTransaccion
				FROM lg_operacioncommodity
				WHERE CodTransaccion = '".$CodTransaccion."'";
		$query_tipo = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_tipo)) $field_tipo = mysql_fetch_array($query_tipo);
		$field_transaccion['CodTransaccion'] = $CodTransaccion;
		$field_transaccion['NomTransaccion'] = $field_tipo['Descripcion'];
		$field_transaccion['TipoMovimiento'] = $field_tipo['TipoMovimiento'];
		$field_transaccion['DocumentoReferencia'] = $field_transaccion['CodDocumento']."-".$field_transaccion['NroDocumento'];
		$field_transaccion['CodDocumento'] = $field_tipo['TipoDocGenerado'];
		$field_transaccion['CodDocumentoReferencia'] = $field_tipo['TipoDocTransaccion'];
		$field_transaccion['NroDocumentoReferencia'] = "";
		$field_transaccion['NroDocumento'] = "";
		$field_transaccion['NroInterno'] = "";
		//	default
		$titulo = "Reversa de Transacci&oacute;n";
		$accion = "nuevo";
		$label_submit = "Reversa";
		$disabled_modificar = "disabled";
		$display_modificar = "display:none;";
		$disabled_item = "disabled";
	}
	
	if ($field_transaccion['FlagManual'] != "S" || $opcion == "ejecutar" || $opcion == "ver") $dPrecioUnit = "disabled";
}
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="document.getElementById('frmentrada').submit()">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<table width="1000" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" onclick="currentTab('tab', this);" class="current"><a href="#" onclick="mostrarTab('tab', 1, 2);">Informaci&oacute;n General</a></li>
            <li id="li2" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 2, 2);">Commodities</a></li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=lg_transaccion_commodity_lista" method="POST" onsubmit="return transaccion_commodity(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="fCodOrganismo" id="fCodOrganismo" value="<?=$fCodOrganismo?>" />
<input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />
<input type="hidden" name="fCodDependencia" id="fCodDependencia" value="<?=$fCodDependencia?>" />
<input type="hidden" name="fFechaDocumentod" id="fFechaDocumentod" value="<?=$fFechaDocumentod?>" />
<input type="hidden" name="fFechaDocumentoh" id="fFechaDocumentoh" value="<?=$fFechaDocumentoh?>" />
<input type="hidden" name="fCodCentroCosto" id="fCodCentroCosto" value="<?=$fCodCentroCosto?>" />
<input type="hidden" name="fEstado" id="fEstado" value="<?=$fEstado?>" />
<input type="hidden" name="fCodTransaccion" id="fCodTransaccion" value="<?=$fCodTransaccion?>" />
<input type="hidden" name="fNomTransaccion" id="fNomTransaccion" value="<?=$fNomTransaccion?>" />
<input type="hidden" name="fCodDocumento" id="fCodDocumento" value="<?=$fCodDocumento?>" />
<input type="hidden" name="fNroDocumento" id="fNroDocumento" value="<?=$fNroDocumento?>" />
<input type="hidden" name="fPeriodo" id="fPeriodo" value="<?=$fPeriodo?>" />
<input type="hidden" name="fCodDocumentoReferencia" id="fCodDocumentoReferencia" value="<?=$fCodDocumentoReferencia?>" />
<input type="hidden" name="fNroDocumentoReferencia" id="fNroDocumentoReferencia" value="<?=$fNroDocumentoReferencia?>" />
<input type="hidden" id="NroDocumento" value="<?=$field_transaccion['NroDocumento']?>" />

<div id="tab1" style="display:block;">
<table width="1000" class="tblForm">
	<tr>
    	<td colspan="4" class="divFormCaption">Informaci&oacute;n General</td>
    </tr>
    <tr>
		<td class="tagForm" width="150">* Organismo:</td>
		<td>
			<select id="CodOrganismo" style="width:275px;" <?=$disabled_ver?>>
				<?=getOrganismos($field_transaccion['CodOrganismo'], 3)?>
			</select>
		</td>
		<td class="tagForm">Estado:</td>
        <td>
        	<input type="hidden" id="Estado" value="<?=$field_transaccion['Estado']?>" />
        	<input type="text" value="<?=printValores("ESTADO-TRANSACCION", $field_transaccion['Estado'])?>" style="width:100px;" class="codigo" disabled />
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Dependencia:</td>
		<td>
			<select id="CodDependencia" style="width:275px;" <?=$disabled_ver?>>
				<?=loadSelectDependiente("mastdependencias", "CodDependencia", "Dependencia", "CodOrganismo", $field_transaccion['CodDependencia'], $field_transaccion['CodOrganismo'], 0)?>
			</select>
		</td>
		<td class="tagForm" width="150">* Transacci&oacute;n:</td>
		<td class="gallery clearfix">
        	<input type="hidden" id="TipoMovimiento" value="<?=$field_transaccion['TipoMovimiento']?>" />
			<input type="text" id="CodTransaccion" value="<?=$field_transaccion['CodTransaccion']?>" style="width:35px;" class="disabled" disabled />
			<input type="text" id="NomTransaccion" value="<?=$field_transaccion['NomTransaccion']?>" style="width:205px;" class="disabled" disabled  />
			<a href="../lib/listas/listado_tipo_transacciones_commodity.php?filtrar=default&cod=CodTransaccion&ventana=transaccion_almacen_sel&nom=NomTransaccion&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]" style=" <?=$display_modificar?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Centro de Costo:</td>
		<td>
			<select id="CodCentroCosto" style="width:275px;" <?=$disabled_ver?>>
				<?=loadSelectDependiente("ac_mastcentrocosto", "CodCentroCosto", "Descripcion", "CodDependencia", $field_transaccion['CodCentroCosto'], $field_transaccion['CodDependencia'], 0)?>
			</select>
		</td>
		<td class="tagForm">* Documento a Generar:</td>
        <td>
        	<input type="text" id="CodDocumento" value="<?=$field_transaccion['CodDocumento']?>" style="width:35px;" class="disabled" disabled />
        	<input type="text" id="NroInterno" value="<?=$field_transaccion['NroInterno']?>" style="width:105px;" class="disabled" disabled />
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Almac&eacute;n:</td>
		<td>
			<select id="CodAlmacen" style="width:200px;" <?=$disabled_ver?>>
				<?=loadSelectAlmacen($field_transaccion['CodAlmacen'], "S", 0)?>
			</select>
		</td>
		<td class="tagForm">* Doc. Referencia:</td>
        <td>
        	<select id="CodDocumentoReferencia" style="width:39px;" <?=$disabled_ver?>>
				<?=loadSelect("lg_tipodocumento", "CodDocumento", "Descripcion", $field_transaccion['CodDocumentoReferencia'], 10);?>
			</select>
            <input type="text" id="NroDocumentoReferencia" maxlength="20" value="<?=$field_transaccion['NroDocumentoReferencia']?>" style="width:105px;" <?=$disabled_ver?> />
        </td>
	</tr>
	<tr>
	 	<td class="tagForm">* Fecha Transacci&oacute;n:</td>
		<td><input type="text" id="FechaDocumento" value="<?=formatFechaDMA(substr($Ahora, 0, 10))?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" <?=$disabled_ver?> /></td>
		<td class="tagForm">Preparada Por:</td>
		<td>
			<input type="hidden" id="IngresadoPor" value="<?=$field_transaccion['IngresadoPor']?>" />
			<input type="text" id="NomIngresadoPor" value="<?=$field_transaccion['NomIngresadoPor']?>" style="width:247px;" class="disabled" disabled />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Doc. Ref / G. Remisi&oacute;n:</td>
		<td>
			<input type="text" id="DocumentoReferencia" maxlength="20" style="width:125px;" value="<?=$field_transaccion['DocumentoReferencia']?>" <?=$disabled_ver?> />
		</td>
		<td class="tagForm">Recibida por:</td>
		<td class="gallery clearfix">
			<input type="hidden" id="RecibidoPor" value="<?=$field_transaccion['RecibidoPor']?>" />
			<input type="text" id="NomRecibidoPor" value="<?=$field_transaccion['NomRecibidoPor']?>" style="width:247px;" class="disabled" disabled />
			<a href="../lib/listas/listado_empleados.php?filtrar=default&cod=RecibidoPor&nom=NomRecibidoPor&iframe=true&width=950&height=525" rel="prettyPhoto[iframe2]" style=" <?=$display_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
		</td>
	</tr>
	<tr>
		<td class="tagForm">O. Compra/Doc. Interno:</td>
		<td>
			<input type="hidden" id="ReferenciaNroDocumento" value="<?=$field_transaccion['ReferenciaNroDocumento']?>" />
			<input type="text" id="DocumentoReferenciaInterno" value="<?=$field_transaccion['DocumentoReferenciaInterno']?>" style="width:125px;" class="disabled" readonly />
		</td>
        <td>&nbsp;</td>
    	<td>
        	<input type="checkbox" name="FlagManual" id="FlagManual" value="S" <?=chkFlag($field_transaccion['FlagManual'])?> onchange="setFlagManualAlmacen(this.checked);" <?=$disabled_ver?> /> Valorizaci&oacute;n Manual
        </td>
	</tr>
    <tr>
		<td class="tagForm">Nota de Entrega:</td>
		<td>
			<input type="text" id="NotaEntrega" maxlength="10" style="width:125px;" value="<?=$field_transaccion['NotaEntrega']?>" class="disabled" readonly />
		</td>
        <td>&nbsp;</td>
        <td>
        	<input type="checkbox" name="FlagPendiente" id="FlagPendiente" value="S" <?=chkFlag($field_transaccion['FlagPendiente'])?> disabled="disabled" /> Valorizaci&oacute;n Pendiente
        </td>
    </tr>
    <tr>
		<td class="tagForm">Comentarios:</td>
		<td colspan="3"><textarea id="Comentarios" style="width:95%; height:50px;" <?=$disabled_ver?>><?=($field_transaccion['Comentarios'])?></textarea></td>
	</tr>
	<tr>
		<td class="tagForm">Razon Rechazo:</td>
		<td colspan="3"><textarea id="MotRechazo" style="width:95%; height:30px;" <?=$disabled_anular?>><?=($field_transaccion['MotRechazo'])?></textarea></td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td>
			<input type="text" size="30" class="disabled" value="<?=$field_transaccion['UltimoUsuario']?>" disabled="disabled" />
			<input type="text" size="25" class="disabled" value="<?=$field_transaccion['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>
</table>
<center> 
<input type="submit" value="<?=$label_submit?>" style=" <?=$display_submit?>" />
<input type="button" value="Cancelar" onclick="this.form.submit();" />
</center>
<div style="width:1000px" class="divMsj">Campos Obligatorios *</div>
</div>
</form>

<div id="tab2" style="display:none;">
<form name="frm_detalles" id="frm_detalles">
<input type="hidden" name="sel_detalles" id="sel_detalles" />
<table width="1000" class="tblBotones">
	<tr>
		<td align="right" class="gallery clearfix">
        	<a id="aItem" href="../lib/listas/listado_commodities.php?filtrar=default&ventana=commodity_detalles_insertar&iframe=true&width=950&height=525" rel="prettyPhoto[iframe3]" style="display:none;"></a>
			<input type="button" class="btLista" value="Commodity" id="btItem" onclick="document.getElementById('aItem').click();" <?=$disabled_item?> />
			<input type="button" class="btLista" value="Borrar" id="btBorrar" onclick="quitarLinea(this, 'detalles');" <?=$disabled_item?> /> | 
            <input type="button" class="btLista" value="Copiar" disabled="disabled" />
            <input type="button" class="btLista" value="Partir Lote" disabled="disabled" />
            <input type="button" class="btLista" value="Inf.Adicional" disabled="disabled" />
		</td>
	</tr>
</table>
<center>
<div style="overflow:scroll; width:1000px; height:400px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
        <th scope="col" width="25">#</th>
        <th scope="col" width="60">Item</th>
        <th scope="col">Descripci&oacute;n</th>
        <th scope="col" width="35">Uni.</th>
        <th scope="col" width="50">Stock Actual</th>
        <th scope="col" width="50">Cantidad</th>
        <th scope="col" width="75">Precio Unit.</th>
        <th scope="col" width="75">Total</th>
        <th scope="col" width="150">Doc. Referencia</th>
    </tr>
    </thead>
    
    <tbody id="lista_detalles">
    <?
	$nrodetalles = 0;
	$sql = "SELECT
				td.*,
				iai.Cantidad AS StockActual
			FROM
				lg_commoditytransacciondetalle td
				LEFT JOIN lg_commoditystock iai ON (iai.CommoditySub = td.CommoditySub AND
													iai.CodAlmacen = '".$field_transaccion['CodAlmacen']."')
			WHERE
				td.CodOrganismo = '".$CodOrganismo."' AND
				td.CodDocumento = '".$CodDocumento."' AND
				td.NroDocumento = '".$NroDocumento."'
			ORDER BY Secuencia";
	$query_detalle = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while($field_detalle = mysql_fetch_array($query_detalle)) {
		$Total = $CantidadPendiente * $PrecioUnit;
		$nrodetalles++;
		?>
		<tr class="trListaBody" onclick="mClk(this, 'sel_detalles');" id="detalles_<?=$nrodetalles?>">
			<th align="center">
				<?=$nrodetalles?>
            </th>
			<td align="center">
                <input type="text" name="CommoditySub" class="cell2" style="text-align:center;" value="<?=$field_detalle['CommoditySub']?>" readonly />
            </td>
			<td align="center">
				<textarea name="Descripcion" style="height:30px;" class="cell" readonly="readonly"><?=($field_detalle['Descripcion'])?></textarea>
			</td>
			<td align="center">
            	<input type="text" name="CodUnidad" value="<?=$field_detalle['CodUnidad']?>" class="cell2" style="text-align:center;" readonly />		
            </td>
			<td align="center">
            	<input type="text" name="StockActual" class="cell2" style="text-align:right;" value="<?=number_format($field_detalle['StockActual'], 4,  '.', '')?>" readonly="readonly" />
            </td>
			<td align="center">
            	<input type="text" name="CantidadRecibida" class="cell" style="text-align:right;" value="<?=number_format($field_detalle['Cantidad'], 4,  '.', '')?>"  onchange="setMontosAlmacen(this.form);" <?=$disabled_item?> />
            </td>
			<td align="center">
            	<input type="text" name="PrecioUnit" class="cell PrecioUnit" style="text-align:right;" value="<?=number_format($field_detalle['PrecioUnit'], 4, '.', '')?>"  onchange="setMontosAlmacen(this.form);" <?=$dPrecioUnit?> />
            </td>
			<td align="center">
            	<input type="text" name="Total" class="cell2" style="text-align:right;" value="<?=number_format($field_detalle['Total'], 2,  '.', '')?>" readonly="readonly" />
            </td>
			<td align="center">
            	<input type="hidden" name="CodCentroCosto" value="<?=$CodCentroCosto?>" />
            	<input type="text" name="ReferenciaCodDocumento" class="cell" style="text-align:center; width:15%;" value="<?=$field_detalle['ReferenciaCodDocumento']?>" />
            	<input type="text" name="ReferenciaNroDocumento" class="cell" style="width:65%;" value="<?=$field_detalle['ReferenciaNroDocumento']?>" />
            	<input type="text" name="ReferenciaSecuencia" class="cell" style="text-align:center; width:10%;" value="<?=$field_detalle['ReferenciaSecuencia']?>" />
			</td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
</center>
<input type="hidden" id="nro_detalles" value="<?=$nrodetalles?>" />
<input type="hidden" id="can_detalles" value="<?=$nrodetalles?>" />
</form>
</div>
