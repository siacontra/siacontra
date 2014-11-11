<?php 
include ("fphp.php");	//echo $accion;
	connect();
extract($_POST);

//	--------------------------
if ($accion == "getDiasHabiles") {
	echo getDiasHabiles($desde, $hasta);
}

//	-------------------------------
function getDiasHabiles($desde, $hasta) {
	$dias_completos = getFechaDias($desde, $hasta);
	$dias_feriados = getDiasFeriados($desde, $hasta);
	$dia_semana = getDiaSemana($desde);
	$dias_habiles = 0;
	
	for ($i=0; $i<=$dias_completos; $i++) {
		if ($dia_semana >= 1 && $dia_semana <= 5) $dias_habiles++;
		$dia_semana++;
		if ($dia_semana == 7) $dia_semana = 0;
	}
	$dias_habiles -= $dias_feriados;
	return $dias_habiles;
}
//	-------------------------------

//	-------------------------------
function getFechaDias($fechad, $fechah) {
	list($dd, $md, $ad) = SPLIT( '[/.-]', $fechad);	$desde = "$ad-$md-$dd";
	list($dh, $mh, $ah) = SPLIT( '[/.-]', $fechah);	$hasta = "$ah-$mh-$dh";
	
	$sql = "SELECT DATEDIFF('$hasta', '$desde');";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$field = mysql_fetch_array($query);
	return $field[0];
}
//	---------------------------------

//	-------------------------------
function getDiasFeriados($fdesde, $fhasta) {
	list($dia_desde, $mes_desde, $anio_desde)=SPLIT('[/.-]', $fdesde); $DiaDesde = "$mes_desde-$dia_desde";
	list($dia_hasta, $mes_hasta, $anio_hasta)=SPLIT('[/.-]', $fhasta); $DiaHasta = "$mes_hasta-$dia_hasta";
	
	$sql = "SELECT * 
			FROM rh_feriados 
			WHERE 
				(FlagVariable = 'S' AND
				 (AnioFeriado = '".$anio_desde."' OR AnioFeriado = '".$anio_hasta."') AND 
				 (DiaFeriado >= '".$DiaDesde."' AND DiaFeriado <= '".$DiaHasta."')) OR
				(FlagVariable = 'N' AND DiaFeriado >= '".$DiaDesde."' AND DiaFeriado <= '".$DiaHasta."')";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$rows = mysql_num_rows($query);	$dias_feriados = 0;
	while ($field = mysql_fetch_array($query)) {
		list($mes, $dia) = SPLIT('[/.-]', $field['DiaFeriado']);
		if ($field['AnioFeriado'] == "") $anio = $anio_desde; else $anio = $field['AnioFeriado'];
		$fecha = "$dia-$mes-$anio";
		$dia_semana = getDiaSemana($fecha);
		if ($dia_semana >= 1 && $dia_semana <= 5) $dias_feriados++;
		if ($anio_desde != $anio_hasta) {
			if ($field['AnioFeriado'] == "") $anio = $anio_hasta; else $anio = $field['AnioFeriado'];
			$fecha = "$dia-$mes-$anio";
			$dia_semana = getDiaSemana($fecha);
			if ($dia_semana >= 1 && $dia_semana <= 5) $dias_feriados++;
		}
	}
	return $dias_feriados;
}
//	-------------------------------
?>