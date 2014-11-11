<?php
if ($opcion == "nuevo") {
	$accion = "nuevo";
	$titulo = "Nueva Aplicaci&oacute;n";
	$cancelar = "document.getElementById('frmentrada').submit();";
	$flagactivo = "checked";
	$display_pa = "display:none;";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	//	consulto datos generales	
	$sql = "SELECT a.*
			FROM mastaplicaciones a
			WHERE a.CodAplicacion = '".$registro."'";
	$query_mast = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query_mast)) $field_aplicacion = mysql_fetch_array($query_mast);
	
	if ($opcion == "modificar") {
		$accion = "modificar";
		$titulo = "Modificar Aplicaci&oacute;n";
		$cancelar = "document.getElementById('frmentrada').submit();";
		$disabled_modificar = "disabled";
	}
	
	elseif ($opcion == "ver") {
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$titulo = "Ver Aplicaci&oacute;n";
		$cancelar = "document.getElementById('frmentrada').submit();";
		$display_submit = "display:none;";
	}
	
	if ($field_aplicacion['Estado'] == "A") $flagactivo = "checked"; else $flaginactivo = "checked";
	if ($field_aplicacion['CodAplicacion'] != "AP") $display_pa = "display:none;";
}
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="<?=$cancelar?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=aplicaciones_lista" method="POST" onsubmit="return aplicaciones(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="fedoreg" id="fedoreg" value="<?=$fedoreg?>" />
<input type="hidden" name="fbuscar" id="fbuscar" value="<?=$fbuscar?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />

<table width="600" class="tblForm">
	<tr>
		<td class="tagForm" width="125">* Aplicaci&oacute;n:</td>
		<td>
        	<input type="text" id="CodAplicacion" style="width:100px; font-weight:bold; font-size:12px;" maxlength="10" value="<?=$field_aplicacion['CodAplicacion']?>" <?=$disabled_modificar?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Descripci&oacute;n:</td>
		<td>
        	<input type="text" id="Descripcion" style="width:250px;" maxlength="30" value="<?=($field_aplicacion['Descripcion'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Periodo Contable:</td>
		<td>
        	<input type="text" id="PeriodoContable" style="width:50px;" maxlength="7" value="<?=($field_aplicacion['PeriodoContable'])?>" <?=$disabled_ver?> />
            <span class="divMsj">(AAAA-MM)</span>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Sistema Fuente:</td>
		<td>
            <select id="CodSistemaFuente" style="width:200px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=loadSelect("ac_sistemafuente", "CodSistemaFuente", "Descripcion", $field_aplicacion['CodSistemaFuente'], 0);?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Prefijo Voucher:</td>
		<td>
        	<input type="text" id="PrefVoucherPD" style="width:25px;" maxlength="2" value="<?=($field_aplicacion['PrefVoucherPD'])?>" <?=$disabled_ver?> />
            <span style=" <?=$display_pa?>">Provisi&oacute;n de Documentos</span>
		</td>
	</tr>	
	<tr style=" <?=$display_pa?>">
		<td class="tagForm">&nbsp;</td>
		<td>
        	<input type="text" id="PrefVoucherPA" style="width:25px;" maxlength="2" value="<?=($field_aplicacion['PrefVoucherPA'])?>" <?=$disabled_ver?> />
            Pagos
		</td>
	</tr>
	<tr style=" <?=$display_pa?>">
		<td class="tagForm">&nbsp;</td>
		<td>
        	<input type="text" id="PrefVoucherLP" style="width:25px;" maxlength="2" value="<?=($field_aplicacion['PrefVoucherLP'])?>" <?=$disabled_ver?> />
            Letras y Préstamos
		</td>
	</tr>
	<tr style=" <?=$display_pa?>">
		<td class="tagForm">&nbsp;</td>
		<td>
        	<input type="text" id="PrefVoucherTB" style="width:25px;" maxlength="2" value="<?=($field_aplicacion['PrefVoucherTB'])?>" <?=$disabled_ver?> />
            Transacciones Bancarias
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
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td>
			<input type="text" size="30" value="<?=$field_aplicacion['UltimoUsuario']?>" disabled="disabled" />
			<input type="text" size="25" value="<?=$field_aplicacion['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>
</table>
<center>
<input type="submit" value="Guardar" style="width:80px; <?=$display_submit?>" />
<input type="button" value="Cancelar" style="width:80px;" onclick="<?=$cancelar?>" />
</center>
<br />
<div style="width:600px; <?=$display_submit?>" class="divMsj">(*) Campos Obligatorios</div>
</form>

<!-- JS	-->
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	$("#CodAplicacion").focus();
});
</script>