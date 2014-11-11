<?php
session_start();
set_time_limit(-1);
ini_set('memory_limit','128M');

//	FUNCION PARA CARGAR SELECTS 
function loadSelectValores($tabla, $codigo, $opt) {
	switch ($tabla) {			
		case "ACTUALIZAR-PERSONA":
			$c[0] = "Persona"; $v[0] = "Persona";
			$c[1] = "Empleado"; $v[1] = "Empleado";
			$c[2] = "Proveedor"; $v[2] = "Proveedor";
			$c[3] = "Cliente"; $v[3] = "Cliente";
			$c[4] = "Otro"; $v[4] = "Otro";
			break;
			
		case "TIPO-PERSONA":
			$c[0] = "EsEmpleado"; $v[0] = "Empleado";
			$c[1] = "EsProveedor"; $v[1] = "Proveedor";
			$c[2] = "EsCliente"; $v[2] = "Cliente";
			$c[3] = "EsOtro"; $v[3] = "Otro";
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
	}
	
	$i=0;
	foreach ($c as $cod) {
		if ($cod == $codigo) return $v[$i];
		$i++;
	}
}
?>