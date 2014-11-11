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
	$sql = "SELECT
				pC.*,
				c.Descripcion AS NomCurso,
				ce.Descripcion AS NomCentroEstudio
			FROM
				rh_postulantes_cursos pc
				INNER JOIN rh_cursos c ON (c.CodCurso = pc.CodCurso)
				INNER JOIN rh_centrosestudios ce ON (ce.CodCentroEstudio = pc.CodCentroEstudio)
			WHERE
				pc.Postulante = '".$Postulante."' AND
				pc.Secuencia = '".$Secuencia."'";
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
<form name="frmentrada" id="frmentrada" action="gehen.php?anz=rh_postulantes_cursos_lista" method="POST" onsubmit="return postulantes_cursos(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="Postulante" id="Postulante" value="<?=$Postulante?>" />
<input type="hidden" id="Secuencia" value="<?=$Secuencia?>" />
<input type="hidden" id="FechaActual" value="<?=substr($Ahora, 0, 10)?>" />

<table width="100%" class="tblForm">
	<tr>
    	<td colspan="4" class="divFormCaption">Datos del Curso</td>
    </tr>
    <tr>
		<td class="tagForm">* Curso:</td>
		<td>
            <input type="hidden" id="CodCurso" value="<?=$field['CodCurso']?>" />
			<input type="text" id="NomCurso" style="width:275px;" class="disabled" value="<?=$field['NomCurso']?>" disabled />
            <a href="javascript:" onClick="parent.document.getElementById('btCurso1').click();">
                <img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
		</td>
		<td class="tagForm">* Tipo de Curso:</td>
		<td>
			<select id="TipoCurso" style="width:200px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
				<?=getMiscelaneos($field['TipoCurso'], "TIPOCURSO", 0)?>
			</select>
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Centro de Estudio:</td>
		<td>
            <input type="hidden" id="CodCentroEstudio" value="<?=$field['CodCentroEstudio']?>" />
			<input type="text" id="NomCentroEstudio" style="width:275px;" class="disabled" value="<?=$field['NomCentroEstudio']?>" disabled />
            <a href="javascript:" onClick="parent.document.getElementById('btCentro2').click();">
                <img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
		</td>
		<td class="tagForm">* Periodo:</td>
		<td>
			<input type="text" id="PeriodoCulminacion" style="width:60px;" maxlength="7" value="<?=$field['PeriodoCulminacion']?>" <?=$disabled_ver?> />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Desde:</td>
		<td>
			<input type="text" id="FechaDesde" value="<?=formatFechaDMA($field['FechaDesde'])?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" <?=$disabled_ver?> />
		</td>
		<td class="tagForm">Hasta:</td>
		<td>
			<input type="text" id="FechaHasta" value="<?=formatFechaDMA($field['FechaHasta'])?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" <?=$disabled_ver?> />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Horas:</td>
		<td>
			<input type="text" id="TotalHoras" style="width:60px;" maxlength="4" value="<?=$field['TotalHoras']?>" <?=$disabled_ver?> />
		</td>
		<td class="tagForm">A&ntilde;os Vigencia:</td>
		<td>
			<input type="text" id="AniosVigencia" style="width:60px;" maxlength="2" value="<?=$field['AniosVigencia']?>" <?=$disabled_ver?> />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Observaciones:</td>
		<td colspan="3">
			<textarea id="Observaciones" style="width:97%; height:40px;" <?=$disabled_ver?>><?=$field['Observaciones']?></textarea>
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