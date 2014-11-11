<?php
if ($opcion == "nuevo") {
	$accion = "nuevo";
	$titulo = "Nuevo Organismo";
	$cancelar = "document.getElementById('frmentrada').submit();";
	$flagactivo = "checked";
	$CodPais = $_PARAMETRO['PAISDEFAULT'];
	$CodEstado = $_PARAMETRO['ESTADODEFAULT'];
	$CodMunicipio = $_PARAMETRO['MUNICIPIODEFAULT'];
	$CodCiudad = $_PARAMETRO['CIUDADDEFAULT'];
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	//	consulto datos generales	
	$sql = "SELECT
				o.*,
				c.CodMunicipio,
				m.CodEstado,
				e.CodPais,
				p.NomCompleto AS NomPersona
			FROM
				mastorganismos o
				INNER JOIN mastciudades c ON (o.CodCiudad = c.CodCiudad)
				INNER JOIN mastmunicipios m ON (c.CodMunicipio = m.CodMunicipio)
				INNER JOIN mastestados e ON (m.CodEstado = e.CodEstado)
				LEFT JOIN mastpersonas p ON (o.CodPersona = p.CodPersona)
			WHERE o.CodOrganismo = '".$registro."'";
	$query_mast = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query_mast)) $field = mysql_fetch_array($query_mast);
	
	if ($opcion == "modificar") {
		$accion = "modificar";
		$titulo = "Modificar Organismo";
		$cancelar = "document.getElementById('frmentrada').submit();";
		$disabled_modificar = "disabled";
	}
	
	elseif ($opcion == "ver") {
		$disabled_modificar = "disabled";
		$disabled_ver = "disabled";
		$titulo = "Ver Organismo";
		$cancelar = "document.getElementById('frmentrada').submit();";
		$display_ver = "display:none;";
		$display_submit = "display:none;";
	}
	
	if ($field['Estado'] == "A") $flagactivo = "checked"; else $flaginactivo = "checked";
	$CodPais = $field['CodPais'];
	$CodEstado = $field['CodEstado'];
	$CodMunicipio = $field['CodMunicipio'];
	$CodCiudad = $field['CodCiudad'];
}
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="<?=$cancelar?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=organismos_lista" method="POST" onsubmit="return organismos(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="fedoreg" id="fedoreg" value="<?=$fedoreg?>" />
<input type="hidden" name="fbuscar" id="fbuscar" value="<?=$fbuscar?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />

<table width="700" class="tblForm">
	<tr>
    	<td class="divFormCaption" colspan="2">Datos del Organismo</td>
    </tr>
	<tr>
		<td class="tagForm" width="150">Organismo:</td>
		<td>
        	<input type="text" id="CodOrganismo" style="width:75px; font-weight:bold; font-size:12px;" value="<?=$field['CodOrganismo']?>" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Descripci&oacute;n:</td>
		<td>
        	<input type="text" id="Organismo" style="width:300px;" maxlength="100" value="<?=($field['Organismo'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Desc. Completa:</td>
		<td>
        	<input type="text" id="DescripComp" style="width:300px;" maxlength="100" value="<?=($field['DescripComp'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Doc. Fiscal:</td>
		<td>
        	<input type="text" id="DocFiscal" style="width:100px;" maxlength="20" value="<?=($field['DocFiscal'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Persona:</td>
		<td class="gallery clearfix">
            <input type="text" id="CodPersona" style="width:60px;" value="<?=$field['CodPersona']?>" disabled="disabled" />
			<input type="text" id="NomPersona" style="width:230px;" value="<?=htmlentities($field['NomPersona'])?>" disabled="disabled" />
            <a href="../lib/listas/listado_personas.php?filtrar=default&cod=CodPersona&nom=NomPersona&EsEmpleado=N&EsCliente=N&iframe=true&width=1000&height=425" rel="prettyPhoto[iframe]" style=" <?=$display_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Repres. Legal:</td>
		<td>
        	<input type="text" id="RepresentLegal" style="width:300px;" maxlength="100" value="<?=($field['RepresentLegal'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Repres. Legal (Doc.):</td>
		<td>
        	<input type="text" id="DocRepreLeg" style="width:100px;" maxlength="20" value="<?=($field['DocRepreLeg'])?>" <?=$disabled_ver?> />
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
    	<td class="divFormCaption" colspan="2">Informaci&oacute;n Adicional</td>
    </tr>
	<tr>
		<td class="tagForm">Nro. Reg. Mercantil:</td>
		<td>
        	<input type="text" id="NumReg" style="width:75px;" maxlength="10" value="<?=($field['NumReg'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Tomo Reg. Mercantil:</td>
		<td>
        	<input type="text" id="TomoReg" style="width:75px;" maxlength="5" value="<?=($field['TomoReg'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Fecha Fundaci&oacute;n:</td>
		<td>
        	<input type="text" id="FechaFundac" value="<?=formatFechaDMA($field['FechaFundac'])?>" style="width:75px;" maxlength="10" class="datepicker" onkeyup="setFechaDMA(this);" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Direcci&oacute;n:</td>
		<td>
        	<textarea id="Direccion" style="width:98%; height:60px;" <?=$disabled_ver?>><?=($field['Direccion'])?></textarea>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Pais:</td>
		<td>
            <select id="CodPais" style="width:200px;" onchange="getOptionsSelect(this.value, 'estado', 'CodEstado', true, 'CodMunicipio', 'CodCiudad');" <?=$disabled_ver?>>
                <?=loadSelect("mastpaises", "CodPais", "Pais", $CodPais, 0);?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Estado:</td>
		<td>
            <select id="CodEstado" style="width:200px;" onchange="getOptionsSelect(this.value, 'municipio', 'CodMunicipio', true, 'CodCiudad');" <?=$disabled_ver?>>
                <?=loadSelectDependienteEstado($CodEstado, $CodPais, 0);?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Municipio:</td>
		<td>
            <select id="CodMunicipio" style="width:200px;" onchange="getOptionsSelect(this.value, 'ciudad', 'CodCiudad', true);" <?=$disabled_ver?>>
                <?=loadSelectDependiente("mastmunicipios", "CodMunicipio", "Municipio", "CodEstado", $CodMunicipio, $CodEstado, 0);?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Ciudad:</td>
		<td>
            <select id="CodCiudad" style="width:200px;" <?=$disabled_ver?>>
                <?=loadSelectDependiente("mastciudades", "CodCiudad", "Ciudad", "CodMunicipio", $CodCiudad, $CodMunicipio, 0);?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Tel&eacute;fonos:</td>
		<td>
        	<input type="text" id="Telefono1" style="width:100px;" maxlength="15" value="<?=($field['Telefono1'])?>" <?=$disabled_ver?> />
        	<input type="text" id="Telefono2" style="width:100px;" maxlength="15" value="<?=($field['Telefono2'])?>" <?=$disabled_ver?> />
        	<input type="text" id="Telefono3" style="width:100px;" maxlength="15" value="<?=($field['Telefono3'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Fax:</td>
		<td>
        	<input type="text" id="Fax1" style="width:100px;" maxlength="15" value="<?=($field['Fax1'])?>" <?=$disabled_ver?> />
        	<input type="text" id="Fax2" style="width:100px;" maxlength="15" value="<?=($field['Fax2'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Pagina Web:</td>
		<td>
        	<input type="text" id="PaginaWeb" style="width:300px;" maxlength="100" value="<?=($field['PaginaWeb'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Logo:</td>
		<td>
        	<input type="text" id="Logo" style="width:300px;" maxlength="30" value="<?=($field['Logo'])?>" <?=$disabled_ver?> />
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
	$("#Organismo").focus();
});
</script>