<?php
$Ahora = ahora();
list($NroProceso, $Secuencia, $CodTipoPago) = split("[.]", $registro);
$sql = "SELECT
			p.*,
			mp.NomCompleto AS NomProveedor,
			b.CodBanco,
			b.Banco,
			e1.CodEmpleado AS CodGeneradoPor,
			p1.NomCompleto AS NomGeneradoPor,
			e2.CodEmpleado AS CodConformadoPor,
			p2.NomCompleto AS NomConformadoPor,
			e3.CodEmpleado AS CodAprobadoPor,
			p3.NomCompleto AS NomAprobadoPor
		FROM
			ap_pagos p
			INNER JOIN mastpersonas mp ON (p.CodProveedor = mp.CodPersona)
			INNER JOIN ap_ctabancaria cb ON (p.NroCuenta = cb.NroCuenta)
			INNER JOIN mastbancos b ON (cb.CodBanco = b.CodBanco)
			LEFT JOIN mastpersonas p1 ON (p.GeneradoPor = p1.CodPersona)
			LEFT JOIN mastempleado e1 ON (p1.CodPersona = e1.CodPersona)
			LEFT JOIN mastpersonas p2 ON (p.ConformadoPor = p2.CodPersona)
			LEFT JOIN mastempleado e2 ON (p2.CodPersona = e2.CodPersona)
			LEFT JOIN mastpersonas p3 ON (p.AprobadoPor = p3.CodPersona)
			LEFT JOIN mastempleado e3 ON (p3.CodPersona = e3.CodPersona)
		WHERE
			p.NroProceso = '".$NroProceso."' AND
			p.Secuencia = '".$Secuencia."'";
$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query)) $field = mysql_fetch_array($query);
if ($opcion == "modificar") {
	$titulo = "Modificaci&oacute;n Restringida del Pago";
	$accion = "modificar";
	$label_submit = "Modificar";
	$disabled_anular = "disabled";
}

elseif ($opcion == "ver") {
	$titulo = "Ver Pago";
	$disabled_ver = "disabled";
	$display_ver = "display:none;";
	$display_submit = "display:none;";
	$disabled_anular = "disabled";
}

elseif ($opcion == "anular") {
	$titulo = "Anular Pago";
	$disabled_ver = "disabled";
	$display_ver = "display:none;";
	$accion = "anular";
	$label_submit = "Anular";
}
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo"><?=$titulo?></td>
		<td align="right"><a class="cerrar" href="#" onclick="document.getElementById('frmentrada').submit()">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<table width="1000" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="header">
            <ul id="tab">
            <!-- CSS Tabs -->
            <li id="li1" onclick="currentTab('tab', this);" class="current"><a href="#" onclick="mostrarTab('tab', 1, 2);">Informaci&oacute;n General</a></li>
            <li id="li2" onclick="currentTab('tab', this);"><a href="#" onclick="mostrarTab('tab', 2, 2);">Sustento del Pago</a></li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=ap_pago_lista" method="POST" onsubmit="return pago(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="maxlimit" id="maxlimit" value="<?=$maxlimit?>" />
<input type="hidden" name="lista" id="lista" value="<?=$lista?>" />
<input type="hidden" name="fCodOrganismo" id="fCodOrganismo" value="<?=$fCodOrganismo?>" />
<input type="hidden" name="fCodProveedor" id="fCodProveedor" value="<?=$fCodProveedor?>" />
<input type="hidden" name="fNomProveedor" id="fNomProveedor" value="<?=$fNomProveedor?>" />
<input type="hidden" name="fNroProceso" id="fNroProceso" value="<?=$fNroProceso?>" />
<input type="hidden" name="fNroPago" id="fNroPago" value="<?=$fNroPago?>" />
<input type="hidden" name="fEstado" id="fEstado" value="<?=$fEstado?>" />
<input type="hidden" name="fFechaPagod" id="fFechaPagod" value="<?=$fFechaPagod?>" />
<input type="hidden" name="fFechaPagoh" id="fFechaPagoh" value="<?=$fFechaPagoh?>" />
<input type="hidden" id="Anio" value="<?=$field['Anio']?>" />
<input type="hidden" id="NroOrden" value="<?=$field['NroOrden']?>" />
<input type="hidden" id="CodVoucher" value="<?=substr($field['VoucherPago'], 0, 2)?>" />

<div id="tab1" style="display:block;">
<table align="center" width="1000" class="tblForm">
    <tr>
        <td colspan="4" class="divFormCaption" style="height:20px;">Informaci&oacute;n Adicional</td>
    </tr>
    <tr>
        <td class="tagForm" width="125">Organismo:</td>
        <td>
            <select id="CodOrganismo" style="width:300px;" disabled="disabled">
                <?=loadSelect("mastorganismos", "CodOrganismo", "Organismo", $field['CodOrganismo'], 1);?>
            </select>
        </td>
        <td class="tagForm" width="125">Pagar A: </td>
        <td>
            <input type="hidden" id="CodProveedor" value="<?=$field['CodProveedor']?>" />
            <input type="text" id="NomProveedor" value="<?=($field['NomProveedor'])?>" style="width:300px;" disabled="disabled" />
        </td>
    </tr>
    <tr>
        <td class="tagForm">Cta. Bancaria:</td>
        <td>
            <select id="NroCuenta" style="width:175px;" disabled="disabled">
                <?=loadSelect("ap_ctabancaria", "NroCuenta", "NroCuenta", $field['NroCuenta'], 1);?>
            </select>
        </td>
        <td class="tagForm">Pago:</td>
        <td>
            <input type="text" id="NroProceso" style="width:60px; font-weight:bold; text-align:center; font-size:14px;" value="<?=$field['NroProceso']?>" disabled="disabled" />
            <input type="text" id="Secuencia" style="width:20px; font-weight:bold; text-align:center; font-size:14px;" value="<?=$field['Secuencia']?>" disabled="disabled" /> - 
            <input type="text" id="NroPago" style="width:85px; font-weight:bold; text-align:center; font-size:14px;" value="<?=$field['NroPago']?>" disabled="disabled" />
        </td>
    </tr>
</table>

<table align="center" width="1000" class="tblForm">
    <tr>
        <td colspan="2" class="divFormCaption" style="height:20px;">Datos del pago</td>
        <td colspan="2" class="divFormCaption">Estados del Pago</td>
        <td colspan="2" class="divFormCaption">Contabilizaci&oacute;n</td>
    </tr>
    <tr>
        <td class="tagForm">Fecha de Pago:</td>
        <td><input type="text" id="FechaPago" maxlength="10" style="width:75px;" class="datepicker" onkeyup="setFechaDMA(this);" value="<?=formatFechaDMA($field['FechaPago'])?>" <?=$disabled_ver?> /></td>
        <td class="tagForm">De Impresi&oacute;n:</td>
        <td><input type="text" id="Estado" style="width:75px;" value="<?=printValores("ESTADO-PAGO", $field['Estado'])?>" disabled /></td>
        <td class="tagForm">Contabilizado:</td>
        <td>
        	<input type="text" style="width:20px;" value="<?=printValoresGeneral("FLAG-CONTABILIZADO", $field['FlagContabilizacionPendiente'])?>" disabled="disabled" />
        	<input type="hidden" id="FlagContabilizacionPendiente" value="<?=$field['FlagContabilizacionPendiente']?>" />
        </td>
    </tr>
    <tr>
        <td class="tagForm">Tipo de Pago</td>
        <td>
            <select id="CodTipoPago" style="width:175px;" disabled>
                <?=loadSelect("masttipopago", "CodTipoPago", "TipoPago", $field['CodTipoPago'], 1)?>
            </select>
        </td>
        <td class="tagForm">De Entrega:</td>
        <td><input type="text" id="EstadoEntrega" style="width:75px;" value="<?=printValores("ESTADO-CHEQUE", $field['EstadoEntrega'])?>" disabled="disabled" /></td>
        <td class="tagForm">Voucher:</td>
        <td>
            <input type="text" id="Periodo" style="width:50px;" value="<?=$field['Periodo']?>" disabled="disabled" />-
            <input type="text" id="VoucherPago" style="width:50px;" value="<?=$field['VoucherPago']?>" disabled="disabled" />
        </td>
    </tr>
    <tr>
        <td class="tagForm">Origen</td>
        <td><input type="text" id="OrigenGeneracion" style="width:75px;" value="<?=printValores("ORIGEN-PAGO", $field['OrigenGeneracion'])?>" disabled="disabled" /></td>
        <td class="tagForm">Fecha de Entrega:</td>
        <td><input type="text" id="FechaEntregado" style="width:75px;" value="<?=formatFechaDMA($field['FechaEntregado'])?>" disabled /></td>
        <td class="divFormCaption" colspan="2" style="height:20px;">Inf. Adicional</td>
    </tr>
    <tr>
        <td class="tagForm">Monto Pago</td>
        <td><input type="text" id="MontoPago" style="width:125px; text-align:right; font-weight:bold; font-size:14px;" value="<?=number_format($field['MontoPago'], 2, ',', '.')?>" disabled="disabled" /></td>
        <td class="tagForm">De Cobro:</td>
        <td><input type="text" id="FlagCobrado" style="width:75px;" value="<?=printValores("ESTADO-CHEQUE-COBRO", $field['FlagCobrado'])?>" disabled="disabled" /></td>
        <td class="tagForm">&nbsp;</td>
        <td>
            <input type="checkbox" id="flagnonegociable" <?=$flagnonegociable?> disabled="disabled" /> Cheque No Negociable
        </td>
    </tr>
    
    <tr>
		<td class="tagForm">* Generado Por:</td>
		<td class="gallery clearfix">
        	<input type="hidden" id="GeneradoPor" value="<?=$field['GeneradoPor']?>" />
        	<input type="text" id="CodGeneradoPor" value="<?=$field['CodGeneradoPor']?>" disabled="disabled" style="width:60px;" />
			<input type="text" id="NomGeneradoPor" value="<?=($field['NomGeneradoPor'])?>" disabled="disabled" style="width:250px;" />
			<a href="../lib/listas/listado_empleados.php?filtrar=default&cod=CodGeneradoPor&nom=NomGeneradoPor&campo3=GeneradoPor&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]" style=" <?=$display_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
        <td class="tagForm">&nbsp;</td>
        <td class="tagForm">&nbsp;</td>
	</tr>
    <tr>
		<td class="tagForm">* Conformado Por:</td>
		<td class="gallery clearfix">
        	<input type="hidden" id="ConformadoPor" value="<?=$field['ConformadoPor']?>" />
        	<input type="text" id="CodConformadoPor" value="<?=$field['CodConformadoPor']?>" disabled="disabled" style="width:60px;" />
			<input type="text" id="NomConformadoPor" value="<?=($field['NomConformadoPor'])?>" disabled="disabled" style="width:250px;" />
			<a href="../lib/listas/listado_empleados.php?filtrar=default&cod=CodConformadoPor&nom=NomConformadoPor&campo3=ConformadoPor&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]" style=" <?=$display_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
        <td class="tagForm">&nbsp;</td>
        <td class="tagForm">&nbsp;</td>
	</tr>
    <tr>
		<td class="tagForm">* Aprobado Por:</td>
		<td class="gallery clearfix">
        	<input type="hidden" id="AprobadoPor" value="<?=$field['AprobadoPor']?>" />
        	<input type="text" id="CodAprobadoPor" value="<?=$field['CodAprobadoPor']?>" disabled="disabled" style="width:60px;" />
			<input type="text" id="NomAprobadoPor" value="<?=($field['NomAprobadoPor'])?>" disabled="disabled" style="width:250px;" />
			<a href="../lib/listas/listado_empleados.php?filtrar=default&cod=CodAprobadoPor&nom=NomAprobadoPor&campo3=AprobadoPor&iframe=true&width=950&height=525" rel="prettyPhoto[iframe1]" style=" <?=$display_ver?>">
            	<img src="../imagenes/f_boton.png" width="20" title="Seleccionar" align="absbottom" style="cursor:pointer;" />
            </a>
        </td>
        <td class="tagForm">&nbsp;</td>
        <td class="tagForm">&nbsp;</td>
	</tr>
</table>

<table align="center" width="1000" class="tblForm">
    <tr>
        <td colspan="4" class="divFormCaption" style="height:20px;">Anulaci&oacute;n / Reemplazo</td>
    </tr>
    <tr>
        <td class="tagForm" width="125">Fecha:</td>
        <td><input type="text" id="FechaAnulacion" style="width:75px;" value="<?=formatFechaDMA($field['FechaAnulacion'])?>" disabled="disabled" /></td>
        <td class="tagForm" width="125">Voucher: </td>
        <td>
            <input type="text" id="PeriodoAnulacion" style="width:50px;" value="<?=$field['PeriodoAnulacion']?>" disabled="disabled" />-
            <input type="text" id="VoucherAnulacion" style="width:50px;" value="<?=$field['VoucherAnulacion']?>" disabled="disabled" />
        </td>
    </tr>
    <tr>
        <td class="tagForm">Anulado Por:</td>
        <td colspan="3"><input type="text" id="NomAnuladoPor" style="width:300px;" value="<?=($field['NomAnuladoPor'])?>" disabled="disabled" /></td>
    </tr>
    <tr>
        <td class="tagForm">Motivo:</td>
        <td colspan="3"><input type="text" id="MotivoAnulacion" style="width:300px;" value="<?=($field['MotivoAnulacion'])?>" <?=$disabled_anular?> /></td>
    </tr>
    <tr>
        <td class="tagForm">Reemplazado Por:</td>
        <td colspan="3"><input type="text" id="NomReemplazadoPor" style="width:300px;" value="<?=($field['NomReemplazadoPor'])?>" disabled="disabled" /></td>
    </tr>
    <tr>
        <td class="tagForm">&Uacute;ltima Modif.:</td>
        <td colspan="3">
            <input type="text" id="UltimoUsuario" value="<?=$field['UltimoUsuario']?>" size="30" disabled="disabled" />
            <input type="text" id="UltimaFecha" size="25" value="<?=$field['UltimaFecha']?>" disabled="disabled" />
        </td>
    </tr>
</table>
<center> 
<input type="submit" value="<?=$label_submit?>" style=" <?=$display_submit?>" />
<input type="button" value="Cancelar" onclick="this.form.submit();" />
</center>
</div>

<div id="tab2" style="display:none;">
<center>
<div style="overflow:scroll; width:1000px; height:300px;">
<table align="center" width="100%" class="tblLista">
	<thead>
    <tr>
        <th scope="col">Proveedor</th>
        <th scope="col" width="150">Documento</th>
        <th scope="col" width="100">Fecha</th>
        <th scope="col" width="100">Estado</th>
        <th scope="col" width="125">Monto Pagado</th>
        <th scope="col" width="125">Monto Retenci&oacute;n</th>
    </tr>
    </thead>
    
    <tbody>
    <?php
	$sql = "SELECT
				mp.NomCompleto As NomProveedor,
				o.CodTipoDocumento,
				o.NroDocumento,
				o.MontoObligacion,
				o.MontoImpuestoOtros,
				o.FechaRegistro,
				o.Estado
			FROM
				ap_pagos p
				INNER JOIN mastpersonas mp ON (p.CodProveedor = mp.CodPersona)
				INNER JOIN ap_ordenpago op ON (p.CodOrganismo = op.CodOrganismo AND
											   p.NroOrden = op.NroOrden) 
				INNER JOIN ap_obligaciones o ON (op.CodProveedor = o.CodProveedor AND
												 op.CodTipoDocumento = o.CodTipoDocumento AND
												 op.NroDocumento = o.NroDocumento)
			WHERE
				p.NroProceso = '".$NroProceso."' AND
				p.Secuencia = '".$Secuencia."'";
	$query_obligacion = mysql_query($sql) or die($sql.mysql_error());
	while($field_obligacion = mysql_fetch_array($query_obligacion)) {
		?>
        <tr class="trListaBody">
        	<td><?=($field_obligacion['NomProveedor'])?></td>
        	<td align="center"><?=$field_obligacion['CodTipoDocumento']?>-<?=$field_obligacion['NroDocumento']?></td>
        	<td align="center"><?=formatFechaDMA($field_obligacion['FechaRegistro'])?></td>
            <td align="center"><?=printValores("ESTADO-OBLIGACIONES", $field_obligacion['Estado'])?></td>
        	<td align="right"><strong><?=number_format($field_obligacion['MontoObligacion'], 2, ',', '.')?></strong></td>
        	<td align="right"><strong><?=number_format($field_obligacion['MontoImpuestoOtros'], 2, ',', '.')?></strong></td>
        </tr>
        <?
	}
	?>
    </tbody>
</table>
</div>
</center>
</div>
</form>