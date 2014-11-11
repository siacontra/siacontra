<?php
include("fphp.php");
include("ac_fphp.php");
extract($_POST);
extract($_GET);

//	voucher
//	----------------
if ($accion == "voucher_insertar_linea") {
	?>
    <td align="center"><?=$nrodetalle?></td>
    <td align="center">
    	<input type="text" name="codcuenta" id="codcuenta_<?=$nrodetalle?>" class="cell2" style="text-align:center;" readonly />
    	<input type="hidden" name="nomcuenta" id="nomcuenta_<?=$nrodetalle?>" />
	</td>
    <td align="center">
    	<input type="text" name="codpersona" id="codpersona_<?=$nrodetalle?>" class="cell2" style="text-align:center;" readonly />
    	<input type="hidden" name="nompersona" id="nompersona_<?=$nrodetalle?>" />
	</td>
    <td align="center">
    	<input type="text" name="documento" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" />
	</td>
    <td align="center">
    	<input type="text" name="fecha" style="text-align:center;" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" />
	</td>
    <td align="center">
    	<input type="text" name="monto" value="0,00" class="cell" style="text-align:right; font-weight:bold;" onBlur="numeroBlur(this); this.className='cell'; setNegativo(this); sumar_voucher();" onFocus="numeroFocus(this); this.className='cellFocus';" />
	</td>
    <td align="center">
    	<input type="text" name="codccosto" id="codccosto_<?=$nrodetalle?>" class="cell2" style="text-align:center;" readonly />
    	<input type="hidden" name="nomccosto" id="nomccosto_<?=$nrodetalle?>" />
	</td>
    <td align="center">
    	<input type="text" name="descripcion" class="cell" onBlur="this.className='cell';" onFocus="this.className='cellFocus';" />
	</td>
    <?
}
//	----------------
elseif ($accion == "voucher_validar_modificar") {
	list($organismo, $periodo, $voucher) = split("[ ]", $codigo);
	$sql = "SELECT Estado
			FROM ac_vouchermast
			WHERE
				CodOrganismo = '".$organismo."' AND
				Periodo = '".$periodo."' AND
				Voucher = '".$voucher."'";
	$query = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] == "AP") echo "¡ERROR: No se puede modificar un registro en estado 'Aprobado'!";
		if ($field['Estado'] == "RE") echo "¡ERROR: No se puede modificar un registro en estado 'Rechazado'!";
		elseif ($field['Estado'] == "MA") echo "¡ERROR: No se puede modificar un registro en estado 'Mayorizado'!";
		elseif ($field['Estado'] == "AN") echo "¡ERROR: No se puede modificar un registro en estado 'Anulado'!";
	} else echo "¡ERROR: No se encontró el registro!";
	
}
//	----------------
elseif ($accion == "voucher_validar_anular") {
	list($organismo, $periodo, $voucher) = split("[ ]", $codigo);
	$sql = "SELECT Estado
			FROM ac_vouchermast
			WHERE
				CodOrganismo = '".$organismo."' AND
				Periodo = '".$periodo."' AND
				Voucher = '".$voucher."'";
	$query = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		if ($field['Estado'] == "AN") echo "¡ERROR: No puede anular un voucher en estado 'Anulado'!";
	} else echo "¡ERROR: No se encontró el registro!";
	
}
//	----------------
elseif ($accion == "voucher_aprobacion_masiva") {
	$lineas = split(";", $codigo);
	foreach ($lineas as $registro) {
		list($organismo, $periodo, $voucher) = split("[ ]", $registro);
		
		$sql = "UPDATE ac_vouchermast
				SET
					Estado = 'AP',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$organismo."' AND
					Periodo = '".$periodo."' AND
					Voucher = '".$voucher."'";
		$query = mysql_query($sql) or die($sql.mysql_error());
	}
}
//	----------------

//	saldo de los detalles de la cuenta
elseif ($accion == "consultas_saldo_cuenta_detalle") {
	//	CONSULTO LA TABLA
	$sql = "SELECT
				vb.CodCuenta,
				vb.SaldoBalance,
				pc.Descripcion
			FROM
				ac_voucherbalance vb
				INNER JOIN ac_mastplancuenta pc ON (vb.CodCuenta = pc.CodCuenta)
			WHERE
				vb.CodOrganismo = '".$codorganismo."' AND
				vb.Periodo = '".$periodo."' AND
				vb.CodCuenta <> '".$codcuenta."' AND
				vb.CodCuenta LIKE '".$codcuenta."%'
			ORDER BY CodCuenta";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);
	//	MUESTRO LA TABLA
	while ($field = mysql_fetch_array($query)) {		
		?>
		<tr class="trListaBody" onclick="mClk(this, 'detalle');" id="<?=$field['CodCuenta']?>">
			<td align="center"><?=$field['CodCuenta']?></td>
			<td><?=($field['Descripcion'])?></td>
			<td align="right"><?=number_format($field['SaldoBalance'], 2, ',', '.')?></td>
			<td align="right"><?=number_format($field['SaldoBalance'], 2, ',', '.')?></td>
		</tr>
		<?
	}
}
//	----------------
?>