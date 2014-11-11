<?php
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
if ($filtrar == "default") {
	$fCodOrganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$fEstado = "PE";
	$maxlimit = $_SESSION["MAXLIMIT"];
	$fordenar = "NroOrden";
	$FlagPagoDiferido = "";
	$fFechaVencimientod = "01-$Mes-$Anio";
	$fFechaVencimientoh = "$Dia-$Mes-$Anio";
}
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Lista de Ordenes de Pago</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="ap_pagos_proveedores_proceso_pdf.php" method="post" target="iReporte">
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
		<td align="right">Proveedor: </td>
		<td class="gallery clearfix">
            <input type="checkbox" onclick="chkFiltroLista_3(this.checked, 'fCodProveedor', 'fNomProveedor', '', 'btProveedor');" />            
            <input type="text" name="fCodProveedor" id="fCodProveedor" style="width:50px;" readonly />
			<input type="text" name="fNomProveedor" id="fNomProveedor" style="width:200px;" readonly />
            <a href="../lib/listas/listado_personas.php?filtrar=default&cod=fCodProveedor&nom=fNomProveedor&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]" id="btProveedor" style="visibility:hidden;">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
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

<table width="1000" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" onclick="currentTab('tab', this);" class="current">
            	<a href="#" onclick="cargarPagina(document.getElementById('frmentrada'), 'ap_pagos_proveedores_proceso_pdf.php'); mostrarTab('tab', 1, 2);">
                	Pagos x N&uacute;mero de Proceso
                </a>
            </li>
            <li id="li2" onclick="currentTab('tab', this);">
            	<a href="#" onclick="cargarPagina(document.getElementById('frmentrada'), 'ap_pagos_proveedores_pendientes_pdf.php'); mostrarTab('tab', 2, 2);">
                	Ordenes de Pago Pendientes
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
		<td align="right" width="125">Nro Proceso:</td>
		<td>
        	<input type="checkbox" style="visibility:hidden;" />
			<input type="text" name="fNroProceso" id="fNroProceso" maxlength="6" style="width:100px;" />
		</td>
	</tr>
</table>
</div>
</div>

<div id="tab2" style="display:none;">
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td align="right">Tipo Doc.:</td>
		<td>
			<input type="checkbox" onclick="chkFiltro(this.checked, 'fCodTipoDocumento');" />
			<select name="fCodTipoDocumento" id="fCodTipoDocumento" style="width:300px;" disabled>
            	<option value="">&nbsp;</option>
                <?=loadSelect("ap_tipodocumento", "CodTipoDocumento", "Descripcion", "", 0)?>
			</select>
		</td>
		<td align="right">Sistema Fuente:</td>
		<td>
			<input type="checkbox" onclick="chkFiltro(this.checked, 'fCodSistemaFuente');" />
			<select name="fCodSistemaFuente" id="fCodSistemaFuente" style="width:300px;" disabled>
            	<option value="">&nbsp;</option>
                <?=loadSelect("ac_sistemafuente", "CodSistemaFuente", "Descripcion", "", 0)?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right" width="125">Nro Doc.:</td>
		<td>
			<input type="checkbox" onclick="chkFiltro(this.checked, 'fNroDocumento');" />
			<input type="text" name="fNroDocumento" id="fNroDocumento" maxlength="20" style="width:100px;" disabled />
		</td>
		<td align="right" width="125">F.Vencimiento: </td>
		<td>
			<input type="checkbox" onclick="chkFiltro_2(this.checked, 'fFechaVencimientod', 'fFechaVencimientoh');" checked />
			<input type="text" name="fFechaVencimientod" id="fFechaVencimientod" value="<?=$fFechaVencimientod?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" />-
            <input type="text" name="fFechaVencimientoh" id="fFechaVencimientoh" value="<?=$fFechaVencimientoh?>" maxlength="10" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" />
        </td>
	</tr>
</table>
</div>
</div>
<iframe name="iReporte" id="iReporte" style="border:solid 1px #CDCDCD; width:1000px; height:600px;"></iframe>
</center>
</form> 