<?php
include("fphp.php");
include("lg_fphp.php");
///////////////////////////////////////////////////////////////////////////////
//	PARA AJAX
///////////////////////////////////////////////////////////////////////////////
//	clasificaciones
if ($modulo == "clasificaciones") {
	//	nuevo
	if ($accion == "nuevo") {
		if ($codigo == "") $codigo = getCodigo("lg_clasificacion", "Clasificacion", 3);
		
		//	inserto
		$sql = "INSERT INTO lg_clasificacion (
							Clasificacion,
							Descripcion,
							ReqOrdenCompra,
							CodAlmacen,
							TipoRequerimiento,
							FlagRecepcionAlmacen,
							FlagRevision,
							ReqAlmacenCompra,
							FlagTransaccion,
							FlagCajaChica,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$codigo."',
							'".($descripcion)."',
							'".$disponible."',
							'".$codalmacen."',
							'".$requerimiento."',
							'".$flagrecepcion."',
							'".$flagrevision."',
							'".$almacen_compra."',
							'".$flagtransaccion."',
							'".$flagcajachica."',
							'".$estado."',
							'".$_SESSION['USUARIO_ACTUAL']."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die($sql.mysql_error());
	}
	
	//	modificar
	elseif ($accion == "modificar") {
		//	actualizo
		$sql = "UPDATE lg_clasificacion
				SET
					Descripcion = '".($descripcion)."',
					ReqOrdenCompra = '".$disponible."',
					CodAlmacen = '".$codalmacen."',
					TipoRequerimiento = '".$requerimiento."',
					FlagRecepcionAlmacen = '".$flagrecepcion."',
					FlagRevision = '".$flagrevision."',
					ReqAlmacenCompra = '".$almacen_compra."',
					FlagTransaccion = '".$flagtransaccion."',
					FlagCajaChica = '".$flagcajachica."',
					Estado = '".$estado."',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE Clasificacion = '".$codigo."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
	}
	
	//	borrar
	elseif ($accion == "eliminar") {
		//	elimino
		$sql = "DELETE FROM lg_clasificacion WHERE Clasificacion = '".$codigo."'";
		$query_delete = mysql_query($sql) or die($sql.mysql_error());
	}
}

//	cotizaciones
elseif ($modulo == "cotizaciones") {
	//	invitar
	if ($accion == "cotizaciones_invitar_proveedor") {
		//	busco errores
		//	proveedores
		$proveedor = split(";", $proveedores);
		foreach ($proveedor as $registro_proveedor) {
			list($codproveedor, $codformapago) = split("[|]", $registro_proveedor);
			
			//	requerimientos
			$requerimiento = split(";", $requerimientos);
			foreach ($requerimiento as $registro_requerimiento) {
				list($idrequerimiento, $cantidad, $flagexonerado) = split("[|]", $registro_requerimiento);
				list($codorganismo, $codrequerimiento, $secuencia) = split("[.]", $idrequerimiento);
				//	verifico si el proveedor ya tiene una invitacion de uno de los requerimientos
				$sql = "SELECT NomProveedor
						FROM lg_cotizacion
						WHERE
							CodRequerimiento = '".$codrequerimiento."' AND
							Secuencia = '".$secuencia."' AND
							CodProveedor = '".$codproveedor."'";
				$query = mysql_query($sql) or die($sql.mysql_error());
				if (mysql_num_rows($query) != 0) {
					$field = mysql_fetch_array($query);
					die("¡ERROR: $field[NomProveedor] ya tiene una invitacion para uno de los requerimientos!");
				}
			}
		}
		
		//selecciono el maximo NroSolicitudCotizacion 
		$sql4 = "SELECT (IFNULL ( MAX( NroSolicitudCotizacion), 0))+1  as numMaximo
						FROM lg_cotizacion";
						
						
						//(IFNULL ( MAX( NroSolicitudCotizacion), 0))+1   
						
						// (max(NroSolicitudCotizacion)+1)
		$query4 = mysql_query($sql4) or die($sql4.mysql_error());
		if (mysql_num_rows($query4) != 0) 
		{
			$fetch4 = mysql_fetch_array($query4);
		}

		//seleccion el maximo del requerimiento para ver si ya he realizado invitacion
		$sql5 = "SELECT (IFNULL ( MAX( NroSolicitudCotizacion), 0))  as numMaximo
						FROM lg_cotizacion 
					where 
					CodRequerimiento = '".$codrequerimiento."'";
		
		$query5 = mysql_query($sql5) or die($sql5.mysql_error());
		
		if (mysql_num_rows($query5) != 0) 
		{
			$fetch5 = mysql_fetch_array($query5);

			

		}

		if($fetch5['numMaximo'] != 0)
		{
			$nroSolicitudCotizacion = $fetch5['numMaximo'];

		} else {
		
			$nroSolicitudCotizacion = $fetch4['numMaximo'];

		}
		
//echo $nroSolicitudCotizacion; exit;

		$numero = intval(getCodigo("lg_cotizacion", "Numero", 10));
		//	inserto la invitacion
		//	proveedores
		$proveedor = split(";", $proveedores);
		foreach ($proveedor as $registro_proveedor) {
			list($codproveedor, $codformapago) = split("[|]", $registro_proveedor);
			$nomproveedor = getValorCampo("mastpersonas", "CodPersona", "NomCompleto", $codproveedor);
			
			//	numero de cotizacion proveedor
			$cotizacion_numero_proveedor = getCodigo("lg_cotizacion", "NroCotizacionProv", 8);
			
			//	requerimientos
			$requerimiento = split(";", $requerimientos);
			foreach ($requerimiento as $registro_requerimiento) {
				list($idrequerimiento, $cantidad, $flagexonerado) = split("[|]", $registro_requerimiento);
				list($codorganismo, $codrequerimiento, $secuencia) = split("[.]", $idrequerimiento);
				
				//	numero de invitacines y el numero de cotizacion
				$nroinvitaciones = count($proveedor);
				$cotizacion_numero = getCodigo("lg_cotizacion", "CotizacionNumero", 8);
				
				//obtengo la mayor invitacion
				
					
				//	inserto cotizacion
				$sql = "INSERT INTO lg_cotizacion (
									CodOrganismo,
									CodRequerimiento,
									Secuencia,
									CotizacionNumero,
									Numero,
									CodProveedor,
									NomProveedor,
									CodFormaPago,
									FechaInvitacion,
									FechaDocumento,
									FechaLimite,
									FechaEntrega,
									Condiciones,
									Observaciones,
									Cantidad,
									Estado,
									NroCotizacionProv,
									FlagAsignado,
									FlagExonerado,
									NumeroInvitacion,
									UltimoUsuario,
									UltimaFecha,
									NroSolicitudCotizacion
						) VALUES (
									'".$codorganismo."',
									'".$codrequerimiento."',
									'".$secuencia."',
									'".$cotizacion_numero."',
									'".$numero."',
									'".$codproveedor."',
									'".($nomproveedor)."',
									'".$codformapago."',
									NOW(),
									NOW(),
									'".formatFechaAMD($flimite)."',
									'".formatFechaAMD($flimite)."',
									'".($condiciones)."',
									'".($observaciones)."',
									'".$cantidad."',
									'A',
									'".$cotizacion_numero_proveedor."',
									'N',
									'".$flagexonerado."',
									'".$cotizacion_numero_proveedor."',
									'".$_SESSION['USUARIO_ACTUAL']."',
									NOW(),
									".$nroSolicitudCotizacion."
						)";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
				
				//	actualizo numero de invitaciones
				$sql = "UPDATE lg_requerimientosdet 
						SET
							CotizacionSecuencia = '".mysql_insert_id()."',
							CotizacionCantidad = '".$cantidad."',
							CotizacionProveedor = '".$codproveedor."',
							CotizacionFormaPago = '".$codformapago."',
							CotizacionRegistros = (CotizacionRegistros + 1)							
						WHERE
							CodRequerimiento = '".$codrequerimiento."' AND 
							Secuencia = '".$secuencia."'";
				$query_update = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
		echo "|$numero";
	}
	
	//	invitar/cotizar
	elseif ($accion == "cotizaciones_invitar_cotizar") {
		$numero = intval(getCodigo("lg_cotizacion", "Numero", 10));
		//	inserto las cotizaciones
		//	proveedores
		$proveedor = split(";", $detalle);
		foreach ($proveedor as $registro_proveedor) {
			list($codproveedor, $flagasig, $cant, $pu, $flagexon, $pu_igv, $descp, $descf, $pu_total, $total, $comparar, $flagmejor, $codformapago, $finvitacion, $flimite, $condiciones, $observaciones, $dias, $validez, $nrocotizacion) = split("[|]", $registro_proveedor);
			$nomproveedor = getValorCampo("mastpersonas", "CodPersona", "NomCompleto", $codproveedor);
			
			if ($flagasig == "S" && $flagmejor == "N") {
				if ($observaciones != "") $observaciones .= "\n$obs";
				else $observaciones = $obs;
			}
			
			//	fecha de entrega
			$fentrega = getFechaFin(date("d-m-Y"), $dias);
			
			//	precio unitario con descuento
			if ($descp != 0) $pu_desc = $pu - ($pu * $descp / 100); else $pu_desc = $pu - $descf;
			
			//	precio x cantidad
			$precio_cantidad = $pu_desc * $cant;
			
			//	verifico si el proveedor ya tiene una invitacion de uno de los requerimientos
			$sql = "SELECT *
					FROM lg_cotizacion
					WHERE
						CodRequerimiento = '".$codrequerimiento."' AND
						Secuencia = '".$secuencia."' AND
						CodProveedor = '".$codproveedor."'";
			$query = mysql_query($sql) or die($sql.mysql_error());
			if (mysql_num_rows($query) != 0) {
				$field = mysql_fetch_array($query);
				
				//	actualizo cotizacion
				$sql = "UPDATE lg_cotizacion
						SET
							FechaInvitacion = '".formatFechaAMD($finvitacion)."',
							FechaDocumento = '".formatFechaAMD($finvitacion)."',
							FechaLimite = '".formatFechaAMD($flimite)."',
							FechaEntrega = '".formatFechaAMD($fentrega)."',
							CodFormaPago = '".$codformapago."',
							PrecioUnitInicio = '".$pu."',
							PrecioUnitInicioIva = '".$pu_igv."',
							PrecioUnit = '".$pu_desc."',
							PrecioUnitIva = '".$pu_total."',
							PrecioCantidad = '".$precio_cantidad."',
							Total = '".$total."',
							ValidezOferta = '".$validez."',
							DiasEntrega = '".$dias."',
							Cantidad = '".$cant."',
							DescuentoFijo = '".$descf."',
							DescuentoPorcentaje = '".$descp."',
							Condiciones = '".($condiciones)."',
							Observaciones = '".($observaciones)."',
							NumeroCotizacion = '".$nrocotizacion."',
							FlagAsignado = '".$flagasig."',
							FlagExonerado = '".$flagexon."',
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()							
						WHERE CotizacionSecuencia = '".$field['CotizacionSecuencia']."'";
				$query_update = mysql_query($sql) or die ($sql.mysql_error());
				
				if ($flagasig == "S") {
					//	actualizo datos de la cotizacion asignada
					$sql = "UPDATE lg_requerimientosdet
							SET
								CotizacionCantidad = '".$cant."',
								CotizacionPrecioUnitInicio = '".$pu."',
								CotizacionPrecioUnit = '".$precio_unitario."',
								CotizacionPrecioUnitIva = '".$precio_unitario_iva."',
								CotizacionFechaAsignacion = '".date("Y-m-d")."'
							WHERE
								CodRequerimiento = '".$codrequerimiento."' AND
								Secuencia = '".$secuencia."'";
					$query_update = mysql_query($sql) or die ($sql.mysql_error());
				}
			} else {
				//	numero de cotizacion proveedor
				$cotizacion_numero_proveedor = getCodigo("lg_cotizacion", "NroCotizacionProv", 8);
				
				//	numero de invitacines y el numero de cotizacion
				$nroinvitaciones = count($proveedor);
				$cotizacion_numero = getCodigo("lg_cotizacion", "CotizacionNumero", 8);
				
				//	inserto cotizacion
				$sql = "INSERT INTO lg_cotizacion (
									CodOrganismo,
									CodRequerimiento,
									Secuencia,
									CotizacionNumero,
									Numero,
									FechaInvitacion,
									FechaDocumento,
									FechaLimite,
									FechaEntrega,
									CodProveedor,
									NomProveedor,
									CodFormaPago,
									PrecioUnitInicio,
									PrecioUnitInicioIva,
									PrecioUnit,
									PrecioUnitIva,
									PrecioCantidad,
									Total,
									ValidezOferta,
									DiasEntrega,
									Cantidad,
									DescuentoFijo,
									DescuentoPorcentaje,
									Condiciones,
									Observaciones,
									Estado,
									NroCotizacionProv,
									NumeroInvitacion,
									NumeroCotizacion,
									FlagAsignado,
									FlagExonerado,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$codorganismo."',
									'".$codrequerimiento."',
									'".$secuencia."',
									'".$cotizacion_numero."',
									'".$numero."',
									'".formatFechaAMD($finvitacion)."',
									'".formatFechaAMD($finvitacion)."',
									'".formatFechaAMD($flimite)."',
									'".formatFechaAMD($fentrega)."',
									'".$codproveedor."',
									'".($nomproveedor)."',
									'".$codformapago."',
									'".$pu."',
									'".$pu_igv."',
									'".$pu_desc."',
									'".$pu_total."',
									'".$precio_cantidad."',
									'".$total."',
									'".$validez."',
									'".$dias."',
									'".$cant."',
									'".$descf."',
									'".$descp."',
									'".($condiciones)."',
									'".($observaciones)."',
									'A',
									'".$cotizacion_numero_proveedor."',
									'".$cotizacion_numero_proveedor."',
									'".$nrocotizacion."',
									'".$flagasig."',
									'".$flagexon."',
									'".$_SESSION['USUARIO_ACTUAL']."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
				
				if ($flagasig == "S") {
					//	actualizo numero de invitaciones
					$sql = "UPDATE lg_requerimientosdet
							SET
								CotizacionCantidad = '".$cant."',
								CotizacionPrecioUnitInicio = '".$pu."',
								CotizacionPrecioUnit = '".$precio_unitario."',
								CotizacionPrecioUnitIva = '".$precio_unitario_iva."',
								CotizacionFechaAsignacion = '".formatFechaAMD($fasignacion)."',
								CotizacionProveedor = '".$codproveedor."',
								CotizacionFormaPago = '".$codformapago."',
								CotizacionRegistros = (CotizacionRegistros + 1)
							WHERE
								CodRequerimiento = '".$codrequerimiento."' AND
								Secuencia = '".$secuencia."'";
					$query_update = mysql_query($sql) or die ($sql.mysql_error());
				} else {
					//	actualizo numero de invitaciones
					$sql = "UPDATE lg_requerimientosdet
							SET CotizacionRegistros = (CotizacionRegistros + 1)
							WHERE
								CodRequerimiento = '".$codrequerimiento."' AND
								Secuencia = '".$secuencia."'";
					$query_update = mysql_query($sql) or die ($sql.mysql_error());
				}
			}
		}
		echo "|$numero";
	}
	
	//	invitar/cotizar
	elseif ($accion == "cotizaciones_invitaciones_cotizar") {
		//	inserto las cotizaciones
		//	proveedores
		$proveedor = split(";", $detalle);
		foreach ($proveedor as $registro_proveedor) {
			list($cotizacion_secuencia, $cant, $pu, $flagasig, $flagexon, $pu_igv, $descp, $descf, $pu_desc, $pu_total, $total, $observaciones) = split("[|]", $registro_proveedor);
			
			//	fecha de entrega
			$fentrega = getFechaFin(date("d-m-Y"), $dias);
			
			//	precio x cantidad
			$precio_cantidad = $pu_desc * $cant;
			
			//	actualizo cotizacion
			$sql = "UPDATE lg_cotizacion
					SET
						FechaApertura = '".formatFechaAMD($fapertura)."',
						FechaRecepcion = '".formatFechaAMD($frecepcion)."',
						FechaDocumento = '".formatFechaAMD($fcotizacion)."',
						FechaEntrega = '".formatFechaAMD($fentrega)."',
						CodFormaPago = '".$codformapago."',
						PrecioUnitInicio = '".$pu."',
						PrecioUnitInicioIva = '".$pu_igv."',
						PrecioUnit = '".$pu_desc."',
						PrecioUnitIva = '".$pu_total."',
						PrecioCantidad = '".$precio_cantidad."',
						Total = '".$total."',
						ValidezOferta = '".$validez."',
						DiasEntrega = '".$dias."',
						Cantidad = '".$cant."',
						DescuentoFijo = '".$descf."',
						DescuentoPorcentaje = '".$descp."',
						Observaciones = '".($observaciones)."',
						FlagAsignado = '".$flagasig."',
						FlagExonerado = '".$flagexon."',
						NumeroCotizacion = '".$nrocotizacionprov."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()							
					WHERE CotizacionSecuencia = '".$cotizacion_secuencia."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
			
			if ($flagasig == "S") {
				$sql = "SELECT CodRequerimiento, Secuencia
						FROM lg_cotizacion
						WHERE CotizacionSecuencia = '".$cotizacion_secuencia."'";
				$query_cot = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_cot) != 0) $field_cot = mysql_fetch_array($query_cot);
				
				//	actualizo requerimiento detalle
				$sql = "UPDATE lg_requerimientosdet
						SET
							CotizacionCantidad = '".$cant."',
							CotizacionPrecioUnitInicio = '".$pu."',
							CotizacionPrecioUnit = '".$pu_desc."',
							CotizacionPrecioUnitIva = '".$pu_total."',
							CotizacionFechaAsignacion = '".date("Y-m-d")."',
							CotizacionProveedor = '".$codproveedor."',
							CotizacionFormaPago = '".$codformapago."'
						WHERE
							CodRequerimiento = '".$field_cot['CodRequerimiento']."' AND
							Secuencia = '".$field_cot['Secuencia']."'";
				$query_update = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
	}
	
	//	eliminar invitacion
	elseif ($accion = "eliminar") {
		$sql = "DELETE FROM lg_cotizacion WHERE NroCotizacionProv = '".$registro."'";
		$query_delete = mysql_query($sql) or die($sql.mysql_error());
	}
}

//	generar ordenes pendientes
elseif ($modulo == "generar_ordenes_pendientes") {
	$monto_bruto = $monto_total - $monto_impuestos;
	if ($monto_impuestos != 0) {
		$partida_igv = $_PARAMETRO['IVADEFAULT'];
		$cuenta_igv = $_PARAMETRO['IVACTADEF'];
	}
	
	//	detalles
	$sql = "SELECT
				c.*,
				r.Anio,
				rd.CodItem,
				rd.CommoditySub,
				rd.Descripcion,
				rd.CodUnidad,				
				rd.CodCentroCosto,				
				rd.CodCuenta,
				rd.cod_partida
			FROM
				lg_cotizacion c
				INNER JOIN lg_requerimientos r ON (c.CodOrganismo = r.CodOrganismo AND
												   c.CodRequerimiento = r.CodRequerimiento)
				INNER JOIN lg_requerimientosdet rd ON (c.CodOrganismo = rd.CodOrganismo AND
													   c.CodRequerimiento = rd.CodRequerimiento AND
													   c.Secuencia = rd.Secuencia)
			WHERE
				c.FlagAsignado = 'S' AND
				c.NroCotizacionProv = '".$nrocotizacionprov."'";
	$query_det = mysql_query($sql) or die ($sql.mysql_error());
	while ($field_det = mysql_fetch_array($query_det)) {
		$anio = $field_det['Anio'];
		$organismo = $field_det['CodOrganismo'];
		$_monto_impuestos += ($field_det['PrecioUnitIva'] - $field_det['PrecioUnit']);
		if ($field_det['CodItem'] != "") { $tabla = "item"; $_codigo = $field_det['CodItem']; }
		else { $tabla = "commodity"; $_codigo = $field_det['CommoditySub']; }
		if ($i > 0) $detalles += ";";
		$detalles += "$_codigo|$field_det[Descripcion]|$field_det[CodUnidad]|$field_det[Cantidad]|$field_det[PrecioUnit]|$field_det[DescuentoPorcentaje]|$field_det[DescuentoFijo]|$field_det[FlagExonerado]|$field_det[PrecioUnitInicioIva]|$field_det[Total]|$field_det[FechaEntrega]|$field_det[CodCentroCosto]|$field_det[cod_partida]|$field_det[CodCuenta]|$field_det[Observaciones]";
	}
	$disponible = verificarDisponibilidadPresupuestariaOC($anio, $organismo, $codigo, $detalles, $monto_impuestos, $tabla);
	if (!$disponible) die("¡ERROR: Se encontrarón lineas del detalle sin disponibilidad presupuestaria!");
	
	//	compras
	if ($accion == "compras") {
		//	genero la orden
		$nroorden = getCodigo_2("lg_ordencompra", "NroOrden", "CodOrganismo", $codorganismo, 10);
		
		//	inserto orden de compra
		$sql = "INSERT INTO lg_ordencompra (
							Anio,
							CodOrganismo,
							NroOrden,
							Mes,
							CodDependencia,
							CodProveedor,
							NomProveedor,
							FechaPrometida,
							Clasificacion,
							CodFormaPago,
							CodTipoServicio,
							CodAlmacen,
							CodAlmacenIngreso,
							NomContacto,
							FaxContacto,
							Entregaren,
							DirEntrega,
							InsEntrega,
							Observaciones,
							ObsDetallada,
							MontoAfecto,
							MontoNoAfecto,
							MontoBruto,
							MontoIGV,
							MontoTotal,
							MontoPendiente,
							MontoOtros,
							PreparadaPor,
							FechaPreparacion,
							PlazoEntrega,
							cod_partida,
							CodCuenta,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							NOW(),
							'".$codorganismo."',
							'".$nroorden."',
							SUBSTRING(NOW(), 6, 2),
							'".$coddependencia."',
							'".$codproveedor."',
							'".($nomproveedor)."',
							'".formatFechaAMD($fentrega)."',
							'".$clasificacion."',
							'".$codformapago."',
							'".$codservicio."',
							'".$almacen_entrega."',
							'".$almacen_ingreso."',
							'".($nomcontacto)."',
							'".$faxcontacto."',
							'".($entregaren)."',
							'".($direccion)."',
							'".($instruccion)."',
							'".($observaciones)."',
							'".($detallada)."',
							'".$monto_afecto."',
							'".$monto_noafecto."',
							'".$monto_bruto."',
							'".$monto_impuestos."',
							'".$monto_total."',
							'".$monto_pendiente."',
							'".$monto_otros."',
							'".$_SESSION['CODPERSONA_ACTUAL']."',
							NOW(),
							'".$dias_entrega."',
							'".$partida_igv."',
							'".$cuenta_igv."',
							'".$_SESSION['USUARIO_ACTUAL']."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		
		//	detalles
		$sql = "SELECT
					c.*,
					rd.CodItem,
					rd.CommoditySub,
					rd.Descripcion,
					rd.CodCentroCosto,
					rd.CodUnidad,
					rd.CodCuenta,
					rd.cod_partida
				FROM
					lg_cotizacion c
					INNER JOIN lg_requerimientosdet rd ON (c.CodOrganismo = rd.CodOrganismo AND
														   c.CodRequerimiento = rd.CodRequerimiento AND
														   c.Secuencia = rd.Secuencia)
				WHERE 
					c.FlagAsignado = 'S' AND
					c.NroCotizacionProv = '".$nrocotizacionprov."'";
		$query_det = mysql_query($sql) or die ($sql.mysql_error());	$i=0;
		while ($field_det = mysql_fetch_array($query_det)) {	$i++;
			//	inserto detalle de la orden
			$sql = "INSERT INTO lg_ordencompradetalle (
								Anio,
								CodOrganismo,
								NroOrden,
								Secuencia,
								Mes,
								CodItem,
								CommoditySub,
								Descripcion,
								CodUnidad,
								CantidadPedida,
								PrecioUnit,
								PrecioCantidad,
								Total,
								DescuentoPorcentaje,
								DescuentoFijo,
								FlagExonerado,
								CodCentroCosto,
								Comentarios,
								FechaPrometida,
								CodCuenta,
								cod_partida,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								NOW(),
								'".$field_det['CodOrganismo']."',
								'".$nroorden."',
								'".$i."',
								SUBSTRING(NOW(), 6, 2),
								'".$field_det['CodItem']."',
								'".$field_det['CommoditySub']."',
								'".$field_det['Descripcion']."',
								'".$field_det['CodUnidad']."',
								'".$field_det['Cantidad']."',
								'".$field_det['PrecioUnit']."',
								'".$field_det['PrecioCantidad']."',
								'".$field_det['Total']."',
								'".$field_det['DescuentoPorcentaje']."',
								'".$field_det['DescuentoFijo']."',
								'".$field_det['FlagExonerado']."',
								'".$field_det['CodCentroCosto']."',
								'".$field_det['Observaciones']."',
								'".$field_det['FechaEntrega']."',
								'".$field_det['CodCuenta']."',
								'".$field_det['cod_partida']."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			
			//	actualizo requerimiento
			$sql = "UPDATE lg_requerimientosdet
					SET
						NroOrden = '".$nroorden."',
						OrdenSecuencia = '".$i."',
						CantidadOrdenCompra = '".$field_det['Cantidad']."',
						Estado = 'CO'
					WHERE
						CodRequerimiento = '".$field_det['CodRequerimiento']."' AND
						Secuencia = '".$field_det['Secuencia']."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
			
			//	verifico si todos los detalles de requerimientos fueron completados
			$sql = "SELECT *
					FROM lg_requerimientosdet
					WHERE
						CodRequerimiento = '".$field_det['CodRequerimiento']."' AND
						(Estado = 'PR' OR Estado = 'PE')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query) == 0) {
				//	completo requerimiento
				$sql = "UPDATE lg_requerimientos
						SET Estado = 'CO'
						WHERE CodRequerimiento = '".$field_det['CodRequerimiento']."'";
				$query_update = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
		
		$i=0;
		//	distribucion
		$sql = "SELECT
					ocd.CodOrganismo,
					ocd.NroOrden,
					ocd.cod_partida,
					ocd.CodCuenta,
					SUM(PrecioCantidad) AS Monto,
					ocd.CodCentroCosto,
					oc.CodProveedor
				FROM
					lg_ordencompradetalle ocd
					INNER JOIN lg_ordencompra oc ON (ocd.CodOrganismo = oc.codOrganismo AND
													 ocd.NroOrden = oc.NroOrden)
				WHERE
					ocd.Anio = SUBSTRING(NOW(), 1, 4) AND
					ocd.CodOrganismo = '".$codorganismo."' AND
					ocd.NroOrden = '".$nroorden."'
				GROUP BY cod_partida";
		$query_dis = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_dis = mysql_fetch_array($query_dis)) {
			$i++;
			
			//	inserto
			$sql = "INSERT INTO lg_distribucionoc (
								Anio,
								CodOrganismo,
								NroOrden,
								Secuencia,
								Mes,
								cod_partida,
								CodCuenta,
								Monto,
								CodCentroCosto,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								NOW(),
								'".$field_dis['CodOrganismo']."',
								'".$field_dis['NroOrden']."',
								'".$i."',
								SUBSTRING(NOW(), 6, 2),
								'".$field_dis['cod_partida']."',
								'".$field_dis['CodCuenta']."',
								'".$field_dis['Monto']."',
								'".$field_dis['CodCentroCosto']."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			
			//	inserto
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
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								NOW(),
								'".$field_dis['CodOrganismo']."',
								(SELECT CodPresupuesto FROM `pv_presupuesto` WHERE EjercicioPpto = '".$anho."'),
								'".$field_dis['CodProveedor']."',
								'OC',
								'".$field_dis['NroOrden']."',
								'".$i."',
								'1',
								SUBSTRING(NOW(), 6, 2),
								'".$field_dis['CodCentroCosto']."',
								'".$field_dis['cod_partida']."',
								'".$field_dis['Monto']."',
								NOW(),
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		}
		//	distribucion (IGV)
		$sql = "SELECT
					CodOrganismo,
					NroOrden,
					cod_partida,
					CodCuenta,
					MontoIGV,
					CodProveedor
				FROM lg_ordencompra
				WHERE
					Anio = SUBSTRING(NOW(), 1, 4) AND
					CodOrganismo = '".$codorganismo."' AND
					NroOrden = '".$nroorden."' AND
					MontoIGV > 0
				GROUP BY cod_partida";
		$query_dis = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_dis = mysql_fetch_array($query_dis)) {
			$i++;
			//	inserto
			$sql = "INSERT INTO lg_distribucionoc (
								Anio,
								CodOrganismo,
								NroOrden,
								Secuencia,
								Mes,
								cod_partida,
								CodCuenta,
								Monto,
								CodCentroCosto,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								NOW(),
								'".$field_dis['CodOrganismo']."',
								'".$field_dis['NroOrden']."',
								'".$i."',
								SUBSTRING(NOW(), 6, 2),
								'".$field_dis['cod_partida']."',
								'".$field_dis['CodCuenta']."',
								'".$field_dis['MontoIGV']."',
								'".$_PARAMETRO['CCOSTOCOMPRA']."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			
			//	inserto
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
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								NOW(),
								'".$field_dis['CodOrganismo']."',
								(SELECT CodPresupuesto FROM `pv_presupuesto` WHERE EjercicioPpto = '".$anho."'),
								'".$field_dis['CodProveedor']."',
								'OC',
								'".$field_dis['NroOrden']."',
								'".$i."',
								'1',
								SUBSTRING(NOW(), 6, 2),
								'".$_PARAMETRO['CCOSTOCOMPRA']."',
								'".$field_dis['cod_partida']."',
								'".$field_dis['Monto']."',
								NOW(),
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		}
		
		//	consulto compromisos
		$sql = "SELECT *
				FROM lg_distribucionoc
				WHERE
					CodOrganismo = '".$codorganismo."' AND
					NroOrden = '".$nroorden."' AND
					Anio = SUBSTRING(NOW(), 1, 4)
				ORDER BY Secuencia";
		$query_dis = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_dis = mysql_fetch_array($query_dis)) {
			//	actualizo presupuesto
			/*$sql = "UPDATE pv_presupuestodet
					SET MontoCompromiso = MontoCompromiso + ".$field_dis['Monto']."
					WHERE
						Organismo = '".$codorganismo."' AND
						CodPresupuesto = (SELECT CodPresupuesto FROM pv_presupuesto WHERE EjercicioPpto = SUBSTRING(NOW(), 1, 4)) AND
						cod_partida = '".$field_dis['cod_partida']."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());*/
		}
		
		echo "|Se ha generado la Orden de Compra Nro. $nroorden";
	}
	
	//	servicios
	elseif ($accion == "servicios") {
		$nroorden = getCodigo_2("lg_ordenservicio", "NroOrden", "CodOrganismo", $codorganismo, 10);
		$fentrega = getFechaFin(date("d-m-Y"), $_PARAMETRO['DIAENTOC']);
		
		//	inserto orden de servicio
		$sql = "INSERT INTO lg_ordenservicio (
							Anio,
							CodOrganismo,
							NroOrden,
							Mes,
							CodDependencia,
							CodProveedor,
							NomProveedor,
							CodFormaPago,
							FechaDocumento,
							CodTipoPago,
							CodTipoServicio,
							PlazoEntrega,
							FechaEntrega,
							FechaValidoDesde,
							FechaValidoHasta,
							MontoOriginal,
							MontoIva,
							TotalMontoIva,
							Descripcion,
							Observaciones,
							CodCentroCosto,
							PreparadaPor,
							FechaPreparacion,
							CodCuenta,
							cod_partida,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							NOW(),
							'".$codorganismo."',
							'".$nroorden."',
							SUBSTRING(NOW(), 6, 2),
							'".$coddependencia."',
							'".$codproveedor."',
							'".($nomproveedor)."',
							'".$codformapago."',
							NOW(),
							'".$codtipopago."',
							'".$codservicio."',
							'".$dias_entrega."',
							'".formatFechaAMD($fentrega)."',
							NOW(),
							NOW(),
							'".$monto_afecto."',
							'".$monto_impuestos."',
							'".$monto_total."',
							'".($descripcion)."',
							'".($observaciones)."',
							'".$ccosto."',
							'".$_SESSION['CODPERSONA_ACTUAL']."',
							NOW(),
							'".$cuenta_igv."',
							'".$partida_igv."',
							'".$_SESSION['USUARIO_ACTUAL']."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		
		//	detalles
		$sql = "SELECT
					c.*,
					rd.CodItem,
					rd.CommoditySub,
					rd.Descripcion,
					rd.CodCentroCosto,
					rd.CodUnidad,
					rd.CodCuenta,
					rd.cod_partida
				FROM
					lg_cotizacion c
					INNER JOIN lg_requerimientosdet rd ON (c.CodOrganismo = rd.CodOrganismo AND
														   c.CodRequerimiento = rd.CodRequerimiento AND
														   c.Secuencia = rd.Secuencia)
				WHERE 
					c.FlagAsignado = 'S' AND
					c.NroCotizacionProv = '".$nrocotizacionprov."'";
		$query_det = mysql_query($sql) or die ($sql.mysql_error());	$i=0;
		while ($field_det = mysql_fetch_array($query_det)) {	$i++;
			//	inserto detalle de la orden
			$sql = "INSERT INTO lg_ordenserviciodetalle (
								Anio,
								CodOrganismo,
								NroOrden,								
								Secuencia,
								Mes,
								CommoditySub,
								Descripcion,
								CantidadPedida,
								PrecioUnit,
								Total,
								FlagExonerado,
								FechaEsperadaTermino,
								FechaTermino,
								CodCentroCosto,
								Comentarios,
								cod_partida,
								CodCuenta,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								NOW(),
								'".$field_det['CodOrganismo']."',
								'".$nroorden."',
								'".$i."',
								SUBSTRING(NOW(), 6, 2),
								'".$field_det['CommoditySub']."',
								'".$field_det['Descripcion']."',
								'".$field_det['Cantidad']."',
								'".$field_det['PrecioUnit']."',
								'".$field_det['Total']."',
								'".$field_det['FlagExonerado']."',
								'".$field_det['FechaEntrega']."',
								'".$field_det['FechaEntrega']."',
								'".$field_det['CodCentroCosto']."',
								'".$field_det['Observaciones']."',
								'".$field_det['cod_partida']."',
								'".$field_det['CodCuenta']."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			
			//	actualizo requerimiento
			$sql = "UPDATE lg_requerimientosdet
					SET
						NroOrden = '".$nroorden."',
						OrdenSecuencia = '".$i."',
						CantidadOrdenCompra = '".$field_det['Cantidad']."',
						Estado = 'CO'
					WHERE
						CodOrganismo = '".$field_det['CodOrganismo']."' AND
						CodRequerimiento = '".$field_det['CodRequerimiento']."' AND
						Secuencia = '".$field_det['Secuencia']."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
			
			//	verifico si todos los detalles de requerimientos fueron completados
			$sql = "SELECT *
					FROM lg_requerimientosdet
					WHERE
						CodRequerimiento = '".$field_det['CodRequerimiento']."' AND
						(Estado = 'PR' OR Estado = 'PE')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query) == 0) {
				//	completo requerimiento
				$sql = "UPDATE lg_requerimientos
						SET Estado = 'CO'
						WHERE CodRequerimiento = '".$field_det['CodRequerimiento']."'";
				$query_update = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
		
		$i=0;
		//	distribucion
		$sql = "SELECT
					osd.Anio,
					osd.Mes,
					osd.CodOrganismo,
					osd.NroOrden,
					osd.cod_partida,
					osd.CodCuenta,
					SUM(osd.CantidadPedida*osd.PrecioUnit) AS Monto,
					osd.CodCentroCosto,
					os.CodProveedor
				FROM
					lg_ordenserviciodetalle osd
					INNER JOIN lg_ordenservicio os ON (osd.CodOrganismo = os.codOrganismo AND
													   osd.NroOrden = os.NroOrden)
				WHERE
					osd.Anio = SUBSTRING(NOW(), 1, 4) AND
					osd.CodOrganismo = '".$codorganismo."' AND
					osd.NroOrden = '".$nroorden."'
				GROUP BY cod_partida";
		$query_dis = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_dis = mysql_fetch_array($query_dis)) {
			$i++;			
			//	inserto distribucion os
			$sql = "INSERT INTO lg_distribucionos (
								Anio,
								CodOrganismo,
								NroOrden,
								Secuencia,
								Mes,
								cod_partida,
								CodCuenta,
								Monto,
								CodCentroCosto,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$field_dis['Anio']."',
								'".$field_dis['CodOrganismo']."',
								'".$field_dis['NroOrden']."',
								'".$i."',
								'".$field_dis['Mes']."',
								'".$field_dis['cod_partida']."',
								'".$field_dis['CodCuenta']."',
								'".$field_dis['Monto']."',
								'".$field_dis['CodCentroCosto']."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			
			//	inserto distribucion compromisos
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
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$field_dis['Anio']."',
								'".$field_dis['CodOrganismo']."',
								(SELECT CodPresupuesto FROM `pv_presupuesto` WHERE EjercicioPpto = '".$anho."'),
								'".$field_dis['CodProveedor']."',
								'OS',
								'".$field_dis['NroOrden']."',
								'".$i."',
								'1',
								'".$field_dis['Mes']."',
								'".$field_dis['CodCentroCosto']."',
								'".$field_dis['cod_partida']."',
								'".$field_dis['Monto']."',
								NOW(),
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		}
		//	distribucion (IGV)
		$sql = "SELECT
					Anio,
					Mes,
					CodOrganismo,
					NroOrden,
					cod_partida,
					CodCuenta,
					MontoIva AS Monto,
					CodProveedor,
					CodCentroCosto
				FROM lg_ordenservicio
				WHERE
					Anio = SUBSTRING(NOW(), 1, 4) AND
					CodOrganismo = '".$codorganismo."' AND
					NroOrden = '".$nroorden."' AND
					MontoIva > 0
				GROUP BY cod_partida";
		$query_dis = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_dis = mysql_fetch_array($query_dis)) {
			$i++;
			//	inserto distribucion os igv
			$sql = "INSERT INTO lg_distribucionos (
								Anio,
								CodOrganismo,
								NroOrden,
								Mes,
								Secuencia,
								cod_partida,
								CodCuenta,
								Monto,
								CodCentroCosto,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$field_dis['Anio']."',
								'".$field_dis['CodOrganismo']."',
								'".$field_dis['NroOrden']."',
								'".$field_dis['Mes']."',
								'".$i."',
								'".$field_dis['cod_partida']."',
								'".$field_dis['CodCuenta']."',
								'".$field_dis['Monto']."',
								'".$field_dis['CodCentroCosto']."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			
			//	inserto distribucion compromisos igv
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
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$field_dis['Anio']."',
								'".$field_dis['CodOrganismo']."',
								(SELECT CodPresupuesto FROM `pv_presupuesto` WHERE EjercicioPpto = '".$anho."'),
								'".$field_dis['CodProveedor']."',
								'OS',
								'".$field_dis['NroOrden']."',
								'".$i."',
								'1',
								'".$field_dis['Mes']."',
								'".$field_dis['CodCentroCosto']."',
								'".$field_dis['cod_partida']."',
								'".$field_dis['Monto']."',
								NOW(),
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		}
		
		//	consulto compromisos
		$sql = "SELECT *
				FROM lg_distribucionos
				WHERE
					Anio = SUBSTRING(NOW(), 1, 4) AND
					CodOrganismo = '".$codorganismo."' AND
					NroOrden = '".$nroorden."'
				ORDER BY Secuencia";
		$query_dis = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_dis = mysql_fetch_array($query_dis)) {
			//	actualizo presupuesto
			/*$sql = "UPDATE pv_presupuestodet
					SET MontoCompromiso = MontoCompromiso + ".$field_dis['Monto']."
					WHERE
						Organismo = '".$organismo."' AND
						CodPresupuesto = (SELECT CodPresupuesto FROM pv_presupuesto WHERE EjercicioPpto = SUBSTRING(NOW(), 1, 4)) AND
						cod_partida = '".$field_dis['cod_partida']."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());*/
		}
		
		echo "|Se ha generado la Orden de Servicio Nro. $nroorden";
	}
}

//	compras
elseif ($modulo == "compras") {
	//	nuevo
	if ($accion == "nuevo") {
		if ($tipoclasificacion == "") $tabla = "item"; else $tabla = "commodity";
		$disponible = verificarDisponibilidadPresupuestariaOC($anio, $organismo, $codigo, $detalles, $monto_impuestos, $tabla);
		if (!$disponible) die("¡ERROR: Se encontrarón lineas del detalle sin disponibilidad presupuestaria!");
		
		$codigo = getCodigo_3("lg_ordencompra", "NroOrden", "CodOrganismo", "Anio", $organismo, $anio, 10);
		$monto_bruto = $monto_total - $monto_impuestos;
		if ($monto_impuestos != 0) {
			$partida_igv = $_PARAMETRO['IVADEFAULT'];
			$cuenta_igv = $_PARAMETRO['IVACTADEF'];
		}
		
		//	inserto orden
		$sql = "INSERT INTO lg_ordencompra (
							Anio,
							CodOrganismo,
							NroOrden,
							Mes,
							Clasificacion,
							CodProveedor,
							NomProveedor,
							CodAlmacen,
							FechaPrometida,
							PreparadaPor,
							FechaPreparacion,
							CodTipoServicio,
							MontoBruto,
							MontoIGV,
							MontoTotal,
							MontoPendiente,
							MontoAfecto,
							MontoNoAfecto,
							CodFormaPago,
							CodAlmacenIngreso,
							NomContacto,
							FaxContacto,
							PlazoEntrega,
							DirEntrega,
							InsEntrega,
							Entregaren,
							Observaciones,
							CodCuenta,
							cod_partida,
							TipoClasificacion,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$anio."',
							'".$organismo."',
							'".$codigo."',
							'".$mes."',
							'".$clasificacion."',
							'".$codproveedor."',
							'".($nomproveedor)."',
							'".$almacen_entrega."',
							'".formatFechaAMD($fentrega)."',
							'".$preparadopor."',
							'".formatFechaAMD($fpreparado)."',
							'".$codservicio."',
							'".$monto_bruto."',
							'".$monto_impuestos."',
							'".$monto_total."',
							'".$monto_pendiente."',
							'".$monto_afecto."',
							'".$monto_noafecto."',
							'".$codformapago."',
							'".$almacen_ingreso."',
							'".$nomcontacto."',
							'".$faxcontacto."',
							'".$dias_entrega."',
							'".($direccion)."',
							'".($instruccion)."',
							'".($entregaren)."',
							'".($comentarios)."',
							'".$cuenta_igv."',
							'".$partida_igv."',
							'".$tipoclasificacion."',
							'".$_SESSION['USUARIO_ACTUAL']."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die($sql.mysql_error());
		
		//	detalles
		$secuencia = 0;
		$detalle = split(";", $detalles);
		foreach ($detalle as $linea) {
			$secuencia++;
			list($_codigo, $_descripcion, $_codunidad, $_cantidad, $_pu, $_descp, $_descf, $_flagexon, $_pu_total, $_total, $_fentrega, $_codccosto, $_codpartida, $_codcuenta, $_observaciones) = split("[|]", $linea);
			
			if ($tipoclasificacion == "") $_coditem = $_codigo;
			else list($_commoditysub, $_secuencia) = split("[.]", $_codigo);
			if ($_descp > 0) $_pu_descuento = $_pu + ($_pu * $_descp / 100);
			else $_pu_descuento = $_pu - $_descf;
			$_precio_cantidad = $_cantidad * $_pu_descuento;
			//	inserto orden detalles
			$sql = "INSERT INTO lg_ordencompradetalle (
								Anio,
								CodOrganismo,
								NroOrden,
								Secuencia,
								Mes,
								CodItem,
								CommoditySub,
								Descripcion,
								CodUnidad,
								CantidadPedida,
								PrecioUnit,
								PrecioCantidad,
								Total,
								DescuentoPorcentaje,
								DescuentoFijo,
								FlagExonerado,
								CodCentroCosto,
								Comentarios,
								FechaPrometida,
								CodCuenta,
								cod_partida,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$anio."',
								'".$organismo."',
								'".$codigo."',
								'".$secuencia."',
								'".$mes."',
								'".$_coditem."',
								'".$_commoditysub."',
								'".($_descripcion)."',
								'".$_codunidad."',
								'".$_cantidad."',
								'".$_pu."',
								'".$_precio_cantidad."',
								'".$_total."',
								'".$_descp."',
								'".$_descf."',
								'".$_flagexon."',
								'".$_codccosto."',
								'".($_observaciones)."',
								'".formatFechaAMD($_fentrega)."',
								'".$_codcuenta."',
								'".$_codpartida."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die($sql.mysql_error());
		}
		
		$i=0;
		//	distribucion
		$sql = "SELECT
					ocd.Anio,
					ocd.Mes,
					ocd.CodOrganismo,
					ocd.NroOrden,
					ocd.cod_partida,
					ocd.CodCuenta,
					SUM(PrecioCantidad) AS Monto,
					ocd.CodCentroCosto,
					oc.CodProveedor
				FROM
					lg_ordencompradetalle ocd
					INNER JOIN lg_ordencompra oc ON (ocd.CodOrganismo = oc.codOrganismo AND
													 ocd.NroOrden = oc.NroOrden)
				WHERE
					ocd.Anio = '".$anio."' AND
					ocd.CodOrganismo = '".$organismo."' AND
					ocd.NroOrden = '".$codigo."'
				GROUP BY cod_partida";
		$query_dis = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_dis = mysql_fetch_array($query_dis)) {
			$i++;
			//	inserto distribucion oc
			$sql = "INSERT INTO lg_distribucionoc (
								Anio,
								CodOrganismo,
								NroOrden,
								Secuencia,
								Mes,
								cod_partida,
								CodCuenta,
								Monto,
								CodCentroCosto,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$field_dis['Anio']."',
								'".$field_dis['CodOrganismo']."',
								'".$field_dis['NroOrden']."',
								'".$i."',
								'".$field_dis['Mes']."',
								'".$field_dis['cod_partida']."',
								'".$field_dis['CodCuenta']."',
								'".$field_dis['Monto']."',
								'".$field_dis['CodCentroCosto']."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			
			//	inserto distribucion compromisos
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
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$field_dis['Anio']."',
								'".$field_dis['CodOrganismo']."',
								(SELECT CodPresupuesto FROM `pv_presupuesto` WHERE EjercicioPpto = '".$anho."'),
								'".$field_dis['CodProveedor']."',
								'OC',
								'".$field_dis['NroOrden']."',
								'".$i."',
								'1',
								'".$field_dis['Mes']."',
								'".$field_dis['CodCentroCosto']."',
								'".$field_dis['cod_partida']."',
								'".$field_dis['Monto']."',
								NOW(),
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		}
		if ($monto_impuestos > 0) {
			//	distribucion (IGV)
			$sql = "SELECT
						Anio,
						Mes,
						CodOrganismo,
						NroOrden,
						cod_partida,
						CodCuenta,
						MontoIGV,
						CodProveedor
					FROM lg_ordencompra
					WHERE
						Anio = '".$anio."' AND
						CodOrganismo = '".$organismo."' AND
						NroOrden = '".$codigo."'
					GROUP BY cod_partida";
			$query_dis = mysql_query($sql) or die ($sql.mysql_error());
			while ($field_dis = mysql_fetch_array($query_dis)) {
				$i++;
				//	inserto distribucion oc igv
				$sql = "INSERT INTO lg_distribucionoc (
									Anio,
									CodOrganismo,
									NroOrden,
									Mes,
									Secuencia,
									cod_partida,
									CodCuenta,
									Monto,
									CodCentroCosto,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$field_dis['Anio']."',
									'".$field_dis['CodOrganismo']."',
									'".$field_dis['NroOrden']."',
									'".$field_dis['Mes']."',
									'".$i."',
									'".$field_dis['cod_partida']."',
									'".$field_dis['CodCuenta']."',
									'".$field_dis['MontoIGV']."',
									'".$_PARAMETRO['CCOSTOCOMPRA']."',
									'".$_SESSION['USUARIO_ACTUAL']."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
				
				//	inserto distribucion compromisos igv
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
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$field_dis['Anio']."',
									'".$field_dis['CodOrganismo']."',
									(SELECT CodPresupuesto FROM `pv_presupuesto` WHERE EjercicioPpto = '".$anho."'),
									'".$field_dis['CodProveedor']."',
									'OC',
									'".$field_dis['NroOrden']."',
									'".$i."',
									'1',
									'".$field_dis['Mes']."',
									'".$_PARAMETRO['CCOSTOCOMPRA']."',
									'".$field_dis['cod_partida']."',
									'".$field_dis['MontoIGV']."',
									NOW(),
									'".$_SESSION['USUARIO_ACTUAL']."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
		
		//	consulto compromisos
		$sql = "SELECT *
				FROM lg_distribucionoc
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$codigo."'
				ORDER BY Secuencia";
		$query_dis = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_dis = mysql_fetch_array($query_dis)) {
			//	actualizo presupuesto
			/*$sql = "UPDATE pv_presupuestodet
					SET MontoCompromiso = MontoCompromiso + ".$field_dis['Monto']."
					WHERE
						Organismo = '".$organismo."' AND
						CodPresupuesto = (SELECT CodPresupuesto FROM pv_presupuesto WHERE EjercicioPpto = '".$anio."') AND
						cod_partida = '".$field_dis['cod_partida']."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());*/
		}
		
		echo "|Se ha generado la Orden de Compra Nro. $codigo";
	}
	
	//	modificar
	elseif ($accion == "modificar") {
		//	consulto compromisos
		$sql = "SELECT *
				FROM lg_distribucionoc
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$codigo."'
				ORDER BY Secuencia";
		$query_dis = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_dis = mysql_fetch_array($query_dis)) {
			//	actualizo presupuesto
			/*$sql = "UPDATE pv_presupuestodet
					SET MontoCompromiso = MontoCompromiso - ".$field_dis['Monto']."
					WHERE
						Organismo = '".$organismo."' AND
						CodPresupuesto = (SELECT CodPresupuesto FROM pv_presupuesto WHERE EjercicioPpto = '".$anio."') AND
						cod_partida = '".$field_dis['cod_partida']."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());*/
		}
		
		//	elimino detalles
		$sql = "DELETE FROM lg_ordencompradetalle
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$codigo."'";
		$query_delete = mysql_query($sql) or die($sql.mysql_error());
		
		//	elimino compromisos
		$sql = "DELETE FROM lg_distribucionoc
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$codigo."'";
		$query_delete = mysql_query($sql) or die($sql.mysql_error());
		
		//	elimino compromisos
		$sql = "DELETE FROM lg_distribucioncompromisos
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					CodTipoDocumento = 'OC' AND
					NroDocumento = '".$codigo."'";
		$query_delete = mysql_query($sql) or die($sql.mysql_error());
		
		$monto_bruto = $monto_total - $monto_impuestos;
		if ($monto_impuestos != 0) {
			$partida_igv = $_PARAMETRO['IVADEFAULT'];
			$cuenta_igv = $_PARAMETRO['IVACTADEF'];
		}
		
		//	inserto orden
		$sql = "UPDATE lg_ordencompra
				SET
					Clasificacion = '".$clasificacion."',
					NomProveedor = '".($nomproveedor)."',
					CodAlmacen = '".$almacen_entrega."',
					FechaPrometida = '".formatFechaAMD($fentrega)."',
					MontoBruto = '".$monto_bruto."',
					MontoIGV = '".$monto_impuestos."',
					MontoTotal = '".$monto_total."',
					MontoPendiente = '".$monto_pendiente."',
					MontoAfecto = '".$monto_afecto."',
					MontoNoAfecto = '".$monto_noafecto."',
					CodFormaPago = '".$codformapago."',
					CodAlmacenIngreso = '".$almacen_ingreso."',
					NomContacto = '".($nomcontacto)."',
					FaxContacto = '".$faxcontacto."',
					PlazoEntrega = '".$dias_entrega."',
					DirEntrega = '".($direccion)."',
					InsEntrega = '".($instruccion)."',
					Entregaren = '".($entregaren)."',
					Observaciones = '".($comentarios)."',
					CodCuenta = '".$cuenta_igv."',
					cod_partida = '".$partida_igv."',
					TipoClasificacion = '".$tipoclasificacion."',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$codigo."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
		
		//	detalles
		$secuencia = 0;
		$detalle = split(";", $detalles);
		foreach ($detalle as $linea) {
			$secuencia++;
			list($_codigo, $_descripcion, $_codunidad, $_cantidad, $_pu, $_descp, $_descf, $_flagexon, $_pu_total, $_total, $_fentrega, $_codccosto, $_codpartida, $_codcuenta, $_observaciones) = split("[|]", $linea);
			
			if ($tipoclasificacion == "") $_coditem = $_codigo;
			else list($_commoditysub, $_secuencia) = split("[.]", $_codigo);
			$_precio_cantidad = $_cantidad * $_pu;
			//	inserto orden detalles
			$sql = "INSERT INTO lg_ordencompradetalle (
								Anio,
								CodOrganismo,
								NroOrden,
								Secuencia,
								Mes,
								CodItem,
								CommoditySub,
								Descripcion,
								CodUnidad,
								CantidadPedida,
								PrecioUnit,
								PrecioCantidad,
								Total,
								DescuentoPorcentaje,
								DescuentoFijo,
								FlagExonerado,
								CodCentroCosto,
								Comentarios,
								FechaPrometida,
								CodCuenta,
								cod_partida,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$anio."',
								'".$organismo."',
								'".$codigo."',
								'".$secuencia."',
								'".$mes."',
								'".$_coditem."',
								'".$_commoditysub."',
								'".($_descripcion)."',
								'".$_codunidad."',
								'".$_cantidad."',
								'".$_pu."',
								'".$_precio_cantidad."',
								'".$_total."',
								'".$_descp."',
								'".$_descf."',
								'".$_flagexon."',
								'".$_codccosto."',
								'".($_observaciones)."',
								'".formatFechaAMD($_fentrega)."',
								'".$_codcuenta."',
								'".$_codpartida."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die($sql.mysql_error());
		}
		
		$i=0;
		//	distribucion
		$sql = "SELECT
					ocd.Anio,
					ocd.Mes,
					ocd.CodOrganismo,
					ocd.NroOrden,
					ocd.cod_partida,
					ocd.CodCuenta,
					SUM(PrecioCantidad) AS Monto,
					ocd.CodCentroCosto,
					oc.CodProveedor
				FROM
					lg_ordencompradetalle ocd
					INNER JOIN lg_ordencompra oc ON (ocd.CodOrganismo = oc.codOrganismo AND
													 ocd.NroOrden = oc.NroOrden)
				WHERE
					ocd.Anio = '".$anio."' AND
					ocd.CodOrganismo = '".$organismo."' AND
					ocd.NroOrden = '".$codigo."'
				GROUP BY cod_partida";
		$query_dis = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_dis = mysql_fetch_array($query_dis)) {
			$i++;
			
			//	inserto distribucion oc
			$sql = "INSERT INTO lg_distribucionoc (
								Anio,
								CodOrganismo,
								NroOrden,
								Secuencia,
								Mes,
								cod_partida,
								CodCuenta,
								Monto,
								CodCentroCosto,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$field_dis['Anio']."',
								'".$field_dis['CodOrganismo']."',
								'".$field_dis['NroOrden']."',
								'".$i."',
								'".$field_dis['Mes']."',
								'".$field_dis['cod_partida']."',
								'".$field_dis['CodCuenta']."',
								'".$field_dis['Monto']."',
								'".$field_dis['CodCentroCosto']."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			
			//	inserto distribucion compromisos
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
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$field_dis['Anio']."',
								'".$field_dis['CodOrganismo']."',
								(SELECT CodPresupuesto FROM `pv_presupuesto` WHERE EjercicioPpto = '".$anho."'),
								'".$field_dis['CodProveedor']."',
								'OC',
								'".$field_dis['NroOrden']."',
								'".$i."',
								'1',
								'".$field_dis['Mes']."',
								'".$field_dis['CodCentroCosto']."',
								'".$field_dis['cod_partida']."',
								'".$field_dis['Monto']."',
								NOW(),
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		}
		if ($monto_impuestos > 0) {
			//	distribucion (IGV)
			$sql = "SELECT
						Anio,
						Mes,
						CodOrganismo,
						NroOrden,
						cod_partida,
						CodCuenta,
						MontoIGV,
						CodProveedor
					FROM lg_ordencompra
					WHERE
						Anio = '".$anio."' AND
						CodOrganismo = '".$organismo."' AND
						NroOrden = '".$codigo."'
					GROUP BY cod_partida";
			$query_dis = mysql_query($sql) or die ($sql.mysql_error());
			while ($field_dis = mysql_fetch_array($query_dis)) {
				$i++;
				//	inserto distribucion oc igv
				$sql = "INSERT INTO lg_distribucionoc (
									Anio,
									CodOrganismo,
									NroOrden,
									Mes,
									Secuencia,
									cod_partida,
									CodCuenta,
									Monto,
									CodCentroCosto,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$field_dis['Anio']."',
									'".$field_dis['CodOrganismo']."',
									'".$field_dis['NroOrden']."',
									'".$field_dis['Mes']."',
									'".$i."',
									'".$field_dis['cod_partida']."',
									'".$field_dis['CodCuenta']."',
									'".$field_dis['MontoIGV']."',
									'".$_PARAMETRO['CCOSTOCOMPRA']."',
									'".$_SESSION['USUARIO_ACTUAL']."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
				
				//	inserto distribucion compromisos igv
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
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$field_dis['Anio']."',
									'".$field_dis['CodOrganismo']."',
									(SELECT CodPresupuesto FROM `pv_presupuesto` WHERE EjercicioPpto = '".$anho."'),
									'".$field_dis['CodProveedor']."',
									'OC',
									'".$field_dis['NroOrden']."',
									'".$i."',
									'1',
									'".$field_dis['Mes']."',
									'".$_PARAMETRO['CCOSTOCOMPRA']."',
									'".$field_dis['cod_partida']."',
									'".$field_dis['MontoIGV']."',
									NOW(),
									'".$_SESSION['USUARIO_ACTUAL']."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
		
		//	consulto compromisos
		$sql = "SELECT *
				FROM lg_distribucionoc
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$codigo."'
				ORDER BY Secuencia";
		$query_dis = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_dis = mysql_fetch_array($query_dis)) {
			//	actualizo presupuesto
			/*$sql = "UPDATE pv_presupuestodet
					SET MontoCompromiso = MontoCompromiso + ".$field_dis['Monto']."
					WHERE
						Organismo = '".$organismo."' AND
						CodPresupuesto = (SELECT CodPresupuesto FROM pv_presupuesto WHERE EjercicioPpto = '".$anio."') AND
						cod_partida = '".$field_dis['cod_partida']."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());*/
		}
		
		echo "|";
	}
	
	//	revisar
	elseif ($accion == "revisar") {
		//	actualizo
		$sql = "UPDATE lg_ordencompra
				SET
					Estado = 'RV',
					RevisadaPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaRevision = '".date("Y-m-d")."',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$codigo."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
		
		echo "|";
	}
	
	//	rechazar
	elseif ($accion == "rechazar") {
		//	actualizo
		$sql = "UPDATE lg_ordencompra
				SET
					Estado = 'RE',
					MotRechazo = '".($razon)."',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$codigo."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
		
		//	actualizo
		$sql = "UPDATE lg_ordencompradetalle
				SET
					Estado = 'RE',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$codigo."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
		
		//	actualizo
		$sql = "UPDATE lg_distribucioncompromisos
				SET
					Estado = 'RE',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					CodOrganismo = '".$codproveedor."' AND
					CodTipoDocumento = 'OC' AND
					NroDocumento = '".$codigo."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
		
		//	consulto compromisos
		$sql = "SELECT *
				FROM lg_distribucionoc
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$codigo."'
				ORDER BY Secuencia";
		$query_dis = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_dis = mysql_fetch_array($query_dis)) {
			//	actualizo presupuesto
			/*$sql = "UPDATE pv_presupuestodet
					SET MontoCompromiso = MontoCompromiso - ".$field_dis['Monto']."
					WHERE
						Organismo = '".$organismo."' AND
						CodPresupuesto = (SELECT CodPresupuesto FROM pv_presupuesto WHERE EjercicioPpto = '".$anio."') AND
						cod_partida = '".$field_dis['cod_partida']."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());*/
		}
		
		echo "|";
	}
	
	//	aprobar
	elseif ($accion == "aprobar") {
		//	actualizo orden
		$sql = "UPDATE lg_ordencompra
				SET
					Estado = 'AP',
					AprobadaPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaAprobacion = '".date("Y-m-d")."',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$codigo."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
		
		//	actualizo orden (detalles)
		$sql = "UPDATE lg_ordencompradetalle
				SET
					Estado = 'PE',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$codigo."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
		
		echo "|";
	}
	
	//	anular
	elseif ($accion == "anular") {
		if ($estado == "PR") $accion_estado = "AN";
		else $accion_estado = "PR";
		
		//	actualizo
		$sql = "UPDATE lg_ordencompra
				SET
					Estado = '".$accion_estado."',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$codigo."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
		
		//	actualizo
		$sql = "UPDATE lg_ordencompradetalle
				SET
					Estado = '".$accion_estado."',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$codigo."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
		
		if ($accion_estado == "AN") {
			//	actualizo
			$sql = "UPDATE lg_distribucioncompromisos
					SET
						Estado = 'AN',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()
					WHERE
						Anio = '".$anio."' AND
						CodOrganismo = '".$organismo."' AND
						CodOrganismo = '".$codproveedor."' AND
						CodTipoDocumento = 'OC' AND
						NroDocumento = '".$codigo."'";
			$query_update = mysql_query($sql) or die($sql.mysql_error());
			
			//	consulto compromisos
			$sql = "SELECT *
					FROM lg_distribucionoc
					WHERE
						Anio = '".$anio."' AND
						CodOrganismo = '".$organismo."' AND
						NroOrden = '".$codigo."'
					ORDER BY Secuencia";
			$query_dis = mysql_query($sql) or die ($sql.mysql_error());
			while ($field_dis = mysql_fetch_array($query_dis)) {
				//	actualizo presupuesto
			/*	$sql = "UPDATE pv_presupuestodet
						SET MontoCompromiso = MontoCompromiso - ".$field_dis['Monto']."
						WHERE
							Organismo = '".$organismo."' AND
							CodPresupuesto = (SELECT CodPresupuesto FROM pv_presupuesto WHERE EjercicioPpto = '".$anio."') AND
							cod_partida = '".$field_dis['cod_partida']."'";
				$query_update = mysql_query($sql) or die ($sql.mysql_error());*/
			}
		}
		
		echo "|";
	}
	
	//	cerrar
	elseif ($accion == "cerrar") {
		//	actualizo
		$sql = "UPDATE lg_ordencompra
				SET
					Estado = 'CE',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$codigo."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
		
		//	actualizo
		$sql = "UPDATE lg_ordencompradetalle
				SET
					Estado = 'CE',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$codigo."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
		
		echo "|";
	}
	
	//	cerrar detalle
	elseif ($accion == "cerrar_detalle") {
		list($anio, $organismo, $codigo, $secuencia) = split("[.]", $registro);
		
		//	verifico el estado del detalle
		$sql = "SELECT *
				FROM lg_ordencompradetalle
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$codigo."' AND
					Secuencia = '".$secuencia."'";
		$query_detalle = mysql_query($sql) or die($sql.mysql_error());
		if (mysql_num_rows($query_detalle) != 0) die("¡ERROR: El detalle ya se encuentra cerrado!");
				
		//	actualizo el detalle
		$sql = "UPDATE lg_ordencompradetalle
				SET
					Estado = 'CE',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$codigo."' AND
					Secuencia = '".$secuencia."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
		
		//	verifico si todos los detalles etan cerrados
		$sql = "SELECT *
				FROM lg_ordencompradetalle
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$codigo."' AND
					Estado != 'CE'";
		$query_detalle = mysql_query($sql) or die($sql.mysql_error());
		if (mysql_num_rows($query_detalle) == 0) {
			//	actualizo a completado la orden
			$sql = "UPDATE lg_ordencompra
					SET
						Estado = 'CO',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()
					WHERE
						Anio = '".$anio."' AND
						CodOrganismo = '".$organismo."' AND
						NroOrden = '".$codigo."'";
			$query_update = mysql_query($sql) or die($sql.mysql_error());
		}
	}
}

//	servicios
elseif ($modulo == "servicios") {
	//	nuevo
	if ($accion == "nuevo") {
		//	verifico disponibilidad
		$disponible = verificarDisponibilidadPresupuestariaOS($anio, $organismo, $codigo, $detalles, $monto_impuestos);
		if (!$disponible) die("¡ERROR: Se encontrarón lineas del detalle sin disponibilidad presupuestaria!");
		
		//	genero codigo
		$codigo = getCodigo_3("lg_ordenservicio", "NroOrden", "CodOrganismo", "Anio", $organismo, $anio, 10);
		
		//	obtengo el codigo de la partida y la cuenta igv
		$monto_bruto = $monto_total - $monto_impuestos;
		if ($monto_impuestos != 0) {
			$partida_igv = $_PARAMETRO['IVADEFAULT'];
			$cuenta_igv = $_PARAMETRO['IVACTADEF'];
		}
		
		//	inserto orden
		$sql = "INSERT INTO lg_ordenservicio (
							Anio,
							CodOrganismo,
							NroOrden,
							Mes,
							CodDependencia,
							CodProveedor,
							NomProveedor,
							CodFormaPago,
							NroInterno,
							FechaDocumento,
							DiasPago,
							CodTipoPago,
							CodTipoServicio,
							PlazoEntrega,
							FechaEntrega,
							MontoOriginal,
							MontoIva,
							TotalMontoIva,
							Descripcion,
							DescAdicional,
							MotRechazo,
							Observaciones,
							FechaValidoDesde,
							FechaValidoHasta,
							CodCentroCosto,
							PreparadaPor,
							FechaPreparacion,
							CodCuenta,
							cod_partida,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$anio."',
							'".$organismo."',
							'".$codigo."',
							'".$mes."',
							'".$dependencia."',
							'".$codproveedor."',
							'".($nomproveedor)."',
							'".$codformapago."',
							'".$nrointerno."',
							NOW(),
							'".$dias_pagar."',
							'".$codtipopago."',
							'".$codservicio."',
							'".$dias_entrega."',
							'".formatFechaAMD($fentrega)."',
							'".$monto_original."',
							'".$monto_impuestos."',
							'".$monto_total."',
							'".($descripcion)."',
							'".($descadicional)."',
							'".($razon)."',
							'".($observaciones)."',							
							'".formatFechaAMD($desde)."',							
							'".formatFechaAMD($hasta)."',
							'".$ccosto."',							
							'".$preparadopor."',
							'".formatFechaAMD($fpreparado)."',
							'".$cuenta_igv."',
							'".$partida_igv."',
							'".$_SESSION['USUARIO_ACTUAL']."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die($sql.mysql_error());
		
		//	detalles
		$secuencia = 0;
		$detalle = split(";", $detalles);
		foreach ($detalle as $linea) {
			$secuencia++;
			list($_codigo, $_descripcion, $_cantidad, $_pu, $_flagexon, $_total, $_fesperada, $_freal, $_codccosto, $_activo, $_flagterminado, $_codpartida, $_codcuenta, $_observaciones) = split("[|]", $linea);
			list($_commoditysub, $_secuencia) = split("[.]", $_codigo);
			
			//	inserto orden detalles
			$sql = "INSERT INTO lg_ordenserviciodetalle (
								Anio,
								CodOrganismo,
								NroOrden,
								Secuencia,
								Mes,
								CommoditySub,
								Descripcion,
								CantidadPedida,
								PrecioUnit,
								Total,
								FlagExonerado,
								FechaEsperadaTermino,
								FechaTermino,
								CodCentroCosto,
								NroActivo,
								Comentarios,
								cod_partida,
								CodCuenta,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$anio."',
								'".$organismo."',
								'".$codigo."',
								'".$secuencia."',
								'".$mes."',
								'".$_commoditysub."',
								'".($_descripcion)."',
								'".$_cantidad."',
								'".$_pu."',
								'".$_total."',
								'".$_flagexon."',
								'".formatFechaAMD($_fesperada)."',
								'".formatFechaAMD($_freal)."',
								'".$_codccosto."',
								'".$_activo."',
								'".($_observaciones)."',
								'".$_codpartida."',
								'".$_codcuenta."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die($sql.mysql_error());
		}
		
		$i=0;
		//	distribucion
		$sql = "SELECT
					osd.Anio,
					osd.Mes,
					osd.CodOrganismo,
					osd.NroOrden,
					osd.cod_partida,
					osd.CodCuenta,
					SUM(osd.CantidadPedida*osd.PrecioUnit) AS Monto,
					osd.CodCentroCosto,
					os.CodProveedor
				FROM
					lg_ordenserviciodetalle osd
					INNER JOIN lg_ordenservicio os ON (osd.CodOrganismo = os.codOrganismo AND
													   osd.NroOrden = os.NroOrden)
				WHERE
					osd.Anio = '".$anio."' AND
					osd.CodOrganismo = '".$organismo."' AND
					osd.NroOrden = '".$codigo."'
				GROUP BY cod_partida";
		$query_dis = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_dis = mysql_fetch_array($query_dis)) {
			$i++;			
			//	inserto distribucion os
			$sql = "INSERT INTO lg_distribucionos (
								Anio,
								CodOrganismo,
								NroOrden,
								Secuencia,
								Mes,
								cod_partida,
								CodCuenta,
								Monto,
								CodCentroCosto,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$field_dis['Anio']."',
								'".$field_dis['CodOrganismo']."',
								'".$field_dis['NroOrden']."',
								'".$i."',
								'".$field_dis['Mes']."',
								'".$field_dis['cod_partida']."',
								'".$field_dis['CodCuenta']."',
								'".$field_dis['Monto']."',
								'".$field_dis['CodCentroCosto']."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			
			//	inserto distribucion compromisos
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
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$field_dis['Anio']."',
								'".$field_dis['CodOrganismo']."',
								(SELECT CodPresupuesto FROM `pv_presupuesto` WHERE EjercicioPpto = '".$anho."'),
								'".$field_dis['CodProveedor']."',
								'OS',
								'".$field_dis['NroOrden']."',
								'".$i."',
								'1',
								'".$field_dis['Mes']."',
								'".$field_dis['CodCentroCosto']."',
								'".$field_dis['cod_partida']."',
								'".$field_dis['Monto']."',
								NOW(),
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		}
		if ($monto_impuestos > 0) {
			//	distribucion (IGV)
			$sql = "SELECT
						Anio,
						Mes,
						CodOrganismo,
						NroOrden,
						cod_partida,
						CodCuenta,
						MontoIva AS Monto,
						CodProveedor,
						CodCentroCosto
					FROM lg_ordenservicio
					WHERE
						Anio = '".$anio."' AND
						CodOrganismo = '".$organismo."' AND
						NroOrden = '".$codigo."'
					GROUP BY cod_partida";
			$query_dis = mysql_query($sql) or die ($sql.mysql_error());
			while ($field_dis = mysql_fetch_array($query_dis)) {
				$i++;
				//	inserto distribucion os igv
				$sql = "INSERT INTO lg_distribucionos (
									Anio,
									CodOrganismo,
									NroOrden,
									Mes,
									Secuencia,
									cod_partida,
									CodCuenta,
									Monto,
									CodCentroCosto,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$field_dis['Anio']."',
									'".$field_dis['CodOrganismo']."',
									'".$field_dis['NroOrden']."',
									'".$field_dis['Mes']."',
									'".$i."',
									'".$field_dis['cod_partida']."',
									'".$field_dis['CodCuenta']."',
									'".$field_dis['Monto']."',
									'".$field_dis['CodCentroCosto']."',
									'".$_SESSION['USUARIO_ACTUAL']."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
				
				//	inserto distribucion compromisos igv
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
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$field_dis['Anio']."',
									'".$field_dis['CodOrganismo']."',
									(SELECT CodPresupuesto FROM `pv_presupuesto` WHERE EjercicioPpto = '".$anho."'),
									'".$field_dis['CodProveedor']."',
									'OS',
									'".$field_dis['NroOrden']."',
									'".$i."',
									'1',
									'".$field_dis['Mes']."',
									'".$field_dis['CodCentroCosto']."',
									'".$field_dis['cod_partida']."',
									'".$field_dis['Monto']."',
									NOW(),
									'".$_SESSION['USUARIO_ACTUAL']."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
		
		//	consulto compromisos
		$sql = "SELECT *
				FROM lg_distribucionos
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$codigo."'
				ORDER BY Secuencia";
		$query_dis = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_dis = mysql_fetch_array($query_dis)) {
			//	actualizo presupuesto
			/*$sql = "UPDATE pv_presupuestodet
					SET MontoCompromiso = MontoCompromiso + ".$field_dis['Monto']."
					WHERE
						Organismo = '".$organismo."' AND
						CodPresupuesto = (SELECT CodPresupuesto FROM pv_presupuesto WHERE EjercicioPpto = '".$anio."') AND
						cod_partida = '".$field_dis['cod_partida']."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());*/
		}
		
		echo "|Se ha generado la Orden de Servicio Nro. $codigo";
	}
	
	//	modificar
	elseif ($accion == "modificar") {
		//	consulto compromisos
		$sql = "SELECT *
				FROM lg_distribucionos
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$codigo."'
				ORDER BY Secuencia";
		$query_dis = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_dis = mysql_fetch_array($query_dis)) {
			//	actualizo presupuesto
			/*$sql = "UPDATE pv_presupuestodet
					SET MontoCompromiso = MontoCompromiso - ".$field_dis['Monto']."
					WHERE
						Organismo = '".$organismo."' AND
						CodPresupuesto = (SELECT CodPresupuesto FROM pv_presupuesto WHERE EjercicioPpto = '".$anio."') AND
						cod_partida = '".$field_dis['cod_partida']."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());*/
		}
		
		//	elimino detalles
		$sql = "DELETE FROM lg_ordenserviciodetalle
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$codigo."'";
		$query_delete = mysql_query($sql) or die($sql.mysql_error());
		
		//	elimino compromisos
		$sql = "DELETE FROM lg_distribucionos
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$codigo."'";
		$query_delete = mysql_query($sql) or die($sql.mysql_error());
		
		//	elimino compromisos
		$sql = "DELETE FROM lg_distribucioncompromisos
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					CodTipoDocumento = 'OS' AND
					NroDocumento = '".$codigo."'";
		$query_delete = mysql_query($sql) or die($sql.mysql_error());
		
		//	verifico disponibilidad
		$disponible = verificarDisponibilidadPresupuestariaOS($anio, $organismo, $codigo, $detalles, $monto_impuestos);
		if (!$disponible) die("¡ERROR: Se encontrarón lineas del detalle sin disponibilidad presupuestaria!");
		
		//	obtengo el codigo de la partida y la cuenta igv
		if ($monto_impuestos != 0) {
			$partida_igv = $_PARAMETRO['IVADEFAULT'];
			$cuenta_igv = $_PARAMETRO['IVACTADEF'];
		}
		
		//	actualizo orden
		$sql = "UPDATE lg_ordenservicio
				SET
					NomProveedor = '".($nomproveedor)."',
					FechaEntrega = '".formatFechaAMD($fentrega)."',
					MontoOriginal = '".$monto_bruto."',
					MontoIva = '".$monto_impuestos."',
					TotalMontoIva = '".$monto_total."',
					CodFormaPago = '".$codformapago."',
					CodTipoPago = '".$codtipopago."',
					DiasPago = '".$dias_pagar."',
					PlazoEntrega = '".$dias_entrega."',					
					Observaciones = '".($descripcion)."',					
					Observaciones = '".($descadicional)."',					
					Observaciones = '".($razon)."',					
					Observaciones = '".($observaciones)."',
					FechaValidoDesde = '".formatFechaAMD($desde)."',
					FechaValidoHasta = '".formatFechaAMD($hasta)."',
					CodCuenta = '".$cuenta_igv."',
					cod_partida = '".$partida_igv."',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$codigo."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
		
		//	detalles
		$secuencia = 0;
		$detalle = split(";", $detalles);
		foreach ($detalle as $linea) {
			$secuencia++;
			list($_codigo, $_descripcion, $_cantidad, $_pu, $_flagexon, $_total, $_fesperada, $_freal, $_codccosto, $_activo, $_flagterminado, $_codpartida, $_codcuenta, $_observaciones) = split("[|]", $linea);
			list($_commoditysub, $_secuencia) = split("[.]", $_codigo);
			
			//	inserto orden detalles
			$sql = "INSERT INTO lg_ordenserviciodetalle (
								Anio,
								CodOrganismo,
								NroOrden,
								Secuencia,
								Mes,
								CommoditySub,
								Descripcion,
								CantidadPedida,
								PrecioUnit,
								Total,
								FlagExonerado,
								FechaEsperadaTermino,
								FechaTermino,
								CodCentroCosto,
								NroActivo,
								Comentarios,
								cod_partida,
								CodCuenta,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$anio."',
								'".$organismo."',
								'".$codigo."',
								'".$secuencia."',
								'".$mes."',
								'".$_commoditysub."',
								'".($_descripcion)."',
								'".$_cantidad."',
								'".$_pu."',
								'".$_total."',
								'".$_flagexon."',
								'".formatFechaAMD($_fesperada)."',
								'".formatFechaAMD($_freal)."',
								'".$_codccosto."',
								'".$_activo."',
								'".($_observaciones)."',
								'".$_codpartida."',
								'".$_codcuenta."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die($sql.mysql_error());
		}
		
		$i=0;
		//	distribucion
		$sql = "SELECT
					osd.Anio,
					osd.Mes,
					osd.CodOrganismo,
					osd.NroOrden,
					osd.cod_partida,
					osd.CodCuenta,
					SUM(osd.CantidadPedida*osd.PrecioUnit) AS Monto,
					osd.CodCentroCosto,
					os.CodProveedor
				FROM
					lg_ordenserviciodetalle osd
					INNER JOIN lg_ordenservicio os ON (osd.CodOrganismo = os.codOrganismo AND
													   osd.NroOrden = os.NroOrden)
				WHERE
					osd.Anio = '".$anio."' AND
					osd.CodOrganismo = '".$organismo."' AND
					osd.NroOrden = '".$codigo."'
				GROUP BY cod_partida";
		$query_dis = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_dis = mysql_fetch_array($query_dis)) {
			$i++;			
			//	inserto distribucion os
			$sql = "INSERT INTO lg_distribucionos (
								Anio,
								CodOrganismo,
								NroOrden,
								Secuencia,
								Mes,
								cod_partida,
								CodCuenta,
								Monto,
								CodCentroCosto,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$field_dis['Anio']."',
								'".$field_dis['CodOrganismo']."',
								'".$field_dis['NroOrden']."',
								'".$i."',
								'".$field_dis['Mes']."',
								'".$field_dis['cod_partida']."',
								'".$field_dis['CodCuenta']."',
								'".$field_dis['Monto']."',
								'".$field_dis['CodCentroCosto']."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			
			//	inserto distribucion compromisos
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
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$field_dis['Anio']."',
								'".$field_dis['CodOrganismo']."',
								(SELECT CodPresupuesto FROM `pv_presupuesto` WHERE EjercicioPpto = '".$anho."'),
								'".$field_dis['CodProveedor']."',
								'OS',
								'".$field_dis['NroOrden']."',
								'".$i."',
								'1',
								'".$field_dis['Mes']."',
								'".$field_dis['CodCentroCosto']."',
								'".$field_dis['cod_partida']."',
								'".$field_dis['Monto']."',
								NOW(),
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		}
		if ($monto_impuestos > 0) {
			//	distribucion (IGV)
			$sql = "SELECT
						Anio,
						Mes,
						CodOrganismo,
						NroOrden,
						cod_partida,
						CodCuenta,
						MontoIva AS Monto,
						CodProveedor,
						CodCentroCosto
					FROM lg_ordenservicio
					WHERE
						Anio = '".$anio."' AND
						CodOrganismo = '".$organismo."' AND
						NroOrden = '".$codigo."'
					GROUP BY cod_partida";
			$query_dis = mysql_query($sql) or die ($sql.mysql_error());
			while ($field_dis = mysql_fetch_array($query_dis)) {
				$i++;
				//	inserto distribucion os igv
				$sql = "INSERT INTO lg_distribucionos (
									Anio,
									CodOrganismo,
									NroOrden,
									Mes,
									Secuencia,
									cod_partida,
									CodCuenta,
									Monto,
									CodCentroCosto,
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$field_dis['Anio']."',
									'".$field_dis['CodOrganismo']."',
									'".$field_dis['NroOrden']."',
									'".$field_dis['Mes']."',
									'".$i."',
									'".$field_dis['cod_partida']."',
									'".$field_dis['CodCuenta']."',
									'".$field_dis['Monto']."',
									'".$field_dis['CodCentroCosto']."',
									'".$_SESSION['USUARIO_ACTUAL']."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
				
				//	inserto distribucion compromisos igv
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
									UltimoUsuario,
									UltimaFecha
						) VALUES (
									'".$field_dis['Anio']."',
									'".$field_dis['CodOrganismo']."',
									(SELECT CodPresupuesto FROM `pv_presupuesto` WHERE EjercicioPpto = '".$anho."'),
									'".$field_dis['CodProveedor']."',
									'OS',
									'".$field_dis['NroOrden']."',
									'".$i."',
									'1',
									'".$field_dis['Mes']."',
									'".$field_dis['CodCentroCosto']."',
									'".$field_dis['cod_partida']."',
									'".$field_dis['Monto']."',
									NOW(),
									'".$_SESSION['USUARIO_ACTUAL']."',
									NOW()
						)";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
		
		//	consulto compromisos
		$sql = "SELECT *
				FROM lg_distribucionos
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$codigo."'
				ORDER BY Secuencia";
		$query_dis = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_dis = mysql_fetch_array($query_dis)) {
			//	actualizo presupuesto
		/*	$sql = "UPDATE pv_presupuestodet
					SET MontoCompromiso = MontoCompromiso + ".$field_dis['Monto']."
					WHERE
						Organismo = '".$organismo."' AND
						CodPresupuesto = (SELECT CodPresupuesto FROM pv_presupuesto WHERE EjercicioPpto = '".$anio."') AND
						cod_partida = '".$field_dis['cod_partida']."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());*/
		}
		
		echo "|";
	}
	
	//	revisar
	elseif ($accion == "revisar") {
		//	actualizo
		$sql = "UPDATE lg_ordenservicio
				SET
					Estado = 'RV',
					RevisadaPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaRevision = '".date("Y-m-d")."',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$codigo."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
		
		echo "|";
	}
	
	//	rechazar
	elseif ($accion == "rechazar") {
		//	actualizo
		$sql = "UPDATE lg_ordenservicio
				SET
					Estado = 'RE',
					MotRechazo = '".($razon)."',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$codigo."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
		
		//	actualizo
		$sql = "UPDATE lg_ordenserviciodetalle
				SET
					FlagTerminado = 'S',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$codigo."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
		
		//	actualizo
		$sql = "UPDATE lg_distribucioncompromisos
				SET
					Estado = 'RE',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					CodOrganismo = '".$codproveedor."' AND
					CodTipoDocumento = 'OC' AND
					NroDocumento = '".$codigo."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
		
		//	consulto compromisos
		$sql = "SELECT *
				FROM lg_distribucionos
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$codigo."'
				ORDER BY Secuencia";
		$query_dis = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_dis = mysql_fetch_array($query_dis)) {
			//	actualizo presupuesto
			/*$sql = "UPDATE pv_presupuestodet
					SET MontoCompromiso = MontoCompromiso - ".$field_dis['Monto']."
					WHERE
						Organismo = '".$organismo."' AND
						CodPresupuesto = (SELECT CodPresupuesto FROM pv_presupuesto WHERE EjercicioPpto = '".$anio."') AND
						cod_partida = '".$field_dis['cod_partida']."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());*/
		}
		
		echo "|";
	}
	
	//	aprobar
	elseif ($accion == "aprobar") {
		//	actualizo orden
		$sql = "UPDATE lg_ordenservicio
				SET
					Estado = 'AP',
					AprobadaPor = '".$_SESSION["CODPERSONA_ACTUAL"]."',
					FechaAprobacion = '".date("Y-m-d")."',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$codigo."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
		
		echo "|";
	}
	
	//	anular
	elseif ($accion == "anular") {
		if ($estado == "PR") { $accion_estado = "AN"; $flag = "S"; }
		else { $accion_estado = "PR"; $flag = "N"; }
		
		//	actualizo
		$sql = "UPDATE lg_ordenservicio
				SET
					Estado = '".$accion_estado."',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$codigo."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
		
		//	actualizo
		$sql = "UPDATE lg_ordenserviciodetalle
				SET
					FlagTerminado = '".$flag."',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$codigo."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
		
		if ($accion_estado == "AN") {
			//	actualizo
			$sql = "UPDATE lg_distribucioncompromisos
					SET
						Estado = 'AN',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()
					WHERE
						Anio = '".$anio."' AND
						CodOrganismo = '".$organismo."' AND
						CodOrganismo = '".$codproveedor."' AND
						CodTipoDocumento = 'OC' AND
						NroDocumento = '".$codigo."'";
			$query_update = mysql_query($sql) or die($sql.mysql_error());
			
			//	consulto compromisos
			$sql = "SELECT *
					FROM lg_distribucionos
					WHERE
						Anio = '".$anio."' AND
						CodOrganismo = '".$organismo."' AND
						NroOrden = '".$codigo."'
					ORDER BY Secuencia";
			$query_dis = mysql_query($sql) or die ($sql.mysql_error());
			while ($field_dis = mysql_fetch_array($query_dis)) {
				//	actualizo presupuesto
				/*$sql = "UPDATE pv_presupuestodet
						SET MontoCompromiso = MontoCompromiso - ".$field_dis['Monto']."
						WHERE
							Organismo = '".$organismo."' AND
							CodPresupuesto = (SELECT CodPresupuesto FROM pv_presupuesto WHERE EjercicioPpto = '".$anio."') AND
							cod_partida = '".$field_dis['cod_partida']."'";
				$query_update = mysql_query($sql) or die ($sql.mysql_error());*/
			}
		}
		
		echo "|";
	}
	
	//	cerrar
	elseif ($accion == "cerrar") {
		//	actualizo
		$sql = "UPDATE lg_ordenservicio
				SET
					Estado = 'CE',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$codigo."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
		
		//	actualizo
		$sql = "UPDATE lg_ordenserviciodetalle
				SET
					FlagTerminado = 'S',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$codigo."'";
		$query_update = mysql_query($sql) or die($sql.mysql_error());
		
		echo "|";
	}
	
	//	confirmar
	elseif ($accion == "confirmar") {
		if ($saldo > 0) $flagterminado = "N"; else $flagterminado = "S";
		
		//	inserto la confirmacion
		$nroconfirmacion = getCodigo("lg_confirmacionservicio", "NroConfirmacion", 4);
		$referencia = "$nroorden-$nroconfirmacion";
		$sql = "INSERT INTO lg_confirmacionservicio (
							NroConfirmacion,
							Anio,
							CodOrganismo,
							NroOrden,
							Secuencia,
							DocumentoReferencia,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$nroconfirmacion."',
							'".$anio."',
							'".$organismo."',
							'".$nroorden."',
							'".$secuencia."',
							'".$referencia."',
							'".$_SESSION['USUARIO_ACTUAL']."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		
		//	actualizo el detalle de la orden
		$sql = "UPDATE lg_ordenserviciodetalle 
				SET
					ConfirmadoPor = '".$_SESSION['CODPERSONA_ACTUAL']."',
					FlagTerminado = '".$flagterminado."',
					CantidadRecibida = (CantidadRecibida + ".floatval($cantidad_recibir)."),
					FechaTermino = '".formatFechaAMD($ftermino)."',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$nroorden."' AND
					Secuencia = '".$secuencia."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
		
		//	consulto si todos los detalles estan terminados
		$sql = "SELECT *
				FROM lg_ordenserviciodetalle
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$nroorden."' AND
					FlagTerminado <> 'S'";
		$query_osd = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_osd) == 0) {
			$sql = "UPDATE lg_ordenservicio
					SET Estado = 'CO'
					WHERE
						Anio = '".$anio."' AND
						CodOrganismo = '".$organismo."' AND
						NroOrden = '".$nroorden."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
		}
		
		// consulto para el documento
		$sql = "SELECT
					osd.CommoditySub,
					osd.Descripcion,
					osd.PrecioUnit,
					($cantidad_recibir * osd.PrecioUnit) AS PrecioCantidad,
					osd.CodCentroCosto,
					os.CodProveedor,
					i.FactorPorcentaje
				FROM
					lg_ordenserviciodetalle osd
					INNER JOIN lg_ordenservicio os ON (os.CodOrganismo = osd.CodOrganismo AND os.NroOrden = osd.NroOrden)
					INNER JOIN mastproveedores pr ON (os.CodProveedor = pr.CodProveedor)
					LEFT JOIN mastformapago fp ON (pr.CodFormaPago = fp.CodFormaPago)
					LEFT JOIN masttiposervicioimpuesto tsi ON (os.CodTipoServicio = tsi.CodTipoServicio)
					LEFT JOIN mastimpuestos i ON (tsi.CodImpuesto = tsi.CodImpuesto)
				WHERE
					osd.Anio = '".$anio."' AND
					osd.CodOrganismo = '".$organismo."' AND
					osd.NroOrden = '".$nroorden."' AND
					osd.Secuencia = '".$secuencia."'";
		$query_os = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_os) != 0) $field_os = mysql_fetch_array($query_os);
		$monto_iva = $field_os['PrecioCantidad'] * $field_os['FactorPorcentaje'] / 100;
		$monto_total = $monto_iva + $field_os['PrecioCantidad'];
		//	inserto el documento
		$sql = "INSERT INTO ap_documentos (
							Anio,
							CodOrganismo,
							CodProveedor,
							DocumentoClasificacion,
							DocumentoReferencia,
							Fecha,
							ReferenciaTipoDocumento,
							ReferenciaNroDocumento,
							MontoAfecto,
							MontoImpuestos,
							MontoTotal,
							MontoPendiente,
							Estado,
							Comentarios,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$anio."',
							'".$organismo."',
							'".$field_os['CodProveedor']."',
							'SER',
							'".$nroorden."-".$nroconfirmacion."',
							'".formatFechaAMD($ftermino)."',
							'OS',
							'".$nroorden."',
							'".$field_os['PrecioCantidad']."',
							'".$monto_iva."',
							'".$monto_total."',
							'".$monto_total."',
							'PR',
							'".$field_os['Descripcion']."',
							'".$_SESSION['USUARIO_ACTUAL']."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		
		//	inserto los detalles de el documento
		$sql = "INSERT INTO ap_documentosdetalle (
							Anio,
							CodProveedor,
							DocumentoClasificacion,
							DocumentoReferencia,
							Secuencia,
							ReferenciaSecuencia,
							CommoditySub,
							Descripcion,
							Cantidad,
							PrecioUnit,
							PrecioCantidad,
							Total,
							CodCentroCosto,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$anio."',
							'".$field_os['CodProveedor']."',
							'SER',
							'".$nroorden."-".$nroconfirmacion."',
							'".$secuencia."',
							'1',
							'".$field_os['CommoditySub']."',
							'".$field_os['Descripcion']."',
							'".$cantidad_recibir."',
							'".$field_os['PrecioUnit']."',
							'".$field_os['PrecioCantidad']."',
							'".$monto_total."',
							'".$field_os['CodCentroCosto']."',
							'".$_SESSION['USUARIO_ACTUAL']."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		
		echo "|$nroconfirmacion";
	}
	
	//	desconfirmar
	elseif ($accion == "desconfirmar") {
		list($anio, $organismo, $referencia) = split("[.]", $registro);
		list($nroorden, $nroconfirmacion) = split("[-]", $referencia);
		
		//	compruebo estado
		$sql = "SELECT Estado
				FROM ap_documentos
				WHERE
					Anio = '".$anio."' AND
					CodProveedor = '".$field_os['CodProveedor']."' AND
					DocumentoClasificacion = 'SER' AND
					DocumentoReferencia = '$nroorden-$nroconfirmacion' AND
					Estado = 'PR'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_doc) == 0) die("¡ERROR: El documento no puede ser desconfirmado!");
		
		// consulto para el documento
		$sql = "SELECT CodProveedor					
				FROM lg_ordenservicio
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$nroorden."'";
		$query_os = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_os) != 0) $field_os = mysql_fetch_array($query_os);
		
		// consulto para el documento
		$sql = "SELECT * FROM lg_confirmacionservicio WHERE NroConfirmacion = '".$nroconfirmacion."'";
		$query_cs = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_cs) != 0) $field_cs = mysql_fetch_array($query_cs);
		
		//	consulto el documento
		$sql = "SELECT Cantidad
				FROM ap_documentosdetalle
				WHERE
					Anio = '".$anio."' AND
					CodProveedor = '".$field_os['CodProveedor']."' AND
					DocumentoClasificacion = 'SER' AND
					DocumentoReferencia = '$nroorden-$nroconfirmacion'";
		$query_doc = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_doc) != 0) $field_doc = mysql_fetch_array($query_doc);
		
		//	actualizo el detalle de la orden de servicio
		$sql = "UPDATE lg_ordenserviciodetalle
				SET
					FlagTerminado = 'N',
					CantidadRecibida = (CantidadRecibida - ".floatval($field_doc['Cantidad'])."),
					FechaTermino = '0000-00-00',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$nroorden."' AND
					Secuencia = '".$field_cs['Secuencia']."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
		
		//	actualizo el detalle de la orden de servicio
		$sql = "UPDATE lg_ordenservicio
				SET
					Estado = 'AP',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$nroorden."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
		
		//	elimino el documento
		$sql = "DELETE FROM ap_documentos 
				WHERE
					Anio = '".$anio."' AND
					CodProveedor = '".$field_os['CodProveedor']."' AND
					DocumentoClasificacion = 'SER' AND
					DocumentoReferencia = '$nroorden-$nroconfirmacion'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
		
		//	elimino los detalles del doucmento
		$sql = "DELETE FROM ap_documentosdetalle
				WHERE
					Anio = '".$anio."' AND
					CodProveedor = '".$field_os['CodProveedor']."' AND
					DocumentoClasificacion = 'SER' AND
					DocumentoReferencia = '$nroorden-$nroconfirmacion'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
		
		// elimino la confirmacion
		$sql = "DELETE FROM lg_confirmacionservicio WHERE NroConfirmacion = '".$nroconfirmacion."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
	}
}

//	cierre mensual
elseif ($modulo == "cierre_mensual") {
	/*
	//	obtengo el valor del periodo anterior
	list(intval($a), intval($m)) = split("[-]", $periodo);
	$m--; if ($m == 0) { $a--; $m = 12; }
	if ($m < 10) $mes = "0$m"; else $mes = "$m";
	$periodo_anterior = "$a-$mes";
	
	//	verifico si existe cierre mensual para el periodo inmediato anterior del organismo
	$sql = "SELECT
				cm.CodOrganismo,
				cm.Codalmacen,
				cm.CodItem,
				cm.StockNuevo,
				i.Descripcion
			FROM
				lg_cierremensual cm
				INNER JOIN lg_itemmast i ON (cm.CodItem = i.CodItem)
			WHERE
				cm.Periodo = '".$periodo_anterior."' AND
				cm.CodOrganismo = '".$organismo."' AND
				cm.StockNuevo > 0 AND
				cm.Precio = 0";
	$query_anterior = mysql_query($sql) or die($sql.mysql_error());
	if (mysql_num_rows($query_anterior) != 0) $field_anterior = mysql_fetch_array($query_anterior);
	
	//	consulto si el periodo se encuentra abierto para el organismo
	*/
}
?>
