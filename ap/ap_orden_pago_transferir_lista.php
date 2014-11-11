<?php
$Ahora = ahora();
if ($filtrar == "default") {
	$fCodOrganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$maxlimit = $_SESSION["MAXLIMIT"];
}
if ($fCodOrganismo != "") { $cCodOrganismo = "checked"; $filtro.=" AND (p.CodOrganismo = '".$fCodOrganismo."')"; } else $dCodOrganismo = "disabled";
if ($fPeriodo != "") { $cPeriodo = "checked"; $filtro.=" AND (p.Periodo = '".$fPeriodo."')"; } else $dPeriodo = "disabled";
//	------------------------------------
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Imprimir/Transferir Pagos</td>
		<td align="right"><a class="cerrar" href="framemain.php">[cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=ap_orden_pago_transferir_lista" method="post">
<input type="hidden" name="concepto" id="concepto" value="<?=$concepto?>" />
<input type="hidden" name="registro" id="registro" />
<div class="divBorder" style="width:1050px;">
<table width="1050" class="tblFiltro">
	<tr>
		<td align="right" width="125">Organismo:</td>
		<td>
			<input type="checkbox" <?=$cCodOrganismo?> onclick="this.checked=!this.checked" />
			<select name="fCodOrganismo" id="fCodOrganismo" style="width:300px;" <?=$dCodOrganismo?>>
				<?=getOrganismos($fCodOrganismo, 3)?>
			</select>
		</td>
		<td align="right">Periodo:</td>
		<td>
			<input type="checkbox" <?=$cPeriodo?> onclick="chkFiltro(this.checked, 'fPeriodo');" />
			<input type="text" name="fPeriodo" id="fPeriodo" value="<?=$fPeriodo?>" maxlength="7" style="width:100px;" <?=$dPeriodo?> />
		</td>
	</tr>
</table>
</div>
<center><input type="submit" value="Buscar"></center><br />

<center>
<table width="1050" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">
			<input type="button" id="btTransferir" value="Imprimir/Transferir" style="width:125px;" onclick="cargarOpcion2(this.form, 'gehen.php?anz=ap_orden_pago_transferir_form&accion=transferir', 'SELF', '', $('#registro').val());" />
		</td>
	</tr>
</table>

<div style="overflow:scroll; width:1050px; height:200px;">
<table width="100%" class="tblLista">
	<thead>    
		<th scope="col" width="75">Prepago</th>
		<th scope="col" align="left">Banco</th>
		<th scope="col" width="120">Cuenta Bancaria</th>
		<th scope="col" width="120">Tipo Pago</th>
		<th scope="col" width="120">Monto Total</th>
		<th scope="col" width="75">Fecha Pago</th>
		<th scope="col" width="70"># Orden de Pago</th>
		<th scope="col" width="50"># Pagos</th>
		<th scope="col" width="25">Dif.</th>
    </thead>
    
    <tbody>
	<?php
	//	consulto todos
	$sql = "SELECT
				p.NroProceso,
				p.Secuencia,
				p.NroCuenta,
				SUM(p.MontoPago) AS MontoPago,
				p.FechaPago,
				p.CodTipoPago,
				p.CodProveedor,
				tp.TipoPago,
				b.Banco, p.NroOrden,
				(SELECT COUNT(*)
				 FROM ap_pagos
				 WHERE
				 	CodProveedor = p.CodProveedor AND
				 	NroProceso = p.NroProceso AND
				 	NroCuenta = p.NroCuenta AND
				 	CodTipoPago = p.CodTipoPago) AS NroPagos
			FROM
				ap_pagos p
				INNER JOIN masttipopago tp ON (p.CodTipoPago = tp.CodTipoPago)
				INNER JOIN ap_ctabancaria cb ON (p.NroCuenta = cb.NroCuenta)
				INNER JOIN mastbancos b ON (cb.CodBanco = b.CodBanco)
			WHERE
				p.Estado = 'GE'
				$filtro
			GROUP BY CodProveedor, NroProceso, CodTipoPago, NroCuenta
			ORDER BY NroProceso, CodTipoPago, NroCuenta";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	while ($field = mysql_fetch_array($query)) {
		$id = $field['NroProceso'].".".$field['Secuencia'];
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro'); appendAjax('accion=documentos_prepago&registro=<?=$id?>', $('#lista_detalles'))" id="<?=$id?>">
			<td align="center"><?=$field['NroProceso']?></td>
			<td><?=($field['Banco'])?></td>
			<td align="center"><?=$field['NroCuenta']?></td>
			<td align="center"><?=($field['TipoPago'])?></td>
			<td align="right"><strong><?=number_format($field['MontoPago'], 2, ',', '.')?></strong></td>
			<td align="center"><?=formatFechaDMA($field['FechaPago'])?></td>
			<td align="center"><?=$field['NroOrden']?></td>
			<td align="center"><?=$field['NroPagos']?></td>
			<td align="center"><?=printFlag($field['FlagPagoDiferido'])?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
</center>
</form>

<center>
<div style="width:1050px" class="divFormCaption">Documentos a Pagar del Pre-Pago</div>
<form name="frm_detalles" id="frm_detalles">
<table width="1050" class="tblBotones">
	<tr>
		<td><div id="rows_detalles"></div></td>
		<td align="right">
			<input type="button" class="btLista" value="Ver" disabled="disabled" />
		</td>
	</tr>
</table>
<div style="overflow:scroll; width:1050px; height:150px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
		<th scope="col" width="25">#</th>
		<th scope="col">Pagar A</th>
		<th scope="col" width="300">Proveedor</th>
		<th scope="col" width="125">Monto a Pagar</th>
		<th scope="col" width="125">Monto Retenido</th>
		<th scope="col" width="125">Neto a Pagar</th>
	</tr>
    </thead>
    
    <tbody id="lista_detalles">
    	
    </tbody>
</table>
</div>
</form>
</center>
