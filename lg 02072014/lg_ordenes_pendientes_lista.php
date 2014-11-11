<?php
if ($filtrar == "default") {
	$fCodOrganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$maxlimit = $_SESSION["MAXLIMIT"];
}
if ($fCodOrganismo != "") { $cCodOrganismo = "checked"; $filtro.=" AND (c.CodOrganismo = '".$fCodOrganismo."')"; } else $dCodOrganismo = "disabled";
if ($fCodProveedor != "") { $cCodProveedor = "checked"; $filtro.=" AND (c.CodProveedor = '".$fCodProveedor."')"; } else $dCodProveedor = "visibility:hidden;";
if ($fCodFormaPago != "") { $cCodFormaPago = "checked"; $filtro.=" AND (c.CodFormaPago = '".$fCodFormaPago."')"; } else $dCodFormaPago = "disabled";
if ($fBuscar != "") { 
	$cBuscar = "checked"; 
	$filtro.=" AND (c.CodProveedor LIKE '%".$fBuscar."%' OR
					c.NomProveedor LIKE '%".utf8_decode($fBuscar)."%' OR 
					cl.Descripcion LIKE '%".utf8_decode($fBuscar)."%' OR
					fp.Descripcion LIKE '%".utf8_decode($fBuscar)."%')";
} else $dBuscar = "disabled";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Generar Ordenes Pendientes</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=lg_ordenes_pendientes_lista" method="post">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:1050px;">
<table width="1050" class="tblFiltro">
	<tr>
		<td align="right" width="125">Organismo:</td>
		<td>
			<input type="checkbox" <?=$cCodOrganismo?> onclick="this.checked=!this.checked" />
			<select name="fCodOrganismo" id="fCodOrganismo" style="width:300px;" <?=$dCodOrganismo?>>
				<?=getOrganismos($fCodOrganismo, 3)?>
			</select>
		</td>
		<td align="right" width="125">Forma de Pago:</td>
		<td>
			<input type="checkbox" <?=$cCodFormaPago?> onclick="chkFiltro(this.checked, 'fCodFormaPago')" />
			<select name="fCodFormaPago" id="fCodFormaPago" style="width:140px;" <?=$dCodFormaPago?>>
				<option value="">&nbsp;</option>
				<?=loadSelect("mastformapago", "CodFormaPago", "Descripcion", $fCodFormaPago, 0)?>
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
		<td align="right">Buscar:</td>
		<td>
			<input type="checkbox" <?=$cBuscar?> onclick="chkFiltro(this.checked, 'fBuscar');" />
			<input type="text" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" style="width:295px;" <?=$dBuscar?> />
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<table width="1050" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" onclick="currentTab('tab', this);" class="current"><a href="#" onclick="mostrarTab('tab', 1, 2);">Compras</a></li>
            <li id="li2" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 2, 2);">Servicios</a></li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<center>
<div id="tab1" style="display:block;">
<table width="1050" class="tblBotones">
	<tr>
		<td><div id="rows_oc"></div></td>
		<td align="right">
			<input type="button" id="btGenerarOC" value="Generar" style="width:75px;" onclick="cargarOpcion2(this.form, 'gehen.php?anz=lg_ordenes_pendientes_compra_form', 'SELF', '', $('#registro').val());" />
		</td>
	</tr>
</table>
<div style="overflow:scroll; width:1050px; height:300px;">
<table width="100%" class="tblLista">
	<thead>
    <tr>
		<th scope="col" width="125" align="left">Clasificaci&oacute;n</th>
		<th scope="col" width="60">Proveedor</th>
		<th scope="col" align="left">Raz&oacute;n Social</th>
		<th scope="col" width="100">Monto</th>
		<th scope="col" width="75">Tipo</th>
		<th scope="col" width="125">Forma de Pago</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				c.*,
				cl.Descripcion AS NomClasificacion,
				fp.Descripcion AS NomFormaPago,
				SUM(Total) AS Total,
				p.Nacionalidad
			FROM
				lg_cotizacion c
				INNER JOIN mastformapago fp ON (c.CodFormaPago = fp.CodFormaPago)
				INNER JOIN lg_requerimientosdet rd ON (c.CodOrganismo = rd.CodOrganismo AND
													   c.CodRequerimiento = rd.CodRequerimiento AND
													   c.Secuencia = rd.Secuencia)
				INNER JOIN lg_requerimientos r ON (r.CodOrganismo = rd.CodOrganismo AND
												   r.CodRequerimiento = rd.CodRequerimiento)
				INNER JOIN lg_clasificacion cl ON (r.Clasificacion = cl.Clasificacion)
				INNER JOIN mastproveedores p ON (c.CodProveedor = p.CodProveedor)
				JOIN lg_informeadjudicacion as iad on iad.CodProveedor=p.CodProveedor
				JOIN lg_adjudicaciondetalle as ad on iad.CodAdjudicacion=ad.CodAdjudicacion and c.CodRequerimiento=ad.CodRequerimiento 
				and c.Secuencia=ad.Secuencia
			WHERE 
				-- c.FlagAsignado = 'S' AND
				rd.Estado = 'PE' AND
				r.Clasificacion <> 'SER'
				$filtro
			GROUP BY NroCotizacionProv, Numero";
	$query_oc = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	$rows_total_oc = mysql_num_rows($query_oc);
	while ($field_compra = mysql_fetch_array($query_oc)) {
		$id = "$field_compra[NroCotizacionProv]";
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<td><?=$field_compra['NomClasificacion']?></td>
            <td align="center"><?=$field_compra['CodProveedor']?></td>
            <td><?=($field_compra['NomProveedor'])?></td>
            <td align="right"><strong><?=number_format($field_compra['Total'], 2, ',', '.')?></strong></td>
            <td align="center"><?=printValoresGeneral("NACIONALIDAD", $field_compra['Nacionalidad'])?></td>
            <td align="center"><?=($field_compra['NomFormaPago'])?></td>
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
		<td><div id="rows_os"></div></td>
		<td align="right">
			<input type="button" id="btGenerarOS" value="Generar" style="width:75px;" onclick="cargarOpcion2(this.form, 'gehen.php?anz=lg_ordenes_pendientes_servicio_form', 'SELF', '', $('#registro').val());" />
		</td>
	</tr>
</table>
<div style="overflow:scroll; width:1050px; height:300px;">
<table width="100%" class="tblLista">
	<thead>
    <tr>
		<th scope="col" width="125" align="left">Clasificaci&oacute;n</th>
		<th scope="col" width="60">Proveedor</th>
		<th scope="col" align="left">Raz&oacute;n Social</th>
		<th scope="col" width="100">Monto</th>
		<th scope="col" width="75">Tipo</th>
		<th scope="col" width="125">Forma de Pago</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				c.*,
				cl.Descripcion AS NomClasificacion,
				fp.Descripcion AS NomFormaPago,
				SUM(Total) AS Total,
				p.Nacionalidad
			FROM
				lg_cotizacion c
				INNER JOIN mastformapago fp ON (c.CodFormaPago = fp.CodFormaPago)
				INNER JOIN lg_requerimientosdet rd ON (c.CodOrganismo = rd.CodOrganismo AND
													   c.CodRequerimiento = rd.CodRequerimiento AND
													   c.Secuencia = rd.Secuencia)
				INNER JOIN lg_requerimientos r ON (r.CodOrganismo = rd.CodOrganismo AND
												   r.CodRequerimiento = rd.CodRequerimiento)
				INNER JOIN lg_clasificacion cl ON (r.Clasificacion = cl.Clasificacion)
				INNER JOIN mastproveedores p ON (c.CodProveedor = p.CodProveedor)
				
				JOIN lg_informeadjudicacion as iad on iad.CodProveedor=p.CodProveedor
				JOIN lg_adjudicaciondetalle as ad on iad.CodAdjudicacion=ad.CodAdjudicacion and c.CodRequerimiento=ad.CodRequerimiento 
				and c.Secuencia=ad.Secuencia
				
			WHERE 

				rd.Estado = 'PE' AND
				r.Clasificacion = 'SER'
			GROUP BY NroCotizacionProv, Numero";
	$query_os = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	$rows_total_os = mysql_num_rows($query_os);
	while ($field_servicio = mysql_fetch_array($query_os)) {
		$id = "$field_servicio[NroCotizacionProv]";
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<td><?=$field_servicio['NomClasificacion']?></td>
            <td align="center"><?=$field_servicio['CodProveedor']?></td>
            <td><?=($field_servicio['NomProveedor'])?></td>
            <td align="right"><strong><?=number_format($field_servicio['Total'], 2, ',', '.')?></strong></td>
            <td align="center"><?=printValoresGeneral("NACIONALIDAD", $field_servicio['Nacionalidad'])?></td>
            <td align="center"><?=($field_servicio['NomFormaPago'])?></td>
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

<script type="text/javascript" language="javascript">
	$(document).ready(function(){
		totalRegistrosGenerarOrden(parseInt(<?=$rows_total_oc?>), parseInt(<?=$rows_total_os?>), "<?=$_ADMIN?>", "<?=$_INSERT?>", "<?=$_UPDATE?>", "<?=$_DELETE?>");
	});
</script>