<?php
session_start();

//	------------------------------------------------------------
//	FUNCIONES GENERALES
//	------------------------------------------------------------

// FUNCION PARA OBTENER LOS DIAS ENTRE DOS FECHAS
function DIAS_FECHA($_DESDE, $_HASTA) {
/*	$sql = "SELECT DATEDIFF('$_HASTA', '$_DESDE') as dias;";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$field = mysql_fetch_array($query);
	
	$dias = ++$field[0];
	if (substr($_HASTA, 5, 5) == "02-28") {
		$dias+=2;
	}
	elseif (substr($_HASTA, 5, 5) == "02-29") {
		$dias+=1;
	}
	
  $dias= $field['dias'];
	
	return $dias;*/
	
		$sql = "SELECT DATEDIFF('$_HASTA', '$_DESDE');";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	$field = mysql_fetch_array($query);
	$dias = ++$field[0];
	if (substr($_HASTA, 5, 5) == "02-28") {
		$dias+=2;
	}
	elseif (substr($_HASTA, 5, 5) == "02-29") {
		$dias+=1;
	}
	
	return $dias;
	
	
}

//	FUNCION PARA OBTENER LOS DIAS DEL MES
function DIAS_DEL_MES($_FECHA) {
	list($dia, $mes, $anio) = SPLIT('[/.-]', $_FECHA);
	$dias_mes['01'] = 31;
	if ($anio%4==0) $dias_mes['02']=29; else $dias_mes['02']=28;
	$dias_mes['03'] = 31;
	$dias_mes['04'] = 30;
	$dias_mes['05'] = 31;
	$dias_mes['06'] = 30;
	$dias_mes['07'] = 31;
	$dias_mes['08'] = 31;
	$dias_mes['09'] = 30;
	$dias_mes['10'] = 31;
	$dias_mes['11'] = 30;
	$dias_mes['12'] = 31;
	return $dias_mes[$mes];
}

//	FUNCION PARA OBTENER EL DIA DE LA SEMANA DE UNA FECHA
function DIA_DE_LA_SEMANA($_FECHA) {
	// primero creo un array para saber los días de la semana
	$dias = array(0, 1, 2, 3, 4, 5, 6);
	$dia = substr($_FECHA, 0, 2);
	$mes = substr($_FECHA, 3, 2);
	$anio = substr($_FECHA, 6, 4);
	
	// en la siguiente instrucción $pru toma el día de la semana, lunes, martes,
	$pru = strtoupper($dias[intval((date("w",mktime(0,0,0,$mes,$dia,$anio))))]);
	return $pru;
}

//	------------------------------------------------------------
//	DISPONIBLES PARA LAS FORMULAS
//	------------------------------------------------------------

//	FUNCION PARA OBTENER LOS VALORES DE TODOS LOS PARAMETROS
function PARAMETROS() {
	$sql = "SELECT * FROM mastparametros";
	$query_parametro = mysql_query($sql) or die ($sql.mysql_error());
	while ($field_parametro = mysql_fetch_array($query_parametro)) {
		$id = $field_parametro['ParametroClave'];
		$_PARAMETRO[$id] = $field_parametro['ValorParam'];
	}
	return $_PARAMETRO;
}

// FUNCION PARA OBTENER LA FORMULA DE UN CONCEPTO
function OBTENER_FORMULA($_ARGS) {
	$sql = "SELECT Formula FROM pr_concepto WHERE CodConcepto = '".$_ARGS['CONCEPTO']."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		return $field['Formula'];
	} else return "";
}

//	FUNCION PARA OBTENER LOS DIAS DEL PROCESO QUE SE ESTA EJECUTANDO
function DIAS_PROCESO($_ARGS) {
	$sql = "SELECT
				FechaDesde, FechaHasta 
			FROM 
				pr_procesoperiodo 
			WHERE 
				CodOrganismo = '".$_ARGS['ORGANISMO']."' AND
				CodTipoNom = '".$_ARGS['NOMINA']."' AND
				Periodo = '".$_ARGS['PERIODO']."' AND
				CodTipoProceso = '".$_ARGS['PROCESO']."' AND
				FlagProcesado = 'N'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return DIAS_FECHA($field['FechaDesde'], $field['FechaHasta']);
}

//	FUNCION PARA OBTENER LA FECHA DEL PROCESO QUE SE ESTA EJECUTANDO
function FECHA_PROCESO($_ARGS) {
	$sql = "SELECT
				FechaDesde, FechaHasta 
			FROM 
				pr_procesoperiodo 
			WHERE 
				CodOrganismo = '".$_ARGS['ORGANISMO']."' AND
				CodTipoNom = '".$_ARGS['NOMINA']."' AND
				Periodo = '".$_ARGS['PERIODO']."' AND
				CodTipoProceso = '".$_ARGS['PROCESO']."' AND
				FlagProcesado = 'N'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return array($field['FechaDesde'], $field['FechaHasta']);
}

//	FUNCION PARA OBTENER LA FECHA DE INGRESO
function FECHA_INGRESO($_ARGS) {
	$sql = "SELECT Fingreso FROM mastempleado WHERE CodPersona = '".$_ARGS['TRABAJADOR']."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return $field['Fingreso'];
}

//	FUNCION PARA OBTENER LA FECHA DE EGRESO
function FECHA_EGRESO($_ARGS) {
	$sql = "SELECT Fegreso FROM mastempleado WHERE CodPersona = '".$_ARGS['TRABAJADOR']."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return $field['Fegreso'];
}

//	FUNCION PARA OBTENER LA FECHA DE EGRESO
function ESTADO($_ARGS) {
	$sql = "SELECT Estado FROM mastempleado WHERE CodPersona = '".$_ARGS['TRABAJADOR']."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return $field['Estado'];
}

//	------------------------------------------------------------
//	DISPONIBLES PARA LOS USUARIOS
//	------------------------------------------------------------

//	FUNCION PARA CALCULAR UN CONCEPTO
function CONCEPTO($_ARGS, $_CONCEPTO) {
	$sql = "SELECT Monto
			FROM pr_tiponominaempleadoconcepto
			WHERE
				CodTipoNom = '".$_ARGS['NOMINA']."' AND
				Periodo = '".$_ARGS['PERIODO']."' AND
				CodPersona = '".$_ARGS['TRABAJADOR']."' AND
				CodOrganismo = '".$_ARGS['ORGANISMO']."' AND
				CodTipoProceso = '".$_ARGS['PROCESO']."' AND
				CodConcepto = '".$_CONCEPTO."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return $field['Monto'];
}

//	FUNCION PARA CALCULAR UN CONCEPTO
function ULTIMO_CONCEPTO($_CONCEPTO) {
	global $_ARGS;
	$sql = "SELECT Monto
			FROM pr_tiponominaempleadoconcepto
			WHERE
				CodPersona = '".$_ARGS['TRABAJADOR']."' AND
				Periodo < '".$_ARGS['PERIODO']."' AND
				CodConcepto = '".$_CONCEPTO."'
			ORDER BY Periodo DESC
			LIMIT 0, 1";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return $field['Monto'];
}

//	FUNCION PARA OBTENER EL SUELDO BASICO DE UN TRABAJADOR
function SUELDO_BASICO($_ARGS) {
	$_PARAMETROS = PARAMETROS();
	$sum_sueldo = 0;
	
	$sql = "SELECT
				  en.Fecha, 
				  en.FechaHasta, 
				  ns.SueldoPromedio AS SueldoBasico,
				  e.Estado,
				  e.Fegreso
			FROM 
				  rh_empleadonivelacion en 
				  INNER JOIN mastempleado e ON (en.CodPersona = e.CodPersona)
				  INNER JOIN rh_puestos p ON (en.CodCargo = p.CodCargo) 
				  INNER JOIN rh_nivelsalarial ns ON (p.CategoriaCargo = ns.CategoriaCargo AND p.Grado = ns.Grado) 
			WHERE 
				  en.CodOrganismo = '".$_ARGS['ORGANISMO']."' AND  
				  --	en.CodTipoNom = '".$_ARGS['NOMINA']."' AND 
				  en.CodPersona = '".$_ARGS['TRABAJADOR']."' AND 
				  -- en.TipoAccion <> 'ET' AND 
				  ((en.FechaHasta = '0000-00-00' AND en.Fecha <= '".$_ARGS['HASTA']."') OR 
				   ('".$_ARGS['DESDE']."' >= en.Fecha AND 
				    '".$_ARGS['DESDE']."' <= en.FechaHasta)) 
			ORDER BY en.Fecha";
//echo $sql;
	$query = mysql_query($sql) or die ($sql.mysql_error());
	while ($field = mysql_fetch_array($query)) {
		
		if ($field['Fecha'] < $_ARGS['DESDE']) $desde = $_ARGS['DESDE'];
		else $desde = $field['Fecha'];
		
		if ($field['FechaHasta'] == "0000-00-00" || $field['FechaHasta'] > $_ARGS['HASTA']) $hasta = $_ARGS['HASTA'];
		else $hasta = $field['FechaHasta'];
		
		if ($field['Estado'] == "A") { 
			
			
			$dias = DIAS_FECHA($desde, $hasta); 	
		//	$dias = DIAS_FECHA($_ARGS['DESDE'], $_ARGS['HASTA']); 
		//	echo " ".$_ARGS['DESDE']."   ". $_ARGS['HASTA'];
		//echo $dias;
		}
		//if ($field['Estado'] == "I") {  $dias =30; }
		else {
			if ($field['Fegreso'] < $_ARGS['DESDE']) $dias = 0;
			else $dias = DIAS_FECHA($_ARGS['DESDE'], $field['Fegreso']);
		
		}
//echo $field['Fegreso']:
//echo $_ARGS['DESDE'];


		$monto = REDONDEO(($field['SueldoBasico'] / $_PARAMETROS['MAXDIASMES'] * $dias), 2);
//echo $monto;
		$sum_sueldo += $monto;
	}
//echo $sum_sueldo;
	return $sum_sueldo;
}

//	FUNCION PARA OBTENER EL SUELDO BASICO DE UN TRABAJADOR
function SUELDO_BASICO_COMPLETO($_ARGS) {
	$_PARAMETROS = PARAMETROS();
	$sum_sueldo = 0;
	
	$sql = "SELECT
				  en.Fecha, 
				  en.FechaHasta, 
				  ns.SueldoPromedio AS SueldoBasico,
				  e.Estado,
				  e.Fegreso
			FROM 
				  rh_empleadonivelacion en 
				  INNER JOIN mastempleado e ON (en.CodPersona = e.CodPersona)
				  INNER JOIN rh_puestos p ON (en.CodCargo = p.CodCargo) 
				  INNER JOIN rh_nivelsalarial ns ON (p.CategoriaCargo = ns.CategoriaCargo AND p.Grado = ns.Grado) 
			WHERE 
				  en.CodOrganismo = '".$_ARGS['ORGANISMO']."' AND  
				  --	en.CodTipoNom = '".$_ARGS['NOMINA']."' AND 
				  en.CodPersona = '".$_ARGS['TRABAJADOR']."' AND 
				  en.TipoAccion <> 'ET' AND 
				  ((en.FechaHasta = '0000-00-00' AND en.Fecha <= '".$_ARGS['HASTA']."') OR 
				   ('".$_ARGS['DESDE']."' >= en.Fecha AND 
				    '".$_ARGS['DESDE']."' <= en.FechaHasta)) 
			ORDER BY en.Fecha";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	while ($field = mysql_fetch_array($query)) {
		if ($field['Fecha'] < $_ARGS['DESDE']) $desde = $_ARGS['DESDE'];
		else $desde = $field['Fecha'];
		
		if ($field['FechaHasta'] == "0000-00-00" || $field['FechaHasta'] > $_ARGS['HASTA']) $hasta = $_ARGS['HASTA'];
		else $hasta = $field['FechaHasta'];
		
		if ($field['Estado'] == "A") $dias = DIAS_FECHA($desde, $hasta);
		else {
			if ($field['Fegreso'] < $_ARGS['DESDE']) $dias = 0;
			else $dias = DIAS_FECHA($_ARGS['DESDE'], $_ARGS['HASTA']);
		}
		
		$monto = ($field['SueldoBasico'] / $_PARAMETROS['MAXDIASMES']) * $dias;
		$sum_sueldo += $monto;
	}
	return $sum_sueldo;
}

//	FUNCION PARA OBTENER LA DIFERENCIA DE SUELDO BASICO DE UN TRABAJADOR
function DIFERENCIA_SUELDO_BASICO($_ARGS) {
	$_PARAMETROS = PARAMETROS();	
	$sum_diferencia = 0;
	
	//	Obtengo el sueldo basico mensual...
	$sql = "SELECT
				  ns.SueldoPromedio AS SueldoBasico 
			FROM 
				  rh_empleadonivelacion en 
				  INNER JOIN rh_puestos p ON (en.CodCargo = p.CodCargo) 
				  INNER JOIN rh_nivelsalarial ns ON (p.CategoriaCargo = ns.CategoriaCargo AND p.Grado = ns.Grado) 
			WHERE 
				  en.CodOrganismo = '".$_ARGS['ORGANISMO']."' AND  
				  --	en.CodTipoNom = '".$_ARGS['NOMINA']."' AND 
				  en.CodPersona = '".$_ARGS['TRABAJADOR']."' AND 
				  en.TipoAccion <> 'ET' AND 
				  en.FechaHasta = '0000-00-00'
			ORDER BY en.Fecha";
	$query_sueldo = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query_sueldo) != 0) $field_sueldo = mysql_fetch_array($query_sueldo);
		
	$sql = "SELECT
				  en.Fecha, 
				  en.FechaHasta, 
				  ns.SueldoPromedio AS SueldoTemporal 
			FROM 
				  rh_empleadonivelacion en 
				  INNER JOIN rh_puestos p ON (en.CodCargo = p.CodCargo) 
				  INNER JOIN rh_nivelsalarial ns ON (p.CategoriaCargo = ns.CategoriaCargo AND p.Grado = ns.Grado) 
			WHERE 
				  en.CodOrganismo = '".$_ARGS['ORGANISMO']."' AND  
				  --	en.CodTipoNom = '".$_ARGS['NOMINA']."' AND 
				  en.CodPersona = '".$_ARGS['TRABAJADOR']."' AND 
				  en.TipoAccion = 'ET' AND 
				  ((en.FechaHasta = '0000-00-00' AND en.Fecha <= '".$_ARGS['HASTA']."') OR 
				   ('".$_ARGS['DESDE']."' >= en.Fecha AND 
				    '".$_ARGS['DESDE']."' <= en.FechaHasta) OR 
				   (en.Fecha >= '".$_ARGS['DESDE']."' AND 
				    en.Fecha <= '".$_ARGS['HASTA']."')) 
			ORDER BY en.Fecha";
	$query_nivelaciones = mysql_query($sql) or die ($sql.mysql_error());
	while ($field_nivelaciones = mysql_fetch_array($query_nivelaciones)) {
		if ($field_nivelaciones['Fecha'] < $_ARGS['DESDE']) $desde = $_ARGS['DESDE'];
		else $desde = $field_nivelaciones['Fecha'];
		##
		if ($field_nivelaciones['FechaHasta'] == "0000-00-00" || $field_nivelaciones['FechaHasta'] > $_ARGS['HASTA']) $hasta = $_ARGS['HASTA'];
		else $hasta = $field_nivelaciones['FechaHasta'];
		##
		$dias = DIAS_FECHA($desde, $hasta);
		##
		$Diferencia = $field_nivelaciones['SueldoTemporal'] - $field_sueldo['SueldoBasico'];
		$Diario = $Diferencia / $_PARAMETROS['MAXDIASMES'];
		$monto = $Diario * $dias;
		##
		$sum_diferencia += $monto;
	}
	return $sum_diferencia;
}

//	FUNCION PARA OBTENER LOS DIAS DE SUELDO BASICO A PAGAR AL TRABAJADOR
function DIAS_SUELDO_BASICO($_ARGS) {
	$_PARAMETROS = PARAMETROS();
	$estado = ESTADO($_ARGS);
	$fegreso = FECHA_EGRESO($_ARGS);
	
	if ($estado == "A") {
		if ($_ARGS['FECHA_INGRESO'] < $_ARGS['DESDE']) return DIAS_FECHA($_ARGS['DESDE'], $_ARGS['HASTA']);
		else return DIAS_FECHA($_ARGS['FECHA_INGRESO'], $_ARGS['HASTA']);
	} else {
		if ($fegreso < $_ARGS['DESDE']) return 0;
		else return DIAS_FECHA($_ARGS['DESDE'], $fegreso);
	}
}

//	FUNCION PARA OBTENER SI UN TRABAJADOR ES UNIVERSITARIO
function UNIVERSITARIO($_ARGS) {
	$sql = "SELECT
				*
			FROM
				rh_empleado_instruccion
			WHERE
				CodPersona = '".$_ARGS['TRABAJADOR']."' AND
				CodGradoInstruccion = 'UNI'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) return true;
	else return false;
}

//	FUNCION PARA OBTENER SI UN TRABAJADOR ES T.S.U.
function TSU($_ARGS) {
	$sql = "SELECT
				*
			FROM
				rh_empleado_instruccion
			WHERE
				CodPersona = '".$_ARGS['TRABAJADOR']."' AND
				CodGradoInstruccion = 'TSU'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) return true;
	else return false;
}

//	FUNCION PARA OBTENER LOS AÑOS DE SERVICIO DE UN TRABAJADOR
function ANIOS_DE_SERVICIO($_ARGS) {
	$fingreso = FECHA_INGRESO($_ARGS);
	$periodo_actual = $_ARGS['HASTA'];
	list($anios, $meses, $dias) = TIEMPO_DE_SERVICIO(formatFechaDMA($fingreso), formatFechaDMA($periodo_actual));
	return $anios;
}

//	FUNCION PARA OBTENER SI UN TRABAJADOR TIENE UNA ESPECIALIZACION
function ESPECIALIZACION($_ARGS) {
	$sql = "SELECT
				*
			FROM
				rh_empleado_instruccion
			WHERE
				CodPersona = '".$_ARGS['TRABAJADOR']."' AND
				CodGradoInstruccion = 'POS' AND
				Nivel = '01'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) return true;
	else return false;
}

//	FUNCION PARA OBTENER SI UN TRABAJADOR TIENE UN MAGISTER
function MAGISTER($_ARGS) {
	$sql = "SELECT
				*
			FROM
				rh_empleado_instruccion
			WHERE
				CodPersona = '".$_ARGS['TRABAJADOR']."' AND
				CodGradoInstruccion = 'POS' AND
				Nivel = '02'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) return true;
	else return false;
}

//	FUNCION PARA OBTENER SI UN TRABAJADOR TIENE UN DOCTORADO
function DOCTORADO($_ARGS) {
	$sql = "SELECT
				*
			FROM
				rh_empleado_instruccion
			WHERE
				CodPersona = '".$_ARGS['TRABAJADOR']."' AND
				CodGradoInstruccion = 'POS' AND
				Nivel = '03'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) return true;
	else return false;
}

//	FUNCION PARA OBTENER EL NUMERO DE CURSOS DE UN TRABAJADOR
function NUMERO_DE_CURSOS($_ARGS) {
	$sql = "SELECT
				*
			FROM
				rh_empleado_cursos
			WHERE
				CodPersona = '".$_ARGS['TRABAJADOR']."' AND
				FlagPago = 'S' AND
				FechaCulminacion <= '".$_ARGS['PERIODO']."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	return mysql_num_rows($query);
}

//	FUNCION PARA OBTENER EL NUMERO DE HIJOS MENORES DE EDAD DE UN TRABAJADOR
function NUMERO_DE_HIJOS_MENORES($_ARGS) {
	$_PARAMETROS = PARAMETROS();
	
	list($a, $m)=SPLIT( '[/.-]', $_ARGS["PERIODO"]); $anio = $a - 18;
	$fecha = "$anio-$m-01";

	$sql = "SELECT
				*
			FROM
				rh_cargafamiliar
			WHERE
				CodPersona = '".$_ARGS['TRABAJADOR']."' AND
				Parentesco = 'HI' AND
				FechaNacimiento  >= '".$fecha."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	return intval(mysql_num_rows($query));
}

//	FUNCION PARA OBTENER EL NUMERO DE HIJOS MENORES DE EDAD DE UN TRABAJADOR
function NUMERO_DE_HIJOS_MENORES14($_ARGS) {
	$_PARAMETROS = PARAMETROS();
	
	list($a, $m) = split("[/.-]", $_ARGS["PERIODO"]); $anio = $a - 15;
	$mes = intval($m) + 1;
	if ($mes > 12) { $mes = 1; $anio++; }
	if ($mes < 10) $mes = "0$mes";	
	$fecha = "$anio-$mes-01";

	$sql = "SELECT *
			FROM rh_cargafamiliar
			WHERE
				CodPersona = '".$_ARGS['TRABAJADOR']."' AND
				Parentesco = 'HI' AND
				FechaNacimiento  >= '".$fecha."'";	echo "$sql";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	return intval(mysql_num_rows($query));
}
//	FUNCION PARA OBTENER EL NUMERO DE HIJOS MAYORES UNIVERSITARIOS DE EDAD DE UN TRABAJADOR
function NUMERO_DE_HIJOS_MAYORES_ESTUDIANDO($_ARGS) {
	$_PARAMETROS = PARAMETROS();
	
	list($a, $m)=SPLIT( '[/.-]', $_ARGS["PERIODO"]); 

            $anio = $a - 18;
	$fecha18 = "$anio-$m-01";
	
	       $anio = $a - 25;
	$fecha25 = "$anio-$m-01";

	$sql = "SELECT
				*
			FROM
				rh_cargafamiliar
			WHERE
				CodPersona = '".$_ARGS['TRABAJADOR']."' AND
				Parentesco = 'HI' 
				AND	FechaNacimiento  <= '".$fecha18."'
				AND	FechaNacimiento  >= '".$fecha25."'
				AND rh_cargafamiliar.Parentesco = 'HI' 
				AND  rh_cargafamiliar.FlagEstudia = 'S'	
				";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	return intval(mysql_num_rows($query));
}
//	FUNCION PARA OBTENER EL NUMERO DE HIJOS MENORES DE EDAD DE UN TRABAJADOR
function NUMERO_DE_HIJOS($edad, $fecha=NULL) {
	global $_ARGS;
	$_PARAMETROS = PARAMETROS();
	
	list($a, $m, $d) = split("[/.-]", $fecha); $anio = $a - ($edad + 1);
	$mes = intval($m);
	$fecha = "$anio-$mes-$d";

	$sql = "SELECT *
			FROM rh_cargafamiliar
			WHERE
				CodPersona = '".$_ARGS['TRABAJADOR']."' AND
				Parentesco = 'HI' AND
				FechaNacimiento  >= '".$fecha."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	return intval(mysql_num_rows($query));
}

//	FUNCION PARA OBTENER EL SUELDO MINIMO
function SUELDO_MINIMO($_ARGS) {
	$sql = "SELECT
				Monto
			FROM
				mastsueldosmin
			WHERE
				Periodo = (SELECT MAX(Periodo) FROM mastsueldosmin WHERE Periodo <= '".$_ARGS['PERIODO']."')";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return $field['Monto'];
}

//	FUNCION PARA OBTENER EL TOTAL ASIGNACIONES DE LA PERSONA
function TOTAL_ASIGNACIONES($_ARGS) {
	$monto = 0;
	$_PARAMETROS = PARAMETROS();
	
	//	Variables usadas en la formula....
	$_DIAS_SUELDO_BASICO = $_ARGS['DIAS_PROCESO'];
	$_SUELDO_BASICO = SUELDO_BASICO_COMPLETO($_ARGS);
	$_SUELDO_BASICO_DIARIO = $_SUELDO_DIARIO / 30; $_SUELDO_BASICO_DIARIO = REDONDEO($_SUELDO_BASICO_DIARIO, 2);
	$_SUELDO_NORMAL = 0;
	$_SUELDO_NORMAL_DIARIO = 0;
	
	$sql = "(SELECT
					pc.CodConcepto,
					pc.Descripcion,
					pc.PlanillaOrden,
					pc.FlagAutomatico,
					pc.Formula,
					pc.Tipo,
					pc.FlagBono,
					pec.Monto,
					pec.Cantidad,
					'1' AS Orden
				FROM
					pr_empleadoconcepto pec
					INNER JOIN pr_concepto pc ON (pec.CodConcepto = pc.CodConcepto)
					INNER JOIN pr_conceptotiponomina pctn ON (pc.CodConcepto = pctn.CodConcepto AND pctn.CodTipoNom = '".$_ARGS['NOMINA']."' )
				WHERE
					(pc.Tipo = 'I') AND	(pec.CodPersona = '".$_ARGS['TRABAJADOR']."') AND
					(pec.Procesos = 'FIN') AND
					((pec.TipoAplicacion = 'T' AND pec.PeriodoHasta >= '".$_ARGS['PERIODO']."' AND pec.PeriodoDesde <= '".$_ARGS['PERIODO']."') OR 
					 (pec.TipoAplicacion = 'P' AND pec.PeriodoDesde <= '".$_ARGS['PERIODO']."'))
				GROUP BY CodConcepto)
					  
			UNION
			
			(SELECT
					pc.CodConcepto,
					pc.Descripcion,
					pc.PlanillaOrden,
					pc.FlagAutomatico,
					pc.Formula,
					pc.Tipo,
					pc.FlagBono,
					'' AS Monto,
					'' AS Cantidad,
					'1' AS Orden
				FROM
					pr_concepto pc
					INNER JOIN pr_conceptoproceso pcp ON (pc.CodConcepto = pcp.CodConcepto AND pcp.CodTipoProceso = 'FIN')
					INNER JOIN pr_conceptotiponomina pctn ON (pc.CodConcepto = pctn.CodConcepto AND pctn.CodTipoNom = '".$_ARGS['NOMINA']."' )
				WHERE
					pc.Tipo = 'I' AND pc.FlagAutomatico = 'S' AND 
					pc.CodConcepto NOT IN (
						SELECT
							pc.CodConcepto
						FROM
							pr_empleadoconcepto pec
							INNER JOIN pr_concepto pc ON (pec.CodConcepto = pc.CodConcepto)
							INNER JOIN pr_conceptotiponomina pctn ON (pc.CodConcepto = pctn.CodConcepto AND pctn.CodTipoNom = '".$_ARGS['NOMINA']."' )
						WHERE
							(pc.Tipo = 'I') AND	(pec.CodPersona = '".$_ARGS['TRABAJADOR']."') AND
							(pec.Procesos = 'FIN') AND
							((pec.TipoAplicacion = 'T' AND pec.PeriodoHasta >= '".$_ARGS['PERIODO']."' AND pec.PeriodoDesde <= '".$_ARGS['PERIODO']."') OR 
							 (pec.TipoAplicacion = 'P' AND pec.PeriodoDesde <= '".$_ARGS['PERIODO']."')))
				GROUP BY CodConcepto)
					
			ORDER BY Orden";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	while ($field = mysql_fetch_array($query)) {
		//	Variables usadas en la formula....
		if (trim($field['Formula']) == "") {
			$_ARGS['CONCEPTO'] = $field['CodConcepto'];
			$_ARGS['FORMULA'] = "";
			$_ARGS['MONTO'] = $field['Monto'];
			$_ARGS['CANTIDAD'] = $field['Cantidad'];
		} else {
			//	Variables usadas en la formula....
			$_ARGS['CONCEPTO'] = $field['CodConcepto'];
			$_ARGS['FORMULA'] = $field['Formula'];
			
			$_MONTO = 0;
			$_CANTIDAD = 0;
			//	Ejecuto la formula del concepto...
			eval($_ARGS['FORMULA']);
			$_ARGS['MONTO'] = REDONDEO($_MONTO, 2);
			$_ARGS['CANTIDAD'] = REDONDEO($_CANTIDAD, 2);
		}
		
		$suma += $_ARGS['MONTO'];	//echo "$_ARGS[CONCEPTO] = $_ARGS[MONTO];\n";
	}
	return $suma;
}

//	FUNCION PARA OBTENER EL NUMERO DE DIAS AL QUE SE LE APLICARA EL PORCENTAJE CORRESPONDIENTE DE LA JERARQUIA
function DIAS_JERARQUIA($_ARGS) {
	$suma = 0;
	$sql = "SELECT 
				  en.Fecha, 
				  en.FechaHasta, 
				  p.Grado, 
				  ns.SueldoPromedio AS SueldoBasico 
			FROM 
				  rh_empleadonivelacion en 
				  INNER JOIN rh_puestos p ON (en.CodCargo = p.CodCargo) 
				  INNER JOIN rh_nivelsalarial ns ON (p.CategoriaCargo = ns.CategoriaCargo AND p.Grado = ns.Grado) 
			WHERE
				  en.CodOrganismo = '".$_ARGS['ORGANISMO']."' AND
				  --	en.CodTipoNom = '".$_ARGS['NOMINA']."' AND
				  en.CodPersona = '".$_ARGS['TRABAJADOR']."' AND
				  en.TipoAccion <> 'ET' AND
				  ((en.FechaHasta = '0000-00-00' AND en.Fecha <= '".$_ARGS['HASTA']."') OR
				   ('".$_ARGS['DESDE']."' <= en.FechaHasta))
			ORDER BY en.Fecha";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	while ($field = mysql_fetch_array($query)) {
		if ($field['Fecha'] < $_ARGS['DESDE']) $desde = $_ARGS['DESDE'];
		else $desde = $field['Fecha'];
		
		if ($field['FechaHasta'] == "0000-00-00" || $field['FechaHasta'] > $_ARGS['HASTA']) $hasta = $_ARGS['HASTA'];
		else $hasta = $field['FechaHasta'];
		
		if ($field['Grado'] == "90" || $field['Grado'] == "96" || $field['Grado'] == "97" || $field['Grado'] == "98" || $field['Grado'] == "99") {
			$dias = DIAS_FECHA($desde, $hasta);
			$suma += $dias;
		}
	}
	return $suma;
}

//	FUNCION PARA OBTENER EL NUMERO DE DIAS AL QUE SE LE APLICARA EL PORCENTAJE CORRESPONDIENTE DE LA JERARQUIA
function DIAS_JERARQUIA_DIFERENCIA($_ARGS) {
	$suma = 0;
	$sql = "SELECT 
				  en.Fecha, 
				  en.FechaHasta, 
				  p.Grado, 
				  ns.SueldoPromedio AS SueldoBasico 
			FROM 
				  rh_empleadonivelacion en 
				  INNER JOIN rh_puestos p ON (en.CodCargo = p.CodCargo) 
				  INNER JOIN rh_nivelsalarial ns ON (p.CategoriaCargo = ns.CategoriaCargo AND p.Grado = ns.Grado) 
			WHERE
				  en.CodOrganismo = '".$_ARGS['ORGANISMO']."' AND
				  --	en.CodTipoNom = '".$_ARGS['NOMINA']."' AND
				  en.CodPersona = '".$_ARGS['TRABAJADOR']."' AND
				  en.TipoAccion = 'ET' AND
				  ((en.FechaHasta = '0000-00-00' AND en.Fecha <= '".$_ARGS['HASTA']."') OR
				   ('".$_ARGS['DESDE']."' <= en.FechaHasta))
			ORDER BY en.Fecha";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	while ($field = mysql_fetch_array($query)) {
		if ($field['Fecha'] < $_ARGS['DESDE']) $desde = $_ARGS['DESDE'];
		else $desde = $field['Fecha'];
		
		if ($field['FechaHasta'] == "0000-00-00" || $field['FechaHasta'] > $_ARGS['HASTA']) $hasta = $_ARGS['HASTA'];
		else $hasta = $field['FechaHasta'];
		
		if ($field['Grado'] == "90" || $field['Grado'] == "96" || $field['Grado'] == "97" || $field['Grado'] == "98" || $field['Grado'] == "99") {
			$dias = DIAS_FECHA($desde, $hasta);
			$suma += $dias;
		}
	}
	return $suma;
}

//	FUNCION PARA OBTENER EL NUMERO DE LUNES DEL MES
function NUMERO_LUNES($_ARGS) {
	list($ap, $mp)=SPLIT('[/.-]', $_ARGS['PERIODO']); $periodo_inicio = "01-$mp-$ap";
	$primer_dia_semana = DIA_DE_LA_SEMANA($periodo_inicio);
	$dias_del_mes = DIAS_DEL_MES($periodo_inicio);
	
	if (($primer_dia_semana == 0 || $primer_dia_semana == 6) && $dias_del_mes == 31) $lunes = 5;
	elseif ($primer_dia_semana == 1 && ($dias_del_mes == 30 || $dias_del_mes == 31)) $lunes = 5;
	elseif ($primer_dia_semana == 2 && ($dias_del_mes == 29 || $dias_del_mes == 30 || $dias_del_mes == 31)) $lunes = 5;
	else $lunes = 4;
	
	return $lunes;
}

//	FUNCION PARA OBTENER EL NUMERO DE LUNES DEL MES
function NUMERO_LUNES_FECHA($_ARGS) {
	$_FECHA_EGRESO = FECHA_EGRESO($_ARGS);
	
	if (ESTADO($_ARGS) == "A") {
		if ($_ARGS['FECHA_INGRESO'] <= $_ARGS['DESDE']) {
			if ($_ARGS['FECHA_INGRESO'] < $_ARGS['HASTA']) list($ae, $me, $de) = SPLIT('[/.-]', $_ARGS['HASTA']);
			else list($ae, $me, $de) = SPLIT('[/.-]', $_ARGS['FECHA_INGRESO']);

			list($ap, $mp) = SPLIT('[/.-]', $_ARGS['PERIODO']); $periodo_inicio = "01-$mp-$ap";
			$primer_dia_semana = DIA_DE_LA_SEMANA($periodo_inicio);
			$dia_semana = $primer_dia_semana;
			$dia_inicio = 1;
			$dia_fin = DIAS_DEL_MES("$de-$me-$ae");
		} else {
			list($ae, $me, $de) = SPLIT('[/.-]', $_ARGS['HASTA']);
			list($ai, $mi, $di) = SPLIT('[/.-]', $_ARGS['FECHA_INGRESO']); $periodo_inicio = "$di-$mi-$ai";	$diai = (int) $di;
			
			$primer_dia_semana = DIA_DE_LA_SEMANA($periodo_inicio);
			$dia_semana = $primer_dia_semana;
			
			if ($dia_semana == 1) {
				$dia_inicio = (int) $di;
			} else {
				if ($dia_semana == 0) $restar_dia_semana = $diai - 7;
				else $restar_dia_semana = $diai - $dia_semana;
				
				if ($restar_dia_semana < 1) $dia_inicio = (int) $di;
				else {
					$diferencia_dias = $dia_semana - 1;
					$dia_inicio = (int) $di;
					$dia_inicio -= $diferencia_dias;
				}
			}
			$dia_fin = DIAS_DEL_MES("$de-$me-$ae");
		}
	}
	else {
		list($ae, $me, $de) = SPLIT('[/.-]', $_FECHA_EGRESO);
		
		if ($_ARGS['FECHA_INGRESO'] <= $_ARGS['DESDE']) {
			list($ap, $mp) = SPLIT('[/.-]', $_ARGS['PERIODO']); $periodo_inicio = "01-$mp-$ap";
			$primer_dia_semana = DIA_DE_LA_SEMANA($periodo_inicio);
			$dia_semana = $primer_dia_semana;
			$dia_inicio = 1;
			$dia_fin = (int) $de;
		} else {	
			list($ai, $mi, $di) = SPLIT('[/.-]', $_ARGS['FECHA_INGRESO']); $periodo_inicio = "$di-$mp-$ap";	$diai = (int) $di;
			$primer_dia_semana = DIA_DE_LA_SEMANA($periodo_inicio);
			$dia_semana = $primer_dia_semana;
			
			if ($dia_semana == 1) {
				$dia_inicio = (int) $di;
			} else {
				if ($dia_semana == 0) $restar_dia_semana = $diai - 7;
				else $restar_dia_semana = $diai - $dia_semana;
				
				if ($restar_dia_semana < 1) $dia_inicio = (int) $di;
				else {
					$diferencia_dias = $dia_semana - 1;
					$dia_inicio = (int) $di;
					$dia_inicio -= $diferencia_dias;
				}
			}
			$dia_fin = (int) $de;
		}
	}
	
	$lunes = 0;
	for ($dia=$dia_inicio; $dia<=$dia_fin; $dia++) {
		if ($dia_semana == 7) $dia_semana = 0;
		if ($dia_semana == 1) $lunes++;
		$dia_semana++;
	}
	return $lunes;
}

//	FUNCION PARA OBTENER EL PAGO POR ADELANTO DE QUINCENA DEL TRABAJADOR
function ADELANTO_QUINCENA($_ARGS) {
	$sql = "SELECT
				TotalIngresos
			FROM
				pr_tiponominaempleado
			WHERE
				--	CodTipoNom = '".$_ARGS['NOMINA']."' AND
				Periodo = '".$_ARGS['PERIODO']."' AND
				CodPersona = '".$_ARGS['TRABAJADOR']."' AND
				CodOrganismo = '".$_ARGS['ORGANISMO']."' AND
				CodTipoProceso = 'ADE'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return $field['TotalIngresos'];
}

//	FUNCION PARA OBTENER EL PAGO POR ADELANTO DE QUINCENA DEL TRABAJADOR
function PORCENTAJE_ISLR($_ARGS) {
	$sql = "SELECT
				Porcentaje
			FROM
				pr_impuestorenta
			WHERE
				CodPersona = '".$_ARGS['TRABAJADOR']."' AND
				'".$_ARGS['PERIODO']."' >= Desde AND '".$_ARGS['PERIODO']."' <= Hasta";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return $field['Porcentaje'];
}

//	FUNCION QUE OBTIENE EL SALARIO DE LA JUBILACION DE UN TRABAJADOR
function SALARIO_JUBILACION($_ARGS) {
	$sql = "SELECT MontoJubilacion FROM mastempleado WHERE CodPersona = '".$_ARGS['TRABAJADOR']."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) {
		$field = mysql_fetch_array($query);
		return $field['MontoJubilacion'];
	}
	else return 0;
}


//	FUNCION PARA OBTENER EL SUELDO BASICO DE UN TRABAJADOR
function PENSION_JUBILACION($_ARGS) {
	$_PARAMETROS = PARAMETROS();
	$sum_sueldo = 0;
	
 $sql = "SELECT
				  en.Fecha, 
				  en.FechaHasta, 
				  ns.SueldoPromedio AS SueldoBasico,
				  e.Estado,
				  e.Fegreso
			FROM 
				  rh_empleadonivelacion en 
				  INNER JOIN mastempleado e ON (en.CodPersona = e.CodPersona)
				  INNER JOIN rh_puestos p ON (en.CodCargo = p.CodCargo) 
				  INNER JOIN rh_nivelsalarial ns ON (p.CategoriaCargo = ns.CategoriaCargo AND p.Grado = ns.Grado) 
			WHERE 
				  en.CodOrganismo = '".$_ARGS['ORGANISMO']."' AND  
				  --	en.CodTipoNom = '".$_ARGS['NOMINA']."' AND 
				  en.CodPersona = '".$_ARGS['TRABAJADOR']."' AND 
				  -- en.TipoAccion <> 'ET' AND 
				  ((en.FechaHasta = '0000-00-00' AND en.Fecha <= '".$_ARGS['HASTA']."') OR 
				   ('".$_ARGS['DESDE']."' >= en.Fecha AND 
				    '".$_ARGS['DESDE']."' <= en.FechaHasta)) 
			ORDER BY en.Fecha";
//echo $sql;
	$query = mysql_query($sql) or die ($sql.mysql_error());
	while ($field = mysql_fetch_array($query)) {
		
		/*
		if ($field['Fecha'] < $_ARGS['DESDE']) $desde = $_ARGS['DESDE'];
		
		else $desde = $field['Fecha'];
		
		if ($field['FechaHasta'] == "0000-00-00" || $field['FechaHasta'] > $_ARGS['HASTA']) $hasta = $_ARGS['HASTA'];
		else $hasta = $field['FechaHasta'];
		
		if ($field['Estado'] == "A") { $dias = DIAS_FECHA($desde, $hasta); }
		else {
			if ($field['Fegreso'] < $_ARGS['DESDE']) $dias = 0;
			else $dias = DIAS_FECHA($_ARGS['DESDE'], $field['Fegreso']);
		} */
//echo "Fecha Egreso: ".$field['Fegreso']."  ARGS_DESDE:".$_ARGS['DESDE'];
//echo $field['Fecha'] ;
//echo $_ARGS['DESDE'];
//echo $dias;
		$monto = REDONDEO(($field['SueldoBasico'] ), 2);
//echo $monto;
		$sum_sueldo += $monto;
	}
//echo $sum_sueldo;
	return $sum_sueldo;
}


//	FUNCION QUE DEVUELVE LOS MESES DE ANTIGUEDAD QUE TIENE UN TRABAJADOR
function MESES_ANTIGUEDAD($_ARGS) {
	$fingreso = FECHA_INGRESO($_ARGS);
	$periodo_actual = $_ARGS['HASTA'];
	list($anios, $meses, $dias) = TIEMPO_DE_SERVICIO(formatFechaDMA($fingreso), formatFechaDMA($periodo_actual));
	$cantidad = $meses + ($anios * 12);
	return $cantidad;
}

//	FUNCION QUE DEVUELVE SI UN FUNCIONARIO TIENE CARGO DE JEFE
function JEFE_TITULAR($_ARGS) {
	$sql = "SELECT
				me.CodPersona,
				rp.Grado
			FROM
				mastempleado me
				INNER JOIN rh_puestos rp ON (me.CodCargo = rp.CodCargo)
			WHERE
				me.CodPersona = '".$_ARGS['TRABAJADOR']."' AND
				rp.Grado >= '90' AND rp.Grado <= '99'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) return true; else return false;
}

//	FUNCION QUE DEVUELVE LOS MESES PARA BONIFICACION DE FIN DE AÑO
function MESES_POR_DERECHO($_ARGS) {
	$fingreso = FECHA_INGRESO($_ARGS);
	list($anio, $mes) = SPLIT('[/.-]', $_ARGS['PERIODO']); $periodo_actual = $anio."-12-31";
	
	list($anios, $meses, $dias) = TIEMPO_DE_SERVICIO(formatFechaDMA($fingreso), formatFechaDMA($periodo_actual));
	$cantidad = $meses + ($anios * 12);
	if ($cantidad > 12) $cantidad = 12;
	return $cantidad;
}

//	FUNCION QUE DEVUELVE LOS MESES PARA BONIFICACION DE FIN DE AÑO
function RETENCION_JUDICIAL($_ARGS) {
	$sql = "SELECT rjc.Descuento 
			FROM 
				rh_retencionjudicial rj
				INNER JOIN rh_retencionjudicialconceptos rjc ON (rj.CodOrganismo = rjc.CodOrganismo AND rj.CodRetencion = rjc.CodRetencion)
			WHERE 
				rj.CodPersona = '".$_ARGS['TRABAJADOR']."' AND 
				rj.FechaResolucion <= '".$_ARGS['HASTA']."' AND 
				rjc.CodConcepto = '".$_ARGS['CONCEPTO']."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return $field['Descuento'];
}

//	FUNCION QUE DEVUELVE LOS MESES PARA BONIFICACION DE FIN DE AÑO
function TIPO_RETENCION($_ARGS) {
	$sql = "SELECT rjc.TipoDescuento 
			FROM 
				rh_retencionjudicial rj
				INNER JOIN rh_retencionjudicialconceptos rjc ON (rj.CodOrganismo = rjc.CodOrganismo AND rj.CodRetencion = rjc.CodRetencion)
			WHERE 
				rj.CodPersona = '".$_ARGS['TRABAJADOR']."' AND 
				rj.FechaResolucion <= '".$_ARGS['HASTA']."' AND 
				rjc.CodConcepto = '".$_ARGS['CONCEPTO']."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	if ($field['TipoDescuento'] == "P") $_TIPO = "PORCENTAJE";
	elseif ($field['TipoDescuento'] == "M") $_TIPO = "MONTO";
	return $_TIPO;
}

//
function ULTIMO_SUELDO_NORMAL($_ARGS) {
	list($anio, $mes) = SPLIT('[/.-]', $_ARGS['PERIODO']);	$anio = (int) $anio;	$mes = (int) $mes;
	$mes--;
	if ($mes == 0) { $anio--; $mes = 12; }
	if ($mes < 10) $mes = "0$mes";	
	$ultimo_periodo = "$anio-$mes";
	
	$sql = "SELECT TotalIngresos 
			FROM pr_tiponominaempleado 
			WHERE
				CodOrganismo = '".$_ARGS['ORGANISMO']."'  AND
				--	CodTipoNom = '".$_ARGS['NOMINA']."'  AND
				CodPersona = '".$_ARGS['TRABAJADOR']."'  AND
				CodTipoProceso = 'FIN'  AND
				Periodo = '".$ultimo_periodo."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return $field['TotalIngresos'];
}

//
function ULTIMO_SUELDO_NORMAL_DIARIO($_ARGS) {
	list($anio, $mes) = SPLIT('[/.-]', $_ARGS['PERIODO']);	$anio = (int) $anio;	$mes = (int) $mes;
	$mes--;
	if ($mes == 0) { $anio--; $mes = 12; }
	if ($mes < 10) $mes = "0$mes";	
	$ultimo_periodo = "$anio-$mes";
	$monto_bono=0;
	$montoTotal=0;
	//-----------------------------------------------------------------

		
		$sql= "SELECT Monto 	FROM pr_tiponominaempleadoconcepto
		WHERE
	CodTipoNom = '".$_ARGS['NOMINA']."' AND 
	Periodo = '".$ultimo_periodo."' AND
	CodPersona = '".$_ARGS['TRABAJADOR']."' AND 	
	CodOrganismo = '".$_ARGS['ORGANISMO']."' AND
	CodTipoProceso = 'FIN' AND 
		( 
		CodConcepto = '0019' OR
		CodConcepto = '0192' OR
		CodConcepto = '0183' OR
		CodConcepto = '0185' OR
		CodConcepto = '0038' OR
		CodConcepto = '0198' OR
		CodConcepto = '0050' OR
		CodConcepto = '0174' OR
		CodConcepto = '0170' OR
		CodConcepto = '0171' OR
		CodConcepto = '0190'OR
		
		CodConcepto = '0197')
		 ";
				
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) 
		{
		while ($field = mysql_fetch_array($query)) 
		 $monto_bono = $monto_bono + $field['Monto'];
		}	
	    
	  
	  
	  $sql= "SELECT Monto 	FROM pr_tiponominaempleadoconcepto
		WHERE
	CodTipoNom = '".$_ARGS['NOMINA']."' AND 
	Periodo = '".$ultimo_periodo."' AND
	CodPersona = '".$_ARGS['TRABAJADOR']."' AND 	
	CodOrganismo = '".$_ARGS['ORGANISMO']."' AND
	CodTipoProceso = 'FIN' AND 
		( 
		CodConcepto = '0001' )
		 ";
				
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) 
		{
		while ($field = mysql_fetch_array($query)) 
		 $monto_bono = $monto_bono + $field['Monto']+ $field['Monto'];
		}	  
	    
	    
/*
0174
0183
0185
0192
0170
0171
0172
0173 */
	
	/*
	$sql = "SELECT TotalIngresos ,SueldoBasico
			FROM pr_tiponominaempleado 
			WHERE
				CodOrganismo = '".$_ARGS['ORGANISMO']."' AND
				CodPersona = '".$_ARGS['TRABAJADOR']."' AND
				CodTipoProceso = 'FIN'  AND
				Periodo = '".$ultimo_periodo."'
			ORDER BY Periodo DESC
			LIMIT 0, 1";//se modifico la condicion de Periodo para que tomara el ultimo
*/
     
     /*
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);


	///
	$sql2 = "SELECT TotalIngresos ,SueldoBasico
			FROM pr_tiponominaempleado 
			WHERE
				CodOrganismo = '".$_ARGS['ORGANISMO']."' AND
				CodPersona = '".$_ARGS['TRABAJADOR']."' AND
				CodTipoProceso = 'PRQ'  AND
				Periodo = '".$ultimo_periodo."'
			ORDER BY Periodo DESC
			LIMIT 0, 1";//se modifico la condicion de Periodo para que tomara el ultimo

	$query2 = mysql_query($sql2) or die ($sql2.mysql_error());
	if (mysql_num_rows($query2) != 0) $field2 = mysql_fetch_array($query2);
*/
/*
	if($field['TotalIngresos'] > $field2['TotalIngresos'])
	{

		$montoTotal = $field['SueldoBasico'];

	} else {

		$montoTotal = $field['SueldoBasico'];
	}*/

	//$monto = ($montoTotal*2+$monto_bono)  / 30;
	
	$monto = ($monto_bono/30)  ;
	return number_format($monto, 2, '.', '');
}


function AMD_GOBIERNO($_ARGS)
{
	 $sql = "SELECT *
		FROM rh_empleado_experiencia AS ex
		JOIN mastempleado AS me ON me.CodPersona = ex.CodPersona
		WHERE me.CodPersona = '".$_ARGS['TRABAJADOR']."' and ex.TipoEnte = '02'";

	$query = mysql_query($sql) or die ($sql.mysql_error());

	$totalAnio = 0;
	$sumaAnio = 0;
	$sumaMes = 0;
	$totalMese = 0;
	$sumaDias = 0;
	$totalDias = 0;
	$auxAnioMes = 0;
	$auxAnioDia = 0;

	//if ($anios>=1) {			
		while ($field_a = mysql_fetch_array($query)) {

		$vectorIngreso = split('-',$field_a['FechaDesde']);
		$vectorEgreso = split('-',$field_a['FechaHasta']);

		list($anioIngreso,$mesIngreso,$diaIngreso) = $vectorIngreso;
		list($anioEgreso,$mesEgreso,$diaEgreso) = $vectorEgreso;				
		list($totalAnio,$totalMese,$totalDias) = TIEMPO_DE_SERVICIO(formatFechaDMA($field_a['FechaDesde']), formatFechaDMA($field_a['FechaHasta']));
	
		$sumaMes += $totalMese;
		$sumaDias += $totalDias;
		$sumaAnio+=$totalAnio;
	}
	
	$sumaMes = $sumaMes + intval($sumaDias/30);
	$sumaDias = $sumaDias%30;
	 
	$sumaAnio = $sumaAnio + intval($sumaMes/12);
	$sumaMes = $sumaMes%12;
        /*
		$sumaMes += $meses;		$sumaDias += $dias;		$sumaAnio+=$anios;
		$auxAnioDia = $sumaDias/30; 		$sumaMes += $auxAnioDia;		$auxAnioMes = $sumaMes/12;
	    $sumaAnio+$auxAnioMes;
		*/
				
	//	}// condicion del primer anio

	return array ($sumaAnio,$sumaMes,$sumaDias);
}////////////////////



function ANIO_SERVICIO_OTRA_INSTITUCION2($_ARGS)
{
	 $sql = "SELECT *
		FROM rh_empleado_experiencia AS ex
		JOIN mastempleado AS me ON me.CodPersona = ex.CodPersona
		WHERE me.CodPersona = '".$_ARGS['TRABAJADOR']."' and ex.TipoEnte = '02'";

	$query = mysql_query($sql) or die ($sql.mysql_error());

	$totalAnio = 0;
	$sumaAnio = 0;//$_ARGS['TRABAJADOR'];
	$sumaMes = 0;
	$totalMese = 0;
	$sumaDias = 0;
	$totalDias = 0;
	$auxAnioMes = 0;
	$auxAnioDia = 0;

	$fingreso = FECHA_INGRESO($_ARGS);
   $periodo_actual = $_ARGS['HASTA'];
	//list($anios, $meses, $dias) = TIEMPO_DE_SERVICIO(formatFechaDMA($fingreso), formatFechaDMA($periodo_actual));
	
	
	//if ($anios>=1) {
			
		while ($field_a = mysql_fetch_array($query)) {

		$vectorIngreso = split('-',$field_a['FechaDesde']);
		$vectorEgreso = split('-',$field_a['FechaHasta']);

		list($anioIngreso,$mesIngreso,$diaIngreso) = $vectorIngreso;
		list($anioEgreso,$mesEgreso,$diaEgreso) = $vectorEgreso;
		
		
		list($totalAnio,$totalMese,$totalDias) = TIEMPO_DE_SERVICIO(formatFechaDMA($field_a['FechaDesde']), formatFechaDMA($field_a['FechaHasta']));
	

		$sumaMes += $totalMese;
		$sumaDias += $totalDias;
		$sumaAnio+=$totalAnio;
	}
	
	//$fingreso = FECHA_INGRESO($_ARGS);
	//$periodo_actual = $_ARGS['HASTA'];
	
	//list($anios, $meses, $dias) = TIEMPO_DE_SERVICIO(formatFechaDMA($fingreso), formatFechaDMA($periodo_actual));
	
		$sumaMes += $meses;		$sumaDias += $dias;		$sumaAnio+=$anios;
	
	$auxAnioDia = $sumaDias/30; 		$sumaMes += $auxAnioDia;		$auxAnioMes = $sumaMes/12;
	
    $sumaAnio+$auxAnioMes;
		
				
	//	}// condicion del primer anio

	return $sumaAnio+$auxAnioMes;
}

function ANIO_SERVICIO_OTRA_INSTITUCION($_ARGS)
{

	$sql = "SELECT *
		FROM rh_empleado_experiencia AS ex
		JOIN mastempleado AS me ON me.CodPersona = ex.CodPersona
		WHERE me.CodPersona = '".$_ARGS['TRABAJADOR']."' and ex.TipoEnte = '02'";

  //print_r ($_ARGS); 
		/*SELECT *
		FROM rh_empleado_experiencia
		WHERE CodPersona = '".$_ARGS['TRABAJADOR']."'
		AND TipoEnte = '02'"*/


	$query = mysql_query($sql) or die ($sql.mysql_error());

	$totalAnio = 0;
	$sumaAnio = 0;//$_ARGS['TRABAJADOR'];
	$sumaMes = 0;
	$totalMese = 0;
	$sumaDias = 0;
	$totalDias = 0;
	$auxAnioMes = 0;
	$auxAnioDia = 0;
	
	
	
	$fingreso = FECHA_INGRESO($_ARGS);
	$periodo_actual = $_ARGS['HASTA'];
	list($anios, $meses, $dias) = TIEMPO_DE_SERVICIO(formatFechaDMA($fingreso), formatFechaDMA($periodo_actual));
	
	
	if ($anios>=1) {
		
		
		
			while ($field_a = mysql_fetch_array($query)) {

		$vectorIngreso = split('-',$field_a['FechaDesde']);
		$vectorEgreso = split('-',$field_a['FechaHasta']);

		list($anioIngreso,$mesIngreso,$diaIngreso) = $vectorIngreso;
		list($anioEgreso,$mesEgreso,$diaEgreso) = $vectorEgreso;
		
		
		list($totalAnio,$totalMese,$totalDias) = TIEMPO_DE_SERVICIO(formatFechaDMA($field_a['FechaDesde']), formatFechaDMA($field_a['FechaHasta']));
	
	//	$totalAnio = $anioEgreso - $anioIngreso;
	//	$totalMese = $mesEgreso - $mesIngreso;
	//	$totalDias = $diaEgreso - $diaIngreso;

		$sumaMes += $totalMese;
		$sumaDias += $totalDias;
		$sumaAnio+=$totalAnio;

//   echo "<br> ANIO: ".$sumaAnio."  MESES:".$sumaDias."  DIAS:".$dias;
	}
	
	$fingreso = FECHA_INGRESO($_ARGS);
	$periodo_actual = $_ARGS['HASTA'];
	
	list($anios, $meses, $dias) = TIEMPO_DE_SERVICIO(formatFechaDMA($fingreso), formatFechaDMA($periodo_actual));
  //  echo "<br> ANIO: ".$anios."  MESES:".$meses."  DIAS:".$dias;
	
	
		$sumaMes += $meses;
		$sumaDias += $dias;
		$sumaAnio+=$anios;
	
	$auxAnioDia = $sumaDias/30;
	
	$sumaMes += $auxAnioDia;
	
	$auxAnioMes = $sumaMes/12;
	
    $sumaAnio+$auxAnioMes;
		
		
		
		}// condicion del primer anio



  
	return $sumaAnio+$auxAnioMes;


}
//

function TOTAL_ANIOS_SERVICIO($_ARGS)
{
$total=0;

$a= ANIO_SERVICIO_OTRA_INSTITUCION($_ARGS);
//$b= ANIOS_DE_SERVICIO($_ARGS);

$total = $a ;

return $total ;
}
//
function ULTIMA_ALICUOTA_VACACIONAL($_ARGS) {
	$_PARAMETROS = PARAMETROS();
	$sql = "SELECT Monto
			FROM pr_tiponominaempleadoconcepto
			WHERE
				CodOrganismo = '".$_ARGS['ORGANISMO']."' AND
				CodPersona = '".$_ARGS['TRABAJADOR']."' AND
				CodTipoProceso = 'FIN' AND
				CodConcepto = '".$_PARAMETROS["ALIVAC"]."' AND
				Periodo < '".$_ARGS['PERIODO']."'
			ORDER BY Periodo DESC
			LIMIT 0, 1";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return $field['Monto'];
}

//
function ULTIMA_ALICUOTA_FIN($_ARGS) {
	$_PARAMETROS = PARAMETROS();
	$sql = "SELECT Monto
			FROM pr_tiponominaempleadoconcepto
			WHERE
				CodOrganismo = '".$_ARGS['ORGANISMO']."' AND
				CodPersona = '".$_ARGS['TRABAJADOR']."' AND
				CodTipoProceso = 'FIN' AND
				CodConcepto = '".$_PARAMETROS["ALIFIN"]."' AND
				Periodo < '".$_ARGS['PERIODO']."'
			ORDER BY Periodo DESC
			LIMIT 0, 1";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return $field['Monto'];
}

//
function SUELDO_NORMAL($_ARGS) {
	 $sql = "SELECT TotalIngresos 
			FROM pr_tiponominaempleado 
			WHERE
				CodOrganismo = '".$_ARGS['ORGANISMO']."'  AND
				--	CodTipoNom = '".$_ARGS['NOMINA']."'  AND
				CodPersona = '".$_ARGS['TRABAJADOR']."'  AND
				CodTipoProceso = 'FIN'  AND
				Periodo = '".$_ARGS['PERIODO']."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return $field['TotalIngresos'];
}

//
function SUELDO_NORMAL_DIARIO($_ARGS) {
	$sql = "SELECT TotalIngresos 
			FROM pr_tiponominaempleado 
			WHERE
				CodOrganismo = '".$_ARGS['ORGANISMO']."'  AND
				--	CodTipoNom = '".$_ARGS['NOMINA']."'  AND
				CodPersona = '".$_ARGS['TRABAJADOR']."'  AND
				CodTipoProceso = 'FIN'  AND
				Periodo = '".$_ARGS['PERIODO']."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	$monto = $field['TotalIngresos'] / 30;
	return number_format($monto, 2, '.', '');
}

//
function SUELDO_BASICO_NETO($_ARGS) {
	$sql = "SELECT SueldoActual
			FROM mastempleado 
			WHERE CodPersona = '".$_ARGS['TRABAJADOR']."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return number_format($field['SueldoActual'], 2, '.', '');
}

//	FUNCION QUE OBTIENE LA REMUNERAION DIARIA DE UN TRABAJADOR PARA UN PERIODO
function REMUNERACION_DIARIA($_ARGS) {
	$sueldo_normal_diario = REDONDEO(($_ARGS['ASIGNACIONES']/30), 2);
	$sql = "SELECT SUM(tnec.Monto) AS Monto
			FROM
				pr_tiponominaempleadoconcepto tnec
				INNER JOIN pr_concepto c ON (tnec.CodConcepto = c.CodConcepto AND
											 c.Tipo = 'I' AND
											 c.FlagBonoRemuneracion = 'S')
			WHERE
				tnec.CodPersona = '".$_ARGS['TRABAJADOR']."' AND
				tnec.Periodo = '".$_ARGS['PERIODO']."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	$remuneracion_diaria = REDONDEO(($field['Monto']/30), 2);
	$monto = $sueldo_normal_diario + $remuneracion_diaria;
	return $monto;
}

//	
function BONOS($_ARGS) {
	$sql = "SELECT SUM(tnec.Monto) AS Bonos
			FROM
				pr_concepto c
				INNER JOIN pr_tiponominaempleadoconcepto tnec ON (tnec.CodConcepto = c.CodConcepto)
			WHERE
				c.FlagBonoRemuneracion = 'S' AND
				--	tnec.CodTipoNom = '".$_ARGS['NOMINA']."' AND
				tnec.Periodo = '".$_ARGS['PERIODO']."' AND
				tnec.CodPersona = '".$_ARGS['TRABAJADOR']."' AND
				tnec.CodOrganismo = '".$_ARGS['ORGANISMO']."'";
	$query = mysql_query($sql) or die ($sql.mysql_error());
	if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
	return floatval($field['Bonos']);
}
?>
