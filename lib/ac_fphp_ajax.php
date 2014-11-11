<?php
include("fphp.php");
include("ac_fphp.php");
///////////////////////////////////////////////////////////////////////////////
//	PARA AJAX
///////////////////////////////////////////////////////////////////////////////
//	control de cierres mensuales
if ($modulo == "control_cierres_mensuales") {
	//	agregar
	if ($accion == "agregar") {
		//	consulto el estado...
		$sql = "SELECT Estado
				FROM ac_controlcierremensual
				WHERE
					CodOrganismo = '".$forganismo."' AND
					TipoRegistro = '".$ftipo_registro."' AND
					Periodo = '".$periodo."'";
		$query_consulta = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_consulta) != 0) {
			$field_consulta = mysql_fetch_array($query_consulta);
			if ($field_consulta['Estado'] == "A")
				die("¡ERROR: Ya existe un periodo abierto!");
			elseif ($field_consulta['Estado'] == "C")
				die("¡ERROR: El periodo se encuentra cerrado!");
		}
		
		//	inserto
		$sql = "INSERT INTO ac_controlcierremensual (
							CodOrganismo,
							TipoRegistro,
							Periodo,
							CodLibroCont,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$forganismo."',
							'".$ftipo_registro."',
							'".$periodo."',
							'".$flibro_contable."',
							'A',
							'".$_SESSION["USUARIO_ACTUAL"]."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	abrir
	elseif ($accion == "abrir") {
		//	consulto por errores...
		$registros = split(";", $seleccion);
		foreach ($registros as $registro) {
			//	consulto el estado...
			$sql = "SELECT Estado
					FROM ac_controlcierremensual
					WHERE
						CodOrganismo = '".$forganismo."' AND
						TipoRegistro = '".$ftipo_registro."' AND
						Periodo = '".$registro."'";
			$query_consulta = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_consulta) != 0) {
				$field_consulta = mysql_fetch_array($query_consulta);
				if ($field_consulta['Estado'] == "A") die("¡ERROR: El periodo $registro ya se encuentra abierto!");
			}
		}
		
		//	actualizo cada registro...
		$registros = split(";", $seleccion);
		foreach ($registros as $registro) {
			//	actualizo
			$sql = "UPDATE ac_controlcierremensual
					SET
						Estado = 'A',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						CodOrganismo = '".$forganismo."' AND
						TipoRegistro = '".$ftipo_registro."' AND
						Periodo = '".$registro."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	
	//	cerrar
	elseif ($accion == "cerrar") {
		//	consulto por errores...
		$registros = split(";", $seleccion);
		foreach ($registros as $registro) {
			//	consulto el estado...
			$sql = "SELECT Estado
					FROM ac_controlcierremensual
					WHERE
						CodOrganismo = '".$forganismo."' AND
						TipoRegistro = '".$ftipo_registro."' AND
						Periodo = '".$registro."'";
			$query_consulta = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_consulta) != 0) {
				$field_consulta = mysql_fetch_array($query_consulta);
				if ($field_consulta['Estado'] == "C") die("¡ERROR: El periodo $registro ya se encuentra cerrado!");
			}
		}
		
		//	actualizo cada registro...
		$registros = split(";", $seleccion);
		foreach ($registros as $registro) {
			//	actualizo
			$sql = "UPDATE ac_controlcierremensual
					SET
						Estado = 'C',
						UltimoUsuario = '".$_SESSION["USUARIO_ACTUAL"]."',
						UltimaFecha = NOW()
					WHERE
						CodOrganismo = '".$forganismo."' AND
						TipoRegistro = '".$ftipo_registro."' AND
						Periodo = '".$registro."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
}

//	voucher
else if ($modulo == "voucher") {
	//	nuevo
	if ($accion == "nuevo") {
		$nrovoucher = getCodigo_2("ac_vouchermast", "NroVoucher", "CodVoucher", $codvoucher, 4);
		$nrointerno = getCodigo("ac_vouchermast", "NroInterno", 10);
		$voucher = "$codvoucher-$nrovoucher";
		$sistema_fuente = "MANUAL";
		
		//	inserto
		$sql = "INSERT INTO ac_vouchermast (
							CodOrganismo,
							Periodo,
							Voucher,
							Prefijo,
							NroVoucher,
							CodVoucher,
							CodDependencia,
							CodSistemaFuente,
							PreparadoPor,
							FechaPreparacion,
							TituloVoucher,
							ComentariosVoucher,
							FechaVoucher,
							NroInterno,
							CodLibroCont,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$organismo."',
							'".$periodo."',
							'".$voucher."',
							'".$codvoucher."',
							'".$nrovoucher."',
							'".$codvoucher."',
							'".$dependencia."',
							'".$sistema_fuente."',
							'".$codingresado."',
							'".date("Y-m-d")."',
							'".($descripcion)."',
							'".($descripcion)."',
							'".formatFechaAMD($fecha)."',
							'".$nrointerno."',
							'".$libro_contable."',
							'".$_SESSION['USUARIO_ACTUAL']."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die($sql.mysql_error());
		
		//	detalles
		$creditos = 0;
		$debitos = 0;
		$i = 0;
		$detalle = split(";", $detalles);
		foreach ($detalle as $linea) {	$i++;
			list($_codcuenta, $_codpersona, $_documento, $_fecha, $_monto, $_codccosto, $_descripcion) = split("[|]", $linea);
			if ($_monto < 0) $creditos += $_monto;
			else $debitos += $_monto;
			
			//	inserto
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
								ReferenciaNroDocumento,
								Descripcion,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$organismo."',
								'".$periodo."',
								'".$voucher."',
								'".$i."',
								'".$_codcuenta."',
								'".$_monto."',
								'".$_codpersona."',
								'".formatFechaAMD($_fecha)."',
								'".$_codccosto."',
								'".$_documento."',
								'".($_descripcion)."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die($sql.mysql_error());
		}
		
		//	actualizo
		$sql = "UPDATE ac_vouchermast
				SET
					Creditos = '".$creditos."',
					Debitos = '".$debitos."',
					Lineas = '".$i."'
				WHERE
					CodOrganismo = '".$organismo."' AND
					Periodo = '".$periodo."' AND
					Voucher = '".$voucher."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
	}
	
	//	modificar
	elseif ($accion == "modificar") {
		$voucher = "$codvoucher-$nrovoucher";		
		//	elimino todas las lineas anteriores a la modificacion
		$sql = "DELETE FROM ac_voucherdet
				WHERE
					CodOrganismo = '".$organismo."' AND
					Periodo = '".$periodo."' AND
					Voucher = '".$voucher."'";
		$query_delete = mysql_query($sql) or die($sql.mysql_error());
		
		//	detalles
		$creditos = 0;
		$debitos = 0;
		$i = 0;
		$detalle = split(";", $detalles);
		foreach ($detalle as $linea) {	$i++;
			list($_codcuenta, $_codpersona, $_documento, $_fecha, $_monto, $_codccosto, $_descripcion) = split("[|]", $linea);
			if ($_monto < 0) $creditos += $_monto;
			else $debitos += $_monto;
			
			//	inserto
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
								ReferenciaNroDocumento,
								Descripcion,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$organismo."',
								'".$periodo."',
								'".$voucher."',
								'".$i."',
								'".$_codcuenta."',
								'".$_monto."',
								'".$_codpersona."',
								'".formatFechaAMD($_fecha)."',
								'".$_codccosto."',
								'".$_documento."',
								'".($_descripcion)."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die($sql.mysql_error());
		}
		
		//	actualizo
		$sql = "UPDATE ac_vouchermast
				SET
					CodDependencia = '".$dependencia."',
					CodSistemaFuente = '".$sistema_fuente."',
					TituloVoucher = '".($descripcion)."',
					ComentariosVoucher = '".($descripcion)."',
					CodLibroCont = '".$libro_contable."',
					Creditos = '".$creditos."',
					Debitos = '".$debitos."',
					Lineas = '".$i."',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$organismo."' AND
					Periodo = '".$periodo."' AND
					Voucher = '".$voucher."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
	}
	
	//	borrar
	elseif ($accion == "eliminar") {
		list($organismo, $periodo, $voucher) = split("[ ]", $registro);
		
		//	consulto el estado del voucher
		$sql = "SELECT Estado
				FROM ac_vouchermast
				WHERE
					CodOrganismo = '".$organismo."' AND
					Periodo = '".$periodo."' AND
					Voucher = '".$voucher."'";
		$query_voucher = mysql_query($sql) or die($sql.mysql_error());		
		if (mysql_num_rows($query_voucher) != 0) {
			$field_voucher = mysql_fetch_array($query_voucher);
			if ($field_voucher['Estado'] == "AP") die("¡ERROR: No se puede borrar un registro en estado 'Aprobado'!");
			elseif ($field_voucher['Estado'] == "MA") die("¡ERROR: No se puede borrar un registro en estado 'Mayorizado'!");
		} else die("¡ERROR: No se encontró el registro!");
		
		//	consulto el balance de las cuentas del voucher
		$sql = "SELECT *
				FROM ac_voucherdet
				WHERE
					CodOrganismo = '".$organismo."' AND
					Periodo = '".$periodo."' AND
					Voucher = '".$voucher."'";
		$query_balance = mysql_query($sql) or die($sql.mysql_error());
		while ($field_balance = mysql_fetch_array($query_balance)) {
			//	quito los valores del balance de la cuenta
			$sql = "UPDATE ac_voucherbalance 
					SET SaldoBalance = (SaldoBalance - $field_balance[MontoVoucher])
					WHERE CodCuenta = '".$field_balance['CodCuenta']."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
		}
		
		//	elimino las lineas del voucher
		$sql = "DELETE FROM ac_voucherdet
				WHERE
					CodOrganismo = '".$organismo."' AND
					Periodo = '".$periodo."' AND
					Voucher = '".$voucher."'";
		$query_delete = mysql_query($sql) or die($sql.mysql_error());
		
		//	elimino el voucher
		$sql = "DELETE FROM ac_vouchermast
				WHERE
					CodOrganismo = '".$organismo."' AND
					Periodo = '".$periodo."' AND
					Voucher = '".$voucher."'";
		$query_delete = mysql_query($sql) or die($sql.mysql_error());
	}
	
	//	anular
	elseif ($accion == "anular") {
		$voucher = "$codvoucher-$nrovoucher";
		
		if ($estado == "AB") $nuevo_estado = "AN";
		elseif ($estado == "AP") $nuevo_estado = "AB";
		elseif ($estado == "RE") $nuevo_estado = "AB";
		elseif ($estado == "MA") $nuevo_estado = "AP";
		elseif ($estado == "AN") die("¡ERROR: No puede anular un voucher en estado 'Anulado'!");
		
		//	si esta mayorizado
		if ($estado == "MA") {
			$detalle = split(";", $detalles);
			foreach ($detalle as $linea) {	$i++;
				list($_codcuenta, $_codpersona, $_documento, $_fecha, $_monto, $_codccosto, $_descripcion) = split("[|]", $linea);				
				
				//	actualizo balance
				$sql = "UPDATE ac_voucherbalance
						SET
							SaldoBalance = (SaldoBalance - ".floatval($_monto)."),
							UltimoUsuario = '".$_SESSION['_USUARIO_ACTUAL']."',
							UltimaFecha = NOW()
						WHERE
							CodOrganismo = '".$organismo."' AND
							Periodo = '".$periodo."' AND
							CodCuenta = '".$_codcuenta."'";
				$query_update = mysql_query($sql) or die($sql.mysql_error());
			}
				
			//	actualizo monto post del detalle
			$sql = "UPDATE ac_voucherdet
					SET
						MontoPost = 0.00,
						UltimoUsuario = '".$_SESSION['_USUARIO_ACTUAL']."',
						UltimaFecha = NOW()
					WHERE
						CodOrganismo = '".$organismo."' AND
						Periodo = '".$periodo."' AND
						Voucher = '".$voucher."'";
			$query_update = mysql_query($sql) or die($sql.mysql_error());
			
			$estado = "MA";
		}
		
		
		
		
		//Selecciono los datos del voucher 
		$sql = "SELECT * FROM ac_vouchermast
				
				WHERE
					CodOrganismo = '".$organismo."' AND
					Periodo = '".$periodo."' AND
					Voucher = '".$voucher."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
		
			//Obtengo los registros del voucher para incrementar el codigo voucher nuevo y evitar duplicidad de id
		
		$reversql=mysql_fetch_array($query_update);
		
		$sql1 = "SELECT MAX(NroVoucher) FROM ac_vouchermast
				
				WHERE
					Prefijo= '".$reversql['Prefijo']."' AND
					CodOrganismo = '".$organismo."' AND
					Periodo = '".date("Y-m")."' 
					";
		$query_update2 = mysql_query($sql1) or die($sql1.mysql_error());
		
		$reversql2=mysql_fetch_array($query_update2);
		
		//incremento el numero del voucher
		$codiv=$reversql2['0']+1;
		
		$nroVoucher="0$codiv";
		
		$prefijo=$reversql['Prefijo'];
		
		$voucherf="$prefijo-$nroVoucher";
		 $Motivo = trim($_POST["Motivo"]);
		
	 
		$_SESSION["comentariosv"]=$voucherf;
		$_SESSION["pcomentariosv"]= date("Y-m");		
		
		
		//	inserto los datos del voucher con nuevo registro pero menteniendo sus datos
		$sql3 = "INSERT INTO ac_vouchermast (
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
							UltimaFecha,
							CodContabilidad,
							FechaAnulacion,
							AnuladoPor,
							PeriodoAnulacion,
							MotivoAnulacion
				) VALUES (
							'".$organismo."',
							'".date("Y-m")."',
							'".$voucherf."',
							'".$prefijo."',
							'".$nroVoucher."',
							'".$prefijo."',
							'".$reversql['CodDependencia']."',
							'".$reversql['CodSistemaFuente']."',
							'".$reversql['Debitos']."',
							'".$reversql['Creditos']."',
							'".$reversql['Lineas']."',
							'".$codingresado."',
							'".date("Y-m-d")."',
							'".$codingresado."',
							'".date("Y-m-d")."',
							'".$reversql['TituloVoucher']."',
							'".$reversql['ComentariosVoucher']."',
							'".date("Y-m-d")."',
							'".$reversql['NroInterno']."',
							'".$reversql['FlagTransferencia']."',
									'AP',
							'".$reversql['CodLibroCont']."',
							'".$_SESSION['USUARIO_ACTUAL']."',
						 			NOW(),
									'',
									NOW(),
							'".$codingresado."',
							'".date("Y-m")."',
							'". $_POST['Motivo']."'
				)";
		$query_insert2 = mysql_query($sql3) or die($sql3.mysql_error());
		
		// Registrar los voucher det 		
		
				//	detalles
		
		$sql4 = "SELECT * FROM ac_voucherdet
				
				WHERE
					
					CodOrganismo = '".$reversql['CodOrganismo']."' AND
					Periodo = '".$reversql['Periodo']."'  AND
					Voucher = '".$reversql['Voucher']."'
					";
					
		$query_update4 = mysql_query($sql4) or die($sql4.mysql_error());
		
		
		
		while ($field4 = mysql_fetch_array($query_update4)) {
		
		
			$montovoucher=(-1*($field4['MontoVoucher']));
			if($field4['MontoPost']<'0'){
				$montovoucherPOT=(-1*($field4['MontoPost']));
				} 
			elseif($field4['MontoPost']>'0'){ 
			$montovoucherPOT=(-1*($field4['MontoPost']));
			}
			else{ 
			 $montovoucherPOT=$field4['MontoPost'];
			  }
		//	inserto los datos del voucher con nuevo registro pero menteniendo sus datos
		$sql5 = "INSERT INTO ac_voucherdet (
							CodOrganismo,
							Periodo,
							Voucher,
							Linea,
							CodContabilidad,
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
									
									) VALUES (
							'".$organismo."',
							'".date("Y-m")."',
							'".$voucherf."',
							'".$field4['Linea']."',
							'".$field4['CodContabilidad']."',
							'".$field4['CodCuenta']."',
							'".$montovoucher."',
							'".$montovoucherPOT."',
							'".$field4['CodPersona']."',
							'".$field4['NroCheque']."',
							'".date("Y-m-d")."',
							'".$field4['CodCentroCosto']."',
							'".$field4['ReferenciaTipoDocumento']."',
							'".$field4['ReferenciaNroDocumento']."',
							'".$field4['Descripcion']."',
							'".$field4['Estado']."',
							'".$_SESSION['_USUARIO_ACTUAL']."',
							NOW()
										)";
		
		
		$query_insert5 = mysql_query($sql5) or die($sql5.mysql_error());
		}
		
		
		
		
	
	
	}
	
	//	aprobar
	elseif ($accion == "aprobar") {
		$voucher = "$codvoucher-$nrovoucher";
		
		//	verifico si puedo mayorizar automaticamente
		if ($_PARAMETRO['VOUMAYPOST'] == "S") {
			$detalle = split(";", $detalles);
			foreach ($detalle as $linea) {	$i++;
				list($_codcuenta, $_codpersona, $_documento, $_fecha, $_monto, $_codccosto, $_descripcion) = split("[|]", $linea);				
				
				//	consulto si existe la cuenta en balance
				$sql = "SELECT *
						FROM ac_voucherbalance
						WHERE
							CodOrganismo = '".$organismo."' AND
							Periodo = '".$periodo."' AND
							CodCuenta = '".$_codcuenta."'";
				$query_bal = mysql_query($sql) or die($sql.mysql_error());
				//	si la cuenta tiene moviviento para el periodo actualizo caso contrario inserto
				if ($field_det['CodCuentaBalance'] == "") {
					//	inserto balance
					$sql = "INSERT INTO ac_voucherbalance (
										CodOrganismo,
										Periodo,
										CodCuenta,
										SaldoInicial,
										SaldoBalance,
										UltimoUsuario,
										UltimaFecha
							) VALUES (
										'".$organismo."',
										'".$periodo."',
										'".$_codcuenta."',
										'".$_monto."',
										'".$_monto."',
										'".$_SESSION['USUARIO_ACTUAL']."',
										NOW()
							)";
					$query_insert = mysql_query($sql) or die($sql.mysql_error());
				} else {
					//	actualizo balance
					$sql = "UPDATE ac_voucherbalance
							SET
								SaldoBalance = (SaldoBalance + ".floatval($_monto)."),
								UltimoUsuario = '".$_SESSION['_USUARIO_ACTUAL']."',
								UltimaFecha = NOW()
							WHERE
								CodOrganismo = '".$organismo."' AND
								Periodo = '".$periodo."' AND
								CodCuenta = '".$_codcuenta."'";
					$query_update = mysql_query($sql) or die($sql.mysql_error());
				}
			}
				
			//	actualizo monto post del detalle
			$sql = "UPDATE ac_voucherdet
					SET
						MontoPost = '".$_monto."',
						UltimoUsuario = '".$_SESSION['_USUARIO_ACTUAL']."',
						UltimaFecha = NOW()
					WHERE
						CodOrganismo = '".$organismo."' AND
						Periodo = '".$periodo."' AND
						Voucher = '".$voucher."'";
			$query_update = mysql_query($sql) or die($sql.mysql_error());
			
			$estado = "MA";
		} else $estado = "AP";
		
		//	actualizo
		$sql = "UPDATE ac_vouchermast
				SET
					Estado = '$estado',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$organismo."' AND
					Periodo = '".$periodo."' AND
					Voucher = '".$voucher."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
	}
	
	//	rechazar
	elseif ($accion == "rechazar") {
		$voucher = "$codvoucher-$nrovoucher";
		
		//	actualizo
		$sql = "UPDATE ac_vouchermast
				SET
					Estado = 'RE',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$organismo."' AND
					Periodo = '".$periodo."' AND
					Voucher = '".$voucher."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
	}
}

//	mayorizar/desmayorizar voucher
else if ($modulo == "voucher_mayorizar") {
	//	mayorizar
	if ($accion == "mayorizar") {
		//	recorro todos los vouchers seleccionados
		$detalle = split(";", $seleccion);
		foreach ($detalle as $voucher) {
			//	consulto detalles
			$sql = "SELECT
						vd.Linea,
						vd.CodCuenta,
						vd.MontoVoucher,
						vb.CodCuenta AS CodCuentaBalance
					FROM
						ac_voucherdet vd
						LEFT JOIN ac_voucherbalance vb ON (vd.CodOrganismo = vb.CodOrganismo AND
														   vd.Periodo = vb.Periodo AND
														   vd.CodCuenta = vb.CodCuenta)
					WHERE
						vd.CodOrganismo = '".$organismo."' AND
						vd.Periodo = '".$periodo."' AND
						vd.Voucher = '".$voucher."'";
			$query_det = mysql_query($sql) or die($sql.mysql_error());
			while ($field_det = mysql_fetch_array($query_det)) {
				//	si la cuenta tiene moviviento para el periodo actualizo caso contrario inserto
				if ($field_det['CodCuentaBalance'] == "") {
					//	inserto balance
					$sql = "INSERT INTO ac_voucherbalance (
										CodOrganismo,
										Periodo,
										CodCuenta,
										SaldoInicial,
										SaldoBalance,
										UltimoUsuario,
										UltimaFecha
							) VALUES (
										'".$organismo."',
										'".$periodo."',
										'".$field_det['CodCuenta']."',
										'".$field_det['MontoVoucher']."',
										'".$field_det['MontoVoucher']."',
										'".$_SESSION['USUARIO_ACTUAL']."',
										NOW()
							)";
					$query_insert = mysql_query($sql) or die($sql.mysql_error());
				} else {
					//	actualizo balance
					$sql = "UPDATE ac_voucherbalance
							SET
								SaldoBalance = (SaldoBalance + ".floatval($field_det['MontoVoucher'])."),
								UltimoUsuario = '".$_SESSION['_USUARIO_ACTUAL']."',
								UltimaFecha = NOW()
							WHERE
								CodOrganismo = '".$organismo."' AND
								Periodo = '".$periodo."' AND
								CodCuenta = '".$field_det['CodCuenta']."'";
					$query_update = mysql_query($sql) or die($sql.mysql_error());
				}
				
				//	actualizo monto post del detalle
				$sql = "UPDATE ac_voucherdet
						SET
							MontoPost = '".$field_det['MontoVoucher']."',
							UltimoUsuario = '".$_SESSION['_USUARIO_ACTUAL']."',
							UltimaFecha = NOW()
						WHERE
							CodOrganismo = '".$organismo."' AND
							Periodo = '".$periodo."' AND
							Voucher = '".$voucher."' AND
							Linea = '".$field_det['Linea']."'";
				$query_update = mysql_query($sql) or die($sql.mysql_error());
			}
					
			//	actualizo voucher
			$sql = "UPDATE ac_vouchermast
					SET
						Estado = 'MA',
						UltimoUsuario = '".$_SESSION['_USUARIO_ACTUAL']."',
						UltimaFecha = NOW()
					WHERE
						CodOrganismo = '".$organismo."' AND
						Periodo = '".$periodo."' AND
						Voucher = '".$voucher."'";
			$query_update = mysql_query($sql) or die($sql.mysql_error());
		}
	}
	
	//	desmayorizar
	elseif ($accion == "desmayorizar") {
		//	recorro todos los vouchers seleccionados
		$detalle = split(";", $seleccion);
		foreach ($detalle as $voucher) {
			//	consulto detalles
			$sql = "SELECT
						vd.Linea,
						vd.CodCuenta,
						vd.MontoVoucher,
						vb.SaldoInicial,
						vb.SaldoBalance
					FROM
						ac_voucherdet vd
						LEFT JOIN ac_voucherbalance vb ON (vd.CodOrganismo = vb.CodOrganismo AND
														   vd.Periodo = vb.Periodo AND
														   vd.CodCuenta = vb.CodCuenta)
					WHERE
						vd.CodOrganismo = '".$organismo."' AND
						vd.Periodo = '".$periodo."' AND
						vd.Voucher = '".$voucher."'";
			$query_det = mysql_query($sql) or die($sql.mysql_error());
			while ($field_det = mysql_fetch_array($query_det)) {
				//	actualizo balance
				$sql = "UPDATE ac_voucherbalance
						SET
							SaldoBalance = (SaldoBalance - ".floatval($field_det['MontoVoucher'])."),
							UltimoUsuario = '".$_SESSION['_USUARIO_ACTUAL']."',
							UltimaFecha = NOW()
						WHERE
							CodOrganismo = '".$organismo."' AND
							Periodo = '".$periodo."' AND
							CodCuenta = '".$field_det['CodCuenta']."'";
				$query_update = mysql_query($sql) or die($sql.mysql_error());
			
				
				//	actualizo monto post del detalle
				$sql = "UPDATE ac_voucherdet
						SET
							MontoPost = 0.00,
							UltimoUsuario = '".$_SESSION['_USUARIO_ACTUAL']."',
							UltimaFecha = NOW()
						WHERE
							CodOrganismo = '".$organismo."' AND
							Periodo = '".$periodo."' AND
							Voucher = '".$voucher."' AND
							Linea = '".$field_det['Linea']."'";
				$query_update = mysql_query($sql) or die($sql.mysql_error());
			}
					
			//	actualizo voucher
			$sql = "UPDATE ac_vouchermast
					SET
						Estado = 'AP',
						UltimoUsuario = '".$_SESSION['_USUARIO_ACTUAL']."',
						UltimaFecha = NOW()
					WHERE
						CodOrganismo = '".$organismo."' AND
						Periodo = '".$periodo."' AND
						Voucher = '".$voucher."'";
			$query_update = mysql_query($sql) or die($sql.mysql_error());
		}
	}
}
?>
