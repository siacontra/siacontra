<?php
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
if ($filtrar == "default") {
	$fCodOrganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$maxlimit = $_SESSION["MAXLIMIT"];
	$fPeriodo = "$Anio-$Mes";
	$fCodTipoPago = "02";
}
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Libro de Cheques</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="ap_libro_cheque_lista_pdf.php" method="post" target="iReporte">
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
		<td align="right" width="125">Fecha Entrega: </td>
		<td>
			<input type="checkbox" onclick="chkFiltro_2(this.checked, 'fFechaEntregadod', 'fFechaEntregadoh');" />
			<input type="text" name="fFechaEntregadod" id="fFechaEntregadod" value="<?=$fFechaEntregadod?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" disabled />-
            <input type="text" name="fFechaEntregadoh" id="fFechaEntregadoh" value="<?=$fFechaEntregadoh?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" disabled />
        </td>
	</tr>
	<tr>
		<td align="right">Banco:</td>
		<td>
			<input type="checkbox" onclick="chkFiltro_2(this.checked, 'fCodBanco', 'fNroCuenta');" />
			<select name="fCodBanco" id="fCodBanco" style="width:300px;" onChange="getOptionsSelect(this.value, 'cuentas_bancarias', 'fNroCuenta', true)" disabled>
            	<option value="">&nbsp;</option>
                <?=loadSelect("mastbancos", "CodBanco", "Banco", "", 0)?>
			</select>
		</td>
		<td align="right">Fecha Cobranza: </td>
		<td>
			<input type="checkbox" onclick="chkFiltro_2(this.checked, 'fFechaCobranzad', 'fFechaCobranzah');" />
			<input type="text" name="fFechaCobranzad" id="fFechaCobranzad" value="<?=$fFechaCobranzad?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" disabled />-
            <input type="text" name="fFechaCobranzah" id="fFechaCobranzah" value="<?=$fFechaCobranzah?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" disabled />
        </td>
	</tr>
	<tr>
		<td align="right">Cta. Bancaria:</td>
		<td>
			<input type="checkbox" style="visibility:hidden;" />
			<select name="fNroCuenta" id="fNroCuenta" style="width:300px;" disabled>
            	<option value="">&nbsp;</option>
                <?=loadSelectDependiente("ap_ctabancaria", "NroCuenta", "NroCuenta", "CodBanco", "", "", 0)?>
			</select>
		</td>
		<td align="right">Fecha Dif. Original: </td>
		<td>
			<input type="checkbox" onclick="chkFiltro_2(this.checked, 'fFechaEmisionDiferidod', 'fFechaEmisionDiferidoh');" />
			<input type="text" name="fFechaEmisionDiferidod" id="fFechaEmisionDiferidod" value="<?=$fFechaEmisionDiferidod?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" disabled />-
            <input type="text" name="fFechaEmisionDiferidoh" id="fFechaEmisionDiferidoh" value="<?=$fFechaEmisionDiferidoh?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" disabled />
        </td>
	</tr>
	<tr>
		<td align="right">Fecha: </td>
		<td>
			<input type="checkbox" onclick="chkFiltro_2(this.checked, 'fFechaPagod', 'fFechaPagoh');" />
			<input type="text" name="fFechaPagod" id="fFechaPagod" value="<?=$fFechaPagod?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" disabled />-
            <input type="text" name="fFechaPagoh" id="fFechaPagoh" value="<?=$fFechaPagoh?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" disabled />
        </td>
		<td align="right">Montos: </td>
		<td>
			<input type="checkbox" onclick="chkFiltro_2(this.checked, 'fMontoPagod', 'fMontoPagoh');" />
			<input type="text" name="fMontoPagod" id="fMontoPagod" style="width:60px;" disabled />-
            <input type="text" name="fMontoPagoh" id="fMontoPagoh" style="width:60px;" disabled />
        </td>
	</tr>
	<tr>
		<td align="right">Periodo:</td>
		<td>
			<input type="checkbox" onclick="chkFiltro(this.checked, 'fPeriodo');" checked />
			<input type="text" name="fPeriodo" id="fPeriodo" maxlength="7" style="width:60px;" value="<?=$fPeriodo?>" />
		</td>
		<td align="right">Tipo de Pago:</td>
		<td>
			<input type="checkbox" onclick="this.checked=!this.checked;" checked />
			<select name="fCodTipoPago" id="fCodTipoPago" style="width:140px;" onChange="getOptionsSelect(this.value, 'cuentas_bancarias', 'fNroCuenta', true)">
                <?=loadSelect("masttipopago", "CodTipoPago", "TipoPago", $fCodTipoPago, 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">Estado Entrega:</td>
		<td>
        	<input type="checkbox" onclick="chkFiltro(this.checked, 'fEstadoEntrega');" />
            <select name="fEstadoEntrega" id="fEstadoEntrega" style="width:105px;" disabled>
                <option value="">&nbsp;</option>
                <?=loadSelectValores("ESTADO-ENTREGA-CHEQUE", "", 0)?>
            </select>
		</td>
		<td align="right">Ordenar Por:</td>
		<td>
			<input type="checkbox" onclick="this.checked=!this.checked;" checked />
			<select name="fordenar" id="fordenar" style="width:140px;">
                <?=loadSelectValores("ORDENAR-LIBRO-CHEQUE", $fordenar, 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">Estado Doc:</td>
		<td>
        	<input type="checkbox" onclick="chkFiltro(this.checked, 'fEstado');" />
            <select name="fEstado" id="fEstado" style="width:105px;" disabled>
                <option value="">&nbsp;</option>
                <?=loadSelectValores("ESTADO-PAGO3", "", 0)?>
            </select>
		</td>
		<td align="right">Cobrado:</td>
		<td>
        	<input type="checkbox" onclick="chkFiltro(this.checked, 'fFlagCobrado');" />
            <select name="fFlagCobrado" id="fFlagCobrado" style="width:105px;" disabled>
                <option value="">&nbsp;</option>
                <?=loadSelectGeneral("FLAG", $fFlagCobrado, 0)?>
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
            	<a href="#" onclick="cargarPagina(document.getElementById('frmentrada'), 'ap_libro_cheque_lista_pdf.php');">
                	Libro de Cheques
                </a>
            </li>
            <li id="li2" onclick="currentTab('tab', this);">
            	<a href="#" onclick="cargarPagina(document.getElementById('frmentrada'), 'ap_libro_cheque_entregados_pdf.php');">
                	Cheques Entregados
                </a>
            </li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<center>
<iframe name="iReporte" id="iReporte" style="border:solid 1px #CDCDCD; width:1000px; height:600px;"></iframe>
</center>
</form> 
