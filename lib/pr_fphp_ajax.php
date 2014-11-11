<?php
include("fphp.php");
include("pr_fphp.php");
///////////////////////////////////////////////////////////////////////////////
//	PARA AJAX
///////////////////////////////////////////////////////////////////////////////
//	fideicomiso
if ($modulo == "fideicomiso") {
	//	agregar
	if ($accion == "actualizarAcumulados") {
		list($anio, $mes) = SPLIT( '[-./]', $periodo);
		$anio_ant = (int) $anio;
		$mes_ant = (int) $mes;
		$mes_ant--; if ($mes_ant == 0) { $mes_ant = 12; $anio_ant--; }
		if ($mes_ant < 10) $mes_ant = "0$mes_ant";
		$periodo_ant = "$anio_ant-$mes_ant";		
		$tasa = tasaInteres($periodo);
		$dias_mes = getDiasMes($periodo);
		
		//	obtengo cada trabajador seleccionado
		$registros = split(";", $detalles);
		foreach ($registros as $registro) {
			list($codpersona, $mes_monto, $mes_dias, $complemento_monto, $complemento_dias) = split("[|]", $registro);
			
			// consulto los acumulados
			$sql = "SELECT *
					FROM pr_acumuladofideicomiso
					WHERE
						CodPersona = '".$codpersona."' AND
						CodOrganismo = '".$organismo."'";
			$query_mast = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_mast) != 0) { $field_mast = mysql_fetch_array($query_mast); $update_mast = true; } else $update_mast = false;
			
			// consulto los acumulados del periodo anterior
			$sql = "SELECT SUM(Transaccion) AS Transaccion, SUM(TransaccionFide) AS TransaccionFide, SUM(Complemento) AS Complemento
					FROM pr_acumuladofideicomisodetalle
					WHERE
						CodPersona = '".$codpersona."' AND
						CodOrganismo = '".$organismo."' AND
						Periodo < '".$periodo."'";
			$query_det_ant = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_det_ant) != 0) $field_det_ant = mysql_fetch_array($query_det_ant);
			
			// consulto los detalles para el periodo
			$sql = "SELECT * 
					FROM pr_acumuladofideicomisodetalle 
					WHERE 
						CodPersona = '".$codpersona."' AND 
						Periodo = '".$periodo."'";
			$query_detalle = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_detalle) != 0) { $field_detalle = mysql_fetch_array($query_detalle); $update_detalle = true; } else $update_detalle = false;
			
			//	inserto o actualizo acumulados
			if ($update_detalle) {
				//	obtengo acumulado antiguedad
				$acumuladoprovdias = $field_mast['AcumuladoProvDias'] + $mes_dias - $field_detalle['Dias'];
				$acumuladoprov = $field_mast['AcumuladoProv'] + $mes_monto - $field_detalle['Transaccion'];
				
				//	obtengo acumulado fideicomiso
				$prestacion_antiguedad = $field_mast['AcumuladoInicialProv'] + $acumuladoprov;
				//$interes_mensual = ($prestacion_antiguedad * $tasa / 100) * $dias_mes / 365;
				$interes_mensual = (($field_mast['AcumuladoInicialProv'] + $field_det_ant['Transaccion'] + $field_det_ant['Complemento'] + $field_detalle['Transaccion'] + $complemento_monto) * $tasa / 100) * $dias_mes / 365;
				$acumuladofide = $field_mast['AcumuladoFide'] + $interes_mensual - $field_detalle['TransaccionFide'];
				
				//	actualizo acumulado
				$sql = "UPDATE pr_acumuladofideicomiso
						SET
							AcumuladoProvDias = '".$acumuladoprovdias."',
							AcumuladoProv = '".$acumuladoprov."',
							AcumuladoFide = '".$acumuladofide."',
							AcumuladoDiasAdicional = '".$complemento_dias."',
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()
						WHERE CodPersona = '".$codpersona."'";
				$query_update = mysql_query($sql) or die ($sql.mysql_error());
					
				//	actualizo detalle
				$sql = "UPDATE pr_acumuladofideicomisodetalle
						SET
							Transaccion = '".$mes_monto."',
							TransaccionFide = '".$interes_mensual."',
							Dias = '".$mes_dias."',
							DiasAdicional = '".$complemento_dias."',
							Complemento = '".$complemento_monto."',
							AnteriorProv = '".($field_mast['AcumuladoInicialProv'] + $field_det_ant['Transaccion'])."',
							AnteriorFide = '".($field_mast['AcumuladoInicialFide'] + $field_det_ant['TransaccionFide'])."',
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()
						WHERE
							CodPersona = '".$codpersona."' AND
							Periodo = '".$periodo."'";
				$query_update = mysql_query($sql) or die ($sql.mysql_error());
								
			} else {
				//	obtengo acumulado antiguedad
				$acumuladoprovdias = $field_mast['AcumuladoProvDias'] + $mes_dias;
				$acumuladoprov = $field_mast['AcumuladoProv'] + $mes_monto;
				$acumuladocompdias = $field_mast['AcumuladoDiasAdicional'] + $complemento_dias;
				
				//	obtengo acumulado fideicomiso
				$prestacion_antiguedad = $field_mast['AcumuladoInicialProv'] + $acumuladoprov;
				//$interes_mensual = ($prestacion_antiguedad * $tasa / 100) * $dias_mes / 365;
				$interes_mensual = (($field_mast['AcumuladoInicialProv'] + $field_det_ant['Transaccion'] + $field_det_ant['Complemento'] + $field_detalle['Transaccion'] + $complemento_monto) * $tasa / 100) * $dias_mes / 365;
				$acumuladofide = $field_mast['AcumuladoFide'] + $interes_mensual;
				
				// inserto o actualizo acumulado
				if ($update_mast) {
					//	actualizo acumulado
					$sql = "UPDATE pr_acumuladofideicomiso
							SET
								AcumuladoProvDias = '".$acumuladoprovdias."',
								AcumuladoProv = '".$acumuladoprov."',
								AcumuladoFide = '".$acumuladofide."',
								AcumuladoDiasAdicional = '".$acumuladocompdias."',
								UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
								UltimaFecha = NOW()
							WHERE CodPersona = '".$codpersona."'";
					$query_update = mysql_query($sql) or die ($sql.mysql_error());
				} else {
					//	inserto acumulado
					$sql = "INSERT INTO pr_acumuladofideicomiso (
										CodPersona, 
										CodOrganismo,
										AcumuladoProvDias,
										AcumuladoProv,
										AcumuladoFide,
										AcumuladoDiasAdicional,
										UltimoUsuario,
										UltimaFecha
							) VALUES (
										'".$codpersona."',
										'".$organismo."',
										'".$acumuladoprovdias."',
										'".$acumuladoprov."',
										'".$acumuladofide."',
										'".$complemento_dias."',
										'".$_SESSION['USUARIO_ACTUAL']."',
										NOW()
							)";
					$query_insert = mysql_query($sql) or die ($sql.mysql_error());
				}
				
				//	inserto detalle
				$sql = "INSERT INTO pr_acumuladofideicomisodetalle (
									CodPersona,
									CodOrganismo,
									Periodo,
									AnteriorProv,
									AnteriorFide,
									Transaccion,
									TransaccionFide,
									Dias,
									DiasAdicional,
									Complemento,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$codpersona."',
									'".$organismo."',
									'".$periodo."',
									".($field_mast['AcumuladoInicialProv'] + $field_det_ant['Transaccion']).",
									".($field_mast['AcumuladoInicialFide'] + $field_det_ant['TransaccionFide']).",
									'".$mes_monto."',
									'".$interes_mensual."',
									'".$mes_dias."',
									'".$complemento_dias."',
									'".$complemento_monto."',
									'".$_SESSION['USUARIO_ACTUAL']."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
	}
}
?>