<?php
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
//	------------------------------------
if ($filtrar == "default") {
	$fCodOrganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$maxlimit = $_SESSION["MAXLIMIT"];
	$fFechaPreparaciond = "01-$Mes-$Anio";
	$fFechaPreparacionh = "$Dia-$Mes-$Anio";
}
if ($fCodOrganismo != "") { $cCodOrganismo = "checked"; $filtro.=" AND (os.CodOrganismo = '".$fCodOrganismo."')"; } else $dCodOrganismo = "disabled";
if ($fCodDependencia != "") { $cCodDependencia = "checked"; $filtro.=" AND (os.CodDependencia = '".$fCodDependencia."')"; } else $dCodDependencia = "disabled";
if ($fCodCentroCosto != "") { $cCodCentroCosto = "checked"; $filtro.=" AND (os.CodCentroCosto = '".$fCodCentroCosto."')"; } else $dCodCentroCosto = "disabled";
if ($fCodProveedor != "") { $cCodProveedor = "checked"; $filtro.=" AND (os.CodProveedor = '".$fCodProveedor."')"; } else $dCodProveedor = "visibility:hidden;";
if ($fBuscar != "") { 
	$cBuscar = "checked"; 
	$filtro.=" AND (os.NroInterno LIKE '%".$fBuscar."%' OR 
					os.FechaPreparacion LIKE '%".utf8_decode($fBuscar)."%' OR
					os.NomProveedor LIKE '%".utf8_decode($fBuscar)."%' OR
					os.Descripcion LIKE '%".utf8_decode($fBuscar)."%' OR
					os.DescAdicional LIKE '%".utf8_decode($fBuscar)."%' OR
					os.MotRechazo LIKE '%".utf8_decode($fBuscar)."%' OR
					os.Observaciones LIKE '%".utf8_decode($fBuscar)."%')";
} else $dBuscar = "disabled";
if ($fFechaPreparaciond != "" || $fFechaPreparacionh != "") {
	$cFechaPreparacion = "checked";
	if ($fFechaPreparaciond != "") $filtro.=" AND (os.FechaPreparacion >= '".formatFechaAMD($fFechaPreparaciond)."')";
	if ($fFechaPreparacionh != "") $filtro.=" AND (os.FechaPreparacion <= '".formatFechaAMD($fFechaPreparacionh)."')";
} else $dFechaPreparacion = "disabled";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Confirmar Realizaci&oacute;n de Servicios</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=lg_orden_servicio_confirmar_lista" method="post">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="registro" id="registro" />
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
		<td align="right" width="125">Proveedor: </td>
		<td class="gallery clearfix">
            <input type="checkbox" <?=$cCodProveedor?> onclick="chkFiltroLista_3(this.checked, 'fCodProveedor', 'fNomProveedor', '', 'btProveedor');" />
            
            <input type="text" name="fCodProveedor" id="fCodProveedor" style="width:50px;" value="<?=$fCodProveedor?>" readonly="readonly" />
			<input type="text" name="fNomProveedor" id="fNomProveedor" style="width:235px;" value="<?=$fNomProveedor?>" readonly="readonly" />
            <a href="../lib/listas/listado_personas.php?filtrar=default&cod=fCodProveedor&nom=fNomProveedor&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]" id="btProveedor" style=" <?=$dCodProveedor?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
	</tr>
    <tr>
		<td align="right">Dependencia:</td>
		<td>
			<input type="checkbox" <?=$cCodDependencia?> onclick="chkFiltro(this.checked, 'fCodDependencia')" />
			<select name="fCodDependencia" id="fCodDependencia" style="width:300px;" onchange="getOptionsSelect(this.value, 'centro_costo', 'fCodCentroCosto', true);" <?=$dCodDependencia?>>
				<option value="">&nbsp;</option>
				<?=getDependencias($fCodDependencia, $fCodOrganismo, 3)?>
			</select>
		</td>
		<td align="right">Buscar:</td>
		<td>
			<input type="checkbox" <?=$cBuscar?> onclick="chkFiltro(this.checked, 'fBuscar');" />
			<input type="text" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" style="width:295px;" <?=$dBuscar?> />
		</td>
	</tr>
    <tr>
		<td align="right">Centro de Costo:</td>
		<td>
			<input type="checkbox" <?=$cCodCentroCosto?> onclick="chkFiltro(this.checked, 'fCodCentroCosto')" />
			<select name="fCodCentroCosto" id="fCodCentroCosto" style="width:300px;" <?=$dCodCentroCosto?>>
				<option value="">&nbsp;</option>
				<?=loadSelectDependiente("ac_mastcentrocosto", "CodCentroCosto", "Descripcion", "CodDependencia", $fCodCentroCosto, $fCodDependencia, 0)?>
			</select>
		</td>
		<td align="right">F.Preparaci&oacute;n: </td>
		<td>
			<input type="checkbox" <?=$cFechaPreparacion?> onclick="chkFiltro_2(this.checked, 'fFechaPreparaciond', 'fFechaPreparacionh');" />
			<input type="text" name="fFechaPreparaciond" id="fFechaPreparaciond" value="<?=$fFechaPreparaciond?>" <?=$dFechaPreparacion?> maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" />-
            <input type="text" name="fFechaPreparacionh" id="fFechaPreparacionh" value="<?=$fFechaPreparacionh?>" <?=$dFechaPreparacion?> maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" />
        </td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<center>
<table width="1050" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" class="current" onclick="currentTab('tab', this);">
            	<a href="#" onclick="mostrarTab('tab', '1', 2)">Ordenes Pendientes por Confirmar</a>
            </li>
            <li id="li2" onclick="currentTab('tab', this);">
            	<a href="#" onclick="mostrarTab('tab', '2', 2);">Confirmaciones ya Realizadas</a>
            </li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<div id="tab1" style="display:block;">
<table width="1050" class="tblBotones">
	<tr>
		<td align="right">
			<input type="button" id="btConfirmar" value="Confirmar Servicio" onclick="cargarOpcion2(this.form, 'gehen.php?anz=lg_orden_servicio_confirmar_form', 'SELF', '', $('#registro').val());" />
		</td>
	</tr>
</table>
<div style="overflow:scroll; width:1050px; height:300px;">
<table width="1500" class="tblLista">
	<thead>
    <tr>
		<th scope="col" width="25">#</th>
		<th scope="col" width="100">Fecha</th>
		<th scope="col">Descripci&oacute;n</th>
		<th scope="col" width="50">C.C.</th>
		<th scope="col" width="60">Cantidad</th>
		<th scope="col" width="60">Cant. Recibida</th>
		<th scope="col" width="60">Cant. Pendiente</th>
		<th scope="col" width="100">Precio Unitario</th>
		<th scope="col" width="100">Monto Afecto</th>
		<th scope="col" width="100">Monto No Afecto</th>
		<th scope="col" width="100">Impuesto</th>
		<th scope="col" width="100">Monto Total</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto lista
	$sql = "SELECT
				os.CodProveedor,
				os.NomProveedor,
				os.FechaPreparacion,
				os.MontoOriginal AS MontoAfecto,
				os.MontoNoAfecto,
				os.MontoIva,
				osd.*,
				(osd.Total - (osd.CantidadPedida * osd.PrecioUnit)) AS Impuesto,
				(CantidadPedida - CantidadRecibida) AS CantidadPendiente
			FROM
				lg_ordenservicio os
				INNER JOIN lg_ordenserviciodetalle osd ON (os.Anio = osd.Anio AND 
														   os.CodOrganismo = osd.CodOrganismo AND 
														   os.NroOrden = osd.NroOrden)
			WHERE
				os.Estado = 'AP' AND
				osd.FlagTerminado = 'N' $filtro
			ORDER BY Anio, CodOrganismo, NroOrden";
	$query_confirmar = mysql_query($sql) or die ($sql.mysql_error());
	while ($field_confirmar = mysql_fetch_array($query_confirmar)) {
		$id = "$field_confirmar[Anio].$field_confirmar[CodOrganismo].$field_confirmar[NroOrden].$field_confirmar[Secuencia]";
		if ($field_confirmar['FlagExonerado'] == "S") {
			$MontoAfecto = 0.00;
			$MontoNoAfecto = $field_confirmar['CantidadPendiente'] * $field_confirmar['PrecioUnit'];
		} else {
			$MontoAfecto = $field_confirmar['CantidadPendiente'] * $field_confirmar['PrecioUnit'];
			$MontoNoAfecto = 0.00;
		}
		$Impuesto = $MontoAfecto * $field_confirmar['MontoIva'] / $field_confirmar['MontoAfecto'];
		$Total = $MontoAfecto + $MontoNoAfecto + $Impuesto;
		
		if ($agrupa != $field_confirmar['NroOrden']) {
			$agrupa = $field_confirmar['NroOrden'];
			?>
            <tr class="trListaBody2">
                <td align="center">O/S</td>
                <td align="center"><?=$field_confirmar['NroOrden']?></td>
                <td colspan="9"><?=($field_confirmar['NomProveedor'])?></td>
            </tr>
            <?
		}
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<td align="center"><?=$field_confirmar['Secuencia']?></td>
			<td align="center"><?=formatFechaDMA($field_confirmar['FechaPreparacion'])?></td>
			<td><?=($field_confirmar['Descripcion'])?></td>
			<td align="center"><?=$field_confirmar['CodCentroCosto']?></td>
			<td align="right"><?=number_format($field_confirmar['CantidadPedida'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field_confirmar['CantidadRecibida'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field_confirmar['CantidadPendiente'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field_confirmar['PrecioUnit'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($MontoAfecto, 2, ',', '.')?></td>
			<td align="right"><?=number_format($MontoNoAfecto, 2, ',', '.')?></td>
			<td align="right"><?=number_format($Impuesto, 2, ',', '.')?></td>
			<td align="right"><?=number_format($field_confirmar['Total'], 2, ',', '.')?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
</div>

<div id="tab2" style="display:none;">
<table width="1050" class="tblBotones">
	<tr>
		<td align="right">
			<input type="button" id="btDesConfirmar" value="Desconfirmar Servicio" onclick="opcionRegistro2(this.form, $('#registro').val(), 'orden_servicio', 'desconfirmar');" />
		</td>
	</tr>
</table>
<div style="overflow:scroll; width:1050px; height:300px;">
<table width="1800" class="tblLista">
	<thead>
    <tr>
		<th scope="col" width="35">#</th>
		<th scope="col" width="75">Fecha</th>
		<th scope="col" width="60">Commodity</th>
		<th scope="col">Descripci&oacute;n</th>
		<th scope="col" width="75">Cantidad</th>
		<th scope="col" width="100">Precio Unit.</th>
		<th scope="col" width="100">Monto Imponible</th>
		<th scope="col" width="100">Monto a Pagar</th>
		<th scope="col" width="100">Estado</th>
		<th scope="col" width="60">C.Costo</th>
		<th scope="col" width="125">Obligaci&oacute;n</th>
		<th scope="col" width="300">Confirmado Por</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto lista
	$sql = "SELECT
				osd.Anio,
				osd.CodOrganismo,
				osd.NroOrden,
				osd.CommoditySub,
				osd.Descripcion,
				d.DocumentoReferencia,
				d.CodProveedor,
				d.Fecha,
				d.Estado,
				d.ObligacionTipoDocumento,
				d.ObligacionNroDocumento,
				dd.Cantidad,
				dd.PrecioUnit,
				dd.PrecioCantidad,
				dd.Total,
				dd.CodCentroCosto,
				(dd.Total - dd.PrecioCantidad) AS Impuesto,
				osd.CantidadPedida,
				mp.NomCompleto AS NomProveedor,
				mp2.NomCompleto AS NomConfirmadoPor
			FROM
				ap_documentos d
				INNER JOIN ap_documentosdetalle dd ON (d.CodProveedor = dd.CodProveedor AND
													   d.DocumentoClasificacion = dd.DocumentoClasificacion AND
													   d.DocumentoReferencia = dd.DocumentoReferencia)
				INNER JOIN lg_ordenserviciodetalle osd ON (d.CodOrganismo = osd.CodOrganismo AND
														   d.ReferenciaNroDocumento = osd.NroOrden AND
														   dd.Secuencia = osd.Secuencia)
				INNER JOIN mastpersonas mp ON (d.CodProveedor = mp.CodPersona)
				LEFT JOIN mastpersonas mp2 ON (osd.ConfirmadoPor = mp2.CodPersona)
				
			WHERE d.DocumentoClasificacion = '".$_PARAMETRO['DOCREFOS']."'
			ORDER BY DocumentoReferencia";
	$query_desconfirmar = mysql_query($sql) or die ($sql.mysql_error());
	while ($field_desconfirmar = mysql_fetch_array($query_desconfirmar)) {
		$id = "$field_desconfirmar[Anio].$field_desconfirmar[CodProveedor].".$_PARAMETRO['DOCREFOS'].".$field_desconfirmar[DocumentoReferencia]";
		if ($agrupa != $field_desconfirmar['NroOrden']) {
			$agrupa = $field_desconfirmar['NroOrden'];
			$i=1;
			?>
            <tr class="trListaBody2">
                <td align="center">O/S</td>
                <td align="center"><?=$field_desconfirmar['NroOrden']?></td>
                <td colspan="2"><?=($field_desconfirmar['NomProveedor'])?></td>
            </tr>
            <?
		}
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<td align="center"><?=$i?></td>
			<td align="center"><?=formatFechaDMA($field_desconfirmar['Fecha'])?></td>
			<td align="center"><?=$field_desconfirmar['CommoditySub']?></td>
			<td><?=($field_desconfirmar['Descripcion'])?></td>
			<td align="right"><?=number_format($field_desconfirmar['Cantidad'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field_desconfirmar['PrecioUnit'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field_desconfirmar['PrecioCantidad'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field_desconfirmar['Total'], 2, ',', '.')?></td>
			<td align="center"><?=printValoresGeneral("ESTADO-DOCUMENTOS", $field_desconfirmar['Estado'])?></td>
			<td align="center"><?=$field_desconfirmar['CodCentroCosto']?></td>
			<td align="center"><?=$field_desconfirmar['ObligacionTipoDocumento']?>-<?=$field_desconfirmar['ObligacionNroDocumento']?></td>
			<td><?=($field_desconfirmar['NomConfirmadoPor'])?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
</div>
</center>
</form>