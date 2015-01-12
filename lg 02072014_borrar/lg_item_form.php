<?php
$Ahora = ahora();
if ($opcion == "nuevo") {
	$accion = "nuevo";
	$titulo = "Nuevo Item";
	$label_submit = "Guardar";
	$field_item['Estado'] = "A";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	//	consulto datos generales
	$sql = "SELECT
				i.*,
				cl.Descripcion AS NomLinea,
				cf.Descripcion AS NomFamilia,
				csf.Descripcion AS NomSubFamilia
			FROM
				lg_itemmast i
				INNER JOIN lg_clasesubfamilia csf ON (i.CodSubFamilia = csf.CodSubFamilia AND
													  i.CodFamilia = csf.CodFamilia AND
													  i.CodLinea = csf.CodLinea)
				INNER JOIN lg_clasefamilia cf ON (i.CodFamilia = cf.CodFamilia AND 
												  i.CodLinea = cf.CodLinea)
				INNER JOIN lg_claselinea cl ON (i.CodLinea = cl.CodLinea)
			WHERE i.CodItem = '".$registro."'";
	$query_item = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_item)) $field_item = mysql_fetch_array($query_item);
	
	if ($opcion == "modificar") {
		$titulo = "Modificar Item";
		$accion = "modificar";
		$disabled_modificar = "disabled";
		$display_modificar = "display:none;";
		$label_submit = "Modificar";
	}
	
	elseif ($opcion == "ver") {
		$titulo = "Ver Item";
		$disabled_ver = "disabled";
		$display_ver = "display:none;";
		$display_submit = "display:none;";
		$disabled_modificar = "disabled";
		$display_modificar = "display:none;";
	}
}
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="document.getElementById('frmentrada').submit()">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<table width="700" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" onclick="currentTab('tab', this);" class="current"><a href="#" onclick="mostrarTab('tab', 1, 2);">Datos Generales</a></li>
            <li id="li2" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 2, 2);">Informaci&oacute;n Adicional</a></li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=lg_item_lista" method="POST" onsubmit="return items(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="fEstado" id="fEstado" value="<?=$fEstado?>" />
<input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />
<input type="hidden" name="fCodProcedencia" id="fCodProcedencia" value="<?=$fCodProcedencia?>" />
<input type="hidden" name="fOrdenar" id="fOrdenar" value="<?=$fOrdenar?>" />

<div id="tab1" style="display:block;">
<table width="700" class="tblForm">
	<tr>
    	<td colspan="4" class="divFormCaption">Datos Generales</td>
    </tr>
	<tr>
		<td class="tagForm" width="150">Item:</td>
		<td><input type="text" id="CodItem" style="width:125px;" class="codigo" value="<?=$field_item['CodItem']?>" disabled="disabled" /></td>
		<td class="tagForm">* Tipo de Item:</td>
		<td>
			<select id="CodTipoItem" style="width:200px;">
				<?=loadSelect("lg_tipoitem", "CodTipoItem", "Descripcion", $field_item['CodTipoItem'], 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Descripci&oacute;n:</td>
		<td colspan="3"><input type="text" id="Descripcion" style="width:90%;" maxlength="255" value="<?=($field_item['Descripcion'])?>" /></td>
	</tr>
	<tr>
		<td class="tagForm">* Unidad de Medida:</td>
		<td>
			<select id="CodUnidad" style="width:132px;">
				<?=loadSelect("mastunidades", "CodUnidad", "Descripcion", $field_item['CodUnidad'], 0)?>
			</select>
		</td>
        <td class="tagForm">* Linea:</td>
        <td class="gallery clearfix">
            <input type="text" id="CodLinea" style="width:125px;" value="<?=$field_item['CodLinea']?>" disabled="disabled" />
			<a href="../lib/listas/listado_familias.php?filtrar=default&cod=CodLinea&nom=CodFamilia&campo3=CodSubFamilia&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
	</tr>
	<tr>
		<td class="tagForm">Unidad de Compra:</td>
		<td>
			<select id="CodUnidadComp" style="width:132px;">
				<option value="">&nbsp;</option>
				<?=loadSelect("mastunidades", "CodUnidad", "Descripcion", $field_item['CodUnidadComp'], 0)?>
			</select>
		</td>
        <td class="tagForm">Familia:</td>
        <td>
            <input type="text" id="CodFamilia" style="width:125px;" value="<?=$field_item['CodFamilia']?>" disabled="disabled" />
        </td>
	</tr>
	<tr>
		<td class="tagForm">Unidad de Embalaje:</td>
		<td>
			<select id="CodUnidadEmb" style="width:132px;">
				<option value="">&nbsp;</option>
				<?=loadSelect("mastunidades", "CodUnidad", "Descripcion", $field_item['CodUnidadEmb'], 0)?>
			</select>
		</td>
        <td class="tagForm">Sub-Familia:</td>
        <td>
            <input type="text" id="CodSubFamilia" style="width:125px;" value="<?=$field_item['CodSubFamilia']?>" disabled="disabled" />
        </td>
	</tr>
	<tr>
    	<td colspan="4" class="divFormCaption">Caracter&iacute;sticas</td>
    </tr>
	<tr>
		<td class="tagForm" width="150">* Cod. Interno:</td>
		<td><input type="text" id="CodInterno" style="width:125px;" maxlength="10" value="<?=$field_item['CodInterno']?>" /></td>
		<td class="tagForm">Imagen del Item:</td>
		<td>
        	<input type="text" id="Imagen" style="width:125px;" maxlength="25" value="<?=$field_item['Imagen']?>" />
        </td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<input type="checkbox" id="FlagLotes" <?=chkFlag($field_item['FlagLotes'])?> /> Se maneja por lotes <br />
			<input type="checkbox" id="FlagItem" <?=chkFlag($field_item['FlagItem'])?> /> Considerado como KIT <br />
			<input type="checkbox" id="FlagKit" <?=chkFlag($field_item['FlagKit'])?> /> Tiene # de Serie x Item
		</td>
		<td>&nbsp;</td>
		<td>
			<input type="checkbox" id="FlagImpuestoVentas" <?=chkFlag($field_item['FlagImpuestoVentas'])?> /> Afecto Imp. Ventas <br />
			<input type="checkbox" id="FlagAuto" <?=chkFlag($field_item['FlagAuto'])?> /> Auto-Requisicionamiento <br />
			<input type="checkbox" id="FlagDisponible" <?=chkFlag($field_item['FlagDisponible'])?> /> Disponible para Ventas
		</td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td colspan="3">
			<input type="radio" name="Estado" id="Activo" value="A" <?=chkOpt($field_item['Estado'], "A")?> /> Activo
			<input type="radio" name="Estado" id="Inactivo" value="I" <?=chkOpt($field_item['Estado'], "I")?> /> Inactivo
		</td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td colspan="3">
			<input type="text" size="30" value="<?=$field_item['UltimoUsuario']?>" disabled="disabled" />
			<input type="text" size="25" value="<?=$field_item['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>  
</table>
</div>

<div id="tab2" style="display:none;">
<table width="700" class="tblForm">
	<tr>
    	<td colspan="2" class="divFormCaption">Caracter&iacute;sticas</td>
    </tr>
	<tr>
		<td class="tagForm" width="150">Marca:</td>
		<td>
			<select id="CodMarca" style="width:200px;">
				<option value="">&nbsp;</option>
				<?=loadSelect("lg_marcas", "CodMarca", "Descripcion", $field_item['CodMarca'], 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Color:</td>
		<td>
			<select id="Color" style="width:125px;">
				<option value="">&nbsp;</option>
				<?=getMiscelaneos($field_item['Color'], "COLOR", 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Procedencia:</td>
		<td>
			<select id="CodProcedencia" style="width:125px;">
				<?=loadSelect("lg_procedencias", "CodProcedencia", "Descripcion", $field_item['CodProcedencia'], 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Cod. Barra:</td>
		<td><input type="text" id="CodBarra" style="width:200px;" maxlength="25" value="<?=$field_item['CodBarra']?>" /></td>
	</tr>
	<tr>
		<td class="tagForm">Stock Minimo:</td>
		<td><input type="text" id="StockMin" style="width:100px; text-align:right;" value="<?=$field_item['StockMin']?>" /></td>
	</tr>
	<tr>
		<td class="tagForm">Stock Máximo:</td>
		<td><input type="text" id="StockMax" style="width:100px; text-align:right;" value="<?=$field_item['StockMax']?>" /></td>
	</tr>
	<tr>
    	<td colspan="2" class="divFormCaption">Informaci&oacute;n Contable</td>
    </tr>
	<tr>
		<td class="tagForm">Cta. Inventario:</td>
		<td class="gallery clearfix">
        	<input type="text" id="CtaInventario" disabled="disabled" style="width:100px;" value="<?=$field_item['CtaInventario']?>" />
			<input type="hidden" id="NomCtaInventario" />
			<a href="../lib/listas/listado_plan_cuentas.php?filtrar=default&cod=CtaInventario&nom=NomCtaInventario&iframe=true&width=950&height=525" rel="prettyPhoto[iframe2]">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Cta. Gasto:</td>
		<td class="gallery clearfix">
        	<input type="text" id="CtaGasto" disabled="disabled" style="width:100px;" value="<?=$field_item['CtaGasto']?>" />
			<input type="hidden" id="NomCtaGasto" />
			<a href="../lib/listas/listado_plan_cuentas.php?filtrar=default&cod=CtaGasto&nom=NomCtaGasto&iframe=true&width=950&height=525" rel="prettyPhoto[iframe3]">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Cta. Venta:</td>
		<td class="gallery clearfix">
        	<input type="text" id="CtaVenta" disabled="disabled" style="width:100px;" value="<?=$field_item['CtaVenta']?>" />
			<input type="hidden" id="NomCtaVenta" />
			<a href="../lib/listas/listado_plan_cuentas.php?filtrar=default&cod=CtaVenta&nom=NomCtaVenta&iframe=true&width=950&height=525" rel="prettyPhoto[iframe4]">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Partida Presupuestaria:</td>
		<td class="gallery clearfix">
        	<input type="text" id="PartidaPresupuestal" disabled="disabled" style="width:100px;" value="<?=$field_item['PartidaPresupuestal']?>" />
			<input type="hidden" id="NomPartidaPresupuestal" />
			<a href="../lib/listas/listado_clasificador_presupuestario.php?filtrar=default&cod=PartidaPresupuestal&nom=NomPartidaPresupuestal&iframe=true&width=950&height=525" rel="prettyPhoto[iframe5]">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
	</tr>
</table>
</div>
<center> 
<input type="submit" value="<?=$label_submit?>" style=" <?=$display_submit?>" />
<input type="button" value="Cancelar" onclick="this.form.submit();" />
</center>
</form>
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
