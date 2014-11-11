<?php
include("fphp_lg.php");
connect();
///////////////////////////////////////////////////////////////////////////////
//	SCRIPTS PARA AJAX
///////////////////////////////////////////////////////////////////////////////
$error = 0;
switch ($modulo) {

//	CLASIFICACION DE COMMODITIES...
case "CLASIFICACION-COMMODITIES":
	//	INSERTAR NUEVO REGISTRO...
	if ($accion == "GUARDAR") {
		$sql = "SELECT * FROM lg_commodityclasificacion WHERE Clasificacion = '".$codigo."' OR Descripcion = '".utf8_decode($descripcion)."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error = "¡REGISTRO YA INGRESADO!";
		else {
			$sql = "INSERT INTO lg_commodityclasificacion (Clasificacion,
															Descripcion,
															FlagTransaccion,
															Estado,
															UltimoUsuario,
															UltimaFecha)
													VALUES ('".$codigo."',
															'".utf8_decode($descripcion)."',
															'".$flagtransaccion."',
															'".$status."',
															'".$_SESSION['USUARIO_ACTUAL']."',
															'".date("Y-m-d H:i:s")."')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	//	MODIFICAR REGISTRO...
	elseif ($accion == "ACTUALIZAR") {
		$sql = "SELECT * FROM lg_commodityclasificacion WHERE Clasificacion <> '".$codigo."' AND Descripcion = '".utf8_decode($descripcion)."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error = "¡REGISTRO YA INGRESADO!";
		else {
			$sql = "UPDATE lg_commodityclasificacion SET Descripcion = '".utf8_decode($descripcion)."',
															FlagTransaccion = '".$flagtransaccion."',
															Estado = '".$status."',
															UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
															UltimaFecha = '".date("Y-m-d H:i:s")."'
													WHERE 
														Clasificacion = '".$codigo."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	//	ELIMINAR REGISTRO...
	elseif ($accion == "ELIMINAR") {
		$sql = "DELETE FROM lg_commodityclasificacion WHERE Clasificacion = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	echo $error;	
	break;

//	COMMODITIES...
case "COMMODITIES":
	//	INSERTAR NUEVO REGISTRO...
	if ($accion == "GUARDAR-MAST") {
		$sql = "SELECT * FROM lg_commoditymast WHERE Clasificacion = '".$clasificacion."' AND CommodityMast = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error = "¡REGISTRO YA INGRESADO!";
		else {
			$codigo = getCodigo("lg_commoditymast", "CommodityMast", 3);
			$sql = "INSERT INTO lg_commoditymast (Clasificacion,
													CommodityMast,
													Descripcion,
													Estado,
													UltimoUsuario,
													UltimaFecha)
											VALUES ('".$clasificacion."',
													'".$codigo."',
													'".utf8_decode($descripcion)."',
													'".$status."',
													'".$_SESSION['USUARIO_ACTUAL']."',
													'".date("Y-m-d H:i:s")."')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	//	MODIFICAR REGISTRO...
	elseif ($accion == "ACTUALIZAR-MAST") {
		$sql = "SELECT * FROM lg_commoditymast WHERE CommodityMast <> '".$codigo."' AND Descripcion = '".utf8_decode($descripcion)."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error = "¡REGISTRO YA INGRESADO!";
		else {
			$sql = "UPDATE lg_commoditymast SET Descripcion = '".utf8_decode($descripcion)."',
												Estado = '".$status."',
												UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
												UltimaFecha = '".date("Y-m-d H:i:s")."'
											WHERE 
												Clasificacion = '".$clasificacion."' AND CommodityMast = '".$codigo."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	//	ELIMINAR REGISTRO...
	elseif ($accion == "ELIMINAR-MAST") {
		$sql = "DELETE FROM lg_commoditymast WHERE CommodityMast = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		echo $error;	
	}
	//	INSERTAR NUEVO REGISTRO...
	elseif ($accion == "GUARDAR-DET") {
		$sql = "SELECT * FROM lg_commoditysub WHERE CommodityMast = '".$commoditymast."' AND Descripcion = '".utf8_decode($descripcion)."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error = "¡REGISTRO YA INGRESADO!";
		else {
			$commoditysub = getCodigo_2("lg_commoditysub", "CommoditySub", "CommodityMast", $commoditymast, 3);
			$sql = "INSERT INTO lg_commoditysub (CommodityMast,
												CommoditySub,
												Codigo,
												Descripcion,
												CodUnidad,
												Estado,
												UltimoUsuario,
												UltimaFecha)
										VALUES ('".$commoditymast."',
												'".$commoditysub."',
												'".$commoditymast.$commoditysub."',
												'".utf8_decode($descripcion)."',
												'".$unidad."',
												'".$estado."',
												'".$_SESSION['USUARIO_ACTUAL']."',
												'".date("Y-m-d H:i:s")."')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	//	EDITAR REGISTRO...
	elseif ($accion == "EDITAR-DET") {
		$sql = "SELECT * FROM lg_commoditysub WHERE Codigo = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
		else $error = "¡NO SE ENCONTRÓ EN LA BASE DE DATOS!";
		
		echo "$error|:|".$field['Codigo']."|:|".utf8_decode($field['Descripcion'])."|:|".$field['CodUnidad']."|:|".$field['Estado'];
	}
	//	ACTUALIZAR REGISTRO...
	elseif ($accion == "ACTUALIZAR-DET") {
		$sql = "SELECT * FROM lg_commoditysub WHERE (CommodityMast = '".$commoditymast."' AND Descripcion = '".utf8_decode($descripcion)."') AND Codigo <> '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error = "¡REGISTRO YA INGRESADO!";
		else {
			$sql = "UPDATE lg_commoditysub SET Descripcion = '".$descripcion."',
												CodUnidad = '".$unidad."',
												Estado = '".$estado."',
												UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
												UltimaFecha = '".date("Y-m-d H:i:s")."'
											WHERE 
												Codigo = '".$codigo."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	//	ELIMINAR REGISTRO...
	elseif ($accion == "ELIMINAR-DET") {
		$sql = "DELETE FROM lg_commoditysub WHERE Codigo = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		echo $error;
	}
	break;

//	UNIDADES DE MEDIDA...
case "UNIDADES-MEDIDA":
	//	INSERTAR NUEVO REGISTRO...
	if ($accion == "GUARDAR-MAST") {
		$sql = "SELECT * FROM mastunidades WHERE CodUnidad = '".$codigo."' OR Descripcion = '".utf8_decode($descripcion)."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error = "¡REGISTRO YA INGRESADO!";
		else {
			$sql = "INSERT INTO mastunidades (CodUnidad,
												Descripcion,
												TipoMedida,
												Estado,
												UltimoUsuario,
												UltimaFecha)
										VALUES ('".$codigo."',
												'".utf8_decode($descripcion)."',
												'".$tipo."',
												'".$status."',
												'".$_SESSION['USUARIO_ACTUAL']."',
												'".date("Y-m-d H:i:s")."')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	//	MODIFICAR REGISTRO...
	elseif ($accion == "ACTUALIZAR-MAST") {
		$sql = "SELECT * FROM mastunidades WHERE CodUnidad <> '".$codigo."' AND TipoMedida = '".$tipo."' AND Descripcion = '".utf8_decode($descripcion)."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error = "¡REGISTRO YA INGRESADO!";
		else {
			$sql = "UPDATE mastunidades SET Descripcion = '".utf8_decode($descripcion)."',
												Estado = '".$status."',
												UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
												UltimaFecha = '".date("Y-m-d H:i:s")."'
											WHERE 
												TipoMedida = '".$tipo."' AND CodUnidad = '".$codigo."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	//	ELIMINAR REGISTRO...
	elseif ($accion == "ELIMINAR-MAST") {
		$sql = "DELETE FROM mastunidades WHERE CodUnidad = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		echo $error;
	}
	//	INSERTAR NUEVO REGISTRO...
	elseif ($accion == "GUARDAR-DET") {
		$sql = "SELECT * FROM mastunidadesconv WHERE CodUnidad = '".$codunidad."' AND CodEquivalente = '".$equivalente."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error = "¡REGISTRO YA INGRESADO!";
		else {
			$sql = "INSERT INTO mastunidadesconv (CodUnidad,
													CodEquivalente,
													Cantidad,
													Estado,
													UltimoUsuario,
													UltimaFecha)
										VALUES ('".$codunidad."',
												'".$equivalente."',
												'".$cantidad."',
												'".$estado."',
												'".$_SESSION['USUARIO_ACTUAL']."',
												'".date("Y-m-d H:i:s")."')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	//	EDITAR REGISTRO...
	elseif ($accion == "EDITAR-DET") {
		$sql = "SELECT * FROM mastunidadesconv WHERE CodUnidad = '".$codunidad."' AND CodEquivalente = '".$equivalente."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
		else $error = "¡NO SE ENCONTRÓ EN LA BASE DE DATOS!";
		
		echo "$error|:|".$field['CodEquivalente']."|:|".$field['Cantidad']."|:|".$field['Estado'];
	}
	//	ACTUALIZAR REGISTRO...
	elseif ($accion == "ACTUALIZAR-DET") {
		$sql = "SELECT * FROM mastunidadesconv WHERE CodUnidad = '".$codunidad."' AND CodEquivalente = '".$equivalente."' AND CodEquivalente <> '".$equivalente."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error = "¡REGISTRO YA INGRESADO!";
		else {
			$sql = "UPDATE mastunidadesconv SET Cantidad = '".$cantidad."',
												Estado = '".$estado."',
												UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
												UltimaFecha = '".date("Y-m-d H:i:s")."'
											WHERE 
												CodUnidad = '".$codunidad."' AND CodEquivalente = '".$equivalente."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	//	ELIMINAR REGISTRO...
	elseif ($accion == "ELIMINAR-DET") {
		$sql = "DELETE FROM mastunidadesconv WHERE CodUnidad = '".$codunidad."' AND CodEquivalente = '".$equivalente."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		echo $error;
	}
	break;

//	ALMACENES...
case "ALMACENES":
	//	INSERTAR NUEVO REGISTRO...
	if ($accion == "GUARDAR") {
		$sql = "SELECT * FROM lg_almacenmast WHERE CodAlmacen = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error = "¡REGISTRO YA INGRESADO!";
		else {
			$sql = "INSERT INTO lg_almacenmast (CodAlmacen,
												Descripcion,
												CodOrganismo,
												CodDependencia,
												TipoAlmacen,
												AlmacenTransito,
												Direccion,
												FlagVenta,
												FlagProduccion,
												FlagCommodity,
												CodPersona,
												CuentaInventario,
												Estado,
												UltimoUsuario,
												UltimaFecha)
										VALUES ('".$codigo."',
												'".utf8_decode($descripcion)."',
												'".$organismo."',
												'".$dependencia."',
												'".$tipo."',
												'".$principal."',
												'".utf8_decode($direccion)."',
												'".$flagventa."',
												'".$flagproduccion."',
												'".$flagcommodities."',
												'".$codpersona."',
												'".$cuenta."',
												'".$estado."',
												'".$_SESSION['USUARIO_ACTUAL']."',
												'".date("Y-m-d H:i:s")."')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	//	MODIFICAR REGISTRO...
	elseif ($accion == "ACTUALIZAR") {
		$sql = "SELECT * FROM lg_almacenmast WHERE CodAlmacen <> '".$codigo."' AND Descripcion = '".utf8_decode($descripcion)."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error = "¡REGISTRO YA INGRESADO!";
		else {
			$sql = "UPDATE lg_almacenmast SET Descripcion = '".utf8_decode($descripcion)."',
												CodOrganismo = '".$organismo."',
												CodDependencia = '".$dependencia."',
												TipoAlmacen = '".$tipo."',
												AlmacenTransito = '".$principal."',
												Direccion = '".utf8_decode($direccion)."',
												FlagVenta = '".$flagventa."',
												FlagProduccion = '".$flagproduccion."',
												FlagCommodity= '".$flagcommodities."',
												CodPersona = '".$codpersona."',
												CuentaINventario = '".$cuenta."',
												Estado = '".$estado."',
												UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
												UltimaFecha = '".date("Y-m-d H:i:s")."'
											WHERE
												CodAlmacen = '".$codigo."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	//	ELIMINAR REGISTRO...
	elseif ($accion == "ELIMINAR") {
		$sql = "DELETE FROM lg_almacenmast WHERE CodAlmacen = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	echo $error;	
	break;

//	CLASIFICACIONES...
case "CLASIFICACIONES":
	//	INSERTAR NUEVO REGISTRO...
	if ($accion == "GUARDAR") {
		$sql = "SELECT * FROM lg_clasificacion WHERE Clasificacion = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error = "¡REGISTRO YA INGRESADO!";
		else {
			$sql = "INSERT INTO lg_clasificacion (Clasificacion,
													Descripcion,
													ReqOrdenCompra,
													CodAlmacen,
													TipoRequerimiento,
													FlagRecepcionAlmacen,
													FlagRevision,
													ReqAlmacenCompra,
													FlagTransaccion,
													Estado,
													UltimoUsuario,
													UltimaFecha)
											VALUES ('".$codigo."',
													'".utf8_decode($descripcion)."',
													'".$disponible."',
													'".$codalmacen."',
													'".$requerimiento."',
													'".$flagrecepcion."',
													'".$flagrevision."',
													'".$almacen_compra."',
													'".$flagtransaccion."',
													'".$estado."',
													'".$_SESSION['USUARIO_ACTUAL']."',
													'".date("Y-m-d H:i:s")."')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	//	MODIFICAR REGISTRO...
	elseif ($accion == "ACTUALIZAR") {
		$sql = "UPDATE lg_clasificacion SET Descripcion = '".utf8_decode($descripcion)."',
											ReqOrdenCompra = '".$disponible."',
											CodAlmacen = '".$codalmacen."',
											TipoRequerimiento = '".$requerimiento."',
											FlagRecepcionAlmacen = '".$flagrecepcion."',
											FlagRevision = '".$flagrevision."',
											ReqAlmacenCompra = '".$almacen_compra."',
											FlagTransaccion = '".$flagtransaccion."',
											Estado = '".$estado."',
											UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
											UltimaFecha = '".date("Y-m-d H:i:s")."'
										WHERE
											Clasificacion = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	//	ELIMINAR REGISTRO...
	elseif ($accion == "ELIMINAR") {
		$sql = "DELETE FROM lg_clasificacion WHERE Clasificacion = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	echo $error;	
	break;

//	TIPOS DE DOCUMENTOS...
case "TIPOS-DOCUMENTOS":
	//	INSERTAR NUEVO REGISTRO...
	if ($accion == "GUARDAR") {
		$sql = "SELECT * FROM lg_tipodocumento WHERE CodDocumento = '".$codigo."' OR Descripcion = '".utf8_decode($descripcion)."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error = "¡REGISTRO YA INGRESADO!";
		else {
			$sql = "INSERT INTO lg_tipodocumento (CodDocumento,
													Descripcion,
													FlagDocFiscal,
													FlagTransaccion,
													Estado,
													UltimoUsuario,
													UltimaFecha)
											VALUES ('".$codigo."',
													'".utf8_decode($descripcion)."',
													'".$flagdocfiscal."',
													'".$flagtransaccion."',
													'".$estado."',
													'".$_SESSION['USUARIO_ACTUAL']."',
													'".date("Y-m-d H:i:s")."')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	//	MODIFICAR REGISTRO...
	elseif ($accion == "ACTUALIZAR") {
		$sql = "SELECT * FROM lg_tipodocumento WHERE Descripcion = '".utf8_decode($descripcion)."' AND CodDocumento <> '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error = "¡REGISTRO YA INGRESADO!";
		else {
			$sql = "UPDATE lg_tipodocumento SET Descripcion = '".utf8_decode($descripcion)."',
												FlagDocFiscal = '".$flagdocfiscal."',
												FlagTransaccion = '".$flagtransaccion."',
												Estado = '".$estado."',
												UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
												UltimaFecha = '".date("Y-m-d H:i:s")."'
											WHERE
												CodDocumento = '".$codigo."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	//	ELIMINAR REGISTRO...
	elseif ($accion == "ELIMINAR") {
		$sql = "DELETE FROM lg_tipodocumento WHERE CodDocumento = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	echo $error;
	break;

//	TIPOS DE TRANSACCIONES...
case "TIPOS-TRANSACCIONES":
	//	INSERTAR NUEVO REGISTRO...
	if ($accion == "GUARDAR") {
		$sql = "SELECT * FROM lg_tipotransaccion WHERE CodTransaccion = '".$codigo."' OR Descripcion = '".utf8_decode($descripcion)."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error = "¡REGISTRO YA INGRESADO!";
		else {
			$sql = "INSERT INTO lg_tipotransaccion (CodTransaccion,
													Descripcion,
													TipoMovimiento,
													TipoDocGenerado,
													TipoDocTransaccion,
													FlagVoucherConsumo,
													FlagVoucherAjuste,
													FlagTransaccion,
													Estado,
													UltimoUsuario,
													UltimaFecha)
											VALUES ('".$codigo."',
													'".utf8_decode($descripcion)."',
													'".$tipo."',
													'".$docgenerado."',
													'".$doctransaccion."',
													'".$flagconsumo."',
													'".$flagajuste."',
													'".$flagtransaccion."',
													'".$estado."',
													'".$_SESSION['USUARIO_ACTUAL']."',
													'".date("Y-m-d H:i:s")."')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	//	MODIFICAR REGISTRO...
	elseif ($accion == "ACTUALIZAR") {
		$sql = "SELECT * FROM lg_tipotransaccion WHERE Descripcion = '".utf8_decode($descripcion)."' AND CodTransaccion <> '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error = "¡REGISTRO YA INGRESADO!";
		else {
			$sql = "UPDATE lg_tipotransaccion SET Descripcion = '".utf8_decode($descripcion)."',
													TipoMovimiento = '".$tipo."',
													TipoDocGenerado = '".$docgenerado."',
													TipoDocTransaccion = '".$doctransaccion."',
													FlagVoucherConsumo = '".$flagconsumo."',
													FlagVoucherAjuste = '".$flagajuste."',
													FlagTransaccion = '".$flagtransaccion."',
													Estado = '".$estado."',
													UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
													UltimaFecha = '".date("Y-m-d H:i:s")."'
												WHERE
													CodTransaccion = '".$codigo."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	//	ELIMINAR REGISTRO...
	elseif ($accion == "ELIMINAR") {
		$sql = "DELETE FROM lg_tipotransaccion WHERE CodTransaccion = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	echo $error;
	break;

//	TIPOS DE ITEMS...
case "TIPOS-ITEMS":
	//	INSERTAR NUEVO REGISTRO...
	if ($accion == "GUARDAR") {
		$sql = "SELECT * FROM lg_tipoitem WHERE Descripcion = '".utf8_decode($descripcion)."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error = "¡REGISTRO YA INGRESADO!";
		else {
			
			$codigo = getCodigo("lg_tipoitem", "CodTipoItem", 2);
			$sql = "INSERT INTO lg_tipoitem (CodTipoItem,
													Descripcion,
													FlagTransaccion,
													Estado,
													UltimoUsuario,
													UltimaFecha)
											VALUES ('".$codigo."',
													'".utf8_decode($descripcion)."',
													'".$flagtransaccion."',
													'".$estado."',
													'".$_SESSION['USUARIO_ACTUAL']."',
													'".date("Y-m-d H:i:s")."')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	//	MODIFICAR REGISTRO...
	elseif ($accion == "ACTUALIZAR") {
		$sql = "SELECT * FROM lg_tipoitem WHERE Descripcion = '".utf8_decode($descripcion)."' AND CodTipoItem <> '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error = "¡REGISTRO YA INGRESADO!";
		else {
			$sql = "UPDATE lg_tipoitem SET Descripcion = '".utf8_decode($descripcion)."',
												FlagTransaccion = '".$flagtransaccion."',
												Estado = '".$estado."',
												UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
												UltimaFecha = '".date("Y-m-d H:i:s")."'
											WHERE
												CodTipoItem = '".$codigo."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	//	ELIMINAR REGISTRO...
	elseif ($accion == "ELIMINAR") {
		$sql = "DELETE FROM lg_tipoitem WHERE CodTipoItem = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	echo $error;
	break;
  
//	ITEMS...
case "ITEMS":
	//	INSERTAR NUEVO REGISTRO...
	if ($accion == "GUARDAR") {
		$sql = "SELECT * FROM lg_itemmast WHERE CodInterno = '".$codinterno."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error = "¡CODIGO INTERNO YA INGRESADO!";
		else {
			$codigo = getCodigo("lg_itemmast", "CodItem", 10);
			$sql = "INSERT INTO lg_itemmast (CodItem,
											 CodInterno,
											 Descripcion,
											 CodTipoItem,
											 CodUnidad,
											 CodUnidadComp,
											 CodUnidadEmb,
											 CodLinea,
											 CodFamilia,
											 CodSubFamilia,
											 FlagLotes,
											 FlagItem,
											 FlagKit,
											 FlagImpuestoVentas,
											 FlagAuto,
											 FlagDisponible,
											 Imagen,
											 CodMarca,
											 Color,
											 CodProcedencia,
											 CodBarra,
											 StockMin,
											 StockMax,
											 CtaInventario,
											 CtaGasto,
											 CtaVenta,
											 PartidaPresupuestal,
											 Estado,
											 UltimoUsuario,
											 UltimaFecha)
									 VALUES ('".$codigo."',
											 '".$codinterno."',
											 '".utf8_decode($descripcion)."',
											 '".$titem."',
											 '".$unidad."',
											 '".$unidad_compra."',
											 '".$unidad_embalaje."',
											 '".$codlinea."',
											 '".$codfamilia."',
											 '".$codsubfamilia."',
											 '".$flaglotes."',
											 '".$flagitem."',
											 '".$flagkit."',
											 '".$flagimpuesto."',
											 '".$flagauto."',
											 '".$flagdisponible."',
											 '".$imagen."',
											 '".$marca."',
											 '".$color."',
											 '".$procedencia."',
											 '".$codbarra."',
											 '".$stockmin."',
											 '".$stockmax."',
											 '".$ctainventario."',
											 '".$ctagasto."',
											 '".$ctaventa."',
											 '".$partida."',
											 '".$estado."',
											 '".$_SESSION['USUARIO_ACTUAL']."',
											 '".date("Y-m-d H:i:s")."')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	//	MODIFICAR REGISTRO...
	elseif ($accion == "ACTUALIZAR") {
		$sql = "SELECT * FROM lg_itemmast WHERE CodInterno = '".$codinterno."' AND CodItem <> '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error = "¡CODIGO INTERNO YA INGRESADO!";
		else {
			$sql = "UPDATE lg_itemmast SET CodInterno = '".$codinterno."',
										   Descripcion = '".utf8_decode($descripcion)."',
										   CodTipoItem = '".$titem."',
										   CodUnidad = '".$unidad."',
										   CodUnidadComp = '".$unidad_compra."',
										   CodUnidadEmb = '".$unidad_embalaje."',
										   CodLinea = '".$codlinea."',
										   CodFamilia = '".$codfamilia."',
										   CodSubFamilia = '".$codsubfamilia."',
										   FlagLotes = '".$flaglotes."',
										   FlagItem = '".$flagitem."',
										   FlagKit = '".$flagkit."',
										   FlagImpuestoVentas = '".$flagimpuesto."',
										   FlagAuto = '".$flagauto."',
										   FlagDisponible = '".$flagdisponible."',
										   Imagen = '".$imagen."',
										   CodMarca = '".$marca."',
										   Color = '".$color."',
										   CodProcedencia = '".$procedencia."',
										   CodBarra = '".$codbarra."',
										   StockMin = '".$stockmin."',
										   StockMax = '".$stockmax."',
										   CtaInventario = '".$ctainventario."',
										   CtaGasto = '".$ctagasto."',
										   CtaVenta = '".$ctaventa."',
										   PartidaPresupuestal = '".$partida."',
										   Estado = '".$estado."',
										   UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
										   UltimaFecha = '".date("Y-m-d H:i:s")."'
									WHERE
										   CodItem = '".$codigo."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	//	ELIMINAR REGISTRO...
	elseif ($accion == "ELIMINAR") {
		$sql = "DELETE FROM lg_itemmast WHERE CodItem = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	echo $error;
	break;

//	LINEAS...
case "LINEAS":
	//	INSERTAR NUEVO REGISTRO...
	if ($accion == "GUARDAR") {
		$sql = "SELECT * FROM lg_claselinea WHERE Descripcion = '".utf8_decode($descripcion)."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error = "¡REGISTRO YA INGRESADO!";
		else {
			$codigo = getCodigo("lg_claselinea", "CodLinea", 6);
			$sql = "INSERT INTO lg_claselinea (CodLinea,
													Descripcion,
													Estado,
													UltimoUsuario,
													UltimaFecha)
											VALUES ('".$codigo."',
													'".utf8_decode($descripcion)."',
													'".$estado."',
													'".$_SESSION['USUARIO_ACTUAL']."',
													'".date("Y-m-d H:i:s")."')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	//	MODIFICAR REGISTRO...
	elseif ($accion == "ACTUALIZAR") {
		$sql = "SELECT * FROM lg_claselinea WHERE Descripcion = '".utf8_decode($descripcion)."' AND CodLinea <> '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error = "¡REGISTRO YA INGRESADO!";
		else {
			$sql = "UPDATE lg_claselinea SET Descripcion = '".utf8_decode($descripcion)."',
												Estado = '".$estado."',
												UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
												UltimaFecha = '".date("Y-m-d H:i:s")."'
											WHERE
												CodLinea = '".$codigo."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	//	ELIMINAR REGISTRO...
	elseif ($accion == "ELIMINAR") {
		$sql = "DELETE FROM lg_claselinea WHERE CodLinea = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	echo $error;
	break;

//	FAMILIAS...
case "FAMILIAS":
	//	INSERTAR NUEVO REGISTRO...
	if ($accion == "GUARDAR") {
		$sql = "SELECT * FROM lg_clasefamilia WHERE Descripcion = '".utf8_decode($descripcion)."' AND CodLinea = '".$codlinea."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error = "¡REGISTRO YA INGRESADO!";
		else {
			
			$codigo = getCodigo_2("lg_clasefamilia", "CodFamilia", "CodLinea", $codlinea, 6);
			$sql = "INSERT INTO lg_clasefamilia (CodLinea,
													CodFamilia,
													Descripcion,
													CuentaInventario,
													CuentaGasto,
													CuentaVentas,
													Estado,
													UltimoUsuario,
													UltimaFecha)
											VALUES ('".$codlinea."',
													'".$codigo."',
													'".utf8_decode($descripcion)."',
													'".$ctainventario."',
													'".$ctagasto."',
													'".$ctaventas."',
													'".$estado."',
													'".$_SESSION['USUARIO_ACTUAL']."',
													'".date("Y-m-d H:i:s")."')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	//	MODIFICAR REGISTRO...
	elseif ($accion == "ACTUALIZAR") {
		$sql = "SELECT * FROM lg_clasefamilia WHERE Descripcion = '".utf8_decode($descripcion)."' AND CodLinea = '".$codlinea."' AND CodFamilia <> '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error = "¡REGISTRO YA INGRESADO!";
		else {
			$sql = "UPDATE lg_clasefamilia SET Descripcion = '".utf8_decode($descripcion)."',
												CuentaInventario = '".$ctainventario."',
												CuentaGasto = '".$ctagasto."',
												CuentaVentas = '".$ctaventas."',
												Estado = '".$estado."',
												UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
												UltimaFecha = '".date("Y-m-d H:i:s")."'
											WHERE
												CodLinea = '".$codlinea."' AND CodFamilia = '".$codigo."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	//	ELIMINAR REGISTRO...
	elseif ($accion == "ELIMINAR") {
		list($codlinea, $codfamilia)=SPLIT( '[|.|]', $codigo);
		$sql = "DELETE FROM lg_clasefamilia WHERE CodLinea = '".$codlinea."' AND CodFamilia = '".$codfamilia."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	echo $error;
	break;

//	SUBFAMILIAS...
case "SUBFAMILIAS":
	//	INSERTAR NUEVO REGISTRO...
	if ($accion == "GUARDAR") {
		$sql = "SELECT * FROM lg_clasesubfamilia WHERE Descripcion = '".utf8_decode($descripcion)."' AND CodLinea = '".$codlinea."' AND CodFamilia = '".$codfamilia."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error = "¡REGISTRO YA INGRESADO!";
		else {
			$codigo = getCodigo_3("lg_clasesubfamilia", "CodSubFamilia", "CodLinea", "CodFamilia", $codlinea, $codfamilia, 6);
			$sql = "INSERT INTO lg_clasesubfamilia (CodLinea,
													CodFamilia,
													CodSubFamilia,
													Descripcion,
													Estado,
													UltimoUsuario,
													UltimaFecha)
											VALUES ('".$codlinea."',
													'".$codfamilia."',
													'".$codigo."',
													'".utf8_decode($descripcion)."',
													'".$estado."',
													'".$_SESSION['USUARIO_ACTUAL']."',
													'".date("Y-m-d H:i:s")."')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	//	MODIFICAR REGISTRO...
	elseif ($accion == "ACTUALIZAR") {
		$sql = "SELECT * FROM lg_clasesubfamilia WHERE Descripcion = '".utf8_decode($descripcion)."' AND CodLinea = '".$codlinea."' AND CodFamilia = '".$codfamilia."' AND CodSubFamilia <> '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error = "¡REGISTRO YA INGRESADO!";
		else {
			$sql = "UPDATE lg_clasesubfamilia SET Descripcion = '".utf8_decode($descripcion)."',
												Estado = '".$estado."',
												UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
												UltimaFecha = '".date("Y-m-d H:i:s")."'
											WHERE
												CodLinea = '".$codlinea."' AND CodFamilia = '".$codfamilia."' AND CodSubFamilia = '".$codigo."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	//	ELIMINAR REGISTRO...
	elseif ($accion == "ELIMINAR") {
		list($codlinea, $codfamilia, $codsubfamilia)=SPLIT( '[|.|]', $codigo);
		$sql = "DELETE FROM lg_clasesubfamilia WHERE CodLinea = '".$codlinea."' AND CodFamilia = '".$codfamilia."' AND CodSubFamilia = '".$codsubfamilia."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	echo $error;
	break;

//	PROCEDENCIAS...
case "PROCEDENCIAS":
	//	INSERTAR NUEVO REGISTRO...
	if ($accion == "GUARDAR") {
		$sql = "SELECT * FROM lg_procedencias WHERE Descripcion = '".utf8_decode($descripcion)."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error = "¡REGISTRO YA INGRESADO!";
		else {
			$sql = "INSERT INTO lg_procedencias (CodProcedencia,
													Descripcion,
													Estado,
													UltimoUsuario,
													UltimaFecha)
											VALUES ('".$codigo."',
													'".utf8_decode($descripcion)."',
													'".$estado."',
													'".$_SESSION['USUARIO_ACTUAL']."',
													'".date("Y-m-d H:i:s")."')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	//	MODIFICAR REGISTRO...
	elseif ($accion == "ACTUALIZAR") {
		$sql = "SELECT * FROM lg_procedencias WHERE Descripcion = '".utf8_decode($descripcion)."' AND CodProcedencia <> '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error = "¡REGISTRO YA INGRESADO!";
		else {
			$sql = "UPDATE lg_procedencias SET Descripcion = '".utf8_decode($descripcion)."',
												Estado = '".$estado."',
												UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
												UltimaFecha = '".date("Y-m-d H:i:s")."'
											WHERE
												CodProcedencia = '".$codigo."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	//	ELIMINAR REGISTRO...
	elseif ($accion == "ELIMINAR") {
		$sql = "DELETE FROM lg_procedencias WHERE CodProcedencia = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	echo $error;
	break;

//	MARCAS...
case "MARCAS":
	//	INSERTAR NUEVO REGISTRO...
	if ($accion == "GUARDAR") {
		$sql = "SELECT * FROM lg_marcas WHERE Descripcion = '".utf8_decode($descripcion)."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error = "¡REGISTRO YA INGRESADO!";
		else {
			$codigo = getCodigo("lg_marcas", "CodMarca", 4);
			$sql = "INSERT INTO lg_marcas (CodMarca,
													Descripcion,
													Estado,
													UltimoUsuario,
													UltimaFecha)
											VALUES ('".$codigo."',
													'".utf8_decode($descripcion)."',
													'".$estado."',
													'".$_SESSION['USUARIO_ACTUAL']."',
													'".date("Y-m-d H:i:s")."')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	//	MODIFICAR REGISTRO...
	elseif ($accion == "ACTUALIZAR") {
		$sql = "SELECT * FROM lg_marcas WHERE Descripcion = '".utf8_decode($descripcion)."' AND CodMarca <> '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error = "¡REGISTRO YA INGRESADO!";
		else {
			$sql = "UPDATE lg_marcas SET Descripcion = '".utf8_decode($descripcion)."',
												Estado = '".$estado."',
												UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
												UltimaFecha = '".date("Y-m-d H:i:s")."'
											WHERE
												CodMarca = '".$codigo."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	//	ELIMINAR REGISTRO...
	elseif ($accion == "ELIMINAR") {
		$sql = "DELETE FROM lg_marcas WHERE CodMarca = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	echo $error;
	break;

//	REQUERIMIENTOS...
case "REQUERIMIENTOS":
	list($d, $m, $a)=SPLIT( '[/.-]', $frequerida); $frequerida = "$a-$m-$d";
	list($d, $m, $a)=SPLIT( '[/.-]', $fpreparado); $fpreparado = "$a-$m-$d";
	list($d, $m, $a)=SPLIT( '[/.-]', $faprobado); $faprobado = "$a-$m-$d";
	list($d, $m, $a)=SPLIT( '[/.-]', $frevisado); $frevisado = "$a-$m-$d";
	//	INSERTAR NUEVO REGISTRO...
	if ($accion == "GUARDAR") {
		$codigo = getCodigo("lg_requerimientos", "CodRequerimiento", 10);
		$sql = "INSERT INTO lg_requerimientos (CodRequerimiento,
												CodOrganismo,
												CodDependencia,
												CodCentroCosto,
												CodAlmacen,
												Clasificacion,
												Prioridad,
												TipoClasificacion,
												PreparadaPor,
												RevisadaPor,
												AprobadaPor,
												FechaRequerida,
												FechaPreparacion,
												FechaRevision,
												FechaAprobacion,
												Comentarios,
												RazonRechazo,
												Estado,
												UltimoUsuario,
												UltimaFecha)
										VALUES ('".$codigo."',
												'".$organismo."',
												'".$dependencia."',
												'".$ccosto."',
												'".$almacen."',
												'".$clasificacion."',
												'".$prioridad."',
												'".$flagdirigido."',
												'".$preparadopor."',
												'".$revisadopor."',
												'".$aprobadopor."',
												'".$frequerida."',
												'".date("Y-m-d")."',
												'".$frevisado."',
												'".$faprobado."',
												'".utf8_decode($comentarios)."',
												'".utf8_decode($razon)."',
												'".$estado."',
												'".$_SESSION['USUARIO_ACTUAL']."',
												'".date("Y-m-d H:i:s")."')";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		echo "$error|$codigo";
	}
	//	MODIFICAR REGISTRO...
	elseif ($accion == "ACTUALIZAR") {
		$sql = "UPDATE lg_requerimientos SET CodCentroCosto = '".$ccosto."',
												CodAlmacen = '".$almacen."',
												Prioridad = '".$prioridad."',
												TipoClasificacion = '".$flagdirigido."',
												PreparadaPor = '".$preparadopor."',
												RevisadaPor = '".$revisadopor."',
												AprobadaPor = '".$aprobadopor."',
												FechaRequerida = '".$frequerida."',
												FechaPreparacion = '".$fpreparado."',
												FechaRevision = '".$frevisado."',
												FechaAprobacion = '".$faprobado."',
												Comentarios = '".utf8_decode($comentarios)."',
												RazonRechazo = '".utf8_decode($razon)."',
												UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
												UltimaFecha = '".date("Y-m-d H:i:s")."'
											WHERE
												CodRequerimiento = '".$codigo."' AND
												CodOrganismo = '".$organismo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		echo $error;
	}
	//	REVISAR REQUERIMIENTO...
	elseif ($accion == "REVISAR") {
		$sql = "UPDATE lg_requerimientos SET RevisadaPor = '".$_SESSION["CODPERSONA_ACTUAL"]."', Estado = 'R', FechaRevision = '".date("Y-m-d")."', UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha = '".date("Y-m-d H:i:s")."' WHERE CodRequerimiento = '".$codrequerimiento."' AND CodOrganismo = '".$organismo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		
		$sql = "UPDATE lg_requerimientosdet SET Estado = 'E', UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha = '".date("Y-m-d H:i:s")."' WHERE CodRequerimiento = '".$codrequerimiento."' AND CodOrganismo = '".$organismo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		echo $error;
	}
	//	APROBAR REQUERIMIENTO...
	elseif ($accion == "APROBAR") {
		$sql = "UPDATE lg_requerimientos SET AprobadaPor = '".$_SESSION["CODPERSONA_ACTUAL"]."', Estado = 'A', FechaAprobacion = '".date("Y-m-d")."', UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha = '".date("Y-m-d H:i:s")."' WHERE CodRequerimiento = '".$codrequerimiento."' AND CodOrganismo = '".$organismo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		
		$sql = "UPDATE lg_requerimientosdet SET Estado = 'E', UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha = '".date("Y-m-d H:i:s")."' WHERE CodRequerimiento = '".$codrequerimiento."' AND CodOrganismo = '".$organismo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		echo $error;
	}
	//	RECHAZAR REQUERIMIENTO...
	elseif ($accion == "RECHAZAR") {
		$sql = "UPDATE lg_requerimientos SET Estado = 'E', RazonRechazo = '".utf8_decode($razon)."', UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha = '".date("Y-m-d H:i:s")."' WHERE CodRequerimiento = '".$codrequerimiento."' AND CodOrganismo = '".$organismo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		
		$sql = "UPDATE lg_requerimientosdet SET Estado = 'N', UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha = '".date("Y-m-d H:i:s")."' WHERE CodRequerimiento = '".$codrequerimiento."' AND CodOrganismo = '".$organismo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		echo $error;
	}
	//	ANULAR REQUERIMIENTO...
	elseif ($accion == "ANULAR") {
		$sql = "SELECT Estado FROM lg_requerimientos WHERE CodOrganismo = '".$organismo."' AND CodRequerimiento = '".$codrequerimiento."'";
		$query_estado = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_estado) != 0) $field_estado = mysql_fetch_array($query_estado);
		
		if ($field_estado['Estado'] == "A") { $estado = "P"; $estadodet = "P"; }
		elseif ($field_estado['Estado'] == "R") { $estado = "P"; $estadodet = "P"; }
		elseif ($field_estado['Estado'] == "P") { $estado = "N"; $estadodet = "P"; }
		elseif ($field_estado['Estado'] == "N") die("NO SE PUEDE ANULAR UN DOCUMENTO YA ANULADO");
		
		$sql = "UPDATE lg_requerimientos SET AprobadaPor = '', Estado = '".$estado."', FechaAprobacion = '', RazonRechazo = '".utf8_decode($razon)."', UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha = '".date("Y-m-d H:i:s")."' WHERE CodRequerimiento = '".$codrequerimiento."' AND CodOrganismo = '".$organismo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		
		$sql = "UPDATE lg_requerimientosdet SET Estado = '".$estadodet."', UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha = '".date("Y-m-d H:i:s")."' WHERE CodRequerimiento = '".$codrequerimiento."' AND CodOrganismo = '".$organismo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		echo $error;
	}
	//	CERRAR REQUERIMIENTO...
	elseif ($accion == "CERRAR") {
		$sql = "SELECT Estado FROM lg_requerimientos WHERE CodOrganismo = '".$organismo."' AND CodRequerimiento = '".$codrequerimiento."'";
		$query_estado = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_estado) != 0) $field_estado = mysql_fetch_array($query_estado);
		
		if ($field_estado['Estado'] == "C")  die("NO SE PUEDE CERRAR UN DOCUMENTO YA CERRADO");
		elseif ($field_estado['Estado'] == "N") die("NO SE PUEDE CERRAR UN DOCUMENTO YA ANULADO");
		
		$sql = "UPDATE lg_requerimientos SET Estado = 'C', UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha = '".date("Y-m-d H:i:s")."' WHERE CodRequerimiento = '".$codrequerimiento."' AND CodOrganismo = '".$organismo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		
		$sql = "UPDATE lg_requerimientosdet SET Estado = 'C', UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha = '".date("Y-m-d H:i:s")."' WHERE CodRequerimiento = '".$codrequerimiento."' AND CodOrganismo = '".$organismo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		echo $error;
	}
	break;

//	REQUERIMIENTOS (DETALLE)...
case "REQUERIMIENTOS-DETALLE":
	//	INSERTAR NUEVO REGISTRO...
	if ($accion == "INSERTAR") {
		if ($tiporeq == "01" || $tiporeq == "03") {
			$coditem = $codigo; 
			//--
			$sql = "SELECT CodUnidad FROM lg_itemmast WHERE CodItem = '".$codigo."'";
			$query_item = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_item) != 0) $field_item = mysql_fetch_array($query_item);
			//--
			$codunidad = $field_item['CodUnidad'];
		} else {
			$commodity = $codigo;
			$codunidad = "";
		}
	
		
		//	---------------------------------
		$secuencia = getCodigo_2("lg_requerimientosdet", "Secuencia", "CodRequerimiento", $codrequerimiento, 4); $secuencia = (int) $secuencia;
		$sql = "INSERT INTO lg_requerimientosdet (CodRequerimiento,
													Secuencia,
													CodOrganismo,
													CodDependencia,
													CodItem,
													CommodityMast,
													Descripcion,
													CodUnidad,
													CantidadPedida,
													CodCentroCosto,
													FlagExonerado,
													FlagCompraAlmacen,
													Estado,
													UltimoUsuario,
													UltimaFecha)
											VALUES ('".$codrequerimiento."',
													'".$secuencia."',
													'".$organismo."',
													'".$dependencia."',
													'".$coditem."',
													'".$commodity."',
													'".utf8_decode($descripcion)."',
													'".$codunidad."',
													'".$cant."',
													'".$ccosto."',
													'N',
													'".$flagdirigido."',
													'".$estado."',
													'".$_SESSION['USUARIO_ACTUAL']."',
													'".date("Y-m-d H:i:s")."')";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	//	EDITAR REGISTRO...
	elseif ($accion == "EDITAR") {
		$sql = "SELECT * FROM lg_requerimientosdet WHERE CodRequerimiento = '".$codrequerimiento."' AND Secuencia = '".$seleccion."'";	
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $field = mysql_fetch_array($query);
		
		if ($tiporeq == "01" || $tiporeq == "03") $codigo = $field['CodItem']; else $codigo = $field['CommodityMast'];
		
		echo "|.|".$field['Secuencia']."|.|".$codigo."|.|".$field['Descripcion']."|.|".$field['CantidadPedida']."|.|".$field['CodCentroCosto']."|.|".$field['Estado']."|.|".$field['Comentario']."|.|".$field['Documento']."|.|".$field['FlagExonerado'];
	}
	//	MODIFICAR REGISTRO...
	elseif ($accion == "UPDATE") {
		$sql = "UPDATE lg_requerimientosdet SET CantidadPedida = '".$cant."',
												FlagExonerado = '".$flagexonerado."',
												CodCentroCosto = '".$ccosto."',
												UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
												UltimaFecha = '".date("Y-m-d H:i:s")."'
											WHERE
												CodRequerimiento = '".$codrequerimiento."' AND
												Secuencia = '".$seleccion."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		echo $error;
	}
	//	ELIMINAR REGISTRO...
	elseif ($accion == "DELETE") {
		$sql = "DELETE FROM lg_requerimientosdet WHERE CodRequerimiento = '".$codrequerimiento."' AND Secuencia = '".$seleccion."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	//	CERRAR DETALLE...
	elseif ($accion == "CERRAR-DETALLE") {
		list($codorganismo, $codrequerimiento, $detalle)=SPLIT('[|]', $registro);
		$sql = "UPDATE lg_requerimientosdet SET Estado = 'C', UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha = '".date("Y-m-d H:i:s")."' WHERE CodRequerimiento = '".$codrequerimiento."' AND CodOrganismo = '".$codorganismo."' AND Secuencia = '".$detalle."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		echo $error;
	}
	break;
	
//	FORMAS DE PAGO...
case "FORMAS-PAGO":
	//	INSERTAR NUEVO REGISTRO...
	if ($accion == "GUARDAR") {
		$sql = "SELECT * FROM mastformapago WHERE Descripcion = '".utf8_decode($descripcion)."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error = "¡REGISTRO YA INGRESADO!";
		else {
			$codigo = getCodigo("mastformapago", "CodFormaPago", 3);
			$sql = "INSERT INTO mastformapago (CodFormaPago,
												Descripcion,
												FlagCredito,
												DiasVence,
												Estado,
												UltimoUsuario,
												UltimaFecha)
										VALUES ('".$codigo."',
												'".utf8_decode($descripcion)."',
												'".$flagcredito."',
												'".$dias."',
												'".$estado."',
												'".$_SESSION['USUARIO_ACTUAL']."',
												'".date("Y-m-d H:i:s")."')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	//	MODIFICAR REGISTRO...
	elseif ($accion == "ACTUALIZAR") {
		$sql = "SELECT * FROM mastformapago WHERE CodFormaPago <> '".$codigo."' AND Descripcion = '".utf8_decode($descripcion)."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error = "¡REGISTRO YA INGRESADO!";
		else {
			$sql = "UPDATE mastformapago SET Descripcion = '".utf8_decode($descripcion)."',
												FlagCredito = '".$flagcredito."',
												DiasVence = '".$dias."',
												Estado = '".$estado."',
												UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
												UltimaFecha = '".date("Y-m-d H:i:s")."'
											WHERE
												CodFormaPago = '".$codigo."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	//	ELIMINAR REGISTRO...
	elseif ($accion == "ELIMINAR") {
		$sql = "DELETE FROM mastformapago WHERE CodFormaPago = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	echo $error;	
	break;
}
?>