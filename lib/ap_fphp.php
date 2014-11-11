<?php
session_start();
set_time_limit(-1);
ini_set('memory_limit','128M');

//	FUNCION PARA CARGAR SELECTS 
function loadSelectValores($tabla, $codigo, $opt) {
	switch ($tabla) {
		case "ESTADO":
			$c[0] = "A"; $v[0] = "Activo";
			$c[1] = "I"; $v[1] = "Inactivo";
			break;
			
		case "ESTADO-OBLIGACIONES-FILTRO":
			$c[0] = "PR"; $v[0] = "En Preparacion";
			$c[1] = "RV"; $v[1] = "Revisada";
			$c[2] = "AP"; $v[2] = "Aprobada";
			$c[3] = "AN"; $v[3] = "Anulada";
			$c[4] = "PA"; $v[4] = "Pagada";
			break;
			
		case "ORDENAR-REGISTRO-COMPRA":
			$c[0] = ""; $v[0] = "Fecha";
			break;
			
		case "ESTADO-PAGO":
			$c[0] = "GE"; $v[0] = "Generado";
			$c[1] = "IM"; $v[1] = "Impreso";
			break;
	}
	
	$i = 0;
	switch ($opt) {
		case 0:
			foreach ($c as $cod) {
				if ($cod == $codigo) echo "<option value='".$cod."' selected>".($v[$i])."</option>";
				else echo "<option value='".$cod."'>".($v[$i])."</option>";
				$i++;
			}
			break;
			
		case 1:
			foreach ($c as $cod) {
				if ($cod == $codigo) echo "<option value='".$cod."' selected>".($v[$i])."</option>";
				$i++;
			}
			break;
	}
}

//	FUNCION PARA IMPRIMIR EN UNA TABLA VALORES
function printValores($tabla, $codigo) {
	switch ($tabla) {
		case "ESTADO":
			$c[0] = "A"; $v[0] = "Activo";
			$c[1] = "I"; $v[1] = "Inactivo";
			break;
			
		case "ESTADO-BANCARIO":
			$c[0] = "PR"; $v[0] = "Pendiente";
			$c[1] = "AP"; $v[1] = "Actualizado";
			$c[2] = "CO"; $v[2] = "Contabilizado";
			break;
			
		case "ESTADO-PAGO":
			$c[0] = "GE"; $v[0] = "Generado";
			$c[1] = "IM"; $v[1] = "Impreso";
			break;
			
		case "ESTADO-ENTREGA":
			$c[0] = "C"; $v[0] = "Custodia";
			break;
			
		case "FLAG-PENDIENTE":
			$c[0] = "N"; $v[0] = "Si";
			$c[1] = "S"; $v[1] = "No";
			break;
			
		case "ESTADO-COBRO":
			$c[0] = "C"; $v[0] = "Custodia";
			break;
	}
	
	$i=0;
	foreach ($c as $cod) {
		if ($cod == $codigo) return ($v[$i]);
		$i++;
	}
}

//	FUNCION PARA IMPRIMIR EN UNA TABLA VALORES
function printValores2($tabla, $codigo) {
	switch ($tabla) {
		case "ESTADO-PAGO":
			$c[0] = "GE"; $v[0] = "Generado";
			$c[1] = "IM"; $v[1] = "Impreso";
			break;
	}
	
	$i=0;
	foreach ($c as $cod) {
		if ($cod == $codigo) return $v[$i];
		$i++;
	}
}

// obtiene la cuenta bancaria por defecto de un tipo de pago para un organismo
function getCuentaBancariaDefault($codorganismo, $codtipopago) {
	$sql = "SELECT *
			FROM ap_ctabancariadefault
			WHERE
				CodOrganismo = '".$codorganismo."' AND
				CodTipoPago = '".$codtipopago."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return $field['NroCuenta'];
}
?>