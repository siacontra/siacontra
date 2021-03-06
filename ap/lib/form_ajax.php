<?php
session_start();
include("../../lib/fphp.php");
include("fphp.php");
//	$__archivo = fopen("query.sql", "w+");
//	fwrite($__archivo, $sql.";\n\n");
///////////////////////////////////////////////////////////////////////////////
//	PARA AJAX
///////////////////////////////////////////////////////////////////////////////
//	generacion de vouchers
if ($modulo == "generar_vouchers") {
	$Creditos = setNumero($Creditos);
	$Debitos = setNumero($Debitos);
	##
	if (formatFechaAMD($FechaVoucher) == "") die("No puede generar el voucher sin la fecha");
	elseif ($Periodo == "") die("No puede generar el voucher sin el periodo");
	//	genero nuevo voucher
	$NroVoucher = getCodigo_3("ac_vouchermast", "NroVoucher", "CodVoucher", "Periodo", $CodVoucher, $Periodo, 4);
	$NroInterno = getCodigo("ac_vouchermast", "NroInterno", 10);
	$Voucher = "$CodVoucher-$NroVoucher";
	
	//	inserto voucher
	$sql = "INSERT INTO ac_vouchermast (
						CodOrganismo,
						Periodo,
						Voucher,
						Prefijo,
						NroVoucher,
						CodVoucher,
						CodDependencia,
						CodSistemaFuente,
						Creditos,
						Debitos,
						Lineas,
						PreparadoPor,
						FechaPreparacion,
						AprobadoPor,
						FechaAprobacion,
						TituloVoucher,
						ComentariosVoucher,
						FechaVoucher,
						NroInterno,
						FlagTransferencia,
						Estado,
						CodLibroCont,
						UltimoUsuario,
						UltimaFecha
			) VALUES (
						'".$CodOrganismo."',
						'".$Periodo."',
						'".$Voucher."',
						'".$CodVoucher."',
						'".$NroVoucher."',
						'".$CodVoucher."',
						'".$CodDependencia."',
						'".$CodSistemaFuente."',
						'".$Creditos."',
						'".$Debitos."',
						'".$Lineas."',
						'".$_SESSION["CODPERSONA_ACTUAL"]."',
						'".formatFechaAMD($FechaVoucher)."',
						'".$_SESSION["CODPERSONA_ACTUAL"]."',
						'".formatFechaAMD($FechaVoucher)."',
						'".$ComentariosVoucher."',
						'".$ComentariosVoucher."',
						'".formatFechaAMD($FechaVoucher)."',
						'".$NroInterno."',
						'N',
						'MA',
						'".$CodLibroCont."',
						'".$_SESSION["USUARIO_ACTUAL"]."',
						NOW()
			)";
	$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	
	//	inserto los detalles
	$linea = split(";char:tr;", $detalles);
	foreach ($linea as $registro) {
		list($_Linea, $_CodCuenta, $_Descripcion, $_MontoVoucher, $_CodPersona, $_ReferenciaTipoDocumento, $_ReferenciaNroDocumento, $_CodCentroCosto, $_FechaVoucher) = split(';char:td;', $registro);
		
		//	inserto detalle
		$sql = "INSERT INTO ac_voucherdet (
							CodOrganismo,
							Periodo,
							Voucher,
							Linea,
							CodCuenta,
							MontoVoucher,
							MontoPost,
							CodPersona,
							FechaVoucher,
							CodCentroCosto,
							ReferenciaTipoDocumento,
							ReferenciaNroDocumento,
							Descripcion,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$CodOrganismo."',
							'".$Periodo."',
							'".$Voucher."',
							'".$_Linea."',
							'".$_CodCuenta."',
							'".$_MontoVoucher."',
							'".$_MontoVoucher."',
							'".$_CodPersona."',
							'".$_FechaVoucher."',
							'".$_CodCentroCosto."',
							'".$_ReferenciaTipoDocumento."',
							'".$_ReferenciaNroDocumento."',
							'".$_Descripcion."',
							'MA',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	verifico balance
		$sql = "SELECT *
				FROM ac_voucherbalance
				WHERE
					CodCuenta = '".$_CodCuenta."' AND
					Periodo = '".$Periodo."'";
		$query = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query) != 0) {
			//	actualizo
			$sql = "UPDATE ac_voucherbalance
					SET SaldoBalance = SaldoBalance + (".floatval($_MontoVoucher).")
					WHERE
						CodOrganismo = '".$CodOrganismo."' AND
						Periodo = '".$Periodo."' AND
						CodCuenta = '".$_CodCuenta."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		} else {
			//	actualizo
			$sql = "INSERT INTO ac_voucherbalance (
								CodOrganismo,
								Periodo,
								CodCuenta,
								SaldoBalance,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodOrganismo."',
								'".$Periodo."',
								'".$_CodCuenta."',
								'".$_MontoVoucher."',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
	}
	
	//	si genere el voucher desde generar voucher de transacciones bancarias
	if ($accion == "transacciones") {
		//	actualizo transaccion banco
		$sql = "UPDATE ap_bancotransaccion
				SET
					Voucher = '".$Voucher."',
					VoucherPeriodo = '".$Periodo."',
					Estado = 'CO'
				WHERE NroTransaccion = '".$NroTransaccion."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	si genere el voucher desde generar voucher de obligaciones
	if ($accion == "provision") {
		//	actualizo obligacion
		$sql = "UPDATE ap_obligaciones
				SET
					FlagContabilizacionPendiente = 'N',
					Voucher = '".$Voucher."',
					Periodo = '".$Periodo."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = '".$CodTipoDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_update = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo orden de pago
		$sql = "UPDATE ap_ordenpago
				SET
					VoucherDocumento = '".$Voucher."',
					VoucherDocPeriodo = '".$Periodo."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = '".$CodTipoDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_update = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	si genere el voucher desde generar voucher de pagos
	if ($accion == "pagos") {
		//	actualizo pago
		$sql = "UPDATE ap_pagos
				SET
					FlagContabilizacionPendiente = 'N',
					VoucherPago = '".$Voucher."',
					Periodo = '".$Periodo."'
				WHERE
					NroProceso = '".$NroProceso."' AND
					CodTipoPago = '".$CodTipoPago."' AND
					NroCuenta = '".$NroCuenta."'";
		$query_update = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo orden de pago
		$sql = "UPDATE ap_ordenpago
				SET
					Voucher = '".$Voucher."',
					Periodo = '".$Periodo."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = '".$CodTipoDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_update = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	obligacion
elseif ($modulo == "obligacion") {
	$MontoObligacion = setNumero($MontoObligacion);
	$MontoImpuestoOtros = setNumero($MontoImpuestoOtros);
	$MontoNoAfecto = setNumero($MontoNoAfecto);
	$MontoAfecto = setNumero($MontoAfecto);
	$MontoAdelanto = setNumero($MontoAdelanto);
	$MontoImpuesto = setNumero($MontoImpuesto);
	$MontoPagoParcial = setNumero($MontoPagoParcial);
	$Comentarios = changeUrl($Comentarios);
	$ComentariosAdicional = changeUrl($ComentariosAdicional);
	$MotivoAnulacion = changeUrl($MotivoAnulacion);
	$detalles_impuesto = changeUrl($detalles_impuesto);
	$detalles_documento = changeUrl($detalles_documento);
	$detalles_distribucion = changeUrl($detalles_distribucion);
	
	//	nuevo
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		$Periodo = substr(formatFechaAMD($FechaRegistro), 0, 7);
		$Anio = substr($Periodo, 0, 4);
		//	verifico valores ingresados
		if (valObligacion($CodProveedor, $CodTipoDocumento, $NroDocumento)) die("Nro. de Obligacion Ya ingresado");
		
		//	obtengo el numero de las ordenes
		$ReferenciaTipoDocumento = "";
		$ReferenciaNroDocumento = "";
		$linea_documento = split(";char:tr;", $detalles_documento);
		foreach ($linea_documento as $registro) {
			list($_Porcentaje, $_DocumentoClasificacion, $_DocumentoReferencia, $_Fecha, $_ReferenciaTipoDocumento, $_ReferenciaNroDocumento, $_MontoTotal, $_MontoAfecto, $_MontoImpuestos, $_MontoAfecto, $_MontoNoAfecto, $_Comentarios) = split(";char:td;", $registro);
			$ReferenciaTipoDocumento = $_ReferenciaTipoDocumento;
			if ($k == 0) $ReferenciaNroDocumento .= $_ReferenciaNroDocumento;
			else $ReferenciaNroDocumento .= "-".$_ReferenciaNroDocumento;
			$k++;
		}
		
		//	inserto obligacion
		$NroRegistro = getCodigo_2("ap_obligaciones", "NroRegistro", "CodOrganismo", $CodOrganismo, 6);
		$sql = "INSERT INTO ap_obligaciones (
							CodProveedor,
							CodTipoDocumento,
							NroDocumento,
							CodOrganismo,
							NroCuenta,
							CodTipoPago,
							FechaRegistro,
							FechaVencimiento,
							CodTipoServicio,
							ReferenciaTipoDocumento,
							ReferenciaNroDocumento,
							MontoObligacion,
							MontoImpuestoOtros,
							MontoNoAfecto,
							MontoAfecto,
							MontoAdelanto,
							MontoImpuesto,
							MontoPagoParcial,
							IngresadoPor,
							NroRegistro,
							Comentarios,
							ComentariosAdicional,
							CodCentroCosto,
							FechaRecepcion,
							CodProveedorPagar,
							FechaDocumento,
							Estado,
							NroControl,
							FechaProgramada,
							FechaPreparacion,
							Periodo,
							FechaFactura,
							FlagGenerarPago,
							FlagAfectoIGV,
							FlagDiferido,
							FlagPagoDiferido,
							FlagCompromiso,
							FlagPresupuesto,
							FlagPagoIndividual,
							FlagCajaChica,
							FlagDistribucionManual,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$CodProveedor."',
							'".$CodTipoDocumento."',
							'".$NroDocumento."',
							'".$CodOrganismo."',
							'".$NroCuenta."',
							'".$CodTipoPago."',
							'".formatFechaAMD($FechaRegistro)."',
							'".formatFechaAMD($FechaVencimiento)."',
							'".$CodTipoServicio."',
							'".$ReferenciaTipoDocumento."',
							'".$ReferenciaNroDocumento."',
							'".($MontoObligacion)."',
							'".($MontoImpuestoOtros)."',
							'".($MontoNoAfecto)."',
							'".($MontoAfecto)."',
							'".($MontoAdelanto)."',
							'".($MontoImpuesto)."',
							'".($MontoPagoParcial)."',
							'".$_SESSION["CODPERSONA_ACTUAL"]."',
							'".$NroRegistro."',
							'".$Comentarios."',
							'".$ComentariosAdicional."',
							'".$CodCentroCosto."',
							'".formatFechaAMD($FechaRecepcion)."',
							'".$CodProveedorPagar."',
							'".formatFechaAMD($FechaDocumento)."',
							'".$Estado."',
							'".$NroControl."',
							'".formatFechaAMD($FechaProgramada)."',
							'".formatFechaAMD($FechaPreparacion)."',
							'".$Periodo."',
							'".formatFechaAMD($FechaFactura)."',
							'".$FlagGenerarPago."',
							'".$FlagAfectoIGV."',
							'".$FlagDiferido."',
							'".$FlagPagoDiferido."',
							'".$FlagCompromiso."',
							'".$FlagPresupuesto."',
							'".$FlagPagoIndividual."',
							'".$FlagCajaChica."',
							'".$FlagDistribucionManual."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	impuestos
		if ($detalles_impuesto != "") {
			$linea_impuesto = split(";char:tr;", $detalles_impuesto);	$_Linea=0;
			foreach ($linea_impuesto as $registro) {	$_Linea++;
				list($_CodImpuesto, $_CodConcepto, $_Signo, $_FlagImponible, $_FlagProvision, $_CodCuenta, $_MontoAfecto, $_FactorPorcentaje, $_MontoImpuesto) = split(";char:td;", $registro);
				//	inserto
				$sql = "INSERT INTO ap_obligacionesimpuesto (
									CodProveedor,
									CodTipoDocumento,
									NroDocumento,
									Linea,
									CodImpuesto,
									CodConcepto,
									FactorPorcentaje,
									MontoImpuesto,
									MontoAfecto,
									CodCuenta,
									FlagProvision,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$CodProveedor."',
									'".$CodTipoDocumento."',
									'".$NroDocumento."',
									'".$_Linea."',
									'".$_CodImpuesto."',
									'".$_CodConcepto."',
									'".$_FactorPorcentaje."',
									'".$_MontoImpuesto."',
									'".$_MontoAfecto."',
									'".$_CodCuenta."',
									'".$_FlagProvision."',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		
		//	documentos
		if ($detalles_documento != "") {
			$linea_documento = split(";char:tr;", $detalles_documento);	$_Linea=0;
			foreach ($linea_documento as $registro) {	$_Linea++;
				list($_Porcentaje, $_DocumentoClasificacion, $_DocumentoReferencia, $_Fecha, $_ReferenciaTipoDocumento, $_ReferenciaNroDocumento, $_MontoTotal, $_MontoAfecto, $_MontoImpuestos, $_MontoAfecto, $_MontoNoAfecto, $_Comentarios) = split(";char:td;", $registro);
				
				//	consulto si existe el documento
				$sql = "SELECT *
						FROM ap_documentos
						WHERE
							Anio = '".$Anio."' AND
							CodProveedor = '".$CodProveedor."' AND
							DocumentoClasificacion = '".$_DocumentoClasificacion."' AND
							DocumentoReferencia = '".$_DocumentoReferencia."'";
				$query_documento = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_documento) != 0) {
					//	actualizo documento
					$sql = "UPDATE ap_documentos
							SET 
								ObligacionTipoDocumento = '".$CodTipoDocumento."',
								ObligacionNroDocumento = '".$NroDocumento."',
								Estado = 'RV',
								UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
								UltimaFecha = NOW()
							WHERE
								Anio = '".$Anio."' AND
								CodProveedor = '".$CodProveedor."' AND
								DocumentoClasificacion = '".$_DocumentoClasificacion."' AND
								DocumentoReferencia = '".$_DocumentoReferencia."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				} else {
					//	inserto documento
					$secuencia_referencia = getCorrelativoSecuencia_2("ap_documentos", "ReferenciaTipoDocumento", "ReferenciaNroDocumento", $_ReferenciaTipoDocumento, $_ReferenciaNroDocumento);
					$sql = "INSERT INTO ap_documentos (
										CodOrganismo,
										CodProveedor,
										DocumentoClasificacion,
										DocumentoReferencia,
										Fecha,
										ReferenciaTipoDocumento,
										ReferenciaNroDocumento,
										Estado,
										ObligacionTipoDocumento,
										ObligacionNroDocumento,
										MontoAfecto,
										MontoNoAfecto,
										MontoImpuestos,
										MontoTotal,
										MontoPendiente,
										Anio,
										UltimoUsuario,
										UltimaFecha
							) VALUES (
										'".$CodOrganismo."',
										'".$CodProveedor."',
										'".$_DocumentoClasificacion."',
										'".$_DocumentoReferencia."',
										NOW(),
										'".$_ReferenciaTipoDocumento."',
										'".$_ReferenciaNroDocumento."',
										'RV',
										'".$CodTipoDocumento."',
										'".$NroDocumento."',
										'".$_MontoAfecto."',
										'".$_MontoNoAfecto."',
										'".$_MontoImpuestos."',
										'".$_MontoTotal."',
										'".$_MontoTotal."',
										'".$Anio."',
										'".$_SESSION["USUARIO_ACTUAL"]."',
										NOW()
							)";
					$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					
					//	documentos detalle
					if ($_ReferenciaTipoDocumento == "OC") {
						$sql = "SELECT *
								FROM lg_ordencompradetalle
								WHERE
									Anio = '".$Anio."' AND
									CodOrganismo = '".$CodOrganismo."' AND
									NroOrden = '".$_ReferenciaNroDocumento."'";
					} else {
						$sql = "SELECT *, (CantidadRecibida * PrecioUnit) As PrecioCantidad
								FROM lg_ordenserviciodetalle
								WHERE
									Anio = '".$Anio."' AND
									CodOrganismo = '".$CodOrganismo."' AND
									NroOrden = '".$_ReferenciaNroDocumento."'";
					}
					$query_od = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					while ($field_od = mysql_fetch_array($query_od)) {
						$sql = "INSERT INTO ap_documentosdetalle (
											CodProveedor,
											DocumentoClasificacion,
											DocumentoReferencia,
											Secuencia,
											CodItem,
											CommoditySub,
											Descripcion,
											Cantidad,
											PrecioUnit,
											PrecioCantidad,
											Total,
											CodCentroCosto,
											Anio,
											UltimoUsuario,
											UltimaFecha
								) VALUES (
											'".$CodProveedor."',
											'".$_DocumentoClasificacion."',
											'$_ReferenciaTipoDocumento-$_ReferenciaNroDocumento-$secuencia_referencia',
											'".$field_od['Secuencia']."',
											'".$field_od['CodItem']."',
											'".$field_od['CommoditySub']."',
											'".$field_od['Descripcion']."',
											'".$field_od['CantidadPedida']."',
											'".$field_od['PrecioUnit']."',
											'".$field_od['PrecioCantidad']."',
											'".$field_od['Total']."',
											'".$field_od['CodCentroCosto']."',
											'".$Periodo."',
											'".$_SESSION["USUARIO_ACTUAL"]."',
											NOW()
								)";
						$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					}
				}
							
				//	verifico si la orden tiene activos fijos
				if ($_ReferenciaTipoDocumento == "OC") {
					$sql = "SELECT ocd.*
							FROM 
								lg_ordencompradetalle ocd
								INNER JOIN lg_commoditysub cs ON (ocd.CommoditySub = cs.Codigo)
								INNER JOIN lg_commoditymast cm ON (cs.CommodityMast = cm.CommodityMast)
							WHERE 
								(cm.Clasificacion = 'ACT' OR cm.Clasificacion = 'BME') AND
								ocd.Anio = SUBSTRING('".$Periodo."', 1, 4) AND
								ocd.CodOrganismo = '".$CodOrganismo."' AND
								ocd.NroOrden = '".$_ReferenciaNroDocumento."'";
					$query_comm = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					if (mysql_num_rows($query_comm) != 0) {
						$sql = "UPDATE lg_activofijo
								SET
									FlagFacturado = 'S',
									ObligacionTipoDocumento = '".$CodTipoDocumento."',
									ObligacionNroDocumento = '".$NroDocumento."'
								WHERE
									Anio = '".$Anio."' AND
									CodOrganismo = '".$CodOrganismo."' AND
									NroOrden = '".$_ReferenciaNroDocumento."'";
						$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
					}
				}
			}
		}
		
		//	distribucion
		if ($detalles_distribucion != "") {
			$linea_distribucion = split(";char:tr;", $detalles_distribucion);	$_Secuencia=0;
			foreach ($linea_distribucion as $registro) {	$_Secuencia++;
				list($_cod_partida, $_CodCuenta, $_CodCentroCosto, $_FlagNoAfectoIGV, $_Monto, $_TipoOrden, $_NroOrden, $_Referencia, $_Descripcion, $_CodPersona, $_NroActivo, $_FlagDiferido) = split(";char:td;", $registro);
				//	inserto distribucion x cuentas
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
									TipoOrden,
									NroOrden,
									FlagNoAfectoIGV,
									Referencia,
									CodPersona,
									NroActivo,
									FlagDiferido,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$CodProveedor."',
									'".$CodTipoDocumento."',
									'".$NroDocumento."',
									'".$_Secuencia."',
									'1',
									'".$_Descripcion."',
									'".$_Monto."',
									'".$_CodCentroCosto."',
									'".$_CodCuenta."',
									'".$_cod_partida."',
									'".$_TipoOrden."',
									'".$_NroOrden."',
									'".$_FlagNoAfectoIGV."',
									'".$_Referencia."',
									'".$_CodPersona."',
									'".$_NroActivo."',
									'".$_FlagDiferido."',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		
		//	resumen
		if ($FlagPresupuesto == "S") $cod_partida = $_PARAMETRO["IVADEFAULT"]; else $cod_partida = "";
		if ($MontoImpuesto > 0) {
			$sql = "(SELECT
						SUM(Monto) AS Monto,
						cod_partida,
						CodCuenta,
						CodCentroCosto
					FROM ap_obligacionescuenta
					WHERE
						CodProveedor = '".$CodProveedor."' AND
						CodTipoDocumento = '".$CodTipoDocumento."' AND
						NroDocumento = '".$NroDocumento."'
					GROUP BY cod_partida,CodCuenta)
					UNION
					(SELECT
						'".($MontoImpuesto)."' AS Monto,
						'".$cod_partida."' AS cod_partida,
						'".$_PARAMETRO["IVACTADEF"]."' AS CodCuenta,
						'".$CodCentroCosto."' AS CodCentroCosto)";
		} else {
			$sql = "SELECT
						SUM(Monto) AS Monto,
						cod_partida,
						CodCuenta,
						CodCentroCosto
					FROM ap_obligacionescuenta
					WHERE
						CodProveedor = '".$CodProveedor."' AND
						CodTipoDocumento = '".$CodTipoDocumento."' AND
						NroDocumento = '".$NroDocumento."'
					GROUP BY cod_partida, CodCuenta";
		}
		$query_res = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$_Secuencia = 0;
		while ($field_res = mysql_fetch_array($query_res)) {
			//	inserto en la distribucion
			$anho = date('Y');
			$sql = "INSERT INTO ap_distribucionobligacion (
								CodProveedor,
								CodTipoDocumento,
								CodPresupuesto,
								NroDocumento,
								CodCentroCosto,
								Monto,
								CodCuenta,
								cod_partida,
								Periodo,
								Estado,
								FlagCompromiso,
								Anio,
								Origen,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodProveedor."',
								'".$CodTipoDocumento."',
								(SELECT CodPresupuesto FROM `pv_presupuesto` WHERE EjercicioPpto = '".$anho."'),
								'".$NroDocumento."',
								'".$field_res['CodCentroCosto']."',
								'".$field_res['Monto']."',
								'".$field_res['CodCuenta']."',
								'".$field_res['cod_partida']."',
								'".$Periodo."',
								'PE',
								'".$FlagCompromiso."',
								'".$Anio."',
								'OB',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			if ($FlagCompromiso == "S") {	$_Secuencia++;
				//	inserto en distribucion compromisos
				$anho = date('Y');
				 $sql = "INSERT INTO lg_distribucioncompromisos (
									Anio,
									CodOrganismo,
									CodPresupuesto,
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
									'".$Anio."',
									'".$CodOrganismo."',
									(SELECT CodPresupuesto FROM `pv_presupuesto` WHERE EjercicioPpto = '".$anho."'),
									'".$CodProveedor."',
									'".$CodTipoDocumento."',
									'".$NroDocumento."',
									'".$_Secuencia."',
									'1',
									SUBSTRING(NOW(), 6, 2),
									'".$field_res['CodCentroCosto']."',
									'".$field_res['cod_partida']."',
									'".$field_res['Monto']."',
									'".$Periodo."',
									'OB',
									'PE',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}	//die("FIN");
		mysql_query("COMMIT");
	}
	
	//	modificar
	elseif ($accion == "modificar") {
		$Periodo = substr(formatFechaAMD($FechaRegistro), 0, 7);
		$Anio = substr($Periodo, 0, 4);
		mysql_query("BEGIN");
		//	actualizo obligacion
		$sql = "UPDATE ap_obligaciones
				SET
					NroCuenta = '".$NroCuenta."',
					CodTipoPago = '".$CodTipoPago."',
					FechaRegistro = '".formatFechaAMD($FechaRegistro)."',
					FechaVencimiento = '".formatFechaAMD($FechaVencimiento)."',
					CodTipoServicio = '".$CodTipoServicio."',
					MontoObligacion = '".$MontoObligacion."',
					MontoImpuestoOtros = '".$MontoImpuestoOtros."',
					MontoNoAfecto = '".$MontoNoAfecto."',
					MontoAfecto = '".$MontoAfecto."',
					MontoAdelanto = '".$MontoAdelanto."',
					MontoImpuesto = '".$MontoImpuesto."',
					MontoPagoParcial = '".$MontoPagoParcial."',
					Comentarios = '".$Comentarios."',
					ComentariosAdicional = '".$ComentariosAdicional."',
					CodCentroCosto = '".$CodCentroCosto."',
					FechaRecepcion = '".formatFechaAMD($FechaRecepcion)."',
					CodProveedorPagar = '".$CodProveedorPagar."',
					FechaDocumento = '".formatFechaAMD($FechaDocumento)."',
					FechaProgramada = '".formatFechaAMD($FechaProgramada)."',
					FechaPreparacion = '".formatFechaAMD($FechaPreparacion)."',
					FechaFactura = '".formatFechaAMD($FechaFactura)."',
					FlagGenerarPago = '".$FlagGenerarPago."',
					FlagAfectoIGV = '".$FlagAfectoIGV."',
					FlagDiferido = '".$FlagDiferido."',
					FlagPagoDiferido = '".$FlagPagoDiferido."',
					FlagCompromiso = '".$FlagCompromiso."',
					FlagPresupuesto = '".$FlagPresupuesto."',
					FlagPagoIndividual = '".$FlagPagoIndividual."',
					FlagCajaChica = '".$FlagCajaChica."',
					FlagDistribucionManual = '".$FlagDistribucionManual."',
					NroControl = '".$NroControl."',
					Periodo = '".$Periodo."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = '".$CodTipoDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	impuestos
		$sql = "DELETE FROM ap_obligacionesimpuesto
				WHERE
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = '".$CodTipoDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if ($detalles_impuesto != "") {
			$linea_impuesto = split(";char:tr;", $detalles_impuesto);	$_Linea=0;
			foreach ($linea_impuesto as $registro) {	$_Linea++;
				list($_CodImpuesto, $_CodConcepto, $_Signo, $_FlagImponible, $_FlagProvision, $_CodCuenta, $_MontoAfecto, $_FactorPorcentaje, $_MontoImpuesto) = split(";char:td;", $registro);
				//	inserto
				$sql = "INSERT INTO ap_obligacionesimpuesto (
									CodProveedor,
									CodTipoDocumento,
									NroDocumento,
									Linea,
									CodImpuesto,
									CodConcepto,
									FactorPorcentaje,
									MontoImpuesto,
									MontoAfecto,
									CodCuenta,
									FlagProvision,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$CodProveedor."',
									'".$CodTipoDocumento."',
									'".$NroDocumento."',
									'".$_Linea."',
									'".$_CodImpuesto."',
									'".$_CodConcepto."',
									'".$_FactorPorcentaje."',
									'".$_MontoImpuesto."',
									'".$_MontoAfecto."',
									'".$_CodCuenta."',
									'".$_FlagProvision."',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		
		//	distribucion
		$sql = "DELETE FROM ap_obligacionescuenta
				WHERE
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = '".$CodTipoDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if ($detalles_distribucion != "") {
			$linea_distribucion = split(";char:tr;", $detalles_distribucion);	$_Secuencia=0;
			foreach ($linea_distribucion as $registro) {	$_Secuencia++;
				list($_cod_partida, $_CodCuenta, $_CodCentroCosto, $_FlagNoAfectoIGV, $_Monto, $_TipoOrden, $_NroOrden, $_Referencia, $_Descripcion, $_CodPersona, $_NroActivo, $_FlagDiferido) = split(";char:td;", $registro);
				//	inserto distribucion x cuentas
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
									TipoOrden,
									NroOrden,
									FlagNoAfectoIGV,
									Referencia,
									CodPersona,
									NroActivo,
									FlagDiferido,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$CodProveedor."',
									'".$CodTipoDocumento."',
									'".$NroDocumento."',
									'".$_Secuencia."',
									'1',
									'".$_Descripcion."',
									'".$_Monto."',
									'".$_CodCentroCosto."',
									'".$_CodCuenta."',
									'".$_cod_partida."',
									'".$_TipoOrden."',
									'".$_NroOrden."',
									'".$_FlagNoAfectoIGV."',
									'".$_Referencia."',
									'".$_CodPersona."',
									'".$_NroActivo."',
									'".$_FlagDiferido."',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		
		//	resumen
		$sql = "DELETE FROM ap_distribucionobligacion
				WHERE
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = '".$CodTipoDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if ($FlagCompromiso == "S") {
			$sql = "DELETE FROM lg_distribucioncompromisos
					WHERE
						Anio = '".$Anio."' AND
						CodOrganismo = '".$CodOrganismo."' AND
						CodProveedor = '".$CodProveedor."' AND
						CodTipoDocumento = '".$CodTipoDocumento."' AND
						NroDocumento = '".$NroDocumento."'";
			$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		if ($FlagPresupuesto == "S") $cod_partida = $_PARAMETRO["IVADEFAULT"]; else $cod_partida = "";
		if ($MontoImpuesto > 0) {
			$sql = "(SELECT
						SUM(Monto) AS Monto,
						cod_partida,
						CodCuenta,
						CodCentroCosto
					FROM ap_obligacionescuenta
					WHERE
						CodProveedor = '".$CodProveedor."' AND
						CodTipoDocumento = '".$CodTipoDocumento."' AND
						NroDocumento = '".$NroDocumento."'
					GROUP BY cod_partida, CodCuenta)
					UNION
					(SELECT
						'".($MontoImpuesto)."' AS Monto,
						'".$cod_partida."' AS cod_partida,
						'".$_PARAMETRO["IVACTADEF"]."' AS CodCuenta,
						'".$CodCentroCosto."' AS CodCentroCosto)";
		} else {
			$sql = "SELECT
						SUM(Monto) AS Monto,
						cod_partida,
						CodCuenta,
						CodCentroCosto
					FROM ap_obligacionescuenta
					WHERE
						CodProveedor = '".$CodProveedor."' AND
						CodTipoDocumento = '".$CodTipoDocumento."' AND
						NroDocumento = '".$NroDocumento."'
					GROUP BY cod_partida, CodCuenta";
		}
		$query_res = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));	$_Secuencia = 0;
		while ($field_res = mysql_fetch_array($query_res)) {
			//	inserto en la distribucion
			$anho = date('Y');
			$sql = "INSERT INTO ap_distribucionobligacion (
								CodProveedor,
								CodTipoDocumento,
								CodPresupuesto,
								NroDocumento,
								CodCentroCosto,
								Monto,
								CodCuenta,
								cod_partida,
								Periodo,
								Estado,
								FlagCompromiso,
								Anio,
								Origen,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$CodProveedor."',
								'".$CodTipoDocumento."',
								(SELECT CodPresupuesto FROM `pv_presupuesto` WHERE EjercicioPpto = '".$anho."'),
								'".$NroDocumento."',
								'".$field_res['CodCentroCosto']."',
								'".$field_res['Monto']."',
								'".$field_res['CodCuenta']."',
								'".$field_res['cod_partida']."',
								'".$Periodo."',
								'PE',
								'".$FlagCompromiso."',
								'".$Anio."',
								'OB',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			if ($FlagCompromiso == "S") {	$_Secuencia++;
				//	inserto en distribucion compromisos
				$anho = date('Y');
				 $sql = "INSERT INTO lg_distribucioncompromisos (
									Anio,
									CodOrganismo,
									CodPresupuesto,
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
									'".$Anio."',
									'".$CodOrganismo."',
									(SELECT CodPresupuesto FROM `pv_presupuesto` WHERE EjercicioPpto = '".$anho."'),
									'".$CodProveedor."',
									'".$CodTipoDocumento."',
									'".$NroDocumento."',
									'".$_Secuencia."',
									'1',
									SUBSTRING(NOW(), 6, 2),
									'".$field_res['CodCentroCosto']."',
									'".$field_res['cod_partida']."',
									'".$field_res['Monto']."',
									'".$Periodo."',
									'OB',
									'PE',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		mysql_query("COMMIT");
	}
	
	//	revisar
	elseif ($accion == "revisar") {
		mysql_query("BEGIN");
		//	actualizo obligacion
		$sql = "UPDATE ap_obligaciones
				SET
					Estado = 'RV',
					RevisadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaRevision = '".formatFechaAMD($FechaRevision)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = '".$CodTipoDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo distribucion
		$sql = "UPDATE ap_distribucionobligacion
				SET
					Estado = 'CA',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = '".$CodTipoDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if ($FlagCompromiso == "S") {
			$sql = "UPDATE lg_distribucioncompromisos
					SET
						Estado = 'CO',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						Anio = '".substr($Periodo, 0, 4)."' AND
						CodOrganismo = '".$CodOrganismo."' AND
						CodProveedor = '".$CodProveedor."' AND
						CodTipoDocumento = '".$CodTipoDocumento."' AND
						NroDocumento = '".$NroDocumento."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	distribucion
		if ($FlagPresupuesto == "S") {
			$sql = "SELECT *
					FROM ap_distribucionobligacion
					WHERE
						CodProveedor = '".$CodProveedor."' AND
						CodTipoDocumento = '".$CodTipoDocumento."' AND
						NroDocumento = '".$NroDocumento."'
					ORDER BY cod_partida";
			$query_dis = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			
			while ($field_dis = mysql_fetch_array($query_dis)) {
				if ($FlagCompromiso == "S") $comprometer = "MontoCompromiso = MontoCompromiso + ".$field_dis['Monto'].",";
				//	actualizo presupuesto
				/*$sql = "UPDATE pv_presupuestodet
						SET
							$comprometer
							MontoCausado = MontoCausado + ".$field_dis['Monto']."
						WHERE
							Organismo = '".$CodOrganismo."' AND
							CodPresupuesto = (SELECT CodPresupuesto FROM pv_presupuesto WHERE EjercicioPpto = '".substr($Periodo, 0, 4)."') AND
							cod_partida = '".$field_dis['cod_partida']."'";
				$query_update = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));*/
			}
		}
		mysql_query("COMMIT");
	}
	
	
	
	
			//	conformar	
	elseif ($accion == "conformar") {
		mysql_query("BEGIN");
		//	actualizo obligacion
		$sql = "UPDATE ap_obligaciones
				SET
					Estado = 'CO',
					ConformadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaConformado = '".formatFechaAMD($FechaRevision)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = '".$CodTipoDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo distribucion
		$sql = "UPDATE ap_distribucionobligacion
				SET
					Estado = 'CA',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = '".$CodTipoDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		if ($FlagCompromiso == "S") {
			$sql = "UPDATE lg_distribucioncompromisos
					SET
						Estado = 'CO',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						Anio = '".substr($Periodo, 0, 4)."' AND
						CodOrganismo = '".$CodOrganismo."' AND
						CodProveedor = '".$CodProveedor."' AND
						CodTipoDocumento = '".$CodTipoDocumento."' AND
						NroDocumento = '".$NroDocumento."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	distribucion
		if ($FlagPresupuesto == "S") {
			$sql = "SELECT *
					FROM ap_distribucionobligacion
					WHERE
						CodProveedor = '".$CodProveedor."' AND
						CodTipoDocumento = '".$CodTipoDocumento."' AND
						NroDocumento = '".$NroDocumento."'
					ORDER BY cod_partida";
			$query_dis = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			
			while ($field_dis = mysql_fetch_array($query_dis)) {
				if ($FlagCompromiso == "S") $comprometer = "MontoCompromiso = MontoCompromiso + ".$field_dis['Monto'].",";
				//	actualizo presupuesto
				/*$sql = "UPDATE pv_presupuestodet
						SET
							$comprometer
							MontoCausado = MontoCausado + ".$field_dis['Monto']."
						WHERE
							Organismo = '".$CodOrganismo."' AND
							CodPresupuesto = (SELECT CodPresupuesto FROM pv_presupuesto WHERE EjercicioPpto = '".substr($Periodo, 0, 4)."') AND
							cod_partida = '".$field_dis['cod_partida']."'";
				$query_update = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));*/
			}
		}
		mysql_query("COMMIT");
	}
	//	aprobar
	elseif ($accion == "aprobar") {
		$Periodo = substr(formatFechaAMD($FechaRegistro), 0, 7);
		$Anio = substr($Periodo, 0, 4);
		mysql_query("BEGIN");
		//	documentos
		$sql = "SELECT *
				FROM ap_documentos
				WHERE
					Anio = '".substr($Periodo, 0, 4)."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					CodProveedor = '".$CodProveedor."' AND
					ObligacionTipoDocumento = '".$CodTipoDocumento."' AND
					ObligacionNroDocumento = '".$NroDocumento."'";
		$query_documentos = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));	$linea=0;
		while ($field_documentos = mysql_fetch_array($query_documentos)) {
			//	actualizo (orden)
			if ($field_documentos['ReferenciaTipoDocumento'] == "OC") {
				$sql = "UPDATE lg_ordencompra 
						SET
							MontoPendiente = (MontoPendiente - (".floatval($MontoAfecto)." + ".floatval($MontoNoAfecto)." + ".floatval($MontoImpuesto).")),
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()
						WHERE
							Anio = '".substr($Periodo, 0, 4)."' AND
							CodOrganismo = '".$CodOrganismo."' AND
							NroOrden = '".$field_documentos['ReferenciaNroDocumento']."'";
				$query_update = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));
			} else {
				$sql = "UPDATE lg_ordenservicio
						SET
							MontoGastado = (MontoGastado - (".floatval($MontoAfecto)." + ".floatval($MontoNoAfecto)." + ".floatval($MontoImpuesto).")),
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()
						WHERE
							Anio = '".substr($Periodo, 0, 4)."' AND
							CodOrganismo = '".$CodOrganismo."' AND
							NroOrden = '".$field_documentos['ReferenciaNroDocumento']."'";
				$query_update = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		
		//	actualizo obligacion
		$sql = "UPDATE ap_obligaciones
				SET
					Estado = 'AP',
					AprobadoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaAprobado = '".formatFechaAMD($FechaAprobado)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = '".$CodTipoDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto (orden de pago)
		$NroOrden = getCodigo_3("ap_ordenpago", "NroOrden", "CodOrganismo", "Anio", $CodOrganismo, $Anio, 10);
		$sql = "INSERT INTO ap_ordenpago (
							Anio,
							CodOrganismo,
							NroOrden,
							CodAplicacion,
							CodProveedor,
							CodTipoDocumento,
							NroDocumento,
							FechaDocumento,
							FechaVencimiento,
							CodProveedorPagar,
							NomProveedorPagar,
							Concepto,
							NroCuenta,
							CodTipoPago,
							FechaOrdenPago,
							MontoTotal,
							NroRegistro,
							FlagPagoDiferido,
							CodCentroCosto,
							CodSistemaFuente,
							FechaVencimientoReal,
							FechaProgramada,
							Estado,
							Periodo,
							RevisadoPor,
							AprobadoPor,
							FechaRevisado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$Anio."',
							'".$CodOrganismo."',
							'".$NroOrden."',
							'AP',
							'".$CodProveedor."',
							'".$CodTipoDocumento."',
							'".$NroDocumento."',
							'".formatFechaAMD($FechaDocumento)."',
							'".formatFechaAMD($FechaVencimiento)."',
							'".$CodProveedorPagar."',
							'".$NomProveedorPagar."',
							'".$Comentarios."',
							'".$NroCuenta."',
							'".$CodTipoPago."',
							NOW(),
							'".$MontoObligacion."',
							'".$NroRegistro."',
							'".$FlagPagoDiferido."',
							'".$CodCentroCosto."',
							'".$field_fuente['CodSistemaFuente']."',
							'".formatFechaAMD($FechaVencimiento)."',
							'".formatFechaAMD($FechaProgramada)."',
							'PE',
							'".$Periodo."',
							'".$RevisadoPor."',
							'".$_SESSION["CODPERSONA_ACTUAL"]."',
							'".formatFechaAMD($FechaRevision)."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto (orden de pago detalles)
		$sql = "SELECT *
				FROM ap_distribucionobligacion
				WHERE
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = '".$CodTipoDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_distribucion = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));	$Linea=0;
		$anho = date('Y');
		while ($field_distribucion = mysql_fetch_array($query_distribucion)) {	$Linea++;
			$sql = "INSERT INTO ap_ordenpagodistribucion (
							CodOrganismo,
							NroOrden,
							CodPresupuesto,
							CodProveedor,
							CodTipoDocumento,
							NroDocumento,
							Linea,
							CodCentroCosto,
							Monto,
							CodCuenta,
							cod_partida,
							Anio,
							Periodo,
							Origen,
							Estado,
							UltimoUsuario,
							UltimaFecha
					) VALUES (
							'".$CodOrganismo."',
							'".$NroOrden."',
							(SELECT CodPresupuesto FROM `pv_presupuesto` WHERE EjercicioPpto = '".$anho."'),
							'".$CodProveedor."',
							'".$CodTipoDocumento."',
							'".$NroDocumento."',
							'".$Linea."',
							'".$CodCentroCosto."',
							'".$field_distribucion['Monto']."',
							'".$field_distribucion['CodCuenta']."',
							'".$field_distribucion['cod_partida']."',
							'".$Anio."',
							'".$Periodo."',
							'OP',
							'PE',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
					)";
			$query_insert = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	inserto (orden de pago contabilidad)
		$sql = "(SELECT
					td.CodCuentaProv AS CodCuenta,
					oc.ReferenciaTipoDocumento AS TipoOrden,
					oc.ReferenciaNroDocumento AS NroOrden,
					pc.Descripcion AS NomCuenta,
					(oc.MontoObligacion) AS MontoVoucher,
					pc.TipoSaldo,
					'01' AS Orden,
					'Haber' AS Columna
				 FROM
					ap_obligaciones oc
					INNER JOIN ap_tipodocumento td ON (oc.CodTipoDocumento = td.CodTipoDocumento)
					INNER JOIN ac_mastplancuenta pc ON (td.CodCuentaProv = pc.CodCuenta)
				 WHERE
					oc.CodProveedor = '".$CodProveedor."' AND
					oc.CodTipoDocumento = '".$CodTipoDocumento."' AND
					oc.NroDocumento = '".$NroDocumento."'
				 GROUP BY CodCuenta)
				UNION
				(SELECT
					(SELECT CodCuenta FROM mastimpuestos WHERE CodImpuesto = '".$_PARAMETRO["IGVCODIGO"]."') AS CodCuenta,
					oc.ReferenciaTipoDocumento AS TipoOrden,
					oc.ReferenciaNroDocumento AS NroOrden,
					(SELECT pc2.Descripcion
					 FROM
						mastimpuestos i2
						INNER JOIN ac_mastplancuenta pc2 ON (i2.CodCuenta = pc2.CodCuenta)
					 WHERE CodImpuesto = '".$_PARAMETRO["IGVCODIGO"]."') AS NomCuenta,
					oc.MontoImpuesto AS MontoVoucher,
					(SELECT pc2.TipoSaldo
					 FROM
						mastimpuestos i2
						INNER JOIN ac_mastplancuenta pc2 ON (i2.CodCuenta = pc2.CodCuenta)
					 WHERE CodImpuesto = '".$_PARAMETRO["IGVCODIGO"]."') AS TipoSaldo,
					'02' AS Orden,
					'Debe' AS Columna
				 FROM ap_obligaciones oc
				 WHERE
					oc.CodProveedor = '".$CodProveedor."' AND
					oc.CodTipoDocumento = '".$CodTipoDocumento."' AND
					oc.NroDocumento = '".$NroDocumento."' AND
					oc.MontoImpuesto > 0
				 GROUP BY CodCuenta)
				UNION
				(SELECT
					oc.CodCuenta,
					o.ReferenciaTipoDocumento AS TipoOrden,
					o.ReferenciaNroDocumento AS NroOrden,
					pc.Descripcion AS NomCuenta,
					ABS(SUM(oc.MontoImpuesto)) AS MontoVoucher,
					pc.TipoSaldo,
					'03' AS Orden,
					'Haber' AS Columna
				 FROM
					ap_obligacionesimpuesto oc
					INNER JOIN ap_obligaciones o ON (oc.CodProveedor = o.CodProveedor AND
													 oc.CodTipoDocumento = o.CodTipoDocumento AND
													 oc.NroDocumento = o.NroDocumento)
					INNER JOIN ac_mastplancuenta pc ON (oc.CodCuenta = pc.CodCuenta)
				 WHERE
					oc.FlagProvision = 'N' AND
					oc.CodProveedor = '".$CodProveedor."' AND
					oc.CodTipoDocumento = '".$CodTipoDocumento."' AND
					oc.NroDocumento = '".$NroDocumento."'
				 GROUP BY oc.CodCuenta)
				UNION
				(SELECT
					oc.CodCuenta,
					oc.TipoOrden,
					oc.NroOrden,
					pc.Descripcion AS NomCuenta,
					SUM(oc.Monto) AS MontoVoucher,
					pc.TipoSaldo,
					'04' AS Orden,
					'Debe' AS Columna
				 FROM
					ap_obligacionescuenta oc
					INNER JOIN ac_mastplancuenta pc ON (oc.CodCuenta = pc.CodCuenta)
				 WHERE
					oc.CodProveedor = '".$CodProveedor."' AND
					oc.CodTipoDocumento = '".$CodTipoDocumento."' AND
					oc.NroDocumento = '".$NroDocumento."'
				 GROUP BY oc.CodCuenta)
				ORDER BY CodCuenta";
		$query_det = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));	$Secuencia=0;
		while($field_det = mysql_fetch_array($query_det)) {	$Secuencia++;
			if ($field_det['Orden'] == "01") {
				$sql = "SELECT ABS(SUM(oi1.MontoImpuesto)) AS MontoRetencion
						FROM
							ap_obligacionesimpuesto oi1
							INNER JOIN ap_obligaciones o1 ON (oi1.CodProveedor = o1.CodProveedor AND
															  oi1.CodTipoDocumento = o1.CodTipoDocumento AND
															  oi1.NroDocumento = o1.NroDocumento)
							INNER JOIN mastimpuestos i1 ON (oi1.CodImpuesto = i1.CodImpuesto)
							INNER JOIN ac_mastplancuenta pc1 ON (i1.CodCuenta = pc1.CodCuenta)
						WHERE
							oi1.FlagProvision = 'P' AND
							oi1.CodProveedor = '".$CodProveedor."' AND
							oi1.CodTipoDocumento = '".$CodTipoDocumento."' AND
							oi1.NroDocumento = '".$NroDocumento."'
						GROUP BY i1.FlagProvision, oi1.CodProveedor, oi1.CodTipoDocumento, oi1.NroDocumento";
				$query_orden1 = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));
				if (mysql_num_rows($query_orden1) != 0) $field_orden1 = mysql_fetch_array($query_orden1);
				$Monto = $field_det['MontoVoucher'] + $field_orden1['MontoRetencion'];
			} else $Monto = $field_det['MontoVoucher'];
			
			if ($field_det['Columna'] == "Haber") {
				$Monto = abs($Monto) * (-1);
				$Debitos += $Monto;
			} else {
				$style = "";
				$Monto = abs($Monto);
				$Creditos += $Monto;
			}
			$sql = "INSERT INTO ap_ordenpagocontabilidad (
								Anio,
								CodOrganismo,
								NroOrden,
								Secuencia,
								CodCuenta,
								Monto,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$Anio."',
								'".$CodOrganismo."',
								'".$NroOrden."',
								'".$Secuencia."',
								'".$field_det['CodCuenta']."',
								'".$Monto."',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		mysql_query("COMMIT");
		echo "|$NroOrden";
	}
	
	//	anular
	elseif ($accion == "anular") {
		mysql_query("BEGIN");
		if ($Estado == "PR") {
			//	ELIMINO O ANULO
			if ($_PARAMETRO["OBLIGANUL"] == "S") {
				//	partidas
				$sql = "DELETE FROM ap_distribucionobligacion
						WHERE
							CodProveedor = '".$CodProveedor."' AND
							CodTipoDocumento = '".$CodTipoDocumento."' AND
							NroDocumento = '".$NroDocumento."'";
				$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				//	impuestos
				$sql = "DELETE FROM ap_obligacionesimpuesto
						WHERE
							CodProveedor = '".$CodProveedor."' AND
							CodTipoDocumento = '".$CodTipoDocumento."' AND
							NroDocumento = '".$NroDocumento."'";
				$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				//	cuentas/partidas
				$sql = "DELETE FROM ap_obligacionescuenta
						WHERE
							CodProveedor = '".$CodProveedor."' AND
							CodTipoDocumento = '".$CodTipoDocumento."' AND
							NroDocumento = '".$NroDocumento."'";
				$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				//	obligacion
				$sql = "DELETE FROM ap_obligaciones
						WHERE
							CodProveedor = '".$CodProveedor."' AND
							CodTipoDocumento = '".$CodTipoDocumento."' AND
							NroDocumento = '".$NroDocumento."'";
				$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			} else {
				//	partidas
				$sql = "UPDATE ap_distribucionobligacion
						SET
							Estado = 'AN',
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()
						WHERE
							CodProveedor = '".$CodProveedor."' AND
							CodTipoDocumento = '".$CodTipoDocumento."' AND
							NroDocumento = '".$NroDocumento."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				if ($FlagCompromiso == "S") {
					$sql = "UPDATE lg_distribucioncompromisos
							SET
								Estado = 'AN',
								UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
								UltimaFecha = NOW()
							WHERE
								Anio = '".substr($Periodo, 0, 4)."' AND
								CodOrganismo = '".$CodOrganismo."' AND
								CodProveedor = '".$CodProveedor."' AND
								CodTipoDocumento = '".$CodTipoDocumento."' AND
								NroDocumento = '".$NroDocumento."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
				//	obligacion
				$sql = "UPDATE ap_obligaciones
						SET
							Estado = 'AN',
							MotivoAnulacion = '".$MotivoAnulacion."',
							FechaAnulacion = NOW(),
							AnuladoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()
						WHERE
							CodProveedor = '".$CodProveedor."' AND
							CodTipoDocumento = '".$CodTipoDocumento."' AND
							NroDocumento = '".$NroDocumento."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
			//	actualizo documento
			$sql = "UPDATE ap_documentos
					SET
						Estado = 'PR',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						Anio = '".substr($Periodo, 0, 4)."' AND
						CodOrganismo = '".$CodOrganismo."' AND
						CodProveedor = '".$CodProveedor."' AND
						ObligacionTipoDocumento = '".$CodTipoDocumento."' AND
						ObligacionNroDocumento = '".$NroDocumento."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		elseif ($Estado == "RV" or $Estado == "CO") {
			//	consulto causados
			$sql = "SELECT *
					FROM ap_distribucionobligacion
					WHERE
						CodProveedor = '".$CodProveedor."' AND
						CodTipoDocumento = '".$CodTipoDocumento."' AND
						NroDocumento = '".$NroDocumento."' AND
						Estado = 'CA'
					ORDER BY cod_partida";
			$query_dis = mysql_query($sql) or die ($sql.mysql_error());
			while ($field_dis = mysql_fetch_array($query_dis)) {
				if ($FlagCompromiso == "S") $comprometer = "MontoCompromiso = MontoCompromiso - ".$field_dis['Monto'].",";
				//	actualizo presupuesto
			 /*	$sql = "UPDATE pv_presupuestodet
						SET
							$comprometer
							MontoCausado = MontoCausado - ".$field_dis['Monto']."
						WHERE
							Organismo = '".$CodOrganismo."' AND
							CodPresupuesto = (SELECT CodPresupuesto FROM pv_presupuesto WHERE EjercicioPpto = '".substr($Periodo, 0, 4)."') AND
							cod_partida = '".$field_dis['cod_partida']."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));*/
			}			
			//	partidas
			$sql = "UPDATE ap_distribucionobligacion
					SET
						Estado = 'PE',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						CodProveedor = '".$CodProveedor."' AND
						CodTipoDocumento = '".$CodTipoDocumento."' AND
						NroDocumento = '".$NroDocumento."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if ($FlagCompromiso == "S") {
				$sql = "UPDATE lg_distribucioncompromisos
						SET
							Estado = 'PE',
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()
						WHERE
							Anio = '".substr($Periodo, 0, 4)."' AND
							CodOrganismo = '".$CodOrganismo."' AND
							CodProveedor = '".$CodProveedor."' AND
							CodTipoDocumento = '".$CodTipoDocumento."' AND
							NroDocumento = '".$NroDocumento."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
			//	obligacion
			$sql = "UPDATE ap_obligaciones
					SET
						Estado = 'PR',
						MotivoAnulacion = '".$MotivoAnulacion."',
						FechaAnulacion = NOW(),
						AnuladoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						CodProveedor = '".$CodProveedor."' AND
						CodTipoDocumento = '".$CodTipoDocumento."' AND
						NroDocumento = '".$NroDocumento."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		mysql_query("COMMIT");
	}
}

//	orden de pago
elseif ($modulo == "orden_pago") {
	$Concepto = changeUrl($Concepto);
	$MotivoAnulacion = changeUrl($MotivoAnulacion);
	
	//	modificar
	if ($accion == "modificar") {
		mysql_query("BEGIN");
		//	actualizo orden
		$sql = "UPDATE ap_ordenpago
				SET
					FechaOrdenPago = '".formatFechaAMD($FechaOrdenPago)."',
					CodTipoPago = '".$CodTipoPago."',
					NroCuenta = '".$NroCuenta."',
					RevisadoPor = '".$RevisadoPor."',
					AprobadoPor = '".$AprobadoPor."',
					Concepto = '".$Concepto."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	
		mysql_query("COMMIT");
	}
	
	//	pre-pago
	elseif ($accion == "prepago") {
		mysql_query("BEGIN");
		//	consulto orden
		$NroProceso = getCodigo("ap_pagos", "NroProceso", 6);
		$sql = "SELECT
					op.CodProveedor,
					op.CodTipoDocumento,
					op.NroDocumento,
					op.CodTipoPago,
					op.CodOrganismo,
					op.NroCuenta,
					op.NroOrden,
					op.Anio,
					op.NomProveedorPagar,
					op.FechaProgramada,
					op.MontoTotal,
					op.RevisadoPor,
					op.AprobadoPor,
					op.Periodo,
					o.MontoObligacion,
					o.MontoImpuestoOtros
				FROM
					ap_ordenpago op
					INNER JOIN ap_obligaciones o ON (op.CodProveedor = o.CodProveedor AND
													 op.CodTipoDocumento = o.CodTipoDocumento AND
													 op.NroDocumento = o.NroDocumento)
				WHERE
					op.Anio = '".$Anio."' AND
					op.CodOrganismo = '".$CodOrganismo."' AND
					op.NroOrden = '".$NroOrden."'";
		$query_op = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		if (mysql_num_rows($query_op) != 0) $field_op = mysql_fetch_array($query_op);
		
		//	actualizo orden de pago
		$sql = "UPDATE ap_ordenpago
				SET
					Estado = 'GE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodProveedor = '".$field_op['CodProveedor']."' AND
					CodTipoDocumento = '".$field_op['CodTipoDocumento']."' AND
					NroDocumento = '".$field_op['NroDocumento']."' AND
					Estado = 'PE'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto pago
		$sql = "INSERT INTO ap_pagos (
							NroProceso,
							Secuencia,
							CodTipoPago,
							CodOrganismo,
							NroCuenta,
							CodProveedor,
							NroOrden,
							Anio,
							NomProveedorPagar,
							MontoPago,
							MontoRetenido,
							OrigenGeneracion,
							Estado,
							EstadoEntrega,
							EstadoChequeManual,
							FlagContabilizacionPendiente,
							FlagNegociacion,
							FlagNoNegociable,
							FlagCobrado,
							FlagCertificadoImpresion,
							FlagPagoDiferido,
							Periodo,
							GeneradoPor,
							ConformadoPor,
							AprobadoPor,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$NroProceso."',
							'1',
							'".$field_op['CodTipoPago']."',
							'".$field_op['CodOrganismo']."',
							'".$field_op['NroCuenta']."',
							'".$field_op['CodProveedor']."',
							'".$field_op['NroOrden']."',
							'".$field_op['Anio']."',
							'".$field_op['NomProveedorPagar']."',
							'".$field_op['MontoTotal']."',
							'".$field_op['MontoImpuestoOtros']."',
							'A',
							'GE',
							'C',
							'',
							'S',
							'N',
							'N',
							'N',
							'N',
							'N',
							'".$field_op['Periodo']."',
							'".$_SESSION["CODPERSONA_ACTUAL"]."',
							'".$field_op['RevisadoPor']."',
							'".$field_op['AprobadoPor']."',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		mysql_query("COMMIT");
	}
	
	//	imprimir/transferir
	elseif ($accion == "transferir") {
		mysql_query("BEGIN");
		//	consulto pagos
		$NroPago = getNroOrdenPago($CodTipoPago, $NroCuenta);
//echo 		$NroPago ;exit;
		$sql = "SELECT
					p.NroProceso,
					p.Secuencia,
					p.CodOrganismo,
					p.NroOrden,
					p.NroCuenta,
					p.CodTipoPago,
					p.CodProveedor,
					p.MontoPago,
					p.Anio,
					p.Periodo,
					op.CodTipoDocumento,
					op.NroDocumento,
					op.Concepto,
					op.CodCentroCosto
				FROM
					ap_pagos p
					INNER JOIN ap_ordenpago op ON (p.Anio = op.Anio AND
												   p.CodOrganismo = op.CodOrganismo AND
												   p.NroOrden = op.NroOrden)
				WHERE
					p.NroProceso = '".$NroProceso."' AND
					p.Secuencia = '".$Secuencia."'
				ORDER BY Secuencia";
		$query_op = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		while ($field_op = mysql_fetch_array($query_op)) {
			//	consulto
			$sql = "SELECT TipoTransaccion, FlagVoucher
					FROM ap_bancotipotransaccion
					WHERE CodTipoTransaccion = '".$_PARAMETRO["TRANSPAGO"]."'";
			$query_flag = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if (mysql_num_rows($query_flag) != 0) $field_flag = mysql_fetch_array($query_flag);
			if ($field_flag['TipoTransaccion'] == "I") $signo = "1";
			elseif ($field_flag['TipoTransaccion'] == "E") $signo = "-1";
			
			//	inserto transaccion
			$NroTransaccion = getCodigo("ap_bancotransaccion", "NroTransaccion", 5);
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
								Comentarios,
								PagoNroProceso,
								PagoSecuencia,
								NroPago,
								FlagConciliacion,
								Estado,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$NroTransaccion."',
								'1',
								'".$field_op['CodOrganismo']."',
								'".$_PARAMETRO["TRANSPAGO"]."',
								'".$field_flag['TipoTransaccion']."',
								'".$field_op['NroCuenta']."',
								'".$field_op['CodTipoDocumento']."',
								'".$field_op['CodProveedor']."',
								'".$field_op['CodCentroCosto']."',
								'".$_SESSION["CODPERSONA_ACTUAL"]."',
								NOW(),
								NOW(),
								'".$field_op['Periodo']."',
								'".($field_op['MontoPago']*$signo)."',
								'".$field_op['Concepto']."',
								'".$field_op['NroProceso']."',
								'".$field_op['Secuencia']."',
								'".$NroPago."',
								'N',
								'AP',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	actualizo balance
			$sql = "UPDATE ap_ctabancariabalance
					SET
						SaldoAnterior = SaldoActual,
						SaldoActual = (SaldoActual + ".($field_op['MontoPago']*$signo)."),
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE NroCuenta = '".$field_op['NroCuenta']."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	actualizo obligacion
			$sql = "UPDATE ap_obligaciones
					SET
						FechaPago = '".formatFechaAMD($FechaPago)."',
						NroPago = '".$NroPago."',
						Estado = 'PA',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						CodProveedor = '".$field_op['CodProveedor']."' AND
						CodTipoDocumento = '".$field_op['CodTipoDocumento']."' AND
						NroDocumento = '".$field_op['NroDocumento']."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	actualizo orden de pago
			$sql = "UPDATE ap_ordenpago
					SET
						Estado = 'PA',
						NroPago = '".$NroPago."',
						FechaTransferencia = '".formatFechaAMD($FechaPago)."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						Anio = '".$field_op['Anio']."' AND
						CodOrganismo = '".$field_op['CodOrganismo']."' AND
						NroOrden = '".$field_op['NroOrden']."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	actualizo orden de pago distribucion
			$sql = "UPDATE ap_ordenpagodistribucion
					SET
						Estado = 'PA',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						Anio = '".$field_op['Anio']."' AND
						CodOrganismo = '".$field_op['CodOrganismo']."' AND
						NroOrden = '".$field_op['NroOrden']."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	consulto compromisos
			$sql = "SELECT *
					FROM ap_ordenpagodistribucion
					WHERE
						CodProveedor = '".$field_op['CodProveedor']."' AND
						CodTipoDocumento = '".$field_op['CodTipoDocumento']."' AND
						NroDocumento = '".$field_op['NroDocumento']."' AND
						Estado = 'PA'
					ORDER BY cod_partida";
			$query_dis = mysql_query($sql) or die ($sql.mysql_error());
			while ($field_dis = mysql_fetch_array($query_dis)) {
				//	actualizo presupuesto
				/*$sql = "UPDATE pv_presupuestodet
						SET MontoPagado = MontoPagado + ".$field_dis['Monto']."
						WHERE
							Organismo = '".$field_op['CodOrganismo']."' AND
							CodPresupuesto = (SELECT CodPresupuesto FROM pv_presupuesto WHERE EjercicioPpto = '".$field_op['Anio']."') AND
							cod_partida = '".$field_dis['cod_partida']."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));*/
			}
			
			//	actualizo pagos
			$sql = "UPDATE ap_pagos
					SET
						FechaPago = '".formatFechaAMD($FechaPago)."',
						NroPago = '".$NroPago."',
						Estado = 'IM',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						NroProceso = '".$NroProceso."' AND
						Secuencia = '".$Secuencia."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	actualizo ultimo numero de pago
			$sql = "UPDATE ap_ctabancariatipopago
					SET
						UltimoNumero = '".$NroPago."',	
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						NroCuenta = '".$NroCuenta."' AND
						CodTipoPago = '".$CodTipoPago."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	consulto e inserto las retenciones		
		$sql = "SELECT
					op.CodOrganismo,
					op.NroOrden,
					op.CodProveedor,
					op.CodTipoDocumento,
					op.NroDocumento,
					o.NroControl,
					o.FechaRegistro,
					o.FechaFactura,
					oi.MontoImpuesto AS MontoRetenido,
					oi.FactorPorcentaje AS Porcentaje,
					(o.MontoAfecto + o.MontoNoAfecto + o.MontoImpuesto) AS MontoFactura,
					o.MontoImpuesto,
					o.MontoAfecto,
					o.MontoNoAfecto,
					o.Periodo,
					i.CodImpuesto,
					i.TipoComprobante
				FROM
					ap_ordenpago op
					INNER JOIN ap_obligaciones o ON (op.NroPago = o.NroPago AND op.CodProveedor = o.CodProveedor)
					INNER JOIN ap_obligacionesimpuesto oi ON (o.CodProveedor = oi.CodProveedor AND
													  		  o.CodTipoDocumento = oi.CodTipoDocumento AND
													  		  o.NroDocumento = oi.NroDocumento)
					INNER JOIN mastimpuestos i ON (oi.CodImpuesto = i.CodImpuesto)
				WHERE op.NroPago = '".$NroPago."'";
//echo $sql; exit;

		$query_retenciones = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		while ($field_retenciones = mysql_fetch_array($query_retenciones)) {
			$NroComprobante = getCodigo_3("ap_retenciones", "NroComprobante", "Anio", "TipoComprobante", date("Y"), $field_retenciones['TipoComprobante'], 8);
			$sql = "INSERT INTO ap_retenciones (
								Anio,
								CodOrganismo,
								NroOrden,
								NroComprobante,
								PeriodoFiscal,
								CodImpuesto,
								FechaComprobante,
								CodProveedor,
								CodTipoDocumento,
								NroDocumento,
								NroControl,
								FechaFactura,
								MontoAfecto,
								MontoNoAfecto,
								MontoImpuesto,
								MontoFactura,
								Porcentaje,
								MontoRetenido,
								TipoComprobante,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								NOW(),
								'".$field_retenciones['CodOrganismo']."',
								'".$field_retenciones['NroOrden']."',
								'".$NroComprobante."',
								'".$field_retenciones['Periodo']."',
								'".$field_retenciones['CodImpuesto']."',
								NOW(),
								'".$field_retenciones['CodProveedor']."',
								'".$field_retenciones['CodTipoDocumento']."',
								'".$field_retenciones['NroDocumento']."',
								'".$field_retenciones['NroControl']."',
								'".$field_retenciones['FechaFactura']."',
								'".$field_retenciones['MontoAfecto']."',
								'".$field_retenciones['MontoNoAfecto']."',
								'".$field_retenciones['MontoImpuesto']."',
								'".$field_retenciones['MontoFactura']."',
								'".$field_retenciones['Porcentaje']."',
								'".$field_retenciones['MontoRetenido']."',
								'".$field_retenciones['TipoComprobante']."',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		mysql_query("COMMIT");
	}
	
	//	anular
	elseif ($accion == "anular") {
		mysql_query("BEGIN");
		//	vouchers
		if ($FlagContabilizacionPendiente == "N" && $VoucherDocumento != "") {
			//	genero nuevo voucher
			$CodVoucher = substr($VoucherDocumento, 0, 2);
			$NroVoucher = getCodigo_3("ac_vouchermast", "NroVoucher", "CodVoucher", "Periodo", $CodVoucher, substr($Ahora, 0, 7), 4);
			$NroInterno = getCodigo("ac_vouchermast", "NroInterno", 10);
			$Voucher = "$CodVoucher-$NroVoucher";
			
			//	voucher mast
			$sql = "INSERT INTO ac_vouchermast (
								CodOrganismo,
								Periodo,
								Voucher,
								Prefijo,
								NroVoucher,
								CodVoucher,
								CodDependencia,
								CodModeloVoucher,
								CodSistemaFuente,
								Creditos,
								Debitos,
								Lineas,
								PreparadoPor,
								FechaPreparacion,
								AprobadoPor,
								FechaAprobacion,
								TituloVoucher,
								ComentariosVoucher,
								FechaVoucher,
								NroInterno,
								FlagTransferencia,
								Estado,
								CodLibroCont,
								UltimoUsuario,
								UltimaFecha
					)
							SELECT
								CodOrganismo,
								NOW() AS Periodo,
								'$Voucher' AS Voucher,
								'$CodVoucher' AS Prefijo,
								'$NroVoucher' AS NroVoucher,
								'$CodVoucher' AS CodVoucher,
								CodDependencia,
								CodModeloVoucher,
								CodSistemaFuente,
								Creditos,
								Debitos,
								Lineas,
								'".$_SESSION["CODPERSONA_ACTUAL"]."' AS PreparadoPor,
								NOW() AS FechaPreparacion,
								'".$_SESSION["CODPERSONA_ACTUAL"]."' AS AprobadoPor,
								NOW() AS FechaAprobacion,
								CONCAT('$MotivoAnulacion (', TituloVoucher, ')') AS TituloVoucher,
								CONCAT('$MotivoAnulacion (', ComentariosVoucher, ')') AS ComentariosVoucher,
								NOW() AS FechaVoucher,
								NroInterno,
								FlagTransferencia,
								Estado,
								CodLibroCont,
								'".$_SESSION["USUARIO_ACTUAL"]."' AS UltimoUsuario,
								NOW() AS UltimaFecha
							FROM ac_vouchermast
							WHERE
								CodOrganismo = '".$CodOrganismo."' AND
								Periodo = '".$VoucherDocPeriodo."' AND
								Voucher = '".$VoucherDocumento."'";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	voucher detalles
			$sql = "INSERT INTO ac_voucherdet (
								CodOrganismo,
								Periodo,
								Voucher,
								Linea,
								CodCuenta,
								MontoVoucher,
								MontoPost,
								CodPersona,
								NroCheque,
								FechaVoucher,
								CodCentroCosto,
								ReferenciaTipoDocumento,
								ReferenciaNroDocumento,
								Descripcion,
								Estado,
								UltimoUsuario,
								UltimaFecha
					)
							SELECT
								CodOrganismo,
								NOW() AS Periodo,
								'$Voucher' AS Voucher,
								Linea,
								CodCuenta,
								(MontoVoucher*(-1)) AS MontoVoucher,
								(MontoPost*(-1)) AS MontoPost,
								CodPersona,
								NroCheque,
								NOW() AS FechaVoucher,
								CodCentroCosto,
								ReferenciaTipoDocumento,
								ReferenciaNroDocumento,
								CONCAT('$MotivoAnulacion (', Descripcion, ')') AS Descripcion,
								Estado,
								'".$_SESSION["USUARIO_ACTUAL"]."' AS UltimoUsuario,
								NOW() AS UltimaFecha
							FROM ac_voucherdet
							WHERE
								CodOrganismo = '".$CodOrganismo."' AND
								Periodo = '".$VoucherDocPeriodo."' AND
								Voucher = '".$VoucherDocumento."'";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	voucher balance
			$sql = "SELECT *
					FROM ac_voucherdet
					WHERE
						CodOrganismo = '".$CodOrganismo."' AND
						Periodo = '".$Periodo."' AND
						Voucher = '".$Voucher."'";
			$query_voucher = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			while ($field_voucher = mysql_fetch_array($query_voucher)) {
				$sql = "UPDATE ac_voucherbalance
						SET SaldoBalance = (SaldoBalance + (".$field_voucher['MontoVoucher']."))
						WHERE
							CodOrganismo = '".$field_voucher['CodOrganismo']."' AND
							Periodo = '".$field_voucher['Periodo']."' AND
							CodCuenta = '".$field_voucher['CodCuenta']."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
			
			//	actualizo obligacion
			$sql = "UPDATE ap_obligaciones
					SET
						VoucherAnulacion = '".$Voucher."',
						PeriodoAnulacion = NOW()
					WHERE
						CodProveedor = '".$CodProveedor."' AND
						CodTipoDocumento = '".$CodTipoDocumento."' AND
						NroDocumento = '".$NroDocumento."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	actualizo orden de pago
			$sql = "UPDATE ap_ordenpago
					SET
						VoucherDocAnulacion = '".$Voucher."',
						PeriodoDocAnulacion = NOW()
					WHERE
						Anio = '".$Anio."' AND
						CodOrganismo = '".$CodOrganismo."' AND
						NroOrden = '".$NroOrden."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		
		//	actualizo obligacion
		$sql = "UPDATE ap_obligaciones
				SET
					Estado = 'PR',
					Voucher = '',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = '".$CodTipoDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	
		//	documentos
		$sql = "SELECT *
				FROM ap_documentos
				WHERE
					Anio = '".substr($Ahora, 0, 4)."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					CodProveedor = '".$CodProveedor."' AND
					ObligacionTipoDocumento = '".$CodTipoDocumento."' AND
					ObligacionNroDocumento = '".$NroDocumento."'";
		$query_documentos = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));	$linea=0;
		while ($field_documentos = mysql_fetch_array($query_documentos)) {
			//	actualizo (orden)
			if ($field_documentos['ReferenciaTipoDocumento'] == "OC") {
				$sql = "UPDATE lg_ordencompra 
						SET
							MontoPendiente = (MontoPendiente + (".floatval($field_documentos['MontoAfecto'])." + ".floatval($field_documentos['MontoNoAfecto'])." + ".floatval($field_documentos['MontoImpuestos']).")),
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()
						WHERE
							Anio = '".substr($Ahora, 0, 4)."' AND
							CodOrganismo = '".$CodOrganismo."' AND
							NroOrden = '".$field_documentos['ReferenciaNroDocumento']."'";
				$query_update = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));
			} else {
				$sql = "UPDATE lg_ordenservicio
						SET
							MontoGastado = (MontoGastado + (".floatval($field_documentos['MontoAfecto'])." + ".floatval($field_documentos['MontoNoAfecto'])." + ".floatval($field_documentos['MontoImpuestos']).")),
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()
						WHERE
							Anio = '".substr($Ahora, 0, 4)."' AND
							CodOrganismo = '".$CodOrganismo."' AND
							NroOrden = '".$field_documentos['ReferenciaNroDocumento']."'";
				$query_update = mysql_query($sql) or die (getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		
		//	consulto causados
		$sql = "SELECT *
				FROM ap_distribucionobligacion
				WHERE
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = '".$CodTipoDocumento."' AND
					NroDocumento = '".$NroDocumento."' AND
					Estado = 'CA'
				ORDER BY cod_partida";
		$query_dis = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_dis = mysql_fetch_array($query_dis)) {
			if ($FlagCompromiso == "S") $comprometer = "MontoCompromiso = MontoCompromiso - ".$field_dis['Monto'].",";
			//	actualizo presupuesto
			/* $sql = "UPDATE pv_presupuestodet
					SET
						$comprometer
						MontoCausado = MontoCausado - ".$field_dis['Monto']."
					WHERE
						Organismo = '".$CodOrganismo."' AND
						CodPresupuesto = (SELECT CodPresupuesto FROM pv_presupuesto WHERE EjercicioPpto = '".$Anio."') AND
						cod_partida = '".$field_dis['cod_partida']."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));*/
		}
		
		//	actualizo obligacion distribucion
		$sql = "UPDATE ap_distribucionobligacion
				SET
					Estado = 'PE',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					CodProveedor = '".$CodProveedor."' AND
					CodTipoDocumento = '".$CodTipoDocumento."' AND
					NroDocumento = '".$NroDocumento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo obligacion distribucion
		$sql = "UPDATE ap_ordenpagodistribucion
				SET
					Estado = 'AN',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	actualizo orden de pago
		$sql = "UPDATE ap_ordenpago
				SET
					Estado = 'AN',
					MotivoAnulacion = '".$MotivoAnulacion."',
					AnuladoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaAnulacion = NOW(),					
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	elimino el pago
		$sql = "DELETE FROM ap_pagos
				WHERE
					Anio = '".$Anio."' AND
					CodOrganismo = '".$CodOrganismo."' AND
					NroOrden = '".$NroOrden."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		echo "|$CodOrganismo.".substr($Ahora, 0, 7).".$Voucher";
		mysql_query("COMMIT");
	}
}

//	pagos (modificacion restringida)
elseif ($modulo == "pago") {
	$MotivoAnulacion = changeUrl($MotivoAnulacion);
	
	//	modificar
	if ($accion == "modificar") {
		list($Anio, $Mes, $Dia) = split("[/.-]", substr($Ahora, 0, 10));
		list($d, $m, $a) = split("[/.-]", $FechaPago);
		if ($m != $Mes) die("La fecha de pago ingresada pertenece a un periodo distinto a lo contabilizado");
		
		//	actualizo orden
		$sql = "UPDATE ap_pagos
				SET
					GeneradoPor = '".$GeneradoPor."',
					ConformadoPor = '".$ConformadoPor."',
					AprobadoPor = '".$AprobadoPor."',
					FechaPago = '".formatFechaAMD($FechaPago)."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE
					NroProceso = '".$NroProceso."' AND
					Secuencia = '".$Secuencia."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
	//	anular
	elseif ($accion == "anular") {
		mysql_query("BEGIN");
		//-------------------
		//	consulto pago
		$sql = "SELECT
					p.NroProceso,
					p.Secuencia,
					p.CodOrganismo,
					p.NroOrden,
					p.NroCuenta,
					p.CodTipoPago,
					p.CodProveedor,
					p.MontoPago,
					p.Anio,
					op.CodTipoDocumento,
					op.NroDocumento,
					op.Concepto,
					op.CodCentroCosto
				FROM
					ap_pagos p
					INNER JOIN ap_ordenpago op ON (p.Anio = op.Anio AND
												   p.CodOrganismo = op.CodOrganismo AND
												   p.NroOrden = op.NroOrden)
				WHERE
					p.NroProceso = '".$NroProceso."' AND
					p.Secuencia = '".$Secuencia."'
				ORDER BY Secuencia";
		$query_op = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		while ($field_op = mysql_fetch_array($query_op)) {
			//	consulto
			$sql = "SELECT TipoTransaccion, FlagVoucher
					FROM ap_bancotipotransaccion
					WHERE CodTipoTransaccion = '".$_PARAMETRO["TRANSANUL"]."'";
			$query_flag = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			if (mysql_num_rows($query_flag) != 0) $field_flag = mysql_fetch_array($query_flag);
			if ($field_flag['TipoTransaccion'] == "I") $signo = "1";
			elseif ($field_flag['TipoTransaccion'] == "E") $signo = "-1";
			
			//	vouchers
			if ($FlagContabilizacionPendiente == "N") {
				//	genero nuevo voucher
				$NroVoucher = getCodigo_3("ac_vouchermast", "NroVoucher", "CodVoucher", "Periodo", $CodVoucher, substr($Ahora, 0, 7), 4);
				$NroInterno = getCodigo("ac_vouchermast", "NroInterno", 10);
				$Voucher = "$CodVoucher-$NroVoucher";
				
				//	voucher mast
				$sql = "INSERT INTO ac_vouchermast (
									CodOrganismo,
									Periodo,
									Voucher,
									Prefijo,
									NroVoucher,
									CodVoucher,
									CodDependencia,
									CodModeloVoucher,
									CodSistemaFuente,
									Creditos,
									Debitos,
									Lineas,
									PreparadoPor,
									FechaPreparacion,
									AprobadoPor,
									FechaAprobacion,
									TituloVoucher,
									ComentariosVoucher,
									FechaVoucher,
									NroInterno,
									FlagTransferencia,
									Estado,
									CodLibroCont,
									UltimoUsuario,
									UltimaFecha
						)
								SELECT
									CodOrganismo,
									NOW() AS Periodo,
									'$Voucher' AS Voucher,
									'$CodVoucher' AS Prefijo,
									'$NroVoucher' AS NroVoucher,
									'$CodVoucher' AS CodVoucher,
									CodDependencia,
									CodModeloVoucher,
									CodSistemaFuente,
									Creditos,
									Debitos,
									Lineas,
									'".$_SESSION["CODPERSONA_ACTUAL"]."' AS PreparadoPor,
									NOW() AS FechaPreparacion,
									'".$_SESSION["CODPERSONA_ACTUAL"]."' AS AprobadoPor,
									NOW() AS FechaAprobacion,
									CONCAT('$MotivoAnulacion (', TituloVoucher, ')') AS TituloVoucher,
									CONCAT('$MotivoAnulacion (', ComentariosVoucher, ')') AS ComentariosVoucher,
									NOW() AS FechaVoucher,
									NroInterno,
									FlagTransferencia,
									Estado,
									CodLibroCont,
									'".$_SESSION["USUARIO_ACTUAL"]."' AS UltimoUsuario,
									NOW() AS UltimaFecha
								FROM ac_vouchermast
								WHERE
									CodOrganismo = '".$CodOrganismo."' AND
									Periodo = '".$Periodo."' AND
									Voucher = '".$VoucherPago."'";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				
				//	voucher detalles
				$sql = "INSERT INTO ac_voucherdet (
									CodOrganismo,
									Periodo,
									Voucher,
									Linea,
									CodCuenta,
									MontoVoucher,
									MontoPost,
									CodPersona,
									NroCheque,
									FechaVoucher,
									CodCentroCosto,
									ReferenciaTipoDocumento,
									ReferenciaNroDocumento,
									Descripcion,
									Estado,
									UltimoUsuario,
									UltimaFecha
						)
								SELECT
									CodOrganismo,
									NOW() AS Periodo,
									'$Voucher' AS Voucher,
									Linea,
									CodCuenta,
									(MontoVoucher*(-1)) AS MontoVoucher,
									(MontoPost*(-1)) AS MontoPost,
									CodPersona,
									NroCheque,
									NOW() AS FechaVoucher,
									CodCentroCosto,
									ReferenciaTipoDocumento,
									ReferenciaNroDocumento,
									CONCAT('$MotivoAnulacion (', Descripcion, ')') AS Descripcion,
									Estado,
									'".$_SESSION["USUARIO_ACTUAL"]."' AS UltimoUsuario,
									NOW() AS UltimaFecha
								FROM ac_voucherdet
								WHERE
									CodOrganismo = '".$CodOrganismo."' AND
									Periodo = '".$Periodo."' AND
									Voucher = '".$VoucherPago."'";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				
				//	voucher balance
				$sql = "SELECT *
						FROM ac_voucherdet
						WHERE
							CodOrganismo = '".$CodOrganismo."' AND
							Periodo = '".$Periodo."' AND
							Voucher = '".$Voucher."'";
				$query_voucher = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				while ($field_voucher = mysql_fetch_array($query_voucher)) {
					$sql = "UPDATE ac_voucherbalance
							SET SaldoBalance = (SaldoBalance + (".$field_voucher['MontoVoucher']."))
							WHERE
								CodOrganismo = '".$field_voucher['CodOrganismo']."' AND
								Periodo = '".$field_voucher['Periodo']."' AND
								CodCuenta = '".$field_voucher['CodCuenta']."'";
					$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
				}
			}
			
			//	inserto transaccion
			$NroTransaccion = getCodigo("ap_bancotransaccion", "NroTransaccion", 5);
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
								Comentarios,
								PagoNroProceso,
								PagoSecuencia,
								NroPago,
								FlagConciliacion,
								Estado,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$NroTransaccion."',
								'1',
								'".$field_op['CodOrganismo']."',
								'".$_PARAMETRO["TRANSANUL"]."',
								'".$field_flag['TipoTransaccion']."',
								'".$field_op['NroCuenta']."',
								'".$field_op['CodTipoDocumento']."',
								'".$field_op['CodProveedor']."',
								'".$field_op['CodCentroCosto']."',
								'".$_SESSION["CODPERSONA_ACTUAL"]."',
								NOW(),
								NOW(),
								NOW(),
								'".($field_op['MontoPago']*$signo)."',
								'".$field_op['Concepto']."',
								'".$field_op['NroProceso']."',
								'".$field_op['Secuencia']."',
								'".$NroPago."',
								'N',
								'AP',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	actualizo balance
			$sql = "UPDATE ap_ctabancariabalance
					SET
						SaldoAnterior = SaldoActual,
						SaldoActual = (SaldoActual + ".($field_op['MontoPago']*$signo)."),
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE NroCuenta = '".$field_op['NroCuenta']."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	actualizo obligacion
			$sql = "UPDATE ap_obligaciones
					SET
						Estado = 'AP',
						NroPago = '',
						NroProceso = '',
						ProcesoSecuencia = '',
						FechaPago = '',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						CodProveedor = '".$field_op['CodProveedor']."' AND
						CodTipoDocumento = '".$field_op['CodTipoDocumento']."' AND
						NroDocumento = '".$field_op['NroDocumento']."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	actualizo orden de pago
			$sql = "UPDATE ap_ordenpago
					SET
						Estado = 'PE',
						NroPago = '',
						Voucher = '',
						VoucherPagoAnulacion = '".$Voucher."',
						PeriodoPagoAnulacion = NOW(),
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						Anio = '".$field_op['Anio']."' AND
						CodOrganismo = '".$field_op['CodOrganismo']."' AND
						NroOrden = '".$field_op['NroOrden']."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	consulto compromisos
			$sql = "SELECT *
					FROM ap_ordenpagodistribucion
					WHERE
						CodProveedor = '".$field_op['CodProveedor']."' AND
						CodTipoDocumento = '".$field_op['CodTipoDocumento']."' AND
						NroDocumento = '".$field_op['NroDocumento']."' AND
						Estado = 'PA'
					ORDER BY cod_partida";
			$query_dis = mysql_query($sql) or die ($sql.mysql_error());
			while ($field_dis = mysql_fetch_array($query_dis)) {
				//	actualizo presupuesto
				/*$sql = "UPDATE pv_presupuestodet
						SET MontoPagado = MontoPagado - ".$field_dis['Monto']."
						WHERE
							Organismo = '".$field_op['CodOrganismo']."' AND
							CodPresupuesto = (SELECT CodPresupuesto FROM pv_presupuesto WHERE EjercicioPpto = '".$field_op['Anio']."') AND
							cod_partida = '".$field_dis['cod_partida']."'";
				$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));*/
			}
			
			//	actualizo orden de pago distribucion
			$sql = "UPDATE ap_ordenpagodistribucion
					SET
						Estado = 'PE',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						Anio = '".$field_op['Anio']."' AND
						CodOrganismo = '".$field_op['CodOrganismo']."' AND
						NroOrden = '".$field_op['NroOrden']."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			
			//	actualizo pagos
			$sql = "UPDATE ap_pagos
					SET
						FechaAnulacion = NOW(),
						MotivoAnulacion = '".$MotivoAnulacion."',
						VoucherAnulacion = '".$Voucher."',
						PeriodoAnulacion = NOW(),
						AnuladoPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
						Estado = 'AN',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						NroProceso = '".$NroProceso."' AND
						Secuencia = '".$Secuencia."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
			//	anulo retenciones
			$sql = "UPDATE ap_retenciones
					SET
						Estado = 'AN',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						Anio = '".$Anio."' AND
						NroOrden = '".$NroOrden."'";
			$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		}
		echo "|$CodOrganismo.".substr($Ahora, 0, 7).".$Voucher";
		//-------------------
		mysql_query("COMMIT");
	}
}

//	
elseif ($modulo == "registro_compra") {
	//	importar
	if ($accion == "importar") {
		$nrocp = 0;
		$nrocf = 0;
		//	eliminar los registros del periodo actual
		$sql = "DELETE FROM ap_registrocompras
				WHERE
					Periodo = '".$Periodo."' AND
					(SistemaFuente = 'CP' OR SistemaFuente = 'CC')";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		
		//	inserto las obligaciones
		if ($FlagCP == "S") {
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
						o.Periodo = '".$Periodo."' AND
						o.CodOrganismo = '".$CodOrganismo."' AND
						(o.Estado = 'AP' OR o.Estado = 'PA') AND
						td.FlagFiscal = 'S'";
			$query_obligaciones = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
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
				$query_impuestos = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
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
									'".$Periodo."',
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
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		
		//	inserto caja chica
		if ($FlagCC == "S") {
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
						o.Periodo = '".$Periodo."' AND
						o.CodOrganismo = '".$CodOrganismo."' AND
						ccd.CodRegimenFiscal = 'I' AND
						(o.Estado = 'AP' OR o.Estado = 'PA')";
			$query_cajachica = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
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
				$query_impuestos = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
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
									'".$Periodo."',
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
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
		echo "|$nrocp|$nrocf";
	}
	
	//	eliminar
	elseif ($accion == "eliminar") {
		list($Periodo, $SistemaFuente, $Secuencia) = split("[.]", $registro);
		//	eliminar
		$sql = "DELETE FROM ap_registrocompras
				WHERE
					Periodo = '".$Periodo."' AND
					SistemaFuente = '".$SistemaFuente."' AND
					Secuencia = '".$Secuencia."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
	
}

//	conciliacion bancaria
elseif ($modulo == "conciliacion-bancaria") {
	//	nuevo
	if ($accion == "actualizar") {
		//	impuestos
		if ($registro != "") {
			$linea = split(";char:tr;", $registro);
			foreach ($linea as $transaccion) {
				list($NroTransaccion, $Secuencia) = split("[.]", $transaccion);
				//	actualizo
				$sql = "UPDATE ap_bancotransaccion
						SET 
							FlagConciliacion = 'S',
							FechaConciliacion = NOW(),
							UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
							UltimaFecha = NOW()
						WHERE
							NroTransaccion = '".$NroTransaccion."' AND
							Secuencia = '".$Secuencia."'";
				$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
			}
		}
	}
}

//	tipos de documentos ctas. x pagar
elseif ($modulo == "tipo_documento_cxp") {
	//	nuevo
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	-----------------
		//	inserto
		$sql = "INSERT INTO ap_tipodocumento
				SET
					CodTipoDocumento = '".$CodTipoDocumento."',
					Descripcion = '".changeUrl($Descripcion)."',
					Clasificacion = '".$Clasificacion."',
					CodRegimenFiscal = '".$CodRegimenFiscal."',
					CodVoucher = '".$CodVoucher."',
					FlagProvision = '".$FlagProvision."',
					CodCuentaProv = '".$CodCuentaProv."',
					FlagAdelanto = '".$FlagAdelanto."',
					CodCuentaAde = '".$CodCuentaAde."',
					FlagFiscal = '".$FlagFiscal."',
					CodFiscal = '".$CodFiscal."',
					FlagAutoNomina = '".$FlagAutoNomina."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	modificar registro
	elseif ($accion == "modificar") {
		mysql_query("BEGIN");
		//	-----------------
		//	actualizar
		$sql = "UPDATE ap_tipodocumento
				SET
					Descripcion = '".changeUrl($Descripcion)."',
					Clasificacion = '".$Clasificacion."',
					CodRegimenFiscal = '".$CodRegimenFiscal."',
					CodVoucher = '".$CodVoucher."',
					FlagProvision = '".$FlagProvision."',
					CodCuentaProv = '".$CodCuentaProv."',
					FlagAdelanto = '".$FlagAdelanto."',
					CodCuentaAde = '".$CodCuentaAde."',
					FlagFiscal = '".$FlagFiscal."',
					CodFiscal = '".$CodFiscal."',
					FlagAutoNomina = '".$FlagAutoNomina."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodTipoDocumento = '".$CodTipoDocumento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	eliminar registro
	elseif ($accion == "eliminar") {
		//	elimino
		$sql = "DELETE FROM ap_tipodocumento WHERE CodTipoDocumento = '".$registro."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}

//	impuestos
elseif ($modulo == "impuestos") {
	//	nuevo
	if ($accion == "nuevo") {
		mysql_query("BEGIN");
		//	-----------------
		//	inserto
		$sql = "INSERT INTO mastimpuestos
				SET
					CodImpuesto = '".$CodImpuesto."',
					Descripcion = '".changeUrl($Descripcion)."',
					CodRegimenFiscal = '".$CodRegimenFiscal."',
					Signo = '".$Signo."',
					CodCuenta = '".$CodCuenta."',
					CodCuentaPub20 = '".$CodCuentaPub20."',
					FactorPorcentaje = '".setNumero($FactorPorcentaje)."',
					FlagProvision = '".$FlagProvision."',
					FlagImponible = '".$FlagImponible."',
					TipoComprobante = '".$TipoComprobante."',
					FlagGeneral = '".$FlagGeneral."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()";
		$query_insert = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	modificar registro
	elseif ($accion == "modificar") {
		mysql_query("BEGIN");
		//	-----------------
		//	actualizar
		$sql = "UPDATE ap_tipodocumento
				SET
					Descripcion = '".changeUrl($Descripcion)."',
					Clasificacion = '".$Clasificacion."',
					CodRegimenFiscal = '".$CodRegimenFiscal."',
					CodVoucher = '".$CodVoucher."',
					FlagProvision = '".$FlagProvision."',
					CodCuentaProv = '".$CodCuentaProv."',
					FlagAdelanto = '".$FlagAdelanto."',
					CodCuentaAde = '".$CodCuentaAde."',
					FlagFiscal = '".$FlagFiscal."',
					CodFiscal = '".$CodFiscal."',
					FlagAutoNomina = '".$FlagAutoNomina."',
					Estado = '".$Estado."',
					UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
					UltimaFecha = NOW()
				WHERE CodTipoDocumento = '".$CodTipoDocumento."'";
		$query_update = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
		//	-----------------
		mysql_query("COMMIT");
	}
	
	//	eliminar registro
	elseif ($accion == "eliminar") {
		//	elimino
		$sql = "DELETE FROM ap_tipodocumento WHERE CodTipoDocumento = '".$registro."'";
		$query_delete = mysql_query($sql) or die(getErrorSql(mysql_errno(), mysql_error(), $sql));
	}
}
?>
