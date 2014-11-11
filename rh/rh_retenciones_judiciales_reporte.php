<?php
list($AnioActual, $MesActual, $DiaActual) = split("[/.-]", substr($Ahora, 0, 10));
if ($filtrar == "default") {
	$fCodOrganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$fCodDependencia = $_SESSION["DEPENDENCIA_ACTUAL"];
	$fFechaD = "$AnioActual-$MesActual-01";
	$fFechaH = "$AnioActual-$MesActual-$DiaActual";
}
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Retenciones Judiciales</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="rh_retenciones_judiciales_lista_pdf.php" method="post" target="iReporte">
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td align="right" width="125">Organismo:</td>
		<td>
			<input type="checkbox" checked onclick="this.checked=!this.checked" />
			<select name="fCodOrganismo" id="fCodOrganismo" style="width:300px;" onChange="getOptionsSelect(this.value, 'dependencia_filtro', 'fCodDependencia', true);">
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
			<select name="fCodDependencia" id="fCodDependencia" style="width:300px;">
            	<option value="">&nbsp;</option>
				<?=getDependencias($fCodDependencia, $fCodOrganismo, 3)?>
			</select>
		</td>
		<td align="right">Fecha: </td>
		<td>
			<input type="checkbox" onclick="chkFiltro_2(this.checked, 'fFechaD', 'fFechaH');" checked />
			<input type="text" name="fFechaD" id="fFechaD" value="<?=formatFechaDMA($fFechaD)?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" /> -
            <input type="text" name="fFechaH" id="fFechaH" value="<?=formatFechaDMA($fFechaH)?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" />
        </td>
	</tr>
    <tr>
		<td align="right">Empleado: </td>
		<td class="gallery clearfix">
            <input type="checkbox" onclick="chkFiltroLista_3(this.checked, 'fCodPersona', 'fNomPersona', 'fCodEmpleado', 'btPersona');" />
            <input type="hidden" name="fCodEmpleado" id="fCodEmpleado" />
            <input type="hidden" name="fCodPersona" id="fCodPersona" />
			<input type="text" name="fNomPersona" id="fNomPersona" class="disabled" style="width:295px;" readonly />
            <a href="../lib/listas/listado_empleados.php?filtrar=default&cod=fCodEmpleado&nom=fNomPersona&campo3=fCodPersona&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]" id="btPersona" style="visibility:hidden;">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
	</tr>
</table>
</div>
<center><input type="submit" name="btBuscar" value="Buscar"></center>
</form> 

<br />

<table width="1000" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" onclick="currentTab('tab', this);" class="current">
            	<a href="#" onclick="cargarPagina(document.getElementById('frmentrada'), 'rh_retenciones_judiciales_lista_pdf.php');">
                	Listado
                </a>
            </li>
            <li id="li2" onclick="currentTab('tab', this);">
            	<a href="#" onclick="cargarPagina(document.getElementById('frmentrada'), 'rh_retenciones_judiciales_detalle_pdf.php');">
                	Detalle
                </a>
            </li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<center>
<iframe name="iReporte" id="iReporte" style="border:solid 1px #CDCDCD; width:1000px; height:500px;"></iframe>
</center>