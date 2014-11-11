<?php
if ($opcion == "nuevo") {
	$field['Estado'] = "A";
	##
	$titulo = "Nuevo Registro";
	$accion = "nuevo";
	$label_submit = "Guardar";
	$clkCancelar = "document.getElementById('frmentrada').submit();";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	list($CodPersona, $CodSecuencia) = split("[_]", $registro);
	//	consulto datos generales
	$sql = "SELECT *
			FROM bancopersona
			WHERE
				CodPersona = '".$CodPersona."' AND
				CodSecuencia = '".$CodSecuencia."'";
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
}
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="<?=$clkCancelar?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=empleados_bancaria_lista" method="POST" enctype="multipart/form-data" onsubmit="return empleados_bancaria(this, '<?=$accion?>');" autocomplete="off">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />

<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="fCodOrganismo" id="fCodOrganismo" value="<?=$fCodOrganismo?>" />
<input type="hidden" name="fCodDependencia" id="fCodDependencia" value="<?=$fCodDependencia?>" />
<input type="hidden" name="fCodCentroCosto" id="fCodCentroCosto" value="<?=$fCodCentroCosto?>" />
<input type="hidden" name="fCodTipoNom" id="fCodTipoNom" value="<?=$fCodTipoNom?>" />
<input type="hidden" name="fCodTipoTrabajador" id="fCodTipoTrabajador" value="<?=$fCodTipoTrabajador?>" />
<input type="hidden" name="fEdoReg" id="fEdoReg" value="<?=$fEdoReg?>" />
<input type="hidden" name="fSitTra" id="fSitTra" value="<?=$fSitTra?>" />
<input type="hidden" name="fFingresoD" id="fFingresoD" value="<?=$fFingresoD?>" />
<input type="hidden" name="fFingresoH" id="fFingresoH" value="<?=$fFingresoH?>" />
<input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />
<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />

<input type="hidden" name="fdOrderBy" id="fdOrderBy" value="<?=$fdOrderBy?>" />
<input type="hidden" name="CodPersona" id="CodPersona" value="<?=$CodPersona?>" />
<input type="hidden" id="CodSecuencia" value="<?=$CodSecuencia?>" />

<table width="600" class="tblForm">
	<tr>
    	<td colspan="2" class="divFormCaption">Datos de la Cuenta Bancaria</td>
    </tr>
    <tr>
		<td class="tagForm" width="125">* Banco:</td>
		<td>
			<select id="CodBanco" style="width:200px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
				<?=loadSelect("mastbancos", "CodBanco", "Banco", $field['CodBanco'], 0)?>
			</select>
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Tipo de Cuenta:</td>
		<td>
			<select id="TipoCuenta" style="width:150px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
				<?=getMiscelaneos($field['TipoCuenta'], "TIPOCTA", 0)?>
			</select>
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Tipo de Aporte:</td>
		<td>
			<select id="Aportes" style="width:150px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
				<?=getMiscelaneos($field['Aportes'], "TIPOAPO", 0)?>
			</select>
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Nro. Cuenta:</td>
		<td>
            <input type="text" id="Ncuenta" style="width:145px;" maxlength="30" value="<?=$field['Ncuenta']?>" <?=$disabled_ver?> />
		</td>
    </tr>
    <tr>
		<td class="tagForm">Sueldo Mensual:</td>
		<td>
           	<input type="text" id="Monto" style="width:75px; text-align:right" maxlength="15" value="<?=number_format($field['Monto'], 2, ',', '.')?>" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" <?=$disabled_ver?> />
		</td>
	</tr>
    <tr>
		<td class="tagForm">&nbsp;</td>
		<td>
           	<input type="checkbox" id="FlagPrincipal" <?=chkFlag($field['FlagPrincipal'])?> <?=$disabled_ver?> /> Cuenta Principal
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

<div style="width:600px" class="divMsj">Campos Obligatorios *</div>