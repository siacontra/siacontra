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
		<td class="titulo">Listado de Transacciones</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="ap_listado_transaccion_pdf.php" method="post" target="iReporte">
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
			<input type="checkbox" onclick="chkFiltro_2(this.checked, 'fFechaTransacciond', 'fFechaTransaccionh');" checked />
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
	<tr>
		<td align="right">Periodo:</td>
		<td>
			<input type="checkbox" onclick="chkFiltro_2(this.checked, 'fPeriodoContabled', 'fPeriodoContableh');" />
			<input type="text" name="fPeriodoContabled" id="fPeriodoContabled" maxlength="7" style="width:60px;" disabled />-
            <input type="text" name="fPeriodoContableh" id="fPeriodoContableh" maxlength="7" style="width:60px;" disabled />
        </td>
		<td align="right">Tipo de Transacci&oacute;n:</td>
		<td>
			<input type="checkbox" onclick="chkFiltro(this.checked, 'fCodTipoTransaccion');" />
			<select name="fCodTipoTransaccion" id="fCodTipoTransaccion" style="width:300px;" disabled>
            	<option value="">&nbsp;</option>
                <?=loadSelect("ap_bancotipotransaccion", "CodTipoTransaccion", "Descripcion", "", 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">Estado:</td>
		<td>
        	<input type="checkbox" onclick="chkFiltro(this.checked, 'fEstado');" />
            <select name="fEstado" id="fEstado" style="width:105px;" disabled>
                <option value="">&nbsp;</option>
                <?=loadSelectValores("ESTADO-TRANSACCION-BANCARIA", "", 0)?>
            </select>
		</td>
		<td align="right">Persona:</td>
		<td class="gallery clearfix">
            <input type="checkbox" <?=$cCodProveedor?> onclick="chkFiltroLista_3(this.checked, 'fCodProveedor', 'fNomProveedor', '', 'btProveedor');" />
            
            <input type="text" name="fCodProveedor" id="fCodProveedor" style="width:50px;" value="<?=$fCodProveedor?>" readonly="readonly" />
			<input type="text" name="fNomProveedor" id="fNomProveedor" style="width:200px;" value="<?=$fNomProveedor?>" readonly="readonly" />
            <a href="../lib/listas/listado_personas.php?filtrar=default&cod=fCodProveedor&nom=fNomProveedor&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]" id="btProveedor" style="visibility:hidden;">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
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
