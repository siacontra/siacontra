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
				pc.*,
				pt.DescripCargo
			FROM
				rh_postulantes_cargos pc
				INNER JOIN rh_puestos pt ON (pt.CodCargo = pc.CodCargo)
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
<form name="frmentrada" id="frmentrada" action="gehen.php?anz=rh_postulantes_cargos_lista" method="POST" onsubmit="return postulantes_cargos(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="Postulante" id="Postulante" value="<?=$Postulante?>" />
<input type="hidden" id="Secuencia" value="<?=$Secuencia?>" />

<table width="80%" class="tblForm">
	<tr>
    	<td colspan="2" class="divFormCaption">Datos del Cargo</td>
    </tr>
    <tr>
		<td class="tagForm" width="125">* Organismo:</td>
		<td>
			<select id="CodOrganismo" style="width:300px;" <?=$disabled_ver?>>
				<?=loadSelect("mastorganismos", "CodOrganismo", "Organismo", $field['CodOrganismo'], 0)?>
			</select>
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Cargo:</td>
		<td>
            <input type="hidden" id="CodCargo" value="<?=$field['CodCargo']?>" />
			<input type="text" id="DescripCargo" style="width:296px;" class="disabled" value="<?=$field['DescripCargo']?>" disabled />
            <a href="javascript:" onClick="parent.document.getElementById('btCargo1').click();">
                <img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
		</td>
	</tr>
    <tr>
		<td class="tagForm">Observaciones:</td>
		<td>
			<textarea id="Comentario" style="width:97%; height:40px;" <?=$disabled_ver?>><?=$field['Comentario']?></textarea>
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