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
			FROM rh_patrimonio_inversion
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
<form name="frmentrada" id="frmentrada" action="gehen.php?anz=empleados_patrimonio_inversiones_lista" method="POST" enctype="multipart/form-data" onsubmit="return empleados_patrimonio_inversiones(this, '<?=$accion?>');" autocomplete="off">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />
<input type="hidden" name="CodPersona" id="CodPersona" value="<?=$CodPersona?>" />
<input type="hidden" id="Secuencia" value="<?=$Secuencia?>" />

<table width="700" class="tblForm">
	<tr>
    	<td colspan="2" class="divFormCaption">Datos de la Inversi&oacute;n</td>
    </tr>
    <tr>
		<td class="tagForm" width="150">* Titular:</td>
		<td>
        	<input type="text" id="Titular" style="width:95%;" maxlength="255" value="<?=htmlentities($field['Titular'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Empresa Remitente:</td>
		<td>
        	<input type="text" id="EmpresaRemitente" style="width:95%;" maxlength="255" value="<?=htmlentities($field['EmpresaRemitente'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Nro. Certificado:</td>
		<td>
        	<input type="text" id="NroCertificado" style="width:200px;" maxlength="20" value="<?=htmlentities($field['NroCertificado'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Cantidad:</td>
		<td>
        	<input type="text" id="Cantidad" style="width:50px; text-align:right;" maxlength="3" value="<?=$field['Cantidad']?>" <?=$disabled_ver?> />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Valor Nominal:</td>
		<td>
        	<input type="text" id="ValorNominal" style="width:100px; text-align:right;" maxlength="14" value="<?=number_format($field['ValorNominal'], 2, ',', '.')?>" onBlur="numeroBlur(this);" onFocus="numeroFocus(this);" <?=$disabled_ver?> />
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
        	<input type="checkbox" id="FlagGarantia" <?=chkFlag($field['FlagGarantia'])?> <?=$disabled_ver?> /> En Garantia
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
<div style="width:700px" class="divMsj">Campos Obligatorios *</div>