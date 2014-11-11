<?php
if ($filtrar == "default") {
	$fCodOrganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$fDocumentoClasificacion = "ROC";
	$maxlimit = $_SESSION["MAXLIMIT"];
}
if ($fCodOrganismo != "") { $cOrganismo = "checked"; $filtro.=" AND (d.CodOrganismo = '".$fCodOrganismo."')"; } else $dOrganismo = "disabled";
if ($fbuscar != "") {
	$cbuscar = "checked";
	if ($sltbuscar == "") $filtro.=" AND (d.DocumentoReferencia LIKE '%".$fbuscar."%' OR d.ReferenciaNroDocumento LIKE '%".($fbuscar)."%' OR d.TransaccionNroDocumento LIKE '%".($fbuscar)."%' OR d.Comentarios LIKE '%".($fbuscar)."%')";	
	else $filtro.=" AND ($sltbuscar LIKE '%".$fbuscar."%')";
} else { $dbuscar = "disabled"; $sltbuscar=""; }
if ($fCodProveedor != "") { $cProveedor = "checked"; $filtro.=" AND (d.CodProveedor = '".$fCodProveedor."')"; } else $dProveedor = "visibility:hidden;";
if ($fDocumentoClasificacion != "") { $cDocumentoClasificacion = "checked"; $filtro.=" AND (d.DocumentoClasificacion = '".$fDocumentoClasificacion."')"; } else $dDocumentoClasificacion = "disabled";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Facturaci&oacute;n de Log&iacute;stica</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=ap_facturacion_lista" method="post">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:1050px;">
<table width="1050" class="tblFiltro">
	<tr>
		<td align="right" width="125">Organismo:</td>
		<td>
			<input type="checkbox" <?=$cOrganismo?> onclick="this.checked=!this.checked" />
			<select name="fCodOrganismo" id="fCodOrganismo" style="width:300px;" <?=$dOrganismo?>>
				<?=getOrganismos($fCodOrganismo, 3)?>
			</select>
		</td>
		<td align="right" width="125">Buscar:</td>
		<td>
			<input type="checkbox" <?=$cBuscar?> onclick="chkFiltro(this.checked, 'fBuscar');" />
			<input type="text" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" style="width:200px;" <?=$dBuscar?> />
		</td>
	</tr>
    
	<tr>
		<td align="right">Proveedor: </td>
		<td class="gallery clearfix">
            <input type="checkbox" <?=$cProveedor?> onclick="chkFiltroLista_3(this.checked, 'fCodProveedor', 'fNomProveedor', '', 'btProveedor');" />
            
            <input type="text" id="fCodProveedor" name="fCodProveedor" style="width:50px;" value="<?=$fCodProveedor?>" readonly="readonly" />
			<input type="text" id="fNomProveedor" name="fNomProveedor" style="width:235px;" value="<?=$fNomProveedor?>" readonly="readonly" />
            <a href="../lib/listas/listado_personas.php?filtrar=default&cod=fCodProveedor&nom=fNomProveedor&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]" id="btProveedor" style=" <?=$dProveedor?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
		<td align="right">Forma de Pago:</td>
		<td>
			<input type="checkbox" <?=$cCodFormaPago?> onclick="chkFiltro(this.checked, 'fCodFormaPago');" />
			<select name="fCodFormaPago" style="width:205px;" <?=$dCodFormaPago?>>
            	<option value=""></option>
                <?=loadSelect("mastformapago", "CodFormaPago", "Descripcion", $fCodFormaPago, 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">Clasificacion:</td>
		<td>
			<input type="checkbox" <?=$cDocumentoClasificacion?> onclick="chkFiltro(this.checked, 'fDocumentoClasificacion');" />
			<select name="fDocumentoClasificacion" id="fDocumentoClasificacion" style="width:205px;" <?=$dDocumentoClasificacion?>>
                <?=loadSelect("ap_documentosclasificacion", "DocumentoClasificacion", "Descripcion", $fDocumentoClasificacion, 0)?>
			</select>
		</td>
	</tr>
</table>
</div>
<center>
<input type="submit" value="Buscar">
</center>
</form>
<br />

<center>
<div name="tab1" id="tab1">
<form name="frm_documentos" id="frm_documentos">
<input type="hidden" id="sel_documento" />
<table width="1050" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">
			<input type="button" id="btPrepararFactura" value="Preparar Factura" onclick="cargarOpcionPrepararFactura(this.form);" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:1050px; height:225px;">
<table width="2000" class="tblLista">
	<thead>
		<th scope="col" width="150">Doc. Interno</th>
		<th scope="col" width="75">Fecha</th>
		<th scope="col" width="100">Nro. OC/OS</th>
		<th scope="col" width="100">Transacci&oacute;n</th>
		<th scope="col">Comentarios</th>
		<th scope="col" width="175">Almacen</th>
		<th scope="col" width="100">Monto Afecto</th>
		<th scope="col" width="100">Monto No Afecto</th>
		<th scope="col" width="100">Impuestos</th>
		<th scope="col" width="100">Total</th>
		<th scope="col" width="125">Forma de Pago</th>
		<th scope="col" width="100">Estado</th>
    </thead>
    
    <tbody>
    <?php
	if ($fCodProveedor != "") {
		//	CONSULTO LA TABLA
		if ($fDocumentoClasificacion == "RCD")
			$sql = "SELECT
						d.*,
						a.Descripcion AS NomAlmacen,
						fp.Descripcion AS NomFormaPago,
						oc.NroInterno AS DocumentoReferenciaInterno,
						oc.NroOrden
					FROM 
						ap_documentos d
						INNER JOIN lg_commoditytransaccion t ON (d.CodOrganismo = t.CodOrganismo AND
																d.TransaccionTipoDocumento = t.CodDocumento AND
																d.TransaccionNroDocumento = t.NroDocumento)
						INNER JOIN lg_almacenmast a ON (t.CodAlmacen = a.CodAlmacen)
						INNER JOIN lg_ordencompra oc ON (d.CodOrganismo = oc.CodOrganismo AND
														 d.ReferenciaNroDocumento = oc.NroOrden)
						INNER JOIN mastformapago fp ON (oc.CodFormaPago = fp.CodFormaPago)
					WHERE 
						d.Estado = 'PR'  AND
						d.ReferenciaTipodocumento = 'OC' $filtro
					ORDER BY Fecha, ReferenciaTipoDocumento, ReferenciaNroDocumento, TransaccionNroDocumento";
		elseif ($fDocumentoClasificacion == "ROC")
			$sql = "SELECT
						d.*,
						a.Descripcion AS NomAlmacen,
						fp.Descripcion AS NomFormaPago,
						/*t.DocumentoReferenciaInterno*/
						t.ReferenciaNroDocumento as DocumentoReferenciaInterno
					FROM 
						ap_documentos d
						INNER JOIN lg_transaccion t ON (d.CodOrganismo = t.CodOrganismo AND
														d.TransaccionTipoDocumento = t.CodDocumento AND
														d.TransaccionNroDocumento = t.NroDocumento)
						INNER JOIN lg_almacenmast a ON (t.CodAlmacen = a.CodAlmacen)
						INNER JOIN lg_ordencompra oc ON (d.CodOrganismo = oc.CodOrganismo AND
														 d.ReferenciaNroDocumento = oc.NroOrden)
						INNER JOIN mastformapago fp ON (oc.CodFormaPago = fp.CodFormaPago)
					WHERE 
						d.Estado = 'PR'  AND
						d.ReferenciaTipodocumento = 'OC' $filtro
					ORDER BY Fecha, ReferenciaTipoDocumento, ReferenciaNroDocumento, TransaccionNroDocumento";
		elseif ($fDocumentoClasificacion == "SER")
			$sql = "SELECT
						d.*,
						'' AS NomAlmacen,
						fp.Descripcion AS NomFormaPago,
						oc.NroInterno AS DocumentoReferenciaInterno
					FROM 
						ap_documentos d
						INNER JOIN lg_ordenservicio oc ON (d.CodOrganismo = oc.CodOrganismo AND
														   d.ReferenciaNroDocumento = oc.NroOrden)
						INNER JOIN mastformapago fp ON (oc.CodFormaPago = fp.CodFormaPago)
					WHERE 
						d.Estado = 'PR' AND
						d.ReferenciaTipodocumento = 'OS' $filtro					
					ORDER BY Fecha, ReferenciaTipoDocumento, ReferenciaNroDocumento";
		
		$query_documento = mysql_query($sql) or die ($sql.mysql_error());
		$rows_total = mysql_num_rows($query_documento);
		//	MUESTRO LA TABLA
		while ($field_documento = mysql_fetch_array($query_documento)) {
			$id = $field_documento['Anio'].".".$field_documento['DocumentoReferencia'];
			?>
			<tr class="trListaBody" onclick="mClkMulti(this); mostrarDocumentosObligacion();" id="row_<?=$id?>">
				<td align="center">
                	<input type="checkbox" name="documento" id="<?=$id?>" value="<?=$id?>" style="display:none" />
					<?=$field_documento['DocumentoReferencia']?>
				</td>
				<td align="center"><?=formatFechaDMA($field_documento['Fecha'])?></td>
				<td align="center"><?=$field_documento['ReferenciaTipoDocumento']?>-<?=$field_documento['DocumentoReferenciaInterno']?></td>
				<td align="center"><?=$field_documento['TransaccionTipoDocumento']?>-<?=$field_documento['TransaccionNroDocumento']?></td>
				<td><?=($field_documento['Comentarios'])?></td>
				<td align="center"><?=($field_documento['NomAlmacen'])?></td>
				<td align="right"><?=number_format($field_documento['MontoAfecto'], 2, ',', '.')?></td>
				<td align="right"><?=number_format($field_documento['MontoNoAfecto'], 2, ',', '.')?></td>
				<td align="right"><?=number_format($field_documento['MontoImpuestos'], 2, ',', '.')?></td>
				<td align="right"><?=number_format($field_documento['MontoTotal'], 2, ',', '.')?></td>
				<td align="center"><?=($field_documento['NomFormaPago'])?></td>
				<td align="center"><?=printValoresGeneral("ESTADO-DOCUMENTOS", $field_documento['Estado'])?></td>
			</tr>
			<?
		}
	} else $rows_total = 0;
	?>
    </tbody>
</table>
</div>
</form>
</div>
</center>

<form name="frm_detalles" id="frm_detalles">
<center>
<div style="overflow:scroll; width:1050px; height:225px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
    	<td colspan="7" class="divFormCaption">Detalles del Documento</td>
    </tr>
	<tr>
		<th scope="col" width="50">#</th>
		<th scope="col" width="100">Item</th>
		<th scope="col">Descripci&oacute;n</th>
		<th scope="col" width="100">C. Costos</th>
		<th scope="col" width="100">Cant.</th>
		<th scope="col" width="100">Precio Unit.</th>
		<th scope="col" width="100">Total</th>
	</tr>
    </thead>
    
    <tbody id="lista_detalles">
    
    </tbody>
</table>
</div>
</center>
</form>
</div>
