<?php
$Ahora = ahora();
if ($opcion == "nuevo") {
	$accion = "nuevo";
	$titulo = "Nuevo Commodity";
	$label_submit = "Guardar";
	$field_commodity['Estado'] = "A";
	$registro = "";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	//	consulto datos generales
	$sql = "SELECT * FROM lg_commoditymast WHERE CommodityMast = '".$registro."'";
	$query_commodity = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_commodity)) $field_commodity = mysql_fetch_array($query_commodity);
	
	if ($opcion == "modificar") {
		$titulo = "Modificar Commodity";
		$accion = "modificar";
		$disabled_modificar = "disabled";
		$display_modificar = "display:none;";
		$label_submit = "Modificar";
		$disabled_sub = "disabled";
	}
	
	elseif ($opcion == "ver") {
		$titulo = "Ver Commodity";
		$disabled_ver = "disabled";
		$display_ver = "display:none;";
		$display_submit = "display:none;";
		$disabled_modificar = "disabled";
		$display_modificar = "display:none;";
		$disabled_sub = "disabled";
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

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=lg_commodity_lista" method="POST" onsubmit="return commodity(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="fEstado" id="fEstado" value="<?=$fEstado?>" />
<input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />
<input type="hidden" name="fClasificacion" id="fClasificacion" value="<?=$fClasificacion?>" />

<table width="700" class="tblForm">
	<tr>
    	<td colspan="2" class="divFormCaption">Datos Generales</td>
    </tr>
	<tr>
		<td class="tagForm">* Commodity:</td>
		<td><input type="text" id="CommodityMast" style="width:50px;" class="codigos" value="<?=$field_commodity['CommodityMast']?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm">* Descripci&oacute;n:</td>
		<td><input type="text" id="Descripcion" maxlength="255" style="width:90%;" value="<?=($field_commodity['Descripcion'])?>" /></td>
	</tr>
	<tr>
		<td class="tagForm">* Clasificaci&oacute;n:</td>
		<td>
			<select id="Clasificacion" style="width:175px;">
				<?=loadSelect("lg_commodityclasificacion", "Clasificacion", "Descripcion", $field_commodity['Clasificacion'], 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
			<input type="radio" name="Estado" id="Activo" value="A" <?=chkOpt($field_commodity['Estado'], "A")?> /> Activo
			<input type="radio" name="Estado" id="Inactivo" value="I" <?=chkOpt($field_commodity['Estado'], "I")?> /> Inactivo
		</td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td>
			<input type="text" size="30" value="<?=$field_commodity['UltimoUsuario']?>" disabled="disabled" />
			<input type="text" size="25" value="<?=$field_commodity['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>  
</table>
<center> 
<input type="submit" value="<?=$label_submit?>" style=" <?=$display_submit?>" />
<input type="button" value="Cancelar" onclick="this.form.submit();" />
</center>
</form>
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
<br />

<form name="frm_detalle" id="frm_detalle">
<input type="hidden" id="sel_detalle" />
<center>
<div style="width:700px" class="divFormCaption">Sub-Clasificaci&oacute;n</div>
<table width="700" class="tblBotones">
    <tr>
        <td class="gallery clearfix">
            <a id="aSelPartida" href="../lib/listas/listado_clasificador_presupuestario.php?filtrar=default&cod=cod_partida&nom=NomPartida&campo3=CodCuenta&campo4=NomCuenta&ventana=selListadoLista&seldetalle=sel_detalle&iframe=true&width=1050&height=500" rel="prettyPhoto[iframe1]" style="display:none;"></a>
            <a id="aSelCuenta" href="../lib/listas/listado_plan_cuentas.php?filtrar=default&cod=CodCuenta&nom=NomCuenta&ventana=selListadoLista&seldetalle=sel_detalle&iframe=true&width=1050&height=500" rel="prettyPhoto[iframe2]" style="display:none;"></a>
            <a id="aSelActivo" href="../lib/listas/listado_clasificacion_activos.php?filtrar=default&cod=CodClasificacion&nom=NomClasificacion&ventana=selListadoLista&seldetalle=sel_detalle&iframe=true&width=1050&height=500" rel="prettyPhoto[iframe3]" style="display:none;"></a>
            <input type="button" class="btLista" id="btSelPartida" value="Sel. Partida" onclick="validarAbrirLista('sel_detalle', 'aSelPartida');" <?=$disabled_ver?> />
            <input type="button" class="btLista" id="btSelCuenta" value="Sel. Cuenta" onclick="validarAbrirLista('sel_detalle', 'aSelCuenta');" <?=$disabled_ver?> />
            <input type="button" class="btLista" id="btSelActivo" value="Sel. Activo" onclick="validarAbrirLista('sel_detalle', 'aSelActivo');" <?=$disabled_ver?> />
        </td>
        <td align="right">
            <input type="button" class="btLista" value="Insertar" onclick="insertarLinea2(this, 'commodity_detalle_insertar', 'detalle', true, '');" <?=$disabled_ver?> />
            <input type="button" class="btLista" value="Quitar" onclick="quitarLineaCommoditySub(this, 'detalle');" <?=$disabled_sub?> />
        </td>
    </tr>
</table>
<div style="overflow:scroll; width:700px; height:250px;">
<table width="850" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="15">#</th>
		<th scope="col" width="50">Clase</th>
		<th scope="col">Descripci&oacute;n</th>
		<th scope="col" width="75">Partida</th>
		<th scope="col" width="75">Cuenta</th>
		<th scope="col" width="75">Clasificaci&oacute;n Activo</th>
		<th scope="col" width="100">Unidad</th>
		<th scope="col" width="75">Estado</th>
    </tr>
    </thead>
    
    <tbody id="lista_detalle">
    <?
    $sql = "SELECT *
			FROM lg_commoditysub
			WHERE CommodityMast = '".$registro."'
			ORDER BY Codigo";
    $query_detalle = mysql_query($sql) or die ($sql.mysql_error());	$nro_detalle=0;
    while ($field_detalle = mysql_fetch_array($query_detalle)) {
        $nro_detalle++;
        ?>
        <tr class="trListaBody" onclick="mClk(this, 'sel_detalle');" id="detalle_<?=$nro_detalle?>">
            <th><?=$nro_detalle?></th>
            <td align="center">
            	<input type="hidden" name="Codigo" id="Codigo_<?=$nro_detalle?>" value="<?=$field_detalle['Codigo']?>" />
                <input type="text" name="CommoditySub" id="CommoditySub_<?=$nro_detalle?>" value="<?=$field_detalle['CommoditySub']?>" style="text-align:center;" maxlength="3" class="cell" <?=$disabled_sub?> />
            </td>
            <td align="center">
                <input type="text" name="Descripcion" value="<?=($field_detalle['Descripcion'])?>" maxlength="255" class="cell" <?=$disabled_ver?> />
            </td>
            <td align="center">
                <input type="text" name="cod_partida" id="cod_partida_<?=$nro_detalle?>" value="<?=$field_detalle['cod_partida']?>" style="text-align:center;" maxlength="12" class="cell" onChange="getDescripcionLista2('accion=getDescripcionPartidaCuenta', this, $('#NomPartida_<?=$nro_detalle?>'), $('#CodCuenta_<?=$nro_detalle?>'), $('#NomCuenta_<?=$nro_detalle?>'));" <?=$disabled_ver?> />
                <input type="hidden" name="NomPartida" id="NomPartida_<?=$nro_detalle?>" />
            </td>
            <td align="center">
                <input type="text" name="CodCuenta" id="CodCuenta_<?=$nro_detalle?>" value="<?=$field_detalle['CodCuenta']?>" maxlength="13" style="text-align:center;" class="cell" onChange="getDescripcionLista2('accion=getDescripcionCuenta', this, $('#NomCuenta_<?=$nro_detalle?>'));" <?=$disabled_ver?> />
                <input type="hidden" name="NomCuenta" id="NomCuenta_<?=$nro_detalle?>" />
            </td>
            <td align="center">
                <input type="text" name="CodClasificacion" id="CodClasificacion_<?=$nro_detalle?>" value="<?=$field_detalle['CodClasificacion']?>" style="text-align:center;" class="cell" onChange="getDescripcionLista2('accion=getDescripcionClasificacionActivo', this, $('#NomClasificacion_<?=$nro_detalle?>'));" <?=$disabled_ver?> />
                <input type="hidden" name="NomClasificacion" id="NomClasificacion_<?=$nro_detalle?>" value="<?=$field_detalle['NomClasificacion']?>" />
            </td>
            <td align="center">
                <select name="CodUnidad" class="cell" <?=$disabled_ver?>>
                    <?=loadSelect("mastunidades", "CodUnidad", "Descripcion", $field_detalle['CodUnidad'], 0)?>
                </select>
            </td>
            <td align="center">
                <select name="Estado" class="cell" <?=$disabled_ver?>>
                    <?=loadSelectGeneral("ESTADO", $field_detalle['Estado'], 0)?>
                </select>
            </td>
        </tr>
        <?
    }
    ?>
    </tbody>
</table>
</div>
</center>
<input type="hidden" id="nro_detalle" value="<?=$nro_detalle?>" />
<input type="hidden" id="can_detalle" value="<?=$nro_detalle?>" />
</form>