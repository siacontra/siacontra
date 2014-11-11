<?php
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
if ($filtrar == "default") {
	$fCodOrganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$maxlimit = $_SESSION["MAXLIMIT"];
	$fFechaTransacciond = "01-$Mes-$Anio";
	$fFechaTransaccionh = "$Dia-$Mes-$Anio";
	$fCodTipoPago = "02";
}
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Reporte de Bancos</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="ap_libro_banco_pdf.php" method="post" target="iReporte">
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
		<td align="right" width="125">Banco:</td>
		<td>
			<input type="checkbox" onclick="chkFiltro_2(this.checked, 'fCodBanco', 'fNroCuenta');" />
			<select name="fCodBanco" id="fCodBanco" style="width:300px;" onChange="getOptionsSelect(this.value, 'cuentas_bancarias', 'fNroCuenta', true)" disabled>
            	<option value="">&nbsp;</option>
                <?=loadSelect("mastbancos", "CodBanco", "Banco", "", 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">Fecha:</td>
		<td>
			<input type="checkbox" onclick="this.checked=!this.checked;" checked />
			<input type="text" name="fFechaTransacciond" id="fFechaTransacciond" value="<?=$fFechaTransacciond?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" />-
            <input type="text" name="fFechaTransaccionh" id="fFechaTransaccionh" value="<?=$fFechaTransaccionh?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" />
        </td>
		<td align="right">Cta. Bancaria:</td>
		<td>
			<input type="checkbox" style="visibility:hidden;" />
			<select name="fNroCuenta" id="fNroCuenta" style="width:300px;" disabled>
            	<option value="">&nbsp;</option>
                <?=loadSelectDependiente("ap_ctabancaria", "NroCuenta", "NroCuenta", "CodBanco", "", "", 0)?>
			</select>
		</td>
	</tr>
</table>
</div>
<center><input type="submit" name="btBuscar" value="Buscar"></center>

<br />

<center>
<iframe name="iReporte" id="iReporte" style="border:solid 1px #CDCDCD; width:1000px; height:600px;"></iframe>
</center>
</form> 