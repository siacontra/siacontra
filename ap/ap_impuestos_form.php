<?php
if ($opcion == "nuevo") {
	$field['Estado'] = "A";
	$field['Signo'] = "P";
	##
	$titulo = "Nuevo Registro";
	$accion = "nuevo";
	$label_submit = "Guardar";
	$disabled_nuevo = "disabled";
	$clkCancelar = "document.getElementById('frmentrada').submit();";
	$focus = "CodImpuesto";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	//	consulto datos generales
	$sql = "SELECT *
			FROM mastimpuestos
			WHERE CodImpuesto = '".$sel_registros."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query)) $field = mysql_fetch_array($query);
	
	if ($opcion == "modificar") {
		$titulo = "Modificar Registro";
		$accion = "modificar";
		$disabled_modificar = "disabled";
		$display_ver = "display:none;";
		$label_submit = "Modificar";
		$clkCancelar = "document.getElementById('frmentrada').submit();";
		$focus = "Descripcion";
	}
	
	elseif ($opcion == "ver") {
		$titulo = "Ver Registro";
		$disabled_nuevo = "disabled";
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$display_ver = "display:none;";
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

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=ap_impuestos_lista" method="POST" enctype="multipart/form-data" onsubmit="return impuestos(this, '<?=$accion?>');" autocomplete="off">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />
<input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />
<input type="hidden" name="fEstado" id="fEstado" value="<?=$fEstado?>" />
<input type="hidden" name="fCodRegimenFiscal" id="fCodRegimenFiscal" value="<?=$fCodRegimenFiscal?>" />
<input type="hidden" name="fFlagImponible" id="fFlagImponible" value="<?=$fFlagImponible?>" />
<input type="hidden" name="fFlagProvision" id="fFlagProvision" value="<?=$fFlagProvision?>" />
<input type="hidden" name="fTipoComprobante" id="fTipoComprobante" value="<?=$fTipoComprobante?>" />

<table width="500" class="tblForm">
	<tr>
    	<td colspan="2" class="divFormCaption">Datos del Registro</td>
    </tr>
	<tr>
		<td class="tagForm" width="125">* C&oacute;digo:</td>
		<td>
            <input type="text" id="CodImpuesto" style="width:50px;" class="codigo" maxlength="3" value="<?=$field['CodImpuesto']?>" <?=$disabled_modificar?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Descripci&oacute;n:</td>
		<td>
            <input type="text" id="Descripcion" style="width:90%;" maxlength="25" value="<?=($field['Descripcion'])?>" <?=$disabled_ver?> />
		</td>
    </tr>
    <tr>
		<td class="tagForm">Signo:</td>
		<td>
			<input type="radio" name="Signo" id="Positivo" value="P" <?=chkOpt($field['Signo'], "P")?> <?=$disabled_ver?> /> Positivo 
            <input type="radio" name="Signo" id="Negativo" value="N" <?=chkOpt($field['Signo'], "N");?> <?=$disabled_ver?> /> Negativo
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Regimen Fiscal</td>
		<td>
			<select id="CodRegimenFiscal" style="width:150px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
				<?=loadSelect("ap_regimenfiscal", "CodRegimenFiscal", "Descripcion", $field['CodRegimenFiscal'], 0)?>
			</select>
		</td>
    </tr>
	<tr>
		<td class="tagForm">* Provisi&oacute;n En:</td>
		<td>
			<select id="FlagProvision" style="width:150px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
				<?=loadSelectValores("IMPUESTO-PROVISION", $field['FlagProvision'], 0)?>
			</select>
		</td>
    </tr>
	<tr>
		<td class="tagForm">* Imponible:</td>
		<td>
			<select id="FlagImponible" style="width:100px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
				<?=loadSelectValores("IMPUESTO-IMPONIBLE", $field['FlagImponible'], 0)?>
			</select>
		</td>
    </tr>
	<tr>
		<td class="tagForm">* Tipo:</td>
		<td>
			<select id="TipoComprobante" style="width:100px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
				<?=loadSelectValores("IMPUESTO-COMPROBANTE", $field['TipoComprobante'], 0)?>
			</select>
		</td>
    </tr>
	<tr>
		<td class="tagForm">Cuenta Contable:</td>
		<td class="gallery clearfix">
			<input type="text" id="CodCuenta" style="width:93px;" value="<?=$field['CodCuenta']?>" disabled="disabled" />
            <a href="../lib/listas/listado_plan_cuentas.php?filtrar=default&cod=CodCuenta&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]" style=" <?=$display_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
		</td>
    </tr>
	<tr>
		<td class="tagForm">Cuenta Pub. 20:</td>
		<td class="gallery clearfix">
			<input type="text" id="CodCuentaPub20" style="width:93px;" value="<?=$field['CodCuentaPub20']?>" disabled="disabled" />
            <a href="../lib/listas/listado_plan_cuentas_pub20.php?filtrar=default&cod=CodCuentaPub20&iframe=true&width=950&height=525" rel="prettyPhoto[iframe2]" style=" <?=$display_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
		</td>
	</tr>
    
	<tr>
		<td class="tagForm">* Porcentaje:</td>
		<td>
            <input type="text" id="FactorPorcentaje" style="width:50px; text-align:right;" value="<?=number_format($field['FactorPorcentaje'], 2, ',', '.')?>" onFocus="numeroFocus(this);" onBlur="numeroBlur(this);" <?=$disabled_ver?> />
		</td>
    </tr>
    <tr>
		<td class="tagForm">Estado:</td>
		<td>
			<input type="radio" name="Estado" id="Activo" value="A" <?=chkOpt($field['Estado'], "A")?> <?=$disabled_nuevo?> /> Activo
            &nbsp;&nbsp;
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
<input type="submit" value="<?=$label_submit?>" style="width:75px; <?=$display_submit?>" />
<input type="button" value="Cancelar" style="width:75px;" onclick="<?=$clkCancelar?>" />
</center>
</form>
<div style="width:500px" class="divMsj">Campos Obligatorios *</div>