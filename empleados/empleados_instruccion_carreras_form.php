<?php
if ($opcion == "nuevo") {
	$field['Estado'] = "A";
	##
	$titulo = "Nuevo Registro";
	$accion = "nuevo";
	$label_submit = "Guardar";
	$disabled_nuevo = "disabled";
	$clkCancelar = "document.getElementById('frmentrada').submit();";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	list($CodPersona, $Secuencia) = split("[_]", $registro);
	//	consulto datos generales
	$sql = "SELECT
				ei.*,
				ce.Descripcion AS NomCentroEstudio
			FROM
				rh_empleado_instruccion ei
				INNER JOIN rh_centrosestudios ce ON (ce.CodCentroEstudio = ei.CodCentroEstudio)
			WHERE
				ei.CodPersona = '".$CodPersona."' AND
				ei.Secuencia = '".$Secuencia."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query)) $field = mysql_fetch_array($query);
	
	if ($opcion == "modificar") {
		$titulo = "Modificar Registro";
		$accion = "modificar";
		$disabled_modificar = "disabled";
		$label_submit = "Modificar";
		$clkCancelar = "document.getElementById('frmentrada').submit();";
	}
	
	elseif ($opcion == "ver") {
		$titulo = "Ver Registro";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$display_submit = "display:none;";
		$clkCancelar = "document.getElementById('frmentrada').submit();";
	}
}
//	------------------------------------
?>
<form name="frmentrada" id="frmentrada" action="gehen.php?anz=empleados_instruccion_carreras_lista" method="POST" enctype="multipart/form-data" onsubmit="return empleados_instruccion_carreras(this, '<?=$accion?>');" autocomplete="off">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />
<input type="hidden" name="CodPersona" id="CodPersona" value="<?=$CodPersona?>" />

<table width="885" class="tblForm">
	<tr>
    	<td colspan="4" class="divFormCaption">Datos del Estudio</td>
    </tr>
    <tr>
		<td class="tagForm" width="150">* Grado de Instrucci&oacute;n:</td>
		<td>
			<select id="CodGradoInstruccion" style="width:275px;" onChange="getOptionsSelect(this.value, 'nivel-instruccion', 'Nivel', true); getOptionsSelectMultiple($('#CodProfesion'), 'profesion', 'CodGradoInstruccion='+$('#CodGradoInstruccion').val()+'&Area='+$('#Area').val(), true);" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
				<?=loadSelect("rh_gradoinstruccion", "CodGradoInstruccion", "Descripcion", $field['CodGradoInstruccion'], 0)?>
			</select>
		</td>
        <td class="tagForm" width="125">Nro.:</td>
		<td>
        	<input type="text" id="Secuencia" style="width:25px;" class="codigo" value="<?=$field['Secuencia']?>" disabled />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Nivel de Instrucci&oacute;n:</td>
		<td>
			<select id="Nivel" style="width:275px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
				<?=loadSelectDependienteSE("rh_nivelgradoinstruccion", "Nivel", "Descripcion", "CodGradoInstruccion", $field['Nivel'], $field['CodGradoInstruccion'], 0)?>
			</select>
		</td>
        <td class="tagForm">* Fecha Graduaci&oacute;n:</td>
		<td>
        	<input type="text" id="FechaGraduacion" style="width:60px;" class="datepicker" maxlength="10" value="<?=formatFechaDMA($field['FechaGraduacion'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Area:</td>
		<td>
            <select id="Area" style="width:275px;;" onChange="getOptionsSelectMultiple($('#CodProfesion'), 'profesion', 'CodGradoInstruccion='+$('#CodGradoInstruccion').val()+'&Area='+$('#Area').val(), true);" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=getMiscelaneos($field['Area'], "AREA", 0);?>
            </select>
		</td>
        <td class="tagForm">Periodo:</td>
		<td>
        	<input type="text" id="FechaDesde" style="width:60px;" class="datepicker" maxlength="10" value="<?=formatFechaDMA($field['FechaDesde'])?>" <?=$disabled_ver?> /> -
        	<input type="text" id="FechaHasta" style="width:60px;" class="datepicker" maxlength="10" value="<?=formatFechaDMA($field['FechaHasta'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Profesi&oacute;n:</td>
		<td>
            <select id="CodProfesion" style="width:275px;;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=loadSelectProfesiones($field['CodProfesion'], $field['CodGradoInstruccion'], $field['Area'], 0);?>
            </select>
		</td>
		<td class="tagForm">Col. Profesional:</td>
		<td>
            <select id="Colegiatura" style="width:145px;;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=getMiscelaneos($field['Colegiatura'], "COLEGIOS", 0);?>
            </select>
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Centro Estudio:</td>
		<td>
            <input type="hidden" id="CodCentroEstudio" value="<?=$field['CodCentroEstudio']?>" />
			<input type="text" id="NomCentroEstudio" style="width:270px;" value="<?=$field['NomCentroEstudio']?>" disabled="disabled" />
            <a href="javascript:" onClick="parent.document.getElementById('a_CodCentroCosto').click();" style=" <?=$display_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
        <td class="tagForm">Nro. Colegiatura:</td>
		<td>
        	<input type="text" id="NroColegiatura" style="width:139px;" maxlength="9" value="<?=$field['NroColegiatura']?>" <?=$disabled_ver?> />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Observaciones:</td>
		<td colspan="3">
        	<textarea id="Observaciones" style="width:98%; height:30px;" <?=$disabled_ver?>><?=htmlentities($field['Observaciones'])?></textarea>
        </td>
	</tr>
    <tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td colspan="3">
			<input type="text" size="30" value="<?=$field['UltimoUsuario']?>" disabled="disabled" />
			<input type="text" size="25" value="<?=$field['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>
</table>
<center>
<input type="submit" value="<?=$label_submit?>" style=" <?=$display_submit?>" />
<input type="button" value="Cancelar" onclick="<?=$clkCancelar?>" />
</center>
</form>
<div style="width:885px" class="divMsj">Campos Obligatorios *</div>