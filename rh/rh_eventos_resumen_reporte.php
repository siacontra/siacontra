<?php
list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
if ($filtrar == "default") {
	$fCodOrganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$fCodDependencia = $_SESSION["DEPENDENCIA_ACTUAL"];
	$fCodTipoNom = $_SESSION["NOMINA_ACTUAL"];
	$maxlimit = $_SESSION["MAXLIMIT"];
	$fPeriodo = "$Anio-$Mes";
}
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Resumen de Eventos</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="rh_eventos_resumen_semanal_pdf.php" method="post" target="iReporte" autocomplete="off">
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td align="right" width="125">Organismo:</td>
		<td>
			<input type="checkbox" checked onclick="this.checked=!this.checked" />
			<select name="fCodOrganismo" id="fCodOrganismo" style="width:300px;" onChange="getOptionsSelect(this.value, 'dependencia_filtro', 'fCodDependencia', true, 'fCodCentroCosto'); getOptionsSelectMultiple($('#fPeriodo'), 'periodo-bono', 'CodOrganismo='+$('#fCodOrganismo').val()+'&CodTipoNom='+$('#fCodTipoNom').val()+'&Periodo='+$('#fPeriodo').val(), true);">
				<?=getOrganismos($fCodOrganismo, 3)?>
			</select>
		</td>
		<td align="right" width="125">Buscar:</td>
		<td>
			<input type="checkbox" onclick="chkFiltro(this.checked, 'fBuscar');" />
			<input type="text" name="fBuscar" id="fBuscar" style="width:250px;" disabled />
		</td>
	</tr>
	<tr>
		<td align="right">Dependencia:</td>
		<td>
			<input type="checkbox" checked onclick="chkFiltro(this.checked, 'fCodDependencia');" />
			<select name="fCodDependencia" id="fCodDependencia" style="width:300px;" onChange="getOptionsSelect(this.value, 'centro_costo', 'fCodCentroCosto', true);">
            	<option value="">&nbsp;</option>
				<?=getDependencias($fCodDependencia, $fCodOrganismo, 3)?>
			</select>
		</td>
		<td align="right">Edo. Reg: </td>
		<td>
        	<input type="checkbox" onclick="chkFiltro(this.checked, 'fEdoReg');" />
            <select name="fEdoReg" id="fEdoReg" style="width:143px;" disabled>
                <option value=""></option>
                <?=loadSelectGeneral("ESTADO", "", 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td align="right">Centro de Costo:</td>
		<td>
			<input type="checkbox" onclick="chkFiltro(this.checked, 'fCodCentroCosto');" />
			<select name="fCodCentroCosto" id="fCodCentroCosto" style="width:300px;" disabled>
            	<option value="">&nbsp;</option>
				<?=loadSelectDependiente("ac_mastcentrocosto", "CodCentroCosto", "Descripcion", "CodDependencia", "", "", 0)?>
			</select>
		</td>
		<td align="right">Sit. Tra.: </td>
		<td>
        	<input type="checkbox" onclick="chkFiltro(this.checked, 'fSitTra');" />
            <select name="fSitTra" id="fSitTra" style="width:143px;" disabled>
                <option value=""></option>
                <?=loadSelectGeneral("ESTADO", $fSitTra, 0)?>
            </select>
		</td>
	</tr>
	<tr>
		<td align="right">Tipo de Nomina:</td>
		<td>
			<input type="checkbox" checked onclick="this.checked=!this.checked;" />
			<select name="fCodTipoNom" id="fCodTipoNom" style="width:300px;" onChange="getOptionsSelectMultiple($('#fPeriodo'), 'periodo-bono', 'CodOrganismo='+$('#fCodOrganismo').val()+'&CodTipoNom='+$('#fCodTipoNom').val(), true); getOptionsSelectMultiple($('#fSemana'), 'semana-bono', 'CodOrganismo='+$('#fCodOrganismo').val()+'&CodTipoNom='+$('#fCodTipoNom').val()+'&Periodo='+$('#fPeriodo').val(), true);">
				<?=loadSelect("tiponomina", "CodTipoNom", "Nomina", $fCodTipoNom, 0)?>
			</select>
		</td>
		<td align="right">Fecha de Evento: </td>
		<td>
			<input type="checkbox" onclick="chkFiltro_2(this.checked, 'fFechaD', 'fFechaH');" />
			<input type="text" name="fFechaD" id="fFechaD" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFecha(this);" disabled /> -
            <input type="text" name="fFechaH" id="fFechaH" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFecha(this);" disabled />
        </td>
	</tr>
	<tr>
		<td align="right">Perfil de Nomina:</td>
		<td>
			<input type="checkbox" onclick="chkFiltro(this.checked, 'fCodPerfil');" />
			<select name="fCodPerfil" id="fCodPerfil" style="width:300px;" disabled>
            	<option value="">&nbsp;</option>
				<?=loadSelect("tipoperfilnom", "CodPerfil", "Perfil", "", 0)?>
			</select>
		</td>
		<td align="right">Periodo:</td>
		<td>
			<input type="checkbox" checked onclick="this.checked=!this.checked;" />
			<select name="fPeriodo" id="fPeriodo" style="width:65px;" onChange="getOptionsSelectMultiple($('#fSemana'), 'semana-bono', 'CodOrganismo='+$('#fCodOrganismo').val()+'&CodTipoNom='+$('#fCodTipoNom').val()+'&Periodo='+$('#fPeriodo').val(), true);">
            	<option value="">&nbsp;</option>
                <?=loadSelectPeriodosBono($fPeriodo, $fCodOrganismo, $fCodTipoNom, 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">Cargo al cual Reporta:</td>
		<td>
			<input type="checkbox" onclick="chkFiltro(this.checked, 'fCodCargo');" />
			<select name="fCodCargo" id="fCodCargo" style="width:300px;" disabled>
            	<option value="">&nbsp;</option>
				<?=loadSelect("rh_puestos", "CodCargo", "DescripCargo", "", 0)?>
			</select>
		</td>
	</tr>
</table>
</div>
<center><input type="submit" name="btBuscar" value="Buscar"></center>

<br />

<table width="1000" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" onclick="currentTab('tab', this);" class="current">
            	<a href="#" onclick="cargarPagina(document.getElementById('frmentrada'), 'rh_eventos_resumen_semanal_pdf.php'); mostrarTab('tab', 1, 4);">
                	Semanal
                </a>
            </li>
            <li id="li2" onclick="currentTab('tab', this);">
            	<a href="#" onclick="cargarPagina(document.getElementById('frmentrada'), 'rh_eventos_resumen_mensual_pdf.php'); mostrarTab('tab', 2, 4);">
                	Mensual
                </a>
            </li>
            <li id="li3" onclick="currentTab('tab', this);">
            	<a href="#" onclick="cargarPagina(document.getElementById('frmentrada'), 'rh_eventos_resumen_general_pdf.php'); mostrarTab('tab', 3, 4);">
                	General
                </a>
            </li>
            <li id="li4" onclick="currentTab('tab', this);">
            	<a href="#" onclick="cargarPagina(document.getElementById('frmentrada'), 'rh_eventos_resumen_consolidado_pdf.php'); mostrarTab('tab', 4, 4);">
                	Consolidado
                </a>
            </li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<center>
<div id="tab1" style="display:block;">
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td align="right" width="125">Semana:</td>
		<td>
			<select name="fSemana" id="fSemana" style="width:175px;" <?=$dSemana?>>
            	<option value="">&nbsp;</option>
                <?=loadSelectSemanasBono("", $fPeriodo, $fCodOrganismo, $fCodTipoNom, 0)?>
			</select>
		</td>
	</tr>
</table>
</div>
</div>

<div id="tab2" style="display:none;"></div>

<div id="tab3" style="display:none;"></div>

<div id="tab4" style="display:none;"></div>

<iframe name="iReporte" id="iReporte" style="border:solid 1px #CDCDCD; width:1000px; height:400px;"></iframe>
</center>
</form> 