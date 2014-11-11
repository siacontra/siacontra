<?php
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
if ($lista == "todos") {
	$titulo = "Lista de Ordenes de Compra";
	$btRevisar = "display:none;";
	$btAprobar = "display:none;";
}
elseif ($lista == "revisar") {
	$titulo = "Revisar Ordenes de Compra";
	$btNuevo = "display:none;";
	$btModificar = "display:none;";
	$btAprobar = "display:none;";
	$fEstado = "PR";
}
elseif ($lista == "aprobar") {
	$titulo = "Aprobar Ordenes de Compra";
	$btNuevo = "display:none;";
	$btModificar = "display:none;";
	$btRevisar = "display:none;";
	$fEstado = "RV";
}
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
if ($fCodOrganismo != "") { $cCodOrganismo = "checked"; $filtro.=" AND (oc.CodOrganismo = '".$fCodOrganismo."')"; } else $dCodOrganismo = "disabled";
if ($fClasificacion != "") { $cClasificacion = "checked"; $filtro.=" AND (oc.Clasificacion = '".$fClasificacion."')"; } else $dClasificacion = "disabled";
if ($fCodProveedor != "") { $cCodProveedor = "checked"; $filtro.=" AND (oc.CodProveedor = '".$fCodProveedor."')"; } else $dCodProveedor = "visibility:hidden;";
if ($fEstado != "") { $cEstado = "checked"; $filtro.=" AND (oc.Estado = '".$fEstado."')"; } else $dEstado = "disabled";
if ($fBuscar != "") { 
	$cBuscar = "checked"; 
	$filtro.=" AND (oc.NroInterno LIKE '%".$fBuscar."%' OR 
					oc.FechaPreparacion LIKE '%".utf8_decode($fBuscar)."%' OR 
					oc.NomProveedor LIKE '%".utf8_decode($fBuscar)."%' OR 
					a1.Descripcion LIKE '%".utf8_decode($fBuscar)."%' OR 
					a2.Descripcion LIKE '%".utf8_decode($fBuscar)."%' OR 
					fp.Descripcion LIKE '%".utf8_decode($fBuscar)."%' OR 
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
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=lg_orden_compra_lista" method="post">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
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
                    <?=loadSelectGeneral("ESTADO-COMPRA", $fEstado, 1)?>
                </select>
                <?
			} else {
				?>
                <input type="checkbox" <?=$cEstado?> onclick="chkFiltro(this.checked, 'fEstado');" />
                <select name="fEstado" id="fEstado" style="width:140px;" <?=$dEstado?>>
					<option value="">&nbsp;</option>
                    <?=loadSelectGeneral("ESTADO-COMPRA", $fEstado, 0)?>
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
			<input type="button" id="btNuevo" value="Nuevo" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=lg_orden_compra_form&opcion=nuevo&origen=lg_orden_compra_lista');" />
            
			<input type="button" id="btModificar" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcionValidar2(this.form, $('#registro').val(), 'accion=orden_compra_modificar', 'gehen.php?anz=lg_orden_compra_form&opcion=modificar&origen=lg_orden_compra_lista', 'SELF', '');" />
            
			<input type="button" id="btVer" value="Ver" style="width:75px; <?=$btVer?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=lg_orden_compra_form&opcion=ver&origen=lg_orden_compra_lista', 'SELF', '', $('#registro').val());" /> | 
            
			<input type="button" id="btRevisar" value="Revisar" style="width:75px; <?=$btRevisar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=lg_orden_compra_form&opcion=revisar&origen=lg_orden_compra_lista', 'SELF', '', $('#registro').val());" />
            
			<input type="button" id="btAprobar" value="Aprobar" style="width:75px; <?=$btAprobar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=lg_orden_compra_form&opcion=aprobar&origen=lg_orden_compra_lista', 'SELF', '', $('#registro').val());" />
            
			<input type="button" id="btAnular" value="Anular" style="width:75px; <?=$btAnular?>" onclick="cargarOpcionValidar2(this.form, $('#registro').val(), 'accion=orden_compra_anular', 'gehen.php?anz=lg_orden_compra_form&opcion=anular&origen=lg_orden_compra_lista', 'SELF', '');" />
            
			<input type="button" id="btCerrar" value="Cerrar" style="width:75px; <?=$btCerrar?>" onclick="cargarOpcionValidar2(this.form, $('#registro').val(), 'accion=orden_compra_cerrar', 'gehen.php?anz=lg_orden_compra_form&opcion=cerrar&origen=lg_orden_compra_lista', 'SELF', '');" /> | 
            
			<input type="button" id="btImprimir" value="Imprimir" style="width:75px; <?=$btImprimir?>" onclick="cargarOpcion2(this.form, 'lg_compras_pdf.php?', 'BLANK', 'height=900, width=1000, left=100, top=0, resizable=no', $('#registro').val());" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:1050px; height:300px;">
<table width="2300" class="tblLista">
	<thead>
    <tr>
		<th scope="col" width="80">Nro. Orden</th>
		<th scope="col" width="75">Fecha Preparaci&oacute;n</th>
		<th scope="col" width="400" align="left">Proveedor</th>
		<th scope="col" width="100" align="right">Monto</th>
		<th scope="col" width="100" align="right">Monto Pagado</th>
		<th scope="col" width="100" align="right">Monto Pendiente</th>
		<th scope="col" width="175">Almacen</th>
		<th scope="col" width="175">Almacen Ingreso</th>
		<th scope="col" width="100">Forma de Pago</th>
		<th scope="col" width="100">Estado</th>
		<th scope="col" align="left">Observaciones</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				oc.Anio,
				oc.CodOrganismo,
				oc.NroOrden,
				oc.FechaPreparacion,
				oc.CodProveedor,
				oc.NomProveedor,
				oc.MontoTotal,
				oc.MontoPendiente,
				oc.Observaciones,
				oc.Estado,
				a1.Descripcion AS NomAlmacen,
				a2.Descripcion AS NomAlmacenIngreso,
				fp.Descripcion AS NomFormaPago
			FROM
				lg_ordencompra oc
				INNER JOIN lg_almacenmast a1 ON (oc.CodAlmacen = a1.Codalmacen)
				LEFT JOIN lg_almacenmast a2 ON (oc.CodAlmacenIngreso = a2.Codalmacen)
				LEFT JOIN mastformapago fp ON (oc.CodFormaPago = fp.CodFormaPago)
				
			WHERE 1 and

			(oc.NroOrden in (select A.NroOrden from lg_verificarimpuordencom as A where  A.Anio = oc.Anio and A.CodOrganismo=oc.CodOrganismo and A.NroOrden=oc.NroOrden)
			and
			oc.NroOrden in (select B.NroOrden from lg_verificarpresuordencom as B where  B.Anio = oc.Anio and B.CodOrganismo=oc.CodOrganismo and B.NroOrden=oc.NroOrden))
			  $filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				oc.Anio,
				oc.CodOrganismo,
				oc.NroOrden,
				oc.NroInterno,
				oc.FechaPreparacion,
				oc.CodProveedor,
				oc.NomProveedor,
				oc.MontoTotal,
				oc.MontoPendiente,
				oc.Observaciones,
				oc.Estado,
				a1.Descripcion AS NomAlmacen,
				a2.Descripcion AS NomAlmacenIngreso,
				fp.Descripcion AS NomFormaPago
			FROM
				lg_ordencompra oc
				INNER JOIN lg_almacenmast a1 ON (oc.CodAlmacen = a1.Codalmacen)
				LEFT JOIN lg_almacenmast a2 ON (oc.CodAlmacenIngreso = a2.Codalmacen)
				LEFT JOIN mastformapago fp ON (oc.CodFormaPago = fp.CodFormaPago)
				
						WHERE 1 and
			
			(oc.NroOrden in (select A.NroOrden from lg_verificarimpuordencom as A where  A.Anio = oc.Anio and A.CodOrganismo=oc.CodOrganismo and A.NroOrden=oc.NroOrden)
			and
			oc.NroOrden in (select B.NroOrden from lg_verificarpresuordencom as B where  B.Anio = oc.Anio and B.CodOrganismo=oc.CodOrganismo and B.NroOrden=oc.NroOrden))
			 $filtro 
			ORDER BY NroInterno
			LIMIT ".intval($limit).", ".intval($maxlimit);
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[Anio].$field[CodOrganismo].$field[NroOrden]";
		if (strlen($field['Observaciones']) > 150) $Observaciones = substr($field['Observaciones'], 0, 150)."...";
		else $Observaciones = $field['Observaciones'];
		$montoPagado=$field['MontoTotal']-$field['MontoPendiente'];
		$montoPendiente=$field['MontoPendiente'];
		if($field['Estado']=='AN') { $montoPagado=0; $montoPendiente=0; }
		
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<td align="center"><?=$field['NroInterno']?></td>
			<td align="center"><?=formatFechaDMA($field['FechaPreparacion'])?></td>
			<td><?=($field['NomProveedor'])?></td>
			<td align="right"><strong><?=number_format($field['MontoTotal'], 2, ',', '.')?></strong></td>
			<td align="right"><?=number_format($montoPagado, 2, ',', '.')?></td>
			<td align="right"><?=number_format($montoPendiente, 2, ',', '.')?></td>
			<td><?=($field['NomAlmacen'])?></td>
			<td><?=($field['NomAlmacenIngreso'])?></td>
			<td align="center"><?=($field['NomFormaPago'])?></td>
			<td align="center"><?=printValoresGeneral("ESTADO-COMPRA", $field['Estado'])?></td>
			<td title="<?=$field['Observaciones']?>"><?=($Observaciones)?></td>
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
