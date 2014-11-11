<?php
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
if ($filtrar == "default") {
	$fCodOrganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$maxlimit = $_SESSION["MAXLIMIT"];
	if ($lista == "todos") {
		$fEstado = "PR";
		$fFechaPreparaciond = "01-$Mes-$Anio";
		$fFechaPreparacionh = "$Dia-$Mes-$Anio";
	}
}
if ($fCodOrganismo != "") { $cCodOrganismo = "checked"; $filtro.=" AND (oc.CodOrganismo = '".$fCodOrganismo."')"; } else $dCodOrganismo = "disabled";
if ($fClasificacion != "") { $cClasificacion = "checked"; $filtro.=" AND (oc.Clasificacion = '".$fClasificacion."')"; } else $dClasificacion = "disabled";
if ($fCodProveedor != "") { $cCodProveedor = "checked"; $filtro.=" AND (oc.CodProveedor = '".$fCodProveedor."')"; } else $dCodProveedor = "visibility:hidden;";
if ($fEstado != "") { $cEstado = "checked"; $filtro.=" AND (ocd.Estado = '".$fEstado."')"; } else $dEstado = "disabled";
if ($fBuscar != "") {
	$cBuscar = "checked"; 
	$filtro.=" AND (oc.NroInterno LIKE '%".$fBuscar."%' OR 
					oc.FechaPreparacion LIKE '%".utf8_decode($fBuscar)."%' OR 
					ocd.Descripcion LIKE '%".utf8_decode($fBuscar)."%' OR 
					oc.Observaciones LIKE '%".utf8_decode($fBuscar)."%')";
} else $dBuscar = "disabled";
if ($fFechaPreparaciond != "" || $fFechaPreparacionh != "") {
	$cFechaPreparacion = "checked";
	if ($fFechaPreparaciond != "") $filtro.=" AND (oc.FechaPreparacion >= '".formatFechaAMD($fFechaPreparaciond)."')";
	if ($fFechaPreparacionh != "") $filtro.=" AND (oc.FechaPreparacion <= '".formatFechaAMD($fFechaPreparacionh)."')";
} else $dFechaPreparacion = "disabled";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Lista de Ordenes de Compra (Detalles)</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=lg_orden_compra_detalle" method="post">
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
		<td align="right" width="125">Clasificaci&oacute;n:</td>
		<td>
			<input type="checkbox" <?=$cClasificacion?> onclick="chkFiltro(this.checked, 'fClasificacion')" />
			<select name="fClasificacion" id="fClasificacion" style="width:140px;" <?=$dClasificacion?>>
				<option value="">&nbsp;</option>
				<?=loadSelectValores("COMPRA-CLASIFICACION", $fClasificacion, 0)?>
			</select>
		</td>
	</tr>
    <tr>
		<td align="right">Proveedor: </td>
		<td class="gallery clearfix">
            <input type="checkbox" <?=$cCodProveedor?> onclick="chkFiltroLista_3(this.checked, 'fCodProveedor', 'fNomProveedor', '', 'btProveedor');" />
            
            <input type="text" name="fCodProveedor" id="fCodProveedor" style="width:50px;" value="<?=$fCodProveedor?>" readonly="readonly" />
			<input type="text" name="fNomProveedor" id="fNomProveedor" style="width:235px;" value="<?=$fNomProveedor?>" readonly="readonly" />
            <a href="../lib/listas/listado_personas.php?filtrar=default&cod=fCodProveedor&nom=fNomProveedor&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]" id="btProveedor" style=" <?=$dCodProveedor?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
		<td align="right">Estado:</td>
		<td>
        	<? 
			if ($lista == "revisar" || $lista == "aprobar") {
				?>
				<input type="checkbox" onclick="this.checked=!this.checked;" checked="checked" />
                <select name="fEstado" id="fEstado" style="width:150px;">
                    <?=loadSelectGeneral("ESTADO-COMPRA-DETALLE", $fEstado, 1)?>
                </select>
                <?
			} else {
				?>
                <input type="checkbox" <?=$cEstado?> onclick="chkFiltro(this.checked, 'fEstado');" />
                <select name="fEstado" id="fEstado" style="width:140px;" <?=$dEstado?>>
					<option value="">&nbsp;</option>
                    <?=loadSelectGeneral("ESTADO-COMPRA-DETALLE", $fEstado, 0)?>
                </select>
                <?
			}
			?>
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
			<input type="button" id="" value="Checkear Impútaci&oacute;n" style="width:auto;" onclick="cargarOpcion2(this.form, 'gehen.php?anz=lg_orden_compra_check&opcion=ver&boton=verificarImputacion&origen=lg_orden_compra_detalle', 'SELF', '', 'registro');" />
            
            <input type="button" id="" value="Checkear Prespuesto" style="width:auto;" onclick="cargarOpcion2(this.form, 'gehen.php?anz=lg_orden_compra_check&opcion=ver&boton=verificarPresupuesto&origen=lg_orden_compra_detalle', 'SELF', '', 'registro');" />
            
            <input type="button" id="btVer" value="Ver" style="width:75px;" onclick="cargarOpcion2(this.form, 'gehen.php?anz=lg_orden_compra_form&opcion=ver&origen=lg_orden_compra_detalle', 'SELF', '', 'registro');" />
                        
			<input type="button" id="btCerrar" value="Cerrar" style="width:75px;" onclick="opcionRegistro2(this.form, $('#registro').val(), 'orden_compra', 'cerrar-detalle');" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:1050px; height:300px;">
<table width="2000" class="tblLista">
	<thead>
    <tr>
		<th scope="col" width="90">Nro. Orden</th>
		<th scope="col" width="25">#</th>
		<th scope="col" width="75">C&oacute;digo</th>
		<th scope="col" width="350" align="left">Descripci&oacute;n</th>
		<th scope="col" width="50">Cant. Pedida</th>
		<th scope="col" width="50">Cant. Recibida</th>
		<th scope="col" width="100">Precio Unit.</th>
		<th scope="col" width="100">Total</th>
		<th scope="col" align="left">Comentarios</th>
		<th scope="col" width="50">C.C.</th>
		<th scope="col" width="100">Estado Detalle</th>
		<th scope="col" width="100">Estado Orden</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				ocd.*,
				oc.Estado As EstadoOrden
			FROM
				lg_ordencompradetalle ocd
				INNER JOIN lg_ordencompra oc ON (ocd.CodOrganismo = oc.CodOrganismo AND
												 ocd.NroOrden = oc.NroOrden AND
												 ocd.Anio = oc.Anio)
			WHERE 1 $filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				ocd.*,
				oc.Estado As EstadoOrden
			FROM
				lg_ordencompradetalle ocd
				INNER JOIN lg_ordencompra oc ON (ocd.CodOrganismo = oc.CodOrganismo AND
												 ocd.NroOrden = oc.NroOrden AND
												 ocd.Anio = oc.Anio)
			WHERE 1 $filtro
			ORDER BY NroInterno
			LIMIT ".intval($limit).", ".intval($maxlimit);

	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[Anio].$field[CodOrganismo].$field[NroOrden].$field[Secuencia]";
		if (strlen($field['Comentarios']) > 150) $Comentarios = substr($field['Comentarios'], 0, 150)."...";
		else $Comentarios = $field['Comentarios'];
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<td align="center"><?=$field['NroInterno']?></td>
			<td align="center"><?=$field['Secuencia']?></td>
			<td align="center"><?=$field['CodItem']?><?=$field['CommoditySub']?></td>
			<td><?=($field['Descripcion'])?></td>
			<td align="right"><?=number_format($field['CantidadPedida'], 4, ',', '.')?></td>
			<td align="right"><?=number_format($field['CantidadRecibida'], 4, ',', '.')?></td>
			<td align="right"><?=number_format($field['PrecioUnit'], 4, ',', '.')?></td>
			<td align="right"><?=number_format($field['Total'], 4, ',', '.')?></td>
			<td title="<?=$field['Comentarios']?>"><?=($Comentarios)?></td>
			<td align="center"><?=$field['CodCentroCosto']?></td>
			<td align="center"><?=printValoresGeneral("ESTADO-COMPRA-DETALLE", $field['Estado'])?></td>
			<td align="center"><?=printValoresGeneral("ESTADO-COMPRA", $field['EstadoOrden'])?></td>
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
