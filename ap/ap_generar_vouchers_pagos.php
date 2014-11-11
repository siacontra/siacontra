<?php
if ($filtrar == "default") {
	$faplicacion = "AP";
	$fsistema_fuente = $_SESSION["SISTEMA_FUENTE"];
	$forganismo = $_SESSION["FILTRO_ORGANISMO_ACTUAL"];
	$fperiodo = date("Y-m");
}
$filtro = "";
if ($forganismo != "") $filtro .= " AND (p.CodOrganismo = '".$forganismo."')";
if ($fperiodo != "") $filtro .= " AND (p.Periodo = '".$fperiodo."')";
?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Generaci&oacute;n de Vouchers de Pagos</td>
		<td align="right"><a class="cerrar"; href="framemain.php">[Cerrar]</a></td>
	</tr>
</table><hr width="100%" color="#333333" />

<form name="frmentrada" id="frmentrada" action="gehen.php?anz=ap_generar_vouchers_pagos" method="post">
<div class="divBorder" style="width:1000px;">
<table width="1000" class="tblFiltro">
	<tr>
		<td width="125" align="right">Aplicaci&oacute;n:</td>
		<td>
        	<input type="checkbox" style="visibility:hidden;" />
        	<input type="text" name="faplicacion" id="faplicacion" value="<?=$faplicacion?>" style="width:25px;" readonly="readonly" />
        </td>
		<td width="125" align="right">Sistema Fuente:</td>
		<td>
        	<input type="checkbox" style="visibility:hidden;" />
        	<input type="text" name="fsistema_fuente" id="fsistema_fuente" value="<?=$fsistema_fuente?>" style="width:75px;" readonly="readonly" />
        </td>
	</tr>
	<tr>
		<td align="right">Organismo:</td>
		<td>
			<input type="checkbox" checked="checked" onclick="this.checked=!this.checked" />
			<select name="forganismo" id="forganismo" style="width:300px;">
				<?=getOrganismos($forganismo, 3);?>
			</select>
		</td>
		<td align="right">Periodo:</td>
		<td>
			<input type="checkbox" checked="checked" onclick="this.checked=!this.checked" />
			<input type="text" name="fperiodo" id="fperiodo" maxlength="7" style="width:60px;" value="<?=$fperiodo?>" />
		</td>
	</tr>
</table>
</div>
<center><input type="submit" name="btBuscar" value="Buscar"></center><br />
</form>

<form name="frm_detalle" id="frm_detalle" method="post" action="gehen.php?anz=ap_generar_vouchers_pagos">
<table width="1000" class="tblBotones">
	<tr>
		<td><div id="rows"></div></td>
		<td align="right">
			<input type="button" id="btVer" value="Ver Pago" disabled="disabled" />
			<input type="button" id="btGenerar" value="Generar Voucher" onclick="generar_vouchers_abrir(this.form.registro.value, 'ap_generar_vouchers_pagos_voucher');" />
		</td>
	</tr>
</table>

<input type="hidden" name="registro" id="registro" />
<center>
<div style="overflow:scroll; width:1000px; height:400px;">
<table width="100%" class="tblLista">
	<thead>
	<tr class="trListaHead">
		<th scope="col" width="125">Cta. Bancaria</th>
		<th scope="col" width="125">Cheque</th>
		<th scope="col">Proveedor</th>
		<th scope="col" width="125">Monto</th>
		<th scope="col" width="75">Fecha de Pago</th>
		<th scope="col" width="125">Tipo de Pago</th>
	</tr>
    </thead>
    
    <tbody>
	<?php
	//	CONSULTO LA TABLA
	$sql = "SELECT
				p.NroProceso,
				p.CodTipoPago,
				p.Secuencia,
				p.NroCuenta,
				p.NroPago,
				p.MontoPago,
				p.FechaPago,				
				mp.NomCompleto AS NomProveedor,
				tp.TipoPago
			FROM
				ap_pagos p
				INNER JOIN mastpersonas mp ON (p.CodProveedor = mp.CodPersona)
				INNER JOIN masttipopago tp ON (p.CodTipoPago = tp.CodTipoPago)
			WHERE
				p.Estado = 'IM' AND
				p.FlagContabilizacionPendiente = 'S'
				$filtro
			ORDER BY CodOrganismo, NroCuenta, NroPago";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {
		$id = "$field[NroProceso].$field[Secuencia]";
		?>
		<tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?=$id?>">
			<td align="center"><?=$field['NroCuenta']?></td>
			<td align="center"><?=$field['NroPago']?></td>
			<td><?=($field['NomProveedor'])?></td>
			<td align="right"><strong><?=number_format($field['MontoPago'], 2, ',', '.')?></strong></td>
			<td align="center"><?=formatFechaDMA($field['FechaPago'])?></td>
			<td><?=($field['TipoPago'])?></td>
		</tr>
		<?
	}
	?>
    </tbody>
</table>
</div>
</center>
</form>