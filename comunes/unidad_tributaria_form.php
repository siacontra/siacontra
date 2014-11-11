<?php
list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
$FechaActual = "$AnioActual-$MesActual-$DiaActual";
if ($opcion == "nuevo") {
	$field['Estado'] = "A";
	$field['Anio'] = $AnioActual;
	$field['Fecha'] = $FechaActual;
	##
	$titulo = "Nuevo Registro";
	$accion = "nuevo";
	$label_submit = "Guardar";
	$disabled_nuevo = "disabled";
	$clkCancelar = "document.getElementById('frmentrada').submit();";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	list($Anio, $Secuencia) = split("[_]", $registro);
	//	consulto datos generales
	$sql = "SELECT *
			FROM mastunidadtributaria
			WHERE
				Anio = '".$Anio."' AND
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

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=unidad_tributaria_lista" method="POST" enctype="multipart/form-data" onsubmit="return unidad_tributaria(this, '<?=$accion?>');" autocomplete="off">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />
<input type="hidden" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" />

<table width="600" class="tblForm">
	<tr>
    	<td colspan="2" class="divFormCaption">Datos del Registro</td>
    </tr>
	<tr>
		<td class="tagForm" width="125">A&ntilde;o:</td>
		<td>
            <input type="text" id="Anio" style="width:35px;" class="codigo" value="<?=$field['Anio']?>" disabled />
            <input type="text" id="Secuencia" style="width:15px;" class="codigo" value="<?=$field['Secuencia']?>" disabled />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Fecha:</td>
		<td>
            <input type="text" id="Fecha" style="width:60px;" class="datepicker" value="<?=formatFechaDMA($field['Fecha'])?>" maxlength="10" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Valor:</td>
		<td>
            <input type="text" id="Valor" style="width:100px; text-align:right;" value="<?=number_format($field['Valor'], 2, ',', '.')?>" onBlur="numeroBlur(this);" onFocus="numeroFocus(this);" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Gaceta Oficial:</td>
		<td>
            <input type="text" id="GacetaOficial" style="width:90%;" maxlength="20" value="<?=$field['GacetaOficial']?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Nro. Providencia:</td>
		<td>
            <input type="text" id="ProvidenciaNro" style="width:90%;" maxlength="20" value="<?=$field['ProvidenciaNro']?>" <?=$disabled_ver?> />
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