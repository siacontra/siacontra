<?php
if ($opcion == "nuevo") {
	$titulo = "Nuevo Registro";
	$accion = "nuevo";
	$disabled_externo = "disabled";
	$label_submit = "Guardar";
	$clkCancelar = "document.getElementById('frmentrada').submit();";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	list($CodPersona, $Secuencia) = split("[_]", $sel_registros);
	//	consulto datos generales
	$sql = "SELECT *
			FROM rh_patrimonio_cuenta
			WHERE
				CodPersona = '".$CodPersona."' AND
				Secuencia = '".$Secuencia."'";
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
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$display_submit = "display:none;";
		$clkCancelar = "document.getElementById('frmentrada').submit();";
	}
}
//	------------------------------------
?>
<form name="frmentrada" id="frmentrada" action="gehen.php?anz=empleados_patrimonio_cuentas_lista" method="POST" enctype="multipart/form-data" onsubmit="return empleados_patrimonio_cuentas(this, '<?=$accion?>');" autocomplete="off">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />
<input type="hidden" name="CodPersona" id="CodPersona" value="<?=$CodPersona?>" />
<input type="hidden" id="Secuencia" value="<?=$Secuencia?>" />

<table width="500" class="tblForm">
	<tr>
    	<td colspan="2" class="divFormCaption">Datos de la Cuenta</td>
    </tr>
    <tr>
		<td class="tagForm" width="125">* Instituci&oacute;n:</td>
		<td>
        	<input type="text" id="Institucion" style="width:90%;" maxlength="100" value="<?=htmlentities($field['Institucion'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Tipo de Cuenta:</td>
		<td>
            <select id="TipoCuenta" style="width:205px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=getMiscelaneos($field['TipoCuenta'], "TIPOCTA", 0);?>
            </select>
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Nro. Cuenta:</td>
		<td>
        	<input type="text" id="NroCuenta" style="width:200px;" maxlength="30" value="<?=$field['NroCuenta']?>" <?=$disabled_ver?> />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Valor:</td>
		<td>
        	<input type="text" id="Valor" style="width:100px; text-align:right;" maxlength="14" value="<?=number_format($field['Valor'], 2, ',', '.')?>" onBlur="numeroBlur(this);" onFocus="numeroFocus(this);" <?=$disabled_ver?> />
		</td>
	</tr>
    <tr>
		<td class="tagForm"></td>
		<td>
        	<input type="checkbox" id="FlagGarantia" <?=chkFlag($field['FlagGarantia'])?> <?=$disabled_ver?> /> Garant&iacute;a
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
<input type="submit" value="<?=$label_submit?>" style="width:75px; <?=$display_submit?>" />
<input type="button" value="Cancelar" style="width:75px;" onclick="<?=$clkCancelar?>" />
</center>
</form>
<div style="width:500px" class="divMsj">Campos Obligatorios *</div>