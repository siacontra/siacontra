<?php
list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
if ($opcion == "nuevo") {
	$accion = "nuevo";
	$label_submit = "Insertar";
	$disabled_nuevo = "disabled";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	list($Postulante, $CodIdioma) = split("[.]", $registro);
	//	consulto datos generales
	$sql = "SELECT *
			FROM rh_postulantes_idiomas
			WHERE
				Postulante = '".$Postulante."' AND
				CodIdioma = '".$CodIdioma."'";
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
<form name="frmentrada" id="frmentrada" action="gehen.php?anz=rh_postulantes_idiomas_lista" method="POST" onsubmit="return postulantes_idiomas(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="Postulante" id="Postulante" value="<?=$Postulante?>" />

<table width="100%" class="tblForm">
	<tr>
    	<td colspan="2" class="divFormCaption">Datos del Registro</td>
    </tr>
    <tr>
		<td class="tagForm">Idioma:</td>
		<td>
			<select id="CodIdioma" style="width:200px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
				<?=loadSelect("mastidioma", "CodIdioma", "DescripcionLocal", $field['CodIdioma'], 0)?>
			</select>
		</td>
	</tr>
    <tr>
		<td class="tagForm">Lectura:</td>
		<td>
			<select id="NivelLectura" style="width:125px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
				<?=getMiscelaneos($field['NivelLectura'], "NIVEL", 0)?>
			</select>
		</td>
	</tr>
    <tr>
		<td class="tagForm">Oral:</td>
		<td>
			<select id="NivelOral" style="width:125px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
				<?=getMiscelaneos($field['NivelOral'], "NIVEL", 0)?>
			</select>
		</td>
	</tr>
    <tr>
		<td class="tagForm">Escritura:</td>
		<td>
			<select id="NivelEscritura" style="width:125px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
				<?=getMiscelaneos($field['NivelEscritura'], "NIVEL", 0)?>
			</select>
		</td>
	</tr>
    <tr>
		<td class="tagForm">General:</td>
		<td>
			<select id="NivelGeneral" style="width:125px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
				<?=getMiscelaneos($field['NivelGeneral'], "NIVEL", 0)?>
			</select>
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
<input type="submit" value="<?=$label_submit?>" style=" <?=$display_submit?>" />
<input type="button" value="Cancelar" onclick="this.form.submit();" />
</center>
</form>