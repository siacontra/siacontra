<?php
include("fphp_lg.php");
connect();
///////////////////////////////////////////////////////////////////////////////
//	SCRIPTS PARA AJAX
///////////////////////////////////////////////////////////////////////////////
$error = 0;
switch ($modulo) {

//	RECEPCION EN ALMACEN...
case "ALMACEN-RECEPCION":
	//	CERRAR LINEA ORDEN DE COMPRA EN RECEPCION DE ALMACEN...
	if ($accion == "cerrarLineaOrdenCompra") {
		$linea = split(";", $detalles);
		foreach ($linea as $secuencia) {
			$sql = "UPDATE lg_ordencompradetalle 
					SET Estado = 'CE' 
					WHERE 
						CodOrganismo = '".$codorganismo."' AND 
						NroOrden = '".$nroorden."' AND 
						Secuencia = '".$secuencia."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
		
		//	verifico si tiene detalles pendiente la orden de compra si no tiene la paso a estado completado
		$sql = "SELECT ocd.*
				FROM
					lg_ordencompradetalle ocd
					INNER JOIN lg_ordencompra oc ON (oc.CodOrganismo = ocd.CodOrganismo AND
													 oc.NroOrden = ocd.NroOrden)
				WHERE
					ocd.CodOrganismo = '".$codorganismo."' AND
					ocd.NroOrden = '".$nroorden."' AND
					ocd.Estado = 'PE'";
		$query_orden_det = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_orden_det) == 0) {
			$sql = "UPDATE lg_ordencompra
					SET Estado = 'CO'
					WHERE 
						CodOrganismo = '".$codorganismo."' AND 
						NroOrden = '".$nroorden."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
		
		echo "|".mysql_num_rows($query_orden_det);
	}
	
	//	INSERTAR NUEVA TRANSACCION DE RECEPCION DE ORDEN COMPRA
	elseif ($accion == "INSERTAR-TRANSACCION") {
		//	consulto el porcentaje igv
		$codimpuesto = getParametro("IGVCODIGO");
		$sql = "SELECT FactorPorcentaje FROM mastimpuestos WHERE CodImpuesto = '".$codimpuesto."'";
		$query_impuesto = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_impuesto) != 0) $field_impuesto = mysql_fetch_array($query_impuesto);
		
		//	consulto los datos de la orden de compra
		$sql = "SELECT *
				FROM lg_ordencompra
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					NroOrden = '".$docinterno."'";
		$query_orden = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_orden) != 0) $field_orden = mysql_fetch_array($query_orden);
		
		//	recorro los detalles de la orden en busca de errores
		$linea = split(";", $detalles);
		foreach ($linea as $registro) {
			list($coditem, $codunidad, $stock, $pedida, $recibida, $pendiente, $cantidad, $preciounit, $docreferenciaorden)=SPLIT( '[|]', $registro);
			list($refcoddoc, $refnrodoc, $refsec)=SPLIT( '[-]', $docreferenciaorden);			
			if ($pendiente > $pedida) die ("¡Cantidad Recibida no puede ser mayor a la Cantidad Pedida Item # $coditem!");
			elseif ($cantidad <= 0) die ("¡La cantidad a recepcionar no puede ser menor o igual a cero Item # $coditem!");
		}
		
		//	consulto si existe el numero de documento
		$sql = "SELECT * 
				FROM ap_documentos 
				WHERE
					Anio = '".$anio."' AND
					CodProveedor = '".$field_orden['CodProveedor']."' AND 
					DocumentoClasificacion = '".$transaccion."' AND 
					DocumentoReferencia = '".$docref."'";
		$query_consulta = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_consulta) != 0) die("¡Nro. de Referencia ya Ingresado!");
		
		//	inserto la transaccion
		$nrodocumento = getCodigo_3("lg_transaccion", "NroDocumento", "CodOrganismo", "CodDocumento", $organismo, $docgenerar, 6);
		$sql = "INSERT INTO lg_transaccion
					(CodOrganismo,
					 CodDependencia,
					 CodDocumento,
					 NroDocumento,
					 CodTransaccion,
					 FechaDocumento,
					 Periodo,
					 CodAlmacen,
					 CodCentroCosto,
					 CodDocumentoReferencia,
					 NroDocumentoReferencia,
					 IngresadoPor,
					 RecibidoPor,
					 Comentarios,
					 MotRechazo,
					 FlagManual,
					 FlagPendiente,
					 Estado,
					 ReferenciaOrganismo,
					 ReferenciaNroDocumento,
					 DocumentoReferencia,
					 UltimoUsuario,
					 UltimaFecha)
				VALUES
					('".$organismo."',
					 '".$dependencia."',
					 '".$docgenerar."',
					 '".$nrodocumento."',
					 '".$transaccion."',
					 '".formatFechaAMD($ftransaccion)."',
					 '".$periodo."',
					 '".$almacen."',
					 '".$ccosto."',
					 '".$docreferencia."',
					 '".$nrodocreferencia."',
					 '".$_SESSION['CODPERSONA_ACTUAL']."',
					 '".$recibidopor."',
					 '".($comentarios)."',
					 '".($razon)."',
					 '".$flagmanual."',
					 '".$flagpendiente."',
					 'CO',
					 '".$organismo."',
					 '".$docinterno."',
					 '".$docref."',
					 '".$_SESSION['USUARIO_ACTUAL']."',
					 NOW())";
		$query_mast = mysql_query($sql) or die ($sql.mysql_error());
		
		//	inserto el documento ctas x pagar
		$sql = "INSERT INTO ap_documentos
					(CodOrganismo,
					 CodProveedor,
					 DocumentoClasificacion,
					 DocumentoReferencia,					 
					 Fecha,					 
					 ReferenciaTipoDocumento,
					 ReferenciaNroDocumento,
					 Estado,
					 TransaccionTipoDocumento,
					 TransaccionNroDocumento,
					 Comentarios,
					 Anio,
					 UltimoUsuario,
					 UltimaFecha)
				VALUES
					('".$organismo."',
					 '".$field_orden['CodProveedor']."',
					 '".$transaccion."',
					 '".$docref."',
					 '".formatFechaAMD($ftransaccion)."',
					 'OC',
					 '".$docinterno."',
					 'PR',
					 '".$docgenerar."',
					 '".$nrodocumento."',
					 '".($comentarios)."',
					 '".$anio."',
					 '".$_SESSION['USUARIO_ACTUAL']."',
					 NOW())";
		$query_mast = mysql_query($sql) or die ($sql.mysql_error());
		
		$secuencia = 0;		
		$total_precio_cantidad = 0;
		$total_afecto = 0;
		$total_noafecto = 0;
		$total_impuesto = 0;
		$monto_total = 0;
		//	recorro los detalles de la orden para insertar, actualizar
		foreach ($linea as $registro) {
			//	obtengo los datos del detalle
			list($coditem, $codunidad, $stock, $pedida, $recibida, $pendiente, $cantidad, $preciounit, $docreferenciaorden)=SPLIT( '[|]', $registro);
			list($refcoddoc, $refnrodoc, $refsec)=SPLIT( '[-]', $docreferenciaorden);
			
			//	consulto los datos del detalle de la orden de compra
			$sql = "SELECT *
					FROM lg_ordencompradetalle
					WHERE
						Anio = '".$anio."' AND
						CodOrganismo = '".$organismo."' AND
						NroOrden = '".$docinterno."' AND
						CodItem = '".$coditem."'";
			$query_orden_detalle = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_orden_detalle) != 0) $field_orden_detalle = mysql_fetch_array($query_orden_detalle);
			
			$precio_cantidad = $cantidad * $preciounit;
			if ($field_orden_detalle['FlagExonerado'] == "S") {
				$monto_afecto = 0;
				$monto_noafecto = $precio_cantidad;
				$impuesto = 0;
				$total = $precio_cantidad;
			} else {
				$monto_afecto = $precio_cantidad;
				$monto_noafecto = 0;
				$impuesto = $precio_cantidad * $field_impuesto['FactorPorcentaje'] / 100;
				$total = $precio_cantidad + $impuesto;
			}
			
			$total_precio_cantidad += $precio_cantidad;
			$total_afecto += $monto_afecto;
			$total_noafecto += $monto_noafecto;
			$total_impuesto += $impuesto;
			$monto_total += $total;
						
			//	inserto los detalles de la transaccion
			$secuencia++;
			$sql = "INSERT INTO lg_transacciondetalle
						(CodOrganismo,
						 CodDocumento,
						 NroDocumento,
						 Secuencia,
						 CodItem,
						 CodUnidad,
						 CantidadPedida,
						 CantidadRecibida,
						 PrecioUnit,
						 Total,
						 ReferenciaOrganismo,
						 ReferenciaCodDocumento,
						 ReferenciaNroDocumento,
						 ReferenciaSecuencia,
						 UltimoUsuario,
						 UltimaFecha)
					VALUES 
						('".$organismo."',
						 '".$docgenerar."',
						 '".$nrodocumento."',
						 '".$secuencia."',
						 '".$coditem."',
						 '".$codunidad."',
						 '".$pendiente."',
						 '".$cantidad."',
						 '".$preciounit."',
						 '".$precio_cantidad."',
						 '".$organismo."',
						 '".trim($refcoddoc)."',
						 '".trim($refnrodoc)."',
						 '".trim($refsec)."',
					 	 '".$_SESSION['USUARIO_ACTUAL']."',
					 	 NOW())";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			
			//	inserto el detalle de la transaccion en el kardex
			$sql = "INSERT INTO lg_kardex
						(CodItem,
						 CodAlmacen,
						 CodDocumento,
						 NroDocumento,
						 Fecha,
						 CodTransaccion,
						 ReferenciaCodOrganismo,
						 ReferenciaCodDocumento,
						 ReferenciaNroDocumento,
						 ReferenciaSecuencia,
						 Cantidad,
						 PrecioUnitario,
						 MontoTotal,
						 PeriodoContable,
						 UltimoUsuario,
						 UltimaFecha)
					VALUES
						('".$coditem."',
						 '".$almacen."',
						 '".$docgenerar."',
						 '".$nrodocumento."',
						 '".date("Y-m-d")."',
						 '".$transaccion."',
						 '".$organismo."',
						 '".trim($refcoddoc)."',
						 '".trim($refnrodoc)."',
						 '".trim($refsec)."',
						 '".$cantidad."',
						 '".$preciounit."',
						 '".$precio_cantidad."',
						 '".date("Y-m")."',
						 '".$_SESSION['USUARIO_ACTUAL']."',
						 NOW())";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
						
			$cantidad_recibida = $recibida + $cantidad;
			//	si la cantidad pedida es igual a la recibida entonces actualizo la cantidad recibida y estado a completado del detalle de la orden de compra
			if ($pedida == $cantidad_recibida) {
				//	actualizo a el estado y la cantidad recibidad del detalle de la orden de compra
				$sql = "UPDATE lg_ordencompradetalle 
						SET 
							Estado = 'CO' ,
							CantidadRecibida = '$cantidad_recibida'
						WHERE
							Anio = '".$anio."' AND
							CodOrganismo = '".$organismo."' AND 
							NroOrden = '".$nroorden."' AND 
							CodItem = '".$coditem."'";
				$query_orden_det = mysql_query($sql) or die ($sql.mysql_error());
				
				//	verifico si tiene detalles pendiente la orden de compra si no tiene la paso a estado completado
				$sql = "SELECT ocd.*
						FROM
							lg_ordencompradetalle ocd
							INNER JOIN lg_ordencompra oc ON (oc.CodOrganismo = ocd.CodOrganismo AND
															 oc.NroOrden = ocd.NroOrden)
						WHERE
							ocd.Anio = '".$anio."' AND
							ocd.CodOrganismo = '".$organismo."' AND
							ocd.NroOrden = '".$nroorden."' AND
							ocd.Estado = 'PE'";
				$query_orden_det = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_orden_det) == 0) {
					//	actualizo el estado a completado de la orden de compra
					$sql = "UPDATE lg_ordencompra
							SET Estado = 'CO'
							WHERE
								Anio = '".$anio."' AND
								CodOrganismo = '".$organismo."' AND 
								NroOrden = '".$nroorden."'";
					$query = mysql_query($sql) or die ($sql.mysql_error());
				}
			} else {
				//	actualizo la cantidad recibida del detalle de la orden de compra
				$sql = "UPDATE lg_ordencompradetalle 
						SET 
							CantidadRecibida = '$cantidad_recibida'
						WHERE
							Anio = '".$anio."' AND
							CodOrganismo = '".$organismo."' AND 
							NroOrden = '".$nroorden."' AND 
							CodItem = '".$coditem."'";
				$query_orden_det = mysql_query($sql) or die ($sql.mysql_error());
			}
			
			//	consulto si existe el item en el almacen ya ingresado
			$sql = "SELECT * FROM lg_itemalmacen WHERE CodItem = '".$coditem."' AND CodAlmacen = '".$almacen."'";
			$query_item = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_item) == 0) {
				//	si no existe inserto el item en el almacen
				$sql = "INSERT INTO lg_itemalmacen
							(CodItem,
							 CodAlmacen,
							 StockPedido,
							 StockMin,
							 StockMax,
							 StockReorden,
							 TiempoEspera,
							 Estado,
							 UltimoUsuario,
							 UltimaFecha)
						VALUES
							('".$coditem."',
							 '".$almacen."',
							 '0',
							 '0',
							 '0',
							 '0',
							 '0',
							 'A',
							 '".$_SESSION['USUARIO_ACTUAL']."',
							 NOW())";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
				
				//	inserto en el inventario
				$sql = "INSERT INTO lg_itemalmaceninv
							(CodItem,
							 CodAlmacen,
							 Proveedor,
							 FechaIngreso,
							 StockIngreso,
							 StockActual,
							 PrecioUnitario,
							 DocReferencia,
							 IngresadoPor,
							 UltimoUsuario,
							 UltimaFecha)
						VALUES
							('".$coditem."',
							 '".$almacen."',
							 '".$field_orden['CodProveedor']."',
							 '".date("Y-m-d")."',
							 '".$cantidad."',
							 '".$cantidad."',
							 '".$preciounit."',
							 '".trim($refnrodoc)."',
							 '".$_SESSION['CODPERSONA_ACTUAL']."',
							 '".$_SESSION['USUARIO_ACTUAL']."',
							 NOW())";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			} else {
				//	actualizo el inventario
				$sql = "UPDATE lg_itemalmaceninv
						SET 
							StockActual = (StockActual + $cantidad),
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()
						WHERE
							CodAlmacen = '".$almacen."' AND
							CodItem = '".$coditem."'";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			}
			
			//	inserto los detalles del documento ctas x pagar
			$sql = "INSERT INTO ap_documentosdetalle
						(CodProveedor,
						 DocumentoClasificacion,
						 DocumentoReferencia,
						 Secuencia,
						 ReferenciaSecuencia,
						 CodItem,
						 Descripcion,
						 Cantidad,
						 PrecioUnit,
						 PrecioCantidad,
						 Total,
						 CodCentroCosto,
						 Anio,
						 UltimoUsuario,
						 UltimaFecha)
					VALUES
						('".$field_orden['CodProveedor']."',
						 '".$transaccion."',
						 '".$docref."',
						 '".$secuencia."',
						 '".$field_orden_detalle['Secuencia']."',
						 '".$field_orden_detalle['CodItem']."',
						 '".$field_orden_detalle['Descripcion']."',
						 '".$cantidad."',
						 '".$preciounit."',
						 '".$precio_cantidad."',
						 '".$total."',
						 '".$ccosto."',
						 NOW(),
						 '".$_SESSION['USUARIO_ACTUAL']."',
						 NOW())";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		}
		
		//	actualizo los montos del documento ctas x pagar
		$sql = "UPDATE ap_documentos
				SET
					MontoAfecto = '".$total_afecto."',
					MontoNoAfecto = '".$total_noafecto."',
					MontoImpuestos = '".$total_impuesto."',
					MontoTotal = '".$monto_total."',
					MontoPendiente = '".$monto_total."'
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					CodProveedor = '".$field_orden['CodProveedor']."' AND
					DocumentoClasificacion = '".$transaccion."' AND
					DocumentoReferencia = '".$docref."'";
		$query_mast = mysql_query($sql) or die ($sql.mysql_error());
		
		//	si la orden reepcionada viene de un requerimeinto entonces actualizo las cantidades y el estado
		$sql = "SELECT 
					rd.CodRequerimiento,
					rd.Secuencia,
					ocd.CantidadPedida,
					ocd.CantidadRecibida,
					(ocd.CantidadPedida - ocd.CantidadRecibida) AS CantidadPendiente
				FROM 
					lg_requerimientosdet rd
					INNER JOIN lg_requerimientos r ON (rd.CodRequerimiento = r.CodRequerimiento)
					INNER JOIN lg_ordencompradetalle ocd ON (rd.Anio = ocd.Anio AND
															 rd.CodOrganismo = ocd.CodOrganismo AND
															 rd.NroOrden = ocd.NroOrden AND
															 rd.CodItem = ocd.CodItem AND
				                                             rd.CommoditySub = ocd.CommoditySub)
				WHERE
					rd.Anio = '".$anio."' AND
					rd.CodOrganismo = '".$organismo."' AND
					rd.NroOrden = '".$nroorden."' AND
					r.Clasificacion <> 'SER'
				ORDER BY Secuencia";
		$query_requerimientosdet = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_requerimientosdet = mysql_fetch_array($query_requerimientosdet)) {
			//	actualizo cantidad recibida y el estado del detalle del requerimeinto
			$sql = "UPDATE lg_requerimientosdet
					SET
						CantidadRecibida = '".$field_requerimientosdet['CantidadRecibida']."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()
					WHERE
						CodRequerimiento = '".$field_requerimientosdet['CodRequerimiento']."' AND
						Secuencia = '".$field_requerimientosdet['Secuencia']."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
		}
		
		echo "|$nrodocumento";
	}
	break;

//	DESPACHO EN ALMACEN...
case "ALMACEN-DESPACHO":
	//	CERRAR LINEA DE REQUERIMINTO EN DESPACHO DE ALMACEN...
	if ($accion == "cerrarLineaRequerimiento") {
		$linea = split(";", $detalles);
		foreach ($linea as $secuencia) {
			$sql = "UPDATE lg_requerimientosdet 
					SET Estado = 'CE' 
					WHERE 
						CodOrganismo = '".$codorganismo."' AND 
						CodRequerimiento = '".$codrequerimiento."' AND 
						Secuencia = '".$secuencia."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
		
		//	verifico si tiene detalles pendiente el requerimiento si no tiene la paso a estado completado
		$sql = "SELECT rd.*
				FROM
					lg_requerimientosdet rd
					INNER JOIN lg_requerimientos r ON (r.CodOrganismo = rd.CodOrganismo AND
													   r.CodRequerimiento = rd.CodRequerimiento)
				WHERE
					rd.CodOrganismo = '".$codorganismo."' AND
					rd.CodRequerimiento = '".$codrequerimiento."' AND
					rd.Estado = 'PE'";
		$query_req_det = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_req_det) == 0) {
			$sql = "UPDATE lg_requerimientos
					SET Estado = 'CO'
					WHERE 
						CodOrganismo = '".$codorganismo."' AND 
						CodRequerimiento = '".$codrequerimiento."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
		
		echo "|".mysql_num_rows($query_req_det);
	}
	
	//	INSERTAR NUEVA TRANSACCION DE RECEPCION DE ORDEN COMPRA
	elseif ($accion == "INSERTAR-TRANSACCION") {
		$linea = split(";", $detalles);
		foreach ($linea as $registro) {
			list($coditem, $codunidad, $stock, $pedida, $recibida, $pendiente, $cantidad, $preciounit, $docreferenciaorden)=SPLIT( '[|]', $registro);
			list($refcoddoc, $refnrodoc, $refsec)=SPLIT( '[-]', $docreferenciaorden);
			
			if ($pendiente > $pedida) die ("¡Cantidad Recibida no puede ser mayor a la Cantidad Pedida Item # $coditem!");
			elseif ($cantidad <= 0) die ("¡La cantidad a despachar no puede ser menor o igual a cero Item # $coditem!");
		}
		
		$nrodocumento = getCodigo_3("lg_transaccion", "NroDocumento", "CodOrganismo", "CodDocumento", $organismo, $docgenerar, 6);		
		//	inserto la transaccion
		$sql = "INSERT INTO lg_transaccion
					(CodOrganismo,
					 CodDependencia,
					 CodDocumento,
					 NroDocumento,
					 CodTransaccion,
					 FechaDocumento,
					 Periodo,
					 CodAlmacen,
					 CodCentroCosto,
					 CodDocumentoReferencia,
					 NroDocumentoReferencia,
					 IngresadoPor,
					 RecibidoPor,
					 Comentarios,
					 MotRechazo,
					 FlagManual,
					 FlagPendiente,
					 Estado,
					 ReferenciaOrganismo,
					 ReferenciaNroDocumento,
					 DocumentoReferencia,
					 UltimoUsuario,
					 UltimaFecha)
				VALUES
					('".$organismo."',
					 '".$dependencia."',
					 '".$docgenerar."',
					 '".$nrodocumento."',
					 '".$transaccion."',
					 '".formatFechaAMD($ftransaccion)."',
					 '".$periodo."',
					 '".$almacen."',
					 '".$ccosto."',
					 '".$docreferencia."',
					 '".$nrodocreferencia."',
					 '".$_SESSION['CODPERSONA_ACTUAL']."',
					 '".$recibidopor."',
					 '".($comentarios)."',
					 '".($razon)."',
					 '".$flagmanual."',
					 '".$flagpendiente."',
					 'CO',
					 '".$organismo."',
					 '".trim($refnrodoc)."',
					 '".$docref."',
					 '".$_SESSION['USUARIO_ACTUAL']."',
					 NOW())";
		$query_mast = mysql_query($sql) or die ($sql.mysql_error());
		
		$secuencia = 0;
		foreach ($linea as $registro) {
			list($coditem, $codunidad, $stock, $pedida, $recibida, $pendiente, $cantidad, $preciounit, $docreferenciaorden)=SPLIT( '[|]', $registro);
			list($refcoddoc, $refnrodoc, $refsec)=SPLIT( '[-]', $docreferenciaorden);
			$cantidad_recibida = $recibida + $cantidad;
			
			$secuencia++;
			//	inserto los detalles de la transaccion
			$sql = "INSERT INTO lg_transacciondetalle
						(CodOrganismo,
						 CodDocumento,
						 NroDocumento,
						 Secuencia,
						 CodItem,
						 CodUnidad,
						 CantidadPedida,
						 CantidadRecibida,
						 PrecioUnit,
						 Total,
						 ReferenciaOrganismo,
						 ReferenciaCodDocumento,
						 ReferenciaNroDocumento,
						 ReferenciaSecuencia,
						 UltimoUsuario,
						 UltimaFecha)
					VALUES 
						('".$organismo."',
						 '".$docgenerar."',
						 '".$nrodocumento."',
						 '".$secuencia."',
						 '".$coditem."',
						 '".$codunidad."',
						 '".$pendiente."',
						 '".$cantidad."',
						 '".$preciounit."',
						 '".$total."',
						 '".$organismo."',
						 '".trim($refcoddoc)."',
						 '".trim($refnrodoc)."',
						 '".trim($refsec)."',
						 '".$_SESSION['USUARIO_ACTUAL']."',
						 NOW())";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			
			//	inserto el detalle de la transaccion en el kardex
			$sql = "INSERT INTO lg_kardex
						(CodItem,
						 CodAlmacen,
						 CodDocumento,
						 NroDocumento,
						 Fecha,
						 CodTransaccion,
						 ReferenciaCodOrganismo,
						 ReferenciaCodDocumento,
						 ReferenciaNroDocumento,
						 ReferenciaSecuencia,
						 Cantidad,
						 PrecioUnitario,
						 MontoTotal,
						 PeriodoContable,
						 UltimoUsuario,
						 UltimaFecha)
					VALUES
						('".$coditem."',
						 '".$almacen."',
						 '".$docgenerar."',
						 '".$nrodocumento."',
						 '".date("Y-m-d")."',
						 '".$transaccion."',
						 '".$organismo."',
						 '".trim($refcoddoc)."',
						 '".trim($refnrodoc)."',
						 '".trim($refsec)."',
						 '-".$cantidad."',
						 '".$preciounit."',
						 '".$total."',
						 '".date("Y-m")."',
						 '".$_SESSION['USUARIO_ACTUAL']."',
						 NOW())";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			
			//	si la cantidad pedida es igual a la recibida paso a completado los detalles del requerimiento y actualizo la cantidad recibida		
			if ($pedida == $cantidad_recibida) {
				$sql = "UPDATE lg_requerimientosdet 
						SET 
							Estado = 'CO' ,
							CantidadRecibida = '$cantidad_recibida'
						WHERE 
							CodOrganismo = '".$organismo."' AND 
							CodRequerimiento = '".$codrequerimiento."' AND 
							CodItem = '".$coditem."'";
				$query_orden_det = mysql_query($sql) or die ($sql.mysql_error());
				
				//	verifico si no tiene detalles pendiente el requerimiento
				$sql = "SELECT rd.*
						FROM
							lg_requerimientosdet rd
							INNER JOIN lg_requerimientos r ON (r.CodOrganismo = rd.CodOrganismo AND
															   r.CodRequerimiento = rd.CodRequerimiento)
						WHERE
							rd.CodOrganismo = '".$organismo."' AND
							rd.CodRequerimiento = '".$codrequerimiento."' AND
							rd.Estado = 'PE'";
				$query_req_det = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_req_det) == 0) {
					//	paso a completado el requerimiento que no tenga detalles pendiente
					$sql = "UPDATE lg_requerimientos
							SET Estado = 'CO'
							WHERE 
								CodOrganismo = '".$organismo."' AND 
								CodRequerimiento = '".$codrequerimiento."'";
					$query = mysql_query($sql) or die ($sql.mysql_error());
				}
			} else {
				//	actualizo la cantidad recibida
				$sql = "UPDATE lg_requerimientosdet 
						SET 
							CantidadRecibida = '$cantidad_recibida'
						WHERE 
							CodOrganismo = '".$organismo."' AND 
							CodRequerimiento = '".$codrequerimiento."' AND 
							CodItem = '".$coditem."'";
				$query_orden_det = mysql_query($sql) or die ($sql.mysql_error());
			}
			
			//	actualizo el inventario
			$sql = "UPDATE lg_itemalmaceninv
					SET
						StockActual = (StockActual - $cantidad),
						StockComprometido = (StockComprometido + $cantidad),
						PrecioUnitario = '".$preciounit."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()
					WHERE
						CodAlmacen = '".$almacen."' AND
						CodItem = '".$coditem."'";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		}
		echo "|$nrodocumento";
	}
	
	//	DIRIGIR A COMPRAS PARA REPOSICION (REQUERIMIENTO COMPLETO)
	elseif ($accion == "dirigirCompraReposicion") {
		$sql = "UPDATE lg_requerimientosdet 
				SET FlagCompraAlmacen = 'C' 
				WHERE 
					CodOrganismo = '".$codorganismo."' AND 
					CodRequerimiento = '".$codrequerimiento."' AND
					Estado = 'PE' AND
					FlagCompraAlmacen = 'A'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	
	//	DIRIGIR A COMPRAS PARA REPOSICION (REQUERIMIENTO DETALLES)
	elseif ($accion == "pasarCompras") {
		$linea = split(";", $detalles);
		foreach ($linea as $secuencia) {
			$sql = "UPDATE lg_requerimientosdet 
					SET FlagCompraAlmacen = 'X'
					WHERE 
						CodOrganismo = '".$codorganismo."' AND 
						CodRequerimiento = '".$codrequerimiento."' AND 
						Secuencia = '".$secuencia."' AND
						Estado = 'PE' AND
						FlagCompraAlmacen = 'A'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}	
	break;

//	TRANSACCIONES...
case "TRANSACCIONES":
	//	INSERTAR NUEVA TRANSACCION
	if ($accion == "INSERTAR-TRANSACCION") {
		$nrodocumento = getCodigo_3("lg_transaccion", "NroDocumento", "CodOrganismo", "CodDocumento", $organismo, $docgenerar, 6);
		//	inserto la transaccion
		$sql = "INSERT INTO lg_transaccion
					(CodOrganismo,
					 CodDependencia,
					 CodDocumento,
					 NroDocumento,
					 CodTransaccion,
					 FechaDocumento,
					 Periodo,
					 CodAlmacen,
					 CodCentroCosto,
					 CodDocumentoReferencia,
					 NroDocumentoReferencia,
					 IngresadoPor,
					 RecibidoPor,
					 Comentarios,
					 MotRechazo,
					 FlagManual,
					 FlagPendiente,
					 Estado,
					 DocumentoReferencia,
					 UltimoUsuario,
					 UltimaFecha)
				VALUES
					('".$organismo."',
					 '".$dependencia."',
					 '".$docgenerar."',
					 '".$nrodocumento."',
					 '".$transaccion."',
					 '".formatFechaAMD($ftransaccion)."',
					 '".$periodo."',
					 '".$almacen."',
					 '".$ccosto."',
					 '".$docreferencia."',
					 '".$nrodocreferencia."',
					 '".$_SESSION['CODPERSONA_ACTUAL']."',
					 '".$recibidopor."',
					 '".($comentarios)."',
					 '".($razon)."',
					 '".$flagmanual."',
					 '".$flagpendiente."',
					 'PR',
					 '".$docref."',
					 '".$_SESSION['USUARIO_ACTUAL']."',
					 NOW())";
		$query_mast = mysql_query($sql) or die ($sql.mysql_error());
		
		$secuencia = 0;		
		$linea = split(";", $detalles);
		//	recorro los detalles de la orden para insertar, actualizar
		foreach ($linea as $registro) {
			list($coditem, $codunidad, $stock, $cantidad, $preciounit, $docreferenciaorden)=SPLIT( '[|]', $registro);
			list($refcoddoc, $refnrodoc, $refsec)=SPLIT( '[-]', $docreferenciaorden);
			$total = $preciounit * $cantidad;
			
			if ($movimiento == "E") $cantidad_kardex = -$cantidad;
			else $cantidad_kardex = $cantidad;
			
			$secuencia++;
			//	inserto los detalles de la transaccion
			$sql = "INSERT INTO lg_transacciondetalle
						(CodOrganismo,
						 CodDocumento,
						 NroDocumento,
						 Secuencia,
						 CodItem,
						 CodUnidad,
						 CantidadPedida,
						 CantidadRecibida,
						 PrecioUnit,
						 Total,
						 UltimoUsuario,
						 UltimaFecha)
					VALUES 
						('".$organismo."',
						 '".$docgenerar."',
						 '".$nrodocumento."',
						 '".$secuencia."',
						 '".$coditem."',
						 '".$codunidad."',
						 '".$cantidad."',
						 '".$cantidad."',
						 '".$preciounit."',
						 '".$total."',
					 	 '".$_SESSION['USUARIO_ACTUAL']."',
					 	 NOW())";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			
			//	inserto el detalle de la transaccion en el kardex
			$sql = "INSERT INTO lg_kardex
						(CodItem,
						 CodAlmacen,
						 CodDocumento,
						 NroDocumento,
						 Fecha,
						 CodTransaccion,
						 Cantidad,
						 PrecioUnitario,
						 MontoTotal,
						 PeriodoContable,
						 UltimoUsuario,
						 UltimaFecha)
					VALUES
						('".$coditem."',
						 '".$almacen."',
						 '".$docgenerar."',
						 '".$nrodocumento."',
						 '".date("Y-m-d")."',
						 '".$transaccion."',
						 '".$cantidad_kardex."',
						 '".$preciounit."',
						 '".$total."',
						 '".date("Y-m")."',
						 '".$_SESSION['USUARIO_ACTUAL']."',
						 NOW())";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());			
			
			//	consulto si existe el item en el almacen ya ingresado
			$sql = "SELECT * FROM lg_itemalmacen WHERE CodItem = '".$coditem."' AND CodAlmacen = '".$almacen."'";
			$query_item = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_item) == 0) {
				//	si no existe inserto el item en el almacen
				$sql = "INSERT INTO lg_itemalmacen
							(CodItem,
							 CodAlmacen,
							 Estado,
							 UltimoUsuario,
							 UltimaFecha)
						VALUES
							('".$coditem."',
							 '".$almacen."',
							 'A',
							 '".$_SESSION['USUARIO_ACTUAL']."',
							 NOW())";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
				
				//	inserto en el inventario
				$sql = "INSERT INTO lg_itemalmaceninv
							(CodItem,
							 CodAlmacen,
							 Proveedor,
							 FechaIngreso,
							 StockIngreso,
							 StockActual,
							 PrecioUnitario,
							 IngresadoPor,
							 UltimoUsuario,
							 UltimaFecha)
						VALUES
							('".$coditem."',
							 '".$almacen."',
							 '".$field_orden['CodProveedor']."',
							 '".date("Y-m-d")."',
							 '".$cantidad."',
							 '".$cantidad."',
							 '".$preciounit."',
							 '".$_SESSION['CODPERSONA_ACTUAL']."',
							 '".$_SESSION['USUARIO_ACTUAL']."',
							 NOW())";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			} else {
				//	actualizo el inventario
				$sql = "UPDATE lg_itemalmaceninv
						SET 
							StockActual = (StockActual + $cantidad_kardex),
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()
						WHERE
							CodAlmacen = '".$almacen."' AND
							CodItem = '".$coditem."'";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
	}
	
	//	CARGAR LOS VALORES DEL TIPO DE TRANSACCION
	elseif ($accion == "setTransaccionDocumento") {
		$sql = "SELECT 
					tt.TipoMovimiento, 
					tt.TipoDocGenerado,
					td.Descripcion AS NomTipoDocumento
				FROM 
					lg_tipotransaccion tt
					INNER JOIN lg_tipodocumento td ON (tt.TipoDocGenerado = td.CodDocumento)
				WHERE
					tt.CodTransaccion = '".$tipotransaccion."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) {
			$field = mysql_fetch_array($query);
			echo "|".$field['TipoMovimiento']."|".$field['TipoDocGenerado']."|".$field['NomTipoDocumento'];
		} else die("¡Error: No se encontró el tipo de transacción!");
	}
	
	//	REVERSA
	elseif ($accion == "REVERSA") {
		//	consulto los datos de la transaccion
		$sql = "SELECT *
				FROM lg_transaccion
				WHERE
					CodOrganismo = '".$organismo."' AND
					CodDocumento = '".$coddocumento_transaccion."' AND
					NroDocumento = '".$nrodocumento_transaccion."'";
		$query_transaccion = mysql_query($sql) or die($sql.mysql_error());
		if (mysql_num_rows($query_transaccion) != 0) $field_transaccion = mysql_fetch_array($query_transaccion);
		
		//	inserto transaccion
		$nrodocumento = getCodigo_3("lg_transaccion", "NroDocumento", "CodOrganismo", "CodDocumento", $organismo, $coddocumento, 6);
		$sql = "INSERT INTO lg_transaccion (
							CodOrganismo,
							CodDependencia,
							CodDocumento,
							NroDocumento,
							CodTransaccion,
							FechaDocumento,
							Periodo,
							CodAlmacen,
							CodCentroCosto,
							CodDocumentoReferencia,
							NroDocumentoReferencia,
							IngresadoPor,
							RecibidoPor,
							Comentarios,
							MotRechazo,
							FlagManual,
							FlagPendiente,
							DocumentoReferencia,
							ReferenciaOrganismo,
							ReferenciaNroDocumento,
							Estado,
							UltimoUsuario,
							UltimaFecha
				) VALUES (
							'".$field_transaccion['CodOrganismo']."',
							'".$field_transaccion['CodDependencia']."',
							'".$coddocumento."',
							'".$nrodocumento."',
							'".$transaccion."',
							'".formatFechaAMD($ftransaccion)."',
							'".$field_transaccion['Periodo']."',
							'".$field_transaccion['CodAlmacen']."',
							'".$field_transaccion['CodCentroCosto']."',
							'".$field_transaccion['CodDocumentoReferencia']."',
							'".$field_transaccion['NroDocumentoReferencia']."',
							'".$_SESSION['CODPERSONA_ACTUAL']."',
							'".$_SESSION['CODPERSONA_ACTUAL']."',
							'".$field_transaccion['Comentarios']."',
							'".($razon)."',
							'".$field_transaccion['FlagManual']."',
							'".$field_transaccion['FlagPendiente']."',
							'".$field_transaccion['DocumentoReferencia']."',
							'".$field_transaccion['ReferenciaOrganismo']."',
							'".$field_transaccion['ReferenciaNroDocumento']."',
							'".$field_transaccion['Estado']."',
							'".$_SESSION['USUARIO_ACTUAL']."',
							NOW()
				)";
		$query_insert = mysql_query($sql) or die($sql.mysql_error());
		
		//	consulto transaccion detalle
		$sql = "SELECT *
				FROM lg_transacciondetalle
				WHERE
					CodOrganismo = '".$organismo."' AND
					CodDocumento = '".$coddocumento_transaccion."' AND
					NroDocumento = '".$nrodocumento_transaccion."'
				ORDER BY Secuencia";
		$query_detalle = mysql_query($sql) or die($sql.mysql_error());
		while ($field_detalle = mysql_fetch_array($query_detalle)) {
			$secuencia++;
			
			//	inserto transaccion detalle
			$sql = "INSERT INTO lg_transacciondetalle (
								CodOrganismo,
								CodDocumento,
								NroDocumento,
								Secuencia,
								CodItem,
								CodUnidad,
								CantidadPedida,
								CantidadRecibida,
								PrecioUnit,
								Total,
								ReferenciaOrganismo,
								ReferenciaCodDocumento,
								ReferenciaNroDocumento,
								ReferenciaSecuencia,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$field_detalle['CodOrganismo']."',
								'".$coddocumento."',
								'".$nrodocumento."',
								'".$secuencia."',
								'".$field_detalle['CodItem']."',
								'".$field_detalle['CodUnidad']."',
								'".$field_detalle['CantidadPedida']."',
								'".$field_detalle['CantidadRecibida']."',
								'".$field_detalle['PrecioUnit']."',
								'".$field_detalle['Total']."',
								'".$field_detalle['ReferenciaOrganismo']."',
								'".$field_detalle['ReferenciaCodDocumento']."',
								'".$field_detalle['ReferenciaNroDocumento']."',
								'".$field_detalle['ReferenciaSecuencia']."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die($sql.mysql_error());
			
			//	inserto kardex
			if ($movimiento == "I") $cantidad_kardex = -$field_detalle['CantidadRecibida'];
			elseif ($movimiento == "E") $cantidad_kardex = $field_detalle['CantidadRecibida'];
			$sql = "INSERT INTO lg_kardex (
								CodItem,
								CodAlmacen,
								CodDocumento,
								NroDocumento,
								Fecha,
								CodTransaccion,
								Cantidad,
								PrecioUnitario,
								MontoTotal,
								PeriodoContable,
								ReferenciaCodOrganismo,
								ReferenciaCodDocumento,
								ReferenciaNroDocumento,
								ReferenciaSecuencia,
								UltimoUsuario,
								UltimaFecha
					) VALUES (
								'".$field_detalle['CodItem']."',
								'".$field_transaccion['CodAlmacen']."',
								'".$coddocumento."',
								'".$nrodocumento."',
								'".date("Y-m-d")."',
								'".$transaccion."',
								'".$cantidad_kardex."',
								'".$field_detalle['PrecioUnit']."',
								'".$field_detalle['Total']."',
								'".$field_transaccion['Periodo']."',
								'".$field_detalle['ReferenciaOrganismo']."',
								'".$field_detalle['ReferenciaCodDocumento']."',
								'".$field_detalle['ReferenciaNroDocumento']."',
								'".$field_detalle['ReferenciaSecuencia']."',
								'".$_SESSION['USUARIO_ACTUAL']."',
								NOW()
					)";
			$query_insert = mysql_query($sql) or die($sql.mysql_error());
			
			//	actualizo inventario
			$sql = "UPDATE lg_itemalmaceninv
					SET
						StockActual = (StockActual + $cantidad_kardex),
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()
					WHERE
						CodAlmacen = '".$field_transaccion['CodAlmacen']."' AND
						CodItem = '".$field_detalle['CodItem']."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
			
			//	actualizo orden de compra / requererimiento detalle
			if ($transaccion == "ARO")
				$sql = "UPDATE lg_ordencompradetalle
						SET
							Estado = 'PE',
							CantidadRecibida = (CantidadRecibida - $field_detalle[CantidadRecibida])
						WHERE
							CodOrganismo = '".$organismo."' AND
							NroOrden = '".$field_transaccion['ReferenciaNroDocumento']."' AND
							CodItem = '".$field_detalle['CodItem']."'";
			elseif ($transaccion == "ARE")
				$sql = "UPDATE lg_requerimientosdet
						SET
							Estado = 'PE',
							CantidadRecibida = (CantidadRecibida - $field_detalle[CantidadRecibida])
						WHERE
							CodOrganismo = '".$organismo."' AND
							CodRequerimiento = '".$field_transaccion['ReferenciaNroDocumento']."' AND
							CodItem = '".$field_detalle['CodItem']."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
		}
		
		if ($transaccion == "ARO") {
			//	actualizo orden de compra
			$sql = "UPDATE lg_ordencompra
					SET Estado = 'AP'
					WHERE
						CodOrganismo = '".$organismo."' AND
						NroOrden = '".$field_transaccion['ReferenciaNroDocumento']."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
			
			//	consulto el documento
			$sql = "SELECT *
					FROM ap_documentos
					WHERE
						CodOrganismo = '".$field_transaccion['CodOrganismo']."' AND
						TransaccionTipoDocumento = '".$field_transaccion['CodDocumento']."' AND
						TransaccionNroDocumento = '".$field_transaccion['NroDocumento']."'";
			$query_doc = mysql_query($sql) or die ($sql.mysql_error());
			while ($field_doc = mysql_fetch_array($query_doc)) {
				//	elimino el documento generado
				$sql = "DELETE FROM ap_documentosdetalle
						WHERE
							CodProveedor = '".$field_doc['CodProveedor']."' AND
							DocumentoClasificacion = '".$field_doc['DocumentoClasificacion']."' AND
							DocumentoReferencia = '".$field_doc['DocumentoReferencia']."'";
				$query_delete = mysql_query($sql) or die ($sql.mysql_error());
				
				//	elimino el documento generado
				$sql = "DELETE FROM ap_documentos
						WHERE
							CodProveedor = '".$field_doc['CodProveedor']."' AND
							DocumentoClasificacion = '".$field_doc['DocumentoClasificacion']."' AND
							DocumentoReferencia = '".$field_doc['DocumentoReferencia']."'";
				$query_delete = mysql_query($sql) or die ($sql.mysql_error());
			}
		} 
		elseif ($transaccion == "ARE") {
			//	actualizo requerimiento
			$sql = "UPDATE lg_requerimientos
					SET Estado = 'AP'
					WHERE
						CodOrganismo = '".$organismo."' AND
						CodRequerimiento = '".$field_transaccion['ReferenciaNroDocumento']."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
		}
		
		//	actualizo la transaccion anulada
		$sql = "UPDATE lg_transaccion
				SET Estado = 'AN'
				WHERE
					CodOrganismo = '".$organismo."' AND
					CodDocumento = '".$coddocumento_transaccion."' AND
					NroDocumento = '".$nrodocumento_transaccion."'";
		$query_update = mysql_query($sql) or die ($sql.mysql_error());
		
		echo "|$nrodocumento";
	}
	break;

//	ITEM X ALMACEN...
case "ITEM-ALMACEN":
	//	INSERTAR
	if ($accion == "GUARDAR") {
		$sql = "SELECT * FROM lg_itemalmacen WHERE CodItem = '".$coditem."'";
		$query_consulta = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_consulta) != 0) die("¡Item ya ingresado!");
		else {
			$sql = "INSERT INTO lg_itemalmacen
						(CodItem,
						 CodAlmacen,
						 StockMax,
						 StockMin,
						 StockReorden,
						 TiempoEspera,
						 Ubicacion1,
						 Ubicacion2,
						 Ubicacion3,
						 Estado,
						 UltimoUsuario,
						 UltimaFecha)
					VALUES 
						('".$coditem."',
						 '".$codalmacen."',
						 '".$stockmax."',
						 '".$stockmin."',
						 '".$reorden."',
						 '".$espera."',
						 '".$ubicacion1."',
						 '".$ubicacion2."',
						 '".$ubicacion3."',
						 '".$estado."',
						 '".$_SESSION['USUARIO_ACTUAL']."',
						 NOW())";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	//	ACTUALIZAR
	elseif ($accion == "ACTUALIZAR") {
		$sql = "UPDATE lg_itemalmacen 
				SET
					StockMin = '".$stockmin."',
					StockMax = '".$stockmax."',
					StockReorden = '".$reorden."',
					TiempoEspera = '".$espera."',
					Ubicacion1 = '".$ubicacion1."',
					Ubicacion2 = '".$ubicacion2."',
					Ubicacion3 = '".$ubicacion3."',
					Estado = '".$estado."',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					CodAlmacen = '".$codalmacen."' AND
					CodItem = '".$coditem."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	//	ELIMINAR
	elseif ($accion == "ELIMINAR") {
		list($codalmacen, $coditem)=SPLIT( '[-]', $codigo);
		$sql = "DELETE FROM lg_itemalmacen WHERE CodAlmacen = '".$codalmacen."' AND CodItem = '".$coditem."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	break;

//	CONTROL DE PERIODOS...
case "CONTROL-PERIODOS":
	//	INSERTAR
	if ($accion == "INSERTAR") {
		$sql = "SELECT * FROM lg_periodocontrol WHERE CodOrganismo = '".$organismo."' AND Periodo = '".$periodo."'";
		$query_consulta = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_consulta) != 0) die("¡Periodo ya ingresado!");
		else {
			$sql = "INSERT INTO lg_periodocontrol
						(CodOrganismo,
						 Periodo,
						 FlagTransaccion,
						 Estado,
						 UltimoUsuario,
						 UltimaFecha)
					VALUES 
						('".$organismo."',
						 '".$periodo."',
						 '".$flagtransaccion."',
						 '".$estado."',
						 '".$_SESSION['USUARIO_ACTUAL']."',
						 NOW())";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	//	ACTUALIZAR
	elseif ($accion == "ACTUALIZAR") {
		$sql = "UPDATE lg_periodocontrol 
				SET
					FlagTransaccion = '".$flagtransaccion."',
					Estado = '".$estado."',
					UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
					UltimaFecha = NOW()
				WHERE
					CodOrganismo = '".$organismo."' AND 
					Periodo = '".$periodo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	//	ELIMINAR
	elseif ($accion == "ELIMINAR") {
		list($organismo, $periodo)=SPLIT( '[.]', $codigo);
		$sql = "DELETE FROM lg_periodocontrol WHERE CodOrganismo = '".$organismo."' AND Periodo = '".$periodo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	break;

//	REQUERIMIENTOS PENDIENTES...
case "REQUERIMIENTOS-PENDIENTES":
	//	CERRAR
	if ($accion == "CERRAR") {
		$linea = split(";", $detalles);
		foreach ($linea as $registro) {
			list($codorganismo, $codrequerimiento, $secuencia)=SPLIT( '[|]', $registro);
			
			$sql = "UPDATE lg_requerimientosdet 
					SET Estado = 'CE' 
					WHERE 
						CodOrganismo = '".$codorganismo."' AND 
						CodRequerimiento = '".$codrequerimiento."' AND 
						Secuencia = '".$secuencia."'";
$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	break;

//	TIPOS DE TRANSACCIONES...
case "TIPOS-TRANSACCIONES-COMMODITIES":
	//	INSERTAR NUEVO REGISTRO...
	if ($accion == "GUARDAR") {
		$sql = "SELECT * FROM lg_operacioncommodity WHERE CodOperacion = '".$codigo."' OR Descripcion = '".($descripcion)."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error = "¡REGISTRO YA INGRESADO!";
		else {
			$sql = "INSERT INTO lg_operacioncommodity (CodOperacion,
													Descripcion,
													TipoMovimiento,
													TipoDocGenerado,
													TipoDocTransaccion,
													Estado,
													UltimoUsuario,
													UltimaFecha)
											VALUES ('".$codigo."',
													'".($descripcion)."',
													'".$tipo."',
													'".$docgenerado."',
													'".$doctransaccion."',
													'".$estado."',
													'".$_SESSION['USUARIO_ACTUAL']."',
													NOW())";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	//	MODIFICAR REGISTRO...
	elseif ($accion == "ACTUALIZAR") {
		$sql = "SELECT * FROM lg_operacioncommodity WHERE Descripcion = '".($descripcion)."' AND CodOperacion <> '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query) != 0) $error = "¡REGISTRO YA INGRESADO!";
		else {
			$sql = "UPDATE lg_operacioncommodity SET Descripcion = '".($descripcion)."',
													 TipoMovimiento = '".$tipo."',
													 TipoDocGenerado = '".$docgenerado."',
													 TipoDocTransaccion = '".$doctransaccion."',
													 Estado = '".$estado."',
													 UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
													 UltimaFecha = NOW()
												WHERE
													 CodOperacion = '".$codigo."'";
			$query = mysql_query($sql) or die ($sql.mysql_error());
		}
	}
	//	ELIMINAR REGISTRO...
	elseif ($accion == "ELIMINAR") {
		$sql = "DELETE FROM lg_operacioncommodity WHERE CodOperacion = '".$codigo."'";
		$query = mysql_query($sql) or die ($sql.mysql_error());
	}
	echo $error;
	break;

//	RECEPCION DE COMMODITIES...
case "COMMODITY-RECEPCION":
	//	INSERTAR NUEVA TRANSACCION
	if ($accion == "INSERTAR-TRANSACCION") {
		//	recorro los detalles de la orden en busca de errores
		$linea = split(";", $detalles);
		foreach ($linea as $registro) {
			list($commodity, $commodity_secuencia, $codunidad, $stock, $pedida, $recibida, $pendiente, $cantidad, $preciounit, $docreferenciaorden)=SPLIT( '[|]', $registro);
			list($refcoddoc, $refnrodoc, $refsec)=SPLIT( '[-]', $docreferenciaorden);			
			if ($cantidad > $pendiente) die ("¡Cantidad Recibida no puede ser mayor a la Cantidad Pedida Commodity # $commodity!");
			elseif ($cantidad <= 0) die ("¡La Cantidad a Recepcionar no puede ser menor o igual a cero Commodity # $commodity!");
		}
		
		//	recorro los activos en busca de errores
		$linea = split(";", $detalles_activo);	$k=0;
		foreach ($linea as $registro) {
			list($checked, $idactivo, $reforden, $descripcion, $clasificacion_activo, $preciounit, $nroserie, $fingreso, $modelo, $codbarra, $codubicacion, $ccosto, $nroplaca, $marca, $color, $flagfactura) = SPLIT( '[|]', $registro);
			list($nrosecuencia, $commodity) = SPLIT( '[_]', $idactivo);
			list($oc, $nroorden, $ordensecuencia) = SPLIT( '[-]', $reforden);
			if ($flagactivo == "S" && $checked == "true") {
				$sql = "SELECT NroSerie
						FROM lg_activofijo
						WHERE NroSerie = '".$nroserie."'";
				$query_select = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_select) != 0) die("¡ERROR: El Nro. de Serie ".$nroserie." ya existe!");
				
				$array_nroserie[$k] = $nroserie;
				
				for ($j=1; $j<=$k; $j++) {
					if ($nroserie == $array_nroserie[$j-1]) die("¡ERROR: El Nro. de Serie ".$nroserie." esta repetido!");
				}
				$k++;
			}
		}
		
		//	consulto el porcentaje igv
		$codimpuesto = getParametro("IGVCODIGO");
		$sql = "SELECT FactorPorcentaje FROM mastimpuestos WHERE CodImpuesto = '".$codimpuesto."'";
		$query_impuesto = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_impuesto) != 0) $field_impuesto = mysql_fetch_array($query_impuesto);
		
		//	consulto los datos de la orden de compra
		$sql = "SELECT * FROM lg_ordencompra WHERE CodOrganismo = '".$organismo."' AND NroOrden = '".$docinterno."'";
		$query_orden = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_orden) != 0) $field_orden = mysql_fetch_array($query_orden);
		
		//	consulto si existe el numero de documento
		$sql = "SELECT * 
				FROM ap_documentos 
				WHERE
					Anio = '".$anio."' AND
					CodProveedor = '".$field_orden['CodProveedor']."' AND 
					DocumentoClasificacion = '".$transaccion."' AND 
					DocumentoReferencia = '".$docrefremision."'";
		$query_consulta = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_consulta) != 0) die("¡Nro. de Referencia ya Ingresado!");
		
		$nrodocumento = getCodigo_3("lg_commoditytransaccion", "NroDocumento", "CodOrganismo", "CodDocumento", $organismo, $docgenerar, 6);
		
		//	inserto la transaccion
		$sql = "INSERT INTO lg_commoditytransaccion
					(CodOrganismo,
					 CodDependencia,
					 CodDocumento,
					 NroDocumento,
					 CodTransaccion,
					 FechaDocumento,
					 Periodo,
					 CodAlmacen,
					 CodCentroCosto,
					 CodDocumentoReferencia,
					 NroDocumentoReferencia,
					 IngresadoPor,
					 RecibidoPor,
					 Comentarios,
					 Estado,
					 ReferenciaOrganismo,
					 ReferenciaNroDocumento,
					 CodUbicacion,
					 FlagActivoFijo,
					 DocumentoReferencia,
					 Anio,
					 UltimoUsuario,
					 UltimaFecha)
				VALUES
					('".$organismo."',
					 '".$dependencia."',
					 '".$docgenerar."',
					 '".$nrodocumento."',
					 '".$transaccion."',
					 '".formatFechaAMD($ftransaccion)."',
					 '".$periodo."',
					 '".$almacen."',
					 '".$ccosto."',
					 '".$docreferencia."',
					 '".$nrodocreferencia."',
					 '".$ingresadopor."',
					 '".$recibidopor."',
					 '".($comentarios)."',
					 'CO',
					 '".$organismo."',
					 '".trim($refnrodoc)."',
					 '".$codubicacion."',
					 '".$flagactivo."',
					 '".$docrefremision."',
					 '".$anio."',
					 '".$_SESSION['USUARIO_ACTUAL']."',
					 NOW())";
		$query_mast = mysql_query($sql) or die ($sql.mysql_error());
		
		//	inserto el documento ctas x pagar
		$sql = "INSERT INTO ap_documentos
					(CodOrganismo,
					 CodProveedor,
					 DocumentoClasificacion,
					 DocumentoReferencia,
					 Fecha,
					 ReferenciaTipoDocumento,
					 ReferenciaNroDocumento,
					 Estado,
					 TransaccionTipoDocumento,
					 TransaccionNroDocumento,
					 Comentarios,
					 Anio,
					 UltimoUsuario,
					 UltimaFecha)
				VALUES
					('".$organismo."',
					 '".$field_orden['CodProveedor']."',
					 '".$transaccion."',
					 '".$docrefremision."',
					 '".formatFechaAMD($ftransaccion)."',
					 'OC',
					 '".trim($refnrodoc)."',
					 'PR',
					 '".$docgenerar."',
					 '".$nrodocumento."',
					 '".($comentarios)."',
					 '".$anio."',
					 '".$_SESSION['USUARIO_ACTUAL']."',
					 NOW())";
		$query_mast = mysql_query($sql) or die ($sql.mysql_error());
		
		$secuencia = 0;		
		$total_precio_cantidad = 0;
		$total_afecto = 0;
		$total_noafecto = 0;
		$total_impuesto = 0;
		$monto_total = 0;
		//	recorro los detalles de la orden para insertar, actualizar
		$linea = split(";", $detalles);
		foreach ($linea as $registro) {
			list($commodity, $secuencia_commodity, $codunidad, $stock, $pedida, $recibida, $pendiente, $cantidad, $preciounit, $docreferenciaorden)=SPLIT( '[|]', $registro);
			list($refcoddoc, $refnrodoc, $refsec)=SPLIT( '[-]', $docreferenciaorden);
			
			$total_transaccion = $preciounit * $cantidad;
			
			//	consulto la descripcion
			$sql = "SELECT Descripcion
					FROM lg_ordencompradetalle
					WHERE
						Anio = '".$anio."' AND
						CodOrganismo = '".$organismo."' AND 
						NroOrden = '".$nroorden."' AND 
						CommoditySub = '".$commodity."' AND 
						Secuencia = '".$secuencia_commodity."'";
			$query_descripcion = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_descripcion) != 0) $field_descripcion = mysql_fetch_array($query_descripcion);
			
			$secuencia++;
			//	inserto los detalles de la transaccion
			$sql = "INSERT INTO lg_commoditytransacciondetalle
						(CodOrganismo,
						 CodDocumento,
						 NroDocumento,
						 Secuencia,
						 CommoditySub,
						 Descripcion,
						 Cantidad,
						 PrecioUnit,
						 Total,
						 ReferenciaOrganismo,
						 ReferenciaCodDocumento,
						 ReferenciaNroDocumento,
						 ReferenciaSecuencia,
						 CodAlmacen,
						 CodCentroCosto,
						 Anio,
						 UltimoUsuario,
						 UltimaFecha)
					VALUES 
						('".$organismo."',
						 '".$docgenerar."',
						 '".$nrodocumento."',
						 '".$secuencia."',
						 '".$commodity."',
						 '".$field_descripcion['Descripcion']."',
						 '".$cantidad."',
						 '".$preciounit."',
						 '".$total_transaccion."',
						 '".$organismo."',
						 '".trim($refcoddoc)."',
						 '".trim($refnrodoc)."',
						 '".trim($refsec)."',
						 '".$almacen."',
						 '".$ccosto."',
						 '".$anio."',
					 	 '".$_SESSION['USUARIO_ACTUAL']."',
					 	 NOW())";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			
			$cantidad_recibida = $recibida + $cantidad;
			
			//	si la cantidad pedida es igual a la recibida entonces actualizo la cantidad recibida y estado a completado del detalle de la orden de compra
			if ($pedida == $cantidad_recibida) {
				//	actualizo a el estado y la cantidad recibidad del detalle de la orden de compra
				$sql = "UPDATE lg_ordencompradetalle 
						SET 
							Estado = 'CO' ,
							CantidadRecibida = '$cantidad_recibida'
						WHERE
							Anio = '".$anio."' AND
							CodOrganismo = '".$organismo."' AND 
							NroOrden = '".$nroorden."' AND 
							CommoditySub = '".$commodity."' AND 
							Secuencia = '".$secuencia_commodity."'";
				$query_orden_det = mysql_query($sql) or die ($sql.mysql_error());
				
				//	verifico si tiene detalles pendiente la orden de compra si no tiene la paso a estado completado
				$sql = "SELECT ocd.*
						FROM
							lg_ordencompradetalle ocd
							INNER JOIN lg_ordencompra oc ON (oc.CodOrganismo = ocd.CodOrganismo AND
															 oc.NroOrden = ocd.NroOrden)
						WHERE
							ocd.Anio = '".$anio."' AND
							ocd.CodOrganismo = '".$organismo."' AND
							ocd.NroOrden = '".$nroorden."' AND
							ocd.Estado = 'PE'";
				$query_orden_det = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_orden_det) == 0) {
					//	actualizo el estado a completado de la orden de compra
					$sql = "UPDATE lg_ordencompra
							SET Estado = 'CO'
							WHERE 
								Anio = '".$anio."' AND
								CodOrganismo = '".$organismo."' AND 
								NroOrden = '".$nroorden."'";
					$query = mysql_query($sql) or die ($sql.mysql_error());
				}
			} else {
				//	actualizo la cantidad recibida del detalle de la orden de compra
				$sql = "UPDATE lg_ordencompradetalle 
						SET 
							CantidadRecibida = '$cantidad_recibida'
						WHERE 
							Anio = '".$anio."' AND
							CodOrganismo = '".$organismo."' AND 
							NroOrden = '".$nroorden."' AND 
							CommoditySub = '".$commodity."' AND 
							Secuencia = '".$secuencia_commodity."'";
				$query_orden_det = mysql_query($sql) or die ($sql.mysql_error());
			}
			
			//	consulto si existe el item en el almacen ya ingresado
			$sql = "SELECT * FROM lg_commoditystock WHERE CommoditySub = '".$commodity."' AND CodAlmacen = '".$almacen."'";
			$query_item = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_item) == 0) {
				//	si no existe inserto el commodity en el stock
				$sql = "INSERT INTO lg_commoditystock (
							CodAlmacen,
							CommoditySub,
							Cantidad,
							PrecioUnitario,
							IngresadoPor,
							UltimoUsuario,
							UltimaFecha
						) VALUES (
							'".$almacen."',
							'".$commodity."',
							'".$cantidad."',
							'".$preciounit."',
							'".$_SESSION['CODPERSONA_ACTUAL']."',
							'".$_SESSION['USUARIO_ACTUAL']."',
							NOW()
						)";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			} else {
				//	actualizo el inventario
				$sql = "UPDATE lg_commoditystock
						SET 
							Cantidad = (Cantidad + $cantidad),
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()
						WHERE
							CodAlmacen = '".$almacen."' AND
							CommoditySub = '".$commodity."'";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			}
			
			//	consulto los datos del detalle de la orden de compra
			$sql = "SELECT *
					FROM lg_ordencompradetalle
					WHERE
						Anio = '".$anio."' AND
						CodOrganismo = '".$organismo."' AND
						NroOrden = '".trim($refnrodoc)."' AND
						CommoditySub = '".$commodity."'";
			$query_orden_detalle = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_orden_detalle) != 0) $field_orden_detalle = mysql_fetch_array($query_orden_detalle);
			
			$precio_cantidad = $cantidad * $preciounit;
			if ($field_orden_detalle['FlagExonerado'] == "S") {
				$monto_afecto = 0;
				$monto_noafecto = $precio_cantidad;
				$impuesto = 0;
				$total = $precio_cantidad;
			} else {
				$monto_afecto = $precio_cantidad;
				$monto_noafecto = 0;
				$impuesto = $precio_cantidad * $field_impuesto['FactorPorcentaje'] / 100;
				$total = $precio_cantidad + $impuesto;
			}
			
			$total_precio_cantidad += $precio_cantidad;
			$total_afecto += $monto_afecto;
			$total_noafecto += $monto_noafecto;
			$total_impuesto += $impuesto;
			$monto_total += $total;
			
			//	inserto los detalles del documento ctas x pagar
			$sql = "INSERT INTO ap_documentosdetalle
						(CodProveedor,
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
						 Anio,
						 UltimoUsuario,
						 UltimaFecha)
					VALUES
						('".$field_orden['CodProveedor']."',
						 '".$transaccion."',
						 '".$docrefremision."',
						 '".$secuencia."',
						 '".$field_orden_detalle['Secuencia']."',
						 '".$field_orden_detalle['CommoditySub']."',
						 '".$field_orden_detalle['Descripcion']."',
						 '".$cantidad."',
						 '".$preciounit."',
						 '".$precio_cantidad."',
						 '".$total."',
						 '".$field_orden_detalle['CodCentroCosto']."',
						 '".$anio."',
						 '".$_SESSION['USUARIO_ACTUAL']."',
						 NOW())";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
		}
		
		//	actualizo los montos del documento ctas x pagar
		$sql = "UPDATE ap_documentos
				SET
					MontoAfecto = '".$total_afecto."',
					MontoNoAfecto = '".$total_noafecto."',
					MontoImpuestos = '".$total_impuesto."',
					MontoTotal = '".$monto_total."',
					MontoPendiente = '".$monto_total."'
				WHERE
					Anio = '".$anio."' AND
					CodOrganismo = '".$organismo."' AND
					CodProveedor = '".$field_orden['CodProveedor']."' AND
					DocumentoClasificacion = '".$transaccion."' AND
					DocumentoReferencia = '".$docrefremision."'";
		$query_mast = mysql_query($sql) or die ($sql.mysql_error());
		
		$linea = split(";", $detalles_activo);
		//	recorro los detalles de la orden para insertar, actualizar
		foreach ($linea as $registro) {
			list($checked, $idactivo, $reforden, $descripcion, $clasificacion_activo, $preciounit, $nroserie, $fingreso, $modelo, $codbarra, $codubicacion, $ccosto, $nroplaca, $marca, $color, $flagfactura) = SPLIT( '[|]', $registro);
			list($nrosecuencia, $commodity) = SPLIT( '[_]', $idactivo);
			list($oc, $nroorden, $ordensecuencia) = SPLIT( '[-]', $reforden);
			
			if ($flagactivo == "S" && $checked == "true") {
				$nrosecuenciaactivo = getCodigo_4("lg_activofijo", "NroSecuencia", "CodOrganismo", "NroOrden", "Secuencia", $organismo, $nroorden, $ordensecuencia, 4);
				$nrosecuenciaactivo = (int) $nrosecuenciaactivo;
				if ($flagfactura == "true") $flagfactura = "S";  else $flagfactura = "N";
				
				//	
				$sql = "SELECT
							cs.CommodityMast,
							cm.Clasificacion
						FROM
							lg_commoditysub cs
							INNER JOIN lg_commoditymast cm ON (cs.CommodityMast = cm.CommodityMast)
							";
				$query_cs = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query_cs) != 0) $field_cs = mysql_fetch_array($query_cs);
				
				$sql = "INSERT INTO lg_activofijo (
							CodOrganismo,
							NroOrden,
							Secuencia,
							NroSecuencia,
							CommoditySub,
							Descripcion,
							CodCentroCosto,
							CodClasificacion,
							CodBarra,
							NroSerie,
							Modelo,
							CodProveedor,
							CodDocumento,
							NroDocumento,
							Monto,
							CodUbicacion,
							FechaIngreso,
							FlagFacturado,
							CodMarca,
							Color,
							NroPlaca,
							Estado,
							NumeroGuia,
							NumeroGuiaFecha,
							Clasificacion,
							UltimoUsuario,
							UltimaFecha
						) VALUES (
							'".$organismo."',
							'".$nroorden."',
							'".$ordensecuencia."',
							'".$nrosecuenciaactivo."',
							'".$commodity."',
							'".($descripcion)."',
							'".$ccosto."',
							'".$clasificacion_activo."',
							'".$codbarra."',
							'".$nroserie."',
							'".($modelo)."',
							'".$field_orden['CodProveedor']."',
							'".$docgenerar."',
							'".$nrodocumento."',
							'".$preciounit."',
							'".$codubicacion."',
							'".formatFechaAMD($fingreso)."',
							'".$flagfactura."',
							'".$marca."',
							'".$color."',
							'".$nroplaca."',
							'PE',
							'".$docrefremision."',
							'".$ftransaccion."',
							'".$field_cs['Clasificacion']."',
							'".$_SESSION['USUARIO_ACTUAL']."',
							NOW())";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
		
		//	si la orden reepcionada viene de un requerimeinto entonces actualizo las cantidades y el estado
		/*
		$sql = "SELECT 
					rd.CodRequerimiento,
					rd.Secuencia,
					ocd.CantidadPedida,
					ocd.CantidadRecibida,
					(ocd.CantidadPedida - ocd.CantidadRecibida) AS CantidadPendiente
				FROM 
					lg_requerimientosdet rd
					INNER JOIN lg_requerimientos r ON (rd.CodRequerimiento = r.CodRequerimiento)
					INNER JOIN lg_ordencompradetalle ocd ON (rd.Anio = ocd.Anio AND
															 rd.CodOrganismo = ocd.CodOrganismo AND
															 rd.NroOrden = ocd.NroOrden AND
															 rd.CodItem = ocd.CodItem AND
				                                             rd.CommoditySub = ocd.CommoditySub)
				WHERE
					rd.Anio = '".$anio."' AND
					rd.CodOrganismo = '".$organismo."' AND
					rd.NroOrden = '".$nroorden."' AND
					r.Clasificacion <> 'SER'
				ORDER BY Secuencia";
		$query_requerimientosdet = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_requerimientosdet = mysql_fetch_array($query_requerimientosdet)) {
			//	actualizo cantidad recibida y el estado del detalle del requerimeinto
			$sql = "UPDATE lg_requerimientosdet
					SET
						CantidadRecibida = '".$field_requerimientosdet['CantidadRecibida']."',
						UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
						UltimaFecha = NOW()
					WHERE
						CodRequerimiento = '".$field_requerimientosdet['CodRequerimiento']."' AND
						Secuencia = '".$field_requerimientosdet['Secuencia']."'";
			$query_update = mysql_query($sql) or die ($sql.mysql_error());
		}
		*/
		echo "|$nrodocumento|$docgenerar";
	}
	break;
	
//	TRANSACCIONES ESPECIALES...
case "TRANSACCIONES-ESPECIALES":
	//	INSERTAR NUEVA TRANSACCION
	if ($accion == "INSERTAR-TRANSACCION") {
		$nrodocumento = getCodigo_3("lg_commoditytransaccion", "NroDocumento", "CodOrganismo", "CodDocumento", $organismo, $docgenerar, 6);
		//	inserto la transaccion
		$sql = "INSERT INTO lg_commoditytransaccion
					(CodOrganismo,
					 CodDependencia,
					 CodDocumento,
					 NroDocumento,
					 CodTransaccion,
					 FechaDocumento,
					 Periodo,
					 CodAlmacen,
					 CodCentroCosto,
					 CodDocumentoReferencia,
					 NroDocumentoReferencia,
					 IngresadoPor,
					 RecibidoPor,
					 Comentarios,
					 Estado,
					 ReferenciaOrganismo,
					 ReferenciaNroDocumento,
					 CodUbicacion,
					 FlagActivoFijo,
					 DocumentoReferencia,
					 Anio,
					 UltimoUsuario,
					 UltimaFecha)
				VALUES
					('".$organismo."',
					 '".$dependencia."',
					 '".$docgenerar."',
					 '".$nrodocumento."',
					 '".$transaccion."',
					 '".formatFechaAMD($ftransaccion)."',
					 '".$periodo."',
					 '".$almacen."',
					 '".$ccosto."',
					 '".$docreferencia."',
					 '".$nrodocreferencia."',
					 '".$ingresadopor."',
					 '".$recibidopor."',
					 '".($comentarios)."',
					 'PR',
					 '".$organismo."',
					 '".trim($refnrodoc)."',
					 '".$codubicacion."',
					 '".$flagactivo."',
					 '".$docrefremision."',
					 NOW(),
					 '".$_SESSION['USUARIO_ACTUAL']."',
					 NOW())";
		$query_mast = mysql_query($sql) or die ($sql.mysql_error());
		
		$secuencia = 0;
		//	recorro los detalles de la orden para insertar, actualizar
		$linea = split(";", $detalles);
		foreach ($linea as $registro) {
			list($commodity, $codunidad, $stock, $cantidad, $preciounit, $docref)=SPLIT( '[|]', $registro);
			list($refcoddoc, $refnrodoc, $refsec)=SPLIT( '[-]', $docref);
			
			$total = $preciounit * $cantidad;
			
			$secuencia++;
			//	inserto los detalles de la transaccion
			$sql = "INSERT INTO lg_commoditytransacciondetalle
						(CodOrganismo,
						 CodDocumento,
						 NroDocumento,
						 Secuencia,
						 CommoditySub,
						 Cantidad,
						 PrecioUnit,
						 Total,
						 ReferenciaOrganismo,
						 ReferenciaCodDocumento,
						 ReferenciaNroDocumento,
						 ReferenciaSecuencia,
						 CodAlmacen,
						 CodCentroCosto,
						 Anio,
						 UltimoUsuario,
						 UltimaFecha)
					VALUES 
						('".$organismo."',
						 '".$docgenerar."',
						 '".$nrodocumento."',
						 '".$secuencia."',
						 '".$commodity."',
						 '".$cantidad."',
						 '".$preciounit."',
						 '".$total."',
						 '".$organismo."',
						 '".trim($refcoddoc)."',
						 '".trim($refnrodoc)."',
						 '".trim($refsec)."',
						 '".$almacen."',
						 '".$ccosto."',
						 NOW(),
					 	 '".$_SESSION['USUARIO_ACTUAL']."',
					 	 NOW())";
			$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			
			//	consulto si existe el item en el almacen ya ingresado
			$sql = "SELECT * FROM lg_commoditystock WHERE CommoditySub = '".$commodity."' AND CodAlmacen = '".$almacen."'";
			$query_item = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_item) == 0) {
				//	si no existe inserto el commodity en el stock
				$sql = "INSERT INTO lg_commoditystock (
							CodAlmacen,
							CommoditySub,
							Cantidad,
							PrecioUnitario,
							IngresadoPor,
							UltimoUsuario,
							UltimaFecha
						) VALUES (
							'".$almacen."',
							'".$commodity."',
							'".$cantidad."',
							'".$preciounit."',
							'".$_SESSION['CODPERSONA_ACTUAL']."',
							'".$_SESSION['USUARIO_ACTUAL']."',
							NOW()
						)";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			} else {
				if ($movimiento == "E") $cantidad = $cantidad * -1;
				//	actualizo el inventario
				$sql = "UPDATE lg_commoditystock
						SET 
							Cantidad = (Cantidad + $cantidad),
							UltimoUsuario = '".$_SESSION['USUARIO_ACTUAL']."',
							UltimaFecha = NOW()
						WHERE
							CodAlmacen = '".$almacen."' AND
							CommoditySub = '".$commodity."'";
				$query_insert = mysql_query($sql) or die ($sql.mysql_error());
			}
		}
		echo "|$nrodocumento|$docgenerar";
	}
	break;
}
?>