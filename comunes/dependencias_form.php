<?php
if ($opcion == "nuevo") {
	$accion = "nuevo";
	$titulo = "Nueva Dependencia";
	$cancelar = "document.getElementById('frmentrada').submit();";
	$flagactivo = "checked";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	//	consulto datos generales	
	$sql = "SELECT
				d.*,
				o.Organismo,
				e.CodEmpleado,
				p.NomCompleto AS NomEmpleado,
				pu.DescripCargo,
				dp.Dependencia AS NomEntidadPadre
			FROM
				mastdependencias d
				INNER JOIN mastorganismos o ON (d.CodOrganismo = o.CodOrganismo)
				LEFT JOIN mastpersonas p ON (d.CodPersona = p.CodPersona)
				LEFT JOIN mastempleado e ON (d.CodPersona = e.CodPersona)
				LEFT JOIN rh_puestos pu ON (e.CodCargo = pu.CodCargo)
				LEFT JOIN mastdependencias dp ON (d.EntidadPadre = dp.CodDependencia)
			WHERE d.CodDependencia = '".$registro."'";
	$query_mast = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query_mast)) $field = mysql_fetch_array($query_mast);
	
	if ($opcion == "modificar") {
		$accion = "modificar";
		$titulo = "Modificar Dependencia";
		$cancelar = "document.getElementById('frmentrada').submit();";
		$disabled_modificar = "disabled";
		$display_nivel = "display:none;";
	}
	
	elseif ($opcion == "ver") {
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$titulo = "Ver Dependencia";
		$cancelar = "document.getElementById('frmentrada').submit();";
		$display_submit = "display:none;";
		$display_nivel = "display:none;";
	}
	
	if ($field['Estado'] == "A") $flagactivo = "checked"; else $flaginactivo = "checked";
}
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="<?=$cancelar?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=dependencias_lista" method="POST" onsubmit="return dependencias(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="fedoreg" id="fedoreg" value="<?=$fedoreg?>" />
<input type="hidden" name="fbuscar" id="fbuscar" value="<?=$fbuscar?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="CodEstructura" id="CodEstructura" value="<?=$field['CodEstructura']?>" />

<table width="700" class="tblForm">
	<tr>
    	<td class="divFormCaption" colspan="2">Datos del Dependencia</td>
    </tr>
	<tr>
		<td class="tagForm" width="150">Dependencia:</td>
		<td>
        	<input type="text" id="CodDependencia" style="width:150px; font-weight:bold; font-size:12px;" value="<?=$field['CodDependencia']?>" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Descripci&oacute;n:</td>
		<td>
        	<input type="text" id="Dependencia" style="width:300px;" maxlength="100" value="<?=($field['Dependencia'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Organismo:</td>
		<td>
        	<select name="CodOrganismo" id="CodOrganismo" style="width:305px;" <?=$disabled_ver?>>
                <?=loadSelect("mastorganismos", "CodOrganismo", "Organismo", $field['CodOrganismo'], 0)?>
            </select>
		</td>
	</tr>    
	<tr>
		<td class="tagForm">Entidad Padre:</td>
		<td class="gallery clearfix">
            <input type="hidden" id="EntidadPadre" value="<?=$field['EntidadPadre']?>" />
            <input type="text" id="EstructuraPadre" style="width:45px;" value="<?=$field['EstructuraPadre']?>" disabled="disabled" />
			<input type="text" id="NomEntidadPadre" style="width:245px;" value="<?=($field['NomEntidadPadre'])?>" disabled="disabled" />
            <a href="../lib/listas/listado_dependencias.php?filtrar=default&ventana=dependencias&cod=EntidadPadre&nom=NomEntidadPadre&campo3=EstructuraPadre&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]" style=" <?=$display_nivel?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Nro. Interno:</td>
		<td>
        	<input type="text" id="CodInterno" style="width:150px;" maxlength="100" value="<?=($field['CodInterno'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Nivel:</td>
		<td>
        	<input type="text" id="Nivel" style="width:25px;" maxlength="2" value="<?=($field['Nivel'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Empleado:</td>
		<td class="gallery clearfix">
        	<input type="hidden" id="CodPersona" value="<?=$field['CodPersona']?>" />
            <input type="text" id="CodEmpleado" style="width:45px;" value="<?=$field['CodEmpleado']?>" disabled="disabled" />
			<input type="text" id="NomEmpleado" style="width:245px;" value="<?=($field['NomEmpleado'])?>" disabled="disabled" />
            <a href="../lib/listas/listado_empleados.php?filtrar=default&ventana=dependencias&cod=CodEmpleado&nom=NomEmpleado&campo3=CodPersona&campo4=DescripCargo&iframe=true&width=1000&height=475" rel="prettyPhoto[iframe2]" style=" <?=$display_submit?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
		</td>
	</tr>
	<tr>
		<td class="tagForm">&nbsp;</td>
		<td>
        	<input type="text" id="DescripCargo" style="width:300px;" maxlength="100" value="<?=($field['DescripCargo'])?>" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">&nbsp;</td>
		<td>
        	<? if ($field['FlagControlFiscal'] == "S") $FlagControlFiscal = "checked" ?>
            <input type="checkbox" name="FlagControlFiscal" id="FlagControlFiscal" value="S" <?=$FlagControlFiscal?> <?=$disabled_ver?> /> Ejerce Control Fiscal
		</td>
	</tr>
	<tr>
		<td class="tagForm">&nbsp;</td>
		<td>
        	<? if ($field['FlagPrincipal'] == "S") $FlagPrincipal = "checked" ?>
            <input type="checkbox" name="FlagPrincipal" id="FlagPrincipal" value="S" <?=$FlagPrincipal?> <?=$disabled_ver?> /> Principal
		</td>
	</tr>
	<tr>
		<td class="tagForm">Tel&eacute;fonos:</td>
		<td>
        	<input type="text" id="Telefono1" style="width:100px;" maxlength="15" value="<?=($field['Telefono1'])?>" <?=$disabled_ver?> />
        	<input type="text" id="Telefono2" style="width:100px;" maxlength="15" value="<?=($field['Telefono2'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Extencion:</td>
		<td>
        	<input type="text" id="Extencion1" style="width:50px;" maxlength="4" value="<?=($field['Extencion1'])?>" <?=$disabled_ver?> />
        	<input type="text" id="Extencion2" style="width:50px;" maxlength="4" value="<?=($field['Extencion2'])?>" <?=$disabled_ver?> />
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
			<input type="text" size="30" value="<?=$field['UltimoUsuario']?>" disabled="disabled" />
			<input type="text" size="25" value="<?=$field['UltimaFecha']?>" disabled="disabled" />
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

<!-- JS	-->
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	$("#Dependencia").focus();
});
</script>