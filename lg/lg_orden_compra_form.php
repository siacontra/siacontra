<?php
if ($opcion == "nuevo") {
	$accion = "nuevo";
	$titulo = "Nueva Orden de Compra";
	$label_submit = "Guardar";
	//	valores default
	$field_orden['Estado'] = "PR";
	$field_orden['CodOrganismo'] = $_SESSION["ORGANISMO_ACTUAL"];
	$field_orden['PreparadaPor'] = $_SESSION["CODPERSONA_ACTUAL"];
	$field_orden['NomPreparadaPor'] = $_SESSION["NOMBRE_USUARIO_ACTUAL"];
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
	//	default
	$disabled_anular = "disabled";
	$display_rechazar = "visibility:hidden;";
	$disabled_item = "disabled";
	$disabled_commodity = "disabled";
}
elseif ($opcion == "modificar" || $opcion == "ver" || $opcion == "revisar" || $opcion == "aprobar" || $opcion == "anular" || $opcion == "cerrar") {
	list($Anio, $CodOrganismo, $NroOrden) = split("[.]", $registro);
	//	consulto datos generales	
	$sql = "SELECT
				oc.*,
				mp.CodTipoServicio,
				ts.Descripcion AS NomTipoServicio,
				i.FactorPorcentaje,
				mp1.NomCompleto AS NomPreparadaPor,
				mp2.NomCompleto AS NomRevisadaPor,
				mp3.NomCompleto AS NomAprobadaPor,
				me1.CodEmpleado AS CodPreparadaPor,
				me2.CodEmpleado AS CodRevisadaPor,
				me3.CodEmpleado AS CodAprobadaPor
			FROM
				lg_ordencompra oc
				INNER JOIN mastproveedores mp ON (oc.CodProveedor = mp.CodProveedor)
				INNER JOIN masttiposervicio ts ON (mp.CodTipoServicio = ts.CodTipoServicio)
				LEFT JOIN masttiposervicioimpuesto tsi ON (ts.CodTipoServicio = tsi.CodTipoServicio)
				LEFT JOIN mastimpuestos i ON (tsi.CodImpuesto = i.CodImpuesto)
				LEFT JOIN mastpersonas mp1 ON (oc.PreparadaPor = mp1.CodPersona)
				LEFT JOIN mastpersonas mp2 ON (oc.RevisadaPor = mp2.CodPersona)
				LEFT JOIN mastpersonas mp3 ON (oc.AprobadaPor = mp3.CodPersona)
				LEFT JOIN mastempleado me1 ON (me1.CodPersona = mp1.CodPersona)
				LEFT JOIN mastempleado me2 ON (me2.CodPersona = mp2.CodPersona)
				LEFT JOIN mastempleado me3 ON (me3.CodPersona = mp3.CodPersona)
			WHERE
				oc.Anio = '".$Anio."' AND
				oc.CodOrganismo = '".$CodOrganismo."' AND
				oc.NroOrden = '".$NroOrden."'";
	$query_orden = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_orden)) $field_orden = mysql_fetch_array($query_orden);
	
	if ($opcion == "modificar") {
		$titulo = "Modificar Orden de Compra";
		$accion = "modificar";
		$disabled_modificar = "disabled";
		$display_modificar = "display:none;";
		$label_submit = "Modificar";
		$display_rechazar = "display:none;";
		$display_rechazar = "visibility:hidden;";
	}
	
	elseif ($opcion == "ver") {
		$titulo = "Ver Orden de Compra";
		$disabled_ver = "disabled";
		$display_ver = "display:none;";
		$display_submit = "display:none;";
		$disabled_modificar = "disabled";
		$display_modificar = "display:none;";
		$disabled_anular = "disabled";
		$display_rechazar = "display:none;";
		$display_rechazar = "visibility:hidden;";
	}
	
	elseif ($opcion == "revisar") {
		$titulo = "Revisar Orden de Compra";
		$accion = "revisar";
		$label_submit = "Revisar";
		$disabled_ver = "disabled";
		$display_ver = "display:none;";
		$disabled_modificar = "disabled";
		$display_modificar = "display:none;";
		$disabled_anular = "disabled";
		$display_rechazar = "display:none;";
		$display_rechazar = "visibility:hidden;";
	}
	
	elseif ($opcion == "aprobar") {
		$titulo = "Aprobar Orden de Compra";
		$accion = "aprobar";
		$label_submit = "Aprobar";
		$disabled_ver = "disabled";
		$display_ver = "display:none;";
		$disabled_modificar = "disabled";
		$display_modificar = "display:none;";
	}
	
	elseif ($opcion == "anular") {
		$titulo = "Anular Orden de Compra";
		$accion = "anular";
		$label_submit = "Anular";
		$disabled_ver = "disabled";
		$display_ver = "display:none;";
		$disabled_modificar = "disabled";
		$display_modificar = "display:none;";
		$display_rechazar = "display:none;";
		$display_rechazar = "visibility:hidden;";
	}
	
	elseif ($opcion == "cerrar") {
		$titulo = "Cerrar Orden de Compra";
		$accion = "cerrar";
		$label_submit = "Cerrar";
		$disabled_ver = "disabled";
		$display_ver = "display:none;";
		$disabled_modificar = "disabled";
		$display_modificar = "display:none;";
		$display_proveedor = "visibility:hidden;";
		$display_rechazar = "display:none;";
		$display_rechazar = "visibility:hidden;";
	}
	
	$disabled_item = $disabled_ver;
	$disabled_commodity = $disabled_ver;
	if (!afectaTipoServicio($field_orden['CodTipoServicio'])) { $dFlagExonerado = "disabled"; $cFlagExonerado = "checked"; }
	$FactorImpuesto = getPorcentajeIVA($field_orden['CodTipoServicio']);
}
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
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

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=<?=$origen?>" method="POST" onsubmit="return orden_compra(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="fCodOrganismo" id="fCodOrganismo" value="<?=$fCodOrganismo?>" />
<input type="hidden" name="fClasificacion" id="fClasificacion" value="<?=$fClasificacion?>" />
<input type="hidden" name="fCodProveedor" id="fCodProveedor" value="<?=$fCodProveedor?>" />
<input type="hidden" name="fEstado" id="fEstado" value="<?=$fEstado?>" />
<input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />
<input type="hidden" name="fFechaPreparaciond" id="fFechaPreparaciond" value="<?=$fFechaPreparaciond?>" />
<input type="hidden" name="fFechaPreparacionh" id="fFechaPreparacionh" value="<?=$fFechaPreparacionh?>" />
<input type="hidden" id="Anio" value="<?=$field_orden['Anio']?>" />
<input type="hidden" id="NroOrden" value="<?=$field_orden['NroOrden']?>" />
<input type="hidden" id="TipoClasificacion" value="<?=$field_orden['TipoClasificacion']?>" />

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
			<input type="hidden" id="Estado" value="<?=$field_orden['Estado']?>" />
			<input type="text" style="width:100px;" class="codigo" value="<?=printValoresGeneral("ESTADO-COMPRA", $field_orden['Estado'])?>" disabled="disabled" />
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Proveedor:</td>
		<td class="gallery clearfix">
            <input type="text" id="CodProveedor" style="width:50px;" value="<?=$field_orden['CodProveedor']?>" disabled="disabled" />
			<input type="text" id="NomProveedor" style="width:235px;" value="<?=$field_orden['NomProveedor']?>" disabled="disabled" />
            <a href="../lib/listas/listado_personas.php?filtrar=default&cod=CodProveedor&nom=NomProveedor&EsProveedor=S&ventana=selListadoOrdenCompraPersona&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]" id="btProveedor" style=" <?=$display_modificar?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
		<td class="tagForm">N&uacute;mero:</td>
		<td>
        	<input type="text" id="NroInterno" style="width:100px;" class="codigo" value="<?=$field_orden['NroInterno']?>" disabled="disabled" />
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Tipo de Servicio:</td>
		<td>
        	<input type="hidden" id="FactorImpuesto" value="<?=$FactorImpuesto?>" />
            <select id="CodTipoServicio" style="width:150px;" <?=$disabled_ver?>>
                <?=loadSelect("masttiposervicio", "CodTipoServicio", "Descripcion", $field_orden['CodTipoServicio'], 1)?>
            </select>
        </td>
    	<td colspan="2" class="divFormCaption">Monto de la Compra</td>
	</tr>
    <tr>
		<td class="tagForm">* Forma de Pago:</td>
		<td>
            <select id="CodFormaPago" style="width:150px;" <?=$disabled_ver?>>
                <?=loadSelect("mastformapago", "CodFormaPago", "Descripcion", $field_orden['CodFormaPago'], 0)?>
            </select>
        </td>
        <td class="tagForm">Monto Afecto:</td>
		<td>
        	<input type="text" id="MontoAfecto" value="<?=number_format($field_orden['MontoAfecto'],  4, '.', '')?>" style="width:150px; text-align:right;" class="disabled" disabled="disabled" />
        </td>
	</tr>
    <tr>
		<td class="tagForm">* Clasificaci&oacute;n:</td>
		<td>
			<select id="Clasificacion" style="width:150px;" <?=$disabled_ver?>>
				<?=loadSelectValores("COMPRA-CLASIFICACION", $field_orden{'Clasificacion'}, 0)?>
			</select>
		</td>
        <td class="tagForm">Monto No Afecto:</td>
		<td>
        	<input type="text" id="MontoNoAfecto" value="<?=number_format($field_orden['MontoNoAfecto'],  4, '.', '')?>" style="width:150px; text-align:right;" class="disabled" disabled="disabled" />
        </td>
	</tr>
    <tr>
		<td class="tagForm">* Almacen Entrega:</td>
		<td>
            <select id="CodAlmacen" style="width:175px;" <?=$disabled_ver?>>
                <?=loadSelect("lg_almacenmast", "CodAlmacen", "Descripcion", $field_orden['CodAlmacen'], 0)?>
            </select>
        </td>
        <td class="tagForm">Monto Bruto:</td>
		<td>
        	<input type="text" id="MontoBruto" value="<?=number_format($field_orden['MontoBruto'],  4, '.', '')?>" style="width:150px; text-align:right;" class="disabled" disabled="disabled" />
        </td>
	</tr>
    <tr>
		<td class="tagForm">* Almacen Ingreso:</td>
		<td>
            <select id="CodAlmacenIngreso" style="width:175px;" <?=$disabled_ver?>>
                <?=loadSelect("lg_almacenmast", "CodAlmacen", "Descripcion", $field_orden['CodAlmacenIngreso'], 0)?>
            </select>
        </td>
        <td class="tagForm">(+/-) Impuestos:</td>
		<td>
        	<input type="text" id="MontoIGV" value="<?=number_format($field_orden['MontoIGV'],  4, '.', '')?>" style="width:150px; text-align:right;" class="disabled" onfocus="numeroFocus(this);" onblur="numeroBlur(this);" onchange="setNuevoMontoIvaOC();" <?=$disabled_ver?> />
        </td>
	</tr>
    <tr>
    	<td colspan="2" class="divFormCaption">Organismo</td>
        <td class="tagForm">Monto Total:</td>
		<td>
        	<input type="text" id="MontoTotal" value="<?=number_format($field_orden['MontoTotal'],  4, '.', '')?>" style="width:150px; text-align:right; font-size:12px; font-weight:bold;" class="disabled" disabled="disabled" />
        </td>
	</tr>
    <tr>
		<td class="tagForm">* Plazo de Entrega:</td>
		<td>
        	<input type="text" id="PlazoEntrega" value="<?=$field_orden['PlazoEntrega']?>" maxlength="10" style="width:20px;" <?=$disabled_ver?> /> <em>(dias)</em>
        	<input type="text" id="FechaPrometida" value="<?=formatFechaDMA($field_orden['FechaPrometida'])?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" <?=$disabled_ver?> />
        </td>
        <td class="tagForm">Monto Pendiente:</td>
		<td>
        	<input type="text" id="MontoPendiente" value="<?=number_format($field_orden['MontoPendiente'],  4, '.', '')?>" style="width:150px; text-align:right;" class="disabled" disabled="disabled" />
        </td>
	</tr>
    <tr>
		<td class="tagForm">Contacto:</td>
		<td>
        	<input type="text" id="NomContacto" value="<?=$field_orden['NomContacto']?>" maxlength="50" style="width:300px;" <?=$disabled_ver?> />
        </td>
        <td class="tagForm">Otros Cargos:</td>
		<td>
        	<input type="text" id="MontoOtros" value="<?=number_format($field_orden['MontoOtros'],  4, '.', '')?>" style="width:150px; text-align:right;" class="disabled" disabled="disabled" />
        </td>
	</tr>
    <tr>
		<td class="tagForm">Fax:</td>
		<td>
        	<input type="text" id="FaxContacto" value="<?=$field_orden['FaxContacto']?>" maxlength="15" style="width:75px;" <?=$disabled_ver?> />
        </td>
    	<td colspan="2" class="divFormCaption">Informaci&oacute;n Adicional</td>
	</tr>
    <tr>
		<td class="tagForm">Entregar En:</td>
		<td>
        	<input type="text" id="Entregaren" value="<?=$field_orden['Entregaren']?>" maxlength="75" style="width:300px;" <?=$disabled_ver?> />
        </td>
        <td class="tagForm">Ingresado Por:</td>
        <td>
            <input type="hidden" id="PreparadaPor" value="<?=$field_orden['PreparadaPor']?>" />
            <input type="text" id="NomPreparadaPor" value="<?=($field_orden['NomPreparadaPor'])?>" style="width:245px;" class="disabled" disabled="disabled" />
        </td>
	</tr>
    <tr>
		<td class="tagForm">Direccion:</td>
		<td>
        	<input type="text" id="DirEntrega" value="<?=$field_orden['DirEntrega']?>" maxlength="75" style="width:300px;" <?=$disabled_ver?> />
        </td>
        <td class="tagForm">Revisado Por:</td>
        <td>
            <input type="hidden" id="RevisadoPor" value="<?=$field_orden['RevisadaPor']?>" />
            <input type="text" id="NomRevisadoPor" value="<?=($field_orden['NomRevisadaPor'])?>" style="width:245px;" class="disabled" disabled="disabled" />
        </td>
	</tr>
    <tr>
		<td class="tagForm">Instr. de Entrega:</td>
		<td>
        	<input type="text" id="InsEntrega" value="<?=$field_orden['InsEntrega']?>" maxlength="75" style="width:300px;" <?=$disabled_ver?> />
        </td>
        <td class="tagForm">Aprobado Por:</td>
        <td>
            <input type="hidden" id="AprobadoPor" value="<?=$field_orden['AprobadaPor']?>" />
            <input type="text" id="NomAprobadoPor" value="<?=($field_orden['NomAprobadaPor'])?>" style="width:245px;" class="disabled" disabled="disabled" />
        </td>
    </tr>
	<tr>
    	<td colspan="4" class="divFormCaption">Observaciones</td>
    </tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n:</td>
		<td colspan="3"><textarea id="Observaciones" style="width:95%; height:250px;" <?=$disabled_ver?> onkeyup="limitarCaracteres(this.id,3000);"><?=($field_orden['Observaciones'])?></textarea></td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n Detallada:</td>
		<td colspan="3"><textarea id="ObsDetallada" style="width:95%; height:50px;" <?=$disabled_ver?>><?=($field_orden['ObsDetallada'])?></textarea></td>
	</tr>
	<tr>
		<td class="tagForm">Razon Rechazo:</td>
		<td colspan="3"><textarea id="MotRechazo" style="width:95%; height:30px;" <?=$disabled_anular?>><?=($field_orden['MotRechazo'])?></textarea></td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td>
			<input type="text" size="30" class="disabled" value="<?=$field_orden['UltimoUsuario']?>" disabled="disabled" />
			<input type="text" size="25" class="disabled" value="<?=$field_orden['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>
</table>
<center> 
<input type="submit" value="<?=$label_submit?>" style=" <?=$display_submit?>" />
<input type="button" value="Cancelar" onclick="this.form.submit();" />
<input type="button" value="Rechazar" onclick="orden_compra_rechazar(this.form);" style=" <?=$display_rechazar?>" />
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
            <input type="button" class="btLista" id="btSelCCosto" value="Sel. C.Costo" onclick="validarAbrirLista('sel_detalles', 'aSelCCosto');" <?=$disabled_ver?> />
        </td>
		<td align="right" class="gallery clearfix">
        	<a id="aItem" href="../lib/listas/listado_items.php?filtrar=default&ventana=orden_compra_detalles_insertar&iframe=true&width=950&height=525" rel="prettyPhoto[iframe3]" style="display:none;"></a>
        	<a id="aCommodity" href="../lib/listas/listado_commodities.php?filtrar=default&ventana=orden_compra_detalles_insertar&PorClasificacion=S&Actualizar=S&iframe=true&width=950&height=525" rel="prettyPhoto[iframe4]" style="display:none;"></a>
			<input type="button" class="btLista" value="Item" id="btItem" onclick="document.getElementById('aItem').click();" <?=$disabled_item?> />
			<input type="button" class="btLista" value="Commodity" id="btCommodity" onclick="document.getElementById('aCommodity').click();" <?=$disabled_commodity?> />
			<input type="button" class="btLista" value="Borrar" onclick="quitarLineaOrdenCompra(this, 'detalles', this.form);" <?=$disabled_ver?> />
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
	$sql = "SELECT *
			FROM lg_ordencompradetalle
			WHERE
				Anio = '".$Anio."' AND
				CodOrganismo = '".$CodOrganismo."' AND
				NroOrden = '".$NroOrden."'
			ORDER BY Secuencia";
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
				<textarea name="Descripcion" style="height:30px;" class="cell" <?=$disabled_descripcion?>><?=($field_detalles['Descripcion'])?></textarea>
			</td>
			<td align="center">
            	<input type="text" name="CodUnidad" value="<?=$field_detalles['CodUnidad']?>" class="cell2" style="text-align:center;" readonly="readonly" <?=$disabled_ver?> />		
            </td>
			<td align="center">
            	<input type="text" name="CantidadPedida" class="cell" style="text-align:right;" value="<?=number_format($field_detalles['CantidadPedida'], 4, '.', '')?>" onchange="setMontosOrdenCompra(this.form);" <?=$disabled_ver?> />
            </td>
			<td align="center">
            	<input type="text" name="PrecioUnit" class="cell" style="text-align:right;" value="<?=number_format($field_detalles['PrecioUnit'], 4, '.', '')?>"  onchange="setMontosOrdenCompra(this.form);" <?=$disabled_ver?> />
            </td>
			<td align="center">
            	<input type="text" name="DescuentoPorcentaje" class="cell" style="text-align:right;" value="<?=number_format($field_detalles['DescuentoPorcentaje'],  4, '.', '')?>"  onchange="setMontosOrdenCompra(this.form);" <?=$disabled_ver?> />
            </td>
			<td align="center">
            	<input type="text" name="DescuentoFijo" class="cell" style="text-align:right;" value="<?=number_format($field_detalles['DescuentoFijo'],  4, '.', '')?>" onchange="setMontosOrdenCompra(this.form);" <?=$disabled_ver?> />
            </td>
			<td align="center">
            	<input type="checkbox" name="FlagExonerado" class="FlagExonerado" onchange="setMontosOrdenCompra(this.form);" <?=chkFlag($field_detalles['FlagExonerado'])?> <?=$disabled_ver?> <?=$dFlagExonerado?> />
            </td>
			<td align="center">
            	<input type="text" name="PrecioUnitTotal" class="cell2" style="text-align:right;" value="<?=number_format($PrecioUnitTotal, 4, '.', '')?>"  readonly="readonly" <?=$disabled_ver?> />
            </td>
			<td align="center">
            	<input type="text" name="Total" class="cell2" style="text-align:right;" value="<?=number_format($field_detalles['Total'],  4, '.', '')?>"  readonly="readonly" <?=$disabled_ver?> />
            </td>
			<td align="center">
            	<input type="text" name="FechaPrometida" value="<?=formatFechaDMA($field_detalles['FechaPrometida'])?>" maxlength="10" style="text-align:center;" class="datepicker cell" onkeyup="setFechaDMA(this);" <?=$disabled_ver?> />
            </td>
			<td align="right">
				<?=number_format($field_detalles['CantidadRecibida'],  4, '.', '')?>
			</td>
			<td align="center">
				<input type="text" name="CodCentroCosto" id="CodCentroCosto_<?=$nrodetalles?>" class="cell" style="text-align:center;" maxlength="4" value="<?=$field_detalles['CodCentroCosto']?>" <?=$disabled_ver?> />
				<input type="hidden" name="NomCentroCosto" id="NomCentroCosto_<?=$nrodetalles?>" value="<?=($field_detalles['NomCentroCosto'])?>" />
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
				<textarea name="Comentarios" style="height:30px;" class="cell" <?=$disabled_ver?>><?=($field_detalles['Comentarios'])?></textarea>
				<input type="hidden" name="CodRequerimiento" />
				<input type="hidden" name="Secuencia" />
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
<div style="overflow:scroll; width:1100px; height:200px;">
<table width="1500" class="tblLista">
	<thead>
	<tr>
        <th scope="col" width="100">C&oacute;digo</th>
        <th scope="col">Raz&oacute;n Social</th>
        <th scope="col" width="75">Cantidad</th>
        <th scope="col" width="100">Precio Unit.</th>
        <th scope="col" width="100">Precio Unit./Imp.</th>
        <th scope="col" width="100">Monto Total</th>
        <th scope="col" width="30">Asig.</th>
        <th scope="col" width="75">Dias Entrega</th>
        <th scope="col" width="75">Fecha Entrega</th>
        <th scope="col" width="100">Cotizaci&oacute;n #</th>
        <th scope="col" width="300">Observaciones</th>
    </tr>
    </thead>
    
    <tbody>
    <?
	$sql = "SELECT
				c.*,
				rd.CodItem,
				rd.CommoditySub,
				rd.Descripcion
			FROM
				lg_cotizacion c
				INNER JOIN lg_requerimientosdet rd ON (c.CodRequerimiento = rd.CodRequerimiento AND 
													   c.Secuencia = rd.Secuencia)
				INNER JOIN lg_ordencompradetalle ocd ON (ocd.Anio = '".$Anio."' AND 
													     ocd.CodOrganismo = '".$CodOrganismo."' AND 
													     ocd.NroOrden = '".$NroOrden."' AND 
													     ocd.Secuencia = rd.Secuencia)
			WHERE
				c.CodOrganismo = '".$CodOrganismo."' AND
				c.CodRequerimiento IN (SELECT distinct rd2.CodRequerimiento 
									   FROM 
											lg_requerimientosdet rd2
											INNER JOIN lg_requerimientos r2 ON (rd2.CodOrganismo = r2.CodOrganismo AND
																				rd2.CodRequerimiento = r2.CodRequerimiento)
									   WHERE 
											rd2.CodOrganismo = '".$CodOrganismo."' AND 
											rd2.NroOrden = '".$NroOrden."' AND rd2.NroOrden is not null AND
											r2.Clasificacion <> 'SER'
									  /*GROUP BY rd2.CodRequerimiento*/)
			ORDER BY CodItem, CommoditySub, CodProveedor";
	$query_cotizaciones = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field_cotizaciones = mysql_fetch_array($query_cotizaciones)) {
		if ($agrupa != $field_cotizaciones['Descripcion']) {
			$agrupa = $field_cotizaciones['Descripcion'];
			?>
			<tr class="trListaBody2">
                <td align="center"><?=$field_cotizaciones['CommoditySub']?></td>
                <td colspan="4"><?=($field_cotizaciones['Descripcion'])?></td>
			</tr>
			<?
		}
		?>
        <tr class="trListaBody">
            <td align="right"><?=$field_cotizaciones['CodProveedor']?></td>
            <td><?=($field_cotizaciones['NomProveedor'])?></td>
            <td align="right"><?=number_format($field_cotizaciones['Cantidad'], 4, '.', '')?></td>
            <td align="right"><?=number_format($field_cotizaciones['PrecioUnit'],  4, '.', '')?></td>
            <td align="right"><?=number_format($field_cotizaciones['PrecioUnitIva'],  4, '.', '')?></td>
            <td align="right"><?=number_format($field_cotizaciones['Total'],  4, '.', '')?></td>
            <td align="center"><?=printFlag($field_cotizaciones['FlagAsignado'])?></td>
            <td align="center"><?=$field_cotizaciones['DiasEntrega']?></td>
            <td align="center"><?=formatFechaDMA($field_cotizaciones['FechaEntrega'])?></td>
            <td align="center"><?=$field_cotizaciones['NumeroCotizacion']?></td>
            <td title="<?=$field_cotizaciones['Observaciones']?>"><?=($comentarios)?></td>
		</tr>
        <?
    }
	?>
    </tbody>
</table>
</div>
<div style="width:1100px;" class="divFormCaption">Requerimientos</div>
<div style="overflow:scroll; width:1100px; height:200px;">
<table width="1500" class="tblLista">
	<thead>
	<tr>
        <th scope="col" width="75">Requerimiento</th>
        <th scope="col" width="50">Req. Linea</th>
        <th scope="col" width="75">Cantidad</th>
        <th scope="col" width="75">Fecha Pedida</th>
        <th scope="col" width="75">Fecha Aprobaci&oacute;n</th>
        <th scope="col">Comentarios</th>
        <th scope="col" width="300">Preparado Por</th>
    </tr>
    </thead>
    
    <tbody>
    <?
	$sql = "SELECT 
				rd.CodRequerimiento,
				rd.Secuencia,
				rd.CantidadOrdenCompra,
				rd.Comentarios,
				r.FechaPreparacion,
				r.FechaAprobacion,
				mp.NomCompleto AS PreparadoPor
			FROM 
				lg_requerimientosdet rd
				INNER JOIN lg_requerimientos r ON (rd.CodRequerimiento = r.CodRequerimiento)
				INNER JOIN mastpersonas mp ON (r.PreparadaPor = mp.CodPersona)
			WHERE
				rd.CodOrganismo = '".$CodOrganismo."' AND
				rd.NroOrden = '".$NroOrden."' AND
				r.Clasificacion <> 'SER'
			ORDER BY Secuencia";
	$query_requerimientosdet = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field_requerimientosdet = mysql_fetch_array($query_requerimientosdet)) {
	?>
	<tr class="trListaBody">
		<td align="center"><?=$field_requerimientosdet['CodRequerimiento']?></td>
		<td align="center"><?=$field_requerimientosdet['Secuencia']?></td>
		<td align="right"><?=number_format($field_requerimientosdet['CantidadOrdenCompra'],  4, '.', '')?></td>
		<td align="center"><?=formatFechaDMA($field_requerimientosdet['FechaPreparacion'])?></td>
		<td align="center"><?=formatFechaDMA($field_requerimientosdet['FechaAprobacion'])?></td>
		<td title="<?=$field_requerimientosdet['Comentarios']?>"><?=($comentarios)?></td>
		<td><?=($field_requerimientosdet['PreparadoPor'])?></td>
	</tr>
	<?
    }
	?>
    </tbody>
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
    
    <tbody>
    <?
	$sql = "SELECT
				t.CodTransaccion,
				td.CodDocumento,
				td.NroDocumento,
				td.CodItem,
				td.Descripcion,
				td.CantidadRecibida,
				a.Descripcion AS NomAlmacen
			FROM 
				lg_transacciondetalle td 
				INNER JOIN lg_transaccion t ON (td.CodOrganismo = t.CodOrganismo AND
												td.CodDocumento = t.CodDocumento AND
												td.NroDocumento = t.NroDocumento AND
												td.ReferenciaCodDocumento = 'RQ')
				LEFT JOIN lg_almacenmast a ON (t.CodAlmacen = a.CodAlmacen)
			WHERE td.ReferenciaNroDocumento = '".$CodRequerimiento."'";
	$query_avances = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field_avances = mysql_fetch_array($query_avances)) {
		?>
		<tr class="trListaBody">
			<td align="center"><?=$field_avances['CodItem']?></td>
			<td><?=($field_avances['Descripcion'])?></td>
			<td align="right"><?=number_format($field_avances['CantidadRecibida'],  4, '.', '')?></td>
			<td align="center"><?=$field_avances['CodDocumento']?>-<?=$field_avances['NroDocumento']?></td>
			<td align="center"><?=($field_avances['NomAlmacen'])?></td>
		</tr>
		<?
	}
	?>
    </tbody>
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
    <?
	$nrocuentas = 0;
	$sql = "(SELECT 
				do.CodCuenta AS Codigo,
				pc.Descripcion,
				SUM(do.CantidadPedida * do.PrecioUnit) AS Monto
			 FROM
				lg_ordencompradetalle do
				INNER JOIN ac_mastplancuenta pc ON (do.CodCuenta = pc.CodCuenta)
			 WHERE
				do.Anio = '".$Anio."' AND
				do.CodOrganismo = '".$CodOrganismo."' AND
				do.NroOrden = '".$NroOrden."'
			 GROUP BY Codigo)
			UNION
			(SELECT 
				os.CodCuenta AS Codigo, 
				pc.Descripcion,
				os.MontoIGV AS Monto
			 FROM 
				lg_ordencompra os
				INNER JOIN ac_mastplancuenta pc ON (os.CodCuenta = pc.CodCuenta)
			 WHERE
				os.Anio = '".$Anio."' AND
				os.CodOrganismo = '".$CodOrganismo."' AND
				os.NroOrden = '".$NroOrden."' AND
				os.MontoIGV <> 0
			 GROUP BY Codigo)
			ORDER BY Codigo";
	$query_cuentas = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field_cuentas = mysql_fetch_array($query_cuentas)) {
		?>
        <tr class="trListaBody">
			<td align="center"><?=$field_cuentas['Codigo']?></td>
			<td><?=($field_cuentas['Descripcion'])?></td>
			<td align="right"><?=number_format($field_cuentas['Monto'], 4, '.', '')?></td>
		</tr>
        <?
		
	}
	?>
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
    <?
	$nropartidas = 0;
	$sql = "SELECT 
				do.cod_partida, 
				pc.denominacion,
				do.Monto,
				pc.CodCuenta
			FROM
				lg_distribucioncompromisos do
				INNER JOIN pv_partida pc ON (do.cod_partida = pc.cod_partida)
			WHERE
				do.Anio = '".$Anio."' AND
				do.CodOrganismo = '".$CodOrganismo."' AND
				do.CodProveedor = '".$field_orden['CodProveedor']."' AND
				do.CodTipoDocumento = 'OC' AND
				do.NroDocumento = '".$NroOrden."'
			ORDER BY cod_partida";
	$query_partidas = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	while ($field_partidas = mysql_fetch_array($query_partidas)) {
		list($MontoAjustado, $MontoComprometido, $MontoPendiente) = disponibilidadPartida($Anio, $CodOrganismo, $field_partidas['cod_partida']);
		if ($field_orden['Estado'] == "PR") $MontoDisponible = $MontoAjustado - $MontoComprometido;
		else $MontoDisponible = $MontoAjustado - $MontoComprometido + $field_partidas['Monto'];
		//	valido
		if ($MontoDisponible < $field_partidas['Monto']) $style = "style='font-weight:bold; background-color:#F8637D;'";
		elseif ($MontoDisponible < ($field_partidas['Monto'] + $MontoPendiente) && $field_orden['Estado'] == "PR") $style = "style='font-weight:bold; background-color:#FFC;'";
		else $style = "style='font-weight:bold; background-color:#D0FDD2;'";
        ?>
        <tr class="trListaBody" <?=$style?>>
            <td align="center">
                <input type="hidden" name="cod_partida" value="<?=$field_partidas['cod_partida']?>" />
                <input type="hidden" name="CodCuenta" value="<?=$field_partidas['CodCuenta']?>" />
                <input type="hidden" name="Monto" value="<?=$field_partidas['Monto']?>" />
                <input type="hidden" name="MontoDisponible" value="<?=$MontoDisponible?>" />
                <input type="hidden" name="MontoPendiente" value="<?=$MontoPendiente?>" />
				<?=$field_partidas['cod_partida']?>
            </td>
            <td>
				<?=($field_partidas['denominacion'])?>
            </td>
            <td align="right"><?=number_format($field_partidas['Monto'],  4, '.', '')?></td>
        </tr>
        <?
    }
	?>
    </tbody>
</table>
</div>
</form>
</center>
</div>
