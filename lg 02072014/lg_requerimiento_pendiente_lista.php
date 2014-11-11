<?php
$Ahora = ahora();
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
//	------------------------------------
if ($filtrar == "default") {
	$fCodOrganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$maxlimit = $_SESSION["MAXLIMIT"];
	if ($lista == "todos") {
		$fEstado = "PE";
		$fFechaPreparaciond = "01-$Mes-$Anio";
		$fFechaPreparacionh = "$Dia-$Mes-$Anio";
	}
}
if ($fCodOrganismo != "") { $cOrganismo = "checked"; $filtro.=" AND (r.CodOrganismo = '".$fCodOrganismo."')"; } else $dOrganismo = "disabled";
if ($fCodDependencia != "") { $cCodDependencia = "checked"; $filtro.=" AND (r.CodDependencia = '".$fCodDependencia."')"; } else $dCodDependencia = "disabled";
if ($fCodCentroCosto != "") { $cCodCentroCosto = "checked"; $filtro.=" AND (r.CodCentroCosto = '".$fCodCentroCosto."')"; } else $dCodCentroCosto = "disabled";
if ($fClasificacion != "") { $cClasificacion = "checked"; $filtro.=" AND (r.Clasificacion = '".$fClasificacion."')"; } else $dClasificacion = "disabled";
if ($fEstado != "") { $cEstado = "checked"; $filtro.=" AND (rd.Estado = '".$fEstado."')"; } else $dEstado = "disabled";
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
		<td class="titulo">Lista de Requerimientos Pendientes</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=lg_requerimiento_pendiente_lista" method="post">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="detalles" id="detalles" />
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
            <input type="checkbox" onclick="this.checked=!this.checked;" checked="checked" />
            <select name="fEstado" id="fEstado" style="width:150px;">
                <?=loadSelectValores("ESTADO-REQUERIMIENTO-DETALLE", $fEstado, 1)?>
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
			<input type="button" id="btGenerar" value="Generar" style="width:75px;" onclick="cargarOpcionRequerimientoPendiente(this.form);" /> | 
            
			<input type="button" id="btVer" value="Ver" style="width:75px;" onclick="cargarOpcion2(this.form, 'gehen.php?anz=lg_requerimiento_form&opcion=ver&origen=lg_requerimiento_lista', 'SELF', '', $('#registro').val());" />
                        
			<input type="button" id="btCerrar" value="Cerrar" style="width:75px;" onclick="opcionRegistro2(this.form, $('#registro').val(), 'requerimiento', 'cerrar-detalle');" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:1050px; height:300px;">
<table width="2300" class="tblLista">
	<thead>
    <tr>
		<th scope="col" width="75">Fecha Preparaci&oacute;n</th>
		<th scope="col" width="75">Fecha Aprobaci&oacute;n</th>
		<th scope="col" width="25">Dias</th>
		<th scope="col" width="100">Requerimiento</th>
		<th scope="col" width="15">#</th>
		<th scope="col" width="75">Item</th>
		<th scope="col">Descripci&oacute;n</th>
		<th scope="col" width="50">Cant. Pedida</th>
		<th scope="col" width="50">Cant. Pendiente</th>
		<th scope="col" width="50">Stock</th>
		<th scope="col" width="175">Clasificaci&oacute;n</th>
		<th scope="col" width="35">C.C.</th>
		<th scope="col" width="175">Almacen</th>
		<th scope="col" width="75">Fecha Requerida</th>
		<th scope="col" width="600">Comentario</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				r.FechaPreparacion,
				r.FechaAprobacion,
				r.Prioridad,
				r.Clasificacion,
				r.FechaRequerida,
				r.CodInterno,
				DATEDIFF(NOW(), r.FechaAprobacion) AS Dias,
				rd.*,
				(rd.CantidadPedida - rd.CantidadRecibida) AS CantidadPendiente,
				c.Descripcion AS NomClasificacion,
				a.Descripcion AS NomAlmacen,
				iai.StockActual, 
				o.Organismo,
				d.Dependencia,
				i.CodLinea,
				i.CodFamilia
			FROM
				lg_requerimientosdet rd
				INNER JOIN lg_requerimientos r ON (rd.CodOrganismo = r.CodOrganismo AND 
												   rd.CodRequerimiento = r.CodRequerimiento)
				INNER JOIN mastorganismos o ON (rd.CodOrganismo = o.CodOrganismo)
				INNER JOIN mastdependencias d ON (r.CodDependencia = d.CodDependencia)
				INNER JOIN lg_itemmast i ON (rd.CodItem = i.CodItem)
				INNER JOIN lg_clasificacion c ON (r.Clasificacion = c.Clasificacion)
				INNER JOIN lg_almacenmast a ON (r.CodAlmacen = a.CodAlmacen)
				LEFT JOIN lg_itemalmaceninv iai ON (r.CodAlmacen = iai.CodAlmacen AND 
													rd.CodItem = iai.CodItem)
			WHERE
				r.Estado = 'AP' AND 
				r.TipoClasificacion = 'A' AND 
				rd.FlagCompraAlmacen <> 'A' $filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				r.FechaPreparacion,
				r.FechaAprobacion,
				r.Prioridad,
				r.Clasificacion,
				r.FechaRequerida,
				r.CodInterno,
				r.Comentarios,
				DATEDIFF(NOW(), r.FechaAprobacion) AS Dias,
				rd.*,
				(rd.CantidadPedida - rd.CantidadRecibida) AS CantidadPendiente,
				c.Descripcion AS NomClasificacion,
				a.Descripcion AS NomAlmacen,
				iai.StockActual, 
				o.Organismo,
				d.Dependencia,
				i.CodLinea,
				i.CodFamilia
			FROM
				lg_requerimientosdet rd
				INNER JOIN lg_requerimientos r ON (rd.CodOrganismo = r.CodOrganismo AND 
												   rd.CodRequerimiento = r.CodRequerimiento)
				INNER JOIN mastorganismos o ON (rd.CodOrganismo = o.CodOrganismo)
				INNER JOIN mastdependencias d ON (r.CodDependencia = d.CodDependencia)
				INNER JOIN lg_itemmast i ON (rd.CodItem = i.CodItem)
				INNER JOIN lg_clasificacion c ON (r.Clasificacion = c.Clasificacion)
				INNER JOIN lg_almacenmast a ON (r.CodAlmacen = a.CodAlmacen)
				LEFT JOIN lg_itemalmaceninv iai ON (r.CodAlmacen = iai.CodAlmacen AND 
													rd.CodItem = iai.CodItem)
			WHERE
				r.Estado = 'AP' AND 
				r.TipoClasificacion = 'A' AND 
				rd.FlagCompraAlmacen <> 'A' $filtro
			LIMIT ".intval($limit).", ".intval($maxlimit);
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[CodRequerimiento].$field[Secuencia]";
		if (strlen($field['Comentarios']) > 245) $Comentarios = substr($field['Comentarios'], 0, 245)."...";
		else $Comentarios = $field['Comentarios'];
		##
		?>
        <tr class="trListaBody" onclick="mClkMulti(this);" id="row_<?=$id?>">
			<td align="center">
               	<input type="checkbox" name="Secuencia" id="<?=$id?>" value="<?=$id?>" style="display:none" />
                <input type="hidden" name="CodItem" value="<?=$field['CodItem']?>" />
                <input type="hidden" name="CommoditySub" value="<?=$field['CommoditySub']?>" />
                <input type="hidden" name="Descripcion" value="<?=$field['Descripcion']?>" />
                <input type="hidden" name="CodUnidad" value="<?=$field['CodUnidad']?>" />
                <input type="hidden" name="CodCentroCosto" value="<?=$field['CodCentroCosto']?>" />
                <input type="hidden" name="FlagExonerado" value="<?=$field['FlagExonerado']?>" />
                <input type="hidden" name="CantidadPedida" value="<?=$field['CantidadPedida']?>" />
                <input type="hidden" name="CodCuenta" value="<?=$field['CodCuenta']?>" />
                <input type="hidden" name="cod_partida" value="<?=$field['cod_partida']?>" />
                <input type="hidden" name="Comentarios" value="<?=$field['Comentarios']?>" />
				<?=formatFechaDMA($field['FechaPreparacion'])?>
            </td>
			<td align="center"><?=formatFechaDMA($field['FechaAprobacion'])?></td>
			<td align="center"><?=$field['Dias']?></td>
			<td align="center"><?=$field['CodInterno']?></td>
			<td align="center"><?=$field['Secuencia']?></td>
			<td align="center"><?=$field['CodItem']?></td>
			<td><?=($field['Descripcion'])?></td>
			<td align="right"><?=number_format($field['CantidadPedida'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field['CantidadPendiente'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field['StockActual'], 2, ',', '.')?></td>
			<td><?=($field['NomClasificacion'])?></td>
			<td align="center"><?=$field['CodCentroCosto']?></td>
			<td><?=($field['NomAlmacen'])?></td>
			<td align="center"><?=formatFechaDMA($field['FechaRequerida'])?></td>
			<td title="<?=$field['Comentarios']?>"><?=($Comentarios)?></td>
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