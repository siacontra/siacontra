<?php
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
if ($lista == "todos") {
	$titulo = "Lista de Ordenes de Servicio";
	$btRevisar = "display:none;";
	$btAprobar = "display:none;";
}
elseif ($lista == "revisar") {
	$titulo = "Revisar Ordenes de Servicio";
	$btNuevo = "display:none;";
	$btModificar = "display:none;";
	$btAprobar = "display:none;";
	$fEstado = "PR";
}
elseif ($lista == "aprobar") {
	$titulo = "Aprobar Ordenes de Servicio";
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
if ($fCodOrganismo != "") { $cCodOrganismo = "checked"; $filtro.=" AND (os.CodOrganismo = '".$fCodOrganismo."')"; } else $dCodOrganismo = "disabled";
if ($fCodDependencia != "") { $cCodDependencia = "checked"; $filtro.=" AND (os.CodDependencia = '".$fCodDependencia."')"; } else $dCodDependencia = "disabled";
if ($fCodProveedor != "") { $cCodProveedor = "checked"; $filtro.=" AND (os.CodProveedor = '".$fCodProveedor."')"; } else $dCodProveedor = "visibility:hidden;";
if ($fEstado != "") { $cEstado = "checked"; $filtro.=" AND (os.Estado = '".$fEstado."')"; } else $dEstado = "disabled";
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
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=lg_orden_servicio_lista" method="post">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:1050px;">
<table width="1050" class="tblFiltro">
	<tr>
		<td align="right" width="125">Organismo:</td>
		<td>
			<input type="checkbox" <?=$cCodOrganismo?> onclick="this.checked=!this.checked" />
			<select name="fCodOrganismo" id="fCodOrganismo" style="width:300px;" onchange="getOptionsSelect(this.value, 'dependencia', 'fCodDependencia', true);" <?=$dCodOrganismo?>>
				<?=getOrganismos($fCodOrganismo, 3)?>
			</select>
		</td>
		<td align="right">Buscar:</td>
		<td>
			<input type="checkbox" <?=$cBuscar?> onclick="chkFiltro(this.checked, 'fBuscar');" />
			<input type="text" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" style="width:295px;" <?=$dBuscar?> />
		</td>
	</tr>
    <tr>
		<td align="right" width="125">Dependencia:</td>
		<td>
			<input type="checkbox" <?=$cCodDependencia?> onclick="chkFiltro(this.checked, 'fCodDependencia')" />
			<select name="fCodDependencia" id="fCodDependencia" style="width:300px;" <?=$dCodDependencia?>>
				<option value="">&nbsp;</option>
				<?=getDependencias($fCodDependencia, $fCodOrganismo, 3)?>
			</select>
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
		<td align="right">Proveedor: </td>
		<td class="gallery clearfix">
            <input type="checkbox" <?=$cCodProveedor?> onclick="chkFiltroLista_3(this.checked, 'fCodProveedor', 'fNomProveedor', '', 'btProveedor');" />
            
            <input type="text" name="fCodProveedor" id="fCodProveedor" style="width:50px;" value="<?=$fCodProveedor?>" readonly="readonly" />
			<input type="text" name="fNomProveedor" id="fNomProveedor" style="width:235px;" value="<?=$fNomProveedor?>" readonly="readonly" />
            <a href="../lib/listas/listado_personas.php?filtrar=default&cod=fCodProveedor&nom=fNomProveedor&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]" id="btProveedor" style=" <?=$dCodProveedor?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
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
			<input type="button" id="btNuevo" value="Nuevo" style="width:75px; <?=$btNuevo?>" onclick="cargarPagina(this.form, 'gehen.php?anz=lg_orden_servicio_form&opcion=nuevo&origen=lg_orden_servicio_lista');" />
            
			<input type="button" id="btModificar" value="Modificar" style="width:75px; <?=$btModificar?>" onclick="cargarOpcionValidar2(this.form, $('#registro').val(), 'accion=orden_servicio_modificar', 'gehen.php?anz=lg_orden_servicio_form&opcion=modificar&origen=lg_orden_servicio_lista', 'SELF', '');" />
            
			<input type="button" id="btVer" value="Ver" style="width:75px; <?=$btVer?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=lg_orden_servicio_form&opcion=ver&origen=lg_orden_servicio_lista', 'SELF', '', 'registro');" /> | 
            
			<input type="button" id="btRevisar" value="Revisar" style="width:75px; <?=$btRevisar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=lg_orden_servicio_form&opcion=revisar&origen=lg_orden_servicio_lista', 'SELF', '', $('#registro').val());" />
            
			<input type="button" id="btAprobar" value="Aprobar" style="width:75px; <?=$btAprobar?>" onclick="cargarOpcion2(this.form, 'gehen.php?anz=lg_orden_servicio_form&opcion=aprobar&origen=lg_orden_servicio_lista', 'SELF', '', $('#registro').val());" />
            
			<input type="button" id="btAnular" value="Anular" style="width:75px; <?=$btAnular?>" onclick="cargarOpcionValidar2(this.form, $('#registro').val(), 'accion=orden_servicio_anular', 'gehen.php?anz=lg_orden_servicio_form&opcion=anular&origen=lg_orden_servicio_lista', 'SELF', '');" />
            
			<input type="button" id="btCerrar" value="Cerrar" style="width:75px; <?=$btCerrar?>" onclick="cargarOpcionValidar2(this.form, $('#registro').val(), 'accion=orden_servicio_cerrar', 'gehen.php?anz=lg_orden_servicio_form&opcion=cerrar&origen=lg_orden_servicio_lista', 'SELF', '');" /> | 
            
			<input type="button" id="btImprimir" value="Imprimir" style="width:75px; <?=$btImprimir?>" onclick="cargarOpcion2(this.form, 'lg_servicios_pdf.php?', 'BLANK', 'height=900, width=1000, left=100, top=0, resizable=no', $('#registro').val());" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:1050px; height:300px;">
<table width="3100" class="tblLista">
	<thead>
    <tr>
		<th scope="col" width="90">Nro. Orden</th>
		<th scope="col" width="75">Fecha Preparaci&oacute;n</th>
		<th scope="col" width="350" align="left">Proveedor</th>
		<th scope="col" width="600" align="left">Descripci&oacute;n</th>
		<th scope="col" width="100" align="right">Monto</th>
		<th scope="col" width="100">Estado</th>
		<th scope="col" width="600" align="left">Descripci&oacute;n Adicional</th>
		<th scope="col" width="400" align="left">Raz&oacute;n Rechazo</th>
		<th scope="col" align="left">Observaciones</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				os.Anio,
				os.CodOrganismo,
				os.NroOrden,
				os.Descripcion,
				os.DescAdicional,
				os.MotRechazo,
				os.Observaciones,
				os.FechaPreparacion,
				os.NomProveedor,
				os.TotalMontoIva,
				os.Estado,
				os.NroInterno
			FROM lg_ordenservicio os
			WHERE 1 and
			
			(os.NroOrden in (select A.NroOrden from lg_verificarimpuordenser as A where  A.Anio = os.Anio and A.CodOrganismo=os.CodOrganismo and A.NroOrden=os.NroOrden)
			and
			os.NroOrden in (select B.NroOrden from lg_verificarpresuordenser as B where  B.Anio = os.Anio and B.CodOrganismo=os.CodOrganismo and B.NroOrden=os.NroOrden))
			$filtro";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT
				os.Anio,
				os.CodOrganismo,
				os.NroOrden,
				os.Descripcion,
				os.DescAdicional,
				os.MotRechazo,
				os.Observaciones,
				os.FechaPreparacion,
				os.NomProveedor,
				os.TotalMontoIva,
				os.Estado,
				os.NroInterno
			FROM lg_ordenservicio os
			WHERE 1 and
			
			(os.NroOrden in (select A.NroOrden from lg_verificarimpuordenser as A where  A.Anio = os.Anio and A.CodOrganismo=os.CodOrganismo and A.NroOrden=os.NroOrden)
			and
			os.NroOrden in (select B.NroOrden from lg_verificarpresuordenser as B where  B.Anio = os.Anio and B.CodOrganismo=os.CodOrganismo and B.NroOrden=os.NroOrden))
			
			 $filtro
			ORDER BY NroInterno
			LIMIT ".intval($limit).", ".intval($maxlimit);
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[Anio].$field[CodOrganismo].$field[NroOrden]";
		if (strlen($field['Descripcion']) > 150) $Descripcion = substr($field['Descripcion'], 0, 150)."..."; else $Descripcion = $field['Descripcion'];
		if (strlen($field['DescAdicional']) > 150) $DescAdicional = substr($field['DescAdicional'], 0, 150)."..."; else $DescAdicional = $field['DescAdicional'];
		if (strlen($field['MotRechazo']) > 150) $MotRechazo = substr($field['MotRechazo'], 0, 150)."..."; else $MotRechazo = $field['MotRechazo'];
		if (strlen($field['Observaciones']) > 150) $Observaciones = substr($field['Observaciones'], 0, 150)."..."; else $Observaciones = $field['Observaciones'];
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<td align="center"><?=$field['NroOrden']?></td>
			<td align="center"><?=formatFechaDMA($field['FechaPreparacion'])?></td>
			<td><?=($field['NomProveedor'])?></td>
			<td title="<?=$field['Descripcion']?>"><?=($Descripcion)?></td>
			<td align="right"><strong><?=number_format($field['TotalMontoIva'], 2, ',', '.')?></strong></td>
			<td align="center"><?=printValoresGeneral("ESTADO-SERVICIO", $field['Estado']);$field['Estado']?></td>
			<td title="<?=$field['DescAdicional']?>"><?=($DescAdicional)?></td>
			<td title="<?=$field['MotRechazo']?>"><?=($MotRechazo)?></td>
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
