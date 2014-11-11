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
		echo $error."|".$codigo;
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
		echo $error."|".$codigo;
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
												 cod_partida,
												 CodCuenta,
												 CodClasificacion,
												 Estado,
												 UltimoUsuario,
												 UltimaFecha)
										VALUES ('".$commoditymast."',
												'".$commoditysub."',
												'".$commoditymast.$commoditysub."',
												'".utf8_decode($descripcion)."',
												'".$unidad."',
												'".$codpartida."',
												'".$codcuenta."',
												'".$codactivo."',
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
		
		echo "$error|:|".$field['Codigo']."|:|".utf8_decode($field['Descripcion'])."|:|".$field['CodUnidad']."|:|".$field['Estado']."|:|".$field['cod_partida']."|:|".$field['CodCuenta']."|:|".$field['CodClasificacion'];
	}
	//	ACTUALIZAR REGISTRO...
	elseif ($accion == "ACTUALIZAR-DET") {
		$sql = "SELECT * FROM lg_commoditysub WHERE (CommodityMast = '".$commoditymast."' AND Descripcion = '".utf8_decode($descripcion)."') AND Codigo <> '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error = "¡REGISTRO YA INGRESADO!";
		else {
			$sql = "UPDATE lg_commoditysub SET Descripcion = '".$descripcion."',
												CodUnidad = '".$unidad."',
												cod_partida = '".$codpartida."',
												CodCuenta = '".$codcuenta."',
												CodClasificacion = '".$codactivo."',
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
		$codrequerimiento = getCodigo("lg_requerimientos", "CodRequerimiento", 10);		
		$correlativo = getCodigo_3("lg_requerimientos", "Secuencia", "Anio", "CodDependencia", date("Y"), $dependencia, 3);
		$secuencia = (int) $correlativo;
		$codinterno_dependencia = getCodInternoDependencia($dependencia);
		$codinterno = "$codinterno_dependencia-$correlativo-".date("Y");
		$requerimiento_retornar = $codrequerimiento;		
		
		//	inserto el requerimiento
		$sql = "INSERT INTO lg_requerimientos (
							CodRequerimiento,
							CodInterno,
							Anio,
							Secuencia,
							CodOrganismo,
							CodDependencia,
							CodCentroCosto,
							CodAlmacen,
							Clasificacion,
							Prioridad,
							TipoClasificacion,
							PreparadaPor,
							FechaRequerida,
							FechaPreparacion,
							Comentarios,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$codrequerimiento."',
							'".$codinterno."',
							'".date("Y")."',
							'".$secuencia."',
							'".$organismo."',
							'".$dependencia."',
							'".$ccosto."',
							'".$almacen."',
							'".$clasificacion."',
							'".$prioridad."',
							'".$flagdirigido."',
							'".$preparadopor."',
							'".$frequerida."',
							'".date("Y-m-d")."',
							'".utf8_decode($comentarios)."',
							'".$estado."',
							'".$_SESSION['USUARIO_ACTUAL']."',
							'".date("Y-m-d H:i:s")."')";
		$query_mast = mysql_query($sql) or die ($sql.mysql_error());
		
		$secuencia = 0;
		//	inserto los detalles
		$detalle = split(";", $detalles);
		foreach ($detalle as $linea) {
			list($codigo, $descripcion_det, $unidad_det, $ccostos_det, $chkexon_det, $cantidad_det, $docreferencia)=SPLIT( '[|]', $linea);
			if ($chkexon_det == "true") $flagexon = "S"; else $flagexon = "N";
			
			if ($tiporeq == "01") {
				$coditem = $codigo;
				$sql = "SELECT
							CtaGasto AS CodCuenta,
							PartidaPresupuestal AS cod_partida
						FROM
							lg_itemmast
						WHERE
							CodItem = '".$coditem."'";
				$query_distribucion = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_distribucion) != 0) $field_distribucion = mysql_fetch_array($query_distribucion);
				
			} else {
				list($cod, $nro) = split("[.]", $codigo);
				$codigo = $cod;
				$commodity = $codigo;
				$sql = "SELECT
							CodCuenta,
							cod_partida
						FROM
							lg_commoditysub
						WHERE
							Codigo = '".$commodity."'";
				$query_distribucion = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_distribucion) != 0) $field_distribucion = mysql_fetch_array($query_distribucion);
			}			
			//	---------------------------------
			$secuencia++;
			$sql = "INSERT INTO lg_requerimientosdet (CodRequerimiento,
														Secuencia,
														CodOrganismo,
														CodItem,
														CommoditySub,
														Descripcion,
														CodUnidad,
														CantidadPedida,
														CodCentroCosto,
														FlagExonerado,
														FlagCompraAlmacen,
														Estado,
														Comentarios,
														CodCuenta,
														cod_partida,
														DocReferencia,
														UltimoUsuario,
														UltimaFecha)
												VALUES ('".$codrequerimiento."',
														'".$secuencia."',
														'".$organismo."',
														'".$coditem."',
														'".$commodity."',
														'".utf8_decode($descripcion_det)."',
														'".$unidad_det."',
														'".$cantidad_det."',
														'".$ccostos_det."',
														'".$flagexon."',
														'".$flagdirigido."',
														'PR',
														'".utf8_decode($comentarios)."',
														'".$field_distribucion['CodCuenta']."',
														'".$field_distribucion['cod_partida']."',
														'".$docreferencia."',
														'".$_SESSION['USUARIO_ACTUAL']."',
														'".date("Y-m-d H:i:s")."')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
			//	---------------------------------
		}
		
		if ($detalles_anterior != "" && $detalles_anterior != "undefined") {
			$linea = split(";", $detalles_anterior);
			foreach ($linea as $registro) {
				list($codorganismo, $codrequerimiento, $secuencia)=SPLIT( '[|]', $registro);			
				$sql = "UPDATE lg_requerimientosdet 
						SET FlagCompraAlmacen = 'A'
						WHERE
							CodOrganismo = '".$codorganismo."' AND
							CodRequerimiento = '".$codrequerimiento."' AND
							Secuencia = '".$secuencia."'";
				$query_det = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
		
		echo $error."|".$codinterno;
	}
	//	MODIFICAR REGISTRO...
	elseif ($accion == "ACTUALIZAR") {
		$codrequerimiento = $codigo;
		
		//	actualizo el requerimiento
		$sql = "UPDATE lg_requerimientos SET CodDependencia = '".$dependencia."',
												CodCentroCosto = '".$ccosto."',
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
												CodRequerimiento = '".$codrequerimiento."' AND
												CodOrganismo = '".$organismo."'";
		$query_mast = mysql_query($sql) or die ($sql.mysql_error());
		
		$secuencia = 0;
		
		//	elimino los detalles
		$sql = "DELETE FROM lg_requerimientosdet WHERE CodOrganismo = '".$organismo."' AND CodRequerimiento = '".$codrequerimiento."'";
		$query_det = mysql_query($sql) or die ($sql.mysql_query());
		
		//	inserto los detalles
		$detalle = split(";", $detalles);
		foreach ($detalle as $linea) {
			list($codigo, $descripcion_det, $unidad_det, $ccostos_det, $chkexon_det, $cantidad_det, $docreferencia)=SPLIT( '[|]', $linea);
			if ($chkexon_det == "true") $flagexon = "S"; else $flagexon = "N";
			
			if ($tiporeq == "01") {
				$coditem = $codigo;
				$sql = "SELECT
							CtaGasto AS CodCuenta,
							PartidaPresupuestal AS cod_partida
						FROM
							lg_itemmast
						WHERE
							CodItem = '".$coditem."'";
				$query_distribucion = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_distribucion) != 0) $field_distribucion = mysql_fetch_array($query_distribucion);
				
			} else {
				list($cod, $nro) = split("[.]", $codigo);
				$codigo = $cod;
				$commodity = $codigo;
				$sql = "SELECT
							CodCuenta,
							cod_partida
						FROM
							lg_commoditysub
						WHERE
							Codigo = '".$commodity."'";
				$query_distribucion = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_distribucion) != 0) $field_distribucion = mysql_fetch_array($query_distribucion);
			}			
			//	---------------------------------
			$secuencia++;
			$sql = "INSERT INTO lg_requerimientosdet (CodRequerimiento,
														Secuencia,
														CodOrganismo,
														CodItem,
														CommoditySub,
														Descripcion,
														CodUnidad,
														CantidadPedida,
														CodCentroCosto,
														FlagExonerado,
														FlagCompraAlmacen,
														Estado,
														Comentarios,
														CodCuenta,
														cod_partida,
														DocReferencia,
														UltimoUsuario,
														UltimaFecha)
												VALUES ('".$codrequerimiento."',
														'".$secuencia."',
														'".$organismo."',
														'".$coditem."',
														'".$commodity."',
														'".utf8_decode($descripcion_det)."',
														'".$unidad_det."',
														'".$cantidad_det."',
														'".$ccostos_det."',
														'".$flagexon."',
														'".$flagdirigido."',
														'PR',
														'".utf8_decode($comentarios)."',
														'".$field_distribucion['CodCuenta']."',
														'".$field_distribucion['cod_partida']."',
														'".$docreferencia."',
														'".$_SESSION['USUARIO_ACTUAL']."',
														'".date("Y-m-d H:i:s")."')";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
		echo $error;
	}
	//	REVISAR REQUERIMIENTO...
	elseif ($accion == "REVISAR") {
		$sql = "UPDATE lg_requerimientos SET RevisadaPor = '".$_SESSION["CODPERSONA_ACTUAL"]."', Estado = 'RV', FechaRevision = '".date("Y-m-d")."', UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha = '".date("Y-m-d H:i:s")."' WHERE CodRequerimiento = '".$codrequerimiento."' AND CodOrganismo = '".$organismo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		echo $error."|";
	}
	//	CONFORMAR REQUERIMIENTO...
	elseif ($accion == "CONFORMAR") {
		$sql = "UPDATE lg_requerimientos SET ConformadaPor = '".$_SESSION["CODPERSONA_ACTUAL"]."', Estado = 'CN', FechaConformacion = '".date("Y-m-d")."', UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha = '".date("Y-m-d H:i:s")."' WHERE CodRequerimiento = '".$codrequerimiento."' AND CodOrganismo = '".$organismo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		echo $error."|";
	}
	//	APROBAR REQUERIMIENTO...
	elseif ($accion == "APROBAR") {
		$sql = "UPDATE lg_requerimientos SET AprobadaPor = '".$_SESSION["CODPERSONA_ACTUAL"]."', Estado = 'AP', FechaAprobacion = '".date("Y-m-d")."', UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha = '".date("Y-m-d H:i:s")."' WHERE CodRequerimiento = '".$codrequerimiento."' AND CodOrganismo = '".$organismo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		
		$sql = "UPDATE lg_requerimientosdet SET Estado = 'PE', UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha = '".date("Y-m-d H:i:s")."' WHERE CodRequerimiento = '".$codrequerimiento."' AND CodOrganismo = '".$organismo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		echo $error."|";
	}
	//	RECHAZAR REQUERIMIENTO...
	elseif ($accion == "RECHAZAR") {
		$sql = "UPDATE lg_requerimientos SET Estado = 'RE', RazonRechazo = '".utf8_decode($razon)."', UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha = '".date("Y-m-d H:i:s")."' WHERE CodRequerimiento = '".$codrequerimiento."' AND CodOrganismo = '".$organismo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		
		$sql = "UPDATE lg_requerimientosdet SET Estado = 'RE', UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha = '".date("Y-m-d H:i:s")."' WHERE CodRequerimiento = '".$codrequerimiento."' AND CodOrganismo = '".$organismo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		echo $error."|";
	}
	//	ANULAR REQUERIMIENTO...
	elseif ($accion == "ANULAR") {
		$sql = "SELECT Estado FROM lg_requerimientos WHERE CodOrganismo = '".$organismo."' AND CodRequerimiento = '".$codrequerimiento."'";
		$query_estado = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_estado) != 0) $field_estado = mysql_fetch_array($query_estado);
		
		if ($field_estado['Estado'] == "AP") { $estado = "PR"; $estadodet = "PR"; }
		elseif ($field_estado['Estado'] == "RV") { $estado = "PR"; $estadodet = "PR"; }
		elseif ($field_estado['Estado'] == "CN") { $estado = "PR"; $estadodet = "PR"; }
		elseif ($field_estado['Estado'] == "PR") { $estado = "AN"; $estadodet = "AN"; }
		elseif ($field_estado['Estado'] == "AN") die("NO SE PUEDE ANULAR UN DOCUMENTO YA ANULADO");
		elseif ($field_estado['Estado'] == "RE") die("NO SE PUEDE ANULAR UN DOCUMENTO RECHAZADO");
		
		$sql = "UPDATE lg_requerimientos SET Estado = '".$estado."', RazonRechazo = '".utf8_decode($razon)."', UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha = '".date("Y-m-d H:i:s")."' WHERE CodRequerimiento = '".$codrequerimiento."' AND CodOrganismo = '".$organismo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		
		$sql = "UPDATE lg_requerimientosdet SET Estado = '".$estadodet."', UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha = '".date("Y-m-d H:i:s")."' WHERE CodRequerimiento = '".$codrequerimiento."' AND CodOrganismo = '".$organismo."' AND Estado <> 'CO'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		
		$sql = "DELETE FROM lg_cotizacion WHERE CodOrganismo = '".$organismo."' AND CodRequerimiento = '".$codrequerimiento."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		echo $error."|";
	}
	//	CERRAR REQUERIMIENTO...
	elseif ($accion == "CERRAR") {
		$sql = "SELECT Estado FROM lg_requerimientos WHERE CodOrganismo = '".$organismo."' AND CodRequerimiento = '".$codrequerimiento."'";
		$query_estado = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_estado) != 0) $field_estado = mysql_fetch_array($query_estado);
		
		if ($field_estado['Estado'] == "CE")  die("NO SE PUEDE CERRAR UN DOCUMENTO YA CERRADO");
		elseif ($field_estado['Estado'] == "AN") die("NO SE PUEDE CERRAR UN DOCUMENTO YA ANULADO");
		elseif ($field_estado['Estado'] == "RE") die("NO SE PUEDE CERRAR UN DOCUMENTO YA RECHAZADO");
		
		$sql = "UPDATE lg_requerimientos SET Estado = 'CE', UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha = '".date("Y-m-d H:i:s")."' WHERE CodRequerimiento = '".$codrequerimiento."' AND CodOrganismo = '".$organismo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
				
		$sql = "UPDATE lg_requerimientosdet SET Estado = 'CE', UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha = '".date("Y-m-d H:i:s")."' WHERE CodRequerimiento = '".$codrequerimiento."' AND CodOrganismo = '".$organismo."' AND Estado <> 'CO'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		echo $error."|";
	}
	break;

//	REQUERIMIENTOS (DETALLE)...
case "REQUERIMIENTOS-DETALLE":
	//	CERRAR DETALLE...
	if ($accion == "CERRAR-DETALLE") {
		list($codorganismo, $codrequerimiento, $detalle)=SPLIT('[|]', $registro);
		$sql = "UPDATE lg_requerimientosdet SET Estado = 'CE', UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."', UltimaFecha = '".date("Y-m-d H:i:s")."' WHERE CodRequerimiento = '".$codrequerimiento."' AND CodOrganismo = '".$codorganismo."' AND Secuencia = '".$detalle."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		
		
		//	verifico si tiene detalles pendiente el requerimiento si no tiene la paso a estado completado
		$sql = "SELECT rd.*
				FROM
					lg_requerimientosdet rd
					INNER JOIN lg_requerimientos r ON (r.CodOrganismo = rd.CodOrganismo AND
													   r.CodRequerimiento = rd.CodRequerimiento)
				WHERE
					rd.CodOrganismo = '".$codorganismo."' AND
					rd.CodRequerimiento = '".$codrequerimiento."' AND
					(rd.Estado = 'PE' OR rd.Estado = 'PR')";
		$query_req_det = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_req_det) == 0) {
			$sql = "UPDATE lg_requerimientos
					SET Estado = 'CO'
					WHERE 
						CodOrganismo = '".$codorganismo."' AND 
						CodRequerimiento = '".$codrequerimiento."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
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

//	ORDENES DE COMPRA...
case "ORDENES-COMPRA":
	//	INSERTAR REGISTRO...
	if ($accion == "INSERTAR") {
		if ($btInsertarItem == "true") $tabla = "item"; else $tabla = "commodity";
		$disponible = verificarDisponibilidadPresupuestariaOC($tabla, $detalles, $organismo, date("Y"), $total_impuesto, $nroorden);
		if (!$disponible) die("¡ERROR: Se encontrarón lineas del detalle sin disponibilidad presupuestaria!");
		
		$nroorden = getCodigo_2("lg_ordencompra", "NroOrden", "CodOrganismo", $organismo, 10);	
		$secuencia = 0;
		//	inserto los detalles
		$detalle = split(";", $detalles);
		foreach ($detalle as $linea) {
			list($codigo, $descripcion, $codunidad, $cantidad, $unitario, $descporc, $descfijo, $exon, $fentregadet, $ccostos, $codpartida, $codcuenta, $obs)=SPLIT( '[|]', $linea);
			if ($btInsertarItem == "true") {
				$coditem = $codigo;
				$sql = "SELECT
							CtaGasto AS CodCuenta,
							PartidaPresupuestal AS cod_partida
						FROM
							lg_itemmast
						WHERE
							CodItem = '".$coditem."'";
				$query_distribucion = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_distribucion) != 0) $field_distribucion = mysql_fetch_array($query_distribucion);
			} else {
				list($cod, $nro) = split("[.]", $codigo);
				$codigo = $cod;
				$codcommodity = $codigo;
				$sql = "SELECT
							CodCuenta,
							cod_partida
						FROM
							lg_commoditysub
						WHERE
							Codigo = '".$codcommodity."'";
				$query_distribucion = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_distribucion) != 0) $field_distribucion = mysql_fetch_array($query_distribucion);
			}
			
			if ($exon == "true") $flagexon = "S"; else $flagexon = "N";
			if ($flagexon == "S") $imp = 0.00; else $imp = $impuesto;			
			$precio_unitario = $unitario;
			$precio_cantidad = $precio_unitario * $cantidad;
			$total_detalle = $precio_cantidad + ($precio_cantidad * $imp / 100);
			
			$secuencia++;
			//	inserto los detalles de la orden
			$sql = "INSERT INTO lg_ordencompradetalle (CodOrganismo,
													   NroOrden,
													   Secuencia,
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
													   Estado,
													   CodCuenta,
													   cod_partida,
													   UltimoUsuario,
													   UltimaFecha)
											  VALUES ('".$organismo."',
											  		  '".$nroorden."',
											  		  '".$secuencia."',
											  		  '".$coditem."',
											  		  '".$codcommodity."',
											  		  '".utf8_decode($descripcion)."',
											  		  '".$codunidad."',
											  		  '".$cantidad."',
											  		  '".$precio_unitario."',
											  		  '".$precio_cantidad."',
											  		  '".$total_detalle."',
											  		  '".$descporc."',
											  		  '".$descfijo."',
											  		  '".$flagexon."',
											  		  '".$ccostos."',
											  		  '".utf8_decode($obs)."',
											  		  '".formatFechaAMD($fentregadet)."',
													  'PR',
											  		  '".$field_distribucion['CodCuenta']."',
											  		  '".$field_distribucion['cod_partida']."',
													  '".$_SESSION['USUARIO_ACTUAL']."',
													  '".date("Y-m-d H:i:s")."')";
			$query_det = mysql_query($sql) or die ($sql.mysql_error());
			
			if ($flagexon == "S") $monto_no_afecto += $precio_unitario;
			else { 
				$monto_afecto += $precio_cantidad; 
				$monto_impuesto += ($precio_cantidad * $imp / 100);
			}
		}
		
		$monto_bruto = $monto_no_afecto + $monto_afecto;
		$monto_total = $monto_bruto + $monto_impuesto;		
		if ($monto_impuesto != 0) {
			$IVADEFAULT = PARAMETROS('IVADEFAULT');
			$IVACTADEF = PARAMETROS('IVACTADEF');
		} else {
			$IVADEFAULT['IVADEFAULT'] = "";
			$IVACTADEF['IVACTADEF'] = "";
		}
		
		$fentrega = fechaFin(date("d-m-Y"), $dias_entrega);
		//	inserto la orden
		$sql = "INSERT INTO lg_ordencompra (CodOrganismo,
											NroOrden,
											Estado,
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
											MotRechazo,
											MontoAfecto,
											MontoNoAfecto,
											MontoBruto,
											MontoIGV,
											MontoTotal,
											MontoPendiente,
											PlazoEntrega,
											PreparadaPor,
											FechaPreparacion,
											cod_partida,
											CodCuenta,
											UltimoUsuario,
											UltimaFecha)
									VALUES ('".$organismo."',
											'".$nroorden."',
											'PR',
											'".$codproveedor."',
											'".utf8_decode($nomproveedor)."',
											'".formatFechaAMD($fentrega)."',
											'".$clasificacion."',
											'".$formapago."',
											'".$codservicio."',
											'".$almacen_entrega."',
											'".$almacen_ingreso."',
											'".utf8_decode($nomcontacto)."',
											'".$faxcontacto."',
											'".utf8_decode($entregaren)."',
											'".utf8_decode($direccion)."',
											'".utf8_decode($instruccion)."',
											'".utf8_decode($observaciones)."',
											'".utf8_decode($detallada)."',
											'".utf8_decode($razon)."',
											'".$monto_afecto."',
											'".$monto_no_afecto."',
											'".$monto_bruto."',
											'".$monto_impuesto."',
											'".$monto_total."',
											'".$monto_total."',
											'".$dias_entrega."',
											'".$_SESSION['CODPERSONA_ACTUAL']."',
											'".date("Y-m-d H:i:s")."',
											'".$IVADEFAULT['IVADEFAULT']."',
											'".$IVACTADEF['IVACTADEF']."',
											'".$_SESSION['USUARIO_ACTUAL']."',
											'".date("Y-m-d H:i:s")."')";
		$query_mast = mysql_query($sql) or die ($sql.mysql_error());
		
		//	inserto la distribucion contable y presupuestaria
		$sql = "SELECT cod_partida, CodCuenta, SUM(PrecioCantidad) AS Monto
				FROM lg_ordencompradetalle
				WHERE
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$nroorden."'
				GROUP BY cod_partida
				ORDER BY Secuencia";
		$query_distribucion = mysql_query($sql) or die ($sql.mysql_error());	$i=0;
		while ($field_distribucion = mysql_fetch_array($query_distribucion)) {	$i++;
			$sql = "INSERT INTO lg_distribucionoc (
								CodOrganismo,
								NroOrden,
								Secuencia,
								cod_partida,
								CodCuenta,
								Monto,
								Periodo,
								Estado,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$organismo."',
								'".$nroorden."',
								'".$i."',
								'".$field_distribucion['cod_partida']."',
								'".$field_distribucion['CodCuenta']."',
								'".$field_distribucion['Monto']."',
								'".date("Y-m")."',
								'CO',
								'".$_SESSION['USUARIO_ACTUAL']."',
								'".date("Y-m-d H:i:s")."'
					)";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		}
		$sql = "SELECT cod_partida, CodCuenta, MontoIGV
				FROM lg_ordencompra
				WHERE
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$nroorden."'";
		$query_distribucion = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_distribucion = mysql_fetch_array($query_distribucion)) {	$i++;
			$sql = "INSERT INTO lg_distribucionoc (
								CodOrganismo,
								NroOrden,
								Secuencia,
								cod_partida,
								CodCuenta,
								Monto,
								Periodo,
								Estado,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$organismo."',
								'".$nroorden."',
								'".$i."',
								'".$field_distribucion['cod_partida']."',
								'".$field_distribucion['CodCuenta']."',
								'".$field_distribucion['MontoIGV']."',
								'".date("Y-m")."',
								'CO',
								'".$_SESSION['USUARIO_ACTUAL']."',
								'".date("Y-m-d H:i:s")."'
					)";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		}
		
		//	actualizo presupuesto
		$sql = "SELECT *
				FROM lg_distribucionoc
				WHERE
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$nroorden."' AND
					Periodo = '".date("Y-m")."'
				ORDER BY Secuencia";
		$query_distribucionoc = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_distribucionoc = mysql_fetch_array($query_distribucionoc)) {
			$sql = "UPDATE pv_presupuestodet
					SET MontoCompromiso = MontoCompromiso + ".$field_distribucionoc['Monto']."
					WHERE
						Organismo = '".$organismo."' AND
						CodPresupuesto = (SELECT CodPresupuesto FROM pv_presupuesto WHERE EjercicioPpto = '".date("Y")."') AND
						cod_partida = '".$field_distribucionoc['cod_partida']."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
		}		
		echo "|$nroorden";
	}
	
	//	ACTUALIZAR REGISTRO...
	elseif ($accion == "ACTUALIZAR") {
		if ($btInsertarItem == "true") $tabla = "item"; else $tabla = "commodity";
		$disponible = verificarDisponibilidadPresupuestariaOC($tabla, $detalles, $organismo, date("Y"), $total_impuesto, $nroorden);
		if (!$disponible) die("¡ERROR: Se encontrarón lineas del detalle sin disponibilidad presupuestaria!");
		
		$secuencia = 0;		
		//	actualizo presupuesto
		$sql = "SELECT *
				FROM lg_distribucionoc
				WHERE
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$nroorden."' AND
					Periodo = '".date("Y-m")."'
				ORDER BY Secuencia";
		$query_distribucionoc = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_distribucionoc = mysql_fetch_array($query_distribucionoc)) {
			$sql = "UPDATE pv_presupuestodet
					SET MontoCompromiso = MontoCompromiso - ".$field_distribucionoc['Monto']."
					WHERE
						Organismo = '".$organismo."' AND
						CodPresupuesto = (SELECT CodPresupuesto FROM pv_presupuesto WHERE EjercicioPpto = '".date("Y")."') AND
						cod_partida = '".$field_distribucionoc['cod_partida']."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
		}
		
		//	elimino los detalles
		$sql = "DELETE FROM lg_ordencompradetalle WHERE CodOrganismo = '".$organismo."' AND NroOrden = '".$nroorden."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
		
		//	elimino la distribucion
		$sql = "DELETE FROM lg_distribucionoc WHERE CodOrganismo = '".$organismo."' AND NroOrden = '".$nroorden."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
		
		//	inserto los detalles
		$detalle = split(";", $detalles);
		foreach ($detalle as $linea) {
			list($codigo, $descripcion, $codunidad, $cantidad, $unitario, $descporc, $descfijo, $exon, $fentregadet, $ccostos, $codpartida, $codcuenta, $obs)=SPLIT( '[|]', $linea);
			if ($btInsertarItem == "true") {
				$coditem = $codigo;				
				$sql = "SELECT
							CtaGasto AS CodCuenta,
							PartidaPresupuestal AS cod_partida
						FROM
							lg_itemmast
						WHERE
							CodItem = '".$coditem."'";
				$query_distribucion = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_distribucion) != 0) $field_distribucion = mysql_fetch_array($query_distribucion);
			} else {
				list($cod, $nro) = split("[.]", $codigo);
				$codigo = $cod;
				$codcommodity = $codigo;				
				$sql = "SELECT
							CodCuenta,
							cod_partida
						FROM
							lg_commoditysub
						WHERE
							Codigo = '".$codcommodity."'";
				$query_distribucion = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_distribucion) != 0) $field_distribucion = mysql_fetch_array($query_distribucion);
			}
			if ($exon == "true") $flagexon = "S"; else $flagexon = "N";	
			
			if ($flagexon == "S") $imp = 0.00; else $imp = $impuesto;			
			
			if ($descporc > 0) $precio_unitario = $unitario - ($unitario * $descporc / 100);
			else if ($descfijo > 0) $precio_unitario = $unitario - ($descfijo);
			else $precio_unitario = $unitario;
						
			$precio_cantidad = $precio_unitario * $cantidad;
			$total_detalle = $precio_cantidad + ($precio_cantidad * $imp / 100);
			
			$secuencia++;
			//	inserto los detalles
			$sql = "INSERT INTO lg_ordencompradetalle (CodOrganismo,
													   NroOrden,
													   Secuencia,
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
													   Estado,
													   CodCuenta,
													   cod_partida,
													   UltimoUsuario,
													   UltimaFecha)
											  VALUES ('".$organismo."',
											  		  '".$nroorden."',
											  		  '".$secuencia."',
											  		  '".$coditem."',
											  		  '".$codcommodity."',
											  		  '".utf8_decode($descripcion)."',
											  		  '".$codunidad."',
											  		  '".$cantidad."',
											  		  '".$precio_unitario."',
											  		  '".$precio_cantidad."',
											  		  '".$total_detalle."',
											  		  '".$descp."',
											  		  '".$descf."',
											  		  '".$flagexon."',
											  		  '".$ccostos."',
											  		  '".utf8_decode($obs)."',
											  		  '".formatFechaAMD($fentregadet)."',
													  'PR',
											  		  '".$field_distribucion['CodCuenta']."',
											  		  '".$field_distribucion['cod_partida']."',
													  '".$_SESSION['USUARIO_ACTUAL']."',
													  '".date("Y-m-d H:i:s")."')";
			$query_det = mysql_query($sql) or die ($sql.mysql_error());
			
			if ($flagexon == "S") $monto_no_afecto += $precio_unitario;
			else { 
				$monto_afecto += $precio_cantidad; 
				$monto_impuesto += ($precio_cantidad * $imp / 100);
			}
		}
		
		$monto_bruto = $monto_no_afecto + $monto_afecto;
		$monto_total = $monto_bruto + $monto_impuesto;		
		if ($monto_impuesto != 0) {
			$IVADEFAULT = PARAMETROS('IVADEFAULT');
			$IVACTADEF = PARAMETROS('IVACTADEF');
		} else {
			$IVADEFAULT['IVADEFAULT'] = "";
			$IVACTADEF['IVACTADEF'] = "";
		}
		
		$fentrega = fechaFin(date("d-m-Y"), $dias_entrega);
		//	actualizo la orden
		$sql = "UPDATE lg_ordencompra SET CodOrganismo = '".$organismo."',
											NroOrden = '".$nroorden."',
											CodProveedor = '".$codproveedor."',
											NomProveedor = '".utf8_decode($nomproveedor)."',
											FechaPrometida = '".formatFechaAMD($fentrega)."',
											Clasificacion = '".$clasificacion."',
											CodFormaPago = '".$formapago."',
											CodTipoServicio = '".$codservicio."',
											CodAlmacen = '".$almacen_entrega."',
											CodAlmacenIngreso = '".$almacen_ingreso."',
											NomContacto = '".utf8_decode($nomcontacto)."',
											FaxContacto = '".$faxcontacto."',
											EntregarEn = '".utf8_decode($entregaren)."',
											DirEntrega = '".utf8_decode($direccion)."',
											InsEntrega = '".utf8_decode($instruccion)."',
											Observaciones = '".utf8_decode($observaciones)."',
											ObsDetallada = '".utf8_decode($detallada)."',
											MotRechazo = '".utf8_decode($razon)."',
											MontoAfecto = '".$monto_afecto."',
											MontoNoAfecto = '".$monto_no_afecto."',
											MontoBruto = '".$monto_bruto."',
											MontoIGV = '".$monto_impuesto."',
											MontoTotal = '".$monto_total."',
											MontoPendiente = '".$monto_total."',
											PlazoEntrega = '".$dias_entrega."',
											cod_partida = '".$IVADEFAULT['IVADEFAULT']."',
											CodCuenta = '".$IVACTADEF['IVACTADEF']."',
											UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
											UltimaFecha = '".date("Y-m-d H:i:s")."'
										WHERE
											CodOrganismo = '".$organismo."' AND
											NroOrden = '".$nroorden."'";
		$query_mast = mysql_query($sql) or die ($sql.mysql_error());
		
		//	inserto la distribucion contable y presupuestaria
		$sql = "SELECT cod_partida, CodCuenta, SUM(PrecioCantidad) AS Monto
				FROM lg_ordencompradetalle
				WHERE
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$nroorden."'
				GROUP BY cod_partida
				ORDER BY Secuencia";
		$query_distribucion = mysql_query($sql) or die ($sql.mysql_error());	$i=0;
		while ($field_distribucion = mysql_fetch_array($query_distribucion)) {	$i++;
			$sql = "INSERT INTO lg_distribucionoc (
								CodOrganismo,
								NroOrden,
								Secuencia,
								cod_partida,
								CodCuenta,
								Monto,
								Periodo,
								Estado,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$organismo."',
								'".$nroorden."',
								'".$i."',
								'".$field_distribucion['cod_partida']."',
								'".$field_distribucion['CodCuenta']."',
								'".$field_distribucion['Monto']."',
								'".date("Y-m")."',
								'CO',
								'".$_SESSION['USUARIO_ACTUAL']."',
								'".date("Y-m-d H:i:s")."'
					)";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		}
		$sql = "SELECT cod_partida, CodCuenta, MontoIGV
				FROM lg_ordencompra
				WHERE
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$nroorden."'";
		$query_distribucion = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_distribucion = mysql_fetch_array($query_distribucion)) {	$i++;
			$sql = "INSERT INTO lg_distribucionoc (
								CodOrganismo,
								NroOrden,
								Secuencia,
								cod_partida,
								CodCuenta,
								Monto,
								Periodo,
								Estado,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$organismo."',
								'".$nroorden."',
								'".$i."',
								'".$field_distribucion['cod_partida']."',
								'".$field_distribucion['CodCuenta']."',
								'".$field_distribucion['MontoIGV']."',
								'".date("Y-m")."',
								'CO',
								'".$_SESSION['USUARIO_ACTUAL']."',
								'".date("Y-m-d H:i:s")."'
					)";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		}
		
		//	actualizo presupuesto
		$sql = "SELECT *
				FROM lg_distribucionoc
				WHERE
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$nroorden."' AND
					Periodo = '".date("Y-m")."'
				ORDER BY Secuencia";
		$query_distribucionoc = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_distribucionoc = mysql_fetch_array($query_distribucionoc)) {
			$sql = "UPDATE pv_presupuestodet
					SET MontoCompromiso = MontoCompromiso + ".$field_distribucionoc['Monto']."
					WHERE
						Organismo = '".$organismo."' AND
						CodPresupuesto = (SELECT CodPresupuesto FROM pv_presupuesto WHERE EjercicioPpto = '".date("Y")."') AND
						cod_partida = '".$field_distribucionoc['cod_partida']."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
		}		
		echo "|";
	}
	
	//	REVISAR REGISTRO...
	elseif ($accion == "REVISAR") {
		$sql = "UPDATE lg_ordencompra SET Estado = 'RV',
											RevisadaPor = '".$_SESSION['CODPERSONA_ACTUAL']."',
											FechaRevision = '".date("Y-m-d H:i:s")."',
											UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
											UltimaFecha = '".date("Y-m-d H:i:s")."'
										WHERE
											CodOrganismo = '".$organismo."' AND
											NroOrden = '".$nroorden."'";
		$query_mast = mysql_query($sql) or die ($sql.mysql_error());
		
		echo "|";
	}
	
	//	APROBAR REGISTRO...
	elseif ($accion == "APROBAR") {
		$sql = "UPDATE lg_ordencompra SET Estado = 'AP',
											AprobadaPor = '".$_SESSION['CODPERSONA_ACTUAL']."',
											FechaAprobacion = '".date("Y-m-d H:i:s")."',
											UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
											UltimaFecha = '".date("Y-m-d H:i:s")."'
										WHERE
											CodOrganismo = '".$organismo."' AND
											NroOrden = '".$nroorden."'";
		$query_mast = mysql_query($sql) or die ($sql.mysql_error());
		
		$sql = "UPDATE lg_ordencompradetalle SET Estado = 'PE',
													UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
													UltimaFecha = '".date("Y-m-d H:i:s")."'
												WHERE
													CodOrganismo = '".$organismo."' AND
													NroOrden = '".$nroorden."'";
		$query_det = mysql_query($sql) or die ($sql.mysql_error());
		
		echo "|";
	}
	
	//	ANULAR REGISTRO...
	elseif ($accion == "ANULAR") {
		$sql = "UPDATE lg_ordencompra SET MotRechazo = '".utf8_decode($observaciones)."',
											Estado = 'AN',
											UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
											UltimaFecha = '".date("Y-m-d H:i:s")."'
										WHERE
											CodOrganismo = '".$organismo."' AND
											NroOrden = '".$nroorden."'";
		$query_mast = mysql_query($sql) or die ($sql.mysql_error());
		
		$sql = "UPDATE lg_ordencompradetalle SET Estado = 'AN',
												 UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
												 UltimaFecha = '".date("Y-m-d H:i:s")."'
											WHERE
												 CodOrganismo = '".$organismo."' AND
												 NroOrden = '".$nroorden."'";
		$query_det = mysql_query($sql) or die ($sql.mysql_error());
		
		echo "|";
	}
	
	//	CERRAR REGISTRO...
	elseif ($accion == "CERRAR") {
		$sql = "UPDATE lg_ordencompra SET MotRechazo = '".utf8_decode($observaciones)."',
											Estado = 'CE',
											UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
											UltimaFecha = '".date("Y-m-d H:i:s")."'
										WHERE
											CodOrganismo = '".$organismo."' AND
											NroOrden = '".$nroorden."'";
		$query_mast = mysql_query($sql) or die ($sql.mysql_error());
		
		$sql = "UPDATE lg_ordencompradetalle SET Estado = 'CE',
												 UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
												 UltimaFecha = '".date("Y-m-d H:i:s")."'
											WHERE
												 CodOrganismo = '".$organismo."' AND
												 NroOrden = '".$nroorden."'";
		$query_det = mysql_query($sql) or die ($sql.mysql_error());
		
		echo "|";
	}	
	break;

//	ORDENES DE SERVICIO...
case "ORDENES-SERVICIO":
	//	INSERTAR REGISTRO...
	if ($accion == "INSERTAR") {
		$disponible = verificarDisponibilidadPresupuestariaOS($tabla, $detalles, $organismo, date("Y"), $total_impuesto, $nroorden);
		if (!$disponible) die("¡ERROR: Se encontrarón lineas del detalle sin disponibilidad presupuestaria!");
		
		$nroorden = getCodigo_2("lg_ordenservicio", "NroOrden", "CodOrganismo", $organismo, 10);	
		$secuencia = 0;
		$total_precio_cantidad = 0;
		$total_impuesto = 0;
		$monto_total = 0;
		//	inserto los detalles
		$detalle = split(";", $detalles);
		foreach ($detalle as $linea) {
			list($codigo, $descripcion_det, $cantidad, $unitario, $esperada, $ccostos, $activo, $terminado, $codpartida, $codcuenta, $obs)=SPLIT( '[|]', $linea);
			list($commodity, $nro) = split("[.]", $codigo);
			//	----------
			$sql = "SELECT
						CodCuenta,
						cod_partida
					FROM lg_commoditysub
					WHERE Codigo = '".$commodity."'";
			$query_distribucion = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_distribucion) != 0) $field_distribucion = mysql_fetch_array($query_distribucion);
			//	----------			
			if ($terminado == "true") $flagterminado = "S"; else $flagterminado = "N";
			
			$precio_cantidad = $unitario * $cantidad;
			$monto_impuesto = $precio_cantidad * $impuesto / 100;
			$total = $precio_cantidad + $monto_impuesto;
			
			$total_precio_cantidad += $precio_cantidad;
			$total_impuesto += $monto_impuesto;
			$monto_total += $total;
			
			$secuencia++;
			//	insert los detalles de la orden
			$sql = "INSERT INTO lg_ordenserviciodetalle (CodOrganismo,
													   	 NroOrden,
														 Secuencia,
														 CommoditySub,
														 Descripcion,
														 CantidadPedida,
														 PrecioUnit,
														 Total,
														 FlagTerminado,
														 CodCentroCosto,
														 Comentarios,
														 FechaEsperadaTermino,
														 CodActivo,
														 CodCuenta,
														 cod_partida,
														 UltimoUsuario,
														 UltimaFecha)
											  VALUES ('".$organismo."',
											  		  '".$nroorden."',
											  		  '".$secuencia."',
											  		  '".$commodity."',
											  		  '".utf8_decode($descripcion_det)."',
											  		  '".$cantidad."',
											  		  '".$unitario."',
											  		  '".$total."',
											  		  '".$flagterminado."',
											  		  '".$ccostos."',
											  		  '".utf8_decode($obs)."',
											  		  '".formatFechaAMD($esperada)."',
											  		  '".$activo."',
											  		  '".$field_distribucion['CodCuenta']."',
											  		  '".$field_distribucion['cod_partida']."',
													  '".$_SESSION['USUARIO_ACTUAL']."',
													  '".date("Y-m-d H:i:s")."')";
			$query_det = mysql_query($sql) or die ($sql.mysql_error());
		}
		
		if ($total_impuesto != 0) {
			$IVADEFAULT = PARAMETROS('IVADEFAULT');
			$IVACTADEF = PARAMETROS('IVACTADEF');
		} else {
			$IVADEFAULT['IVADEFAULT'] = "";
			$IVACTADEF['IVACTADEF'] = "";
		}
		
		//	inserto orden
		$sql = "INSERT INTO lg_ordenservicio (CodOrganismo,
											  NroOrden,
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
											  Observaciones,
											  FechaValidoDesde,
											  FechaValidoHasta,
											  CodCentroCosto,
											  PreparadaPor,
											  FechaPreparacion,
											  Estado,
											  cod_partida,
											  CodCuenta,
											  UltimoUsuario,
											  UltimaFecha)
									VALUES ('".$organismo."',
											'".$nroorden."',
											'".$dependencia."',
											'".$codproveedor."',
											'".utf8_decode($nomproveedor)."',
											'".$formapago."',
											'".$nrointerno."',
											'".formatFechaAMD($fdocumento)."',
											'".$dias_pagar."',
											'".$tipopago."',
											'".$codservicio."',
											'".$dentrega."',
											'".formatFechaAMD($fentrega)."',
											'".$total_precio_cantidad."',
											'".$total_impuesto."',
											'".$monto_total."',
											'".utf8_decode($descripcion)."',
											'".utf8_decode($descripcion_adicional)."',
											'".utf8_decode($observaciones)."',
											'".formatFechaAMD($fdesde)."',
											'".formatFechaAMD($fhasta)."',
											'".$codccosto."',
											'".$_SESSION['CODPERSONA_ACTUAL']."',
											'".date("Y-m-d H:i:s")."',
											'PR',
											'".$IVADEFAULT['IVADEFAULT']."',
											'".$IVACTADEF['IVACTADEF']."',
											'".$_SESSION['USUARIO_ACTUAL']."',
											'".date("Y-m-d H:i:s")."')";
		$query_mast = mysql_query($sql) or die ($sql.mysql_error());
		
		//	inserto la distribucion cntable y presupuestaria
		$sql = "SELECT cod_partida, CodCuenta, SUM(CantidadPedida * PrecioUnit) AS Monto
				FROM lg_ordenserviciodetalle
				WHERE
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$nroorden."'
				GROUP BY cod_partida
				ORDER BY Secuencia";
		$query_distribucion = mysql_query($sql) or die ($sql.mysql_error());	$i=0;
		while ($field_distribucion = mysql_fetch_array($query_distribucion)) {	$i++;
			$sql = "INSERT INTO lg_distribucionos (
								CodOrganismo,
								NroOrden,
								Secuencia,
								cod_partida,
								CodCuenta,
								Monto,
								Periodo,
								Estado,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$organismo."',
								'".$nroorden."',
								'".$i."',
								'".$field_distribucion['cod_partida']."',
								'".$field_distribucion['CodCuenta']."',
								'".$field_distribucion['Monto']."',
								'".date("Y-m")."',
								'CO',
								'".$_SESSION['USUARIO_ACTUAL']."',
								'".date("Y-m-d H:i:s")."'
					)";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		}
		$sql = "SELECT cod_partida, CodCuenta, MontoIva
				FROM lg_ordenservicio
				WHERE
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$nroorden."'";
		$query_distribucion = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_distribucion = mysql_fetch_array($query_distribucion)) {	$i++;
			$sql = "INSERT INTO lg_distribucionos (
								CodOrganismo,
								NroOrden,
								Secuencia,
								cod_partida,
								CodCuenta,
								Monto,
								Periodo,
								Estado,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$organismo."',
								'".$nroorden."',
								'".$i."',
								'".$field_distribucion['cod_partida']."',
								'".$field_distribucion['CodCuenta']."',
								'".$field_distribucion['MontoIva']."',
								'".date("Y-m")."',
								'CO',
								'".$_SESSION['USUARIO_ACTUAL']."',
								'".date("Y-m-d H:i:s")."'
					)";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		}
		
		//	actualizo presupuesto
		$sql = "SELECT *
				FROM lg_distribucionos
				WHERE
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$nroorden."' AND
					Periodo = '".date("Y-m")."'
				ORDER BY Secuencia";
		$query_distribucionoc = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_distribucionoc = mysql_fetch_array($query_distribucionoc)) {
			$sql = "UPDATE pv_presupuestodet
					SET MontoCompromiso = MontoCompromiso + ".$field_distribucionoc['Monto']."
					WHERE
						Organismo = '".$organismo."' AND
						CodPresupuesto = (SELECT CodPresupuesto FROM pv_presupuesto WHERE EjercicioPpto = '".date("Y")."') AND
						cod_partida = '".$field_distribucionoc['cod_partida']."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
		}
		
		echo "|$nroorden";
	}
	
	//	ACTUALIZAR REGISTRO...
	elseif ($accion == "ACTUALIZAR") {
		$disponible = verificarDisponibilidadPresupuestariaOS($tabla, $detalles, $organismo, date("Y"), $total_impuesto, $nroorden);
		if (!$disponible) die("¡ERROR: Se encontrarón lineas del detalle sin disponibilidad presupuestaria!");
		
		$secuencia = 0;
		
		//	actualizo presupuesto
		$sql = "SELECT *
				FROM lg_distribucionos
				WHERE
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$nroorden."' AND
					Periodo = '".date("Y-m")."'
				ORDER BY Secuencia";
		$query_distribucionoc = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_distribucionoc = mysql_fetch_array($query_distribucionoc)) {
			$sql = "UPDATE pv_presupuestodet
					SET MontoCompromiso = MontoCompromiso - ".$field_distribucionoc['Monto']."
					WHERE
						Organismo = '".$organismo."' AND
						CodPresupuesto = (SELECT CodPresupuesto FROM pv_presupuesto WHERE EjercicioPpto = '".date("Y")."') AND
						cod_partida = '".$field_distribucionoc['cod_partida']."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
		}
		
		//	elimino los detalles
		$sql = "DELETE FROM lg_ordenserviciodetalle WHERE CodOrganismo = '".$organismo."' AND NroOrden = '".$nroorden."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
		
		//	elimino la distribucion
		$sql = "DELETE FROM lg_distribucionos WHERE CodOrganismo = '".$organismo."' AND NroOrden = '".$nroorden."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
		
		//	inserto los detalles
		$detalle = split(";", $detalles);
		foreach ($detalle as $linea) {
			list($registro, $descripcion_det, $cantidad, $unitario, $esperada, $ccostos, $activo, $terminado, $codpartida, $codcuenta, $obs)=SPLIT( '[|]', $linea);
			list($codigo, $sec)=SPLIT( '[.]', $linea);
			
			//	----------
			$sql = "SELECT
						CodCuenta,
						cod_partida
					FROM
						lg_commoditysub
					WHERE
						Codigo = '".$codigo."'";
			$query_distribucion = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_distribucion) != 0) $field_distribucion = mysql_fetch_array($query_distribucion);
			//	----------
			
			if ($terminado == "true") $flagterminado = "S"; else $flagterminado = "N";
			
			$total_detalle = ($unitario + ($unitario * $impuesto / 100)) * $cantidad;
			
			$secuencia++;
			//	inserto los detalles de la orden
			$sql = "INSERT INTO lg_ordenserviciodetalle (CodOrganismo,
													   	 NroOrden,
														 Secuencia,
														 CommoditySub,
														 Descripcion,
														 CantidadPedida,
														 PrecioUnit,
														 Total,
														 FlagTerminado,
														 CodCentroCosto,
														 Comentarios,
														 FechaEsperadaTermino,
														 CodActivo,
														 CodCuenta,
														 cod_partida,
														 UltimoUsuario,
														 UltimaFecha)
											  VALUES ('".$organismo."',
											  		  '".$nroorden."',
											  		  '".$secuencia."',
											  		  '".$codigo."',
											  		  '".utf8_decode($descripcion_det)."',
											  		  '".$cantidad."',
											  		  '".$unitario."',
											  		  '".$total_detalle."',
											  		  '".$flagterminado."',
											  		  '".$ccostos."',
											  		  '".utf8_decode($obs)."',
											  		  '".formatFechaAMD($esperada)."',
											  		  '".$activo."',
											  		  '".$field_distribucion['CodCuenta']."',
											  		  '".$field_distribucion['cod_partida']."',
													  '".$_SESSION['USUARIO_ACTUAL']."',
													  '".date("Y-m-d H:i:s")."')";
			$query_det = mysql_query($sql) or die ($sql.mysql_error());
			
			$monto_original += ($unitario * $cantidad);
			$monto_iva += ($monto_original * $impuesto / 100);
			$monto_total = ($monto_original + $monto_iva);
		}
		
		if ($monto_iva != 0) {
			$IVADEFAULT = PARAMETROS('IVADEFAULT');
			$IVACTADEF = PARAMETROS('IVACTADEF');
		} else {
			$IVADEFAULT['IVADEFAULT'] = "";
			$IVACTADEF['IVACTADEF'] = "";
		}
		
		//	actualizo la orden
		$sql = "UPDATE lg_ordenservicio SET NroInterno = '".$nrointerno."',
											FechaDocumento = '".formatFechaAMD($fdocumento)."',
											DiasPago = '".$dias_pagar."',
											PlazoEntrega = '".$dentrega."',
											FechaEntrega = '".formatFechaAMD($fentrega)."',
											MontoOriginal = '".$monto_original."',
											MontoIva = '".$monto_iva."',
											TotalMontoIva = '".$monto_total."',
											Descripcion = '".utf8_decode($descripcion)."',
											DescAdicional = '".utf8_decode($descripcion_adicional)."',
											Observaciones = '".utf8_decode($observaciones)."',
											FechaValidoDesde = '".formatFechaAMD($fdesde)."',
											FechaValidoHasta = '".formatFechaAMD($fhasta)."',
											cod_partida = '".$IVADEFAULT['IVADEFAULT']."',
											CodCuenta = '".$IVACTADEF['IVACTADEF']."',
											UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
											UltimaFecha = '".date("Y-m-d H:i:s")."'
										WHERE
											CodOrganismo = '".$organismo."' AND
											NroOrden = '".$nroorden."'";
		$query_mast = mysql_query($sql) or die ($sql.mysql_error());
		
		//	inserto la distribucion cntable y presupuestaria
		$sql = "SELECT cod_partida, CodCuenta, SUM(CantidadPedida * PrecioUnit) AS Monto
				FROM lg_ordenserviciodetalle
				WHERE
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$nroorden."'
				GROUP BY cod_partida
				ORDER BY Secuencia";
		$query_distribucion = mysql_query($sql) or die ($sql.mysql_error());	$i=0;
		while ($field_distribucion = mysql_fetch_array($query_distribucion)) {	$i++;
			$sql = "INSERT INTO lg_distribucionos (
								CodOrganismo,
								NroOrden,
								Secuencia,
								cod_partida,
								CodCuenta,
								Monto,
								Periodo,
								Estado,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$organismo."',
								'".$nroorden."',
								'".$i."',
								'".$field_distribucion['cod_partida']."',
								'".$field_distribucion['CodCuenta']."',
								'".$field_distribucion['Monto']."',
								'".date("Y-m")."',
								'CO',
								'".$_SESSION['USUARIO_ACTUAL']."',
								'".date("Y-m-d H:i:s")."'
					)";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		}
		$sql = "SELECT cod_partida, CodCuenta, MontoIva
				FROM lg_ordenservicio
				WHERE
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$nroorden."'";
		$query_distribucion = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_distribucion = mysql_fetch_array($query_distribucion)) {	$i++;
			$sql = "INSERT INTO lg_distribucionos (
								CodOrganismo,
								NroOrden,
								Secuencia,
								cod_partida,
								CodCuenta,
								Monto,
								Periodo,
								Estado,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$organismo."',
								'".$nroorden."',
								'".$i."',
								'".$field_distribucion['cod_partida']."',
								'".$field_distribucion['CodCuenta']."',
								'".$field_distribucion['MontoIva']."',
								'".date("Y-m")."',
								'CO',
								'".$_SESSION['USUARIO_ACTUAL']."',
								'".date("Y-m-d H:i:s")."'
					)";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		}
		
		//	actualizo presupuesto
		$sql = "SELECT *
				FROM lg_distribucionos
				WHERE
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$nroorden."' AND
					Periodo = '".date("Y-m")."'
				ORDER BY Secuencia";
		$query_distribucionoc = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_distribucionoc = mysql_fetch_array($query_distribucionoc)) {
			$sql = "UPDATE pv_presupuestodet
					SET MontoCompromiso = MontoCompromiso + ".$field_distribucionoc['Monto']."
					WHERE
						Organismo = '".$organismo."' AND
						CodPresupuesto = (SELECT CodPresupuesto FROM pv_presupuesto WHERE EjercicioPpto = '".date("Y")."') AND
						cod_partida = '".$field_distribucionoc['cod_partida']."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
		}
		
		echo "|";
	}
	
	//	REVISAR REGISTRO...
	elseif ($accion == "REVISAR") {
		$sql = "UPDATE lg_ordenservicio SET Estado = 'RV',
											RevisadaPor = '".$_SESSION['CODPERSONA_ACTUAL']."',
											FechaRevision = '".date("Y-m-d H:i:s")."',
											UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
											UltimaFecha = '".date("Y-m-d H:i:s")."'
										WHERE
											CodOrganismo = '".$organismo."' AND
											NroOrden = '".$nroorden."'";
		$query_mast = mysql_query($sql) or die ($sql.mysql_error());
		
		echo "|";
	}
	
	//	APROBAR REGISTRO...
	elseif ($accion == "APROBAR") {
		$sql = "UPDATE lg_ordenservicio SET Observaciones = '".utf8_decode($observaciones)."',
											Estado = 'AP',
											AprobadaPor = '".$_SESSION['CODPERSONA_ACTUAL']."',
											FechaAprobacion = '".date("Y-m-d H:i:s")."',
											UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
											UltimaFecha = '".date("Y-m-d H:i:s")."'
										WHERE
											CodOrganismo = '".$organismo."' AND
											NroOrden = '".$nroorden."'";
		$query_mast = mysql_query($sql) or die ($sql.mysql_error());
		
		echo "|";
	}
	
	//	ANULAR REGISTRO...
	elseif ($accion == "ANULAR") {
		$sql = "UPDATE lg_ordenservicio SET MotRechazo = '".utf8_decode($observaciones)."',
											Estado = 'AN',
											UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
											UltimaFecha = '".date("Y-m-d H:i:s")."'
										WHERE
											CodOrganismo = '".$organismo."' AND
											NroOrden = '".$nroorden."'";
		$query_mast = mysql_query($sql) or die ($sql.mysql_error());
		
		$sql = "UPDATE lg_ordenserviciodetalle SET FlagTerminado = 'S',
												   UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
												   UltimaFecha = '".date("Y-m-d H:i:s")."'
											  WHERE
												   CodOrganismo = '".$organismo."' AND
												   NroOrden = '".$nroorden."'";
		$query_det = mysql_query($sql) or die ($sql.mysql_error());
		
		echo "|";
	}
	
	//	CERRAR REGISTRO...
	elseif ($accion == "CERRAR") {
		$sql = "UPDATE lg_ordenservicio SET Estado = 'CE',
											UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
											UltimaFecha = '".date("Y-m-d H:i:s")."'
										WHERE
											CodOrganismo = '".$organismo."' AND
											NroOrden = '".$nroorden."'";
		$query_mast = mysql_query($sql) or die ($sql.mysql_error());
		
		$sql = "UPDATE lg_ordenserviciodetalle SET FlagTerminado = 'S',
												   UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
												   UltimaFecha = '".date("Y-m-d H:i:s")."'
											  WHERE
												   CodOrganismo = '".$organismo."' AND
												   NroOrden = '".$nroorden."'";
		$query_det = mysql_query($sql) or die ($sql.mysql_error());
		
		echo "|";
	}
	
	//	CONFIRMAR...
	elseif ($accion == "confirmarOrdenServicio") {
		$saldo = (int) $saldo;
		if ($saldo == 0) $flagterminado = "S"; else $flagterminado = "N";
		
		//	inserto la confirmacion
		$nroconfirmacion = getCodigo("lg_confirmacionservicio", "NroConfirmacion", 4);
		$referencia = "$nroorden-$nroconfirmacion";
		$sql = "INSERT INTO lg_confirmacionservicio (
							NroConfirmacion,
							CodOrganismo,
							NroOrden,
							Secuencia,
							DocumentoReferencia,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$nroconfirmacion."',
							'".$codorganismo."',
							'".$nroorden."',
							'".$secuencia."',
							'".$referencia."',
							'".$_SESSION['USUARIO_ACTUAL']."',
							'".date("Y-m-d H:i:s")."'
				)";
		$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		
		//	actualizo el detalle de la orden
		$sql = "UPDATE lg_ordenserviciodetalle 
				SET 
					FlagTerminado = '".$flagterminado."',
					CantidadRecibida = '".$cantidad_recibir."',
					FechaTermino = '".formatFechaAMD($ftermino)."',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE
					CodOrganismo = '".$codorganismo."' AND
					NroOrden = '".$nroorden."' AND
					Secuencia = '".$secuencia."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
		
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
					osd.CodOrganismo = '".$codorganismo."' AND
					osd.NroOrden = '".$nroorden."' AND
					osd.Secuencia = '".$secuencia."'";
		$query_os = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_os) != 0) $field_os = mysql_fetch_array($query_os);		
		$monto_iva = $field_os['PrecioCantidad'] * $field_os['FactorPorcentaje'] / 100;
		$monto_total = $monto_iva + $field_os['PrecioCantidad'];
		//	inserto el documento
		$sql = "INSERT INTO ap_documentos (
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
							Estado,
							Comentarios,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$codorganismo."',
							'".$field_os['CodProveedor']."',
							'SER',
							'".$nroorden."-".$nroconfirmacion."',
							'".formatFechaAMD($ftermino)."',
							'OS',
							'".$nroorden."',
							'".$field_os['PrecioCantidad']."',
							'".$monto_iva."',
							'".$monto_total."',
							'PR',
							'".$field_os['Descripcion']."',
							'".$_SESSION['USUARIO_ACTUAL']."',
							'".date("Y-m-d H:i:s")."'
				)";
		$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		
		//	inserto los detalles de el documento
		$sql = "INSERT INTO ap_documentosdetalle (
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
							'".$field_os['CodProveedor']."',
							'SER',
							'".$nroorden."-".$nroconfirmacion."',
							'".$secuencia."',
							'1',
							'".$field_os['CommoditySub']."',
							'".utf8_decode($field_os['Descripcion'])."',
							'".$cantidad_recibir."',
							'".$field_os['PrecioUnit']."',
							'".$field_os['PrecioCantidad']."',
							'".$monto_total."',
							'".$field_os['CodCentroCosto']."',
							'".$_SESSION['USUARIO_ACTUAL']."',
							'".date("Y-m-d H:i:s")."'
				)";
		$query_insert = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	DESCONFIRMAR...
	elseif ($accion == "desconfirmarOrdenServicio") {
		list($codorganismo, $nroorden, $nroconfirmacion)=SPLIT( '[-]', $seldesconfirmar);
		
		// consulto para el documento
		$sql = "SELECT CodProveedor					
				FROM lg_ordenservicio
				WHERE
					CodOrganismo = '".$codorganismo."' AND
					NroOrden = '".$nroorden."'";
		$query_os = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_os) != 0) $field_os = mysql_fetch_array($query_os);
		
		// consulto para el documento
		$sql = "SELECT *					
				FROM lg_confirmacionservicio
				WHERE NroConfirmacion = '".$nroconfirmacion."'";
		$query_cs = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_cs) != 0) $field_cs = mysql_fetch_array($query_cs);
		
		//	actualizo el detalle de la orden de servicio
		$sql = "UPDATE lg_ordenserviciodetalle 
				SET 
					FlagTerminado = 'N',
					CantidadRecibida = '0.00',
					FechaTermino = '0000-00-00',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = '".date("Y-m-d H:i:s")."'
				WHERE
					CodOrganismo = '".$codorganismo."' AND
					NroOrden = '".$nroorden."' AND
					Secuencia = '".$field_cs['Secuencia']."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
		
		//	elimino el documento
		$sql = "DELETE FROM ap_documentos 
				WHERE
					CodProveedor = '".$field_os['CodProveedor']."' AND
					DocumentoClasificacion = 'SER' AND
					DocumentoReferencia = '$nroorden-$nroconfirmacion'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
		
		//	elimino los detalles del doucmento
		$sql = "DELETE FROM ap_documentosdetalle
				WHERE
					CodProveedor = '".$field_os['CodProveedor']."' AND
					DocumentoClasificacion = 'SER' AND
					DocumentoReferencia = '$nroorden-$nroconfirmacion'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
		
		// elimino la confirmacion
		$sql = "DELETE FROM lg_confirmacionservicio WHERE NroConfirmacion = '".$nroconfirmacion."'";
		$query_delete = mysql_query($sql) or die ($sql.mysql_error());
	}
	break;
}
?>