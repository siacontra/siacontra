<?php
if ($opcion == "nuevo") {
	$field['Estado'] = "E";
	$valEstado = "ESTADO-DOCUMENTOS1";
	##
	$titulo = "Nuevo Registro";
	$accion = "nuevo";
	$label_submit = "Guardar";
	$disabled_nuevo = "disabled";
	$clkCancelar = "document.getElementById('frmentrada').submit();";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	list($CodPersona, $CodDocumento, $Secuencia) = split("[_]", $sel_registros);
	//	consulto datos generales
	$sql = "SELECT
				dh.*,
				p.NomCompleto AS NomResponsable
			FROM
				rh_documentos_historia dh
				INNER JOIN mastpersonas p ON (p.CodPersona = dh.Responsable)
			WHERE
				dh.CodPersona = '".$CodPersona."' AND
				dh.CodDocumento = '".$CodDocumento."' AND
				dh.Secuencia = '".$Secuencia."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query)) $field = mysql_fetch_array($query);
	
	if ($opcion == "modificar") {
		$titulo = "Modificar Registro";
		$accion = "modificar";
		$disabled_modificar = "disabled";
		$display_modificar = "display:none;";
		$label_submit = "Modificar";
		$clkCancelar = "document.getElementById('frmentrada').submit();";
		$valEstado = "ESTADO-DOCUMENTOS2";
	}
	
	elseif ($opcion == "ver") {
		$titulo = "Ver Registro";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$display_modificar = "display:none;";
		$display_submit = "display:none;";
		$valEstado = "ESTADO-DOCUMENTOS";
	}
}
//	------------------------------------
?>
<form name="frmentrada" id="frmentrada" action="gehen.php?anz=empleados_documentos_movimientos_lista" method="POST" enctype="multipart/form-data" onsubmit="return empleados_documentos_movimientos(this, '<?=$accion?>');" autocomplete="off">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />
<input type="hidden" name="CodPersona" id="CodPersona" value="<?=$CodPersona?>" />
<input type="hidden" name="CodDocumento" id="CodDocumento" value="<?=$CodDocumento?>" />
<input type="hidden" id="Secuencia" value="<?=$Secuencia?>" />

<table width="885" class="tblForm">
	<tr>
    	<td colspan="4" class="divFormCaption">Datos del Movimiento</td>
    </tr>
    <tr>
		<td class="tagForm" width="150">* Persona Relacionada:</td>
		<td>
            <input type="hidden" id="Responsable" value="<?=$field['Responsable']?>" />
			<input type="text" id="NomResponsable" style="width:300px;" value="<?=$field['NomResponsable']?>" disabled="disabled" />
            <a href="javascript:" onClick="parent.document.getElementById('a_personas').click();" style=" <?=$display_modificar?>" id="a_personas">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
		<td class="tagForm" width="125">* Estado:</td>
		<td>
			<select id="Estado" style="width:85px;" <?=$disabled_ver?>>
				<?=loadSelectValores($valEstado, $field['Estado'], 0)?>
			</select>
		</td>
	</tr>
    <tr>
        <td class="tagForm">* Fecha Entrega:</td>
		<td>
        	<input type="text" id="FechaEntrega" style="width:80px;" class="datepicker" maxlength="10" value="<?=formatFechaDMA($field['FechaEntrega'])?>" onchange="setFechaDMA(this);" <?=$disabled_modificar?> />
		</td>
        <td class="tagForm">* Fecha Estado:</td>
		<td>
        	<input type="text" id="FechaDevuelto" style="width:80px;" class="datepicker" maxlength="10" value="<?=formatFechaDMA($field['FechaDevuelto'])?>" onchange="setFechaDMA(this);" <?=$disabled_nuevo?> />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Obs. Entrega:</td>
		<td colspan="3">
        	<textarea id="ObsEntrega" style="width:98%; height:30px;" <?=$disabled_modificar?>><?=htmlentities($field['ObsEntrega'])?></textarea>
        </td>
	</tr>
    <tr>
		<td class="tagForm">Obs. Devoluci&oacute;n o P&eacute;rdida:</td>
		<td colspan="3">
        	<textarea id="ObsDevuelto" style="width:98%; height:30px;" <?=$disabled_nuevo?>><?=htmlentities($field['ObsDevuelto'])?></textarea>
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
<input type="submit" value="<?=$label_submit?>" style="width:75px; <?=$display_submit?>" />
<input type="button" value="Cancelar" style="width:75px;" onclick="$('#frmentrada').attr('action', 'gehen.php?anz=empleados_documentos_movimientos_lista').attr('target', ''); <?=$clkCancelar?>" />
</center>
</form>
<div style="width:885px" class="divMsj">Campos Obligatorios *</div>