<?php
if ($opcion == "nuevo") {
	$field['Estado'] = "A";
	$accion = "nuevo";
	$titulo = "Nuevo Registro";
	$label_submit = "Guardar";
	$disabled_nuevo = "disabled";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	//	consulto datos generales
	$sql = "SELECT *
			FROM rh_tipoevaluacion
			WHERE TipoEvaluacion = '".$registro."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query)) $field = mysql_fetch_array($query);
	
	if ($opcion == "modificar") {
		$titulo = "Modificar Registro";
		$accion = "modificar";
		$label_submit = "Modificar";
		$disabled_modificar = "disabled";
		if ($field['FlagSistema'] == "S") $disabled_sistema = "disabled";
	}
	
	elseif ($opcion == "ver") {
		$titulo = "Ver Registro";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$disabled_sistema = "disabled";
		$disabled_grados = "disabled";
		$display_submit = "display:none;";
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

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=rh_evaluacion_tipo_lista" method="POST" onsubmit="return evaluacion_tipo(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />
<input type="hidden" name="fEstado" id="fEstado" value="<?=$fEstado?>" />

<table width="700" class="tblForm">
	<tr>
    	<td colspan="2" class="divFormCaption">Tipo de Evaluaci&oacute;n</td>
    </tr>
	<tr>
		<td class="tagForm" width="100">* C&oacute;digo:</td>
		<td>
			<input type="text" id="TipoEvaluacion" style="width:50px;" class="codigo" value="<?=$field['TipoEvaluacion']?>" <?=$disabled_modificar?> />
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Denominaci&oacute;n:</td>
		<td>
			<input type="text" id="Descripcion" style="width:90%;" maxlength="50" value="<?=($field['Descripcion'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
    <tr>
		<td class="tagForm">&nbsp;</td>
		<td>
			<input type="checkbox" id="FlagSistema" <?=chkFlag($field['FlagSistema'])?> <?=$disabled_sistema?> /> Transacci&oacute;n del Sistema
		</td>
	</tr>
    <tr>
		<td class="tagForm">Estado:</td>
		<td>
			<input type="radio" name="Estado" id="Activo" value="A" <?=chkOpt($field['Estado'], "A")?> <?=$disabled_nuevo?> /> Activo
            &nbsp; &nbsp; &nbsp; 
            <input type="radio" name="Estado" id="Inactivo" value="I" <?=chkOpt($field['Estado'], "I");?> <?=$disabled_nuevo?> /> Inactivo
		</td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td>
			<input type="text" size="30" class="disabled" value="<?=$field['UltimoUsuario']?>" disabled="disabled" />
			<input type="text" size="25" class="disabled" value="<?=$field['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>
</table>
<center>
<input type="submit" value="<?=$label_submit?>" style="width:75px; <?=$display_submit?>" />
<input type="button" value="Cancelar" style="width:75px;" onclick="this.form.submit();" />
</center>
</form>
<br />
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>
<br />
<center>
<form name="frm_grados" id="frm_grados">
<input type="hidden" id="sel_grados" />
<div style="width:700px" class="divFormCaption">Grados de Calificaci&oacute;n</div>
<table width="700" class="tblBotones">
    <tr>
        <td align="right">
            <input type="button" class="btLista" value="Insertar" onclick="insertar(this, 'grados', true, 'accion=evaluacion_tipo_grados_insertar');" <?=$disabled_grados?> />
            <input type="button" class="btLista" value="Borrar" onclick="quitarLinea(this, 'grados');" <?=$disabled_grados?> />
        </td>
    </tr>
</table>
<div style="overflow:scroll; width:700px; height:250px;">
<table width="100%" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="15">&nbsp;</th>
        <th scope="col" width="35">Cod.</th>
        <th scope="col" align="left">Descripci&oacute;n</th>
        <th scope="col" width="55">Puntaje Min.</th>
        <th scope="col" width="55">Puntaje Max.</th>
        <th scope="col" width="75">Estado</th>
    </tr>
    </thead>
    
    <tbody id="lista_grados">
    <?
    $sql = "SELECT *
			FROM rh_gradoscompetencia
			WHERE TipoEvaluacion = '".$field['TipoEvaluacion']."'
			ORDER BY Grado";
    $query_grados = mysql_query($sql) or die ($sql.mysql_error());
    while ($field_grados = mysql_fetch_array($query_grados)) {	$nro_grados++;
        ?>
        <tr class="trListaBody" onclick="mClk(this, 'sel_grados');" id="grados_<?=$nro_grados?>">
            <th>
				<?=$nro_grados?>
            </th>
            <td>
            	<input type="text" name="Grado" class="cell" style="text-align:center;" maxlength="4" value="<?=$field_grados['Grado']?>" <?=$disabled_grados?> />
            </td>
            <td>
            	<input type="text" name="Descripcion" class="cell" maxlength="50" value="<?=htmlentities($field_grados['Descripcion'])?>" <?=$disabled_grados?> />
            </td>
            <td>
            	<input type="text" name="PuntajeMin" class="cell" style="text-align:center;" maxlength="4" value="<?=$field_grados['PuntajeMin']?>" <?=$disabled_grados?> />
            </td>
            <td>
            	<input type="text" name="PuntajeMax" class="cell" style="text-align:center;" maxlength="4" value="<?=$field_grados['PuntajeMax']?>" <?=$disabled_grados?> />
            </td>
            <td>
            	<select name="Estado" class="cell" <?=$disabled_grados?>>
                    <?=loadSelectGeneral("ESTADO", $field_grados['Estado'], 0)?>
                </select>
            </td>
        </tr>
        <?
    }
    ?>
    </tbody>
</table>
</div>
<input type="hidden" id="nro_grados" value="<?=$nro_grados?>" />
<input type="hidden" id="can_grados" value="<?=$nro_grados?>" />
</form>
</center>
