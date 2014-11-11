<?php
include("fphp_ap.php");
	$__archivo = fopen("query.sql", "w+");
//	fwrite($__archivo, $sql.";\n\n");
///////////////////////////////////////////////////////////////////////////////
//	SCRIPTS PARA AJAX
///////////////////////////////////////////////////////////////////////////////
//	CLASIFICACION DE DOCUMENTOS
if ($modulo == "DOCUMENTOS-CLASIFICACION") {
	connect();
	//	INSERTAR
	if ($accion == "GUARDAR") {
		//	consulto si existe...
		$sql = "SELECT DocumentoClasificacion
				FROM ap_documentosclasificacion
				WHERE DocumentoClasificacion = '".$codigo."'";
		$query_consulta = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_consulta) != 0) die("¡ERROR: Código ya ingresado!");
		
		//	inserto
		$sql = "INSERT INTO ap_documentosclasificacion (
							DocumentoClasificacion,
							Descripcion,
							CodCuenta,
							FlagItem,
							FlagCliente,
							FlagCompromiso,
							FlagTransaccion,
							Estado,
							UltimoUsuario,
							UltimaFecha
						) VALUES (
							'".$codigo."',
							'".($descripcion)."',
							'".$codcuenta."',
							'".$flagitem."',
							'".$flagcliente."',
							'".$flagcompromiso."',
							'".$flagtransaccion."',
							'".$estado."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW())";
		$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		
		//	impuestos
		$sql = "DELETE FROM ap_obligacionesimpuesto WHERE ";
	}
	
	//	ACTUALIZAR
	elseif ($accion == "ACTUALIZAR") {
		$sql = "UPDATE ap_documentosclasificacion 
				SET
					Descripcion = '".($descripcion)."',
					CodCuenta = '".$codcuenta."',
					FlagItem = '".$flagitem."',
					FlagCliente = '".$flagcliente."',
					FlagCompromiso = '".$flagcompromiso."',
					FlagTransaccion = '".$flagtransaccion."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					DocumentoClasificacion = '".$codigo."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	ELIMINAR
	elseif ($accion == "ELIMINAR") {
		//	consulto si existe...
		$sql = "SELECT FlagTransaccion 
				FROM ap_documentosclasificacion
				WHERE 
					DocumentoClasificacion = '".$registro."' AND
					FlagTransaccion = 'S'";
		$query_consulta = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_consulta) != 0) die("¡ERROR: Este registro es una transacción del sistema!");
		
		//	elimino
		$sql = "DELETE FROM ap_documentosclasificacion WHERE DocumentoClasificacion = '".$registro."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
	}
}

//	CUENTAS BANCARIAS
elseif ($modulo == "CUENTA-BANCARIA") {
	connect();
	//	INSERTAR
	if ($accion == "INSERTAR") {
		//	consulto si existe...
		$sql = "SELECT NroCuenta FROM ap_ctabancaria WHERE NroCuenta = '".$nrocuenta."'";
		$query_consulta = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_consulta) != 0) die("¡ERROR: Nro. de Cuenta ya ingresado!");
		
		//	inserto
		$sql = "INSERT INTO ap_ctabancaria (
							NroCuenta,
							CodOrganismo,
							CodBanco,
							Descripcion,
							CtaBanco,
							TipoCuenta,
							FechaApertura,
							CodCuenta,
							Agencia,
							Distrito,
							Atencion,
							Cargo,
							FlagConciliacionBancaria,
							FlagConciliacionCP,
							FlagDebitoBancario,
							Estado,
							UltimoUsuario,
							UltimaFecha
						) VALUES (
							'".$nrocuenta."',
							'".$organismo."',
							'".$banco."',
							'".($descripcion)."',
							'".$ctabanco."',
							'".$tcuenta."',
							'".formatFechaDMA($fapertura)."',
							'".$codcuenta."',
							'".($agencia)."',
							'".($distrito)."',
							'".($atencion)."',
							'".($cargo)."',
							'".$flagconciliacionb."',
							'".$flagconciliacionr."',
							'".$flagdebito."',
							'".$estado."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW())";
		$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		
		// inserto los tios de pagos disponibles
		if ($detalles != "") {
			$detalle = split(";", $detalles);
			foreach ($detalle as $tpago) {
				$sql = "INSERT INTO ap_ctabancariatipopago (
								NroCuenta,
								CodTipoPago,
								UltimoUsuario,
								UltimaFecha
							) VALUES (
								'".$nrocuenta."',
								'".$tpago."',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW())";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
		
		//	inserto
		$sql = "INSERT INTO ap_ctabancariabalance (
							NroCuenta,
							UltimoUsuario,
							UltimaFecha
						) VALUES (
							'".$nrocuenta."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW())";
		$query_insert = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	INSERTAR
	elseif ($accion == "ACTUALIZAR") {
		//	actualizo
		$sql = "UPDATE ap_ctabancaria 
				SET
					CodOrganismo = '".$organismo."',
					CodBanco = '".$banco."',
					Descripcion = '".($descripcion)."',
					CtaBanco = '".$ctabanco."',
					TipoCuenta = '".$tcuenta."',
					FechaApertura = '".formatFechaDMA($fapertura)."',
					CodCuenta = '".$codcuenta."',
					Agencia = '".($agencia)."',
					Distrito = '".($distrito)."',
					Atencion = '".($atencion)."',
					Cargo = '".($cargo)."',
					FlagConciliacionBancaria = '".$flagconciliacionb."',
					FlagConciliacionCP = '".$flagconciliacionr."',
					FlagDebitoBancario = '".$flagdebito."',
					Estado = '".$estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE NroCuenta = '".$nrocuenta."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
		
		// inserto los tios de pagos disponibles
		$sql = "DELETE FROM ap_ctabancariatipopago WHERE NroCuenta = '".$nrocuenta."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
		if ($detalles != "") {
			$detalle = split(";", $detalles);
			foreach ($detalle as $tpago) {
				$sql = "INSERT INTO ap_ctabancariatipopago (
								NroCuenta,
								CodTipoPago,
								UltimoUsuario,
								UltimaFecha
							) VALUES (
								'".$nrocuenta."',
								'".$tpago."',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW())";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
	}
	
	//	ELIMINAR
	elseif ($accion == "ELIMINAR") {
		$sql = "DELETE FROM ap_ctabancariatipopago WHERE NroCuenta = '".$registro."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
		
		$sql = "DELETE FROM ap_ctabancaria WHERE NroCuenta = '".$registro."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
	}
}

//	CUENTAS BANCARIAS DEFAULT
elseif ($modulo == "CUENTA-BANCARIA-DEFAULT") {
	connect();
	//	INSERTAR
	if ($accion == "INSERTAR") {
		//	consulto si existe...
		$sql = "SELECT *
				FROM ap_ctabancariadefault
				WHERE
					CodOrganismo = '".$organismo."' AND
					CodTipoPago = '".$tpago."' AND
					NroCuenta = '".$nrocuenta."'";
		$query_consulta = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_consulta) != 0) die("¡ERROR: Registro ya ingresado!");
		
		//	inserto
		$sql = "INSERT INTO ap_ctabancariadefault (
							CodOrganismo,
							CodTipoPago,
							NroCuenta,
							UltimoUsuario,
							UltimaFecha
						) VALUES (
							'".$organismo."',
							'".$tpago."',
							'".$nrocuenta."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW())";
		$query_insert = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	INSERTAR
	elseif ($accion == "ACTUALIZAR") {
		//	consulto si existe...
		$sql = "SELECT *
				FROM ap_ctabancariadefault
				WHERE
					CodOrganismo = '".$organismo."' AND
					CodTipoPago = '".$tpago."' AND
					NroCuenta = '".$nrocuenta."'";
		$query_consulta = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_consulta) != 0) die("¡ERROR: Registro ya ingresado!");
		
		list($forganismo, $fnrocuenta, $ftpago)=SPLIT('[ ]', $registro);
		//	actualizo
		$sql = "UPDATE ap_ctabancariadefault
				SET
					CodOrganismo = '".$organismo."',
					CodTipoPago = '".$tpago."',
					NroCuenta = '".$nrocuenta."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$forganismo."' AND
					CodTipoPago = '".$ftpago."' AND
					NroCuenta = '".$fnrocuenta."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	ELIMINAR
	elseif ($accion == "ELIMINAR") {
		list($forganismo, $fnrocuenta, $ftpago)=SPLIT('[ ]', $registro);
		$sql = "DELETE FROM ap_ctabancariadefault 
				WHERE
					CodOrganismo = '".$forganismo."' AND
					CodTipoPago = '".$ftpago."' AND
					NroCuenta = '".$fnrocuenta."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
	}
}

//	TIPÒS DE TRANSACCIONE BANCARIA
elseif ($modulo == "TIPO-TRANSACCION-BANCARIA") {
	connect();
	//	INSERTAR
	if ($accion == "INSERTAR") {
		//	consulto si existe...
		$sql = "SELECT *
				FROM ap_bancotipotransaccion
				WHERE CodTipoTransaccion = '".$codigo."'";
		$query_consulta = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_consulta) != 0) die("¡ERROR: Tipo de Transacción ya ingresada!");
		
		//	inserto
		$sql = "INSERT INTO ap_bancotipotransaccion (
							CodTipoTransaccion,
							Descripcion,
							TipoTransaccion,
							FlagVoucher,
							CodVoucher,
							CodCuenta,
							FlagTransaccion,
							Estado,
							UltimoUsuario,
							UltimaFecha
						) VALUES (
							'".$codigo."',
							'".$descripcion."',
							'".$ttransaccion."',
							'".$flagvoucher."',
							'".$voucher."',
							'".$codcuenta."',
							'".$flagtransaccion."',
							'".$estado."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW())";
		$query_insert = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	INSERTAR
	elseif ($accion == "ACTUALIZAR") {
		//	actualizo
		$sql = "UPDATE ap_bancotipotransaccion
				SET
					Descripcion = '".$descripcion."',
					TipoTransaccion = '".$ttransaccion."',
					FlagVoucher = '".$flagvoucher."',
					CodVoucher = '".$voucher."',
					CodCuenta = '".$codcuenta."',
					FlagTransaccion = '".$flagtransaccion."',
					Estado = '".$estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodTipoTransaccion = '".$codigo."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	ELIMINAR
	elseif ($accion == "ELIMINAR") {
		$sql = "DELETE FROM ap_bancotipotransaccion WHERE CodTipoTransaccion = '".$registro."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
	}
}

//	ORDEN DE PAGO
elseif ($modulo == "TRANSACCION-BANCARIA") {
	connect();
	//	INSERTAR
	if ($accion == "INSERTAR") {
		//	detalles
		if ($detalles != "" && $FlagPresupuesto == "S") {
			$linea = split(";", $detalles); $secuencia = 0;
			foreach ($linea as $registro) {	$secuencia++;
				list($nrosecuencia, $codtransaccion, $tipotransaccion, $nrocuenta, $monto, $tipodocumento, $referenciabanco, $persona, $ccosto, $cod_partida) = SPLIT( '[|]', $registro);
				if ($ccosto == "") die("!ERROR: Debe seleccionar Centro de Costo¡");
				elseif ($cod_partida == "") die("!ERROR: Debe seleccionar Partida Presupuestaria¡");
				else {
					//	consulto nro de transaccion
					$sql = "SELECT CodigoReferenciaBanco
							FROM ap_bancotransaccion
							WHERE CodigoReferenciaBanco = '".$referenciabanco."'";
					$query_referencia = mysql_query($sql) or die($sql.mysql_error());
					if (mysql_num_rows($query_referencia) != 0) die("¡ERROR: Nro. de referencia existe!");
				}
			}
		}
		
		//	genero
		$nrotransaccion = getCodigo("ap_bancotransaccion", "NroTransaccion", 5);
		//	detalles
		if ($detalles != "") {
			$linea = split(";", $detalles); $secuencia = 0;
			foreach ($linea as $registro) {	$secuencia++;
				list($nrosecuencia, $codtransaccion, $tipotransaccion, $nrocuenta, $monto, $tipodocumento, $referenciabanco, $persona, $ccosto, $cod_partida) = SPLIT( '[|]', $registro);
				
				if ($tipotransaccion == "E") $monto = abs($monto) * (-1);
				elseif ($tipotransaccion == "I") $monto = abs($monto);
				
				//	consulto
				$sql = "SELECT FlagVoucher FROM ap_bancotipotransaccion WHERE CodTipoTransaccion = '".$codtransaccion."'";
				$query_flag = mysql_query($sql) or die($sql.mysql_error());
				if (mysql_num_rows($query_flag) != 0) $field_flag = mysql_fetch_array($query_flag);
							
				//	inserto
				$sql = "INSERT INTO ap_bancotransaccion (
									NroTransaccion,
									Secuencia,
									CodOrganismo,
									CodTipoTransaccion,
									TipoTransaccion,
									NroCuenta,
									CodTipoDocumento,
									CodProveedor,
									CodCentroCosto,
									PreparadoPor,
									FechaPreparacion,
									FechaTransaccion,
									PeriodoContable,
									Monto,
									CodigoReferenciaBanco,
									Comentarios,
									FlagGeneraVoucher,
									Estado,
									FlagPresupuesto,
									CodPartida,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$nrotransaccion."',
									'".$secuencia."',
									'".$organismo."',
									'".$codtransaccion."',
									'".$tipotransaccion."',
									'".$nrocuenta."',
									'".$tipodocumento."',
									'".$persona."',
									'".$ccosto."',
									'".$codpreparado."',
									'".formatFechaAMD($fpreparado)."',
									'".formatFechaAMD($ftransaccion)."',
									'".substr(formatFechaAMD($ftransaccion), 0, 7)."',
									'".$monto."',
									'".($referenciabanco)."',
									'".($comentarios)."',
									'".$field_flag['FlagVoucher']."',
									'PR',
									'".$FlagPresupuesto."',
									'".$cod_partida."',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
	}
	
	//	MODIFICAR
	elseif ($accion == "MODIFICAR") {
		//	detalles
		if ($detalles != "" && $FlagPresupuesto == "S") {
			$linea = split(";", $detalles); $secuencia = 0;
			foreach ($linea as $registro) {	$secuencia++;
				list($nrosecuencia, $codtransaccion, $tipotransaccion, $nrocuenta, $monto, $tipodocumento, $referenciabanco, $persona, $ccosto, $cod_partida) = SPLIT( '[|]', $registro);
				if ($ccosto == "") die("!ERROR: Debe seleccionar Centro de Costo¡");
				elseif ($cod_partida == "") die("!ERROR: Debe seleccionar Partida Presupuestaria¡");
			}
		}
		
		//	actualizo
		$linea = split(";", $detalles);
		foreach ($linea as $registro) {
			list($nrosecuencia, $codtransaccion, $tipotransaccion, $nrocuenta, $monto, $tipodocumento, $referenciabanco, $persona, $ccosto, $cod_partida) = SPLIT( '[|]', $registro);
			
			if ($tipotransaccion == "E") $monto = abs($monto) * (-1);
			elseif ($tipotransaccion == "I") $monto = abs($monto);
			
			//	actualizo transaccion
			$sql = "UPDATE ap_bancotransaccion
					SET
						FechaTransaccion = '".formatFechaAMD($ftransaccion)."',
						Comentarios = '".($comentarios)."',
						NroCuenta = '".$nrocuenta."',
						CodTipoDocumento = '".$tipodocumento."',
						CodProveedor = '".$persona."',
						CodCentroCosto = '".$ccosto."',
						CodigoReferenciaBanco = '".($referenciabanco)."',
						Monto = '".$monto."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						NroTransaccion = '".$nrotransaccion."' AND
						Secuencia = '".$nrosecuencia."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	
	//	ACTUALIZAR
	elseif ($accion == "ACTUALIZAR") {
		mysql_query("BEGIN");
		//	detalles
		if ($detalles != "" && $FlagPresupuesto == "S") {
			$linea = split(";", $detalles); $secuencia = 0;
			foreach ($linea as $registro) {	$secuencia++;
				list($nrosecuencia, $codtransaccion, $tipotransaccion, $nrocuenta, $monto, $tipodocumento, $referenciabanco, $persona, $ccosto, $cod_partida) = SPLIT( '[|]', $registro);
				if ($ccosto == "") die("!ERROR: Debe seleccionar Centro de Costo¡");
				elseif ($cod_partida == "") die("!ERROR: Debe seleccionar Partida Presupuestaria¡");
			}
		}
		
		//	actualizo
		$linea = split(";", $detalles);
		foreach ($linea as $registro) {
			list($nrosecuencia, $codtransaccion, $tipotransaccion, $nrocuenta, $monto, $tipodocumento, $referenciabanco, $persona, $ccosto, $cod_partida) = SPLIT( '[|]', $registro);
			
			if ($tipotransaccion == "E") $monto = abs($monto) * (-1);
			elseif ($tipotransaccion == "I") $monto = abs($monto);
			
			//	actualizo
			$sql = "UPDATE ap_bancotransaccion
					SET
						Estado = 'AP',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						NroTransaccion = '".$nrotransaccion."' AND
						Secuencia = '".$nrosecuencia."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
			
			//	actualizo transaccion
			$sql = "UPDATE ap_ctabancariabalance
					SET
						MontoTransaccion = (".floatval($monto)."),
						SaldoAnterior = SaldoActual,
						SaldoActual = (SaldoActual + (".floatval($monto).")),
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE NroCuenta = '".$nrocuenta."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
			
			if ($FlagPresupuesto == "S") {
				$m = $monto * (-1);
				//	inserto distribucion compromisos
				$sql = "INSERT INTO lg_distribucioncompromisos (
									Anio,
									CodOrganismo,
									CodProveedor,
									CodTipoDocumento,
									NroDocumento,
									Secuencia,
									Linea,
									Mes,
									CodCentroCosto,
									cod_partida,
									Monto,
									Periodo,
									Origen,
									Estado,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									SUBSTRING('$periodo', 1, 4),
									'".$organismo."',
									'".$persona."',
									'".$tipodocumento."',
									'".$referenciabanco."',
									'1',
									'1',
									SUBSTRING($periodo, 6, 2),
									'".$ccosto."',
									'".$cod_partida."',
									(".floatval($m)."),
									'".$periodo."',
									'TB',
									'CO',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
				
				//	inserto distribucion causados
				$sql = "INSERT INTO ap_distribucionobligacion (
									CodProveedor,
									CodTipoDocumento,
									NroDocumento,
									cod_partida,
									CodCentroCosto,
									Monto,
									Periodo,
									FlagCompromiso,
									Anio,
									CodOrganismo,
									Estado,
									Origen,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$persona."',
									'".$tipodocumento."',
									'".$referenciabanco."',
									'".$cod_partida."',
									'".$ccosto."',
									(".floatval($m)."),
									'".$periodo."',
									'N',
									SUBSTRING('$periodo', 1, 4),
									'".$organismo."',
									'CA',
									'TB',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
				
				//	inserto distribucion pagadas
				$sql = "INSERT INTO ap_ordenpagodistribucion (
									Anio,
									CodOrganismo,
									NroOrden,
									Linea,
									CodProveedor,
									CodTipoDocumento,
									NroDocumento,
									Monto,
									CodCentroCosto,
									cod_partida,
									FlagNoAfectoIGV,
									Periodo,
									Origen,
									Estado,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									SUBSTRING('$periodo', 1, 4),
									'".$organismo."',
									'".$referenciabanco."',
									'1',
									'".$persona."',
									'".$tipodocumento."',
									'".$referenciabanco."',
									(".floatval($m)."),
									'".$ccosto."',
									'".$cod_partida."',
									'N',
									'".$periodo."',
									'TB',
									'PA',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
				
				//	actualizo presupuesto
				$sql = "UPDATE pv_presupuestodet
						SET
							MontoCompromiso = MontoCompromiso + ".floatval($m).",
							MontoCausado = MontoCausado + ".floatval($m).",
							MontoPagado = MontoPagado + ".floatval($m)."
						WHERE
							Organismo = '".$organismo."' AND
							CodPresupuesto = (SELECT CodPresupuesto FROM pv_presupuesto WHERE EjercicioPpto = SUBSTRING('$periodo', 1, 4)) AND
							cod_partida = '".$cod_partida."'";
				$query_update = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
		mysql_query("COMMIT");
	}
	
	//	DESACTUALIZAR
	elseif ($accion == "DESACTUALIZAR") {
		mysql_query("BEGIN");
		//	detalles
		if ($detalles != "" && $FlagPresupuesto == "S") {
			$linea = split(";", $detalles); $secuencia = 0;
			foreach ($linea as $registro) {	$secuencia++;
				list($nrosecuencia, $codtransaccion, $tipotransaccion, $nrocuenta, $monto, $tipodocumento, $referenciabanco, $persona, $ccosto, $cod_partida) = SPLIT( '[|]', $registro);
				if ($ccosto == "") die("!ERROR: Debe seleccionar Centro de Costo¡");
				elseif ($cod_partida == "") die("!ERROR: Debe seleccionar Partida Presupuestaria¡");
			}
		}
		
		//	actualizo
		$linea = split(";", $detalles);
		foreach ($linea as $registro) {
			list($nrosecuencia, $codtransaccion, $tipotransaccion, $nrocuenta, $monto, $tipodocumento, $referenciabanco, $persona, $ccosto) = SPLIT( '[|]', $registro);
			//	actualizo
			$sql = "UPDATE ap_bancotransaccion
					SET
						Estado = 'PR',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						NroTransaccion = '".$nrotransaccion."' AND
						Secuencia = '".$nrosecuencia."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
			
			if ($tipotransaccion == "E") $monto = abs($monto) * (-1);
			elseif ($tipotransaccion == "I") $monto = abs($monto);
			
			//	actualizo
			$sql = "UPDATE ap_ctabancariabalance
					SET
						FechaTransaccion = '".formatFechaAMD($ftransaccion)."',
						SaldoActual = (SaldoActual + $monto),
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE NroCuenta = '".$nrocuenta."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
			
			if ($FlagPresupuesto == "S") {
				$m = $monto * (-1);
				//	elimino distribucion compromisos
				$sql = "DELETE FROM lg_distribucioncompromisos
						WHERE
							Anio = SUBSTRING('$periodo', 1, 4) AND
							CodOrganismo = '".$organismo."' AND
							CodProveedor = '".$persona."' AND
							CodTipoDocumento = '".$tipodocumento."' AND
							NroDocumento = '".$referenciabanco."'";
				$query_delete = mysql_query($sql) or die ($sql.mysql_error());
				
				//	elimino distribucion causados
				$sql = "DELETE FROM ap_distribucionobligacion
						WHERE
							CodProveedor = '".$persona."' AND
							CodTipoDocumento = '".$tipodocumento."' AND
							NroDocumento = '".$referenciabanco."' AND
							cod_partida = '".$cod_partida."' AND
							CodCentrocosto = '".$ccosto."'";
				$query_delete = mysql_query($sql) or die ($sql.mysql_error());
				
				//	elimino distribucion pagadas
				$sql = "DELETE FROM ap_ordenpagodistribucion
						WHERE
							Anio = SUBSTRING('$periodo', 1, 4) AND
							CodOrganismo = '".$organismo."' AND
							NroOrden = '".$referenciabanco."'";
				$query_delete = mysql_query($sql) or die ($sql.mysql_error());
				
				//	actualizo presupuesto
				$sql = "UPDATE pv_presupuestodet
						SET
							MontoCompromiso = MontoCompromiso + ".floatval($m).",
							MontoCausado = MontoCausado + ".floatval($m).",
							MontoPagado = MontoPagado + ".floatval($m)."
						WHERE
							Organismo = '".$organismo."' AND
							CodPresupuesto = (SELECT CodPresupuesto FROM pv_presupuesto WHERE EjercicioPpto = SUBSTRING('$periodo', 1, 4)) AND
							cod_partida = '".$cod_partida."'";
				$query_update = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
		mysql_query("COMMIT");
	}
	
	//	ELIMINAR
	elseif ($accion == "ELIMINAR") {}
}

//	GRUPO DE CONCEPTO DE GASTOS
elseif ($modulo == "GRUPO-CONCEPTO-GASTO") {
	connect();
	//	INSERTAR
	if ($accion == "INSERTAR") {
		//	consulto si el registro existe...
		$sql = "SELECT * FROM ap_conceptogastogrupo WHERE CodGastoGrupo = '".$codigo."' OR Descripcion = '".$descripcion."'";
		$query_consulta = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_consulta) != 0) die("¡ERROR: Registro ya existe!");
		
		//	inserto
		if (trim($codigo) == "") $codigo = getCodigo("ap_conceptogastogrupo", "CodGastoGrupo", 3);
		$sql = "INSERT INTO ap_conceptogastogrupo (
							CodGastoGrupo,
							Descripcion,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$codigo."',
							'".($descripcion)."',
							'".$estado."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW())";
		$query_insert = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	ACTUALIZAR
	elseif ($accion == "ACTUALIZAR") {
		//	consulto si el registro existe...
		$sql = "SELECT * FROM ap_conceptogastogrupo WHERE CodGastoGrupo <> '".$codigo."' AND Descripcion = '".$descripcion."'";
		$query_consulta = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_consulta) != 0) die("¡ERROR: Registro ya existe!");
		
		//	inserto
		$sql = "UPDATE ap_conceptogastogrupo
				SET
					Descripcion = '".($descripcion)."',
					Estado = '".$estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodGastoGrupo = '".$codigo."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	ELIMINAR
	elseif ($accion == "ELIMINAR") {
		$sql = "DELETE FROM ap_conceptogastogrupo WHERE CodGastoGrupo = '".$registro."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
	}
}

//	AUTORIZACION DE CAJA CHICA
elseif ($modulo == "AUTORIZACION-CAJA-CHICA") {
	connect();
	//	INSERTAR
	if ($accion == "INSERTAR") {
		//	consulto si el registro existe...
		$sql = "SELECT * FROM ap_cajachicaautorizacion WHERE CodEmpleado = '".$codempleado."' AND CodOrganismo = '".$codorganismo."'";
		$query_consulta = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_consulta) != 0) die("¡ERROR: Empleado ya existe!");
		
		//	inserto
		$sql = "INSERT INTO ap_cajachicaautorizacion (
							CodEmpleado,
							CodOrganismo,
							Monto,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$codempleado."',
							'".$codorganismo."',
							'".$monto."',
							'".$estado."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW())";
		$query_insert = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	ACTUALIZAR
	elseif ($accion == "ACTUALIZAR") {
		//	inserto
		$sql = "UPDATE ap_cajachicaautorizacion
				SET
					Monto = '".$monto."',
					Estado = '".$estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodEmpleado = '".$codempleado."' AND 
					CodOrganismo = '".$codorganismo."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	ELIMINAR
	elseif ($accion == "ELIMINAR") {
		list($codorganismo, $codempleado) = split("[.]", $registro);
		$sql = "DELETE FROM ap_cajachicaautorizacion WHERE CodEmpleado = '".$codempleado."' AND CodOrganismo = '".$codorganismo."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
	}
}

//	CLASIFICACION DE GASTOS
elseif ($modulo == "CLASIFICACION-GASTO") {
	connect();
	//	INSERTAR
	if ($accion == "INSERTAR") {
		//	consulto si el registro existe...
		$sql = "SELECT * FROM ap_clasificaciongastos WHERE CodClasificacion = '".$codigo."' OR Descripcion = '".($descripcion)."'";
		$query_consulta = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_consulta) != 0) die("¡ERROR: Registro ya existe!");
		
		//	inserto
		$sql = "INSERT INTO ap_clasificaciongastos (
							CodClasificacion,
							Descripcion,
							Aplicacion,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$codigo."',
							'".($descripcion)."',
							'".$aplicacion."',
							'".$estado."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW())";
		$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		
		//	concepto de gastos
		$sql = "DELETE FROM ap_conceptoclasificaciongastos WHERE CodClasificacion = '".$codigo."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
		if ($detalles != "") {
			$detalle = split(";", $detalles);
			foreach ($detalle as $concepto) {
				$sql = "INSERT INTO ap_conceptoclasificaciongastos (
									CodConceptoGasto,
									CodClasificacion,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$concepto."',
									'".$codigo."',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
	}
	
	//	ACTUALIZAR
	elseif ($accion == "ACTUALIZAR") {
		//	inserto
		$sql = "UPDATE ap_clasificaciongastos
				SET
					Descripcion = '".($descripcion)."',
					Aplicacion = '".$aplicacion."',
					Estado = '".$estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodClasificacion = '".$codigo."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
		
		//	concepto de gastos
		$sql = "DELETE FROM ap_conceptoclasificaciongastos WHERE CodClasificacion = '".$codigo."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
		if ($detalles != "") {
			$detalle = split(";", $detalles);
			foreach ($detalle as $concepto) {
				$sql = "INSERT INTO ap_conceptoclasificaciongastos (
									CodConceptoGasto,
									CodClasificacion,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$concepto."',
									'".$codigo."',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
	}
	
	//	ELIMINAR
	elseif ($accion == "ELIMINAR") {
		//	elimino conceptos asociados
		$sql = "DELETE FROM ap_conceptoclasificaciongastos WHERE CodClasificacion = '".$registro."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
		//	elimino el registro
		$sql = "DELETE FROM ap_clasificaciongastos WHERE CodClasificacion = '".$registro."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
	}
}

//	CONCEPTO DE GASTOS
elseif ($modulo == "CONCEPTO-GASTO") {
	connect();
	//	INSERTAR
	if ($accion == "INSERTAR") {
		//	consulto si el registro existe...
		$sql = "SELECT * FROM ap_conceptogastos WHERE CodConceptoGasto = '".$codigo."' OR Descripcion = '".($descripcion)."'";
		$query_consulta = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_consulta) != 0) die("¡ERROR: Registro ya existe!");
		
		//	inserto
		if (trim($codigo) == "") $codigo = getCodigo("ap_conceptogastos", "CodConceptoGasto", 4);
		$sql = "INSERT INTO ap_conceptogastos (
							CodConceptoGasto,
							Descripcion,
							CodGastoGrupo,
							CodPartida,
							CodCuenta,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$codigo."',
							'".($descripcion)."',
							'".$grupo."',
							'".$codpartida."',
							'".$codcuenta."',
							'".$estado."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW())";
		$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		
		//	clasificaciones
		$sql = "DELETE FROM ap_conceptoclasificaciongastos WHERE CodConceptoGasto = '".$codigo."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
		if ($detalles != "") {
			$detalle = split(";", $detalles);
			foreach ($detalle as $clasificacion) {
				$sql = "INSERT INTO ap_conceptoclasificaciongastos (
									CodConceptoGasto,
									CodClasificacion,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$codigo."',
									'".$clasificacion."',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
	}
	
	//	ACTUALIZAR
	elseif ($accion == "ACTUALIZAR") {
		//	consulto si el registro existe...
		$sql = "SELECT * FROM ap_conceptogastos WHERE CodConceptoGasto <> '".$codigo."' AND Descripcion = '".($descripcion)."'";
		$query_consulta = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_consulta) != 0) die("¡ERROR: Registro ya existe!");
		
		//	actualizo
		$sql = "UPDATE ap_conceptogastos
				SET
					Descripcion = '".($descripcion)."',
					CodGastoGrupo = '".$grupo."',
					CodPartida = '".$codpartida."',
					CodCuenta = '".$codcuenta."',
					Estado = '".$estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodConceptoGasto = '".$codigo."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
		
		//	clasificaciones
		$sql = "DELETE FROM ap_conceptoclasificaciongastos WHERE CodConceptoGasto = '".$codigo."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
		if ($detalles != "") {
			$detalle = split(";", $detalles);
			foreach ($detalle as $clasificacion) {
				$sql = "INSERT INTO ap_conceptoclasificaciongastos (
									CodConceptoGasto,
									CodClasificacion,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$codigo."',
									'".$clasificacion."',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
	}
	
	//	ELIMINAR
	elseif ($accion == "ELIMINAR") {
		//	elimino clasificaciones asociadas
		$sql = "DELETE FROM ap_conceptoclasificaciongastos WHERE CodConceptoGasto = '".$registro."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
		//	elimino el registro
		$sql = "DELETE FROM ap_conceptogastos WHERE CodConceptoGasto = '".$registro."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
	}
}

//	CAJA CHICA
elseif ($modulo == "CAJA-CHICA") {
	connect();
	$_REPCC = getParametro("REPCC");
	
	//	INSERTAR
	if ($accion == "AGREGAR") {
		//	obtengo suma de pagado
		if ($detalles != "") {
			$secuencia = 0;
			$detalle = split(";.;", $detalles);
			foreach ($detalle as $registro) {	$secuencia++;
				list($_fdocumento, $_concepto, $_descripcion, $_pagado, $_tipoimpuesto, $_tiposervicio, $_afecto, $_noafecto, $_impuesto, $_retencion, $_rif, $_tipodocumento, $_nrodocumento, $_nrofactura, $_codpersona, $_nompersona, $_distribucion) = split("[*]", $registro);
				if ($tipoimpuesto == "N") $flagnoafectoigv = "S"; else $flagnoafectoigv = "N";
				$total_pagado += $_pagado;
			}
		}
		$monto_reposicion_max = $monto_autorizado * $_REPCC / 100;
		//if ($total_pagado >= $monto_reposicion_max) die("El monto a reembolsar excede el limite máximo permitido ($_REPCC% del Monto Autorizado)");
		if ($total_pagado > $monto_autorizado) die("El Monto a Pagar excede el Monto Autorizado");
		
		//	inserto
		$codigo = getCodigo_3("ap_cajachica", "NroCajaChica", "Periodo", "FlagCajaChica", $periodo, $flagcajachica, 4);
		$sql = "INSERT INTO ap_cajachica (
							FlagCajaChica,
							Periodo,
							NroCajaChica,
							CodOrganismo,
							CodDependencia,
							CodResponsable,
							CodClasificacion,
							CodCentroCosto,
							CodBeneficiario,
							CodPersonaPagar,
							NomPersonaPagar,
							CodTipoPago,
							PreparadoPor,
							FechaPreparacion,
							Descripcion,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$flagcajachica."',
							'".$periodo."',
							'".$codigo."',
							'".$codorganismo."',
							'".$coddependencia."',
							'".$codbeneficiario."',
							'".$codclasificacion."',
							'".$codccosto."',
							'".$codbeneficiario."',
							'".$codpagara."',
							'".($nompagara)."',
							'".$codtipopago."',
							'".$codpreparadopor."',
							'".formatFechaAMD($fpreparadopor)."',
							'".($descripcion)."',
							'".$estado."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW())";
		$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		
		//	detalles
		if ($detalles != "") {
			$secuencia = 0;
			$detalle = split(";.;", $detalles);
			foreach ($detalle as $registro) {	$secuencia++;
				list($_fdocumento, $_concepto, $_descripcion, $_pagado, $_tipoimpuesto, $_tiposervicio, $_afecto, $_noafecto, $_impuesto, $_retencion, $_rif, $_tipodocumento, $_nrodocumento, $_nrofactura, $_codpersona, $_nompersona, $_distribucion) = split("[*]", $registro);
				$codempleado = getCodEmpleadoFromCodPersona($codbeneficiario);
				if ($tipoimpuesto == "N") $flagnoafectoigv = "S"; else $flagnoafectoigv = "N";
				
				//	inserto
				$sql = "INSERT INTO ap_cajachicadetalle (
									FlagCajaChica,
									Periodo,
									NroCajaChica,
									Secuencia,
									CodConceptoGasto,
									Fecha,
									Descripcion,
									CodRegimenFiscal,
									DocFiscal,
									CodProveedor,
									NomProveedor,
									CodTipoDocumento,
									NroDocumento,
									NroRecibo,
									FechaDocumento,
									MontoAfecto,
									MontoNoAfecto,
									MontoImpuesto,
									MontoRetencion,
									MontoPagado,
									CodTipoServicio,
									Comentarios,
									FlagNoAfectoIGV,
									CodEmpleado,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$flagcajachica."',
									'".$periodo."',
									'".$codigo."',
									'".$secuencia."',
									'".$_concepto."',
									NOW(),
									'".($_descripcion)."',
									'".$_tipoimpuesto."',
									'".$_rif."',
									'".$_codpersona."',
									'".($_nompersona)."',
									'".$_tipodocumento."',
									'".$_nrodocumento."',
									'".$_nrofactura."',
									'".formatFechaAMD($_fdocumento)."',
									'".$_afecto."',
									'".$_noafecto."',
									'".$_impuesto."',
									'".$_retencion."',
									'".$_pagado."',
									'".$_tiposervicio."',
									'".($_descripcion)."',
									'".$flagnoafectoigv."',
									'".$codempleado."',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";

				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
				
				if (trim($_distribucion) == "") {	
					//	consulto
					$sql = "SELECT CodPartida, CodCuenta FROM ap_conceptogastos WHERE CodConceptoGasto = '".$_concepto."'";
					$query_concepto = mysql_query($sql) or die($sql.mysql_error());
					if (mysql_num_rows($query_concepto) != 0) $field_concepto = mysql_fetch_array($query_concepto);
					//	-------------------------
					$_bruto = $_afecto + $_noafecto;
					$_distribucion = $_bruto."|$_concepto|$field_concepto[CodPartida]|$field_concepto[CodCuenta]|$codccosto";
					$set = false;
				} else $set = true;
				//	distribucion
				$linea = 0;
				$detalle_distribucion = split(";", $_distribucion);
				foreach ($detalle_distribucion as $registro_distribucion) {	$linea++;
					list($_dismonto, $_disconcepto, $_dispartida, $_discuenta, $_disccosto) = split("[|]", $registro_distribucion);
					if ($set) $_dismonto = setNumero($_dismonto);
					
					//	inserto
					$sql = "INSERT INTO ap_cajachicadistribucion (
										FlagCajaChica,
										Periodo,
										NroCajaChica,
										Secuencia,
										Linea,
										CodConceptoGasto,
										Monto,
										CodOrganismo,
										CodCentroCosto,
										CodPartida,
										CodCuenta,
										UltimoUsuario,
										UltimaFecha
							) VALUES (
										'".$flagcajachica."',
										'".$periodo."',
										'".$codigo."',
										'".$secuencia."',
										'".$linea."',
										'".$_disconcepto."',
										'".$_dismonto."',
										'".$codorganismo."',
										'".$_disccosto."',
										'".$_dispartida."',
										'".$_discuenta."',
										'".$_SESSION["USUARIO_ACTUAL"]."',
										NOW()
							)";
					$query_insert = mysql_query($sql) or die ($sql.mysql_error());
				}				
				//$total_pagado += $_pagado;
			}
		}
		
		//	actualizo montos
		$sql = "UPDATE ap_cajachica
				SET
					MontoTotal = '".$total_pagado."',
					MontoNeto = '".$total_pagado."'
				WHERE
					FlagCajaChica = '".$flagcajachica."' AND
					Periodo = '".$periodo."' AND
					NroCajaChica = '".$codigo."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	ACTUALIZAR
	elseif ($accion == "ACTUALIZAR") {
		//	obtengo suma de pagado
		if ($detalles != "") {
			$secuencia = 0;
			$detalle = split(";.;", $detalles);
			foreach ($detalle as $registro) {	$secuencia++;
				list($_fdocumento, $_concepto, $_descripcion, $_pagado, $_tipoimpuesto, $_tiposervicio, $_afecto, $_noafecto, $_impuesto, $_retencion, $_rif, $_tipodocumento, $_nrodocumento, $_nrofactura, $_codpersona, $_nompersona, $_distribucion) = split("[*]", $registro);
				if ($tipoimpuesto == "N") $flagnoafectoigv = "S"; else $flagnoafectoigv = "N";
				$total_pagado += $_pagado;
			}
		}
		$monto_reposicion_max = $monto_autorizado * $_REPCC / 100;
		//if ($total_pagado >= $monto_reposicion_max) die("El monto a reembolsar excede el limite máximo permitido ($_REPCC% del Monto Autorizado)");
		if ($total_pagado > $monto_autorizado) die("El Monto a Pagar excede el Monto Autorizado");
		
		//	elimino distribucion		
		$sql = "DELETE FROM ap_cajachicadistribucion
				WHERE
					FlagCajaChica = '".$flagcajachica."' AND
					Periodo = '".$periodo."' AND
					NroCajaChica = '".$codigo."'";
//echo $sql;					
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
		
		//	elimino detalles
		$sql = "DELETE FROM ap_cajachicadetalle
				WHERE
					FlagCajaChica = '".$flagcajachica."' AND
					Periodo = '".$periodo."' AND
					NroCajaChica = '".$codigo."'";
					
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
		
		//	actualizo		
		$sql = "UPDATE ap_cajachica
				SET
					CodResponsable = '".$codbeneficiario."',
					CodClasificacion = '".$codclasificacion."',
					CodCentroCosto = '".$codccosto."',
					CodBeneficiario = '".$codbeneficiario."',
					CodPersonaPagar = '".$codpagara."',
					NomPersonaPagar = '".($nompagara)."',
					CodTipoPago = '".$codtipopago."',
					Descripcion = '".($descripcion)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					FlagCajaChica = '".$flagcajachica."' AND
					Periodo = '".$periodo."' AND
					NroCajaChica = '".$codigo."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
		
		//	detalles
		if ($detalles != "") {
			$secuencia = 0;
			$detalle = split(";.;", $detalles);
			foreach ($detalle as $registro) {	$secuencia++;
				list($_fdocumento, $_concepto, $_descripcion, $_pagado, $_tipoimpuesto, $_tiposervicio, $_afecto, $_noafecto, $_impuesto, $_retencion, $_rif, $_tipodocumento, $_nrodocumento, $_nrofactura, $_codpersona, $_nompersona, $_distribucion) = split("[*]", $registro);
				$codempleado = getCodEmpleadoFromCodPersona($codbeneficiario);
				if ($tipoimpuesto == "N") $flagnoafectoigv = "N"; else $flagnoafectoigv = "S";
				
				//	inserto
				$sql = "INSERT INTO ap_cajachicadetalle (
									FlagCajaChica,
									Periodo,
									NroCajaChica,
									Secuencia,
									CodConceptoGasto,
									Fecha,
									Descripcion,
									CodRegimenFiscal,
									DocFiscal,
									CodProveedor,
									NomProveedor,
									CodTipoDocumento,
									NroDocumento,
									NroRecibo,
									FechaDocumento,
									MontoAfecto,
									MontoNoAfecto,
									MontoImpuesto,
									MontoRetencion,
									MontoPagado,
									CodTipoServicio,
									Comentarios,
									FlagNoAfectoIGV,
									CodEmpleado,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$flagcajachica."',
									'".$periodo."',
									'".$codigo."',
									'".$secuencia."',
									'".$_concepto."',
									NOW(),
									'".($_descripcion)."',
									'".$_tipoimpuesto."',
									'".$_rif."',
									'".$_codpersona."',
									'".($_nompersona)."',
									'".$_tipodocumento."',
									'".$_nrodocumento."',
									'".$_nrofactura."',
									'".formatFechaAMD($_fdocumento)."',
									'".$_afecto."',
									'".$_noafecto."',
									'".$_impuesto."',
									'".$_retencion."',
									'".$_pagado."',
									'".$_tiposervicio."',
									'".($_descripcion)."',
									'".$flagnoafectoigv."',
									'".$codempleado."',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
				
				if (trim($_distribucion) == "") {	
					//	consulto
					$sql = "SELECT CodPartida, CodCuenta FROM ap_conceptogastos WHERE CodConceptoGasto = '".$_concepto."'";
					$query_concepto = mysql_query($sql) or die($sql.mysql_error());
					if (mysql_num_rows($query_concepto) != 0) $field_concepto = mysql_fetch_array($query_concepto);
					//	-------------------------
					$_bruto = $_afecto + $_noafecto;
					$_distribucion = $_bruto."|$_concepto|$field_concepto[CodPartida]|$field_concepto[CodCuenta]|$codccosto";
					$set = false;
				} else $set = true;
				//	distribucion
				$linea = 0;
				$detalle_distribucion = split(";", $_distribucion);
				foreach ($detalle_distribucion as $registro_distribucion) {	$linea++;
					list($_dismonto, $_disconcepto, $_dispartida, $_discuenta, $_disccosto) = split("[|]", $registro_distribucion);
					if ($set) $_dismonto = setNumero($_dismonto);
					//	inserto
					$sql = "INSERT INTO ap_cajachicadistribucion (
										FlagCajaChica,
										Periodo,
										NroCajaChica,
										Secuencia,
										Linea,
										CodConceptoGasto,
										Monto,
										CodOrganismo,
										CodCentroCosto,
										CodPartida,
										CodCuenta,
										UltimoUsuario,
										UltimaFecha
							) VALUES (
										'".$flagcajachica."',
										'".$periodo."',
										'".$codigo."',
										'".$secuencia."',
										'".$linea."',
										'".$_disconcepto."',
										'".$_dismonto."',
										'".$codorganismo."',
										'".$_disccosto."',
										'".$_dispartida."',
										'".$_discuenta."',
										'".$_SESSION["USUARIO_ACTUAL"]."',
										NOW()
							)";
//echo $sql;							
					$query_insert = mysql_query($sql) or die ($sql.mysql_error());
				}				
				//$total_pagado += $_pagado;
			}
		}
		
		//	actualizo montos
		$sql = "UPDATE ap_cajachica
				SET
					MontoTotal = '".$total_pagado."',
					MontoNeto = '".$total_pagado."'
				WHERE
					FlagCajaChica = '".$flagcajachica."' AND
					Periodo = '".$periodo."' AND
					NroCajaChica = '".$codigo."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	APROBAR
	elseif ($accion == "APROBAR") {
		//	valido monto autorizado
		if ($monto_reembolsar < ($monto_autorizado * $_REPCC / 100)) die("ERROR: No puede aprobar porque el monto a reembolsar es menor al $_REPCC% del monto autorizado");
		
		//	consulto disponibilidad
		$sql = "SELECT
					ccd.CodPartida,
					SUM(ccd.Monto) AS Monto,
					(pd.MontoAjustado - pd.MontoCompromiso) AS MontoDisponible
				FROM
					ap_cajachicadistribucion ccd
					INNER JOIN pv_presupuesto p ON (ccd.CodOrganismo = p.Organismo AND
													p.EjercicioPpto = '2012')
					LEFT JOIN pv_presupuestodet pd ON (p.Organismo = pd.Organismo AND
													   p.CodPresupuesto = pd.CodPresupuesto AND
													   ccd.CodPartida = pd.cod_partida)
				WHERE
					ccd.FlagCajaChica = '".$flagcajachica."' AND
					ccd.Periodo = '".$periodo."' AND
					ccd.NroCajaChica = '".$codigo."'
				GROUP BY CodPartida
				ORDER BY CodPartida";
		$query = mysql_query($sql) or die($sql.mysql_error());
		while ($field = mysql_fetch_array($query)) {
			if ($field['Monto'] > $field['MontoDisponible']) {
				die("ERROR: Se encontró la partida $field[CodPartida] sin disponibilidad presupuestaria");
			}
		}
		
		//	detalles
		if ($detalles != "") {
			$secuencia = 0;
			$detalle = split(";.;", $detalles);
			foreach ($detalle as $registro) {	$secuencia++;
				list($_fdocumento, $_concepto, $_descripcion, $_pagado, $_tipoimpuesto, $_tiposervicio, $_afecto, $_noafecto, $_impuesto, $_retencion, $_rif, $_tipodocumento, $_nrodocumento, $_nrofactura, $_codpersona, $_nompersona, $_distribucion) = split("[*]", $registro);
				
				$monto_afecto += $_afecto;
				$monto_noafecto += $_noafecto;
				$monto_impuesto += $_impuesto;
				$monto_retencion += $_retencion;
				$monto_obligacion += $_pagado;
			}
		}
		
		//	actualizo
		$sql = "UPDATE ap_cajachica
				SET
					AprobadoPor = '".$codaprobadopor."',
					FechaAprobacion = '".formatFechaAMD($faprobadopor)."',
					Estado = 'AP',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					FlagCajaChica = '".$flagcajachica."' AND
					Periodo = '".$periodo."' AND
					NroCajaChica = '".$codigo."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
		
		//	inserto la obligacion
		$TSERVCC = getParametro("TSERVCC");
		$nroregistro = getCodigo_2("ap_obligaciones", "NroRegistro", "CodOrganismo", $codorganismo, 6);
		$nrodocumento = "00$codigo";
		$sql = "INSERT INTO ap_obligaciones (
							CodProveedor,
							CodTipoDocumento,
							NroDocumento,
							NroControl,
							CodOrganismo,
							CodTipoPago,
							NroCuenta,
							CodTipoServicio,
							FechaRegistro,
							FechaVencimiento,
							FechaRecepcion,
							FechaDocumento,
							FechaProgramada,
							NroRegistro,
							MontoObligacion,
							MontoImpuesto,
							MontoImpuestoOtros,
							MontoNoAfecto,
							MontoAfecto,
							IngresadoPor,
							FechaPreparacion,
							RevisadoPor,
							FechaRevision,
							Comentarios,
							ComentariosAdicional,
							CodCentroCosto,
							CodProveedorPagar,
							CodResponsable,
							FlagCajaChica,
							Estado,
							UltimoUsuario,
							UltimaFecha,
							Periodo
				)
						SELECT
							cc.CodBeneficiario,
							cc.CodClasificacion,
							'".$nrodocumento."' AS NroDocumento,
							'".$nrodocumento."' AS NroControl,
							cc.CodOrganismo,
							cc.CodTipoPago,
							cbd.NroCuenta,
							'".$TSERVCC."' AS CodTipoServicio,
							cc.FechaPreparacion,
							cc.FechaAprobacion,
							cc.FechaPreparacion,
							cc.FechaPreparacion,
							cc.FechaAprobacion,
							'".$nroregistro."' AS NroRegistro,
							'".($monto_obligacion)."' AS MontoObligacion,
							'".($monto_impuesto)."' AS MontoImpuesto,
							'".($monto_retencion)."' AS MontoImpuestoOtros,
							'".($monto_noafecto)."' AS MontoNoAfecto,
							'".($monto_afecto)."' AS MontoAfecto,
							cc.PreparadoPor,
							cc.FechaPreparacion,
							cc.PreparadoPor,
							cc.FechaPreparacion,
							cc.Descripcion,
							cc.Descripcion,
							cc.CodCentroCosto,
							cc.CodPersonaPagar,
							cc.CodResponsable,
							'S' AS FlagCajaChica,
							'PR' AS Estado,
							'".$_SESSION["USUARIO_ACTUAL"]."' AS UltimoUsuario,
							NOW() AS UltimaFecha,
							NOW() AS Periodo
						FROM
							ap_cajachica cc
							LEFT JOIN ap_ctabancariadefault cbd ON (cc.CodOrganismo = cbd.CodOrganismo AND cc.CodTipoPago = cbd.CodTipoPago)
						WHERE
							cc.FlagCajaChica = '".$flagcajachica."' AND
							cc.Periodo = '".$periodo."' AND
							cc.NroCajaChica = '".$codigo."'";
//echo $sql;

		$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		
		//	inserto la distribucion x cuenta
		$sql = "SELECT
					ccd.*,
					cg.Descripcion,
					cc.MontoImpuesto
				FROM
					ap_cajachicadistribucion ccd
					INNER JOIN ap_conceptogastos cg ON (ccd.CodConceptoGasto = cg.CodConceptoGasto)
					INNER JOIN ap_cajachicadetalle cc ON (cc.FlagCajaChica = ccd.FlagCajaChica AND
													      cc.Periodo = ccd.Periodo AND
														  cc.NroCajaChica = ccd.NroCajaChica AND
														  cc.Secuencia = ccd.Secuencia)
				WHERE
					ccd.FlagCajaChica = '".$flagcajachica."' AND
					ccd.Periodo = '".$periodo."' AND
					ccd.NroCajaChica = '".$codigo."'
				ORDER BY Secuencia, Linea";
		$query_distribucion = mysql_query($sql) or die($sql.mysql_error());
		while($field_distribucion = mysql_fetch_array($query_distribucion)) {
			if ($field_distribucion['MontoImpuesto'] > 0) $FlagNoAfectoIGV = "N";
			else $FlagNoAfectoIGV = "S";
			$sql = "INSERT INTO ap_obligacionescuenta (
								CodProveedor,
								CodTipoDocumento,
								NroDocumento,
								Secuencia,
								Linea,
								Descripcion,
								Monto,
								CodCentroCosto,
								CodCuenta,
								cod_partida,
								FlagNoAfectoIGV,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$codbeneficiario."',
								'".$codclasificacion."',
								'".$nrodocumento."',
								'".$field_distribucion['Secuencia']."',
								'".$field_distribucion['Linea']."',
								'".$field_distribucion['Descripcion']."',
								'".$field_distribucion['Monto']."',
								'".$field_distribucion['CodCentroCosto']."',
								'".$field_distribucion['CodCuenta']."',
								'".$field_distribucion['CodPartida']."',
								'".$FlagNoAfectoIGV."',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		}
		
		//	inserto los impuestos
		$sql = "SELECT
					i.CodImpuesto,
					i.FactorPorcentaje,
					i.Signo,
					i.CodRegimenFiscal,
					i.FlagImponible,
					ccd.MontoAfecto,
					ccd.MontoImpuesto
				FROM
					mastimpuestos i
					INNER JOIN masttiposervicioimpuesto tsi ON (i.CodImpuesto = tsi.CodImpuesto)
					INNER JOIN ap_cajachicadetalle ccd ON (tsi.CodTipoServicio = ccd.CodTipoServicio)
				WHERE
					i.CodRegimenFiscal = 'R' AND
					ccd.FlagCajaChica = '".$flagcajachica."' AND
					ccd.Periodo = '".$periodo."' AND
					ccd.NroCajaChica = '".$codigo."'";
		$query_impuestos = mysql_query($sql) or die($sql.mysql_error());	$linea = 0;
		while ($field_impuestos = mysql_fetch_array($query_impuestos)) {	$linea++;
			if ($field_impuestos['FlagImponible'] == "N") $monto_afecto = $field_impuestos['MontoAfecto'];
			elseif ($field_impuestos['FlagImponible'] == "I") $monto_afecto = $field_impuestos['MontoImpuesto'];
			$retencion = $monto_afecto * $field_impuestos['FactorPorcentaje'] / 100;
			if ($field_impuestos['Signo'] == "N") $retencion *= (-1);
			#			
			$sql = "INSERT INTO ap_obligacionesimpuesto (
								CodProveedor,
								CodTipoDocumento,
								NroDocumento,
								Linea,
								CodImpuesto,
								FactorPorcentaje,
								MontoImpuesto,
								MontoAfecto,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$codbeneficiario."',
								'".$codclasificacion."',
								'".$nrodocumento."',
								'".$linea."',
								'".$field_impuestos['CodImpuesto']."',
								'".$field_impuestos['FactorPorcentaje']."',
								'".$retencion."',
								'".$monto_afecto."',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		}
		
		//	inserto la distribucion
		$sql = "SELECT
					cod_partida,
					CodCuenta,
					CodCentroCosto,
					SUM(Monto) AS Monto
				FROM ap_obligacionescuenta 
				WHERE
					CodProveedor = '".$codbeneficiario."' AND
					CodTipoDocumento = '".$codclasificacion."' AND
					NroDocumento = '".$nrodocumento."'
				GROUP BY cod_partida, CodCuenta, CodCentroCosto
				ORDER BY cod_partida";
		$query_distribucion = mysql_query($sql) or die($sql.mysql_error());
		while ($field_distribucion = mysql_fetch_array($query_distribucion)) {
			$id = date("Y").".$codorganismo.$codbeneficiario.$codclasificacion.$nrodocumento.$field_distribucion[cod_partida].$field_distribucion[CodCentroCosto]";
			if ($grupo != $id) {
				$grupo = $id;
				++$Secuencia;
				$Linea = 0;
			}
			++$Linea;
			//	comprometido
			$sql = "INSERT INTO lg_distribucioncompromisos (
								Anio,
								CodOrganismo,
								CodProveedor,
								CodTipoDocumento,
								NroDocumento,
								Secuencia,
								Linea,
								Mes,
								CodCentroCosto,
								cod_partida,
								Monto,
								Periodo,
								Origen,
								Estado,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								NOW(),
								'".$codorganismo."',
								'".$codbeneficiario."',
								'".$codclasificacion."',
								'".$nrodocumento."',
								'".$Secuencia."',
								'".$Linea."',
								SUBSTRING(NOW(), 6, 2),
								'".$field_distribucion['CodCentroCosto']."',
								'".$field_distribucion['cod_partida']."',
								'".$field_distribucion['Monto']."',
								NOW(),
								'OB',
								'PE',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			
			//	causado
			$sql = "INSERT INTO ap_distribucionobligacion (
								CodProveedor,
								CodTipoDocumento,
								NroDocumento,
								CodCuenta,
								cod_partida,
								CodCentroCosto,
								Monto,
								Periodo,
								Estado,
								Anio,
								FlagCompromiso,
								Origen,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$codbeneficiario."',
								'".$codclasificacion."',
								'".$nrodocumento."',
								'".$field_distribucion['CodCuenta']."',
								'".$field_distribucion['cod_partida']."',
								'".$field_distribucion['CodCentroCosto']."',
								'".$field_distribucion['Monto']."',
								NOW(),
								'PE',
								NOW(),
								'S',
								'OB',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		}
		$IVACTADEF = getParametro("IVACTADEF");
		$IVADEFAULT = getParametro("IVADEFAULT");
		//	compromiso
		$sql = "INSERT INTO lg_distribucioncompromisos (
							Anio,
							CodOrganismo,
							CodProveedor,
							CodTipoDocumento,
							NroDocumento,
							Secuencia,
							Linea,
							Mes,
							CodCentroCosto,
							cod_partida,
							Monto,
							Periodo,
							Origen,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							NOW(),
							'".$codorganismo."',
							'".$codbeneficiario."',
							'".$codclasificacion."',
							'".$nrodocumento."',
							'".++$Secuencia."',
							'1',
							SUBSTRING(NOW(), 6, 2),
							'".$codccosto."',
							'".$IVADEFAULT."',
							'".$monto_impuesto."',
							NOW(),
							'OB',
							'PE',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		
		//	causado
		$sql = "INSERT INTO ap_distribucionobligacion (
							CodProveedor,
							CodTipoDocumento,
							NroDocumento,
							CodCuenta,
							cod_partida,
							CodCentroCosto,
							Monto,
							Periodo,
							Estado,
							Anio,
							FlagCompromiso,
							Origen,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$codbeneficiario."',
							'".$codclasificacion."',
							'".$nrodocumento."',
							'".$IVACTADEF."',
							'".$IVADEFAULT."',
							'".$codccosto."',
							'".$monto_impuesto."',
							NOW(),
							'PE',
							NOW(),
							'S',
							'OB',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		
		//	actualizo caja chica
		$sql = "UPDATE ap_cajachica
				SET
					CodTipoDocumento = CodClasificacion,
					NroDocumento = '".$nrodocumento."',
					NroDocumentoInterno = '".$nrodocumento."'
				WHERE
					FlagCajaChica = '".$flagcajachica."' AND
					Periodo = '".$periodo."' AND
					NroCajaChica = '".$codigo."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
		
		/*//	consulto compromisos
		$sql = "SELECT *
				FROM ap_distribucionobligacion
				WHERE
					CodProveedor = '".$codbeneficiario."' AND
					CodTipoDocumento = '".$codclasificacion."' AND
					NroDocumento = '".$nrodocumento."'
				ORDER BY cod_partida";
				
		$query_dis = mysql_query($sql) or die ($sql.mysql_error());
		
		while ($field_dis = mysql_fetch_array($query_dis)) {
			//	actualizo presupuesto
			$sql = "UPDATE pv_presupuestodet
					SET
						MontoCompromiso = MontoCompromiso + ".$field_dis['Monto'].",
						MontoCausado = MontoCausado + ".$field_dis['Monto']."
					WHERE
						Organismo = '".$codorganismo."' AND
						CodPresupuesto = (SELECT CodPresupuesto FROM pv_presupuesto WHERE EjercicioPpto = '".date("Y")."') AND
						cod_partida = '".$field_dis['cod_partida']."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());

		}*/
		
	}
	
	//	ANULAR
	elseif ($accion == "ANULAR") {
		if ($estado == "PR") $estado_reversa = "AN";
		elseif ($estado == "AP") {
				$estado_reversa = "AN";
			
		}
		elseif ($estado == "PA") {
			//	
			$estado_reversa = "AN";
		}
		//	actualizo
		$sql = "UPDATE ap_cajachica
				SET
					Estado = '".$estado_reversa."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					FlagCajaChica = '".$flagcajachica."' AND
					Periodo = '".$periodo."' AND
					NroCajaChica = '".$codigo."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
	}
}

//	CHEQUES
elseif ($modulo == "CHEQUES") {
	connect();
	//	ENTREGAR
	if ($accion == "entregarCheque") {
		list($nroproceso, $secuencia) = split("[.]", $registro);
		$sql = "UPDATE ap_pagos
				SET
					FechaEntregado = NOW(),
					EstadoEntrega = 'E',
					NroValija = '',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					NroProceso = '".$nroproceso."' AND
					Secuencia = '".$secuencia."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
	}
	
	//	DEVOLVER
	elseif ($accion == "devolverCheque") {
		list($nroproceso, $secuencia) = split("[.]", $registro);
		$sql = "UPDATE ap_pagos
				SET
					FechaEntregado = NOW(),
					EstadoEntrega = 'C',
					NroValija = '',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					NroProceso = '".$nroproceso."' AND
					Secuencia = '".$secuencia."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
	}
	
	//	COBRADO
	elseif ($accion == "cobrarCheque") {
		list($nroproceso, $secuencia) = split("[.]", $registro);
		
		$sql = "SELECT FlagCobrado FROM ap_pagos WHERE NroProceso = '".$nroproceso."' AND Secuencia = '".$secuencia."'";
		$query_flag = mysql_query($sql) or die($sql.mysql_error());
		if (mysql_num_rows($query_flag) != 0) $field_flag = mysql_fetch_array($query_flag);
		if ($field_flag['FlagCobrado'] == "S") {
			$sql = "UPDATE ap_pagos
					SET
						FechaCobranza = '',
						FlagCobrado = 'N',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						NroProceso = '".$nroproceso."' AND
						Secuencia = '".$secuencia."'";
		} else {
			$sql = "UPDATE ap_pagos
					SET
						FechaCobranza = '".formatFechaAMD($fcobranza)."',
						FlagCobrado = 'S',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						NroProceso = '".$nroproceso."' AND
						Secuencia = '".$secuencia."'";
		}
		$query_update = mysql_query($sql) or die($sql.mysql_error());
	}
}

//	REGISTRO DE COMPRAS
elseif ($modulo == "REGISTRO-COMPRAS") {
	connect();
	//	ENTREGAR
	if ($accion == "IMPORTAR") {
		$nrocp = 0;
		$nrocf = 0;
		//	eliminar los registros del periodo actual
		$sql = "DELETE FROM ap_registrocompras
				WHERE
					Periodo = '".$periodo."' AND
					(SistemaFuente = 'CP' OR SistemaFuente = 'CC') ;";
		$query_delete = mysql_query($sql) or die($sql.mysql_error());
		
		//	inserto las obligaciones
		if ($flagcp == "S") {
			$sql = "SELECT
						o.CodProveedor,
						o.CodTipoDocumento,
						o.NroDocumento,
						mp.NomCompleto,
						mp.DocFiscal,
						o.CodOrganismo,
						o.FechaRegistro,
						o.Voucher,
						o.Periodo,
						o.NroRegistro,
						o.NroControl,
						'N' AS FlagCajaChica,
						o.Comentarios,
						o.MontoAfecto,
						o.MontoNoAfecto,
						o.MontoImpuestoOtros,
						o.MontoObligacion,
						o.MontoImpuesto
					FROM
						ap_obligaciones o
						INNER JOIN mastpersonas mp ON (o.CodProveedor = mp.CodPersona)
						INNER JOIN ap_tipodocumento td ON (o.CodTipoDocumento = td.CodTipoDocumento)
					WHERE
						o.Periodo = '".$periodo."' AND
						o.CodOrganismo = '".$organismo."' AND
						(o.Estado = 'AP' OR o.Estado = 'PA') AND
						td.FlagFiscal = 'S'";
			$query_obligaciones = mysql_query($sql) or die($sql.mysql_error());
			while ($field_obligaciones = mysql_fetch_array($query_obligaciones)) {	$nrocp++;
				//	consulto el monto de los impuestos 
				$sql = "SELECT SUM(oi.MontoImpuesto) AS MontoImpuesto
						FROM
							ap_obligacionesimpuesto oi
							INNER JOIN mastimpuestos i ON (oi.CodImpuesto = i.CodImpuesto)
						WHERE
							oi.CodProveedor = '".$field_obligaciones['CodProveedor']."' AND
							oi.CodTipoDocumento = '".$field_obligaciones['CodTipoDocumento']."' AND
							oi.NroDocumento = '".$field_obligaciones['NroDocumento']."' AND
							i.CodRegimenFiscal = 'R' AND
							i.TipoComprobante = 'IVA'";
				$query_impuestos = mysql_query($sql) or die($sql.mysql_error());
				if (mysql_num_rows($query_impuestos) != 0) $field_impuestos = mysql_fetch_array($query_impuestos);
				
				//	inserto el registro de compras
				$sql = "INSERT INTO ap_registrocompras (
									Periodo,
									SistemaFuente,
									Secuencia,
									CodProveedor,
									CodTipoDocumento,
									NroDocumento,
									NomProveedor,
									RifProveedor,
									CodOrganismo,
									FechaDocumento,
									Voucher,
									VoucherPeriodo,
									NroRegistro,
									NroDocumentoInterno,
									EstadoDocumento,
									Comentarios,									
									MontoImponible,
									FiscalImponible,
									ImponibleGravado,									
									MontoImpuestoVentas,
									MontoCreditoFiscal,
									FiscalImpuestoVentas,
									IGVGravado,									
									MontoObligacion,
									FiscalObligacion,
									MontoNoAfecto,
									FiscalNoAfecto,
									FiscalImpuestoRetenido,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$periodo."',
									'CP',
									'".$nrocp."',
									'$field_obligaciones[CodProveedor]',
									'$field_obligaciones[CodTipoDocumento]',
									'$field_obligaciones[NroDocumento]',
									'$field_obligaciones[NomCompleto]',
									'$field_obligaciones[DocFiscal]',
									'$field_obligaciones[CodOrganismo]',
									'$field_obligaciones[FechaRegistro]',
									'$field_obligaciones[Voucher]',
									'$field_obligaciones[Periodo]',
									'$field_obligaciones[NroRegistro]',
									'$field_obligaciones[NroControl]',
									'IN',
									'$field_obligaciones[Comentarios]',									
									'$field_obligaciones[MontoAfecto]',
									'$field_obligaciones[MontoAfecto]',
									'$field_obligaciones[MontoAfecto]',									
									'$field_obligaciones[MontoImpuesto]',
									'$field_obligaciones[MontoImpuesto]',
									'$field_obligaciones[MontoImpuesto]',
									'$field_obligaciones[MontoImpuesto]',									
									'$field_obligaciones[MontoObligacion]',
									'$field_obligaciones[MontoObligacion]',
									'$field_obligaciones[MontoNoAfecto]',
									'$field_obligaciones[MontoNoAfecto]',
									'$field_impuestos[MontoImpuesto]',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die($sql.mysql_error());
			}
		}
		
		//	inserto caja chica
		if ($flagcf == "S") {
			$sql = "SELECT
						cc.CodOrganismo,
						cc.FlagCajaChica,
						cc.NroCajaChica,
						cc.Descripcion,
						o.Voucher,
						o.Periodo,
						o.NroRegistro,
						ccd.CodTipoDocumento,
						ccd.NroDocumento,
						ccd.CodProveedor,
						ccd.NomProveedor,
						ccd.DocFiscal,
						ccd.NroRecibo,
						ccd.FechaDocumento,
						ccd.MontoAfecto,
						ccd.MontoNoAfecto,
						ccd.MontoImpuesto,
						ccd.MontoRetencion,
						ccd.MontoPagado AS MontoObligacion
					FROM
						ap_cajachicadetalle ccd
						INNER JOIN ap_cajachica cc ON (ccd.FlagCajaChica = cc.FlagCajaChica AND
													   ccd.NroCajaChica = cc.NroCajaChica)
						INNER JOIN ap_obligaciones o ON (cc.CodBeneficiario = o.CodProveedor AND
														 cc.CodTipoDocumento = o.CodTipoDocumento AND
														 cc.NroDocumento = o.NroDocumento)
						INNER JOIN mastpersonas mp ON (cc.CodBeneficiario = mp.CodPersona)
					WHERE
						o.Periodo = '".$periodo."' AND
						o.CodOrganismo = '".$organismo."' AND
						(o.Estado = 'AP' OR o.Estado = 'PA') AND
						ccd.CodRegimenFiscal = 'I'";
			$query_cajachica = mysql_query($sql) or die($sql.mysql_error());
			while ($field_cajachica = mysql_fetch_array($query_cajachica)) {	$nrocf++;
				//	consulto el monto de los impuestos 
				$sql = "SELECT SUM(oi.MontoImpuesto) AS MontoImpuesto
						FROM
							ap_obligacionesimpuesto oi
							INNER JOIN mastimpuestos i ON (oi.CodImpuesto = i.CodImpuesto)
						WHERE
							oi.CodProveedor = '".$field_obligaciones['CodProveedor']."' AND
							oi.CodTipoDocumento = '".$field_obligaciones['CodTipoDocumento']."' AND
							oi.NroDocumento = '".$field_obligaciones['NroDocumento']."' AND
							i.CodRegimenFiscal = 'R' AND
							i.TipoComprobante = 'IVA'";
				$query_impuestos = mysql_query($sql) or die($sql.mysql_error());
				if (mysql_num_rows($query_impuestos) != 0) $field_impuestos = mysql_fetch_array($query_impuestos);
				
				//	inserto el registro de compras
				$sql = "INSERT INTO ap_registrocompras (
									Periodo,
									SistemaFuente,
									Secuencia,
									CodProveedor,
									CodTipoDocumento,
									NroDocumento,
									NomProveedor,
									RifProveedor,
									CodOrganismo,
									FechaDocumento,
									Voucher,
									VoucherPeriodo,
									NroRegistro,
									NroDocumentoInterno,
									EstadoDocumento,
									Comentarios,									
									MontoImponible,
									FiscalImponible,
									ImponibleGravado,									
									MontoImpuestoVentas,
									MontoCreditoFiscal,
									FiscalImpuestoVentas,
									IGVGravado,									
									MontoObligacion,
									FiscalObligacion,
									MontoNoAfecto,
									FiscalNoAfecto,
									FiscalImpuestoRetenido,
									FlagCajaChica,
									NroCajaChica,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$periodo."',
									'CC',
									'".$nrocf."',
									'$field_cajachica[CodProveedor]',
									'$field_cajachica[CodTipoDocumento]',
									'$field_cajachica[NroDocumento]',
									'$field_cajachica[NomProveedor]',
									'$field_cajachica[DocFiscal]',
									'$field_cajachica[CodOrganismo]',
									'$field_cajachica[FechaDocumento]',
									'$field_cajachica[Voucher]',
									'$field_cajachica[Periodo]',
									'$field_cajachica[NroRegistro]',
									'$field_cajachica[NroRecibo]',
									'IN',
									'$field_cajachica[Descripcion]',									
									'$field_cajachica[MontoAfecto]',
									'$field_cajachica[MontoAfecto]',
									'$field_cajachica[MontoAfecto]',									
									'$field_cajachica[MontoImpuesto]',
									'$field_cajachica[MontoImpuesto]',
									'$field_cajachica[MontoImpuesto]',
									'$field_cajachica[MontoImpuesto]',									
									'$field_cajachica[MontoObligacion]',
									'$field_cajachica[MontoObligacion]',
									'$field_cajachica[MontoNoAfecto]',
									'$field_cajachica[MontoNoAfecto]',
									'$field_impuestos[MontoImpuesto]',
									'$field_cajachica[FlagCajaChica]',
									'$field_cajachica[NroCajaChica]',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die($sql.mysql_error());
			}
		}
		echo "|$nrocp|$nrocf";
		
	}
	
	//	ELIMINAR
	elseif ($accion == "ELIMINAR") {
		list($periodo, $sistema, $secuencia) = split("[.]", $registro);
		$sql = "DELETE FROM ap_registrocompras WHERE Periodo = '".$periodo."' AND SistemaFuente = '".$sistema."' AND Secuencia = '".$secuencia."'";
		$query_delete = mysql_query($sql) or die($sql.mysql_error());
	}
}
?>
