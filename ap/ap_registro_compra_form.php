<?php
list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
//	------------------------------------
if ($opcion == "nuevo") {
	$accion = "nuevo";
	$titulo = "Agregar Registro";
	$label_submit = "Guardar";
	$field['Periodo'] = "$Anio-$Mes";
	$field['SistemaFuente'] = "CP";
	$field['FechaDocumento'] = "$Anio-$Mes-$Dia";
	$field['Fecha'] = "$Anio-$Mes-$Dia";
	$field['VoucherPeriodo'] = "$Anio-$Mes";
}
elseif ($opcion == "modificar" || $opcion == "ver") {
	list($Periodo, $SistemaFuente, $Secuencia) = split("[.]", $registro);
	
	//	consulto datos generales
	$sql = "SELECT rc.*
			FROM ap_registrocompras rc
			WHERE
				rc.Periodo = '".$Periodo."' AND
				rc.SistemaFuente = '".$SistemaFuente."' AND
				rc.Secuencia = '".$Secuencia."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query)) $field = mysql_fetch_array($query);
	
	if ($opcion == "modificar") {
		$titulo = "Modificar Registro";
		$accion = "modificar";
		$disabled_modificar = "disabled";
		$display_modificar = "display:none;";
		$label_submit = "Modificar";
	}
	
	elseif ($opcion == "ver") {
		$titulo = "Ver Registro";
		$disabled_ver = "disabled";
		$display_ver = "display:none;";
		$display_submit = "display:none;";
		$disabled_modificar = "disabled";
		$display_modificar = "display:none;";
	}
}
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="document.getElementById('frmentrada').submit()">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<table width="900" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" onclick="currentTab('tab', this);" class="current"><a href="#" onclick="mostrarTab('tab', 1, 2);">Informaci&oacute;n General</a></li>
            <li id="li2" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 2, 2);">Detalle de los Montos</a></li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=ap_registro_compra_lista" method="POST" onsubmit="return registro_compra(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="fCodOrganismo" id="fCodOrganismo" value="<?=$fCodOrganismo?>" />
<input type="hidden" name="fCodProveedor" id="fCodProveedor" value="<?=$fCodProveedor?>" />
<input type="hidden" name="fNomProveedor" id="fNomProveedor" value="<?=$fNomProveedor?>" />
<input type="hidden" name="fSistemaFuente" id="fSistemaFuente" value="<?=$fSistemaFuente?>" />
<input type="hidden" name="fPeriodo" id="fPeriodo" value="<?=$fPeriodo?>" />

<div id="tab1" style="display:block;">
<table width="900" class="tblForm">
	<tr>
    	<td colspan="4" class="divFormCaption">Informaci&oacute;n General</td>
    </tr>
	<tr>
		<td class="tagForm" width="125">* Organismo:</td>
		<td>
        	<select id="CodOrganismo" style="width:300px;" <?=$disabled_modificar?>>
            	<?=loadSelect("mastorganismos", "CodOrganismo", "Organismo", $field['CodOrganismo'], 0)?>
            </select>
		</td>
		<td class="tagForm" width="150">Periodo:</td>
		<td><input type="text" id="Periodo" style="width:50px;" value="<?=$field['Periodo']?>" disabled /></td>
	</tr>
    <tr>
		<td class="tagForm">* Proveedor:</td>
		<td class="gallery clearfix">
        	<input type="text" id="CodProveedor" value="<?=$field['CodProveedor']?>" disabled="disabled" style="width:50px;" />
			<input type="text" id="NomProveedor" value="<?=$field['NomProveedor']?>" disabled="disabled" style="width:235px;" />
			<a href="../lib/listas/listado_personas.php?filtrar=default&cod=CodProveedor&nom=NomProveedor&campo3=DocFiscal&EsEmpleado=S&EsProveedor=S&EsOtros=S&ventana=registro_compra_form&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]" style=" <?=$display_modificar?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
		<td class="tagForm">Sistema Fuente:</td>
		<td>
        	<input type="hidden" id="SistemaFuente" value="<?=$field['SistemaFuente']?>" />
        	<input type="text" style="width:168px;" value="<?=printValores("SISTEMA-FUENTE-REGISTRO-COMPRA", $field['SistemaFuente'])?>" disabled />
        </td>
	</tr>
    <tr>
		<td class="tagForm">Doc. Fiscal:</td>
		<td>
        	<input type="text" id="DocFiscal" style="width:100px;" value="<?=$field['DocFiscal']?>" disabled="disabled" />
        </td>
		<td class="tagForm">* Documento:</td>
		<td>
        	<select id="CodTipoDocumento" style="width:40px;" <?=$disabled_modificar?>>
            	<option value="">&nbsp;</option>
                <?=loadSelect("ap_tipodocumento", "CodTipoDocumento", "Descripcion", $field['CodTipoDocumento'], 10)?>
            </select>
			<input type="text" id="NroDocumento" maxlength="20" style="width:125px;" value="<?=$field['NroDocumento']?>" <?=$disabled_modificar?> />
		</td>
	</tr>
    <tr>
		<td class="tagForm">&nbsp;</td>
		<td colspan="3">
        	<input type="checkbox" id="FlagIncluirRegistro" <?=chkFlag($field['FlagIncluirRegistro'])?> <?=$disabled_ver?> /> Incluir en el Registro de Compras
        </td>
	</tr>
	<tr>
    	<td colspan="4" class="divFormCaption">Datos del Documento</td>
    </tr>
    <tr>
        <td class="tagForm">Fecha:</td>
        <td><input type="text" id="FechaDocumento" value="<?=formatFechaDMA($field['FechaDocumento'])?>" style="width:75px;" class="datepicker" onkeyup="setFechaDMA(this);" <?=$disabled_ver?> /></td>
		<td class="tagForm">Obligaci&oacute;n Relacionada:</td>
		<td>
			<input type="text" id="ObligacionCodDocumento" maxlength="3" style="width:34px;" value="<?=$field['ObligacionCodDocumento']?>" <?=$disabled_ver?> />
			<input type="text" id="ObligacionNroDocumento" maxlength="20" style="width:125px;" value="<?=$field['ObligacionNroDocumento']?>" <?=$disabled_ver?> />
		</td>
    </tr>
    <tr>
		<td class="tagForm">Voucher:</td>
		<td>
			<input type="text" id="VoucherPeriodo" maxlength="7" style="width:50px;" value="<?=$field['VoucherPeriodo']?>" <?=$disabled_ver?> />
			<input type="text" id="Voucher" maxlength="7" style="width:50px;" value="<?=$field['Voucher']?>" <?=$disabled_ver?> />
		</td>
        <td class="tagForm">Fecha:</td>
        <td><input type="text" id="Fecha" value="<?=formatFechaDMA($field['Fecha'])?>" style="width:75px;" class="datepicker" onkeyup="setFechaDMA(this);" <?=$disabled_ver?> /></td>
    </tr>
    <tr>
		<td class="tagForm">Doc. Relacionado:</td>
		<td colspan="3">
			<input type="text" id="DocRelacionado" maxlength="3" style="width:34px;" value="<?=$field['DocRelacionado']?>" disabled />
			<input type="text" id="NroRelacionado" maxlength="20" style="width:125px;" value="<?=$field['NroRelacionado']?>" disabled />
		</td>
    </tr>
	<tr>
    	<td colspan="4" class="divFormCaption">Comentarios</td>
    </tr>
    <tr>
    	<td colspan="4">
        	<textarea id="Comentarios" style="width:100%; height:45px;" <?=$disabled_ver?>><?=($field['Comentarios'])?></textarea>
        </td>
    </tr>
</table>
<table width="900" class="tblForm">
	<tr>
		<td class="tagForm">&Uacute;ltima Modif.:</td>
		<td>
			<input type="text" size="30" class="disabled" value="<?=$field['UltimoUsuario']?>" disabled="disabled" />
			<input type="text" size="25" class="disabled" value="<?=$field['UltimaFecha']?>" disabled="disabled" />
		</td>
	</tr>
</table>
<center>
<input type="submit" value="<?=$label_submit?>" style=" <?=$display_submit?>" />
<input type="button" value="Cancelar" onclick="this.form.submit();" />
</center>
<div style="width:900px" class="divMsj">Campos Obligatorios *</div>
</div>

<div id="tab2" style="display:none;">
<table width="900" class="tblForm">
	<tr>
    	<td>&nbsp;</td>
    	<td class="divFormCaption">Obligaci&oacute;n Original</td>
    	<td class="divFormCaption">Montos Reportados</td>
    	<td class="divFormCaption">Desagregaci&oacute;n del Monto Imponible</td>
    	<td class="divFormCaption">Monto Imponible</td>
    	<td class="divFormCaption">I.V.A.</td>
	</tr>
    <tr>
		<td class="tagForm">Monto Imponible:</td>
		<td width="95" align="center"><input type="text" id="imponible_original" value="<?=number_format($field['Monto1'], 2, ',', '.')?>" style="width:96%; text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
		<td width="95" align="center"><input type="text" id="imponible_original" value="<?=number_format($field['Monto1'], 2, ',', '.')?>" style="width:96%; text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
		<td class="tagForm">Operaciones Gravadas:</td>
		<td width="95" align="center"><input type="text" id="imponible_original" value="<?=number_format($field['Monto1'], 2, ',', '.')?>" style="width:96%; text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
		<td width="95" align="center"><input type="text" id="imponible_original" value="<?=number_format($field['Monto1'], 2, ',', '.')?>" style="width:96%; text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
	</tr>
    <tr>
		<td class="tagForm">Monto No Afecto:</td>
		<td width="95" align="center"><input type="text" id="imponible_original" value="<?=number_format($field['Monto1'], 2, ',', '.')?>" style="width:96%; text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
		<td width="95" align="center"><input type="text" id="imponible_original" value="<?=number_format($field['Monto1'], 2, ',', '.')?>" style="width:96%; text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
		<td class="tagForm">Operaciones Gravadas y No Gravadas:</td>
		<td width="95" align="center"><input type="text" id="imponible_original" value="<?=number_format($field['Monto1'], 2, ',', '.')?>" style="width:96%; text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
		<td width="95" align="center"><input type="text" id="imponible_original" value="<?=number_format($field['Monto1'], 2, ',', '.')?>" style="width:96%; text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
	</tr>
    <tr>
		<td class="tagForm">I.V.A.:</td>
		<td width="95" align="center"><input type="text" id="imponible_original" value="<?=number_format($field['Monto1'], 2, ',', '.')?>" style="width:96%; text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
		<td width="95" align="center"><input type="text" id="imponible_original" value="<?=number_format($field['Monto1'], 2, ',', '.')?>" style="width:96%; text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
		<td class="tagForm">&nbsp;</td>
		<td width="95" align="center" style="border-top:#000 1px solid;">
        	<input type="text" id="imponible_original" value="<?=number_format($field['Monto1'], 2, ',', '.')?>" style="width:96%; text-align:right; font-size:11px; font-weight:bold;" disabled="disabled" />
		</td>
		<td width="95" align="center" style="border-top:#000 1px solid;">
        	<input type="text" id="imponible_original" value="<?=number_format($field['Monto1'], 2, ',', '.')?>" style="width:96%; text-align:right; font-size:11px; font-weight:bold;" disabled="disabled" />
		</td>
	</tr>
    <tr>
		<td class="tagForm">Total Documento:</td>
		<td width="95" align="center" style="border-top:#000 1px solid;">
        	<input type="text" id="imponible_original" value="<?=number_format($field['Monto1'], 2, ',', '.')?>" style="width:96%; text-align:right; font-size:11px; font-weight:bold;" disabled="disabled" />
		</td>
		<td width="95" align="center" style="border-top:#000 1px solid;">
        	<input type="text" id="imponible_original" value="<?=number_format($field['Monto1'], 2, ',', '.')?>" style="width:96%; text-align:right; font-size:11px; font-weight:bold;" disabled="disabled" />
		</td>
		<td class="divFormCaption">Desagregaci&oacute;n del Monto No Afecto</td>
		<td class="tagForm">&nbsp;</td>
		<td class="tagForm">&nbsp;</td>
	</tr>
    <tr>
		<td class="tagForm">&nbsp;</td>
		<td class="tagForm">&nbsp;</td>
		<td class="tagForm">&nbsp;</td>
		<td class="tagForm">Imponible:</td>
		<td width="95" align="center"><input type="text" id="imponible_original" value="<?=number_format($field['Monto1'], 2, ',', '.')?>" style="width:96%; text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
		<td class="tagForm">&nbsp;</td>
	</tr>
    <tr>
		<td class="tagForm">&nbsp;</td>
		<td class="tagForm">&nbsp;</td>
		<td class="tagForm">&nbsp;</td>
		<td class="tagForm">I.V.A.:</td>
		<td width="95" align="center"><input type="text" id="imponible_original" value="<?=number_format($field['Monto1'], 2, ',', '.')?>" style="width:96%; text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
		<td class="tagForm">&nbsp;</td>
	</tr>
    <tr>
		<td class="tagForm">&nbsp;</td>
		<td class="tagForm">&nbsp;</td>
		<td class="tagForm">&nbsp;</td>
		<td class="tagForm">Adquisiciones e Importaciones No Gravadas:</td>
		<td width="95" align="center"><input type="text" id="imponible_original" value="<?=number_format($field['Monto1'], 2, ',', '.')?>" style="width:96%; text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
		<td class="tagForm">&nbsp;</td>
	</tr>
    <tr>
		<td class="tagForm">&nbsp;</td>
		<td class="tagForm">&nbsp;</td>
		<td class="tagForm">&nbsp;</td>
		<td class="tagForm">Otros Tributos y Cargos que no forman parte de la Base Imponible:</td>
		<td width="95" align="center"><input type="text" id="imponible_original" value="<?=number_format($field['Monto1'], 2, ',', '.')?>" style="width:96%; text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
		<td class="tagForm">&nbsp;</td>
	</tr>
    <tr>
		<td class="tagForm">&nbsp;</td>
		<td class="tagForm">&nbsp;</td>
		<td class="tagForm">&nbsp;</td>
		<td class="tagForm">Imponible:</td>
		<td width="95" align="center"><input type="text" id="imponible_original" value="<?=number_format($field['Monto1'], 2, ',', '.')?>" style="width:96%; text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
		<td class="tagForm">&nbsp;</td>
	</tr>
    <tr>
		<td class="tagForm">&nbsp;</td>
		<td class="tagForm">&nbsp;</td>
		<td class="tagForm">&nbsp;</td>
		<td class="tagForm">I.V.A.:</td>
		<td width="95" align="center"><input type="text" id="imponible_original" value="<?=number_format($field['Monto1'], 2, ',', '.')?>" style="width:96%; text-align:right;" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
		<td class="tagForm">&nbsp;</td>
	</tr>
    <tr>
		<td class="tagForm">&nbsp;</td>
		<td class="tagForm">&nbsp;</td>
		<td class="tagForm">&nbsp;</td>
		<td class="tagForm">&nbsp;</td>
		<td width="95" align="center" style="border-top:#000 1px solid;">
        	<input type="text" id="imponible_original" value="<?=number_format($field['Monto1'], 2, ',', '.')?>" style="width:96%; text-align:right; font-size:11px; font-weight:bold;" disabled="disabled" />
		</td>
		<td class="tagForm">&nbsp;</td>
	</tr>
</table>
</div>
</form>