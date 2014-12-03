<?php
if ($opcion == "nuevo") {
	$accion = "nuevo";
	$titulo = "Nuevo Registro";
	$cancelar = "document.getElementById('frmentrada').submit();";
	$flagactivo = "checked";
	$flagtitulo = "checked";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	//	consulto datos generales	
	$sql = "SELECT c.*,
			c.CodMunicipio,
			m.CodEstado,
			e.CodPais
			FROM mastciudades c
			INNER JOIN mastmunicipios m ON (c.CodMunicipio = m.CodMunicipio)
			INNER JOIN mastestados e ON (m.CodEstado = e.CodEstado)
			WHERE c.CodCiudad = '".$registro."'";
	$query_form = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_form)) $field_form = mysql_fetch_array($query_form);
	
	if ($opcion == "modificar") {
		$accion = "modificar";
		$titulo = "Modificar Registro";
		$cancelar = "document.getElementById('frmentrada').submit();";
		$disabled_modificar = "disabled";
	}
	
	elseif ($opcion == "ver") {
		$disabled_ver = "disabled";
		$disabled_modificar = "disabled";
		$titulo = "Ver Registro";
		$cancelar = "window.close();";
		$display_submit = "display:none;";
	}
	$CodPais = $field_form['CodPais'];
	$CodEstado = $field_form['CodEstado'];
	$CodMunicipio = $field_form['CodMunicipio'];
	if ($field_form['Estado'] == "A") $flagactivo = "checked"; else $flaginactivo = "checked";
}
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="<?=$cancelar?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=ciudades_lista" method="POST" onsubmit="return ciudades(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="fedoreg" id="fedoreg" value="<?=$fedoreg?>" />
<input type="hidden" name="fordenar" id="fordenar" value="<?=$fordenar?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="fmunicipio" id="fmunicipio" value="<?=$fmunicipio?>" />

<table width="700" class="tblForm">
	<tr>
		<td class="tagForm">C&oacute;digo:</td>
		<td>
        	<input type="text" id="CodCiudad" value="<?=($field_form['CodCiudad'])?>" style="width:110px;" class="codigo" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Descripci&oacute;n:</td>
		<td>
        	<input type="text" id="Ciudad" style="width:95%;" maxlength="100" value="<?=($field_form['Ciudad'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Cod. Postal:</td>
		<td>
        	<input type="text" id="CodPostal" style="width:110px;;"  maxlength="10" value="<?=($field_form['CodPostal'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Pais:</td>
		<td>
            <select id="CodPais" style="width:200px;" onchange="getOptionsSelect(this.value, 'estado', 'CodEstado', true, 'CodMunicipio', 'CodCiudad');" <?=$disabled_ver?>>
                <?=loadSelect("mastpaises", "CodPais", "Pais", $CodPais, 0);?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Estado:</td>
		<td>
            <select id="CodEstado" style="width:200px;" onchange="getOptionsSelect(this.value, 'municipio', 'CodMunicipio', true, 'CodCiudad');" <?=$disabled_ver?>>
                <?=loadSelectDependienteEstado($CodEstado, $CodPais, 0);?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Municipio:</td>
		<td>
            <select id="CodMunicipio" style="width:200px;" onchange="getOptionsSelect(this.value, 'ciudad', 'CodCiudad', true);" <?=$disabled_ver?>>
                <?=loadSelectDependiente("mastmunicipios", "CodMunicipio", "Municipio", "CodEstado", $CodMunicipio, $CodEstado, 0);?>
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
			<input type="text" size="30" value="<?=$field_form['UltimoUsuario']?>" disabled="disabled" />
			<input type="text" size="25" value="<?=$field_form['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>
</table>
<center>
<input type="submit" value="Guardar" style="width:80px; <?=$display_submit?>" />
<input type="button" value="Cancelar" style="width:80px;" onclick="<?=$cancelar?>" />
</center>
<br />
<div style="width:700px; <?=$display_submit?>" class="divMsj">(*) Campos Obligatorios</div>
</form>

<!-- JS	-->
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	$("#Ciudad").focus();
});
</script>