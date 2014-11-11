<?php
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
if ($filtrar == "default") {
	$fCodOrganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$maxlimit = $_SESSION["MAXLIMIT"];
	$fPeriodod = "$Anio-$Mes";
	$fPeriodoh = "$Anio-$Mes";
	$fFechaComprobanted = "01-$Mes-$Anio";
	$fFechaComprobanteh = "$Dia-$Mes-$Anio";
}
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Registro de Compras</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="ap_registro_compra_libro_pdf.php" method="post" target="iReporte">
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td align="right" width="125">Organismo:</td>
		<td>
			<input type="checkbox" checked onclick="this.checked=!this.checked" />
			<select name="fCodOrganismo" id="fCodOrganismo" style="width:300px;">
				<?=getOrganismos($fCodOrganismo, 3)?>
			</select>
		</td>
		<td align="right">Periodo:</td>
		<td>
			<input type="checkbox" checked onclick="this.checked=!this.checked" />
			<input type="text" name="fPeriodod" id="fPeriodod" value="<?=$fPeriodod?>" maxlength="7" style="width:60px;" /> - 
            <input type="text" name="fPeriodoh" id="fPeriodoh" value="<?=$fPeriodoh?>" maxlength="7" style="width:60px;" />
		</td>
	</tr>
	<tr>
		<td align="right" width="125">Proveedor:</td>
        <td class="gallery clearfix">
            <input type="checkbox" onclick="chkFiltroLista_3(this.checked, 'fCodProveedor', 'fNomProveedor', '', 'btProveedor');" />            
            <input type="text" name="fCodProveedor" id="fCodProveedor" style="width:50px;" readonly />
			<input type="text" name="fNomProveedor" id="fNomProveedor" style="width:235px;" readonly />
            <a href="../lib/listas/listado_personas.php?filtrar=default&cod=fCodProveedor&nom=fNomProveedor&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]" id="btProveedor" style="visibility:hidden;">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
		<td align="right">Ordenar Por:</td>
        <td>
			<input type="checkbox" checked onclick="this.checked=!this.checked" />
			<select name="fOrdenar" id="fOrdenar" style="width:145px;">
				<?=loadSelectValores("ORDENAR-REGISTRO-COMPRA", $fOrdenar, 0)?>
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
            	<a href="#" onclick="cargarPagina(document.getElementById('frmentrada'), 'ap_registro_compra_libro_pdf.php'); mostrarTab('tab', 1, 4);">
                	Registro de Compras
                </a>
            </li>
            <li id="li2" onclick="currentTab('tab', this);">
            	<a href="#" onclick="cargarPagina(document.getElementById('frmentrada'), 'ap_registro_compra_seniat_pdf.php'); mostrarTab('tab', 2, 4);">
                	Retenci&oacute;n I.V.A
                </a>
            </li>
            <li id="li3" onclick="currentTab('tab', this);">
            	<a href="#" onclick="cargarPagina(document.getElementById('frmentrada'), 'ap_registro_compra_retencion_islr_pdf.php'); mostrarTab('tab', 3, 4);">
                	Retenci&oacute;n I.S.L.R
                </a>
            </li>
            <li id="li4" onclick="currentTab('tab', this);">
            	<a href="#" onclick="cargarPagina(document.getElementById('frmentrada'), 'ap_registro_compra_retencion_1x1000_pdf.php'); mostrarTab('tab', 4, 4);">
                	Retenci&oacute;n 1X1000
                </a>
            </li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<center>
<div id="tab1" style="display:block;"></div>

<div id="tab2" style="display:none;">
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td align="right" width="125">Fecha:</td>
		<td>
			<input type="text" name="fFechaComprobanted" id="fFechaComprobanted" value="<?=$fFechaComprobanted?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" /> - 
            <input type="text" name="fFechaComprobanteh" id="fFechaComprobanteh" value="<?=$fFechaComprobanteh?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaComprobanteDMA(this);" />
		</td>
        <td align="right">
        	<input type="button" value="Exportar Archivo SENIAT" onclick="registro_compra_seniat_txt(this.form, 'IVA');" />
        	<input type="button" value="Exportar a Excel" onclick="registro_compra_seniat_excel(this.form, 'IVA');" />
        </td>
	</tr>
</table>
</div>
</div>

<div id="tab3" style="display:none;">
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
        <td align="right">
        	<!--<input type="button" value="Exportar a Excel" onclick="registro_compra_retencion_islr_excel(this.form, 'ISLR');" />-->
        </td>
	</tr>
</table>
</div>
</div>

<div id="tab4" style="display:none;">
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
        <td align="right">
        	<input type="button" value="Exportar a Excel" onclick="registro_compra_retencion_1x1000_excel(this.form, '1x1000');" />
        </td>
	</tr>
</table>
</div>
</div>

<iframe name="iReporte" id="iReporte" style="border:solid 1px #CDCDCD; width:1000px; height:600px;"></iframe>
</center>
</form> 