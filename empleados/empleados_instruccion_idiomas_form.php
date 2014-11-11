<?php
if ($opcion == "nuevo") {
	$titulo = "Nuevo Registro";
	$accion = "nuevo";
	$label_submit = "Guardar";
	$clkCancelar = "document.getElementById('frmentrada').submit();";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	list($CodPersona, $CodIdioma) = split("[_]", $registro);
	//	consulto datos generales
	$sql = "SELECT *
			FROM rh_empleado_idioma
			WHERE
				CodPersona = '".$CodPersona."' AND
				CodIdioma = '".$CodIdioma."'";
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
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$display_submit = "display:none;";
		$clkCancelar = "document.getElementById('frmentrada').submit();";
	}
}
//	------------------------------------
?>
<form name="frmentrada" id="frmentrada" action="gehen.php?anz=empleados_instruccion_idiomas_lista" method="POST" enctype="multipart/form-data" onsubmit="return empleados_instruccion_idiomas(this, '<?=$accion?>');" autocomplete="off">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />
<input type="hidden" name="CodPersona" id="CodPersona" value="<?=$CodPersona?>" />

<table width="885" class="tblForm">
	<tr>
    	<td colspan="2" class="divFormCaption">Datos del Idioma</td>
    </tr>
    <tr>
		<td class="tagForm" width="150">* Idioma:</td>
		<td>
			<select id="CodIdioma" style="width:200px;" <?=$disabled_modificar?>>
            	<option value="">&nbsp;</option>
				<?=loadSelect("mastidioma", "CodIdioma", "DescripcionLocal", $field['CodIdioma'], 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Lectura:</td>
		<td>
            <select id="NivelLectura" style="width:125px;;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=getMiscelaneos($field['NivelLectura'], "NIVEL", 0);?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Oral:</td>
		<td>
            <select id="NivelOral" style="width:125px;;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=getMiscelaneos($field['NivelOral'], "NIVEL", 0);?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Escritura:</td>
		<td>
            <select id="NivelEscritura" style="width:125px;;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=getMiscelaneos($field['NivelEscritura'], "NIVEL", 0);?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* General:</td>
		<td>
            <select id="NivelGeneral" style="width:125px;;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=getMiscelaneos($field['NivelGeneral'], "NIVEL", 0);?>
            </select>
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
<input type="submit" value="<?=$label_submit?>" style=" <?=$display_submit?>" />
<input type="button" value="Cancelar" onclick="<?=$clkCancelar?>" />
</center>
</form>
<div style="width:885px" class="divMsj">Campos Obligatorios *</div>