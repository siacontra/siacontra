<?php
if ($opcion == "nuevo") {
	$accion = "nuevo";
	$titulo = "Nuevo Organismo Externo";
	$cancelar = "document.getElementById('frmentrada').submit();";
	$flagactivo = "checked";
	$CodPais = $_PARAMETRO['PAISDEFAULT'];
	$CodEstado = $_PARAMETRO['ESTADODEFAULT'];
	$CodMunicipio = $_PARAMETRO['MUNICIPIODEFAULT'];
	$CodCiudad = $_PARAMETRO['CIUDADDEFAULT'];
	$display_nivel = "visibility:hidden;";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	//	consulto datos generales	
	$sql = "SELECT
				oe.*,
				c.CodMunicipio,
				m.CodEstado,
				e.CodPais,
				d.Dependencia,
				d.Estructura
			FROM
				pf_organismosexternos oe
				INNER JOIN mastciudades c ON (oe.CodCiudad = c.CodCiudad)
				INNER JOIN mastmunicipios m ON (c.CodMunicipio = m.CodMunicipio)
				INNER JOIN mastestados e ON (m.CodEstado = e.CodEstado)
				LEFT JOIN mastdependencias d ON (oe.CodDependencia = d.CodDependencia)
			WHERE oe.CodOrganismo = '".$registro."'";
	$query_mast = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query_mast)) $field_organismo = mysql_fetch_array($query_mast);
	
	if ($opcion == "modificar") {
		$accion = "modificar";
		$titulo = "Modificar Organismo Externo";
		$cancelar = "document.getElementById('frmentrada').submit();";
		$disabled_modificar = "disabled";
		if ($field_organismo['FlagSujetoControl'] == "N") $display_nivel = "visibility:hidden;";
	}
	
	elseif ($opcion == "ver") {
		$disabled_ver = "disabled";
		$titulo = "Ver Organismo Externo";
		$cancelar = "window.close();";
		$display_submit = "display:none;";
		$display_nivel = "visibility:hidden;";
	}
	
	if ($field_organismo['Estado'] == "A") $flagactivo = "checked"; else $flaginactivo = "checked";
	if ($field_organismo['FlagSujetoControl'] == "S") $FlagSujetoControl = "checked";
	if ($field_organismo['FlagSocial'] == "S") $FlagSocial = "checked";
	
	$CodPais = $field_organismo['CodPais'];
	$CodEstado = $field_organismo['CodEstado'];
	$CodMunicipio = $field_organismo['CodMunicipio'];
	$CodCiudad = $field_organismo['CodCiudad'];
}
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="<?=$cancelar?>">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<table width="700" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" onclick="currentTab('tab', this);" class="current"><a href="#" onclick="mostrarTab('tab', 1, 2);">Datos Generales</a></li>
            <li id="li2" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 2, 2);">Info. Adicional</a></li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=pf_organismos_externos_lista" method="POST" onsubmit="return organismos_externos(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="fedoreg" id="fedoreg" value="<?=$fedoreg?>" />
<input type="hidden" name="fbuscar" id="fbuscar" value="<?=$fbuscar?>" />
<input type="hidden" name="fordenar" id="fordenar" value="<?=$fordenar?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />

<div id="tab1" style="display:block;">
<table width="700" class="tblForm">
	<tr>
    	<td class="divFormCaption" colspan="2">Datos del Organismo</td>
    </tr>
	<tr>
		<td class="tagForm" width="150">Organismo:</td>
		<td>
        	<input type="text" id="CodOrganismo" style="width:75px; font-weight:bold; font-size:12px;" value="<?=$field_organismo['CodOrganismo']?>" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Descripci&oacute;n:</td>
		<td>
        	<input type="text" id="Organismo" style="width:300px;" maxlength="100" value="<?=($field_organismo['Organismo'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Desc. Completa:</td>
		<td>
        	<input type="text" id="DescripComp" style="width:300px;" maxlength="100" value="<?=($field_organismo['DescripComp'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Sujeto a Control:</td>
		<td>
        	<input type="checkbox" id="FlagSujetoControl" value="S" onclick="setBtDependenciaInterna(this.checked);" <?=$FlagSujetoControl?> <?=$disabled_ver?> />
		</td>
	</tr>
	
	  <tr>
    <td class="tagForm">Org. Social: </td>
    <td>
    	<?// if ($field_organismo['FlagSocial'] == "S") $orgsocialS= "checked"; else $orgsocialN = "checked"; ?>
    	<input type="checkbox" id="FlagSocial" value="S"   <?=$FlagSocial?> <?=$disabled_ver?> />
	  </td>
  </tr>
    <tr>
		<td class="tagForm">* Dependencia Interna:</td>
		<td class="gallery clearfix">
            <input type="hidden" id="CodDependencia" value="<?=$field_organismo['CodDependencia']?>" />
            <input type="text" id="Estructura" style="width:45px;" value="<?=$field_organismo['Estructura']?>" disabled="disabled" />
			<input type="text" id="NomDependencia" style="width:245px;" value="<?=($field_organismo['Dependencia'])?>" disabled="disabled" />
            <a href="../lib/listas/listado_dependencias.php?filtrar=default&FlagControlFiscal=S&ventana=dependencias&cod=CodDependencia&nom=NomDependencia&campo3=Estructura&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]" style=" <?=$display_nivel?>" id="btDependencias">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Doc. Fiscal:</td>
		<td>
        	<input type="text" id="DocFiscal" style="width:100px;" maxlength="20" value="<?=($field_organismo['DocFiscal'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Repres. Legal:</td>
		<td>
        	<input type="text" id="RepresentLegal" style="width:300px;" maxlength="100" value="<?=($field_organismo['RepresentLegal'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Repres. Legal (Doc.):</td>
		<td>
        	<input type="text" id="DocRepreLeg" style="width:100px;" maxlength="20" value="<?=($field_organismo['DocRepreLeg'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Repres. Legal (Cargo):</td>
		<td>
        	<input type="text" id="Cargo" style="width:300px;" maxlength="100" value="<?=($field_organismo['Cargo'])?>" <?=$disabled_ver?> />
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
        	<input type="text" id="NumReg" style="width:75px;" maxlength="10" value="<?=($field_organismo['NumReg'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Tomo Reg. Mercantil:</td>
		<td>
        	<input type="text" id="TomoReg" style="width:75px;" maxlength="5" value="<?=($field_organismo['TomoReg'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Fecha Fundaci&oacute;n:</td>
		<td>
        	<input type="text" id="FechaFundac" value="<?=formatFechaDMA($field_organismo['FechaFundac'])?>" style="width:75px;" maxlength="10" class="datepicker" onkeyup="setFechaDMA(this);" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Direcci&oacute;n:</td>
		<td>
        	<textarea id="Direccion" style="width:98%; height:60px;" <?=$disabled_ver?>><?=($field_organismo['Direccion'])?></textarea>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Pais:</td>
		<td>
            <select id="CodPais" style="width:200px;" onchange="getOptionsSelect(this.value, 'estado', 'CodEstado', 200, 3, 'CodMunicipio', 'CodCiudad');" <?=$disabled_ver?>>
                <?=loadSelect("mastpaises", "CodPais", "Pais", $CodPais, 0);?>
            </select>
		</td>
	</tr>
	<tr>
		<td class="tagForm">* Estado:</td>
		<td>
            <select id="CodEstado" style="width:200px;" onchange="getOptionsSelect(this.value, 'municipio', 'CodMunicipio', 200, 2, 'CodCiudad');" <?=$disabled_ver?>>
                <?=loadSelectDependiente("mastestados", "CodEstado", "Estado", "CodPais", $CodEstado, $CodPais, 0);?>
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
        	<input type="text" id="Telefono1" style="width:100px;" maxlength="15" value="<?=($field_organismo['Telefono1'])?>" <?=$disabled_ver?> />
        	<input type="text" id="Telefono2" style="width:100px;" maxlength="15" value="<?=($field_organismo['Telefono2'])?>" <?=$disabled_ver?> />
        	<input type="text" id="Telefono3" style="width:100px;" maxlength="15" value="<?=($field_organismo['Telefono3'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Fax:</td>
		<td>
        	<input type="text" id="Fax1" style="width:100px;" maxlength="15" value="<?=($field_organismo['Fax1'])?>" <?=$disabled_ver?> />
        	<input type="text" id="Fax2" style="width:100px;" maxlength="15" value="<?=($field_organismo['Fax2'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Pagina Web:</td>
		<td>
        	<input type="text" id="PaginaWeb" style="width:300px;" maxlength="100" value="<?=($field_organismo['PaginaWeb'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Logo:</td>
		<td>
        	<input type="text" id="Logo" style="width:300px;" maxlength="30" value="<?=($field_organismo['Logo'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td>
			<input type="text" size="30" value="<?=$field_organismo['UltimoUsuario']?>" disabled="disabled" />
			<input type="text" size="25" value="<?=$field_organismo['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>
</table>
<center>
<input type="submit" value="Guardar" style="width:80px; <?=$display_submit?>" />
<input type="button" value="Cancelar" style="width:80px;" onclick="<?=$cancelar?>" />
</center>
</div>

<div id="tab2" style="display:none;">
<table width="700" class="tblForm">
	<tr>
		<td class="tagForm" width="100">Nro. Gaceta:</td>
		<td>
        	<input type="text" id="Gaceta" style="width:100px;" maxlength="25" value="<?=($field_organismo['Gaceta'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Resoluci&oacute;n:</td>
		<td>
        	<input type="text" id="Resolucion" style="width:100px;" maxlength="25" value="<?=($field_organismo['Resolucion'])?>" <?=$disabled_ver?> />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Misi&oacute;n:</td>
		<td>
        	<textarea id="Mision" style="width:98%; height:150px;" <?=$disabled_ver?>><?=($field_organismo['Mision'])?></textarea>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Visi&oacute;n:</td>
		<td>
        	<textarea id="Vision" style="width:98%; height:150px;" <?=$disabled_ver?>><?=($field_organismo['Vision'])?></textarea>
		</td>
	</tr>
	<tr>
		<td class="tagForm">Otro:</td>
		<td>
        	<textarea id="Otros" style="width:98%; height:150px;" <?=$disabled_ver?>><?=($field_organismo['Otros'])?></textarea>
		</td>
	</tr>
</table>
</div>

<br />
<div style="width:700px; <?=$display_submit?>" class="divMsj">(*) Campos Obligatorios</div>
</form>
