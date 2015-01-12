<?php
$Ahora = ahora();
$focus = "Descripcion";
if ($opcion == "nuevo") {
	$accion = "nuevo";
	$titulo = "Nueva Sub-Familia";
	$label_submit = "Guardar";
	$field_subfamilia['Estado'] = "A";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	list($CodLinea, $CodFamilia, $CodSubFamilia) = split("[.]", $registro);
	//	consulto datos generales
	$sql = "SELECT *
			FROM lg_clasesubfamilia
			WHERE
				CodLinea = '".$CodLinea."' AND
				CodFamilia = '".$CodFamilia."' AND
				CodSubFamilia = '".$CodSubFamilia."'";
	$query_familia = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_familia)) $field_subfamilia = mysql_fetch_array($query_familia);
	
	if ($opcion == "modificar") {
		$titulo = "Modificar Sub-Familia";
		$accion = "modificar";
		$disabled_modificar = "disabled";
		$display_modificar = "display:none;";
		$label_submit = "Modificar";
	}
	
	elseif ($opcion == "ver") {
		$titulo = "Ver Sub-Familia";
		$disabled_ver = "disabled";
		$display_ver = "display:none;";
		$display_submit = "display:none;";
		$disabled_modificar = "disabled";
		$display_modificar = "display:none;";
	}
}
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="document.getElementById('frmentrada').submit()">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=lg_subfamilia_lista" method="POST" onsubmit="return subfamilia(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="fEstado" id="fEstado" value="<?=$fEstado?>" />
<input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />

<table width="550" class="tblForm">
	<tr>
    	<td colspan="2" class="divFormCaption">Datos Generales</td>
    </tr>
	<tr>
		<td class="tagForm">* Linea:</td>
		<td>
            <select name="CodLinea" id="CodLinea" style="width:255px; height:20px;" class="codigo" <?=$disabled_modificar?> onChange="getOptionsSelect(this.value, 'familia', 'CodFamilia', true);">
            	<option value="">&nbsp;</option>
                <?=loadSelect("lg_claselinea", "CodLinea", "Descripcion", $field_subfamilia['CodLinea'], 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Familia:</td>
		<td>
            <select name="CodFamilia" id="CodFamilia" style="width:255px; height:20px;" class="codigo" <?=$disabled_modificar?>>
                <?=loadSelectDependiente("lg_clasefamilia", "CodFamilia", "Descripcion", "CodLinea", $field_subfamilia['CodFamilia'], $field_subfamilia['CodLinea'], 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm" width="125">* Sub-Familia:</td>
		<td><input type="text" id="CodSubFamilia" style="width:75px;" class="codigo" value="<?=$field_subfamilia['CodSubFamilia']?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm">* Descripci&oacute;n:</td>
		<td><input type="text" id="Descripcion" style="width:90%;" maxlength="100" value="<?=($field_subfamilia['Descripcion'])?>" <?=$disabled_ver?> /></td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
			<input type="radio" name="Estado" id="Activo" value="A" <?=chkOpt($field_subfamilia['Estado'], "A")?> <?=$disabled_ver?> /> Activo
			<input type="radio" name="Estado" id="Inactivo" value="I" <?=chkOpt($field_subfamilia['Estado'], "I")?> <?=$disabled_ver?> /> Inactivo
		</td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td>
			<input type="text" size="30" value="<?=$field_subfamilia['UltimoUsuario']?>" class="disabled" disabled="disabled" />
			<input type="text" size="25" value="<?=$field_subfamilia['UltimaFecha']?>" class="disabled" disabled="disabled" />
		</td>
	</tr>  
</table>

<center> 
<input type="submit" value="<?=$label_submit?>" style=" <?=$display_submit?>" />
<input type="button" value="Cancelar" onclick="this.form.submit();" />
</center>
</form>
<div style="width:550px" class="divMsj">Campos Obligatorios *</div>