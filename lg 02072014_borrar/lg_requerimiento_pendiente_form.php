<?php
//	consulto clasificacion
$sql = "SELECT ReqAlmacenCompra, TipoRequerimiento, CodAlmacen, FlagCajaChica
		FROM lg_clasificacion
		WHERE Clasificacion = '".$_PARAMETRO["REQRAU"]."'";
$query_clasificacion = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query_clasificacion) != 0) $field_clasificacion = mysql_fetch_array($query_clasificacion);
##
$FechaRequerida = formatFechaAMD(getFechaFin(formatFechaDMA(substr($Ahora, 0, 10)), $_PARAMETRO['DIASDEFREQ']));
if ($field_clasificacion['TipoRequerimiento'] == "01") $disabled_commodity = "disabled"; else $disabled_item = "disabled";
$CodDependencia = getValorCampo("ac_mastcentrocosto", "CodCentroCosto", "CodDependencia", $_PARAMETRO["CCOSTOCOMPRA"]);
$CodOrganismo = getValorCampo("mastdependencias", "CodDependencia", "CodOrganismo", $CodDependencia);
##
$Comentarios = "";
$detalle = split(";char:tr;", $detalles);
foreach ($detalle as $linea) {
	list($_Requerimiento, $_CodItem, $_CommoditySub, $_Descripcion, $_CodUnidad, $_CodCentroCosto, $_FlagExonerado, $_CantidadPedida, $_CodCuenta, $_cod_partida, $_Comentarios) = split(";char:td;", $linea);
	$i++;
	if ($_Grupo != $_Comentarios && $_Comentarios != "") {
		$_Grupo = $_Comentarios;
		if ($i == 1) $Comentarios .= $_Comentarios;
		else $Comentarios .= " / ".$_Comentarios;
	}
}
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Generar Requerimiento</td>
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
            <li id="li5" onclick="currentTab('tab', this);">
            	<a href="#" onclick="mostrarTabDistribucionRequerimiento();">Distribuci&oacute;n Presupuestaria/Contables</a>
            </li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=lg_requerimiento_pendiente_lista" method="POST" onsubmit="return requerimiento(this, 'nuevo');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="fCodOrganismo" id="fCodOrganismo" value="<?=$fCodOrganismo?>" />
<input type="hidden" name="fTipoClasificacion" id="fTipoClasificacion" value="<?=$fTipoClasificacion?>" />
<input type="hidden" name="fCodDependencia" id="fCodDependencia" value="<?=$fCodDependencia?>" />
<input type="hidden" name="fClasificacion" id="fClasificacion" value="<?=$fClasificacion?>" />
<input type="hidden" name="fCodCentroCosto" id="fCodCentroCosto" value="<?=$fCodCentroCosto?>" />
<input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />
<input type="hidden" name="fEstado" id="fEstado" value="<?=$fEstado?>" />
<input type="hidden" name="fFechaPreparaciond" id="fFechaPreparaciond" value="<?=$fFechaPreparaciond?>" />
<input type="hidden" name="fFechaPreparacionh" id="fFechaPreparacionh" value="<?=$fFechaPreparacionh?>" />
<input type="hidden" id="CodRequerimiento" />
<input type="hidden" id="TipoRequerimiento" value="<?=$field_clasificacion['TipoRequerimiento']?>" />
<input type="hidden" id="Anio" value="<?=substr($Ahora, 0, 4)?>" />
<input type="hidden" id="detalles_anterior" value="<?=$detalles?>" />

<div id="tab1" style="display:block;">
<table width="1100" class="tblForm">
	<tr>
    	<td colspan="4" class="divFormCaption">Informaci&oacute;n General</td>
    </tr>
	<tr>
		<td class="tagForm" width="150">* Organismo:</td>
		<td>
			<select id="CodOrganismo" style="width:300px;" onchange="getOptionsSelect(this.value, 'dependencia', 'CodDependencia', true, 'CodCentroCosto');" <?=$disabled_modificar?>>
				<?=getOrganismos($CodOrganismo, 3)?>
			</select>
		</td>
		<td class="tagForm">N&uacute;mero:</td>
		<td>
        	<input type="text" id="CodInterno" style="width:100px;" class="codigo" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Dependencia:</td>
		<td>
			<select id="CodDependencia" style="width:300px;" onchange="getOptionsSelect(this.value, 'centro_costo', 'CodCentroCosto', true);" <?=$disabled_modificar?>>
				<?=getDependencias($CodDependencia, $_SESSION["ORGANISMO_ACTUAL"], 3);?>
			</select>
		</td>
		<td class="tagForm">Estado:</td>
		<td>
			<input type="hidden" id="Estado" value="PR" />
			<input type="text" style="width:100px;" class="codigo" value="<?=printValores("ESTADO-REQUERIMIENTO", "PR")?>" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Centro de Costo:</td>
		<td>
			<select id="CodCentroCosto" style="width:300px;">
				<?=loadSelectDependiente("ac_mastcentrocosto", "CodCentroCosto", "Descripcion", "CodDependencia", $_PARAMETRO["CCOSTOCOMPRA"], $CodDependencia, 0)?>
			</select>
		</td>
		<td class="tagForm">Dirigido A:</td>
		<td>
			<input type="radio" name="TipoClasificacion" id="FlagCompras" value="C" disabled="disabled" <?=chkOpt($field_clasificacion['ReqAlmacenCompra'], "C")?> /> Compras
			<input type="radio" name="TipoClasificacion" id="FlagAlmacen" value="A" disabled="disabled" <?=chkOpt($field_clasificacion['ReqAlmacenCompra'], "A")?> /> Almac&eacute;n
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Clasificaci&oacute;n:</td>
		<td>
			<select id="Clasificacion" style="width:200px;" onchange="setDirigidoA(this.value);" <?=$disabled_modificar?>>
				<?=loadSelectClasificacion($_PARAMETRO["REQRAU"], 1)?>
			</select>
		</td>
		<td class="tagForm">Preparada por:</td>
		<td class="gallery clearfix">
			<input type="hidden" id="PreparadaPor" value="<?=$_SESSION["CODPERSONA_ACTUAL"]?>" />
			<input type="text" id="NomPreparadaPor" value="<?=$_SESSION["NOMBRE_USUARIO_ACTUAL"]?>" style="width:195px;" class="disabled" disabled="disabled" />
			<input type="text" id="FechaPreparacion" value="<?=formatFechaDMA(substr($Ahora, 0, 10))?>" style="width:60px;" class="disabled" disabled="disabled" />
			<a href="../lib/listas/listado_empleados.php?filtrar=default&cod=PreparadaPor&nom=NomPreparadaPor&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Almac&eacute;n:</td>
		<td>
			<select id="CodAlmacen" style="width:200px;">
				<?=loadSelect("lg_almacenmast", "CodAlmacen", "Descripcion", $field_clasificacion['CodAlmacen'], 0)?>
			</select>
		</td>
		<td class="tagForm">Revisada por:</td>
		<td>
			<input type="hidden" id="RevisadaPor" />
			<input type="text" id="NomRevisadaPor" style="width:195px;" class="disabled" disabled="disabled" />
			<input type="text" id="FechaRevision" style="width:60px;" class="disabled" disabled="disabled" />
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Prioridad:</td>
		<td>
			<select id="Prioridad" style="width:200px;">
				<?=loadSelectGeneral("PRIORIDAD", "", 0)?>
			</select>
		</td>
		<td class="tagForm">Conformada por:</td>
		<td>
			<input type="hidden" id="ConformadaPor" />
			<input type="text" id="NomConformadaPor" style="width:195px;" class="disabled" disabled="disabled" />
			<input type="text" id="FechaConformacion" style="width:60px;" class="disabled" disabled="disabled" />
		</td>
	</tr>
    <tr>
	 	<td class="tagForm">* Fecha Requerida:</td>
		<td><input type="text" id="FechaRequerida" value="<?=formatFechaDMA($FechaRequerida)?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" /></td>
		<td class="tagForm">Aprobada por:</td>
		<td>
			<input type="hidden" id="AprobadaPor" />
			<input type="text" id="NomAprobadaPor" style="width:195px;" class="disabled" disabled="disabled" />
			<input type="text" id="FechaAprobacion" style="width:60px;" class="disabled" disabled="disabled" />
		</td>
	</tr>
    <tr>
    	<td>&nbsp;</td>
    	<td colspan="3">
        	<input type="checkbox" name="FlagCajaChica" id="FlagCajaChica" value="S" disabled /> Requerimiento para Caja Chica
        </td>
    </tr>
	<tr>
		<td class="tagForm">Comentarios:</td>
		<td colspan="3"><textarea id="Comentarios" style="width:95%; height:50px;"><?=$Comentarios?></textarea></td>
	</tr>
	<tr>
		<td class="tagForm">Razon Rechazo:</td>
		<td colspan="3"><textarea id="RazonRechazo" style="width:95%; height:30px;" disabled></textarea></td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td>
			<input type="text" size="30" class="disabled" disabled="disabled" />
			<input type="text" size="25" class="disabled" disabled="disabled" />
		</td>
	</tr>  
</table>
<table width="1100" class="tblForm">
	<tr><th class="divFormCaption" colspan="2">Informaci&oacute;n para Compras</th></tr>
	<tr>
		<td class="tagForm" width="150">Clasificaci&oacute;n:</td>
		<td>
			<select id="ClasificacionOC" style="width:150px;">
            	<option value="">&nbsp;</option>
				<?=loadSelectValores("COMPRA-CLASIFICACION", $field_requerimiento['ClasificacionOC'], 0)?>
			</select>
		</td>
	</tr>
	<tr>
	 	<td class="tagForm">Proveedor Sugerido:</td>
        <td class="gallery clearfix">
			<input type="hidden" id="ProveedorSugerido" />
			<input type="text" id="NomProveedorSugerido" style="width:190px;" disabled="disabled" />
			<a href="../lib/listas/listado_personas.php?filtrar=default&cod=ProveedorSugerido&nom=NomProveedorSugerido&ventana=requerimiento&iframe=true&width=950&height=525" rel="prettyPhoto[iframe2]" id="btProveedorSugerido">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
		</td>
	</tr>
	<tr>
	 	<td class="tagForm">Doc. Ref. Proveedor:</td>
		<td><input type="text" id="ProveedorDocRef" style="width:150px;" disabled /></td>
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
            <a id="aSelCCosto" href="../lib/listas/listado_centro_costos.php?filtrar=default&cod=CodCentroCosto&nom=NomCentroCosto&ventana=selListadoLista&seldetalle=sel_detalles&filtroDependencia=S&iframe=true&width=1050&height=500" rel="prettyPhoto[iframe5]" style="display:none;"></a>
            <input type="button" class="btLista" id="btSelCCosto" value="Sel. C.Costo" onclick="validarAbrirLista('sel_detalles', 'aSelCCosto');" />
        </td>
		<td align="right" class="gallery clearfix">
        	<a id="aItem" href="../lib/listas/listado_items.php?filtrar=default&ventana=requerimiento_detalles_insertar&iframe=true&width=950&height=525" rel="prettyPhoto[iframe3]" style="display:none;"></a>
        	<a id="aCommodity" href="../lib/listas/listado_commodities.php?filtrar=default&ventana=requerimiento_detalles_insertar&iframe=true&width=950&height=525" rel="prettyPhoto[iframe4]" style="display:none;"></a>
			<input type="button" class="btLista" value="Item" id="btItem" onclick="document.getElementById('aItem').click();" <?=$disabled_item?> />
			<input type="button" class="btLista" value="Commodity" id="btCommodity" onclick="document.getElementById('aCommodity').click();" <?=$disabled_commodity?> />
			<input type="button" class="btLista" value="Borrar" onclick="quitarLinea(this, 'detalles');" />
		</td>
	</tr>
</table>
<center>
<div style="overflow:scroll; width:1100px; height:400px;">
<table width="1400" class="tblLista">
	<thead>
	<tr>
        <th scope="col" width="25">#</th>
        <th scope="col" width="80">C&oacute;digo</th>
        <th scope="col">Descripci&oacute;n</th>
        <th scope="col" width="40">Uni.</th>
        <th scope="col" width="60">C.Costo</th>
        <th scope="col" width="30">Ex.</th>
        <th scope="col" width="60">Cantidad</th>
        <th scope="col" width="75">Dirigido A</th>
        <th scope="col" width="90">Estado</th>
        <th scope="col" width="125">Doc. Referencia</th>
        <th scope="col" width="60">Cant. Compra</th>
        <th scope="col" width="60">Cant. Recibida</th>
        <th scope="col" width="75">Fecha Cotizaci&oacute;n</th>
    </tr>
    </thead>
    
    <tbody id="lista_detalles">
    <?
	$nrodetalles = 0;
	$detalle = split(";char:tr;", $detalles);
	foreach ($detalle as $linea) {
		list($_Requerimiento, $_CodItem, $_CommoditySub, $_Descripcion, $_CodUnidad, $_CodCentroCosto, $_FlagExonerado, $_CantidadPedida, $_CodCuenta, $_cod_partida, $_Comentarios) = split(";char:td;", $linea);
		list($_CodRequerimiento, $_Secuencia) = split("[.]", $_Requerimiento);
		$_DocReferencia = getValorCampo("lg_requerimientos", "CodRequerimiento", "CodInterno", $_CodRequerimiento);
		$nrodetalles++;
		?>
		<tr class="trListaBody" onclick="mClk(this, 'sel_detalles');" id="detalles_<?=$nrodetalles?>">
			<td align="center">
				<?=$nrodetalles?>
            </td>
			<td align="center">
            	<?=$_CodItem?>
                <input type="hidden" name="CodItem" class="cell2" style="text-align:center;" value="<?=$_CodItem?>" readonly />
                <input type="hidden" name="CommoditySub" class="cell2" style="text-align:center;" value="<?=$_CommoditySub?>" readonly />
            </td>
			<td align="center">
				<textarea name="Descripcion" style="height:30px;" class="cell" <?=$disabled_descripcion?>><?=changeUrl($_Descripcion)?></textarea>
			</td>
			<td align="center">
            	<input type="text" name="CodUnidad" value="<?=$_CodUnidad?>" class="cell2" style="text-align:center;" readonly />		
            </td>
			<td align="center">
				<input type="text" name="CodCentroCosto" id="CodCentroCosto_<?=$nrodetalles?>" class="cell" style="text-align:center;" value="<?=$_PARAMETRO["CCOSTOCOMPRA"]?>" />
				<input type="hidden" name="NomCentroCosto" id="NomCentroCosto_<?=$nrodetalles?>" />
			</td>
			<td align="center">
            	<input type="checkbox" name="FlagExonerado" <?=chkFlag($_FlagExonerado)?> />
            </td>
			<td align="center">
            	<input type="text" name="CantidadPedida" class="cell" style="text-align:right; font-weight:bold;" value="<?=number_format($_CantidadPedida, 2, ',', '.')?>" onBlur="numeroBlur(this);" onFocus="numeroFocus(this);" />
            </td>
			<td align="center">
            	<input type="hidden" name="FlagCompraAlmacen" value="<?=$_FlagCompraAlmacen?>" />
				<?=printValoresGeneral("DIRIGIDO", "C")?>
            </td>
			<td align="center">
				<?=printValores("ESTADO-REQUERIMIENTO-DETALLE", "PR")?>
            </td>
			<td align="center">
				<?=$_DocReferencia?>
				<input type="hidden" name="CodCuenta" value="<?=$_CodCuenta?>" />
				<input type="hidden" name="cod_partida" value="<?=$_cod_partida?>" />
			</td>
			<td align="right">
				0,00
            </td>
			<td align="right">
				0,00
            </td>
			<td align="center">
				
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
<div style="overflow:scroll; width:1100px; height:400px;">
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
<div style="overflow:scroll; width:1100px; height:400px;">
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
<div style="overflow:scroll; width:1100px; height:150px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
        <th scope="col" width="100">Cuenta</th>
        <th scope="col">Descripci&oacute;n</th>
        <th scope="col" width="75">%</th>
    </tr>
    </thead>
    
    <tbody id="lista_cuentas">
    </tbody>
</table>
</div>

<div style="width:1100px;" class="divFormCaption">Distribuci&oacute;n Presupuestaria</div>
<div style="overflow:scroll; width:1100px; height:150px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
        <th scope="col" width="100">Partida</th>
        <th scope="col">Descripci&oacute;n</th>
        <th scope="col" width="75">%</th>
    </tr>
    </thead>
    
    <tbody id="lista_partidas">
    </tbody>
</table>
</div>
</center>
</div>