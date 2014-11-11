<?php
if ($opcion == "nuevo") {
	$FlagInterno = "S";
	$field['FlagExterno'] = "N";
	##
	$titulo = "Nuevo Registro";
	$accion = "nuevo";
	$disabled_externo = "disabled";
	$label_submit = "Guardar";
	$clkCancelar = "document.getElementById('frmentrada').submit();";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	list($CodPersona, $Secuencia) = split("[_]", $sel_registros);
	//	consulto datos generales
	$sql = "SELECT
				*,
				p.NomCompleto AS NomResponsable
			FROM
				rh_meritosfaltas mf
				LEFT JOIN mastpersonas AS p ON (p.CodPersona = mf.Responsable)
			WHERE
				mf.CodPersona = '".$CodPersona."' AND
				mf.Secuencia = '".$Secuencia."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query)) $field = mysql_fetch_array($query);
	
	if ($opcion == "modificar") {
		$titulo = "Modificar Registro";
		$accion = "modificar";
		$disabled_modificar = "disabled";
		if ($field['FlagExterno'] == "S") $visibility_interno = "visibility:hidden;"; else { $disabled_externo = "disabled"; $FlagInterno = "S"; }
		$label_submit = "Modificar";
		$clkCancelar = "document.getElementById('frmentrada').submit();";
	}
	
	elseif ($opcion == "ver") {
		$titulo = "Ver Registro";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$disabled_externo = "disabled";
		$display_submit = "display:none;";
		$visibility_interno = "visibility:hidden;";
		if ($field['FlagExterno'] != "S") $FlagInterno = "S";
		$clkCancelar = "document.getElementById('frmentrada').submit();";
	}
}
//	------------------------------------
?>
<form name="frmentrada" id="frmentrada" action="gehen.php?anz=empleados_meritos_lista" method="POST" enctype="multipart/form-data" onsubmit="return empleados_meritos(this, '<?=$accion?>');" autocomplete="off">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />
<input type="hidden" name="CodPersona" id="CodPersona" value="<?=$CodPersona?>" />
<input type="hidden" id="Secuencia" value="<?=$Secuencia?>" />
<input type="hidden" id="Tipo" value="M" />

<table width="885" class="tblForm">
	<tr>
    	<td colspan="2" class="divFormCaption">Datos del M&eacute;rito</td>
    </tr>
    <tr>
		<td class="tagForm" width="150">* M&eacute;rito:</td>
		<td>
			<select id="Clasificacion" style="width:175px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
				<?=getMiscelaneos($field['Clasificacion'], "MERITO", 0)?>
			</select>
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Documento:</td>
		<td>
        	<input type="text" id="Documento" style="width:300px;" maxlength="50" value="<?=htmlentities($field['Documento'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
    <tr>
        <td class="tagForm">* Fecha Doc.:</td>
		<td>
        	<input type="text" id="FechaDoc" style="width:60px;" class="datepicker" maxlength="10" value="<?=formatFechaDMA($field['FechaDoc'])?>" onChange="setFechaDMA(this);" <?=$disabled_ver?> />
		</td>
    </tr>
    <tr>
		<td class="tagForm">* Responsable:</td>
		<td>
        	<input type="checkbox" id="FlagInterno" <?=chkFlag($FlagInterno)?> <?=$disabled_ver?> onClick="setFlagInterno(this.checked);" />
            <input type="hidden" id="Responsable" value="<?=$field['Responsable']?>" />
			<input type="text" id="NomResponsable" style="width:300px;" value="<?=$field['NomResponsable']?>" disabled="disabled" />
            <a href="javascript:" onClick="parent.document.getElementById('a_ResponsableMerito').click();" style=" <?=$visibility_interno?>" id="a_Responsable">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
	</tr>
    <tr>
		<td class="tagForm">* Persona Externa:</td>
		<td>
        	<input type="checkbox" id="FlagExterno" <?=chkFlag($field['FlagExterno'])?> <?=$disabled_ver?> onClick="setFlagExterno(this.checked);" />
        	<input type="text" id="Externo" style="width:300px;" maxlength="100" value="<?=htmlentities($field['Externo'])?>" <?=$disabled_externo?> />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Observaciones:</td>
		<td>
        	<textarea id="Observacion" style="width:95%; height:50px;" <?=$disabled_ver?>><?=htmlentities($field['Observacion'])?></textarea>
		</td>
	</tr>
    <tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td>
			<input type="text" size="30" value="<?=$field['UltimoUsuario']?>" disabled="disabled" />
			<input type="text" size="25" value="<?=$field['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>
</table>
<center>
<input type="submit" value="<?=$label_submit?>" style="width:75px; <?=$display_submit?>" />
<input type="button" value="Cancelar" style="width:75px;" onclick="<?=$clkCancelar?>" />
</center>
</form>
<div style="width:885px" class="divMsj">Campos Obligatorios *</div>