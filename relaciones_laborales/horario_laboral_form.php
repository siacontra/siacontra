<?php
list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
$FechaActual = "$AnioActual-$MesActual-$DiaActual";
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
	//	consulto datos generales
	$sql = "SELECT *
			FROM rh_horariolaboral
			WHERE CodHorario = '".$registro."'";
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
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="<?=$clkCancelar?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=horario_laboral_lista" method="POST" enctype="multipart/form-data" onsubmit="return horario_laboral(this, '<?=$accion?>');" autocomplete="off">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />
<input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />

<table width="500" class="tblForm">
	<tr>
    	<td colspan="2" class="divFormCaption">Informaci&oacute;n del Horario</td>
    </tr>
	<tr>
		<td class="tagForm" width="125">Horario:</td>
		<td>
            <input type="text" id="CodHorario" style="width:35px;" class="codigo" value="<?=$field['CodHorario']?>" disabled />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Descripci&oacute;n:</td>
		<td>
            <input type="text" id="Descripcion" style="width:90%;" maxlength="50" value="<?=$field['Descripcion']?>" <?=$disabled_ver?> />
		</td>
    </tr>
	<tr>
		<td class="tagForm">&nbsp;</td>
		<td>
            <input type="checkbox" id="FlagCorrido" onClick="clkCorrido(this.checked);" <?=chkFlag($field['FlagCorrido'])?> <?=$disabled_ver?> /> Horario Corrido
		</td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
            <input type="radio" name="Estado" id="Activo" value="A" <?=chkOpt($field['Estado'], "A");?> <?=$disabled_nuevo?> /> Activo
            &nbsp; &nbsp; &nbsp; 
            <input type="radio" name="Estado" id="Inactivo" value="I" <?=chkOpt($field['Estado'], "I");?> <?=$disabled_nuevo?> /> Inactivo
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
<div style="width:500px" class="divMsj">Campos Obligatorios *</div>
<br />

<center>
<form name="frm_detalles" id="frm_detalles">
<input type="hidden" id="sel_detalles" />
<table width="500" class="tblBotones">
	<tr>
    	<td class="divFormCaption">Detalle del Horario</td>
    </tr>
</table>
<div style="overflow:scroll; width:500px; height:300px;">
<table width="100%" class="tblLista">
    <thead>
    <tr>
        <th scope="col" width="15" rowspan="2">&nbsp;</th>
        <th scope="col" width="30" rowspan="2">Lab.</th>
        <th scope="col" rowspan="2">Dia</th>
        <th scope="col" colspan="2">1er Turno</th>
        <th scope="col" colspan="2">2do Turno</th>
    </tr>
    <tr>
        <th scope="col" width="55">Entrada</th>
        <th scope="col" width="55">Salida</th>
        <th scope="col" width="55">Entrada</th>
        <th scope="col" width="55">Salida</th>
    </tr>
    </thead>
    
    <tbody id="lista_detalles">
    <?php
	if ($opcion == "nuevo") {
		$sql = "(SELECT '1' AS Dia, 'S' AS FlagLaborable) UNION
				(SELECT '2' AS Dia, 'S' AS FlagLaborable) UNION
				(SELECT '3' AS Dia, 'S' AS FlagLaborable) UNION
				(SELECT '4' AS Dia, 'S' AS FlagLaborable) UNION
				(SELECT '5' AS Dia, 'S' AS FlagLaborable) UNION
				(SELECT '6' AS Dia, 'N' AS FlagLaborable) UNION
				(SELECT '7' AS Dia, 'N' AS FlagLaborable)";
	} else {
		$sql = "SELECT * FROM rh_horariolaboraldet WHERE CodHorario = '".$field['CodHorario']."' ORDER BY Dia";
	}
	$query_detalles = mysql_query($sql) or die ($sql.mysql_error());
	while ($field_detalles = mysql_fetch_array($query_detalles)) {	$nro_detalles++;
		if ($field_detalles['FlagLaborable'] == "S") $disabled_detalles = ""; else {
			$disabled_detalles = "disabled";
			$field_detalles['Entrada1'] = "";
			$field_detalles['Salida1'] = "";
			$field_detalles['Entrada2'] = "";
			$field_detalles['Salida2'] = "";
		}
		?>
		<tr class="trListaBody" onclick="mClk(this, 'sel_detalles');" id="detalles_<?=$nro_detalles?>">
			<th>
				<?=$nro_detalles?>
			</th>
			<td align="center">
				<input type="checkbox" name="FlagLaborable" onClick="clkLaborable(this.checked, 'dia<?=$field_detalles['Dia']?>');" <?=chkFlag($field_detalles['FlagLaborable'])?> <?=$disabled_ver?> />
			</td>
			<td>
				<input type="hidden" name="Dia" value="<?=$field_detalles['Dia']?>" />
                <?=printValoresGeneral("DIA-SEMANA", $field_detalles['Dia'])?>
			</td>
			<td>
				<input type="text" name="Entrada1" class="cell dia<?=$field_detalles['Dia']?> turno1" style="text-align:center;" maxlength="8" value="<?=formatHora12($field_detalles['Entrada1'])?>" <?=$disabled_detalles?> />
			</td>
			<td>
				<input type="text" name="Salida1" class="cell dia<?=$field_detalles['Dia']?> turno1" style="text-align:center;" maxlength="8" value="<?=formatHora12($field_detalles['Salida1'])?>" <?=$disabled_detalles?> />
			</td>
			<td>
				<input type="text" name="Entrada2" class="cell dia<?=$field_detalles['Dia']?> turno2" style="text-align:center;" maxlength="8" value="<?=formatHora12($field_detalles['Entrada2'])?>" <?=$disabled_detalles?> />
			</td>
			<td>
				<input type="text" name="Salida2" class="cell dia<?=$field_detalles['Dia']?> turno2" style="text-align:center;" maxlength="8" value="<?=formatHora12($field_detalles['Salida2'])?>" <?=$disabled_detalles?> />
			</td>
		</tr>
		<?
	}
    ?>
    </tbody>
</table>
</div>
<input type="hidden" id="nro_detalles" value="<?=$nro_detalles?>" />
<input type="hidden" id="can_detalles" value="<?=$nro_detalles?>" />
</form>
</center>