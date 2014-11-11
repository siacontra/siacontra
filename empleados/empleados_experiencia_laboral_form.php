<?php
if ($opcion == "nuevo") {
	$titulo = "Nuevo Registro";
	$accion = "nuevo";
	$label_submit = "Guardar";
	$clkCancelar = "document.getElementById('frmentrada').submit();";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	list($CodPersona, $Secuencia) = split("[_]", $registro);
	//	consulto datos generales
	$sql = "SELECT *
			FROM rh_empleado_experiencia
			WHERE
				CodPersona = '".$CodPersona."' AND
				Secuencia = '".$Secuencia."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query)) $field = mysql_fetch_array($query);
	
	if ($opcion == "modificar") {
		$titulo = "Modificar Registro";
		$accion = "modificar";
		$label_submit = "Modificar";
		$clkCancelar = "document.getElementById('frmentrada').submit();";
	}
	
	elseif ($opcion == "ver") {
		$titulo = "Ver Registro";
		$disabled_ver = "disabled";
		$display_submit = "display:none;";
		$clkCancelar = "document.getElementById('frmentrada').submit();";
	}
	
	list($TiempoAnios, $TiempoMeses, $TiempoDias) = getEdad(formatFechaDMA($field['FechaDesde']), formatFechaDMA($field['FechaHasta']));
}
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="<?=$clkCancelar?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=empleados_experiencia_laboral_lista" method="POST" enctype="multipart/form-data" onsubmit="return empleados_experiencia_laboral(this, '<?=$accion?>');" autocomplete="off">
<?=filtroEmpleados()?>
<input type="hidden" name="fdOrderBy" id="fdOrderBy" value="<?=$fdOrderBy?>" />
<input type="hidden" name="CodPersona" id="CodPersona" value="<?=$CodPersona?>" />
<input type="hidden" id="Secuencia" value="<?=$Secuencia?>" />

<table width="800" class="tblForm">
	<tr>
    	<td colspan="4" class="divFormCaption">Datos de la Experiencia</td>
    </tr>
    <tr>
		<td class="tagForm">* Empresa:</td>
		<td>
            <input type="text" id="Empresa" style="width:300px;" maxlength="255" value="<?=htmlentities($field['Empresa'])?>" <?=$disabled_ver?> />
		</td>
		<td class="tagForm">* Tipo de Entidad:</td>
		<td>
			<select id="TipoEnte" style="width:175px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
				<?=getMiscelaneos($field['TipoEnte'], "TIPOENTE", 0)?>
			</select>
		</td>
    </tr>
    <tr>
		<td class="tagForm">Cargo Ocupado:</td>
		<td>
            <input type="text" id="CargoOcupado" style="width:300px;" maxlength="255" value="<?=htmlentities($field['CargoOcupado'])?>" <?=$disabled_ver?> />
		</td>
		<td class="tagForm">* Motivo de Cese:</td>
		<td>
			<select id="MotivoCese" style="width:175px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
				<?=getMiscelaneos($field['MotivoCese'], "MOTCESE", 0)?>
			</select>
		</td>
    </tr>
    <tr>
		<td class="tagForm">Area de Experiencia:</td>
		<td>
			<select id="AreaExperiencia" style="width:250px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
				<?=getMiscelaneos($field['AreaExperiencia'], "AREAEXP", 0)?>
			</select>
		</td>
		<td class="tagForm">Sueldo Mensual:</td>
		<td>
           	<input type="text" id="Sueldo" style="width:110px; text-align:right" maxlength="15" value="<?=number_format($field['Sueldo'], 2, ',', '.')?>" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" <?=$disabled_ver?> />
		</td>
    </tr>
    <tr>
        <td class="tagForm">* Periodo:</td>
		<td>
        	<input type="text" id="FechaDesde" style="width:60px;" class="datepicker" maxlength="10" value="<?=formatFechaDMA($field['FechaDesde'])?>" onchange="getEdad($(this).val(), $('#FechaHasta').val(), $('#TiempoAnios'), $('#TiempoMeses'), $('#TiempoDias'))" <?=$disabled_ver?> /> -
        	<input type="text" id="FechaHasta" style="width:60px;" class="datepicker" maxlength="10" value="<?=formatFechaDMA($field['FechaHasta'])?>" onchange="getEdad($('#FechaDesde').val(), $(this).val(), $('#TiempoAnios'), $('#TiempoMeses'), $('#TiempoDias'))" <?=$disabled_ver?> />
		</td>
		<td class="tagForm">Tiempo de Servicio:</td>
		<td>
        	<input type="text" id="TiempoAnios" style="width:25px;" value="<?=$TiempoAnios?>" disabled />a 
        	<input type="text" id="TiempoMeses" style="width:25px;" value="<?=$TiempoMeses?>" disabled />m 
        	<input type="text" id="TiempoDias" style="width:25px;" value="<?=$TiempoDias?>" disabled />d
		</td>
    </tr>
    <tr>
		<td class="tagForm">Funciones:</td>
		<td colspan="3">
            <textarea id="Funciones" style="width:95%" <?=$disabled_ver?>><?=htmlentities($field['Funciones'])?></textarea>
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
<input type="button" value="Cancelar" style="width:75px;" onclick="<?=$clkCancelar?>" />
</center>

</form>

<div style="width:800px" class="divMsj">Campos Obligatorios *</div>
