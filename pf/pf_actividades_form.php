<?php
if ($opcion == "nuevo") {
	$accion = "nuevo";
	$titulo = "Nueva Actividad";
	$cancelar = "document.getElementById('frmentrada').submit();";
	$flagactivo = "checked";
	$display_pa = "display:none;";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	//	consulto datos generales	
	$sql = "SELECT
				a.*,
				f.CodProceso
			FROM
				pf_actividades a
				INNER JOIN pf_fases f ON (a.CodFase = f.CodFase)
			WHERE a.CodActividad = '".$registro."'";
	$query_mast = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query_mast)) $field_actividades = mysql_fetch_array($query_mast);
	
	if ($opcion == "modificar") {
		$accion = "modificar";
		$titulo = "Modificar Actividad";
		$cancelar = "document.getElementById('frmentrada').submit();";
		$disabled_modificar = "disabled";
	}
	
	elseif ($opcion == "ver") {
		$disabled_ver = "disabled";
		$titulo = "Ver Actividad";
		$cancelar = "window.close();";
		$display_submit = "display:none;";
	}
	
	if ($field_actividades['Estado'] == "A") $flagactivo = "checked"; else $flaginactivo = "checked";
	if ($field_actividades['FlagAutoArchivo'] == "S") $FlagAutoArchivo = "checked";
	if ($field_actividades['FlagNoAfectoPlan'] == "S") $FlagNoAfectoPlan = "checked";
}
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="<?=$cancelar?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=pf_actividades_lista" method="POST" onsubmit="return actividades(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="fedoreg" id="fedoreg" value="<?=$fedoreg?>" />
<input type="hidden" name="fordenar" id="fordenar" value="<?=$fordenar?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="fproceso" id="fproceso" value="<?=$fproceso?>" />
<input type="hidden" name="ffase" id="ffase" value="<?=$ffase?>" />

<table width="600" class="tblForm">
	<tr>
		<td class="tagForm" width="125">* Actividad:</td>
		<td>
        	<input type="text" id="CodActividad" style="width:50px; font-weight:bold; font-size:12px;" value="<?=$field_actividades['CodActividad']?>" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Descripci&oacute;n:</td>
		<td>
        	<input type="text" id="Descripcion" style="width:300px;" maxlength="255" value="<?=($field_actividades['Descripcion'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Descripci&oacute;n General:</td>
		<td>
        	<textarea id="Comentarios" style="width:98%; height:60px;" <?=$disabled_ver?>><?=($field_actividades['Comentarios'])?></textarea>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Proceso:</td>
		<td>
            <select id="CodProceso" style="width:250px;" onchange="getOptionsSelect(this.value, 'fases', 'CodFase', true);" <?=$disabled_modificar?>>
                <option value="">&nbsp;</option>
                <?=loadSelect("pf_procesos", "CodProceso", "Descripcion", $field_actividades['CodProceso'], 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Fase:</td>
		<td>
            <select id="CodFase" style="width:250px;" <?=$disabled_modificar?>>
                <option value="">&nbsp;</option>
                <?=loadSelectDependiente("pf_fases", "CodFase", "Descripcion", "CodProceso", $field_actividades['CodFase'], $field_actividades['CodProceso'], 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Duraci&oacute;n:</td>
		<td>
        	<input type="text" id="Duracion" style="width:50px;" maxlength="4" value="<?=($field_actividades['Duracion'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">&nbsp;</td>
		<td>
        	<input type="checkbox" id="FlagAutoArchivo" value="S" <?=$disabled_ver?> <?=$FlagAutoArchivo?> /> Auto de Archivo
		</td>
	</tr>
	<tr>
		<td class="tagForm">&nbsp;</td>
		<td>
        	<input type="checkbox" id="FlagNoAfectoPlan" value="S" <?=$disabled_ver?> <?=$FlagNoAfectoPlan?> /> No Afecta Planificaci&oacute;n
		</td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
            <input type="radio" name="Estado" id="activo" value="A" <?=$flagactivo?> <?=$disabled_ver?> /> Activo
            <input type="radio" name="Estado" id="inactivo" value="I" <?=$flaginactivo?> <?=$disabled_ver?> /> Inactivo
		</td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td>
			<input type="text" size="30" value="<?=$field_actividades['UltimoUsuario']?>" disabled="disabled" />
			<input type="text" size="25" value="<?=$field_actividades['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>
</table>
<center>
<input type="submit" value="Guardar" style="width:80px; <?=$display_submit?>" />
<input type="button" value="Cancelar" style="width:80px;" onclick="<?=$cancelar?>" />
</center>
<br />
<div style="width:600px; <?=$display_submit?>" class="divMsj">(*) Campos Obligatorios</div>
</form>