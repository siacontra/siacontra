<?php
if ($opcion == "nuevo") {
	$titulo = "Nuevo Registro";
	$accion = "nuevo";
	$label_submit = "Guardar";
	$clkCancelar = "document.getElementById('frmentrada').submit();";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	list($CodPersona, $Secuencia) = split("[_]", $sel_registros);
	//	consulto datos generales
	$sql = "SELECT *
			FROM rh_empleado_referencias
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
<form name="frmentrada" id="frmentrada" action="gehen.php?anz=empleados_referencias_laborales_lista" method="POST" enctype="multipart/form-data" onsubmit="return empleados_referencias_laborales(this, '<?=$accion?>');" autocomplete="off">
<input type="hidden" name="_APLICACION" id="_APLICACION" value="<?=$_APLICACION?>" />
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="fOrderBy" id="fOrderBy" value="<?=$fOrderBy?>" />
<input type="hidden" name="CodPersona" id="CodPersona" value="<?=$CodPersona?>" />
<input type="hidden" id="Secuencia" value="<?=$Secuencia?>" />
<input type="hidden" id="Tipo" value="L" />

<table width="885" class="tblForm">
	<tr>
    	<td colspan="2" class="divFormCaption">Datos de la Referencia</td>
    </tr>
    <tr>
		<td class="tagForm" width="125">* Nombre del Jefe:</td>
		<td>
        	<input type="text" id="Nombre" style="width:95%;" maxlength="200" value="<?=htmlentities($field['Nombre'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Empresa:</td>
		<td>
        	<input type="text" id="Empresa" style="width:95%;" maxlength="200" value="<?=htmlentities($field['Empresa'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Cargo:</td>
		<td>
        	<input type="text" id="Cargo" style="width:95%;" maxlength="100" value="<?=htmlentities($field['Cargo'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
    <tr>
		<td class="tagForm">* Tel&eacute;fono:</td>
		<td>
        	<input type="text" id="Telefono" style="width:100px;" maxlength="15" value="<?=$field['Telefono']?>" <?=$disabled_ver?> />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Direcci&oacute;n:</td>
		<td>
        	<textarea id="Direccion" style="width:95%; height:50px;" <?=$disabled_ver?>><?=htmlentities($field['Direccion'])?></textarea>
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
<div style="width:885px" class="divMsj">Campos Obligatorios *</div>