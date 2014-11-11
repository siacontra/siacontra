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
	$sql = "SELECT pr.*
			FROM rh_postulantes_referencias pr
			WHERE
				pr.Postulante = '".$Postulante."' AND
				pr.Secuencia = '".$Secuencia."'";
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
<form name="frmentrada" id="frmentrada" action="gehen.php?anz=rh_postulantes_referencias_personales_lista" method="POST" onsubmit="return postulantes_referencias(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="Postulante" id="Postulante" value="<?=$Postulante?>" />
<input type="hidden" id="Secuencia" value="<?=$Secuencia?>" />
<input type="hidden" id="Tipo" value="P" />
<input type="hidden" id="Cargo" value="" />

<table width="80%" class="tblForm">
	<tr>
    	<td colspan="4" class="divFormCaption">Datos de la Referencia Laboral</td>
    </tr>
    <tr>
		<td class="tagForm" width="125">* Nombre:</td>
		<td>
			<input type="text" id="Nombre" style="width:99%;" maxlength="100" value="<?=$field['Nombre']?>" <?=$disabled_ver?> />
		</td>
		<td class="tagForm" width="100">* Tel&eacute;fono:</td>
		<td width="100">
			<input type="text" id="Telefono" style="width:125px;" maxlength="15" value="<?=$field['Telefono']?>" <?=$disabled_ver?> />
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Empresa:</td>
		<td colspan="3">
			<input type="text" id="Empresa" style="width:99%;" maxlength="255" value="<?=$field['Empresa']?>" <?=$disabled_ver?> />
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Direcci&oacute;n:</td>
		<td colspan="3">
			<textarea id="Direccion" style="width:99%; height:30px;" <?=$disabled_ver?>><?=$field['Direccion']?></textarea>
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