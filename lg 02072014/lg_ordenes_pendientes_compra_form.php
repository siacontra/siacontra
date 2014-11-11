<?php
//	consulto cotizacion
$sql = "SELECT
			c.CodOrganismo,
			c.CodProveedor,
			c.NomProveedor,
			c.CodFormaPago,
			pr.CodTipoServicio,
			r.CodAlmacen
		FROM
			lg_cotizacion c
			INNER JOIN mastproveedores pr ON (c.CodProveedor = pr.CodProveedor)
			INNER JOIN lg_requerimientos r ON (r.CodRequerimiento = c.CodRequerimiento)
		WHERE c.NroCotizacionProv = '".$registro."'
		GROUP BY c.NroCotizacionProv";

$query_orden = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query_orden)) $field_orden = mysql_fetch_array($query_orden);
//	valores default
$field_orden['Anio'] = substr($Ahora, 0, 4);
$field_orden['FechaPreparacion'] = substr($Ahora, 0, 10);
$field_orden['PlazoEntrega'] = $_PARAMETRO['DIAENTOC'];
$field_orden['FechaPrometida'] = formatFechaAMD(getFechaFin(formatFechaDMA(substr($Ahora, 0, 10)), $_PARAMETRO['DIAENTOC']));
//	organismo
$sql = "SELECT * FROM mastorganismos WHERE CodOrganismo = '".$_SESSION['ORGANISMO_ACTUAL']."'";
$query_organismo = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query_organismo)) $field_organismo = mysql_fetch_array($query_organismo);
$field_orden['NomContacto'] = $field_organismo['RepresentLegal'];
$field_orden['FaxContacto'] = $field_organismo['Fax1'];
$field_orden['Entregaren'] = $field_organismo['Organismo'];
$field_orden['DirEntrega'] = $field_organismo['Direccion'];
##
if (!afectaTipoServicio($field_orden['CodTipoServicio'])) { $dFlagExonerado = "disabled"; $cFlagExonerado = "checked"; }
$FactorImpuesto = getPorcentajeIVA($field_orden['CodTipoServicio']);
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Generar Orden de Compra</td>
		<td align="right"><a class="cerrar" href="#" onclick="document.getElementById('frmentrada').submit()">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<table width="1100" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" onclick="currentTab('tab', this);" class="current"><a href="#" onclick="mostrarTab('tab', 1, 5);">Informaci&oacute;n General</a></li>
            <li id="li2" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 2, 5);">Items/Commodities</a></li>
            <li id="li3" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 3, 5);">Cotizaciones</a></li>
            <li id="li4" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 4, 5);">Avances</a></li>
            <li id="li5" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 5, 5);">Distribuci&oacute;n Presupuestaria/Contables</a></li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=lg_ordenes_pendientes_lista" method="POST" onsubmit="return orden_compra(this, 'nuevo');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="fCodOrganismo" id="fCodOrganismo" value="<?=$fCodOrganismo?>" />
<input type="hidden" name="fCodFormaPago" id="fCodFormaPago" value="<?=$fCodFormaPago?>" />
<input type="hidden" name="fCodProveedor" id="fCodProveedor" value="<?=$fCodProveedor?>" />
<input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />
<input type="hidden" id="NroCotizacionProv" value="<?=$registro?>" />
<input type="hidden" id="Anio" value="<?=$field_orden['Anio']?>" />
<input type="hidden" id="NroOrden" />
<input type="hidden" id="TipoClasificacion" value="<?=$field_orden['TipoClasificacion']?>" />
<input type="hidden" id="GenerarPendiente" value="S" />

<div id="tab1" style="display:block;">
<table width="1100" class="tblForm">
	<tr>
		<td class="tagForm" width="150">* Organismo:</td>
		<td>
			<select id="CodOrganismo" style="width:300px;" <?=$disabled_modificar?>>
				<?=getOrganismos($field_orden['CodOrganismo'], 3)?>
			</select>
		</td>
		<td class="tagForm" width="150">Estado:</td>
		<td>
			<input type="hidden" id="Estado" value="PR" />
			<input type="text" style="width:100px;" class="codigo" value="<?=printValoresGeneral("ESTADO-COMPRA", "PR")?>" disabled="disabled" />
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Proveedor:</td>
		<td>
            <input type="text" id="CodProveedor" style="width:50px;" value="<?=$field_orden['CodProveedor']?>" disabled="disabled" />
			<input type="text" id="NomProveedor" style="width:235px;" value="<?=$field_orden['NomProveedor']?>" disabled="disabled" />
        </td>
		<td class="tagForm">N&uacute;mero:</td>
		<td>
        	<input type="text" id="NroInterno" style="width:100px;" class="codigo" disabled="disabled" />
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Tipo de Servicio:</td>
		<td>
        	<input type="hidden" id="FactorImpuesto" value="<?=$FactorImpuesto?>" />
            <select id="CodTipoServicio" style="width:150px;">
                <?=loadSelect("masttiposervicio", "CodTipoServicio", "Descripcion", $field_orden['CodTipoServicio'], 1)?>
            </select>
        </td>
    	<td colspan="2" class="divFormCaption">Monto de la Compra</td>
	</tr>
    <tr>
		<td class="tagForm">* Forma de Pago:</td>
		<td>
            <select id="CodFormaPago" style="width:150px;">
                <?=loadSelect("mastformapago", "CodFormaPago", "Descripcion", $field_orden['CodFormaPago'], 0)?>
            </select>
        </td>
        <td class="tagForm">Monto Afecto:</td>
		<td>
        	<input type="text" id="MontoAfecto" value="<?=number_format($MontoAfecto, 2, ',', '.')?>" style="width:150px; text-align:right;" class="disabled" disabled="disabled" />
        </td>
	</tr>
    <tr>
		<td class="tagForm">* Clasificaci&oacute;n:</td>
		<td>
			<select id="Clasificacion" style="width:150px;">
				<?=loadSelectValores("COMPRA-CLASIFICACION", "", 0)?>
			</select>
		</td>
        <td class="tagForm">Monto No Afecto:</td>
		<td>
        	<input type="text" id="MontoNoAfecto" value="<?=number_format($MontoAfecto, 2, ',', '.')?>" style="width:150px; text-align:right;" class="disabled" disabled="disabled" />
        </td>
	</tr>
    <tr>
		<td class="tagForm">* Almacen Entrega:</td>
		<td>
            <select id="CodAlmacen" style="width:175px;">
                <?=loadSelect("lg_almacenmast", "CodAlmacen", "Descripcion", $field_orden['CodAlmacen'], 0)?>
            </select>
        </td>
        <td class="tagForm">Monto Bruto:</td>
		<td>
        	<input type="text" id="MontoBruto" value="<?=number_format($MontoBruto, 2, ',', '.')?>" style="width:150px; text-align:right;" class="disabled" disabled="disabled" />
        </td>
	</tr>
    <tr>
		<td class="tagForm">* Almacen Ingreso:</td>
		<td>
            <select id="CodAlmacenIngreso" style="width:175px;">
                <?=loadSelect("lg_almacenmast", "CodAlmacen", "Descripcion", $field_orden['CodAlmacen'], 0)?>
            </select>
        </td>
        <td class="tagForm">(+/-) Impuestos:</td>
		<td>
        	<input type="text" id="MontoIGV" value="<?=number_format($MontoIGV, 2, ',', '.')?>" style="width:150px; text-align:right;" class="disabled" disabled="disabled" />
        </td>
	</tr>
    <tr>
    	<td colspan="2" class="divFormCaption">Organismo</td>
        <td class="tagForm">Monto Total:</td>
		<td>
        	<input type="text" id="MontoTotal" value="<?=number_format($MontoTotal, 2, ',', '.')?>" style="width:150px; text-align:right; font-size:12px; font-weight:bold;" class="disabled" disabled="disabled" />
        </td>
	</tr>
    <tr>
		<td class="tagForm">* Plazo de Entrega:</td>
		<td>
        	<input type="text" id="PlazoEntrega" value="<?=$field_orden['PlazoEntrega']?>" maxlength="10" style="width:20px;" /> <em>(dias)</em>
        	<input type="text" id="FechaPrometida" value="<?=formatFechaDMA($field_orden['FechaPrometida'])?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" />
        </td>
        <td class="tagForm">Monto Pendiente:</td>
		<td>
        	<input type="text" id="MontoPendiente" value="<?=number_format($MontoPendiente, 2, ',', '.')?>" style="width:150px; text-align:right;" class="disabled" disabled="disabled" />
        </td>
	</tr>
    <tr>
		<td class="tagForm">Contacto:</td>
		<td>
        	<input type="text" id="NomContacto" value="<?=$field_orden['NomContacto']?>" maxlength="50" style="width:300px;" />
        </td>
        <td class="tagForm">Otros Cargos:</td>
		<td>
        	<input type="text" id="MontoOtros" value="<?=number_format($MontoOtros, 2, ',', '.')?>" style="width:150px; text-align:right;" class="disabled" disabled="disabled" />
        </td>
	</tr>
    <tr>
		<td class="tagForm">Fax:</td>
		<td>
        	<input type="text" id="FaxContacto" value="<?=$field_orden['FaxContacto']?>" maxlength="15" style="width:75px;" />
        </td>
    	<td colspan="2" class="divFormCaption">Informaci&oacute;n Adicional</td>
	</tr>
    <tr>
		<td class="tagForm">Entregar En:</td>
		<td>
        	<input type="text" id="Entregaren" value="<?=$field_orden['Entregaren']?>" maxlength="75" style="width:300px;" />
        </td>
        <td class="tagForm">Ingresado Por:</td>
        <td>
            <input type="hidden" id="PreparadaPor" value="<?=$_SESSION["CODPERSONA_ACTUAL"]?>" />
            <input type="text" id="NomPreparadaPor" value="<?=$_SESSION["NOMBRE_USUARIO_ACTUAL"]?>" style="width:245px;" class="disabled" disabled="disabled" />
        </td>
	</tr>
    <tr>
		<td class="tagForm">Direccion:</td>
		<td>
        	<input type="text" id="DirEntrega" value="<?=$field_orden['DirEntrega']?>" maxlength="75" style="width:300px;" />
        </td>
        <td class="tagForm">Revisado Por:</td>
        <td>
            <input type="hidden" id="RevisadoPor" />
            <input type="text" id="NomRevisadoPor" style="width:245px;" class="disabled" disabled="disabled" />
        </td>
	</tr>
    <tr>
		<td class="tagForm">Instr. de Entrega:</td>
		<td>
        	<input type="text" id="InsEntrega" value="<?=$field_orden['InsEntrega']?>" maxlength="75" style="width:300px;" />
        </td>
        <td class="tagForm">Aprobado Por:</td>
        <td>
            <input type="hidden" id="AprobadoPor" />
            <input type="text" id="NomAprobadoPor" style="width:245px;" class="disabled" disabled="disabled" />
        </td>
    </tr>
	<tr>
    	<td colspan="4" class="divFormCaption">Observaciones</td>
    </tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td colspan="3"><textarea id="Observaciones" style="width:95%; height:30px;"></textarea></td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n Detallada:</td>
		<td colspan="3"><textarea id="ObsDetallada" style="width:95%; height:50px;"></textarea></td>
	</tr>
	<tr>
		<td class="tagForm">Razon Rechazo:</td>
		<td colspan="3"><textarea id="MotRechazo" style="width:95%; height:30px;" disabled></textarea></td>
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
<input type="submit" value="Generar" />
<input type="button" value="Cancelar" onclick="this.form.submit();" />
</center>
<div style="width:1100px" class="divMsj">Campos Obligatorios *</div>
</div>
</form>

<div id="tab2" style="display:none;">
<form name="frm_detalles" id="frm_detalles">
<input type="hidden" name="sel_detalles" id="sel_detalles" />
<table width="1100" class="tblBotones">
	<tr>
    	<td class="gallery clearfix">
            <a id="aSelCCosto" href="../lib/listas/listado_centro_costos.php?filtrar=default&cod=CodCentroCosto&nom=NomCentroCosto&ventana=selListadoLista&seldetalle=sel_detalles&filtroDependencia=S&iframe=true&width=1050&height=500" rel="prettyPhoto[iframe2]" style="display:none;"></a>
            <input type="button" class="btLista" id="btSelCCosto" value="Sel. C.Costo" onclick="validarAbrirLista('sel_detalles', 'aSelCCosto');" />
        </td>
	</tr>
</table>
<center>
<div style="overflow:scroll; width:1100px; height:450px;">
<table width="2400" class="tblLista">
	<thead>
	<tr>
        <th scope="col" width="15">#</th>
        <th scope="col" width="80">C&oacute;digo</th>
        <th scope="col" width="400">Descripci&oacute;n</th>
        <th scope="col" width="35">Uni.</th>
        <th scope="col" width="50">Cant. Pedida</th>
        <th scope="col" width="100">P. Unit.</th>
        <th scope="col" width="50">% Desc.</th>
        <th scope="col" width="50">Desc. Fijo</th>
        <th scope="col" width="25">Exon.</th>
        <th scope="col" width="100">Monto P. Unit.</th>
        <th scope="col" width="100">Total</th>
        <th scope="col" width="60">F. Entrega</th>
        <th scope="col" width="50">Cant. Recib.</th>
        <th scope="col" width="35">C.C.</th>
        <th scope="col" width="90">Estado</th>
        <th scope="col" width="100">Partida</th>
        <th scope="col" width="100">Cta. Contable</th>
        <th scope="col">Observaciones</th>
    </tr>
    </thead>
    
    <tbody id="lista_detalles">
    <?
	$nrodetalles = 0;
	$sql = "SELECT
				c.Cantidad AS CantidadPedida,
				c.PrecioUnit,
				c.DescuentoPorcentaje,
				c.DescuentoFijo,
				c.FlagExonerado,
				c.Total,
				c.FechaLimite,
				rd.CodItem,
				rd.CommoditySub,
				rd.Descripcion,
				rd.CodUnidad,
				rd.CodCuenta,
				rd.cod_partida,
				rd.Comentarios,
				rd.CodRequerimiento,
				rd.Secuencia,
				c.CodProveedor
			FROM
				lg_cotizacion c
				INNER JOIN lg_requerimientosdet rd ON (c.CodRequerimiento = rd.CodRequerimiento AND
													   c.Secuencia = rd.Secuencia)
													   
			JOIN lg_informeadjudicacion as iad on iad.CodProveedor=c.CodProveedor
			JOIN lg_adjudicaciondetalle as ad on iad.CodAdjudicacion=ad.CodAdjudicacion and c.CodRequerimiento=ad.CodRequerimiento 
			and c.Secuencia=ad.Secuencia
																	   
			WHERE c.NroCotizacionProv = '".$registro."'
			ORDER BY c.Secuencia";
	$query_detalles = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field_detalles = mysql_fetch_array($query_detalles)) {
		$nrodetalles++;
		if ($field_detalles['CodItem'] != "") {
			$disabled_descripcion = "readonly";
			$Codigo = $field_detalles['CodItem'];
			$CommoditySub = "";
		} else {
			$disabled_descripcion = "";
			$CodItem = "";
			$Codigo = $field_detalles['CommoditySub'];
		}
		$disabled_descripcion = $disabled_ver;
		$PrecioUnitTotal = $field_detalles['PrecioUnit'] - $field_detalles['DescuentoFijo'] - ($field_detalles['PrecioUnit'] * $field_detalles['DescuentoPorcentaje'] / 100);
		$PrecioUnitTotal = $PrecioUnitTotal + ($PrecioUnitTotal * $FactorImpuesto / 100);
		?>
		<tr class="trListaBody" onclick="mClk(this, 'sel_detalles');" id="detalles_<?=$nrodetalles?>">
			<th align="center">
				<?=$nrodetalles?>
            </th>
			<td align="center">
            	<?=$Codigo?>
                <input type="hidden" name="CodItem" class="cell2" style="text-align:center;" value="<?=$field_detalles['CodItem']?>" readonly />
                <input type="hidden" name="CommoditySub" class="cell2" style="text-align:center;" value="<?=$field_detalles['CommoditySub']?>" readonly />
            </td>
			<td align="center">
				<textarea name="Descripcion" style="height:30px;" class="cell2" <?=$disabled_descripcion?> readonly><?=($field_detalles['Descripcion'])?></textarea>
			</td>
			<td align="center">
            	<input type="text" name="CodUnidad" value="<?=$field_detalles['CodUnidad']?>" class="cell2" style="text-align:center;" readonly="readonly" />		
            </td>
			<td align="center">
            	<input type="text" name="CantidadPedida" class="cell2" style="text-align:right;" value="<?=number_format($field_detalles['CantidadPedida'], 2, ',', '.')?>" onBlur="numeroBlur(this);" onFocus="numeroFocus(this);" onchange="setMontosOrdenCompra(this.form);" readonly />
            </td>
			<td align="center">
            	<input type="text" name="PrecioUnit" class="cell2" style="text-align:right;" value="<?=number_format($field_detalles['PrecioUnit'], 2, ',', '.')?>" onBlur="numeroBlur(this);" onFocus="numeroFocus(this);" onchange="setMontosOrdenCompra(this.form);" readonly />
            </td>
			<td align="center">
            	<input type="text" name="DescuentoPorcentaje" class="cell2" style="text-align:right;" value="<?=number_format($field_detalles['DescuentoPorcentaje'], 2, ',', '.')?>" onBlur="numeroBlur(this);" onFocus="numeroFocus(this);" onchange="setMontosOrdenCompra(this.form);" readonly />
            </td>
			<td align="center">
            	<input type="text" name="DescuentoFijo" class="cell" style="text-align:right;" value="<?=number_format($field_detalles['DescuentoFijo'], 2, ',', '.')?>" onBlur="numeroBlur(this);" onFocus="numeroFocus(this);" onchange="setMontosOrdenCompra(this.form);" readonly />
            </td>
			<td align="center">

            	<input type="checkbox" name="FlagExonerado" class="FlagExonerado" onchange="setMontosOrdenCompra(this.form);" <?=chkFlag($field_detalles['FlagExonerado'])?> <?=$dFlagExonerado?> disabled />
            </td>
			<td align="center">
            	<input type="text" name="PrecioUnitTotal" class="cell2" style="text-align:right;" value="<?=number_format($PrecioUnitTotal, 2, ',', '.')?>" readonly="readonly" />
            </td>
			<td align="center">
            	<input type="text" name="Total" class="cell2" style="text-align:right;" value="<?=number_format($field_detalles['Total'], 2, ',', '.')?>" readonly="readonly" />
            </td>
			<td align="center">
            	<input type="text" name="FechaPrometida" value="<?=formatFechaDMA($field_detalles['FechaLimite'])?>" maxlength="10" style="text-align:center;" class="datepicker cell2" onkeyup="setFechaDMA(this);" readonly />
            </td>
			<td align="right">
				<?=number_format($field_detalles['CantidadRecibida'], 2, ',', '.')?>
			</td>
			<td align="center">
				<input type="text" name="CodCentroCosto" id="CodCentroCosto_<?=$nrodetalles?>" class="cell2" style="text-align:center;" maxlength="4" value="<?=$_PARAMETRO['CCOSTOCOMPRA']?>" readonly />
				<input type="hidden" name="NomCentroCosto" id="NomCentroCosto_<?=$nrodetalles?>" />
			</td>
			<td align="center">
				<?=printValoresGeneral("ESTADO-COMPRA-DETALLE", $field_detalles['Estado'])?>
            </td>
			<td align="center">
				<?=$field_detalles['cod_partida']?>
				<input type="hidden" name="cod_partida" value="<?=$field_detalles['cod_partida']?>" />
			</td>
			<td align="center">
				<?=$field_detalles['CodCuenta']?>
				<input type="hidden" name="CodCuenta" value="<?=$field_detalles['CodCuenta']?>" />
			</td>
			<td align="center">
				<textarea name="Comentarios" style="height:30px;" class="cell2" readonly><?=($field_detalles['Comentarios'])?></textarea>
				<input type="hidden" name="CodRequerimiento" value="<?=$field_detalles['CodRequerimiento']?>" />
				<input type="hidden" name="Secuencia" value="<?=$field_detalles['Secuencia']?>" />
                
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

<div id="tab3" style="display:none;">
<center>
<div style="width:1100px;" class="divFormCaption">Cotizaciones</div>
<div style="overflow:scroll; width:1100px; height:450px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
        <th scope="col" width="100">C&oacute;digo</th>
        <th scope="col">Raz&oacute;n Social</th>
        <th scope="col" width="75">Cantidad</th>
        <th scope="col" width="100">Precio Unit.</th>
        <th scope="col" width="100">Monto Total</th>
        <th scope="col" width="30">Asig.</th>
        <th scope="col" width="75">Fecha</th>
        <th scope="col" width="100">Cotizaci&oacute;n #</th>
        <th scope="col" width="100">Invitaci&oacute;n #</th>
    </tr>
    </thead>
    
</table>
</div>
</center>
</div>

<div id="tab4" style="display:none;">
<center>
<div style="width:1100px;" class="divFormCaption">Avances</div>
<div style="overflow:scroll; width:1100px; height:450px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
        <th scope="col" width="100">Item</th>
        <th scope="col">Descripci&oacute;n</th>
        <th scope="col" width="75">Cantidad</th>
        <th scope="col" width="100">Transacci&oacute;n</th>
        <th scope="col" width="150">Almacen</th>
    </tr>
    </thead>
    
</table>
</div>
</center>
</div>

<div id="tab5" style="display:none;">
<center>
<div style="width:1100px;" class="divFormCaption">Distribuci&oacute;n Contable</div>
<div style="overflow:scroll; width:1100px; height:200px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
        <th scope="col" width="100">Cuenta</th>
        <th scope="col">Descripci&oacute;n</th>
        <th scope="col" width="100">Monto</th>
    </tr>
    </thead>
    
    <tbody id="lista_cuentas">
    </tbody>
</table>
</div>

<div style="width:1100px;" class="divFormCaption">Distribuci&oacute;n Presupuestaria</div>
<form name="frm_partidas" id="frm_partidas">
<table width="1100" class="tblBotones">
	<tr>
    	<td width="35"><div style="background-color:#F8637D; width:25px; height:20px;"></div></td>
        <td>Sin disponibilidad presupuestaria</td>
    	<td width="35"><div style="background-color:#D0FDD2; width:25px; height:20px;"></div></td>
        <td>Disponibilidad presupuestaria</td>
    	<td width="35"><div style="background-color:#FFC; width:25px; height:20px;"></div></td>
        <td>Disponibilidad presupuestaria (Tiene ordenes pendientes)</td>
		<td align="right">
			<input type="button" value="Disponibilidad Presupuestaria" onclick="verDisponibilidadPresupuestaria();" />
		</td>
	</tr>
</table>
<div style="overflow:scroll; width:1100px; height:200px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
        <th scope="col" width="100">Partida</th>
        <th scope="col">Descripci&oacute;n</th>
        <th scope="col" width="100">Monto</th>
    </tr>
    </thead>
    
    <tbody id="lista_partidas">
    </tbody>
</table>
</div>
</form>
</center>
</div>

<script language="javascript">
$(document).ready(function(){
	setMontosOrdenCompra(document.getElementById("frm_detalles"));
});
</script>
