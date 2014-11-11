<?php
if ($opcion == "nuevo") {
	$accion = "nuevo";
	$titulo = "Nuevo Centro de Costo";
	$cancelar = "document.getElementById('frmentrada').submit();";
	$flagactivo = "checked";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	//	consulto datos generales	
	$sql = "SELECT
				cc.*,
				d.CodOrganismo,
				p.NomCompleto AS NomEmpleado,
				e.CodEmpleado
			FROM
				ac_mastcentrocosto cc
				INNER JOIN mastdependencias d ON (cc.CodDependencia = d.CodDependencia)
				LEFT JOIN mastpersonas p ON (cc.CodPersona = p.CodPersona)
				LEFT JOIN mastempleado e ON (p.CodPersona = e.CodPersona)
			WHERE cc.CodCentroCosto = '".$registro."'";
	$query_cc = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query_cc)) $field_cc = mysql_fetch_array($query_cc);
	
	if ($opcion == "modificar") {
		$accion = "modificar";
		$titulo = "Modificar Centro de Costo";
		$cancelar = "document.getElementById('frmentrada').submit();";
		$disabled_modificar = "disabled";
	}
	
	elseif ($opcion == "ver") {
		$disabled_ver = "disabled";
		$titulo = "Ver Centro de Costo";
		$cancelar = "document.getElementById('frmentrada').submit();";
		$disabled_modificar = "disabled";
		$display_submit = "display:none;";
	}
	
	if ($field_cc['Estado'] == "A") $flagactivo = "checked"; else $flaginactivo = "checked";
	if ($field_cc['FlagAdministrativo'] == "S") $FlagAdministrativo = "checked";
	if ($field_cc['FlagVentas'] == "S") $FlagVentas = "checked";
	if ($field_cc['FlagFinanciero'] == "S") $FlagFinanciero = "checked";
	if ($field_cc['FlagProduccion'] == "S") $FlagProduccion = "checked";
	if ($field_cc['FlagCentroIngreso'] == "S") $FlagCentroIngreso = "checked";
}
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="<?=$cancelar?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=centro_costos_lista" method="POST" onsubmit="return centro_costos(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="fedoreg" id="fedoreg" value="<?=$fedoreg?>" />
<input type="hidden" name="fsubgrupocc" id="fsubgrupocc" value="<?=$fsubgrupocc?>" />
<input type="hidden" name="fgrupocc" id="fgrupocc" value="<?=$fgrupocc?>" />
<input type="hidden" name="fbuscar" id="fbuscar" value="<?=$fbuscar?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />

<table width="600" class="tblForm">
	<tr><td class="divFormCaption" colspan="2">Centro de Costo</td></tr>
	<tr>
		<td class="tagForm" width="125">* Centro de Costo:</td>
		<td>
        	<input type="text" id="CodCentroCosto" style="width:50px; font-weight:bold; font-size:12px;" maxlength="4" value="<?=$field_cc['CodCentroCosto']?>" <?=$disabled_modificar?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Descripci&oacute;n:</td>
		<td>
        	<input type="text" id="Descripcion" style="width:90%;" maxlength="255" value="<?=($field_cc['Descripcion'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td align="right">* Organismo:</td>
		<td>
            <select id="CodOrganismo" style="width:300px;" onchange="getOptionsSelect(this.value, 'dependencia', 'CodDependencia', true);" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=loadSelect("mastorganismos", "CodOrganismo", "Organismo", $field_cc['CodOrganismo'], 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td align="right">* Dependencia:</td>
		<td>
            <select id="CodDependencia" style="width:300px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=loadSelectDependiente("mastdependencias", "CodDependencia", "Dependencia", "CodOrganismo", $field_cc['CodDependencia'], $field_cc['CodOrganismo'], 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Abreviatura:</td>
		<td>
        	<input type="text" id="Abreviatura" style="width:200px;" maxlength="10" value="<?=($field_cc['Abreviatura'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td align="right">Empleado:</td>
		<td class="gallery clearfix">
        	<input type="hidden" id="CodPersona" value="<?=$field_cc['CodPersona']?>" />
            <input type="text" id="CodEmpleado" style="width:45px;" maxlength="6" value="<?=$field_cc['CodEmpleado']?>" disabled="disabled" />
			<input type="text" id="NomEmpleado" style="width:240px;" value="<?=$field_cc['NomEmpleado']?>" disabled="disabled" />
            <a href="../lib/listas/listado_empleados.php?filtrar=default&ventana=&cod=CodEmpleado&nom=NomEmpleado&campo3=CodPersona&iframe=true&width=1000&height=475" rel="prettyPhoto[iframe1]" style=" <?=$display_submit?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
		</td>
	</tr>
	<tr>
		<td align="right">Tipo de C.Costos:</td>
		<td>
            <select id="TipoCentroCosto" style="width:100px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
            </select>
		</td>
	</tr>
	<tr><td class="divFormCaption" colspan="2">Informaci&oacute;n Presupuestaria</td></tr>
	<tr>
		<td align="right">* Grupo C.C:</td>
		<td>
            <select id="CodGrupoCentroCosto" style="width:200px;" onchange="getOptionsSelect(this.value, 'subgrupocentrocosto', 'CodSubGrupoCentroCosto', true);" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=loadSelect("ac_grupocentrocosto", "CodGrupoCentroCosto", "Descripcion", $field_cc['CodGrupoCentroCosto'], 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td align="right">* Sub-Grupo C.C:</td>
		<td>
            <select id="CodSubGrupoCentroCosto" style="width:200px;" <?=$disabled_ver?>>
            	<option value="">&nbsp;</option>
                <?=loadSelectDependiente("ac_subgrupocentrocosto", "CodSubGrupoCentroCosto", "Descripcion", "CodGrupoCentroCosto", $field_cc['CodSubGrupoCentroCosto'], $field_cc['CodGrupoCentroCosto'], 0)?>
            </select>
		</td>
	</tr>
	<tr><td class="divFormCaption" colspan="2">Tipo de Centro Costo</td></tr>
	<tr>
		<td align="right">&nbsp;</td>
		<td>
        	<input type="checkbox" id="FlagAdministrativo" value="S" <?=$FlagAdministrativo?> <?=$disabled_ver?> /> Administrativo
		</td>
	</tr>
	<tr>
		<td align="right">&nbsp;</td>
		<td>
        	<input type="checkbox" id="FlagVentas" value="S" <?=$FlagVentas?> <?=$disabled_ver?> /> Ventas
		</td>
	</tr>
	<tr>
		<td align="right">&nbsp;</td>
		<td>
        	<input type="checkbox" id="FlagFinanciero" value="S" <?=$FlagFinanciero?> <?=$disabled_ver?> /> Financiero
		</td>
	</tr>
	<tr>
		<td align="right">&nbsp;</td>
		<td>
        	<input type="checkbox" id="FlagProduccion" value="S" <?=$FlagProduccion?> <?=$disabled_ver?> /> Producci&oacute;n
		</td>
	</tr>
	<tr>
		<td align="right">&nbsp;</td>
		<td>
        	<input type="checkbox" id="FlagCentroIngreso" value="S" <?=$FlagCentroIngreso?> <?=$disabled_ver?> /> Centro de Ingresos
		</td>
	</tr>
	<tr><td class="divFormCaption" colspan="2">Auditor&iacute;a</td></tr>
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
			<input type="text" size="30" value="<?=$field_cc['UltimoUsuario']?>" disabled="disabled" />
			<input type="text" size="25" value="<?=$field_cc['UltimaFecha']?>" disabled="disabled" />
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
	$("#CodCentroCosto").focus();
});
</script>