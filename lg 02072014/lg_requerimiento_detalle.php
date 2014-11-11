<?php
$Ahora = ahora();
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
//	------------------------------------
if ($filtrar == "default") {
	$fCodOrganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$maxlimit = $_SESSION["MAXLIMIT"];
	if ($lista == "todos") {
		$fEstado = "PR";
		$fFechaPreparaciond = "01-$Mes-$Anio";
		$fFechaPreparacionh = "$Dia-$Mes-$Anio";
	}
}
if ($fCodOrganismo != "") { $cOrganismo = "checked"; $filtro.=" AND (r.CodOrganismo = '".$fCodOrganismo."')"; } else $dOrganismo = "disabled";
if ($fCodDependencia != "") { $cCodDependencia = "checked"; $filtro.=" AND (r.CodDependencia = '".$fCodDependencia."')"; } else $dCodDependencia = "disabled";
if ($fCodCentroCosto != "") { $cCodCentroCosto = "checked"; $filtro.=" AND (r.CodCentroCosto = '".$fCodCentroCosto."')"; } else $dCodCentroCosto = "disabled";
if ($fClasificacion != "") { $cClasificacion = "checked"; $filtro.=" AND (r.Clasificacion = '".$fClasificacion."')"; } else $dClasificacion = "disabled";
if ($fEstado != "") { $cEstado = "checked"; $filtro.=" AND (r.Estado = '".$fEstado."')"; } else $dEstado = "disabled";
if ($fTipoClasificacion != "") { $cTipoClasificacion = "checked"; $filtro.=" AND (r.TipoClasificacion = '".$fTipoClasificacion."')"; } else $dTipoClasificacion = "disabled";
if ($fBuscar != "") { 
	$cBuscar = "checked"; 
	$filtro.=" AND (r.CodRequerimiento LIKE '%".$fBuscar."%' OR 
					a.Descripcion LIKE '%".utf8_decode($fBuscar)."%' OR 
					r.CodCentroCosto LIKE '%".utf8_decode($fBuscar)."%' OR 
					c.Descripcion LIKE '%".utf8_decode($fBuscar)."%')";
} else $dBuscar = "disabled";
if ($fFechaPreparaciond != "" || $fFechaPreparacionh != "") {
	$cFechaPreparacion = "checked";
	if ($fFechaPreparaciond != "") $filtro.=" AND (r.FechaPreparacion >= '".formatFechaAMD($fFechaPreparaciond)."')";
	if ($fFechaPreparacionh != "") $filtro.=" AND (r.FechaPreparacion <= '".formatFechaAMD($fFechaPreparacionh)."')";
} else $dFechaPreparacion = "disabled";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Lista de Requerimientos (Detalle)</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=lg_requerimiento_detalle" method="post">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:1050px;">
<table width="1050" class="tblFiltro">
	<tr>
		<td align="right" width="125">Organismo:</td>
		<td>
			<input type="checkbox" <?=$cOrganismo?> onclick="this.checked=!this.checked" />
			<select name="fCodOrganismo" id="fCodOrganismo" style="width:300px;" onchange="getOptionsSelect(this.value, 'dependencia', 'fCodDependencia', true, 'fCodCentroCosto');" <?=$dOrganismo?>>
				<?=getOrganismos($fCodOrganismo, 3)?>
			</select>
		</td>
		<td align="right">Dirigido a:</td>
		<td>
			<input type="checkbox" <?=$cTipoClasificacion?> onclick="chkFiltro(this.checked, 'fTipoClasificacion')" />
			<select name="fTipoClasificacion" id="fTipoClasificacion" style="width:140px;" <?=$dTipoClasificacion?>>
				<option value="">&nbsp;</option>
				<?=loadSelectValores("DIRIGIDO", $fTipoClasificacion, 0)?>
			</select>
		</td>
	</tr>
    <tr>
		<td align="right">Dependencia:</td>
		<td>
			<input type="checkbox" <?=$cCodDependencia?> onclick="chkFiltro(this.checked, 'fCodDependencia')" />
			<select name="fCodDependencia" id="fCodDependencia" style="width:300px;" <?=$dCodDependencia?>>
				<option value="">&nbsp;</option>
				<?=getDependencias($fCodDependencia, $fCodOrganismo, 3);?>
			</select>
		</td>
		<td align="right">Clasificaci&oacute;n:</td>
		<td>
			<input type="checkbox" <?=$cClasificacion?> onclick="chkFiltro(this.checked, 'fClasificacion')" />
			<select name="fClasificacion" id="fClasificacion" style="width:140px;" <?=$dClasificacion?>>
				<option value="">&nbsp;</option>
				<?=loadSelect("lg_clasificacion", "Clasificacion", "Descripcion", $fClasificacion, 0)?>
			</select>
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
		<td align="right">Estado:</td>
		<td>
            <input type="checkbox" <?=$cEstado?> onclick="chkFiltro(this.checked, 'fEstado');" />
            <select name="fEstado" id="fEstado" style="width:140px;" <?=$dEstado?>>
                <option value="">&nbsp;</option>
                <?=loadSelectGeneral("ESTADO-REQUERIMIENTO-DETALLE", $fEstado, 0)?>
            </select>
		</td>
	</tr>
    <tr>
		<td align="right">Buscar:</td>
		<td>
			<input type="checkbox" <?=$cBuscar?> onclick="chkFiltro(this.checked, 'fBuscar');" />
			<input type="text" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" style="width:295px;" <?=$dBuscar?> />
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
<table width="1050" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">
			<input type="button" id="btVer" value="Ver" style="width:75px;" onclick="cargarOpcion2(this.form, 'gehen.php?anz=lg_requerimiento_form&opcion=ver&origen=lg_requerimiento_lista', 'SELF', '', $('#registro').val());" />
                        
			<input type="button" id="btCerrar" value="Cerrar" style="width:75px;" onclick="opcionRegistro2(this.form, $('#registro').val(), 'requerimiento', 'cerrar-detalle');" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:1050px; height:300px;">
<table width="2400" class="tblLista">
	<thead>
    <tr>
		<th scope="col" width="100">Requerimiento</th>
		<th scope="col" width="35">#</th>
		<th scope="col" width="80">Item</th>
		<th scope="col">Descripci&oacute;n</th>
		<th scope="col" width="35">Uni.</th>
		<th scope="col" width="50">Cant. Pedida</th>        
		<th scope="col" width="50">Cant. Recibida</th>
		<th scope="col" width="50">Cant. Pendien.</th>
		<th scope="col" width="50">Cant. Compra.</th>
		<th scope="col" width="50">Stock Actual</th>
		<th scope="col" width="75">Dirigido A</th>
		<th scope="col" width="100">Estado Linea</th>
		<th scope="col" width="100">Estado Requerimiento</th>
		<th scope="col" width="100">Fecha Preparaci&oacute;n</th>
		<th scope="col" width="400">Dependencia</th>
		<th scope="col" width="200">Almac&eacute;n</th>
		<th scope="col" width="100">Doc. Referencia</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				r.CodOrganismo,
				r.CodRequerimiento,
				r.CodInterno,
				r.Clasificacion,
				r.FechaPreparacion,
				r.Estado AS EstadoMast,
				r.Anio,
				r.CodDependencia,
				rd.Secuencia,
				rd.CodItem,
				rd.CommoditySub,
				rd.Descripcion,
				rd.CodUnidad,
				rd.CantidadPedida,
				rd.CantidadRecibida,
				rd.CantidadOrdenCompra,
				rd.DocReferencia,
				rd.FlagCompraAlmacen,
				rd.Estado AS EstadoDetalle,
				c.Descripcion AS NomClasificacion,
				a.Descripcion AS NomAlmacen,
				d.Dependencia
			FROM
				lg_requerimientosdet rd
				INNER JOIN lg_requerimientos r ON (rd.CodRequerimiento = r.CodRequerimiento)
				INNER JOIN lg_clasificacion c ON (r.Clasificacion = c.Clasificacion)
				INNER JOIN lg_almacenmast a ON (r.CodAlmacen = a.CodAlmacen)
				INNER JOIN mastdependencias d ON (r.CodDependencia = d.CodDependencia)
			WHERE 1 $filtro
			ORDER BY Clasificacion, CodDependencia, Anio, CodRequerimiento, Secuencia";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				r.CodOrganismo,
				r.CodRequerimiento,
				r.CodInterno,
				r.Clasificacion,
				r.FechaPreparacion,
				r.Estado AS EstadoMast,
				r.Anio,
				r.CodDependencia,
				rd.Secuencia,
				rd.CodItem,
				rd.CommoditySub,
				rd.Descripcion,
				rd.CodUnidad,
				rd.CantidadPedida,
				rd.CantidadRecibida,
				rd.CantidadOrdenCompra,
				rd.DocReferencia,
				rd.FlagCompraAlmacen,
				rd.Estado AS EstadoDetalle,
				c.Descripcion AS NomClasificacion,
				a.Descripcion AS NomAlmacen,
				d.Dependencia
			FROM
				lg_requerimientosdet rd
				INNER JOIN lg_requerimientos r ON (rd.CodRequerimiento = r.CodRequerimiento)
				INNER JOIN lg_clasificacion c ON (r.Clasificacion = c.Clasificacion)
				INNER JOIN lg_almacenmast a ON (r.CodAlmacen = a.CodAlmacen)
				INNER JOIN mastdependencias d ON (r.CodDependencia = d.CodDependencia)
			WHERE 1 $filtro
			ORDER BY Clasificacion, CodDependencia, Anio, CodRequerimiento, Secuencia
			LIMIT ".intval($limit).", ".intval($maxlimit);
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[CodRequerimiento].$field[Secuencia]";
		if ($field['CodItem'] != "") $coddetalle = $field['CodItem']; else $coddetalle = $field['CommoditySub'];
		##
		if ($grupo != $field['Clasificacion']) {
			$grupo = $field['Clasificacion'];
			?>
            <tr class="trListaBody2">
                <td colspan="4"><?=($field['NomClasificacion'])?></td>
            </tr>
            <?
		}
		##
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<td align="center"><?=$field['CodInterno']?></td>
			<td align="center"><?=$field['Secuencia']?></td>
			<td align="center"><?=$coddetalle?></td>
			<td><?=($field['Descripcion'])?></td>
			<td align="center"><?=$field['CodUnidad']?></td>
			<td align="right"><?=number_format($field['CantidadPedida'], 2, ',', '.')?></td>            
			<td align="right"><?=number_format($field['CantidadRecibida'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($cantidad_pendiente, 2, ',', '.')?></td>
			<td align="right"><?=number_format($field['CantidadOrdenCompra'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field['StockActual'], 2, ',', '.')?></td>
			<td align="center"><?=printValores("DIRIGIDO", $field['FlagCompraAlmacen'])?></td>
			<td align="center"><?=printValores("ESTADO-REQUERIMIENTO-DETALLE", $field['EstadoDetalle'])?></td>
			<td align="center"><?=printValores("ESTADO-REQUERIMIENTO", $field['EstadoMast'])?></td>
			<td align="center"><?=formatFechaDMA($field['FechaPreparacion'])?></td>
			<td><?=utf8_decode($field['Dependencia'])?></td>
			<td><?=utf8_decode($field['NomAlmacen'])?></td>
			<td align="center"><?=$field['DocReferencia']?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
<table width="1050">
	<tr>
    	<td>
        	Mostrar: 
            <select name="maxlimit" style="width:50px;" onchange="this.form.submit();">
                <?=loadSelectGeneral("MAXLIMIT", $maxlimit, 0)?>
            </select>
        </td>
        <td align="right">
        	<?=paginacion(intval($rows_total), intval($rows_lista), intval($maxlimit), intval($limit));?>
        </td>
    </tr>
</table>
</center>
</form>