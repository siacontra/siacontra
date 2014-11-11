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
				ec.*,
				c.Descripcion AS NomCurso,
				ce.Descripcion AS NomCentroEstudio
            FROM
				rh_empleado_cursos ec
				INNER JOIN rh_cursos c ON (c.CodCurso = ec.CodCurso)
				INNER JOIN rh_centrosestudios ce ON (ce.CodCentroEstudio = ec.CodCentroEstudio)
			WHERE
				ec.CodPersona = '".$CodPersona."' AND
				ec.Secuencia = '".$Secuencia."'";
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
<form name="frmentrada" id="frmentrada" action="gehen.php?anz=empleados_instruccion_cursos_lista" method="POST" enctype="multipart/form-data" onsubmit="return empleados_instruccion_cursos(this, '<?=$accion?>');" autocomplete="off">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />
<input type="hidden" name="CodPersona" id="CodPersona" value="<?=$CodPersona?>" />

<table width="885" class="tblForm">
	<tr>
    	<td colspan="4" class="divFormCaption">Datos del Curso</td>
    </tr>
    <tr>
        <td class="tagForm" width="125">Nro.:</td>
		<td>
        	<input type="text" id="Secuencia" style="width:25px;" class="codigo" value="<?=$field['Secuencia']?>" disabled />
		</td>
        <td class="tagForm" width="150"></td>
		<td>
        	<input type="checkbox" id="FlagInstitucional" <?=chkFlag($field['FlagInstitucional'])?> <?=$disabled_ver?> /> Auspiciado por el Organismo
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Curso:</td>
		<td>
            <input type="hidden" id="CodCurso" value="<?=$field['CodCurso']?>" />
			<input type="text" id="NomCurso" style="width:270px;" value="<?=$field['NomCurso']?>" disabled="disabled" />
            <a href="javascript:" onClick="parent.document.getElementById('a_CodCurso').click();" style=" <?=$display_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
        <td class="tagForm"></td>
		<td>
        	<input type="checkbox" id="FlagPago" <?=chkFlag($field['FlagPago'])?> <?=$disabled_ver?> /> Pagar en Nomina
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Centro Estudio:</td>
		<td>
            <input type="hidden" id="CodCentroEstudio" value="<?=$field['CodCentroEstudio']?>" />
			<input type="text" id="NomCentroEstudio" style="width:270px;" value="<?=$field['NomCentroEstudio']?>" disabled="disabled" />
            <a href="javascript:" onClick="parent.document.getElementById('a_CodCentroCosto2').click();" style=" <?=$display_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
        <td class="tagForm"></td>
		<td>
        	<input type="checkbox" id="FlagArea" <?=chkFlag($field['FlagArea'])?> <?=$disabled_ver?> /> Curso asociado al puesto
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Tipo de Curso:</td>
		<td>
            <select id="TipoCurso" style="width:275px;;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=getMiscelaneos($field['TipoCurso'], "TIPOCURSO", 0);?>
            </select>
		</td>
        <td class="tagForm">Periodo:</td>
		<td>
        	<input type="text" id="FechaDesde" style="width:60px;" class="datepicker" maxlength="10" value="<?=formatFechaDMA($field['FechaDesde'])?>" <?=$disabled_ver?> /> -
        	<input type="text" id="FechaHasta" style="width:60px;" class="datepicker" maxlength="10" value="<?=formatFechaDMA($field['FechaHasta'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
    <tr>
        <td class="tagForm">Horas:</td>
		<td>
        	<input type="text" id="TotalHoras" style="width:60px;" maxlength="4" value="<?=$field['TotalHoras']?>" <?=$disabled_ver?> />
		</td>
        <td class="tagForm">* Periodo Culminaci&oacute;n:</td>
		<td>
        	<input type="text" id="FechaCulminacion" style="width:60px;" maxlength="7" value="<?=$field['FechaCulminacion']?>" <?=$disabled_ver?> />
		</td>
	</tr>
    <tr>
        <td class="tagForm">A&ntilde;os:</td>
		<td>
        	<input type="text" id="AniosVigencia" style="width:60px;" maxlength="2" value="<?=$field['AniosVigencia']?>" <?=$disabled_ver?> />
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