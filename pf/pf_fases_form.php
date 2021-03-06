<?php
if ($opcion == "nuevo") {
	$accion = "nuevo";
	$titulo = "Nueva Fase";
	$cancelar = "document.getElementById('frmentrada').submit();";
	$flagactivo = "checked";
	$display_pa = "display:none;";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	//	consulto datos generales	
	$sql = "SELECT f.*
			FROM pf_fases f
			WHERE f.CodFase = '".$registro."'";
	$query_mast = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query_mast)) $field_procesos = mysql_fetch_array($query_mast);
	
	if ($opcion == "modificar") {
		$accion = "modificar";
		$titulo = "Modificar Proceso";
		$cancelar = "document.getElementById('frmentrada').submit();";
		$disabled_modificar = "disabled";
	}
	
	elseif ($opcion == "ver") {
		$disabled_ver = "disabled";
		$titulo = "Ver Proceso";
		$cancelar = "window.close();";
		$display_submit = "display:none;";
	}
	
	if ($field_procesos['Estado'] == "A") $flagactivo = "checked"; else $flaginactivo = "checked";
}
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="<?=$cancelar?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=pf_fases_lista" method="POST" onsubmit="return fases(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="fedoreg" id="fedoreg" value="<?=$fedoreg?>" />
<input type="hidden" name="fordenar" id="fordenar" value="<?=$fordenar?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="fproceso" id="fproceso" value="<?=$fproceso?>" />

<table width="600" class="tblForm">
	<tr>
		<td class="tagForm" width="125">* Fase:</td>
		<td>
        	<input type="text" id="CodFase" style="width:35px; font-weight:bold; font-size:12px;" value="<?=$field_procesos['CodFase']?>" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Descripci&oacute;n:</td>
		<td>
        	<input type="text" id="Descripcion" style="width:300px;" maxlength="50" value="<?=($field_procesos['Descripcion'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Proceso:</td>
		<td>
            <select id="CodProceso" style="width:305px;" <?=$disabled_modificar?>>
                <?=loadSelect("pf_procesos", "CodProceso", "Descripcion", $field_procesos['CodProceso'], 0)?>
            </select>
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
			<input type="text" size="30" value="<?=$field_procesos['UltimoUsuario']?>" disabled="disabled" />
			<input type="text" size="25" value="<?=$field_procesos['UltimaFecha']?>" disabled="disabled" />
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