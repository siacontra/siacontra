<?php
if ($opcion == "nuevo") {
	$accion = "nuevo";
	$titulo = "Nueva Dependencia Externa";
	$cancelar = "document.getElementById('frmentrada').submit();";
	$flagactivo = "checked";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	//	consulto datos generales	
	$sql = "SELECT
				de.*,
				oe.Organismo
			FROM
				pf_dependenciasexternas de
				INNER JOIN pf_organismosexternos oe ON (de.CodOrganismo = oe.CodOrganismo)
			WHERE de.CodDependencia = '".$registro."'";
	$query_mast = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query_mast)) $field_dependencia = mysql_fetch_array($query_mast);
	
	if ($opcion == "modificar") {
		$accion = "modificar";
		$titulo = "Modificar Dependencia Externa";
		$cancelar = "document.getElementById('frmentrada').submit();";
		$disabled_modificar = "disabled";
	}
	
	elseif ($opcion == "ver") {
		$disabled_ver = "disabled";
		$titulo = "Ver Dependencia Externa";
		$cancelar = "window.close();";
		$display_submit = "display:none;";
	}
	
	if ($field_dependencia['Estado'] == "A") $flagactivo = "checked"; else $flaginactivo = "checked";
}
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="<?=$cancelar?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=pf_dependencias_externas_lista" method="POST" onsubmit="return dependencias_externas(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="fedoreg" id="fedoreg" value="<?=$fedoreg?>" />
<input type="hidden" name="fbuscar" id="fbuscar" value="<?=$fbuscar?>" />
<input type="hidden" name="fordenar" id="fordenar" value="<?=$fordenar?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />

<table width="700" class="tblForm">
	<tr>
    	<td class="divFormCaption" colspan="2">Datos del Dependencia</td>
    </tr>
	<tr>
		<td class="tagForm" width="150">Dependencia:</td>
		<td>
        	<input type="text" id="CodDependencia" style="width:50px; font-weight:bold; font-size:12px;" value="<?=$field_dependencia['CodDependencia']?>" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Descripci&oacute;n:</td>
		<td>
        	<input type="text" id="Dependencia" style="width:300px;" maxlength="255" value="<?=($field_dependencia['Dependencia'])?>" <?=$disabled_ver?> />
		</td>
	</tr>    
	<tr>
		<td class="tagForm">Organismo:</td>
		<td class="gallery clearfix">
            <input type="text" id="CodOrganismo" style="width:45px;" value="<?=$field_dependencia['CodOrganismo']?>" disabled="disabled" />
			<input type="text" id="NomOrganismo" style="width:245px;" value="<?=($field_dependencia['Organismo'])?>" disabled="disabled" />
            <a href="../lib/listas/listado_organismos_externos.php?filtrar=default&&cod=CodOrganismo&nom=NomOrganismo&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Direcci&oacute;n:</td>
		<td>
        	<textarea id="Direccion" style="width:98%; height:50px;" <?=$disabled_ver?>><?=($field_dependencia['Direccion'])?></textarea>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Representante:</td>
		<td>
        	<input type="text" id="Representante" style="width:300px;" maxlength="100" value="<?=($field_dependencia['Representante'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Cargo:</td>
		<td>
        	<input type="text" id="Cargo" style="width:300px;" maxlength="100" value="<?=($field_dependencia['Cargo'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Tel&eacute;fonos:</td>
		<td>
        	<input type="text" id="Telefono1" style="width:100px;" maxlength="15" value="<?=($field_dependencia['Telefono1'])?>" <?=$disabled_ver?> />
        	<input type="text" id="Telefono2" style="width:100px;" maxlength="15" value="<?=($field_dependencia['Telefono2'])?>" <?=$disabled_ver?> />
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
			<input type="text" size="30" value="<?=$field_dependencia['UltimoUsuario']?>" disabled="disabled" />
			<input type="text" size="25" value="<?=$field_dependencia['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>
</table>
<center>
<input type="submit" value="Guardar" style="width:80px; <?=$display_submit?>" />
<input type="button" value="Cancelar" style="width:80px;" onclick="<?=$cancelar?>" />
</center>
<br />
<div style="width:700px; <?=$display_submit?>" class="divMsj">(*) Campos Obligatorios</div>
</form>