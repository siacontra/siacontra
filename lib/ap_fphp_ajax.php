<?php
include("fphp.php");
include("ap_fphp.php");
///////////////////////////////////////////////////////////////////////////////
//	PARA AJAX
///////////////////////////////////////////////////////////////////////////////
//	control de cierres mensuales
if ($modulo == "generar_vouchers") {
	//	generar vouchers de provision
	if ($accion == "generar_vouchers_provision") {
		//	sistema fuente
		$sql = "SELECT CodSistemaFuente FROM mastaplicaciones WHERE CodAplicacion = 'AP'";
		$query_aplicacion = mysql_query($sql) or die($sql.mysql_error());
		if (mysql_num_rows($query_aplicacion) != "") $field_aplicacion = mysql_fetch_array($query_aplicacion);
		$codsistemafuente = $field_aplicacion['CodSistemaFuente'];
		
		//	obligaciones
		$linea = split(";", $obligaciones);
		foreach ($linea as $registro) {
			list($organismo, $codproveedor, $tdoc, $nrofactura, $codvoucher) = split('[.]', $registro);
			$nrovoucher = getCodigo_2("ac_vouchermast", "NroVoucher", "CodVoucher", $codvoucher, 4);
			$nrointerno = getCodigo("ac_vouchermast", "NroInterno", 10);
			$codigo = "$codvoucher-$nrovoucher";
			$lineas = 0;
			$creditos = 0;
			$debitos = 0;
			
			//	obligacion
			$sql = "SELECT Comentarios, ComentariosAdicional
					FROM ap_obligaciones
					WHERE
						CodOrganismo = '".$organismo."' AND
						CodProveedor = '".$codproveedor."' AND
						CodTipoDocumento = '".$tdoc."' AND
						NroDocumento = '".$nrofactura."'";
			$query_obligacion = mysql_query($sql) or die($sql.mysql_error());
			if (mysql_num_rows($query_obligacion) != "") $field_obligacion = mysql_fetch_array($query_obligacion);
			$descripcion = $field_obligacion['Comentarios'];
			$comentarios = $field_obligacion['ComentariosAdicional'];
			
			//	inserto el voucher
			$sql = "INSERT INTO ac_vouchermast (
								CodOrganismo,
								Periodo,
								Voucher,
								Prefijo,
								NroVoucher,
								CodVoucher,
								CodSistemaFuente,
								PreparadoPor,
								FechaPreparacion,
								AprobadoPor,
								FechaAprobacion,
								TituloVoucher,
								ComentariosVoucher,
								FechaVoucher,
								NroInterno,
								Estado,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$organismo."',
								'".date("Y-m")."',
								'".$codigo."',
								'".$codvoucher."',
								'".$nrovoucher."',
								'".$codvoucher."',
								'".$codsistemafuente."',
								'".$_SESSION['CODPERSONA_ACTUAL']."',
								NOW(),
								'".$_SESSION['CODPERSONA_ACTUAL']."',
								NOW(),
								'".($descripcion)."',
								'".($comentarios)."',
								NOW(),
								'".$nrointerno."',
								'MA',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			
			//	detalles del voucher
			$sql = "(SELECT
						td.CodCuentaProv AS CodCuenta,
						oc.ReferenciaTipoDocumento AS TipoOrden,
						oc.ReferenciaNroDocumento AS NroOrden,
						pc.Descripcion AS NomCuenta,
						oc.MontoObligacion AS MontoVoucher,
						pc.TipoSaldo,
						'01' AS Orden,
						'Haber' AS Columna
					 FROM
						ap_obligaciones oc
						INNER JOIN ap_tipodocumento td ON (oc.CodTipoDocumento = td.CodTipoDocumento)
						INNER JOIN ac_mastplancuenta pc ON (td.CodCuentaProv = pc.CodCuenta)
					 WHERE
						oc.CodProveedor = '".$codproveedor."' AND
						oc.CodTipoDocumento = '".$tdoc."' AND
						oc.NroDocumento = '".$nrofactura."'
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
						(SELECT FlagProvision FROM mastimpuestos WHERE CodImpuesto = '".$_PARAMETRO["IGVCODIGO"]."') = 'N' AND
						oc.CodProveedor = '".$codproveedor."' AND
						oc.CodTipoDocumento = '".$tdoc."' AND
						oc.NroDocumento = '".$nrofactura."' AND
						oc.MontoImpuesto > 0
					 GROUP BY CodCuenta)
					
					UNION
					
					(SELECT
						i.CodCuenta,
						o.ReferenciaTipoDocumento AS TipoOrden,
						o.ReferenciaNroDocumento AS NroOrden,
						pc.Descripcion AS NomCuenta,
						SUM(oc.MontoImpuesto) AS MontoVoucher,
						pc.TipoSaldo,
						'03' AS Orden,
						'Haber' AS Columna
					 FROM
						ap_obligacionesimpuesto oc
						INNER JOIN ap_obligaciones o ON (oc.CodProveedor = o.CodProveedor AND
														 oc.CodTipoDocumento = o.CodTipoDocumento AND
														 oc.NroDocumento = o.NroDocumento)
						INNER JOIN mastimpuestos i ON (oc.CodImpuesto = i.CodImpuesto)
						INNER JOIN ac_mastplancuenta pc ON (i.CodCuenta = pc.CodCuenta)
					 WHERE
						i.FlagProvision = 'N' AND
						oc.CodProveedor = '".$codproveedor."' AND
						oc.CodTipoDocumento = '".$tdoc."' AND
						oc.NroDocumento = '".$nrofactura."'
					 GROUP BY i.CodCuenta)
					
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
						oc.CodProveedor = '".$codproveedor."' AND
						oc.CodTipoDocumento = '".$tdoc."' AND
						oc.NroDocumento = '".$nrofactura."'
					 GROUP BY oc.CodCuenta)
					
					ORDER BY CodCuenta";
			$query_voucher_det = mysql_query($sql) or die ($sql.mysql_error());	$lineas=0;
			while($field_voucher_det = mysql_fetch_array($query_voucher_det)) {	$lineas++;
				if ($field_voucher_det['Columna'] == "Haber") {
					$monto = -$field_voucher_det['MontoVoucher'];
					$debitos += $monto;
				} else {
					$monto = $field_voucher_det['MontoVoucher'];
					$creditos += $monto;
				}
				$codcuenta = $field_voucher_det['CodCuenta'];
				$ccosto = $field_voucher_det['CodCentroCosto'];
				$comentarios = $field_voucher_det['NomCuenta'];
				$tiposaldo = $field_voucher_det['TipoSaldo'];
				$torden = $field_voucher_det['TipoOrden'];
				$norden = $field_voucher_det['NroOrden'];
				
				//	inserto los detalles del voucher
				$sql = "INSERT INTO ac_voucherdet (
									CodOrganismo,
									Periodo,
									Voucher,
									Linea,
									CodCuenta,
									MontoVoucher,
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
									'".$organismo."',
									NOW(),
									'".$codigo."',
									'".$lineas."',
									'".$codcuenta."',
									'".$monto."',
									'".$codproveedor."',
									NOW(),
									'".$ccosto."',
									'".$torden."',
									'".$norden."',
									'".($comentarios)."',
									'MA',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
				
				//	consulto si existe la cuenta en balance
				$sql = "SELECT *
						FROM ac_voucherbalance
						WHERE CodCuenta = '".$codcuenta."'";
				$query_balance = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_balance) != 0) {
					$field_balance = mysql_fetch_array($query_balance);
					//	actualizo balance
					$sql = "UPDATE ac_voucherbalance
							SET
								SaldoBalance = (SaldoBalance + $monto),
								UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
								UltimaFecha = NOW()
							WHERE CodCuenta = '".$codcuenta."'";
					$query_update = mysql_query($sql) or die ($sql.mysql_error());
				} else {
					//	inserto balance
					$sql = "INSERT INTO ac_voucherbalance (
										CodOrganismo,
										Periodo,
										CodCuenta,
										SaldoBalance,
										UltimoUsuario,
										UltimaFecha
							) VALUES (
										'".$organismo."',
										'".date("Y-m")."',
										'".$codcuenta."',
										'".$monto."',
										'".$_SESSION["USUARIO_ACTUAL"]."',
										NOW()
							)";
					$query_insert = mysql_query($sql) or die ($sql.mysql_error());
				}
			}
			
			//	actualizo los montos del voucher
			$sql = "UPDATE ac_vouchermast
					SET
						Lineas = '".$lineas."',
						Creditos = '".$creditos."',
						Debitos = '".$debitos."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						CodOrganismo = '".$organismo."' AND
						Periodo = '".date("Y-m")."' AND
						Voucher = '".$codigo."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
			
			//	actualizo obligacion
			$sql = "UPDATE ap_obligaciones
					SET
						FlagContabilizacionPendiente = 'N',
						Voucher = '".$codigo."',
						Periodo = '".date("Y-m")."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						CodProveedor = '".$codproveedor."' AND
						CodTipoDocumento = '".$tdoc."' AND
						NroDocumento = '".$nrofactura."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
			
			//	actualizo orden de pago
			$sql = "UPDATE ap_ordenpago
					SET
						Voucher = '".$codigo."',
						Periodo = NOW(),
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						CodProveedor = '".$codproveedor."' AND
						CodTipoDocumento = '".$tdoc."' AND
						NroDocumento = '".$nrofactura."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	
	//	generar vouchers de pagos
	elseif ($accion == "generar_vouchers_pagos") {
		//	tipo de voucher para la aplicacion
		$sql = "SELECT PrefVoucherPA FROM mastaplicaciones WHERE CodAplicacion = 'AP'";
		$query_prefpa = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_prefpa) != 0) {
			$field_prefpa = mysql_fetch_array($query_prefpa);
			$codvoucher = $field_prefpa['PrefVoucherPA'];
		}
		
		//	pagos
		$linea = split(";", $pagos);
		foreach ($linea as $registro) {
			list($nroproceso, $secuencia) = split('[.]', $registro);
			$nrovoucher = getCodigo_2("ac_vouchermast", "NroVoucher", "CodVoucher", $codvoucher, 4);
			$nrointerno = getCodigo("ac_vouchermast", "NroInterno", 10);
			$codigo = "$codvoucher-$nrovoucher";
			$lineas = 0;
			$creditos = 0;
			$debitos = 0;
			
			//	consulto el titulo y los comentarios de la obligacion
			$sql = "SELECT Comentarios, ComentariosAdicional
					FROM ap_obligaciones
					WHERE
						NroProceso = '".$nroproceso."' AND
						ProcesoSecuencia = '".$secuencia."'";
			$query_obligacion = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_obligacion) != 0) $field_obligacion = mysql_fetch_array($query_obligacion);
			
			//	inserto el voucher
			$sql = "INSERT INTO ac_vouchermast (
								CodOrganismo,
								Periodo,
								Voucher,
								Prefijo,
								NroVoucher,
								CodVoucher,
								CodSistemaFuente,
								PreparadoPor,
								FechaPreparacion,
								AprobadoPor,
								FechaAprobacion,
								TituloVoucher,
								ComentariosVoucher,
								FechaVoucher,
								NroInterno,
								Estado,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$organismo."',
								NOW(),
								'".$codigo."',
								'".$codvoucher."',
								'".$nrovoucher."',
								'".$codvoucher."',
								'".$sistema_fuente."',
								'".$_SESSION['CODPERSONA_ACTUAL']."',
								NOW(),
								'".$_SESSION['CODPERSONA_ACTUAL']."',
								NOW(),
								'".$field_obligacion['Comentarios']."',
								'".$field_obligacion['ComentariosAdicional']."',
								NOW(),
								'".$nrointerno."',
								'MA',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			
			//	detalles del voucher
			$sql = "SELECT
						p.CodProveedor,
						p.MontoPago,
						p.NroOrden,
						op.Concepto,
						op.CodCentroCosto,
						op.CodTipoDocumento,
						op.NroDocumento,
						o.CodCuenta AS CodCuentaPago,
						o.Comentarios,
						pc1.Descripcion AS NomCuentaPago,
						pc1.TipoSaldo AS TipoSaldoCuentaPago,
						cb.CodCuenta AS CodCuentaBanco,
						cb.CodBanco,
						pc2.Descripcion AS NomCuentaBanco,
						pc2.TipoSaldo AS TipoSaldoCuentaBanco,
						td.CodVoucher,
						td.Descripcion AS NomCuenta,
						b.Banco
					FROM
						ap_pagos p
						INNER JOIN ap_ordenpago op ON (p.CodOrganismo = op.CodOrganismo AND p.NroOrden = op.NroOrden)
						INNER JOIN ap_tipodocumento td ON (op.CodTipoDocumento = td.CodTipoDocumento)
						INNER JOIN ap_ctabancaria cb ON (p.NroCuenta = cb.NroCuenta)
						INNER JOIN mastbancos b ON (cb.CodBanco = b.CodBanco)
						INNER JOIN ap_obligaciones o ON (op.CodProveedor = o.CodProveedor AND
														 op.CodTipoDocumento = o.CodTipoDocumento AND
														 op.NroDocumento = o.NroDocumento)
						LEFT JOIN ac_mastplancuenta pc1 ON (o.CodCuenta = pc1.CodCuenta)
						LEFT JOIN ac_mastplancuenta pc2 ON (cb.CodCuenta = pc2.CodCuenta)
					WHERE
						p.NroProceso = '".$nroproceso."' AND
						p.Secuencia = '".$secuencia."'";
			$query_voucher_det = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_voucher_det) != 0) $field_voucher_det = mysql_fetch_array($query_voucher_det);
			
			//	voucher detalle (banco)
			$monto_voucher = -$field_voucher_det['MontoPago'];
			$haber += $monto_voucher;
			$codcuenta[$lineas] = $field_voucher_det['CodCuentaBanco'];
			$monto[$lineas] = $monto_voucher;
			$ccosto[$lineas] = $field_voucher_det['CodCentroCosto'];
			$comentarios[$lineas] = $field_voucher_det['NomCuentaBanco'];
			$tsaldo[$lineas] = $field_voucher_det['TipoSaldoCuentaBanco'];
			$proveedor[$lineas] = $field_voucher_det['CodBanco'];
			
			if ($field_voucher_det['CodCuentaPago'] != "") {
				$lineas++;
				//	voucher detalle (gasto)
				$monto_voucher = $field_voucher_det['MontoPago'];
				$debe += $monto_voucher;
				$codcuenta[$lineas] = $field_voucher_det['CodCuentaPago'];
				$monto[$lineas] = $monto_voucher;
				$ccosto[$lineas] = $field_voucher_det['CodCentroCosto'];
				$comentarios[$lineas] = $field_voucher_det['NomCuentaPago'];
				$tsaldo[$lineas] = $field_voucher_det['TipoSaldoCuentaPago'];
				$proveedor[$lineas] = $field_voucher_det['CodProveedor'];
			} else {
				$sql = "(SELECT
							(SELECT CodCuenta FROM mastimpuestos WHERE CodImpuesto = '".getParametro("IGVCODIGO")."') AS CodCuenta,
							(SELECT pc2.Descripcion
							 FROM
								mastimpuestos i2
								INNER JOIN ac_mastplancuenta pc2 ON (i2.CodCuenta = pc2.CodCuenta)
							 WHERE CodImpuesto = '".getParametro("IGVCODIGO")."') AS NomCuenta,
							oc.MontoImpuesto AS MontoVoucher,
							(SELECT pc2.TipoSaldo
							 FROM
								mastimpuestos i2
								INNER JOIN ac_mastplancuenta pc2 ON (i2.CodCuenta = pc2.CodCuenta)
							 WHERE CodImpuesto = '".getParametro("IGVCODIGO")."') AS TipoSaldo,
							'02' AS Orden,
							'Debe' AS Columna
						 FROM ap_obligaciones oc
						 WHERE
							(SELECT FlagProvision FROM mastimpuestos WHERE CodImpuesto = '".getParametro("IGVCODIGO")."') = 'N' AND
							oc.CodProveedor = '".$field_voucher_det['CodProveedor']."' AND
							oc.CodTipoDocumento = '".$field_voucher_det['CodTipoDocumento']."' AND
							oc.NroDocumento = '".$field_voucher_det['NroDocumento']."' AND
							oc.MontoImpuesto > 0
						 GROUP BY CodCuenta)						
						UNION						
						(SELECT
							i.CodCuenta,
							pc.Descripcion AS NomCuenta,
							SUM(oc.MontoImpuesto) AS MontoVoucher,
							pc.TipoSaldo,
							'03' AS Orden,
							'Haber' AS Columna
						 FROM
							ap_obligacionesimpuesto oc
							INNER JOIN ap_obligaciones o ON (oc.CodProveedor = o.CodProveedor AND
															 oc.CodTipoDocumento = o.CodTipoDocumento AND
															 oc.NroDocumento = o.NroDocumento)
							INNER JOIN mastimpuestos i ON (oc.CodImpuesto = i.CodImpuesto)
							INNER JOIN ac_mastplancuenta pc ON (i.CodCuenta = pc.CodCuenta)
						 WHERE
							i.FlagProvision = 'P' AND
							oc.CodProveedor = '".$field_voucher_det['CodProveedor']."' AND
							oc.CodTipoDocumento = '".$field_voucher_det['CodTipoDocumento']."' AND
							oc.NroDocumento = '".$field_voucher_det['NroDocumento']."'
						 GROUP BY i.CodCuenta)						
						UNION						
						(SELECT
							oc.CodCuenta,
							pc.Descripcion AS NomCuenta,
							SUM(oc.Monto) AS MontoVoucher,
							pc.TipoSaldo,
							'04' AS Orden,
							'Debe' AS Columna
						 FROM
							ap_obligacionescuenta oc
							INNER JOIN ac_mastplancuenta pc ON (oc.CodCuenta = pc.CodCuenta)
						 WHERE
							oc.CodProveedor = '".$field_voucher_det['CodProveedor']."' AND
							oc.CodTipoDocumento = '".$field_voucher_det['CodTipoDocumento']."' AND
							oc.NroDocumento = '".$field_voucher_det['NroDocumento']."'
						 GROUP BY oc.CodCuenta)
						ORDER BY CodCuenta";
				$query_voucher = mysql_query($sql) or die ($sql.mysql_error());
				while($field_voucher = mysql_fetch_array($query_voucher)) {
					$lineas++;
					if ($field_voucher['Columna'] == "Haber") {
						$monto = -$field_voucher['MontoVoucher'];
						$haber += $monto;
					} else {
						$style = "";
						$monto = $field_voucher['MontoVoucher'];
						$debe += $monto;
					}
					$codcuenta[$lineas] = $field_voucher['CodCuenta'];
					$monto[$lineas] = $monto;
					$ccosto[$lineas] = $field_voucher['CodCentroCosto'];
					$comentarios[$lineas] = $field_voucher['NomCuenta'];
					$tsaldo[$lineas] = $field_voucher['TipoSaldo'];
					$proveedor[$lineas] = $field_voucher_det['CodProveedor'];
				}
			}
			
			//	inserto detalles
			for ($i=0; $i<=$lineas;$i++) {
				$j = $i + 1;
				//	inserto los detalles del voucher
				$sql = "INSERT INTO ac_voucherdet (
									CodOrganismo,
									Periodo,
									Voucher,
									Linea,
									CodCuenta,
									MontoVoucher,
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
									'".$organismo."',
									NOW(),
									'".$codigo."',
									'".$j."',
									'".$codcuenta[$i]."',
									'".$monto[$i]."',
									'".$proveedor[$i]."',
									NOW(),
									'".$ccosto[$i]."',
									'OP',
									'".$field_voucher_det['NroOrden']."',
									'".($comentarios[$i])."',
									'MA',
									'".$_SESSION["USUARIO_ACTUAL"]."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
				
				//	consulto si existe la cuenta en balance
				$sql = "SELECT * FROM ac_voucherbalance WHERE CodCuenta = '".$codcuenta[$i]."'";
				$query_balance = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_balance) != 0) {
					$field_balance = mysql_fetch_array($query_balance);
					//	actualizo balance
					$sql = "UPDATE ac_voucherbalance
							SET
								SaldoBalance = (SaldoBalance + ".$monto[$i]."),
								UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
								UltimaFecha = NOW()
							WHERE CodCuenta = '".$codcuenta[$i]."'";
					$query_update = mysql_query($sql) or die ($sql.mysql_error());
				} else {
					//	inserto balance
					$sql = "INSERT INTO ac_voucherbalance (
										CodOrganismo,
										Periodo,
										CodCuenta,
										SaldoBalance,
										UltimoUsuario,
										UltimaFecha
							) VALUES (
										'".$organismo."',
										NOW(),
										'".$codcuenta[$i]."',
										'".$monto[$i]."',
										'".$_SESSION["USUARIO_ACTUAL"]."',
										NOW()
							)";
					$query_insert = mysql_query($sql) or die ($sql.mysql_error());
				}
			}
			
			//	actualizo los montos del voucher
			$sql = "UPDATE ac_vouchermast
					SET
						Lineas = '".$lineas."',
						Creditos = '".$creditos."',
						Debitos = '".$debitos."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						CodOrganismo = '".$organismo."' AND
						Periodo = SUBSTRING(NOW(), 1, 7) AND
						Voucher = '".$codigo."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
		
			//	actualizo orden de pago
			$sql = "UPDATE ap_pagos
					SET
						FlagContabilizacionPendiente = 'N',
						VoucherPago = '".$codigo."',
						Periodo = NOW()
					WHERE NroProceso = '".$nroproceso."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	
	//	generar vouchers de transacciones
	elseif ($accion == "generar_vouchers_transacciones") {
		//	sistema fuete
		$sql = "SELECT CodSistemaFuente FROM mastaplicaciones WHERE CodAplicacion = 'AP'";
		$query_prefpa = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_prefpa) != 0) {
			$field_prefpa = mysql_fetch_array($query_prefpa);
			$sistema_fuente = $field_prefpa['CodSistemaFuente'];
		}
		
		//	transacciones
		$linea = split(";", $transacciones);
		foreach ($linea as $registro) {
			list($nrotransaccion, $secuencia, $codvoucher) = split('[.]', $registro);
			$nrovoucher = getCodigo_2("ac_vouchermast", "NroVoucher", "CodVoucher", $codvoucher, 4);
			$nrointerno = getCodigo("ac_vouchermast", "NroInterno", 10);
			$codigo = "$codvoucher-$nrovoucher";
			$lineas = 0;
			$creditos = 0;
			$debitos = 0;
			
			//	consulto el titulo y los comentarios
			$sql = "SELECT Comentarios
					FROM ap_bancotransaccion
					WHERE
						NroTransaccion = '".$nrotransaccion."' AND
						Secuencia = '".$secuencia."'
					GROUP BY NroTransaccion";
			$query_transaccion = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_transaccion) != 0) $field_transaccion = mysql_fetch_array($query_transaccion);
			
			//	inserto el voucher
			$sql = "INSERT INTO ac_vouchermast (
								CodOrganismo,
								Periodo,
								Voucher,
								Prefijo,
								NroVoucher,
								CodVoucher,
								CodSistemaFuente,
								PreparadoPor,
								FechaPreparacion,
								AprobadoPor,
								FechaAprobacion,
								TituloVoucher,
								ComentariosVoucher,
								FechaVoucher,
								NroInterno,
								Estado,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$organismo."',
								NOW(),
								'".$codigo."',
								'".$codvoucher."',
								'".$nrovoucher."',
								'".$codvoucher."',
								'".$sistema_fuente."',
								'".$_SESSION['CODPERSONA_ACTUAL']."',
								NOW(),
								'".$_SESSION['CODPERSONA_ACTUAL']."',
								NOW(),
								'".$field_transaccion['Comentarios']."',
								'".$field_transaccion['Comentarios']."',
								NOW(),
								'".$nrointerno."',
								'MA',
								'".$_SESSION["USUARIO_ACTUAL"]."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			
			//	detalles del voucher
			$j = 0;
			$sql = "SELECT
						bt.*,
						btt.CodCuenta AS CodCuentaTransaccion,
						btt.TipoTransaccion,
						cb.CodCuenta AS CodCuentaBanco
					FROM
						ap_bancotransaccion bt
						INNER JOIN ap_bancotipotransaccion btt ON (bt.CodTipoTransaccion = btt.CodTipoTransaccion)
						INNER JOIN ap_ctabancaria cb ON (bt.NroCuenta = cb.NroCuenta)
					WHERE
						bt.NroTransaccion = '".$nrotransaccion."' AND
						bt.Secuencia = '".$secuencia."'
					ORDER BY Secuencia";
			$query_transaccion = mysql_query($sql) or die ($sql.mysql_error());
			while ($field_transaccion = mysql_fetch_array($query_transaccion)) {
				if ($field_transaccion['TipoTransaccion'] == "T") {
					$lineas++;
					$j++;
					//	inserto los detalles del voucher
					$sql = "INSERT INTO ac_voucherdet (
										CodOrganismo,
										Periodo,
										Voucher,
										Linea,
										CodCuenta,
										MontoVoucher,
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
										'".$organismo."',
										NOW(),
										'".$codigo."',
										'".$j."',
										'".$field_transaccion['CodCuentaBanco']."',
										'".$field_transaccion['Monto']."',
										'".$field_transaccion['CodProveedor']."',
										NOW(),
										'".$field_transaccion['CodCentroCosto']."',
										'".$field_transaccion['CodTipoDocumento']."',
										'".$field_transaccion['NroTransaccion']."-".$field_transaccion['Secuencia']."',
										'".$field_transaccion['CodCentroCosto']."',
										'MA',
										'".$_SESSION["USUARIO_ACTUAL"]."',
										NOW()
							)";
					$query_insert = mysql_query($sql) or die ($sql.mysql_error());
					
					//	consulto si existe la cuenta en balance
					$sql = "SELECT * FROM ac_voucherbalance WHERE CodCuenta = '".$field_transaccion['CodCuentaBanco']."'";
					$query_balance = mysql_query($sql) or die ($sql.mysql_error());
					if (mysql_num_rows($query_balance) != 0) {
						$field_balance = mysql_fetch_array($query_balance);
						//	actualizo balance
						$sql = "UPDATE ac_voucherbalance
								SET
									SaldoBalance = (SaldoBalance + ".$field_transaccion['Monto']."),
									UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
									UltimaFecha = NOW()
								WHERE CodCuenta = '".$field_transaccion['CodCuentaBanco']."'";
						$query_update = mysql_query($sql) or die ($sql.mysql_error());
					} else {
						//	inserto balance
						$sql = "INSERT INTO ac_voucherbalance (
											CodOrganismo,
											Periodo,
											CodCuenta,
											SaldoBalance,
											UltimoUsuario,
											UltimaFecha
								) VALUES (
											'".$organismo."',
											NOW(),
											'".$field_transaccion['CodCuentaBanco']."',
											'".$field_transaccion['Monto']."',
											'".$_SESSION["USUARIO_ACTUAL"]."',
											NOW()
								)";
						$query_insert = mysql_query($sql) or die ($sql.mysql_error());
					}
				} else {
					if ($field_transaccion['TipoTransaccion'] == "I") {
						$debe = $field_transaccion['Monto'];
						$haber = -$field_transaccion['Monto'];
						$codcuentadebe = $field_transaccion['CodCuentaBanco'];
						$codcuentahaber = $field_transaccion['CodCuentaTransaccion'];
					}
					elseif ($field_transaccion['TipoTransaccion'] == "E") {
						$debe = $field_transaccion['Monto'];
						$haber = -$field_transaccion['Monto'];
						$codcuentadebe = $field_transaccion['CodCuentaTransaccion'];
						$codcuentahaber = $field_transaccion['CodCuentaBanco'];
					}
					$debitos += $haber;
					$creditos  += $debe;
					$lineas++;
					//	----------------	debe
					$j++;
					//	inserto los detalles del voucher
					$sql = "INSERT INTO ac_voucherdet (
										CodOrganismo,
										Periodo,
										Voucher,
										Linea,
										CodCuenta,
										MontoVoucher,
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
										'".$organismo."',
										NOW(),
										'".$codigo."',
										'".$j."',
										'".$codcuentadebe."',
										'".$debe."',
										'".$field_transaccion['CodProveedor']."',
										NOW(),
										'".$field_transaccion['CodCentroCosto']."',
										'".$field_transaccion['CodTipoDocumento']."',
										'".$field_transaccion['NroTransaccion']."-".$field_transaccion['Secuencia']."',
										'".$field_transaccion['CodCentroCosto']."',
										'MA',
										'".$_SESSION["USUARIO_ACTUAL"]."',
										NOW()
							)";
					$query_insert = mysql_query($sql) or die ($sql.mysql_error());
					
					//	consulto si existe la cuenta en balance
					$sql = "SELECT * FROM ac_voucherbalance WHERE CodCuenta = '".$field_transaccion['CodCuentaBanco']."'";
					$query_balance = mysql_query($sql) or die ($sql.mysql_error());
					if (mysql_num_rows($query_balance) != 0) {
						$field_balance = mysql_fetch_array($query_balance);
						//	actualizo balance
						$sql = "UPDATE ac_voucherbalance
								SET
									SaldoBalance = (SaldoBalance + ".$debe."),
									UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
									UltimaFecha = NOW()
								WHERE CodCuenta = '".$codcuentadebe."'";
						$query_update = mysql_query($sql) or die ($sql.mysql_error());
					} else {
						//	inserto balance
						$sql = "INSERT INTO ac_voucherbalance (
											CodOrganismo,
											Periodo,
											CodCuenta,
											SaldoBalance,
											UltimoUsuario,
											UltimaFecha
								) VALUES (
											'".$organismo."',
											NOW(),
											'".$codcuentadebe."',
											'".$debe."',
											'".$_SESSION["USUARIO_ACTUAL"]."',
											NOW()
								)";
						$query_insert = mysql_query($sql) or die ($sql.mysql_error());
					}
					//	----------------	haber
					$j++;
					//	inserto los detalles del voucher
					$sql = "INSERT INTO ac_voucherdet (
										CodOrganismo,
										Periodo,
										Voucher,
										Linea,
										CodCuenta,
										MontoVoucher,
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
										'".$organismo."',
										NOW(),
										'".$codigo."',
										'".$j."',
										'".$codcuentahaber."',
										'".$haber."',
										'".$field_transaccion['CodProveedor']."',
										NOW(),
										'".$field_transaccion['CodCentroCosto']."',
										'".$field_transaccion['CodTipoDocumento']."',
										'".$field_transaccion['NroTransaccion']."-".$field_transaccion['Secuencia']."',
										'".$field_transaccion['CodCentroCosto']."',
										'MA',
										'".$_SESSION["USUARIO_ACTUAL"]."',
										NOW()
							)";
					$query_insert = mysql_query($sql) or die ($sql.mysql_error());
					
					//	consulto si existe la cuenta en balance
					$sql = "SELECT * FROM ac_voucherbalance WHERE CodCuenta = '".$field_transaccion['CodCuentaTransaccion']."'";
					$query_balance = mysql_query($sql) or die ($sql.mysql_error());
					if (mysql_num_rows($query_balance) != 0) {
						$field_balance = mysql_fetch_array($query_balance);
						//	actualizo balance
						$sql = "UPDATE ac_voucherbalance
								SET
									SaldoBalance = (SaldoBalance + ".$haber."),
									UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
									UltimaFecha = NOW()
								WHERE CodCuenta = '".$codcuentahaber."'";
						$query_update = mysql_query($sql) or die ($sql.mysql_error());
					} else {
						//	inserto balance
						$sql = "INSERT INTO ac_voucherbalance (
											CodOrganismo,
											Periodo,
											CodCuenta,
											SaldoBalance,
											UltimoUsuario,
											UltimaFecha
								) VALUES (
											'".$organismo."',
											NOW(),
											'".$codcuentahaber."',
											'".$haber."',
											'".$_SESSION["USUARIO_ACTUAL"]."',
											NOW()
								)";
						$query_insert = mysql_query($sql) or die ($sql.mysql_error());
					}
				}
			}
			
			//	actualizo los montos del voucher
			$sql = "UPDATE ac_vouchermast
					SET
						Lineas = '".$lineas."',
						Creditos = '".$creditos."',
						Debitos = '".$debitos."',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						CodOrganismo = '".$organismo."' AND
						Periodo = SUBSTRING(NOW(), 1, 7) AND
						Voucher = '".$codigo."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
		
			//	actualizo transaccion banco
			$sql = "UPDATE ap_bancotransaccion
					SET
						Voucher = '".$codigo."',
						VoucherPeriodo = NOW(),
						Estado = 'CO'
					WHERE
						NroTransaccion = '".$nrotransaccion."' AND
						Secuencia = '".$secuencia."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
}
?>