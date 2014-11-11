<?php
$Ahora = ahora();
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
//	------------------------------------
if ($filtrar == "default") {
	$fCodOrganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$maxlimit = $_SESSION["MAXLIMIT"];
}
if ($fCodOrganismo != "") { $cCodOrganismo = "checked"; $filtro.=" AND (r.CodOrganismo = '".$fCodOrganismo."')"; } else $dCodOrganismo = "disabled";
if ($fCodDependencia != "") { $cCodDependencia = "checked"; $filtro.=" AND (r.CodDependencia = '".$fCodDependencia."')"; } else $dCodDependencia = "disabled";
if ($fFechaRequerida != "") { $cFechaRequerida = "checked"; $filtro.=" AND (r.FechaRequerida <= '".formatFechaAMD($fFechaRequerida)."')"; } else $dFechaRequerida = "disabled";
if ($fCodAlmacen != "") { $cCodAlmacen = "checked"; $filtro.=" AND (r.CodAlmacen = '".$fCodAlmacen."')"; } else $dCodAlmacen = "disabled";
if ($fBuscar != "") {
	$cBuscar = "checked";
	$filtro.=" AND (r.CodRequerimiento LIKE '%".$fBuscar."%' OR
					r.Comentarios LIKE '%".$fBuscar."%' OR
					r.CodInterno LIKE '%".$fBuscar."%' OR
					a.Descripcion LIKE '%".utf8_decode($fBuscar)."%' OR
					r.CodCentroCosto LIKE '%".utf8_decode($fBuscar)."%')";
} else $dBuscar = "disabled";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Recepci&oacute;n / Despacho de Requerimientos de Caja Chica</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=lg_transaccion_cajachica_lista" method="post">
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
		<td align="right" width="125">Fecha Requerida <=: </td>
		<td>
			<input type="checkbox" <?=$cFechaRequerida?> onclick="chkFiltro(this.checked, 'fFechaRequerida');" />
			<input type="text" name="fFechaRequerida" id="fFechaRequerida" value="<?=$fFechaRequerida?>" <?=$dFechaRequerida?> maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" />
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
				<?=loadSelect("lg_almacenmast", "CodAlmacen", "Descripcion", $fCodAlmacen, 0)?>
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
            <li id="li1" onclick="currentTab('tab', this);" class="current"><a href="#" onclick="mostrarTab('tab', 1, 2);">Recepci&oacute;n</a></li>
            <li id="li2" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 2, 2);">Despacho</a></li>
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
		<th scope="col" width="90">Requerimiento</th>
		<th scope="col" width="35">C.C</th>
		<th scope="col" width="65">Fecha Requerida</th>
		<th scope="col" align="left">Comentarios</th>
		<th scope="col" width="150">Almacen</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				r.CodRequerimiento,
				r.CodInterno,
				r.Comentarios,
				r.CodDependencia,
				r.CodCentroCosto,
				r.FechaRequerida,
				cc.Descripcion AS NomCentroCosto,
				a.Descripcion AS NomAlmacen,
				d.Dependencia
			FROM
				lg_requerimientos r
				INNER JOIN ac_mastcentrocosto cc ON (r.CodCentroCosto = cc.CodCentroCosto)
				INNER JOIN lg_requerimientosdet rd ON (rd.CodRequerimiento = r.CodRequerimiento)
				INNER JOIN mastdependencias d ON (r.CodDependencia = d.CodDependencia)
				INNER JOIN lg_almacenmast a ON (r.CodAlmacen = a.CodAlmacen)
			WHERE
				(r.Estado = 'AP' AND r.FlagCajaChica = 'S') AND
				(rd.Estado = 'PE') AND
				rd.CantidadPedida > rd.CantidadOrdenCompra $filtro
			GROUP BY CodRequerimiento
			ORDER BY Dependencia, CodDependencia, CodRequerimiento, CodItem, CommoditySub";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		if (strlen($field['Comentarios']) > 175) { $Comentarios = substr($field['Comentarios'], 0, 175)."..."; $TitleComentarios = $field['Comentarios'];}
		else { $Comentarios = $field['Comentarios']; $TitleComentarios = ""; }
		##
		if ($grupo != $field['CodDependencia']) {
			$grupo = $field['CodDependencia'];
			?>
            <tr class="trListaBody2">
                <td colspan="4"><?=($field['Dependencia'])?></td>
            </tr>
            <?
		}
		##
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro'); appendAjax('accion=transaccion_cajachica_recepcion_detalles&registro='+$('#registro').val(), $('#lista_detalles_recepcion'));" id="<?=$field['CodRequerimiento']?>.rec">
            <td align="right"><?=$field['CodInterno']?></td>
            <td align="center"><?=$field['CodCentroCosto']?></td>
            <td align="center"><?=formatFechaDMA($field['FechaRequerida'])?></td>
            <td title="<?=$TitleComentarios?>"><?=($Comentarios)?></td>
            <td><?=($field['NomAlmacen'])?></td>
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
			<input type="button" value="Recepcionar" style="width:75px;" onclick="cargarOpcionRecepcionCajaChica(this.form);" /> | 
			
            <input type="button" value="Cerrar" style="width:75px;" onclick="opcionRegistroMultiple(this.form, 'Secuencia', 'caja-chica', 'cerrar-detalle-requerimiento', true);" />
            
            
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
        <th scope="col" width="90">C&oacute;digo</th>
        <th scope="col" align="left">Descripci&oacute;n</th>
        <th scope="col" width="25">Uni.</th>
        <th scope="col" width="55">Cant. Pedida</th>
        <th scope="col" width="55">Cant. Pendiente</th>
        <th scope="col" width="35">C.C</th>
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
		<th scope="col" width="90">Requerimiento</th>
		<th scope="col" width="35">C.C</th>
		<th scope="col" width="65">Fecha Requerida</th>
		<th scope="col" align="left">Comentarios</th>
		<th scope="col" width="150">Almacen</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				r.CodRequerimiento,
				r.CodInterno,
				r.Comentarios,
				r.CodDependencia,
				r.CodCentroCosto,
				r.FechaRequerida,
				cc.Descripcion AS NomCentroCosto,
				a.Descripcion AS NomAlmacen,
				d.Dependencia
			FROM
				lg_requerimientos r
				INNER JOIN ac_mastcentrocosto cc ON (r.CodCentroCosto = cc.CodCentroCosto)
				INNER JOIN lg_requerimientosdet rd ON (rd.CodRequerimiento = r.CodRequerimiento)
				INNER JOIN mastdependencias d ON (r.CodDependencia = d.CodDependencia)
				INNER JOIN lg_almacenmast a ON (r.CodAlmacen = a.CodAlmacen)
			WHERE
				(r.Estado = 'AP' AND r.FlagCajaChica = 'S') AND
				(rd.Estado = 'PE') AND
				(rd.CantidadOrdenCompra > rd.CantidadRecibida) $filtro
			GROUP BY CodRequerimiento
			ORDER BY Dependencia, CodDependencia, CodRequerimiento, CodItem, CommoditySub";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		if (strlen($field['Comentarios']) > 175) { $Comentarios = substr($field['Comentarios'], 0, 175)."..."; $TitleComentarios = $field['Comentarios'];}
		else { $Comentarios = $field['Comentarios']; $TitleComentarios = ""; }
		##
		if ($grupo != $field['CodDependencia']) {
			$grupo = $field['CodDependencia'];
			?>
            <tr class="trListaBody2">
                <td colspan="4"><?=($field['Dependencia'])?></td>
            </tr>
            <?
		}
		##
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro'); appendAjax('accion=transaccion_cajachica_despacho_detalles&registro='+$('#registro').val(), $('#lista_detalles_despacho'));" id="<?=$field['CodRequerimiento']?>.des">
            <td align="right"><?=$field['CodInterno']?></td>
            <td align="center"><?=$field['CodCentroCosto']?></td>
            <td align="center"><?=formatFechaDMA($field['FechaRequerida'])?></td>
            <td title="<?=$TitleComentarios?>"><?=($Comentarios)?></td>
            <td><?=($field['NomAlmacen'])?></td>
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
			<input type="button" value="Despachar" style="width:75px;" onclick="cargarOpcionDespachoCajaChica(this.form);" /> | 
			
            <input type="button" value="Cerrar" style="width:75px;" onclick="opcionRegistroMultiple(this.form, 'Secuencia', 'almacen', 'cerrar-detalle-compras', true);" />
            
            
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
        <th scope="col" width="90">C&oacute;digo</th>
        <th scope="col" align="left">Descripci&oacute;n</th>
        <th scope="col" width="25">Uni.</th>
        <th scope="col" width="55">Cant. Pedida</th>
        <th scope="col" width="55">Cant. Pendiente</th>
        <th scope="col" width="35">C.C</th>
	</tr>
    </thead>
    
    <tbody id="lista_detalles_despacho">
    
    </tbody>
</table>
</div>
</div>
</form>
</center>