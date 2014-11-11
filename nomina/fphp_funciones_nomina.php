<?php 
include ("fphp_nomina.php");
include ("funciones_globales_nomina.php");
connect();
//	--------------------------
if ($accion=="EJECUTAR-FORMULA-CONCEPTO") {
	//	Variables usadas en la formula....
	$_PARAMETROS = PARAMETROS();
	$_ARGS['NOMINA'] = $nomina;
	$_ARGS['PERIODO'] = $periodo;
	$_ARGS['PROCESO'] = "[TODOS]";
	$_ARGS['ORGANISMO'] = "0001";
	$_ARGS['DESDE'] = $periodo."-01";
	$_ARGS['HASTA'] = $periodo."-30";
	$_ARGS['DIAS_PROCESO'] = 30;
	$_ARGS['TRABAJADOR'] = $_CODPERSONA;
	$_ARGS['FECHA_INGRESO'] = FECHA_INGRESO($_ARGS);
	$_ARGS['CONCEPTO'] = $_CODCONCEPTO;
	$_ARGS['FORMULA'] = OBTENER_FORMULA($_ARGS);
	$_DIAS_SUELDO_BASICO = DIAS_SUELDO_BASICO($_ARGS);
	$_SUELDO_BASICO = SUELDO_BASICO($_ARGS);
	$_SUELDO_BASICO_DIARIO = $_SUELDO_DIARIO / 30;
	$_SUELDO_BASICO_DIARIO = REDONDEO($_SUELDO_BASICO_DIARIO, 2);
	$_SUELDO_NORMAL_COMPLETO = TOTAL_ASIGNACIONES($_ARGS);
	$_SUELDO_NORMAL = $_SUELDO_NORMAL_COMPLETO;
	$_SUELDO_NORMAL_DIARIO = $_SUELDO_NORMAL_COMPLETO/30;
	$_SUELDO_NORMAL_DIARIO = REDONDEO($_SUELDO_NORMAL_DIARIO, 2);
	
	if ($_ARGS['FECHA_INGRESO'] <= $_ARGS['HASTA']) {
		//	Ejecuto la formula del concepto...
		eval($_ARGS['FORMULA']);
		$_ARGS['MONTO'] = REDONDEO($_MONTO, 2);
		$_ARGS['CANTIDAD'] = REDONDEO($_CANTIDAD, 2);
	} else {
		$_ARGS['MONTO'] = 0;
		$_ARGS['CANTIDAD'] = 0;
	}
	
	if ($_ARGS['FORMULA'] == "") $formula = "NO"; else $formula = "SI";
	
	//	Retorno los valores devueltos por la formula
	echo $formula."|".$_ARGS['MONTO']."|".$_ARGS['CANTIDAD'];
}
?>



