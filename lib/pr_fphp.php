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
		case "ESTADO":
			$c[0] = "A"; $v[0] = "Activo";
			$c[1] = "I"; $v[1] = "Inactivo";
			break;
	}
	
	$i=0;
	foreach ($c as $cod) {
		if ($cod == $codigo) return $v[$i];
		$i++;
	}
}

// funcion para validar los dos dias adicionales por antiguedad
function getDiasAdicionales($fingreso, $periodo) {
	list($ia, $im, $id) = split("[-]", $fingreso);
	list($pa, $pm) = split("[-]", $periodo);
	if ($ia <= '1997' && $pm == "06") $cantidad = 2;
	elseif ($ia <= '1997' && $pm != "06") $cantidad = 0;
	elseif ($ia > '1997' && $pm == $im && $pa != $ia) $cantidad = 2;
	else $cantidad = 0;
	return $cantidad;
}

//	funcion para obtener el complemento por los dos dias adicionales de antiguedad
function calculo_antiguedad_complemento($periodo, $trabajador, $fingreso) {
	list($pa, $pm) = split("[-]", $periodo);
	$mes = intval($pm + 1);
	if ($mes == 13) { $mes = 1; $ano = $pa; } else $ano = intval($pa - 1);
	if ($mes < 10) $desde = "$ano-0$mes"; else $desde = "$ano-$mes";	
	$hasta = $periodo;
	
	$CodPersona = $trabajador;
	$dPeriodo = $desde;
	$hPeriodo = $hasta;
	$Fingreso = $fingreso;
	$FechaHasta = "$periodo-30";
	
	//	obtengo los sueldos mensuales
	$sql = "SELECT Periodo, SueldoNormal
			FROM rh_sueldos
			WHERE
				CodPersona = '".$CodPersona."' AND
				Periodo >= '".$dPeriodo."' AND
				Periodo <= '".$hPeriodo."'";
	$query_sueldo = mysql_query($sql) or die ($sql.mysql_error());
	while($field_sueldo = mysql_fetch_array($query_sueldo)) {
		$periodo = $field_sueldo['Periodo'];
		$_PERIODOS[$periodo] = $periodo;
		$_SUELDO[$periodo] = $field_sueldo['SueldoNormal'];
	}
	
	//	obtengo bonos adicionales
	$sql = "SELECT Periodo, SUM(tnec.Monto) AS Monto
			FROM
				pr_tiponominaempleadoconcepto tnec
				INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto AND
											 c.Tipo = 'I' AND
											 c.FlagBonoRemuneracion = 'S')
			WHERE
				tnec.CodPersona = '".$CodPersona."' AND
				tnec.Periodo >= '".$dPeriodo."' AND
				tnec.Periodo <= '".$hPeriodo."'
			GROUP BY Periodo";
	$query_bonos = mysql_query($sql) or die ($sql.mysql_error());
	while($field_bonos = mysql_fetch_array($query_bonos)) {
		$periodo = $field_bonos['Periodo'];
		$_BONOS[$periodo] = $field_bonos['Monto'];
	}
	
	//	obtengo la alicuota vacacional
	$sql = "SELECT Periodo, Monto
			FROM pr_tiponominaempleadoconcepto
			WHERE
				CodPersona = '".$CodPersona."' AND
				Periodo >= '".$dPeriodo."' AND
				Periodo <= '".$hPeriodo."' AND
				CodConcepto = '0046'";
	$query_alivac = mysql_query($sql) or die ($sql.mysql_error());
	while($field_alivac = mysql_fetch_array($query_alivac)) {
		$periodo = $field_alivac['Periodo'];
		$_ALIVAC[$periodo] = $field_alivac['Monto'];
	}
	
	//	obtengo la alicuota fin de año
	$sql = "SELECT Periodo, Monto
			FROM pr_tiponominaempleadoconcepto
			WHERE
				CodPersona = '".$CodPersona."' AND
				Periodo >= '".$dPeriodo."' AND
				Periodo <= '".$hPeriodo."' AND
				CodConcepto = '0047'";
	$query_fin = mysql_query($sql) or die ($sql.mysql_error());
	while($field_fin = mysql_fetch_array($query_fin)) {
		$periodo = $field_fin['Periodo'];
		$_ALIFIN[$periodo] = $field_fin['Monto'];
	}
	
	//	obtengo los dias acumulados
	if ($Fingreso <= "1997-06-30") $FechaInicio = "1997-06-01"; else $FechaInicio = $Fingreso;
	list($sAnios, $sMeses, $sDias) = getTiempo(formatFechaDMA($FechaInicio), formatFechaDMA($FechaHasta));
	$DiasAcumulados = ($sAnios - 1) * 2;
	
	//	operaciones
	$SueldoAlicuotas = 0;
	foreach ($_PERIODOS as $periodo) {
		$RemuneracionDiaria = round(($_SUELDO[$periodo] + $_BONOS[$periodo]) / 30, 2);
		$SueldoAlicuotas += $_ALIVAC[$periodo] + $_ALIFIN[$periodo] + $RemuneracionDiaria;
	}
	$Monto = $SueldoAlicuotas / 12 * $DiasAcumulados;
	return $Monto;
	
	/*//	obtengo el sueldo normal
	$sql = "SELECT SUM(TotalIngresos) AS Monto
			FROM pr_tiponominaempleado
			WHERE
				CodPersona = '".$trabajador."' AND
				Periodo >= '".$desde."' AND
				Periodo <= '".$hasta."' AND
				CodTipoProceso = 'FIN'";
	$query_sueldo = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query_sueldo) != 0) $field_sueldo = mysql_fetch_array($query_sueldo);
	
	//	obtengo bonos adicionales
	$sql = "SELECT SUM(tnec.Monto) AS Monto
			FROM
				pr_tiponominaempleadoconcepto tnec
				INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto AND
											 c.Tipo = 'I' AND
											 c.FlagBonoRemuneracion = 'S')
			WHERE
				tnec.CodPersona = '".$trabajador."' AND
				tnec.Periodo >= '".$desde."' AND
				tnec.Periodo <= '".$hasta."'";	//echo "$sql<br>";
	$query_bonos = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query_bonos) != 0) $field_bonos = mysql_fetch_array($query_bonos);
	
	//	obtengo la alicuota vacacional
	$sql = "SELECT SUM(Monto) AS Monto
			FROM pr_tiponominaempleadoconcepto
			WHERE
				CodPersona = '".$trabajador."' AND
				Periodo >= '".$desde."' AND
				Periodo <= '".$hasta."' AND
				CodConcepto = '0046'";
	$query_alivac = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query_alivac) != 0) $field_alivac = mysql_fetch_array($query_alivac);
	
	//	obtengo la alicuota fin de año
	$sql = "SELECT SUM(Monto) AS Monto
			FROM pr_tiponominaempleadoconcepto
			WHERE
				CodPersona = '".$trabajador."' AND
				Periodo >= '".$desde."' AND
				Periodo <= '".$hasta."' AND
				CodConcepto = '0047'";
	$query_fin = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query_fin) != 0) $field_fin = mysql_fetch_array($query_fin);
	
	//	obtengo los dias acumulados
	if ($fingreso <= "1997-06-30") $fidesde = "1997-06-01"; else $fidesde = $fingreso;
	list($da, $dm) = split("[-]", $desde);
	list($ha, $hm) = split("[-]", $hasta);
	list($aservicio, $mservicio, $dservicio) = getTiempo(formatFechaDMA($fidesde), "30-$hm-$ha");
	$dias_acumulados = ($aservicio - 1) * 2;
	
	//	operaciones
	$sueldo_normal_diario = redondeo(($field_sueldo['Monto']/30), 2);
	$bonos_diario = redondeo(($field_bonos['Monto']/30), 2);
	$remuneracion_diaria = $sueldo_normal_diario + $bonos_diario;
	$sueldo_alicuotas = $remuneracion_diaria + $field_alivac['Monto'] + $field_fin['Monto'];
	$monto = $sueldo_alicuotas / 12 * $dias_acumulados;
	return $monto;*/
}
//	---------------------------------
?>