<?php
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
if ($filtrar == "default") {
	$fCodOrganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$maxlimit = $_SESSION["MAXLIMIT"];
	$fFechaPagod = "01-$Mes-$Anio";
	$fFechaPagoh = "$Dia-$Mes-$Anio";
	$fCodTipoPago = "02";
}
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Estado de Entrega de Cheques</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="ap_cheque_estado_entrega_cartera_pdf.php" method="post" target="iReporte">
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
		<td align="right" width="125">Fecha:</td>
		<td>
			<input type="checkbox" onclick="this.checked=!this.checked;" checked />
			<input type="text" name="fFechaPagod" id="fFechaPagod" value="<?=$fFechaPagod?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" />-
            <input type="text" name="fFechaPagoh" id="fFechaPagoh" value="<?=$fFechaPagoh?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" />
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
		<td align="right">Tipo de Pago:</td>
		<td>
			<input type="checkbox" onclick="this.checked=!this.checked;" checked />
			<select name="fCodTipoPago" id="fCodTipoPago" style="width:140px;" onChange="getOptionsSelect(this.value, 'cuentas_bancarias', 'fNroCuenta', true)">
                <?=loadSelect("masttipopago", "CodTipoPago", "TipoPago", $fCodTipoPago, 0)?>
			</select>
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
		<td>&nbsp;</td>
		<td><input type="checkbox" name="FlagAnulado" id="FlagAnulado" value="S" /> Incluir Anulados</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><input type="checkbox" name="FlagObligaciones" id="FlagObligaciones" value="S" /> Ver Detalle de Obligaciones</td>
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
            	<a href="#" onclick="cargarPagina(document.getElementById('frmentrada'), 'ap_cheque_estado_entrega_cartera_pdf.php');">
                	En Cartera
                </a>
            </li>
            <li id="li2" onclick="currentTab('tab', this);">
            	<a href="#" onclick="cargarPagina(document.getElementById('frmentrada'), 'ap_cheque_estado_entrega_entregado_pdf.php');">
                	Entregados
                </a>
            </li>
            <li id="li3" onclick="currentTab('tab', this);">
            	<a href="#" onclick="cargarPagina(document.getElementById('frmentrada'), 'ap_cheque_estado_entrega_proveedor_pdf.php');">
                	En Cartera x Proveedor
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