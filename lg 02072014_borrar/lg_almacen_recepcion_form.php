<?php
//	------------------------------------
//	orden
list($Anio, $CodOrganismo, $NroOrden) = split("[.]", $registro);
$sql = "SELECT
			oc.Anio,
			oc.NroOrden,
			oc.NroInterno,
			oc.CodOrganismo,
			oc.CodDependencia,
			oc.CodAlmacen,
			oc.Observaciones,
			oc.PreparadaPor AS RecibidoPor,
			oc.CodProveedor,
			p.NomCompleto AS NomRecibidoPor,
			((SELECT COUNT(*)
			  FROM ap_documentos
			  WHERE
			 	Anio = '".$Anio."' AND
				CodOrganismo = '".$CodOrganismo."' AND
				ReferenciaTipoDocumento = 'OC' AND
				ReferenciaNroDocumento = '".$NroOrden."') + 1) AS SecuenciaDocumento
		FROM
			lg_ordencompra oc
			INNER JOIN mastpersonas p ON (oc.PreparadaPor = p.CodPersona)
		WHERE
			oc.Anio = '".$Anio."' AND
			oc.CodOrganismo = '".$CodOrganismo."' AND
			oc.NroOrden = '".$NroOrden."'";
$query_orden = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query_orden) != 0) $field_orden = mysql_fetch_array($query_orden);
//	------------------------------------
//	tipo de transaccion
$sql = "SELECT
			tt.CodTransaccion,
			tt.Descripcion AS NomTransaccion,
			tt.TipoDocGenerado AS CodDocumento,
			tt.TipoDocTransaccion AS CodDocumentoReferencia,
			tt.TipoMovimiento,
			td.Descripcion AS NomTipoDocGenerado
		FROM
			lg_tipotransaccion tt
			INNER JOIN lg_tipodocumento td ON (tt.TipoDocGenerado = td.CodDocumento)
		WHERE tt.CodTransaccion = '".$_PARAMETRO["TRANSRECEP"]."'";
$query_documento = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query_documento) != 0) $field_documento = mysql_fetch_array($query_documento);
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Recepci&oacute;n en Almacen</td>
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
            <li id="li2" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 2, 2);">Items</a></li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=lg_almacen_recepcion_lista" method="POST" onsubmit="return almacen_recepcion(this);">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="fCodOrganismo" id="fCodOrganismo" value="<?=$fCodOrganismo?>" />
<input type="hidden" name="fCodDependencia" id="fCodDependencia" value="<?=$fCodDependencia?>" />
<input type="hidden" name="fFechaPrometida" id="fFechaPrometida" value="<?=$fFechaPrometida?>" />
<input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />
<input type="hidden" name="fCodAlmacen" id="fCodAlmacen" value="<?=$fCodAlmacen?>" />
<input type="hidden" id="Anio" value="<?=$field_orden['Anio']?>" />
<input type="hidden" id="NroOrden" value="<?=$field_orden['NroOrden']?>" />
<input type="hidden" id="CodProveedor" value="<?=$field_orden['CodProveedor']?>" />

<div id="tab1" style="display:block;">
<table width="1000" class="tblForm">
	<tr>
    	<td colspan="4" class="divFormCaption">Informaci&oacute;n General</td>
    </tr>
    <tr>
		<td class="tagForm" width="150">* Organismo:</td>
		<td>
			<select id="CodOrganismo" style="width:275px;">
				<?=getOrganismos($field_orden['CodOrganismo'], 3)?>
			</select>
		</td>
		<td class="tagForm">Estado:</td>
        <td>
        	<input type="hidden" id="Estado" value="PR" />
        	<input type="text" value="<?=printValores("ESTADO-TRANSACCION", "PR")?>" style="width:100px;" class="codigo" readonly />
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Dependencia:</td>
		<td>
			<select id="CodDependencia" style="width:275px;">
				<?=loadSelect("mastdependencias", "CodDependencia", "Dependencia", $field_orden['CodDependencia'], 1)?>
			</select>
		</td>
		<td class="tagForm" width="150">* Transacci&oacute;n:</td>
		<td class="gallery clearfix">
        	<input type="hidden" id="TipoMovimiento" value="<?=$field_documento['TipoMovimiento']?>" />
			<input type="text" id="CodTransaccion" value="<?=$field_documento['CodTransaccion']?>" style="width:35px;" class="disabled" readonly />
			<input type="text" id="NomTransaccion" value="<?=$field_documento['NomTransaccion']?>" style="width:205px;" class="disabled" readonly  />
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Centro de Costo:</td>
		<td>
			<select id="CodCentroCosto" style="width:275px;">
				<?=loadSelect("ac_mastcentrocosto", "CodCentroCosto", "Descripcion", $_PARAMETRO["CCOSTOCOMPRA"], 1)?>
			</select>
		</td>
		<td class="tagForm">* Documento a Generar:</td>
        <td>
        	<input type="text" id="CodDocumento" value="<?=$field_documento['CodDocumento']?>" style="width:35px;" class="disabled" readonly />
        	<input type="text" id="NroDocumento" value="<?=$field_documento['NroDocumento']?>" style="width:105px;" class="disabled" readonly />
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Almac&eacute;n:</td>
		<td>
			<select id="CodAlmacen" style="width:200px;">
            	<?=loadSelectAlmacen($field_orden['CodAlmacen'], "N", 1)?>
			</select>
		</td>
		<td class="tagForm">* Doc. Referencia:</td>
        <td>
        	<select id="CodDocumentoReferencia" style="width:39px;">
				<?=loadSelect("lg_tipodocumento", "CodDocumento", "Descripcion", $field_documento['CodDocumentoReferencia'], 10);?>
			</select>
            <input type="text" id="NroDocumentoReferencia" maxlength="20" style="width:105px;" />
        </td>
	</tr>
	<tr>
	 	<td class="tagForm">* Fecha Transacci&oacute;n:</td>
		<td><input type="text" id="FechaDocumento" value="<?=formatFechaDMA(substr($Ahora, 0, 10))?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" /></td>
		<td class="tagForm">Preparada Por:</td>
		<td>
			<input type="hidden" id="IngresadoPor" value="<?=$_SESSION["CODPERSONA_ACTUAL"]?>" />
			<input type="text" id="NomIngresadoPor" value="<?=$_SESSION["NOMBRE_USUARIO_ACTUAL"]?>" style="width:247px;" class="disabled" readonly="readonly" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Doc. Ref / G. Remisi&oacute;n:</td>
		<td>
			<input type="text" id="DocumentoReferencia" maxlength="20" style="width:125px;" value="OC-<?=$field_orden['NroInterno']?>-<?=$field_orden['Anio']?>-<?=$field_orden['SecuenciaDocumento']?>" />
		</td>
		<td class="tagForm">Recibida por:</td>
		<td class="gallery clearfix">
			<input type="hidden" id="RecibidoPor" value="<?=$field_orden['RecibidoPor']?>" />
			<input type="text" id="NomRecibidoPor" value="<?=$field_orden['NomRecibidoPor']?>" style="width:247px;" class="disabled" readonly="readonly" />

			<a href="../lib/listas/listado_empleados.php?filtrar=default&cod=RecibidoPor&nom=NomRecibidoPor&iframe=true&width=950&height=525" rel="prettyPhoto[iframe2]">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
		</td>
	</tr>
	<tr>
		<td class="tagForm">O. Compra/Doc. Interno:</td>
		<td>
			<input type="hidden" id="ReferenciaNroDocumento" value="<?=$field_orden['NroOrden']?>" />
			<input type="text" id="DocumentoReferenciaInterno" value="<?=$field_orden['NroInterno']?>" style="width:125px;" class="disabled" readonly />
		</td>
        <td>&nbsp;</td>
    	<td>
        	<input type="checkbox" name="FlagManual" id="FlagManual" value="S" disabled="disabled" /> Valorizaci&oacute;n Manual
        </td>
	</tr>
    <tr>
		<td class="tagForm">Nota de Entrega:</td>
		<td>
			<input type="text" id="NotaEntrega" maxlength="10" style="width:125px;" class="disabled" readonly />
		</td>
        <td>&nbsp;</td>
        <td>
        	<input type="checkbox" name="FlagPendiente" id="FlagPendiente" value="S" disabled="disabled" /> Valorizaci&oacute;n Pendiente
        </td>
    </tr>
    <tr>
		<td class="tagForm">Comentarios:</td>
		<td colspan="3"><textarea id="Comentarios" style="width:95%; height:50px;"><?=($field_orden['Observaciones'])?></textarea></td>
	</tr>
	<tr>
		<td class="tagForm">Razon Rechazo:</td>
		<td colspan="3"><textarea id="MotRechazo" style="width:95%; height:30px;" class="disabled" readonly="readonly"></textarea></td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td>
			<input type="text" size="30" class="disabled" disabled="disabled" />
			<input type="text" size="25" class="disabled" disabled="disabled" />
		</td>
	</tr>
</table>
<center> 
<input type="submit" value="Procesar" />
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
		<td align="right">
			<input type="button" class="btLista" value="Item" disabled="disabled" />
			<input type="button" class="btLista" value="Borrar" disabled="disabled" /> | 
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
	$detalle = split(";char:tr;", $detalles);
	foreach ($detalle as $linea) {
		list($_Linea, $CodItem, $Descripcion, $CodUnidad, $StockActual, $CantidadPedida, $CantidadRecibida, $CantidadPendiente, $PrecioUnit, $FlagExonerado, $CodCentroCosto) = split(";char:td;", $linea);
		list($_Anio, $_CodOrganismo, $_NroOrden, $Secuencia) = split("[.]", $_Linea);
		$Total = $CantidadPendiente * $PrecioUnit;
		$nrodetalles++;
		?>
		<tr class="trListaBody" onclick="mClk(this, 'sel_detalles');" id="detalles_<?=$nrodetalles?>">
			<th align="center">
				<?=$nrodetalles?>
            </th>
			<td align="center">
                <input type="text" name="CodItem" class="cell2" style="text-align:center;" value="<?=$CodItem?>" readonly />
            </td>
			<td align="center">
				<textarea name="Descripcion" style="height:30px;" class="cell" readonly="readonly"><?=($Descripcion)?></textarea>
			</td>
			<td align="center">
            	<input type="text" name="CodUnidad" value="<?=$CodUnidad?>" class="cell2" style="text-align:center;" readonly />		
            </td>
			<td align="center">
            	<input type="text" name="StockActual" class="cell2" style="text-align:right;" value="<?=number_format($StockActual, 2, ',', '.')?>" readonly="readonly" />
            </td>
			<td align="center">
            	<input type="hidden" name="CantidadPedida" value="<?=$CantidadPedida?>" />
            	<input type="hidden" name="CantidadPendiente" value="<?=$CantidadPendiente?>" />
            	<input type="text" name="CantidadRecibida" class="cell" style="text-align:right;" value="<?=number_format($CantidadPendiente, 2, ',', '.')?>" onBlur="numeroBlur(this);" onFocus="numeroFocus(this);" onchange="setMontosAlmacen(this.form);" />
            </td>
			<td align="center">
            	<input type="hidden" name="FlagExonerado" value="<?=$FlagExonerado?>" />
            	<input type="text" name="PrecioUnit" class="cell2" style="text-align:right;" value="<?=number_format($PrecioUnit, 2, ',', '.')?>" readonly="readonly" />
            </td>
			<td align="center">
            	<input type="text" name="Total" class="cell2" style="text-align:right;" value="<?=number_format($Total, 2, ',', '.')?>" readonly="readonly" />
            </td>
			<td align="center">
            	<input type="hidden" name="CodCentroCosto" value="<?=$CodCentroCosto?>" />
            	<input type="hidden" name="ReferenciaCodDocumento" value="OC" />
            	<input type="hidden" name="ReferenciaNroDocumento" value="<?=$field_orden['NroInterno']?>" />
            	<input type="hidden" name="ReferenciaSecuencia" value="<?=$Secuencia?>" />
                OC-<?=$field_orden['NroInterno']?>-<?=$Secuencia?>
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