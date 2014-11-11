<?php
$Ahora = ahora();
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
//	------------------------------------
if ($filtrar == "default") {
	$fCodOrganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$maxlimit = $_SESSION["MAXLIMIT"];
}
if ($fCodOrganismo != "") { $cCodOrganismo = "checked"; $filtro.=" AND (oc.CodOrganismo = '".$fCodOrganismo."')"; } else $dCodOrganismo = "disabled";
if ($fCodDependencia != "") { $cCodDependencia = "checked"; $filtro.=" AND (oc.CodDependencia = '".$fCodDependencia."')"; } else $dCodDependencia = "disabled";
if ($fFechaPrometida != "") { $cFechaPrometida = "checked"; $filtro.=" AND (oc.FechaPrometida <= '".formatFechaAMD($fFechaPrometida)."')"; } else $dFechaPrometida = "disabled";
if ($fCodAlmacen != "") { $cCodAlmacen = "checked"; $filtro.=" AND (oc.CodAlmacen = '".$fCodAlmacen."')"; } else $dCodAlmacen = "disabled";
if ($fBuscar != "") {
	$cBuscar = "checked";
	$filtro.=" AND (oc.NroOrden LIKE '%".$fBuscar."%' OR
					oc.CodProveedor LIKE '%".$fBuscar."%' OR
					oc.FechaPrometida LIKE '%".$fBuscar."%' OR
					oc.NomProveedor LIKE '%".$fBuscar."%' OR
					fp.Descripcion LIKE '%".$fBuscar."%')";
} else $dBuscar = "disabled";
//	------------------------------------
?>
<body onload="deshabilitar()">
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Ingreso / Despacho de Commodities</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=lg_transaccion_commodity_especial_lista" method="post">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="detalles" id="detalles" />
<div class="divBorder" style="width:1050px;">
<table width="1050" class="tblFiltro">
	<tr>
		<td align="right" width="125">Organismo:</td>
		<td>
			<input type="checkbox" <?=$cCodOrganismo?> onclick="this.checked=!this.checked" />
			<select name="fCodOrganismo" id="fCodOrganismo" style="width:300px;" onchange="getOptionsSelect(this.value, 'dependencia', 'fCodDependencia', true, 'fCodCentroCosto');" <?=$dCodOrganismo?>>
				<?=getOrganismos($fCodOrganismo, 3)?>
			</select>
		</td>
		<td align="right" width="125">Fecha Entrega <=: </td>
		<td>
			<input type="checkbox" <?=$cFechaPrometida?> onclick="chkFiltro(this.checked, 'fFechaPrometida');" />
			<input type="text" name="fFechaPrometida" id="fFechaPrometida" value="<?=$fFechaPrometida?>" <?=$dFechaPrometida?> maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" />
        </td>
	</tr>
	<tr>
		<td align="right">Dependencia:</td>
		<td>
			<input type="checkbox" <?=$cCodDependencia?> onclick="chkFiltro(this.checked, 'fCodDependencia');" />
			<select name="fCodDependencia" id="fCodDependencia" style="width:300px;" <?=$dCodDependencia?>>
            	<option value="">&nbsp;</option>
				<?=getDependencias($fCodDependencia, $fCodOrganismo, 3)?>
			</select>
		</td>
		<td align="right">Almacen:</td>
		<td>
			<input type="checkbox" <?=$cCodAlmacen?> onclick="chkFiltro(this.checked, 'fCodAlmacen')" />
			<select name="fCodAlmacen" id="fCodAlmacen" style="width:140px;" <?=$dCodAlmacen?>>
				<option value="">&nbsp;</option>
				<?=loadSelectAlmacen($fCodAlmacen, "S", 0)?>
			</select>
		</td>
	</tr>
    <tr>
		<td align="right">Buscar:</td>
		<td>
			<input type="checkbox" <?=$cBuscar?> onclick="chkFiltro(this.checked, 'fBuscar');" />
			<input type="text" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" style="width:295px;" <?=$dBuscar?> />
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />
</form>

<table width="1050" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" onclick="currentTab('tab', this);" class="current"><a href="#" onclick="mostrarTab('tab', 1, 2);">Ingresos de O/C</a></li>
            <li id="li2" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 2, 2);">Despacho de Req. x O/C</a></li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<center>
<form name="frm_recepcion" id="frm_recepcion">
<div id="tab1" style="display:block;">
<div style="overflow:scroll; width:1050px; height:200px;">
<table width="100%" class="tblLista">
	<thead>
    <tr>
		<th scope="col" width="75">Nro. Orden</th>
		<th scope="col" width="50">Proveedor</th>
		<th scope="col" align="left">Raz&oacute;n Social</th>
		<th scope="col" width="75">Fecha Entrega</th>
		<th scope="col" width="125">Forma de Pago</th>
		<th scope="col" width="75">Clasificaci&oacute;n</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT 
				oc.Anio,
				oc.CodOrganismo,
				oc.NroOrden,
				oc.NroInterno,
				oc.CodProveedor,
				oc.NomProveedor,
				oc.FechaPrometida,
				oc.Clasificacion,
				fp.Descripcion AS NomFormaPago
			FROM
				lg_ordencompra oc
				INNER JOIN lg_ordencompradetalle ocd ON (oc.CodOrganismo = ocd.CodOrganismo AND
														 oc.NroOrden = ocd.NroOrden AND
														 ocd.CommoditySub <> '')
				INNER JOIN mastformapago fp ON (oc.CodFormaPago = fp.CodFormaPago)
				INNER JOIN lg_almacenmast a ON (oc.CodAlmacen = a.CodAlmacen AND a.FlagCommodity = 'S')
			WHERE (oc.Estado = 'AP')
			GROUP BY Anio, CodOrganismo, NroOrden";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[Anio].$field[CodOrganismo].$field[NroOrden].rec";
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro'); appendAjax('accion=transaccion_commodity_recepcion_detalles&registro='+$('#registro').val(), $('#lista_detalles_recepcion'));" id="<?=$id?>">
            <td align="center"><?=$field['NroInterno']?></td>
            <td align="center"><?=$field['CodProveedor']?></td>
            <td><?=$field['NomProveedor']?></td>
            <td align="center"><?=formatFechaDMA($field['FechaPrometida'])?></td>
            <td align="center"><?=($field['NomFormaPago'])?></td>
            <td align="center"><?=printValores("COMPRA-CLASIFICACION", $field['Clasificacion'])?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
<table width="1050" class="tblBotones">
	<tr>
		<td align="right">
			<input type="button" id="btRecepcionar" value="Recepcionar" style="width:75px;" onclick="cargarOpcionRecepcionCommodity(this.form);" /> | 
			
            <input type="button" id="btCerrar" value="Cerrar" style="width:75px;" onclick="opcionRegistroMultiple(this.form, 'Secuencia', 'almacen', 'cerrar-detalle-compras', true);" />
            
            
		</td>
	</tr>
</table>
<div style="overflow:scroll; width:1050px; height:200px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
    	<td colspan="10" class="divFormCaption">Detalles de la Orden</td>
    </tr>
	<tr>
		<th scope="col" width="75">Item</th>
		<th scope="col">Descripci&oacute;n</th>
		<th scope="col" width="25">Uni.</th>
		<th scope="col" width="50">Cant. Pedida</th>
		<th scope="col" width="50">Cant. Recib.</th>
		<th scope="col" width="50">Cant. Pend.</th>
		<th scope="col" width="75">P. Unit.</th>
		<th scope="col" width="40">C.C.</th>
	</tr>
    </thead>
    
    <tbody id="lista_detalles_recepcion">
    
    </tbody>
</table>
</div>
</div>
</form>

<form name="frm_despacho" id="frm_despacho">
<div id="tab2" style="display:none;">
<div style="overflow:scroll; width:1050px; height:200px;">
<table width="100%" class="tblLista">
	<thead>
    <tr>
		<th scope="col" width="75">Nro. Orden</th>
		<th scope="col" width="50">Proveedor</th>
		<th scope="col" align="left">Raz&oacute;n Social</th>
		<th scope="col" width="75">Fecha Entrega</th>
		<th scope="col" width="125">Forma de Pago</th>
		<th scope="col" width="75">Clasificaci&oacute;n</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT 
				oc.Anio,
				oc.CodOrganismo,
				oc.NroOrden,
				oc.NroInterno,
				oc.CodProveedor,
				oc.NomProveedor,
				oc.FechaPrometida,
				oc.Clasificacion
			FROM
				lg_ordencompra oc
				INNER JOIN lg_ordencompradetalle ocd ON (oc.Anio = ocd.Anio AND
														 oc.CodOrganismo = ocd.CodOrganismo AND
														 oc.NroOrden = ocd.NroOrden AND
														 ocd.CommoditySub <> '')
				INNER JOIN lg_requerimientosdet rd ON (ocd.Anio = rd.Anio AND
													   ocd.CodOrganismo = rd.CodOrganismo AND
													   ocd.NroOrden = rd.NroOrden AND
													   ocd.Secuencia = rd.OrdenSecuencia)
				INNER JOIN lg_requerimientos r ON (r.CodRequerimiento = rd.CodRequerimiento AND r.FlagCajaChica <> 'S')
				INNER JOIN lg_almacenmast a ON (oc.CodAlmacen = a.CodAlmacen AND a.FlagCommodity = 'S')
			WHERE
				oc.Estado <> 'AN' AND
				ocd.CantidadRecibida > 0 AND
				rd.CantidadPedida > rd.CantidadRecibida
				$filtro
			GROUP BY Anio, CodOrganismo, NroOrden";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[Anio].$field[CodOrganismo].$field[NroOrden].des";
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro'); appendAjax('accion=transaccion_commodity_despacho_detalles&registro='+$('#registro').val(), $('#lista_detalles_despacho'));" id="<?=$id?>">
            <td align="center"><?=$field['NroInterno']?></td>
            <td align="center"><?=$field['CodProveedor']?></td>
            <td><?=$field['NomProveedor']?></td>
            <td align="center"><?=formatFechaDMA($field['FechaPrometida'])?></td>
            <td align="center"><?=($field['NomFormaPago'])?></td>
            <td align="center"><?=printValores("COMPRA-CLASIFICACION", $field['Clasificacion'])?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
<table width="1050" class="tblBotones">
	<tr>
		<td align="right">
			<input type="button" id="btDespachar" value="Despachar" style="width:75px;" onclick="cargarOpcionDespachoCommodity(this.form);" /> | 
			
            <input type="button" id="btCerrar" value="Cerrar" style="width:75px;" onclick="opcionRegistroMultiple(this.form, 'Secuencia', 'almacen', 'cerrar-detalle-compras', true);" />
            
            
		</td>
	</tr>
</table>
<div style="overflow:scroll; width:1050px; height:200px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
    	<td colspan="10" class="divFormCaption">Detalles del Requerimiento</td>
    </tr>
	<tr>
		<th scope="col" width="75">Requerimiento</th>
		<th scope="col" width="15">#</th>
		<th scope="col" width="75">Comm.</th>
		<th scope="col">Descripci&oacute;n</th>
		<th scope="col" width="25">Uni.</th>
		<th scope="col" width="50">Cant. Pedida</th>
		<th scope="col" width="50">Cant. Rec.</th>
		<th scope="col" width="50">Cant. Pend.</th>
		<th scope="col" width="40">C.C.</th>
	</tr>
    </thead>
    
    <tbody id="lista_detalles_despacho">
    
    </tbody>
</table>
</div>
</div>
</form>
</center>
</body>
<script language="javascript">
	function deshabilitar(){
		document.getElementById('btRecepcionar').disabled = false;
		document.getElementById('btCerrar').disabled = false;
		document.getElementById('btDespachar').disabled = false;


	}
</script>
