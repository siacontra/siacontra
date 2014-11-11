<?php
if ($opcion == "nuevo") {
	$accion = "nuevo";
	$titulo = "Nueva Par&aacute;metro";
	$cancelar = "document.getElementById('frmentrada').submit();";
	$flagactivo = "checked";
	$CodOrganismo = $_SESSION["ORGANISMO_ACTUAL"];
	$CodAplicacion = $_SESSION["APLICACION_ACTUAL"];
	$flagTexto = "checked";
	$disabled_numero = "disabled";
	$disabled_fecha = "disabled";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	//	consulto datos generales	
	$sql = "SELECT a.*
			FROM mastparametros a
			WHERE a.ParametroClave = '".$registro."'";
	$query_mast = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query_mast)) $field_parametro = mysql_fetch_array($query_mast);
	
	if ($opcion == "modificar") {
		$accion = "modificar";
		$titulo = "Modificar Par&aacute;metro";
		$cancelar = "document.getElementById('frmentrada').submit();";
		$disabled_modificar = "disabled";
		if ($field_parametro['TipoValor'] == "T") { $disabled_numero = "disabled"; $disabled_fecha = "disabled"; }
		elseif ($field_parametro['TipoValor'] == "N") { $disabled_texto = "disabled"; $disabled_fecha = "disabled"; }
		elseif ($field_parametro['TipoValor'] == "F") { $disabled_texto = "disabled"; $disabled_numero = "disabled"; }
	}
	
	elseif ($opcion == "ver") {
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$titulo = "Ver Par&aacute;metro";
		$cancelar = "document.getElementById('frmentrada').submit();";
		$display_submit = "display:none;";
		$disabled_texto = "disabled";
		$disabled_numero = "disabled";
		$disabled_fecha = "disabled";
	}
	
	$CodOrganismo = $field_parametro["CodOrganismo"];
	$CodAplicacion = $field_parametro["CodAplicacion"];
	if ($field_parametro['Estado'] == "A") $flagactivo = "checked"; else $flaginactivo = "checked";
	if ($field_parametro['TipoValor'] == "T") { $flagTexto = "checked"; $ValorTexto = $field_parametro['ValorParam']; }
	elseif ($field_parametro['TipoValor'] == "N") { $flagNumero = "checked"; $ValorNumero = $field_parametro['ValorParam']; }
	elseif ($field_parametro['TipoValor'] == "F") { $flagFecha = "checked"; $ValorFecha = $field_parametro['ValorParam']; }
}
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="<?=$cancelar?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=parametros_lista" method="POST" onsubmit="return parametros(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="forganismo" id="forganismo" value="<?=$forganismo?>" />
<input type="hidden" name="faplicacion" id="faplicacion" value="<?=$faplicacion?>" />
<input type="hidden" name="fedoreg" id="fedoreg" value="<?=$fedoreg?>" />
<input type="hidden" name="fbuscar" id="fbuscar" value="<?=$fbuscar?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />

<table width="800" class="tblForm">
	<tr>
    	<td class="divFormCaption" colspan="2">Informaci&oacute;n del Par&aacute;metro</td>
    </tr>
	<tr>
		<td class="tagForm" width="125">* Aplicaci&oacute;n:</td>
		<td>
            <select id="CodAplicacion" style="width:300px;" <?=$disabled_modificar?>>
                <?=loadSelect("mastaplicaciones", "CodAplicacion", "Descripcion", $CodAplicacion, 0);?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Organismo:</td>
		<td>
            <select id="CodOrganismo" style="width:300px;" <?=$disabled_modificar?>>
                <?=loadSelect("mastorganismos", "CodOrganismo", "Organismo", $CodOrganismo, 0);?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Par&aacute;metro:</td>
		<td>
        	<input type="text" id="ParametroClave" style="width:150px; font-weight:bold; font-size:12px;" maxlength="20" value="<?=$field_parametro['ParametroClave']?>" <?=$disabled_modificar?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Descripci&oacute;n:</td>
		<td>
        	<input type="text" id="DescripcionParam" style="width:98%;" maxlength="100" value="<?=($field_parametro['DescripcionParam'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Explicaci&oacute;n:</td>
		<td>
        	<textarea id="Explicacion" style=" width:98%; height:60px;" <?=$disabled_ver?>><?=htmlentities($field_parametro['Explicacion'])?></textarea>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Estado:</td>
		<td>
            <input type="radio" name="Estado" id="activo" value="A" <?=$flagactivo?> <?=$disabled_ver?> /> Activo
            <input type="radio" name="Estado" id="inactivo" value="I" <?=$flaginactivo?> <?=$disabled_ver?> /> Inactivo
		</td>
	</tr>
	<tr>
    	<td class="divFormCaption" colspan="2">Valores del Par&aacute;metro</td>
    </tr>
	<tr>
		<td class="tagForm">* Texto:</td>
		<td>
        	<input type="radio" name="TipoValor" id="Texto" value="T" onclick="setTipoValorParametro(false, true, true);" <?=$disabled_ver?> <?=$flagTexto?> />
        	<input type="text" id="ValorTexto" style="width:200px;" maxlength="100" value="<?=htmlentities($ValorTexto)?>" <?=$disabled_texto?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* N&uacute;mero:</td>
		<td>
        	<input type="radio" name="TipoValor" id="Numero" value="N" onclick="setTipoValorParametro(true, false, true);" <?=$disabled_ver?> <?=$flagNumero?> />
        	<input type="text" id="ValorNumero" style="width:200px;" maxlength="100" value="<?=($ValorNumero)?>" <?=$disabled_numero?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Fecha:</td>
		<td>
        	<input type="radio" name="TipoValor" id="Fecha" value="F" onclick="setTipoValorParametro(true, true, false);" <?=$disabled_ver?> <?=$flagFecha?> />
        	<input type="text" id="ValorFecha" value="<?=($ValorFecha)?>" style="width:75px;" maxlength="10" class="datepicker" onkeyup="setFechaDMA(this);" <?=$disabled_fecha?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td>
			<input type="text" size="30" value="<?=$field_parametro['UltimoUsuario']?>" disabled="disabled" />
			<input type="text" size="25" value="<?=$field_parametro['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>
</table>
<center>
<input type="submit" value="Guardar" style="width:80px; <?=$display_submit?>" />
<input type="button" value="Cancelar" style="width:80px;" onclick="<?=$cancelar?>" />
</center>
<br />
<div style="width:800px; <?=$display_submit?>" class="divMsj">(*) Campos Obligatorios</div>
</form>

<!-- JS	-->
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	$("#ParametroClave").focus();
});
</script>