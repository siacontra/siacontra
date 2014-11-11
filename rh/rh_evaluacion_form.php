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
			FROM rh_evaluacion
			WHERE Evaluacion = '".$registro."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query)) $field = mysql_fetch_array($query);
	
	if ($opcion == "modificar") {
		$titulo = "Modificar Registro";
		$accion = "modificar";
		$label_submit = "Modificar";
		$disabled_modificar = "disabled";
	}
	
	elseif ($opcion == "ver") {
		$titulo = "Ver Registro";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
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

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=rh_evaluacion_lista" method="POST" onsubmit="return evaluacion(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />
<input type="hidden" name="fEstado" id="fEstado" value="<?=$fEstado?>" />
<input type="hidden" name="fTipoEvaluacion" id="fTipoEvaluacion" value="<?=$fTipoEvaluacion?>" />

<table width="550" class="tblForm">
	<tr>
    	<td colspan="4" class="divFormCaption">Evaluaci&oacute;n</td>
    </tr>
	<tr>
		<td class="tagForm">C&oacute;digo:</td>
		<td colspan="3">
			<input type="text" id="Evaluacion" style="width:50px;" class="codigo" value="<?=$field['Evaluacion']?>" disabled />
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Denominaci&oacute;n:</td>
		<td colspan="3">
			<input type="text" id="Descripcion" style="width:350px;" maxlength="40" value="<?=htmlentities($field['Descripcion'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
    <tr>
		<td class="tagForm" width="125">* Puntaje Min.:</td>
		<td>
			<input type="text" id="PuntajeMin" style="width:60px;" maxlength="4" value="<?=htmlentities($field['PuntajeMin'])?>" <?=$disabled_ver?> />
		</td>
		<td class="tagForm" width="125">* Puntaje Max.:</td>
		<td>
			<input type="text" id="PuntajeMax" style="width:60px;" maxlength="4" value="<?=htmlentities($field['PuntajeMax'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Tipo de Evaluaci&oacute;n:</td>
		<td colspan="3">
            <select id="TipoEvaluacion" style="width:300px;" <?=$disabled_ver?>>
                <option value="">&nbsp;</option>
                <?=loadSelect("rh_tipoevaluacion", "TipoEvaluacion", "Descripcion", $field['TipoEvaluacion'], 0)?>
            </select>
		</td>
	</tr>
    <tr>
		<td class="tagForm">Plantilla:</td>
		<td colspan="3">
            <select id="Plantilla" style="width:300px;" <?=$disabled_ver?>>
                <option value="">&nbsp;</option>
            </select>
		</td>
	</tr>
    <tr>
		<td class="tagForm">Estado:</td>
		<td colspan="3">
			<input type="radio" name="Estado" id="Activo" value="A" <?=chkOpt($field['Estado'], "A")?> <?=$disabled_nuevo?> /> Activo
            &nbsp; &nbsp; 
            <input type="radio" name="Estado" id="Inactivo" value="I" <?=chkOpt($field['Estado'], "I");?> <?=$disabled_nuevo?> /> Inactivo
		</td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td colspan="3">
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
<div style="width:550px" class="divMsj">Campos Obligatorios *</div>
<br />
