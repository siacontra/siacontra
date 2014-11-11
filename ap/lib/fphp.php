<?php
session_start();
set_time_limit(-1);
ini_set('memory_limit','128M');

//	FUNCION PARA CARGAR SELECTS 
function loadSelectValores($tabla, $codigo, $opt) {
	switch ($tabla) {
		case "ESTADO-BANCARIO":
			$c[0] = "PR"; $v[0] = "Pendiente";
			$c[1] = "AP"; $v[1] = "Actualizado";
			$c[2] = "CO"; $v[2] = "Contabilizado";
			break;
			
		case "ESTADO-OBLIGACIONES":
			$c[0] = "PR"; $v[0] = "En Preparacion";
			$c[1] = "RV"; $v[1] = "Revisada";
			$c[2] = "AP"; $v[2] = "Aprobada";
			$c[3] = "AN"; $v[3] = "Anulada";
			$c[4] = "PA"; $v[4] = "Pagada";
			$c[5] = "CO"; $v[5] = "Conformada";
			break;
		
		case "ESTADO-ORDEN-PAGO":
			$c[0] = "PE"; $v[0] = "Pendiente";
			$c[1] = "GE"; $v[1] = "Generada";
			$c[2] = "PA"; $v[2] = "Pagada";
			$c[3] = "AN"; $v[3] = "Anulada";
			break;
			
		case "ORDENAR-ORDEN-PAGO":
			$c[0] = "NroOrden"; $v[0] = "Nro. Orden";
			$c[1] = "NomProveedorPagar, CodTipoDocumento, NroDocumento"; $v[1] = "Proveedor/Documento";
			$c[2] = "MontoTotal"; $v[2] = "Monto a Pagar";
			$c[3] = "FechaProgramada"; $v[3] = "Fecha Prog. Pago";
			break;
			
		case "ESTADO-PAGO":
			$c[0] = "GE"; $v[0] = "Generado";
			$c[1] = "IM"; $v[1] = "Impreso";
			break;
			
		case "ESTADO-PAGO2":
			$c[0] = "IM"; $v[0] = "Impreso";
			$c[1] = "AN"; $v[1] = "Anulado";
			break;
			
		case "ESTADO-PAGO3":
			$c[0] = "GE"; $v[0] = "Generado";
			$c[1] = "IM"; $v[1] = "Impreso";
			$c[2] = "AN"; $v[2] = "Anulado";
			break;
				
		case "ORDENAR-LIBRO-CHEQUE":
			$c[0] = "pa.NroPago"; $v[0] = "Nro. Cheque";
			$c[1] = "pa.FechaPago"; $v[1] = "Fecha de Pago";
			$c[2] = "pa.VoucherPago"; $v[2] = "Voucher de Pago";
			break;
			
		case "ESTADO-ENTREGA-CHEQUE":
			$c[0] = "C"; $v[0] = "Custodia";
			$c[1] = "E"; $v[1] = "Entregado";
			break;
			
		case "ESTADO-TRANSACCION-BANCARIA":
			$c[0] = "PR"; $v[0] = "Pendiente";
			$c[1] = "AP"; $v[1] = "Actualizada";
			$c[2] = "CO"; $v[2] = "Contabilizada";
			break;
			
		case "SISTEMA-FUENTE-REGISTRO-COMPRA":
			$c[0] = "CP"; $v[0] = "Cuentas x Pagar";
			$c[1] = "CC"; $v[1] = "Caja Chica";
			break;
			
		case "ORDENAR-REGISTRO-COMPRA":
			$c[0] = "Fecha"; $v[0] = "Fecha";
			$c[1] = "Proveedor"; $v[1] = "Nombre o Razón o Social";
			break;
			
		case "CLASIFICACION-CXP":
			$c[0] = "O"; $v[0] = "Obligaciones";
			$c[1] = "C"; $v[1] = "Otros de Ctas. Por Pagar";
			$c[2] = "P"; $v[2] = "Préstamos";
			$c[3] = "E"; $v[3] = "Otros Externos";
			break;
			
		case "ESTADO-CAJACHICA":
			$c[0] = "PR"; $v[0] = "En Preparación";
			$c[1] = "AP"; $v[1] = "Aprobado";
			$c[2] = "AN"; $v[2] = "Anulado";
			break;
			
		case "IMPUESTO-PROVISION":
			$c[0] = "N"; $v[0] = "Provisión del Documento";
			$c[1] = "P"; $v[1] = "Pago del Documento";
			break;
			
		case "IMPUESTO-IMPONIBLE":
			$c[0] = "N"; $v[0] = "Monto Afecto";
			$c[1] = "I"; $v[1] = "IGV/IVA";
			break;
			
		case "IMPUESTO-COMPROBANTE":
			$c[0] = "IVA"; $v[0] = "IVA";
			$c[1] = "ISLR"; $v[1] = "ISLR";
			$c[2] = "1X1000"; $v[2] = "1X1000";
			break;
	}
	
	$i = 0;
	switch ($opt) {
		case 0:
			foreach ($c as $cod) {
				if ($cod == $codigo) echo "<option value='".$cod."' selected>".$v[$i]."</option>";
				else echo "<option value='".$cod."'>".$v[$i]."</option>";
				$i++;
			}
			break;
			
		case 1:
			foreach ($c as $cod) {
				if ($cod == $codigo) echo "<option value='".$cod."' selected>".$v[$i]."</option>";
				$i++;
			}
			break;
	}
}

//	FUNCION PARA IMPRIMIR EN UNA TABLA VALORES
function printValores($tabla, $codigo) {
	switch ($tabla) {
		case "ESTADO-BANCARIO":
			$c[0] = "PR"; $v[0] = "Pendiente";
			$c[1] = "AP"; $v[1] = "Actualizado";
			$c[2] = "CO"; $v[2] = "Contabilizado";
			break;
			
		case "ESTADO-OBLIGACIONES":
			$c[0] = "PR"; $v[0] = "En Preparacion";
			$c[1] = "RV"; $v[1] = "Revisada";
			$c[2] = "AP"; $v[2] = "Aprobada";
			$c[3] = "AN"; $v[3] = "Anulada";
			$c[4] = "PA"; $v[4] = "Pagada";
			$c[5] = "CO"; $v[5] = "Conformada";
			break;
		
		case "ESTADO-ORDEN-PAGO":
			$c[0] = "PE"; $v[0] = "Pendiente";
			$c[1] = "GE"; $v[1] = "Generada";
			$c[2] = "PA"; $v[2] = "Pagada";
			$c[3] = "AN"; $v[3] = "Anulada";
			break;
			
		case "ESTADO-PAGO":
			$c[0] = "GE"; $v[0] = "Generado";
			$c[1] = "IM"; $v[1] = "Impreso";
			$c[2] = "AN"; $v[2] = "Anulado";
			break;
		
		case "ESTADO-CHEQUE":
			$c[0] = "C"; $v[0] = "Custodia";
			$c[1] = "E"; $v[1] = "Entregado";
			break;
		
		case "ESTADO-CHEQUE-COBRO":
			$c[0] = "S"; $v[0] = "Cobrado";
			$c[1] = "N"; $v[1] = "Pendiente";
			break;
		
		case "ORIGEN-PAGO":
			$c[0] = "A"; $v[0] = "Automatico";
			break;
			
		case "SISTEMA-FUENTE-REGISTRO-COMPRA":
			$c[0] = "CP"; $v[0] = "Cuentas x Pagar";
			$c[1] = "CC"; $v[1] = "Caja Chica";
			break;
			
		case "CLASIFICACION-CXP":
			$c[0] = "O"; $v[0] = "Obligaciones";
			$c[1] = "C"; $v[1] = "Otros de Ctas. Por Pagar";
			$c[2] = "P"; $v[2] = "Préstamos";
			$c[3] = "E"; $v[3] = "Otros Externos";
			break;
			
		case "ESTADO-CAJACHICA":
			$c[0] = "PR"; $v[0] = "En Preparación";
			$c[1] = "AP"; $v[1] = "Aprobado";
			$c[2] = "AN"; $v[2] = "Anulado";
			break;
			
		case "IMPUESTO-PROVISION":
			$c[0] = "N"; $v[0] = "Provisión del Documento";
			$c[1] = "P"; $v[1] = "Pago del Documento";
			break;
			
		case "IMPUESTO-IMPONIBLE":
			$c[0] = "N"; $v[0] = "Monto Afecto";
			$c[1] = "I"; $v[1] = "IGV/IVA";
			break;
			
		case "IMPUESTO-COMPROBANTE":
			$c[0] = "IVA"; $v[0] = "IVA";
			$c[1] = "ISLR"; $v[1] = "ISLR";
			$c[2] = "1X1000"; $v[2] = "1X1000";
			break;
	}
	
	$i=0;
	foreach ($c as $cod) {
		if ($cod == $codigo) return $v[$i];
		$i++;
	}
}

//	FUNCION PARA CARGAR SELECTS
function loadSelectTipoDocumentoObligacion($codigo, $opt) {
	switch ($opt) {
		case 0:
			$sql = "SELECT CodTipoDocumento, Descripcion FROM ap_tipodocumento WHERE Clasificacion = 'O' AND Estado = 'A' ORDER BY CodTipoDocumento";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field = mysql_fetch_array($query)) {
				if ($field[0] == $codigo) { ?><option value="<?=$field[0]?>" selected="selected"><?=($field[1])?></option><? }
				else { ?><option value="<?=$field[0]?>"><?=($field[1])?></option><? }
			}
			break;
			
		case 1:
			$sql = "SELECT CodTipoDocumento, Descripcion FROM ap_tipodocumento WHERE Clasificacion = 'O' AND Estado = 'A' AND CodTipoDocumento = '$codigo'";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field = mysql_fetch_array($query)) {
				?><option value="<?=$field[0]?>" selected="selected"><?=($field[1])?></option><?
			}
			break;
			
		case 10:
			$sql = "SELECT CodTipoDocumento, Descripcion FROM ap_tipodocumento WHERE Clasificacion = 'O' AND Estado = 'A' ORDER BY CodTipoDocumento";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field = mysql_fetch_array($query)) {
				if ($field[0] == $codigo) { ?><option value="<?=$field[0]?>" selected="selected"><?=$field[0]?>&nbsp;&nbsp;&nbsp;<?=($field[1])?></option><? }
				else { ?><option value="<?=$field[0]?>"><?=$field[0]?>&nbsp;&nbsp;&nbsp;<?=($field[1])?></option><? }
			}
			break;
			
		case 11:
			$sql = "SELECT CodTipoDocumento, Descripcion FROM ap_tipodocumento WHERE Clasificacion = 'O' AND Estado = 'A' AND CodTipoDocumento = '$codigo'";
			$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field = mysql_fetch_array($query)) {
				?><option value="<?=$field[0]?>" selected="selected"><?=$field[0]?>&nbsp;&nbsp;&nbsp;<?=($field[1])?></option><?
			}
			break;
	}
}

//	OBTENGO EL ULTIMO NUMERO DE PAGO PARA LA CUENTA BANCARIA
function getNroOrdenPago($CodTipoPago, $NroCuenta) {
	$sql = "SELECT UltimoNumero
			FROM ap_ctabancariatipopago
			WHERE
				CodTipoPago = '".$CodTipoPago."' AND
				NroCuenta = '".$NroCuenta."'";
	$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	$codigo = (int) ($field['UltimoNumero'] + 1);
	$codigo = (string) str_repeat("0", 10-strlen($codigo)).$codigo;
	return $codigo;
}
?>
