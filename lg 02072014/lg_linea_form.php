<?php
$Ahora = ahora();
if ($opcion == "nuevo") {
	$accion = "nuevo";
	$titulo = "Nueva Linea";
	$label_submit = "Guardar";
	$field_linea['Estado'] = "A";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	//	consulto datos generales
	$sql = "SELECT *
			FROM lg_claselinea
			WHERE CodLinea = '".$registro."'";
	$query_linea = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query_linea)) $field_linea = mysql_fetch_array($query_linea);
	
	if ($opcion == "modificar") {
		$titulo = "Modificar Linea";
		$accion = "modificar";
		$disabled_modificar = "disabled";
		$display_modificar = "display:none;";
		$label_submit = "Modificar";
	}
	
	elseif ($opcion == "ver") {
		$titulo = "Ver Linea";
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

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=lg_linea_lista" method="POST" onsubmit="return linea(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="fEstado" id="fEstado" value="<?=$fEstado?>" />
<input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />
<input type="hidden" name="fOrdenar" id="fOrdenar" value="<?=$fOrdenar?>" />

<table width="550" class="tblForm">
	<tr>
    	<td colspan="2" class="divFormCaption">Datos Generales</td>
    </tr>
	<tr>
		<td class="tagForm" width="125">Linea:</td>
		<td><input type="text" id="CodLinea" style="width:75px;" class="codigo" value="<?=$field_linea['CodLinea']?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm">* Descripci&oacute;n:</td>
		<td><input type="text" id="Descripcion" style="width:90%;" maxlength="100" value="<?=($field_linea['Descripcion'])?>" <?=$disabled_ver?> /></td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
			<input type="radio" name="Estado" id="Activo" value="A" <?=chkOpt($field_linea['Estado'], "A")?> <?=$disabled_ver?> /> Activo
			<input type="radio" name="Estado" id="Inactivo" value="I" <?=chkOpt($field_linea['Estado'], "I")?> <?=$disabled_ver?> /> Inactivo
		</td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td>
			<input type="text" size="30" value="<?=$field_linea['UltimoUsuario']?>" class="disabled" disabled="disabled" />
			<input type="text" size="25" value="<?=$field_linea['UltimaFecha']?>" class="disabled" disabled="disabled" />
		</td>
	</tr>  
</table>

<center> 
<input type="submit" value="<?=$label_submit?>" style=" <?=$display_submit?>" />
<input type="button" value="Cancelar" onclick="this.form.submit();" />
</center>
</form>
<div style="width:550px" class="divMsj">Campos Obligatorios *</div>