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
			
		case "ESTADO-CONTROL-CIERRE":
			$c[0] = "A"; $v[0] = "Abierto";
			$c[1] = "C"; $v[1] = "Cerrado";
			break;
			
		case "TIPO-REGISTRO":
			$c[0] = "AB"; $v[0] = "Periodo Abierto";
			$c[1] = "AC"; $v[1] = "Periodo Actual";
			break;
			
		case "ESTADO-VOUCHER":
			$c[0] = "AB"; $v[0] = "Abierto";
			$c[1] = "AP"; $v[1] = "Aprobado";
			$c[2] = "MA"; $v[2] = "Mayorizado";
			$c[3] = "AN"; $v[3] = "Anulado";
			$c[4] = "RE"; $v[4] = "Rechazado";
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
			
		case "ESTADO-CONTROL-CIERRE":
			$c[0] = "A"; $v[0] = "Abierto";
			$c[1] = "C"; $v[1] = "Cerrado";
			break;
			
		case "TIPO-REGISTRO":
			$c[0] = "AB"; $v[0] = "Periodo Abierto";
			$c[1] = "AC"; $v[1] = "Periodo Actual";
			break;
			
		case "ESTADO-VOUCHER":
			$c[0] = "AB"; $v[0] = "Abierto";
			$c[1] = "AP"; $v[1] = "Aprobado";
			$c[2] = "MA"; $v[2] = "Mayorizado";
			$c[3] = "AN"; $v[3] = "Anulado";
			$c[4] = "RE"; $v[4] = "Rechazado";
			break;
	}
	
	$i=0;
	foreach ($c as $cod) {
		if ($cod == $codigo) return ($v[$i]);
		$i++;
	}
}
?>