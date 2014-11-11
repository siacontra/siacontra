<?php
//	------------------------------------
//	orden
list($CodRequerimiento) = split("[.]", $registro);
$sql = "SELECT
			r.CodRequerimiento,
			r.CodInterno,
			r.CodOrganismo,
			r.CodDependencia,
			r.CodCentroCosto,
			r.CodAlmacen,
			r.PreparadaPor AS RecibidoPor,
			r.Comentarios,
			p.NomCompleto AS NomRecibidoPor,
			a.FlagCommodity
		FROM
			lg_requerimientos r
			INNER JOIN mastpersonas p ON (r.PreparadaPor = p.CodPersona)
			INNER JOIN lg_requerimientosdet rd ON (rd.CodRequerimiento = r.CodRequerimiento)
			INNER JOIN lg_almacenmast a ON (a.CodAlmacen = r.CodAlmacen)
		WHERE r.CodRequerimiento = '".$CodRequerimiento."'
		GROUP BY CodRequerimiento";
$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
//	------------------------------------
if ($field['FlagCommodity'] == "S") {
	//	tipo de transaccion
	$sql = "SELECT
				tt.CodOperacion AS CodTransaccion,
				tt.Descripcion AS NomTransaccion,
				tt.TipoDocGenerado AS CodDocumento,
				tt.TipoDocTransaccion AS CodDocumentoReferencia,
				tt.TipoMovimiento,
				td.Descripcion AS NomTipoDocGenerado
			FROM
				lg_operacioncommodity tt
				INNER JOIN lg_tipodocumento td ON (tt.TipoDocGenerado = td.CodDocumento)
			WHERE tt.CodOperacion = '".$_PARAMETRO["TRANSDESPACC"]."'";
} else {
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
			WHERE tt.CodTransaccion = '".$_PARAMETRO["TRANSDESPACCA"]."'";
}
$query_documento = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query_documento) != 0) $field_documento = mysql_fetch_array($query_documento);
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Despacho de Caja Chica</td>
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
            <li id="li2" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 2, 2);">Item/Commodities</a></li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=lg_transaccion_cajachica_lista" method="POST" onsubmit="return transaccion_cajachica_despacho(this);">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="fCodOrganismo" id="fCodOrganismo" value="<?=$fCodOrganismo?>" />
<input type="hidden" name="fCodDependencia" id="fCodDependencia" value="<?=$fCodDependencia?>" />
<input type="hidden" name="fFechaPrometida" id="fFechaPrometida" value="<?=$fFechaPrometida?>" />
<input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />
<input type="hidden" name="fCodAlmacen" id="fCodAlmacen" value="<?=$fCodAlmacen?>" />
<input type="hidden" id="FlagCommodity" value="<?=$field['FlagCommodity']?>" />
<input type="hidden" id="CodRequerimiento" value="<?=$field['CodRequerimiento']?>" />

<div id="tab1" style="display:block;">
<table width="1000" class="tblForm">
	<tr>
    	<td colspan="4" class="divFormCaption">Informaci&oacute;n General</td>
    </tr>
    <tr>
		<td class="tagForm" width="150">* Organismo:</td>
		<td>
			<select id="CodOrganismo" style="width:275px;">
				<?=getOrganismos($field['CodOrganismo'], 3)?>
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
				<?=loadSelect("mastdependencias", "CodDependencia", "Dependencia", $field['CodDependencia'], 1)?>
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
            	<?=loadSelect("lg_almacenmast", "CodAlmacen", "Descripcion", $field['CodAlmacen'], 1)?>
			</select>
		</td>
		<td class="tagForm">* Doc. Referencia:</td>
        <td>
        	<select id="CodDocumentoReferencia" style="width:39px;">
				<?=loadSelect("lg_tipodocumento", "CodDocumento", "Descripcion", $field_documento['CodDocumentoReferencia'], 10);?>
			</select>
            <input type="text" id="NroDocumentoReferencia" maxlength="20" style="width:105px;" value="<?=$field['CodRequerimiento']?>" />
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
			<input type="text" id="DocumentoReferencia" maxlength="20" style="width:125px;" value="RQ-<?=$field['CodInterno']?>" />
		</td>
		<td class="tagForm">Recibida por:</td>
		<td class="gallery clearfix">
			<input type="hidden" id="RecibidoPor" value="<?=$field['RecibidoPor']?>" />
			<input type="text" id="NomRecibidoPor" value="<?=$field['NomRecibidoPor']?>" style="width:247px;" class="disabled" readonly="readonly" />

			<a href="../lib/listas/listado_empleados.php?filtrar=default&cod=RecibidoPor&nom=NomRecibidoPor&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
		</td>
	</tr>
	<tr>
		<td class="tagForm">O. Compra/Doc. Interno:</td>
		<td>
			<input type="hidden" id="ReferenciaNroDocumento" value="<?=$field['CodRequerimiento']?>" />
			<input type="text" id="DocumentoReferenciaInterno" value="<?=$field['CodInterno']?>" style="width:125px;" class="disabled" readonly />
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
        	<input type="checkbox" name="FlagPendiente" id="FlagPendiente" value="S" disabled="disabled" checked="checked" /> Valorizaci&oacute;n Pendiente
        </td>
    </tr>
    <tr>
		<td class="tagForm">Comentarios:</td>
		<td colspan="3"><textarea id="Comentarios" style="width:95%; height:50px;"><?=($field['Comentarios'])?></textarea></td>
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
		<td align="right">&nbsp;
			
		</td>
	</tr>
</table>
<center>
<div style="overflow:scroll; width:1000px; height:400px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
        <th scope="col" width="25">#</th>
        <th scope="col" width="75">Commodity</th>
        <th scope="col">Descripci&oacute;n</th>
        <th scope="col" width="35">Uni.</th>
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
		list($_Linea, $_CodItem, $_CommoditySub, $_Descripcion, $_CodUnidad, $_CantidadPedida, $_CantidadPendiente, $_CodCentroCosto) = split(";char:td;", $linea);
		list($_CodRequerimiento, $_Secuencia) = split("[.]", $_Linea);
		$_Total = $_CantidadPendiente * $_PrecioUnit;
		if ($_CodItem != "") $_Codigo = $_CodItem; else $_Codigo = $_CommoditySub;
		$nrodetalles++;
		?>
		<tr class="trListaBody" onclick="mClk(this, 'sel_detalles');" id="detalles_<?=$nrodetalles?>">
			<th align="center">
				<?=$nrodetalles?>
            </th>
			<td align="center">
                <input type="hidden" name="CodItem" value="<?=$_CodItem?>" />
                <input type="hidden" name="CommoditySub" value="<?=$_CommoditySub?>" />
                <?=$_Codigo?>
            </td>
			<td align="center">
				<textarea name="Descripcion" style="height:30px;" class="cell" readonly="readonly"><?=($_Descripcion)?></textarea>
			</td>
			<td align="center">
            	<input type="text" name="CodUnidad" value="<?=$_CodUnidad?>" class="cell2" style="text-align:center;" readonly />		
            </td>
			<td align="center">
            	<input type="hidden" name="CantidadPedida" value="<?=$_CantidadPedida?>" />
            	<input type="text" name="CantidadRecibida" class="cell" style="text-align:right;" value="<?=number_format($_CantidadPendiente, 4,  '.', '')?>" onFocus="this.value=''" onchange="setMontosAlmacen(this.form);" />
            </td>
			<td align="center">
            	<input type="text" name="PrecioUnit" class="cell2" style="text-align:right;" value="<?=number_format($_PrecioUnit, 4,  '.', '')?>" onFocus="this.value=''" onchange="setMontosAlmacen(this.form);" readonly="readonly" />
            </td>
			<td align="center">
            	<input type="text" name="Total" class="cell2" style="text-align:right;" value="<?=number_format($_Total, 2,  '.', '')?>" readonly="readonly" />
            </td>
			<td align="center">
            	<input type="hidden" name="CodCentroCosto" value="<?=$_CodCentroCosto?>" />
            	<input type="hidden" name="ReferenciaCodDocumento" value="OC" />
            	<input type="hidden" name="ReferenciaNroDocumento" value="<?=$field['CodInterno']?>" />
            	<input type="hidden" name="ReferenciaSecuencia" value="<?=$_Secuencia?>" />
                RQ-<?=$field['CodInterno']?>-<?=$_Secuencia?>
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
