<?php
$Ahora = ahora();
list($NroProceso, $Secuencia) = split("[.]", $registro);
//	consulto datos generales
$sql = "SELECT
			p.NroProceso,
			p.CodTipoPago,
			p.NroCuenta,
			p.FechaPago,
			SUM(p.MontoPago) AS MontoPago,
			tp.TipoPago,
			cb.CodCuenta,
			cb.Descripcion AS NomCuentaBanco,
			pc.Descripcion AS NomCuentaContable,
			cbb.SaldoActual
		FROM
			ap_pagos p
			INNER JOIN masttipopago tp ON (p.CodTipoPago = tp.CodTipoPago)
			INNER JOIN ap_ctabancaria cb ON (p.NroCuenta = cb.NroCuenta)
			INNER JOIN ap_ctabancariabalance cbb ON (cb.NroCuenta = cbb.NroCuenta)
			INNER JOIN ac_mastplancuenta pc ON (cb.CodCuenta = pc.CodCuenta)
		WHERE
			p.NroProceso = '".$NroProceso."' AND
			p.Secuencia = '".$Secuencia."'
		GROUP BY NroProceso, Secuencia";
$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
if (mysql_num_rows($query)) $field = mysql_fetch_array($query);
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Impresi&oacute;n/Transferencia de Pagos</td>
		<td align="right"><a class="cerrar" href="#" onclick="document.getElementById('frmentrada').submit()">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=ap_orden_pago_transferir_lista" method="POST" onsubmit="return transferir(this, '<?=$accion?>');">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="fCodOrganismo" id="fCodOrganismo" value="<?=$fCodOrganismo?>" />
<input type="hidden" name="fPeriodo" id="fPeriodo" value="<?=$fPeriodo?>" />
<input type="hidden" name="Secuencia" id="Secuencia" value="<?=$Secuencia?>" />

<table width="1050" class="tblForm">
	<tr>
    	<td colspan="2" class="divFormCaption">Informaci&oacute;n del Pre-Pago</td>
    </tr>
	<tr>
		<td class="tagForm" width="150">Fecha de Pago:</td>
		<td><input type="text" id="FechaPago" value="<?=formatFechaDMA(substr($Ahora, 0, 10))?>" style="width:60px;" class="datepicker" onkeyup="setFechaDMA(this);" /></td>
	</tr>
	<tr>
		<td class="tagForm">Nro. Pre-Pago:</td>
		<td><input type="text" id="NroProceso" style="width:60px;" class="disabled" value="<?=$NroProceso?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="tagForm">Tipo de Pago:</td>
		<td>
        	<input type="hidden" id="CodTipoPago" value="<?=$field['CodTipoPago']?>" />
        	<input type="text" style="width:150px;" class="disabled" value="<?=($field['TipoPago'])?>" disabled="disabled" /> 
            Monto a Pagar: 
            <input type="text" id="MontoPago" style="width:125px; text-align:right;" class="codigo" value="<?=number_format($field['MontoPago'], 2, ',', '.')?>" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Cta. Bancaria:</td>
		<td>
        	<input type="text" id="NroCuenta" style="width:150px;" class="disabled" value="<?=$field['NroCuenta']?>" disabled="disabled" />
        	<input type="text" style="width:350px;" class="disabled" value="<?=($field['NomCuentaBanco'])?>" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td class="tagForm">Cta. Contable:</td>
		<td>
        	<input type="text" id="CodCuenta" style="width:150px;" class="disabled" value="<?=$field['CodCuenta']?>" disabled="disabled" />
        	<input type="text" style="width:350px;" class="disabled" value="<?=($field['NomCuentaContable'])?>" disabled="disabled" />
		</td>
	</tr>
</table>
<center> 
<input type="submit" value="Aceptar" />
<input type="button" value="Cancelar" onclick="this.form.submit();" />
</center>
</form>
<br />

<center>
<div style="width:1050px" class="divFormCaption">Documentos a Pagar del Pre-Pago</div>
<form name="frm_detalles" id="frm_detalles">
<div style="overflow:scroll; width:1050px; height:150px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
		<th scope="col" width="25">#</th>
		<th scope="col">Pagar A</th>
		<th scope="col" width="125">Doc. Fiscal</th>
		<th scope="col" width="150">Nro. Documento</th>
		<th scope="col" width="75">Fecha</th>
		<th scope="col" width="75"># Registro</th>
		<th scope="col" width="125">Neto a Pagar</th>
	</tr>
    </thead>
    
    <tbody id="lista_detalles">
    <?php
	$sql = "SELECT
				p.Secuencia,
				p.NomProveedorPagar,
				p.MontoPago,
				p.FechaPago,
				mp.DocFiscal,
				op.CodTipoDocumento,
				op.NroDocumento,
				op.NroRegistro
			FROM
				ap_pagos p
				INNER JOIN ap_ordenpago op ON (p.CodOrganismo = op.CodOrganismo AND
											   p.NroOrden = op.NroOrden)
				INNER JOIN mastpersonas mp ON (p.CodProveedor = mp.CodPersona)
			WHERE
				p.NroProceso = '".$NroProceso."' AND
				p.Secuencia = '".$Secuencia."'
			ORDER BY Secuencia";
	$query_detalles = mysql_query($sql) or die ($sql.mysql_error());
	while ($field_detalles = mysql_fetch_array($query_detalles)) {
		?>
		<tr class="trListaBody">
			<th align="center"><?=$field_detalles['Secuencia']?></th>
			<td><?=($field_detalles['NomProveedorPagar'])?></td>
			<td><?=$field_detalles['DocFiscal']?></td>
			<td align="center"><?=$field_detalles['CodTipoDocumento']?>-<?=$field_detalles['NroDocumento']?></td>
			<td align="center"><?=formatFechaDMA($field_detalles['FechaPago'])?></td>
			<td align="center"><?=$field_detalles['NroRegistro']?></td>
			<td align="right"><strong><?=number_format($field_detalles['MontoPago'], 2, ',', '.')?></strong></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
</form>
</center>
