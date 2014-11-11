<?php
session_start();
set_time_limit(-1);
ini_set('memory_limit','128M');

//	FUNCION PARA CARGAR SELECTS 
function loadSelectValores($tabla, $codigo, $opt) {
	switch ($tabla) {			
		case "ORDENAR-CARRERAS":
			$c[0] = "e.CodEmpleado"; $v[0] = "Empleado";
			$c[1] = "e.CodDependencia"; $v[1] = "Dependencia";
			$c[2] = "e.Fingreso"; $v[2] = "Fecha de Ingreso";
			$c[3] = "pu1.DescripCargo, pu2.DescripCargo"; $v[3] = "Cargo";
			break;
		
		case "ESTADO-CARRERAS":
			$c[0] = "PR"; $v[0] = "En Preparación";
			$c[1] = "AP"; $v[1] = "Aprobado";
			break;

		case "ORDENAR-EVALUACION":
			$c[0] = "e1.CodEmpleado"; $v[0] = "Empleado";
			$c[1] = "p1.NomCompleto"; $v[1] = "Nombre";
			$c[2] = "pu1.DescripCargo"; $v[2] = "Cargo";
			$c[3] = "ee.Periodo"; $v[3] = "Periodo";
			$c[4] = "ee.Estado"; $v[4] = "Estado";
			break;

		case "ESTADO-EVALUACION":
			$c[0] = "EE"; $v[0] = "En Evaluación";
			$c[1] = "EV"; $v[1] = "Evaluado";
			break;

		case "TIPO-VACACIONES":
			$c[0] = "G"; $v[0] = "Goce";
			$c[1] = "I"; $v[1] = "Interrupcion";
			break;

		case "ESTADO-VACACIONES":
			$c[0] = "PE"; $v[0] = "Pendiente";
			$c[1] = "AP"; $v[1] = "Aprobado";
			$c[2] = "CO"; $v[2] = "Completado";
			$c[3] = "AN"; $v[3] = "Anulado";
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
		case "ESTADO-EVALUACION":
			$c[0] = "EE"; $v[0] = "En Evaluación";
			$c[1] = "EV"; $v[1] = "Evaluado";
			break;

		case "TIPO-VACACIONES":
			$c[0] = "G"; $v[0] = "Goce";
			$c[1] = "I"; $v[1] = "Interrupcion";
			break;

		case "ESTADO-VACACIONES":
			$c[0] = "PE"; $v[0] = "Pendiente";
			$c[1] = "AP"; $v[1] = "Aprobado";
			$c[2] = "CO"; $v[2] = "Completado";
			$c[3] = "AN"; $v[3] = "Anulado";
			break;
	}
	
	$i=0;
	foreach ($c as $cod) {
		if ($cod == $codigo) return $v[$i];
		$i++;
	}
}

// obtiene la cuenta bancaria por defecto de un tipo de pago para un organismo
function getCuentaBancariaDefault($CodOrganismo, $CodTipoPago) {
	$sql = "SELECT NroCuenta
			FROM ap_ctabancariadefault 
			WHERE
				CodOrganismo = '".$CodOrganismo."' AND
				CodTipoPago = '".$CodTipoPago."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return $field['NroCuenta'];
}
?>