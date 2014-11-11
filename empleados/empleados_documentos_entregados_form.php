<?php
if ($opcion == "nuevo") {
	$field['Estado'] = "A";
	##
	$titulo = "Nuevo Registro";
	$accion = "nuevo";
	$label_submit = "Guardar";
	$disabled_nuevo = "disabled";
	$disabled_presento = "disabled";
	$visibility_familiar = "visibility:hidden;";
	$clkCancelar = "document.getElementById('frmentrada').submit();";
	$actualizarRuta = "document.getElementById('Ruta').click();";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	list($CodPersona, $CodDocumento) = split("[_]", $sel_registros);
	//	consulto datos generales
	$sql = "SELECT
				ed.*
			FROM
				rh_empleado_documentos ed
			WHERE
				ed.CodPersona = '".$CodPersona."' AND
				ed.CodDocumento = '".$CodDocumento."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query)) $field = mysql_fetch_array($query);
	
	if ($opcion == "modificar") {
		$titulo = "Modificar Registro";
		$accion = "modificar";
		$disabled_modificar = "disabled";
		if ($field['FlagPresento'] != "S") $disabled_presento = "disabled";
		if ($field['FlagCarga'] != "S") $visibility_familiar = "visibility:hidden;";
		$label_submit = "Modificar";
		$clkCancelar = "document.getElementById('frmentrada').submit();";
		$actualizarRuta = "document.getElementById('Ruta').click();";
	}
	
	elseif ($opcion == "ver") {
		$titulo = "Ver Registro";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$disabled_presento = "disabled";
		$display_submit = "display:none;";
		$visibility_familiar = "visibility:hidden;";
		$clkCancelar = "document.getElementById('frmentrada').submit();";
	}
}
if ($field['Ruta'] == "") {
	$field['Ruta'] = "foto_blank.png";
	$RutaAnterior = "";
} else $RutaAnterior = $field['Ruta'];
$Ruta = $_PARAMETRO["PATHIMGDOC"].$field['Ruta'];
if (!file_exists($Ruta)) $Ruta = $_PARAMETRO["PATHIMGDOC"]."img_blank.png";
//	------------------------------------
?>
<form name="frmentrada" id="frmentrada" action="gehen.php?anz=empleados_documentos_entregados_lista" method="POST" enctype="multipart/form-data" onsubmit="return empleados_documentos_entregados(this, '<?=$accion?>');" autocomplete="off">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />
<input type="hidden" name="CodPersona" id="CodPersona" value="<?=$CodPersona?>" />
<input type="hidden" name="FlagCopiarImagen" id="FlagCopiarImagen" value="N" />
<input type="hidden" name="RutaAnterior" id="RutaAnterior" value="<?=$RutaAnterior?>" />
<input type="hidden" id="CodDocumento" value="<?=$CodDocumento?>" />

<table width="885" class="tblForm">
	<tr>
    	<td colspan="5" class="divFormCaption">Datos del Documento</td>
    </tr>
	<tr>
		<td class="tagForm">* Documento:</td>
		<td>
            <select id="Documento" style="width:175px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=getMiscelaneos($field['Documento'], "DOCUMENTOS", 0);?>
            </select>
		</td>
		<td>
			<input type="checkbox" id="FlagPresento" <?=chkFlag($field['FlagPresento'])?> onclick="setFlagPresento(this.checked);" <?=$disabled_ver?> /> Entregado
		</td>
		<td colspan="2" align="center">
			<input type="checkbox" id="FlagCarga" <?=chkFlag($field['FlagCarga'])?> onclick="setFlagCarga(this.checked);" <?=$disabled_ver?> /> Documento Familiar
		</td>
	</tr>
    <tr>
        <td class="tagForm">* Fecha Entrega:</td>
		<td colspan="2">
        	<input type="text" id="FechaPresento" style="width:60px;" class="datepicker" maxlength="10" value="<?=formatFechaDMA($field['FechaPresento'])?>" onchange="setFechaDMA(this);" <?=$disabled_presento?> />
		</td>
        <td class="tagForm">Fecha Vencimiento:</td>
		<td>
        	<input type="text" id="FechaVence" style="width:60px;" class="datepicker" maxlength="10" value="<?=formatFechaDMA($field['FechaVence'])?>" onchange="setFechaDMA(this);" <?=$disabled_presento?> />
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Persona Relacionada:</td>
		<td colspan="2">
            <input type="hidden" id="CargaFamiliar" value="<?=$field['CargaFamiliar']?>" />
			<input type="text" id="NomCargaFamiliar" style="width:300px;" value="<?=$field['NomCargaFamiliar']?>" disabled="disabled" />
            <a href="javascript:" onClick="parent.document.getElementById('a_carga_familiar').click();" style=" <?=$visibility_familiar?>" id="a_carga_familiar">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
		<td class="tagForm">Parentesco:</td>
		<td>
        	<input type="text" id="Parentesco" style="width:150px;" value="<?=htmlentities($field['Parentesco'])?>" disabled />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Observaciones:</td>
		<td colspan="4">
        	<textarea id="Observaciones" style="width:98%; height:30px;" <?=$disabled_ver?>><?=htmlentities($field['Observaciones'])?></textarea>
        </td>
	</tr>
    <tr>
		<td class="tagForm"></td>
		<td colspan="4" height="100">
        	<img src="<?=$Ruta?>" style="max-height:100px; max-width:80px; cursor:pointer;" id="imgRuta" onclick="<?=$actualizarRuta?>" title="Actualizar Documento del Empleado" />
            <input type="file" name="Ruta" id="Ruta" class="ui-corner-all" style="width:200px; display:none;" onchange="copiarImagenTMP(this.form, this.id, 'imgRuta');" <?=$disabled_ver?> />
        </td>
	</tr>
    <tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td colspan="4">
			<input type="text" size="30" value="<?=$field['UltimoUsuario']?>" disabled="disabled" />
			<input type="text" size="25" value="<?=$field['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>
</table>
<center>
<input type="submit" value="<?=$label_submit?>" style="width:75px; <?=$display_submit?>" />
<input type="button" value="Cancelar" style="width:75px;" onclick="$('#frmentrada').attr('action', 'gehen.php?anz=empleados_documentos_entregados_lista').attr('target', ''); <?=$clkCancelar?>" />
</center>
</form>
<div style="width:885px" class="divMsj">Campos Obligatorios *</div>

<iframe name="imagen_tmp" id="imagen_tmp" style="display:none;"></iframe>