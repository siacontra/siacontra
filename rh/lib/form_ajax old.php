<?php
session_start();
include("../../lib/fphp.php");
include("fphp.php");
	$__archivo = fopen("query.sql", "w+");
//	fwrite($__archivo, $sql.";\n\n");
///////////////////////////////////////////////////////////////////////////////
//	PARA AJAX
///////////////////////////////////////////////////////////////////////////////
//	desrrollo de carreras y sucesion
if ($modulo == "desarrollo_carreras") {
	//	nuevo registro
	if ($accion== "nuevo") {
		mysql_query("BEGIN");
		//	-------------------
		//	valido
		$sql = "SELECT *
				FROM rh_asociacioncarreras
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					CodPersona = '".$CodPersona."' AND
					Secuencia = '".$Secuencia."'";
		$query = mysql_query($sql) or die($sql.mysql_error());
		if (mysql_num_rows($query) != 0) die("Ya existe un registro del empleado con la Evaluaci&oacute;n seleccionada");
	
		//	genero codigo
		$Codigo = getCodigo_3("rh_asociacioncarreras", "Codigo", "CodOrganismo", "Secuencia", $CodOrganismo, $Secuencia, 4);
		
		//	inserto
		$sql = "INSERT INTO rh_asociacioncarreras
				SET
					CodOrganismo = '".$CodOrganismo."',
					Secuencia = '".$Secuencia."',
					Codigo = '".$Codigo."',
					CodPersona = '".$CodPersona."',
					CodCargo = '".$CodCargo."',
					DescripCargo = '".changeUrl($DescripCargo)."',
					CodDependencia = '".$CodDependencia."',
					Periodo = '".$Periodo."',
					IniciadoPor = '".$IniciadoPor."',
					FechaIniciado = '".formatFechaAMD($FechaIniciado)."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		echo "|$Codigo";
		//	-------------------
		mysql_query("COMMIT");
	}
	
	//	actualizar registro
	elseif ($accion== "modificar") {
		mysql_query("BEGIN");
		//	-------------------
		//	capacitacion tecnica
		$sql = "DELETE FROM rh_asociacioncarrerascaptecnica
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					Secuencia = '".$Secuencia."' AND
					Codigo = '".$Codigo."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		$_Linea = 0;
		$captecnica = split(";char:tr;", $detalles_captecnica);
		foreach ($captecnica as $_Descripcion) {
			//	inserto
			$sql = "INSERT INTO rh_asociacioncarrerascaptecnica
					SET
						CodOrganismo = '".$CodOrganismo."',
						Secuencia = '".$Secuencia."',
						Codigo = '".$Codigo."',
						Linea = '".++$_Linea."',
						Descripcion = '".changeUrl($_Descripcion)."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	habilidades
		$sql = "DELETE FROM rh_asociacioncarrerashabilidad
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					Secuencia = '".$Secuencia."' AND
					Codigo = '".$Codigo."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		$_Linea = 0;
		$habilidad = split(";char:tr;", $detalles_habilidad);
		foreach ($habilidad as $_detalle) {
			list($_Tipo, $_Descripcion) = split(";char:td;", $_detalle);
			//	inserto
			$sql = "INSERT INTO rh_asociacioncarrerashabilidad
					SET
						CodOrganismo = '".$CodOrganismo."',
						Secuencia = '".$Secuencia."',
						Codigo = '".$Codigo."',
						Linea = '".++$_Linea."',
						Tipo = '".$_Tipo."',
						Descripcion = '".changeUrl($_Descripcion)."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	evaluaciones
		$sql = "DELETE FROM rh_asociacioncarrerasevaluacion
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					Secuencia = '".$Secuencia."' AND
					Codigo = '".$Codigo."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		$_Linea = 0;
		$evaluacion = split(";char:tr;", $detalles_evaluacion);
		foreach ($evaluacion as $_Descripcion) {
			//	inserto
			$sql = "INSERT INTO rh_asociacioncarrerasevaluacion
					SET
						CodOrganismo = '".$CodOrganismo."',
						Secuencia = '".$Secuencia."',
						Codigo = '".$Codigo."',
						Linea = '".++$_Linea."',
						Descripcion = '".changeUrl($_Descripcion)."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	metas
		$sql = "DELETE FROM rh_asociacioncarrerasmetas
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					Secuencia = '".$Secuencia."' AND
					Codigo = '".$Codigo."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		$_Linea = 0;
		$metas = split(";char:tr;", $detalles_metas);
		foreach ($metas as $_Descripcion) {
			//	inserto
			$sql = "INSERT INTO rh_asociacioncarrerasmetas
					SET
						CodOrganismo = '".$CodOrganismo."',
						Secuencia = '".$Secuencia."',
						Codigo = '".$Codigo."',
						Linea = '".++$_Linea."',
						Descripcion = '".changeUrl($_Descripcion)."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		//	-------------------
		mysql_query("COMMIT");
	}
	
	//	terminar
	elseif ($accion== "terminar") {
		mysql_query("BEGIN");
		//	-------------------
		//	actualizo
		$sql = "UPDATE rh_asociacioncarreras
				SET
					TerminadoPor = '".$TerminadoPor."',
					FechaTerminado = '".formatFechaAMD($FechaTerminado)."',
					Estado = 'TE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		echo "|$Codigo";	die();
		//	-------------------
		mysql_query("COMMIT");
	}
}

//	solicitud de vacaciones
elseif ($modulo == "vacaciones") {
	//	nuevo registro
	if ($accion== "nuevo") {
		//	valido
		$sql = "SELECT * FROM rh_vacacionsolicitud WHERE CodPersona = '".$CodPersona."' AND Estado = 'PE'";
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query) != 0) die("Existe una solicitud pendiente para este trabajador");
		
		//	genero codigo
		$CodSolicitud = getCodigo_2("rh_vacacionsolicitud", "CodSolicitud", "Anio", $Anio, 6);
		
		//	inserto
		$sql = "INSERT INTO rh_vacacionsolicitud
				SET
					Anio = '".$Anio."',
					CodSolicitud = '".$CodSolicitud."',
					CodPersona = '".$CodPersona."',
					Tipo = '".$Tipo."',
					Fecha = '".formatFechaAMD($Fecha)."',
					Periodo = '".$Periodo."',
					FechaSalida = '".formatFechaAMD($FechaSalida)."',
					FechaTermino = '".formatFechaAMD($FechaTermino)."',
					NroDias = '".setNumero($NroDias)."',
					FechaIncorporacion = '".formatFechaAMD($FechaIncorporacion)."',
					Motivo = '".changeUrl($Motivo)."',
					CreadoPor = '".$CreadoPor."',
					FechaCreacion = NOW(),
					CodOrganismo = '".$CodOrganismo."',
					CodDependencia = '".$CodDependencia."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	periodos vacacionales
		$_Secuencia = 0;
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			list($_NroPeriodo, $_FlagUtilizarPeriodo, $_NroDias, $_FechaInicio, $_FechaFin, $_DiasDerecho, $_DiasUsados, $_DiasPendientes, $_Observaciones, $_SecuenciaLinea) = split(";char:td;", $linea);
			$_FechaIncorporacion = getFechaFinHabiles($_FechaFin, 2);
			//	inserto
			$sql = "INSERT INTO rh_vacacionsolicituddetalle
					SET
						Anio = '".$Anio."',
						CodSolicitud = '".$CodSolicitud."',
						Secuencia = '".++$_Secuencia."',
						CodPersona = '".$CodPersona."',
						NroPeriodo = '".$_NroPeriodo."',
						Periodo = '".$Periodo."',
						FechaInicio = '".formatFechaAMD($_FechaInicio)."',
						FechaFin = '".formatFechaAMD($_FechaFin)."',
						FechaIncorporacion = '".formatFechaAMD($_FechaIncorporacion)."',
						NroDias = '".setNumero($_NroDias)."',
						DiasDerecho = '".setNumero($_DiasDerecho)."',
						DiasPendientes = '".setNumero($_DiasPendientes)."',
						DiasUsados = '".setNumero($_DiasUsados)."',
						FlagUtilizarPeriodo = '".$_FlagUtilizarPeriodo."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		echo "|$Anio-$CodSolicitud";
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		if ($Estado == "AP") {
			//	modifico
			$sql = "UPDATE rh_vacacionsolicitud
					SET
						Documento = '".changeUrl($Documento)."',
						Observaciones = '".changeUrl($Observaciones)."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						Anio = '".$Anio."' AND
						CodSolicitud = '".$CodSolicitud."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	periodos vacacionales
			$_Secuencia = 0;
			$detalle = split(";char:tr;", $detalles);
			foreach ($detalle as $linea) {
				list($_NroPeriodo, $_FlagUtilizarPeriodo, $_NroDias, $_FechaInicio, $_FechaFin, $_DiasDerecho, $_DiasUsados, $_DiasPendientes, $_Observaciones, $_SecuenciaLinea) = split(";char:td;", $linea);
				//	inserto
				$sql = "UPDATE rh_vacacionsolicituddetalle
						SET
							Observaciones = '".changeUrl($_Observaciones)."',
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()
						WHERE
							Anio = '".$Anio."' AND
							CodSolicitud = '".$CodSolicitud."' AND
							Secuencia = '".$_SecuenciaLinea."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		else {
			//	modifico
			$sql = "UPDATE rh_vacacionsolicitud
					SET
						FechaSalida = '".formatFechaAMD($FechaSalida)."',
						FechaTermino = '".formatFechaAMD($FechaTermino)."',
						NroDias = '".setNumero($NroDias)."',
						FechaIncorporacion = '".formatFechaAMD($FechaIncorporacion)."',
						Motivo = '".changeUrl($Motivo)."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						Anio = '".$Anio."' AND
						CodSolicitud = '".$CodSolicitud."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	periodos vacacionales
			$sql = "DELETE FROM rh_vacacionsolicituddetalle
					WHERE
						Anio = '".$Anio."' AND
						CodSolicitud = '".$CodSolicitud."'";
			$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			$_Secuencia = 0;
			$detalle = split(";char:tr;", $detalles);
			foreach ($detalle as $linea) {
				list($_NroPeriodo, $_FlagUtilizarPeriodo, $_NroDias, $_FechaInicio, $_FechaFin, $_DiasDerecho, $_DiasUsados, $_DiasPendientes, $_Observaciones) = split(";char:td;", $linea);
				$_FechaIncorporacion = getFechaFinHabiles($_FechaFin, 2);
				//	inserto
				$sql = "INSERT INTO rh_vacacionsolicituddetalle
						SET
							Anio = '".$Anio."',
							CodSolicitud = '".$CodSolicitud."',
							Secuencia = '".++$_Secuencia."',
							CodPersona = '".$CodPersona."',
							NroPeriodo = '".$_NroPeriodo."',
							Periodo = '".$Periodo."',
							FechaInicio = '".formatFechaAMD($_FechaInicio)."',
							FechaFin = '".formatFechaAMD($_FechaFin)."',
							FechaIncorporacion = '".formatFechaAMD($_FechaIncorporacion)."',
							NroDias = '".setNumero($_NroDias)."',
							DiasDerecho = '".setNumero($_DiasDerecho)."',
							DiasPendientes = '".setNumero($_DiasPendientes)."',
							DiasUsados = '".setNumero($_DiasUsados)."',
							FlagUtilizarPeriodo = '".$_FlagUtilizarPeriodo."',
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
	}
	
	//	revisar registro
	elseif ($accion== "revisar") {
		//	modifico
		$sql = "UPDATE rh_vacacionsolicitud
				SET
					Estado = 'RV',
					RevisadoPor = '".$RevisadoPor."',
					FechaRevision = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodSolicitud = '".$CodSolicitud."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	conformar registro
	elseif ($accion== "conformar") {
		//	modifico
		$sql = "UPDATE rh_vacacionsolicitud
				SET
					Estado = 'CO',
					Documento = '".$Documento."',
					ConformadoPor = '".$ConformadoPor."',
					FechaConformacion = NOW(),
					Observaciones = '".changeUrl($Observaciones)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodSolicitud = '".$CodSolicitud."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	periodos vacacionales
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			list($_NroPeriodo, $_FlagUtilizarPeriodo, $_NroDias, $_FechaInicio, $_FechaFin, $_DiasDerecho, $_DiasUsados, $_DiasPendientes, $_Observaciones, $_SecuenciaLinea) = split(";char:td;", $linea);
			//	actualizo observaciones
			$sql = "UPDATE rh_vacacionsolicituddetalle
					SET
						Observaciones = '".changeUrl($_Observaciones)."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()
					WHERE
						Anio = '".$Anio."' AND
						CodSolicitud = '".$CodSolicitud."' AND
						Secuencia = '".$_SecuenciaLinea."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
	}
	
	//	aprobar registro
	elseif ($accion== "aprobar") {
		//	consulto empleado
		$sql = "SELECT CodTipoNom FROM mastempleado WHERE CodPersona = '".$CodPersona."'" ;
		$query_empleado = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_empleado) != 0) $field_empleado = mysql_fetch_array($query_empleado);
		
		//	genero codigo
		$NroOtorgamiento = getCodigo_2("rh_vacacionsolicitud", "NroOtorgamiento", "Anio", $Anio, 6);
		
		//	actualizo solicitud
		$sql = "UPDATE rh_vacacionsolicitud
				SET
					NroOtorgamiento = '".$NroOtorgamiento."',
					Estado = 'AP',
					AprobadoPor = '".$AprobadoPor."',
					FechaAprobacion = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodSolicitud = '".$CodSolicitud."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	periodos vacacionales
		$detalle = split(";char:tr;", $detalles);
		foreach ($detalle as $linea) {
			list($_NroPeriodo, $_FlagUtilizarPeriodo, $_NroDias, $_FechaInicio, $_FechaFin, $_DiasDerecho, $_DiasUsados, $_DiasPendientes, $_Observaciones) = split(";char:td;", $linea);			
			//	inserto utilizacion
			$Secuencia = getCodigo_2("rh_vacacionutilizacion", "Secuencia", "CodPersona", $CodPersona, 2);
			$Secuencia = intval($Secuencia);
			$sql = "INSERT INTO rh_vacacionutilizacion
					SET
						Secuencia = '".$Secuencia."',
						CodPersona = '".$CodPersona."',
						NroPeriodo = '".$_NroPeriodo."',
						CodTipoNom = '".$field_empleado['CodTipoNom']."',
						FechaInicio = '".formatFechaAMD($_FechaInicio)."',
						FechaFin = '".formatFechaAMD($_FechaFin)."',
						TipoUtilizacion = '".$Tipo."',
						DiasUtiles = '".setNumero($_NroDias)."',
						Anio = '".$Anio."',
						CodSolicitud = '".$CodSolicitud."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	consulto pendientes anteriores
			$sql = "SELECT Pendientes
					FROM rh_vacacionperiodo
					WHERE
						CodPersona = '".$CodPersona."' AND
						NroPeriodo = '".($_NroPeriodo-1)."'";
			$query_pendientes = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if (mysql_num_rows($query_pendientes) != 0) $field_pendientes = mysql_fetch_array($query_pendientes);
			
			$_Pendientes += intval(setNumero($_NroDias));
			//	actualizo periodo
			$sql = "UPDATE rh_vacacionperiodo
					SET
						PendientePeriodo = '".intval($field_pendientes['Pendientes'])."',
						DiasGozados = (DiasGozados + ".intval(setNumero($_NroDias))."),
						TotalUtilizados = (TotalUtilizados + ".intval(setNumero($_NroDias))."),
						Pendientes = (Pendientes - ".intval($_Pendientes)."),
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						CodPersona = '".$CodPersona."' AND
						NroPeriodo = '".$_NroPeriodo."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
	}
	
	//	anular registro
	elseif ($accion== "anular") {
		if ($Estado == "AP") {
			$EstadoNuevo = "PE";
			##	consulto empleado
			$sql = "SELECT pt.Grado
					FROM
						mastempleado e
						INNER JOIN rh_puestos pt ON (pt.CodCargo = pt.CodCargo)
					WHERE e.CodPersona = '".$CodPersona."'";
			$query_empleado = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if (mysql_num_rows($query_empleado) != 0) $field_empleado = mysql_fetch_array($query_empleado);
			
			##	elimino de utilizacion
			$sql = "DELETE FROM rh_vacacionutilizacion
					WHERE
						Anio = '".$Anio."' AND
						CodSolicitud = '".$CodSolicitud."'";
			$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			##	actualizo periodos vacacionales
			$sql = "SELECT *
					FROM rh_vacacionperiodo
					WHERE CodPersona = '".$CodPersona."'
					ORDER BY NroPeriodo";
			$query_periodos = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while($field_periodos = mysql_fetch_array($query_periodos)) {
				##	consulto pendientes anteriores
				$sql = "SELECT Pendientes
						FROM rh_vacacionperiodo
						WHERE
							CodPersona = '".$field_periodos['CodPersona']."' AND
							NroPeriodo = '".($field_periodos['NroPeriodo']-1)."'";
				$query_pendientes_ant = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_pendientes_ant) != 0) $field_pendientes_ant = mysql_fetch_array($query_pendientes_ant);
				##	consulto gozados
				$sql = "SELECT SUM(DiasUtiles) AS DiasGozados
						FROM rh_vacacionutilizacion
						WHERE
							CodPersona = '".$field_periodos['CodPersona']."' AND
							NroPeriodo = '".$field_periodos['NroPeriodo']."' AND
							TipoUtilizacion = 'G'";
				$query_gozados = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_gozados) != 0) $field_gozados = mysql_fetch_array($query_gozados);
				##	consulto interrumpidos
				$sql = "SELECT SUM(DiasUtiles) AS DiasInterrumpidos
						FROM rh_vacacionutilizacion
						WHERE
							CodPersona = '".$field_periodos['CodPersona']."' AND
							NroPeriodo = '".$field_periodos['NroPeriodo']."' AND
							TipoUtilizacion = 'I'";
				$query_interrumpidos = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_interrumpidos) != 0) $field_interrumpidos = mysql_fetch_array($query_interrumpidos);
				##	consulto pendientes anteriores
				$sql = "SELECT PendientePago
						FROM rh_vacacionperiodo
						WHERE
							CodPersona = '".$field_periodos['CodPersona']."' AND
							NroPeriodo = '".($field_periodos['NroPeriodo']-1)."'";
				$query_pendientes_pago_ant = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_pendientes_pago_ant) != 0) $field_pendientes_pago_ant = mysql_fetch_array($query_pendientes_pago_ant);
				##	consulto pagados
				$sql = "SELECT SUM(DiasPago) AS PagosRealizados
						FROM rh_vacacionpago
						WHERE
							CodPersona = '".$field_periodos['CodPersona']."' AND
							NroPeriodo = '".$field_periodos['NroPeriodo']."'";
				$query_pagados = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_pagados) != 0) $field_pagados = mysql_fetch_array($query_pagados);
				##	calculo dias
				$Pendientes = $field_periodos['Derecho'] + $field_pendientes_ant['Pendientes'] - $field_gozados['DiasGozados'] + $field_interrumpidos['DiasInterrumpidos'];
				if ($field_empleado['Grado'] == "99") $DiasPago = $_PARAMETRO["PAGOFINDC"]; else $DiasPago = $_PARAMETRO["PAGOVACA"];
				$PendientePago = $DiasPago + $field_pendientes_pago_ant['PendientePago'] - $field_pagados['PagosRealizados'];
				##	actualizo
				$sql = "UPDATE rh_vacacionperiodo
						SET
							PendientePeriodo = '".$field_pendientes_ant['Pendientes']."',
							DiasGozados = '".$field_gozados['DiasGozados']."',
							DiasInterrumpidos = '".$field_interrumpidos['DiasInterrumpidos']."',
							TotalUtilizados = '".$field_gozados['DiasGozados']."',
							Pendientes = '".$Pendientes."',
							PagosRealizados = '".$field_pagados['PagosRealizados']."',
							PendientePago = '".$PendientePago."'
						WHERE
							CodPersona = '".$field_periodos['CodPersona']."' AND
							NroPeriodo = '".$field_periodos['NroPeriodo']."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		elseif ($Estado == "RV") $EstadoNuevo = "PE";
		elseif ($Estado == "CO") $EstadoNuevo = "PE";
		elseif ($Estado == "PE") $EstadoNuevo = "AN";
		
		//	modifico
		$sql = "UPDATE rh_vacacionsolicitud
				SET
					Estado = '".$EstadoNuevo."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodSolicitud = '".$CodSolicitud."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	requerimientos
elseif ($modulo == "requerimientos") {
	//	nuevo registro
	if ($accion== "nuevo") {
		//	genero codigo
		$Requerimiento = getCodigo_2("rh_requerimiento", "Requerimiento", "CodOrganismo", $CodOrganismo, 6);
		
		//	inserto
		$sql = "INSERT INTO rh_requerimiento
				SET
					Requerimiento = '".$Requerimiento."',
					FechaSolicitud = NOW(),
					NumeroSolicitado = '".$NumeroSolicitado."',
					NumeroPendiente = '".$NumeroSolicitado."',
					CodDependencia = '".$CodDependencia."',
					CodOrganismo = '".$CodOrganismo."',
					CodPersona = '".$CodPersona."',
					Modalidad = '".$Modalidad."',
					VigenciaInicio = '".formatFechaAMD($VigenciaInicio)."',
					VigenciaFin = '".formatFechaAMD($VigenciaFin)."',
					CodCargo = '".$CodCargo."',
					Motivo = '".$Motivo."',
					TipoContrato = '".$TipoContrato."',
					FechaDesde = '".formatFechaAMD($FechaDesde)."',
					FechaHasta = '".formatFechaAMD($FechaHasta)."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	evaluaciones
		if ($evaluacion != "") {
			$detalles_evaluacion = split(";char:tr;", $evaluacion);
			foreach ($detalles_evaluacion as $linea) {
				list($_Secuencia, $_Evaluacion, $_Etapa, $_PlantillaEvaluacion) = split(";char:td;", $linea);
				//	inserto
				$sql = "INSERT INTO rh_requerimientoeval
						SET
							Requerimiento = '".$Requerimiento."',
							CodOrganismo = '".$CodOrganismo."',
							Secuencia = '".$_Secuencia."',
							Evaluacion = '".$_Evaluacion."',
							Etapa = '".$_Etapa."',
							PlantillaEvaluacion = '".$_PlantillaEvaluacion."',
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		echo "|$Requerimiento";
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		//	actualizo
		$sql = "UPDATE rh_requerimiento
				SET
					NumeroSolicitado = '".$NumeroSolicitado."',
					NumeroPendiente = '".$NumeroSolicitado."',
					CodDependencia = '".$CodDependencia."',
					Modalidad = '".$Modalidad."',
					VigenciaInicio = '".formatFechaAMD($VigenciaInicio)."',
					VigenciaFin = '".formatFechaAMD($VigenciaFin)."',
					Motivo = '".$Motivo."',
					TipoContrato = '".$TipoContrato."',
					FechaDesde = '".formatFechaAMD($FechaDesde)."',
					FechaHasta = '".formatFechaAMD($FechaHasta)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					Requerimiento = '".$Requerimiento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	evaluaciones
		$sql = "DELETE FROM rh_requerimientoeval
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					Requerimiento = '".$Requerimiento."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if ($evaluacion != "") {
			$detalles_evaluacion = split(";char:tr;", $evaluacion);
			foreach ($detalles_evaluacion as $linea) {
				list($_Secuencia, $_Evaluacion, $_Etapa, $_PlantillaEvaluacion) = split(";char:td;", $linea);
				//	inserto
				$sql = "INSERT INTO rh_requerimientoeval
						SET
							Requerimiento = '".$Requerimiento."',
							CodOrganismo = '".$CodOrganismo."',
							Secuencia = '".$_Secuencia."',
							Evaluacion = '".$_Evaluacion."',
							Etapa = '".$_Etapa."',
							PlantillaEvaluacion = '".$_PlantillaEvaluacion."',
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
	}
	
	//	aprobar registro
	elseif ($accion== "aprobar") {
		//	actualizo
		$sql = "UPDATE rh_requerimiento
				SET
					Estado = 'AP',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					Requerimiento = '".$Requerimiento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	terminar registro
	elseif ($accion== "finalizar") {
		//	actualizo
		$sql = "UPDATE rh_requerimiento
				SET
					Estado = 'TE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$CodOrganismo."' AND
					Requerimiento = '".$Requerimiento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	insertar candidato
	elseif ($accion== "insertar-candidato") {
		//	inicio transaccion
		mysql_query("BEGIN");
		
		//	inserto
		$sql = "INSERT INTO rh_requerimientopost
				SET
					Requerimiento = '".$Requerimiento."',
					CodOrganismo = '".$CodOrganismo."',
					TipoPostulante = '".$TipoPostulante."',
					Postulante = '".$Postulante."',
					Estado = 'P',
					Puntaje = '0.00',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto evaluaciones
		$sql = "INSERT INTO rh_requerimientoevalpost (
							CodOrganismo,
							Requerimiento,
							TipoPostulante,
							Postulante,
							Secuencia,
							Calificativo,
							FlagAprobacion,
							UltimoUsuario,
							UltimaFecha
				)
						SELECT
							CodOrganismo,
							Requerimiento,
							'".$TipoPostulante."' AS TipoPostulante,
							'".$Postulante."' AS Postulante,
							Secuencia,
							'0.00' AS Calificativo,
							'N' AS FlagAprobacion,
							'".$_SESSION["USUARIO_ACTUAL"]."' AS UltimoUsuario,
							NOW() AS UltimaFecha
						FROM rh_requerimientoeval
						WHERE
							CodOrganismo = '".$CodOrganismo."' AND
							Requerimiento = '".$Requerimiento."'";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		if ($TipoPostulante == "I") {
			//	consulto la persona
			$sql = "SELECT
						p.CodPersona,
						p.NomCompleto,
						e.CodEmpleado
					FROM
						mastpersonas p
						INNER JOIN mastempleado e ON (e.CodPersona = p.CodPersona)
					WHERE p.CodPersona = '".$Postulante."'";
		}
		elseif ($TipoPostulante == "E") {
			//	consulto la persona
			$sql = "SELECT
						p.Postulante AS CodPersona,
						CONCAT(p.Nombres, ' ', p.Apellido1, ' ', p.Apellido2) AS NomCompleto,
						p.Postulante AS CodEmpleado
					FROM rh_postulantes p
					WHERE p.Postulante = '".$Postulante."'";
		}
		$query_candidato = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_candidato) != 0) $field_candidato = mysql_fetch_array($query_candidato);
		
		//	tr a insertar en la lista
		echo "|";
		?>
        <tr class="trListaBody" onclick="mClk(this, 'sel_candidato'); document.getElementById('frm_candidato').submit();" id="candidato_<?=$TipoPostulante.$field_candidato['CodPersona']?>">
            <th>
                <?=$nro_candidato?>
            </th>
            <td align="center">
                <input type="hidden" name="TipoPostulante" value="<?=$TipoPostulante?>" />
                <input type="hidden" name="Postulante" value="<?=$field_candidato['CodPersona']?>" />
                <?=$TipoPostulante.$field_candidato['CodEmpleado']?>
            </td>
            <td>
                <?=$field_candidato['NomCompleto']?>
            </td>
            <td>
                <input type="text" name="Puntaje" class="cell2" style="text-align:right;" value="0,00" readonly="readonly" />
            </td>
            <td>
                <?=printValores("ESTADO-POSTULANTE", "P")?>
            </td>
        </tr>
        <?
		
		mysql_query("COMMIT");
		//	fin transaccion
	}
	
	//	borrar candidato
	elseif ($accion== "borrar-candidato") {
		//	inicio transaccion
		mysql_query("BEGIN");
		
		$TipoPostulante = substr($seldetalle, 10, 1);
		$Postulante = substr($seldetalle, 11, 6);
		
		//	elimino
		$sql = "DELETE FROM rh_requerimientoevalpost
				WHERE
					Requerimiento = '".$Requerimiento."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					TipoPostulante = '".$TipoPostulante."' AND
					Postulante = '".$Postulante."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto evaluaciones
		$sql = "DELETE FROM rh_requerimientopost
				WHERE
					Requerimiento = '".$Requerimiento."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					TipoPostulante = '".$TipoPostulante."' AND
					Postulante = '".$Postulante."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		mysql_query("COMMIT");
		//	fin transaccion
	}
	
	//	regitrar puntaje competencia
	elseif ($accion== "registrar-puntaje") {
		//	inicio transaccion
		mysql_query("BEGIN");
		
		//	actualizo estado del requerimiento
		$sql = "UPDATE rh_requerimiento
				SET Estado = 'EE'
				WHERE
					Requerimiento = '".$Requerimiento."' AND
					CodOrganismo = '".$CodOrganismo."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	competencias
		$sql = "DELETE FROM rh_requerimientocomp
				WHERE
					Requerimiento = '".$Requerimiento."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					TipoPostulante = '".$TipoPostulante."' AND
					Postulante = '".$Postulante."' AND
					Evaluacion = '".$Evaluacion."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if ($competencias != "") {
			$_Secuencia = 0;
			$detalles_competencias = split(";char:tr;", $competencias);
			foreach ($detalles_competencias as $linea) {
				list($Registro, $Puntaje) = split(";char:td;", $linea);
				list($C, $Competencia, $P) = split("[_]", $Registro);
				//	inserto
				$sql = "INSERT INTO rh_requerimientocomp
						SET
							Requerimiento = '".$Requerimiento."',
							CodOrganismo = '".$CodOrganismo."',
							TipoPostulante = '".$TipoPostulante."',
							Postulante = '".$Postulante."',
							Evaluacion = '".$Evaluacion."',
							Competencia = '".$Competencia."',
							Puntaje = '".$Puntaje."',
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				$TotalPuntaje += $Puntaje;
				++$_Secuencia;
			}
		}
		
		//	consulto nro de competencias
		$sql = "SELECT COUNT(*) AS Nro FROM rh_evaluacionitems WHERE Evaluacion = '".$Evaluacion."'";
		$query_count = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_count) != 0) $field_count = mysql_fetch_array($query_count);
		
		//	actualizar calificativo de la evaluacion
		$Calificativo = $TotalPuntaje / $field_count['Nro'];
		$sql = "UPDATE rh_requerimientoevalpost
				SET
					Calificativo = '".$Calificativo."',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					Requerimiento = '".$Requerimiento."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					TipoPostulante = '".$TipoPostulante."' AND
					Postulante = '".$Postulante."' AND
					Secuencia = '".$Secuencia."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo puntaje del postulante
		$sql = "SELECT
					COUNT(*) AS Nro,
					(SELECT SUM(Calificativo)
					 FROM rh_requerimientoevalpost
					 WHERE
						Requerimiento = rep.Requerimiento AND
						CodOrganismo = rep.CodOrganismo AND
						TipoPostulante = rep.TipoPostulante AND
						Postulante = rep.Postulante) AS Calificativo
				FROM rh_requerimientoevalpost rep
				WHERE
					Requerimiento = '".$Requerimiento."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					TipoPostulante = '".$TipoPostulante."' AND
					Postulante = '".$Postulante."'";
		$query_eval = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_eval) != 0) $field_eval = mysql_fetch_array($query_eval);
		
		//	actualizar calificativo de la evaluacion
		$Puntaje = $field_eval['Calificativo'] / $field_eval['Nro'];
		$sql = "UPDATE rh_requerimientopost
				SET
					Puntaje = '".$Puntaje."',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					Requerimiento = '".$Requerimiento."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					TipoPostulante = '".$TipoPostulante."' AND
					Postulante = '".$Postulante."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	fin transaccion
		mysql_query("COMMIT");
	}
	
	//	aprobar candidato
	elseif ($accion== "aprobar-candidato") {
		//	inicio transaccion
		mysql_query("BEGIN");
		
		//	valido
		$sql = "SELECT Estado, Puntaje
				FROM rh_requerimientopost
				WHERE
					Requerimiento = '".$Requerimiento."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					TipoPostulante = '".$TipoPostulante."' AND
					Postulante = '".$Postulante."'";
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
		if ($field['Estado'] == "A") die("El Candidato ya encuentra en Estado Aprobado");
		else if ($field['Estado'] == "C") die("El Candidato ya encuentra en Estado Aprobado");
		else if ($field['Puntaje'] == 0) die("No puede Aprobar un Candidato sin Puntaje");
		
		//	actualizo estado del requerimiento del postulante
		$sql = "UPDATE rh_requerimientopost
				SET Estado = 'A'
				WHERE
					Requerimiento = '".$Requerimiento."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					TipoPostulante = '".$TipoPostulante."' AND
					Postulante = '".$Postulante."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo estado de la evaluacion del requerimiento del postulante
		$sql = "UPDATE rh_requerimientoevalpost
				SET FlagAprobacion = 'S'
				WHERE
					Requerimiento = '".$Requerimiento."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					TipoPostulante = '".$TipoPostulante."' AND
					Postulante = '".$Postulante."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo estado del postulante
		if ($TipoPostulante == "E") {
			$sql = "UPDATE rh_postulantes SET Estado = 'A' WHERE Postulante = '".$Postulante."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	fin transaccion
		mysql_query("COMMIT");
	}
	
	//	descalificar candidato
	elseif ($accion== "descalificar-candidato") {
		//	inicio transaccion
		mysql_query("BEGIN");
		
		//	valido estado
		$sql = "SELECT Estado 
				FROM rh_requerimientopost
				WHERE
					Requerimiento = '".$Requerimiento."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					TipoPostulante = '".$TipoPostulante."' AND
					Postulante = '".$Postulante."'";
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
		if ($field['Estado'] == "D") die("El Candidato ya encuentra Descalificado");
		else if ($field['Estado'] == "C") die("No se puede descalificar un Candidato que ha sido Contratado");
		
		//	actualizo estado del requerimiento del postulante
		$sql = "UPDATE rh_requerimientopost
				SET Estado = 'D'
				WHERE
					Requerimiento = '".$Requerimiento."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					TipoPostulante = '".$TipoPostulante."' AND
					Postulante = '".$Postulante."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo estado de la evaluacion del requerimiento del postulante
		$sql = "UPDATE rh_requerimientoevalpost
				SET FlagAprobacion = 'N'
				WHERE
					Requerimiento = '".$Requerimiento."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					TipoPostulante = '".$TipoPostulante."' AND
					Postulante = '".$Postulante."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo estado del postulante
		if ($TipoPostulante == "E") {
			$sql = "UPDATE rh_postulantes SET Estado = 'P' WHERE Postulante = '".$Postulante."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	fin transaccion
		mysql_query("COMMIT");
	}
}

//	postulantes
elseif ($modulo == "postulantes") {
	//	nuevo registro
	if ($accion== "nuevo") {
		//	genero codigo
		$Postulante = getCodigo("rh_postulantes", "Postulante", 6);
		$Expediente = $Anio.$Postulante;
		
		//	inserto
		$sql = "INSERT INTO rh_postulantes
				SET
					Postulante = '".$Postulante."',
					Apellido1 = '".changeUrl($Apellido1)."',
					Apellido2 = '".changeUrl($Apellido2)."',
					Nombres = '".changeUrl($Nombres)."',
					ResumenEjec = '".changeUrl($ResumenEjec)."',
					CiudadNacimiento = '".$CiudadNacimiento."',
					CiudadDomicilio = '".$CiudadDomicilio."',
					Fnacimiento = '".formatFechaAMD($Fnacimiento)."',
					Sexo = '".$Sexo."',
					Direccion = '".changeUrl($Direccion)."',
					Referencia = '".changeUrl($Referencia)."',
					Email = '".changeUrl($Email)."',
					Telefono1 = '".$Telefono1."',
					FechaRegistro = NOW(),
					Expediente = '".$Expediente."',
					TipoDocumento = '".$TipoDocumento."',
					Ndocumento = '".$Ndocumento."',
					Estado = '".$Estado."',
					EstadoCivil = '".$EstadoCivil."',
					FedoCivil = '".formatFechaAMD($FedoCivil)."',
					GrupoSanguineo = '".$GrupoSanguineo."',
					SituacionDomicilio = '".$SituacionDomicilio."',
					InformacionAdic = '".changeUrl($InformacionAdic)."',
					FlagBeneficas = '".$FlagBeneficas."',
					Beneficas = '".changeUrl($Beneficas)."',
					FlagLaborales = '".$FlagLaborales."',
					Laborales = '".changeUrl($Laborales)."',
					FlagCulturales = '".$FlagCulturales."',
					Culturales = '".changeUrl($Culturales)."',
					FlagDeportivas = '".$FlagDeportivas."',
					Deportivas = '".changeUrl($Deportivas)."',
					FlagReligiosas = '".$FlagReligiosas."',
					Religiosas = '".changeUrl($Religiosas)."',
					FlagSociales = '".$FlagSociales."',
					Sociales = '".changeUrl($Sociales)."',
					Anio = '".$Anio."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		echo "|$Postulante";
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		//	actualizo
		$sql = "UPDATE rh_postulantes
				SET
					Apellido1 = '".changeUrl($Apellido1)."',
					Apellido2 = '".changeUrl($Apellido2)."',
					Nombres = '".changeUrl($Nombres)."',
					ResumenEjec = '".changeUrl($ResumenEjec)."',
					CiudadNacimiento = '".$CiudadNacimiento."',
					CiudadDomicilio = '".$CiudadDomicilio."',
					Fnacimiento = '".formatFechaAMD($Fnacimiento)."',
					Sexo = '".$Sexo."',
					Direccion = '".changeUrl($Direccion)."',
					Referencia = '".changeUrl($Referencia)."',
					Email = '".changeUrl($Email)."',
					Telefono1 = '".$Telefono1."',
					TipoDocumento = '".$TipoDocumento."',
					Ndocumento = '".$Ndocumento."',
					Estado = '".$Estado."',
					EstadoCivil = '".$EstadoCivil."',
					FedoCivil = '".formatFechaAMD($FedoCivil)."',
					GrupoSanguineo = '".$GrupoSanguineo."',
					SituacionDomicilio = '".$SituacionDomicilio."',
					InformacionAdic = '".changeUrl($InformacionAdic)."',
					FlagBeneficas = '".$FlagBeneficas."',
					Beneficas = '".changeUrl($Beneficas)."',
					FlagLaborales = '".$FlagLaborales."',
					Laborales = '".changeUrl($Laborales)."',
					FlagCulturales = '".$FlagCulturales."',
					Culturales = '".changeUrl($Culturales)."',
					FlagDeportivas = '".$FlagDeportivas."',
					Deportivas = '".changeUrl($Deportivas)."',
					FlagReligiosas = '".$FlagReligiosas."',
					Religiosas = '".changeUrl($Religiosas)."',
					FlagSociales = '".$FlagSociales."',
					Sociales = '".changeUrl($Sociales)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE Postulante = '".$Postulante."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	postulantes (instruccion)
elseif ($modulo == "postulantes_instruccion") {
	//	nuevo registro
	if ($accion== "nuevo") {
		//	genero codigo
		$Secuencia = getCodigo_2("rh_postulantes_instruccion", "Secuencia", "Postulante", $Postulante, 3);
		$Secuencia = intval($Secuencia);
		
		//	inserto
		$sql = "INSERT INTO rh_postulantes_instruccion
				SET
					Postulante = '".$Postulante."',
					Secuencia = '".$Secuencia."',
					CodGradoInstruccion = '".$CodGradoInstruccion."',
					Area = '".$Area."',
					CodProfesion = '".$CodProfesion."',
					Nivel = '".$Nivel."',
					CodCentroEstudio = '".$CodCentroEstudio."',
					FechaDesde = '".formatFechaAMD($FechaDesde)."',
					FechaHasta = '".formatFechaAMD($FechaHasta)."',
					Colegiatura = '".$Colegiatura."',
					NroColegiatura = '".changeUrl($NroColegiatura)."',
					Observaciones = '".changeUrl($Observaciones)."',
					FechaGraduacion = '".formatFechaAMD($FechaGraduacion)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		//	actualizo
		$sql = "UPDATE rh_postulantes_instruccion
				SET
					CodGradoInstruccion = '".$CodGradoInstruccion."',
					Area = '".$Area."',
					CodProfesion = '".$CodProfesion."',
					Nivel = '".$Nivel."',
					CodCentroEstudio = '".$CodCentroEstudio."',
					FechaDesde = '".formatFechaAMD($FechaDesde)."',
					FechaHasta = '".formatFechaAMD($FechaHasta)."',
					Colegiatura = '".$Colegiatura."',
					NroColegiatura = '".changeUrl($NroColegiatura)."',
					Observaciones = '".changeUrl($Observaciones)."',
					FechaGraduacion = '".formatFechaAMD($FechaGraduacion)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Postulante = '".$Postulante."' AND
					Secuencia = '".$Secuencia."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	eliminar registro
	elseif ($accion== "eliminar") {
		list($Postulante, $Secuencia) = split("[.]", $registro);
		//	eliminar
		$sql = "DELETE FROM rh_postulantes_instruccion
				WHERE
					Postulante = '".$Postulante."' AND
					Secuencia = '".$Secuencia."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	postulantes (idiomas)
elseif ($modulo == "postulantes_idiomas") {
	//	nuevo registro
	if ($accion== "nuevo") {
		//	inserto
		$sql = "INSERT INTO rh_postulantes_idioma
				SET
					Postulante = '".$Postulante."',
					CodIdioma = '".$CodIdioma."',
					NivelLectura = '".$NivelLectura."',
					NivelEscritura = '".$NivelEscritura."',
					NivelOral = '".$NivelOral."',
					NivelGeneral = '".$NivelGeneral."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	eliminar registro
	elseif ($accion== "eliminar") {
		list($Postulante, $CodIdioma) = split("[.]", $registro);
		//	eliminar
		$sql = "DELETE FROM rh_postulantes_idioma
				WHERE
					Postulante = '".$Postulante."' AND
					CodIdioma = '".$CodIdioma."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	postulantes (informatica)
elseif ($modulo == "postulantes_informatica") {
	//	nuevo registro
	if ($accion== "nuevo") {
		//	inserto
		$sql = "INSERT INTO rh_postulantes_informat
				SET
					Postulante = '".$Postulante."',
					Informatica = '".$Informatica."',
					Nivel = '".$Nivel."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	eliminar registro
	elseif ($accion== "eliminar") {
		list($Postulante, $Informatica) = split("[.]", $registro);
		//	eliminar
		$sql = "DELETE FROM rh_postulantes_informat
				WHERE
					Postulante = '".$Postulante."' AND
					Informatica = '".$Informatica."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	postulantes (cursos)
elseif ($modulo == "postulantes_cursos") {
	//	nuevo registro
	if ($accion== "nuevo") {
		//	genero codigo
		$Secuencia = getCodigo_2("rh_postulantes_cursos", "Secuencia", "Postulante", $Postulante, 3);
		$Secuencia = intval($Secuencia);
		
		//	inserto
		$sql = "INSERT INTO rh_postulantes_cursos
				SET
					Postulante = '".$Postulante."',
					Secuencia = '".$Secuencia."',
					CodCurso = '".$CodCurso."',
					TipoCurso = '".$TipoCurso."',
					CodCentroEstudio = '".$CodCentroEstudio."',
					FechaDesde = '".formatFechaAMD($FechaDesde)."',
					FechaHasta = '".formatFechaAMD($FechaHasta)."',
					TotalHoras = '".$TotalHoras."',
					AniosVigencia = '".$AniosVigencia."',
					Observaciones = '".changeUrl($Observaciones)."',
					PeriodoCulminacion = '".$PeriodoCulminacion."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		//	actualizo
		$sql = "UPDATE rh_postulantes_cursos
				SET
					CodCurso = '".$CodCurso."',
					TipoCurso = '".$TipoCurso."',
					CodCentroEstudio = '".$CodCentroEstudio."',
					FechaDesde = '".formatFechaAMD($FechaDesde)."',
					FechaHasta = '".formatFechaAMD($FechaHasta)."',
					TotalHoras = '".$TotalHoras."',
					AniosVigencia = '".$AniosVigencia."',
					Observaciones = '".changeUrl($Observaciones)."',
					PeriodoCulminacion = '".$PeriodoCulminacion."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Postulante = '".$Postulante."' AND
					Secuencia = '".$Secuencia."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	eliminar registro
	elseif ($accion== "eliminar") {
		list($Postulante, $Secuencia) = split("[.]", $registro);
		//	eliminar
		$sql = "DELETE FROM rh_postulantes_cursos
				WHERE
					Postulante = '".$Postulante."' AND
					Secuencia = '".$Secuencia."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	postulantes (experiencias)
elseif ($modulo == "postulantes_experiencias") {
	//	nuevo registro
	if ($accion== "nuevo") {
		//	genero codigo
		$Secuencia = getCodigo_2("rh_postulantes_experiencia", "Secuencia", "Postulante", $Postulante, 3);
		$Secuencia = intval($Secuencia);
		
		//	inserto
		$sql = "INSERT INTO rh_postulantes_experiencia
				SET
					Postulante = '".$Postulante."',
					Secuencia = '".$Secuencia."',
					Empresa = '".changeUrl($Empresa)."',
					FechaDesde = '".formatFechaAMD($FechaDesde)."',
					FechaHasta = '".formatFechaAMD($FechaHasta)."',
					MotivoCese = '".$MotivoCese."',
					Sueldo = '".setNumero($Sueldo)."',
					AreaExperiencia = '".$AreaExperiencia."',
					TipoEnte = '".$TipoEnte."',
					CargoOcupado = '".changeUrl($CargoOcupado)."',
					Funciones = '".changeUrl($Funciones)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		//	actualizo
		$sql = "UPDATE rh_postulantes_experiencia
				SET
					Empresa = '".changeUrl($Empresa)."',
					FechaDesde = '".formatFechaAMD($FechaDesde)."',
					FechaHasta = '".formatFechaAMD($FechaHasta)."',
					MotivoCese = '".$MotivoCese."',
					Sueldo = '".setNumero($Sueldo)."',
					AreaExperiencia = '".$AreaExperiencia."',
					TipoEnte = '".$TipoEnte."',
					CargoOcupado = '".changeUrl($CargoOcupado)."',
					Funciones = '".changeUrl($Funciones)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Postulante = '".$Postulante."' AND
					Secuencia = '".$Secuencia."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	eliminar registro
	elseif ($accion== "eliminar") {
		list($Postulante, $Secuencia) = split("[.]", $registro);
		//	eliminar
		$sql = "DELETE FROM rh_postulantes_experiencia
				WHERE
					Postulante = '".$Postulante."' AND
					Secuencia = '".$Secuencia."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	postulantes (referencias)
elseif ($modulo == "postulantes_referencias") {
	//	nuevo registro
	if ($accion== "nuevo") {
		//	genero codigo
		$Secuencia = getCodigo_2("rh_postulantes_referencias", "Secuencia", "Postulante", $Postulante, 3);
		$Secuencia = intval($Secuencia);
		
		//	inserto
		$sql = "INSERT INTO rh_postulantes_referencias
				SET
					Postulante = '".$Postulante."',
					Secuencia = '".$Secuencia."',
					Direccion = '".changeUrl($Direccion)."',
					Nombre = '".changeUrl($Nombre)."',
					Cargo = '".changeUrl($Cargo)."',
					Empresa = '".changeUrl($Empresa)."',
					Telefono = '".$Telefono."',
					Tipo = '".$Tipo."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		//	actualizo
		$sql = "UPDATE rh_postulantes_referencias
				SET
					Direccion = '".changeUrl($Direccion)."',
					Nombre = '".changeUrl($Nombre)."',
					Cargo = '".changeUrl($Cargo)."',
					Empresa = '".changeUrl($Empresa)."',
					Telefono = '".$Telefono."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Postulante = '".$Postulante."' AND
					Secuencia = '".$Secuencia."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	eliminar registro
	elseif ($accion== "eliminar") {
		list($Postulante, $Secuencia) = split("[.]", $registro);
		//	eliminar
		$sql = "DELETE FROM rh_postulantes_referencias
				WHERE
					Postulante = '".$Postulante."' AND
					Secuencia = '".$Secuencia."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	postulantes (documentos)
elseif ($modulo == "postulantes_documentos") {
	//	nuevo registro
	if ($accion== "nuevo") {
		//	genero codigo
		$Secuencia = getCodigo_2("rh_postulantes_documentos", "Secuencia", "Postulante", $Postulante, 3);
		$Secuencia = intval($Secuencia);
		
		//	inserto
		$sql = "INSERT INTO rh_postulantes_documentos
				SET
					Postulante = '".$Postulante."',
					Secuencia = '".$Secuencia."',
					Observaciones = '".changeUrl($Observaciones)."',
					Documento = '".$Documento."',
					FlagPresento = '".$FlagPresento."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		//	actualizo
		$sql = "UPDATE rh_postulantes_documentos
				SET
					Observaciones = '".changeUrl($Observaciones)."',
					Documento = '".$Documento."',
					FlagPresento = '".$FlagPresento."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Postulante = '".$Postulante."' AND
					Secuencia = '".$Secuencia."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	eliminar registro
	elseif ($accion== "eliminar") {
		list($Postulante, $Secuencia) = split("[.]", $registro);
		//	eliminar
		$sql = "DELETE FROM rh_postulantes_documentos
				WHERE
					Postulante = '".$Postulante."' AND
					Secuencia = '".$Secuencia."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	postulantes (cargos)
elseif ($modulo == "postulantes_cargos") {
	//	nuevo registro
	if ($accion== "nuevo") {
		//	genero codigo
		$Secuencia = getCodigo_2("rh_postulantes_cargos", "Secuencia", "Postulante", $Postulante, 3);
		$Secuencia = intval($Secuencia);
		
		//	inserto
		$sql = "INSERT INTO rh_postulantes_cargos
				SET
					Postulante = '".$Postulante."',
					Secuencia = '".$Secuencia."',
					CodOrganismo = '".$CodOrganismo."',
					CodCargo = '".$CodCargo."',
					Comentario = '".changeUrl($Comentario)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		//	actualizo
		$sql = "UPDATE rh_postulantes_cargos
				SET
					CodOrganismo = '".$CodOrganismo."',
					CodCargo = '".$CodCargo."',
					Comentario = '".changeUrl($Comentario)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Postulante = '".$Postulante."' AND
					Secuencia = '".$Secuencia."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	eliminar registro
	elseif ($accion== "eliminar") {
		list($Postulante, $Secuencia) = split("[.]", $registro);
		//	eliminar
		$sql = "DELETE FROM rh_postulantes_cargos
				WHERE
					Postulante = '".$Postulante."' AND
					Secuencia = '".$Secuencia."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	capacitaciones
elseif ($modulo == "capacitaciones") {
	//	nuevo registro
	if ($accion== "nuevo") {
		mysql_query("BEGIN");
		//	-------------
		//	genero codigo
		$Capacitacion = getCodigo_3("rh_capacitacion", "Capacitacion", "Anio", "CodOrganismo", $Anio, $CodOrganismo, 6);
		
		//	inserto
		$sql = "INSERT INTO rh_capacitacion
				SET
					Anio = '".$Anio."',
					CodOrganismo = '".$CodOrganismo."',
					Capacitacion = '".$Capacitacion."',
					CodCurso = '".$CodCurso."',
					CodCentroEstudio = '".$CodCentroEstudio."',
					Vacantes = '".setNumero($Vacantes)."',
					FechaDesde = '".formatFechaAMD($FechaDesde)."',
					FechaHasta = '".formatFechaAMD($FechaHasta)."',
					Participantes = '".setNumero($Participantes)."',
					Aula = '".changeUrl($Aula)."',
					TipoCapacitacion = '".$TipoCapacitacion."',
					Expositor = '".changeUrl($Expositor)."',
					CodCiudad = '".$CodCiudad."',
					Solicitante = '".$Solicitante."',
					TelefonoContacto = '".changeUrl($TelefonoContacto)."',
					CostoEstimado = '".setNumero($CostoEstimado)."',
					MontoAsumido = '".setNumero($MontoAsumido)."',
					Modalidad = '".$Modalidad."',
					TipoCurso = '".$TipoCurso."',
					FlagCostos = '".$FlagCostos."',
					Periodo = '".$Periodo."',
					Observaciones = '".changeUrl($Observaciones)."',
					Fundamentacion1 = '".changeUrl($Fundamentacion1)."',
					Fundamentacion2 = '".changeUrl($Fundamentacion2)."',
					Fundamentacion3 = '".changeUrl($Fundamentacion3)."',
					Fundamentacion4 = '".changeUrl($Fundamentacion4)."',
					Fundamentacion5 = '".changeUrl($Fundamentacion5)."',
					Fundamentacion6 = '".changeUrl($Fundamentacion6)."',
					Fundamentacion7 = '".changeUrl($Fundamentacion7)."',
					Estado = '".$Estado."',
					CreadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaCreado = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	detalles_participantes
		if ($detalles_participantes != "") {
			$_Secuencia = 0;
			$participantes = split(";char:tr;", $detalles_participantes);
			foreach ($participantes as $participante) {
				list($_CodPersona, $_CodDependencia) = split(";char:td;", $participante);
				//	inserto
				$sql = "INSERT INTO rh_capacitacion_empleados
						SET
							Anio = '".$Anio."',
							CodOrganismo = '".$CodOrganismo."',
							Capacitacion = '".$Capacitacion."',
							Secuencia = '".++$_Secuencia."',
							CodPersona = '".$_CodPersona."',
							CodDependencia = '".$_CodDependencia."',
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		//	-------------
		mysql_query("COMMIT");
		//	-------------
		echo "|$Capacitacion";
	}
	
	//	modificar registro
	elseif ($accion== "modificar") {
		mysql_query("BEGIN");
		//	-------------
		//	actualizo
		$sql = "UPDATE rh_capacitacion
				SET
					CodCurso = '".$CodCurso."',
					CodCentroEstudio = '".$CodCentroEstudio."',
					Vacantes = '".setNumero($Vacantes)."',
					FechaDesde = '".formatFechaAMD($FechaDesde)."',
					FechaHasta = '".formatFechaAMD($FechaHasta)."',
					Participantes = '".setNumero($Participantes)."',
					Aula = '".changeUrl($Aula)."',
					TipoCapacitacion = '".$TipoCapacitacion."',
					Expositor = '".changeUrl($Expositor)."',
					CodCiudad = '".$CodCiudad."',
					Solicitante = '".$Solicitante."',
					TelefonoContacto = '".changeUrl($TelefonoContacto)."',
					CostoEstimado = '".setNumero($CostoEstimado)."',
					MontoAsumido = '".setNumero($MontoAsumido)."',
					Modalidad = '".$Modalidad."',
					TipoCurso = '".$TipoCurso."',
					FlagCostos = '".$FlagCostos."',
					Observaciones = '".changeUrl($Observaciones)."',
					Fundamentacion1 = '".changeUrl($Fundamentacion1)."',
					Fundamentacion2 = '".changeUrl($Fundamentacion2)."',
					Fundamentacion3 = '".changeUrl($Fundamentacion3)."',
					Fundamentacion4 = '".changeUrl($Fundamentacion4)."',
					Fundamentacion5 = '".changeUrl($Fundamentacion5)."',
					Fundamentacion6 = '".changeUrl($Fundamentacion6)."',
					Fundamentacion7 = '".changeUrl($Fundamentacion7)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Capacitacion = '".$Capacitacion."' AND
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	detalles_participantes
		$sql = "DELETE FROM rh_capacitacion_empleados
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					Capacitacion = '".$Capacitacion."'";	fwrite($__archivo, $sql.";\n\n");
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if ($detalles_participantes != "") {
			$_Secuencia = 0;
			$participantes = split(";char:tr;", $detalles_participantes);
			foreach ($participantes as $participante) {
				list($_CodPersona, $_CodDependencia) = split(";char:td;", $participante);
				//	inserto
				$sql = "INSERT INTO rh_capacitacion_empleados
						SET
							Anio = '".$Anio."',
							CodOrganismo = '".$CodOrganismo."',
							Capacitacion = '".$Capacitacion."',
							Secuencia = '".++$_Secuencia."',
							CodPersona = '".$_CodPersona."',
							CodDependencia = '".$_CodDependencia."',
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		//	-------------
		mysql_query("COMMIT");
		//	-------------
	}
	
	//	aprobar registro
	elseif ($accion== "aprobar") {
		mysql_query("BEGIN");
		//	-------------
		//	actualizo
		$sql = "UPDATE rh_capacitacion
				SET
					Estado = 'AP',
					AprobadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaAprobado = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Capacitacion = '".$Capacitacion."' AND
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	detalles_horario
		if ($detalles_horario != "") {
			$_Secuencia = 0;
			$horarios = split(";char:tr;", $detalles_horario);
			foreach ($horarios as $horario) {
				list($_Estado, $_Lunes, $_Martes, $_Miercoles, $_Jueves, $_Viernes, $_Sabado, $_Domingo, $_FechaDesde, $_HoraInicioLunes, $_HoraInicioMartes, $_HoraInicioMiercoles, $_HoraInicioJueves, $_HoraInicioViernes, $_HoraInicioSabado, $_HoraInicioDomingo, $_TotalDias, $_FechaHasta, $_HoraFinLunes, $_HoraFinMartes, $_HoraFinMiercoles, $_HoraFinJueves, $_HoraFinViernes, $_HoraFinSabado, $_HoraFinDomingo, $_TotalHoras) = split(";char:td;", $horario);
				
				//	consulto
				$sql = "SELECT CodPersona
						FROM rh_capacitacion_empleados
						WHERE
							Anio = '".$Anio."' AND
							CodOrganismo = '".$CodOrganismo."' AND
							Capacitacion = '".$Capacitacion."'";	fwrite($__archivo, $sql.";\n\n");
				$query_empleado = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				while($field_empleado = mysql_fetch_array($query_empleado)) {
					//	inserto
					$sql = "INSERT INTO rh_capacitacion_hora
							SET
								Anio = '".$Anio."',
								CodOrganismo = '".$CodOrganismo."',
								Capacitacion = '".$Capacitacion."',
								Secuencia = '".++$_Secuencia."',
								CodPersona = '".$field_empleado['CodPersona']."',
								Estado = '".$_Estado."',
								FechaDesde = '".formatFechaAMD($_FechaDesde)."',
								FechaHasta = '".formatFechaAMD($_FechaHasta)."',
								Lunes = '".$_Lunes."',
								HoraInicioLunes = '".$_HoraInicioLunes."',
								HoraFinLunes = '".$_HoraFinLunes."',
								Martes = '".$_Martes."',
								HoraInicioMartes = '".$_HoraInicioMartes."',
								HoraFinMartes = '".$_HoraFinMartes."',
								Miercoles = '".$_Miercoles."',
								HoraInicioMiercoles = '".$_HoraInicioMiercoles."',
								HoraFinMiercoles = '".$_HoraFinMiercoles."',
								Jueves = '".$_Jueves."',
								HoraInicioJueves = '".$_HoraInicioJueves."',
								HoraFinJueves = '".$_HoraFinJueves."',
								Viernes = '".$_Viernes."',
								HoraInicioViernes = '".$_HoraInicioViernes."',
								HoraFinViernes = '".$_HoraFinViernes."',
								Sabado = '".$_Sabado."',
								HoraInicioSabado = '".$_HoraInicioSabado."',
								HoraFinSabado = '".$_HoraFinSabado."',
								Domingo = '".$_Domingo."',
								HoraInicioDomingo = '".$_HoraInicioDomingo."',
								HoraFinDomingo = '".$_HoraFinDomingo."',
								TotalDias = '".$_TotalDias."',
								TotalHoras = '".$_TotalHoras."',
								UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
								UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
					$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
			}
		}
		//	-------------
		mysql_query("COMMIT");
		//	-------------
	}
	
	//	iniciar registro
	elseif ($accion== "iniciar") {
		mysql_query("BEGIN");
		//	-------------
		//	actualizo
		$sql = "UPDATE rh_capacitacion
				SET
					Estado = 'IN',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Capacitacion = '".$Capacitacion."' AND
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	detalles_gastos
		if ($detalles_gastos != "") {
			$_Secuencia = 0;
			$gastos = split(";char:tr;", $detalles_gastos);
			foreach ($gastos as $detalle) {
				list($_Numero, $_Fecha, $_SubTotal, $_Impuestos, $_Total) = split(";char:td;", $detalle);
				
				//	inserto
				$sql = "INSERT INTO rh_capacitacion_gastos
						SET
							Anio = '".$Anio."',
							CodOrganismo = '".$CodOrganismo."',
							Capacitacion = '".$Capacitacion."',
							Secuencia = '".++$_Secuencia."',
							Numero = '".$_Numero."',
							Fecha = '".$_Fecha."',
							SubTotal = '".$_SubTotal."',
							Impuestos = '".$_Impuestos."',
							Total = '".$_Total."',
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		//	-------------
		mysql_query("COMMIT");
		//	-------------
	}
	
	//	terminar registro
	elseif ($accion== "terminar") {
		mysql_query("BEGIN");
		//	-------------
		//	actualizo
		$sql = "UPDATE rh_capacitacion
				SET
					Estado = 'TE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Capacitacion = '".$Capacitacion."' AND
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-------------
		mysql_query("COMMIT");
		//	-------------
	}
}

//	tipos de evaluacion
elseif ($modulo == "evaluacion_tipo") {
	//	nuevo registro
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	-------------
		//	inserto
		$sql = "INSERT INTO rh_tipoevaluacion
				SET
					TipoEvaluacion = '".$TipoEvaluacion."',
					Descripcion = '".changeUrl($Descripcion)."',
					Estado = '".$Estado."',
					FlagSistema = '".$FlagSistema."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	grados de calificacion
		if ($detalles_grados != "") {
			$_Secuencia = 0;
			$grados = split(";char:tr;", $detalles_grados);
			foreach ($grados as $grado) {
				list($_Grado, $_Descripcion, $_PuntajeMin, $_PuntajeMax, $_Estado) = split(";char:td;", $grado);
				//	inserto
				$sql = "INSERT INTO rh_gradoscompetencia
						SET
							TipoEvaluacion = '".$TipoEvaluacion."',
							Grado = '".$_Grado."',
							Descripcion = '".$_Descripcion."',
							PuntajeMin = '".$_PuntajeMin."',
							PuntajeMax = '".$_PuntajeMax."',
							Estado = '".$_Estado."',
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		//	-------------
		mysql_query("COMMIT");
		//	-------------
	}
	
	//	modificar registro
	elseif ($accion == "modificar") {
		mysql_query("BEGIN");
		//	-------------
		//	inserto
		$sql = "UPDATE rh_tipoevaluacion
				SET
					Descripcion = '".changeUrl($Descripcion)."',
					Estado = '".$Estado."',
					FlagSistema = '".$FlagSistema."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE TipoEvaluacion = '".$TipoEvaluacion."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	grados de calificacion
		$sql = "DELETE FROM rh_gradoscompetencia WHERE TipoEvaluacion = '".$TipoEvaluacion."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if ($detalles_grados != "") {
			$_Secuencia = 0;
			$grados = split(";char:tr;", $detalles_grados);
			foreach ($grados as $grado) {
				list($_Grado, $_Descripcion, $_PuntajeMin, $_PuntajeMax, $_Estado) = split(";char:td;", $grado);
				//	inserto
				$sql = "INSERT INTO rh_gradoscompetencia
						SET
							TipoEvaluacion = '".$TipoEvaluacion."',
							Grado = '".$_Grado."',
							Descripcion = '".$_Descripcion."',
							PuntajeMin = '".$_PuntajeMin."',
							PuntajeMax = '".$_PuntajeMax."',
							Estado = '".$_Estado."',
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		//	-------------
		mysql_query("COMMIT");
		//	-------------
	}
	
	//	eliminar registro
	elseif ($accion == "eliminar") {
		mysql_query("BEGIN");
		//	-------------
		//	consulto
		$sql = "SELECT FlagSistema FROM rh_tipoevaluacion WHERE TipoEvaluacion = '".$registro."' AND FlagSistema = 'S'";
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query) != 0) die("No puede eliminar este registro (Transacci&oacute;n del Sistema)");
		
		//	elimino
		$sql = "DELETE FROM rh_gradoscompetencia WHERE TipoEvaluacion = '".$registro."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	elimino
		$sql = "DELETE FROM rh_tipoevaluacion WHERE TipoEvaluacion = '".$registro."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-------------
		mysql_query("COMMIT");
		//	-------------
	}
}

//	evaluacion
elseif ($modulo == "evaluacion") {
	//	nuevo registro
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	-------------
		//	genero codigo
		$Evaluacion = getCodigo("rh_evaluacion", "Evaluacion", 4);
		$Evaluacion = intval($Evaluacion);
		
		//	inserto
		$sql = "INSERT INTO rh_evaluacion
				SET
					Evaluacion = '".$Evaluacion."',
					TipoEvaluacion = '".$TipoEvaluacion."',
					Descripcion = '".changeUrl($Descripcion)."',
					PuntajeMin = '".$PuntajeMin."',
					PuntajeMax = '".$PuntajeMax."',
					Plantilla = '".$Plantilla."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-------------
		mysql_query("COMMIT");
		//	-------------
	}
	
	//	modificar registro
	elseif ($accion == "modificar") {
		mysql_query("BEGIN");
		//	-------------
		//	inserto
		$sql = "UPDATE rh_evaluacion
				SET
					TipoEvaluacion = '".$TipoEvaluacion."',
					Descripcion = '".changeUrl($Descripcion)."',
					PuntajeMin = '".$PuntajeMin."',
					PuntajeMax = '".$PuntajeMax."',
					Plantilla = '".$Plantilla."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE Evaluacion = '".$Evaluacion."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-------------
		mysql_query("COMMIT");
		//	-------------
	}
	
	//	eliminar registro
	elseif ($accion == "eliminar") {
		mysql_query("BEGIN");
		//	-------------
		//	elimino
		$sql = "DELETE FROM rh_evaluacion WHERE Evaluacion = '".$registro."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-------------
		mysql_query("COMMIT");
		//	-------------
	}
}

//	itesms para evaluacion
elseif ($modulo == "evaluacion_items") {
	//	nuevo registro
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	-------------
		//	genero codigo
		$CodItem = getCodigo_2("rh_evaluacionitems", "CodItem", "Evaluacion", $Evaluacion, 4);
		$CodItem = intval($CodItem);
		
		//	inserto
		$sql = "INSERT INTO rh_evaluacionitems
				SET
					Evaluacion = '".$Evaluacion."',
					CodItem = '".$CodItem."',
					Descripcion = '".changeUrl($Descripcion)."',
					PuntajeMin = '".$PuntajeMin."',
					PuntajeMax = '".$PuntajeMax."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-------------
		mysql_query("COMMIT");
		//	-------------
	}
	
	//	modificar registro
	elseif ($accion == "modificar") {
		mysql_query("BEGIN");
		//	-------------
		//	inserto
		$sql = "UPDATE rh_evaluacionitems
				SET
					Descripcion = '".changeUrl($Descripcion)."',
					PuntajeMin = '".$PuntajeMin."',
					PuntajeMax = '".$PuntajeMax."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Evaluacion = '".$Evaluacion."' AND
					CodItem = '".$CodItem."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-------------
		mysql_query("COMMIT");
		//	-------------
	}
	
	//	eliminar registro
	elseif ($accion == "eliminar") {
		mysql_query("BEGIN");
		//	-------------
		list($Evaluacion, $CodItem) = split("[.]", $registro);
		//	elimino
		$sql = "DELETE FROM rh_evaluacionitems
				WHERE
					Evaluacion = '".$Evaluacion."' AND
					CodItem = '".$CodItem."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-------------
		mysql_query("COMMIT");
		//	-------------
	}
}

//	grupos de competencia
elseif ($modulo == "competencias_grupo") {
	//	nuevo registro
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	-------------
		//	inserto
		$sql = "INSERT INTO rh_evaluacionarea
				SET
					Area = '".$Area."',
					Descripcion = '".changeUrl($Descripcion)."',
					TipoEvaluacion = '".$TipoEvaluacion."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-------------
		mysql_query("COMMIT");
		//	-------------
	}
	
	//	modificar registro
	elseif ($accion == "modificar") {
		mysql_query("BEGIN");
		//	-------------
		//	inserto
		$sql = "UPDATE rh_evaluacionarea
				SET
					Descripcion = '".changeUrl($Descripcion)."',
					TipoEvaluacion = '".$TipoEvaluacion."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE Area = '".$Area."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-------------
		mysql_query("COMMIT");
		//	-------------
	}
	
	//	eliminar registro
	elseif ($accion == "eliminar") {
		mysql_query("BEGIN");
		//	-------------
		//	elimino
		$sql = "DELETE FROM rh_evaluacionarea WHERE Area = '".$registro."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-------------
		mysql_query("COMMIT");
		//	-------------
	}
}

//	competencias
elseif ($modulo == "competencias") {
	//	nuevo registro
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	-------------
		//	genero codigo
		$Competencia = getCodigo("rh_evaluacionfactores", "Competencia", 3);
		$Competencia = intval($Competencia);
		
		//	inserto
		$sql = "INSERT INTO rh_evaluacionfactores
				 SET
  					Competencia = '".$Competencia."',
  					Descripcion = '".changeUrl($Descripcion)."',
  					Explicacion = '".changeUrl($Explicacion)."',
  					TipoCompetencia = '".$TipoCompetencia."',
  					Area = '".$Area."',
  					Nivel = '".$Nivel."',
  					Calificacion = '".$Calificacion."',
  					FlagPlantilla = '".$FlagPlantilla."',
  					ValorRequerido = '".$ValorRequerido."',
  					ValorMinimo = '".$ValorMinimo."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	grados de calificacion
		if ($detalles_grados != "") {
			$_Secuencia = 0;
			$grados = split(";char:tr;", $detalles_grados);
			foreach ($grados as $grado) {
				list($_TipoEvaluacion, $_Grado, $_Valor, $_Explicacion, $_Explicacion2, $_Estado) = split(";char:td;", $grado);
				//	inserto
				$sql = "INSERT INTO rh_factorvalor
						 SET
  							Secuencia = '".++$_Secuencia."',
  							Competencia = '".$Competencia."',
  							TipoEvaluacion = '".$_TipoEvaluacion."',
  							Grado = '".$_Grado."',
  							Explicacion = '".$_Explicacion."',
  							Explicacion2 = '".$_Explicacion2."',
  							Valor = '".$_Valor."',
							Estado = '".$_Estado."',
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		//	-------------
		mysql_query("COMMIT");
		//	-------------
	}
	
	//	modificar registro
	elseif ($accion == "modificar") {
		mysql_query("BEGIN");
		//	-------------
		//	inserto
		$sql = "UPDATE rh_evaluacionfactores
				 SET
  					Descripcion = '".changeUrl($Descripcion)."',
  					Explicacion = '".changeUrl($Explicacion)."',
  					TipoCompetencia = '".$TipoCompetencia."',
  					Area = '".$Area."',
  					Nivel = '".$Nivel."',
  					Calificacion = '".$Calificacion."',
  					FlagPlantilla = '".$FlagPlantilla."',
  					ValorRequerido = '".$ValorRequerido."',
  					ValorMinimo = '".$ValorMinimo."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				 WHERE Competencia = '".$Competencia."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	grados de calificacion
		$sql = "DELETE FROM rh_factorvalor WHERE Competencia = '".$Competencia."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	grados de calificacion
		if ($detalles_grados != "") {
			$_Secuencia = 0;
			$grados = split(";char:tr;", $detalles_grados);
			foreach ($grados as $grado) {
				list($_TipoEvaluacion, $_Grado, $_Valor, $_Explicacion, $_Explicacion2, $_Estado) = split(";char:td;", $grado);
				//	inserto
				$sql = "INSERT INTO rh_factorvalor
						 SET
  							Secuencia = '".++$_Secuencia."',
  							Competencia = '".$Competencia."',
  							TipoEvaluacion = '".$_TipoEvaluacion."',
  							Grado = '".$_Grado."',
  							Explicacion = '".$_Explicacion."',
  							Explicacion2 = '".$_Explicacion2."',
  							Valor = '".$_Valor."',
							Estado = '".$_Estado."',
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		//	-------------
		mysql_query("COMMIT");
		//	-------------
	}
	
	//	eliminar registro
	elseif ($accion == "eliminar") {
		mysql_query("BEGIN");
		//	-------------
		//	elimino
		$sql = "DELETE FROM rh_factorvalor WHERE Competencia = '".$registro."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	elimino
		$sql = "DELETE FROM rh_evaluacionfactores WHERE Competencia = '".$registro."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-------------
		mysql_query("COMMIT");
		//	-------------
	}
}

//	plantilla de competencias
elseif ($modulo == "competencias_plantilla") {
	//	nuevo registro
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	-------------
		//	genero codigo
		$Plantilla = getCodigo("rh_evaluacionfactoresplantilla", "Plantilla", 3);
		$Plantilla = intval($Plantilla);
		
		//	inserto
		$sql = "INSERT INTO rh_evaluacionfactoresplantilla
				 SET
  					Plantilla = '".$Plantilla."',
  					Descripcion = '".changeUrl($Descripcion)."',
  					TipoEvaluacion = '".$TipoEvaluacion."',
  					FlagTipoEvaluacion = '".$FlagTipoEvaluacion."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	competencias
		if ($detalles_competencias != "") {
			$_Secuencia = 0;
			$competencias = split(";char:tr;", $detalles_competencias);
			foreach ($competencias as $competencia) {
				list($_Competencia, $_Peso, $_FactorParticipacion, $_FlagPotencial, $_FlagCompetencia, $_FlagConceptual) = split(";char:td;", $competencia);
				//	inserto
				$sql = "INSERT INTO rh_factorvalorplantilla
						 SET
  							Plantilla = '".$Plantilla."',
  							Competencia = '".$_Competencia."',
  							Peso = '".$_Peso."',
  							FactorParticipacion = '".$_FactorParticipacion."',
  							FlagPotencial = '".$_FlagPotencial."',
  							FlagCompetencia = '".$_FlagCompetencia."',
  							FlagConceptual = '".$_FlagConceptual."',
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		//	-------------
		mysql_query("COMMIT");
		//	-------------
	}
	
	//	modificar registro
	elseif ($accion == "modificar") {
		mysql_query("BEGIN");
		//	-------------
		//	actualizo
		$sql = "UPDATE rh_evaluacionfactoresplantilla
				 SET
  					Descripcion = '".changeUrl($Descripcion)."',
  					TipoEvaluacion = '".$TipoEvaluacion."',
  					FlagTipoEvaluacion = '".$FlagTipoEvaluacion."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE Plantilla = '".$Plantilla."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	competencias
		$sql = "DELETE FROM rh_factorvalorplantilla WHERE Plantilla = '".$Plantilla."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if ($detalles_competencias != "") {
			$_Secuencia = 0;
			$competencias = split(";char:tr;", $detalles_competencias);
			foreach ($competencias as $competencia) {
				list($_Competencia, $_Peso, $_FactorParticipacion, $_FlagPotencial, $_FlagCompetencia, $_FlagConceptual) = split(";char:td;", $competencia);
				//	inserto
				$sql = "INSERT INTO rh_factorvalorplantilla
						 SET
  							Plantilla = '".$Plantilla."',
  							Competencia = '".$_Competencia."',
  							Peso = '".$_Peso."',
  							FactorParticipacion = '".$_FactorParticipacion."',
  							FlagPotencial = '".$_FlagPotencial."',
  							FlagCompetencia = '".$_FlagCompetencia."',
  							FlagConceptual = '".$_FlagConceptual."',
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		//	-------------
		mysql_query("COMMIT");
		//	-------------
	}
	
	//	eliminar registro
	elseif ($accion == "eliminar") {
		mysql_query("BEGIN");
		//	-------------
		//	elimino
		$sql = "DELETE FROM rh_factorvalorplantilla WHERE Plantilla = '".$registro."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	elimino
		$sql = "DELETE FROM rh_evaluacionfactoresplantilla WHERE Plantilla = '".$registro."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-------------
		mysql_query("COMMIT");
		//	-------------
	}
}

//	grados de calificacion
elseif ($modulo == "grados_calificacion") {
	//	nuevo registro
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	-------------
		//	genero codigo
		$Grado = getCodigo("rh_gradoscalificacion", "Grado", 4);
		$Grado = intval($Grado);
		
		//	inserto
		$sql = "INSERT INTO rh_gradoscalificacion
				SET
					Grado = '".$Grado."',
					Descripcion = '".changeUrl($Descripcion)."',
					Definicion = '".changeUrl($Definicion)."',
					PuntajeMin = '".$PuntajeMin."',
					PuntajeMax = '".$PuntajeMax."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-------------
		mysql_query("COMMIT");
		//	-------------
	}
	
	//	modificar registro
	elseif ($accion == "modificar") {
		mysql_query("BEGIN");
		//	-------------
		//	inserto
		$sql = "UPDATE rh_gradoscalificacion
				SET
					Descripcion = '".changeUrl($Descripcion)."',
					Definicion = '".changeUrl($Definicion)."',
					PuntajeMin = '".$PuntajeMin."',
					PuntajeMax = '".$PuntajeMax."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE Grado = '".$Grado."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-------------
		mysql_query("COMMIT");
		//	-------------
	}
	
	//	eliminar registro
	elseif ($accion == "eliminar") {
		mysql_query("BEGIN");
		//	-------------
		//	elimino
		$sql = "DELETE FROM rh_gradoscalificacion WHERE Grado = '".$registro."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-------------
		mysql_query("COMMIT");
		//	-------------
	}
}

//	apertura de periodo (bono de alimentacion)
elseif ($modulo == "bono_periodos") {
	//	nuevo
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	-----------------
		//	genero codigo
		$Anio = substr($Periodo, 0, 4);
		$CodBonoAlim = getCodigo_3("rh_bonoalimentacion", "CodBonoAlim", "Anio", "CodOrganismo", $Anio, $CodOrganismo, 3);
		
		//	inserto
		$sql = "INSERT INTO rh_bonoalimentacion
				SET
					Anio = '".$Anio."',
					CodOrganismo = '".$CodOrganismo."',
					CodBonoAlim = '".$CodBonoAlim."',
					Periodo = '".$Periodo."',
					Descripcion = '".changeUrl($Descripcion)."',
					FechaInicio = '".formatFechaAMD($FechaInicio)."',
					FechaFin = '".formatFechaAMD($FechaFin)."',
					CodTipoNom = '".$CodTipoNom."',
					TotalDiasPeriodo = '".setNumero($TotalDiasPeriodo)."',
					TotalDiasPago = '".setNumero($TotalDiasPago)."',
					TotalFeriados = '".setNumero($TotalFeriados)."',
					ValorDia = '".setNumero($ValorDia)."',
					HorasDiaria = '".setNumero($HorasDiaria)."',
					HorasSemanal = '".setNumero($HorasSemanal)."',
					ValorSemanal = '".setNumero($ValorSemanal)."',
					ValorMes = '".setNumero($ValorMes)."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	empleados
		$_dias_completos = setNumero($TotalDiasPeriodo);
		$empleados = split(";char:tr;", $detalles_empleados);
		foreach ($empleados as $_CodPersona) {
			$_ValorPagar = 0;
			$_DiasInactivos = 0;
			$_dia_semana = getDiaSemana($FechaInicio);
			$_fecha = $FechaInicio;
			//	obtengo la leyenda de los dias
			for ($i=1; $i<=$_dias_completos; $i++) {
				if ($_dia_semana == 7) $_dia_semana = 0;
				if ($_dia_semana >= 1 && $_dia_semana <= 5) {
				
					if (getDiasFeriados($_fecha, $_fecha) > 0) 
					{
						$l = "F";
						 $_ValorPagar += setNumero($ValorDia); //agregado modificacion
						
					} else { 
					
							$l = "X"; 
							$_ValorPagar += setNumero($ValorDia); 
					}
				}
				elseif ($_dia_semana == 0 || $_dia_semana == 6) 
				{
					$l = "I"; 
					$_DiasInactivos++; 
					 $_ValorPagar += setNumero($ValorDia); //agregado modificacion
				}
				$_Dia[$i] = $l;
				##
				$_dia_semana++;
				$_fecha = obtenerFechaFin($_fecha, 2);
			}
			//	inserto
			$sql = "INSERT INTO rh_bonoalimentaciondet
					SET
						Anio = '".$Anio."',
						CodOrganismo = '".$CodOrganismo."',
						CodBonoAlim = '".$CodBonoAlim."',
						CodPersona = '".$_CodPersona."',
						Dia1 = '".$_Dia[1]."',
						Dia2 = '".$_Dia[2]."',
						Dia3 = '".$_Dia[3]."',
						Dia4 = '".$_Dia[4]."',
						Dia5 = '".$_Dia[5]."',
						Dia6 = '".$_Dia[6]."',
						Dia7 = '".$_Dia[7]."',
						Dia8 = '".$_Dia[8]."',
						Dia9 = '".$_Dia[9]."',
						Dia10 = '".$_Dia[10]."',
						Dia11 = '".$_Dia[11]."',
						Dia12 = '".$_Dia[12]."',
						Dia13 = '".$_Dia[13]."',
						Dia14 = '".$_Dia[14]."',
						Dia15 = '".$_Dia[15]."',
						Dia16 = '".$_Dia[16]."',
						Dia17 = '".$_Dia[17]."',
						Dia18 = '".$_Dia[18]."',
						Dia19 = '".$_Dia[19]."',
						Dia20 = '".$_Dia[20]."',
						Dia21 = '".$_Dia[21]."',
						Dia22 = '".$_Dia[22]."',
						Dia23 = '".$_Dia[23]."',
						Dia24 = '".$_Dia[24]."',
						Dia25 = '".$_Dia[25]."',
						Dia26 = '".$_Dia[26]."',
						Dia27 = '".$_Dia[27]."',
						Dia28 = '".$_Dia[28]."',
						Dia29 = '".$_Dia[29]."',
						Dia30 = '".$_Dia[30]."',
						Dia31 = '".$_Dia[31]."',
						DiasPeriodo = '".setNumero($TotalDiasPeriodo)."',
						DiasPago = '".setNumero($TotalDiasPago)."',
						DiasFeriados = '".setNumero($TotalFeriados)."',
						DiasInactivos = '".$_DiasInactivos."',
						ValorPagar = '".$_ValorPagar."',
						TotalPagar = '".$_ValorPagar."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	modificar
	elseif ($accion == "modificar") {
		mysql_query("BEGIN");
		//	-----------------
		//	actualizo
		$sql = "UPDATE rh_bonoalimentacion
				SET
					Descripcion = '".changeUrl($Descripcion)."',
					Periodo = '".$Periodo."',
					FechaInicio = '".formatFechaAMD($FechaInicio)."',
					FechaFin = '".formatFechaAMD($FechaFin)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					CodBonoAlim = '".$CodBonoAlim."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	empleados
		$sql = "DELETE FROM rh_bonoalimentaciondet
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					CodBonoAlim = '".$CodBonoAlim."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		$_dias_completos = setNumero($TotalDiasPeriodo);
		$empleados = split(";char:tr;", $detalles_empleados);
		foreach ($empleados as $_CodPersona) {
			$_ValorPagar = 0;
			$_DiasInactivos = 0;
			$_dia_semana = getDiaSemana($FechaInicio);
			$_fecha = $FechaInicio;
			//	obtengo la leyenda de los dias
			for ($i=1; $i<=$_dias_completos; $i++) {
				if ($_dia_semana == 7) $_dia_semana = 0;
				if ($_dia_semana >= 1 && $_dia_semana <= 5) {
				
					if (getDiasFeriados($_fecha, $_fecha) > 0)
					{
						 $l = "F";
						 $_ValorPagar += setNumero($ValorDia); //agregado modificacion
						 
					} else { 
						
						$l = "X"; 
						$_ValorPagar += setNumero($ValorDia); 
					}
				}
				elseif ($_dia_semana == 0 || $_dia_semana == 6)
				{ 
					$l = "I"; 
					$_DiasInactivos++; 
					$_ValorPagar += setNumero($ValorDia); //agregado modificacion
				}
				$_Dia[$i] = $l;
				##
				$_dia_semana++;
				$_fecha = obtenerFechaFin($_fecha, 2);
			}
			//	inserto
			$sql = "INSERT INTO rh_bonoalimentaciondet
					SET
						Anio = '".$Anio."',
						CodOrganismo = '".$CodOrganismo."',
						CodBonoAlim = '".$CodBonoAlim."',
						CodPersona = '".$_CodPersona."',
						Dia1 = '".$_Dia[1]."',
						Dia2 = '".$_Dia[2]."',
						Dia3 = '".$_Dia[3]."',
						Dia4 = '".$_Dia[4]."',
						Dia5 = '".$_Dia[5]."',
						Dia6 = '".$_Dia[6]."',
						Dia7 = '".$_Dia[7]."',
						Dia8 = '".$_Dia[8]."',
						Dia9 = '".$_Dia[9]."',
						Dia10 = '".$_Dia[10]."',
						Dia11 = '".$_Dia[11]."',
						Dia12 = '".$_Dia[12]."',
						Dia13 = '".$_Dia[13]."',
						Dia14 = '".$_Dia[14]."',
						Dia15 = '".$_Dia[15]."',
						Dia16 = '".$_Dia[16]."',
						Dia17 = '".$_Dia[17]."',
						Dia18 = '".$_Dia[18]."',
						Dia19 = '".$_Dia[19]."',
						Dia20 = '".$_Dia[20]."',
						Dia21 = '".$_Dia[21]."',
						Dia22 = '".$_Dia[22]."',
						Dia23 = '".$_Dia[23]."',
						Dia24 = '".$_Dia[24]."',
						Dia25 = '".$_Dia[25]."',
						Dia26 = '".$_Dia[26]."',
						Dia27 = '".$_Dia[27]."',
						Dia28 = '".$_Dia[28]."',
						Dia29 = '".$_Dia[29]."',
						Dia30 = '".$_Dia[30]."',
						Dia31 = '".$_Dia[31]."',
						DiasPeriodo = '".setNumero($TotalDiasPeriodo)."',
						DiasPago = '".setNumero($TotalDiasPago)."',
						DiasFeriados = '".setNumero($TotalFeriados)."',
						DiasInactivos = '".$_DiasInactivos."',
						ValorPagar = '".$_ValorPagar."',
						TotalPagar = '".$_ValorPagar."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		//	-----------------
		mysql_query("COMMIT");

	}
	
	//	cerrar
	elseif ($accion == "cerrar") {
		mysql_query("BEGIN");
		//	-----------------
		//	actualizo
		$sql = "UPDATE rh_bonoalimentacion
				SET
					Estado = 'C',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					CodBonoAlim = '".$CodBonoAlim."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo
		$sql = "UPDATE rh_bonoalimentaciondet
				SET
					Estado = 'C',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					CodBonoAlim = '".$CodBonoAlim."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
}

//	apertura de periodo (bono de alimentacion)
elseif ($modulo == "bono_periodos_registrar_eventos_procesar") {
	mysql_query("BEGIN");
	//	-----------------
	//	elimino eventos
	$sql = "DELETE FROM rh_bonoalimentacioneventos
			WHERE
				Anio = '".$Anio."' AND
				CodOrganismo = '".$CodOrganismo."' AND
				CodBonoAlim = '".$CodBonoAlim."' AND
				CodPersona = '".$CodPersona."'";	fwrite($__archivo, $sql.";\n\n");
	$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	
	//	actualizo los descuentos
	for($i=1;$i<=31;$i++) {
		$sql = "UPDATE rh_bonoalimentaciondet
				SET Dia".$i." = 'X'
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					CodBonoAlim = '".$CodBonoAlim."' AND
					CodPersona = '".$CodPersona."' AND
					Dia".$i." = 'D'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	eventos
	$_Secuencia = 0;
	list($_HorasPar, $_MinutosPar) = split("[:]", $_PARAMETRO['UTDESC']);
	$eventos = split(";char:tr;", $detalles_eventos);
	foreach ($eventos as $linea) {
		list($_Fecha, $_HoraSalida, $_HoraEntrada, $_TotalHoras, $_Motivo, $_TipoEvento, $_Observaciones) = split(";char:td;", $linea);
		if ($_EventoHoras[$_Fecha] != "") {
			$_EventoHoras[$_Fecha] = sumarHoras($_EventoHoras[$_Fecha], $_TotalHoras);
			$_EventoFecha[$_Fecha] = $_Fecha;
		} else {
			$_EventoHoras[$_Fecha] = $_TotalHoras;
			$_EventoFecha[$_Fecha] = $_Fecha;
		}
		//	inserto
		if ($_HoraSalida != "") $Salida = "HoraSalida = '".$_HoraSalida."',"; else $Salida = "HoraSalida = NULL,";
		if ($_HoraEntrada != "") $HoraEntrada = "HoraEntrada = '".$_HoraEntrada."',"; else $HoraEntrada = "HoraEntrada = NULL,";
		$sql = "INSERT INTO rh_bonoalimentacioneventos
				SET
					Anio = '".$Anio."',
					CodOrganismo = '".$CodOrganismo."',
					CodBonoAlim = '".$CodBonoAlim."',
					CodPersona = '".$CodPersona."',
					Secuencia = '".++$_Secuencia."',
					Fecha = '".$_Fecha."',
					$Salida
					$Entrada
					HoraEntrada = '".$_HoraEntrada."',
					TotalHoras = '".$_TotalHoras."',
					TipoEvento = '".$_TipoEvento."',
					Motivo = '".$_Motivo."',
					Observaciones = '".$_Observaciones."',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	sumo los descuentos
	$_DiasDescuento = 0;
	foreach ($_EventoFecha as $_Fecha) {
		list($_Horas, $_Minutos) = split("[:]", $_EventoHoras[$_Fecha]);
		if (($_Horas > $_HorasPar) || ($_Horas == $_HorasPar && $_Minutos >= $_MinutosPar)) {
			$_DiasDescuento++;
			$_Dia = getFechaDias(formatFechaDMA($FechaInicio), formatFechaDMA($_Fecha)) + 1;
			//	actualizo detalle del bono alimenticio
			$sql = "UPDATE rh_bonoalimentaciondet
					SET Dia".$_Dia." = 'D'
					WHERE
						Anio = '".$Anio."' AND
						CodOrganismo = '".$CodOrganismo."' AND
						CodBonoAlim = '".$CodBonoAlim."' AND
						CodPersona = '".$CodPersona."'";	fwrite($__archivo, $sql.";\n\n");
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		} else {
			die("El tiempo total para el dia <strong>".formatFechaDMA($_Fecha)."</strong> es <strong>($_EventoHoras[$_Fecha])</strong> y no puede ser menor a <strong>$_PARAMETRO[UTDESC]</strong>");
		}
	}
	
	//	actualizo los descuentos
	$sql = "UPDATE rh_bonoalimentaciondet
			SET
				DiasDescuento = '".$_DiasDescuento."',
				ValorDescuento = ((ValorPagar / DiasPago) * ".intval($_DiasDescuento)."),
				TotalPagar = (ValorPagar - ((ValorPagar / DiasPago) * ".intval($_DiasDescuento)."))
			WHERE
				Anio = '".$Anio."' AND
				CodOrganismo = '".$CodOrganismo."' AND
				CodBonoAlim = '".$CodBonoAlim."' AND
				CodPersona = '".$CodPersona."'";	fwrite($__archivo, $sql.";\n\n");
	$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	//	-----------------
	mysql_query("COMMIT");
}

//	proceso de jubilacion
elseif ($modulo == "jubilaciones") {
	//	nuevo
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	-----------------
		//	valido
		if ($MontoJubilacion > ($SueldoBase * $_PARAMETRO['PORCLIMITJUB'] / 100)) die("El Monto de Jubilaci&oacute;n no puede ser mayor al ".$_PARAMETRO['PORCLIMITJUB']."% del Sueldo Base");
		
		//	genero codigo
		$CodProceso = getCodigo("rh_proceso_jubilacion", "CodProceso", 4);
		
		//	inserto
		$sql = "INSERT INTO rh_proceso_jubilacion
				SET
					CodProceso = '".$CodProceso."',
					CodOrganismo = '".$CodOrganismo."',
					CodPersona = '".$CodPersona."',
					AniosServicio = '".$AniosServicio."',
					AniosServicioExceso = '".$AniosServicioExceso."',
					Edad = '".$Edad."',
					CodDependencia = '".$CodDependencia."',
					NomDependencia = '".changeUrl($NomDependencia)."',
					CodCargo = '".$CodCargo."',
					DescripCargo = '".changeUrl($DescripCargo)."',
					ProcesadoPor = '".$ProcesadoPor."',
					FechaProcesado = NOW(),
					ObsProcesado = '".changeUrl($ObsProcesado)."',
					MontoJubilacion = '".$MontoJubilacion."',
					Coeficiente = '".$Coeficiente."',
					TotalSueldo = '".$TotalSueldo."',
					TotalPrimas = '".$TotalPrimas."',
					Periodo = NOW(),
					Fingreso = '".formatFechaAMD($Fingreso)."',
					SueldoActual = '".setNumero($SueldoActual)."',
					Estado = '".$Estado."',
					CodTipoNom = '".$CodTipoNom."',
					CodTipoTrabajador = '".$CodTipoTrabajador."',
					ObsCese = '".changeUrl($ObsCese)."',
					SitTra = '".$SitTra."',
					CodMotivoCes = '".$CodMotivoCes."',
					Fegreso = '".formatFechaAMD($Fegreso)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	antecedentes
		$_Secuencia = 0;
		$antecedentes = split(";char:tr;", $detalles_antecedentes);
		foreach ($antecedentes as $linea) {
			list($_Organismo, $_FIngreso, $_FEgreso, $_Anios, $_Meses, $_Dias) = split(";char:td;", $linea);
			
			//	inserto
			$sql = "INSERT INTO rh_empleado_antecedentes
					SET
						CodProceso = '".$CodProceso."',
						Secuencia = '".++$_Secuencia."',
						CodPersona = '".$CodPersona."',
						Organismo = '".changeUrl($_Organismo)."',
						FIngreso = '".$_FIngreso."',
						FEgreso = '".$_FEgreso."',
						TipoProceso = '".$TipoProceso."',
						Anios = '".$_Anios."',
						Meses = '".$_Meses."',
						Dias = '".$_Dias."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	sueldos
		$sueldos = split(";char:tr;", $detalles_sueldos);
		foreach ($sueldos as $linea) {
			list($_Secuencia, $_Periodo, $_CodConcepto, $_Monto) = split(";char:td;", $linea);
			
			//	inserto
			$sql = "INSERT INTO rh_relacionsueldojubilacion
					SET
						CodProceso = '".$CodProceso."',
						Secuencia = '".$_Secuencia."',
						CodPersona = '".$CodPersona."',
						TipoProceso = '".$TipoProceso."',
						Periodo = '".$_Periodo."',
						CodConcepto = '".$_CodConcepto."',
						Monto = '".$_Monto."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		echo "|$CodProceso";
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	modificar
	elseif ($accion == "modificar") {
		mysql_query("BEGIN");
		//	-----------------
		//	actualizo
		$sql = "UPDATE rh_proceso_jubilacion
				SET
					ObsProcesado = '".changeUrl($ObsProcesado)."',
					CodTipoNom = '".$CodTipoNom."',
					CodTipoTrabajador = '".$CodTipoTrabajador."',
					ObsCese = '".changeUrl($ObsCese)."',
					SitTra = '".$SitTra."',
					CodMotivoCes = '".$CodMotivoCes."',
					Fegreso = '".formatFechaAMD($Fegreso)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodProceso = '".$CodProceso."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	conformar
	elseif ($accion == "conformar") {
		mysql_query("BEGIN");
		//	-----------------
		//	actualizo
		$sql = "UPDATE rh_proceso_jubilacion
				SET
					Estado = 'CN',
					ConformadoPor = '".$ConformadoPor."',
					ObsConformado = '".changeUrl($ObsConformado)."',
					FechaConformado = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodProceso = '".$CodProceso."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	aprobar
	elseif ($accion == "aprobar") {
		mysql_query("BEGIN");
		//	-----------------
		//	actualizo
		$sql = "UPDATE rh_proceso_jubilacion
				SET
					Estado = 'AP',
					AprobadoPor = '".$AprobadoPor."',
					ObsAprobado = '".changeUrl($ObsAprobado)."',
					FechaAprobado = NOW(),
					FechaJubilacion = '".formatFechaAMD($Fegreso)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodProceso = '".$CodProceso."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	modifico empleado
		$sql = "UPDATE mastempleado
				SET
					CodTipoNom = '".$CodTipoNom."',
					CodTipoTrabajador = '".$CodTipoTrabajador."',
					CodMotivoCes = '".$CodMotivoCes."',
					Fegreso = '".formatFechaAMD($Fegreso)."',
					Estado = '".$SitTra."',
					ObsCese = '".changeUrl($ObsCese)."',
					MontoJubilacion = '".$MontoJubilacion."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodPersona = '".$CodPersona."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo nivelaciones anterior
		$FechaHasta = obtenerFechaFin($Fegreso, -1);
		$sql = "UPDATE rh_empleadonivelacion
				SET FechaHasta = '".formatFechaAMD($FechaHasta)."'
				WHERE
					CodPersona = '".$CodPersona."' AND
					FechaHasta = '0000-00-00'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto en nivelacion
		$Secuencia = getCodigo_2("rh_empleadonivelacion", "Secuencia", "CodPersona", $CodPersona, 6);
		$Secuencia = intval($Secuencia);
		$sql = "INSERT INTO rh_empleadonivelacion
				SET
					CodPersona = '".$CodPersona."',
					Secuencia = '".$Secuencia."',
					Fecha = '".formatFechaAMD($Fingreso)."',
					CodOrganismo = '".$CodOrganismo."',
					CodDependencia = '".$CodDependencia."',
					CodCargo = '".$CodCargo."',
					CodTipoNom = '".$CodTipoNom."',
					Estado = '".$SitTra."',
					FechaHasta = '0000-00-00',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto en nivelacion historial
		$SecuenciaH = getCodigo_2("rh_empleadonivelacionhistorial", "Secuencia", "CodPersona", $CodPersona, 6);
		$SecuenciaH = intval($SecuenciaH);
		$sql = "INSERT INTO rh_empleadonivelacionhistorial (
							CodPersona,
							Secuencia,
							Fecha,
							Organismo,
							Dependencia,
							Cargo,
							NivelSalarial,
							CategoriaCargo,
							TipoNomina,
							Estado,
							UltimoUsuario,
							UltimaFecha
				)
						SELECT
							en.CodPersona,
							'$Secuencia' AS Secuencia,
							en.Fecha,
							o.Organismo,
							d.Dependencia,
							pt.DescripCargo AS Cargo,
							pt.NivelSalarial,
							md.Descripcion AS CategoriaCargo,
							tn.Nomina AS TipoNomina,
							en.Estado,
							'".$_SESSION["USUARIO_ACTUAL"]."' AS UltimoUsuario,
							NOW() AS UltimaFecha
						FROM
							rh_empleadonivelacion en
							INNER JOIN mastorganismos o ON (o.CodOrganismo = en.CodOrganismo)
							INNER JOIN mastdependencias d ON (d.CodDependencia = en.CodDependencia)
							INNER JOIN tiponomina tn ON (tn.CodTipoNom = en.CodTipoNom)
							INNER JOIN rh_puestos pt ON (pt.CodCargo = en.CodCargo)
							LEFT JOIN mastmiscelaneosdet md ON (md.CodDetalle = pt.CategoriaCargo AND
																md.CodMaestro = 'CATCARGO')
						WHERE
							en.CodPersona = '".$CodPersona."' AND
							en.Secuencia = '".$Secuencia."'";	fwrite($__archivo, $sql.";\n\n");
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto en historial
		$Secuencia = getCodigo_2("rh_historial", "Secuencia", "CodPersona", $CodPersona, 6);
		$Secuencia = intval($Secuencia);
		$sql = "INSERT INTO rh_historial (
							CodPersona,
							Secuencia,
							Periodo,
							Fingreso,
							Organismo,
							Dependencia,
							Cargo,
							NivelSalarial,
							CategoriaCargo,
							TipoNomina,
							TipoPago,
							Estado,
							MotivoCese,
							Fegreso,
							ObsCese,
							TipoTrabajador,
							UltimoUsuario,
							UltimaFecha
				)
						SELECT
							e.CodPersona,
							'$Secuencia' AS Secuencia,
							NOW() AS Periodo,
							e.Fingreso,
							o.Organismo,
							d.Dependencia,
							pt.DescripCargo AS Cargo,
							pt.NivelSalarial,
							md.Descripcion AS CategoriaCargo,
							tn.Nomina AS TipoNomina,
							tp.TipoPago,
							e.Estado,
							mc.MotivoCese,
							e.Fegreso,
							e.ObsCese,
							tt.TipoTrabajador,
							'".$_SESSION["USUARIO_ACTUAL"]."' AS UltimoUsuario,
							NOW() AS UltimaFecha
						FROM
							mastempleado e
							INNER JOIN mastorganismos o ON (o.CodOrganismo = e.CodOrganismo)
							INNER JOIN mastdependencias d ON (d.CodDependencia = e.CodDependencia)
							INNER JOIN tiponomina tn ON (tn.CodTipoNom = e.CodTipoNom)
							INNER JOIN rh_tipotrabajador tt ON (tt.CodTipoTrabajador = e.CodTipoTrabajador)
							INNER JOIN masttipopago tp ON (tp.CodTipoPago = e.CodTipoPago)
							INNER JOIN rh_puestos pt ON (pt.CodCargo = e.CodCargo)
							LEFT JOIN mastmiscelaneosdet md ON (md.CodDetalle = pt.CategoriaCargo AND
																md.CodMaestro = 'CATCARGO')
							LEFT JOIN rh_motivocese mc ON (mc.CodMotivoCes = e.CodMotivoCes)
						WHERE e.CodPersona = '".$CodPersona."'";	fwrite($__archivo, $sql.";\n\n");
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
}

//	proceso de pension x invalidez
elseif ($modulo == "pensiones_invalidez") {
	//	nuevo
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	-----------------
		//	valido
		$SueldoActual = setNumero($UltimoSueldo);
		$Monto = setNumero($MontoPension);
		$MontoMin = $SueldoActual * $_PARAMETRO['PENPORMIN'] / 100;
		$MontoMax = $SueldoActual * $_PARAMETRO['PENPORMAX'] / 100;
		if ($Monto < $MontoMin || $Monto > $MontoMax) die("El Sueldo NO puede ser menor que el ".$_PARAMETRO['PENPORMIN']."% ni mayor que el ".$_PARAMETRO['PENPORMAX']."% del Ultimo Sueldo");
		
		//	genero codigo
		$CodProceso = getCodigo("rh_proceso_pension", "CodProceso", 4);
		
		//	inserto
		$sql = "INSERT INTO rh_proceso_pension
				SET
					CodProceso = '".$CodProceso."',
					CodOrganismo = '".$CodOrganismo."',
					CodPersona = '".$CodPersona."',
					AniosServicio = '".$AniosServicio."',
					Edad = '".$Edad."',
					CodDependencia = '".$CodDependencia."',
					NomDependencia = '".changeUrl($NomDependencia)."',
					CodCargo = '".$CodCargo."',
					DescripCargo = '".changeUrl($DescripCargo)."',
					ProcesadoPor = '".$ProcesadoPor."',
					FechaProcesado = NOW(),
					ObsProcesado = '".changeUrl($ObsProcesado)."',
					MontoPension = '".setNumero($MontoPension)."',
					Fingreso = '".formatFechaAMD($Fingreso)."',
					UltimoSueldo = '".setNumero($UltimoSueldo)."',
					TipoPension = '".$TipoPension."',
					MotivoPension = '".$MotivoPension."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		echo "|$CodProceso";
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	modificar
	elseif ($accion == "modificar") {
		mysql_query("BEGIN");
		//	-----------------
		//	valido
		$SueldoActual = setNumero($UltimoSueldo);
		$Monto = setNumero($MontoPension);
		$MontoMin = $SueldoActual * $_PARAMETRO['PENPORMIN'] / 100;
		$MontoMax = $SueldoActual * $_PARAMETRO['PENPORMAX'] / 100;
		if ($Monto < $MontoMin || $Monto > $MontoMax) die("El Sueldo NO puede ser menor que el ".$_PARAMETRO['PENPORMIN']."% ni mayor que el ".$_PARAMETRO['PENPORMAX']."% del Ultimo Sueldo");
		
		//	actualizo
		$sql = "UPDATE rh_proceso_pension
				SET
					MotivoPension = '".$MotivoPension."',
					MontoPension = '".setNumero($MontoPension)."',
					ObsProcesado = '".changeUrl($ObsProcesado)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodProceso = '".$CodProceso."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	conformar
	elseif ($accion == "conformar") {
		mysql_query("BEGIN");
		//	-----------------
		//	actualizo
		$sql = "UPDATE rh_proceso_pension
				SET
					Estado = 'CN',
					ConformadoPor = '".$ConformadoPor."',
					ObsConformado = '".changeUrl($ObsConformado)."',
					FechaConformado = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodProceso = '".$CodProceso."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	aprobar
	elseif ($accion == "aprobar") {
		mysql_query("BEGIN");
		//	-----------------
		//	actualizo
		$sql = "UPDATE rh_proceso_pension
				SET
					Estado = 'AP',
					AprobadoPor = '".$AprobadoPor."',
					ObsAprobado = '".changeUrl($ObsAprobado)."',
					FechaAprobado = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodProceso = '".$CodProceso."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	modifico empleado
		$sql = "UPDATE mastempleado
				SET
					CodTipoNom = '".$CodTipoNom."',
					CodTipoTrabajador = '".$CodTipoTrabajador."',
					CodMotivoCes = '".$CodMotivoCes."',
					Fegreso = '".formatFechaAMD($Fegreso)."',
					Estado = '".$SitTra."',
					ObsCese = '".changeUrl($ObsCese)."',
					SueldoAnterior = '".setNumero($UltimoSueldo)."',
					SueldoActual = '".setNumero($MontoPension)."',
					MontoJubilacion = '".setNumero($MontoPension)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodPersona = '".$CodPersona."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo nivelaciones anterior
		$FechaHasta = obtenerFechaFin($Fegreso, -1);
		$sql = "UPDATE rh_empleadonivelacion
				SET FechaHasta = '".formatFechaAMD($FechaHasta)."'
				WHERE
					CodPersona = '".$CodPersona."' AND
					FechaHasta = '0000-00-00'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto en nivelacion
		$Secuencia = getCodigo_2("rh_empleadonivelacion", "Secuencia", "CodPersona", $CodPersona, 6);
		$Secuencia = intval($Secuencia);
		$sql = "INSERT INTO rh_empleadonivelacion
				SET
					CodPersona = '".$CodPersona."',
					Secuencia = '".$Secuencia."',
					Fecha = '".formatFechaAMD($Fingreso)."',
					CodOrganismo = '".$CodOrganismo."',
					CodDependencia = '".$CodDependencia."',
					CodCargo = '".$CodCargo."',
					CodTipoNom = '".$CodTipoNom."',
					Estado = '".$SitTra."',
					FechaHasta = '0000-00-00',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto en nivelacion historial
		$SecuenciaH = getCodigo_2("rh_empleadonivelacionhistorial", "Secuencia", "CodPersona", $CodPersona, 6);
		$SecuenciaH = intval($SecuenciaH);
		$sql = "INSERT INTO rh_empleadonivelacionhistorial (
							CodPersona,
							Secuencia,
							Fecha,
							Organismo,
							Dependencia,
							Cargo,
							NivelSalarial,
							CategoriaCargo,
							TipoNomina,
							Estado,
							UltimoUsuario,
							UltimaFecha
				)
						SELECT
							en.CodPersona,
							'$SecuenciaH' AS Secuencia,
							en.Fecha,
							o.Organismo,
							d.Dependencia,
							pt.DescripCargo AS Cargo,
							pt.NivelSalarial,
							md.Descripcion AS CategoriaCargo,
							tn.Nomina AS TipoNomina,
							en.Estado,
							'".$_SESSION["USUARIO_ACTUAL"]."' AS UltimoUsuario,
							NOW() AS UltimaFecha
						FROM
							rh_empleadonivelacion en
							INNER JOIN mastorganismos o ON (o.CodOrganismo = en.CodOrganismo)
							INNER JOIN mastdependencias d ON (d.CodDependencia = en.CodDependencia)
							INNER JOIN tiponomina tn ON (tn.CodTipoNom = en.CodTipoNom)
							INNER JOIN rh_puestos pt ON (pt.CodCargo = en.CodCargo)
							LEFT JOIN mastmiscelaneosdet md ON (md.CodDetalle = pt.CategoriaCargo AND
																md.CodMaestro = 'CATCARGO')
						WHERE
							en.CodPersona = '".$CodPersona."' AND
							en.Secuencia = '".$Secuencia."'";	fwrite($__archivo, $sql.";\n\n");
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto en historial
		$Secuencia = getCodigo_2("rh_historial", "Secuencia", "CodPersona", $CodPersona, 6);
		$Secuencia = intval($Secuencia);
		$sql = "INSERT INTO rh_historial (
							CodPersona,
							Secuencia,
							Periodo,
							Fingreso,
							Organismo,
							Dependencia,
							Cargo,
							NivelSalarial,
							CategoriaCargo,
							TipoNomina,
							TipoPago,
							Estado,
							MotivoCese,
							Fegreso,
							ObsCese,
							TipoTrabajador,
							UltimoUsuario,
							UltimaFecha
				)
						SELECT
							e.CodPersona,
							'$Secuencia' AS Secuencia,
							NOW() AS Periodo,
							e.Fingreso,
							o.Organismo,
							d.Dependencia,
							pt.DescripCargo AS Cargo,
							pt.NivelSalarial,
							md.Descripcion AS CategoriaCargo,
							tn.Nomina AS TipoNomina,
							tp.TipoPago,
							e.Estado,
							mc.MotivoCese,
							e.Fegreso,
							e.ObsCese,
							tt.TipoTrabajador,
							'".$_SESSION["USUARIO_ACTUAL"]."' AS UltimoUsuario,
							NOW() AS UltimaFecha
						FROM
							mastempleado e
							INNER JOIN mastorganismos o ON (o.CodOrganismo = e.CodOrganismo)
							INNER JOIN mastdependencias d ON (d.CodDependencia = e.CodDependencia)
							INNER JOIN tiponomina tn ON (tn.CodTipoNom = e.CodTipoNom)
							INNER JOIN rh_tipotrabajador tt ON (tt.CodTipoTrabajador = e.CodTipoTrabajador)
							INNER JOIN masttipopago tp ON (tp.CodTipoPago = e.CodTipoPago)
							INNER JOIN rh_puestos pt ON (pt.CodCargo = e.CodCargo)
							LEFT JOIN mastmiscelaneosdet md ON (md.CodDetalle = pt.CategoriaCargo AND
																md.CodMaestro = 'CATCARGO')
							LEFT JOIN rh_motivocese mc ON (mc.CodMotivoCes = e.CodMotivoCes)
						WHERE e.CodPersona = '".$CodPersona."'";	fwrite($__archivo, $sql.";\n\n");
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
}

//	proceso de pension x sobreviviente
elseif ($modulo == "pensiones_sobreviviente") {
	//	nuevo
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	-----------------
		//	valido
		if ($MontoJubilacion > ($SueldoBase * $_PARAMETRO['PORCLIMITJUB'] / 100)) die("El Monto de Jubilaci&oacute;n no puede ser mayor al ".$_PARAMETRO['PORCLIMITJUB']."% del Sueldo Base");
		
		//	genero codigo
		$CodProceso = getCodigo("rh_proceso_pension", "CodProceso", 4);
		
		//	inserto
		$sql = "INSERT INTO rh_proceso_pension
				SET
					CodProceso = '".$CodProceso."',
					CodOrganismo = '".$CodOrganismo."',
					CodPersona = '".$CodPersona."',
					AniosServicio = '".$AniosServicio."',
					AniosServicioExceso = '".$AniosServicioExceso."',
					Edad = '".$Edad."',
					CodDependencia = '".$CodDependencia."',
					NomDependencia = '".changeUrl($NomDependencia)."',
					CodCargo = '".$CodCargo."',
					DescripCargo = '".changeUrl($DescripCargo)."',
					ProcesadoPor = '".$ProcesadoPor."',
					FechaProcesado = NOW(),
					ObsProcesado = '".changeUrl($ObsProcesado)."',
					MontoJubilacion = '".$MontoJubilacion."',
					Coeficiente = '".$Coeficiente."',
					TotalSueldo = '".$TotalSueldo."',
					TotalPrimas = '".$TotalPrimas."',
					Periodo = NOW(),
					Fingreso = '".formatFechaAMD($Fingreso)."',
					UltimoSueldo = '".setNumero($UltimoSueldo)."',
					MontoPension = '".setNumero($MontoPension)."',
					TipoPension = '".$TipoPension."',
					MotivoPension = '".$MotivoPension."',
					Estado = '".$Estado."',
					CodTipoNom = '".$CodTipoNom."',
					CodTipoTrabajador = '".$CodTipoTrabajador."',
					ObsCese = '".changeUrl($ObsCese)."',
					SitTra = '".$SitTra."',
					CodMotivoCes = '".$CodMotivoCes."',
					Fegreso = '".formatFechaAMD($Fegreso)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	antecedentes
		$_Secuencia = 0;
		$antecedentes = split(";char:tr;", $detalles_antecedentes);
		foreach ($antecedentes as $linea) {
			list($_Organismo, $_FIngreso, $_FEgreso, $_Anios, $_Meses, $_Dias) = split(";char:td;", $linea);
			//	inserto
			$sql = "INSERT INTO rh_empleado_antecedentes
					SET
						CodProceso = '".$CodProceso."',
						Secuencia = '".++$_Secuencia."',
						CodPersona = '".$CodPersona."',
						Organismo = '".changeUrl($_Organismo)."',
						FIngreso = '".$_FIngreso."',
						FEgreso = '".$_FEgreso."',
						TipoProceso = '".$TipoProceso."',
						Anios = '".$_Anios."',
						Meses = '".$_Meses."',
						Dias = '".$_Dias."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	sueldos
		$sueldos = split(";char:tr;", $detalles_sueldos);
		foreach ($sueldos as $linea) {
			list($_Secuencia, $_Periodo, $_CodConcepto, $_Monto) = split(";char:td;", $linea);
			//	inserto
			$sql = "INSERT INTO rh_relacionsueldojubilacion
					SET
						CodProceso = '".$CodProceso."',
						Secuencia = '".$_Secuencia."',
						CodPersona = '".$CodPersona."',
						TipoProceso = '".$TipoProceso."',
						Periodo = '".$_Periodo."',
						CodConcepto = '".$_CodConcepto."',
						Monto = '".$_Monto."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	beneficiarios
		$_Secuencia = 0;
		$beneficiarios = split(";char:tr;", $detalles_beneficiarios);
		foreach ($beneficiarios as $linea) {
			list($_NroDocumento, $_NombreCompleto, $_FlagPrincipal, $_Parentesco, $_FechaNacimiento, $_Sexo, $_FlagIncapacitados, $_FlagEstudia) = split(";char:td;", $linea);
			//	inserto
			$sql = "INSERT INTO rh_beneficiariopension
					SET
						CodProceso = '".$CodProceso."',
						Secuencia = '".++$_Secuencia."',
						CodPersona = '".$CodPersona."',
						NroDocumento = '".$_NroDocumento."',
						NombreCompleto = '".changeUrl($_NombreCompleto)."',
						FechaNacimiento = '".$_FechaNacimiento."',
						Sexo = '".$_Sexo."',
						FlagIncapacitados = '".$_FlagIncapacitados."',
						FlagEstudia = '".$_FlagEstudia."',
						FlagPrincipal = '".$_FlagPrincipal."',
						Parentesco = '".$_Parentesco."',
						Estado = 'A',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		echo "|$CodProceso";
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	modificar
	elseif ($accion == "modificar") {
		mysql_query("BEGIN");
		//	-----------------
		//	inserto
		$sql = "UPDATE rh_proceso_pension
				SET
					ObsProcesado = '".changeUrl($ObsProcesado)."',
					CodTipoNom = '".$CodTipoNom."',
					CodTipoTrabajador = '".$CodTipoTrabajador."',
					ObsCese = '".changeUrl($ObsCese)."',
					SitTra = '".$SitTra."',
					CodMotivoCes = '".$CodMotivoCes."',
					Fegreso = '".formatFechaAMD($Fegreso)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodProceso = '".$CodProceso."'";	fwrite($__archivo, $sql.";\n\n");
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	beneficiarios
		$sql = "DELETE FROM rh_beneficiariopension WHERE CodProceso = '".$CodProceso."'";	fwrite($__archivo, $sql.";\n\n");
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		$_Secuencia = 0;
		$beneficiarios = split(";char:tr;", $detalles_beneficiarios);
		foreach ($beneficiarios as $linea) {
			list($_NroDocumento, $_NombreCompleto, $_FlagPrincipal, $_Parentesco, $_FechaNacimiento, $_Sexo, $_FlagIncapacitados, $_FlagEstudia) = split(";char:td;", $linea);
			//	inserto
			$sql = "INSERT INTO rh_beneficiariopension
					SET
						CodProceso = '".$CodProceso."',
						Secuencia = '".++$_Secuencia."',
						CodPersona = '".$CodPersona."',
						NroDocumento = '".$_NroDocumento."',
						NombreCompleto = '".changeUrl($_NombreCompleto)."',
						FechaNacimiento = '".$_FechaNacimiento."',
						Sexo = '".$_Sexo."',
						FlagIncapacitados = '".$_FlagIncapacitados."',
						FlagEstudia = '".$_FlagEstudia."',
						FlagPrincipal = '".$_FlagPrincipal."',
						Parentesco = '".$_Parentesco."',
						Estado = 'A',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	conformar
	elseif ($accion == "conformar") {
		mysql_query("BEGIN");
		//	-----------------
		//	actualizo
		$sql = "UPDATE rh_proceso_pension
				SET
					Estado = 'CN',
					ConformadoPor = '".$ConformadoPor."',
					ObsConformado = '".changeUrl($ObsConformado)."',
					FechaConformado = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodProceso = '".$CodProceso."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	aprobar
	elseif ($accion == "aprobar") {
		mysql_query("BEGIN");
		//	-----------------
		//	actualizo
		$sql = "UPDATE rh_proceso_pension
				SET
					Estado = 'AP',
					AprobadoPor = '".$AprobadoPor."',
					ObsAprobado = '".changeUrl($ObsAprobado)."',
					FechaAprobado = NOW(),
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodProceso = '".$CodProceso."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	modifico empleado
		$sql = "UPDATE mastempleado
				SET
					CodTipoNom = '".$CodTipoNom."',
					CodTipoTrabajador = '".$CodTipoTrabajador."',
					CodMotivoCes = '".$CodMotivoCes."',
					Fegreso = '".formatFechaAMD($Fegreso)."',
					Estado = '".$SitTra."',
					ObsCese = '".changeUrl($ObsCese)."',
					MontoJubilacion = '".$MontoJubilacion."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodPersona = '".$CodPersona."'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo nivelaciones anterior
		$FechaHasta = obtenerFechaFin($Fegreso, -1);
		$sql = "UPDATE rh_empleadonivelacion
				SET FechaHasta = '".formatFechaAMD($FechaHasta)."'
				WHERE
					CodPersona = '".$CodPersona."' AND
					FechaHasta = '0000-00-00'";	fwrite($__archivo, $sql.";\n\n");
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto en nivelacion
		$Secuencia = getCodigo_2("rh_empleadonivelacion", "Secuencia", "CodPersona", $CodPersona, 6);
		$Secuencia = intval($Secuencia);
		$sql = "INSERT INTO rh_empleadonivelacion
				SET
					CodPersona = '".$CodPersona."',
					Secuencia = '".$Secuencia."',
					Fecha = '".formatFechaAMD($Fingreso)."',
					CodOrganismo = '".$CodOrganismo."',
					CodDependencia = '".$CodDependencia."',
					CodCargo = '".$CodCargo."',
					CodTipoNom = '".$CodTipoNom."',
					Estado = '".$SitTra."',
					FechaHasta = '0000-00-00',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";	fwrite($__archivo, $sql.";\n\n");
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto en nivelacion historial
		$SecuenciaH = getCodigo_2("rh_empleadonivelacionhistorial", "Secuencia", "CodPersona", $CodPersona, 6);
		$SecuenciaH = intval($SecuenciaH);
		$sql = "INSERT INTO rh_empleadonivelacionhistorial (
							CodPersona,
							Secuencia,
							Fecha,
							Organismo,
							Dependencia,
							Cargo,
							NivelSalarial,
							CategoriaCargo,
							TipoNomina,
							Estado,
							UltimoUsuario,
							UltimaFecha
				)
						SELECT
							en.CodPersona,
							'$Secuencia' AS Secuencia,
							en.Fecha,
							o.Organismo,
							d.Dependencia,
							pt.DescripCargo AS Cargo,
							pt.NivelSalarial,
							md.Descripcion AS CategoriaCargo,
							tn.Nomina AS TipoNomina,
							en.Estado,
							'".$_SESSION["USUARIO_ACTUAL"]."' AS UltimoUsuario,
							NOW() AS UltimaFecha
						FROM
							rh_empleadonivelacion en
							INNER JOIN mastorganismos o ON (o.CodOrganismo = en.CodOrganismo)
							INNER JOIN mastdependencias d ON (d.CodDependencia = en.CodDependencia)
							INNER JOIN tiponomina tn ON (tn.CodTipoNom = en.CodTipoNom)
							INNER JOIN rh_puestos pt ON (pt.CodCargo = en.CodCargo)
							LEFT JOIN mastmiscelaneosdet md ON (md.CodDetalle = pt.CategoriaCargo AND
																md.CodMaestro = 'CATCARGO')
						WHERE
							en.CodPersona = '".$CodPersona."' AND
							en.Secuencia = '".$Secuencia."'";	fwrite($__archivo, $sql.";\n\n");
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto en historial
		$Secuencia = getCodigo_2("rh_historial", "Secuencia", "CodPersona", $CodPersona, 6);
		$Secuencia = intval($Secuencia);
		$sql = "INSERT INTO rh_historial (
							CodPersona,
							Secuencia,
							Periodo,
							Fingreso,
							Organismo,
							Dependencia,
							Cargo,
							NivelSalarial,
							CategoriaCargo,
							TipoNomina,
							TipoPago,
							Estado,
							MotivoCese,
							Fegreso,
							ObsCese,
							TipoTrabajador,
							UltimoUsuario,
							UltimaFecha
				)
						SELECT
							e.CodPersona,
							'$Secuencia' AS Secuencia,
							NOW() AS Periodo,
							e.Fingreso,
							o.Organismo,
							d.Dependencia,
							pt.DescripCargo AS Cargo,
							pt.NivelSalarial,
							md.Descripcion AS CategoriaCargo,
							tn.Nomina AS TipoNomina,
							tp.TipoPago,
							e.Estado,
							mc.MotivoCese,
							e.Fegreso,
							e.ObsCese,
							tt.TipoTrabajador,
							'".$_SESSION["USUARIO_ACTUAL"]."' AS UltimoUsuario,
							NOW() AS UltimaFecha
						FROM
							mastempleado e
							INNER JOIN mastorganismos o ON (o.CodOrganismo = e.CodOrganismo)
							INNER JOIN mastdependencias d ON (d.CodDependencia = e.CodDependencia)
							INNER JOIN tiponomina tn ON (tn.CodTipoNom = e.CodTipoNom)
							INNER JOIN rh_tipotrabajador tt ON (tt.CodTipoTrabajador = e.CodTipoTrabajador)
							INNER JOIN masttipopago tp ON (tp.CodTipoPago = e.CodTipoPago)
							INNER JOIN rh_puestos pt ON (pt.CodCargo = e.CodCargo)
							LEFT JOIN mastmiscelaneosdet md ON (md.CodDetalle = pt.CategoriaCargo AND
																md.CodMaestro = 'CATCARGO')
							LEFT JOIN rh_motivocese mc ON (mc.CodMotivoCes = e.CodMotivoCes)
						WHERE e.CodPersona = '".$CodPersona."'";	fwrite($__archivo, $sql.";\n\n");
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto los beneficiarios en persona
		$sql = "";
		//	-----------------
		mysql_query("COMMIT");
	}
}

?>