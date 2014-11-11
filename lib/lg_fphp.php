<?php
session_start();
set_time_limit(-1);
ini_set('memory_limit','128M');

//	FUNCION PARA CARGAR SELECTS 
function loadSelectValores($tabla, $codigo, $opt) {
	switch ($tabla) {
		case "ESTADO":
			$c[0] = "A"; $v[0] = "Activo";
			$c[1] = "I"; $v[1] = "Inactivo";
			break;
		
		case "ESTADO-REQUERIMIENTO":
			$c[0] = "PR"; $v[0] = "En Preparación";
			$c[1] = "RV"; $v[1] = "Revisado";
			$c[2] = "CN"; $v[2] = "Conformado";
			$c[3] = "AP"; $v[3] = "Aprobado";
			$c[4] = "AN"; $v[4] = "Anulado";
			$c[5] = "RE"; $v[5] = "Rechazado";
			$c[6] = "CE"; $v[6] = "Cerrado";
			$c[7] = "CO"; $v[7] = "Completado";
			break;
		
		case "ESTADO-REQUERIMIENTO-DETALLE":
			$c[0] = "PR"; $v[0] = "En Preparación";
			$c[1] = "PE"; $v[1] = "Pendiente";
			$c[2] = "AN"; $v[2] = "Anulado";
			$c[3] = "RE"; $v[3] = "Rechazado";
			$c[4] = "CE"; $v[4] = "Cerrado";
			$c[5] = "CO"; $v[5] = "Completado";
			break;
		
		case "DIRIGIDO":
			$c[0] = "C"; $v[0] = "Compras";
			$c[1] = "A"; $v[1] = "Almacen";
			break;
		
		case "PRIORIDAD":
			$c[0] = "N"; $v[0] = "Normal";
			$c[1] = "U"; $v[1] = "Urgente";
			$c[2] = "M"; $v[2] = "Muy Urgente";
			break;
			
		case "BUSCAR-REQUERIMIENTOS":
			$c[0] = "lr.CodRequerimiento"; $v[0] = "# Requerimiento";
			$c[1] = "lr.Descripcion"; $v[1] = "Descripción";
			$c[2] = "lr.CodCentroCosto"; $v[2] = "Centro de Costo";
			$c[3] = "lc.Descripcion"; $v[3] = "Clasificación";
			break;
			
		case "BUSCAR-REQUERIMIENTOS-DETALLE":
			$c[0] = "rd.CodRequerimiento"; $v[0] = "# Requerimiento";
			$c[1] = "rd.CodItem, rd.CommoditySub"; $v[1] = "Código";
			$c[2] = "rd.Descripcion"; $v[2] = "Descripción";
			$c[3] = "rd.CodCentroCosto"; $v[3] = "Centro de Costo";
			break;
		
		case "ORDENAR-REQUERIMIENTO-DETALLE":
			$c[0] = "rd.CodRequerimiento, rd.Secuencia"; $v[0] = "# Requerimiento";
			$c[1] = "rd.CodItem, rd.CommoditySub"; $v[1] = "Item/Commodity";
			break;
			
		case "BUSCAR-INVITACION-COTIZACION":
			$c[0] = "lrd.CodRequerimiento"; $v[0] = "# Requerimiento";
			$c[1] = "lrd.CodItem|lrd.CommoditySub"; $v[1] = "Código";
			$c[2] = "lrd.Descripcion"; $v[2] = "Descripción";
			$c[3] = "lrd.CodCentroCosto"; $v[3] = "Centro de Costo";
			$c[4] = "c.CotizacionNumero"; $v[4] = "# Invitacion";
			$c[5] = "c.CodProveedor"; $v[5] = "Proveedor";
			$c[6] = "c.NomProveedor"; $v[6] = "Razón Social";
			break;
		
		case "COMPRA-CLASIFICACION":
			$c[0] = "L"; $v[0] = "O/C Local";
			$c[1] = "F"; $v[1] = "O/C Foráneo";
			break;
		
		case "ORDENAR-COMPRA":
			$c[0] = "oc.NroOrden"; $v[0] = "Nro. Orden";
			$c[1] = "oc.FechaPreparacion"; $v[1] = "F. Preparación";
			$c[2] = "oc.NomProveedor"; $v[2] = "Proveedor";
			$c[3] = "oc.Estado"; $v[3] = "Estado";
			break;
		
		case "ORDENAR-COMPRA-DETALLE":
			$c[0] = "ocd.NroOrden"; $v[0] = "Nro. Orden";
			$c[1] = "ocd.CodItem, ocd.CommoditySub"; $v[1] = "Código";
			$c[2] = "ocd.Descripcion"; $v[2] = "Descripción";
			break;
		
		case "ESTADO-COMPRA":
			$c[0] = "PR"; $v[0] = "En Preparación";
			$c[1] = "RV"; $v[1] = "Revisado";
			$c[2] = "AP"; $v[2] = "Aprobado";
			$c[3] = "AN"; $v[3] = "Anulado";
			$c[4] = "RE"; $v[4] = "Rechazado";
			$c[5] = "CE"; $v[5] = "Cerrado";
			$c[6] = "CO"; $v[6] = "Completado";
			break;
		
		case "ESTADO-COMPRA-DETALLE":
			$c[0] = "PR"; $v[0] = "En Preparación";
			$c[1] = "PE"; $v[1] = "Pendiente";
			$c[2] = "AN"; $v[2] = "Anulado";
			$c[3] = "CE"; $v[3] = "Cerrado";
			$c[4] = "CO"; $v[4] = "Completado";
			break;
		
		case "ORDENAR-SERVICIO":
			$c[0] = "os.NroOrden"; $v[0] = "Nro. Orden";
			$c[1] = "os.FechaPreparacion"; $v[1] = "F. Preparación";
			$c[2] = "os.NomProveedor"; $v[2] = "Proveedor";
			$c[3] = "os.Estado"; $v[3] = "Estado";
			break;
		
		case "ESTADO-SERVICIO":
			$c[0] = "PR"; $v[0] = "En Preparación";
			$c[1] = "RV"; $v[1] = "Revisado";
			$c[2] = "AP"; $v[2] = "Aprobado";
			$c[3] = "AN"; $v[3] = "Anulado";
			$c[4] = "RE"; $v[4] = "Rechazado";
			$c[5] = "CE"; $v[5] = "Cerrado";
			$c[6] = "CO"; $v[6] = "Completado";
			break;
		
		case "ORDENAR-SERVICIO-DETALLE":
			$c[0] = "osd.NroOrden"; $v[0] = "Nro. Orden";
			$c[1] = "osd.CommoditySub"; $v[1] = "Código";
			$c[2] = "osd.Descripcion"; $v[2] = "Descripción";
			break;
	}
	
	$i = 0;
	switch ($opt) {
		case 0:
			foreach ($c as $cod) {
				if ($cod == $codigo) echo "<option value='".$cod."' selected>".$v[$i]."</option>";
				else echo "<option value='".$cod."'>".$v[$i]."</option>";
				$i++;
			}
			break;
			
		case 1:
			foreach ($c as $cod) {
				if ($cod == $codigo) echo "<option value='".$cod."' selected>".$v[$i]."</option>";
				$i++;
			}
			break;
	}
}

//	FUNCION PARA IMPRIMIR EN UNA TABLA VALORES
function printValores($tabla, $codigo) {
	switch ($tabla) {
		case "ESTADO":
			$c[0] = "A"; $v[0] = "Activo";
			$c[1] = "I"; $v[1] = "Inactivo";
			break;
		
		case "ESTADO-REQUERIMIENTO":
			$c[0] = "PR"; $v[0] = "En Preparación";
			$c[1] = "RV"; $v[1] = "Revisado";
			$c[2] = "CN"; $v[2] = "Conformado";
			$c[3] = "AP"; $v[3] = "Aprobado";
			$c[4] = "AN"; $v[4] = "Anulado";
			$c[5] = "RE"; $v[5] = "Rechazado";
			$c[6] = "CE"; $v[6] = "Cerrado";
			$c[7] = "CO"; $v[7] = "Completado";
			break;
		
		case "ESTADO-REQUERIMIENTO-DETALLE":
			$c[0] = "PR"; $v[0] = "En Preparación";
			$c[1] = "PE"; $v[1] = "Pendiente";
			$c[2] = "AN"; $v[2] = "Anulado";
			$c[3] = "RE"; $v[3] = "Rechazado";
			$c[4] = "CE"; $v[4] = "Cerrado";
			$c[5] = "CO"; $v[5] = "Completado";
			break;
		
		case "DIRIGIDO":
			$c[0] = "C"; $v[0] = "Compras";
			$c[1] = "A"; $v[1] = "Almacen";
			break;
		
		case "PRIORIDAD":
			$c[0] = "N"; $v[0] = "Normal";
			$c[1] = "U"; $v[1] = "Urgente";
			$c[2] = "M"; $v[2] = "Muy Urgente";
			break;
		
		case "ESTADO-COMPRA":
			$c[0] = "PR"; $v[0] = "En Preparación";
			$c[1] = "PE"; $v[1] = "Pendiente";
			$c[2] = "RV"; $v[2] = "Revisado";
			$c[3] = "AP"; $v[3] = "Aprobado";
			$c[4] = "AN"; $v[4] = "Anulado";
			$c[5] = "RE"; $v[5] = "Rechazado";
			$c[6] = "CE"; $v[6] = "Cerrado";
			$c[7] = "CO"; $v[7] = "Completado";
			break;
		
		case "ESTADO-COMPRA-DETALLE":
			$c[0] = "PR"; $v[0] = "En Preparación";
			$c[1] = "PE"; $v[1] = "Pendiente";
			$c[2] = "AN"; $v[2] = "Anulado";
			$c[3] = "CE"; $v[3] = "Cerrado";
			$c[4] = "CO"; $v[4] = "Completado";
			break;
		
		case "ESTADO-SERVICIO":
			$c[0] = "PR"; $v[0] = "En Preparación";
			$c[1] = "RV"; $v[1] = "Revisado";
			$c[2] = "AP"; $v[2] = "Aprobado";
			$c[3] = "AN"; $v[3] = "Anulado";
			$c[4] = "RE"; $v[4] = "Rechazado";
			$c[5] = "CE"; $v[5] = "Cerrado";
			$c[6] = "CO"; $v[6] = "Completado";
			break;
		
		case "ESTADO-DOCUMENTO":
			$c[0] = "PR"; $v[0] = "Pendiente";
			$c[1] = "RV"; $v[1] = "Facturado";
			break;
		
		case "COMPRA-CLASIFICACION":
			$c[0] = "L"; $v[0] = "O/C Local";
			$c[1] = "F"; $v[1] = "O/C Foráneo";
			break;
	}
	
	$i=0;
	foreach ($c as $cod) {
		if ($cod == $codigo) return $v[$i];
		$i++;
	}
}

//	FUNCION PARA CARGAR SELECTS
function loadSelectClasificacion($codigo, $opt) {
	switch ($opt) {
		case 0:
			$sql = "SELECT Clasificacion, Descripcion FROM lg_clasificacion WHERE Clasificacion <> 'RAU' ORDER BY Clasificacion";
			$query = mysql_query($sql) or die ($sql.mysql_error());
			while ($field = mysql_fetch_array($query)) {
				if ($field[0] == $codigo) { ?><option value="<?=$field[0]?>" selected="selected"><?=($field[1])?></option><? }
				else { ?><option value="<?=$field[0]?>"><?=($field[1])?></option><? }
			}
			break;
			
		case 1:
			$sql = "SELECT Clasificacion, Descripcion FROM lg_clasificacion WHERE Clasificacion = '".$codigo."' ORDER BY Clasificacion";
			$query = mysql_query($sql) or die ($sql.mysql_error());
			while ($field = mysql_fetch_array($query)) {
				?><option value="<?=$field[0]?>" selected="selected"><?=($field[1])?></option><?
			}
			break;
	}
}

//	FUNCION PARA IMPRIMIR UN CHECK
function printFlagCompras($check) {
	if ($check == "O") $flag = "<img src='../imagenes/flag.png' />";
	return $flag;
}

//	funcion para verificar disponibiliadad presupuestaria de las cotizaciones
function verificarDisponibilidadPresupuestariaOC($anio, $organismo, $codigo, $detalles, $monto_impuestos, $tabla) {
	global $_PARAMETRO;
	$disponible = true;
	//	recorro los detalles de los items o de los commodities
	if ($tabla == "item") {
		$secuencia = 0;
		$detalle = split(";", $detalles);
		foreach ($detalle as $linea) {
			$secuencia++;
			list($_codigo, $_descripcion, $_codunidad, $_cantidad, $_pu, $_descp, $_descf, $_flagexon, $_pu_total, $_total, $_fentrega, $_codccosto, $_codpartida, $_codcuenta, $_observaciones) = split("[|]", $linea);
			if ($secuencia == 1) $filtro_det .= " i.CodItem = '".$_codigo."'";
			else $filtro_det .= " OR i.CodItem = '".$_codigo."'";
			$partida[$_codpartida] += ($_cantidad * $_pu);
		}
		$sql = "(SELECT
					do.cod_partida,
					pc.denominacion,
					pvp.EjercicioPpto,
					(pvpd.MontoAjustado - pvpd.MontoCompromiso + do.Monto) AS MontoDisponible
				 FROM
					lg_distribucionoc do
					INNER JOIN pv_partida pc ON (do.cod_partida = pc.cod_partida)
					LEFT JOIN pv_presupuesto pvp ON (do.CodOrganismo = pvp.Organismo AND
													 do.Anio = pvp.EjercicioPpto)
					LEFT JOIN pv_presupuestodet pvpd ON (pc.cod_partida = pvpd.cod_partida AND
													 	 pvp.CodPresupuesto = pvpd.CodPresupuesto AND
														 pvp.Organismo = pvpd.Organismo)
				 WHERE
					do.Anio = '".$anio."' AND
					do.CodOrganismo = '".$organismo."' AND
					do.NroOrden = '".$codigo."' AND
					pc.cod_partida <> '".$_PARAMETRO['IVADEFAULT']."')
					
				UNION
				
				(SELECT
					p.cod_partida,
					p.denominacion,
					pvp.EjercicioPpto,
					(pvpd.MontoAjustado - pvpd.MontoCompromiso) AS MontoDisponible
				 FROM
					lg_itemmast i
					INNER JOIN pv_partida p ON (i.PartidaPresupuestal = p.cod_partida)
					LEFT JOIN pv_presupuesto pvp ON (pvp.Organismo = '".$organismo."' AND
													 pvp.EjercicioPpto = '".$anio."')
					LEFT JOIN pv_presupuestodet pvpd ON (p.cod_partida = pvpd.cod_partida AND
													 	 pvp.CodPresupuesto = pvpd.CodPresupuesto AND
														 pvp.Organismo = pvpd.Organismo)
				 WHERE
				 	1 AND ($filtro_sel $filtro_det) AND
					p.cod_partida NOT IN (SELECT do1.cod_partida
										  FROM
												lg_distribucionoc do1
												INNER JOIN pv_partida pc1 ON (do1.cod_partida = pc1.cod_partida)
												LEFT JOIN pv_presupuesto pvp1 ON (pvp1.Organismo = do1.CodOrganismo AND
																				  pvp1.EjercicioPpto = do1.Anio)
												LEFT JOIN pv_presupuestodet pvpd1 ON (pc1.cod_partida = pvpd1.cod_partida AND 
																				  	  pvp1.CodPresupuesto = pvpd1.CodPresupuesto AND
																					  pvp1.Organismo = pvpd1.Organismo)
										  WHERE
												do1.Anio = '".$anio."' AND
												do1.CodOrganismo = '".$organismo."' AND
												do1.NroOrden = '".$codigo."' AND
												pc1.cod_partida <> '".$_PARAMETRO['IVADEFAULT']."')					
				 GROUP BY cod_partida)
				
				ORDER BY cod_partida";
	} else {
		$secuencia = 0;
		$detalle = split(";", $detalles);
		foreach ($detalle as $linea) {
			$secuencia++;
			list($_codigo, $_descripcion, $_codunidad, $_cantidad, $_pu, $_descp, $_descf, $_flagexon, $_pu_total, $_total, $_fentrega, $_codccosto, $_codpartida, $_codcuenta, $_observaciones) = split("[|]", $linea);
			list($_commodity, $_secuencia) = split("[.]", $_codigo);
			if ($secuencia == 1) $filtro_det .= " cs.Codigo = '".$_commodity."'";
			else $filtro_det .= " OR cs.Codigo = '".$_commodity."'";
			$partida[$_codpartida] += ($_cantidad * $_pu);			
		}
		$sql = "(SELECT 
					do.cod_partida,
					pc.denominacion,
					pvp.EjercicioPpto,
					(pvpd.MontoAjustado - pvpd.MontoCompromiso + do.Monto) AS MontoDisponible
				 FROM
					lg_distribucionoc do
					INNER JOIN pv_partida pc ON (do.cod_partida = pc.cod_partida)
					LEFT JOIN pv_presupuesto pvp ON (do.CodOrganismo = pvp.Organismo AND
													 do.Anio = pvp.EjercicioPpto)
					LEFT JOIN pv_presupuestodet pvpd ON (pc.cod_partida = pvpd.cod_partida AND 
													 	 pvp.CodPresupuesto = pvpd.CodPresupuesto AND
														 pvp.Organismo = pvpd.Organismo)
				 WHERE
					do.Anio = '".$anio."' AND
					do.CodOrganismo = '".$organismo."' AND
					do.NroOrden = '".$codigo."' AND
					pc.cod_partida <> '".$_PARAMETRO['IVADEFAULT']."')
					
				UNION
				
				(SELECT
					p.cod_partida,
					p.denominacion,
					pvp.EjercicioPpto,
					(pvpd.MontoAjustado - pvpd.MontoCompromiso) AS MontoDisponible
				 FROM
					lg_commoditysub cs
					INNER JOIN pv_partida p ON (cs.cod_partida = p.cod_partida)
					LEFT JOIN pv_presupuesto pvp ON (pvp.Organismo = '".$organismo."' AND
													 pvp.EjercicioPpto = '".$anio."')
					LEFT JOIN pv_presupuestodet pvpd ON (p.cod_partida = pvpd.cod_partida AND
													 	 pvp.CodPresupuesto = pvpd.CodPresupuesto AND
														 pvp.Organismo = pvpd.Organismo)
				 WHERE 
				 	1 AND ($filtro_sel $filtro_det) AND
					p.cod_partida NOT IN (SELECT do1.cod_partida
										  FROM
												lg_distribucionoc do1
												INNER JOIN pv_partida pc1 ON (do1.cod_partida = pc1.cod_partida)
												LEFT JOIN pv_presupuesto pvp1 ON (do1.CodOrganismo = pvp1.Organismo AND
																				  do1.Anio = pvp1.EjercicioPpto)
												LEFT JOIN pv_presupuestodet pvpd1 ON (pc1.cod_partida = pvpd1.cod_partida AND 
																				  	  pvp1.CodPresupuesto = pvpd1.CodPresupuesto AND
																					  pvp1.Organismo = pvpd1.Organismo)
										  WHERE
												do1.Anio = '".$anio."' AND
												do1.CodOrganismo = '".$organismo."' AND
												do1.NroOrden = '".$codigo."' AND
												pc1.cod_partida <> '".$_PARAMETRO['IVADEFAULT']."')					
				 GROUP BY cod_partida)
				
				ORDER BY cod_partida";
	}
	
	//	consulto partidas	
	$query_general = mysql_query($sql) or die ($sql.mysql_error());
	while($field_general = mysql_fetch_array($query_general)) {
		$codpartida = $field_general['cod_partida'];
		$resta = $field_general['MontoDisponible'] - $partida[$codpartida];
		if ($resta < 0) $disponible = false;
	}
	
	//	consulto partida igv
	if ($monto_impuestos > 0) {
		//	si ya tiene distribuido algun monto en el igv lo obtengo
		$sql = "SELECT do.Monto
				FROM lg_distribucionoc do
				WHERE
					do.Anio = '".$anio."' AND
					do.CodOrganismo = '".$organismo."' AND
					do.NroOrden = '".$codigo."' AND
					do.cod_partida = '".$_PARAMETRO['IVADEFAULT']."'";
		$query_igv = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_igv) != 0) $field_igv = mysql_fetch_array($query_general);
		$montoigv = floatval($field_igv['Monto']);
		
		//	obtengo la disponibilidad de la partida del igv		
		$sql = "SELECT
					p.cod_partida,
					p.denominacion,
					(pvpd.MontoAjustado - pvpd.MontoCompromiso + $montoigv) AS MontoDisponible
				FROM
					pv_partida p
					LEFT JOIN pv_presupuesto pvp ON (pvp.Organismo = '".$organismo."' AND
													 pvp.EjercicioPpto = '".$anio."')
					LEFT JOIN pv_presupuestodet pvpd ON (p.cod_partida = pvpd.cod_partida AND
														 pvp.CodPresupuesto = pvpd.CodPresupuesto AND
														 pvp.Organismo = pvpd.Organismo)
				WHERE p.cod_partida = '".$_PARAMETRO['IVADEFAULT']."'";
		$query_general = mysql_query($sql) or die ($sql.mysql_error());
		while($field_general = mysql_fetch_array($query_general)) {
			$resta = $field_general['MontoDisponible'] - $monto_impuestos;
			if ($resta < 0) $disponible = false;
		}
	}
	return $disponible;
}

//	funcion para verificar disponibiliadad presupuestaria de las cotizaciones
function verificarDisponibilidadPresupuestariaOS($anio, $organismo, $codigo, $detalles, $monto_impuestos) {
	global $_PARAMETRO;
	$disponible = true;
	//	recorro los detalles de los items o de los commodities	
	$secuencia = 0;
	$detalle = split(";", $detalles);
	foreach ($detalle as $linea) {
		$secuencia++;
		list($_codigo, $_descripcion, $_cantidad, $_pu, $_total, $_fesperada, $_freal, $_codccosto, $_activo, $_flagterminado, $_codpartida, $_codcuenta, $_observaciones) = split("[|]", $linea);
		list($_commodity, $_secuencia) = split("[.]", $_codigo);
		if ($secuencia == 1) $filtro_det .= " cs.Codigo = '".$_commodity."'";
		else $filtro_det .= " OR cs.Codigo = '".$_commodity."'";
		$partida[$_codpartida] += ($_cantidad * $_pu);
	}
	$sql = "(SELECT 
				do.cod_partida,
				pc.denominacion,
				pvp.EjercicioPpto,
				(pvpd.MontoAjustado - pvpd.MontoCompromiso + do.Monto) AS MontoDisponible
			 FROM
				lg_distribucionoc do
				INNER JOIN pv_partida pc ON (do.cod_partida = pc.cod_partida)
				LEFT JOIN pv_presupuesto pvp ON (do.CodOrganismo = pvp.Organismo AND
												 do.Anio = pvp.EjercicioPpto)
				LEFT JOIN pv_presupuestodet pvpd ON (pc.cod_partida = pvpd.cod_partida AND
													 pvp.CodPresupuesto = pvpd.CodPresupuesto AND
													 pvp.Organismo = pvpd.Organismo)
			 WHERE
				do.Anio = '".$anio."' AND
				do.CodOrganismo = '".$organismo."' AND
				do.NroOrden = '".$codigo."' AND
				pc.cod_partida <> '".$_PARAMETRO['IVADEFAULT']."')
				
			UNION
			
			(SELECT
				p.cod_partida,
				p.denominacion,
				pvp.EjercicioPpto,
				(pvpd.MontoAjustado - pvpd.MontoCompromiso) AS MontoDisponible
			 FROM
				lg_commoditysub cs
				INNER JOIN pv_partida p ON (cs.cod_partida = p.cod_partida)
				LEFT JOIN pv_presupuesto pvp ON (pvp.Organismo = '".$organismo."' AND
												 pvp.EjercicioPpto = '".$anio."')
				LEFT JOIN pv_presupuestodet pvpd ON (p.cod_partida = pvpd.cod_partida AND
												 	 pvp.CodPresupuesto = pvpd.CodPresupuesto AND
													 pvp.Organismo = pvpd.Organismo)
			 WHERE 
				1 AND ($filtro_sel $filtro_det) AND
				p.cod_partida NOT IN (SELECT do1.cod_partida
									  FROM
											lg_distribucionoc do1
											INNER JOIN pv_partida pc1 ON (do1.cod_partida = pc1.cod_partida)
											LEFT JOIN pv_presupuesto pvp1 ON (do1.CodOrganismo = pvp1.Organismo AND
																			  do1.Anio = pvp1.EjercicioPpto)
											LEFT JOIN pv_presupuestodet pvpd1 ON (pc1.cod_partida = pvpd1.cod_partida AND 
																			  	  pvp1.CodPresupuesto = pvpd1.CodPresupuesto AND
																				  pvp1.Organismo = pvpd1.Organismo)
									  WHERE
											do1.Anio = '".$anio."' AND
											do1.CodOrganismo = '".$organismo."' AND
											do1.NroOrden = '".$codigo."' AND
											pc1.cod_partida <> '".$_PARAMETRO['IVADEFAULT']."')					
			 GROUP BY cod_partida)
			
			ORDER BY cod_partida";
	$query_general = mysql_query($sql) or die ($sql.mysql_error());
	while($field_general = mysql_fetch_array($query_general)) {
		$codpartida = $field_general['cod_partida'];
		$resta = $field_general['MontoDisponible'] - $partida[$codpartida];
		if ($resta < 0) $disponible = false;
	}
	
	//	consulto partida igv
	if ($monto_impuestos > 0) {
		//	si ya tiene distribuido algun monto en el igv lo obtengo
		$sql = "SELECT do.Monto
				FROM lg_distribucionoc do
				WHERE
					do.Anio = '".$anio."' AND
					do.CodOrganismo = '".$organismo."' AND
					do.NroOrden = '".$codigo."' AND
					do.cod_partida = '".$_PARAMETRO['IVADEFAULT']."'";
		$query_igv = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_igv) != 0) $field_igv = mysql_fetch_array($query_general);
		$montoigv = floatval($field_igv['Monto']);
		
		//	obtengo la disponibilidad de la partida del igv		
		$sql = "SELECT
					p.cod_partida,
					p.denominacion,
					(pvpd.MontoAjustado - pvpd.MontoCompromiso + $montoigv) AS MontoDisponible
				FROM
					pv_partida p
					LEFT JOIN pv_presupuesto pvp ON (pvp.Organismo = '".$organismo."' AND
													 pvp.EjercicioPpto = '".$anio."')
					LEFT JOIN pv_presupuestodet pvpd ON (p.cod_partida = pvpd.cod_partida AND
														 pvp.CodPresupuesto = pvpd.CodPresupuesto AND
														 pvp.Organismo = pvpd.Organismo)
				WHERE p.cod_partida = '".$_PARAMETRO['IVADEFAULT']."'";
		$query_general = mysql_query($sql) or die ($sql.mysql_error());
		while($field_general = mysql_fetch_array($query_general)) {
			$resta = $field_general['MontoDisponible'] - $monto_impuestos;
			if ($resta < 0) $disponible = false;
		}
	}
	return $disponible;
}

function estiloGrupo($j)
{
	$mod = $j;
	
	if($mod % 2 == 0)
	{
   		$estilo = "estiloGrupo1";
		
	} else {
		
       	$estilo = "estiloGrupo2";
	}
	
	return $estilo;
} 

?>