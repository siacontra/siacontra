<?php
if ($filtrar == "default") {
	$forganismo == $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$fperiodo = date("Y-m");
}
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Generaci&oacute;n de Vouchers para Transacciones Bancarias</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=ap_generar_vouchers_transacciones" method="post">
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td align="right" width="125">Organismo:</td>
		<td>
			<input type="checkbox" checked="checked" onclick="this.checked=!this.checked" />
			<select name="forganismo" id="forganismo" style="width:300px;">
				<?=getOrganismos($forganismo, 3);?>
			</select>
		</td>
		<td align="right" width="125">Periodo:</td>
		<td>
			<input type="checkbox" checked="checked" onclick="this.checked=!this.checked" />
			<input type="text" name="fperiodo" id="fperiodo" maxlength="7" style="width:60px;" value="<?=$fperiodo?>" />
		</td>
	</tr>
</table>
</div>
<center><input type="submit" name="btBuscar" value="Buscar"></center><br />
</form>

<form name="frm_detalle" id="frm_detalle" method="post" action="gehen.php?anz=ap_generar_vouchers_transacciones">
<table width="1000" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">
			<input type="button" id="btVer" value="Ver Pago" disabled="disabled" />
			<input type="button" id="btGenerar" value="Generar Voucher" onclick="generar_vouchers_abrir(this.form.registro.value, 'ap_generar_vouchers_transacciones_voucher');" />
		</td>
	</tr>
</table>

<input type="hidden" name="registro" id="registro" />
<center>
<div style="overflow:scroll; width:1000px; height:300px;">
<table width="1100" class="tblLista">
	<thead>
	<tr class="trListaHead">
		<th scope="col" width="60">N&uacute;mero</th>
		<th scope="col" width="75">Fecha</th>
		<th scope="col">Comentario</th>
		<th scope="col" width="125">Doc. Referencia Banco</th>
		<th scope="col" width="125">Nro. Documento</th>
		<th scope="col" width="75">Estado</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	CONSULTO LA TABLA
	if ($forganismo != "") { $filtro .= "AND (bt.CodOrganismo = '".$forganismo."')"; }
	if ($fperiodo != "") { $filtro .= "AND (bt.PeriodoContable = '".$fperiodo."')"; }
	$sql = "SELECT
				bt.*,
				btt.CodVoucher
			FROM
				ap_bancotransaccion bt
				INNER JOIN ap_bancotipotransaccion btt ON (bt.CodTipoTransaccion = btt.CodTipoTransaccion)
			WHERE
				bt.Estado = 'AP' AND
				bt.FlagGeneraVoucher = 'S'
				$filtro
			GROUP BY NroTransaccion
			ORDER BY NroTransaccion, Secuencia";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[NroTransaccion].$field[Secuencia].$field[CodVoucher]";
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<td align="center"><?=$field['NroTransaccion']?></td>
			<td align="center"><?=formatFechaDMA($field['FechaTransaccion'])?></td>
			<td><?=($field['Comentarios'])?></td>
			<td align="center"><?=$field['CodigoReferenciaBanco']?></td>
			<td align="center"><?=$field['CodigoReferenciaInterno']?></td>
			<td align="center"><?=printValores("ESTADO-BANCARIO", $field['Estado'])?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div><br />

<div style="overflow:scroll; width:1000px; height:200px;">
<table width="100%" class="tblLista">
	<thead>
	<tr class="trListaHead">
		<th scope="col" width="25">Vou.</th>
		<th scope="col" width="125">Cta. Bancaria</th>
		<th scope="col" width="100">Cuenta</th>
		<th scope="col">Tipo Transacci&oacute;n</th>
		<th scope="col" width="25">Tipo</th>
		<th scope="col" width="100">Cta. Transacci&oacute;n</th>
		<th scope="col" width="125">Monto</th>
		<th scope="col" width="75">C&oacute;digo Interno</th>
		<th scope="col" width="75">Doc. Ref. Banco</th>
		<th scope="col" width="75">C.Costo</th>
		<th scope="col" width="75">Persona</th>
	</tr>
    </thead>
    
    <tbody id="lista_detalles">
    </tbody>
</table>
</div>
</center>
</form>