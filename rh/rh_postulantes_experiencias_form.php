<?php
list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
if ($opcion == "nuevo") {
	$accion = "nuevo";
	$label_submit = "Insertar";
	$disabled_nuevo = "disabled";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	list($Postulante, $Secuencia) = split("[.]", $registro);
	//	consulto datos generales
	$sql = "SELECT pe.*
			FROM rh_postulantes_experiencia pe
			WHERE
				pe.Postulante = '".$Postulante."' AND
				pe.Secuencia = '".$Secuencia."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query)) $field = mysql_fetch_array($query);
	
	if ($opcion == "modificar") {
		$accion = "modificar";
		$label_submit = "Modificar";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$display_modificar = "display:none;";
		$visible_modificar = "visibility:hidden;";
	}
	
	elseif ($opcion == "ver") {
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$display_modificar = "display:none;";
		$display_submit = "display:none;";
		$visible_modificar = "visibility:hidden;";
	}
}
//	------------------------------------
?>
<form name="frmentrada" id="frmentrada" action="gehen.php?anz=rh_postulantes_experiencias_lista" method="POST" onsubmit="return postulantes_experiencias(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="Postulante" id="Postulante" value="<?=$Postulante?>" />
<input type="hidden" id="Secuencia" value="<?=$Secuencia?>" />
<input type="hidden" id="FechaActual" value="<?=substr($Ahora, 0, 10)?>" />

<table width="100%" class="tblForm">
	<tr>
    	<td colspan="4" class="divFormCaption">Informaci&oacute;n de la Experiencia Laboral</td>
    </tr>
    <tr>
		<td class="tagForm">* Empresa:</td>
		<td>
			<input type="text" id="Empresa" style="width:95%;" maxlength="255" value="<?=$field['Empresa']?>" <?=$disabled_ver?> />
		</td>
		<td class="tagForm">* Motivo de Cese:</td>
		<td>
			<select id="MotivoCese" style="width:200px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
				<?=getMiscelaneos($field['MotivoCese'], "MOTCESE", 0)?>
			</select>
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Cargo Ocupado:</td>
		<td>
			<input type="text" id="CargoOcupado" style="width:95%;" maxlength="255" value="<?=$field['CargoOcupado']?>" <?=$disabled_ver?> />
		</td>
		<td class="tagForm">Area de Experiencia:</td>
		<td>
			<select id="AreaExperiencia" style="width:200px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
				<?=getMiscelaneos($field['AreaExperiencia'], "AREAEXP", 0)?>
			</select>
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Desde:</td>
		<td>
			<input type="text" id="FechaDesde" value="<?=formatFechaDMA($field['FechaDesde'])?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" <?=$disabled_ver?> />
		</td>
		<td class="tagForm">* Hasta:</td>
		<td>
			<input type="text" id="FechaHasta" value="<?=formatFechaDMA($field['FechaHasta'])?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" <?=$disabled_ver?> />
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Tipo de Ente:</td>
		<td>
			<select id="TipoEnte" style="width:200px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
				<?=getMiscelaneos($field['TipoEnte'], "TIPOENTE", 0)?>
			</select>
		</td>
		<td class="tagForm">Sueldo:</td>
		<td>
			<input type="text" id="Sueldo" style="width:60px; text-align:right;" value="<?=number_format($field['Sueldo'], 2, ',', '.')?>" onfocus="numeroFocus(this);" onblur="numeroBlur(this);" <?=$disabled_ver?> />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Funciones:</td>
		<td colspan="3">
			<textarea id="Funciones" style="width:97%; height:40px;" <?=$disabled_ver?>><?=$field['Funciones']?></textarea>
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
<input type="submit" value="<?=$label_submit?>" style=" <?=$display_submit?>" />
<input type="button" value="Cancelar" onclick="this.form.submit();" />
</center>
</form>